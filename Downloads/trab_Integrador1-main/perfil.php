<?php
/**
 * Página de Perfil do Usuário - Requer autenticação
 */

// Incluir arquivo de inicialização do sistema
require_once __DIR__ . '/includes/init.php';

// Verificar se o usuário está logado
if (!$auth->isLoggedIn()) {
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header('Location: Login.php');
    exit;
}

// Se for admin e houver ?id=, mostrar o perfil do corretor correspondente
if (isset($_GET['id']) && $auth->hasRole('admin')) {
    $corretor_id = intval($_GET['id']);
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? AND user_type = 'broker'");
    $stmt->execute([$corretor_id]);
    $corretor = $stmt->fetch();
    if ($corretor) {
        $current_user = $corretor;
    } else {
        // Se não encontrar corretor, volta para admin.php
        header('Location: admin.php');
        exit;
    }
} else {
    $current_user = $auth->getCurrentUser();
}

// Verificar mensagens flash
$flash = getFlashMessage();

// Processar atualização do perfil se formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $errors = [];
    
    // Validar dados
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    
    if (empty($first_name)) $errors[] = "Nome é obrigatório";
    if (empty($last_name)) $errors[] = "Sobrenome é obrigatório";
    if (empty($email)) $errors[] = "Email é obrigatório";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email inválido";
    
    // Verificar se email já existe (exceto o atual)
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
            $stmt->execute([$email, $current_user['id']]);
            if ($stmt->fetch()) {
                $errors[] = "Este email já está sendo usado por outro usuário";
            }
        } catch (Exception $e) {
            $errors[] = "Erro ao verificar email";
        }
    }
    
    // Atualizar perfil se não há erros
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("
                UPDATE users SET 
                first_name = ?, last_name = ?, email = ?, phone = ?, address = ?, updated_at = NOW()
                WHERE id = ?
            ");
            $stmt->execute([$first_name, $last_name, $email, $phone, $address, $current_user['id']]);
            
            // Atualizar dados na sessão
            $_SESSION['user']['first_name'] = $first_name;
            $_SESSION['user']['last_name'] = $last_name;
            $_SESSION['user']['email'] = $email;
            $_SESSION['user']['phone'] = $phone;
            $_SESSION['user']['address'] = $address;
            
            // Atualizar variável local
            $current_user = $_SESSION['user'];
            
            setFlashMessage("Perfil atualizado com sucesso!", "success");
            header("Location: perfil.php");
            exit;
            
        } catch (Exception $e) {
            $errors[] = "Erro ao atualizar perfil: " . $e->getMessage();
        }
    }
    
    if (!empty($errors)) {
        setFlashMessage(implode("<br>", $errors), "danger");
    }
}

