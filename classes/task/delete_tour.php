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

use core\task\adhoc_task;
use local_adaptive_course_audit\tour\manager as tour_manager;

/**
 * Ad-hoc task to delete a plugin-owned user tour after it ended.
 *
 * @package     local_adaptive_course_audit
 */
final class delete_tour extends adhoc_task {

    /**
     * Run the task.
     *
     * @return void
     */
    public function execute(): void {
        $data = $this->get_custom_data();
        $tourid = isset($data->tourid) ? (int)$data->tourid : 0;

        if ($tourid <= 0) {
            return;
        }

        $manager = new tour_manager();
        try {
            $manager->delete_tour($tourid);
        } catch (\Throwable $exception) {
            debugging('Error deleting adaptive course audit tour via task: ' . $exception->getMessage(), DEBUG_DEVELOPER);
        }
    }
}

