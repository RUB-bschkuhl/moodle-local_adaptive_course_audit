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
 * German language strings for the Adaptive course audit local plugin.
 *
 * @package     local_adaptive_course_audit
 * @category    string
 * @copyright   2025 Moodle HQ
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Adaptives Kurs-Audit';
$string['reviewcoursenode'] = 'Kurs prüfen';
$string['reviewcoursenode_resume'] = 'Letzte Prüfung fortsetzen';
$string['reviewcourseheading'] = 'Adaptives Kurs-Audit';
$string['reviewcourseintro'] = 'Bereit, „{$a}“ zu verbessern? Klicken Sie auf die Schaltfläche unten – wir zeigen Ihnen, was gut läuft und wo noch Potenzial steckt.</br>';
$string['reviewcoursedescription'] = 'Analysiert Ihren gesamten Kurs und erstellt eine geführte Tour mit Vorschlägen. <b>Tipp:</b> Starten Sie zuerst mit einem einzelnen Abschnitt.';
$string['startreview'] = 'Prüfung starten';
$string['startreviewhelp'] = 'Wählen Sie einen Eintrag aus und starten Sie die Prüfung, um Tipps direkt auf Ihrer Kursseite zu sehen. Tipp: Starten Sie mit einer Abschnittsprüfung, um den Einstieg überschaubar zu halten.';
$string['reviewcoursematerialsintro'] = 'Die Hinweise in der Tour basieren auf diesen Materialien (aus MIau.nrw):';
$string['reviewcoursematerial_leitfaden'] = 'Leitfaden Adaptive Lehre (PDF)';
$string['reviewcoursematerial_miau'] = 'Adaptive Kursgestaltung in Moodle (PDF)';
$string['startreviewerror'] = 'Die adaptive Prüfung kann derzeit nicht gestartet werden. Bitte versuchen Sie es später erneut.';
$string['startreviewpermission'] = 'Sie haben keine Berechtigung, in diesem Kurs eine Prüfung zu starten.';
$string['loop_quiz_unlock_followups_summary'] = 'Wir schauen, ob Inhalte in einen Test münden, der die nächsten Schritte freischaltet – so zeigen Lernende ihr Wissen, bevor es weitergeht.';
$string['tourname'] = 'Kurstipps für {$a}';
$string['tourdescription'] = 'Ein kurzer Rundgang mit Vorschlägen für {$a}.';
$string['tourintro_default_title'] = 'So nutzen Sie diese Tour';
$string['tourintro_default_content'] = '<p>Diese Tour führt Sie Schritt für Schritt. Setzen Sie die Empfehlung um und gehen Sie dann zum nächsten Schritt.</p>';
$string['tourintro_audit_title'] = 'So nutzen Sie die Kurs-Audit-Tour';
$string['tourintro_audit_content'] = '<p>Die Kurs-Audit-Tour zeigt Verbesserungspotenziale direkt in Ihrem Kurs.</p><ul><li><strong>Es wird nichts automatisch geändert</strong> – Sie entscheiden, was Sie umsetzen.</li><li>Setzen Sie die Empfehlung (oft im Bearbeitungsmodus) um und gehen Sie dann weiter.</li><li>Wenn ein Schritt eine Schaltfläche anbietet (z. B. „Zeigen Sie mir wie …“), startet eine kurze geführte Hilfe für genau diese Einstellung.</li></ul>';
$string['tourintro_scenario_title'] = 'So nutzen Sie die Szenario-Tour';
$string['tourintro_scenario_content'] = '<p>Die Szenario-Tour ist eine praktische Checkliste für den Aufbau eines adaptiven Kursdesigns.</p><ul><li><strong>Es wird nichts automatisch geändert</strong> – nutzen Sie die Schritte als Anleitung beim Bearbeiten Ihres Kurses.</li><li>Schalten Sie den Bearbeitungsmodus ein, damit Sie Änderungen direkt umsetzen können.</li><li>Denken Sie in Etappen: erst Struktur, dann Aktivitäten, dann Bedingungen/Feedback.</li></ul>';
$string['tourintro_teach_title'] = 'So nutzen Sie die geführte Hilfe';
$string['tourintro_teach_content'] = '<p>Diese kurze Tour konzentriert sich auf eine konkrete Einstellung oder Aktion (z. B. Test-Verhalten, Feedback, Zugriffsbeschränkungen).</p><ul><li>Nehmen Sie die Änderung auf dieser Seite vor und gehen Sie dann zum nächsten Schritt.</li><li><strong>Es wird nichts automatisch geändert</strong> – die Tour erklärt nur, was zu tun ist.</li><li>Sie können die Tour jederzeit schließen und später fortsetzen.</li></ul>';
$string['reviewtableheading'] = 'Was möchten Sie prüfen?';
$string['table_heading'] = 'Kurs-Audit Touren';
$string['reviewcoltitle'] = 'Umfang';
$string['reviewcoldescription'] = 'Was wir prüfen';
$string['reviewcolaction'] = '';
$string['reviewbadge_extensive'] = 'Umfangreich';
$string['teachquiz_row_title'] = 'Test: {$a}';
$string['teachquiz_row_description'] = 'Eine kurze Tour durch die wichtigsten Test-Einstellungen für adaptive Lehre (Verhalten, Versuche, Feedback, Überprüfungsoptionen, Zeitplanung, Sicherheit).';
$string['teachquiz_guidedhelp_button'] = 'Geführte Hilfe';
$string['touraction_add_quiz'] = 'Test hier hinzufügen';
$string['touraction_edit_quiz_settings'] = 'Einstellungen von „{$a}“ bearbeiten';
$string['startteacherror'] = 'Die geführte Hilfe kann gerade nicht gestartet werden. Bitte versuchen Sie es später erneut.';
$string['teachtourname'] = 'Geführte Hilfe: {$a}';
$string['teachtourdescription'] = 'Kurze, praktische Schritte zu Test-Einstellungen in {$a->course}.';
$string['actiontourname'] = 'Geführte Hilfe: {$a->action}';
$string['actiontourdescription'] = 'Kurze Schritte, um diese Aktion in {$a->course} abzuschließen.';
$string['actiontour_loop_quiz_unlock_followups_addquiz_step_name_title'] = 'Test benennen';
$string['actiontour_loop_quiz_unlock_followups_addquiz_step_name_body'] = '<p>Geben Sie dem Test einen klaren Namen, damit er in diesem Abschnitt auffällt.</p><ul><li>Nutzen Sie ein Verb („Prüfen“, „Üben“, „Selbsttest“).</li><li>Halten Sie ihn kurz, damit er in der Abschnittsansicht gut lesbar ist.</li></ul>';
$string['actiontour_loop_quiz_unlock_followups_addquiz_step_completion_title'] = 'Abschluss aktivieren';
$string['actiontour_loop_quiz_unlock_followups_addquiz_step_completion_body'] = '<p>Schalten Sie den <strong>Aktivitätsabschluss</strong> ein, damit Folgeaktivitäten darauf aufbauen können.</p><p><strong>Ziel:</strong> Moodle kann die nächsten Schritte zuverlässig freischalten.</p>';
$string['actiontour_loop_quiz_unlock_followups_addquiz_step_access_title'] = 'Voraussetzung hinzufügen';
$string['actiontour_loop_quiz_unlock_followups_addquiz_step_access_body'] = '<p>Nutzen Sie <strong>Zugriffsbeschränkungen</strong>, um den Test hinter die vorbereitende Aktivität zu legen.</p><p>So verhindern Sie, dass Lernende direkt zum Test springen.</p>';
$string['actiontour_loop_quiz_unlock_followups_editquiz_step_access_title'] = 'Test absichern';
$string['actiontour_loop_quiz_unlock_followups_editquiz_step_access_body'] = '<p>Fügen Sie unter <strong>Zugriffsbeschränkungen</strong> eine Abschlussbedingung hinzu, damit zuerst die Vorbereitung erledigt wird.</p><p><strong>Tipp:</strong> Abschlussbedingungen sind meist robuster als Datumsbedingungen.</p>';
$string['actiontour_loop_quiz_unlock_followups_editquiz_step_completion_title'] = 'Abschluss prüfen';
$string['actiontour_loop_quiz_unlock_followups_editquiz_step_completion_body'] = '<p>Stellen Sie sicher, dass der Abschluss automatisch gesetzt wird, damit Folgeaktivitäten freigeschaltet werden.</p><p><strong>Typisch:</strong> Abschluss beim Abgeben / bei Bestehen.</p>';
$string['reviewtypeadaptive'] = 'Ganzer Kurs';
$string['reviewtypesection'] = 'Abschnitt „{$a}“';
$string['reviewsectiondescription'] = 'Nur „{$a}“ prüfen.';
$string['reviewcourseerror'] = 'Beim Laden der Vorschau ist etwas schiefgelaufen. Bitte erneut versuchen.';
$string['startsectionreview'] = 'Abschnitt prüfen';
$string['privacy:metadata'] = 'Dieses Plugin speichert keine personenbezogenen Daten.';
$string['settings:description'] = 'Weitere Optionen erscheinen hier, sobald das Plugin wächst.';
$string['adaptive_course_audit:view'] = 'Kursprüfungsseite anzeigen';

