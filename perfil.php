<?php

session_start();
include "config.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    //echo "ID do usuário: " . $id; // Debug: Exibe o ID do usuário
} else {
    $id = $_SESSION['id'];
    
    
}

if (isset($_GET['post_id'])) {
    echo "<button onclick=\"location.href='postinteiro.php?id=" . $_GET['post_id'] . "'\">Voltar para a postagem</button><br>";
   
}

echo "<button onclick=\"location.href='sistema.php'\">Voltar para o feed</button><br>";
echo "<br>";

$sql = "SELECT * FROM usuarios WHERE id = $id";
$result = mysqli_query($conexao, $sql);
$usuario = mysqli_fetch_assoc($result);

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

           


<div class="acoes-post">
    
    <label class="icon-btn">
        +
        <input type="file" multiple accept="image/*" hidden>
    </label>

    <button type="button" class="icon-btn" onclick="abrirEmoji()">😊</button>
    <input type="text" id="campoTexto">

</div>

<div class="posts">
    <a href='postinteiro.php?id=<?= $p['id'] ?>&perfil_id=<?= $id ?>' class='link-post'></a>
    <div class="userinfo">
        <img src='<?= $p['foto_perfil'] ?>' class='pfpimgPost'>
        <span class='card-user'><?= $p['nome'] ?></span>
        <h2 class='card-title'><?= $p['titulo'] ?></h2>
        <p class='card-text'><?= $p['descricao'] ?></p>
        <h5 class='card-date'><?= date("d/m/Y H:i", strtotime($p['data'])) ?></h5>

        <button class="votaraqui" onclick="votar(<?= $p['id'] ?>, 1)">
            <img width="15px" src="imgs/arrow.webp">
        </button>
        
        <span>
             <!--isset = caso exista um valor para os votes, mostre tal valor. Caso contrário, mostre 0-->
            <span id="upvotes-<?= $p['id'] ?>"><?= isset($p['upvotes']) ? $p['upvotes'] : 0 ?></span> 
        </span> 

        <button class="votaraqui" onclick="votar(<?= $p['id'] ?>, -1)">
            <img width="15px" src="imgs/arrowd.jpg">
        </button>
        <span>
            <!--isset = caso exista um valor para os votes, mostre tal valor. Caso contrário, mostre 0-->
            <span id="downvotes-<?= $p['id'] ?>"><?= isset($p['downvotes']) ? $p['downvotes'] : 0 ?></span>
        </span>
    </div>

    <?php foreach ($conexao->query("SELECT nome FROM imagens WHERE id_post = " . $p['id']) as $img): ?>
        <img src='uploads/<?= $img['nome'] ?>' width='200'>
    <?php endforeach; ?>
    <br>
    <input id="comentario-<?= $p['id'] ?>" placeholder="Deixe um comentário..."> 
    <button onclick="postarComentario(<?= $p['id'] ?>)">Postar Comentário</button> 
    <br>
    <?php
       $result = $conexao->query(" SELECT c.comentario, c.data, c.id, c.upvotes, c.downvotes, c.parent_id, u.nome, u.foto_perfil, c.usuario_id 
                        FROM comentarios c 
                        JOIN usuarios u 
                        ON c.usuario_id = u.id 
                        WHERE c.post_id = {$p['id']} 
                        ORDER BY c.upvotes DESC 
                        LIMIT 3"); //limita a exibição a 3 comentários, ordenados pelos mais votados. Os demais comentários podem ser vistos na página do post inteiro
                        
                        $result_total = $conexao->query(" SELECT c.comentario, c.data, c.id, c.upvotes, c.downvotes, c.parent_id, u.nome, u.foto_perfil 
                        FROM comentarios c
                        JOIN usuarios u 
                        ON c.usuario_id = u.id 
                        WHERE c.post_id = {$p['id']} 
                        ORDER BY c.upvotes DESC"); //query para obter o total de comentários

                       
                        

                        $comentarios = [];

                        while ($c = $result->fetch_assoc()) {
                            $comentarios[$c['parent_id']][] = $c;
                        }
                        
                         mostrarComentarios(NULL, $comentarios);

                          if ($result_total->num_rows > 3) { //verifica se há mais de 3 comentários para mostrar o botão de ver mais
                            //$comentarios = [];

                            //while ($c = $result->fetch_assoc()) {
                              //  $comentarios[$c['parent_id']][] = $c;
                            //}
                            
                             //mostrarComentarios(NULL, $comentarios);

                            echo "<br>";
                            //echo "<button class='ver-mais' onclick='carregarMais({$row['id']}, 3)'>Ver mais comentários</button>"; //botão para carregar mais comentários, começa a partir do offset 3, ou seja, a partir do 4º comentário
                            echo "<a class='vermaiscoment'href='postinteiro.php?id={$p['id']}'>Mais Comentários</a>"; //botão para carregar mais comentários, redireciona para a página do post inteiro onde todos os comentários são exibidos
                            //echo "<button class='ver-mais' onclick='location.href=\"postinteiro.php?id={$row['id']}\"'>Ver mais comentários</button>"; //botão para carregar mais comentários, redireciona para a página do post inteiro onde todos os comentários são exibidos
                        } if ($result_total->num_rows == 0) {
                            echo "<p>Seja o primeiro a comentar!</p>"; //caso não haja comentários, mostra essa mensagem
                        }
        ?>
    

    <div class="comentarios" id="comentarios-<?= $p['id'] ?>">
        <?php mostrarComentarios(0, $comentarios); ?>
    </div>  
</div>
        <?php endif; ?>
    <?php endforeach; ?>


</body>
</html>