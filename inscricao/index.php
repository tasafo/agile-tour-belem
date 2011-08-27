<?php
require_once 'general/autoload.php';
$niveis = "../";
require_once $niveis . 'topo.php';

$o_tipo_inscricao = new TipoInscricaoDAO();
$a_tipo_inscricoes = $o_tipo_inscricao->busca("status = 'A'");

$tabela = "";
foreach ($a_tipo_inscricoes as $tipo_inscricao) {
    $tabela .= '
	    <tr>
	        <td scope="row" class="css_td">' . $tipo_inscricao->descricao . '</td>
	        <td colspan="2" class="css_td">R$ ' . Funcoes::formata_moeda_para_exibir($tipo_inscricao->valor) . '</td>
	    </tr>';
}
?>
<html>
    <head>
        <title>Formul&aacute;rio de Inscri&ccedil;&atilde;o</title>
    </head>
    <body>
        <font color="#7eabd6" face="Trebuchet MS, Arial, Helvetica, sans-serif" size="3"><b>Inscri&ccedil;&otilde;es</b></font>
        <br>
        <p align="justify">
            <font face="Tahoma, Geneva, sans-serif" size="2">Para realizar sua inscri&ccedil;&atilde;o selecione uma das categorias abaixo.</font>
        </p>
        <table width="100%">
	       <tr>
		       <td>
		           <a href="view/CadastrarInscricaoIndividual.php">
		               <font face="Tahoma, Geneva, sans-serif" size="3">
		                   <b>Inscri&ccedil;&atilde;o Individual</b>
		               </font>
		           </a>
		       </td>
	       </tr>
	       <tr>
		      <td>
		          <font face="Tahoma, Geneva, sans-serif" size="2">
		              Selecione esta op&ccedil;&atilde;o caso voc&ecirc; esteja fazendo sua inscri&ccedil;&atilde;o de forma avulsa, ou seja,
		              o pagamento ser&aacute; efetuado por voc&ecirc; e n&atilde;o pela empresa que voc&ecirc; trabalha.
		          </font>
		      </td>
		   </tr>
	       <tr>
		      <td height="20"></td>
		   </tr>
	       <tr>
		      <td>
		          <a href="view/CadastrarInscricaoEmpresa.php">
		              <font	face="Tahoma, Geneva, sans-serif" size="3">
		                  <b>Inscri&ccedil;&atilde;o por Empresa</b>
		              </font>
		          </a>
		      </td>
		   </tr>
	       <tr>
			  <td>
			      <font face="Tahoma, Geneva, sans-serif" size="2">
			          Selecione esta op&ccedil;&atilde;o para efetuar m&uacute;ltiplas inscri&ccedil;&otilde;es por empresa/institui&ccedil;&atilde;o.
			          O cadastramento de informa&ccedil;&otilde;es da empresa/institui&ccedil;&atilde;o &eacute; obrigat&oacute;rio.
			      </font>
			  </td>
		   </tr>
        </table>
        
        <br>
        <font color="#7eabd6" face="Trebuchet MS, Arial, Helvetica, sans-serif" size="3">
            <b>Investimento</b>
        </font>
        <br>
        <br>
        
        <table border="0" class="css_table">
	       <tr>
		      <th scope="row" class="css_th">Categoria</th>
		      <th class="css_th">Investimento</th>
	       </tr>
           <?php echo $tabela ?>
        </table>
        <?php require_once $niveis . 'rodape.php'; ?>
    </body>
</html>
