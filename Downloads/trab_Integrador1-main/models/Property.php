<?php
/**
 * Model para Propriedades/Imóveis
 */

class Property {
    private $pdo;
    
    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }
    
    /**
     * Buscar propriedade por ID
     */
    public function findById($id) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT p.*, 
                       u.first_name as broker_first_name, u.last_name as broker_last_name, 
                       u.phone as broker_phone, u.email as broker_email,
                       b.company, b.license_number, b.rating,
                       GROUP_CONCAT(pi.image_path ORDER BY pi.is_primary DESC, pi.display_order) as images
                FROM properties p
                LEFT JOIN brokers b ON p.broker_id = b.id
                LEFT JOIN users u ON b.user_id = u.id
                LEFT JOIN property_images pi ON p.id = pi.property_id
                WHERE p.id = ?
                GROUP BY p.id
            ");
            $stmt->execute([$id]);
            $property = $stmt->fetch();
            
            if ($property) {
                // Converter JSON fields
                $property['features'] = $property['features'] ? json_decode($property['features'], true) : [];
                $property['amenities'] = $property['amenities'] ? json_decode($property['amenities'], true) : [];
                $property['images'] = $property['images'] ? explode(',', $property['images']) : [];
                
                // Incrementar contador de visualizações
                $this->incrementViews($id);
            }
            
            return $property;
        } catch (Exception $e) {
            error_log("Property findById error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Listar propriedades com filtros e paginação
     */
    public function getAll($page = 1, $limit = 12, $filters = []) {
        try {
            $offset = ($page - 1) * $limit;
            $where = ['p.status = ?'];
            $params = ['active'];
            
            // Aplicar filtros
            if (!empty($filters['search'])) {
                $where[] = "(p.title LIKE ? OR p.description LIKE ? OR p.address LIKE ? OR p.neighborhood LIKE ? OR p.city LIKE ?)";
                $search = '%' . $filters['search'] . '%';
                $params[] = $search;
                $params[] = $search;
                $params[] = $search;
                $params[] = $search;
                $params[] = $search;
            }
            
            if (!empty($filters['property_type'])) {
                $where[] = "p.property_type = ?";
                $params[] = $filters['property_type'];
            }
            
            if (!empty($filters['transaction_type'])) {
                if ($filters['transaction_type'] == 'both') {
                    $where[] = "p.transaction_type IN ('both', 'sale', 'rent')";
                } else {
                    $where[] = "(p.transaction_type = ? OR p.transaction_type = 'both')";
                    $params[] = $filters['transaction_type'];
                }
            }
            
            if (!empty($filters['city'])) {
                $where[] = "p.city = ?";
                $params[] = $filters['city'];
            }
            
            if (!empty($filters['neighborhood'])) {
                $where[] = "p.neighborhood = ?";
                $params[] = $filters['neighborhood'];
            }
            
            if (!empty($filters['min_price'])) {
                $where[] = "p.price >= ?";
                $params[] = $filters['min_price'];
            }
            
            if (!empty($filters['max_price'])) {
                $where[] = "p.price <= ?";
                $params[] = $filters['max_price'];
            }
            
            if (!empty($filters['bedrooms'])) {
                $where[] = "p.bedrooms >= ?";
                $params[] = $filters['bedrooms'];
            }
            
            if (!empty($filters['bathrooms'])) {
                $where[] = "p.bathrooms >= ?";
                $params[] = $filters['bathrooms'];
            }
            
            if (!empty($filters['garage_spaces'])) {
                $where[] = "p.garage_spaces >= ?";
                $params[] = $filters['garage_spaces'];
            }
            
            if (!empty($filters['furnished'])) {
                $where[] = "p.furnished = ?";
                $params[] = $filters['furnished'] == 'yes' ? 1 : 0;
            }
            
            if (!empty($filters['pets_allowed'])) {
                $where[] = "p.pets_allowed = ?";
                $params[] = $filters['pets_allowed'] == 'yes' ? 1 : 0;
            }
            
            if (!empty($filters['broker_id'])) {
                $where[] = "p.broker_id = ?";
                $params[] = $filters['broker_id'];
            }
            
            // Apenas propriedades em destaque
            if (!empty($filters['featured'])) {
                $where[] = "p.featured = 1";
            }
            
            $where_clause = implode(' AND ', $where);
            
            // Ordenação
            $order_by = 'p.created_at DESC';
            if (!empty($filters['sort'])) {
                switch ($filters['sort']) {
                    case 'price_asc':
                        $order_by = 'p.price ASC';
                        break;
                    case 'price_desc':
                        $order_by = 'p.price DESC';
                        break;
                    case 'newest':
                        $order_by = 'p.created_at DESC';
                        break;
                    case 'oldest':
                        $order_by = 'p.created_at ASC';
                        break;
                    case 'area_asc':
                        $order_by = 'p.area_sqm ASC';
                        break;
                    case 'area_desc':
                        $order_by = 'p.area_sqm DESC';
                        break;
                }
            }
            
            // Query principal
            $stmt = $this->pdo->prepare("
                SELECT p.*, 
                       u.first_name as broker_first_name, u.last_name as broker_last_name,
                       b.company, b.rating,
                       pi.image_path as primary_image,
                       COUNT(*) OVER() as total_records
                FROM properties p
                LEFT JOIN brokers b ON p.broker_id = b.id
                LEFT JOIN users u ON b.user_id = u.id
                LEFT JOIN property_images pi ON p.id = pi.property_id AND pi.is_primary = 1
                WHERE {$where_clause}
                ORDER BY {$order_by}
                LIMIT {$limit} OFFSET {$offset}
            ");
            $stmt->execute($params);
            $results = $stmt->fetchAll();
            
            // Processar resultados
            foreach ($results as &$property) {
                $property['features'] = $property['features'] ? json_decode($property['features'], true) : [];
                $property['amenities'] = $property['amenities'] ? json_decode($property['amenities'], true) : [];
            }
            
            $total = $results ? $results[0]['total_records'] : 0;
            
            return [
                'data' => $results,
                'total' => $total,
                'page' => $page,
                'limit' => $limit,
                'pages' => ceil($total / $limit)
            ];
        } catch (Exception $e) {
            error_log("Property getAll error: " . $e->getMessage());
            return ['data' => [], 'total' => 0, 'page' => 1, 'limit' => $limit, 'pages' => 0];
        }
    }
    
    /**
     * Criar nova propriedade
     */
    public function create($data) {
        try {
            $this->pdo->beginTransaction();
            
            $stmt = $this->pdo->prepare("
                INSERT INTO properties (
                    broker_id, title, description, property_type, transaction_type, price, rent_price,
                    area_sqm, bedrooms, bathrooms, garage_spaces, furnished, pets_allowed,
                    address, neighborhood, city, state, zip_code, latitude, longitude,
                    features, amenities, status, featured
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $result = $stmt->execute([
                $data['broker_id'],
                $data['title'],
                $data['description'],
                $data['property_type'],
                $data['transaction_type'],
                $data['price'],
                $data['rent_price'] ?? null,
                $data['area_sqm'],
                $data['bedrooms'] ?? 0,
                $data['bathrooms'] ?? 0,
                $data['garage_spaces'] ?? 0,
                $data['furnished'] ?? false,
                $data['pets_allowed'] ?? false,
                $data['address'],
                $data['neighborhood'] ?? null,
                $data['city'],
                $data['state'],
                $data['zip_code'],
                $data['latitude'] ?? null,
                $data['longitude'] ?? null,
                isset($data['features']) ? json_encode($data['features']) : null,
                isset($data['amenities']) ? json_encode($data['amenities']) : null,
                $data['status'] ?? 'pending',
                $data['featured'] ?? false
            ]);
            
            $property_id = $this->pdo->lastInsertId();
            
            $this->pdo->commit();
            
            return [
                'success' => true,
                'property_id' => $property_id,
                'message' => 'Propriedade criada com sucesso!'
            ];
        } catch (Exception $e) {
            $this->pdo->rollBack();
            error_log("Property create error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Erro ao criar propriedade.'
            ];
        }
    }
    
    /**
     * Atualizar propriedade
     */
    public function update($id, $data) {
        try {
            $fields = [];
            $params = [];
            
            $allowed_fields = [
                'title', 'description', 'property_type', 'transaction_type', 'price', 'rent_price',
                'area_sqm', 'bedrooms', 'bathrooms', 'garage_spaces', 'furnished', 'pets_allowed',
                'address', 'neighborhood', 'city', 'state', 'zip_code', 'latitude', 'longitude',
                'status', 'featured'
            ];
            
            foreach ($allowed_fields as $field) {
                if (isset($data[$field])) {
                    $fields[] = "{$field} = ?";
                    $params[] = $data[$field];
                }
            }
            
            if (isset($data['features'])) {
                $fields[] = "features = ?";
                $params[] = json_encode($data['features']);
            }
            
            if (isset($data['amenities'])) {
                $fields[] = "amenities = ?";
                $params[] = json_encode($data['amenities']);
            }
            
            if (empty($fields)) {
                return ['success' => false, 'message' => 'Nenhum campo para atualizar.'];
            }
            
            $params[] = $id;
            
            $stmt = $this->pdo->prepare("UPDATE properties SET " . implode(', ', $fields) . " WHERE id = ?");
            $result = $stmt->execute($params);
            
            if ($result) {
                return ['success' => true, 'message' => 'Propriedade atualizada com sucesso!'];
            } else {
                return ['success' => false, 'message' => 'Erro ao atualizar propriedade.'];
            }
        } catch (Exception $e) {
            error_log("Property update error: " . $e->getMessage());
            return ['success' => false, 'message' => 'Erro interno.'];
        }
    }
    
    /**
     * Adicionar imagem à propriedade
     */
    public function addImage($property_id, $image_path, $alt_text = '', $is_primary = false) {
        try {
            // Se esta for a imagem principal, desmarcar outras como principais
            if ($is_primary) {
                $stmt = $this->pdo->prepare("UPDATE property_images SET is_primary = 0 WHERE property_id = ?");
                $stmt->execute([$property_id]);
            }
            
            // Obter próximo order
            $stmt = $this->pdo->prepare("SELECT COALESCE(MAX(display_order), 0) + 1 as next_order FROM property_images WHERE property_id = ?");
            $stmt->execute([$property_id]);
            $next_order = $stmt->fetch()['next_order'];
            
            $stmt = $this->pdo->prepare("
                INSERT INTO property_images (property_id, image_path, image_name, alt_text, is_primary, display_order) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            
            return $stmt->execute([
                $property_id,
                $image_path,
                basename($image_path),
                $alt_text,
                $is_primary ? 1 : 0,
                $next_order
            ]);
        } catch (Exception $e) {
            error_log("Property addImage error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Incrementar visualizações
     */
    private function incrementViews($property_id) {
        try {
            $stmt = $this->pdo->prepare("UPDATE properties SET views_count = views_count + 1 WHERE id = ?");
            $stmt->execute([$property_id]);
        } catch (Exception $e) {
            error_log("Property incrementViews error: " . $e->getMessage());
        }
    }
    
    /**
     * Adicionar aos favoritos
     */
    public function addToFavorites($user_id, $property_id) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT IGNORE INTO user_favorites (user_id, property_id) VALUES (?, ?)
            ");
            $result = $stmt->execute([$user_id, $property_id]);
            
            if ($result && $stmt->rowCount() > 0) {
                // Incrementar contador de favoritos
                $stmt = $this->pdo->prepare("UPDATE properties SET favorites_count = favorites_count + 1 WHERE id = ?");
                $stmt->execute([$property_id]);
                
                return ['success' => true, 'message' => 'Adicionado aos favoritos!'];
            }
            
            return ['success' => false, 'message' => 'Já está nos favoritos.'];
        } catch (Exception $e) {
            error_log("Property addToFavorites error: " . $e->getMessage());
            return ['success' => false, 'message' => 'Erro interno.'];
        }
    }
    
    /**
     * Remover dos favoritos
     */
    public function removeFromFavorites($user_id, $property_id) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM user_favorites WHERE user_id = ? AND property_id = ?");
            $result = $stmt->execute([$user_id, $property_id]);
            
            if ($result && $stmt->rowCount() > 0) {
                // Decrementar contador de favoritos
                $stmt = $this->pdo->prepare("UPDATE properties SET favorites_count = GREATEST(favorites_count - 1, 0) WHERE id = ?");
                $stmt->execute([$property_id]);
                
                return ['success' => true, 'message' => 'Removido dos favoritos!'];
            }
            
            return ['success' => false, 'message' => 'Não estava nos favoritos.'];
        } catch (Exception $e) {
            error_log("Property removeFromFavorites error: " . $e->getMessage());
            return ['success' => false, 'message' => 'Erro interno.'];
        }
    }
    
    /**
     * Verificar se está nos favoritos do usuário
     */
    public function isFavorited($user_id, $property_id) {
        try {
            $stmt = $this->pdo->prepare("SELECT 1 FROM user_favorites WHERE user_id = ? AND property_id = ?");
            $stmt->execute([$user_id, $property_id]);
            return $stmt->fetch() !== false;
        } catch (Exception $e) {
            error_log("Property isFavorited error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obter propriedades similares
     */
    public function getSimilar($property_id, $limit = 6) {
        try {
            // Primeiro, obter dados da propriedade atual
            $property = $this->findById($property_id);
            if (!$property) {
                return [];
            }
            
            $stmt = $this->pdo->prepare("
                SELECT p.*, 
                       u.first_name as broker_first_name, u.last_name as broker_last_name,
                       b.company,
                       pi.image_path as primary_image
                FROM properties p
                LEFT JOIN brokers b ON p.broker_id = b.id
                LEFT JOIN users u ON b.user_id = u.id
                LEFT JOIN property_images pi ON p.id = pi.property_id AND pi.is_primary = 1
                WHERE p.id != ? 
                  AND p.status = 'active'
                  AND (
                      p.city = ? 
                      OR p.property_type = ?
                      OR (p.price BETWEEN ? AND ?)
                  )
                ORDER BY 
                    (CASE WHEN p.city = ? THEN 1 ELSE 0 END) +
                    (CASE WHEN p.property_type = ? THEN 1 ELSE 0 END) +
                    (CASE WHEN p.price BETWEEN ? AND ? THEN 1 ELSE 0 END) DESC,
                    p.created_at DESC
                LIMIT ?
            ");
            
            $price_range = $property['price'] * 0.3; // 30% de tolerância no preço
            $min_price = $property['price'] - $price_range;
            $max_price = $property['price'] + $price_range;
            
            $stmt->execute([
                $property_id,
                $property['city'],
                $property['property_type'],
                $min_price,
                $max_price,
                $property['city'],
                $property['property_type'],
                $min_price,
                $max_price,
                $limit
            ]);
            
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log("Property getSimilar error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Deletar propriedade
     */
    public function delete($id) {
        try {
            $this->pdo->beginTransaction();
            
            // Cancelar visitas pendentes
            $stmt = $this->pdo->prepare("UPDATE property_visits SET status = 'cancelled' WHERE property_id = ? AND status IN ('scheduled', 'confirmed')");
            $stmt->execute([$id]);
            
            // Remover dos favoritos
            $stmt = $this->pdo->prepare("DELETE FROM user_favorites WHERE property_id = ?");
            $stmt->execute([$id]);
            
            // Marcar como inativa (soft delete)
            $stmt = $this->pdo->prepare("UPDATE properties SET status = 'inactive' WHERE id = ?");
            $stmt->execute([$id]);
            
            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            error_log("Property delete error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obter estatísticas das propriedades
     */
    public function getStats($broker_id = null) {
        try {
            $where = $broker_id ? "WHERE broker_id = ?" : "";
            $params = $broker_id ? [$broker_id] : [];
            
            $stmt = $this->pdo->prepare("
                SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active,
                    SUM(CASE WHEN status = 'sold' THEN 1 ELSE 0 END) as sold,
                    SUM(CASE WHEN status = 'rented' THEN 1 ELSE 0 END) as rented,
                    SUM(views_count) as total_views,
                    SUM(favorites_count) as total_favorites,
                    AVG(price) as avg_price
                FROM properties {$where}
            ");
            $stmt->execute($params);
            
            return $stmt->fetch();
        } catch (Exception $e) {
            error_log("Property getStats error: " . $e->getMessage());
            return [];
        }
    }
}
