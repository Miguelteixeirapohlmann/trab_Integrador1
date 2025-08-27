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
        <form id="CorretorLoginForm">
            <!-- Campo de usuário -->
            <div class="form-outline mb-4">
                <input type="text" id="CorretorUser" class="form-control" required />
                <label class="form-label" for="CorretorUser">Usuário</label>
            </div>

            <!-- Campo de senha -->
            <div class="form-outline mb-4">
                <input type="password" id="CorretorPass" class="form-control" required />
                <label class="form-label" for="CorretorPass">Senha</label>
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

            <div id="adminLoginError" class="alert alert-danger" style="display:none;">Usuário ou senha incorretos.</div>

            <!-- Botões de submissão -->
            <div class="d-grid gap-2 mb-4">
                <button type="submit" href="index-corretor.php" class="btn btn-primary btn-lg">Entrar</button>
                <a href="Login_ADM.php" class="btn btn-secondary btn-lg">Login ADM</a>
                <a href="Login.php" class="btn btn-info btn-lg text-white">Login </a>
            </div>
        </form>
    </div>
            <footer class="bg-light py-5">
            <div class="container px-4 px-lg-5"><div class="small text-center text-muted">Copyright &copy; 2025 - Company Miguel</div></div>
        </footer>

    <!-- Scripts -->
    <script>
        document.getElementById('CorretorLoginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const user = document.getElementById('CorretorUser').value;
            const pass = document.getElementById('CorretorPass').value;
            // Credenciais fixas de exemplo
           if((user === 'joao' && pass === '1234567') ||
               (user === 'maria' && pass === '1234567') ||
               (user === 'carlos' && pass === '1234567')) {
                localStorage.setItem('corretor_logado', user);
                window.location.href = 'index-corretor.php';
            } else {
                document.getElementById('adminLoginError').style.display = 'block';
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/SimpleLightbox/2.1.0/simpleLightbox.min.js"></script>
</body>
</html>
