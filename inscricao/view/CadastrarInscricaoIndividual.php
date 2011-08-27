<?php
$niveis = "../../";

require_once '../general/autoload.php';
require_once $niveis . 'topo.php';

$o_tipo_inscricao = new TipoInscricaoDAO();
$a_tipo_inscricoes = $o_tipo_inscricao->busca("status = 'A'");
$a_estados = Funcoes::lista_estados();

$select = "";
foreach ($a_tipo_inscricoes as $tipo_inscricao) {
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
		<script type="text/javascript" src="js/individual.js"></script>
		<link type="text/css" href="css/validacao.css" rel="stylesheet" />
		<link type="text/css" href="css/estilo.css" rel="stylesheet" />
	</head>
    <body>
		<b>Inscri&ccedil;&atilde;o Individual</b>
		<br><br>
		<div class="container">
		    <ol></ol>
		</div>
		<br>
		<form class="cmxform" id="form" name="formIndividual" action="" method="post">
			<table class="bordasimples">
				<tr align="center">
					<td align="left" width="40%">Categoria*</td>
					<td align="left" width="60%">
					    <select name="categoria_inscricao" id="categoria_inscricao" style="width: 340px">
						<?php echo $select ?>
					    </select>
					</td>
				</tr>
				<tr>
					<td align="left">Nome*</td>
					<td align="left">
					    <input type="text" name="nome" id="nome" maxlength="60" size="35" />
					</td>
				</tr>
				<tr>
					<td align="left">CPF*</td>
					<td align="left">
					    <input type="text" name="cpf" id="cpf" maxlength="11" size="11" onkeypress="mascara(this,apenasNumeros);" />
					</td>
				</tr>
				<tr>
					<td align="left">Email*</td>
					<td align="left">
					    <input type="text" name="email" id="email" maxlength="45" size="35" />
					</td>
				</tr>
				<!--
				<tr>
					<td align="left">Senha *</td>
					<td align="left">
					    <input type="password" name="senha" id="senha" maxlength="20" size="20" />
					</td>
				</tr>
				<tr>
					<td align="left">Confirma&ccedil;&atilde;o*:</td>
					<td align="left">
					    <input type="password" name="confirmacao_senha" id="confirmacao_senha" maxlength="20" size="20" />
					</td>
				</tr>
			    -->
				<tr>
					<td align="left">Nome para Crach&aacute;*</td>
					<td align="left">
					    <input type="text" name="nome_cracha" id="nome_cracha" maxlength="60" size="35" />
					</td>
				</tr>
				<tr>
					<td align="left">DDD/Telefone*</td>
					<td align="left">
					    <input type="text" name="ddd" id="ddd" maxlength="2" size="3" onkeypress="mascara(this,apenasNumeros);" />
						<input type="text" name="telefone" id="telefone" maxlength="8" size="10" onkeypress="mascara(this,apenasNumeros);" />
				    </td>
				</tr>
				<tr>
					<td align="left">Institui&ccedil;&atilde;o*</td>
					<td align="left">
					    <input type="text" name="empresa" id="empresa" maxlength="100" size="35" />
					</td>
				</tr>
				<tr>
					<td align="left">Sexo*</td>
					<td align="left">
					    <select name="sexo" id="sexo">
						   <option value="M">Masculino</otion>
						   <option value="F">Feminino</option>
					    </select>
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
				<tr>
					<td colspan="2"><b>*campos obrigat&oacute;rios</b></td>
				</tr>
				<tr>
					<td colspan="2" align="right">
					    <div id="div_botao_salvar">
					        <input type="button" id="gravar" value="Cadastrar" />
					    </div>
					    <div id="div_salvando"></div>
					</td>
				</tr>
			</table>
	   </form>
	   <?php require_once $niveis . 'rodape.php'; ?>
	</body>
</html>
