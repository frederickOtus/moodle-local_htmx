<?php

namespace local_htmx\local;

use cm_info;
use context;

abstract class base_handler {

    /**
     * Controls the behavior of login_required. You can either change this in a subclass, or overload the method for more complex logic.
     * @var bool
     */
    const LOGIN_REQUIRED = true;

    /**
     * Controls the behavior of read_only_session. You can either change this in a subclass, or overload the method for more complex logic.
     * @var bool
     */
    const READ_ONLY_SESSION = true;

    /**
     * If the user must be logged in to access this endpoint.
     *
     * @return bool
     */
    public function login_required() : bool {
        return static::LOGIN_REQUIRED;
    }

    /**
     * If the session should be read only.
     *
     * @return bool
     */
    public function read_only_session() : bool {
        return static::READ_ONLY_SESSION;
    }

    /**
     * Capabilities that the user must have to access this endpoint.
     * Returning an empty list will avoid permission checking.
     *
     * Expects an array of string names of capabilities. They will be checked against
     * the context supplied by $this->get_context
     *
     * @return array
     */
    abstract public function required_capabilities() : array;

    /**
     * Used by the HTMX controller to pass to require_login.
     * You only really need this if you need the $PAGE->set_cm called.
     *
     * @return ?cm_info
     */
    abstract function get_cm() : ?cm_info;

    /**
     * Used by the HTMX controller to pass to require_login.
     *
     * @return int
     */
    abstract public function get_course_id() : int;

    /**
     * Render output for this handler.
     * 
     * @return string
     */
    abstract public function render() : string;

    /**
     * Returns the context for this handler.
     *
     * @return \context
     */
    abstract public function get_context() : context;


    /**
     * Attempts to autoload a handler class based on the path_info.
     */
    public static function get_instance($path_info) : ?base_handler {
        $parts = explode('/', $path_info);
        if(sizeof($parts) < 3) {
            return null;
        }

        $component = $parts[1];
        $name = array_slice($parts, 2);
        $fullname = "$component\\htmx\\" . implode('\\', $name);

        if(!class_exists($fullname)) {
            return null;
        }else {
            $handler = new $fullname();
            return $handler;
        }
    }
}
