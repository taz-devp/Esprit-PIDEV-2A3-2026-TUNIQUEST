# TuniQuest – User Account Management Platform

## Overview

This project was developed as part of the PIDEV – 2nd Year Engineering Program at **Esprit School of Engineering** (Academic Year 2026–2027).

TuniQuest is a PHP web application built on the MVC architecture. It lets users create an account, authenticate securely, and manage their personal information, with a frontoffice for users and a backoffice for administrators.

## Features

- Create an account / Deactivate my account
- Log in / Log out
- Two-factor authentication (2FA)
- **Consult my personal information** (ID, username, email, role, registration date)
- **Modify my personal information** (username and email)
- Change password / Reset password by email
- Consult my history
- Update my professional identity

### Security measures (personal information module)

- The user id comes from the session, never from the URL or the form.
- The role can never be changed from the form (no self-promotion to admin).
- Prepared statements (SQL injection protection).
- CSRF token on forms, checked with `hash_equals()`.
- Output escaped with `htmlspecialchars()` (XSS protection).
- Input validation on 3 levels: HTML5, JavaScript and PHP (server side).

## Tech Stack

### Frontend

- HTML5 / CSS3
- JavaScript (client-side form validation)

### Backend

- PHP (MVC architecture, PDO)
- MySQL

## Architecture

The project follows the MVC pattern:
cd
exit
cd






## Contributors

| Member | Responsibility |
|---|---|
| Member 1 – <name> | Create an account, Deactivate my account |
| Member 2 – <name> | Login, Logout |
| Member 3 – <name> | Two-factor authentication |
| Member 4 – <name> | Consult / Modify my personal information |
| Member 5 – <name> | Change password, Reset password by email |
| Member 6 – <name> | Consult my history |
| Member 7 – <name> | Update professional identity |

## Academic Context

Developed at **Esprit School of Engineering – Tunisia**

PIDEV – 2A3 | 2026–2027

## Getting Started

### Prerequisites

- WAMP (or XAMPP) with PHP 7.4 or higher and MySQL
- Git

### Installation

1. Clone the repository into your web server folder (for WAMP: `www`):

   ```bash
   git clone https://github.com/taz-devp/Esprit-PIDEV-2A3-2026-TUNIQUEST.git
   cd Esprit-PIDEV-2A3-2026-TUNIQUEST
   ```

2. Create a MySQL database named `dbtuniquest` (phpMyAdmin) with the `users` table.
3. Check the connection settings in `config/database.php` (host, database name, user, password).
4. Start WAMP and open `http://localhost/Esprit-PIDEV-2A3-2026-TUNIQUEST/index.php`.

### Main routes

| Route | Description |
|---|---|
| `index.php?action=profile` | Consult my personal information |
| `index.php?action=edit_profile` | Modify my personal information |

## Acknowledgments

Thanks to our supervisors and to the teaching staff of **Esprit School of Engineering** for their guidance throughout this project.
