<?php
session_start();

if (!isset($_SESSION['id_usuario']) || empty($_SESSION['id_usuario'])) {

    header('Location: ../../index.php'); // Redireciona para a página de login
    exit();
}

$id_usuario = $_SESSION['id_usuario'];



include('../../classes/Receita.php');
$database = new Conexao();
$db = $database->getConnection();
$receita = new Receita($db);


if(isset($_POST['Cadastrar'])){

    $tipoReceita = $_POST["TipoReceita"];
    $tipoReceber = $_POST["TipoReceber"];
    $valorReceita = $_POST["valorReceita"];
    $dataValidade = $_POST["validade"];
    $repete = $_POST["repete"];
    $infocomp = $_POST["infoComp"];

    if ($receita->cadastrarReceita($id_usuario,$tipoReceita,$tipoReceber,$valorReceita,$dataValidade,$repete,$infocomp) == TRUE){

        return true;

    }
    else{
        echo "Erro ao cadastrar";
    }


}




$umAnoAtras = date( 'Y-m-d', strtotime('-1 year'));
$umAnoFrente = date( 'Y-m-d', strtotime('+1 year'));
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="mascaraMoeda.js"></script>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/autonumeric/4.1.0/autoNumeric.min.js"></script>

</head>
<body>

<form  method= "POST">

<label for="TipoReceita">Tipo da Receita</label>
  <select id="TipoReceita" name="TipoReceita">
    <option> Selecionar</option>
    <option value="Salario">Salário</option>
    <option value="Comissao">Comissão</option>
    <option value="Aluguel">Aluguel</option>
    <option value="Alimentacao">Alimentação</option>
    <option value="Emprestimo">Empréstimo</option>
    <option value="Eventos">Eventos</option>
    <option value="Investimento">Inventimentos</option>
    <option value="Reembolso">Reembolso</option>
    <option value="Doacao">Doação</option>

</select>


  <label for="TipoRecebe">Forma de Recebimento</label>
  <select id="TipoRecebe" name="TipoReceber">
    <option> Selecionar</option>
    <option value="Dinheiro">Dinheiro</option>
    <option value="Cheque">Cheque</option>
    <option value="CartaoCred">Cartão de Crédito</option>
    <option value="CartaoDeb">Cartão de Débito</option>
    <option value="PayPal">PayPal</option>
    <option value="PicPay">PicPay</option>
    <option value="PagSeguro">PagSeguro</option>
    <option value="Pix">Pix</option>
    <!-- Outras opções de categoria -->
  </select>

  <label for="valorRec">Valor da Receita:</label>
  <input type="text" name="valorReceita" class="decimal-input" onInput="mascaraMoeda(event);">

  <label for="dataRecebe">Validade do Recebimento:</label>
  <input type="date" id="validade" name="validade" min="<?php echo $umAnoAtras; ?>" max="<?php echo $umAnoFrente; ?>" required>

  <label for="repete">Tipo de Repetição</label>
  <select id="repete" name="repete">
  <option value='1'>Valor único</option>
  <option value='200'>Receita Contínua</option>

  <?php

  $recebe = 1;

  for ($i=0; $i < 119; $i++) {

    $recebe++;

    echo "<option value='$recebe'>$recebe vezes</option>";
  }

  ?>

    <!-- Outras opções de imóvel associado -->
  </select>
  <label for="infoComplementares">Informações complementares:</label>
  <textarea id="infoComplementares" name="infoComp"></textarea>


  <button type="submit" name = "Cadastrar"> Cadastrar Receita</button>
</form>

<button onclick ="location.href='../Gestao/GestaoReceita.php'" class= "botaodir">Gestão de Receitas</button>



</body>
</html>
