var idsSelecionados = []; // Variável global para armazenar os IDs das linhas selecionadas

function selecionarLinha(linha) {
    linha.classList.toggle('selecionada');
    var id = linha.dataset.id;

    // Atualiza a lista de IDs selecionados
    if (linha.classList.contains('selecionada')) {
        idsSelecionados.push(id);
    } else {
        var index = idsSelecionados.indexOf(id);
        if (index !== -1) {
            idsSelecionados.splice(index, 1);
        }
    }

    console.log("IDs selecionados:", idsSelecionados);
}


document.addEventListener('click', function(event) {
    // Verifica se o clique ocorreu fora da tabela
    if (!event.target.closest('table')) {
        // Remove a classe "selecionada" de todas as linhas
        var linhasSelecionadas = document.querySelectorAll('.selecionada');
        linhasSelecionadas.forEach(function(linha) {
            linha.classList.remove('selecionada');
            idsSelecionados = [];
        });
    }
    console.log("Evento de clique em uma área em branco acionado!");
    console.log(idsSelecionados);


});


function pagarDespesa() {
    var linhasSelecionadas = document.querySelectorAll('.selecionada');

    var despesasJaPagas = [];

    if (linhasSelecionadas.length === 0) {
        alert("Selecione pelo menos uma despesa para pagar.");
        return;
    }

    linhasSelecionadas.forEach(function(linha) {
            if (linha.classList.contains('Sim')) {
                despesasJaPagas.push(linha);
            } else {
                // Marcar a despesa como paga
                linha.classList.remove('Não');
                linha.classList.add('Sim');
                linha.querySelector('.pago-col').textContent = 'Sim';
            }
        });

        if (despesasJaPagas.length > 0) {
            var mensagem = "As despesas marcadas como pagas não serão alteradas:\n";
            despesasJaPagas.forEach(function(linha) {
            });
            alert(mensagem);
        }

    else if (confirm("Tem certeza que deseja marcar as despesas selecionadas como pagas?")) {



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

        xhr.send('ids=' + idsSelecionados.join(','));



    }
}



function excluirSelecionados() {
    if (idsSelecionados.length === 0) {
        alert("Selecione pelo menos uma linha para excluir.");
        return;
    }

    if (confirm("Tem certeza que deseja excluir as despesas selecionadas?")) {
        // Realize a requisição AJAX para excluir os registros no banco de dados
        // Use a variável idsSelecionados para enviar os IDs para o arquivo PHP
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'excluirDespesa.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // A requisição foi concluída com sucesso
                alert(xhr.responseText);
                // Atualize a tabela ou faça outras ações necessárias
            }
        };

        xhr.send('ids=' + idsSelecionados.join(','));

        var linhasSelecionadas = document.querySelectorAll('.selecionada');
        linhasSelecionadas.forEach(function(linha) {
            linha.remove();


        });

        // Limpa a lista de IDs selecionados
        idsSelecionados = [];


    }

}

function abrirModal(row) {
    var id = row.getAttribute("data-id");
    var modal = document.getElementById("myModal");

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


