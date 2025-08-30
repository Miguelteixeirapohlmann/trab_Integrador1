<?php
/**
 * Página de Login - Administrador
 */

// Incluir arquivo de inicialização do sistema
require_once __DIR__ . '/includes/init.php';

// Se usuário já estiver logado, redirecionar
if ($auth->isLoggedIn()) {
    redirect('index.php');
}

// Verificar mensagens flash
$flash = getFlashMessage();

// Processar formulário de login
$error_message = '';
$email_value = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_form'])) {
    // Verificar rate limiting
    if (!isset($_SESSION['tentativas_login'])) {
        $_SESSION['tentativas_login'] = 0;
        $_SESSION['ultimo_tentativa'] = time();
    }
    
    // Se muitas tentativas, bloquear temporariamente
    if ($_SESSION['tentativas_login'] >= 5) {
        $tempo_bloqueio = 300; // 5 minutos
        if (time() - $_SESSION['ultimo_tentativa'] < $tempo_bloqueio) {
            $tempo_restante = $tempo_bloqueio - (time() - $_SESSION['ultimo_tentativa']);
            $error_message = "Muitas tentativas de login. Tente novamente em " . ceil($tempo_restante / 60) . " minutos.";
        } else {
            // Reset das tentativas após o período de bloqueio
            $_SESSION['tentativas_login'] = 0;
        }
    }
    
    if (empty($error_message)) {
        $email = trim($_POST['email'] ?? '');
        $senha = trim($_POST['senha'] ?? '');
        $remember = isset($_POST['remember']);
        
        $email_value = $email; // Preservar email em caso de erro
        
        // Validações básicas
        if (empty($email) || empty($senha)) {
            $error_message = 'Por favor, preencha todos os campos.';
        } elseif (!validateEmail($email)) {
            $error_message = 'Por favor, insira um email válido.';
        } else {
            // Tentar fazer login
            $result = $auth->login($email, $senha, $remember);
            
            if ($result['success']) {
                // Verificar se é admin
                $user = $result['user'];
                if ($user['user_type'] !== 'admin') {
                    $error_message = 'Acesso negado. Esta área é restrita a administradores.';
                } else {
                    // Login bem-sucedido
                    $_SESSION['tentativas_login'] = 0; // Reset tentativas
                    redirect('views/admin/dashboard.php', 'Login de administrador realizado com sucesso!', 'success');
                }
            } else {
                // Incrementar tentativas
                $_SESSION['tentativas_login']++;
                $_SESSION['ultimo_tentativa'] = time();
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
    <title>Login Administrativo - <?php echo APP_NAME; ?></title>
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

     <!-- Navigation-->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top py-3" id="mainNav">
        <div class="container px-4 px-lg-5">
            <a class="navbar-brand" href="#Tela_Inicial">Tela Inicial</a>
            <button class="navbar-toggler navbar-toggler-right" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ms-auto my-2 my-lg-0">
                    <li class="nav-item"><a class="nav-link" href="alugar.php">Alugar</a></li>
                    <li class="nav-item"><a class="nav-link" href="compra.php">Compra</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#services">Descobrir</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#Final">Ajuda</a></li>
                    <li class="nav-item"><a class="nav-link" href="agendar_visita.php">Agendar Visita</a></li>
                    
                    <?php if ($auth->isLoggedIn()): ?>
                        <?php $currentUser = $auth->getCurrentUser(); ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?php if (!empty($currentUser['avatar'])): ?>
                                    <img src="<?php echo asset('uploads/' . $currentUser['avatar']); ?>" alt="Avatar" class="rounded-circle me-1" style="width: 24px; height: 24px;">
                                <?php endif; ?>
                                <?php echo htmlspecialchars($currentUser['first_name']); ?>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="perfil.php">Meu Perfil</a></li>
                                <?php if ($auth->hasRole('admin')): ?>
                                    <li><a class="dropdown-item" href="views/admin/dashboard.php">Painel Admin</a></li>
                                <?php endif; ?>
                                <?php if ($auth->hasRole('broker')): ?>
                                    <li><a class="dropdown-item" href="meus_imoveis.php">Meus Imóveis</a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="logout.php">Sair</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="Login.php">Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="Cadastro.php">Registrar</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5" style="max-width: 500px;">
        <!-- Mensagens Flash -->
        <?php if ($flash): ?>
            <div class="alert alert-<?php echo $flash['type'] === 'success' ? 'success' : ($flash['type'] === 'error' ? 'danger' : 'info'); ?> alert-dismissible fade show">
                <?php echo htmlspecialchars($flash['message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?php echo htmlspecialchars($error_message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header bg-danger text-white">
                <h3 class="text-center mb-0">
                    <i class="fas fa-shield-alt me-2"></i>Login Administrativo
                </h3>
            </div>
            <div class="card-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Área Restrita:</strong> Este acesso é exclusivo para administradores do sistema.
                </div>
                
                <form method="post" action="">
                    <!-- Token CSRF -->
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <input type="hidden" name="login_form" value="1">
                    
                    <!-- Campo de email -->
                    <div class="form-floating mb-3">
                        <input type="email" id="email" name="email" class="form-control" 
                               placeholder="name@example.com" required 
                               value="<?php echo htmlspecialchars($email_value); ?>" />
                        <label for="email">Email do Administrador</label>
                    </div>

                    <!-- Campo de senha -->
                    <div class="form-floating mb-3">
                        <input type="password" id="senha" name="senha" class="form-control" 
                               placeholder="Senha" required />
                        <label for="senha">Senha Administrativa</label>
                    </div>

                    <!-- Layout de 2 colunas para formatação inline -->
                    <div class="row mb-4">
                        <div class="col d-flex justify-content-center">
                            <!-- Checkbox -->
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" />
                                <label class="form-check-label" for="remember">Lembrar de mim</label>
                            </div>
                        </div>

                        <div class="col">
                            <!-- Link simples -->
                            <a href="esqueci_senha.php">Esqueceu a senha?</a>
                        </div>
                    </div>

                    <!-- Botões de submissão -->
                    <div class="d-grid gap-2 mb-4">
                        <button type="submit" class="btn btn-danger btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i>Entrar como Admin
                        </button>
                    </div>

                    <!-- Botões de navegação -->
                    <div class="text-center">
                        <p>Outros tipos de acesso:</p>
                        <hr>
                        <div class="d-grid gap-2">
                            <a href="Login.php" class="btn btn-outline-primary">
                                <i class="fas fa-user me-2"></i>Login de Usuário
                            </a>
                            <a href="Login_Corretor.php" class="btn btn-outline-info">
                                <i class="fas fa-handshake me-2"></i>Login de Corretor
                            </a>
                        </div>
                        <hr>
                        <p class="mt-3">Não tem conta? <a href="Cadastro.php">Registre-se</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/SimpleLightbox/2.1.0/simpleLightbox.min.js"></script>

    <footer class="bg-light py-5">
        <div class="container px-4 px-lg-5">
            <div class="small text-center text-muted">
                Copyright &copy; 2025 - Company Miguel
            </div>
        </div>
    </footer>
</body>
</html>