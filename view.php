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
 * View page for mod_flexaccess.
 *
 * @copyright  2026 Ralf Erlebach
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');

$id = required_param('id', PARAM_INT);
$cm = get_coursemodule_from_id('flexaccess', $id, 0, false, MUST_EXIST);
$course = get_course($cm->course);
$instance = $DB->get_record('flexaccess', ['id' => $cm->instance], '*', MUST_EXIST);

require_login($course, true, $cm);
$context = context_module::instance($cm->id);
require_capability('mod/flexaccess:view', $context);

$PAGE->set_url('/mod/flexaccess/view.php', ['id' => $cm->id]);
$PAGE->set_title(format_string($instance->name));
$PAGE->set_heading(format_string($course->fullname));

$completion = new completion_info($course);
$completion->set_module_viewed($cm);

echo $OUTPUT->header();
echo $OUTPUT->heading(format_string($instance->name));
if (trim($instance->intro) !== '') {
    echo $OUTPUT->box(format_module_intro('flexaccess', $instance, $cm->id), 'generalbox mod_introbox');
}
$authavailable = class_exists('\\auth_flexaccess\\api');
$accounttype = $authavailable ? \auth_flexaccess\api::classify_user($USER->id) : null;
$state = \mod_flexaccess\local\view_state::resolve($accounttype, $authavailable);

switch ($state) {
    case \mod_flexaccess\local\view_state::TEMPORARY:
        $mform = new \mod_flexaccess\form\self_activation_form($PAGE->url->out(false), ['id' => $cm->id]);
        if ($data = $mform->get_data()) {
            require_capability('mod/flexaccess:activate', $context);
            $status = \auth_flexaccess\api::self_activate(
                $USER->id, $data->email, $data->firstname ?? '', $data->lastname ?? '');
            $type = ($status === 'activated') ? 'success' : 'error';
            echo $OUTPUT->notification(get_string('sa:' . $status, 'mod_flexaccess'), $type);
            if ($status !== 'activated') {
                $mform->display();
            }
        } else {
            echo $OUTPUT->notification(get_string('view:temporary', 'mod_flexaccess'), 'info');
            $mform->display();
        }
        break;
    case \mod_flexaccess\local\view_state::AUTHENTICATED:
        echo $OUTPUT->notification(get_string('view:authenticated', 'mod_flexaccess'), 'info');
        break;
    default:
        echo $OUTPUT->notification(get_string('view:unavailable', 'mod_flexaccess'), 'warning');
}

echo $OUTPUT->footer();
