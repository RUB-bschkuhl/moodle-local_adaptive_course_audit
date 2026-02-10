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
$string['reviewcoursematerialsintro'] = 'The hints in the guided tour are based on these materials (from MIau.nrw):';
$string['reviewcoursematerial_leitfaden'] = 'Leitfaden adaptive Lehre (PDF)';
$string['reviewcoursematerial_miau'] = 'Adaptive Kursgestaltung in Moodle (PDF)';
$string['startreviewerror'] = 'Unable to start the adaptive review right now. Please try again later.';
$string['startreviewpermission'] = 'You do not have permission to start a review in this course.';
$string['loop_quiz_unlock_followups_summary'] = 'We scan your course for adaptive learning patterns—checkpoints, feedback loops, and conditional progression—and suggest practical improvements.';
$string['tourname'] = 'Course tips for {$a}';
$string['tourdescription'] = 'A quick walkthrough with suggestions for {$a}.';
$string['tourplaceholdertitle'] = 'Let\'s get started';
$string['tourplaceholdercontent'] = '<p>We\'ll highlight a few spots in your course and give you practical tips along the way.</p><p><strong>How to use this:</strong> apply the suggestion, then continue the tour.</p>';
$string['reviewtableheading'] = 'What would you like to review?';
$string['reviewcoltitle'] = 'Scope';
$string['reviewcoldescription'] = 'What we check';
$string['reviewcolaction'] = '';
$string['teachquiz_row_title'] = 'Quiz: {$a}';
$string['teachquiz_row_description'] = 'Show me how to make this quiz more adaptive (behaviour, feedback, practice loops).';
$string['teachquiz_behaviour_button'] = 'Adaptive behaviour';
$string['teachquiz_feedback_button'] = 'Feedback + attempts';
$string['teachquiz_reviewoptions_button'] = 'Review options';
$string['teachquiz_grading_button'] = 'Grading';
$string['teachquiz_timingsecurity_button'] = 'Timing + security';
$string['touraction_add_quiz'] = 'Add a quiz here';
$string['touraction_edit_quiz_settings'] = 'Edit "{$a}" settings';
$string['startteacherror'] = 'Unable to start the teaching tour right now. Please try again later.';
$string['actiontourname'] = 'Guided help: {$a->action}';
$string['actiontourdescription'] = 'Short steps to finish this action in {$a->course}.';
$string['actiontour_loop_quiz_unlock_followups_addquiz_step_name_title'] = 'Name the quiz';
$string['actiontour_loop_quiz_unlock_followups_addquiz_step_name_body'] = '<p>Give the quiz a clear name so it stands out in this section.</p><ul><li>Use a verb (“Check”, “Practice”, “Self-test”).</li><li>Keep it short so it fits in the section view.</li></ul>';
$string['actiontour_loop_quiz_unlock_followups_addquiz_step_completion_title'] = 'Enable completion';
$string['actiontour_loop_quiz_unlock_followups_addquiz_step_completion_body'] = '<p>Turn on activity completion so follow-up items can depend on this quiz.</p><p><strong>Goal:</strong> Moodle can reliably unlock the next steps.</p>';
$string['actiontour_loop_quiz_unlock_followups_addquiz_step_access_title'] = 'Add a prerequisite';
$string['actiontour_loop_quiz_unlock_followups_addquiz_step_access_body'] = '<p>Use <strong>Restrict access</strong> to gate the quiz behind the prep activity.</p><p>This prevents learners from skipping straight to the quiz.</p>';
$string['actiontour_loop_quiz_unlock_followups_editquiz_step_access_title'] = 'Gate the quiz';
$string['actiontour_loop_quiz_unlock_followups_editquiz_step_access_body'] = '<p>Add a completion condition under <strong>Restrict access</strong> so learners finish the prep first.</p><p><strong>Tip:</strong> prefer completion-based conditions over date-based ones.</p>';
$string['actiontour_loop_quiz_unlock_followups_editquiz_step_completion_title'] = 'Check completion';
$string['actiontour_loop_quiz_unlock_followups_editquiz_step_completion_body'] = '<p>Ensure quiz completion is tracked automatically so follow-ups can unlock.</p><p><strong>Typical setup:</strong> mark complete when the quiz is submitted / a passing grade is achieved.</p>';
$string['reviewtypeadaptive'] = 'Whole course';
$string['reviewtypesection'] = 'Section "{$a}"';
$string['reviewsectiondescription'] = 'Focus on "{$a}" only.';
$string['reviewcourseerror'] = 'Something went wrong loading the preview. Please try again.';
$string['startsectionreview'] = 'Review this section';
$string['privacy:metadata'] = 'This plugin does not store any personal data.';
$string['settings:description'] = 'More options will appear here as the plugin grows.';
$string['adaptive_course_audit:view'] = 'View the course review page';

