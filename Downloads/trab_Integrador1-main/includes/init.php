<?php
/**
 * Arquivo de inicialização do sistema
 */

// Iniciar sessão se necessário
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Definir timezone
date_default_timezone_set('America/Sao_Paulo');

// Definir ambiente (development ou production)
if (!defined('ENVIRONMENT')) {
    define('ENVIRONMENT', 'development'); // Altere para 'production' em produção
}

// Configurar exibição de erros
if (ENVIRONMENT === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Incluir arquivos essenciais
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/navbar.php';

// Instanciar sistema de autenticação
$auth = new Auth();

// Definir constantes da aplicação se não foram definidas
if (!defined('APP_NAME')) {
    define('APP_NAME', 'Real Estate Platform');
}

if (!defined('APP_URL')) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'];
    define('APP_URL', $protocol . $host);
}

// Função global para URLs
function url($path = '') {
    return APP_URL . '/' . ltrim($path, '/');
}

// Função global para assets
function asset($path) {
    return url($path);
}

// Função global para verificar se é AJAX
function isAjax() {
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

// Função para resposta JSON
function jsonResponse($data, $status = 200) {
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}
