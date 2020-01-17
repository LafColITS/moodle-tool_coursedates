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
 * Form for changing course dates.
 *
 * @package   tool_coursedates
 * @copyright 2017 Lafayette College ITS
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_coursedates;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');
require_once($CFG->dirroot.'/admin/tool/coursedates/locallib.php');

/**
 * Form for changing course dates.
 *
 * @package   tool_coursedates
 * @copyright 2017 Lafayette College ITS
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class set_dates_form extends \moodleform {
    /**
     * Defines the form.
     */
    public function definition() {
        $mform = $this->_form;

        // Explanatory instruction.

        $mform->addElement('html', '<p>' . get_string('setdatesinstruction', 'tool_coursedates') . '</p>');

        // Date pickers.
        $mform->addElement('date_selector', 'startdate', get_string('startdate'), array('optional' => true));
        $mform->addHelpButton('startdate', 'startdate');
        $mform->addElement('date_selector', 'enddate', get_string('enddate'), array('optional' => true));
        $mform->addHelpButton('enddate', 'enddate');

        // Auto end dates for weekly format.
        $options = array(
            TOOL_COURSEDATES_AUTOENDDATE_DEFAULT => get_string('autoenddate_default', 'tool_coursedates'),
            TOOL_COURSEDATES_AUTOENDDATE_ON      => get_string('autoenddate_on', 'tool_coursedates'),
            TOOL_COURSEDATES_AUTOENDDATE_OFF     => get_string('autoenddate_off', 'tool_coursedates')
        );
        $mform->addElement('select', 'autoenddate', get_string('autoenddate', 'tool_coursedates'),
            $options);
        $mform->addHelpButton('autoenddate', 'autoenddate', 'tool_coursedates');
        $mform->setDefault('autoenddate', TOOL_COURSEDATES_AUTOENDDATE_DEFAULT);

        // Don't overwrite existing enddates.
        $mform->addElement('checkbox', 'keependdates', get_string('keependdates', 'tool_coursedates'));
        $mform->setDefault('keependdates', TOOL_COURSEDATES_KEEPENDDATES_DEFAULT);

        // Metadata.
        $mform->addElement('hidden', 'category');
        $mform->setType('category', PARAM_INT);

        $this->add_action_buttons(true, get_string('confirm'));
    }

    /**
     * Validation check for form submission.
     *
     * @param array $data Submitted form data.
     * @param array $files Submitted files. Unused.
     */
    public function validation($data, $files) {
        $errors = array();
        if ((!isset($data['startdate']) || $data['startdate'] == 0)
            && (!isset($data['enddate']) || $data['enddate'] == 0)) {
            $errors['startdate'] = get_string('atleastonedate', 'tool_coursedates');
        }
        return $errors;
    }
}
