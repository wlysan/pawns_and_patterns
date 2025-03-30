-- Tabela de Categorias de Produtos
CREATE TABLE IF NOT EXISTS product_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    parent_id INT DEFAULT 0,
    attributes TEXT, -- Irá armazenar metadados serializados
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    status ENUM('Ativo', 'Inativo', 'Pendente', 'Desabilitado', 'Em análise') DEFAULT 'Ativo',
    is_deleted BOOLEAN DEFAULT FALSE,
    deleted_at TIMESTAMP NULL,
    
    -- Índices para otimização de consultas
    INDEX idx_parent (parent_id),
    INDEX idx_status (status),
    INDEX idx_deleted (is_deleted)
);

-- Tabela de relação entre Produtos e Categorias (para múltiplas categorias)
CREATE TABLE IF NOT EXISTS product_category_relations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    category_id INT NOT NULL,
    is_primary BOOLEAN DEFAULT FALSE, -- Marca a categoria principal do produto
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('Ativo', 'Inativo') DEFAULT 'Ativo',
    is_deleted BOOLEAN DEFAULT FALSE,
    deleted_at TIMESTAMP NULL,
    
    -- Índices para otimização de consultas
    UNIQUE INDEX idx_product_category (product_id, category_id),
    INDEX idx_category (category_id),
    INDEX idx_product (product_id),
    INDEX idx_deleted (is_deleted)
);