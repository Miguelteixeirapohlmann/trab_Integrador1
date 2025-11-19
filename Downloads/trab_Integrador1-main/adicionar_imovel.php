<?php
/**
 * Página para Adicionar Novo Imóvel
 */

require_once __DIR__ . '/includes/init.php';

// Requer login e perfil corretor/admin
$auth->requireLogin('Login.php');
$current_user = $auth->getCurrentUser();
if (!in_array($current_user['user_type'] ?? '', ['broker', 'corretor', 'admin'])) {
    header('Location: index.php');
    exit;
}

// Carregar dados existentes do JSON
$json_file = __DIR__ . '/data/casas_data.json';
$all_data = [];
if (file_exists($json_file)) {
    $all_data = json_decode(file_get_contents($json_file), true) ?: [];
}

// Obter lista de corretores para o combobox
$brokers = [];
try {
    $stmt = $pdo->prepare("SELECT b.id AS broker_id, u.first_name, u.last_name FROM brokers b LEFT JOIN users u ON b.user_id = u.id WHERE u.user_type = 'broker' ORDER BY u.first_name, u.last_name");
    $stmt->execute();
    $brokers = $stmt->fetchAll();
} catch (Throwable $e) {
    // Se não existir a tabela, segue sem combobox
}

// Campo livre para corretor (nome)

$broker_name = '';
$errors = [];

