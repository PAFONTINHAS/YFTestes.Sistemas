<?php

require dirname(__DIR__) . "/conexao/banco.php";



$database = new Conexao();
$db = $database->getConnection();


class Despesa{

    private $conn;
    private $table_name = "despesa";


    public function __construct($db){
        $this->conn = $db;
    }

    public function cadastrarDespesa ($id_usuario,$nomeDespesa,$categoria,$valor,$dataVencimento,$formaPagamento,$imovelAssociado,$parcela,$infocomp){

            // Remover símbolo "R$", pontos de milhar e substituir a vírgula pelo ponto
            $valorDespFormatado = preg_replace('/[^\d,]/', '', $valor);
            // Converter para um número decimal
            $valorDespDecimal = str_replace(',', '.', $valorDespFormatado);
            $valorDespDecimal = floatval($valorDespDecimal);

            // Preparar a consulta SQL com parâmetros
            $sql = "INSERT INTO " . $this->table_name . " (id_usuario, nome, categoria, valor, vencimento, formapag, imovelassoc, parcela, infocomp) VALUES (:id_usuario, :nomeDespesa, :categoria, :valorDespDecimal, :dataVencimento, :formaPagamento, :imovelAssociado, :parcela, :infocomp)";
            $stmt = $this->conn->prepare($sql);

            // Vincular os valores dos parâmetros
            $stmt->bindParam(':id_usuario', $id_usuario);
            $stmt->bindParam(':nomeDespesa', $nomeDespesa);
            $stmt->bindParam(':categoria', $categoria);
            $stmt->bindParam(':valorDespDecimal', $valorDespDecimal);
            $stmt->bindParam(':dataVencimento', $dataVencimento);
            $stmt->bindParam(':formaPagamento', $formaPagamento);
            $stmt->bindParam(':imovelAssociado', $imovelAssociado);
            $stmt->bindParam(':parcela', $parcela);
            $stmt->bindParam(':infocomp', $infocomp);

            // Executar a consulta
            if ($stmt->execute()) {

                 echo "<script>alert('Despesa Cadastrada com Sucesso');</script>";
                 header("Location: CadastroDespesa.php");
                 exit;
                } else {
                    echo "Erro ao cadastrar a despesa.";
                }
    }

    // funcao para ler registros
    public function verDespesa($id_usuario){
        $query = "SELECT * FROM ". $this->table_name . " WHERE id_usuario = :id_usuario ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->execute();
        return $stmt;
    }

    public function obterDadosDespesa($db, $id, $id_usuario){

        // Verificar se foi fornecido um ID válido na query string

            $despesa = new Despesa($db);

            $idDespesa = $_GET['id'];
            $id_usuario = $_GET['id_usuario'];
            // Preparar a consulta SQL e executá-la
            $stmt = $this->conn->prepare("SELECT * FROM despesa WHERE id = ? AND id_usuario = ?");
            $stmt->execute([$idDespesa, $id_usuario]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                $dadosDespesa = $result;

                $dataPagamentoEN = $dadosDespesa['data_pagamento'];

                $dataPagamentoBR = date("d/m/Y", strtotime(str_replace('-', '/', $dataPagamentoEN)));

                $nomeDespesa = $dadosDespesa['nome'];

                $infoComp = $dadosDespesa['infocomp'];

                $vencimento = $dadosDespesa['vencimento'];

                [$categoria, $pagamento, $parcela, $imovelAssoc, $valorDespFormatado, $vencimentoBR] = $despesa->organizacao($dadosDespesa["categoria"], $dadosDespesa["formapag"], $dadosDespesa["parcela"], $dadosDespesa["imovelassoc"], $dadosDespesa["valor"], $dadosDespesa['vencimento']);

                $novaParcela = date("Y-m-d", strtotime($vencimento . "-20 days"));

                $parcelaReal = date("Y-m-d", strtotime($novaParcela . "today"));

                $dadosDespesa['categoria'] = $categoria;
                $dadosDespesa['valor'] = $valorDespFormatado;
                $dadosDespesa['parcela'] = $parcela;
                $dadosDespesa['formaPagamento'] = $pagamento;
                $dadosDespesa['imovelAssociado'] = $imovelAssoc;
                $dadosDespesa['dataVencimento'] = $vencimentoBR;
                $dadosDespesa['nomeDespesa'] = $nomeDespesa;
                $dadosDespesa['infoComp'] = $infoComp;

                if ($dadosDespesa['pago'] == 1) {
                    $dadosDespesa['pago'] = "Sim";
                } else {
                    $dadosDespesa['pago'] = "Não";
                }

                // Agora você pode retornar os dados da despesa como uma resposta JSON
                if ($parcela != " Despesa Finalizada") {
                    $response = array(
                        'despesa' => $dadosDespesa,
                        'dataPagamento' => $dataPagamentoEN, // Inclua aqui a data de pagamento da despesa, se disponível
                        'novaParcela' => $parcelaReal
                    );
                } else {
                    $dadosDespesa['dataVencimento'] = $parcela;
                    $parcelaReal = $parcela;
                    $dadosDespesa['infoComp'] = $parcela;
                    $response = array(
                        'despesa' => $dadosDespesa,
                        'dataPagamento' => $dataPagamentoBR, // Inclua aqui a data de pagamento da despesa, se disponível
                        'novaParcela' => $parcelaReal
                    );
                }

                header('Content-Type: application/json');

                echo json_encode($response);
            } else {
                // Caso o ID não corresponda a nenhuma despesa, retorne um erro ou uma resposta vazia, conforme a sua necessidade
                // Por exemplo:
                header('HTTP/1.1 404 Not Found');
                echo "Despesa não encontrada";
            }


        }

