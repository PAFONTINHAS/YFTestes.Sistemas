<?php
require_once 'banco.php';

$sql = "SELECT * FROM caddesp";
$result = $conn->query($sql);

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


    while ($row = $result->fetch_assoc()) {
    $dataVencimento = date("d/m/Y", strtotime($row["vencimento"]));
    $pagoClass = ($row["pago"] == 1) ? "Sim" : "Não";


    if($row['parcela'] == 0){
        $row['parcela'] = "valor único";
    }
    else{

        $row['parcela'] = $row['parcela']." vezes";
    }


    if($row['formapag'] == "cartaoCredito")
    {
        $row['formapag'] = "Cartão de Crédito";
    }
    elseif($row['formapag'] == "tranferencia"){
        $row['formapag'] = "Tranferência";
    }

    echo "<tr onclick=\"abrirModal(this)\" class=\"$pagoClass\" data-id=\"". $row["id"] . "\">


        <td>" . $row["nome"] . "</td>
            <td>" . $row["categoria"] . "</td>
            <td> R$ " . $row["valor"] . "</td>
            <td>" . $row["parcela"] . "</td>
            <td>" . $row["formapag"] . "</td>
            <td>" . $row["imovelassoc"] . "</td>
            <td>" . $dataVencimento . "</td>
            <td class=\"pago-col\">" . $pagoClass . "</td>
            <td>" . $row["infocomp"] . "</td>
        </tr>";


        }
    }
    else {
        echo "Nenhum registro encontrado.";
        echo '<button class="botao-cadastro" onclick="location.href=\'CadastroDespesa.php\'">Cadastrar nova despesa</button>';

    }

?>
    </tr>
    </table>

    <div id="myModal" class="modal" data-id="">
    <div class="modal-conteudo">
        <span class="fechar" onclick="fecharModal()">&times;</span>
        <h2 id="modalTitulo"></h2>
        <p>Categoria: <span id="modalCategoria"></span></p>
        <p>Valor: <span id="modalValor"></span></p>
        <p>Parcela: <span id="modalParcela"></span></p>
        <p>Forma de Pagamento: <span id="modalFormaPagamento"></span></p>
        <p>Imóvel Associado: <span id="modalImovelAssociado"></span></p>
        <p>Data de Vencimento: <span id="modalDataVencimento"></span></p>
        <p>Pago: <span id="modalPago"></span></p>
        <p>Informações Complementares: <span id="modalInformacoesComplementares"></span></p>
        <button name="pagar" onclick="pagarDespesa()">Pagar</button>
        <button name= "excluir" onclick="excluirDespesa()">Excluir</button>
    </div>
</div>

</body>

<script>
    function abrirModal(row) {
    var id = row.getAttribute("data-id");
    var modal = document.getElementById("myModal");
    modal.setAttribute("data-id", id);


     // Acessar os dados da linha clicada
     var nomeDespesa = row.cells[0].textContent;
    var categoria = row.cells[1].textContent;
    var valor = row.cells[2].textContent;
    var parcela = row.cells[3].textContent;
    var formaPagamento = row.cells[4].textContent;
    var imovelAssociado = row.cells[5].textContent;
    var dataVencimento = row.cells[6].textContent;
    var pago = row.cells[7].textContent;
    var informacoesComplementares = row.cells[8].textContent;

    // Exibir os dados no modal
    document.getElementById("modalTitulo").textContent = nomeDespesa;
    document.getElementById("modalCategoria").textContent = categoria;
    document.getElementById("modalValor").textContent = valor;
    document.getElementById("modalParcela").textContent = parcela;
    document.getElementById("modalFormaPagamento").textContent = formaPagamento;
    document.getElementById("modalImovelAssociado").textContent = imovelAssociado;
    document.getElementById("modalDataVencimento").textContent = dataVencimento;
    document.getElementById("modalPago").textContent = pago;
    document.getElementById("modalInformacoesComplementares").textContent = informacoesComplementares;

    modal.style.display = "block"; // Exibe o modal
}

function fecharModal() {
    var modal = document.getElementById("myModal");
    modal.style.display = "none"; // Oculta o modal
}




function excluirDespesa() {
    if (confirm("Tem certeza que deseja excluir essa despesa?")) {
        var modal = document.getElementById("myModal");
        var idDespesa = modal.getAttribute("data-id");

        // Realize a requisição AJAX para excluir o registro no banco de dados
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'excluir.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // A requisição foi concluída com sucesso
                alert(xhr.responseText);
                // Atualize a tabela ou faça outras ações necessárias
                // Por exemplo, recarregue a página ou atualize a lista de despesas
                var linha = document.querySelector('.selecionada[data-id="' + idDespesa + '"]');
            if (linha) {
                linha.remove();
            }
            }
        };
        xhr.send('id=' + idDespesa);
        fecharModal();
    }
}





 </script>

</html>




