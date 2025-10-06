<?php
/**
 * Script para debug de usuários
 */

require_once __DIR__ . '/includes/init.php';

try {
    // Buscar todos os usuários
    $stmt = $pdo->prepare("SELECT id, first_name, last_name, email, status, user_type, created_at FROM users ORDER BY created_at DESC LIMIT 10");
    $stmt->execute();
    $users = $stmt->fetchAll();
    
    echo "<h2>Últimos 10 usuários cadastrados:</h2>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Nome</th><th>Email</th><th>Status</th><th>Tipo</th><th>Cadastrado em</th><th>Ações</th></tr>";
    
    foreach ($users as $user) {
        echo "<tr>";
        echo "<td>" . $user['id'] . "</td>";
        echo "<td>" . $user['first_name'] . " " . $user['last_name'] . "</td>";
        echo "<td>" . $user['email'] . "</td>";
        echo "<td style='color: " . ($user['status'] == 'active' ? 'green' : 'red') . "'>" . $user['status'] . "</td>";
        echo "<td>" . $user['user_type'] . "</td>";
        echo "<td>" . $user['created_at'] . "</td>";
        echo "<td>";
        if ($user['status'] == 'pending') {
            echo "<a href='?activate=" . $user['id'] . "' style='color: blue;'>Ativar</a>";
        }
        echo "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    
    // Ativar usuário se solicitado
    if (isset($_GET['activate'])) {
        $user_id = (int)$_GET['activate'];
        $stmt = $pdo->prepare("UPDATE users SET status = 'active' WHERE id = ?");
        $stmt->execute([$user_id]);
        echo "<p style='color: green;'>Usuário ID $user_id foi ativado com sucesso!</p>";
        echo "<script>setTimeout(() => window.location.href = 'debug_users.php', 2000);</script>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Erro: " . $e->getMessage() . "</p>";
}
?>

<h3>Como testar o login:</h3>
<ol>
    <li>Verifique se o usuário tem status 'active' (verde)</li>
    <li>Se estiver 'pending' (vermelho), clique em "Ativar"</li>
    <li>Tente fazer login novamente</li>
</ol>

<p><a href="Login.php">Ir para página de Login</a></p>
<p><a href="Cadastro.php">Ir para página de Cadastro</a></p>
