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
    global $PAGE, $DB;

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

    $hastour = false;
    try {
        $hastour = $DB->record_exists('local_adaptive_course_tour', ['courseid' => $course->id]);
    } catch (Throwable $exception) {
        debugging('Error checking adaptive course audit tour mapping: ' . $exception->getMessage(), DEBUG_DEVELOPER);
    }

    $iscourseview = strpos((string)$PAGE->pagetype, 'course-view') === 0;
    $ismodedit = (strpos((string)$PAGE->url->get_path(), '/course/modedit.php') !== false);
    $acatourid = optional_param('startacatour', 0, PARAM_INT);
    $shouldlaunch = $acatourid > 0 && ($iscourseview || $ismodedit);

    // Load the JS tour launcher when the URL parameter is present.
    if ($shouldlaunch) {
        try {
            $PAGE->requires->js_call_amd(
                'local_adaptive_course_audit/tour_launcher',
                'init'
            );
        } catch (Throwable $exception) {
            debugging('Error loading adaptive course audit tour launcher: ' . $exception->getMessage(), DEBUG_DEVELOPER);
        }
    }

    // Load CSS and sprites when a tour is expected (existing DB mapping or explicit launch).
    if (($hastour && $iscourseview) || $shouldlaunch) {
        try {
            $PAGE->requires->css(new moodle_url('/local/adaptive_course_audit/styles.css'));
            $PAGE->requires->js_call_amd(
                'local_adaptive_course_audit/tour_sprites',
                'init',
                [
                    'talkSpriteUrl' => (new moodle_url('/local/adaptive_course_audit/pix/miau_talk_sprite.png'))->out(false),
                    'winkSpriteUrl' => (new moodle_url('/local/adaptive_course_audit/pix/miau_wink_sprite.png'))->out(false),
                ]
            );
        } catch (Throwable $exception) {
            debugging('Error loading adaptive course audit sprites: ' . $exception->getMessage(), DEBUG_DEVELOPER);
        }
    }
}

