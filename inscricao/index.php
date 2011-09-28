<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <?php include(dirname(__FILE__) . "/inc/header.php") ?>
    <link rel="stylesheet" type="text/css" href="./fancybox/jquery.fancybox-1.3.4.css" media="screen" />
    <script type="text/javascript" src="./view/js/jquery/jquery.js"></script>
    <script type="text/javascript" src="./view/js/jquery/jquery.validate.js"></script>
    <script type="text/javascript" src="./fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
    <script type="text/javascript" src="./fancybox/jquery.fancybox-1.3.4.pack.js"></script>
    <script type="text/javascript" src="./view/js/recupera_pagamento.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#indiv").fancybox({
                'width'				: '50%',
                'height'			: '60%',
                'autoScale'			: false,
                'transitionIn'		: 'none',
                'transitionOut'		: 'none',
                'type'				: 'iframe'
            });
            
            $("#emp").fancybox({
                'width'				: '80%',
                'height'			: '80%',
                'autoScale'			: false,
                'transitionIn'		: 'none',
                'transitionOut'		: 'none',
                'type'				: 'iframe'
            });
        });
    </script>
</head>
<body>
    <div id="main_header">
        <div id="header">
            <div id="menu">
                <?php include(dirname(__FILE__) . "/inc/topo.php") ?>
            </div>
            <div id="texto">
                <h1>Recupere o link do Pagseguro informando seu e-mail logo abaixo</h1>
                <form id="form_recupera" name="form_recupera" action="view/recuperaPagamento.php" method="post">
                    <input type="text" name="email" id="email" maxlength="100" size="30" />
                    <input type="button" id="recuperar" name="recuperar" value="Recuperar" />
                </form>
                <h1>Para realizar sua inscrição no evento, selecione uma das categorias abaixo.</h1>
            </div>
        </div>
    </div>
    <div id="main_body">
        <div id="body">
            <div class="box" style="color:#596b3a">
                <img src="img/individual.gif" alt="" width="355" height="58" />
                <p>Selecione esta opção caso você esteja fazendo sua inscrição de forma avulsa, ou seja, o pagamento será efetuado por você e não pela empresa que você trabalha.</p>
                <div class="inscricao01">
                    <a id="indiv" href="view/CadastrarInscricaoIndividual.php"></a>
                </div>
            </div>
            <div class="box" style="color:#89383f">
                <img src="img/empresa.gif" alt="" width="355" height="58" />
                <p>Selecione esta opção para efetuar múltiplas inscrições por empresa/instituição. <br />O cadastramento de informações da empresa/instituição é obrigatório.</p>
                <div class="inscricao02">
                    <a  id="emp" href="view/CadastrarInscricaoEmpresa.php"></a>
                </div>
            </div>
        </div>
        <div id="main_footer">
            <div id="footer">
                <?php include(dirname(__FILE__) . "/inc/rodape.php") ?>
            </div>
        </div>
    </div>
</body>
</html>