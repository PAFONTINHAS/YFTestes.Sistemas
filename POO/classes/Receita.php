<?php

require dirname(__DIR__) . "/conexao/banco.php";



$database = new Conexao();
$db = $database->getConnection();


class Receita{

    private $conn;
    private $table_name = "receita";


    public function __construct($db){
        $this->conn = $db;
    }

    public function cadastrarReceita ($id_usuario,$tipoReceita,$tipoReceber,$valorReceita,$dataValidade,$repete,$infocomp){

            // Remover símbolo "R$", pontos de milhar e substituir a vírgula pelo ponto
            $valorRecFormatado = preg_replace('/[^\d,]/', '', $valorReceita);
            // Converter para um número decimal
            $valorRecDecimal = str_replace(',', '.', $valorRecFormatado);
            $valorRecDecimal = floatval($valorRecDecimal);

            // Preparar a consulta SQL com parâmetros
            $sql = "INSERT INTO " . $this->table_name . " (id_usuario, tiporec, tiporecebe, valorrec, validade, repete, infocomp) VALUES (:id_usuario, :tipoReceita, :tipoReceber, :valorRecDecimal, :dataValidade, :repete, :infocomp)";
            $stmt = $this->conn->prepare($sql);

            // Vincular os valores dos parâmetros
            $stmt->bindParam(':id_usuario', $id_usuario);
            $stmt->bindParam(':tipoReceita', $tipoReceita);
            $stmt->bindParam(':tipoReceber', $tipoReceber);
            $stmt->bindParam(':valorRecDecimal', $valorRecDecimal);
            $stmt->bindParam(':dataValidade', $dataValidade);
            $stmt->bindParam(':repete', $repete);
            $stmt->bindParam(':infocomp', $infocomp);

            // Executar a consulta
            if ($stmt->execute()) {

                 echo "<script>alert('Receita Cadastrada com Sucesso');</script>";
                 header("Location: CadastroReceita.php");
                 exit;
                } else {
                    echo "Erro ao cadastrar a receita.";
                }
    }

    // funcao para ler registros
    public function verReceita($id_usuario){
        $query = "SELECT * FROM ". $this->table_name . " WHERE id_usuario = :id_usuario ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->execute();
        return $stmt;
    }

