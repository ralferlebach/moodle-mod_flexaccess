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
 * Tests for the FlexAccess activation activity boundary.
 *
 * @package    mod_flexaccess
 * @copyright  2026 Ralf Erlebach
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_flexaccess;

/**
 * Activation manager tests.
 *
 * @package    mod_flexaccess
 * @covers     \mod_flexaccess\local\activation_manager
 */
final class activation_manager_test extends \advanced_testcase {
    /**
     * Skip when the required sibling plugin is not installed (per-plugin CI).
     *
     * @return void
     */
    protected function setUp(): void {
        parent::setUp();
        global $DB;
        if (!$DB->get_manager()->table_exists('auth_flexaccess_account')) {
            $this->markTestSkipped('Requires the auth_flexaccess sibling plugin to be installed.');
        }
    }

    /**
     * A regular Moodle user is not a temporary FlexAccess user.
     */
    public function test_regular_user_is_not_temporary(): void {
        $this->resetAfterTest();
        $user = $this->getDataGenerator()->create_user();
        $this->assertFalse(\mod_flexaccess\local\activation_manager::is_temporary_user($user->id));
    }
}
