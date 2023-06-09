
function abrirModal(row) {
    var id = row.getAttribute("data-id");
    var modalDespesaPaga = document.getElementById("modalDespesaPaga");
    var modalDespesaNaoPaga = document.getElementById("modalDespesaNaoPaga");

    // Define o ID da despesa no atributo data-id do modal
    modalDespesaPaga.setAttribute("data-id", id);
    modalDespesaNaoPaga.setAttribute("data-id", id);

    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            // Processar a resposta JSON e obter os dados da despesa
            var response = JSON.parse(this.responseText);
            var despesa = response.despesa;
            var dataPagamento = response.dataPagamento;
            var novaParcela = response.novaParcela;

            if (despesa.pago === "Sim") {
                
                // Preencher os dados no modal de despesas pagas
                document.getElementById("modalTituloPaga").textContent = despesa.nomeDespesa;
                document.getElementById("modalCategoriaPaga").textContent = despesa.categoria;
                document.getElementById("modalValorPaga").textContent = despesa.valor;
                document.getElementById("modalParcelaPaga").textContent = despesa.parcela;
                document.getElementById("modalFormaPagamentoPaga").textContent = despesa.formaPagamento;
                document.getElementById("modalImovelAssociadoPaga").textContent = despesa.imovelAssociado;
                document.getElementById("modalDataVencimentoPaga").textContent = despesa.dataVencimento;
                document.getElementById("modalPagoPaga").textContent = despesa.pago;
                document.getElementById("modalDataPagamentoPaga").textContent = dataPagamento;
                document.getElementById("modalNovaParcela").textContent = novaParcela;
                document.getElementById("modalInformacoesComplementaresPaga").textContent = despesa.infoComp;

                // Exibir o modal de despesas pagas
                modalDespesaPaga.style.display = "block";
            } else {
                // Preencher os dados no modal de despesas não pagas
                document.getElementById("modalTituloNaoPaga").textContent = despesa.nomeDespesa;
                document.getElementById("modalCategoriaNaoPaga").textContent = despesa.categoria;
                document.getElementById("modalValorNaoPaga").textContent = despesa.valor;
                document.getElementById("modalParcelaNaoPaga").textContent = despesa.parcela;
                document.getElementById("modalFormaPagamentoNaoPaga").textContent = despesa.formaPagamento;
                document.getElementById("modalImovelAssociadoNaoPaga").textContent = despesa.imovelAssociado;
                document.getElementById("modalDataVencimentoNaoPaga").textContent = despesa.dataVencimento;
                document.getElementById("modalPagoNaoPaga").textContent = despesa.pago;
                document.getElementById("modalDataPagamentoNaoPaga").value = ""; // Limpar o campo de data de pagamento
                document.getElementById("modalInformacoesComplementaresNaoPaga").textContent = despesa.infoComp;

                // Exibir o modal de despesas não pagas
                modalDespesaNaoPaga.style.display = "block";
            }
        }
    };

    xhr.open("GET", "obterDadosDespesa.php?id=" + id, true);
    xhr.send();
}

function fecharModalDP() {
    var modalDespesaPaga = document.getElementById("modalDespesaPaga");
    modalDespesaPaga.style.display = "none"; // Ocultar o modal de despesas pagas
}

function fecharModalDNP() {
    var modalDespesaNaoPaga = document.getElementById("modalDespesaNaoPaga");
    modalDespesaNaoPaga.style.display = "none"; // Ocultar o modal de despesas não pagas
}



function pagarDespesa() {

    if (confirm("Tem certeza que deseja pagar essa despesa?")) {
        var modalDespesaNaoPaga = document.getElementById("modalDespesaNaoPaga");
        var dataPagamentoInput = document.getElementById("modalDataPagamentoNaoPaga").value;

        var idDespesa = modalDespesaNaoPaga.getAttribute("data-id");


        // Verificar se a data de pagamento foi informada
        if (dataPagamentoInput === "") {
            alert("Por favor, informe a data de pagamento.");
            return; // Encerrar a função sem prosseguir com o pagamento
        }else{
            // Executar a lógica de pagamento da despesa
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'pagarDespesa.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        // A requisição foi concluída com sucesso
                        alert(xhr.responseText);
                        // Atualize a tabela ou faça outras ações necessárias
                    }
                };


                // Enviar os dados para pagarDespesa.php
                var params = 'id=' + idDespesa + '&dataPagamento=' + dataPagamentoInput;
                xhr.send(params);

                // Atualizar os dados da despesa
                var row = document.querySelector('tr[data-id="' + idDespesa + '"]');
                if (row.classList.contains('Sim')) {
                    despesasJaPagas.push(row);
                } else {
                    row.classList.remove("Não");
                    row.classList.add("Sim");
                }
                fecharModalDespesaNaoPaga();
        }


    }
}


function excluirDespesa() {
    if (confirm("Tem certeza que deseja excluir essa despesa?")) {

        var idDespesa = document.querySelector(".modal").getAttribute("data-id");

        // Realize a requisição AJAX para excluir o registro no banco de dados
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'excluirDespesa.php', true); // Alterado para 'pagarDespesa.php'
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // A requisição foi concluída com sucesso
                alert(xhr.responseText);
                // Atualize a tabela ou faça outras ações necessárias
                // Por exemplo, recarregue a página ou atualize a lista de despesas
                var linha = document.querySelector('#linha[data-id="' + idDespesa + '"]');
                if (linha) {
                    linha.remove();
                }
            }
        };
        xhr.send('id=' + idDespesa);
        fecharModal();
    }
}


function exibirCalendario() {
    flatpickr(".calendario", {
      dateFormat: "d/m/Y", // Formato da data
      locale: "br", // Idioma do calendário
      disableMobile: true // Desabilita o calendário em dispositivos móveis
    }).open();
  }


