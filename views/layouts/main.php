<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="<?php echo $page_description ?? 'Plataforma completa para compra, venda e aluguel de imóveis'; ?>" />
    <meta name="author" content="Real Estate Platform" />
    <title><?php echo $page_title ?? 'Real Estate Platform'; ?></title>
    
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="/assets/favicon.ico" />
    
    <!-- Bootstrap Icons-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Merriweather+Sans:400,700" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic" rel="stylesheet" type="text/css" />
    
    <!-- SimpleLightbox plugin CSS-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/SimpleLightbox/2.1.0/simpleLightbox.min.css" rel="stylesheet" />
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="/css/styles.css" rel="stylesheet" />
    
    <!-- Custom CSS for specific pages -->
    <?php if (isset($custom_css)): ?>
        <?php foreach ($custom_css as $css_file): ?>
            <link href="<?php echo $css_file; ?>" rel="stylesheet" />
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body id="<?php echo $body_id ?? 'home'; ?>">
    
    <!-- Navigation-->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top py-3" id="mainNav">
        <div class="container px-4 px-lg-5">
            <a class="navbar-brand" href="/index.php">
                <i class="bi bi-house-fill me-2"></i>
                <?php echo APP_NAME ?? 'Real Estate'; ?>
            </a>
            <button class="navbar-toggler navbar-toggler-right" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ms-auto my-2 my-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="/index.php#properties">Imóveis</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Transações
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/properties/sale.php">Comprar</a></li>
                            <li><a class="dropdown-item" href="/properties/rent.php">Alugar</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/visits/schedule.php">Agendar Visita</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/index.php#contact">Contato</a>
                    </li>
                    
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <!-- Usuário logado -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?php if (isset($_SESSION['avatar']) && $_SESSION['avatar']): ?>
                                    <img src="<?php echo $_SESSION['avatar']; ?>" alt="Avatar" class="rounded-circle me-2" width="30" height="30">
                                <?php else: ?>
                                    <i class="bi bi-person-circle fs-4 me-2"></i>
                                <?php endif; ?>
                                <?php echo $_SESSION['user_name'] ?? 'Usuário'; ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="/profile/index.php"><i class="bi bi-person me-2"></i>Meu Perfil</a></li>
                                <li><a class="dropdown-item" href="/profile/favorites.php"><i class="bi bi-heart me-2"></i>Favoritos</a></li>
                                <li><a class="dropdown-item" href="/visits/my-visits.php"><i class="bi bi-calendar me-2"></i>Minhas Visitas</a></li>
                                
                                <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'broker'): ?>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><h6 class="dropdown-header">Área do Corretor</h6></li>
                                    <li><a class="dropdown-item" href="/broker/dashboard.php"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                                    <li><a class="dropdown-item" href="/broker/properties.php"><i class="bi bi-house me-2"></i>Meus Imóveis</a></li>
                                    <li><a class="dropdown-item" href="/broker/visits.php"><i class="bi bi-calendar-check me-2"></i>Visitas</a></li>
                                <?php endif; ?>
                                
                                <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin'): ?>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><h6 class="dropdown-header">Administração</h6></li>
                                    <li><a class="dropdown-item" href="/admin/dashboard.php"><i class="bi bi-gear me-2"></i>Painel Admin</a></li>
                                <?php endif; ?>
                                
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="/auth/logout.php"><i class="bi bi-box-arrow-right me-2"></i>Sair</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <!-- Usuário não logado -->
                        <li class="nav-item">
                            <a class="nav-link" href="/auth/login.php">
                                <i class="bi bi-box-arrow-in-right me-1"></i>
                                Entrar
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-outline-primary rounded-pill px-3 ms-2" href="/auth/register.php">
                                <i class="bi bi-person-plus me-1"></i>
                                Cadastrar
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        <?php echo $content ?? ''; ?>
    </main>

    <!-- Footer-->
    <footer class="bg-light py-5">
        <div class="container px-4 px-lg-5">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="mb-3">
                        <i class="bi bi-house-fill me-2"></i>
                        <?php echo APP_NAME ?? 'Real Estate'; ?>
                    </h5>
                    <p class="text-muted">
                        Plataforma completa para compra, venda e aluguel de imóveis. 
                        Conectamos pessoas de forma segura e eficiente.
                    </p>
                    <div class="d-flex">
                        <a href="#" class="text-muted me-3"><i class="bi bi-facebook fs-5"></i></a>
                        <a href="#" class="text-muted me-3"><i class="bi bi-instagram fs-5"></i></a>
                        <a href="#" class="text-muted me-3"><i class="bi bi-linkedin fs-5"></i></a>
                        <a href="#" class="text-muted"><i class="bi bi-twitter fs-5"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="mb-3">Imóveis</h6>
                    <ul class="list-unstyled">
                        <li><a href="/properties/sale.php" class="text-muted">Comprar</a></li>
                        <li><a href="/properties/rent.php" class="text-muted">Alugar</a></li>
                        <li><a href="/properties/commercial.php" class="text-muted">Comercial</a></li>
                        <li><a href="/properties/land.php" class="text-muted">Terrenos</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="mb-3">Serviços</h6>
                    <ul class="list-unstyled">
                        <li><a href="/visits/schedule.php" class="text-muted">Agendar Visita</a></li>
                        <li><a href="/broker/register.php" class="text-muted">Seja um Corretor</a></li>
                        <li><a href="/help/faq.php" class="text-muted">FAQ</a></li>
                        <li><a href="/help/contact.php" class="text-muted">Suporte</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-4 mb-4">
                    <h6 class="mb-3">Contato</h6>
                    <div class="d-flex mb-2">
                        <i class="bi bi-phone fs-5 me-3 text-primary"></i>
                        <div>
                            <div>+55 (51) 2627-7423</div>
                            <div>+55 (51) 8937-9844</div>
                        </div>
                    </div>
                    <div class="d-flex mb-2">
                        <i class="bi bi-envelope fs-5 me-3 text-primary"></i>
                        <div>contato@realestate.com</div>
                    </div>
                    <div class="d-flex">
                        <i class="bi bi-geo-alt fs-5 me-3 text-primary"></i>
                        <div>Porto Alegre, RS - Brasil</div>
                    </div>
                </div>
            </div>
            
            <hr class="my-4">
            
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="small text-muted">
                        Copyright &copy; <?php echo date('Y'); ?> - <?php echo APP_NAME ?? 'Real Estate Platform'; ?>
                    </div>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="small text-muted">
                        Versão <?php echo APP_VERSION ?? '1.0.0'; ?> | 
                        <a href="/legal/privacy.php" class="text-muted">Privacidade</a> | 
                        <a href="/legal/terms.php" class="text-muted">Termos</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- SimpleLightbox plugin JS-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/SimpleLightbox/2.1.0/simpleLightbox.min.js"></script>
    
    <!-- Core theme JS-->
    <script src="/js/scripts.js"></script>
    
    <!-- Custom JS for specific pages -->
    <?php if (isset($custom_js)): ?>
        <?php foreach ($custom_js as $js_file): ?>
            <script src="<?php echo $js_file; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- Inline scripts -->
    <?php if (isset($inline_scripts)): ?>
        <script>
            <?php echo $inline_scripts; ?>
        </script>
    <?php endif; ?>
    
</body>
</html>