    public function atualizarRecebimento($id, $id_usuario, $dataAtual, $validade) {

        $validadeEN = date("Y-m-d", strtotime(str_replace('/','-', $validade )));
        $novaRepeticao = date("Y-m-d", strtotime($validadeEN . "-20 days"));

        // Verificar se a data atual está dentro do intervalo
        if ($dataAtual == $novaRepeticao && $dataAtual <= $validadeEN){

            $sql = "UPDATE " . $this->table_name . " SET recebido = CASE WHEN repete = 0 THEN 1 ELSE 0 END WHERE id = :id AND id_usuario = :id_usuario";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':id_usuario', $id_usuario);
            $stmt->execute();

        }

    }

    public function receberReceita($idReceita, $id_usuario, $dataRecebimento){

        //Conversão da data de recebimento para o sistema americano de datas
        $dataRecEN = date("Y-m-d", strtotime(str_replace('/', '-', $dataRecebimento)));

        // Operação de subtração da repetição
        $stmt = $this->conn->prepare("SELECT repete FROM " . $this->table_name . " WHERE id = :idReceita AND id_usuario = :id_usuario");
        $stmt->bindParam(':idReceita', $idReceita, PDO::PARAM_INT);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->execute();
        $dadoRepete = $stmt->fetch(PDO::FETCH_ASSOC);
        $RepeticaoAtual = $dadoRepete['repete'];
        $novaRepeticao = 0;

        // operação de adição da data de validade
        $stmt = $this->conn->prepare("SELECT validade FROM " . $this->table_name . " WHERE id = :idReceita AND id_usuario = :id_usuario");
        $stmt->bindParam(':idReceita', $idReceita, PDO::PARAM_INT);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->execute();
        $dadoValidade = $stmt->fetch(PDO::FETCH_ASSOC);
        $validadeAtual = $dadoValidade['validade'];
        $validadeAdd = 0;

        // Pegando o dado do valor do banco de dados
        $stmt = $this->conn->prepare("SELECT valorrec FROM " . $this->table_name . " WHERE id = :idReceita AND id_usuario = :id_usuario");
        $stmt->bindParam(':idReceita', $idReceita, PDO::PARAM_INT);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->execute();
        $consulta = $stmt->fetch(PDO::FETCH_ASSOC);
        $valorBanco = $consulta['valorrec'];

        // Pegando o dado do saldo do banco de dados
        $stmt = $this->conn->prepare("SELECT saldo FROM usuario WHERE id = :id_usuario");
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->execute();
        $consulta2 = $stmt->fetch(PDO::FETCH_ASSOC);
        $saldoBanco = $consulta2['saldo'];

        $saldoAtual = $saldoBanco + $valorBanco;

        if ($RepeticaoAtual == 200) {
            $novaRepeticao = $RepeticaoAtual;
            $validadeAdd = date("Y-m-d", strtotime($validadeAtual . "+1 month"));
        } elseif ($RepeticaoAtual == 0) {
            $novaRepeticao = $RepeticaoAtual;
            $validadeAdd = $validadeAtual;
        } else {
            $novaRepeticao = $RepeticaoAtual - 1;
            $validadeAdd = date("Y-m-d", strtotime($validadeAtual . "+1 month"));
        }

        // Atualize a receita no banco de dados com a data de recebimento fornecida
        $stmt = $this->conn->prepare("UPDATE " . $this->table_name . " SET data_recebimento = :dataRecEN, repete = :novaRepeticao, validade = :validadeAdd, recebido = 1 WHERE id = :idReceita AND id_usuario = :id_usuario");
        $stmt->bindParam(':dataRecEN', $dataRecEN);
        $stmt->bindParam(':novaRepeticao', $novaRepeticao, PDO::PARAM_INT);
        $stmt->bindParam(':validadeAdd', $validadeAdd);
        $stmt->bindParam(':idReceita', $idReceita, PDO::PARAM_INT);
        $stmt->bindParam(':id_usuario', $id_usuario);
        if ($stmt->execute()) {
            $stmt = $this->conn->prepare("UPDATE usuario SET saldo = :saldoAtual WHERE id = :id_usuario");
            $stmt->bindParam(':saldoAtual', $saldoAtual);
            $stmt->bindParam(':id_usuario', $id_usuario);
            if ($stmt->execute()) {
                echo "receita recebida com sucesso.";
            } else {
                echo "Erro ao receber a receita: " . $stmt->errorInfo()[2];
            }
        } else {
            echo "Erro ao receber a receita: " . $stmt->errorInfo()[2];
        }

        $stmt = null;
        $this->conn = null;


    }


    public function excluirReceita($idReceita,$id_usuario){

         // Prepara a instrução SQL de exclusão
         $sql = "DELETE FROM " . $this->table_name . " WHERE id = :idReceita AND id_usuario = :id_usuario";

         // Prepara a declaração
         $stmt = $this->conn->prepare($sql);

         // Vincula o parâmetro de ID à declaração
         $stmt->bindParam(':idReceita', $idReceita, PDO::PARAM_INT);
         $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);

         // Executa a declaração
         if ($stmt->execute()) {
             // Verifica se a exclusão foi bem-sucedida
             echo "Receita excluída com sucesso.";
         } else {
             echo "Falha ao excluir a receita. Erro: " . $stmt->errorInfo()[2];
         }

         // Fecha a declaração
         $stmt = null;

         // Fecha a conexão com o banco de dados
         $this->conn = null;


    }



    public function calcularValores($id_usuario){

        $conn = $this->conn;
            // Cálculo para todas as receitas cadastradas no banco de dados
            $sql = "SELECT SUM(valorrec) AS soma_valores, COUNT(*) AS valor_total FROM " . $this->table_name . " WHERE id_usuario = :id_usuario";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id_usuario', $id_usuario);
            $stmt->execute();
            $dados = $stmt->fetch(PDO::FETCH_ASSOC);
            $valorTotal = $dados['soma_valores'];

            // Cálculo para todas as receitass que já foram pagas
            $query = "SELECT SUM(valorrec) AS soma_receitas_recebidas FROM " . $this->table_name . " WHERE recebido = 1 AND id_usuario = :id_usuario";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':id_usuario', $id_usuario);
            $stmt->execute();
            $recebidos = $stmt->fetch(PDO::FETCH_ASSOC);
            $valorReceitasPagas = $recebidos['soma_receitas_recebidas'];

            // Cálculo para todas as receitas finalizadas
            $query2 = "SELECT SUM(valorrec) AS soma_receitas_final FROM " . $this->table_name . " WHERE repete = 0 AND id_usuario = :id_usuario";
            $stmt = $conn->prepare($query2);
            $stmt->bindParam(':id_usuario', $id_usuario);
            $stmt->execute();
            $finalizados = $stmt->fetch(PDO::FETCH_ASSOC);
            $valorReceitasFinalizadas = $finalizados['soma_receitas_final'];

            // Pegando o dado do saldo do banco de dados
            $query4 = "SELECT saldo FROM usuario WHERE id = :id_usuario";
            $stmt = $conn->prepare($query4);
            $stmt->bindParam(':id_usuario', $id_usuario);
            $stmt->execute();
            $consulta2 = $stmt->fetch(PDO::FETCH_ASSOC);
            $saldoBanco = $consulta2['saldo'];

            // Cálculo do valor a pagar
            $valorReal = $valorTotal - $valorReceitasFinalizadas;
            $valorAReceber = $valorTotal - $valorReceitasPagas;

            // Conversão dos valores para o sistema monetário brasileiro
            $valorAReceberBR = number_format($valorAReceber, 2, ',', '.');
            $valorRealBr = number_format($valorReal, 2, ',', '.');
            $saldo = number_format($saldoBanco, 2, ',', '.');

            // Retornar os valores calculados
            return [
                'valorTotal' => $valorTotal,
                'valorAReceber' => $valorAReceberBR,
                'saldo' => $saldo
            ];



    }

    public static function organizacao($receita, $recebimento, $repete, $valorRec, $vencimento){

        // Alterações da Repetição
        if($repete == 0){
            $repete = " Receita Finalizada";
            // $recebimento = " Receita Finalizada";
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


}


$receita = new Receita($db);

if (isset($_POST["id"]) && isset($_POST["dataRecebimento"]) && isset($_POST['id_usuario'])) {
    $idReceita = $_POST["id"];
    $id_usuario = $_POST['id_usuario'];
    $dataRecebimento = $_POST["dataRecebimento"];

    $receber = $receita->receberReceita($idReceita,$id_usuario,$dataRecebimento);

    if($receber == true){
        return $receber;
    }
}
elseif(isset($_POST["idExcluir"]) && isset($_POST['id_usuario'])){

    $idReceita = $_POST['idExcluir'];
    $id_usuario  = $_POST['id_usuario'];

    $excluir = $receita->excluirReceita($idReceita,$id_usuario);
}
