<?php
require_once 'includes/student_auth.php';
$user = getCurrentUser();
$student = getStudentProfile($user['id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container">
        <h2>My Profile</h2>
        <div class="profile-section">
            <div class="profile-pic">
                <img src="<?php echo getProfilePic($user['id']); ?>" alt="Profile Picture">
                <form id="pic-upload-form" enctype="multipart/form-data">
                    <input type="file" id="profile-pic" accept="image/*">
                    <button type="submit" class="btn">Update Picture</button>
                </form>
            </div>
            <form id="profile-form">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="name">Full Name:</label>
                    <input type="text" id="name" value="<?php echo htmlspecialchars($student['name'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label for="college">College:</label>
                    <input type="text" id="college" value="<?php echo htmlspecialchars($student['college'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label for="year">Year:</label>
                    <input type="text" id="year" value="<?php echo htmlspecialchars($student['year'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label for="skills">Skills:</label>
                    <textarea id="skills"><?php echo htmlspecialchars($student['skills'] ?? ''); ?></textarea>
                </div>
                <button type="submit" class="btn">Update Profile</button>
            </form>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    <script src="../assets/js/dashboard.js"></script>
</body>
</html>
