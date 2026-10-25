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
 * Activity timestamps from Moodle's clock (issue #6).
 *
 * @package    mod_flexaccess
 * @copyright  2026 Ralf Erlebach
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_flexaccess;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/mod/flexaccess/lib.php');
require_once($CFG->dirroot . '/course/modlib.php');

/**
 * Create and update set timecreated/timemodified from \core\clock.
 *
 * @package    mod_flexaccess
 * @covers ::flexaccess_add_instance
 * @covers ::flexaccess_update_instance
 */
final class lib_test extends \advanced_testcase {
    /**
     * Creating an activity stamps both times with the clock's exact value.
     */
    public function test_create_uses_frozen_clock(): void {
        global $DB;
        $this->resetAfterTest();
        $this->mock_clock_with_frozen(1893456037);
        $course = $this->getDataGenerator()->create_course();
        $module = $this->getDataGenerator()->create_module('flexaccess', ['course' => $course->id]);
        $row = $DB->get_record('flexaccess', ['id' => $module->id], '*', MUST_EXIST);
        $this->assertSame(1893456037, (int) $row->timecreated);
        $this->assertSame(1893456037, (int) $row->timemodified);
    }

    /**
     * An update refreshes timemodified only, also when the data carries a creation time.
     */
    public function test_update_keeps_timecreated(): void {
        global $DB;
        $this->resetAfterTest();
        $clock = $this->mock_clock_with_frozen(1893456000);
        $course = $this->getDataGenerator()->create_course();
        $module = $this->getDataGenerator()->create_module('flexaccess', ['course' => $course->id]);

        $clock->set_to(1893459600);
        $this->assertTrue(flexaccess_update_instance((object) [
            'instance' => $module->id,
            'name' => 'Renamed',
            'timecreated' => 1,
        ]));
        $row = $DB->get_record('flexaccess', ['id' => $module->id], '*', MUST_EXIST);
        $this->assertSame(1893456000, (int) $row->timecreated);
        $this->assertSame(1893459600, (int) $row->timemodified);
        $this->assertSame('Renamed', $row->name);
    }

    /**
     * Through the course API with an advancing clock, every update moves timemodified forward.
     */
    public function test_updates_follow_incrementing_clock(): void {
        global $DB;
        $this->resetAfterTest();
        $this->setAdminUser();
        $this->mock_clock_with_incrementing(1893456000);
        $course = $this->getDataGenerator()->create_course();
        $module = $this->getDataGenerator()->create_module('flexaccess', ['course' => $course->id]);
        $created = (int) $DB->get_field('flexaccess', 'timecreated', ['id' => $module->id]);
        $this->assertGreaterThanOrEqual(1893456000, $created);

        $last = (int) $DB->get_field('flexaccess', 'timemodified', ['id' => $module->id]);
        for ($i = 1; $i <= 3; $i++) {
            $cm = get_coursemodule_from_instance('flexaccess', $module->id);
            [, , , $info] = get_moduleinfo_data($cm, $course);
            $info->name = 'Update ' . $i;
            $info->introeditor = ['text' => '', 'format' => FORMAT_HTML, 'itemid' => 0];
            update_moduleinfo($cm, $info, $course);
            $row = $DB->get_record('flexaccess', ['id' => $module->id], '*', MUST_EXIST);
            $this->assertGreaterThan($last, (int) $row->timemodified, 'Update ' . $i);
            $this->assertSame($created, (int) $row->timecreated);
            $last = (int) $row->timemodified;
        }
        $this->assertSame('Update 3', $row->name);
    }
}
