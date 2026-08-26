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
 * Self-activation form for a temporary FlexAccess user.
 *
 * @package    mod_flexaccess
 * @copyright  2026 Ralf Erlebach
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_flexaccess\form;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->libdir . '/formslib.php');

/**
 * Captures the e-mail and name for in-course self-activation.
 *
 * @package    mod_flexaccess
 */
class self_activation_form extends \moodleform {
    /**
     * Define the form.
     *
     * @return void
     */
    protected function definition(): void {
        $mform = $this->_form;

        $mform->addElement('text', 'email', get_string('saemail', 'mod_flexaccess'));
        $mform->setType('email', PARAM_EMAIL);
        $mform->addRule('email', get_string('required'), 'required', null, 'client');

        $mform->addElement('text', 'firstname', get_string('safirstname', 'mod_flexaccess'));
        $mform->setType('firstname', PARAM_NOTAGS);

        $mform->addElement('text', 'lastname', get_string('salastname', 'mod_flexaccess'));
        $mform->setType('lastname', PARAM_NOTAGS);

        $mform->addElement('passwordunmask', 'password', get_string('sapassword', 'mod_flexaccess'));
        $mform->setType('password', PARAM_RAW);
        $mform->addRule('password', get_string('required'), 'required', null, 'client');
        $mform->addHelpButton('password', 'sapassword', 'mod_flexaccess');

        $mform->addElement('hidden', 'id', $this->_customdata['id']);
        $mform->setType('id', PARAM_INT);

        $this->add_action_buttons(false, get_string('sasubmit', 'mod_flexaccess'));
    }

    /**
     * Validate the submitted e-mail and password.
     *
     * @param array $data Submitted data.
     * @param array $files Submitted files.
     * @return array Validation errors keyed by element name.
     */
    public function validation($data, $files): array {
        $errors = parent::validation($data, $files);
        $errmsg = '';
        if (!check_password_policy($data['password'] ?? '', $errmsg)) {
            $errors['password'] = $errmsg;
        }
        return $errors;
    }
}
