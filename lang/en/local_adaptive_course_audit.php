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
$string['reviewcoursenode_resume'] = 'Resume last audit';
$string['reviewcourseheading'] = 'Adaptive course audit';
$string['reviewcourseintro'] = 'Ready to improve "{$a}"? Hit the button below and we\'ll walk you through what\'s working and what could be better.<br/>';
$string['reviewcoursedescription'] = 'Analyses your whole course and creates a guided tour with suggestions. <b>Tip:</b> if this feels like a lot, start with a single section first.';
$string['startreview'] = 'Start review';
$string['startreviewhelp'] = 'Pick a row and start the review to see tips directly on your course page. Tip: start with a section review to keep it manageable.';
$string['reviewcoursematerialsintro'] = 'The hints in the guided tour are based on these materials (from MIau.nrw):';
$string['reviewcoursematerial_leitfaden'] = 'Leitfaden adaptive Lehre (PDF)';
$string['reviewcoursematerial_miau'] = 'Adaptive Kursgestaltung in Moodle (PDF)';
$string['startreviewerror'] = 'Unable to start the adaptive review right now. Please try again later.';
$string['startreviewpermission'] = 'You do not have permission to start a review in this course.';
$string['loop_quiz_unlock_followups_summary'] = 'We scan your course for adaptive learning patterns—checkpoints, feedback loops, and conditional progression—and suggest practical improvements.';
$string['tourname'] = 'Course tips for {$a}';
$string['tourdescription'] = 'A quick walkthrough with suggestions for {$a}.';
$string['tourintro_default_title'] = 'How to use this tour';
$string['tourintro_default_content'] = '<p>This tour guides you step by step. Apply the suggestion, then continue.</p>';
$string['tourintro_audit_title'] = 'How to use the audit tour';
$string['tourintro_audit_content'] = '<p>This audit tour highlights improvement opportunities directly in your course.</p><ul><li><strong>Nothing is changed automatically</strong> — you decide what to implement.</li><li>Apply the suggestion (often in editing mode), then continue to the next step.</li><li>If a step offers a button (e.g. “Show me how…”), it opens a short guided help for that specific setting.</li></ul>';
$string['tourintro_scenario_title'] = 'How to use the scenario tour';
$string['tourintro_scenario_content'] = '<p>The scenario tour is a practical checklist for building an adaptive course design.</p><ul><li><strong>Nothing is changed automatically</strong> — use the steps as guidance while you edit your course.</li><li>Turn editing on so you can implement changes as you go.</li><li>Think of the steps as milestones (structure first, then activities, then conditions/feedback).</li></ul>';
$string['tourintro_teach_title'] = 'How to use the guided help';
$string['tourintro_teach_content'] = '<p>This short tour focuses on one specific setting or action (e.g. quiz behaviour, feedback, access conditions).</p><ul><li>Make the change on this page, then continue to the next step.</li><li><strong>Nothing is changed automatically</strong> — the tour only explains what to do.</li><li>You can close the tour at any time and resume later.</li></ul>';
$string['reviewtableheading'] = 'What would you like to review?';
$string['table_heading'] = 'Course audit tours';
$string['reviewcoltitle'] = 'Scope';
$string['reviewcoldescription'] = 'What we check';
$string['reviewcolaction'] = '';
$string['reviewbadge_extensive'] = 'More extensive';
$string['teachquiz_row_title'] = 'Quiz: {$a}';
$string['teachquiz_row_description'] = 'A short guided tour through the key quiz settings for adaptive teaching (behaviour, attempts, feedback, review options, timing, security).';
$string['teachquiz_guidedhelp_button'] = 'Guided help';
$string['touraction_add_quiz'] = 'Add a quiz here';
$string['touraction_edit_quiz_settings'] = 'Edit "{$a}" settings';
$string['startteacherror'] = 'Unable to start the teaching tour right now. Please try again later.';
$string['teachtourname'] = 'Guided help: {$a}';
$string['teachtourdescription'] = 'Short, practical steps for quiz settings in {$a->course}.';
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

// Course-level: filter settings (auto-linking for activity names).
$string['rule_course_filter_activitynames_name'] = 'Activity auto-linking in feedback';
$string['rule_course_filter_activitynames_description'] = 'Check whether the “Activity names auto-linking” filter is enabled in this course (helps link course materials in feedback).';
$string['rule_course_filter_activitynames_rationale'] = '<h5>Why this matters</h5><p>Actionable feedback often points learners to the right next resource—especially through links to learning materials in the course (cf. Leitfaden adaptive Lehre, 2025, Ch. 3.3 & 4; MIau.nrw, Ch. Erste Schritte & Next Steps).</p><p>If <strong>Activity names auto-linking</strong> is enabled, you can reference activity names in quiz/question feedback and Moodle will auto-link them—a technical implementation aid that makes linking in feedback easier.</p>';
$string['rule_course_filter_activitynames_headline_success'] = 'Activity auto-linking is available';
$string['rule_course_filter_activitynames_headline_needs_work'] = 'Activity auto-linking is not enabled';
$string['rule_course_filter_activitynames_missing'] = 'We recommend enabling the <strong>Activity names auto-linking</strong> filter in this course so activity references in feedback can become direct links.';
$string['rule_course_filter_activitynames_notavailable'] = 'The <strong>Activity names auto-linking</strong> filter does not appear to be available in this course (it may be disabled site-wide). It can help to ask an admin to enable it.';
$string['touraction_open_course_filters'] = 'Open course filter settings';

