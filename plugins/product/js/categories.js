/**
 * JavaScript para o gerenciamento de categorias de produtos
 * Paws&Patterns - Pet Boutique (Irlanda)
 */

document.addEventListener('DOMContentLoaded', function() {
    // Gestão de atributos dinâmicos
    setupDynamicAttributes();
    
    // Manipulação de modais
    setupModals();
    
    // Validação de formulários
    setupFormValidation();
    
    // Toggle para exibir subcategorias
    setupCategoryToggles();
});

/**
 * Configurar a funcionalidade de atributos dinâmicos no formulário
 */
function setupDynamicAttributes() {
    const addAttributeBtn = document.getElementById('add-attribute-btn');
    
    if (addAttributeBtn) {
        addAttributeBtn.addEventListener('click', function() {
            addAttributeRow();
        });
    }
    
    // Setup para botões de remoção existentes
    document.querySelectorAll('.remove-attribute-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            const row = this.closest('.attribute-row');
            row.parentNode.removeChild(row);
        });
    });
}

/**
 * Configurar os exemplos de atributos clicáveis
 */
function setupAttributeExamples() {
    const exampleItems = document.querySelectorAll('.example-item');
    
    exampleItems.forEach(item => {
        item.addEventListener('click', function() {
            const key = this.dataset.key;
            const value = this.dataset.value;
            
            // Cria um novo atributo com os valores do exemplo
            addAttributeRow(key, value);
        });
    });
}

/**
 * Adiciona uma nova linha de atributo com valores opcionais
 * @param {string} key - Chave do atributo
 * @param {string} value - Valor do atributo
 */
function addAttributeRow(key = '', value = '') {
    const attributesContainer = document.getElementById('attributes-container');
    if (!attributesContainer) return;
    
    const attributeRow = document.createElement('div');
    attributeRow.className = 'attribute-row';
    
    attributeRow.innerHTML = `
        <input type="text" class="form-control attr-key" name="attr_key[]" value="${escapeHtml(key)}" placeholder="Key (e.g. P)">
        <input type="text" class="form-control attr-value" name="attr_value[]" value="${escapeHtml(value)}" placeholder="Value (e.g. SMALL)">
        <button type="button" class="btn btn-danger btn-sm remove-attribute-btn">Remove</button>
    `;
    
    attributesContainer.appendChild(attributeRow);
    
    // Adiciona o evento para remover o atributo
    const removeBtn = attributeRow.querySelector('.remove-attribute-btn');
    removeBtn.addEventListener('click', function() {
        attributesContainer.removeChild(attributeRow);
    });
}

/**
 * Escapar HTML para evitar XSS
 * @param {string} unsafe String que pode conter caracteres HTML
 * @return {string} String com caracteres HTML escapados
 */
function escapeHtml(unsafe) {
    if (typeof unsafe !== 'string') return '';
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

/**
 * Configurar a funcionalidade de modais
 */
function setupModals() {
    // Fecha modais quando o usuário clica fora deles
    window.addEventListener('click', function(event) {
        const modals = document.querySelectorAll('.modal-overlay');
        modals.forEach(function(modal) {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });
    });
    
    // Botões para fechar modais
    document.querySelectorAll('.close-modal-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            const modal = this.closest('.modal-overlay');
            modal.style.display = 'none';
        });
    });
    
    // Exibe o modal de confirmação de exclusão
    const deleteModal = document.getElementById('delete-confirmation-modal');
    if (deleteModal) {
        const categoryId = deleteModal.dataset.categoryId;
        if (categoryId) {
            deleteModal.style.display = 'flex';
        }
    }
}

/**
 * Configurar validação de formulários
 */
function setupFormValidation() {
    const categoryForm = document.getElementById('category-form');
    
    if (categoryForm) {
        categoryForm.addEventListener('submit', function(event) {
            let valid = true;
            
            // Validação do nome (obrigatório)
            const nameInput = document.getElementById('category-name');
            if (!nameInput.value.trim()) {
                document.getElementById('name-error').textContent = 'Category name is required';
                valid = false;
            } else {
                document.getElementById('name-error').textContent = '';
            }
            
            // Prevenção de auto-referência (categoria pai igual a ela mesma)
            const parentSelect = document.getElementById('parent-id');
            const categoryId = categoryForm.dataset.categoryId;
            
            if (categoryId && parentSelect.value === categoryId) {
                document.getElementById('parent-error').textContent = 'A category cannot be its own parent';
                valid = false;
            } else {
                document.getElementById('parent-error').textContent = '';
            }
            
            if (!valid) {
                event.preventDefault();
            }
        });
    }
}

/**
 * Configurar toggles para exibir/ocultar subcategorias
 */
function setupCategoryToggles() {
    const toggleButtons = document.querySelectorAll('.category-toggle');
    
    toggleButtons.forEach(function(toggle) {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const categoryId = this.dataset.categoryId;
            const subCategories = document.querySelectorAll(`.subcategory-of-${categoryId}`);
            const icon = this.querySelector('i');
            
            // Alterna a rotação do ícone
            icon.style.transform = icon.style.transform === 'rotate(90deg)' ? '' : 'rotate(90deg)';
            
            // Alterna a visibilidade das subcategorias
            subCategories.forEach(function(subCategory) {
                subCategory.classList.toggle('hidden');
                
                // Se a subcategoria for escondida, também esconde seus filhos
                if (subCategory.classList.contains('hidden')) {
                    const subId = subCategory.id.replace('category-row-', '');
                    const nestedSubs = document.querySelectorAll(`.subcategory-of-${subId}`);
                    
                    // Resetar ícones de subcategorias
                    const subToggle = subCategory.querySelector('.category-toggle i');
                    if (subToggle) {
                        subToggle.style.transform = '';
                    }
                    
                    // Esconder todas subcategorias filhas
                    nestedSubs.forEach(nestedSub => {
                        nestedSub.classList.add('hidden');
                    });
                }
            });
        });
    });
}

/**
 * Confirmar exclusão de categoria
 * @param {number} categoryId ID da categoria a ser excluída
 */
function confirmDeleteCategory(categoryId) {
    if (!categoryId) return;
    
    // Redireciona para a rota de confirmação de exclusão
    window.location.href = `/index.php/category/confirmar_exclusao/${categoryId}`;
}

/**
 * Fechar alertas
 * @param {HTMLElement} alertElement Elemento do alerta
 */
function closeAlert(alertElement) {
    if (alertElement) {
        alertElement.style.display = 'none';
    }
}