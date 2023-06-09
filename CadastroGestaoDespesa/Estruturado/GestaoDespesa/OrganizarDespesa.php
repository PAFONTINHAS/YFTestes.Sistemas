<?php
require_once '../../../conexao/banco.php';
function organizacao($categoria, $pagamento, $parcela, $imovelAssoc, $valor, $vencimento){

    // Alterações da Parcela
   if($parcela == 0){
        $parcela = " Despesa Finalizada";
        $pagamento = " Despesa Finalizada";

    }
    elseif($parcela == 1 ){
        $parcela = "valor único";
    }
    else{
        $parcela .= " vezes";
    }


    // Alterações da categoria

    if($categoria == "Alimentacao"){
        $categoria = "Alimentação";
    }
    elseif($categoria == "Educacao"){
        $categoria = "Educação";
    }
    elseif($categoria == "Saude"){
        $categoria = "Saúde";
    }
    elseif($categoria == "Vestuario"){
        $categoria = "Vestuário";
    }
    elseif($categoria == "Acessorios"){
        $categoria = "Acessórios";
    }
    elseif($categoria == "Eletronicos"){
        $categoria = "Eletrônicos";
    }
    elseif($categoria == "ServicoPublico"){
        $categoria = "Serviço Público";
    }
    elseif($categoria == "CuidadosPessoais"){
        $categoria = "Cuidados Pessoais";
    }
    elseif($categoria == "Doacoes-Caridade"){
        $categoria = "Doações/Caridade";
    }
    elseif($categoria == "SuperMercado"){
        $categoria = "Super Mercado";
    }

    // Alterações do tipo de pagamento

    if ($pagamento == "CartaoCredito"){
        $pagamento = "Cartão de Crédito";
    }
    elseif($pagamento == "CartaoDebito"){
        $pagamento = "Cartão de Débito";
    }
    elseif($pagamento == "Transferencia"){
        $pagamento = "Tranferência Bancária";
    }
    elseif($pagamento == "Boleto"){
        $pagamento = "Boleto Bancário";
    }


    // Alterações do imóvel associado

    if($imovelAssoc == "Galpao-Armazem"){
        $imovelAssoc = "Galpão/Armazém";
    }
    elseif($imovelAssoc == "Sitio-Fazenda"){
        $imovelAssoc = "Sítio/Fazenda";
    }
    elseif($imovelAssoc == "Chacara"){
        $imovelAssoc = "Chácara";
    }
    elseif($imovelAssoc == "PredioComercial"){
        $imovelAssoc = "Prédio Comercial";
    }
    elseif($imovelAssoc == "SalaComercial"){
        $imovelAssoc = "Sala Comercial";
    }


    $valorDespFormatado = number_format($valor, 2, ',', '.');

    $vencimentoBR = date("d/m/Y", strtotime(str_replace('-', '/', $vencimento)));


    return [$categoria, $pagamento, $parcela, $imovelAssoc, $valorDespFormatado, $vencimentoBR];

}
