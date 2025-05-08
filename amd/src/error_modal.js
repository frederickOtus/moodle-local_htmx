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
 * Modal triggered on HTMX error responses.
 *
 * @module     local_htmx/error_modal
 * @copyright  2025 Peter Miller <pita.da.bread07@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import ModalFactory from 'core/modal_factory';
import {get_string as getString} from 'core/str';

export const init = async () => {
    const modal = await ModalFactory.create({
        type: ModalFactory.types.CANCEL,
        title: getString('errormodal:title', 'local_htmx'),
        body: getString('errormodal:body', 'local_htmx'),
    });

    htmx.on("htmx:responseError", function (evt) {
        modal.show();
        window.console.error("Error response", evt);
    });

    document.body.dispatchEvent(new CustomEvent('local_htmx:errorModalReady'));
};
