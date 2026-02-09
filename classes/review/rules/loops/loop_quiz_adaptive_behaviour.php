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
 * Loop rule: detect adaptive quiz question behaviour (adaptive/interactive modes).
 *
 * @package     local_adaptive_course_audit
 */
class loop_quiz_adaptive_behaviour extends rule_base {
    /** @var string Rule identifier. */
    public const rule_key = 'loop_quiz_adaptive_behaviour';

    /** @var string Target type. */
    public const target_type = 'section';

    /** @var string[] Behaviours that support multiple tries / practice loops. */
    private const SUPPORTED_BEHAVIOURS = [
        'adaptive',
        'adaptivenopenalty',
        'interactive',
        'interactivecountback',
    ];

    /**
     * Constructor.
     */
    public function __construct() {
        parent::__construct(
            self::rule_key,
            self::target_type,
            get_string('rule_loop_quiz_adaptive_behaviour_name', 'local_adaptive_course_audit'),
            get_string('rule_loop_quiz_adaptive_behaviour_description', 'local_adaptive_course_audit'),
            'hint'
        );
    }

    /**
     * Evaluate quiz behaviour settings in a section.
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

        $quizdetails = [];
        foreach ($quizcms as $quizcm) {
            if (empty($quizcm->instance)) {
                continue;
            }

            try {
                $quiz = $DB->get_record(
                    'quiz',
                    ['id' => (int)$quizcm->instance],
                    'id, name, preferredbehaviour, attempts',
                    IGNORE_MISSING
                );
            } catch (\Throwable $exception) {
                debugging('Error loading quiz for adaptive behaviour audit: ' . $exception->getMessage(), DEBUG_DEVELOPER);
                continue;
            }

            if (empty($quiz)) {
                continue;
            }

            $quizdetails[] = (object)[
                'cmid' => (int)$quizcm->id,
                'name' => (string)$quizcm->name,
                'behaviour' => (string)($quiz->preferredbehaviour ?? ''),
                'attempts' => isset($quiz->attempts) ? (int)$quiz->attempts : 0,
            ];
        }

        if (empty($quizdetails)) {
            return null;
        }

        $supported = array_values(array_filter($quizdetails, function($detail) {
            return in_array($detail->behaviour, self::SUPPORTED_BEHAVIOURS, true);
        }));

        $messages = [];
        $messages[] = get_string('rule_loop_quiz_adaptive_behaviour_rationale', 'local_adaptive_course_audit');

        foreach ($quizdetails as $detail) {
            $messages[] = get_string('rule_loop_quiz_adaptive_behaviour_found_quiz', 'local_adaptive_course_audit', [
                'quiz' => $detail->name,
                'behaviour' => $detail->behaviour !== '' ? $detail->behaviour : get_string('rule_loop_quiz_adaptive_behaviour_behaviour_unknown', 'local_adaptive_course_audit'),
            ]);
        }

        $actions = [];
        $status = !empty($supported);

        if (!$status) {
            $candidate = $quizdetails[0];
            $messages[] = get_string('rule_loop_quiz_adaptive_behaviour_missing', 'local_adaptive_course_audit', $candidate->name);

            $editurl = new \moodle_url('/course/modedit.php', [
                'update' => (int)$candidate->cmid,
                'return' => 0,
                'sr' => 0,
                'sesskey' => sesskey(),
            ]);
            $pathmatch = '/course/modedit.php%update=' . (int)$candidate->cmid . '%';

            $actions[] = [
                'label' => get_string('touraction_edit_quiz_behaviour', 'local_adaptive_course_audit', $candidate->name),
                'url' => $editurl,
                'type' => 'secondary',
                'tour' => [
                    'key' => 'quiz_behaviour_settings_' . (int)$candidate->cmid,
                    'pathmatch' => $pathmatch,
                    'steps' => [
                        [
                            'title' => get_string('actiontour_quizbehaviour_step_behaviour_title', 'local_adaptive_course_audit'),
                            'content' => get_string('actiontour_quizbehaviour_step_behaviour_body', 'local_adaptive_course_audit'),
                            'targettype' => (string)target::TARGET_SELECTOR,
                            'targetvalue' => '#id_preferredbehaviour',
                            'config' => [
                                'placement' => 'right',
                                'backdrop' => true,
                            ],
                        ],
                    ],
                ],
            ];
        } else {
            $usednames = array_map(static function($detail) {
                return $detail->name;
            }, $supported);
            $messages[] = get_string('rule_loop_quiz_adaptive_behaviour_success', 'local_adaptive_course_audit', implode(', ', $usednames));
        }

        $headline = $status
            ? get_string('rule_loop_quiz_adaptive_behaviour_headline_success', 'local_adaptive_course_audit')
            : get_string('rule_loop_quiz_adaptive_behaviour_headline_needs_work', 'local_adaptive_course_audit');

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

