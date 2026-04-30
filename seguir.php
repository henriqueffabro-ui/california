<?php

include 'config.php';
session_start();


$id_usuario = $_POST["id_usuario"];
$id_seguidor = $_SESSION['id']; // usuário logado

$result = $conexao->query("SELECT * FROM seguidores WHERE id_seguidor = $id_seguidor AND id_seguido = $id_usuario");

if ($result->num_rows > 0) {
    $seguir = $result->fetch_assoc();

        // usuário já está seguindo este perfil → remove
        $conexao->query("DELETE FROM seguidores WHERE id_seguidor = $id_seguidor AND id_seguido = $id_usuario");
        $conexao->query("UPDATE usuarios SET seguidores = COALESCE(seguidores, 0) - 1 WHERE id = $id_usuario");
        $seguindo = false;
 }else {
    // usuário não está seguindo este perfil → insere
    $conexao->query("INSERT INTO seguidores (id_seguidor, id_seguido) VALUES ($id_seguidor, $id_usuario)");
    $conexao->query("UPDATE usuarios SET seguidores = COALESCE(seguidores, 0) + 1 WHERE id = $id_usuario");
    $seguindo = true;
}

echo json_encode([
    
    "seguindo" => $seguindo
]);
?>
       