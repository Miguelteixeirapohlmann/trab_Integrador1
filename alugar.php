<?php
/**
 * Página de Aluguel de Imóveis
 */

// Incluir arquivo de inicialização do sistema
require_once __DIR__ . '/includes/init.php';

// Verificar mensagens flash
$flash = getFlashMessage();

// Simulação de dados de casas para aluguel
$casas_aluguel = [
    [
        'id' => 1,
        'titulo' => 'Apartamento Centro',
        'endereco' => 'Centro, Cidade - UF',
        'valor' => 1500.00,
        'quartos' => 2,
        'banheiros' => 1,
        'area' => 65,
        'imagem' => 'imgs/foto1.jpeg',
        'descricao' => 'Apartamento bem localizado no centro da cidade, próximo a comércios e transporte público.'
    ],
    [
        'id' => 2,
        'titulo' => 'Casa Jardim das Flores',
        'endereco' => 'Jardim das Flores, Cidade - UF',
        'valor' => 2200.00,
        'quartos' => 3,
        'banheiros' => 2,
        'area' => 120,
        'imagem' => 'imgs/foto2.jpg',
        'descricao' => 'Casa ampla com quintal, ideal para famílias. Localizada em bairro residencial tranquilo.'
    ],
    [
        'id' => 3,
        'titulo' => 'Apartamento Vista Alegre',
        'endereco' => 'Vista Alegre, Cidade - UF',
        'valor' => 1800.00,
        'quartos' => 2,
        'banheiros' => 2,
        'area' => 75,
        'imagem' => 'imgs/foto3.jpg',
        'descricao' => 'Apartamento moderno com vista privilegiada, sacada gourmet e vaga de garagem.'
    ],
    [
        'id' => 4,
        'titulo' => 'Casa Solar dos Pássaros',
        'endereco' => 'Solar dos Pássaros, Cidade - UF',
        'valor' => 2800.00,
        'quartos' => 4,
        'banheiros' => 3,
        'area' => 180,
        'imagem' => 'imgs/foto4.jpg',
        'descricao' => 'Casa espaçosa com jardim, piscina e área de churrasqueira. Perfeita para famílias grandes.'
    ],
    [
        'id' => 5,
        'titulo' => 'Apartamento Bela Vista',
        'endereco' => 'Bela Vista, Cidade - UF',
        'valor' => 1600.00,
        'quartos' => 2,
        'banheiros' => 1,
        'area' => 60,
        'imagem' => 'imgs/foto5.jpg',
        'descricao' => 'Apartamento aconchegante, recém-reformado, com armários planejados.'
    ],
    [
        'id' => 6,
        'titulo' => 'Casa Nova Esperança',
        'endereco' => 'Nova Esperança, Cidade - UF',
        'valor' => 2500.00,
        'quartos' => 3,
        'banheiros' => 2,
        'area' => 140,
        'imagem' => 'imgs/casa6.jpg',
        'descricao' => 'Casa térrea com área gourmet, ideal para quem valoriza conforto e praticidade.'
    ]
];

// Filtros
$filtro_valor_max = isset($_GET['valor_max']) ? (int)$_GET['valor_max'] : null;
$filtro_quartos = isset($_GET['quartos']) ? (int)$_GET['quartos'] : null;
$filtro_tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';

