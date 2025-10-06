<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela de Compra - Registro de Imóvel</title>
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
                Tela de Registrar Compra
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Alugar</a>
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
    <div class="container mt-4">
        <div class="row g-4">
            <!-- Form Section -->
            <div class="col-lg-8">
                <div class="card form-card">
                    <div class="card-header section-header">
                        <h5 class="mb-0">Informações do Imóvel</h5>
                    </div>
                    <div class="card-body">
                        <form id="propertyForm">
                            <div class="row">
                                <div class="col-md-6 mb-3 animate-focus">
                                    <label for="nome-imovel" class="form-label">Nome do imóvel:</label>
                                    <input type="text" class="form-control" id="nome-imovel" placeholder="Digite o nome do imóvel">
                                </div>
                                <div class="col-md-6 mb-3 animate-focus">
                                    <label for="preco-total" class="form-label">Preço total:</label>
                                    <input type="text" class="form-control" id="preco-total" placeholder="R$ 0,00">
                                </div>
                            </div>

                            <div class="mb-3">
                                <button type="button" class="btn btn-media w-100" id="btn-imagens">
                                    <i class="bi bi-camera me-2"></i>Imagens e vídeos
                                </button>
                            </div>

                            <div class="mb-3 animate-focus">
                                <label for="descricao" class="form-label">Descrição detalhada:</label>
                                <textarea class="form-control" id="descricao" rows="4" placeholder="Descreva as características do imóvel..."></textarea>
                            </div>

                            <div class="mb-3 animate-focus">
                                <label for="localizacao" class="form-label">Localização:</label>
                                <input type="text" class="form-control" id="localizacao" placeholder="Endereço completo">
                            </div>

                            <div class="mb-4">
                                <button type="button" class="btn btn-media w-100" id="btn-planta">
                                    <i class="bi bi-house me-2"></i>Planta do imóvel
                                </button>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-custom-primary w-100" id="btn-agendar">
                                        <i class="bi bi-calendar-check me-2"></i>Agendar visita
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-custom-secondary w-100" id="btn-corretor">
                                        <i class="bi bi-chat-dots me-2"></i>Fale com um corretor
                                    </button>
                                </div>
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn btn-success btn-lg w-100" id="btn-completar-registro">
                                    <i class="bi bi-check-circle me-2"></i>Completar registro dos imóveis
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Payment Options -->
                <div class="card payment-card mb-4">
                    <div class="card-header section-header">
                        <h6 class="mb-0">Opções de Pagamento</h6>
                    </div>
                    <div class="card-body">
                        <div class="payment-option">
                            <i class="bi bi-credit-card me-2"></i>Financiamento bancário
                        </div>
                        <div class="payment-option">
                            <i class="bi bi-cash me-2"></i>Pagamento à vista
                        </div>
                        <div class="payment-option">
                            <i class="bi bi-calculator me-2"></i>Simulador de financiamento
                        </div>
                        <div class="payment-option">
                            <i class="bi bi-building me-2"></i>Parcelamento direto com a construtora
                        </div>
                        
                       
                    </div>
                </div>

                <!-- Logo Section -->

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