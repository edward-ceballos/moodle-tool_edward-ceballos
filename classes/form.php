<?php 
// This file is part of Moodle - http://moodle.org/
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
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package    tool
 * @subpackage edward
 * @copyright  2020 Edward Ceballos <info@edwardceballos.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once("$CFG->libdir/formslib.php");
 
class form extends moodleform {
    //Add elements to form
    public function definition() {

        // $repeatarray = array();
        // $repeatarray[] = $form->createElement('text', 'limit', get_string('limitno', 'choice'));
 
        // if (@$this->_instance){
        //     $repeatno = $DB->count_records('choice_options', array('choiceid'=>$this->_instance));
        //     $repeatno += 2;
        // } else {
        //     $repeatno = 2;
        // }
 
        // $repeateloptions = array();
        // $repeateloptions['limit']['default'] = 0;
        // $repeateloptions['limit']['disabledif'] = array('limitanswers', 'eq', 0);
        // $repeateloptions['limit']['rule'] = 'numeric';
        // $repeateloptions['limit']['type'] = PARAM_INT;
 
        // $repeateloptions['limit']['helpbutton'] = array('choiceoptions', 'choice');
 
        // $this->repeat_elements($repeatarray, $repeatno,
        //             $repeateloptions, 'option_repeats', 'option_add_fields', 1, null, true);

        global $CFG, $PAGE;
 
        $form = $this->_form;

        $form->addElement('header', null, get_string('pluginname', 'tool_edward'));
        $form->addElement('text', 'name', get_string('name'), array('size' => '100%', 'maxlength' => 255));
        $form->setType('name', PARAM_MULTILANG);

        $form->addRule('name', get_string('required'), 'required', '', 'client');

        $form->addElement('checkbox', 'completed', get_string('status'));
        $form->setType('completed', PARAM_NOTAGS);
        $form->addHelpButton('completed', 'completed', 'tool_edward');

        $form->addElement('hidden', 'courseid', $PAGE->course->id);
        $form->setType('courseid', PARAM_NOTAGS);

        $form->addElement('hidden', 'id', NUll);
        $form->setType('id', PARAM_NOTAGS);

        $this->add_action_buttons();
    }
    //Custom validation should be added here
    function validation($data, $files) {

        var_dump($data);
        global $DB;
        $errors = array();

if (isset($data['id']) && empty($data['id']) || !isset($data['id'])) {

    $name = $DB->get_record('tool_edward', array('name' => $data['name']));

        if (!empty($name)) {
            $errors['name'] = get_string('error_name',  'tool_edward', $name->name);
        }
}
        
 
        return $errors;
    }
}