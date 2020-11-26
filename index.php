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

require_login(null, false);
require_capability('tool/edward:view', context_module::instance($PAGE->course->id));

$url = new moodle_url('/admin/tool/edward/index.php');
$PAGE->set_url($url, array('id' => $PAGE->course->id));
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('report');
$PAGE->set_title(get_string('pluginname', 'tool_edward'));
$PAGE->set_heading(get_string('hello_world', 'tool_edward'));

$settingnode = $PAGE->settingsnav->add(get_string('pluginname', 'tool_edward'), new moodle_url('/admin/tool/edward/index.php', array('id'=> $PAGE->course->id)), navigation_node::TYPE_CONTAINER);

$count_user = $DB->count_records('user');

echo $OUTPUT->header();

echo html_writer::div(
	get_string('hello_world', 'tool_edward'), 
	'multilang', 
	array('id' => $PAGE->course->id, 'lang' => current_language())
); 

echo html_writer::div(
	get_string('text_count_users', 'tool_edward', $count_user), 
	'multilang', 
	array('id' => $PAGE->course->id, 'lang' => current_language())
); 

if (has_capability('tool/edward:edit', context_system::instance())){
	echo html_writer::link(
		new moodle_url('/admin/tool/edward/edit.php'),
		get_string('add', 'tool_edward')
	); 
}

if (!class_exists('mod_forum_some_class')) {
  require_once(__DIR__ . '/classes/data.php');
}

$data = new data();
$table = $data->get_data($PAGE->course->id);

echo html_writer::table($table);

echo $OUTPUT->footer();