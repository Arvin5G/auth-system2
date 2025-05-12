<?php
// components/sidebar.php
?>
<!-- Sidebar Overlay (for mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h2><?php echo SITE_NAME; ?></h2>
    </div>
    <div class="sidebar-menu">
        <ul>
            <li class="<?php echo basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : ''; ?>">
                <a href="dashboard.php">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </li>
            <li class="<?php echo basename($_SERVER['PHP_SELF']) === 'profile.php' ? 'active' : ''; ?>">
                <a href="profile.php">
                    <i class="fas fa-user"></i> Profile
                </a>
            </li>
            <li class="<?php echo basename($_SERVER['PHP_SELF']) === 'chat.php' ? 'active' : ''; ?>">
                <a href="chat.php">
                    <i class="fas fa-message"></i> Chat
                </a>
            </li>
            <li class="<?php echo basename($_SERVER['PHP_SELF']) === 'pre-registration.php' ? 'active' : ''; ?>">
                <a href="pre-registration.php">
                    <i class="fas fa-clipboard-list"></i> Pre-Registration
                </a>
            </li>
            <li class="<?php echo basename($_SERVER['PHP_SELF']) === 'assessment.php' ? 'active' : ''; ?>">
                <a href="assessment.php">
                    <i class="fas fa-user-graduate"></i> Student Assessment
                </a>
            </li>
            <li class="<?php echo basename($_SERVER['PHP_SELF']) === 'individual-form.php' ? 'active' : ''; ?>">
                <a href="individual-form.php">
                    <i class="fas fa-file-signature"></i> Individual Form
                </a>
            </li>
            <li class="<?php echo basename($_SERVER['PHP_SELF']) === 'grades.php' ? 'active' : ''; ?>">
                <a href="grades.php">
                    <i class="fas fa-chart-line"></i> My Grades
                </a>
            </li>
            <li class="<?php echo basename($_SERVER['PHP_SELF']) === 'permanent-record.php' ? 'active' : ''; ?>">
                <a href="permanent-record.php">
                    <i class="fas fa-scroll"></i> My Permanent Record
                </a>
            </li>
            <li class="<?php echo basename($_SERVER['PHP_SELF']) === 'enrollment-status.php' ? 'active' : ''; ?>">
                <a href="enrollment-status.php">
                    <i class="fas fa-clipboard-check"></i> Enrollment Status
                </a>
            </li>
            <li class="<?php echo basename($_SERVER['PHP_SELF']) === 'report-bugs.php' ? 'active' : ''; ?>">
                <a href="report-bugs.php">
                    <i class="fas fa-bug"></i> Report Bugs
                </a>
            </li>
        </ul>
    </div>
</div>