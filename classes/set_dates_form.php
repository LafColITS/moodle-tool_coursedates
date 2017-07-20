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

class set_dates_form extends \moodleform {
    public function definition() {
        $mform = $this->_form;

        // Date pickers.
        $mform->addElement('date_selector', 'startdate', get_string('startdate'), array('optional' => true));
        $mform->addHelpButton('startdate', 'startdate');
        $mform->addElement('date_selector', 'enddate', get_string('enddate'), array('optional' => true));
        $mform->addHelpButton('enddate', 'enddate');

        // Metadata.
        $mform->addElement('hidden', 'category');
        $mform->setType('category', PARAM_INT);

        $this->add_action_buttons(true, get_string('confirm'));
    }

    public function validation($data, $files) {
        $errors = array();
        if ((!isset($data['startdate']) || $data['startdate'] == 0)
            && (!isset($data['enddate']) || $data['enddate'] == 0)) {
            $errors['startdate'] = get_string('atleastonedate', 'tool_coursedates');
        }
        return $errors;
    }
}
