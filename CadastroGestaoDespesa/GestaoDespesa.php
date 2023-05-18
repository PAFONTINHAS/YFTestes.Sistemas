<?php

require_once 'banco.php';

$sql = "SELECT * FROM caddesp";
$result = $conn->query($sql);
?>


<style>
    /* Estilos CSS da tabela */
    table {
        border-collapse: collapse;
        width: 100%;
    }

    th, td {
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

    .sim {
        background-color: #7FFF7F; /* Verde para despesas pagas */
    }

    .não {
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

</style>

<script>
var idsSelecionados = []; // Variável global para armazenar os IDs das linhas selecionadas

function selecionarLinha(linha) {
    linha.classList.toggle('selecionada');
    var id = linha.dataset.id;

    // Atualiza a lista de IDs selecionados
    if (linha.classList.contains('selecionada')) {
        idsSelecionados.push(id);
    } else {
        var index = idsSelecionados.indexOf(id);
        if (index !== -1) {
            idsSelecionados.splice(index, 1);
        }
    }

    console.log("IDs selecionados:", idsSelecionados);
}


document.addEventListener('click', function(event) {
    // Verifica se o clique ocorreu fora da tabela
    if (!event.target.closest('table')) {
        // Remove a classe "selecionada" de todas as linhas
        var linhasSelecionadas = document.querySelectorAll('.selecionada');
        linhasSelecionadas.forEach(function(linha) {
            linha.classList.remove('selecionada');
            idsSelecionados = [];
        });
    }
    console.log("Evento de clique em uma área em branco acionado!");
    console.log(idsSelecionados);


});


function pagarDespesa() {
    var linhasSelecionadas = document.querySelectorAll('.selecionada');

    var despesasJaPagas = [];

    if (linhasSelecionadas.length === 0) {
        alert("Selecione pelo menos uma despesa para pagar.");
        return;
    }

    linhasSelecionadas.forEach(function(linha) {
            if (linha.classList.contains('Sim')) {
                despesasJaPagas.push(linha);
            } else {
                // Marcar a despesa como paga
                linha.classList.remove('Não');
                linha.classList.add('Sim');
                linha.querySelector('.pago-col').textContent = 'Sim';
            }
        });

        if (despesasJaPagas.length > 0) {
            var mensagem = "As despesas marcadas como pagas não serão alteradas:\n";
            despesasJaPagas.forEach(function(linha) {
            });
            alert(mensagem);
        }

    else if (confirm("Tem certeza que deseja marcar as despesas selecionadas como pagas?")) {



        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'pagar.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // A requisição foi concluída com sucesso
                alert(xhr.responseText);
                // Atualize a tabela ou faça outras ações necessárias
            }
        };

        xhr.send('ids=' + idsSelecionados.join(','));



    }
}



function excluirSelecionados() {
    if (idsSelecionados.length === 0) {
        alert("Selecione pelo menos uma linha para excluir.");
        return;
    }

    if (confirm("Tem certeza que deseja excluir as despesas selecionadas?")) {
        // Realize a requisição AJAX para excluir os registros no banco de dados
        // Use a variável idsSelecionados para enviar os IDs para o arquivo PHP
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'excluir.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // A requisição foi concluída com sucesso
                alert(xhr.responseText);
                // Atualize a tabela ou faça outras ações necessárias
            }
        };

        xhr.send('ids=' + idsSelecionados.join(','));

        var linhasSelecionadas = document.querySelectorAll('.selecionada');
        linhasSelecionadas.forEach(function(linha) {
            linha.remove();


        });

        // Limpa a lista de IDs selecionados
        idsSelecionados = [];


    }

}


</script>
<?php



if ($result->num_rows > 0) {
    echo "<table class='tabela-despesas'>
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
            </tr>";

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

        echo "<tr onclick=\"selecionarLinha(this)\" class=\"$pagoClass\" data-id=\"" . $row["id"] . "\">
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
    echo "</table>";

    echo "<hr>";

        $sql = "SELECT SUM(valor) AS soma_valores, COUNT(*) AS valor_total FROM caddesp";
        $result = $conn->query($sql);

        // Obtém os dados da query como um array associativo
        $dados = $result->fetch_assoc();


        echo "<h2> Valor Total: R$:". $dados['soma_valores']. "</h2>";

    echo "<button class='botao-pagar' onclick='pagarDespesa()'>Pagar Despesa</button>";
            echo "<button class='botao-excluir' onclick='excluirSelecionados()'>Excluir Despesa</button>";
            echo '<button class="botao-cadastro" onclick="location.href=\'CadastroDespesa.php\'">Cadastrar nova despesa</button>';
        } else {
    echo "Nenhum registro encontrado.";
    echo '<button class="botao-cadastro" onclick="location.href=\'CadastroDespesa.php\'">Cadastrar nova despesa</button>';

}


?>




