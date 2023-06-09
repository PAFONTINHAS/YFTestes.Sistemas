<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: index.php');
    exit;
}

require_once '../../conexao/banco.php';

$id_usuario = $_SESSION['id'];

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


    $sql = "INSERT INTO cadorc (id_usuario, titulo, validade, valorOrc, valorAtual, prioridade, infoComp) VALUES ('$id_usuario','$titulo', '$validade', '$valorOrcDecimal', '$valorOrcAtualDecimal', '$prioridade', '$infoComp')";


    $query = mysqli_query($conn, $sql);

    header("Location:CadastroOrcamento.php");
}
