<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

// Get user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #800000;
            --secondary-color:rgb(44, 3, 3);
            --accent-color: #4895ef;
            --dark-color: #1b263b;
            --light-color: #f8f9fa;
            --danger-color: #800000;
            --sidebar-width: 250px;
            --header-height: 70px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f7fa;
            color: #333;
            overflow-x: hidden;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles with Animation */
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(135deg, var(--primary-color));
            color: white;
            position: fixed;
            height: 100vh;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 100;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            transform: translateX(0);
        }

        .sidebar.collapsed {
            transform: translateX(calc(-1 * var(--sidebar-width)));
            box-shadow: none;
        }

        .sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .sidebar-menu ul {
            list-style: none;
        }

        .sidebar-menu li a {
            display: block;
            padding: 15px 20px;
            color: white;
            text-decoration: none;
            transition: all 0.3s;
            font-size: 15px;
        }

        .sidebar-menu li a:hover {
            background-color: rgba(255, 255, 255, 0.1);
            padding-left: 25px;
        }

        .sidebar-menu li a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .sidebar-menu li.active a {
            background-color: rgba(255, 255, 255, 0.2);
            border-left: 3px solid white;
        }

        /* Main Content Styles */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .main-content.expanded {
            margin-left: 0;
        }

        /* Header Styles */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 30px;
            height: var(--header-height);
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 90;
        }

        .header-left {
            display: flex;
            align-items: center;
        }

        .header-left h2 {
            color: var(--dark-color);
            font-size: 22px;
        }

        .header-right {
            display: flex;
            align-items: center;
        }

        .profile {
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .profile-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 10px;
            border: 2px solid var(--primary-color);
        }

        .profile-name {
            font-weight: 500;
            margin-right: 10px;
        }

        /* Content Area Styles */
        .content-wrapper {
            padding: 30px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .page-title h1 {
            color: var(--dark-color);
            font-size: 28px;
            font-weight: 600;
        }

        .page-title p {
            color: #6c757d;
            font-size: 14px;
        }

        /* Card Styles */
        .card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            padding: 25px;
            margin-bottom: 30px;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .card-header h3 {
            font-size: 18px;
            color: var(--dark-color);
        }

        .user-info p {
            margin-bottom: 15px;
            font-size: 15px;
        }

        .user-info strong {
            color: var(--dark-color);
            font-weight: 600;
            display: inline-block;
            width: 120px;
        }

        /* Button Styles */
        .btn {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
        }

        .btn-danger {
            background-color: var(--danger-color);
            color: white;
        }

        .btn-danger:hover {
            background-color: #5c0000;
            transform: translateY(-2px);
        }

        /* Alert Styles */
        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert.success {
            background-color: #d1fae5;
            color: #065f46;
            border-left: 4px solid #10b981;
        }

        /* Menu Toggle Styles */
        .menu-toggle {
            background: none;
            border: none;
            font-size: 20px;
            color: var(--dark-color);
            cursor: pointer;
            margin-right: 20px;
            transition: transform 0.3s;
        }

        .menu-toggle.rotated {
            transform: rotate(90deg);
        }

        /* Profile Dropdown */
        .profile-dropdown {
            position: relative;
        }

        .dropdown-menu {
            position: absolute;
            right: 0;
            top: 50px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            width: 200px;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s;
            z-index: 100;
        }

        .profile-dropdown:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            top: 60px;
        }

        .dropdown-menu a {
            display: block;
            padding: 10px 15px;
            color: #555;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s;
        }

        .dropdown-menu a:hover {
            background-color: #f8f9fa;
            color: var(--primary-color);
            padding-left: 20px;
        }

        .dropdown-menu a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .dropdown-divider {
            height: 1px;
            background-color: #eee;
            margin: 5px 0;
        }

        /* Overlay for mobile */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 99;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s;
        }

        .sidebar-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(calc(-1 * var(--sidebar-width)));
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar Overlay (for mobile) -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h2><?php echo SITE_NAME; ?></h2>
            </div>
            <div class="sidebar-menu">
                <ul>
                    <li>
                        <a href="dashboard.php">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                    </li>
                    <li class="active">
                        <a href="profile.php">
                            <i class="fas fa-user"></i> Profile
                        </a>
                    </li>
                    <li>
                        <a href="courses.php">
                            <i class="fas fa-message"></i> Chat
                        </a>
                    </li>
                    <li>
                        <a href="messages.php">
                            <i class="fas fa-clipboard-list"></i> Pre-Registration
                        </a>
                    </li>
                    <li>
                        <a href="settings.php">
                            <i class="fas fa-user-graduate"></i> Student Assessment
                        </a>
                    </li>
                    <li>
                        <a href="help.php">
                            <i class="fas fa-file-signature"></i> Individual Form
                        </a>
                    </li>
                    <li>
                        <a href="help.php">
                            <i class="fas fa-chart-line"></i> My Grades
                        </a>
                    </li>
                    <li>
                        <a href="help.php">
                        <i class="fas fa-scroll"></i> My Permanent Record
                        </a>
                    </li>
                    <li>
                        <a href="help.php">
                            <i class="fas fa-clipboard-check"></i> Enrollment Status
                        </a>
                    </li>
                    <li>
                        <a href="help.php">
                            <i class="fas fa-bug"></i> Report Bugs
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content" id="mainContent">
            <!-- Header -->
            <header class="header">
                <div class="header-left">
                    <button class="menu-toggle" id="menuToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h2>Test</h2>
                </div>
                <div class="header-right">
                    <div class="profile-dropdown">
                        <div class="profile">
                            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($user['firstname'].'+'.$user['lastname']); ?>&background=f5f7fa" alt="Profile" class="profile-img">
                            <span class="profile-name"><?php echo htmlspecialchars($user['firstname']); ?></span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="dropdown-menu">
                            <a href="profile.php">
                                <i class="fas fa-user"></i> My Profile
                            </a>
                            <a href="settings.php">
                                <i class="fas fa-cog"></i> Settings
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="logout.php">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content Wrapper -->
            <div class="content-wrapper">
                <?php if (isset($_GET['success'])): ?>
                    <div class="alert success"><?php echo htmlspecialchars($_GET['success']); ?></div>
                <?php endif; ?>
                
                <div class="page-header">
                    <div class="page-title">
                        <h1>Welcome back, <?php echo htmlspecialchars($user['firstname']); ?>!</h1>
                        <p>Here's what's happening with your account today.</p>
                    </div>
                    <!-- <div class="page-actions">
                        <a href="edit_profile.php" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit Profile
                        </a>
                    </div> -->
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h3>Personal Information</h3>
                    </div>
                    <div class="user-info">
                        <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                        <p><strong>Course:</strong> <?php echo htmlspecialchars($user['course']); ?></p>
                        <p><strong>Year Level:</strong> <?php echo htmlspecialchars($user['year_level']); ?></p>
                    </div>
                </div>
                
                <div class="actions">
                    <a href="logout.php" class="btn btn-danger">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('menuToggle');
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            
            // Check if there's a saved state in localStorage
            const sidebarState = localStorage.getItem('sidebarCollapsed');
            
            // Initialize sidebar state
            if (sidebarState === 'true') {
                sidebar.classList.add('collapsed');
                mainContent.classList.add('expanded');
            } else if (window.innerWidth <= 768) {
                // On mobile, collapse by default
                sidebar.classList.add('collapsed');
                mainContent.classList.add('expanded');
            }
            
            // Toggle sidebar
            menuToggle.addEventListener('click', function() {
                // For mobile view
                if (window.innerWidth <= 768) {
                    sidebar.classList.toggle('active');
                    sidebarOverlay.classList.toggle('active');
                    menuToggle.classList.toggle('rotated');
                } 
                // For desktop view
                else {
                    sidebar.classList.toggle('collapsed');
                    mainContent.classList.toggle('expanded');
                    
                    // Save state to localStorage
                    const isCollapsed = sidebar.classList.contains('collapsed');
                    localStorage.setItem('sidebarCollapsed', isCollapsed);
                }
            });
            
            // Close sidebar when clicking outside on mobile
            sidebarOverlay.addEventListener('click', function() {
                sidebar.classList.remove('active');
                sidebarOverlay.classList.remove('active');
                menuToggle.classList.remove('rotated');
            });
            
            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    // On desktop, ensure overlay is hidden
                    sidebarOverlay.classList.remove('active');
                    menuToggle.classList.remove('rotated');
                    
                    // If sidebar was active (mobile view), transition to collapsed state
                    if (sidebar.classList.contains('active')) {
                        sidebar.classList.remove('active');
                        const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
                        if (isCollapsed) {
                            sidebar.classList.add('collapsed');
                            mainContent.classList.add('expanded');
                        } else {
                            sidebar.classList.remove('collapsed');
                            mainContent.classList.remove('expanded');
                        }
                    }
                } else {
                    // On mobile, ensure sidebar is collapsed by default
                    if (!sidebar.classList.contains('active')) {
                        sidebar.classList.add('collapsed');
                        mainContent.classList.add('expanded');
                    }
                }
            });
        });
    </script>
</body>
</html>