// Processar alteração de senha
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $errors = [];
    
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    if (empty($current_password)) $errors[] = "Senha atual é obrigatória";
    if (empty($new_password)) $errors[] = "Nova senha é obrigatória";
    if (strlen($new_password) < 6) $errors[] = "Nova senha deve ter pelo menos 6 caracteres";
    if ($new_password !== $confirm_password) $errors[] = "Confirmação de senha não confere";
    
    // Verificar senha atual
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
            $stmt->execute([$current_user['id']]);
            $user_data = $stmt->fetch();
            
            if (!password_verify($current_password, $user_data['password'])) {
                $errors[] = "Senha atual incorreta";
            }
        } catch (Exception $e) {
            $errors[] = "Erro ao verificar senha atual";
        }
    }
    
    // Atualizar senha se não há erros
    if (empty($errors)) {
        try {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET password = ?, updated_at = NOW() WHERE id = ?");
            $stmt->execute([$hashed_password, $current_user['id']]);
            
            setFlashMessage("Senha alterada com sucesso!", "success");
            header("Location: perfil.php");
            exit;
            
        } catch (Exception $e) {
            setFlashMessage("Erro ao alterar senha: " . $e->getMessage(), "danger");
        }
    } else {
        setFlashMessage(implode("<br>", $errors), "danger");
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil - <?php echo APP_NAME ?? 'Imobiliária'; ?></title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome para ícones -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <!-- Fontes do Google -->
    <link href="https://fonts.googleapis.com/css?family=Merriweather+Sans:400,700" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic" rel="stylesheet" type="text/css" />
    <!-- CSS personalizado -->
    <link href="css/styles.css" rel="stylesheet" />
</head>

<body id="page-top">
    <!-- Navigation -->
    <?php
    // Navbar minimalista igual ao index-corretor.php
    ?>
    <nav class="navbar navbar-expand-lg navbar-light fixed-top py-3" id="mainNav">
        <div class="container px-4 px-lg-5">
            <?php
            // Redirecionar baseado no tipo de usuário
            $homeUrl = 'index.php'; // Default para usuários comuns
            if ($auth->hasRole('admin')) {
                $homeUrl = 'admin.php';
            } elseif ($auth->hasRole('broker')) {
                $homeUrl = 'index-corretor.php';
            }
            ?>
            <a class="navbar-brand" href="<?php echo $homeUrl; ?>">Tela Inicial</a>
            <button class="navbar-toggler navbar-toggler-right" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ms-auto my-2 my-lg-0">
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
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="logout.php">Sair</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <?php
    ?>



    <!-- Conteúdo Principal -->
    <section class="page-section">
        <div class="container px-4 px-lg-5">
            <?php if ($flash): ?>
                <div class="alert alert-<?php echo $flash['type']; ?> alert-dismissible fade show" role="alert" style="margin-top: 90px;">
                    <?php echo $flash['message']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-lg-8">
                    <!-- Cabeçalho -->
                    <div class="text-center mb-5">
                        <h2 class="mt-0" style="color: orange;">
                            <i class="fas fa-user-circle me-2"></i>Meu Perfil
                        </h2>
                        <hr class="divider divider-light" />
                        <p class="mb-4" style="color: orange;">
                            Gerencie suas informações pessoais e configurações da conta
                        </p>
                    </div>

                    <!-- Informações do Perfil -->
                    <div class="card shadow mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-user me-2"></i>Informações Pessoais
                            </h5>
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <input type="hidden" name="update_profile" value="1">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="first_name" class="form-label">Nome *</label>
                                        <input type="text" class="form-control" id="first_name" name="first_name" 
                                               value="<?php echo htmlspecialchars($current_user['first_name']); ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="last_name" class="form-label">Sobrenome *</label>
                                        <input type="text" class="form-control" id="last_name" name="last_name" 
                                               value="<?php echo htmlspecialchars($current_user['last_name']); ?>" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="<?php echo htmlspecialchars($current_user['email']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Telefone</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" 
                                           value="<?php echo htmlspecialchars($current_user['phone'] ?? ''); ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="address" class="form-label">Endereço</label>
                                    <textarea class="form-control" id="address" name="address" rows="2"><?php echo htmlspecialchars($current_user['address'] ?? ''); ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tipo de Usuário</label>
                                    <input type="text" class="form-control" value="<?php 
                                        $types = ['user' => 'Cliente', 'broker' => 'Corretor', 'admin' => 'Administrador'];
                                        echo $types[$current_user['user_type']] ?? 'Usuário';
                                    ?>" readonly>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>Salvar Alterações
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Alterar Senha -->
                    <div class="card shadow">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0">
                                <i class="fas fa-lock me-2"></i>Alterar Senha
                            </h5>
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <input type="hidden" name="change_password" value="1">
                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Senha Atual *</label>
                                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                                </div>
                                <div class="mb-3">
                                    <label for="new_password" class="form-label">Nova Senha *</label>
                                    <input type="password" class="form-control" id="new_password" name="new_password" 
                                           minlength="6" required>
                                    <div class="form-text">Mínimo 6 caracteres</div>
                                </div>
                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">Confirmar Nova Senha *</label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                </div>
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-key me-1"></i>Alterar Senha
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core JS -->
    <script src="js/scripts.js"></script>
    
    <!-- Script para validação de senha -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const newPassword = document.getElementById('new_password');
            const confirmPassword = document.getElementById('confirm_password');
            
            function validatePassword() {
                if (newPassword.value !== confirmPassword.value) {
                    confirmPassword.setCustomValidity('Senhas não conferem');
                } else {
                    confirmPassword.setCustomValidity('');
                }
            }
            
            newPassword.addEventListener('input', validatePassword);
            confirmPassword.addEventListener('input', validatePassword);
        });
    </script>
        <!-- Imóveis do corretor -->
        <?php
        // Exibir imóveis se o perfil é de corretor
        if (($current_user['user_type'] ?? '') === 'broker') {
            // Buscar broker_id
            $broker_id = null;
            $stmt = $pdo->prepare("SELECT id FROM brokers WHERE user_id = ?");
            $stmt->execute([$current_user['id']]);
            $broker = $stmt->fetch();
            if ($broker) {
                $broker_id = $broker['id'];
            }
            if ($broker_id) {
                // Buscar casas vinculadas ao corretor
                $stmt = $pdo->prepare("SELECT * FROM properties WHERE broker_id = ?");
                $stmt->execute([$broker_id]);
                $casas = $stmt->fetchAll();
                echo '<div class="container mt-5">';
                echo '<h3 class="mb-3" style="color: orange;"><i class="fas fa-home me-2"></i>Imóveis deste corretor</h3>';
                if ($casas) {
                    echo '<div class="row">';
                    foreach ($casas as $casa) {
                        echo '<div class="col-md-6 col-lg-4 mb-4">';
                        echo '<div class="card shadow">';
                        echo '<div class="card-body">';
                        echo '<h5 class="card-title">' . htmlspecialchars($casa['title']) . '</h5>';
                        echo '<p class="card-text">' . htmlspecialchars($casa['description']) . '</p>';
                        echo '<p><strong>Valor:</strong> R$ ' . number_format($casa['price'], 2, ',', '.') . '</p>';
                        echo '<a href="properties/view.php?id=' . $casa['id'] . '" class="btn btn-primary btn-sm">Ver detalhes</a>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                    echo '</div>';
                } else {
                    echo '<p>Nenhum imóvel cadastrado para este corretor.</p>';
                }
                echo '</div>';
            }
        }
        ?>
</body>
</html>
