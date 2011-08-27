<?php
require_once dirname(__FILE__) . '/../general/autoload.php';

class IndividualDAO extends AbstractDAO {
	public $id;
	public $id_inscricao;
	public $nome;
	public $email;
	public $instituicao;
	public $sexo = "M";
	public $cep;
	public $situacao = "A";

	function __construct() {
		parent::__construct($this);
	}
}
?>