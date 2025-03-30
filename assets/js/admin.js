/**
 * JavaScript para o painel administrativo
 * Paws&Patterns - Pet Boutique (Irlanda)
 * 
 * Este arquivo inclui funcionalidades interativas para o painel administrativo:
 * - Controle da barra lateral para layouts responsivos
 * - Manipulação de menus e submenus
 * - Suporte para componentes do Bootstrap
 * - Funcionalidades para gráficos do dashboard
 */

document.addEventListener('DOMContentLoaded', function() {
    // Elementos do DOM
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const mobileSidebarToggle = document.getElementById('mobileSidebarToggle');
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle');

    // Verifica qual é o link ativo com base na URL atual
    function setActiveNav() {
        const currentPath = window.location.pathname;
        const navLinks = document.querySelectorAll('.nav-link, .submenu-link');
        
        navLinks.forEach(link => {
            // Remove a classe active de todos os links
            if (link.parentElement) {
                link.parentElement.classList.remove('active');
            }
            
            // Verifica se o href do link está contido no caminho atual
            const href = link.getAttribute('href');
            if (href && currentPath.includes(href) && href !== '#') {
                // Adiciona classe active ao item pai
                if (link.parentElement) {
                    link.parentElement.classList.add('active');
                }
                
                // Se for um item de submenu, expande o menu pai
                const parentSubmenu = link.closest('.submenu');
                if (parentSubmenu) {
                    const parentDropdown = parentSubmenu.previousElementSibling;
                    if (parentDropdown && parentDropdown.classList.contains('dropdown-toggle')) {
                        parentDropdown.setAttribute('aria-expanded', 'true');
                        parentSubmenu.classList.add('show');
                        if (parentDropdown.parentElement) {
                            parentDropdown.parentElement.classList.add('active');
                        }
                    }
                }
            }
        });
    }
    
    // Define o link ativo com base na URL atual
    setActiveNav();

    // Toggle da Sidebar para dispositivos móveis
    function toggleSidebar() {
        if (sidebar) {
            sidebar.classList.toggle('active');
        }
    }

    // Adiciona event listeners para os botões de toggle da sidebar
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', toggleSidebar);
    }
    
    if (mobileSidebarToggle) {
        mobileSidebarToggle.addEventListener('click', toggleSidebar);
    }

    // Fecha a sidebar ao clicar fora dela em dispositivos móveis
    document.addEventListener('click', function(event) {
        if (!sidebar) return;
        
        const isClickInsideSidebar = sidebar.contains(event.target);
        const isClickOnToggle = mobileSidebarToggle && mobileSidebarToggle.contains(event.target);
        
        if (window.innerWidth < 992 && !isClickInsideSidebar && !isClickOnToggle && sidebar.classList.contains('active')) {
            toggleSidebar();
        }
    });

    // Inicializa os tooltips do Bootstrap se disponíveis
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        tooltips.forEach(tooltip => {
            new bootstrap.Tooltip(tooltip);
        });
    }
    
    // Adiciona funcionalidade para os dropdowns de submenu do Bootstrap 5
    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Alterna o estado de expansão
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            this.setAttribute('aria-expanded', !isExpanded);
            
            // Identifica o submenu associado
            const targetId = this.getAttribute('data-bs-target');
            if (targetId) {
                const submenu = document.querySelector(targetId);
                
                if (submenu) {
                    submenu.classList.toggle('show');
                }
            }
        });
    });
    
    // Adiciona suporte para compatibilidade com menus de plugins (atributos antigos do Bootstrap 4)
    document.querySelectorAll('[data-toggle="collapse"]').forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Alterna o estado de expansão
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            this.setAttribute('aria-expanded', !isExpanded);
            
            // Identifica o submenu associado
            const targetId = this.getAttribute('href');
            if (targetId) {
                const submenu = document.querySelector(targetId);
                
                if (submenu) {
                    submenu.classList.toggle('show');
                }
            }
        });
    });
    
    // Implementa o gerenciamento de charts para o dashboard (exemplo básico)
    function initDashboardCharts() {
        // Verifica se estamos na página de dashboard e se Chart.js está disponível
        const salesChartCanvas = document.getElementById('salesChart');
        const ordersChartCanvas = document.getElementById('ordersChart');
        
        if (typeof Chart !== 'undefined') {
            // Chart de Vendas (se existir no DOM)
            if (salesChartCanvas) {
                const salesCtx = salesChartCanvas.getContext('2d');
                new Chart(salesCtx, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                        datasets: [{
                            label: 'Sales',
                            data: [1200, 1900, 1500, 2500, 1800, 3000],
                            borderColor: '#000',
                            backgroundColor: 'rgba(0, 0, 0, 0.1)',
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
            }
            
            // Chart de Pedidos (se existir no DOM)
            if (ordersChartCanvas) {
                const ordersCtx = ordersChartCanvas.getContext('2d');
                new Chart(ordersCtx, {
                    type: 'bar',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                        datasets: [{
                            label: 'Orders',
                            data: [65, 59, 80, 81, 56, 94],
                            backgroundColor: '#000'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
            }
        }
    }
    
    // Inicializa os gráficos do dashboard se estiver na página correspondente
    if (window.location.pathname.includes('/admin/dashboard')) {
        initDashboardCharts();
    }
    
    // Funcionalidade para manter determinados dropdowns abertos quando necessário
    document.querySelectorAll('.dropdown-menu').forEach(dropdown => {
        dropdown.addEventListener('click', function(e) {
            if (e.target.classList.contains('keep-open') || e.target.closest('.keep-open')) {
                e.stopPropagation();
            }
        });
    });
    
    // Adapta a interface para diferentes tipos de dispositivos
    function handleResponsiveChanges() {
        if (window.innerWidth < 992) {
            if (sidebar) {
                sidebar.classList.remove('active');
            }
        }
    }
    
    // Executa no carregamento e quando a janela é redimensionada
    handleResponsiveChanges();
    window.addEventListener('resize', handleResponsiveChanges);
});