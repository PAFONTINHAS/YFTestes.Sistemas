<?php
require_once '../../conexao/banco.php';

// Verificar se a requisição é do tipo POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Verificar se os parâmetros foram recebidos corretamente
    if (isset($_POST["id"]) && isset($_POST["inserirValor"])) {
        // Obter os valores dos parâmetros
        $idOrcamento = $_POST["id"];
        $inserirValorBR = $_POST["inserirValor"];

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

        // Subtraindo o valor o orçamento com o valor já depositado
        $subtracao = $orcamento - $valorAtual;

        // Verificando se o valor sacado é menor que o valor já depositado
        if($valorDepositado <= $subtracao){

            $deposito = $valorAtual + $valorDepositado;

            $sql = "UPDATE cadorc SET valorAtual = '$deposito' WHERE  id = '$idOrcamento'";

            $resultado = $conn -> query($sql);

            if($resultado == TRUE){

            echo "Valor depositado com êxito";

            }
            else{
                die('Erro ao Sacar o valor'. $conn->error);
            }
        }else{

            $subtracaoConvertida = number_format($subtracao, 2, ',', '.');

            echo("Erro: Deposite um valor menor ou igual a R$ " . $subtracaoConvertida);
        }



    }
} else {
    echo "Requisição inválida.";
    return;
}

?>