// Tasks.
$string['task_cleanup_tours'] = 'Veraltete Touren des adaptiven Kurs-Audits bereinigen';

// Quiz unlock follow-ups rule strings.
$string['rule_loop_quiz_unlock_followups_name'] = 'Erst lernen, dann nachweisen';
$string['rule_loop_quiz_unlock_followups_headline_min_items'] = 'Mehr Aktivitäten nötig';
$string['rule_loop_quiz_unlock_followups_headline_missing_quiz'] = 'Noch kein Test';
$string['rule_loop_quiz_unlock_followups_headline_missing_kb'] = 'Erst Inhalte anlegen';
$string['rule_loop_quiz_unlock_followups_headline_quiz_no_precondition'] = 'Test braucht Voraussetzung';
$string['rule_loop_quiz_unlock_followups_headline_no_followups'] = 'Nichts wird freigeschaltet';
$string['rule_loop_quiz_unlock_followups_headline_success'] = 'Sieht gut aus!';
$string['rule_loop_quiz_unlock_followups_description'] = 'Erst Inhalte, dann ein Test, der die nächsten Schritte freischaltet.';
$string['rule_loop_quiz_unlock_followups_min_items'] = 'Dieser Abschnitt braucht mindestens {$a} Aktivitäten für eine sinnvolle Abfolge.';
$string['rule_loop_quiz_unlock_followups_missing_quiz'] = 'Wir empfehlen, einen Test hinzuzufügen, damit Lernende zeigen können, was sie gelernt haben.';
$string['rule_loop_quiz_unlock_followups_missing_kb'] = 'Es kann hilfreich sein, vor dem Test Inhalte anzubieten (z. B. Seite, Buch oder Link).';
$string['rule_loop_quiz_unlock_followups_quiz_no_precondition'] = 'Wir empfehlen, den Test an vorherige Arbeit zu knüpfen, damit niemand vorspringt.';
$string['rule_loop_quiz_unlock_followups_no_followups'] = 'Aktuell hängt noch nichts vom Test ab. Es kann hilfreich sein, Folgeaktivitäten über den Abschluss des Tests freizuschalten.';
$string['rule_loop_quiz_unlock_followups_additional_followups'] = 'Sie könnten eine weitere Aktivität hinzufügen, die von „{$a->activity}“ abhängt.';
$string['rule_loop_quiz_unlock_followups_followup_list'] = '{$a->count} Aktivitäten werden nach dem Test freigeschaltet: {$a->items}.';
$string['rule_loop_quiz_unlock_followups_success'] = 'Prima! Inhalte führen in einen Test, der die nächsten Schritte freischaltet.';
$string['rule_loop_quiz_unlock_followups_rationale'] = '<h5>Warum das wichtig ist</h5><p>Ein minimal adaptiver Lernpfad folgt oft einem einfachen Muster:</p><ul><li>Wissensinhalt</li><li>Kompetenzabfrage (Test)</li><li>passende nächste Schritte</li></ul><p>Das Freischalten von Folgeaktivitäten über Voraussetzungen und Abschlussverfolgung hilft Lernenden, ihr Wissen zu zeigen, bevor sie weitergehen (vgl. Leitfaden adaptive Lehre, 2025; MIau.nrw).</p>';

