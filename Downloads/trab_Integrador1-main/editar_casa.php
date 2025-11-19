<?php
/**
 * Página para Editar Casa Específica
 */

require_once 'includes/init.php';

// Verificar se o usuário está logado e é corretor
$auth->requireLogin('Login.php');
$current_user = $auth->getCurrentUser();

// Verificar se é corretor (broker) ou admin
if (!in_array($current_user['user_type'], ['broker', 'corretor', 'admin'])) {
    header('Location: index.php');
    exit;
}

// Verificar se foi passado um ID
if (!isset($_GET['id'])) {
    header("Location: gerenciar_imoveis.php");
    exit;
}

$casa_id = intval($_GET['id']);

// Dados das casas (hardcoded para demonstração)
$casas_data = [
    1 => [
        'id' => 1,
        'title' => 'Casa em Santo Antônio da Patrulha',
        'description' => 'Casa ampla, bem iluminada, recém-reformada, com quintal e garagem para quatro carros. Bairro tranquilo e seguro, ideal para famílias.',
        'price' => 5200000.00,
        'rent_price' => 2200000.00,
        'bedrooms' => 6,
        'suites' => 2,
        'bathrooms' => 4,
        'living_rooms' => 2,
        'kitchens' => 2,
        'area' => 1536.00,
        'address' => 'Rua Antônio Alves Pinheiro, 2523',
        'neighborhood' => 'Santo Antônio da Patrulha',
        'city' => 'Santo Antônio da Patrulha',
        'state' => 'RS',
        'zip_code' => '',
        'type' => 'both',
        'status' => 'active'
    ],
    2 => [
        'id' => 2,
        'title' => 'Casa em Taquara',
        'description' => 'Casa aconchegante, com varanda, área de serviço, quintal e garagem. Bairro residencial, silencioso e com fácil acesso ao centro.',
        'price' => 1500.00,
        'rent_price' => 1500.00,
        'bedrooms' => 3,
        'suites' => 0,
        'bathrooms' => 1,
        'living_rooms' => 1,
        'kitchens' => 1,
        'area' => 336.00,
        'address' => 'Rua Professor Nestor Paulo Hartmann, 45',
        'neighborhood' => 'Bairro Recreio',
        'city' => 'Taquara',
        'state' => 'RS',
        'zip_code' => '',
        'type' => 'rent',
        'status' => 'active'
    ],
    3 => [
        'id' => 3,
        'title' => 'Casa em Taquara Alto Padrão',
        'description' => 'Casa moderna, com varanda, área gourmet, jardim e garagem para dois carros. Bairro planejado, seguro e com ótima infraestrutura.',
        'price' => 3000000.00,
        'rent_price' => 0,
        'bedrooms' => 3,
        'suites' => 3,
        'bathrooms' => 2,
        'living_rooms' => 2,
        'kitchens' => 1,
        'area' => 837.00,
        'address' => 'Rua Luiz Capovilla, 3206',
        'neighborhood' => 'Nossa Senhora de Fátima',
        'city' => 'Taquara',
        'state' => 'RS',
        'zip_code' => '',
        'type' => 'sale',
        'status' => 'active'
    ],
    4 => [
        'id' => 4,
        'title' => 'Casa em Taquara Rua Mundo Novo',
        'description' => 'Casa charmosa, área de lazer ampla. Bairro familiar e arborizado.',
        'price' => 170000.00,
        'rent_price' => 1000.00,
        'bedrooms' => 2,
        'suites' => 0,
        'bathrooms' => 1,
        'living_rooms' => 1,
        'kitchens' => 1,
        'area' => 150.00,
        'address' => 'Rua Mundo Novo, 1368',
        'neighborhood' => 'Mundo Novo',
        'city' => 'Taquara',
        'state' => 'RS',
        'zip_code' => '',
        'type' => 'both',
        'status' => 'active'
    ],
    5 => [
        'id' => 5,
        'title' => 'Casa em Taquara Rua Flores da Cunha',
        'description' => 'Casa espaçosa, com varanda, piscina e garagem. Bairro valorizado e com ótima infraestrutura.',
        'price' => 380000.00,
        'rent_price' => 900.00,
        'bedrooms' => 2,
        'suites' => 0,
        'bathrooms' => 1,
        'living_rooms' => 1,
        'kitchens' => 1,
        'area' => 225.00,
        'address' => 'Rua Flores da Cunha, 456',
        'neighborhood' => 'Mundo Novo',
        'city' => 'Taquara',
        'state' => 'RS',
        'zip_code' => '',
        'type' => 'both',
        'status' => 'active'
    ],
    6 => [
        'id' => 6,
        'title' => 'Casa em Parobé',
        'description' => 'Casa confortável e garagem. Bairro tranquilo e arborizado.',
        'price' => 195000.00,
        'rent_price' => 1000.00,
        'bedrooms' => 1,
        'suites' => 0,
        'bathrooms' => 1,
        'living_rooms' => 1,
        'kitchens' => 1,
        'area' => 120.00,
        'address' => 'RUA LAGOINHA, 88',
        'neighborhood' => 'EMANCIPAÇÃO',
        'city' => 'Parobé',
        'state' => 'RS',
        'zip_code' => '',
        'type' => 'both',
        'status' => 'active'
    ],
    7 => [
        'id' => 7,
        'title' => 'Casa em Taquara Bairro Santa Terezinha',
        'description' => 'Casa prática, com quintal, área de serviço e garagem. Bairro residencial e bem localizado.',
        'price' => 650000.00,
        'rent_price' => 2000.00,
        'bedrooms' => 3,
        'suites' => 1,
        'bathrooms' => 1,
        'living_rooms' => 1,
        'kitchens' => 1,
        'area' => 363.00,
        'address' => 'Rua Amazonas, 55',
        'neighborhood' => 'Santa Terezinha',
        'city' => 'Taquara',
        'state' => 'RS',
        'zip_code' => '',
        'type' => 'both',
        'status' => 'active'
    ],
    8 => [
        'id' => 8,
        'title' => 'Casa em Taquara Bairro Tucanos',
        'description' => 'Casa compacta, ideal para solteiros ou casal, com fácil acesso a todos os pontos da cidade.',
        'price' => 184900.00,
        'rent_price' => 0,
        'bedrooms' => 1,
        'suites' => 0,
        'bathrooms' => 1,
        'living_rooms' => 1,
        'kitchens' => 1,
        'area' => 180.00,
        'address' => 'Rua ALVARINO LACERDA FILHO, 1843',
        'neighborhood' => 'Tucanos',
        'city' => 'Taquara',
        'state' => 'RS',
        'zip_code' => '',
        'type' => 'sale',
        'status' => 'active'
    ],
    9 => [
        'id' => 9,
        'title' => 'Casa em Taquara - Rua São Francisco',
        'description' => 'Casa nova, pronta para morar, com quintal, área de serviço e garagem. Bairro em crescimento e com boa infraestrutura.',
        'price' => 450000.00,
        'rent_price' => 0,
        'bedrooms' => 3,
        'suites' => 0,
        'bathrooms' => 2,
        'living_rooms' => 1,
        'kitchens' => 1,
        'area' => 286.00,
        'address' => 'Rua São Francisco, 502',
        'neighborhood' => 'Medianeira',
        'city' => 'Taquara',
        'state' => 'RS',
        'zip_code' => '',
        'type' => 'sale',
        'status' => 'active'
    ]
];

