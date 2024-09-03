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
 **/

document.addEventListener('DOMContentLoaded', function () {
    const imageContainer = document.getElementById('image-container');
    const screenWidth = window.innerWidth;
    const animationDuration = 5; // Duration in seconds.

    // Set the initial position of the image.
    imageContainer.style.left = ` - 100px`;

    /**
     * Function to move the image across the screen.
     */
    function moveImage() {
        imageContainer.style.transition = `left ${animationDuration}s linear`;
        imageContainer.style.left = `${screenWidth}px`;

        // Reset the image position after it exits the screen.
        setTimeout(() => {
            imageContainer.style.transition = 'none';
            imageContainer.style.left = ` - 100px`;
            // Restart the animation.
            setTimeout(moveImage, 50);
        }, animationDuration * 1000);
    }

    // Start the animation.
    moveImage();
});