// Verzweigung nach Testergebnis (Note/Punkte) – Strings.
$string['rule_loop_branch_by_grade_name'] = 'Nach Testergebnis verzweigen';
$string['rule_loop_branch_by_grade_description'] = 'Nutzen Sie Noten-/Punktegrenzen, um unterschiedliche Folgepfade freizuschalten (Förderpfad vs. Vertiefung).';
$string['rule_loop_branch_by_grade_headline_missing_gradeitem'] = 'Notenbasierte Verzweigung nicht verfügbar';
$string['rule_loop_branch_by_grade_missing_gradeitem'] = 'Für den Test „{$a}“ konnte kein Eintrag im Bewertungsbuch gefunden werden. Prüfen Sie, ob der Test eine Maximalpunktzahl hat und im Bewertungsbuch geführt wird.';
$string['rule_loop_branch_by_grade_headline_missing'] = 'Noch keine notenbasierten Pfade';
$string['rule_loop_branch_by_grade_missing'] = 'Wir empfehlen, bei Folgeaktivitäten unter „Zugriffsbeschränkungen“ eine Bedingung „Bewertung“ zu nutzen, damit Lernende je nach Ergebnis in „{$a}“ unterschiedliche nächste Schritte sehen.';
$string['rule_loop_branch_by_grade_headline_success'] = 'Notenbasierte Verzweigung gefunden';
$string['rule_loop_branch_by_grade_found'] = 'Folgeaktivitäten sind über das Ergebnis in „{$a->quiz}“ freigeschaltet: {$a->branches}.';
$string['rule_loop_branch_by_grade_suggest_two_paths'] = 'Tipp: Definieren Sie sowohl einen Förderpfad (Maximalwert) als auch einen Vertiefungspfad (Minimalwert) für „{$a}“.';
$string['rule_loop_branch_by_grade_rationale'] = '<h5>Warum das wichtig ist</h5><p>Verzweigungen nach Testergebnis sind ein praxisnaher Weg, auf heterogene Vorkenntnisse zu reagieren.</p><p>Über Zugriffsbeschränkungen können Lernende je nach Leistung zu Förder- oder Vertiefungsaktivitäten geleitet werden (vgl. Leitfaden adaptive Lehre, 2025; MIau.nrw).</p>';
$string['rule_loop_branch_by_grade_range_any'] = 'beliebiges Ergebnis';
$string['rule_loop_branch_by_grade_range_min'] = '≥ {$a}%';
$string['rule_loop_branch_by_grade_range_max'] = '< {$a}%';
$string['rule_loop_branch_by_grade_range_between'] = '{$a->min}%–{$a->max}%';
$string['touraction_add_grade_gate'] = 'Noten-Bedingung zu „{$a}“ hinzufügen';
$string['actiontour_gradegate_step_access_title'] = 'Bewertungsbedingung hinzufügen';
$string['actiontour_gradegate_step_access_body'] = '<p>Öffnen Sie <strong>Zugriffsbeschränkungen</strong> und fügen Sie eine Bedingung <strong>Bewertung</strong> (Min/Max) hinzu.</p><p><strong>Ergebnis:</strong> Lernende sehen je nach Punktzahl unterschiedliche Folgepfade.</p>';

// Quiz-Feedback-Qualität – Strings.
$string['rule_loop_quiz_feedback_name'] = 'Handlungsleitendes Quiz-Feedback';
$string['rule_loop_quiz_feedback_description'] = 'Prüft, ob der Test handlungsleitendes Gesamtfeedback gibt und Übungs-Schleifen (mehrere Versuche) unterstützt.';
$string['rule_loop_quiz_feedback_headline_success'] = 'Feedback unterstützt adaptive nächste Schritte';
$string['rule_loop_quiz_feedback_headline_needs_work'] = 'Feedback kann adaptiver werden';
$string['rule_loop_quiz_feedback_missing'] = 'Wir empfehlen, für „{$a}“ „Gesamtfeedback“ zu hinterlegen, damit Lernende nach der Abgabe konkrete Hinweise erhalten.';
$string['rule_loop_quiz_feedback_found'] = 'Gesamtfeedback ist für „{$a}“ vorhanden.';
$string['rule_loop_quiz_feedback_missing_links'] = 'Es kann hilfreich sein, das Feedback handlungsleitend zu gestalten (z. B. mit Links auf passende Förder-/Vertiefungsressourcen für „{$a}“).';
$string['rule_loop_quiz_feedback_suggest_attempts'] = 'Tipp: Erlauben Sie mehr als einen Versuch für „{$a}“, damit Lernende nach einem Förderpfad erneut antreten können.';
$string['rule_loop_quiz_feedback_rationale'] = '<h5>Warum das wichtig ist</h5><p>Feedback in Tests ist ein niedrigschwelliger Einstieg in adaptive Lehre.</p><p><strong>Handlungsleitendes Feedback</strong> (idealerweise mit Links) und Wiederholungsversuche unterstützen Selbststeuerung und helfen, Lücken zu schließen, bevor es weitergeht (vgl. Leitfaden adaptive Lehre, 2025; MIau.nrw).</p>';
$string['touraction_edit_quiz_feedback'] = 'Feedback in „{$a}“ verbessern';
$string['actiontour_quizfeedback_step_overallfeedback_title'] = 'Gesamtfeedback ergänzen';
$string['actiontour_quizfeedback_step_overallfeedback_body'] = '<p>Nutzen Sie <strong>Gesamtfeedback</strong>-Bänder, um je nach Ergebnis unterschiedliche Hinweise zu geben.</p><p><strong>Handlungsleitend:</strong> Verlinken Sie auf passende Folgeaktivitäten (Förderpfad vs. Vertiefung).</p>';
$string['actiontour_quizfeedback_step_attempts_title'] = 'Übungs-Schleifen ermöglichen';
$string['actiontour_quizfeedback_step_attempts_body'] = '<p>Setzen Sie <strong>Versuche erlaubt</strong> auf mehr als 1, wenn Lernende nach einem Förderpfad erneut üben sollen.</p><p>So wird der Test zur Übungs-Schleife mit Feedback.</p>';

