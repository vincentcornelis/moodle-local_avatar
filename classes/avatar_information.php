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
 * Avatar information
 *
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @package   local_avatar
 * @copyright 04/09/2024 LdesignMedia.nl - Luuk Verhoeven
 * @author    Vincent Cornelis
 **/

namespace local_avatar;

use core_user;

/**
 * Class avatar
 *
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @package   local_avatar
 * @copyright 04/09/2024 LdesignMedia.nl - Luuk Verhoeven
 * @author    Vincent Cornelis
 **/
class avatar_information {

    /**
     * Get necessary information for JavaScript to be able to display avatars.
     *
     * @return object
     */
    public function get_avatar_js_information(): object {
        global $USER;

        if (!$this->avatars_enabled()) {
            return (object) [];
        }

        return (object) [
            'userid' => $USER->id,
            'movement' => $this->movement_enabled(),
            'avatars' => $this->get_user_visible_avatars(),
        ];

    }

    /**
     * Check if the user has enabled avatars.
     *
     * @return false|mixed
     */
    private function avatars_enabled() {
        global $DB, $USER;

        return $DB->get_field('local_avatar', 'showotheravatars', ['userid' => $USER->id]);
    }

    /**
     * Check if own avatar is enabled.
     *
     * @return false|mixed
     */
    private function own_avatar_enabled() {
        global $DB, $USER;

        return $DB->get_field('local_avatar', 'showownavatar', ['userid' => $USER->id]);
    }

    /**
     * Check if user has movement enabled.
     *
     * @return false|mixed
     */
    private function movement_enabled() {
        global $DB, $USER;

        return $DB->get_field('local_avatar', 'avatarsmovement', ['userid' => $USER->id]);
    }

    /**
     * Get the maximum number of visible avatars.
     *
     * @return false|mixed
     */
    private function get_max_visible() {
        global $DB, $USER;

        return $DB->get_field('local_avatar', 'shownumberofavatars', ['userid' => $USER->id]);
    }

    /**
     * Get the user visible avatars.
     *
     * @return array
     */
    private function get_user_visible_avatars() {
        global $DB, $USER;

        $visibleusers = helper::get_user_visible_users();
        $maxvisible = $this->get_max_visible();

        if ($maxvisible > 0) {
            $visibleusers = array_slice($visibleusers, 0, $maxvisible);
        }

        if ($this->own_avatar_enabled()) {
            if ($maxvisible > 0) {
                array_pop($visibleusers);
            }
            $visibleusers[] = $USER->id;
        }

        if (empty($visibleusers)) {
            return [];
        }

        [$insql, $inparams] = $DB->get_in_or_equal($visibleusers, SQL_PARAMS_NAMED);

        // Filter out users who have their visibility set to none.
        $filteredvisibleusers = array_keys(array_filter($DB->get_records_select_menu(
            'local_avatar',
            'userid ' . $insql,
            $inparams,
            '',
            'userid, showownavatartoothers'
        )));

        if (empty($filteredvisibleusers)) {
            return [];
        }

        $onlineusers = $this->check_online_status($filteredvisibleusers);

        if (empty($onlineusers)) {
            return [];
        }

        [$insql, $inparams] = $DB->get_in_or_equal($onlineusers, SQL_PARAMS_NAMED);

        $selectedavatars = $DB->get_records_select_menu(
            'local_avatar',
            'userid ' . $insql,
            $inparams,
            '',
            'userid, selectedavatar'
        );

        $avatars = [];

        foreach ($onlineusers as $onlineuser) {

            $user = core_user::get_user($onlineuser);

            $avatars[$onlineuser] = [
                'fullname' => fullname($user),
                'avatar' => $selectedavatars[$onlineuser],
            ];
        }

        return $avatars;
    }

    /**
     * Check if user is online.
     *
     * @param array $users
     *
     * @return array
     */
    private function check_online_status(array $users): array {
        global $DB;

        [$insql, $inparams] = $DB->get_in_or_equal($users, SQL_PARAMS_NAMED);

        $select = "lastaccess > :timefrom
                   AND lastaccess <= :now
                   AND id " . $insql;

        $timetoshowusers = 300; // Seconds default.
        $timefrom = 100 * floor((time() - $timetoshowusers) / 100); // Round to nearest 100 seconds for better query cache.

        $params = [
            'timefrom' => $timefrom,
            'now' => time(),
        ];

        $params = array_merge($params, $inparams);

        return $DB->get_fieldset_select('user', 'id', $select, $params);

    }

}
