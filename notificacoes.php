<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: index.php');
    exit;
}


require_once 'conexao/banco.php';

$id_usuario = $_SESSION['id'];



    $sql = "SELECT * FROM notificacoes WHERE id_usuario = '$id_usuario'";
    $result = $conn->query($sql);
    $dados = $result->fetch_assoc();

    $sql2 = "SELECT vencimento FROM caddesp WHERE id_usuario = '$id_usurio'";
    $resultado = $conn->query($sql2);
    $despesa = $resultado->fetch_assoc();
    $vencimento = $despesa['vencimento'];
    

?>


<h1>Bem vindo à sua aba de notificações</h1>