// Quiz unlock follow-ups rule strings.
$string['rule_loop_quiz_unlock_followups_name'] = 'Learn, then prove it';
$string['rule_loop_quiz_unlock_followups_headline_min_items'] = 'Add more activities';
$string['rule_loop_quiz_unlock_followups_headline_missing_quiz'] = 'No quiz yet';
$string['rule_loop_quiz_unlock_followups_headline_missing_kb'] = 'Start with content';
$string['rule_loop_quiz_unlock_followups_headline_quiz_no_precondition'] = 'Quiz needs a gate';
$string['rule_loop_quiz_unlock_followups_headline_no_followups'] = 'Nothing unlocked by quiz';
$string['rule_loop_quiz_unlock_followups_headline_success'] = 'Looking good!';
$string['rule_loop_quiz_unlock_followups_description'] = 'Content first, then a quiz that unlocks what comes next.';
$string['rule_loop_quiz_unlock_followups_min_items'] = 'This section needs at least {$a} activities to form a proper sequence. Please add activities following the pattern “content → quiz → next steps” and follow the instructions to make this section more adaptive.';
$string['rule_loop_quiz_unlock_followups_missing_quiz'] = 'We recommend adding a quiz so learners can show what they\'ve learned.';
$string['rule_loop_quiz_unlock_followups_missing_kb'] = 'It can help to add some content before the quiz (a page, book, or link works well).';
$string['rule_loop_quiz_unlock_followups_quiz_no_precondition'] = 'We recommend gating the quiz behind earlier work so learners don\'t skip ahead.';
$string['rule_loop_quiz_unlock_followups_no_followups'] = 'Nothing is gated by this quiz yet. It can help to link follow-up activities to quiz completion.';
$string['rule_loop_quiz_unlock_followups_additional_followups'] = 'You could add another activity that depends on "{$a->activity}".';
$string['rule_loop_quiz_unlock_followups_followup_list'] = '{$a->count} activities unlock after the quiz: {$a->items}.';
$string['rule_loop_quiz_unlock_followups_success'] = 'Nice! Content leads into a quiz that unlocks the next steps.';
$string['rule_loop_quiz_unlock_followups_rationale'] = '<h5>Why this matters</h5><p>A minimal adaptive learning path often follows a simple pattern:</p><ul><li>learning content</li><li>competence check (quiz)</li><li>targeted next steps</li></ul><p>Gating follow-up activities and using completion tracking helps learners prove what they know before moving on (cf. Leitfaden adaptive Lehre, 2025, Ch. 3.1, Fig. 2; MIau.nrw, Ch. Erste Schritte).</p>';

// Grade branching rule strings.
$string['rule_loop_branch_by_grade_name'] = 'Branch by quiz score';
$string['rule_loop_branch_by_grade_description'] = 'Use quiz grade thresholds to unlock different follow-up paths (remedial vs advanced).';
$string['rule_loop_branch_by_grade_headline_missing_gradeitem'] = 'Grade-based branching not available';
$string['rule_loop_branch_by_grade_missing_gradeitem'] = 'We could not find a grade item for the quiz "{$a}". It can help to ensure the quiz has a maximum grade and is included in the gradebook.';
$string['rule_loop_branch_by_grade_headline_missing'] = 'No grade-based branches yet';
$string['rule_loop_branch_by_grade_missing'] = 'We recommend using Restrict access → Grade conditions on follow-up activities so learners see different next steps based on their score in "{$a}".';
$string['rule_loop_branch_by_grade_headline_success'] = 'Grade-based branching found';
$string['rule_loop_branch_by_grade_found'] = 'Follow-ups are gated by "{$a->quiz}" score: {$a->branches}.';
$string['rule_loop_branch_by_grade_suggest_two_paths'] = 'Consider defining both a “remedial” path (max score) and an “advanced” path (min score) for "{$a}".';
$string['rule_loop_branch_by_grade_rationale'] = '<h5>Why this matters</h5><p>Branching paths by quiz score is a practical way to react to heterogeneous prior knowledge.</p><p>Learners can be guided to remediation or extension activities via prerequisites based on performance (cf. Leitfaden adaptive Lehre, 2025, Ch. 3.1; MIau.nrw, Ch. Erste Schritte).</p>';
$string['rule_loop_branch_by_grade_range_any'] = 'any score';
$string['rule_loop_branch_by_grade_range_min'] = '≥ {$a}%';
$string['rule_loop_branch_by_grade_range_max'] = '< {$a}%';
$string['rule_loop_branch_by_grade_range_between'] = '{$a->min}%–{$a->max}%';
$string['touraction_add_grade_gate'] = 'Add a grade gate to "{$a}"';
$string['actiontour_gradegate_step_access_title'] = 'Add a grade condition';
$string['actiontour_gradegate_step_access_body'] = '<p>Open <strong>Restrict access</strong> and add a <strong>Grade</strong> condition (min/max) tied to the quiz score.</p><p><strong>Result:</strong> learners see different follow-ups based on their score.</p>';

