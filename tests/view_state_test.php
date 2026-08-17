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
 * Tests for the FlexAccess activity view-state resolver.
 *
 * @package    mod_flexaccess
 * @copyright  2026 Ralf Erlebach
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_flexaccess;

use mod_flexaccess\local\view_state;

/**
 * View-state tests.
 */
final class view_state_test extends \advanced_testcase {
    /**
     * A temporary user gets the self-activation state.
     */
    public function test_temporary_user(): void {
        $this->assertSame(view_state::TEMPORARY, view_state::resolve('temporary user', true));
    }

    /**
     * An authenticated user (or any non-temporary type) gets the authenticated state.
     */
    public function test_authenticated_user(): void {
        $this->assertSame(view_state::AUTHENTICATED, view_state::resolve('authenticated user', true));
        $this->assertSame(view_state::AUTHENTICATED, view_state::resolve(null, true));
    }

    /**
     * Without the auth facade, the state is unavailable regardless of type.
     */
    public function test_unavailable_without_auth(): void {
        $this->assertSame(view_state::UNAVAILABLE, view_state::resolve('temporary user', false));
        $this->assertSame(view_state::UNAVAILABLE, view_state::resolve(null, false));
    }
}
