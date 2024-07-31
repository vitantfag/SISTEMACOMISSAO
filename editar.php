<?php
// Conecta ao banco de dados
$conn = new mysqli('localhost', 'root', 'iHNM1995?', 'sistemacomissao');
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Verifica se o ID do aluno foi fornecido
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Busca o aluno
    $sql = "SELECT * FROM alunos WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $aluno = $result->fetch_assoc();
    } else {
        die("Aluno não encontrado.");
    }
} else {
    die("ID do aluno não fornecido.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Atualiza os dados do aluno
    $nome = $conn->real_escape_string($_POST['aluno']);
    $cgu = $conn->real_escape_string($_POST['cgu']);
    $telefone = $conn->real_escape_string($_POST['telefone']);
    $email = $conn->real_escape_string($_POST['email']);
    $curso = $conn->real_escape_string($_POST['curso']);
    $tamanho_camiseta = $conn->real_escape_string($_POST['tamanho_camiseta']);
    $inicio_musica = $conn->real_escape_string($_POST['inicio_musica']);
    $quadro_hall_fama = $conn->real_escape_string($_POST['quadro_hall_fama']);

    // Verifica se há um arquivo de música novo
    if (isset($_FILES['musica']) && $_FILES['musica']['error'] == UPLOAD_ERR_OK) {
        $musica_nome = $_FILES['musica']['name'];
        $musica_tmp = $_FILES['musica']['tmp_name'];
        $musica_ext = pathinfo($musica_nome, PATHINFO_EXTENSION);

        // Verifica se a música anterior existe e a remove
        if (!empty($aluno['musica'])) {
            $musica_antiga = 'uploads/' . urlencode($aluno['musica']);
            if (file_exists($musica_antiga)) {
                unlink($musica_antiga);
            }
        }

        // Move o arquivo de música para o diretório de uploads
        $musica_novo_nome = time() . '.' . $musica_ext;
        $musica_destino = 'uploads/' . $musica_novo_nome;
        move_uploaded_file($musica_tmp, $musica_destino);
        $musica = $conn->real_escape_string($musica_novo_nome);
    } else {
        $musica = $aluno['musica'];
    }

    $sql = "UPDATE alunos SET aluno='$nome', cgu='$cgu', telefone='$telefone', email='$email', curso='$curso', tamanho_camiseta='$tamanho_camiseta', inicio_musica='$inicio_musica', quadro_hall_fama='$quadro_hall_fama', musica='$musica' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "Aluno atualizado com sucesso. <a href='dashboard.php'>Voltar para o Dashboard</a>";
    } else {
        echo "Erro ao atualizar aluno: " . $conn->error;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Aluno</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        header {
            background-color: #4CAF50;
            color: white;
            padding: 15px 0;
            text-align: center;
        }

        main {
            padding: 20px 0;
        }

        .container {
            max-width: 800px;
            width: 100%;
            margin: 0 auto;
            padding: 20px;
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        input[type="text"], input[type="file"] {
            width: calc(100% - 24px);
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            box-sizing: border-box;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .current-music {
            margin-bottom: 20px;
        }

        .current-music a {
            color: #2196F3;
            text-decoration: none;
        }

        .current-music a:hover {
            text-decoration: underline;
        }

        .back-button {
            display: inline-block;
            background-color: #2196F3;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            font-size: 16px;
            margin-top: 20px;
        }

        .back-button:hover {
            background-color: #0b7dda;
        }

        /* Ocultar o conteúdo em telas menores que 350px */
        @media (max-width: 349px) {
            body {
                display: none;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>EDITAR ALUNO</h1>
    </header>
    <main>
        <div class="container">
            <form action="" method="post" enctype="multipart/form-data">
                <label for="aluno">Nome:</label>
                <input type="text" id="aluno" name="aluno" value="<?php echo htmlspecialchars($aluno['aluno']); ?>" required>

                <label for="cgu">CGU:</label>
                <input type="text" id="cgu" name="cgu" value="<?php echo htmlspecialchars($aluno['cgu']); ?>" required>

                <label for="telefone">Telefone:</label>
                <input type="text" id="telefone" name="telefone" value="<?php echo htmlspecialchars($aluno['telefone']); ?>" required>

                <label for="email">Email:</label>
                <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($aluno['email']); ?>" required>

                <label for="curso">Curso:</label>
                <input type="text" id="curso" name="curso" value="<?php echo htmlspecialchars($aluno['curso']); ?>" required>

                <label for="tamanho_camiseta">Tamanho da Camiseta:</label>
                <input type="text" id="tamanho_camiseta" name="tamanho_camiseta" value="<?php echo htmlspecialchars($aluno['tamanho_camiseta']); ?>" required>

                <label for="inicio_musica">Início da Música:</label>
                <input type="text" id="inicio_musica" name="inicio_musica" value="<?php echo htmlspecialchars($aluno['inicio_musica']); ?>" required>

                <label for="quadro_hall_fama">Quadro do Hall da Fama:</label>
                <input type="text" id="quadro_hall_fama" name="quadro_hall_fama" value="<?php echo htmlspecialchars($aluno['quadro_hall_fama']); ?>" required>

                <label for="musica">Música:</label>
                <input type="file" id="musica" name="musica">

                <?php if (!empty($aluno['musica'])): ?>
                    <div class="current-music">
                        <p>Música atual: <a href="uploads/<?php echo urlencode($aluno['musica']); ?>" target="_blank"><?php echo htmlspecialchars($aluno['musica']); ?></a></p>
                    </div>
                <?php endif; ?>

                <input type="submit" value="Atualizar">
            </form>
            <a class="back-button" href="dashboard.php">Voltar para o Dashboard</a>
        </div>
    </main>
</body>
</html>
