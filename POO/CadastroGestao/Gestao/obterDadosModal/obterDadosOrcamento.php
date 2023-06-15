<?php

session_start();
if (!isset($_SESSION['id_usuario']) || empty($_SESSION['id_usuario'])) {

    header('Location: ../../index.php'); // Redireciona para a página de login
    exit();
}


include ('../../../classes/Orcamento.php');

$orcamento = new Orcamento($db);


$id_usuario = $_SESSION['id_usuario'];



// Verificar se foi fornecido um ID válido na query string
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $idOrcamento = $_GET['id'];
    $conn = $db;


    // Preparar a consulta SQL e executá-la
    $query = "SELECT * FROM orcamento WHERE id = :idOrcamento AND id_usuario = :id_usuario";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':idOrcamento', $idOrcamento, PDO::PARAM_INT);
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result !== false) {
        $dadosOrcamento = $result;

        $titulo = $dadosOrcamento['titulo'];
        $validade = $dadosOrcamento['validade'];
        $valorOrc = $dadosOrcamento['valorOrc'];
        $valorAtual = $dadosOrcamento['valorAtual'];
        $prioridade = $dadosOrcamento['prioridade'];
        $infoComp = $dadosOrcamento['infoComp'];

        $valorAtualBR = number_format($valorAtual, 2, ',', '.');
        $valorOrcBR = number_format($valorOrc, 2, ',', '.');

        $validadeBR = date("d/m/Y", strtotime(str_replace('-', '/', $validade)));

        $dadosOrcamento['titulo'] = $titulo;
        $dadosOrcamento['validade'] = $validadeBR;
        $dadosOrcamento['valorOrc'] = $valorOrcBR;
        $dadosOrcamento['valorAtual'] = $valorAtualBR;
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
    $stmt = null;
    $conn = null;
} else {
    // Caso o ID não tenha sido fornecido ou seja inválido, retorne um erro ou uma resposta vazia, conforme a sua necessidade
    // Por exemplo:
    header('HTTP/1.1 400 Bad Request');
    echo "ID do orçamento inválido";
}
?>
