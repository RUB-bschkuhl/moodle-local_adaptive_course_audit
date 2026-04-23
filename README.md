[![Intro](pix/02_Intro_Katze_transparent.gif)](pix/02_Intro_Katze_transparent.gif)

# Adaptive course audit

`local/adaptive_course_audit` adds guided, in-course review tours to help teachers
build adaptive learning paths in Moodle.

## What this plugin does

- Adds a **Review course** navigation entry per course.
- Runs an adaptive review for a whole course or a single section.
- Creates short guided tours for quiz settings and three scenario-based setup paths.
- Offers a **resume last audit** shortcut for teachers/managers.
- Never changes course settings automatically; it only guides the user step by step.

The audit logic includes checks such as:
- content -> quiz -> follow-up unlock patterns
- quiz feedback and adaptive behaviour
- branching by grade / conditional progression
- optional interactive/diagnostic patterns

## Supported Moodle versions

- Moodle **4.5+** (`$plugin->requires = 2024100700`).

## Dependencies

- Requires Moodle core subsystem plugin **User tours** (`tool_usertours`).
- Keep User tours enabled for full plugin functionality.

## Installation

1. Copy the plugin to `local/adaptive_course_audit`.
2. Visit **Site administration -> Notifications** or run `php admin/cli/upgrade.php`.
3. Assign required capabilities and purge caches if needed.

No Composer or npm build step is required on the target Moodle server.

## Permissions

- `local/adaptive_course_audit:view` to access the review page.
- `moodle/course:manageactivities` to start review/scenario actions.

## Included materials

The guidance references these PDF resources from [`files/`](files/):

- [Leitfaden Adaptive Lehre (PDF)](files/Leitfaden_Adaptive_Lehre.pdf)
- [Adaptive Kursgestaltung in Moodle (PDF)](files/Adaptive_Kursgestaltung_in_Moodle.pdf)
