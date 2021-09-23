<?php

class edward_submission implements renderable {

    public function __construct(stdclass $submission, $anonymous = false, array $attachments = null) {
        
        $courseid = required_param('courseid', PARAM_INT);

        if (!$anonymous) {
            $this->count = $submission->count;
            $this->add = $submission->add;
            $this->content = $submission->content;
            $this->capability = has_capability('tool/edward:edit', context_course::instance($courseid));
        }
    }


    protected function col_name($text, $format = FORMAT_MOODLE, $options = null): string{
        return format_string($text, $format, $options);
    }

    public function export_for_template(renderer_base $output) {
        global $DB, $CFG, $cm;
        $data = new stdClass();
        $data->count = $this->count;
        $data->add = $this->add;
        $data->capability = $this->capability;
        $courseid = required_param('courseid', PARAM_INT);
        $context = context_course::instance($courseid);
        $rows = $DB->get_records('tool_edward', array('courseid' => $courseid));

        $fs = get_file_storage();
        $data->head = array('ID', get_string('name'), get_string('status'), get_string('priority', 'tool_edward'), get_string('description', 'tool_edward'), get_string('timecreated', 'tool_edward'), get_string('timemodified', 'tool_edward'));

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

            $data->content[] = array(
                "id" => $records->id, 
                "name" => $this->col_name($records->name), 
                "completed" => $records->completed == 1 ? get_string('yes') : get_string('no'),
                "priority" => $records->priority,
                "description" => $records->description,
                "attachments" => implode('<br>', $attachments),
                "timecreated" => $records->timecreated ? userdate($records->timecreated) : '',
                "timemodified" => $records->timemodified ? userdate($records->timemodified) : '',
                "actions" => html_writer::link(
                    new moodle_url('/admin/tool/edward/edit.php', array('courseid' => $courseid, 'edit' =>$records->id)),
                    get_string('edit', 'tool_edward'), array('title' => get_string('editentrytitle', 'tool_edward', format_string($records->name)), 'class' => 'edit')
                )
                .' '.html_writer::link(
                    new moodle_url('/admin/tool/edward/delete.php', array('courseid' => $courseid, 'delete' => $records->id)),
                    get_string('delete', 'tool_edward'), array('class' => 'del')
                ),
            );
        }

        return $data;
    }
}

class tool_edward_renderer extends plugin_renderer_base {

    protected function render_edward_submission(edward_submission $submission) {

        // var_dump($submission);
        // $out  = $this->output->heading(format_string($submission->title), 2);
        // $out .= $this->output->container(format_string($submission->title), 'author');
        // $out .= $this->output->container(format_text($submission->content, FORMAT_HTML), 'content');
        // return $this->output->container($out, 'submission');
        $data = $submission->export_for_template($this);
        // echo "<pre>";
        // var_dump($data);
        //     echo "</pre>";
    // Do other logic if needed.
        return $this->render_from_template('tool_edward/renderer', $data);
    }
}