<?php
/**
 * Página de Recuperação de Senha
 */

// Incluir arquivo de inicialização do sistema
require_once __DIR__ . '/includes/init.php';

// Se usuário já estiver logado, redirecionar
if ($auth->isLoggedIn()) {
    redirect('index.php');
}

// Verificar mensagens flash
$flash = getFlashMessage();

// Processar formulário de recuperação
$error_message = '';
$success_message = '';
$step = isset($_GET['step']) ? $_GET['step'] : '1';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['request_reset'])) {
        // Step 1: Solicitar reset de senha
        if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
            $error_message = 'Token de segurança inválido.';
        } else {
            $email = trim($_POST['email'] ?? '');
            
            if (empty($email) || !validateEmail($email)) {
                $error_message = 'Por favor, insira um email válido.';
            } else {
                // Tentar solicitar reset de senha
                $result = $auth->requestPasswordReset($email);
                
                if ($result['success']) {
                    // Simular envio de email (em produção, enviar email real)
                    $reset_link = url("esqueci_senha.php?step=2&token=" . $result['token']);
                    
                    $success_message = "Um link de recuperação foi enviado para seu email. 
                                       <br><small class='text-muted'>Para teste: <a href='$reset_link' class='text-decoration-none'>Clique aqui</a></small>";
                    
                    // Em produção, enviar email:
                    try {
                        $subject = "Recuperação de senha - " . APP_NAME;
                        $body = "
                            <h3>Recuperação de senha</h3>
                            <p>Você solicitou a recuperação de sua senha.</p>
                            <p>Clique no link abaixo para criar uma nova senha:</p>
                            <p><a href='$reset_link' style='background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Recuperar Senha</a></p>
                            <p><small>Este link expira em 1 hora.</small></p>
                            <p>Se você não solicitou esta recuperação, ignore este email.</p>
                        ";
                        
                        sendEmail($email, $subject, $body);
                    } catch (Exception $e) {
                        error_log("Email send error: " . $e->getMessage());
                    }
                } else {
                    $error_message = $result['message'];
                }
            }
        }
    } elseif (isset($_POST['reset_password'])) {
        // Step 2: Definir nova senha
        if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
            $error_message = 'Token de segurança inválido.';
        } else {
            $token = trim($_POST['token'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            
            if (empty($token) || empty($password) || empty($confirm_password)) {
                $error_message = 'Todos os campos são obrigatórios.';
            } elseif (strlen($password) < 6) {
                $error_message = 'A senha deve ter pelo menos 6 caracteres.';
            } elseif ($password !== $confirm_password) {
                $error_message = 'As senhas não coincidem.';
            } else {
                // Resetar senha
                $result = $auth->resetPassword($token, $password);
                
                if ($result['success']) {
                    redirect('Login.php', 'Senha alterada com sucesso! Faça login com sua nova senha.', 'success');
                } else {
                    $error_message = $result['message'];
                }
            }
        }
    }
}

// Verificar token para step 2
$valid_token = false;
if ($step === '2') {
    $token = $_GET['token'] ?? '';
    if (!empty($token)) {
        // Verificar se token é válido (simulação)
        $valid_token = strlen($token) === 64; // Token hex de 32 bytes = 64 chars
    }
    
    if (!$valid_token) {
        $error_message = 'Link de recuperação inválido ou expirado.';
        $step = '1';
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
    <title>Recuperar Senha - <?php echo APP_NAME; ?></title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        
        .recovery-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="recovery-card">
                    <div class="card-header bg-primary text-white text-center py-4">
                        <h3 class="mb-0">
                            <i class="bi bi-key-fill me-2"></i>
                            <?php echo $step === '2' ? 'Nova Senha' : 'Recuperar Senha'; ?>
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

                        <?php if (!empty($success_message)): ?>
                            <div class="alert alert-success">
                                <i class="bi bi-check-circle me-2"></i>
                                <?php echo $success_message; ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($error_message)): ?>
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <?php echo htmlspecialchars($error_message); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($step === '1'): ?>
                            <!-- Step 1: Solicitar recuperação -->
                            <div class="text-center mb-4">
                                <p class="text-muted">Digite seu email para receber o link de recuperação de senha.</p>
                            </div>
                            
                            <form method="POST" action="">
                                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                                <input type="hidden" name="request_reset" value="1">
                                
                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control" id="email" name="email" 
                                           placeholder="name@example.com" required>
                                    <label for="email">Email</label>
                                </div>
                                
                                <div class="d-grid gap-2 mb-3">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="bi bi-send me-2"></i>
                                        Enviar Link de Recuperação
                                    </button>
                                </div>
                            </form>
                        
                        <?php elseif ($step === '2' && $valid_token): ?>
                            <!-- Step 2: Definir nova senha -->
                            <div class="text-center mb-4">
                                <p class="text-muted">Digite sua nova senha.</p>
                            </div>
                            
                            <form method="POST" action="">
                                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                                <input type="hidden" name="reset_password" value="1">
                                <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token'] ?? ''); ?>">
                                
                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control" id="password" name="password" 
                                           placeholder="Nova senha" required minlength="6">
                                    <label for="password">Nova Senha</label>
                                </div>
                                
                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                                           placeholder="Confirmar senha" required minlength="6">
                                    <label for="confirm_password">Confirmar Nova Senha</label>
                                </div>
                                
                                <div class="alert alert-info small">
                                    <i class="bi bi-info-circle me-2"></i>
                                    A senha deve ter pelo menos 6 caracteres.
                                </div>
                                
                                <div class="d-grid gap-2 mb-3">
                                    <button type="submit" class="btn btn-success btn-lg">
                                        <i class="bi bi-check2 me-2"></i>
                                        Alterar Senha
                                    </button>
                                </div>
                            </form>
                        <?php endif; ?>
                        
                        <!-- Links de navegação -->
                        <div class="text-center">
                            <hr>
                            <div class="d-flex justify-content-between">
                                <a href="Login.php" class="text-decoration-none">
                                    <i class="bi bi-arrow-left me-1"></i>
                                    Voltar ao Login
                                </a>
                                <a href="index.php" class="text-decoration-none">
                                    <i class="bi bi-house me-1"></i>
                                    Página Inicial
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
        // Validação de senha em tempo real
        document.addEventListener('DOMContentLoaded', function() {
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirm_password');
            
            function validatePasswords() {
                if (password && confirmPassword) {
                    if (password.value !== confirmPassword.value) {
                        confirmPassword.setCustomValidity('As senhas não coincidem');
                    } else {
                        confirmPassword.setCustomValidity('');
                    }
                }
            }
            
            if (password) password.addEventListener('input', validatePasswords);
            if (confirmPassword) confirmPassword.addEventListener('input', validatePasswords);
        });
    </script>
</body>
</html>
