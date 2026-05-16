<?php

include 'config.php';

$id = $_GET['id'];

$sql = "
SELECT 
    usuarios.id,
    usuarios.nome,
    usuarios.foto_perfil
FROM seguidores
JOIN usuarios 
    ON seguidores.id_seguidor = usuarios.id
WHERE seguidores.id_seguido = '$id'
";
$seguidores = $conexao->query($sql);




?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="Esistema.css">
</head>
<body>

    <button onclick="window.location.href='perfil.php?id=<?= $id ?>'">Voltar ao Perfil</button>
    <h1>Seguidores</h1>

    
    <?php
      
       if ($seguidores->num_rows > 0){
            
            foreach ($seguidores as $seguidor) {

            //esse <a> é o link para o perfil do seguidor, passando o id do seguidor e o id do perfil que está sendo visualizado (para o botão de voltar funcionar)
                echo "<a class='link-perfil' href='perfil.php?id=" . $seguidor['id'] . "&perfil_id=" . $id . "'>";
                echo "<p><img class='pfpimgPost' src='" . $seguidor['foto_perfil'] . "' alt='Foto de perfil' width='50' height='50'> " . $seguidor['nome'] . "</p>";
                echo "</a>";
            }
           
        } else {
            echo "<p>Este usuário não tem seguidores.</p>";
        }
    ?>

</body>
</html>