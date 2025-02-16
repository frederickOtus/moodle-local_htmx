<?php

namespace local_htmx\local;

use local_htmx\local\base_handler;
use core\output\named_templatable;
use renderable;
use renderer_base;

abstract class templateable_handler extends base_handler implements named_templatable, renderable {

    public function render() : string {
        global $PAGE;
        $renderer = $PAGE->get_renderer('core', null);

        $template = $this->get_template_name($renderer);
        $data = $this->export_for_template($renderer);
        return $renderer->render_from_template($template, $data);
    }

    /**
     *  Get name of template to load. By default, this be driven off class name.
     *  For example: component_foo\htmx\bar will load template component_foo\htmx\bar.
     *
     * @param renderer_base $renderer
     * @return string
     */
    public function get_template_name(renderer_base $renderer): string
    {
        return  str_replace('\\', '/', get_class($this));
    }
}
