<?php
if(isset($_POST['submit'])){ //se o usuário clicar no botão de submit, o código dentro do if será executado
    //print_r($_POST['nome']);
    //print_r($_POST['email']); testes de funcionamento
    //print_r($_POST['senha']);

    include_once('config.php'); //inclui o arquivo que faz conexão com o banco de dados

    $nome = $_POST['nome'];
    $email = $_POST['email']; //variáveis que recebem os dados do formulário
    $senha = $_POST['senha'];

    $result = mysqli_query($conexao, "INSERT INTO usuarios(nome,email,senha) VALUES ('$nome','$email','$senha')");
    // o result salva as informações no banco de dados

    header('Location: login.php'); //redireciona para a página de login.php 
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="Ecadastro.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="icon" href="imgs/bulbi.png" type="image/x-icon">
</head>
<body>
    <button id="bVoltar" onclick="location.href='home.php'">Voltar</button>

    <!-- o atributo form action define para onde (qual URL ou script) os dados de um formulário serão enviados quando o usuário clicar no botão de envio (submit).-->
    <form action="cadastro.php" method="post">
    <div id="cadastroBox">
        <h1>Cadastro</h1>
        <input id="UserNome" name="nome" type="text" placeholder="Insira o Nome de Usuário"><br><br>
        <input id="UserEmail" name="email" type="text" placeholder="Insira o E-mail"><br><br>
        <input id="UserSenha" name="senha" type="password" placeholder="Insira a Senha">
        <br><br><br>
        <input id="bCriarConta" type="submit" name="submit"></input>

        

    </div>
    </form>
</body>
</html>