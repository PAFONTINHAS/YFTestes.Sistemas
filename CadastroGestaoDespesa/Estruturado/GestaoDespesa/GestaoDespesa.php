<?php
require_once '../../../conexao/banco.php';

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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <title>Gestão de Despesas</title>
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
        while ($row = $result->fetch_assoc()) {
            $dataVencimento = date("d/m/Y", strtotime($row["vencimento"]));
            $pagoClass = ($row["pago"] == 1) ? "Sim" : "Não";

            if ($row['parcela'] == 0) {
                $row['parcela'] = "valor único";
            } else {
                $row['parcela'] = $row['parcela'] . " vezes";
            }

            if ($row['formapag'] == "cartaoCredito") {
                $row['formapag'] = "Cartão de Crédito";
            } elseif ($row['formapag'] == "tranferencia") {
                $row['formapag'] = "Transferência";
            }

            echo "<tr onclick=\"abrirModal(this)\" class=\"$pagoClass\" data-id=\"" . $row["id"] . "\">
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
            echo '<button class="botao-cadastro" onclick="location.href=\'../CadastroDespesa/CadastroDespesa.php\'">Cadastrar nova despesa</button>';
    
        }
        ?>
    
        </tr>
    </table>
        <a href="../CadastroDespesa/CadastroDespesa.php">Cadastrar Nova Despesa</a>
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
            <p>Data de Pagamento: <input id="modalDataPagamento" class="calendario" type="text" name="dataPagamento" disabled></p>
            <p>Informações Complementares: <span id="modalInformacoesComplementares"></span></p>
            <button class="botao-pagar" name="pagar" onclick="pagarDespesa()">Pagar</button>
            <button class="botao-excluir" name="excluir" onclick="excluirDespesa()">Excluir</button>
        </div>
    </div>

</body>

<script>
    function abrirModal(row) {
        var id = row.getAttribute("data-id");
        var modal = document.getElementById("myModal");
        var dataAtual = new Date().toLocaleDateString('pt-BR');
        document.getElementById("modalDataPagamento").value = dataAtual;
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
        xhr.open('POST', 'excluirDespesa.php', true);
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



function pagarDespesa() {
        if (confirm("Tem certeza que deseja pagar essa despesa?")) {

            
            var pagoSpan = document.getElementById("modalPago");
            var dataPagamentoInput = document.getElementById("modalDataPagamento");

            // Verificar se a despesa já está paga
            if (pagoSpan.textContent === "Sim") {
                alert("Essa despesa já está paga.");
                return; // Encerrar a função sem prosseguir com a marcação de pagamento
            }
            else{
                 var modal = document.getElementById("myModal");
            var idDespesa = modal.getAttribute("data-id");

            var dataPagamentoInput = document.getElementById("modalDataPagamento");
            // Definir a data atual como a data de pagamento
            var dataAtual = new Date();
                var dia = String(dataAtual.getDate()).padStart(2, '0');
                var mes = String(dataAtual.getMonth() + 1).padStart(2, '0');
                var ano = dataAtual.getFullYear();
                var dataPagamento = dia + '/' + mes + '/' + ano;

                // Atribuir a data atual ao campo de data de pagamento
                dataPagamentoInput.value = dataPagamento;

                // Remover o atributo readonly para permitir a edição da data de pagamento
                dataPagamentoInput.removeAttribute("readonly");


            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'pagarDespesa.php', true);
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
           
}

    flatpickr(".calendario", {
        dateFormat: "d/m/Y", // Formato da data
        locale: "pt", // Idioma do calendário
        disableMobile: true // Desabilita o calendário em dispositivos móveis
    });

 </script>
<style>



     /* Estilos CSS da tabela */
   /* Estilos CSS da tabela */
    table {
        border-collapse: collapse;
        width: 100%;
    }

    th,
    td {
        padding: 8px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #f2f2f2;
    }

    tr:hover {
        background-color: blue;
    }

    .selecionada {
        background-color: rgba(0, 0, 255, 0.319);
    }

    .Sim {
        background-color: #7FFF7F; /* Verde para despesas pagas */
    }

    .Não {
        background-color: #FF7F7F; /* Vermelho para despesas não pagas */
    }

    .botao-pagar {
        background-color: #7FFF7F; /* Verde */
        color: white;
        padding: 6px 12px;
        border: none;
        cursor: pointer;
        margin-right: 10px;
    }

    .botao-excluir {
        background-color: #FF7F7F; /* Vermelho */
        color: white;
        padding: 6px 12px;
        border: none;
        cursor: pointer;
        margin-right: 10px;
    }

    .botao-cadastro {
        background-color: #FFFF7F; /* Amarelo */
        color: black;
        padding: 6px 12px;
        border: none;
        cursor: pointer;
        margin-right: 10px;
    }

    .selecionada {
        background-color: blue;
    }

/* Estilos do modal */
.modal {
  display: none;
  position: fixed;
  z-index: 1000;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgba(0, 0, 0, 0.5);
}

.modal-conteudo {
  background-color: #f8f8f8;
  margin: 20% auto;
  padding: 20px;
  border-radius: 10px;
  max-width: 400px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

/* Estilos do botão de fechar */
.close {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: #333;
  text-decoration: none;
  cursor: pointer;
}

/* Estilos para o título do modal */
.modal-title {
  font-size: 18px;
  font-weight: bold;
  margin-bottom: 10px;
  color: #333;
}

/* Estilos para o calendário */
.calendario {
  width: 100%;
  padding: 10px;
  margin-bottom: 10px;
  border-radius: 4px;
  border: 1px solid #ccc;
  box-sizing: border-box;
  font-family: Arial, sans-serif;
  font-size: 14px;
  color: #333;
}

/* Estilos para o botão de pagamento */
.botao-pagar {
  background-color: #4caf50;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-family: Arial, sans-serif;
  font-size: 14px;
}

.botao-pagar:hover {
  background-color: #45a049;
}

.botao-excluir {
  background-color: red;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-family: Arial, sans-serif;
  font-size: 14px;
}

.botao-excluir:hover {
  background-color: red;
}



</style>
</html>



