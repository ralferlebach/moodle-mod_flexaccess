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
 * Instance form for mod_flexaccess.
 *
 * @copyright  2026 Ralf Erlebach
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/course/moodleform_mod.php');

/** Activity settings form. */
final class mod_flexaccess_mod_form extends moodleform_mod {
    /** Define form. */
    public function definition(): void {
        $mform = $this->_form;
        $mform->addElement('text', 'name', get_string('name'), ['size' => 64]);
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', null, 'required', null, 'client');
        $this->standard_intro_elements();
        $mform->addElement('textarea', 'profilefieldsjson', get_string('profilefields', 'mod_flexaccess'), ['rows' => 3, 'cols' => 60]);
        $mform->setType('profilefieldsjson', PARAM_RAW_TRIMMED);
        $mform->addHelpButton('profilefieldsjson', 'profilefields', 'mod_flexaccess');
        $this->standard_coursemodule_elements();
        $this->add_action_buttons();
    }
}
