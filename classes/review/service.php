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

namespace local_adaptive_course_audit\review;

defined('MOODLE_INTERNAL') || die();

use context_course;
use local_adaptive_course_audit\review\rules\loops\loop_1;
use local_adaptive_course_audit\tour\manager as tour_manager;
use html_writer;
use moodle_exception;
use tool_usertours\target;


use tool_usertours\tour;

/**
 * Review orchestration service.
 *
 * @package     local_adaptive_course_audit
 */
final class service {
    private const TOUR_TABLE = 'local_adaptive_course_tour';

    /**
     * Start an adaptive review for the provided course.
     *
     * @param int $courseid
     * @param int|null $sectionid Optional section id to scope the review.
     * @return array
     */
    public static function start_review(int $courseid, ?int $sectionid = null): array {
        global $DB;

        $course = get_course($courseid);
        $context = context_course::instance($course->id);
        if (!$context) {
            throw new moodle_exception('invalidcourseid');
        }
        /** @var \context $context */

        require_capability('moodle/course:manageactivities', $context);

        $manager = new tour_manager();
        self::delete_existing_tour((int)$course->id, $manager);
        self::delete_existing_action_tours((int)$course->id);

        $coursename = format_string($course->fullname, true, ['context' => $context]);
        $tourname = get_string('tourname', 'local_adaptive_course_audit', $course->shortname);
        $tourdescription = get_string('tourdescription', 'local_adaptive_course_audit', $coursename);
        $pathmatch = "/course/view.php?id={$course->id}";

        $tourconfig = [
            'displaystepnumbers' => true,
            'showtourwhen' => tour::SHOW_TOUR_UNTIL_COMPLETE,
            'backdrop' => true,
            'reflex' => false,
        ];

        $tour = $manager->create_tour($tourname, $tourdescription, $pathmatch, $tourconfig);

        self::store_tour_mapping((int)$course->id, (int)$tour->get_id());

        $manager->reset_tour_for_all_users((int)$tour->get_id());

        // Run loop-based checks (currently Loop 1) to gather adaptive insights.
        try {
            $sanitisedsectionid = ($sectionid !== null && $sectionid > 0) ? (int)$sectionid : null;
            $results = self::run_loop_checks((int)$course->id, $sanitisedsectionid);
            self::add_loop_results_as_steps($manager, $results);
            self::create_action_tours($course, $results);
        } catch (\Throwable $exception) {
            debugging('Error running adaptive course audit loops: ' . $exception->getMessage(), DEBUG_DEVELOPER);
        }

        return [
            'status' => true,
        ];
    }

    /**
     * Delete an existing adaptive tour for the same course.
     *
     * @param int $courseid
     * @param tour_manager $manager
     * @return void
     */
    private static function delete_existing_tour(int $courseid, tour_manager $manager): void {
        global $DB;

        $record = $DB->get_record(self::TOUR_TABLE, ['courseid' => $courseid]);

        if ($record) {
            try {
                $manager->delete_tour((int)$record->tourid);
            } catch (\Throwable $exception) {
                debugging('Error deleting adaptive course audit tour: ' . $exception->getMessage(), DEBUG_DEVELOPER);
            }
            $DB->delete_records(self::TOUR_TABLE, ['id' => $record->id]);
        }
    }

    /**
     * Persist the newly created tour mapping.
     *
     * @param int $courseid
     * @param int $tourid
     * @return void
     */
    private static function store_tour_mapping(int $courseid, int $tourid): void {
        global $DB;

        $record = (object)[
            'courseid' => $courseid,
            'tourid' => $tourid,
            'timecreated' => time(),
            'timemodified' => time(),
        ];

        $DB->insert_record(self::TOUR_TABLE, $record);
    }

