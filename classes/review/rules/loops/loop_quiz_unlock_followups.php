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

use local_adaptive_course_audit\review\rules\mod_classifier;
use local_adaptive_course_audit\review\rules\rule_base;
use tool_usertours\target;

/**
 * Quiz unlock follow-ups rule: checks for knowledge building -> quiz -> dependent follow-ups.
 *
 * @package     local_adaptive_course_audit
 */
class loop_quiz_unlock_followups extends rule_base {
    /** @var string Rule identifier. */
    public const rule_key = 'loop_quiz_unlock_followups';

    /** @var string Target type. */
    public const target_type = 'section';

    /**
     * Constructor.
     */
    public function __construct() {
        parent::__construct(
            self::rule_key,
            self::target_type,
            get_string('rule_loop_quiz_unlock_followups_name', 'local_adaptive_course_audit'),
            get_string('rule_loop_quiz_unlock_followups_description', 'local_adaptive_course_audit'),
            'hint'
        );
    }

    /**
     * Evaluate the loop pattern in a section.
     *
     * @param object $target Section object containing modules.
     * @param object|null $course Course record.
     * @return object|null
     */
    public function check_target($target, $course = null) {
        if (empty($target) || empty($course)) {
            return null;
        }

        $rationale = get_string('rule_loop_quiz_unlock_followups_rationale', 'local_adaptive_course_audit');

        $modules = $this->get_visible_modules($target);

        //Regel minimale Items in Section.
        if (count($modules) < 3) {
            return $this->create_result(
                false,
                [
                    $rationale,
                    get_string('rule_loop_quiz_unlock_followups_min_items', 'local_adaptive_course_audit', 3),
                ],
                (int)$target->section,
                (int)$course->id,
                [],
                get_string('rule_loop_quiz_unlock_followups_headline_min_items', 'local_adaptive_course_audit')
            );
        }

        $quizzes = array_filter($modules, function ($cm) {
            return $cm->modname === 'quiz';
        });

        //Regel missing quiz in Section.
        if (empty($quizzes)) {
            $addquizurl = new \moodle_url('/course/modedit.php', [
                'add' => 'quiz',
                'course' => (int)$course->id,
                'section' => (int)$target->section,
                'return' => 0,
                'sr' => 0,
                'sesskey' => sesskey(),
            ]);
            // Pathmatch uses wildcard before params since Moodle may add sr/return params first.
            $addquizpathmatch = '/course/modedit.php%add=quiz%section=' . (int)$target->section . '%course=' . (int)$course->id . '%';
            $actions = [
                [
                    'label' => get_string('touraction_add_quiz', 'local_adaptive_course_audit'),
                    'url' => $addquizurl,
                    'type' => 'primary',
                    'tour' => [
                        'key' => 'loop_quiz_unlock_followups_add_quiz_section_' . (int)$target->section,
                        'pathmatch' => $addquizpathmatch,
                        'steps' => [
                            [
                                'title' => get_string('actiontour_loop_quiz_unlock_followups_addquiz_step_name_title', 'local_adaptive_course_audit'),
                                'content' => get_string('actiontour_loop_quiz_unlock_followups_addquiz_step_name_body', 'local_adaptive_course_audit'),
                                'targettype' => (string)target::TARGET_SELECTOR,
                                'targetvalue' => '#id_name',
                                'config' => [
                                    'placement' => 'right',
                                    'backdrop' => true,
                                ],
                            ],
                            [
                                'title' => get_string('actiontour_loop_quiz_unlock_followups_addquiz_step_completion_title', 'local_adaptive_course_audit'),
                                'content' => get_string('actiontour_loop_quiz_unlock_followups_addquiz_step_completion_body', 'local_adaptive_course_audit'),
                                'targettype' => (string)target::TARGET_SELECTOR,
                                'targetvalue' => 'fieldset#id_activitycompletionheader',
                                'config' => [
                                    'placement' => 'right',
                                    'backdrop' => true,
                                ],
                            ],
                            [
                                'title' => get_string('actiontour_loop_quiz_unlock_followups_addquiz_step_access_title', 'local_adaptive_course_audit'),
                                'content' => get_string('actiontour_loop_quiz_unlock_followups_addquiz_step_access_body', 'local_adaptive_course_audit'),
                                'targettype' => (string)target::TARGET_SELECTOR,
                                'targetvalue' => 'fieldset#id_availabilityconditionsheader',
                                'config' => [
                                    'placement' => 'right',
                                    'backdrop' => true,
                                ],
                            ],
                        ],
                    ],
                ],
            ];

            return $this->create_result(
                false,
                [
                    $rationale,
                    get_string('rule_loop_quiz_unlock_followups_missing_quiz', 'local_adaptive_course_audit'),
                ],
                (int)$target->section,
                (int)$course->id,
                $actions,
                get_string('rule_loop_quiz_unlock_followups_headline_missing_quiz', 'local_adaptive_course_audit')
            );
        }

        $knowledgebuilding = array_filter($modules, function ($cm) {
            if ($cm->modname === 'quiz') {
                return false;
            }
            return mod_classifier::is_module_in_category($cm->modname, mod_classifier::MOD_WISSENSAUFBAU);
        });

        //Regel missing knowledge building in Section.
        if (empty($knowledgebuilding)) {
            return $this->create_result(
                false,
                [
                    $rationale,
                    get_string('rule_loop_quiz_unlock_followups_missing_kb', 'local_adaptive_course_audit'),
                ],
                (int)$target->section,
                (int)$course->id,
                [],
                get_string('rule_loop_quiz_unlock_followups_headline_missing_kb', 'local_adaptive_course_audit')
            );
        }

        $quizwithavailability = $this->find_quiz_with_availability($quizzes);

        if (!$quizwithavailability) {
            $quizcm = reset($quizzes);
            $actions = [];

            if ($quizcm !== false) {
                $editquizurl = new \moodle_url('/course/modedit.php', [
                    'update' => (int)$quizcm->id,
                    'return' => 0,
                    'sr' => 0,
                    'sesskey' => sesskey(),
                ]);
                // Pathmatch uses wildcard before and after update param since Moodle may reorder params.
                $editquizpathmatch = '/course/modedit.php%update=' . (int)$quizcm->id . '%';
                $actions[] = [
                    'label' => get_string('touraction_edit_quiz_settings', 'local_adaptive_course_audit', $quizcm->name),
                    'url' => $editquizurl,
                    'type' => 'secondary',
                    'tour' => [
                        'key' => 'loop_quiz_unlock_followups_quiz_settings_' . (int)$quizcm->id,
                        'pathmatch' => $editquizpathmatch,
                        'steps' => [
                            [
                                'title' => get_string('actiontour_loop_quiz_unlock_followups_editquiz_step_access_title', 'local_adaptive_course_audit'),
                                'content' => get_string('actiontour_loop_quiz_unlock_followups_editquiz_step_access_body', 'local_adaptive_course_audit'),
                                'targettype' => (string)target::TARGET_SELECTOR,
                                'targetvalue' => 'fieldset#id_availabilityconditionsheader',
                                'config' => [
                                    'placement' => 'right',
                                    'backdrop' => true,
                                ],
                            ],
                            [
                                'title' => get_string('actiontour_loop_quiz_unlock_followups_editquiz_step_completion_title', 'local_adaptive_course_audit'),
                                'content' => get_string('actiontour_loop_quiz_unlock_followups_editquiz_step_completion_body', 'local_adaptive_course_audit'),
                                'targettype' => (string)target::TARGET_SELECTOR,
                                'targetvalue' => 'fieldset#id_activitycompletionheader',
                                'config' => [
                                    'placement' => 'right',
                                    'backdrop' => true,
                                ],
                            ],
                        ],
                    ],
                ];
            }

            return $this->create_result(
                false,
                [
                    $rationale,
                    get_string('rule_loop_quiz_unlock_followups_quiz_no_precondition', 'local_adaptive_course_audit'),
                ],
                (int)$target->section,
                (int)$course->id,
                $actions,
                get_string('rule_loop_quiz_unlock_followups_headline_quiz_no_precondition', 'local_adaptive_course_audit')
            );
        }

        [$dependentcount, $dependents] = $this->count_modules_depending_on_quiz($modules, (int)$quizwithavailability->id);

        if ($dependentcount === 0) {
            return $this->create_result(
                false,
                [
                    $rationale,
                    get_string('rule_loop_quiz_unlock_followups_no_followups', 'local_adaptive_course_audit'),
                ],
                (int)$target->section,
                (int)$course->id,
                [],
                get_string('rule_loop_quiz_unlock_followups_headline_no_followups', 'local_adaptive_course_audit')
            );
        }

        $messages = [
            $rationale,
            get_string('rule_loop_quiz_unlock_followups_success', 'local_adaptive_course_audit'),
        ];

        if ($dependentcount < 2) {
            $messages[] = get_string('rule_loop_quiz_unlock_followups_additional_followups', 'local_adaptive_course_audit', [
                'activity' => $quizwithavailability->name,
            ]);
        }

        if (!empty($dependents)) {
            $messages[] = get_string('rule_loop_quiz_unlock_followups_followup_list', 'local_adaptive_course_audit', [
                'count' => $dependentcount,
                'items' => implode(', ', $dependents),
            ]);
        }

        return $this->create_result(
            true,
            $messages,
            (int)$target->section,
            (int)$course->id,
            [],
            get_string('rule_loop_quiz_unlock_followups_headline_success', 'local_adaptive_course_audit')
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

    /**
     * Find a quiz with availability rules configured.
     *
     * @param array $quizzes Array of course module objects.
     * @return object|null
     */
    private function find_quiz_with_availability(array $quizzes) {
        foreach ($quizzes as $quiz) {
            if (!empty($quiz->availability) && trim((string)$quiz->availability) !== '') {
                return $quiz;
            }
        }
        return null;
    }

    /**
     * Count modules that depend on a quiz via completion availability.
     *
     * @param array $modules All modules in the section.
     * @param int $quizcmid The course module id of the quiz.
     * @return array [int count, array names]
     */
    private function count_modules_depending_on_quiz(array $modules, int $quizcmid): array {
        $count = 0;
        $names = [];

        foreach ($modules as $module) {
            if (empty($module->availability)) {
                continue;
            }

            if ($this->availability_depends_on_cm((string)$module->availability, $quizcmid)) {
                $count++;
                $names[] = $module->name;
            }
        }

        return [$count, $names];
    }

    /**
     * Determine whether availability JSON contains completion dependency on a CM.
     *
     * @param string $availabilityjson JSON string.
     * @param int $cmid Course module id to look for.
     * @return bool
     */
    private function availability_depends_on_cm(string $availabilityjson, int $cmid): bool {
        $decoded = json_decode($availabilityjson);
        if (!is_object($decoded) || empty($decoded->c) || !is_array($decoded->c)) {
            return false;
        }

        foreach ($decoded->c as $condition) {
            if (isset($condition->type) && $condition->type === 'completion' && isset($condition->cm)) {
                if ((int)$condition->cm === $cmid) {
                    return true;
                }
            }
        }

        return false;
    }
}

