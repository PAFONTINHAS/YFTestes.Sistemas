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
        $valorReal = $inserirValorAtualDecimal;


        // Pegando valorAtual do banco de dados
        $query = "SELECT valorAtual FROM cadorc WHERE id = '$idOrcamento'";
        $result = $conn->query($query);
        $dadoValor = $result->fetch_assoc();
        $valor = $dadoValor['valorAtual'];

        // Verificando se o valor sacado é menor que o valor já depositado
        if($valor >= $valorReal){

            $valorSubtraido = $valor - $valorReal;

            $sql = "UPDATE cadorc SET valorAtual = '$valorSubtraido' WHERE  id = '$idOrcamento'";
            $resultado = $conn -> query($sql);

            if($resultado == TRUE){

                echo"Valor sacado com êxito";

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
