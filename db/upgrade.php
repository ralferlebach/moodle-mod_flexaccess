<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Upgrade steps for mod_flexaccess.
 *
 * Activity modules must ship this file even when there is nothing to upgrade yet: Moodle calls
 * xmldb_flexaccess_upgrade() on every version bump, and the plugin validation of the Moodle plugins
 * directory rejects an activity module without it.
 *
 * @package    mod_flexaccess
 * @copyright  2026 Ralf Erlebach
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Upgrade the mod_flexaccess plugin.
 *
 * The activity stores nothing beyond its own instance table, which install.xml has defined
 * unchanged since the first release, so no upgrade step has been necessary so far. New steps are
 * added here as "if ($oldversion < <version>) { ... }" blocks, each closed by a savepoint.
 *
 * @param int $oldversion The version we are upgrading from.
 * @return bool
 */
function xmldb_flexaccess_upgrade($oldversion) {
    return true;
}
