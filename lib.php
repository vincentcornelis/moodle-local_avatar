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
 * Library functions
 *
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @package   local_avatar
 * @copyright 03/09/2024 LdesignMedia.nl - Luuk Verhoeven
 * @author    Vincent Cornelis
 **/

use local_avatar\helper;

require_once($CFG->dirroot . '/local/avatar/classes/helper.php');

/**
 * Execute before http headers.
 *
 * @return void
 */
function local_avatar_before_http_headers(): void {
    global $PAGE;

    // Check if we're on a course, or module page.
    if ($PAGE->context->contextlevel !== CONTEXT_COURSE && $PAGE->context->contextlevel !== CONTEXT_MODULE) {
        return;
    }

    // Test helper function.
    $helper = new helper();

    $visibleusers = $helper->get_user_visible_users();
    // echo '<pre>';
    // print_r($visibleusers);
    // echo '</pre>';

    $PAGE->requires->js_call_amd('local_avatar/avatar', 'init');
}

/**
 * Doesn't really extend the user navigation, but edits the custom user menu items to add the avatar link.
 *
 * @param navigation_node $navigation
 * @param object $user
 * @param object $usercontext
 * @param object $course
 * @param object $coursecontext
 *
 * @return void
 */
function local_avatar_extend_navigation_user(
    navigation_node $navigation,
    object $user,
    object $usercontext,
    object $course,
    object $coursecontext
): void {

    $enabled = get_config('local_avatar', 'enabled');

    if (!$enabled) {
        return;
    }

    $myavataritem = "nav:myavatar,local_avatar|/local/avatar/view/myavatar.php";

    $custommenuitems = explode(PHP_EOL, get_config('core', 'customusermenuitems'));

    if (!empty($custommenuitems)) {

        // Don't add another menu item if it already exists.
        if (in_array($myavataritem, $custommenuitems)) {
            return;
        }

        // Check if we can add the avatar link after the profile link.
        $myprofileindex = null;

        foreach ($custommenuitems as $key => $custommenuitem) {
            if (stristr($custommenuitem, 'profile.php')) {
                $myprofileindex = $key;
                break;
            }
        }

        array_splice($custommenuitems, $myprofileindex + 1, 0, $myavataritem);

    } else {
        $custommenuitems[] = $myavataritem;
    }

    set_config('customusermenuitems', implode(PHP_EOL, $custommenuitems));
    theme_reset_all_caches();

}
