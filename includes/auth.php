<?php
/**
 * Sistema de Autenticação
 */

require_once __DIR__ . '/database.php';

class Auth {
    private $pdo;
    
    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
        $this->startSession();
    }
    
    private function startSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    /**
     * Fazer login do usuário
     */
    public function login($email, $password, $remember = false) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT u.*, up.avatar, b.id as broker_id 
                FROM users u 
                LEFT JOIN user_profiles up ON u.id = up.user_id
                LEFT JOIN brokers b ON u.id = b.user_id
                WHERE u.email = ? AND u.status = 'active'
            ");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                // Atualizar sessão
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_type'] = $user['user_type'];
                $_SESSION['avatar'] = $user['avatar'];
                
                if ($user['broker_id']) {
                    $_SESSION['broker_id'] = $user['broker_id'];
                }
                
                // Log da ação
                $this->logAction($user['id'], 'login', 'users', $user['id']);
                
                // Cookie "lembrar-me"
                if ($remember) {
                    $token = bin2hex(random_bytes(32));
                    // Salvar token no banco para validação futura
                    setcookie('remember_token', $token, time() + (86400 * 30), '/'); // 30 dias
                }
                
                return [
                    'success' => true,
                    'user' => $user,
                    'message' => 'Login realizado com sucesso!'
                ];
            }
            
            return [
                'success' => false,
                'message' => 'Email ou senha inválidos.'
            ];
            
        } catch (Exception $e) {
            error_log("Login error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Erro interno. Tente novamente.'
            ];
        }
    }
    
    /**
     * Registrar novo usuário
     */
    public function register($data) {
        try {
            // Verificar se email já existe
            $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$data['email']]);
            if ($stmt->fetch()) {
                return [
                    'success' => false,
                    'message' => 'Este email já está cadastrado.'
                ];
            }
            
            // Criar usuário
            $stmt = $this->pdo->prepare("
                INSERT INTO users (first_name, last_name, email, password, cpf, phone, user_type, status, verification_token) 
                VALUES (?, ?, ?, ?, ?, ?, ?, 'active', ?)
            ");
            
            $verification_token = bin2hex(random_bytes(32));
            $password_hash = password_hash($data['password'], PASSWORD_DEFAULT);
            $user_type = $data['user_type'] ?? 'user';
            
            $stmt->execute([
                $data['first_name'],
                $data['last_name'],
                $data['email'],
                $password_hash,
                $data['cpf'] ?? null,
                $data['phone'] ?? null,
                $user_type,
                $verification_token
            ]);
            
            $user_id = $this->pdo->lastInsertId();
            
            // Criar perfil do usuário
            $stmt = $this->pdo->prepare("
                INSERT INTO user_profiles (user_id, address, city, state, zip_code) 
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $user_id,
                $data['address'] ?? null,
                $data['city'] ?? null,
                $data['state'] ?? null,
                $data['zip_code'] ?? null
            ]);
            
            // Se for corretor, criar registro de corretor
            if ($user_type === 'broker') {
                $stmt = $this->pdo->prepare("
                    INSERT INTO brokers (user_id, license_number, company) 
                    VALUES (?, ?, ?)
                ");
                $stmt->execute([
                    $user_id,
                    $data['license_number'] ?? null,
                    $data['company'] ?? null
                ]);
            }
            
            // Log da ação
            $this->logAction($user_id, 'register', 'users', $user_id);
            
            return [
                'success' => true,
                'user_id' => $user_id,
                'verification_token' => $verification_token,
                'message' => 'Usuário cadastrado com sucesso!'
            ];
            
        } catch (Exception $e) {
            error_log("Registration error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Erro ao cadastrar usuário. Tente novamente.'
            ];
        }
    }
    
    /**
     * Fazer logout
     */
    public function logout() {
        if (isset($_SESSION['user_id'])) {
            $this->logAction($_SESSION['user_id'], 'logout', 'users', $_SESSION['user_id']);
        }
        
        session_destroy();
        setcookie('remember_token', '', time() - 3600, '/'); // Remover cookie
        
        return [
            'success' => true,
            'message' => 'Logout realizado com sucesso!'
        ];
    }
    
    /**
     * Verificar se usuário está logado
     */
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    /**
     * Obter dados do usuário atual
     */
    public function getCurrentUser() {
        if (!$this->isLoggedIn()) {
            return null;
        }
        
        try {
            $stmt = $this->pdo->prepare("
                SELECT u.*, up.*, b.id as broker_id, b.license_number, b.company
                FROM users u 
                LEFT JOIN user_profiles up ON u.id = up.user_id
                LEFT JOIN brokers b ON u.id = b.user_id
                WHERE u.id = ?
            ");
            $stmt->execute([$_SESSION['user_id']]);
            return $stmt->fetch();
        } catch (Exception $e) {
            error_log("Get current user error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Verificar permissões do usuário
     */
    public function hasRole($role) {
        return isset($_SESSION['user_type']) && $_SESSION['user_type'] === $role;
    }
    
    /**
     * Requerer login
     */
    public function requireLogin($redirect = '/auth/login.php') {
        if (!$this->isLoggedIn()) {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            header('Location: ' . $redirect);
            exit;
        }
    }
    
    /**
     * Requerer permissão específica
     */
    public function requireRole($role, $redirect = '/index.php') {
        $this->requireLogin();
        if (!$this->hasRole($role)) {
            header('Location: ' . $redirect);
            exit;
        }
    }
    
    /**
     * Resetar senha
     */
    public function requestPasswordReset($email) {
        try {
            $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = ? AND status = 'active'");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if (!$user) {
                return [
                    'success' => false,
                    'message' => 'Email não encontrado.'
                ];
            }
            
            $token = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', time() + 3600); // 1 hora
            
            $stmt = $this->pdo->prepare("
                UPDATE users SET password_reset_token = ?, password_reset_expires = ? WHERE id = ?
            ");
            $stmt->execute([$token, $expires, $user['id']]);
            
            // Log da ação
            $this->logAction($user['id'], 'password_reset_request', 'users', $user['id']);
            
            return [
                'success' => true,
                'token' => $token,
                'message' => 'Token de recuperação gerado com sucesso!'
            ];
            
        } catch (Exception $e) {
            error_log("Password reset request error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Erro ao processar solicitação.'
            ];
        }
    }
    
    /**
     * Resetar senha com token
     */
    public function resetPassword($token, $newPassword) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT id FROM users 
                WHERE password_reset_token = ? 
                AND password_reset_expires > NOW()
                AND status = 'active'
            ");
            $stmt->execute([$token]);
            $user = $stmt->fetch();
            
            if (!$user) {
                return [
                    'success' => false,
                    'message' => 'Token inválido ou expirado.'
                ];
            }
            
            $password_hash = password_hash($newPassword, PASSWORD_DEFAULT);
            
            $stmt = $this->pdo->prepare("
                UPDATE users 
                SET password = ?, password_reset_token = NULL, password_reset_expires = NULL 
                WHERE id = ?
            ");
            $stmt->execute([$password_hash, $user['id']]);
            
            // Log da ação
            $this->logAction($user['id'], 'password_reset', 'users', $user['id']);
            
            return [
                'success' => true,
                'message' => 'Senha alterada com sucesso!'
            ];
            
        } catch (Exception $e) {
            error_log("Password reset error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Erro ao alterar senha.'
            ];
        }
    }
    
    /**
     * Log de ações do sistema
     */
    private function logAction($user_id, $action, $table_name = null, $record_id = null, $old_values = null, $new_values = null) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO system_logs (user_id, action, table_name, record_id, old_values, new_values, ip_address, user_agent) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $user_id,
                $action,
                $table_name,
                $record_id,
                $old_values ? json_encode($old_values) : null,
                $new_values ? json_encode($new_values) : null,
                $_SERVER['REMOTE_ADDR'] ?? null,
                $_SERVER['HTTP_USER_AGENT'] ?? null
            ]);
        } catch (Exception $e) {
            error_log("Log action error: " . $e->getMessage());
        }
    }
}
