<?php
session_start();

// Configurações do banco de dados
$servername = 'localhost';
$username = 'root';
$password = 'iHNM1995?';
$dbname = 'sistemacomissao';

// Conecta ao banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $aluno = $_POST['aluno'];
    $cgu = $_POST['cgu'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $curso = $_POST['curso'];
    $tamanho = isset($_POST['tamanho']) ? implode(", ", $_POST['tamanho']) : '';
    $inicioMusica = $_POST['inicioMusica'];
    $quadroHallFama = $_POST['quadroHallFama'];

    // Processa o upload do arquivo MP3
    if (isset($_FILES['musica']) && $_FILES['musica']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        // Verifica se o diretório existe, se não, cria-o
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $uploadFile = $uploadDir . basename($_FILES['musica']['name']);

        // Move o arquivo para o diretório de uploads
        if (move_uploaded_file($_FILES['musica']['tmp_name'], $uploadFile)) {
            echo "Arquivo enviado com sucesso.";
            $musica = basename($_FILES['musica']['name']);
        } else {
            echo "Falha ao enviar o arquivo.";
            $musica = ''; // Define $musica como vazio se o upload falhar
        }
    } else {
        $musica = ''; // Define $musica como vazio se não houver arquivo
    }

    // Insere os dados no banco de dados
    $stmt = $conn->prepare("INSERT INTO alunos (aluno, cgu, telefone, email, curso, tamanho_camiseta, musica, inicio_musica, quadro_hall_fama) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $aluno, $cgu, $telefone, $email, $curso, $tamanho, $musica, $inicioMusica, $quadroHallFama);
    
    if ($stmt->execute()) {
        echo "Cadastro realizado com sucesso.";
    } else {
        echo "Erro ao cadastrar: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
