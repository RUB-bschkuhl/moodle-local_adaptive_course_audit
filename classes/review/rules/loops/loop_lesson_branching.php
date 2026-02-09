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

/**
 * Loop rule: detect Lesson branching (conditional jumps based on answers).
 *
 * @package     local_adaptive_course_audit
 */
class loop_lesson_branching extends rule_base {
    /** @var string Rule identifier. */
    public const rule_key = 'loop_lesson_branching';

    /** @var string Target type. */
    public const target_type = 'section';

    /**
     * Constructor.
     */
    public function __construct() {
        parent::__construct(
            self::rule_key,
            self::target_type,
            get_string('rule_loop_lesson_branching_name', 'local_adaptive_course_audit'),
            get_string('rule_loop_lesson_branching_description', 'local_adaptive_course_audit'),
            'hint'
        );
    }

    /**
     * Evaluate lesson branching within a section.
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

        $lessoncms = array_values(array_filter($modules, static function($cm) {
            return $cm->modname === 'lesson';
        }));
        if (empty($lessoncms)) {
            return null;
        }

        $messages = [];
        $messages[] = get_string('rule_loop_lesson_branching_rationale', 'local_adaptive_course_audit');

        $status = false;
        $branchinglessons = [];
        foreach ($lessoncms as $lessoncm) {
            if (empty($lessoncm->instance)) {
                continue;
            }

            // Get all answers for this lesson and look for explicit page jumps.
            $answers = [];
            try {
                $answers = $DB->get_records(
                    'lesson_answers',
                    ['lessonid' => (int)$lessoncm->instance],
                    '',
                    'id, pageid, jumpto'
                );
            } catch (\Throwable $exception) {
                debugging('Error loading lesson answers for adaptive audit: ' . $exception->getMessage(), DEBUG_DEVELOPER);
                continue;
            }

            if (empty($answers)) {
                $messages[] = get_string('rule_loop_lesson_branching_lesson_no_answers', 'local_adaptive_course_audit', $lessoncm->name);
                continue;
            }

            $jumptos = [];
            foreach ($answers as $answer) {
                $jumpto = isset($answer->jumpto) ? (int)$answer->jumpto : 0;
                // In lessons, jumpto > 0 points to a specific page id (explicit branching).
                if ($jumpto > 0) {
                    $jumptos[$jumpto] = true;
                }
            }

            if (count($jumptos) >= 2) {
                $status = true;
                $branchinglessons[] = (string)$lessoncm->name;
                $messages[] = get_string('rule_loop_lesson_branching_found', 'local_adaptive_course_audit', $lessoncm->name);
            } else {
                $messages[] = get_string('rule_loop_lesson_branching_missing', 'local_adaptive_course_audit', $lessoncm->name);
            }
        }

        $actions = [];
        $firstlesson = $lessoncms[0] ?? null;
        if (!empty($firstlesson) && !empty($firstlesson->id)) {
            $actions[] = [
                'label' => get_string('touraction_open_lesson_editor', 'local_adaptive_course_audit', $firstlesson->name),
                'url' => (new \moodle_url('/mod/lesson/edit.php', ['id' => (int)$firstlesson->id])),
                'type' => 'secondary',
            ];
        }

        $headline = $status
            ? get_string('rule_loop_lesson_branching_headline_success', 'local_adaptive_course_audit')
            : get_string('rule_loop_lesson_branching_headline_needs_work', 'local_adaptive_course_audit');

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