// Test: Überprüfungsoptionen (was Lernende sehen, wann).
$string['actiontour_quizreviewoptions_step_reviewoptions_title'] = 'Überprüfungsoptionen einstellen';
$string['actiontour_quizreviewoptions_step_reviewoptions_body'] = '<p>Öffnen Sie <strong>Überprüfungsoptionen</strong> und legen Sie fest, was Lernende <em>während</em>, <em>direkt nach</em> und <em>später</em> sehen.</p><p><strong>Tipp:</strong> Geben Sie die passende Information zum passenden Zeitpunkt frei (z. B. Gesamtfeedback, richtige Antworten, allgemeines Feedback).</p>';

// Test: Bewertung (wie Versuche bewertet werden / Note im Kurs).
$string['actiontour_quizgrading_step_grade_title'] = 'Bewertung prüfen';
$string['actiontour_quizgrading_step_grade_body'] = '<p>Unter <strong>Bewertung</strong> wählen Sie, wie mehrere Versuche bewertet werden (beste/mittlere/erste/letzte Bewertung) und ob die Maximalpunktzahl sinnvoll gesetzt ist.</p><p><strong>Wirkung:</strong> Das Testergebnis passt zu Ihren Lernzielen und ggf. zu notenbasierten Voraussetzungen.</p>';

// Test: Abschlussverfolgung (robuste Folgepfade und Berichte).
$string['actiontour_quizcompletion_step_completion_title'] = 'Abschluss für Folgeaktivitäten nutzen';
$string['actiontour_quizcompletion_step_completion_body'] = '<p>Unter <strong>Aktivitätsabschluss</strong> legen Sie Bedingungen fest, wann der Test als abgeschlossen gilt (z. B. Abgabe und/oder Bestehen).</p><p><strong>Warum:</strong> So können Sie Folgeaktivitäten zuverlässig freischalten, Fortschritt in Berichten nachvollziehen und robuste Lernpfade bauen.</p>';

// Test: Zeitplanung und Sicherheit (Zugriff/Regeln).
$string['actiontour_quiztimingsecurity_step_timing_title'] = 'Zeitplanung festlegen';
$string['actiontour_quiztimingsecurity_step_timing_body'] = '<p>Nutzen Sie <strong>Zeitplanung</strong>, um Verfügbarkeit (Öffnen/Schließen) zu steuern und bei Bedarf ein Zeitlimit zu setzen.</p><p><strong>Tipp:</strong> Stimmen Sie die Zeitplanung auf den Kursrhythmus ab, damit Lernende gut planen können.</p>';
$string['actiontour_quiztimingsecurity_step_security_title'] = 'Sicherheitsoptionen prüfen';
$string['actiontour_quiztimingsecurity_step_security_body'] = '<p>Unter <strong>Sicherheit</strong> legen Sie fest, ob zusätzliche Einschränkungen nötig sind (z. B. Passwort, Subnetz, Browser-Sicherheit).</p><p><strong>Tipp:</strong> Halten Sie es einfach, solange es kein klares Prüfungsszenario gibt.</p>';

// Diagnose-Checkpoint (Umfrage/Choice/Feedback) – Strings.
$string['rule_loop_diagnostic_checkpoint_name'] = 'Diagnose-Checkpoint';
$string['rule_loop_diagnostic_checkpoint_description'] = 'Nutzen Sie eine kurze Diagnose (Choice/Feedback/Survey) plus klare Wegweiser, damit Lernende ihren Lernweg besser steuern können.';
$string['rule_loop_diagnostic_checkpoint_rationale'] = '<h5>Warum das wichtig ist</h5><p>Kurze Diagnosen (Bedarfe/Selbsteinschätzung) liefern ein Rückmeldesignal für Lernende und Lehrende.</p><p>Mit klaren Wegweisern und optionalen Voraussetzungen unterstützen sie Selbststeuerung und helfen Lernenden, den passenden Pfad zu wählen (vgl. Leitfaden adaptive Lehre, 2025; MIau.nrw).</p>';
$string['rule_loop_diagnostic_checkpoint_headline_missing'] = 'Kein Diagnose-Checkpoint';
$string['rule_loop_diagnostic_checkpoint_missing'] = 'Wir empfehlen, eine kurze Diagnose-Aktivität (z. B. Feedback) einzusetzen, um Bedarfe oder Selbsteinschätzung am Abschnittsanfang zu erfassen.';
$string['rule_loop_diagnostic_checkpoint_found'] = 'Diagnose-Checkpoint gefunden: „{$a}“.';
$string['rule_loop_diagnostic_checkpoint_missing_signposting'] = 'Es kann hilfreich sein, direkt danach Wegweiser zu ergänzen (z. B. Textfeld/Seite), die erklären, welchen Pfad Lernende als Nächstes wählen sollen.';
$string['rule_loop_diagnostic_checkpoint_suggest_gate'] = 'Optional: Es kann hilfreich sein, Folgeaktivitäten erst nach Abschluss von „{$a}“ freizuschalten, damit niemand den Checkpoint überspringt.';
$string['rule_loop_diagnostic_checkpoint_gated_followups'] = '{$a} Aktivitäten werden erst nach Abschluss des Checkpoints freigeschaltet.';
$string['rule_loop_diagnostic_checkpoint_headline_success'] = 'Checkpoint + Wegweiser vorhanden';
$string['rule_loop_diagnostic_checkpoint_headline_needs_work'] = 'Checkpoint braucht klarere Wegweiser';
$string['touraction_add_diagnostic'] = 'Diagnose-Aktivität hinzufügen';
$string['touraction_edit_diagnostic'] = '„{$a}“ bearbeiten';
$string['actiontour_diagnostic_step_name_title'] = 'Checkpoint benennen';
$string['actiontour_diagnostic_step_name_body'] = 'Wählen Sie einen klaren Namen wie „Kurz-Selbstcheck“ oder „Bedarfsabfrage“, damit Lernende den Zweck verstehen.';
$string['actiontour_diagnostic_step_access_title'] = 'Optional: Folgeaktivitäten absichern';
$string['actiontour_diagnostic_step_access_body'] = '<p>Wenn gewünscht: Nutzen Sie <strong>Zugriffsbeschränkungen</strong> (Abschluss), damit Lernende den Checkpoint vor dem Weiterarbeiten abschließen.</p><p><strong>Tipp:</strong> Optional lassen, wenn Überspringen nicht kritisch ist.</p>';

