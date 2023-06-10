<?php

require_once '../../conexao/banco.php';
require_once 'OrganizarReceita.php';

$sql = "SELECT * FROM cadrec";
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


        $recebidoClass = $row["recebido"] ? "Sim" : "Não";

        $contagem ++;
        [$tipoRec, $tipoRecebe, $repete, $valorRecFormatado, $validade] = organizacao($row["tiporec"], $row["tiporecebe"], $row["repete"], $row["valorrec"], $row['validade']);


        $validadeEN = date("Y-m-d", strtotime(str_replace('/','-', $validade )));

        $novaRepeticao = date("Y-m-d", strtotime($validadeEN . "-20 days"));

        $repeticaoAcima = date("Y-m-d", strtotime($validadeEN . "+1 days"));


      // Converter as datas para objetos DateTime
        $dataAtualObj = new DateTime($dataAtual);
        $novaRepeticaoObj = new DateTime($novaRepeticao);
        $repeticaoAcimaObj = new DateTime($repeticaoAcima);

        if ($dataAtualObj >= $novaRepeticaoObj && $dataAtualObj < $repeticaoAcimaObj) {
            $recebidoClass = "Não";
            $sql = "UPDATE cadrec SET recebido = 0 WHERE id = '$id'";
            $resultQuery = $conn->query($sql);

        }elseif($dataAtualObj != $novaRepeticaoObj && $dataAtual < $repeticaoAcimaObj ){
            if($row['recebido'] == 1){
                $recebidoClass = "Sim";
                $sql2 = "UPDATE cadrec SET recebido = 1 WHERE id = '$id'";
                $resultQuery2 = $conn->query($sql2);
            }
            else{
                $recebidoClass = "Não";
                $sql2 = "UPDATE cadrec SET recebido = 0 WHERE id = '$id'";
                $resultQuery2 = $conn->query($sql2);
            }

        }
        else{
            return;
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
      $sql = "SELECT SUM(valorrec) AS soma_valores, COUNT(*) AS valor_total FROM cadrec";
      $result = $conn->query($sql);
      $dados = $result->fetch_assoc();
      $valorTotal = $dados['soma_valores'];

      // Cálculo para todas as despesas que já foram pagas
      $query = "SELECT SUM(valorrec) AS soma_receitas_recebidas FROM cadrec WHERE recebido = 1";
      $resultado = $conn->query($query);
      $recebidos = $resultado->fetch_assoc();
      $valorDespesasPagas = $recebidos['soma_receitas_recebidas'];

      //Cálculo para todas as despesas finalizadas
      $query2 = "SELECT SUM(valorrec) AS soma_receitas_final FROM cadrec WHERE repete = 0";
      $resultado2 = $conn->query($query2);
      $finalizados = $resultado2->fetch_assoc();
      $valorDespesasFinalizadas = $finalizados['soma_receitas_final'];

      // Cálculo do valor a pagar
      $valorReal = $valorTotal - $valorDespesasFinalizadas;
      $valorAReceber = $valorTotal - $valorDespesasPagas;

      //Conversão dos valores para o sistema monetário brasileiro
      $valorAReceberBR = number_format($valorAReceber, 2, ',', '.');
      $valorRealBr = number_format($valorReal, 2, ',', '.');

      if($contagem == 1){
        $Cadastrados = " Despesa Cadastrada";

        }
        else{
            $Cadastrados = " Despesas Cadastradas";
        }

      echo "<h2>Valor de Todas as Receitas: R$ " . $valorRealBr . "</h2>";
      echo "<h2>Valor de Todas as Receitas Pendentes: R$ " . $valorAReceberBR . "</h2>";
      echo "<h2>Número de Registros: " . $contagem . $Cadastrados . "</h2>";

?>


<button class="botao-cadastro" onclick="location.href='../CadastroReceita/CadastroReceita.php'">Cadastrar nova receita</button>

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
