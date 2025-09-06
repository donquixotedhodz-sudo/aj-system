# San Rafael Cave Resort - OTP Email Verification System

A modern booking system for San Rafael Cave Resort featuring user authentication with OTP email verification, room reservations, and administrative management.

## Features

- **Email Verification**: Secure OTP-based email verification for user registration
- **Rate Limiting**: Prevents spam by limiting OTP requests (2-minute cooldown)
- **Secure Storage**: OTPs are stored securely in the database with expiration times
- **Multiple Purposes**: Supports different OTP purposes (signup, login, password reset)
- **Auto-cleanup**: Expired OTPs are automatically cleaned up
- Room booking system
- Admin dashboard
- Responsive design
- Secure password handling

## System Components

### 1. Database Structure

The system uses an `otp_codes` table to store verification codes:

```sql
CREATE TABLE otp_codes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    otp_code VARCHAR(6) NOT NULL,
    purpose ENUM('signup', 'login', 'password_reset') NOT NULL,
    expires_at DATETIME NOT NULL,
    is_used BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email_purpose (email, purpose),
    INDEX idx_expires_at (expires_at)
);
```

### 2. Core Classes

#### OTPManager (`includes/otp_manager.php`)
Handles all OTP operations:
- `createOTP($email, $purpose)` - Generates a new 6-digit OTP
- `verifyOTP($email, $otp_code, $purpose)` - Verifies an OTP code
- `markOTPAsUsed($email, $otp_code, $purpose)` - Marks OTP as used
- `invalidateExistingOTPs($email, $purpose)` - Invalidates previous OTPs
- `cleanupExpiredOTPs()` - Removes expired OTPs
- `hasRecentOTP($email, $purpose, $minutes)` - Rate limiting check

#### EmailSender (`includes/email_config.php`)
Handles email sending functionality:
- `sendOTPEmail($email, $otp_code, $purpose)` - Sends OTP via email
- `testEmailConfiguration()` - Tests email setup

## Setup Instructions

### Prerequisites
- XAMPP (or similar) with PHP 7.4+ and MySQL
- Web browser
- Email server configuration

### Setup Steps

1. **Start XAMPP Services**
   - Start Apache and MySQL services from the XAMPP Control Panel

2. **Database Initialization** (choose one method)
   
   **Web Interface Method:**
   - Navigate to `http://localhost/src-final/database/setup.php`
   - Follow the on-screen instructions to initialize the database
   
   **Command Line Method:**
   - Open a terminal/command prompt
   - Navigate to the database directory: `cd c:\xampp\htdocs\src-final\database`
   - Run the initialization script: `php init_db_cli.php`
   
   Both methods will:
   - Create the necessary database and tables (including `otp_codes`)
   - Create a demo user with the following credentials:
     - Email: `demo@example.com`
     - Password: `password123`

3. **Email Configuration**
   - Configure your server's mail settings for PHP's `mail()` function
   - For XAMPP users: Enable `sendmail` in `php.ini`:
     ```ini
     [mail function]
     SMTP = localhost
     smtp_port = 25
     sendmail_from = noreply@sanrafaelcave.com
     ```

4. **Testing the System**
   - Go to `http://localhost/src-final/login.php`
   - Try creating a new account to test OTP verification
   - Use the demo credentials to log in directly

### Database Structure

- **Database Name**: `san_rafael_cave_db`

- **Tables**:
  - `users`: Stores user account information
    - `id`: Auto-incremented user ID
    - `fullname`: User's full name
    - `email`: User's email address (unique)
    - `password`: Hashed password
    - `created_at`: Timestamp of account creation
    - `updated_at`: Timestamp of last update
  
  - `user_sessions`: Stores "remember me" session tokens
    - `id`: Auto-incremented session ID
    - `user_id`: Foreign key to users table
    - `session_token`: Unique token for persistent login
    - `expires_at`: Expiration date/time
    - `created_at`: Timestamp of token creation
    
  - `otp_codes`: Stores OTP verification codes
    - `id`: Auto-incremented OTP ID
    - `email`: Email address for OTP
    - `otp_code`: 6-digit verification code
    - `purpose`: Purpose of OTP (signup, login, password_reset)
    - `expires_at`: Expiration timestamp
    - `is_used`: Whether OTP has been used
    - `created_at`: Creation timestamp

## Usage Guide

### User Registration Flow

1. User fills out registration form
2. System validates input
3. OTP is generated and sent to user's email
4. User enters OTP on verification page
5. Upon successful verification, account is created
6. User is automatically logged in

### Integration Example

```php
// Generate and send OTP
$otpManager = new OTPManager();
$emailSender = new EmailSender();

// Check rate limiting
if (!$otpManager->hasRecentOTP($email, 'signup', 2)) {
    $otp_code = $otpManager->createOTP($email, 'signup');
    $emailSender->sendOTPEmail($email, $otp_code, 'signup');
}

// Verify OTP
if ($otpManager->verifyOTP($email, $user_input_otp, 'signup')) {
    // OTP is valid, proceed with registration
    $otpManager->markOTPAsUsed($email, $user_input_otp, 'signup');
}
```

## Security Features

### 1. Rate Limiting
- Users can only request a new OTP every 2 minutes
- Prevents spam and abuse

### 2. Expiration
- OTPs expire after 10 minutes
- Automatic cleanup of expired codes

### 3. Single Use
- Each OTP can only be used once
- Used OTPs are marked in the database

### 4. Purpose-Specific
- OTPs are tied to specific purposes (signup, login, etc.)
- Prevents cross-purpose attacks

### 5. Traditional Security
- Password hashing using PHP's password_hash()
- Input sanitization
- CSRF protection (via session tokens)
- Secure cookie handling

### File Structure

- `/database/`
  - `init_db.php`: Database initialization script (web interface)
  - `init_db_cli.php`: Command-line initialization script
  - `san-rafael-cave_db.sql`: SQL schema file (includes otp_codes table)
  - `setup.php`: Setup guide page
  - `test_connection.php`: Database connection test

- `/includes/`
  - `auth_check.php`: Authentication verification
  - `db_check.php`: Database existence verification
  - `db_connect.php`: Database connection
  - `login.php`: Login processing
  - `logout.php`: Logout processing
  - `signup.php`: Registration processing with OTP
  - `complete_signup.php`: Completes registration after OTP verification
  - `otp_manager.php`: OTP generation and verification
  - `email_config.php`: Email sending functionality

- `login.php`: Login/signup form page
- `verify_otp.php`: OTP verification page

## Troubleshooting

### Email Not Sending
1. Check server mail configuration
2. Verify `sendmail` is enabled in PHP
3. Check spam/junk folders
4. Test with `EmailSender::testEmailConfiguration()`

### OTP Not Working
1. Check database connection
2. Verify `otp_codes` table exists
3. Check for expired OTPs
4. Ensure proper session management

### Rate Limiting Issues
1. Check system time synchronization
2. Verify database timestamps
3. Clear expired OTPs manually if needed

### Customization

To modify database connection settings, edit the following files:
- `/includes/db_connect.php`
- `/database/init_db.php`

Default settings:
- Host: `localhost`
- Username: `root`
- Password: `` (empty)
- Database: `san_rafael_cave_db`

## Technologies Used

- PHP 7.4+ with PDO
- MySQL
- Bootstrap 5
- JavaScript
- HTML5/CSS3
- Email verification system

## License

This project is for educational purposes.