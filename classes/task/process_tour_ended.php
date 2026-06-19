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

namespace local_adaptive_course_audit\task;

use core\task\adhoc_task;
use local_adaptive_course_audit\tour\manager as tour_manager;

/**
 * Ad-hoc task to process tour-ended cleanup.
 *
 * @package     local_adaptive_course_audit
 * @copyright   2025 Bastian Schmidt-Kuhl <bastian.schmidt-kuhl@ruhr-uni-bochum.de>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class process_tour_ended extends adhoc_task {
    /**
     * Execute the deferred tour-ended processing.
     *
     * @return void
     */
    public function execute(): void {
        global $DB;

        $data = $this->get_custom_data();
        $tourid = (int)($data->tourid ?? 0);
        $stepindex = (int)($data->stepindex ?? -1);
        $userid = (int)($data->userid ?? 0);

        if ($tourid <= 0) {
            return;
        }

        $ismain = (bool)($data->ismain ?? false);
        $issubtour = (bool)($data->issubtour ?? false);
        $config = isset($data->config) ? (array)$data->config : [];
        $courseid = (int)($config['local_adaptive_course_audit_courseid'] ?? 0);
        $prevtourid = (int)($config['local_adaptive_course_audit_prev_tourid'] ?? 0);

        if ($ismain) {
            $this->delete_related_subtours($tourid);

            if ($userid > 0) {
                try {
                    $mapping = $DB->get_record(
                        'local_adaptive_course_audit_tour',
                        ['tourid' => $tourid],
                        'courseid',
                        IGNORE_MISSING
                    );
                    $mcid = !empty($mapping) && !empty($mapping->courseid) ? (int)$mapping->courseid : 0;
                    if ($mcid > 0) {
                        $DB->delete_records('local_adaptive_course_audit_review', [
                            'courseid' => $mcid,
                            'userid' => $userid,
                        ]);
                    }
                } catch (\Throwable $exception) {
                    debugging(
                        'Error deleting adaptive course audit review start marker: ' . $exception->getMessage(),
                        DEBUG_DEVELOPER
                    );
                }
            }
        }

        if ($issubtour && $prevtourid > 0) {
            try {
                $prevmanager = new tour_manager();
                $prevmanager->delete_tour($prevtourid);

                $sql = 'SELECT id, configdata FROM {tool_usertours_tours}'
                    . ' WHERE configdata LIKE ? AND configdata NOT LIKE ? AND configdata LIKE ?';
                $nexttour = $DB->get_record_sql(
                    $sql,
                    [
                        '%"local_adaptive_course_audit_courseid":' . $courseid . '%',
                        '%"local_adaptive_course_audit_action":1%',
                        '%"local_adaptive_course_audit_prev_tourid":' . $prevtourid . '%',
                    ]
                );
                $nexttourid = 0;
                if (!empty($nexttour)) {
                    $nexttourid = (int)$nexttour->id;
                }

                if ($nexttourid > 0 && $courseid > 0) {
                    $DB->insert_record('local_adaptive_course_audit_tour', [
                        'courseid' => $courseid,
                        'tourid' => $nexttourid,
                        'timecreated' => time(),
                        'timemodified' => time(),
                    ]);
                }
            } catch (\Throwable $exception) {
                debugging(
                    'Error deleting prev tour / updating next tour mapping after subtour end: '
                        . $exception->getMessage(),
                    DEBUG_DEVELOPER
                );
            }
        }

        try {
            $manager = new tour_manager();
            $manager->delete_tour($tourid);
        } catch (\Throwable $exception) {
            debugging('Error deleting adaptive course audit tour: ' . $exception->getMessage(), DEBUG_DEVELOPER);
        }
    }

    /**
     * Delete plugin-owned subtours linked to the given main tour.
     *
     * @param int $maintourid
     * @return void
     */
    private function delete_related_subtours(int $maintourid): void {
        global $DB;

        $courseid = 0;
        try {
            $mapping = $DB->get_record(
                'local_adaptive_course_audit_tour',
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

        $manager = new tour_manager();
        foreach ($records as $record) {
            $tourid = (int)($record->id ?? 0);
            if ($tourid <= 0 || $tourid === $maintourid) {
                continue;
            }

            $config = null;
            if (!empty($record->configdata)) {
                $config = json_decode((string)$record->configdata, true);
            }
            if (!is_array($config) || empty($config['local_adaptive_course_audit_action'])) {
                continue;
            }

            $previd = (int)($config['local_adaptive_course_audit_prev_tourid'] ?? 0);

            if ($previd > 0) {
                if ($previd !== $maintourid) {
                    continue;
                }
            } else {
                if (
                    $courseid <= 0
                    || empty($config['local_adaptive_course_audit_courseid'])
                    || (int)$config['local_adaptive_course_audit_courseid'] !== $courseid
                ) {
                    continue;
                }
            }

            try {
                $manager->delete_tour($tourid);
            } catch (\Throwable $exception) {
                debugging('Error deleting adaptive action tour: ' . $exception->getMessage(), DEBUG_DEVELOPER);
            }
        }
    }
}
