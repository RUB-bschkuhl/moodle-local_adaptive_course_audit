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
$string['reviewcourseintro'] = 'Eine Einführung folgt in Kürze. Verwenden Sie die Schaltfläche unten, um die adaptive Prüfung für {$a} zu starten.';
$string['reviewcoursedescription'] = 'Beim Start der Prüfung wird der Kurs analysiert, eine geführte Tour erstellt und Sie kehren zur Kursseite zurück, sobald die Prüfpunkte bereit sind.';
$string['startreview'] = 'Prüfung starten';
$string['startreviewhelp'] = 'Den Ablauf der adaptiven Prüfung ausführen und eine geführte Tour für diesen Kurs erstellen.';
$string['startreviewsuccess'] = 'Prüfung wurde gestartet. Die geführte Tour erscheint auf der Kursseite.';
$string['startreviewerror'] = 'Die adaptive Prüfung kann derzeit nicht gestartet werden. Bitte versuchen Sie es später erneut.';
$string['startreviewpermission'] = 'Sie haben keine Berechtigung, in diesem Kurs eine Prüfung zu starten.';
$string['tourname'] = 'Adaptives Kurs-Audit ({$a})';
$string['tourdescription'] = 'Geführte Prüfschritte für {$a}.';
$string['tourplaceholdertitle'] = 'Übersicht zum adaptiven Kurs-Audit';
$string['tourplaceholdercontent'] = 'Willkommen beim adaptiven Audit. Wir prüfen diesen Kurs oder Abschnitt und erstellen eine kurze geführte Tour mit konkreten nächsten Schritten.';
$string['reviewtableheading'] = 'Optionen für Kursprüfung';
$string['reviewcoltitle'] = 'Titel';
$string['reviewcoldescription'] = 'Beschreibung';
$string['reviewcolaction'] = 'Aktion';
$string['touraction_add_quiz'] = 'Einen Test zu diesem Abschnitt hinzufügen';
$string['touraction_edit_quiz_settings'] = 'Testeinstellungen für "{$a}" öffnen';
$string['reviewtypeadaptive'] = 'Adaptives Kurs-Audit';
$string['reviewtypesection'] = 'Adaptives Audit für Abschnitt "{$a}"';
$string['reviewsectiondescription'] = 'Das adaptive Audit nur für den Abschnitt "{$a}" ausführen.';
$string['reviewcourseerror'] = 'Die Vorschau des adaptiven Kurs-Audits kann derzeit nicht geladen werden.';
$string['startsectionreview'] = 'Abschnittsprüfung starten';
$string['privacy:metadata'] = 'Das Plugin Adaptives Kurs-Audit speichert keine personenbezogenen Daten.';
$string['settings:description'] = 'Adaptive Einsichten für jeden Kurs erscheinen hier, sobald das Plugin weiter ausgebaut wird.';
$string['adaptive_course_audit:view'] = 'Zugriff auf den Navigationspunkt für das adaptive Kurs-Audit';

// Loop 1 rule strings.
$string['rule_loop_1_name'] = 'Schleife 1: Wissensaufbau, dann Test';
$string['rule_loop_1_headline_min_items'] = 'Weitere Aktivitäten hinzufügen';
$string['rule_loop_1_headline_missing_quiz'] = 'Test fehlt';
$string['rule_loop_1_headline_missing_kb'] = 'Zuerst Wissensaufbau ergänzen';
$string['rule_loop_1_headline_quiz_no_precondition'] = 'Test benötigt Voraussetzung';
$string['rule_loop_1_headline_no_followups'] = 'Abhängigkeiten für Folgeaktivitäten hinzufügen';
$string['rule_loop_1_headline_success'] = 'Muster für Schleife 1 erfüllt';
$string['rule_loop_1_description'] = 'Prüft auf eine Sequenz aus Wissensaufbau-Aktivitäten, die zu einem Test führt, der Folgeaktivitäten freischaltet.';
$string['rule_loop_1_min_items'] = 'Fügen Sie mindestens {$a} sichtbare Aktivitäten hinzu, um diese Sequenz zu strukturieren.';
$string['rule_loop_1_missing_quiz'] = 'Fügen Sie einen Test hinzu, damit Lernende ihr Wissen vor dem Weiterarbeiten nachweisen können.';
$string['rule_loop_1_missing_kb'] = 'Fügen Sie vor dem Test mindestens eine Wissensaufbau-Aktivität hinzu (z.B. Seite, Buch, URL).';
$string['rule_loop_1_quiz_no_precondition'] = 'Legen Sie Verfügbarkeitsbedingungen für den Test fest, damit er von vorheriger Arbeit abhängt.';
$string['rule_loop_1_no_followups'] = 'Keine Aktivität hängt vom Test ab. Fügen Sie nachfolgenden Elementen Abschluss-basierten Zugriff hinzu.';
$string['rule_loop_1_additional_followups'] = 'Erwägen Sie eine weitere Folgeaktivität, die von "{$a->activity}" abhängt, um das Ergebnis zu festigen.';
$string['rule_loop_1_followup_list'] = '{$a->count} Aktivitäten hängen vom Test ab: {$a->items}.';
$string['rule_loop_1_success'] = 'Schleife-1-Muster erkannt: Wissensaufbau, nachgelagerter Test und abhängige Folgeaktivitäten.';

