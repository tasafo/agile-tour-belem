<?php
require_once '../general/autoload.php';
require_once '../util/constantes.php';

header("Content-Type: application/xml; charset=utf-8");

$xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
$xml .= "<gravacao>\n";

$a_campos = array("email" => $_REQUEST['email']);
foreach($a_campos as $campo => $valor) {
	$o_individual = new IndividualDAO();

	if ($o_individual->busca("$campo = '$valor'")) {
		$xml .= "<erro>O $campo informado ja encontra-se cadastrado em nosso sistema.</erro>";
		die($xml .= "</gravacao>");
	}
}

$o_tipo_inscricao = new TipoInscricaoDAO();

if (!$o_tipo_inscricao->busca($_REQUEST['id_tipo_inscricao'])) {
    $xml .= "<erro>Tipo de Inscricao nao encontrado</erro>";
    die($xml .= "</gravacao>");
}

$o_transacao = new Banco();
$o_transacao->begin();

$o_inscricao = new InscricaoDAO();
$o_inscricao->id_tipo_inscricao = $o_tipo_inscricao->id;
$o_inscricao->data_registro = date("Y-m-d H:i:s");

if (!$o_inscricao->salva()) {
	$o_transacao->rollback();
	$xml .= "<erro>Falha ao tentar gravar dados da inscricao</erro>";
	die($xml .= "</gravacao>");
}

$o_individual = new IndividualDAO();
$o_individual->id_inscricao = $o_inscricao->id;
$o_individual->nome = $_REQUEST['nome'];
$o_individual->email = $_REQUEST['email'];
$o_individual->instituicao = $_REQUEST['instituicao'];
$o_individual->sexo = $_REQUEST['sexo'];
$o_individual->cep = $_REQUEST['cep'];
$o_individual->situacao = 'A';

if (!$o_individual->salva()) {
	$o_transacao->rollback();
	$xml .= "<erro>Falha ao tentar gravar dados do usuario: " . $o_individual->erro_sql . "</erro>";
	die($xml .= "</gravacao>");
}

$o_transacao->commit();

$nome_enviar = Funcoes::remove_acentos($o_individual->nome);

// Enviar email
$mail = new PHPMailer();
$mail->From = SENDMAIL_FROM;
$mail->FromName = SENDMAIL_FROM_NAME;
$mail->Host = SENDMAIL_HOST;
$mail->IsMail();
$mail->IsHTML(true);
$mail->AddAddress($o_individual->email, $nome_enviar);
$mail->Subject = "Cadastro realizado com sucesso";

$mensagem_estudante = "";
if (trim($o_tipo_inscricao->descricao) == "Estudante") {
	$mensagem_estudante = "Para a confirma&ccedil;&atilde;o de sua inscri&ccedil;&atilde;o, precisamos que envie para <a href='mailto:" . SENDMAIL_FROM . "'>" . SENDMAIL_FROM . "</a> uma c&oacute;pia de sua carteira de estudante.<br><br>";
}

$mail->Body = "
    <html>
    <body>
    Ol&aacute; <b>$nome_enviar</b>,<br><br>
    Obrigado pelo interesse em participar do <b>" . NOME_EVENTO . "</b>!<br><br>
    Confirmamos o cadastro de seus dados em nosso sistema.<br><br>
    Estamos aguardando a confirma&ccedil;&atilde;o do PagSeguro, para finalizarmos seu processo de inscri&ccedil;&atilde;o.<br><br>
    Assim que conclu&iacute;do, voc&ecirc; receber&aacute; uma mensagem.<br><br>
    Caso necess&aacute;rio, acesse <a href='" . HOME_PAGE . "inscricao/view/pagamentoIndividual.php?id=" . $o_individual->id . "'>" . HOME_PAGE . "inscricao/view/pagamentoIndividual.php?id=" . $o_individual->id . "</a> para efetuar o pagamento de sua inscri&ccedil;&atilde;o.<br><br>
    $mensagem_estudante
    <br>At&eacute; o evento!<br><br>
    <b>Organiza&ccedil;&atilde;o do " . NOME_EVENTO . "</b>!
    </body>
    </html>
";

if (!$mail->Send()) {
	$xml .= "<erro>Falha ao tentar enviar e-mail para o usuario</erro>";
	die($xml .= "</gravacao>");
}

$mensagem = "Seus dados foram registrados em nosso sistema com sucesso. Agora voce devera efetuar o pagamento de sua inscricao.";

$xml .= "<msg>$mensagem</msg>";
$xml .= "<id>" . $o_individual->id . "</id>";
die($xml .= "</gravacao>");
?>
