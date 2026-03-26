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
use local_adaptive_course_audit\review\rules\loops\loop_branch_by_grade;
use local_adaptive_course_audit\review\rules\loops\loop_course_filter_activitynames_autolinking;
use local_adaptive_course_audit\review\rules\loops\loop_diagnostic_checkpoint;
use local_adaptive_course_audit\review\rules\loops\loop_h5p_interactive;
use local_adaptive_course_audit\review\rules\loops\loop_quiz_unlock_followups;
use local_adaptive_course_audit\review\rules\loops\loop_lesson_branching;
use local_adaptive_course_audit\review\rules\loops\loop_quiz_adaptive_behaviour;
use local_adaptive_course_audit\review\rules\loops\loop_quiz_feedback;
use local_adaptive_course_audit\review\rules\loops\loop_quiz_random_questions;
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
final class service
{
    private const TOUR_TABLE = 'local_adaptive_course_tour';
    private const REVIEW_TABLE = 'local_adaptive_course_review';

    /** @var string Teach tour key: combined quiz guided help. */
    private const TEACH_KEY_QUIZ_GUIDED_HELP = 'quizguidedhelp';
    /** @var string Teach tour key (legacy): quiz behaviour guidance. */
    private const TEACH_KEY_QUIZ_BEHAVIOUR = 'quizbehaviour';
    /** @var string Teach tour key (legacy): quiz feedback guidance. */
    private const TEACH_KEY_QUIZ_FEEDBACK = 'quizfeedback';
    /** @var string Teach tour key (legacy): quiz review options guidance. */
    private const TEACH_KEY_QUIZ_REVIEW_OPTIONS = 'quizreviewoptions';
    /** @var string Teach tour key (legacy): quiz grading guidance. */
    private const TEACH_KEY_QUIZ_GRADING = 'quizgrading';
    /** @var string Teach tour key (legacy): quiz timing and security guidance. */
    private const TEACH_KEY_QUIZ_TIMING_SECURITY = 'quiztimingsecurity';

    /**
     * Format plain text as safe HTML paragraphs.
     *
     * @param string $text
     * @return string
     */
    private static function format_plain_text_as_html(string $text): string
    {
        $text = trim($text);
        if ($text === '') {
            return '';
        }

        $paragraphs = preg_split("/\\R{2,}/", $text) ?: [];
        $out = [];

        foreach ($paragraphs as $paragraph) {
            $paragraph = trim((string)$paragraph);
            if ($paragraph === '') {
                continue;
            }
            // Preserve single line breaks within a paragraph.
            $out[] = html_writer::tag('p', nl2br(s($paragraph), false));
        }

        return implode('', $out);
    }

    /**
     * Format the first message (usually rationale) as a highlighted block.
     *
     * If the rationale contains HTML, render it as cleaned HTML (FORMAT_HTML).
     * Otherwise, try to split it into a short heading and body at the first colon.
     *
     * @param string $rationale
     * @param context_course|null $context
     * @return string
     */
    private static function format_rationale_block(string $rationale, ?context_course $context = null): string
    {
        $rationale = trim($rationale);
        if ($rationale === '') {
            return '';
        }

        // Allow richer formatting when the string intentionally contains HTML.
        if (strpos($rationale, '<') !== false && strpos($rationale, '>') !== false) {
            $options = ['filter' => false];
            if ($context !== null) {
                $options['context'] = $context;
            }
            $html = format_text($rationale, FORMAT_HTML, $options);
            return html_writer::div($html, 'local-aca-tour-rationale');
        }

        $heading = '';
        $body = $rationale;
        $colonpos = strpos($rationale, ':');
        if ($colonpos !== false) {
            $candidateheading = trim(substr($rationale, 0, $colonpos));
            $candidatebody = trim(substr($rationale, $colonpos + 1));
            // Avoid treating normal sentences with colon as headings.
            if ($candidateheading !== '' && $candidatebody !== '' && \core_text::strlen($candidateheading) <= 80) {
                $heading = $candidateheading;
                $body = $candidatebody;
            }
        }

        $titlehtml = ($heading !== '')
            ? html_writer::tag('h5', s($heading), ['class' => 'local-aca-tour-rationale-title'])
            : '';

        return html_writer::div($titlehtml . self::format_plain_text_as_html($body), 'local-aca-tour-rationale');
    }

    /**
     * Format action tour step content.
     *
     * @param string $content
     * @param context_course $context
     * @return string
     */
    private static function format_action_tour_step_content(string $content, context_course $context): string
    {
        $content = trim($content);
        if ($content === '') {
            return '';
        }

        if (strpos($content, '<') !== false && strpos($content, '>') !== false) {
            return format_text($content, FORMAT_HTML, [
                'context' => $context,
                'filter' => false,
            ]);
        }

        return self::format_plain_text_as_html($content);
    }

    /**
     * Start an adaptive review for the provided course.
     *
     * @param int $courseid
     * @param int|null $sectionid Optional section id to scope the review.
     * @return array
     */
    public static function start_review(int $courseid, ?int $sectionid = null): array
    {
        global $DB;
        global $USER;

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
        // Use a non-matching pathmatch so the normal Moodle tour bootstrap
        // does not pick this up; the tour is started via JS tour_launcher instead.
        $pathmatch = '/__aca_noop__';

        $tourconfig = [
            'displaystepnumbers' => true,
            'showtourwhen' => tour::SHOW_TOUR_UNTIL_COMPLETE,
            'backdrop' => true,
            'reflex' => false,
        ];

        $tour = $manager->create_tour(
            $tourname,
            $tourdescription,
            $pathmatch,
            $tourconfig,
            true,
            get_string('tourintro_audit_title', 'local_adaptive_course_audit'),
            get_string('tourintro_audit_content', 'local_adaptive_course_audit')
        );

        self::store_tour_mapping((int)$course->id, (int)$tour->get_id());

        $manager->reset_tour_for_all_users((int)$tour->get_id());

        // Track the most recent audit review started per user and course.
        try {
            self::store_latest_audit_review_start((int)$course->id, (int)$USER->id, $sectionid);
        } catch (\Throwable $exception) {
            debugging('Error storing adaptive course audit review start: ' . $exception->getMessage(), DEBUG_DEVELOPER);
        }

        // Run loop-based checks to gather adaptive insights.
        try {
            $sanitisedsectionid = ($sectionid !== null && $sectionid > 0) ? (int)$sectionid : null;
            $results = self::run_loop_checks((int)$course->id, $sanitisedsectionid);
            // Create action tours first so their IDs can be injected into step action URLs.
            $actiontourmap = self::create_action_tours($course, $results);
            self::add_loop_results_as_steps($manager, $results, $actiontourmap);
        } catch (\Throwable $exception) {
            debugging('Error running adaptive course audit loops: ' . $exception->getMessage(), DEBUG_DEVELOPER);
        }

        return [
            'status' => true,
            'tourid' => (int)$tour->get_id(),
        ];
    }

    /**
     * Store the latest audit-review start per user and course.
     *
     * This is used to resume/re-run a review action elsewhere (whole-course or a specific section).
     *
     * @param int $courseid
     * @param int $userid
     * @param int|null $sectionid
     * @return void
     */
    private static function store_latest_audit_review_start(int $courseid, int $userid, ?int $sectionid): void
    {
        global $DB;

        if ($courseid <= 0 || $userid <= 0) {
            return;
        }

        $sanitisedsectionid = ($sectionid !== null && $sectionid > 0) ? (int)$sectionid : 0;

        // Store a stable URL (without sesskey) so it can be reconstructed later.
        $urlparams = [
            'courseid' => $courseid,
            'action' => 'startreview',
        ];
        if ($sanitisedsectionid > 0) {
            $urlparams['sectionid'] = $sanitisedsectionid;
        }
        $reviewurl = (new \moodle_url('/local/adaptive_course_audit/review.php', $urlparams))->out(false);

        $now = time();

        $existing = null;
        try {
            $existing = $DB->get_record(self::REVIEW_TABLE, [
                'courseid' => $courseid,
                'userid' => $userid,
            ]);
        } catch (\Throwable $exception) {
            debugging('Error fetching existing adaptive course review start: ' . $exception->getMessage(), DEBUG_DEVELOPER);
            $existing = null;
        }

        if (!empty($existing) && !empty($existing->id)) {
            $record = (object)[
                'id' => (int)$existing->id,
                'sectionid' => $sanitisedsectionid,
                'reviewurl' => $reviewurl,
                'timemodified' => $now,
            ];
            $DB->update_record(self::REVIEW_TABLE, $record);
            return;
        }

        $record = (object)[
            'courseid' => $courseid,
            'userid' => $userid,
            'sectionid' => $sanitisedsectionid,
            'reviewurl' => $reviewurl,
            'timecreated' => $now,
            'timemodified' => $now,
        ];
        $DB->insert_record(self::REVIEW_TABLE, $record);
    }

