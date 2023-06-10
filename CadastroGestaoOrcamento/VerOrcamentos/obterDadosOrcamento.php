<?php
require_once '../../conexao/banco.php';

// Verificar se foi fornecido um ID válido na query string
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $idOrcamento = $_GET['id'];

    // Preparar a consulta SQL e executá-la
    $stmt = $conn->prepare("SELECT * FROM cadorc WHERE id = ?");
    $stmt->bind_param("i", $idOrcamento);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {


        $dadosOrcamento = $result->fetch_assoc();

        $titulo = $dadosOrcamento['titulo'];
        $validade = $dadosOrcamento['validade'];
        $valorOrc = $dadosOrcamento['valorOrc'];
        $valorAtual = $dadosOrcamento['valorAtual'];
        $prioridade = $dadosOrcamento['prioridade'];
        $infoComp = $dadosOrcamento['infoComp'];

        $dadosOrcamento['titulo'] = $titulo;
        $dadosOrcamento['validade'] = $validade;
        $dadosOrcamento['valorOrc'] = $valorOrc;
        $dadosOrcamento['valorAtual'] = $valorAtual;
        $dadosOrcamento['prioridade'] = $prioridade;
        $dadosOrcamento['infoComp'] = $infoComp;



        // Agora você pode retornar os dados do orcamento como uma resposta JSON
        $response = array(
            'orcamento' => $dadosOrcamento
        );

        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        // Caso o ID não corresponda a nenhum orcamento, retorne um erro ou uma resposta vazia, conforme a sua necessidade
        // Por exemplo:
        header('HTTP/1.1 404 Not Found');
        echo "Orçamento Não Encontrado";
    }

    // Fechar a conexão
    $stmt->close();
    $conn->close();
} else {
    // Caso o ID não tenha sido fornecido ou seja inválido, retorne um erro ou uma resposta vazia, conforme a sua necessidade
    // Por exemplo:
    header('HTTP/1.1 400 Bad Request');
    echo "ID do orçamento inválido";
}
?>