// Aplicar filtros
if ($filtro_valor_max || $filtro_quartos || $filtro_tipo) {
    $casas_aluguel = array_filter($casas_aluguel, function($casa) use ($filtro_valor_max, $filtro_quartos, $filtro_tipo) {
        $passa_filtro = true;
        
        if ($filtro_valor_max && $casa['valor'] > $filtro_valor_max) {
            $passa_filtro = false;
        }
        
        if ($filtro_quartos && $casa['quartos'] < $filtro_quartos) {
            $passa_filtro = false;
        }
        
        if ($filtro_tipo && stripos($casa['titulo'], $filtro_tipo) === false) {
            $passa_filtro = false;
        }
        
        return $passa_filtro;
    });
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Imóveis para Aluguel - <?php echo APP_NAME; ?></title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet">
    <style>
        .property-card {
            transition: transform 0.3s ease;
            height: 100%;
        }
        
        .property-card:hover {
            transform: translateY(-5px);
        }
        
        .property-img {
            height: 250px;
            object-fit: cover;
        }
        
        .price-tag {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: bold;
            font-size: 1.1rem;
        }
        
        .property-features {
            display: flex;
            gap: 1rem;
            margin: 1rem 0;
        }
        
        .feature-item {
            display: flex;
            align-items: center;
            gap: 0.3rem;
            font-size: 0.9rem;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top py-3" id="mainNav" style="background-color: white;">
        <div class="container px-4 px-lg-5">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-house-door me-2"></i>Real Estate
            </a>
            <button class="navbar-toggler navbar-toggler-right" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ms-auto my-2 my-lg-0">
                    <li class="nav-item"><a class="nav-link" href="index.php">Início</a></li>
                    <li class="nav-item"><a class="nav-link" href="casas_disponiveis.php">Comprar</a></li>
                    <li class="nav-item"><a class="nav-link active" href="alugar.php">Alugar</a></li>
                    <li class="nav-item"><a class="nav-link" href="agendar_visita.php">Agendar Visita</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#contact">Contato</a></li>
                    <?php if ($auth->isLoggedIn()): ?>
                        <li class="nav-item"><a class="nav-link" href="logout.php">Sair</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="Login.php">Login</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-fluid" style="padding-top: 100px;">
        <!-- Mensagens Flash -->
        <?php if ($flash): ?>
            <div class="container mb-4">
                <div class="alert alert-<?php echo $flash['type'] === 'success' ? 'success' : ($flash['type'] === 'error' ? 'danger' : 'info'); ?> alert-dismissible fade show">
                    <?php echo $flash['message']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        <?php endif; ?>

        <!-- Header Section -->
        <div class="container mb-5">
            <div class="text-center">
                <h1 class="display-4 fw-bold text-primary">
                    <i class="bi bi-key me-3"></i>
                    Imóveis para Aluguel
                </h1>
                <p class="lead text-muted">Encontre o imóvel ideal para alugar com as melhores condições</p>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="container mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-funnel me-2"></i>
                        Filtros de Busca
                    </h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="" class="row g-3">
                        <div class="col-md-3">
                            <label for="valor_max" class="form-label">Valor máximo</label>
                            <select class="form-select" id="valor_max" name="valor_max">
                                <option value="">Qualquer valor</option>
                                <option value="1000" <?php echo $filtro_valor_max == 1000 ? 'selected' : ''; ?>>Até R$ 1.000</option>
                                <option value="1500" <?php echo $filtro_valor_max == 1500 ? 'selected' : ''; ?>>Até R$ 1.500</option>
                                <option value="2000" <?php echo $filtro_valor_max == 2000 ? 'selected' : ''; ?>>Até R$ 2.000</option>
                                <option value="2500" <?php echo $filtro_valor_max == 2500 ? 'selected' : ''; ?>>Até R$ 2.500</option>
                                <option value="3000" <?php echo $filtro_valor_max == 3000 ? 'selected' : ''; ?>>Até R$ 3.000</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="quartos" class="form-label">Mín. de quartos</label>
                            <select class="form-select" id="quartos" name="quartos">
                                <option value="">Qualquer</option>
                                <option value="1" <?php echo $filtro_quartos == 1 ? 'selected' : ''; ?>>1+ quarto</option>
                                <option value="2" <?php echo $filtro_quartos == 2 ? 'selected' : ''; ?>>2+ quartos</option>
                                <option value="3" <?php echo $filtro_quartos == 3 ? 'selected' : ''; ?>>3+ quartos</option>
                                <option value="4" <?php echo $filtro_quartos == 4 ? 'selected' : ''; ?>>4+ quartos</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="tipo" class="form-label">Tipo de imóvel</label>
                            <input type="text" class="form-control" id="tipo" name="tipo" 
                                   placeholder="Ex: Casa, Apartamento..." value="<?php echo htmlspecialchars($filtro_tipo); ?>">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search me-2"></i>Filtrar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Properties Grid -->
        <div class="container">
            <div class="row">
                <?php if (empty($casas_aluguel)): ?>
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <i class="bi bi-info-circle me-2"></i>
                            Nenhum imóvel encontrado com os filtros selecionados. Tente ajustar os critérios de busca.
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($casas_aluguel as $casa): ?>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card property-card shadow-sm">
                                <img src="<?php echo htmlspecialchars($casa['imagem']); ?>" 
                                     class="card-img-top property-img" alt="<?php echo htmlspecialchars($casa['titulo']); ?>">
                                
                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title mb-0"><?php echo htmlspecialchars($casa['titulo']); ?></h5>
                                        <span class="price-tag"><?php echo formatCurrency($casa['valor']); ?>/mês</span>
                                    </div>
                                    
                                    <p class="text-muted mb-2">
                                        <i class="bi bi-geo-alt me-1"></i>
                                        <?php echo htmlspecialchars($casa['endereco']); ?>
                                    </p>
                                    
                                    <div class="property-features">
                                        <div class="feature-item">
                                            <i class="bi bi-door-open text-primary"></i>
                                            <span><?php echo $casa['quartos']; ?> quartos</span>
                                        </div>
                                        <div class="feature-item">
                                            <i class="bi bi-droplet text-primary"></i>
                                            <span><?php echo $casa['banheiros']; ?> banheiros</span>
                                        </div>
                                        <div class="feature-item">
                                            <i class="bi bi-arrows-fullscreen text-primary"></i>
                                            <span><?php echo $casa['area']; ?>m²</span>
                                        </div>
                                    </div>
                                    
                                    <p class="card-text flex-grow-1"><?php echo htmlspecialchars($casa['descricao']); ?></p>
                                    
                                    <div class="d-grid gap-2">
                                        <a href="agendar_visita.php?casa=<?php echo urlencode($casa['titulo']); ?>" 
                                           class="btn btn-primary">
                                            <i class="bi bi-calendar-check me-2"></i>
                                            Agendar Visita
                                        </a>
                                        <button class="btn btn-outline-primary" onclick="abrirWhatsApp('<?php echo htmlspecialchars($casa['titulo']); ?>')">
                                            <i class="bi bi-whatsapp me-2"></i>
                                            Contato via WhatsApp
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-light py-4 mt-5">
        <div class="container px-4 px-lg-5">
            <div class="small text-center text-muted">
                Copyright &copy; 2025 - <?php echo APP_NAME; ?>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function abrirWhatsApp(casa) {
            const telefone = '5511999999999'; // Substitua pelo número real
            const mensagem = `Olá! Tenho interesse no aluguel do imóvel: ${casa}. Gostaria de mais informações.`;
            const url = `https://wa.me/${telefone}?text=${encodeURIComponent(mensagem)}`;
            window.open(url, '_blank');
        }
    </script>
</body>
</html>
