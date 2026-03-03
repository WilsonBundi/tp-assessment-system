<?php
require 'vendor/autoload.php';
require 'common/config/bootstrap.php';

$config = require 'common/config/main-local.php';
$db = new yii\db\Connection($config['components']['db']);
$db->open();

$sql = "INSERT INTO user (username, auth_key, password_hash, email, status, created_at, updated_at) 
        VALUES ('admin', 'ffbc756784677f229f963b248a5759d1', '\$2y\$10\$11bvig5gZQ5YqktjSAZxZuz7lV91QMdt/GRoPOcnrN4GjKSfbfDlm', 'admin@example.com', 10, UNIX_TIMESTAMP(), UNIX_TIMESTAMP())";

try {
    $db->createCommand($sql)->execute();
    echo "Admin user created successfully!\n";
    
    $user = $db->createCommand("SELECT * FROM user WHERE username = 'admin'")->queryOne();
    echo "Username: " . $user['username'] . "\n";
    echo "Email: " . $user['email'] . "\n";
    echo "Status: " . $user['status'] . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

$db->close();
