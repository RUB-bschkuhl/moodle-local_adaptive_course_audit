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

use local_adaptive_course_audit\review\service as review_service;

require_once(__DIR__ . '/../../config.php');

$courseid = required_param('courseid', PARAM_INT);
$action = optional_param('action', '', PARAM_ALPHANUMEXT);
$sectionid = optional_param('sectionid', 0, PARAM_INT);
$course = get_course($courseid);
require_login($course);

$context = context_course::instance($course->id);
if (!$context) {
    throw new moodle_exception('invalidcourseid');
}
/** @var context $context */
require_capability('local/adaptive_course_audit:view', $context);

$url = new moodle_url('/local/adaptive_course_audit/review.php', ['courseid' => $course->id]);
$coursename = format_string($course->fullname, true, ['context' => $context]);
$pageheading = get_string('reviewcourseheading', 'local_adaptive_course_audit');

$PAGE->set_url($url);
$PAGE->set_context($context);
$PAGE->set_pagelayout('incourse');
$PAGE->set_title($pageheading);
$PAGE->set_heading($coursename);
$PAGE->navbar->add($pageheading);
navigation_node::override_active_url($url);
$PAGE->requires->css(new moodle_url('/local/adaptive_course_audit/styles.css'));

if ($action === 'startreview') {
    require_sesskey();

    try {
        $reviewresult = review_service::start_review((int)$course->id, $sectionid > 0 ? $sectionid : null);
        if (!empty($reviewresult['status'])) {
            redirect(
                new moodle_url('/course/view.php', ['id' => $course->id])
            );
        }

        $failuremessage = !empty($reviewresult['message'])
            ? $reviewresult['message']
            : get_string('startreviewerror', 'local_adaptive_course_audit');

        redirect($url, $failuremessage, 0, \core\output\notification::NOTIFY_ERROR);
    } catch (moodle_exception $exception) {
        debugging('Error in course audit: ' . $exception->getMessage(), DEBUG_DEVELOPER);
        redirect($url, $exception->getMessage(), 0, \core\output\notification::NOTIFY_ERROR);
    } catch (Throwable $exception) {
        debugging('Error in course audit: ' . $exception->getMessage(), DEBUG_DEVELOPER);
        redirect($url, get_string('startreviewerror', 'local_adaptive_course_audit'), 0, \core\output\notification::NOTIFY_ERROR);
    }
}

$intro = '';
$introimage = '';
$loop1summary = '';

try {
    $intro = get_string('reviewcourseintro', 'local_adaptive_course_audit', $coursename);
} catch (Throwable $exception) {
    debugging('Error in course audit: ' . $exception->getMessage(), DEBUG_DEVELOPER);
    $intro = get_string('reviewcourseerror', 'local_adaptive_course_audit');
}

try {
    $loop1summary = get_string('loop1summary', 'local_adaptive_course_audit');
} catch (Throwable $exception) {
    debugging('Error in course audit: ' . $exception->getMessage(), DEBUG_DEVELOPER);
}

try {
    $introimageurl = new moodle_url('/local/adaptive_course_audit/pix/02_Intro_Katze_transparent.gif');
    $introimage = html_writer::empty_tag('img', [
        'src' => $introimageurl->out(false),
        'alt' => get_string('reviewcoursenode', 'local_adaptive_course_audit'),
        'class' => 'local-adaptive-course-audit-hero-img',
        'loading' => 'lazy',
    ]);
} catch (Throwable $exception) {
    debugging('Error loading adaptive course audit intro image: ' . $exception->getMessage(), DEBUG_DEVELOPER);
}

$startreviewdescription = get_string('reviewcoursedescription', 'local_adaptive_course_audit');
$startreviewhelp = get_string('startreviewhelp', 'local_adaptive_course_audit');

$tableheaders = [
    get_string('reviewcoltitle', 'local_adaptive_course_audit'),
    get_string('reviewcoldescription', 'local_adaptive_course_audit'),
    get_string('reviewcolaction', 'local_adaptive_course_audit'),
];

$rows = [];

$hasmanagecap = has_capability('moodle/course:manageactivities', $context);
$starturl = new moodle_url('/local/adaptive_course_audit/review.php', [
    'courseid' => $course->id,
    'action' => 'startreview',
    'sesskey' => sesskey(),
]);

