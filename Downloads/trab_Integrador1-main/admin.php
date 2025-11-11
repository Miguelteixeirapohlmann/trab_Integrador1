<?php
/**
 * Página de Administração
 */

require_once 'includes/init.php';

// Verificar se o usuário está logado e é admin
if (!$auth->isLoggedIn()) {
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header('Location: Login.php');
    exit;
}

$current_user = $auth->getCurrentUser();

// Verificar se é admin
if (!$current_user || $current_user['user_type'] !== 'admin') {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Administração</title>
    <link href="css/styles.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>

        .carousel-item {
            transition: none !important;
        }
        .carousel.slide .carousel-item {
            transform: translateX(0) !important;
        }
        .carousel-inner .carousel-item.active,
        .carousel-inner .carousel-item-next,
        .carousel-inner .carousel-item-prev {
            transition: none !important;
        }
        
        /* Navbar Admin - texto branco */
        .navbar-brand {
            color: white !important;
        }
        .navbar-brand.text-white {
            color: white !important;
        }
        .nav-link {
            color: white !important;
        }
        .navbar-nav .nav-link {
            color: white !important;
        }
        .navbar-nav .nav-link:hover {
            color: #f8f9fa !important;
        }
        .dropdown-toggle {
            color: white !important;
        }
        /* Força texto branco no botão Administração */
        nav.navbar-dark .navbar-brand {
            color: white !important;
        }
    </style>
</head>
<body>

    <?php 
    if (function_exists('renderNavbar')) {
        renderNavbar($current_user, 'admin');
    } else {

        ?>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark"></nav>
    <?php } ?>
    <div class="container my-5 pb-4">
        <h2 class="mb-4">Gerenciar Corretores</h2>
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Contas de Corretores</span>
                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalCorretor" onclick="openCorretorModal()"><i class="fas fa-plus"></i> Novo Corretor</button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped mb-0" id="corretoresTable">
                        <thead class="table-dark">
                            <tr>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Ações</th>
                                <th>Imóveis</th>
                            </tr>
                        </thead>
                        <tbody>
           <?php
           // Buscar todos os corretores
           $stmt = $pdo->prepare("SELECT u.id, u.first_name, u.last_name, u.email, u.status, b.id as broker_id FROM users u LEFT JOIN brokers b ON u.id = b.user_id WHERE u.user_type = 'broker'");
           $stmt->execute();
           $corretores = $stmt->fetchAll();
           foreach ($corretores as $corretor) {
               echo '<tr>';
               echo '<td>' . htmlspecialchars($corretor['first_name'] . ' ' . $corretor['last_name']) . '</td>';
               echo '<td>' . htmlspecialchars($corretor['email']) . '</td>';
               echo '<td><span class="badge ' . ($corretor['status'] === 'active' ? 'bg-success' : 'bg-secondary') . '">' . ($corretor['status'] === 'active' ? 'Ativo' : 'Desabilitado') . '</span></td>';
               echo '<td>';
               // Botão para perfil do corretor
               echo '<a href="perfil.php?id=' . $corretor['id'] . '" class="btn btn-info btn-sm me-1">Ver Perfil</a>';
               // Botão para imóveis do corretor
               if ($corretor['broker_id']) {
                   echo '<a href="gerenciar_imoveis.php?id=' . $corretor['broker_id'] . '" class="btn btn-primary btn-sm">Ver Imóveis</a>';
               } else {
                   echo '<button class="btn btn-secondary btn-sm" disabled>Ver Imóveis</button>';
               }
               echo '</td>';
               echo '<td>';
               // Listar imóveis do corretor
               if ($corretor['broker_id']) {
                   $stmt2 = $pdo->prepare("SELECT id, title, price FROM properties WHERE broker_id = ?");
                   $stmt2->execute([$corretor['broker_id']]);
                   $imoveis = $stmt2->fetchAll();
                   if ($imoveis) {
                       echo '<ul class="list-unstyled mb-0">';
                       foreach ($imoveis as $imovel) {
                           // Tenta encontrar o arquivo da casa pelo id
                           $casaFile = 'Casas/Casa' . $imovel['id'] . '.php';
                           if (!file_exists($casaFile)) {
                               $casaFile = 'properties/view.php?id=' . $imovel['id'];
                           }
                           echo '<li>';
                           echo '<a href="' . $casaFile . '" target="_blank">' . htmlspecialchars($imovel['title']) . '</a>';
                           echo ' <span class="text-muted">R$ ' . number_format($imovel['price'], 2, ',', '.') . '</span>';
                           echo '</li>';
                       }
                       echo '</ul>';
                   } else {
                       echo '<span class="text-muted">Nenhum imóvel</span>';
                   }
               } else {
                   echo '<span class="text-muted">Nenhum imóvel</span>';
               }
               echo '</td>';
               echo '</tr>';
           }
           ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalCorretor" tabindex="-1" aria-labelledby="modalCorretorLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <form id="corretorForm">
                <div class="modal-header">
                  <h5 class="modal-title" id="modalCorretorLabel">Novo Corretor</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                                <div class="modal-body">
                                    <input type="hidden" id="corretorId">
                                    <div class="mb-3">
                                        <label for="corretorNome" class="form-label">Nome do Corretor</label>
                                        <input type="text" class="form-control" id="corretorNome" name="corretorNome" placeholder="Digite o nome completo" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="corretorEmail" class="form-label">E-mail</label>
                                        <input type="email" class="form-control" id="corretorEmail" name="corretorEmail" placeholder="exemplo@email.com" required>
                                    </div>
                </div>
              </form>
            </div>
          </div>
        </div>

    <!-- Gerenciador de Imóveis Dinâmico -->
    <div class="card mt-5">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Gerenciador de Imóveis dos Corretores</h4>
        </div>
        <div class="card-body">
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php
                require_once 'models/Property.php';
                $propertyModel = new Property();
                $stmt = $pdo->prepare("SELECT b.id as broker_id, u.first_name, u.last_name FROM brokers b LEFT JOIN users u ON b.user_id = u.id");
                $stmt->execute();
                $brokers = $stmt->fetchAll();
                foreach ($brokers as $broker) {
                    $properties = $propertyModel->getAll(1, 100, ['broker_id' => $broker['broker_id']]);
                    foreach ($properties['data'] as $property) {
                        $images = isset($property['images']) && is_array($property['images']) ? $property['images'] : [];
                        ?>
                        <div class="col">
                            <div class="card property-card" style="border-radius:12px;box-shadow:0 4px 16px rgba(0,0,0,0.08);">
                                <div class="position-relative overflow-hidden">
                                    <div id="carouselProperty<?php echo $property['id']; ?>" class="carousel slide" style="width:100%;margin:auto;">
                                        <div class="carousel-inner" style="border-radius:10px;">
                                            <?php foreach ($images as $idx => $img): ?>
                                                <div class="carousel-item<?php echo $idx === 0 ? ' active' : ''; ?>">
                                                    <img src="<?php echo htmlspecialchars($img); ?>" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="<?php echo htmlspecialchars($property['title']); ?>">
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <?php if (count($images) > 1): ?>
                                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselProperty<?php echo $property['id']; ?>" data-bs-slide="prev" style="width:32px;height:32px;top:50%;transform:translateY(-50%);">
                                                <span class="carousel-control-prev-icon" style="width:16px;height:16px;" aria-hidden="true"></span>
                                                <span class="visually-hidden">Anterior</span>
                                            </button>
                                            <button class="carousel-control-next" type="button" data-bs-target="#carouselProperty<?php echo $property['id']; ?>" data-bs-slide="next" style="width:32px;height:32px;top:50%;transform:translateY(-50%);">
                                                <span class="carousel-control-next-icon" style="width:16px;height:16px;" aria-hidden="true"></span>
                                                <span class="visually-hidden">Próximo</span>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="card-body text-center">
                                    <h5 class="property-title" style="font-size:1.1rem;font-weight:600;color:#333;"><?php echo htmlspecialchars($property['title']); ?></h5>
                                    <div class="property-price price-sale mb-2" style="font-size:1rem;font-weight:700;">
                                        <?php if ($property['transaction_type'] === 'sale' || $property['transaction_type'] === 'both'): ?>
                                            Compra <?php echo formatCurrency($property['price']); ?>
                                        <?php endif; ?>
                                        <?php if ($property['transaction_type'] === 'rent' || $property['transaction_type'] === 'both'): ?>
                                            <?php if ($property['transaction_type'] === 'both'): ?> | <?php endif; ?>
                                            Aluguel <?php echo formatCurrency($property['rent_price']); ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="mb-2"><span class="badge bg-success rounded-pill"><?php echo ucfirst($property['status']); ?></span></div>
                                    <div class="mb-2"><span class="badge bg-primary rounded-pill"><?php echo htmlspecialchars($broker['first_name'] . ' ' . $broker['last_name']); ?></span></div>
                                    <a href="properties/view.php?id=<?php echo $property['id']; ?>" class="btn btn-details w-100 btn-sm" style="background:linear-gradient(45deg,#ff7b00,#ff9500);border:none;color:white;">Ver Detalhes</a>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>
