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

defined('MOODLE_INTERNAL') || die();

use core\task\scheduled_task;
use local_adaptive_course_audit\tour\manager as tour_manager;

/**
 * Scheduled cleanup task for stale plugin-owned user tours.
 *
 * This is a safety net in addition to the ad-hoc deletion queued when a tour ends.
 *
 * @package     local_adaptive_course_audit
 */
final class cleanup_tours extends scheduled_task {

    /** @var int Retention period for stale mappings (12 hours). */
    private const RETENTION_SECONDS = 12 * HOURSECS; 
    /** @var string Config marker for action/subtours owned by this plugin. */
    private const ACTION_TOUR_CONFIG_MARKER = 'local_adaptive_course_audit_action';
    /** @var string Table storing latest audit-review starts per user/course. */
    private const REVIEW_START_TABLE = 'local_adaptive_course_review';
    /** @var string Core usertours config key for last major update timestamp. */
    private const TOUR_MAJOR_UPDATE_TIME_CONFIG = 'majorupdatetime';
    /** @var string Config key holding a plugin-set tour creation timestamp. */
    private const PLUGIN_TOUR_TIMECREATED_CONFIG = 'local_adaptive_course_audit_timecreated';

    /**
     * Get a descriptive task name for the admin UI.
     *
     * @return string
     */
    public function get_name(): string {
        return get_string('task_cleanup_tours', 'local_adaptive_course_audit');
    }

    /**
     * Execute the scheduled cleanup.
     *
     * @return void
     */
    public function execute(): void {
        global $DB;

        $cutoff = time() - self::RETENTION_SECONDS;
        // Moodle requires unique named query params, even if the value is identical.
        $select = 'timemodified <= :cutoff1 OR timecreated <= :cutoff2';
        $params = [
            'cutoff1' => $cutoff,
            'cutoff2' => $cutoff,
        ];

        $records = [];
        try {
            $records = $DB->get_records_select(
                'local_adaptive_course_tour',
                $select,
                $params,
                'timemodified ASC, id ASC',
                'id, tourid'
            );
        } catch (\Throwable $exception) {
            debugging('Error loading stale adaptive course audit tour mappings: ' . $exception->getMessage(), DEBUG_DEVELOPER);
            $records = [];
        }

        $manager = new tour_manager();

        foreach ($records as $record) {
            $tourid = (int)($record->tourid ?? 0);
            if ($tourid > 0) {
                try {
                    $manager->delete_tour($tourid);
                } catch (\Throwable $exception) {
                    debugging('Error deleting stale adaptive course audit tour via cleanup task: ' . $exception->getMessage(), DEBUG_DEVELOPER);
                }
            }

            // Always remove the mapping row as it is stale (even if the tour is already gone).
            try {
                $DB->delete_records('local_adaptive_course_tour', ['id' => (int)$record->id]);
            } catch (\Throwable $exception) {
                debugging('Error deleting stale adaptive course audit tour mapping: ' . $exception->getMessage(), DEBUG_DEVELOPER);
            }
        }

        // Also remove stale action tours (subtours) which are not stored in local_adaptive_course_tour.
        $this->cleanup_stale_action_tours($manager, $cutoff);

        // Clean up stale "latest audit review start" markers.
        try {
            $DB->delete_records_select(self::REVIEW_START_TABLE, $select, $params);
        } catch (\Throwable $exception) {
            debugging('Error deleting stale adaptive course review start records: ' . $exception->getMessage(), DEBUG_DEVELOPER);
        }
    }

    /**
     * Remove stale action tours owned by this plugin.
     *
     * Action tours are identified via a config marker stored in tool_usertours_tours.configdata.
     * We determine staleness using:
     * - timecreated/timemodified columns (if the Moodle version provides them), else
     * - a plugin-set config timestamp stored in configdata.
     *
     * @param tour_manager $manager
     * @param int $cutoff
     * @return void
     */
    private function cleanup_stale_action_tours(tour_manager $manager, int $cutoff): void {
        global $DB;

        $columns = [];
        try {
            $columns = $DB->get_columns('tool_usertours_tours');
        } catch (\Throwable $exception) {
            debugging('Error reading tool_usertours_tours columns: ' . $exception->getMessage(), DEBUG_DEVELOPER);
            return;
        }

        $hasTimecreated = !empty($columns) && array_key_exists('timecreated', $columns);
        $hasTimemodified = !empty($columns) && array_key_exists('timemodified', $columns);

        $fields = 'id, configdata';
        if ($hasTimecreated) {
            $fields .= ', timecreated';
        }
        if ($hasTimemodified) {
            $fields .= ', timemodified';
        }

        $records = [];
        try {
            $records = $DB->get_records_sql(
                "SELECT {$fields} FROM {tool_usertours_tours} WHERE configdata LIKE ?",
                ['%"' . self::ACTION_TOUR_CONFIG_MARKER . '"%']
            );
        } catch (\Throwable $exception) {
            debugging('Error loading adaptive course audit action tours for cleanup: ' . $exception->getMessage(), DEBUG_DEVELOPER);
            return;
        }

        if (empty($records)) {
            return;
        }

        foreach ($records as $record) {
            $tourid = (int)($record->id ?? 0);
            if ($tourid <= 0) {
                continue;
            }

            $config = null;
            if (!empty($record->configdata)) {
                $config = json_decode((string)$record->configdata, true);
            }
            if (!is_array($config) || empty($config[self::ACTION_TOUR_CONFIG_MARKER])) {
                continue;
            }

            $candidateTimes = [];
            if (!empty($config[self::TOUR_MAJOR_UPDATE_TIME_CONFIG])) {
                $candidateTimes[] = (int)$config[self::TOUR_MAJOR_UPDATE_TIME_CONFIG];
            }
            if ($hasTimemodified && isset($record->timemodified) && (int)$record->timemodified > 0) {
                $candidateTimes[] = (int)$record->timemodified;
            }
            if ($hasTimecreated && isset($record->timecreated) && (int)$record->timecreated > 0) {
                $candidateTimes[] = (int)$record->timecreated;
            }
            if (!empty($config[self::PLUGIN_TOUR_TIMECREATED_CONFIG])) {
                $candidateTimes[] = (int)$config[self::PLUGIN_TOUR_TIMECREATED_CONFIG];
            }

            $candidateTimes = array_filter($candidateTimes, static function(int $value): bool {
                return $value > 0;
            });

            $tourtime = !empty($candidateTimes) ? max($candidateTimes) : null;

            if ($tourtime === null) {
                debugging(
                    'Skipping action tour cleanup due to missing timestamp: tour ' . $tourid,
                    DEBUG_DEVELOPER
                );
                continue;
            }

            if ($tourtime > $cutoff) {
                continue;
            }

            try {
                $manager->delete_tour($tourid);
            } catch (\Throwable $exception) {
                debugging('Error deleting stale adaptive action tour via cleanup task: ' . $exception->getMessage(), DEBUG_DEVELOPER);
            }
        }
    }
}

