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
 * Expands relevant collapsible fieldsets on modedit.php.
 *
 * This ensures usertour step targeting works even when settings sections are collapsed.
 *
 * @module     local_adaptive_course_audit/modedit_expander
 * @copyright  2026 Moodle HQ
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Expand the closest collapsible fieldset for an element.
 *
 * @param {Element} el
 */
const expandClosestCollapsibleFieldset = (el) => {
    const fieldset = el.closest('fieldset.collapsible');
    if (!fieldset) {
        return;
    }

    const toggler = fieldset.querySelector('a.fheader[aria-controls]');
    if (!toggler) {
        return;
    }

    // Moodle form headers use Bootstrap-style collapse where the real state lives on the
    // controlled container div (e.g. #id_timingcontainer) rather than on the fieldset.
    const controlsId = toggler.getAttribute('aria-controls');
    const container = controlsId ? document.getElementById(controlsId) : null;

    if (container && !container.classList.contains('show')) {
        // Force expanded state without relying on Bootstrap JS being ready.
        container.classList.add('show');
        container.classList.remove('collapsing');
        container.style.height = '';

        toggler.classList.remove('collapsed');
        toggler.setAttribute('aria-expanded', 'true');
        fieldset.classList.remove('collapsed');
        return;
    }

    // Fallback: if we couldn't resolve the container, try the native click.
    const expanded = toggler.getAttribute('aria-expanded');
    if (expanded === 'false') {
        toggler.click();
    }
};

const expandRelevantFieldsets = () => {
    const selectors = [
        '#id_preferredbehaviour',
        '#id_attempts',
        '#id_completion_2',
        '#fitem_id_completion',
        '#id_grademethod',
        '#id_attemptimmediately',
        '[id^="fitem_id_feedbacktext"]',
        '#id_timeopen',
        '#id_quizpassword',
    ];

    selectors.forEach((selector) => {
        const el = document.querySelector(selector);
        if (!el) {
            return;
        }
        expandClosestCollapsibleFieldset(el);
    });
};

export const init = () => {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            expandRelevantFieldsets();
            window.setTimeout(expandRelevantFieldsets, 150);
        });
        return;
    }

    expandRelevantFieldsets();
    window.setTimeout(expandRelevantFieldsets, 150);
};

