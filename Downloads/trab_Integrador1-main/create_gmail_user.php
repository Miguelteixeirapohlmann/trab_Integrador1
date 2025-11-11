<?php
/**
 * Script para criar usuário com Gmail e senha 123456
 */

require_once 'config/database.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    $usuario = [
        'first_name' => 'Usuario',
        'last_name' => 'Gmail',
        'email' => 'usuario@gmail.com',
        'password' => password_hash('123456', PASSWORD_DEFAULT),
        'user_type' => 'user',
        'phone' => '51999999999',
        'cpf' => '123.456.789-00'
    ];

    // Verifica se já existe
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$usuario['email']]);
    
    if (!$stmt->fetch()) {
        // Cria usuário
        $stmt = $pdo->prepare("
            INSERT INTO users (first_name, last_name, email, password, user_type, status, email_verified, phone, cpf)
            VALUES (?, ?, ?, ?, ?, 'active', 1, ?, ?)
        ");
        
        $stmt->execute([
            $usuario['first_name'],
            $usuario['last_name'],
            $usuario['email'],
            $usuario['password'],
            $usuario['user_type'],
            $usuario['phone'],
            $usuario['cpf']
        ]);
        
        echo "✅ Usuário Gmail criado com sucesso!<br>";
        echo "<strong>Email:</strong> {$usuario['email']}<br>";
        echo "<strong>Senha:</strong> 123456<br>";
        echo "<strong>Nome:</strong> {$usuario['first_name']} {$usuario['last_name']}<br>";
        echo "<strong>Tipo:</strong> Cliente (user)<br>";
    } else {
        echo "ℹ️ Usuário com este email já existe.<br>";
        echo "<strong>Email:</strong> {$usuario['email']}<br>";
        echo "<strong>Senha:</strong> 123456<br>";
    }

} catch (Exception $e) {
    echo "❌ Erro ao criar usuário: " . $e->getMessage();
}
?>