<?php
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Conectar ao banco de dados
    $conn = new mysqli('localhost', 'root', 'iHNM1995?', 'sistemacomissao');
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    // Preparar e executar a exclusão
    $sql = "DELETE FROM alunos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('Aluno excluído com sucesso!'); window.location.href='dashboard.php';</script>";
    } else {
        echo "<script>alert('Erro ao excluir aluno.'); window.location.href='index.php';</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<script>alert('ID não fornecido.'); window.location.href='index.php';</script>";
}
?>
