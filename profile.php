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
$pageTitle = 'Profile';

// Initialize variables
$errors = [];
$success = '';
$formData = [
    'firstname' => $user['firstname'],
    'lastname' => $user['lastname'],
    'email' => $user['email'],
    'course' => $user['course'],
    'year_level' => $user['year_level']
];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $formData = [
        'firstname' => trim($_POST['firstname'] ?? ''),
        'lastname' => trim($_POST['lastname'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'course' => trim($_POST['course'] ?? ''),
        'year_level' => trim($_POST['year_level'] ?? '')
    ];

    // Validate inputs
    if (empty($formData['firstname'])) {
        $errors['firstname'] = 'First name is required';
    }
    
    if (empty($formData['lastname'])) {
        $errors['lastname'] = 'Last name is required';
    }
    
    if (empty($formData['email'])) {
        $errors['email'] = 'Email is required';
    } elseif (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email format';
    }
    
    if (empty($formData['course'])) {
        $errors['course'] = 'Course is required';
    }
    
    if (empty($formData['year_level'])) {
        $errors['year_level'] = 'Year level is required';
    }

    // If no errors, update database
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("UPDATE users SET firstname = ?, lastname = ?, email = ?, course = ?, year_level = ? WHERE id = ?");
            $stmt->execute([
                $formData['firstname'],
                $formData['lastname'],
                $formData['email'],
                $formData['course'],
                $formData['year_level'],
                $_SESSION['user_id']
            ]);
            
            $success = 'Profile updated successfully!';
            
            // Refresh user data
            $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $user = $stmt->fetch();
            
        } catch (PDOException $e) {
            $errors['database'] = 'Error updating profile: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <?php include 'components/styles.php'; ?>
    <style>
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        .form-control:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 2px rgba(128, 0, 0, 0.1);
        }
        .error-message {
            color: var(--danger-color);
            font-size: 14px;
            margin-top: 5px;
        }
        .is-invalid {
            border-color: var(--danger-color) !important;
        }
        .edit-mode .view-mode {
            display: none;
        }
        .edit-mode .edit-mode {
            display: block;
        }
        .view-mode .edit-mode {
            display: none;
        }
        .view-mode .view-mode {
            display: block;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php include 'components/sidebar.php'; ?>
        
        <!-- Main Content -->
        <div class="main-content" id="mainContent">
            <?php include 'components/header.php'; ?>

            <!-- Content Wrapper -->
            <div class="content-wrapper">
                <div class="page-header">
                    <div class="page-title">
                        <h1>My Profile</h1>
                        <p>Manage your personal information</p>
                    </div>
                    <div class="page-actions">
                        <button id="toggleEdit" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit Profile
                        </button>
                    </div>
                </div>

                <?php if (!empty($success)): ?>
                    <div class="alert success"><?php echo htmlspecialchars($success); ?></div>
                <?php endif; ?>

                <?php if (!empty($errors['database'])): ?>
                    <div class="alert danger"><?php echo htmlspecialchars($errors['database']); ?></div>
                <?php endif; ?>

                <div class="card view-mode">
                    <div class="card-header">
                        <h3>Personal Information</h3>
                    </div>
                    <div class="user-info">
                        <p><strong>Name:</strong> <?php echo htmlspecialchars($user['firstname'] . ' ' . htmlspecialchars($user['lastname'])); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                        <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
                        <p><strong>Course:</strong> <?php echo htmlspecialchars($user['course']); ?></p>
                        <p><strong>Year Level:</strong> <?php echo htmlspecialchars($user['year_level']); ?></p>
                    </div>
                </div>

                <div class="card edit-mode">
                    <div class="card-header">
                        <h3>Edit Profile</h3>
                    </div>
                    <form method="POST" action="profile.php" class="card-content">
                        <div class="form-group">
                            <label for="firstname">First Name</label>
                            <input type="text" id="firstname" name="firstname" class="form-control <?php echo isset($errors['firstname']) ? 'is-invalid' : ''; ?>" 
                                   value="<?php echo htmlspecialchars($formData['firstname']); ?>">
                            <?php if (isset($errors['firstname'])): ?>
                                <div class="error-message"><?php echo htmlspecialchars($errors['firstname']); ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="lastname">Last Name</label>
                            <input type="text" id="lastname" name="lastname" class="form-control <?php echo isset($errors['lastname']) ? 'is-invalid' : ''; ?>" 
                                   value="<?php echo htmlspecialchars($formData['lastname']); ?>">
                            <?php if (isset($errors['lastname'])): ?>
                                <div class="error-message"><?php echo htmlspecialchars($errors['lastname']); ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" 
                                   value="<?php echo htmlspecialchars($formData['email']); ?>">
                            <?php if (isset($errors['email'])): ?>
                                <div class="error-message"><?php echo htmlspecialchars($errors['email']); ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="course">Course</label>
                            <select id="course" name="course" class="form-control <?php echo isset($errors['course']) ? 'is-invalid' : ''; ?>">
                                <option value="">Select Course</option>
                                <option value="BSIT" <?php echo $formData['course'] === 'BSIT' ? 'selected' : ''; ?>>BS Information Technology</option>
                                <option value="BSCS" <?php echo $formData['course'] === 'BSCS' ? 'selected' : ''; ?>>BS Computer Science</option>
                                <option value="BSIS" <?php echo $formData['course'] === 'BSIS' ? 'selected' : ''; ?>>BS Information Systems</option>
                                <option value="BSCE" <?php echo $formData['course'] === 'BSCE' ? 'selected' : ''; ?>>BS Computer Engineering</option>
                            </select>
                            <?php if (isset($errors['course'])): ?>
                                <div class="error-message"><?php echo htmlspecialchars($errors['course']); ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="year_level">Year Level</label>
                            <select id="year_level" name="year_level" class="form-control <?php echo isset($errors['year_level']) ? 'is-invalid' : ''; ?>">
                                <option value="">Select Year Level</option>
                                <option value="1st Year" <?php echo $formData['year_level'] === '1st Year' ? 'selected' : ''; ?>>1st Year</option>
                                <option value="2nd Year" <?php echo $formData['year_level'] === '2nd Year' ? 'selected' : ''; ?>>2nd Year</option>
                                <option value="3rd Year" <?php echo $formData['year_level'] === '3rd Year' ? 'selected' : ''; ?>>3rd Year</option>
                                <option value="4th Year" <?php echo $formData['year_level'] === '4th Year' ? 'selected' : ''; ?>>4th Year</option>
                            </select>
                            <?php if (isset($errors['year_level'])): ?>
                                <div class="error-message"><?php echo htmlspecialchars($errors['year_level']); ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group" style="margin-top: 30px;">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                            <button type="button" id="cancelEdit" class="btn btn-danger" style="margin-left: 10px;">
                                <i class="fas fa-times"></i> Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include 'components/script.php'; ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleEdit = document.getElementById('toggleEdit');
            const cancelEdit = document.getElementById('cancelEdit');
            const editModeElements = document.querySelectorAll('.edit-mode');
            const viewModeElements = document.querySelectorAll('.view-mode');
            
            // Check if we're coming back with errors to stay in edit mode
            const hasErrors = <?php echo !empty($errors) ? 'true' : 'false'; ?>;
            
            if (hasErrors) {
                document.body.classList.add('edit-mode');
                toggleEdit.textContent = 'View Profile';
            } else {
                document.body.classList.add('view-mode');
            }
            
            toggleEdit.addEventListener('click', function() {
                if (document.body.classList.contains('view-mode')) {
                    document.body.classList.remove('view-mode');
                    document.body.classList.add('edit-mode');
                    toggleEdit.innerHTML = '<i class="fas fa-eye"></i> View Profile';
                } else {
                    document.body.classList.remove('edit-mode');
                    document.body.classList.add('view-mode');
                    toggleEdit.innerHTML = '<i class="fas fa-edit"></i> Edit Profile';
                }
            });
            
            cancelEdit.addEventListener('click', function() {
                document.body.classList.remove('edit-mode');
                document.body.classList.add('view-mode');
                toggleEdit.innerHTML = '<i class="fas fa-edit"></i> Edit Profile';
                
                // Reset form to original values
                document.querySelector('form').reset();
            });
        });
    </script>
</body>
</html>