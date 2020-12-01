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

use core_user\output\myprofile\tree;
defined('MOODLE_INTERNAL') || die();

class data {
	/** @var array */
	protected $rows = [];

	protected function col_name($text, $format = FORMAT_MOODLE, $options = null): string{
		return format_string($text, $format, $options);
	}

	public function get_data($id) {
		global $DB, $PAGE;
		$rows = $DB->get_records('tool_edward', array('courseid' => $id));

		$table = new html_table();

		if (has_capability('tool/edward:edit', context_course::instance($PAGE->course->id))){
			$table->head = array('ID', get_string('name'), get_string('resolved', 'tool_edward'), get_string('priority', 'tool_edward'), get_string('timecreated', 'tool_edward'), get_string('timemodified', 'tool_edward'), get_string('actions', 'tool_edward'));

			foreach ($rows as $records) {
				
				$table->data[] = array(
					$records->id, 
					$this->col_name($records->name), 
					$records->completed == 1 ? get_string('yes') : get_string('no'),
					$records->priority,
					$records->timecreated ? userdate($records->timecreated) : '',
					$records->timemodified ? userdate($records->timemodified) : '',
					html_writer::link(
						new moodle_url('/admin/tool/edward/edit.php', array('id' => $PAGE->course->id, 'edit' =>$records->id)),
						get_string('edit', 'tool_edward'), array('title' => get_string('editentrytitle', 'tool_edward', format_string($records->name)), 'class' => 'edit')
					)
					.' '.html_writer::link(
						new moodle_url('/admin/tool/edward/delete.php', array('id' => $PAGE->course->id, 'delete' =>$records->id)),
						get_string('delete', 'tool_edward'), array('class' => 'del')
					),
				);
			}
		}
		else{
			$table->head = array('ID', get_string('name'), get_string('status'), get_string('priority', 'tool_edward'), get_string('timecreated', 'tool_edward'), get_string('timemodified', 'tool_edward'));

			foreach ($rows as $records) {
				$table->data[] = array(
					$records->id, 
					$this->col_name($records->name), 
					$records->completed == 1 ? get_string('yes') : get_string('no'),
					$records->priority,
					$records->timecreated ? userdate($records->timecreated) : '',
					$records->timemodified ? userdate($records->timemodified) : '',
				);
			}
		}
		
		return $table;
	}
}