        public function calcularValores($id_usuario) {
            $conn = $this->conn; // Obtenha a conexão com o banco de dados da classe

        // Cálculo para todas as receitas cadastradas no banco de dados
        $sql = "SELECT SUM(valor) AS soma_valores, COUNT(*) AS valor_total FROM " . $this->table_name . " WHERE id_usuario = :id_usuario";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->execute();
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);
        $valorTotal = $dados['soma_valores'];

        // Cálculo para todas as despesas que já foram pagas
        $query = "SELECT SUM(valor) AS soma_despesas_pagas FROM " . $this->table_name . " WHERE pago = 1 AND id_usuario = :id_usuario";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->execute();
        $recebidos = $stmt->fetch(PDO::FETCH_ASSOC);
        $valorDespesasPagas = $recebidos['soma_despesas_pagas'];

        // Cálculo para todas as despesas finalizadas
        $query2 = "SELECT SUM(valor) AS soma_despesas_final FROM " . $this->table_name . " WHERE parcela = 0 AND id_usuario = :id_usuario";
        $stmt = $conn->prepare($query2);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->execute();
        $finalizados = $stmt->fetch(PDO::FETCH_ASSOC);
        $valorDespesasFinalizadas = $finalizados['soma_despesas_final'];

        // Pegando o saldo do usuário
        $query3 = "SELECT saldo FROM usuario WHERE id = :id_usuario";
        $stmt = $conn->prepare($query3);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->execute();
        $consulta = $stmt->fetch(PDO::FETCH_ASSOC);
        $saldoBanco = $consulta['saldo'];

        // Cálculo do valor a pagar
        $valorReal = $valorTotal - $valorDespesasFinalizadas;
        $valorAPagar = $valorTotal - $valorDespesasPagas;

        // Conversão dos valores para o sistema monetário brasileiro
        $valorAPagarBR = number_format($valorAPagar, 2, ',', '.');
        $valorTotalBR = number_format($valorTotal, 2, ',', '.');
        $valorRealBr = number_format($valorReal, 2, ',', '.');
        $saldo = number_format($saldoBanco, 2, ',', '.');


