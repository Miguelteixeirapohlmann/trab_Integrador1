<?php
/**
 * Página Ver Agendamentos - Para Corretores
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
    header('Location: index.php');
    exit;
}

// Buscar visitas agendadas para este corretor
$visits_stmt = $pdo->prepare("
    SELECT pv.*, 
           p.title as property_title,
           p.address as property_address,
           p.neighborhood,
           p.city,
           u.first_name,
           u.last_name,
           u.email as user_email,
           u.phone as user_phone
    FROM property_visits pv
    JOIN properties p ON pv.property_id = p.id
    LEFT JOIN users u ON pv.user_id = u.id
    WHERE pv.broker_id = ?
    ORDER BY pv.visit_date ASC
");
$visits_stmt->execute([$broker_data['id']]);
$scheduled_visits = $visits_stmt->fetchAll();

// Separar visitas por status
$upcoming_visits = [];
$completed_visits = [];
$cancelled_visits = [];

foreach ($scheduled_visits as $visit) {
    switch ($visit['status']) {
        case 'scheduled':
        case 'confirmed':
            $upcoming_visits[] = $visit;
            break;
        case 'completed':
            $completed_visits[] = $visit;
            break;
        case 'cancelled':
        case 'no_show':
            $cancelled_visits[] = $visit;
            break;
    }
}

// Verificar mensagens flash
$flash = getFlashMessage();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Agendamentos - <?php echo APP_NAME ?? 'Imobiliária'; ?></title>
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
    renderNavbar($current_user, 'agendamentos');
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
                    <h1 class="text-white font-weight-bold">Agendamentos de Visitas</h1>
                    <hr class="divider" />
                </div>
                <div class="col-lg-8 align-self-baseline">
                    <p class="text-white-75 mb-5">Gerencie todas as visitas agendadas para seus imóveis.</p>
                </div>
            </div>
        </div>
    </header>

    <!-- Seção de Agendamentos -->
    <section class="page-section" id="agendamentos">
        <div class="container px-4 px-lg-5">
            <!-- Estatísticas Rápidas -->
            <div class="row mb-5">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card bg-primary text-white h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h4 class="mb-0"><?php echo count($upcoming_visits); ?></h4>
                                    <p class="mb-0">Visitas Pendentes</p>
                                </div>
                                <div class="ms-3">
                                    <i class="fas fa-clock fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card bg-success text-white h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h4 class="mb-0"><?php echo count($completed_visits); ?></h4>
                                    <p class="mb-0">Visitas Realizadas</p>
                                </div>
                                <div class="ms-3">
                                    <i class="fas fa-check-circle fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card bg-warning text-white h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h4 class="mb-0"><?php echo count($cancelled_visits); ?></h4>
                                    <p class="mb-0">Visitas Canceladas</p>
                                </div>
                                <div class="ms-3">
                                    <i class="fas fa-times-circle fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs para diferentes tipos de visitas -->
            <div class="row">
                <div class="col-12">
                    <ul class="nav nav-tabs" id="visitTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="upcoming-tab" data-bs-toggle="tab" data-bs-target="#upcoming" type="button" role="tab">
                                <i class="fas fa-clock me-2"></i>Próximas Visitas (<?php echo count($upcoming_visits); ?>)
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed" type="button" role="tab">
                                <i class="fas fa-check me-2"></i>Realizadas (<?php echo count($completed_visits); ?>)
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="cancelled-tab" data-bs-toggle="tab" data-bs-target="#cancelled" type="button" role="tab">
                                <i class="fas fa-times me-2"></i>Canceladas (<?php echo count($cancelled_visits); ?>)
                            </button>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="tab-content mt-4" id="visitTabContent">
                <!-- Próximas Visitas -->
                <div class="tab-pane fade show active" id="upcoming" role="tabpanel">
                    <?php if (empty($upcoming_visits)): ?>
                        <div class="alert alert-info text-center">
                            <i class="fas fa-calendar-times fa-3x mb-3"></i>
                            <h4>Nenhuma visita pendente</h4>
                            <p>Você não possui visitas agendadas no momento.</p>
                        </div>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach ($upcoming_visits as $visit): ?>
                                <div class="col-lg-6 col-md-12 mb-4">
                                    <div class="card h-100 shadow border-left-primary">
                                        <div class="card-header bg-primary text-white">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h5 class="mb-0"><?php echo htmlspecialchars($visit['property_title']); ?></h5>
                                                <span class="badge bg-light text-dark"><?php echo ucfirst($visit['status']); ?></span>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <h6><i class="fas fa-calendar-alt me-2"></i>Data e Horário</h6>
                                                <p class="text-primary fs-5 fw-bold">
                                                    <?php echo date('d/m/Y \à\s H:i', strtotime($visit['visit_date'])); ?>
                                                </p>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <h6><i class="fas fa-map-marker-alt me-2"></i>Endereço</h6>
                                                <p><?php echo htmlspecialchars($visit['property_address'] . ', ' . $visit['neighborhood'] . ', ' . $visit['city']); ?></p>
                                            </div>

                                            <div class="mb-3">
                                                <h6><i class="fas fa-user me-2"></i>Cliente</h6>
                                                <p><strong><?php echo htmlspecialchars($visit['visitor_name']); ?></strong></p>
                                                <p class="mb-1"><i class="fas fa-envelope me-2"></i><?php echo htmlspecialchars($visit['visitor_email']); ?></p>
                                                <p class="mb-0"><i class="fas fa-phone me-2"></i><?php echo htmlspecialchars($visit['visitor_phone']); ?></p>
                                            </div>

                                            <?php if ($visit['message']): ?>
                                                <div class="mb-3">
                                                    <h6><i class="fas fa-comment me-2"></i>Mensagem do Cliente</h6>
                                                    <div class="alert alert-light">
                                                        <?php echo nl2br(htmlspecialchars($visit['message'])); ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>

                                            <div class="mb-3">
                                                <small class="text-muted">
                                                    <i class="fas fa-info-circle me-1"></i>
                                                    Tipo: <?php echo ucfirst($visit['visit_type']); ?> | 
                                                    Agendado em: <?php echo date('d/m/Y H:i', strtotime($visit['created_at'])); ?>
                                                </small>
                                            </div>
                                        </div>
                                        
                                        <div class="card-footer">
                                            <div class="btn-group w-100" role="group">
                                                <button type="button" class="btn btn-success btn-sm">
                                                    <i class="fas fa-check me-1"></i>Confirmar
                                                </button>
                                                <button type="button" class="btn btn-warning btn-sm">
                                                    <i class="fas fa-clock me-1"></i>Reagendar
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-times me-1"></i>Cancelar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Visitas Realizadas -->
                <div class="tab-pane fade" id="completed" role="tabpanel">
                    <?php if (empty($completed_visits)): ?>
                        <div class="alert alert-info text-center">
                            <i class="fas fa-check-circle fa-3x mb-3"></i>
                            <h4>Nenhuma visita realizada</h4>
                            <p>Você ainda não possui visitas concluídas.</p>
                        </div>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach ($completed_visits as $visit): ?>
                                <div class="col-lg-6 col-md-12 mb-4">
                                    <div class="card h-100 shadow border-left-success">
                                        <div class="card-header bg-success text-white">
                                            <h5 class="mb-0"><?php echo htmlspecialchars($visit['property_title']); ?></h5>
                                        </div>
                                        <div class="card-body">
                                            <p><strong>Data:</strong> <?php echo date('d/m/Y H:i', strtotime($visit['visit_date'])); ?></p>
                                            <p><strong>Cliente:</strong> <?php echo htmlspecialchars($visit['visitor_name']); ?></p>
                                            <p><strong>Endereço:</strong> <?php echo htmlspecialchars($visit['property_address']); ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Visitas Canceladas -->
                <div class="tab-pane fade" id="cancelled" role="tabpanel">
                    <?php if (empty($cancelled_visits)): ?>
                        <div class="alert alert-info text-center">
                            <i class="fas fa-times-circle fa-3x mb-3"></i>
                            <h4>Nenhuma visita cancelada</h4>
                            <p>Você não possui visitas canceladas.</p>
                        </div>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach ($cancelled_visits as $visit): ?>
                                <div class="col-lg-6 col-md-12 mb-4">
                                    <div class="card h-100 shadow border-left-warning">
                                        <div class="card-header bg-warning text-white">
                                            <h5 class="mb-0"><?php echo htmlspecialchars($visit['property_title']); ?></h5>
                                        </div>
                                        <div class="card-body">
                                            <p><strong>Data:</strong> <?php echo date('d/m/Y H:i', strtotime($visit['visit_date'])); ?></p>
                                            <p><strong>Cliente:</strong> <?php echo htmlspecialchars($visit['visitor_name']); ?></p>
                                            <p><strong>Status:</strong> <?php echo ucfirst($visit['status']); ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
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
