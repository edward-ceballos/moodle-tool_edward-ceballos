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

defined('MOODLE_INTERNAL') || die;


if (isset($_GET['edit'])) {
	$row = $DB->get_record('tool_edward', array('id' => trim($_GET['edit'])));
	if ($row && $row->courseid == trim($_GET['id'])) {
	require_login(null, false);
	require_capability('tool/edward:edit', context_module::instance($PAGE->course->id));
	$url = new moodle_url('/admin/tool/edward/edit.php');
	$nexturl = new moodle_url('/admin/tool/edward/index.php');
	$PAGE->set_url($url, array('id' => $row->id));
	$PAGE->set_context(context_system::instance());
	$PAGE->set_pagelayout('report');
	$PAGE->set_title(get_string('pluginname', 'tool_edward'));
	$PAGE->set_heading(get_string('hello_world', 'tool_edward'));

	$settingnode = $PAGE->settingsnav->add(get_string('pluginname', 'tool_edward'), new moodle_url('/admin/tool/edward/index.php', array('id' => $row->id)), navigation_node::TYPE_CONTAINER);

	$settingnode2 = $settingnode->add(get_string('edit', 'tool_edward'), new moodle_url('/admin/tool/edward/edit.php', array('id' => $row->id)), navigation_node::TYPE_CONTAINER);
	
		$form = new form();

		$form->set_data($row);

		if ($form->is_cancelled()) {
			redirect($nexturl);
		} 
		else if ($froform = $form->get_data()) {

			// $froform->timecreated = time();
			$froform->timemodified = time();

			$DB->insert_record('tool_edward', $froform, $returnid=true, $bulk=false);
			redirect($nexturl);
		} 
		else {
			echo $OUTPUT->header();
			$form->display();
			echo $OUTPUT->footer();
		}
	}else{
		redirect($nexturl);
	}
}
else{
	require_login(null, false);
	require_capability('tool/edward:edit', context_module::instance($PAGE->course->id));
	$url = new moodle_url('/admin/tool/edward/edit.php');
	$nexturl = new moodle_url('/admin/tool/edward/index.php');
	$PAGE->set_url($url, array('id' => $PAGE->course->id));
	$PAGE->set_context(context_system::instance());
	$PAGE->set_pagelayout('report');
	$PAGE->set_title(get_string('pluginname', 'tool_edward'));
	$PAGE->set_heading(get_string('hello_world', 'tool_edward'));

	$settingnode = $PAGE->settingsnav->add(get_string('pluginname', 'tool_edward'), new moodle_url('/admin/tool/edward/index.php', array('id'=> $PAGE->course->id)), navigation_node::TYPE_CONTAINER);

	$settingnode2 = $settingnode->add(get_string('add', 'tool_edward'), new moodle_url('/admin/tool/edward/edit.php', array('id'=> $PAGE->course->id)), navigation_node::TYPE_CONTAINER);

	$form = new form();

	if ($form->is_cancelled()) {
		redirect($nexturl);
	} 
	else if ($froform = $form->get_data()) {

if (isset($froform->id) && !empty($froform->id)) {
	// $froform->timecreated = time();
	$froform->timemodified = time();

		$DB->update_record('tool_edward', $froform, $returnid=true, $bulk=false);
}
else{
	$froform->timecreated = time();
	// $froform->timemodified = time();

		$DB->insert_record('tool_edward', $froform, $returnid=true, $bulk=false);
}
		
		redirect($nexturl);
	} 
	else {
		echo $OUTPUT->header();
		$form->display();
		echo $OUTPUT->footer();
	}
}
