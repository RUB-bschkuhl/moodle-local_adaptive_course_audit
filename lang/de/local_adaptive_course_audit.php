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
$string['reviewcourseintro'] = 'Bereit, „{$a}" zu verbessern? Klicken Sie auf die Schaltfläche unten – wir zeigen Ihnen, was gut läuft und wo noch Potenzial steckt.';
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
$string['tourintro_audit_content'] = '<p>Die Kurs-Audit-Tour zeigt Verbesserungspotenziale direkt in Ihrem Kurs.</p><ul><li><strong>Es wird nichts automatisch geändert</strong> – Sie entscheiden, was Sie umsetzen.</li><li>Setzen Sie die Empfehlung (oft im Bearbeitungsmodus) um und gehen Sie dann weiter.</li><li>Wenn ein Schritt eine Schaltfläche anbietet (z. B. „Zeigen Sie mir wie …"), startet eine kurze geführte Hilfe für genau diese Einstellung.</li></ul>';
$string['tourintro_scenario_title'] = 'So nutzen Sie die Szenario-Tour';
$string['tourintro_scenario_content'] = '<p>Die Szenario-Tour ist eine praktische Checkliste für den Aufbau eines adaptiven Kursdesigns.</p><ul><li><strong>Es wird nichts automatisch geändert</strong> – nutzen Sie die Schritte als Anleitung beim Bearbeiten Ihres Kurses.</li><li>Schalten Sie den Bearbeitungsmodus ein, damit Sie Änderungen direkt umsetzen können.</li><li>Denken Sie in Etappen: erst Struktur, dann Aktivitäten, dann Bedingungen/Feedback.</li></ul>';
$string['tourintro_teach_title'] = 'So nutzen Sie die geführte Hilfe';
$string['tourintro_teach_content'] = '<p>Diese kurze Tour konzentriert sich auf eine konkrete Einstellung oder Aktion (z. B. Test-Verhalten, Feedback, Voraussetzungen).</p><ul><li>Nehmen Sie die Änderung auf dieser Seite vor und gehen Sie dann zum nächsten Schritt.</li><li><strong>Es wird nichts automatisch geändert</strong> – die Tour erklärt nur, was zu tun ist.</li><li>Sie können die Tour jederzeit schließen und später fortsetzen.</li></ul>';
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
$string['touraction_edit_quiz_settings'] = 'Einstellungen von „{$a}" bearbeiten';
$string['startteacherror'] = 'Die geführte Hilfe kann gerade nicht gestartet werden. Bitte versuchen Sie es später erneut.';
$string['teachtourname'] = 'Geführte Hilfe: {$a}';
$string['teachtourdescription'] = 'Kurze, praktische Schritte zu Test-Einstellungen in {$a->course}.';
$string['actiontourname'] = 'Geführte Hilfe: {$a->action}';
$string['actiontourdescription'] = 'Kurze Schritte, um diese Aktion in {$a->course} abzuschließen.';
$string['actiontour_loop_quiz_unlock_followups_addquiz_step_name_title'] = 'Test benennen';
$string['actiontour_loop_quiz_unlock_followups_addquiz_step_name_body'] = '<p>Geben Sie dem Test einen klaren Namen, damit er in diesem Abschnitt auffällt.</p><ul><li>Nutzen Sie ein Verb („Prüfen", „Üben", „Selbsttest").</li><li>Halten Sie ihn kurz, damit er in der Abschnittsansicht gut lesbar ist.</li></ul>';
$string['actiontour_loop_quiz_unlock_followups_addquiz_step_completion_title'] = 'Abschluss aktivieren';
$string['actiontour_loop_quiz_unlock_followups_addquiz_step_completion_body'] = '<p>Schalten Sie den <strong>Aktivitätsabschluss</strong> ein, damit Folgeaktivitäten darauf aufbauen können.</p><p><strong>Ziel:</strong> Moodle kann die nächsten Schritte zuverlässig freischalten.</p>';
$string['actiontour_loop_quiz_unlock_followups_addquiz_step_access_title'] = 'Voraussetzung hinzufügen';
$string['actiontour_loop_quiz_unlock_followups_addquiz_step_access_body'] = '<p>Nutzen Sie <strong>Voraussetzungen</strong>, um den Test hinter die vorbereitende Aktivität zu legen.</p><p>So verhindern Sie, dass Lernende direkt zum Test springen.</p>';
$string['actiontour_loop_quiz_unlock_followups_editquiz_step_access_title'] = 'Test absichern';
$string['actiontour_loop_quiz_unlock_followups_editquiz_step_access_body'] = '<p>Fügen Sie unter <strong>Voraussetzungen</strong> eine Abschlussbedingung hinzu, damit zuerst die Vorbereitung erledigt wird.</p><p><strong>Tipp:</strong> Abschlussbedingungen sind meist robuster als Datumsbedingungen.</p>';
$string['actiontour_loop_quiz_unlock_followups_editquiz_step_completion_title'] = 'Abschluss prüfen';
$string['actiontour_loop_quiz_unlock_followups_editquiz_step_completion_body'] = '<p>Stellen Sie sicher, dass der Abschluss automatisch gesetzt wird, damit Folgeaktivitäten freigeschaltet werden.</p><p><strong>Typisch:</strong> Abschluss beim Abgeben / bei Bestehen.</p>';
$string['reviewtypeadaptive'] = 'Ganzer Kurs';
$string['reviewtypesection'] = 'Abschnitt „{$a}"';
$string['reviewsectiondescription'] = 'Nur „{$a}" prüfen.';
$string['reviewcourseerror'] = 'Beim Laden der Vorschau ist etwas schiefgelaufen. Bitte erneut versuchen.';
$string['startsectionreview'] = 'Abschnitt prüfen';
$string['privacy:metadata'] = 'Dieses Plugin speichert keine personenbezogenen Daten.';
$string['settings:description'] = 'Weitere Optionen erscheinen hier, sobald das Plugin wächst.';
$string['adaptive_course_audit:view'] = 'Kursprüfungsseite anzeigen';

// Tasks.
$string['task_cleanup_tours'] = 'Veraltete Touren des adaptiven Kurs-Audits bereinigen';

