<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: index.php');
    exit;
}


require_once '../../conexao/banco.php';

$id_usuario = $_SESSION['id'];

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Prepara a instrução SQL de exclusão
    $sql = "DELETE FROM caddesp WHERE id = ? AND id_usuario = '$id_usuario'";

    // Prepara a declaração
    $stmt = $conn->prepare($sql);

    // Vincula o parâmetro de ID à declaração
    $stmt->bind_param('i', $id);

    // Executa a declaração
    if ($stmt->execute()) {
        // Verifica se a exclusão foi bem-sucedida
        echo "Despesa excluída com sucesso.";
    } else {
        echo "Falha ao excluir a despesa. Erro: " . $stmt->error;
    }


    // Fecha a declaração
    $stmt->close();

    // Fecha a conexão com o banco de dados
    $conn->close();
} else {
    echo "Nenhum ID definido.";
}
?>
