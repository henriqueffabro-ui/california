<?php
    //sessão
    session_start();
    include_once('config.php'); //conecta com o arquivo de que conecta com o banco de dados
    print_r($_SESSION);

    $result = $conexao->query("SELECT * FROM postagens");

    //print_r($_SESSION);

    //Se o usuário não estiver logado, redireciona para a página de login.php (a página de login).
    //Caso contrário, acessa o sistema.php
    if((!isset($_SESSION['email']) == true) and (!isset($_SESSION['senha']) == true)){
        
        unset($_SESSION['email']);
        unset($_SESSION['senha']);
        header('Location: login.php');
        exit;

    }
    else{
        $logado = $_SESSION['email'];
     
    }



   

$sql = "SELECT postagens.*, usuarios.nome 
        FROM postagens
        JOIN usuarios ON postagens.id_usuario = usuarios.id
        ORDER BY postagens.id DESC";//seleciona todas as postagens do banco de dados e ordena por id em ordem decrescente (as postagens mais recentes aparecem primeiro)
$result = $conexao->query($sql); //executa a consulta SQL e salva o resultado na variável $result
//print_r($result); // teste de funcionamento




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema</title>
    <link rel="stylesheet" href="Esistema.css">
</head>
<body>
    <h1>Boas-vindas, <?php echo $_SESSION['nome']; ?>!</h1><br>
    
    <!-- emi -->
    <head>
        <meta charset="UTF-8">
        <tittle> Isumagi </tittle>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <header class="topo">
        <h1> Isumagi </h1>
        <input type="text" id="pesquisa" placeholder="Pesquise ideias!">
        </header>
        <div class="container">
            <aside class="sidebar">
                <ul>
                    <li> <button class="botao1">Pág inicial</button> </li>
                    <li> <button class="botao2">Explorar</button> </li>
                    <li> <button class="botao3">Seguindo</button> </li>
                </ul>
            </aside>

            <main class="feed"></main>
            <aside class="sidebar-esquerda">
                <div class="card">
                    
                </div>
            </aside>
        </div>


        <div class="modal" id="modalPost">
            <div class="modal-content">
                <h2> Nova ideia </h2>
                <input id="titulo" type="text" placeholder="Seu Título aqui!">
                <textarea id="descricao" placeholder="Descrição"></textarea>
                <div class="modal-botoes">
                    <button class="botao4" onclick="criarPost()">Postar</button>
                    <button class="botao5" onclick="fecharModal()">Canelar</button>
                </div>
            </div>
        </div>

    <a href="sair.php">Sair</a>

    <form action="sistema.php" method="post" enctype="multipart/form-data">
    <div id="loginBox">
        <h1>Sugerir Ideia</h1>
        <input id="TituloPost" name="titulo" type="text" placeholder="Nome da Ideia"><br><br>
        <input id="DescPost" name="descricao" type="text" placeholder="Descrição da Ideia"><br><br>

        <input type="file" id="fileInput" style="display: none;" onchange="mostrarImg()" name="imagem[]" multiple> <!-- esse fica escondigo, e aciona a função mostrarImg. Esse input é acionado pelo button-->

        <button type="button" onclick="document.getElementById('fileInput').click()"> <!-- aciona o input escondigo -->
            Selecionar imagem
        </button>

        <br>
        
        <button type="button" id="bPostar" onclick="postar()">Postar</button>

    </div>
    </form>

    

    <div id="imgpreview" style="display: flex; gap: 10px; flex-wrap: wrap;"></div> <!-- onde virão as imgs selecionadas -->

    <br>
    <div id="posts"></div>
    
    <div class="card">
        <!--<div class="card-body" style="border: 3px solid #ccc; border-radius: 5px; padding: 10px; margin-bottom: 10px;">-->
            <?php
                if ($result->num_rows > 0) { //verifica se há postagens no banco de dados
                    while($row = $result->fetch_assoc()) { //enquanto houver postagens, o código dentro do while será executado
                        echo "<div class='posts'> ";
                        echo "<h5 class='card-title'>" . $row["titulo"] . "</h5>"; //mostra o título da postagem
                        echo "<p class='card-text'>" . $row["descricao"] . "</p>"; //mostra a descrição da postagem
                        echo "<h5 class='card-user'> Postado por: " . $row["nome"] . "</h5>";
                        echo "<h5 class='card-date'> Data: " . $row["data"] . "</h5>"; //mostra a data da postagem

                       
                        ?>
                          <button onclick="votar(<?= $row['id'] ?>, 1)"><img width="15px" id="arrowup" src="imgs\arrow.webp"></button>
                        <span>
                            <span id="upvotes-<?= $row['id'] ?>"><?= $row['upvotes'] ?></span> 
                        </span> 
                        
                        <button onclick="votar(<?= $row['id'] ?>, -1)"><img width="15px" id="arrowdown" src="imgs\arrowd.jpg"></button>
                        <span>
                            <span id="downvotes-<?= $row['id'] ?>"><?= $row['downvotes'] ?></span>
                        </span>
                        
                        <br>
                        <input id="comentario-<?= $row['id'] ?>" placeholder="Deixe um comentário..."> 
                        <button onclick="postarComentario(<?= $row['id'] ?>)">Postar Comentário</button> 
                        <br>
                        


                        <?php
                        echo"<br>"; //quebra de linha
                        $id_post = $row['id'];

                        $sql_img = "SELECT * FROM imagens WHERE id_post = $id_post";
                        $result_img = $conexao->query($sql_img);

                        $temImagem = false;

                        if ($result_img->num_rows > 0) { // verifica se há alguma nesse campo no banco de dados
                            while($img = $result_img->fetch_assoc()){ //percorre todas as imgs associadas à postagem
                                if (!empty($img['nome']) && file_exists("uploads/".$img['nome'])) { //verifica se realmente existe img
                                    echo "<img src='uploads/".$img['nome']."' width='200' style='margin-left: 10px;'>"; //coloca na tela
                                    $temImagem = true; //isso é só pra dar um <br> depois
                                }
                            }
                        }

                        if ($temImagem) {
                            echo "<br>";
                        }

                        echo "<br>";
                        if($row["nome"] == $_SESSION['nome']){ //verifica se o nome do usuário logado é igual ao nome do usuário que fez a postagem
                            echo "<button onclick='editarPostagem(" . $row["id"] . ")' id='BdeEditar' class='btn btn-primary'>Editar</button>"; //se for igual, mostra o botão de editar
                            echo "<button onclick='excluirPostagem(" . $row["id"] . ")' id='BdeExcluir' class='btn btn-danger'>Excluir</button>"; //se for igual, mostra o botão de excluir
                        }
                        if($row['editado'] == 1){
                            echo "<p>Editado</p>";
                        }

                        ?> 
                        <div class="comentarios" id="comentarios-<?= $row['id'] ?>">
                            
                        </div>
                        
                        <?php
                        //$comentarios = $conexao->query(" 
                             //  SELECT comentario, data, id, upvotes, downvotes FROM comentarios 
                            //WHERE post_id = {$row['id']}
                            //ORDER BY upvotes DESC
                        //");

                        // pega os comentários do post cujo id é igual ao id da postagem atual
                        $comentarios = $conexao->query(" 
                            SELECT c.comentario, c.data, c.id, c.upvotes, c.downvotes, u.nome FROM comentarios c
                            JOIN usuarios u ON c.usuario_id = u.id
                            WHERE c.post_id = {$row['id']}
                            AND c.parent_id IS NULL
                            ORDER BY c.upvotes DESC
                        ");

                        while ($c = $comentarios->fetch_assoc()) { //percorre todos os comentarios

                        // c é o apelido da tabela comentarios. r é o das respostas. u é de usuarios. O join é para pegar o nome do usuário que fez o comentário, associando o id do usuário da tabela comentarios com o id da tabela usuarios. O where é para pegar apenas os comentários que pertencem à postagem atual (c.post_id = id da postagem) e que não são respostas (c.parent_id IS NULL). O order by é para ordenar os comentários por número de upvotes, do maior para o menor.
                        $respostas = $conexao->query(" 
                            SELECT c.comentario, c.data, c.id, c.downvotes, c.upvotes, c.parent_id, u.nome
                            FROM comentarios c
                            JOIN usuarios u ON c.usuario_id = u.id
                            WHERE c.parent_id = {$c['id']}
                        ");
                            echo "<h6 class='nome_comentario'>{$c['nome']}</h6>"; //mostra o nome do usuário que fez o comentário
                            
                            echo "<div id='comentario-{$c['id']}'>";
                            echo "<p class='comentario'>{$c['comentario']}</p>"; //mostra o comentario 
                            echo "</div>";

                            echo "<p class='comentario-data'>{$c['data']}</p>"; //mostra a data do comentario
                            
                            

                                // BOTÃO UPVOTE
                            echo "<button type='button' class='upvotarcoment' onclick='votarcoment({$c['id']}, 1)'>
                                <img width='15px' src='imgs/arrow.webp'>
                            </button>";

                            // CONTADOR UPVOTE
                            echo "<span id='upvotescoment-{$c['id']}'>" . ($c['upvotes'] ?? 0) . "</span>";

                                // BOTÃO DOWNVOTE
                            echo "<button type='button' class='downvotarcoment' onclick='votarcoment({$c['id']}, -1)'>
                                <img width='15px' src='imgs/arrowd.jpg'>
                            </button>";

                            // CONTADOR DOWNVOTE
                            echo "<span id='downvotescoment-{$c['id']}'>" . ($c['downvotes'] ?? 0) . "</span>";

                            echo"<br>";
                            echo"<input id='resposta-{$c['id']}' placeholder='Responda...'>"; 
                            echo"<button onclick='postarResposta({$c['id']})'>Postar</button>";
                            echo"<br>";

                            echo "<div id='Aparecerresposta-{$c['id']}' class='respostas'>";

                                while ($r = $respostas->fetch_assoc()) {

                                    
                                    echo "</span>";

                                    echo "<h6 class='nome_resposta'>{$r['nome']}</h6>";
                                    //menção
                                    echo "<span class='resposta_texto'>";

                                    echo "<span class='nomePai_resposta'>
                                        <a href='#comentario-{$r['parent_id']}'>
                                            @{$r['nome']}
                                        </a>
                                    </span>";

                                    echo " {$r['comentario']}";
                                    //data
                                    echo "<p class='comentario-data'>{$r['data']}</p>"; //mostra a data da resposta

                                    

                                         // BOTÃO UPVOTE
                                echo "<button type='button' class='upvotarcoment' onclick='votarcoment({$r['id']}, 1)'>
                                    <img width='15px' src='imgs/arrow.webp'>
                                </button>";

                                // CONTADOR UPVOTE
                                echo "<span id='upvotescoment-{$r['id']}'>" . ($r['upvotes'] ?? 0) . "</span>";

                                // BOTÃO DOWNVOTE
                                echo "<button type='button' class='downvotarcoment' onclick='votarcoment({$r['id']}, -1)'>
                                    <img width='15px' src='imgs/arrowd.jpg'>
                                </button>";

                            // CONTADOR DOWNVOTE
                            echo "<span id='downvotescoment-{$r['id']}'>" . ($r['downvotes'] ?? 0) . "</span>";

                            echo"<br>";
                            echo"<input id='resposta-{$r['id']}' placeholder='Responda...'>"; 
                            echo"<button onclick='postarResposta({$r['id']})'>Postar</button>";
                            echo"<br>";
                            


                                    
                                
                                }
                            
                            echo "</div>";
                            
                            echo "<p style='color: #a9a9a9; font-size: 12px;'>--------------------------------------------------------------------------------------------------------</p>"; //separa os comentarios com uma linha
                                
                            //aqui vai o botão de responder o comentário
                                
                        
                                
                            }
                            

                        echo "</div>"; //adiciona uma linha para separar as postagens
                    }
                } else {
                    echo "Nenhuma postagem encontrada."; //caso não exista postagens
                }
               
            ?>
        <!--</div>-->
    </div>
    <div id="confirmBox" class="confirm-box">
    <div class="confirm-content">
        <p>Tem certeza que deseja excluir esta postagem?</p>
        <button onclick="confirmar()">Sim</button>
        <button onclick="cancelar()">Cancelar</button>
    </div>
    </div>

    
    <script src="sistema.js"></script>

        
       
</body>
</html>
