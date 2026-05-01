<?php

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

        if($tipo == 1){
            $conexao->query("UPDATE postagens SET upvotes = COALESCE(upvotes, 0) - 1 WHERE id = $post_id");
        } else {
            $conexao->query("UPDATE postagens SET downvotes = COALESCE(downvotes, 0) - 1 WHERE id = $post_id");
        }


    } else {
        // voto diferente → atualiza
        $conexao->query("UPDATE votos SET tipo = $tipo WHERE usuario_id = $usuario AND post_id = $post_id");

        if($tipo == 1){
            $conexao->query("UPDATE postagens SET upvotes = COALESCE(upvotes, 0) + 1, downvotes = COALESCE(downvotes, 0) - 1 WHERE id = $post_id");
        } else {
            $conexao->query("UPDATE postagens SET downvotes = COALESCE(downvotes, 0) + 1, upvotes = COALESCE(upvotes, 0) - 1 WHERE id = $post_id");
        }
    }

} else {
    // nunca votou → insere
    $conexao->query("INSERT INTO votos (usuario_id, post_id, tipo) VALUES ($usuario, $post_id, $tipo)");

    if($tipo == 1){
        $conexao->query("UPDATE postagens SET upvotes = COALESCE(upvotes, 0) + 1 WHERE id = $post_id");
    } else {
        $conexao->query("UPDATE postagens SET downvotes = COALESCE(downvotes, 0) + 1 WHERE id = $post_id");
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

$row = $conexao->query("SELECT upvotes, downvotes FROM postagens WHERE id = $post_id")->fetch_assoc();


$up = $row['upvotes'] ?? 0;
$down = $row['downvotes'] ?? 0;

echo json_encode([
    "up" => $up,
    "down" => $down
]);


?>