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

namespace local_htmx\hook;

/**
 * Class before_standard_head_html_generation
 *
 * @package    local_htmx
 * @copyright  2025 Sam Smucker <sam.smucker@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class before_standard_head_html_generation {
    /**
     * Require HTMX library JS.
     */
    public static function inject_htmx(): void {
        global $PAGE;
        $PAGE->requires->js(new \moodle_url('/local/htmx/htmx/htmx.min.js'), inhead: true);
        $PAGE->requires->js(new \moodle_url('/local/htmx/htmx/htmxUtils.js'), inhead: true);
        $PAGE->requires->js_call_amd('local_htmx/error_modal', 'init');
    }
}