// Quiz-Verhalten (adaptiv/interaktiv) – Strings.
$string['rule_loop_quiz_adaptive_behaviour_name'] = 'Adaptives Quiz-Verhalten';
$string['rule_loop_quiz_adaptive_behaviour_description'] = 'Prüft, ob Tests adaptive/interaktive Fragemodi nutzen (mehrere Versuche mit unmittelbarem Feedback).';
$string['rule_loop_quiz_adaptive_behaviour_headline_success'] = 'Adaptives Quiz-Verhalten wird genutzt';
$string['rule_loop_quiz_adaptive_behaviour_headline_needs_work'] = 'Quiz-Verhalten kann adaptiver werden';
$string['rule_loop_quiz_adaptive_behaviour_rationale'] = '<h5>Warum das wichtig ist</h5><p>Adaptive Lehre lebt von kontinuierlicher Rückkopplung und kurzen Kompetenzabfragen.</p><p>Adaptive/interaktive Fragemodi können Tests in eine Übungs-Schleife mit unmittelbarem Feedback verwandeln und so Selbstkorrektur vor dem Weiterlernen unterstützen (vgl. Leitfaden adaptive Lehre, 2025; MIau.nrw).</p>';
$string['rule_loop_quiz_adaptive_behaviour_found_quiz'] = 'Test „{$a->quiz}“ nutzt Fragemodus: {$a->behaviour}.';
$string['rule_loop_quiz_adaptive_behaviour_behaviour_unknown'] = 'unbekannt';
$string['rule_loop_quiz_adaptive_behaviour_missing'] = 'Tipp: Es kann hilfreich sein, „{$a}“ auf einen adaptiven/interaktiven Fragemodus umzustellen, damit Lernende mit unmittelbarem Feedback erneut versuchen können.';
$string['rule_loop_quiz_adaptive_behaviour_success'] = 'Gut: Mindestens ein Test unterstützt hier Übungs-Schleifen ({$a}).';
$string['touraction_edit_quiz_behaviour'] = 'Verhalten in „{$a}“ anpassen';
$string['actiontour_quizbehaviour_step_behaviour_title'] = 'Adaptiven Fragemodus aktivieren';
$string['actiontour_quizbehaviour_step_behaviour_body'] = '<p>Stellen Sie <strong>Frageverhalten</strong> auf einen adaptiven/interaktiven Modus (z. B. Adaptiver Modus oder Interaktiv mit mehreren Versuchen).</p><p><strong>Wirkung:</strong> Lernende erhalten unmittelbares Feedback und können erneut versuchen.</p>';

// Lektion-Verzweigungen – Strings.
$string['rule_loop_lesson_branching_name'] = 'Lektion-Verzweigungen';
$string['rule_loop_lesson_branching_description'] = 'Prüft, ob Lektionen je nach Antwort zu unterschiedlichen Seiten verzweigen (Sprünge/„jumpto“).';
$string['rule_loop_lesson_branching_headline_success'] = 'Lektions-Verzweigungen gefunden';
$string['rule_loop_lesson_branching_headline_needs_work'] = 'Lektion könnte adaptiver verzweigen';
$string['rule_loop_lesson_branching_rationale'] = '<h5>Warum das wichtig ist</h5><p>Verzweigte Lernpfade ermöglichen unterschiedliche nächste Schritte je nach Antwort (z. B. Förderung vs. Vertiefung).</p><p>Die Moodle-Aktivität „Lektion“ unterstützt komplexe Lernpfade durch bedingte Navigation (vgl. Leitfaden adaptive Lehre, 2025; MIau.nrw).</p>';
$string['rule_loop_lesson_branching_found'] = 'Lektion „{$a}“ enthält Verzweigungen zwischen Seiten.';
$string['rule_loop_lesson_branching_missing'] = 'Lektion „{$a}“ wirkt überwiegend linear. Erwägen Sie bedingte Sprünge, um Lernende je nach Antwort zu unterschiedlichen Folgeseiten zu leiten.';
$string['rule_loop_lesson_branching_lesson_no_answers'] = 'Lektion „{$a}“ hat noch keine Antwort-Datensätze (sie ist ggf. noch leer).';
$string['touraction_open_lesson_editor'] = 'Lektion-Editor für „{$a}“ öffnen';

// Zufallsfragen (Aufgabenpools) – Strings.
$string['rule_loop_quiz_random_questions_name'] = 'Zufallsfragen (Aufgabenpool)';
$string['rule_loop_quiz_random_questions_description'] = 'Prüft, ob Tests Zufallsfragen aus einem Aufgabenpool nutzen, um Versuche zu variieren.';
$string['rule_loop_quiz_random_questions_headline_success'] = 'Zufallsfragen gefunden';
$string['rule_loop_quiz_random_questions_headline_needs_work'] = 'Noch keine Zufallsfragen';
$string['rule_loop_quiz_random_questions_rationale'] = '<h5>Warum das wichtig ist</h5><p>Zufallsbasierte Aufgabenpools machen Wiederholungsversuche sinnvoller und unterstützen Übungs-Schleifen.</p><p>Sie sind zudem eine Gestaltungsoption für adaptive Testszenarien (vgl. Leitfaden adaptive Lehre, 2025: „zufallsbasierte Aufgabenpools“).</p>';
$string['rule_loop_quiz_random_questions_found'] = 'Test „{$a->quiz}“ enthält {$a->count} Zufallsfragen-Slots.';
$string['rule_loop_quiz_random_questions_missing'] = 'Test „{$a}“ nutzt noch keine Zufallsfragen. Tipp: Es kann hilfreich sein, Zufallsfragen aus einer kategorisierten Fragensammlung zu ergänzen.';
$string['rule_loop_quiz_random_questions_empty'] = 'Test „{$a}“ hat noch keine Fragen/Slots (er ist ggf. noch leer).';
$string['touraction_open_quiz_edit'] = 'Fragen in „{$a}“ bearbeiten';