// Carregar dados persistidos do JSON (se houver) ANTES de validar existência
$json_file = __DIR__ . '/data/casas_data.json';
$all_data = [];
if (file_exists($json_file)) {
    $all_data = json_decode(file_get_contents($json_file), true) ?: [];
}

// Verificar se a casa existe (lista estática 1..9 ou criada no JSON)
if (!isset($casas_data[$casa_id]) && !isset($all_data[$casa_id])) {
    $_SESSION['flash_message'] = [
        'type' => 'error',
        'message' => 'Casa não encontrada.'
    ];
    header("Location: gerenciar_imoveis.php");
    exit;
}

// Base: se existir estática, usa-a; senão inicia vazia e usa apenas JSON
if (!isset($casas_data[$casa_id])) {
    $casas_data[$casa_id] = ['id' => $casa_id];
}

// Mesclar dados do JSON por cima da base
if (isset($all_data[$casa_id]) && is_array($all_data[$casa_id])) {
    $casas_data[$casa_id] = array_merge($casas_data[$casa_id], $all_data[$casa_id]);
}

$casa = $casas_data[$casa_id];

// Diretório de imagens da casa
$image_dir = __DIR__ . "/imgs/Casa{$casa_id}/";
$image_url = "imgs/Casa{$casa_id}/";