    /**
     * Execute loop-based audit checks for the course.
     *
     * @param int $courseid
     * @param int|null $sectionid Optional section id to scope the checks.
     * @return array Results from executed loops.
     */
    private static function run_loop_checks(int $courseid, ?int $sectionid = null): array {
        $modinfo = get_fast_modinfo($courseid);
        $course = $modinfo->get_course();
        $sections = $modinfo->get_section_info_all();
        $sectionscm = $modinfo->get_sections();
        $results = [];
        $targetsectionid = ($sectionid !== null && $sectionid > 0) ? $sectionid : null;
        $hasmatchingsection = false;

        $looprule = new loop_1();

        foreach ($sections as $sectioninfo) {
            if ($targetsectionid !== null && (int)$sectioninfo->id !== $targetsectionid) {
                continue;
            }

            $hasmatchingsection = true;
            // Skip hidden or orphaned sections.
            if (property_exists($sectioninfo, 'visible') && !$sectioninfo->visible) {
                continue;
            }
            if (property_exists($sectioninfo, 'uservisible') && !$sectioninfo->uservisible) {
                continue;
            }

            $section = (object)[
                'id' => $sectioninfo->id,
                'section' => $sectioninfo->section,
                'modules' => [],
            ];

            $cmids = $sectionscm[$sectioninfo->section] ?? [];
            foreach ($cmids as $cmid) {
                $cm = $modinfo->get_cm($cmid);
                if ($cm === null) {
                    continue;
                }
                $section->modules[] = (object)[
                    'id' => $cm->id,
                    'name' => $cm->name,
                    'modname' => $cm->modname,
                    'availability' => $cm->availability,
                    'uservisible' => $cm->uservisible,
                    'deletioninprogress' => $cm->deletioninprogress,
                ];
            }

            $result = $looprule->check_target($section, $course);
            if ($result !== null) {
                $results[] = $result;
            }
        }

        if ($targetsectionid !== null && !$hasmatchingsection) {
            debugging(
                'Requested section id not found for adaptive course audit: course ' . $courseid .
                ', section ' . $targetsectionid,
                DEBUG_DEVELOPER
            );
        }

        return $results;
    }

    /**
     * Add loop check results to the tour as steps.
     *
     * @param tour_manager $manager
     * @param array $results
     * @return void
     */
    private static function add_loop_results_as_steps(tour_manager $manager, array $results): void {
        foreach ($results as $result) {
            $statusprefix = $result->status ? '[OK] ' : '';
            $headline = $result->headline ?? '';
            if ($headline === '') {
                $headline = $result->rule_name ?? get_string('reviewcourseheading', 'local_adaptive_course_audit');
            }
            $title = $statusprefix . $headline;

            $messages = [];
            if (!empty($result->messages)) {
                foreach ($result->messages as $message) {
                    $messages[] = '<li>' . s($message) . '</li>';
                }
            }

            $content = !empty($messages)
                ? '<ul>' . implode('', $messages) . '</ul>'
                : '<p>' . s(get_string('startreviewhelp', 'local_adaptive_course_audit')) . '</p>';

            $actionshtml = '';
            if (!empty($result->actions) && is_array($result->actions)) {
                $actionbuttons = [];
                foreach ($result->actions as $action) {
                    if (empty($action['url']) || empty($action['label'])) {
                        continue;
                    }

                    $classes = 'btn btn-secondary';
                    if (!empty($action['type']) && $action['type'] === 'primary') {
                        $classes = 'btn btn-primary';
                    }

                    $url = $action['url'];
                    if ($url instanceof \moodle_url) {
                        $url = $url->out(false);
                    }

                    $actionbuttons[] = html_writer::link($url, s($action['label']), ['class' => $classes]);
                }

                if (!empty($actionbuttons)) {
                    $actionshtml = html_writer::div(implode(' ', $actionbuttons), 'local-adaptive-course-audit-step-actions');
                }
            }

            if (!empty($actionshtml)) {
                $content .= $actionshtml;
            }
                
            try {
                $targettype = (string)target::TARGET_UNATTACHED;
                $targetvalue = '';
                if (!empty($result->rule_target) && !empty($result->rule_target_id) || $result->rule_target_id == 0) {
                    if ($result->rule_target === 'section') {
                        $selector = "#section-" . $result->rule_target_id;
                        if ($selector !== null) {
                            $targettype = (string)target::TARGET_SELECTOR;
                            $targetvalue = $selector;
                        }
                    }
                }

                $manager->add_step(
                    $title,
                    $content,
                    $targettype,
                    $targetvalue,
                    [
                        'placement' => 'right',
                        'orphan' => true,
                        'backdrop' => true,
                    ]
                );
            } catch (\Throwable $exception) {
                debugging('Error adding adaptive course audit loop step: ' . $exception->getMessage(), DEBUG_DEVELOPER);
            }
        }
    }

