<?php
require_once __DIR__ . '/includes/init.php';
require_once __DIR__ . '/models/Property.php';

// Instanciar modelo de propriedades
$propertyModel = new Property();

// Buscar propriedades em destaque
$featured_properties = $propertyModel->getAll(1, 6, ['featured' => true]);

// Configurações da página
$page_title = "Real Estate Platform - Página Inicial";
$page_description = "Plataforma completa para compra, venda e aluguel de imóveis";
$body_id = "home";

// Capturar conteúdo da página
ob_start();
?>

<!-- Masthead-->
<header class="masthead">
    <div class="container px-4 px-lg-5 h-100">
        <div class="row gx-4 gx-lg-5 h-100 align-items-center justify-content-center text-center">
            <div class="col-lg-8 align-self-end">
                <h1 class="text-white font-weight-bold">Encontre o Imóvel dos Seus Sonhos</h1>
                <hr class="divider" />
            </div>
            <div class="col-lg-8 align-self-baseline">
                <p class="text-white-75 mb-5">
                    Plataforma completa para compra, venda e aluguel de imóveis. 
                    Conectamos pessoas de forma segura e eficiente, oferecendo as melhores oportunidades 
                    em casas, apartamentos, terrenos e imóveis comerciais.
                </p>
                <a class="btn btn-primary btn-xl" href="#properties">Explorar Imóveis</a>
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
                    Com tecnologia de ponta e atendimento personalizado, facilitamos a busca pelo seu novo lar 
                    ou investimento.
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
                    <h3 class="h4 mb-2">Segurança Garantida</h3>
                    <p class="text-muted mb-0">
                        Transações seguras com verificação completa de documentos e identidade. 
                        Seus dados estão protegidos com criptografia avançada.
                    </p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 text-center">
                <div class="mt-5">
                    <div class="mb-2"><i class="bi-search fs-1 text-primary"></i></div>
                    <h3 class="h4 mb-2">Busca Inteligente</h3>
                    <p class="text-muted mb-0">
                        Encontre exatamente o que procura com nossos filtros avançados por localização, 
                        preço, características e muito mais.
                    </p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 text-center">
                <div class="mt-5">
                    <div class="mb-2"><i class="bi-calendar-check fs-1 text-primary"></i></div>
                    <h3 class="h4 mb-2">Agendamento Fácil</h3>
                    <p class="text-muted mb-0">
                        Agende visitas de forma simples e rápida. Escolha o melhor horário e 
                        receba confirmação instantânea.
                    </p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 text-center">
                <div class="mt-5">
                    <div class="mb-2"><i class="bi-people fs-1 text-primary"></i></div>
                    <h3 class="h4 mb-2">Corretores Qualificados</h3>
                    <p class="text-muted mb-0">
                        Nossa rede de corretores certificados está pronta para ajudar você 
                        a encontrar o imóvel perfeito.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Properties-->
<section class="page-section bg-light" id="properties">
    <div class="container px-4 px-lg-5">
        <h2 class="text-center mt-0 mb-5">Imóveis em Destaque</h2>
        
        <?php if (!empty($featured_properties['data'])): ?>
        <div class="row gx-4 gx-lg-5">
            <?php foreach ($featured_properties['data'] as $property): ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 shadow">
                    <?php if ($property['primary_image']): ?>
                    <img src="<?php echo htmlspecialchars($property['primary_image']); ?>" 
                         class="card-img-top" 
                         alt="<?php echo htmlspecialchars($property['title']); ?>"
                         style="height: 250px; object-fit: cover;">
                    <?php else: ?>
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                         style="height: 250px;">
                        <i class="bi bi-house fs-1 text-muted"></i>
                    </div>
                    <?php endif; ?>
                    
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($property['title']); ?></h5>
                        <p class="card-text text-muted">
                            <i class="bi bi-geo-alt me-1"></i>
                            <?php echo htmlspecialchars($property['address'] . ', ' . $property['city']); ?>
                        </p>
                        <p class="card-text small">
                            <?php echo substr(htmlspecialchars($property['description']), 0, 100) . '...'; ?>
                        </p>
                        
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="property-features">
                                <?php if ($property['bedrooms']): ?>
                                <span class="badge bg-secondary me-1">
                                    <i class="bi bi-bed me-1"></i><?php echo $property['bedrooms']; ?>
                                </span>
                                <?php endif; ?>
                                <?php if ($property['bathrooms']): ?>
                                <span class="badge bg-secondary me-1">
                                    <i class="bi bi-house-door me-1"></i><?php echo $property['bathrooms']; ?>
                                </span>
                                <?php endif; ?>
                                <span class="badge bg-secondary">
                                    <?php echo number_format($property['area_sqm'], 0); ?>m²
                                </span>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="price">
                                <strong class="text-primary fs-5">
                                    <?php echo formatCurrency($property['price']); ?>
                                </strong>
                                <?php if ($property['transaction_type'] == 'rent' || $property['transaction_type'] == 'both'): ?>
                                    <small class="text-muted">/mês</small>
                                <?php endif; ?>
                            </div>
                            <a href="/properties/view.php?id=<?php echo $property['id']; ?>" 
                               class="btn btn-primary btn-sm">
                                Ver Detalhes
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-4">
            <a href="/properties/all.php" class="btn btn-outline-primary btn-lg">
                Ver Todos os Imóveis
            </a>
        </div>
        <?php else: ?>
        <div class="text-center">
            <i class="bi bi-house fs-1 text-muted mb-3"></i>
            <p class="text-muted">Nenhum imóvel em destaque no momento.</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Call to action-->
