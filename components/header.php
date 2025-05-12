<?php
// components/header.php
?>
<!-- Header -->
<header class="header">
    <div class="header-left">
        <button class="menu-toggle" id="menuToggle">
            <i class="fas fa-bars"></i>
        </button>
        <h2><?php echo isset($pageTitle) ? $pageTitle : 'Dashboard'; ?></h2>
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