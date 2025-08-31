<?php
/**
 * Página de Gerenciamento de Imóveis para Corretor
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

// Verificar mensagens flash
$flash = getFlashMessage();

// Dados das 3 casas (hardcoded para demonstração)
$casas = [
    [
        'id' => 1,
        'title' => 'Casa 1 - Residencial Nova Vista',
        'description' => 'Linda casa térrea com 3 quartos, sendo 1 suíte, sala ampla, cozinha planejada, área de serviço e quintal. Localizada em bairro residencial tranquilo.',
        'price' => 450000.00,
        'bedrooms' => 3,
        'bathrooms' => 2,
        'area' => 180.50,
        'address' => 'Rua das Flores, 123 - Nova Vista',
        'status' => 'Disponível',
        'type' => 'Venda',
        'image' => 'imgs/Casa1/Casa1.0.jpg'
    ],
    [
        'id' => 2,
        'title' => 'Casa 2 - Condomínio Verde Mar',
        'description' => 'Casa duplex moderna com 4 quartos, sendo 2 suítes, sala de estar e jantar integradas, cozinha gourmet, lavabo, área gourmet com piscina.',
        'price' => 680000.00,
        'bedrooms' => 4,
        'bathrooms' => 3,
        'area' => 250.00,
        'address' => 'Alameda dos Ipês, 456 - Verde Mar',
        'status' => 'Disponível',
        'type' => 'Venda/Aluguel',
        'image' => 'imgs/Casa2/Casa2.0.jpg'
    ],
    [
        'id' => 3,
        'title' => 'Casa 3 - Bairro Central',
        'description' => 'Casa comercial e residencial, ideal para quem quer morar e trabalhar no mesmo local. 2 quartos, sala, cozinha, banheiro e espaço comercial na frente.',
        'price' => 350000.00,
        'bedrooms' => 2,
        'bathrooms' => 1,
        'area' => 120.00,
        'address' => 'Rua Principal, 789 - Centro',
        'status' => 'Disponível',
        'type' => 'Aluguel',
        'image' => 'imgs/Casa3/Casa3.0.jpg'
    ]
];

// Processar exclusão
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'excluir') {
    $casa_id = intval($_POST['casa_id']);
    
    // Aqui você normalmente removeria do banco de dados
    // Por enquanto, apenas mostrar mensagem de sucesso
    $flash = [
        'type' => 'success',
        'message' => "Casa {$casa_id} foi excluída com sucesso!"
    ];
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Gerenciar Imóveis - Company Miguel</title>
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
                    <h1 class="mx-auto my-0 text-uppercase">Meus Imóveis</h1>
                    <h2 class="text-white-50 mx-auto mt-2 mb-5">Gerencie suas propriedades</h2>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <section class="contact-section bg-black" id="imoveis">
        <div class="container px-4 px-lg-5">
            
            <!-- Mensagens Flash -->
            <?php if ($flash): ?>
                <div class="alert alert-<?php echo $flash['type']; ?> alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($flash['message']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <!-- Estatísticas -->
            <div class="row gx-4 gx-lg-5 mb-5">
                <div class="col-md-4 mb-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-home fa-2x mb-2"></i>
                            <h4>3</h4>
                            <p class="mb-0">Total de Imóveis</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-check-circle fa-2x mb-2"></i>
                            <h4>3</h4>
                            <p class="mb-0">Disponíveis</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-eye fa-2x mb-2"></i>
                            <h4>25</h4>
                            <p class="mb-0">Visualizações</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de Imóveis -->
            <div class="row gx-4 gx-lg-5">
                <?php foreach ($casas as $casa): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100">
                            <img src="<?php echo $casa['image']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($casa['title']); ?>" style="height: 200px; object-fit: cover;">
                            
                            <div class="card-body">
                                <h5 class="card-title text-black"><?php echo htmlspecialchars($casa['title']); ?></h5>
                                <p class="card-text text-muted small"><?php echo htmlspecialchars(substr($casa['description'], 0, 100)) . '...'; ?></p>
                                
                                <div class="row text-center mb-3">
                                    <div class="col-4">
                                        <small class="text-muted">Quartos</small>
                                        <div class="fw-bold text-black"><?php echo $casa['bedrooms']; ?></div>
                                    </div>
                                    <div class="col-4">
                                        <small class="text-muted">Banheiros</small>
                                        <div class="fw-bold text-black"><?php echo $casa['bathrooms']; ?></div>
                                    </div>
                                    <div class="col-4">
                                        <small class="text-muted">Área</small>
                                        <div class="fw-bold text-black"><?php echo $casa['area']; ?>m²</div>
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <i class="fas fa-map-marker-alt text-primary me-1"></i>
                                    <span class="text-black small"><?php echo htmlspecialchars($casa['address']); ?></span>
                                </div>

                                <div class="mb-3">
                                    <span class="badge bg-primary me-1"><?php echo $casa['type']; ?></span>
                                    <span class="badge bg-success"><?php echo $casa['status']; ?></span>
                                </div>

                                <div class="h5 text-primary fw-bold">
                                    R$ <?php echo number_format($casa['price'], 2, ',', '.'); ?>
                                </div>
                            </div>

                            <div class="card-footer">
                                <div class="btn-group w-100" role="group">
                                    <a href="editar_casa.php?id=<?php echo $casa['id']; ?>" 
                                       class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-edit me-1"></i>Editar
                                    </a>
                                    <a href="Casas/Casa<?php echo $casa['id']; ?>.php" 
                                       class="btn btn-outline-info btn-sm" target="_blank">
                                        <i class="fas fa-eye me-1"></i>Ver
                                    </a>
                                    <button type="button" class="btn btn-outline-danger btn-sm" 
                                            onclick="confirmarExclusao(<?php echo $casa['id']; ?>, '<?php echo htmlspecialchars($casa['title']); ?>')">
                                        <i class="fas fa-trash me-1"></i>Excluir
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Botão para adicionar nova propriedade -->
            <div class="text-center mt-4">
                <a href="adicionar_imovel.php" class="btn btn-success btn-lg">
                    <i class="fas fa-plus me-2"></i>Adicionar Novo Imóvel
                </a>
            </div>
        </div>
    </section>

    <!-- Modal de Confirmação de Exclusão -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirmar Exclusão</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Tem certeza que deseja excluir o imóvel "<span id="propertyName"></span>"?
                    <br><br>
                    <strong>Esta ação não pode ser desfeita!</strong>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Excluir</button>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-light py-5">
        <div class="container px-4 px-lg-5">
            <div class="small text-center text-muted">Copyright &copy; 2025 - Company Miguel</div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>
    
    <script>
        let propertyToDelete = null;

        function confirmarExclusao(propertyId, propertyName) {
            propertyToDelete = propertyId;
            document.getElementById('propertyName').textContent = propertyName;
            
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        }

        document.getElementById('confirmDelete').addEventListener('click', function() {
            if (propertyToDelete) {
                // Criar formulário para excluir
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '';
                
                const actionInput = document.createElement('input');
                actionInput.type = 'hidden';
                actionInput.name = 'action';
                actionInput.value = 'excluir';
                
                const idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.name = 'casa_id';
                idInput.value = propertyToDelete;
                
                form.appendChild(actionInput);
                form.appendChild(idInput);
                document.body.appendChild(form);
                form.submit();
            }
        });

        // Auto dismiss flash messages
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });
    </script>
</body>
</html>
