<?php require_once 'includes/config.php'; ?>
<?php require_once 'includes/functions.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Register Test</h1>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="alert error"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>
        
        <form action="create_account.php" method="post">
            <div class="form-group">
                <label for="firstname">First Name</label>
                <input type="text" id="firstname" name="firstname" required>
            </div>
            
            <div class="form-group">
                <label for="lastname">Last Name</label>
                <input type="text" id="lastname" name="lastname" required>
            </div>
            
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="course">Course</label>
                <select name="course" required>
                    <option value="" disabled selected>Select your course</option>
                    <option value="BSIS">BSIS</option>
                    <option value="BSIT">BSIT</option>
                    <option value="BSCS">BSCS</option>
                    <option value="BTVTED">BTVTED</option>
                    <option value="BPA">BPA</option>
                    <option value="BSA">BSA</option>
                    <option value="BSAIS">BSAIS</option>
                    <option value="BSE">BSE</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="year_level">Year Level</label>
                <select id="year_level" name="year_level" required>
                    <option value="" disabled selected>Select year level</option>
                    <option value="1st Year">1st Year</option>
                    <option value="2nd Year">2nd Year</option>
                    <option value="3rd Year">3rd Year</option>
                    <option value="4th Year">4th Year</option>
                    <option value="5th Year">5th Year</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn">Register</button>
            </div>
            
            <div class="form-footer">
                <span>Already have an account? <a href="login.php">Login</a></span>
            </div>
        </form>
    </div>
</body>
</html>