function abrirModal(row) {
    var id = row.getAttribute("data-id");
    var modal = document.getElementById("myModal");
    var dataAtual = new Date().toLocaleDateString('pt-BR');
    document.getElementById("modalDataPagamento").value = dataAtual;
    modal.setAttribute("data-id", id);

    // Acessar os dados da linha clicada
    var nomeDespesa = row.cells[0].textContent;
    var categoria = row.cells[1].textContent;
    var valor = row.cells[2].textContent;
    var parcela = row.cells[3].textContent;
    var formaPagamento = row.cells[4].textContent;
    var imovelAssociado = row.cells[5].textContent;
    var dataVencimento = row.cells[6].textContent;
    var pago = row.cells[7].textContent;
    var informacoesComplementares = row.cells[8].textContent;

    // Exibir os dados no modal
    document.getElementById("modalTitulo").textContent = nomeDespesa;
    document.getElementById("modalCategoria").textContent = categoria;
    document.getElementById("modalValor").textContent = valor;
    document.getElementById("modalParcela").textContent = parcela;
    document.getElementById("modalFormaPagamento").textContent = formaPagamento;
    document.getElementById("modalImovelAssociado").textContent = imovelAssociado;
    document.getElementById("modalDataVencimento").textContent = dataVencimento;
    document.getElementById("modalPago").textContent = pago;
    document.getElementById("modalInformacoesComplementares").textContent = informacoesComplementares;

    modal.style.display = "block"; // Exibe o modal
}

function fecharModal() {
    var modal = document.getElementById("myModal");
    modal.style.display = "none"; // Oculta o modal
}



function pagarDespesa() {
if (confirm("Tem certeza que deseja pagar essa despesa?")) {
    var pagoSpan = document.getElementById("modalPago");
    var dataPagamentoInput = document.getElementById("modalDataPagamento");

    // Verificar se a despesa já está paga
    if (pagoSpan.textContent === "Sim") {
        alert("Essa despesa já está paga.");
        return; // Encerrar a função sem prosseguir com a marcação de pagamento
    }

    var modal = document.getElementById("myModal");
    var idDespesa = modal.getAttribute("data-id");

    // Definir a data atual como a data de pagamento
    var dataAtual = new Date();
    var dia = String(dataAtual.getDate()).padStart(2, '0');
    var mes = String(dataAtual.getMonth() + 1).padStart(2, '0');
    var ano = dataAtual.getFullYear();
    var dataPagamento = dia + '/' + mes + '/' + ano;

    // Atribuir a data atual ao campo de data de pagamento
    dataPagamentoInput.value = dataPagamento;
    dataPagamentoInput.setAttribute("readonly", true); // Impedir que a data seja alterada

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

    xhr.send('id=' + idDespesa);

    var row = document.querySelector('tr[data-id="' + idDespesa + '"]');
    if (row.classList.contains('Sim')) {
        despesasJaPagas.push(row);
    } else {
        row.classList.remove("Não");
        row.classList.add("Sim");
    }

    fecharModal();
}
}

function excluirDespesa() {
if (confirm("Tem certeza que deseja excluir essa despesa?")) {
    var modal = document.getElementById("myModal");
    var idDespesa = modal.getAttribute("data-id");

    // Realize a requisição AJAX para excluir o registro no banco de dados
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'excluirDespesa.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // A requisição foi concluída com sucesso
            alert(xhr.responseText);
            // Atualize a tabela ou faça outras ações necessárias
            // Por exemplo, recarregue a página ou atualize a lista de despesas
            var linha = document.querySelector('.selecionada[data-id="' + idDespesa + '"]');
        if (linha) {
            linha.remove();
        }
        }
    };
    xhr.send('id=' + idDespesa);
    fecharModal();
}
}


flatpickr(".calendario", {
    dateFormat: "d/m/Y", // Formato da data
    locale: "pt", // Idioma do calendário
    disableMobile: true // Desabilita o calendário em dispositivos móveis
});
