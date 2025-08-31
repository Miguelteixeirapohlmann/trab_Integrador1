<?php
/**
 * Página Inicial - Real Estate Platform
 */

// Incluir arquivo de inicialização do sistema
require_once __DIR__ . '/includes/init.php';

// Configurações da página
$page_title = "Tela Inicial - Real Estate Platform";
$page_description = "Plataforma completa para compra, venda e aluguel de imóveis";

// Verificar se há mensagem flash
$flash = getFlashMessage();

// Verificar parâmetros de sucesso/erro (para compatibilidade com sistema antigo)
$success_message = null;
$error_message = null;

if (isset($_GET['success'])) {
    switch ($_GET['success']) {
        case 'mensagem_enviada':
            $success_message = 'Mensagem enviada com sucesso! Entraremos em contato em breve.';
            break;
    }
}

if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 'campos_obrigatorios':
            $error_message = 'Por favor, preencha todos os campos obrigatórios.';
            break;
        case 'email_invalido':
            $error_message = 'Por favor, insira um email válido.';
            break;
    }
}

// Gerar token CSRF para o formulário de contato
$csrf_token = generateCSRFToken();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="<?php echo $page_description; ?>" />
    <meta name="author" content="Real Estate Platform" />
    <title><?php echo $page_title; ?></title>
    
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Bootstrap Icons-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Merriweather+Sans:400,700" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic" rel="stylesheet" type="text/css" />
    <!-- SimpleLightbox plugin CSS-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/SimpleLightbox/2.1.0/simpleLightbox.min.css" rel="stylesheet" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="css/styles.css?v=20250829" rel="stylesheet" />
