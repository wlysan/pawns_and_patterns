<!-- <script>
    function buscarProdutosPorFiltro() {
        const url = "http://localhost/plugins/produtos/controllers/api_produtos_controller.php";

        fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    "acao": "buscarProdutosPorFiltro",
                    "filtros": {
                                 
                    }
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log("Data received:", data);
            })
            .catch(error => console.error("Erro ao listar produtos:", error));
    }

    buscarProdutosPorFiltro();
</script> -->

<script>
    function buscarProdutosPorFiltro() {
    const url = "http://localhost/plugins/produtos/controllers/api_produtos_controller.php";

    fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                "acao": "buscarProdutosPorFiltro",
                "filtros": {
                    "preco_max": 90,
                }
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log("Produtos filtrados:", data);
        })
        .catch(error => console.error("Erro ao listar produtos:", error));
}

buscarProdutosPorFiltro();

</script>