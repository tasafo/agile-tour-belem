<?php
require 'validaSessao.php';
require_once '../general/autoload.php';

$o_inscricao = new InscricaoDAO();

$a_inscritos = $o_inscricao->selecionar_inscritos_individual(true, "ind.instituicao");

if (!$a_inscritos) {
    echo '<center><h3><a href="menu.php">Voltar ao Menu</a></h3><br>';
    die("<h2>Nenhuma inscri&ccedil;&atilde;o encontrada</h2></center>");
}
?>
<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
        <title>Manutenção de Inscritos</title>
        <script type="text/javascript" src="../view/js/jquery/jquery.js" ></script>
        <script type="text/javascript" src="../view/js/jquery/jquery.alerts/jquery.alerts.js" ></script>
        <script type="text/javascript" src="js/manutencaoInscritos.js" ></script>
        <link href="../view/js/jquery/jquery.alerts/jquery.alerts.css" rel="stylesheet" />
        <link href="css/admin.css" rel="stylesheet" />
    </head>
    <body>
        <center>
            <h3><a href="menu.php">Voltar ao Menu</a></h3>
            <h2>Manutenção de Inscritos</h2>
        </center>
        <form id="form" action="post" action="">
            <b>Novo nome para a instituição:</b> <input type="text" id="novo_nome" name="novo_nome" size="30" maxlength="50" />
            <input type='button' name='mudar' id='mudar' value='Mudar' />
            <br><br>
            <table width="100%" border="1" class="bordasimples">
                <tr style="font-weight: bold">
                    <td>&nbsp;</td>
                    <td align="center">Id</td>
                    <td>Instituição</td>
                    <td>Nome</td>
                    <td>E-mail</td>
                </tr>
                <?php
                foreach ($a_inscritos as $inscritos) {
                ?>
                <tr>
                    <td align="center"><input type='checkbox' name='id[]' value='<?php echo $inscritos->id_individual ?>' /></td>
                    <td align="center"><?php echo $inscritos->id_individual ?></td>
                    <td><?php echo utf8_encode($inscritos->instituicao) ?></td>
                    <td><?php echo utf8_encode($inscritos->nome) ?></td>
                    <td><?php echo $inscritos->email ?></td>
                <?php
                }
                ?>
            </table>
        </form>
    </body>
</html>