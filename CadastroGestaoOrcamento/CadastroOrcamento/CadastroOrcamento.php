<?php
// Criação de orçamentos: O usuário terá a capacidade de criar orçamentos. Será pedido para o usuário preencher o nome do orçamento, a categoria que ele deseja fazer o orçamento, o valor atual e a meta financeira como um valor limite para executar o orçamento que deseja. POSSÍVEL INCREMENTO: O USUÁRIO PODERÁ DEFINIR O VALOR DE DUAS FORMAS, UMA FORMA É DEFINIR O VALOR MANUALMENTE DE ACORDO COM O QUE ELE TEM DISPONÍVEL. OUTRA FORMA É PEGAR O VALOR ATUAL DAS RECEITAS JÁ RECEBIDAS SUBTRAÍDAS COM AS DESPESAS PAGAS. PODERÁ SER ADICIONADA A OPÇÃO DE CATEGORIAS PERSONALIZADAS PARA QUE O USUÁRIO POSSA DEFINIR CATEGORIAS QUE NÃO ESTÃO PADRONIZADAS NO SISTEMA.

// Ver Orçamentos: Poderá ser executado da mesma forma que a gestão de despesas e receitas, uma forma de tabela de acordo com os atributos cadastrados na página de criação de orçamentos, e acessado com as informações através de uma modal box. POSSÍVEL INCREMENTO: FAZER COM QUE O USUÁRIO POSSA ALTERAR O VALOR DA FORMA QUE PUDER, PODENDO (SE QUISER) REGISTRAR A DATA DA ADIÇÃO OU SUBTRAÇÃO. NA TELA DE INFORMAÇÃO DE ORÇAMENTO, PODEMOS ADICIONAR UMA ABA DE PROGRESSO PARA QUE O USUÁRIO ACOMPANHE O PROGRESSO EM FORMA DE PORCENTAGEM, QUE VAI VARIANDO DE ACORDO COM A ADIÇÃO OU REMOÇÃO DE DINHEIRO. SE O USUÁRIO OPTAR POR DIMINUIR O VALOR ATUAL DO ORÇAMENTO, ELE PODERÁ COLOCAR UMA INFORMAÇÃO DE JUSTIFICATIVA PARA O MOTIVO DA REMOÇÃO, SE QUISER. AO CONQUISTAR SUA META, O USUÁRIO PODERÁ EXPORTAR OS DADOS EM ALGUMA FORMA DE PDF OU ALGUM OUTRO DOCUMENTO, ASSIM COMO ALGUMA FORMA DE CONQUISTA, COMO O COMPARTILHAMENTO DA META ADQUIRIDA, BEM COMO (OPICIONAL E NADA IMPORTANTE), CASO HAJA UM SISTEMA PREMIUM DO SITE, LIBERAR O ACESSO À ALGUMAS FUNCIONALIDADES INICIALMENTE PAGAS POR TEMPO LIMITADO.
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: index.php');
    exit;
}

require_once '../../conexao/banco.php';

$doisAnosAtras = date('Y-m-d', strtotime('-1 year'));
$cemAnosFrente = date('Y-m-d', strtotime('+10 years'));
$id = $_SESSION['id'];



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Orçamentos</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
</head>
<body>


<form action= "criarOrcamento.php" method= "POST">
  <label for="titulo">Título do Orçamento:</label>
  <input type="text" id="titulo" name="titulo" required>


    <label for="dataValidade">Período de Validade:</label>
    <input type="date" id="dataValidade" name="validade" min="<?php echo $doisAnosAtras; ?>" max="<?php echo $cemAnosFrente; ?>" required>

  <label for="valorOrcamento">Valor Total do Orçamento</label>
  <input type="text" class="decimal-input" onInput="mascaraMoeda(event)" name="valorOrcamento" required>

    <label for="valorAtual">Valor Já Disponível Para o Orçamento</label>
    <input type="text" class = "decimal-input" onInput = "mascaraMoeda(event)" name = "valorAtual" required>
    <p>Ou Usar Valor do Saldo Atual </p>

    <p><input type="checkbox" name="valorAtual" value = "1.280,99"> R$: 1.280,99</p>


    <label for="prioridade">Prioridade:</label>
  <select id="prioridade" name="prioridade" required>
    <option>Selecionar</option>
    <option value="Baixa">Baixa</option>
    <option value="Media">Média</option>
    <option value="Alta">Alta</option>

  </select>

  <label for="infoComp">Informações complementares:</label>
  <textarea id="infoComp" name="infoComp" ></textarea>


    <button type="submit" name = "Criar"> Criar Orçamento</button>
    <button onclick = "location.href= '../VerOrcamentos/VerOrcamentos.php'"> Ver Orçamentos </button>
</form>




</body>
</html>
