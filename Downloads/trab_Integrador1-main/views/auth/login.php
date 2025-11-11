<?php
require_once __DIR__ . '/../../includes/init.php';

// Redirecionar se já estiver logado
if ($auth->isLoggedIn()) {
    $user_type = $_SESSION['user_type'] ?? 'user';
    switch ($user_type) {
        case 'admin':
            redirect('/admin/dashboard.php');
            break;
        case 'broker':
            redirect('/broker/dashboard.php');
            break;
        default:
            redirect('/index.php');
            break;
    }
}

$error_message = '';
$success_message = '';

// Processar login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $error_message = 'Token de segurança inválido.';
    } else {
        $email = sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);
        
        if (empty($email) || empty($password)) {
            $error_message = 'Preencha todos os campos.';
        } else {
            $result = $auth->login($email, $password, $remember);
            
            if ($result['success']) {
                $success_message = $result['message'];
                
                // Redirecionar após login
                $redirect_url = $_SESSION['redirect_after_login'] ?? '/index.php';
                unset($_SESSION['redirect_after_login']);
                
                echo '<script>setTimeout(function(){ window.location.href = "' . $redirect_url . '"; }, 1500);</script>';
            } else {
                $error_message = $result['message'];
            }
        }
    }
}

// Configurações da página
$page_title = "Login - " . APP_NAME;
$page_description = "Faça login na sua conta";
$body_id = "login";

ob_start();
?>

<div class="container mt-5 mb-5" style="max-width: 500px;">
    <div class="card shadow">
        <div class="card-header bg-primary text-white text-center">
            <h4 class="mb-0">
                <i class="bi bi-box-arrow-in-right me-2"></i>
                Fazer Login
            </h4>
        </div>
        
        <div class="card-body p-4">
            <?php if ($error_message): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($error_message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
            
            <?php if ($success_message): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($success_message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
            
            <form method="POST" action="" novalidate>
                <input type="hidden" name="action" value="login">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                
                <!-- Campo de email -->
                <div class="form-floating mb-3">
                    <input type="email" 
                           id="email" 
                           name="email" 
                           class="form-control" 
                           placeholder="name@example.com"
                           value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                           required />
                    <label for="email">Endereço de email</label>
                </div>

                <!-- Campo de senha -->
                <div class="form-floating mb-3">
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="form-control" 
                           placeholder="Senha"
                           required />
                    <label for="password">Senha</label>
                </div>

                <!-- Layout de 2 colunas para formatação inline -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <!-- Checkbox -->
                        <div class="form-check">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="remember" 
                                   name="remember"
                                   <?php echo isset($_POST['remember']) ? 'checked' : ''; ?> />
                            <label class="form-check-label" for="remember">
                                Lembrar de mim
                            </label>
                        </div>
                    </div>

                    <div class="col-md-6 text-end">
                        <!-- Link simples -->
                        <a href="/auth/forgot-password.php" class="text-decoration-none">
                            Esqueceu a senha?
                        </a>
                    </div>
                </div>

                <!-- Botão de submissão -->
                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-box-arrow-in-right me-2"></i>
                        Entrar
                    </button>
                </div>
            </form>
            
            <!-- Divisor -->
            <hr class="my-4">
            
            <!-- Links para diferentes tipos de login -->
            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <a href="/admin/login.php" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-gear me-2"></i>
                        Login Admin
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="/broker/login.php" class="btn btn-outline-info w-100">
                        <i class="bi bi-building me-2"></i>
                        Login Corretor
                    </a>
                </div>
            </div>

            <!-- Link de registro -->
            <div class="text-center">
                <p class="mb-0">
                    Não tem uma conta? 
                    <a href="/auth/register.php" class="text-decoration-none fw-bold">
                        Registre-se agora
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

<?php 
$content = ob_get_clean();
include __DIR__ . '/../views/layouts/main.php';
?>