// Tasks.
$string['task_cleanup_tours'] = 'Clean up stale adaptive course audit tours';

// Quiz unlock follow-ups rule strings.
$string['rule_loop_quiz_unlock_followups_name'] = 'Learn, then prove it';
$string['rule_loop_quiz_unlock_followups_headline_min_items'] = 'Add more activities';
$string['rule_loop_quiz_unlock_followups_headline_missing_quiz'] = 'No quiz yet';
$string['rule_loop_quiz_unlock_followups_headline_missing_kb'] = 'Start with content';
$string['rule_loop_quiz_unlock_followups_headline_quiz_no_precondition'] = 'Quiz needs a gate';
$string['rule_loop_quiz_unlock_followups_headline_no_followups'] = 'Nothing unlocked by quiz';
$string['rule_loop_quiz_unlock_followups_headline_success'] = 'Looking good!';
$string['rule_loop_quiz_unlock_followups_description'] = 'Content first, then a quiz that unlocks what comes next.';
$string['rule_loop_quiz_unlock_followups_min_items'] = 'This section needs at least {$a} activities to form a proper sequence.';
$string['rule_loop_quiz_unlock_followups_missing_quiz'] = 'Add a quiz so learners can show what they\'ve learned.';
$string['rule_loop_quiz_unlock_followups_missing_kb'] = 'Put some content before the quiz (a page, book, or link works well).';
$string['rule_loop_quiz_unlock_followups_quiz_no_precondition'] = 'Make the quiz depend on earlier work so learners don\'t skip ahead.';
$string['rule_loop_quiz_unlock_followups_no_followups'] = 'Nothing is gated by this quiz yet. Link follow-up activities to quiz completion.';
$string['rule_loop_quiz_unlock_followups_additional_followups'] = 'You could add another activity that depends on "{$a->activity}".';
$string['rule_loop_quiz_unlock_followups_followup_list'] = '{$a->count} activities unlock after the quiz: {$a->items}.';
$string['rule_loop_quiz_unlock_followups_success'] = 'Nice! Content leads into a quiz that unlocks the next steps.';
$string['rule_loop_quiz_unlock_followups_rationale'] = '<h5>Why this matters</h5><p>A minimal adaptive learning path often follows a simple pattern:</p><ul><li>learning content</li><li>competence check (quiz)</li><li>targeted next steps</li></ul><p>Gating follow-up activities and using completion tracking helps learners prove what they know before moving on (vgl. Leitfaden adaptive Lehre, 2025; MIau.nrw).</p>';

// Grade branching rule strings.
$string['rule_loop_branch_by_grade_name'] = 'Branch by quiz score';
$string['rule_loop_branch_by_grade_description'] = 'Use quiz grade thresholds to unlock different follow-up paths (remedial vs advanced).';
$string['rule_loop_branch_by_grade_headline_missing_gradeitem'] = 'Grade-based branching not available';
$string['rule_loop_branch_by_grade_missing_gradeitem'] = 'We could not find a grade item for the quiz "{$a}". Ensure the quiz has a maximum grade and is included in the gradebook.';
$string['rule_loop_branch_by_grade_headline_missing'] = 'No grade-based branches yet';
$string['rule_loop_branch_by_grade_missing'] = 'Add Restrict access → Grade conditions to follow-up activities so learners see different next steps based on their score in "{$a}".';
$string['rule_loop_branch_by_grade_headline_success'] = 'Grade-based branching found';
$string['rule_loop_branch_by_grade_found'] = 'Follow-ups are gated by "{$a->quiz}" score: {$a->branches}.';
$string['rule_loop_branch_by_grade_suggest_two_paths'] = 'Consider defining both a “remedial” path (max score) and an “advanced” path (min score) for "{$a}".';
$string['rule_loop_branch_by_grade_rationale'] = '<h5>Why this matters</h5><p>Branching paths by quiz score is a practical way to react to heterogeneous prior knowledge.</p><p>Learners can be guided to remediation or extension activities via prerequisites based on performance (vgl. Leitfaden adaptive Lehre, 2025; MIau.nrw).</p>';
$string['rule_loop_branch_by_grade_range_any'] = 'any score';
$string['rule_loop_branch_by_grade_range_min'] = '≥ {$a}%';
$string['rule_loop_branch_by_grade_range_max'] = '< {$a}%';
$string['rule_loop_branch_by_grade_range_between'] = '{$a->min}%–{$a->max}%';
$string['touraction_add_grade_gate'] = 'Add a grade gate to "{$a}"';
$string['actiontour_gradegate_step_access_title'] = 'Add a grade condition';
$string['actiontour_gradegate_step_access_body'] = '<p>Open <strong>Restrict access</strong> and add a <strong>Grade</strong> condition (min/max) tied to the quiz score.</p><p><strong>Result:</strong> learners see different follow-ups based on their score.</p>';

