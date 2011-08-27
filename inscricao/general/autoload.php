<?php
function __autoload($class) {
	$caminho = dirname(__FILE__) . "/..";

	$achou_dao = strstr($class, "DAO");

	$pasta = ($achou_dao) ? "dao" : "general";
	
	require_once("$caminho/$pasta/$class.class.php");
}
?>