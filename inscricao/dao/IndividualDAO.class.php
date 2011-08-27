<?php
require_once dirname(__FILE__) . '/../general/autoload.php';

class IndividualDAO extends AbstractDAO {
	public $id;
	public $id_inscricao;
	public $id_endereco;
	public $nome;
	public $cpf;
	public $email;
	public $nome_cracha;
	public $senha;
	public $ddd;
	public $telefone;
	public $empresa;
	public $sexo;
	public $situacao;

	function __construct() {
		parent::__construct($this);
	}
}
?>