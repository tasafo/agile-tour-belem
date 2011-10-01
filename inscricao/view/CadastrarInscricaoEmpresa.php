<?php
session_start();

unset($_SESSION['Funcionarios']);

$niveis = "../../";

require_once '../general/autoload.php';
require_once '../util/constantes.php';
require_once $niveis . 'topo.php';

$o_inscricao = new InscricaoDAO();
$o_tipo_inscricao = new TipoInscricaoDAO();

$a_total_inscritos = $o_inscricao->valor_total_inscritos();
$a_tipo_inscricao = $o_tipo_inscricao->busca("status = 'A'");

if ($a_tipo_inscricao) {
    if (count($a_tipo_inscricao) == 1) {
        $id_tipo_inscricao = $a_tipo_inscricao[0]->id;
        $valor_inscricao = $a_tipo_inscricao[0]->valor;
    } else {
        $select_tipo_inscricao = "";
        foreach ($a_tipo_inscricao as $tipo_inscricao) {
            $select_tipo_inscricao .= "<option value='" . $tipo_inscricao->id . "'>" . $tipo_inscricao->descricao . " - R$ ". Funcoes::formata_moeda_para_exibir($tipo_inscricao->valor) . "</option>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
	<head>
		<meta charset="utf-8">
		<title>Inscrição por Empresa</title>
		<script type="text/javascript" src="js/jquery/jquery.js"></script>
		<script type="text/javascript" src="js/jquery/jquery.validate.js"></script>
		<script type="text/javascript" src="js/validacao.js"></script>
		<script type="text/javascript" src="js/empresa.js"></script>
		<link type="text/css" href="css/validacao.css" rel="stylesheet" />
		<link type="text/css" href="css/estilo.css" rel="stylesheet" />
	
        <style type="text/css">
			body { color:#89383f; }
			.titulo { font-size:18px; color:#bc6367 !important;}
			.caixa {
			    -moz-border-radius: 3px 3px 3px 3px;
			    border: 1px solid #e8d2d4;
				padding: 3px;
			}
			
			.caixa:focus{  
				-moz-border-radius: 3px 3px 3px 3px;
				border: 1px solid #d0afb1;
			}
			
			.submit {
				background: none repeat scroll 0 0 #ad5656;
				cursor:pointer;
				color: #FFFFFF;
				font: bold 12px Arial,Sans-serif;
				height: 30px;
				margin: 0;
				padding: 2px;
				text-transform: uppercase;
				width: 155px;
				border: 1px solid #6e3535;
				-moz-border-radius: 3px 3px 3px 3px;
				filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#b86969', endColorstr='#843f3f'); /* IE */
				background: -webkit-gradient(linear, left top, left bottom, from(#b86969), to(#843f3f)); /* webkit browsers */
				background: -moz-linear-gradient(top,  #b86969,  #843f3f); /* Firefox 3.6+ */
			}
			
			.submit:hover {
				background: none repeat scroll 0 0 #8d4444;
			}
		</style>
    </head>
	<body>
	    <?php if ($a_total_inscritos[0]->quantidade >= QTD_MAXIMA_INSCRITOS) { ?>
        <h1><?php die("As vagas foram preenchidas.<br><br>Inscrições encerradas.") ?></h1>
        <?php } ?>
		<b class="titulo">Inscrição por Empresa</b>
		<br><br>
		Para inscrição por empresa, por favor, siga as instruções abaixo.
		<ul>
			<li>Você deverá preencher os dados da
			instituição apenas uma vez;</li>
			<li>Você pode cadastrar quantos funcionários desejar, mas cada
			inserção será feita uma por vez, pressionando o
			botão <b>Adicionar Funcionário</b>;</li>
			<li>Após inserir todos os funcionários basta pressionar o
			botão <b>Realizar Inscrição</b> para concluir o
			cadastro dos dados da inscrição.</li>
		</ul>
		
		<div class="container" id="div_msg_empresa">
		    <ol></ol>
		</div>
		<br>
		<form class="cmxform" id="form" name="formEmpresa" action="" method="post">
			<table class="bordasimples" style="width: 450px">
				<tr>
					<td colspan="2" align="center"><b>Informações da Instituição</b></td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td align="left">Nome</td>
					<td align="left">
					   <input type="text" name="nome" id="nome" maxlength="60" class="caixa" size="35" />
					</td>
				</tr>
				<tr>
					<td align="left">Nome do Responsável</td>
					<td align="left">
					   <input type="text" name="responsavel" id="responsavel" class="caixa" maxlength="60" size="35" />
					</td>
				</tr>
				<tr>
					<td align="left">E-mail</td>
					<td align="left">
					   <input type="text" name="email" id="email" class="caixa" maxlength="45" size="35" />
					</td>
				</tr>
				<tr>
					<td align="left">CEP</td>
					<td align="left">
					   <input type="text" name="cep" id="cep" class="caixa" maxlength="8" size="9" onKeyPress="mascara(this,apenasNumeros);" />
					   ( somente números )
					</td>
				</tr>
			</table>
		</form>
		
		<form class="cmxform" id="frmFunc" name="formFuncionarios" action="" method="post">
			<table class="bordasimples" style="width: 450px">
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2" align="center">
					   <b>Informações dos Funcionários da Instituição</b>
					</td>
				</tr>
				<tr>
					<td colspan="2">  
                        <div class="container" id="div_msg_funcionario">
                            <ol></ol>
                        </div>
                    </td>
				</tr>
				<tr align="center">
				<?php if (count($a_tipo_inscricao) == 1) { ?>
					<td align="left" width="40%">Valor</td>
					<td align="left" width="60%">
                        <input type="hidden" name="func_id_tipo_inscricao" id="func_id_tipo_inscricao" value="<?php echo $id_tipo_inscricao ?>" />
					    <input type="text" readonly="readonly" class="caixa" name="valor_inscricao" id="valor_inscricao" size="10" value="R$ <?php echo Funcoes::formata_moeda_para_exibir($valor_inscricao) ?>" />
					</td>
                <?php } else { ?>
                    <td align="left" width="40%">Categoria</td>
                    <td align="left" width="60%">
                        <select name="func_id_tipo_inscricao" id="func_id_tipo_inscricao" style="width: 340px">
                        <?php echo $select_tipo_inscricao ?>
                        </select>
                    </td>
                <?php } ?>
				</tr>
				<tr>
					<td align="left">Nome</td>
					<td align="left">
					   <input type="text" name="func_nome" class="caixa" id="func_nome" maxlength="60" size="35" />
					</td>
				</tr>
				<tr>
					<td align="left">E-mail</td>
					<td align="left">
					   <input type="text" name="func_email" class="caixa" id="func_email"	maxlength="45" size="35" />
					</td>
				</tr>
				<tr>
					<td align="left">Sexo</td>
					<td align="left">
					   <select name="func_sexo" id="func_sexo" class="caixa">
					       <option value="M">Masculino</option>
						   <option value="F">Feminino</option>
					   </select>
					</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2" align="center">
					   <input type="button"	id="insere_funcionario" class="submit" value="Adicionar Funcionário" />
					</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2">
					   <div id="div_grade_funcionarios" style="display: none"></div>
					</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2" align="center">
					   <div id="div_botao_salvar">
					       <input type="button" id="gravar" class="submit" value="Realizar Inscrição" />
					   </div>
					   <div id="div_salvando"></div>
					</td>
				</tr>
			</table>
		</form>
    </body>
</html>
