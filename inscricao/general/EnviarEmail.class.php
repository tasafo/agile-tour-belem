<?php
require_once dirname(__FILE__) . '/autoload.php';
require_once dirname(__FILE__) . '/../util/constantes.php';

class EnviarEmail {
    public static function enviar($motivo, $tipo, $email, $nome, $id = 0, $complemento = "") {
        if ($motivo == "cadastro")
            $titulo = "Cadastro realizado com sucesso";
        elseif ($motivo == "pagamento")
            $titulo = "Confirmação de pagamento e inscrição";
    
        $mail = new PHPMailer();
        $mail->From = SENDMAIL_FROM;
        $mail->FromName = SENDMAIL_FROM_NAME;
        $mail->Host = SENDMAIL_HOST;
        $mail->IsMail();
        $mail->IsHTML(true);
        $mail->AddAddress($email, $nome);
        $mail->Subject = NOME_EVENTO . " - $titulo";

        $saudacao = $tipo == "individual" ? "Ol&aacute; " : "";
        
        $texto = "
            <html>
            <body>
            $saudacao<b>$nome</b>,<br><br>";
        
        if ($motivo == "cadastro") {
            $tipoCapitulado = ucfirst($tipo);
            
            $texto .= "
                Obrigado pelo interesse em participar do <b>" . NOME_EVENTO . "</b>!<br><br>
                Confirmamos o cadastro de seus dados em nosso sistema.<br><br>
                Estamos aguardando a confirma&ccedil;&atilde;o do PagSeguro, para finalizarmos seu processo de inscri&ccedil;&atilde;o.<br><br>
                Assim que conclu&iacute;do, voc&ecirc; receber&aacute; uma mensagem.<br><br>
                Caso tenha ocorrido algum problema, utilize o link abaixo para efetuar o pagamento e confirmar a inscri&ccedil;&atilde;.<br><br>
                <a href='" . HOME_PAGE . "inscricao/view/pagamento$tipoCapitulado.php?id=" . $id . "'>" . HOME_PAGE . "inscricao/view/pagamento$tipoCapitulado.php?id=" . $id . "</a> para efetuar o pagamento de sua inscri&ccedil;&atilde;o.<br><br>
                $complemento";
                
        } elseif ($motivo == "pagamento") {
            if ($tipo == "individual") {
                $texto .= "
                    Escrevemos para informar que recebemos o pagamento de sua inscri&ccedil;&atilde;o.<br><br>";
            } elseif ($tipo == "empresa") {
                $texto .= "
                    Escrevemos para informar que recebemos o pagamento da inscri&ccedil;&atilde;o de seus funcion&aacute;rios.<br><br>
                    $complemento";
            }
        }

        $texto .= "
            Acesse nosso <a href='" . HOME_PAGE . "'>web site</a> ou siga o <a href='" . TWITTER_ENDERECO . "'>" . TWITTER_NOME . "</a> no Twitter para acompanhar as novidades do " . NOME_EVENTO . ".<br><br>
            At&eacute; o evento!<br><br>
            <b>Organiza&ccedil;&atilde;o do " . NOME_EVENTO . "</b>!<br><br>
            </body>
            </html>";
        
        $mail->Body = $texto;
   
        return $mail->Send();
    } 
}