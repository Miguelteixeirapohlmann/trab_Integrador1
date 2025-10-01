<?php
require_once __DIR__ . '/../../includes/init.php';

// Redirecionar se já estiver logado
if ($auth->isLoggedIn()) {
    redirect('/index.php');
}

$error_message = '';
$success_message = '';

// Processar registro
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'register') {
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $error_message = 'Token de segurança inválido.';
    } else {
        $data = [
            'first_name' => sanitize($_POST['first_name'] ?? ''),
            'last_name' => sanitize($_POST['last_name'] ?? ''),
            'email' => sanitize($_POST['email'] ?? ''),
            'cpf' => sanitize($_POST['cpf'] ?? ''),
            'phone' => sanitize($_POST['phone'] ?? ''),
            'password' => $_POST['password'] ?? '',
            'confirm_password' => $_POST['confirm_password'] ?? '',
            'address' => sanitize($_POST['address'] ?? ''),
            'city' => sanitize($_POST['city'] ?? ''),
            'state' => sanitize($_POST['state'] ?? ''),
            'zip_code' => sanitize($_POST['zip_code'] ?? ''),
            'user_type' => sanitize($_POST['user_type'] ?? 'user')
        ];
        
        $terms = isset($_POST['terms_accepted']);
        
        // Validações
        $errors = [];
        
        if (empty($data['first_name'])) $errors[] = 'Nome é obrigatório.';
        if (empty($data['last_name'])) $errors[] = 'Sobrenome é obrigatório.';
        if (empty($data['email']) || !validateEmail($data['email'])) $errors[] = 'Email válido é obrigatório.';
        if (!empty($data['cpf']) && !validateCPF($data['cpf'])) $errors[] = 'CPF inválido.';
        if (!empty($data['phone']) && !validatePhone($data['phone'])) $errors[] = 'Telefone inválido.';
        if (empty($data['password'])) $errors[] = 'Senha é obrigatória.';
        if (strlen($data['password']) < 8) $errors[] = 'Senha deve ter no mínimo 8 caracteres.';
        if ($data['password'] !== $data['confirm_password']) $errors[] = 'Senhas não coincidem.';
        if (!$terms) $errors[] = 'Você deve aceitar os termos de uso.';
        
        // Se for corretor, campos adicionais
        if ($data['user_type'] === 'broker') {
            if (empty($_POST['license_number'])) $errors[] = 'CRECI é obrigatório para corretores.';
            $data['license_number'] = sanitize($_POST['license_number'] ?? '');
            $data['company'] = sanitize($_POST['company'] ?? '');
        }
        
        if (empty($errors)) {
            $result = $auth->register($data);
            
            if ($result['success']) {
                $success_message = $result['message'];
                echo '<script>setTimeout(function(){ window.location.href = "/auth/login.php?registered=1"; }, 2000);</script>';
            } else {
                $error_message = $result['message'];
            }
        } else {
            $error_message = implode('<br>', $errors);
        }
    }
}

// Configurações da página
$page_title = "Cadastro - " . APP_NAME;
$page_description = "Crie sua conta gratuita";
$body_id = "register";

ob_start();
?>

