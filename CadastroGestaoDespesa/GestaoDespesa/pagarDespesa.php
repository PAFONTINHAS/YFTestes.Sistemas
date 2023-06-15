<?php

session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: index.php');
    exit;
}
$id_usuario = $_SESSION['id'];

require_once '../../conexao/banco.php';

// Verificar se a requisição é do tipo POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Verificar se os parâmetros foram recebidos corretamente
    if (isset($_POST["id"]) && isset($_POST["dataPagamento"])) {
        // Obter os valores dos parâmetros
        $idDespesa = $_POST["id"];
        $dataPagamento = $_POST["dataPagamento"];

        //Conversão da data de pagamento para os sistema americano
        $dataReal = date("Y-m-d", strtotime(str_replace('/', '-', $dataPagamento)));

        // Pegando o dado da parcela do banco de dados
        $query = "SELECT parcela FROM caddesp WHERE id = '$idDespesa' AND id_usuario = '$id_usuario'";
        $result = $conn->query($query);
        $dados = $result->fetch_assoc();
        $parcelaAtual = $dados['parcela'];
        $novaParcela = 0;


        // Pegando o dado do vencimento do banco de dados
        $query2 = "SELECT vencimento FROM caddesp WHERE id = '$idDespesa' AND id_usuario = '$id_usuario'";
        $resultado = $conn -> query($query2);
        $dadoVencimento = $resultado->fetch_assoc();
        $vencimentoAtual = $dadoVencimento['vencimento'];
        $vencimentoAdd;

        // Pegando o dado do valor do banco de dados
        $query3 = "SELECT valor FROM caddesp WHERE id = '$idDespesa' AND id_usuario = '$id_usuario'";
        $resultado3 = $conn->query($query3);
        $consulta = $resultado3->fetch_assoc();
        $valorBanco = $consulta['valor'];

        // Pegando o dado do saldo do banco de dados
        $query4 = "SELECT saldo FROM usuario WHERE id = '$id_usuario'";
        $resultado4 = $conn->query($query4);
        $consulta2 = $resultado4->fetch_assoc();
        $saldoBanco = $consulta2['saldo'];


        // calculando o valor atual do saldo
        $saldoAtual = $saldoBanco - $valorBanco;


        if($parcelaAtual == 0){
            $novaParcela = $parcelaAtual;
            $vencimentoAdd = $vencimentoAtual;
        }
        else{

            $novaParcela = $parcelaAtual - 1;
            $vencimentoAdd = date("Y-m-d", strtotime($vencimentoAtual . "+1 month"));

        }

        // Atualize a despesa no banco de dados com a data de pagamento fornecida
        $sql = "UPDATE caddesp SET data_pagamento = '$dataReal', pago = 1, parcela = '$novaParcela', vencimento = '$vencimentoAdd' WHERE id = '$idDespesa' AND id_usuario = '$id_usuario'";
        $sql2 = "UPDATE usuario SET saldo = '$saldoAtual' WHERE id = '$id_usuario'";

        if ($conn->query($sql) === TRUE && $conn->query($sql2) == TRUE) {
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
