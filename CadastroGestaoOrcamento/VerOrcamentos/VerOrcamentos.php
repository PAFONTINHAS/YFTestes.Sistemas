<?php

session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: index.php');
    exit;
}
$id = $_SESSION['id'];

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
            <th>Valor Investido</th>
            <th>Prioridade </th>
            <th>Informações Complementares</th>
        </tr>
        <?php
        while ($row = $result->fetch_assoc()) {

            //convertendo valores do banco de dados
            $validade = date('d/m/Y', strtotime(str_replace('/','-', $row['validade'])));
            $valorOrc = number_format($row['valorOrc'], 2, ',', '.');
            $valorAtual = number_format($row['valorAtual'], 2, ',', '.');


            echo "<tr id='linha' onclick=\"abrirModal(this)\" data-id=\"" . $row['id']. "\">
                <td>" . $row['titulo'] . "</td>
                <td>" . $validade . "</td>
                <td> R$ " .$valorOrc. "</td>
                <td> R$ " . $valorAtual  . "</td>
                <td>" . $row['prioridade']. "</td>
                <td>" . $row['infoComp'] . "</td>
            </tr>";




        }

        echo "</tr>";
        echo"</table>";
        echo"<hr>";

        //buscando o saldo do banco de dados
        $query  = "SELECT saldo FROM usuario WHERE id = $id";
        $resultado = $conn->query($query);
        $consulta = $resultado->fetch_assoc();
        $saldoBanco = $consulta['saldo'];

            $saldo = number_format($saldoBanco, 2, ',', '.');

        echo "<h2>Saldo da sua conta: " . $saldo . "</h2>";


            echo '<button class="botao-cadastro" onclick="location.href=\'../CadastroOrcamento/CadastroOrcamento.php\'">Criar Novo Orçamento</button>';

    ?>


   <!-- Modal para orçamentos -->
<div id="modalVerOrcamento" class="modal" data-id="">
    <div class="modal-conteudo">
        <span class="fechar" onclick="fecharModal()">&times;</span>
        <h2 id="modalTitulo"></h2>
        <p>Validade: <span id="modalValidade"></span></p>
        <p>Valor do Orçamento: R$ <span id="modalValorOrc"></span></p>
        <p>Valor Investido: R$ <span id="modalValorAtual"></span></p>
        <p>Prioridade: <span id="modalPrioridade"></span></p>
        <p>Insira o valor para sacar ou depositar :<input type="text" class = "decimal-input" onInput = "mascaraMoeda(event)" name = "modalValorInserir" id = "modalValorInserir" required>
</p>

        <p>Informações Complementares: <span id="modalInfoComp"></span></p>

        <button class="botao-excluir" name="excluir" onclick="excluirOrcamento()">Excluir</button>
        <button class="botao-sacar" name="sacar" onclick="sacarValor()">Sacar</button>
        <button class="botao-depositar" name="depositar" onclick="depositarValor()">Depositar</button>

    </div>
</div>


<?php }
else {
    echo "Nenhum registro encontrado.";
    echo '<button class="botao-cadastro" onclick="location.href=\'../CadastroOrcamento/CadastroOrcamento.php\'">Criar Novo  Orçamento</button>';

}

?>


</body>



