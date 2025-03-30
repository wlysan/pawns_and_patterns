<!-- Conteúdo Interno -->
<div class="content">
    <!-- Filtros e Ordenação -->
    <div class="filters-container">
        <button id="filterButton"><i class="fas fa-filter"></i> Filter</button>
        <div class="sort-by">
            <button id="sortButton">
                Sort by <i class="fas fa-chevron-down"></i>
            </button>
            <div id="sortOptions" class="sort-options">
                <div class="sort-option">Featured</div>
                <div class="sort-option">Price: Low to High</div>
                <div class="sort-option">Price: High to Low</div>
                <div class="sort-option">Newest</div>
            </div>
        </div>
    </div>

    <!-- Lista de Produtos -->
    <div class="product-grid">
        <div class="product-card">
            <img src="https://cdn.shopify.com/s/files/1/2172/3321/files/16_67c68462-9527-4533-9e86-bfe131ef3026.png?v=1732042824&width=300" alt="Product 1" />
            <h3>Product 1</h3>
            <p class="price">$120.00</p>
        </div>
        <div class="product-card">
            <img src="https://cdn.shopify.com/s/files/1/2172/3321/files/14_24dc5bf2-acd6-4712-9aa3-601d50c4a5c8.png?v=1736783454&width=300" alt="Product 2" />
            <h3>Product 2</h3>
            <p class="price">$150.00</p>
        </div>
        <div class="product-card">
            <img src="https://cdn.shopify.com/s/files/1/2172/3321/files/14_3a7fa447-5110-4e3e-a5eb-160ad8a2b5fa.png?v=1732136149&width=300" alt="Product 3" />
            <h3>Product 3</h3>
            <p class="price">$200.00</p>
        </div>
        <div class="product-card">
            <img src="https://cdn.shopify.com/s/files/1/2172/3321/files/14_3a7fa447-5110-4e3e-a5eb-160ad8a2b5fa.png?v=1732136149&width=300" alt="Product 3" />
            <h3>Product 3</h3>
            <p class="price">$200.00</p>
        </div>
    </div>

    <!-- Paginação -->
    <div class="pagination">
        <button class="page-btn"><i class="fas fa-chevron-left"></i></button>
        <button class="page-btn active">1</button>
        <button class="page-btn">2</button>
        <button class="page-btn">3</button>
        <button class="page-btn"><i class="fas fa-chevron-right"></i></button>
    </div>
</div>

<!-- Modal de Filtro -->
<div id="filterModalPatbo" class="modal-filter">
    <div class="modal-filter-content">
        <span class="close-filter">&times;</span>
        <h2>Filter</h2>
        <div class="filter-section">
            <h3>Category</h3>
            <ul>
                <li>
                    <input type="checkbox" id="category1" />
                    <label for="category1">Dresses</label>
                </li>
                <li>
                    <input type="checkbox" id="category2" />
                    <label for="category2">Tops</label>
                </li>
                <li>
                    <input type="checkbox" id="category3" />
                    <label for="category3">Bottoms</label>
                </li>
            </ul>
        </div>
        <div class="filter-section">
            <h3>Size</h3>
            <ul>
                <li>
                    <input type="checkbox" id="size1" /> <label for="size1">S</label>
                </li>
                <li>
                    <input type="checkbox" id="size2" /> <label for="size2">M</label>
                </li>
                <li>
                    <input type="checkbox" id="size3" /> <label for="size3">L</label>
                </li>
            </ul>
        </div>
        <div class="filter-section">
            <h3>Color</h3>
            <ul>
                <li>
                    <input type="checkbox" id="color1" />
                    <label for="color1">Black</label>
                </li>
                <li>
                    <input type="checkbox" id="color2" />
                    <label for="color2">White</label>
                </li>
                <li>
                    <input type="checkbox" id="color3" />
                    <label for="color3">Red</label>
                </li>
            </ul>
        </div>
        <button class="apply-filter">Apply</button>
    </div>
</div>

<script>
    // script.js
    // Abrir e fechar o modal de filtro
    document.addEventListener("DOMContentLoaded", () => {
        const filterButton = document.getElementById("filterButton");
        const filterModal = document.getElementById("filterModalPatbo");
        const closeFilter = document.querySelector(".close-filter");

        // Função para abrir o modal
        filterButton.addEventListener("click", () => {
            filterModal.classList.add("show");
        });

        // Função para fechar o modal
        closeFilter.addEventListener("click", () => {
            filterModal.classList.remove("show");
        });

        // Fechar ao clicar fora do modal
        filterModal.addEventListener("click", (event) => {
            if (event.target === filterModal) {
                filterModal.classList.remove("show");
            }
        });
    });

    // Abrir e fechar o dropdown de ordenação
    document
        .getElementById("sortButton")
        .addEventListener("click", function() {
            document.getElementById("sortOptions").style.display =
                document.getElementById("sortOptions").style.display === "block" ?
                "none" :
                "block";
        });

    // Fechar o dropdown ao clicar fora
    window.addEventListener("click", function(event) {
        if (!event.target.matches("#sortButton")) {
            document.getElementById("sortOptions").style.display = "none";
        }
    });
</script>