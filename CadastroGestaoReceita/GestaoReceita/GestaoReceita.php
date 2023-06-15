<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: index.php');
    exit;
}

require_once '../../conexao/banco.php';
require_once 'OrganizarReceita.php';
$id_usuario = $_SESSION['id'];


$sql = "SELECT * FROM cadrec WHERE id_usuario = '$id_usuario'";
$result = $conn->query($sql);
$contagem = 0;
$dataAtual = date("Y-m-d");

if ($result->num_rows > 0) {

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
    <title>Document</title>
    <!-- Flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>
<body>
<table class='tabela-receitas'>
    <tr>
        <th>Tipo da Receita</th>
        <th>Tipo de Recebimento</th>
        <th>Valor da Receita</th>
        <th>Repetições</th>
        <th>Validade do Recebimento</th>
        <th>Recebido</th>
        <th>Informações Complementares</th>


    </tr>
    <?php
    while ($row = $result->fetch_assoc()) {

        $id = $row['id'];

        $repeteFinalizada = $row["repete"];
        $recebidoClass = $row["recebido"] ? "Sim" : "Não";

        $contagem ++;
        [$tipoRec, $tipoRecebe, $repete, $valorRecFormatado, $validade] = organizacao($row["tiporec"], $row["tiporecebe"], $row["repete"], $row["valorrec"], $row['validade']);


        $validadeEN = date("Y-m-d", strtotime(str_replace('/','-', $validade )));

        $novaRepeticao = date("Y-m-d", strtotime($validadeEN . "-20 days"));



           // Verificar se a data atual está dentro do intervalo
           if ($dataAtual == $novaRepeticao && $dataAtual < $validade){

            if($repeteFinalizada == 0){

                $sql = "UPDATE cadrec SET recebido = 1 WHERE id = '$id' AND id_usuario = '$id_usuario'";
                $recebidoClass = "Sim";
                $resultQuery = $conn->query($sql);

            }
            else{

                $sql = "UPDATE cadrec SET recebido = 0 WHERE id = '$id' AND id_usuario = '$id_usuario'";
                $recebidoClass = "Não";
                $resultQuery = $conn->query($sql);
            }
        }


        echo "<tr id = 'linha' onclick=\"abrirModal(this)\" class=\"$recebidoClass\" data-id=\"" . $row["id"] . "\">
        <td>" . $tipoRec . "</td>
        <td>" . $tipoRecebe . "</td>
        <td>R$ " . $valorRecFormatado . "</td>
        <td>" . $repete . "</td>
        <td>" . $validade . "</td>
        <td class=\"rec-col\">" . $recebidoClass . "</td>
        <td>" . $row["infocomp"] . "</td>
        </tr>";
    }


    echo"</table>";
    echo"<hr>";


    // Cálculo para todas as receitas cadastradas no banco de dados
    $sql = "SELECT SUM(valorrec) AS soma_valores, COUNT(*) AS valor_total FROM cadrec WHERE id_usuario = '$id_usuario'";
    $result = $conn->query($sql);
    $dados = $result->fetch_assoc();
    $valorTotal = $dados['soma_valores'];

    // Cálculo para todas as receitass que já foram pagas
    $query = "SELECT SUM(valorrec) AS soma_receitas_recebidas FROM cadrec WHERE recebido = 1 AND id_usuario = '$id_usuario'";
    $resultado = $conn->query($query);
    $recebidos = $resultado->fetch_assoc();
    $valorReceitasPagas = $recebidos['soma_receitas_recebidas'];

    //Cálculo para todas as receitas finalizadas
    $query2 = "SELECT SUM(valorrec) AS soma_receitas_final FROM cadrec WHERE repete = 0 AND id_usuario = '$id_usuario'";
    $resultado2 = $conn->query($query2);
    $finalizados = $resultado2->fetch_assoc();
    $valorReceitasFinalizadas = $finalizados['soma_receitas_final'];

    // Pegando o dado do saldo do banco de dados
    $query4 = "SELECT saldo FROM usuario WHERE id = '$id_usuario' ";
    $resultado3 = $conn->query($query4);
    $consulta2 = $resultado3->fetch_assoc();
    $saldoBanco = $consulta2['saldo'];

    // Cálculo do valor a pagar
    $valorReal = $valorTotal - $valorReceitasFinalizadas;
    $valorAReceber = $valorTotal - $valorReceitasPagas;

    //Conversão dos valores para o sistema monetário brasileiro
    $valorAReceberBR = number_format($valorAReceber, 2, ',', '.');
    $valorRealBr = number_format($valorReal, 2, ',', '.');
    $saldo = number_format($saldoBanco, 2, ',', '.');

    if($contagem == 1){
    $Cadastrados = " Receita Cadastrada";

    }
    else{
        $Cadastrados = " Receitas Cadastradas";
    }

    echo "<h2>Valor de Todas as Receitas: R$ " . $valorRealBr . "</h2>";
    echo "<h2>Valor de Todas as Receitas Pendentes: R$ " . $valorAReceberBR . "</h2>";
    echo "<h2>Número de Registros: " . $contagem . $Cadastrados . "</h2>";
    echo "<h2>Saldo da sua conta: " . $saldo . "</h2>";

?>


<button class="botao-cadastro" onclick="location.href='../CadastroReceita/CadastroReceita.php'">Cadastrar nova receita</button>
<button class="botao-cadastro" onclick="location.href='../../homePage.php'">Página Inicial</button>

   <!-- Modal para receitas pagas -->
   <div id="modalReceitaRecebida" class="modal" data-id="">
    <div class="modal-conteudo">
        <span class="fechar" onclick="fecharModalReceitaR()">&times;</span>
        <h2 id="modalTituloRecebida"></h2>
        <p>Tipo de Recebimento: <span id="modalTipoRecebimentoRecebida"></span></p>
        <p>Valor da Receita: R$ <span id="modalValorRecebida"></span></p>
        <p>Repetições: <span id="modalRepeticaoRecebida"></span></p>
        <p>Validade do Recebimento: <span id="modalValidadeRecebida"></span></p>
        <p>Recebido: <span id="modalRecebidoRecebida"></span></p>
        <p>Data de Recebimento: <span id="modalDataRecebimentoRecebida"></span></p>
        <p>Próxima Repeticao Liberada Dia: <span id="modalProximaRepeticao"></span></p>
        <p>Informações Complementares: <span id="modalInformacoesComplementaresRecebida"></span></p>
        <button class="botao-excluir" name="excluir" onclick="excluirReceita()">Excluir</button>
    </div>
</div>

<!-- Modal para receitas não pagas -->
<div id="modalReceitaNaoRecebida" class="modal" data-id="">
    <div class="modal-conteudo">
        <span class="fechar" onclick="fecharModalReceitaNR()">&times;</span>
        <h2 id="modalTituloNaoRecebida"></h2>
        <p>Tipo de Recebimento: <span id="modalTipoRecebimentoNaoRecebida"></span></p>
        <p>Valor da Receita: R$ <span id="modalValorNaoRecebida"></span></p>
        <p>Repetições: <span id="modalRepeticaoNaoRecebida"></span></p>
        <p>Validade do Recebimento: <span id="modalValidadeNaoRecebida"></span></p>
        <p>Recebido: <span id="modalRecebidoNaoRecebida"></span></p>
        <p>Já recebeu? Insira a data de recebimento aqui: <input id="modalDataRecebimento" class="calendarioReceita" type="text" name="dataRecebimento" onclick="exibirCalendario()" autocomplete="off"></p>
        <p>Informações Complementares: <span id="modalInformacoesComplementaresNaoRecebida"></span></p>
        <button class="botao-pagar" name="pagar" onclick="receberReceita()">Receber</button>
        <button class="botao-excluir" name="excluir" onclick="excluirReceita()">Excluir</button>
    </div>
</div>

<?php } else {
        echo "Nenhum registro encontrado.";
        echo '<button class="botao-cadastro" onclick="location.href=\'../CadastroReceita/CadastroReceita.php\'">Cadastrar nova receita</button>';
    }
?>
</body>


</html>