// Quiz feedback quality rule strings.
$string['rule_loop_quiz_feedback_name'] = 'Actionable Quiz-Feedback';
$string['rule_loop_quiz_feedback_description'] = 'Check whether Overall feedback is set for the quiz (the baseline for actionable guidance and adaptive next steps).';
$string['rule_loop_quiz_feedback_headline_success'] = 'Feedback supports adaptive next steps';
$string['rule_loop_quiz_feedback_headline_needs_work'] = 'Feedback could be more adaptive';
$string['rule_loop_quiz_feedback_missing'] = 'We recommend adding Overall feedback to "{$a}" so learners get guidance after submitting.';
$string['rule_loop_quiz_feedback_found'] = 'Overall feedback exists for "{$a}".';
$string['rule_loop_quiz_feedback_missing_links'] = 'Tip: When you add overall feedback, make it actionable (e.g. include links to relevant resources). Alternatively, if available, you can use the “Activity names auto-linking” filter so activities referenced by name become links automatically.';
$string['rule_loop_quiz_feedback_suggest_attempts'] = 'Consider allowing more than one attempt for "{$a}" to support practice loops.';
$string['rule_loop_quiz_feedback_rationale'] = '<h5>Why this matters</h5><p>Feedback in tests is a low-threshold entry into adaptive teaching.</p><p><strong>Actionable feedback</strong> (ideally with links) and the option to retry can help learners self-steer and close gaps before progressing (cf. Leitfaden adaptive Lehre, 2025, Ch. 3.3 & 4; MIau.nrw, Ch. Erste Schritte & Next Steps).</p>';
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

// Quiz completion tracking (reliable follow-ups and reporting).
$string['actiontour_quizcompletion_step_completion_title'] = 'Track completion for follow-ups';
$string['actiontour_quizcompletion_step_completion_body'] = '<p>Under <strong>Activity completion</strong>, set conditions that mark the quiz complete (e.g. submitted and/or a passing grade).</p><p><strong>Why:</strong> completion lets you unlock follow-up activities, track progress in reports, and build reliable learning paths.</p>';

// Quiz timing and security (access rules).
$string['actiontour_quiztimingsecurity_step_timing_title'] = 'Set timing and availability';
$string['actiontour_quiztimingsecurity_step_timing_body'] = '<p>Use <strong>Timing</strong> to control availability (open/close) and, if needed, add a time limit.</p><p><strong>Tip:</strong> align timing with your course rhythm so learners can plan.</p>';
$string['actiontour_quiztimingsecurity_step_security_title'] = 'Review access restrictions';
$string['actiontour_quiztimingsecurity_step_security_body'] = '<p>In <strong>Security</strong>, decide whether additional restrictions are needed (e.g. password, subnet, browser security).</p><p><strong>Tip:</strong> keep it simple unless you have a clear exam scenario.</p>';

