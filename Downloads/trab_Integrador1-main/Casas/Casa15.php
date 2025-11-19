<?php
require_once __DIR__ . '/../includes/init.php';
$auth = $auth ?? null;
$current_user = $auth ? $auth->getCurrentUser() : null;

$casa_id = 15;
$defaults = [
    'id' => 15,
    'title' => 'dddd',
    'description' => 'dddddd',
    'price' => 2395,
    'rent_price' => 222222,
    'bedrooms' => 1,
    'suites' => 0,
    'bathrooms' => 1,
    'living_rooms' => 1,
    'kitchens' => 1,
    'area' => 234,
    'address' => 'Casa',
    'neighborhood' => 'Santo Antônio da Patrulha',
    'city' => 'Taquara',
    'state' => 'RS',
    'zip_code' => '95600-088',
    'type' => 'sale',
    'status' => 'active'
];

// Carregar dados atualizados do JSON, se houver
$json_file = __DIR__ . '/../data/casas_data.json';
if (file_exists($json_file)) {
    $all = json_decode(file_get_contents($json_file), true) ?: [];
    if (isset($all[$casa_id]) && is_array($all[$casa_id])) {
        $defaults = array_merge($defaults, $all[$casa_id]);
    }
}
$casa = $defaults;

function listarImagensCasa($id) {
    $dir = __DIR__ . '/../imgs/Casa' . $id . '/';
    $web = '../imgs/Casa' . $id . '/';
    $images = [];
    if (is_dir($dir)) {
        $files = scandir($dir);
        foreach ($files as $file) {
            if (preg_match('/^Casa' . $id . '\\.\d+\.(jpg|jpeg|png|gif)$/i', $file)) {
                $images[] = $web . $file;
            }
        }
        usort($images, function($a, $b) {
            preg_match('/\.(\d+)\./', $a, $ma);
            preg_match('/\.(\d+)\./', $b, $mb);
            return intval($ma[1] ?? 0) <=> intval($mb[1] ?? 0);
        });
    }
    return $images;
}

function formatCurrencyBR($n) {
    if (!is_numeric($n)) return '';
    return number_format((float)$n, 2, ',', '.');
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($casa['title']); ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../css/styles.css?v=20250829" rel="stylesheet">
    <style>
    body { background-color: #ffffff !important; }
    /* Estilos para manter a imagem estável */
    #carouselCasa15 .carousel-inner {
        height: 400px;
        overflow: hidden;
        background-color: #000;
        position: relative;
    }
    #carouselCasa15 .carousel-item.active { position: relative; }
    #carouselCasa15 .carousel-item img {
        width: 100%;
        height: 400px;
        object-fit: cover;
        object-position: center;
        display: block;
        background-color: #000;
    }
    /* Remover transições do Bootstrap */
    #carouselCasa15 .carousel-item-next,
    #carouselCasa15 .carousel-item-prev,
    #carouselCasa15 .carousel-item-start,
    #carouselCasa15 .carousel-item-end {
        transition: none !important;
        transform: none !important;
    }
    .btn-admin-action { width: 130px; display: inline-block; text-align: center; }
    </style>
</head>
<body>
<?php if (function_exists('renderNavbar')) { renderNavbar($current_user, '', '../'); } ?>

