<?php

session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: index.php');
    exit;
}

require_once '../../conexao/banco.php';
require 'OrganizarDespesa.php';

$id_usuario = $_SESSION['id'];

// Verificar se foi fornecido um ID válido na query string
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $idDespesa = $_GET['id'];

    // Preparar a consulta SQL e executá-la
    $stmt = $conn->prepare("SELECT * FROM caddesp WHERE id = ? AND id_usuario = '$id_usuario'");
    $stmt->bind_param("i", $idDespesa);
    $stmt->execute();
    $result = $stmt->get_result();



    if ($result->num_rows > 0) {


        $dadosDespesa = $result->fetch_assoc();

        $dataPagamentoEN = $dadosDespesa['data_pagamento'];

        $dataPagamentoBR = date("d/m/Y", strtotime(str_replace('-', '/', $dataPagamentoEN)));

        $nomeDespesa = $dadosDespesa['nome'];

        $infoComp = $dadosDespesa['infocomp'];

        $vencimento = $dadosDespesa['vencimento'];

        [$categoria, $pagamento, $parcela, $imovelAssoc, $valorDespFormatado, $vencimentoBR] = organizacao($dadosDespesa["categoria"],$dadosDespesa["formapag"],  $dadosDespesa["parcela"], $dadosDespesa["imovelassoc"] , $dadosDespesa["valor"], $dadosDespesa['vencimento']);


        $novaParcela = date("Y-m-d", strtotime($vencimento . "-20 days"));

        $parcelaReal = date("d/m/Y", strtotime($novaParcela . "today"));

        $dadosDespesa['categoria'] = $categoria;
        $dadosDespesa['valor'] = $valorDespFormatado;
        $dadosDespesa['parcela'] = $parcela;
        $dadosDespesa['formaPagamento'] = $pagamento;
        $dadosDespesa['imovelAssociado'] = $imovelAssoc;
        $dadosDespesa['dataVencimento'] = $vencimentoBR;
        $dadosDespesa['nomeDespesa'] = $nomeDespesa;
        $dadosDespesa['infoComp'] = $infoComp;

        if($dadosDespesa['pago'] == 1){
            $dadosDespesa['pago'] = "Sim";
        }
        else{
            $dadosDespesa['pago'] = "Não";
        }


        // Agora você pode retornar os dados da despesa como uma resposta JSON
        if($parcela != " Despesa Finalizada"){
             $response = array(
            'despesa' => $dadosDespesa,
            'dataPagamento' => $dataPagamentoBR, // Inclua aqui a data de pagamento da despesa, se disponível
            'novaParcela' => $parcelaReal
        );
        }else{
            $dadosDespesa['dataVencimento'] = $parcela;
            $parcelaReal = $parcela;
            $dadosDespesa['infoComp'] = $parcela;
            $response = array(
                'despesa' => $dadosDespesa,
                'dataPagamento' => $dataPagamentoBR, // Inclua aqui a data de pagamento da despesa, se disponível
                'novaParcela' => $parcelaReal
            );
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        // Caso o ID não corresponda a nenhuma despesa, retorne um erro ou uma resposta vazia, conforme a sua necessidade
        // Por exemplo:
        header('HTTP/1.1 404 Not Found');
        echo "Despesa não encontrada";
    }

    // Fechar a conexão
    $stmt->close();
    $conn->close();
} else {
    // Caso o ID não tenha sido fornecido ou seja inválido, retorne um erro ou uma resposta vazia, conforme a sua necessidade
    // Por exemplo:
    header('HTTP/1.1 400 Bad Request');
    echo "ID de despesa inválido";
}
?>
