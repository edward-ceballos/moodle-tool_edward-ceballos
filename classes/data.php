<?php

defined('MOODLE_INTERNAL') || die();

class data {
	/** @var array */
	protected $rows = [];

	protected function col_name($text, $format = FORMAT_MOODLE, $options = null): string{
		return format_string($text, $format, $options);
	}

	public function get_data() {
		global $DB;
		$rows = $DB->get_records('tool_edward');

		$table = new html_table();

		$table->head = array('ID', get_string('name'), get_string('status'), get_string('priority', 'tool_edward'), get_string('timecreated', 'tool_edward'), get_string('timemodified', 'tool_edward'));

		foreach ($rows as $records) {
			$table->data[] = array(
				$records->id, 
				$this->col_name($records->name), 
				$records->completed == 1 ? get_string('yes') : get_string('no'),
				$records->priority,
				userdate($records->timecreated),
				userdate($records->timemodified)
			);
		}
		return $table;
	}
}