// H5P (Vorhanden?) – Strings.
$string['rule_loop_h5p_interactive_name'] = 'Interaktives H5P (Vorhanden?)';
$string['rule_loop_h5p_interactive_description'] = 'Prüft, ob es im Abschnitt eine H5P-Aktivität gibt (nur Vorhandenheits-Check).';
$string['rule_loop_h5p_interactive_headline_success'] = 'H5P-Aktivität gefunden';
$string['rule_loop_h5p_interactive_headline_needs_work'] = 'Noch keine H5P-Aktivität';
$string['rule_loop_h5p_interactive_rationale'] = '<h5>Warum das wichtig ist</h5><p>H5P kann Entscheidungspunkte und visuelle Verzweigungen (z. B. „Branching Scenario“) unterstützen.</p><p>So können Lernende Inhalte passend zu ihren Bedarfen durchlaufen (vgl. Leitfaden adaptive Lehre, 2025; MIau.nrw).</p>';
$string['rule_loop_h5p_interactive_found'] = 'H5P-Aktivität(en) im Abschnitt gefunden: {$a}.';
$string['rule_loop_h5p_interactive_missing'] = 'Keine H5P-Aktivität im Abschnitt gefunden. Wenn Sie einen visuellen, verzweigten Lernpfad möchten, kann H5P ein niedrigschwelliger Einstieg sein.';
$string['touraction_add_h5p'] = 'H5P-Aktivität hinzufügen';
$string['actiontour_h5p_step_name_title'] = 'H5P-Aktivität benennen';
$string['actiontour_h5p_step_name_body'] = '<p>Wählen Sie einen klaren Namen, damit Lernende wissen, was sie erwartet.</p><ul><li>Beispiel: <strong>Branching Scenario</strong></li><li>Beispiel: <strong>Wähle deinen Pfad</strong></li></ul>';

// Szenario-Touren.
$string['scenario_heading'] = 'Szenario-Touren: Schritt für Schritt zum adaptiven Kurs';
$string['scenario_description'] = 'Wählen Sie ein Szenario, das zu Ihrem Kurs passt. Die Tour begleitet Sie durch die wichtigsten Schritte.';
$string['scenario_description_html'] = '<p>Wählen Sie ein Szenario, das zu Ihrem Kurs passt. Die Tour begleitet Sie durch die wichtigsten Schritte. <strong>Es wird nichts automatisch geändert</strong> – setzen Sie die Hinweise Schritt für Schritt um.</p><ul><li><strong>Pfad 1 (Minimalist):</strong> klein anfangen und ausgewählte Teile adaptiv gestalten.</li><li><strong>Pfad 2 (Sequenziell):</strong> Themen bauen aufeinander auf; Sektionen werden nacheinander freigeschaltet.</li><li><strong>Pfad 3 (Kompass):</strong> freie Themenreihenfolge mit einem „Kompass“-Quiz zur Orientierung.</li></ul><p><strong>Tipp:</strong> Schalten Sie im Kurs den Bearbeitungsmodus ein, damit Sie Änderungen direkt während der Tour umsetzen können.</p>';
$string['scenario_1_button'] = 'Minimalist: Einzelne Teile adaptiv';
$string['scenario_1_title'] = 'Pfad 1 – Minimalist adaptiver Kursbau';
$string['scenario_2_button'] = 'Sequenziell: Themen aufeinander aufbauend';
$string['scenario_2_title'] = 'Pfad 2 – Einfache Kursgestaltung, Themen aufeinander aufbauend';
$string['scenario_3_button'] = 'Kompass: Freie Themenreihenfolge';
$string['scenario_3_title'] = 'Pfad 3 – Kompassmodell, Themen nicht aufeinander aufbauend';
$string['startscenarioerror'] = 'Die Szenario-Tour kann gerade nicht gestartet werden. Bitte versuchen Sie es später erneut.';
$string['scenario_tourname'] = 'Szenario-Tour: {$a}';
$string['scenario_tourdescription'] = 'Schritt-für-Schritt-Anleitung für adaptive Kursgestaltung.';

