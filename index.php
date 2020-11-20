<?php

require_once(__DIR__ . '/../../../config.php');

require_login();

$url = new moodle_url('/admin/tool/edward/index.php');
$PAGE->set_context(context_system::instance());

$PAGE->set_url($url, array('id' => 3));
$PAGE->set_pagelayout('report');
$PAGE->set_title(get_string('pluginname', 'tool_edward'));
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
	get_string('text_count_users', 'tool_edward', $count_user), 
	'multilang', 
	array('id' => '2', 'lang' => 'en')
); 

if (!class_exists('mod_forum_some_class')) {
  require_once(__DIR__ . '/classes/data.php');
}

$data = new data();
$table = $data->get_data();


// $table = new html_table();

// $table->head = array('ID', 'Name', 'Status', 'Priority', 'Time Created', 'Time Modified');

// foreach ($rows as $records) {
// 	$table->data[] = array(
// 		$records->id, 
// 		col_name($records->name), 
// 		$records->completed == 1 ? get_string ('yes') : get_string ('no'),
// 		$records->priority,
// 		userdate($records->timecreated),
// 		userdate($records->timemodified)
// 	);
// }
echo html_writer::table($table);

echo $OUTPUT->footer();