<?php

require_once 'banco.php';

$sql = "SELECT * FROM cadrec";
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
                <th>Tipo da Receita</th>
                <th>Tipo de Recebimento</th>
                <th>Valor da Receita</th>
                <th>Data de Recebimento</th>
            </tr>";

    while ($row = $result->fetch_assoc()) {
        $dataRecebe = date("d/m/Y", strtotime($row["datarecebe"]));


        echo "<tr onclick=\"selecionarLinha(this)\" data-id=\"" . $row["id"] . "\">
        <td>" . $row["tiporec"] . "</td>
        <td>" . $row["tiporecebe"] . "</td>
        <td> R$ " .$row["valorrec"] . "</td>
        <td>" . $dataRecebe . "</td>
        </tr>";



    }
    echo "</table>";

    echo "<hr>";

        $sql = "SELECT SUM(valorrec) AS soma_valores, COUNT(*) AS valor_total FROM cadrec";
        $result = $conn->query($sql);

        // Obtém os dados da query como um array associativo
        $dados = $result->fetch_assoc();


        echo "<h2> Valor Total: R$:". $dados['soma_valores']. "</h2>";

            echo "<button class='botao-excluir' onclick='excluirSelecionados()'>Excluir Despesa</button>";
            echo '<button class="botao-cadastro" onclick="location.href=\'CadastroReceita.php\'">Cadastrar nova receita</button>';
        } else {
    echo "Nenhum registro encontrado.";
    echo '<button class="botao-cadastro" onclick="location.href=\'CadastroReceita.php\'">Cadastrar nova receita</button>';

}


?>




