-- Tabela de configurações do sistema
CREATE TABLE IF NOT EXISTS system_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT NOT NULL,
    setting_group VARCHAR(50) NOT NULL,
    setting_description VARCHAR(255),
    field_type ENUM('text', 'textarea', 'number', 'boolean', 'select', 'date', 'color') NOT NULL DEFAULT 'text',
    field_options TEXT, -- Para campos do tipo 'select', armazena as opções em formato JSON
    is_public BOOLEAN DEFAULT FALSE, -- Indica se a configuração é acessível publicamente
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    created_by INT, -- ID do administrador que criou a configuração
    updated_by INT, -- ID do administrador que atualizou a configuração pela última vez
    status ENUM('Ativo', 'Inativo', 'Pendente', 'Desabilitado') DEFAULT 'Ativo',
    is_deleted BOOLEAN DEFAULT FALSE,
    deleted_at TIMESTAMP NULL,
    
    -- Índices para otimização de consultas
    INDEX idx_setting_group (setting_group),
    INDEX idx_status (status),
    INDEX idx_is_public (is_public),
    INDEX idx_is_deleted (is_deleted)
);

-- Tabela de log de atividades de administrador
CREATE TABLE IF NOT EXISTS admin_activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL, -- ID do administrador
    action VARCHAR(100) NOT NULL, -- Ex: 'login', 'update_settings', 'create_product', etc.
    entity_type VARCHAR(50) NULL, -- Tipo da entidade afetada, ex: 'product', 'order', 'customer', etc.
    entity_id INT NULL, -- ID da entidade afetada, se aplicável
    description TEXT, -- Descrição detalhada da atividade
    ip_address VARCHAR(45) NOT NULL, -- Endereço IP do administrador
    user_agent TEXT, -- Navegador/dispositivo usado
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    additional_data JSON, -- Dados adicionais em formato JSON, como valores anteriores
    status ENUM('Sucesso', 'Erro', 'Aviso') DEFAULT 'Sucesso',
    is_deleted BOOLEAN DEFAULT FALSE,
    deleted_at TIMESTAMP NULL,
    
    -- Índices para otimização de consultas
    INDEX idx_user_id (user_id),
    INDEX idx_action (action),
    INDEX idx_entity (entity_type, entity_id),
    INDEX idx_created_at (created_at),
    INDEX idx_status (status),
    INDEX idx_is_deleted (is_deleted)
);

-- Tabela para widgets do dashboard
CREATE TABLE IF NOT EXISTS dashboard_widgets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    widget_key VARCHAR(50) NOT NULL UNIQUE,
    widget_title VARCHAR(100) NOT NULL,
    widget_description TEXT,
    widget_type VARCHAR(50) NOT NULL, -- Ex: 'chart', 'stats', 'list', 'calendar', etc.
    widget_config JSON, -- Configurações do widget em formato JSON
    is_default BOOLEAN DEFAULT FALSE, -- Se é um widget padrão do sistema
    is_enabled BOOLEAN DEFAULT TRUE, -- Se está ativo e visível
    position INT, -- Ordem de exibição
    size VARCHAR(20) DEFAULT 'medium', -- Tamanho do widget: 'small', 'medium', 'large', 'full'
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    status ENUM('Ativo', 'Inativo', 'Desabilitado', 'Em desenvolvimento') DEFAULT 'Ativo',
    is_deleted BOOLEAN DEFAULT FALSE,
    deleted_at TIMESTAMP NULL,
    
    -- Índices para otimização de consultas
    INDEX idx_is_enabled (is_enabled),
    INDEX idx_status (status),
    INDEX idx_is_deleted (is_deleted)
);

-- Tabela para associar widgets a usuários administradores específicos
CREATE TABLE IF NOT EXISTS user_dashboard_widgets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    widget_id INT NOT NULL,
    is_enabled BOOLEAN DEFAULT TRUE,
    position INT, -- Posição personalizada para este usuário
    widget_config JSON, -- Configurações personalizadas para este usuário
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    status ENUM('Ativo', 'Inativo') DEFAULT 'Ativo',
    is_deleted BOOLEAN DEFAULT FALSE,
    deleted_at TIMESTAMP NULL,
    
    -- Chaves únicas e índices
    UNIQUE INDEX idx_user_widget (user_id, widget_id),
    INDEX idx_widget_id (widget_id),
    INDEX idx_is_enabled (is_enabled),
    INDEX idx_is_deleted (is_deleted),
    
    -- Restrições de chave estrangeira seriam adicionadas em produção
    -- FOREIGN KEY (user_id) REFERENCES users(id),
    -- FOREIGN KEY (widget_id) REFERENCES dashboard_widgets(id)
);

-- Dados iniciais para configurações do sistema
INSERT INTO system_settings 
(setting_key, setting_value, setting_group, setting_description, field_type, is_public) 
VALUES
('store_name', 'Paws&Patterns', 'general', 'Name of the store', 'text', TRUE),
('store_email', 'info@pawsandpatterns.ie', 'general', 'Primary email address for the store', 'text', TRUE),
('store_phone', '+353 1 123 4567', 'general', 'Primary phone number for the store', 'text', TRUE),
('store_address', '123 Pet Lane, Dublin, Ireland', 'general', 'Physical address of the store', 'textarea', TRUE),
('currency', 'EUR', 'general', 'Default currency', 'select', TRUE),
('tax_rate', '23', 'general', 'Default tax rate (VAT) percentage', 'number', FALSE),
('enable_stock_notifications', 'true', 'notifications', 'Send notifications for low stock', 'boolean', FALSE),
('low_stock_threshold', '5', 'notifications', 'Threshold for low stock alerts', 'number', FALSE),
('enable_customer_reviews', 'true', 'product', 'Allow customers to leave product reviews', 'boolean', TRUE),
('order_prefix', 'PP-', 'order', 'Prefix for order numbers', 'text', FALSE);

-- Dados iniciais para widgets do dashboard
INSERT INTO dashboard_widgets 
(widget_key, widget_title, widget_description, widget_type, is_default, is_enabled, position, size) 
VALUES
('sales_overview', 'Sales Overview', 'Chart showing sales over time', 'chart', TRUE, TRUE, 1, 'large'),
('recent_orders', 'Recent Orders', 'List of the most recent orders', 'list', TRUE, TRUE, 2, 'medium'),
('low_stock', 'Low Stock Products', 'Products that are low in stock', 'list', TRUE, TRUE, 3, 'medium'),
('order_stats', 'Order Statistics', 'Basic order statistics', 'stats', TRUE, TRUE, 4, 'small'),
('customer_stats', 'Customer Statistics', 'Basic customer statistics', 'stats', TRUE, TRUE, 5, 'small'),
('sales_by_category', 'Sales by Category', 'Pie chart showing sales by product category', 'chart', TRUE, TRUE, 6, 'medium');