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

	public function get_data($courseid) {
		global $DB, $CFG, $cm;

		$courseid  = required_param('courseid', PARAM_INT);
		$context = context_course::instance($courseid);
		$rows = $DB->get_records('tool_edward', array('courseid' => $courseid));
		$table = new html_table();

		$fs = get_file_storage();

		if (has_capability('tool/edward:edit', context_course::instance($courseid))){
			
			$table->head = array( get_string('name'), get_string('resolved', 'tool_edward'), get_string('priority', 'tool_edward'), get_string('description', 'tool_edward'), get_string('attachments', 'tool_edward'), get_string('timecreated', 'tool_edward'), get_string('timemodified', 'tool_edward'), get_string('actions', 'tool_edward'));

			foreach ($rows as $records) {
				$records->description = file_rewrite_pluginfile_urls($records->description, 'pluginfile.php', $context->id, 'tool_edward', 'img', $records->id);
				$attachments = array();
				$files = $fs->get_area_files($context->id, 'tool_edward', 'attachments', $records->id);
				foreach ($files as $file) {
				    $filename = $file->get_filename();
				    if ($filename != '.') {
					    $url = moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(), $file->get_itemid(), $file->get_filepath(), $file->get_filename(), false);
					    $attachments[] = html_writer::link($url, $filename, array('target' => '_blank'));
				    }
				}


				$table->data[] = array(
					$this->col_name($records->name), 
					$records->completed == 1 ? get_string('yes') : get_string('no'),
					$records->priority,
					$records->description,
					implode('<br>', $attachments),
					$records->timecreated ? userdate($records->timecreated) : '',
					$records->timemodified ? userdate($records->timemodified) : '',
					html_writer::link(
						new moodle_url('/admin/tool/edward/edit.php', array('courseid' => $courseid, 'edit' =>$records->id)),
						get_string('edit', 'tool_edward'), array('title' => get_string('editentrytitle', 'tool_edward', format_string($records->name)), 'class' => 'edit')
					)
					.' '.html_writer::link(
						new moodle_url('/admin/tool/edward/delete.php', array('courseid' => $courseid, 'delete' =>$records->id)),
						get_string('delete', 'tool_edward'), array('class' => 'del')
					),
				);
			}
		}
		else{
			$table->head = array('ID', get_string('name'), get_string('status'), get_string('priority', 'tool_edward'), get_string('description', 'tool_edward'), get_string('timecreated', 'tool_edward'), get_string('timemodified', 'tool_edward'));

			foreach ($rows as $records) {
				$table->data[] = array(
					$records->id, 
					$this->col_name($records->name), 
					$records->completed == 1 ? get_string('yes') : get_string('no'),
					$records->priority,
					$records->description,
					$records->timecreated ? userdate($records->timecreated) : '',
					$records->timemodified ? userdate($records->timemodified) : '',
				);
			}
		}
		
		return $table;
	}
}