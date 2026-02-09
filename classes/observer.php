<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

declare(strict_types=1);

namespace local_adaptive_course_audit;

defined('MOODLE_INTERNAL') || die();

use core\task\manager as task_manager;
use local_adaptive_course_audit\task\delete_tour;
use tool_usertours\step as usertour_step;
use tool_usertours\tour as usertour;

/**
 * Event observer callbacks for the Adaptive course audit plugin.
 *
 * @package     local_adaptive_course_audit
 */
final class observer {

    /**
     * Triggered when a user tour ends (completed or exited early).
     *
     * Behaviour depends on whether this is the main tour (stored in our mapping table)
     * or an action/sub tour (marked in configdata), and whether it ended early.
     *
     * @param \tool_usertours\event\tour_ended $event The event data.
     * @return void
     */
    public static function tour_ended(\tool_usertours\event\tour_ended $event): void {
        global $DB;

        $data = $event->get_data();
        $tourid = (int)($data['objectid'] ?? 0);
        $stepindex = isset($data['other']['stepindex']) ? (int)$data['other']['stepindex'] : -1;

        if ($tourid <= 0) {
            return;
        }

        // Determine whether this tour is "main" (mapped) or "action/sub tour" (config marker).
        $ismain = false;
        $issubtour = false;
        $ownedbyplugin = false;

        try {
            $ismain = $DB->record_exists('local_adaptive_course_tour', ['tourid' => $tourid]);
        } catch (\Throwable $exception) {
            debugging('Error checking adaptive course audit tour mapping: ' . $exception->getMessage(), DEBUG_DEVELOPER);
        }

        try {
            $record = $DB->get_record('tool_usertours_tours', ['id' => $tourid], 'id, configdata', IGNORE_MISSING);
            if (!empty($record) && !empty($record->configdata)) {
                $config = json_decode((string)$record->configdata, true);
                if (is_array($config)) {
                    $ownedbyplugin = !empty($config['local_adaptive_course_audit']) || !empty($config['local_adaptive_course_audit_action']);
                    $issubtour = !empty($config['local_adaptive_course_audit_action']);
                }
            }
        } catch (\Throwable $exception) {
            debugging('Error reading tour config for adaptive course audit: ' . $exception->getMessage(), DEBUG_DEVELOPER);
        }

        if ($ismain) {
            $ownedbyplugin = true;
        }

        if (!$ownedbyplugin) {
            return;
        }

        // Determine whether the tour ended on the final step.
        $totalsteps = 0;
        try {
            $totalsteps = (int)$DB->count_records('tool_usertours_steps', ['tourid' => $tourid]);
        } catch (\Throwable $exception) {
            debugging('Error counting tour steps for adaptive course audit: ' . $exception->getMessage(), DEBUG_DEVELOPER);
            $totalsteps = 0;
        }

        $laststepindex = $totalsteps > 0 ? ($totalsteps - 1) : -1;
        $completed = ($laststepindex >= 0) && ($stepindex >= $laststepindex);
        $endedearly = !$completed;

        // Main tours: if ended early, retrigger by bumping majorupdatetime; otherwise delete everything.
        if ($ismain && $endedearly) {
            // Remove all steps up to (and including) the current step, except the first step.
            // This allows a reload to start at the next step (after the intro step).
            if ($stepindex > 0) {
                try {
                    $steps = $DB->get_records(
                        'tool_usertours_steps',
                        ['tourid' => $tourid],
                        'sortorder ASC, id ASC',
                        'id'
                    );
                    $steps = array_values($steps);

                    // Delete steps 1..$stepindex (inclusive), but never beyond the step list.
                    $maxindex = min($stepindex, count($steps) - 1);
                    for ($index = 1; $index <= $maxindex; $index++) {
                        $stepid = (int)($steps[$index]->id ?? 0);
                        if ($stepid > 0) {
                            usertour_step::instance($stepid)->remove();
                        }
                    }
                } catch (\Throwable $exception) {
                    debugging('Error pruning steps for adaptive main tour: ' . $exception->getMessage(), DEBUG_DEVELOPER);
                }
            }

            try {
                $tour = usertour::instance($tourid);
                $tour->set_config('majorupdatetime', time());
                $tour->persist();
            } catch (\Throwable $exception) {
                debugging('Error updating majorupdatetime for adaptive main tour: ' . $exception->getMessage(), DEBUG_DEVELOPER);
            }
            return;
        }

        // Sub tours: always delete only the sub tour; Main tours: delete only when completed.
        if ($issubtour || ($ismain && $completed)) {
            $task = new delete_tour();
            $task->set_custom_data((object)[
                'tourid' => $tourid,
            ]);
            $task->set_component('local_adaptive_course_audit');

            try {
                task_manager::queue_adhoc_task($task);
            } catch (\Throwable $exception) {
                debugging('Error queuing adaptive course audit tour deletion: ' . $exception->getMessage(), DEBUG_DEVELOPER);
            }
        }
    }
}

