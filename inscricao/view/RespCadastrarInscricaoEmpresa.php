<?php
session_start();

require_once '../general/autoload.php';
require_once '../util/constantes.php';

header("Content-Type: application/xml; charset=utf-8");

$xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
$xml .= "<gravacao>\n";

if (empty($_SESSION['Funcionarios'])) {
	$xml .= "<erro>Voce deve adicionar pelo menos um funcionario na lista.</erro>";
	die($xml .= "</gravacao>");
}

$a_campos = array("email" => $_REQUEST['email']);
foreach($a_campos as $campo => $valor) {
	$o_empresa = new EmpresaDAO();

	if ($o_empresa->busca("$campo = '$valor'")) {
		$xml .= "<erro>O $campo informado ja encontra-se cadastrado em nosso sistema.</erro>";
        die($xml .= "</gravacao>");
	}
}

$o_transacao = new Banco();
$o_transacao->begin();

$o_empresa = new EmpresaDAO();
$o_empresa->nome = $_REQUEST['nome'];
$o_empresa->responsavel = $_REQUEST['responsavel'];
$o_empresa->email = $_REQUEST['email'];
$o_empresa->cep = $_REQUEST['cep'];

if (!$o_empresa->salva()) {
	$o_transacao->rollback();
	$xml .= "<erro>Falha ao tentar gravar dados da empresa: " . $o_empresa->erro_sql . "</erro>";
	die($xml .= "</gravacao>");
}

if (!empty($_SESSION['Funcionarios'])) {
	foreach ($_SESSION['Funcionarios'] as $funcionario) {
		$func_id_tipo_inscricao = $funcionario['func_id_tipo_inscricao'];
		$func_nome = $funcionario['func_nome'];
		$func_email = $funcionario['func_email'];
		$func_cep = $funcionario['func_cep'];
		$func_sexo = $funcionario['func_sexo'];

		$func_nome_sem_acento = Funcoes::remove_acentos($func_nome);

		$o_inscricao = new InscricaoDAO();
		$o_inscricao->id_empresa = $o_empresa->id;
		$o_inscricao->id_tipo_inscricao = $func_id_tipo_inscricao;
		$o_inscricao->data_registro = date("Y-m-d H:i:s");

		if (!$o_inscricao->salva()) {
			$o_transacao->rollback();
			$xml .= "<erro>Falha ao tentar gravar dados da inscricao de $func_nome_sem_acento</erro>";
			die($xml .= "</gravacao>");
		}

		$o_individual = new IndividualDAO();
        $o_individual->id_inscricao = $o_inscricao->id;
        $o_individual->nome = $func_nome;
        $o_individual->email = $func_email;
        $o_individual->cep = $o_empresa->cep;
        $o_individual->sexo = $func_sexo;
        $o_individual->situacao = 'A';

		if (!$o_individual->salva()) {
			$o_transacao->rollback();
			$xml .= "<erro>Falha ao tentar gravar dados do usuario $func_nome_sem_acento</erro>";
			die($xml .= "</gravacao>");
		}
	}
}

$o_transacao->commit();

$nome_enviar = Funcoes::remove_acentos($o_empresa->nome);

// Enviar email
$mail = new PHPMailer();
$mail->From = SENDMAIL_FROM;
$mail->FromName = SENDMAIL_FROM_NAME;
$mail->Host = SENDMAIL_HOST;
$mail->IsMail();
$mail->IsHTML(true);
$mail->AddAddress($o_empresa->email, $nome_enviar);
$mail->Subject = "Cadastro realizado com sucesso";

$mail->Body = "
    <html>
    <body>
    <b>$nome_enviar</b>,<br><br>
    Obrigado pelo interesse em participar do <b>" . NOME_EVENTO . "</b>!<br><br>
    Confirmamos o cadastro de seus dados e funcion&aacute;rios em nosso sistema.<br><br>
    Estamos aguardando a confirma&ccedil;&atilde;o do PagSeguro, para finalizarmos seu processo de inscri&ccedil;&atilde;o.<br><br>
    Assim que conclu&iacute;do, voc&ecirc; receber&aacute; uma mensagem.<br><br>
    Caso tenha ocorrido algum problema, utilize o link abaixo para efetuar o pagamento e confirmar as inscri&ccedil;&otilde;es.<br><br>
    <a href='" . HOME_PAGE . "inscricao/view/pagamentoEmpresa.php?id=" . $o_empresa->id . "'>" . HOME_PAGE . "inscricao/view/pagamentoEmpresa.php?id=" . $o_empresa->id . "</a><br><br>
    Para a confirma&ccedil;&atilde;o de inscri&ccedil;&otilde;es de estudantes, precisamos que envie para <a href='mailto:" . SENDMAIL_FROM . "'>" . SENDMAIL_FROM . "</a> uma c&oacute;pia das carteiras dos estudantes.<br><br>
    <br>At&eacute; o evento!<br><br>
    <b>Organiza&ccedil;&atilde;o do " . NOME_EVENTO . "</b>!<br><br>
    </body>
    </html>
";

if (!$mail->Send()) {
	$xml .= "<erro>Falha ao tentar enviar e-mail para a empresa</erro>";
	die($xml .= "</gravacao>");
}

$mensagem = "Seus dados foram registrados em nosso sistema com sucesso. Agora voce devera efetuar o pagamento das inscricoes.";

$xml .= "<msg>$mensagem</msg>";
$xml .= "<id>" . $o_empresa->id . "</id>";
die($xml .= "</gravacao>");
?>
