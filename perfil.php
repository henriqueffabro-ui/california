<?php

session_start();
include "config.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    //echo "ID do usuário: " . $id; // Debug: Exibe o ID do usuário
} else {
    $id = $_SESSION['id'];
    
    
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


 function mostrarComentarios($parent_id, $comentarios, $nivel = 0) {

                if (!isset($comentarios[$parent_id])) return; //se nao tiver respostas, não executa o código

                foreach ($comentarios[$parent_id] as $c) { //percorre todos os comentários com tal parent id

                    $espaco = $nivel * -5; //isso calcula a margem adicionada a cada resposta. Quanto mais px, maior a margem

                    echo "<div style='margin-left:{$espaco}px'>";

                    echo "<br>";
                    echo "<div class='userinfo'>";
                    echo "<img src='" . $c['foto_perfil'] . "' alt='Foto de perfil' class='pfpimgComent'>";
                    echo "<span class='nome_comentario'>{$c['nome']}</span>";
                    echo "</div>";

                    echo "<div id='comentario-{$c['id']}'>";
                    echo "<p class='comentario'>{$c['comentario']}</p>";
                    echo "</div>";

                    echo "<p class='comentario-data'>{$c['data']}</p>";

                // Upvote
                echo "<button class='upvotarcoment' onclick='votarcoment({$c['id']}, 1)'>
                    <img width='15px' src='imgs/arrow.webp'>
                </button>";

                echo "<span id='upvotescoment-{$c['id']}'>" . ($c['upvotes'] ?? 0) . "</span>";

                // Downvote
                echo "<button class='downvotarcoment' onclick='votarcoment({$c['id']}, -1)'>
                    <img width='15px' src='imgs/arrowd.jpg'>
                </button>";

                echo "<span id='downvotescoment-{$c['id']}'>" . ($c['downvotes'] ?? 0) . "</span>";

                echo "<br>";

                // Input respostas
                echo "<input id='resposta-{$c['id']}' placeholder='Responda...'>";
                echo "<button onclick='postarResposta({$c['id']})'>Postar</button>";

                echo "<br>";

                // container respostas
                echo "<div id='Aparecerresposta-{$c['id']}' class='respostas'>";

                // recursão, chama a função novamente para fazer respostas de respostas
                mostrarComentarios($c['id'], $comentarios, $nivel + 1);

                echo "</div>";

                echo "</div>";

            }
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
    <!-- <button onclick="seguir(<?= $usuario['id'] ?>, this)">Seguir</button> -->
     <?php
     echo "<span class='bSeguir" . $usuario['id'] . "'>";
                                if ($usuario['id'] != $_SESSION['id']) {
                                    // Verifica se o usuário logado já segue o autor da postagem
                                    $id_usuario_logado = $_SESSION['id'];
                                    $id_usuario_autor = $usuario['id'];
                                    $seguindo = $conexao->query("SELECT * FROM seguidores WHERE id_seguidor = $id_usuario_logado AND id_seguido = $id_usuario_autor")->num_rows > 0;

                                    if ($seguindo) {
                                        echo "<button class='bSeguindo' onclick='seguir(" . $usuario['id'] . ", this)'>Seguindo</button>";
                                    } else {
                                        echo "<button class='bSeguir' onclick='seguir(" . $usuario['id'] . ", this)'>Seguir</button>";
                                    }
                                }
                            echo "</span>";
        ?>
    <form id="formPfp" method="POST" enctype="multipart/form-data">
    <input type="file" id="filePfp" style="display: none;"
       name="inputPfp"
       onchange="document.getElementById('formPfp').submit();"> <!-- esse fica escondigo, e aciona a função mostrarImg. Esse input é acionado pelo button-->

    <?php
    if ($id == $_SESSION['id']) { // Verifica se o usuário logado é o dono do perfil
        echo '<button type="button" onclick="document.getElementById(\'filePfp\').click()">Alterar foto de perfil</button>';
    }
    ?>    
    </form>
        <?php
        
        if (!empty($usuario['foto_perfil']) && file_exists($usuario['foto_perfil'])) {
            echo "<img class='pfpimg' src='".$usuario['foto_perfil']."' width='100'>";
        }

        //echo "<img src='".$usuario['foto_perfil']."' width='100'>";
        //echo $usuario['foto_perfil'];
        ?>
    
    <span id="seguidores-<?= $usuario['id'] ?>">Seguidores: <?= $usuario['seguidores'] ?? 0 ?></span>
    <h2>Ideias:</h2><br>
    <?php foreach ($posts as $p): ?>
        <?php
        
            
            if ($p['id_usuario'] == $id): ?>

             <?php
        $comentarios = [];

        $sql = "SELECT comentarios.*, usuarios.nome, usuarios.foto_perfil 
                FROM comentarios
                JOIN usuarios ON comentarios.usuario_id = usuarios.id
                WHERE post_id = {$p['id']}
                ORDER BY data ASC";

        $resultcoment = $conexao->query($sql);

        while ($row = $resultcoment->fetch_assoc()) {
            $parent = $row['parent_id'] ?? 0;
            $comentarios[$parent][] = $row;
        }
        ?>


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
                
                <br>
                <input id="comentario-<?= $p['id'] ?>" placeholder="Deixe um comentário..."> 
                <button onclick="postarComentario(<?= $p['id'] ?>)">Postar Comentário</button> 
                <br>

                <div class="comentarios" id="comentarios-<?= $p['id'] ?>">
                    <?php mostrarComentarios(0, $comentarios); ?>
                </div>  

                
            </div>
        <?php endif; ?>
    <?php endforeach; ?>



    
</body>
</html>