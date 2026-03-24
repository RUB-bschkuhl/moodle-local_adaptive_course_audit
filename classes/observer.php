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
     * Delete plugin-owned action/sub tours that are linked to the given main tour.
     *
     * New-style linkage (minimalist sequence tours): the subtour stores
     * local_adaptive_course_audit_prev_tourid = <maintourid> and is only deleted
     * when that exact main tour ends.
     *
     * Old-style linkage (regular audit action tours): no prev_tourid is set, so the
     * subtour is matched by local_adaptive_course_audit_courseid instead (backward compat).
     *
     * @param int $maintourid The main tour id which just ended.
     * @return void
     */
    private static function queue_related_subtour_deletions(int $maintourid): void
    {
        global $DB;

        // Resolve courseid from the mapping table (needed for old-style backward compat).
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

            $prevtourid = (int)($config['local_adaptive_course_audit_prev_tourid'] ?? 0);

            if ($prevtourid > 0) {
                // New-style: only delete this subtour if it was explicitly linked to $maintourid.
                // This prevents a T1-end from accidentally deleting Subtour B (linked to T2).
                if ($prevtourid !== $maintourid) {
                    continue;
                }
            } else {
                // Old-style (regular audit tours): match by courseid for backward compatibility.
                if (
                    $courseid <= 0
                    || empty($config['local_adaptive_course_audit_courseid'])
                    || (int)$config['local_adaptive_course_audit_courseid'] !== $courseid
                ) {
                    continue;
                }
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
        $config = [];
        $courseid = 0;

        try {
            $ismain = $DB->record_exists('local_adaptive_course_tour', ['tourid' => $tourid]);
        } catch (\Throwable $exception) {
            debugging('Error checking adaptive course audit tour mapping: ' . $exception->getMessage(), DEBUG_DEVELOPER);
        }

        try {
            $record = $DB->get_record('tool_usertours_tours', ['id' => $tourid], 'id, configdata', IGNORE_MISSING);
            if (!empty($record) && !empty($record->configdata)) {
                $decoded = json_decode((string)$record->configdata, true);
                if (is_array($decoded)) {
                    $config = $decoded;
                    $ownedbyplugin = !empty($config['local_adaptive_course_audit']) || !empty($config['local_adaptive_course_audit_action']);
                    $issubtour = !empty($config['local_adaptive_course_audit_action']);
                    $courseid = (int)($config['local_adaptive_course_audit_courseid'] ?? 0);
                    $prevtourid = (int)($config['local_adaptive_course_audit_prev_tourid'] ?? 0);
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

        // $totalsteps = 0;
        // try {
        //     $totalsteps = (int)$DB->count_records('tool_usertours_steps', ['tourid' => $tourid]);
        // } catch (\Throwable $exception) {
        //     debugging('Error counting tour steps for adaptive course audit: ' . $exception->getMessage(), DEBUG_DEVELOPER);
        //     $totalsteps = 0;
        // }

        // $laststepindex = $totalsteps > 0 ? ($totalsteps - 1) : -1;
        // $completed = ($laststepindex >= 0) && ($stepindex >= $laststepindex);

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

        if ($issubtour) {
            // Only used in scenario tours
            if ($prevtourid > 0) {
                try {
                    $prevmanager = new tour_manager();
                    $prevmanager->delete_tour($prevtourid);
                    // Find the next sequence tour by looking for another action tour
                    // whose prev_tourid points to a different (still existing) sequence tour.

                    // TODO problem when multiple people start tours in same course at the same time
                    $nexttour = $DB->get_record_sql(
                        'SELECT id, configdata FROM {tool_usertours_tours} WHERE configdata LIKE ? AND configdata NOT LIKE ? AND configdata LIKE ?',
                        [
                            '%"local_adaptive_course_audit_courseid":' . $courseid . '%',
                            '%"local_adaptive_course_audit_action":1%',
                            '%"local_adaptive_course_audit_prev_tourid":' . $prevtourid . '%',
                        ]
                    );
                    //TODO WÄHLE ALLE tool_usertours_tours AUS WO DIE KURSID PASST, KANN ICH AUS AKTUELL GELÖSCHTER TOUR HOLEN GGF, UND local_adaptive_course_audit_prev_tourid existiert 
                    //WÄHLE SO DIE NÄCHSTE TOUR AUS DIE GEStARTET WIRD, NICHT INDEM DIE ACTION TOUR DIE DANACH KOMMEN KÖNNTE DIESE BESTIMMT!
                    $nexttourid = 0;
                    if (!empty($nexttour)) {
                        $nexttourid = (int)$nexttour->id;
                    }

                    // Update the course→tour mapping so lib.php picks up the next sequence tour.
                    if ($nexttourid > 0 && $courseid > 0) {
                        //Add to local_adaptive_course_tour table an entry like the following, not update, use the functions that exist dont manipulate the db itself
                        //| id  | courseid | tourid | timecreated | timemodified |
                        //| 102 |        2 |    328 |  1772182732 |   1772182732 |
                        $DB->insert_record('local_adaptive_course_tour', [
                            'courseid' => $courseid,
                            'tourid' => $nexttourid,
                            'timecreated' => time(),
                            'timemodified' => time(),
                        ]);
                    }
                } catch (\Throwable $exception) {
                    debugging('Error deleting prev tour / updating next tour mapping after subtour end: ' . $exception->getMessage(), DEBUG_DEVELOPER);
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
