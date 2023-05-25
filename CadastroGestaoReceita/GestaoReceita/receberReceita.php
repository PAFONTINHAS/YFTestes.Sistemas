<?php
require_once '../../conexao/banco.php';

if (isset($_POST['id'])){
    $ids = $_POST['id'];

    // Separa os IDs em um array
    $idArray = explode(',', $ids);

    // Itera sobre os IDs e realiza a atualização no banco de dados
    foreach ($idArray as $id) {
        // Prepara a instrução SQL de atualização
        $sql = "UPDATE cadrec SET recebido = 1 WHERE id = ?";

        // Prepara a declaração
        $stmt = $conn->prepare($sql);

        // Vincula o parâmetro de ID à declaração
        $stmt->bind_param('i', $id);

        // Executa a declaração
        $stmt->execute();

        // Verifica se ocorreu algum erro na execução
        if ($stmt->errno) {
            echo "Erro ao receber receita: " . $stmt->error;
            $stmt->close();
            $conn->close();
            exit(); // Encerra o script em caso de erro
        }

        // Fecha a declaração
        $stmt->close();
    }

    // Fecha a conexão com o banco de dados
    $conn->close();

    echo "Receita marcada como recebida com sucesso.";
} else {
    echo "Nenhum ID definido.";
}
?>
