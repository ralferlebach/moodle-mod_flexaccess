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
 * Test data generator for mod_flexaccess.
 *
 * @package    mod_flexaccess
 * @copyright  2026 Ralf Erlebach
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Creates FlexAccess activity instances for tests.
 *
 * @package    mod_flexaccess
 */
class mod_flexaccess_generator extends testing_module_generator {
    /**
     * Create a FlexAccess activity instance.
     *
     * @param array|\stdClass|null $record Instance overrides.
     * @param array|null $options Generator options.
     * @return \stdClass The created instance record.
     */
    public function create_instance($record = null, ?array $options = null) {
        $record = (object) (array) $record;
        $defaults = [
            'name' => 'FlexAccess',
            'intro' => '',
            'introformat' => FORMAT_HTML,
            'profilefieldsjson' => '{}',
        ];
        foreach ($defaults as $field => $value) {
            if (!isset($record->$field)) {
                $record->$field = $value;
            }
        }
        return parent::create_instance($record, (array) $options);
    }
}
