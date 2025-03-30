<?php
/**
 * View de Listagem de Produtos
 * Exibe a lista de produtos com opções de filtragem e paginação
 */

// Dados da paginação
$pagination = $view_data['pagination'] ?? [];
$current_page = $pagination['current_page'] ?? 1;
$total_pages = $pagination['total_pages'] ?? 1;
$total_items = $pagination['total_items'] ?? 0;

// Dados de busca/filtro
$search = $view_data['search'] ?? '';
$search_field = $view_data['search_field'] ?? 'name';
$selected_category = $view_data['selected_category'] ?? '';

// Dados de ordenação
$sort_by = $view_data['sort_by'] ?? 'created_at';
$sort_order = $view_data['sort_order'] ?? 'desc';
?>

<div class="row mb-4">
    <div class="col-md-6">
        <a href="/index.php/product_add" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Product
        </a>
    </div>
    <div class="col-md-6">
        <form method="get" action="/index.php/products" class="d-flex">
            <select name="search_field" class="form-select me-2" style="width: auto;">
                <option value="name" <?php echo $search_field === 'name' ? 'selected' : ''; ?>>Name</option>
                <option value="sku" <?php echo $search_field === 'sku' ? 'selected' : ''; ?>>SKU</option>
            </select>
            <input type="text" name="search" class="form-control me-2" placeholder="Search products..." 
                   value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="btn btn-outline-secondary">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <form method="get" action="/index.php/products" class="d-flex">
            <label for="categoryFilter" class="form-label me-2 mt-1">Category:</label>
            <select id="categoryFilter" name="category" class="form-select me-2" style="width: auto;">
                <option value="">All Categories</option>
                <?php foreach ($view_data['all_categories'] as $category): ?>
                    <option value="<?php echo $category['id']; ?>" 
                            <?php echo $selected_category == $category['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($category['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-outline-secondary">
                <i class="fas fa-filter"></i> Filter
            </button>
        </form>
    </div>
    <div class="col-md-6 text-end">
        <div class="d-flex justify-content-end align-items-center">
            <span class="me-3">Sort by:</span>
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <?php
                    $sort_labels = [
                        'name' => 'Name',
                        'price' => 'Price',
                        'sku' => 'SKU',
                        'created_at' => 'Date Added',
                        'stock_quantity' => 'Stock'
                    ];
                    echo $sort_labels[$sort_by] ?? 'Date Added';
                    echo ' (' . ($sort_order === 'asc' ? 'A-Z' : 'Z-A') . ')';
                    ?>
                </button>
                <ul class="dropdown-menu" aria-labelledby="sortDropdown">
                    <?php foreach ($sort_labels as $field => $label): ?>
                        <li>
                            <a class="dropdown-item <?php echo $sort_by === $field && $sort_order === 'asc' ? 'active' : ''; ?>" 
                               href="/index.php/products?sort_by=<?php echo $field; ?>&sort_order=asc<?php echo !empty($search) ? '&search=' . urlencode($search) . '&search_field=' . $search_field : ''; ?><?php echo !empty($selected_category) ? '&category=' . $selected_category : ''; ?>">
                                <?php echo $label; ?> (A-Z)
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item <?php echo $sort_by === $field && $sort_order === 'desc' ? 'active' : ''; ?>" 
                               href="/index.php/products?sort_by=<?php echo $field; ?>&sort_order=desc<?php echo !empty($search) ? '&search=' . urlencode($search) . '&search_field=' . $search_field : ''; ?><?php echo !empty($selected_category) ? '&category=' . $selected_category : ''; ?>">
                                <?php echo $label; ?> (Z-A)
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($view_data['products'])): ?>
    <div class="row">
        <?php foreach ($view_data['products'] as $product): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 product-card">
                    <?php if ($product['featured']): ?>
                        <div class="featured-badge">
                            <span class="badge bg-warning text-dark">Featured</span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($product['sale_price']) && $product['sale_price'] < $product['price']): ?>
                        <div class="sale-badge">
                            <span class="badge bg-danger">Sale</span>
                        </div>
                    <?php endif; ?>
                    
                    <div class="product-img-container">
                        <a href="/index.php/product_detail/<?php echo $product['id']; ?>">
                            <img src="<?php echo htmlspecialchars($product['main_image'] ?? '/assets/images/no-image.jpg'); ?>" 
                                 class="card-img-top product-img" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        </a>
                    </div>
                    
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="/index.php/product_detail/<?php echo $product['id']; ?>" class="product-link">
                                <?php echo htmlspecialchars($product['name']); ?>
                            </a>
                        </h5>
                        
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="product-price">
                                <?php if (!empty($product['sale_price']) && $product['sale_price'] < $product['price']): ?>
                                    <span class="text-muted text-decoration-line-through me-2">
                                        <?php echo format_price($product['price']); ?>
                                    </span>
                                    <span class="text-danger fw-bold">
                                        <?php echo format_price($product['sale_price']); ?>
                                    </span>
                                <?php else: ?>
                                    <span class="fw-bold">
                                        <?php echo format_price($product['price']); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <span class="badge <?php echo get_status_badge_class($product['status']); ?>">
                                <?php echo $product['status']; ?>
                            </span>
                        </div>
                        
                        <p class="card-text product-short-desc">
                            <?php echo htmlspecialchars($product['short_description'] ?? substr(strip_tags($product['description']), 0, 100) . '...'); ?>
                        </p>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">SKU: <?php echo htmlspecialchars($product['sku']); ?></small>
                            <small class="text-muted">Stock: <?php echo $product['stock_quantity']; ?></small>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-transparent">
                        <div class="d-flex justify-content-between">
                            <a href="/index.php/product_edit/<?php echo $product['id']; ?>" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="/index.php/product_detail/<?php echo $product['id']; ?>" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                    onclick="location.href='/index.php/product_delete/<?php echo $product['id']; ?>'">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <!-- Paginação -->
    <?php if ($total_pages > 1): ?>
        <nav aria-label="Product pagination">
            <ul class="pagination justify-content-center">
                <li class="page-item <?php echo $current_page <= 1 ? 'disabled' : ''; ?>">
                    <a class="page-link" href="/index.php/products?page=<?php echo $current_page - 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) . '&search_field=' . $search_field : ''; ?><?php echo !empty($selected_category) ? '&category=' . $selected_category : ''; ?><?php echo !empty($sort_by) ? '&sort_by=' . $sort_by . '&sort_order=' . $sort_order : ''; ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php echo $i === $current_page ? 'active' : ''; ?>">
                        <a class="page-link" href="/index.php/products?page=<?php echo $i; ?><?php echo !empty($search) ? '&search=' . urlencode($search) . '&search_field=' . $search_field : ''; ?><?php echo !empty($selected_category) ? '&category=' . $selected_category : ''; ?><?php echo !empty($sort_by) ? '&sort_by=' . $sort_by . '&sort_order=' . $sort_order : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    </li>
                <?php endfor; ?>
                
                <li class="page-item <?php echo $current_page >= $total_pages ? 'disabled' : ''; ?>">
                    <a class="page-link" href="/index.php/products?page=<?php echo $current_page + 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) . '&search_field=' . $search_field : ''; ?><?php echo !empty($selected_category) ? '&category=' . $selected_category : ''; ?><?php echo !empty($sort_by) ? '&sort_by=' . $sort_by . '&sort_order=' . $sort_order : ''; ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    <?php endif; ?>
    
    <div class="d-flex justify-content-between align-items-center mt-3">
        <div>
            <p class="text-muted mb-0">
                Showing <?php echo count($view_data['products']); ?> of <?php echo $total_items; ?> product(s)
            </p>
        </div>
        <div>
            <form method="get" action="/index.php/products" class="d-flex align-items-center">
                <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
                <input type="hidden" name="search_field" value="<?php echo htmlspecialchars($search_field); ?>">
                <input type="hidden" name="category" value="<?php echo htmlspecialchars($selected_category); ?>">
                <input type="hidden" name="sort_by" value="<?php echo htmlspecialchars($sort_by); ?>">
                <input type="hidden" name="sort_order" value="<?php echo htmlspecialchars($sort_order); ?>">
                
                <label for="per_page" class="me-2">Show:</label>
                <select id="per_page" name="per_page" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
                    <?php $per_page_options = [10, 20, 50, 100]; ?>
                    <?php foreach ($per_page_options as $option): ?>
                        <option value="<?php echo $option; ?>" <?php echo $pagination['per_page'] == $option ? 'selected' : ''; ?>>
                            <?php echo $option; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>
    </div>
<?php else: ?>
    <div class="alert alert-info">
        <?php if (!empty($search) || !empty($selected_category)): ?>
            <p>No products found matching your search criteria.</p>
            <a href="/index.php/products" class="btn btn-outline-primary">Clear filters</a>
        <?php else: ?>
            <p>No products found in the system.</p>
            <a href="/index.php/product_add" class="btn btn-primary">Add your first product</a>
        <?php endif; ?>
    </div>
<?php endif; ?>