// Quiz feedback quality rule strings.
$string['rule_loop_quiz_feedback_name'] = 'Handlungsleitendes Quiz-Feedback';
$string['rule_loop_quiz_feedback_description'] = 'Check whether the quiz gives actionable overall feedback and supports practice loops (multiple attempts).';
$string['rule_loop_quiz_feedback_headline_success'] = 'Feedback supports adaptive next steps';
$string['rule_loop_quiz_feedback_headline_needs_work'] = 'Feedback can be more adaptive';
$string['rule_loop_quiz_feedback_missing'] = 'Add Overall feedback to "{$a}" so learners get guidance after submitting.';
$string['rule_loop_quiz_feedback_found'] = 'Overall feedback exists for "{$a}".';
$string['rule_loop_quiz_feedback_missing_links'] = 'Make the feedback actionable: include links to specific remedial/advanced resources for "{$a}".';
$string['rule_loop_quiz_feedback_suggest_attempts'] = 'Consider allowing more than one attempt for "{$a}" to support practice loops.';
$string['rule_loop_quiz_feedback_rationale'] = '<h5>Why this matters</h5><p>Feedback in tests is a low-threshold entry into adaptive teaching.</p><p><strong>Actionable feedback</strong> (ideally with links) and the option to retry can help learners self-steer and close gaps before progressing (vgl. Leitfaden adaptive Lehre, 2025; MIau.nrw).</p>';
$string['touraction_edit_quiz_feedback'] = 'Improve feedback in "{$a}"';
$string['actiontour_quizfeedback_step_overallfeedback_title'] = 'Add overall feedback';
$string['actiontour_quizfeedback_step_overallfeedback_body'] = '<p>Use <strong>Overall feedback</strong> bands to give different guidance based on score.</p><p><strong>Make it actionable:</strong> include links to the next activity (remedial vs advanced).</p>';
$string['actiontour_quizfeedback_step_attempts_title'] = 'Enable practice loops';
$string['actiontour_quizfeedback_step_attempts_body'] = '<p>Set <strong>Attempts allowed</strong> to more than one if you want learners to retry after following a remedial path.</p><p>This turns the quiz into a practice loop with feedback.</p>';

// Quiz review options (what learners see, when).
$string['actiontour_quizreviewoptions_step_reviewoptions_title'] = 'Tune the review options';
$string['actiontour_quizreviewoptions_step_reviewoptions_body'] = '<p>Open <strong>Review options</strong> and decide what learners can see <em>during</em>, <em>immediately after</em>, and <em>later</em>.</p><p><strong>Tip:</strong> show the right amount of information at the right time (e.g. overall feedback, which answers were correct, and general feedback).</p>';

// Quiz grading (how attempts are graded / course grade impact).
$string['actiontour_quizgrading_step_grade_title'] = 'Check the grading setup';
$string['actiontour_quizgrading_step_grade_body'] = '<p>Under <strong>Grade</strong>, choose how multiple attempts should be graded (highest/average/first/last) and ensure the maximum grade makes sense.</p><p><strong>Outcome:</strong> the quiz score fits your intended learning path and branching conditions.</p>';

// Quiz timing and security (access rules).
$string['actiontour_quiztimingsecurity_step_timing_title'] = 'Set timing and availability';
$string['actiontour_quiztimingsecurity_step_timing_body'] = '<p>Use <strong>Timing</strong> to control availability (open/close) and, if needed, add a time limit.</p><p><strong>Tip:</strong> align timing with your course rhythm so learners can plan.</p>';
$string['actiontour_quiztimingsecurity_step_security_title'] = 'Review access restrictions';
$string['actiontour_quiztimingsecurity_step_security_body'] = '<p>In <strong>Security</strong>, decide whether additional restrictions are needed (e.g. password, subnet, browser security).</p><p><strong>Tip:</strong> keep it simple unless you have a clear exam scenario.</p>';

