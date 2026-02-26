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
    global $PAGE, $DB, $USER, $OUTPUT;

    if (!has_capability('local/adaptive_course_audit:view', $context)) {
        return;
    }

    $url = new moodle_url('/local/adaptive_course_audit/review.php', ['courseid' => $course->id]);
    $nodekey = 'local_adaptive_course_audit_review';

    if ($navigation->find($nodekey, navigation_node::TYPE_CUSTOM)) {
        return;
    }

    $reviewnode = $navigation->add(
        get_string('reviewcoursenode', 'local_adaptive_course_audit'),
        $url,
        navigation_node::TYPE_CUSTOM,
        null,
        $nodekey,
        new pix_icon('i/report', '')
    );

    // If the user previously started an audit tour in this course (and it hasn't ended yet),
    // show a quick navigation link to rerun/continue it.
    $hasmanagecap = has_capability('moodle/course:manageactivities', $context);
    if ($hasmanagecap && !empty($USER) && !empty($USER->id)) {
        try {
            $lastreview = $DB->get_record(
                'local_adaptive_course_review',
                ['courseid' => (int)$course->id, 'userid' => (int)$USER->id],
                'id, sectionid',
                IGNORE_MISSING
            );
            if (!empty($lastreview) && !empty($lastreview->id)) {
                $resumeparams = [
                    'courseid' => (int)$course->id,
                    'action' => 'startreview',
                    'sesskey' => sesskey(),
                ];
                if (!empty($lastreview->sectionid) && (int)$lastreview->sectionid > 0) {
                    $resumeparams['sectionid'] = (int)$lastreview->sectionid;
                }
                $reviewstarturl = new moodle_url('/local/adaptive_course_audit/review.php', $resumeparams);

                // Ensure editing mode is enabled before launching the audit tour flow.
                // Editing toggle redirects, so we preserve the actual start URL via `return`.
                $resumeurl = new moodle_url('/course/view.php', [
                    'id' => (int)$course->id,
                    'edit' => 1,
                    'sesskey' => sesskey(),
                    'return' => $reviewstarturl->out_as_local_url(false),
                ]);

                if ($navigation->find('local_adaptive_course_audit_review_resume', navigation_node::TYPE_CUSTOM)) {
                    return;
                }
                // Note: the course secondary navigation (moremenu) does not render navigation_node icons.
                // If we want an icon here, we must embed it in the node text.
                $resumetext = get_string('reviewcoursenode_resume', 'local_adaptive_course_audit') . ' ' . 
                $OUTPUT->pix_icon('i/reload', '');

                $navigation->add(
                    $resumetext,
                    $resumeurl,
                    navigation_node::TYPE_CUSTOM,
                    null,
                    'local_adaptive_course_audit_review_resume',
                    new pix_icon('i/reload', '')
                );
            }
        } catch (Throwable $exception) {
            debugging('Error checking adaptive course audit resume link: ' . $exception->getMessage(), DEBUG_DEVELOPER);
        }
    }

    $hastour = false;
    try {
        $hastour = $DB->record_exists('local_adaptive_course_tour', ['courseid' => $course->id]);
    } catch (Throwable $exception) {
        debugging('Error checking adaptive course audit tour mapping: ' . $exception->getMessage(), DEBUG_DEVELOPER);
    }

    $iscourseview = strpos((string)$PAGE->pagetype, 'course-view') === 0;
    $ismodedit = (strpos((string)$PAGE->url->get_path(), '/course/modedit.php') !== false);
    $ismodquizedit = (strpos((string)$PAGE->url->get_path(), '/mod/quiz/edit.php') !== false);
    $acatourid = optional_param('startacatour', 0, PARAM_INT);
    $shouldlaunch = $acatourid > 0 && ($iscourseview || $ismodedit || $ismodquizedit);
    $shouldexpandmodedit = $ismodedit && optional_param('acaexpand', 0, PARAM_INT) > 0;

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

    // Expand relevant modedit fieldsets when coming from an audit step link.
    if ($shouldexpandmodedit) {
        try {
            $PAGE->requires->js_call_amd(
                'local_adaptive_course_audit/modedit_expander',
                'init'
            );
        } catch (Throwable $exception) {
            debugging('Error loading adaptive course audit modedit expander: ' . $exception->getMessage(), DEBUG_DEVELOPER);
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
