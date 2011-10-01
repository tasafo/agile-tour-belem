<?php
require_once '../general/autoload.php';
require_once '../util/constantes.php';

header("Content-Type: application/xml; charset=utf-8");

$xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
$xml .= "<agilidade>\n";

$idEmpresa = $_REQUEST['idEmpresa'];
$dtPagamento = $_REQUEST['dtPagamento'];
$nome = $_REQUEST['nome'];
$email = $_REQUEST['email'];
$cortesia = $_REQUEST['cortesia'];

$txPagamento = 0;
if ($cortesia != "S")
    $txPagamento = Funcoes::formata_moeda_para_gravar($_REQUEST['txPagamento']);

if (!Funcoes::checa_data($dtPagamento)) {
    $xml .= "<erro>A data nao e valida</erro>";
    $xml .= "<idEmpresa>$idEmpresa</idEmpresa>";
    die($xml .= "</agilidade>");
}

$msg_recarregar = "";

if ($cortesia == "S") {
    $o_tipo_inscricao = new TipoInscricaoDAO();
    $a_tipo_inscricao = $o_tipo_inscricao->busca("descricao = 'Cortesia'");

    if (!$a_tipo_inscricao) {
        $xml .= "<erro>Tipo de Inscricao Cortesia nao foi encontrada</erro>";
        $xml .= "<idInscricao>$idInscricao</idInscricao>";
        die($xml .= "</agilidade>");
    }
    
    $id_tipo_inscricao = $a_tipo_inscricao[0]->id;

    $o_inscricao = new InscricaoDAO();
    $a_inscricoes_da_empresa = $o_inscricao->busca("id_empresa = $idEmpresa");
    
    foreach ($a_inscricoes_da_empresa as $inscrito) {
        $o_inscricao = new InscricaoDAO();
        $o_inscricao->id = $inscrito->id;
        $o_inscricao->id_tipo_inscricao = $id_tipo_inscricao;

        if (!$o_inscricao->salva()) {
            $xml .= "<erro>Falha ao tentar atualizar o tipo de inscricao dos usuarios</erro>";
            $xml .= "<idInscricao>$idEmpresa</idInscricao>";
            die($xml .= "</agilidade>");
        }
    }

    $msg_recarregar = ". Recarregue a pagina para atualizar os valores";
}

$o_inscricao = new InscricaoDAO();
$a_funcionarios_empresa = $o_inscricao->selecionar_funcionarios_inscritos($idEmpresa);

if (!$a_funcionarios_empresa) {
    $xml .= "<erro>Nao foi encontrado nenhum funcionario da empresa</erro>";
    $xml .= "<idEmpresa>$idEmpresa</idEmpresa>";
    die($xml .= "</agilidade>");
}

$listaFuncionarios = "";
foreach ($a_funcionarios_empresa as $funcionario) {
    $listaFuncionarios .= Funcoes::remove_acentos(utf8_encode($funcionario->nome)) . " - " . $funcionario->email . "<br><br>" ;
}

$o_inscricao = new InscricaoDAO();
$a_inscricoes_da_empresa = $o_inscricao->busca("id_empresa = $idEmpresa");

foreach ($a_inscricoes_da_empresa as $inscrito) {
    $o_inscricao = new InscricaoDAO();
    $o_inscricao->id = $inscrito->id;
    $o_inscricao->data_pagamento = Funcoes::formata_data_para_gravar($dtPagamento);
    $o_inscricao->taxa = $txPagamento;

    if (!$o_inscricao->salva()) {
        $xml .= "<erro>Falha ao tentar atualizar o pagamento da empresa</erro>";
        $xml .= "<idEmpresa>$idEmpresa</idEmpresa>";
        die($xml .= "</agilidade>");
    }
}

// Enviar email para a Empresa
$mail = new PHPMailer();
$mail->From = SENDMAIL_FROM;
$mail->FromName = SENDMAIL_FROM_NAME;
$mail->Host = SENDMAIL_HOST;
$mail->IsMail();
$mail->IsHTML(true);
$mail->AddAddress($email, $nome);
$mail->Subject = NOME_EVENTO . " - Confirmação de Pagamento e Inscrição";

$mail->Body = "
    <html>
    <body>
    <b>$nome</b>,<br><br>
    Escrevemos para informar que recebemos o pagamento da inscri&ccedil;&atilde;o de seus funcion&aacute;rios.<br><br>
    $listaFuncionarios
    Acesse nosso <a href='" . HOME_PAGE . "'>web site</a> ou siga o <a href='" . TWITTER_ENDERECO . "'>" . TWITTER_NOME . "</a> no Twitter para acompanhar as novidades do " . NOME_EVENTO . " .<br><br>
    At&eacute; o evento!<br><br>
    <b>Organiza&ccedil;&atilde;o do " . NOME_EVENTO . "</b>!<br><br>
    </body>
    </html>
";

if (!$mail->Send()) {
    $xml .= "<erro>Falha ao tentar enviar e-mail para a empresa</erro>";
    $xml .= "<idEmpresa>$idEmpresa</idEmpresa>";
    die($xml .= "</agilidade>");
}

// Enviar e-mail para os funcionario da Empresa
foreach ($a_funcionarios_empresa as $funcionario) {
    $nome_func = Funcoes::remove_acentos(utf8_encode($funcionario->nome));
    $email_func = $funcionario->email;

    $mail = new PHPMailer();
    $mail->From = SENDMAIL_FROM;
    $mail->FromName = SENDMAIL_FROM_NAME;
    $mail->Host = SENDMAIL_HOST;
    $mail->IsMail();
    $mail->IsHTML(true);
    $mail->AddAddress($email_func, $nome_func);
    $mail->Subject = NOME_EVENTO . " - Confirmação de Pagamento e Inscrição";

    $mail->Body = "
        <html>
        <body>
        Ol&aacute; <b>$nome_func</b>,<br><br>
        Escrevemos para informar que recebemos o pagamento de sua inscri&ccedil;&atilde;o.<br><br>
        Acesse nosso <a href='" . HOME_PAGE . "'>web site</a> ou siga o <a href='" . TWITTER_ENDERECO . "'>" . TWITTER_NOME . "</a> no Twitter para acompanhar as novidades do " . NOME_EVENTO . ".<br><br>
        At&eacute; o evento!<br><br>
        <b>Organiza&ccedil;&atilde;o do " . NOME_EVENTO . "</b>!<br><br>
        </body>
        </html>
    ";

    if (!$mail->Send()) {
        $xml .= "<erro>Falha ao tentar enviar e-mail para o usuario</erro>";
        $xml .= "<idEmpresa>$idEmpresa</idEmpresa>";
        die($xml .= "</agilidade>");
    }
}

$xml .= "<mensagem>Operacao realizada com sucesso. O E-mail ja foi enviado para a empresa$msg_recarregar</mensagem>";
$xml .= "<dataPagamento>$dtPagamento</dataPagamento>";
$xml .= "<idEmpresa>$idEmpresa</idEmpresa>";
die($xml .= "</agilidade>");
?>