// Diagnostic checkpoint rule strings.
$string['rule_loop_diagnostic_checkpoint_name'] = 'Diagnostic checkpoint';
$string['rule_loop_diagnostic_checkpoint_description'] = 'Use a short diagnostic (Choice/Feedback/Survey) plus signposting to support learner self-steering.';
$string['rule_loop_diagnostic_checkpoint_rationale'] = '<h5>Why this matters</h5><p>Short diagnostics (needs/self-assessment) provide a feedback signal for learners and teachers.</p><p>With clear signposting and optional gating, they support self-steering and help learners choose an appropriate path (cf. Leitfaden adaptive Lehre, 2025, Ch. 3.4 & 4; MIau.nrw, Ch. Erste Schritte).</p>';
$string['rule_loop_diagnostic_checkpoint_headline_missing'] = 'No diagnostic checkpoint';
$string['rule_loop_diagnostic_checkpoint_missing'] = 'We recommend adding a short diagnostic activity (e.g. Feedback) to capture needs or self-assessment at the start of this section.';
$string['rule_loop_diagnostic_checkpoint_found'] = 'Diagnostic checkpoint found: "{$a}".';
$string['rule_loop_diagnostic_checkpoint_missing_signposting'] = 'It can help to add signposting right after the checkpoint (e.g. a Label or Page) explaining which path to follow next.';
$string['rule_loop_diagnostic_checkpoint_suggest_gate'] = 'Optionally, it can help to gate follow-up activities behind completion of "{$a}" so learners don’t skip the checkpoint.';
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
$string['rule_loop_quiz_adaptive_behaviour_rationale'] = '<h5>Why this matters</h5><p>Adaptive teaching benefits from continuous feedback loops and short competence checks (cf. Leitfaden adaptive Lehre, 2025, Ch. 3.2 & 4; MIau.nrw, Ch. Erste Schritte).</p><p>Adaptive/interactive question behaviours in Moodle can turn a quiz into a practice loop with immediate feedback, supporting learners to self-correct before moving on.</p>';
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
$string['rule_loop_lesson_branching_rationale'] = '<h5>Why this matters</h5><p>Branching learning paths allow different next steps depending on learners’ answers (e.g., remediation vs. extension).</p><p>Moodle Lessons support complex learning paths with conditional navigation (cf. Leitfaden adaptive Lehre, 2025, Ch. 3.1; MIau.nrw, Ch. Erste Schritte).</p>';
$string['rule_loop_lesson_branching_found'] = 'Lesson "{$a}" contains branching jumps between pages.';
$string['rule_loop_lesson_branching_missing'] = 'Lesson "{$a}" appears mostly linear. Consider adding conditional jumps to guide learners to different follow-up pages based on answers.';
$string['rule_loop_lesson_branching_lesson_no_answers'] = 'Lesson "{$a}" has no answer records yet (it may still be empty).';
$string['touraction_open_lesson_editor'] = 'Open lesson editor for "{$a}"';

// Quiz random questions rule strings.
$string['rule_loop_quiz_random_questions_name'] = 'Random questions (question pools)';
$string['rule_loop_quiz_random_questions_description'] = 'Check whether quizzes use random question slots (question pools) to vary attempts.';
$string['rule_loop_quiz_random_questions_headline_success'] = 'Random question slots found';
$string['rule_loop_quiz_random_questions_headline_needs_work'] = 'No random question slots yet';
$string['rule_loop_quiz_random_questions_rationale'] = '<h5>Why this matters</h5><p>Random question pools can make retakes meaningful and support practice loops.</p><p>They also enable differentiated testing designs (cf. Leitfaden adaptive Lehre, 2025, Ch. 3.2).</p>';
$string['rule_loop_quiz_random_questions_found'] = 'Quiz "{$a->quiz}" contains {$a->count} random question slot(s).';
$string['rule_loop_quiz_random_questions_missing'] = 'Quiz "{$a}" does not use random question slots yet. Consider adding random questions from a categorised question bank.';
$string['rule_loop_quiz_random_questions_empty'] = 'Quiz "{$a}" has no slots/questions yet (it may still be empty).';
$string['touraction_open_quiz_edit'] = 'Edit questions in "{$a}"';
$string['actiontour_quizrandomquestions_step_add_title'] = 'Add a random question';
$string['actiontour_quizrandomquestions_step_add_body'] = '<p>Open the <strong>Add</strong> menu here and choose <strong>a random question</strong>.</p><p><strong>Tip:</strong> use categories to build question pools and vary attempts.</p>';

// H5P presence rule strings.
$string['rule_loop_h5p_interactive_name'] = 'Interactive H5P (presence)';
$string['rule_loop_h5p_interactive_description'] = 'Check whether an H5P activity exists in the section (presence check only).';
$string['rule_loop_h5p_interactive_headline_success'] = 'H5P activity found';
$string['rule_loop_h5p_interactive_headline_needs_work'] = 'No H5P activity yet';
$string['rule_loop_h5p_interactive_rationale'] = '<h5>Why this matters</h5><p>H5P can support decision points and branching learning paths (e.g. Branching Scenario).</p><p>This helps learners navigate content in a way that fits their needs (cf. Leitfaden adaptive Lehre, 2025, Ch. 3.1; MIau.nrw, Ch. Und nun?).</p>';
$string['rule_loop_h5p_interactive_found'] = 'H5P activity(ies) found in this section: {$a}.';
$string['rule_loop_h5p_interactive_missing'] = 'No H5P activity found in this section. If you want a visual branching scenario, H5P can be a lightweight entry point.';
$string['touraction_add_h5p'] = 'Add an H5P activity';
$string['actiontour_h5p_step_name_title'] = 'Name the H5P activity';
$string['actiontour_h5p_step_name_body'] = '<p>Choose a clear name so learners know what to expect.</p><ul><li>Example: <strong>Branching scenario</strong></li><li>Example: <strong>Choose your path</strong></li></ul>';

