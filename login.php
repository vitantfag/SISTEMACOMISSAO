<?php
session_start();

// Conexão com o banco de dados
$servername = 'localhost';
$username = 'root';
$password = 'iHNM1995?';
$dbname = 'sistemacomissao';

// Cria conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    // Prepara e executa a consulta ao banco de dados
    $stmt = $conn->prepare("SELECT senha FROM administrador WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $stmt->bind_result($stored_password);
    $stmt->fetch();

    // Verifica a senha
    if ($senha === $stored_password) {
        $_SESSION['loggedin'] = true;
        header('Location: dashboard.php');
        exit;
    } else {
        echo "Usuário ou senha incorretos.";
    }

    // Fecha a conexão
    $stmt->close();
    $conn->close();
}
?>