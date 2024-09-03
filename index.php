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
// Moodle-Konfigurationsdatei einbinden
require_once(__DIR__.'/../../config.php');

// Seiten-URL definieren
$PAGE->set_url(new moodle_url('/local/myplugin/index.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_title('Mein Plugin');
$PAGE->set_heading('Willkommen bei meinem Plugin');

// Authentifizierung erzwingen (falls erforderlich)
require_login();

// Header ausgeben
echo $OUTPUT->header();

// Hauptinhalt
echo '<div class="myplugin-content">';
echo '<h2>Willkommen zu meinem lokalen Plugin!</h2>';
echo '<p>Dies ist eine Beispielseite für ein lokales Moodle-Plugin.</p>';
echo '<p>Sie können hier beliebige Inhalte oder Funktionen hinzufügen.</p>';
echo '</div>';

// Footer ausgeben
echo $OUTPUT->footer();

