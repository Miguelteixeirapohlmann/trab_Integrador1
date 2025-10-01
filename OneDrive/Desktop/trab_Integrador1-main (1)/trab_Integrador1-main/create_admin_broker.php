<?php
require_once __DIR__ . '/includes/database.php';

$senha = password_hash('123456', PASSWORD_DEFAULT);

// Criar admin
$stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, password, user_type, status, email_verified) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->execute(['Admin', 'Teste', 'admin@teste.com', $senha, 'admin', 'active', 1]);

// Criar corretor
$stmt->execute(['Corretor', 'Teste', 'corretor@teste.com', $senha, 'broker', 'active', 1]);
$corretor_id = $pdo->lastInsertId();
$pdo->prepare("INSERT INTO brokers (user_id, license_number, company) VALUES (?, ?, ?)")->execute([$corretor_id, 'CRECI-TESTE', 'Imobiliária Teste']);

echo "Usuários admin e corretor criados com sucesso!\n";
