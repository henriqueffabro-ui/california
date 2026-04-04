<?php

var_dump($_POST);
exit;

session_start();
include "config.php";

$post_id = (int) $_POST["post_id"];
$tipo = (int) $_POST["tipo"];
$usuario = $_SESSION['id']; // usuário logado

// verifica se já existe voto
$result = $conexao->query("SELECT * FROM votos WHERE usuario_id = $usuario AND post_id = $post_id");

if ($result->num_rows > 0) {
    $voto = $result->fetch_assoc();

    if ($voto['tipo'] == $tipo) {
        // mesmo voto → remove
        $conexao->query("DELETE FROM votos WHERE usuario_id = $usuario AND post_id = $post_id");
    } else {
        // voto diferente → atualiza
        $conexao->query("UPDATE votos SET tipo = $tipo WHERE usuario_id = $usuario AND post_id = $post_id");
    }

} else {
    // nunca votou → insere
    $conexao->query("INSERT INTO votos (usuario_id, post_id, tipo) VALUES ($usuario, $post_id, $tipo)");
}

// calcula total
$result = $conexao->query("
    SELECT SUM(tipo) as total
    FROM votos
    WHERE post_id = $post_id
");

$row = $result->fetch_assoc();
echo $row['total'] ?? 0;


?>