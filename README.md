# HTMX #

## What is HTMX? ##

The core functionality of HTMX:
- allows you to add hypermedia controls to all HTML elements
- allows hypermedia controls to reload parts of the page without a full page reload

If this is your first exposure to HTMX, I recommend reading the following articles:
- [HATEOS](https://htmx.org/essays/hateoas/)
- [Locality of Behavior](https://htmx.org/essays/locality-of-behavior/)
- [Hypermedia-Driven Applications](https://htmx.org/essays/hypermedia-driven-apps/)

And of course, the [HTMX documentation](https://htmx.org/docs/).

To see some examples of this plugin in action, check out the [demo branch](https://github.com/frederickOtus/moodle-local_htmx/tree/demos)

## What is this plugin? ##

This plugin:
- Loads the HTMX library onto every page
- Adds a modal popup for HTMX requests that return 4XX or 5XX errors
- Adds an opinionated way for responding to HTMX requests

## Installing via uploaded ZIP file ##

1. Log in to your Moodle site as an admin and go to _Site administration >
   Plugins > Install plugins_.
2. Upload the ZIP file with the plugin code. You should only be prompted to add
   extra details if your plugin type is not automatically detected.
3. Check the plugin validation report and finish the installation.

## Installing manually ##

The plugin can be also installed by putting the contents of this directory to

    {your/moodle/dirroot}/local/htmx

Afterwards, log in to your Moodle site as an admin and go to _Site administration >
Notifications_ to complete the installation.

Alternatively, you can run

    $ php admin/cli/upgrade.php

to complete the installation from the command line.

## License ##

2024 Peter Miller <pita.da.bread07@gmail.com>

This program is free software: you can redistribute it and/or modify it under
the terms of the GNU General Public License as published by the Free Software
Foundation, either version 3 of the License, or (at your option) any later
version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with
this program.  If not, see <https://www.gnu.org/licenses/>.