        // Retornar os valores calculados
        return [
            'valorTotal' => $valorTotalBR,
            'valorAPagar' => $valorAPagarBR,
            'saldo' => $saldo
        ];

    }


    public function atualizarPagamento($id, $id_usuario, $dataAtual, $vencimentoBR) {
        // Verificar se a data atual está dentro do intervalo
        $vencimento = date("Y-m-d", strtotime(str_replace('/', '-', $vencimentoBR)));
        $novaParcela = date("Y-m-d", strtotime($vencimento . "-20 days"));

        if ($dataAtual == $novaParcela && $dataAtual <= $vencimento) {
            $sql = "UPDATE " . $this->table_name . " SET pago = CASE WHEN parcela = 0 THEN 1 ELSE 0 END WHERE id = :id AND id_usuario = :id_usuario";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':id_usuario', $id_usuario);
            $stmt->execute();
        }
    }


    public function pagarDespesa($idDespesa, $id_usuario, $dataPagamento){


            //Conversão da data de pagamento para o sistema americano
            $dataReal = date("Y-m-d", strtotime(str_replace('/', '-', $dataPagamento)));

            // Pegando o dado da parcela do banco de dados
            $query = "SELECT parcela FROM " . $this->table_name . " WHERE id = :idDespesa AND id_usuario = :id_usuario";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':idDespesa', $idDespesa);
            $stmt->bindParam(':id_usuario', $id_usuario);
            $stmt->execute();
            $dados = $stmt->fetch(PDO::FETCH_ASSOC);
            $parcelaAtual = $dados['parcela'];
            $novaParcela = 0;

            // Pegando o dado do vencimento do banco de dados
            $query2 = "SELECT vencimento FROM " . $this->table_name . " WHERE id = :idDespesa AND id_usuario = :id_usuario";
            $stmt2 = $this->conn->prepare($query2);
            $stmt2->bindParam(':idDespesa', $idDespesa);
            $stmt2->bindParam(':id_usuario', $id_usuario);
            $stmt2->execute();
            $dadoVencimento = $stmt2->fetch(PDO::FETCH_ASSOC);
            $vencimentoAtual = $dadoVencimento['vencimento'];
            $vencimentoAdd;

            // Pegando o dado do valor do banco de dados
            $query3 = "SELECT valor FROM " . $this->table_name . " WHERE id = :idDespesa AND id_usuario = :id_usuario";
            $stmt3 = $this->conn->prepare($query3);
            $stmt3->bindParam(':idDespesa', $idDespesa);
            $stmt3->bindParam(':id_usuario', $id_usuario);
            $stmt3->execute();
            $consulta = $stmt3->fetch(PDO::FETCH_ASSOC);
            $valorBanco = $consulta['valor'];

            // Pegando o dado do saldo do banco de dados
            $query4 = "SELECT saldo FROM usuario WHERE id = :id_usuario";
            $stmt4 = $this->conn->prepare($query4);
            $stmt4->bindParam(':id_usuario', $id_usuario);
            $stmt4->execute();
            $consulta2 = $stmt4->fetch(PDO::FETCH_ASSOC);
            $saldoBanco = $consulta2['saldo'];

            // Calculando o valor atual do saldo
            $saldoAtual = $saldoBanco - $valorBanco;

            if ($parcelaAtual == 0) {
                $novaParcela = $parcelaAtual;
                $vencimentoAdd = $vencimentoAtual;
            } else {
                $novaParcela = $parcelaAtual - 1;
                $vencimentoAdd = date("Y-m-d", strtotime($vencimentoAtual . "+1 month"));
            }

            // Atualize a despesa no banco de dados com a data de pagamento fornecida
            $sql = "UPDATE " . $this->table_name . " SET data_pagamento = :dataReal, pago = 1, parcela = :novaParcela, vencimento = :vencimentoAdd WHERE id = :idDespesa AND id_usuario = :id_usuario";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':dataReal', $dataReal);
            $stmt->bindParam(':novaParcela', $novaParcela);
            $stmt->bindParam(':vencimentoAdd', $vencimentoAdd);
            $stmt->bindParam(':idDespesa', $idDespesa);
            $stmt->bindParam(':id_usuario', $id_usuario);
            $stmt->execute();

            $sql2 = "UPDATE usuario SET saldo = :saldoAtual WHERE id = :id_usuario";
            $stmt2 = $this->conn->prepare($sql2);
            $stmt2->bindParam(':saldoAtual', $saldoAtual);
            $stmt2->bindParam(':id_usuario', $id_usuario);
            $stmt2->execute();

            if ($stmt->rowCount() > 0 && $stmt2->rowCount() > 0) {
                echo "Despesa paga com sucesso.";
            } else {
                echo "Erro ao pagar a despesa: " . $this->conn->errorInfo()[2];
            }

            $this->conn = null;


    }


    public function excluirDespesa($idDespesa, $id_usuario){

            // Prepara a instrução SQL de exclusão
            $sql = "DELETE FROM " . $this->table_name . " WHERE id = :idDespesa AND id_usuario = :id_usuario";

            // Prepara a declaração
            $stmt = $this->conn->prepare($sql);

            // Vincula o parâmetro de ID à declaração
            $stmt->bindParam(':idDespesa', $idDespesa, PDO::PARAM_INT);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);

            // Executa a declaração
            if ($stmt->execute()) {
                // Verifica se a exclusão foi bem-sucedida
                echo "Despesa excluída com sucesso.";
            } else {
                echo "Falha ao excluir a despesa. Erro: " . $stmt->errorInfo()[2];
            }

            // Fecha a declaração
            $stmt = null;

            // Fecha a conexão com o banco de dados
            $this->conn = null;


    }






    public static function organizacao($categoria, $pagamento, $parcela, $imovelAssoc, $valor, $vencimento){

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

}

$despesa = new Despesa($db);


if (isset($_POST["id"]) && isset($_POST["dataPagamento"]) && isset($_POST['id_usuario'])) {

    $idDespesa = $_POST['id'];
    $id_usuario = $_POST['id_usuario'];
    $dataPagamento = $_POST['dataPagamento'];

    $pagar = $despesa->pagarDespesa($idDespesa,$id_usuario,$dataPagamento);

    if ($pagar == true){

        return $pagar;
    }
}
elseif (isset($_POST['idExcluir']) && isset($_POST['id_usuario'])) {

    $idDespesa = $_POST['idExcluir'];
    $id_usuario = $_POST['id_usuario'];

    $excluir = $despesa->excluirDespesa($idDespesa,$id_usuario);

    if ($excluir == true ){

        return $excluir;
    }
}
