<?php
$pdo = new PDO('mysql:host=db;dbname=trab_integrador;charset=utf8mb4', 'user', 'password');

echo "🔍 Investigando problemas de login..." . PHP_EOL;

// 1. Verificar João e Maria
echo PHP_EOL . "1️⃣ Verificando João e Maria:" . PHP_EOL;
$usuarios_problema = ['joao@corretor.com', 'maria@corretor.com'];

foreach ($usuarios_problema as $email) {
    $stmt = $pdo->prepare('SELECT id, first_name, last_name, email, password, user_type, status FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user) {
        echo "✅ Usuário encontrado: {$user['first_name']} {$user['last_name']} - {$email}" . PHP_EOL;
        echo "   Status: {$user['status']}" . PHP_EOL;
        echo "   Tipo: {$user['user_type']}" . PHP_EOL;
        
        // Testar senha
        $senha_ok = password_verify('123456', $user['password']);
        echo "   Senha 123456: " . ($senha_ok ? 'OK ✅' : 'ERRO ❌') . PHP_EOL;
        
        if (!$senha_ok) {
            // Gerar novo hash e atualizar
            $novo_hash = password_hash('123456', PASSWORD_DEFAULT);
            $stmt_update = $pdo->prepare('UPDATE users SET password = ? WHERE email = ?');
            $stmt_update->execute([$novo_hash, $email]);
            echo "   🔧 Senha corrigida!" . PHP_EOL;
        }
        
    } else {
        echo "❌ Usuário não encontrado: $email" . PHP_EOL;
    }
    echo PHP_EOL;
}

// 2. Criar usuário Miguel
echo "2️⃣ Criando usuário Miguel:" . PHP_EOL;
$hash_miguel = password_hash('123456', PASSWORD_DEFAULT);

// Verificar se Miguel já existe
$stmt = $pdo->prepare('SELECT id FROM users WHERE first_name = ? OR email LIKE ?');
$stmt->execute(['Miguel', '%miguel%']);
$miguel_exists = $stmt->fetch();

if (!$miguel_exists) {
    $stmt_insert = $pdo->prepare('INSERT INTO users (first_name, last_name, email, password, user_type, status, email_verified) VALUES (?, ?, ?, ?, ?, ?, ?)');
    $stmt_insert->execute(['Miguel', 'Silva', 'miguel@test.com', $hash_miguel, 'user', 'active', 1]);
    echo "✅ Usuário Miguel criado: miguel@test.com / 123456" . PHP_EOL;
} else {
    echo "ℹ️ Usuário Miguel já existe" . PHP_EOL;
}

// 3. Teste final de todos
echo PHP_EOL . "3️⃣ Teste final de login:" . PHP_EOL;
$usuarios_teste = [
    ['email' => 'joao@corretor.com', 'nome' => 'João (Corretor)'],
    ['email' => 'maria@corretor.com', 'nome' => 'Maria (Corretor)'],
    ['email' => 'miguel@test.com', 'nome' => 'Miguel (Usuário)']
];

foreach ($usuarios_teste as $teste) {
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$teste['email']]);
    $user = $stmt->fetch();
    
    if ($user && password_verify('123456', $user['password'])) {
        echo "✅ {$teste['nome']} - Login funcionando" . PHP_EOL;
    } else {
        echo "❌ {$teste['nome']} - Login com problema" . PHP_EOL;
    }
}

echo PHP_EOL . "🎯 Processo concluído!" . PHP_EOL;
?>