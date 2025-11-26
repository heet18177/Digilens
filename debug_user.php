<?php
// Debug script to check user data and avatar field
require_once 'vendor/autoload.php';
require_once 'helpers/functions.php';

use App\Models\User;

echo "<h2>User Debug Information</h2>";

// Check if user is logged in
if (auth()) {
    $user = currentUser();
    echo "<h3>Current User Data:</h3>";
    echo "<pre>";
    print_r($user);
    echo "</pre>";
    
    // Check avatar field specifically
    if (isset($user['avatar'])) {
        echo "<p>Avatar field exists: " . ($user['avatar'] ? $user['avatar'] : 'NULL') . "</p>";
        
        if ($user['avatar']) {
            $avatarPath = __DIR__ . '/public/' . $user['avatar'];
            echo "<p>Avatar file path: " . $avatarPath . "</p>";
            echo "<p>Avatar file exists: " . (file_exists($avatarPath) ? 'YES' : 'NO') . "</p>";
            
            if (file_exists($avatarPath)) {
                echo "<p>Avatar file size: " . filesize($avatarPath) . " bytes</p>";
                echo "<p>Avatar file permissions: " . substr(sprintf('%o', fileperms($avatarPath)), -4) . "</p>";
            }
        }
    } else {
        echo "<p>❌ Avatar field not found in user data!</p>";
    }
    
    // Test database update
    echo "<h3>Testing Database Update:</h3>";
    $userModel = new User();
    $testData = ['bio' => 'Test bio update at ' . date('Y-m-d H:i:s')];
    $updateResult = $userModel->updateProfile($user['id'], $testData);
    echo "<p>Database update test: " . ($updateResult ? 'SUCCESS' : 'FAILED') . "</p>";
    
} else {
    echo "<p>❌ No user logged in. Please log in first.</p>";
}

// Check uploads directory
echo "<h3>Uploads Directory Check:</h3>";
$uploadsDir = __DIR__ . '/public/uploads/avatars/';
echo "<p>Uploads directory: " . $uploadsDir . "</p>";
echo "<p>Directory exists: " . (is_dir($uploadsDir) ? 'YES' : 'NO') . "</p>";
echo "<p>Directory writable: " . (is_writable($uploadsDir) ? 'YES' : 'NO') . "</p>";

if (is_dir($uploadsDir)) {
    $files = scandir($uploadsDir);
    echo "<p>Files in directory: " . implode(', ', array_filter($files, function($f) { return $f !== '.' && $f !== '..'; })) . "</p>";
}
?>

