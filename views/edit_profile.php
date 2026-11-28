<?php
/**
 * views/edit_profile.php
 * MEMBER 4: Edit my personal information
 * Expects: $errors (array), $old (array: username, email), $csrfToken (string)
 *
 * Validation levels:
 *   1. HTML5 attributes (required, pattern, minlength, maxlength, type=email)
 *   2. JavaScript (assets/js/validation.js)
 *   3. PHP on the server (AuthController::updateProfile)  <- the one that really protects
 */
ob_start();
$old = $old ?? [];
?>
<div class="container">
    <h1>Edit my information</h1>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <?php foreach ($errors as $error): ?>
                <div><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Filled by assets/js/validation.js when the browser detects an error -->
    <div id="client-errors" class="alert alert-error" hidden></div>

    <form id="edit-profile-form" method="POST" action="index.php?action=edit_profile">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">

        <label for="username">Username (3 to 50 characters: letters, digits, _ or -)</label>
        <input type="text" id="username" name="username" required
               minlength="3" maxlength="50" pattern="[a-zA-Z0-9_\-]{3,50}"
               value="<?= htmlspecialchars($old['username'] ?? '', ENT_QUOTES, 'UTF-8') ?>">

        <label for="email">Email address</label>
        <input type="email" id="email" name="email" required maxlength="150"
               value="<?= htmlspecialchars($old['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>">

        <button type="submit">Save changes</button>
    </form>

    <div class="switch-link">
        <a href="index.php?action=profile">Cancel</a>
    </div>
</div>

<script src="assets/js/validation.js"></script>
<?php
$content = ob_get_clean();
$title = 'Edit profile - MyApp';
require __DIR__ . '/layout.php';