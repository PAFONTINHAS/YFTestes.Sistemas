<?php

session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: index.php');
    exit;
}
$id_usuario = $_SESSION['id'];

require_once '../../conexao/banco.php';

if(isset($_POST['Cadastrar'])){

    $tipoReceita = $_POST["TipoReceita"];
    $tipoRecebe = $_POST["TipoRecebe"];
    $valorRec = $_POST["valorRec"];
    $validade = $_POST["validade"];
    $repete = $_POST["repete"];
    $infocomp = $_POST["infoComplementares"];

        // Remover símbolo "R$", pontos de milhar e substituir a vírgula pelo ponto
    // Remover símbolo "R$", pontos de milhar e substituir a vírgula pelo ponto
    $valorRecFormatado = preg_replace('/[^\d,]/', '', $valorRec); // Resultado: 12,233.12

    echo $valorRecFormatado;

    // Converter para um número decimal
    $valorRecDecimal = str_replace(',', '.', $valorRecFormatado); // Certifique-se de que a coluna no banco de dados seja do tipo decimal
    $valorRecDecimal = floatval($valorRecDecimal);

    echo "<br>".$valorRecDecimal;

    // Restante do código...



        $sql = "INSERT INTO cadrec(id_usuario,tiporec, tiporecebe, valorrec, validade, repete, infocomp) VALUES ('$id_usuario','$tipoReceita','$tipoRecebe','$valorRecDecimal','$validade','$repete', '$infocomp')";

        $query = mysqli_query($conn, $sql);

        header("Location:CadastroReceita.php");


}

