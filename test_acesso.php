<?php
/**
 * Teste de acesso às páginas quando logado
 */

require_once __DIR__ . '/includes/init.php';

// Verificar se está logado
echo "<!DOCTYPE html>\n<html>\n<head><title>Teste de Acesso</title></head>\n<body>\n";

echo "<h2>Status da Sessão</h2>\n";
echo "<p><strong>Sessão iniciada:</strong> " . (session_status() === PHP_SESSION_ACTIVE ? 'SIM' : 'NÃO') . "</p>\n";
echo "<p><strong>Usuário logado:</strong> " . ($auth->isLoggedIn() ? 'SIM' : 'NÃO') . "</p>\n";

if ($auth->isLoggedIn()) {
    $user = $auth->getCurrentUser();
    echo "<p><strong>Nome do usuário:</strong> " . htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) . "</p>\n";
    echo "<p><strong>Email:</strong> " . htmlspecialchars($user['email']) . "</p>\n";
    echo "<p><strong>Tipo:</strong> " . htmlspecialchars($user['user_type']) . "</p>\n";
}

echo "<hr>\n";
echo "<h2>Links de Teste</h2>\n";
echo "<p><a href='alugar.php'>Ir para página de Aluguel</a></p>\n";
echo "<p><a href='Compra.php'>Ir para página de Compra</a></p>\n";
echo "<p><a href='casas_disponiveis.php'>Ir para página de Casas Disponíveis</a></p>\n";
echo "<p><a href='agendar_visita.php'>Ir para página de Agendar Visita</a></p>\n";

echo "<hr>\n";
echo "<p><a href='index.php'>Voltar para a página inicial</a></p>\n";
echo "<p><a href='logout.php'>Fazer logout</a></p>\n";

echo "</body>\n</html>";
?>
