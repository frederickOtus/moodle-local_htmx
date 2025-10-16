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
 * Process HTMX attributes on content added to the DOM after the
 * initial page load. This will process anything that dispatches
 * a filterContentUpdated event.
 *
 * @module     local_htmx/process_new_content
 * @copyright  2025 Sam Smucker
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import { eventTypes } from 'core_filters/events';

export const init = () => {
    document.addEventListener(eventTypes.filterContentUpdated, event => {
        if (typeof htmx === 'undefined') {
            return;
        }
        event.detail.nodes.forEach(node => {
            htmx.process(node);
        });
    });
};