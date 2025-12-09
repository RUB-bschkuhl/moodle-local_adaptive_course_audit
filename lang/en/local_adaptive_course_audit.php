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
$string['reviewcourseintro'] = 'Ready to improve "{$a}"? Hit the button below and we\'ll walk you through what\'s working and what could be better.';
$string['reviewcoursedescription'] = 'Analyses your course and creates a guided tour with suggestions.';
$string['startreview'] = 'Start review';
$string['startreviewhelp'] = 'Pick a row and start the review to see tips directly on your course page.';
$string['startreviewerror'] = 'Unable to start the adaptive review right now. Please try again later.';
$string['startreviewpermission'] = 'You do not have permission to start a review in this course.';
$string['loop1summary'] = 'We look for a learning path where content leads into a quiz that unlocks the next steps – helping learners prove what they know before moving on.';
$string['tourname'] = 'Course tips for {$a}';
$string['tourdescription'] = 'A quick walkthrough with suggestions for {$a}.';
$string['tourplaceholdertitle'] = 'Let\'s get started';
$string['tourplaceholdercontent'] = 'We\'ll highlight a few spots in your course and give you practical tips along the way.';
$string['reviewtableheading'] = 'What would you like to review?';
$string['reviewcoltitle'] = 'Scope';
$string['reviewcoldescription'] = 'What we check';
$string['reviewcolaction'] = '';
$string['touraction_add_quiz'] = 'Add a quiz here';
$string['touraction_edit_quiz_settings'] = 'Edit "{$a}" settings';
$string['reviewtypeadaptive'] = 'Whole course';
$string['reviewtypesection'] = 'Section "{$a}"';
$string['reviewsectiondescription'] = 'Focus on "{$a}" only.';
$string['reviewcourseerror'] = 'Something went wrong loading the preview. Please try again.';
$string['startsectionreview'] = 'Review this section';
$string['privacy:metadata'] = 'This plugin does not store any personal data.';
$string['settings:description'] = 'More options will appear here as the plugin grows.';
$string['adaptive_course_audit:view'] = 'View the course review page';

// Loop 1 rule strings.
$string['rule_loop_1_name'] = 'Learn, then prove it';
$string['rule_loop_1_headline_min_items'] = 'Add more activities';
$string['rule_loop_1_headline_missing_quiz'] = 'No quiz yet';
$string['rule_loop_1_headline_missing_kb'] = 'Start with content';
$string['rule_loop_1_headline_quiz_no_precondition'] = 'Quiz needs a gate';
$string['rule_loop_1_headline_no_followups'] = 'Nothing unlocked by quiz';
$string['rule_loop_1_headline_success'] = 'Looking good!';
$string['rule_loop_1_description'] = 'Content first, then a quiz that unlocks what comes next.';
$string['rule_loop_1_min_items'] = 'This section needs at least {$a} activities to form a proper sequence.';
$string['rule_loop_1_missing_quiz'] = 'Add a quiz so learners can show what they\'ve learned.';
$string['rule_loop_1_missing_kb'] = 'Put some content before the quiz (a page, book, or link works well).';
$string['rule_loop_1_quiz_no_precondition'] = 'Make the quiz depend on earlier work so learners don\'t skip ahead.';
$string['rule_loop_1_no_followups'] = 'Nothing is gated by this quiz yet. Link follow-up activities to quiz completion.';
$string['rule_loop_1_additional_followups'] = 'You could add another activity that depends on "{$a->activity}".';
$string['rule_loop_1_followup_list'] = '{$a->count} activities unlock after the quiz: {$a->items}.';
$string['rule_loop_1_success'] = 'Nice! Content leads into a quiz that unlocks the next steps.';

