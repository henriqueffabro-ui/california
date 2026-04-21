<?php

session_start();
include "config.php";

$id = $_SESSION['id'];

$sql = "SELECT * FROM usuarios WHERE id = $id";
$result = mysqli_query($conexao, $sql);
$usuario = mysqli_fetch_assoc($result);

$sql_posts = "SELECT * FROM postagens WHERE id_usuario = $id";
$result_posts = mysqli_query($conexao, $sql_posts);


$posts = $conexao->query("
    SELECT p.*, u.nome 
    FROM postagens p
    JOIN usuarios u ON p.id_usuario = u.id
    ORDER BY p.data DESC
");

while($p = $posts->fetch_assoc()) {
    include "post.php";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link rel="icon" href="imgs/bulbi.png" type="image/x-icon">
</head>
<body>
    <h1><?= $usuario['nome'] ?></h1>
    <h2>Ideias:</h2><br>
    <?php foreach ($posts as $p): ?>
        <div class="post">
            <h2><?= $p['titulo'] ?></h2>
            <p><?= $p['descricao'] ?></p>
        </div>
    <?php endforeach; ?>

<div class="posts">

    <h3 class="card-user"><?= htmlspecialchars($p["nome"]) ?></h3>
    <h5 class="card-title"><?= htmlspecialchars($p["titulo"]) ?></h5>
    <p class="card-text"><?= nl2br(htmlspecialchars($p["descricao"])) ?></p>

    <h5 class="card-date">Data: <?= $p["data"] ?></h5>

    <!-- Votos -->
    <button onclick="votar(<?= $p['id'] ?>, 1)">
        <img width="15px" src="imgs/arrow.webp">
    </button>

    <span id="upvotes-<?= $p['id'] ?>"><?= $p['upvotes'] ?></span>

    <button onclick="votar(<?= $p['id'] ?>, -1)">
        <img width="15px" src="imgs/arrowd.jpg">
    </button>

    <span id="downvotes-<?= $p['id'] ?>"><?= $p['downvotes'] ?></span>

    <br>

    <!-- Comentário -->
    <input id="comentario-<?= $p['id'] ?>" placeholder="Deixe um comentário..."> 
    <button onclick="postarComentario(<?= $p['id'] ?>)">Postar</button>

    <br><br>

    <!-- Imagens -->
    <?php
    $id_post = $p['id'];
    $result_img = $conexao->query("SELECT * FROM imagens WHERE id_post = $id_post");

    while($img = $result_img->fetch_assoc()){
        if (!empty($img['nome']) && file_exists("uploads/".$img['nome'])) {
            echo "<img class='imgsnopost' src='uploads/".$img['nome']."' width='200'>";
        }
    }
    ?>

    <br>

    <!-- Botões dono -->
    <?php if ($p["nome"] == $_SESSION['nome']): ?>
        <button onclick="editarPostagem(<?= $p["id"] ?>)">Editar</button>
        <button onclick="excluirPostagem(<?= $p["id"] ?>)">Excluir</button>
    <?php endif; ?>

    <?php if ($p['editado'] == 1): ?>
        <p>Editado</p>
    <?php endif; ?>

    <!-- Comentários -->
    <div class="comentarios">
        <?php
        $result = $conexao->query("
            SELECT c.*, u.nome 
            FROM comentarios c
            JOIN usuarios u ON c.usuario_id = u.id
            WHERE c.post_id = {$p['id']}
            ORDER BY c.upvotes DESC
        ");

        $comentarios = [];

        while ($c = $result->fetch_assoc()) {
            $comentarios[$c['parent_id']][] = $c;
        }

        mostrarComentarios(NULL, $comentarios);
        ?>
    </div>

</div>

    
</body>
</html>