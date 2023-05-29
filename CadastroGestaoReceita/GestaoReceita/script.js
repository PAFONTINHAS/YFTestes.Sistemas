function abrirModal(row) {
    var id = row.getAttribute("data-id");
    var modalRecetiaRecebida = document.getElementById("modalReceitaRecebida");
    var modalReceitaNaoRecebida = document.getElementById("modalReceitaNaoRecebida");

    // Define o ID da despesa no atributo data-id do modal
    modalRecetiaRecebida.setAttribute("data-id", id);
    modalReceitaNaoRecebida.setAttribute("data-id", id);

    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            // Processar a resposta JSON e obter os dados da despesa
            var response = JSON.parse(this.responseText);
            var receita = response.receita;
            var dataRecebimento = response.dataRecebimento;

            if (receita.recebido === "Sim") {
                // Preencher os dados no modal de despesas pagas
                document.getElementById("modalTituloRecebida").textContent = receita.tipoReceita;
                document.getElementById("modalTipoRecebimentoRecebida").textContent = receita.tipoRecebimento;
                document.getElementById("modalValorRecebida").textContent = receita.valorRec;
                document.getElementById("modalRepeticaoRecebida").textContent = receita.repete;
                document.getElementById("modalValidadeRecebida").textContent = receita.validade;
                document.getElementById("modalRecebidoRecebida").textContent = receita.recebido;
                document.getElementById("modalDataRecebimentoRecebida").textContent = dataRecebimento;
                document.getElementById("modalInformacoesComplementaresRecebida").textContent = receita.infoComp;

                // Exibir o modal de despesas pagas
                modalReceitaRecebida.style.display = "block";
            } else {
                // Preencher os dados no modal de despesas não pagas
                document.getElementById("modalTituloNaoRecebida").textContent = receita.tipoReceita;
                document.getElementById("modalTipoRecebimentoNaoRecebida").textContent = receita.tipoRecebimento;
                document.getElementById("modalValorNaoRecebida").textContent = receita.valorRec;
                document.getElementById("modalRepeticaoNaoRecebida").textContent = receita.repete;
                document.getElementById("modalValidadeNaoRecebida").textContent = receita.validade;
                document.getElementById("modalRecebidoNaoRecebida").textContent = receita.recebido;
                document.getElementById("modalDataRecebimento").textContent = "";
                document.getElementById("modalInformacoesComplementaresNaoRecebida").textContent = receita.infoComp;

                // Exibir o modal de despesas não pagas
                modalReceitaNaoRecebida.style.display = "block";
            }
        }
    };

    xhr.open("GET", "obterDadosReceita.php?id=" + id, true);
    xhr.send();
}


function fecharModalReceitaR() {
    var modalDespesaPaga = document.getElementById("modalReceitaRecebida");
    modalDespesaPaga.style.display = "none"; // Ocultar o modal de despesas pagas
}

function fecharModalReceitaNR() {
    var modalDespesaNaoPaga = document.getElementById("modalReceitaNaoRecebida");
    modalDespesaNaoPaga.style.display = "none"; // Ocultar o modal de despesas não pagas
}



function receberReceita() {

    if (confirm("Tem certeza que deseja receber essa receita?")) {

        var modalReceitaNaoRecebida = document.getElementById("modalReceitaNaoRecebida");
        var idReceita = modalReceitaNaoRecebida.getAttribute("data-id");
        var dataRecebimento = document.getElementById("modalDataRecebimento").value;

        // Verificar se a data de pagamento foi informada
        if (dataRecebimento === "") {
            alert("Por favor, informe a data de recebimento.");
            return; // Encerrar a função sem prosseguir com o pagamento
        }else{

            // Executar a lógica de pagamento da receita
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'receberReceita.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        // A requisição foi concluída com sucesso
                        alert(xhr.responseText);
                        // Atualize a tabela ou faça outras ações necessárias
                    }
                };


                // Enviar os dados para receberReceita.php
                var params = 'id=' + idReceita + '&dataRecebimento=' + dataRecebimento;
                xhr.send(params);
                fecharModalReceitaNR();

                // Atualizar os dados da receita
                var row = document.querySelector('tr[data-id="' + idReceita + '"]');
                if (row.classList.contains('Sim')) {
                    receitasJaRecebidas.push(row);
                } else {
                    row.classList.remove("Não");
                    row.classList.add("Sim");
                }
        }


    }
}



function excluirReceita() {
    if (confirm("Tem certeza que deseja excluir essa receita?")) {

        var idReceita = document.querySelector(".modal").getAttribute("data-id");

        // Realize a requisição AJAX para excluir o registro no banco de dados
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'excluirReceita.php', true); // Alterado para 'pagarReceita.php'
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // A requisição foi concluída com sucesso
                alert(xhr.responseText);
                // Atualize a tabela ou faça outras ações necessárias
                // Por exemplo, recarregue a página ou atualize a lista de receitas

                var linha = document.querySelector('#linha[data-id="' + idReceita + '"]');
                if (linha) {
                    linha.remove();
                }
            }
        };
        xhr.send('id=' + idReceita);
        fecharModalReceitaR();

    }
}


function exibirCalendario() {
    flatpickr(".calendarioReceita", {
      dateFormat: "d/m/Y", // Formato da data
      locale: "br", // Idioma do calendário
      disableMobile: true // Desabilita o calendário em dispositivos móveis
    }).open();
  }