// Pfad 1 – Minimalist.
$string['scenario_1_step1_title'] = 'Ziele und Zielgruppe reflektieren';
$string['scenario_1_step1_content'] = '<p>Machen Sie sich die <strong>Lernziele</strong>, <strong>Zielgruppe</strong> und <strong>Lernvoraussetzungen</strong> Ihres Kurses bewusst, bevor Sie mit der adaptiven Gestaltung beginnen.</p><p>Fragen Sie sich: Was sollen Lernende am Ende können? Welche Vorkenntnisse bringen sie mit?</p><div class="local-aca-tour-rationale"><h5>Warum das wichtig ist</h5><p>Adaptive Lehre setzt voraus, dass Lehrende diagnostische Kompetenz entwickeln, um Bedarfe einzuschätzen. Reflexion über Ziele und Zielgruppe ist der Startpunkt für gezielte Adaption (vgl. Leitfaden adaptive Lehre, 2025; MIau.nrw).</p></div>';
$string['scenario_1_step2_title'] = 'Umfang festlegen: Einzelne Teile';
$string['scenario_1_step2_content'] = '<p>Entscheiden Sie sich zunächst für <strong>einzelne Sektionen</strong>, die Sie adaptiv gestalten möchten. Nicht der ganze Kurs muss auf einmal umgebaut werden.</p><p>Wählen Sie einen Abschnitt, der sich besonders eignet (z.B. mit heterogenen Vorkenntnissen).</p><div class="local-aca-tour-rationale"><h5>Warum das wichtig ist</h5><p>Klein anfangen: Adaptive Lehre kann schon innerhalb einer einzelnen Kurssektion beginnen, ohne den ganzen Kurs umzugestalten (Handlungsempfehlung 1, Leitfaden adaptive Lehre, 2025).</p></div>';
$string['scenario_1_step3_title'] = '3-Element-Struktur sicherstellen';
$string['scenario_1_step3_content'] = '<p>Jede adaptive Sektion braucht mindestens:</p><ol><li><strong>Inhalt zum Wissensaufbau</strong> (Seite, Buch, Video, Link)</li><li><strong>Ein Quiz</strong> zur Wissensüberprüfung</li><li><strong>Einen optionalen Inhalt</strong> zur Vertiefung (wird bei Nicht-Bestehen freigeschaltet)</li></ol><div class="local-aca-tour-rationale"><h5>Warum das wichtig ist</h5><p>Die Grundstruktur mit 3 Elementen (Wissensinhalt → Kompetenzabfrage → optionaler Inhalt) ist das Basismuster für Lernpfade mit Verzweigung. Damit können Lernende je nach Ergebnis unterschiedliche nächste Schritte sehen (vgl. Leitfaden adaptive Lehre, 2025; MIau.nrw).</p></div>';
$string['scenario_1_step4_title'] = 'Adaptiven Loop einrichten';
$string['scenario_1_step4_content'] = '<p>Richten Sie folgende Abfolge ein:</p><ol><li><strong>Inhalt</strong> zum Wissensaufbau</li><li><strong>Quiz</strong> (Wissensüberprüfung) – mit Abschluss- und Voraussetzungsbedingung</li></ol><p><strong>Wenn bestanden:</strong> Die Sektion ist abgeschlossen.</p><p><strong>Wenn nicht bestanden:</strong></p><ul><li>Alternativer Inhalt wird freigeschaltet (über Zugriffsbeschränkung bei Nicht-Bestehen)</li><li>Erneute Bearbeitung des Quiz möglich (mehrere Versuche erlauben)</li></ul><div class="local-aca-tour-rationale"><h5>Warum das wichtig ist</h5><p>Handlungsleitendes Feedback und Wiederholungsversuche unterstützen Selbststeuerung und helfen Lernenden, Lücken zu schließen, bevor es weitergeht. Übungs-Schleifen sind ein niedrigschwelliger Einstieg in adaptive Lehre (vgl. Leitfaden adaptive Lehre, 2025; MIau.nrw).</p></div>';
$string['scenario_1_step5_title'] = 'Abschlussquiz als Lernzielkontrolle';
$string['scenario_1_step5_content'] = '<p>Fügen Sie ein <strong>Abschlussquiz</strong> hinzu, das die zentralen Lernziele prüft.</p><p><strong>Wenn bestanden:</strong> Kurs ist abgeschlossen – Gratifikation (z.B. Badge, Abschlussmeldung).</p><p><strong>Wenn nicht bestanden:</strong> Zurück zum adaptiven Loop (Schritt 4) – Lernende bearbeiten die Fördermaterialien erneut.</p><div class="local-aca-tour-rationale"><h5>Warum das wichtig ist</h5><p>Tests sind zentral für die Lernpfadgestaltung. Eine finale Kompetenzabfrage sichert, dass Lernende die Lernziele erreicht haben, und gibt gleichzeitig Lehrenden Rückmeldung über den Lernerfolg (vgl. Leitfaden adaptive Lehre, 2025; MIau.nrw).</p></div>';

// Pfad 2 – Sequenziell.
$string['scenario_2_step1_title'] = 'Ziele und Zielgruppe reflektieren';
$string['scenario_2_step1_content'] = '<p>Machen Sie sich die <strong>Lernziele</strong>, <strong>Zielgruppe</strong> und <strong>Lernvoraussetzungen</strong> Ihres Kurses bewusst, bevor Sie mit der adaptiven Gestaltung beginnen.</p><p>Fragen Sie sich: Was sollen Lernende am Ende können? Welche Vorkenntnisse bringen sie mit?</p><div class="local-aca-tour-rationale"><h5>Warum das wichtig ist</h5><p>Adaptive Lehre setzt voraus, dass Lehrende diagnostische Kompetenz entwickeln, um Bedarfe einzuschätzen. Reflexion über Ziele und Zielgruppe ist der Startpunkt für gezielte Adaption (vgl. Leitfaden adaptive Lehre, 2025; MIau.nrw).</p></div>';
$string['scenario_2_step2_title'] = 'Umfang: Ganzer Kurs, aufeinander aufbauend';
$string['scenario_2_step2_content'] = '<p>Sie möchten den <strong>ganzen Kurs</strong> adaptiv gestalten. Die Themen bauen aufeinander auf – jede Sektion muss abgeschlossen werden, bevor die nächste freigeschaltet wird.</p><p>Planen Sie die Reihenfolge der Themen so, dass Grundlagenwissen vor Vertiefung kommt.</p><div class="local-aca-tour-rationale"><h5>Warum das wichtig ist</h5><p>Lineare Lernpfade nutzen voraussetzungsbasierte Freischaltung: Inhalte werden erst sichtbar, wenn bestimmte Bedingungen erfüllt sind (Testergebnis, Aktivitätsabschluss). Dies entspricht dem linearen Lernpfad aus dem Leitfaden adaptive Lehre (vgl. Leitfaden adaptive Lehre, 2025; MIau.nrw).</p></div>';
$string['scenario_2_step3_title'] = 'Kurs in Sektionen aufteilen';
$string['scenario_2_step3_content'] = '<p>Unterteilen Sie die Themen in <strong>Moodle-Sektionen</strong>. Jede Sektion sollte die 3-Element-Struktur enthalten:</p><ol><li><strong>Inhalt</strong> zum Wissensaufbau</li><li><strong>Quiz</strong> zur Wissensüberprüfung</li><li><strong>Optionaler Inhalt</strong> zur Vertiefung</li></ol><div class="local-aca-tour-rationale"><h5>Warum das wichtig ist</h5><p>Beginnen Sie mit einfachen Lernpfaden: Starten Sie mit einem abgeschlossenen Lernpfad (z.B. Video + Quiz + Feedback), bevor Sie skalieren (Handlungsempfehlung 2, Leitfaden adaptive Lehre, 2025).</p></div>';
$string['scenario_2_step4_title'] = 'Sektionen sequenziell verknüpfen';
$string['scenario_2_step4_content'] = '<p>Richten Sie in jeder Sektion den adaptiven Loop ein (wie in Pfad 1):</p><ol><li><strong>Inhalt</strong> → <strong>Quiz</strong> → bestanden/nicht bestanden</li></ol><p><strong>Zusätzlich:</strong> Wenn Sektion n abgeschlossen wird, schaltet sich <strong>Sektion n+1</strong> frei.</p><p>Nutzen Sie dafür <strong>Voraussetzungen</strong> und <strong>Abschlussverfolgung</strong> auf Sektionsebene.</p><div class="local-aca-tour-rationale"><h5>Warum das wichtig ist</h5><p>Voraussetzungen steuern die Sichtbarkeit von Inhalten basierend auf bestimmten Bedingungen (Testergebnis, Aktivitätsabschluss). Die sequenzielle Freischaltung stellt sicher, dass Grundlagen sitzen, bevor Vertiefung stattfindet (vgl. Leitfaden adaptive Lehre, 2025; MIau.nrw).</p></div>';
$string['scenario_2_step5_title'] = 'Abschlussquiz als Lernzielkontrolle';
$string['scenario_2_step5_content'] = '<p>Fügen Sie ein <strong>Abschlussquiz</strong> hinzu, das die zentralen Lernziele prüft.</p><p><strong>Wenn bestanden:</strong> Kurs ist abgeschlossen – Gratifikation (z.B. Badge, Abschlussmeldung).</p><p><strong>Wenn nicht bestanden:</strong> Zurück zum adaptiven Loop – Lernende bearbeiten die Fördermaterialien erneut.</p><div class="local-aca-tour-rationale"><h5>Warum das wichtig ist</h5><p>Tests sind zentral für die Lernpfadgestaltung. Eine finale Kompetenzabfrage sichert, dass Lernende die Lernziele erreicht haben, und gibt gleichzeitig Lehrenden Rückmeldung über den Lernerfolg (vgl. Leitfaden adaptive Lehre, 2025; MIau.nrw).</p></div>';

