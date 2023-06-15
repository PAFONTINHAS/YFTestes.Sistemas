<?php

include ('conexao/banco.php');
include ('classes/usuario.php');

$database = new Conexao();
$db = $database->getConnection();

$usuario = new Usuario($db);


if (isset($_POST['Logar'])) {

    $email = $_POST['email'];
    $senha = $_POST['senha'];

    if ($usuario->logar($email,$senha)){
        $_SESSION['id'] = $id;
        exit();
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Finances</title>
    <link rel="shortcut icon" type="image/png" href="./Logo/YourFinancesLogo.jpg">
    <link rel="stylesheet" href="CSS/LoginRegistro/LoginPage.css">
    <script src=""></script>

</head>
<body>

    <main class="conteiner">

    <!-- Elemento principal do site -->

        <h1>Your Finances</h1>

    <form method = "POST">

        <div class="login-conteiner">

            <div class="Logincard">

                <h3>Login</h3>

                <div class="campos">


                    <input type="text" id="usuario" placeholder="Insira o Email" name = "email">

                </div>

                <div class="underline"></div>

                <div class="campos">

                    <input type="password" id="senha" placeholder="Insira a Senha" name = "senha">

                </div>

                <div class="underline"></div>

                <button type="submit" id = "Botao" name = "Logar">Logar</button>

            </div>

            <div class="Register">

                <h3>Não está registrado ainda? <a href="telaRegistro.php" id="registrar">Registre-se</a> </h3>

            </div>

        </div>

    </form>

    </main>


</body>
</html>


