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
    <nav class="navbar navbar-expand-lg fixed-top bg-dark">
        <div class="container">

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAdmin">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAdmin">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link text-white" href="index.php">Loja</a></li>
                </ul>
                <span class="navbar-text text-white">Painel Corretor</span>
            </div>
        </div>
    </nav>

    <section class="admin-section">
        <div class="container">
            <h2 class="mb-4">Gerenciar Produtos</h2>
            <!-- Formulário para adicionar produto -->
            <div class="card mb-4">
                <div class="card-header">Adicionar Produto</div>
                <div class="card-body">
                    <form id="addProductForm" class="row g-3">
                        <div class="col-md-4">
                            <label for="productName" class="form-label">Nome do Produto</label>
                            <input type="text" class="form-control" id="productName" required>
                        </div>
                        <div class="col-md-2">
                            <label for="productType" class="form-label">Tipo</label>
                            <select class="form-select" id="productType" required>
                                <option value="casas">casa</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="productPrice" class="form-label">Preço (R$)</label>
                            <input type="number" class="form-control" id="productPrice" min="0" step="0.01" required>
                        </div>
                        <div class="col-md-2">
                            <label for="productImage" class="form-label">Imagem</label>
                            <input type="file" class="form-control" id="productImage" accept="image/*" required>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-success w-100"><i class="fas fa-plus"></i> Adicionar</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Tabela de produtos -->
            <div class="card">
                <div class="card-header">Produtos Cadastrados</div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0" id="productsTable">
                            <thead class="table-dark">
                                <tr>
                                    <th>Imagem</th>
                                    <th>Nome</th>
                                    <th>Tipo</th>
                                    <th>Preço</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Produtos serão inseridos aqui via JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
   
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>