// Pfad 3 – Kompass-Modell.
$string['scenario_3_step1_title'] = 'Ziele und Zielgruppe reflektieren';
$string['scenario_3_step1_content'] = '<p>Machen Sie sich die <strong>Lernziele</strong>, <strong>Zielgruppe</strong> und <strong>Lernvoraussetzungen</strong> Ihres Kurses bewusst, bevor Sie mit der adaptiven Gestaltung beginnen.</p><p>Fragen Sie sich: Was sollen Lernende am Ende können? Welche Vorkenntnisse bringen sie mit?</p><div class="local-aca-tour-rationale"><h5>Warum das wichtig ist</h5><p>Adaptive Lehre setzt voraus, dass Lehrende diagnostische Kompetenz entwickeln, um Bedarfe einzuschätzen. Reflexion über Ziele und Zielgruppe ist der Startpunkt für gezielte Adaption (vgl. Leitfaden adaptive Lehre, 2025; MIau.nrw).</p></div>';
$string['scenario_3_step2_title'] = 'Umfang: Ganzer Kurs, freie Reihenfolge';
$string['scenario_3_step2_content'] = '<p>Sie möchten den <strong>ganzen Kurs</strong> adaptiv gestalten. Die Themen bauen <strong>nicht</strong> aufeinander auf – Lernende können die Inhalte in eigener Reihenfolge bearbeiten.</p><p>Der Kurs funktioniert wie ein Baukasten: Ein Kompass-Quiz hilft bei der Orientierung.</p><div class="local-aca-tour-rationale"><h5>Warum das wichtig ist</h5><p>Lernpfade mit freier Erkundung ermöglichen es Lernenden, Inhalte in eigener Reihenfolge zu erkunden. Dies fördert Autonomie und Selbststeuerung – ein zentrales Ziel adaptiver Lehre (vgl. Leitfaden adaptive Lehre, 2025; MIau.nrw).</p></div>';
$string['scenario_3_step3_title'] = 'Orientierungsaktivität erstellen';
$string['scenario_3_step3_content'] = '<p>Erstellen Sie eine <strong>Aktivität</strong> (z.B. Textseite oder Textfeld) mit einer Beschreibung des Kursaufbaus und den Spielregeln:</p><ul><li>Wie ist der Kurs aufgebaut?</li><li>Was wird von den Lernenden erwartet?</li><li>Wie funktioniert der Kompass-Quiz?</li></ul><div class="local-aca-tour-rationale"><h5>Warum das wichtig ist</h5><p>Transparenz über Adaptivität schaffen: Machen Sie deutlich, welche Inhalte adaptiv sind und welche Rolle Empfehlungen spielen (Handlungsempfehlung 7, Leitfaden adaptive Lehre, 2025).</p></div>';
$string['scenario_3_step4_title'] = 'Quiz-Kompass erstellen';
$string['scenario_3_step4_content'] = '<p>Erstellen Sie ein <strong>Quiz</strong> (den „Kompass"), das Fragen zu <strong>allen Inhalten</strong> des Kurses behandelt.</p><ul><li>Das Quiz dient als <strong>Orientierungshilfe</strong>, nicht als Prüfung.</li><li>Nach Bearbeitung des Quiz schalten sich <strong>alle Sektionen</strong> mit ihren Aktivitäten frei.</li><li>Der Kompass ist Voraussetzung für die erste Aktivität jeder Sektion.</li></ul><div class="local-aca-tour-rationale"><h5>Warum das wichtig ist</h5><p>Der Fragenkompass ist ein zentrales Element für Lernpfade mit freier Erkundung: Ein Orientierungstest empfiehlt Lernpfade und hilft bei der Selbsteinschätzung (vgl. Leitfaden adaptive Lehre, 2025; MIau.nrw).</p></div>';
$string['scenario_3_step5_title'] = 'Feedback mit Inhaltsverweisen versehen';
$string['scenario_3_step5_content'] = '<p>Verlinken Sie in jeder Frage des Kompass-Quiz im <strong>Feedback</strong> (bei falscher und richtiger Antwort) den Inhalt, auf den sich die Frage bezieht.</p><ul><li><strong>Falsche Antwort:</strong> Verlinken Sie auf die Sektion/Aktivität, die das Thema behandelt.</li><li><strong>Richtige Antwort:</strong> Bestätigen Sie und verlinken Sie optional auf Vertiefungsmaterial.</li></ul><p><em>Hinweis: Dieser Schritt wird in einer späteren Version weiter ausgearbeitet.</em></p><div class="local-aca-tour-rationale"><h5>Warum das wichtig ist</h5><p>Handlungsleitendes Feedback mit gezielten Verlinkungen macht den Kompass zum Wegweiser: Lernende werden direkt zu den passenden Kursinhalten geleitet (vgl. Leitfaden adaptive Lehre, 2025; MIau.nrw).</p></div>';
