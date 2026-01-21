<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anonymous Forum - Tor Hidden Service</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Courier New', monospace;
            background: #0a0a0a;
            color: #00ff00;
            line-height: 1.6;
            padding: 20px;
        }
        
        .container {
            max-width: 900px;
            margin: 0 auto;
        }
        
        header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 2px solid #00ff00;
            margin-bottom: 30px;
        }
        
        header h1 {
            font-size: 2em;
            text-shadow: 0 0 10px #00ff00;
            margin-bottom: 10px;
        }
        
        header p {
            color: #00aa00;
            font-size: 0.9em;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #00ff00;
            text-decoration: none;
            padding: 10px 20px;
            border: 1px solid #00ff00;
            border-radius: 5px;
        }

        .back-link:hover {
            background: #00ff00;
            color: #000;
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid;
            border-radius: 5px;
            display: none;
        }
        
        .alert-success {
            background: #002200;
            border-color: #00ff00;
            color: #00ff00;
        }
        
        .alert-error {
            background: #220000;
            border-color: #ff0000;
            color: #ff0000;
        }
        
        .post-form {
            background: #111;
            padding: 20px;
            border: 1px solid #00ff00;
            border-radius: 5px;
            margin-bottom: 30px;
        }
        
        .post-form h2 {
            margin-bottom: 15px;
            color: #00ff00;
            font-size: 1.3em;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #00aa00;
        }
        
        textarea, input[type="text"], input[type="url"], input[type="file"] {
            width: 100%;
            padding: 10px;
            background: #000;
            border: 1px solid #00ff00;
            color: #00ff00;
            font-family: 'Courier New', monospace;
            border-radius: 3px;
        }
        
        textarea {
            resize: vertical;
            min-height: 100px;
        }
        
        textarea:focus, input:focus {
            outline: none;
            box-shadow: 0 0 10px #00ff00;
        }
        
        button {
            background: #00ff00;
            color: #000;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-family: 'Courier New', monospace;
            font-weight: bold;
            font-size: 1em;
        }
        
        button:hover {
            background: #00cc00;
            box-shadow: 0 0 15px #00ff00;
        }

        button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        .posts {
            margin-top: 30px;
        }
        
        .post {
            background: #111;
            border: 1px solid #00ff00;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .post-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #003300;
        }
        
        .post-id {
            color: #00aa00;
            font-size: 0.9em;
        }
        
        .post-time {
            color: #006600;
            font-size: 0.85em;
        }
        
        .post-content {
            margin: 15px 0;
            word-wrap: break-word;
        }
        
        .post-link {
            margin: 10px 0;
            padding: 10px;
            background: #000;
            border-left: 3px solid #00ff00;
        }
        
        .post-link a {
            color: #00ccff;
            text-decoration: none;
            word-break: break-all;
        }
        
        .post-link a:hover {
            text-decoration: underline;
        }
        
        .post-file {
            margin: 15px 0;
            padding: 15px;
            background: #000;
            border: 1px solid #00ff00;
            border-radius: 3px;
        }
        
        .post-file-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .post-file-name {
            color: #ffff00;
            font-weight: bold;
        }
        
        .post-file-size {
            color: #00aa00;
            font-size: 0.9em;
        }
        
        .post-file a {
            color: #00ff00;
            text-decoration: none;
            padding: 5px 10px;
            border: 1px solid #00ff00;
            border-radius: 3px;
            display: inline-block;
            margin-top: 10px;
        }
        
        .post-file a:hover {
            background: #00ff00;
            color: #000;
        }
        
        .replies {
            margin-top: 15px;
            border-left: 2px solid #003300;
            padding-left: 15px;
        }
        
        .reply {
            background: #0a0a0a;
            padding: 10px;
            margin: 10px 0;
            border-radius: 3px;
        }
        
        .reply-header {
            color: #006600;
            font-size: 0.85em;
            margin-bottom: 5px;
        }
        
        .reply-form {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #003300;
        }
        
        .reply-form textarea {
            min-height: 60px;
            margin-bottom: 10px;
        }
        
        .reply-form button {
            padding: 8px 20px;
            font-size: 0.9em;
        }
        
        .no-posts {
            text-align: center;
            padding: 40px;
            color: #006600;
            font-style: italic;
        }
        
        .file-info {
            font-size: 0.85em;
            color: #00aa00;
            margin-top: 5px;
        }

        .loading {
            text-align: center;
            padding: 20px;
            color: #00aa00;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="/" class="back-link">← Back to Home</a>
        
        <header>
            <h1>🧅 Anonymous Forum</h1>
            <p>Share files and links anonymously on the Tor network</p>
        </header>
        
        <div id="alertSuccess" class="alert alert-success"></div>
        <div id="alertError" class="alert alert-error"></div>
        
        <div class="post-form">
            <h2>Create New Post</h2>
            <form id="postForm">
                <div class="form-group">
                    <label for="content">Message (optional)</label>
                    <textarea name="content" id="content" placeholder="Share your thoughts anonymously..."></textarea>
                </div>
                
                <div class="form-group">
                    <label for="link">Link (optional)</label>
                    <input type="url" name="link" id="link" placeholder="https://example.onion">
                </div>
                
                <div class="form-group">
                    <label for="file">Upload File (optional, max 10MB)</label>
                    <input type="file" name="file" id="file">
                    <div class="file-info">Supported: All file types</div>
                </div>
                
                <button type="submit" id="submitBtn">Post Anonymously</button>
            </form>
        </div>
        
        <div class="posts">
            <h2 style="margin-bottom: 20px; color: #00ff00;">Recent Posts</h2>
            <div id="postsContainer" class="loading">Loading posts...</div>
        </div>
    </div>

    <script>
        // Load posts on page load
        loadPosts();

        // Handle form submission
        document.getElementById('postForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Posting...';
            
            const formData = new FormData(this);
            formData.append('action', 'post');
            
            try {
                const response = await fetch('/api.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showAlert('success', result.message);
                    this.reset();
                    loadPosts();
                } else {
                    showAlert('error', result.message);
                }
            } catch (error) {
                showAlert('error', 'An error occurred. Please try again.');
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Post Anonymously';
            }
        });

        async function loadPosts() {
            try {
                const response = await fetch('/api.php');
                const result = await response.json();
                
                if (result.success) {
                    displayPosts(result.data);
                } else {
                    document.getElementById('postsContainer').innerHTML = 
                        '<div class="no-posts">Failed to load posts</div>';
                }
            } catch (error) {
                document.getElementById('postsContainer').innerHTML = 
                    '<div class="no-posts">Error loading posts</div>';
            }
        }

        function displayPosts(posts) {
            const container = document.getElementById('postsContainer');
            
            if (posts.length === 0) {
                container.innerHTML = '<div class="no-posts">No posts yet. Be the first to share something!</div>';
                return;
            }
            
            container.innerHTML = posts.map(post => `
                <div class="post">
                    <div class="post-header">
                        <span class="post-id">ID: ${post.id}</span>
                        <span class="post-time">${post.timeago}</span>
                    </div>
                    
                    ${post.content ? `<div class="post-content">${escapeHtml(post.content).replace(/\n/g, '<br>')}</div>` : ''}
                    
                    ${post.link ? `
                        <div class="post-link">
                            🔗 <a href="${escapeHtml(post.link)}" target="_blank">${escapeHtml(post.link)}</a>
                        </div>
                    ` : ''}
                    
                    ${post.file ? `
                        <div class="post-file">
                            <div class="post-file-info">
                                <span class="post-file-name">📎 ${escapeHtml(post.file.name)}</span>
                                <span class="post-file-size">${post.file.sizeFormatted}</span>
                            </div>
                            <a href="/uploads/${post.file.path}" download>Download File</a>
                        </div>
                    ` : ''}
                    
                    ${post.replies.length > 0 ? `
                        <div class="replies">
                            <strong style="color: #00aa00;">Replies (${post.replies.length}):</strong>
                            ${post.replies.map(reply => `
                                <div class="reply">
                                    <div class="reply-header">${reply.timeago}</div>
                                    <div>${escapeHtml(reply.content).replace(/\n/g, '<br>')}</div>
                                </div>
                            `).join('')}
                        </div>
                    ` : ''}
                    
                    <div class="reply-form">
                        <form onsubmit="submitReply(event, '${post.id}')">
                            <textarea name="reply_content" placeholder="Reply anonymously..." required></textarea>
                            <button type="submit">Reply</button>
                        </form>
                    </div>
                </div>
            `).join('');
        }

        async function submitReply(e, postId) {
            e.preventDefault();
            
            const form = e.target;
            const textarea = form.querySelector('textarea');
            const button = form.querySelector('button');
            const originalText = button.textContent;
            
            button.disabled = true;
            button.textContent = 'Replying...';
            
            const formData = new FormData();
            formData.append('action', 'reply');
            formData.append('post_id', postId);
            formData.append('reply_content', textarea.value);
            
            try {
                const response = await fetch('/api.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showAlert('success', result.message);
                    textarea.value = '';
                    loadPosts();
                } else {
                    showAlert('error', result.message);
                }
            } catch (error) {
                showAlert('error', 'An error occurred. Please try again.');
            } finally {
                button.disabled = false;
                button.textContent = originalText;
            }
        }

        function showAlert(type, message) {
            const alertId = type === 'success' ? 'alertSuccess' : 'alertError';
            const alertEl = document.getElementById(alertId);
            
            // Hide other alert
            const otherAlert = type === 'success' ? 'alertError' : 'alertSuccess';
            document.getElementById(otherAlert).style.display = 'none';
            
            alertEl.textContent = message;
            alertEl.style.display = 'block';
            
            setTimeout(() => {
                alertEl.style.display = 'none';
            }, 5000);
            
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Auto-refresh posts every 30 seconds
        setInterval(loadPosts, 30000);
    </script>
</body>
</html>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Courier New', monospace;
            background: #0a0a0a;
            color: #00ff00;
            line-height: 1.6;
            padding: 20px;
        }
        
        .container {
            max-width: 900px;
            margin: 0 auto;
        }
        
        header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 2px solid #00ff00;
            margin-bottom: 30px;
        }
        
        header h1 {
            font-size: 2em;
            text-shadow: 0 0 10px #00ff00;
            margin-bottom: 10px;
        }
        
        header p {
            color: #00aa00;
            font-size: 0.9em;
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid;
            border-radius: 5px;
        }
        
        .alert-success {
            background: #002200;
            border-color: #00ff00;
            color: #00ff00;
        }
        
        .alert-error {
            background: #220000;
            border-color: #ff0000;
            color: #ff0000;
        }
        
        .post-form {
            background: #111;
            padding: 20px;
            border: 1px solid #00ff00;
            border-radius: 5px;
            margin-bottom: 30px;
        }
        
        .post-form h2 {
            margin-bottom: 15px;
            color: #00ff00;
            font-size: 1.3em;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #00aa00;
        }
        
        textarea, input[type="text"], input[type="url"], input[type="file"] {
            width: 100%;
            padding: 10px;
            background: #000;
            border: 1px solid #00ff00;
            color: #00ff00;
            font-family: 'Courier New', monospace;
            border-radius: 3px;
        }
        
        textarea {
            resize: vertical;
            min-height: 100px;
        }
        
        textarea:focus, input:focus {
            outline: none;
            box-shadow: 0 0 10px #00ff00;
        }
        
        button {
            background: #00ff00;
            color: #000;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-family: 'Courier New', monospace;
            font-weight: bold;
            font-size: 1em;
        }
        
        button:hover {
            background: #00cc00;
            box-shadow: 0 0 15px #00ff00;
        }
        
        .posts {
            margin-top: 30px;
        }
        
        .post {
            background: #111;
            border: 1px solid #00ff00;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .post-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #003300;
        }
        
        .post-id {
            color: #00aa00;
            font-size: 0.9em;
        }
        
        .post-time {
            color: #006600;
            font-size: 0.85em;
        }
        
        .post-content {
            margin: 15px 0;
            word-wrap: break-word;
        }
        
        .post-link {
            margin: 10px 0;
            padding: 10px;
            background: #000;
            border-left: 3px solid #00ff00;
        }
        
        .post-link a {
            color: #00ccff;
            text-decoration: none;
            word-break: break-all;
        }
        
        .post-link a:hover {
            text-decoration: underline;
        }
        
        .post-file {
            margin: 15px 0;
            padding: 15px;
            background: #000;
            border: 1px solid #00ff00;
            border-radius: 3px;
        }
        
        .post-file-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .post-file-name {
            color: #ffff00;
            font-weight: bold;
        }
        
        .post-file-size {
            color: #00aa00;
            font-size: 0.9em;
        }
        
        .post-file a {
            color: #00ff00;
            text-decoration: none;
            padding: 5px 10px;
            border: 1px solid #00ff00;
            border-radius: 3px;
            display: inline-block;
            margin-top: 10px;
        }
        
        .post-file a:hover {
            background: #00ff00;
            color: #000;
        }
        
        .replies {
            margin-top: 15px;
            border-left: 2px solid #003300;
            padding-left: 15px;
        }
        
        .reply {
            background: #0a0a0a;
            padding: 10px;
            margin: 10px 0;
            border-radius: 3px;
        }
        
        .reply-header {
            color: #006600;
            font-size: 0.85em;
            margin-bottom: 5px;
        }
        
        .reply-form {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #003300;
        }
        
        .reply-form textarea {
            min-height: 60px;
            margin-bottom: 10px;
        }
        
        .reply-form button {
            padding: 8px 20px;
            font-size: 0.9em;
        }
        
        .no-posts {
            text-align: center;
            padding: 40px;
            color: #006600;
            font-style: italic;
        }
        
        .file-info {
            font-size: 0.85em;
            color: #00aa00;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>🧅 Anonymous Forum</h1>
            <p>Share files and links anonymously on the Tor network</p>
        </header>
        
        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="post-form">
            <h2>Create New Post</h2>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="post">
                
                <div class="form-group">
                    <label for="content">Message (optional)</label>
                    <textarea name="content" id="content" placeholder="Share your thoughts anonymously..."></textarea>
                </div>
                
                <div class="form-group">
                    <label for="link">Link (optional)</label>
                    <input type="url" name="link" id="link" placeholder="https://example.onion">
                </div>
                
                <div class="form-group">
                    <label for="file">Upload File (optional, max 10MB)</label>
                    <input type="file" name="file" id="file">
                    <div class="file-info">Supported: All file types</div>
                </div>
                
                <button type="submit">Post Anonymously</button>
            </form>
        </div>
        
        <div class="posts">
            <h2 style="margin-bottom: 20px; color: #00ff00;">Recent Posts</h2>
            
            <?php if (empty($posts)): ?>
                <div class="no-posts">
                    No posts yet. Be the first to share something!
                </div>
            <?php else: ?>
                <?php foreach ($posts as $post): ?>
                    <div class="post">
                        <div class="post-header">
                            <span class="post-id">ID: <?php echo $post['id']; ?></span>
                            <span class="post-time"><?php echo timeAgo($post['timestamp']); ?></span>
                        </div>
                        
                        <?php if (!empty($post['content'])): ?>
                            <div class="post-content">
                                <?php echo nl2br($post['content']); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($post['link'])): ?>
                            <div class="post-link">
                                🔗 <a href="<?php echo $post['link']; ?>" target="_blank"><?php echo $post['link']; ?></a>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($post['file'])): ?>
                            <div class="post-file">
                                <div class="post-file-info">
                                    <span class="post-file-name">📎 <?php echo $post['file']['name']; ?></span>
                                    <span class="post-file-size"><?php echo formatFileSize($post['file']['size']); ?></span>
                                </div>
                                <a href="/uploads/<?php echo $post['file']['path']; ?>" download>Download File</a>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($post['replies'])): ?>
                            <div class="replies">
                                <strong style="color: #00aa00;">Replies (<?php echo count($post['replies']); ?>):</strong>
                                <?php foreach ($post['replies'] as $reply): ?>
                                    <div class="reply">
                                        <div class="reply-header">
                                            <?php echo timeAgo($reply['timestamp']); ?>
                                        </div>
                                        <div><?php echo nl2br($reply['content']); ?></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="reply-form">
                            <form method="POST">
                                <input type="hidden" name="action" value="reply">
                                <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                                <textarea name="reply_content" placeholder="Reply anonymously..." required></textarea>
                                <button type="submit">Reply</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
