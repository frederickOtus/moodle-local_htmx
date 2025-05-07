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

use cm_info;
use core\context;

/**
 * Base HTMX request handler.
 *
 * @package    local_htmx
 * @copyright  2025 Peter Miller <pita.da.bread07@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
abstract class base_handler {

    /**
     * Controls the behavior of login_required. You can either change this in a subclass
     * or overload the method for more complex logic.
     *
     * @var bool
     */
    const LOGIN_REQUIRED = true;

    /**
     * Controls the behavior of read_only_session. You can either change this in a subclass
     * or overload the method for more complex logic.
     *
     * @var bool
     */
    const READ_ONLY_SESSION = true;

    /**
     * If the user must be logged in to access this endpoint.
     *
     * @return bool
     */
    public function login_required(): bool {
        return static::LOGIN_REQUIRED;
    }

    /**
     * If the session should be read only.
     *
     * @return bool
     */
    public function read_only_session(): bool {
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
    abstract public function required_capabilities(): array;

    /**
     * Used by the HTMX controller to pass to require_login.
     * You only really need this if you need the $PAGE->set_cm called.
     *
     * @return ?cm_info
     */
    abstract public function get_cm(): ?cm_info;

    /**
     * Used by the HTMX controller to pass to require_login.
     *
     * @return int
     */
    abstract public function get_course_id(): int;

    /**
     * Render output for this handler.
     *
     * @return string
     */
    abstract public function render(): string;

    /**
     * Returns the context for this handler.
     *
     * @return \context
     */
    abstract public function get_context(): context;

    /**
     * Attempts to autoload a handler class based on the path_info.
     *
     * @param string $pathinfo
     * @return ?base_handler
     */
    public static function get_instance($pathinfo): ?base_handler {
        $parts = explode('/', $pathinfo);
        if (count($parts) < 3) {
            return null;
        }

        $component = $parts[1];
        $name = array_slice($parts, 2);
        $fullname = "$component\\htmx\\" . implode('\\', $name);

        if (!class_exists($fullname)) {
            return null;
        } else {
            $handler = new $fullname();
            return $handler;
        }
    }
}
