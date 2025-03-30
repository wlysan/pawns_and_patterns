<div class="product-page">
    <!-- Galeria de Imagens -->
    <div class="product-gallery">
        <div class="main-image">
            <img id="main-image" src="https://wallpapers.com/images/hd/beautiful-girls-desktop-a49p48tr9y5wu3rj.jpg" alt="Vestido Satin Sunset">
            <div class="zoom" id="zoomLens"></div> <!-- Lupa para zoom -->
        </div>
        <div class="thumbnails">
            <img src="https://wallpapers.com/images/hd/beautiful-girls-desktop-a49p48tr9y5wu3rj.jpg" alt="Thumbnail 1" data-large="https://wallpapers.com/images/hd/beautiful-girls-desktop-a49p48tr9y5wu3rj.jpg">
            <img src="https://cdn.shopify.com/s/files/1/2172/3321/files/2_8e46aa45-dd93-468e-b776-1ac34b3a60ca.png?v=1739225764&width=400" alt="Thumbnail 2" data-large="https://cdn.shopify.com/s/files/1/2172/3321/files/2_8e46aa45-dd93-468e-b776-1ac34b3a60ca.png?v=1739225764&width=400">
            <img src="https://cdn.shopify.com/s/files/1/2172/3321/files/8_7daa66c0-a6cd-48f2-81a7-e5d6d320b1af.png?v=1739819717&width=400" alt="Thumbnail 3" data-large="https://cdn.shopify.com/s/files/1/2172/3321/files/8_7daa66c0-a6cd-48f2-81a7-e5d6d320b1af.png?v=1739819717&width=400">
        </div>
    </div>

    <!-- Detalhes do Produto -->
    <div class="product-details">
        <h1 class="product-title">Preorder Satin Sunset Long Sleeve Gown</h1>
        <p class="product-price">$1,890.00</p>
        <p class="product-description">
            O vestido Satin Sunset é uma peça deslumbrante, perfeita para noites especiais. Com mangas longas e um caimento impecável, ele combina elegância e sofisticação.
        </p>

        <!-- Seleção de cor -->
        <div class="color-picker">
            <label for="color-select">Color:</label>
            <div class="color-options">
                <input type="radio" id="color-red" name="color" value="red" checked>
                <label for="color-red" class="color-option red"></label>

                <input type="radio" id="color-blue" name="color" value="blue">
                <label for="color-blue" class="color-option blue"></label>

                <input type="radio" id="color-green" name="color" value="green">
                <label for="color-green" class="color-option green"></label>

                <input type="radio" id="color-yellow" name="color" value="yellow">
                <label for="color-yellow" class="color-option yellow"></label>

                <input type="radio" id="color-black" name="color" value="black">
                <label for="color-black" class="color-option black"></label>
            </div>
        </div>

        <!-- Seleção de tamanho -->
        <div class="size-picker">
            <label for="size-select">Size:</label>
            <div class="size-options">
                <input type="radio" id="size-pp" name="size" value="pp" checked>
                <label for="size-pp" class="size-option pp">PP</label>

                <input type="radio" id="size-p" name="size" value="p">
                <label for="size-p" class="size-option p">P</label>

                <input type="radio" id="size-m" name="size" value="m">
                <label for="size-m" class="size-option m">M</label>

                <input type="radio" id="size-g" name="size" value="g">
                <label for="size-g" class="size-option g">G</label>

                <input type="radio" id="size-gg" name="size" value="gg">
                <label for="size-gg" class="size-option gg">GG</label>
            </div>
            <!-- Botão para abrir o modal -->
            <p class="text-guide-size">FIT PREDICTOR - <a href="#" id="open-modal-size">Calculate your size - Size Guide</a></p>
        </div>

        <!-- Detalhes Adicionais -->
        <div class="additional-details">
            <h3>Detalhes do Produto</h3>
            <ul>
                <li><strong>Material:</strong> Seda Satinada</li>
                <li><strong>Cor:</strong> Vermelho Sunset</li>
                <li><strong>Estilo:</strong> Longo com mangas</li>
                <li><strong>Entrega:</strong> Disponível para pré-venda</li>
            </ul>
        </div>

        <!-- Botão de Comprar -->
        <button class="add-to-cart">Adicionar ao Carrinho</button>
    </div>
</div>

<!-- Modal de tela cheia -->
<div id="fullscreenModal" class="fullscreen">
    <span class="close-fullscreen">&times;</span>
    <img id="fullscreenImage" src="" alt="Fullscreen Image">
    <div class="zoom-two" id="fullscreenZoomLens"></div> <!-- Lupa para o fullscreen -->
</div>

<!-- Modal -->
<div id="size-modal" class="modal-size">
    <div class="modal-content-size">
        <span class="close-modal-size">&times;</span>
        <h2>IRIS MAXI ROBE</h2>
        <p>SIZE CHARTS</p>
        <table>
            <tr>
                <th> </th>
                <th>BUST</th>
                <th>WAIST</th>
                <th>HIP</th>
                <th>SLEEVE</th>
            </tr>
            <tr>
                <td>XS</td>
                <td>33</td>
                <td>28</td>
                <td>53</td>
                <td>26</td>
            </tr>
            <tr>
                <td>S</td>
                <td>35</td>
                <td>30</td>
                <td>55</td>
                <td>26</td>
            </tr>
            <tr>
                <td>M</td>
                <td>36</td>
                <td>31</td>
                <td>57</td>
                <td>26</td>
            </tr>
            <tr>
                <td>L</td>
                <td>38</td>
                <td>33</td>
                <td>57</td>
                <td>27</td>
            </tr>
            <tr>
                <td>XL</td>
                <td>39</td>
                <td>34</td>
                <td>59</td>
                <td>27</td>
            </tr>
        </table>
    </div>
