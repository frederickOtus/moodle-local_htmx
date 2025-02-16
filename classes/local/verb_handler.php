<?php

namespace local_htmx\local;

use coding_exception;
use renderer_base;
use stdClass;

abstract class templateable_handler extends templateable_handler {

    public function get() : stdClass {
        throw new coding_exception('Not implemented');
    }

    public function post() : stdClass {
        throw new coding_exception('Not implemented');
    }

    public function put() : stdClass {
        throw new coding_exception('Not implemented');
    }

    public function delete() : stdClass {
        throw new coding_exception('Not implemented');
    }

    public function export_for_template(renderer_base $output)
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET') {
            return $this->get();
        } else if($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->post();
        } else if($_SERVER['REQUEST_METHOD'] === 'PUT') {
            return $this->put();
        } else if($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            return $this->delete();
        }
    }
}
