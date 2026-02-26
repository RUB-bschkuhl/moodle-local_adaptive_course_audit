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

use local_adaptive_course_audit\review\availability_helper;
use local_adaptive_course_audit\review\rules\rule_base;
use tool_usertours\target;

/**
 * Loop rule: detect branching paths based on quiz grades (Restrict access by grade).
 *
 * This is a classic adaptive pattern: remedial path for lower scores, advanced path for higher scores.
 *
 * @package     local_adaptive_course_audit
 */
class loop_branch_by_grade extends rule_base {
    /** @var string Rule identifier. */
    public const rule_key = 'loop_branch_by_grade';

    /** @var string Target type. */
    public const target_type = 'section';

    /**
     * Constructor.
     */
    public function __construct() {
        parent::__construct(
            self::rule_key,
            self::target_type,
            get_string('rule_loop_branch_by_grade_name', 'local_adaptive_course_audit'),
            get_string('rule_loop_branch_by_grade_description', 'local_adaptive_course_audit'),
            'hint'
        );
    }

    /**
     * Evaluate the grade-branching pattern in a section.
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

        $rationale = get_string('rule_loop_branch_by_grade_rationale', 'local_adaptive_course_audit');

        $modules = $this->get_visible_modules($target);
        if (count($modules) < 3) {
            // Too little structure to talk about branches.
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
            debugging('Adaptive course audit: quiz CM missing instance id for grade branching check.', DEBUG_DEVELOPER);
            return null;
        }

        // Resolve grade item for this quiz (main grade item).
        $gradeitem = null;
        try {
            $gradeitem = $DB->get_record('grade_items', [
                'courseid' => (int)$course->id,
                'itemtype' => 'mod',
                'itemmodule' => 'quiz',
                'iteminstance' => (int)$quizcm->instance,
                'itemnumber' => 0,
            ], 'id', IGNORE_MISSING);
        } catch (\Throwable $exception) {
            debugging('Error resolving quiz grade item for adaptive audit: ' . $exception->getMessage(), DEBUG_DEVELOPER);
        }

        if (empty($gradeitem) || empty($gradeitem->id)) {
            return $this->create_result(
                false,
                [
                    $rationale,
                    get_string('rule_loop_branch_by_grade_missing_gradeitem', 'local_adaptive_course_audit', $quizcm->name),
                ],
                (int)$target->section,
                (int)$course->id,
                [],
                get_string('rule_loop_branch_by_grade_headline_missing_gradeitem', 'local_adaptive_course_audit')
            );
        }

        // Find follow-up activities restricted by this quiz's grade item.
        $branches = [];
        foreach ($modules as $module) {
            if ($module->modname === 'quiz' || empty($module->availability)) {
                continue;
            }

            $conditions = availability_helper::get_grade_conditions((string)$module->availability);
            foreach ($conditions as $condition) {
                if ((int)$condition['id'] !== (int)$gradeitem->id) {
                    continue;
                }
                $branches[] = (object)[
                    'cmid' => (int)$module->id,
                    'name' => (string)$module->name,
                    'min' => $condition['min'],
                    'max' => $condition['max'],
                ];
            }
        }

        if (empty($branches)) {
            $actions = [];
            // Offer an action tour to add a grade restriction to one follow-up activity (best-effort).
            $candidate = $this->find_first_nonquiz_module_after_quiz($modules, $quizcm);
            if ($candidate !== null) {
                $editurl = new \moodle_url('/course/modedit.php', [
                    'update' => (int)$candidate->id,
                    'return' => 0,
                    'sr' => 0,
                    'sesskey' => sesskey(),
                ]);
                $pathmatch = '/course/modedit.php%update=' . (int)$candidate->id . '%';
                $actions[] = [
                    'label' => get_string('touraction_add_grade_gate', 'local_adaptive_course_audit', $candidate->name),
                    'url' => $editurl,
                    'type' => 'secondary',
                    'tour' => [
                        'key' => 'grade_branch_gate_' . (int)$candidate->id,
                        'pathmatch' => $pathmatch,
                        'steps' => [
                            [
                                'title' => get_string('actiontour_gradegate_step_access_title', 'local_adaptive_course_audit'),
                                'content' => get_string('actiontour_gradegate_step_access_body', 'local_adaptive_course_audit'),
                                'targettype' => (string)target::TARGET_SELECTOR,
                                'targetvalue' => 'fieldset#id_availabilityconditionsheader',
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
                    get_string('rule_loop_branch_by_grade_missing', 'local_adaptive_course_audit', $quizcm->name),
                ],
                (int)$target->section,
                (int)$course->id,
                $actions,
                get_string('rule_loop_branch_by_grade_headline_missing', 'local_adaptive_course_audit')
            );
        }

        // Summarise branch ranges.
        $branchlabels = [];
        $haslow = false;
        $hashigh = false;
        foreach ($branches as $branch) {
            $rangetext = $this->format_minmax($branch->min, $branch->max);
            $branchlabels[] = $branch->name . ' (' . $rangetext . ')';
            if ($branch->max !== null) {
                $haslow = true;
            }
            if ($branch->min !== null) {
                $hashigh = true;
            }
        }

        $messages = [
            $rationale,
            get_string('rule_loop_branch_by_grade_found', 'local_adaptive_course_audit', [
                'quiz' => $quizcm->name,
                'branches' => implode(', ', $branchlabels),
            ]),
        ];

        if (!$haslow || !$hashigh) {
            $messages[] = get_string('rule_loop_branch_by_grade_suggest_two_paths', 'local_adaptive_course_audit', $quizcm->name);
        }

        return $this->create_result(
            true,
            $messages,
            (int)$target->section,
            (int)$course->id,
            [],
            get_string('rule_loop_branch_by_grade_headline_success', 'local_adaptive_course_audit')
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
     * Find the first non-quiz module after the quiz in section order (for grade gate action tour).
     *
     * @param array $modules Visible modules in section order.
     * @param object $quizcm The quiz course module.
     * @return object|null
     */
    private function find_first_nonquiz_module_after_quiz(array $modules, object $quizcm): ?object {
        $passedquiz = false;
        foreach ($modules as $module) {
            if ((int)$module->id === (int)$quizcm->id) {
                $passedquiz = true;
                continue;
            }
            if ($passedquiz && !empty($module->uservisible) && empty($module->deletioninprogress) && $module->modname !== 'quiz') {
                return $module;
            }
        }
        return null;
    }

    /**
     * Format min/max bounds into a human-readable range.
     *
     * Values are stored as percentages in Moodle availability JSON.
     *
     * @param float|null $min
     * @param float|null $max
     * @return string
     */
    private function format_minmax(?float $min, ?float $max): string {
        if ($min === null && $max === null) {
            return get_string('rule_loop_branch_by_grade_range_any', 'local_adaptive_course_audit');
        }
        if ($min !== null && $max === null) {
            return get_string('rule_loop_branch_by_grade_range_min', 'local_adaptive_course_audit', $min);
        }
        if ($min === null && $max !== null) {
            return get_string('rule_loop_branch_by_grade_range_max', 'local_adaptive_course_audit', $max);
        }
        return get_string('rule_loop_branch_by_grade_range_between', 'local_adaptive_course_audit', [
            'min' => $min,
            'max' => $max,
        ]);
    }
}

