<?php

session_start();
include "config.php";
header('Content-Type: application/json');

$usuario = $_SESSION['id']; // usuário logado
$titulo = $conexao->real_escape_string($_POST["titulo"] ?? ''); // título do post, escapado para evitar SQL injection
$descricao = $conexao->real_escape_string($_POST["descricao"] ?? ''); // descrição do post, escapado para evitar SQL injection

$resultUser = $conexao->query("SELECT nome FROM usuarios WHERE id = $usuario");
$user = $resultUser->fetch_assoc();
$nome = $user['nome'];

$conexao->query("INSERT INTO postagens (id_usuario, titulo, descricao) VALUES ($usuario, '" . $titulo . "', '" . $descricao . "')");

$id_post = $conexao->insert_id;
$row = $conexao->query("SELECT titulo, descricao FROM postagens WHERE id = $id_post")->fetch_assoc();

echo json_encode([
    "titulo" => $row['titulo'],
    "descricao" => $row['descricao'],
    "id" => $id_post,
    "nome" => $nome
]);


?>
