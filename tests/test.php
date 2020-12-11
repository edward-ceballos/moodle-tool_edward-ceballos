<?php

defined('MOODLE_INTERNAL') || die();

global $CFG;

require_once($CFG->dirroot . '/webservice/tests/helpers.php');

// namespace tool_edward; // Optional, but recommended.
 
class tool_edward_some_permission_testcase extends advanced_testcase {
    public function test_view() {
        global $DB;

        $this->setAdminUser();
        $this->resetAfterTest(true);

        $user = $this->getDataGenerator()->create_user();
        $course1 = $this->getDataGenerator()->create_course();
        $monitorgenerator = $this->getDataGenerator();
        $data = array('courseid' => $course1->id, 'name' => 'Primer entrada', 'completed' => 1, 'priority' => 1, 'timecreated' => time(), 'timemodified' => NULL);
         $generator = $this->getDataGenerator()->get_plugin_generator('tool_edward')->create_thing($data);
    }
}