<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar Visita</title>
    <link href="css/styles.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <div class="container">
            <a class="navbar-brand" href="index.php">Início</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php#Alugar">Alugar</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#comprar">Compra</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#services">Descobrir</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#Final">Ajuda</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container my-5">
        <h2 class="mb-4">Agendar Visita a um Imóvel</h2>
        <form id="visitaForm" class="card p-4">
            <div class="mb-3">
                <label for="clienteNome" class="form-label">Seu nome</label>
                <input type="text" class="form-control" id="clienteNome" required>
            </div>
            <div class="mb-3">
                <label for="clienteEmail" class="form-label">Seu email</label>
                <input type="email" class="form-control" id="clienteEmail" required>
            </div>
            <div class="mb-3">
                <label for="casaSelect" class="form-label">Escolha a casa</label>
                <select class="form-select" id="casaSelect" name="casa" required>
                    <option value="" disabled selected>Selecione uma casa</option>
                    <option>Casa Realengo</option>
                    <option>Casa Alto Rolantinho</option>
                    <option>Casa Alpha Ville</option>
                    <option>Casa Jardim das Flores</option>
                    <option>Casa Vista Alegre</option>
                    <option>Casa Solar dos Pássaros</option>
                    <option>Casa Bela Vista</option>
                    <option>Casa do Gabriel</option>
                    <option>Casa Nova Esperança</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="corretorSelecionado" class="form-label">Corretor responsável</label>
                <select class="form-select" id="corretorSelecionado" name="corretor" required>
                    <option value="" disabled selected>Selecione o corretor</option>
                    <option>Marcos</option>
                    <option>Maria</option>
                    <option>João</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="dataVisita" class="form-label">Data da visita</label>
                <input type="date" class="form-control" id="dataVisita" name="data" required>
            </div>
            <div class="mb-3">
                <label for="horaVisita" class="form-label">Horário</label>
                <select class="form-select" id="horaVisita" name="hora" required>
                    <option value="" disabled selected>Selecione o horário</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Agendar Visita</button>
        </form>
        <div id="visitaSucesso" class="alert alert-success mt-4" style="display:none;">Visita agendada com sucesso!</div>
    </div>
    <script>
    // Preencher horários: 7:30 até 11:30, depois 13:30 até 18:30, de 30 em 30 minutos
    const horaVisita = document.getElementById('horaVisita');
    function addHorario(h, m) {
        let hora = h.toString().padStart(2, '0') + ':' + m.toString().padStart(2, '0');
        const opt = document.createElement('option');
        opt.value = hora;
        opt.textContent = hora;
        horaVisita.appendChild(opt);
    }
function preencherHorarios(tipo) {
    horaVisita.innerHTML = '<option value="" disabled selected>Selecione o horário</option>';
    if (tipo === 'manha') {
        for (let h = 7; h <= 11; h++) {
            if (h === 7) {
                addHorario(7, 30);
            } else {
                addHorario(h, 0);
                addHorario(h, 30);
            }
        }
    } else {
        for (let h = 7; h <= 11; h++) {
            if (h === 7) {
                addHorario(7, 30);
            } else {
                addHorario(h, 0);
                addHorario(h, 30);
            }
        }
        for (let h = 13; h <= 18; h++) {
            if (h === 13) {
                addHorario(13, 30);
            } else {
                addHorario(h, 0);
                addHorario(h, 30);
            }
        }
    }
}
// Inicial: completo (apenas dias úteis)
preencherHorarios('completo');

    // Impedir seleção de domingo e datas passadas, sábado só manhã
    const dataInput = document.getElementById('dataVisita');
    // Define o mínimo para hoje
    const hoje = new Date();
    const yyyy = hoje.getFullYear();
    const mm = String(hoje.getMonth() + 1).padStart(2, '0');
    const dd = String(hoje.getDate()).padStart(2, '0');
    dataInput.setAttribute('min', `${yyyy}-${mm}-${dd}`);

    dataInput.addEventListener('input', function() {
        // Corrigir fuso horário para evitar erro de dia da semana
        const partes = this.value.split('-');
        // yyyy-mm-dd
        const data = new Date(Number(partes[0]), Number(partes[1]) - 1, Number(partes[2]), 12, 0, 0);
        const hojeDate = new Date(yyyy + '-' + mm + '-' + dd);
        const diaSemana = data.getDay();
        if (diaSemana === 0) {
            alert('Domingo não é permitido.');
            this.value = '';
            preencherHorarios('completo');
        } else if (data < hojeDate) {
            alert('Não é possível agendar para datas passadas.');
            this.value = '';
            preencherHorarios('completo');
        } else if (diaSemana === 6) {
            preencherHorarios('manha');
        } else {
            preencherHorarios('completo');
        }
    });

    // Salvar agendamento em JSON e mostrar mensagem
    document.getElementById('visitaForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const nome = document.getElementById('clienteNome').value;
        const email = document.getElementById('clienteEmail').value;
        const casa = document.getElementById('casaSelect').value;
        const corretor = document.getElementById('corretorSelecionado').value;
        const data = document.getElementById('dataVisita').value;
        const hora = document.getElementById('horaVisita').value;
        // Salvar via fetch para PHP
        fetch('salvar_agendamento.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({nome, email, casa, corretor, data, hora})
        });
        document.getElementById('visitaSucesso').style.display = 'block';
        setTimeout(function() {
            document.getElementById('visitaSucesso').style.display = 'none';
            document.getElementById('visitaForm').reset();
        }, 3000);
    });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
