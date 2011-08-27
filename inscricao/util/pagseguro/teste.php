<?php

// Incluindo o arquivo da biblioteca
include('pgs.php');

// Criando um novo carrinho
#$pgs=new pgs(array('email_cobranca'=>'seu_email_no@pagseguro.com.br'));

$pgs=new pgs(array(
  'email_cobranca'=>'seu_email_no@pagseguro.com.br',
  'tipo'=>'CBR',
  'ref_transacao'=>'A36',
  'tipo_frete'=>'PAC'
));

// Adicionando um produto
$pgs->adicionar(array(
  array(
    "descricao"=>"Descricaoo do Produto",
    "valor"=>12.90,
    "peso"=>2,
    "quantidade"=>1,
    "id"=>"33"
  ),
));

// Mostrando o botao de pagamento
$pgs->mostra();

?>