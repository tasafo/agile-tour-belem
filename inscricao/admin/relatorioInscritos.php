<?php
require 'validaSessao.php';
require_once '../general/autoload.php';

$o_inscricao = new InscricaoDAO();

$a_relacao_inscritos = $o_inscricao->selecionar_relacao_geral_inscritos();

if (!$a_relacao_inscritos) {
	echo '<center><h3><a href="menu.php">Voltar ao Menu</a></h3><br>';
	die("<h2>Nenhuma inscri&ccedil;&atilde;o encontrada</h2></center>");
}
?>
<html>
    <head>
        <title>Rela&ccedil;&atilde;o Geral de Inscritos</title>
        <style type="text/css" title="mystyles" media="all">
            table.bordasimples {border-collapse: collapse;}

            table.bordasimples tr td {border:1px solid #000000;}
        </style>
    </head>
    <body>
        <center><a href="menu.php">Voltar ao Menu</a></center>
        <br>
        <table width="100%" border="1" class="bordasimples">
            <tr style="font-weight: bold; text-align: center">
                <td colspan="4">Rela&ccedil;&atilde;o Geral de Inscritos</td>
            </tr>
            <tr style="font-weight: bold; text-align: center">
                <td width="5%" align="center">N.</td>
                <td width="35%">Nome</td>
                <td width="35%">Categoria</td>
                <td width="25%">Assinatura</td>
            </tr>
            <?php
            $contador = 1;

            foreach ($a_relacao_inscritos as $inscrito) {
                $nome = $inscrito->nome;
                $categoria = $inscrito->descricao_tipo_inscricao;
            ?>
            <tr>
                <td align="center"><?php echo $contador++ ?></td>
                <td><?php echo Funcoes::remove_acentos($nome) ?></td>
                <td><?php echo $categoria ?></td>
                <td>&nbsp;</td>
            </tr>
            <?php
            }
            ?>
        </table>
    </body>
</html>