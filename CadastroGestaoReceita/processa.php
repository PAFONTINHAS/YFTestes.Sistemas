<?php

require 'banco.php';

if(isset($_POST['Cadastrar'])){

    $tipoReceita = $_POST["TipoReceita"];
    $tipoRecebe = $_POST["TipoRecebe"];
    $valorRec = $_POST["valorRec"];
    $dataRecebe = $_POST["dataRecebe"];
    $repete = $_POST["repete"];
    $infocomp = $_POST["infoComplementares"];

    // if (empty($tipoReceita) || empty($tipoRecebe) || empty($valorRec) || empty($dataRecebe) || empty($repete) || empty($imovelAssociado))
    // {
    //     echo "<script>alert('verifique que todas as informações estão corretas')</script>";
    //     header("Location: CadastroDespesa.php");

    // }

        $sql = "INSERT INTO cadrec(tiporec, tiporecebe, valorrec, datarecebe, repete, infocomp) VALUES ('$tipoReceita','$tipoRecebe','$valorRec','$dataRecebe','$repete', '$infocomp')";

        $query = mysqli_query($conn, $sql);

        header("Location:CadastroReceita.php");


}

