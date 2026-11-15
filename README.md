# Smart Currency Converter (PHP + MySQL)

A plain PHP port of the original Java/Spring Boot app: live exchange rates,
conversion history saved in MySQL, user accounts with an admin area.

Currencies: USD, EUR, GBP, JPY, CAD, JOD (edit `CURRENCIES` in `config.php` to change).

## Requirements

- XAMPP 8.2 (PHP 8.2 + MariaDB/MySQL), installed at `C:\xampp`
- Internet access (rates come from https://open.er-api.com, no API key needed)

## First-time setup

1. Start **MySQL** from the XAMPP Control Panel.
2. Copy the config (already done if `config.php` exists):
   ```
   copy config.example.php config.php
   ```
3. Create the database and tables (this also wipes existing data, so only run it once):
   ```
   C:\xampp\mysql\bin\mysql.exe -u root < setup.sql
   ```

## Run

From this folder:

```
C:\xampp\php\php.exe -S localhost:8000
```

Open http://localhost:8000 (you are sent to the login page first).

## Default login

- Username: `admin`
- Password: `admin12345`

**Change it after the first login:** log out, click "Forgot your password?",
enter `admin`, open the reset link that appears, and choose a new password.

## Pages

| Page         | Who        | What                                              |
|--------------|------------|---------------------------------------------------|
| `login.php`  | everyone   | Log in                                            |
| `forgot.php` | everyone   | Create a password reset link (shown on screen)    |
| `reset.php`  | everyone   | Set a new password from a reset link              |
| `index.php`  | logged in  | Converter + your last 5 conversions               |
| `logs.php`   | logged in  | All your conversions (admins see everyone's)      |
| `admin.php`  | admin only | List users, create users                          |

## Notes

- **Demo mode password reset:** in production the reset link would be emailed;
  here it is shown on screen. Links expire after 30 minutes and only a SHA-256
  hash of the token is stored.
- All SQL with user input uses prepared statements (procedural mysqli).
- All output is escaped with `htmlentities()`; every form has a CSRF token.
- Code layout: pages handle the request and render; SQL lives in `includes/users.php`
  and `includes/conversions.php`; the rate API call is in `includes/rates.php`;
  sessions/login/CSRF are in `includes/auth.php`.
- Rates By [Exchange Rate API](https://www.exchangerate-api.com) (link required by
  their terms, shown in the footer of every page).
