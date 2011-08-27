<!DOCTYPE html>
<?php
$niveis = "../../";

require_once '../general/autoload.php';
require_once $niveis . 'topo.php';

$o_tipo_inscricao = new TipoInscricaoDAO();
$a_tipo_inscricao = $o_tipo_inscricao->busca("status = 'A'");

if ($a_tipo_inscricoes) {
	$id_tipo_inscricao = $a_tipo_inscricao[0]->id;
	$valor_inscricao = $a_tipo_inscricao[0]->valor;
}
?>
<html lang="pt-br">
	<head>
		<meta charset="utf-8">
		<title>Inscrição Individual</title>
		<script type="text/javascript" src="js/jquery/jquery.js"></script>
		<script type="text/javascript" src="js/jquery/jquery.validate.js"></script>
		<script type="text/javascript" src="js/validacao.js"></script>
		<script type="text/javascript" src="js/individual.js"></script>
		<link href="css/validacao.css" rel="stylesheet" />
		<link href="css/estilo.css" rel="stylesheet" />
	</head>
    <body>
		<b>Inscrição Individual</b>
		<br><br>
		<div class="container">
		    <ol></ol>
		</div>
		<br>
		<form class="cmxform" id="form" name="formIndividual" action="" method="post">
			<input type="hidden" name="id_tipo_inscricao" id="id_tipo_inscricao" value="<?php echo $id_tipo_inscricao ?>" />
			<table class="bordasimples">
				<tr align="center">
					<td align="left" width="40%">Valor</td>
					<td align="left" width="60%">
					    <input type="text" readonly="readonly" name="valor_inscricao" id="valor_inscricao" maxlength="8" size="8" value="R$ <?php echo Funcoes::formata_moeda_para_exibir($valor_inscricao) ?>" />
					</td>
				</tr>
				<tr>
					<td align="left">Nome</td>
					<td align="left">
					    <input type="text" name="nome" id="nome" maxlength="60" size="35" />
					</td>
				</tr>
				<tr>
					<td align="left">E-mail</td>
					<td align="left">
					    <input type="text" name="email" id="email" maxlength="45" size="35" />
					</td>
				</tr>
				<tr>
					<td align="left">Instituição</td>
					<td align="left">
					    <input type="text" name="instituicao" id="instituicao" maxlength="100" size="35" />
					</td>
				</tr>
				<tr>
					<td align="left">Sexo</td>
					<td align="left">
					    <select name="sexo" id="sexo">
						   <option value="M">Masculino</otion>
						   <option value="F">Feminino</option>
					    </select>
					</td>
				</tr>
				<tr>
					<td align="left">CEP</td>
					<td align="left">
					   <input type="text" name="cep" id="cep" maxlength="8" size="9" onkeypress="mascara(this,apenasNumeros);" />
					   ( somente números )
					</td>
				</tr>
				<tr>
					<td colspan="2"><br><b>Obs.: todos os campos são obrigatórios</b></td>
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
