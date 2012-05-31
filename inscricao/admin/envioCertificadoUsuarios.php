<?php
require 'validaSessao.php';
require_once '../general/autoload.php';
require_once '../util/constantes.php';

$a_perfis = array('palestrante', 'organizador');

$o_usuario = new UsuarioDAO();

$caminho = dirname(__FILE__);

if (!isset($_POST['id'])) {
  $a_usuarios = $o_usuario->busca(null, "perfis, nome");
  
} else {
  $selecionados = "";
  foreach($_POST['id'] as $id)
    $selecionados .= "$id, ";
  
  $selecionados = substr($selecionados, 0, strlen($selecionados) - 2);
  
  $a_usuarios = $o_usuario->busca("id IN($selecionados)", "perfis, nome");
  
  if ($a_usuarios) {
    $modelo = "$caminho/../certificado/" . TEMPLATE_CERTIFICADO;

    foreach($a_usuarios as $usuario) {
      $nome = $usuario->nome;
      $email = $usuario->email;
      $perfis_usuario = $usuario->perfis;
      $tema_palestra = $usuario->tema_palestra;

      foreach($a_perfis as $perfil) {
        if (strstr($perfis_usuario, $perfil)) {
          require_once("$caminho/../certificado/lib/write_html.php");

          $nome_arquivo = "Certificado " . NOME_EVENTO . " $perfil " . Funcoes::remove_acentos(utf8_encode($nome)) . ".pdf";
          $nome_arquivo = strtolower(str_replace(" ", "_", $nome_arquivo));
          $arquivo_destino = "$caminho/../temp/$nome_arquivo";

          $pdf = new PDF_HTML();
          
          $pdf->AddPage('L');
          $pdf->setSourceFile($modelo);
          $tplIdx = $pdf->importPage(1);
          $pdf->useTemplate($tplIdx);
          
          $palestra = ($perfil == "palestrante") ? ', com o tema "' . utf8_encode($tema_palestra) . '"' : "";
          
          $nome_convertido = utf8_encode($nome);
          
          $titulo = "CERTIFICADO";

          $corpo = utf8_decode("Certificamos que <b>$nome_convertido</b> participou do evento " . NOME_EVENTO . ", realizado " . PERIODO_EVENTO . ", " . LOCAL_EVENTO . ", com carga horária de " . CARGA_HORARIA_EVENTO ." horas, na qualidade de <b>$perfil</b>$palestra.");
          
          // Titulo
          $pdf->SetFont('Arial', 'B', 32);
          $pdf->SetTextColor(255, 255, 255);
          $pdf->SetXY(110, 90);
          $pdf->Write(0, $titulo);
          
          // Corpo do texto
          $pdf->SetFont('Arial', '', 22);
          $pdf->SetTextColor(255, 255, 255);
          $pdf->SetY("100");
          $pdf->SetX("20");
          $pdf->WriteHTML($corpo, 9);

          $pdf->Output($arquivo_destino, 'F');
          
          $retorno = EnviarEmail::enviar("envio_certificado", "", $email, $nome, 0, "", $arquivo_destino);
          
          if (file_exists($arquivo_destino)) unlink($arquivo_destino);
      
          echo "<br><br>O certificado de $perfil de <b>" . utf8_encode($nome) . "</b>" . ($retorno ? "" : " nao") . " foi enviado com sucesso";
        }
      }
    }
    echo '<center><h3><a href="menu.php">Voltar ao Menu</a></h3></center>';
  }
  exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
        <title>Envio de Certificado aos Usuários</title>
        <script type="text/javascript" src="../view/js/jquery/jquery.js" ></script>
        <script type="text/javascript" src="../view/js/jquery/jquery.alerts/jquery.alerts.js" ></script>
        <link href="css/admin.css" rel="stylesheet" />
    </head>
    <body>
        <center>
            <h3><a href="menu.php">Voltar ao Menu</a></h3>
            <h2>Envio de Certificado aos Usuários</h2>
        </center>
        <?php if ($a_usuarios) { ?>
        <form id="form" method="post" action="envioCertificadoUsuarios.php">
            <table width="100%" border="1" class="bordasimples">
                <tr style="font-weight: bold">
                    <td align="center">&nbsp;</td>
                    <td>Id</td>
                    <td>Nome</td>
                    <td>E-mail</td>
                    <td>Perfis</td>
                    <td>Tema da Palestra</td>
                </tr>
                <?php
                foreach ($a_usuarios as $usuario) {
                ?>
                <tr>
                    <td align="center"><input type='checkbox' name='id[]' value='<?php echo $usuario->id ?>' /></td>
                    <td><?php echo $usuario->id ?></td>
                    <td><?php echo utf8_encode($usuario->nome) ?></td>
                    <td><?php echo $usuario->email ?></td>
                    <td><?php echo $usuario->perfis ?></td>
                    <td><?php echo utf8_encode($usuario->tema_palestra) ?></td>
                <?php
                }
                ?>
            </table>
            <center>
              <br><br>
              <b style="color:red">Selecione o(s) usuário(s) e clique no botão abaixo para iniciar o processo de envio.</b><br><br>
              <input type='submit' name='enviar' id='enviar' value='Enviar Certificados' /><br><br>
            </center>
        </form>
        <?php } ?>
    </body>
</html>