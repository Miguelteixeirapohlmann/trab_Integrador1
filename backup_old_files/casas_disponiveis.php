
   


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Casas Disponíveis</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
        <link href="css/styles.css" rel="stylesheet">
        <style>
            .carousel-img-fixed {
                width: 100%;
                height: 300px;
                object-fit: cover;
                object-position: center;
                border-radius: 0.5rem;
                box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.10);
            }
            .product-card {
                min-height: 600px;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
            }
            @media (max-width: 768px) {
                .carousel-img-fixed {
                    height: 180px;
                }
                .product-card {
                    min-height: 420px;
                }
            }
        </style>


 <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <div class="container">
            <a class="navbar-brand" href="index.php">Início</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php#Alugar">Alugar</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#comprar">Compra</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#services">Descobrir</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#Final">Ajuda</a></li>
                    <li class="nav-item"><a class="nav-link" href="agendar_visita.php">Agendar Visita</a></li>

                </ul>
            </div>
        </div>
    </nav>

</head>
<body>
     <section id="lancamentos" class="section">
        <div class="container mb-4">

        </div>
        <div class="container">
            <h2 class="section-title">Casas Disponiveis</h2>   

             
            <form class="row g-3" method="get" action="casas_disponiveis.php">
         
             <div class="col-auto d-flex align-items-center gap-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tipo" id="aluguel" value="aluguel" <?php if(isset($_GET['tipo']) && $_GET['tipo']==='aluguel') echo 'checked'; ?> >
                        <label class="form-check-label" for="aluguel">Aluguel</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tipo" id="venda" value="venda" <?php if(isset($_GET['tipo']) && $_GET['tipo']==='venda') echo 'checked'; ?> >
                        <label class="form-check-label" for="venda">Venda</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tipo" id="ambos" value="ambos" <?php if(isset($_GET['tipo']) && $_GET['tipo']==='ambos') echo 'checked'; ?> >
                        <label class="form-check-label" for="ambos">Ambos</label>
                    </div>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary mb-3">Pesquisar</button>
                </div>
            </form>

            <?php
            // Array de casas
            $casas = [
                [
                    'imgs' => ['imgs/Casa1/Casa1.1.jpg', 'imgs/Casa1/Casa1.0.jpg','imgs/Casa1/Casa1.2.jpg','imgs/Casa1/Casa1.3.jpg','imgs/Casa1/Casa1.4.jpg','imgs/Casa1/Casa1.5.jpg','imgs/Casa1/Casa1.6.jpg','imgs/Casa1/Casa1.7.jpg','imgs/Casa1/Casa1.8.jpg','imgs/Casa1/Casa1.9.jpg'],
                    'titulo' => 'Casa em Santo Antônio da Patrulha',
                    'venda' => 'R$ 5.200.000,00',
                    'aluguel' => 'R$ 1.000.000,00 ao Mês',
                    'link' => 'Casas/Casa1.php'
                ],
                [
                    'imgs' => ['imgs/Casa2/Casa2.0.jpg', 'imgs/Casa2/Casa2.1.jpg', 'imgs/Casa2/Casa2.2.jpg', 'imgs/Casa2/Casa2.3.jpg', 'imgs/Casa2/Casa2.4.jpg', 'imgs/Casa2/Casa2.5.jpg', 'imgs/Casa2/Casa2.6.jpg', 'imgs/Casa2/Casa2.7.jpg', 'imgs/Casa2/Casa2.8.jpg', 'imgs/Casa2/Casa2.9.jpg', 'imgs/Casa2/Casa2.10.jpg', 'imgs/Casa2/Casa2.11.jpg', 'imgs/Casa2/Casa2.12.jpg', 'imgs/Casa2/Casa2.13.jpg'],
                    'titulo' => 'Casa em Taquara',
                    'venda' => null,
                    'aluguel' => 'R$ 2.000,00 ao Mês',
                    'link' => 'Casas/Casa2.php'
                ],
                [
                    'imgs' => ['imgs/Casa3/Casa3.0.jpg', 'imgs/Casa3/Casa3.1.jpg', 'imgs/Casa3/Casa3.2.jpg', 'imgs/Casa3/Casa3.3.jpg', 'imgs/Casa3/Casa3.4.jpg', 'imgs/Casa3/Casa3.5.jpg', 'imgs/Casa3/Casa3.6.jpg', 'imgs/Casa3/Casa3.7.jpg', 'imgs/Casa3/Casa3.8.jpg', 'imgs/Casa3/Casa3.9.jpg'],
                    'titulo' => 'Casa Em Taquara Alto Padrão',
                    'venda' => 'R$ 3.000.000,00',
                    'aluguel' => null,
                    'link' => 'Casas/Casa3.php'
                ],
                [
                    'imgs' => ['imgs/Casa4/Casa4.4.jpg', 'imgs/Casa4/Casa4.2.jpg', 'imgs/Casa4/Casa4.0.jpg', 'imgs/Casa4/Casa4.3.jpg', 'imgs/Casa4/Casa4.5.jpg', 'imgs/Casa4/Casa4.6.jpg', 'imgs/Casa4/Casa4.7.jpg', 'imgs/Casa4/Casa4.8.jpg', 'imgs/Casa4/Casa4.9.jpg'],
                    'titulo' => 'Casa em Taquara Rua Mundo Novo',
                    'venda' => 'R$ 170.000,00',
                    'aluguel' => 'R$ 1.700,00 ao Mês',
                    'link' => 'Casas/Casa4.php'
                ],
                [
                    'imgs' => ['imgs/Casa5/Casa5.0.jpg', 'imgs/Casa5/Casa5.1.jpg', 'imgs/Casa5/Casa5.2.jpg', 'imgs/Casa5/Casa5.3.jpg', 'imgs/Casa5/Casa5.4.jpg', 'imgs/Casa5/Casa5.5.jpg', 'imgs/Casa5/Casa5.6.jpg', 'imgs/Casa5/Casa5.7.jpg', 'imgs/Casa5/Casa5.8.jpg', 'imgs/Casa5/Casa5.9.jpg', 'imgs/Casa5/Casa5.10.jpg'],
                    'titulo' => 'Casa em Taquara Flores da Cunha',
                    'venda' => 'R$ 380.000,00',
                    'aluguel' => 'R$ 1.750,00 ao Mês',
                    'link' => 'Casas/Casa5.php'
                ],
                [
                    'imgs' => ['imgs/Casa6/Casa6.0.jpg', 'imgs/Casa6/Casa6.1.jpg', 'imgs/Casa6/Casa6.2.jpg', 'imgs/Casa6/Casa6.3.jpg', 'imgs/Casa6/Casa6.4.jpg'],
                    'titulo' => 'Casa em Parobé',
                    'venda' => 'R$ 210.000,00',
                    'aluguel' => 'R$ 1.500,00 ao Mês',
                    'link' => 'Casas/Casa6.php'
                ],
                [
                    'imgs' => ['imgs/Casa7/Casa7.0.jpg', 'imgs/Casa7/Casa7.1.jpg', 'imgs/Casa7/Casa7.2.jpg', 'imgs/Casa7/Casa7.3.jpg', 'imgs/Casa7/Casa7.4.jpg'],
                    'titulo' => 'Casa em Taquara Santa Terezinha',
                    'venda' => 'R$ 650.000,00',
                    'aluguel' => 'R$ 1.500,00 ao Mês',
                    'link' => 'Casas/Casa7.php'
                ],
                [
                    'imgs' => ['imgs/Casa8/Casa8.0.jpg', 'imgs/Casa8/Casa8.1.jpg', 'imgs/Casa8/Casa8.2.jpg', 'imgs/Casa8/Casa8.3.jpg', 'imgs/Casa8/Casa8.4.jpg', 'imgs/Casa8/Casa8.5.jpg', 'imgs/Casa8/Casa8.6.jpg', 'imgs/Casa8/Casa8.7.jpg', 'imgs/Casa8/Casa8.8.jpg', 'imgs/Casa8/Casa8.9.jpg'],
                    'titulo' => 'Casa em Taquara rua Alvarino Lacerda Filho',
                    'venda' => 'R$ 184.900,00',
                    'aluguel' => null,
                    'link' => 'Casas/Casa8.php'
                ],
                [
                    'imgs' => ['imgs/casa9/Casa9.0.jpg', 'imgs/casa9/Casa9.1.jpg', 'imgs/casa9/Casa9.2.jpg', 'imgs/casa9/Casa9.3.jpg', 'imgs/casa9/Casa9.4.jpg'],
                    'titulo' => 'Casa em Taquara - São Francisco',
                    'venda' => 'R$ 450.000,00',
                    'aluguel' => null,
                    'link' => 'Casas/Casa9.php'
                ],
            ];

            $tipo = $_GET['tipo'] ?? 'ambos';
