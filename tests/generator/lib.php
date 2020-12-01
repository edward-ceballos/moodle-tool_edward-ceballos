<?php

class tool_edward_generator extends component_generator_base {
    public function create_thing($thing) {
        global $DB;
        $DB->insert_record('tool_edward_things', $thing);
    }
}