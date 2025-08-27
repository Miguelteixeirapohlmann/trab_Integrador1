-- Dropa todas as tabelas se elas existirem
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS system_logs;
DROP TABLE IF EXISTS reviews;
DROP TABLE IF EXISTS contact_messages;
DROP TABLE IF EXISTS property_visits;
DROP TABLE IF EXISTS user_favorites;
DROP TABLE IF EXISTS property_images;
DROP TABLE IF EXISTS properties;
DROP TABLE IF EXISTS brokers;
DROP TABLE IF EXISTS user_profiles;
DROP TABLE IF EXISTS users;

SET FOREIGN_KEY_CHECKS = 1;

-- Criação do banco de dados
CREATE DATABASE IF NOT EXISTS real_estate CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE real_estate;

-- Tabela de usuários
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    cpf VARCHAR(14) UNIQUE,
    phone VARCHAR(20),
    user_type ENUM('user', 'broker', 'admin') DEFAULT 'user',
    status ENUM('active', 'inactive', 'pending') DEFAULT 'pending',
    email_verified BOOLEAN DEFAULT FALSE,
    verification_token VARCHAR(255) NULL,
    password_reset_token VARCHAR(255) NULL,
    password_reset_expires DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_email (email),
    INDEX idx_user_type (user_type),
    INDEX idx_status (status)
);

-- Tabela de perfis de usuário
CREATE TABLE user_profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    avatar VARCHAR(255) NULL,
    bio TEXT NULL,
    address VARCHAR(255) NULL,
    city VARCHAR(100) NULL,
    state VARCHAR(100) NULL,
    zip_code VARCHAR(10) NULL,
    website VARCHAR(255) NULL,
    social_links JSON NULL,
    preferences JSON NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_profile (user_id)
);

-- Tabela de corretores
CREATE TABLE brokers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    license_number VARCHAR(50) UNIQUE,
    company VARCHAR(255) NULL,
    specialties JSON NULL,
    commission_rate DECIMAL(5,2) DEFAULT 3.00,
    rating DECIMAL(3,2) DEFAULT 0.00,
    reviews_count INT DEFAULT 0,
    total_sales INT DEFAULT 0,
    years_experience INT DEFAULT 0,
    languages JSON NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_broker_user (user_id)
);

-- Tabela de propriedades/imóveis
CREATE TABLE properties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    broker_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    property_type ENUM('house', 'apartment', 'condo', 'townhouse', 'commercial', 'land') NOT NULL,
    transaction_type ENUM('sale', 'rent', 'both') NOT NULL,
    price DECIMAL(15,2) NOT NULL,
    rent_price DECIMAL(15,2) NULL,
    area_sqm DECIMAL(8,2) NOT NULL,
    bedrooms INT DEFAULT 0,
    bathrooms INT DEFAULT 0,
    garage_spaces INT DEFAULT 0,
    furnished BOOLEAN DEFAULT FALSE,
    pets_allowed BOOLEAN DEFAULT FALSE,
    
    -- Endereço
    address VARCHAR(255) NOT NULL,
    neighborhood VARCHAR(100),
    city VARCHAR(100) NOT NULL,
    state VARCHAR(100) NOT NULL,
    zip_code VARCHAR(10) NOT NULL,
    latitude DECIMAL(10, 8) NULL,
    longitude DECIMAL(11, 8) NULL,
    
    -- Features e amenidades
    features JSON NULL,
    amenities JSON NULL,
    
    -- Status e datas
    status ENUM('active', 'pending', 'sold', 'rented', 'inactive') DEFAULT 'pending',
    featured BOOLEAN DEFAULT FALSE,
    views_count INT DEFAULT 0,
    favorites_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (broker_id) REFERENCES brokers(id) ON DELETE CASCADE,
    INDEX idx_property_type (property_type),
    INDEX idx_transaction_type (transaction_type),
    INDEX idx_price (price),
    INDEX idx_city (city),
    INDEX idx_status (status),
    INDEX idx_featured (featured)
);

-- Tabela de imagens das propriedades
CREATE TABLE property_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    property_id INT NOT NULL,
    image_path VARCHAR(500) NOT NULL,
    image_name VARCHAR(255) NOT NULL,
    alt_text VARCHAR(255) NULL,
    is_primary BOOLEAN DEFAULT FALSE,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE,
    INDEX idx_property_id (property_id),
    INDEX idx_is_primary (is_primary)
);

