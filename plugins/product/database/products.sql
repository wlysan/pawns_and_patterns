-- Tabela principal de Produtos
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sku VARCHAR(50) NOT NULL UNIQUE,  -- Código único do produto
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT,
    short_description VARCHAR(255),
    price DECIMAL(10, 2) NOT NULL,
    sale_price DECIMAL(10, 2),
    stock_quantity INT DEFAULT 0,
    weight DECIMAL(10, 2),           -- Peso em gramas
    dimensions VARCHAR(100),         -- Formato: LxWxH em cm
    attributes TEXT,                 -- Atributos serializados (JSON ou PHP serialize)
    images TEXT,                     -- URLs das imagens serializadas
    featured BOOLEAN DEFAULT FALSE,  -- Produto em destaque
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    status ENUM('Ativo', 'Inativo', 'Pendente', 'Esgotado', 'Novo', 'Em promoção', 'Descontinuado', 'Pré-venda') DEFAULT 'Pendente',
    is_deleted BOOLEAN DEFAULT FALSE,
    deleted_at TIMESTAMP NULL,
    
    -- Índices para otimização de consultas
    INDEX idx_sku (sku),
    INDEX idx_name (name),
    INDEX idx_price (price),
    INDEX idx_status (status),
    INDEX idx_featured (featured),
    INDEX idx_deleted (is_deleted)
);

-- A tabela product_category_relations já foi definida em categories.sql
-- Para referência, ela estabelece a relação many-to-many entre produtos e categorias:
-- CREATE TABLE IF NOT EXISTS product_category_relations (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     product_id INT NOT NULL,
--     category_id INT NOT NULL,
--     is_primary BOOLEAN DEFAULT FALSE,
--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
--     status ENUM('Ativo', 'Inativo') DEFAULT 'Ativo',
--     is_deleted BOOLEAN DEFAULT FALSE,
--     deleted_at TIMESTAMP NULL,
--     
--     UNIQUE INDEX idx_product_category (product_id, category_id),
--     INDEX idx_category (category_id),
--     INDEX idx_product (product_id),
--     INDEX idx_deleted (is_deleted)
-- );

-- Tabela de variações de produtos (para produtos com opções como tamanho, cor, etc.)
CREATE TABLE IF NOT EXISTS product_variations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    sku VARCHAR(50) NOT NULL UNIQUE,
    name VARCHAR(255) NOT NULL,
    price_adjustment DECIMAL(10, 2) DEFAULT 0.00,  -- Ajuste de preço em relação ao produto principal
    stock_quantity INT DEFAULT 0,
    attributes TEXT,                 -- Atributos serializados (tamanho, cor, etc.)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    status ENUM('Ativo', 'Inativo', 'Esgotado') DEFAULT 'Ativo',
    is_deleted BOOLEAN DEFAULT FALSE,
    deleted_at TIMESTAMP NULL,
    
    FOREIGN KEY (product_id) REFERENCES products(id),
    INDEX idx_product_id (product_id),
    INDEX idx_status (status),
    INDEX idx_deleted (is_deleted)
);

-- Tabela para armazenar tags de produtos
CREATE TABLE IF NOT EXISTS product_tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    slug VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('Ativo', 'Inativo') DEFAULT 'Ativo',
    is_deleted BOOLEAN DEFAULT FALSE,
    deleted_at TIMESTAMP NULL,
    
    INDEX idx_name (name),
    INDEX idx_status (status),
    INDEX idx_deleted (is_deleted)
);

-- Tabela de relacionamento entre produtos e tags
CREATE TABLE IF NOT EXISTS product_tag_relations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    tag_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_deleted BOOLEAN DEFAULT FALSE,
    deleted_at TIMESTAMP NULL,
    
    UNIQUE INDEX idx_product_tag (product_id, tag_id),
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (tag_id) REFERENCES product_tags(id),
    INDEX idx_deleted (is_deleted)
);