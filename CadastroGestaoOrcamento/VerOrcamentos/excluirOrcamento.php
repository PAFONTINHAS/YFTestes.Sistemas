<?php
require_once '../../conexao/banco.php';


if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Prepara a instrução SQL de exclusão
    $sql = "DELETE FROM cadorc WHERE id = ?";

    // Prepara a declaração
    $stmt = $conn->prepare($sql);

    // Vincula o parâmetro de ID à declaração
    $stmt->bind_param('i', $id);

    // Executa a declaração
    if ($stmt->execute()) {
        // Verifica se a exclusão foi bem-sucedida
        echo "Orçamento excluído com sucesso.";
    } else {
        echo "Falha ao excluir a Orçamento. Erro: " . $stmt->error;
    }


    // Fecha a declaração
    $stmt->close();

    // Fecha a conexão com o banco de dados
    $conn->close();
} else {
    echo "Nenhum ID definido.";
}
?>
