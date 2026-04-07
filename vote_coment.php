<?php

session_start();
include "config.php";


$comentario_id = (int) $_POST["comentario_id"];
$tipo = (int) $_POST["tipo"];
$usuario = $_SESSION['id']; // usuário logado

// verifica se já existe voto
$result = $conexao->query("SELECT * FROM votes_comentarios WHERE usuario_id = $usuario AND comentario_id = $comentario_id");

if ($result->num_rows > 0) {
    $voto = $result->fetch_assoc();

    if ($voto['tipo'] == $tipo) {
        // mesmo voto → remove
        $conexao->query("DELETE FROM votes_comentarios WHERE usuario_id = $usuario AND comentario_id = $comentario_id");

        if($tipo == 1){
            $conexao->query("UPDATE comentarios SET upvotes = COALESCE(upvotes, 0) - 1 WHERE id = $comentario_id");
        } else {
            $conexao->query("UPDATE comentarios SET downvotes = COALESCE(downvotes, 0) - 1 WHERE id = $comentario_id");
        }


    } else {
        // voto diferente → atualiza
        $conexao->query("UPDATE votes_comentarios SET tipo = $tipo WHERE usuario_id = $usuario AND comentario_id = $comentario_id");

        if($tipo == 1){
            $conexao->query("UPDATE comentarios SET upvotes = COALESCE(upvotes, 0) + 1, downvotes = COALESCE(downvotes, 0) - 1 WHERE id = $comentario_id");
        } else {
            $conexao->query("UPDATE comentarios SET downvotes = COALESCE(downvotes, 0) + 1, upvotes = COALESCE(upvotes, 0) - 1 WHERE id = $comentario_id");
        }
    }

} else {
    // nunca votou → insere
    $conexao->query("INSERT INTO votes_comentarios (usuario_id, comentario_id, tipo) VALUES ($usuario, $comentario_id, $tipo)");

    if($tipo == 1){
        $conexao->query("UPDATE comentarios SET upvotes = COALESCE(upvotes, 0) + 1 WHERE id = $comentario_id");
    } else {
        $conexao->query("UPDATE comentarios SET downvotes = COALESCE(downvotes, 0) + 1 WHERE id = $comentario_id");
    }
}

//$result = $conexao->query("
  //  SELECT 
    //    SUM(CASE WHEN tipo = 1 THEN 1 ELSE 0 END) as upvotes,
      //  SUM(CASE WHEN tipo = -1 THEN 1 ELSE 0 END) as downvotes
    //FROM votos
    //WHERE post_id = $post_id
//"); 

//if($tipo == 1){
  //  $conexao->query("UPDATE postagens SET upvotes = COALESCE(upvotes, 0) + 1 WHERE id = $post_id");
//} else {
  //  $conexao->query("UPDATE postagens SET downvotes = COALESCE(downvotes, 0) + 1 WHERE id = $post_id");
//}

$row = $result->fetch_assoc();

$row = $conexao->query("SELECT upvotes, downvotes FROM comentarios WHERE id = $comentario_id")->fetch_assoc();


$upcoment = $row['upvotes'] ?? 0;
$downcoment = $row['downvotes'] ?? 0;

echo json_encode([
    "upcoment" => $upcoment,
    "downcoment" => $downcoment
]);


?>