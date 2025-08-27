<?php
/**
 * Funções auxiliares do sistema
 */

/**
 * Função para incluir e renderizar views
 */
function renderView($view, $data = [], $layout = 'main') {
    // Extrair variáveis para a view
    extract($data);
    
    // Iniciar buffer de saída
    ob_start();
    
    // Incluir a view
    include __DIR__ . "/../views/{$view}.php";
    
    // Capturar conteúdo
    $content = ob_get_clean();
    
    // Se layout for false, retornar apenas o conteúdo
    if ($layout === false) {
        return $content;
    }
    
    // Incluir layout
    include __DIR__ . "/../views/layouts/{$layout}.php";
}

/**
 * Função para sanitizar dados de entrada
 */
function sanitize($data) {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

/**
 * Validar email
 */
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validar CPF
 */
function validateCPF($cpf) {
    $cpf = preg_replace('/[^0-9]/', '', $cpf);
    
    if (strlen($cpf) != 11) {
        return false;
    }
    
    // Verificar se todos os dígitos são iguais
    if (preg_match('/(\d)\1{10}/', $cpf)) {
        return false;
    }
    
    // Validar dígitos verificadores
    for ($t = 9; $t < 11; $t++) {
        for ($d = 0, $c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) {
            return false;
        }
    }
    
    return true;
}

/**
 * Validar telefone brasileiro
 */
function validatePhone($phone) {
    $phone = preg_replace('/[^0-9]/', '', $phone);
    return preg_match('/^[1-9]{2}9?[0-9]{8}$/', $phone);
}

/**
 * Formatar moeda brasileira
 */
function formatCurrency($value) {
    return 'R$ ' . number_format($value, 2, ',', '.');
}

/**
 * Formatar CPF
 */
function formatCPF($cpf) {
    $cpf = preg_replace('/[^0-9]/', '', $cpf);
    return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $cpf);
}

/**
 * Formatar telefone
 */
function formatPhone($phone) {
    $phone = preg_replace('/[^0-9]/', '', $phone);
    if (strlen($phone) == 11) {
        return preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $phone);
    } elseif (strlen($phone) == 10) {
        return preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $phone);
    }
    return $phone;
}

/**
 * Formatar data brasileira
 */
function formatDate($date, $format = 'd/m/Y') {
    if (!$date) return '';
    return date($format, strtotime($date));
}

/**
 * Gerar slug para URLs
 */
function generateSlug($string) {
    $string = strtolower($string);
    $string = preg_replace('/[àáâãäå]/', 'a', $string);
    $string = preg_replace('/[èéêë]/', 'e', $string);
    $string = preg_replace('/[ìíîï]/', 'i', $string);
    $string = preg_replace('/[òóôõö]/', 'o', $string);
    $string = preg_replace('/[ùúûü]/', 'u', $string);
    $string = preg_replace('/[ç]/', 'c', $string);
    $string = preg_replace('/[^a-z0-9\s-]/', '', $string);
    $string = preg_replace('/[\s-]+/', '-', $string);
    return trim($string, '-');
}

/**
 * Upload de arquivos
 */
