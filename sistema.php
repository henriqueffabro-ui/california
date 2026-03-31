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
                        echo "<button id='bUpvotes' class='btn btn-success'>Upvotes: " . $row["upvotes"] . "</button>"; //mostra o número de upvotes da postagem
                        echo "<button id='bDownvotes' class='btn btn-danger'>Downvotes: " . $row["downvotes"] . "</button>"; //mostra o número de downvotes da postagem
                        
                        echo"<br>"; //quebra de linha
                        $id_post = $row['id'];

                        $sql_img = "SELECT * FROM imagens WHERE id_post = $id_post";
                        $result_img = $conexao->query($sql_img);

                        while($img = $result_img->fetch_assoc()){
                        echo "<img src='uploads/".$img['nome']."' width='200' style='margin-left: 10px;'>"; //mostra as imgs da postagem

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
    <script>

        
        



        function mostrarImg(){ //ao clicar no botão de img, aciona o input, que por sua vez aciona essa função
            
                

            const input = document.getElementById('fileInput'); //pega o elemento input

            //const nome = input.files[0]?.name || "Nenhum arquivo selecionado"; mostra o nome da img na tela, 
            //document.getElementById('nomeArquivo').innerText = nome;           mas pode ser util no banco de dados para salvar o nome da img, caso queira fazer isso futuramente

            const imgpreview = document.getElementById('imgpreview'); //pega a div onde ficam as imgs na tela

            

            for(let i = 0; i < input.files.length; i++){ //percorre todas as imgs
                const arquivo = input.files[i]; //pega a img atual, de acordo com o índice do for
            
                if(arquivo.type.startsWith("image/")){ //verifica se o arquivo é img

                    const img = document.createElement("img"); //cria uma tag img
                    img.src = URL.createObjectURL(arquivo); //insere no src da img o caminho da img atual
                    img.style.display = "block"; //ativa a exibição da img
                    img.style.width = "200px"; 
                    img.style.height = "auto"; //esses 2 são tamanho da img

                    img.style.marginRight = "10px"; //essa é a margem, a img vai para a direita

                    imgpreview.appendChild(img); //appendChild insere a img dentro da div imgpreview, e mostra tudo na tela
                }
            }
            
        }

        function editarPostagem(id) {
            window.location.href = 'editarPostagem.php?id=' + id;
        }

        //function excluirPostagem(id) {
        //    window.location.href = 'excluirPostagem.php?id=' + id;
        //}

        function excluirPostagem(id) {
            document.getElementById('confirmBox').style.display = 'flex';
            document.getElementById('confirmBox').dataset.id = id; // Armazena o ID da postagem a ser excluída
        }

        function confirmar() {
           var id = document.getElementById('confirmBox').dataset.id; // Recupera o ID da postagem a ser excluída
           window.location.href = 'excluirPostagem.php?id=' + id;
        }

        function cancelar() {
            document.getElementById('confirmBox').style.display = 'none'; // Esconde a caixa de confirmação
        }

    </script>
</body>
</html>
