<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Administração</title>
    <link href="css/styles.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Administração</a>
        </div>
    </nav>
    <div class="container my-5">
        <h2 class="mb-4">Gerenciar Corretores</h2>
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Contas de Corretores</span>
                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalCorretor" onclick="openCorretorModal()"><i class="fas fa-plus"></i> Novo Corretor</button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped mb-0" id="corretoresTable">
                        <thead class="table-dark">
                            <tr>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Ações</th>
                                <th>Imóveis</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Corretores via JS -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal de Cadastro/Edição de Corretor -->
        <div class="modal fade" id="modalCorretor" tabindex="-1" aria-labelledby="modalCorretorLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <form id="corretorForm">
                <div class="modal-header">
                  <h5 class="modal-title" id="modalCorretorLabel">Novo Corretor</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <input type="hidden" id="corretorId">
                        <tbody>
                        <tr>
                            <td colspan="6">
                                <h4 class="mb-4">Imóveis dos Corretores</h4>
                                <div class="row g-4">
                                    <!-- Casa 1 -->
                                    <div class="col-lg-4 col-md-6">
                                        <div class="card property-card" style="border-radius:12px;box-shadow:0 4px 16px rgba(0,0,0,0.08);">
                                            <div class="position-relative overflow-hidden">
                                                <div id="carouselCasa1" class="carousel slide" data-bs-ride="carousel" style="width:100%;margin:auto;">
                                                    <div class="carousel-inner" style="border-radius:10px;">
                                                        <div class="carousel-item active"><img src="imgs/Casa1/Casa1.0.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Santo Antônio da Patrulha 1"></div>
                                                        <div class="carousel-item"><img src="imgs/Casa1/Casa1.1.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Santo Antônio da Patrulha 2"></div>
                                                        <div class="carousel-item"><img src="imgs/Casa1/Casa1.2.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Santo Antônio da Patrulha 3"></div>
                                                        <div class="carousel-item"><img src="imgs/Casa1/Casa1.3.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Santo Antônio da Patrulha 4"></div>
                                                        <div class="carousel-item"><img src="imgs/Casa1/Casa1.4.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Santo Antônio da Patrulha 5"></div>
                                                        <div class="carousel-item"><img src="imgs/Casa1/Casa1.5.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Santo Antônio da Patrulha 6"></div>
                                                        <div class="carousel-item"><img src="imgs/Casa1/Casa1.6.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Santo Antônio da Patrulha 7"></div>
                                                        <div class="carousel-item"><img src="imgs/Casa1/Casa1.7.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Santo Antônio da Patrulha 8"></div>
                                                        <div class="carousel-item"><img src="imgs/Casa1/Casa1.8.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Santo Antônio da Patrulha 9"></div>
                                                        <div class="carousel-item"><img src="imgs/Casa1/Casa1.9.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Santo Antônio da Patrulha 10"></div>
                                                    </div>
                                                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselCasa1" data-bs-slide="prev" style="width:32px;height:32px;top:50%;transform:translateY(-50%);">
                                                        <span class="carousel-control-prev-icon" style="width:16px;height:16px;" aria-hidden="true"></span>
                                                        <span class="visually-hidden">Anterior</span>
                                                    </button>
                                                    <button class="carousel-control-next" type="button" data-bs-target="#carouselCasa1" data-bs-slide="next" style="width:32px;height:32px;top:50%;transform:translateY(-50%);">
                                                        <span class="carousel-control-next-icon" style="width:16px;height:16px;" aria-hidden="true"></span>
                                                        <span class="visually-hidden">Próximo</span>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <h5 class="property-title" style="font-size:1.1rem;font-weight:600;color:#333;">Casa em Santo Antônio da Patrulha</h5>
                                                <div class="property-price price-sale mb-2" style="font-size:1rem;font-weight:700;">R$ 300.000,00</div>
                                                <div class="mb-2"><span class="badge bg-success rounded-pill">Disponível</span></div>
                                                <div class="mb-2"><span class="badge bg-primary rounded-pill">João Silva</span></div>
                                                <a href="Casas/Casa1.php" class="btn btn-details w-100 btn-sm" style="background:linear-gradient(45deg,#ff7b00,#ff9500);border:none;color:white;">Ver Detalhes</a>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Repita para casas 2 a 9, seguindo o mesmo padrão -->
                                </div>
                            </td>
                        </tr>
                                        </button>
                                        <button class="carousel-control-next" type="button" data-bs-target="#carouselCasa1" data-bs-slide="next" style="width:32px;height:32px;top:50%;transform:translateY(-50%);">
                                            <span class="carousel-control-next-icon" style="width:16px;height:16px;" aria-hidden="true"></span>
                                            <span class="visually-hidden">Próximo</span>
                                        </button>
                                        <div class="carousel-indicators" style="bottom:0;">
                                            <button type="button" data-bs-target="#carouselCasa1" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1" style="width:8px;height:8px;"></button>
                                            <button type="button" data-bs-target="#carouselCasa1" data-bs-slide-to="1" aria-label="Slide 2" style="width:8px;height:8px;"></button>
                                            <button type="button" data-bs-target="#carouselCasa1" data-bs-slide-to="2" aria-label="Slide 3" style="width:8px;height:8px;"></button>
                                            <button type="button" data-bs-target="#carouselCasa1" data-bs-slide-to="3" aria-label="Slide 4" style="width:8px;height:8px;"></button>
                                            <button type="button" data-bs-target="#carouselCasa1" data-bs-slide-to="4" aria-label="Slide 5" style="width:8px;height:8px;"></button>
                                            <button type="button" data-bs-target="#carouselCasa1" data-bs-slide-to="5" aria-label="Slide 6" style="width:8px;height:8px;"></button>
                                            <button type="button" data-bs-target="#carouselCasa1" data-bs-slide-to="6" aria-label="Slide 7" style="width:8px;height:8px;"></button>
                                            <button type="button" data-bs-target="#carouselCasa1" data-bs-slide-to="7" aria-label="Slide 8" style="width:8px;height:8px;"></button>
                                            <button type="button" data-bs-target="#carouselCasa1" data-bs-slide-to="8" aria-label="Slide 9" style="width:8px;height:8px;"></button>
                                            <button type="button" data-bs-target="#carouselCasa1" data-bs-slide-to="9" aria-label="Slide 10" style="width:8px;height:8px;"></button>
                                        </div>
                                    </div>
                                </td>
                                <td>Casa em Santo Antônio da Patrulha</td>
                                <td>João Silva</td>
                                <td>R$ 300.000,00</td>
                                <td>Disponível</td>
                                <td><a href="Casas/Casa1.php" class="btn btn-sm btn-primary">Ver detalhes</a></td>
                            </tr>
                            <tr>
                                <td>
                                    <div id="carouselCasa2" class="carousel slide" data-bs-ride="carousel">
                                        <div class="carousel-inner">
                                            <div class="carousel-item active"><img src="imgs/Casa2/Casa2.0.jpg" class="d-block w-100 product-img-preview" alt="Casa em Taquara 1"></div>
                                            <div class="carousel-item"><img src="imgs/Casa2/Casa2.1.jpg" class="d-block w-100 product-img-preview" alt="Casa em Taquara 2"></div>
                                            <div class="carousel-item"><img src="imgs/Casa2/Casa2.2.jpg" class="d-block w-100 product-img-preview" alt="Casa em Taquara 3"></div>
                                            <div class="carousel-item"><img src="imgs/Casa2/Casa2.3.jpg" class="d-block w-100 product-img-preview" alt="Casa em Taquara 4"></div>
                                            <div class="carousel-item"><img src="imgs/Casa2/Casa2.4.jpg" class="d-block w-100 product-img-preview" alt="Casa em Taquara 5"></div>
                                            <div class="carousel-item"><img src="imgs/Casa2/Casa2.5.jpg" class="d-block w-100 product-img-preview" alt="Casa em Taquara 6"></div>
                                            <div class="carousel-item"><img src="imgs/Casa2/Casa2.6.jpg" class="d-block w-100 product-img-preview" alt="Casa em Taquara 7"></div>
                                            <div class="carousel-item"><img src="imgs/Casa2/Casa2.7.jpg" class="d-block w-100 product-img-preview" alt="Casa em Taquara 8"></div>
                                            <div class="carousel-item"><img src="imgs/Casa2/Casa2.8.jpg" class="d-block w-100 product-img-preview" alt="Casa em Taquara 9"></div>
                                            <div class="carousel-item"><img src="imgs/Casa2/Casa2.9.jpg" class="d-block w-100 product-img-preview" alt="Casa em Taquara 10"></div>
                                            <div class="carousel-item"><img src="imgs/Casa2/Casa2.10.jpg" class="d-block w-100 product-img-preview" alt="Casa em Taquara 11"></div>
                                            <div class="carousel-item"><img src="imgs/Casa2/Casa2.11.jpg" class="d-block w-100 product-img-preview" alt="Casa em Taquara 12"></div>
                                            <div class="carousel-item"><img src="imgs/Casa2/Casa2.12.jpg" class="d-block w-100 product-img-preview" alt="Casa em Taquara 13"></div>
                                            <div class="carousel-item"><img src="imgs/Casa2/Casa2.13.jpg" class="d-block w-100 product-img-preview" alt="Casa em Taquara 14"></div>
                                        </div>
                                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselCasa2" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Anterior</span>
                                        </button>
                                        <button class="carousel-control-next" type="button" data-bs-target="#carouselCasa2" data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Próximo</span>
                                        </button>
                                    </div>
                                </td>
                                <td>Casa em Taquara</td>
                                <td>João Silva</td>
                                <td>R$ 220.000,00</td>
                                <td>Disponível</td>
                                <td><a href="Casas/Casa2.php" class="btn btn-sm btn-primary">Ver detalhes</a></td>
                            </tr>
                            <tr>
                                <td>
                                    <div id="carouselCasa3" class="carousel slide" data-bs-ride="carousel">
                                        <div class="carousel-inner">
                                            <div class="carousel-item active"><img src="imgs/Casa3/Casa3.0.jpg" class="d-block w-100 product-img-preview" alt="Casa em Taquara Alto Padrão 1"></div>
                                            <div class="carousel-item"><img src="imgs/Casa3/Casa3.1.jpg" class="d-block w-100 product-img-preview" alt="Casa em Taquara Alto Padrão 2"></div>
                                            <div class="carousel-item"><img src="imgs/Casa3/Casa3.2.jpg" class="d-block w-100 product-img-preview" alt="Casa em Taquara Alto Padrão 3"></div>
                                            <div class="carousel-item"><img src="imgs/Casa3/Casa3.3.jpg" class="d-block w-100 product-img-preview" alt="Casa em Taquara Alto Padrão 4"></div>
                                            <div class="carousel-item"><img src="imgs/Casa3/Casa3.4.jpg" class="d-block w-100 product-img-preview" alt="Casa em Taquara Alto Padrão 5"></div>
                                            <div class="carousel-item"><img src="imgs/Casa3/Casa3.5.jpg" class="d-block w-100 product-img-preview" alt="Casa em Taquara Alto Padrão 6"></div>
                                            <div class="carousel-item"><img src="imgs/Casa3/Casa3.6.jpg" class="d-block w-100 product-img-preview" alt="Casa em Taquara Alto Padrão 7"></div>
                                            <div class="carousel-item"><img src="imgs/Casa3/Casa3.7.jpg" class="d-block w-100 product-img-preview" alt="Casa em Taquara Alto Padrão 8"></div>
                                            <div class="carousel-item"><img src="imgs/Casa3/Casa3.8.jpg" class="d-block w-100 product-img-preview" alt="Casa em Taquara Alto Padrão 9"></div>
                                            <div class="carousel-item"><img src="imgs/Casa3/Casa3.9.jpg" class="d-block w-100 product-img-preview" alt="Casa em Taquara Alto Padrão 10"></div>
                                        </div>
                                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselCasa3" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Anterior</span>
                                        </button>
                                        <button class="carousel-control-next" type="button" data-bs-target="#carouselCasa3" data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Próximo</span>
                                        </button>
                                    </div>
                                </td>
                                <td>Casa em Taquara Alto Padrão</td>
                                <td>João Silva</td>
                                <td>R$ 3.000.000,00</td>
                                <td>Disponível</td>
                                <td><a href="Casas/Casa3.php" class="btn btn-sm btn-primary">Ver detalhes</a></td>
                            </tr>
                            <tr>
                                <td>
                                    <div id="carouselCasa4" class="carousel slide" data-bs-ride="carousel">
                                        <div class="carousel-inner">
                                            <div class="carousel-item active">
                                                <img src="imgs/Casa4/Casa4.0.jpg" class="d-block w-100 product-img-preview" alt="Casa em Parobé 1">
                                            </div>
                                            <div class="carousel-item">
                                                <img src="imgs/Casa4/Casa4.2.jpg" class="d-block w-100 product-img-preview" alt="Casa em Parobé 2">
                                            </div>
                                            <div class="carousel-item">
                                                <img src="imgs/Casa4/Casa4.3.jpg" class="d-block w-100 product-img-preview" alt="Casa em Parobé 3">
                                            </div>
                                        </div>
                                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselCasa4" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Anterior</span>
                                        </button>
                                        <button class="carousel-control-next" type="button" data-bs-target="#carouselCasa4" data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Próximo</span>
                                        </button>
                                    </div>
                                </td>
                                <td>Casa em Parobé</td>
                                <td>Maria Souza</td>
                                <td>R$ 180.000,00</td>
                                <td>Disponível</td>
                                <td><a href="Casas/Casa4.php" class="btn btn-sm btn-primary">Ver detalhes</a></td>
                            </tr>
                            <tr>
                                <td>
                                    <div id="carouselCasa5" class="carousel slide" data-bs-ride="carousel">
                                        <div class="carousel-inner">
                                            <div class="carousel-item active">
                                                <img src="imgs/Casa5/Casa5.0.jpg" class="d-block w-100 product-img-preview" alt="Casa em Igrejinha 1">
                                            </div>
                                            <div class="carousel-item">
                                                <img src="imgs/Casa5/Casa5.1.jpg" class="d-block w-100 product-img-preview" alt="Casa em Igrejinha 2">
                                            </div>
                                            <div class="carousel-item">
                                                <img src="imgs/Casa5/Casa5.10.jpg" class="d-block w-100 product-img-preview" alt="Casa em Igrejinha 3">
                                            </div>
                                        </div>
                                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselCasa5" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Anterior</span>
                                        </button>
                                        <button class="carousel-control-next" type="button" data-bs-target="#carouselCasa5" data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Próximo</span>
                                        </button>
                                    </div>
                                </td>
                                <td>Casa em Igrejinha</td>
                                <td>Maria Souza</td>
                                <td>R$ 250.000,00</td>
                                <td>Disponível</td>
                                <td><a href="Casas/Casa5.php" class="btn btn-sm btn-primary">Ver detalhes</a></td>
                            </tr>
                            <tr>
                                <td>
                                    <div id="carouselCasa6" class="carousel slide" data-bs-ride="carousel">
                                        <div class="carousel-inner">
                                            <div class="carousel-item active">
                                                <img src="imgs/Casa6/Casa6.0.jpg" class="d-block w-100 product-img-preview" alt="Casa em Rolante 1">
                                            </div>
                                            <div class="carousel-item">
                                                <img src="imgs/Casa6/Casa6.1.jpg" class="d-block w-100 product-img-preview" alt="Casa em Rolante 2">
                                            </div>
                                            <div class="carousel-item">
                                                <img src="imgs/Casa6/Casa6.2.jpg" class="d-block w-100 product-img-preview" alt="Casa em Rolante 3">
                                            </div>
                                        </div>
                                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselCasa6" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Anterior</span>
                                        </button>
                                        <button class="carousel-control-next" type="button" data-bs-target="#carouselCasa6" data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Próximo</span>
                                        </button>
                                    </div>
                                </td>
                                <td>Casa em Rolante</td>
                                <td>Maria Souza</td>
                                <td>R$ 320.000,00</td>
                                <td>Disponível</td>
                                <td><a href="Casas/Casa6.php" class="btn btn-sm btn-primary">Ver detalhes</a></td>
                            </tr>
                            <tr>
                                <td>
                                    <div id="carouselCasa7" class="carousel slide" data-bs-ride="carousel">
                                        <div class="carousel-inner">
                                            <div class="carousel-item active">
                                                <img src="imgs/Casa7/Casa7.0.jpg" class="d-block w-100 product-img-preview" alt="Casa em Sapiranga 1">
                                            </div>
                                            <div class="carousel-item">
                                                <img src="imgs/Casa7/Casa7.1.jpg" class="d-block w-100 product-img-preview" alt="Casa em Sapiranga 2">
                                            </div>
                                            <div class="carousel-item">
                                                <img src="imgs/Casa7/Casa7.2.jpg" class="d-block w-100 product-img-preview" alt="Casa em Sapiranga 3">
                                            </div>
                                        </div>
                                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselCasa7" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Anterior</span>
                                        </button>
                                        <button class="carousel-control-next" type="button" data-bs-target="#carouselCasa7" data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Próximo</span>
                                        </button>
                                    </div>
                                </td>
                                <td>Casa em Sapiranga</td>
                                <td>Pedro Costa</td>
                                <td>R$ 210.000,00</td>
                                <td>Disponível</td>
                                <td><a href="Casas/Casa7.php" class="btn btn-sm btn-primary">Ver detalhes</a></td>
                            </tr>
                            <tr>
                                <td>
                                    <div id="carouselCasa8" class="carousel slide" data-bs-ride="carousel">
                                        <div class="carousel-inner">
                                            <div class="carousel-item active">
                                                <img src="imgs/Casa8/Casa8.0.jpg" class="d-block w-100 product-img-preview" alt="Casa em Nova Hartz 1">
                                            </div>
                                            <div class="carousel-item">
                                                <img src="imgs/Casa8/Casa8.1.jpg" class="d-block w-100 product-img-preview" alt="Casa em Nova Hartz 2">
                                            </div>
                                            <div class="carousel-item">
                                                <img src="imgs/Casa8/Casa8.2.jpg" class="d-block w-100 product-img-preview" alt="Casa em Nova Hartz 3">
                                            </div>
                                        </div>
                                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselCasa8" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Anterior</span>
                                        </button>
                                        <button class="carousel-control-next" type="button" data-bs-target="#carouselCasa8" data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Próximo</span>
                                        </button>
                                    </div>
                                </td>
                                <td>Casa em Nova Hartz</td>
                                <td>Pedro Costa</td>
                                <td>R$ 195.000,00</td>
                                <td>Disponível</td>
                                <td><a href="Casas/Casa8.php" class="btn btn-sm btn-primary">Ver detalhes</a></td>
                            </tr>
                            <tr>
                                <td>
                                    <div id="carouselCasa9" class="carousel slide" data-bs-ride="carousel">
                                        <div class="carousel-inner">
                                            <div class="carousel-item active">
                                                <img src="imgs/Casa9/Casa9.0.jpg" class="d-block w-100 product-img-preview" alt="Casa em Campo Bom 1">
                                            </div>
                                            <div class="carousel-item">
                                                <img src="imgs/Casa9/Casa9.1.jpg" class="d-block w-100 product-img-preview" alt="Casa em Campo Bom 2">
                                            </div>
                                            <div class="carousel-item">
                                                <img src="imgs/Casa9/Casa9.2.jpg" class="d-block w-100 product-img-preview" alt="Casa em Campo Bom 3">
                                            </div>
                                        </div>
                                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselCasa9" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Anterior</span>
                                        </button>
                                        <button class="carousel-control-next" type="button" data-bs-target="#carouselCasa9" data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Próximo</span>
                                        </button>
                                    </div>
                                </td>
                                <td>Casa em Campo Bom</td>
                                <td>Pedro Costa</td>
                                <td>R$ 275.000,00</td>
                                <td>Disponível</td>
                                <td><a href="Casas/Casa9.php" class="btn btn-sm btn-primary">Ver detalhes</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="js/admin.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
