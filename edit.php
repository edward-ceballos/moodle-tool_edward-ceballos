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

require_once(__DIR__ . '/../../../config.php');
require_once(__DIR__ .'/classes/form.php');
require_once(__DIR__ .'/lib.php');

defined('MOODLE_INTERNAL') || die;

$courseid = required_param('courseid', PARAM_INT);
$course = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);
require_login(null, false);
require_capability('tool/edward:edit', context_course::instance($courseid));
$context = context_course::instance($courseid);
$url = new moodle_url('/admin/tool/edward/edit.php');
$nexturl = new moodle_url('/admin/tool/edward/index.php', array('courseid' => $courseid));
$PAGE->set_url($url, array('courseid' => $courseid));
$PAGE->set_context(context_course::instance($courseid));
// $PAGE->set_pagelayout('report');
$PAGE->set_title(get_string('pluginname', 'tool_edward'));
$PAGE->set_heading(get_string('hello_world', 'tool_edward'));
$PAGE->set_context(context_course::instance($courseid));
$PAGE->set_course($course);

if (isset($_GET['edit'])) {

	$row = $DB->get_record('tool_edward', array('id' => trim($_GET['edit'])));

	if ($row && $row->courseid == trim($_GET['courseid'])) {

		$context = context_course::instance($courseid);

		$settingnode = $PAGE->settingsnav->add(get_string('pluginname', 'tool_edward'), new moodle_url('/admin/tool/edward/index.php', array('courseid' => $row->courseid)), navigation_node::TYPE_CONTAINER);

		$settingnode2 = $settingnode->add(get_string('edit', 'tool_edward'), new moodle_url('/admin/tool/edward/edit.php', array('courseid' => $row->courseid)), navigation_node::TYPE_CONTAINER);

		$form = new form();

		$descriptionoptions = array('trusttext'=>true, 'subdirs'=>true, 'maxfiles' => EDITOR_UNLIMITED_FILES, 'context' => $context);

		$row->description = file_prepare_standard_editor($row, 'description', $descriptionoptions, $context, 'tool_edward', 'img', $row->id);

		$draftitemid = file_get_submitted_draft_itemid('attachments');

		file_prepare_draft_area($draftitemid, $context->id, 'tool_edward', 'attachments', $row->id,
			array('subdirs' => 0, 'maxbytes' => EDITOR_UNLIMITED_FILES, 'maxfiles' => 50));

		$row->attachments = $draftitemid;
		
		$form->set_data($row);
		echo $OUTPUT->header();
		$form->display();
		echo $OUTPUT->footer();
	}else{
		redirect($nexturl);
	}
}
else{

	$settingnode = $PAGE->settingsnav->add(get_string('pluginname', 'tool_edward'), new moodle_url('/admin/tool/edward/index.php', array('courseid'=> $courseid)), navigation_node::TYPE_CONTAINER);

	$settingnode2 = $settingnode->add(get_string('add', 'tool_edward'), new moodle_url('/admin/tool/edward/edit.php', array('courseid'=> $courseid)), navigation_node::TYPE_CONTAINER);

	$descriptionoptions = array('trusttext'=>true, 'subdirs'=>true, 'maxfiles' => EDITOR_UNLIMITED_FILES, 'context' => $context);

	$form = new form();

	if ($form->is_cancelled()) {
		redirect($nexturl);
	} 
	else if ($froform = $form->get_data()) {
		
		if (isset($froform->id) && !empty($froform->id)) {

			$froform = file_postupdate_standard_editor($froform, 'description', $descriptionoptions, $context, 'tool_edward', 'img', $froform->id);

			file_save_draft_area_files($froform->attachments, $context->id, 'tool_edward', 'attachments',
				$froform->id, array('subdirs' => 0, 'maxbytes' => EDITOR_UNLIMITED_FILES, 'maxfiles' => 50));

			$fs = get_file_storage();
			$froform->timemodified = time();

			if (!isset($froform->completed)) {
				$froform->completed = 0;
			}

			$DB->update_record('tool_edward', $froform);
		}
		else{
			$froform->timecreated = time();

			$froform->id = $DB->insert_record('tool_edward', $froform, $returnid=true, $bulk=false);

			$froform = file_postupdate_standard_editor($froform, 'description', $descriptionoptions, $context, 'tool_edward', 'img', $froform->id);

			file_save_draft_area_files($froform->attachments, $context->id, 'tool_edward', 'attachments',
				$froform->id, array('subdirs' => 0, 'maxbytes' => EDITOR_UNLIMITED_FILES, 'maxfiles' => 50));

			$DB->update_record('tool_edward', $froform);
		}
		redirect($nexturl);
	} 
	else {
		echo $OUTPUT->header();
		$form->display();
		echo $OUTPUT->footer();
	}
}

