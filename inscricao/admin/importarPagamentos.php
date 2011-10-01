<?php
require 'validaSessao.php';
require_once '../general/autoload.php';
require_once '../util/constantes.php';

$msg_ok = "<font color='blue'><b>[Ok]</b></font>";
$msg_aviso = "<font color='green'><b>[Aviso]</b></font>";
$msg_erro = "<font color='red'><b>[Erro]</b></font>";

if ($_FILES['arquivo']) {
    if ($_FILES['arquivo']['error'] == 0) {
        $arquivo_temp = $_FILES['arquivo']['tmp_name'];
        $nome_arquivo = $_FILES['arquivo']['name'];
        $diretorio = dirname(__FILE__) . "/pagtoimport";
        $arquivo_copiado = "$diretorio/$nome_arquivo";

        if (!move_uploaded_file($arquivo_temp, $arquivo_copiado)) {
            echo "<h2>$msg_erro - Não foi possível importar o arquivo de pagamentos</h2>";
        } else {
            $xml = simplexml_load_file($arquivo_copiado);
            
            if (!$xml) {
                echo "<h2>$msg_erro - O arquivo de pagamentos não é um XML válido</h2>";
            } else {
                if (!$xml->Table) {
                    echo "<h2>$msg_erro - A estrutura do arquivo de pagamentos não é válida</h2>";
                } else {
                    echo "<b>Log de importação de pagamentos</b><br><br>";
                
                    foreach($xml->Table as $pagamento) {
                        if ($pagamento->Tipo_Transacao == "Pagamento" && $pagamento->Status == "Aprovada") {
                            echo "<b> > " . $pagamento->Cliente_Nome . "</b> ";
                            
                            $id_individual = substr($pagamento->Ref_Transacao, 1);
                            
                            $o_individual = new IndividualDAO();
                            $o_inscricao = new InscricaoDAO();
                            
                            if (!$o_individual->busca($id_individual)) {
                                echo "$msg_erro - Usuário não encontrado<br><br>";
                            } else {
                                $nome = $o_individual->nome;
                                $email = $o_individual->email;
                                
                                if (!$o_inscricao->busca($o_individual->id_inscricao)) {
                                    echo "$msg_erro - Inscrição não encontrada<br><br>";
                                } else {
                                    if (!empty($o_inscricao->data_pagamento)) {
                                        echo "$msg_aviso - O pagameno já consta no sistema<br><br>";
                                    } else {
                                        $data_pagamento = Funcoes::formata_data_para_gravar(substr($pagamento->Data_Transacao, 0, 10)) . substr($pagamento->Data_Transacao, 10);
                                        
                                        $data_compensacao = Funcoes::formata_data_para_gravar(substr($pagamento->Data_Compensacao, 0, 10)) . substr($pagamento->Data_Compensacao, 10);
                                        
                                        $valor_taxa = Funcoes::formata_moeda_para_gravar($pagamento->Valor_Taxa);
                                        
                                        $o_inscricao->data_pagamento = $data_pagamento;
                                        $o_inscricao->data_compensacao = $data_compensacao;
                                        $o_inscricao->taxa = $valor_taxa;
                                        $o_inscricao->tipo_pagamento = $pagamento->Tipo_Pagamento;
                                        $o_inscricao->transacao_id = $pagamento->Transacao_ID;

                                        if (!$o_inscricao->salva()) {
                                            echo "$msg_erro - Falha ao tentar atualizar o pagamento do usuario<br><br>";
                                        } else {
                                            // Enviar email
                                            $mail = new PHPMailer();
                                            $mail->From = SENDMAIL_FROM;
                                            $mail->FromName = SENDMAIL_FROM_NAME;
                                            $mail->Host = SENDMAIL_HOST;
                                            $mail->IsMail();
                                            $mail->IsHTML(true);
                                            $mail->AddAddress($email, $nome);
                                            $mail->Subject = "Confirmação de Pagamento e Inscrição - " . NOME_EVENTO;

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
                                                echo "$msg_erro - Falha ao tentar enviar e-mail para o usuario<br><br>";
                                            }
                                            
                                            echo "$msg_ok<br><br>";
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <title>Importar Pagamentos</title>
    </head>
    <body>
        <center>
            <h3><a href="menu.php">Voltar ao Menu</a></h3>
            <h2>Importar Pagamentos</h2>
            <form name="frmDirf" method="post" enctype="multipart/form-data" action="importarPagamentos.php">
                Arquivo: <input type="file" size="40" name="arquivo"><br><br>
                <input type="submit" id="arquivo" name="arquivo" value="Importar arquivo" />
            </form>
        </center>
    </body>
</html> 
