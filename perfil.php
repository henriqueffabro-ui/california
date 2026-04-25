<?php

session_start();
include "config.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    //echo "ID do usuário: " . $id; // Debug: Exibe o ID do usuário
} else {
    echo "Usuário não encontrado";
    exit;
}

$sql = "SELECT * FROM usuarios WHERE id = $id";
$result = mysqli_query($conexao, $sql);
$usuario = mysqli_fetch_assoc($result);

$sql_posts = "SELECT * FROM postagens WHERE id_usuario = $id";
$result_posts = mysqli_query($conexao, $sql_posts);


$posts = $conexao->query("
    SELECT p.*, u.nome, u.foto_perfil
    FROM postagens p
    JOIN usuarios u ON p.id_usuario = u.id
    ORDER BY p.data DESC
");

//while($p = $posts->fetch_assoc()) {
  //  include "post.php";
//}

if (isset($_FILES['inputPfp'])) {
    $pfp = $_FILES['inputPfp'];
    $ext = pathinfo($pfp['name'], PATHINFO_EXTENSION);
    $pfpNome = uniqid() . "." . $ext;
    $caminho = "uploads/" . $pfpNome;

move_uploaded_file($pfp['tmp_name'], $caminho);

$sqlImg = "UPDATE usuarios SET foto_perfil = '$caminho' WHERE id = $id";
mysqli_query($conexao, $sqlImg);
header("Location: perfil.php");
exit;


}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link rel="icon" href="imgs/bulbi.png" type="image/x-icon">
    <link rel="stylesheet" href="perfil.css">
    <script src="perfil.js"></script>
    
</head>
<body>
    <h1><?= $usuario['nome'] ?></h1>
    <button onclick="location.href='sistema.php'">Voltar para o feed</button>
    <form id="formPfp" method="POST" enctype="multipart/form-data">
    <input type="file" id="filePfp" style="display: none;"
       name="inputPfp"
       onchange="document.getElementById('formPfp').submit();"> <!-- esse fica escondigo, e aciona a função mostrarImg. Esse input é acionado pelo button-->

        <button type="button" onclick="document.getElementById('filePfp').click()"> <!-- aciona o input escondigo -->
            Alterar foto de perfil
        </button>
    </form>
        <?php
        if (!empty($usuario['foto_perfil']) && file_exists($usuario['foto_perfil'])) {
            echo "<img class='pfpimg' src='".$usuario['foto_perfil']."' width='100'>";
        }

        //echo "<img src='".$usuario['foto_perfil']."' width='100'>";
        //echo $usuario['foto_perfil'];
        ?>
    
    <h2>Ideias:</h2><br>
    <?php foreach ($posts as $p): ?>
        <?php if ($p['id_usuario'] == $id): ?>
            <div class="posts">
                <div class="userinfo">
                    <img src='<?= $p['foto_perfil'] ?>' class='pfpimgPost'>
                    <span class='card-user'><?= $p['nome'] ?></span>
                    <h2 class='card-title'><?= $p['titulo'] ?></h2>
                    <p class='card-text'><?= $p['descricao'] ?></p>
                    <h5 class='card-date'><?= date("d/m/Y H:i", strtotime($p['data'])) ?></h5>

                    <button onclick="votar(<?= $p['id'] ?>, 1)"><img width="15px" id="arrowup" src="imgs\arrow.webp"></button>
                        <span>
                            <span id="upvotes-<?= $p['id'] ?>"><?= $p['upvotes'] ?></span> 
                        </span> 
                        
                        <button onclick="votar(<?= $p['id'] ?>, -1)"><img width="15px" id="arrowdown" src="imgs\arrowd.jpg"></button>
                        <span>
                            <span id="downvotes-<?= $p['id'] ?>"><?= $p['downvotes'] ?></span>
                        </span>
                </div>

                <?php foreach ($conexao->query("SELECT nome FROM imagens WHERE id_post = " . $p['id']) as $img): ?>
                    <img src='uploads/<?= $img['nome'] ?>' width='200'>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>



    
</body>
</html>