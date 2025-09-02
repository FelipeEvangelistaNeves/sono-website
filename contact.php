<?php
// contact.php
require_once 'config/database.php';

$response = ['success' => false, 'message' => ''];

if ($_POST) {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $mensagem = trim($_POST['mensagem'] ?? '');
    
    // Validações
    if (empty($nome) || empty($email) || empty($mensagem)) {
        $response['message'] = 'Todos os campos são obrigatórios.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Email inválido.';
    } elseif (strlen($mensagem) < 10) {
        $response['message'] = 'A mensagem deve ter pelo menos 10 caracteres.';
    } else {
        try {
            $db = new Database();
            $conn = $db->getConnection();
            
            // Criar tabela de contatos se não existir
            $conn->exec("CREATE TABLE IF NOT EXISTS contacts (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nome VARCHAR(100) NOT NULL,
                email VARCHAR(100) NOT NULL,
                mensagem TEXT NOT NULL,
                ip_address VARCHAR(45),
                user_agent TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )");
            
            // Inserir contato
            $stmt = $conn->prepare("INSERT INTO contacts (nome, email, mensagem, ip_address, user_agent) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $nome,
                $email,
                $mensagem,
                $_SERVER['REMOTE_ADDR'] ?? '',
                $_SERVER['HTTP_USER_AGENT'] ?? ''
            ]);
            
            $response['success'] = true;
            $response['message'] = 'Mensagem enviada com sucesso! Entraremos em contato em breve.';
            
            // Opcional: Enviar email de notificação
            $to = 'admin@sono.com';
            $subject = 'Nova mensagem do site - ' . $nome;
            $body = "Nome: {$nome}\nEmail: {$email}\n\nMensagem:\n{$mensagem}";
            $headers = "From: noreply@sono.com\r\nReply-To: {$email}";
            
            // mail($to, $subject, $body, $headers);
            
        } catch (Exception $e) {
            $response['message'] = 'Erro interno. Tente novamente mais tarde.';
            error_log('Erro ao salvar contato: ' . $e->getMessage());
        }
    }
}

// Se for requisição AJAX, retornar JSON
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Caso contrário, redirecionar com mensagem
$messageParam = $response['success'] ? 'success' : 'error';
$message = urlencode($response['message']);
header("Location: index.php?contact={$messageParam}&message={$message}#contato");
exit;
?>