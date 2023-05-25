<?php

require_once '../../conexao/banco.php';

function organizacao($categoria, $recebimento, $repete, $valorRec){

    // Alterações da Repetição

    if($repete == 0 ){
        $repete = "valor único";
    }
    else{
        $repete .= " vezes";
    }
    // Alterações da categoria

    if($categoria == "Salario"){
        $categoria = "Salário";
    }
    elseif($categoria == "Comissao"){
        $categoria = "Comissão";
    }
    elseif($categoria == "SaldoIni"){
        $categoria = "Saldo Inicial";
    }
    elseif($categoria == "Alimentacao"){
        $categoria = "Alimentação";
    }
    elseif($categoria == "Doacao"){
        $categoria = "Doação";
    }
    elseif($categoria == "Emprestimo"){
        $categoria = "Empréstimo";
    }

    // Alterações do Recebimento

    if ($recebimento == "CartaoCred"){
        $recebimento = "Cartão de Crédito";
    }
    elseif($recebimento == "CartaoDeb"){
        $recebimento = "Cartão de Débito";
    }

    $valorRecFormatado = number_format($valorRec, 2, ',', '.');


    return [$categoria, $recebimento, $repete, $valorRecFormatado];



}


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
        <th>Repetições</th>
        <th>Validade do Recebimento</th>
        <th>Recebido</th>
        <th>Informações Complementares</th>


    </tr>

    <?php
    while ($row = $result->fetch_assoc()) {
        $dataRecebe = date("d/m/Y", strtotime($row["datarecebe"]));
        $recebidoClass = $row["recebido"] ? "Sim" : "Não";

        [$tipoRec, $tipoRecebe, $repete, $valorRecFormatado] = organizacao($row["tiporec"], $row["tiporecebe"], $row["repete"], $row["valorrec"]);


        echo "<tr id = 'linha' onclick=\"abrirModal(this)\" class=\"$recebidoClass\" data-id=\"" . $row["id"] . "\">
        <td>" . $tipoRec . "</td>
        <td>" . $tipoRecebe . "</td>
        <td>R$ " . $valorRecFormatado . "</td>
        <td>" . $repete . "</td>
        <td>" . $dataRecebe . "</td>
        <td class=\"rec-col\">" . $recebidoClass . "</td>
        <td>" . $row["infocomp"] . "</td>
        </tr>";
    }





    // Obtém os dados da query como um array associativo
    $dados = $result->fetch_assoc();
    } else {
        echo "Nenhum registro encontrado.";
        echo '<button class="botao-cadastro" onclick="location.href=\'../CadastroReceita/CadastroReceita.php\'">Cadastrar nova receita</button>';
    }

    ?>

</table>
<hr>

<?php

        $sql = "SELECT SUM(valorrec) AS soma_valores, COUNT(*) AS valor_total FROM cadrec";
        $result = $conn->query($sql);

        // Obtém os dados da query como um array associativo
        $dados = $result->fetch_assoc();
?>

    <h2>Valor Total: R$: <?php echo $dados['soma_valores']?> </h2>


<button class="botao-cadastro" onclick="location.href='../CadastroReceita/CadastroReceita.php'">Cadastrar nova receita</button>

<!-- Modal -->
<div id="myModal2" class="modal" data-id="">
    <div class="modal-conteudo">
        <span class="fechar" onclick="fecharModal()">&times;</span>
        <h2 id="modalTitulo"></h2>
        <p>Tipo da Receita: <span id="modalTipoReceita"></span></p>
        <p>Tipo de Recebimento: <span id="modalTipoRecebimento"></span></p>
        <p>Valor da Receita: <span id="modalValorReceita"></span></p>
        <p>Validade do Recebimento: <span id="modalValidadeRecebimento"></span></p>
        <p>Repetições: <span id="modalRepete"></span></p>
        <p>Recebido: <span id="modalRecebido"></span></p>
        <p>Data do Recebimento: <input id="modalDataRecebimento" class="calendario2" type="text" name="dataRecebimento" disabled></p>
        <p>Informações Complementares: <span id= "modalInfoComp"></span></p>
        <button class="botao-receber" name="receber" onclick="receberReceita()">Receber</button>
        <button class="botao-excluir" name="excluir" onclick="excluirReceita()">Excluir</button>
    </div>
</div>

</body>