// Kurs-Ebene: Filter-Einstellungen (Auto-Verlinkung von Aktivitätsnamen).
$string['rule_course_filter_activitynames_name'] = 'Aktivitäts-Verlinkung im Feedback';
$string['rule_course_filter_activitynames_description'] = 'Prüft, ob der Filter „Aktivitätsnamen automatisch verlinken" in diesem Kurs aktiviert ist (hilft beim Verlinken von Kursmaterial im Feedback).';
$string['rule_course_filter_activitynames_rationale'] = '<h5>Warum das wichtig ist</h5><p>Handlungsleitendes Feedback verweist oft auf passende nächste Ressourcen – insbesondere durch Verlinkungen auf Lernmaterialien im Kurs (vgl. Leitfaden adaptive Lehre, 2025, Kap. 3.3 u. Kap. 4; MIau.nrw, Kap. Erste Schritte u. Next Steps).</p><p>Wenn <strong>Aktivitätsnamen automatisch verlinken</strong> aktiviert ist, können Sie in Quiz-/Frage-Feedbacks Aktivitätsnamen nennen und Moodle verlinkt diese automatisch – eine technische Umsetzungshilfe, die das Verlinken im Feedback erleichtert.</p>';
$string['rule_course_filter_activitynames_headline_success'] = 'Aktivitäts-Verlinkung ist verfügbar';
$string['rule_course_filter_activitynames_headline_needs_work'] = 'Aktivitäts-Verlinkung ist nicht aktiviert';
$string['rule_course_filter_activitynames_missing'] = 'Wir empfehlen, den Filter <strong>Aktivitätsnamen automatisch verlinken</strong> in diesem Kurs zu aktivieren, damit Verweise im Feedback direkt zu Links werden können.';
$string['rule_course_filter_activitynames_notavailable'] = 'Der Filter <strong>Aktivitätsnamen automatisch verlinken</strong> scheint in diesem Kurs nicht verfügbar zu sein (evtl. ist er systemweit deaktiviert). Es kann hilfreich sein, die Aktivierung bei der Administration anzufragen.';
$string['touraction_open_course_filters'] = 'Filter-Einstellungen im Kurs öffnen';

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
$string['rule_loop_quiz_unlock_followups_additional_followups'] = 'Sie könnten eine weitere Aktivität hinzufügen, die von „{$a->activity}" abhängt.';
$string['rule_loop_quiz_unlock_followups_followup_list'] = '{$a->count} Aktivitäten werden nach dem Test freigeschaltet: {$a->items}.';
$string['rule_loop_quiz_unlock_followups_success'] = 'Prima! Inhalte führen in einen Test, der die nächsten Schritte freischaltet.';
$string['rule_loop_quiz_unlock_followups_rationale'] = '<h5>Warum das wichtig ist</h5><p>Ein minimal adaptiver Lernpfad folgt oft einem einfachen Muster:</p><ul><li>Wissensinhalt</li><li>Kompetenzabfrage (Test)</li><li>passende nächste Schritte</li></ul><p>Das Freischalten von Folgeaktivitäten über Voraussetzungen und Abschlussverfolgung hilft Lernenden, ihr Wissen zu zeigen, bevor sie weitergehen (vgl. Leitfaden adaptive Lehre, 2025, Kap. 3.1, Abb. 2; MIau.nrw, Kap. Erste Schritte).</p>';

// Verzweigung nach Testergebnis (Note/Punkte) – Strings.
$string['rule_loop_branch_by_grade_name'] = 'Nach Testergebnis verzweigen';
$string['rule_loop_branch_by_grade_description'] = 'Nutzen Sie Noten-/Punktegrenzen, um unterschiedliche Folgepfade freizuschalten (Förderpfad vs. Vertiefung).';
$string['rule_loop_branch_by_grade_headline_missing_gradeitem'] = 'Notenbasierte Verzweigung nicht verfügbar';
$string['rule_loop_branch_by_grade_missing_gradeitem'] = 'Für den Test „{$a}" konnte kein Eintrag im Bewertungsbuch gefunden werden. Prüfen Sie, ob der Test eine Maximalpunktzahl hat und im Bewertungsbuch geführt wird.';
$string['rule_loop_branch_by_grade_headline_missing'] = 'Noch keine notenbasierten Pfade';
$string['rule_loop_branch_by_grade_missing'] = 'Wir empfehlen, bei Folgeaktivitäten unter „Voraussetzungen" eine Bedingung „Bewertung" zu nutzen, damit Lernende je nach Ergebnis in „{$a}" unterschiedliche nächste Schritte sehen.';
$string['rule_loop_branch_by_grade_headline_success'] = 'Notenbasierte Verzweigung gefunden';
$string['rule_loop_branch_by_grade_found'] = 'Folgeaktivitäten sind über das Ergebnis in „{$a->quiz}" freigeschaltet: {$a->branches}.';
$string['rule_loop_branch_by_grade_suggest_two_paths'] = 'Tipp: Definieren Sie sowohl einen Förderpfad (Maximalwert) als auch einen Vertiefungspfad (Minimalwert) für „{$a}".';
$string['rule_loop_branch_by_grade_rationale'] = '<h5>Warum das wichtig ist</h5><p>Verzweigungen nach Testergebnis sind ein praxisnaher Weg, auf heterogene Vorkenntnisse zu reagieren.</p><p>Über Voraussetzungen können Lernende je nach Leistung zu Förder- oder Vertiefungsaktivitäten geleitet werden (vgl. Leitfaden adaptive Lehre, 2025, Kap. 3.1; MIau.nrw, Kap. Erste Schritte).</p>';
$string['rule_loop_branch_by_grade_range_any'] = 'beliebiges Ergebnis';
$string['rule_loop_branch_by_grade_range_min'] = '≥ {$a}%';
$string['rule_loop_branch_by_grade_range_max'] = '< {$a}%';
$string['rule_loop_branch_by_grade_range_between'] = '{$a->min}%–{$a->max}%';
$string['touraction_add_grade_gate'] = 'Noten-Bedingung zu „{$a}" hinzufügen';
$string['actiontour_gradegate_step_access_title'] = 'Bewertungsbedingung hinzufügen';
$string['actiontour_gradegate_step_access_body'] = '<p>Öffnen Sie <strong>Voraussetzungen</strong> und fügen Sie eine Bedingung <strong>Bewertung</strong> (Min/Max) hinzu.</p><p><strong>Ergebnis:</strong> Lernende sehen je nach Punktzahl unterschiedliche Folgepfade.</p>';

