# Adaptive course audit

## English

### Audience
- Teachers and course owners who want quick, guided course quality checks.
- Requires the ability to manage activities in the course to start a review.

### What it does
- Adds an **Adaptive course audit** link to the course navigation.
- Runs an adaptive review that turns findings into a guided **User Tour** on your course page.
- First check (“Learn, then prove it”) looks for a learning path: content ➜ quiz ➜ follow-up activities that unlock after the quiz.
- Generates action tours that walk you through adding or configuring activities (e.g., create a quiz, set completion, add access restrictions).

### Requirements
- Moodle 4.5 or later.
- Plugin location: `local/adaptive_course_audit`.
- Capability: `local/adaptive_course_audit:view` to open the page. Starting a review also requires `moodle/course:manageactivities`.
- Uses Moodle’s built-in User Tours; they must be enabled.

### Installation (site admin)
1) Copy or deploy the plugin into `local/adaptive_course_audit` in your Moodle codebase.  
2) Visit **Site administration → Notifications** or run `php admin/cli/upgrade.php` to install.  
3) Purge caches if needed (`php admin/cli/purge_caches.php`).  
4) Grant `local/adaptive_course_audit:view` (and typically `moodle/course:manageactivities`) to the roles that should run reviews, e.g., Teacher/Manager.  
5) Ensure User Tours are not disabled globally.

### How to use (teachers/course owners)
1) Open your course and choose **Adaptive course audit** from the course navigation.  
2) Pick **Whole course** or a visible **Section** and press **Start review** (requires manage activities).  
3) You are sent back to the course page; a guided tour appears with findings and action buttons.  
4) Follow the steps to add or configure activities (e.g., add a quiz, enable completion, set access rules).  
5) Re-run the review after making changes to refresh the tips.

### What the first check looks for
- At least 3 visible activities in the section.  
- A quiz is present.  
- Preparatory/knowledge-building content before the quiz (e.g., Page, Book, File, Folder, URL, Forum, Wiki, Lesson, H5P/LTI content).  
- The quiz has an availability condition (e.g., depends on earlier work).  
- At least one follow-up activity depends on quiz completion.  
- If something is missing, the tour suggests fixes and can open guided action tours on the relevant page.

### Data and privacy
- Stores only a course-to-tour mapping in `local_adaptive_course_tour`; no personal data is stored.  
- Tours are filtered so only the user who started the review sees them.

### Troubleshooting
- If no tour appears, ensure you have the required capabilities, the section is visible, and User Tours are enabled. Clear caches if changes are not reflected.

---

## Deutsch

### Zielgruppe
- Lehrkräfte und Kursverantwortliche, die ihren Kurs schnell prüfen und verbessern möchten.
- Zum Starten einer Prüfung ist die Berechtigung zum Verwalten von Aktivitäten erforderlich.

### Funktionsumfang
- Fügt einen Link **Adaptive course audit** in der Kursnavigation hinzu.
- Startet eine adaptive Prüfung und zeigt die Ergebnisse als geführte **User Tour** direkt im Kurs an.
- Erste Prüfschleife („Lernen, dann zeigen“) sucht nach einem Lernpfad: Inhalte ➜ Quiz ➜ Folgeaktivitäten, die nach dem Quiz freigeschaltet werden.
- Erstellt Aktions-Touren, die durch das Anlegen/Konfigurieren von Aktivitäten führen (z. B. Quiz anlegen, Abschluss aktivieren, Zugriffsregeln setzen).

### Voraussetzungen
- Moodle 4.5 oder höher.
- Plugin-Pfad: `local/adaptive_course_audit`.
- Capability: `local/adaptive_course_audit:view`, zum Starten zudem `moodle/course:manageactivities`.
- Die Moodle User Tours müssen aktiviert sein.

### Installation (Site-Administration)
1) Plugin nach `local/adaptive_course_audit` im Moodle-Code kopieren.  
2) **Website-Administration → Mitteilungen** aufrufen oder `php admin/cli/upgrade.php` ausführen.  
3) Falls nötig Caches leeren (`php admin/cli/purge_caches.php`).  
4) Den Rollen (z. B. Trainer/Manager) `local/adaptive_course_audit:view` und in der Regel `moodle/course:manageactivities` zuweisen.  
5) Sicherstellen, dass User Tours global nicht deaktiviert sind.

### Nutzung (Lehrkräfte/Kursverantwortliche)
1) Kurs öffnen und in der Kursnavigation **Adaptive course audit** wählen.  
2) **Gesamten Kurs** oder einen sichtbaren **Abschnitt** auswählen und **Prüfung starten** (erfordert Aktivitätsverwaltung).  
3) Sie werden zurück zur Kursseite geleitet; eine geführte Tour mit Hinweisen erscheint.  
4) Den Schritten folgen, um Aktivitäten anzulegen oder zu konfigurieren (z. B. Quiz hinzufügen, Abschluss aktivieren, Zugriffsregeln setzen).  
5) Nach Änderungen die Prüfung erneut starten, um aktualisierte Hinweise zu erhalten.

### Was in der ersten Prüfung geprüft wird
- Mindestens 3 sichtbare Aktivitäten im Abschnitt.  
- Ein Quiz ist vorhanden.  
- Vor dem Quiz gibt es vorbereitende Inhalte (z. B. Textseite, Buch, Datei/Ordner, URL, Forum, Wiki, Lesson, H5P/LTI).  
- Das Quiz besitzt eine Zugriffsbedingung (abhängig von Vorleistungen).  
- Mindestens eine Folgeaktivität ist an den Quizabschluss gebunden.  
- Fehlt etwas, schlägt die Tour konkrete Schritte vor und kann Aktions-Touren auf der passenden Seite öffnen.

### Daten & Datenschutz
- Es wird nur eine Kurs-zu-Tour-Zuordnung in `local_adaptive_course_tour` gespeichert; keine personenbezogenen Daten.  
- Die Tour wird so gefiltert, dass nur die Person, die die Prüfung gestartet hat, sie sieht.

### Fehlerbehebung
- Wenn keine Tour erscheint: Berechtigungen prüfen, Abschnitt sichtbar schalten, User Tours aktivieren. Bei Bedarf Cache leeren.

