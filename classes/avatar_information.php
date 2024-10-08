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
     * Get avatars
     *
     * Fixed indexes for now
     *
     * @return string[]
     */
    public static function get_avatars(): array {

        $avatars = [
            1 => '/local/avatar/pix/goose_basic.gif',
            2 => '/local/avatar/pix/r2d2.gif',
            3 => '/local/avatar/pix/seal_basic.gif',
            4 => '/local/avatar/pix/stormtrooper.gif',
            5 => '/local/avatar/pix/sheep_basic.gif',
        ];

        array_walk($avatars, static function(&$avatar) {
            global $CFG;
            $avatar = $CFG->wwwroot . $avatar;
        });

        return $avatars;
    }

    /**
     * Create random avatars for all existing users.
     *
     * @var object
     */
    private object $settings;

    public static function create_random_avatars(): void {
        global $DB;

        $users = $DB->get_records('user', [], '', 'id');

        foreach ($users as $user) {
            self::create_random_avatar($user->id);
        }

    }

    /**
     * Create random avatar for newly created user.
     *
     * @param \core\event\user_created $event
     *
     * @return void
     */
    public static function user_created(\core\event\user_created $event): void {

        $user = $event->get_record_snapshot('user', $event->objectid);
        self::create_random_avatar($user->id);

    }

    /**
     * Create random avatar for user id.
     *
     * @param $userid
     *
     * @return void
     */
    public static function create_random_avatar($userid): void {
        global $DB;

        $min = array_key_first(self::get_avatars());
        $max = array_key_last(self::get_avatars());

        $avatar = rand($min, $max);

        $data = (object) [
            'userid' => $userid,
            'selectedavatar' => $avatar,
            'showownavatar' => 1,
            'showownavatartoothers' => 1,
            'showotheravatars' => 1,
            'avatarsmovement' => 1,
            'shownumberofavatars' => 50,
        ];

        // Update if exists.
        if ($record = $DB->get_record('local_avatar', ['userid' => $userid])) {
            $data->id = $record->id;
            $DB->update_record('local_avatar', $data);

            return;
        }

        // Otherwise insert.
        $DB->insert_record('local_avatar', $data);

    }

    /**
     * Get necessary information for JavaScript to be able to display avatars.
     *
     * @return object
     */
    public function get_avatar_js_information(): object {
        global $USER;

        $this->load_user_settings();

        if (!$this->avatars_enabled()) {
            return (object) [];
        }

        return (object) [
            'userid' => $USER->id,
            'movement' => $this->movement_enabled(),
            'avatars' => $this->get_user_visible_avatars(),
            'avatargifs' => self::get_avatars(),
        ];

    }

    /**
     * Load user settings.
     *
     * @return void
     */
    private function load_user_settings(): void {
        global $DB, $USER;

        // All users get a default avatar, so should always exist.
        $this->settings = $DB->get_record(
            'local_avatar',
            ['userid' => $USER->id],
            'id, showownavatar, showownavatartoothers, showotheravatars, avatarsmovement, shownumberofavatars',
        );
    }

    /**
     * Check if the user has enabled avatars.
     *
     * @return false|mixed
     */
    private function avatars_enabled() {
        return $this->settings->showotheravatars;
    }

    /**
     * Check if own avatar is enabled.
     *
     * @return false|mixed
     */
    private function own_avatar_enabled() {
        return $this->settings->showotheravatars;
    }

    /**
     * Check if user has movement enabled.
     *
     * @return false|mixed
     */
    private function movement_enabled() {
        return $this->settings->avatarsmovement;
    }

    /**
     * Get the maximum number of visible avatars.
     *
     * @return false|mixed
     */
    private function get_max_visible() {
        return $this->settings->shownumberofavatars;
    }

    /**
     * Get the user visible avatars.
     *
     * @return array
     */
    private function get_user_visible_avatars(): array {
        global $DB, $USER;

        $visibleusers = helper::get_user_visible_users();

        if (empty($visibleusers)) {
            return [];
        }

        $maxvisible = $this->get_max_visible();

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

        if ($maxvisible > 0) {
            $onlineusers = array_slice($onlineusers, 0, $maxvisible);
        }

        if ($this->own_avatar_enabled()) {
            if ($maxvisible > 0 && count($onlineusers) >= $maxvisible) {
                array_pop($onlineusers);
            }
            $onlineusers[] = $USER->id;
        }

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
