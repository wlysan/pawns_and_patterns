<?php
/**
 * View para adicionar nova categoria de produto
 */

// Valida se os dados necessários existem
$current_category = $current_category ?? [];
$errors = $errors ?? [];
$all_categories = $all_categories ?? [];

// Inicializa valores padrão
$name = $current_category['name'] ?? '';
$description = $current_category['description'] ?? '';
$parent_id = $current_category['parent_id'] ?? 0;
$status = $current_category['status'] ?? 'Ativo';
$attributes = $current_category['attributes'] ?? [];
?>

<form id="category-form" class="category-form" action="/index.php/category_add" method="post">
    <div class="form-group">
        <label for="category-name">Category Name</label>
        <input type="text" id="category-name" name="name" class="form-control" 
              value="<?php echo htmlspecialchars($name); ?>" 
              placeholder="Enter category name" required>
        <?php if (isset($errors['name'])): ?>
            <div id="name-error" class="text-danger"><?php echo htmlspecialchars($errors['name']); ?></div>
        <?php else: ?>
            <div id="name-error" class="text-danger"></div>
        <?php endif; ?>
    </div>
    
    <div class="form-group">
        <label for="parent-id">Parent Category</label>
        <select id="parent-id" name="parent_id" class="form-select">
            <option value="0">None (Top Level)</option>
            <?php foreach ($all_categories as $cat): ?>
                <option value="<?php echo $cat['id']; ?>" <?php echo ($parent_id == $cat['id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($cat['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php if (isset($errors['parent_id'])): ?>
            <div id="parent-error" class="text-danger"><?php echo htmlspecialchars($errors['parent_id']); ?></div>
        <?php else: ?>
            <div id="parent-error" class="text-danger"></div>
        <?php endif; ?>
    </div>
    
    <div class="form-group">
        <label for="description">Description</label>
        <textarea id="description" name="description" class="form-control" 
                 placeholder="Enter category description"><?php echo htmlspecialchars($description); ?></textarea>
    </div>
    
    <div class="form-group">
        <label for="status">Status</label>
        <select id="status" name="status" class="form-select">
            <option value="Ativo" <?php echo ($status == 'Ativo') ? 'selected' : ''; ?>>Active</option>
            <option value="Inativo" <?php echo ($status == 'Inativo') ? 'selected' : ''; ?>>Inactive</option>
            <option value="Pendente" <?php echo ($status == 'Pendente') ? 'selected' : ''; ?>>Pending</option>
            <option value="Desabilitado" <?php echo ($status == 'Desabilitado') ? 'selected' : ''; ?>>Disabled</option>
        </select>
    </div>
    
    <div class="dynamic-attributes">
        <h4>Additional Attributes</h4>
        <p class="attribute-help">Add key-value pairs to store extra information for this category, such as available sizes, display options, or metadata.</p>
        <div id="attributes-container">
            <?php if (!empty($attributes) && is_array($attributes)): ?>
                <?php foreach ($attributes as $key => $value): ?>
                <div class="attribute-row">
                    <input type="text" class="form-control attr-key" name="attr_key[]" value="<?php echo htmlspecialchars($key); ?>" placeholder="Key (e.g. available_sizes)">
                    <input type="text" class="form-control attr-value" name="attr_value[]" value="<?php echo htmlspecialchars($value); ?>" placeholder="Value (e.g. P,M,G,GG)">
                    <button type="button" class="btn btn-danger btn-sm remove-attribute-btn">Remove</button>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <button type="button" id="add-attribute-btn" class="btn btn-secondary add-attribute-btn">Add Attribute</button>        
    </div>
    
    <div class="form-group mt-4">
        <button type="submit" class="btn btn-primary">Save Category</button>
        <a href="/index.php/category" class="btn btn-secondary">Cancel</a>
    </div>
</form>