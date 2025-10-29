<?php
require_once 'includes/student_auth.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notes - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container">
        <h2>Notes Sharing</h2>
        <div class="notes-section">
            <div class="upload-notes">
                <h3>Upload New Notes</h3>
                <form id="upload-form" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="note-title">Title:</label>
                        <input type="text" id="note-title" placeholder="Note Title" required>
                    </div>
                    <div class="form-group">
                        <label for="note-description">Description:</label>
                        <textarea id="note-description" placeholder="Brief description"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="note-file">File:</label>
                        <input type="file" id="note-file" accept=".pdf,.doc,.docx,.txt" required>
                    </div>
                    <button type="submit" class="btn">Upload</button>
                </form>
            </div>
            <div class="shared-notes">
                <h3>Shared Notes</h3>
                <div id="notes-list">
                    <!-- Notes will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    <script src="../assets/js/dashboard.js"></script>
</body>
</html>
