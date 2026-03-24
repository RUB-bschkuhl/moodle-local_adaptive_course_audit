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
 * Loop rule: detect random questions in a quiz (question pool / random slots).
 *
 * In Moodle 4.5, random-question slots are represented via records in
 * {question_set_references} with component 'mod_quiz', questionarea 'slot'
 * and itemid = quiz_slots.id.
 *
 * @package     local_adaptive_course_audit
 */
class loop_quiz_random_questions extends rule_base {
    /** @var string Rule identifier. */
    public const rule_key = 'loop_quiz_random_questions';

    /** @var string Target type. */
    public const target_type = 'section';

    /**
     * Constructor.
     */
    public function __construct() {
        parent::__construct(
            self::rule_key,
            self::target_type,
            get_string('rule_loop_quiz_random_questions_name', 'local_adaptive_course_audit'),
            get_string('rule_loop_quiz_random_questions_description', 'local_adaptive_course_audit'),
            'hint'
        );
    }

    /**
     * Evaluate random question usage for quizzes in a section.
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

        $modules = $this->get_visible_modules($target);
        if (empty($modules)) {
            return null;
        }

        $quizcms = array_values(array_filter($modules, static function($cm) {
            return $cm->modname === 'quiz';
        }));
        if (empty($quizcms)) {
            return null;
        }

        $messages = [];
        $messages[] = get_string('rule_loop_quiz_random_questions_rationale', 'local_adaptive_course_audit');

        $status = false;
        $actions = [];

        foreach ($quizcms as $quizcm) {
            if (empty($quizcm->instance)) {
                continue;
            }

            $slotcount = 0;
            $randomslotcount = 0;
            try {
                $slotcount = (int)$DB->count_records('quiz_slots', ['quizid' => (int)$quizcm->instance]);
                $randomslotcount = (int)$DB->count_records_sql("
                    SELECT COUNT(1)
                      FROM {quiz_slots} slot
                      JOIN {question_set_references} qsr
                        ON qsr.component = 'mod_quiz'
                       AND qsr.questionarea = 'slot'
                       AND qsr.itemid = slot.id
                     WHERE slot.quizid = ?
                ", [(int)$quizcm->instance]);
            } catch (\Throwable $exception) {
                debugging('Error checking quiz random questions for adaptive audit: ' . $exception->getMessage(), DEBUG_DEVELOPER);
                continue;
            }

            if ($slotcount <= 0) {
                $messages[] = get_string('rule_loop_quiz_random_questions_empty', 'local_adaptive_course_audit', $quizcm->name);
                continue;
            }

            if ($randomslotcount > 0) {
                $status = true;
                $messages[] = get_string('rule_loop_quiz_random_questions_found', 'local_adaptive_course_audit', [
                    'quiz' => $quizcm->name,
                    'count' => $randomslotcount,
                ]);
            } else {
                $messages[] = get_string('rule_loop_quiz_random_questions_missing', 'local_adaptive_course_audit', $quizcm->name);

                // Provide a quick link to the quiz editing page for adding random questions.
                $editurl = new \moodle_url('/mod/quiz/edit.php', ['cmid' => (int)$quizcm->id]);
                $pathmatch = '/mod/quiz/edit.php%cmid=' . (int)$quizcm->id . '%';
                $actions[] = [
                    'label' => get_string('touraction_open_quiz_edit', 'local_adaptive_course_audit', $quizcm->name),
                    'url' => $editurl,
                    'type' => 'secondary',
                    'tour' => [
                        'key' => 'quiz_random_questions_' . (int)$quizcm->id,
                        'pathmatch' => $pathmatch,
                        'steps' => [
                            [
                                'title' => get_string('actiontour_quizrandomquestions_step_add_title', 'local_adaptive_course_audit'),
                                'content' => get_string('actiontour_quizrandomquestions_step_add_body', 'local_adaptive_course_audit'),
                                'targettype' => (string)target::TARGET_SELECTOR,
                                'targetvalue' => '.last-add-menu a',
                                'config' => [
                                    'placement' => 'right',
                                    'backdrop' => true,
                                ],
                            ],
                        ],
                    ],
                ];
            }
        }

        $headline = $status
            ? get_string('rule_loop_quiz_random_questions_headline_success', 'local_adaptive_course_audit')
            : get_string('rule_loop_quiz_random_questions_headline_needs_work', 'local_adaptive_course_audit');

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

