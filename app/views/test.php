<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Categorias</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }
        input, select, button {
            margin-top: 5px;
            padding: 8px;
            width: 100%;
            max-width: 400px;
        }
        button {
            cursor: pointer;
            background-color: #007bff;
            color: white;
            border: none;
            margin-top: 15px;
        }
        button:hover {
            background-color: #0056b3;
        }
        pre {
            background: #f4f4f4;
            padding: 10px;
            width: 100%;
            max-width: 600px;
            overflow-x: auto;
        }
    </style>
</head>
<body>

    <h2>Gerenciar Categorias</h2>

    <label for="nome">Nome da Categoria:</label>
    <input type="text" id="nome" placeholder="Digite o nome da categoria">

    <label for="descricao">Descrição:</label>
    <input type="text" id="descricao" placeholder="Digite a descrição (opcional)">

    <label for="nivel">Nível:</label>
    <input type="number" id="nivel" value="1" min="1" onchange="mostrarCategoriaPai()">

    <div id="categoriaPaiContainer" style="display: none;">
        <label for="categoriaPai">Categoria Pai:</label>
        <select id="categoriaPai">
            <option value="">Carregando...</option>
        </select>
    </div>

    <button onclick="criarCategoria()">Criar Categoria</button>
    <button onclick="listarCategorias()">Listar Categorias</button>

    <h3>Resultado:</h3>
    <pre id="output"></pre>

    <script>
        const apiUrl = "http://localhost/plugins/produtos/controllers/api_categoria_controller.php";

        async function criarCategoria() {
            const nome = document.getElementById("nome").value.trim();
            const descricao = document.getElementById("descricao").value.trim();
            const nivel = parseInt(document.getElementById("nivel").value, 10);
            let id_categoria_pai = null;

            if (!nome) {
                alert("Por favor, insira um nome para a categoria.");
                return;
            }

            if (nivel > 1) {
                id_categoria_pai = document.getElementById("categoriaPai").value;
                if (!id_categoria_pai) {
                    alert("Por favor, selecione uma categoria pai.");
                    return;
                }
            }

            const data = {
                acao: "criarCategoria",
                nome: nome,
                descricao: descricao || "",
                nivel: nivel,
                id_categoria_pai: id_categoria_pai ? parseInt(id_categoria_pai) : null
            };

            try {
                const response = await fetch(apiUrl, {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify(data)
                });

                const result = await response.json();
                document.getElementById("output").textContent = JSON.stringify(result, null, 2);
            } catch (error) {
                console.error("Erro ao criar categoria:", error);
                document.getElementById("output").textContent = "Erro ao conectar à API.";
            }
        }

        async function listarCategorias() {
            const data = { acao: "listarCategorias" };

            try {
                const response = await fetch(apiUrl, {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify(data)
                });

                const result = await response.json();
                document.getElementById("output").textContent = JSON.stringify(result, null, 2);

                if (result.status === "sucesso" && result.dados.length > 0) {
                    atualizarListaCategoriaPai(result.dados);
                }
            } catch (error) {
                console.error("Erro ao listar categorias:", error);
                document.getElementById("output").textContent = "Erro ao conectar à API.";
            }
        }

        function atualizarListaCategoriaPai(categorias) {
            const select = document.getElementById("categoriaPai");
            select.innerHTML = '<option value="">Selecione uma categoria pai</option>';

            categorias.forEach(categoria => {
                const option = document.createElement("option");
                option.value = categoria.id;
                option.textContent = categoria.nome;
                select.appendChild(option);
            });
        }

        function mostrarCategoriaPai() {
            const nivel = parseInt(document.getElementById("nivel").value, 10);
            const container = document.getElementById("categoriaPaiContainer");

            if (nivel > 1) {
                container.style.display = "block";
                listarCategorias();
            } else {
                container.style.display = "none";
            }
        }
    </script>

</body>
</html>
