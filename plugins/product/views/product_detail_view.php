<?php
/**
 * View de Detalhes do Produto
 * Exibe informações detalhadas de um produto específico
 */

// Obter dados do produto
$product = $view_data['current_product'] ?? [];

// Verificar se o produto existe
if (empty($product)) {
    echo '<div class="alert alert-danger">Product not found</div>';
    return;
}

// Imagens do produto
$images = $product['images'] ?? [];
$main_image = !empty($images) ? $images[0] : '/assets/images/no-image.jpg';

// Atributos do produto
$attributes = $product['attributes'] ?? [];

// Produtos relacionados
$related_products = $view_data['related_products'] ?? [];

// Funções auxiliares
function format_price($price) {
    return '€' . number_format($price, 2);
}

function get_status_badge_class($status) {
    switch ($status) {
        case 'Ativo':
            return 'bg-success';
        case 'Pendente':
            return 'bg-warning text-dark';
        case 'Inativo':
            return 'bg-secondary';
        case 'Esgotado':
            return 'bg-danger';
        case 'Em promoção':
            return 'bg-info text-dark';
        case 'Descontinuado':
            return 'bg-dark';
        case 'Pré-venda':
            return 'bg-primary';
        default:
            return 'bg-light text-dark';
    }
}
?>

<div class="row">
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <a href="/index.php/products" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Products
            </a>
            <div>
                <a href="/index.php/product_edit/<?php echo $product['id']; ?>" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit Product
                </a>
                <button type="button" class="btn btn-danger ms-2" data-bs-toggle="modal" data-bs-target="#deleteConfirmModal">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Imagens e galeria -->
    <div class="col-md-6 mb-4">
        <div class="product-main-image mb-3">
            <img src="<?php echo htmlspecialchars($main_image); ?>" id="mainProductImage" 
                 class="img-fluid rounded" alt="<?php echo htmlspecialchars($product['name']); ?>">
        </div>
        
        <?php if (count($images) > 1): ?>
            <div class="row product-thumbnails">
                <?php foreach ($images as $index => $image): ?>
                    <div class="col-3 mb-3">
                        <img src="<?php echo htmlspecialchars($image); ?>" 
                             class="img-thumbnail product-thumb <?php echo $index === 0 ? 'active' : ''; ?>" 
                             alt="Thumbnail <?php echo $index + 1; ?>"
                             onclick="document.getElementById('mainProductImage').src = this.src; document.querySelectorAll('.product-thumb').forEach(thumb => thumb.classList.remove('active')); this.classList.add('active');">
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Informações do produto -->
    <div class="col-md-6">
        <div class="product-details">
            <h1 class="product-title mb-2"><?php echo htmlspecialchars($product['name']); ?></h1>
            
            <div class="product-meta mb-3">
                <span class="text-muted">SKU: <?php echo htmlspecialchars($product['sku']); ?></span>
                <span class="mx-2">|</span>
                <span class="badge <?php echo get_status_badge_class($product['status']); ?>">
                    <?php echo $product['status']; ?>
                </span>
                <?php if ($product['featured']): ?>
                    <span class="badge bg-warning text-dark ms-2">Featured</span>
                <?php endif; ?>
            </div>
            
            <div class="product-price mb-4">
                <?php if (!empty($product['sale_price']) && $product['sale_price'] < $product['price']): ?>
                    <span class="text-muted text-decoration-line-through me-2">
                        <?php echo format_price($product['price']); ?>
                    </span>
                    <span class="current-price text-danger">
                        <?php echo format_price($product['sale_price']); ?>
                    </span>
                    <span class="badge bg-danger ms-2">
                        <?php 
                        $discount = round(($product['price'] - $product['sale_price']) / $product['price'] * 100);
                        echo $discount . '% OFF';
                        ?>
                    </span>
                <?php else: ?>
                    <span class="current-price">
                        <?php echo format_price($product['price']); ?>
                    </span>
                <?php endif; ?>
            </div>
            
            <?php if (!empty($product['short_description'])): ?>
                <div class="product-short-description mb-4">
                    <p><?php echo htmlspecialchars($product['short_description']); ?></p>
                </div>
            <?php endif; ?>
            
            <div class="product-stock mb-4">
                <span class="stock-label">Availability:</span>
                <?php if ($product['stock_quantity'] > 0): ?>
                    <span class="text-success">In Stock (<?php echo $product['stock_quantity']; ?> available)</span>
                <?php else: ?>
                    <span class="text-danger">Out of Stock</span>
                <?php endif; ?>
            </div>
            
            <?php if (!empty($product['categories'])): ?>
                <div class="product-categories mb-4">
                    <span class="categories-label">Categories:</span>
                    <?php foreach ($product['categories'] as $category): ?>
                        <span class="badge bg-light text-dark me-1">
                            <?php echo htmlspecialchars($category['name']); ?>
                            <?php if (isset($category['is_primary']) && $category['is_primary']): ?>
                                <i class="fas fa-star text-warning ms-1" title="Primary Category"></i>
                            <?php endif; ?>
                        </span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($attributes)): ?>
                <div class="product-attributes mb-4">
                    <h5>Product Attributes</h5>
                    <table class="table table-sm">
                        <tbody>
                            <?php foreach ($attributes as $key => $value): ?>
                                <tr>
                                    <th scope="row" style="width: 40%;"><?php echo htmlspecialchars($key); ?></th>
                                    <td><?php echo htmlspecialchars($value); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($product['weight']) || !empty($product['dimensions'])): ?>
                <div class="product-shipping-info mb-4">
                    <h5>Shipping Information</h5>
                    <table class="table table-sm">
                        <tbody>
                            <?php if (!empty($product['weight'])): ?>
                                <tr>
                                    <th scope="row" style="width: 40%;">Weight</th>
                                    <td><?php echo $product['weight']; ?> g</td>
                                </tr>
                            <?php endif; ?>
                            <?php if (!empty($product['dimensions'])): ?>
                                <tr>
                                    <th scope="row" style="width: 40%;">Dimensions (LxWxH)</th>
                                    <td><?php echo htmlspecialchars($product['dimensions']); ?></td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Descrição completa do produto -->
