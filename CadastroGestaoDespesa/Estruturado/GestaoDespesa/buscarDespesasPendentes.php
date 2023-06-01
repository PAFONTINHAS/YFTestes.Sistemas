<?php
// Recupere o valor do mês enviado pela solicitação AJAX
$mesSelecionado = $_GET['mes'];

// Execute a consulta SQL para obter as despesas pendentes para o mês selecionado
$sql = "SELECT * FROM cadesp WHERE MONTH(vencimento) = MONTH('$mesSelecionado') AND pago = 0";
$resultado = $conn->query($sql);

// Prepare um array para armazenar as despesas pendentes
$despesasPendentes = array();

// Percorra os resultados da consulta e adicione as despesas pendentes ao array
while ($row = $resultado->fetch_assoc()) {
  $despesasPendentes[] = array(
    'descricao' => $row['descricao'],
    'valor' => $row['valor'],
    'vencimento' => $row['vencimento']
  );
}

// Retorne as despesas pendentes como uma resposta JSON
echo json_encode($despesasPendentes);
?>
