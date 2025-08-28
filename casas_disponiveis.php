<?php
/**
 * Página de Casas Disponíveis
 */

// Incluir arquivo de inicialização do sistema
require_once __DIR__ . '/includes/init.php';

// Verificar mensagens flash
$flash = getFlashMessage();

// Aqui pode ser implementada lógica para buscar casas do banco de dados
// Por exemplo:
/*
try {
    $stmt = $pdo->prepare("
        SELECT p.*, pi.filename as image_filename 
        FROM properties p 
        LEFT JOIN property_images pi ON p.id = pi.property_id AND pi.is_primary = 1
        WHERE p.status = 'available'
        ORDER BY p.created_at DESC
    ");
    $stmt->execute();
    $properties = $stmt->fetchAll();
} catch (Exception $e) {
    error_log("Error fetching properties: " . $e->getMessage());
    $properties = [];
}
*/

// Para manter compatibilidade com o sistema atual, usar dados estáticos
$properties = [];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Casas Disponíveis - <?php echo APP_NAME ?? 'Imobiliária'; ?></title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Ícones do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <!-- Fontes do Google -->
    <link href="https://fonts.googleapis.com/css?family=Merriweather+Sans:400,700" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic" rel="stylesheet" type="text/css" />
    <!-- Font Awesome para ícones -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #ff7b00 0%, #ff9500 100%);
            color: white;
            padding: 120px 0 80px;
            margin-top: 76px;
        }
        
        .search-section {
            background: #f8f9fa;
            padding: 40px 0;
            border-radius: 15px;
            margin: -50px 15px 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .carousel-img-fixed {
            width: 100%;
            height: 280px;
            object-fit: cover;
            object-position: center;
            border-radius: 10px 10px 0 0;
            transition: transform 0.3s ease;
        }
        
        .property-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            height: 100%;
            background: white;
        }
        
        .property-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }
        
        .property-card:hover .carousel-img-fixed {
            transform: scale(1.05);
        }
        
        .card-body {
            padding: 25px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            flex-grow: 1;
        }
        
        .property-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 15px;
            line-height: 1.4;
        }
        
        .property-price {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: #333;
        }
        
        .price-sale {
            color: #333;
        }
        
        .price-rent {
            color: #333;
        }
        
        .btn-details {
            background: linear-gradient(45deg, #ff7b00, #ff9500);
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            margin-top: auto;
        }
        
        .btn-details:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 123, 0, 0.4);
            color: white;
        }
        
        .filter-form {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        
        .form-check-input:checked {
            background-color: #ff7b00;
            border-color: #ff7b00;
        }
        
        .btn-search {
            background: linear-gradient(45deg, #ff7b00, #ff9500);
            border: none;
            padding: 12px 40px;
            border-radius: 25px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .carousel-control-prev,
        .carousel-control-next {
            width: 40px;
            height: 40px;
            background-color: rgba(0,0,0,0.5);
            border-radius: 50%;
            top: 50%;
            transform: translateY(-50%);
        }
        
        .carousel-control-prev {
            left: 10px;
        }
        
        .carousel-control-next {
            right: 10px;
        }
        
        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #333;
            text-align: center;
            margin-bottom: 50px;
        }
        
        .no-properties {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }
        
        .no-properties i {
            font-size: 4rem;
            margin-bottom: 20px;
            color: #dee2e6;
        }
        
        @media (max-width: 768px) {
            .carousel-img-fixed {
                height: 200px;
            }
            
            .hero-section {
                padding: 100px 0 60px;
            }
            
            .section-title {
                font-size: 2rem;
            }
        }
    </style>

</head>
<body>
    <!-- Navigation-->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top py-3" id="mainNav">
        <div class="container px-4 px-lg-5">
            <a class="navbar-brand" href="index.php">Tela Inicial</a>
            <button class="navbar-toggler navbar-toggler-right" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ms-auto my-2 my-lg-0">
                    <li class="nav-item"><a class="nav-link" href="alugar.php">Alugar</a></li>
                    <li class="nav-item"><a class="nav-link" href="Compra.php">Compra</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#services">Descobrir</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#Final">Ajuda</a></li>
                    <li class="nav-item"><a class="nav-link" href="agendar_visita.php">Agendar Visita</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="hero-section">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <h1 class="display-4 fw-bold mb-4">
                        Casas Disponíveis
                    </h1>
                    <p class="lead mb-0">Encontre o imóvel perfeito para você e sua família</p>
                </div>
            </div>
        </div>
    </header>
    <!-- Mensagens Flash -->
    <?php if ($flash): ?>
        <div class="alert alert-<?php echo $flash['type'] === 'success' ? 'success' : ($flash['type'] === 'error' ? 'danger' : 'info'); ?> alert-dismissible fade show position-fixed" 
             style="top: 80px; right: 20px; z-index: 9999; max-width: 400px;">
            <?php echo htmlspecialchars($flash['message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Search Section -->
    <section class="search-section">
        <div class="container">
            <div class="filter-form">
                <h3 class="text-center mb-4">Filtrar Imóveis</h3>
                <form class="row g-3 align-items-end" method="get" action="casas_disponiveis.php">
                    <div class="col-md-8">
                        <label class="form-label fw-bold">Tipo de Negócio:</label>
                        <div class="d-flex gap-4 justify-content-center">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="tipo" id="aluguel" value="aluguel" <?php if(isset($_GET['tipo']) && $_GET['tipo']==='aluguel') echo 'checked'; ?>>
                                <label class="form-check-label fw-semibold" for="aluguel">
                                    Aluguel
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="tipo" id="venda" value="venda" <?php if(isset($_GET['tipo']) && $_GET['tipo']==='venda') echo 'checked'; ?>>
                                <label class="form-check-label fw-semibold" for="venda">
                                    Venda
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="tipo" id="ambos" value="ambos" <?php if(isset($_GET['tipo']) && $_GET['tipo']==='ambos') echo 'checked'; ?>>
                                <label class="form-check-label fw-semibold" for="ambos">
                                    Ambos
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <button type="submit" class="btn btn-search text-white">
                            Buscar Imóveis
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Properties Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="section-title">
                <?php 
                $tipo = $_GET['tipo'] ?? 'todos';
                switch($tipo) {
                    case 'aluguel':
                        echo 'Imóveis para Aluguel';
                        break;
                    case 'venda':
                        echo 'Imóveis para Venda';
                        break;
                    case 'ambos':
                        echo 'Venda e Aluguel';
                        break;
                    default:
                        echo 'Todos os Imóveis';
                }
                ?>
            </h2>

            <?php
            // Array de casas
            $casas = [
                [
                    'imgs' => ['imgs/Casa1/Casa1.1.jpg', 'imgs/Casa1/Casa1.0.jpg','imgs/Casa1/Casa1.2.jpg','imgs/Casa1/Casa1.3.jpg','imgs/Casa1/Casa1.4.jpg','imgs/Casa1/Casa1.5.jpg','imgs/Casa1/Casa1.6.jpg','imgs/Casa1/Casa1.7.jpg','imgs/Casa1/Casa1.8.jpg','imgs/Casa1/Casa1.9.jpg'],
                    'titulo' => 'Casa em Santo Antônio da Patrulha',
                    'venda' => 'R$ 5.200.000,00',
                    'aluguel' => 'R$ 1.000.000,00 ao Mês',
                    'link' => 'Casas/Casa1.php'
                ],
                [
                    'imgs' => ['imgs/Casa2/Casa2.0.jpg', 'imgs/Casa2/Casa2.1.jpg', 'imgs/Casa2/Casa2.2.jpg', 'imgs/Casa2/Casa2.3.jpg', 'imgs/Casa2/Casa2.4.jpg', 'imgs/Casa2/Casa2.5.jpg', 'imgs/Casa2/Casa2.6.jpg', 'imgs/Casa2/Casa2.7.jpg', 'imgs/Casa2/Casa2.8.jpg', 'imgs/Casa2/Casa2.9.jpg', 'imgs/Casa2/Casa2.10.jpg', 'imgs/Casa2/Casa2.11.jpg', 'imgs/Casa2/Casa2.12.jpg', 'imgs/Casa2/Casa2.13.jpg'],
                    'titulo' => 'Casa em Taquara',
                    'venda' => null,
                    'aluguel' => 'R$ 2.000,00 ao Mês',
                    'link' => 'Casas/Casa2.php'
                ],
                [
                    'imgs' => ['imgs/Casa3/Casa3.0.jpg', 'imgs/Casa3/Casa3.1.jpg', 'imgs/Casa3/Casa3.2.jpg', 'imgs/Casa3/Casa3.3.jpg', 'imgs/Casa3/Casa3.4.jpg', 'imgs/Casa3/Casa3.5.jpg', 'imgs/Casa3/Casa3.6.jpg', 'imgs/Casa3/Casa3.7.jpg', 'imgs/Casa3/Casa3.8.jpg', 'imgs/Casa3/Casa3.9.jpg'],
                    'titulo' => 'Casa Em Taquara Alto Padrão',
                    'venda' => 'R$ 3.000.000,00',
                    'aluguel' => null,
                    'link' => 'Casas/Casa3.php'
                ],
                [
                    'imgs' => ['imgs/Casa4/Casa4.4.jpg', 'imgs/Casa4/Casa4.2.jpg', 'imgs/Casa4/Casa4.0.jpg', 'imgs/Casa4/Casa4.3.jpg', 'imgs/Casa4/Casa4.5.jpg', 'imgs/Casa4/Casa4.6.jpg', 'imgs/Casa4/Casa4.7.jpg', 'imgs/Casa4/Casa4.8.jpg', 'imgs/Casa4/Casa4.9.jpg'],
                    'titulo' => 'Casa em Taquara Rua Mundo Novo',
                    'venda' => 'R$ 170.000,00',
                    'aluguel' => 'R$ 1.700,00 ao Mês',
                    'link' => 'Casas/Casa4.php'
                ],
                [
                    'imgs' => ['imgs/Casa5/Casa5.0.jpg', 'imgs/Casa5/Casa5.1.jpg', 'imgs/Casa5/Casa5.2.jpg', 'imgs/Casa5/Casa5.3.jpg', 'imgs/Casa5/Casa5.4.jpg', 'imgs/Casa5/Casa5.5.jpg', 'imgs/Casa5/Casa5.6.jpg', 'imgs/Casa5/Casa5.7.jpg', 'imgs/Casa5/Casa5.8.jpg', 'imgs/Casa5/Casa5.9.jpg', 'imgs/Casa5/Casa5.10.jpg'],
                    'titulo' => 'Casa em Taquara Flores da Cunha',
                    'venda' => 'R$ 380.000,00',
                    'aluguel' => 'R$ 1.750,00 ao Mês',
                    'link' => 'Casas/Casa5.php'
                ],
                [
                    'imgs' => ['imgs/Casa6/Casa6.0.jpg', 'imgs/Casa6/Casa6.1.jpg', 'imgs/Casa6/Casa6.2.jpg', 'imgs/Casa6/Casa6.3.jpg', 'imgs/Casa6/Casa6.4.jpg'],
                    'titulo' => 'Casa em Parobé',
                    'venda' => 'R$ 210.000,00',
                    'aluguel' => 'R$ 1.500,00 ao Mês',
                    'link' => 'Casas/Casa6.php'
                ],
                [
                    'imgs' => ['imgs/Casa7/Casa7.0.jpg', 'imgs/Casa7/Casa7.1.jpg', 'imgs/Casa7/Casa7.2.jpg', 'imgs/Casa7/Casa7.3.jpg', 'imgs/Casa7/Casa7.4.jpg'],
                    'titulo' => 'Casa em Taquara Santa Terezinha',
                    'venda' => 'R$ 650.000,00',
                    'aluguel' => 'R$ 1.500,00 ao Mês',
                    'link' => 'Casas/Casa7.php'
                ],
                [
                    'imgs' => ['imgs/Casa8/Casa8.0.jpg', 'imgs/Casa8/Casa8.1.jpg', 'imgs/Casa8/Casa8.2.jpg', 'imgs/Casa8/Casa8.3.jpg', 'imgs/Casa8/Casa8.4.jpg', 'imgs/Casa8/Casa8.5.jpg', 'imgs/Casa8/Casa8.6.jpg', 'imgs/Casa8/Casa8.7.jpg', 'imgs/Casa8/Casa8.8.jpg', 'imgs/Casa8/Casa8.9.jpg'],
                    'titulo' => 'Casa em Taquara rua Alvarino Lacerda Filho',
                    'venda' => 'R$ 184.900,00',
                    'aluguel' => null,
                    'link' => 'Casas/Casa8.php'
                ],
                [
                    'imgs' => ['imgs/casa9/Casa9.0.jpg', 'imgs/casa9/Casa9.1.jpg', 'imgs/casa9/Casa9.2.jpg', 'imgs/casa9/Casa9.3.jpg', 'imgs/casa9/Casa9.4.jpg'],
                    'titulo' => 'Casa em Taquara - São Francisco',
                    'venda' => 'R$ 450.000,00',
                    'aluguel' => null,
                    'link' => 'Casas/Casa9.php'
                ],
            ];

            $tipo = $_GET['tipo'] ?? 'ambos';
            $casas_filtradas = array_filter($casas, function($casa) use ($tipo) {
                if ($tipo === 'aluguel') {
                    return !empty($casa['aluguel']) && is_null($casa['venda']);
                } elseif ($tipo === 'venda') {
                    return !empty($casa['venda']) && is_null($casa['aluguel']);
                } elseif ($tipo === 'ambos') {
                    return !empty($casa['aluguel']) && !empty($casa['venda']);
                }
                return true;
            });
            ?>
            
            <?php if (empty($casas_filtradas)): ?>
                <div class="no-properties">
                    <h3>Nenhum imóvel encontrado</h3>
                    <p>Não há imóveis disponíveis para os filtros selecionados.</p>
                    <a href="casas_disponiveis.php" class="btn btn-search text-white mt-3">
                        Ver Todos os Imóveis
                    </a>
                </div>
            <?php else: ?>
                <div class="row g-4">
                    <?php foreach ($casas_filtradas as $i => $casa): ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="card property-card">
                                <div class="position-relative overflow-hidden">
                                    <div id="carouselCasa<?php echo $i; ?>" class="carousel slide" data-bs-interval="false">
                                        <div class="carousel-inner">
                                            <?php foreach ($casa['imgs'] as $j => $img): ?>
                                                <div class="carousel-item<?php if ($j === 0) echo ' active'; ?>">
                                                    <img src="<?php echo $img; ?>" alt="<?php echo htmlspecialchars($casa['titulo']); ?>" class="d-block w-100 carousel-img-fixed">
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <?php if (count($casa['imgs']) > 1): ?>
                                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselCasa<?php echo $i; ?>" data-bs-slide="prev">
                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Anterior</span>
                                            </button>
                                            <button class="carousel-control-next" type="button" data-bs-target="#carouselCasa<?php echo $i; ?>" data-bs-slide="next">
                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Próximo</span>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <!-- Badge para tipo de negócio -->
                                    <div class="position-absolute top-0 start-0 m-3">
                                        <?php if (!empty($casa['venda']) && !empty($casa['aluguel'])): ?>
                                            <span class="badge rounded-pill" style="background-color: #ff7b00;">Venda & Aluguel</span>
                                        <?php elseif (!empty($casa['venda'])): ?>
                                            <span class="badge bg-success rounded-pill">Venda</span>
                                        <?php elseif (!empty($casa['aluguel'])): ?>
                                            <span class="badge rounded-pill" style="background-color: #ff7b00;">Aluguel</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <div class="card-body">
                                    <h5 class="property-title">
                                        <?php echo htmlspecialchars($casa['titulo']); ?>
                                    </h5>
                                    
                                    <div class="mb-3">
                                        <?php if ($tipo === 'aluguel' && !empty($casa['aluguel'])): ?>
                                            <div class="property-price price-rent">
                                                <?php echo $casa['aluguel']; ?>
                                            </div>
                                        <?php elseif ($tipo === 'venda' && !empty($casa['venda'])): ?>
                                            <div class="property-price price-sale">
                                                <?php echo $casa['venda']; ?>
                                            </div>
                                        <?php elseif ($tipo === 'ambos'): ?>
                                            <?php if (!empty($casa['venda'])): ?>
                                                <div class="property-price price-sale">
                                                    <?php echo $casa['venda']; ?>
                                                </div>
                                            <?php endif; ?>
                                            <?php if (!empty($casa['aluguel'])): ?>
                                                <div class="property-price price-rent">
                                                    <?php echo $casa['aluguel']; ?>
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <a href="<?php echo $casa['link']; ?>" class="btn btn-details w-100">
                                        Ver Detalhes
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <footer class="bg-light py-5">
        <div class="container px-4 px-lg-5">
            <div class="small text-center text-muted">Copyright &copy; 2025 - Company Miguel</div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>
</body>
</html>
