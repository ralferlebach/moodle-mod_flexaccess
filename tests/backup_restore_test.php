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

namespace mod_flexaccess;

use PHPUnit\Framework\Attributes\CoversClass;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/backup/util/includes/backup_includes.php');
require_once($CFG->dirroot . '/backup/util/includes/restore_includes.php');

/**
 * Backup and restore roundtrip test for mod_flexaccess.
 *
 * @package    mod_flexaccess
 * @copyright  2026 Ralf Erlebach
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
#[CoversClass(\backup_flexaccess_activity_structure_step::class)]
#[CoversClass(\restore_flexaccess_activity_structure_step::class)]
final class backup_restore_test extends \advanced_testcase {
    /**
     * A FlexAccess instance survives a backup and restore into a new course.
     *
     * @return void
     */
    public function test_backup_then_restore_preserves_instance(): void {
        global $DB, $USER, $CFG;
        $this->resetAfterTest();
        $this->setAdminUser();

        $generator = $this->getDataGenerator();
        $course = $generator->create_course();
        $module = $generator->create_module('flexaccess', [
            'course' => $course->id,
            'name' => 'Entry point',
            'profilefieldsjson' => '{"fields":["email"]}',
        ]);

        $backupid = $this->backup_course($course->id, (int) $USER->id);
        $newcourse = $generator->create_course();
        $this->restore_course($backupid, $newcourse->id, (int) $USER->id);

        $restored = $DB->get_records('flexaccess', ['course' => $newcourse->id]);
        $this->assertCount(1, $restored);
        $instance = reset($restored);
        $this->assertSame('Entry point', $instance->name);
        $this->assertSame('{"fields":["email"]}', $instance->profilefieldsjson);
    }

    /**
     * Back up a course and return the backup id.
     *
     * @param int $courseid Course to back up.
     * @param int $userid Operator user id.
     * @return string Backup id.
     */
    private function backup_course(int $courseid, int $userid): string {
        $bc = new \backup_controller(
            \backup::TYPE_1COURSE,
            $courseid,
            \backup::FORMAT_MOODLE,
            \backup::INTERACTIVE_NO,
            \backup::MODE_GENERAL,
            $userid
        );
        $bc->execute_plan();
        $results = $bc->get_results();
        $file = $results['backup_destination'];
        $dir = make_backup_temp_directory('rt' . $courseid);
        $file->extract_to_pathname(get_file_packer('application/vnd.moodle.backup'), $dir);
        $bc->destroy();
        return 'rt' . $courseid;
    }

    /**
     * Restore a previously extracted backup into a target course.
     *
     * @param string $backupid Extracted backup directory id.
     * @param int $courseid Target course id.
     * @param int $userid Operator user id.
     * @return void
     */
    private function restore_course(string $backupid, int $courseid, int $userid): void {
        $rc = new \restore_controller(
            $backupid,
            $courseid,
            \backup::INTERACTIVE_NO,
            \backup::MODE_GENERAL,
            $userid,
            \backup::TARGET_EXISTING_ADDING
        );
        $this->assertTrue($rc->execute_precheck());
        $rc->execute_plan();
        $rc->destroy();
    }
}