<?php if (!empty($product['description'])): ?>
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Product Description</h5>
                </div>
                <div class="card-body">
                    <div class="product-description">
                        <?php echo nl2br(htmlspecialchars($product['description'])); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Produtos relacionados -->
<?php if (!empty($related_products)): ?>
    <div class="row mt-5">
        <div class="col-12">
            <h3 class="mb-4">Related Products</h3>
            <div class="row">
                <?php foreach ($related_products as $related): ?>
                    <div class="col-md-3 mb-4">
                        <div class="card h-100 product-card">
                            <div class="product-img-container">
                                <a href="/index.php/product_detail/<?php echo $related['id']; ?>">
                                    <img src="<?php echo htmlspecialchars($related['main_image'] ?? '/assets/images/no-image.jpg'); ?>" 
                                         class="card-img-top product-img" alt="<?php echo htmlspecialchars($related['name']); ?>">
                                </a>
                            </div>
                            
                            <div class="card-body">
                                <h5 class="card-title">
                                    <a href="/index.php/product_detail/<?php echo $related['id']; ?>" class="product-link">
                                        <?php echo htmlspecialchars($related['name']); ?>
                                    </a>
                                </h5>
                                
                                <div class="product-price">
                                    <?php if (!empty($related['sale_price']) && $related['sale_price'] < $related['price']): ?>
                                        <span class="text-muted text-decoration-line-through me-2">
                                            <?php echo format_price($related['price']); ?>
                                        </span>
                                        <span class="text-danger fw-bold">
                                            <?php echo format_price($related['sale_price']); ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="fw-bold">
                                            <?php echo format_price($related['price']); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Modal de confirmação de exclusão -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the product "<strong><?php echo htmlspecialchars($product['name']); ?></strong>"?</p>
                <p class="text-danger">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="post" action="/index.php/product_delete/<?php echo $product['id']; ?>">
                    <input type="hidden" name="confirm_delete" value="1">
                    <button type="submit" class="btn btn-danger">Delete Product</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.product-main-image {
    text-align: center;
    border: 1px solid #dee2e6;
    border-radius: 0.25rem;
    padding: 10px;
    background-color: #fff;
}

.product-main-image img {
    max-height: 400px;
    object-fit: contain;
}

.product-thumb {
    cursor: pointer;
    height: 80px;
    object-fit: cover;
    transition: all 0.2s;
}

.product-thumb.active {
    border-color: #0d6efd;
}

.current-price {
    font-size: 1.8rem;
    font-weight: 600;
}

.stock-label, .categories-label {
    font-weight: 600;
    margin-right: 10px;
}

.product-title {
    font-size: 2rem;
    font-weight: 700;
}

.product-meta {
    font-size: 0.9rem;
}

.product-description {
    line-height: 1.7;
}

.product-attributes th {
    font-weight: 600;
}
</style>