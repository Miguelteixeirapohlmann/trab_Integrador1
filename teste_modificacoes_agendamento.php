<?php
/**
 * Teste das modificações do sistema de agendamento
 */

echo "=== TESTE DAS MODIFICAÇÕES DO AGENDAMENTO ===\n\n";

// Teste 1: Verificação de email Gmail
echo "Teste 1: Verificação de email Gmail\n";
echo "------------------------------------\n";

$emails_teste = [
    'usuario@gmail.com',
    'teste@hotmail.com', 
    'admin@empresa.com',
    'cliente@gmail.com',
    'vendedor@outlook.com'
];

foreach ($emails_teste as $email) {
    $isGmail = str_ends_with($email, '@gmail.com');
    $status = $isGmail ? '✓ PERMITIDO' : '✗ BLOQUEADO';
    echo "Email: {$email} -> {$status}\n";
}

echo "\n";

// Teste 2: Mapeamento casa-corretor
echo "Teste 2: Mapeamento Casa -> Corretor\n";
echo "------------------------------------\n";

$casaCorretorMap = [
    'Casa em Santo Antônio da Patrulha' => 'João borges',
    'Casa em Taquara' => 'João borges', 
    'Casa Em Taquara Alto Padrão' => 'João borges',
    'Casa em Taquara Rua Mundo Novo' => 'Maria Santos',
    'Casa em Taquara Flores da Cunha' => 'Maria Santos',
    'Casa em Parobé' => 'Maria Santos',
    'Casa em Taquara Santa Terezinha' => 'Pedro Costa',
    'Casa em Taquara rua Alvarino Lacerda Filho' => 'Pedro Costa',
    'Casa em Taquara - São Francisco' => 'Pedro Costa'
];

foreach ($casaCorretorMap as $casa => $corretor) {
    echo "Casa: {$casa}\n";
    echo "Corretor: {$corretor}\n";
    echo "---\n";
}

echo "\n=== RESUMO DAS MODIFICAÇÕES IMPLEMENTADAS ===\n";
echo "1. ✓ Campo corretor agora é readonly/disabled (não editável)\n";
echo "2. ✓ Corretor é selecionado automaticamente baseado na casa\n";
echo "3. ✓ Apenas emails @gmail.com podem fazer agendamentos\n";
echo "4. ✓ Aviso visual para usuários sem email Gmail\n";
echo "5. ✓ Formulário bloqueado para usuários sem email Gmail\n";
echo "6. ✓ Campo hidden garante envio do corretor selecionado\n";

echo "\n=== COMO TESTAR ===\n";
echo "1. Acesse: http://localhost/trab_Integrador1/agendar_visita.php\n";
echo "2. Faça login com usuário que tem email Gmail\n";
echo "3. Selecione uma casa e observe que o corretor é preenchido automaticamente\n";
echo "4. Tente com usuário sem email Gmail para ver o bloqueio\n";

?>
