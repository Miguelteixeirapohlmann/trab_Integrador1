<?php
// Associa imagens das pastas imgs/CasaN aos imóveis existentes no banco
require_once __DIR__ . '/includes/init.php';
require_once __DIR__ . '/models/Property.php';

$propertyModel = new Property();

// Busca todos os imóveis existentes
$stmt = $pdo->query("SELECT id, title FROM properties ORDER BY id ASC");
$properties = $stmt->fetchAll();

$base_dir = __DIR__ . '/imgs/';
foreach ($properties as $property) {
    $pid = $property['id'];
    $folder = $base_dir . 'Casa' . $pid;
    if (!is_dir($folder)) {
        echo "Pasta não encontrada para imóvel $pid: $folder\n";
        continue;
    }
    $files = scandir($folder);
    $img_files = array_filter($files, function($f) {
        return preg_match('/\.(jpg|jpeg|png|webp)$/i', $f);
    });
    $count = 0;
    foreach ($img_files as $img) {
        $img_path = 'imgs/Casa' . $pid . '/' . $img;
        $is_primary = ($count === 0);
        $ok = $propertyModel->addImage($pid, $img_path, $img, $is_primary);
        if ($ok) {
            echo "Imagem adicionada ao imóvel $pid: $img_path\n";
        } else {
            echo "Erro ao adicionar imagem ao imóvel $pid: $img_path\n";
        }
        $count++;
    }
}
echo "Imagens das pastas imgs/CasaN associadas aos imóveis existentes!\n";
