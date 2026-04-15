<?php

session_start();
include "config.php";

$post_id = (int) $_POST["post_id"];
$usuario = $_SESSION['id']; // usuário logado
$titulo = $conexao->real_escape_string($_POST["titulo"] ?? ''); // título do post, escapado para evitar SQL injection
$descricao = $conexao->real_escape_string($_POST["descricao"] ?? ''); // descrição do post, escapado para evitar SQL injection

$resultUser = $conexao->query("SELECT nome FROM usuarios WHERE id = $usuario");
$user = $resultUser->fetch_assoc();
$nome = $user['nome'];

$conexao->query("INSERT INTO posts (usuario_id, titulo, descricao) VALUES ($usuario, '" . $titulo . "', '" . $descricao . "')");

$id_post = $conexao->insert_id;
$row = $conexao->query("SELECT titulo, descricao FROM posts WHERE id = $id_post")->fetch_assoc();

echo json_encode([
    "titulo" => "<h2>{$row['titulo']}</h2>",
    "descricao" => "<p>{$row['descricao']}</p>",
    "id" => $id_post,
    "nome" => $nome
]);


?>
