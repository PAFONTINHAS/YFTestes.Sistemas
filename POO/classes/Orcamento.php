<?php

require dirname(__DIR__) . "/conexao/banco.php";



$database = new Conexao();
$db = $database->getConnection();


class Orcamento{

    private $conn;
    private $table_name = "orcamento";


    public function __construct($db){
        $this->conn = $db;
    }

    public function cadastrarOrcamento($id_usuario,$titulo,$validade,$valorOrcamento,$valorAtual,$prioridade,$infoComp){

            // Remover símbolo "R$", pontos de milhar e substituir a vírgula pelo ponto
            $valorOrcFormatado = preg_replace('/[^\d,]/', '', $valorOrcamento);
            // Converter para um número decimal
            $valorOrcDecimal = str_replace(',', '.', $valorOrcFormatado);
            $valorOrc = floatval($valorOrcDecimal);

            // Remover símbolo "R$", pontos de milhar e substituir a vírgula pelo ponto
            $valorAtualFormatado = preg_replace('/[^\d,]/', '', $valorAtual);
            // Converter para um número decimal
            $valorAtualDecimal = str_replace(',', '.', $valorAtualFormatado);
            $valorAtual = floatval($valorAtualDecimal);

            // Preparar a consulta SQL com parâmetros
            $sql = "INSERT INTO " . $this->table_name . " (id_usuario, titulo, validade, valorOrc, valorAtual, prioridade, infocomp) VALUES (:id_usuario, :titulo, :validade, :valorOrc, :valorAtual, :prioridade, :infocomp)";
            $stmt = $this->conn->prepare($sql);

            // Vincular os valores dos parâmetros
            $stmt->bindParam(':id_usuario', $id_usuario);
            $stmt->bindParam(':titulo', $titulo);
            $stmt->bindParam(':validade', $validade);
            $stmt->bindParam(':valorOrc', $valorOrc);
            $stmt->bindParam(':valorAtual', $valorAtual);
            $stmt->bindParam(':prioridade', $prioridade);
            $stmt->bindParam(':infocomp', $infocomp);

            // Executar a consulta
            if ($stmt->execute()) {

                 echo "<script>alert('Orcamento Cadastrada com Sucesso');</script>";
                 header("Location: CadastroOrcamento.php");
                 exit;
                } else {
                    echo "Erro ao cadastrar a Orcamento.";
                }
    }

    // funcao para ler registros
    public function verOrcamento($id_usuario){
        $query = "SELECT * FROM ". $this->table_name . " WHERE id_usuario = :id_usuario ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->execute();
        return $stmt;
    }

    public function pegarSaldo ($id_usuario){

        // Preparando a consulta
        $query = "SELECT saldo FROM usuario WHERE id = :id_usuario";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);

        // Executando a consulta
        $stmt->execute();

        // Obtendo o saldo do banco de dados
        $consulta = $stmt->fetch(PDO::FETCH_ASSOC);
        $saldoBanco = $consulta['saldo'];

        $saldo = number_format($saldoBanco, 2, ',', '.');

        return [ 'Saldo' => $saldo ];
    }

    public function sacarValor($idOrcamento,$id_usuario,$inserirValorBR,$operacao){

        echo "PORRAAAAAAAAAAAAAA";

                // Convertendo o Valor Atual para o sistema monetário americano
                // Removendo qualquer tipo de caractere do input
                $inserirValorEN = preg_replace('/[^\d,]/', '', $inserirValorBR);
                $inserirValorAtual = str_replace(',', '.', $inserirValorEN);
                $inserirValorAtualDecimal = floatval($inserirValorAtual);
                $valorReal = $inserirValorAtualDecimal;

                // Pegando valorAtual do banco de dados
                $query = "SELECT valorAtual FROM " . $this->table_name . " WHERE id = :idOrcamento AND id_usuario = :id_usuario";
                $stmt =  $this->conn->prepare($query);
                $stmt->bindParam(':idOrcamento', $idOrcamento, PDO::PARAM_INT);
                $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                $stmt->execute();
                $dadoValor = $stmt->fetch(PDO::FETCH_ASSOC);
                $valor = $dadoValor['valorAtual'];

                // Buscando o saldo do banco de dados
                $query  = "SELECT saldo FROM usuario WHERE id = :id_usuario";
                $stmt =  $this->conn->prepare($query);
                $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                $stmt->execute();
                $consulta = $stmt->fetch(PDO::FETCH_ASSOC);
                $saldoBanco = $consulta['saldo'];

                if($operacao == "somar"){
                    $novoSaldo = $saldoBanco + $valorReal;
                } else {
                    $novoSaldo = $saldoBanco;
                }

                // Verificando se o valor sacado é menor que o valor já depositado
                if($valor >= $valorReal){
                    $valorSubtraido = $valor - $valorReal;

                    $sql = "UPDATE " . $this->table_name . " SET valorAtual = :valorSubtraido WHERE  id = :idOrcamento AND id_usuario = :id_usuario";
                    $stmt =  $this->conn->prepare($sql);
                    $stmt->bindParam(':valorSubtraido', $valorSubtraido);
                    $stmt->bindParam(':idOrcamento', $idOrcamento, PDO::PARAM_INT);
                    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                    $stmt->execute();

                    $sql2 = "UPDATE usuario SET saldo = :novoSaldo WHERE id = :id_usuario";
                    $stmt =  $this->conn->prepare($sql2);
                    $stmt->bindParam(':novoSaldo', $novoSaldo);
                    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                    $stmt->execute();

                    if($operacao != "somar") {
                        echo "Valor sacado com êxito";
                    } else {
                        echo "Valor sacado com sucesso e adicionado ao saldo";
                    }
    }


}

