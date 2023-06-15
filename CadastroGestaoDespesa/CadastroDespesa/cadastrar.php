<?php

session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: index.php');
    exit;
}


require_once '../../conexao/banco.php';

$id = $_SESSION['id'];

if(isset($_POST['Cadastrar'])){

    $nomeDespesa = $_POST["nome"];
    $categoria = $_POST["categoria"];
    $valor = $_POST["valor"];
    $dataVencimento = $_POST["dataVencimento"];
    $formaPagamento = $_POST["formaPagamento"];
    $imovelAssociado = $_POST["imovelAssociado"];
    $parcela = $_POST["parcelas"];
    $infocomp = $_POST["infoComplementares"];

    // Remover símbolo "R$", pontos de milhar e substituir a vírgula pelo ponto
    // Remover símbolo "R$", pontos de milhar e substituir a vírgula pelo ponto
    $valorDespFormatado = preg_replace('/[^\d,]/', '', $valor); // Resultado: 12,233.12


    // Converter para um número decimal
    $valorDespDecimal = str_replace(',', '.', $valorDespFormatado); // Certifique-se de que a coluna no banco de dados seja do tipo decimal
    $valorDespDecimal = floatval($valorDespDecimal);


    // Restante do código...


        $sql = "INSERT INTO caddesp(id_usuario, nome, categoria, valor, vencimento, formapag, imovelassoc, parcela, infocomp) VALUES ('$id','$nomeDespesa','$categoria','$valorDespDecimal','$dataVencimento','$formaPagamento','$imovelAssociado','$parcela', '$infocomp')";

        $query = mysqli_query($conn, $sql);

        header("Location:CadastroDespesa.php");


}

