<?php
/**
 * Página de Login
 */

// Incluir arquivo de inicialização do sistema
require_once __DIR__ . '/includes/init.php';

// Se usuário já estiver logado, redirecionar
if ($auth->isLoggedIn()) {
    $user = $auth->getCurrentUser();
    if ($user['user_type'] === 'admin') {
        redirect('admin.php');
    } elseif ($user['user_type'] === 'broker' || $user['user_type'] === 'corretor') {
        redirect('gerenciar_imoveis.php');
    } else {
        redirect('index.php');
    }
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
    // Bloqueio removido
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
                // Login bem-sucedido
                $_SESSION['tentativas_login'] = 0; // Reset tentativas
                
                // Verificar se há URL de redirecionamento
                $redirect_url = $_SESSION['redirect_after_login'] ?? null;
                unset($_SESSION['redirect_after_login']);
                
                // Redirecionar baseado no tipo de usuário
                $user = $result['user'];
                if (!$redirect_url) {
                    if ($user['user_type'] === 'admin') {
                        $redirect_url = 'admin.php';
                    } elseif ($user['user_type'] === 'broker' || $user['user_type'] === 'corretor') {
                        $redirect_url = 'gerenciar_imoveis.php';
                    } else {
                        $redirect_url = 'index.php';
                    }
                }
                
                redirect($redirect_url, 'Login realizado com sucesso!', 'success');
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
    <title>Página de Login - <?php echo APP_NAME; ?></title>
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
    <nav class="navbar navbar-expand-lg navbar-light fixed-top py-3" id="mainNav">
        <div class="container px-4 px-lg-5">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-home me-2"></i>Tela Inicial
            </a>
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
                        <li class="nav-item"><a class="nav-link" href="Cadastro.php">Cadastro</a></li>
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
            <div class="card-header">
                <h3 class="text-center mb-0">Login</h3>
            </div>
            <div class="card-body">
                <form method="post" action="">
                    <!-- Token CSRF -->
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <input type="hidden" name="login_form" value="1">
                    
                    <!-- Campo de email -->
                    <div class="form-floating mb-3">
                        <input type="email" id="email" name="email" class="form-control" 
                               placeholder="name@example.com" required 
                               value="<?php echo htmlspecialchars($email_value); ?>" />
                        <label for="email">Endereço de email</label>
                    </div>

                    <!-- Campo de senha -->
                    <div class="form-floating mb-3">
                        <input type="password" id="senha" name="senha" class="form-control" 
                               placeholder="Senha" required />
                        <label for="senha">Senha</label>
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
                        <button type="submit" class="btn btn-primary btn-lg">Entrar</button>
                    </div>

                    <!-- Botões de registro -->
                    <div class="text-center">
                        <p>Não é membro? <a href="Cadastro.php">Registre-se</a></p>
                        <hr>
                        <div class="d-grid gap-2">
                            <a href="Login_ADM.php" class="btn btn-outline-secondary">Acesso Administrativo</a>
                            <a href="Login_Corretor.php" class="btn btn-outline-info">Acesso de Corretor</a>
                        </div>
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
