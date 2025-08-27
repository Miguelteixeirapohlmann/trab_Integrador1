<?php
/**
 * Script para configurar automaticamente o banco de dados
 * Execute este arquivo no navegador para criar o banco e tabelas
 */

// Configurações do banco (usando as mesmas do config)
$host = 'localhost';
$port = '3307';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

// Se está sendo executado via navegador, mostrar HTML
$is_web = !empty($_SERVER['HTTP_HOST']);

if ($is_web) {
    echo "<!DOCTYPE html><html><head><title>Configuração do Banco</title><style>body{font-family:Arial,sans-serif;max-width:800px;margin:50px auto;padding:20px;} .success{color:green;} .error{color:red;} .warning{color:orange;}</style></head><body>";
    echo "<h2>🔧 Configurando Banco de Dados...</h2>";
}

try {
    // Conectar sem especificar banco
    $dsn = "mysql:host=$host;port=$port;charset=$charset";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    
    echo "<div class='success'>✅ Conectado ao MySQL</div>";
    
    // Ler o arquivo SQL
    $sql_file = __DIR__ . '/database.sql';
    if (!file_exists($sql_file)) {
        throw new Exception("Arquivo database.sql não encontrado!");
    }
    
    $sql = file_get_contents($sql_file);
    echo "<div class='success'>✅ Arquivo SQL carregado</div>";
    
    // Dividir o SQL em comandos individuais
    $sql_commands = explode(';', $sql);
    
    $success_count = 0;
    $error_count = 0;
    
    foreach ($sql_commands as $command) {
        $command = trim($command);
        
        // Pular comandos vazios e comentários
        if (empty($command) || strpos($command, '--') === 0) {
            continue;
        }
        
        try {
            $pdo->exec($command);
            $success_count++;
            
            // Mostrar apenas comandos importantes
            if (strpos($command, 'CREATE DATABASE') !== false) {
                echo "<div class='success'>✅ Banco de dados 'real_estate' criado</div>";
            } elseif (strpos($command, 'CREATE TABLE') !== false) {
                preg_match('/CREATE TABLE\s+(\w+)/', $command, $matches);
                if (isset($matches[1])) {
                    echo "<div class='success'>✅ Tabela '{$matches[1]}' criada</div>";
                }
            } elseif (strpos($command, 'INSERT INTO users') !== false) {
                echo "<div class='success'>✅ Usuários padrão inseridos</div>";
            } elseif (strpos($command, 'INSERT INTO brokers') !== false) {
                echo "<div class='success'>✅ Dados dos corretores inseridos</div>";
            } elseif (strpos($command, 'INSERT INTO properties') !== false) {
                echo "<div class='success'>✅ Propriedades de exemplo inseridas</div>";
            }
            
        } catch (PDOException $e) {
            $error_message = $e->getMessage();
            
            // Ignorar alguns erros "esperados"
            if (strpos($error_message, 'already exists') !== false || 
                strpos($error_message, 'Duplicate entry') !== false) {
                continue;
            }
            
            $error_count++;
            echo "<div class='error'>❌ Erro: " . htmlspecialchars($error_message) . "</div>";
        }
    }
    
    echo "<hr>";
    echo "<h3>📊 Resumo da Configuração:</h3>";
    echo "<p><strong>Comandos executados:</strong> $success_count</p>";
    echo "<p><strong>Erros encontrados:</strong> $error_count</p>";
    
    if ($error_count == 0) {
        echo "<div class='success'><h3>🎉 Banco de dados configurado com sucesso!</h3></div>";
        echo "<h4>📋 Dados de acesso padrão:</h4>";
        echo "<ul>";
        echo "<li><strong>Admin:</strong> admin@realestate.com / password</li>";
        echo "<li><strong>Corretor 1:</strong> joao.silva@realestate.com / password</li>";
        echo "<li><strong>Corretor 2:</strong> maria.santos@realestate.com / password</li>";
        echo "<li><strong>Corretor 3:</strong> pedro.costa@realestate.com / password</li>";
        echo "</ul>";
        echo "<p><em>💡 Lembre-se de alterar as senhas padrão após o primeiro acesso!</em></p>";
        echo "<br><a href='index.php' style='background: #007bff; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; font-size: 16px;'>🏠 Ir para a Página Principal</a>";
        echo "<br><br><a href='test_connection.php' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-left: 10px;'>🔍 Testar Conexão</a>";
    } else {
        echo "<div class='error'><h3>❌ Alguns erros ocorreram durante a configuração.</h3></div>";
        echo "<p>Verifique os erros acima e tente executar o script novamente.</p>";
    }
    
} catch (Exception $e) {
    echo "<div class='error'><h3>❌ Erro fatal:</h3>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p></div>";
    
    $message = $e->getMessage();
    if (strpos($message, 'Connection refused') !== false) {
        echo "<div class='warning'><h4>💡 Solução:</h4>";
        echo "<p>1. Abra o painel de controle do XAMPP</p>";
        echo "<p>2. Inicie o serviço <strong>MySQL</strong></p>";
        echo "<p>3. Execute este script novamente</p></div>";
    } elseif (strpos($message, 'Access denied') !== false) {
        echo "<div class='warning'><h4>💡 Solução:</h4>";
        echo "<p>1. Verifique se o usuário 'root' está correto</p>";
        echo "<p>2. Se houver senha configurada no MySQL, atualize o arquivo config/database.php</p></div>";
    }
}

if ($is_web) {
    echo "</body></html>";
}
?>
