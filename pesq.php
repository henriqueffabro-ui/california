<?php

session_start();
include "config.php";

$pesquisa = $_GET['pesquisa'] ?? ''; //pega o termo de pesquisa enviado pelo formulário, ou uma string vazia se não houver

$sql = "SELECT postagens.*, usuarios.nome, usuarios.foto_perfil FROM postagens 
        JOIN usuarios ON postagens.id_usuario = usuarios.id 
        WHERE postagens.titulo LIKE '%$pesquisa%' OR postagens.descricao LIKE '%$pesquisa%'"; //consulta SQL para buscar postagens que tenham o termo de pesquisa no título ou na descrição, e também traz o nome e a foto de perfil do usuário que fez a postagem
$posts = $conexao->query($sql);


?>