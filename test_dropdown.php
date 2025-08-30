<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste Dropdown Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top py-3" style="background-color: #000000 !important;">
        <div class="container px-4 px-lg-5">
            <a class="navbar-brand" href="index.php" style="color: #ffffff !important;">Teste Dropdown</a>
        </div>
    </nav>

    <?php
    // Simular usuário logado para testar o dropdown
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
                        <h4>Teste do Dropdown do Usuário</h4>
                    </div>
                    <div class="card-body">
                        <p>Este teste verifica se o dropdown do nome do usuário está funcionando corretamente.</p>
                        <hr>
                        <h5>Como testar:</h5>
                        <ol>
                            <li>Clique no nome "João Silva" na barra verde acima</li>
                            <li>Deve aparecer um dropdown com duas opções:</li>
                            <ul>
                                <li>"Meu Perfil" - vai para perfil.php</li>
                                <li>"Sair" - vai para logout.php</li>
                            </ul>
                            <li>O dropdown deve ter ícones ao lado de cada opção</li>
                            <li>O link "Sair" deve aparecer em vermelho</li>
                        </ol>
                        
                        <div class="alert alert-info mt-3">
                            <strong>Observação:</strong> Esta é apenas uma demonstração visual. 
                            Os links funcionarão quando você estiver logado no sistema real.
                        </div>
                        
                        <div class="mt-4">
                            <h6>Funcionalidades implementadas:</h6>
                            <ul>
                                <li>✅ Nome do usuário clicável</li>
                                <li>✅ Dropdown Bootstrap</li>
                                <li>✅ Link para "Meu Perfil"</li>
                                <li>✅ Link para "Sair" (em vermelho)</li>
                                <li>✅ Ícones Font Awesome</li>
                                <li>✅ Divider entre opções</li>
                                <li>✅ Removido botão "Perfil" da barra</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
