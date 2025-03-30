<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paws&Patterns</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Flickity CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flickity/2.2.2/flickity.min.css">

    <!-- Font Awesome (ícones) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Google Fonts (Old Standard TT) -->
    <link href="https://fonts.googleapis.com/css2?family=Old+Standard+TT:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">

    <!-- Modal -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link rel="stylesheet" href="/assets/css/global.css">
    <link rel="stylesheet" href="/assets/css/header.css">
    <link rel="stylesheet" href="/assets/css/footer.css">
    <link rel="stylesheet" href="/assets/css/home.css">
    <link rel="stylesheet" href="/assets/css/login_register_lost_password.css">
    <link rel="stylesheet" href="/assets/css/products.css">
    <link rel="stylesheet" href="/assets/css/detail_products.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body style="display: flex; justify-content: space-between;">

    <header class="container-header">
        <div class="menu-icon" id="menuIcon">
            <i class="fas fa-bars"></i>
        </div>

        <ul class="container-ul">
            <li><a class="nav-link" href="#">Watches</a></li>
            <li><a class="nav-link" href="#">Maison</a></li>
            <li><a class="nav-link" href="#">Services</a></li>
            <li><a class="nav-link" href="#">Boutiques</a></li>
        </ul>

        <div class="container-logo">
            <a class="link-logo" href="/index.php/home">
                <div class="logo-image-container">
                    <span class="logo-text">Paws</span>
                    <img class="logo-image" src="/assets/images/logo.svg" alt="Paws&Patterns">
                    <span class="logo-text">Patterns</span>
                </div>
            </a>
        </div>

        <div class="container-right">
            <ul class="container-ul">
                <li><a class="nav-link" href="#">Contact</a></li>
                <li>
                    <button class="country-language-btn" id="countryLanguageBtn">
                        CH / EN <i class="fas fa-chevron-down"></i>
                    </button>
                </li>
                <li><a href="#" class="search-link"><i class="fas fa-search"></i></a></li>
            </ul>
        </div>
    </header>

    <div id="content" style="padding-top: 30px">

        <?php
        get_std_controller($rota['route']);
        include get_view($rota['route']);
        ?>

    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <!-- Seção Sobre -->
            <div class="footer-section">
                <h4>About</h4>
                <p>WatchTOP is a brand that celebrates femininity and elegance, with unique and sophisticated designs.</p>
            </div>

            <!-- Seção Links Úteis -->
            <div class="footer-section">
                <h4>Useful Links</h4>
                <ul>
                    <li><a href="#">About US</a></li>
                    <li><a href="#">FAQ</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms of Service</a></li>
                </ul>
            </div>

            <!-- Seção Contato -->
            <div class="footer-section">
                <h4>Contatc</h4>
                <ul>
                    <li>Email: contato@watchTOP.com</li>
                    <li>Telephone: (11) 9999-9999</li>
                    <li>Address: Rua da Moda, 123 - São Paulo, SP</li>
                </ul>
            </div>

            <!-- Seção Redes Sociais -->
            <div class="footer-section">
                <h4>Social media</h4>
                <div class="social-icons">
                    <a href="#" target="_blank"><i class="fab fa-instagram"></i></a>
                    <a href="#" target="_blank"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" target="_blank"><i class="fab fa-twitter"></i></a>
                    <a href="#" target="_blank"><i class="fab fa-pinterest"></i></a>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer__content-bottom">
            <div>
                <p>Copyright 2025, <a href="/" title="">WatchDogs</a></p>
            </div>
            <div>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-facebook-square"></i></a>
            </div>
            <div>
                <a href="#">Terms of Service</a>
                <a href="#">Privacy Policy</a>
            </div>
        </div>
    </footer>

    <!-- Link para Font Awesome (ícones) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Flickity JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flickity/2.2.2/flickity.pkgd.min.js"></script>

    <!-- Bootstrap JS (com Popper.js) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

