<?php
    //sessão
    session_start();
    include_once('config.php'); //conecta com o arquivo de que conecta com o banco de dados
    

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




$pesquisando = isset($_GET['pesquisa']) && $_GET['pesquisa'] != "";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema</title>
    <link rel="stylesheet" href="Esistema.css">
    <link rel="icon" href="imgs/bulbi.png" type="image/x-icon">
</head>
<body>
    
    
    <!-- emi -->
    <head>
        <meta charset="UTF-8">
        <tittle> Isumagi </tittle>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <header class="topo">
        
        <h1> Isumagi </h1>
        <form id="formPesq" method="GET">

        <?php if ($pesquisando) { ?>
       
            <button id="bVoltarPesq" onclick="location.href='sistema.php'">Voltar</button>
            
            <form id="formFiltros" method="GET">
            <input type="hidden" name="pesquisa" value="<?= $_GET['pesquisa'] ?? '' ?>">
            <input id ="filtroUsuarios"type="checkbox" class="filtro" name="filtroUsuarios" <?= isset($_GET['filtroUsuarios']) ? 'checked' : '' ?> value="user">
            <label for="filtroUsuarios">Filtrar por usuários</label>
            <input id="filtroPostagens" type="checkbox" class="filtro" name="filtroPostagens" <?= isset($_GET['filtroPostagens']) ? 'checked' : '' ?> value="post">
            <label for="filtroPostagens">Filtrar por postagens</label>
            <button type="submit">Aplicar filtros</button>
            </form>

            
        <?php } 
        
        if (isset($_GET['filtroUsuarios'])) {
            $filtro = 'usuarios';
        } elseif (isset($_GET['filtroPostagens'])) {
            $filtro = 'postagens';
        } else {
            $filtro = 'todos';
        }
        ?>

            
            <input name="pesquisa" type="text" class="pesquisa" placeholder="Pesquise ideias!">
            <button type="submit">Buscar</button>
        </form>
        
        <a href='perfil.php?id=<?= $_SESSION['id'] ?>'>
            <button class="bNavbar" onclick="location.href='perfil.php'">Perfil</button>
        </a>
        </header>
        <br><br><br><br>
        
        <?php print_r($_SESSION); ?>
        <h1>Boas-vindas, <?php echo $_SESSION['nome']; ?>!</h1><br>
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


        <!--<div class="modal" id="modalPost">
            <div class="modal-content">
                <h2> Nova ideia </h2>
                <input id="titulo" type="text" placeholder="Seu Título aqui!">
                <textarea id="descricao" placeholder="Descrição"></textarea>
                <div class="modal-botoes">
                    <button class="botao4" onclick="criarPost()">Postar</button>
                    <button class="botao5" onclick="fecharModal()">Canelar</button>
                </div>
            </div>
        </div>-->

    <a href="sair.php">Sair</a>

    <form id="formPost" action="sistema.php" method="post" enctype="multipart/form-data">
    <div id="loginBox">
        <h1>Sugerir Ideia</h1>
        <input id="TituloPost" name="titulo" type="text" placeholder="Nome da Ideia"><br><br>
        <input id="DescPost" name="descricao" type="text" placeholder="Descrição da Ideia"><br><br>

        <input type="file" id="fileInput" style="display: none;" onchange="adicionarImagem()" name="imagem[]" multiple> <!-- esse fica escondigo, e aciona a função mostrarImg. Esse input é acionado pelo button-->

        <button type="button" onclick="document.getElementById('fileInput').click()"> <!-- aciona o input escondigo -->
            Selecionar imagem
        </button>

        <br>
        
        
        <button type="button" id="bPostar" onclick="postar()">Postar</button>

    </div>
    </form>

    
    <ul id="listaImgs"></ul>
    <br>
    <div id="imgpreview" style="display: flex; gap: 10px; flex-wrap: wrap;"></div> <!-- onde virão as imgs selecionadas -->

    <br>
    <div id="posts"></div>
    
    <div class="card">
        <!--<div class="card-body" style="border: 3px solid #ccc; border-radius: 5px; padding: 10px; margin-bottom: 10px;">-->
            <?php

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
                if (!empty($_GET['pesquisa'])) {

                    $pesquisa = $_GET['pesquisa'] ?? ''; //pega o termo de pesquisa enviado pelo formulário, ou uma string vazia se não houver


                    if($filtro == 'usuarios') {
                        
                        $posts = $conexao->query("
                            SELECT * 
                            FROM usuarios 
                            WHERE nome LIKE '%$pesquisa%' 
                        ");

                        if ($posts->num_rows == 0) {
                            echo "Nenhum usuario encontrado  com esse nome.";
                        } else {
                            while ($u = $posts->fetch_assoc()) {
                                echo "<div class='card'>";
                                echo "<a href='perfil.php?id=" . $u['id'] . "'>";
                                echo "<img src='" . $u['foto_perfil'] . "' alt='Foto de perfil' class='pfpimg'>";
                                echo "<span class='card-user'>" . $u['nome'] . "</span>";
                                echo "</a>";
                                echo "</div>";
                            }
                        }


                    }
                    
                    else{
                    $sql = "SELECT postagens.*, usuarios.nome, usuarios.foto_perfil FROM postagens 
                            JOIN usuarios ON postagens.id_usuario = usuarios.id 
                            WHERE postagens.titulo LIKE '%$pesquisa%' OR postagens.descricao LIKE '%$pesquisa%' OR usuarios.nome LIKE '%$pesquisa%'"; //consulta SQL para buscar postagens que tenham o termo de pesquisa no título ou na descrição, e também traz o nome e a foto de perfil do usuário que fez a postagem
                    $posts = $conexao->query($sql);

                    $sql_user = "SELECT * FROM usuarios WHERE usuarios.nome LIKE '%$pesquisa%'"; //consulta SQL para buscar usuários que tenham o termo de pesquisa no nome
                    $usuarios = $conexao->query($sql_user);
                    }
                    

                }
                else {
                $posts = $conexao->query("
                    SELECT p.*, u.nome, u.foto_perfil 
                    FROM postagens p
                    JOIN usuarios u ON p.id_usuario = u.id
                    ORDER BY p.data DESC
                ");
                }
                if ($posts->num_rows > 0) { //verifica se há postagens no banco de dados
                    while($row = $posts->fetch_assoc()) { //enquanto houver postagens, o código dentro do while será executado
                        
                        
                        echo "<a href='postinteiro.php?id=" . $row['id'] . "' class='link-post'>";
                       
                        echo "<div class='posts'> ";
                        
                        echo "<div class='userinfo'>";
                            echo "<a href='perfil.php?id=" . $row['id_usuario'] . "'>";
                                echo "<img src='" . $row["foto_perfil"] . "' alt='Foto de perfil' class='pfpimgPost'>";
                                echo "<span class='card-user'>" . $row["nome"] . "</span>";
                               
                            echo "</a>";
                            echo "<span class='bSeguir" . $row['id_usuario'] . "'>";
                                if ($row['id_usuario'] != $_SESSION['id']) {
                                    // Verifica se o usuário logado já segue o autor da postagem
                                    $id_usuario_logado = $_SESSION['id'];
                                    $id_usuario_autor = $row['id_usuario'];
                                    $seguindo = $conexao->query("SELECT * FROM seguidores WHERE id_seguidor = $id_usuario_logado AND id_seguido = $id_usuario_autor")->num_rows > 0;

                                    if ($seguindo) {
                                        echo "<button class='bSeguindo' onclick='seguir(" . $row['id_usuario'] . ", this)'>Seguindo</button>";
                                    } else {
                                        echo "<button class='bSeguir' onclick='seguir(" . $row['id_usuario'] . ", this)'>Seguir</button>";
                                    }
                                }
                            echo "</span>";
                        echo "</div>";
                        
                        echo "<h5 class='card-title'>" . $row["titulo"] . "</h5>"; //mostra o título da postagem
                        echo "<p class='card-text'>" . $row["descricao"] . "</p>"; //mostra a descrição da postagem
                        
                        echo "<h5 class='card-date'> Data: " . $row["data"] . "</h5>"; //mostra a data da postagem

                    
                        ?>
                          <button class="votaraqui" onclick="votar(<?= $row['id'] ?>, 1)"><img width="15px" id="arrowup" src="imgs\arrow.webp"></button>
                        <span>
                            <!--isset = caso exista um valor para os votes, mostre tal valor. Caso contrário, mostre 0-->
                            <span id="upvotes-<?= $row['id'] ?>"><?= isset($row['upvotes']) ? $row['upvotes'] : 0 ?></span> 
                        </span> 
                        
                        <button class="votaraqui" onclick="votar(<?= $row['id'] ?>, -1)"><img width="15px" id="arrowdown" src="imgs\arrowd.jpg"></button>
                        <span>
                            <span id="downvotes-<?= $row['id'] ?>"><?= isset($row['downvotes']) ? $row['downvotes'] : 0 ?></span>
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
                                    echo "<img class ='imgsnopost' src='uploads/".$img['nome']."' width='200' style='margin-left: 10px;'>"; //coloca na tela
                                    $temImagem = true; //isso é só pra dar um <br> depois
                                }
                            }
                        }

                        if ($temImagem) {
                            echo "<br>";
                        }

                        
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
                        $result = $conexao->query(" SELECT c.comentario, c.data, c.id, c.upvotes, c.downvotes, c.parent_id, u.nome, u.foto_perfil 
                        FROM comentarios c 
                        JOIN usuarios u 
                        ON c.usuario_id = u.id 
                        WHERE c.post_id = {$row['id']} 
                        ORDER BY c.upvotes DESC ");

                        $comentarios = [];

                        while ($c = $result->fetch_assoc()) {
                            $comentarios[$c['parent_id']][] = $c;
                        }
                        
                         mostrarComentarios(NULL, $comentarios);

                        

                        echo "</a>";
                        echo "</div>";
                        

                        echo"<br>";
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
