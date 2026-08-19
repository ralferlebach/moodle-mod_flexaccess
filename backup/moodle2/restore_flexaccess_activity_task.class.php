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
 * Restore task definition for mod_flexaccess.
 *
 * @package    mod_flexaccess
 * @copyright  2026 Ralf Erlebach
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/flexaccess/backup/moodle2/restore_flexaccess_stepslib.php');

/**
 * Restore task for the FlexAccess activity.
 *
 * @package    mod_flexaccess
 */
class restore_flexaccess_activity_task extends restore_activity_task {
    /**
     * No task-specific settings.
     *
     * @return void
     */
    protected function define_my_settings() {
    }

    /**
     * Define the restore steps.
     *
     * @return void
     */
    protected function define_my_steps() {
        $this->add_step(new restore_flexaccess_activity_structure_step('flexaccess_structure', 'flexaccess.xml'));
    }

    /**
     * Define file areas restored from the activity.
     *
     * @return array
     */
    public static function define_decode_contents() {
        $contents = [];
        $contents[] = new restore_decode_content('flexaccess', ['intro'], 'flexaccess');
        return $contents;
    }

    /**
     * Define the decoding rules for links.
     *
     * @return array
     */
    public static function define_decode_rules() {
        $rules = [];
        $rules[] = new restore_decode_rule('FLEXACCESSVIEWBYID', '/mod/flexaccess/view.php?id=$1', 'course_module');
        $rules[] = new restore_decode_rule('FLEXACCESSINDEX', '/mod/flexaccess/index.php?id=$1', 'course');
        return $rules;
    }
}
