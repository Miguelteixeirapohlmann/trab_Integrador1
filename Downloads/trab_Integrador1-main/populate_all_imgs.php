<?php
// Popula o banco com todas as imagens das pastas imgs/CasaN
require_once __DIR__ . '/includes/init.php';
require_once __DIR__ . '/models/Property.php';

$propertyModel = new Property();

// Mapeamento: cada imóvel recebe as imagens da respectiva pasta CasaN
$property_ids = [1,2,3,4,5,6,7,8,9];
$base_dir = __DIR__ . '/imgs/';

foreach ($property_ids as $pid) {
    $folder = $base_dir . 'Casa' . $pid;
    if (!is_dir($folder)) continue;
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
echo "Imagens das pastas imgs/CasaN populadas no banco!\n";
