<?php
session_start();

if (!isset($_SESSION['id_usuario']) || empty($_SESSION['id_usuario'])) {

    header('Location: ../../index.php'); // Redireciona para a página de login
    exit();
}

$id_usuario = $_SESSION['id_usuario'];


include('../../classes/Despesa.php');
$database = new Conexao();
$db = $database->getConnection();
$despesa = new Despesa($db);


if (isset($_POST['Cadastrar'])){
    $nomeDespesa = $_POST["nome"];
    $categoria = $_POST["categoria"];
    $valor = $_POST["valor"];
    $dataVencimento = $_POST["dataVencimento"];
    $formaPagamento = $_POST["formaPagamento"];
    $imovelAssociado = $_POST["imovelAssociado"];
    $parcela = $_POST["parcelas"];
    $infocomp = $_POST["infoComplementares"];

    if ($despesa->cadastrarDespesa($id_usuario,$nomeDespesa,$categoria,$valor,$dataVencimento,$formaPagamento,$imovelAssociado,$parcela,$infocomp) == TRUE){

        return true;

    }
    else{
        echo "Erro ao cadastrar";
    }
}



$doisAnosAtras = date('Y-m-d', strtotime('-1 year'));
$cemAnosFrente = date('Y-m-d', strtotime('+50 years'));

?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <script src="mascaraMoeda.js"></script>
</head>
<body>

<form method= "POST">
  <label for="nome">Nome da despesa:</label>
  <input type="text" id="nome" name="nome" required>

  <label for="categoria">Categoria:</label>
  <select id="categoria" name="categoria">
    <option disabled >Selecionar</option>
    <option value="Acessorios">Acessórios</option>
    <option value="Alimentacao">Alimentação</option>
    <option value="CuidadosPessoais">Cuidados Pessoais</option>
    <option value="Doacoes-Caridade">Doações/Caridade</option>
    <option value="Educacao">Educação</option>
    <option value="Eletronicos">Eletrônicos</option>
    <option value="Entretenimento">Entretenimento</option>
    <option value="Impostos">Impostos</option>
    <option value="Moradia">Moradia</option>
    <option value="Saude">Saúde</option>
    <option value="Seguros">Seguros</option>
    <option value="ServicoPublico">Servico Público</option>
    <option value="SuperMecado">Super Mercado</option>
    <option value="Viagens">Viagens</option>
    <option value="Vestuario">Vestuário</option>
    <!-- Outras opções de categoria -->
  </select>

  <label for="valor">Valor da despesa:</label>
  <input type="text" class="decimal-input" onInput="mascaraMoeda(event)" name="valor">


  <label for="dataVencimento">Data de vencimento:</label>
  <input type="date" id="dataVencimento" name="dataVencimento" min="<?php echo $doisAnosAtras; ?>" max="<?php echo $cemAnosFrente; ?>" required>

  <label for="formaPagamento">Forma de pagamento:</label>
  <select id="formaPagamento" name="formaPagamento">
  <option disabled >Selecionar</option>
    <option value="Dinheiro">Dinheiro</option>
    <option value="CartaoCredito">Cartão de Crédito</option>
    <option value="CartaoDebito">Cartão de Débito</option>
    <option value="Cheque">Cheque</option>
    <option value="Transferencia">Transferência Bancária</option>
    <option value="Boleto">Boleto Bancário</option>
    <option value="PayPal">PayPal</option>
    <option value="Pix">Pix</option>
  </select>

  <label for="imovelAssociado">Imóvel associado:</label>
  <select id="imovelAssociado" name="imovelAssociado">
   <option disabled >Selecionar</option>
    <option value="Casa">Casa</option>
    <option value="Apartamento">Apartamento</option>
    <option value="Terreno">Terreno</option>
    <option value="SalaComercial">Sala Comercial</option>
    <option value="Loja">Loja</option>
    <option value="Galpao-Armazem">Galpão/Armazém</option>
    <option value="Sitio-Fazenda">Sítio/Fazenda</option>
    <option value="Chacara">Chácara</option>
    <option value="PredioComercial">Prédio Comercial</option>
    <!-- Outras opções de imóvel associado -->
  </select>

  <label for="parcelas">Parcela:</label>
  <select id="parcelas" name="parcelas">
  <option disabled >Selecionar</option>
  <option value='1'>Valor único</option>
  <?php

  $parcela = 1;

  for ($i=0; $i < 119; $i++) {

    $parcela ++;

    echo "<option value='$parcela'>$parcela vezes</option>";
  }

  ?>

    <!-- Outras opções de imóvel associado -->
  </select>
  <label for="infoComplementares">Informações complementares:</label>
  <textarea id="infoComplementares" name="infoComplementares"></textarea>


  <button type="submit" name = "Cadastrar"> Cadastrar Despesa</button>
</form>

<button onclick = "location.href='../Gestao/gestaoDespesa.php'">Gestão de Despesas</button>



</body>
</html>

