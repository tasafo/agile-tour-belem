<?php
require 'validaSessao.php';
require_once '../general/autoload.php';
require_once '../util/constantes.php';

$o_inscricao = new InscricaoDAO();

$a_inscritos_individual = $o_inscricao->selecionar_inscritos_individual();

if (!$a_inscritos_individual) {
	echo '<center><h3><a href="menu.php">Voltar ao Menu</a></h3><br>';
	die("<h2>Nenhuma inscri&ccedil;&atilde;o encontrada</h2></center>");
}
?>
<html>
    <head>
        <title>Inscritos Individualmente</title>
        <script type="text/javascript" src="../view/js/jquery/jquery.js" ></script>
        <script type="text/javascript" src="../view/js/jquery/jquery.alerts/jquery.alerts.js" ></script>
        <script type="text/javascript" src="../view/js/validacao.js" ></script>
        <script type="text/javascript" src="js/relatorioIndividual.js" ></script>
        <link type="text/css" href="../view/js/jquery/jquery.alerts/jquery.alerts.css" rel="stylesheet" />
    </head>
    <body>
        <h3><center><a href="menu.php">Voltar ao Menu</a></center></h3>
        <h2><center>Inscritos Individualmente</center></h2>
        <table width="100%" border="1">
            <tr style="font-weight: bold">
                <td align="center">Inscri&ccedil;&atilde;o</td>
                <td align="center">Data Inscri&ccedil;&atilde;o</td>
                <td>Nome</td>
                <td>E-mail</td>
                <td>Empresa</td>
                <td>Inscrito como</td>
                <td align="right">Valor</td>
                <td align="center">Data Pagamento</td>
                <td>Cortesia?</td>
                <td align="center">Pagto.</td>
                <td align="center">Cancelar</td>
            </tr>
            <?php
            $contador = 0;
            $contadorConfirmados = 0;
            $contadorEmAberto = 0;
            $valorInscricaoConfirmados = 0;
            $valorInscricaoEmAberto = 0;
            $valorInscricaoTotal = 0;
            
            foreach ($a_inscritos_individual as $individual) {
                $contador++;
                $idIndividual = $individual->id_individual;
                $idInscricao = $individual->id_inscricao;
                $nome = $individual->nome;
                $valorInscricaoTotal += $individual->valor;

                if (empty($individual->data_pagamento)) {
                    $contadorEmAberto++;
                    $valorInscricaoEmAberto += $individual->valor;

                    $dataPagamento = "<input type='text' size=10 maxlength=10 name='dtPagamento' id='data_$idInscricao' onkeypress='mascara(this,data);' onblur='validaData(this);' />";
                    $cortesia = "<input type='checkbox' name='cortesia' id='cortesia_$idInscricao' value='N' onclick='marcaCortesia($idInscricao)' />";
                    $confirmar = "<input type='button' name='confirmar' id='confirmar' value='Confirmar' onclick='confirmaPagamento($idInscricao)' />";
                    $cancelar = "<input type='button' name='cancelar' id='cancelar' value='Cancelar' onclick='confirmaCancelamento($idIndividual)' />";
                } else {
                    $contadorConfirmados++;
                    $valorInscricaoConfirmados += $individual->valor;

                    $dataPagamento = Funcoes::formata_data_para_exibir($individual->data_pagamento);
                    $cortesia = "&nbsp;";
                    $confirmar = "&nbsp;";
                    $cancelar = "&nbsp;";
                }
            ?>
            <tr>
                <td align="center"><?php echo $idInscricao ?></td>
                <td align="center"><?php echo Funcoes::formata_data_para_exibir($individual->data_registro) ?></td>
                <td><span id="nome_<?php echo $idInscricao ?>"><?php echo Funcoes::remove_acentos($nome) ?></span></td>
                <td><span id="email_<?php echo $idInscricao ?>"><?php echo $individual->email ?></span></td>
                <td><?php echo Funcoes::remove_acentos($individual->empresa) ?></td>
                <td><?php echo $individual->descricao_tipo_inscricao ?></td>
                <td align="right"><?php echo Funcoes::formata_moeda_para_exibir($individual->valor) ?></td>
                <td align="center"><div id="div_data_pagamento_<?php echo $idInscricao ?>"><?php echo $dataPagamento ?></div></td>
                <td align="center"><div id="div_cortesia_<?php echo $idInscricao ?>"><?php echo $cortesia ?></div></td>
                <td align="center"><div id="div_botao_<?php echo $idInscricao ?>"><?php echo $confirmar ?></div><span style="color: red" id="gravando_<?php echo $idInscricao ?>"></span></td>
                <td align="center"><div id="div_cancelar_<?php echo $idIndividual ?>"><?php echo $cancelar ?></div><span style="color: red" id="cancelando_<?php echo $idIndividual ?>"></span></td>
            </tr>
            <?php
            }
            ?>
            <tr style="font-weight: bold; color: red">
                <td colspan="6">Valor de [ <?php echo $contadorEmAberto ?> ] inscrito(s) em aberto</td>
                <td align="right"><?php echo Funcoes::formata_moeda_para_exibir($valorInscricaoEmAberto) ?></td>
                <td colspan="4">&nbsp;</td>
            </tr>
            <tr style="font-weight: bold; color: blue">
                <td colspan="6">Valor de [ <?php echo $contadorConfirmados ?> ] inscrito(s) confirmados</td>
                <td align="right"><?php echo Funcoes::formata_moeda_para_exibir($valorInscricaoConfirmados) ?></td>
                <td colspan="4">&nbsp;</td>
            </tr>
            <tr style="font-weight: bold; color: green">
                <td colspan="6">Valor de [ <?php echo $contador ?> ] inscrito(s) no total</td>
                <td align="right"><?php echo Funcoes::formata_moeda_para_exibir($valorInscricaoTotal) ?></td>
                <td colspan="4">&nbsp;</td>
            </tr>
        </table>
    </body>
</html>