<?php
require_once dirname(__FILE__) . '/../general/autoload.php';

class EnderecoDAO extends AbstractDAO {
	public $id;
	public $endereco;
	public $numero;
	public $complemento;
	public $bairro;
	public $cep;
	public $cidade;
	public $uf;

	function __construct() {
		parent::__construct($this);
	}
}
?>