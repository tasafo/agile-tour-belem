<?php
session_start();

unset($_SESSION['Funcionarios']);

$niveis = "../../";
require_once '../general/autoload.php';
require_once $niveis . 'topo.php';

$o_tipo_inscricao = new TipoInscricaoDAO();
$a_tipo_inscricao = $o_tipo_inscricao->busca("status = 'A'");
$a_estados = Funcoes::lista_estados();

$select = "";
foreach ($a_tipo_inscricao as $tipo_inscricao) {
	$select .= "<option value='" . $tipo_inscricao->id . "'>" . $tipo_inscricao->descricao . " - R$ ". Funcoes::formata_moeda_para_exibir($tipo_inscricao->valor) . "</option>";
}

$select_estados = "";
foreach ($a_estados as $sigla => $nome) {
	$selecionado = ($sigla == UF_PADRAO) ? " selected='selected'" : "";
	$select_estados .= "<option value='" . $sigla . "'" . $selecionado . ">" . $nome . "</option>";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<title>Formul&aacute;rio de Inscri&ccedil;&atilde;o</title>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
		<script type="text/javascript" src="js/jquery/jquery.js"></script>
		<script type="text/javascript" src="js/jquery/jquery.validate.js"></script>
		<script type="text/javascript" src="js/validacao.js"></script>
		<script type="text/javascript" src="js/empresa.js"></script>
		<link type="text/css" href="css/validacao.css" rel="stylesheet" />
		<link type="text/css" href="css/estilo.css" rel="stylesheet" />
	</head>
	<body>
		<b>Inscri&ccedil;&atilde;o por Empresa</b>
		<br><br>
		Para inscri&ccedil;&atilde;o por empresa, por favor, siga as instru&ccedil;&otilde;es abaixo.
		<ul>
			<li>Voc&ecirc; dever&aacute; preencher os dados da
			institui&ccedil;&atilde;o apenas uma vez;</li>
			<li>Voc&ecirc; pode cadastrar quantos funcionarios desejar, mas cada
			inser&ccedil;&atilde;o ser&aacute; feita uma por vez, pressionando o
			bot&atilde;o <b>Inserir Funcion&aacute;rio</b>;</li>
			<li>Ap&oacute;s inserir todos os funcion&aacute;rios basta pressionar o
			bot&atilde;o<b> Concluir Cadastro</b> para concluir o
			cadastro dos dados da inscri&ccedil;&atilde;o.</li>
		</ul>
		
		<div class="container" id="div_msg_empresa">
		    <ol></ol>
		</div>
		<br>
		
		<form class="cmxform" id="form" name="formEmpresa" action="" method="post">
			<table class="bordasimples" style="width: 450px">
				<tr>
					<td colspan="2" align="center"><b>Informa&ccedil;&otilde;es da Institui&ccedil;&atilde;o</b></td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td align="left" width="40%">Raz&atilde;o Social*</td>
					<td align="left" width="60%">
					   <input type="text" name="razao_social" id="razao_social" maxlength="60" size="35" />
					</td>
				</tr>
				<tr>
					<td align="left">Nome Fantasia*</td>
					<td align="left">
					   <input type="text" name="nome_fantasia" id="nome_fantasia" maxlength="60" size="35" />
					</td>
				</tr>
				<tr>
					<td align="left">Nome do Respons&aacute;vel*</td>
					<td align="left">
					   <input type="text" name="nome_responsavel" id="nome_responsavel" maxlength="60" size="35" />
					</td>
				</tr>
				<tr>
					<td align="left">CNPJ*</td>
					<td align="left">
					   <input type="text" name="cnpj" id="cnpj"	maxlength="14" size="15" onkeypress="mascara(this,apenasNumeros);" />
					</td>
				</tr>
				<tr>
					<td align="left">Email*</td>
					<td align="left">
					   <input type="text" name="email" id="email" maxlength="45" size="35" />
					</td>
				</tr>
				<tr>
					<td align="left">DDD/Telefone*</td>
					<td align="left">
					   <input type="text" name="ddd" id="ddd" maxlength="2" size="3" onkeypress="mascara(this,apenasNumeros);" />
					   <input type="text" name="telefone" id="telefone" maxlength="8" size="10"	onkeypress="mascara(this,apenasNumeros);" />
					</td>
				</tr>
				<tr>
					<td align="left">Endere&ccedil;o*</td>
					<td align="left">
					   <input type="text" name="endereco" id="endereco" maxlength="60" size="35" />
					</td>
				</tr>
				<tr>
					<td align="left">N&uacute;mero*</td>
					<td align="left">
					   <input type="text" name="numero" id="numero" maxlength="5" size="6" onkeypress="mascara(this,apenasNumeros);" />
					</td>
				</tr>
				<tr>
					<td align="left">Complemento</td>
					<td align="left">
					   <input type="text" name="complemento" id="complemento" maxlength="60" size="35" />
					</td>
				</tr>
				<tr>
					<td align="left">Bairro*</td>
					<td align="left">
					   <input type="text" name="bairro" id="bairro" maxlength="45" size="35" />
					</td>
				</tr>
				<tr>
					<td align="left">CEP*</td>
					<td align="left">
					   <input type="text" name="cep" id="cep" maxlength="8" size="9" onkeypress="mascara(this,apenasNumeros);" />
					</td>
				</tr>
				<tr>
					<td align="left">Cidade*</td>
					<td align="left">
					   <input type="text" name="cidade" id="cidade" maxlength="45" size="35" />
				    </td>
				</tr>
				<tr>
					<td>UF*</td>
					<td>
					   <select name="uf">
					   <?php echo $select_estados; ?>
					   </select>
					</td>
				</tr>
			</table>
		</form>
			
		<div class="container" id="div_msg_funcionario">
		    <ol></ol>
		</div>
		<form class="cmxform" id="frmFunc" name="formFuncionarios" action="" method="post">
			<table class="bordasimples" style="width: 450px">
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2" align="center">
					   <b>Informa&ccedil;&otilde;es dos Funcion&aacute;rios da Institui&ccedil;&atilde;o</b>
					</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td align="left" width="40%">Categoria*</td>
					<td align="left" width="60%">
					   <select name="func_categoria_inscricao" id="func_categoria_inscricao" style="width: 340px">
					   <?php echo $select ?>
					   </select>
					</td>
				</tr>
				<tr>
					<td align="left">Nome</td>
					<td align="left">
					   <input type="text" name="func_nome" id="func_nome" maxlength="60" size="35" />
					</td>
				</tr>
				<tr>
					<td align="left">CPF*</td>
					<td align="left">
					   <input type="text" name="func_cpf" id="func_cpf" maxlength="11" size="11" onkeypress="mascara(this,apenasNumeros);" />
					</td>
				</tr>
				<tr>
					<td align="left">Email*</td>
					<td align="left">
					   <input type="text" name="func_email" id="func_email"	maxlength="45" size="35" />
					</td>
				</tr>
				<tr>
					<td align="left">Nome no Crach&aacute;*</td>
					<td align="left">
					   <input type="text" name="func_nome_cracha" id="func_nome_cracha" maxlength="60" size="35" />
					</td>
				</tr>
				<tr>
					<td align="left">DDD/Telefone*</td>
					<td align="left">
					   <input type="text" name="func_ddd" id="func_ddd" maxlength="2" size="3" onkeypress="mascara(this,apenasNumeros);" />
					   <input type="text" name="func_telefone" id="func_telefone" maxlength="8" size="10" onkeypress="mascara(this,apenasNumeros);" />
					</td>
				</tr>
				<tr>
					<td align="left">Sexo*</td>
					<td align="left">
					   <select name="func_sexo" id="func_sexo">
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
					   <input type="button"	id="insere_funcionario" value="Inserir Funcion&aacute;rio" />
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
					       <input type="button" id="gravar" value="Concluir Cadastro" />
					   </div>
					   <div id="div_salvando"></div>
					</td>
				</tr>
			</table>
		</form>
		<?php require_once $niveis . 'rodape.php'; ?>
    </body>
</html>
