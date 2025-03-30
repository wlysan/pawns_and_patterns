<?php
/**
 * View para listar categorias de produtos
 */

// Verifica se há categorias para exibir
if (empty($categories)): 
?>
<div class="categories-empty">
    <p>No categories found. Use the button above to create your first category.</p>
</div>
<?php else: ?>

<table class="categories-table">
    <thead>
        <tr>
            <th style="width: 40%;">Name</th>
            <th style="width: 30%;">Description</th>
            <th style="width: 15%;">Status</th>
            <th style="width: 15%;">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php render_category_rows($categories); ?>
    </tbody>
</table>

<?php endif; ?>

<?php
/**
 * Função recursiva para renderizar linhas de categorias com subcategorias
 * @param array $categories Lista de categorias
 * @param int $level Nível de profundidade (para indentação)
 */
function render_category_rows($categories, $level = 0) {
    foreach ($categories as $category) {
        $has_children = isset($category['children']) && !empty($category['children']);
        $class = $level > 0 ? 'subcategory subcategory-of-' . $category['parent_id'] : '';
        
        if ($level > 0) {
            $class .= ' hidden'; // Inicialmente esconde subcategorias
        }
        ?>
        <tr class="<?php echo $class; ?>" id="category-row-<?php echo $category['id']; ?>">
            <td>
                <?php if ($has_children): ?>
                <span class="category-toggle" data-category-id="<?php echo $category['id']; ?>">
                    <i class="fas fa-caret-right"></i>
                </span>
                <?php endif; ?>
                
                <?php if ($level > 0): ?>
                <span class="subcategory-indicator">└ </span>
                <?php endif; ?>
                
                <?php echo htmlspecialchars($category['name']); ?>
            </td>
            <td><?php echo htmlspecialchars(substr($category['description'] ?? '', 0, 100)); ?></td>
            <td>
                <span class="status-badge status-<?php echo strtolower($category['status']); ?>">
                    <?php echo htmlspecialchars($category['status']); ?>
                </span>
            </td>
            <td>
                <a href="/index.php/category_edit/<?php echo $category['id']; ?>" class="btn btn-secondary btn-sm btn-table">Edit</a>
                <button type="button" class="btn btn-danger btn-sm btn-table" onclick="confirmDeleteCategory(<?php echo $category['id']; ?>)">Delete</button>
            </td>
        </tr>
        <?php
        if ($has_children) {
            render_category_rows($category['children'], $level + 1);
        }
    }
}
?>