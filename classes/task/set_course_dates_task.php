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
 * @package   tool_coursedates
 * @copyright 2017 Lafayette College ITS
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_coursedates\task;

defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot.'/lib/coursecatlib.php');

class set_course_dates_task extends \core\task\adhoc_task {
    public function get_component() {
        return 'tool_coursedates';
    }

    public function execute() {
        $data = $this->get_custom_data();
        if (empty($data->category)) {
            mtrace("No category id");
            return;
        }
        if (!isset($data->enddate)) {
            mtrace("No end date specified");
            return;
        }
        $category = \coursecat::get($data->category);
        if (!$category) {
            mtrace("Invalid category id");
            return;
        }
        $courses = $category->get_courses(
            array(
                'recursive' => true,
                'limit' => 0
            )
        );
        foreach ($courses as $course) {
            $context = \context_course::instance($course->id);
            if (has_capability('moodle/course:update', $context)) {
                $course->enddate = $data->enddate;
                update_course($course);
            }
        }
    }
}
