<?php
/**
 * Script para resetar senha de usuário específico
 */

require_once __DIR__ . '/config/database.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $new_password = trim($_POST['password'] ?? '');
    
    if (!empty($email) && !empty($new_password)) {
        try {
            $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
            
            // Verificar se o usuário existe
            $stmt = $pdo->prepare("SELECT id, first_name, email FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if ($user) {
                // Hash da nova senha
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                
                // Atualizar senha e status
                $stmt = $pdo->prepare("UPDATE users SET password = ?, status = 'active' WHERE email = ?");
                $stmt->execute([$hashed_password, $email]);
                
                $message = "<div style='color: green; border: 1px solid green; padding: 10px; margin: 10px 0;'>";
                $message .= "✅ Senha resetada com sucesso para: " . $user['first_name'] . " (" . $user['email'] . ")<br>";
                $message .= "Nova senha: <strong>" . htmlspecialchars($new_password) . "</strong><br>";
                $message .= "Status alterado para: <strong>active</strong>";
                $message .= "</div>";
            } else {
                $message = "<div style='color: red; border: 1px solid red; padding: 10px; margin: 10px 0;'>";
                $message .= "❌ Usuário não encontrado: " . htmlspecialchars($email);
                $message .= "</div>";
            }
            
        } catch (Exception $e) {
            $message = "<div style='color: red; border: 1px solid red; padding: 10px; margin: 10px 0;'>";
            $message .= "❌ Erro: " . $e->getMessage();
            $message .= "</div>";
        }
    } else {
        $message = "<div style='color: orange; border: 1px solid orange; padding: 10px; margin: 10px 0;'>";
        $message .= "⚠️ Preencha todos os campos";
        $message .= "</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset de Senha</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; max-width: 600px; }
        .form-group { margin: 15px 0; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="email"], input[type="password"], input[type="text"] {
            width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;
        }
        button { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #0056b3; }
        .nav-links { margin: 20px 0; }
        .nav-links a { margin-right: 15px; text-decoration: none; color: #007bff; }
    </style>
</head>
<body>
    <h1>🔑 Reset de Senha</h1>
    
    <?php echo $message; ?>
    
    <form method="POST">
        <div class="form-group">
            <label for="email">Email do usuário:</label>
            <input type="email" id="email" name="email" required>
        </div>
        
        <div class="form-group">
            <label for="password">Nova senha:</label>
            <input type="text" id="password" name="password" required placeholder="Digite a nova senha">
            <small>Dica: Use uma senha simples para teste, como '123456'</small>
        </div>
        
        <button type="submit">🔄 Resetar Senha</button>
    </form>
    
    <div class="nav-links">
        <h3>Navegação:</h3>
        <a href="test_database.php">🗄️ Testar Banco</a>
        <a href="debug_users.php">👥 Ver Usuários</a>
        <a href="test_login.php">🔍 Testar Login</a>
        <a href="Login.php">🔐 Página de Login</a>
        <a href="Cadastro.php">📝 Cadastro</a>
    </div>
    
    <hr>
    <h3>Como usar:</h3>
    <ol>
        <li>Digite o email do usuário que está com problema no login</li>
        <li>Digite uma nova senha (ex: 123456)</li>
        <li>Clique em "Resetar Senha"</li>
        <li>O usuário será automaticamente ativado (status = active)</li>
        <li>Teste o login na página de login</li>
    </ol>
</body>
</html>
