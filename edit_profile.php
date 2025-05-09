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

$errors = [];
$success = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $course = trim($_POST['course']);
    $year_level = trim($_POST['year_level']);

    // Validate inputs
    if (empty($firstname)) {
        $errors['firstname'] = 'First name is required';
    }
    if (empty($lastname)) {
        $errors['lastname'] = 'Last name is required';
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Valid email is required';
    }
    if (empty($username)) {
        $errors['username'] = 'Username is required';
    }

    // Check if email already exists (excluding current user)
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
    $stmt->execute([$email, $_SESSION['user_id']]);
    if ($stmt->fetch()) {
        $errors['email'] = 'Email already in use';
    }

    // Check if username already exists (excluding current user)
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
    $stmt->execute([$username, $_SESSION['user_id']]);
    if ($stmt->fetch()) {
        $errors['username'] = 'Username already in use';
    }

    // If no errors, update profile
    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE users SET firstname = ?, lastname = ?, email = ?, username = ?, course = ?, year_level = ? WHERE id = ?");
        if ($stmt->execute([$firstname, $lastname, $email, $username, $course, $year_level, $_SESSION['user_id']])) {
            $success = 'Profile updated successfully!';
            
            // Update session data if needed
            $_SESSION['user_firstname'] = $firstname;
            
            // Refresh user data
            $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $user = $stmt->fetch();
        } else {
            $errors['general'] = 'Failed to update profile. Please try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Add to your existing CSS */
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: var(--dark-color);
        }
        
        .form-control {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 15px;
            transition: border 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            outline: none;
        }
        
        .text-danger {
            color: var(--danger-color);
            font-size: 13px;
            margin-top: 5px;
        }
        
        .profile-picture-container {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .profile-picture {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--primary-color);
        }
        
        .change-photo-btn {
            display: inline-block;
            margin-top: 10px;
            color: var(--primary-color);
            cursor: pointer;
            font-weight: 500;
        }
        
        .change-photo-btn:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar Overlay -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Sidebar -->
        <?php include 'includes/sidebar.php'; ?>

        <!-- Main Content -->
        <div class="main-content" id="mainContent">
            <!-- Header -->
            <?php include 'includes/header.php'; ?>

            <!-- Content Wrapper -->
            <div class="content-wrapper">
                <?php if ($success): ?>
                    <div class="alert success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <?php if (isset($errors['general'])): ?>
                    <div class="alert danger"><?php echo $errors['general']; ?></div>
                <?php endif; ?>
                
                <div class="page-header">
                    <div class="page-title">
                        <h1>Edit Profile</h1>
                        <p>Update your personal information</p>
                    </div>
                    <div class="page-actions">
                        <a href="profile.php" class="btn btn-primary">
                            <i class="fas fa-user"></i> View Profile
                        </a>
                    </div>
                </div>
                
                <div class="card">
                    <div class="profile-picture-container">
                        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($user['firstname'].'+'.$user['lastname']); ?>&background=4CAF50&size=150" alt="Profile" class="profile-picture">
                        <div class="change-photo-btn">Change Photo</div>
                    </div>
                    
                    <form action="edit_profile.php" method="POST">
                        <div class="form-group">
                            <label for="firstname">First Name</label>
                            <input type="text" id="firstname" name="firstname" class="form-control" value="<?php echo htmlspecialchars($user['firstname']); ?>">
                            <?php if (isset($errors['firstname'])): ?>
                                <span class="text-danger"><?php echo $errors['firstname']; ?></span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="form-group">
                            <label for="lastname">Last Name</label>
                            <input type="text" id="lastname" name="lastname" class="form-control" value="<?php echo htmlspecialchars($user['lastname']); ?>">
                            <?php if (isset($errors['lastname'])): ?>
                                <span class="text-danger"><?php echo $errors['lastname']; ?></span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>">
                            <?php if (isset($errors['email'])): ?>
                                <span class="text-danger"><?php echo $errors['email']; ?></span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" id="username" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>">
                            <?php if (isset($errors['username'])): ?>
                                <span class="text-danger"><?php echo $errors['username']; ?></span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="form-group">
                            <label for="course">Course</label>
                            <input type="text" id="course" name="course" class="form-control" value="<?php echo htmlspecialchars($user['course']); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="year_level">Year Level</label>
                            <select id="year_level" name="year_level" class="form-control">
                                <option value="1st Year" <?php echo ($user['year_level'] == '1st Year') ? 'selected' : ''; ?>>1st Year</option>
                                <option value="2nd Year" <?php echo ($user['year_level'] == '2nd Year') ? 'selected' : ''; ?>>2nd Year</option>
                                <option value="3rd Year" <?php echo ($user['year_level'] == '3rd Year') ? 'selected' : ''; ?>>3rd Year</option>
                                <option value="4th Year" <?php echo ($user['year_level'] == '4th Year') ? 'selected' : ''; ?>>4th Year</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                            <a href="profile.php" class="btn btn-danger">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/main.js"></script>
</body>
</html>