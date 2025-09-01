<?php
// Script para atualizar a senha do usuário teste@teste.com para 123456 (bcrypt)
require_once __DIR__ . '/includes/database.php';

$new_password = password_hash('123456', PASSWORD_DEFAULT);
$email = 'teste@teste.com';

$sql = "UPDATE users SET password = ? WHERE email = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$new_password, $email]);

echo "Senha atualizada com sucesso para o usuário $email\n";
