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

// Set page title
$pageTitle = 'Dashboard';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <?php include 'components/styles.php'; ?>
</head>
<body>
    <div class="container">
        <?php include 'components/sidebar.php'; ?>
        
        <!-- Main Content -->
        <div class="main-content" id="mainContent">
            <?php include 'components/header.php'; ?>

            <!-- Content Wrapper -->
            <div class="content-wrapper">
                <?php if (isset($_GET['success'])): ?>
                    <div class="alert success"><?php echo htmlspecialchars($_GET['success']); ?></div>
                <?php endif; ?>
                
                <div class="page-header">
                    <div class="page-title">
                        <h1>Dashboard Overview</h1>
                        <p>Welcome back, <?php echo htmlspecialchars($user['firstname']); ?>! Here's what's happening today.</p>
                    </div>
                    <div class="page-actions">
                        <a href="#" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Quick Action
                        </a>
                    </div>
                </div>
                
                <!-- Dashboard Stats Cards -->
                <div class="card-row" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
                    <div class="card">
                        <div class="card-header">
                            <h3><i class="fas fa-user-graduate" style="margin-right: 10px;"></i> Current Enrollment</h3>
                        </div>
                        <div class="card-content" style="font-size: 24px; font-weight: bold; color: var(--primary-color);">
                            3 Courses
                        </div>
                        <div class="card-footer" style="margin-top: 10px; font-size: 14px; color: #6c757d;">
                            <i class="fas fa-info-circle"></i> 2 ongoing, 1 completed
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h3><i class="fas fa-calendar-check" style="margin-right: 10px;"></i> Upcoming Deadlines</h3>
                        </div>
                        <div class="card-content" style="font-size: 24px; font-weight: bold; color: var(--primary-color);">
                            5 Tasks
                        </div>
                        <div class="card-footer" style="margin-top: 10px; font-size: 14px; color: #6c757d;">
                            <i class="fas fa-clock"></i> 2 due this week
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h3><i class="fas fa-chart-line" style="margin-right: 10px;"></i> Current GPA</h3>
                        </div>
                        <div class="card-content" style="font-size: 24px; font-weight: bold; color: var(--primary-color);">
                            3.75
                        </div>
                        <div class="card-footer" style="margin-top: 10px; font-size: 14px; color: #6c757d;">
                            <i class="fas fa-trend-up"></i> +0.15 from last term
                        </div>
                    </div>
                </div>
                
                <!-- Recent Activities Section -->
                <div class="card">
                    <div class="card-header">
                        <h3><i class="fas fa-bell" style="margin-right: 10px;"></i> Recent Activities</h3>
                    </div>
                    <div class="activities-list">
                        <div class="activity-item" style="display: flex; align-items: center; padding: 15px 0; border-bottom: 1px solid #eee;">
                            <div class="activity-icon" style="background-color: #e3f2fd; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                                <i class="fas fa-book" style="color: var(--primary-color);"></i>
                            </div>
                            <div class="activity-content">
                                <p style="margin-bottom: 5px; font-weight: 500;">New assignment posted in CS101</p>
                                <p style="font-size: 13px; color: #6c757d;">Due: May 15, 2023 | 11:59 PM</p>
                            </div>
                        </div>
                        
                        <div class="activity-item" style="display: flex; align-items: center; padding: 15px 0; border-bottom: 1px solid #eee;">
                            <div class="activity-icon" style="background-color: #e8f5e9; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                                <i class="fas fa-check-circle" style="color: #4caf50;"></i>
                            </div>
                            <div class="activity-content">
                                <p style="margin-bottom: 5px; font-weight: 500;">You submitted Math302 Quiz</p>
                                <p style="font-size: 13px; color: #6c757d;">Submitted: May 10, 2023 | 3:45 PM</p>
                            </div>
                        </div>
                        
                        <div class="activity-item" style="display: flex; align-items: center; padding: 15px 0;">
                            <div class="activity-icon" style="background-color: #fff3e0; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                                <i class="fas fa-exclamation-triangle" style="color: #ff9800;"></i>
                            </div>
                            <div class="activity-content">
                                <p style="margin-bottom: 5px; font-weight: 500;">Pre-registration opens soon</p>
                                <p style="font-size: 13px; color: #6c757d;">Starts: May 20, 2023 | 8:00 AM</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Links Section -->
                <div class="card">
                    <div class="card-header">
                        <h3><i class="fas fa-link" style="margin-right: 10px;"></i> Quick Links</h3>
                    </div>
                    <div class="quick-links" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 15px; padding: 15px 0;">
                        <a href="grades.php" class="quick-link" style="display: flex; align-items: center; padding: 10px 15px; background-color: #f8f9fa; border-radius: 5px; text-decoration: none; color: #333; transition: all 0.3s;">
                            <i class="fas fa-chart-line" style="margin-right: 10px; color: var(--primary-color);"></i>
                            <span>My Grades</span>
                        </a>
                        <a href="pre-registration.php" class="quick-link" style="display: flex; align-items: center; padding: 10px 15px; background-color: #f8f9fa; border-radius: 5px; text-decoration: none; color: #333; transition: all 0.3s;">
                            <i class="fas fa-clipboard-list" style="margin-right: 10px; color: var(--primary-color);"></i>
                            <span>Pre-Registration</span>
                        </a>
                        <a href="permanent-record.php" class="quick-link" style="display: flex; align-items: center; padding: 10px 15px; background-color: #f8f9fa; border-radius: 5px; text-decoration: none; color: #333; transition: all 0.3s;">
                            <i class="fas fa-scroll" style="margin-right: 10px; color: var(--primary-color);"></i>
                            <span>Permanent Record</span>
                        </a>
                        <a href="individual-form.php" class="quick-link" style="display: flex; align-items: center; padding: 10px 15px; background-color: #f8f9fa; border-radius: 5px; text-decoration: none; color: #333; transition: all 0.3s;">
                            <i class="fas fa-file-signature" style="margin-right: 10px; color: var(--primary-color);"></i>
                            <span>Individual Form</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'components/script.php'; ?>
</body>
</html>