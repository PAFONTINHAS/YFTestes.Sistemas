<?php

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
    <script src="script.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/autonumeric/4.1.0/autoNumeric.min.js"></script>



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
  .botaodir{
    position:absolute;
    top:83%;
    left:36%;
    width:29%;
  }
</style>

</head>
<body>

<form action= "cadastrar.php" method= "POST">

<label for="TipoReceita">Tipo da Receita</label>
  <select id="TipoReceita" name="TipoReceita">
    <option> Selecionar</option>
    <option value="Salario">Salário</option>
    <option value="Comissao">Comissão</option>
    <option value="Saldo Inicial">Saldo Inicial</option>
    <option value="Aluguel">Aluguel</option>
    <option value="Investimento">Inventimentos</option>
    <option value="Alimentacao">Alimentação</option>
    <option value="Eventos">Eventos</option>
    <option value="Reembolso">Reembolso</option>
    <option value="Doacao">Doação</option>
    <option value="Emprestimo">Empréstimo</option>

</select>


  <label for="TipoRecebe">Forma de Recebimento</label>
  <select id="TipoRecebe" name="TipoRecebe">
    <option> Selecionar</option>
    <option value="Dinheiro">Dinheiro</option>
    <option value="Cheque">Cheque</option>
    <option value="CartaoCred">Cartão de Crédito</option>
    <option value="CartaoDeb">Cartão de Débito</option>
    <option value="Pix">Pix</option>
    <option value="PayPal">PayPal</option>
    <option value="PicPay">PicPay</option>
    <option value="PagSeguro">PagSeguro</option>
    <!-- Outras opções de categoria -->
  </select>

  <label for="valorRec">Valor da Receita:</label>
  <input type="text" name="valorRec" class="decimal-input" onInput="mascaraMoeda(event);">

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
  <textarea id="infoComplementares" name="infoComplementares"></textarea>


  <button type="submit" name = "Cadastrar"> Cadastrar Receita</button>
</form>

<button onclick ="location.href='../GestaoReceita/GestaoReceita.php'" class= "botaodir">Gestão de Receitas</button>



</body>
<script>

$(document).ready(function() {
    $('.decimal-input').autoNumeric('init', {
      decimalCharacter: ',',
      digitGroupSeparator: '.',
      decimalPlaces: 2,
      currencySymbol: '',
      unformatOnSubmit: true
    });
  });

  function mascaraMoeda(event) {
    const onlyDigits = event.target.value
      .split("")
      .filter(s => /\d/.test(s))
      .join("")
      .padStart(3, "0")
    const digitsFloat = onlyDigits.slice(0, -2) + "." + onlyDigits.slice(-2)
    event.target.value = maskCurrency(digitsFloat)
  }

  function maskCurrency(valor, locale = 'pt-BR', currency = 'BRL') {
    return new Intl.NumberFormat(locale, {
      style: 'currency',
      currency
    }).format(valor)
  }

</script>
</html>
