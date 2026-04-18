<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="Ecadastro.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" href="imgs/bulbi.png" type="image/x-icon">
</head>
<body>
    <button id="bVoltar" onclick="location.href='home.php'">Voltar</button>

   
    <!-- o atributo form action define para onde (qual URL ou script) os dados de um formulário serão enviados quando o usuário clicar no botão de envio (submit).-->

    <form action="testLogin.php" method="post">
            <div id="cadastroBox">
            <h1>Login</h1>
            <input name="email" type="text" placeholder="Insira o E-mail"><br><br>
            <input name="senha" type="password" placeholder="Insira a Senha">
            <br><br><br>
            <input class="inputSubmit" type="submit" name="submit" value="Enviar"></input>
        </div>
    </form>
</body>
</html>