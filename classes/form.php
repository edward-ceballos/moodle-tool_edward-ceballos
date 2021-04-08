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

        global $CFG;
        $courseid  = optional_param('courseid', null, PARAM_INT);

        $form = $this->_form;
        $context = context_course::instance($courseid);
        $descriptionoptions = array('trusttext'=>true, 'subdirs'=>true, 'maxfiles' => EDITOR_UNLIMITED_FILES, 'context'=>$context);
        // echo "<pre>";
        // var_dump($this);
        // $descriptionoptions = $this->_customdata['descriptionoptions'];

        $form->addElement('header', null, get_string('pluginname', 'tool_edward'));
        $form->addElement('text', 'name', get_string('name'), array('size' => '100%', 'maxlength' => 255));
        $form->setType('name', PARAM_MULTILANG);

        $form->addRule('name', get_string('required'), 'required', '', 'client');

        $form->addElement('checkbox', 'completed', get_string('status'));
        $form->setType('completed', PARAM_NOTAGS);
        $form->addHelpButton('completed', 'completed', 'tool_edward');

        $form->addElement('hidden', 'courseid', $courseid);
        $form->setType('courseid', PARAM_NOTAGS);

        $form->addElement('hidden', 'id', NUll);
        $form->setType('id', PARAM_NOTAGS);

        $form->addElement('editor', 'description_editor', get_string('description', 'tool_edward'), null, $descriptionoptions);
        $form->setType('description_editor', PARAM_RAW);
        $form->addRule('description_editor', get_string('required'), 'required', null, 'client');

        $form->addElement('filemanager', 'attachments', get_string('attachments', 'tool_edward'), null,
            array('subdirs' => 0, 'maxbytes' => EDITOR_UNLIMITED_FILES, 'areamaxbytes' => 10485760, 'maxfiles' => 50,
              'accepted_types' => array('document'), 'return_types'=> FILE_INTERNAL | FILE_EXTERNAL));

        $this->add_action_buttons();
    }


    function data_preprocessing(&$default_values) {
        if ($this->current->instance) {
            // Editing existing instance - copy existing files into draft area.
            $draftitemid = file_get_submitted_draft_itemid('attachments');
            file_prepare_draft_area($draftitemid, $this->context->id, 'tool_edward', 'attachments', 0, array('subdirs'=>0, 'maxbytes' => 0, 'maxfiles' => 1, 'mainfile' => true));
            $default_values['attachments'] = $draftitemid;
        }
    }
    
    //Custom validation should be added here
    function validation($data, $files) {

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