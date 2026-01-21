<?php
// API Backend for the forum
header('Content-Type: application/json');

// Configuration
define('UPLOAD_DIR', '/var/www/html/uploads/');
define('MAX_FILE_SIZE', 10 * 1024 * 1024); // 10MB
define('DATA_FILE', '/var/www/data/posts.json');

// Create necessary directories
if (!file_exists(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0755, true);
}
if (!file_exists(dirname(DATA_FILE))) {
    mkdir(dirname(DATA_FILE), 0755, true);
}
if (!file_exists(DATA_FILE)) {
    file_put_contents(DATA_FILE, json_encode([]));
}

// Helper functions
function sendResponse($success, $message = '', $data = null) {
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}

function formatFileSize($bytes) {
    if ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        return number_format($bytes / 1024, 2) . ' KB';
    }
    return $bytes . ' B';
}

function timeAgo($timestamp) {
    $diff = time() - $timestamp;
    if ($diff < 60) return $diff . 's ago';
    if ($diff < 3600) return floor($diff / 60) . 'm ago';
    if ($diff < 86400) return floor($diff / 3600) . 'h ago';
    return floor($diff / 86400) . 'd ago';
}

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Handle GET requests - Fetch posts
if ($method === 'GET') {
    $posts = json_decode(file_get_contents(DATA_FILE), true);
    
    // Add formatted timestamps
    foreach ($posts as &$post) {
        $post['timeago'] = timeAgo($post['timestamp']);
        if (!empty($post['file'])) {
            $post['file']['sizeFormatted'] = formatFileSize($post['file']['size']);
        }
        foreach ($post['replies'] as &$reply) {
            $reply['timeago'] = timeAgo($reply['timestamp']);
        }
    }
    
    sendResponse(true, 'Posts loaded', $posts);
}

// Handle POST requests - Create post or reply
if ($method === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'post') {
        $content = trim($_POST['content'] ?? '');
        $link = trim($_POST['link'] ?? '');
        
        if (empty($content) && empty($link) && empty($_FILES['file']['name'])) {
            sendResponse(false, 'Please provide content, a link, or upload a file.');
        }
        
        $post = [
            'id' => uniqid(),
            'content' => htmlspecialchars($content),
            'link' => htmlspecialchars($link),
            'file' => null,
            'timestamp' => time(),
            'replies' => []
        ];
        
        // Handle file upload
        if (!empty($_FILES['file']['name'])) {
            if ($_FILES['file']['error'] === UPLOAD_ERR_OK) {
                if ($_FILES['file']['size'] <= MAX_FILE_SIZE) {
                    $fileExt = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
                    $fileName = uniqid() . '.' . $fileExt;
                    $filePath = UPLOAD_DIR . $fileName;
                    
                    if (move_uploaded_file($_FILES['file']['tmp_name'], $filePath)) {
                        $post['file'] = [
                            'name' => htmlspecialchars($_FILES['file']['name']),
                            'path' => $fileName,
                            'size' => $_FILES['file']['size']
                        ];
                    } else {
                        sendResponse(false, 'Failed to upload file.');
                    }
                } else {
                    sendResponse(false, 'File is too large. Maximum size is 10MB.');
                }
            }
        }
        
        $posts = json_decode(file_get_contents(DATA_FILE), true);
        array_unshift($posts, $post);
        file_put_contents(DATA_FILE, json_encode($posts));
        
        sendResponse(true, 'Post created successfully!', $post);
        
    } elseif ($action === 'reply') {
        $postId = $_POST['post_id'] ?? '';
        $replyContent = trim($_POST['reply_content'] ?? '');
        
        if (empty($replyContent) || empty($postId)) {
            sendResponse(false, 'Reply content and post ID required.');
        }
        
        $posts = json_decode(file_get_contents(DATA_FILE), true);
        $found = false;
        
        foreach ($posts as &$post) {
            if ($post['id'] === $postId) {
                $reply = [
                    'id' => uniqid(),
                    'content' => htmlspecialchars($replyContent),
                    'timestamp' => time()
                ];
                $post['replies'][] = $reply;
                $found = true;
                break;
            }
        }
        
        if (!$found) {
            sendResponse(false, 'Post not found.');
        }
        
        file_put_contents(DATA_FILE, json_encode($posts));
        sendResponse(true, 'Reply added successfully!');
    }
    
    sendResponse(false, 'Invalid action.');
}

sendResponse(false, 'Method not allowed.');
?>
