<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Seu Espaço</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Ícones do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <!-- Fontes do Google -->
    <link href="https://fonts.googleapis.com/css?family=Merriweather+Sans:400,700" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic" rel="stylesheet" type="text/css" />
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- CSS Personalizado -->
    <link href="css/styles.css" rel="stylesheet" />
</head>
<body>
    <div class="cadastro-container">
        <div class="cadastro-header">
            <i class="bi bi-person-plus-fill"></i>
            <h2>Crie sua conta</h2>
            <p>Leva menos de um minuto para se cadastrar</p>
        </div>

        <?php
        $msg = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cadastro_form'])) {
            $firstName = trim($_POST['firstName'] ?? '');
            $lastName = trim($_POST['lastName'] ?? '');
            $cpf = trim($_POST['cpf'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $confirmPassword = trim($_POST['confirmPassword'] ?? '');
            $terms = isset($_POST['termsCheck']);
            if ($firstName && $lastName && $cpf && $phone && $email && $password && $confirmPassword && $terms) {
                if ($password !== $confirmPassword) {
                    $msg = '<div class="alert alert-danger">As senhas não coincidem.</div>';
                } elseif (strlen($password) < 8) {
                    $msg = '<div class="alert alert-danger">A senha deve ter pelo menos 8 caracteres.</div>';
                } else {
                    $arquivo = __DIR__ . '/data/usuarios.json';
                    $usuarios = [];
                    if (file_exists($arquivo)) {
                        $json = file_get_contents($arquivo);
                        $usuarios = json_decode($json, true) ?: [];
                    }
                    $novoUsuario = [
                        'nome' => $firstName,
                        'sobrenome' => $lastName,
                        'cpf' => $cpf,
                        'telefone' => $phone,
                        'email' => $email,
                        'senha' => password_hash($password, PASSWORD_DEFAULT),
                        'data' => date('Y-m-d H:i:s')
                    ];
                    $usuarios[] = $novoUsuario;
                    file_put_contents($arquivo, json_encode($usuarios, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
                    $msg = '<div class="alert alert-success" id="msg-temporaria">Usuário criado com sucesso! Redirecionando para o login...</div>';
                    echo $msg;
                    echo '<script>setTimeout(function(){ window.location.href = "login.php"; }, 2000);</script>';
                    exit;
                }
            } else {
                $msg = '<div class="alert alert-danger">Preencha todos os campos corretamente.</div>';
            }
        }
        echo $msg;
        ?>
        <form method="post" action="">
            <input type="hidden" name="cadastro_form" value="1">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-outline mb-4">
                        <input type="text" id="firstName" name="firstName" class="form-control" required />
                        <label class="form-label" for="firstName">Nome</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-outline mb-4">
                        <input type="text" id="lastName" name="lastName" class="form-control" required />
                        <label class="form-label" for="lastName">Sobrenome</label>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-outline mb-4">
                        <input type="text" id="cpf" name="cpf" class="form-control" required 
                               pattern="\d{3}\.\d{3}\.\d{3}-\d{2}" 
                               title="Formato: 000.000.000-00" />
                        <label class="form-label" for="cpf">CPF</label>
                        <small class="text-muted">Formato: 000.000.000-00</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-outline mb-4">
                        <input type="tel" id="phone" name="phone" class="form-control" required 
                               pattern="\([0-9]{2}\) [0-9]{4,5}-[0-9]{4}" 
                               title="Formato: (00) 00000-0000" />
                        <label class="form-label" for="phone">Telefone</label>
                        <small class="text-muted">Formato: (00) 00000-0000</small>
                    </div>
                </div>
            </div>
            
            <div class="form-outline mb-4">
                <input type="email" id="email" name="email" class="form-control" required />
                <label class="form-label" for="email">Endereço de email</label>
            </div>
            <div class="form-outline mb-4">
                <input type="password" id="password" name="password" class="form-control" required 
                       oninput="updatePasswordStrength(this.value)" />
                <label class="form-label" for="password">Senha</label>
                <div class="pass-strength">
                    <div class="pass-strength-bar" id="passwordStrength"></div>
                </div>
                <small class="text-muted">Mínimo de 8 caracteres</small>
            </div>
            <div class="form-outline mb-4">
                <input type="password" id="confirmPassword" name="confirmPassword" class="form-control" required />
                <label class="form-label" for="confirmPassword">Confirme sua senha</label>
            </div>
            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" id="termsCheck" name="termsCheck" required />
                <label class="form-check-label" for="termsCheck">
                    Eu concordo com os <a href="#">Termos de Serviço</a> e <a href="#">Política de Privacidade</a>
                </label>
            </div>
            <button type="submit" class="btn btn-cadastrar">
                <i class="bi bi-person-plus me-2"></i> Cadastrar
            </button>
            <div class="login-link">
                <p>Já tem uma conta? <a href="login.php">Faça login</a></p>
            </div>
        </form>
    <script>
    // Esconde a mensagem de sucesso após 4 segundos
    window.addEventListener('DOMContentLoaded', function() {
      var msg = document.getElementById('msg-temporaria');
      if(msg) {
        setTimeout(function() {
          msg.style.display = 'none';
        }, 4000);
      }
    });
    </script>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/cadastro.js"></script>
</body>
</html>