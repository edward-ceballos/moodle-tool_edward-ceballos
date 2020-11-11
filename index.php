<?php

require_once(__DIR__ . '/../../../config.php');

require_login();

$url = new moodle_url('/admin/tool/edward/index.php');
$PAGE->set_context(context_system::instance());
$PAGE->set_url($url);
$PAGE->set_pagelayout('report');
$PAGE->set_title('Hello to the Edward Ceballos');
$PAGE->set_heading(get_string('hello_world', 'tool_edward'));

echo $OUTPUT->header();
echo $OUTPUT->footer();