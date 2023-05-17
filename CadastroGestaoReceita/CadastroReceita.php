<?php

$umAnoAtras = date('d/m/Y', strtotime('-1 year'));
$umAnoFrente = date( 'd/m/Y', strtotime('+1 year'));
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

<label for="TipoReceita">Tipo da Receita</label>
  <select id="TipoReceita" name="TipoReceita">
    <option value="salario">Salário</option>
    <option value="comissao">Comissão</option>
    <option value="saldoini">Saldo Inicial</option>
</select>


  <label for="TipoRecebe">Tipo de Recebimento</label>
  <select id="TipoRecebe" name="TipoRecebe">
    <option value="Dinheiro">Dinheiro</option>
    <option value="Cheque">Cheque</option>
    <option value="CartaoCred">Cartão de Crédito</option>
    <option value="CartaoDeb">Cartão de Débito</option>

    <!-- Outras opções de categoria -->
  </select>

  <label for="valorRec">Valor da Receita:</label>
  <input type="text"  name="valorRec">

  <label for="dataRecebe">Data de recebimento:</label>
  <input type="date" id="dataRecebe" name="dataRecebe" min="<?php echo $umAnoAtras; ?>" max="<?php echo $umAnoFrente; ?>" required>

  <label for="repete">Tipo de Repetição</label>
  <select id="repete" name="repete">
  <option value='0'>Valor único</option>
  <?php

  $recebe = 1;

  for ($i=0; $i < 47; $i++) {

    $recebe++;

    echo "<option value='$recebe'>$recebe</option>";
  }

  ?>

    <!-- Outras opções de imóvel associado -->
  </select>
  <label for="infoComplementares">Informações complementares:</label>
  <textarea id="infoComplementares" name="infoComplementares"></textarea>


  <button type="submit" name = "Cadastrar"> Cadastrar Receita</button>
</form>

<form action="GestaoReceita.php"><button type="submit">Gestão de Receitas</button></form>



</body>
</html>
