<?php 

defined('MOODLE_INTERNAL') || die();

function xmldb_qtype_myqtype_upgrade($oldversion) {
	global $DB;

	$dbman = $DB->get_manager();
	if ($oldversion < 2020112015) {

        // Define table tool_edward to be created.
        $table = new xmldb_table('tool_edward');

        // Adding fields to table tool_edward.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('courseid', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('name', XMLDB_TYPE_CHAR, '255', null, null, null, null);
        $table->add_field('completed', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('priority', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '1');
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, null, null, null);

        // Adding keys to table tool_edward.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('foreign', XMLDB_KEY_FOREIGN_UNIQUE, ['courseid'], 'course', ['id']);

        // Adding indexes to table tool_edward.
        $table->add_index('uninque_name', XMLDB_INDEX_UNIQUE, ['name']);

        // Conditionally launch create table for tool_edward.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Edward savepoint reached.
        upgrade_plugin_savepoint(true, 2020112015, 'tool', 'edward');
    }
	return true;
}
