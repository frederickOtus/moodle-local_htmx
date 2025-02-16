<?php 

function local_htmx_before_standard_html_head() {
        global $PAGE;
    $PAGE->requires->js(new moodle_url('/local/htmx/htmx/htmx.min.js'));
    $PAGE->requires->js_call_amd('local_htmx/error_modal', 'init');
}