$casas_filtradas = array_filter($casas, function($casa) use ($tipo) {
    if ($tipo === 'aluguel') {
        return !empty($casa['aluguel']) && is_null($casa['venda']);
    } elseif ($tipo === 'venda') {
        return !empty($casa['venda']) && is_null($casa['aluguel']);
    } elseif ($tipo === 'ambos') {
        return !empty($casa['aluguel']) && !empty($casa['venda']);
    }
    return true;
});
            ?>
            <div class="row">
            <?php foreach ($casas_filtradas as $i => $casa): ?>
                <div class="col-md-4">
                    <div class="product-card">
                        <div class="product-image" style="width:100%; aspect-ratio:1/1; overflow:hidden;">
                            <div id="carouselCasa<?php echo $i; ?>" class="carousel slide" data-bs-interval="false">
                                <div class="carousel-inner">
                                    <?php foreach ($casa['imgs'] as $j => $img): ?>
                                        <div class="carousel-item<?php if ($j === 0) echo ' active'; ?>">
                                            <img src="<?php echo $img; ?>" alt="<?php echo htmlspecialchars($casa['titulo']); ?>" class="d-block w-100 carousel-img-fixed">
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <?php if (count($casa['imgs']) > 1): ?>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselCasa<?php echo $i; ?>" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Anterior</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselCasa<?php echo $i; ?>" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Próximo</span>
                                </button>
                                <?php endif; ?>
                            </div>
                        </div>
                        <h3 class="product-title"><?php echo htmlspecialchars($casa['titulo']); ?></h3>
                        <?php if ($tipo === 'aluguel' && !empty($casa['aluguel'])): ?>
                            <p class="product-price"><?php echo $casa['aluguel']; ?></p>
                        <?php elseif ($tipo === 'venda' && !empty($casa['venda'])): ?>
                            <p class="product-price"><?php echo $casa['venda']; ?></p>
                        <?php elseif ($tipo === 'ambos'): ?>
                            <?php if (!empty($casa['venda'])): ?><p class="product-price"><?php echo $casa['venda']; ?></p><?php endif; ?>
                            <?php if (!empty($casa['aluguel'])): ?><p class="product-price"><?php echo $casa['aluguel']; ?></p><?php endif; ?>
                        <?php endif; ?>
                        <a href="<?php echo $casa['link']; ?>" class="btn btn-custom">Ver detalhes</a>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
        </div>
    </section>
         <footer class="bg-light py-5">
        <div class="container px-4 px-lg-5"><div class="small text-center text-muted">Copyright &copy; 2025 - Company Miguel</div></div>
    </footer>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>
</html>