// Quiz-Feedback-Qualität – Strings.
$string['rule_loop_quiz_feedback_name'] = 'Handlungsleitendes Quiz-Feedback';
$string['rule_loop_quiz_feedback_description'] = 'Prüft, ob für den Test „Gesamtfeedback" hinterlegt ist (Basis für handlungsleitende Hinweise und adaptive nächste Schritte).';
$string['rule_loop_quiz_feedback_headline_success'] = 'Feedback unterstützt adaptive nächste Schritte';
$string['rule_loop_quiz_feedback_headline_needs_work'] = 'Feedback kann adaptiver werden';
$string['rule_loop_quiz_feedback_missing'] = 'Wir empfehlen, für „{$a}" „Gesamtfeedback" zu hinterlegen, damit Lernende nach der Abgabe konkrete Hinweise erhalten.';
$string['rule_loop_quiz_feedback_found'] = 'Gesamtfeedback ist für „{$a}" vorhanden.';
$string['rule_loop_quiz_feedback_missing_links'] = 'Tipp: Wenn Sie Gesamtfeedback ergänzen, gestalten Sie es handlungsleitend – z. B. mit Links zu passenden Ressourcen. Alternativ können Sie (falls verfügbar) den Filter „Aktivitätsnamen automatisch verlinken" nutzen, damit genannte Aktivitätsnamen automatisch zu Links werden.';
$string['rule_loop_quiz_feedback_suggest_attempts'] = 'Tipp: Erlauben Sie mehr als einen Versuch für „{$a}", damit Lernende nach einem Förderpfad erneut antreten können.';
$string['rule_loop_quiz_feedback_rationale'] = '<h5>Warum das wichtig ist</h5><p>Feedback in Tests ist ein niedrigschwelliger Einstieg in adaptive Lehre.</p><p><strong>Handlungsleitendes Feedback</strong> (idealerweise mit Links) und Wiederholungsversuche unterstützen Selbststeuerung und helfen, Lücken zu schließen, bevor es weitergeht (vgl. Leitfaden adaptive Lehre, 2025, Kap. 3.3 u. Kap. 4; MIau.nrw, Kap. Erste Schritte u. Next Steps).</p>';
$string['touraction_edit_quiz_feedback'] = 'Feedback in „{$a}" verbessern';
$string['actiontour_quizfeedback_step_overallfeedback_title'] = 'Gesamtfeedback ergänzen';
$string['actiontour_quizfeedback_step_overallfeedback_body'] = '<p>Nutzen Sie <strong>Gesamtfeedback</strong>, um je nach Ergebnis unterschiedliche Hinweise zu geben.</p><p><strong>Handlungsleitend:</strong> Verlinken Sie auf passende Folgeaktivitäten (Förderpfad vs. Vertiefung).</p>';
$string['actiontour_quizfeedback_step_attempts_title'] = 'Übungs-Schleifen ermöglichen';
$string['actiontour_quizfeedback_step_attempts_body'] = '<p>Setzen Sie <strong>Versuche erlaubt</strong> auf mehr als 1, wenn Lernende nach einem Förderpfad erneut üben sollen.</p><p>So wird der Test zur Übungs-Schleife mit Feedback.</p>';

// Test: Überprüfungsoptionen (was Lernende sehen, wann).
$string['actiontour_quizreviewoptions_step_reviewoptions_title'] = 'Überprüfungsoptionen einstellen';
$string['actiontour_quizreviewoptions_step_reviewoptions_body'] = '<p>Öffnen Sie <strong>Überprüfungsoptionen</strong> und legen Sie fest, was Lernende <em>während</em>, <em>direkt nach</em> und <em>später</em> sehen.</p><p><strong>Tipp:</strong> Geben Sie die passende Information zum passenden Zeitpunkt frei (z. B. Gesamtfeedback, richtige Antworten, allgemeines Feedback).</p>';

// Test: Bewertung (wie Versuche bewertet werden / Note im Kurs).
$string['actiontour_quizgrading_step_grade_title'] = 'Bewertung prüfen';
$string['actiontour_quizgrading_step_grade_body'] = '<p>Unter <strong>Bewertung</strong> wählen Sie, wie mehrere Versuche bewertet werden (beste/mittlere/erste/letzte Bewertung) und ob die Maximalpunktzahl sinnvoll gesetzt ist.</p><p><strong>Wirkung:</strong> Das Testergebnis passt zu Ihren Lernzielen und ggf. zu notenbasierten Voraussetzungen.</p>';

// Test: Abschlussverfolgung (robuste Folgepfade und Berichte).
$string['actiontour_quizcompletion_step_completion_title'] = 'Abschluss für Folgeaktivitäten nutzen';
$string['actiontour_quizcompletion_step_completion_body'] = '<p>Unter <strong>Aktivitätsabschluss</strong> legen Sie Bedingungen fest, wann der Test als abgeschlossen gilt (z. B. Abgabe und/oder Bestehen).</p><p><strong>Warum:</strong> So können Sie Folgeaktivitäten zuverlässig freischalten, Fortschritt in Berichten nachvollziehen und robuste Lernpfade bauen.</p>';

// Test: Zeitplanung und Sicherheit (Zugriff/Regeln).
$string['actiontour_quiztimingsecurity_step_timing_title'] = 'Zeitplanung festlegen';
$string['actiontour_quiztimingsecurity_step_timing_body'] = '<p>Nutzen Sie <strong>Zeitplanung</strong>, um Verfügbarkeit (Öffnen/Schließen) zu steuern und bei Bedarf ein Zeitlimit zu setzen.</p><p><strong>Tipp:</strong> Stimmen Sie die Zeitplanung auf den Kursrhythmus ab, damit Lernende gut planen können.</p>';
$string['actiontour_quiztimingsecurity_step_security_title'] = 'Sicherheitsoptionen prüfen';
$string['actiontour_quiztimingsecurity_step_security_body'] = '<p>Unter <strong>Sicherheit</strong> legen Sie fest, ob zusätzliche Einschränkungen nötig sind (z. B. Passwort, Subnetz, Browser-Sicherheit).</p><p><strong>Tipp:</strong> Halten Sie es einfach, solange es kein klares Prüfungsszenario gibt.</p>';

// Diagnose-Checkpoint (Umfrage/Choice/Feedback) – Strings.
$string['rule_loop_diagnostic_checkpoint_name'] = 'Diagnose-Checkpoint';
$string['rule_loop_diagnostic_checkpoint_description'] = 'Nutzen Sie eine kurze Diagnose (Choice/Feedback/Survey) plus klare Wegweiser, damit Lernende ihren Lernweg besser steuern können.';
$string['rule_loop_diagnostic_checkpoint_rationale'] = '<h5>Warum das wichtig ist</h5><p>Kurze Diagnosen (Bedarfe/Selbsteinschätzung) liefern ein Rückmeldesignal für Lernende und Lehrende.</p><p>Mit klaren Wegweisern und optionalen Voraussetzungen unterstützen sie Selbststeuerung und helfen Lernenden, den passenden Pfad zu wählen (vgl. Leitfaden adaptive Lehre, 2025, Kap. 3.4 u. Kap. 4; MIau.nrw, Kap. Erste Schritte).</p>';
$string['rule_loop_diagnostic_checkpoint_headline_missing'] = 'Kein Diagnose-Checkpoint';
$string['rule_loop_diagnostic_checkpoint_missing'] = 'Wir empfehlen, eine kurze Diagnose-Aktivität (z. B. Feedback) einzusetzen, um Bedarfe oder Selbsteinschätzung am Abschnittsanfang zu erfassen.';
$string['rule_loop_diagnostic_checkpoint_found'] = 'Diagnose-Checkpoint gefunden: „{$a}".';
$string['rule_loop_diagnostic_checkpoint_missing_signposting'] = 'Es kann hilfreich sein, direkt danach Wegweiser zu ergänzen (z. B. Textfeld/Seite), die erklären, welchen Pfad Lernende als Nächstes wählen sollen.';
$string['rule_loop_diagnostic_checkpoint_suggest_gate'] = 'Optional: Es kann hilfreich sein, Folgeaktivitäten erst nach Abschluss von „{$a}" freizuschalten, damit niemand den Checkpoint überspringt.';
$string['rule_loop_diagnostic_checkpoint_gated_followups'] = '{$a} Aktivitäten werden erst nach Abschluss des Checkpoints freigeschaltet.';
$string['rule_loop_diagnostic_checkpoint_headline_success'] = 'Checkpoint + Wegweiser vorhanden';
$string['rule_loop_diagnostic_checkpoint_headline_needs_work'] = 'Checkpoint braucht klarere Wegweiser';
$string['touraction_add_diagnostic'] = 'Diagnose-Aktivität hinzufügen';
$string['touraction_edit_diagnostic'] = '„{$a}" bearbeiten';
$string['actiontour_diagnostic_step_name_title'] = 'Checkpoint benennen';
$string['actiontour_diagnostic_step_name_body'] = 'Wählen Sie einen klaren Namen wie „Kurz-Selbstcheck" oder „Bedarfsabfrage", damit Lernende den Zweck verstehen.';
$string['actiontour_diagnostic_step_access_title'] = 'Optional: Folgeaktivitäten absichern';
$string['actiontour_diagnostic_step_access_body'] = '<p>Wenn gewünscht: Nutzen Sie <strong>Voraussetzungen</strong> (Abschluss), damit Lernende den Checkpoint vor dem Weiterarbeiten abschließen.</p><p><strong>Tipp:</strong> Optional lassen, wenn Überspringen nicht kritisch ist.</p>';

