<?php

session_start();
include_once('config.php'); //conecta com o arquivo que conecta com o banco de dados

$result = mysqli_query($conexao, "DELETE FROM comentarios WHERE id=" . $_GET['id']); //deleta a postagem do banco de dados com base no id passado pela URL
header('Location: sistema.php?comentario=excluido'); //redireciona para a página de sistema.php e adiciona um parâmetro na URL para indicar que a postagem foi excluída
?>
