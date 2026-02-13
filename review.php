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
$cmid = optional_param('cmid', 0, PARAM_INT);
$teach = optional_param('teach', '', PARAM_ALPHANUMEXT);
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

if ($action === 'startteach') {
    require_sesskey();

    try {
        $teachresult = review_service::start_teach_tour((int)$course->id, (int)$cmid, (string)$teach);
        if (!empty($teachresult['status']) && !empty($teachresult['redirect']) && $teachresult['redirect'] instanceof moodle_url) {
            redirect($teachresult['redirect']);
        }

        $failuremessage = !empty($teachresult['message'])
            ? (string)$teachresult['message']
            : get_string('startteacherror', 'local_adaptive_course_audit');

        redirect($url, $failuremessage, 0, \core\output\notification::NOTIFY_ERROR);
    } catch (moodle_exception $exception) {
        debugging('Error in adaptive teaching tour: ' . $exception->getMessage(), DEBUG_DEVELOPER);
        redirect($url, $exception->getMessage(), 0, \core\output\notification::NOTIFY_ERROR);
    } catch (Throwable $exception) {
        debugging('Error in adaptive teaching tour: ' . $exception->getMessage(), DEBUG_DEVELOPER);
        redirect($url, get_string('startteacherror', 'local_adaptive_course_audit'), 0, \core\output\notification::NOTIFY_ERROR);
    }
}

if ($action === 'startscenario') {
    require_sesskey();
    $scenario = required_param('scenario', PARAM_INT);

    try {
        $scenarioresult = review_service::start_scenario_tour((int)$course->id, $scenario);
        if (!empty($scenarioresult['status'])) {
            redirect(
                new moodle_url('/course/view.php', ['id' => $course->id])
            );
        }

        $failuremessage = !empty($scenarioresult['message'])
            ? (string)$scenarioresult['message']
            : get_string('startscenarioerror', 'local_adaptive_course_audit');

        redirect($url, $failuremessage, 0, \core\output\notification::NOTIFY_ERROR);
    } catch (moodle_exception $exception) {
        debugging('Error in scenario tour: ' . $exception->getMessage(), DEBUG_DEVELOPER);
        redirect($url, $exception->getMessage(), 0, \core\output\notification::NOTIFY_ERROR);
    } catch (Throwable $exception) {
        debugging('Error in scenario tour: ' . $exception->getMessage(), DEBUG_DEVELOPER);
        redirect($url, get_string('startscenarioerror', 'local_adaptive_course_audit'), 0, \core\output\notification::NOTIFY_ERROR);
    }
}

$intro = '';
$introimage = '';
$loopsummary = '';

try {
    $intro = get_string('reviewcourseintro', 'local_adaptive_course_audit', $coursename);
} catch (Throwable $exception) {
    debugging('Error in course audit: ' . $exception->getMessage(), DEBUG_DEVELOPER);
    $intro = get_string('reviewcourseerror', 'local_adaptive_course_audit');
}

