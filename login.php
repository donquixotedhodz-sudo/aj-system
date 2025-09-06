<?php
// Include database check
require_once 'includes/db_check.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Sign Up - Cave Exploration</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Montserrat:wght@700;800;900&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
    <style>
        .auth-container {
            min-height: 100vh;
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('assets/images/about-cave.jpg');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
        }
        
        .auth-card {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            max-width: 900px;
            width: 100%;
            position: relative;
        }
        
        .auth-inner {
            display: flex;
            height: 600px;
            transition: all 0.6s ease-in-out;
        }
        
        .auth-side {
            width: 50%;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            transition: all 0.6s ease-in-out;
        }
        
        .auth-welcome {
            background: linear-gradient(135deg, #3a7bd5, #00d2ff);
            color: white;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        
        .auth-login-welcome {
            background: linear-gradient(135deg, #3a7bd5, #00d2ff);
            color: white;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        
        .auth-form-container {
            transition: all 0.6s ease-in-out;
        }
        
        .auth-title {
            font-family: 'Montserrat', sans-serif;
            margin-bottom: 1.5rem;
            font-weight: 700;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-control {
            padding: 0.75rem 1rem;
            border-radius: 8px;
            border: 1px solid #ddd;
            background-color: rgba(255, 255, 255, 0.9);
        }
        
        .btn-auth {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #3a7bd5, #00d2ff);
            border: none;
        }
        
        .btn-outline-light {
            border: 2px solid white;
            background: transparent;
        }
        
        .btn-outline-light:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .auth-links {
            margin-top: 1.5rem;
            text-align: center;
        }
        
        .auth-links a {
            color: #3a7bd5;
            text-decoration: none;
        }
        
        .auth-links a:hover {
            text-decoration: underline;
        }
        
        .flipped .auth-inner {
            transform: translateX(-50%);
        }
        
        .social-login {
            display: flex;
            justify-content: center;
            margin-top: 1.5rem;
            gap: 1rem;
        }
        
        .social-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            transition: all 0.3s;
        }
        
        .social-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .facebook {
            background-color: #3b5998;
        }
        
        .google {
            background-color: #dd4b39;
        }
        
        .twitter {
            background-color: #1da1f2;
        }
        
        /* Login/Signup toggle styles */
        .login-view .login-form {
            display: block;
        }
        
        .login-view .signup-form {
            display: none;
        }
        
        .login-view .login-welcome {
            display: none;
        }
        
        .login-view .signup-welcome {
            display: flex;
        }
        
        .signup-view .login-form {
            display: none;
        }
        
        .signup-view .signup-form {
            display: block;
        }
        
        .signup-view .login-welcome {
            display: flex;
        }
        
        .signup-view .signup-welcome {
            display: none;
        }
        
        @media (max-width: 768px) {
            .auth-inner {
                flex-direction: column;
                height: auto;
            }
            
            .auth-side {
                width: 100%;
                padding: 2rem;
            }
            
            .auth-welcome, .auth-login-welcome {
                display: none;
            }
            
            .mobile-toggle {
                display: block;
                margin-top: 1rem;
                text-align: center;
            }
            
            .desktop-toggle {
                display: none;
            }
        }
        
        @media (min-width: 769px) {
            .mobile-toggle {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="container d-flex flex-column align-items-center">
            <div class="auth-card login-view">
                <div class="auth-inner">
                    <!-- Left Side - Welcome/Signup -->
                    <div class="auth-side auth-welcome signup-welcome">
                        <div class="auth-form-container">
                            <h2 class="auth-title">Welcome Back!</h2>
                            <p class="mb-4">To keep connected with us please login with your personal information</p>
                            <button class="btn btn-outline-light btn-auth desktop-toggle" id="showSignup">Sign Up</button>
                        </div>
                    </div>
                    
                    <!-- Left Side - Signup Form -->
                    <div class="auth-side signup-form">
                        <div class="auth-form-container">
                            <h2 class="auth-title">Create Account</h2>
                            <form action="includes/signup.php" method="post">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="fullname" placeholder="Full Name" required>
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control" name="email" placeholder="Email Address" required>
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" name="password" placeholder="Password" required>
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password" required>
                                </div>
                                <button type="submit" class="btn btn-primary btn-auth w-100">Sign Up</button>
                            </form>
                            <div class="social-login">
                                <a href="#" class="social-btn facebook"><i class="fab fa-facebook-f"></i></a>
                                <a href="#" class="social-btn google"><i class="fab fa-google"></i></a>
                                <a href="#" class="social-btn twitter"><i class="fab fa-twitter"></i></a>
                            </div>
                            <div class="auth-links mobile-toggle">
                                <span>Already have an account? </span>
                                <a href="#" id="mobileBackToLogin"><strong>Login</strong></a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right Side - Login Welcome -->
                    <div class="auth-side auth-login-welcome login-welcome">
                        <div class="auth-form-container">
                            <h2 class="auth-title">New Here?</h2>
                            <p class="mb-4">Sign up and discover the amazing cave exploration adventures</p>
                            <button class="btn btn-outline-light btn-auth desktop-toggle" id="showSignupFromLogin">LOGIN</button>
                        </div>
                    </div>
                    
                    <!-- Right Side - Login Form -->
                    <div class="auth-side login-form">
                        <div class="auth-form-container">
                            <h2 class="auth-title">Login to Your Account</h2>
                            <form action="includes/login.php" method="post">
                                <div class="form-group">
                                    <input type="email" class="form-control" name="email" placeholder="Email Address" required>
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" name="password" placeholder="Password" required>
                                </div>
                                <div class="form-group form-check">
                                    <input type="checkbox" class="form-check-input" id="rememberMe" name="remember">
                                    <label class="form-check-label" for="rememberMe">Remember me</label>
                                    <a href="#" class="float-end">Forgot password?</a>
                                </div>
                                <button type="submit" class="btn btn-primary btn-auth w-100">Login</button>
                            </form>
                            <div class="social-login">
                                <a href="#" class="social-btn facebook"><i class="fab fa-facebook-f"></i></a>
                                <a href="#" class="social-btn google"><i class="fab fa-google"></i></a>
                                <a href="#" class="social-btn twitter"><i class="fab fa-twitter"></i></a>
                            </div>
                            <div class="auth-links mobile-toggle">
                                <span>Don't have an account? </span>
                                <a href="#" id="mobileShowSignup"><strong>Sign Up</strong></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center mt-4 mb-3">
                <a href="index.php" class="btn btn-outline-light px-4 py-2"><i class="fas fa-home me-2"></i>Back to Home</a>
            </div>
            
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Custom JS -->
    <script>
        $(document).ready(function() {
            // Desktop version - Show signup form
            $('#showSignup').click(function() {
                $('.auth-card').removeClass('login-view').addClass('signup-view');
                $('.auth-inner').addClass('flipped');
            });
            
            // Desktop version - Back to login
            $('#backToLogin').click(function() {
                $('.auth-card').removeClass('signup-view').addClass('login-view');
                $('.auth-inner').removeClass('flipped');
            });
            
            // Desktop version - Show login from signup welcome (right side)
            $('#showSignupFromLogin').click(function() {
                $('.auth-card').removeClass('signup-view').addClass('login-view');
                $('.auth-inner').removeClass('flipped');
            });
            
            // Mobile version - Show signup form
            $('#mobileShowSignup').click(function() {
                $('.auth-card').removeClass('login-view').addClass('signup-view');
                $('.auth-inner').addClass('flipped');
            });
            
            // Mobile version - Back to login
            $('#mobileBackToLogin').click(function() {
                $('.auth-card').removeClass('signup-view').addClass('login-view');
                $('.auth-inner').removeClass('flipped');
            });
            
            // Handle signup form submission with AJAX
            $('.signup-form form').on('submit', function(e) {
                e.preventDefault();
                
                const form = $(this);
                const submitBtn = form.find('button[type="submit"]');
                const originalText = submitBtn.text();
                
                // Clear previous error states
                form.find('.form-control').removeClass('is-invalid');
                form.find('.invalid-feedback').remove();
                
                // Show loading state
                submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
                
                // Prepare form data
                const formData = new FormData(this);
                
                $.ajax({
                    url: 'includes/signup.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function(response) {
                        console.log('Server response:', response);
                        
                        // Check if response is valid
                        if (typeof response !== 'object') {
                            try {
                                response = JSON.parse(response);
                            } catch (e) {
                                console.error('Invalid JSON response:', response);
                                alert('Server returned invalid response. Please try again.');
                                return;
                            }
                        }
                        
                        if (response.success) {
                            // Show success message with better styling
                            const successDiv = $('<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                                '<i class="fas fa-check-circle me-2"></i>' + response.message +
                                '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                                '</div>');
                            
                            // Insert success message before the form
                            form.before(successDiv);
                            
                            // Redirect after a short delay
                            setTimeout(function() {
                                if (response.redirect) {
                                    window.location.href = response.redirect;
                                } else {
                                    window.location.href = 'index.php';
                                }
                            }, 2000);
                        } else {
                            // Handle validation errors
                            if (response.errors && Object.keys(response.errors).length > 0) {
                                // Show field-specific errors
                                $.each(response.errors, function(field, message) {
                                    const input = form.find('[name="' + field + '"]');
                                    input.addClass('is-invalid');
                                    input.after('<div class="invalid-feedback">' + message + '</div>');
                                });
                            } else {
                                // Show general error message
                                const errorMsg = response.message || 'An unknown error occurred';
                                alert('Error: ' + errorMsg);
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('An error occurred. Please try again.');
                        console.error('Signup error:', error);
                    },
                    complete: function() {
                        // Reset button state
                        submitBtn.prop('disabled', false).text(originalText);
                    }
                });
            });
            
            // Real-time validation feedback
            $('.signup-form input').on('blur', function() {
                const input = $(this);
                const value = input.val().trim();
                const name = input.attr('name');
                
                // Clear previous error state
                input.removeClass('is-invalid');
                input.next('.invalid-feedback').remove();
                
                // Basic validation
                if (name === 'fullname' && value.length < 2) {
                    input.addClass('is-invalid');
                    input.after('<div class="invalid-feedback">Name must be at least 2 characters long</div>');
                } else if (name === 'email' && value && !isValidEmail(value)) {
                    input.addClass('is-invalid');
                    input.after('<div class="invalid-feedback">Please enter a valid email address</div>');
                } else if (name === 'password' && value.length > 0 && value.length < 8) {
                    input.addClass('is-invalid');
                    input.after('<div class="invalid-feedback">Password must be at least 8 characters long</div>');
                } else if (name === 'confirm_password' && value && value !== $('.signup-form input[name="password"]').val()) {
                    input.addClass('is-invalid');
                    input.after('<div class="invalid-feedback">Passwords do not match</div>');
                }
            });
            
            // Email validation helper function
            function isValidEmail(email) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return emailRegex.test(email);
            }
        });
    </script>
</body>
</html>