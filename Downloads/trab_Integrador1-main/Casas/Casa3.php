<?php
require_once __DIR__ . '/../includes/init.php';
$current_user = $auth->getCurrentUser();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Casa em Taquara Alto Padrão</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../css/styles.css?v=20250829" rel="stylesheet">
    <style>
        <?php if (!$auth->isLoggedIn()): ?>
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
        <?php endif; ?>
        
    /* Estilos mínimos para manter a imagem estável */
    #carouselCasa3 .carousel-inner { 
        height: 400px; 
        overflow: hidden; 
        background-color: #000;
        position: relative;
    }
    #carouselCasa3 .carousel-item { 
        height: 400px;
        background-color: #000;
        position: absolute;
        width: 100%;
        top: 0;
        left: 0;
        transition: none !important;
        transform: none !important;
    }
    #carouselCasa3 .carousel-item.active {
        position: relative;
    }
    #carouselCasa3 .carousel-item img { 
        width: 100%; 
        height: 400px; 
        object-fit: cover; 
        object-position: center;
        display: block;
        background-color: #000;
    }
    /* Remover todas as transições do Bootstrap */
    #carouselCasa3 .carousel-item-next,
    #carouselCasa3 .carousel-item-prev,
    #carouselCasa3 .carousel-item-start,
    #carouselCasa3 .carousel-item-end {
        transition: none !important;
        transform: none !important;
    }
    /* Largura igual para botões Editar e Excluir */
    .btn-admin-action {
        width: 130px; /* ajuste conforme necessário */
        display: inline-block;
        text-align: center;
    }
    </style>
