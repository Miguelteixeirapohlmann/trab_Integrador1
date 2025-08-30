<?php
/**
 * Script para atualizar o banco de dados com as novas tabelas de compra e aluguel
 */

require_once __DIR__ . '/config/database.php';

echo "=== ATUALIZAÇÃO DO BANCO DE DADOS ===\n\n";

try {
    // Ler e executar o arquivo database.sql
    $sql_content = file_get_contents(__DIR__ . '/database.sql');
    
    if ($sql_content === false) {
        throw new Exception("Erro ao ler o arquivo database.sql");
    }
    
    echo "Arquivo database.sql carregado com sucesso!\n";
    
    // Dividir o conteúdo em comandos individuais
    $commands = explode(';', $sql_content);
    
    $executed = 0;
    $errors = 0;
    
    foreach ($commands as $command) {
        $command = trim($command);
        
        // Pular comandos vazios e comentários
        if (empty($command) || strpos($command, '--') === 0) {
            continue;
        }
        
        try {
            $pdo->exec($command);
            $executed++;
            
            // Mostrar progresso para comandos importantes
            if (strpos($command, 'CREATE TABLE') !== false) {
                preg_match('/CREATE TABLE (\w+)/', $command, $matches);
                if (isset($matches[1])) {
                    echo "✓ Tabela '{$matches[1]}' criada/atualizada\n";
                }
            } elseif (strpos($command, 'INSERT INTO') !== false) {
                preg_match('/INSERT INTO (\w+)/', $command, $matches);
                if (isset($matches[1])) {
                    echo "✓ Dados inseridos na tabela '{$matches[1]}'\n";
                }
            }
            
        } catch (Exception $e) {
            $errors++;
            echo "✗ Erro ao executar comando: " . $e->getMessage() . "\n";
            echo "  Comando: " . substr($command, 0, 100) . "...\n";
        }
    }
    
    echo "\n=== RESUMO ===\n";
    echo "Comandos executados: $executed\n";
    echo "Erros encontrados: $errors\n";
    
    if ($errors === 0) {
        echo "\n🎉 Banco de dados atualizado com sucesso!\n";
        
        // Verificar se as novas tabelas foram criadas
        echo "\n=== VERIFICAÇÃO DAS TABELAS ===\n";
        
        $tables_to_check = [
            'property_purchases' => 'Tabela de compras de imóveis',
            'property_rentals' => 'Tabela de aluguéis de imóveis', 
            'payments' => 'Tabela de pagamentos'
        ];
        
        foreach ($tables_to_check as $table => $description) {
            try {
                $stmt = $pdo->query("SELECT COUNT(*) as count FROM $table");
                $result = $stmt->fetch();
                echo "✓ $description: {$result['count']} registros\n";
            } catch (Exception $e) {
                echo "✗ $description: Erro - " . $e->getMessage() . "\n";
            }
        }
        
    } else {
        echo "\n⚠️  Atualização concluída com alguns erros. Verifique os logs acima.\n";
    }
    
} catch (Exception $e) {
    echo "❌ ERRO CRÍTICO: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n=== ESTRUTURA DAS NOVAS TABELAS ===\n";
echo "\n📋 TABELA property_purchases (Compras):\n";
echo "- Gerencia todo o processo de compra/venda\n";
echo "- Inclui informações financeiras (preço, entrada, financiamento)\n";
echo "- Controla status do processo (pendente → aprovado → contratado → concluído)\n";
echo "- Armazena dados do comprador, vendedor, corretor\n";
echo "- Suporte a documentos e observações\n";

echo "\n🏠 TABELA property_rentals (Aluguéis):\n";
echo "- Gerencia contratos de locação\n";
echo "- Controla período, valores, depósitos\n";
echo "- Status do contrato (ativo, expirado, rescindido)\n";
echo "- Informações do inquilino e proprietário\n";
echo "- Renovação automática e termos especiais\n";

echo "\n💰 TABELA payments (Pagamentos):\n";
echo "- Rastreia todos os pagamentos relacionados\n";
echo "- Aluguéis mensais, entradas, parcelas\n";
echo "- Diferentes métodos de pagamento\n";
echo "- Status de pagamento e vencimentos\n";
echo "- Comprovantes e referências de transação\n";

echo "\nBanco de dados pronto para uso! 🚀\n";
?>