<!-- Modal do Menu Principal -->
<div class="modal" id="modal">
    <div class="modal-content">
        <span class="close-modal" id="closeModal">&times;</span>
        <ul>
            <li><a href="#">Watches</a></li>
            <li><a href="#">Maison</a></li>
            <li><a href="#">Services</a></li>
            <li><a href="#">Boutiques</a></li>
            <li><a href="#">Contact</a></li>
            <li>
                <a href="#" id="openCountryLanguageModalFromMenu">
                    CH / EN <i class="fas fa-chevron-down"></i>
                </a>
            </li>
            <li><a href="#"><i class="fas fa-search"></i></a></li>
        </ul>
    </div>
</div>

<!-- Modal de País/Idioma -->
<div class="modal-country-language" id="countryLanguageModal">
    <div class="modal-country-language-content">
        <span class="close-modal" id="closeCountryLanguageModal">&times;</span>
        <h2>Country</h2>
        <div class="dropdown">
            <select id="countrySelect">
                <option value="CH">Switzerland</option>
                <option value="US">United States</option>
                <option value="BR">Brazil</option>
                <option value="FR">France</option>
            </select>
        </div>
        <h2>Language</h2>
        <div class="dropdown">
            <select id="languageSelect">
                <option value="EN">English</option>
                <option value="FR">French</option>
                <option value="DE">German</option>
                <option value="PT">Portuguese</option>
            </select>
        </div>
        <button class="update-button" id="updateButton">Atualizar</button>
    </div>
</div>

<!-- Script rolagem header - e os dois modais -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const menuIcon = document.getElementById('menuIcon');
        const modal = document.getElementById('modal');
        const closeModal = document.getElementById('closeModal');

        const countryLanguageBtn = document.getElementById('countryLanguageBtn');
        const openCountryLanguageModalFromMenu = document.getElementById('openCountryLanguageModalFromMenu');
        const countryLanguageModal = document.getElementById('countryLanguageModal');
        const closeCountryLanguageModal = document.getElementById('closeCountryLanguageModal');

        const countrySelect = document.getElementById('countrySelect');
        const languageSelect = document.getElementById('languageSelect');
        const updateButton = document.getElementById('updateButton');

        // Abrir modal do menu principal
        menuIcon.addEventListener('click', () => {
            modal.classList.add('open');
        });

        // Fechar modal do menu principal
        closeModal.addEventListener('click', () => {
            modal.classList.remove('open');
        });

        // Abrir modal de país/idioma a partir do botão no header
        countryLanguageBtn.addEventListener('click', () => {
            countryLanguageModal.classList.add('open');
        });

        // Abrir modal de país/idioma a partir do link no menu principal
        openCountryLanguageModalFromMenu.addEventListener('click', (e) => {
            e.preventDefault();
            countryLanguageModal.classList.add('open');
        });

        // Fechar modal de país/idioma
        closeCountryLanguageModal.addEventListener('click', () => {
            countryLanguageModal.classList.remove('open');
        });

        // Atualizar as iniciais ao clicar no botão "Atualizar"
        updateButton.addEventListener('click', () => {
            const country = countrySelect.value;
            const language = languageSelect.value;
            countryLanguageBtn.textContent = `${country} / ${language}`;
            openCountryLanguageModalFromMenu.textContent = `${country} / ${language}`;
            countryLanguageModal.classList.remove('open'); // Fechar o modal após atualizar
        });

        // Fechar modais ao clicar fora
        window.addEventListener('click', (event) => {
            if (event.target === modal) {
                modal.classList.remove('open');
            }
            if (event.target === countryLanguageModal) {
                countryLanguageModal.classList.remove('open');
            }
        });

        // Scroll do header
        window.addEventListener("scroll", function() {
            var header = document.querySelector(".container-header");
            if (window.scrollY > 50) {
                header.classList.add("scrolled");
            } else {
                header.classList.remove("scrolled");
            }
        });
    });
</script>



</html>