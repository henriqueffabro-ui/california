<?php
    
session_start();


include "config.php";


$parent_id = isset($_POST["parent_id"]) ? (int) $_POST["parent_id"] : null;
$usuario_id = $_SESSION['id']; // usuário logado
$conteudo = $_POST["conteudo"] ?? ''; // resposta enviada pelo usuário, escapada para evitar SQL injection

$resultUser = $conexao->query("SELECT nome FROM usuarios WHERE id = $usuario_id"); //pega o nome do usuario que respondeu, por meio de chave estrangeira
$user = $resultUser->fetch_assoc();
$nome = $user['nome'];

//pega o nome do usuario que fez o comentario original, por meio de chaves estrangeiras
$resultUserPai = $conexao->query("SELECT nome FROM usuarios u JOIN comentarios c ON u.id = c.usuario_id WHERE c.id = $parent_id");
$userPai = $resultUserPai->fetch_assoc();
$nomePai = $userPai['nome'];


if (empty($conteudo)) {
    echo json_encode(["erro" => "Resposta vazia"]);
    exit;
}

$result = $conexao->query("SELECT post_id FROM comentarios WHERE id = $parent_id");

if (!$result || $result->num_rows === 0) {
    echo json_encode(["erro" => "Comentário pai não encontrado"]);
    exit;
}
$row = $result->fetch_assoc();

$post_id = $row['post_id'];

// prepared statement (melhor prática)
$sql = "INSERT INTO comentarios (usuario_id, comentario, parent_id, post_id) VALUES (?, ?, ?, ?)";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("isii", $usuario_id, $conteudo, $parent_id, $post_id);
$stmt->execute();

$id = $stmt->insert_id; // ID do comentário recém-inserido
echo json_encode([
    "conteudo" => $conteudo,
    "parent_id" => $parent_id,
    "id" => $id,
    "nome" => $nome,
    "nomePai" => $nomePai
]);


?>