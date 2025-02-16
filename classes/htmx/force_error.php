<?php

namespace local_htmx\htmx;

use local_htmx\local\templateable_handler;
use cm_info;
use context;
use context_system;
use renderer_base;

class force_error extends templateable_handler {
    public function required_capabilities(): array
    {
        return [];
    }

    public function get_cm(): ?cm_info
    {
       return null; 
    }

    public function get_context(): context {
        return context_system::instance();
    }

    public function get_course_id(): int {
        return SITEID;
    }

    public function export_for_template(renderer_base $output)
    {
        throw new \Exception('Forced error');
    }
}
