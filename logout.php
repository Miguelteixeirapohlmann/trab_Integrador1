<?php
/**
 * Arquivo de logout para compatibilidade
 */

// Incluir sistema de inicialização
require_once __DIR__ . '/includes/init.php';

// Fazer logout
$result = $auth->logout();

// Redirecionar para página inicial
redirect('index.php', 'Você foi desconectado com sucesso!', 'success');
?>
