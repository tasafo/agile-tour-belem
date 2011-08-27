<?php
$niveis = "../../";

require_once '../general/autoload.php';
require_once '../util/constantes.php';
require_once '../util/pagseguro/pgs.php';
require_once $niveis . 'topo.php';

if (intval($_REQUEST['id']) == 0)
	die("<center><h2>Informa&ccedil;&otilde;es incorretas</h2></center>");

$o_empresa = new EmpresaDAO();
$o_individual = new IndividualDAO();
$o_endereco = new EnderecoDAO();
$o_inscricao = new InscricaoDAO();
$o_tipo_inscricao = new TipoInscricaoDAO();

if (!$o_empresa->busca($_REQUEST['id']))
	die("<center><h2>Informa&ccedil;&otilde;es incorretas</h2></center>");

$a_funcionarios_inscritos = $o_inscricao->selecionar_funcionarios_inscritos($o_empresa->id);

if (!$a_funcionarios_inscritos)
	die("<center><h2>Informa&ccedil;&otilde;es incorretas</h2></center>");

if (!$o_endereco->busca($o_empresa->id_endereco))
    die("<center><h2>Informa&ccedil;&otilde;es incorretas</h2></center>");

// Criando um novo carrinho no pagseguro
// OBS.: na referencia da transacao sera colocado I(ndividual) e E(mpresa) antes do cpf
$pgs = new pgs(array(
  'email_cobranca' => EMAIL_COBRANCA,
  'tipo' => 'CP',
  'moeda' => 'BRL',
  'ref_transacao' => "E" . $o_empresa->cnpj
));

$pgs->cliente(array(
  'nome' => Funcoes::remove_acentos($o_empresa->nome_fantasia),
  'cep' => $o_endereco->cep,
  'end' => Funcoes::remove_acentos($o_endereco->endereco),
  'num' => $o_endereco->numero,
  'compl' => Funcoes::remove_acentos($o_endereco->complemento),
  'bairro' => Funcoes::remove_acentos($o_endereco->bairro),
  'cidade' => Funcoes::remove_acentos($o_endereco->cidade),
  'uf' => $o_endereco->uf,
  'pais' => 'BRA',
  'ddd' => $o_empresa->ddd,
  'tel' => $o_empresa->telefone,
  'email' => $o_empresa->email
));

// Adicionando os funcionarios no carrinho do PagSeguro
$a_carrinho = array();

if (count($a_funcionarios_inscritos) > 25) {
	$total_inscritos = 0;
	$valor_total_pagamento_unico = 0;
	
	foreach ($a_funcionarios_inscritos as $inscrito) {
		$valor_total_pagamento_unico += $inscrito->valor;
		$total_inscritos++;
	}

	$a_carrinho[] = array(
        "descricao" => "Inscricao Agile Tour 2011 - Pagamento Unico ($total_inscritos inscricoes tipo Empresa)",
        "valor" => $valor_total_pagamento_unico,
        "peso" => 0,
        "quantidade" => 1,
        "id" => $o_empresa->cnpj
	);
} else {
	foreach ($a_funcionarios_inscritos as $inscrito) {
		$a_carrinho[] = array(
            "descricao" => "Inscricao Agile Tour 2011 - " . trim(Funcoes::remove_acentos($inscrito->nome)),
            "valor" => $inscrito->valor,
            "peso" => 0,
            "quantidade" => 1,
            "id" => $inscrito->cpf
		);
	}
}

$pgs->adicionar($a_carrinho);
?>
<html>
<head>
</head>
<body>
<b>Inscri&ccedil;&atilde;o por Empresa</b>
<br><br>
<b><?php echo $o_empresa->nome_fantasia ?></b>,<br><br>
Para finalizar o processo de inscri&ccedil;&atilde;o, efetue o pagamento
da mesma clicando no bot&atilde;o abaixo.<br><br>
Assim que recebermos a notifica&ccedil;&atilde;o de seu pagamento voc&ecirc;
ser&aacute; comunicado(a) por email. <br><br>
O pagamento dever&aacute; ser realizado via PagSeguro.<br><br>
<center><br>
<?php
// Mostrando o botao de pagamento
$pgs->mostra();

require_once $niveis . 'rodape.php';
?>
</center>
</body>
</html>
