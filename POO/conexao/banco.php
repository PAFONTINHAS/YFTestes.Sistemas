<?php

    class Conexao{

        private $host = "localhost";
        private $database= "yourfinances";
        private $user = "root";
        private $pass = "";
        public $conn;

        public function getConnection(){

            $this->conn = null;

            try{
                $this->conn = new PDO("mysql:host=". $this->host.";dbname=". $this->database, $this->user, $this->pass);
                $this->conn ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }catch(PDOException $e){
                echo "Erro na conexão: ". $e->getMessage();
            }

            return $this->conn;
        }

    }