    /**
     * Remove any adaptive action tours for the given course.
     *
     * @param int $courseid
     * @return void
     */
    private static function delete_existing_action_tours(int $courseid): void {
        global $DB;

        try {
            $records = $DB->get_records_sql(
                'SELECT id, configdata FROM {tool_usertours_tours} WHERE configdata LIKE ?',
                ['%"local_adaptive_course_audit_action"%']
            );
        } catch (\Throwable $exception) {
            debugging('Error fetching adaptive action tours: ' . $exception->getMessage(), DEBUG_DEVELOPER);
            return;
        }

        if (empty($records)) {
            return;
        }

        $manager = new tour_manager();
        foreach ($records as $record) {
            try {
                $config = json_decode((string)$record->configdata, true);
                if (!is_array($config)) {
                    continue;
                }

                $isplugin = !empty($config['local_adaptive_course_audit_action']);
                $samecourse = isset($config['local_adaptive_course_audit_courseid'])
                    && (int)$config['local_adaptive_course_audit_courseid'] === $courseid;
                if (!$isplugin || !$samecourse) {
                    continue;
                }

                $manager->delete_tour((int)$record->id);
            } catch (\Throwable $exception) {
                debugging('Error deleting adaptive action tour: ' . $exception->getMessage(), DEBUG_DEVELOPER);
            }
        }
    }

    /**
     * Create tours that guide the user through a chosen action.
     *
     * @param object $course
     * @param array $results
     * @return void
     */
    private static function create_action_tours($course, array $results): void {
        $coursecontext = context_course::instance($course->id);
        $courseshortname = format_string($course->shortname, true, ['context' => $coursecontext]);

        foreach ($results as $result) {
            if (empty($result->actions) || !is_array($result->actions)) {
                continue;
            }

            foreach ($result->actions as $action) {
                if (empty($action['tour']) || empty($action['tour']['steps']) || !is_array($action['tour']['steps'])) {
                    continue;
                }

                if (empty($action['tour']['pathmatch'])) {
                    debugging('Action tour missing pathmatch, skipping tour creation', DEBUG_DEVELOPER);
                    continue;
                }
                $pathmatch = $action['tour']['pathmatch'];

                $tourkey = $action['tour']['key'] ?? sha1($pathmatch);
                $actionlabel = !empty($action['label'])
                    ? (string)$action['label']
                    : get_string('startreview', 'local_adaptive_course_audit');

                $tourname = $action['tour']['name'] ?? get_string('actiontourname', 'local_adaptive_course_audit', (object)[
                    'action' => $actionlabel,
                ]);

                $tourdescription = $action['tour']['description'] ?? get_string('actiontourdescription', 'local_adaptive_course_audit', (object)[
                    'course' => $courseshortname,
                ]);

                $tourconfig = array_merge([
                    'displaystepnumbers' => true,
                    'showtourwhen' => tour::SHOW_TOUR_UNTIL_COMPLETE,
                    'backdrop' => true,
                    'reflex' => false,
                    'local_adaptive_course_audit_action' => 1,
                    'local_adaptive_course_audit_courseid' => (int)$course->id,
                    'local_adaptive_course_audit_key' => $tourkey,
                ], $action['tour']['config'] ?? []);

                $manager = new tour_manager();

                debugging('Creating action tour with pathmatch: ' . $pathmatch, DEBUG_DEVELOPER);

                try {
                    $tour = $manager->create_tour($tourname, $tourdescription, $pathmatch, $tourconfig, false);
                    debugging('Created action tour ID: ' . $tour->get_id() . ' with pathmatch: ' . $tour->get_pathmatch(), DEBUG_DEVELOPER);
                } catch (\Throwable $exception) {
                    debugging('Error creating adaptive action tour: ' . $exception->getMessage(), DEBUG_DEVELOPER);
                    continue;
                }

                foreach ($action['tour']['steps'] as $step) {
                    $title = !empty($step['title']) ? (string)$step['title'] : $tourname;
                    $content = !empty($step['content']) ? (string)$step['content'] : '';
                    $targettype = !empty($step['targettype']) || $step['targettype'] == 0
                        ? (string)$step['targettype']
                        : (string)target::TARGET_UNATTACHED;
                    $targetvalue = !empty($step['targetvalue']) ? (string)$step['targetvalue'] : '';
                    $config = !empty($step['config']) && is_array($step['config']) ? $step['config'] : [];

                    try {
                        $manager->add_step(
                            $title,
                            html_writer::tag('p', s($content)),
                            $targettype,
                            $targetvalue,
                            $config
                        );
                    } catch (\Throwable $exception) {
                        debugging('Error adding adaptive action tour step: ' . $exception->getMessage(), DEBUG_DEVELOPER);
                    }
                }

                try {
                    $manager->reset_tour_for_all_users((int)$tour->get_id());
                } catch (\Throwable $exception) {
                    debugging('Error resetting adaptive action tour: ' . $exception->getMessage(), DEBUG_DEVELOPER);
                }
            }
        }
    }

}

