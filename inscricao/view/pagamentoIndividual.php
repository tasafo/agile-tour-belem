<?php
$niveis = "../../";

require_once '../general/autoload.php';
require_once '../util/constantes.php';
require_once '../util/pagseguro/pgs.php';
require_once $niveis . 'topo.php';

if (intval($_REQUEST['id']) == 0)
	die("<center><h2>Informa&ccedil;&otilde;es incorretas</h2></center>");

$o_individual = new IndividualDAO();
$o_endereco = new EnderecoDAO();
$o_inscricao = new InscricaoDAO();
$o_tipo_inscricao = new TipoInscricaoDAO();

if (!$o_individual->busca($_REQUEST['id']))
	die("<center><h2>Informa&ccedil;&otilde;es incorretas</h2></center>");

if (!$o_endereco->busca($o_individual->id_endereco))
    die("<center><h2>Informa&ccedil;&otilde;es incorretas</h2></center>");

if (!$o_inscricao->busca($o_individual->id_inscricao))
    die("<center><h2>Informa&ccedil;&otilde;es incorretas</h2></center>");

if (!$o_tipo_inscricao->busca($o_inscricao->id_tipo_inscricao))
    die("<center><h2>Informa&ccedil;&otilde;es incorretas</h2></center>");

// Criando um novo carrinho no pagseguro
// OBS.: na referencia da transacao sera colocado I(ndividual) e E(mpresa) antes do cpf
$pgs = new pgs(array(
  'email_cobranca' => EMAIL_COBRANCA,
  'tipo' => 'CP',
  'moeda' => 'BRL',
  'ref_transacao' => "I" . $o_individual->cpf
));

$pgs->cliente(array(
  'nome' => Funcoes::remove_acentos($o_individual->nome),
  'cep' => $o_endereco->cep,
  'end' => Funcoes::remove_acentos($o_endereco->endereco),
  'num' => $o_endereco->numero,
  'compl' => Funcoes::remove_acentos($o_endereco->complemento),
  'bairro' => Funcoes::remove_acentos($o_endereco->bairro),
  'cidade' => Funcoes::remove_acentos($o_endereco->cidade),
  'uf' => $o_endereco->uf,
  'pais' => 'BRA',
  'ddd' => $o_individual->ddd,
  'tel' => $o_individual->telefone,
  'email' => $o_individual->email
));

// Adicionando um produto
$pgs->adicionar(array(
array(
    "descricao" => "Inscricao Agile Tour 2011 - " . Funcoes::remove_acentos($o_tipo_inscricao->descricao),
    "valor" => $o_tipo_inscricao->valor,
    "peso" => 0,
    "quantidade" => 1,
    "id" => $o_tipo_inscricao->id
),
));

$tratamento = ($o_individual->sexo == "M") ? "Caro" : "Cara";
?>
<html>
<head>
</head>
<body>
<b>Inscri&ccedil;&atilde;o Individual</b>
<br><br>
<b><?php echo $tratamento . " " . $o_individual->nome ?></b>,<br><br>
Para finalizar o processo de inscri&ccedil;&atilde;o, efetue o pagamento
da mesma clicando no bot&atilde;o abaixo.<br><br>
Assim que recebermos a notifica&ccedil;&atilde;o de seu pagamento voc&ecirc;
ser&aacute; comunicado(a) por email.<br><br>
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
