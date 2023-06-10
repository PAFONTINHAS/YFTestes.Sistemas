<?php
require_once '../../conexao/banco.php';

$sql = "SELECT * FROM cadorc";
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
    <title>Ver Orçamentos</title>
    <script src="script.js"></script>
    <!-- Flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>


</head>

<body>
    <table class='tabela-Orcamentos'>
        <tr>
            <th>Título do Orçamento</th>
            <th>Validade</th>
            <th>Valor Total do Orçamento</th>
            <th>Valor Guardado Para o Orçamento</th>
            <th>Prioridade do Orçamento</th>
            <th>Informações Complementares</th>
        </tr>
        <?php
        while ($row = $result->fetch_assoc()) {

            echo "<tr id='linha' onclick=\"abrirModal(this)\" data-id=\"" . $row['id']. "\">
                <td>" . $row['titulo'] . "</td>
                <td>" . $row['validade'] . "</td>
                <td> R$ " .$row['valorOrc']. "</td>
                <td>" . $row['valorAtual'] . "</td>
                <td>" . $row['prioridade']. "</td>
                <td>" . $row['infoComp'] . "</td>
            </tr>";




        }

        echo "</tr>";
        echo"</table>";
        echo"<hr>";


            echo '<button class="botao-cadastro" onclick="location.href=\'../CadastroOrcamento/CadastroOrcamento.php\'">Criar Novo Orçamento</button>';

    ?>


   <!-- Modal para orçamentos -->
<div id="modalVerOrcamento" class="modal" data-id="">
    <div class="modal-conteudo">
        <span class="fechar" onclick="fecharModal()">&times;</span>
        <h2 id="modalTitulo"></h2>
        <p>Validade: <span id="modalValidade"></span></p>
        <p>Valor do Orçamento: <span id="modalValorOrc"></span></p>
        <p>Valor Já Guardado para o Orçamento: <span id="modalValorAtual"></span></p>
        <p>Prioridade: <span id="modalPrioridade"></span></p>
        <p>Informações Complementares: <span id="modalInfoComp"></span></p>

        <button class="botao-excluir" name="excluir" onclick="excluirOrcamento()">Excluir</button>
    </div>
</div>


<?php }
else {
    echo "Nenhum registro encontrado.";
    echo '<button class="botao-cadastro" onclick="location.href=\'../CadastroOrcamento/CadastroOrcamento.php\'">Criar Novo  Orçamento</button>';

}

?>


</body>



