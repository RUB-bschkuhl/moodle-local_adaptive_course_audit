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
final class service {
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
    private static function format_plain_text_as_html(string $text): string {
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
    private static function format_rationale_block(string $rationale, ?context_course $context = null): string {
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
    private static function format_action_tour_step_content(string $content, context_course $context): string {
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
    public static function start_review(int $courseid, ?int $sectionid = null): array {
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
    private static function store_latest_audit_review_start(int $courseid, int $userid, ?int $sectionid): void {
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
    public static function start_teach_tour(int $courseid, int $cmid, string $teachkey): array {
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
    private static function build_teach_tour_steps(string $teachkey, string $quizname): array {
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
    private static function delete_existing_action_tour_by_key(int $courseid, string $key): void {
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
    private static function add_loop_results_as_steps(tour_manager $manager, array $results, array $actiontourmap = []): void {
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
     * @return array Array with status and optional message.
     */
    public static function start_scenario_tour(int $courseid, int $scenario): array {
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
    private static function build_scenario_steps(int $scenario): array {
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
     * @return array Map of action tour keys to tour IDs.
     */
    private static function create_action_tours($course, array $results): array {
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