</div>

<!-- Modal -->
<script>
    document.getElementById("open-modal-size").addEventListener("click", function(event) {
        event.preventDefault();
        document.getElementById("size-modal").style.display = "block";
    });

    document.querySelector(".close-modal-size").addEventListener("click", function() {
        document.getElementById("size-modal").style.display = "none";
    });

    window.addEventListener("click", function(event) {
        if (event.target === document.getElementById("size-modal")) {
            document.getElementById("size-modal").style.display = "none";
        }
    });
</script>

<!-- Script JavaScript -->
<script>
    // Captura todas as miniaturas
    const thumbnails = document.querySelectorAll('.thumbnails img');
    // Captura a imagem principal
    const mainImage = document.getElementById('main-image');

    // Adiciona um evento de clique a cada miniatura
    thumbnails.forEach(thumbnail => {
        thumbnail.addEventListener('click', () => {
            // Obtém o src da imagem grande a partir do atributo data-large
            const largeImageSrc = thumbnail.getAttribute('data-large');
            // Atualiza o src da imagem principal
            mainImage.src = largeImageSrc;
        });
    });
</script>

<script>
    // Função para aplicar o efeito de zoom
    function applyZoom(element, zoomLens) {
        // Calcular a proporção da imagem
        const imageAspectRatio = element.naturalWidth / element.naturalHeight;

        element.addEventListener("mousemove", function(event) {
            const rect = element.getBoundingClientRect();
            const x = event.clientX - rect.left;
            const y = event.clientY - rect.top;

            // Posiciona a lupa
            zoomLens.style.display = "block";
            zoomLens.style.left = `${x}px`;
            zoomLens.style.top = `${y}px`;

            // Calcula a posição mantendo a proporção
            const bgX = (x / element.offsetWidth) * 100;
            const bgY = (y / element.offsetHeight) * 100;

            // Ajusta o background-size baseado na proporção da imagem
            const bgSize = `${400}% auto`;
            zoomLens.style.backgroundSize = bgSize;

            zoomLens.style.backgroundImage = `url('${element.src}')`;
            zoomLens.style.backgroundPosition = `${bgX}% ${bgY}%`;
        });

        element.addEventListener("mouseleave", function() {
            zoomLens.style.display = "none";
        });
    }

    function applyZoomFullScreen(element, zoomLens) {
        element.addEventListener("mousemove", function(event) {
            const rect = element.getBoundingClientRect();
            const x = event.clientX - rect.left;
            const y = event.clientY - rect.top;

            // Calcula a posição relativa do mouse na imagem (0 a 1)
            const relativeY = y / element.offsetHeight;

            // Transição suave para o offset vertical
            const maxOffset = zoomLens.offsetHeight * 1;
            const yOffset = (relativeY - 0.5) * -maxOffset;

            // Posiciona a lupa considerando o centro dela
            const lupaCentroX = x - (zoomLens.offsetWidth / 2);
            const lupaCentroY = y - (zoomLens.offsetHeight / 2) + yOffset;

            zoomLens.style.display = "block";
            zoomLens.style.left = `${lupaCentroX}px`;
            zoomLens.style.top = `${lupaCentroY}px`;

            // Ajusta o ponto de zoom para o centro da lupa
            const zoomX = (x / element.offsetWidth) * 100;
            const zoomY = (y / element.offsetHeight) * 100;

            zoomLens.style.backgroundImage = `url('${element.src}')`;
            zoomLens.style.backgroundPosition = `${zoomX}% ${zoomY}%`;
        });

        element.addEventListener("mouseleave", function() {
            zoomLens.style.display = "none";
        });
    }

    // Captura os elementos
    const mainImg = document.getElementById("main-image");
    const zoomLens = document.getElementById("zoomLens");
    const fullscreenModal = document.getElementById("fullscreenModal");
    const fullscreenImg = document.getElementById("fullscreenImage");
    const fullscreenZoomLens = document.getElementById("fullscreenZoomLens");

    // Aplica o zoom na imagem principal
    applyZoom(mainImg, zoomLens);

    // Abrir imagem em tela cheia ao clicar
    mainImg.addEventListener("click", function() {
        fullscreenImg.src = mainImg.src;
        fullscreenModal.style.display = "flex";
    });

    // Fechar tela cheia ao clicar no botão ou fora da imagem
    document.querySelector(".close-fullscreen").addEventListener("click", function() {
        fullscreenModal.style.display = "none";
    });

    window.addEventListener("click", function(event) {
        if (event.target === fullscreenModal) {
            fullscreenModal.style.display = "none";
        }
    });

    // Aplica o zoom na imagem em tela cheia
    applyZoomFullScreen(fullscreenImg, fullscreenZoomLens);

    // Trocar imagem ao clicar nas miniaturas
    const thumbnailImgs = document.querySelectorAll('.thumbnails img');
    thumbnailImgs.forEach(thumbnail => {
        thumbnail.addEventListener('click', () => {
            const largeImageSrc = thumbnail.getAttribute('data-large');
            mainImg.src = largeImageSrc;
        });
    });
</script>