<?php

global $CFG;

require_once($CFG->dirroot . '/webservice/tests/helpers.php');

class tool_edward_generator extends component_generator_base {
    public function create_thing($thing) {
        global $DB;
        $DB->insert_record('tool_edward', $thing);
    }
}