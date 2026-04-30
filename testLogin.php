<?php

//sessão
session_start();
print_r($_SESSION); //teste para ver se a sessão está funcionando

//Aqui, vamos ver se o email e senha inseridos no login existem no banco de dados. Caso existam, o usuário faz login

//print_r para ver se os dados estão sendo recebidos corretamente do formulário de login.php
    //print_r($_POST['email']);
    //print_r($_POST['senha']);

    //Caso exista uma variável de submit, ou seja, caso o botão de submit do formulário de login.php seja clicado e as variáveis email e senha não estejam vazias, vamos acessar o banco de dados para verificar se o email e senha inseridos existem.
    if(isset($_POST['submit']) && !empty($_POST['email']) && !empty($_POST['senha'])){
        //Acessa a conexão com o banco de dados de dados e as vriáveis recebem seus valores inseridos no login.php
        include_once('config.php');
        $email = $_POST['email'];
        $senha = $_POST['senha'];
        $nome = $_POST['nome'];

        //Verificar se está tudo correto
        //print_r($email);
        //print_r($senha);

        //Verifica se os parametros email e senha digitados no login existem no banco de dados
        $sql = "SELECT * FROM usuarios WHERE email = '$email' and senha = '$senha'";

        $result = $conexao->query($sql);

        //print_r($row); //teste para ver se a consulta SQL está correta

        //print_r($result); teste para ver se a consulta está funcionando

        if(mysqli_num_rows($result) < 1){

            unset($_SESSION['email']);
            unset($_SESSION['senha']);
            unset($_SESSION['nome']);
            //Senha incorreta. Redirecionar para a página de login.php (a página de login)
            header('Location: login.php');
        }
        else{
            $row = mysqli_fetch_assoc($result); //fetch_assoc() é um método que retorna a próxima linha de um conjunto de resultados como um array associativo. Ele é usado para acessar os dados retornados por uma consulta SQL.
            
            print_r($row);


            $_SESSION['nome'] = $row['nome']; //a variável nome recebe o valor do campo nome do banco de dados, ou seja, o nome do usuário que fez login
        
            $_SESSION['email'] = $email;
            $_SESSION['senha'] = $senha;
            $_SESSION['id'] = $row['id'];

            
           
            //header para redirecionar para a página do sistema.php, ou seja, login efetuado com sucesso
            header('Location: sistema.php');
        }
    }
    else{
    //Não acessar. Redirecionar para a página de login.php (a página de login)
    header('Location: login.php');
    }
?>