<?php
/**
 * Script para testar a conexão com o banco de dados
 */

// Configurações do banco
$host = 'localhost';
$port = '3307';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

// Primeiro, vamos tentar conectar sem especificar o banco para ver se o MySQL está funcionando
try {
    $dsn = "mysql:host=$host;port=$port;charset=$charset";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    
    echo "✅ Conexão com MySQL: OK<br>";
    
    // Verificar se o banco real_estate existe
    $stmt = $pdo->query("SHOW DATABASES LIKE 'real_estate'");
    $database_exists = $stmt->rowCount() > 0;
    
    if ($database_exists) {
        echo "✅ Banco de dados 'real_estate': Existe<br>";
        
        // Conectar ao banco específico
        $dsn = "mysql:host=$host;port=$port;dbname=real_estate;charset=$charset";
        $pdo_db = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        
        echo "✅ Conexão com banco 'real_estate': OK<br>";
        
        // Verificar se as tabelas existem
        $stmt = $pdo_db->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        if (count($tables) > 0) {
            echo "✅ Tabelas encontradas: " . implode(', ', $tables) . "<br>";
        } else {
            echo "❌ Nenhuma tabela encontrada no banco 'real_estate'<br>";
            echo "<strong>Solução:</strong> Execute o arquivo database.sql no phpMyAdmin<br>";
        }
        
    } else {
        echo "❌ Banco de dados 'real_estate': Não existe<br>";
        echo "<strong>Solução:</strong> Execute o arquivo database.sql no phpMyAdmin<br>";
    }
    
} catch (PDOException $e) {
    echo "❌ Erro na conexão: " . $e->getMessage() . "<br>";
    
    if (strpos($e->getMessage(), 'Connection refused') !== false) {
        echo "<strong>Solução:</strong> Verifique se o MySQL/MariaDB está rodando no XAMPP<br>";
    } elseif (strpos($e->getMessage(), 'Access denied') !== false) {
        echo "<strong>Solução:</strong> Verifique usuário e senha do banco de dados<br>";
    }
}

echo "<hr>";
echo "<h3>Informações do Sistema:</h3>";
echo "PHP Version: " . PHP_VERSION . "<br>";
echo "PDO MySQL: " . (extension_loaded('pdo_mysql') ? 'Disponível' : 'Não disponível') . "<br>";
echo "Servidor: " . $_SERVER['SERVER_SOFTWARE'] . "<br>";

?>
