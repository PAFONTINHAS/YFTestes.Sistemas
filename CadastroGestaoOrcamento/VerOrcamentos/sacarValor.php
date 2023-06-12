<?php

session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: index.php');
    exit;
}
$id = $_SESSION['id'];


require_once '../../conexao/banco.php';

// Verificar se a requisição é do tipo POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Verificar se os parâmetros foram recebidos corretamente
    if (isset($_POST["id"]) && isset($_POST["inserirValor"]) && isset($_POST["operacao"])) {
        // Obter os valores dos parâmetros
        $idOrcamento = $_POST["id"];
        $inserirValorBR = $_POST["inserirValor"];
        $operacao = $_POST['operacao'];

         // Convertendo o Valor Atual para o sistema monetário americano
         // Removendo qualquer tipo de carcter do input
        $inserirValorEN = preg_replace('/[^\d,]/', '', $inserirValorBR);
        $inserirValorAtual = str_replace(',', '.', $inserirValorEN);
        $inserirValorAtualDecimal = floatval($inserirValorAtual);
        $valorReal = $inserirValorAtualDecimal;


        // Pegando valorAtual do banco de dados
        $query = "SELECT valorAtual FROM cadorc WHERE id = '$idOrcamento'";
        $result = $conn->query($query);
        $dadoValor = $result->fetch_assoc();
        $valor = $dadoValor['valorAtual'];

        //buscando o saldo do banco de dados
        $query  = "SELECT saldo FROM usuario WHERE id = $id";
        $resultado = $conn->query($query);
        $consulta = $resultado->fetch_assoc();
        $saldoBanco = $consulta['saldo'];


        if($operacao == "somar"){

            $novoSaldo = $saldoBanco + $valorReal;

        }
        else{
            $novoSaldo = $saldoBanco;
        }
        // Verificando se o valor sacado é menor que o valor já depositado
        if($valor >= $valorReal){

            $valorSubtraido = $valor - $valorReal;

            $sql = "UPDATE cadorc SET valorAtual = '$valorSubtraido' WHERE  id = '$idOrcamento'";
            $sql2 = "UPDATE usuario SET saldo = '$novoSaldo' WHERE id = 1";


            if($conn -> query($sql) && $conn -> query($sql2) && $operacao != "somar"){

                echo"Valor sacado com êxito";

            }
            elseif($conn -> query($sql) && $conn -> query($sql2) && $operacao == "somar"){
                echo "Valor sacado com sucesso e adicionado ao saldo";
            }
            else{
                die('Erro ao Sacar o valor'. $conn->error);
            }
        }else{
            echo("Não é possível transformar o valor negativo");
        }
    }
} else {
    echo "Requisição inválida.";
    return;
}

?>
