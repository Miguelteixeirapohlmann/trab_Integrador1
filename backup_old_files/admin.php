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
                    <label for="corretorNome" class="form-label">Nome</label>
                    <input type="text" class="form-control" id="corretorNome" required>
                  </div>
                  <div class="mb-3">
                    <label for="corretorEmail" class="form-label">Email</label>
                    <input type="email" class="form-control" id="corretorEmail" required>
                  </div>
                  <div class="mb-3">
                    <label for="corretorStatus" class="form-label">Status</label>
                    <select class="form-select" id="corretorStatus">
                      <option value="true">Ativo</option>
                      <option value="false">Desabilitado</option>
                    </select>
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
        <h2 class="mb-4">Gerenciar Imóveis</h2>
        <div class="card">
            <div class="card-header">Todos os Imóveis</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped mb-0" id="imoveisTable">
                        <thead class="table-dark">
                            <tr>
                                <th>Imagem</th>
                                <th>Nome</th>
                                <th>Corretor</th>
                                <th>Preço</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><img src="imgs/Foto9.jpg" alt="Casa Realengo" class="img-fluid product-img-preview"></td>
                                <td>Casa Realengo</td>
                                <td>Corretor</td>
                                <td>R$ 250.000,00</td>
                                <td>Disponível</td>
                                <td><a href="Casas/Casa1.php" class="btn btn-sm btn-primary">Ver detalhes</a></td>
                            </tr>
                            <tr>
                                <td><img src="imgs/foto8.jpg" alt="Casa Alto Rolantinho" class="img-fluid product-img-preview"></td>
                                <td>Casa Alto Rolantinho</td>
                                <td>Corretor</td>
                                <td>R$ 180.000,00</td>
                                <td>Disponível</td>
                                <td><a href="Casas/Casa2.php" class="btn btn-sm btn-primary">Ver detalhes</a></td>
                            </tr>
                            <tr>
                                <td><img src="imgs/foto7.jpg" alt="Casa Alpha Ville" class="img-fluid product-img-preview"></td>
                                <td>Casa Alpha Ville</td>
                                <td>Corretor</td>
                                <td>R$ 150.000,00</td>
                                <td>Disponível</td>
                                <td><a href="Casas/Casa3.php" class="btn btn-sm btn-primary">Ver detalhes</a></td>
                            </tr>
                            <tr>
                                <td><img src="imgs/foto1.jpeg" alt="Casa Jardim das Flores" class="img-fluid product-img-preview"></td>
                                <td>Casa Jardim das Flores</td>
                                <td>Corretor</td>
                                <td>R$ 220.000,00</td>
                                <td>Disponível</td>
                                <td><a href="Casas/Casa4.php" class="btn btn-sm btn-primary">Ver detalhes</a></td>
                            </tr>
                            <tr>
                                <td><img src="imgs/foto2.jpg" alt="Casa Vista Alegre" class="img-fluid product-img-preview"></td>
                                <td>Casa Vista Alegre</td>
                                <td>Corretor</td>
                                <td>R$ 310.000,00</td>
                                <td>Disponível</td>
                                <td><a href="Casas/Casa5.php" class="btn btn-sm btn-primary">Ver detalhes</a></td>
                            </tr>
                            <tr>
                                <td><img src="imgs/foto3.jpg" alt="Casa Solar dos Pássaros" class="img-fluid product-img-preview"></td>
                                <td>Casa Solar dos Pássaros</td>
                                <td>Corretor</td>
                                <td>R$ 275.000,00</td>
                                <td>Disponível</td>
                                <td><a href="Casas/Casa6.php" class="btn btn-sm btn-primary">Ver detalhes</a></td>
                            </tr>
                            <tr>
                                <td><img src="imgs/foto4.jpg" alt="Casa Bela Vista" class="img-fluid product-img-preview"></td>
                                <td>Casa Bela Vista</td>
                                <td>Corretor</td>
                                <td>R$ 199.000,00</td>
                                <td>Disponível</td>
                                <td><a href="Casas/Casa7.php" class="btn btn-sm btn-primary">Ver detalhes</a></td>
                            </tr>
                            <tr>
                                <td><img src="imgs/foto5.jpg" alt="Casa do Gabriel" class="img-fluid product-img-preview"></td>
                                <td>Casa do Gabriel</td>
                                <td>Corretor</td>
                                <td>R$ 1,00</td>
                                <td>Disponível</td>
                                <td><a href="Casas/Casa8.php" class="btn btn-sm btn-primary">Ver detalhes</a></td>
                            </tr>
                            <tr>
                                <td><img src="imgs/casa6.jpg" alt="Casa Nova Esperança" class="img-fluid product-img-preview"></td>
                                <td>Casa Nova Esperança</td>
                                <td>Corretor</td>
                                <td>R$ 185.000,00</td>
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
