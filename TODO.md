[x] Delete logik nach tour abschluss 
[x] MIau button in kurs nach tour - Nö

[x] Quiz adaptiv eingestellt prüfen
[x] mehr How tos?
[] Voreingestellte Feedbacks für fragen prüfen
[] Integration von Bausteinen (Aktivitätsgruppen?) an anderer stelle nicht in erklärung?

[x] rework interface on review page, what makes sense?
[] tour inside question bank / other areas


Mechanismus erklären bevor auf review geklickt wird
Nutzer wissen sonst nicht warum sie das review starten wollen wenn nicht irhe frage beantwortet wird.
Fragen im Dropdown auswählen die beantwortet werden sollen (ist mein quiz gut auf adaptive szenarien eingestelt?)


grunt 

## Compliance / plugin submission TODOs

- [ ] Replace the `null_provider` privacy implementation with a real Privacy API provider for `local_adaptive_course_review` and update the `privacy:metadata` strings to describe the stored user/course review data correctly.
- [ ] Declare the dependency on `tool_usertours` in `version.php` (and keep it documented in `README.md`), because the plugin stores foreign keys to tour records and depends on User Tours being enabled.
- [ ] Stop exposing raw exception messages to end users in `review.php`; show stable language-string errors instead and keep technical detail in `debugging()`.
- [ ] Review all `@copyright` headers and replace `Moodle HQ` if this plugin is not actually authored/released by Moodle HQ.
- [ ] Decide whether the plugin is still intentionally `MATURITY_ALPHA`; if aiming for directory submission, update maturity/release metadata only when the remaining blockers are resolved.
- [ ] Add submission-facing documentation in English and include repository URL, bug tracker URL, documentation URL, and screenshots expected by the Moodle plugin checklist.
- [ ] Run a release-readiness pass with developer debugging enabled and verify the plugin on both MySQL and PostgreSQL before calling it submission-ready.
- [ ] Make sure the final distributable ZIP does not include plugin-local `.git` metadata.