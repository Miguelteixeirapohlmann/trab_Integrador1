<?php
/**
 * Processamento de mensagens de contato
 */

// Verificar se é uma requisição POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit;
}

// Capturar dados do formulário
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$message = trim($_POST['message'] ?? '');

// Validar dados obrigatórios
if (empty($name) || empty($email) || empty($message)) {
    header('Location: ../index.php?error=campos_obrigatorios');
    exit;
}

// Validar email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: ../index.php?error=email_invalido');
    exit;
}

// Preparar dados da mensagem
$mensagem = [
    'id' => uniqid(),
    'nome' => $name,
    'email' => $email,
    'telefone' => $phone,
    'mensagem' => $message,
    'data' => date('Y-m-d H:i:s'),
    'status' => 'nova'
];

// Arquivo para armazenar as mensagens
$arquivo_mensagens = __DIR__ . '/mensagens.json';

// Carregar mensagens existentes
$mensagens = [];
if (file_exists($arquivo_mensagens)) {
    $json = file_get_contents($arquivo_mensagens);
    $mensagens = json_decode($json, true) ?: [];
}

// Adicionar nova mensagem
$mensagens[] = $mensagem;

// Salvar todas as mensagens
file_put_contents($arquivo_mensagens, json_encode($mensagens, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

// Redirecionar com sucesso
header('Location: ../index.php?success=mensagem_enviada');
exit;
?>
