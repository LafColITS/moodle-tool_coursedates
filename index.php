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
 * Set course dates.
 *
 * @package   tool_coursedates
 * @copyright 2017 Lafayette College ITS
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../../config.php');
require_once($CFG->dirroot.'/lib/coursecatlib.php');

$categoryid = required_param('category', PARAM_INT);
$startdate  = optional_param('startdate', -1, PARAM_INT);
$enddate    = optional_param('enddate', -1, PARAM_INT);
$category   = \coursecat::get($categoryid);
$context    = \context_coursecat::instance($categoryid);

// Ensure the user can be here.
require_login(0, false);
require_capability('moodle/course:update', $context);
$returnurl = new \moodle_url('/course/management.php', array('categoryid' => $categoryid));

// Current location.
$url = new \moodle_url('/admin/tool/coursedates/index.php',
    array(
        'category' => $categoryid
    )
);

// Setup page.
$PAGE->set_context($context);
$PAGE->set_pagelayout('admin');
$PAGE->set_url($url);
$PAGE->set_title(new lang_string('coursecatmanagement') . ': '. get_string('setdates', 'tool_coursedates'));
$PAGE->set_heading($SITE->fullname);

// Create form.
$mform = new \tool_coursedates\set_dates_form('index.php', array('category' => $categoryid));
if ($mform->is_cancelled()) {
    redirect($returnurl);
} else if ($data = $mform->get_data()) {
    // Process data.
    $task = new \tool_coursedates\task\set_course_dates_task();
    $task->set_custom_data(
        array(
            'category' => $categoryid,
            'enddate' => $enddate,
            'startdate' => $startdate
        )
    );
    \core\task\manager::queue_adhoc_task($task);
    redirect($returnurl, get_string('updatequeued', 'tool_coursedates', $category->name));
} else {
    // Prepare the form.
    $mform->set_data(array('category' => $categoryid));
}

// Print page.
echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();