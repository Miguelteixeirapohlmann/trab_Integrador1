<?php
/**
 * Página para Editar Casa Específica
 */

require_once 'includes/init.php';

// Verificar se o usuário está logado e é corretor
$auth->requireLogin('Login.php');
$current_user = $auth->getCurrentUser();

// Verificar se é corretor
if ($current_user['user_type'] !== 'corretor') {
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
        'title' => 'Casa 1 - Residencial Nova Vista',
        'description' => 'Linda casa térrea com 3 quartos, sendo 1 suíte, sala ampla, cozinha planejada, área de serviço e quintal. Localizada em bairro residencial tranquilo.',
        'price' => 450000.00,
        'bedrooms' => 3,
        'bathrooms' => 2,
        'area' => 180.50,
        'address' => 'Rua das Flores, 123',
        'neighborhood' => 'Nova Vista',
        'city' => 'São Paulo',
        'state' => 'SP',
        'zip_code' => '01234-567',
        'type' => 'sale',
        'status' => 'active'
    ],
    2 => [
        'id' => 2,
        'title' => 'Casa 2 - Condomínio Verde Mar',
        'description' => 'Casa duplex moderna com 4 quartos, sendo 2 suítes, sala de estar e jantar integradas, cozinha gourmet, lavabo, área gourmet com piscina.',
        'price' => 680000.00,
        'bedrooms' => 4,
        'bathrooms' => 3,
        'area' => 250.00,
        'address' => 'Alameda dos Ipês, 456',
        'neighborhood' => 'Verde Mar',
        'city' => 'São Paulo',
        'state' => 'SP',
        'zip_code' => '02345-678',
        'type' => 'both',
        'status' => 'active'
    ],
    3 => [
        'id' => 3,
        'title' => 'Casa 3 - Bairro Central',
        'description' => 'Casa comercial e residencial, ideal para quem quer morar e trabalhar no mesmo local. 2 quartos, sala, cozinha, banheiro e espaço comercial na frente.',
        'price' => 350000.00,
        'bedrooms' => 2,
        'bathrooms' => 1,
        'area' => 120.00,
        'address' => 'Rua Principal, 789',
        'neighborhood' => 'Centro',
        'city' => 'São Paulo',
        'state' => 'SP',
        'zip_code' => '03456-789',
        'type' => 'rent',
        'status' => 'active'
    ]
];

// Verificar se a casa existe
if (!isset($casas_data[$casa_id])) {
    $_SESSION['flash_message'] = [
        'type' => 'error',
        'message' => 'Casa não encontrada.'
    ];
    header("Location: gerenciar_imoveis.php");
    exit;
}

$casa = $casas_data[$casa_id];

// Processar atualização
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $bedrooms = intval($_POST['bedrooms']);
    $bathrooms = intval($_POST['bathrooms']);
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
    if ($price <= 0) $errors[] = "Preço deve ser maior que zero";
    if ($bedrooms <= 0) $errors[] = "Número de quartos deve ser maior que zero";
    if ($bathrooms <= 0) $errors[] = "Número de banheiros deve ser maior que zero";
    if ($area <= 0) $errors[] = "Área deve ser maior que zero";
    if (empty($address)) $errors[] = "Endereço é obrigatório";
    
    if (empty($errors)) {
        // Aqui você normalmente salvaria no banco de dados
        $_SESSION['flash_message'] = [
            'type' => 'success',
            'message' => 'Casa "' . htmlspecialchars($title) . '" foi atualizada com sucesso!'
        ];
        header("Location: gerenciar_imoveis.php");
        exit;
    }
}
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
                    <h2 class="text-white-50 mx-auto mt-2 mb-5"><?php echo htmlspecialchars($casa['title']); ?></h2>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <section class="contact-section bg-black" id="edit-property">
        <div class="container px-4 px-lg-5">
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
                                <div class="row mb-3">
                                    <div class="col-md-8">
                                        <label for="title" class="form-label text-black">Título *</label>
                                        <input type="text" class="form-control" id="title" name="title" 
                                               value="<?php echo htmlspecialchars($casa['title']); ?>" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="price" class="form-label text-black">Preço (R$) *</label>
                                        <input type="number" step="0.01" class="form-control" id="price" name="price" 
                                               value="<?php echo $casa['price']; ?>" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label text-black">Descrição *</label>
                                    <textarea class="form-control" id="description" name="description" rows="4" required><?php echo htmlspecialchars($casa['description']); ?></textarea>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="bedrooms" class="form-label text-black">Quartos *</label>
                                        <input type="number" class="form-control" id="bedrooms" name="bedrooms" 
                                               value="<?php echo $casa['bedrooms']; ?>" min="1" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="bathrooms" class="form-label text-black">Banheiros *</label>
                                        <input type="number" class="form-control" id="bathrooms" name="bathrooms" 
                                               value="<?php echo $casa['bathrooms']; ?>" min="1" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="area" class="form-label text-black">Área (m²) *</label>
                                        <input type="number" step="0.01" class="form-control" id="area" name="area" 
                                               value="<?php echo $casa['area']; ?>" min="1" required>
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

                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary me-3">
                                        <i class="fas fa-save me-1"></i>Salvar Alterações
                                    </button>
                                    <a href="gerenciar_imoveis.php" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-1"></i>Voltar
                                    </a>
                                </div>
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

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>
</body>
</html>