// Quiz-Verhalten (adaptiv/interaktiv) – Strings.
$string['rule_loop_quiz_adaptive_behaviour_name'] = 'Adaptives Quiz-Verhalten';
$string['rule_loop_quiz_adaptive_behaviour_description'] = 'Prüft, ob Tests adaptive/interaktive Fragemodi nutzen (mehrere Versuche mit unmittelbarem Feedback).';
$string['rule_loop_quiz_adaptive_behaviour_headline_success'] = 'Adaptives Quiz-Verhalten wird genutzt';
$string['rule_loop_quiz_adaptive_behaviour_headline_needs_work'] = 'Quiz-Verhalten kann adaptiver werden';
$string['rule_loop_quiz_adaptive_behaviour_rationale'] = '<h5>Warum das wichtig ist</h5><p>Adaptive Lehre lebt von kontinuierlicher Rückkopplung und kurzen Kompetenzabfragen (vgl. Leitfaden adaptive Lehre, 2025, Kap. 3.2 u. Kap. 4; MIau.nrw, Kap. Erste Schritte).</p><p>Adaptive/interaktive Fragemodi in Moodle können Tests in eine Übungs-Schleife mit unmittelbarem Feedback verwandeln und so Selbstkorrektur vor dem Weiterlernen unterstützen.</p>';
$string['rule_loop_quiz_adaptive_behaviour_found_quiz'] = 'Test „{$a->quiz}" nutzt Fragemodus: {$a->behaviour}.';
$string['rule_loop_quiz_adaptive_behaviour_behaviour_unknown'] = 'unbekannt';
$string['rule_loop_quiz_adaptive_behaviour_missing'] = 'Tipp: Es kann hilfreich sein, „{$a}" auf einen adaptiven/interaktiven Fragemodus umzustellen, damit Lernende mit unmittelbarem Feedback erneut versuchen können.';
$string['rule_loop_quiz_adaptive_behaviour_success'] = 'Gut: Mindestens ein Test unterstützt hier Übungs-Schleifen ({$a}).';
$string['touraction_edit_quiz_behaviour'] = 'Verhalten in „{$a}" anpassen';
$string['actiontour_quizbehaviour_step_behaviour_title'] = 'Adaptiven Fragemodus aktivieren';
$string['actiontour_quizbehaviour_step_behaviour_body'] = '<p>Stellen Sie <strong>Frageverhalten</strong> auf einen adaptiven/interaktiven Modus (z. B. Adaptiver Modus oder Interaktiv mit mehreren Versuchen).</p><p><strong>Wirkung:</strong> Lernende erhalten unmittelbares Feedback und können erneut versuchen.</p>';

// Lektion-Verzweigungen – Strings.
$string['rule_loop_lesson_branching_name'] = 'Lektion-Verzweigungen';
$string['rule_loop_lesson_branching_description'] = 'Prüft, ob Lektionen je nach Antwort zu unterschiedlichen Seiten verzweigen (Sprünge/„jumpto").';
$string['rule_loop_lesson_branching_headline_success'] = 'Lektions-Verzweigungen gefunden';
$string['rule_loop_lesson_branching_headline_needs_work'] = 'Lektion könnte adaptiver verzweigen';
$string['rule_loop_lesson_branching_rationale'] = '<h5>Warum das wichtig ist</h5><p>Verzweigte Lernpfade ermöglichen unterschiedliche nächste Schritte je nach Antwort (z. B. Förderung vs. Vertiefung).</p><p>Die Moodle-Aktivität „Lektion" unterstützt komplexe Lernpfade durch bedingte Navigation (vgl. Leitfaden adaptive Lehre, 2025, Kap. 3.1; MIau.nrw, Kap. Erste Schritte).</p>';
$string['rule_loop_lesson_branching_found'] = 'Lektion „{$a}" enthält Verzweigungen zwischen Seiten.';
$string['rule_loop_lesson_branching_missing'] = 'Lektion „{$a}" wirkt überwiegend linear. Erwägen Sie bedingte Sprünge, um Lernende je nach Antwort zu unterschiedlichen Folgeseiten zu leiten.';
$string['rule_loop_lesson_branching_lesson_no_answers'] = 'Lektion „{$a}" hat noch keine Antwort-Datensätze (sie ist ggf. noch leer).';
$string['touraction_open_lesson_editor'] = 'Lektion-Editor für „{$a}" öffnen';

// Zufallsfragen (Aufgabenpools) – Strings.
$string['rule_loop_quiz_random_questions_name'] = 'Zufallsfragen (Aufgabenpool)';
$string['rule_loop_quiz_random_questions_description'] = 'Prüft, ob Tests Zufallsfragen nutzen, um Versuche zu variieren.';
$string['rule_loop_quiz_random_questions_headline_success'] = 'Zufallsfragen gefunden';
$string['rule_loop_quiz_random_questions_headline_needs_work'] = 'Noch keine Zufallsfragen';
$string['rule_loop_quiz_random_questions_rationale'] = '<h5>Warum das wichtig ist</h5><p>Zufallsbasierte Aufgabenpools machen Wiederholungsversuche sinnvoller und unterstützen Übungs-Schleifen.</p><p>Sie sind zudem eine Gestaltungsoption für adaptive Testszenarien (vgl. Leitfaden adaptive Lehre, 2025, Kap. 3.2).</p>';
$string['rule_loop_quiz_random_questions_found'] = 'Test „{$a->quiz}" enthält {$a->count} Zufallsfragen-Slots.';
$string['rule_loop_quiz_random_questions_missing'] = 'Test „{$a}" nutzt noch keine Zufallsfragen. Tipp: Es kann hilfreich sein, Zufallsfragen aus einer kategorisierten Fragensammlung zu ergänzen.';
$string['rule_loop_quiz_random_questions_empty'] = 'Test „{$a}" hat noch keine Fragen/Slots (er ist ggf. noch leer).';
$string['touraction_open_quiz_edit'] = 'Fragen in „{$a}" bearbeiten';
$string['actiontour_quizrandomquestions_step_add_title'] = 'Zufallsfrage hinzufügen';
$string['actiontour_quizrandomquestions_step_add_body'] = '<p>Öffnen Sie hier das <strong>Hinzufügen</strong>-Menü und wählen Sie <strong>Zufallsfrage</strong>.</p><p><strong>Tipp:</strong> Nutzen Sie Kategorien, um Aufgabenpools zu strukturieren und Versuche zu variieren.</p>';

