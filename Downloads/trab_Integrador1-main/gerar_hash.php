<?php
/**
 * Script para gerar hash correto da senha 123456 e atualizar usuário
 */

// Gerar hash correto da senha 123456
$senha = '123456';
$hash_correto = password_hash($senha, PASSWORD_DEFAULT);

echo "Hash gerado para senha '123456': " . $hash_correto . "\n";
echo "Verificação: " . (password_verify('123456', $hash_correto) ? 'OK' : 'FALHOU') . "\n";
?>