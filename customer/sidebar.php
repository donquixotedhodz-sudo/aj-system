<?php
// Get current page name for active state
$current_page = basename($_SERVER['PHP_SELF']);

// Get user profile picture
require_once '../includes/db_connect.php';
$user_profile_picture = null;
if (isset($_SESSION['user_id'])) {
    try {
        $stmt = $pdo->prepare("SELECT profile_picture FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user_profile_picture = $stmt->fetchColumn();
    } catch (PDOException $e) {
        // Handle error silently, fall back to initials
    }
}
?>

<div class="sidebar bg-dark text-white" id="sidebar">
    <div class="sidebar-header p-3 text-center">
        <div class="user-logo mb-3">
            <?php if (!empty($user_profile_picture) && file_exists($user_profile_picture)): ?>
                <img src="<?php echo htmlspecialchars($user_profile_picture); ?>" alt="Profile Picture" class="circular-logo profile-image">
            <?php else: ?>
                <div class="circular-logo">
                    <?php 
                    $user_name = $_SESSION['user_fullname'] ?? 'User';
                    $initials = '';
                    $name_parts = explode(' ', trim($user_name));
                    foreach($name_parts as $part) {
                        if(!empty($part)) {
                            $initials .= strtoupper(substr($part, 0, 1));
                        }
                    }
                    echo htmlspecialchars(substr($initials, 0, 2));
                    ?>
                </div>
            <?php endif; ?>
        </div>
        <h5 class="mb-0"><?php echo htmlspecialchars($_SESSION['user_fullname'] ?? 'User'); ?></h5>
        <small class="text-white">Customer Dashboard</small>
    </div>
    
    <nav class="sidebar-nav">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'profile.php') ? 'active' : ''; ?>" href="profile.php">
                    <i class="fas fa-user me-2"></i>
                    Profile
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'my_bookings.php') ? 'active' : ''; ?>" href="my_bookings.php">
                    <i class="fas fa-calendar-alt me-2"></i>
                    My Bookings
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'settings.php') ? 'active' : ''; ?>" href="settings.php">
                    <i class="fas fa-cog me-2"></i>
                    Settings
                </a>
            </li>
            <li class="nav-item mt-auto">
                <hr class="sidebar-divider">
                <a class="nav-link text-danger" href="../includes/logout.php">
                    <i class="fas fa-sign-out-alt me-2"></i>
                    Logout
                </a>
            </li>
        </ul>
    </nav>
    

</div>

<!-- Mobile Toggle Button -->
<button class="btn btn-dark d-md-none" type="button" id="sidebarToggle" style="position: fixed; top: 85px; left: 10px; z-index: 1050;">
    <i class="fas fa-bars"></i>
</button>

<style>
.sidebar {
    position: fixed;
    top: 76px;
    left: 0;
    height: calc(100vh - 76px);
    width: 250px;
    z-index: 999;
    transition: transform 0.3s ease;
    display: flex;
    flex-direction: column;
    background-color: rgba(0, 0, 0, 0.9) !important;
}

.circular-logo {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    font-weight: bold;
    color: white;
    margin: 0 auto;
    border: 3px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.profile-image {
    width: 60px;
    height: 60px;
    object-fit: cover;
    background: none;
}

.sidebar-nav {
    flex: 1;
    padding: 0;
}

.sidebar .nav-link {
    color: #adb5bd;
    padding: 12px 20px;
    border-radius: 0;
    transition: all 0.3s ease;
}

.sidebar .nav-link:hover {
    color: #fff;
    background-color: rgba(255, 255, 255, 0.1);
}

.sidebar .nav-link.active {
    color: #fff;
    background: linear-gradient(90deg, #3498db, #6c5ce7);
}

.sidebar-divider {
    border-color: #495057;
    margin: 10px 20px;
}

.sidebar-header {
    border-bottom: 1px solid #495057;
}

/* Mobile responsiveness */
@media (max-width: 767.98px) {
    .sidebar {
        transform: translateX(-100%);
    }
    
    .sidebar.show {
        transform: translateX(0);
    }
    
    .main-content {
        margin-left: 0 !important;
    }
}

@media (min-width: 768px) {
    .main-content {
        margin-left: 250px;
        margin-top: 76px;
        transition: margin-left 0.3s ease;
        min-height: calc(100vh - 76px);
        background-color: #f8f9fa;
        padding-top: 0 !important;
    }
    
    #sidebarToggle {
        display: none !important;
    }
}
</style>

<script>
// Mobile sidebar toggle
document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('show');
        });
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            if (window.innerWidth < 768 && 
                !sidebar.contains(e.target) && 
                !sidebarToggle.contains(e.target) && 
                sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
            }
        });
    }
});
</script>