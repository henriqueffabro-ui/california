<?php
    session_start();
    include_once('config.php'); //conecta com o arquivo que conecta com o banco de dados
    print_r($_SESSION);

    $id = $_GET['id'];

    $sql = "SELECT * FROM postagens WHERE id=$id";
    $result = $conexao->query($sql);
    $row = $result->fetch_assoc();

    if(isset($_POST['BotaoSalvar'])){ //se o usuário clicar no botão de submit, o código dentro do if será executado


    $titulo = $_POST['titulo']; 
    $descricao = $_POST['descricao']; //variáveis que recebem os dados da postagem
    $nome = $_SESSION['nome']; //variável que recebe o nome do usuário logado para salvar o nome do usuário que fez a postagem no banco de dados

    
    $result = mysqli_query($conexao, "UPDATE postagens SET titulo='$titulo', descricao='$descricao' WHERE id=$id");
    // o result salva as informações no banco de dados
    $editado = mysqli_query($conexao, "UPDATE postagens SET editado=1 WHERE id=$id"); //variável para indicar que a postagem foi editada
    $_SESSION['editado'] = 1;

    header('Location: sistema.php?postagem=editada'); //redireciona para a página de sistema.php e adiciona um parâmetro na URL para indicar que a postagem foi editada
    echo "Postagem editada com sucesso!"; //mensagem de sucesso
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
    <br>
    <form action="editarPostagem.php?id=<?php echo $id; ?>" method="post">
    <input type="text" name="titulo" value="<?php echo $row['titulo']; ?>" placeholder="Título da Postagem"><br><br>
    <input type="text" name="descricao" value="<?php echo $row['descricao']; ?>" placeholder="Descrição da Postagem"><br><br>
    <button id="bSalvar" name="BotaoSalvar">Salvar</button>
    <button type="button" onclick="cancelar()" id="bCancelar">Cancelar</button>
    </form>
    <script>
        //document.getElementById('bCancelar').addEventListener('click', function() {
          //  window.location.href = 'sistema.php';
        //});

        function cancelar() {
            window.location.href = 'sistema.php';
        }
        
    </script>
</body>
</html>