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
 *
 *
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @package   local_avatar
 * @copyright 04/09/2024 LdesignMedia.nl - Luuk Verhoeven
 * @author    Vincent Cornelis
 **/

namespace local_avatar;

/**
 * Class helper
 *
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @package   local_avatar
 * @copyright 04/09/2024 LdesignMedia.nl - Luuk Verhoeven
 * @author    Vincent Cornelis
 **/
class helper {

    public static function get_user_visible_users() {
        global $USER;

        // TODO: Check wich users the user can see.
        // Check if there are groups set in the course.
        // Check if there are groups, which group(s) the user is in.
        // Return the user-ids of the users that are in the same group(s) as the user.
        // Make sure only active enrollments are returned.
    }

}