// H5P (Vorhanden?) – Strings.
$string['rule_loop_h5p_interactive_name'] = 'Interaktives H5P (Vorhanden?)';
$string['rule_loop_h5p_interactive_description'] = 'Prüft, ob es im Abschnitt eine H5P-Aktivität gibt (nur Vorhandenheits-Check).';
$string['rule_loop_h5p_interactive_headline_success'] = 'H5P-Aktivität gefunden';
$string['rule_loop_h5p_interactive_headline_needs_work'] = 'Noch keine H5P-Aktivität';
$string['rule_loop_h5p_interactive_rationale'] = '<h5>Warum das wichtig ist</h5><p>H5P kann Entscheidungspunkte und visuelle Verzweigungen (z. B. „Branching Scenario") unterstützen.</p><p>So können Lernende Inhalte passend zu ihren Bedarfen durchlaufen (vgl. Leitfaden adaptive Lehre, 2025, Kap. 3.1; MIau.nrw, Kap. Und nun?).</p>';
$string['rule_loop_h5p_interactive_found'] = 'H5P-Aktivität(en) im Abschnitt gefunden: {$a}.';
$string['rule_loop_h5p_interactive_missing'] = 'Keine H5P-Aktivität im Abschnitt gefunden. Wenn Sie einen visuellen, verzweigten Lernpfad möchten, kann H5P ein niedrigschwelliger Einstieg sein.';
$string['touraction_add_h5p'] = 'H5P-Aktivität hinzufügen';
$string['actiontour_h5p_step_name_title'] = 'H5P-Aktivität benennen';
$string['actiontour_h5p_step_name_body'] = '<p>Wählen Sie einen klaren Namen, damit Lernende wissen, was sie erwartet.</p><ul><li>Beispiel: <strong>Branching Scenario</strong></li><li>Beispiel: <strong>Wähle deinen Pfad</strong></li></ul>';

// Szenario-Touren.
$string['scenario_heading'] = 'Szenario-Touren: Schritt für Schritt zum adaptiven Kurs';
$string['scenario_description'] = 'Wählen Sie ein Szenario, das zu Ihrem Kurs passt. Die Tour begleitet Sie durch die wichtigsten Schritte.';
$string['scenario_description_html'] = '<p>Wählen Sie ein Szenario, das zu Ihrem Kurs passt. Die Tour begleitet Sie durch die wichtigsten Schritte. <strong>Es wird nichts automatisch geändert</strong> – setzen Sie die Hinweise Schritt für Schritt um.</p><ul><li><strong>Pfad 1 (Minimalist):</strong> klein anfangen und ausgewählte Teile adaptiv gestalten.</li><li><strong>Pfad 2 (Sequenziell):</strong> Themen bauen aufeinander auf; Sektionen werden nacheinander freigeschaltet.</li><li><strong>Pfad 3 (Kompass):</strong> freie Themenreihenfolge mit einem „Kompass"-Quiz zur Orientierung.</li></ul>';
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
$string['scenario_1_step3_title'] = 'Das Muster: Inhalt → Quiz → Weiteres';
$string['scenario_1_step3_content'] = '<p>Die minimalistische adaptive Struktur besteht aus drei Teilen in einer Sektion:</p><ol><li><strong>Lerninhalt</strong> – eine Seite, ein Video oder ein Buch zum Thema</li><li><strong>Ein Quiz</strong> – eine kurze Wissensüberprüfung im adaptiven Modus</li><li><strong>Weiterführender Inhalt</strong> – zusätzliche Ressourcen, die für Studierende freigeschaltet werden, die mehr Unterstützung brauchen</li></ol><p>Lassen Sie uns das gemeinsam Schritt für Schritt aufbauen.</p><div class="local-aca-tour-rationale"><h5>Warum das wichtig ist</h5><p>Die Grundstruktur mit 3 Elementen (Wissensinhalt → Kompetenzabfrage → optionaler Inhalt) ist das Basismuster für Lernpfade mit Verzweigung. Damit können Lernende je nach Ergebnis unterschiedliche nächste Schritte sehen (vgl. Leitfaden adaptive Lehre, 2025; MIau.nrw).</p></div>';
$string['scenario_1_step4_title'] = 'Schritt 1 von 3 – Neue Sektion anlegen';
$string['scenario_1_step4_content'] = '<p>Erstellen Sie zunächst eine eigene Sektion für Ihre adaptive Einheit.</p><ol><li>Stellen Sie sicher, dass die Kursbearbeitung aktiv ist (oben rechts)</li><li>Scrollen Sie zum Ende des Kurses und klicken Sie auf <strong>Sektion hinzufügen</strong></li><li>Benennen Sie die Sektion z.B. <em>Adaptive Einheit: [Ihr Thema]</em></li></ol><p>Klicken Sie auf <strong>Weiter</strong>, wenn Ihre Sektion bereit ist.</p>';
$string['scenario_1_step5_title'] = 'Schritt 2 von 3 – Lerninhalt hinzufügen';
$string['scenario_1_step5_content'] = '<p>Fügen Sie nun eine Lerninhalt-Seite zu Ihrem Kurs hinzu. Klicken Sie den Button, um den Seiteneditor mit einer geführten Tour zu öffnen.</p>';
$string['scenario_1_step5_button'] = 'Inhaltsseite hinzufügen →';
$string['scenario_1_step6_title'] = 'Schritt 3 von 3 – Quiz hinzufügen';
$string['scenario_1_step6_content'] = '<p>Fügen Sie nun ein Quiz für die Wissensüberprüfung hinzu. Klicken Sie den Button, um den Quiz-Editor mit einer geführten Tour für adaptive Einstellungen zu öffnen.</p>';
$string['scenario_1_step6_button'] = 'Quiz hinzufügen →';
$string['scenario_1_step7_title'] = 'Weiterführenden Inhalt hinzufügen';
$string['scenario_1_step7_content'] = '<p>Fügen Sie abschließend Inhalte für Studierende hinzu, die mehr Unterstützung brauchen. Dies könnte sein:</p><ul><li>Eine <strong>Seite</strong> mit zusätzlichen Erklärungen oder Musteraufgaben</li><li>Eine <strong>URL</strong> mit einem Link zu externem Lesematerial</li><li>Eine <strong>Datei</strong> mit ergänzendem Material</li></ul><p>Fügen Sie diese Aktivität in Ihrer neuen Sektion über den Standard-Kurseditor ein. Danach können Sie Zugangsbeschränkungen setzen, sodass der Inhalt nur für Studierende freigeschaltet wird, die das Quiz nicht bestanden haben.</p><p><strong>Ihre minimalistische adaptive Struktur ist nun vollständig. Sehr gut!</strong></p>';
// Subtour A: Seitenerstellung (geführt über Aktionsbutton aus Schritt 5).
$string['minimalist_page_tour_intro_title'] = 'Ihre Inhaltsseite erstellen';
$string['minimalist_page_tour_intro_content'] = 'Diese kurze Tour begleitet Sie beim Einrichten einer Lerninhalt-Seite für Ihre adaptive Einheit.';
$string['minimalist_page_step1_title'] = 'Inhaltsseite benennen';
$string['minimalist_page_step1_content'] = '<p>Geben Sie dieser Seite einen aussagekräftigen Namen, zum Beispiel: <em>Lernmaterial: [Ihr Thema]</em></p>';
$string['minimalist_page_step2_title'] = 'Lerninhalt hinzufügen';
$string['minimalist_page_step2_content'] = '<p>Schreiben oder fügen Sie Ihren Lerninhalt hier ein. Platzhaltertext ist vorerst in Ordnung – Sie können ihn jederzeit bearbeiten.</p>';
$string['minimalist_page_step3_title'] = 'Speichern und zurück';
$string['minimalist_page_step3_content'] = '<p>Speichern Sie die Seite mit <strong>Speichern und zum Kurs</strong>. Kehren Sie dann zu Ihrem Kurs-Guide zurück und klicken Sie auf <strong>Weiter</strong>.</p>';
// Subtour B: Quiz-Erstellung (geführt über Aktionsbutton aus Schritt 6).
$string['minimalist_quiz_tour_intro_title'] = 'Ihr adaptives Quiz erstellen';
$string['minimalist_quiz_tour_intro_content'] = 'Diese Tour begleitet Sie durch die wichtigsten Einstellungen für ein adaptives Quiz.';
$string['minimalist_quiz_step1_title'] = 'Quiz benennen';
$string['minimalist_quiz_step1_content'] = '<p>Geben Sie dem Quiz einen aussagekräftigen Namen, zum Beispiel: <em>Wissenscheck: [Ihr Thema]</em></p>';
$string['minimalist_quiz_step2_title'] = 'Adaptiven Modus einstellen';
$string['minimalist_quiz_step2_content'] = '<p>Stellen Sie das Fragenverhalten auf <strong>Adaptiver Modus</strong>. Dadurch erhalten Studierende sofortiges Feedback nach jeder Antwort und können Fragen wiederholen.</p>';
$string['minimalist_quiz_step3_title'] = 'Mehrere Versuche erlauben';
$string['minimalist_quiz_step3_content'] = '<p>Erlauben Sie mindestens 2–3 Versuche. Das ermöglicht den adaptiven Wiederholungsloop.</p>';
$string['minimalist_quiz_step4_title'] = 'Abschlussbedingungen festlegen';
$string['minimalist_quiz_step4_content'] = '<p>Aktivieren Sie eine Abschlussbedingung (z.B. Mindestpunktzahl erforderlich). Damit wird gesteuert, welche Folgeaktivitäten basierend auf dem Quiz-Ergebnis freigeschaltet werden.</p>';
$string['minimalist_quiz_step5_title'] = 'Speichern und zurück';
$string['minimalist_quiz_step5_content'] = '<p>Speichern Sie das Quiz mit <strong>Speichern und zum Kurs</strong>. Fragen können Sie später direkt auf der Kursseite hinzufügen.</p>';

