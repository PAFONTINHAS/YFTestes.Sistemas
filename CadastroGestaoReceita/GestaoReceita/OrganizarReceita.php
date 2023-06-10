<?php
require_once '../../conexao/banco.php';

function organizacao($receita, $recebimento, $repete, $valorRec, $vencimento){

// Alterações da Repetição
if($repete == 0){
    $repete = " Receita Finalizada";
    $recebimento = " Receita Finalizada";
}
elseif($repete == 1 ){
    $repete = "valor único";
}
elseif ($repete == 200){
    $repete = "Receita Contínua";
}
else{
    $repete .= " vezes";
}
// Alterações da receita

if($receita == "Salario"){
    $receita = "Salário";
}
elseif($receita == "Comissao"){
    $receita = "Comissão";
}

elseif($receita == "Alimentacao"){
    $receita = "Alimentação";
}
elseif($receita == "Doacao"){
    $receita = "Doação";
}
elseif($receita == "Emprestimo"){
    $receita = "Empréstimo";
}

// Alterações do Recebimento

if ($recebimento == "CartaoCred"){
    $recebimento = "Cartão de Crédito";
}
elseif($recebimento == "CartaoDeb"){
    $recebimento = "Cartão de Débito";
}


$valorRecFormatado = number_format($valorRec, 2, ',', '.');

$vencimentoBR = date("d/m/Y", strtotime(str_replace('-', '/', $vencimento)));

return [$receita, $recebimento, $repete, $valorRecFormatado, $vencimentoBR];



}