<section class="page-section bg-dark text-white">
    <div class="container px-4 px-lg-5 text-center">
        <h2 class="mb-4">Pronto para encontrar seu novo lar?</h2>
        <p class="mb-4">Explore nossa vasta seleção de imóveis ou cadastre-se para receber alertas personalizados.</p>
        <div class="d-flex flex-column flex-md-row justify-content-center gap-3">
            <a class="btn btn-light btn-xl" href="/properties/search.php">
                <i class="bi bi-search me-2"></i>Buscar Imóveis
            </a>
            <?php if (!$auth->isLoggedIn()): ?>
            <a class="btn btn-outline-light btn-xl" href="/auth/register.php">
                <i class="bi bi-person-plus me-2"></i>Criar Conta
            </a>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Contact-->
<section class="page-section" id="contact">
    <div class="container px-4 px-lg-5">
        <div class="row gx-4 gx-lg-5 justify-content-center">
            <div class="col-lg-8 col-xl-6 text-center">
                <h2 class="mt-0">Entre em Contato</h2>
                <hr class="divider" />
                <p class="text-muted mb-5">
                    Tem dúvidas ou precisa de ajuda? Nossa equipe está pronta para atendê-lo!
                </p>
            </div>
        </div>
        
        <div class="row gx-4 gx-lg-5 justify-content-center mb-5">
            <div class="col-lg-6">
                <?php 
                $flash = getFlashMessage();
                if ($flash): 
                ?>
                <div class="alert alert-<?php echo $flash['type'] === 'error' ? 'danger' : $flash['type']; ?> alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($flash['message']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>
                
                <form id="contactForm" method="POST" action="/controllers/ContactController.php">
                    <input type="hidden" name="action" value="send_message">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                    
                    <div class="form-floating mb-3">
                        <input class="form-control" id="name" name="name" type="text" 
                               placeholder="Seu nome..." required />
                        <label for="name">Nome Completo</label>
                    </div>
                    
                    <div class="form-floating mb-3">
                        <input class="form-control" id="email" name="email" type="email" 
                               placeholder="name@example.com" required />
                        <label for="email">Email</label>
                    </div>
                    
                    <div class="form-floating mb-3">
                        <input class="form-control" id="phone" name="phone" type="tel" 
                               placeholder="(11) 99999-9999" />
                        <label for="phone">Telefone (opcional)</label>
                    </div>
                    
                    <div class="form-floating mb-3">
                        <input class="form-control" id="subject" name="subject" type="text" 
                               placeholder="Assunto..." />
                        <label for="subject">Assunto (opcional)</label>
                    </div>
                    
                    <div class="form-floating mb-3">
                        <textarea class="form-control" id="message" name="message" 
                                  placeholder="Sua mensagem..." style="height: 10rem" required></textarea>
                        <label for="message">Mensagem</label>
                    </div>
                    
                    <div class="d-grid">
                        <button class="btn btn-primary btn-xl" type="submit">
                            <i class="bi bi-envelope me-2"></i>Enviar Mensagem
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php 
$content = ob_get_clean();
include __DIR__ . '/views/layouts/main.php';
?>
