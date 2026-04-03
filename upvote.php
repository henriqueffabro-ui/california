<?php
session_start();
include "config.php";

$id = (int) $_POST["id_post"];

$conexao->query("
    UPDATE postagens 
    SET upvotes = COALESCE(upvotes, 0) + 1 
    WHERE id = $id
");

// pega valor atualizado
$result = $conexao->query("SELECT upvotes FROM postagens WHERE id = $id");
$row = $result->fetch_assoc();

echo json_encode([
    "upvotes" => $row["upvotes"]
]);