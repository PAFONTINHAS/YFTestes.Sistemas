<?php

$doisAnosAtras = date('Y-m-d', strtotime('-2 years'));
$cemAnosFrente = date('Y-m-d', strtotime('+100 years'));
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="script.js"></script>

    <style>
  /* Estilos para o formulário */
  form {
    display: flex;
    flex-direction: column;
    gap: 10px;
    max-width: 400px;
    margin: 0 auto;
  }

  /* Estilos para os rótulos */
  label {
    font-weight: bold;
  }

  /* Estilos para as caixas de seleção */
  select {
    padding: 5px;
    border-radius: 4px;
    border: 1px solid #ccc;
  }

  /* Estilos para os campos de entrada de texto */
  input[type="text"],
  input[type="number"],
  input[type="date"] {
    padding: 5px;
    border-radius: 4px;
    border: 1px solid #ccc;
  }

  /* Estilos para o botão de enviar */
  input[type="submit"] {
    padding: 10px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
  }

  /* Estilos para o botão de enviar quando o mouse estiver sobre ele */
  input[type="submit"]:hover {
    background-color: #45a049;
  }
  #infoComplementares {
    width: 100%;
    height: 100px;
    border-radius:10px;

  }
</style>

</head>
<body>

<form action= "processa.php" method= "POST">
  <label for="nome">Nome da despesa:</label>
  <input type="text" id="nome" name="nome" required>

  <label for="categoria">Categoria:</label>
  <select id="categoria" name="categoria">
    <option value="alimentacao">Alimentação</option>
    <option value="moradia">Moradia</option>
    <option value="transporte">Transporte</option>
    <!-- Outras opções de categoria -->
  </select>

  <label for="valor">Valor da despesa:</label>
  <input type="text" onKeyUp="mascaraMoeda(this, event)" name="valor">

  <label for="dataVencimento">Data de vencimento:</label>
  <input type="date" id="dataVencimento" name="dataVencimento" min="<?php echo $doisAnosAtras; ?>" max="<?php echo $cemAnosFrente; ?>" required>

  <label for="formaPagamento">Forma de pagamento:</label>
  <select id="formaPagamento" name="formaPagamento">
    <option value="cartaoCredito">Cartão de Crédito</option>
    <option value="Dinheiro">Dinheiro</option>
    <option value="transferencia">Transferência Bancária</option>
  </select>

  <label for="imovelAssociado">Imóvel associado:</label>
  <select id="imovelAssociado" name="imovelAssociado">
    <option value="casa">Casa</option>
    <option value="apartamento">Apartamento</option>
    <option value="terreno">Terreno</option>
    <!-- Outras opções de imóvel associado -->
  </select>

  <label for="parcelas">Parcela:</label>
  <select id="parcelas" name="parcelas">
  <option value='0'>Valor único</option>
  <?php

  $parcela = 1;

  for ($i=0; $i < 47; $i++) {

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

<form action="GestaoDespesa.php"><button type="submit">Gestão de Despesas</button></form>



</body>
</html>
