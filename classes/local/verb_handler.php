<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

namespace local_htmx\local;

use core\exception\coding_exception;
use core\output\renderer_base;
use stdClass;

/**
 * HTMX request handler made to process HTTP verbs.
 *
 * @package    local_htmx
 * @copyright  2025 Peter Miller <pita.da.bread07@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
abstract class verb_handler extends templateable_handler {

    /**
     * GET endpoint.
     *
     * @return stdClass
     */
    public function get(): stdClass {
        throw new coding_exception('Not implemented');
    }

    /**
     * POST endpoint.
     *
     * @return stdClass
     */
    public function post(): stdClass {
        throw new coding_exception('Not implemented');
    }

    /**
     * PUT endpoint.
     *
     * @return stdClass
     */
    public function put(): stdClass {
        throw new coding_exception('Not implemented');
    }

    /**
     * DELETE endpoint.
     *
     * @return stdClass
     */
    public function delete(): stdClass {
        throw new coding_exception('Not implemented');
    }

    /**
     * Get context from available HTTP method handler.
     *
     * @param renderer_base $output
     * @return stdClass
     */
    public function export_for_template(renderer_base $output): stdClass {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            return $this->get();
        } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->post();
        } else if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            return $this->put();
        } else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            return $this->delete();
        }
    }
}
