<?php
/**
 * Página de Agendamento de Visitas - Requer autenticação
 */

// Incluir arquivo de inicialização do sistema
require_once __DIR__ . '/includes/init.php';

// Verificar se o usuário está logado
$auth->requireLogin('Login.php');
$current_user = $auth->getCurrentUser();

// Verificar mensagens flash
$flash = getFlashMessage();

// Processar formulário de agendamento
$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agendar_visita'])) {
    // Validar CSRF token
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $error_message = 'Token de segurança inválido.';
    } else {
        // Verificar se o usuário logado tem email gmail.com
        if (!str_ends_with($current_user['email'], '@gmail.com')) {
            $error_message = 'Apenas usuários com email Gmail podem fazer agendamentos.';
        } else {
            // Capturar e sanitizar dados
            $nome = trim($_POST['clienteNome'] ?? '');
            $email = trim($_POST['clienteEmail'] ?? '');
            $telefone = trim($_POST['clienteTelefone'] ?? '');
            $casa = trim($_POST['casaSelect'] ?? '');
            $corretor = trim($_POST['corretorSelect'] ?? '');
            $broker_id = intval($_POST['broker_id'] ?? 0);
            $data = trim($_POST['dataVisita'] ?? '');
            $horario = trim($_POST['horarioVisita'] ?? '');
            $observacoes = trim($_POST['observacoes'] ?? '');
            
            // Validações
            $errors = [];
        
        if (empty($nome)) {
            $errors[] = 'Nome é obrigatório.';
        }
        
        if (empty($email) || !validateEmail($email)) {
            $errors[] = 'Email válido é obrigatório.';
        }
        
        if (empty($telefone) || !validatePhone($telefone)) {
            $errors[] = 'Telefone válido é obrigatório.';
        }
        
        if (empty($casa)) {
            $errors[] = 'Selecione uma casa.';
        }
        
        if (empty($corretor)) {
            $errors[] = 'Selecione um corretor.';
        } else {
            $corretores_validos = ['João borges', 'Maria Santos', 'Pedro Costa'];
            if (!in_array($corretor, $corretores_validos)) {
                $errors[] = 'Corretor inválido selecionado.';
            }
        }
        
        if (empty($data)) {
            $errors[] = 'Data da visita é obrigatória.';
        } else {
            $data_visita = DateTime::createFromFormat('Y-m-d', $data);
            $hoje = new DateTime();
            if (!$data_visita || $data_visita < $hoje) {
                $errors[] = 'Data da visita deve ser futura.';
            } else {
                // Verificar se é domingo (0 = domingo)
                $diaSemana = (int)$data_visita->format('w');
                if ($diaSemana === 0) {
                    $errors[] = 'Visitas não são realizadas aos domingos.';
                }
                
                // Verificar se é sábado e horário após 11:00
                if ($diaSemana === 6 && !empty($horario)) {
                    $horariosPermitidosSabado = ['07:00', '08:00', '09:00', '10:00', '11:00'];
                    if (!in_array($horario, $horariosPermitidosSabado)) {
                        $errors[] = 'Aos sábados, as visitas são realizadas apenas até 11:00.';
                    }
                }
            }
        }
        
        if (empty($horario)) {
            $errors[] = 'Horário da visita é obrigatório.';
        } else {
            // Validar horários disponíveis
            $horariosDisponiveis = ['07:00', '08:00', '09:00', '10:00', '11:00', '14:00', '15:00', '16:00', '17:00'];
            if (!in_array($horario, $horariosDisponiveis)) {
                $errors[] = 'Horário inválido selecionado.';
            }
        }
        
        if (!empty($errors)) {
            $error_message = implode('<br>', $errors);
        } else {
            // Preparar dados para salvamento
            $agendamento_id = uniqid();
            try {
                // Verificar se o ID já existe no banco (evitar duplicação)
                $check_stmt = $pdo->prepare("SELECT id FROM agendamentos WHERE id = ?");
                $check_stmt->execute([$agendamento_id]);
                if ($check_stmt->fetch()) {
                    $agendamento_id = uniqid() . '_' . time();
                }
                // Salvar no banco de dados (agora inclui broker_id)
                try {
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $stmt = $pdo->prepare("
                        INSERT INTO agendamentos (id, nome, email, telefone, casa, corretor, data_visita, horario, observacoes, data_agendamento, status)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                    ");
                    $result = $stmt->execute([
                        $agendamento_id,
                        $nome,
                        $email,
                        $telefone,
                        $casa,
                        $corretor,
                        $data,
                        $horario,
                        $observacoes,
                        date('Y-m-d H:i:s'),
                        'agendado'
                    ]);
                    if ($result) {
                        setFlashMessage('Visita agendada com sucesso! Entraremos em contato para confirmar.', 'success');
                        header('Location: index.php');
                        exit;
                    } else {
                        $error_message = 'Erro ao agendar visita. Tente novamente.';
                    }
                } catch (PDOException $e) {
                    $error_message = 'Erro ao salvar agendamento no banco: ' . $e->getMessage();
                }
            } catch (PDOException $e) {
                error_log("Erro ao salvar agendamento no banco: " . $e->getMessage());
                setFlashMessage('Erro ao salvar agendamento no banco: ' . $e->getMessage(), 'danger');
                // Sistema de fallback: salvar no arquivo JSON se o banco falhar
                $agendamento = [
                    'id' => $agendamento_id,
                    'nome' => htmlspecialchars($nome),
                    'email' => htmlspecialchars($email),
                    'telefone' => htmlspecialchars($telefone),
                    'casa' => htmlspecialchars($casa),
                    'corretor' => htmlspecialchars($corretor),
                    'broker_id' => $broker_id,
                    'data_visita' => $data,
                    'horario' => htmlspecialchars($horario),
                    'observacoes' => htmlspecialchars($observacoes),
                    'data_agendamento' => date('Y-m-d H:i:s'),
                    'status' => 'agendado'
                ];
                $arquivo_agendamentos = __DIR__ . '/data/agendamentos.json';
                if (!is_dir(dirname($arquivo_agendamentos))) {
                    mkdir(dirname($arquivo_agendamentos), 0755, true);
                }
                $agendamentos = [];
                if (file_exists($arquivo_agendamentos)) {
                    $json = file_get_contents($arquivo_agendamentos);
                    $agendamentos = json_decode($json, true) ?: [];
                }
                $existe = false;
                foreach ($agendamentos as $ag) {
                    if ($ag['id'] === $agendamento_id) {
                        $existe = true;
                        break;
                    }
                }
                if (!$existe) {
                    $agendamentos[] = $agendamento;
                    if (file_put_contents($arquivo_agendamentos, json_encode($agendamentos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
                        setFlashMessage('Vá até a imobiliária para ver detalhes da visita.', 'warning');
                        header('Location: index.php');
                        exit;
                    } else {
                        $error_message = 'Erro ao agendar visita. Tente novamente.';
                    }
                } else {
                    $error_message = 'Este agendamento já foi registrado. Tente novamente.';
                }
            }
        }
        } // Fechamento do else para verificação do gmail
    }
}

// Gerar token CSRF
$csrf_token = generateCSRFToken();

// Lista de casas disponíveis
$casas_disponiveis = [
    'Casa em Santo Antônio da Patrulha',
    'Casa em Taquara',
    'Casa Em Taquara Alto Padrão',
    'Casa em Taquara Rua Mundo Novo',
    'Casa em Taquara Flores da Cunha',
    'Casa em Parobé',
    'Casa em Taquara Santa Terezinha',
    'Casa em Taquara rua Alvarino Lacerda Filho',
    'Casa em Taquara - São Francisco'
];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar Visita - <?php echo APP_NAME; ?></title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <?php
    require_once __DIR__ . '/includes/navbar.php';
    renderNavbar($current_user, 'agendar_visita');
    ?>

    <?php 
    require_once __DIR__ . '/includes/user_info.php';
    renderUserInfo($current_user); 
    ?>

    <!-- Main Content -->
    <div class="container my-5" style="padding-top: 40px;">
        
        <!-- Verificação do email Gmail -->
        <?php if (!str_ends_with($current_user['email'], '@gmail.com')): ?>
            <div class="alert alert-warning alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>Atenção!</strong> Apenas usuários com email Gmail (@gmail.com) podem fazer agendamentos. 
                Seu email atual: <strong><?php echo htmlspecialchars($current_user['email']); ?></strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <!-- Mensagens Flash -->
        <?php if ($flash): ?>
            <div class="alert alert-<?php echo $flash['type'] === 'success' ? 'success' : ($flash['type'] === 'error' ? 'danger' : 'info'); ?> alert-dismissible fade show">
                <?php echo $flash['message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?php echo $success_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?php echo $error_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header">
                        <h2 class="mb-0 text-center">
                            <i class="bi bi-calendar-check me-2"></i>
                            Agendar Visita a um Imóvel
                        </h2>
                    </div>
                    <div class="card-body">
                        <?php if (!str_ends_with($current_user['email'], '@gmail.com')): ?>
                            <div class="alert alert-danger">
                                <i class="bi bi-x-circle-fill me-2"></i>
                                <strong>Formulário Bloqueado!</strong> Este formulário está disponível apenas para usuários com email Gmail (@gmail.com).
                            </div>
                            <div class="text-center">
                                <p class="text-muted">Para fazer agendamentos, você precisa:</p>
                                <ol class="text-muted text-start" style="max-width: 400px; margin: 0 auto;">
                                    <li>Criar uma conta com email Gmail</li>
                                    <li>Ou alterar seu email atual para um Gmail</li>
                                </ol>
                            </div>
                        <?php else: ?>
                        <form method="POST" action="">
                            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                            <input type="hidden" name="agendar_visita" value="1">
                            <input type="hidden" id="broker_id" name="broker_id" value="">
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="clienteNome" name="clienteNome" 
                                               placeholder="Seu nome" required value="<?php echo htmlspecialchars($nome ?? $current_user['first_name'] . ' ' . $current_user['last_name']); ?>">
                                        <label for="clienteNome">Nome Completo</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="email" class="form-control" id="clienteEmail" name="clienteEmail" 
                                               placeholder="seu@email.com" required value="<?php echo htmlspecialchars($email ?? $current_user['email']); ?>">
                                        <label for="clienteEmail">Email</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="tel" class="form-control" id="clienteTelefone" name="clienteTelefone" 
                                               placeholder="(11) 99999-9999" required value="<?php echo htmlspecialchars($telefone ?? $current_user['phone'] ?? ''); ?>">
                                        <label for="clienteTelefone">Telefone</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="casaSelect" name="casaSelect" required>
                                            <option value="" disabled <?php echo empty($casa) ? 'selected' : ''; ?>>Selecione uma casa</option>
                                            <?php foreach ($casas_disponiveis as $casa_opcao): ?>
                                                <option value="<?php echo htmlspecialchars($casa_opcao); ?>" 
                                                        <?php echo (isset($casa) && $casa === $casa_opcao) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($casa_opcao); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <label for="casaSelect">Imóvel de Interesse</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="corretorSelect" name="corretorDisplay" required readonly disabled>
                                            <option value="" disabled <?php echo empty($corretor ?? '') ? 'selected' : ''; ?>>Selecione um corretor</option>
                                            <option value="João borges" <?php echo (isset($corretor) && $corretor === 'João borges') ? 'selected' : ''; ?>>João borges</option>
                                            <option value="Maria Santos" <?php echo (isset($corretor) && $corretor === 'Maria Santos') ? 'selected' : ''; ?>>Maria Santos</option>
                                            <option value="Pedro Costa" <?php echo (isset($corretor) && $corretor === 'Pedro Costa') ? 'selected' : ''; ?>>Pedro Costa</option>
                                        </select>
                                        <label for="corretorSelect">Corretor Designado (Automático)</label>
                                        <!-- Campo hidden para garantir que o valor seja enviado -->
                                        <input type="hidden" id="corretorHidden" name="corretorSelect" value="<?php echo htmlspecialchars($corretor ?? ''); ?>">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="date" class="form-control" id="dataVisita" name="dataVisita" 
                                               required min="<?php echo date('Y-m-d'); ?>" value="<?php echo htmlspecialchars($data ?? ''); ?>">
                                        <label for="dataVisita">Data da Visita</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="horarioVisita" name="horarioVisita" required>
                                            <option value="" disabled <?php echo empty($horario) ? 'selected' : ''; ?>>Selecione o horário</option>
                                            <option value="07:00" <?php echo (isset($horario) && $horario === '07:00') ? 'selected' : ''; ?>>07:00</option>
                                            <option value="08:00" <?php echo (isset($horario) && $horario === '08:00') ? 'selected' : ''; ?>>08:00</option>
                                            <option value="09:00" <?php echo (isset($horario) && $horario === '09:00') ? 'selected' : ''; ?>>09:00</option>
                                            <option value="10:00" <?php echo (isset($horario) && $horario === '10:00') ? 'selected' : ''; ?>>10:00</option>
                                            <option value="11:00" <?php echo (isset($horario) && $horario === '11:00') ? 'selected' : ''; ?>>11:00</option>
                                            <option value="14:00" <?php echo (isset($horario) && $horario === '14:00') ? 'selected' : ''; ?>>14:00</option>
                                            <option value="15:00" <?php echo (isset($horario) && $horario === '15:00') ? 'selected' : ''; ?>>15:00</option>
                                            <option value="16:00" <?php echo (isset($horario) && $horario === '16:00') ? 'selected' : ''; ?>>16:00</option>
                                            <option value="17:00" <?php echo (isset($horario) && $horario === '17:00') ? 'selected' : ''; ?>>17:00</option>
                                        </select>
                                        <label for="horarioVisita">Horário</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-floating mb-4">
                                <textarea class="form-control" id="observacoes" name="observacoes" 
                                          placeholder="Observações adicionais..." style="height: 100px"><?php echo htmlspecialchars($observacoes ?? ''); ?></textarea>
                                <label for="observacoes">Observações (Opcional)</label>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-calendar-plus me-2"></i>
                                    Agendar Visita
                                </button>
                                <a href="index.php" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-2"></i>
                                    Voltar ao Início
                                </a>
                            </div>
                        </form>
                        <?php endif; ?> <!-- Fim da verificação do email Gmail -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-light py-4 mt-5">
        <div class="container px-4 px-lg-5">
            <div class="small text-center text-muted">
                Copyright &copy; 2025 - Company Miguel
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Configurar data mínima para hoje
        document.getElementById('dataVisita').min = new Date().toISOString().split('T')[0];
        
        // Mapeamento casa -> corretor
        // Casa 1-3 = João borges, Casa 4-6 = Maria Santos, Casa 7-9 = Pedro Costa
        const casaCorretorMap = {
            'Casa em Santo Antônio da Patrulha': 'João borges',        // Casa 1
            'Casa em Taquara': 'João borges',                          // Casa 2  
            'Casa Em Taquara Alto Padrão': 'João borges',              // Casa 3
            'Casa em Taquara Rua Mundo Novo': 'Maria Santos',          // Casa 4
            'Casa em Taquara Flores da Cunha': 'Maria Santos',         // Casa 5
            'Casa em Parobé': 'Maria Santos',                          // Casa 6
            'Casa em Taquara Santa Terezinha': 'Pedro Costa',          // Casa 7
            'Casa em Taquara rua Alvarino Lacerda Filho': 'Pedro Costa', // Casa 8
            'Casa em Taquara - São Francisco': 'Pedro Costa'           // Casa 9
        };
        
        // Função para atualizar corretor baseado na casa selecionada
        document.getElementById('casaSelect').addEventListener('change', function(e) {
            const casaSelecionada = e.target.value;
            const corretorSelect = document.getElementById('corretorSelect');
            const corretorHidden = document.getElementById('corretorHidden');
            const brokerIdInput = document.getElementById('broker_id');
            let corretorDesignado = '';
            let brokerId = '';
            if (casaSelecionada && casaCorretorMap[casaSelecionada]) {
                corretorDesignado = casaCorretorMap[casaSelecionada];
                corretorSelect.value = corretorDesignado;
                corretorHidden.value = corretorDesignado;
                // Definir broker_id conforme corretor
                if (corretorDesignado === 'João borges') brokerId = 1;
                else if (corretorDesignado === 'Maria Santos') brokerId = 2;
                else if (corretorDesignado === 'Pedro Costa') brokerId = 3;
                brokerIdInput.value = brokerId;
                console.log(`Casa selecionada: ${casaSelecionada} -> Corretor: ${corretorDesignado} (ID: ${brokerId})`);
            } else {
                corretorSelect.value = '';
                corretorHidden.value = '';
                brokerIdInput.value = '';
            }
        });
        
        // Função para verificar se a data é domingo
        function isDomingo(dateString) {
            const date = new Date(dateString + 'T00:00:00');
            return date.getDay() === 0; // 0 = domingo
        }
        
        // Função para verificar se a data é sábado
        function isSabado(dateString) {
            const date = new Date(dateString + 'T00:00:00');
            return date.getDay() === 6; // 6 = sábado
        }
        
        // Validação da data - bloquear domingo
        document.getElementById('dataVisita').addEventListener('change', function(e) {
            const selectedDate = e.target.value;
            
            if (isDomingo(selectedDate)) {
                alert('Desculpe, não realizamos visitas aos domingos. Por favor, escolha outro dia.');
                e.target.value = '';
                return;
            }
            
            // Atualizar horários conforme o dia selecionado
            updateHorarios(selectedDate);
        });
        
        // Função para atualizar os horários disponíveis
        function updateHorarios(dateString) {
            const horarioSelect = document.getElementById('horarioVisita');
            const currentValue = horarioSelect.value;
            
            // Limpar opções atuais (exceto a primeira)
            while (horarioSelect.children.length > 1) {
                horarioSelect.removeChild(horarioSelect.lastChild);
            }
            
            let horarios = [];
            
            if (isSabado(dateString)) {
                // Sábado: apenas até 11:00
                horarios = [
                    { value: '07:00', text: '07:00' },
                    { value: '08:00', text: '08:00' },
                    { value: '09:00', text: '09:00' },
                    { value: '10:00', text: '10:00' },
                    { value: '11:00', text: '11:00' }
                ];
            } else {
                // Outros dias: horários normais
                horarios = [
                    { value: '07:00', text: '07:00' },
                    { value: '08:00', text: '08:00' },
                    { value: '09:00', text: '09:00' },
                    { value: '10:00', text: '10:00' },
                    { value: '11:00', text: '11:00' },
                    { value: '14:00', text: '14:00' },
                    { value: '15:00', text: '15:00' },
                    { value: '16:00', text: '16:00' },
                    { value: '17:00', text: '17:00' }
                ];
            }
            
            // Adicionar as opções de horário
            horarios.forEach(function(horario) {
                const option = document.createElement('option');
                option.value = horario.value;
                option.textContent = horario.text;
                if (currentValue === horario.value) {
                    option.selected = true;
                }
                horarioSelect.appendChild(option);
            });
            
            // Se era sábado e o horário selecionado era após 11:00, limpar seleção
            if (isSabado(dateString) && currentValue && 
                ['14:00', '15:00', '16:00', '17:00'].includes(currentValue)) {
                horarioSelect.value = '';
            }
        }
        
        // Validação adicional no frontend
        document.getElementById('clienteTelefone').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 10) {
                if (value.length === 11) {
                    value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
                } else {
                    value = value.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
                }
                e.target.value = value;
            }
        });
        
        // Validação final no submit
        document.querySelector('form').addEventListener('submit', function(e) {
            const selectedDate = document.getElementById('dataVisita').value;
            
            if (isDomingo(selectedDate)) {
                e.preventDefault();
                alert('Não é possível agendar visitas aos domingos.');
                return false;
            }
            
            if (isSabado(selectedDate)) {
                const selectedTime = document.getElementById('horarioVisita').value;
                if (['14:00', '15:00', '16:00', '17:00'].includes(selectedTime)) {
                    e.preventDefault();
                    alert('Aos sábados, as visitas são realizadas apenas até 11:00.');
                    return false;
                }
            }
        });
    </script>
</body>
</html>
