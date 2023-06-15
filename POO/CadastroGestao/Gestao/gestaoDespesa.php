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
require __DIR__ . "/../../classes/Despesa.php";


$despesa = new Despesa($db);


$dadosTabela = $despesa->verDespesa($id_usuario);


if ($dadosTabela->rowCount() > 0) {
$contagem = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="gestaoDespesa.css">
<title>Gestão de Despesas</title>
<script src="obterDadosModal/modalDespesa.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>


</head>
<body>
<table class='tabela-despesas'>
    <tr>
        <th>Nome da Despesa</th>
        <th>Categoria</th>
        <th>Valor</th>
        <th>Parcela</th>
        <th>Forma de Pagamento</th>
        <th>Imóvel Associado</th>
        <th>Data de Vencimento</th>
        <th>Pago</th>
        <th>Informações Complementares</th>
    </tr>
    <?php

    while ($row = $dadosTabela->fetch(PDO::FETCH_ASSOC)) {

        $pago = $row["pago"];
        $infocomp = $row["infocomp"];

        $contagem ++;


        $pagoClass = ($row["pago"] == 1) ? "Sim" : "Não";


        [$categoria, $pagamento, $parcela, $imovelAssoc, $valorDespFormatado, $vencimentoBR  ] = $despesa->organizacao($row["categoria"],$row["formapag"],  $row["parcela"], $row["imovelassoc"] , $row["valor"], $row['vencimento']);


        date_default_timezone_set('America/Sao_Paulo'); // Definindo o fuso horário como São Paulo

        $id = $row['id'];
        $dataAtual  = date("Y-m-d");




        $despesa->atualizarPagamento($id, $id_usuario, $dataAtual, $vencimentoBR);

        echo "<tr id='linha' onclick=\"abrirModal(this)\" class=\"$pagoClass\" data-id=\"" . $id. "\">
            <td>" . $row["nome"] . "</td>
            <td>" . $categoria . "</td>
            <td> R$ " . $valorDespFormatado . "</td>
            <td>" . $parcela . "</td>
            <td>" . $pagamento. "</td>
            <td>" . $imovelAssoc . "</td>
            <td>" . $vencimentoBR . "</td>
            <td class=\"pago-col\">" . $pagoClass . "</td>
            <td>" . $row["infocomp"] . "</td>
        </tr>";




    }

    echo "</tr>";
    echo"</table>";
    echo"<hr>";



    if($contagem == 1){
        $Cadastrados = " Despesa Cadastrada";

    }
    else{
        $Cadastrados = " Despesas Cadastradas";
    }

    $dadosCalculados = $despesa->calcularValores($id_usuario);

    // Atribua os valores calculados a variáveis individuais
    $valorTotal = $dadosCalculados['valorTotal'];
    $valorAPagarBR = $dadosCalculados['valorAPagar'];
    $saldo = $dadosCalculados['saldo'];

    echo "<h2>Valor de Todas as Despesas: R$ " . $valorTotal . "</h2>";
    echo "<h2>Valor de Todas as Despesas Pendentes: R$ " . $valorAPagarBR   . "</h2>";
    echo "<h2>Número de Registros: " . $contagem . $Cadastrados . "</h2>";
    echo "<h2>Saldo da sua conta: R$ " . $saldo . "</h2>";


    echo '<button class="botao-cadastro" onclick="location.href=\'../Cadastro/cadastroDespesa.php\'">Cadastrar nova despesa</button>';
    echo '<button class="botao-cadastro" onclick="location.href=\'../../PaginaInicial.php\'">Página Inicial</button>';

?>


<!-- Modal para despesas pagas -->
<div id="modalDespesaPaga" class="modal" data-id="">
<div class="modal-conteudo">
    <span class="fechar" onclick="fecharModalDP()">&times;</span>
    <h2 id="modalTituloPaga"></h2>
    <p>Categoria: <span id="modalCategoriaPaga"></span></p>
    <p>Valor: R$ <span id="modalValorPaga"></span></p>
    <p>Parcela: <span id="modalParcelaPaga"></span></p>
    <p>Forma de Pagamento: <span id="modalFormaPagamentoPaga"></span></p>
    <p>Imóvel Associado: <span id="modalImovelAssociadoPaga"></span></p>
    <p>Data de Vencimento: <span id="modalDataVencimentoPaga"></span></p>
    <p>Pago: <span id="modalPagoPaga"></span></p>
    <p>Data de Pagamento: <span id="modalDataPagamentoPaga"></span></p>
    <p>Próxima Parcela Liberada Dia:<span id="modalNovaParcela"></span></p>
    <p>Informações Complementares: <span id="modalInformacoesComplementaresPaga"></span></p>
    <button class="botao-excluir" name="excluir" onclick="excluirDespesa()">Excluir</button>
</div>
</div>

<!-- Modal para despesas não pagas -->
<div id="modalDespesaNaoPaga" class="modal" data-id="">
<div class="modal-conteudo">
    <span class="fechar" onclick="fecharModalDNP()">&times;</span>
    <h2 id="modalTituloNaoPaga"></h2>
    <p>Categoria: <span id="modalCategoriaNaoPaga"></span></p>
    <p>Valor: R$ <span id="modalValorNaoPaga"></span></p>
    <p>Parcela: <span id="modalParcelaNaoPaga"></span></p>
    <p>Forma de Pagamento: <span id="modalFormaPagamentoNaoPaga"></span></p>
    <p>Imóvel Associado: <span id="modalImovelAssociadoNaoPaga"></span></p>
    <p>Data de Vencimento: <span id="modalDataVencimentoNaoPaga"></span></p>
    <p>Pago: <span id="modalPagoNaoPaga"></span></p>
    <p>Quer pagar ou já pagou? Insira a data aqui: <input id="modalDataPagamentoNaoPaga" class="calendario" type="text" name="dataPagamento" onclick="exibirCalendario()" autocomplete="off"></p>
    <p>Informações Complementares: <span id="modalInformacoesComplementaresNaoPaga"></span></p>
    <button class="botao-pagar" name="pagar" onclick="pagarDespesa()">Pagar</button>
    <button class="botao-excluir" name="excluir" onclick="excluirDespesa()">Excluir</button>
</div>
</div>

<?php
}
else {
echo "Nenhum registro encontrado.";
echo '<button class="botao-cadastro" onclick="location.href=\'../Cadastro/cadastroDespesa.php\'">Cadastrar nova despesa</button>';

}

?>


</body>
</html>







