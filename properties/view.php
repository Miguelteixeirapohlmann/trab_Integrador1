<?php
require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../models/Property.php';

$property_id = intval($_GET['id'] ?? 0);

if (!$property_id) {
    redirect('/index.php', 'Propriedade não encontrada.', 'error');
}

$propertyModel = new Property();
$property = $propertyModel->findById($property_id);

if (!$property) {
    redirect('/index.php', 'Propriedade não encontrada.', 'error');
}

// Buscar propriedades similares
$similar_properties = $propertyModel->getSimilar($property_id, 4);

// Verificar se está nos favoritos (se usuário logado)
$is_favorited = false;
if ($auth->isLoggedIn()) {
    $is_favorited = $propertyModel->isFavorited($_SESSION['user_id'], $property_id);
}

// Configurações da página
$page_title = $property['title'] . " - " . APP_NAME;
$page_description = substr($property['description'], 0, 160) . "...";
$body_id = "property";

ob_start();
?>

<!-- Property Header -->
<div class="container-fluid p-0">
    <!-- Property Images Carousel -->
    <?php if (!empty($property['images'])): ?>
    <div id="propertyCarousel" class="carousel slide" data-bs-interval="false" data-bs-touch="false" data-bs-keyboard="false">
        <div class="carousel-inner">
            <?php foreach ($property['images'] as $index => $image): ?>
            <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                <img src="<?php echo htmlspecialchars($image); ?>" 
                     class="d-block w-100" 
                     alt="<?php echo htmlspecialchars($property['title']); ?>"
                     style="height: 500px; object-fit: cover;">
            </div>
            <?php endforeach; ?>
        </div>
        
        <?php if (count($property['images']) > 1): ?>
        <button class="carousel-control-prev" type="button" data-bs-target="#propertyCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#propertyCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
        <?php endif; ?>
    </div>
    <?php else: ?>
    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 500px;">
        <div class="text-center text-muted">
            <i class="bi bi-house fs-1 mb-3"></i>
            <p>Nenhuma imagem disponível</p>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Property Content -->
<div class="container my-5">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Property Info -->
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div>
                    <h1 class="mb-2"><?php echo htmlspecialchars($property['title']); ?></h1>
                    <p class="text-muted mb-0">
                        <i class="bi bi-geo-alt me-1"></i>
                        <?php echo htmlspecialchars($property['address'] . ', ' . $property['city'] . ', ' . $property['state']); ?>
                    </p>
                </div>
                
                <?php if ($auth->isLoggedIn()): ?>
                <button class="btn btn-outline-danger" 
                        onclick="toggleFavorite(<?php echo $property_id; ?>)"
                        id="favoriteBtn">
                    <i class="bi bi-heart<?php echo $is_favorited ? '-fill' : ''; ?>"></i>
                    <?php echo $is_favorited ? 'Remover' : 'Favoritar'; ?>
                </button>
                <?php endif; ?>
            </div>
            
            <!-- Property Details -->
            <div class="row mb-4">
                <div class="col-md-3 text-center">
                    <div class="border rounded p-3">
                        <i class="bi bi-rulers fs-2 text-primary"></i>
                        <div class="mt-2">
                            <strong><?php echo number_format($property['area_sqm'], 0); ?>m²</strong>
                            <div class="small text-muted">Área</div>
                        </div>
                    </div>
                </div>
                
                <?php if ($property['bedrooms']): ?>
                <div class="col-md-3 text-center">
                    <div class="border rounded p-3">
                        <i class="bi bi-bed fs-2 text-primary"></i>
                        <div class="mt-2">
                            <strong><?php echo $property['bedrooms']; ?></strong>
                            <div class="small text-muted">Quartos</div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if ($property['bathrooms']): ?>
                <div class="col-md-3 text-center">
                    <div class="border rounded p-3">
                        <i class="bi bi-house-door fs-2 text-primary"></i>
                        <div class="mt-2">
                            <strong><?php echo $property['bathrooms']; ?></strong>
                            <div class="small text-muted">Banheiros</div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if ($property['garage_spaces']): ?>
                <div class="col-md-3 text-center">
                    <div class="border rounded p-3">
                        <i class="bi bi-car-front fs-2 text-primary"></i>
                        <div class="mt-2">
                            <strong><?php echo $property['garage_spaces']; ?></strong>
                            <div class="small text-muted">Vagas</div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Description -->
            <div class="mb-4">
                <h3>Descrição</h3>
                <p><?php echo nl2br(htmlspecialchars($property['description'])); ?></p>
            </div>
            
            <!-- Features -->
            <?php if (!empty($property['features'])): ?>
            <div class="mb-4">
                <h3>Características</h3>
                <div class="row">
                    <?php foreach ($property['features'] as $feature): ?>
                    <div class="col-md-6 mb-2">
                        <i class="bi bi-check2 text-success me-2"></i>
                        <?php echo htmlspecialchars($feature); ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Amenities -->
            <?php if (!empty($property['amenities'])): ?>
            <div class="mb-4">
                <h3>Comodidades</h3>
                <div class="row">
                    <?php foreach ($property['amenities'] as $amenity): ?>
                    <div class="col-md-6 mb-2">
                        <i class="bi bi-star text-warning me-2"></i>
                        <?php echo htmlspecialchars($amenity); ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Price and Action -->
            <div class="card mb-4">
                <div class="card-body text-center">
                    <div class="price mb-3">
                        <h2 class="text-primary mb-0"><?php echo formatCurrency($property['price']); ?></h2>
                        <?php if ($property['transaction_type'] == 'rent' || $property['transaction_type'] == 'both'): ?>
                            <small class="text-muted">/mês</small>
                        <?php endif; ?>
                        
                        <?php if ($property['transaction_type'] == 'both' && $property['rent_price']): ?>
                            <div class="mt-2">
                                <span class="text-muted">Ou alugue por </span>
                                <strong><?php echo formatCurrency($property['rent_price']); ?>/mês</strong>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <a href="/visits/schedule.php?property_id=<?php echo $property_id; ?>" 
                           class="btn btn-primary btn-lg">
                            <i class="bi bi-calendar-event me-2"></i>
                            Agendar Visita
                        </a>
                        
                        <a href="tel:<?php echo $property['broker_phone']; ?>" 
                           class="btn btn-outline-primary">
                            <i class="bi bi-telephone me-2"></i>
                            Ligar para Corretor
                        </a>
                        
                        <a href="mailto:<?php echo $property['broker_email']; ?>" 
                           class="btn btn-outline-secondary">
                            <i class="bi bi-envelope me-2"></i>
                            Enviar Email
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Broker Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Corretor Responsável</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-3">
                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 50px; height: 50px;">
                                <i class="bi bi-person text-white fs-4"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="mb-1">
                                <?php echo htmlspecialchars($property['broker_first_name'] . ' ' . $property['broker_last_name']); ?>
                            </h6>
                            <?php if ($property['company']): ?>
                            <small class="text-muted"><?php echo htmlspecialchars($property['company']); ?></small>
                            <?php endif; ?>
                            <?php if ($property['license_number']): ?>
                            <div class="small text-muted">CRECI: <?php echo htmlspecialchars($property['license_number']); ?></div>
                            <?php endif; ?>
                            <?php if ($property['rating']): ?>
                            <div class="small">
                                <i class="bi bi-star-fill text-warning"></i>
                                <?php echo number_format($property['rating'], 1); ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Property Stats -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Informações</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <div class="h4 mb-0 text-primary"><?php echo number_format($property['views_count']); ?></div>
                                <small class="text-muted">Visualizações</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="h4 mb-0 text-danger"><?php echo number_format($property['favorites_count']); ?></div>
                            <small class="text-muted">Favoritos</small>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="small text-muted">
                        <div><strong>Tipo:</strong> <?php echo ucfirst($property['property_type']); ?></div>
                        <div><strong>Transação:</strong> <?php echo ucfirst($property['transaction_type']); ?></div>
                        <div><strong>Status:</strong> <?php echo ucfirst($property['status']); ?></div>
                        <?php if ($property['furnished']): ?>
                        <div><i class="bi bi-check2 text-success"></i> Mobiliado</div>
                        <?php endif; ?>
                        <?php if ($property['pets_allowed']): ?>
                        <div><i class="bi bi-check2 text-success"></i> Aceita pets</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Similar Properties -->
