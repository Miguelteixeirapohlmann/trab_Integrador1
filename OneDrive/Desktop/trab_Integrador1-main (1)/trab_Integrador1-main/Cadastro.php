<?php
/**
 * Página de Cadastro de Usuários
 */

// Incluir arquivo de inicialização do sistema
require_once __DIR__ . '/includes/init.php';

// Se usuário já estiver logado, redirecionar
if ($auth->isLoggedIn()) {
    redirect('index.php');
}

// Verificar mensagens flash
$flash = getFlashMessage();

// Processar formulário de cadastro
$error_message = '';
$form_data = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register_form'])) {
    // Validar CSRF token
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $error_message = 'Token de segurança inválido.';
    } else {
        // Capturar dados do formulário
        $form_data = [
            'first_name' => trim($_POST['first_name'] ?? ''),
            'last_name' => trim($_POST['last_name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'cpf' => trim($_POST['cpf'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'password' => $_POST['password'] ?? '',
            'confirm_password' => $_POST['confirm_password'] ?? '',
            // Impede cadastro como admin
            'user_type' => (isset($_POST['user_type']) && $_POST['user_type'] === 'broker') ? 'broker' : 'user'
        ];
        
        // Validações
        $errors = [];
        
        if (empty($form_data['first_name'])) {
            $errors[] = 'Nome é obrigatório.';
        }
        
        if (empty($form_data['last_name'])) {
            $errors[] = 'Sobrenome é obrigatório.';
        }
        
        if (empty($form_data['email']) || !validateEmail($form_data['email'])) {
            $errors[] = 'Email válido é obrigatório.';
        }
        
        if (!empty($form_data['cpf']) && !validateCPF($form_data['cpf'])) {
            $errors[] = 'CPF inválido.';
        }
        
        if (!empty($form_data['phone']) && !validatePhone($form_data['phone'])) {
            $errors[] = 'Telefone inválido.';
        }
        
        if (empty($form_data['password'])) {
            $errors[] = 'Senha é obrigatória.';
        } elseif (strlen($form_data['password']) < 6) {
            $errors[] = 'Senha deve ter pelo menos 6 caracteres.';
        }
        
        if ($form_data['password'] !== $form_data['confirm_password']) {
            $errors[] = 'As senhas não coincidem.';
        }
        
        if (!empty($errors)) {
            $error_message = implode('<br>', $errors);
        } else {
            // Tentar registrar usuário
            $result = $auth->register($form_data);
            
            if ($result['success']) {
                redirect('Login.php', 'Cadastro realizado com sucesso! Faça login para continuar.', 'success');
            } else {
                $error_message = $result['message'];
            }
        }
    }
}

// Gerar token CSRF
$csrf_token = generateCSRFToken();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - <?php echo APP_NAME; ?></title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 6rem 0 2rem 0; /* padding-top maior para o navbar */
        }
        
        .register-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <?php
    require_once __DIR__ . '/includes/navbar.php';
    // Exibe navbar para visitante (não logado)
    if (function_exists('renderNavbar')) {
        renderNavbar(null, 'cadastro');
    }
    ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="register-card">
                    <div class="card-header bg-primary text-white text-center py-4">
                        <h3 class="mb-0">
                            <i class="bi bi-person-plus-fill me-2"></i>
                            Criar Conta
                        </h3>
                    </div>
                    
                    <div class="card-body p-4">
                        <!-- Mensagens Flash -->
                        <?php if ($flash): ?>
                            <div class="alert alert-<?php echo $flash['type'] === 'success' ? 'success' : ($flash['type'] === 'error' ? 'danger' : 'info'); ?> alert-dismissible fade show">
                                <?php echo $flash['message']; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($error_message)): ?>
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <?php echo $error_message; ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="">
                            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                            <input type="hidden" name="register_form" value="1">
                            
                            <!-- Dados Pessoais -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="first_name" name="first_name" 
                                               placeholder="Nome" required 
                                               value="<?php echo htmlspecialchars($form_data['first_name'] ?? ''); ?>">
                                        <label for="first_name">Nome *</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="last_name" name="last_name" 
                                               placeholder="Sobrenome" required 
                                               value="<?php echo htmlspecialchars($form_data['last_name'] ?? ''); ?>">
                                        <label for="last_name">Sobrenome *</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="email" class="form-control" id="email" name="email" 
                                               placeholder="name@example.com" required 
                                               value="<?php echo htmlspecialchars($form_data['email'] ?? ''); ?>">
                                        <label for="email">Email *</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="tel" class="form-control" id="phone" name="phone" 
                                               placeholder="(11) 99999-9999"
                                               value="<?php echo htmlspecialchars($form_data['phone'] ?? ''); ?>">
                                        <label for="phone">Telefone</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="cpf" name="cpf" 
                                               placeholder="000.000.000-00" maxlength="14"
                                               value="<?php echo htmlspecialchars($form_data['cpf'] ?? ''); ?>">
                                        <label for="cpf">CPF</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="user_type" name="user_type">
                                            <option value="user" <?php echo ($form_data['user_type'] ?? 'user') === 'user' ? 'selected' : ''; ?> >
                                                Cliente
                                            </option>
                                            <option value="broker" <?php echo ($form_data['user_type'] ?? '') === 'broker' ? 'selected' : ''; ?> >
                                                Corretor
                                            </option>
                                        </select>
                                        <label for="user_type">Tipo de Conta</label>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Senha -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="password" class="form-control" id="password" name="password" 
                                               placeholder="Senha" required minlength="6">
                                        <label for="password">Senha *</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                                               placeholder="Confirmar senha" required minlength="6">
                                        <label for="confirm_password">Confirmar Senha *</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="alert alert-info small">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Campos obrigatórios estão marcados com *</strong><br>
                                A senha deve ter pelo menos 6 caracteres.
                            </div>
                            
                            <div class="d-grid gap-2 mb-3">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-person-plus me-2"></i>
                                    Criar Conta
                                </button>
                            </div>
                        </form>
                        
                        <!-- Links de navegação -->
                        <div class="text-center">
                            <hr>
                            <p class="mb-0">Já tem uma conta? 
                                <a href="Login.php" class="text-decoration-none">
                                    <i class="bi bi-box-arrow-in-right me-1"></i>
                                    Fazer Login
                                </a>
                            </p>
                            <div class="mt-2">
                                <a href="index.php" class="text-muted text-decoration-none">
                                    <i class="bi bi-house me-1"></i>
                                    Voltar à Página Inicial
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Máscara para CPF
            const cpfInput = document.getElementById('cpf');
            if (cpfInput) {
                cpfInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    if (value.length <= 11) {
                        value = value.replace(/(\d{3})(\d)/, '$1.$2');
                        value = value.replace(/(\d{3})(\d)/, '$1.$2');
                        value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
                        e.target.value = value;
                    }
                });
            }
            
            // Máscara para telefone
            const phoneInput = document.getElementById('phone');
            if (phoneInput) {
                phoneInput.addEventListener('input', function(e) {
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
            }
            
            // Validação de senha
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirm_password');
            
            function validatePasswords() {
                if (password.value !== confirmPassword.value) {
                    confirmPassword.setCustomValidity('As senhas não coincidem');
                } else {
                    confirmPassword.setCustomValidity('');
                }
            }
            
            password.addEventListener('input', validatePasswords);
            confirmPassword.addEventListener('input', validatePasswords);
        });
    </script>
</body>
</html>
