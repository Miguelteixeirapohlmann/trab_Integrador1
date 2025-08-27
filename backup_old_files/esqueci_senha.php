<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperação de Senha</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <!-- CSS Personalizado -->
    <link href="css/styles.css" rel="stylesheet" />
</head>
<body>
    <div class="container password-container">
        <div class="password-header">
            <i class="fas fa-key"></i>
            <h2>Esqueceu sua senha?</h2>
            <p>Digite seu e-mail cadastrado para receber as instruções de redefinição</p>
        </div>
        <div class="alert-info">
            <i class="fas fa-info-circle me-2"></i>
            Um link de redefinição será enviado para seu e-mail com validade de 1 hora.
        </div>
        <form id="recupera-form">
            <div class="mb-3">
                <label for="email" class="form-label">E-mail cadastrado</label>
                <input type="email" class="form-control" id="email" placeholder="seu@email.com" required>
            </div>
            <button type="submit" class="btn btn-primary btn-submit">
                <i class="fas fa-paper-plane me-2"></i> Enviar Instruções
            </button>
        </form>
        <div id="mensagem-sucesso" style="display:none;" class="alert alert-success mt-3">
            Mensagem enviada com sucesso!
        </div>
        <div class="back-to-login">
            <a href="login.php"><i class="fas fa-arrow-left me-2"></i>Voltar para o login</a>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.getElementById('recupera-form').addEventListener('submit', function(e) {
        e.preventDefault();
        document.getElementById('mensagem-sucesso').style.display = 'block';
        document.getElementById('email').value = '';
        setTimeout(function() {
            document.getElementById('mensagem-sucesso').style.display = 'none';
        }, 3000);
    });
    </script>
</body>
</html>