/**
 * JavaScript para funcionalidades das páginas de produtos
 * Paws&Patterns - Pet Boutique (Irlanda)
 */

document.addEventListener('DOMContentLoaded', function() {
    /**
     * Funções para o formulário de produto
     */
    initProductFormHandlers();
    
    /**
     * Funções para a página de detalhes do produto
     */
    initProductDetailHandlers();
    
    /**
     * Funções para a página de listagem de produtos
     */
    initProductListHandlers();
});

/**
 * Inicializa manipuladores de eventos para o formulário de produto
 */
function initProductFormHandlers() {
    // Adicionar atributo
    const addAttributeBtn = document.getElementById('addAttributeBtn');
    if (addAttributeBtn) {
        addAttributeBtn.addEventListener('click', function() {
            const template = document.getElementById('attributeTemplate').innerHTML;
            const container = document.getElementById('attributeContainer');
            container.insertAdjacentHTML('beforeend', template);
            
            // Registrar eventos para o novo botão de remover
            registerRemoveAttributeEvents();
        });
        
        // Inicializar eventos para botões existentes
        registerRemoveAttributeEvents();
    }
    
    // Adicionar imagem
    const addImageBtn = document.getElementById('addImageBtn');
    if (addImageBtn) {
        addImageBtn.addEventListener('click', function() {
            const template = document.getElementById('imageTemplate').innerHTML;
            const container = document.getElementById('imageContainer');
            container.insertAdjacentHTML('beforeend', template);
            
            // Registrar eventos para o novo botão de remover
            registerRemoveImageEvents();
            registerImagePreviewEvents();
        });
        
        // Inicializar eventos para botões existentes
        registerRemoveImageEvents();
        registerImagePreviewEvents();
    }
    
    // Checkboxes de categorias
    const categoryCheckboxes = document.querySelectorAll('.category-checkbox');
    if (categoryCheckboxes.length > 0) {
        categoryCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updatePrimaryCategories);
        });
    }
}

/**
 * Registra eventos para remover atributos
 */
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

/**
 * Registra eventos para remover imagens
 */
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

/**
 * Registra eventos para preview de imagens
 */
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

/**
 * Atualiza exibição de opções de categoria primária
 */
function updatePrimaryCategories() {
    const checkedCategories = Array.from(document.querySelectorAll('.category-checkbox:checked'));
    
    // Mostrar/ocultar opções de categoria primária
    document.querySelectorAll('.primary-category-radio').forEach(radio => {
        const categoryId = radio.value;
        const isChecked = checkedCategories.some(cb => cb.value === categoryId);
        const container = radio.closest('.form-check-inline');
        
        if (container) {
            container.style.display = isChecked ? '' : 'none';
        }
    });
    
    // Garantir que uma categoria primária esteja selecionada
    const anyPrimarySelected = Array.from(document.querySelectorAll('.primary-category-radio:checked'))
        .some(radio => {
            const categoryId = radio.value;
            return checkedCategories.some(cb => cb.value === categoryId);
        });
    
    if (checkedCategories.length > 0 && !anyPrimarySelected) {
        const firstCategoryId = checkedCategories[0].value;
        const radio = document.querySelector(`.primary-category-radio[value="${firstCategoryId}"]`);
        if (radio) {
            radio.checked = true;
        }
    }
}

/**
 * Inicializa manipuladores de eventos para a página de detalhes do produto
 */
function initProductDetailHandlers() {
    // Galeria de imagens
    const thumbnails = document.querySelectorAll('.product-thumb');
    const mainImage = document.getElementById('mainProductImage');
    
    if (thumbnails.length > 0 && mainImage) {
        thumbnails.forEach(thumb => {
            thumb.addEventListener('click', function() {
                // Atualizar imagem principal
                mainImage.src = this.src;
                
                // Atualizar classe ativa
                thumbnails.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
            });
        });
    }
}

/**
 * Inicializa manipuladores de eventos para a página de listagem de produtos
 */
function initProductListHandlers() {
    // Filtro por categoria - submeter formulário ao mudar
    const categoryFilter = document.getElementById('categoryFilter');
    if (categoryFilter) {
        categoryFilter.addEventListener('change', function() {
            this.closest('form').submit();
        });
    }
    
    // Atualização de itens por página - submeter formulário ao mudar
    const perPageSelect = document.getElementById('per_page');
    if (perPageSelect) {
        perPageSelect.addEventListener('change', function() {
            this.closest('form').submit();
        });
    }
}