// Diagnostic checkpoint rule strings.
$string['rule_loop_diagnostic_checkpoint_name'] = 'Diagnostic checkpoint';
$string['rule_loop_diagnostic_checkpoint_description'] = 'Use a short diagnostic (Choice/Feedback/Survey) plus signposting to support learner self-steering.';
$string['rule_loop_diagnostic_checkpoint_rationale'] = '<h5>Why this matters</h5><p>Short diagnostics (needs/self-assessment) provide a feedback signal for learners and teachers.</p><p>With clear signposting and optional gating, they support self-steering and help learners choose an appropriate path (vgl. Leitfaden adaptive Lehre, 2025; MIau.nrw).</p>';
$string['rule_loop_diagnostic_checkpoint_headline_missing'] = 'No diagnostic checkpoint';
$string['rule_loop_diagnostic_checkpoint_missing'] = 'Add a short diagnostic activity (e.g. Feedback) to capture needs or self-assessment at the start of this section.';
$string['rule_loop_diagnostic_checkpoint_found'] = 'Diagnostic checkpoint found: "{$a}".';
$string['rule_loop_diagnostic_checkpoint_missing_signposting'] = 'Add signposting right after the checkpoint (e.g. a Label or Page) explaining which path to follow next.';
$string['rule_loop_diagnostic_checkpoint_suggest_gate'] = 'Optionally gate follow-up activities behind completion of "{$a}" so learners don’t skip the checkpoint.';
$string['rule_loop_diagnostic_checkpoint_gated_followups'] = '{$a} activities are gated behind completing the checkpoint.';
$string['rule_loop_diagnostic_checkpoint_headline_success'] = 'Checkpoint + guidance in place';
$string['rule_loop_diagnostic_checkpoint_headline_needs_work'] = 'Checkpoint needs clearer guidance';
$string['touraction_add_diagnostic'] = 'Add a diagnostic activity';
$string['touraction_edit_diagnostic'] = 'Edit "{$a}" settings';
$string['actiontour_diagnostic_step_name_title'] = 'Name the checkpoint';
$string['actiontour_diagnostic_step_name_body'] = 'Use a clear name like “Quick self-check” or “Needs survey” so learners understand the purpose.';
$string['actiontour_diagnostic_step_access_title'] = 'Optionally gate follow-ups';
$string['actiontour_diagnostic_step_access_body'] = '<p>If you want, use <strong>Restrict access</strong> (completion) so learners complete the checkpoint before moving on.</p><p><strong>Tip:</strong> keep it optional unless skipping the checkpoint would break the learning path.</p>';

// Quiz behaviour (adaptive/interactive) rule strings.
$string['rule_loop_quiz_adaptive_behaviour_name'] = 'Adaptive quiz behaviour';
$string['rule_loop_quiz_adaptive_behaviour_description'] = 'Check whether quizzes use adaptive/interactive question behaviours (multiple tries with immediate feedback).';
$string['rule_loop_quiz_adaptive_behaviour_headline_success'] = 'Adaptive quiz behaviour in use';
$string['rule_loop_quiz_adaptive_behaviour_headline_needs_work'] = 'Quiz behaviour could be more adaptive';
$string['rule_loop_quiz_adaptive_behaviour_rationale'] = '<h5>Why this matters</h5><p>Adaptive teaching benefits from continuous feedback loops and short competence checks.</p><p>Adaptive/interactive question behaviours can turn a quiz into a practice loop with immediate feedback, supporting learners to self-correct before moving on (vgl. Leitfaden adaptive Lehre, 2025; MIau.nrw).</p>';
$string['rule_loop_quiz_adaptive_behaviour_found_quiz'] = 'Quiz "{$a->quiz}" uses behaviour: {$a->behaviour}.';
$string['rule_loop_quiz_adaptive_behaviour_behaviour_unknown'] = 'unknown';
$string['rule_loop_quiz_adaptive_behaviour_missing'] = 'Consider switching "{$a}" to an adaptive/interactive behaviour so learners can retry with immediate feedback.';
$string['rule_loop_quiz_adaptive_behaviour_success'] = 'Great: at least one quiz supports practice loops here ({$a}).';
$string['touraction_edit_quiz_behaviour'] = 'Adjust behaviour in "{$a}"';
$string['actiontour_quizbehaviour_step_behaviour_title'] = 'Enable adaptive behaviour';
$string['actiontour_quizbehaviour_step_behaviour_body'] = '<p>Change <strong>How questions behave</strong> to an adaptive/interactive mode (e.g., Adaptive mode or Interactive with multiple tries).</p><p><strong>Outcome:</strong> learners can try again and get immediate feedback.</p>';

