<?php
/**
 * Página de Aluguel - Requer autenticação
 */

// Incluir arquivo de inicialização do sistema
require_once __DIR__ . '/includes/init.php';

// Verificar se o usuário está logado
$auth->requireLogin('Login.php');
$current_user = $auth->getCurrentUser();

// Verificar mensagens flash
$flash = getFlashMessage();

// Buscar propriedades disponíveis para aluguel
$properties_stmt = $pdo->prepare("
    SELECT p.id, p.title, p.rent_price, p.address, p.neighborhood, p.city, p.bedrooms, p.bathrooms, p.area_sqm
    FROM properties p 
    WHERE p.status = 'active' AND (p.transaction_type = 'rent' OR p.transaction_type = 'both') AND p.rent_price IS NOT NULL
    ORDER BY p.title
");
$properties_stmt->execute();
$available_properties = $properties_stmt->fetchAll();

// Buscar corretores disponíveis
$brokers_stmt = $pdo->prepare("
    SELECT b.id, u.first_name, u.last_name, b.company, b.specialties, b.years_experience, b.rating
    FROM brokers b
    JOIN users u ON b.user_id = u.id
    WHERE u.status = 'active'
    ORDER BY u.first_name, u.last_name
");
$brokers_stmt->execute();
$available_brokers = $brokers_stmt->fetchAll();

// Processar formulário de aluguel
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_aluguel'])) {
    $errors = [];
    
    try {
        // Validar dados do formulário
        $property_id = intval($_POST['property_id'] ?? 0);
        $broker_id = intval($_POST['broker_id'] ?? 0);
        $nome = trim($_POST['nome'] ?? '');
        $cpf = trim($_POST['cpf'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telefone = trim($_POST['telefone'] ?? '');
        $rg = trim($_POST['rg'] ?? '');
        $endereco = trim($_POST['endereco'] ?? '');
        $profissao = trim($_POST['profissao'] ?? '');
        $renda = floatval($_POST['renda'] ?? 0);
        $valor_aluguel = floatval($_POST['valor_aluguel'] ?? 0);
        $periodo_meses = intval($_POST['periodo_meses'] ?? 12);
        $observacoes = trim($_POST['observacoes'] ?? '');
        
        // Validações básicas
        if ($property_id <= 0) $errors[] = "Selecione uma propriedade";
        if ($broker_id <= 0) $errors[] = "Selecione um corretor";
        if (empty($nome)) $errors[] = "Nome completo é obrigatório";
        if (empty($cpf)) $errors[] = "CPF é obrigatório";
        if (empty($email)) $errors[] = "Email é obrigatório";
        if (empty($telefone)) $errors[] = "Telefone é obrigatório";
        if (empty($endereco)) $errors[] = "Endereço é obrigatório";
        if (empty($profissao)) $errors[] = "Profissão é obrigatória";
        if ($renda <= 0) $errors[] = "Renda mensal deve ser informada";
        if ($valor_aluguel <= 0) $errors[] = "Valor do aluguel deve ser informado";
        if ($periodo_meses < 6) $errors[] = "Período mínimo de aluguel é 6 meses";
        
        // Validar email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email inválido";
        }
        
        // Validar se propriedade e corretor existem
        if ($property_id > 0) {
            $property_check = $pdo->prepare("SELECT id FROM properties WHERE id = ? AND status = 'active' AND (transaction_type = 'rent' OR transaction_type = 'both') AND rent_price IS NOT NULL");
            $property_check->execute([$property_id]);
            if (!$property_check->fetch()) {
                $errors[] = "Propriedade selecionada não está disponível para aluguel";
            }
        }
        
        if ($broker_id > 0) {
            $broker_check = $pdo->prepare("SELECT b.id FROM brokers b JOIN users u ON b.user_id = u.id WHERE b.id = ? AND u.status = 'active'");
            $broker_check->execute([$broker_id]);
            if (!$broker_check->fetch()) {
                $errors[] = "Corretor selecionado não está disponível";
            }
        }
        
        // Se não há erros, salvar no banco
        if (empty($errors)) {
            // Inserir registro de aluguel
            $stmt = $pdo->prepare("
                INSERT INTO property_rentals (
                    property_id, tenant_id, landlord_id, broker_id, monthly_rent,
                    start_date, end_date, rental_period_months, status,
                    tenant_income, tenant_profession, contract_terms, notes,
                    security_deposit
                ) VALUES (
                    ?, ?, 1, ?, ?, NOW(), DATE_ADD(NOW(), INTERVAL ? MONTH), ?, 'pending', ?, ?, ?, ?, ?
                )
            ");
            
            $contract_terms = "Processo de aluguel iniciado através do site. Documentos e detalhes a serem validados na imobiliária.";
            $notes = "Dados do locatário: Nome: $nome, CPF: $cpf, Email: $email, Telefone: $telefone, Endereço: $endereco" . 
                    ($observacoes ? ", Observações: $observacoes" : "");
            $security_deposit = $valor_aluguel * 2; // Caução de 2 meses
            
            $stmt->execute([
                $property_id, // property_id
                $current_user['id'], // tenant_id
                $broker_id, // broker_id
                $valor_aluguel, // monthly_rent
                $periodo_meses, // para calcular end_date
                $periodo_meses, // rental_period_months
                $renda, // tenant_income
                $profissao, // tenant_profession
                $contract_terms, // contract_terms
                $notes, // notes
                $security_deposit // security_deposit
            ]);
            
            // Mensagem de sucesso
            setFlashMessage(
                "<strong>Solicitação de aluguel enviada com sucesso!</strong><br>" .
                "Passe na imobiliária para assinar o contrato.<br>" .
                "<strong>Endereço:</strong> Rua das Flores, 123 - Centro<br>" .
                "<strong>Telefone:</strong> (51) 3333-4444<br>" .
                "<strong>Horário:</strong> Segunda a Sexta das 8h às 18h",
                "success"
            );
            
            // Redirecionar para evitar reenvio do formulário
            header("Location: alugar.php");
            exit;
        }
        
        if (!empty($errors)) {
            setFlashMessage(implode("<br>", $errors), "danger");
        }
        
    } catch (Exception $e) {
        error_log("Erro ao processar aluguel: " . $e->getMessage());
        setFlashMessage("Erro interno do sistema. Tente novamente mais tarde.", "danger");
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela de Aluguel - <?php echo APP_NAME ?? 'Imobiliária'; ?></title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Ícones do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <!-- Fontes do Google -->
    <link href="https://fonts.googleapis.com/css?family=Merriweather+Sans:400,700" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic" rel="stylesheet" type="text/css" />
    <!-- Plugin SimpleLightbox CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/SimpleLightbox/2.1.0/simpleLightbox.min.css" rel="stylesheet" />
    <!-- Font Awesome para ícones sociais -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <!-- Tema principal CSS (inclui Bootstrap) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
</head>
<body>
    <!-- Navigation-->
    <?php
    require_once __DIR__ . '/includes/navbar.php';
    renderNavbar($current_user, 'alugar');
    ?>

    <?php
    // Exibir barra de informações do usuário
    require_once __DIR__ . '/includes/user_info.php';
    renderUserInfo($current_user);
    ?>

    <?php if ($flash): ?>
        <div class="container-fluid mt-3">
            <div class="alert alert-<?php echo $flash['type']; ?> alert-dismissible fade show" role="alert">
                <?php echo $flash['message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    <?php endif; ?>

    <!-- Hero Section -->
    <header class="masthead">
        <div class="container px-4 px-lg-5 h-100">
            <div class="row gx-4 gx-lg-5 h-100 align-items-center justify-content-center text-center">
                <div class="col-lg-8 align-self-end">
                    <h1 class="text-white font-weight-bold">Aluguel de Imóveis</h1>
                    <hr class="divider" />
                </div>
                <div class="col-lg-8 align-self-baseline">
                    <p class="text-white-75 mb-5">Encontre o imóvel perfeito para alugar com segurança e tranquilidade. Preencha o formulário abaixo para iniciar o processo de locação.</p>
                </div>
            </div>
        </div>
    </header>

    <!-- Formulário de Aluguel -->
    <section class="page-section" id="aluguel-form">
        <div class="container px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-lg-8 col-xl-6 text-center">
                    <h2 class="mt-0">Documentos para Aluguel</h2>
                    <hr class="divider" />
                    <p class="text-muted mb-5">Para finalizar o aluguel do seu imóvel, precisamos dos seguintes documentos:</p>
                </div>
            </div>
            
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow-lg border-0">
                        <div class="card-header bg-success text-white text-center">
                            <h4 class="mb-0"><i class="fas fa-key me-2"></i>Formulário de Aluguel</h4>
                        </div>
                        <div class="card-body p-4">
                            <form id="aluguelForm" method="POST">
                                <input type="hidden" name="submit_aluguel" value="1">
                                
                                <!-- Seleção de Propriedade e Corretor -->
                                <div class="mb-4">
                                    <h5 class="text-success mb-3"><i class="fas fa-home me-2"></i>Seleção de Propriedade</h5>
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="property_id" class="form-label">Escolha a Propriedade <span class="text-danger">*</span></label>
                                            <select class="form-select form-select-lg" id="property_id" name="property_id" required onchange="updateRentPrice()">
                                                <option value="">Selecione uma propriedade...</option>
                                                <?php foreach ($available_properties as $property): ?>
                                                    <option value="<?php echo $property['id']; ?>" data-rent="<?php echo $property['rent_price']; ?>">
                                                        <?php echo htmlspecialchars($property['title']); ?> - 
                                                        R$ <?php echo number_format($property['rent_price'], 2, ',', '.'); ?>/mês - 
                                                        <?php echo $property['bedrooms']; ?>Q <?php echo $property['bathrooms']; ?>B - 
                                                        <?php echo $property['area_sqm']; ?>m² - 
                                                        <?php echo htmlspecialchars($property['neighborhood'] . ', ' . $property['city']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Seleção de Corretor -->
                                <div class="mb-4">
                                    <h5 class="text-success mb-3"><i class="fas fa-user-tie me-2"></i>Seleção de Corretor</h5>
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="broker_id" class="form-label">Escolha o Corretor <span class="text-danger">*</span></label>
                                            <select class="form-select form-select-lg" id="broker_id" name="broker_id" required>
                                                <option value="">Selecione um corretor...</option>
                                                <?php foreach ($available_brokers as $broker): ?>
                                                    <option value="<?php echo $broker['id']; ?>">
                                                        <?php echo htmlspecialchars($broker['first_name'] . ' ' . $broker['last_name']); ?>
                                                        <?php if ($broker['company']): ?>
                                                            - <?php echo htmlspecialchars($broker['company']); ?>
                                                        <?php endif; ?>
                                                        (<?php echo $broker['years_experience']; ?> anos de experiência)
                                                        <?php if ($broker['rating'] > 0): ?>
                                                            - ⭐ <?php echo number_format($broker['rating'], 1); ?>
                                                        <?php endif; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Dados Pessoais -->
                                <div class="mb-4">
                                    <h5 class="text-success mb-3"><i class="fas fa-user me-2"></i>Dados Pessoais</h5>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="nome" class="form-label">Nome Completo <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-lg" id="nome" name="nome" 
                                                   value="<?php echo htmlspecialchars($current_user['first_name'] . ' ' . $current_user['last_name']); ?>" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="cpf" class="form-label">CPF <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-lg" id="cpf" name="cpf" 
                                                   value="<?php echo htmlspecialchars($current_user['cpf'] ?? ''); ?>" 
                                                   placeholder="000.000.000-00" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control form-control-lg" id="email" name="email" 
                                                   value="<?php echo htmlspecialchars($current_user['email']); ?>" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="telefone" class="form-label">Telefone <span class="text-danger">*</span></label>
                                            <input type="tel" class="form-control form-control-lg" id="telefone" name="telefone" 
                                                   value="<?php echo htmlspecialchars($current_user['phone'] ?? ''); ?>" 
                                                   placeholder="(00) 00000-0000" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Documentos e Informações Adicionais -->
                                <div class="mb-4">
                                    <h5 class="text-success mb-3"><i class="fas fa-file-alt me-2"></i>Informações Adicionais</h5>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="rg" class="form-label">RG</label>
                                            <input type="text" class="form-control form-control-lg" id="rg" name="rg" placeholder="Número do RG">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="profissao" class="form-label">Profissão <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-lg" id="profissao" name="profissao" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="endereco" class="form-label">Endereço Completo <span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="endereco" name="endereco" rows="3" 
                                                  placeholder="Rua, número, bairro, cidade, CEP..." required><?php echo htmlspecialchars($current_user['address'] ?? ''); ?></textarea>
                                    </div>
                                </div>

                                <!-- Informações de Locação -->
                                <div class="mb-4">
                                    <h5 class="text-success mb-3"><i class="fas fa-dollar-sign me-2"></i>Informações de Locação</h5>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="renda" class="form-label">Renda Mensal Bruta <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control form-control-lg" id="renda" name="renda" 
                                                   step="0.01" min="0" placeholder="3000.00" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="valor_aluguel" class="form-label">Valor de Aluguel Desejado <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control form-control-lg" id="valor_aluguel" name="valor_aluguel" 
                                                   step="0.01" min="0" placeholder="1500.00" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="periodo_meses" class="form-label">Período de Locação (meses) <span class="text-danger">*</span></label>
                                            <select class="form-select form-select-lg" id="periodo_meses" name="periodo_meses" required>
                                                <option value="">Selecione...</option>
                                                <option value="6">6 meses</option>
                                                <option value="12" selected>12 meses</option>
                                                <option value="18">18 meses</option>
                                                <option value="24">24 meses</option>
                                                <option value="36">36 meses</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Observações -->
                                <div class="mb-4">
                                    <label for="observacoes" class="form-label">Observações Adicionais</label>
                                    <textarea class="form-control" id="observacoes" name="observacoes" rows="3" 
                                              placeholder="Descreva suas preferências, necessidades especiais ou outras informações relevantes..."></textarea>
                                </div>

                                <!-- Lista de Documentos Required -->
                                <div class="mb-4">
                                    <h5 class="text-info mb-3"><i class="fas fa-clipboard-list me-2"></i>Documentos Necessários</h5>
                                    <div class="alert alert-info">
                                        <p><strong>Você precisará apresentar os seguintes documentos na imobiliária:</strong></p>
                                        <ul class="mb-0">
                                            <li>RG e CPF (originais e cópias)</li>
                                            <li>Comprovante de renda (3 últimos holerites)</li>
                                            <li>Comprovante de residência atualizado</li>
                                            <li>Certidão de estado civil</li>
                                            <li>Certidão negativa do SPC/SERASA</li>
                                            <li>Referências pessoais e comerciais</li>
                                            <li>Documentos do fiador (se necessário)</li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Botões -->
                                <div class="d-flex justify-content-center align-items-center gap-3 mt-4">
                                    <button type="submit" class="btn btn-success btn-lg px-5">
                                        <i class="fas fa-key me-2"></i>Solicitar Aluguel
                                    </button>
                                    <button type="reset" class="btn btn-outline-secondary btn-lg px-5">
                                        <i class="fas fa-redo me-2"></i>Limpar Formulário
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

     <footer class="bg-light py-5">
            <div class="container px-4 px-lg-5"><div class="small text-center text-muted">Copyright &copy; 2025 - Company Miguel</div></div>
        </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/SimpleLightbox/2.1.0/simpleLightbox.min.js"></script>
    <script src="js/scripts.js"></script>
    
    <script>
        // Função para atualizar o preço do aluguel automaticamente
        function updateRentPrice() {
            const propertySelect = document.getElementById('property_id');
            const rentInput = document.getElementById('valor_aluguel');
            
            if (propertySelect.value) {
                const selectedOption = propertySelect.options[propertySelect.selectedIndex];
                const rent = selectedOption.getAttribute('data-rent');
                if (rent) {
                    rentInput.value = parseFloat(rent).toFixed(2);
                }
            } else {
                rentInput.value = '';
            }
        }

        // Verificar se há mensagem de sucesso e limpar formulário
        document.addEventListener('DOMContentLoaded', function() {
            const alert = document.querySelector('.alert-success');
            if (alert) {
                // Limpar o formulário após sucesso
                const form = document.getElementById('aluguelForm');
                if (form) {
                    // Resetar campos que não são pré-preenchidos
                    const fieldsToReset = ['property_id', 'broker_id', 'rg', 'profissao', 'renda', 'valor_aluguel', 'observacoes'];
                    fieldsToReset.forEach(fieldName => {
                        const field = document.getElementById(fieldName);
                        if (field) {
                            field.value = '';
                        }
                    });
                    
                    // Resetar select
                    const selectField = document.getElementById('periodo_meses');
                    if (selectField) {
                        selectField.value = '';
                    }
                }
            }
        });
    </script>
</body>
</html>
