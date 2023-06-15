<?php

session_start();
if (!isset($_SESSION['id_usuario']) || empty($_SESSION['id_usuario'])) {
    header('Location: login.php'); // Redireciona para a página de login
    exit;
}

include ('conexao/banco.php');
include ('classes/Usuario.php');

$database = new Conexao();
$db = $database->getConnection();

$usuario = new Usuario($db);

$id_usuario = $_SESSION['id_usuario'];


$nome = ""; // variável para armazenar o nome do usuário
$email = ""; // variável para armazenar o e-mail do usuário
$saldo = ""; // variável para armazenar o saldo do usuário


$usuario->obterDados($id_usuario, $nome, $email, $saldo);


    echo '<script>';
    echo 'var saldo = ' . json_encode($saldo) . ';';
    echo 'var id_usuario = ' . json_encode($id_usuario) .';';
    echo '</script>';

    if ($saldo != NULL) {
        $saldo = number_format($saldo, 2, ',', '.');
    } else {
        $saldo = NULL;
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
    <title>Tela Inicial</title>
</head>
<body>
    <h1>Bem-vindo, <?php echo $id_usuario ?>!</h1>
    <p><a href="logout.php">Logout</a></p>
<h1>Bem vindo ao sistema </h1>

<h1>
    Seu nome é: <?php echo $nome; ?>
    <br>
    Seu email é: <?php echo $email; ?>
    <br>
    Seu saldo atual é: R$ <?php echo $saldo; ?>
</h1>

<div id="modalSaldoInicial" class="modal" data-id="">
    <div class="modal-conteudo">
        <span class="fechar" onclick="fecharModal()">&times;</span>
        <h2 id="modalTituloPaga">Saldo Inicial</h2>
        <P>Notamos que é a primeira vez que acessa esse site: Antes de prosseguir insira o saldo inicial da sua conta:
            <input type="text" id="pegarSaldo" class="decimal-input" onInput="mascaraMoeda(event)" name="SaldoInicial">
        </P>
        <p>Você também poderá optar por não inserir o valor. Se fizer, as informações e cálculos sobre as suas receitas, despesas e orçamentos poderão ficar imprecisas.</p>

        <p>Você não verá essa tela novamente.</p>
        <button class="botao-adicionar" name="enviar" onclick="adicionarSaldo()">Adicionar Saldo</button>
        <button class="botao-fechar" name="enviar" onclick="fecharModal()">Fechar Sem Inserir o Valor</button>

    </div>
</div>

<button onclick="location.href= 'CadastroGestao/Cadastro/cadastroDespesa.php'">Cadastro de Despesas</button>
<button onclick="location.href='CadastroGestao/Cadastro/cadastroReceita.php'">Cadastro de Receitas</button>
<button onclick="location.href='CadastroGestao/Cadastro/cadastroOrcamento.php'">Cadastro de Orcamentos</button>
</body>
</html>
