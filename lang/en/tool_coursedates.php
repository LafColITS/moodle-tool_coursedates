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
 * Language strings for tool_coursedates.
 *
 * @package   tool_coursedates
 * @copyright 2017 Lafayette College ITS
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

$string['atleastonedate'] = 'You must set at least one date to update';
$string['autoenddate'] = 'Calculate the end date?';
$string['autoenddate_default'] = 'Do not modify';
$string['autoenddate_help'] = 'Moodle can calculate the end date based on the number of sections in courses which use the weekly topic format. You may choose whether to enforce this on the category level.';
$string['autoenddate_off'] = 'Disable auto end dates';
$string['autoenddate_on'] = 'Force auto end dates';
$string['coursedates:setdates'] = 'Set the start/end dates of all courses in a category.';
$string['keependdates'] = 'Keep existing end dates';
$string['pluginname'] = 'Set course dates';
$string['privacy:metadata'] = 'The Set course dates plugin does not store any personal data.';
$string['setdates'] = 'Set course dates';
$string['setdatesinstruction'] = 'Set the start and end dates for all courses in a category, including subcategories. Choose your options and click "Confirm". On confirmation, Moodle will create an "adhoc task" to set all the dates in the background. This requires that cron be enabled.';
$string['updatequeued'] = 'An adhoc task has been queued to update all the courses in the category <strong>{$a}</strong>. It will run the next time cron executes.';
