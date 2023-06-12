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
    if (isset($_POST["id"]) && isset($_POST["dataRecebimento"])) {
        // Obter os valores dos parâmetros
        $idReceita = $_POST["id"];
        $dataRecebimento = $_POST["dataRecebimento"];

        //Conversão da data de recebimento para o sistema americano de datas
        $dataRecEN = date("Y-m-d", strtotime(str_replace('/', '-', $dataRecebimento)));


        // Operação de subtração da repetição
        $query = "SELECT repete FROM cadrec WHERE id = '$idReceita'";
        $result = $conn->query($query);
        $dadoRepete = $result->fetch_assoc();
        $RepeticaoAtual = $dadoRepete['repete'];
        $novaRepeticao = 0;


        // operação de adição da data de validade
        $query2 = "SELECT validade FROM cadrec WHERE id = '$idReceita'";
        $resultado = $conn -> query($query2);
        $dadoValidade = $resultado->fetch_assoc();
        $validadeAtual = $dadoValidade['validade'];
        $validadeAdd = 0;


        // Pegando o dado do valor do banco de dados
        $query3 = "SELECT valorrec FROM cadrec WHERE id = '$idReceita'";
        $resultado2 = $conn->query($query3);
        $consulta = $resultado2->fetch_assoc();
        $valorBanco = $consulta['valorrec'];

        // Pegando o dado do saldo do banco de dados
        $query4 = "SELECT saldo FROM usuario WHERE id = $id";
        $resultado3 = $conn->query($query4);
        $consulta2 = $resultado3->fetch_assoc();
        $saldoBanco = $consulta2['saldo'];

        $saldoAtual = $saldoBanco + $valorBanco;

        if($RepeticaoAtual == 200){
            $novaRepeticao = $RepeticaoAtual;
            $validadeAdd = date("Y-m-d", strtotime($validadeAtual . "+1 month"));
        }
        elseif($RepeticaoAtual == 0){
            $novaRepeticao = $RepeticaoAtual;
            $validadeAdd = $validadeAtual;
        }
        else{

            $novaRepeticao = $RepeticaoAtual - 1;
            $validadeAdd = date("Y-m-d", strtotime($validadeAtual . "+1 month"));

        }

        // Atualize a receita no banco de dados com a data de recebimento fornecida
        $sql = "UPDATE cadrec SET data_recebimento = '$dataRecEN', repete = '$novaRepeticao', validade = '$validadeAdd', recebido = 1 WHERE id = '$idReceita'";
        $sql2 = "UPDATE usuario SET saldo = '$saldoAtual' WHERE id = 1";

        if ($conn->query($sql) === TRUE && $conn->query($sql2)) {
            echo "receita recebida com sucesso.";
        } else {
            echo "Erro ao receber a receita: " . $conn->error;
        }

        $conn->close();
    } else {
        echo "Parâmetros inválidos.";
    }
} else {
    echo "Requisição inválida.";
}
?>
