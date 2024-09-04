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
    var gif1 = "../local/avatar/pix/r2d2.gif";
    var gif2 = "../local/avatar/pix/seal_basic.gif";
    var gif3 = "../local/avatar/pix/goose_basic.gif";
    var gif4 = "../local/avatar/pix/stormtrooper.gif";
    var gifs = [gif1,gif2,gif3,gif4];

    Log.log("The init function was called");

    const avatarMoodlenautDiv = document.createElement('div');
    avatarMoodlenautDiv.style.cssText = 'position:absolute;' +
        'width:100%;' +
        'height:200px;' +
        'z-index:-10;' +
        'overflow:hidden;' +
        'bottom:0';
    avatarMoodlenautDiv.setAttribute("id", "avatar-moodlenautspace");
    document.getElementById("page-wrapper").appendChild(avatarMoodlenautDiv);

    // THE GOOSE INJECTION
    const avatarMoodlenautContainer = [];
    for (let i = 0; i < 5; i++) {
        var ava = createMoodlenautContainer(gifs[Math.floor(Math.random() * 4)],i);
        avatarMoodlenautContainer.push(ava);
        document.getElementById("avatar-moodlenautspace").appendChild(ava);
    }

    // MOVE THE GOOSE.
    for (let id = 0; id < 5; id++) {
        const random = Math.floor(Math.random() * 5000);
        setInterval(() => {
            document.getElementById("avatar-moodlenautContainer"+id).style.left = "110%";
        }, random);
    }

    /**
     * Create a avatar with an id and a gif
     * @param {String} gif gif for the avatar
     * @param {int} id id for the avatar
     */
    function createMoodlenautContainer(gif,id){
        const avatarMoodlenautContainer = document.createElement('div');
        avatarMoodlenautContainer.style.cssText = 'position:absolute;' +
            'width:64px;' +
            'height:64px;' +
            'z-index:100;' +
            'background-image:url("'+gif+'");' +
            'overflow:hidden;' +
            'bottom:0px;' +
            'background-size:cover;';
        avatarMoodlenautContainer.setAttribute("id", "avatar-moodlenautContainer"+id);
        avatarMoodlenautContainer.style.left = "-70px";
        avatarMoodlenautContainer.style.transition = "left 20s linear";
        return avatarMoodlenautContainer;
    }
};
