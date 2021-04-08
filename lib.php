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
        has_capability('tool/edward:view', $context)){
        global $PAGE;

        $edward_node = $navigation->add(get_string('pluginname', 'tool_edward'), new moodle_url('/admin/tool/edward/index.php', array('courseid' => $course->id)), null, null, null, new pix_icon('i/settings', ''));

        $settingnode = $PAGE->navigation->add(get_string('pluginname', 'tool_edward'), new moodle_url('/admin/tool/edward/index.php', array('courseid' => $course->id)), null, null, null, new pix_icon('i/settings', ''));

        $settingnode->showinflatnavigation = true;

    }

}

function tool_edward_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {

    if ($filearea !== 'img' && $filearea !== 'attachments') {
        return false;
    }

    if (($context->contextlevel != 50) &&
        !has_capability('tool/edward:view', $context)) {
        return false;
    }

    $itemid = array_shift($args);

    $filename = array_pop($args);
    if (!$args) {
        $filepath = '/';
    } else {
        $filepath = '/'.implode('/', $args).'/';
    }
 
    // Retrieve the file from the Files API.
    $fs = get_file_storage();
    $file = $fs->get_file($context->id, 'tool_edward', $filearea, $itemid, $filepath, $filename);
    if (!$file) {
        return false;
    }
 
    send_stored_file($file, 86400, 0, $forcedownload, $options);
}




