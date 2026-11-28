/**
 * assets/js/validation.js
 * MEMBER 4: client-side validation for the "Edit my information" form.
 *
 * Why an external file? index.php sends the header
 * "Content-Security-Policy: default-src 'self'", which BLOCKS inline <script>.
 * A script loaded from our own site ('self') is allowed.
 *
 * NOTE: this is only for user comfort. The real validation is on the SERVER
 * (AuthController::updateProfile). Never trust the browser alone.
 */
document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('edit-profile-form');
    var box  = document.getElementById('client-errors');
    if (!form || !box) {
        return;
    }

    form.addEventListener('submit', function (event) {
        var username = form.elements['username'].value.trim();
        var email    = form.elements['email'].value.trim();
        var errors   = [];

        if (username === '' || email === '') {
            errors.push('All fields are required.');
        } else {
            if (!/^[a-zA-Z0-9_\-]{3,50}$/.test(username)) {
                errors.push('Username must be 3 to 50 characters (letters, digits, _ or -).');
            }
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                errors.push('Invalid email address.');
            }
        }

        // Clear the previous messages
        while (box.firstChild) {
            box.removeChild(box.firstChild);
        }

        if (errors.length > 0) {
            event.preventDefault(); // stop the form: nothing is sent to the server
            errors.forEach(function (message) {
                var line = document.createElement('div');
                line.textContent = message; // textContent (not innerHTML): no XSS
                box.appendChild(line);
            });
            box.hidden = false;
        } else {
            box.hidden = true;
        }
    });
});