public function depositarValor($idOrcamento,$id_usuario,$inserirValorBR,$operacao){


     // Convertendo o Valor Atual para o sistema monetário americano
     $inserirValorEN = preg_replace('/[^\d,]/', '', $inserirValorBR);
     $inserirValorAtual = str_replace(',', '.', $inserirValorEN);
     $inserirValorAtualDecimal = floatval($inserirValorAtual);
     $valorDepositado = $inserirValorAtualDecimal;

     // Pegando valorAtual do banco de dados
     $query = "SELECT * FROM " . $this->table_name . " WHERE id = :idOrcamento AND id_usuario = :id_usuario";
     $stmt =  $this->conn->prepare($query);
     $stmt->bindParam(':idOrcamento', $idOrcamento, PDO::PARAM_INT);
     $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
     $stmt->execute();
     $dadoValor = $stmt->fetch(PDO::FETCH_ASSOC);
     $valorAtual = $dadoValor['valorAtual'];
     $orcamento = $dadoValor['valorOrc'];

     // Buscando o saldo do banco de dados
     $query  = "SELECT saldo FROM usuario WHERE id = :id_usuario";
     $stmt =  $this->conn->prepare($query);
     $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
     $stmt->execute();
     $consulta = $stmt->fetch(PDO::FETCH_ASSOC);
     $saldoBanco = $consulta['saldo'];

     // Subtraindo o valor do orçamento com o valor já depositado
     $subtracao = $orcamento - $valorAtual;

     if($operacao == "subtrair"){
         $novoSaldo = $saldoBanco - $valorDepositado;
     } else {
         $novoSaldo = $saldoBanco;
     }

     // Verificando se o valor sacado é menor que o valor já depositado
     if($valorDepositado <= $subtracao){
         $deposito = $valorAtual + $valorDepositado;

         $sql = "UPDATE " . $this->table_name . " SET valorAtual = :deposito WHERE  id = :idOrcamento AND id_usuario = :id_usuario";
         $stmt =  $this->conn->prepare($sql);
         $stmt->bindParam(':deposito', $deposito);
         $stmt->bindParam(':idOrcamento', $idOrcamento, PDO::PARAM_INT);
         $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
         $stmt->execute();

         $sql2 = "UPDATE usuario SET saldo = :novoSaldo WHERE id = :id_usuario";
         $stmt =  $this->conn->prepare($sql2);
         $stmt->bindParam(':novoSaldo', $novoSaldo);
         $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
         $stmt->execute();

         if($operacao != "subtrair"){
             echo "Valor depositado com êxito";
         } else {
             echo "Valor depositado com sucesso e removido do saldo";
         }
     } else {
         if ($valorAtual == $orcamento){
             echo "Você já atingiu o limite do seu orçamento";
         } else {
             $subtracaoConvertida = number_format($subtracao, 2, ',', '.');
             echo "Erro: Deposite um valor menor ou igual a R$ " . $subtracaoConvertida;
         }
     }

}

public function excluirOrcamento($idOrcamento,$id_usuario){

        // Prepara a instrução SQL de exclusão
    $sql = "DELETE FROM " . $this->table_name . " WHERE id = :idOrcamento AND id_usuario = :id_usuario";

    // Prepara a declaração
    $stmt =  $this->conn->prepare($sql);

    // Vincula o parâmetro de ID à declaração
    $stmt->bindParam(':idOrcamento', $idOrcamento, PDO::PARAM_INT);
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);

    // Executa a declaração
    if ($stmt->execute()) {
        // Verifica se a exclusão foi bem-sucedida
        echo "Orçamento excluído com sucesso.";
    } else {
        echo "Falha ao excluir o Orçamento. Erro: " . $stmt->errorInfo()[2];
    }

    // Fecha a declaração
    $stmt->closeCursor();

}




}


$orcamento = new Orcamento($db);

// Verificar se os parâmetros foram recebidos corretamente
if (isset($_POST["idSacar"]) && isset($_POST["inserirValor"]) && isset($_POST["operacao"]) && isset($_POST['id_usuario'])) {


    // Obter os valores dos parâmetros
    $idOrcamento = $_POST["idSacar"];
    $id_usuario = $_POST['id_usuario'];
    $inserirValorBR = $_POST["inserirValor"];
    $operacao = $_POST['operacao'];

    $sacar = $orcamento->sacarValor($idOrcamento,$id_usuario,$inserirValorBR,$operacao);

    if($sacar == true){

        return $sacar;
    }
} elseif(isset($_POST["idDepositar"]) && isset($_POST["inserirValor"]) && isset($_POST["operacao"]) && isset($_POST['id_usuario'])){

    // Obter os valores dos parâmetros
    $idOrcamento = $_POST["idDepositar"];
    $id_usuario = $_POST['id_usuario'];
    $inserirValorBR = $_POST["inserirValor"];
    $operacao = $_POST['operacao'];

    $depositar = $orcamento->depositarValor($idOrcamento,$id_usuario,$inserirValorBR,$operacao);

    if($depositar == true){
        return $depositar;
    }


} elseif(isset($_POST["idExcluir"]) && isset($_POST['id_usuario'])){

    $idOrcamento = $_POST["idExcluir"];
    $id_usuario = $_POST['id_usuario'];

    $excluir = $orcamento->excluirOrcamento($idOrcamento,$id_usuario);

    if($excluir == true){

        return $excluir;
    }

}
