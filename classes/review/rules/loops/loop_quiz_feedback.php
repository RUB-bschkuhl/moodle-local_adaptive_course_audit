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

namespace local_adaptive_course_audit\review\rules\loops;

defined('MOODLE_INTERNAL') || die();

use local_adaptive_course_audit\review\rules\rule_base;
use tool_usertours\target;

/**
 * Loop rule: quiz feedback quality (overall feedback + actionable next steps).
 *
 * @package     local_adaptive_course_audit
 */
class loop_quiz_feedback extends rule_base {
    /** @var string Rule identifier. */
    public const rule_key = 'loop_quiz_feedback';

    /** @var string Target type. */
    public const target_type = 'section';

    /**
     * Constructor.
     */
    public function __construct() {
        parent::__construct(
            self::rule_key,
            self::target_type,
            get_string('rule_loop_quiz_feedback_name', 'local_adaptive_course_audit'),
            get_string('rule_loop_quiz_feedback_description', 'local_adaptive_course_audit'),
            'hint'
        );
    }

    /**
     * Evaluate quiz feedback quality in a section.
     *
     * @param object $target Section object containing modules.
     * @param object|null $course Course record.
     * @return object|null
     */
    public function check_target($target, $course = null) {
        global $DB;

        if (empty($target) || empty($course)) {
            return null;
        }

        $rationale = get_string('rule_loop_quiz_feedback_rationale', 'local_adaptive_course_audit');

        $modules = $this->get_visible_modules($target);
        if (empty($modules)) {
            return null;
        }

        $quizzes = array_values(array_filter($modules, static function($cm) {
            return $cm->modname === 'quiz';
        }));
        if (empty($quizzes)) {
            return null;
        }

        $quizcm = $quizzes[0];
        if (empty($quizcm->instance)) {
            debugging('Adaptive course audit: quiz CM missing instance id for feedback check.', DEBUG_DEVELOPER);
            return null;
        }

        $quiz = null;
        try {
            $quiz = $DB->get_record('quiz', ['id' => (int)$quizcm->instance], 'id, name, attempts', IGNORE_MISSING);
        } catch (\Throwable $exception) {
            debugging('Error loading quiz for adaptive audit: ' . $exception->getMessage(), DEBUG_DEVELOPER);
        }
        if (empty($quiz)) {
            return null;
        }

        $feedbacks = [];
        try {
            $feedbacks = $DB->get_records('quiz_feedback', ['quizid' => (int)$quiz->id], 'mingrade DESC');
        } catch (\Throwable $exception) {
            debugging('Error loading quiz overall feedback for adaptive audit: ' . $exception->getMessage(), DEBUG_DEVELOPER);
        }

        $hasfeedback = false;
        if (!empty($feedbacks)) {
            foreach ($feedbacks as $feedback) {
                $text = (string)($feedback->feedbacktext ?? '');
                if (trim($text) === '') {
                    continue;
                }
                $hasfeedback = true;
            }
        }

        $attempts = isset($quiz->attempts) ? (int)$quiz->attempts : 0;
        $multipleattempts = ($attempts === 0 || $attempts > 1);

        $messages = [$rationale];
        $actions = [];

        $editurl = new \moodle_url('/course/modedit.php', [
            'update' => (int)$quizcm->id,
            'return' => 0,
            'sr' => 0,
            'sesskey' => sesskey(),
        ]);
        $pathmatch = '/course/modedit.php%update=' . (int)$quizcm->id . '%';

        $actions[] = [
            'label' => get_string('touraction_edit_quiz_feedback', 'local_adaptive_course_audit', $quizcm->name),
            'url' => $editurl,
            'type' => 'secondary',
            'tour' => [
                'key' => 'quiz_feedback_settings_' . (int)$quizcm->id,
                'pathmatch' => $pathmatch,
                'steps' => [
                    [
                        'title' => get_string('actiontour_quizfeedback_step_overallfeedback_title', 'local_adaptive_course_audit'),
                        'content' => get_string('actiontour_quizfeedback_step_overallfeedback_body', 'local_adaptive_course_audit'),
                        'targettype' => (string)target::TARGET_SELECTOR,
                        'targetvalue' => '#id_overallfeedbackhdr',
                        'config' => [
                            'placement' => 'right',
                            'backdrop' => true,
                        ],
                    ],
                    [
                        'title' => get_string('actiontour_quizfeedback_step_attempts_title', 'local_adaptive_course_audit'),
                        'content' => get_string('actiontour_quizfeedback_step_attempts_body', 'local_adaptive_course_audit'),
                        'targettype' => (string)target::TARGET_SELECTOR,
                        'targetvalue' => '#id_attempts',
                        'config' => [
                            'placement' => 'right',
                            'backdrop' => true,
                        ],
                    ],
                ],
            ],
        ];

        if (!$hasfeedback) {
            $messages[] = get_string('rule_loop_quiz_feedback_missing', 'local_adaptive_course_audit', $quizcm->name);
            $messages[] = get_string('rule_loop_quiz_feedback_missing_links', 'local_adaptive_course_audit', $quizcm->name);
            if (!$multipleattempts) {
                $messages[] = get_string('rule_loop_quiz_feedback_suggest_attempts', 'local_adaptive_course_audit', $quizcm->name);
            }
        } else {
            $messages[] = get_string('rule_loop_quiz_feedback_found', 'local_adaptive_course_audit', $quizcm->name);
        }

        $status = $hasfeedback;
        $headline = $status
            ? get_string('rule_loop_quiz_feedback_headline_success', 'local_adaptive_course_audit')
            : get_string('rule_loop_quiz_feedback_headline_needs_work', 'local_adaptive_course_audit');

        return $this->create_result(
            $status,
            $messages,
            (int)$target->section,
            (int)$course->id,
            $actions,
            $headline
        );
    }

    /**
     * Filter visible modules in the section.
     *
     * @param object $section Section object enriched with modules.
     * @return array
     */
    private function get_visible_modules($section): array {
        $visible = [];
        if (!empty($section->modules)) {
            foreach ($section->modules as $module) {
                if (!empty($module->uservisible) && empty($module->deletioninprogress)) {
                    $visible[] = $module;
                }
            }
        }
        return $visible;
    }
}