    /**
     * Start an on-demand teaching tour for a specific course module.
     *
     * The tour is created as a short-lived "action tour" (sub tour) and will auto-run
     * on the target page via pathmatch. When the tour ends, the plugin observer will
     * clean it up.
     *
     * @param int $courseid
     * @param int $cmid
     * @param string $teachkey
     * @return array Array with status, redirect URL and optional message.
     */
    public static function start_teach_tour(int $courseid, int $cmid, string $teachkey): array
    {
        $teachkey = trim($teachkey);
        if ($courseid <= 0 || $cmid <= 0 || $teachkey === '') {
            return [
                'status' => false,
                'message' => get_string('startteacherror', 'local_adaptive_course_audit'),
            ];
        }

        $course = get_course($courseid);
        $context = context_course::instance($course->id);
        if (!$context) {
            throw new moodle_exception('invalidcourseid');
        }
        /** @var \context $context */

        /** @var context_course $coursecontext */
        $coursecontext = $context;

        require_capability('moodle/course:manageactivities', $context);

        $modinfo = get_fast_modinfo($course->id);
        try {
            $cm = $modinfo->get_cm($cmid);
        } catch (\Throwable $exception) {
            debugging('Error resolving cm for adaptive teaching tour: ' . $exception->getMessage(), DEBUG_DEVELOPER);
            $cm = null;
        }

        if (empty($cm) || (int)$cm->course !== (int)$course->id) {
            return [
                'status' => false,
                'message' => get_string('startteacherror', 'local_adaptive_course_audit'),
            ];
        }

        if ((string)$cm->modname !== 'quiz') {
            return [
                'status' => false,
                'message' => get_string('startteacherror', 'local_adaptive_course_audit'),
            ];
        }

        $allowedteach = [
            self::TEACH_KEY_QUIZ_GUIDED_HELP,
            self::TEACH_KEY_QUIZ_BEHAVIOUR,
            self::TEACH_KEY_QUIZ_FEEDBACK,
            self::TEACH_KEY_QUIZ_REVIEW_OPTIONS,
            self::TEACH_KEY_QUIZ_GRADING,
            self::TEACH_KEY_QUIZ_TIMING_SECURITY,
        ];
        if (!in_array($teachkey, $allowedteach, true)) {
            return [
                'status' => false,
                'message' => get_string('startteacherror', 'local_adaptive_course_audit'),
            ];
        }

        $quizname = format_string((string)$cm->name, true, ['context' => $context]);
        $editurl = new \moodle_url('/course/modedit.php', [
            'update' => (int)$cmid,
            'return' => 0,
            'sr' => 0,
            'sesskey' => sesskey(),
        ]);
        if ($teachkey === self::TEACH_KEY_QUIZ_GUIDED_HELP || $teachkey === self::TEACH_KEY_QUIZ_BEHAVIOUR) {
            $editurl->set_anchor('id_interactionhdrcontainer');
        }
        if ($teachkey === self::TEACH_KEY_QUIZ_FEEDBACK) {
            $editurl->set_anchor('id_overallfeedbackhdr');
        }
        if ($teachkey === self::TEACH_KEY_QUIZ_REVIEW_OPTIONS) {
            $editurl->set_anchor('id_reviewoptionshdr');
        }
        if ($teachkey === self::TEACH_KEY_QUIZ_GRADING) {
            $editurl->set_anchor('id_gradehdr');
        }
        if ($teachkey === self::TEACH_KEY_QUIZ_TIMING_SECURITY) {
            $editurl->set_anchor('id_timinghdr');
        }
        // Use a non-matching pathmatch; the tour is started via JS tour_launcher.
        $pathmatch = '/__aca_noop__';

        // Merge legacy per-topic teach keys into the combined tour.
        if (in_array($teachkey, [
            self::TEACH_KEY_QUIZ_BEHAVIOUR,
            self::TEACH_KEY_QUIZ_FEEDBACK,
            self::TEACH_KEY_QUIZ_REVIEW_OPTIONS,
            self::TEACH_KEY_QUIZ_GRADING,
            self::TEACH_KEY_QUIZ_TIMING_SECURITY,
        ], true)) {
            $teachkey = self::TEACH_KEY_QUIZ_GUIDED_HELP;
        }

        $tourkey = 'teach_' . $teachkey . '_' . (int)$cmid;

        $tourname = get_string('teachtourname', 'local_adaptive_course_audit', $quizname);
        $tourdescription = get_string('teachtourdescription', 'local_adaptive_course_audit', (object)[
            'course' => format_string($course->shortname, true, ['context' => $context]),
        ]);

        $tourconfig = [
            'displaystepnumbers' => true,
            'showtourwhen' => tour::SHOW_TOUR_UNTIL_COMPLETE,
            'backdrop' => true,
            'reflex' => false,
            'local_adaptive_course_audit_action' => 1,
            'local_adaptive_course_audit_courseid' => (int)$course->id,
            'local_adaptive_course_audit_key' => $tourkey,
            'local_adaptive_course_audit_timecreated' => time(),
        ];

        $manager = new tour_manager();

        // Ensure there is no unintended interaction with previously started tours in this course.
        // This removes the main review tour (if any) and any existing action/sub tours owned by this plugin.
        self::delete_existing_tour((int)$course->id, $manager);
        self::delete_existing_action_tours((int)$course->id);

        try {
            $tour = $manager->create_tour(
                $tourname,
                $tourdescription,
                $pathmatch,
                $tourconfig,
                true,
                get_string('tourintro_teach_title', 'local_adaptive_course_audit'),
                get_string('tourintro_teach_content', 'local_adaptive_course_audit')
            );
        } catch (\Throwable $exception) {
            debugging('Error creating adaptive teaching tour: ' . $exception->getMessage(), DEBUG_DEVELOPER);
            return [
                'status' => false,
                'message' => get_string('startteacherror', 'local_adaptive_course_audit'),
            ];
        }

        try {
            $steps = self::build_teach_tour_steps($teachkey, $quizname);
            foreach ($steps as $step) {
                $manager->add_step(
                    (string)$step['title'],
                    self::format_action_tour_step_content((string)$step['content'], $coursecontext),
                    (string)$step['targettype'],
                    (string)$step['targetvalue'],
                    (array)$step['config']
                );
            }
        } catch (\Throwable $exception) {
            debugging('Error adding adaptive teaching tour steps: ' . $exception->getMessage(), DEBUG_DEVELOPER);
        }

        try {
            $manager->reset_tour_for_all_users((int)$tour->get_id());
        } catch (\Throwable $exception) {
            debugging('Error resetting adaptive teaching tour: ' . $exception->getMessage(), DEBUG_DEVELOPER);
        }

        return [
            'status' => true,
            'redirect' => $editurl,
            'tourid' => (int)$tour->get_id(),
        ];
    }

    /**
     * Build tour steps for a teach key.
     *
     * @param string $teachkey
     * @param string $quizname
     * @return array[]
     */
    private static function build_teach_tour_steps(string $teachkey, string $quizname): array
    {
        $commonconfig = [
            'placement' => 'right',
            'backdrop' => true,
            'orphan' => true,
        ];

        if ($teachkey === self::TEACH_KEY_QUIZ_GUIDED_HELP) {
            return [
                [
                    'title' => get_string('actiontour_quizbehaviour_step_behaviour_title', 'local_adaptive_course_audit'),
                    'content' => get_string('actiontour_quizbehaviour_step_behaviour_body', 'local_adaptive_course_audit'),
                    'targettype' => (string)target::TARGET_SELECTOR,
                    'targetvalue' => '#id_preferredbehaviour',
                    'config' => $commonconfig,
                ],
                [
                    'title' => get_string('actiontour_quizfeedback_step_attempts_title', 'local_adaptive_course_audit'),
                    'content' => get_string('actiontour_quizfeedback_step_attempts_body', 'local_adaptive_course_audit'),
                    'targettype' => (string)target::TARGET_SELECTOR,
                    'targetvalue' => '#id_attempts',
                    'config' => $commonconfig,
                ],
                [
                    'title' => get_string('actiontour_quizcompletion_step_completion_title', 'local_adaptive_course_audit'),
                    'content' => get_string('actiontour_quizcompletion_step_completion_body', 'local_adaptive_course_audit'),
                    'targettype' => (string)target::TARGET_SELECTOR,
                    'targetvalue' => '#id_completion_2', 
                    'config' => $commonconfig,
                ],
                [
                    'title' => get_string('actiontour_quizgrading_step_grade_title', 'local_adaptive_course_audit'),
                    'content' => get_string('actiontour_quizgrading_step_grade_body', 'local_adaptive_course_audit'),
                    'targettype' => (string)target::TARGET_SELECTOR,
                    'targetvalue' => '#id_grademethod',
                    'config' => $commonconfig,
                ],
                [
                    'title' => get_string('actiontour_quizreviewoptions_step_reviewoptions_title', 'local_adaptive_course_audit'),
                    'content' => get_string('actiontour_quizreviewoptions_step_reviewoptions_body', 'local_adaptive_course_audit'),
                    'targettype' => (string)target::TARGET_SELECTOR,
                    'targetvalue' => '#id_attemptimmediately',
                    'config' => $commonconfig,
                ],
                [
                    'title' => get_string('actiontour_quizfeedback_step_overallfeedback_title', 'local_adaptive_course_audit'),
                    'content' => get_string('actiontour_quizfeedback_step_overallfeedback_body', 'local_adaptive_course_audit'),
                    'targettype' => (string)target::TARGET_SELECTOR,
                    // The overall feedback editor is a repeated element; target the first row’s wrapper (fallback included).
                    'targetvalue' => '#fitem_id_feedbacktext_0, [id^="fitem_id_feedbacktext"]',
                    'config' => $commonconfig,
                ],
                [
                    'title' => get_string('actiontour_quiztimingsecurity_step_timing_title', 'local_adaptive_course_audit'),
                    'content' => get_string('actiontour_quiztimingsecurity_step_timing_body', 'local_adaptive_course_audit'),
                    'targettype' => (string)target::TARGET_SELECTOR,
                    'targetvalue' => '#id_timeopen',
                    'config' => $commonconfig,
                ],
            ];
        }

        return [
            [
                'title' => $quizname,
                'content' => get_string('startteacherror', 'local_adaptive_course_audit'),
                'targettype' => (string)target::TARGET_UNATTACHED,
                'targetvalue' => '',
                'config' => $commonconfig,
            ],
        ];
    }

