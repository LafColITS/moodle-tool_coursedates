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
 * Unit tests for tasks.
 *
 * @package    tool_coursedates
 * @category   test
 * @copyright  2017 Lafayette College ITS
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;

require_once($CFG->dirroot.'/admin/tool/coursedates/locallib.php');

/**
 * Unit test for setting course dates.
 *
 * @package   tool_coursedates
 * @copyright 2017 Lafayette College ITS
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class tool_coursedates_coursedates_testcase extends advanced_testcase {
    public function test_set_dates() {
        global $DB;

        $this->setAdminUser();
        $this->resetAfterTest(true);

        // Create test data.
        $coursestartdate = time();
        $category1 = $this->getDataGenerator()->create_category();
        $category2 = $this->getDataGenerator()->create_category(array('parent' => $category1->id));
        $this->getDataGenerator()->create_course(array('category' => $category1->id, 'startdate' => $coursestartdate));
        for ($i = 1; $i <= 100; $i++) {
            $this->getDataGenerator()->create_course(array('category' => $category2->id, 'startdate' => $coursestartdate));
        }

        // Sanity check.
        $coursesnoenddate = $DB->count_records('course', array('category' => $category1->id, 'enddate' => 0));
        $this->assertEquals(1, $coursesnoenddate);
        $coursesnoenddate = $DB->count_records('course', array('category' => $category2->id, 'enddate' => 0));
        $this->assertEquals(100, $coursesnoenddate);

        // Set an end date for the second category only.
        $courseenddate = $coursestartdate + 86400;
        $task = new \tool_coursedates\task\set_course_dates_task();
        $task->set_custom_data(
            array(
                'category' => $category2->id,
                'enddate' => $courseenddate,
                'autoenddate' => TOOL_COURSEDATES_AUTOENDDATE_DEFAULT,
                'keependdates' => TOOL_COURSEDATES_KEEPENDDATES_OFF
            )
        );
        \core\task\manager::queue_adhoc_task($task);
        $task = \core\task\manager::get_next_adhoc_task(time());
        $this->assertInstanceOf('\\tool_coursedates\\task\\set_course_dates_task', $task);
        $task->execute();
        \core\task\manager::adhoc_task_complete($task);

        // All but one course should have an end date.
        $coursesnoenddate = $DB->count_records('course', array('category' => $category1->id, 'enddate' => $courseenddate));
        $this->assertEquals(0, $coursesnoenddate);
        $coursesnoenddate = $DB->count_records('course', array('category' => $category2->id, 'enddate' => $courseenddate));
        $this->assertEquals(100, $coursesnoenddate);

        // Set an end date for the first category.
        $task = new \tool_coursedates\task\set_course_dates_task();
        $task->set_custom_data(
            array(
                'category' => $category1->id,
                'enddate'  => $courseenddate,
                'autoenddate' => TOOL_COURSEDATES_AUTOENDDATE_DEFAULT,
                'keependdates' => TOOL_COURSEDATES_KEEPENDDATES_OFF
            )
        );
        \core\task\manager::queue_adhoc_task($task);
        $task = \core\task\manager::get_next_adhoc_task(time());
        $this->assertInstanceOf('\\tool_coursedates\\task\\set_course_dates_task', $task);
        $task->execute();
        \core\task\manager::adhoc_task_complete($task);

        // All courses should have an end date.
        $coursesnoenddate = $DB->count_records('course', array('category' => $category1->id, 'enddate' => $courseenddate));
        $this->assertEquals(1, $coursesnoenddate);
        $coursesnoenddate = $DB->count_records('course', array('category' => $category2->id, 'enddate' => $courseenddate));
        $this->assertEquals(100, $coursesnoenddate);

        // Move all but one course forward one week.
        $newstartdate = $coursestartdate + 86400;
        $newenddate = $courseenddate + 86400;
        $task = new \tool_coursedates\task\set_course_dates_task();
        $task->set_custom_data(
            array(
                'category'  => $category2->id,
                'enddate'   => $newenddate,
                'startdate' => $newstartdate,
                'autoenddate' => TOOL_COURSEDATES_AUTOENDDATE_DEFAULT,
                'keependdates' => TOOL_COURSEDATES_KEEPENDDATES_OFF
            )
        );
        \core\task\manager::queue_adhoc_task($task);
        $task = \core\task\manager::get_next_adhoc_task(time());
        $this->assertInstanceOf('\\tool_coursedates\\task\\set_course_dates_task', $task);
        $task->execute();
        \core\task\manager::adhoc_task_complete($task);

        // One course should be forward a week.
        $coursesnewdates = $DB->count_records('course',
            array('category' => $category1->id, 'startdate' => $newstartdate, 'enddate' => $newenddate));
        $this->assertEquals(0, $coursesnewdates);
        $coursesnochange = $DB->count_records('course',
            array('category' => $category2->id, 'startdate' => $newstartdate, 'enddate' => $newenddate));
        $this->assertEquals(100, $coursesnochange);

        // Unset the end date for half the courses in Category 2.
        $enddate = $newenddate;
        $DB->set_field_select('course', 'enddate', 0, "category = ? AND MOD(id, 2) = 0", array($category2->id));
        $courseswithenddate = $DB->count_records('course', array('category' => $category2->id, 'enddate' => $enddate));
        $this->assertEquals(50, $courseswithenddate);
        $coursesnoenddate = $DB->count_records('course', array('category' => $category2->id, 'enddate' => 0));
        $this->assertEquals(50, $coursesnoenddate);

        // Set a new end date only for the courses in Category 2 with no end date.
        $newenddate = $enddate + 86400;
        $task = new \tool_coursedates\task\set_course_dates_task();
        $task->set_custom_data(
            array(
                'category'  => $category2->id,
                'enddate'   => $newenddate,
                'autoenddate' => TOOL_COURSEDATES_AUTOENDDATE_DEFAULT,
                'keependdates' => TOOL_COURSEDATES_KEEPENDDATES_ON
            )
        );
        \core\task\manager::queue_adhoc_task($task);
        $task = \core\task\manager::get_next_adhoc_task(time());
        $this->assertInstanceOf('\\tool_coursedates\\task\\set_course_dates_task', $task);
        $task->execute();
        \core\task\manager::adhoc_task_complete($task);

        // Half the courses in Category 2 should have the new end date.
        $courseswitholdenddate = $DB->count_records('course', array('category' => $category2->id, 'enddate' => $enddate));
        $this->assertEquals(50, $courseswitholdenddate);
        $courseswithnewenddate = $DB->count_records('course', array('category' => $category2->id, 'enddate' => $newenddate));
        $this->assertEquals(50, $courseswithnewenddate);
    }
}