function uploadFile($file, $upload_dir = 'uploads/', $allowed_types = ['jpg', 'jpeg', 'png', 'webp']) {
    if (!isset($file['error']) || is_array($file['error'])) {
        return ['success' => false, 'message' => 'Erro no arquivo enviado.'];
    }

    switch ($file['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            return ['success' => false, 'message' => 'Nenhum arquivo foi enviado.'];
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            return ['success' => false, 'message' => 'Arquivo muito grande.'];
        default:
            return ['success' => false, 'message' => 'Erro desconhecido.'];
    }

    if ($file['size'] > MAX_FILE_SIZE) {
        return ['success' => false, 'message' => 'Arquivo muito grande.'];
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    $ext = array_search(
        $mime,
        [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'webp' => 'image/webp'
        ],
        true
    );

    if (!$ext || !in_array($ext, $allowed_types)) {
        return ['success' => false, 'message' => 'Tipo de arquivo não permitido.'];
    }

    $filename = sprintf('%s_%s.%s',
        uniqid(),
        date('YmdHis'),
        $ext
    );

    $upload_path = UPLOAD_PATH . $upload_dir;
    if (!is_dir($upload_path)) {
        mkdir($upload_path, 0755, true);
    }

    $full_path = $upload_path . $filename;

    if (!move_uploaded_file($file['tmp_name'], $full_path)) {
        return ['success' => false, 'message' => 'Falha ao mover arquivo.'];
    }

    return [
        'success' => true,
        'filename' => $filename,
        'path' => $upload_dir . $filename,
        'full_path' => $full_path
    ];
}

/**
 * Redimensionar imagem
 */
function resizeImage($source, $destination, $max_width = 800, $max_height = 600, $quality = 80) {
    $info = getimagesize($source);
    if (!$info) {
        return false;
    }

    list($width, $height, $type) = $info;

    // Calcular novas dimensões
    $ratio = min($max_width / $width, $max_height / $height);
    $new_width = intval($width * $ratio);
    $new_height = intval($height * $ratio);

    // Criar imagem a partir do arquivo
    switch ($type) {
        case IMAGETYPE_JPEG:
            $source_image = imagecreatefromjpeg($source);
            break;
        case IMAGETYPE_PNG:
            $source_image = imagecreatefrompng($source);
            break;
        case IMAGETYPE_WEBP:
            $source_image = imagecreatefromwebp($source);
            break;
        default:
            return false;
    }

    // Criar nova imagem
    $new_image = imagecreatetruecolor($new_width, $new_height);

    // Preservar transparência para PNG
    if ($type == IMAGETYPE_PNG) {
        imagealphablending($new_image, false);
        imagesavealpha($new_image, true);
        $transparent = imagecolorallocatealpha($new_image, 255, 255, 255, 127);
        imagefilledrectangle($new_image, 0, 0, $new_width, $new_height, $transparent);
    }

    // Redimensionar
    imagecopyresampled($new_image, $source_image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

    // Salvar imagem
    switch ($type) {
        case IMAGETYPE_JPEG:
            $result = imagejpeg($new_image, $destination, $quality);
            break;
        case IMAGETYPE_PNG:
            $result = imagepng($new_image, $destination, 9);
            break;
        case IMAGETYPE_WEBP:
            $result = imagewebp($new_image, $destination, $quality);
            break;
    }

    // Limpar memória
    imagedestroy($source_image);
    imagedestroy($new_image);

    return $result;
}

/**
 * Enviar email (configurar SMTP posteriormente)
 */
function sendEmail($to, $subject, $body, $from = null) {
    $from = $from ?: 'noreply@realestate.com';
    
    $headers = [
        'MIME-Version: 1.0',
        'Content-type: text/html; charset=utf-8',
        'From: ' . $from,
        'Reply-To: ' . $from,
        'X-Mailer: PHP/' . phpversion()
    ];

    return mail($to, $subject, $body, implode("\r\n", $headers));
}

/**
 * Gerar token CSRF
 */
function generateCSRFToken() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Validar token CSRF
 */
function validateCSRFToken($token) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Redirect com mensagem flash
 */
function redirect($url, $message = null, $type = 'success') {
    if ($message) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['flash_message'] = $message;
        $_SESSION['flash_type'] = $type;
    }
    header('Location: ' . $url);
    exit;
}

/**
 * Obter e limpar mensagem flash
 */
function getFlashMessage() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        $type = $_SESSION['flash_type'] ?? 'info';
        
        unset($_SESSION['flash_message'], $_SESSION['flash_type']);
        
        return ['message' => $message, 'type' => $type];
    }
    
    return null;
}

/**
 * Debug helper
 */
function dd($data) {
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
    die();
}
