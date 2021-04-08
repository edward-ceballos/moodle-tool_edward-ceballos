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

defined('MOODLE_INTERNAL') || die;

if ($deleteid = optional_param('delete', null, PARAM_INT)) {
    $record = $DB->get_record('tool_edward', ['id' => $deleteid], '*', MUST_EXIST);
    require_login(get_course($record->courseid));
    require_capability('tool/edward:edit', context_course::instance($record->courseid));
    $DB->delete_records('tool_edward', ['id' => $deleteid]);
    redirect(new moodle_url('/admin/tool/edward/index.php', ['courseid' => $record->courseid]));
    var_dump(optional_param('delete', null, PARAM_INT));	
}
else{
var_dump(optional_param('delete', null, PARAM_INT));	
}