<!DOCTYPE html>
<?php
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
		<title>Inscrição Individual</title>
		<script type="text/javascript" src="js/jquery/jquery.js"></script>
		<script type="text/javascript" src="js/jquery/jquery.validate.js"></script>
		<script type="text/javascript" src="js/validacao.js"></script>
		<script type="text/javascript" src="js/individual.js"></script>
		<link href="css/validacao.css" rel="stylesheet" />
		<link href="css/estilo.css" rel="stylesheet" />
       
        <style type="text/css">
			body {  color:#596b3a; }
			.titulo { font-size:18px; color:#88a459 !important;}
			.caixa {
			    -moz-border-radius: 3px 3px 3px 3px;
			    border: 1px solid #d5e2bf;
				padding: 3px;
			}
			
			.caixa:focus{  
				-moz-border-radius: 3px 3px 3px 3px;
				border: 1px solid #a3b494;
			}
			
			.submit {
				background: none repeat scroll 0 0 #7c945f;
				cursor:pointer;
				color: #FFFFFF;
				font: bold 12px Arial,Sans-serif;
				height: 30px;
				margin: 0;
				padding: 5px;
				text-transform: uppercase;
				width: 135px;
				border: 1px solid #546d38;
				-moz-border-radius: 3px 3px 3px 3px;
				filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#95aa7b', endColorstr='#657f47'); /* IE */
				background: -webkit-gradient(linear, left top, left bottom, from(#95aa7b), to(#657f47)); /* webkit browsers */
				background: -moz-linear-gradient(top,  #95aa7b,  #657f47); /* Firefox 3.6+ */
			}
			
			.submit:hover {
				background: none repeat scroll 0 0 #6b854d;
			}
		</style>
	</head>
    <body>
		<b class="titulo">Inscrição Individual</b>
		<br><br>
		<div class="container">
		    <ol></ol>
		</div>
		<form class="cmxform" id="form" name="formIndividual" action="" method="post">
			<input type="hidden" name="id_tipo_inscricao" id="id_tipo_inscricao" value="<?php echo $id_tipo_inscricao ?>" />
			<table class="bordasimples">
				<tr align="center">
					<td align="left" width="40%">Valor</td>
					<td align="left" width="60%">
					    <input type="text" readonly="readonly" class="caixa" name="valor_inscricao" id="valor_inscricao" size="10" value="R$  <?php echo Funcoes::formata_moeda_para_exibir($valor_inscricao) ?>" />
					</td>
				</tr>
				<tr>
					<td align="left">Nome</td>
					<td align="left">
					    <input type="text" name="nome" class="caixa" id="nome" maxlength="60" size="35" />
				    </td>
				</tr>
				<tr>
					<td align="left">E-mail</td>
					<td align="left">
					    <input type="text" name="email" class="caixa" id="email" maxlength="45" size="35" />	
					</td>
				</tr>
				<tr>
					<td align="left">Instituição</td>
					<td align="left">
					    <input type="text" name="instituicao" class="caixa" id="instituicao" maxlength="100" size="35" />
					</td>
				</tr>
				<tr>
					<td align="left">Sexo</td>
					<td align="left">
					    <select name="sexo" id="sexo" class="caixa">
						   <option value="M">Masculino</otion>
						   <option value="F">Feminino</option>
					    </select>
					</td>
				</tr>
				<tr>
					<td align="left">CEP</td>
					<td align="left">
					   <input type="text" name="cep" id="cep" class="caixa" maxlength="8" size="9" onKeyPress="mascara(this,apenasNumeros);" /> ( somente números )
					</td>
				</tr>
				<tr>
					<td colspan="2"><br><b>Obs.: todos os campos são obrigatórios</b></td>
				</tr>
				<tr>
				  <td colspan="2" align="center">&nbsp;</td>
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
