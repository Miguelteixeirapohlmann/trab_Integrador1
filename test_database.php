<?php
/**
 * Script para testar a estrutura da tabela users
 */

require_once __DIR__ . '/config/database.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    
    echo "<h2>✅ Conexão com banco OK!</h2>";
    
    // Verificar se a tabela users existe
    $stmt = $pdo->prepare("SHOW TABLES LIKE 'users'");
    $stmt->execute();
    $table_exists = $stmt->fetch();
    
    if ($table_exists) {
        echo "<h3>✅ Tabela 'users' existe</h3>";
        
        // Mostrar estrutura da tabela
        echo "<h4>Estrutura da tabela:</h4>";
        $stmt = $pdo->prepare("DESCRIBE users");
        $stmt->execute();
        $columns = $stmt->fetchAll();
        
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>Campo</th><th>Tipo</th><th>Nulo</th><th>Chave</th><th>Padrão</th></tr>";
        foreach ($columns as $column) {
            echo "<tr>";
            echo "<td>" . $column['Field'] . "</td>";
            echo "<td>" . $column['Type'] . "</td>";
            echo "<td>" . $column['Null'] . "</td>";
            echo "<td>" . $column['Key'] . "</td>";
            echo "<td>" . $column['Default'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Contar usuários
        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM users");
        $stmt->execute();
        $count = $stmt->fetch();
        
        echo "<h4>Total de usuários cadastrados: " . $count['total'] . "</h4>";
        
        if ($count['total'] > 0) {
            // Mostrar alguns usuários
            $stmt = $pdo->prepare("SELECT id, first_name, last_name, email, status, created_at FROM users ORDER BY created_at DESC LIMIT 5");
            $stmt->execute();
            $users = $stmt->fetchAll();
            
            echo "<h4>Últimos usuários:</h4>";
            echo "<table border='1' cellpadding='5'>";
            echo "<tr><th>ID</th><th>Nome</th><th>Email</th><th>Status</th><th>Data</th></tr>";
            foreach ($users as $user) {
                echo "<tr>";
                echo "<td>" . $user['id'] . "</td>";
                echo "<td>" . $user['first_name'] . " " . $user['last_name'] . "</td>";
                echo "<td>" . $user['email'] . "</td>";
                echo "<td style='color: " . ($user['status'] == 'active' ? 'green' : 'red') . "'>" . $user['status'] . "</td>";
                echo "<td>" . $user['created_at'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
        
    } else {
        echo "<h3 style='color: red;'>❌ Tabela 'users' NÃO existe!</h3>";
        echo "<p>Execute o script SQL para criar as tabelas.</p>";
    }
    
} catch (Exception $e) {
    echo "<h2 style='color: red;'>❌ Erro de conexão!</h2>";
    echo "<p>Erro: " . $e->getMessage() . "</p>";
    echo "<p>Verifique as configurações no arquivo config/database.php</p>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Teste do Banco de Dados</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; margin: 10px 0; }
        th { background-color: #f0f0f0; }
    </style>
</head>
<body>
    <h1>🗄️ Teste do Banco de Dados</h1>
    
    <hr>
    <p><a href="test_login.php">🔍 Testar Login</a></p>
    <p><a href="debug_users.php">👥 Ver/Ativar Usuários</a></p>
    <p><a href="Login.php">🔐 Página de Login</a></p>
</body>
</html>
