
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
                document.getElementById("modalInfoComp").textContent = orcamento.infoComp;

                // Exibir o modal de orcamentos
                modalVerOrcamento.style.display = "block";
        }
    };

    xhr.open("GET", "obterDadosOrcamento.php?id=" + id, true);
    xhr.send();
}

function fecharModal() {
    var modalVerOrcamento = document.getElementById("modalVerOrcamento");
    modalVerOrcamento.style.display = "none"; // Ocultar o modal de orcamentos
}



// function exibirCalendario() {
//     flatpickr(".calendario", {
//       dateFormat: "d/m/Y", // Formato da data
//       locale: "br", // Idioma do calendário
//       disableMobile: true // Desabilita o calendário em dispositivos móveis
//     }).open();
//   }


