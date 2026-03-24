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
 * Course rule: check whether "Activity names auto-linking" is enabled in this course.
 *
 * When enabled, teachers can reference activity names in feedback (questions/quizzes) and Moodle will auto-link them.
 *
 * @package     local_adaptive_course_audit
 */
final class loop_course_filter_activitynames_autolinking extends rule_base {
    /** @var string Rule identifier. */
    public const rule_key = 'course_filter_activitynames_autolinking';

    /** @var string Target type. */
    public const target_type = 'course';

    /** @var string Filter shortname for Activity names auto-linking. */
    private const FILTER_NAME = 'activitynames';

    /**
     * Constructor.
     */
    public function __construct() {
        parent::__construct(
            self::rule_key,
            self::target_type,
            get_string('rule_course_filter_activitynames_name', 'local_adaptive_course_audit'),
            get_string('rule_course_filter_activitynames_description', 'local_adaptive_course_audit'),
            'hint'
        );
    }

    /**
     * Evaluate filter availability and state in the course context.
     *
     * @param object|null $target Unused (course-level rule).
     * @param object|null $course Course record.
     * @return object|null
     */
    public function check_target($target, $course = null) {
        if (empty($course) || empty($course->id)) {
            return null;
        }

        $coursecontext = null;
        try {
            $coursecontext = \context_course::instance((int)$course->id);
        } catch (\Throwable $exception) {
            $coursecontext = null;
        }
        if ($coursecontext === null) {
            return null;
        }

        $rationale = get_string('rule_course_filter_activitynames_rationale', 'local_adaptive_course_audit');

        $available = [];
        try {
            $available = filter_get_available_in_context($coursecontext);
        } catch (\Throwable $exception) {
            debugging('Error reading available filters in course context: ' . $exception->getMessage(), DEBUG_DEVELOPER);
            $available = [];
        }

        $filteravailable = is_array($available) && isset($available[self::FILTER_NAME]);

        $active = [];
        try {
            $active = filter_get_active_in_context($coursecontext);
        } catch (\Throwable $exception) {
            debugging('Error reading active filters in course context: ' . $exception->getMessage(), DEBUG_DEVELOPER);
            $active = [];
        }

        $enabled = is_array($active) && array_key_exists(self::FILTER_NAME, $active);

        $messages = [$rationale];
        if (!$filteravailable) {
            $messages[] = get_string('rule_course_filter_activitynames_notavailable', 'local_adaptive_course_audit');
        } else if (!$enabled) {
            $messages[] = get_string('rule_course_filter_activitynames_missing', 'local_adaptive_course_audit');
        }

        $actions = [];
        if ($filteravailable) {
            $actions[] = [
                'label' => get_string('touraction_open_course_filters', 'local_adaptive_course_audit'),
                'url' => new \moodle_url('/filter/manage.php', [
                    'contextid' => (int)$coursecontext->id,
                ]),
                'type' => 'secondary',
            ];
        }

        $status = ($filteravailable && $enabled);
        $headline = $status
            ? get_string('rule_course_filter_activitynames_headline_success', 'local_adaptive_course_audit')
            : get_string('rule_course_filter_activitynames_headline_needs_work', 'local_adaptive_course_audit');

        return $this->create_result(
            $status,
            $messages,
            0,
            (int)$course->id,
            $actions,
            $headline
        );
    }
}

