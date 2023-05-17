<?php

    require_once 'banco.php';

if (isset($_POST['ids'])) {
    // Obtém os IDs a serem excluídos
    $ids = $_POST['ids'];

    // Separa os IDs em um array
    $idArray = explode(',', $ids);

    // Itera sobre os IDs e realiza a exclusão no banco de dados
    foreach ($idArray as $id) {
        // Prepara a instrução SQL de exclusão
        $sql = "DELETE FROM cadrec WHERE id = ?";

        // Prepara a declaração
        $stmt = $conn->prepare($sql);

        // Vincula o parâmetro de ID à declaração
        $stmt->bind_param('i', $id);

        // Executa a declaração
        $stmt->execute();


        // Fecha a declaração
    }

    if ($stmt->affected_rows > 0) {

        // Verifica se a exclusão foi bem-sucedida
        echo "Dado(s) excluído com sucesso.";

    } else {
        echo "Falha ao excluir o dado.";
    }
$stmt->close();

    // Fecha a conexão com o banco de dados
    $conn->close();
} else {
    echo "Nenhum ID definido.";
}

