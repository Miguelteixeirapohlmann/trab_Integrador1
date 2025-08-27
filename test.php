<?php
echo "PHP está funcionando!\n";
echo "Versão do PHP: " . PHP_VERSION . "\n";
echo "Data atual: " . date('Y-m-d H:i:s') . "\n";
echo "Diretório atual: " . __DIR__ . "\n";

// Testar se os includes funcionam
echo "Testando includes...\n";

// Verificar se o arquivo de configuração existe
if (file_exists(__DIR__ . '/config/database.php')) {
    echo "✓ Arquivo config/database.php encontrado\n";
} else {
    echo "✗ Arquivo config/database.php não encontrado\n";
}

// Verificar se os includes existem
$includes = ['includes/init.php', 'includes/database.php', 'includes/auth.php', 'includes/functions.php'];
foreach ($includes as $file) {
    if (file_exists(__DIR__ . '/' . $file)) {
        echo "✓ Arquivo $file encontrado\n";
    } else {
        echo "✗ Arquivo $file não encontrado\n";
    }
}

// Testar include do init
try {
    require_once __DIR__ . '/includes/init.php';
    echo "✓ Sistema inicializado com sucesso\n";
    
    // Verificar se as constantes foram definidas
    if (defined('APP_NAME')) {
        echo "✓ APP_NAME definido: " . APP_NAME . "\n";
    }
    
    if (defined('APP_URL')) {
        echo "✓ APP_URL definido: " . APP_URL . "\n";
    }
    
    // Verificar se a classe Auth existe
    if (class_exists('Auth')) {
        echo "✓ Classe Auth carregada\n";
        if (isset($auth) && $auth instanceof Auth) {
            echo "✓ Instância Auth criada\n";
        }
    }
    
} catch (Exception $e) {
    echo "✗ Erro ao inicializar sistema: " . $e->getMessage() . "\n";
    echo "Arquivo: " . $e->getFile() . "\n";
    echo "Linha: " . $e->getLine() . "\n";
}
