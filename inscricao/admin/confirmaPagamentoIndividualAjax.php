<?php
require_once '../general/autoload.php';
require_once '../util/constantes.php';

header("Content-Type: application/xml; charset=utf-8");

$xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
$xml .= "<agilidade>\n";

$idInscricao = $_REQUEST['idInscricao'];
$dtPagamento = $_REQUEST['dtPagamento'];
$nome = $_REQUEST['nome'];
$email = $_REQUEST['email'];
$cortesia = $_REQUEST['cortesia'];

$txPagamento = 0;
if ($cortesia != "S")
    $txPagamento = Funcoes::formata_moeda_para_gravar($_REQUEST['txPagamento']);

if (!Funcoes::checa_data($dtPagamento)) {
    $xml .= "<erro>Data invalida</erro>";
    $xml .= "<idInscricao>$idInscricao</idInscricao>";
    die($xml .= "</agilidade>");
}

if (!is_numeric($txPagamento)) {
    $xml .= "<erro>Taxa invalida</erro>";
    $xml .= "<idInscricao>$idInscricao</idInscricao>";
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
    
    $o_inscricao = new InscricaoDAO();
    $o_inscricao->id = $idInscricao;
    $o_inscricao->id_tipo_inscricao = $a_tipo_inscricao[0]->id;

    if (!$o_inscricao->salva()) {
        $xml .= "<erro>Falha ao tentar atualizar o tipo de inscricao do usuario</erro>";
        $xml .= "<idInscricao>$idInscricao</idInscricao>";
        die($xml .= "</agilidade>");
    }

    $msg_recarregar = ". Recarregue a pagina para atualizar os valores";
}

$o_inscricao = new InscricaoDAO();
$o_inscricao->id = $idInscricao;
$o_inscricao->data_pagamento = Funcoes::formata_data_para_gravar($dtPagamento);
$o_inscricao->taxa = $txPagamento;

if (!$o_inscricao->salva()) {
    $xml .= "<erro>Falha ao tentar atualizar o pagamento do usuario</erro>";
    $xml .= "<idInscricao>$idInscricao</idInscricao>";
    die($xml .= "</agilidade>");
}

// Enviar email
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
    Ol&aacute; <b>$nome</b>,<br><br>
    Escrevemos para informar que recebemos o pagamento de sua inscri&ccedil;&atilde;o.<br><br>
    Acesse nosso <a href='" . HOME_PAGE . "'>web site</a> ou siga o <a href='" . TWITTER_ENDERECO . "'>" . TWITTER_NOME . "</a> no Twitter para acompanhar as novidades do " . NOME_EVENTO . ".<br><br>
    At&eacute; o evento!<br><br>
    <b>Organiza&ccedil;&atilde;o do " . NOME_EVENTO . "</b>!<br><br>
    </body>
    </html>
";

if (!$mail->Send()) {
    $xml .= "<erro>Falha ao tentar enviar e-mail para o usuario</erro>";
    die($xml .= "</agilidade>");
}

$xml .= "<mensagem>Operacao realizada com sucesso. O E-mail ja foi enviado para o inscrito$msg_recarregar</mensagem>";
$xml .= "<dataPagamento>$dtPagamento</dataPagamento>";
$xml .= "<taxaPagamento>$txPagamento</taxaPagamento>";
$xml .= "<idInscricao>$idInscricao</idInscricao>";
die($xml .= "</agilidade>");
?>
