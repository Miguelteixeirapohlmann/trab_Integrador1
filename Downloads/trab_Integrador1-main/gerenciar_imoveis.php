<?php
// Página para listar todos os imóveis de um corretor
require_once __DIR__ . '/includes/init.php';
if (!$auth->isLoggedIn() || !$auth->hasRole('admin')) {
    header('Location: Login.php');
    exit;
}
$broker_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$broker_id) {
    echo '<div class="container mt-5"><div class="alert alert-danger">Corretor não especificado.</div></div>';
    exit;
}
$stmt = $pdo->prepare('SELECT u.first_name, u.last_name FROM brokers b LEFT JOIN users u ON b.user_id = u.id WHERE b.id = ?');
$stmt->execute([$broker_id]);
$broker = $stmt->fetch();
if (!$broker || !is_array($broker)) {
    // Tenta buscar pelo user_id se não encontrar pelo broker_id
    $stmt = $pdo->prepare('SELECT first_name, last_name FROM users WHERE id = ?');
    $stmt->execute([$broker_id]);
    $broker = $stmt->fetch();
}
$stmt2 = $pdo->prepare('SELECT * FROM properties WHERE broker_id = ?');
$stmt2->execute([$broker_id]);
$imoveis = $stmt2->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Imóveis do Corretor</title>
    <link href="css/styles.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Imóveis de <?php
    if ($broker && is_array($broker)) {
        echo htmlspecialchars($broker['first_name'] . ' ' . $broker['last_name']);
    } else {
        echo 'Corretor não encontrado';
    }
    ?></h2>
    <?php
    // Imóveis dinâmicos
    $tem_imovel = false;
    if ($imoveis && count($imoveis) > 0) {
        echo '<div class="row">';
        foreach ($imoveis as $imovel) {
            echo '<div class="col-md-6 col-lg-4 mb-4">';
            echo '<div class="card shadow">';
            echo '<div class="card-header bg-info text-white">Imóvel ID: ' . intval($imovel['id']) . '</div>';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">' . htmlspecialchars($imovel['title']) . '</h5>';
            echo '<p class="card-text">' . htmlspecialchars($imovel['description']) . '</p>';
            echo '<p><strong>Valor:</strong> R$ ' . number_format($imovel['price'], 2, ',', '.') . '</p>';
            echo '<a href="properties/view.php?id=' . $imovel['id'] . '" class="btn btn-primary btn-sm">Ver detalhes</a>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
        $tem_imovel = true;
    }

    // Imóveis estáticos (Casa1–Casa9)
    $static_casas = [
        ['id'=>1,'title'=>'Casa em Santo Antônio da Patrulha','corretor'=>'Maria Santos'],
        ['id'=>2,'title'=>'Casa em Taquara','corretor'=>'João Silva'],
        ['id'=>3,'title'=>'Casa em Taquara Alto Padrão','corretor'=>'Pedro Costa'],
        ['id'=>4,'title'=>'Casa em Taquara Rua Mundo Novo','corretor'=>'Maria Santos'],
        ['id'=>5,'title'=>'Casa em Taquara Rua Flores da Cunha','corretor'=>'João Silva'],
        ['id'=>6,'title'=>'Casa em Parobé','corretor'=>'Pedro Costa'],
        ['id'=>7,'title'=>'Casa em Taquara Bairro Santa Terezinha','corretor'=>'Maria Santos'],
        ['id'=>8,'title'=>'Casa em Taquara Bairro Tucanos','corretor'=>'João Silva'],
        ['id'=>9,'title'=>'Casa em Taquara - Rua São Francisco','corretor'=>'Pedro Costa'],
    ];
    $broker_name = $broker ? trim($broker['first_name'] . ' ' . $broker['last_name']) : '';
    $casas_do_corretor = array_filter($static_casas, function($casa) use ($broker_name) {
        return strtolower($casa['corretor']) === strtolower($broker_name);
    });
    if ($casas_do_corretor) {
        echo '<div class="row">';
        foreach ($casas_do_corretor as $casa) {
            echo '<div class="col-md-6 col-lg-4 mb-4">';
            echo '<div class="card shadow">';
            echo '<div class="card-header bg-info text-white">Imóvel ID: ' . intval($casa['id']) . '</div>';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">' . htmlspecialchars($casa['title']) . '</h5>';
            echo '<a href="Casas/Casa' . intval($casa['id']) . '.php" class="btn btn-primary btn-sm">Ver detalhes</a>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
        $tem_imovel = true;
    }

    if (!$tem_imovel) {
        echo '<div class="alert alert-warning">Nenhum imóvel cadastrado para este corretor.</div>';
    }
    ?>
    <a href="admin.php" class="btn btn-secondary mt-3">Voltar para Administração</a>
</div>
</body>
</html>
