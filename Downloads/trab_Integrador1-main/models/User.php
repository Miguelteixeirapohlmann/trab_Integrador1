<?php
/**
 * Model para Usuários
 */

class User {
    private $pdo;
    
    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }
    
    /**
     * Buscar usuário por ID
     */
    public function findById($id) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT u.*, up.*, b.id as broker_id, b.license_number, b.company, b.rating
                FROM users u 
                LEFT JOIN user_profiles up ON u.id = up.user_id
                LEFT JOIN brokers b ON u.id = b.user_id
                WHERE u.id = ?
            ");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (Exception $e) {
            error_log("User findById error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Buscar usuário por email
     */
    public function findByEmail($email) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT u.*, up.*, b.id as broker_id 
                FROM users u 
                LEFT JOIN user_profiles up ON u.id = up.user_id
                LEFT JOIN brokers b ON u.id = b.user_id
                WHERE u.email = ?
            ");
            $stmt->execute([$email]);
            return $stmt->fetch();
        } catch (Exception $e) {
            error_log("User findByEmail error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Listar todos os usuários com paginação
     */
    public function getAll($page = 1, $limit = 20, $filters = []) {
        try {
            $offset = ($page - 1) * $limit;
            $where = [];
            $params = [];
            
            // Aplicar filtros
            if (!empty($filters['search'])) {
                $where[] = "(u.first_name LIKE ? OR u.last_name LIKE ? OR u.email LIKE ?)";
                $search = '%' . $filters['search'] . '%';
                $params[] = $search;
                $params[] = $search;
                $params[] = $search;
            }
            
            if (!empty($filters['user_type'])) {
                $where[] = "u.user_type = ?";
                $params[] = $filters['user_type'];
            }
            
            if (!empty($filters['status'])) {
                $where[] = "u.status = ?";
                $params[] = $filters['status'];
            }
            
            $where_clause = $where ? 'WHERE ' . implode(' AND ', $where) : '';
            
            // Query principal
            $stmt = $this->pdo->prepare("
                SELECT u.*, up.city, up.state, b.company, b.rating,
                       COUNT(*) OVER() as total_records
                FROM users u 
                LEFT JOIN user_profiles up ON u.id = up.user_id
                LEFT JOIN brokers b ON u.id = b.user_id
                {$where_clause}
                ORDER BY u.created_at DESC
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
            error_log("User getAll error: " . $e->getMessage());
            return ['data' => [], 'total' => 0, 'page' => 1, 'limit' => $limit, 'pages' => 0];
        }
    }
    
    /**
     * Atualizar perfil do usuário
     */
    public function updateProfile($user_id, $data) {
        try {
            $this->pdo->beginTransaction();
            
            // Atualizar dados básicos do usuário
            if (isset($data['first_name']) || isset($data['last_name']) || isset($data['phone'])) {
                $fields = [];
                $params = [];
                
                if (isset($data['first_name'])) {
                    $fields[] = 'first_name = ?';
                    $params[] = $data['first_name'];
                }
                
                if (isset($data['last_name'])) {
                    $fields[] = 'last_name = ?';
                    $params[] = $data['last_name'];
                }
                
                if (isset($data['phone'])) {
                    $fields[] = 'phone = ?';
                    $params[] = $data['phone'];
                }
                
                if ($fields) {
                    $params[] = $user_id;
                    $stmt = $this->pdo->prepare("UPDATE users SET " . implode(', ', $fields) . " WHERE id = ?");
                    $stmt->execute($params);
                }
            }
            
            // Atualizar ou inserir perfil
            $stmt = $this->pdo->prepare("
                INSERT INTO user_profiles (user_id, bio, address, city, state, zip_code, website, social_links, preferences) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE
                bio = VALUES(bio),
                address = VALUES(address),
                city = VALUES(city),
                state = VALUES(state),
                zip_code = VALUES(zip_code),
                website = VALUES(website),
                social_links = VALUES(social_links),
                preferences = VALUES(preferences)
            ");
            
            $stmt->execute([
                $user_id,
                $data['bio'] ?? null,
                $data['address'] ?? null,
                $data['city'] ?? null,
                $data['state'] ?? null,
                $data['zip_code'] ?? null,
                $data['website'] ?? null,
                isset($data['social_links']) ? json_encode($data['social_links']) : null,
                isset($data['preferences']) ? json_encode($data['preferences']) : null
            ]);
            
            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            error_log("User updateProfile error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Atualizar avatar do usuário
     */
    public function updateAvatar($user_id, $avatar_path) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO user_profiles (user_id, avatar) VALUES (?, ?)
                ON DUPLICATE KEY UPDATE avatar = VALUES(avatar)
            ");
            return $stmt->execute([$user_id, $avatar_path]);
        } catch (Exception $e) {
            error_log("User updateAvatar error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Alterar senha do usuário
     */
    public function changePassword($user_id, $current_password, $new_password) {
        try {
            // Verificar senha atual
            $stmt = $this->pdo->prepare("SELECT password FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            $user = $stmt->fetch();
            
            if (!$user || !password_verify($current_password, $user['password'])) {
                return ['success' => false, 'message' => 'Senha atual incorreta.'];
            }
            
            // Atualizar senha
            $stmt = $this->pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            $result = $stmt->execute([password_hash($new_password, PASSWORD_DEFAULT), $user_id]);
            
            if ($result) {
                return ['success' => true, 'message' => 'Senha alterada com sucesso.'];
            } else {
                return ['success' => false, 'message' => 'Erro ao alterar senha.'];
            }
        } catch (Exception $e) {
            error_log("User changePassword error: " . $e->getMessage());
            return ['success' => false, 'message' => 'Erro interno.'];
        }
    }
    
    /**
     * Desativar usuário
     */
    public function deactivate($user_id) {
        try {
            $stmt = $this->pdo->prepare("UPDATE users SET status = 'inactive' WHERE id = ?");
            return $stmt->execute([$user_id]);
        } catch (Exception $e) {
            error_log("User deactivate error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Reativar usuário
     */
    public function activate($user_id) {
        try {
            $stmt = $this->pdo->prepare("UPDATE users SET status = 'active' WHERE id = ?");
            return $stmt->execute([$user_id]);
        } catch (Exception $e) {
            error_log("User activate error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obter estatísticas do usuário
     */
    public function getStats($user_id) {
        try {
            $stats = [];
            
            // Total de favoritos
            $stmt = $this->pdo->prepare("SELECT COUNT(*) as count FROM user_favorites WHERE user_id = ?");
            $stmt->execute([$user_id]);
            $stats['favorites'] = $stmt->fetch()['count'];
            
            // Total de visitas agendadas
            $stmt = $this->pdo->prepare("SELECT COUNT(*) as count FROM property_visits WHERE user_id = ?");
            $stmt->execute([$user_id]);
            $stats['visits'] = $stmt->fetch()['count'];
            
            // Se for corretor, buscar estatísticas adicionais
            $stmt = $this->pdo->prepare("SELECT id FROM brokers WHERE user_id = ?");
            $stmt->execute([$user_id]);
            $broker = $stmt->fetch();
            
            if ($broker) {
                // Total de propriedades
                $stmt = $this->pdo->prepare("SELECT COUNT(*) as count FROM properties WHERE broker_id = ?");
                $stmt->execute([$broker['id']]);
                $stats['properties'] = $stmt->fetch()['count'];
                
                // Total de visitas como corretor
                $stmt = $this->pdo->prepare("SELECT COUNT(*) as count FROM property_visits WHERE broker_id = ?");
                $stmt->execute([$broker['id']]);
                $stats['broker_visits'] = $stmt->fetch()['count'];
            }
            
            return $stats;
        } catch (Exception $e) {
            error_log("User getStats error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Deletar usuário (soft delete)
     */
    public function delete($user_id) {
        try {
            $this->pdo->beginTransaction();
            
            // Marcar usuário como inativo
            $stmt = $this->pdo->prepare("UPDATE users SET status = 'inactive', email = CONCAT(email, '_deleted_', NOW()) WHERE id = ?");
            $stmt->execute([$user_id]);
            
            // Cancelar visitas pendentes
            $stmt = $this->pdo->prepare("UPDATE property_visits SET status = 'cancelled' WHERE user_id = ? AND status IN ('scheduled', 'confirmed')");
            $stmt->execute([$user_id]);
            
            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            error_log("User delete error: " . $e->getMessage());
            return false;
        }
    }
}