// Criar diretório se não existir
if (!file_exists($image_dir)) {
    mkdir($image_dir, 0777, true);
}

// Função para obter imagens da casa
function getPropertyImages($casa_id) {
    $image_dir = __DIR__ . "/imgs/Casa{$casa_id}/";
    $images = [];
    
    if (is_dir($image_dir)) {
        $files = scandir($image_dir);
        foreach ($files as $file) {
            if (preg_match('/^Casa' . $casa_id . '\.\d+\.(jpg|jpeg|png|gif)$/i', $file)) {
                $images[] = $file;
            }
        }
        // Ordenar por número
        usort($images, function($a, $b) {
            preg_match('/\.(\d+)\./', $a, $match_a);
            preg_match('/\.(\d+)\./', $b, $match_b);
            return intval($match_a[1]) - intval($match_b[1]);
        });
    }
    
    return $images;
}

// Processar exclusão de imagem
if (isset($_POST['delete_image'])) {
    $image_to_delete = basename($_POST['delete_image']);
    $file_path = $image_dir . $image_to_delete;
    
    if (file_exists($file_path) && preg_match('/^Casa' . $casa_id . '\.\d+\.(jpg|jpeg|png|gif)$/i', $image_to_delete)) {
        if (unlink($file_path)) {
            $_SESSION['flash_message'] = [
                'type' => 'success',
                'message' => 'Imagem excluída com sucesso!'
            ];
        } else {
            $_SESSION['flash_message'] = [
                'type' => 'error',
                'message' => 'Erro ao excluir imagem.'
            ];
        }
    }
    header("Location: editar_casa.php?id=" . $casa_id);
    exit;
}

