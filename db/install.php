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
 * Provides code to be executed during the module installation
 *
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @package   local_avatar
 * @copyright 03/09/2024 LdesignMedia.nl - Luuk Verhoeven
 * @author    Vincent Cornelis
 **/

use local_avatar\avatar_information;

/**
 * Post installation procedure
 *
 * @return void
 */
function xmldb_local_avatar_install(): void {
    global $DB;

    // Get category sortorder.
    $sql = "SELECT MAX(sortorder) as sortorder FROM {customfield_category}";

    $sortorder = $DB->get_record_sql($sql);
    $sortorder = $sortorder->sortorder + 1;

    // Add course customfield category.
    $categoryname = 'Avatar';

    if (!$DB->record_exists('customfield_category', ['name' => $categoryname])) {
        $category = [
            'name' => $categoryname,
            'descriptionformat' => 0,
            'sortorder' => $sortorder,
            'timecreated' => time(),
            'timemodified' => time(),
            'component' => 'core_course',
            'area' => 'course',
            'itemid' => 0,
            'contextid' => 1,
        ];

        $categoryid = $DB->insert_record('customfield_category', $category);
    }

    // Add course customfield.
    $shortname = 'avatar_enabled';

    if (!$DB->record_exists('customfield_field', ['shortname' => $shortname])) {

        $name = 'Avatar enabled';
        $configdata = json_encode([
            'required' => "0",
            'uniquevalues' => "0",
            'checkbydefault' => "1",
            'locked' => "0",
            'visibility' => "2",
        ],
            JSON_THROW_ON_ERROR
        );

        $customfield = [
            'shortname' => $shortname,
            'name' => $name,
            'type' => 'checkbox',
            'descriptionformat' => 1,
            'sortorder' => 0,
            'categoryid' => $categoryid,
            'configdata' => $configdata,
            'timecreated' => time(),
            'timemodified' => time(),
        ];

        $DB->insert_record('customfield_field', $customfield);
    }

    avatar_information::create_random_avatars();

}
