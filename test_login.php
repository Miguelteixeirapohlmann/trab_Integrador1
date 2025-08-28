<?php
/**
 * Script para testar login manualmente
 */

require_once __DIR__ . '/includes/init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    
    echo "<h2>Teste de Login</h2>";
    echo "<p><strong>Email digitado:</strong> $email</p>";
    echo "<p><strong>Senha digitada:</strong> $senha</p>";
    
    try {
        // Buscar usuário no banco
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user) {
            echo "<h3 style='color: green;'>✅ Usuário encontrado no banco!</h3>";
            echo "<p><strong>ID:</strong> " . $user['id'] . "</p>";
            echo "<p><strong>Nome:</strong> " . $user['first_name'] . " " . $user['last_name'] . "</p>";
            echo "<p><strong>Email no banco:</strong> " . $user['email'] . "</p>";
            echo "<p><strong>Status:</strong> <span style='color: " . ($user['status'] == 'active' ? 'green' : 'red') . "'>" . $user['status'] . "</span></p>";
            echo "<p><strong>Senha hash no banco:</strong> " . substr($user['password'], 0, 20) . "...</p>";
            
            // Testar verificação da senha
            $senha_valida = password_verify($senha, $user['password']);
            echo "<p><strong>Senha válida:</strong> <span style='color: " . ($senha_valida ? 'green' : 'red') . "'>" . ($senha_valida ? 'SIM' : 'NÃO') . "</span></p>";
            
            if ($user['status'] !== 'active') {
                echo "<p style='color: red;'>❌ <strong>PROBLEMA:</strong> Usuário não está ativo! Status: " . $user['status'] . "</p>";
                echo "<p><a href='debug_users.php'>Clique aqui para ativar o usuário</a></p>";
            } elseif (!$senha_valida) {
                echo "<p style='color: red;'>❌ <strong>PROBLEMA:</strong> Senha incorreta!</p>";
                echo "<p>Tente cadastrar novamente ou usar a opção 'Esqueci minha senha'.</p>";
            } else {
                echo "<p style='color: green;'>✅ <strong>TUDO OK:</strong> Login deveria funcionar!</p>";
                
                // Tentar fazer login através da classe Auth
                $auth = new Auth();
                $result = $auth->login($email, $senha);
                
                if ($result['success']) {
                    echo "<p style='color: green;'>✅ <strong>LOGIN REALIZADO COM SUCESSO!</strong></p>";
                    echo "<p><a href='index.php'>Ir para página inicial</a></p>";
                } else {
                    echo "<p style='color: red;'>❌ <strong>ERRO NO LOGIN:</strong> " . $result['message'] . "</p>";
                }
            }
            
        } else {
            echo "<h3 style='color: red;'>❌ Usuário NÃO encontrado no banco!</h3>";
            echo "<p>Verifique se o email está correto ou faça um novo cadastro.</p>";
        }
        
    } catch (Exception $e) {
        echo "<p style='color: red;'>Erro: " . $e->getMessage() . "</p>";
    }
    
    echo "<hr>";
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste de Login</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .form-group { margin-bottom: 15px; }
        input[type="email"], input[type="password"] { width: 300px; padding: 8px; }
        button { padding: 10px 20px; background: #007bff; color: white; border: none; }
    </style>
</head>
<body>
    <h1>🔧 Teste de Login - Debug</h1>
    
    <form method="POST">
        <div class="form-group">
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
        </div>
        
        <div class="form-group">
            <label for="senha">Senha:</label><br>
            <input type="password" id="senha" name="senha" required>
        </div>
        
        <button type="submit">🔍 Testar Login</button>
    </form>
    
    <hr>
    <p><a href="debug_users.php">📋 Ver lista de usuários</a></p>
    <p><a href="Login.php">🔐 Ir para Login normal</a></p>
    <p><a href="Cadastro.php">📝 Fazer novo cadastro</a></p>
</body>
</html>
