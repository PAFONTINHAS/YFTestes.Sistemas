<?php
require_once 'conexao/banco.php';

// Verifica se o usuário já está logado
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header('Location: home.php');
    exit;
}

// Verifica se o formulário de registro foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtém os dados do formulário
    $nome = $_POST['username'];
    $email = $_POST['email'];
    $senha = $_POST['password'];
    $confirSenha = $_POST['confirSenha'];


    // Verifica se houve algum erro na conexão
    if ($conn->connect_error) {
        die('Erro de conexão: ' . $conn->connect_error);
    }

    // Verifica se o nome de usuário já está em uso
    $stmt = $conn->prepare('SELECT id FROM usuario WHERE email = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo 'Email já cadastrado.';
    } else {
        // Insere o novo usuário no banco de dados
        $stmt = $conn->prepare('INSERT INTO usuario (nome, email, senha, confirSenha) VALUES (?, ?, ?, ?)');
        $criptSenha = password_hash($senha, PASSWORD_DEFAULT);
        $criptConfirSenha = password_hash($confirSenha, PASSWORD_DEFAULT);

        $stmt->bind_param('ssss', $nome, $email, $criptSenha, $criptConfirSenha);

        if ($stmt->execute()) {
            echo 'Cadastro realizado com sucesso. <a href="index.php">Fazer login</a>.';
        } else {
            echo 'Erro ao realizar o cadastro.';
        }
    }

    // Fecha a consulta e a conexão com o banco de dados
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cadastro de Usuário</title>
</head>
<body>
    <h1>Cadastro de Usuário</h1>
    <form  method="POST">
        <label for="nome">Nome:</label>
        <input type="nome" name="username" id="username" required><br>


        <label for="email">email:</label>
        <input type="email" name="email" id="password" required><br>

        <label for="password">Senha:</label>
        <input type="password" name="password" id="password" required><br>

        <label for="password">confirmar Senha:</label>
        <input type="password" name="confirSenha" id="password" required><br>


        <input type="submit" value="Registrar">
    </form>
</body>
</html>