    /**
     * Delete existing plugin-owned action tours for the same course and key.
     *
     * @param int $courseid
     * @param string $key
     * @return void
     */
    private static function delete_existing_action_tour_by_key(int $courseid, string $key): void
    {
        global $DB;

        $records = [];
        try {
            $records = $DB->get_records_sql(
                'SELECT id, configdata FROM {tool_usertours_tours} WHERE configdata LIKE ?',
                ['%"local_adaptive_course_audit_action"%']
            );
        } catch (\Throwable $exception) {
            debugging('Error fetching adaptive action tours for key cleanup: ' . $exception->getMessage(), DEBUG_DEVELOPER);
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
                $samekey = isset($config['local_adaptive_course_audit_key'])
                    && (string)$config['local_adaptive_course_audit_key'] === $key;
                if (!$isplugin || !$samecourse || !$samekey) {
                    continue;
                }

                $manager->delete_tour((int)$record->id);
            } catch (\Throwable $exception) {
                debugging('Error deleting adaptive action tour by key: ' . $exception->getMessage(), DEBUG_DEVELOPER);
            }
        }
    }

    /**
     * Delete an existing adaptive tour for the same course.
     *
     * @param int $courseid
     * @param tour_manager $manager
     * @return void
     */
    private static function delete_existing_tour(int $courseid, tour_manager $manager): void
    {
        global $DB;

        // Fetch all mapped tours for this course (minimalist scenario creates multiple: T1, T2, T3).
        $records = $DB->get_records(self::TOUR_TABLE, ['courseid' => $courseid]);

        foreach ($records as $record) {
            try {
                $manager->delete_tour((int)$record->tourid);
            } catch (\Throwable $exception) {
                debugging('Error deleting adaptive course audit tour: ' . $exception->getMessage(), DEBUG_DEVELOPER);
            }
        }

        if (!empty($records)) {
            $DB->delete_records(self::TOUR_TABLE, ['courseid' => $courseid]);
        }

        // Clean up orphaned scenario tours for this course.
        // These can accumulate when store_tour_mapping fails due to the unique constraint
        // on courseid (e.g. T2/T3 from a previous minimalist scenario run).
        self::delete_orphaned_scenario_tours($courseid, $manager);
    }

    /**
     * Delete orphaned scenario tours that belong to this course but are not tracked in the mapping table.
     *
     * @param int $courseid
     * @param tour_manager $manager
     * @return void
     */
    private static function delete_orphaned_scenario_tours(int $courseid, tour_manager $manager): void
    {
        global $DB;

        try {
            $records = $DB->get_records_sql(
                'SELECT id, configdata FROM {tool_usertours_tours} WHERE configdata LIKE ?',
                ['%"local_adaptive_course_audit_scenario"%']
            );
        } catch (\Throwable $exception) {
            debugging('Error fetching orphaned scenario tours: ' . $exception->getMessage(), DEBUG_DEVELOPER);
            return;
        }

        foreach ($records as $record) {
            try {
                $config = json_decode((string)$record->configdata, true);
                if (!is_array($config)) {
                    continue;
                }

                $isplugin = !empty($config['local_adaptive_course_audit_scenario']);
                $samecourse = isset($config['local_adaptive_course_audit_courseid'])
                    && (int)$config['local_adaptive_course_audit_courseid'] === $courseid;
                if (!$isplugin || !$samecourse) {
                    continue;
                }

                $manager->delete_tour((int)$record->id);
            } catch (\Throwable $exception) {
                debugging('Error deleting orphaned scenario tour: ' . $exception->getMessage(), DEBUG_DEVELOPER);
            }
        }
    }

    /**
     * Persist the newly created tour mapping.
     *
     * @param int $courseid
     * @param int $tourid
     * @return void
     */
    private static function store_tour_mapping(int $courseid, int $tourid): void
    {
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
    private static function run_loop_checks(int $courseid, ?int $sectionid = null): array
    {
        $modinfo = get_fast_modinfo($courseid);
        $course = $modinfo->get_course();
        $sections = $modinfo->get_section_info_all();
        $sectionscm = $modinfo->get_sections();
        $results = [];
        $targetsectionid = ($sectionid !== null && $sectionid > 0) ? $sectionid : null;
        $hasmatchingsection = false;

        // Course-level checks (run once per review).
        $courserules = [
            new loop_course_filter_activitynames_autolinking(),
        ];
        foreach ($courserules as $courserule) {
            try {
                $result = $courserule->check_target(null, $course);
                if ($result !== null) {
                    $results[] = $result;
                }
            } catch (\Throwable $exception) {
                debugging('Error running adaptive course audit course rule: ' . $exception->getMessage(), DEBUG_DEVELOPER);
            }
        }

        $looprules = [
            new loop_quiz_unlock_followups(),
            new loop_branch_by_grade(),
            new loop_quiz_feedback(),
            new loop_quiz_adaptive_behaviour(),
            new loop_quiz_random_questions(),
            new loop_lesson_branching(),
            //  new loop_h5p_interactive(),
            //  new loop_diagnostic_checkpoint(),
        ];

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
                    'instance' => $cm->instance,
                    'availability' => $cm->availability,
                    'uservisible' => $cm->uservisible,
                    'deletioninprogress' => $cm->deletioninprogress,
                ];
            }

            foreach ($looprules as $looprule) {
                try {
                    $result = $looprule->check_target($section, $course);
                    if ($result !== null) {
                        $results[] = $result;
                    }
                } catch (\Throwable $exception) {
                    debugging('Error running adaptive course audit loop rule: ' . $exception->getMessage(), DEBUG_DEVELOPER);
                }
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
     * @param array $actiontourmap Map of action tour keys to tour IDs, used to inject startacatour URL params.
     * @return void
     */
    private static function add_loop_results_as_steps(tour_manager $manager, array $results, array $actiontourmap = []): void
    {
        foreach ($results as $result) {
            // For now, exclude "success" results (i.e. when the user already implemented the recommendation).
            // We keep the underlying rule + strings intact so we can re-enable these steps later if desired.
            if (!empty($result->status)) {
                continue;
            }

            $headline = $result->headline ?? '';
            if ($headline === '') {
                $headline = $result->rule_name ?? get_string('reviewcourseheading', 'local_adaptive_course_audit');
            }
            $title = $headline;

            $context = null;
            if (!empty($result->course_id)) {
                try {
                    $context = context_course::instance((int)$result->course_id);
                } catch (\Throwable $exception) {
                    $context = null;
                }
            }

            $rawmessages = [];
            if (!empty($result->messages) && is_array($result->messages)) {
                $rawmessages = array_values($result->messages);
            }

            $contentchunks = [];
            if (!empty($rawmessages)) {
                $rationale = (string)array_shift($rawmessages);

                if (!empty($rawmessages)) {
                    if (count($rawmessages) === 1) {
                        $contentchunks[] = self::format_plain_text_as_html((string)$rawmessages[0]);
                    } else {
                        $items = [];
                        foreach ($rawmessages as $message) {
                            $items[] = html_writer::tag('li', self::format_plain_text_as_html((string)$message));
                        }
                        $contentchunks[] = html_writer::tag('ul', implode('', $items), [
                            'class' => 'local-aca-tour-message-list',
                        ]);
                    }
                }

                if ($rationale !== '') {
                    $contentchunks[] = self::format_rationale_block($rationale, $context);
                }
            }

            if (empty($contentchunks)) {
                $contentchunks[] = self::format_plain_text_as_html(
                    (string)get_string('startreviewhelp', 'local_adaptive_course_audit')
                );
            }

            $content = html_writer::div(implode('', $contentchunks), 'local-aca-tour-step-content');

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

                    // If we send the user to modedit.php from within an audit step, expand relevant
                    // collapsible fieldsets automatically so settings are visible immediately.
                    if ($url instanceof \moodle_url) {
                        $path = (string)$url->get_path();
                        if (strpos($path, '/course/modedit.php') !== false) {
                            $url->param('acaexpand', 1);
                        }
                    }

                    // Inject the startacatour parameter so the JS tour_launcher
                    // can start the corresponding action tour on the target page.
                    if (!empty($action['tour']['key']) && isset($actiontourmap[$action['tour']['key']])) {
                        if ($url instanceof \moodle_url) {
                            $url->param('startacatour', $actiontourmap[$action['tour']['key']]);
                        }
                    }
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

    /** @var int Scenario tour: minimalist (individual parts). */
    private const SCENARIO_MINIMALIST = 1;
    /** @var int Scenario tour: sequential (topics build on each other). */
    private const SCENARIO_SEQUENTIAL = 2;
    /** @var int Scenario tour: compass model (free topic order). */
    private const SCENARIO_COMPASS = 3;

    /**
     * Start a scenario tour that guides the instructor through adaptive course design.
     *
     * @param int $courseid
     * @param int $scenario Scenario identifier (1, 2 or 3).
     * @param array $options Additional scenario state carried across redirects.
     * @return array Array with status and optional message.
     */
    public static function start_scenario_tour(int $courseid, int $scenario, array $options = []): array
    {
        global $DB;

        $allowedscenarios = [self::SCENARIO_MINIMALIST, self::SCENARIO_SEQUENTIAL, self::SCENARIO_COMPASS];
        if (!in_array($scenario, $allowedscenarios, true)) {
            return [
                'status' => false,
                'message' => get_string('startscenarioerror', 'local_adaptive_course_audit'),
            ];
        }

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

        $scenariotitle = get_string("scenario_{$scenario}_title", 'local_adaptive_course_audit');
        $tourname = get_string('scenario_tourname', 'local_adaptive_course_audit', $scenariotitle);
        $tourdescription = get_string('scenario_tourdescription', 'local_adaptive_course_audit');
        // Use a non-matching pathmatch; the tour is started via JS tour_launcher.
        $pathmatch = '/__aca_noop__';

        $tourconfig = [
            'displaystepnumbers' => true,
            'showtourwhen' => tour::SHOW_TOUR_UNTIL_COMPLETE,
            'backdrop' => true,
            'reflex' => false,
            'local_adaptive_course_audit_scenario' => $scenario,
            'local_adaptive_course_audit_courseid' => (int)$course->id,
        ];

        try {
            $tour = $manager->create_tour(
                $tourname,
                $tourdescription,
                $pathmatch,
                $tourconfig,
                true,
                get_string('tourintro_scenario_title', 'local_adaptive_course_audit'),
                get_string('tourintro_scenario_content', 'local_adaptive_course_audit')
            );
        } catch (\Throwable $exception) {
            debugging('Error creating scenario tour: ' . $exception->getMessage(), DEBUG_DEVELOPER);
            return [
                'status' => false,
                'message' => get_string('startscenarioerror', 'local_adaptive_course_audit'),
            ];
        }

        self::store_tour_mapping((int)$course->id, (int)$tour->get_id());

        if ($scenario === self::SCENARIO_MINIMALIST) {
            try {
                self::build_minimalist_interactive_steps($manager, (int)$course->id, (int)$tour->get_id());
            } catch (\Throwable $exception) {
                debugging('Error building minimalist interactive tour: ' . $exception->getMessage(), DEBUG_DEVELOPER);
            }
        } else if ($scenario === self::SCENARIO_SEQUENTIAL) {
            try {
                return self::build_sequential_interactive_steps($manager, (int)$course->id, (int)$tour->get_id(), $options);
            } catch (\Throwable $exception) {
                debugging('Error building sequential interactive tour: ' . $exception->getMessage(), DEBUG_DEVELOPER);
                return [
                    'status' => false,
                    'message' => get_string('startscenarioerror', 'local_adaptive_course_audit'),
                ];
            }
        } else if ($scenario === self::SCENARIO_COMPASS) {
            try {
                return self::build_compass_interactive_steps($manager, (int)$course->id, (int)$tour->get_id(), $options);
            } catch (\Throwable $exception) {
                debugging('Error building compass interactive tour: ' . $exception->getMessage(), DEBUG_DEVELOPER);
                return [
                    'status' => false,
                    'message' => get_string('startscenarioerror', 'local_adaptive_course_audit'),
                ];
            }
        } else {
            try {
                $steps = self::build_scenario_steps($scenario);
                foreach ($steps as $step) {
                    $manager->add_step(
                        (string)$step['title'],
                        (string)$step['content'],
                        (string)$step['targettype'],
                        (string)$step['targetvalue'],
                        (array)$step['config']
                    );
                }
            } catch (\Throwable $exception) {
                debugging('Error adding scenario tour steps: ' . $exception->getMessage(), DEBUG_DEVELOPER);
            }

            try {
                $manager->reset_tour_for_all_users((int)$tour->get_id());
            } catch (\Throwable $exception) {
                debugging('Error resetting scenario tour: ' . $exception->getMessage(), DEBUG_DEVELOPER);
            }
        }

        return [
            'status' => true,
            'tourid' => (int)$tour->get_id(),
        ];
    }

    /**
     * Build tour steps for a scenario path.
     *
     * @param int $scenario
     * @return array[]
     */
    private static function build_scenario_steps(int $scenario): array
    {
        $commonconfig = [
            'placement' => 'right',
            'orphan' => true,
            'backdrop' => true,
        ];

        $stepcount = 5;
        $steps = [];

        for ($i = 1; $i <= $stepcount; $i++) {
            $steps[] = [
                'title' => get_string("scenario_{$scenario}_step{$i}_title", 'local_adaptive_course_audit'),
                'content' => get_string("scenario_{$scenario}_step{$i}_content", 'local_adaptive_course_audit'),
                'targettype' => (string)target::TARGET_UNATTACHED,
                'targetvalue' => '',
                'config' => $commonconfig,
            ];
        }

        return $steps;
    }

    /**
     * Find the first quiz activity in the course.
     *
     * @param int $courseid
     * @return \cm_info|null
     */
    private static function find_first_quiz_cm(int $courseid): ?\cm_info
    {
        $modinfo = get_fast_modinfo($courseid);
        foreach ($modinfo->get_cms() as $cm) {
            if (!empty($cm->modname) && $cm->modname === 'quiz') {
                return $cm;
            }
        }

        return null;
    }

    /**
     * Resolve a default question category for the given context.
     *
     * @param int $courseid
     * @param \cm_info|null $quizcm
     * @return \stdClass|null
     */
    private static function get_default_question_category(int $courseid, ?\cm_info $quizcm = null): ?\stdClass
    {
        global $CFG;

        require_once($CFG->dirroot . '/question/editlib.php');

        try {
            if ($quizcm !== null) {
                $basecontext = \context_module::instance((int)$quizcm->id);
                if (!$basecontext) {
                    return null;
                }
                /** @var \context $questioncontext */
                $questioncontext = $basecontext;
                $contexts = new \core_question\local\bank\question_edit_contexts($questioncontext);
                return question_make_default_categories($contexts->all());
            }

            $basecontext = \context_course::instance($courseid);
            if (!$basecontext) {
                return null;
            }
            /** @var \context $questioncontext */
            $questioncontext = $basecontext;
            $contexts = new \core_question\local\bank\question_edit_contexts($questioncontext);
            return question_make_default_categories($contexts->all());
        } catch (\Throwable $exception) {
            debugging('Error resolving default question category for compass tour: ' . $exception->getMessage(), DEBUG_DEVELOPER);
            return null;
        }
    }

    /**
     * Build a review URL for a scenario stage.
     *
     * @param int $courseid
     * @param int $scenario
     * @param array $params
     * @return \moodle_url
     */
    private static function build_scenario_review_url(int $courseid, int $scenario, array $params = []): \moodle_url
    {
        $baseparams = [
            'courseid' => $courseid,
            'action' => 'startscenario',
            'scenario' => $scenario,
            'sesskey' => sesskey(),
        ];

        return new \moodle_url('/local/adaptive_course_audit/review.php', array_merge($baseparams, $params));
    }

    /**
     * Get user-selectable course sections.
     *
     * @param int $courseid
     * @return \section_info[]
     */
    private static function get_selectable_sections(int $courseid): array
    {
        $modinfo = get_fast_modinfo($courseid);
        $course = $modinfo->get_course();
        $sections = [];

        foreach ($modinfo->get_section_info_all() as $sectioninfo) {
            if ((int)$sectioninfo->section <= 0) {
                continue;
            }

            $sections[] = [
                'id' => (int)$sectioninfo->id,
                'name' => get_section_name($course, $sectioninfo),
            ];
        }

        return $sections;
    }

    /**
     * Render clickable buttons for section selection.
     *
     * @param int $courseid
     * @param int $scenario
     * @param string $paramname
     * @param array $baseparams
     * @param int|null $excludeid
     * @return string
     */
    private static function render_section_selection_html(
        int $courseid,
        int $scenario,
        string $paramname,
        array $baseparams = [],
        ?int $excludeid = null
    ): string {
        $links = [];
        foreach (self::get_selectable_sections($courseid) as $section) {
            if ($excludeid !== null && (int)$section['id'] === $excludeid) {
                continue;
            }

            $url = self::build_scenario_review_url($courseid, $scenario, array_merge($baseparams, [
                $paramname => (int)$section['id'],
            ]));
            $links[] = html_writer::link(
                $url->out(false),
                s((string)$section['name']),
                ['class' => 'btn btn-secondary local-adaptive-course-audit-choice-button']
            );
        }

        if (empty($links)) {
            return html_writer::div(
                s(get_string('scenario_choice_none_available', 'local_adaptive_course_audit')),
                'local-adaptive-course-audit-step-actions'
            );
        }

        return html_writer::div(implode('', $links), 'local-adaptive-course-audit-step-actions');
    }

    /**
     * Render clickable buttons for module selection inside a section.
     *
     * @param int $courseid
     * @param int $sectionid
     * @param int $scenario
     * @param string $paramname
     * @param array $baseparams
     * @return string
     */
    private static function render_section_module_selection_html(
        int $courseid,
        int $sectionid,
        int $scenario,
        string $paramname,
        array $baseparams = []
    ): string {
        $modinfo = get_fast_modinfo($courseid);
        $sectioninfo = $modinfo->get_section_info_by_id($sectionid);
        $links = [];

        if (empty($sectioninfo)) {
            return html_writer::div(
                s(get_string('scenario_choice_none_available', 'local_adaptive_course_audit')),
                'local-adaptive-course-audit-step-actions'
            );
        }

        foreach ($modinfo->sections[(int)$sectioninfo->section] ?? [] as $cmid) {
            $cm = $modinfo->get_cm($cmid);
            if (!$cm->uservisible || empty($cm->name)) {
                continue;
            }

            $url = self::build_scenario_review_url($courseid, $scenario, array_merge($baseparams, [
                $paramname => (int)$cm->id,
            ]));
            $label = format_string($cm->name, true, ['context' => \context_course::instance($courseid)]) .
                ' (' . $cm->modname . ')';
            $links[] = html_writer::link(
                $url->out(false),
                s($label),
                ['class' => 'btn btn-secondary local-adaptive-course-audit-choice-button']
            );
        }

        if (empty($links)) {
            return html_writer::div(
                s(get_string('scenario_choice_none_available', 'local_adaptive_course_audit')),
                'local-adaptive-course-audit-step-actions'
            );
        }

        return html_writer::div(implode('', $links), 'local-adaptive-course-audit-step-actions');
    }

    /**
     * Render clickable buttons for quiz selection.
     *
     * @param int $courseid
     * @param int $scenario
     * @param string $paramname
     * @param array $baseparams
     * @return string
     */
    private static function render_quiz_selection_html(
        int $courseid,
        int $scenario,
        string $paramname,
        array $baseparams = []
    ): string {
        $modinfo = get_fast_modinfo($courseid);
        $links = [];

        foreach ($modinfo->get_cms() as $cm) {
            if ($cm->modname !== 'quiz' || !$cm->uservisible || empty($cm->name)) {
                continue;
            }

            $url = self::build_scenario_review_url($courseid, $scenario, array_merge($baseparams, [
                $paramname => (int)$cm->id,
            ]));
            $links[] = html_writer::link(
                $url->out(false),
                s((string)$cm->name),
                ['class' => 'btn btn-secondary local-adaptive-course-audit-choice-button']
            );
        }

        if (empty($links)) {
            return html_writer::div(
                s(get_string('scenario_choice_none_available', 'local_adaptive_course_audit')),
                'local-adaptive-course-audit-step-actions'
            );
        }

        return html_writer::div(implode('', $links), 'local-adaptive-course-audit-step-actions');
    }

    /**
     * Render clickable buttons for course-wide module selection.
     *
     * @param int $courseid
     * @param int $scenario
     * @param string $paramname
     * @param array $baseparams
     * @param string|null $modname
     * @return string
     */
    private static function render_course_module_selection_html(
        int $courseid,
        int $scenario,
        string $paramname,
        array $baseparams = [],
        ?string $modname = null
    ): string {
        $modinfo = get_fast_modinfo($courseid);
        $links = [];

        foreach ($modinfo->get_cms() as $cm) {
            if (!$cm->uservisible || empty($cm->name)) {
                continue;
            }
            if ($modname !== null && $cm->modname !== $modname) {
                continue;
            }

            $url = self::build_scenario_review_url($courseid, $scenario, array_merge($baseparams, [
                $paramname => (int)$cm->id,
            ]));
            $label = (string)$cm->name;
            if ($modname === null) {
                $label .= ' (' . $cm->modname . ')';
            }
            $links[] = html_writer::link(
                $url->out(false),
                s($label),
                ['class' => 'btn btn-secondary local-adaptive-course-audit-choice-button']
            );
        }

        if (empty($links)) {
            return html_writer::div(
                s(get_string('scenario_choice_none_available', 'local_adaptive_course_audit')),
                'local-adaptive-course-audit-step-actions'
            );
        }

        return html_writer::div(implode('', $links), 'local-adaptive-course-audit-step-actions');
    }

    /**
     * Build interactive steps for the compass scenario.
     *
     * Scenario 3 now guides one complete compass cycle with staged selection
     * steps on the course page and page-specific subtours for question,
     * completion, and section restriction setup.
     *
     * @param tour_manager $manager
     * @param int $courseid
     * @param int $maintourid
     * @param array $options
     * @return array
     */
    private static function build_compass_interactive_steps(
        tour_manager $manager,
        int $courseid,
        int $maintourid,
        array $options = []
    ): array
    {
        global $DB;

        $commonconfig = [
            'placement' => 'right',
            'orphan' => true,
            'backdrop' => true,
        ];

        $modinfo = get_fast_modinfo($courseid);
        $nextsectionnum = count($modinfo->get_section_info_all());
        $quizcmid = (int)($options['quiz_cmid'] ?? 0);
        $feedbackcmid = (int)($options['feedback_cmid'] ?? 0);
        $targetsectionid = (int)($options['target_sectionid'] ?? 0);
        $questionready = !empty($options['question_ready']);

        $actiontourconfig = [
            'displaystepnumbers' => true,
            'showtourwhen' => tour::SHOW_TOUR_UNTIL_COMPLETE,
            'backdrop' => true,
            'reflex' => false,
            'local_adaptive_course_audit_action' => 1,
            'local_adaptive_course_audit_courseid' => $courseid,
        ];

        if ($quizcmid <= 0) {
            $quizformtourid = null;
            try {
                $quizmanager = new tour_manager();
                $quiztour = $quizmanager->create_tour(
                    get_string('compass_quiz_tour_intro_title', 'local_adaptive_course_audit'),
                    get_string('compass_quiz_tour_intro_content', 'local_adaptive_course_audit'),
                    '/__aca_noop__',
                    array_merge($actiontourconfig, [
                        'local_adaptive_course_audit_key' => 'compass_quiz_setup_' . $courseid,
                    ]),
                    true,
                    get_string('compass_quiz_tour_intro_title', 'local_adaptive_course_audit'),
                    get_string('compass_quiz_tour_intro_content', 'local_adaptive_course_audit')
                );
                $quizmanager->add_step(
                    get_string('compass_quiz_step1_title', 'local_adaptive_course_audit'),
                    get_string('compass_quiz_step1_content', 'local_adaptive_course_audit'),
                    (string)target::TARGET_SELECTOR,
                    '#id_name',
                    $commonconfig
                );
                $quizmanager->add_step(
                    get_string('compass_quiz_step2_title', 'local_adaptive_course_audit'),
                    get_string('compass_quiz_step2_content', 'local_adaptive_course_audit'),
                    (string)target::TARGET_SELECTOR,
                    '#id_preferredbehaviour, #id_attempts',
                    $commonconfig
                );
                $quizmanager->add_step(
                    get_string('compass_quiz_step3_title', 'local_adaptive_course_audit'),
                    get_string('compass_quiz_step3_content', 'local_adaptive_course_audit'),
                    (string)target::TARGET_SELECTOR,
                    '#id_submitbutton, #id_submitbutton2',
                    $commonconfig
                );
                $quizformtourid = (int)$quiztour->get_id();
                $quizmanager->reset_tour_for_all_users($quizformtourid);
            } catch (\Throwable $exception) {
                debugging('Error creating compass quiz setup tour: ' . $exception->getMessage(), DEBUG_DEVELOPER);
            }

            $quizbuttonhtml = '';
            $quizurl = new \moodle_url('/course/modedit.php', [
                'add' => 'quiz',
                'course' => $courseid,
                'return' => 0,
                'section' => $nextsectionnum,
                'acaexpand' => 1,
            ]);
            if ($quizformtourid !== null) {
                $quizurl->param('startacatour', $quizformtourid);
            }
            $quizbutton = html_writer::link(
                $quizurl->out(false),
                s(get_string('scenario_3_step3_button', 'local_adaptive_course_audit')),
                ['class' => 'btn btn-primary']
            );
            $quizbuttonhtml = html_writer::div($quizbutton, 'local-adaptive-course-audit-step-actions');

            $manager->add_step(
                get_string('scenario_3_step1_title', 'local_adaptive_course_audit'),
                get_string('scenario_3_step1_content', 'local_adaptive_course_audit'),
                (string)target::TARGET_UNATTACHED,
                '',
                $commonconfig
            );
            $manager->add_step(
                get_string('scenario_3_step2_title', 'local_adaptive_course_audit'),
                get_string('scenario_3_step2_content', 'local_adaptive_course_audit'),
                (string)target::TARGET_UNATTACHED,
                '',
                $commonconfig
            );
            $manager->add_step(
                get_string('scenario_3_step3_title', 'local_adaptive_course_audit'),
                get_string('scenario_3_step3_content', 'local_adaptive_course_audit') .
                    $quizbuttonhtml .
                    self::render_quiz_selection_html($courseid, self::SCENARIO_COMPASS, 'quiz_cmid'),
                (string)target::TARGET_UNATTACHED,
                '',
                $commonconfig
            );
            $manager->reset_tour_for_all_users($maintourid);
            return [
                'status' => true,
                'tourid' => $maintourid,
            ];
        }

        if (!$questionready) {
            $quizcm = get_fast_modinfo($courseid)->get_cm($quizcmid);
            $questioncategory = self::get_default_question_category($courseid, $quizcm);
            $questioneditortourid = null;
            $questiongatewaytourid = null;

            try {
                $questionmanager = new tour_manager();
                $questiontour = $questionmanager->create_tour(
                    get_string('compass_feedback_tour_intro_title', 'local_adaptive_course_audit'),
                    get_string('compass_feedback_tour_intro_content', 'local_adaptive_course_audit'),
                    '/__aca_noop__',
                    array_merge($actiontourconfig, [
                        'local_adaptive_course_audit_key' => 'compass_question_editor_' . $courseid . '_' . $quizcmid,
                    ]),
                    true,
                    get_string('compass_feedback_tour_intro_title', 'local_adaptive_course_audit'),
                    get_string('compass_feedback_tour_intro_content', 'local_adaptive_course_audit')
                );
                $questionmanager->add_step(
                    get_string('compass_feedback_step1_title', 'local_adaptive_course_audit'),
                    get_string('compass_feedback_step1_content', 'local_adaptive_course_audit'),
                    (string)target::TARGET_SELECTOR,
                    'form.mform[data-qtype], #fitem_id_questiontext',
                    $commonconfig
                );
                $questionmanager->add_step(
                    get_string('compass_feedback_step2_title', 'local_adaptive_course_audit'),
                    get_string('compass_feedback_step2_content', 'local_adaptive_course_audit'),
                    (string)target::TARGET_SELECTOR,
                    '#id_answerhdr, .fcontainer.clearfix',
                    $commonconfig
                );
                $questionmanager->add_step(
                    get_string('compass_feedback_step3_title', 'local_adaptive_course_audit'),
                    get_string('compass_feedback_step3_content', 'local_adaptive_course_audit'),
                    (string)target::TARGET_SELECTOR,
                    '#fitem_id_generalfeedback, #id_combinedfeedbackhdr',
                    $commonconfig
                );
                $questionmanager->add_step(
                    get_string('compass_feedback_step4_title', 'local_adaptive_course_audit'),
                    get_string('compass_feedback_step4_content', 'local_adaptive_course_audit'),
                    (string)target::TARGET_SELECTOR,
                    '#id_updatebutton, #id_submitbutton',
                    $commonconfig
                );
                $questioneditortourid = (int)$questiontour->get_id();
                $questionmanager->reset_tour_for_all_users($questioneditortourid);
            } catch (\Throwable $exception) {
                debugging('Error creating compass question editor tour: ' . $exception->getMessage(), DEBUG_DEVELOPER);
            }

            if ($questioneditortourid !== null && $questioncategory !== null) {
                try {
                    $gatewaymanager = new tour_manager();
                    $returnurl = self::build_scenario_review_url($courseid, self::SCENARIO_COMPASS, [
                        'quiz_cmid' => $quizcmid,
                        'question_ready' => 1,
                    ]);
                    $questionediturl = new \moodle_url('/question/bank/editquestion/question.php', [
                        'qtype' => 'multichoice',
                        'category' => (int)$questioncategory->id,
                        'cmid' => $quizcmid,
                        'returnurl' => $returnurl->out_as_local_url(false),
                        'startacatour' => $questioneditortourid,
                    ]);
                    $openbutton = html_writer::link(
                        $questionediturl->out(false),
                        s(get_string('compass_feedback_open_editor_button', 'local_adaptive_course_audit')),
                        ['class' => 'btn btn-primary']
                    );
                    $gatewaytour = $gatewaymanager->create_tour(
                        get_string('compass_feedback_gateway_tour_intro_title', 'local_adaptive_course_audit'),
                        get_string('compass_feedback_gateway_tour_intro_content', 'local_adaptive_course_audit'),
                        '/__aca_noop__',
                        array_merge($actiontourconfig, [
                            'local_adaptive_course_audit_key' => 'compass_question_gateway_' . $courseid . '_' . $quizcmid,
                        ]),
                        true,
                        get_string('compass_feedback_gateway_tour_intro_title', 'local_adaptive_course_audit'),
                        get_string('compass_feedback_gateway_tour_intro_content', 'local_adaptive_course_audit')
                    );
                    $gatewaymanager->add_step(
                        get_string('compass_feedback_gateway_step1_title', 'local_adaptive_course_audit'),
                        get_string('compass_feedback_gateway_step1_content', 'local_adaptive_course_audit'),
                        (string)target::TARGET_SELECTOR,
                        '.mod-quiz-edit-content',
                        $commonconfig
                    );
                    $gatewaymanager->add_step(
                        get_string('compass_feedback_gateway_step2_title', 'local_adaptive_course_audit'),
                        get_string('compass_feedback_gateway_step2_content', 'local_adaptive_course_audit') .
                            html_writer::div($openbutton, 'local-adaptive-course-audit-step-actions'),
                        (string)target::TARGET_SELECTOR,
                        '.mod-quiz-edit-content',
                        $commonconfig
                    );
                    $questiongatewaytourid = (int)$gatewaytour->get_id();
                    $gatewaymanager->reset_tour_for_all_users($questiongatewaytourid);
                } catch (\Throwable $exception) {
                    debugging('Error creating compass question gateway tour: ' . $exception->getMessage(), DEBUG_DEVELOPER);
                }
            }

            try {
                $manager->delete_tour($maintourid);
                $DB->delete_records(self::TOUR_TABLE, ['tourid' => $maintourid]);
            } catch (\Throwable $exception) {
                debugging('Error deleting scenario 3 staging hub before quiz edit redirect: ' . $exception->getMessage(), DEBUG_DEVELOPER);
            }

            return [
                'status' => true,
                'tourid' => $questiongatewaytourid,
                'redirect' => new \moodle_url('/mod/quiz/edit.php', ['cmid' => $quizcmid]),
            ];
        }

        if ($feedbackcmid <= 0) {
            $pagetourid = null;
            try {
                $pagemanager = new tour_manager();
                $pagetour = $pagemanager->create_tour(
                    get_string('compass_orientation_tour_intro_title', 'local_adaptive_course_audit'),
                    get_string('compass_orientation_tour_intro_content', 'local_adaptive_course_audit'),
                    '/__aca_noop__',
                    array_merge($actiontourconfig, [
                        'local_adaptive_course_audit_key' => 'compass_feedback_page_' . $courseid,
                    ]),
                    true,
                    get_string('compass_orientation_tour_intro_title', 'local_adaptive_course_audit'),
                    get_string('compass_orientation_tour_intro_content', 'local_adaptive_course_audit')
                );
                $pagemanager->add_step(
                    get_string('compass_orientation_step1_title', 'local_adaptive_course_audit'),
                    get_string('compass_orientation_step1_content', 'local_adaptive_course_audit'),
                    (string)target::TARGET_SELECTOR,
                    '#id_name',
                    $commonconfig
                );
                $pagemanager->add_step(
                    get_string('compass_orientation_step2_title', 'local_adaptive_course_audit'),
                    get_string('compass_orientation_step2_content', 'local_adaptive_course_audit'),
                    (string)target::TARGET_SELECTOR,
                    '#id_contentsection',
                    $commonconfig
                );
                $pagemanager->add_step(
                    get_string('compass_orientation_step3_title', 'local_adaptive_course_audit'),
                    get_string('compass_orientation_step3_content', 'local_adaptive_course_audit'),
                    (string)target::TARGET_SELECTOR,
                    '#id_submitbutton, #id_submitbutton2',
                    $commonconfig
                );
                $pagetourid = (int)$pagetour->get_id();
                $pagemanager->reset_tour_for_all_users($pagetourid);
            } catch (\Throwable $exception) {
                debugging('Error creating scenario 3 feedback activity setup tour: ' . $exception->getMessage(), DEBUG_DEVELOPER);
            }

            $pageurl = new \moodle_url('/course/modedit.php', [
                'add' => 'page',
                'course' => $courseid,
                'return' => 0,
                'section' => $nextsectionnum,
                'acaexpand' => 1,
            ]);
            if ($pagetourid !== null) {
                $pageurl->param('startacatour', $pagetourid);
            }
            $pagebutton = html_writer::link(
                $pageurl->out(false),
                s(get_string('scenario_3_step4_button', 'local_adaptive_course_audit')),
                ['class' => 'btn btn-primary']
            );

            $manager->add_step(
                get_string('scenario_3_step4_title', 'local_adaptive_course_audit'),
                get_string('scenario_3_step4_content', 'local_adaptive_course_audit') .
                    html_writer::div($pagebutton, 'local-adaptive-course-audit-step-actions') .
                    self::render_course_module_selection_html(
                        $courseid,
                        self::SCENARIO_COMPASS,
                        'feedback_cmid',
                        [
                            'quiz_cmid' => $quizcmid,
                            'question_ready' => 1,
                        ],
                        'page'
                    ),
                (string)target::TARGET_UNATTACHED,
                '',
                $commonconfig
            );
            $manager->reset_tour_for_all_users($maintourid);
            return [
                'status' => true,
                'tourid' => $maintourid,
            ];
        }

        if ($targetsectionid <= 0) {
            $manager->add_step(
                get_string('scenario_3_step5_title', 'local_adaptive_course_audit'),
                get_string('scenario_3_step5_content', 'local_adaptive_course_audit') .
                    self::render_section_selection_html(
                        $courseid,
                        self::SCENARIO_COMPASS,
                        'target_sectionid',
                        [
                            'quiz_cmid' => $quizcmid,
                            'question_ready' => 1,
                            'feedback_cmid' => $feedbackcmid,
                        ]
                    ),
                (string)target::TARGET_UNATTACHED,
                '',
                $commonconfig
            );
            $manager->reset_tour_for_all_users($maintourid);
            return [
                'status' => true,
                'tourid' => $maintourid,
            ];
        }

        $finaltourid = null;
        try {
            $finalmanager = new tour_manager();
            $finaltour = $finalmanager->create_tour(
                get_string('scenario_3_repeat_tour_title', 'local_adaptive_course_audit'),
                get_string('scenario_3_repeat_tour_content', 'local_adaptive_course_audit'),
                '/__aca_noop__',
                array_merge($actiontourconfig, [
                    'local_adaptive_course_audit_key' => 'scenario3_repeat_' . $courseid . '_' . $feedbackcmid . '_' . $targetsectionid,
                ]),
                true,
                get_string('scenario_3_repeat_tour_title', 'local_adaptive_course_audit'),
                get_string('scenario_3_repeat_tour_content', 'local_adaptive_course_audit')
            );
            $finalmanager->add_step(
                get_string('scenario_3_repeat_step_title', 'local_adaptive_course_audit'),
                get_string('scenario_3_repeat_step_content', 'local_adaptive_course_audit'),
                (string)target::TARGET_UNATTACHED,
                '',
                $commonconfig
            );
            $finaltourid = (int)$finaltour->get_id();
            $finalmanager->reset_tour_for_all_users($finaltourid);
        } catch (\Throwable $exception) {
            debugging('Error creating scenario 3 repeat tour: ' . $exception->getMessage(), DEBUG_DEVELOPER);
        }

        $restrictiontourid = null;
        try {
            $restrictionmanager = new tour_manager();
            $backtocoursebuttonhtml = '';
            if ($finaltourid !== null) {
                $backtourl = new \moodle_url('/course/view.php', [
                    'id' => $courseid,
                    'startacatour' => $finaltourid,
                ]);
                $backbutton = html_writer::link(
                    $backtourl->out(false),
                    s(get_string('scenario_3_restriction_step2_button', 'local_adaptive_course_audit')),
                    ['class' => 'btn btn-primary']
                );
                $backtocoursebuttonhtml = html_writer::div($backbutton, 'local-adaptive-course-audit-step-actions');
            }

            $restrictiontour = $restrictionmanager->create_tour(
                get_string('scenario_3_restriction_tour_title', 'local_adaptive_course_audit'),
                get_string('scenario_3_restriction_tour_content', 'local_adaptive_course_audit'),
                '/__aca_noop__',
                array_merge($actiontourconfig, [
                    'local_adaptive_course_audit_key' => 'scenario3_restrict_' . $courseid . '_' . $targetsectionid,
                ]),
                true,
                get_string('scenario_3_restriction_tour_title', 'local_adaptive_course_audit'),
                get_string('scenario_3_restriction_tour_content', 'local_adaptive_course_audit')
            );
            $restrictionmanager->add_step(
                get_string('scenario_3_restriction_step1_title', 'local_adaptive_course_audit'),
                get_string('scenario_3_restriction_step1_content', 'local_adaptive_course_audit'),
                (string)target::TARGET_SELECTOR,
                'fieldset#id_availabilityconditions, #fitem_id_availabilityconditionsjson, #id_availabilityconditionsjson',
                $commonconfig
            );
            $restrictionmanager->add_step(
                get_string('scenario_3_restriction_step2_title', 'local_adaptive_course_audit'),
                get_string('scenario_3_restriction_step2_content', 'local_adaptive_course_audit') . $backtocoursebuttonhtml,
                (string)target::TARGET_SELECTOR,
                '#id_submitbutton, #id_submitbutton2',
                $commonconfig
            );
            $restrictiontourid = (int)$restrictiontour->get_id();
            $restrictionmanager->reset_tour_for_all_users($restrictiontourid);
        } catch (\Throwable $exception) {
            debugging('Error creating scenario 3 restriction tour: ' . $exception->getMessage(), DEBUG_DEVELOPER);
        }

        $completiontourid = null;
        try {
            $completionmanager = new tour_manager();
            $restrictionbuttonhtml = '';
            if ($restrictiontourid !== null) {
                $restricturl = new \moodle_url('/course/editsection.php', [
                    'id' => $targetsectionid,
                    'startacatour' => $restrictiontourid,
                    'acaexpand' => 1,
                ]);
                $restrictbutton = html_writer::link(
                    $restricturl->out(false),
                    s(get_string('scenario_3_completion_step2_button', 'local_adaptive_course_audit')),
                    ['class' => 'btn btn-primary']
                );
                $restrictionbuttonhtml = html_writer::div($restrictbutton, 'local-adaptive-course-audit-step-actions');
            }

            $completiontour = $completionmanager->create_tour(
                get_string('scenario_3_completion_tour_title', 'local_adaptive_course_audit'),
                get_string('scenario_3_completion_tour_content', 'local_adaptive_course_audit'),
                '/__aca_noop__',
                array_merge($actiontourconfig, [
                    'local_adaptive_course_audit_key' => 'scenario3_completion_' . $courseid . '_' . $feedbackcmid,
                ]),
                true,
                get_string('scenario_3_completion_tour_title', 'local_adaptive_course_audit'),
                get_string('scenario_3_completion_tour_content', 'local_adaptive_course_audit')
            );
            $completionmanager->add_step(
                get_string('scenario_3_completion_step1_title', 'local_adaptive_course_audit'),
                get_string('scenario_3_completion_step1_content', 'local_adaptive_course_audit'),
                (string)target::TARGET_SELECTOR,
                'fieldset#id_activitycompletionheader, #fitem_id_completion, #id_completion_2',
                $commonconfig
            );
            $completionmanager->add_step(
                get_string('scenario_3_completion_step2_title', 'local_adaptive_course_audit'),
                get_string('scenario_3_completion_step2_content', 'local_adaptive_course_audit') . $restrictionbuttonhtml,
                (string)target::TARGET_SELECTOR,
                '#id_submitbutton, #id_submitbutton2',
                $commonconfig
            );
            $completiontourid = (int)$completiontour->get_id();
            $completionmanager->reset_tour_for_all_users($completiontourid);
        } catch (\Throwable $exception) {
            debugging('Error creating scenario 3 completion tour: ' . $exception->getMessage(), DEBUG_DEVELOPER);
        }

        try {
            $manager->delete_tour($maintourid);
            $DB->delete_records(self::TOUR_TABLE, ['tourid' => $maintourid]);
        } catch (\Throwable $exception) {
            debugging('Error deleting scenario 3 staging hub before redirect: ' . $exception->getMessage(), DEBUG_DEVELOPER);
        }

        return [
            'status' => true,
            'tourid' => $completiontourid,
            'redirect' => new \moodle_url('/course/modedit.php', [
                'update' => $feedbackcmid,
                'return' => 0,
                'sr' => 0,
                'sesskey' => sesskey(),
                'acaexpand' => 1,
            ]),
        ];
    }

    /**
     * Build interactive steps for the sequential scenario.
     *
     * Scenario 2 now assumes existing adaptive elements and guides the user
     * through selecting a source section, target section, source activity,
     * and then configuring completion plus section restrictions.
     *
     * @param tour_manager $manager
     * @param int $courseid
     * @param int $maintourid
     * @param array $options
     * @return array
     */
    private static function build_sequential_interactive_steps(
        tour_manager $manager,
        int $courseid,
        int $maintourid,
        array $options = []
    ): array
    {
        global $DB;

        $commonconfig = [
            'placement' => 'right',
            'orphan' => true,
            'backdrop' => true,
        ];

        $sourcesectionid = (int)($options['source_sectionid'] ?? 0);
        $targetsectionid = (int)($options['target_sectionid'] ?? 0);
        $sourcecmid = (int)($options['source_cmid'] ?? 0);

        $actiontourconfig = [
            'displaystepnumbers' => true,
            'showtourwhen' => tour::SHOW_TOUR_UNTIL_COMPLETE,
            'backdrop' => true,
            'reflex' => false,
            'local_adaptive_course_audit_action' => 1,
            'local_adaptive_course_audit_courseid' => $courseid,
        ];
        if ($sourcesectionid <= 0) {
            $manager->add_step(
                get_string('scenario_2_step1_title', 'local_adaptive_course_audit'),
                get_string('scenario_2_step1_content', 'local_adaptive_course_audit'),
                (string)target::TARGET_UNATTACHED,
                '',
                $commonconfig
            );
            $manager->add_step(
                get_string('scenario_2_step2_title', 'local_adaptive_course_audit'),
                get_string('scenario_2_step2_content', 'local_adaptive_course_audit'),
                (string)target::TARGET_UNATTACHED,
                '',
                $commonconfig
            );
            $manager->add_step(
                get_string('scenario_2_step3_title', 'local_adaptive_course_audit'),
                get_string('scenario_2_step3_content', 'local_adaptive_course_audit') .
                    self::render_section_selection_html($courseid, self::SCENARIO_SEQUENTIAL, 'source_sectionid'),
                (string)target::TARGET_UNATTACHED,
                '',
                $commonconfig
            );
            $manager->reset_tour_for_all_users($maintourid);
            return [
                'status' => true,
                'tourid' => $maintourid,
            ];
        }

        if ($targetsectionid <= 0) {
            $manager->add_step(
                get_string('scenario_2_step4_title', 'local_adaptive_course_audit'),
                get_string('scenario_2_step4_content', 'local_adaptive_course_audit') .
                    self::render_section_selection_html(
                        $courseid,
                        self::SCENARIO_SEQUENTIAL,
                        'target_sectionid',
                        ['source_sectionid' => $sourcesectionid],
                        $sourcesectionid
                    ),
                (string)target::TARGET_UNATTACHED,
                '',
                $commonconfig
            );
            $manager->reset_tour_for_all_users($maintourid);
            return [
                'status' => true,
                'tourid' => $maintourid,
            ];
        }

        if ($sourcecmid <= 0) {
            $manager->add_step(
                get_string('scenario_2_step5_title', 'local_adaptive_course_audit'),
                get_string('scenario_2_step5_content', 'local_adaptive_course_audit') .
                    self::render_section_module_selection_html(
                        $courseid,
                        $sourcesectionid,
                        self::SCENARIO_SEQUENTIAL,
                        'source_cmid',
                        [
                            'source_sectionid' => $sourcesectionid,
                            'target_sectionid' => $targetsectionid,
                        ]
                    ),
                (string)target::TARGET_UNATTACHED,
                '',
                $commonconfig
            );
            $manager->reset_tour_for_all_users($maintourid);
            return [
                'status' => true,
                'tourid' => $maintourid,
            ];
        }

        $finaltourid = null;
        try {
            $finalmanager = new tour_manager();
            $finaltour = $finalmanager->create_tour(
                get_string('scenario_2_repeat_tour_title', 'local_adaptive_course_audit'),
                get_string('scenario_2_repeat_tour_content', 'local_adaptive_course_audit'),
                '/__aca_noop__',
                array_merge($actiontourconfig, [
                    'local_adaptive_course_audit_key' => 'scenario2_repeat_' . $courseid . '_' . $sourcesectionid . '_' . $targetsectionid,
                ]),
                true,
                get_string('scenario_2_repeat_tour_title', 'local_adaptive_course_audit'),
                get_string('scenario_2_repeat_tour_content', 'local_adaptive_course_audit')
            );
            $finalmanager->add_step(
                get_string('scenario_2_repeat_step_title', 'local_adaptive_course_audit'),
                get_string('scenario_2_repeat_step_content', 'local_adaptive_course_audit'),
                (string)target::TARGET_UNATTACHED,
                '',
                $commonconfig
            );
            $finaltourid = (int)$finaltour->get_id();
            $finalmanager->reset_tour_for_all_users($finaltourid);
        } catch (\Throwable $exception) {
            debugging('Error creating scenario 2 final repeat tour: ' . $exception->getMessage(), DEBUG_DEVELOPER);
        }

        $restrictiontourid = null;
        try {
            $restrictionmanager = new tour_manager();
            $backtocoursebuttonhtml = '';
            if ($finaltourid !== null) {
                $backtourl = new \moodle_url('/course/view.php', [
                    'id' => $courseid,
                    'startacatour' => $finaltourid,
                ]);
                $backbutton = html_writer::link(
                    $backtourl->out(false),
                    s(get_string('scenario_2_restriction_step2_button', 'local_adaptive_course_audit')),
                    ['class' => 'btn btn-primary']
                );
                $backtocoursebuttonhtml = html_writer::div($backbutton, 'local-adaptive-course-audit-step-actions');
            }

            $restrictiontour = $restrictionmanager->create_tour(
                get_string('scenario_2_restriction_tour_title', 'local_adaptive_course_audit'),
                get_string('scenario_2_restriction_tour_content', 'local_adaptive_course_audit'),
                '/__aca_noop__',
                array_merge($actiontourconfig, [
                    'local_adaptive_course_audit_key' => 'scenario2_restrict_' . $courseid . '_' . $targetsectionid,
                ]),
                true,
                get_string('scenario_2_restriction_tour_title', 'local_adaptive_course_audit'),
                get_string('scenario_2_restriction_tour_content', 'local_adaptive_course_audit')
            );
            $restrictionmanager->add_step(
                get_string('scenario_2_restriction_step1_title', 'local_adaptive_course_audit'),
                get_string('scenario_2_restriction_step1_content', 'local_adaptive_course_audit'),
                (string)target::TARGET_SELECTOR,
                'fieldset#id_availabilityconditions, #fitem_id_availabilityconditionsjson, #id_availabilityconditionsjson',
                $commonconfig
            );
            $restrictionmanager->add_step(
                get_string('scenario_2_restriction_step2_title', 'local_adaptive_course_audit'),
                get_string('scenario_2_restriction_step2_content', 'local_adaptive_course_audit') . $backtocoursebuttonhtml,
                (string)target::TARGET_SELECTOR,
                '#id_submitbutton, #id_submitbutton2',
                $commonconfig
            );
            $restrictiontourid = (int)$restrictiontour->get_id();
            $restrictionmanager->reset_tour_for_all_users($restrictiontourid);
        } catch (\Throwable $exception) {
            debugging('Error creating scenario 2 restriction tour: ' . $exception->getMessage(), DEBUG_DEVELOPER);
        }

        $completiontourid = null;
        try {
            $completionmanager = new tour_manager();
            $restrictionbuttonhtml = '';
            if ($restrictiontourid !== null) {
                $restricturl = new \moodle_url('/course/editsection.php', [
                    'id' => $targetsectionid,
                    'startacatour' => $restrictiontourid,
                    'acaexpand' => 1,
                ]);
                $restrictbutton = html_writer::link(
                    $restricturl->out(false),
                    s(get_string('scenario_2_completion_step2_button', 'local_adaptive_course_audit')),
                    ['class' => 'btn btn-primary']
                );
                $restrictionbuttonhtml = html_writer::div($restrictbutton, 'local-adaptive-course-audit-step-actions');
            }

            $completiontour = $completionmanager->create_tour(
                get_string('scenario_2_completion_tour_title', 'local_adaptive_course_audit'),
                get_string('scenario_2_completion_tour_content', 'local_adaptive_course_audit'),
                '/__aca_noop__',
                array_merge($actiontourconfig, [
                    'local_adaptive_course_audit_key' => 'scenario2_completion_' . $courseid . '_' . $sourcecmid,
                ]),
                true,
                get_string('scenario_2_completion_tour_title', 'local_adaptive_course_audit'),
                get_string('scenario_2_completion_tour_content', 'local_adaptive_course_audit')
            );
            $completionmanager->add_step(
                get_string('scenario_2_completion_step1_title', 'local_adaptive_course_audit'),
                get_string('scenario_2_completion_step1_content', 'local_adaptive_course_audit'),
                (string)target::TARGET_SELECTOR,
                'fieldset#id_activitycompletionheader, #fitem_id_completion, #id_completion_2',
                $commonconfig
            );
            $completionmanager->add_step(
                get_string('scenario_2_completion_step2_title', 'local_adaptive_course_audit'),
                get_string('scenario_2_completion_step2_content', 'local_adaptive_course_audit') . $restrictionbuttonhtml,
                (string)target::TARGET_SELECTOR,
                '#id_submitbutton, #id_submitbutton2',
                $commonconfig
            );
            $completiontourid = (int)$completiontour->get_id();
            $completionmanager->reset_tour_for_all_users($completiontourid);
        } catch (\Throwable $exception) {
            debugging('Error creating scenario 2 completion tour: ' . $exception->getMessage(), DEBUG_DEVELOPER);
        }

        try {
            $manager->delete_tour($maintourid);
            $DB->delete_records(self::TOUR_TABLE, ['tourid' => $maintourid]);
        } catch (\Throwable $exception) {
            debugging('Error deleting scenario 2 staging hub before redirect: ' . $exception->getMessage(), DEBUG_DEVELOPER);
        }

        $redirecturl = new \moodle_url('/course/modedit.php', [
            'update' => $sourcecmid,
            'return' => 0,
            'sr' => 0,
            'sesskey' => sesskey(),
            'acaexpand' => 1,
        ]);

        return [
            'status' => true,
            'tourid' => $completiontourid,
            'redirect' => $redirecturl,
        ];
    }

    /**
     * Build interactive steps for the minimalist scenario, split across three consecutive main tours.
     *
     * Tour 1 (T1, $maintourid): steps 1–5, launched immediately via startacatour URL param.
     *   - Step 5 contains a button that opens modedit.php and starts Subtour A (page creation).
     *   - When Subtour A ends it deletes T1, allowing T2 to auto-trigger on the next course view.
     *
     * Tour 2 (T2): step 6, auto-triggered on course/view.php once T1 is gone.
     *   - Step 6 contains a button that opens modedit.php and starts Subtour B (quiz creation).
     *   - When Subtour B ends it deletes T2, allowing T3 to auto-trigger.
     *
     * Tour 3 (T3): step 7, auto-triggered on course/view.php once T2 is gone.
     *
     * T2 and T3 are stored in the mapping table so lib.php knows to load CSS/sprites.
     *
     * @param tour_manager $manager Manager whose current tour is T1 (the first sequence tour).
     * @param int $courseid
     * @param int $maintourid ID of T1 (passed so Subtour A can store it as its prev_tourid).
     * @return void
     */
    private static function build_minimalist_interactive_steps(tour_manager $manager, int $courseid, int $maintourid): void
    {
        $commonconfig = [
            'placement' => 'right',
            'orphan' => true,
            'backdrop' => true,
        ];

        // Determine the section index for the "Add section" step (step 4).
        $modinfo = get_fast_modinfo($courseid);
        $nextsectionnum = count($modinfo->get_section_info_all());

        // Base config shared by all action (sub)tours.
        $actiontourconfig = [
            'displaystepnumbers' => true,
            'showtourwhen' => tour::SHOW_TOUR_UNTIL_COMPLETE,
            'backdrop' => true,
            'reflex' => false,
            'local_adaptive_course_audit_action' => 1,
            'local_adaptive_course_audit_courseid' => $courseid,
        ];

        // Config shared by T2 and T3 (sequence continuation tours that auto-trigger on course view).
        // Use a non-matching pathmatch so Moodle core bootstrap does NOT pick these up.
        // They are started via JS tour_launcher using the 'startacatour' configdata flag.
        $seqtourpathmatch = '/__aca_noop__';
        $seqtourconfig = [
            'displaystepnumbers' => true,
            'showtourwhen' => tour::SHOW_TOUR_UNTIL_COMPLETE,
            'backdrop' => true,
            'reflex' => false,
            'local_adaptive_course_audit_scenario' => self::SCENARIO_MINIMALIST,
            'local_adaptive_course_audit_courseid' => $courseid,
            'local_adaptive_course_audit_prev_tourid' => $maintourid,
            'startacatour' => true,   // auto-start on course view
        ];
        // -------------------------------------------------------------------------
        // Subtour A: Page creation — linked to T1 via prev_tourid.
        // When Subtour A ends, the observer will delete T1, unblocking T2.
        // -------------------------------------------------------------------------
        $subtourAId = null;
        try {
            $pageManager = new tour_manager();
            $pageTour = $pageManager->create_tour(
                get_string('minimalist_page_tour_intro_title', 'local_adaptive_course_audit'),
                get_string('minimalist_page_tour_intro_content', 'local_adaptive_course_audit'),
                '/__aca_noop__',
                array_merge($actiontourconfig, [
                    'local_adaptive_course_audit_key' => 'minimalist_page_creation',
                    'local_adaptive_course_audit_prev_tourid' => $maintourid,
                ]),
                true,
                get_string('minimalist_page_tour_intro_title', 'local_adaptive_course_audit'),
                get_string('minimalist_page_tour_intro_content', 'local_adaptive_course_audit')
            );
            $pageManager->add_step(
                get_string('minimalist_page_step1_title', 'local_adaptive_course_audit'),
                get_string('minimalist_page_step1_content', 'local_adaptive_course_audit'),
                (string)target::TARGET_SELECTOR,
                '#id_name',
                $commonconfig
            );
            $pageManager->add_step(
                get_string('minimalist_page_step2_title', 'local_adaptive_course_audit'),
                get_string('minimalist_page_step2_content', 'local_adaptive_course_audit'),
                (string)target::TARGET_SELECTOR,
                '#id_contentsection',
                $commonconfig
            );
            $pageManager->add_step(
                get_string('minimalist_page_step3_title', 'local_adaptive_course_audit'),
                get_string('minimalist_page_step3_content', 'local_adaptive_course_audit'),
                (string)target::TARGET_SELECTOR,
                '#id_submitbutton2',
                $commonconfig
            );
            $subtourAId = (int)$pageTour->get_id();
            $pageManager->reset_tour_for_all_users($subtourAId);
        } catch (\Throwable $exception) {
            debugging('Error creating minimalist page subtour: ' . $exception->getMessage(), DEBUG_DEVELOPER);
        }

        // -------------------------------------------------------------------------
        // Tour 2 (T2): step 6 — auto-triggers on course/view.php after T1 is deleted.
        // Created now so its ID is available for Subtour B's prev_tourid.
        // -------------------------------------------------------------------------
        $t2Manager = new tour_manager();
        $t2Id = null;
        try {
            $scenariotitle = get_string('scenario_' . self::SCENARIO_MINIMALIST . '_title', 'local_adaptive_course_audit');
            $t2Tour = $t2Manager->create_tour(
                get_string('scenario_tourname', 'local_adaptive_course_audit', $scenariotitle),
                get_string('scenario_tourdescription', 'local_adaptive_course_audit'),
                $seqtourpathmatch,
                $seqtourconfig,
                false   // no intro step — this is a mid-sequence continuation
            );
            $t2Id = (int)$t2Tour->get_id();
            // T2 mapping is NOT stored now — the unique constraint on courseid allows only one
            // mapping at a time, and T1 already occupies it. The observer inserts the T2 mapping
            // when Subtour A ends and T1 is deleted (see observer::tour_ended).
        } catch (\Throwable $exception) {
            debugging('Error creating minimalist T2 sequence tour: ' . $exception->getMessage(), DEBUG_DEVELOPER);
        }

        // -------------------------------------------------------------------------
        // Subtour B: Quiz creation — linked to T2 via prev_tourid.
        // When Subtour B ends, the observer will delete T2, unblocking T3.
        // -------------------------------------------------------------------------
        $subtourBId = null;
        try {
            $quizManager = new tour_manager();
            $quizTour = $quizManager->create_tour(
                get_string('minimalist_quiz_tour_intro_title', 'local_adaptive_course_audit'),
                get_string('minimalist_quiz_tour_intro_content', 'local_adaptive_course_audit'),
                '/__aca_noop__',
                array_merge($actiontourconfig, [
                    'local_adaptive_course_audit_key' => 'minimalist_quiz_creation',
                    'local_adaptive_course_audit_prev_tourid' => $t2Id ?? 0,
                ]),
                true,
                get_string('minimalist_quiz_tour_intro_title', 'local_adaptive_course_audit'),
                get_string('minimalist_quiz_tour_intro_content', 'local_adaptive_course_audit')
            );
            $quizManager->add_step(
                get_string('minimalist_quiz_step1_title', 'local_adaptive_course_audit'),
                get_string('minimalist_quiz_step1_content', 'local_adaptive_course_audit'),
                (string)target::TARGET_SELECTOR,
                '#id_name',
                $commonconfig
            );
            $quizManager->add_step(
                get_string('minimalist_quiz_step2_title', 'local_adaptive_course_audit'),
                get_string('minimalist_quiz_step2_content', 'local_adaptive_course_audit'),
                (string)target::TARGET_SELECTOR,
                '#id_preferredbehaviour',
                $commonconfig
            );
            $quizManager->add_step(
                get_string('minimalist_quiz_step3_title', 'local_adaptive_course_audit'),
                get_string('minimalist_quiz_step3_content', 'local_adaptive_course_audit'),
                (string)target::TARGET_SELECTOR,
                '#id_attempts',
                $commonconfig
            );
            $quizManager->add_step(
                get_string('minimalist_quiz_step4_title', 'local_adaptive_course_audit'),
                get_string('minimalist_quiz_step4_content', 'local_adaptive_course_audit'),
                (string)target::TARGET_SELECTOR,
                '#id_activitycompletionheader',
                $commonconfig
            );
            $quizManager->add_step(
                get_string('minimalist_quiz_step5_title', 'local_adaptive_course_audit'),
                get_string('minimalist_quiz_step5_content', 'local_adaptive_course_audit'),
                (string)target::TARGET_SELECTOR,
                '#id_submitbutton2',
                $commonconfig
            );
            $subtourBId = (int)$quizTour->get_id();
            $quizManager->reset_tour_for_all_users($subtourBId);
        } catch (\Throwable $exception) {
            debugging('Error creating minimalist quiz subtour: ' . $exception->getMessage(), DEBUG_DEVELOPER);
        }

        // -------------------------------------------------------------------------
        // Tour 3 (T3): step 7 — auto-triggers on course/view.php after T2 is deleted.
        // -------------------------------------------------------------------------
        $t3Manager = new tour_manager();
        $t3Id = null;
        try {
            $scenariotitle = get_string('scenario_' . self::SCENARIO_MINIMALIST . '_title', 'local_adaptive_course_audit');
            $t3Tour = $t3Manager->create_tour(
                get_string('scenario_tourname', 'local_adaptive_course_audit', $scenariotitle),
                get_string('scenario_tourdescription', 'local_adaptive_course_audit'),
                $seqtourpathmatch,
                [
                    'displaystepnumbers' => true,
                    'showtourwhen' => tour::SHOW_TOUR_UNTIL_COMPLETE,
                    'backdrop' => true,
                    'reflex' => false,
                    'local_adaptive_course_audit_scenario' => self::SCENARIO_MINIMALIST,
                    'local_adaptive_course_audit_courseid' => $courseid,
                    'local_adaptive_course_audit_prev_tourid' => $t2Id,
                    'startacatour' => true,   
                ],
                false   // no intro step
            );
            $t3Id = (int)$t3Tour->get_id();
            // T3 mapping is NOT stored now — same reason as T2 above.
            // The observer inserts the T3 mapping when Subtour B ends and T2 is deleted.
        } catch (\Throwable $exception) {
            debugging('Error creating minimalist T3 sequence tour: ' . $exception->getMessage(), DEBUG_DEVELOPER);
        }

        // -------------------------------------------------------------------------
        // Build action button HTML.
        // -------------------------------------------------------------------------
        $pageurl = new \moodle_url('/course/modedit.php', [
            'add' => 'page',
            'course' => $courseid,
            'return' => 0,
            'section' => $nextsectionnum,
        ]);
        if ($subtourAId !== null) {
            $pageurl->param('startacatour', $subtourAId);
        }
        $pagebtn = html_writer::link(
            $pageurl->out(false),
            s(get_string('scenario_1_step5_button', 'local_adaptive_course_audit')),
            ['class' => 'btn btn-primary']
        );
        $pagebtnhtml = html_writer::div($pagebtn, 'local-adaptive-course-audit-step-actions');

        $quizurl = new \moodle_url('/course/modedit.php', [
            'add' => 'quiz',
            'course' => $courseid,
            'return' => 0,
            'section' => $nextsectionnum,
        ]);
        if ($subtourBId !== null) {
            $quizurl->param('startacatour', $subtourBId);
        }
        $quizbtn = html_writer::link(
            $quizurl->out(false),
            s(get_string('scenario_1_step6_button', 'local_adaptive_course_audit')),
            ['class' => 'btn btn-primary']
        );
        $quizbtnhtml = html_writer::div($quizbtn, 'local-adaptive-course-audit-step-actions');

        // -------------------------------------------------------------------------
        // T1: steps 1–5 (intro step was added by create_tour in start_scenario_tour).
        // -------------------------------------------------------------------------
        $manager->add_step(
            get_string('scenario_1_step1_title', 'local_adaptive_course_audit'),
            get_string('scenario_1_step1_content', 'local_adaptive_course_audit'),
            (string)target::TARGET_UNATTACHED,
            '',
            $commonconfig
        );
        $manager->add_step(
            get_string('scenario_1_step2_title', 'local_adaptive_course_audit'),
            get_string('scenario_1_step2_content', 'local_adaptive_course_audit'),
            (string)target::TARGET_UNATTACHED,
            '',
            $commonconfig
        );
        $manager->add_step(
            get_string('scenario_1_step3_title', 'local_adaptive_course_audit'),
            get_string('scenario_1_step3_content', 'local_adaptive_course_audit'),
            (string)target::TARGET_UNATTACHED,
            '',
            $commonconfig
        );
        // Step 4: create a new section.
        $manager->add_step(
            get_string('scenario_1_step4_title', 'local_adaptive_course_audit'),
            get_string('scenario_1_step4_content', 'local_adaptive_course_audit'),
            (string)target::TARGET_SELECTOR,
            '#course-addsection',
            $commonconfig
        );
        // Step 5: add learning content page (button → Subtour A → course view → T2).
        $manager->add_step(
            get_string('scenario_1_step5_title', 'local_adaptive_course_audit'),
            get_string('scenario_1_step5_content', 'local_adaptive_course_audit') . $pagebtnhtml,
            (string)target::TARGET_SELECTOR,
            '.course-section:last-child',
            $commonconfig
        );

        // -------------------------------------------------------------------------
        // T2: step 6 — quiz action button (button → Subtour B → course view → T3).
        // -------------------------------------------------------------------------
        if ($t2Id !== null) {
            try {
                $t2Manager->add_step(
                    get_string('scenario_1_step6_title', 'local_adaptive_course_audit'),
                    get_string('scenario_1_step6_content', 'local_adaptive_course_audit') . $quizbtnhtml,
                    (string)target::TARGET_SELECTOR,
                    '.course-section:last-child',
                    $commonconfig
                );
            } catch (\Throwable $exception) {
                debugging('Error adding step to minimalist T2 tour: ' . $exception->getMessage(), DEBUG_DEVELOPER);
            }
        }

        // -------------------------------------------------------------------------
        // T3: step 7 — final summary step.
        // -------------------------------------------------------------------------
        if ($t3Id !== null) {
            try {
                $t3Manager->add_step(
                    get_string('scenario_1_step7_title', 'local_adaptive_course_audit'),
                    get_string('scenario_1_step7_content', 'local_adaptive_course_audit'),
                    (string)target::TARGET_UNATTACHED,
                    '',
                    $commonconfig
                );
            } catch (\Throwable $exception) {
                debugging('Error adding step to minimalist T3 tour: ' . $exception->getMessage(), DEBUG_DEVELOPER);
            }
        }

        // Reset all three main tours so the current user sees them from the start.
        $manager->reset_tour_for_all_users($maintourid);
        if ($t2Id !== null) {
            $t2Manager->reset_tour_for_all_users($t2Id);
        }
        if ($t3Id !== null) {
            $t3Manager->reset_tour_for_all_users($t3Id);
        }
    }

    /**
     * Remove any adaptive action tours for the given course.
     *
     * @param int $courseid
     * @return void
     */
    private static function delete_existing_action_tours(int $courseid): void
    {
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
     * @return array Map of action tour keys to tour IDs.
     */
    private static function create_action_tours($course, array $results): array
    {
        $coursecontext = context_course::instance($course->id);
        $courseshortname = format_string($course->shortname, true, ['context' => $coursecontext]);
        $actiontourmap = [];

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

                // Use a non-matching pathmatch; the tour is started via JS tour_launcher.
                $pathmatch = '/__aca_noop__';

                $tourkey = $action['tour']['key'] ?? sha1($action['tour']['pathmatch']);
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
                    $tour = $manager->create_tour(
                        $tourname,
                        $tourdescription,
                        $pathmatch,
                        $tourconfig,
                        true,
                        get_string('tourintro_teach_title', 'local_adaptive_course_audit'),
                        get_string('tourintro_teach_content', 'local_adaptive_course_audit')
                    );
                    $actiontourmap[$tourkey] = (int)$tour->get_id();
                    debugging('Created action tour ID: ' . $tour->get_id(), DEBUG_DEVELOPER);
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
                            self::format_action_tour_step_content($content, $coursecontext),
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

        return $actiontourmap;
    }
}