// Scenario tours.
$string['scenario_heading'] = 'Scenario tours: step by step to an adaptive course';
$string['scenario_description'] = 'Choose a scenario that fits your course. The tour will guide you through the key steps.';
$string['scenario_description_html'] = '<p>Choose a scenario that fits your course. The tour will guide you through the key steps. <strong>Nothing is changed automatically</strong> — apply the suggestions as you go.</p><ul><li><strong>Path 1 (Minimalist):</strong> start small and make selected parts adaptive.</li><li><strong>Path 2 (Sequential):</strong> topics build on each other; unlock sections step by step.</li><li><strong>Path 3 (Compass):</strong> free topic order with a “compass” quiz for orientation.</li></ul><p><strong>Tip:</strong> turn editing on in your course so you can implement changes while following the tour.</p>';
$string['scenario_1_button'] = 'Minimalist: adapt individual parts';
$string['scenario_1_title'] = 'Path 1 – Minimalist adaptive course design';
$string['scenario_2_button'] = 'Sequential: topics build on each other';
$string['scenario_2_title'] = 'Path 2 – Simple course design, sequential topics';
$string['scenario_3_button'] = 'Compass: free topic order';
$string['scenario_3_title'] = 'Path 3 – Compass model, non-sequential topics';
$string['startscenarioerror'] = 'Unable to start the scenario tour right now. Please try again later.';
$string['scenario_tourname'] = 'Scenario tour: {$a}';
$string['scenario_tourdescription'] = 'Step-by-step guidance for adaptive course design.';

// Path 1 – Minimalist.
$string['scenario_1_step1_title'] = 'Reflect on goals and target group';
$string['scenario_1_step1_content'] = '<p>Before you start adapting your course, clarify your <strong>learning goals</strong>, <strong>target group</strong>, and <strong>prerequisites</strong>.</p><p>Ask yourself: What should learners be able to do at the end? What prior knowledge do they bring?</p><div class="local-aca-tour-rationale"><h5>Why this matters</h5><p>Adaptive teaching requires instructors to develop diagnostic competence to assess learner needs. Reflecting on goals and target group is the starting point for targeted adaptation (cf. Leitfaden adaptive Lehre, 2025; MIau.nrw).</p></div>';
$string['scenario_1_step2_title'] = 'Define scope: individual parts';
$string['scenario_1_step2_content'] = '<p>Start by choosing <strong>individual sections</strong> to make adaptive. You don\'t need to redesign the entire course at once.</p><p>Pick a section that is particularly suitable (e.g. one with heterogeneous prior knowledge).</p><div class="local-aca-tour-rationale"><h5>Why this matters</h5><p>Start small: adaptive teaching can begin within a single course section without redesigning the whole course (recommendation 1, Leitfaden adaptive Lehre, 2025).</p></div>';
$string['scenario_1_step3_title'] = 'The template: Content → Quiz → More Content';
$string['scenario_1_step3_content'] = '<p>The minimalist adaptive structure has three parts in one section:</p><ol><li><strong>Learning content</strong> – a page, video, or book that introduces the topic</li><li><strong>A quiz</strong> – a short competence check with adaptive mode enabled</li><li><strong>Follow-up content</strong> – additional resources unlocked for students who need more support</li></ol><p>Let\'s build this together, one piece at a time.</p><div class="local-aca-tour-rationale"><h5>Why this matters</h5><p>The basic 3-element structure (learning content → competence check → optional content) is the foundational pattern for branching learning paths. It allows learners to see different next steps based on their results (cf. Leitfaden adaptive Lehre, 2025; MIau.nrw).</p></div>';
$string['scenario_1_step4_title'] = 'Step 1 of 3 – Create a new section';
$string['scenario_1_step4_content'] = '<p>First, create a dedicated section for your adaptive unit.</p><ol><li>Make sure course editing is turned on (top right of the course page)</li><li>Scroll to the bottom of your course and click <strong>Add section</strong></li><li>Name it something like <em>Adaptive Unit: [Your Topic]</em></li></ol><p>Click <strong>Next</strong> when your section is ready.</p>';
$string['scenario_1_step5_title'] = 'Step 2 of 3 – Add learning content';
$string['scenario_1_step5_content'] = '<p>Now add a learning content page to your course. Click the button below to open the page editor with a guided tour.</p>';
$string['scenario_1_step5_button'] = 'Add a content page →';
$string['scenario_1_step6_title'] = 'Step 3 of 3 – Add a quiz';
$string['scenario_1_step6_content'] = '<p>Now add a quiz for the competence check. Click the button below to open the quiz editor with a guided tour for adaptive settings.</p>';
$string['scenario_1_step6_button'] = 'Add a quiz →';
$string['scenario_1_step7_title'] = 'Add follow-up content';
$string['scenario_1_step7_content'] = '<p>Finally, add content for students who need more support. This could be:</p><ul><li>A <strong>Page</strong> with additional explanations or worked examples</li><li>A <strong>URL</strong> linking to external reading material</li><li>A <strong>File</strong> with supplementary material</li></ul><p>Add this activity in your new section using the standard course editor. Once it\'s there, set access restrictions so it only unlocks for students who did not pass the quiz.</p><p><strong>Your minimalist adaptive structure is now in place. Well done!</strong></p>';
// Subtour A: Page creation (guided via action button from step 5).
$string['minimalist_page_tour_intro_title'] = 'Creating your content page';
$string['minimalist_page_tour_intro_content'] = 'This short guide will walk you through setting up a learning content page for your adaptive unit.';
$string['minimalist_page_step1_title'] = 'Name your content page';
$string['minimalist_page_step1_content'] = '<p>Give this page a clear name, for example: <em>Learning Material: [Your Topic]</em></p>';
$string['minimalist_page_step2_title'] = 'Add your learning content';
$string['minimalist_page_step2_content'] = '<p>Write or paste your learning content here. Placeholder text is fine for now – you can edit it any time.</p>';
$string['minimalist_page_step3_title'] = 'Save and return';
$string['minimalist_page_step3_content'] = '<p>Use the <strong>Save and return to course</strong> button to save this page. Then return to your course guide and click <strong>Next</strong> to continue.</p>';
// Subtour B: Quiz creation (guided via action button from step 6).
$string['minimalist_quiz_tour_intro_title'] = 'Creating your adaptive quiz';
$string['minimalist_quiz_tour_intro_content'] = 'This guide will walk you through the key settings for an adaptive quiz.';
$string['minimalist_quiz_step1_title'] = 'Name your quiz';
$string['minimalist_quiz_step1_content'] = '<p>Give this quiz a clear name, for example: <em>Knowledge Check: [Your Topic]</em></p>';
$string['minimalist_quiz_step2_title'] = 'Set adaptive mode';
$string['minimalist_quiz_step2_content'] = '<p>Set the question behaviour to <strong>Adaptive mode</strong>. This gives students immediate feedback after each answer and lets them retry questions.</p>';
$string['minimalist_quiz_step3_title'] = 'Allow multiple attempts';
$string['minimalist_quiz_step3_content'] = '<p>Allow at least 2–3 attempts. This enables the adaptive retry loop so students can keep practising.</p>';
$string['minimalist_quiz_step4_title'] = 'Set completion conditions';
$string['minimalist_quiz_step4_content'] = '<p>Enable a completion condition (e.g. require a passing grade). This is what unlocks or locks follow-up content based on quiz results.</p>';
$string['minimalist_quiz_step5_title'] = 'Save and return';
$string['minimalist_quiz_step5_content'] = '<p>Save the quiz using <strong>Save and return to course</strong>. You can add questions to it later directly from the course page.</p>';

