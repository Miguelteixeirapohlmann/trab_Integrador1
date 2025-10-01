<?php
require_once __DIR__ . '/../../includes/init.php';

// Fazer logout
$result = $auth->logout();

// Redirecionar para página inicial com mensagem
redirect('/index.php', 'Você foi desconectado com sucesso!', 'success');
?>
