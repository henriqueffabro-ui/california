<?php
session_start();
include "config.php";

$id = (int) $_POST["id_post"];

$conexao->query("
    UPDATE postagens 
    SET downvotes = COALESCE(downvotes, 0) + 1 
    WHERE id = $id
");

// pega valor atualizado
$result = $conexao->query("SELECT downvotes FROM postagens WHERE id = $id");
$row = $result->fetch_assoc();

echo json_encode([
    "downvotes" => $row["downvotes"]
]);
?>