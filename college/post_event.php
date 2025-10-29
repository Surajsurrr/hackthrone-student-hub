<?php
require_once 'includes/college_auth.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Event - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container">
        <h2>Post New Event</h2>
        <form id="post-event-form">
            <div class="form-group">
                <label for="title">Event Title:</label>
                <input type="text" id="title" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" required></textarea>
            </div>
            <div class="form-group">
                <label for="date">Event Date & Time:</label>
                <input type="datetime-local" id="date" required>
            </div>
            <div class="form-group">
                <label for="type">Event Type:</label>
                <select id="type" required>
                    <option value="hackathon">Hackathon</option>
                    <option value="symposium">Symposium</option>
                    <option value="project-expo">Project Expo</option>
                    <option value="workshop">Workshop</option>
                </select>
            </div>
            <div class="form-group">
                <label for="location">Location:</label>
                <input type="text" id="location">
            </div>
            <div class="form-group">
                <label for="max_participants">Max Participants:</label>
                <input type="number" id="max_participants">
            </div>
            <button type="submit" class="btn">Post Event</button>
        </form>
    </div>

    <?php include 'includes/footer.php'; ?>
    <script src="../assets/js/dashboard.js"></script>
</body>
</html>
