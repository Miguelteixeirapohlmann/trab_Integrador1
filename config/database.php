<?php
/**
 * Configuração do Banco de Dados
 */

// Detectar se está no ambiente Docker ou local
$isDocker = isset($_ENV['APP_ENV']) && $_ENV['APP_ENV'] === 'docker' || 
            getenv('APP_ENV') === 'docker' || 
            file_exists('/.dockerenv');

if ($isDocker) {
    // Configurações para Docker
    define('DB_HOST', 'db');
    define('DB_NAME', 'real_estate');
    define('DB_USER', 'real_estate_user');
    define('DB_PASS', 'real_estate_pass_2024');
} else {
    // Configurações para ambiente local
    define('DB_HOST', 'localhost:3307');
    define('DB_NAME', 'real_estate');
    define('DB_USER', 'root');
    define('DB_PASS', '');
}

define('DB_CHARSET', 'utf8mb4');

// Configurações da aplicação
define('APP_NAME', 'Real Estate Platform');
define('APP_URL', 'http://localhost');
define('APP_VERSION', '1.0.0');

// Configurações de sessão
define('SESSION_TIMEOUT', 3600); // 1 hora em segundos

// Configurações de upload
define('UPLOAD_PATH', __DIR__ . '/../uploads/');
define('MAX_FILE_SIZE', 5242880); // 5MB
define('ALLOWED_IMAGE_TYPES', ['jpg', 'jpeg', 'png', 'webp']);
