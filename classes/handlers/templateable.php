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

namespace local_htmx\handlers;

use core\output\named_templatable;
use core\output\renderable;
use core\output\renderer_base;

/**
 * Renderable and templatable HTMX request handler.
 *
 * @package    local_htmx
 * @copyright  2025 Peter Miller <pita.da.bread07@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
abstract class templateable extends base implements named_templatable, renderable {

    /**
     * Render HTMX response.
     *
     * @return string
     */
    public function render(): string {
        global $PAGE;
        $renderer = $PAGE->get_renderer('core', null);

        $template = $this->get_template_name($renderer);
        $data = $this->export_for_template($renderer);
        return $renderer->render_from_template($template, $data);
    }

    /**
     *  Get name of template to load. By default, this be driven off class name.
     *  For example: component_foo\htmx\bar will load template component_foo/htmx/bar.
     *
     * @param renderer_base $renderer
     * @return string
     */
    public function get_template_name(renderer_base $renderer): string {
        return  str_replace('\\', '/', get_class($this));
    }
}
