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
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->dirroot.'/'.$CFG->admin.'/tool/edward/lib.php');
require_login(null, false);

$courseid = required_param('courseid', PARAM_INT);
$course = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);

$url = new moodle_url('/admin/tool/edward/index.php');
$PAGE->set_url($url, array('courseid' => $courseid));
$PAGE->set_context(context_course::instance($courseid));
$PAGE->set_course($course);
$PAGE->set_title(get_string('pluginname', 'tool_edward'));
$PAGE->set_heading(get_string('hello_world', 'tool_edward'));

$count_user = $DB->count_records('user');

echo $OUTPUT->header();

echo html_writer::div(
	get_string('hello_world', 'tool_edward'), 
	'multilang', 
	array('id' => $courseid, 'lang' => current_language())
); 

echo html_writer::div(
	get_string('text_count_users', 'tool_edward', $count_user), 
	'multilang', 
	array('id' => $courseid, 'lang' => current_language())
); 

if (has_capability('tool/edward:edit', context_course::instance($courseid))){
	echo html_writer::link(
		new moodle_url('/admin/tool/edward/edit.php', array('courseid' => $courseid)),
		get_string('add', 'tool_edward'),
		array('class' => "add")
	); 
}

// if (!class_exists('data')) {
  require_once(__DIR__ . '/classes/data.php');
// }

$data = new data();
$table = $data->get_data($courseid);

echo html_writer::table($table);

echo $OUTPUT->footer();