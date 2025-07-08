<?php
require_once 'config.php';

$schema = file_get_contents(__DIR__ . '/../sql/schema.sql');

try {
    $pdo->exec($schema);
    echo "Database initialized successfully.";
} catch (PDOException $e) {
    echo "Database initialization failed: " . $e->getMessage();
}
?>