<div class="container py-5 mt-5">
    <div class="row align-items-center">
        <div class="col-md-6 mb-4 mb-md-0">
            <?php $images = listarImagensCasa($casa_id); ?>
            <div id="carouselCasa15" class="carousel slide" data-bs-interval="false" data-bs-touch="false" data-bs-keyboard="false">
                <?php if (!empty($images)): ?>
                    <div class="carousel-indicators">
                        <?php foreach ($images as $idx => $_): ?>
                            <button type="button" data-bs-target="#carouselCasa15" data-bs-slide-to="<?php echo $idx; ?>" class="<?php echo $idx===0?'active':''; ?>" <?php echo $idx===0?'aria-current="true"':''; ?> aria-label="Slide <?php echo $idx+1; ?>"></button>
                        <?php endforeach; ?>
                    </div>
                    <div class="carousel-inner rounded shadow" style="height: 400px; overflow: hidden;">
                        <?php foreach ($images as $idx => $img): ?>
                            <div class="carousel-item <?php echo $idx===0?'active':''; ?>">
                                <img src="<?php echo htmlspecialchars($img); ?>" class="d-block w-100" alt="Imagem <?php echo $idx+1; ?>" onerror="this.closest('.carousel-item').remove();">
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php if (count($images) > 1): ?>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselCasa15" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Anterior</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselCasa15" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Próximo</span>
                        </button>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="bg-light p-5 text-center">Nenhuma imagem enviada ainda.</div>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-md-6">
            <h1 class="mb-3"><?php echo htmlspecialchars($casa['title']); ?></h1>
            <h4 class="text-success mb-4">
                <?php if (!empty($casa['price'])): ?>
                    Compra R$ <?php echo formatCurrencyBR($casa['price']); ?>
                <?php endif; ?>
                <?php if (!empty($casa['rent_price'])): ?>
                    <span class="text-success mb-4"> Aluguel R$ <?php echo formatCurrencyBR($casa['rent_price']); ?></span>
                <?php endif; ?>
            </h4>
            <ul class="list-group mb-4">
                <?php if (!empty($casa['area'])): ?><li class="list-group-item"><strong>Tamanho:</strong> <?php echo number_format((float)$casa['area'], 0, ',', '.'); ?> m²</li><?php endif; ?>
                <li class="list-group-item"><strong>Quartos:</strong> <?php echo (int)$casa['bedrooms']; ?> (<?php echo (int)$casa['suites']; ?>) Suítes</li>
                <li class="list-group-item"><strong>Banheiros:</strong> <?php echo (int)$casa['bathrooms']; ?></li>
                <li class="list-group-item"><strong>Salas:</strong> <?php echo (int)$casa['living_rooms']; ?></li>
                <li class="list-group-item"><strong>Cozinhas:</strong> <?php echo (int)$casa['kitchens']; ?></li>
                <li class="list-group-item"><strong>Endereço:</strong> <?php echo htmlspecialchars(($casa['address'] ?? '') . (empty($casa['neighborhood'])?'':', '.$casa['neighborhood'])); ?></li>
                <li class="list-group-item"><strong>Localização:</strong> <?php echo htmlspecialchars(($casa['city'] ?? '') . (empty($casa['state'])?'':' - '.$casa['state']) . (empty($casa['zip_code'])?'':' - '.$casa['zip_code'])); ?></li>
            </ul>
            <p><?php echo nl2br(htmlspecialchars($casa['description'])); ?></p>

            <?php if ($auth && $auth->isLoggedIn() && isset($current_user['user_type']) && $current_user['user_type'] === 'admin'): ?>
                <span class="admin-actions-inline" style="white-space: nowrap; display: inline-flex; gap: 0; align-items: center;">
                    <a href="../editar_casa.php?id=15" class="btn btn-warning mx-0 btn-admin-action"><i class="fas fa-edit me-1"></i> Editar</a>
                    <form method="POST" action="../gerenciar_imoveis.php" onsubmit="return confirm('Tem certeza que deseja excluir este imóvel?');" style="display:inline-block; margin:0;">
                        <input type="hidden" name="action" value="excluir">
                        <input type="hidden" name="casa_id" value="15">
                        <button type="submit" class="btn btn-danger mx-0 btn-admin-action"><i class="fas fa-trash me-1"></i> Excluir</button>
                    </form>
                </span>
            <?php elseif ($auth && $auth->isLoggedIn() && isset($current_user['user_type']) && $current_user['user_type'] === 'broker'): ?>
                <div class="d-flex gap-3">
                    <a href="../editar_casa.php?id=15" class="btn btn-warning">Editar</a>
                </div>
            <?php else: ?>
                <div class="d-flex gap-3">
                    <a href="../Compra.php" class="btn btn-success">Compra</a>
                    <a href="../alugar.php" class="btn btn-primary">Alugar</a>
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