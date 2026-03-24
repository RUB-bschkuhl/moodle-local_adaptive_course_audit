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
 * @copyright  2026 Moodle HQ
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
