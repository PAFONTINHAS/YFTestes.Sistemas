<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "CadDesp";

// Criando uma conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificando se a conexão foi estabelecida com sucesso
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}


// Executar operações no banco de dados...

// Fechar a conexão

?>
