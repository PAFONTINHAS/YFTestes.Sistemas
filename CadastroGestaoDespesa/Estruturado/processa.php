<?php

require 'banco.php';

if(isset($_POST['Cadastrar'])){

    $nomeDespesa = $_POST["nome"];
    $categoria = $_POST["categoria"];
    $valor = $_POST["valor"];
    $dataVencimento = $_POST["dataVencimento"];
    $formaPagamento = $_POST["formaPagamento"];
    $imovelAssociado = $_POST["imovelAssociado"];
    $parcela = $_POST["parcelas"];
    $infocomp = $_POST["infoComplementares"];

    // if (empty($nomeDespesa) || empty($categoria) || empty($valor) || empty($dataVencimento) || empty($formaPagamento) || empty($imovelAssociado))
    // {
    //     echo "<script>alert('verifique que todas as informações estão corretas')</script>";
    //     header("Location: CadastroDespesa.php");

    // }

        $sql = "INSERT INTO caddesp(nome, categoria, valor, vencimento, formapag, imovelassoc, parcela, infocomp) VALUES ('$nomeDespesa','$categoria','$valor','$dataVencimento','$formaPagamento','$imovelAssociado','$parcela', '$infocomp')";

        $query = mysqli_query($conn, $sql);

        header("Location:CadastroDespesa.php");


}

