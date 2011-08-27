<?php
require_once '../general/autoload.php';

$idEmpresa = $_REQUEST['hdnIdEmpresa'];
$idEndereco = $_REQUEST['hdnIdEndereco'];
$categoria_inscricao = $_REQUEST['func_categoria_inscricao'];
$nome = $_REQUEST['func_nome'];
$cpf = $_REQUEST['func_cpf'];
$email = $_REQUEST['func_email'];
$nome_cracha = $_REQUEST['func_nome_cracha'];
$ddd = $_REQUEST['func_ddd'];
$telefone = $_REQUEST['func_telefone'];
$sexo = $_REQUEST['func_sexo'];

$nome_sem_acento = Funcoes::remove_acentos($nome);

$a_campos = array("cpf", "email");
foreach($a_campos as $campo) {
	$o_individual = new IndividualDAO();

	if ($o_individual->busca("$campo = '" . $$campo . "'"))
	    die("Atencao! Este $campo ja foi utilizando em uma inscricao no sistema.");
}

$o_transacao = new Banco();
$o_transacao->begin();

$o_inscricao = new InscricaoDAO();
$o_inscricao->id_empresa = $idEmpresa;
$o_inscricao->id_tipo_inscricao = $categoria_inscricao;
$o_inscricao->data_registro = date("Y-m-d H:i:s");

if (!$o_inscricao->salva()) {
    $o_transacao->rollback();
	die("Atencao! Falha ao tentar gravar dados da inscricao de $nome_sem_acento: " . $o_inscricao->erro_sql);
} else {
	$o_individual = new IndividualDAO();
	$o_individual->id_inscricao = $o_inscricao->id;
	$o_individual->id_endereco = $idEndereco;
	$o_individual->nome = $nome;
	$o_individual->cpf = $cpf;
	$o_individual->email = $email;
	$o_individual->nome_cracha = $nome_cracha;
	$o_individual->ddd = $ddd;
	$o_individual->telefone = $telefone;
	$o_individual->sexo = $sexo;
	$o_individual->situacao = 'A';
	
	if (!$o_individual->salva()) {
        $o_transacao->rollback();
        die("Atencao! Falha ao tentar gravar dados do usuario $nome_sem_acento: " . $o_individual->erro_sql);
	}
}

$o_transacao->commit();

$o_inscricao = new InscricaoDAO();
$a_funcionarios_inscritos = $o_inscricao->selecionar_funcionarios_inscritos($idEmpresa);
?>
<table width="100%" border="1">
	<tr style="font-weight: bold; text-align: center">
		<td>Inscri&ccedil;&atilde;o</td>
		<td>Nome</td>
		<td>E-mail</td>
		<td>Inscrito como</td>
	</tr>
	<?php foreach ($a_funcionarios_inscritos as $inscrito) { ?>
	<tr>
		<td align="center"><?php echo $inscrito->id ?></td>
		<td><?php echo trim(Funcoes::remove_acentos($inscrito->nome)) ?></td>
		<td><?php echo $inscrito->email ?></td>
		<td><?php echo $inscrito->descricao ?></td>
	</tr>
	<?php } ?>
</table>
