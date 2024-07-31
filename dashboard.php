<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema de Cadastro de Alunos</title>
    <style>
        body {
            font-family: 'Roboto Condensed', sans-serif;
            background-color: #f8f8f8;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        header {
            background-color: #4CAF50;
            color: white;
            padding: 15px 20px;
            text-align: center;
            width: 100%;
            box-sizing: border-box;
        }
        h1 {
            margin: 0;
        }
        .container {
            padding: 20px;
            max-width: 800px; /* Define o tamanho máximo dos cartões */
            margin: auto;
            box-sizing: border-box;
        }
        .search-container {
            margin-bottom: 20px;
        }
        input[type="text"] {
            width: calc(100% - 120px); /* Ajusta a largura do campo de pesquisa */
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .sort-buttons {
            margin-bottom: 20px;
        }
        .sort-buttons button {
            padding: 10px;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            margin-right: 10px;
            transition: background-color 0.3s;
        }
        .sort-buttons .sort-name {
            background-color: #2196F3;
            color: white;
        }
        .sort-buttons .sort-course {
            background-color: #4CAF50;
            color: white;
        }
        .sort-buttons .sort-name:hover {
            background-color: #1e88e5;
        }
        .sort-buttons .sort-course:hover {
            background-color: #45a049;
        }
        .cards-container {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .card {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 15px;
            background-color: white;
        }
        .header {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .actions a {
            margin-right: 10px;
        }
        .edit-btn, .delete-btn, .download-btn {
            text-decoration: none;
            border: none;
            border-radius: 4px;
            padding: 8px 12px;
            font-size: 14px;
            cursor: pointer;
            display: inline-block;
            margin: 2px 0;
            text-align: center;
            transition: background-color 0.3s, color 0.3s;
        }
        .edit-btn {
            background-color: #4CAF50;
            color: white;
            border: 1px solid #4CAF50;
        }
        .edit-btn:hover {
            background-color: #45a049;
        }
        .delete-btn {
            background-color: #f44336;
            color: white;
            border: 1px solid #f44336;
        }
        .delete-btn:hover {
            background-color: #e53935;
        }
        .download-btn {
            background-color: #2196F3;
            color: white;
            border: 1px solid #2196F3;
        }
        .download-btn:hover {
            background-color: #1e88e5;
        }
    </style>
</head>
<body>
    <header>
        <h1>DASHBOARD - SISTEMA DE CADASTRO DE ALUNOS</h1>
    </header>
    <main>
        <div class="container">
            <div class="search-container">
                <input type="text" id="search" placeholder="Pesquise por nome...">
            </div>
            <div class="sort-buttons">
                <button class="sort-name">Ordenar por Nome</button>
                <button class="sort-course">Ordenar por Curso</button>
            </div>
            <div class="cards-container" id="cards-container">
                <?php
                // Conecta ao banco de dados
                $conn = new mysqli('localhost', 'root', 'iHNM1995?', 'sistemacomissao');
                if ($conn->connect_error) {
                    die("Conexão falhou: " . $conn->connect_error);
                }

                // Consulta os alunos
                $orderBy = isset($_GET['order']) ? $_GET['order'] : 'aluno'; // Define a ordenação padrão
                $sql = "SELECT * FROM alunos ORDER BY $orderBy ASC";
                $result = $conn->query($sql);

                // Verifica se há alunos
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $musica_link = 'uploads/' . urlencode($row['musica']);
                        $edit_link = "editar.php?id={$row['id']}";
                        $delete_link = "delete.php?id={$row['id']}"; // Link para a página de exclusão
                        echo "<div class='card'>
                            <div class='header'>Aluno</div>
                            <div class='value'>{$row['aluno']}</div>
                            <div class='header'>CGU</div>
                            <div class='value'>{$row['cgu']}</div>
                            <div class='header'>Telefone</div>
                            <div class='value'>{$row['telefone']}</div>
                            <div class='header'>Email</div>
                            <div class='value'>{$row['email']}</div>
                            <div class='header'>Curso</div>
                            <div class='value'>{$row['curso']}</div>
                            <div class='header'>Camiseta</div>
                            <div class='value'>{$row['tamanho_camiseta']}</div>
                            <div class='header'>Música</div>
                            <div class='value'><a href='{$musica_link}' class='download-btn' download>Baixar Música</a></div>
                            <div class='header'>Início da Música</div>
                            <div class='value'>{$row['inicio_musica']}</div>
                            <div class='header'>Quadro do Hall da Fama</div>
                            <div class='value'>{$row['quadro_hall_fama']}</div>
                            <div class='actions'>
                                <a href='{$edit_link}' class='edit-btn'>Alterar</a>
                                <a href='{$delete_link}' class='delete-btn' onclick='return confirm(\"Tem certeza que deseja excluir este aluno?\")'>Excluir</a>
                            </div>
                        </div>";
                    }
                } else {
                    echo "<div class='card'>Nenhum aluno cadastrado</div>";
                }
                $conn->close();
                ?>
            </div>
        </div>
    </main>
    <script>
        document.getElementById('search').addEventListener('input', function() {
            const searchValue = this.value.toLowerCase();
            document.querySelectorAll('.card').forEach(card => {
                const text = card.textContent.toLowerCase();
                card.style.display = text.includes(searchValue) ? '' : 'none';
            });
        });

        document.querySelector('.sort-name').addEventListener('click', function() {
            window.location.search = 'order=aluno';
        });

        document.querySelector('.sort-course').addEventListener('click', function() {
            window.location.search = 'order=curso';
        });
    </script>
</body>
</html>
