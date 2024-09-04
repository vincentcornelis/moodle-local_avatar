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

    /**
     * Get user visible users
     *
     * @return array
     */
    public static function get_user_visible_users() {
        global $USER, $COURSE, $DB;

        $course = get_course($COURSE->id);
        $context = \context_course::instance($course->id);

        if (!is_enrolled($context, $USER->id)) {
            return [];
        }

        $visibleusers = [];

        if ($course->groupmode == NOGROUPS) {
            // Return all users in the course.
            $sql = "SELECT u.id
                FROM {user} u
                JOIN {user_enrolments} ue ON ue.userid = u.id
                JOIN {enrol} e ON e.id = ue.enrolid
                WHERE e.courseid = :courseid AND ue.status = :active";

            // Parameters for the query.
            $params = [
                'courseid' => $COURSE->id,
                'active' => ENROL_USER_ACTIVE,
            ];

            // Execute the query and return the list of user IDs.
            $visibleusers = $DB->get_fieldset_sql($sql, $params);

            return $visibleusers;
        }
        // Check if the user is enrolled in the course and has an active enrollment.
        $usergroups = groups_get_user_groups($COURSE->id, $USER->id);
        $usergroups = reset($usergroups);

        // Check if the user is in any groups.
        if (!empty($usergroups)) {
            return array_keys(groups_get_groups_members($usergroups));
        }

        return $visibleusers;
    }

}
