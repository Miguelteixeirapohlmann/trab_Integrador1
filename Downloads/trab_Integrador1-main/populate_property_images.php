<?php
// Popula o banco com imagens para os imóveis cadastrados
require_once __DIR__ . '/includes/init.php';
require_once __DIR__ . '/models/Property.php';

$propertyModel = new Property();

// IDs dos imóveis cadastrados (ajuste conforme necessário)
$property_ids = [1, 2, 3]; // IDs dos imóveis criados pelo script anterior

// Imagens para cada imóvel (exemplo usando estrutura de pastas imgs/CasaN)
$imagens = [
    1 => [
        'imgs/Casa1/Casa1.1.jpg',
        'imgs/Casa1/Casa1.2.jpg',
        'imgs/Casa1/Casa1.3.jpg',
        'imgs/Casa1/Casa1.4.jpg',
        'imgs/Casa1/Casa1.5.jpg',
    ],
    2 => [
        'imgs/Casa2/Casa2.1.jpg',
        'imgs/Casa2/Casa2.2.jpg',
        'imgs/Casa2/Casa2.3.jpg',
        'imgs/Casa2/Casa2.4.jpg',
    ],
    3 => [
        'imgs/Casa3/Casa3.1.jpg',
        'imgs/Casa3/Casa3.2.jpg',
        'imgs/Casa3/Casa3.3.jpg',
        'imgs/Casa3/Casa3.4.jpg',
        'imgs/Casa3/Casa3.5.jpg',
        'imgs/Casa3/Casa3.6.jpg',
    ]
];

foreach ($property_ids as $pid) {
    if (!isset($imagens[$pid])) continue;
    foreach ($imagens[$pid] as $idx => $img) {
        $is_primary = ($idx === 0);
        $ok = $propertyModel->addImage($pid, $img, basename($img), $is_primary);
        if ($ok) {
            echo "Imagem adicionada ao imóvel $pid: $img\n";
        } else {
            echo "Erro ao adicionar imagem ao imóvel $pid: $img\n";
        }
    }
}
echo "Imagens populadas!\n";
