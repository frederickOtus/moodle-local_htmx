<?php

namespace local_htmx\output;

use templatable;
use renderable;
use renderer_base;

class demo implements templatable, renderable {

    public function export_for_template(renderer_base $output)
    {
        return [

        ];
    }
}

