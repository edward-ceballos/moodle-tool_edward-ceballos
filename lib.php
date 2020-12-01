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

defined('MOODLE_INTERNAL') || die();



function tool_edward_extend_navigation_course($navigation, $course, $context) {

    if(($context->contextlevel === 50) &&
        has_capability('gradereport/grader:view', $context)){
        global $PAGE;

        $course_node = $PAGE->navigation->add(get_string('pluginname', 'tool_edward'), new moodle_url('/admin/tool/edward/index.php', array('id' => $course->id)), navigation_node::TYPE_SETTING, null, null, new pix_icon('i/settings', ''));

        $course_node->showinflatnavigation = true;
        $course_node->set_indent = 1;
    }

}