<?php
/**
 * View principal para gerenciamento de categorias de produtos
 * Esta view atua como um roteador para as views específicas
 */

// Obtém os dados do controller
$errors = $view_data['errors'] ?? [];
$success = $view_data['success'] ?? '';
$categories = $view_data['categories'] ?? [];
$current_category = $view_data['current_category'] ?? null;
$page_title = $view_data['page_title'] ?? 'Product Categories';
$mode = $view_data['mode'] ?? 'list';

// Para selecionar categorias pai no formulário
$all_categories = $view_data['all_categories'] ?? [];

// Para modal de confirmação de exclusão
$confirm_delete = $view_data['confirm_delete'] ?? null;
$delete_category = $view_data['delete_category'] ?? null;

// Obtém action para processamento de mensagens
$action = get_action();
?>

<!-- Carrega os estilos e scripts específicos -->
<link rel="stylesheet" href="/plugins/product/css/categories.css">

<div class="category-container">
    <div class="category-header">
        <h1 class="category-title"><?php echo htmlspecialchars($page_title); ?></h1>
        
        <?php if ($mode === 'list'): ?>
        <div class="category-actions">
            <a href="/index.php/category_add" class="btn btn-primary">Add New Category</a>
        </div>
        <?php endif; ?>
    </div>
    
    <?php 
    // Verifica se há uma mensagem de sucesso baseada na ação
    if ($action && strpos($action, 'sucesso') !== false) {
        $success_parts = explode('|', $action);
        $success_action = $success_parts[1] ?? '';
        
        switch ($success_action) {
            case 'add':
                echo '<div class="alert alert-success">Category added successfully!</div>';
                break;
            case 'update':
                echo '<div class="alert alert-success">Category updated successfully!</div>';
                break;
            case 'delete':
                echo '<div class="alert alert-success">Category deleted successfully!</div>';
                break;
        }
    }
    ?>
    
    <?php if (!empty($errors['general'])): ?>
    <div class="alert alert-danger">
        <?php echo htmlspecialchars($errors['general']); ?>
    </div>
    <?php endif; ?>
    
    <?php
    // Carrega a view adequada com base no modo
    if ($mode === 'add') {
        include 'category_add_view.php';
    } elseif ($mode === 'edit') {
        include 'category_edit_view.php';
    } else {
        include 'category_list_view.php';
    }
    ?>
</div>

<!-- Modal de confirmação de exclusão -->
<?php if ($confirm_delete && $delete_category): ?>
<div class="modal-overlay" id="delete-confirmation-modal" data-category-id="<?php echo $confirm_delete; ?>">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Confirm Delete</h3>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to delete the category "<?php echo htmlspecialchars($delete_category['name']); ?>"?</p>
            
            <?php
            // Verifica se a categoria tem subcategorias
            $has_subcategories = false;
            foreach ($all_categories as $cat) {
                if (isset($cat['parent_id']) && $cat['parent_id'] == $delete_category['id']) {
                    $has_subcategories = true;
                    break;
                }
            }
            
            if ($has_subcategories):
            ?>
            <div class="alert alert-danger">
                <strong>Warning:</strong> This category has subcategories. Deleting it will affect product categorization.
            </div>
            <?php endif; ?>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary close-modal-btn">Cancel</button>
            
            <form id="delete-category-form" action="/index.php/category_delete/<?php echo $confirm_delete; ?>" method="post">
                <input type="hidden" name="confirm_delete" value="1">
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Carrega o JavaScript específico -->
<script src="/plugins/product/js/categories.js"></script>