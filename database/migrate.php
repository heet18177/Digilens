<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Load environment
if (file_exists(__DIR__ . '/../.env')) {
    $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($key, $value) = explode('=', $line, 2);
        $_ENV[trim($key)] = trim($value, '"\'');
    }
}

$config = require __DIR__ . '/../config/database.php';

try {
    // Use socket for XAMPP MySQL on macOS
    $socket = '/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock';
    if (file_exists($socket)) {
        $dsn = "mysql:unix_socket={$socket};dbname=mysql;charset={$config['charset']}";
    } else {
        $dsn = "mysql:host={$config['host']};port={$config['port']};charset={$config['charset']}";
    }
    $pdo = new PDO($dsn, $config['username'], $config['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database if not exists
    echo "Creating database '{$config['dbname']}'...\n";
    $pdo->exec("CREATE DATABASE IF NOT EXISTS {$config['dbname']} CHARACTER SET {$config['charset']} COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE {$config['dbname']}");
    echo "Database created/selected successfully.\n\n";
    
    // Get all migration files
    $migrationFiles = glob(__DIR__ . '/migrations/*.sql');
    sort($migrationFiles);
    
    foreach ($migrationFiles as $file) {
        $filename = basename($file);
        echo "Running migration: {$filename}...\n";
        
        $sql = file_get_contents($file);
        $pdo->exec($sql);
        
        echo "✓ Migration completed: {$filename}\n\n";
    }
    
    echo "\n🎉 All migrations completed successfully!\n";
    
} catch (PDOException $e) {
    echo "❌ Migration failed: " . $e->getMessage() . "\n";
    exit(1);
}