// Processar criação
if (isset($_POST['create_property'])) {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $rent_price = floatval($_POST['rent_price'] ?? 0);
    $bedrooms = intval($_POST['bedrooms'] ?? 0);
    $suites = intval($_POST['suites'] ?? 0);
    $bathrooms = intval($_POST['bathrooms'] ?? 0);
    $living_rooms = intval($_POST['living_rooms'] ?? 1);
    $kitchens = intval($_POST['kitchens'] ?? 1);
    $area = floatval($_POST['area'] ?? 0);
    $address = trim($_POST['address'] ?? '');
    $neighborhood = trim($_POST['neighborhood'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $state = trim($_POST['state'] ?? '');
    $zip_code = trim($_POST['zip_code'] ?? '');
    $type = $_POST['type'] ?? 'sale';
    $status = $_POST['status'] ?? 'active';
    $broker_name = '';
    $brokerChoiceRaw = $_POST['broker_id'] ?? '';
    $broker_id = null;
    $isCustomBroker = false;
    if ($brokerChoiceRaw !== '' && ctype_digit((string)$brokerChoiceRaw)) {
        $broker_id = (int)$brokerChoiceRaw;
    } elseif ($brokerChoiceRaw !== '' && preg_match('/^custom:(.+)$/u', $brokerChoiceRaw, $m)) {
        $isCustomBroker = true;
        $broker_name = trim($m[1]);
        $broker_id = null;
    }
    // Validar corretor obrigatório (aceita ID válido ou opção custom:Nome)
    if (!$broker_id && !$isCustomBroker) {
        $errors[] = 'Selecione um corretor.';
    }
    // Se selecionou ID no combobox, preencher nome do corretor a partir do banco
    if ($broker_id && !empty($brokers)) {
        foreach ($brokers as $b) {
            if ((int)$b['broker_id'] === $broker_id) {
                $full = trim(($b['first_name'] ?? '') . ' ' . ($b['last_name'] ?? ''));
                if ($full !== '') { $broker_name = $full; }
                break;
            }
        }
    }

    if ($title === '') $errors[] = 'Título é obrigatório';
    if ($description === '') $errors[] = 'Descrição é obrigatória';
    if ($price <= 0 && $rent_price <= 0) $errors[] = 'Preço de venda ou aluguel deve ser maior que zero';
    if ($bedrooms <= 0) $errors[] = 'Número de quartos deve ser maior que zero';
    if ($bathrooms <= 0) $errors[] = 'Número de banheiros deve ser maior que zero';
    if ($area <= 0) $errors[] = 'Área deve ser maior que zero';
    if ($address === '') $errors[] = 'Endereço é obrigatório';
    if ($city === '') $errors[] = 'Cidade é obrigatória';
    if ($state === '') $errors[] = 'Estado é obrigatório';

    if (empty($errors)) {
        $existingIds = array_map('intval', array_keys($all_data));
        for ($i = 1; $i <= 9; $i++) { $existingIds[] = $i; }
        $nextId = empty($existingIds) ? 1 : (max($existingIds) + 1);

        $newCasa = [
            'id' => $nextId,
            'title' => $title,
            'description' => $description,
            'price' => $price,
            'rent_price' => $rent_price,
            'bedrooms' => $bedrooms,
            'suites' => $suites,
            'bathrooms' => $bathrooms,
            'living_rooms' => $living_rooms,
            'kitchens' => $kitchens,
            'area' => $area,
            'address' => $address,
            'neighborhood' => $neighborhood,
            'city' => $city,
            'state' => $state,
            'zip_code' => $zip_code,
            'type' => $type,
            'status' => $status,
            'broker_name' => $broker_name,
            'broker_id' => $broker_id
        ];

        $all_data[$nextId] = $newCasa;

        $data_dir = dirname($json_file);
        if (!file_exists($data_dir)) { mkdir($data_dir, 0777, true); }
        file_put_contents($json_file, json_encode($all_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        $image_dir = __DIR__ . "/imgs/Casa{$nextId}/";
        if (!file_exists($image_dir)) { mkdir($image_dir, 0777, true); }

        $upload_errors = [];
        $upload_success = 0;
        if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
            $existing = glob($image_dir . "Casa{$nextId}.*.{jpg,jpeg,png,gif}", GLOB_BRACE) ?: [];
            $next_number = count($existing);

            $allowed_extensions = ['jpg','jpeg','png','gif'];
            foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
                if (($_FILES['images']['error'][$key] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
                    $error_messages = [
                        UPLOAD_ERR_INI_SIZE => 'Arquivo excede o tamanho máximo permitido pelo servidor',
                        UPLOAD_ERR_FORM_SIZE => 'Arquivo excede o tamanho máximo do formulário',
                        UPLOAD_ERR_PARTIAL => 'Upload parcial - tente novamente',
                        UPLOAD_ERR_NO_FILE => 'Nenhum arquivo foi enviado',
                        UPLOAD_ERR_NO_TMP_DIR => 'Pasta temporária ausente',
                        UPLOAD_ERR_CANT_WRITE => 'Falha ao escrever no disco',
                        UPLOAD_ERR_EXTENSION => 'Upload bloqueado por extensão'
                    ];
                    $upload_errors[] = ($_FILES['images']['name'][$key] ?? 'arquivo') . ': ' . ($error_messages[$_FILES['images']['error'][$key]] ?? 'Erro desconhecido');
                    continue;
                }
                $file_extension = strtolower(pathinfo($_FILES['images']['name'][$key], PATHINFO_EXTENSION));
                if (!in_array($file_extension, $allowed_extensions)) {
                    $upload_errors[] = ($_FILES['images']['name'][$key] ?? 'arquivo') . ': extensão inválida. Use JPG, PNG ou GIF';
                    continue;
                }
                if (($_FILES['images']['size'][$key] ?? 0) > 5242880) {
                    $size_mb = round(($_FILES['images']['size'][$key] ?? 0) / 1024 / 1024, 2);
                    $upload_errors[] = ($_FILES['images']['name'][$key] ?? 'arquivo') . ": muito grande ({$size_mb}MB). Máximo: 5MB";
                    continue;
                }
                $new_filename = "Casa{$nextId}.{$next_number}.{$file_extension}";
                $destination = $image_dir . $new_filename;
                if (move_uploaded_file($tmp_name, $destination)) {
                    $upload_success++;
                    $next_number++;
                } else {
                    $upload_errors[] = 'Falha ao salvar ' . ($_FILES['images']['name'][$key] ?? 'arquivo') . '. Verifique permissões da pasta.';
                }
            }
        }

        $casasDir = __DIR__ . DIRECTORY_SEPARATOR . 'Casas';
        if (!file_exists($casasDir)) { mkdir($casasDir, 0777, true); }
        $casaPagePath = $casasDir . DIRECTORY_SEPARATOR . 'Casa' . $nextId . '.php';
        $defaultTitle = $title;
        $defaultType = $type;
        $defaultStatus = $status;
        $defaultPrice = $price;
        $defaultRentPrice = $rent_price;
        $defaultBedrooms = $bedrooms;
        $defaultSuites = $suites;
        $defaultBathrooms = $bathrooms;
        $defaultLiving = $living_rooms;
        $defaultKitchens = $kitchens;
        $defaultArea = $area;
        $defaultAddress = $address;
        $defaultNeighborhood = $neighborhood;
        $defaultCity = $city;
        $defaultState = $state;
        $defaultZip = $zip_code;

        $pageContent = <<<'PHP'
<?php
require_once __DIR__ . '/../includes/init.php';
$auth = $auth ?? null;
$current_user = $auth ? $auth->getCurrentUser() : null;

$casa_id = __CASA_ID__;
$defaults = [
    'id' => __CASA_ID__,
    'title' => '__TITLE__',
    'description' => '__DESCRIPTION__',
    'price' => __PRICE__,
    'rent_price' => __RENT_PRICE__,
    'bedrooms' => __BEDROOMS__,
    'suites' => __SUITES__,
    'bathrooms' => __BATHROOMS__,
    'living_rooms' => __LIVING__,
    'kitchens' => __KITCHENS__,
    'area' => __AREA__,
    'address' => '__ADDRESS__',
    'neighborhood' => '__NEIGHBORHOOD__',
    'city' => '__CITY__',
    'state' => '__STATE__',
    'zip_code' => '__ZIP__',
    'type' => '__TYPE__',
    'status' => '__STATUS__'
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
    #carouselCasa__CASA_ID__ .carousel-inner {
        height: 400px;
        overflow: hidden;
        background-color: #000;
        position: relative;
    }
    #carouselCasa__CASA_ID__ .carousel-item.active { position: relative; }
    #carouselCasa__CASA_ID__ .carousel-item img {
        width: 100%;
        height: 400px;
        object-fit: cover;
        object-position: center;
        display: block;
        background-color: #000;
    }
    /* Remover transições do Bootstrap */
    #carouselCasa__CASA_ID__ .carousel-item-next,
    #carouselCasa__CASA_ID__ .carousel-item-prev,
    #carouselCasa__CASA_ID__ .carousel-item-start,
    #carouselCasa__CASA_ID__ .carousel-item-end {
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
            <div id="carouselCasa__CASA_ID__" class="carousel slide" data-bs-interval="false" data-bs-touch="false" data-bs-keyboard="false">
                <?php if (!empty($images)): ?>
                    <div class="carousel-indicators">
                        <?php foreach ($images as $idx => $_): ?>
                            <button type="button" data-bs-target="#carouselCasa__CASA_ID__" data-bs-slide-to="<?php echo $idx; ?>" class="<?php echo $idx===0?'active':''; ?>" <?php echo $idx===0?'aria-current="true"':''; ?> aria-label="Slide <?php echo $idx+1; ?>"></button>
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
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselCasa__CASA_ID__" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Anterior</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselCasa__CASA_ID__" data-bs-slide="next">
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
                    <a href="../editar_casa.php?id=__CASA_ID__" class="btn btn-warning mx-0 btn-admin-action"><i class="fas fa-edit me-1"></i> Editar</a>
                    <form method="POST" action="../gerenciar_imoveis.php" onsubmit="return confirm('Tem certeza que deseja excluir este imóvel?');" style="display:inline-block; margin:0;">
                        <input type="hidden" name="action" value="excluir">
                        <input type="hidden" name="casa_id" value="__CASA_ID__">
                        <button type="submit" class="btn btn-danger mx-0 btn-admin-action"><i class="fas fa-trash me-1"></i> Excluir</button>
                    </form>
                </span>
            <?php elseif ($auth && $auth->isLoggedIn() && isset($current_user['user_type']) && $current_user['user_type'] === 'broker'): ?>
                <div class="d-flex gap-3">
                    <a href="../editar_casa.php?id=__CASA_ID__" class="btn btn-warning">Editar</a>
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
PHP;

        // Substituir placeholders
        $replacements = [
            '__CASA_ID__' => $nextId,
            '__TITLE__' => str_replace(['"', "'"], ['"', "'"], $defaultTitle),
            '__DESCRIPTION__' => str_replace(['"', "'"], ['"', "'"], $description),
            '__PRICE__' => is_numeric($defaultPrice) ? $defaultPrice : 0,
            '__RENT_PRICE__' => is_numeric($defaultRentPrice) ? $defaultRentPrice : 0,
            '__BEDROOMS__' => (int)$defaultBedrooms,
            '__SUITES__' => (int)$defaultSuites,
            '__BATHROOMS__' => (int)$defaultBathrooms,
            '__LIVING__' => (int)$defaultLiving,
            '__KITCHENS__' => (int)$defaultKitchens,
            '__AREA__' => is_numeric($defaultArea) ? $defaultArea : 0,
            '__ADDRESS__' => str_replace(['"', "'"], ['"', "'"], $defaultAddress),
            '__NEIGHBORHOOD__' => str_replace(['"', "'"], ['"', "'"], $defaultNeighborhood),
            '__CITY__' => str_replace(['"', "'"], ['"', "'"], $defaultCity),
            '__STATE__' => str_replace(['"', "'"], ['"', "'"], $defaultState),
            '__ZIP__' => str_replace(['"', "'"], ['"', "'"], $defaultZip),
            '__TYPE__' => $defaultType,
            '__STATUS__' => $defaultStatus,
        ];
        // Ajuste IDs no CSS/HTML (#carouselCasa__CASA_ID__) antes de substituir variáveis numéricas
        $pageContent = str_replace('__CASA_ID__', $nextId, $pageContent);
        $finalPageContent = strtr($pageContent, $replacements);
        file_put_contents($casaPagePath, $finalPageContent);

        $flashMsg = 'Imóvel criado com sucesso! Página gerada em Casas/Casa' . $nextId . '.php.';
        if ($upload_success > 0) {
            $flashMsg .= " {$upload_success} imagem(ns) enviada(s).";
        }
        if (!empty($upload_errors)) {
            $flashMsg .= ' Alguns arquivos não foram salvos: ' . htmlspecialchars(implode(' | ', $upload_errors));
        }

        $_SESSION['flash_message'] = [
            'type' => empty($upload_errors) ? 'success' : 'warning',
            'message' => $flashMsg
        ];
        header('Location: admin.php');
        exit;
    }
}

// Valores padrão do formulário
$form = [
    'title' => $_POST['title'] ?? '',
    'description' => $_POST['description'] ?? '',
    'price' => $_POST['price'] ?? '',
    'rent_price' => $_POST['rent_price'] ?? '',
    'bedrooms' => $_POST['bedrooms'] ?? 1,
    'suites' => $_POST['suites'] ?? 0,
    'bathrooms' => $_POST['bathrooms'] ?? 1,
    'living_rooms' => $_POST['living_rooms'] ?? 1,
    'kitchens' => $_POST['kitchens'] ?? 1,
    'area' => $_POST['area'] ?? '',
    'address' => $_POST['address'] ?? '',
    'neighborhood' => $_POST['neighborhood'] ?? '',
    'city' => $_POST['city'] ?? '',
    'state' => $_POST['state'] ?? '',
    'zip_code' => $_POST['zip_code'] ?? '',
    'type' => $_POST['type'] ?? 'sale',
    'status' => $_POST['status'] ?? 'active',
    'broker_id' => $_POST['broker_id'] ?? '',
    'broker_name' => $broker_name,
];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Adicionar Imóvel - Company Miguel</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet" />
    <style>
        body { background-color: #ffffff !important; min-height: 100vh; }
        .masthead { background: #ffffff !important; padding: 3rem 0; }
        h1, h2 { color: #000000 !important; }
    </style>
    </head>
<body id="page-top">
    <?php if (function_exists('renderNavbar')) { renderNavbar($current_user, 'gerenciar_imoveis'); } ?>

    <header class="masthead">
        <div class="container px-4 px-lg-5 d-flex h-100 align-items-center justify-content-center">
            <div class="d-flex justify-content-center">
                <div class="text-center">
                    <h1 class="mx-auto my-0 text-uppercase">Adicionar Imóvel</h1>
                    <h2 class="mx-auto mt-2 mb-5">Preencha os dados do novo imóvel</h2>
                </div>
            </div>
        </div>
    </header>

    <section class="contact-section bg-white" id="create-property">
        <div class="container px-4 px-lg-5">

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger" role="alert">
                    <ul class="mb-0">
                        <?php foreach ($errors as $e): ?>
                            <li><?php echo htmlspecialchars($e); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="row gx-4 gx-lg-5">
                <div class="col-md-10 col-lg-8 mx-auto">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="text-black mb-0"><i class="fas fa-plus me-2"></i>Novo Imóvel</h4>
                        </div>
                        <div class="card-body">
                            <form method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="create_property" value="1" />
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label for="title" class="form-label text-black">Título *</label>
                                        <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($form['title']); ?>" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label text-black">Descrição *</nlabel>
                                    <textarea class="form-control" id="description" name="description" rows="4" required><?php echo htmlspecialchars($form['description']); ?></textarea>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="price" class="form-label text-black">Preço Venda (R$)</label>
                                        <input type="number" step="0.01" class="form-control" id="price" name="price" value="<?php echo htmlspecialchars((string)$form['price']); ?>" min="0">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="rent_price" class="form-label text-black">Preço Aluguel (R$)</label>
                                        <input type="number" step="0.01" class="form-control" id="rent_price" name="rent_price" value="<?php echo htmlspecialchars((string)$form['rent_price']); ?>" min="0">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label for="bedrooms" class="form-label text-black">Quartos *</label>
                                        <input type="number" class="form-control" id="bedrooms" name="bedrooms" value="<?php echo htmlspecialchars((string)$form['bedrooms']); ?>" min="1" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="suites" class="form-label text-black">Suítes</label>
                                        <input type="number" class="form-control" id="suites" name="suites" value="<?php echo htmlspecialchars((string)$form['suites']); ?>" min="0">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="bathrooms" class="form-label text-black">Banheiros *</label>
                                        <input type="number" class="form-control" id="bathrooms" name="bathrooms" value="<?php echo htmlspecialchars((string)$form['bathrooms']); ?>" min="1" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="area" class="form-label text-black">Área (m²) *</label>
                                        <input type="number" step="0.01" class="form-control" id="area" name="area" value="<?php echo htmlspecialchars((string)$form['area']); ?>" min="1" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="living_rooms" class="form-label text-black">Salas</label>
                                        <input type="number" class="form-control" id="living_rooms" name="living_rooms" value="<?php echo htmlspecialchars((string)$form['living_rooms']); ?>" min="0">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="kitchens" class="form-label text-black">Cozinhas</label>
                                        <input type="number" class="form-control" id="kitchens" name="kitchens" value="<?php echo htmlspecialchars((string)$form['kitchens']); ?>" min="0">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-8">
                                        <label for="address" class="form-label text-black">Endereço *</label>
                                        <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($form['address']); ?>" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="neighborhood" class="form-label text-black">Bairro</label>
                                        <input type="text" class="form-control" id="neighborhood" name="neighborhood" value="<?php echo htmlspecialchars($form['neighborhood']); ?>">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="city" class="form-label text-black">Cidade *</label>
                                        <input type="text" class="form-control" id="city" name="city" value="<?php echo htmlspecialchars($form['city']); ?>" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="state" class="form-label text-black">Estado *</label>
                                        <input type="text" class="form-control" id="state" name="state" value="<?php echo htmlspecialchars($form['state']); ?>" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="zip_code" class="form-label text-black">CEP</label>
                                        <input type="text" class="form-control" id="zip_code" name="zip_code" value="<?php echo htmlspecialchars($form['zip_code']); ?>">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="type" class="form-label text-black">Tipo de Transação</label>
                                        <select class="form-control" id="type" name="type">
                                            <option value="sale" <?php echo $form['type']==='sale'?'selected':''; ?>>Venda</option>
                                            <option value="rent" <?php echo $form['type']==='rent'?'selected':''; ?>>Aluguel</option>
                                            <option value="both" <?php echo $form['type']==='both'?'selected':''; ?>>Venda e Aluguel</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="status" class="form-label text-black">Status</label>
                                        <select class="form-control" id="status" name="status">
                                            <option value="active" <?php echo $form['status']==='active'?'selected':''; ?>>Ativo</option>
                                            <option value="pending" <?php echo $form['status']==='pending'?'selected':''; ?>>Pendente</option>
                                            <option value="sold" <?php echo $form['status']==='sold'?'selected':''; ?>>Vendido</option>
                                            <option value="rented" <?php echo $form['status']==='rented'?'selected':''; ?>>Alugado</option>
                                            <option value="inactive" <?php echo $form['status']==='inactive'?'selected':''; ?>>Inativo</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label for="broker_id" class="form-label text-black">Corretor *</label>
                                        <select class="form-control" id="broker_id" name="broker_id" required>
                                            <option value="">Selecione um corretor</option>
                                            <?php foreach ($brokers as $b): $idOpt=(int)$b['broker_id']; $nm=trim(($b['first_name']??'').' '.($b['last_name']??'')); ?>
                                                <option value="<?php echo $idOpt; ?>" <?php echo ((string)$form['broker_id'] === (string)$idOpt) ? 'selected' : ''; ?>><?php echo htmlspecialchars($nm); ?></option>
                                            <?php endforeach; ?>
                                            <optgroup label="Corretores Padrão">
                                                <option value="custom:Pedro Costa" <?php echo ($form['broker_id']==='custom:Pedro Costa'?'selected':''); ?>>Pedro Costa</option>
                                                <option value="custom:João Silva" <?php echo ($form['broker_id']==='custom:João Silva'?'selected':''); ?>>João Silva</option>
                                                <option value="custom:Maria Santos" <?php echo ($form['broker_id']==='custom:Maria Santos'?'selected':''); ?>>Maria Santos</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="imageInput" class="form-label text-black">
                                        <i class="fas fa-images me-2"></i>Imagens do Imóvel (opcional)
                                    </label>
                                    <input type="file" name="images[]" id="imageInput" multiple accept="image/jpeg,image/jpg,image/png,image/gif" class="form-control">
                                    <div class="form-text">
                                        Formatos: JPG, JPEG, PNG, GIF | Máx: 5MB por arquivo | Pode selecionar múltiplos
                                    </div>
                                </div>
                                <hr class="my-4">
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary btn-lg">Criar Imóvel</button>
                                    <a href="admin.php" class="btn btn-secondary btn-lg ms-2">Cancelar</a>
                                </div>
                                <p class="text-muted mt-3 mb-0 text-center">Após criar, você poderá gerenciar e excluir fotos.</p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-light py-5">
        <div class="container px-4 px-lg-5">
            <div class="small text-center text-muted">Copyright &copy; 2025 - Company Miguel</div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>
</body>
</html>

