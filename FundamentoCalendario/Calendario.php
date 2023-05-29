<?php

// Função para obter o mês atual
function getMonth() {
    return date('m');
}

// Função para obter o ano atual
function getYear() {
    return date('Y');
}

// Função para obter o próximo mês
function getNextMonth($month, $year) {
    $nextMonth = $month + 1;
    $nextYear = $year;

    if ($nextMonth > 12) {
        $nextMonth = 1;
        $nextYear++;
    }

    return array($nextMonth, $nextYear);
}

// Função para obter o mês anterior
function getPreviousMonth($month, $year) {
    $previousMonth = $month - 1;
    $previousYear = $year;

    if ($previousMonth < 1) {
        $previousMonth = 12;
        $previousYear--;
    }

    return array($previousMonth, $previousYear);
}

// Exemplo de uso das funções
$currentMonth = getMonth();
$currentYear = getYear();

$nextMonth = getNextMonth($currentMonth, $currentYear);
$previousMonth = getPreviousMonth($currentMonth, $currentYear);



?>
