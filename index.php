<?php

require_once(__DIR__ . '/../../../config.php');

require_login();

$url = new moodle_url('/admin/tool/edward/index.php');
$PAGE->set_context(context_system::instance());

$PAGE->set_url($url, array('id' => 3));
$PAGE->set_pagelayout('report');
$PAGE->set_title('Hello to the Edward Ceballos');
$PAGE->set_heading(get_string('hello_world', 'tool_edward'));

$settingnode = $PAGE->settingsnav->add(get_string('hello_world', 'tool_edward'), new moodle_url('/admin/tool/edward/index.php', array('id'=>'3')), navigation_node::TYPE_CONTAINER);
$settingnode->make_active();

$count_user = $DB->count_records('user');
echo $OUTPUT->header();

echo html_writer::div(
	get_string('hello_world', 'tool_edward'), 
	'multilang', 
	array('id' => '1', 'lang' => 'en')
); 

echo html_writer::div(
	"Cantidad de usuarios registrados: {$count_user}", 
	'multilang', 
	array('id' => '2', 'lang' => 'en')
); 

echo $OUTPUT->footer();