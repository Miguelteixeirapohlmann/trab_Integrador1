<?php
/**
 * Script de teste para verificar upload de imagens
 */

echo "<h2>Teste de Upload - Diagnóstico</h2>";

// Verificar PHP.ini settings
echo "<h3>Configurações PHP:</h3>";
echo "upload_max_filesize: " . ini_get('upload_max_filesize') . "<br>";
echo "post_max_size: " . ini_get('post_max_size') . "<br>";
echo "max_file_uploads: " . ini_get('max_file_uploads') . "<br>";
echo "file_uploads: " . (ini_get('file_uploads') ? 'Habilitado' : 'Desabilitado') . "<br>";

// Verificar diretórios
echo "<h3>Verificação de Diretórios:</h3>";

$casa_id = 1;
$image_dir = __DIR__ . "/imgs/Casa{$casa_id}/";

echo "Diretório: " . $image_dir . "<br>";
echo "Existe: " . (is_dir($image_dir) ? 'SIM' : 'NÃO') . "<br>";

if (!is_dir($image_dir)) {
    echo "Tentando criar diretório...<br>";
    if (mkdir($image_dir, 0777, true)) {
        echo "✓ Diretório criado com sucesso!<br>";
    } else {
        echo "✗ ERRO ao criar diretório<br>";
    }
}

if (is_dir($image_dir)) {
    echo "Permissão de escrita: " . (is_writable($image_dir) ? 'SIM' : 'NÃO') . "<br>";
    echo "Permissão de leitura: " . (is_readable($image_dir) ? 'SIM' : 'NÃO') . "<br>";
    
    // Listar arquivos existentes
    $files = scandir($image_dir);
    echo "Arquivos no diretório: " . count($files) . "<br>";
    echo "<ul>";
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            echo "<li>$file</li>";
        }
    }
    echo "</ul>";
}

// Formulário de teste
echo "<h3>Formulário de Teste:</h3>";
?>

<form method="POST" enctype="multipart/form-data" style="border: 1px solid #ccc; padding: 20px; background: #f9f9f9;">
    <div>
        <label>Selecione imagem(ns) para teste:</label><br>
        <input type="file" name="test_images[]" multiple accept="image/*">
    </div>
    <br>
    <button type="submit" name="test_upload" style="padding: 10px 20px; background: #007bff; color: white; border: none; cursor: pointer;">
        Testar Upload
    </button>
</form>

<?php
if (isset($_POST['test_upload'])) {
    echo "<h3>Resultado do Teste:</h3>";
    
    if (!isset($_FILES['test_images']) || empty($_FILES['test_images']['name'][0])) {
        echo "<p style='color: red;'>❌ Nenhum arquivo foi enviado!</p>";
        echo "<pre>";
        print_r($_FILES);
        echo "</pre>";
    } else {
        echo "<p style='color: green;'>✓ Arquivos recebidos!</p>";
        echo "<pre>";
        print_r($_FILES['test_images']);
        echo "</pre>";
        
        foreach ($_FILES['test_images']['tmp_name'] as $key => $tmp_name) {
            if ($_FILES['test_images']['error'][$key] === UPLOAD_ERR_OK) {
                $filename = basename($_FILES['test_images']['name'][$key]);
                $destination = $image_dir . "test_" . time() . "_" . $filename;
                
                echo "<p>Tentando salvar: $filename</p>";
                
                if (move_uploaded_file($tmp_name, $destination)) {
                    echo "<p style='color: green;'>✓ Upload bem-sucedido: $filename</p>";
                } else {
                    echo "<p style='color: red;'>✗ Falha no upload: $filename</p>";
                    echo "<p>Destino: $destination</p>";
                }
            } else {
                echo "<p style='color: red;'>✗ Erro no arquivo {$_FILES['test_images']['name'][$key]}: código " . $_FILES['test_images']['error'][$key] . "</p>";
            }
        }
    }
}
?>
