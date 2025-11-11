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
               echo '<a href="perfil.php?id=' . $corretor['id'] . '" class="btn btn-info btn-sm me-1">Ver Perfil</a>';
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
                           echo '<li>';
                           echo '<a href="properties/view.php?id=' . $imovel['id'] . '" target="_blank">' . htmlspecialchars($imovel['title']) . '</a>';
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

    <!-- Gerenciador de Imóveis -->
    <div class="card mt-5">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Gerenciador de Imóveis dos Corretores</h4>
        </div>
        <div class="card-body">
            <div class="row row-cols-1 row-cols-md-3 g-4">
              
           <!-- imóvel 1 -->
             <div class="col">
                    <div class="card property-card" style="border-radius:12px;box-shadow:0 4px 16px rgba(0,0,0,0.08);">
                        <div class="position-relative overflow-hidden">
                            <div id="carouselCasa1" class="carousel slide" style="width:100%;margin:auto;">
                                <div class="carousel-inner" style="border-radius:10px;">
                                    <div class="carousel-item active"><img src="imgs/Casa1/Casa1.0.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Igrejinha 1"></div>
                                    <div class="carousel-item"><img src="imgs/Casa1/Casa1.1.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Igrejinha 2"></div>
                                    <div class="carousel-item"><img src="imgs/Casa1/Casa1.2.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Igrejinha 3"></div>
                                    <div class="carousel-item"><img src="imgs/Casa1/Casa1.3.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Igrejinha 3"></div>
                                    <div class="carousel-item"><img src="imgs/Casa1/Casa1.4.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Igrejinha 3"></div>
                                    <div class="carousel-item"><img src="imgs/Casa1/Casa1.5.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Igrejinha 3"></div>
                                    <div class="carousel-item"><img src="imgs/Casa1/Casa1.6.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Igrejinha 3"></div>
                                    <div class="carousel-item"><img src="imgs/Casa1/Casa1.7.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Igrejinha 3"></div>
                                    <div class="carousel-item"><img src="imgs/Casa1/Casa1.8.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Igrejinha 3"></div>
                                    <div class="carousel-item"><img src="imgs/Casa1/Casa1.9.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Igrejinha 3"></div>
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselCasa1" data-bs-slide="prev" style="width:32px;height:32px;top:50%;transform:translateY(-50%);">
                                    <span class="carousel-control-prev-icon" style="width:16px;height:16px;" aria-hidden="true"></span>
                                    <span class="visually-hidden">Anterior</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselCasa1" data-bs-slide="next" style="width:32px;height:32px;top:50%;transform:translateY(-50%);">
                                    <span class="carousel-control-next-icon" style="width:16px;height:16px;" aria-hidden="true"></span>
                                    <span class="visually-hidden">Próximo</span>
                                </button>
                            </div>
                        </div>
                        <div class="card-body text-center">
                            <h5 class="property-title" style="font-size:1.1rem;font-weight:600;color:#333;">Casa em Santo Antônio da Patrulha</h5>
                            <div class="property-price price-sale mb-2" style="font-size:1rem;font-weight:700;">Compra R$ 5.200.000,00 Aluguel R$ 2.200.000,00</div>
                            <div class="mb-2"><span class="badge bg-success rounded-pill">Disponível</span></div>
                            <div class="mb-2"><span class="badge bg-primary rounded-pill">João Silva</span></div>
                            <a href="Casas/Casa1.php" class="btn btn-details w-100 btn-sm" style="background:linear-gradient(45deg,#ff7b00,#ff9500);border:none;color:white;">Ver Detalhes</a>
                            
                        </div>
                    </div>
                </div> 
            
            
            <!-- imóvel 2 -->
             <div class="col">
                    <div class="card property-card" style="border-radius:12px;box-shadow:0 4px 16px rgba(0,0,0,0.08);">
                        <div class="position-relative overflow-hidden">
                            <div id="carouselCasa2" class="carousel slide" style="width:100%;margin:auto;">
                                <div class="carousel-inner" style="border-radius:10px;">
                                    <div class="carousel-item active"><img src="imgs/Casa2/Casa2.0.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Igrejinha 1"></div>
                                    <div class="carousel-item"><img src="imgs/Casa2/Casa2.1.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Igrejinha 2"></div>
                                    <div class="carousel-item"><img src="imgs/Casa2/Casa2.2.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Igrejinha 3"></div>
                                    <div class="carousel-item"><img src="imgs/Casa2/Casa2.3.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Igrejinha 3"></div>
                                    <div class="carousel-item"><img src="imgs/Casa2/Casa2.4.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Igrejinha 3"></div>
                                    <div class="carousel-item"><img src="imgs/Casa2/Casa2.5.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Igrejinha 3"></div>
                                    <div class="carousel-item"><img src="imgs/Casa2/Casa2.6.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Igrejinha 3"></div>
                                    <div class="carousel-item"><img src="imgs/Casa2/Casa2.7.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Igrejinha 3"></div>
                                    <div class="carousel-item"><img src="imgs/Casa2/Casa2.8.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Igrejinha 3"></div>
                                    <div class="carousel-item"><img src="imgs/Casa2/Casa2.9.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Igrejinha 3"></div>
                                    <div class="carousel-item"><img src="imgs/Casa2/Casa2.10.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Igrejinha 3"></div>
                                    <div class="carousel-item"><img src="imgs/Casa2/Casa2.11.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Igrejinha 3"></div>
                                    <div class="carousel-item"><img src="imgs/Casa2/Casa2.12.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Igrejinha 3"></div>
                                    <div class="carousel-item"><img src="imgs/Casa2/Casa2.13.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Igrejinha 3"></div>
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselCasa2" data-bs-slide="prev" style="width:32px;height:32px;top:50%;transform:translateY(-50%);">
                                    <span class="carousel-control-prev-icon" style="width:16px;height:16px;" aria-hidden="true"></span>
                                    <span class="visually-hidden">Anterior</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselCasa2" data-bs-slide="next" style="width:32px;height:32px;top:50%;transform:translateY(-50%);">
                                    <span class="carousel-control-next-icon" style="width:16px;height:16px;" aria-hidden="true"></span>
                                    <span class="visually-hidden">Próximo</span>
                                </button>
                            </div>
                        </div>
                        <div class="card-body text-center">
                            <h5 class="property-title" style="font-size:1.1rem;font-weight:600;color:#333;">Casa em Taquara</h5>
                            <div class="property-price price-sale mb-2" style="font-size:1rem;font-weight:700;">Aluguel R$ 1.500,00</div>
                            <div class="mb-2"><span class="badge bg-success rounded-pill">Disponível</span></div>
                            <div class="mb-2"><span class="badge bg-primary rounded-pill">João Silva</span></div>
                            <a href="Casas/Casa2.php" class="btn btn-details w-100 btn-sm" style="background:linear-gradient(45deg,#ff7b00,#ff9500);border:none;color:white;">Ver Detalhes</a>
                           
                        </div>
                    </div>
                </div>   
            <!-- Imóvel 3 -->
          <div class="col">
                    <div class="card property-card" style="border-radius:12px;box-shadow:0 4px 16px rgba(0,0,0,0.08);">
                        <div class="position-relative overflow-hidden">
                            <div id="carouselCasa3" class="carousel slide" style="width:100%;margin:auto;">
                                <div class="carousel-inner" style="border-radius:10px;">
                                    <div class="carousel-item active"><img src="imgs/Casa3/Casa3.0.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Igrejinha 1"></div>
                                    <div class="carousel-item"><img src="imgs/Casa3/Casa3.1.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Igrejinha 2"></div>
                                    <div class="carousel-item"><img src="imgs/Casa3/Casa3.2.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Igrejinha 3"></div>
                                    <div class="carousel-item"><img src="imgs/Casa3/Casa3.3.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Igrejinha 3"></div>
                                    <div class="carousel-item"><img src="imgs/Casa3/Casa3.4.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Igrejinha 3"></div>
                                    <div class="carousel-item"><img src="imgs/Casa3/Casa3.5.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Igrejinha 3"></div>
                                    <div class="carousel-item"><img src="imgs/Casa3/Casa3.6.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Igrejinha 3"></div>
                                    <div class="carousel-item"><img src="imgs/Casa3/Casa3.7.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Igrejinha 3"></div>
                                    <div class="carousel-item"><img src="imgs/Casa3/Casa3.8.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Igrejinha 3"></div>
                                    <div class="carousel-item"><img src="imgs/Casa3/Casa3.9.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Igrejinha 3"></div>
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselCasa3" data-bs-slide="prev" style="width:32px;height:32px;top:50%;transform:translateY(-50%);">
                                    <span class="carousel-control-prev-icon" style="width:16px;height:16px;" aria-hidden="true"></span>
                                    <span class="visually-hidden">Anterior</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselCasa3" data-bs-slide="next" style="width:32px;height:32px;top:50%;transform:translateY(-50%);">
                                    <span class="carousel-control-next-icon" style="width:16px;height:16px;" aria-hidden="true"></span>
                                    <span class="visually-hidden">Próximo</span>
                                </button>
                            </div>
                        </div>
                        <div class="card-body text-center">
                            <h5 class="property-title" style="font-size:1.1rem;font-weight:600;color:#333;">Casa em Taquara Alto Padrão</h5>
                            <div class="property-price price-sale mb-2" style="font-size:1rem;font-weight:700;">Compra R$ 3.000.000,00</div>
                            <div class="mb-2"><span class="badge bg-success rounded-pill">Disponível</span></div>
                            <div class="mb-2"><span class="badge bg-primary rounded-pill">João Silva</span></div>
                            <a href="Casas/Casa3.php" class="btn btn-details w-100 btn-sm" style="background:linear-gradient(45deg,#ff7b00,#ff9500);border:none;color:white;">Ver Detalhes</a>

                        </div>
                    </div>
                </div>
               <!-- Imóvel 4 -->
                <div class="col">
                    <div class="card property-card" style="border-radius:12px;box-shadow:0 4px 16px rgba(0,0,0,0.08);">
                        <div class="position-relative overflow-hidden">
                            <div id="carouselCasa4" class="carousel slide" style="width:100%;margin:auto;">
                                <div class="carousel-inner" style="border-radius:10px;">
                                    <div class="carousel-item active"><img src="imgs/Casa4/Casa4.0.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Igrejinha 1"></div>
                                    <div class="carousel-item"><img src="imgs/Casa4/Casa4.2.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Igrejinha 3"></div>
                                    <div class="carousel-item"><img src="imgs/Casa4/Casa4.3.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Igrejinha 3"></div>
                                    <div class="carousel-item"><img src="imgs/Casa4/Casa4.4.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Igrejinha 3"></div>
                                    <div class="carousel-item"><img src="imgs/Casa4/Casa4.5.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Igrejinha 3"></div>
                                    <div class="carousel-item"><img src="imgs/Casa4/Casa4.6.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Igrejinha 3"></div>
                                    <div class="carousel-item"><img src="imgs/Casa4/Casa4.7.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Igrejinha 3"></div>
                                    <div class="carousel-item"><img src="imgs/Casa4/Casa4.8.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Igrejinha 3"></div>
                                    <div class="carousel-item"><img src="imgs/Casa4/Casa4.9.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Igrejinha 3"></div>
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselCasa4" data-bs-slide="prev" style="width:32px;height:32px;top:50%;transform:translateY(-50%);">
                                    <span class="carousel-control-prev-icon" style="width:16px;height:16px;" aria-hidden="true"></span>
                                    <span class="visually-hidden">Anterior</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselCasa4" data-bs-slide="next" style="width:32px;height:32px;top:50%;transform:translateY(-50%);">
                                    <span class="carousel-control-next-icon" style="width:16px;height:16px;" aria-hidden="true"></span>
                                    <span class="visually-hidden">Próximo</span>
                                </button>
                            </div>
                        </div>
                        <div class="card-body text-center">
                            <h5 class="property-title" style="font-size:1.1rem;font-weight:600;color:#333;">Casa em Taquara Rua Mundo Novo</h5>
                            <div class="property-price price-sale mb-2" style="font-size:1rem;font-weight:700;">Compra R$ 170.000,00 Aluguel R$ 1.000,00</div>
                            <div class="mb-2"><span class="badge bg-success rounded-pill">Disponível</span></div>
                            <div class="mb-2"><span class="badge bg-primary rounded-pill">Maria Souza</span></div>
                            <a href="Casas/Casa4.php" class="btn btn-details w-100 btn-sm" style="background:linear-gradient(45deg,#ff7b00,#ff9500);border:none;color:white;">Ver Detalhes</a>
                            
                        </div>
                    </div>
                </div>
                <!-- Imóvel 5 -->
                <div class="col">
                    <div class="card property-card" style="border-radius:12px;box-shadow:0 4px 16px rgba(0,0,0,0.08);">
                        <div class="position-relative overflow-hidden">
                            <div id="carouselCasa5" class="carousel slide" style="width:100%;margin:auto;">
                                <div class="carousel-inner" style="border-radius:10px;">
                                    <div class="carousel-item active"><img src="imgs/Casa5/Casa5.0.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Igrejinha 1"></div>
                                    <div class="carousel-item"><img src="imgs/Casa5/Casa5.1.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Igrejinha 2"></div>
                                    <div class="carousel-item"><img src="imgs/Casa5/Casa5.2.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Igrejinha 3"></div>
                                    <div class="carousel-item"><img src="imgs/Casa5/Casa5.3.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Igrejinha 3"></div>
                                    <div class="carousel-item"><img src="imgs/Casa5/Casa5.4.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Igrejinha 3"></div>
                                    <div class="carousel-item"><img src="imgs/Casa5/Casa5.5.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Igrejinha 3"></div>
                                    <div class="carousel-item"><img src="imgs/Casa5/Casa5.6.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Igrejinha 3"></div>
                                    <div class="carousel-item"><img src="imgs/Casa5/Casa5.7.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Igrejinha 3"></div>
                                    <div class="carousel-item"><img src="imgs/Casa5/Casa5.8.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Igrejinha 3"></div>
                                    <div class="carousel-item"><img src="imgs/Casa5/Casa5.9.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Igrejinha 3"></div>
                                    <div class="carousel-item"><img src="imgs/Casa5/Casa5.10.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Igrejinha 3"></div>
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselCasa5" data-bs-slide="prev" style="width:32px;height:32px;top:50%;transform:translateY(-50%);">
                                    <span class="carousel-control-prev-icon" style="width:16px;height:16px;" aria-hidden="true"></span>
                                    <span class="visually-hidden">Anterior</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselCasa5" data-bs-slide="next" style="width:32px;height:32px;top:50%;transform:translateY(-50%);">
                                    <span class="carousel-control-next-icon" style="width:16px;height:16px;" aria-hidden="true"></span>
                                    <span class="visually-hidden">Próximo</span>
                                </button>
                            </div>
                        </div>
                        <div class="card-body text-center">
                            <h5 class="property-title" style="font-size:1.1rem;font-weight:600;color:#333;">Casa em Taquara Rua Flores da Cunha</h5>
                            <div class="property-price price-sale mb-2" style="font-size:1rem;font-weight:700;">Compra R$ 380.000,00 Aluguel R$ 900,00</div>
                            <div class="mb-2"><span class="badge bg-success rounded-pill">Disponível</span></div>
                            <div class="mb-2"><span class="badge bg-primary rounded-pill">Maria Souza</span></div>
                            <a href="Casas/Casa5.php" class="btn btn-details w-100 btn-sm" style="background:linear-gradient(45deg,#ff7b00,#ff9500);border:none;color:white;">Ver Detalhes</a>
                            
                        </div>
                    </div>
                </div>
                <!-- Imóvel 6 -->
                <div class="col">
                    <div class="card property-card" style="border-radius:12px;box-shadow:0 4px 16px rgba(0,0,0,0.08);">
                        <div class="position-relative overflow-hidden">
                            <div id="carouselCasa6" class="carousel slide" style="width:100%;margin:auto;">
                                <div class="carousel-inner" style="border-radius:10px;">
                                    <div class="carousel-item active"><img src="imgs/Casa6/Casa6.0.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Rolante 1"></div>
                                    <div class="carousel-item"><img src="imgs/Casa6/Casa6.1.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Rolante 2"></div>
                                    <div class="carousel-item"><img src="imgs/Casa6/Casa6.2.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Rolante 3"></div>
                                    <div class="carousel-item"><img src="imgs/Casa6/Casa6.3.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Rolante 3"></div>
                                    <div class="carousel-item"><img src="imgs/Casa6/Casa6.4.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Rolante 3"></div>
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselCasa6" data-bs-slide="prev" style="width:32px;height:32px;top:50%;transform:translateY(-50%);">
                                    <span class="carousel-control-prev-icon" style="width:16px;height:16px;" aria-hidden="true"></span>
                                    <span class="visually-hidden">Anterior</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselCasa6" data-bs-slide="next" style="width:32px;height:32px;top:50%;transform:translateY(-50%);">
                                    <span class="carousel-control-next-icon" style="width:16px;height:16px;" aria-hidden="true"></span>
                                    <span class="visually-hidden">Próximo</span>
                                </button>
                            </div>
                        </div>
                        <div class="card-body text-center">
                            <h5 class="property-title" style="font-size:1.1rem;font-weight:600;color:#333;">Casa em Parobé</h5>
                            <div class="property-price price-sale mb-2" style="font-size:1rem;font-weight:700;">Compra R$ 195.000,00 Aluguel R$ 1.000,00</div>
                            <div class="mb-2"><span class="badge bg-success rounded-pill">Disponível</span></div>
                            <div class="mb-2"><span class="badge bg-primary rounded-pill">Maria Souza</span></div>
                            <a href="Casas/Casa6.php" class="btn btn-details w-100 btn-sm" style="background:linear-gradient(45deg,#ff7b00,#ff9500);border:none;color:white;">Ver Detalhes</a>
                           
                        </div>
                    </div>
                </div>
                <!-- Imóvel 7 -->
                <div class="col">
                    <div class="card property-card" style="border-radius:12px;box-shadow:0 4px 16px rgba(0,0,0,0.08);">
                        <div class="position-relative overflow-hidden">
                            <div id="carouselCasa7" class="carousel slide" style="width:100%;margin:auto;">
                                <div class="carousel-inner" style="border-radius:10px;">
                                    <div class="carousel-item active"><img src="imgs/Casa7/Casa7.0.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Sapiranga 1"></div>
                                    <div class="carousel-item"><img src="imgs/Casa7/Casa7.1.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Sapiranga 2"></div>
                                    <div class="carousel-item"><img src="imgs/Casa7/Casa7.2.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Sapiranga 3"></div>
                                    <div class="carousel-item"><img src="imgs/Casa7/Casa7.3.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Sapiranga 4"></div>   
                                    <div class="carousel-item"><img src="imgs/Casa7/Casa7.4.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Sapiranga 5"></div>
                                    <div class="carousel-item"><img src="imgs/Casa7/Casa7.5.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Sapiranga 6"></div>            
                                    <div class="carousel-item"><img src="imgs/Casa7/Casa7.6.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Sapiranga 6"></div>
                                    <div class="carousel-item"><img src="imgs/Casa7/Casa7.7.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Sapiranga 6"></div>
                                    <div class="carousel-item"><img src="imgs/Casa7/Casa7.8.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Sapiranga 6"></div>
                                    <div class="carousel-item"><img src="imgs/Casa7/Casa7.9.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Sapiranga 6"></div>
                                    <div class="carousel-item"><img src="imgs/Casa7/Casa7.10.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Sapiranga 6"></div>
                                    <div class="carousel-item"><img src="imgs/Casa7/Casa7.11.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Sapiranga 6"></div>
                                    <div class="carousel-item"><img src="imgs/Casa7/Casa7.12.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Sapiranga 6"></div>
                                    <div class="carousel-item"><img src="imgs/Casa7/Casa7.13.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Sapiranga 6"></div>
                                    <div class="carousel-item"><img src="imgs/Casa7/Casa7.14.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Sapiranga 6"></div>
                                    <div class="carousel-item"><img src="imgs/Casa7/Casa7.15.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Sapiranga 6"></div>
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselCasa7" data-bs-slide="prev" style="width:32px;height:32px;top:50%;transform:translateY(-50%);">
                                    <span class="carousel-control-prev-icon" style="width:16px;height:16px;" aria-hidden="true"></span>
                                    <span class="visually-hidden">Anterior</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselCasa7" data-bs-slide="next" style="width:32px;height:32px;top:50%;transform:translateY(-50%);">
                                    <span class="carousel-control-next-icon" style="width:16px;height:16px;" aria-hidden="true"></span>
                                    <span class="visually-hidden">Próximo</span>
                                </button>
                            </div>
                        </div>
                        <div class="card-body text-center">
                            <h5 class="property-title" style="font-size:1.1rem;font-weight:600;color:#333;">Casa em Taquara Bairro Santa Terezinha</h5>
                            <div class="property-price price-sale mb-2" style="font-size:1rem;font-weight:700;">Compra R$ 650.000,00 Aluguel R$ 2.000,00</div>
                            <div class="mb-2"><span class="badge bg-success rounded-pill">Disponível</span></div>
                            <div class="mb-2"><span class="badge bg-primary rounded-pill">Pedro Costa</span></div>
                            <a href="Casas/casa7.php" class="btn btn-details w-100 btn-sm" style="background:linear-gradient(45deg,#ff7b00,#ff9500);border:none;color:white;">Ver Detalhes</a>
                           
                        </div>
                    </div>
                </div>
                <!-- Imóvel 8 -->
                <div class="col">
                    <div class="card property-card" style="border-radius:12px;box-shadow:0 4px 16px rgba(0,0,0,0.08);">
                        <div class="position-relative overflow-hidden">
                            <div id="carouselCasa8" class="carousel slide" style="width:100%;margin:auto;">
                                <div class="carousel-inner" style="border-radius:10px;">
                                    <div class="carousel-item active"><img src="imgs/Casa8/Casa8.0.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Taquara - Bairro Tucanos 1"></div>
                                    <div class="carousel-item"><img src="imgs/Casa8/Casa8.1.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Taquara - Bairro Tucanos 2"></div>
                                    <div class="carousel-item"><img src="imgs/Casa8/Casa8.2.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Taquara - Bairro Tucanos 3"></div>
                                    <div class="carousel-item"><img src="imgs/Casa8/Casa8.3.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Taquara - Bairro Tucanos 4"></div>
                                    <div class="carousel-item"><img src="imgs/Casa8/Casa8.4.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Taquara - Bairro Tucanos 5"></div>
                                    <div class="carousel-item"><img src="imgs/Casa8/Casa8.5.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Taquara - Bairro Tucanos 6"></div>
                                    <div class="carousel-item"><img src="imgs/Casa8/Casa8.6.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Taquara - Bairro Tucanos 7"></div>
                                    <div class="carousel-item"><img src="imgs/Casa8/Casa8.7.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Taquara - Bairro Tucanos 8"></div>
                                    <div class="carousel-item"><img src="imgs/Casa8/Casa8.8.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Taquara - Bairro Tucanos 9"></ 
                                    <div class="carousel-item"><img src="imgs/Casa8/Casa8.9.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Taquara - Bairro Tucanos 10"></div>
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselCasa8" data-bs-slide="prev" style="width:32px;height:32px;top:50%;transform:translateY(-50%);">
                                    <span class="carousel-control-prev-icon" style="width:16px;height:16px;" aria-hidden="true"></span>
                                    <span class="visually-hidden">Anterior</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselCasa8" data-bs-slide="next" style="width:32px;height:32px;top:50%;transform:translateY(-50%);">
                                    <span class="carousel-control-next-icon" style="width:16px;height:16px;" aria-hidden="true"></span>
                                    <span class="visually-hidden">Próximo</span>
                                </button>
                            </div>
                        </div>
                        <div class="card-body text-center">
                            <h5 class="property-title" style="font-size:1.1rem;font-weight:600;color:#333;">Casa em Taquara - Bairro Tucanos</h5>
                            <div class="property-price price-sale mb-2" style="font-size:1rem;font-weight:700;">Compra R$ 184.000,00</div>
                            <div class="mb-2"><span class="badge bg-success rounded-pill">Disponível</span></div>
                            <div class="mb-2"><span class="badge bg-primary rounded-pill">Pedro Costa</span></div>
                            <a href="Casas/Casa8.php" class="btn btn-details w-100 btn-sm" style="background:linear-gradient(45deg,#ff7b00,#ff9500);border:none;color:white;">Ver Detalhes</a>
                            
                        </div>
                    </div>
                </div>
                <!-- Imóvel 9 -->
                <div class="col">
                    <div class="card property-card" style="border-radius:12px;box-shadow:0 4px 16px rgba(0,0,0,0.08);">
                        <div class="position-relative overflow-hidden">
                            <div id="carouselCasa9" class="carousel slide" style="width:100%;margin:auto;">
                                <div class="carousel-inner" style="border-radius:10px;">
                                    <div class="carousel-item active"><img src="imgs/Casa9/Casa9.0.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Taquara"></div>
                                    <div class="carousel-item"><img src="imgs/Casa9/Casa9.1.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa "></div>
                                    <div class="carousel-item"><img src="imgs/Casa9/Casa9.2.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa "></div>
                                    <div class="carousel-item"><img src="imgs/Casa9/Casa9.3.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa "></div>
                                    <div class="carousel-item"><img src="imgs/Casa9/Casa9.4.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa "></div>
                                    <div class="carousel-item"><img src="imgs/Casa9/Casa9.5.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa "></div>
                                    <div class="carousel-item"><img src="imgs/Casa9/Casa9.6.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa "></div>
                                    <div class="carousel-item"><img src="imgs/Casa9/Casa9.7.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa "></div>
                                    <div class="carousel-item"><img src="imgs/Casa9/Casa9.8.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa "></div>
                                    <div class="carousel-item"><img src="imgs/Casa9/Casa9.9.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa "></div>
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselCasa9" data-bs-slide="prev" style="width:32px;height:32px;top:50%;transform:translateY(-50%);">
                                    <span class="carousel-control-prev-icon" style="width:16px;height:16px;" aria-hidden="true"></span>
                                    <span class="visually-hidden">Anterior</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselCasa9" data-bs-slide="next" style="width:32px;height:32px;top:50%;transform:translateY(-50%);">
                                    <span class="carousel-control-next-icon" style="width:16px;height:16px;" aria-hidden="true"></span>
                                    <span class="visually-hidden">Próximo</span>
                                </button>
                            </div>
                        </div>
                        <div class="card-body text-center">
                            <h5 class="property-title" style="font-size:1.1rem;font-weight:600;color:#333;">Casa em Taquara - Rua São Francisco</h5>
                            <div class="property-price price-sale mb-2" style="font-size:1rem;font-weight:700;">Compra R$ 450.000,00</div>
                            <div class="mb-2"><span class="badge bg-success rounded-pill">Disponível</span></div>
                            <div class="mb-2"><span class="badge bg-primary rounded-pill">Pedro Costa</span></div>
                            <a href="Casas/Casa9.php" class="btn btn-details w-100 btn-sm" style="background:linear-gradient(45deg,#ff7b00,#ff9500);border:none;color:white;">Ver Detalhes</a>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="js/admin.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Todos os carrosséis funcionam igual ao carrossel 2 (comportamento padrão do Bootstrap)
        document.addEventListener('DOMContentLoaded', function() {
            // Sem interferências - deixa o Bootstrap gerenciar todos os carrosséis normalmente
            console.log('Carrosséis iniciados com comportamento padrão');
        });
    </script>
</body>
</html>
