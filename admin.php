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
                                    <div class="mb-3">
                                        <label for="corretorNome" class="form-label">Nome do Corretor</label>
                                        <input type="text" class="form-control" id="corretorNome" name="corretorNome" placeholder="Digite o nome completo" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="corretorEmail" class="form-label">E-mail</label>
                                        <input type="email" class="form-control" id="corretorEmail" name="corretorEmail" placeholder="exemplo@email.com" required>
                                    </div>
                </div>
              </form>
            </div>
          </div>
        </div>

    <!-- Gerenciador de Imóveis -->
    <div class="card mt-5">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Gerenciador de Imóveis dos Corretores</h4>
        </div>
        <div class="card-body">
            <div class="row row-cols-1 row-cols-md-3 g-4">
         
                <!-- Imóvel 4 -->
                <div class="col">
                    <div class="card property-card" style="border-radius:12px;box-shadow:0 4px 16px rgba(0,0,0,0.08);">
                        <div class="position-relative overflow-hidden">
                            <div id="carouselCasa4" class="carousel slide" data-bs-ride="carousel" style="width:100%;margin:auto;">
                                <div class="carousel-inner" style="border-radius:10px;">
                                    <div class="carousel-item active"><img src="imgs/Casa4/Casa4.0.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Parobé 1"></div>
                                    <div class="carousel-item"><img src="imgs/Casa4/Casa4.2.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Parobé 2"></div>
                                    <div class="carousel-item"><img src="imgs/Casa4/Casa4.3.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Parobé 3"></div>
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselCasa4" data-bs-slide="prev" style="width:32px;height:32px;top:50%;transform:translateY(-50%);">
                                    <span class="carousel-control-prev-icon" style="width:16px;height:16px;" aria-hidden="true"></span>
                                    <span class="visually-hidden">Anterior</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselCasa4" data-bs-slide="next" style="width:32px;height:32px;top:50%;transform:translateY(-50%);">
                                    <span class="carousel-control-next-icon" style="width:16px;height:16px;" aria-hidden="true"></span>
                                    <span class="visually-hidden">Próximo</span>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="property-title" style="font-size:1.1rem;font-weight:600;color:#333;">Casa em Parobé</h5>
                            <div class="property-price price-sale mb-2" style="font-size:1rem;font-weight:700;">R$ 180.000,00</div>
                            <div class="mb-2"><span class="badge bg-success rounded-pill">Disponível</span></div>
                            <div class="mb-2"><span class="badge bg-primary rounded-pill">Maria Souza</span></div>
                            <a href="Casas/Casa4.php" class="btn btn-details w-100 btn-sm" style="background:linear-gradient(45deg,#ff7b00,#ff9500);border:none;color:white;">Ver Detalhes</a>
                            <div class="d-flex justify-content-between mt-3">
                                <button class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i> Editar</button>
                                <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i> Excluir</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Imóvel 5 -->
                <div class="col">
                    <div class="card property-card" style="border-radius:12px;box-shadow:0 4px 16px rgba(0,0,0,0.08);">
                        <div class="position-relative overflow-hidden">
                            <div id="carouselCasa5" class="carousel slide" data-bs-ride="carousel" style="width:100%;margin:auto;">
                                <div class="carousel-inner" style="border-radius:10px;">
                                    <div class="carousel-item active"><img src="imgs/Casa5/Casa5.0.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Igrejinha 1"></div>
                                    <div class="carousel-item"><img src="imgs/Casa5/Casa5.1.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Igrejinha 2"></div>
                                    <div class="carousel-item"><img src="imgs/Casa5/Casa5.10.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Igrejinha 3"></div>
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselCasa5" data-bs-slide="prev" style="width:32px;height:32px;top:50%;transform:translateY(-50%);">
                                    <span class="carousel-control-prev-icon" style="width:16px;height:16px;" aria-hidden="true"></span>
                                    <span class="visually-hidden">Anterior</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselCasa5" data-bs-slide="next" style="width:32px;height:32px;top:50%;transform:translateY(-50%);">
                                    <span class="carousel-control-next-icon" style="width:16px;height:16px;" aria-hidden="true"></span>
                                    <span class="visually-hidden">Próximo</span>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="property-title" style="font-size:1.1rem;font-weight:600;color:#333;">Casa em Igrejinha</h5>
                            <div class="property-price price-sale mb-2" style="font-size:1rem;font-weight:700;">R$ 250.000,00</div>
                            <div class="mb-2"><span class="badge bg-success rounded-pill">Disponível</span></div>
                            <div class="mb-2"><span class="badge bg-primary rounded-pill">Maria Souza</span></div>
                            <a href="Casas/Casa5.php" class="btn btn-details w-100 btn-sm" style="background:linear-gradient(45deg,#ff7b00,#ff9500);border:none;color:white;">Ver Detalhes</a>
                            <div class="d-flex justify-content-between mt-3">
                                <button class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i> Editar</button>
                                <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i> Excluir</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Imóvel 6 -->
                <div class="col">
                    <div class="card property-card" style="border-radius:12px;box-shadow:0 4px 16px rgba(0,0,0,0.08);">
                        <div class="position-relative overflow-hidden">
                            <div id="carouselCasa6" class="carousel slide" data-bs-ride="carousel" style="width:100%;margin:auto;">
                                <div class="carousel-inner" style="border-radius:10px;">
                                    <div class="carousel-item active"><img src="imgs/Casa6/Casa6.0.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Rolante 1"></div>
                                    <div class="carousel-item"><img src="imgs/Casa6/Casa6.1.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Rolante 2"></div>
                                    <div class="carousel-item"><img src="imgs/Casa6/Casa6.2.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Rolante 3"></div>
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselCasa6" data-bs-slide="prev" style="width:32px;height:32px;top:50%;transform:translateY(-50%);">
                                    <span class="carousel-control-prev-icon" style="width:16px;height:16px;" aria-hidden="true"></span>
                                    <span class="visually-hidden">Anterior</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselCasa6" data-bs-slide="next" style="width:32px;height:32px;top:50%;transform:translateY(-50%);">
                                    <span class="carousel-control-next-icon" style="width:16px;height:16px;" aria-hidden="true"></span>
                                    <span class="visually-hidden">Próximo</span>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="property-title" style="font-size:1.1rem;font-weight:600;color:#333;">Casa em Rolante</h5>
                            <div class="property-price price-sale mb-2" style="font-size:1rem;font-weight:700;">R$ 320.000,00</div>
                            <div class="mb-2"><span class="badge bg-success rounded-pill">Disponível</span></div>
                            <div class="mb-2"><span class="badge bg-primary rounded-pill">Maria Souza</span></div>
                            <a href="Casas/Casa6.php" class="btn btn-details w-100 btn-sm" style="background:linear-gradient(45deg,#ff7b00,#ff9500);border:none;color:white;">Ver Detalhes</a>
                            <div class="d-flex justify-content-between mt-3">
                                <button class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i> Editar</button>
                                <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i> Excluir</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Imóvel 7 -->
                <div class="col">
                    <div class="card property-card" style="border-radius:12px;box-shadow:0 4px 16px rgba(0,0,0,0.08);">
                        <div class="position-relative overflow-hidden">
                            <div id="carouselCasa7" class="carousel slide" data-bs-ride="carousel" style="width:100%;margin:auto;">
                                <div class="carousel-inner" style="border-radius:10px;">
                                    <div class="carousel-item active"><img src="imgs/Casa7/Casa7.0.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Sapiranga 1"></div>
                                    <div class="carousel-item"><img src="imgs/Casa7/Casa7.1.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Sapiranga 2"></div>
                                    <div class="carousel-item"><img src="imgs/Casa7/Casa7.2.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Sapiranga 3"></div>
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselCasa7" data-bs-slide="prev" style="width:32px;height:32px;top:50%;transform:translateY(-50%);">
                                    <span class="carousel-control-prev-icon" style="width:16px;height:16px;" aria-hidden="true"></span>
                                    <span class="visually-hidden">Anterior</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselCasa7" data-bs-slide="next" style="width:32px;height:32px;top:50%;transform:translateY(-50%);">
                                    <span class="carousel-control-next-icon" style="width:16px;height:16px;" aria-hidden="true"></span>
                                    <span class="visually-hidden">Próximo</span>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="property-title" style="font-size:1.1rem;font-weight:600;color:#333;">Casa em Sapiranga</h5>
                            <div class="property-price price-sale mb-2" style="font-size:1rem;font-weight:700;">R$ 210.000,00</div>
                            <div class="mb-2"><span class="badge bg-success rounded-pill">Disponível</span></div>
                            <div class="mb-2"><span class="badge bg-primary rounded-pill">Pedro Costa</span></div>
                            <a href="Casas/Casa7.php" class="btn btn-details w-100 btn-sm" style="background:linear-gradient(45deg,#ff7b00,#ff9500);border:none;color:white;">Ver Detalhes</a>
                            <div class="d-flex justify-content-between mt-3">
                                <button class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i> Editar</button>
                                <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i> Excluir</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Imóvel 8 -->
                <div class="col">
                    <div class="card property-card" style="border-radius:12px;box-shadow:0 4px 16px rgba(0,0,0,0.08);">
                        <div class="position-relative overflow-hidden">
                            <div id="carouselCasa8" class="carousel slide" data-bs-ride="carousel" style="width:100%;margin:auto;">
                                <div class="carousel-inner" style="border-radius:10px;">
                                    <div class="carousel-item active"><img src="imgs/Casa8/Casa8.0.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Nova Hartz 1"></div>
                                    <div class="carousel-item"><img src="imgs/Casa8/Casa8.1.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Nova Hartz 2"></div>
                                    <div class="carousel-item"><img src="imgs/Casa8/Casa8.2.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Nova Hartz 3"></div>
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselCasa8" data-bs-slide="prev" style="width:32px;height:32px;top:50%;transform:translateY(-50%);">
                                    <span class="carousel-control-prev-icon" style="width:16px;height:16px;" aria-hidden="true"></span>
                                    <span class="visually-hidden">Anterior</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselCasa8" data-bs-slide="next" style="width:32px;height:32px;top:50%;transform:translateY(-50%);">
                                    <span class="carousel-control-next-icon" style="width:16px;height:16px;" aria-hidden="true"></span>
                                    <span class="visually-hidden">Próximo</span>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="property-title" style="font-size:1.1rem;font-weight:600;color:#333;">Casa em Nova Hartz</h5>
                            <div class="property-price price-sale mb-2" style="font-size:1rem;font-weight:700;">R$ 195.000,00</div>
                            <div class="mb-2"><span class="badge bg-success rounded-pill">Disponível</span></div>
                            <div class="mb-2"><span class="badge bg-primary rounded-pill">Pedro Costa</span></div>
                            <a href="Casas/Casa8.php" class="btn btn-details w-100 btn-sm" style="background:linear-gradient(45deg,#ff7b00,#ff9500);border:none;color:white;">Ver Detalhes</a>
                            <div class="d-flex justify-content-between mt-3">
                                <button class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i> Editar</button>
                                <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i> Excluir</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Imóvel 9 -->
                <div class="col">
                    <div class="card property-card" style="border-radius:12px;box-shadow:0 4px 16px rgba(0,0,0,0.08);">
                        <div class="position-relative overflow-hidden">
                            <div id="carouselCasa9" class="carousel slide" data-bs-ride="carousel" style="width:100%;margin:auto;">
                                <div class="carousel-inner" style="border-radius:10px;">
                                    <div class="carousel-item active"><img src="imgs/Casa9/Casa9.0.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Campo Bom 1"></div>
                                    <div class="carousel-item"><img src="imgs/Casa9/Casa9.1.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Campo Bom 2"></div>
                                    <div class="carousel-item"><img src="imgs/Casa9/Casa9.2.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Campo Bom 3"></div>
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselCasa9" data-bs-slide="prev" style="width:32px;height:32px;top:50%;transform:translateY(-50%);">
                                    <span class="carousel-control-prev-icon" style="width:16px;height:16px;" aria-hidden="true"></span>
                                    <span class="visually-hidden">Anterior</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselCasa9" data-bs-slide="next" style="width:32px;height:32px;top:50%;transform:translateY(-50%);">
                                    <span class="carousel-control-next-icon" style="width:16px;height:16px;" aria-hidden="true"></span>
                                    <span class="visually-hidden">Próximo</span>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="property-title" style="font-size:1.1rem;font-weight:600;color:#333;">Casa em Campo Bom</h5>
                            <div class="property-price price-sale mb-2" style="font-size:1rem;font-weight:700;">R$ 275.000,00</div>
                            <div class="mb-2"><span class="badge bg-success rounded-pill">Disponível</span></div>
                            <div class="mb-2"><span class="badge bg-primary rounded-pill">Pedro Costa</span></div>
                            <a href="Casas/Casa9.php" class="btn btn-details w-100 btn-sm" style="background:linear-gradient(45deg,#ff7b00,#ff9500);border:none;color:white;">Ver Detalhes</a>
                            <div class="d-flex justify-content-between mt-3">
                                <button class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i> Editar</button>
                                <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i> Excluir</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
                                        <!-- ...carrosséis grandes removidos... -->
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
                                    <div id="carouselCasa4" class="carousel slide" data-bs-ride="carousel" style="width:100%;margin:auto;">
                                        <div class="carousel-inner" style="border-radius:10px;">
                                            <div class="carousel-item active"><img src="imgs/Casa4/Casa4.0.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Parobé 1"></div>
                                            <div class="carousel-item"><img src="imgs/Casa4/Casa4.2.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Parobé 2"></div>
                                            <div class="carousel-item"><img src="imgs/Casa4/Casa4.3.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Parobé 3"></div>
                                        </div>
                                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselCasa4" data-bs-slide="prev" style="width:32px;height:32px;top:50%;transform:translateY(-50%);">
                                            <span class="carousel-control-prev-icon" style="width:16px;height:16px;" aria-hidden="true"></span>
                                            <span class="visually-hidden">Anterior</span>
                                        </button>
                                        <button class="carousel-control-next" type="button" data-bs-target="#carouselCasa4" data-bs-slide="next" style="width:32px;height:32px;top:50%;transform:translateY(-50%);">
                                            <span class="carousel-control-next-icon" style="width:16px;height:16px;" aria-hidden="true"></span>
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
                                    <div id="carouselCasa5" class="carousel slide" data-bs-ride="carousel" style="width:100%;margin:auto;">
                                        <div class="carousel-inner" style="border-radius:10px;">
                                            <div class="carousel-item active"><img src="imgs/Casa5/Casa5.0.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Igrejinha 1"></div>
                                            <div class="carousel-item"><img src="imgs/Casa5/Casa5.1.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Igrejinha 2"></div>
                                            <div class="carousel-item"><img src="imgs/Casa5/Casa5.10.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Igrejinha 3"></div>
                                        </div>
                                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselCasa5" data-bs-slide="prev" style="width:32px;height:32px;top:50%;transform:translateY(-50%);">
                                            <span class="carousel-control-prev-icon" style="width:16px;height:16px;" aria-hidden="true"></span>
                                            <span class="visually-hidden">Anterior</span>
                                        </button>
                                        <button class="carousel-control-next" type="button" data-bs-target="#carouselCasa5" data-bs-slide="next" style="width:32px;height:32px;top:50%;transform:translateY(-50%);">
                                            <span class="carousel-control-next-icon" style="width:16px;height:16px;" aria-hidden="true"></span>
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
                                    <div id="carouselCasa6" class="carousel slide" data-bs-ride="carousel" style="width:100%;margin:auto;">
                                        <div class="carousel-inner" style="border-radius:10px;">
                                            <div class="carousel-item active"><img src="imgs/Casa6/Casa6.0.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Rolante 1"></div>
                                            <div class="carousel-item"><img src="imgs/Casa6/Casa6.1.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Rolante 2"></div>
                                            <div class="carousel-item"><img src="imgs/Casa6/Casa6.2.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Rolante 3"></div>
                                        </div>
                                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselCasa6" data-bs-slide="prev" style="width:32px;height:32px;top:50%;transform:translateY(-50%);">
                                            <span class="carousel-control-prev-icon" style="width:16px;height:16px;" aria-hidden="true"></span>
                                            <span class="visually-hidden">Anterior</span>
                                        </button>
                                        <button class="carousel-control-next" type="button" data-bs-target="#carouselCasa6" data-bs-slide="next" style="width:32px;height:32px;top:50%;transform:translateY(-50%);">
                                            <span class="carousel-control-next-icon" style="width:16px;height:16px;" aria-hidden="true"></span>
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
                <!-- Imóvel 7 -->
                <div class="col">
                    <div class="card property-card" style="border-radius:12px;box-shadow:0 4px 16px rgba(0,0,0,0.08);">
                        <div class="position-relative overflow-hidden">
                            <div id="carouselCasa7" class="carousel slide" data-bs-ride="carousel" style="width:100%;margin:auto;">
                                <div class="carousel-inner" style="border-radius:10px;">
                                    <div class="carousel-item active"><img src="imgs/Casa7/Casa7.0.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Sapiranga 1"></div>
                                    <div class="carousel-item"><img src="imgs/Casa7/Casa7.1.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Sapiranga 2"></div>
                                    <div class="carousel-item"><img src="imgs/Casa7/Casa7.2.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Sapiranga 3"></div>
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselCasa7" data-bs-slide="prev" style="width:32px;height:32px;top:50%;transform:translateY(-50%);">
                                    <span class="carousel-control-prev-icon" style="width:16px;height:16px;" aria-hidden="true"></span>
                                    <span class="visually-hidden">Anterior</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselCasa7" data-bs-slide="next" style="width:32px;height:32px;top:50%;transform:translateY(-50%);">
                                    <span class="carousel-control-next-icon" style="width:16px;height:16px;" aria-hidden="true"></span>
                                    <span class="visually-hidden">Próximo</span>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="property-title" style="font-size:1.1rem;font-weight:600;color:#333;">Casa em Sapiranga</h5>
                            <div class="property-price price-sale mb-2" style="font-size:1rem;font-weight:700;">R$ 210.000,00</div>
                            <div class="mb-2"><span class="badge bg-success rounded-pill">Disponível</span></div>
                            <div class="mb-2"><span class="badge bg-primary rounded-pill">Pedro Costa</span></div>
                            <a href="Casas/Casa7.php" class="btn btn-details w-100 btn-sm" style="background:linear-gradient(45deg,#ff7b00,#ff9500);border:none;color:white;">Ver Detalhes</a>
                            <div class="d-flex justify-content-between mt-3">
                                <button class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i> Editar</button>
                                <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i> Excluir</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Imóvel 8 -->
                <div class="col">
                    <div class="card property-card" style="border-radius:12px;box-shadow:0 4px 16px rgba(0,0,0,0.08);">
                        <div class="position-relative overflow-hidden">
                            <div id="carouselCasa8" class="carousel slide" data-bs-ride="carousel" style="width:100%;margin:auto;">
                                <div class="carousel-inner" style="border-radius:10px;">
                                    <div class="carousel-item active"><img src="imgs/Casa8/Casa8.0.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Nova Hartz 1"></div>
                                    <div class="carousel-item"><img src="imgs/Casa8/Casa8.1.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Nova Hartz 2"></div>
                                    <div class="carousel-item"><img src="imgs/Casa8/Casa8.2.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Nova Hartz 3"></div>
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselCasa8" data-bs-slide="prev" style="width:32px;height:32px;top:50%;transform:translateY(-50%);">
                                    <span class="carousel-control-prev-icon" style="width:16px;height:16px;" aria-hidden="true"></span>
                                    <span class="visually-hidden">Anterior</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselCasa8" data-bs-slide="next" style="width:32px;height:32px;top:50%;transform:translateY(-50%);">
                                    <span class="carousel-control-next-icon" style="width:16px;height:16px;" aria-hidden="true"></span>
                                    <span class="visually-hidden">Próximo</span>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="property-title" style="font-size:1.1rem;font-weight:600;color:#333;">Casa em Nova Hartz</h5>
                            <div class="property-price price-sale mb-2" style="font-size:1rem;font-weight:700;">R$ 195.000,00</div>
                            <div class="mb-2"><span class="badge bg-success rounded-pill">Disponível</span></div>
                            <div class="mb-2"><span class="badge bg-primary rounded-pill">Pedro Costa</span></div>
                            <a href="Casas/Casa8.php" class="btn btn-details w-100 btn-sm" style="background:linear-gradient(45deg,#ff7b00,#ff9500);border:none;color:white;">Ver Detalhes</a>
                            <div class="d-flex justify-content-between mt-3">
                                <button class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i> Editar</button>
                                <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i> Excluir</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Imóvel 9 -->
                <div class="col">
                    <div class="card property-card" style="border-radius:12px;box-shadow:0 4px 16px rgba(0,0,0,0.08);">
                        <div class="position-relative overflow-hidden">
                            <div id="carouselCasa9" class="carousel slide" data-bs-ride="carousel" style="width:100%;margin:auto;">
                                <div class="carousel-inner" style="border-radius:10px;">
                                    <div class="carousel-item active"><img src="imgs/Casa9/Casa9.0.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Campo Bom 1"></div>
                                    <div class="carousel-item"><img src="imgs/Casa9/Casa9.1.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Campo Bom 2"></div>
                                    <div class="carousel-item"><img src="imgs/Casa9/Casa9.2.jpg" class="d-block w-100 carousel-img-fixed" style="height:120px;object-fit:cover;" alt="Casa em Campo Bom 3"></div>
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselCasa9" data-bs-slide="prev" style="width:32px;height:32px;top:50%;transform:translateY(-50%);">
                                    <span class="carousel-control-prev-icon" style="width:16px;height:16px;" aria-hidden="true"></span>
                                    <span class="visually-hidden">Anterior</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselCasa9" data-bs-slide="next" style="width:32px;height:32px;top:50%;transform:translateY(-50%);">
                                    <span class="carousel-control-next-icon" style="width:16px;height:16px;" aria-hidden="true"></span>
                                    <span class="visually-hidden">Próximo</span>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="property-title" style="font-size:1.1rem;font-weight:600;color:#333;">Casa em Campo Bom</h5>
                            <div class="property-price price-sale mb-2" style="font-size:1rem;font-weight:700;">R$ 275.000,00</div>
                            <div class="mb-2"><span class="badge bg-success rounded-pill">Disponível</span></div>
                            <div class="mb-2"><span class="badge bg-primary rounded-pill">Pedro Costa</span></div>
                            <a href="Casas/Casa9.php" class="btn btn-details w-100 btn-sm" style="background:linear-gradient(45deg,#ff7b00,#ff9500);border:none;color:white;">Ver Detalhes</a>
                            <div class="d-flex justify-content-between mt-3">
                                <button class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i> Editar</button>
                                <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i> Excluir</button>
                            </div>
                        </div>
                    </div>
                </div>
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
