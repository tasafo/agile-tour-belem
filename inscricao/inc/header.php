	<title>AgileTourBelém 2011 :: Faça Sua Inscrição</title>
    <meta name="description" content="AgieTourBelem" />
	<meta name="keywords" content="Palavras-chave: Agile, Scrum, Belém, Pará, Desenvolvimento, Softwares"/>
    <link href="css/index.css" rel="stylesheet" type="text/css" media="screen" />
    <script type="text/javascript" src="./view/js/jquery/jquery.js"></script>

	<script language="JavaScript">
        function Valida(quadro) {
            if (quadro.name.value == "") {
                alert ("Ops, o campo nome e obrigatorio.");
                quadro.name.focus();
            } else if (quadro.email.value == "") {
                alert ("Ops, o campo email e obrigatorio.");
                quadro.email.focus(); }
            else if(!validarEmail(quadro.email.value)) {
                quadro.email.focus();
            } else if (quadro.mensagem.value == "") {
                alert ("Ops, o campo mensagem e obrigatorio.");
                quadro.mensagem.focus();
            } else {
                quadro.submit();
            }
        }
        
        function validarEmail(email) {
            expressao = /^\w{2,}.{0,1}\w{0,}@\w{3,}.\w{3,}/;
            valido = expressao.exec(email);

            if (!valido) {
                alert("Ops, digite um email valido.");
                return false;
            }
            return true;
        }
 
        $(document).ready(function() {
            // hide #back-top first
            $("#back-top").hide();
            
            // fade in #back-top
            $(function () {
                $(window).scroll(function () {
                    if ($(this).scrollTop() > 100) {
                        $('#back-top').fadeIn();
                    } else {
                        $('#back-top').fadeOut();
                    }
                });

                // scroll body to 0px on click
                $('#back-top a').click(function () {
                    $('body,html').animate({
                        scrollTop: 0
                    }, 800);
                    return false;
                });
            });
        });
    </script>
