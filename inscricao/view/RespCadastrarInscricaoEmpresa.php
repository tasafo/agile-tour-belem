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

$a_campos = array("cnpj", "email");
foreach($a_campos as $campo) {
	$o_empresa = new EmpresaDAO();

	if ($o_empresa->busca("$campo = '" . $$campo . "'")) {
		$xml .= "<erro>O $campo informado ja encontra-se cadastrado em nosso sistema.</erro>";
        die($xml .= "</gravacao>");
	}
}

$o_transacao = new Banco();
$o_transacao->begin();

$o_endereco = new EnderecoDAO();
$o_endereco->endereco = $_REQUEST['endereco'];
$o_endereco->numero = $_REQUEST['numero'];
$o_endereco->complemento = $_REQUEST['complemento'];
$o_endereco->bairro = $_REQUEST['bairro'];
$o_endereco->cep = $_REQUEST['cep'];
$o_endereco->cidade = $_REQUEST['cidade'];
$o_endereco->uf = $_REQUEST['uf'];

if (!$o_endereco->salva()) {
	$o_transacao->rollback();
	$xml .= "<erro>Falha ao tentar gravar dados do endereco</erro>";
	die($xml .= "</gravacao>");
}

$o_empresa = new EmpresaDAO();
$o_empresa->id_endereco = $o_endereco->id;
$o_empresa->razao_social = $_REQUEST['razao_social'];
$o_empresa->nome_fantasia = $_REQUEST['nome_fantasia'];
$o_empresa->nome_responsavel = $_REQUEST['nome_responsavel'];
$o_empresa->cnpj = $_REQUEST['cnpj'];
$o_empresa->email = $_REQUEST['email'];
$o_empresa->ddd = $_REQUEST['ddd'];
$o_empresa->telefone = $_REQUEST['telefone'];

if (!$o_empresa->salva()) {
	$o_transacao->rollback();
	$xml .= "<erro>Falha ao tentar gravar dados da empresa: " . $o_empresa->erro_sql . "</erro>";
	die($xml .= "</gravacao>");
}

if (!empty($_SESSION['Funcionarios'])) {
	foreach ($_SESSION['Funcionarios'] as $funcionario) {
		$func_categoria_inscricao = $funcionario['func_categoria_inscricao'];
		$func_nome = $funcionario['func_nome'];
		$func_cpf = $funcionario['func_cpf'];
		$func_email = $funcionario['func_email'];
		$func_nome_cracha = $funcionario['func_nome_cracha'];
		$func_ddd = $funcionario['func_ddd'];
		$func_telefone = $funcionario['func_telefone'];
		$func_sexo = $funcionario['func_sexo'];

		$func_nome_sem_acento = Funcoes::remove_acentos($func_nome);

		$o_inscricao = new InscricaoDAO();
		$o_inscricao->id_empresa = $o_empresa->id;
		$o_inscricao->id_tipo_inscricao = $func_categoria_inscricao;
		$o_inscricao->data_registro = date("Y-m-d H:i:s");

		if (!$o_inscricao->salva()) {
			$o_transacao->rollback();
			$xml .= "<erro>Falha ao tentar gravar dados da inscricao de $func_nome_sem_acento</erro>";
			die($xml .= "</gravacao>");
		}

		$o_individual = new IndividualDAO();
        $o_individual->id_inscricao = $o_inscricao->id;
        $o_individual->id_endereco = $o_endereco->id;
        $o_individual->nome = $func_nome;
        $o_individual->cpf = $func_cpf;
        $o_individual->email = $func_email;
        $o_individual->nome_cracha = $func_nome_cracha;
        $o_individual->ddd = $func_ddd;
        $o_individual->telefone = $func_telefone;
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

$nome_enviar = Funcoes::remove_acentos($o_empresa->nome_fantasia);

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
