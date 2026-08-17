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
 * Boundary adapter from the activity to auth_flexaccess.
 *
 * @copyright  2026 Ralf Erlebach
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_flexaccess\local;

/** Activation orchestration boundary. */
final class activation_manager {
    /**
     * Whether the user is currently a temporary FlexAccess user.
     *
     * @param int $userid User ID.
     * @return bool
     */
    public static function is_temporary_user(int $userid): bool {
        global $DB;
        $account = $DB->get_record('auth_flexaccess_account', ['userid' => $userid], 'id,accounttype');
        return $account && $account->accounttype === \auth_flexaccess\local\account_type::TEMPORARY_USER;
    }
}
