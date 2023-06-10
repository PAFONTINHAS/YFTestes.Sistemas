<?php

require_once '../../conexao/banco.php';

if (isset($_POST['Criar'])){

    $titulo = $_POST['titulo'];
    $validade = $_POST['validade'];
    $valorOrcamento = $_POST['valorOrcamento'];
    $valorAtual = $_POST['valorAtual'];
    $prioridade = $_POST['prioridade'];
    $infoComp = $_POST['infoComp'];

    // Convertendo o Valor do Orçamento para o sistema monetário americano
    $valorOrcFormatado = preg_replace('/[^\d,]/', '', $valorOrcamento);
    $valorOrcDecimal = str_replace(',', '.', $valorOrcFormatado);
    $valorOrcDecimal = floatval($valorOrcDecimal);

    // Convertendo o Valor Atual para o sistema monetário americano
    $valorOrcAtualFormatado = preg_replace('/[^\d,]/', '', $valorAtual);
    $valorOrcAtualDecimal = str_replace(',', '.', $valorOrcAtualFormatado);
    $valorOrcAtualDecimal = floatval($valorOrcAtualDecimal);


    $sql = "INSERT INTO cadorc (titulo, validade, valorOrc, valorAtual, prioridade, infoComp) VALUES ('$titulo', '$validade', '$valorOrcDecimal', '$valorOrcAtualDecimal', '$prioridade', '$infoComp')";


    $query = mysqli_query($conn, $sql);

    header("Location:CadastroOrcamento.php");
}
