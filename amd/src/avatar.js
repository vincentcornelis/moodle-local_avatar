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
 * JavaScript for displaying an avatar.
 *
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @copyright 28/03/2022 LdesignMedia.nl - Luuk Verhoeven
 * @author    Vincent Cornelis
 */

import Log from 'core/log';

/**
 * Initialise.
 */
export const init = () => {

    Log.log("The init function was called");

    const avatarMoodlenautDiv = document.createElement('div');
    avatarMoodlenautDiv.style.cssText = 'position:absolute;' +
        'width:100%;' +
        'height:200px;' +
        'z-index:-10;' +
        'overflow:hidden;bottom:0';
    avatarMoodlenautDiv.setAttribute("id", "avatar-moodlenautspace");
    document.getElementById("page-wrapper").appendChild(avatarMoodlenautDiv);

    // THE GOOSE INJECTION.
    const avatarMoodlenautContainer = document.createElement('div');
    avatarMoodlenautContainer.style.cssText = 'position:absolute;' +
        'width:64px;height:64px;' +
        'z-index:100;' +
        'background-image:url("../local/avatar/pix/r2d2.gif");' +
        'overflow:hidden;bottom:0px;' +
        'background-size:cover;';
    avatarMoodlenautContainer.setAttribute("id", "avatar-moodlenautContainer");
    avatarMoodlenautContainer.style.left = "10px";
    avatarMoodlenautContainer.style.transition = "left 20s linear";
    document.getElementById("avatar-moodlenautspace").appendChild(avatarMoodlenautContainer);

    // MOVE THE GOOSE.
    // Main(3000,avatarMoodlenautContainer);
    setInterval(() => {
        avatarMoodlenautContainer.style.left = "90%";
    }, 3000);
};
