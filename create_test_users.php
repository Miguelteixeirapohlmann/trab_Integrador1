<?php
/**
 * Script para criar um usuário corretor de teste
 */

require_once __DIR__ . '/includes/init.php';

try {
    // Verificar se já existe um usuário corretor de teste
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = 'corretor@test.com'");
    $stmt->execute();
    
    if ($stmt->fetch()) {
        echo "✓ Usuário corretor de teste já existe (corretor@test.com / 123456)\n";
    } else {
        // Criar usuário corretor
        $password_hash = password_hash('123456', PASSWORD_DEFAULT);
        
        $stmt = $pdo->prepare("
            INSERT INTO users (first_name, last_name, email, password, user_type, status, email_verified, phone, cpf)
            VALUES (?, ?, ?, ?, 'broker', 'active', 1, ?, ?)
        ");
        
        $stmt->execute([
            'João',
            'Corretor',
            'corretor@test.com',
            $password_hash,
            '51999998888',
            '111.111.111-11'
        ]);
        
        $user_id = $pdo->lastInsertId();
        
        // Criar dados do corretor
        $stmt = $pdo->prepare("
            INSERT INTO brokers (user_id, license_number, company, specialties, years_experience, rating)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $user_id,
            'CRECI-TEST-001',
            'Imobiliária Teste',
            '["Residencial", "Comercial"]',
            5,
            4.8
        ]);
        
        echo "✓ Usuário corretor criado: corretor@test.com / 123456\n";
    }
    
    // Verificar se já existe um usuário cliente de teste
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = 'cliente@test.com'");
    $stmt->execute();
    
    if ($stmt->fetch()) {
        echo "✓ Usuário cliente de teste já existe (cliente@test.com / 123456)\n";
    } else {
        // Criar usuário cliente
        $password_hash = password_hash('123456', PASSWORD_DEFAULT);
        
        $stmt = $pdo->prepare("
            INSERT INTO users (first_name, last_name, email, password, user_type, status, email_verified, phone, cpf, address)
            VALUES (?, ?, ?, ?, 'user', 'active', 1, ?, ?, ?)
        ");
        
        $stmt->execute([
            'Maria',
            'Cliente',
            'cliente@test.com',
            $password_hash,
            '51999997777',
            '222.222.222-22',
            'Rua Teste, 123 - Centro - Porto Alegre/RS'
        ]);
        
        echo "✓ Usuário cliente criado: cliente@test.com / 123456\n";
    }
    
    echo "\n✅ Usuários de teste criados com sucesso!\n";
    echo "Corretor: corretor@test.com / 123456\n";
    echo "Cliente: cliente@test.com / 123456\n";
    
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
}
?>
