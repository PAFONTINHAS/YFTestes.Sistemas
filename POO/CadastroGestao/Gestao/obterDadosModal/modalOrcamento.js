// FUNÇÃO PARA ABRIR O MODAL
function abrirModal(row) {
    var id = row.getAttribute("data-id");
    var modalVerOrcamento = document.getElementById("modalVerOrcamento");

//     // Define o ID da orcamento no atributo data-id do modal
    modalVerOrcamento.setAttribute("data-id", id);
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            // Processar a resposta JSON e obter os dados do orcamento
            var response = JSON.parse(this.responseText);
            var orcamento = response.orcamento;

                // Preencher os dados no modal de orcamentos
                document.getElementById("modalTitulo").textContent = orcamento.titulo;
                document.getElementById("modalValidade").textContent = orcamento.validade;
                document.getElementById("modalValorOrc").textContent = orcamento.valorOrc;
                document.getElementById("modalValorAtual").textContent = orcamento.valorAtual;
                document.getElementById("modalPrioridade").textContent = orcamento.prioridade;
                document.getElementById("modalValorInserir").value = "";
                document.getElementById("modalInfoComp").textContent = orcamento.infoComp;

                // Exibir o modal de orcamentos
                modalVerOrcamento.style.display = "block";
        }
    };

    xhr.open("GET", "../Gestao/obterDadosModal/obterDadosOrcamento.php?id=" + id, true);
    xhr.send();
}

function fecharModal() {
    var modalVerOrcamento = document.getElementById("modalVerOrcamento");
    modalVerOrcamento.style.display = "none"; // Ocultar o modal de orcamentos
}

// FUNÇÃO PARA SACAR O VALOR

function sacarValor(){

    var modalVerOrcamento = document.getElementById("modalVerOrcamento");
    var inserirValor = document.getElementById("modalValorInserir").value;
    var idOrcamento = modalVerOrcamento.getAttribute("data-id");

    //REMOVENDO AS LETRAS IMPOSTAS PELA MÁSCARA DE MOEDA E CONVERTENDO O VALOR
    var valorInserido = inserirValor.replace(/[^\d,]/g, '');
    var novoValor = parseFloat(valorInserido.replace(',', '.'));

    if(inserirValor === ""){
        alert("O campo não pode ficar vazio");
        return;
    }
    else if( novoValor === 0.00){
        alert("Insira algum valor acima de R$ 0");
        return;
    }
    else if (confirm("Deseja sacar " + inserirValor + " do valor investido?")) {

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '../../classes/Orcamento.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // A requisição foi concluída com sucesso
                alert(xhr.responseText);
                // Atualize a tabela ou faça outras ações necessárias
            }
        };

        if(confirm("Deseja adicionar o valor sacado ao Saldo Atual?")){
            var operacao = "somar";

            // Enviar os dados para atualizarSaldo.php
            var params = 'idSacar='+ idOrcamento + '&inserirValor=' + inserirValor + '&operacao=' + operacao + '&id_usuario=' + id_usuario; // O valor que você deseja adicionar ao saldo
            xhr.send(params);
        }
        else{

            var operacao = null;
            // Enviar os dados para pagarDespesa.php
            var params = 'idSacar=' + idOrcamento + '&inserirValor=' + inserirValor +"&operacao=" + operacao + '&id_usuario=' + id_usuario;
            xhr.send(params);

        }

        fecharModal();
    }
    else{
        alert("Operação cancelada");
        return;
    }
}

// FUNÇÃO PARA SOMAR O VALOR INSERIDO AO VALOR JÁ INVESTIDO

function depositarValor(){

    var modalVerOrcamento = document.getElementById("modalVerOrcamento");
    var idOrcamento = modalVerOrcamento.getAttribute("data-id");
    var inserirValor = document.getElementById("modalValorInserir").value;

    var valorInserido = inserirValor.replace(/[^\d,]/g, '');
    var novoValor = parseFloat(valorInserido.replace(',', '.'));

    if ( inserirValor === "") {

        alert("Por favor, insira um valor válido.");
        return; // Encerrar a função sem prosseguir com o sacamento
    }
    else if(novoValor === 0.00){
        alert("Insira algum valor acima de R$ 0")
    }
    else if (confirm("Deseja Depositar " + inserirValor + " ao orçamento?")) {

        // Verificar se a data de pagamento foi informada
            // Executar a lógica de pagamento da despesa
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '../../classes/Orcamento.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // A requisição foi concluída com sucesso
                    alert(xhr.responseText);
                    // Atualize a tabela ou faça outras ações necessárias
                }
            };

            if(confirm("Deseja subtrair o valor depositado do Saldo Atual?")){
                var operacao = "subtrair";

                // Enviar os dados para atualizarSaldo.php
                var params = 'idDepositar=' + idOrcamento + '&inserirValor=' + inserirValor + "&operacao=" + operacao + '&id_usuario=' + id_usuario;
                xhr.send(params);
            }
            else{

                var operacao = null;

                // Enviar os dados para pagarDespesa.php
                var params = 'idDepositar=' + idOrcamento + '&inserirValor=' + inserirValor + "&operacao=" + operacao + '&id_usuario=' + id_usuario;
                xhr.send(params);

            }

            fecharModal();
        }
        else{
            alert("Operação cancelada");
            return;
        }
    }


    // FUNÇÃO PARA EXCLUIR O ORÇAMENTO
    function excluirOrcamento() {
        if (confirm("Tem certeza que deseja excluir esse orçamento?")) {

            var idOrcamento= document.querySelector(".modal").getAttribute("data-id");

            // Realize a requisição AJAX para excluir o registro no banco de dados
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '../../classes/Orcamento.php', true); // Alterado para 'pagarDespesa.php'
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // A requisição foi concluída com sucesso
                    alert(xhr.responseText);
                    // Atualize a tabela ou faça outras ações necessárias
                    // Por exemplo, recarregue a página ou atualize a lista de despesas
                    var linha = document.querySelector('#linha[data-id="' + idOrcamento + '"]');
                    if (linha) {
                        linha.remove();
                    }
                }
            };
            xhr.send('idExcluir=' + idOrcamento + '&id_usuario=' + id_usuario);
            fecharModal();
        }
    }