<script>
    function abrirModal(row) {
    var id = row.getAttribute("data-id");
    var modal = document.getElementById("myModal2");
    var dataAtual = new Date().toLocaleDateString('pt-BR');
    document.getElementById("modalDataRecebimento").value = dataAtual;
    modal.setAttribute("data-id", id);

    // Acessar os dados da linha clicada
        var tipoReceita = row.cells[0].textContent; // Índice 0 corresponde à primeira célula na linha
        var tipoRecebimento = row.cells[1].textContent; // Índice 1 corresponde à segunda célula na linha
        var valorReceita = row.cells[2].textContent; // Índice 2 corresponde à terceira célula na linha
        var repete = row.cells[3].textContent; // Índice 4 corresponde à quinta célula na linha
        var validadeRecebimento = row.cells[4].textContent; // Índice 3 corresponde à quarta célula na linha
        var recebido = row.cells[5].textContent; // Índice 5 corresponde à sexta célula na linha
        var infoComp = row.cells[6] ? row.cells[6].textContent: "";
        var dataRecebimento = row.cells[7] ? row.cells[7].textContent : "";



    // Exibir os dados no modal
        document.getElementById("modalTitulo").textContent = tipoReceita;
        document.getElementById("modalTipoReceita").textContent = tipoReceita;
        document.getElementById("modalTipoRecebimento").textContent = tipoRecebimento;
        document.getElementById("modalValorReceita").textContent = valorReceita;
        document.getElementById("modalValidadeRecebimento").textContent = validadeRecebimento;
        document.getElementById("modalRecebido").textContent = recebido;
        document.getElementById("modalRepete").textContent = repete;
        document.getElementById("modalDataRecebimento").textContent = dataRecebimento;
        document.getElementById("modalInfoComp").textContent = infoComp;


    modal.style.display = "block"; // Exibe o modal

        console.log(tipoReceita);
        console.log(tipoRecebimento);
        console.log(valorReceita);
        console.log(validadeRecebimento);
        console.log(recebido);
        console.log(repete);
        console.log(dataRecebimento);
        console.log(infoComp);
}

function fecharModal() {
    var modal = document.getElementById("myModal2");
    modal.style.display = "none"; // Oculta o modal
}

flatpickr(".calendario2", {
        dateFormat: "d/m/Y", // Formato da data
        locale: "pt", // Idioma do calendário
        disableMobile: true // Desabilita o calendário em dispositivos móveis
    });

function receberReceita() {
    if (confirm("Tem certeza que deseja pagar essa despesa?")) {
        var recSpan = document.getElementById("modalRecebido");
        var dataRecebimentoInput = document.getElementById("modalDataRecebimento");

        // Verificar se a despesa já está paga
        if (recSpan.textContent === "Sim") {
            alert("Essa receita já foi recebida.");
            return; // Encerrar a função sem prosseguir com a marcação de pagamento
        }

        var modal = document.getElementById("myModal2");
        var idReceita = modal.getAttribute("data-id");

        // Definir a data atual como a data de pagamento
        var dataAtual = new Date();
        var dia = String(dataAtual.getDate()).padStart(2, '0');
        var mes = String(dataAtual.getMonth() + 1).padStart(2, '0');
        var ano = dataAtual.getFullYear();
        var dataRecebimento = dia + '/' + mes + '/' + ano;

        // Atribuir a data atual ao campo de data de pagamento
        dataRecebimentoInput.value = dataRecebimento;
        dataRecebimentoInput.setAttribute("readonly", true); // Impedir que a data seja alterada

        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'receberReceita.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // A requisição foi concluída com sucesso
                alert(xhr.responseText);
                // Atualize a tabela ou faça outras ações necessárias
            }
        };

        xhr.send('id=' + idReceita);

        var row = document.querySelector('tr[data-id="' + idReceita + '"]');
        if (row.classList.contains('Sim')) {
            receitaJaRecebida.push(row);
        } else {
            row.classList.remove("Não");
            row.classList.add("Sim");
        }

        fecharModal();
    }
}



function excluirReceita() {
    if (confirm("Tem certeza que deseja excluir essa receita?")) {
        var modal = document.getElementById("myModal2");
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
    color: black;
}

th {
    background-color: #f2f2f2;
}

#linha:hover {
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

.botao-receber {
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
.fechar {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
  }

  .fechar:hover,
  .fechar:focus {
    color: #000;
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
.botao-receber {
background-color: #4caf50;
color: white;
padding: 10px 20px;
border: none;
border-radius: 4px;
cursor: pointer;
font-family: Arial, sans-serif;
font-size: 14px;
}

.botao-receber:hover {
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
