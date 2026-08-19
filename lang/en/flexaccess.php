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
 * Language strings for mod_flexaccess.
 *
 * @package    mod_flexaccess
 * @copyright  2026 Ralf Erlebach
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['flexaccess:activate'] = 'Self-activate a FlexAccess temporary account';
$string['flexaccess:addinstance'] = 'Add a new FlexAccess activation activity';
$string['flexaccess:view'] = 'View the FlexAccess activation activity';
$string['modulename'] = 'FlexAccess activation';
$string['modulenameplural'] = 'FlexAccess activations';
$string['pluginadministration'] = 'FlexAccess activation administration';
$string['pluginname'] = 'FlexAccess activation';
$string['privacy:metadata'] = 'The activity stores only configuration. User activation data is owned by auth_flexaccess.';
$string['profilefields'] = 'Additional profile fields';
$string['profilefields_help'] = 'Scaffold: later this will contain the allowlisted profile-field identifiers requested during self-activation.';
$string['sa:activated'] = 'Your account has been activated. Your results and access are kept.';
$string['sa:email'] = 'E-mail address';
$string['sa:emailtaken'] = 'That e-mail address is already in use. Please use a different one.';
$string['sa:firstname'] = 'First name';
$string['sa:invalidemail'] = 'Please enter a valid e-mail address.';
$string['sa:lastname'] = 'Last name';
$string['sa:notapplicable'] = 'Your account does not require activation.';
$string['sa:password'] = 'Choose a password';
$string['sa:password_help'] = 'Set a password so you can log back in to this account later with your email address.';
$string['sa:submit'] = 'Activate my account';
$string['stubnotice'] = 'FlexAccess activation scaffold: the self-activation form is not implemented yet.';
$string['view:authenticated'] = 'Your account is already a full account. Nothing to activate here.';
$string['view:temporary'] = 'You are using temporary access. Self-activation to keep your results will be available here.';
$string['view:unavailable'] = 'FlexAccess is not fully installed, so self-activation is unavailable.';
