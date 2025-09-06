<?php
// Include database check first
require_once 'includes/db_check.php';

// Include authentication check
require_once 'includes/auth_check.php';

// Include database connection
require_once 'includes/db_connect.php';

// Fetch cave explorations from database
$cave_explorations = [];
try {
    $stmt = $conn->prepare("SELECT * FROM cave_explorations ORDER BY created_at DESC");
    $stmt->execute();
    $cave_explorations = $stmt->fetchAll();
} catch(PDOException $e) {
    // If table doesn't exist or is empty, use default data
    $cave_explorations = [
        [
            'id' => 1,
            'name' => 'Beginner Cave Tour',
            'price' => 89.00,
            'image' => 'assets/images/expedition-1.jpg'
        ],
        [
            'id' => 2,
            'name' => 'Underground River Adventure',
            'price' => 149.00,
            'image' => 'assets/images/expedition-2.jpg'
        ],
        [
            'id' => 3,
            'name' => 'Advanced Spelunking Expedition',
            'price' => 249.00,
            'image' => 'assets/images/expedition-3.jpg'
        ]
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cave Exploration - Discover the Underground Wonders</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Montserrat:wght@700;800;900&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">San Rafael Cave</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#expeditions">Expeditions</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#gallery">Gallery</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact</a>
                    </li>
                    <?php if (is_logged_in()): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link position-relative px-3 py-2" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle me-2"></i>
                            <span><?php echo htmlspecialchars($_SESSION['user_fullname']); ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 py-2" aria-labelledby="navbarDropdown" style="min-width: 200px;">
                            <li class="px-2 text-muted small mb-2 ps-3">ACCOUNT</li>
                            <li><a class="dropdown-item rounded py-2" href="customer/profile.php"><i class="fas fa-user me-2 text-primary"></i>Profile</a></li>
                            <li><a class="dropdown-item rounded py-2" href="customer/my_bookings.php"><i class="fas fa-history me-2 text-primary"></i>My Bookings</a></li>
                            <li><hr class="dropdown-divider mx-2"></li>
                            <li><a class="dropdown-item rounded py-2 text-danger" href="includes/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                        </ul>
                    </li>
                    <?php else: ?>
                    <li class="nav-item ms-2">
                        <a class="nav-link btn btn-outline-light btn-sm rounded-pill px-3 py-2 d-flex align-items-center" href="login.php">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            <span>Login / Sign Up</span>
                        </a>
                    </li>
                    
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Notification System -->
    <?php if (isset($_SESSION['notification'])): ?>
    <div class="notification-container">
        <div class="alert alert-<?php echo $_SESSION['notification']['type']; ?> alert-dismissible fade show notification-alert" role="alert">
            <div class="d-flex align-items-center">
                <i class="<?php echo $_SESSION['notification']['icon']; ?> me-3 fs-4"></i>
                <div class="flex-grow-1">
                    <strong><?php echo htmlspecialchars($_SESSION['notification']['message']); ?></strong>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
    <?php 
        // Clear the notification after displaying
        unset($_SESSION['notification']);
    endif; 
    ?>

    <!-- Hero Section -->
    <section id="home" class="hero-section">
        <div class="overlay"></div>
        <div class="container h-100">
            <div class="row h-100 align-items-center">
                <div class="col-md-8 mx-auto text-center">
                    <h1 class="display-1 fw-bold text-white mb-4">Explore the Depths</h1>
                    <p class="lead text-white mb-5">Discover the hidden wonders beneath the Earth's surface with our expert-guided cave exploration adventures.</p>
                    <div class="hero-buttons">
                        <a href="#expeditions" class="btn btn-primary btn-lg me-3">Our Expeditions</a>
                        <a href="#contact" class="btn btn-outline-light btn-lg">Contact Us</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="about-img-container">
                        <img src="assets/images/about-cave.jpg" alt="Cave Exploration" class="img-fluid rounded shadow">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="section-header mb-4">
                        <h2 class="section-title">About Our Cave Expeditions</h2>
                        <div class="section-divider"></div>
                    </div>
                    <p class="lead">We are passionate explorers dedicated to unveiling the mysteries hidden beneath the Earth's surface.</p>
                    <p>With over 15 years of experience in cave exploration, our team of expert guides ensures safe and unforgettable adventures for explorers of all levels. From beginner-friendly caverns to challenging spelunking expeditions, we offer a variety of experiences tailored to your comfort and skill level.</p>
                    <div class="row mt-4">
                        <div class="col-md-6 mb-3">
                            <div class="feature-item">
                                <i class="fas fa-shield-alt feature-icon"></i>
                                <h5>Safety First</h5>
                                <p>Top-rated equipment and protocols</p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="feature-item">
                                <i class="fas fa-user-tie feature-icon"></i>
                                <h5>Expert Guides</h5>
                                <p>Certified and experienced leaders</p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="feature-item">
                                <i class="fas fa-mountain feature-icon"></i>
                                <h5>Diverse Locations</h5>
                                <p>Explore caves across the country</p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="feature-item">
                                <i class="fas fa-users feature-icon"></i>
                                <h5>Small Groups</h5>
                                <p>Personalized attention guaranteed</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Expeditions Section -->
    <section id="expeditions" class="py-5 bg-light">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2 class="section-title">Our Expeditions</h2>
                <div class="section-divider mx-auto"></div>
                <p class="section-subtitle">Choose your adventure from our carefully curated expeditions</p>
            </div>
            <div class="row justify-content-center">
                <?php foreach($cave_explorations as $exploration): ?>
                <div class="col-md-4 mb-4">
                    <div class="card expedition-card h-100">
                        <img src="<?php echo htmlspecialchars($exploration['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($exploration['name']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($exploration['name']); ?></h5>
                            <div class="expedition-details mb-3">
                                <span><i class="far fa-clock"></i> 3-6 hours</span>
                                <span><i class="fas fa-signal"></i> All Levels</span>
                            </div>
                            <p class="card-text">Explore the magnificent underground world with our expert guides. Experience breathtaking formations and hidden chambers in this unforgettable cave exploration adventure.</p>
                            <div class="expedition-price mb-3">
                                <span class="price">$<?php echo number_format($exploration['price'], 0); ?></span> per tour guide 
                            </div>
                            <button type="button" class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#bookingModal" data-expedition-id="<?php echo $exploration['id']; ?>" data-expedition-name="<?php echo htmlspecialchars($exploration['name']); ?>" data-expedition-price="<?php echo $exploration['price']; ?>">Book Now</button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    <section id="gallery" class="py-5">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2 class="section-title">Exploration Gallery</h2>
                <div class="section-divider mx-auto"></div>
                <p class="section-subtitle">Glimpses of the underground wonders that await you</p>
            </div>
            <div class="row g-3 gallery-container justify-content-center">
                <div class="col-lg-3 col-md-4 col-6 gallery-item">
                    <a href="assets/images/gallery-1.jpg" class="gallery-link">
                        <img src="assets/images/gallery-1.jpg" alt="Cave Formations" class="img-fluid rounded">
                    </a>
                </div>
                <div class="col-lg-3 col-md-4 col-6 gallery-item">
                    <a href="assets/images/gallery-2.jpg" class="gallery-link">
                        <img src="assets/images/gallery-2.jpg" alt="Underground Lake" class="img-fluid rounded">
                    </a>
                </div>
                <div class="col-lg-3 col-md-4 col-6 gallery-item">
                    <a href="assets/images/gallery-3.jpg" class="gallery-link">
                        <img src="assets/images/gallery-3.jpg" alt="Crystal Formations" class="img-fluid rounded">
                    </a>
                </div>
                <div class="col-lg-3 col-md-4 col-6 gallery-item">
                    <a href="assets/images/gallery-4.jpg" class="gallery-link">
                        <img src="assets/images/gallery-4.jpg" alt="Cave Entrance" class="img-fluid rounded">
                    </a>
                </div>
                <div class="col-lg-3 col-md-4 col-6 gallery-item">
                    <a href="assets/images/gallery-5.jpg" class="gallery-link">
                        <img src="assets/images/gallery-5.jpg" alt="Spelunking Team" class="img-fluid rounded">
                    </a>
                </div>
                <div class="col-lg-3 col-md-4 col-6 gallery-item">
                    <a href="assets/images/gallery-6.jpg" class="gallery-link">
                        <img src="assets/images/gallery-6.jpg" alt="Cave Waterfall" class="img-fluid rounded">
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials py-5 bg-light">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2 class="section-title">Explorer Testimonials</h2>
                <div class="section-divider mx-auto"></div>
                <p class="section-subtitle">What our adventurers say about their experiences</p>
            </div>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="testimonial-card h-100">
                        <div class="testimonial-content">
                            <p>"The underground river expedition was absolutely breathtaking! Our guide was knowledgeable and made safety a priority while ensuring we had an unforgettable adventure."</p>
                        </div>
                        <div class="testimonial-author d-flex align-items-center">
                            <div class="testimonial-avatar">
                                <img src="assets/images/testimonial-1.jpg" alt="Sarah Johnson">
                            </div>
                            <div class="testimonial-info">
                                <h5 class="mb-0">Sarah Johnson</h5>
                                <small>Underground River Adventure</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="testimonial-card h-100">
                        <div class="testimonial-content">
                            <p>"As a first-time cave explorer, I was nervous, but the beginner tour was perfect. The formations were stunning, and our guide made everyone feel comfortable and engaged throughout."</p>
                        </div>
                        <div class="testimonial-author d-flex align-items-center">
                            <div class="testimonial-avatar">
                                <img src="assets/images/testimonial-2.jpg" alt="Michael Rodriguez">
                            </div>
                            <div class="testimonial-info">
                                <h5 class="mb-0">Michael Rodriguez</h5>
                                <small>Beginner Cave Tour</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="testimonial-card h-100">
                        <div class="testimonial-content">
                            <p>"The advanced spelunking expedition pushed my limits in the best way possible. Rappelling down into untouched chambers was an experience I'll never forget. Worth every penny!"</p>
                        </div>
                        <div class="testimonial-author d-flex align-items-center">
                            <div class="testimonial-avatar">
                                <img src="assets/images/testimonial-3.jpg" alt="Emma Chen">
                            </div>
                            <div class="testimonial-info">
                                <h5 class="mb-0">Emma Chen</h5>
                                <small>Advanced Spelunking Expedition</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-5">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2 class="section-title">Contact Us</h2>
                <div class="section-divider mx-auto"></div>
                <p class="section-subtitle">Ready to explore? Get in touch with our team</p>
            </div>
            <div class="row">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="contact-info">
                        <h3>Get In Touch</h3>
                        <p>Have questions about our expeditions or want to book a custom adventure? Reach out to our team using the contact form or through any of the methods below.</p>
                        <div class="contact-item d-flex align-items-center mb-3">
                            <i class="fas fa-map-marker-alt contact-icon"></i>
                            <div>
                                <h5 class="mb-0">Our Location</h5>
                                <p class="mb-0">123 Adventure Way, Cavetown, CT 06789</p>
                            </div>
                        </div>
                        <div class="contact-item d-flex align-items-center mb-3">
                            <i class="fas fa-phone-alt contact-icon"></i>
                            <div>
                                <h5 class="mb-0">Phone Number</h5>
                                <p class="mb-0">(555) 123-4567</p>
                            </div>
                        </div>
                        <div class="contact-item d-flex align-items-center mb-3">
                            <i class="fas fa-envelope contact-icon"></i>
                            <div>
                                <h5 class="mb-0">Email Address</h5>
                                <p class="mb-0">info@cavexplore.com</p>
                            </div>
                        </div>
                        <div class="contact-item d-flex align-items-center">
                            <i class="fas fa-clock contact-icon"></i>
                            <div>
                                <h5 class="mb-0">Office Hours</h5>
                                <p class="mb-0">Monday - Friday: 9AM - 5PM</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="contact-form-container">
                        <form id="contactForm" action="includes/contact.php" method="post">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Your Name</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="phone" name="phone">
                            </div>
                            <div class="mb-3">
                                <label for="expedition" class="form-label">Interested Expedition</label>
                                <select class="form-select" id="expedition" name="expedition">
                                    <option value="" selected>Select an expedition</option>
                                    <option value="Beginner Cave Tour">Beginner Cave Tour</option>
                                    <option value="Underground River Adventure">Underground River Adventure</option>
                                    <option value="Advanced Spelunking Expedition">Advanced Spelunking Expedition</option>
                                    <option value="Custom Adventure">Custom Adventure</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Your Message</label>
                                <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">Send Message</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h4 class="text-white">CaveXplore</h4>
                    <p class="text-white-50">Unveiling the mysteries beneath the Earth's surface since 2008. Join us for an adventure of a lifetime.</p>
                    <div class="social-links">
                        <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                    <h5 class="text-white mb-4">Quick Links</h5>
                    <ul class="footer-links">
                        <li><a href="#home">Home</a></li>
                        <li><a href="#about">About Us</a></li>
                        <li><a href="#expeditions">Expeditions</a></li>
                        <li><a href="#gallery">Gallery</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-4 mb-4 mb-md-0">
                    <h5 class="text-white mb-4">Expeditions</h5>
                    <ul class="footer-links">
                        <li><a href="#expeditions">Beginner Cave Tour</a></li>
                        <li><a href="#expeditions">Underground River Adventure</a></li>
                        <li><a href="#expeditions">Advanced Spelunking</a></li>
                        <li><a href="#contact">Custom Adventures</a></li>
                        <li><a href="#">Group Bookings</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-4">
                    <h5 class="text-white mb-4">Newsletter</h5>
                    <p class="text-white-50">Subscribe to receive updates on new expeditions and special offers.</p>
                    <form class="newsletter-form">
                        <div class="input-group mb-3">
                            <input type="email" class="form-control" placeholder="Your email" required>
                            <button class="btn btn-primary" type="submit">Subscribe</button>
                        </div>
                    </form>
                </div>
            </div>
            <hr class="footer-divider">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="text-white-50 mb-0">&copy; 2023 CaveXplore. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <ul class="footer-bottom-links">
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms of Service</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <!-- Booking Modal -->
    <div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bookingModalLabel">
                        <i class="fas fa-calendar-check me-2"></i>Book Your Cave Adventure
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="expedition-info" class="alert alert-info mb-4" style="display: none;">
                        <h6><i class="fas fa-mountain me-2"></i><span id="selected-expedition-name"></span></h6>
                        <p class="mb-0"><i class="fas fa-dollar-sign me-2"></i>$<span id="selected-expedition-price"></span> per tour guide</p>
                    </div>
                    
                    <form id="modalBookingForm" action="process_booking.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" id="modal-expedition-id" name="expedition_id">
                        
                        <!-- Tour Date -->
                        <div class="mb-3">
                            <label for="modal-tour-date" class="form-label">Preferred Tour Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="modal-tour-date" name="tour_date" required min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                            <div class="form-text">Please select a date at least 24 hours in advance</div>
                        </div>

                        <!-- Participants -->
                        <div class="mb-3">
                            <h6 class="mb-3">Participants (Maximum 3 people)</h6>
                            
                            <!-- Person 1 (Required) -->
                            <div class="mb-3">
                                <label for="modal-person1-name" class="form-label">Person 1 - Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="modal-person1-name" name="person1_name" required>
                            </div>

                            <!-- Person 2 (Optional) -->
                            <div class="mb-3">
                                <label for="modal-person2-name" class="form-label">Person 2 - Full Name (Optional)</label>
                                <input type="text" class="form-control" id="modal-person2-name" name="person2_name">
                            </div>

                            <!-- Person 3 (Optional) -->
                            <div class="mb-3">
                                <label for="modal-person3-name" class="form-label">Person 3 - Full Name (Optional)</label>
                                <input type="text" class="form-control" id="modal-person3-name" name="person3_name">
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="mb-3">
                            <h6 class="mb-3">Contact Information</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="modal-contact-email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="modal-contact-email" name="contact_email" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="modal-contact-phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" id="modal-contact-phone" name="contact_phone">
                                </div>
                            </div>
                        </div>

                        <!-- Payment Proof Upload -->
                        <div class="mb-3">
                            <label for="modal-payment-proof" class="form-label">Payment Proof <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="modal-payment-proof" name="payment_proof" accept=".jpg,.jpeg,.png,.pdf" required>
                            <div class="form-text">
                                <i class="fas fa-info-circle"></i> Upload payment screenshot or document. Accepted: JPG, PNG, PDF (Max 5MB)
                            </div>
                        </div>

                        <!-- Special Requests -->
                        <div class="mb-3">
                            <label for="modal-special-requests" class="form-label">Special Requests or Notes</label>
                            <textarea class="form-control" id="modal-special-requests" name="special_requests" rows="3" placeholder="Any dietary restrictions, accessibility needs, or special requests..."></textarea>
                        </div>

                        <!-- Total Amount Display -->
                        <div class="mb-3">
                            <div class="alert alert-light border">
                                <h6 class="mb-2"><i class="fas fa-calculator me-2"></i>Booking Summary</h6>
                                <div id="modal-booking-summary">
                                    <p class="mb-1">Tour guide fee: $300</p>
                                    <p class="mb-1">Number of participants: <span id="modal-participant-count">1</span></p>
                                    <p class="mb-1">Fee per person: $<span id="modal-price-per-person">35</span></p>
                                    <hr>
                                    <h6 class="mb-0">Total Amount: $<span id="modal-total-amount">335</span></h6>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="modalBookingForm" class="btn btn-primary">
                        <i class="fas fa-calendar-check me-2"></i>Submit Booking
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JavaScript -->
    <script src="js/main.js"></script>
    
    <!-- Booking Modal JavaScript -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const bookingModal = document.getElementById('bookingModal');
        
        bookingModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const expeditionId = button.getAttribute('data-expedition-id');
            const expeditionName = button.getAttribute('data-expedition-name');
            const expeditionPrice = button.getAttribute('data-expedition-price');
            
            // Update modal content
            document.getElementById('modal-expedition-id').value = expeditionId;
            document.getElementById('selected-expedition-name').textContent = expeditionName;
            document.getElementById('selected-expedition-price').textContent = parseFloat(expeditionPrice).toFixed(0);
            document.getElementById('expedition-info').style.display = 'block';
            
            // Update booking summary
            updateModalBookingSummary();
        });
        
        // Calculate total amount for modal
        function updateModalBookingSummary() {
            const tourGuideFee = 300; // Fixed tour guide fee per transaction
            const feePerPerson = 35; // Fee per person
            
            // Count participants
            let participantCount = 0;
            if (document.getElementById('modal-person1-name').value.trim()) participantCount++;
            if (document.getElementById('modal-person2-name').value.trim()) participantCount++;
            if (document.getElementById('modal-person3-name').value.trim()) participantCount++;
            
            if (participantCount === 0) participantCount = 1; // At least 1 participant
            
            const totalAmount = tourGuideFee + (feePerPerson * participantCount);
            
            document.getElementById('modal-participant-count').textContent = participantCount;
            document.getElementById('modal-price-per-person').textContent = feePerPerson.toFixed(0);
            document.getElementById('modal-total-amount').textContent = totalAmount.toFixed(0);
        }
        
        // Add event listeners for modal form
        document.getElementById('modal-person1-name').addEventListener('input', updateModalBookingSummary);
        document.getElementById('modal-person2-name').addEventListener('input', updateModalBookingSummary);
        document.getElementById('modal-person3-name').addEventListener('input', updateModalBookingSummary);
        
        // Reset modal when closed
        bookingModal.addEventListener('hidden.bs.modal', function() {
            document.getElementById('modalBookingForm').reset();
            document.getElementById('expedition-info').style.display = 'none';
        });
        
        // Handle form submission
        document.getElementById('modalBookingForm').addEventListener('submit', function(e) {
            console.log('Form submission triggered');
            
            // Basic validation check
            const expeditionId = document.getElementById('modal-expedition-id').value;
            const tourDate = document.getElementById('modal-tour-date').value;
            const person1Name = document.getElementById('modal-person1-name').value;
            const paymentProof = document.getElementById('modal-payment-proof').files[0];
            
            if (!expeditionId || !tourDate || !person1Name || !paymentProof) {
                alert('Please fill in all required fields including payment proof upload.');
                e.preventDefault();
                return false;
            }
            
            console.log('Form validation passed, submitting...');
            // Let the form submit normally to process_booking.php
        });
    });
    </script>
    
    <!-- Notification Auto-Hide Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const notifications = document.querySelectorAll('.notification-alert');
            
            notifications.forEach(function(notification) {
                // Auto-hide after 5 seconds
                setTimeout(function() {
                    if (notification && notification.parentNode) {
                        // Add fade-out animation
                        notification.style.transition = 'all 0.5s ease-out';
                        notification.style.opacity = '0';
                        notification.style.transform = 'translate(-50%, -20px)';
                        
                        // Remove from DOM after animation
                        setTimeout(function() {
                            if (notification.parentNode) {
                                notification.parentNode.remove();
                            }
                        }, 500);
                    }
                }, 5000);
                
                // Add hover effect to pause auto-hide
                let autoHideTimeout;
                
                notification.addEventListener('mouseenter', function() {
                    clearTimeout(autoHideTimeout);
                });
                
                notification.addEventListener('mouseleave', function() {
                    autoHideTimeout = setTimeout(function() {
                        if (notification && notification.parentNode) {
                            notification.style.transition = 'all 0.5s ease-out';
                            notification.style.opacity = '0';
                            notification.style.transform = 'translate(-50%, -20px)';
                            
                            setTimeout(function() {
                                if (notification.parentNode) {
                                    notification.parentNode.remove();
                                }
                            }, 500);
                        }
                    }, 2000);
                });
            });
        });
    </script>
</body>
</html>