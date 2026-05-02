<?php
session_start();
include "config.php";

$id_post = $_GET['id']; // Obtém o ID do usuário a partir da URL

$sql = "SELECT * FROM postagens WHERE id = $id_post"; // Consulta SQL para obter os dados do usuário
$result = $conexao->query($sql); // Executa a consulta
$row = $result->fetch_assoc(); // Obtém os dados da postagem

$postador_id = $row['id_usuario']; // Obtém o ID do usuário que fez a postagem
$sql_postador = "SELECT * FROM usuarios WHERE id = $postador_id"; // Consulta SQL para obter os dados do usuário que fez a postagem
$result_postador = $conexao->query($sql_postador); // Executa a consulta
$row_postador = $result_postador->fetch_assoc(); // Obtém os dados do usuário que fez a postagem

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
    <title>Document</title>
</head>
<body>
    <button onclick="location.href='sistema.php'">Voltar para o feed</button>
    <br>
    <img src="<?= $row_postador['foto_perfil'] ?>" alt="Foto de perfil" width="50px">
    <h1><?= $row_postador['nome'] ?></h1>
    <h1><?= $row['titulo'] ?></h1>
    <p><?= $row['descricao'] ?></p>
    <?php 
    $sqlimg = "SELECT * FROM imagens WHERE id_post = $id_post"; // Consulta SQL para obter as imagens associadas à postagem
    $resultimg = $conexao->query($sqlimg); // Executa a consulta

    if ($resultimg->num_rows > 0) { // Verifica se há imagens associadas à postagem
        while($rowimg = $resultimg->fetch_assoc()) { // Loop para exibir cada imagem
            echo "<img src='uploads/" . $rowimg['nome'] . "' alt='Imagem da postagem'>"; // Exibe a imagem
        }
    }
    ?>

      <?php
        $comentarios = [];

        $sql = "SELECT comentarios.*, usuarios.nome, usuarios.foto_perfil 
                FROM comentarios
                JOIN usuarios ON comentarios.usuario_id = usuarios.id
                WHERE post_id = {$row['id']}
                ORDER BY data ASC";

        $resultcoment = $conexao->query($sql);

        while ($row = $resultcoment->fetch_assoc()) {
            $parent = $row['parent_id'] ?? 0;
            $comentarios[$parent][] = $row;
        }
        ?>

        <br>
                <input id="comentario-<?= $row['id'] ?>" placeholder="Deixe um comentário..."> 
                <button onclick="postarComentario(<?= $row['id'] ?>)">Postar Comentário</button> 
                <br>

                <div class="comentarios" id="comentarios-<?= $row['id'] ?>">
                    <?php mostrarComentarios(0, $comentarios); ?>
                </div>  
</body>
</html>