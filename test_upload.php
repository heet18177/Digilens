<?php
// Simple test script to debug file upload
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>File Upload Test</h2>";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['test_file'])) {
    echo "<h3>Upload Test Results:</h3>";
    echo "<pre>";
    print_r($_FILES['test_file']);
    echo "</pre>";
    
    $file = $_FILES['test_file'];
    
    if ($file['error'] === UPLOAD_ERR_OK) {
        echo "<p>✅ File upload successful!</p>";
        
        // Test the uploadFile function
        require_once 'helpers/functions.php';
        
        $result = uploadFile($file, 'uploads/avatars');
        if ($result) {
            echo "<p>✅ uploadFile() function works! File saved as: " . $result . "</p>";
            echo "<p>Full path: " . __DIR__ . '/public/' . $result . "</p>";
        } else {
            echo "<p>❌ uploadFile() function failed!</p>";
        }
    } else {
        echo "<p>❌ File upload failed with error: " . $file['error'] . "</p>";
    }
}
?>

<form method="POST" enctype="multipart/form-data">
    <input type="file" name="test_file" accept="image/*" required>
    <button type="submit">Test Upload</button>
</form>

