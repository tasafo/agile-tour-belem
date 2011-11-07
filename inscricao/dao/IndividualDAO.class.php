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
	
    function inscritos_por_intervalo($inicio, $fim, $inadimplentes = false, $incluir_cancelados = false, $adimplentes = false) {
        $filtro_inadimplentes = ($inadimplentes) ? "AND ins.data_pagamento IS NULL" : "";
        
        $filtro_cancelados = ($incluir_cancelados) ? "" : "AND ind.situacao = 'A'";
        
        $filtro_adimplentes = ($adimplentes) ? "AND ins.data_pagamento IS NOT NULL" : "";
        
        $sql = "SELECT ind.* FROM individual ind
            JOIN inscricao ins ON (ind.id_inscricao = ins.id)
            WHERE ind.id BETWEEN $inicio AND $fim
            $filtro_inadimplentes
            $filtro_cancelados
            $filtro_adimplentes
            ORDER BY ind.id";

        return $this->resultado_consulta($sql);
    }
}
?>