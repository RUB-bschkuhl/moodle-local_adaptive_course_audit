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

/**
 * Language strings for the Adaptive course audit local plugin.
 *
 * @package     local_adaptive_course_audit
 * @category    string
 * @copyright   2025 Moodle HQ
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Adaptive course audit';
$string['reviewcoursenode'] = 'Review course';
$string['reviewcourseheading'] = 'Adaptive course audit';
$string['reviewcourseintro'] = 'An introductory narrative will appear here soon. Use the button below to start the adaptive review for {$a}.';
$string['reviewcoursedescription'] = 'Starting a review will analyse this course, create a guided user tour, and return you to the course page once the checkpoints are ready.';
$string['startreview'] = 'Start review';
$string['startreviewhelp'] = 'Run the adaptive review workflow and build a guided tour for this course.';
$string['startreviewsuccess'] = 'Review started successfully. Watch for the guided tour on the course page.';
$string['startreviewerror'] = 'Unable to start the adaptive review right now. Please try again later.';
$string['startreviewpermission'] = 'You do not have permission to start a review in this course.';
$string['tourname'] = 'Adaptive course audit review ({$a})';
$string['tourdescription'] = 'Guided review checkpoints for {$a}.';
$string['tourplaceholdertitle'] = 'Adaptive course audit overview';
$string['tourplaceholdercontent'] = 'Runs adaptive checks on this course or a section and builds a guided tour with improvement hints.';
$string['reviewtableheading'] = 'Course review options';
$string['reviewcoltitle'] = 'Title';
$string['reviewcoldescription'] = 'Description';
$string['reviewcolaction'] = 'Action';
$string['touraction_add_quiz'] = 'Add a quiz to this section';
$string['touraction_edit_quiz_settings'] = 'Open quiz settings for "{$a}"';
$string['reviewtypeadaptive'] = 'Adaptive course audit';
$string['reviewtypesection'] = 'Adaptive audit for section "{$a}"';
$string['reviewsectiondescription'] = 'Run the adaptive audit for the "{$a}" section only.';
$string['reviewcourseerror'] = 'Unable to load the adaptive course audit preview at the moment.';
$string['startsectionreview'] = 'Start section review';
$string['privacy:metadata'] = 'The Adaptive course audit plugin does not store any personal data.';
$string['settings:description'] = 'Adaptive insights tailored to every course will appear here as the plugin evolves.';
$string['adaptive_course_audit:view'] = 'Access the Adaptive course audit course navigation entry';

// Loop 1 rule strings.
$string['rule_loop_1_name'] = 'Loop 1: knowledge build-up then quiz';
$string['rule_loop_1_description'] = 'Checks for a sequence with knowledge-building activities leading into a quiz that gates follow-up activities.';
$string['rule_loop_1_min_items'] = 'Add at least {$a} visible activities to structure this sequence.';
$string['rule_loop_1_missing_quiz'] = 'Add a quiz so learners can demonstrate knowledge before progressing.';
$string['rule_loop_1_missing_kb'] = 'Add at least one knowledge-building activity before the quiz (e.g., page, book, URL).';
$string['rule_loop_1_quiz_no_precondition'] = 'Set availability conditions on the quiz so it depends on prior work.';
$string['rule_loop_1_no_followups'] = 'No activity depends on the quiz. Add completion-based availability to follow-up items.';
$string['rule_loop_1_additional_followups'] = 'Consider adding another follow-up that depends on "{$a->activity}" to reinforce the outcome.';
$string['rule_loop_1_followup_list'] = '{$a->count} activities depend on the quiz: {$a->items}.';
$string['rule_loop_1_success'] = 'Loop 1 pattern detected: knowledge build-up, gated quiz, and dependent follow-ups.';

