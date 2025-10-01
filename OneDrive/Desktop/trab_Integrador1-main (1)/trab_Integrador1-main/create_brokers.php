<?php
// Script para criar corretores Pedro, Maria e João
require_once 'config/database.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    $corretores = [
        [
            'first_name' => 'Pedro',
            'last_name' => 'Silva',
            'email' => 'pedro@corretor.com',
            'password' => password_hash('123456', PASSWORD_DEFAULT),
            'user_type' => 'broker',
        ],
        [
            'first_name' => 'Maria',
            'last_name' => 'Oliveira',
            'email' => 'maria@corretor.com',
            'password' => password_hash('123456', PASSWORD_DEFAULT),
            'user_type' => 'broker',
        ],
        [
            'first_name' => 'Joao',
            'last_name' => 'Souza',
            'email' => 'joao@corretor.com',
            'password' => password_hash('123456', PASSWORD_DEFAULT),
            'user_type' => 'broker',
        ],
    ];

    foreach ($corretores as $corretor) {
        // Verifica se já existe
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$corretor['email']]);
        if (!$stmt->fetch()) {
            // Cria usuário
            $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, password, user_type, status) VALUES (?, ?, ?, ?, ?, 'active')");
            $stmt->execute([
                $corretor['first_name'],
                $corretor['last_name'],
                $corretor['email'],
                $corretor['password'],
                $corretor['user_type']
            ]);
            $user_id = $pdo->lastInsertId();
            // Cria registro de corretor
            $stmt = $pdo->prepare("INSERT INTO brokers (user_id) VALUES (?)");
            $stmt->execute([$user_id]);
            echo "✅ Corretor {$corretor['first_name']} criado!<br>";
        } else {
            echo "ℹ️ Corretor {$corretor['first_name']} já existe.<br>";
        }
    }
    echo "<br><strong>Login: pedro@corretor.com, maria@corretor.com, joao@corretor.com<br>Senha: 123456</strong>";
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage();
}
?>
