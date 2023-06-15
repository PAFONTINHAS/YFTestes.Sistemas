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
require __DIR__ . "/../../classes/Orcamento.php";


$orcamento = new Orcamento($db);


$dadosTabela = $orcamento->verOrcamento($id_usuario);


if ($dadosTabela->rowCount() > 0) {
$contagem = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="gestaoOrcamento.css">
<title>Gestão de Receitas</title>
<script src="../Cadastro/mascaraMoeda.js"></script>
<script src="obterDadosModal/modalOrcamento.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>
<body>
<table class='tabela-Receitas'>
    <tr>
        <th>Título do Orçamento</th>
        <th>Validade</th>
        <th>Valor Total do Orçamento</th>
        <th>Valor Investido</th>
        <th>Prioridade </th>
        <th>Informações Complementares</th>
    </tr>
    <?php

    while ($row = $dadosTabela->fetch(PDO::FETCH_ASSOC)) {

        $contagem ++;


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



    if($contagem == 1){
        $Cadastrados = " Receita Cadastrada";

    }
    else{
        $Cadastrados = " Receitas Cadastradas";
    }

    $Saldo = $orcamento->pegarSaldo($id_usuario);

    // Atribua os valores calculados a variáveis individuais
    $valorSaldo = $Saldo['Saldo'];

    echo "<h2>Saldo da sua conta: R$ " . $valorSaldo . "</h2>";


    echo '<button class="botao-cadastro" onclick="location.href=\'../Cadastro/cadastroOrcamento.php\'">Cadastrar novo Orçamento</button>';
    echo '<button class="botao-cadastro" onclick="location.href=\'../../PaginaInicial.php\'">Página Inicial</button>';

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
        <p>Insira o valor para sacar ou depositar :<input type="text" class = "decimal-input" onInput = "mascaraMoeda(event)" name = "modalValorInserir" id = "modalValorInserir" required></p>
        <p>Informações Complementares: <span id="modalInfoComp"></span></p>
        <button class="botao-excluir" name="excluir" onclick="excluirOrcamento()">Excluir</button>
        <button class="botao-sacar" name="sacar" onclick="sacarValor()">Sacar</button>
        <button class="botao-depositar" name="depositar" onclick="depositarValor()">Depositar</button>

    </div>
</div>

<?php
}
else {
echo "Nenhum registro encontrado.";
echo '<button class="botao-cadastro" onclick="location.href=\'../Cadastro/cadastroOrcamento.php\'">Cadastrar novo Orçamento</button>';

}

?>


</body>
</html>



