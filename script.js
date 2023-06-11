window.onload= function(){
    if(saldo  == null){
        abrirModal()
    }
}

function abrirModal() {

    var modalSaldoInicial = document.getElementById("modalSaldoInicial");
    modalSaldoInicial.style.display = "block";

}

function fecharModal() {
    var modalSaldoInicial = document.getElementById("modalSaldoInicial");
    var pegarSaldo = document.getElementById("pegarSaldo").value;

    if(saldo == null){
      var xhr = new XMLHttpRequest();
      xhr.open('POST', 'manipularSaldo.php', true);
      xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
      xhr.onreadystatechange = function() {
          if (xhr.readyState === 4 && xhr.status === 200) {
              // A requisição foi concluída com sucesso
              alert(xhr.responseText);
              // Atualize a tabela ou faça outras ações necessárias
          }
      };

      // Enviar os dados para pagarDespesa.php
      var params = 'SaldoInicial=' + pegarSaldo;
      xhr.send(params);
    }


    modalSaldoInicial.style.display = "none"; // Ocultar o modal de orcamentos

}

function adicionarSaldo(){
    var pegarSaldo = document.getElementById("pegarSaldo").value;

    // Executar a lógica de pagamento da despesa
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'manipularSaldo.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // A requisição foi concluída com sucesso
            alert(xhr.responseText);
            // Atualize a tabela ou faça outras ações necessárias
        }
    };

    // Enviar os dados para pagarDespesa.php
    var params = 'SaldoInicial=' + pegarSaldo;
    xhr.send(params);



    fecharModal();
}



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


