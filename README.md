[![Intro](pix/02_Intro_Katze_transparent.gif)](pix/02_Intro_Katze_transparent.gif)

# Adaptives Kurs-Audit

**Machen Sie aus Ihrem Moodle-Kurs ein durchdachtes Lernumfeld:** Statt abstrakter Checklisten bekommen Sie eine geführte Tour direkt auf der Kursseite – mit klaren nächsten Schritten zur adaptiven Gestaltung. Hier die kompakte Übersicht für `local/adaptive_course_audit` (Moodle **4.5+**).

## Materialien

Die Hinweise in den Touren beziehen sich u. a. auf diese PDFs im Ordner [`files/`](files/):

- [Leitfaden Adaptive Lehre (PDF)](files/Leitfaden_Adaptive_Lehre.pdf)
- [Adaptive Kursgestaltung in Moodle (PDF)](files/Adaptive_Kursgestaltung_in_Moodle.pdf)

## Möglichkeiten

- **Kursnavigation:** Eintrag **„Kurs prüfen“** zur Audit-Seite; optional **„Letzte Prüfung fortsetzen“**, wenn Sie bereits eine Prüfung gestartet haben.
- **Adaptive Prüfung** für den **ganzen Kurs** oder einen **Abschnitt:** Ergebnisse als **User Tour** auf der Kursseite, inklusive geführter Schritte zu Einstellungen. Es wird nichts automatisch geändert.
- **Geführte Hilfe** je **sichtbarem Quiz** in einem Abschnitt: kompakte Tour durch zentrale Test-Einstellungen für adaptive Lehre.
- **Drei Szenario-Touren:** Aufbauhilfen für einen **minimalistischen** adaptiven Abschnitt, einen **sequenziellen** Gesamtkurs oder ein **Kompass**-Modell mit freier Themenreihenfolge.

Kern der inhaltlichen Prüfung ist u. a. das Muster **„Erst lernen, dann nachweisen“** (Inhalte → Test → Folgeaktivitäten über Voraussetzungen/Abschluss), ergänzt um weitere Kurs- und Testregeln.

## Voraussetzungen

- **User Tours** müssen aktiviert sein.
- `local/adaptive_course_audit:view` für die Seite; zum Starten von Prüfungen und Szenarien zusätzlich `moodle/course:manageactivities`.

## Installation

1. Plugin nach `local/adaptive_course_audit` kopieren.  
2. **Website-Administration → Mitteilungen** oder `php admin/cli/upgrade.php`.  
3. Passende Rollen mit den Capabilities versehen; bei Bedarf Caches leeren.

Bei Problemen: Berechtigungen, sichtbare Abschnitte und User Tours prüfen.
