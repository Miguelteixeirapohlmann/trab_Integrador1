<?php
/**
 * Model para Mensagens de Contato
 */

class Contact {
    private $pdo;
    
    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }
    
    /**
     * Criar nova mensagem de contato
     */
    public function create($data) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO contact_messages (
                    name, email, phone, subject, message, user_id, 
                    status, ip_address, user_agent
                ) VALUES (?, ?, ?, ?, ?, ?, 'new', ?, ?)
            ");
            
            $result = $stmt->execute([
                $data['name'],
                $data['email'],
                $data['phone'],
                $data['subject'],
                $data['message'],
                $data['user_id'],
                $data['ip_address'],
                $data['user_agent']
            ]);
            
            if ($result) {
                return [
                    'success' => true,
                    'message_id' => $this->pdo->lastInsertId(),
                    'message' => 'Mensagem enviada com sucesso!'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Erro ao enviar mensagem.'
                ];
            }
        } catch (Exception $e) {
            error_log("Contact create error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Erro interno. Tente novamente.'
            ];
        }
    }
    
    /**
     * Buscar mensagem por ID
     */
    public function findById($id) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT cm.*, u.first_name, u.last_name, u.email as user_email
                FROM contact_messages cm
                LEFT JOIN users u ON cm.user_id = u.id
                WHERE cm.id = ?
            ");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (Exception $e) {
            error_log("Contact findById error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Listar mensagens com paginação e filtros
     */
    public function getAll($page = 1, $limit = 20, $filters = []) {
        try {
            $offset = ($page - 1) * $limit;
            $where = [];
            $params = [];
            
            // Aplicar filtros
            if (!empty($filters['status'])) {
                $where[] = "cm.status = ?";
                $params[] = $filters['status'];
            }
            
            if (!empty($filters['search'])) {
                $where[] = "(cm.name LIKE ? OR cm.email LIKE ? OR cm.subject LIKE ? OR cm.message LIKE ?)";
                $search = '%' . $filters['search'] . '%';
                $params[] = $search;
                $params[] = $search;
                $params[] = $search;
                $params[] = $search;
            }
            
            if (!empty($filters['date_from'])) {
                $where[] = "DATE(cm.created_at) >= ?";
                $params[] = $filters['date_from'];
            }
            
            if (!empty($filters['date_to'])) {
                $where[] = "DATE(cm.created_at) <= ?";
                $params[] = $filters['date_to'];
            }
            
            $where_clause = $where ? 'WHERE ' . implode(' AND ', $where) : '';
            
            // Query principal
            $stmt = $this->pdo->prepare("
                SELECT cm.*, 
                       u.first_name, u.last_name, u.email as user_email,
                       COUNT(*) OVER() as total_records
                FROM contact_messages cm
                LEFT JOIN users u ON cm.user_id = u.id
                {$where_clause}
                ORDER BY cm.created_at DESC
                LIMIT {$limit} OFFSET {$offset}
            ");
            $stmt->execute($params);
            $results = $stmt->fetchAll();
            
            $total = $results ? $results[0]['total_records'] : 0;
            
            return [
                'data' => $results,
                'total' => $total,
                'page' => $page,
                'limit' => $limit,
                'pages' => ceil($total / $limit)
            ];
        } catch (Exception $e) {
            error_log("Contact getAll error: " . $e->getMessage());
            return ['data' => [], 'total' => 0, 'page' => 1, 'limit' => $limit, 'pages' => 0];
        }
    }
    
    /**
     * Atualizar status da mensagem
     */
    public function updateStatus($id, $status) {
        try {
            $allowed_statuses = ['new', 'read', 'replied', 'archived'];
            if (!in_array($status, $allowed_statuses)) {
                return [
                    'success' => false,
                    'message' => 'Status inválido.'
                ];
            }
            
            $stmt = $this->pdo->prepare("UPDATE contact_messages SET status = ? WHERE id = ?");
            $result = $stmt->execute([$status, $id]);
            
            if ($result) {
                return [
                    'success' => true,
                    'message' => 'Status atualizado com sucesso!'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Erro ao atualizar status.'
                ];
            }
        } catch (Exception $e) {
            error_log("Contact updateStatus error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Erro interno.'
            ];
        }
    }
    
    /**
     * Deletar mensagem
     */
    public function delete($id) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM contact_messages WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (Exception $e) {
            error_log("Contact delete error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obter estatísticas das mensagens
     */
    public function getStats() {
        try {
            $stmt = $this->pdo->prepare("
                SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'new' THEN 1 ELSE 0 END) as new_messages,
                    SUM(CASE WHEN status = 'read' THEN 1 ELSE 0 END) as read_messages,
                    SUM(CASE WHEN status = 'replied' THEN 1 ELSE 0 END) as replied_messages,
                    SUM(CASE WHEN status = 'archived' THEN 1 ELSE 0 END) as archived_messages,
                    SUM(CASE WHEN DATE(created_at) = CURDATE() THEN 1 ELSE 0 END) as today_messages,
                    SUM(CASE WHEN created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) THEN 1 ELSE 0 END) as week_messages
                FROM contact_messages
            ");
            $stmt->execute();
            
            return $stmt->fetch() ?: [];
        } catch (Exception $e) {
            error_log("Contact getStats error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Marcar como lida
     */
    public function markAsRead($id) {
        return $this->updateStatus($id, 'read');
    }
    
    /**
     * Marcar como respondida
     */
    public function markAsReplied($id) {
        return $this->updateStatus($id, 'replied');
    }
    
    /**
     * Arquivar mensagem
     */
    public function archive($id) {
        return $this->updateStatus($id, 'archived');
    }
}
