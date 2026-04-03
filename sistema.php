<?php
    //sessão
    session_start();
    include_once('config.php'); //conecta com o arquivo de que conecta com o banco de dados
    print_r($_SESSION);

    //print_r($_SESSION);

    //Se o usuário não estiver logado, redireciona para a página de login.php (a página de login).
    //Caso contrário, acessa o sistema.php
    if((!isset($_SESSION['email']) == true) and (!isset($_SESSION['senha']) == true)){
        
        unset($_SESSION['email']);
        unset($_SESSION['senha']);
        header('Location: login.php');

    }
    else{
        $logado = $_SESSION['email'];
     
    }



    if(isset($_POST['submitPost'])){ //se o usuário clicar no botão de submit, o código dentro do if será executado


    $titulo = $_POST['titulo']; 
    $descricao = $_POST['descricao']; //variáveis que recebem os dados da postagem
    $id_usuario = $_SESSION['id']; //variável que recebe o nome do usuário logado para salvar o nome do usuário que fez a postagem no banco de dados
    
    
    $result = mysqli_query($conexao, "INSERT INTO postagens(titulo, descricao, id_usuario) VALUES ('$titulo','$descricao','$id_usuario')");
    // o result salva as informações no banco de dados


    $id_post = $conexao->insert_id;

    foreach($_FILES['imagem']['name'] as $key => $nomeimg){

    $tmp = $_FILES['imagem']['tmp_name'][$key];

    $novoNome = uniqid() . "." . pathinfo($nomeimg, PATHINFO_EXTENSION);

        move_uploaded_file($tmp, "uploads/" . $novoNome);

        // salva no banco (exemplo)
        $sql = "INSERT INTO imagens (nome, id_post)
                VALUES ('$novoNome','$id_post')";
        
        $conexao->query($sql);


    }

    header('Location: sistema.php?postagem=criada'); //redireciona para a página de sistema.php e adiciona um parâmetro na URL para indicar que a postagem foi criada
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
        <input id="bPostar" type="submit" name="submitPost" onclick="postar()"></input>

    </div>
    </form>

    <div id="imgpreview" style="display: flex; gap: 10px; flex-wrap: wrap;"></div> <!-- onde virão as imgs selecionadas -->

    <br>
    <div id="posts"></div>
    
    <div class="card" style="width: 18rem;">
        <div class="card-body" style="border: 3px solid #ccc; border-radius: 5px; padding: 10px; margin-bottom: 10px;">
            <?php
                if ($result->num_rows > 0) { //verifica se há postagens no banco de dados
                    while($row = $result->fetch_assoc()) { //enquanto houver postagens, o código dentro do while será executado
                        echo "<h5 class='card-title'>" . $row["titulo"] . "</h5>"; //mostra o título da postagem
                        echo "<p class='card-text'>" . $row["descricao"] . "</p>"; //mostra a descrição da postagem
                        echo "<h5 class='card-user'> Postado por: " . $row["nome"] . "</h5>";
                        echo "<h5 class='card-date'> Data: " . $row["data"] . "</h5>"; //mostra a data da postagem
                        ?>
                        
                        <button class="btn-upvote" data-id="<?php echo $row['id']; ?>">
                            
                            Upvotes: <span id="up-<?php echo $row['id']; ?>">
                                <?php echo $row["upvotes"] ?? 0; ?>
                            </span>
                        </button>
                        
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

                        echo "<hr>"; //adiciona uma linha para separar as postagens
                    }
                } else {
                    echo "Nenhuma postagem encontrada."; //caso não exista postagens
                }
               
            ?>
        </div>
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
