<?php
// Script para criar admin
require_once 'config/database.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    $admin = [
        'first_name' => 'Admin',
        'last_name' => 'Master',
        'email' => 'admin@admin.com',
        'password' => password_hash('123456', PASSWORD_DEFAULT),
        'user_type' => 'admin',
    ];

    // Verifica se já existe
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$admin['email']]);
    if (!$stmt->fetch()) {
        // Cria usuário
        $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, password, user_type, status) VALUES (?, ?, ?, ?, ?, 'active')");
        $stmt->execute([
            $admin['first_name'],
            $admin['last_name'],
            $admin['email'],
            $admin['password'],
            $admin['user_type']
        ]);
        echo "✅ Admin criado!<br>";
    } else {
        echo "ℹ️ Admin já existe.<br>";
    }
    echo "<br><strong>Login: admin@admin.com<br>Senha: 123456</strong>";
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage();
}
?>
