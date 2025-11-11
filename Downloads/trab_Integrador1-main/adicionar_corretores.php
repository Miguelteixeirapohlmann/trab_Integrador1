<?php
$pdo = new PDO('mysql:host=db;dbname=trab_integrador;charset=utf8mb4', 'user', 'password');

echo "🚀 Adicionando corretores específicos..." . PHP_EOL;

// Hash para senha 123456
$hash_123456 = password_hash('123456', PASSWORD_DEFAULT);

// Corretores específicos para adicionar
$corretores_especificos = [
    ['Pedro', 'Costa', 'pedro@corretor.com', $hash_123456],
    ['Maria', 'Santos', 'maria@corretor.com', $hash_123456], 
    ['João', 'Silva', 'joao@corretor.com', $hash_123456]
];

// Verificar se já existem e atualizar, ou criar novos
$stmt_check = $pdo->prepare('SELECT id FROM users WHERE email = ?');
$stmt_insert = $pdo->prepare('INSERT INTO users (first_name, last_name, email, password, user_type, status, email_verified) VALUES (?, ?, ?, ?, "broker", "active", 1)');
$stmt_update = $pdo->prepare('UPDATE users SET first_name = ?, last_name = ?, password = ?, user_type = "broker", status = "active", email_verified = 1 WHERE email = ?');

foreach ($corretores_especificos as $corretor) {
    $stmt_check->execute([$corretor[2]]);
    $exists = $stmt_check->fetch();
    
    if ($exists) {
        // Atualizar existente
        $stmt_update->execute([$corretor[0], $corretor[1], $corretor[3], $corretor[2]]);
        echo "✅ Atualizado: {$corretor[2]}" . PHP_EOL;
    } else {
        // Criar novo
        $stmt_insert->execute([$corretor[0], $corretor[1], $corretor[2], $corretor[3]]);
        echo "➕ Criado: {$corretor[2]}" . PHP_EOL;
    }
}

// Verificar todos os corretores
echo PHP_EOL . "📋 Todos os corretores no sistema:" . PHP_EOL;
$stmt = $pdo->query("SELECT id, first_name, last_name, email, user_type FROM users WHERE user_type = 'broker' ORDER BY first_name");
$corretores = $stmt->fetchAll();

foreach ($corretores as $corretor) {
    echo "🏢 ID: {$corretor['id']} - {$corretor['first_name']} {$corretor['last_name']} - {$corretor['email']}" . PHP_EOL;
}

// Testar login de cada corretor
echo PHP_EOL . "🧪 Testando login dos corretores:" . PHP_EOL;
foreach ($corretores as $corretor) {
    $stmt = $pdo->prepare('SELECT password FROM users WHERE email = ?');
    $stmt->execute([$corretor['email']]);
    $user = $stmt->fetch();
    
    $senha_ok = password_verify('123456', $user['password']);
    echo ($senha_ok ? '✅' : '❌') . " {$corretor['email']} - Senha: 123456" . PHP_EOL;
}

echo PHP_EOL . "🎉 Processo concluído!" . PHP_EOL;
?>
