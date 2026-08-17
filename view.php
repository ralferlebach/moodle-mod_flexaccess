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
 * View page for mod_flexaccessactivation.
 *
 * @copyright  2026 Ralf Erlebach
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');

$id = required_param('id', PARAM_INT);
$cm = get_coursemodule_from_id('flexaccessactivation', $id, 0, false, MUST_EXIST);
$course = get_course($cm->course);
$instance = $DB->get_record('flexaccessactivation', ['id' => $cm->instance], '*', MUST_EXIST);

require_login($course, true, $cm);
$context = context_module::instance($cm->id);
require_capability('mod/flexaccessactivation:view', $context);

$PAGE->set_url('/mod/flexaccessactivation/view.php', ['id' => $cm->id]);
$PAGE->set_title(format_string($instance->name));
$PAGE->set_heading(format_string($course->fullname));

$completion = new completion_info($course);
$completion->set_module_viewed($cm);

echo $OUTPUT->header();
echo $OUTPUT->heading(format_string($instance->name));
if (trim($instance->intro) !== '') {
    echo $OUTPUT->box(format_module_intro('flexaccessactivation', $instance, $cm->id), 'generalbox mod_introbox');
}
echo $OUTPUT->notification(get_string('stubnotice', 'mod_flexaccessactivation'), 'info');
echo $OUTPUT->footer();
