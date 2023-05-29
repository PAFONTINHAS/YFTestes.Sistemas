<?php

require_once '../../conexao/banco.php';


// Verificar se a requisição é do tipo POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Verificar se os parâmetros foram recebidos corretamente
    if (isset($_POST["id"]) && isset($_POST["dataRecebimento"])) {
        // Obter os valores dos parâmetros
        $idReceita = $_POST["id"];
        $dataRecebimento = $_POST["dataRecebimento"];

        //Conversão da data de recebimento para o sistema americano de datas
        $dataRecEN = date("Y-m-d", strtotime(str_replace('/', '-', $dataRecebimento)));


        // Operação de subtração da repetição
        $query = "SELECT repete FROM cadrec WHERE id = '$idReceita'";
        $result = $conn->query($query);
        $dados = $result->fetch_assoc();
        $RepeticaoAtual = $dados['repete'];
        $novaRepeticao = $RepeticaoAtual - 1;


        // Atualize a receita no banco de dados com a data de recebimento fornecida
        $sql = "UPDATE cadrec SET data_recebimento = '$dataRecEN', repete = '$novaRepeticao', recebido = 1 WHERE id = '$idReceita'";

        if ($conn->query($sql) === TRUE) {
            echo "receita recebida com sucesso.";
        } else {
            echo "Erro ao receber a receita: " . $conn->error;
        }

        $conn->close();
    } else {
        echo "Parâmetros inválidos.";
    }
} else {
    echo "Requisição inválida.";
}
?>