<?php if (!empty($similar_properties)): ?>
<section class="bg-light py-5">
    <div class="container">
        <h2 class="text-center mb-5">Propriedades Similares</h2>
        
        <div class="row">
            <?php foreach ($similar_properties as $similar): ?>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <?php if ($similar['primary_image']): ?>
                    <img src="<?php echo htmlspecialchars($similar['primary_image']); ?>" 
                         class="card-img-top" 
                         alt="<?php echo htmlspecialchars($similar['title']); ?>"
                         style="height: 200px; object-fit: cover;">
                    <?php else: ?>
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                         style="height: 200px;">
                        <i class="bi bi-house fs-1 text-muted"></i>
                    </div>
                    <?php endif; ?>
                    
                    <div class="card-body">
                        <h6 class="card-title"><?php echo htmlspecialchars($similar['title']); ?></h6>
                        <p class="card-text text-muted small">
                            <i class="bi bi-geo-alt me-1"></i>
                            <?php echo htmlspecialchars($similar['city']); ?>
                        </p>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <strong class="text-primary">
                                <?php echo formatCurrency($similar['price']); ?>
                            </strong>
                            <a href="/properties/view.php?id=<?php echo $similar['id']; ?>" 
                               class="btn btn-outline-primary btn-sm">
                                Ver
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<script>
// Favoritar/desfavoritar
<?php if ($auth->isLoggedIn()): ?>
function toggleFavorite(propertyId) {
    const btn = document.getElementById('favoriteBtn');
    const icon = btn.querySelector('i');
    
    fetch('/api/toggle-favorite.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            property_id: propertyId,
            csrf_token: '<?php echo generateCSRFToken(); ?>'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (data.action === 'added') {
                icon.classList.remove('bi-heart');
                icon.classList.add('bi-heart-fill');
                btn.innerHTML = '<i class="bi bi-heart-fill"></i> Remover';
            } else {
                icon.classList.remove('bi-heart-fill');
                icon.classList.add('bi-heart');
                btn.innerHTML = '<i class="bi bi-heart"></i> Favoritar';
            }
            
            // Mostrar toast de sucesso
            showToast(data.message, 'success');
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Erro ao processar solicitação', 'error');
    });
}

function showToast(message, type) {
    // Implementar toast notification
    alert(message); // Temporário
}
<?php endif; ?>
</script>

<?php 
$content = ob_get_clean();
include __DIR__ . '/../views/layouts/main.php';
?>
