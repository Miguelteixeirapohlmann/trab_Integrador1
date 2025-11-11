<?php
/**
 * Componente de Navegação que se adapta ao tipo de usuário
 */

function renderNavbar($current_user, $current_page = '', $path_prefix = '') {
    $user_type = $current_user['user_type'] ?? 'user';
    $app_name = APP_NAME ?? 'Imobiliária';
    
    // Para admin, usar navbar escura com texto branco
    if ($user_type === 'admin') {
        // Navbar fixa no topo também para admin
        echo '<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top py-3" id="mainNav">';
    } else {
        echo '<nav class="navbar navbar-expand-lg navbar-light fixed-top py-3" id="mainNav">';
    }
    echo '<div class="container px-4 px-lg-5">';
    
    // Link do logo baseado no tipo de usuário
    if ($user_type === 'broker') {
        $home_link = $path_prefix . 'index-corretor.php';
        $home_text = 'Tela Inicial';
    } elseif ($user_type === 'admin') {
        $home_link = $path_prefix . 'admin.php';
        $home_text = 'Tela Inicial';
    } else {
        $home_link = $path_prefix . 'index.php';  
        $home_text = 'Tela Inicial';
    }
    
    $brand_class = ($user_type === 'admin') ? 'navbar-brand text-white' : 'navbar-brand';
    echo '<a class="' . $brand_class . '" href="' . $home_link . '">';
    echo '<i class="fas fa-home me-2"></i>' . htmlspecialchars($home_text);
    echo '</a>';
    
    echo '<button class="navbar-toggler navbar-toggler-right" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">';
    echo '<span class="navbar-toggler-icon"></span>';
    echo '</button>';
    
    echo '<div class="collapse navbar-collapse" id="navbarResponsive">';
    echo '<ul class="navbar-nav ms-auto my-2 my-lg-0">';
    
    // Define classe para links baseado no tipo de usuário
    $nav_link_class = ($user_type === 'admin') ? 'nav-link text-white' : 'nav-link';
    
    // Menu baseado no tipo de usuário
    if ($user_type === 'broker') {
        // Menu para Corretores
        ?>
        <li class="nav-item">
            <a class="<?= $nav_link_class ?> <?= $current_page === 'gerenciar_imoveis' ? 'active' : '' ?>" href="<?= $path_prefix ?>gerenciar_imoveis.php">
                <i class="fas fa-home me-1"></i>Início
            </a>
        </li>
        <li class="nav-item">
            <a class="<?= $nav_link_class ?> <?= $current_page === 'casas_disponiveis' ? 'active' : '' ?>" href="<?= $path_prefix ?>casas_disponiveis.php">
                <i class="fas fa-search me-1"></i>Casas Disponíveis
            </a>
        </li>
        <li class="nav-item">
            <a class="<?= $nav_link_class ?> <?= $current_page === 'agendamentos' ? 'active' : '' ?>" href="<?= $path_prefix ?>agendamentos.php">
                <i class="fas fa-calendar-check me-1"></i>Ver Agendamentos
            </a>
        </li>
        <?php
    } else {
        // Menu para Clientes e Admin
        if ($user_type === 'admin') {
            // Menu simplificado para Admin
            ?>
            <li class="nav-item">
                <a class="<?= $nav_link_class ?> <?= $current_page === 'perfil' ? 'active' : '' ?>" href="<?= $path_prefix ?>perfil.php">
                    <i class="fas fa-user me-1"></i>Meu Perfil
                </a>
            </li>
            <?php
        } else {
            // Menu completo para Clientes
            ?>
            <li class="nav-item">
                <a class="<?= $nav_link_class ?> <?= $current_page === 'casas_disponiveis' ? 'active' : '' ?>" href="<?= $path_prefix ?>casas_disponiveis.php">
                    <i class="fas fa-search me-1"></i>Casas Disponíveis
                </a>
            </li>
            <li class="nav-item">
                <a class="<?= $nav_link_class ?> <?= $current_page === 'alugar' ? 'active' : '' ?>" href="<?= $path_prefix ?>alugar.php">
                    <i class="fas fa-key me-1"></i>Alugar
                </a>
            </li>
            <li class="nav-item">
                <a class="<?= $nav_link_class ?> <?= $current_page === 'compra' ? 'active' : '' ?>" href="<?= $path_prefix ?>Compra.php">
                    <i class="fas fa-shopping-cart me-1"></i>Comprar
                </a>
            </li>
            <li class="nav-item">
                <a class="<?= $nav_link_class ?> <?= $current_page === 'agendar_visita' ? 'active' : '' ?>" href="<?= $path_prefix ?>agendar_visita.php">
                    <i class="fas fa-calendar-alt me-1"></i>Agendar Visita
                </a>
            </li>
            <?php
        }
    }
    ?>
    
    <li class="nav-item">
        <a class="<?= $nav_link_class ?>" href="<?= $path_prefix ?>logout.php">
            <i class="fas fa-sign-out-alt me-1"></i>Sair
        </a>
    </li>
    
    <?php
    echo '</ul>';
    echo '</div>';
    echo '</div>';
    echo '</nav>';
    // Garantir ajuste de espaçamento do body para navbar fixa (sem duplicar em múltiplas chamadas)
    echo '<script>if(!document.body.classList.contains("has-fixed-navbar")){document.body.classList.add("has-fixed-navbar");}</script>';
}
?>
