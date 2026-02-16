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
 */
export const init = () => {
    const id = extractTourIdFromUrl();
    if (!id) {
        return;
    }

    // Short delay so the normal Moodle bootstrap can run first;
    // usertourInit() will end any competing tour before starting ours.
    window.setTimeout(() => {
        usertourInit([{tourId: id, startTour: true, filtervalues: {}}], []);
    }, 500);
};
