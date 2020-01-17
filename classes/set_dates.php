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
 * Helper functions for tool_coursedates.
 *
 * @package   tool_coursedates
 * @copyright 2017 Lafayette College ITS
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_coursedates;

defined('MOODLE_INTERNAL') || die();
global $CFG;

require_once($CFG->dirroot.'/admin/tool/coursedates/locallib.php');
require_once($CFG->dirroot.'/course/lib.php');
require_once($CFG->dirroot . '/course/externallib.php');

/**
 * Helper functions for tool_coursedates.
 *
 * @package   tool_coursedates
 * @copyright 2017 Lafayette College ITS
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class set_dates {
    /**
     * Alter course date information for a single course.
     *
     * @param stdClass $course
     * @param stdClass $data
     */
    public static function maybe_alter_course_dates($course, $data) {
        if (!$course->can_edit()) {
            return;
        }

        $record             = get_course($course->id);
        $data->keependdates = isset($data->keependdates) ? $data->keependdates : TOOL_COURSEDATES_KEEPENDDATES_DEFAULT;
        $lockenddates       = $data->keependdates == TOOL_COURSEDATES_KEEPENDDATES_ON && isset($record->enddate) && !empty($record->enddate);

        // Handle requested format changes.
        if (!$lockenddates && $data->autoenddate != TOOL_COURSEDATES_AUTOENDDATE_DEFAULT && $course->format == 'weeks') {
            $format = course_get_format($course);
            $formatoptions = array('automaticenddate' => $data->autoenddate);
            $format->update_course_format_options($formatoptions);
        }

        if ( !$lockenddates && isset($data->enddate) && $data->enddate !== 0) {
            $record->enddate = $data->enddate;
        }

        if (isset($data->startdate) && $data->startdate !== 0) {
            $record->startdate = $data->startdate;
        }

        try {
            update_course($record);
        } catch (\moodle_exception $e) {
            debugging($e->getMessage());
        }
    }
}
