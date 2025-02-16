<?php

// Default to no session locks for HTMX requests.
define('READ_ONLY_SESSION', true);
define('HTMX_REQUEST', true);

/**
* By default, moodle's paramter functions ONLY work for get and post paramters.
* So, we can hack around this by deletcting the delete and put requests
* that we want to support and manually decoding the request body and storing
* it in $_POST where Moodle will find it.
*/
if($_SERVER['REQUEST_METHOD'] == 'DELETE' || $_SERVER['REQUEST_METHOD'] == 'PUT') {
    $data = [];
    if($_SERVER['CONTENT_TYPE'] == 'application/x-www-form-urlencoded') {
        parse_str(file_get_contents("php://input"), $data);
    }else if($_SERVER['CONTENT_TYPE'] == 'application/json') {
        $data = json_decode(file_get_contents("php://input"), true);
    }

    if(is_array($data) && !empty($data)) {
        $_POST = $data;
    }
}

require_once('../../config.php');

// Extract out correct HTMX object:
$pathinfo = $_SERVER['PATH_INFO'];
try{
    $htmx_handler = \local_htmx\local\base_handler::get_instance($pathinfo);

    if(empty($htmx_handler)) {
        header("HTTP/1.0 404 Not Found");
        exit();
    }

    if(!$htmx_handler->read_only_session()) {
        \core\session\manager::restart_with_write_lock(false);
    }

    $url = new moodle_url($SCRIPT . $pathinfo);

    $PAGE->set_context($htmx_handler->get_context());
    $PAGE->set_url($url);

    if($htmx_handler->login_required()) {
        $course = $htmx_handler->get_course_id();
        $cm = $htmx_handler->get_cm();
        try {
            require_login(
                courseorid: $course,
                autologinguest: false,
                cm: $cm,
                preventredirect: true,
            );

            $context = $htmx_handler->get_context();
            foreach($htmx_handler->required_capabilities() as $cap) {
                require_capability($cap, $context);
            }
        }catch(moodle_exception | required_capability_exception $e) {
            header("HTTP/1.0 403 Forbidden");
            exit();
        }
    }else {
        if(!empty($htmx_handler->required_capabilities())) {
            throw new coding_exception("Handler has required capabilities but login is not required.");
        }
    }

    echo $htmx_handler->render();
}catch(Throwable $e){
    header("HTTP/1.0 500 Internal Server Error");
    $debug = false;
    if (!empty($CFG->debugusers) && $USER) {
        $debugusers = explode(',', $CFG->debugusers);
        $debug = in_array($USER->id, $debugusers);
    }

    $debug = $debug || (!empty($CFG->debug) && $CFG->debug > 0);

    if($debug) {
        echo $e->getFile() . ": " . $e->getLine() . "\n";
        echo $e->getMessage() . "\n";
        $extra_properties = [
            'errorcode',
            'module',
            'debuginfo',
        ];

        if(is_object($e)) {
            foreach($extra_properties as $prop) {
                if(property_exists($e, $prop)) {
                    echo "$prop: " . $e->$prop . "\n";
                }
            }
        }

        echo "\n";
        echo $e->getTraceAsString();
    }
}
