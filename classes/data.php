<?php

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

		if (has_capability('tool/edward:edit', context_system::instance())){
			$table->head = array('ID', get_string('name'), get_string('resolved', 'tool_edward'), get_string('priority', 'tool_edward'), get_string('timecreated', 'tool_edward'), get_string('timemodified', 'tool_edward'), get_string('edit', 'tool_edward'));

			foreach ($rows as $records) {
				$table->data[] = array(
					$records->id, 
					$this->col_name($records->name), 
					$records->completed == 1 ? get_string('yes') : get_string('no'),
					$records->priority,
					$records->timecreated ? userdate($records->timecreated) : '',
					$records->timemodified ? userdate($records->timemodified) : '',
					html_writer::link(
						new moodle_url('/admin/tool/edward/edit.php', array('id' =>$PAGE->course->id, 'edit' =>$records->id)),
						get_string('edit', 'tool_edward'))
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