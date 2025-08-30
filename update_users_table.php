<?php
/**
 * Script para atualizar a estrutura da tabela users
 * Adiciona campo address se não existir
 */

require_once __DIR__ . '/config/database.php';

try {
    echo "Verificando estrutura da tabela users...\n";
    
    // Verificar se o campo address existe na tabela users
    $stmt = $pdo->prepare("SHOW COLUMNS FROM users LIKE 'address'");
    $stmt->execute();
    $column_exists = $stmt->fetch();
    
    if (!$column_exists) {
        echo "Campo 'address' não existe. Adicionando...\n";
        
        // Adicionar campo address
        $pdo->exec("ALTER TABLE users ADD COLUMN address TEXT NULL AFTER phone");
        
        echo "Campo 'address' adicionado com sucesso!\n";
        
        // Migrar dados da tabela user_profiles se existir
        echo "Verificando dados na tabela user_profiles...\n";
        
        $stmt = $pdo->prepare("
            SELECT up.user_id, up.address 
            FROM user_profiles up 
            WHERE up.address IS NOT NULL AND up.address != ''
        ");
        $stmt->execute();
        $profiles = $stmt->fetchAll();
        
        if (!empty($profiles)) {
            echo "Migrando " . count($profiles) . " endereços...\n";
            
            foreach ($profiles as $profile) {
                $update_stmt = $pdo->prepare("
                    UPDATE users 
                    SET address = ? 
                    WHERE id = ? AND (address IS NULL OR address = '')
                ");
                $update_stmt->execute([$profile['address'], $profile['user_id']]);
            }
            
            echo "Endereços migrados com sucesso!\n";
        }
        
    } else {
        echo "Campo 'address' já existe na tabela users.\n";
    }
    
    echo "Atualização concluída com sucesso!\n";
    
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
}
?>
