<?php
/**
 * Página de Compra
 */

// Incluir arquivo de inicialização do sistema
require_once __DIR__ . '/includes/init.php';

// Verificar mensagens flash
$flash = getFlashMessage();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela de Compra - <?php echo APP_NAME; ?></title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Ícones do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <!-- Fontes do Google -->
    <link href="https://fonts.googleapis.com/css?family=Merriweather+Sans:400,700" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic" rel="stylesheet" type="text/css" />
    <!-- Plugin SimpleLightbox CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/SimpleLightbox/2.1.0/simpleLightbox.min.css" rel="stylesheet" />
    <!-- Font Awesome para ícones sociais -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <!-- Tema principal CSS (inclui Bootstrap) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
</head>
<body>
     <!-- Navigation-->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top py-3" id="mainNav">
        <div class="container px-4 px-lg-5">
            <a class="navbar-brand" href="index.php">Tela Inicial</a>
            <button class="navbar-toggler navbar-toggler-right" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ms-auto my-2 my-lg-0">
                    <li class="nav-item"><a class="nav-link" href="alugar_new.php">Alugar</a></li>
                    <li class="nav-item"><a class="nav-link" href="Compra.php">Compra</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#services">Descobrir</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#Final">Ajuda</a></li>
                    <li class="nav-item"><a class="nav-link" href="agendar_visita.php">Agendar Visita</a></li>
                    
                    <?php if ($auth->isLoggedIn()): ?>
                        <?php $currentUser = $auth->getCurrentUser(); ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?php if (!empty($currentUser['avatar'])): ?>
                                    <img src="<?php echo asset('uploads/' . $currentUser['avatar']); ?>" alt="Avatar" class="rounded-circle me-1" style="width: 24px; height: 24px;">
                                <?php endif; ?>
                                <?php echo htmlspecialchars($currentUser['first_name']); ?>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="views/auth/profile.php">Meu Perfil</a></li>
                                <?php if ($auth->hasRole('admin')): ?>
                                    <li><a class="dropdown-item" href="views/admin/dashboard.php">Painel Admin</a></li>
                                <?php endif; ?>
                                <?php if ($auth->hasRole('broker')): ?>
                                    <li><a class="dropdown-item" href="views/properties/manage.php">Meus Imóveis</a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="views/auth/logout.php">Sair</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="Login.php">Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="Cadastro.php">Cadastro</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="masthead">
        <div class="container px-4 px-lg-5 h-100">
            <div class="row gx-4 gx-lg-5 h-100 align-items-center justify-content-center text-center">
                <div class="col-lg-8 align-self-end">
                    <h1 class="text-white font-weight-bold">Compra de Imóveis</h1>
                    <hr class="divider" />
                </div>
                <div class="col-lg-8 align-self-baseline">
                    <p class="text-white-75 mb-5">Realize o sonho da casa própria com segurança e tranquilidade. Preencha o formulário abaixo para iniciar o processo de compra.</p>
                </div>
            </div>
        </div>
    </header>

    <!-- Formulário de Compra -->
    <section class="page-section" id="compra-form">
        <div class="container px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-lg-8 col-xl-6 text-center">
                    <h2 class="mt-0">Documentos para Compra</h2>
                    <hr class="divider" />
                    <p class="text-muted mb-5">Para finalizar a compra do seu imóvel, precisamos dos seguintes documentos:</p>
                </div>
            </div>
            
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow-lg border-0">
                        <div class="card-header bg-primary text-white text-center">
                            <h4 class="mb-0"><i class="fas fa-home me-2"></i>Formulário de Compra</h4>
                        </div>
                        <div class="card-body p-4">
                            <form id="compraForm" method="POST">
                                <!-- Dados Pessoais -->
                                <div class="mb-4">
                                    <h5 class="text-primary mb-3"><i class="fas fa-user me-2"></i>Dados Pessoais</h5>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="nome" class="form-label">Nome Completo <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-lg" id="nome" name="nome" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="cpf" class="form-label">CPF <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-lg" id="cpf" name="cpf" placeholder="000.000.000-00" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control form-control-lg" id="email" name="email" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="telefone" class="form-label">Telefone <span class="text-danger">*</span></label>
                                            <input type="tel" class="form-control form-control-lg" id="telefone" name="telefone" placeholder="(00) 00000-0000" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Documentos Necessários -->
                                <div class="mb-4">
                                    <h5 class="text-primary mb-3"><i class="fas fa-file-alt me-2"></i>Documentos Necessários</h5>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="rg" class="form-label">RG</label>
                                            <input type="text" class="form-control form-control-lg" id="rg" name="rg" placeholder="Número do RG">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="estado-civil" class="form-label">Estado Civil</label>
                                            <select class="form-select form-select-lg" id="estado-civil" name="estado_civil">
                                                <option value="">Selecione...</option>
                                                <option value="solteiro">Solteiro(a)</option>
                                                <option value="casado">Casado(a)</option>
                                                <option value="divorciado">Divorciado(a)</option>
                                                <option value="viuvo">Viúvo(a)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="endereco" class="form-label">Endereço Completo</label>
                                        <textarea class="form-control" id="endereco" name="endereco" rows="3" placeholder="Rua, número, bairro, cidade, CEP..."></textarea>
                                    </div>
                                </div>

                                <!-- Informações Financeiras -->
                                <div class="mb-4">
                                    <h5 class="text-primary mb-3"><i class="fas fa-dollar-sign me-2"></i>Informações Financeiras</h5>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="renda" class="form-label">Renda Mensal</label>
                                            <input type="text" class="form-control form-control-lg" id="renda" name="renda" placeholder="R$ 0,00">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="profissao" class="form-label">Profissão</label>
                                            <input type="text" class="form-control form-control-lg" id="profissao" name="profissao">
                                        </div>
                                    </div>
                                </div>

                                <!-- Lista de Documentos Required -->
                                <div class="mb-4">
                                    <h5 class="text-info mb-3"><i class="fas fa-clipboard-list me-2"></i>Documentos Obrigatórios</h5>
                                    <div class="alert alert-info">
                                        <ul class="mb-0">
                                            <li>Declaração de Imposto de Renda (3 últimos anos)</li>
                                            <li>Comprovante de residência atualizado</li>
                                            <li>Comprovantes de renda (holerites, extratos bancários)</li>
                                            <li>Certidão de estado civil</li>
                                            <li>Certidão negativa do SPC/SERASA</li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Botões -->
                                <div class="d-flex justify-content-center align-items-center gap-3 mt-4">
                                    <button type="submit" class="btn btn-primary btn-lg px-5">
                                        <i class="fas fa-paper-plane me-2"></i>Enviar Solicitação
                                    </button>
                                    <button type="reset" class="btn btn-outline-secondary btn-lg px-5">
                                        <i class="fas fa-redo me-2"></i>Limpar Formulário
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

     <footer class="bg-light py-5">
            <div class="container px-4 px-lg-5"><div class="small text-center text-muted">Copyright &copy; 2025 - Company Miguel</div></div>
        </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/SimpleLightbox/2.1.0/simpleLightbox.min.js"></script>
    <script src="js/scripts.js"></script>
</body>
</html>