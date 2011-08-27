<?php
require_once dirname(__FILE__) . '/../general/autoload.php';

class EmpresaDAO extends AbstractDAO {
	public $id;
	public $id_endereco;
	public $cnpj;
	public $razao_social;
	public $nome_fantasia;
	public $nome_responsavel;
	public $ddd;
	public $telefone;
	public $email;
	public $senha;

	function __construct() {
		parent::__construct($this);
	}
}
?>