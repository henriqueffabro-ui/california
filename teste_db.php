<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conexao = new mysqli("localhost", "root", "", "seu_banco");

if ($conexao->connect_error) {
    die("Erro: " . $conexao->connect_error);
}

echo "Conectado com sucesso";