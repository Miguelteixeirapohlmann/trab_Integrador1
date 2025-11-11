<?php
// Script para corrigir a senha do admin para 123456
require_once __DIR__ . '/includes/database.php';

$new_password = password_hash('123456', PASSWORD_DEFAULT);
$email = 'admin@teste.com';

$sql = "UPDATE users SET password = ? WHERE email = ? AND user_type = 'admin'";
$stmt = $pdo->prepare($sql);
$stmt->execute([$new_password, $email]);

echo "Senha do admin atualizada para 123456\n";
