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
 * @copyright 03/09/2024 LdesignMedia.nl - Luuk Verhoeven
 * @author    Vincent Cornelis
 **/
 
use local_avatar\form\myavatar_form; // Import the namespaced class.


require_once('../../../config.php');

global $DB;
global $USER;

$url = new moodle_url( '/local/avatar/view/myavatar.php' );
$PAGE->set_url($url);
$PAGE->set_context(context_system::instance()); // Set the context.
$PAGE->set_title(get_string('myavatarsettings', 'local_avatar')); // Set the page title.
$PAGE->set_heading(get_string('myavatarsettings', 'local_avatar')); // Set the page heading.

$mform = new myavatar_form(); // Instantiate the form.

// Pre-fill the form with the user's data.
$data = $DB->get_record('local_avatar', ['userid' => $USER->id]);
$mform->set_data($data);

$mform->handle_submission(); // Handle the form submission.

echo $OUTPUT->header(); // Display the header.

echo $mform->render();

echo $OUTPUT->footer(); // Display the footer.


