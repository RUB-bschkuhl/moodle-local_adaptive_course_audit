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

use local_adaptive_course_audit\tour\manager as tour_manager;
use tool_usertours\step as usertour_step;
use tool_usertours\tour as usertour;

/**
 * Event observer callbacks for the Adaptive course audit plugin.
 *
 * @package     local_adaptive_course_audit
 */
final class observer
{
    /**
     * Queue deletion for all plugin-owned action/sub tours belonging to the same course as the main tour.
     *
     * Action tours are linked via configdata:
     * - local_adaptive_course_audit_action = 1
     * - local_adaptive_course_audit_courseid = <courseid>
     *
     * @param int $maintourid The main tour id which just completed.
     * @return void
     */
    private static function queue_related_subtour_deletions(int $maintourid): void
    {
        global $DB;

        $courseid = 0;
        try {
            $mapping = $DB->get_record(
                'local_adaptive_course_tour',
                ['tourid' => $maintourid],
                'courseid',
                IGNORE_MISSING
            );
            if (!empty($mapping) && !empty($mapping->courseid)) {
                $courseid = (int)$mapping->courseid;
            }
        } catch (\Throwable $exception) {
            debugging('Error resolving course mapping for adaptive main tour: ' . $exception->getMessage(), DEBUG_DEVELOPER);
            return;
        }

        if ($courseid <= 0) {
            return;
        }

        $records = [];
        try {
            $records = $DB->get_records_sql(
                'SELECT id, configdata FROM {tool_usertours_tours} WHERE configdata LIKE ?',
                ['%"local_adaptive_course_audit_action"%']
            );
        } catch (\Throwable $exception) {
            debugging('Error fetching adaptive action tours for subtour cleanup: ' . $exception->getMessage(), DEBUG_DEVELOPER);
            return;
        }

        if (empty($records)) {
            return;
        }

        $seen = [];
        $manager = new tour_manager();
        foreach ($records as $record) {
            $tourid = (int)($record->id ?? 0);
            if ($tourid <= 0 || $tourid === $maintourid) {
                continue;
            }
            if (isset($seen[$tourid])) {
                continue;
            }

            $config = null;
            if (!empty($record->configdata)) {
                $config = json_decode((string)$record->configdata, true);
            }
            if (!is_array($config) || empty($config['local_adaptive_course_audit_action'])) {
                continue;
            }
            if (
                empty($config['local_adaptive_course_audit_courseid'])
                || (int)$config['local_adaptive_course_audit_courseid'] !== $courseid
            ) {
                continue;
            }

            $seen[$tourid] = true;
            try {
                $manager->delete_tour($tourid);
            } catch (\Throwable $exception) {
                debugging('Error deleting adaptive action tour: ' . $exception->getMessage(), DEBUG_DEVELOPER);
            }
        }
    }

    /**
     * Triggered when a user tour ends (completed or exited early).
     *
     * Behaviour depends on whether this is the main tour (stored in our mapping table)
     * or an action/sub tour (marked in configdata), and whether it ended early.
     *
     * @param \tool_usertours\event\tour_ended $event The event data.
     * @return void
     */
    public static function tour_ended(\tool_usertours\event\tour_ended $event): void
    {
        global $DB;

        $data = $event->get_data();
        $tourid = (int)($data['objectid'] ?? 0);
        $stepindex = isset($data['other']['stepindex']) ? (int)$data['other']['stepindex'] : -1;
        $userid = (int)($data['userid'] ?? 0);

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

        // TODO for now just delete tours when they end.
        // Main tours: if ended early, retrigger by bumping majorupdatetime; otherwise delete everything.
        // if ($ismain && !$completed) {
        //     // Remove all steps up to (and including) the current step, except the first step.
        //     // This allows a reload to start at the next step (after the intro step).
        //     if ($stepindex > 0) {
        //         try {
        //             $steps = $DB->get_records(
        //                 'tool_usertours_steps',
        //                 ['tourid' => $tourid],
        //                 'sortorder ASC, id ASC',
        //                 'id'
        //             );
        //             $steps = array_values($steps);

        //             // Delete steps 1..$stepindex (inclusive), but never beyond the step list.
        //             $maxindex = min($stepindex, count($steps) - 1);
        //             for ($index = 1; $index <= $maxindex; $index++) {
        //                 $stepid = (int)($steps[$index]->id ?? 0);
        //                 if ($stepid > 0) {
        //                     usertour_step::instance($stepid)->remove();
        //                 }
        //             }
        //         } catch (\Throwable $exception) {
        //             debugging('Error pruning steps for adaptive main tour: ' . $exception->getMessage(), DEBUG_DEVELOPER);
        //         }
        //     }

        //     try {
        //         $tour = usertour::instance($tourid);
        //         $tour->set_config('majorupdatetime', time());
        //         $tour->persist();
        //     } catch (\Throwable $exception) {
        //         debugging('Error updating majorupdatetime for adaptive main tour: ' . $exception->getMessage(), DEBUG_DEVELOPER);
        //     }
        //     return;
        // }

        // Sub tours: always delete only the sub tour; Main tours: delete only when completed.
        // if ($issubtour || ($ismain && $completed)) {
        // if ($ismain && $completed) {
        if ($ismain) {
            self::queue_related_subtour_deletions($tourid);

            // Remove the stored "resume audit" marker once the audit tour ends for this user.
            if ($userid > 0) {
                try {
                    $mapping = $DB->get_record(
                        'local_adaptive_course_tour',
                        ['tourid' => $tourid],
                        'courseid',
                        IGNORE_MISSING
                    );
                    $courseid = !empty($mapping) && !empty($mapping->courseid) ? (int)$mapping->courseid : 0;
                    if ($courseid > 0) {
                        $DB->delete_records('local_adaptive_course_review', [
                            'courseid' => $courseid,
                            'userid' => $userid,
                        ]);
                    }
                } catch (\Throwable $exception) {
                    debugging('Error deleting adaptive course audit review start marker: ' . $exception->getMessage(), DEBUG_DEVELOPER);
                }
            }
        }
        try {
            $manager = new tour_manager();
            $manager->delete_tour($tourid);
        } catch (\Throwable $exception) {
            debugging('Error deleting adaptive course audit tour: ' . $exception->getMessage(), DEBUG_DEVELOPER);
        }
        // }
    }
}
