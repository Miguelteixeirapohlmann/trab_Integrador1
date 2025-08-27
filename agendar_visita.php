<?php
/**
 * Página de Agendamento de Visitas
 */

// Incluir arquivo de inicialização do sistema
require_once __DIR__ . '/includes/init.php';

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
        // Capturar e sanitizar dados
        $nome = trim($_POST['clienteNome'] ?? '');
        $email = trim($_POST['clienteEmail'] ?? '');
        $telefone = trim($_POST['clienteTelefone'] ?? '');
        $casa = trim($_POST['casaSelect'] ?? '');
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
        
        if (empty($data)) {
            $errors[] = 'Data da visita é obrigatória.';
        } else {
            $data_visita = DateTime::createFromFormat('Y-m-d', $data);
            $hoje = new DateTime();
            if (!$data_visita || $data_visita < $hoje) {
                $errors[] = 'Data da visita deve ser futura.';
            }
        }
        
        if (empty($horario)) {
            $errors[] = 'Horário da visita é obrigatório.';
        }
        
        if (!empty($errors)) {
            $error_message = implode('<br>', $errors);
        } else {
            // Preparar dados para salvamento
            $agendamento = [
                'id' => uniqid(),
                'nome' => htmlspecialchars($nome),
                'email' => htmlspecialchars($email),
                'telefone' => htmlspecialchars($telefone),
                'casa' => htmlspecialchars($casa),
                'data_visita' => $data,
                'horario' => htmlspecialchars($horario),
                'observacoes' => htmlspecialchars($observacoes),
                'data_agendamento' => date('Y-m-d H:i:s'),
                'status' => 'agendado'
            ];
            
            // Salvar no arquivo JSON (sistema de fallback)
            $arquivo_agendamentos = __DIR__ . '/data/agendamentos.json';
            
            // Criar diretório se não existir
            if (!is_dir(dirname($arquivo_agendamentos))) {
                mkdir(dirname($arquivo_agendamentos), 0755, true);
            }
            
            // Carregar agendamentos existentes
            $agendamentos = [];
            if (file_exists($arquivo_agendamentos)) {
                $json = file_get_contents($arquivo_agendamentos);
                $agendamentos = json_decode($json, true) ?: [];
            }
            
            // Adicionar novo agendamento
            $agendamentos[] = $agendamento;
            
            // Salvar
            if (file_put_contents($arquivo_agendamentos, json_encode($agendamentos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
                $success_message = 'Visita agendada com sucesso! Entraremos em contato para confirmar.';
                
                // Limpar campos após sucesso
                $nome = $email = $telefone = $casa = $data = $horario = $observacoes = '';
            } else {
                $error_message = 'Erro ao agendar visita. Tente novamente.';
            }
        }
    }
}

// Gerar token CSRF
$csrf_token = generateCSRFToken();

// Lista de casas disponíveis
$casas_disponiveis = [
    'Casa Realengo',
    'Casa Alto Rolantinho',
    'Casa Alpha Ville',
    'Casa Jardim das Flores',
    'Casa Vista Alegre',
    'Casa Solar dos Pássaros',
    'Casa Bela Vista',
    'Casa do Gabriel',
    'Casa Nova Esperança'
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
    <nav class="navbar navbar-expand-lg navbar-light fixed-top py-3" id="mainNav" style="background-color: white;">
        <div class="container px-4 px-lg-5">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-house-door me-2"></i>Real Estate
            </a>
            <button class="navbar-toggler navbar-toggler-right" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ms-auto my-2 my-lg-0">
                    <li class="nav-item"><a class="nav-link" href="index.php">Início</a></li>
                    <li class="nav-item"><a class="nav-link" href="casas_disponiveis.php">Imóveis</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#contact">Contato</a></li>
                    <?php if ($auth->isLoggedIn()): ?>
                        <li class="nav-item"><a class="nav-link" href="logout.php">Sair</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="Login.php">Login</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container my-5" style="padding-top: 100px;">
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
                        <form method="POST" action="">
                            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                            <input type="hidden" name="agendar_visita" value="1">
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="clienteNome" name="clienteNome" 
                                               placeholder="Seu nome" required value="<?php echo htmlspecialchars($nome ?? ''); ?>">
                                        <label for="clienteNome">Nome Completo</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="email" class="form-control" id="clienteEmail" name="clienteEmail" 
                                               placeholder="seu@email.com" required value="<?php echo htmlspecialchars($email ?? ''); ?>">
                                        <label for="clienteEmail">Email</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="tel" class="form-control" id="clienteTelefone" name="clienteTelefone" 
                                               placeholder="(11) 99999-9999" required value="<?php echo htmlspecialchars($telefone ?? ''); ?>">
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
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-light py-4 mt-5">
        <div class="container px-4 px-lg-5">
            <div class="small text-center text-muted">
                Copyright &copy; 2025 - <?php echo APP_NAME; ?>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Configurar data mínima para hoje
        document.getElementById('dataVisita').min = new Date().toISOString().split('T')[0];
        
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
    </script>
</body>
</html>
