<?php
// Corrige: inicialização antes de usar $pdo
require_once __DIR__ . '/includes/init.php';
// Processa alteração de status do corretor
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['broker_id'], $_POST['status'])) {
    $bid = intval($_POST['broker_id']);
    $newStatus = $_POST['status'] === 'active' ? 'active' : 'inactive';
    $stmtStatus = $pdo->prepare('UPDATE users SET status = ? WHERE id = ? AND user_type = "broker"');
    $stmtStatus->execute([$newStatus, $bid]);
}
/**
 * Página de Administração
 */
if (!$auth->isLoggedIn()) {
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'] ?? 'admin.php';
    header('Location: Login.php');
    exit;
}
$current_user = $auth->getCurrentUser();
if (!$current_user || ($current_user['user_type'] ?? '') !== 'admin') {
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <style>
        .carousel-item { transition: none !important; }
        .carousel.slide .carousel-item { transform: translateX(0) !important; }
        .carousel-inner .carousel-item.active,
        .carousel-inner .carousel-item-next,
        .carousel-inner .carousel-item-prev { transition: none !important; }
        .navbar-brand, .nav-link, .dropdown-toggle { color: white !important; }
        nav.navbar-dark .navbar-brand { color: white !important; }
    </style>
</head>
<body>
<?php if (function_exists('renderNavbar')) { renderNavbar($current_user, 'admin'); } else { ?>
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
               echo '<td>';
               echo '<form method="post" action="" style="display:inline-block;min-width:120px;">';
               echo '<input type="hidden" name="broker_id" value="' . intval($corretor['id']) . '">';
               echo '<select name="status" class="form-select form-select-sm d-inline w-auto" onchange="this.form.submit()">';
               echo '<option value="active"' . ($corretor['status'] === 'active' ? ' selected' : '') . '>Ativo</option>';
               echo '<option value="inactive"' . ($corretor['status'] === 'inactive' ? ' selected' : '') . '>Desativado</option>';
               echo '</select>';
               echo '</form>';
               echo '</td>';
               echo '<td>';
               // Botão para perfil do corretor
               echo '<a href="perfil.php?id=' . $corretor['id'] . '" class="btn btn-info btn-sm me-1">Ver Perfil</a>';
               echo '<td>';
               // Listar imóveis do corretor
               $brokerId = $corretor['broker_id'] ? intval($corretor['broker_id']) : intval($corretor['id']);
               $stmt2 = $pdo->prepare("SELECT id, title, price FROM properties WHERE broker_id = ?");
               $stmt2->execute([$corretor['broker_id']]);
               $imoveis = $stmt2->fetchAll();
               if ($imoveis) {
                   echo '<ul class="list-unstyled mb-0">';
                   foreach ($imoveis as $imovel) {
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
               }
               // Sempre mostra o botão
               echo '<div class="mt-2">'
                   . '<a href="gerenciar_imoveis.php?id=' . $brokerId . '" '
                   . 'class="btn btn-outline-primary btn-sm w-100">Ver todos os imóveis</a>'
                   . '</div>';
               echo '</td>';
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

    <?php /* Cards das Casas (embaixo) removido */ ?>
        <div class="card mt-5 d-none">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Cards das Casas</h4>
            </div>
            <div class="card-body">
                <div class="row row-cols-1 row-cols-md-3 g-4">
 
                    <?php
                    require_once 'models/Property.php';
                    $propertyModel = new Property();
                    $stmt = $pdo->prepare("SELECT b.id as broker_id, u.first_name, u.last_name FROM brokers b LEFT JOIN users u ON b.user_id = u.id");
                    $stmt->execute();
                    $brokers = $stmt->fetchAll();
                    foreach ($brokers as $broker):
                        $properties = $propertyModel->getAll(1, 100, ['broker_id' => $broker['broker_id']]);
                        foreach ($properties['data'] as $property):
                            $images = $propertyModel->getImages($property['id']);
                            if (empty($images) && !empty($property['primary_image'])) {
                                $images = [$property['primary_image']];
                            }
                            $hasImages = !empty($images);
                    ?>
                    <div class="col">
                        <div class="card property-card" style="border-radius:12px;box-shadow:0 4px 16px rgba(0,0,0,0.08);">
                            <div class="position-relative overflow-hidden">
                                <div id="carouselProperty<?php echo $property['id']; ?>" class="carousel slide" style="width:100%;margin:auto;">
                                    <?php if ($hasImages): ?>
                                        <div class="carousel-indicators">
                                            <?php foreach ($images as $idx => $img): ?>
                                                <button type="button" data-bs-target="#carouselProperty<?php echo $property['id']; ?>" data-bs-slide-to="<?php echo $idx; ?>"<?php echo $idx === 0 ? ' class="active" aria-current="true"' : ''; ?> aria-label="Slide <?php echo $idx+1; ?>"></button>
                                            <?php endforeach; ?>
                                        </div>
                                        <div class="carousel-inner" style="border-radius:10px;">
                                            <?php foreach ($images as $idx => $img): ?>
                                                <div class="carousel-item<?php echo $idx === 0 ? ' active' : ''; ?>">
                                                    <img src="<?php echo htmlspecialchars($img); ?>" class="d-block w-100 carousel-img-fixed" style="height:160px;object-fit:cover;" alt="<?php echo htmlspecialchars($property['title']); ?>">
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
                                    <?php else: ?>
                                        <div class="bg-light d-flex align-items-center justify-content-center" style="height:160px;border-radius:10px;">
                                            <i class="bi bi-house fs-1 text-muted"></i>
                                        </div>
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
                        endforeach; // properties
                    endforeach; // brokers
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Gerenciar Imóveis -->
    <div class="card mt-4 mb-5">
        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Gerenciar Imóveis</h4>
            <a href="adicionar_imovel.php" class="btn btn-success btn-sm">
                <i class="fas fa-plus me-1"></i> Novo Imóvel
            </a>
        </div>
        <div class="card-body">
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php
                // Helper para listar imagens existentes por casa
                function listCasaImages($n) {
                    $baseDir = __DIR__ . DIRECTORY_SEPARATOR . 'imgs' . DIRECTORY_SEPARATOR . 'Casa' . $n;
                    $webBase = 'imgs/Casa' . $n . '/';
                    $images = [];
                    if (is_dir($baseDir)) {
                        $files = array_merge(
                            glob($baseDir . DIRECTORY_SEPARATOR . 'Casa' . $n . '.*.jpg') ?: [],
                            glob($baseDir . DIRECTORY_SEPARATOR . 'Casa' . $n . '.*.jpeg') ?: []
                        );
                        sort($files, SORT_NATURAL);
                        foreach ($files as $file) {
                            $images[] = $webBase . basename($file);
                        }
                    }
                    return $images;
                }

                // Carregar metadados salvos (preços, tipo, etc.) quando existirem
                $casasJson = [];
                $jsonPath = __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'casas_data.json';
                if (file_exists($jsonPath)) {
                    $json = file_get_contents($jsonPath);
                    $casasJson = json_decode($json, true) ?: [];
                }
                // Valores padrão para casas 2–9 quando não houver JSON
                $casasDefaults = [
                    '2' => ['rent_price' => 1500, 'type' => 'rent'],
                    '3' => ['price' => 3000000, 'type' => 'sale'],
                    '4' => ['price' => 170000, 'rent_price' => 1000, 'type' => 'both'],
                    '5' => ['price' => 380000, 'rent_price' => 900, 'type' => 'both'],
                    '6' => ['price' => 195000, 'rent_price' => 1000, 'type' => 'both'],
                    '7' => ['price' => 650000, 'rent_price' => 2000, 'type' => 'both'],
                    '8' => ['price' => 184900, 'type' => 'sale'],
                    '9' => ['price' => 450000, 'type' => 'sale'],
                ];

                // Títulos verdadeiros (padrão) para exibição quando não houver no JSON
                $casasDefaultTitles = [
                    '1' => 'Casa em Santo Antônio da Patrulha',
                    '2' => 'Casa em Taquara',
                    '3' => 'Casa em Taquara Alto Padrão',
                    '4' => 'Casa em Taquara Rua Mundo Novo',
                    '5' => 'Casa em Taquara Rua Flores da Cunha',
                    '6' => 'Casa em Parobé',
                    '7' => 'Casa em Taquara Bairro Santa Terezinha',
                    '8' => 'Casa em Taquara Bairro Tucanos',
                    '9' => 'Casa em Taquara - Rua São Francisco',
                ];

                for ($n = 1; $n <= 9; $n++):
                    $images = listCasaImages($n);
                    if (empty($images)) continue;
                    $title = 'Casa ' . $n;
                    // Metadados da casa (para título e preços)
                    $meta = $casasJson[(string)$n] ?? ($casasDefaults[(string)$n] ?? null);
                    $displayTitle = isset($meta['title']) && is_string($meta['title']) && trim($meta['title']) !== ''
                        ? trim($meta['title'])
                        : ($casasDefaultTitles[(string)$n] ?? $title);
                    $link = 'Casas/Casa' . $n . '.php';
                    if (!file_exists(__DIR__ . DIRECTORY_SEPARATOR . 'Casas' . DIRECTORY_SEPARATOR . 'Casa' . $n . '.php')) {
                        if ($n === 7 && file_exists(__DIR__ . DIRECTORY_SEPARATOR . 'Casas' . DIRECTORY_SEPARATOR . 'casa7.php')) {
                            $link = 'Casas/casa7.php';
                        }
                    }
                ?>
                <div class="col">
                    <div class="card h-100 border border-danger" style="border-width:2px !important;">
                        <div class="position-relative">
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="badge bg-primary"><?php echo count($images); ?> imagem<?php echo count($images) > 1 ? 's' : ''; ?></span>
                            </div>
                            <div id="carouselStaticCasa<?php echo $n; ?>" class="carousel slide">
                                <div class="carousel-indicators">
                                    <?php foreach ($images as $idx => $img): ?>
                                        <button type="button" data-bs-target="#carouselStaticCasa<?php echo $n; ?>" data-bs-slide-to="<?php echo $idx; ?>"<?php echo $idx === 0 ? ' class="active" aria-current="true"' : ''; ?> aria-label="Slide <?php echo $idx+1; ?>"></button>
                                    <?php endforeach; ?>
                                </div>
                                <div class="carousel-inner">
                                    <?php foreach ($images as $idx => $img): ?>
                                        <div class="carousel-item<?php echo $idx === 0 ? ' active' : ''; ?>">
                                            <img src="<?php echo htmlspecialchars($img); ?>" class="d-block w-100" style="height:220px;object-fit:cover;" alt="<?php echo htmlspecialchars($title); ?>">
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <?php if (count($images) > 1): ?>
                                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselStaticCasa<?php echo $n; ?>" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Anterior</span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#carouselStaticCasa<?php echo $n; ?>" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Próximo</span>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($displayTitle); ?></h5>
                            <?php
                            $hasSale = $meta && isset($meta['price']) && is_numeric($meta['price']);
                            $hasRent = $meta && isset($meta['rent_price']) && is_numeric($meta['rent_price']);
                            ?>
                            <div class="mb-2">
                                <?php if ($hasSale && $hasRent): ?>
                                    <span class="badge bg-success mb-2">À venda</span> <span class="badge bg-info mb-2">Para Alugar</span><br>
                                    <strong>Valor de compra:</strong> R$ <?php echo formatCurrency($meta['price']); ?><br>
                                    <strong>Valor do aluguel:</strong> R$ <?php echo formatCurrency($meta['rent_price']); ?>
                                <?php elseif ($hasSale): ?>
                                    <span class="badge bg-success mb-2">À venda</span><br>
                                    <strong>Valor de compra:</strong> R$ <?php echo formatCurrency($meta['price']); ?>
                                <?php elseif ($hasRent): ?>
                                    <span class="badge bg-info mb-2">Para Alugar</span><br>
                                    <strong>Valor do aluguel:</strong> R$ <?php echo formatCurrency($meta['rent_price']); ?>
                                <?php else: ?>
                                    <span class="badge bg-secondary mb-2">Sem preço cadastrado</span>
                                <?php endif; ?>
                            </div>
                            <div class="d-flex justify-content-center mt-3">
                                <a class="btn btn-sm w-100" style="background:linear-gradient(45deg,#ff7b00,#ff9500);border:none;color:white;" href="<?php echo $link; ?>">
                                    <i class="bi bi-info-circle"></i> Mais informações
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endfor; ?>
                <?php
                // Renderizar imóveis adicionais (IDs > 9) criados via JSON
                $extraIds = [];
                foreach ($casasJson as $id => $meta) {
                    if (!is_numeric($id) || (int)$id <= 9) continue;
                    // Filtrar itens removidos/placeholder
                    $titleCheck = isset($meta['title']) ? trim((string)$meta['title']) : '';
                    $statusCheck = isset($meta['status']) ? strtolower((string)$meta['status']) : '';
                    $isTestTitle = preg_match('/^(casa\s*teste|ggg)$/i', $titleCheck) === 1;
                    $isDeleted = in_array($statusCheck, ['deleted','inactive'], true);
                    if ($isTestTitle || $isDeleted) continue;
                    $extraIds[] = (int)$id;
                }
                sort($extraIds);
                foreach ($extraIds as $eid):
                    $images = listCasaImages($eid);
                    $title = 'Casa ' . $eid;
                    $meta = $casasJson[(string)$eid] ?? null;
                    $displayTitle = (isset($meta['title']) && is_string($meta['title']) && trim($meta['title']) !== '') ? trim($meta['title']) : $title;
                    $link = 'Casas/Casa' . $eid . '.php';
                ?>
                <div class="col">
                    <div class="card h-100 border border-danger" style="border-width:2px !important;">
                        <div class="position-relative">
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="badge bg-primary"><?php echo count($images); ?> imagem<?php echo count($images) > 1 ? 's' : ''; ?></span>
                            </div>
                            <div id="carouselStaticCasa<?php echo $eid; ?>" class="carousel slide">
                                <?php if (!empty($images)): ?>
                                <div class="carousel-indicators">
                                    <?php foreach ($images as $idx => $img): ?>
                                        <button type="button" data-bs-target="#carouselStaticCasa<?php echo $eid; ?>" data-bs-slide-to="<?php echo $idx; ?>"<?php echo $idx === 0 ? ' class="active" aria-current="true"' : ''; ?> aria-label="Slide <?php echo $idx+1; ?>"></button>
                                    <?php endforeach; ?>
                                </div>
                                <div class="carousel-inner">
                                    <?php foreach ($images as $idx => $img): ?>
                                        <div class="carousel-item<?php echo $idx === 0 ? ' active' : ''; ?>">
                                            <img src="<?php echo htmlspecialchars($img); ?>" class="d-block w-100" style="height:220px;object-fit:cover;" alt="<?php echo htmlspecialchars($displayTitle); ?>">
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <?php if (count($images) > 1): ?>
                                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselStaticCasa<?php echo $eid; ?>" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Anterior</span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#carouselStaticCasa<?php echo $eid; ?>" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Próximo</span>
                                    </button>
                                <?php endif; ?>
                                <?php else: ?>
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="height:220px;">
                                        <i class="bi bi-house fs-1 text-muted"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($displayTitle); ?></h5>
                            <?php
                            $hasSale = $meta && isset($meta['price']) && is_numeric($meta['price']);
                            $hasRent = $meta && isset($meta['rent_price']) && is_numeric($meta['rent_price']);
                            ?>
                            <div class="mb-2">
                                <?php if ($hasSale && $hasRent): ?>
                                    <span class="badge bg-success mb-2">À venda</span> <span class="badge bg-info mb-2">Para Alugar</span><br>
                                    <strong>Valor de compra:</strong> R$ <?php echo formatCurrency($meta['price']); ?><br>
                                    <strong>Valor do aluguel:</strong> R$ <?php echo formatCurrency($meta['rent_price']); ?>
                                <?php elseif ($hasSale): ?>
                                    <span class="badge bg-success mb-2">À venda</span><br>
                                    <strong>Valor de compra:</strong> R$ <?php echo formatCurrency($meta['price']); ?>
                                <?php elseif ($hasRent): ?>
                                    <span class="badge bg-info mb-2">Para Alugar</span><br>
                                    <strong>Valor do aluguel:</strong> R$ <?php echo formatCurrency($meta['rent_price']); ?>
                                <?php else: ?>
                                    <span class="badge bg-secondary mb-2">Sem preço cadastrado</span>
                                <?php endif; ?>
                            </div>
                            <div class="d-flex justify-content-center mt-3">
                                <a class="btn btn-sm w-100" style="background:linear-gradient(45deg,#ff7b00,#ff9500);border:none;color:white;" href="<?php echo $link; ?>">
                                    <i class="bi bi-info-circle"></i> Mais informações
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
