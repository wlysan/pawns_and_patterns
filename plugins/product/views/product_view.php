<?php
/**
 * View de Produtos
 * Exibe listagem, formulários de adição/edição e opção de exclusão de produtos
 */

// Variáveis esperadas de $view_data:
// - mode: modo de exibição (list, add, edit, delete)
// - page_title: título da página
// - products: array de produtos (para listagem)
// - current_product: dados do produto atual (para edição/visualização)
// - pagination: informações de paginação
// - all_categories: lista de todas as categorias disponíveis
// - errors: mensagens de erro
// - success: mensagem de sucesso

// Debug - Exibe dados passados para a view (remover em produção)
// error_log('Product view data: ' . print_r($view_data, true));

// Determina qual subview exibir com base no modo
$mode = $view_data['mode'] ?? 'list';

// Título da página
$page_title = $view_data['page_title'] ?? 'Products';

// Mensagens de erro e sucesso
$errors = $view_data['errors'] ?? [];
$success = $view_data['success'] ?? '';

// Funções auxiliares para a view
/**
 * Cria HTML para modal de confirmação de exclusão
 */
function render_delete_confirmation($product) {
    if (!$product) return '';
    
    $html = '<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete the product "<strong>' . htmlspecialchars($product['name']) . '</strong>"?</p>
                    <p class="text-danger">This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form method="post" action="/index.php/product_delete/' . $product['id'] . '">
                        <input type="hidden" name="confirm_delete" value="1">
                        <button type="submit" class="btn btn-danger">Delete Product</button>
                    </form>
                </div>
            </div>
        </div>
    </div>';
    
    return $html;
}

/**
 * Formata preço para exibição
 */
function format_price($price) {
    return '€' . number_format($price, 2);
}

/**
 * Gera o HTML para exibir estrelas de avaliação
 */
function render_rating($rating) {
    $rating = floatval($rating);
    $full_stars = floor($rating);
    $half_star = ($rating - $full_stars) >= 0.5;
    $empty_stars = 5 - $full_stars - ($half_star ? 1 : 0);
    
    $html = '<div class="product-rating">';
    
    // Estrelas cheias
    for ($i = 0; $i < $full_stars; $i++) {
        $html .= '<i class="fas fa-star"></i>';
    }
    
    // Meia estrela (se aplicável)
    if ($half_star) {
        $html .= '<i class="fas fa-star-half-alt"></i>';
    }
    
    // Estrelas vazias
    for ($i = 0; $i < $empty_stars; $i++) {
        $html .= '<i class="far fa-star"></i>';
    }
    
    $html .= '</div>';
    
    return $html;
}

/**
 * Retorna uma cor de badge com base no status do produto
 */
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

/**
 * Verifica se um valor existe em um array
 */
function in_array_r($needle, $haystack) {
    if (is_array($haystack)) {
        foreach ($haystack as $item) {
            if (is_array($item) && isset($item['id']) && $item['id'] == $needle) {
                return true;
            }
        }
    }
    return false;
}
?>

<div class="container py-4">
    <h1 class="mb-4"><?php echo htmlspecialchars($page_title); ?></h1>
    
    <?php if (!empty($success)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($success); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($errors['general'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($errors['general']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <?php
    // Exibe a view correspondente ao modo atual
    switch ($mode) {
        case 'add':
        case 'edit':
            include 'product_form_view.php';
            break;
            
        case 'delete':
            // Esta lógica está no controller
            break;
            
        case 'list':
        default:
            include 'product_list_view.php';
            break;
    }
    ?>
</div>

<?php
// Exibe modal de confirmação de exclusão se necessário
if (isset($view_data['delete_product']) && $view_data['delete_product']) {
    echo render_delete_confirmation($view_data['delete_product']);
    
    // Script para exibir automaticamente o modal
    echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            var deleteModal = new bootstrap.Modal(document.getElementById("deleteConfirmModal"));
            deleteModal.show();
        });
    </script>';
}
?>