<?php
/**
 * Página Meus Imóveis - Para Corretores
 */

// Incluir arquivo de inicialização do sistema
require_once __DIR__ . '/includes/init.php';

// Verificar se o usuário está logado e é corretor
$auth->requireLogin('Login.php');
$current_user = $auth->getCurrentUser();

// Verificar se é corretor
if ($current_user['user_type'] !== 'broker') {
    header('Location: index.php');
    exit;
}

// Buscar dados do corretor
$broker_stmt = $pdo->prepare("SELECT * FROM brokers WHERE user_id = ?");
$broker_stmt->execute([$current_user['id']]);
$broker_data = $broker_stmt->fetch();

if (!$broker_data) {
    // Se não encontrou dados do corretor, redirecionar
    header('Location: index.php');
    exit;
}

// Buscar imóveis do corretor
$properties_stmt = $pdo->prepare("
    SELECT p.*, 
           COUNT(pp.id) as total_purchases,
           COUNT(pr.id) as total_rentals,
           COUNT(pv.id) as total_visits
    FROM properties p 
    LEFT JOIN property_purchases pp ON p.id = pp.property_id
    LEFT JOIN property_rentals pr ON p.id = pr.property_id  
    LEFT JOIN property_visits pv ON p.id = pv.property_id
    WHERE p.broker_id = ?
    GROUP BY p.id
    ORDER BY p.created_at DESC
");
$properties_stmt->execute([$broker_data['id']]);
$broker_properties = $properties_stmt->fetchAll();

// Verificar mensagens flash
$flash = getFlashMessage();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Imóveis - <?php echo APP_NAME ?? 'Imobiliária'; ?></title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Ícones do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <!-- Fontes do Google -->
    <link href="https://fonts.googleapis.com/css?family=Merriweather+Sans:400,700" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic" rel="stylesheet" type="text/css" />
    <!-- Font Awesome para ícones sociais -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <!-- Tema principal CSS (inclui Bootstrap) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
</head>
<body>
    <?php
    require_once __DIR__ . '/includes/navbar.php';
    renderNavbar($current_user, 'meus_imoveis');
    ?>

    <?php
    // Exibir barra de informações do usuário
    require_once __DIR__ . '/includes/user_info.php';
    renderUserInfo($current_user);
    ?>

    <?php if ($flash): ?>
        <div class="container-fluid mt-3">
            <div class="alert alert-<?php echo $flash['type']; ?> alert-dismissible fade show" role="alert">
                <?php echo $flash['message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    <?php endif; ?>

    <!-- Hero Section -->
    <header class="masthead">
        <div class="container px-4 px-lg-5 h-100">
            <div class="row gx-4 gx-lg-5 h-100 align-items-center justify-content-center text-center">
                <div class="col-lg-8 align-self-end">
                    <h1 class="text-white font-weight-bold">Meus Imóveis</h1>
                    <hr class="divider" />
                </div>
                <div class="col-lg-8 align-self-baseline">
                    <p class="text-white-75 mb-5">Gerencie seus imóveis e acompanhe as transações.</p>
                </div>
            </div>
        </div>
    </header>

    <!-- Seção de Imóveis -->
    <section class="page-section" id="imoveis">
        <div class="container px-4 px-lg-5">
            <!-- Estatísticas Rápidas -->
            <div class="row mb-5">
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card bg-primary text-white h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h4 class="mb-0"><?php echo count($broker_properties); ?></h4>
                                    <p class="mb-0">Total de Imóveis</p>
                                </div>
                                <div class="ms-3">
                                    <i class="fas fa-building fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card bg-success text-white h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h4 class="mb-0"><?php echo array_sum(array_column($broker_properties, 'total_purchases')); ?></h4>
                                    <p class="mb-0">Vendas Realizadas</p>
                                </div>
                                <div class="ms-3">
                                    <i class="fas fa-handshake fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card bg-info text-white h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h4 class="mb-0"><?php echo array_sum(array_column($broker_properties, 'total_rentals')); ?></h4>
                                    <p class="mb-0">Aluguéis Ativos</p>
                                </div>
                                <div class="ms-3">
                                    <i class="fas fa-key fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card bg-warning text-white h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h4 class="mb-0"><?php echo array_sum(array_column($broker_properties, 'total_visits')); ?></h4>
                                    <p class="mb-0">Visitas Agendadas</p>
                                </div>
                                <div class="ms-3">
                                    <i class="fas fa-calendar-alt fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de Imóveis -->
            <div class="row">
                <div class="col-12 mb-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3>Seus Imóveis</h3>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPropertyModal">
                            <i class="fas fa-plus me-2"></i>Adicionar Imóvel
                        </button>
                    </div>
                </div>
            </div>

            <?php if (empty($broker_properties)): ?>
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle fa-3x mb-3"></i>
                            <h4>Nenhum imóvel cadastrado</h4>
                            <p>Você ainda não possui imóveis cadastrados. Clique no botão "Adicionar Imóvel" para começar.</p>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($broker_properties as $property): ?>
                        <div class="col-lg-6 col-md-12 mb-4">
                            <div class="card h-100 shadow">
                                <div class="card-header bg-<?php echo $property['status'] === 'active' ? 'success' : 'secondary'; ?> text-white">
                                    <h5 class="mb-0"><?php echo htmlspecialchars($property['title']); ?></h5>
                                    <span class="badge bg-light text-dark"><?php echo ucfirst($property['status']); ?></span>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <strong>Tipo:</strong> <?php echo ucfirst($property['property_type']); ?>
                                        </div>
                                        <div class="col-6">
                                            <strong>Área:</strong> <?php echo $property['area_sqm']; ?>m²
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <strong>Quartos:</strong> <?php echo $property['bedrooms']; ?>
                                        </div>
                                        <div class="col-6">
                                            <strong>Banheiros:</strong> <?php echo $property['bathrooms']; ?>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <strong>Endereço:</strong><br>
                                        <small><?php echo htmlspecialchars($property['address'] . ', ' . $property['neighborhood'] . ', ' . $property['city']); ?></small>
                                    </div>

                                    <div class="row mb-3">
                                        <?php if ($property['transaction_type'] === 'sale' || $property['transaction_type'] === 'both'): ?>
                                            <div class="col-6">
                                                <strong>Preço Venda:</strong><br>
                                                <span class="text-success">R$ <?php echo number_format($property['price'], 2, ',', '.'); ?></span>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if ($property['transaction_type'] === 'rent' || $property['transaction_type'] === 'both'): ?>
                                            <div class="col-6">
                                                <strong>Preço Aluguel:</strong><br>
                                                <span class="text-info">R$ <?php echo number_format($property['rent_price'] ?? 0, 2, ',', '.'); ?>/mês</span>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="row text-center mb-3">
                                        <div class="col-4">
                                            <span class="badge bg-primary"><?php echo $property['total_purchases']; ?> Vendas</span>
                                        </div>
                                        <div class="col-4">
                                            <span class="badge bg-success"><?php echo $property['total_rentals']; ?> Aluguéis</span>
                                        </div>
                                        <div class="col-4">
                                            <span class="badge bg-warning"><?php echo $property['total_visits']; ?> Visitas</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card-footer">
                                    <div class="btn-group w-100" role="group">
                                        <button type="button" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-edit me-1"></i>Editar
                                        </button>
                                        <button type="button" class="btn btn-outline-info btn-sm">
                                            <i class="fas fa-eye me-1"></i>Ver
                                        </button>
                                        <button type="button" class="btn btn-outline-danger btn-sm">
                                            <i class="fas fa-trash me-1"></i>Excluir
                                        </button>
                                    </div>
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
