<?php

include('../conexao/banco.php');

$database = new Conexao();
$db = $database->getConnection();

class Usuario {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function cadastrar($nome, $email, $senha, $confirSenha) {
        $criptSenha = password_hash($senha, PASSWORD_DEFAULT); // criptografa a senha com um hash seguro
        $criptConfirSenha = password_hash($confirSenha, PASSWORD_DEFAULT);

        // Verificar se o e-mail já está registrado
        $sql = "SELECT COUNT(*) AS count FROM usuario WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // verifica se algum dos campos está vazio
        if (empty($nome) || empty($email) || empty($criptSenha) || empty($criptConfirSenha)) {
            // exibe um alerta e redireciona para a página de cadastro
            echo "<script>alert('Certifique-se de que todas as informações foram inseridas corretamente');</script>";
            echo "<script>location.href='../RegisterPage.php';</script>";
            exit;
        } elseif ($result['count'] > 0) {
            echo "<script>alert('Esse e-mail já está registrado. Por favor, use outro endereço de e-mail para se cadastrar .');</script>";
            echo "<script>location.href='../RegisterPage.php';</script>";
            exit;
        } else {
            // prepara a query de inserção
            $stmt = $this->conn->prepare("INSERT INTO usuario (nome, email, senha, confirSenha) VALUES (:nome, :email, :criptSenha, :criptConfirSenha)");

            // vincula os valores dos parâmetros na query
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':criptSenha', $criptSenha);
            $stmt->bindParam(':criptConfirSenha', $criptConfirSenha);

            // executa a query
            $resultado = $stmt->execute();

            if ($resultado) {
                // exibe uma mensagem de sucesso
                echo "<script> alert('Usuario cadastrado com sucesso') </script>";
                // redireciona para a página de login
                header("Location: ../index.php");
                exit;
            } else {
                // exibe uma mensagem de erro
                echo "Erro ao cadastrar o usuário.";
            }

            return $resultado;
        }
    }



    public function logar($email,$senha){

         // Busca no banco de dados pelo email informado
        $stmt = $this->conn->prepare("SELECT id, nome, email, senha FROM usuario WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);


        // Verifica se o usuário foi encontrado e se a senha corresponde
        if ($usuario && password_verify($senha, $usuario['senha'])) {
            // Login bem sucedido - redireciona para a página de perfil do usuário
            session_start();
            $_SESSION['id_usuario'] = $usuario['id'];
            $_SESSION['nome'] = $usuario['nome'];
            $_SESSION['email'] = $usuario['email'];
            $_SESSION['saldo'] = $usuario ['saldo'];
            header("Location: ../POO/PaginaInicial.php");
            exit();
        }
        else if(empty($email) or empty($senha)){
            if(empty($email)){
                print "<script>alert('Preencha o email corretamente')</script>";
            }

            else{
                print "<script>alert('Preencha a senha corretamente')</script>";
            }
        }
        else if($email != $usuario['email']){
            print "<script>alert('Email não encontrado')</script>";

        }
        else if ($senha != $usuario['senha']){
            print "<script>alert('Senha Incorreta')</script>";
        }
    }


    public function obterDados($id_usuario, &$nome, &$email, &$saldo) {
        $sql = "SELECT nome, email, saldo FROM usuario WHERE id = :id_usuario";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->execute();
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);

        $nome = $dados['nome'];
        $email = $dados['email'];
        $saldo = $dados['saldo'];
    }

    public function adicionarSaldoInicial($id_usuario,$saldoInicial){

            // Verificar se os parâmetros foram recebidos corretamente

            $inserirSaldo = preg_replace('/[^\d,]/', '', $saldoInicial);
            $inserirSaldoAtual = str_replace(',', '.', $inserirSaldo);
            $saldo = floatval($inserirSaldoAtual);

            if ($saldoInicial == NULL) {
                $sql = "UPDATE usuario SET saldo = 0.00 WHERE id = :id_usuario";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);

                if ($stmt->execute()) {
                    echo " Iniciando saldo com R$ 0,00";
                } else {
                    die('Erro ao adicionar o Saldo' . $stmt->errorInfo());
                }
            } else {
                // Verificando se o valor sacado é menor que o valor já depositado
                $sql = "UPDATE usuario SET saldo = :saldo WHERE id = :id_usuario";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindValue(':saldo', $saldo);
                $stmt->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);

                if ($stmt->execute()) {
                    echo "Saldo Adicionado Com Sucesso!";
                } else {
                    die('Erro ao adicionar o Saldo' . $stmt->errorInfo());
                }
            }
        }


}


// SOLICITAÇÃO DO JAVASCRIPT PARA ENVIAR O VALOR DO SALDO INSERIDO PELO USUARIO PARA MANDAR PARA O BANCO DE DADOS

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['SaldoInicial']) && isset($_POST['id_usuario'])){

    $usuario = new Usuario($db);

    $valorSaldo = $_POST['SaldoInicial'];
    $id_usuario = $_POST['id_usuario'];

    $resultado = $usuario->adicionarSaldoInicial($id_usuario,$valorSaldo);

    if ($resultado == TRUE){
        return $resultado;

    }

}


