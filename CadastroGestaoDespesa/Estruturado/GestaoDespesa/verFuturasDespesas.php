<?php
require_once '../../../conexao/banco.php';

if (isset($_GET['mes'])) {
    $mesSelecionado = $_GET['mes'];


    // Prepare a consulta SQL
    $query = "SELECT * FROM caddesp WHERE MONTH(vencimento) = ?";
    $stmt = $conexao->prepare($query);

    // Verifique se a preparação da consulta teve sucesso
    if (!$stmt) {
        die('Falha ao preparar a consulta SQL: ' . $conexao->error);
    }

    // Defina o parâmetro e execute a consulta
    $stmt->bind_param('i', $mesSelecionado);
    $stmt->execute();

    // Obtenha o resultado da consulta
    $resultado = $stmt->get_result();

    // Organize as despesas utilizando a função 'organizacao()'
    $despesasOrganizadas = array();
    while ($despesa = $resultado->fetch_assoc()) {
        $despesasOrganizadas[] = organizacao($despesa['categoria'], $despesa['formapag'], $despesa['parcela'], $despesa['imovelassoc'], $despesa['valor'], $despesa['vencimento']);
    }

    // Retorne as despesas pendentes no formato JSON
    echo json_encode($despesasOrganizadas);

    // Feche a conexão e libere os recursos
    $stmt->close();
    $conexao->close();
}
?>