// Path 2 – Sequential.
$string['scenario_2_step1_title'] = 'Assume existing adaptive building blocks';
$string['scenario_2_step1_content'] = '<p>This scenario assumes that your sections already contain <strong>minimal adaptive elements</strong>, for example a content item and an activity that can later act as a completion signal.</p><p>This tour does <strong>not</strong> rebuild those elements. It focuses only on connecting two sections.</p>';
$string['scenario_2_step2_title'] = 'Choose a free section pairing';
$string['scenario_2_step2_content'] = '<p>You are working with <strong>free pairing</strong>: first choose the source section, then explicitly choose the target section that should unlock afterwards.</p><p>This lets you connect sections based on your own didactic structure instead of a fixed n+1 pattern.</p>';
$string['scenario_2_step3_title'] = 'Select the source section';
$string['scenario_2_step3_content'] = '<p>Select the section that already contains the activity whose completion should unlock another section.</p><p>Use one of the buttons below to continue.</p>';
$string['scenario_2_step4_title'] = 'Select the target section';
$string['scenario_2_step4_content'] = '<p>Now choose the section that should become available after the source activity has been completed.</p><p>Pick the target section below.</p>';
$string['scenario_2_step5_title'] = 'Choose the source activity';
$string['scenario_2_step5_content'] = '<p>Select the existing activity in the source section that should act as the completion signal for the target section.</p><p>Typical examples are quizzes, assignments, or pages with completion tracking.</p>';
$string['scenario_choice_none_available'] = 'No matching choices are available yet.';
$string['scenario_2_completion_tour_title'] = 'Configure completion on the source activity';
$string['scenario_2_completion_tour_content'] = 'This short tour shows where to turn the selected source activity into a usable completion signal.';
$string['scenario_2_completion_step1_title'] = 'Enable a usable completion rule';
$string['scenario_2_completion_step1_content'] = '<p>Use the completion area to configure the activity so it can serve as a reliable prerequisite for another section.</p><p>Choose a rule that matches the activity type, such as <strong>students must view</strong> or <strong>students must receive a grade</strong>.</p>';
$string['scenario_2_completion_step2_title'] = 'Save and continue to the target section';
$string['scenario_2_completion_step2_content'] = '<p>Save the activity settings. Then open the target section and add a restriction that depends on this completion rule.</p>';
$string['scenario_2_completion_step2_button'] = 'Open target section restrictions →';
$string['scenario_2_restriction_tour_title'] = 'Restrict the target section';
$string['scenario_2_restriction_tour_content'] = 'This short tour highlights where the target section can be restricted based on the chosen source activity.';
$string['scenario_2_restriction_step1_title'] = 'Add an activity completion restriction';
$string['scenario_2_restriction_step1_content'] = '<p>Use the access restrictions area to add a condition based on <strong>activity completion</strong>.</p><p>Select the source activity and require that it must be completed before this section becomes available.</p>';
$string['scenario_2_restriction_step2_title'] = 'Save the section and return to the course';
$string['scenario_2_restriction_step2_content'] = '<p>Save the section settings once the restriction is in place. Afterwards return to the course to continue or repeat the pattern.</p>';
$string['scenario_2_restriction_step2_button'] = 'Return to the course →';
$string['scenario_2_repeat_tour_title'] = 'Repeat the linking pattern';
$string['scenario_2_repeat_tour_content'] = 'The first section link is now configured. You can repeat the same pattern for any other source-target pair in your course.';
$string['scenario_2_repeat_step_title'] = 'Repeat for further section pairs';
$string['scenario_2_repeat_step_content'] = '<p>You have completed one section-to-section connection.</p><p>Repeat this pattern wherever another section should open only after a chosen source activity has been completed.</p>';

