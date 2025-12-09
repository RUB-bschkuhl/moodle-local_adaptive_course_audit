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

/**
 * Library callbacks for Adaptive course audit.
 *
 * @package     local_adaptive_course_audit
 * @copyright   2025 Moodle HQ
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Adds the Adaptive course audit link to the course secondary navigation.
 *
 * @param navigation_node $navigation Course navigation node to extend.
 * @param stdClass $course Course record.
 * @param context $context Course context.
 */
function local_adaptive_course_audit_extend_navigation_course(
    navigation_node $navigation,
    stdClass $course,
    context $context
): void {
    if (!has_capability('local/adaptive_course_audit:view', $context)) {
        return;
    }

    $url = new moodle_url('/local/adaptive_course_audit/review.php', ['courseid' => $course->id]);
    $nodekey = 'local_adaptive_course_audit_review';

    if ($navigation->find($nodekey, navigation_node::TYPE_CUSTOM)) {
        return;
    }

    $navigation->add(
        get_string('reviewcoursenode', 'local_adaptive_course_audit'),
        $url,
        navigation_node::TYPE_CUSTOM,
        null,
        $nodekey,
        new pix_icon('i/report', '')
    );
}