// Processar upload de imagens
if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
    error_log("=== PROCESSANDO UPLOAD DE IMAGENS ===");
    error_log("Casa ID: " . $casa_id);
    error_log("Image dir: " . $image_dir);
    error_log("FILES data: " . print_r($_FILES['images'], true));
    
    $existing_images = getPropertyImages($casa_id);
    $next_number = count($existing_images);
    
    error_log("Imagens existentes: " . count($existing_images));
    error_log("Próximo número: " . $next_number);
    
    $upload_errors = [];
    $upload_success = 0;
    
    foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
        error_log("Processando arquivo $key: {$_FILES['images']['name'][$key]}");
        
        if ($_FILES['images']['error'][$key] !== UPLOAD_ERR_OK) {
            $error_messages = [
                UPLOAD_ERR_INI_SIZE => 'Arquivo excede o tamanho máximo permitido pelo servidor',
                UPLOAD_ERR_FORM_SIZE => 'Arquivo excede o tamanho máximo do formulário',
                UPLOAD_ERR_PARTIAL => 'Upload parcial - tente novamente',
                UPLOAD_ERR_NO_FILE => 'Nenhum arquivo foi enviado',
                UPLOAD_ERR_NO_TMP_DIR => 'Pasta temporária ausente',
                UPLOAD_ERR_CANT_WRITE => 'Falha ao escrever no disco',
                UPLOAD_ERR_EXTENSION => 'Upload bloqueado por extensão'
            ];
            $error_msg = $error_messages[$_FILES['images']['error'][$key]] ?? 'Erro desconhecido';
            $upload_errors[] = "{$_FILES['images']['name'][$key]}: $error_msg";
            error_log("Erro no upload: " . $error_msg);
            continue;
        }
        
        $file_extension = strtolower(pathinfo($_FILES['images']['name'][$key], PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (!in_array($file_extension, $allowed_extensions)) {
            $upload_errors[] = "Arquivo {$_FILES['images']['name'][$key]} tem extensão inválida. Use: JPG, PNG ou GIF";
            error_log("Extensão inválida: " . $file_extension);
            continue;
        }
        
        // Verificar tamanho (max 5MB)
        if ($_FILES['images']['size'][$key] > 5242880) {
            $size_mb = round($_FILES['images']['size'][$key] / 1024 / 1024, 2);
            $upload_errors[] = "Arquivo {$_FILES['images']['name'][$key]} é muito grande ({$size_mb}MB). Máximo: 5MB";
            error_log("Arquivo muito grande: " . $size_mb . "MB");
            continue;
        }
        
        // Verificar se o diretório existe e tem permissão de escrita
        if (!is_dir($image_dir)) {
            if (!mkdir($image_dir, 0777, true)) {
                $upload_errors[] = "Erro ao criar diretório de imagens";
                error_log("Falha ao criar diretório: " . $image_dir);
                continue;
            }
        }
        
        if (!is_writable($image_dir)) {
            $upload_errors[] = "Sem permissão para salvar no diretório";
            error_log("Diretório sem permissão de escrita: " . $image_dir);
            continue;
        }
        
        // Gerar nome do arquivo mantendo a extensão original
        $new_filename = "Casa{$casa_id}.{$next_number}." . $file_extension;
        $destination = $image_dir . $new_filename;
        
        error_log("Tentando mover de: " . $tmp_name . " para: " . $destination);
        
        if (move_uploaded_file($tmp_name, $destination)) {
            error_log("Upload bem-sucedido: " . $new_filename);
            $upload_success++;
            $next_number++;
        } else {
            $upload_errors[] = "Erro ao salvar {$_FILES['images']['name'][$key]}. Verifique as permissões da pasta.";
            error_log("Falha no move_uploaded_file");
        }
    }
    
    if ($upload_success > 0) {
        $_SESSION['flash_message'] = [
            'type' => 'success',
            'message' => "{$upload_success} imagem(ns) adicionada(s) com sucesso!"
        ];
    }
    
    if (!empty($upload_errors)) {
        $_SESSION['flash_message'] = [
            'type' => 'warning',
            'message' => implode('<br>', $upload_errors)
        ];
    }
    
    header("Location: editar_casa.php?id=" . $casa_id);
    exit;
}

