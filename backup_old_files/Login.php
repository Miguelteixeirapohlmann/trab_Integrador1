<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Login</title>
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
    <div class="container mt-5" style="max-width: 500px;">
        <?php
        session_start();
        $msg = '';
        if (!isset($_SESSION['tentativas_login'])) {
            $_SESSION['tentativas_login'] = 0;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_form'])) {
            $email = trim($_POST['email'] ?? '');
            $senha = trim($_POST['senha'] ?? '');
            if ($email && $senha) {
                $arquivo = 'data/usuarios.json';
                if (file_exists($arquivo)) {
                    $json = file_get_contents($arquivo);
                    $usuarios = json_decode($json, true) ?: [];
                    $usuarioEncontrado = null;
                    foreach ($usuarios as $usuario) {
                        if (strtolower($usuario['email']) === strtolower($email)) {
                            $usuarioEncontrado = $usuario;
                            break;
                        }
                    }
                    if ($usuarioEncontrado && password_verify($senha, $usuarioEncontrado['senha'])) {
                        $_SESSION['tentativas_login'] = 0;
                        $msg = '<div class="alert alert-success">Login realizado com sucesso! Redirecionando...</div>';
                        echo $msg;
                        echo '<script>setTimeout(function(){ window.location.href = "index.php"; }, 1500);</script>';
                        exit;
                    } else {
                        $_SESSION['tentativas_login']++;
                        if ($_SESSION['tentativas_login'] >= 3) {
                            $msg = '<div class="alert alert-danger">Email ou senha inválidos. Redirecionando para recuperação de senha...</div>';
                            echo $msg;
                            echo '<script>setTimeout(function(){ window.location.href = "esqueci_senha.php"; }, 2000);</script>';
                            $_SESSION['tentativas_login'] = 0;
                            exit;
                        } else {
                            $msg = '<div class="alert alert-danger">Email ou senha inválidos.</div>';
                        }
                    }
                } else {
                    $msg = '<div class="alert alert-danger">Nenhum usuário cadastrado.</div>';
                }
            } else {
                $msg = '<div class="alert alert-danger">Preencha todos os campos.</div>';
            }
        }
        echo $msg;
        ?>
        <form method="post" action="">
            <input type="hidden" name="login_form" value="1">
            <!-- Campo de email -->
            <div class="form-outline mb-4">
                <input type="email" id="form2Example1" name="email" class="form-control" required />
                <label class="form-label" for="form2Example1">Endereço de email</label>
            </div>

            <!-- Campo de senha -->
            <div class="form-outline mb-4">
                <input type="password" id="form2Example2" name="senha" class="form-control" required />
                <label class="form-label" for="form2Example2">Senha</label>
            </div>

            <!-- Layout de 2 colunas para formatação inline -->
            <div class="row mb-4">
                <div class="col d-flex justify-content-center">
                    <!-- Checkbox -->
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="form2Example31" checked />
                        <label class="form-check-label" for="form2Example31"> Lembrar de mim </label>
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
                <a href="Login_ADM.php" class="btn btn-secondary btn-lg">Login ADM</a>
                <a href="Login_Corretor.php" class="btn btn-info btn-lg text-white">Login Corretor</a>
            </div>

            <!-- Botões de registro -->
            <div class="text-center">
                <p>Não é membro? <a href="Cadastro.php">Registre-se</a></p>
            </div>
        </form>
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