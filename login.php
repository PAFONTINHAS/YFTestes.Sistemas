<?php
session_start();

require_once 'conexao/banco.php';

// Verifica se o usuário já está logado
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header('Location: homePage.php');
    exit;
}

// Verifica se o formulário de login foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtém os dados do formulário
    $email = $_POST['email'];
    $senha = $_POST['password'];

    // Prepara a consulta SQL para buscar o usuário no banco de dados
    $stmt = $conn->prepare('SELECT id, email, senha FROM usuario WHERE email = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();

    // Verifica se o usuário existe no banco de dados
    if ($stmt->num_rows === 1) {
        // Vincula as colunas de resultado
        $stmt->bind_result($id, $email, $criptSenha);
        $stmt->fetch();

        // Verifica se a senha está correta
        if (password_verify($senha, $criptSenha)) {
            // Inicia a sessão
            $_SESSION['loggedin'] = true;
            $_SESSION['id'] = $id;
            $_SESSION['email'] = $email;

            // Redireciona para a página inicial
            header('Location: homePage.php');
        } else {
            echo 'Senha incorreta.';
        }
    } else {
        echo 'Usuário não encontrado.';
    }

    // Fecha a consulta
    $stmt->close();
}
?>
