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
defined('MOODLE_INTERNAL') || die();

function local_avatar_before_footer() {
    global $PAGE;

    // Check if we're on a course page
    if ($PAGE->pagetype === 'course-view') {
        // Include the JavaScript file
        $PAGE->requires->js('/local/avatar/amd/src/avatar.js');

        // Add the HTML for the moving image
        echo '<div id="image-container">
                <img id="moving-image" src="' . $CFG->wwwroot . '/local/avatar/pix/pic.png" alt="Moving Image">
              </div>';
    }
}