</head>
<body>
    <!-- Navigation-->
    <?php
    // Render the shared navbar (will reflect logged-in user if any)
    if (function_exists('renderNavbar')) {
        renderNavbar($current_user, '', '../');
    } else {
        // Fallback navbar
    ?>
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
    <?php } ?>
    
    <div class="container py-5 mt-5">
        <div class="row align-items-center">
            <div class="col-md-6 mb-4 mb-md-0">
                <!-- Carrossel de Imagens (com indicadores) -->
                <div id="carouselCasa3" class="carousel slide" data-bs-interval="false" data-bs-touch="false" data-bs-keyboard="false">
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#carouselCasa3" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                        <button type="button" data-bs-target="#carouselCasa3" data-bs-slide-to="1" aria-label="Slide 2"></button>
                        <button type="button" data-bs-target="#carouselCasa3" data-bs-slide-to="2" aria-label="Slide 3"></button>
                        <button type="button" data-bs-target="#carouselCasa3" data-bs-slide-to="3" aria-label="Slide 4"></button>
                        <button type="button" data-bs-target="#carouselCasa3" data-bs-slide-to="4" aria-label="Slide 5"></button>
                        <button type="button" data-bs-target="#carouselCasa3" data-bs-slide-to="5" aria-label="Slide 6"></button>
                        <button type="button" data-bs-target="#carouselCasa3" data-bs-slide-to="6" aria-label="Slide 7"></button>
                        <button type="button" data-bs-target="#carouselCasa3" data-bs-slide-to="7" aria-label="Slide 8"></button>
                        <button type="button" data-bs-target="#carouselCasa3" data-bs-slide-to="8" aria-label="Slide 9"></button>
                        <button type="button" data-bs-target="#carouselCasa3" data-bs-slide-to="9" aria-label="Slide 10"></button>
                    </div>
                    <div class="carousel-inner rounded shadow" style="height: 400px; overflow: hidden;">
                        <div class="carousel-item active">
                            <img src="../imgs/Casa3/Casa3.0.jpg" class="d-block w-100" alt="Casa Alpha Ville - Foto 1" style="width: 100%; height: 400px; object-fit: cover; object-position: center;">
                        </div>
                        <div class="carousel-item">
                            <img src="../imgs/Casa3/Casa3.1.jpg" class="d-block w-100" alt="Casa Alpha Ville - Foto 2" style="width: 100%; height: 400px; object-fit: cover; object-position: center;">
                        </div>
                        <div class="carousel-item">
                            <img src="../imgs/Casa3/Casa3.2.jpg" class="d-block w-100" alt="Casa Alpha Ville - Foto 3" style="width: 100%; height: 400px; object-fit: cover; object-position: center;">
                        </div>
                        <div class="carousel-item">
                            <img src="../imgs/Casa3/Casa3.3.jpg" class="d-block w-100" alt="Casa Alpha Ville - Foto 4" style="width: 100%; height: 400px; object-fit: cover; object-position: center;">
                        </div>
                        <div class="carousel-item">
                            <img src="../imgs/Casa3/Casa3.4.jpg" class="d-block w-100" alt="Casa Alpha Ville - Foto 5" style="width: 100%; height: 400px; object-fit: cover; object-position: center;">
                        </div>
                        <div class="carousel-item">
                            <img src="../imgs/Casa3/Casa3.5.jpg" class="d-block w-100" alt="Casa Alpha Ville - Foto 6" style="width: 100%; height: 400px; object-fit: cover; object-position: center;">
                        </div>
                        <div class="carousel-item">
                            <img src="../imgs/Casa3/Casa3.6.jpg" class="d-block w-100" alt="Casa Alpha Ville - Foto 7" style="width: 100%; height: 400px; object-fit: cover; object-position: center;">
                        </div>
                        <div class="carousel-item">
                            <img src="../imgs/Casa3/Casa3.7.jpg" class="d-block w-100" alt="Casa Alpha Ville - Foto 8" style="width: 100%; height: 400px; object-fit: cover; object-position: center;">
                        </div>
                        <div class="carousel-item">
                            <img src="../imgs/Casa3/Casa3.8.jpg" class="d-block w-100" alt="Casa Alpha Ville - Foto 9" style="width: 100%; height: 400px; object-fit: cover; object-position: center;">
                        </div>
                        <div class="carousel-item">
                            <img src="../imgs/Casa3/Casa3.9.jpg" class="d-block w-100" alt="Casa Alpha Ville - Foto 10" style="width: 100%; height: 400px; object-fit: cover; object-position: center;">
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselCasa3" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Anterior</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselCasa3" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Próximo</span>
                    </button>
                </div>
            </div>
            <div class="col-md-6">
                <h1 class="mb-3">Casa em Taquara Alto Padrão</h1>
                <span class="badge bg-primary mb-2">Corretor: Pedro Costa</span>
                <h4 class="text-success mb-4">Compra R$ 3.000.000,00</h4>
                <ul class="list-group mb-4">
                    <li class="list-group-item"><strong>Tamanho:</strong> 837 m² de Esquina</li>
                    <li class="list-group-item"><strong>Quartos:</strong> 3 (com Suítes e Closet)</li>
                    <li class="list-group-item"><strong>Banheiros:</strong> 2</li>
                    <li class="list-group-item"><strong>Salas:</strong> 2 Salas de Estar (1 com Lareira)</li>
                    <li class="list-group-item"><strong>Cozinhas:</strong> 1</li>
                    <li class="list-group-item"><strong>Área:</strong> Varanda, área gourmet e jardim</li>
                    <li class="list-group-item"><strong>Pátio:</strong> Sim</li>
                    <li class="list-group-item"><strong>Endereço:</strong> Rua Luiz Capovilla, 3206, Nossa Senhora de Fátima, Taquara - RS</li>
                    <li class="list-group-item">
                        <strong>Localização:</strong>
                        Próxima a shoppings, escolas, restaurantes e fácil acesso à rodovia.
                    </li>
                </ul>
                <p>
                    Casa moderna, com varanda, área gourmet, jardim e garagem para dois carros. Bairro planejado, seguro e com ótima infraestrutura.
                </p>
                <?php if ($auth->isLoggedIn() && isset($current_user['user_type']) && $current_user['user_type'] === 'admin'): ?>
                    <span class="admin-actions-inline" style="white-space: nowrap; display: inline-flex; gap: 0; align-items: center;">
                        <a href="../editar_casa.php?id=3" class="btn btn-warning mx-0 btn-admin-action"><i class="fas fa-edit me-1"></i> Editar</a>
                        <form method="POST" action="../gerenciar_imoveis.php" onsubmit="return confirm('Tem certeza que deseja excluir este imóvel?');" style="display:inline-block; margin:0;">
                            <input type="hidden" name="action" value="excluir">
                            <input type="hidden" name="casa_id" value="3">
                            <button type="submit" class="btn btn-danger mx-0 btn-admin-action"><i class="fas fa-trash me-1"></i> Excluir</button>
                        </form>
                    </span>
                <?php elseif ($auth->isLoggedIn() && isset($current_user['user_type']) && $current_user['user_type'] === 'broker'): ?>
                    <div class="d-flex gap-3">
                        <a href="../editar_casa.php?id=3" class="btn btn-warning">Editar</a>
                    </div>
                <?php else: ?>
                    <div class="d-flex gap-3">
                        <a href="../Compra.php" class="btn btn-success">Compra</a>
                    </div>
                <?php endif; ?>
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
