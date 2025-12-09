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
$string['reviewcourseintro'] = 'Bereit, „{$a}" zu verbessern? Klicken Sie auf den Button und wir zeigen Ihnen, was gut läuft und wo noch Potenzial steckt.';
$string['reviewcoursedescription'] = 'Analysiert Ihren Kurs und erstellt eine geführte Tour mit Vorschlägen.';
$string['startreview'] = 'Prüfung starten';
$string['startreviewhelp'] = 'Wählen Sie eine Zeile und starten Sie die Prüfung, um Tipps direkt auf Ihrer Kursseite zu sehen.';
$string['startreviewerror'] = 'Die adaptive Prüfung kann derzeit nicht gestartet werden. Bitte versuchen Sie es später erneut.';
$string['startreviewpermission'] = 'Sie haben keine Berechtigung, in diesem Kurs eine Prüfung zu starten.';
$string['loop1summary'] = 'Wir schauen, ob Inhalte in einen Test münden, der die nächsten Schritte freischaltet – so zeigen Lernende ihr Wissen, bevor es weitergeht.';
$string['tourname'] = 'Kurstipps für {$a}';
$string['tourdescription'] = 'Ein kurzer Rundgang mit Vorschlägen für {$a}.';
$string['tourplaceholdertitle'] = 'Los geht\'s';
$string['tourplaceholdercontent'] = 'Wir zeigen Ihnen ein paar Stellen im Kurs und geben praktische Tipps dazu.';
$string['reviewtableheading'] = 'Was möchten Sie prüfen?';
$string['reviewcoltitle'] = 'Bereich';
$string['reviewcoldescription'] = 'Was wir prüfen';
$string['reviewcolaction'] = '';
$string['touraction_add_quiz'] = 'Test hier hinzufügen';
$string['touraction_edit_quiz_settings'] = '„{$a}" bearbeiten';
$string['reviewtypeadaptive'] = 'Ganzer Kurs';
$string['reviewtypesection'] = 'Abschnitt „{$a}"';
$string['reviewsectiondescription'] = 'Nur „{$a}" prüfen.';
$string['reviewcourseerror'] = 'Beim Laden der Vorschau ist etwas schiefgelaufen. Bitte erneut versuchen.';
$string['startsectionreview'] = 'Abschnitt prüfen';
$string['privacy:metadata'] = 'Dieses Plugin speichert keine personenbezogenen Daten.';
$string['settings:description'] = 'Weitere Optionen erscheinen hier, sobald das Plugin wächst.';
$string['adaptive_course_audit:view'] = 'Kursprüfungsseite anzeigen';

// Loop 1 rule strings.
$string['rule_loop_1_name'] = 'Lernen, dann zeigen';
$string['rule_loop_1_headline_min_items'] = 'Mehr Aktivitäten nötig';
$string['rule_loop_1_headline_missing_quiz'] = 'Noch kein Test';
$string['rule_loop_1_headline_missing_kb'] = 'Erst Inhalte anlegen';
$string['rule_loop_1_headline_quiz_no_precondition'] = 'Test braucht Voraussetzung';
$string['rule_loop_1_headline_no_followups'] = 'Nichts wird freigeschaltet';
$string['rule_loop_1_headline_success'] = 'Sieht gut aus!';
$string['rule_loop_1_description'] = 'Erst Inhalte, dann ein Test, der die nächsten Schritte freischaltet.';
$string['rule_loop_1_min_items'] = 'Dieser Abschnitt braucht mindestens {$a} Aktivitäten für eine sinnvolle Abfolge.';
$string['rule_loop_1_missing_quiz'] = 'Fügen Sie einen Test hinzu, damit Lernende zeigen können, was sie gelernt haben.';
$string['rule_loop_1_missing_kb'] = 'Legen Sie vor dem Test Inhalte an (z. B. Seite, Buch oder Link).';
$string['rule_loop_1_quiz_no_precondition'] = 'Machen Sie den Test von vorheriger Arbeit abhängig, damit niemand vorspringt.';
$string['rule_loop_1_no_followups'] = 'Noch nichts hängt vom Test ab. Verknüpfen Sie Folgeaktivitäten mit dem Testergebnis.';
$string['rule_loop_1_additional_followups'] = 'Sie könnten eine weitere Aktivität hinzufügen, die von „{$a->activity}" abhängt.';
$string['rule_loop_1_followup_list'] = '{$a->count} Aktivitäten werden nach dem Test freigeschaltet: {$a->items}.';
$string['rule_loop_1_success'] = 'Prima! Inhalte führen in einen Test, der die nächsten Schritte freischaltet.';

