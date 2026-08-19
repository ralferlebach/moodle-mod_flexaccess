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
 * Backup structure step for mod_flexaccess.
 *
 * @package    mod_flexaccess
 * @copyright  2026 Ralf Erlebach
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Defines the backup structure for a FlexAccess activity instance.
 *
 * The activity stores only instance configuration; it holds no per-user data, so there is no
 * user-info branch.
 *
 * @package    mod_flexaccess
 */
class backup_flexaccess_activity_structure_step extends backup_activity_structure_step {
    /**
     * Define the XML structure to back up.
     *
     * @return backup_nested_element
     */
    protected function define_structure() {
        $flexaccess = new backup_nested_element('flexaccess', ['id'], [
            'name', 'intro', 'introformat', 'profilefieldsjson', 'timecreated', 'timemodified',
        ]);

        $flexaccess->set_source_table('flexaccess', ['id' => backup::VAR_ACTIVITYID]);

        $flexaccess->annotate_files('mod_flexaccess', 'intro', null);

        return $this->prepare_activity_structure($flexaccess);
    }
}