// Processar atualização de dados
if (isset($_POST['update_property'])) {
    // Debug log
    error_log("=== PROCESSANDO UPDATE_PROPERTY ===");
    error_log("POST data: " . print_r($_POST, true));
    
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $rent_price = isset($_POST['rent_price']) ? floatval($_POST['rent_price']) : 0;
    $bedrooms = intval($_POST['bedrooms']);
    $suites = isset($_POST['suites']) ? intval($_POST['suites']) : 0;
    $bathrooms = intval($_POST['bathrooms']);
    $living_rooms = isset($_POST['living_rooms']) ? intval($_POST['living_rooms']) : 1;
    $kitchens = isset($_POST['kitchens']) ? intval($_POST['kitchens']) : 1;
    $area = floatval($_POST['area']);
    $address = trim($_POST['address']);
    $neighborhood = trim($_POST['neighborhood']);
    $city = trim($_POST['city']);
    $state = trim($_POST['state']);
    $zip_code = trim($_POST['zip_code']);
    $type = $_POST['type'];
    $status = $_POST['status'];
    
    // Validação básica
    $errors = [];
    if (empty($title)) $errors[] = "Título é obrigatório";
    if (empty($description)) $errors[] = "Descrição é obrigatória";
    if ($price <= 0 && $rent_price <= 0) $errors[] = "Preço de venda ou aluguel deve ser maior que zero";
    if ($bedrooms <= 0) $errors[] = "Número de quartos deve ser maior que zero";
    if ($bathrooms <= 0) $errors[] = "Número de banheiros deve ser maior que zero";
    if ($area <= 0) $errors[] = "Área deve ser maior que zero";
    if (empty($address)) $errors[] = "Endereço é obrigatório";
    if (empty($city)) $errors[] = "Cidade é obrigatória";
    if (empty($state)) $errors[] = "Estado é obrigatório";
    
    if (empty($errors)) {
        // Atualizar dados no array (em produção, salvaria no banco de dados)
        $casas_data[$casa_id] = [
            'id' => $casa_id,
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
            'status' => $status
        ];
        
        // Salvar em arquivo JSON temporário para persistência
        $json_file = __DIR__ . '/data/casas_data.json';
        $json_dir = dirname($json_file);
        if (!file_exists($json_dir)) {
            mkdir($json_dir, 0777, true);
        }
        
        // Carregar dados existentes ou criar novo array
        $all_data = [];
        if (file_exists($json_file)) {
            $all_data = json_decode(file_get_contents($json_file), true) ?: [];
        }
        
        // Atualizar dados da casa específica
        $all_data[$casa_id] = $casas_data[$casa_id];
        
        // Salvar de volta
        file_put_contents($json_file, json_encode($all_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        
        $_SESSION['flash_message'] = [
            'type' => 'success',
            'message' => 'Propriedade "' . htmlspecialchars($title) . '" foi atualizada com sucesso!'
        ];
        header("Location: editar_casa.php?id=" . $casa_id);
        exit;
    }
}

// Obter imagens atuais
$current_images = getPropertyImages($casa_id);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Editar <?php echo htmlspecialchars($casa['title']); ?> - Company Miguel</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet" />
    <style>
        body {
            background-color: #ffffff !important;
            min-height: 100vh;
        }
        .masthead {
            background: #ffffff !important;
            padding: 3rem 0;
        }
        .contact-section {
            background: #ffffff !important;
        }
        h1, h2 {
            color: #000000 !important;
        }
        .image-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        .image-item {
            position: relative;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            aspect-ratio: 1;
        }
        .image-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .image-item .delete-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(220, 53, 69, 0.9);
            border: none;
            color: white;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.3s;
        }
        .image-item .delete-btn:hover {
            background: rgba(220, 53, 69, 1);
        }
        .upload-area {
            border: 2px dashed #007bff;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            background: #f8f9fa;
            cursor: pointer;
            transition: all 0.3s;
        }
        .upload-area:hover {
            background: #e9ecef;
            border-color: #0056b3;
        }
        .upload-area.dragover {
            background: #d1ecf1;
            border-color: #0c5460;
        }
    </style>
</head>

<body id="page-top">
    <!-- Navigation -->
    <?php 
    if (function_exists('renderNavbar')) {
        renderNavbar($current_user, 'gerenciar_imoveis');
    }
    ?>

    <!-- Header -->
    <header class="masthead">
        <div class="container px-4 px-lg-5 d-flex h-100 align-items-center justify-content-center">
            <div class="d-flex justify-content-center">
                <div class="text-center">
                    <h1 class="mx-auto my-0 text-uppercase">Editar Imóvel</h1>
                    <h2 class="mx-auto mt-2 mb-5"><?php echo htmlspecialchars($casa['title']); ?></h2>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <section class="contact-section bg-white" id="edit-property">
        <div class="container px-4 px-lg-5">
            <!-- Mensagens Flash -->
            <?php if (isset($_SESSION['flash_message'])): 
                $flash = $_SESSION['flash_message'];
                unset($_SESSION['flash_message']);
            ?>
                <div class="alert alert-<?php echo $flash['type'] === 'error' ? 'danger' : $flash['type']; ?> alert-dismissible fade show" role="alert">
                    <?php echo $flash['message']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Mensagens de erro -->
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger" role="alert">
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- Formulário de Edição de Dados -->

            <div class="row gx-4 gx-lg-5">
                <div class="col-md-10 col-lg-8 mx-auto">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="text-black mb-0">
                                <i class="fas fa-edit me-2"></i>Editar Propriedade
                            </h4>
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <input type="hidden" name="update_property" value="1">
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label for="title" class="form-label text-black">Título *</label>
                                        <input type="text" class="form-control" id="title" name="title" 
                                               value="<?php echo htmlspecialchars($casa['title']); ?>" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label text-black">Descrição *</label>
                                    <textarea class="form-control" id="description" name="description" rows="4" required><?php echo htmlspecialchars($casa['description']); ?></textarea>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="price" class="form-label text-black">Preço Venda (R$)</label>
                                        <input type="number" step="0.01" class="form-control" id="price" name="price" 
                                               value="<?php echo $casa['price']; ?>" min="0">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="rent_price" class="form-label text-black">Preço Aluguel (R$)</label>
                                        <input type="number" step="0.01" class="form-control" id="rent_price" name="rent_price" 
                                               value="<?php echo isset($casa['rent_price']) ? $casa['rent_price'] : 0; ?>" min="0">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label for="bedrooms" class="form-label text-black">Quartos *</label>
                                        <input type="number" class="form-control" id="bedrooms" name="bedrooms" 
                                               value="<?php echo $casa['bedrooms']; ?>" min="1" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="suites" class="form-label text-black">Suítes</label>
                                        <input type="number" class="form-control" id="suites" name="suites" 
                                               value="<?php echo isset($casa['suites']) ? $casa['suites'] : 0; ?>" min="0">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="bathrooms" class="form-label text-black">Banheiros *</label>
                                        <input type="number" class="form-control" id="bathrooms" name="bathrooms" 
                                               value="<?php echo $casa['bathrooms']; ?>" min="1" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="area" class="form-label text-black">Área (m²) *</label>
                                        <input type="number" step="0.01" class="form-control" id="area" name="area" 
                                               value="<?php echo $casa['area']; ?>" min="1" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="living_rooms" class="form-label text-black">Salas</label>
                                        <input type="number" class="form-control" id="living_rooms" name="living_rooms" 
                                               value="<?php echo isset($casa['living_rooms']) ? $casa['living_rooms'] : 1; ?>" min="0">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="kitchens" class="form-label text-black">Cozinhas</label>
                                        <input type="number" class="form-control" id="kitchens" name="kitchens" 
                                               value="<?php echo isset($casa['kitchens']) ? $casa['kitchens'] : 1; ?>" min="0">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-8">
                                        <label for="address" class="form-label text-black">Endereço *</label>
                                        <input type="text" class="form-control" id="address" name="address" 
                                               value="<?php echo htmlspecialchars($casa['address']); ?>" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="neighborhood" class="form-label text-black">Bairro</label>
                                        <input type="text" class="form-control" id="neighborhood" name="neighborhood" 
                                               value="<?php echo htmlspecialchars($casa['neighborhood']); ?>">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="city" class="form-label text-black">Cidade *</label>
                                        <input type="text" class="form-control" id="city" name="city" 
                                               value="<?php echo htmlspecialchars($casa['city']); ?>" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="state" class="form-label text-black">Estado *</label>
                                        <input type="text" class="form-control" id="state" name="state" 
                                               value="<?php echo htmlspecialchars($casa['state']); ?>" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="zip_code" class="form-label text-black">CEP</label>
                                        <input type="text" class="form-control" id="zip_code" name="zip_code" 
                                               value="<?php echo htmlspecialchars($casa['zip_code']); ?>">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="type" class="form-label text-black">Tipo de Transação</label>
                                        <select class="form-control" id="type" name="type">
                                            <option value="sale" <?php echo ($casa['type'] === 'sale') ? 'selected' : ''; ?>>Venda</option>
                                            <option value="rent" <?php echo ($casa['type'] === 'rent') ? 'selected' : ''; ?>>Aluguel</option>
                                            <option value="both" <?php echo ($casa['type'] === 'both') ? 'selected' : ''; ?>>Venda e Aluguel</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="status" class="form-label text-black">Status</label>
                                        <select class="form-control" id="status" name="status">
                                            <option value="active" <?php echo ($casa['status'] === 'active') ? 'selected' : ''; ?>>Ativo</option>
                                            <option value="pending" <?php echo ($casa['status'] === 'pending') ? 'selected' : ''; ?>>Pendente</option>
                                            <option value="sold" <?php echo ($casa['status'] === 'sold') ? 'selected' : ''; ?>>Vendido</option>
                                            <option value="rented" <?php echo ($casa['status'] === 'rented') ? 'selected' : ''; ?>>Alugado</option>
                                            <option value="inactive" <?php echo ($casa['status'] === 'inactive') ? 'selected' : ''; ?>>Inativo</option>
                                        </select>
                                    </div>
                                </div>

                                <hr class="my-4">

                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        Salvar Alterações
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Seção de Gerenciamento de Fotos (Separada) -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="text-black mb-0">
                                <i class="fas fa-images me-2"></i>Gerenciar Fotos
                            </h5>
                        </div>
                        <div class="card-body">
                            <!-- Galeria de Imagens Atuais -->
                            <?php if (!empty($current_images)): ?>
                                <p class="text-muted mb-3">Fotos Atuais: <?php echo count($current_images); ?> imagem(ns)</p>
                                <div class="image-gallery mb-4">
                                    <?php foreach ($current_images as $image): ?>
                                        <div class="image-item">
                                            <img src="<?php echo $image_url . $image; ?>" alt="<?php echo htmlspecialchars($image); ?>">
                                            <form method="POST" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja excluir esta imagem?');">
                                                <input type="hidden" name="delete_image" value="<?php echo htmlspecialchars($image); ?>">
                                                <button type="submit" class="delete-btn" title="Excluir imagem">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info mb-3">
                                    <i class="fas fa-info-circle me-2"></i>Nenhuma foto cadastrada ainda.
                                </div>
                            <?php endif; ?>

                            <!-- Botão para Adicionar Fotos -->
                            <button type="button" class="btn btn-outline-primary mb-3" data-bs-toggle="collapse" data-bs-target="#uploadSection">
                                <i class="fas fa-plus-circle me-1"></i>Adicionar Novas Fotos
                            </button>

                            <!-- Área de Upload (Colapsável) -->
                            <div class="collapse show mb-4" id="uploadSection">
                                <div class="border rounded p-3 bg-light">
                                    <form method="POST" enctype="multipart/form-data" id="uploadForm">
                                        <div class="mb-3">
                                            <label for="imageInput" class="form-label text-black">
                                                <i class="fas fa-images me-2"></i>Selecione as imagens para upload:
                                            </label>
                                            <input type="file" name="images[]" id="imageInput" multiple accept="image/jpeg,image/jpg,image/png,image/gif" class="form-control">
                                            <div class="form-text">
                                                Formatos aceitos: JPG, JPEG, PNG, GIF | Tamanho máximo: 5MB por arquivo | Selecione múltiplos arquivos
                                            </div>
                                        </div>
                                        
                                        <div id="filePreview" class="mb-3"></div>
                                        
                                        <button type="submit" class="btn btn-success" id="uploadBtn" style="display:none;">
                                            <i class="fas fa-upload me-1"></i>Fazer Upload das Fotos
                                        </button>
                                    </form>
                                </div>
                            </div>
                            
                            <!-- Botão Voltar -->
                            <?php
                                $back_link = file_exists(__DIR__ . "/Casas/Casa{$casa_id}.php")
                                    ? ("Casas/Casa{$casa_id}.php")
                                    : 'admin.php';
                            ?>
                            <div class="text-center mt-4">
                                <a href="<?php echo $back_link; ?>" class="btn btn-secondary btn-lg">
                                    Voltar
                                </a>
                            </div>
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

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>
    
    <script>
        // Elementos
        const imageInput = document.getElementById('imageInput');
        const filePreview = document.getElementById('filePreview');
        const uploadBtn = document.getElementById('uploadBtn');

        // Quando selecionar arquivos
        imageInput.addEventListener('change', function() {
            const files = this.files;
            if (files.length > 0) {
                let previewHTML = '<div class="alert alert-info"><strong>Arquivos selecionados para upload:</strong><ul class="mb-0 mt-2">';
                
                Array.from(files).forEach((file, index) => {
                    const size = (file.size / 1024 / 1024).toFixed(2);
                    const icon = file.type.startsWith('image/') ? 'fa-image' : 'fa-file';
                    const sizeClass = file.size > 5242880 ? 'text-danger' : 'text-success';
                    previewHTML += `<li><i class="fas ${icon} me-2"></i>${file.name} <span class="${sizeClass}">(${size} MB)</span></li>`;
                });
                
                previewHTML += '</ul></div>';
                filePreview.innerHTML = previewHTML;
                uploadBtn.style.display = 'inline-block';
            } else {
                filePreview.innerHTML = '';
                uploadBtn.style.display = 'none';
            }
        });
    </script>
</body>
</html>
