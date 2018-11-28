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
 * Navigation for tool_coursedates.
 *
 * @package   tool_coursedates
 * @copyright 2017 Lafayette College ITS
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Extends the category navigation to show the course dates tool.
 *
 * @param navigation_node $navigation The navigation node to extend
 * @param context         $context The category context
 */
function tool_coursedates_extend_navigation_category_settings($navigation, $context) {
    if (has_capability('tool/coursedates:setdates', $context)) {
         $navigation->add_node(
             navigation_node::create(
                 get_string('setdates', 'tool_coursedates'),
                    new moodle_url(
                        "/admin/tool/coursedates/index.php",
                        array('category' => $context->instanceid)
                    ),
                    navigation_node::TYPE_SETTING,
                    null,
                    null,
                    new pix_icon('i/settings', '')
                    )
                );
    }
}
