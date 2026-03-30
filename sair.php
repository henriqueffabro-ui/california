<?php
    session_start();

    //botão de encerrar sessão, unset destrói as variáveis e o header redireciona para a página de login
    unset($_SESSION['email']);
    unset($_SESSION['senha']);
    header('Location: login.php');
?>