    <?php
    require_once '../../../conexao/banco.php';
    require 'OrganizarDespesa.php';

    $sql = "SELECT * FROM caddesp";
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
    <title>Gestão de Despesas</title>
    <script src="script.js"></script>
    <!-- Flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>


    </head>
    cxzv
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
        while ($row = $result->fetch_assoc()) {


            $id = $row['id'];
            $contagem ++;

            $parcelaTeste = $row['parcela'];

            $pagoClass = ($row["pago"] == 1) ? "Sim" : "Não";

            $vencimento = $row['vencimento'];

            [$categoria, $pagamento, $parcela, $imovelAssoc, $valorDespFormatado, $vencimentoBR  ] = organizacao($row["categoria"],$row["formapag"],  $row["parcela"], $row["imovelassoc"] , $row["valor"], $row['vencimento']);


            $vencimentoEN = date("Y-m-d", strtotime(str_replace('/','-', $vencimentoBR )));

            $novaParcela = date("Y-m-d", strtotime($vencimentoEN . "-20 days"));

            $parcelaAcima = date("Y-m-d", strtotime($vencimentoEN . "+1 days"));


            // Converter as datas para objetos DateTime
            $dataAtualObj = new DateTime($dataAtual);
            $novaParcelaObj = new DateTime($novaParcela);
            $parcelaAcimaObj = new DateTime($parcelaAcima);

            // Verificar se a data atual está dentro do intervalo
            if ($dataAtualObj >= $novaParcelaObj && $dataAtualObj < $parcelaAcimaObj ) {
                if($parcelaTeste != 0 ){
                    $pagoClass = "Não";
                    $sql = "UPDATE caddesp SET pago = 0 WHERE id = '$id'";
                    $resultQuery = $conn->query($sql);
                }
                else{
                    return;
                }
            }elseif($dataAtualObj != $novaParcelaObj && $dataAtual < $parcelaAcimaObj ){
                if($row['pago'] == 1){
                    $pagoClass = "Sim";
                    $sql2 = "UPDATE caddesp SET pago = 1 WHERE id = '$id'";
                    $resultQuery2 = $conn->query($sql2);
                }
                else{
                    $pagoClass = "Não";
                    $sql2 = "UPDATE caddesp SET pago = 0 WHERE id = '$id'";
                    $resultQuery2 = $conn->query($sql2);
                }

            }
            else{
                return;
            }

            echo "<tr id='linha' onclick=\"abrirModal(this)\" class=\"$pagoClass\" data-id=\"" . $row['id']. "\">
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

        // Cálculo para todas as receitas cadastradas no banco de dados
        $sql = "SELECT SUM(valor) AS soma_valores, COUNT(*) AS valor_total FROM caddesp";
        $result = $conn->query($sql);
        $dados = $result->fetch_assoc();
        $valorTotal = $dados['soma_valores'];

        // Cálculo para todas as despesas que já foram pagas
        $query = "SELECT SUM(valor) AS soma_despesas_pagas FROM caddesp WHERE pago = 1";
        $resultado = $conn->query($query);
        $recebidos = $resultado->fetch_assoc();
        $valorDespesasPagas = $recebidos['soma_despesas_pagas'];

        //Cálculo para todas as despesas finalizadas
        $query2 = "SELECT SUM(valor) AS soma_despesas_final FROM caddesp WHERE parcela = 0";
        $resultado2 = $conn->query($query2);
        $finalizados = $resultado2->fetch_assoc();
        $valorDespesasFinalizadas = $finalizados['soma_despesas_final'];

        // Cálculo do valor a pagar
        $valorReal = $valorTotal - $valorDespesasFinalizadas;
        $valorAPagar = $valorTotal - $valorDespesasPagas;

        //Conversão dos valores para o sistema monetário brasileiro
        $valorAPagarBR = number_format($valorAPagar, 2, ',', '.');
        $valorRealBr = number_format($valorReal, 2, ',', '.');

        if($contagem == 1){
            $Cadastrados = " Despesa Cadastrada";

        }
        else{
            $Cadastrados = " Despesas Cadastradas";
        }

        echo "<h2>Valor de Todas as Despesas: R$ " . $valorRealBr . "</h2>";
        echo "<h2>Valor de Todas as Despesas Pendentes: R$ " . $valorAPagarBR . "</h2>";
        echo "<h2>Número de Registros: " . $contagem . $Cadastrados . "</h2>";




            echo '<button class="botao-cadastro" onclick="location.href=\'../CadastroDespesa/CadastroDespesa.php\'">Cadastrar nova despesa</button>';

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

    <?php }
    else {
    echo "Nenhum registro encontrado.";
    echo '<button class="botao-cadastro" onclick="location.href=\'../CadastroDespesa/CadastroDespesa.php\'">Cadastrar nova despesa</button>';

    }

    ?>


    </body>



