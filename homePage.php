<?php

session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: index.php');
    exit;
}


require_once 'conexao/banco.php';

$id = $_SESSION['id'];



$sql = "SELECT * FROM usuario WHERE id = $id";
$resultado = $conn->query($sql);
$dados = $resultado->fetch_assoc();

$nome = $dados['nome'];
$email = $dados['email'];
$saldoEN = $dados['saldo'];

    echo '<script>';
    echo 'var saldo = ' . json_encode($saldoEN) . ';';
    echo '</script>';

    if($saldoEN != NULL){

        $saldo = number_format($saldoEN, 2, ',', '.');
    }
    else{

        $saldo = NULL;

    }

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="style.css">        <script src="script.js"></script>
        <title>Tela Inicial</title>
    </head>
    <body>

<h1>Bem-vindo, <?php echo $_SESSION['email']; ?>!</h1>
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
            <input type="text" id="pegarSaldo" class = "decimal-input" onInput= "mascaraMoeda(event)" name = "SaldoInicial">
        </P>
        <p>Você também poderá optar por não inserir o valor. Se fizer, as informações e cálculos sobre as suas receitas,
            despesas e orçamentos poderão ficar imprecisas.</p>

        <p>Você não verá essa tela novamente.</p>
        <button class="botao-adicionar" name="enviar" onclick="adicionarSaldo()">Adicionar Saldo</button>
        <button class="botao-fechar" name="enviar" onclick="fecharModal()">Fechar Sem Inserir o Valor</button>

    </div>
    </div>





<button type="submit" onclick="location.href='CadastroGestaoDespesa/Estruturado/CadastroDespesa/CadastroDespesa.php'">Cadastro de Despesas</button>
<button type="submit" onclick="location.href='CadastroGestaoDespesa/Estruturado/GestaoDespesa/GestaoDespesa.php'">Gestão de Despesas</button>
<button type="submit" onclick="location.href='CadastroGestaoReceita/CadastroReceita/CadastroReceita.php'">Cadastro de Receitas</button>
<button type="submit" onclick="location.href='CadastroGestaoReceita/GestaoReceita/GestaoReceita.php'">Gestão de Receitas</button>
<button type="submit" onclick="location.href='CadastroGestaoDespesa/Estruturado/CadastroDespesa/CadastroDespesas(frontend).php'">Cadastro de Despesas(Frontend)</button>
<button type="submit" onclick="location.href='CadastroGestaoReceita/CadastroReceita/CadastroReceitas(frontend).php'">Cadastro De Receitas(FrontEnd)</button>
<button type="submit" onclick="location.href='CadastroGestaoOrcamento/CadastroOrcamento/CadastroOrcamento.php'">Cadastrar Orçamentos</button>
<button type="submit" onclick="location.href='CadastroGestaoOrcamento/VerOrcamentos/VerOrcamentos.php'">Ver Orçamentos</button>

</body>
</html>
