<?php
    
session_start();
include "config.php";


$post_id = (int) $_POST["post_id"];
$usuario = $_SESSION['id']; // usuário logado
$comentario = $conexao->real_escape_string($_POST["comentario"] ?? ''); // comentário enviado pelo usuário, escapado para evitar SQL injection

$conexao->query("INSERT INTO comentarios (usuario_id, post_id, comentario) VALUES ($usuario, $post_id, '" . $comentario . "')");

$id_comentario = $conexao->insert_id;

$row = $conexao->query("SELECT comentario FROM comentarios WHERE id = $id_comentario")->fetch_assoc();

echo json_encode([
    "comentario" => "<p>{$row['comentario']}</p>"
]);


?>