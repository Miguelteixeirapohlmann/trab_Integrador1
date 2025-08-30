<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Casa do Bosque</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/styles.css?v=20250829" rel="stylesheet">
    <style>
        /* Ajuste para navbar fixa sem espaço em branco extra */
        body { padding-top: 72px; }
        .navbar {
            background-color: #000000 !important;
        }
        .navbar .navbar-brand,
        .navbar .nav-link {
            color: #ffffff !important;
        }
        .navbar .nav-link:hover {
            color: #cccccc !important;
        }
        
    /* Estilos mínimos para manter a imagem estável */
    #carouselCasa9 .carousel-inner { height: 400px; overflow: hidden; }
    #carouselCasa9 .carousel-item img { width: 100%; height: 400px; object-fit: cover; object-position: center; }
    </style>
</head>
<body>
    <!-- Navigation-->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top py-3" id="mainNav">
        <div class="container px-4 px-lg-5">
            <a class="navbar-brand" href="../index.php">Tela Inicial</a>
            <button class="navbar-toggler navbar-toggler-right" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ms-auto my-2 my-lg-0">
                    <li class="nav-item"><a class="nav-link" href="../casas_disponiveis.php">Casas Disponíveis</a></li>
                    <li class="nav-item"><a class="nav-link" href="../alugar.php">Alugar</a></li>
                    <li class="nav-item"><a class="nav-link" href="../Compra.php">Comprar</a></li>
                    <li class="nav-item"><a class="nav-link" href="../agendar_visita.php">Agendar Visita</a></li>
                    <li class="nav-item"><a class="nav-link" href="../Login.php">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="../Cadastro.php">Cadastrar</a></li>
                </ul>
            </div>
        </div>
    </nav>
    
    <div class="container py-4">
        <div class="row align-items-center">
            <div class="col-md-6 mb-4 mb-md-0">
                <!-- Carrossel de Imagens (com indicadores) -->
                <div id="carouselCasa9" class="carousel slide" data-bs-interval="false" data-bs-touch="false" data-bs-keyboard="false">
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#carouselCasa9" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                        <button type="button" data-bs-target="#carouselCasa9" data-bs-slide-to="1" aria-label="Slide 2"></button>
                        <button type="button" data-bs-target="#carouselCasa9" data-bs-slide-to="2" aria-label="Slide 3"></button>
                        <button type="button" data-bs-target="#carouselCasa9" data-bs-slide-to="3" aria-label="Slide 4"></button>
                        <button type="button" data-bs-target="#carouselCasa9" data-bs-slide-to="4" aria-label="Slide 5"></button>
                        <button type="button" data-bs-target="#carouselCasa9" data-bs-slide-to="5" aria-label="Slide 6"></button>
                        <button type="button" data-bs-target="#carouselCasa9" data-bs-slide-to="6" aria-label="Slide 7"></button>
                        <button type="button" data-bs-target="#carouselCasa9" data-bs-slide-to="7" aria-label="Slide 8"></button>
                        <button type="button" data-bs-target="#carouselCasa9" data-bs-slide-to="8" aria-label="Slide 9"></button>
                        <button type="button" data-bs-target="#carouselCasa9" data-bs-slide-to="9" aria-label="Slide 10"></button>
                    </div>
                    <div class="carousel-inner rounded shadow" style="height: 400px; overflow: hidden;">
                        <div class="carousel-item active">
                            <img src="../imgs/Casa9/Casa9.0.jpg" class="d-block w-100" alt="Casa Nova Esperança - Foto 1" style="width: 100%; height: 400px; object-fit: cover; object-position: center;">
                        </div>
                        <div class="carousel-item">
                            <img src="../imgs/Casa9/Casa9.1.jpg" class="d-block w-100" alt="Casa Nova Esperança - Foto 2" style="width: 100%; height: 400px; object-fit: cover; object-position: center;">
                        </div>
                        <div class="carousel-item">
                            <img src="../imgs/Casa9/Casa9.2.jpg" class="d-block w-100" alt="Casa Nova Esperança - Foto 3" style="width: 100%; height: 400px; object-fit: cover; object-position: center;">
                        </div>
                        <div class="carousel-item">
                            <img src="../imgs/Casa9/Casa9.3.jpg" class="d-block w-100" alt="Casa Nova Esperança - Foto 4" style="width: 100%; height: 400px; object-fit: cover; object-position: center;">
                        </div>
                        <div class="carousel-item">
                            <img src="../imgs/Casa9/Casa9.4.jpg" class="d-block w-100" alt="Casa Nova Esperança - Foto 5" style="width: 100%; height: 400px; object-fit: cover; object-position: center;">
                        </div>
                        <div class="carousel-item">
                            <img src="../imgs/Casa9/Casa9.5.jpg" class="d-block w-100" alt="Casa Nova Esperança - Foto 6" style="width: 100%; height: 400px; object-fit: cover; object-position: center;">
                        </div>
                        <div class="carousel-item">
                            <img src="../imgs/Casa9/Casa9.6.jpg" class="d-block w-100" alt="Casa Nova Esperança - Foto 7" style="width: 100%; height: 400px; object-fit: cover; object-position: center;">
                        </div>
                        <div class="carousel-item">
                            <img src="../imgs/Casa9/Casa9.7.jpg" class="d-block w-100" alt="Casa Nova Esperança - Foto 8" style="width: 100%; height: 400px; object-fit: cover; object-position: center;">
                        </div>
                        <div class="carousel-item">
                            <img src="../imgs/Casa9/Casa9.8.jpg" class="d-block w-100" alt="Casa Nova Esperança - Foto 9" style="width: 100%; height: 400px; object-fit: cover; object-position: center;">
                        </div>
                        <div class="carousel-item">
                            <img src="../imgs/Casa9/Casa9.9.jpg" class="d-block w-100" alt="Casa Nova Esperança - Foto 10" style="width: 100%; height: 400px; object-fit: cover; object-position: center;">
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselCasa9" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Anterior</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselCasa9" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Próximo</span>
                    </button>
                </div>
            </div>
            <div class="col-md-6">
                <h1 class="mb-3">Casa Nova Esperança</h1>
                <h4 class="text-success mb-4">R$ 185.000,00</h4>
                <ul class="list-group mb-4">
                    <li class="list-group-item"><strong>Tamanho:</strong> 90 m²</li>
                    <li class="list-group-item"><strong>Quartos:</strong> 2</li>
                    <li class="list-group-item"><strong>Banheiros:</strong> 1</li>
                    <li class="list-group-item"><strong>Salas:</strong> 1</li>
                    <li class="list-group-item"><strong>Cozinhas:</strong> 1</li>
                    <li class="list-group-item"><strong>Área:</strong> Quintal, área de serviço</li>
                    <li class="list-group-item"><strong>Pátio:</strong> Sim</li>
                    <li class="list-group-item"><strong>Endereço:</strong> Rua Nova, 99, Nova Esperança, Rio de Janeiro - RJ</li>
                    <li class="list-group-item">
                        <strong>Localização:</strong>
                        Próxima a escolas, mercados, farmácias e transporte público.
                    </li>
                </ul>
                <p>
                    Casa nova, pronta para morar, com quintal, área de serviço e garagem. Bairro em crescimento e com boa infraestrutura.
                </p>
                <div class="d-flex gap-3">
                    <a href="../Compra.php" class="btn btn-success">Compra</a>
                    <a href="../alugar.php" class="btn btn-primary">Alugar</a>
                </div>
            </div>
        </div>
    </div>
    <footer class="bg-light py-5">
        <div class="container px-4 px-lg-5">
            <div class="small text-center text-muted">Copyright &copy; 2025 - Company Miguel</div>
        </div>
    </footer>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
