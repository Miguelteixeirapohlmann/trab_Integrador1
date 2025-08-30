<?php
// Teste para verificar os dias da semana
echo "<h3>Teste de Dias da Semana</h3>";

// Testa algumas datas conhecidas
$dates = [
    '2025-09-01', // Segunda-feira
    '2025-09-06', // Sábado
    '2025-09-07', // Domingo
    '2025-09-08', // Segunda-feira
];

foreach ($dates as $date) {
    $dt = DateTime::createFromFormat('Y-m-d', $date);
    $dayOfWeek = (int)$dt->format('w');
    $dayNames = ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];
    
    echo "Data: $date - Dia: " . $dayNames[$dayOfWeek] . " (código: $dayOfWeek)<br>";
    
    // Teste das validações
    if ($dayOfWeek === 0) {
        echo "  -> É DOMINGO - deve ser bloqueado<br>";
    }
    if ($dayOfWeek === 6) {
        echo "  -> É SÁBADO - horários limitados até 11:00<br>";
    }
    echo "<br>";
}
?>

<script>
// Teste JavaScript
console.log("=== Teste JavaScript ===");
const testDates = ['2025-09-01', '2025-09-06', '2025-09-07', '2025-09-08'];
const dayNames = ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];

testDates.forEach(dateStr => {
    const date = new Date(dateStr + 'T00:00:00');
    const dayOfWeek = date.getDay();
    console.log(`Data: ${dateStr} - Dia: ${dayNames[dayOfWeek]} (código: ${dayOfWeek})`);
    
    if (dayOfWeek === 0) {
        console.log("  -> É DOMINGO - deve ser bloqueado");
    }
    if (dayOfWeek === 6) {
        console.log("  -> É SÁBADO - horários limitados até 11:00");
    }
});
</script>
