<?php
/**
 * Componente para exibir informações do usuário logado
 */

function renderUserInfo($current_user) {
    if (!$current_user) return '';
    
    $user_name = htmlspecialchars($current_user['first_name'] . ' ' . $current_user['last_name']);
    $user_email = htmlspecialchars($current_user['email']);
    $user_type_label = [
        'user' => 'Cliente',
        'broker' => 'Corretor',
        'admin' => 'Administrador'
    ][$current_user['user_type']] ?? 'Usuário';
    
    echo <<<HTML
    <div id="userInfoBar" class="alert alert-success alert-dismissible fade show user-info-bar" role="alert" 
         style="margin-bottom: 0; border-radius: 0; position: fixed; top: 72px; left: 0; right: 0; z-index: 1025;
                transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out;">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-user-check me-2"></i>
                    <strong>Bem-vindo(a), 
                        <div class="dropdown d-inline">
                            <a class="text-decoration-none text-dark fw-bold dropdown-toggle" href="#" role="button" 
                               id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false" 
                               style="cursor: pointer; border: none; background: transparent;">
                                {$user_name}
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="userDropdown">
                                <li>
                                    <a class="dropdown-item" href="perfil.php">
                                        <i class="fas fa-user me-2"></i>Meu Perfil
                                    </a>
                                </li>
HTML;
    
    // Menu específico para corretor
    if ($current_user['user_type'] === 'broker') {
        echo <<<HTML
                                <li>
                                    <a class="dropdown-item" href="meus_imoveis.php">
                                        <i class="fas fa-building me-2"></i>Meus Imóveis
                                    </a>
                                </li>
HTML;
    }
    
    echo <<<HTML
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="logout.php">
                                        <i class="fas fa-sign-out-alt me-2"></i>Sair
                                    </a>
                                </li>
                            </ul>
                        </div>
                    !</strong> 
                    <span class="badge bg-primary ms-2">{$user_type_label}</span>
                    <small class="text-muted ms-2">({$user_email})</small>
                </div>
                <div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const body = document.body;
            const userInfoBar = document.getElementById('userInfoBar');
            let lastScrollTop = 0;
            let isUserInfoVisible = true;
            
            // Adicionar classe ao body para ajustar layout
            body.classList.add('user-info-visible');
            
            // Função para controlar a visibilidade da barra baseado no scroll
            function handleScroll() {
                if (!userInfoBar || !isUserInfoVisible) return;
                
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                
                if (scrollTop > 100) { // Após rolar 100px para baixo
                    if (scrollTop > lastScrollTop) {
                        // Rolando para baixo - ocultar barra
                        userInfoBar.style.transform = 'translateY(-100%)';
                        userInfoBar.style.opacity = '0';
                        body.classList.remove('user-info-visible');
                    } else {
                        // Rolando para cima - mostrar barra
                        userInfoBar.style.transform = 'translateY(0)';
                        userInfoBar.style.opacity = '1';
                        body.classList.add('user-info-visible');
                    }
                } else if (scrollTop <= 50) {
                    // No topo da página - sempre mostrar
                    userInfoBar.style.transform = 'translateY(0)';
                    userInfoBar.style.opacity = '1';
                    body.classList.add('user-info-visible');
                }
                
                lastScrollTop = scrollTop;
            }
            
            // Adicionar listener de scroll com throttle para melhor performance
            let ticking = false;
            function onScroll() {
                if (!ticking) {
                    requestAnimationFrame(function() {
                        handleScroll();
                        ticking = false;
                    });
                    ticking = true;
                }
            }
            
            window.addEventListener('scroll', onScroll);
            
            // Quando o alert for fechado manualmente, remover a classe e o listener
            const closeBtn = userInfoBar.querySelector('.btn-close');
            if (closeBtn) {
                closeBtn.addEventListener('click', function() {
                    isUserInfoVisible = false;
                    body.classList.remove('user-info-visible');
                    window.removeEventListener('scroll', onScroll);
                });
            }
        });
    </script>
HTML;
}
?>
