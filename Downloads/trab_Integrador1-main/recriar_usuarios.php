<?php
$pdo = new PDO('mysql:host=db;dbname=trab_integrador;charset=utf8mb4', 'user', 'password');
$hash = password_hash('123456', PASSWORD_DEFAULT);
echo 'Hash gerado: ' . $hash . PHP_EOL;

// Limpar usuários existentes
$pdo->exec('DELETE FROM users');

// Criar usuários com hash correto
$users = [
    ['Pedro', 'Costa', 'pedro@corretor.com', 'broker'],
    ['Maria', 'Santos', 'maria@corretor.com', 'broker'], 
    ['João', 'Silva', 'joao@corretor.com', 'broker'],
    ['Admin', 'Sistema', 'admin@admin.com', 'admin'],
    ['Usuario', 'Teste', 'user@test.com', 'user']
];

$stmt = $pdo->prepare('INSERT INTO users (first_name, last_name, email, password, user_type, status, email_verified) VALUES (?, ?, ?, ?, ?, "active", 1)');

foreach ($users as $user) {
    $stmt->execute([$user[0], $user[1], $user[2], $hash, $user[3]]);
    echo 'Criado: ' . $user[2] . PHP_EOL;
}

echo 'Todos os usuários criados com sucesso!' . PHP_EOL;

// Testar um login
echo PHP_EOL . 'Teste de verificação:' . PHP_EOL;
$stmt = $pdo->prepare('SELECT email, password FROM users WHERE email = ?');
$stmt->execute(['admin@admin.com']);
$user = $stmt->fetch();

if ($user) {
    echo 'Hash no banco: ' . $user['password'] . PHP_EOL;
    echo 'Password verify: ' . (password_verify('123456', $user['password']) ? 'TRUE' : 'FALSE') . PHP_EOL;
}
?>
