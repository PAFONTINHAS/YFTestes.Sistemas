<?php
require_once '../../conexao/banco.php';
require 'OrganizarReceita.php';


// Verificar se foi fornecido um ID válido na query string
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $idReceita = $_GET['id'];

    // Preparar a consulta SQL e executá-la
    $stmt = $conn->prepare("SELECT * FROM cadrec WHERE id = ?");
    $stmt->bind_param("i", $idReceita);
    $stmt->execute();
    $result = $stmt->get_result();



    if ($result->num_rows > 0) {


        $dadosReceita = $result->fetch_assoc();

        $dataRecebimentoEN = $dadosReceita['data_recebimento'];

        $dataRecebimentoBR = date("d/m/Y", strtotime(str_replace('-', '/', $dataRecebimentoEN)));



        [$tipoReceita, $recebimento, $repete, $valorRecFormatado, $vencimentoBR] = organizacao($dadosReceita['tiporec'], $dadosReceita['tiporecebe'], $dadosReceita['repete'], $dadosReceita['valorrec'] , $dadosReceita['validade']);

        $infoComp = $dadosReceita['infocomp'];

        $dadosReceita['tipoReceita'] = $tipoReceita;
        $dadosReceita['tipoRecebimento'] = $recebimento;
        $dadosReceita['repete'] = $repete;
        $dadosReceita['valorRec'] = $valorRecFormatado;
        $dadosReceita['validade'] = $vencimentoBR;
        $dadosReceita['infoComp'] = $infoComp;


        if($dadosReceita['recebido'] == 1){
            $dadosReceita['recebido'] = "Sim";
        }
        else{
            $dadosReceita['recebido'] = "Não";
        }


        // Agora você pode retornar os dados da receita como uma resposta JSON
        $response = array(
            'receita' => $dadosReceita,
            'dataRecebimento' => $dataRecebimentoBR // Inclua aqui a data de pagamento da receita, se disponível
        );

        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        // Caso o ID não corresponda a nenhuma receita, retorne um erro ou uma resposta vazia, conforme a sua necessidade
        // Por exemplo:
        header('HTTP/1.1 404 Not Found');
        echo "receita não encontrada";
    }

    // Fechar a conexão
    $stmt->close();
    $conn->close();
} else {
    // Caso o ID não tenha sido fornecido ou seja inválido, retorne um erro ou uma resposta vazia, conforme a sua necessidade
    // Por exemplo:
    header('HTTP/1.1 400 Bad Request');
    echo "ID de receita inválido";
}
?>