<!DOCTYPE html>
<?php
session_start();

unset($_SESSION['Funcionarios']);

$niveis = "../../";
require_once '../general/autoload.php';
require_once $niveis . 'topo.php';

$o_tipo_inscricao = new TipoInscricaoDAO();
$a_tipo_inscricao = $o_tipo_inscricao->busca("status = 'A'");

if ($a_tipo_inscricao) {
	$id_tipo_inscricao = $a_tipo_inscricao[0]->id;
	$valor_inscricao = $a_tipo_inscricao[0]->valor;
}
?>
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
	</head>
	<body>
		<b>Inscrição por Empresa</b>
		<br><br>
		Para inscrição por empresa, por favor, siga as instruções abaixo.
		<ul>
			<li>Você deverá preencher os dados da
			instituição apenas uma vez;</li>
			<li>Você pode cadastrar quantos funcionarios desejar, mas cada
			inserçãoo será feita uma por vez, pressionando o
			botão <b>Inserir Funcionário</b>;</li>
			<li>Após inserir todos os funcionários basta pressionar o
			botão<b> Concluir Cadastro</b> para concluir o
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
					   <input type="text" name="nome" id="nome" maxlength="60" size="35" />
					</td>
				</tr>
				<tr>
					<td align="left">Nome do Responsável</td>
					<td align="left">
					   <input type="text" name="responsavel" id="responsavel" maxlength="60" size="35" />
					</td>
				</tr>
				<tr>
					<td align="left">E-mail</td>
					<td align="left">
					   <input type="text" name="email" id="email" maxlength="45" size="35" />
					</td>
				</tr>
				<tr>
					<td align="left">CEP</td>
					<td align="left">
					   <input type="text" name="cep" id="cep" maxlength="8" size="9" onkeypress="mascara(this,apenasNumeros);" />
					   ( somente números )
					</td>
				</tr>
			</table>
		</form>
			
		<div class="container" id="div_msg_funcionario">
		    <ol></ol>
		</div>
		<form class="cmxform" id="frmFunc" name="formFuncionarios" action="" method="post">
			<input type="hidden" name="func_id_tipo_inscricao" id="func_id_tipo_inscricao" value="<?php echo $id_tipo_inscricao ?>" />
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
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr align="center">
					<td align="left" width="40%">Valor</td>
					<td align="left" width="60%">
					    <input type="text" readonly="readonly" name="valor_inscricao" id="valor_inscricao" maxlength="8" size="8" value="R$ <?php echo Funcoes::formata_moeda_para_exibir($valor_inscricao) ?>" />
					</td>
				</tr>
				<tr>
					<td align="left">Nome</td>
					<td align="left">
					   <input type="text" name="func_nome" id="func_nome" maxlength="60" size="35" />
					</td>
				</tr>
				<tr>
					<td align="left">E-mail</td>
					<td align="left">
					   <input type="text" name="func_email" id="func_email"	maxlength="45" size="35" />
					</td>
				</tr>
				<tr>
					<td align="left">Sexo</td>
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
