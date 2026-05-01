<?php
//Variáveis para conexão com o banco de dados
$dbHost = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'banco_forum';

//Teste para ver se a conexão com o banco de dados está funcionando
$conexao = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);
//if ($conexao->connect_error) {
//    echo "Falha na conexão: ";
//}
//else {
  //  echo "Conexão bem-sucedida!";
//}


?>