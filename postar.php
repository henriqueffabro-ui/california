<?php

session_start();
include "config.php";
header('Content-Type: application/json');

$usuario = $_SESSION['id']; // usuário logado
$titulo = $conexao->real_escape_string($_POST["titulo"] ?? ''); // título do post, escapado para evitar SQL injection
$descricao = $conexao->real_escape_string($_POST["descricao"] ?? ''); // descrição do post, escapado para evitar SQL injection

$sqlPost = "INSERT INTO postagens (id_usuario, titulo, descricao)
VALUES ($usuario, '$titulo', '$descricao')";

$conexao->query($sqlPost);

$id_post = mysqli_insert_id($conexao);

$imagens = [];

if (!empty($_FILES['imagem']['name'][0])) {

    foreach ($_FILES['imagem']['tmp_name'] as $key => $tmp) {

        $nome = $_FILES['imagem']['name'][$key];
        $nomeFinal = uniqid() . "_" . $nome;

        move_uploaded_file($tmp, "uploads/" . $nomeFinal);

        $sqlImg = "INSERT INTO imagens (nome, id_post)
        VALUES ('$nomeFinal', '$id_post')";

        mysqli_query($conexao, $sqlImg);

        $imagens[] = $nomeFinal;
    }
}


$resultUser = $conexao->query("SELECT nome FROM usuarios WHERE id = $usuario");
$user = $resultUser->fetch_assoc();
$nome = $user['nome'];


echo json_encode([
    "titulo" => $titulo,
    "descricao" => $descricao,
    "id" => $id_post,
    "nome" => $nome,
    "imagens" => $imagens
]);


?>
