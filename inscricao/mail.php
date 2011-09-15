<?php
require_once('general/PHPMailer.class.php');

$nome = stripslashes($_POST['name']);
$email = stripslashes($_POST['email']);
$texto = stripslashes($_POST['mensagem']);

// faco a chamada da classe
$Email = new PHPMailer();
// na classe, ha a opcao de idioma, setei como br
//$Email->SetLanguage("br");
// esta chamada diz que o envio sera feito atraves da funcao mail do php. Voce mudar para sendmail, qmail, etc 
// se quiser utilizar o programa de email do seu unix/linux para enviar o email
$Email->IsMail(); 
// ativa o envio de e-mails em HTML, se false, desativa.
$Email->IsHTML(true); 
// nome do remetente do email
$Email->FromName = $nome;
// Endereco de destino do email, ou seja, pra onde voce quer que a mensagem do formulario va?
$Email->AddAddress("fabioaguiar@gmail.com");
$Email->AddBcc("luizgrsanches@gmail.com");
$Email->AddBcc("felip.aguiar@gmail.com");

// informando no email, o assunto da mensagem
$Email->Subject = "ATB 2011 - Formulario de Contato on-line de " . $nome;

$Email->Body .= "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"br\" lang=\"br\">
";

// Define o texto da mensagem (aceita HTML)
$Email->Body .= "<b>Nome:</b> $nome<BR>";
$Email->Body .= "<b>Email:</b> $email<BR>";
$Email->Body .= "<b>Comentario:</b> $texto<BR>";

if($Email->Send()){
	header("Location: index.php");
} 
else{ 
	echo "<script>alert(\"Nao foi possivel enviar o formulario. Tente outra vez!\");</script>";
	header("Location: index.php");
}
?>