// Lesson branching rule strings.
$string['rule_loop_lesson_branching_name'] = 'Lesson branching';
$string['rule_loop_lesson_branching_description'] = 'Check whether lessons branch to different pages based on answers (conditional jumps).';
$string['rule_loop_lesson_branching_headline_success'] = 'Lesson branching found';
$string['rule_loop_lesson_branching_headline_needs_work'] = 'Lesson could branch more adaptively';
$string['rule_loop_lesson_branching_rationale'] = '<h5>Why this matters</h5><p>Branching learning paths allow different next steps depending on learners’ answers (e.g., remediation vs. extension).</p><p>Moodle Lessons support complex learning paths with conditional navigation (vgl. Leitfaden adaptive Lehre, 2025; MIau.nrw).</p>';
$string['rule_loop_lesson_branching_found'] = 'Lesson "{$a}" contains branching jumps between pages.';
$string['rule_loop_lesson_branching_missing'] = 'Lesson "{$a}" appears mostly linear. Consider adding conditional jumps to guide learners to different follow-up pages based on answers.';
$string['rule_loop_lesson_branching_lesson_no_answers'] = 'Lesson "{$a}" has no answer records yet (it may still be empty).';
$string['touraction_open_lesson_editor'] = 'Open lesson editor for "{$a}"';

// Quiz random questions rule strings.
$string['rule_loop_quiz_random_questions_name'] = 'Random questions (question pools)';
$string['rule_loop_quiz_random_questions_description'] = 'Check whether quizzes use random question slots (question pools) to vary attempts.';
$string['rule_loop_quiz_random_questions_headline_success'] = 'Random question slots found';
$string['rule_loop_quiz_random_questions_headline_needs_work'] = 'No random question slots yet';
$string['rule_loop_quiz_random_questions_rationale'] = '<h5>Why this matters</h5><p>Random question pools can make retakes meaningful and support practice loops.</p><p>They also enable differentiated testing designs (vgl. Leitfaden adaptive Lehre, 2025, “zufallsbasierte Aufgabenpools”).</p>';
$string['rule_loop_quiz_random_questions_found'] = 'Quiz "{$a->quiz}" contains {$a->count} random question slot(s).';
$string['rule_loop_quiz_random_questions_missing'] = 'Quiz "{$a}" does not use random question slots yet. Consider adding random questions from a categorised question bank.';
$string['rule_loop_quiz_random_questions_empty'] = 'Quiz "{$a}" has no slots/questions yet (it may still be empty).';
$string['touraction_open_quiz_edit'] = 'Edit questions in "{$a}"';

// H5P presence rule strings.
$string['rule_loop_h5p_interactive_name'] = 'Interactive H5P (presence)';
$string['rule_loop_h5p_interactive_description'] = 'Check whether an H5P activity exists in the section (presence check only).';
$string['rule_loop_h5p_interactive_headline_success'] = 'H5P activity found';
$string['rule_loop_h5p_interactive_headline_needs_work'] = 'No H5P activity yet';
$string['rule_loop_h5p_interactive_rationale'] = '<h5>Why this matters</h5><p>H5P can support decision points and branching learning paths (e.g. Branching Scenario).</p><p>This helps learners navigate content in a way that fits their needs (vgl. Leitfaden adaptive Lehre, 2025; MIau.nrw).</p>';
$string['rule_loop_h5p_interactive_found'] = 'H5P activity(ies) found in this section: {$a}.';
$string['rule_loop_h5p_interactive_missing'] = 'No H5P activity found in this section. If you want a visual branching scenario, H5P can be a lightweight entry point.';
$string['touraction_add_h5p'] = 'Add an H5P activity';
$string['actiontour_h5p_step_name_title'] = 'Name the H5P activity';
$string['actiontour_h5p_step_name_body'] = '<p>Choose a clear name so learners know what to expect.</p><ul><li>Example: <strong>Branching scenario</strong></li><li>Example: <strong>Choose your path</strong></li></ul>';
