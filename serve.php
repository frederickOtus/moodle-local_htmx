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

/**
 * Script to handle all incoming HTMX requests.
 *
 * @package    local_htmx
 * @copyright  2025 Peter Miller <pita.da.bread07@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Default to no session locks for HTMX requests.
define('READ_ONLY_SESSION', true);
define('HTMX_REQUEST', true);

// By default, moodle's paramter functions ONLY work for get and post paramters.
// So, we can hack around this by deletcting the delete and put requests
// that we want to support and manually decoding the request body and storing
// it in $_POST where Moodle will find it.
if ($_SERVER['REQUEST_METHOD'] == 'DELETE' || $_SERVER['REQUEST_METHOD'] == 'PUT') {
    $data = [];
    if ($_SERVER['CONTENT_TYPE'] == 'application/x-www-form-urlencoded') {
        parse_str(file_get_contents("php://input"), $data);
    } else if ($_SERVER['CONTENT_TYPE'] == 'application/json') {
        $data = json_decode(file_get_contents("php://input"), true);
    }

    if (is_array($data) && !empty($data)) {
        $_POST = $data;
    }
}

require_once('../../config.php');

// Extract out correct HTMX object.
$pathinfo = $_SERVER['PATH_INFO'];
try {
    $htmxhandler = \local_htmx\handlers\base::get_instance($pathinfo);

    if (empty($htmxhandler)) {
        header("HTTP/1.0 404 Not Found");
        exit();
    }

    if (!$htmxhandler->read_only_session()) {
        \core\session\manager::restart_with_write_lock(false);
    }

    $url = new moodle_url($SCRIPT . $pathinfo);

    $PAGE->set_context($htmxhandler->get_context());
    $PAGE->set_url($url);

    if ($htmxhandler->login_required()) {
        $course = $htmxhandler->get_course_id();
        $cm = $htmxhandler->get_cm();
        try {
            require_login(
                courseorid: $course,
                autologinguest: false,
                cm: $cm,
                preventredirect: true,
            );

            $context = $htmxhandler->get_context();
            foreach ($htmxhandler->required_capabilities() as $cap) {
                require_capability($cap, $context);
            }
        } catch (moodle_exception | required_capability_exception $e) {
            header("HTTP/1.0 403 Forbidden");
            exit();
        }
    } else {
        if (!empty($htmxhandler->required_capabilities())) {
            throw new coding_exception("Handler has required capabilities but login is not required.");
        }
    }

    if ($htmxhandler::INCLUDE_REQUIRED_JS) {
        // Call the header() method to force the page to set up its requirements manager.
        $OUTPUT->header();
        $PAGE->start_collecting_javascript_requirements();
        $html = $htmxhandler->render();
        $jsfooter = $PAGE->requires->get_end_code();
        echo $OUTPUT->render_from_template('local_htmx/html_with_required_js', [
            'html' => $html,
            'js' => $jsfooter,
        ]);
    } else {
        echo $htmxhandler->render();
    }
} catch (Throwable $e) {
    header("HTTP/1.0 500 Internal Server Error");
    $debug = false;
    if (!empty($CFG->debugusers) && $USER) {
        $debugusers = explode(',', $CFG->debugusers);
        $debug = in_array($USER->id, $debugusers);
    }

    $debug = $debug || (!empty($CFG->debug) && $CFG->debug > 0);

    if ($debug) {
        echo $e->getFile() . ": " . $e->getLine() . "\n";
        echo $e->getMessage() . "\n";
        $extraproperties = [
            'errorcode',
            'module',
            'debuginfo',
        ];

        if (is_object($e)) {
            foreach ($extraproperties as $prop) {
                if (property_exists($e, $prop)) {
                    echo "$prop: " . $e->$prop . "\n";
                }
            }
        }

        echo "\n";
        echo $e->getTraceAsString();
    }
}