// Pfad 2 – Sequenziell.
$string['scenario_2_step1_title'] = 'Vorhandene adaptive Bausteine voraussetzen';
$string['scenario_2_step1_content'] = '<p>Dieses Szenario setzt voraus, dass Ihre Sektionen bereits <strong>minimale adaptive Bausteine</strong> enthalten, zum Beispiel Inhalte und eine Aktivitaet, die spaeter als Abschlusssignal dienen kann.</p><p>Diese Tour baut solche Elemente <strong>nicht</strong> neu auf. Sie konzentriert sich nur auf die Verknuepfung zweier Sektionen.</p>';
$string['scenario_2_step2_title'] = 'Freie Abschnittspaarung waehlen';
$string['scenario_2_step2_content'] = '<p>Sie arbeiten mit einer <strong>freien Paarung</strong>: Zuerst waehlen Sie den Quellabschnitt, danach den Zielabschnitt, der spaeter freigeschaltet werden soll.</p><p>So koennen Sie Sektionen nach Ihrer didaktischen Struktur verbinden statt nach einem festen n+1-Muster.</p>';
$string['scenario_2_step3_title'] = 'Quellabschnitt auswaehlen';
$string['scenario_2_step3_content'] = '<p>Waehlen Sie den Abschnitt aus, der bereits die Aktivitaet enthaelt, deren Abschluss spaeter einen anderen Abschnitt freischalten soll.</p><p>Nutzen Sie dazu einen der Buttons unten.</p>';
$string['scenario_2_step4_title'] = 'Zielabschnitt auswaehlen';
$string['scenario_2_step4_content'] = '<p>Waehlen Sie nun den Abschnitt, der nach Abschluss der Aktivitaet aus dem Quellabschnitt verfuegbar werden soll.</p><p>Treffen Sie die Auswahl unten.</p>';
$string['scenario_2_step5_title'] = 'Quellaktivitaet auswaehlen';
$string['scenario_2_step5_content'] = '<p>Waehlen Sie die vorhandene Aktivitaet im Quellabschnitt, die als Abschlusssignal fuer den Zielabschnitt dienen soll.</p><p>Typische Beispiele sind Quizze, Aufgaben oder Seiten mit Abschlussverfolgung.</p>';
$string['scenario_choice_none_available'] = 'Es sind noch keine passenden Auswahlmoeglichkeiten vorhanden.';
$string['scenario_2_completion_tour_title'] = 'Abschluss der Quellaktivitaet konfigurieren';
$string['scenario_2_completion_tour_content'] = 'Diese kurze Tour zeigt, wo Sie die gewaehlte Quellaktivitaet als nutzbares Abschlusssignal konfigurieren.';
$string['scenario_2_completion_step1_title'] = 'Eine nutzbare Abschlussregel setzen';
$string['scenario_2_completion_step1_content'] = '<p>Nutzen Sie den Bereich zur Abschlussverfolgung, damit die Aktivitaet spaeter als verlaessliche Voraussetzung fuer eine andere Sektion dienen kann.</p><p>Waehlen Sie eine Regel passend zum Aktivitaetstyp, zum Beispiel <strong>muss angesehen werden</strong> oder <strong>muss bewertet werden</strong>.</p>';
$string['scenario_2_completion_step2_title'] = 'Speichern und mit dem Zielabschnitt weitermachen';
$string['scenario_2_completion_step2_content'] = '<p>Speichern Sie die Aktivitaetseinstellungen. Oeffnen Sie danach den Zielabschnitt und legen Sie dort eine Einschraenkung an, die von dieser Abschlussregel abhaengt.</p>';
$string['scenario_2_completion_step2_button'] = 'Zielabschnitts-Beschraenkungen oeffnen →';
$string['scenario_2_restriction_tour_title'] = 'Zielabschnitt einschraenken';
$string['scenario_2_restriction_tour_content'] = 'Diese kurze Tour markiert die Stelle, an der der Zielabschnitt anhand der gewaehlten Quellaktivitaet eingeschraenkt wird.';
$string['scenario_2_restriction_step1_title'] = 'Aktivitaetsabschluss als Bedingung hinzufuegen';
$string['scenario_2_restriction_step1_content'] = '<p>Nutzen Sie den Bereich fuer Zugangsbeschraenkungen, um eine Bedingung auf Basis des <strong>Aktivitaetsabschlusses</strong> hinzuzufuegen.</p><p>Waehlen Sie die Quellaktivitaet aus und verlangen Sie, dass sie abgeschlossen sein muss, bevor dieser Abschnitt verfuegbar wird.</p>';
$string['scenario_2_restriction_step2_title'] = 'Abschnitt speichern und zum Kurs zurueck';
$string['scenario_2_restriction_step2_content'] = '<p>Speichern Sie die Abschnittseinstellungen, sobald die Beschraenkung gesetzt ist. Kehren Sie danach zum Kurs zurueck, um das Muster weiter zu verwenden.</p>';
$string['scenario_2_restriction_step2_button'] = 'Zum Kurs zurueck →';
$string['scenario_2_repeat_tour_title'] = 'Verknuepfungsmuster wiederholen';
$string['scenario_2_repeat_tour_content'] = 'Die erste Abschnittsverknuepfung ist jetzt eingerichtet. Sie koennen dasselbe Muster fuer weitere Quell-Ziel-Paare wiederholen.';
$string['scenario_2_repeat_step_title'] = 'Fuer weitere Abschnittspaare wiederholen';
$string['scenario_2_repeat_step_content'] = '<p>Sie haben eine erste Verbindung von Abschnitt zu Abschnitt eingerichtet.</p><p>Wiederholen Sie dieses Muster ueberall dort, wo ein weiterer Abschnitt erst nach Abschluss einer gewaehlten Quellaktivitaet verfuegbar werden soll.</p>';

