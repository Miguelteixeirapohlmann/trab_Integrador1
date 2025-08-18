// Dados simulados de casas e corretores
const casas = [
    { id: 1, nome: "Casa Realengo", corretor: "João Silva" },
    { id: 2, nome: "Casa Vista Alegre", corretor: "Maria Souza" },
    { id: 3, nome: "Casa Nova Esperança", corretor: "João Silva" }
];

window.addEventListener('DOMContentLoaded', () => {
    const casaSelect = document.getElementById('casaSelect');
    const corretorSelecionado = document.getElementById('corretorSelecionado');

    // Preenche o select de casas
    casas.forEach(casa => {
        const option = document.createElement('option');
        option.value = casa.id;
        option.textContent = casa.nome;
        casaSelect.appendChild(option);
    });

    // Atualiza corretor automaticamente ao escolher casa
    casaSelect.addEventListener('change', function() {
        const casa = casas.find(c => c.id == this.value);
        corretorSelecionado.value = casa ? casa.corretor : '';
    });

    // Formulário de agendamento
    document.getElementById('visitaForm').addEventListener('submit', function(e) {
        e.preventDefault();
        document.getElementById('visitaSucesso').style.display = 'block';
        this.reset();
        corretorSelecionado.value = '';
    });
});
