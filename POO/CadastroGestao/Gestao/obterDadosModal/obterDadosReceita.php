<?php
session_start();
if (!isset($_SESSION['id_usuario']) || empty($_SESSION['id_usuario'])) {

    header('Location: ../../index.php'); // Redireciona para a página de login
    exit();
}


include ('../../../classes/Receita.php');


$receita = new Receita($db);
$id_usuario = $_SESSION['id_usuario'];


// Verificar se foi fornecido um ID válido na query string
if (isset($_GET['id']) && !empty($_GET['id'])) {

    $idReceita = $_GET['id'];

    $conn = $db;

    // Preparar a consulta SQL e executá-la
    $stmt = $conn->prepare("SELECT * FROM receita WHERE id = :idReceita AND id_usuario = :id_usuario");
    $stmt->bindParam(':idReceita', $idReceita, PDO::PARAM_INT);
    $stmt->bindParam(':id_usuario', $id_usuario);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($result) > 0) {
        $dadosReceita = $result[0];

        [$tipoReceita, $recebimento, $repete, $valorRecFormatado, $validadeBR] = $receita->organizacao($dadosReceita['tiporec'], $dadosReceita['tiporecebe'], $dadosReceita['repete'], $dadosReceita['valorrec'], $dadosReceita['validade']);

        $dataRecebimentoEN = $dadosReceita['data_recebimento'];
        $infoComp = $dadosReceita['infocomp'];

        $dataRecebimentoBR = date("d/m/Y", strtotime(str_replace('-', '/', $dataRecebimentoEN)));
        $validade = $dadosReceita['validade'];

        $novaRepeticao = date("Y-m-d", strtotime($validade . "-20 days"));
        $repeticaoReal = date("d/m/Y", strtotime($novaRepeticao . "today"));

        $dadosReceita['tipoReceita'] = $tipoReceita;
        $dadosReceita['tipoRecebimento'] = $recebimento;
        $dadosReceita['repete'] = $repete;
        $dadosReceita['valorRec'] = $valorRecFormatado;
        $dadosReceita['validade'] = $validadeBR;
        $dadosReceita['infoComp'] = $infoComp;

        if ($dadosReceita['recebido'] == 1) {
            $dadosReceita['recebido'] = "Sim";
        } else {
            $dadosReceita['recebido'] = "Não";
        }

        if ($repete != " Receita Finalizada") {
            $response = array(
                'receita' => $dadosReceita,
                'dataRecebimento' => $dataRecebimentoBR,
                'novaRepeticao' => $repeticaoReal
            );
        } else {
            $dadosReceita['validade'] = $repete;
            $repetcaoReal = $repete;
            $dadosReceita['infoComp'] = $repete;
            $response = array(
                'receita' => $dadosReceita,
                'dataRecebimento' => $dataRecebimentoBR,
                'novaRepeticao' => $repete
            );
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        // Caso o ID não corresponda a nenhuma receita, retorne um erro ou uma resposta vazia, conforme a sua necessidade
        // Por exemplo:
        header('HTTP/1.1 404 Not Found');
        echo "receita não encontrada";
    }

    // Fechar a conexão
    $stmt = null;
    $conn = null;
} else {
    // Caso o ID não tenha sido fornecido ou seja inválido, retorne um erro ou uma resposta vazia, conforme a sua necessidade
    // Por exemplo:
    header('HTTP/1.1 400 Bad Request');
    echo "ID de receita inválido";
}
