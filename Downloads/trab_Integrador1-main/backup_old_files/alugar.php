<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela de Aluguel </title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
</head>
<body>
    <div class="curve-decoration curve-1"></div>
    <div class="curve-decoration curve-2"></div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg custom-navbar">
        <div class="container">
            <a class="navbar-brand" href="#">
                Tela de Aluguel
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="casas_disponiveis.php">Comprar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#services">Descobrir</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#contact">Ajuda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Tela Inicial</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container min-vh-100 d-flex justify-content-center align-items-center">
        <div class="row w-100 justify-content-center">
            <!-- Form Section -->
            <div class="col-12 col-md-10 col-lg-8">
                <div class="card form-card">
                    <div class="card-body">
                        <form id="rentalForm">
                            <!-- Dados Pessoais -->
                            <div class="row">
                                <div class="col-md-6 mb-3 animate-focus">
                                    <label for="nome" class="form-label">Nome:</label>
                                    <input type="text" class="form-control" id="nome" placeholder="Nome completo">
                                </div>
                                <div class="col-md-6 mb-3 animate-focus">
                                    <label for="rg" class="form-label">RG:</label>
                                    <input type="text" class="form-control" id="rg" placeholder="00.000.000-0">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3 animate-focus">
                                    <label for="cpf" class="form-label">CPF:</label>
                                    <input type="text" class="form-control" id="cpf" placeholder="000.000.000-00">
                                </div>
                                <div class="col-md-6 mb-3 animate-focus">
                                    <label for="comprovante-residencia" class="form-label">Comprovante de residência:</label>
                                    <input type="text" class="form-control" id="comprovante-residencia" placeholder="Tipo de comprovante">
                                </div>
                            </div>

                            <div class="mb-4 animate-focus">
                                <label for="comprovante-renda" class="form-label">Comprovante de renda:</label>
                                <input type="text" class="form-control" id="comprovante-renda" placeholder="Valor mensal bancário, declaração de imposto de renda, contra-cheque ou certidão">
                            </div>

                            <!-- Seção de Garantia Locatícia -->
                            <div class="garantia-section">
                                <h6 class="garantia-title">Garantia Locatícia</h6>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3 animate-focus">
                                        <label for="fiador" class="form-label">Fiador:</label>
                                        <input type="text" class="form-control" id="fiador" placeholder="Nome do fiador">
                                    </div>
                                    <div class="col-md-6 mb-3 animate-focus">
                                        <label for="caucao" class="form-label">Caução:</label>
                                        <input type="text" class="form-control" id="caucao" placeholder="Valor da caução">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3 animate-focus">
                                        <label for="seguro-fianca" class="form-label">Seguro-fiança:</label>
                                        <input type="text" class="form-control" id="seguro-fianca" placeholder="Dados do seguro">
                                    </div>
                                    <div class="col-md-6 mb-3 animate-focus">
                                        <label for="titulo-capitalizacao" class="form-label">Título de capitalização:</label>
                                        <input type="text" class="form-control" id="titulo-capitalizacao" placeholder="Número do título">
                                    </div>
                                </div>
                            </div>
                               <div class="d-flex justify-content-center mb-3">
                                <a href="" class="btn btn-primary btn-xl disabled">Enviar fórmulario</a>
                            </div>

                            <!-- Informações do Contrato -->
                            <div class="contract-info">
                                <div class="contract-highlight">Assinatura do Contrato:</div>
                                Após aprovação, você assina o contrato e faz o pagamento inicial
                                <br>(primeiro aluguel, caução ou seguro-fiança).
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-light py-5">
        <div class="container px-4 px-lg-5"><div class="small text-center text-muted">Copyright &copy; 2025 - Company Miguel</div></div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>
</html>