try {
    $loopsummary = get_string('loop_quiz_unlock_followups_summary', 'local_adaptive_course_audit');
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

$materialsblock = '';
try {
    $materialsintro = get_string('reviewcoursematerialsintro', 'local_adaptive_course_audit');

    $materialfiles = [
        [
            'filename' => 'Leitfaden_Adaptive_Lehre.pdf',
            'label' => get_string('reviewcoursematerial_leitfaden', 'local_adaptive_course_audit'),
        ],
        [
            'filename' => 'Adaptive_Kursgestaltung_in_Moodle.pdf',
            'label' => get_string('reviewcoursematerial_miau', 'local_adaptive_course_audit'),
        ],
    ];

    $materialitems = [];
    foreach ($materialfiles as $materialfile) {
        if (empty($materialfile['filename']) || empty($materialfile['label'])) {
            continue;
        }

        $diskpath = __DIR__ . '/files/' . $materialfile['filename'];
        if (!file_exists($diskpath)) {
            continue;
        }

        $fileurl = new moodle_url('/local/adaptive_course_audit/files/' . $materialfile['filename']);
        $materialitems[] = html_writer::tag(
            'li',
            html_writer::link(
                $fileurl,
                s($materialfile['label']),
                [
                    'target' => '_blank',
                    'rel' => 'noopener',
                ]
            )
        );
    }

    if (!empty($materialitems)) {
        $materialsblock = html_writer::div(
            html_writer::tag('div', s($materialsintro), ['class' => 'local-adaptive-course-audit-materials-intro']) .
                html_writer::tag('ul', implode('', $materialitems), ['class' => 'local-adaptive-course-audit-materials-list']),
            'local-adaptive-course-audit-materials'
        );
    }
} catch (Throwable $exception) {
    debugging('Error building adaptive course audit materials block: ' . $exception->getMessage(), DEBUG_DEVELOPER);
}

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
$sectionscm = [];
$modinfo = null;
try {
    $modinfo = get_fast_modinfo($course->id);
    $sectioninfoall = $modinfo->get_section_info_all();
    $sectionscm = $modinfo->get_sections();
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

        $sectiontitle = get_string('reviewtypesection', 'local_adaptive_course_audit', $sectionname);
        $sectiontitlecell = html_writer::div(
            html_writer::span('↳', 'local-adaptive-course-audit-tree-marker', ['aria-hidden' => 'true']) .
                html_writer::span(s($sectiontitle), 'local-adaptive-course-audit-tree-label'),
            'local-adaptive-course-audit-tree-item local-adaptive-course-audit-tree-item--depth-1'
        );

        $sectionrows[] = html_writer::tag(
            'tr',
            html_writer::tag('td', $sectiontitlecell, ['class' => 'local-adaptive-course-audit-tree-cell']) .
                html_writer::tag('td', s($sectiondescription)) .
                html_writer::tag('td', $actioncell, ['class' => 'local-adaptive-course-audit-actions'])
        );

        // Teaching rows: quizzes in this section (show how to enable adaptive features).
        $cmids = $sectionscm[$sectioninfo->section] ?? [];
        if (!empty($modinfo) && !empty($cmids)) {
            foreach ($cmids as $cmid) {
                try {
                    $cm = $modinfo->get_cm($cmid);
                } catch (Throwable $exception) {
                    debugging('Error resolving section module for adaptive audit review page: ' . $exception->getMessage(), DEBUG_DEVELOPER);
                    continue;
                }

                if (empty($cm) || (string)$cm->modname !== 'quiz') {
                    continue;
                }
                if (empty($cm->uservisible) || !empty($cm->deletioninprogress)) {
                    continue;
                }

                $quizname = (string)$cm->name;
                $moduletitle = get_string('teachquiz_row_title', 'local_adaptive_course_audit', $quizname);
                $titlecell = html_writer::div(
                    html_writer::span('↳', 'local-adaptive-course-audit-tree-marker', ['aria-hidden' => 'true']) .
                        html_writer::span(s($moduletitle), 'local-adaptive-course-audit-tree-label'),
                    'local-adaptive-course-audit-tree-item local-adaptive-course-audit-tree-item--depth-2'
                );
                $descriptioncell = get_string('teachquiz_row_description', 'local_adaptive_course_audit');

                if ($hasmanagecap) {
                    $teachbaseurl = new moodle_url('/local/adaptive_course_audit/review.php', [
                        'courseid' => $course->id,
                        'action' => 'startteach',
                        'cmid' => (int)$cm->id,
                        'sesskey' => sesskey(),
                    ]);

                    $behavioururl = new moodle_url($teachbaseurl, ['teach' => 'quizbehaviour']);
                    $feedbackurl = new moodle_url($teachbaseurl, ['teach' => 'quizfeedback']);
                    $reviewoptionsurl = new moodle_url($teachbaseurl, ['teach' => 'quizreviewoptions']);
                    $gradingurl = new moodle_url($teachbaseurl, ['teach' => 'quizgrading']);
                    $timingsecurityurl = new moodle_url($teachbaseurl, ['teach' => 'quiztimingsecurity']);

                    $actionbuttons = [];
                    $actionbuttons[] = html_writer::link(
                        $behavioururl,
                        s(get_string('teachquiz_behaviour_button', 'local_adaptive_course_audit')),
                        [
                            'class' => 'btn btn-secondary btn-sm local-adaptive-course-audit-teach-button',
                            'title' => get_string('teachquiz_behaviour_button', 'local_adaptive_course_audit'),
                        ]
                    );
                    $actionbuttons[] = html_writer::link(
                        $feedbackurl,
                        s(get_string('teachquiz_feedback_button', 'local_adaptive_course_audit')),
                        [
                            'class' => 'btn btn-secondary btn-sm local-adaptive-course-audit-teach-button',
                            'title' => get_string('teachquiz_feedback_button', 'local_adaptive_course_audit'),
                        ]
                    );
                    $actionbuttons[] = html_writer::link(
                        $reviewoptionsurl,
                        s(get_string('teachquiz_reviewoptions_button', 'local_adaptive_course_audit')),
                        [
                            'class' => 'btn btn-secondary btn-sm local-adaptive-course-audit-teach-button',
                            'title' => get_string('teachquiz_reviewoptions_button', 'local_adaptive_course_audit'),
                        ]
                    );
                    $actionbuttons[] = html_writer::link(
                        $gradingurl,
                        s(get_string('teachquiz_grading_button', 'local_adaptive_course_audit')),
                        [
                            'class' => 'btn btn-secondary btn-sm local-adaptive-course-audit-teach-button',
                            'title' => get_string('teachquiz_grading_button', 'local_adaptive_course_audit'),
                        ]
                    );
                    $actionbuttons[] = html_writer::link(
                        $timingsecurityurl,
                        s(get_string('teachquiz_timingsecurity_button', 'local_adaptive_course_audit')),
                        [
                            'class' => 'btn btn-secondary btn-sm local-adaptive-course-audit-teach-button',
                            'title' => get_string('teachquiz_timingsecurity_button', 'local_adaptive_course_audit'),
                        ]
                    );

                    $actioncell = html_writer::div(
                        implode('', $actionbuttons),
                        'local-adaptive-course-audit-teach-actions'
                    );
                } else {
                    $actioncell = $OUTPUT->notification(
                        get_string('startreviewpermission', 'local_adaptive_course_audit'),
                        \core\output\notification::NOTIFY_INFO
                    );
                }

                $sectionrows[] = html_writer::tag(
                    'tr',
                    html_writer::tag('td', $titlecell, [
                        'class' => 'local-adaptive-course-audit-module-title local-adaptive-course-audit-tree-cell',
                    ]) .
                        html_writer::tag('td', s($descriptioncell)) .
                        html_writer::tag('td', $actioncell, ['class' => 'local-adaptive-course-audit-actions']),
                    ['class' => 'local-adaptive-course-audit-module-row']
                );
            }
        }
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
if (!empty($materialsblock)) {
    echo $materialsblock;
}
if (!empty($loopsummary)) {
    echo html_writer::div(s($loopsummary), 'local-adaptive-course-audit-loop-summary');
}

// Scenario tour buttons.
if ($hasmanagecap) {
    $scenarioheading = get_string('scenario_heading', 'local_adaptive_course_audit');
    $scenariodescription = get_string('scenario_description', 'local_adaptive_course_audit');

    $scenariobuttons = [];
    for ($i = 1; $i <= 3; $i++) {
        $scenariourl = new moodle_url('/local/adaptive_course_audit/review.php', [
            'courseid' => $course->id,
            'action' => 'startscenario',
            'scenario' => $i,
            'sesskey' => sesskey(),
        ]);
        $scenariobuttons[] = html_writer::link(
            $scenariourl,
            s(get_string("scenario_{$i}_button", 'local_adaptive_course_audit')),
            [
                'class' => 'btn btn-outline-primary local-adaptive-course-audit-scenario-button',
                'title' => get_string("scenario_{$i}_title", 'local_adaptive_course_audit'),
            ]
        );
    }

    echo html_writer::div(
        html_writer::tag('h3', s($scenarioheading)) .
            html_writer::tag('p', s($scenariodescription)) .
            html_writer::div(implode('', $scenariobuttons), 'local-adaptive-course-audit-scenario-buttons'),
        'local-adaptive-course-audit-scenario-section'
    );
}

echo html_writer::div($table, 'local-adaptive-course-audit-table-wrapper');
echo $OUTPUT->footer();
