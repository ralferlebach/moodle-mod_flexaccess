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
 * Core callbacks for mod_flexaccessactivation.
 *
 * @copyright  2026 Ralf Erlebach
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/** @param string $feature Feature name. @return mixed */
function flexaccessactivation_supports($feature) {
    return match ($feature) {
        FEATURE_MOD_ARCHETYPE => MOD_ARCHETYPE_OTHER,
        FEATURE_GROUPS => false,
        FEATURE_GROUPINGS => false,
        FEATURE_MOD_INTRO => true,
        FEATURE_SHOW_DESCRIPTION => true,
        FEATURE_COMPLETION_TRACKS_VIEWS => true,
        FEATURE_BACKUP_MOODLE2 => false,
        default => null,
    };
}

/** @param stdClass $data Module data. @param mod_flexaccessactivation_mod_form|null $mform Form. @return int */
function flexaccessactivation_add_instance($data, $mform = null): int {
    global $DB;
    $data->timecreated = time();
    $data->timemodified = $data->timecreated;
    return $DB->insert_record('flexaccessactivation', $data);
}

/** @param stdClass $data Module data. @param mod_flexaccessactivation_mod_form|null $mform Form. @return bool */
function flexaccessactivation_update_instance($data, $mform = null): bool {
    global $DB;
    $data->id = $data->instance;
    $data->timemodified = time();
    return $DB->update_record('flexaccessactivation', $data);
}

/** @param int $id Instance ID. @return bool */
function flexaccessactivation_delete_instance($id): bool {
    global $DB;
    if (!$DB->record_exists('flexaccessactivation', ['id' => $id])) {
        return false;
    }
    $DB->delete_records('flexaccessactivation', ['id' => $id]);
    return true;
}
