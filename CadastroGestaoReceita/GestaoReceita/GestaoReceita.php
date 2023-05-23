<?php

require_once '../../conexao/banco.php';

$sql = "SELECT * FROM cadrec";
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
    <script src="script.js"></script>
    <title>Document</title>
</head>

<body>

<table class='tabela-receitas'>
    <tr>
        <th>Tipo da Receita</th>
        <th>Tipo de Recebimento</th>
        <th>Valor da Receita</th>
        <th>Recebido</th>
        <th>Data de Recebimento</th>

    </tr>

    <?php
    while ($row = $result->fetch_assoc()) {
        $dataRecebe = date("d/m/Y", strtotime($row["datarecebe"]));
        $recebidoClass = $row["recebido"] ? "Sim" : "Não";

        echo "<tr onclick=\"abrirModal(this)\" data-id=\"" . $row["id"] . "\">
        <td>" . $row["tiporec"] . "</td>
        <td>" . $row["tiporecebe"] . "</td>
        <td>R$ " . $row["valorrec"] . "</td>
        <td>" . $dataRecebe . "</td>
        <td class=\"rec-col\">" . $recebidoClass . "</td>
        </tr>";
    }

    echo "<hr>";

    $sql = "SELECT SUM(valorrec) AS soma_valores, COUNT(*) AS valor_total FROM cadrec";
    $result = $conn->query($sql);

    // Obtém os dados da query como um array associativo
    $dados = $result->fetch_assoc();
    } else {
        echo "Nenhum registro encontrado.";
        echo '<button class="botao-cadastro" onclick="location.href=\'CadastroReceita.php\'">Cadastrar nova receita</button>';
    }

    ?>

</table>

<h2>Valor Total: R$ <?php echo $dados['soma_valores']; ?></h2>
<button class="botao-cadastro" onclick="location.href='CadastroReceita.php'">Cadastrar nova receita</button>

<!-- Modal -->
<div id="myModal" class="modal" data-id="">
    <div class="modal-conteudo">
        <span class="fechar" onclick="fecharModal()">&times;</span>
        <h2 id="modalTitulo"></h2>
        <p>Tipo da Receita: <span id="modalTipoReceita"></span></p>
        <p>Tipo de Recebimento: <span id="modalTipoRecebimento"></span></p>
        <p>Valor da Receita: <span id="modalValorReceita"></span></p>
        <p>Recebido: <span id="modalRecebido"></span></p>
        <p>Data de Recebimento: <span id="modalDataRecebimento"></span></p>
        <button class="botao-receber" name="receber" onclick="receberReceita()">Receber</button>
        <button class="botao-excluir" name="excluir" onclick="excluirReceita()">Excluir</button>
    </div>
</div>

</body>

<script>
    function abrirModal(row) {
    var id = row.getAttribute("data-id");
    var modal = document.getElementById("myModal");
    var dataAtual = new Date().toLocaleDateString('pt-BR');
    document.getElementById("modalDataRecebimento").textContent = dataAtual;
    modal.setAttribute("data-id", id);

    // Acessar os dados da linha clicada
    var tipoReceita = row.cells[0].textContent;
    var tipoRecebimento = row.cells[1].textContent;
    var valorReceita = row.cells[2].textContent;
    var dataRecebimento = row.cells[3].textContent;
    var recebido = row.cells[4].textContent;


    // Exibir os dados no modal
    document.getElementById("modalTitulo").textContent = tipoReceita;
    document.getElementById("modalTipoReceita").textContent = tipoReceita;
    document.getElementById("modalTipoRecebimento").textContent = tipoRecebimento;
    document.getElementById("modalValorReceita").textContent = valorReceita;
    document.getElementById("modalRecebido").textContent = recebido;
    document.getElementById("modalDataRecebimento").textContent = dataRecebimento;


    modal.style.display = "block"; // Exibe o modal
}

function fecharModal() {
    var modal = document.getElementById("myModal");
    modal.style.display = "none"; // Oculta o modal
}


function receberReceita() {

    if (confirm("Tem certeza que deseja marcar essa receita como recebida?")) {
        var recSpan = document.getElementById("modalRecebido");
        var dataPagamentoInput = document.getElementById("modalRecebimento");

        // Verificar se a despesa já está paga
        if (pagoSpan.textContent === "Sim") {
            alert("Essa receita já foi recebida.");
            return; // Encerrar a função sem prosseguir com a marcação de pagamento
        }

        var modal = document.getElementById("myModal");
        var idDespesa = modal.getAttribute("data-id");

        // Definir a data atual como a data de pagamento
        var dataAtual = new Date();
        var dia = String(dataAtual.getDate()).padStart(2, '0');
        var mes = String(dataAtual.getMonth() + 1).padStart(2, '0');
        var ano = dataAtual.getFullYear();
        var dataPagamento = dia + '/' + mes + '/' + ano;

        // Atribuir a data atual ao campo de data de pagamento
        dataPagamentoInput.value = dataPagamento;
        dataPagamentoInput.setAttribute("readonly", true); // Impedir que a data seja alterada

        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'receberDespesa.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // A requisição foi concluída com sucesso
                alert(xhr.responseText);
                // Atualize a tabela ou faça outras ações necessárias
            }
        };

        xhr.send('id=' + idDespesa);

        var row = document.querySelector('tr[data-id="' + idDespesa + '"]');
        if (row.classList.contains('Sim')) {
            despesasJaPagas.push(row);
        } else {
            row.classList.remove("Não");
            row.classList.add("Sim");
        }

        fecharModal();
    }
}



function excluirReceita() {
    if (confirm("Tem certeza que deseja excluir essa receita?")) {
        var modal = document.getElementById("myModal");
        var idReceita = modal.getAttribute("data-id");

        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'excluirReceita.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // A requisição foi concluída com sucesso
                alert(xhr.responseText);
                // Remova a linha da tabela ou faça outras ações necessárias
            }
        };

        xhr.send('id=' + idReceita);

        var row = document.querySelector('tr[data-id="' + idReceita + '"]');
        row.remove();

        fecharModal();
    }
}

</script>

<style>

.tabela-receitas {
    width: 100%;
    border-collapse: collapse;
  }

  .tabela-receitas th,
  .tabela-receitas td {
    padding: 8px;
    text-align: left;
    border-bottom: 1px solid #ddd;
  }

  .tabela-receitas th {
    background-color: #f2f2f2;
  }

  .tabela-receitas tr:hover {
    background-color: #f5f5f5;
  }

  .Sim {
    color: green;
    font-weight: bold;
  }

  .Não {
    color: red;
    font-weight: bold;
  }

</style>

</html>
