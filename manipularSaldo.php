<?php
require_once 'conexao/banco.php';

// Verificar se a requisição é do tipo POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Verificar se os parâmetros foram recebidos corretamente
    if (isset($_POST["SaldoInicial"])) {
        // Obter os valores dos parâmetros
        $saldoInicial = $_POST["SaldoInicial"];

        $inserirSaldo = preg_replace('/[^\d,]/', '', $saldoInicial);
        $inserirSaldoAtual = str_replace(',', '.', $inserirSaldo);
        $saldo = floatval($inserirSaldoAtual);


        if($saldoInicial == NULL){

            $sql = "UPDATE usuario SET saldo = 0.00 WHERE  id = 1";
            $resultado = $conn -> query($sql);

            if($resultado == FALSE){

                die('Erro ao adicionar o Saldo'. $conn->error);

            }
        }
        else{
            // Verificando se o valor sacado é menor que o valor já depositado

            $sql = "UPDATE usuario SET saldo = '$saldo' WHERE  id = 1";
            $resultado = $conn -> query($sql);

            if($resultado == TRUE){

                echo"Saldo Adicionado Com Sucesso!";

            }
            else{
                die('Erro ao adicionar o Saldo'. $conn->error);
            }
        }

        }else{
            echo("Erro.");
        }




} else {
    echo "Requisição inválida.";
    return;
}

?>
