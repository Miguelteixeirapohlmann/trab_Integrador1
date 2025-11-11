<?php
require_once __DIR__ . '/includes/database.php';

$emails = ['admin@teste.com', 'corretor@teste.com'];
foreach ($emails as $email) {
    $stmt = $pdo->prepare("SELECT id, email, password, user_type, status, email_verified FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user) {
        echo "Usuário encontrado: ".$user['email']."\n";
        echo "Tipo: ".$user['user_type']." | Status: ".$user['status']." | Verificado: ".$user['email_verified']."\n";
        echo "Senha (hash): ".$user['password']."\n\n";
    } else {
        echo "Usuário não encontrado: $email\n\n";
    }
}
