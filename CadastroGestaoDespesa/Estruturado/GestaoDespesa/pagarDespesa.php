<?php

require_once '../../../conexao/banco.php';

// Verificar se a requisição é do tipo POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Verificar se os parâmetros foram recebidos corretamente
    if (isset($_POST["id"]) && isset($_POST["dataPagamento"])) {
        // Obter os valores dos parâmetros
        $idDespesa = $_POST["id"];
        $dataPagamento = $_POST["dataPagamento"];

        //Conversão da data de pagamento para os sistema americano
        $dataReal = date("Y-m-d", strtotime(str_replace('/', '-', $dataPagamento)));

        // Operação de subtração da parcela
        $query = "SELECT parcela FROM caddesp WHERE id = '$idDespesa'";
        $result = $conn->query($query);
        $dados = $result->fetch_assoc();
        $parcelaAtual = $dados['parcela'];
        $novaParcela = $parcelaAtual - 1;

        // Atualize a despesa no banco de dados com a data de pagamento fornecida
        $sql = "UPDATE caddesp SET data_pagamento = '$dataReal', pago = 1, parcela = '$novaParcela' WHERE id = '$idDespesa'";

        if ($conn->query($sql) === TRUE) {
            echo "Despesa paga com sucesso.";
        } else {
            echo "Erro ao pagar a despesa: " . $conn->error;
        }

        $conn->close();
    } else {
        echo "Parâmetros inválidos.";
    }
} else {
    echo "Requisição inválida.";
}
?>
