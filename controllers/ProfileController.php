<?php
/**
 * controllers/ProfileController.php
 * MEMBER 4: Consult my personal information + Edit my personal information
 *
 * Same rules as the workshop:
 *  - ALL the logic is here (SQL, validation, session, CSRF)
 *  - Prepared statements only (SQL injection protection)
 *  - The role and the id are NEVER read from the form
 */
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/UserModel.php';

class ProfileController
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnexion();
    }

    // ==============================================================
    // CSRF (same session key as AuthController: 'csrf_token')
    // ==============================================================
    public function getCsrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    private function verifyCsrfToken(?string $token): bool
    {
        if (!$token || empty($_SESSION['csrf_token'])) {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }

    // ==============================================================
    // DATA ACCESS (prepared statements - SQL injection protection)
    // ==============================================================
    public function findById(int $id): ?UserModel
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ? UserModel::fromArray($row) : null;
    }

    /**
     * Is the email or username already used by ANOTHER user?
     * (we exclude the current user, otherwise he could never save his own data)
     */
    private function existsForOtherUser(string $email, string $username, int $currentId): bool
    {
        $stmt = $this->db->prepare(
            'SELECT id FROM users
             WHERE (email = :email OR username = :username) AND id <> :id
             LIMIT 1'
        );
        $stmt->execute([
            'email'    => $email,
            'username' => $username,
            'id'       => $currentId,
        ]);
        return (bool) $stmt->fetch();
    }

    private function updateUser(UserModel $user): bool
    {
        // NB: only username and email are updated. Never role, never password here.
        $stmt = $this->db->prepare(
            'UPDATE users SET username = :username, email = :email WHERE id = :id'
        );
        return $stmt->execute([
            'username' => $user->getUsername(),
            'email'    => $user->getEmail(),
            'id'       => $user->getId(),
        ]);
    }

    // ==============================================================
    // CONSULT MY PERSONAL INFORMATION
    // ==============================================================
    /**
     * Returns the logged-in user (the id comes from the SESSION, never from the URL)
     */
    public function getProfile(): ?UserModel
    {
        return $this->findById((int) ($_SESSION['user_id'] ?? 0));
    }

    // ==============================================================
    // EDIT MY PERSONAL INFORMATION
    // ==============================================================
    public function updateProfile(array $post): array
    {
        $errors = [];

        if (!$this->verifyCsrfToken($post['csrf_token'] ?? null)) {
            $errors[] = "Invalid security token. Please try again.";
            return $errors;
        }

        // The id comes from the session: a user can only edit HIS OWN account
        $userId   = (int) ($_SESSION['user_id'] ?? 0);
        $username = trim($post['username'] ?? '');
        $email    = trim($post['email'] ?? '');

        if ($username === '' || $email === '') {
            $errors[] = "All fields are required.";
        }
        if (!preg_match('/^[a-zA-Z0-9_\-]{3,50}$/', $username)) {
            $errors[] = "Username must be 3 to 50 characters (letters, digits, _ or -).";
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email address.";
        }
        if (!empty($errors)) {
            return $errors;
        }

        if ($this->existsForOtherUser($email, $username, $userId)) {
            $errors[] = "This email or username is already taken.";
            return $errors;
        }

        $user = $this->findById($userId);
        if (!$user) {
            $errors[] = "User not found.";
            return $errors;
        }

        // Only these two fields are changed. Role stays untouched.
        $user->setUsername($username);
        $user->setEmail($email);

        if (!$this->updateUser($user)) {
            $errors[] = "An error occurred while updating your information.";
            return $errors;
        }

        // Keep the session in sync (the navbar / dashboard read $_SESSION['username'])
        $_SESSION['username'] = $user->getUsername();

        return []; // empty array = success
    }
}