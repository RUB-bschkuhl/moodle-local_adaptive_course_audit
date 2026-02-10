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
$string['reviewcourseheading'] = 'Adaptives Kurs-Audit';
$string['reviewcourseintro'] = 'Bereit, „{$a}“ zu verbessern? Klicken Sie auf die Schaltfläche unten – wir zeigen Ihnen, was gut läuft und wo noch Potenzial steckt.';
$string['reviewcoursedescription'] = 'Analysiert Ihren Kurs und erstellt eine geführte Tour mit Vorschlägen.';
$string['startreview'] = 'Prüfung starten';
$string['startreviewhelp'] = 'Wählen Sie einen Eintrag aus und starten Sie die Prüfung, um Tipps direkt auf Ihrer Kursseite zu sehen.';
$string['reviewcoursematerialsintro'] = 'Die Hinweise in der Tour basieren auf diesen Materialien (aus MIau.nrw):';
$string['reviewcoursematerial_leitfaden'] = 'Leitfaden Adaptive Lehre (PDF)';
$string['reviewcoursematerial_miau'] = 'Adaptive Kursgestaltung in Moodle (PDF)';
$string['startreviewerror'] = 'Die adaptive Prüfung kann derzeit nicht gestartet werden. Bitte versuchen Sie es später erneut.';
$string['startreviewpermission'] = 'Sie haben keine Berechtigung, in diesem Kurs eine Prüfung zu starten.';
$string['loop_quiz_unlock_followups_summary'] = 'Wir schauen, ob Inhalte in einen Test münden, der die nächsten Schritte freischaltet – so zeigen Lernende ihr Wissen, bevor es weitergeht.';
$string['tourname'] = 'Kurstipps für {$a}';
$string['tourdescription'] = 'Ein kurzer Rundgang mit Vorschlägen für {$a}.';
$string['tourplaceholdertitle'] = 'Los geht\'s';
$string['tourplaceholdercontent'] = '<p>Wir zeigen Ihnen ein paar Stellen im Kurs und geben praktische Tipps dazu.</p><p><strong>So nutzen Sie die Tour:</strong> Empfehlung umsetzen, dann mit dem nächsten Schritt weitermachen.</p>';
$string['reviewtableheading'] = 'Was möchten Sie prüfen?';
$string['reviewcoltitle'] = 'Umfang';
$string['reviewcoldescription'] = 'Was wir prüfen';
$string['reviewcolaction'] = '';
$string['teachquiz_row_title'] = 'Test: {$a}';
$string['teachquiz_row_description'] = 'Zeigen Sie mir, wie ich diesen Test adaptiver mache (Verhalten, Feedback, Übungs-Schleifen).';
$string['teachquiz_behaviour_button'] = 'Adaptives Verhalten';
$string['teachquiz_feedback_button'] = 'Feedback + Versuche';
$string['teachquiz_reviewoptions_button'] = 'Überprüfungsoptionen';
$string['teachquiz_grading_button'] = 'Bewertung';
$string['teachquiz_timingsecurity_button'] = 'Zeitplanung + Sicherheit';
$string['touraction_add_quiz'] = 'Test hier hinzufügen';
$string['touraction_edit_quiz_settings'] = 'Einstellungen von „{$a}“ bearbeiten';
$string['startteacherror'] = 'Die geführte Hilfe kann gerade nicht gestartet werden. Bitte versuchen Sie es später erneut.';
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
$string['rule_loop_quiz_unlock_followups_missing_quiz'] = 'Fügen Sie einen Test hinzu, damit Lernende zeigen können, was sie gelernt haben.';
$string['rule_loop_quiz_unlock_followups_missing_kb'] = 'Legen Sie vor dem Test Inhalte an (z. B. Seite, Buch oder Link).';
$string['rule_loop_quiz_unlock_followups_quiz_no_precondition'] = 'Machen Sie den Test von vorheriger Arbeit abhängig, damit niemand vorspringt.';
$string['rule_loop_quiz_unlock_followups_no_followups'] = 'Noch nichts hängt vom Test ab. Verknüpfen Sie Folgeaktivitäten mit dem Abschluss des Tests.';
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
$string['rule_loop_branch_by_grade_missing'] = 'Fügen Sie bei Folgeaktivitäten unter „Zugriffsbeschränkungen“ eine Bedingung „Bewertung“ hinzu, damit Lernende je nach Ergebnis in „{$a}“ unterschiedliche nächste Schritte sehen.';
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
$string['rule_loop_quiz_feedback_missing'] = 'Fügen Sie für „{$a}“ „Gesamtfeedback“ hinzu, damit Lernende nach der Abgabe konkrete Hinweise erhalten.';
$string['rule_loop_quiz_feedback_found'] = 'Gesamtfeedback ist für „{$a}“ vorhanden.';
$string['rule_loop_quiz_feedback_missing_links'] = 'Machen Sie das Feedback handlungsleitend: Verlinken Sie für „{$a}“ gezielt auf passende Förder-/Vertiefungsressourcen.';
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
$string['rule_loop_diagnostic_checkpoint_missing'] = 'Fügen Sie eine kurze Diagnose-Aktivität hinzu (z. B. Feedback), um Bedarfe oder Selbsteinschätzung am Abschnittsanfang zu erfassen.';
$string['rule_loop_diagnostic_checkpoint_found'] = 'Diagnose-Checkpoint gefunden: „{$a}“.';
$string['rule_loop_diagnostic_checkpoint_missing_signposting'] = 'Fügen Sie direkt danach Wegweiser hinzu (z. B. Textfeld/Seite), die erklären, welchen Pfad Lernende als Nächstes wählen sollen.';
$string['rule_loop_diagnostic_checkpoint_suggest_gate'] = 'Optional: Schalten Sie Folgeaktivitäten erst nach Abschluss von „{$a}“ frei, damit niemand den Checkpoint überspringt.';
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
$string['rule_loop_quiz_adaptive_behaviour_missing'] = 'Tipp: Stellen Sie „{$a}“ auf einen adaptiven/interaktiven Fragemodus um, damit Lernende mit unmittelbarem Feedback erneut versuchen können.';
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
$string['rule_loop_quiz_random_questions_missing'] = 'Test „{$a}“ nutzt noch keine Zufallsfragen. Tipp: Fügen Sie Zufallsfragen aus einer kategorisierten Fragensammlung hinzu.';
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