$actioncell = '';
if ($hasmanagecap) {
    $iconhtml = $OUTPUT->pix_icon('i/refresh', get_string('startreview', 'local_adaptive_course_audit'));
    $actioncell = html_writer::link(
        $starturl,
        $iconhtml,
        [
            'class' => 'btn btn-primary local-adaptive-course-audit-start-button',
            'title' => get_string('startreview', 'local_adaptive_course_audit'),
        ]
    );
} else {
    $actioncell = $OUTPUT->notification(
        get_string('startreviewpermission', 'local_adaptive_course_audit'),
        \core\output\notification::NOTIFY_INFO
    );
}

$rows[] = html_writer::tag(
    'tr',
    html_writer::tag('td', get_string('reviewtypeadaptive', 'local_adaptive_course_audit')) .
        html_writer::tag('td', s($startreviewdescription)) .
        html_writer::tag('td', $actioncell, ['class' => 'local-adaptive-course-audit-actions'])
);

$sectionrows = [];
$sectioninfoall = [];
try {
    $sectioninfoall = get_fast_modinfo($course->id)->get_section_info_all();
} catch (Throwable $exception) {
    debugging('Error loading course sections for adaptive audit: ' . $exception->getMessage(), DEBUG_DEVELOPER);
}

if (!empty($sectioninfoall)) {
    foreach ($sectioninfoall as $sectioninfo) {
        if ((property_exists($sectioninfo, 'visible') && !$sectioninfo->visible) ||
            (property_exists($sectioninfo, 'uservisible') && !$sectioninfo->uservisible)
        ) {
            continue;
        }

        $sectionname = '';
        try {
            $sectionname = get_section_name($course, $sectioninfo);
        } catch (Throwable $exception) {
            debugging('Error resolving section name for adaptive audit: ' . $exception->getMessage(), DEBUG_DEVELOPER);
            $sectionname = get_string('sectionname', 'moodle');
        }

        $sectiondescription = get_string('reviewsectiondescription', 'local_adaptive_course_audit', $sectionname);
        $sectionstarturl = new moodle_url('/local/adaptive_course_audit/review.php', [
            'courseid' => $course->id,
            'action' => 'startreview',
            'sectionid' => $sectioninfo->id,
            'sesskey' => sesskey(),
        ]);

        if ($hasmanagecap) {
            $iconhtml = $OUTPUT->pix_icon('i/refresh', get_string('startsectionreview', 'local_adaptive_course_audit'));
            $actioncell = html_writer::link(
                $sectionstarturl,
                $iconhtml,
                [
                    'class' => 'btn btn-primary local-adaptive-course-audit-start-button',
                    'title' => get_string('startsectionreview', 'local_adaptive_course_audit'),
                ]
            );
        } else {
            $actioncell = $OUTPUT->notification(
                get_string('startreviewpermission', 'local_adaptive_course_audit'),
                \core\output\notification::NOTIFY_INFO
            );
        }

        $sectionrows[] = html_writer::tag(
            'tr',
            html_writer::tag('td', ' - ' . get_string('reviewtypesection', 'local_adaptive_course_audit', $sectionname)) .
                html_writer::tag('td', s($sectiondescription)) .
                html_writer::tag('td', $actioncell, ['class' => 'local-adaptive-course-audit-actions'])
        );
    }
}

if (!empty($sectionrows)) {
    $rows = array_merge($rows, $sectionrows);
}

$table = html_writer::tag(
    'table',
    html_writer::tag(
        'thead',
        html_writer::tag(
            'tr',
            implode('', array_map(function ($header) {
                return html_writer::tag('th', $header);
            }, $tableheaders))
        )
    ) .
        html_writer::tag('tbody', implode('', $rows)),
    ['class' => 'generaltable local-adaptive-course-audit-table']
);

echo $OUTPUT->header();
echo $OUTPUT->heading($pageheading);
echo html_writer::div(
    html_writer::div(s($intro), 'local-adaptive-course-audit-hero-text') .
        ($introimage !== '' ? html_writer::div($introimage, 'local-adaptive-course-audit-hero-image') : ''),
    'local-adaptive-course-audit-hero'
);
echo html_writer::div(s($startreviewhelp), 'local-adaptive-course-audit-help');
if (!empty($loop1summary)) {
    echo html_writer::div(s($loop1summary), 'local-adaptive-course-audit-loop-summary');
}
echo html_writer::div($table, 'local-adaptive-course-audit-table-wrapper');
echo $OUTPUT->footer();
