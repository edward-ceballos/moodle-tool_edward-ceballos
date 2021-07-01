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

/**
 * Load edward message for guests.
 *
 * @return string The HTML code to insert before the head.
 */
function tool_edward_before_standard_html_head() {
    global $CFG, $PAGE, $USER;

    $message = null;
    if (!empty($CFG->siteedwardhandler)
            && $CFG->siteedwardhandler == 'tool_edward'
            && empty($USER->edwardagreed)
            && (isguestuser() || !isloggedin())) {
        $output = $PAGE->get_renderer('tool_edward');
        try {
            $page = new \tool_edward\output\guestconsent();
            $message = $output->render($page);
        } catch (dml_read_exception $e) {
            // During upgrades, the new plugin code with new SQL could be in place but the DB not upgraded yet.
            $message = null;
        }
    }

    return $message;
}

/**
 * Callback to add footer elements.
 *
 * @return string HTML footer content
 */
function tool_edward_standard_footer_html() {
    global $CFG, $PAGE;

    $output = '';
    if (!empty($CFG->siteedwardhandler)
            && $CFG->siteedwardhandler == 'tool_edward') {
        $policies = api::get_current_versions_ids();
        if (!empty($policies)) {
            $url = new moodle_url('/admin/tool/edward/viewall.php', ['returnurl' => $PAGE->url]);
            $output .= html_writer::link($url, get_string('useredwardsettings', 'tool_edward'));
            $output = html_writer::div($output, 'policiesfooter');
        }
    }

    return $output;
}



