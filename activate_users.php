<?php
// Script temporário para ativar usuários
require_once 'config/database.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    
    // Ativar todos os usuários pendentes
    $stmt = $pdo->prepare("UPDATE users SET status = 'active' WHERE status = 'pending'");
    $stmt->execute();
    $count = $stmt->rowCount();
    
    echo "✅ Ativados {$count} usuários!<br>";
    
    // Mostrar usuários ativos
    $stmt = $pdo->prepare("SELECT first_name, last_name, email, status FROM users WHERE status = 'active'");
    $stmt->execute();
    $users = $stmt->fetchAll();
    
    echo "<h3>Usuários Ativos:</h3>";
    foreach($users as $user) {
        echo "- {$user['first_name']} {$user['last_name']} ({$user['email']}) - Status: {$user['status']}<br>";
    }
    
    echo "<br><strong>Agora você pode fazer login normalmente!</strong>";
    echo "<br><a href='Login.php'>🔐 Ir para Login</a>";
    
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage();
}

// Auto-delete este arquivo após execução
if (isset($_GET['delete'])) {
    unlink(__FILE__);
    echo "<br><br>✅ Arquivo temporário removido!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ativação de Usuários</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        a { color: #007bff; text-decoration: none; }
    </style>
</head>
<body>
    <h1>🔧 Ativação de Usuários</h1>
    <p><a href="?delete=1">🗑️ Remover este arquivo</a></p>
</body>
</html>
