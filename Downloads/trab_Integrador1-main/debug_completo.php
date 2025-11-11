<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Debug Completo do Sistema</h1>";

// Teste 1: Configurações
echo "<h2>1. Configurações</h2>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Docker env check: " . (file_exists('/.dockerenv') ? 'SIM (Docker)' : 'NÃO (Local)') . "<br>";

// Teste 2: Configuração do banco
echo "<h2>2. Configuração do Banco</h2>";
require_once __DIR__ . '/config/database.php';
echo "DB_HOST: " . DB_HOST . "<br>";
echo "DB_NAME: " . DB_NAME . "<br>";
echo "DB_USER: " . DB_USER . "<br>";
echo "DB_PASS: " . (DB_PASS ? '[DEFINIDA: ' . DB_PASS . ']' : '[VAZIA]') . "<br>";

// Teste 3: Conexão direta
echo "<h2>3. Teste de Conexão Direta</h2>";
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo_direct = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    echo "✅ Conexão direta bem-sucedida!<br>";
    
    // Testar consulta
    $stmt = $pdo_direct->query("SELECT COUNT(*) as total FROM users");
    $result = $stmt->fetch();
    echo "Total de usuários: " . $result['total'] . "<br>";
    
} catch (Exception $e) {
    echo "❌ Erro na conexão direta: " . $e->getMessage() . "<br>";
}

// Teste 4: Inclusão do sistema
echo "<h2>4. Teste do Sistema</h2>";
try {
    require_once __DIR__ . '/includes/database.php';
    echo "✅ Database.php incluído com sucesso!<br>";
    
    global $pdo;
    if ($pdo) {
        echo "✅ Variável \$pdo está disponível!<br>";
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM users");
        $result = $stmt->fetch();
        echo "Total via \$pdo global: " . $result['total'] . "<br>";
    } else {
        echo "❌ Variável \$pdo não está disponível!<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Erro ao incluir sistema: " . $e->getMessage() . "<br>";
}

// Teste 5: Auth class
echo "<h2>5. Teste da Classe Auth</h2>";
try {
    session_start();
    require_once __DIR__ . '/includes/auth.php';
    $auth = new Auth();
    echo "✅ Classe Auth instanciada com sucesso!<br>";
    
    // Teste manual de login
    echo "<h3>5.1 Teste Manual de Login</h3>";
    $email = 'admin@admin.com';
    $senha = '123456';
    
    echo "Testando: $email / $senha<br>";
    
    // Buscar usuário manualmente
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND status = 'active'");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user) {
        echo "✅ Usuário encontrado: " . $user['first_name'] . " " . $user['last_name'] . "<br>";
        echo "Hash no banco: " . $user['password'] . "<br>";
        
        $verify_result = password_verify($senha, $user['password']);
        echo "password_verify result: " . ($verify_result ? 'TRUE ✅' : 'FALSE ❌') . "<br>";
        
        if ($verify_result) {
            // Testar login via auth
            $login_result = $auth->login($email, $senha);
            echo "Login via Auth class: " . ($login_result['success'] ? 'SUCESSO ✅' : 'FALHA ❌ - ' . $login_result['message']) . "<br>";
        }
    } else {
        echo "❌ Usuário não encontrado<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Erro na classe Auth: " . $e->getMessage() . "<br>";
    echo "Stack trace: <pre>" . $e->getTraceAsString() . "</pre>";
}

// Teste 6: Verificar todas as senhas
echo "<h2>6. Verificação de Todas as Senhas</h2>";
try {
    $stmt = $pdo->query("SELECT id, email, password FROM users");
    $users = $stmt->fetchAll();
    
    foreach ($users as $user) {
        $verify = password_verify('123456', $user['password']);
        echo "ID {$user['id']} - {$user['email']}: " . ($verify ? '✅ Senha OK' : '❌ Senha ERRO') . "<br>";
    }
} catch (Exception $e) {
    echo "❌ Erro ao verificar senhas: " . $e->getMessage() . "<br>";
}

?>
