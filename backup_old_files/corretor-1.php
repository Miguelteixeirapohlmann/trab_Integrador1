<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil do Corretor - João Silva</title>
    <link href="css/styles.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="admin.php">Administração</a>
        </div>
    </nav>
    <div class="container my-5">
        <h2>Perfil do Corretor: João Silva</h2>
        <div class="mb-3">
            <span class="badge bg-success">Ativo</span>
            <span class="ms-3">Email: joao@email.com</span>
        </div>
        <h4>Imóveis deste corretor</h4>
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped mb-0" id="imoveisCorretorTable">
                        <thead class="table-dark">
                            <tr>
                                <th>Imagem</th>
                                <th>Nome</th>
                                <th>Preço</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Imóveis via JS -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="js/corretor-1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
