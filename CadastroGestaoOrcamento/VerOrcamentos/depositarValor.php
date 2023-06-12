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
        $inserirValorEN = preg_replace('/[^\d,]/', '', $inserirValorBR);
        $inserirValorAtual = str_replace(',', '.', $inserirValorEN);
        $inserirValorAtualDecimal = floatval($inserirValorAtual);
        $valorDepositado = $inserirValorAtualDecimal;


        // Pegando valorAtual do banco de dados
        $query = "SELECT * FROM cadorc WHERE id = '$idOrcamento'";
        $result = $conn->query($query);
        $dadoValor = $result->fetch_assoc();
        $valorAtual = $dadoValor['valorAtual'];
        $orcamento = $dadoValor['valorOrc'];


        //buscando o saldo do banco de dados
        $query  = "SELECT saldo FROM usuario WHERE id = $id";
        $resultado = $conn->query($query);
        $consulta = $resultado->fetch_assoc();
        $saldoBanco = $consulta['saldo'];



        // Subtraindo o valor o orçamento com o valor já depositado
        $subtracao = $orcamento - $valorAtual;


        if($operacao == "subtrair"){

            $novoSaldo = $saldoBanco + $valorDepositado;

        }
        else{
            $novoSaldo = $saldoBanco;
        }
        //TERMINAR A VALIDAÇÃO DE SALVAR OU NÃO O SALDO. CRIAR MAIS UM $SQL PARA ISSO


        // Verificando se o valor sacado é menor que o valor já depositado
        if($valorDepositado <= $subtracao){

            $deposito = $valorAtual + $valorDepositado;

            $sql = "UPDATE cadorc SET valorAtual = '$deposito' WHERE  id = '$idOrcamento'";
            $sql2 = "UPDATE usuario SET saldo = '$novoSaldo' WHERE id = 1";

            if($conn -> query($sql) && $conn -> query($sql2) && $operacao != "subtrair"){

                echo"Valor depositado com êxito";

            }
            elseif($conn -> query($sql) && $conn -> query($sql2) && $operacao == "subtrair"){
                echo "Valor depositado com sucesso e removido do saldo";
            }
            else{
                die('Erro ao Sacar o valor'. $conn->error);
            }
        }


        else{

            if ($valorAtual == $orcamento){

                echo("Você já atingiu o limite do seu orçamento");
            }
            else{
                $subtracaoConvertida = number_format($subtracao, 2, ',', '.');

                echo("Erro: Deposite um valor menor ou igual a R$ " . $subtracaoConvertida);
            }
        }



    }
} else {
    echo "Requisição inválida.";
    return;
}

?>
