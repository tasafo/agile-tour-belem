<?php
session_start();

require_once '../util/constantes.php';
?>
<html>
    <head>
        <title>Login</title>
    </head>
    <body onload="document.permissao.password.focus();">
    <?php
    if (!$_POST['password'] || $_POST['password'] != SENHA_ADMIN ) {
        unset($_SESSION['permissaoAdmin']);
    ?>
        <center>
            <br><br>
            <form name="permissao" method="post" action="index.php">
                Senha: <input type="password" name="password" size="15"/><br><br>
                <input type="submit" name="acessar" value="acessar" />
            </form>
        </center>
    <?php
    } else {
        $_SESSION['permissaoAdmin'] = 'ok';
    ?>
        <script>window.location='menu.php'</script>
    <?php
    }
    ?>
    </body>
</html>