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
 * Tour launcher for the adaptive course audit plugin.
 *
 * Detects the `startacatour` URL parameter and starts the specified tour
 * programmatically, bypassing the normal Moodle tour matching which only
 * starts the first matching tour per page.
 *
 * Delegates to tool_usertours/usertours init() so that all event handling
 * (markStepShown, markTourComplete, reset link, resize) is handled by
 * Moodle core.
 *
 * @module     local_adaptive_course_audit/tour_launcher
 * @copyright  2026 Bastian Schmidt-Kuhl <bastian.schmidt-kuhl@ruhr-uni-bochum.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
import {init as usertourInit} from 'tool_usertours/usertours';

/**
 * Extract and remove the startacatour parameter from the URL.
 *
 * @returns {number|null} The tour ID, or null if not present.
 */
const extractTourIdFromUrl = () => {
    const urlParams = new URLSearchParams(window.location.search);
    const raw = urlParams.get('startacatour');
    if (!raw) {
        return null;
    }

    const parsed = parseInt(raw, 10);
    if (isNaN(parsed) || parsed <= 0) {
        return null;
    }

    // Remove the parameter from the address bar without reloading.
    urlParams.delete('startacatour');
    const search = urlParams.toString();
    const cleaned = window.location.pathname + (search ? '?' + search : '') + window.location.hash;
    window.history.replaceState(null, '', cleaned);

    return parsed;
};

/**
 * Entry point: detect URL parameter and launch tour after a short delay.
 * @param {number} continueTour Optional tour ID to continue after completing the current one (for subtours).
 */
export const init = (continueTour = 0) => {
    let id = extractTourIdFromUrl();
    if (!id) {
        if (continueTour == 0) {
        return;
        }
        id = continueTour;
    }

    const isModEdit = window.location.pathname.indexOf('/course/modedit.php') !== -1;

    const startTour = () => {
        try {
            // On modedit.php, expand the relevant collapsible sections after the tour has started,
            // then trigger a few resizes so tool_usertours recalculates placement.
            if (isModEdit && typeof window.require === 'function') {
                window.setTimeout(() => {
                    window.require(['local_adaptive_course_audit/modedit_expander'], (expander) => {
                        try {
                            if (expander && typeof expander.init === 'function') {
                                expander.init();
                            }
                        } catch (error) {
                            window.console.error('[ACA tour_launcher] Expander error', error);
                        }
                    });
                }, 1500);
            }

            usertourInit([{tourId: id, startTour: true, filtervalues: {cssselector: []}}], ['tool_usertours/filter_cssselector']);
        } catch (error) {
            // If this fails, the usertour backdrop can appear without a step.
            window.console.error('[ACA tour_launcher] Failed to start tour', error);
        }
    };

    // Short delay so the normal Moodle bootstrap can run first;
    // usertourInit() will end any competing tour before starting ours.
    window.setTimeout(startTour, 500);
};

const NEXT_AFTER_SAVE_KEY = 'aca_next_after_save';

/**
 * On modedit.php: attach a submit listener to the modedit form that stashes
 * a {sectionid, tourid, courseid} follow-up target only when the user
 * actually submits (not when they cancel). The stash is consumed on the
 * next course view to redirect to the section edit page.
 *
 * @param {number} sectionid
 * @param {number} tourid
 * @param {number} courseid
 */
export const stashAfterSave = (sectionid, tourid, courseid) => {
    const writeStash = () => {
        try {
            window.sessionStorage.setItem(NEXT_AFTER_SAVE_KEY, JSON.stringify({
                sectionid: parseInt(sectionid, 10),
                tourid: parseInt(tourid, 10),
                courseid: parseInt(courseid, 10),
            }));
        } catch (error) {
            window.console.error('[ACA tour_launcher] Failed to stash next-after-save', error);
        }
    };

    const attach = () => {
        const form = document.querySelector('form.mform');
        if (!form) {
            return false;
        }
        // Only stash when the form is actually submitted, not when Cancel is clicked.
        // Moodle's cancel button triggers a native form reset/navigation via its own handler
        // that does not fire 'submit'.
        form.addEventListener('submit', writeStash);
        return true;
    };

    if (!attach()) {
        // DOM may not be ready yet.
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', attach, {once: true});
        }
    }
};

/**
 * On course/view.php: if a stash exists for this course, consume it and
 * redirect to the section edit page with the stored tour id.
 *
 * @param {number} courseid
 */
export const consumeAfterSave = (courseid) => {
    let payload;
    try {
        const raw = window.sessionStorage.getItem(NEXT_AFTER_SAVE_KEY);
        if (!raw) {
            return;
        }
        payload = JSON.parse(raw);
    } catch (error) {
        window.console.error('[ACA tour_launcher] Failed to read next-after-save stash', error);
        return;
    }

    const sectionid = parseInt(payload && payload.sectionid, 10);
    const tourid = parseInt(payload && payload.tourid, 10);
    const stashedcourseid = parseInt(payload && payload.courseid, 10);
    if (!sectionid || !tourid || !stashedcourseid) {
        return;
    }
    if (parseInt(courseid, 10) !== stashedcourseid) {
        return;
    }

    try {
        window.sessionStorage.removeItem(NEXT_AFTER_SAVE_KEY);
    } catch (error) {
        window.console.error('[ACA tour_launcher] Failed to clear next-after-save stash', error);
    }

    const base = (window.M && window.M.cfg && window.M.cfg.wwwroot) ? window.M.cfg.wwwroot : '';
    const url = base + '/course/editsection.php'
        + '?id=' + encodeURIComponent(sectionid)
        + '&startacatour=' + encodeURIComponent(tourid)
        + '&acaexpand=1';
    window.location.replace(url);
};
