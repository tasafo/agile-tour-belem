<?php
require 'validaSessao.php';
require_once '../general/autoload.php';
require_once '../util/constantes.php';

$o_inscricao = new InscricaoDAO();

$a_inscritos_empresas = $o_inscricao->selecionar_inscritos_empresas();

if (!$a_inscritos_empresas) {
	echo '<center><h3><a href="menu.php">Voltar ao Menu</a></h3><br>';
	die("<h2>Nenhuma inscri&ccedil;&atilde;o encontrada</h2></center>");
}
?>
<html>
    <head>
        <title>Inscritos por Empresa</title>
        <script type="text/javascript" src="../view/js/jquery/jquery.js" ></script>
        <script type="text/javascript" src="../view/js/jquery/jquery.alerts/jquery.alerts.js" ></script>
        <script type="text/javascript" src="../view/js/validacao.js" ></script>
        <script type="text/javascript" src="js/relatorioEmpresas.js" ></script>
        <link type="text/css" href="../view/js/jquery/jquery.alerts/jquery.alerts.css" rel="stylesheet" />
    </head>
    <body>
        <h3><center><a href="menu.php">Voltar ao Menu</a></center></h3>
        <h2><center>Inscritos por Empresa</center></h2>
        <table width="100%" border="1">
            <tr style="font-weight: bold">
                <td align="center">Inscri&ccedil;&atilde;o</td>
                <td align="center">Data Inscri&ccedil;&atilde;o</td>
                <td>Nome</td>
                <td>E-mail</td>
                <td>Inscrito como</td>
                <td align="right">Valor</td>
                <td align="center">Data Pagamento</td>
                <td>Cortesia?</td>
                <td align="center">Pagamento</td>
                <td align="center">Funcion&aacute;rios</td>
            </tr>
            <?php
            $contador = 0;
            $contadorEmpresa = 0;
            $contadorConfirmados = 0;
            $contadorEmAberto = 0;
            
            $idEmpresa = 0;

            $valorInscricaoEmpresa = 0;
            $valorInscricaoConfirmados = 0;
            $valorInscricaoEmAberto = 0;
            $valorInscricaoTotal = 0;
            
            foreach ($a_inscritos_empresas as $inscricao) {
                $contador++;
                $idInscricao = $inscricao->id_inscricao;

                if ($idEmpresa != $inscricao->id_empresa) {
                    if ($idEmpresa != 0) { // Imprimir o valor total de inscricoes da empresa

            ?>
            <tr style="font-weight: bold; color: maroon">
                <td colspan="5">[ <?php echo $contadorEmpresa ?> ] inscrito(s) da Empresa</td>
                <td align="right"><?php echo Funcoes::formata_moeda_para_exibir($valorInscricaoEmpresa) ?></td>
            </tr>
            <?php
                    }
                    
                    $idEmpresa = $inscricao->id_empresa;
                    $valorInscricaoEmpresa = 0;
                    $contadorEmpresa = 0;

                    if (empty($inscricao->data_pagamento)) {
                        $dataPagamento = "<input type='text' size=10 maxlength=10 name='dtPagamento' id='data_$idEmpresa' onkeypress='mascara(this,data);' onblur='validaData(this);' />";
                        $confirmar = "<input type='button' name='confirmar' id='confirmar' value='Confirmar' onclick='confirmaPagamento($idEmpresa)' />";
                        $cortesia = "<input type='checkbox' name='cortesia' id='cortesia_$idEmpresa' value='N' onclick='marcaCortesia($idEmpresa)' />";
                    } else {
                        $dataPagamento = Funcoes::formata_data_para_exibir($inscricao->data_pagamento);
                        $confirmar = "&nbsp;";
                        $cortesia = "&nbsp;";
                    }
            ?>
            <tr style="font-weight: bold; color: navy">
                <td align="center"><?php echo $idEmpresa ?></td>
                <td>&nbsp;</td>
                <td><span id="nome_<?php echo $idEmpresa ?>"><?php echo Funcoes::remove_acentos($inscricao->nome_fantasia) ?></span></td>
                <td>
                    E-mail: <span id="email_<?php echo $idEmpresa ?>"><?php echo $inscricao->email_empresa ?></span><br>
                    Resp.: <?php echo Funcoes::remove_acentos($inscricao->nome_responsavel) ?><br>
                    Fone: <?php echo $inscricao->telefone ?><br>
                </td>
                <td><span style="color: red" id="salvando_<?php echo $idEmpresa ?>"></span></td>
                <td>&nbsp;</td>
                <td align="center"><div id="div_data_pagamento_<?php echo $idEmpresa ?>"><?php echo $dataPagamento ?></div></td>
                <td align="center"><div id="div_cortesia_<?php echo $idEmpresa ?>"><?php echo $cortesia ?></div></td>
                <td align="center"><div id="div_botao_<?php echo $idEmpresa ?>"><?php echo $confirmar ?></div></td>
                <td align="center"><input type='button' name='adicionar' id='adicionar' value='Adicionar' onclick="window.location='addEmployee.php?id=<?php echo $idEmpresa ?>'"/></td>
            </tr>
            <?php
                }
            ?>
            <tr>
                <td align="center"><?php echo $idInscricao ?></td>
                <td align="center"><?php echo Funcoes::formata_data_para_exibir($inscricao->data_registro) ?></td>
                <td><?php echo Funcoes::remove_acentos($inscricao->nome) ?></td>
                <td><?php echo $inscricao->email ?></td>
                <td><?php echo $inscricao->descricao_tipo_inscricao ?></td>
                <td align="right"><?php echo Funcoes::formata_moeda_para_exibir($inscricao->valor) ?></td>
            </tr>
            <?php
                $valorInscricaoEmpresa += $inscricao->valor;
                $valorInscricaoTotal += $inscricao->valor;
                $contadorEmpresa++;

                if (empty($inscricao->data_pagamento)) {
                    $contadorEmAberto++;
                    $valorInscricaoEmAberto += $inscricao->valor;
                } else {
                    $contadorConfirmados++;
                    $valorInscricaoConfirmados += $inscricao->valor;
                }
            }
            ?>
            <tr style="font-weight: bold; color: maroon">
                <td colspan="5">[ <?php echo $contadorEmpresa ?> ] inscrito(s) da Empresa</td>
                <td align="right"><?php echo Funcoes::formata_moeda_para_exibir($valorInscricaoEmpresa) ?></td>
            </tr>
            <tr style="font-weight: bold; color: red">
                <td colspan="5">Valor de [ <?php echo $contadorEmAberto ?> ] inscrito(s) em aberto</td>
                <td align="right"><?php echo Funcoes::formata_moeda_para_exibir($valorInscricaoEmAberto) ?></td>
            </tr>
            <tr style="font-weight: bold; color: blue">
                <td colspan="5">Valor de [ <?php echo $contadorConfirmados ?> ] inscrito(s) confirmados</td>
                <td align="right"><?php echo Funcoes::formata_moeda_para_exibir($valorInscricaoConfirmados) ?></td>
            </tr>
            <tr style="font-weight: bold; color: green">
                <td colspan="5">Valor de [ <?php echo $contador ?> ] inscrito(s) no total</td>
                <td align="right"><?php echo Funcoes::formata_moeda_para_exibir($valorInscricaoTotal) ?></td>
            </tr>
        </table>
    </body>
</html>