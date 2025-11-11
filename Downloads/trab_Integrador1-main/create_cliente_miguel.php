<?php
// Script para criar cliente Miguel
require_once 'config/database.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    $cliente = [
        'first_name' => 'Miguel',
        'last_name' => 'Teixeira',
        'email' => 'miguel@cliente.com',
        'password' => password_hash('123456', PASSWORD_DEFAULT),
        'user_type' => 'user',
    ];

    // Verifica se já existe
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$cliente['email']]);
    if (!$stmt->fetch()) {
        // Cria usuário
        $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, password, user_type, status) VALUES (?, ?, ?, ?, ?, 'active')");
        $stmt->execute([
            $cliente['first_name'],
            $cliente['last_name'],
            $cliente['email'],
            $cliente['password'],
            $cliente['user_type']
        ]);
        echo "✅ Cliente Miguel criado!<br>";
    } else {
        echo "ℹ️ Cliente Miguel já existe.<br>";
    }
    echo "<br><strong>Login: miguel@cliente.com<br>Senha: 123456</strong>";
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage();
}
?>
