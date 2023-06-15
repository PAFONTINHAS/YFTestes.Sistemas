<?php

session_start();

if (!isset($_SESSION['id_usuario']) || empty($_SESSION['id_usuario'])) {

    header('Location: ../../index.php'); // Redireciona para a página de login
    exit();
}

$id_usuario = $_SESSION['id_usuario'];


echo '<script>';
echo 'var id_usuario = ' . json_encode($id_usuario) .';';
echo '</script>';

// require __DIR__ . "/../../conexao/banco.php";
require __DIR__ . "/../../classes/Receita.php";


$receita = new Receita($db);


$dadosTabela = $receita->verReceita($id_usuario);


if ($dadosTabela->rowCount() > 0) {
$contagem = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="gestaoReceita.css">
<title>Gestão de Receitas</title>
<script src="obterDadosModal/modalReceita.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

</head>
<body>
<table class='tabela-Receitas'>
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

    while ($row = $dadosTabela->fetch(PDO::FETCH_ASSOC)) {

        $contagem ++;
        $recebidoClass = $row["recebido"] ? "Sim" : "Não";



        [$tipoRec, $tipoRecebe, $repete, $valorRecFormatado, $validade] = $receita->organizacao($row["tiporec"], $row["tiporecebe"], $row["repete"], $row["valorrec"], $row['validade']);


        date_default_timezone_set('America/Sao_Paulo'); // Definindo o fuso horário como São Paulo

        $id = $row['id'];
        $dataAtual  = date("Y-m-d");

        $receita->atualizarRecebimento($id, $id_usuario, $dataAtual, $validade);

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

    echo "</tr>";
    echo"</table>";
    echo"<hr>";



    if($contagem == 1){
        $Cadastrados = " Receita Cadastrada";

    }
    else{
        $Cadastrados = " Receitas Cadastradas";
    }

    $dadosCalculados = $receita->calcularValores($id_usuario);

    // Atribua os valores calculados a variáveis individuais
    $valorTotal = $dadosCalculados['valorTotal'];
    $valorAReceber = $dadosCalculados['valorAReceber'];
    $saldo = $dadosCalculados['saldo'];

    echo "<h2>Valor de Todas as Receitas: R$ " . $valorTotal . "</h2>";
    echo "<h2>Valor de Todas as Receitas Pendentes: R$ " . $valorAReceber   . "</h2>";
    echo "<h2>Número de Registros: " . $contagem . $Cadastrados . "</h2>";
    echo "<h2>Saldo da sua conta: R$ " . $saldo . "</h2>";


    echo '<button class="botao-cadastro" onclick="location.href=\'../Cadastro/cadastroReceita.php\'">Cadastrar nova receita</button>';
    echo '<button class="botao-cadastro" onclick="location.href=\'../../PaginaInicial.php\'">Página Inicial</button>';

?>


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
        <button class="botao-receber" name="pagar" onclick="receberReceita()">Receber</button>
        <button class="botao-excluir" name="excluir" onclick="excluirReceita()">Excluir</button>
    </div>
</div>

<?php
}
else {
echo "Nenhum registro encontrado.";
echo '<button class="botao-cadastro" onclick="location.href=\'../Cadastro/cadastroReceita.php\'">Cadastrar nova receita</button>';

}

?>


</body>
</html>



