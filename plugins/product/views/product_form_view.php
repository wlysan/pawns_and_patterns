<?php
/**
 * View de Formulário de Produto (Adição/Edição)
 * Exibe o formulário para adicionar/editar produtos
 */

// Obter dados atuais do produto (para edição)
$product = $view_data['current_product'] ?? [];
$product_id = $product['id'] ?? null;
$mode = $view_data['mode'] ?? 'add';
$is_edit_mode = ($mode === 'edit');

// Título do formulário
$form_title = $is_edit_mode ? 'Edit Product' : 'Add New Product';

// URL de ação do formulário
$form_action = $is_edit_mode 
    ? '/index.php/product_edit/' . $product_id
    : '/index.php/product_add';

// Obter categorias
$all_categories = $view_data['all_categories'] ?? [];

// Mensagens de erro
$errors = $view_data['errors'] ?? [];
?>

<div class="row">
    <div class="col-12 mb-4">
        <a href="/index.php/products" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Products
        </a>
    </div>
</div>

<form method="post" action="<?php echo $form_action; ?>" enctype="multipart/form-data" id="productForm">
    <div class="row">
        <div class="col-md-8">
            <!-- Informações Básicas -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Basic Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control <?php echo isset($errors['name']) ? 'is-invalid' : ''; ?>" 
                               id="name" name="name" required
                               value="<?php echo htmlspecialchars($product['name'] ?? ''); ?>">
                        <?php if (isset($errors['name'])): ?>
                            <div class="invalid-feedback">
                                <?php echo htmlspecialchars($errors['name']); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="sku" class="form-label">SKU <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?php echo isset($errors['sku']) ? 'is-invalid' : ''; ?>" 
                                   id="sku" name="sku" required
                                   value="<?php echo htmlspecialchars($product['sku'] ?? ''); ?>">
                            <?php if (isset($errors['sku'])): ?>
                                <div class="invalid-feedback">
                                    <?php echo htmlspecialchars($errors['sku']); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <?php
                                $statuses = ['Pendente', 'Ativo', 'Inativo', 'Esgotado', 'Novo', 'Em promoção', 'Descontinuado', 'Pré-venda'];
                                $current_status = $product['status'] ?? 'Pendente';
                                
                                foreach ($statuses as $status):
                                ?>
                                    <option value="<?php echo $status; ?>" <?php echo $current_status === $status ? 'selected' : ''; ?>>
                                        <?php echo $status; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="short_description" class="form-label">Short Description</label>
                        <input type="text" class="form-control" id="short_description" name="short_description" 
                               maxlength="255" placeholder="Brief product description (max 255 characters)"
                               value="<?php echo htmlspecialchars($product['short_description'] ?? ''); ?>">
                        <div class="form-text">A short summary that appears in product listings</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Full Description</label>
                        <textarea class="form-control" id="description" name="description" rows="6"><?php echo htmlspecialchars($product['description'] ?? ''); ?></textarea>
                    </div>
                </div>
            </div>
            
            <!-- Preços e Estoque -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Pricing & Inventory</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">Regular Price (€) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">€</span>
                                <input type="number" class="form-control <?php echo isset($errors['price']) ? 'is-invalid' : ''; ?>" 
                                       id="price" name="price" step="0.01" min="0" required
                                       value="<?php echo htmlspecialchars($product['price'] ?? ''); ?>">
                                <?php if (isset($errors['price'])): ?>
                                    <div class="invalid-feedback">
                                        <?php echo htmlspecialchars($errors['price']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="sale_price" class="form-label">Sale Price (€)</label>
                            <div class="input-group">
                                <span class="input-group-text">€</span>
                                <input type="number" class="form-control <?php echo isset($errors['sale_price']) ? 'is-invalid' : ''; ?>" 
                                       id="sale_price" name="sale_price" step="0.01" min="0"
                                       value="<?php echo htmlspecialchars($product['sale_price'] ?? ''); ?>">
                                <?php if (isset($errors['sale_price'])): ?>
                                    <div class="invalid-feedback">
                                        <?php echo htmlspecialchars($errors['sale_price']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="form-text">Leave empty for no sale price</div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="stock_quantity" class="form-label">Stock Quantity</label>
                            <input type="number" class="form-control <?php echo isset($errors['stock_quantity']) ? 'is-invalid' : ''; ?>" 
                                   id="stock_quantity" name="stock_quantity" min="0"
                                   value="<?php echo htmlspecialchars($product['stock_quantity'] ?? 0); ?>">
                            <?php if (isset($errors['stock_quantity'])): ?>
                                <div class="invalid-feedback">
                                    <?php echo htmlspecialchars($errors['stock_quantity']); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" id="featured" name="featured" value="1" 
                                       <?php echo isset($product['featured']) && $product['featured'] ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="featured">
                                    Featured Product
                                </label>
                                <div class="form-text">Featured products appear in special sections on the website</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Atributos do Produto -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Product Attributes</h5>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="addAttributeBtn">
                        <i class="fas fa-plus"></i> Add Attribute
                    </button>
                </div>
                <div class="card-body">
                    <div id="attributeContainer">
                        <?php
                        $attributes = $product['attributes'] ?? [];
                        if (!empty($attributes)):
                            $index = 0;
                            foreach ($attributes as $key => $value):
                        ?>
                            <div class="row attribute-row mb-2">
                                <div class="col-md-5">
                                    <input type="text" class="form-control" name="attr_key[]" placeholder="Attribute (e.g. Size, Color)"
                                           value="<?php echo htmlspecialchars($key); ?>">
                                </div>
                                <div class="col-md-5">
                                    <input type="text" class="form-control" name="attr_value[]" placeholder="Value"
                                           value="<?php echo htmlspecialchars($value); ?>">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-outline-danger remove-attribute-btn">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        <?php
                                $index++;
                            endforeach;
                        else:
                            // Exibir pelo menos uma linha vazia
                        ?>
                            <div class="row attribute-row mb-2">
                                <div class="col-md-5">
                                    <input type="text" class="form-control" name="attr_key[]" placeholder="Attribute (e.g. Size, Color)">
                                </div>
                                <div class="col-md-5">
                                    <input type="text" class="form-control" name="attr_value[]" placeholder="Value">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-outline-danger remove-attribute-btn">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div id="attributeTemplate" style="display: none;">
                        <div class="row attribute-row mb-2">
                            <div class="col-md-5">
                                <input type="text" class="form-control" name="attr_key[]" placeholder="Attribute (e.g. Size, Color)">
                            </div>
                            <div class="col-md-5">
                                <input type="text" class="form-control" name="attr_value[]" placeholder="Value">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-outline-danger remove-attribute-btn">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-text">
                        Add product-specific attributes like Size, Color, Material, etc.
                    </div>
                </div>
            </div>
            
            <!-- Dimensões e Peso -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Shipping Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="weight" class="form-label">Weight (g)</label>
                            <input type="number" class="form-control" id="weight" name="weight" step="0.01" min="0"
                                   value="<?php echo htmlspecialchars($product['weight'] ?? ''); ?>">
                            <div class="form-text">Product weight in grams</div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="dimensions" class="form-label">Dimensions (LxWxH)</label>
                            <input type="text" class="form-control" id="dimensions" name="dimensions" 
                                   placeholder="e.g. 20x15x5 cm"
                                   value="<?php echo htmlspecialchars($product['dimensions'] ?? ''); ?>">
                            <div class="form-text">Format: LxWxH in centimeters</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <!-- Categorias -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Categories</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($all_categories)): ?>
                        <div class="mb-3">
                            <label class="form-label">Select Categories <span class="text-danger">*</span></label>
                            <?php if (isset($errors['categories'])): ?>
                                <div class="alert alert-danger py-1">
                                    <?php echo htmlspecialchars($errors['categories']); ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="category-list">
                                <?php foreach ($all_categories as $category): ?>
                                    <?php 
                                    // Verificar se a categoria está selecionada
                                    $is_selected = in_array_r($category['id'], $product['categories'] ?? []);
                                    
                                    // Verificar se é a categoria primária
                                    $is_primary = isset($product['primary_category']) && $product['primary_category'] == $category['id'];
                                    ?>
                                    <div class="form-check">
                                        <input class="form-check-input category-checkbox" type="checkbox" 
                                               name="categories[]" value="<?php echo $category['id']; ?>" 
                                               id="category_<?php echo $category['id']; ?>"
                                               <?php echo $is_selected ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="category_<?php echo $category['id']; ?>">
                                            <?php echo htmlspecialchars($category['name']); ?>
                                        </label>
                                        
                                        <div class="form-check form-check-inline ms-2" style="<?php echo $is_selected ? '' : 'display: none;'; ?>">
                                            <input class="form-check-input primary-category-radio" type="radio" 
                                                   name="primary_category" value="<?php echo $category['id']; ?>" 
                                                   id="primary_<?php echo $category['id']; ?>"
                                                   <?php echo $is_primary ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="primary_<?php echo $category['id']; ?>">
                                                Primary
                                            </label>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <?php if (isset($errors['primary_category'])): ?>
                                <div class="alert alert-danger py-1 mt-2">
                                    <?php echo htmlspecialchars($errors['primary_category']); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            No categories found. <a href="/index.php/category_add">Create a category</a> first.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Imagens -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Product Images</h5>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="addImageBtn">
                        <i class="fas fa-plus"></i> Add Image
                    </button>
                </div>
                <div class="card-body">
                    <div id="imageContainer">
                        <?php
                        $images = $product['images'] ?? [];
                        if (!empty($images)):
                            foreach ($images as $index => $image_url):
                        ?>
                            <div class="row image-row mb-3 align-items-center">
                                <div class="col-md-3">
                                    <img src="<?php echo htmlspecialchars($image_url); ?>" class="img-thumbnail product-thumb" alt="Product Image">
                                </div>
                                <div class="col-md-7">
                                    <input type="text" class="form-control" name="images[]" placeholder="Image URL"
                                           value="<?php echo htmlspecialchars($image_url); ?>">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-outline-danger remove-image-btn">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        <?php
                            endforeach;
                        else:
                            // Exibir pelo menos uma linha vazia
                        ?>
                            <div class="row image-row mb-3 align-items-center">
                                <div class="col-md-3">
                                    <div class="empty-image-preview text-center">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <input type="text" class="form-control" name="images[]" placeholder="Image URL">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-outline-danger remove-image-btn">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div id="imageTemplate" style="display: none;">
                        <div class="row image-row mb-3 align-items-center">
                            <div class="col-md-3">
                                <div class="empty-image-preview text-center">
                                    <i class="fas fa-image text-muted"></i>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <input type="text" class="form-control" name="images[]" placeholder="Image URL">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-outline-danger remove-image-btn">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-text">
                        The first image will be used as the main product image.
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12 d-flex justify-content-between mb-5">
            <a href="/index.php/products" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">
                <?php echo $is_edit_mode ? 'Update Product' : 'Create Product'; ?>
            </button>
        </div>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Adicionar atributo
    document.getElementById('addAttributeBtn').addEventListener('click', function() {
        const template = document.getElementById('attributeTemplate').innerHTML;
        const container = document.getElementById('attributeContainer');
        container.insertAdjacentHTML('beforeend', template);
        
        // Registrar eventos para o novo botão de remover
        registerRemoveAttributeEvents();
    });
    
    // Função para registrar eventos nos botões de remover atributo
    function registerRemoveAttributeEvents() {
        document.querySelectorAll('.remove-attribute-btn').forEach(button => {
            button.addEventListener('click', function() {
                const row = this.closest('.attribute-row');
                if (document.querySelectorAll('.attribute-row').length > 1) {
                    row.remove();
                } else {
                    // Limpar campos se for a última linha
                    row.querySelectorAll('input').forEach(input => {
                        input.value = '';
                    });
                }
            });
        });
    }
    
    // Adicionar imagem
    document.getElementById('addImageBtn').addEventListener('click', function() {
        const template = document.getElementById('imageTemplate').innerHTML;
        const container = document.getElementById('imageContainer');
        container.insertAdjacentHTML('beforeend', template);
        
        // Registrar eventos para o novo botão de remover
        registerRemoveImageEvents();
        registerImagePreviewEvents();
    });
    
    // Função para registrar eventos nos botões de remover imagem
    function registerRemoveImageEvents() {
        document.querySelectorAll('.remove-image-btn').forEach(button => {
            button.addEventListener('click', function() {
                const row = this.closest('.image-row');
                if (document.querySelectorAll('.image-row').length > 1) {
                    row.remove();
                } else {
                    // Limpar campos se for a última linha
                    row.querySelectorAll('input').forEach(input => {
                        input.value = '';
                    });
                    const preview = row.querySelector('.col-md-3');
                    preview.innerHTML = '<div class="empty-image-preview text-center"><i class="fas fa-image text-muted"></i></div>';
                }
            });
        });
    }
    
    // Atualizar preview de imagem quando a URL mudar
    function registerImagePreviewEvents() {
        document.querySelectorAll('.image-row input').forEach(input => {
            input.addEventListener('change', function() {
                const row = this.closest('.image-row');
                const preview = row.querySelector('.col-md-3');
                const url = this.value.trim();
                
                if (url) {
                    preview.innerHTML = `<img src="${url}" class="img-thumbnail product-thumb" alt="Product Image">`;
                } else {
                    preview.innerHTML = '<div class="empty-image-preview text-center"><i class="fas fa-image text-muted"></i></div>';
                }
            });
        });
    }
    
    // Mostrar/ocultar botões de categoria primária
    document.querySelectorAll('.category-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const categoryId = this.value;
            const primaryRadio = document.querySelector(`.form-check-inline[style*="display: none"] input[value="${categoryId}"]`);
            
            if (primaryRadio) {
                const radioContainer = primaryRadio.closest('.form-check-inline');
                radioContainer.style.display = this.checked ? '' : 'none';
            }
            
            // Se não houver nenhuma categoria selecionada com primária, selecionar a primeira
            const checkedCategories = Array.from(document.querySelectorAll('.category-checkbox:checked'));
            const anyPrimarySelected = Array.from(document.querySelectorAll('.primary-category-radio:checked')).length > 0;
            
            if (checkedCategories.length > 0 && !anyPrimarySelected) {
                const firstCategoryId = checkedCategories[0].value;
                document.querySelector(`.primary-category-radio[value="${firstCategoryId}"]`).checked = true;
            }
        });
    });
    
    // Registrar eventos iniciais
    registerRemoveAttributeEvents();
    registerRemoveImageEvents();
    registerImagePreviewEvents();
});
</script>

<style>
    .product-thumb {
        width: 70px;
        height: 70px;
        object-fit: cover;
    }
    
    .empty-image-preview {
        width: 70px;
        height: 70px;
        background-color: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.25rem;
        border: 1px solid #dee2e6;
    }
    
    .empty-image-preview i {
        font-size: 1.5rem;
        opacity: 0.5;
    }
    
    .category-list {
        max-height: 300px;
        overflow-y: auto;
        padding-right: 10px;
    }
    
    .form-check-inline {
        margin-top: -5px;
    }
</style>