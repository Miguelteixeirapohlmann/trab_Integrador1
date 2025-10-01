-- Dropa todas as tabelas se elas existirem
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS system_logs;
DROP TABLE IF EXISTS reviews;
DROP TABLE IF EXISTS contact_messages;
DROP TABLE IF EXISTS agendamentos;
DROP TABLE IF EXISTS property_visits;
DROP TABLE IF EXISTS user_favorites;
DROP TABLE IF EXISTS property_rentals;
DROP TABLE IF EXISTS property_purchases;
DROP TABLE IF EXISTS property_images;
DROP TABLE IF EXISTS properties;
DROP TABLE IF EXISTS brokers;
DROP TABLE IF EXISTS user_profiles;
DROP TABLE IF EXISTS users;

SET FOREIGN_KEY_CHECKS = 1;

-- Criação do banco de dados
CREATE DATABASE IF NOT EXISTS trab_integrador CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE trab_integrador;

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

-- Tabela de compras/vendas de imóveis
CREATE TABLE property_purchases (
    id INT AUTO_INCREMENT PRIMARY KEY,
    property_id INT NOT NULL,
    buyer_id INT NOT NULL,
    seller_id INT NOT NULL,
    broker_id INT NOT NULL,
    purchase_price DECIMAL(15,2) NOT NULL,
    down_payment DECIMAL(15,2) NULL,
    financing_amount DECIMAL(15,2) NULL,
    financing_bank VARCHAR(255) NULL,
    payment_method ENUM('cash', 'financing', 'mixed') DEFAULT 'financing',
    contract_date DATE NOT NULL,
    completion_date DATE NULL,
    status ENUM('pending', 'approved', 'contracted', 'completed', 'cancelled') DEFAULT 'pending',
    
    -- Informações do comprador
    buyer_income DECIMAL(12,2) NULL,
    buyer_profession VARCHAR(255) NULL,
    buyer_documents JSON NULL,
    
    -- Informações contratuais
    contract_terms TEXT NULL,
    special_conditions TEXT NULL,
    commission_rate DECIMAL(5,2) DEFAULT 3.00,
    commission_amount DECIMAL(12,2) NULL,
    
    -- Documentação
    contract_file VARCHAR(500) NULL,
    deed_file VARCHAR(500) NULL,
    other_documents JSON NULL,
    
    -- Observações e histórico
    notes TEXT NULL,
    admin_notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE,
    FOREIGN KEY (buyer_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (seller_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (broker_id) REFERENCES brokers(id) ON DELETE CASCADE,
    INDEX idx_buyer_id (buyer_id),
    INDEX idx_seller_id (seller_id),
    INDEX idx_broker_id (broker_id),
    INDEX idx_status (status),
    INDEX idx_contract_date (contract_date)
);

-- Tabela de aluguéis de imóveis
CREATE TABLE property_rentals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    property_id INT NOT NULL,
    tenant_id INT NOT NULL,
    landlord_id INT NOT NULL,
    broker_id INT NOT NULL,
    monthly_rent DECIMAL(12,2) NOT NULL,
    security_deposit DECIMAL(12,2) NOT NULL,
    admin_fee DECIMAL(12,2) NULL,
    rental_period_months INT NOT NULL DEFAULT 12,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    status ENUM('pending', 'active', 'expired', 'terminated', 'cancelled') DEFAULT 'pending',
    
    -- Informações do inquilino
    tenant_income DECIMAL(12,2) NULL,
    tenant_profession VARCHAR(255) NULL,
    tenant_references JSON NULL,
    tenant_documents JSON NULL,
    
    -- Termos do contrato
    contract_terms TEXT NULL,
    special_conditions TEXT NULL,
    pets_allowed BOOLEAN DEFAULT FALSE,
    smoking_allowed BOOLEAN DEFAULT FALSE,
    subletting_allowed BOOLEAN DEFAULT FALSE,
    
    -- Informações financeiras
    commission_rate DECIMAL(5,2) DEFAULT 8.33,
    commission_amount DECIMAL(12,2) NULL,
    late_fee_rate DECIMAL(5,2) DEFAULT 2.00,
    
    -- Documentação
    contract_file VARCHAR(500) NULL,
    inventory_file VARCHAR(500) NULL,
    other_documents JSON NULL,
    
    -- Renovação automática
    auto_renewal BOOLEAN DEFAULT FALSE,
    renewal_terms TEXT NULL,
    
    -- Observações e histórico
    notes TEXT NULL,
    admin_notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE,
    FOREIGN KEY (tenant_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (landlord_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (broker_id) REFERENCES brokers(id) ON DELETE CASCADE,
    INDEX idx_tenant_id (tenant_id),
    INDEX idx_landlord_id (landlord_id),
    INDEX idx_broker_id (broker_id),
    INDEX idx_status (status),
    INDEX idx_start_date (start_date),
    INDEX idx_end_date (end_date)
);

-- Tabela de pagamentos (para aluguéis e compras)
CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    rental_id INT NULL,
    purchase_id INT NULL,
    payer_id INT NOT NULL,
    amount DECIMAL(12,2) NOT NULL,
    payment_type ENUM('rent', 'security_deposit', 'admin_fee', 'down_payment', 'installment', 'commission', 'late_fee', 'other') NOT NULL,
    payment_method ENUM('cash', 'bank_transfer', 'credit_card', 'debit_card', 'check', 'pix') NOT NULL,
    due_date DATE NOT NULL,
    payment_date DATE NULL,
    status ENUM('pending', 'paid', 'overdue', 'cancelled', 'refunded') DEFAULT 'pending',
    
    -- Informações da transação
    transaction_id VARCHAR(255) NULL,
    payment_reference VARCHAR(255) NULL,
    bank_slip_code VARCHAR(255) NULL,
    
    -- Observações
    description TEXT NULL,
    notes TEXT NULL,
    admin_notes TEXT NULL,
    
    -- Comprovantes
    receipt_file VARCHAR(500) NULL,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (rental_id) REFERENCES property_rentals(id) ON DELETE CASCADE,
    FOREIGN KEY (purchase_id) REFERENCES property_purchases(id) ON DELETE CASCADE,
    FOREIGN KEY (payer_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_rental_id (rental_id),
    INDEX idx_purchase_id (purchase_id),
    INDEX idx_payer_id (payer_id),
    INDEX idx_due_date (due_date),
    INDEX idx_payment_date (payment_date),
    INDEX idx_status (status),
    INDEX idx_payment_type (payment_type)
);

-- Tabela de agendamentos de visitas (sistema de agendamentos)
CREATE TABLE agendamentos (
    id VARCHAR(50) PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    casa VARCHAR(255) NOT NULL,
    corretor VARCHAR(255) NOT NULL,
    data_visita DATE NOT NULL,
    horario TIME NOT NULL,
    observacoes TEXT NULL,
    data_agendamento TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('agendado', 'confirmado', 'concluido', 'cancelado') DEFAULT 'agendado',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_email (email),
    INDEX idx_data_visita (data_visita),
    INDEX idx_status (status),
    INDEX idx_corretor (corretor)
);

-- Tabela de compras realizadas por clientes (compra_cliente)
CREATE TABLE compra_cliente (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    property_id INT NOT NULL,
    corretor_id INT NOT NULL,
    valor_total DECIMAL(15,2) NOT NULL,
    valor_entrada DECIMAL(15,2) NULL,
    valor_financiado DECIMAL(15,2) NULL,
    banco_financiador VARCHAR(255) NULL,
    forma_pagamento ENUM('avista', 'financiamento', 'consorcio', 'outro') DEFAULT 'avista',
    data_compra DATE NOT NULL,
    status ENUM('pendente', 'aprovada', 'concluida', 'cancelada') DEFAULT 'pendente',
    observacoes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE,
    FOREIGN KEY (corretor_id) REFERENCES brokers(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_property_id (property_id),
    INDEX idx_corretor_id (corretor_id),
    INDEX idx_status (status)
);

-- Exemplo de inserção de compra
INSERT INTO compra_cliente (user_id, property_id, corretor_id, valor_total, valor_entrada, valor_financiado, banco_financiador, forma_pagamento, data_compra, status, observacoes)
VALUES (5, 1, 1, 220000.00, 44000.00, 176000.00, 'Banco do Brasil', 'financiamento', '2024-01-15', 'concluida', 'Compra realizada com sucesso pelo cliente Carlos Oliveira.');

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
INSERT INTO properties (broker_id, title, description, property_type, transaction_type, price, rent_price, area_sqm, bedrooms, bathrooms, garage_spaces, address, neighborhood, city, state, zip_code, status) 
VALUES 
(1, 'Casa Jardim das Flores', 'Bela casa em localização privilegiada com amplo jardim e acabamentos de primeira qualidade.', 'house', 'sale', 220000.00, NULL, 180.50, 3, 2, 2, 'Rua das Flores, 123', 'Jardim das Flores', 'Porto Alegre', 'RS', '91234-567', 'active'),
(2, 'Apartamento Vista Alegre', 'Apartamento moderno com vista panorâmica, próximo ao centro da cidade.', 'apartment', 'both', 310000.00, 2500.00, 120.00, 2, 2, 1, 'Av. Vista Alegre, 456', 'Vista Alegre', 'Porto Alegre', 'RS', '91345-678', 'active'),
(3, 'Casa Bela Vista', 'Casa espaçosa ideal para famílias, com quintal amplo e churrasqueira.', 'house', 'sale', 199000.00, NULL, 200.00, 4, 3, 2, 'Rua Bela Vista, 789', 'Bela Vista', 'Canoas', 'RS', '92123-456', 'active'),
(1, 'Casa para Aluguel Centro', 'Casa disponível para locação no centro da cidade, totalmente mobiliada.', 'house', 'rent', NULL, 1800.00, 140.00, 3, 2, 1, 'Rua do Centro, 321', 'Centro', 'Porto Alegre', 'RS', '90000-000', 'active'),
(2, 'Apartamento Cobertura', 'Cobertura duplex com terraço e vista panorâmica da cidade.', 'apartment', 'sale', 450000.00, NULL, 250.00, 4, 3, 2, 'Rua dos Altos, 987', 'Moinhos de Vento', 'Porto Alegre', 'RS', '91000-000', 'active');

-- Inserir alguns usuários clientes para os exemplos
INSERT INTO users (first_name, last_name, email, password, user_type, status, email_verified, phone, cpf) 
VALUES 
('Carlos', 'Oliveira', 'carlos.oliveira@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'active', TRUE, '51987654321', '123.456.789-01'),
('Ana', 'Ferreira', 'ana.ferreira@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'active', TRUE, '51876543210', '234.567.890-12'),
('Roberto', 'Lima', 'roberto.lima@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'active', TRUE, '51765432109', '345.678.901-23'),
('Patrícia', 'Souza', 'patricia.souza@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'active', TRUE, '51654321098', '456.789.012-34');

-- Inserir exemplos de compras
INSERT INTO property_purchases (property_id, buyer_id, seller_id, broker_id, purchase_price, down_payment, financing_amount, financing_bank, payment_method, contract_date, status, buyer_income, buyer_profession, commission_rate, commission_amount, contract_terms, notes) 
VALUES 
(1, 5, 2, 1, 220000.00, 44000.00, 176000.00, 'Banco do Brasil', 'financing', '2024-01-15', 'completed', 8000.00, 'Engenheiro', 3.00, 6600.00, 'Contrato de compra e venda com financiamento habitacional.', 'Primeira casa do cliente, processo tranquilo.'),
(3, 6, 4, 3, 199000.00, 60000.00, 139000.00, 'Caixa Econômica Federal', 'financing', '2024-02-20', 'contracted', 7500.00, 'Professora', 3.00, 5970.00, 'Compra à vista com financiamento complementar.', 'Cliente muito satisfeita com a escolha.'),
(5, 7, 1, 2, 450000.00, 135000.00, 315000.00, 'Banco Santander', 'financing', '2024-03-10', 'pending', 15000.00, 'Médico', 3.50, 15750.00, 'Cobertura de luxo, financiamento especial.', 'Aguardando aprovação final do banco.');

-- Exemplo adicional de compra
INSERT INTO property_purchases (property_id, buyer_id, seller_id, broker_id, purchase_price, down_payment, financing_amount, financing_bank, payment_method, contract_date, status, buyer_income, buyer_profession, commission_rate, commission_amount, contract_terms, notes)
VALUES (2, 8, 3, 2, 310000.00, 62000.00, 248000.00, 'Itaú', 'financing', '2024-04-05', 'approved', 9000.00, 'Analista de Sistemas', 3.00, 9300.00, 'Contrato padrão com financiamento bancário.', 'Compra realizada sem intercorrências.');

-- Inserir exemplos de aluguéis
INSERT INTO property_rentals (property_id, tenant_id, landlord_id, broker_id, monthly_rent, security_deposit, admin_fee, rental_period_months, start_date, end_date, status, tenant_income, tenant_profession, commission_rate, commission_amount, contract_terms, pets_allowed, notes) 
VALUES 
(2, 8, 3, 2, 2500.00, 5000.00, 500.00, 12, '2024-01-01', '2024-12-31', 'active', 6000.00, 'Analista de Sistemas', 8.33, 2500.00, 'Contrato de locação residencial de 12 meses.', TRUE, 'Inquilino pontual, permite animais pequenos.'),
(4, 5, 2, 1, 1800.00, 3600.00, 360.00, 24, '2024-02-15', '2026-02-14', 'active', 4500.00, 'Engenheiro', 8.33, 1500.00, 'Locação por 24 meses com desconto no aluguel.', FALSE, 'Casa mobiliada, contrato longo prazo.');

-- Inserir exemplos de agendamentos
INSERT INTO agendamentos (id, nome, email, telefone, casa, corretor, data_visita, horario, observacoes, data_agendamento, status) 
VALUES 
('68b4838270277', 'Miguel Pohlmann', 'miguel@gmail.com', '(51) 99929-8592', 'Casa em Parobé', 'Maria Santos', '2025-09-03', '08:00:00', '', '2025-08-31 14:16:50', 'agendado'),
('example001', 'Carlos Oliveira', 'carlos.oliveira@gmail.com', '(51) 98765-4321', 'Casa em Santo Antônio da Patrulha', 'João borges', '2025-09-05', '09:00:00', 'Interessado em financiamento', '2025-08-31 10:30:00', 'agendado'),
('example002', 'Ana Ferreira', 'ana.ferreira@gmail.com', '(51) 87654-3210', 'Casa em Taquara Flores da Cunha', 'Maria Santos', '2025-09-06', '14:00:00', 'Primeira visita', '2025-08-31 11:45:00', 'confirmado'),
('example003', 'Roberto Lima', 'roberto.lima@gmail.com', '(51) 76543-2109', 'Casa em Taquara Santa Terezinha', 'Pedro Costa', '2025-09-07', '16:00:00', 'Visita com a família', '2025-08-31 15:20:00', 'agendado');

-- Inserir exemplos de pagamentos
INSERT INTO payments (rental_id, purchase_id, payer_id, amount, payment_type, payment_method, due_date, payment_date, status, description, transaction_id) 
VALUES 
-- Pagamentos de aluguel
(1, NULL, 8, 2500.00, 'rent', 'pix', '2024-01-05', '2024-01-03', 'paid', 'Aluguel Janeiro/2024 - Apartamento Vista Alegre', 'PIX20240103001'),
(1, NULL, 8, 2500.00, 'rent', 'pix', '2024-02-05', '2024-02-02', 'paid', 'Aluguel Fevereiro/2024 - Apartamento Vista Alegre', 'PIX20240202001'),
(1, NULL, 8, 2500.00, 'rent', 'bank_transfer', '2024-03-05', NULL, 'pending', 'Aluguel Março/2024 - Apartamento Vista Alegre', NULL),
(2, NULL, 5, 1800.00, 'rent', 'bank_transfer', '2024-02-15', '2024-02-14', 'paid', 'Aluguel Fevereiro/2024 - Casa Centro', 'TED20240214001'),
(2, NULL, 5, 1800.00, 'rent', 'bank_transfer', '2024-03-15', NULL, 'pending', 'Aluguel Março/2024 - Casa Centro', NULL),

-- Pagamentos de compra
(NULL, 1, 5, 44000.00, 'down_payment', 'bank_transfer', '2024-01-15', '2024-01-15', 'paid', 'Entrada - Casa Jardim das Flores', 'TED20240115001'),
(NULL, 1, 5, 1500.00, 'installment', 'bank_transfer', '2024-02-15', '2024-02-15', 'paid', 'Parcela 1/120 - Financiamento Casa Jardim', 'DEB20240215001'),
(NULL, 2, 6, 60000.00, 'down_payment', 'bank_transfer', '2024-02-20', '2024-02-20', 'paid', 'Entrada - Casa Bela Vista', 'TED20240220001'),
(NULL, 3, 7, 135000.00, 'down_payment', 'bank_transfer', '2024-03-10', NULL, 'pending', 'Entrada - Cobertura Moinhos de Vento', NULL),

-- Comissões
(NULL, 1, 1, 6600.00, 'commission', 'bank_transfer', '2024-01-30', '2024-01-30', 'paid', 'Comissão venda Casa Jardim das Flores', 'COM20240130001'),
(1, NULL, 2, 2500.00, 'commission', 'bank_transfer', '2024-01-10', '2024-01-10', 'paid', 'Comissão locação Apartamento Vista Alegre', 'COM20240110001');

-- ========================================
-- CONSULTAS ÚTEIS PARA O SISTEMA
-- ========================================

-- Consultas úteis para o sistema de agendamentos

-- Buscar agendamentos por status
-- SELECT * FROM agendamentos WHERE status = 'agendado';

-- Buscar agendamentos por corretor
-- SELECT * FROM agendamentos WHERE corretor = 'João borges';

-- Buscar agendamentos por data
-- SELECT * FROM agendamentos WHERE data_visita BETWEEN '2025-09-01' AND '2025-09-30';

-- Buscar agendamentos por casa
-- SELECT * FROM agendamentos WHERE casa LIKE '%Taquara%';

-- Estatísticas de agendamentos por corretor
-- SELECT corretor, COUNT(*) as total_agendamentos, 
--        COUNT(CASE WHEN status = 'confirmado' THEN 1 END) as confirmados,
--        COUNT(CASE WHEN status = 'concluido' THEN 1 END) as concluidos
-- FROM agendamentos 
-- GROUP BY corretor;

-- Consultas úteis para propriedades e corretores

-- Buscar propriedades disponíveis por corretor
-- SELECT p.title, p.price, p.rent_price, u.first_name, u.last_name
-- FROM properties p 
-- JOIN brokers b ON p.broker_id = b.id
-- JOIN users u ON b.user_id = u.id
-- WHERE p.status = 'active';

-- Relatório de vendas por corretor
-- SELECT u.first_name, u.last_name, COUNT(pp.id) as total_vendas, SUM(pp.purchase_price) as valor_total
-- FROM brokers b
-- JOIN users u ON b.user_id = u.id
-- LEFT JOIN property_purchases pp ON b.id = pp.broker_id AND pp.status = 'completed'
-- GROUP BY b.id, u.first_name, u.last_name;

-- Relatório de locações por corretor
-- SELECT u.first_name, u.last_name, COUNT(pr.id) as total_locacoes, SUM(pr.monthly_rent) as renda_mensal_total
-- FROM brokers b
-- JOIN users u ON b.user_id = u.id
-- LEFT JOIN property_rentals pr ON b.id = pr.broker_id AND pr.status = 'active'
-- GROUP BY b.id, u.first_name, u.last_name;

-- Buscar pagamentos em atraso
-- SELECT p.*, u.first_name, u.last_name
-- FROM payments p
-- JOIN users u ON p.payer_id = u.id
-- WHERE p.status = 'pending' AND p.due_date < CURDATE();

-- Propriedades mais visualizadas
-- SELECT title, views_count, favorites_count
-- FROM properties 
-- WHERE status = 'active'
-- ORDER BY views_count DESC, favorites_count DESC
-- LIMIT 10;