<div class="container mt-4 mb-5" style="max-width: 700px;">
    <div class="card shadow">
        <div class="card-header bg-primary text-white text-center">
            <h4 class="mb-0">
                <i class="bi bi-person-plus me-2"></i>
                Criar Conta
            </h4>
            <p class="mb-0 mt-1">Junte-se à nossa plataforma</p>
        </div>
        
        <div class="card-body p-4">
            <?php if ($error_message): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $error_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
            
            <?php if ($success_message): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($success_message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
            
            <form method="POST" action="" id="registerForm" novalidate>
                <input type="hidden" name="action" value="register">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                
                <!-- Tipo de usuário -->
                <div class="mb-4">
                    <label class="form-label fw-bold">Tipo de Conta</label>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="user_type" id="user_type_user" value="user" 
                                       <?php echo ($_POST['user_type'] ?? 'user') === 'user' ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="user_type_user">
                                    <strong>Cliente</strong><br>
                                    <small class="text-muted">Para buscar, comprar ou alugar imóveis</small>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="user_type" id="user_type_broker" value="broker"
                                       <?php echo ($_POST['user_type'] ?? '') === 'broker' ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="user_type_broker">
                                    <strong>Corretor</strong><br>
                                    <small class="text-muted">Para anunciar e gerenciar imóveis</small>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Dados pessoais -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="text" id="first_name" name="first_name" class="form-control" 
                                   placeholder="Nome" value="<?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?>" required>
                            <label for="first_name">Nome *</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="text" id="last_name" name="last_name" class="form-control" 
                                   placeholder="Sobrenome" value="<?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?>" required>
                            <label for="last_name">Sobrenome *</label>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="text" id="cpf" name="cpf" class="form-control" 
                                   placeholder="000.000.000-00" value="<?php echo htmlspecialchars($_POST['cpf'] ?? ''); ?>">
                            <label for="cpf">CPF (opcional)</label>
                            <small class="text-muted">Formato: 000.000.000-00</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="tel" id="phone" name="phone" class="form-control" 
                                   placeholder="(11) 99999-9999" value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
                            <label for="phone">Telefone (opcional)</label>
                            <small class="text-muted">Formato: (11) 99999-9999</small>
                        </div>
                    </div>
                </div>
                
                <div class="form-floating mb-3">
                    <input type="email" id="email" name="email" class="form-control" 
                           placeholder="name@example.com" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                    <label for="email">Email *</label>
                </div>
                
                <!-- Campos específicos para corretor -->
                <div id="broker_fields" style="display: none;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" id="license_number" name="license_number" class="form-control" 
                                       placeholder="CRECI-12345" value="<?php echo htmlspecialchars($_POST['license_number'] ?? ''); ?>">
                                <label for="license_number">CRECI *</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" id="company" name="company" class="form-control" 
                                       placeholder="Nome da empresa" value="<?php echo htmlspecialchars($_POST['company'] ?? ''); ?>">
                                <label for="company">Empresa (opcional)</label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Endereço -->
                <h6 class="mt-4 mb-3">Endereço (opcional)</h6>
                <div class="form-floating mb-3">
                    <input type="text" id="address" name="address" class="form-control" 
                           placeholder="Rua, número" value="<?php echo htmlspecialchars($_POST['address'] ?? ''); ?>">
                    <label for="address">Endereço</label>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="text" id="city" name="city" class="form-control" 
                                   placeholder="Cidade" value="<?php echo htmlspecialchars($_POST['city'] ?? ''); ?>">
                            <label for="city">Cidade</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-floating mb-3">
                            <select id="state" name="state" class="form-select">
                                <option value="">Selecione</option>
                                <option value="AC" <?php echo ($_POST['state'] ?? '') === 'AC' ? 'selected' : ''; ?>>Acre</option>
                                <option value="AL" <?php echo ($_POST['state'] ?? '') === 'AL' ? 'selected' : ''; ?>>Alagoas</option>
                                <option value="RS" <?php echo ($_POST['state'] ?? '') === 'RS' ? 'selected' : ''; ?>>Rio Grande do Sul</option>
                                <option value="SP" <?php echo ($_POST['state'] ?? '') === 'SP' ? 'selected' : ''; ?>>São Paulo</option>
                                <!-- Adicionar outros estados -->
                            </select>
                            <label for="state">Estado</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-floating mb-3">
                            <input type="text" id="zip_code" name="zip_code" class="form-control" 
                                   placeholder="00000-000" value="<?php echo htmlspecialchars($_POST['zip_code'] ?? ''); ?>">
                            <label for="zip_code">CEP</label>
                        </div>
                    </div>
                </div>
                
                <!-- Senha -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="password" id="password" name="password" class="form-control" 
                                   placeholder="Senha" required>
                            <label for="password">Senha *</label>
                            <small class="text-muted">Mínimo 8 caracteres</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="password" id="confirm_password" name="confirm_password" class="form-control" 
                                   placeholder="Confirmar senha" required>
                            <label for="confirm_password">Confirmar Senha *</label>
                        </div>
                    </div>
                </div>
                
                <!-- Termos -->
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" id="terms_accepted" name="terms_accepted" required>
                    <label class="form-check-label" for="terms_accepted">
                        Eu concordo com os 
                        <a href="/legal/terms.php" target="_blank">Termos de Serviço</a> e 
                        <a href="/legal/privacy.php" target="_blank">Política de Privacidade</a> *
                    </label>
                </div>
                
                <!-- Botão -->
                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-person-plus me-2"></i>
                        Criar Conta
                    </button>
                </div>
            </form>
            
            <!-- Link de login -->
            <div class="text-center">
                <p class="mb-0">
                    Já tem uma conta? 
                    <a href="/auth/login.php" class="text-decoration-none fw-bold">
                        Faça login
                    </a>
                </p>
            </div>
        </div>
    </div>
    
    <!-- Links adicionais -->
    <div class="text-center mt-3">
        <a href="/index.php" class="text-muted text-decoration-none">
            <i class="bi bi-arrow-left me-1"></i>
            Voltar para o início
        </a>
    </div>
</div>

<script>
// Mostrar/ocultar campos específicos do corretor
document.addEventListener('DOMContentLoaded', function() {
    const userTypeRadios = document.querySelectorAll('input[name="user_type"]');
    const brokerFields = document.getElementById('broker_fields');
    const licenseNumberField = document.getElementById('license_number');
    
    function toggleBrokerFields() {
        const selectedType = document.querySelector('input[name="user_type"]:checked').value;
        if (selectedType === 'broker') {
            brokerFields.style.display = 'block';
            licenseNumberField.required = true;
        } else {
            brokerFields.style.display = 'none';
            licenseNumberField.required = false;
        }
    }
    
    userTypeRadios.forEach(radio => {
        radio.addEventListener('change', toggleBrokerFields);
    });
    
    // Inicializar
    toggleBrokerFields();
    
    // Máscara para CPF
    const cpfField = document.getElementById('cpf');
    cpfField.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d{2})$/, '$1-$2');
        e.target.value = value;
    });
    
    // Máscara para telefone
    const phoneField = document.getElementById('phone');
    phoneField.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length <= 11) {
            value = value.replace(/(\d{2})(\d)/, '($1) $2');
            if (value.length > 10) {
                value = value.replace(/(\d{5})(\d{4})$/, '$1-$2');
            } else {
                value = value.replace(/(\d{4})(\d{4})$/, '$1-$2');
            }
        }
        e.target.value = value;
    });
    
    // Máscara para CEP
    const zipField = document.getElementById('zip_code');
    zipField.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        value = value.replace(/(\d{5})(\d)/, '$1-$2');
        e.target.value = value;
    });
});
</script>

<?php 
$content = ob_get_clean();
include __DIR__ . '/../views/layouts/main.php';
?>