// Pfad 3 – Kompass-Modell.
$string['scenario_3_step1_title'] = 'Kompass-Modell einfuehren';
$string['scenario_3_step1_content'] = '<p>Das Kompass-Modell funktioniert am besten, wenn die verbundenen Abschnitte <strong>klar unterschiedliche Themen</strong> behandeln.</p><p>Diese Themen sollten nicht streng aufeinander aufbauen. Lernende sollen in einen Themenpfad einsteigen koennen, ohne vorher einen anderen absolvieren zu muessen.</p>';
$string['scenario_3_step2_title'] = 'Einen vollstaendigen Themen-Zyklus planen';
$string['scenario_3_step2_content'] = '<p>Dieser Durchlauf baut genau <strong>einen Kompass-Zyklus</strong> auf:</p><ol><li>Kompass-Quiz waehlen oder erstellen</li><li>Eine Multiple-Choice-Frage mit Feedback anlegen</li><li>Eine Feedback-Aktivitaet am Kursende erstellen</li><li>Fuer diese Aktivitaet View-Completion setzen</li><li>Einen Themenabschnitt ueber diese Aktivitaet freischalten</li></ol>';
$string['scenario_3_step3_title'] = 'Kompass-Quiz erstellen oder auswaehlen';
$string['scenario_3_step3_content'] = '<p>Erstellen Sie ein Kompass-Quiz oder waehlen Sie ein vorhandenes Quiz aus, das diese Rolle uebernehmen soll.</p><p>Nach dem Speichern eines neuen Quiz starten Sie dieses Szenario erneut und waehlen das Quiz unten aus.</p>';
$string['scenario_3_step3_button'] = 'Kompass-Quiz erstellen →';
$string['scenario_3_step4_title'] = 'Feedback-Aktivitaet erstellen oder auswaehlen';
$string['scenario_3_step4_content'] = '<p>Erstellen Sie am Kursende eine neue Feedback-Aktivitaet, vorzugsweise eine <strong>Seite</strong>.</p><p>Der Titel dieser Aktivitaet muss exakt dem Feedback-Text aus der Kompass-Frage entsprechen, zum Beispiel <em>For Access to Topic X - Click here</em>.</p><p>Nach dem Speichern einer neuen Seite starten Sie dieses Szenario erneut und waehlen die Aktivitaet unten aus.</p>';
$string['scenario_3_step4_button'] = 'Feedback-Aktivitaet erstellen →';
$string['scenario_3_step5_title'] = 'Themenabschnitt fuer die Freischaltung waehlen';
$string['scenario_3_step5_content'] = '<p>Waehlen Sie den Themenabschnitt aus, der erst verfuegbar werden soll, nachdem Lernende die Feedback-Aktivitaet angesehen haben.</p><p>Die naechste Tour fuehrt Sie dann durch die Zugangsbeschraenkung dieses Abschnitts.</p>';
$string['scenario_3_step5_button'] = 'Kompass-Frage einrichten →';
$string['compass_orientation_tour_intro_title'] = 'Feedback-Aktivitaet erstellen';
$string['compass_orientation_tour_intro_content'] = 'Diese kurze Tour hilft Ihnen dabei, die Feedback-Aktivitaet anzulegen, deren Titel exakt zum Antwort-Feedback aus der Kompass-Frage passen muss.';
$string['compass_orientation_step1_title'] = 'Feedback-Text als Aktivitaetstitel verwenden';
$string['compass_orientation_step1_content'] = '<p>Benennen Sie diese Seite exakt wie das Antwort-Feedback aus der Kompass-Frage, zum Beispiel <em>For Access to Topic X - Click here</em>.</p>';
$string['compass_orientation_step2_title'] = 'Kurzen Hinweis fuer Lernende eintragen';
$string['compass_orientation_step2_content'] = '<p>Nutzen Sie den Seiteninhalt, um Lernenden mitzuteilen, dass das Oeffnen dieser Aktivitaet ihnen Zugang zum verknuepften Themenabschnitt gibt.</p>';
$string['compass_orientation_step3_title'] = 'Speichern und zum Kurs zurueck';
$string['compass_orientation_step3_content'] = '<p>Speichern Sie die Aktivitaet mit <strong>Speichern und zum Kurs</strong>. Oeffnen Sie dieses Szenario danach erneut und waehlen Sie die neu erstellte Seite aus.</p>';
$string['compass_quiz_tour_intro_title'] = 'Kompass-Quiz erstellen';
$string['compass_quiz_tour_intro_content'] = 'Diese kurze Tour fuehrt durch die Quiz-Einstellungen fuer einen Kompass, der Lernende in einen Themenpfad lenkt.';
$string['compass_quiz_step1_title'] = 'Kompass-Quiz klar benennen';
$string['compass_quiz_step1_content'] = '<p>Geben Sie dem Quiz einen klaren Namen wie <em>Kompass-Quiz</em> oder <em>Finde dein naechstes Thema</em>.</p>';
$string['compass_quiz_step2_title'] = 'Einstellungen fuer Orientierung nutzen';
$string['compass_quiz_step2_content'] = '<p>Waehlen Sie Einstellungen, die das Quiz leichtgewichtig und leitend halten. Es soll Vorwissen oder Praeferenz diagnostizieren und keine Abschlusspruefung sein.</p>';
$string['compass_quiz_step3_title'] = 'Speichern und zum Kurs zurueck';
$string['compass_quiz_step3_content'] = '<p>Speichern Sie das Quiz mit <strong>Speichern und zum Kurs</strong>. Oeffnen Sie dieses Szenario danach erneut und waehlen Sie das Quiz aus der Liste aus.</p>';
$string['compass_feedback_tour_intro_title'] = 'Eine Kompass-Frage mit Feedback erstellen';
$string['compass_feedback_tour_intro_content'] = 'Diese Tour konzentriert sich auf eine einzelne Multiple-Choice-Frage, die Lernende zu einem Themenabschnitt weiterleitet.';
$string['compass_feedback_step1_title'] = 'Fragetext formulieren';
$string['compass_feedback_step1_content'] = '<p>Erstellen Sie eine Multiple-Choice-Frage, die Vorwissen, Bedarf oder Praeferenz in Bezug auf ein Thema abfragt.</p>';
$string['compass_feedback_step2_title'] = 'Antwortoptionen anlegen';
$string['compass_feedback_step2_content'] = '<p>Tragen Sie die Antwortoptionen ein, aus denen Lernende waehlen koennen. Richten Sie sie auf die Themenentscheidung aus, die der Kompass unterstuetzen soll.</p>';
$string['compass_feedback_step3_title'] = 'Feedback-Titel exakt eintragen';
$string['compass_feedback_step3_content'] = '<p>Tragen Sie im Antwort-Feedback oder im allgemeinen Feedback exakt den Titel der Aktivitaet ein, die Lernende als Naechstes anklicken sollen, zum Beispiel <em>For Access to Topic X - Click here</em>.</p>';
$string['compass_feedback_step4_title'] = 'Frage speichern';
$string['compass_feedback_step4_content'] = '<p>Speichern Sie die Frage. Danach geht das Szenario mit dem Anlegen der passenden Feedback-Aktivitaet im Kurs weiter.</p>';
$string['compass_feedback_step5_title'] = 'Frage speichern';
$string['compass_feedback_step5_content'] = '<p>Speichern Sie die Frage, sobald Fragetext, Antworten und Feedback-Titel bereit sind.</p>';
$string['compass_feedback_gateway_tour_intro_title'] = 'Frageeditor aus dem Kompass-Quiz oeffnen';
$string['compass_feedback_gateway_tour_intro_content'] = 'Dieser kurze Gateway startet auf der Quiz-Strukturseite und oeffnet dann den Frageeditor.';
$string['compass_feedback_gateway_step1_title'] = 'Von der Quiz-Struktur ausgehen';
$string['compass_feedback_gateway_step1_content'] = '<p>Nutzen Sie die Quiz-Strukturseite, um zu bestaetigen, dass dieses Quiz der Kompass fuer den Themen-Zyklus ist, den Sie gerade aufbauen.</p>';
$string['compass_feedback_gateway_step2_title'] = 'Neue Multiple-Choice-Frage oeffnen';
$string['compass_feedback_gateway_step2_content'] = '<p>Mit dem Button unten oeffnen Sie den Editor fuer eine neue Multiple-Choice-Frage und tragen dort das themenspezifische Feedback ein.</p>';
$string['compass_feedback_open_editor_button'] = 'Frageeditor oeffnen →';
$string['scenario_3_completion_tour_title'] = 'View-Completion auf der Feedback-Aktivitaet konfigurieren';
$string['scenario_3_completion_tour_content'] = 'Diese kurze Tour zeigt, wo die Feedback-Aktivitaet in ein Ansicht-basiertes Abschlusssignal verwandelt wird.';
$string['scenario_3_completion_step1_title'] = 'Ansehen der Aktivitaet verlangen';
$string['scenario_3_completion_step1_content'] = '<p>Nutzen Sie die Abschlussverfolgung so, dass Lernende diese Aktivitaet <strong>ansehen</strong> muessen. Dieses Ereignis wird spaeter das Signal fuer die Freischaltung des Themenabschnitts.</p>';
$string['scenario_3_completion_step2_title'] = 'Speichern und mit dem Themenabschnitt weitermachen';
$string['scenario_3_completion_step2_content'] = '<p>Speichern Sie die Aktivitaetseinstellungen. Oeffnen Sie danach den Themenabschnitt und hinterlegen Sie dort eine Aktivitaetsabschluss-Beschraenkung auf Basis dieser Feedback-Aktivitaet.</p>';
$string['scenario_3_completion_step2_button'] = 'Beschraenkungen des Themenabschnitts oeffnen →';
$string['scenario_3_restriction_tour_title'] = 'Themenabschnitt einschraenken';
$string['scenario_3_restriction_tour_content'] = 'Diese kurze Tour markiert die Stelle, an der der Themenabschnitt erst nach Ansicht der Feedback-Aktivitaet freigegeben wird.';
$string['scenario_3_restriction_step1_title'] = 'Feedback-Aktivitaet als Bedingung hinzufuegen';
$string['scenario_3_restriction_step1_content'] = '<p>Nutzen Sie die Zugangsbeschraenkungen, um den Abschluss der Feedback-Aktivitaet zu verlangen, bevor dieser Themenabschnitt verfuegbar wird.</p>';
$string['scenario_3_restriction_step2_title'] = 'Abschnitt speichern und zum Kurs zurueck';
$string['scenario_3_restriction_step2_content'] = '<p>Speichern Sie die Abschnittseinstellungen. Kehren Sie danach zum Kurs zurueck, um dasselbe Kompass-Muster fuer ein weiteres Thema zu wiederholen.</p>';
$string['scenario_3_restriction_step2_button'] = 'Zum Kurs zurueck →';
$string['scenario_3_repeat_tour_title'] = 'Kompass-Zyklus wiederholen';
$string['scenario_3_repeat_tour_content'] = 'Ein vollstaendiger Kompass-Zyklus ist jetzt eingerichtet. Sie koennen dieses Muster fuer weitere Themen wiederholen.';
$string['scenario_3_repeat_step_title'] = 'Fuer weitere Themen wiederholen';
$string['scenario_3_repeat_step_content'] = '<p>Sie haben einen vollstaendigen Kompass-Zyklus abgeschlossen: Frage, Feedback-Aktivitaet, View-Completion und Abschnittsfreigabe.</p><p>Wiederholen Sie diesen Zyklus fuer jedes weitere Thema, zu dem der Kompass Lernende fuehren soll.</p>';