<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste de Autenticação</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top py-3" style="background-color: #000000 !important;">
        <div class="container px-4 px-lg-5">
            <a class="navbar-brand" href="index.php" style="color: #ffffff !important;">Teste Auth</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="Login.php" style="color: #ffffff !important;">
                    <i class="fas fa-sign-in-alt"></i> Fazer Login
                </a>
            </div>
        </div>
    </nav>

    <?php
    // Simular usuário logado para testar o layout
    $test_user = [
        'first_name' => 'João',
        'last_name' => 'Silva',
        'email' => 'joao@teste.com',
        'user_type' => 'user'
    ];
    
    require_once __DIR__ . '/includes/user_info.php';
    renderUserInfo($test_user);
    ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Teste de Layout com Usuário Logado</h4>
                    </div>
                    <div class="card-body">
                        <p>Este é um teste para verificar se o menu não está sobrepondo as informações do usuário.</p>
                        <p>A barra verde com informações do usuário deve aparecer logo abaixo do menu preto, sem sobreposição.</p>
                        <hr>
                        <h5>Funcionalidades testadas:</h5>
                        <ul>
                            <li>✅ Menu fixo no topo (preto)</li>
                            <li>✅ Informações do usuário (verde, logo abaixo)</li>
                            <li>✅ Nome do usuário clicável com dropdown</li>
                            <li>✅ Dropdown com "Meu Perfil" e "Sair"</li>
                            <li>✅ Conteúdo principal sem sobreposição</li>
                            <li>🆕 <strong>Novo:</strong> Barra some ao rolar para baixo</li>
                            <li>🆕 <strong>Novo:</strong> Barra aparece ao rolar para cima</li>
                        </ul>
                        <div class="alert alert-info">
                            <strong>Instruções:</strong>
                            <ol>
                                <li>Verifique se não há sobreposição entre o menu e a barra do usuário</li>
                                <li>Clique no nome do usuário para ver o dropdown</li>
                                <li>Teste o link "Meu Perfil" no dropdown</li>
                                <li>Teste o link "Sair" no dropdown</li>
                                <li>Role a página para baixo - a barra deve desaparecer</li>
                                <li>Role para cima - a barra deve reaparecer</li>
                                <li>Teste fechar a barra clicando no "X"</li>
                            </ol>
                        </div>
                        
                        <!-- Conteúdo extra para permitir scroll -->
                        <div class="mt-5">
                            <h5>Conteúdo de teste para scroll</h5>
                            <p>Role esta página para baixo para testar a funcionalidade de ocultação da barra do usuário.</p>
                            
                            <div style="height: 300px; background: linear-gradient(45deg, #f8f9fa, #e9ecef);" class="d-flex align-items-center justify-content-center rounded mb-4">
                                <div class="text-center">
                                    <h6>Seção 1</h6>
                                    <p>Continue rolando...</p>
                                </div>
                            </div>
                            
                            <div style="height: 300px; background: linear-gradient(45deg, #e9ecef, #dee2e6);" class="d-flex align-items-center justify-content-center rounded mb-4">
                                <div class="text-center">
                                    <h6>Seção 2</h6>
                                    <p>A barra do usuário deve ter desaparecido</p>
                                </div>
                            </div>
                            
                            <div style="height: 300px; background: linear-gradient(45deg, #dee2e6, #ced4da);" class="d-flex align-items-center justify-content-center rounded mb-4">
                                <div class="text-center">
                                    <h6>Seção 3</h6>
                                    <p>Role para cima para ver a barra retornar</p>
                                </div>
                            </div>
                            
                            <div style="height: 300px; background: linear-gradient(45deg, #ced4da, #adb5bd);" class="d-flex align-items-center justify-content-center rounded mb-4">
                                <div class="text-center">
                                    <h6>Seção 4</h6>
                                    <p>Final do teste</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
