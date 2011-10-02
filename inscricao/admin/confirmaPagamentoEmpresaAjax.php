<?php
require_once '../general/autoload.php';

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

$taxa_por_pessoa = 0;
if ($txPagamento > 0)
    $taxa_por_pessoa = $txPagamento / count($a_funcionarios_empresa);

$lista_funcionarios = "";

foreach ($a_funcionarios_empresa as $inscrito) {
    $nome_func = Funcoes::remove_acentos(utf8_encode($inscrito->nome));
    $email_func = $inscrito->email;

    $lista_funcionarios .= "$nome_func - $email_func<br><br>";
    
    $o_inscricao = new InscricaoDAO();
    $o_inscricao->id = $inscrito->id;
    $o_inscricao->data_pagamento = Funcoes::formata_data_para_gravar($dtPagamento);
    $o_inscricao->taxa = $taxa_por_pessoa;

    if (!$o_inscricao->salva()) {
        $xml .= "<erro>Falha ao tentar atualizar o pagamento do funcionario</erro>";
        $xml .= "<idEmpresa>$idEmpresa</idEmpresa>";
        die($xml .= "</agilidade>");
    } else {
        $retorno = EnviarEmail::enviar("pagamento", "individual", $email_func, $nome_func);
        if (!$retorno) {
            $xml .= "<erro>Falha ao tentar enviar e-mail para o usuario</erro>";
            $xml .= "<idEmpresa>$idEmpresa</idEmpresa>";
            die($xml .= "</agilidade>");
        }       
    }
}

$retorno = EnviarEmail::enviar("pagamento", "empresa", $email, $nome, 0, $lista_funcionarios);
if (!$retorno) {
    $xml .= "<erro>Falha ao tentar enviar e-mail para a empresa</erro>";
    $xml .= "<idEmpresa>$idEmpresa</idEmpresa>";
    die($xml .= "</agilidade>");
}

$xml .= "<mensagem>Operacao realizada com sucesso. O E-mail ja foi enviado para a empresa e funcionarios$msg_recarregar</mensagem>";
$xml .= "<dataPagamento>$dtPagamento</dataPagamento>";
$xml .= "<idEmpresa>$idEmpresa</idEmpresa>";
die($xml .= "</agilidade>");
?>
