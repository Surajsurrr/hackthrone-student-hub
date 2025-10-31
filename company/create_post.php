<?php
require_once 'includes/company_auth.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <style>
        .create-post-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
        }

        .page-header {
            margin-bottom: 30px;
        }

        .page-header h1 {
            font-size: 2rem;
            color: #1e293b;
            margin-bottom: 10px;
        }

        .page-header p {
            color: #64748b;
        }

        .post-form {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #334155;
        }

        .form-group input[type="text"],
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: border-color 0.2s;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 150px;
            font-family: inherit;
        }

        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            font-size: 1rem;
            transition: all 0.2s;
        }

        .btn-primary {
            background: #667eea;
            color: white;
            flex: 1;
        }

        .btn-primary:hover {
            background: #5568d3;
        }

        .btn-secondary {
            background: #e2e8f0;
            color: #475569;
        }

        .btn-secondary:hover {
            background: #cbd5e1;
        }

        .helper-text {
            font-size: 0.85rem;
            color: #64748b;
            margin-top: 5px;
        }

        .success-message {
            background: #d1fae5;
            color: #065f46;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
        }

        .error-message {
            background: #fee2e2;
            color: #991b1b;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="create-post-container">
        <div class="page-header">
            <h1>📝 Create Company Post</h1>
            <p>Share your company's research, history, announcements, and achievements</p>
        </div>

        <div id="success-message" class="success-message"></div>
        <div id="error-message" class="error-message"></div>

        <form id="create-post-form" class="post-form">
            <div class="form-group">
                <label for="title">Post Title *</label>
                <input type="text" id="title" name="title" required placeholder="Enter an engaging title">
            </div>

            <div class="form-group">
                <label for="post-type">Post Type *</label>
                <select id="post-type" name="post_type" required>
                    <option value="">Select post type...</option>
                    <option value="research">Research & Innovation</option>
                    <option value="history">Company History & Milestones</option>
                    <option value="announcement">Announcement</option>
                    <option value="culture">Company Culture</option>
                    <option value="achievement">Achievement & Awards</option>
                    <option value="general">General Update</option>
                </select>
                <p class="helper-text">Choose the category that best describes your post</p>
            </div>

            <div class="form-group">
                <label for="content">Content *</label>
                <textarea id="content" name="content" required placeholder="Write your post content here..."></textarea>
                <p class="helper-text">Share detailed information about your topic</p>
            </div>

            <div class="form-group">
                <label for="tags">Tags</label>
                <input type="text" id="tags" name="tags" placeholder="e.g., AI, Machine Learning, Innovation">
                <p class="helper-text">Separate multiple tags with commas</p>
            </div>

            <div class="form-group">
                <label for="image-url">Image URL (optional)</label>
                <input type="text" id="image-url" name="image_url" placeholder="https://example.com/image.jpg">
                <p class="helper-text">Add an image to make your post more engaging</p>
            </div>

            <div class="form-group">
                <label for="status">Status *</label>
                <select id="status" name="status" required>
                    <option value="published">Publish Now</option>
                    <option value="draft">Save as Draft</option>
                </select>
            </div>

            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="window.location.href='manage_postings.php'">Cancel</button>
                <button type="submit" class="btn btn-primary">Create Post</button>
            </div>
        </form>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script>
        document.getElementById('create-post-form').addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = {
                title: document.getElementById('title').value,
                post_type: document.getElementById('post-type').value,
                content: document.getElementById('content').value,
                tags: document.getElementById('tags').value,
                image_url: document.getElementById('image-url').value,
                status: document.getElementById('status').value
            };

            try {
                const response = await fetch('../api/company/createPost.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();

                if (data.success) {
                    document.getElementById('success-message').textContent = 'Post created successfully!';
                    document.getElementById('success-message').style.display = 'block';
                    document.getElementById('error-message').style.display = 'none';
                    
                    setTimeout(() => {
                        window.location.href = 'manage_postings.php';
                    }, 1500);
                } else {
                    document.getElementById('error-message').textContent = data.error || 'Failed to create post';
                    document.getElementById('error-message').style.display = 'block';
                    document.getElementById('success-message').style.display = 'none';
                }
            } catch (error) {
                console.error('Error:', error);
                document.getElementById('error-message').textContent = 'An error occurred. Please try again.';
                document.getElementById('error-message').style.display = 'block';
                document.getElementById('success-message').style.display = 'none';
            }
        });
    </script>
</body>
</html>