-- Tabela de favoritos
CREATE TABLE user_favorites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    property_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_property (user_id, property_id)
);

-- Tabela de agendamentos de visitas
CREATE TABLE property_visits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    property_id INT NOT NULL,
    user_id INT NULL,
    broker_id INT NOT NULL,
    visit_date DATETIME NOT NULL,
    visit_type ENUM('presencial', 'virtual') DEFAULT 'presencial',
    status ENUM('scheduled', 'confirmed', 'completed', 'cancelled', 'no_show') DEFAULT 'scheduled',
    notes TEXT NULL,
    visitor_name VARCHAR(255) NOT NULL,
    visitor_email VARCHAR(255) NOT NULL,
    visitor_phone VARCHAR(20) NOT NULL,
    message TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (broker_id) REFERENCES brokers(id) ON DELETE CASCADE,
    INDEX idx_visit_date (visit_date),
    INDEX idx_status (status)
);

-- Tabela de mensagens de contato
CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NULL,
    subject VARCHAR(255) NULL,
    message TEXT NOT NULL,
    user_id INT NULL,
    property_id INT NULL,
    status ENUM('new', 'read', 'replied', 'archived') DEFAULT 'new',
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE SET NULL,
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
);

-- Tabela de avaliações
CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reviewer_id INT NOT NULL,
    broker_id INT NOT NULL,
    property_id INT NULL,
    rating TINYINT NOT NULL,
    review_text TEXT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (reviewer_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (broker_id) REFERENCES brokers(id) ON DELETE CASCADE,
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE SET NULL,
    UNIQUE KEY unique_review (reviewer_id, broker_id, property_id),
    INDEX idx_broker_id (broker_id),
    INDEX idx_rating (rating),
    INDEX idx_status (status)
);

-- Tabela de logs do sistema
CREATE TABLE system_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    action VARCHAR(100) NOT NULL,
    table_name VARCHAR(50) NULL,
    record_id INT NULL,
    old_values JSON NULL,
    new_values JSON NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_action (action),
    INDEX idx_created_at (created_at)
);

-- Inserir usuário administrador padrão
INSERT INTO users (first_name, last_name, email, password, user_type, status, email_verified) 
VALUES ('Admin', 'System', 'admin@realestate.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'active', TRUE);

-- Inserir alguns corretores de exemplo
INSERT INTO users (first_name, last_name, email, password, user_type, status, email_verified) 
VALUES 
('João', 'Silva', 'joao.silva@realestate.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'broker', 'active', TRUE),
('Maria', 'Santos', 'maria.santos@realestate.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'broker', 'active', TRUE),
('Pedro', 'Costa', 'pedro.costa@realestate.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'broker', 'active', TRUE);

-- Inserir dados dos corretores
INSERT INTO brokers (user_id, license_number, company, specialties, years_experience, languages) 
VALUES 
(2, 'CRECI-12345', 'Silva Imóveis', '["Residencial", "Comercial"]', 5, '["Português", "Inglês"]'),
(3, 'CRECI-67890', 'Santos & Associados', '["Residencial", "Apartamentos"]', 8, '["Português", "Espanhol"]'),
(4, 'CRECI-11111', 'Costa Negócios Imobiliários', '["Casas de luxo", "Terrenos"]', 12, '["Português"]');

-- Inserir algumas propriedades de exemplo
INSERT INTO properties (broker_id, title, description, property_type, transaction_type, price, area_sqm, bedrooms, bathrooms, garage_spaces, address, neighborhood, city, state, zip_code, status) 
VALUES 
(1, 'Casa Jardim das Flores', 'Bela casa em localização privilegiada com amplo jardim e acabamentos de primeira qualidade.', 'house', 'sale', 220000.00, 180.50, 3, 2, 2, 'Rua das Flores, 123', 'Jardim das Flores', 'Porto Alegre', 'RS', '91234-567', 'active'),
(2, 'Apartamento Vista Alegre', 'Apartamento moderno com vista panorâmica, próximo ao centro da cidade.', 'apartment', 'both', 310000.00, 120.00, 2, 2, 1, 'Av. Vista Alegre, 456', 'Vista Alegre', 'Porto Alegre', 'RS', '91345-678', 'active'),
(3, 'Casa Bela Vista', 'Casa espaçosa ideal para famílias, com quintal amplo e churrasqueira.', 'house', 'sale', 199000.00, 200.00, 4, 3, 2, 'Rua Bela Vista, 789', 'Bela Vista', 'Canoas', 'RS', '92123-456', 'active');
