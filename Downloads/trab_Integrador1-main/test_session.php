<?php
session_start();

// Teste 1: Verificar se a sessão funciona
if (!isset($_SESSION['test_counter'])) {
    $_SESSION['test_counter'] = 0;
}
$_SESSION['test_counter']++;

// Teste 2: Verificar dados de login
echo "<h2>Teste de Sessão</h2>";
echo "<p><strong>Session ID:</strong> " . session_id() . "</p>";
echo "<p><strong>Contador de visitas:</strong> " . $_SESSION['test_counter'] . "</p>";
echo "<hr>";

echo "<h3>Dados da Sessão de Login:</h3>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

echo "<hr>";
echo "<h3>Informações do PHP:</h3>";
echo "<p><strong>session.save_path:</strong> " . ini_get('session.save_path') . "</p>";
echo "<p><strong>session.cookie_lifetime:</strong> " . ini_get('session.cookie_lifetime') . "</p>";
echo "<p><strong>session.gc_maxlifetime:</strong> " . ini_get('session.gc_maxlifetime') . "</p>";

echo "<hr>";
echo "<p><a href='test_session.php'>Recarregar página</a></p>";
echo "<p><a href='Login.php'>Ir para Login</a></p>";
echo "<p><a href='admin.php'>Ir para Admin</a></p>";
?>
