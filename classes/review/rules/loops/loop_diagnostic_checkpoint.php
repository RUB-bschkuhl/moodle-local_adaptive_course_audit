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
 * Loop rule: diagnostic checkpoint (survey/choice/feedback) + signposting.
 *
 * Moodle core does not gate content by specific answers, so we focus on:
 * - A checkpoint that captures needs / self-assessment
 * - Clear signposting after the checkpoint to help learners choose next steps
 * - Optional completion-gated follow-ups to avoid skipping ahead.
 *
 * @package     local_adaptive_course_audit
 */
class loop_diagnostic_checkpoint extends rule_base {
    /** @var string Rule identifier. */
    public const rule_key = 'loop_diagnostic_checkpoint';

    /** @var string Target type. */
    public const target_type = 'section';

    /** @var string[] */
    private const DIAGNOSTIC_MODS = ['choice', 'feedback', 'survey'];

    /**
     * Constructor.
     */
    public function __construct() {
        parent::__construct(
            self::rule_key,
            self::target_type,
            get_string('rule_loop_diagnostic_checkpoint_name', 'local_adaptive_course_audit'),
            get_string('rule_loop_diagnostic_checkpoint_description', 'local_adaptive_course_audit'),
            'hint'
        );
    }

    /**
     * Evaluate diagnostic checkpoint + signposting in a section.
     *
     * @param object $target Section object containing modules.
     * @param object|null $course Course record.
     * @return object|null
     */
    public function check_target($target, $course = null) {
        if (empty($target) || empty($course)) {
            return null;
        }

        $rationale = get_string('rule_loop_diagnostic_checkpoint_rationale', 'local_adaptive_course_audit');

        $modules = $this->get_visible_modules($target);
        if (count($modules) < 2) {
            return null;
        }

        $diagnosticindex = null;
        $diagnostic = null;
        foreach ($modules as $idx => $module) {
            if (in_array((string)$module->modname, self::DIAGNOSTIC_MODS, true)) {
                $diagnosticindex = $idx;
                $diagnostic = $module;
                break;
            }
        }

        if ($diagnostic === null) {
            $addurl = new \moodle_url('/course/modedit.php', [
                'add' => 'feedback',
                'course' => (int)$course->id,
                'section' => (int)$target->section,
                'return' => 0,
                'sr' => 0,
                'sesskey' => sesskey(),
            ]);
            $pathmatch = '/course/modedit.php%add=feedback%section=' . (int)$target->section . '%course=' . (int)$course->id . '%';

            $actions = [
                [
                    'label' => get_string('touraction_add_diagnostic', 'local_adaptive_course_audit'),
                    'url' => $addurl,
                    'type' => 'primary',
                    'tour' => [
                        'key' => 'diagnostic_add_feedback_section_' . (int)$target->section,
                        'pathmatch' => $pathmatch,
                        'steps' => [
                            [
                                'title' => get_string('actiontour_diagnostic_step_name_title', 'local_adaptive_course_audit'),
                                'content' => get_string('actiontour_diagnostic_step_name_body', 'local_adaptive_course_audit'),
                                'targettype' => (string)target::TARGET_SELECTOR,
                                'targetvalue' => '#id_name',
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
                    get_string('rule_loop_diagnostic_checkpoint_missing', 'local_adaptive_course_audit'),
                ],
                (int)$target->section,
                (int)$course->id,
                $actions,
                get_string('rule_loop_diagnostic_checkpoint_headline_missing', 'local_adaptive_course_audit')
            );
        }

        $messages = [
            $rationale,
            get_string('rule_loop_diagnostic_checkpoint_found', 'local_adaptive_course_audit', $diagnostic->name),
        ];

        // Signposting: look for label/page immediately after diagnostic.
        $hassignposting = false;
        if ($diagnosticindex !== null) {
            for ($i = $diagnosticindex + 1; $i < min(count($modules), $diagnosticindex + 4); $i++) {
                $next = $modules[$i];
                if (in_array((string)$next->modname, ['label', 'page'], true)) {
                    $hassignposting = true;
                    break;
                }
            }
        }

        if (!$hassignposting) {
            $messages[] = get_string('rule_loop_diagnostic_checkpoint_missing_signposting', 'local_adaptive_course_audit');
        }

        // Optional: check for any follow-ups that are gated by completion of the diagnostic activity.
        $dependentcount = 0;
        foreach ($modules as $module) {
            if (empty($module->availability)) {
                continue;
            }
            $cmids = availability_helper::get_completion_cmids((string)$module->availability);
            if (in_array((int)$diagnostic->id, $cmids, true)) {
                $dependentcount++;
            }
        }

        if ($dependentcount === 0) {
            $messages[] = get_string('rule_loop_diagnostic_checkpoint_suggest_gate', 'local_adaptive_course_audit', $diagnostic->name);
        } else {
            $messages[] = get_string('rule_loop_diagnostic_checkpoint_gated_followups', 'local_adaptive_course_audit', $dependentcount);
        }

        $status = ($hassignposting && $dependentcount > 0);
        $headline = $status
            ? get_string('rule_loop_diagnostic_checkpoint_headline_success', 'local_adaptive_course_audit')
            : get_string('rule_loop_diagnostic_checkpoint_headline_needs_work', 'local_adaptive_course_audit');

        $actions = [];
        $editurl = new \moodle_url('/course/modedit.php', [
            'update' => (int)$diagnostic->id,
            'return' => 0,
            'sr' => 0,
            'sesskey' => sesskey(),
        ]);
        $pathmatch = '/course/modedit.php%update=' . (int)$diagnostic->id . '%';
        $actions[] = [
            'label' => get_string('touraction_edit_diagnostic', 'local_adaptive_course_audit', $diagnostic->name),
            'url' => $editurl,
            'type' => 'secondary',
            'tour' => [
                'key' => 'diagnostic_edit_' . (int)$diagnostic->id,
                'pathmatch' => $pathmatch,
                'steps' => [
                    [
                        'title' => get_string('actiontour_diagnostic_step_access_title', 'local_adaptive_course_audit'),
                        'content' => get_string('actiontour_diagnostic_step_access_body', 'local_adaptive_course_audit'),
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

