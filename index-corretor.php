
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
        <!-- SimpleLightbox plugin CSS-->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/SimpleLightbox/2.1.0/simpleLightbox.min.css" rel="stylesheet" />
        <!-- Core theme CSS (includes Bootstrap)-->
       <link href="css/styles.css" rel="stylesheet">
    </head>
<body>
    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top py-3" id="mainNav">
        <div class="container px-4 px-lg-5">
            <button class="navbar-toggler navbar-toggler-right" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ms-auto my-2 my-lg-0">
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
                                <li><a class="dropdown-item" href="perfil.php">Meu Perfil</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="logout.php">Sair</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <section class="admin-section">
        <div class="container" style="padding-top: 90px;">
            <h2 class="mb-4">Casas Disponíveis</h2>
            <div class="row justify-content-center" id="casasCards">
                <!-- Cards serão renderizados via JS -->
            </div>
        </div>
    </section>

        <!-- Modal de Edição -->
        <div class="modal fade" id="modalEditarCasa" tabindex="-1" aria-labelledby="modalEditarCasaLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditarCasaLabel">Editar Casa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <form id="formEditarCasa">
                        <div class="modal-body">
                            <input type="hidden" id="editCasaId">
                            <div class="mb-3">
                                <label for="editCasaNome" class="form-label">Nome</label>
                                <input type="text" class="form-control" id="editCasaNome" required>
                            </div>
                            <div class="mb-3">
                                <label for="editCasaValor" class="form-label">Valor (R$)</label>
                                <input type="number" class="form-control" id="editCasaValor" min="0" required>
                            </div>
                            <div class="mb-3">
                                <label for="editCasaTipo" class="form-label">Tipo</label>
                                <input type="text" class="form-control" id="editCasaTipo" required>
                            </div>
                            <div class="mb-3">
                                <label for="editCasaImagens" class="form-label">Imagens</label>
                                <input type="file" class="form-control" id="editCasaImagens" accept="image/*" multiple>
                                <div id="imagensPreview" class="mt-2 d-flex flex-wrap gap-2"></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
   
                <!-- Pedidos Personalizados -->
                <div class="tab-pane fade" id="custom-orders" role="tabpanel">
                    <div class="card">
                        <div class="card-header">Pedidos Personalizados</div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped mb-0" id="customOrdersTable">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Cliente</th>
                                            <th>Email</th>
                                            <th>Descrição</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Pedidos personalizados via JS -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

        <script src="js/scripts.js"></script>
        <script>
        // Dados iniciais das casas
        let casas = [
            {
                id: 1,
                nome: "Casa em Santo Antônio da Patrulha",
                valor: 300000,
                tipo: "Residencial",
                imagens: [
                    "imgs/Casa1/Casa1.0.jpg",
                    "imgs/Casa1/Casa1.1.jpg",
                    "imgs/Casa1/Casa1.2.jpg",
                    "imgs/Casa1/Casa1.3.jpg",
                    "imgs/Casa1/Casa1.4.jpg",
                    "imgs/Casa1/Casa1.5.jpg",
                    "imgs/Casa1/Casa1.6.jpg",
                    "imgs/Casa1/Casa1.7.jpg",
                    "imgs/Casa1/Casa1.8.jpg",
                    "imgs/Casa1/Casa1.9.jpg"
                ]
            },
            {
                id: 2,
                nome: "Casa em Taquara",
                valor: 220000,
                tipo: "Residencial",
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
            },
            {
                id: 3,
                nome: "Casa em Taquara Alto Padrão",
                valor: 3000000,
                tipo: "Alto Padrão",
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
            }
        ];

        function renderCasas() {
            const container = document.getElementById('casasCards');
            container.innerHTML = '';
            casas.forEach((casa, idx) => {
                const card = document.createElement('div');
                card.className = 'col-md-4 mb-4';
                const carouselId = `carousel-casa-${casa.id}`;
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
                            <p class="card-text mb-1"><strong>Valor:</strong> R$ ${casa.valor.toLocaleString('pt-BR')}</p>
                            <p class="card-text"><strong>Tipo:</strong> ${casa.tipo}</p>
                            <div class="d-flex justify-content-between mt-3">
                                <button class="btn btn-warning btn-sm" onclick="abrirModalEditarCasa(${casa.id})"><i class="bi bi-pencil"></i> Editar</button>
                                <button class="btn btn-danger btn-sm" onclick="excluirCasa(${casa.id})"><i class="bi bi-trash"></i> Excluir</button>
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
            document.getElementById('editCasaId').value = casa.id;
            document.getElementById('editCasaNome').value = casa.nome;
            document.getElementById('editCasaValor').value = casa.valor;
            document.getElementById('editCasaTipo').value = casa.tipo;
            document.getElementById('editCasaImagens').value = '';
            renderImagensPreview(casa.imagens);
            const modal = new bootstrap.Modal(document.getElementById('modalEditarCasa'));
            modal.show();
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

        document.getElementById('editCasaImagens').addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            const preview = document.getElementById('imagensPreview');
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
        });

        document.getElementById('formEditarCasa').addEventListener('submit', function(e) {
            e.preventDefault();
            const id = parseInt(document.getElementById('editCasaId').value);
            const nome = document.getElementById('editCasaNome').value;
            const valor = parseFloat(document.getElementById('editCasaValor').value);
            const tipo = document.getElementById('editCasaTipo').value;
            const files = Array.from(document.getElementById('editCasaImagens').files);
            let imagens = casas.find(c => c.id === id).imagens;
            if (files.length > 0) {
                imagens = [];
                files.forEach(file => {
                    imagens.push(URL.createObjectURL(file));
                });
            }
            casas = casas.map(c => c.id === id ? { ...c, nome, valor, tipo, imagens } : c);
            renderCasas();
            bootstrap.Modal.getInstance(document.getElementById('modalEditarCasa')).hide();
        });

        function excluirCasa(id) {
            if (confirm('Tem certeza que deseja excluir esta casa?')) {
                casas = casas.filter(c => c.id !== id);
                renderCasas();
            }
        }

        // Inicialização
        document.addEventListener('DOMContentLoaded', renderCasas);
        </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>