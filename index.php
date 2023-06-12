<?php
require_once 'conexao/banco.php';

// Verifica se o usuário já está logado
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sistema de Login</title>
</head>
<body>
    <h1>Sistema de Login</h1>
    <form action="login.php" method="POST">
        <label for="username">Email:</label>
        <input type="email" name="email" id="username" required><br>

        <label for="password">Senha:</label>
        <input type="password" name="password" id="password" required><br>

        <input type="submit" value="Login">
    </form>
    <p>Ainda não tem uma conta? <a href="registro.php">Registrar</a></p>
</body>
</html>
