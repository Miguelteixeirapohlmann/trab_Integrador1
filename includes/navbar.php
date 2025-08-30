<?php
/**
 * Componente de Navegação que se adapta ao tipo de usuário
 */

function renderNavbar($current_user, $current_page = '') {
    $user_type = $current_user['user_type'] ?? 'user';
    $app_name = APP_NAME ?? 'Imobiliária';
    
    echo '<nav class="navbar navbar-expand-lg navbar-light fixed-top py-3" id="mainNav">';
    echo '<div class="container px-4 px-lg-5">';
    echo '<a class="navbar-brand" href="index.php">';
    echo '<i class="fas fa-home me-2"></i>' . htmlspecialchars($app_name);
    echo '</a>';
    
    echo '<button class="navbar-toggler navbar-toggler-right" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">';
    echo '<span class="navbar-toggler-icon"></span>';
    echo '</button>';
    
    echo '<div class="collapse navbar-collapse" id="navbarResponsive">';
    echo '<ul class="navbar-nav ms-auto my-2 my-lg-0">';
    
    // Menu baseado no tipo de usuário
    if ($user_type === 'broker') {
        // Menu para Corretores
        ?>
        <li class="nav-item">
            <a class="nav-link <?= $current_page === 'casas_disponiveis' ? 'active' : '' ?>" href="casas_disponiveis.php">
                <i class="fas fa-home me-1"></i>Casas Disponíveis
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $current_page === 'meus_imoveis' ? 'active' : '' ?>" href="meus_imoveis.php">
                <i class="fas fa-building me-1"></i>Meus Imóveis
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $current_page === 'agendamentos' ? 'active' : '' ?>" href="agendamentos.php">
                <i class="fas fa-calendar-check me-1"></i>Ver Agendamentos
            </a>
        </li>
        <?php
    } else {
        // Menu para Clientes
        ?>
        <li class="nav-item">
            <a class="nav-link <?= $current_page === 'casas_disponiveis' ? 'active' : '' ?>" href="casas_disponiveis.php">
                <i class="fas fa-home me-1"></i>Casas Disponíveis
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $current_page === 'alugar' ? 'active' : '' ?>" href="alugar.php">
                <i class="fas fa-key me-1"></i>Alugar
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $current_page === 'compra' ? 'active' : '' ?>" href="Compra.php">
                <i class="fas fa-shopping-cart me-1"></i>Comprar
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $current_page === 'agendar_visita' ? 'active' : '' ?>" href="agendar_visita.php">
                <i class="fas fa-calendar-alt me-1"></i>Agendar Visita
            </a>
        </li>
        <?php
    }
    ?>
    
    <li class="nav-item">
        <a class="nav-link" href="logout.php">
            <i class="fas fa-sign-out-alt me-1"></i>Sair
        </a>
    </li>
    
    <?php
    echo '</ul>';
    echo '</div>';
    echo '</div>';
    echo '</nav>';
}
?>
