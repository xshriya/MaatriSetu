<?php
/**
 * Secure Database Connection Helper
 * Uses configuration from config.php
 */

function getDatabaseConnection() {
    // Load configuration
    $config_file = __DIR__ . '/config.php';
    if (!file_exists($config_file)) {
        throw new Exception('Configuration file not found. Please copy config.example.php to config.php and update your credentials.');
    }
    
    $config = require $config_file;
    $db_config = $config['database'];
    
    try {
        $conn = new mysqli(
            $db_config['host'],
            $db_config['username'],
            $db_config['password'],
            $db_config['database']
        );
        
        // Set charset
        $conn->set_charset($db_config['charset'] ?? 'utf8mb4');
        
        if ($conn->connect_error) {
            throw new Exception('Database connection failed: ' . $conn->connect_error);
        }
        
        return $conn;
    } catch (Exception $e) {
        error_log('Database connection error: ' . $e->getMessage());
        throw new Exception('Database connection failed. Please check your configuration.');
    }
}

function getConfig($section = null) {
    static $config = null;
    
    if ($config === null) {
        $config_file = __DIR__ . '/config.php';
        if (!file_exists($config_file)) {
            throw new Exception('Configuration file not found. Please copy config.example.php to config.php and update your credentials.');
        }
        $config = require $config_file;
    }
    
    if ($section === null) {
        return $config;
    }
    
    return $config[$section] ?? null;
}
?>
