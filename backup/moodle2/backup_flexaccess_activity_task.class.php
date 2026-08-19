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
 * Backup task definition for mod_flexaccess.
 *
 * @package    mod_flexaccess
 * @copyright  2026 Ralf Erlebach
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/flexaccess/backup/moodle2/backup_flexaccess_stepslib.php');

/**
 * Backup task for the FlexAccess activity.
 *
 * @package    mod_flexaccess
 */
class backup_flexaccess_activity_task extends backup_activity_task {
    /**
     * No task-specific settings.
     *
     * @return void
     */
    protected function define_my_settings() {
    }

    /**
     * Define the backup steps.
     *
     * @return void
     */
    protected function define_my_steps() {
        $this->add_step(new backup_flexaccess_activity_structure_step('flexaccess_structure', 'flexaccess.xml'));
    }

    /**
     * Encode links to the activity in content so they can be restored.
     *
     * @param string $content Content possibly containing links.
     * @return string
     */
    public static function encode_content_links($content) {
        global $CFG;
        $base = preg_quote($CFG->wwwroot, '/');

        // Link to the activity view page.
        $pattern = '/(' . $base . '\/mod\/flexaccess\/view\.php\?id=)([0-9]+)/';
        $content = preg_replace($pattern, '$@FLEXACCESSVIEWBYID*$2@$', $content);

        // Link to the activity index page.
        $pattern = '/(' . $base . '\/mod\/flexaccess\/index\.php\?id=)([0-9]+)/';
        $content = preg_replace($pattern, '$@FLEXACCESSINDEX*$2@$', $content);

        return $content;
    }
}