</head>
<section id="final">
<body id="Tela_Inicial">
    <!-- Mensagens Flash -->
    <?php if ($flash): ?>
        <div class="alert alert-<?php echo $flash['type'] === 'success' ? 'success' : ($flash['type'] === 'error' ? 'danger' : 'info'); ?> alert-dismissible fade show position-fixed" 
             style="top: 80px; right: 20px; z-index: 9999; max-width: 400px;">
            <?php echo htmlspecialchars($flash['message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <?php if ($success_message): ?>
        <div class="alert alert-success alert-dismissible fade show position-fixed" 
             style="top: 80px; right: 20px; z-index: 9999; max-width: 400px;">
            <?php echo htmlspecialchars($success_message); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <?php if ($error_message): ?>
        <div class="alert alert-danger alert-dismissible fade show position-fixed" 
             style="top: 80px; right: 20px; z-index: 9999; max-width: 400px;">
            <?php echo htmlspecialchars($error_message); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Navigation-->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top py-3" id="mainNav">
        <div class="container px-4 px-lg-5">
            <a class="navbar-brand" href="#Tela_Inicial">Tela Inicial</a>
            <button class="navbar-toggler navbar-toggler-right" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ms-auto my-2 my-lg-0">
                    <?php if ($auth->isLoggedIn()): ?>
                        <!-- Menu para usuário logado -->
                        <li class="nav-item"><a class="nav-link" href="#services">Descobrir</a></li>
                        <li class="nav-item"><a class="nav-link" href="#comprar">Comprar</a></li>
                        <li class="nav-item"><a class="nav-link" href="#Alugar">Alugar</a></li>
                        <li class="nav-item"><a class="nav-link" href="#contact">Ajuda</a></li>
                        <li class="nav-item"><a class="nav-link" href="agendar_visita.php">Agendar Visita</a></li>

                        <?php $currentUser = $auth->getCurrentUser(); ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?php if (!empty($currentUser['avatar'])): ?>
                                    <img src="<?php echo asset('uploads/' . $currentUser['avatar']); ?>" alt="Avatar" class="rounded-circle me-1" style="width: 24px; height: 24px;">
                                <?php endif; ?>
                                <?php echo htmlspecialchars($currentUser['first_name']); ?>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="perfil.php">Meu Perfil</a></li>
                                <?php if ($currentUser['user_type'] === 'admin'): ?>
                                    <li><a class="dropdown-item" href="views/admin/dashboard.php">Painel Admin</a></li>
                                <?php endif; ?>
                                <?php if ($currentUser['user_type'] === 'corretor'): ?>
                                    <li><a class="dropdown-item" href="gerenciar_imoveis.php">Meus Imóveis</a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="logout.php">Sair</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <!-- Menu para usuário não logado -->
                        <li class="nav-item"><a class="nav-link" href="#services">Descobrir</a></li>
                        <li class="nav-item"><a class="nav-link" href="Compra.php">Comprar</a></li>
                        <li class="nav-item"><a class="nav-link" href="alugar.php">Alugar</a></li>
                        <li class="nav-item"><a class="nav-link" href="#contact">Ajuda</a></li>
                        <li class="nav-item"><a class="nav-link" href="agendar_visita.php">Agendar Visita</a></li>

                        <li class="nav-item"><a class="nav-link" href="Login.php">Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="Cadastro.php">Registrar</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Masthead-->
    <header class="masthead">
        <div class="container px-4 px-lg-5 h-100">
            <div class="row gx-4 gx-lg-5 h-100 align-items-center justify-content-center text-center">
                <div class="col-lg-8 align-self-end">
                    <h1 class="text-white font-weight-bold" style="font-size: 3.5rem;">Encontre a casa dos seus sonhos</h1>
                    <hr class="divider" />
                </div>
                <div class="col-lg-8 align-self-baseline">
                    <p class="text-white-75 mb-5" style="font-size: 1.4rem; line-height: 1.6;">
                        Especialistas em venda e aluguel de casas. 
                        Encontre a casa perfeita para você e sua família de forma segura e eficiente. 
                        Temos as melhores opções do mercado imobiliário.
                    </p>
                </div>
            </div>
        </div>
    </header>

    <!-- About-->
    <section class="page-section bg-primary" id="about">
        <div class="container px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-lg-8 text-center">
                    <h2 class="text-white mt-0">Por que escolher nossa plataforma?</h2>
                    <hr class="divider divider-light" />
                    <p class="text-white-75 mb-4">
                        Oferecemos uma experiência completa e segura para todas as suas necessidades imobiliárias. 
                        Com tecnologia de ponta e atendimento personalizado, facilitamos a busca pelo seu novo lar.
                    </p>
                    <a class="btn btn-light btn-xl" href="#services">Descobrir Mais</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Services-->
    <section class="page-section" id="services">
        <div class="container px-4 px-lg-5">
            <h2 class="text-center mt-0">Nossos Serviços</h2>
            <hr class="divider" />
            <div class="row gx-4 gx-lg-5">
                <div class="col-lg-3 col-md-6 text-center">
                    <div class="mt-5">
                        <div class="mb-2"><i class="bi-shield-check fs-1 text-primary"></i></div>
                        <h3 class="h4 mb-2">Segurança</h3>
                        <p class="text-muted mb-0">Nosso site é seguro. Usamos conexão criptografada (HTTPS) e protegemos seus dados com sistemas de segurança. Suas informações estão seguras com a gente.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 text-center">
                    <div class="mt-5">
                        <div class="mb-2"><i class="bi-laptop fs-1 text-primary"></i></div>
                        <h3 class="h4 mb-2">Atualização</h3>
                        <p class="text-muted mb-0">Estamos sempre atualizando nosso site para melhorar a segurança, corrigir possíveis erros e oferecer uma melhor experiência para você. Novas funcionalidades e melhorias são aplicadas regularmente.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 text-center">
                    <div class="mt-5">
                        <div class="mb-2"><i class="bi-globe fs-1 text-primary"></i></div>
                        <h3 class="h4 mb-2">Pronto para Publicar</h3>
                        <p class="text-muted mb-0">Este conteúdo está finalizado, revisado e aprovado. Está adequado para ser exibido ao público, sem necessidade de ajustes. Pode ser publicado a qualquer momento.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 text-center">
                    <div class="mt-5">
                        <div class="mb-2"><i class="bi-heart fs-1 text-primary"></i></div>
                        <h3 class="h4 mb-2">Feito com amor</h3>
                        <p class="text-muted mb-0">Cada detalhe deste projeto foi pensado com carinho e dedicação. Mais do que um simples trabalho, foi feito com amor para oferecer a melhor experiência possível a você.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Portfolio-->
    <div id="portfolio">
        <div class="container-fluid p-0">
            <div class="row g-0">
                <div class="col-lg-4 col-sm-6">
                    <a class="portfolio-box" href="imgs/Casa1/Casa1.1.jpg" title="Casa Jardim das Flores">
                        <img class="img-fluid" src="imgs/Casa1/Casa1.1.jpg" alt="..." style="width: 100%; height: 350px; object-fit: cover; object-position: center;" />
                        <div class="portfolio-box-caption">
                            <div class="project-category text-white-50">Categoria</div>
                            <div class="project-name">Casa para venda</div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <a class="portfolio-box" href="imgs/Casa2/Casa2.0.jpg" title="Casa Vista Alegre">
                        <img class="img-fluid" src="imgs/Casa2/Casa2.0.jpg" alt="..." style="width: 100%; height: 350px; object-fit: cover; object-position: center;" />
                        <div class="portfolio-box-caption">
                            <div class="project-category text-white-50">Categoria</div>
                            <div class="project-name">Casa para venda</div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <a class="portfolio-box" href="imgs/Casa3/Casa3.3.jpg" title="Casa Solar dos Pássaros">
                        <img class="img-fluid" src="imgs/Casa3/Casa3.3.jpg" alt="..." style="width: 100%; height: 350px; object-fit: cover; object-position: center;" />
                        <div class="portfolio-box-caption">
                            <div class="project-category text-white-50">Categoria</div>
                            <div class="project-name">Casa para venda</div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <a class="portfolio-box" href="imgs/Casa4/Casa4.4.jpg" title="Casa Bela Vista">
                        <img class="img-fluid" src="imgs/Casa4/Casa4.4.jpg" alt="..." style="width: 100%; height: 350px; object-fit: cover; object-position: center;" />
                        <div class="portfolio-box-caption">
                            <div class="project-category text-white-50">Categoria</div>
                            <div class="project-name">Casa para venda</div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <a class="portfolio-box" href="imgs/Casa5/Casa5.0.jpg" title="Casa do Gabriel">
                        <img class="img-fluid" src="imgs/Casa5/Casa5.0.jpg" alt="..." style="width: 100%; height: 350px; object-fit: cover; object-position: center;" />
                        <div class="portfolio-box-caption">
                            <div class="project-category text-white-50">Categoria</div>
                            <div class="project-name">Casa para venda</div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <a class="portfolio-box" href="imgs/Casa6/Casa6.0.jpg" title="Casa Nova Esperança">
                        <img class="img-fluid" src="imgs/Casa6/Casa6.0.jpg" alt="..." style="width: 100%; height: 350px; object-fit: cover; object-position: center;" />
                        <div class="portfolio-box-caption p-3">
                            <div class="project-category text-white-50">Categoria</div>
                            <div class="project-name">Casa para venda</div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <a class="portfolio-box" href="imgs/Casa7/Casa7.0.jpg" title="Casa Nova Esperança">
                        <img class="img-fluid" src="imgs/Casa7/Casa7.0.jpg" alt="..." style="width: 100%; height: 350px; object-fit: cover; object-position: center;" />
                        <div class="portfolio-box-caption p-3">
                            <div class="project-category text-white-50">Categoria</div>
                            <div class="project-name">Casa para venda</div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <a class="portfolio-box" href="imgs/Casa8/Casa8.0.jpg" title="Casa Nova Esperança">
                        <img class="img-fluid" src="imgs/Casa8/Casa8.0.jpg" alt="..." style="width: 100%; height: 350px; object-fit: cover; object-position: center;" />
                        <div class="portfolio-box-caption p-3">
                            <div class="project-category text-white-50">Categoria</div>
                            <div class="project-name">Casa para venda</div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <a class="portfolio-box" href="imgs/Casa9/Casa9.0.jpg" title="Casa Nova Esperança">
                        <img class="img-fluid" src="imgs/Casa9/Casa9.0.jpg" alt="..." style="width: 100%; height: 350px; object-fit: cover; object-position: center;" />
                        <div class="portfolio-box-caption p-3">
                            <div class="project-category text-white-50">Categoria</div>
                            <div class="project-name">Casa para venda</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Call to action-->
    <section class="page-section bg-dark text-white" id="comprar">
        <div class="container px-4 px-lg-5 text-center">
            <h2 class="mb-4">mais casas a venda</h2>
            <a class="btn btn-light btn-xl" href="casas_disponiveis.php">Comprar</a>
        </div>
    </section>
    
    <!-- Seção Alugar -->
    <section class="page-section" id="Alugar">
        <div class="container px-4 px-lg-5 text-center">
            <h2 class="mb-4">Casas para Alugar</h2>
            <p class="mb-5">Encontre a casa perfeita para alugar com as melhores condições do mercado.</p>
            <a class="btn btn-primary btn-xl" href="casas_disponiveis.php">Alugar Imóvel</a>
        </div>
    </section>

    <!-- Contact-->
    <section class="page-section" id="contact">
        <div class="container px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-lg-8 col-xl-6 text-center">
                    <section id="Final"></section>
                    <h2 class="mt-0">Ajuda</h2>
                    <hr class="divider" />
                    <p class="text-muted mb-5">Lugar em que o usuario manda mensagem, e tiramos suas duvidas.</p>
                </div>
            </div>
            <div class="row gx-4 gx-lg-5 justify-content-center mb-5">
                <div class="col-lg-6">
                    <form id="contactForm" method="POST" action="controllers/ContactController.php">
                        <!-- Token CSRF -->
                        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                        
                        <div class="form-floating mb-3">
                            <input class="form-control" id="name" name="name" type="text" 
                                   placeholder="Seu nome..." required 
                                   value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" />
                            <label for="name">Nome Completo</label>
                        </div>
                        
                        <div class="form-floating mb-3">
                            <input class="form-control" id="email" name="email" type="email" 
                                   placeholder="name@example.com" required 
                                   value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" />
                            <label for="email">Email</label>
                        </div>
                        
                        <div class="form-floating mb-3">
                            <input class="form-control" id="phone" name="phone" type="tel" 
                                   placeholder="(11) 99999-9999" 
                                   value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>" />
                            <label for="phone">Telefone (opcional)</label>
                        </div>
                        
                        <div class="form-floating mb-3">
                            <textarea class="form-control" id="message" name="message" 
                                      placeholder="Sua mensagem..." style="height: 10rem" required><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                            <label for="message">Mensagem</label>
                        </div>
                        
                        <div class="d-grid">
                            <button class="btn btn-primary btn-xl" type="submit">Enviar Mensagem</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer-->
    <footer class="bg-light py-5">
        <div class="container px-4 px-lg-5">
            <div class="small text-center text-muted">Copyright &copy; 2025 - Company Miguel</div>
        </div>
    </footer>

    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SimpleLightbox plugin JS-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/SimpleLightbox/2.1.0/simpleLightbox.min.js"></script>
    <!-- Core theme JS-->
    <script src="js/scripts.js"></script>
</body>
</section>
</html>
