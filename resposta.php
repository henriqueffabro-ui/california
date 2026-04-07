<?php
    
session_start();


include "config.php";


$parent_id = isset($_POST["parent_id"]) ? (int) $_POST["parent_id"] : null;
$usuario_id = $_SESSION['id']; // usuário logado
$conteudo = $_POST["conteudo"] ?? ''; // resposta enviada pelo usuário, escapada para evitar SQL injection

if (empty($conteudo)) {
    echo json_encode(["erro" => "Resposta vazia"]);
    exit;
}

// prepared statement (melhor prática)
$sql = "INSERT INTO comentarios (usuario_id, comentario, parent_id) VALUES (?, ?, ?)";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("isi", $usuario_id, $conteudo, $parent_id);
$stmt->execute();

echo json_encode([
    "conteudo" => $conteudo,
    "parent_id" => $parent_id
]);


?>