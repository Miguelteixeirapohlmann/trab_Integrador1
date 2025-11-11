<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste de Login - Debug</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .test-section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        .success { color: green; }
        .error { color: red; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>🔍 Sistema de Debug - Login</h1>

    <div class="test-section">
        <h2>📋 Contas Disponíveis para Teste</h2>
        <table>
            <tr><th>Tipo</th><th>Email</th><th>Senha</th><th>Status</th></tr>
            <tr><td>Admin</td><td>admin@admin.com</td><td>123456</td><td class="success">✅ Ativo</td></tr>
            <tr><td>Usuário</td><td>user@test.com</td><td>123456</td><td class="success">✅ Ativo</td></tr>
            <tr><td>Corretor</td><td>pedro@corretor.com</td><td>123456</td><td class="success">✅ Ativo</td></tr>
            <tr><td>Corretor</td><td>maria@corretor.com</td><td>123456</td><td class="success">✅ Ativo</td></tr>
            <tr><td>Corretor</td><td>joao@corretor.com</td><td>123456</td><td class="success">✅ Ativo</td></tr>
        </table>
    </div>

    <div class="test-section">
        <h2>🔗 Links de Teste</h2>
        <p><a href="Login.php" target="_blank">🚀 Login Principal (Login.php)</a></p>
        <p><a href="Login_ADM.php" target="_blank">👑 Login Admin (Login_ADM.php)</a></p>
        <p><a href="Login_Corretor.php" target="_blank">🏢 Login Corretor (Login_Corretor.php)</a></p>
        <p><a href="index.php" target="_blank">🏠 Página Inicial</a></p>
    </div>

    <div class="test-section">
        <h2>🔍 Teste Rápido de Login</h2>
        <form method="POST" action="">
            <label>Email:</label><br>
            <input type="email" name="email" value="admin@admin.com" required style="width: 300px; padding: 5px;"><br><br>
            
            <label>Senha:</label><br>
            <input type="password" name="password" value="123456" required style="width: 300px; padding: 5px;"><br><br>
            
            <button type="submit" name="test_login" style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 5px;">🔐 Testar Login</button>
        </form>

        <?php
        if (isset($_POST['test_login'])) {
            echo "<div style='margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 5px;'>";
            echo "<h3>Resultado do Teste:</h3>";
            
            try {
                require_once __DIR__ . '/includes/init.php';
                
                $email = $_POST['email'];
                $password = $_POST['password'];
                
                echo "<p><strong>Email:</strong> $email</p>";
                echo "<p><strong>Senha:</strong> [OCULTA]</p>";
                
                $result = $auth->login($email, $password);
                
                if ($result['success']) {
                    echo "<p class='success'>✅ <strong>LOGIN BEM-SUCEDIDO!</strong></p>";
                    echo "<p>Usuário: " . $result['user']['first_name'] . " " . $result['user']['last_name'] . "</p>";
                    echo "<p>Tipo: " . $result['user']['user_type'] . "</p>";
                    echo "<p>Status: " . $result['user']['status'] . "</p>";
                    
                    // Fazer logout para não interferir
                    $auth->logout();
                } else {
                    echo "<p class='error'>❌ <strong>FALHA NO LOGIN</strong></p>";
                    echo "<p>Mensagem: " . $result['message'] . "</p>";
                }
                
            } catch (Exception $e) {
                echo "<p class='error'>❌ <strong>ERRO INTERNO:</strong> " . $e->getMessage() . "</p>";
            }
            
            echo "</div>";
        }
        ?>
    </div>

    <div class="test-section">
        <h2>🗄️ Status do Banco de Dados</h2>
        <?php
        try {
            require_once __DIR__ . '/includes/database.php';
            echo "<p class='success'>✅ Conexão com banco de dados: OK</p>";
            
            $stmt = $pdo->query("SELECT COUNT(*) as total FROM users WHERE status = 'active'");
            $result = $stmt->fetch();
            echo "<p>👥 Usuários ativos: " . $result['total'] . "</p>";
            
        } catch (Exception $e) {
            echo "<p class='error'>❌ Erro na conexão: " . $e->getMessage() . "</p>";
        }
        ?>
    </div>

    <div class="test-section">
        <h2>⚙️ Informações do Sistema</h2>
        <p><strong>Servidor:</strong> <?php echo $_SERVER['SERVER_NAME']; ?>:<?php echo $_SERVER['SERVER_PORT']; ?></p>
        <p><strong>PHP:</strong> <?php echo PHP_VERSION; ?></p>
        <p><strong>Data/Hora:</strong> <?php echo date('d/m/Y H:i:s'); ?></p>
    </div>
</body>
</html>
