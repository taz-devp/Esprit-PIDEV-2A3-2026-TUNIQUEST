<?php
/**
 * views/profile.php
 * MEMBER 4: Consult my personal information
 * Expects: $user (UserModel)
 */
ob_start();
?>
<div class="container wide">
    <h1>My personal information</h1>

    <?php if (!empty($_GET['updated'])): ?>
        <div class="alert alert-success">Your information has been updated successfully.</div>
    <?php endif; ?>

    <p>
        <span class="badge <?= $user->getRole() === 'admin' ? 'badge-admin' : 'badge-user' ?>">
            Role: <?= htmlspecialchars($user->getRole(), ENT_QUOTES, 'UTF-8') ?>
        </span>
    </p>

    <table>
        <tr><th>ID</th><td><?= $user->getId() ?></td></tr>
        <tr><th>Username</th><td><?= htmlspecialchars($user->getUsername(), ENT_QUOTES, 'UTF-8') ?></td></tr>
        <tr><th>Email</th><td><?= htmlspecialchars($user->getEmail(), ENT_QUOTES, 'UTF-8') ?></td></tr>
        <tr><th>Member since</th><td><?= htmlspecialchars($user->getCreatedAt(), ENT_QUOTES, 'UTF-8') ?></td></tr>
    </table>

    <a href="index.php?action=edit_profile"><button type="button">Edit my information</button></a>

    <div class="switch-link">
        <a href="index.php?action=<?= $user->getRole() === 'admin' ? 'admin' : 'dashboard' ?>">Back to my space</a>
    </div>
</div>
<?php
$content = ob_get_clean();
$title = 'My profile - MyApp';
require __DIR__ . '/layout.php';