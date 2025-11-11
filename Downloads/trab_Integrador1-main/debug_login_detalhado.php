<?php
/**
 * Debug do processo de login
 */
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/includes/init.php';

echo "<h2>🔍 Debug do Sistema de Login</h2>";

// Simular um POST de login
if (!isset($_POST['email'])) {
    ?>
    <form method="POST">
        <h3>Testar Login:</h3>
        <p>
            <label>Email:</label><br>
            <input type="email" name="email" value="admin@admin.com" style="width:300px;padding:5px;">
        </p>
        <p>
            <label>Senha:</label><br>
            <input type="password" name="senha" value="123456" style="width:300px;padding:5px;">
        </p>
        <p>
            <button type="submit" style="padding:10px 20px;background:#007bff;color:white;border:none;cursor:pointer;">Testar Login</button>
        </p>
    </form>
    <?php
}

if (isset($_POST['email']) && isset($_POST['senha'])) {
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);
    
    echo "<h3>🔍 Processo de Login Detalhado</h3>";
    echo "<p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>";
    echo "<p><strong>Senha:</strong> " . htmlspecialchars($senha) . "</p>";
    
    // Passo 1: Validar email
    echo "<h4>1️⃣ Validação de Email:</h4>";
    $email_valido = validateEmail($email);
    echo "Resultado: " . ($email_valido ? "✅ Válido" : "❌ Inválido") . "<br>";
    
    if (!$email_valido) {
        echo "<p style='color:red;'>❌ Email inválido. Parando processo.</p>";
        exit;
    }
    
    // Passo 2: Buscar usuário no banco
    echo "<h4>2️⃣ Busca no Banco de Dados:</h4>";
    try {
        $stmt = $pdo->prepare("SELECT u.*, up.avatar, b.id as broker_id FROM users u LEFT JOIN user_profiles up ON u.id = up.user_id LEFT JOIN brokers b ON u.id = b.user_id WHERE u.email = ? AND u.status = 'active'");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user) {
            echo "✅ Usuário encontrado:<br>";
            echo "- ID: " . $user['id'] . "<br>";
            echo "- Nome: " . $user['first_name'] . " " . $user['last_name'] . "<br>";
            echo "- Email: " . $user['email'] . "<br>";
            echo "- Tipo: " . $user['user_type'] . "<br>";
            echo "- Status: " . $user['status'] . "<br>";
            echo "- Hash da senha: " . substr($user['password'], 0, 30) . "...<br>";
        } else {
            echo "❌ Usuário não encontrado ou inativo<br>";
            exit;
        }
    } catch (Exception $e) {
        echo "❌ Erro na consulta: " . $e->getMessage() . "<br>";
        exit;
    }
    
    // Passo 3: Verificar senha
    echo "<h4>3️⃣ Verificação de Senha:</h4>";
    $senha_correta = password_verify($senha, $user['password']);
    echo "password_verify('" . $senha . "', hash): " . ($senha_correta ? "✅ TRUE" : "❌ FALSE") . "<br>";
    
    if (!$senha_correta) {
        echo "<p style='color:red;'>❌ Senha incorreta. Parando processo.</p>";
        echo "<p>Debug da senha:</P>";
        echo "- Senha informada: '" . $senha . "'<br>";
        echo "- Hash no banco: " . $user['password'] . "<br>";
        echo "- Novo hash seria: " . password_hash($senha, PASSWORD_DEFAULT) . "<br>";
        exit;
    }
    
    // Passo 4: Testar login completo
    echo "<h4>4️⃣ Processo de Login Completo:</h4>";
    $result = $auth->login($email, $senha);
    
    if ($result['success']) {
        echo "✅ Login realizado com sucesso!<br>";
        echo "- Sessão criada para: " . $_SESSION['user_name'] . "<br>";
        echo "- Tipo de usuário: " . $_SESSION['user_type'] . "<br>";
        echo "- ID da sessão: " . session_id() . "<br>";
        
        echo "<h4>🎯 Redirecionamento:</h4>";
        if ($user['user_type'] === 'corretor') {
            echo "👨‍💼 Usuário é corretor - deveria ir para: gerenciar_imoveis.php<br>";
        } elseif ($user['user_type'] === 'admin') {
            echo "👑 Usuário é admin - deveria ir para: admin.php<br>";
        } else {
            echo "👤 Usuário comum - deveria ir para: index.php<br>";
        }
        
    } else {
        echo "❌ Falha no login: " . $result['message'] . "<br>";
    }
    
    echo "<h4>📊 Estado da Sessão:</h4>";
    echo "<pre>" . print_r($_SESSION, true) . "</pre>";
}
?>
