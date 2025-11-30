<?php
require_once __DIR__ . '/includes/init.php';
$current_user = $auth->getCurrentUser();
require_once __DIR__ . '/includes/navbar.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>admin - Corretor</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Bootstrap Icons-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Merriweather+Sans:400,700" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic" rel="stylesheet" type="text/css" />
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
        <!-- SimpleLightbox plugin CSS-->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/SimpleLightbox/2.1.0/simpleLightbox.min.css" rel="stylesheet" />
        <!-- Core theme CSS -->
        <link href="css/styles.css" rel="stylesheet">
    </head>
<body>
    <!-- Header -->
    <?php renderNavbar($current_user, 'corretor'); ?>

    <!-- Seção de Casas -->
    <section class="page-section bg-light py-5" id="casas">
        <div class="container px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-lg-8 text-center">
                    <h2 class="text-dark mt-0">Imóveis Disponíveis</h2>
                    <hr class="divider" />
                    <p class="text-muted mb-5">Confira os imóveis disponíveis para venda e aluguel</p>
                </div>
            </div>
            <div class="row gx-4 gx-lg-5" id="casasCards">
                <!-- Os cards das casas serão renderizados aqui via JavaScript -->
            </div>
        </div>
    </section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/SimpleLightbox/2.1.0/simpleLightbox.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>
    <script>
        // Seleção dinâmica das casas conforme corretor logado
        let casas = [];
        <?php
    $corretor_nome = isset($current_user['first_name']) && $current_user['first_name'] !== null ? strtolower($current_user['first_name']) : '';
    if ($corretor_nome === 'maria') {
        ?>
        casas = [
            {
                id: 1,
                nome: "Casa em Santo Antônio da Patrulha",
                tipo: "Residencial",
                tipo_negocio: "ambos",
                valor_compra: 5200000,
                valor_aluguel: 2200000,
                imagens: [
                    "imgs/Casa1/Casa1.1.jpg",
                    "imgs/Casa1/Casa1.3.jpg",
                    "imgs/Casa1/Casa1.4.jpg",
                    "imgs/Casa1/Casa1.5.jpg",
                    "imgs/Casa1/Casa1.8.jpg",
                    "imgs/Casa1/Casa1.9.jpg",
                    "imgs/Casa1/Casa1.11.jpg",
                    "imgs/Casa1/Casa1.13.jpg",
                    "imgs/Casa1/Casa1.15.jpg"
                ]
            },
            {
                id: 4,
                nome: "Casa em Taquara Rua Mundo Novo",
                tipo: "Residencial",
                tipo_negocio: "ambos",
                valor_compra: 170000,
                valor_aluguel: 1000,
                imagens: [
                    "imgs/Casa4/Casa4.0.jpg",
                    "imgs/Casa4/Casa4.2.jpg",
                    "imgs/Casa4/Casa4.3.jpg",
                    "imgs/Casa4/Casa4.4.jpg",
                    "imgs/Casa4/Casa4.5.jpg",
                    "imgs/Casa4/Casa4.6.jpg",
                    "imgs/Casa4/Casa4.7.jpg",
                    "imgs/Casa4/Casa4.8.jpg",
                    "imgs/Casa4/Casa4.9.jpg"
                ]
            },
            {
                id: 7,
                nome: "Casa em Taquara Bairro Santa Terezinha",
                tipo: "Residencial",
                tipo_negocio: "ambos",
                valor_compra: 650000,
                valor_aluguel: 2000,
                imagens: [
                    "imgs/Casa7/Casa7.0.jpg",
                    "imgs/Casa7/Casa7.1.jpg",
                    "imgs/Casa7/Casa7.2.jpg",
                    "imgs/Casa7/Casa7.3.jpg",
                    "imgs/Casa7/Casa7.4.jpg",
                    "imgs/Casa7/Casa7.5.jpg",
                    "imgs/Casa7/Casa7.6.jpg",
                    "imgs/Casa7/Casa7.7.jpg",
                    "imgs/Casa7/Casa7.8.jpg",
                    "imgs/Casa7/Casa7.9.jpg"
                ]
            }
        ];
        <?php
    } else if ($corretor_nome === 'pedro') {
    // ...existing code...
        ?>
        casas = [
            {
                id: 8,
                nome: "Casa em Taquara Bairro Tucanos",
                tipo: "Residencial",
                tipo_negocio: "compra",
                valor_compra: 184900,
                imagens: [
                    "imgs/Casa8/Casa8.0.jpg",
                    "imgs/Casa8/Casa8.1.jpg",
                    "imgs/Casa8/Casa8.2.jpg",
                    "imgs/Casa8/Casa8.3.jpg",
                    "imgs/Casa8/Casa8.4.jpg",
                    "imgs/Casa8/Casa8.5.jpg",
                    "imgs/Casa8/Casa8.6.jpg",
                    "imgs/Casa8/Casa8.7.jpg",
                    "imgs/Casa8/Casa8.8.jpg",
                    "imgs/Casa8/Casa8.9.jpg"
                ]
            },
            {
                id: 9,
                nome: "Casa em Taquara - Rua São Francisco",
                tipo: "Residencial",
                tipo_negocio: "compra",
                valor_compra: 450000,
                imagens: [
                    "imgs/Casa9/Casa9.0.jpg",
                    "imgs/Casa9/Casa9.1.jpg",
                    "imgs/Casa9/Casa9.2.jpg",
                    "imgs/Casa9/Casa9.3.jpg",
                    "imgs/Casa9/Casa9.4.jpg",
                    "imgs/Casa9/Casa9.5.jpg",
                    "imgs/Casa9/Casa9.6.jpg",
                    "imgs/Casa9/Casa9.7.jpg",
                    "imgs/Casa9/Casa9.8.jpg",
                    "imgs/Casa9/Casa9.9.jpg"
                ]
            },
            {
                id: 2,
                nome: "Casa em Taquara",
                tipo: "Residencial",
                tipo_negocio: "aluguel",
                valor_aluguel: 1500,
                imagens: [
                    "imgs/Casa2/Casa2.0.jpg",
                    "imgs/Casa2/Casa2.1.jpg",
                    "imgs/Casa2/Casa2.2.jpg",
                    "imgs/Casa2/Casa2.3.jpg",
                    "imgs/Casa2/Casa2.4.jpg",
                    "imgs/Casa2/Casa2.5.jpg",
                    "imgs/Casa2/Casa2.6.jpg",
                    "imgs/Casa2/Casa2.7.jpg",
                    "imgs/Casa2/Casa2.8.jpg",
                    "imgs/Casa2/Casa2.9.jpg",
                    "imgs/Casa2/Casa2.10.jpg",
                    "imgs/Casa2/Casa2.11.jpg",
                    "imgs/Casa2/Casa2.12.jpg",
                    "imgs/Casa2/Casa2.13.jpg"
                ]
            }
        ];
        <?php
        } else {
        ?>
        casas = [
            {
                id: 3,
                nome: "Casa em Taquara Alto Padrão",
                tipo: "Alto Padrão",
                tipo_negocio: "compra",
                valor_compra: 3000000,
                imagens: [
                    "imgs/Casa3/Casa3.0.jpg",
                    "imgs/Casa3/Casa3.1.jpg",
                    "imgs/Casa3/Casa3.2.jpg",
                    "imgs/Casa3/Casa3.3.jpg",
                    "imgs/Casa3/Casa3.4.jpg",
                    "imgs/Casa3/Casa3.5.jpg",
                    "imgs/Casa3/Casa3.6.jpg",
                    "imgs/Casa3/Casa3.7.jpg",
                    "imgs/Casa3/Casa3.8.jpg",
                    "imgs/Casa3/Casa3.9.jpg"
                ]
            },
            {
                id: 5,
                nome: "Casa 5 - Exemplo",
                tipo: "Residencial",
                tipo_negocio: "aluguel",
                valor_aluguel: 1200,
                imagens: [
                    "imgs/Casa5/Casa5.0.jpg",
                    "imgs/Casa5/Casa5.1.jpg",
                    "imgs/Casa5/Casa5.2.jpg",
                    "imgs/Casa5/Casa5.3.jpg"
                ]
            },
            {
                id: 6,
                nome: "Casa 6 - Exemplo",
                tipo: "Residencial",
                tipo_negocio: "compra",
                valor_compra: 250000,
                imagens: [
                    "imgs/Casa6/Casa6.0.jpg",
                    "imgs/Casa6/Casa6.1.jpg",
                    "imgs/Casa6/Casa6.2.jpg",
                    "imgs/Casa6/Casa6.3.jpg"
                ]
            }
        ];
        <?php } ?>

        function renderCasas() {
            const container = document.getElementById('casasCards');
            container.innerHTML = '';
            casas.forEach((casa, idx) => {
                const card = document.createElement('div');
                card.className = 'col-md-4 mb-4';
                const carouselId = `carousel-casa-${casa.id}`;
                // Lógica robusta para exibir preços mesmo se faltar valor de aluguel ou compra
                const parseValor = (v) => {
                    if (v === undefined || v === null || v === '' ) return null;
                    if (typeof v === 'number') return v;
                    // Tentar converter string tipo "450.000,00" em número
                    const num = parseFloat(String(v).replace(/[^0-9,]/g,'').replace(',','.'));
                    return isNaN(num) ? null : num;
                };
                const valorCompraNum = parseValor(casa.valor_compra);
                const valorAluguelNum = parseValor(casa.valor_aluguel);
                const hasCompra = valorCompraNum !== null && valorCompraNum > 0;
                const hasAluguel = valorAluguelNum !== null && valorAluguelNum > 0;
                const formatCurrency = (n) => n.toLocaleString('pt-BR', {minimumFractionDigits: 2});
                let negocioHtml = '';
                if (hasCompra && hasAluguel) {
                    negocioHtml = `<span class='badge bg-success mb-2'>À venda</span> <span class='badge bg-info mb-2'>Para Alugar</span><br><strong>Valor de compra:</strong> R$ ${formatCurrency(valorCompraNum)}<br><strong>Valor do aluguel:</strong> R$ ${formatCurrency(valorAluguelNum)}`;
                } else if (hasCompra) {
                    negocioHtml = `<span class='badge bg-success mb-2'>À venda</span><br><strong>Valor de compra:</strong> R$ ${formatCurrency(valorCompraNum)}`;
                } else if (hasAluguel) {
                    negocioHtml = `<span class='badge bg-info mb-2'>Para Alugar</span><br><strong>Valor do aluguel:</strong> R$ ${formatCurrency(valorAluguelNum)}`;
                } else {
                    negocioHtml = `<span class='badge bg-secondary mb-2'>Sem preço cadastrado</span>`;
                }
                card.innerHTML = `
                    <div class="card h-100 border-top border-primary" style="border-width:4px !important;">
                        <div class="position-absolute top-0 end-0 m-2">
                            <span class="badge bg-primary">${casa.imagens.length} imagem${casa.imagens.length > 1 ? 's' : ''}</span>
                        </div>
                        <div id="${carouselId}" class="carousel slide">
                            <div class="carousel-indicators">
                                ${casa.imagens.map((img, i) => `
                                    <button type="button" data-bs-target="#${carouselId}" data-bs-slide-to="${i}"${i === 0 ? ' class=\"active\" aria-current=\"true\"' : ''} aria-label="Slide ${i+1}"></button>
                                `).join('')}
                            </div>
                            <div class="carousel-inner">
                                ${casa.imagens.map((img, i) => `
                                    <div class="carousel-item${i === 0 ? ' active' : ''}">
                                        <img src="${img}" class="d-block w-100" alt="Casa ${casa.id}" style="height:220px;object-fit:cover;">
                                    </div>
                                `).join('')}
                            </div>
                            ${casa.imagens.length > 1 ? `
                                <button class="carousel-control-prev" type="button" data-bs-target="#${carouselId}" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Anterior</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#${carouselId}" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Próximo</span>
                                </button>
                            ` : ''}
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">${casa.nome}</h5>
                            <p class="card-text mb-1"><strong>Tipo:</strong> ${casa.tipo}</p>
                            <div class="mb-2">${negocioHtml}</div>
                            <div class="d-flex justify-content-center mt-3">
                                <a class="btn btn-primary btn-sm" href="Casas/Casa${casa.id}.php">
                                    <i class="bi bi-info-circle"></i> Mais informações
                                </a>
                            </div>
                        </div>
                    </div>
                `;
                container.appendChild(card);
            });
        }

        function abrirModalEditarCasa(id) {
            const casa = casas.find(c => c.id === id);
            if (!casa) return;
            const elId = document.getElementById('editCasaId');
            if (elId) elId.value = casa.id;
            const elNome = document.getElementById('editCasaNome');
            if (elNome) elNome.value = casa.nome;
            const elValor = document.getElementById('editCasaValor');
            if (elValor) elValor.value = casa.valor;
            const elTipo = document.getElementById('editCasaTipo');
            if (elTipo) elTipo.value = casa.tipo;
            const elImagens = document.getElementById('editCasaImagens');
            if (elImagens) elImagens.value = '';
            renderImagensPreview(casa.imagens);
            const modalEl = document.getElementById('modalEditarCasa');
            if (modalEl) {
                const modal = new bootstrap.Modal(modalEl);
                modal.show();
            }
        }

        function renderImagensPreview(imagens) {
            const preview = document.getElementById('imagensPreview');
            preview.innerHTML = '';
            imagens.forEach(src => {
                const img = document.createElement('img');
                img.src = src;
                img.style.height = '60px';
                img.style.objectFit = 'cover';
                img.className = 'rounded border';
                preview.appendChild(img);
            });
        }

        const editCasaImagens = document.getElementById('editCasaImagens');
        if (editCasaImagens) {
            editCasaImagens.addEventListener('change', function(e) {
                const files = Array.from(e.target.files);
                const preview = document.getElementById('imagensPreview');
                if (preview) {
                    preview.innerHTML = '';
                    files.forEach(file => {
                        const reader = new FileReader();
                        reader.onload = function(ev) {
                            const img = document.createElement('img');
                            img.src = ev.target.result;
                            img.style.height = '60px';
                            img.style.objectFit = 'cover';
                            img.className = 'rounded border';
                            preview.appendChild(img);
                        };
                        reader.readAsDataURL(file);
                    });
                }
            });
        }

        const formEditarCasa = document.getElementById('formEditarCasa');
        if (formEditarCasa) {
            formEditarCasa.addEventListener('submit', function(e) {
                e.preventDefault();
                const id = parseInt(document.getElementById('editCasaId').value);
                const nome = document.getElementById('editCasaNome').value;
                const valor = parseFloat(document.getElementById('editCasaValor').value);
                const tipo = document.getElementById('editCasaTipo').value;
                const editCasaImagens = document.getElementById('editCasaImagens');
                const files = editCasaImagens ? Array.from(editCasaImagens.files) : [];
                let imagens = casas.find(c => c.id === id).imagens;
                if (files.length > 0) {
                    imagens = [];
                    files.forEach(file => {
                        imagens.push(URL.createObjectURL(file));
                    });
                }
                casas = casas.map(c => c.id === id ? { ...c, nome, valor, tipo, imagens } : c);
                renderCasas();
                const modalEditarCasa = document.getElementById('modalEditarCasa');
                if (modalEditarCasa) {
                    bootstrap.Modal.getInstance(modalEditarCasa).hide();
                }
            });
        }

        function excluirCasa(id) {
            if (confirm('Tem certeza que deseja excluir esta casa?')) {
                casas = casas.filter(c => c.id !== id);
                renderCasas();
            }
        }

        // Inicialização
        document.addEventListener('DOMContentLoaded', renderCasas);
        </script>
</body>
</html>