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
 * Pure view-state resolution for the FlexAccess self-activation activity.
 *
 * Decides what the activity should render based on the viewer's FlexAccess classification,
 * without any dependency on the auth plugin being loadable (the value is passed in). This keeps
 * the branching logic unit-testable and the cross-plugin call runtime-lazy.
 *
 * @package    mod_flexaccess
 * @copyright  2026 Ralf Erlebach
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_flexaccess\local;

/**
 * Resolves the activity view state.
 *
 * @package    mod_flexaccess
 */
final class view_state {
    /**
     * Viewer is already an authenticated user.
     */
    public const AUTHENTICATED = 'authenticated';
    /**
     * Viewer is a temporary user who may self-activate.
     */
    public const TEMPORARY = 'temporary';
    /**
     * The auth facade is unavailable.
     */
    public const UNAVAILABLE = 'unavailable';

    /**
     * The account-type value denoting a temporary user (auth_flexaccess account_type).
     */
    private const TEMPORARY_USER = 'temporary user';

    /**
     * Resolve the view state.
     *
     * @param string|null $accounttype The viewer's FlexAccess account type, or null if unknown.
     * @param bool $authavailable Whether the auth_flexaccess facade is available.
     * @return string One of the state constants.
     */
    public static function resolve(?string $accounttype, bool $authavailable): string {
        if (!$authavailable) {
            return self::UNAVAILABLE;
        }
        if ($accounttype === self::TEMPORARY_USER) {
            return self::TEMPORARY;
        }
        return self::AUTHENTICATED;
    }
}
