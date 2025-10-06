<?php
require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../models/Contact.php';

/**
 * Controlador para mensagens de contato
 */

// Verificar se a requisição é POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/index.php');
}

// Processar formulário de contato diretamente
sendContactMessage();

function sendContactMessage() {
    global $auth;
    
    // Validar CSRF token
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        redirect('/index.php#contact', 'Token de segurança inválido.', 'error');
        return;
    }
    
    // Capturar e limpar dados do formulário
    $name = trim(strval($_POST['name'] ?? ''));
    $email = trim(strval($_POST['email'] ?? ''));
    $phone = trim(strval($_POST['phone'] ?? ''));
    $subject = trim(strval($_POST['subject'] ?? 'Contato via site'));
    $message = trim(strval($_POST['message'] ?? ''));
    
    // Dados sanitizados
    $data = [
        'name' => htmlspecialchars(strip_tags($name), ENT_QUOTES, 'UTF-8'),
        'email' => htmlspecialchars(strip_tags($email), ENT_QUOTES, 'UTF-8'),
        'phone' => htmlspecialchars(strip_tags($phone), ENT_QUOTES, 'UTF-8'),
        'subject' => htmlspecialchars(strip_tags($subject), ENT_QUOTES, 'UTF-8'),
        'message' => htmlspecialchars(strip_tags($message), ENT_QUOTES, 'UTF-8'),
        'user_id' => $auth->isLoggedIn() ? $_SESSION['user_id'] : null,
        'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null
    ];
    
    // Validações
    $errors = [];
    
    if (empty($data['name'])) {
        $errors[] = 'Nome é obrigatório.';
    }
    
    if (empty($data['email']) || !validateEmail($data['email'])) {
        $errors[] = 'Email válido é obrigatório.';
    }
    
    if (empty($data['message'])) {
        $errors[] = 'Mensagem é obrigatória.';
    }
    
    if (!empty($data['phone']) && !validatePhone($data['phone'])) {
        $errors[] = 'Telefone inválido.';
    }
    
    // Verificar rate limiting (opcional)
    $session_key = 'last_contact_message';
    $rate_limit = 60; // 1 minuto entre mensagens
    
    if (isset($_SESSION[$session_key])) {
        $time_diff = time() - $_SESSION[$session_key];
        if ($time_diff < $rate_limit) {
            $errors[] = 'Aguarde um momento antes de enviar outra mensagem.';
        }
    }
    
    if (!empty($errors)) {
        redirect('/index.php#contact', implode(' ', $errors), 'error');
        return;
    }
    
    // Verificar se a tabela de contato existe, senão usar sistema de fallback
    try {
        // Tentar usar o modelo de banco de dados
        $contactModel = new Contact();
        $result = $contactModel->create($data);
        
        if ($result['success']) {
            $_SESSION[$session_key] = time();
            
            // Tentar enviar email de notificação (opcional)
            try {
                $subject = "Nova mensagem de contato - " . $data['subject'];
                $body = sprintf("
                    <h3>Nova mensagem recebida</h3>
                    <p><strong>Nome:</strong> %s</p>
                    <p><strong>Email:</strong> %s</p>
                    <p><strong>Telefone:</strong> %s</p>
                    <p><strong>Assunto:</strong> %s</p>
                    <p><strong>Mensagem:</strong></p>
                    <p>%s</p>
                    <hr>
                    <p><small>IP: %s</small></p>
                ",
                    htmlspecialchars($data['name']),
                    htmlspecialchars($data['email']),
                    htmlspecialchars($data['phone']),
                    htmlspecialchars($data['subject']),
                    nl2br(htmlspecialchars($data['message'])),
                    htmlspecialchars($data['ip_address'])
                );
                
                sendEmail('contato@realestate.com', $subject, $body);
            } catch (Exception $e) {
                error_log("Email notification error: " . $e->getMessage());
            }
            
            redirect('/index.php#contact', 'Mensagem enviada com sucesso! Entraremos em contato em breve.', 'success');
        } else {
            redirect('/index.php#contact', $result['message'], 'error');
        }
    } catch (Exception $e) {
        // Fallback para o sistema de arquivo (compatibilidade com sistema antigo)
        error_log("Database contact error, using file fallback: " . $e->getMessage());
        
        // Preparar dados da mensagem para arquivo
        $mensagem = [
            'id' => uniqid(),
            'nome' => $data['name'],
            'email' => $data['email'],
            'telefone' => $data['phone'],
            'assunto' => $data['subject'],
            'mensagem' => $data['message'],
            'data' => date('Y-m-d H:i:s'),
            'status' => 'nova',
            'ip_address' => $data['ip_address'],
            'user_agent' => $data['user_agent']
        ];
        
        // Arquivo para armazenar as mensagens
        $arquivo_mensagens = __DIR__ . '/../data/mensagens.json';
        
        // Carregar mensagens existentes
        $mensagens = [];
        if (file_exists($arquivo_mensagens)) {
            $json = file_get_contents($arquivo_mensagens);
            $mensagens = json_decode($json, true) ?: [];
        }
        
        // Adicionar nova mensagem
        $mensagens[] = $mensagem;
        
        // Salvar todas as mensagens
        if (file_put_contents($arquivo_mensagens, json_encode($mensagens, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
            $_SESSION[$session_key] = time();
            redirect('/index.php#contact', 'Mensagem enviada com sucesso! Entraremos em contato em breve.', 'success');
        } else {
            redirect('/index.php#contact', 'Erro ao enviar mensagem. Tente novamente.', 'error');
        }
    }
}