// Path 3 – Compass model.
$string['scenario_3_step1_title'] = 'Introduce the compass model';
$string['scenario_3_step1_content'] = '<p>The compass model works best when the connected sections cover <strong>distinct topics</strong>.</p><p>These topics should not depend on a strict buildup. Learners should be able to enter one topic path without first completing another.</p>';
$string['scenario_3_step2_title'] = 'Plan one complete topic cycle';
$string['scenario_3_step2_content'] = '<p>This run builds exactly <strong>one compass cycle</strong>:</p><ol><li>Choose or create the compass quiz</li><li>Add one multiple-choice question with answer feedback</li><li>Create one feedback activity at the course end</li><li>Use view completion on that activity</li><li>Unlock one topic section through it</li></ol>';
$string['scenario_3_step3_title'] = 'Create or choose the compass quiz';
$string['scenario_3_step3_content'] = '<p>Create a compass quiz or select an existing quiz that should play this role.</p><p>After saving a newly created quiz, start this scenario again and pick it from the list below.</p>';
$string['scenario_3_step3_button'] = 'Create compass quiz →';
$string['scenario_3_step4_title'] = 'Create or choose the feedback activity';
$string['scenario_3_step4_content'] = '<p>Create a new feedback activity at the end of the course, preferably a <strong>page</strong>.</p><p>The activity title must match the answer feedback text from the compass question exactly, for example <em>For Access to Topic X - Click here</em>.</p><p>After saving a new page, start this scenario again and select it from the list below.</p>';
$string['scenario_3_step4_button'] = 'Create feedback activity →';
$string['scenario_3_step5_title'] = 'Choose the topic section to unlock';
$string['scenario_3_step5_content'] = '<p>Select the topic section that should only become available after learners have viewed the feedback activity.</p><p>The next tour will guide you through the access restriction on that section.</p>';
$string['scenario_3_step5_button'] = 'Set up compass question →';
$string['compass_orientation_tour_intro_title'] = 'Create the feedback activity';
$string['compass_orientation_tour_intro_content'] = 'This short tour helps you create the feedback activity whose title must match the answer feedback from the compass question.';
$string['compass_orientation_step1_title'] = 'Use the feedback text as the activity title';
$string['compass_orientation_step1_content'] = '<p>Name this page exactly like the answer feedback from the compass question, for example <em>For Access to Topic X - Click here</em>.</p>';
$string['compass_orientation_step2_title'] = 'Add a short orientation prompt';
$string['compass_orientation_step2_content'] = '<p>Use the page content to tell learners that opening this activity gives them access to the linked topic section.</p>';
$string['compass_orientation_step3_title'] = 'Save and return to the course';
$string['compass_orientation_step3_content'] = '<p>Save the activity with <strong>Save and return to course</strong>. Afterwards reopen this scenario and select the page you just created.</p>';
$string['compass_quiz_tour_intro_title'] = 'Create the compass quiz';
$string['compass_quiz_tour_intro_content'] = 'This short tour walks you through the quiz settings for a compass that steers learners toward one topic path.';
$string['compass_quiz_step1_title'] = 'Name the compass quiz clearly';
$string['compass_quiz_step1_content'] = '<p>Give the quiz a clear name such as <em>Compass quiz</em> or <em>Find your next topic</em>.</p>';
$string['compass_quiz_step2_title'] = 'Use settings that support orientation';
$string['compass_quiz_step2_content'] = '<p>Choose settings that keep the quiz lightweight and guidance-oriented. The quiz should diagnose preference or prior knowledge, not act as a final exam.</p>';
$string['compass_quiz_step3_title'] = 'Save and return to the course';
$string['compass_quiz_step3_content'] = '<p>Save the quiz with <strong>Save and return to course</strong>. Then reopen this scenario and select the quiz from the list.</p>';
$string['compass_feedback_tour_intro_title'] = 'Create one compass question with feedback';
$string['compass_feedback_tour_intro_content'] = 'This tour focuses on a single multiple-choice question that points learners toward one topic section.';
$string['compass_feedback_step1_title'] = 'Write the question prompt';
$string['compass_feedback_step1_content'] = '<p>Create one multiple-choice question that checks prior knowledge, need, or preference related to one topic.</p>';
$string['compass_feedback_step2_title'] = 'Add answer options';
$string['compass_feedback_step2_content'] = '<p>Enter the answer options learners can choose from. Keep them aligned with the topic decision you want the compass to support.</p>';
$string['compass_feedback_step3_title'] = 'Enter the feedback title exactly';
$string['compass_feedback_step3_content'] = '<p>In the answer feedback or general feedback, use the exact title of the activity learners should click next, for example <em>For Access to Topic X - Click here</em>.</p>';
$string['compass_feedback_step4_title'] = 'Save the question';
$string['compass_feedback_step4_content'] = '<p>Save the question. Afterwards the scenario continues with creating the matching feedback activity in the course.</p>';
$string['compass_feedback_step5_title'] = 'Save the question';
$string['compass_feedback_step5_content'] = '<p>Save the question once the prompt, answers, and feedback title are ready.</p>';
$string['compass_feedback_gateway_tour_intro_title'] = 'Open the question editor from the compass quiz';
$string['compass_feedback_gateway_tour_intro_content'] = 'This short gateway starts on the quiz structure page and then opens the question editor.';
$string['compass_feedback_gateway_step1_title'] = 'Start from the quiz structure';
$string['compass_feedback_gateway_step1_content'] = '<p>Use the quiz structure page to confirm that this quiz is the compass for the topic cycle you are setting up.</p>';
$string['compass_feedback_gateway_step2_title'] = 'Open a new multiple-choice question';
$string['compass_feedback_gateway_step2_content'] = '<p>Use the button below to open the editor for one new multiple-choice question and enter the topic-specific feedback there.</p>';
$string['compass_feedback_open_editor_button'] = 'Open question editor →';
$string['scenario_3_completion_tour_title'] = 'Configure view completion on the feedback activity';
$string['scenario_3_completion_tour_content'] = 'This short tour shows where the feedback activity can be turned into a view-based completion signal.';
$string['scenario_3_completion_step1_title'] = 'Require learners to view the activity';
$string['scenario_3_completion_step1_content'] = '<p>Use activity completion to require that learners <strong>view</strong> this activity. That view event will become the signal for unlocking the topic section.</p>';
$string['scenario_3_completion_step2_title'] = 'Save and continue to the topic section';
$string['scenario_3_completion_step2_content'] = '<p>Save the activity settings. Then open the topic section and add an activity completion restriction based on this feedback activity.</p>';
$string['scenario_3_completion_step2_button'] = 'Open topic section restrictions →';
$string['scenario_3_restriction_tour_title'] = 'Restrict the topic section';
$string['scenario_3_restriction_tour_content'] = 'This short tour highlights where the topic section can be released only after the feedback activity has been viewed.';
$string['scenario_3_restriction_step1_title'] = 'Add the feedback activity as a restriction';
$string['scenario_3_restriction_step1_content'] = '<p>Use access restrictions to require completion of the feedback activity before this topic section becomes available.</p>';
$string['scenario_3_restriction_step2_title'] = 'Save the section and return to the course';
$string['scenario_3_restriction_step2_content'] = '<p>Save the section settings. Then return to the course to repeat the same compass pattern for another topic.</p>';
$string['scenario_3_restriction_step2_button'] = 'Return to the course →';
$string['scenario_3_repeat_tour_title'] = 'Repeat the compass cycle';
$string['scenario_3_repeat_tour_content'] = 'One complete compass cycle is now in place. You can repeat the same pattern for other topic sections.';
$string['scenario_3_repeat_step_title'] = 'Repeat for further topics';
$string['scenario_3_repeat_step_content'] = '<p>You have completed one full compass cycle: question, feedback activity, view completion, and section release.</p><p>Repeat this cycle for each additional topic you want the compass to route to.</p>';
