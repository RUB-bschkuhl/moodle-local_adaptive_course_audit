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

namespace local_adaptive_course_audit\tour;

defined('MOODLE_INTERNAL') || die();

use tool_usertours\step;
use tool_usertours\tour;
use tool_usertours\target;

/**
 * Lightweight tour manager for Adaptive course audit.
 *
 * @package     local_adaptive_course_audit
 * @copyright   2025 Moodle HQ
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class manager
{
    /** @var tour|null */
    private $tour = null;

    /**
     * Safely load a tour instance if it exists.
     *
     * @param int $tourid
     * @return tour|null
     */
    private function get_tour_if_exists(int $tourid): ?tour
    {
        global $DB;

        if (!$DB->record_exists('tool_usertours_tours', ['id' => $tourid])) {
            return null;
        }

        try {
            return tour::instance($tourid);
        } catch (\Throwable $exception) {
            debugging('Error loading adaptive course audit tour: ' . $exception->getMessage(), DEBUG_DEVELOPER);
            return null;
        }
    }

    /**
     * Create a new tour and persist it.
     *
     * @param string $name
     * @param string $description
     * @param string $pathmatch
     * @param array $config
     * @param bool $addplaceholderstep Whether to prepend the default intro step.
     * @return tour
     */
    public function create_tour(
        string $name,
        string $description,
        string $pathmatch,
        array $config = [],
        bool $addplaceholderstep = true
    ): tour
    {
        global $USER;

        $tour = new tour();
        $tour->set_name($name);
        $tour->set_description($description);
        $tour->set_pathmatch($pathmatch);
        $tour->set_enabled(tour::ENABLED);

        if (method_exists($tour, 'set_ondemand')) {
            // Some Moodle versions expose this setter; keep it compatible.
            call_user_func([$tour, 'set_ondemand'], tour::DISABLED);
        }

        // Ensure the tour only shows for the user who started it.
        // The notification popover exists on all pages with the navbar.
        $tour->set_filter_values('cssselector', ["#nav-notification-popover-container[data-userid=\"{$USER->id}\"]"]);

        $tour->set_sortorder(0);

        foreach ($config as $key => $value) {
            $tour->set_config($key, $value);
        }

        // Mark this tour as owned by this plugin (used by event observers).
        $tour->set_config('local_adaptive_course_audit', 1);

        $tour->persist();
        $this->tour = $tour;

        if ($addplaceholderstep) {
            $this->add_step(
                get_string('tourplaceholdertitle', 'local_adaptive_course_audit'),
                get_string('tourplaceholdercontent', 'local_adaptive_course_audit'),
                (string)target::TARGET_UNATTACHED,
                '',
                [
                    'placement' => 'right',
                    'orphan' => true,
                    'backdrop' => true,
                ]
            );
        }

        return $tour;
    }

    /**
     * Add a step to the current tour.
     *
     * @param string $title
     * @param string $content
     * @param string $targettype
     * @param string $targetvalue
     * @param array $config
     * @return step
     */
    public function add_step(string $title, string $content, string $targettype, string $targetvalue, array $config = []): step
    {
        if ($this->tour === null) {
            throw new \coding_exception('create_tour() must be called before add_step().');
        }

        $step = new step();
        $step->set_tourid($this->tour->get_id());
        $step->set_title($title);
        $step->set_content($content, FORMAT_HTML);
        $step->set_targettype($targettype);
        $step->set_targetvalue($targetvalue);

        foreach ($config as $key => $value) {
            $step->set_config($key, $value);
        }

        $step->persist();

        return $step;
    }

    /**
     * Reset tour visibility so users see new steps.
     *
     * @param int $tourid
     * @return bool
     */
    public function reset_tour_for_all_users(int $tourid): bool
    {
        $tour = $this->get_tour_if_exists($tourid);
        if ($tour === null) {
            debugging('Adaptive course audit tour not found during reset: ' . $tourid, DEBUG_DEVELOPER);
            return false;
        }

        $tour->mark_major_change();
        return true;
    }

    /**
     * Delete a tour and all of its steps.
     *
     * @param int $tourid
     * @return bool
     */
    public function delete_tour(int $tourid): bool
    {
        $tour = $this->get_tour_if_exists($tourid);
        if ($tour === null) {
            debugging('Adaptive course audit tour not found during delete: ' . $tourid, DEBUG_DEVELOPER);
            return false;
        }

        $tour->remove();

        // Clean up any stored mapping rows for this tour.
        try {
            global $DB;
            $DB->delete_records('local_adaptive_course_tour', ['tourid' => $tourid]);
        } catch (\Throwable $exception) {
            debugging('Error deleting adaptive course audit mapping: ' . $exception->getMessage(), DEBUG_DEVELOPER);
        }
        return true;
    }
}
