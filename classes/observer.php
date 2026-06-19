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


use local_adaptive_course_audit\task\process_tour_ended;
use tool_usertours\tour as usertour;

/**
 * Event observer callbacks for the Adaptive course audit plugin.
 *
 * @package     local_adaptive_course_audit
 * @copyright   2025 Bastian Schmidt-Kuhl <bastian.schmidt-kuhl@ruhr-uni-bochum.de>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class observer
{
    /**
     * Triggered when a user tour ends (completed or exited early).
     *
     * Immediately disables the tour so it is no longer shown, then queues
     * an ad-hoc task for the heavy cleanup work (subtour deletion, mapping
     * cleanup, review marker removal).
     *
     * @param \tool_usertours\event\tour_ended $event The event data.
     * @return void
     */
    public static function tour_ended(\tool_usertours\event\tour_ended $event): void {
        global $DB;

        $data = $event->get_data();
        $tourid = (int)($data['objectid'] ?? 0);
        $stepindex = isset($data['other']['stepindex']) ? (int)$data['other']['stepindex'] : -1;
        $userid = (int)($data['userid'] ?? 0);

        if ($tourid <= 0) {
            return;
        }

        $ismain = false;
        $issubtour = false;
        $ownedbyplugin = false;
        $config = [];

        try {
            $ismain = $DB->record_exists('local_adaptive_course_audit_tour', ['tourid' => $tourid]);
        } catch (\Throwable $exception) {
            debugging(
                'Error checking adaptive course audit tour mapping: ' . $exception->getMessage(),
                DEBUG_DEVELOPER
            );
        }

        try {
            $record = $DB->get_record('tool_usertours_tours', ['id' => $tourid], 'id, configdata', IGNORE_MISSING);
            if (!empty($record) && !empty($record->configdata)) {
                $decoded = json_decode((string)$record->configdata, true);
                if (is_array($decoded)) {
                    $config = $decoded;
                    $ownedbyplugin = !empty($config['local_adaptive_course_audit'])
                        || !empty($config['local_adaptive_course_audit_action']);
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

        // Disable the ended tour immediately so it no longer displays.
        // Related subtours are cleaned up by the ad-hoc task; they are scoped
        // to a specific user via CSS selector filters and pose minimal risk
        // in the brief window before cron runs.
        try {
            $DB->set_field('tool_usertours_tours', 'enabled', usertour::DISABLED, ['id' => $tourid]);
        } catch (\Throwable $exception) {
            debugging('Error disabling adaptive course audit tour: ' . $exception->getMessage(), DEBUG_DEVELOPER);
        }

        $task = new process_tour_ended();
        $task->set_custom_data([
            'tourid' => $tourid,
            'stepindex' => $stepindex,
            'userid' => $userid,
            'ismain' => $ismain,
            'issubtour' => $issubtour,
            'config' => $config,
        ]);
        \core\task\manager::queue_adhoc_task($task);
    }
}
