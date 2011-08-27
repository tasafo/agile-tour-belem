$(document).ready(function($){
	// Funcoes para os links
	$("#gravar").click( function() {
		validar();
	});

	$("#nome").focus();
});

function validar(){
    var container = $('div.container');
    
	$('#form').validate({
        errorContainer: container,
		errorLabelContainer: $("ol", container),
		wrapper: 'li',
		meta: "validate",
		//  define regras para os campos
		rules: {
			nome: {
				required: true
			},
			cpf:{
				required: true,
				verificaCPF: true
			},
			email:{
				required: true,
				email: true
			},
            nome_cracha: {
				required: true
			},
            ddd: {
				required: true,
				digits: true,
                minlength:2
			},
            telefone: {
				required: true,
				digits: true,
                minlength: 8
			},
            empresa: {
				required: true
			},
            endereco: {
				required: true
			},
            numero: {
				required: true,
				digits: true
			},
            bairro: {
				required: true
			},
            cep: {
				required: true,
				digits: true,
                minlength: 8
			},
            cidade: {
				required: true
			}
		},
		// define messages para cada campo
		messages: {
            nome: 'Informe seu Nome',
            cpf: 'Informe seu CPF v&aacute;lido',
            email: 'Informe seu E-mail v&aacute;lido',
            nome_cracha: 'Informe seu Nome para o Crach&aacute;',
            ddd: 'Informe o DDD',
            telefone: 'Informe seu Telefone',
            empresa: 'Informe sua Institui&ccedil;&atilde;o',
            endereco: 'Informe seu Endere&ccedil;o',
            numero: 'Informe o n&uacute;mero de sua residência',
            bairro: 'Informe seu Bairro',
            cep: 'Informe seu CEP',
            cidade: 'Informe sua Cidade'
		},
		submitHandler: function(form) {
			salvar();
		}
	});

	$('#form').submit();
}

function salvar() {
	//$("#div_botao_salvar").hide("fast");

	$("#div_salvando").show("fast",function() {
		$(this).html("<center><font face='Tahoma, Geneva, sans-serif' size='2' color='red'><b>Salvando. Aguarde um momento...</b></font></center>");
	});

	parametros = $('#form').serialize();
	$.ajax({
		type: "POST",
		url: "RespCadastrarInscricaoIndividual.php",
		dataType: "xml",
		data: parametros,
		success: analisarResposta
	});

    $().ajaxStop(function(){
		$("#div_salvando").fadeOut("slow",function(){
			$(this).html("");
			//$("#div_botao_salvar").fadeIn("slow");
		});
	});
}

function analisarResposta(xml) {
    erro = $('erro', xml).text();
    if (erro) {
        alert(erro);

		$("#div_salvando").fadeOut("slow",function(){
			$(this).html("");
			//$("#div_botao_salvar").fadeIn("slow");
		});
        
        return false;
    } else {
        idIndividual = $('id', xml).text();

        alert($('msg', xml).text());
        window.location = 'pagamentoIndividual.php?id=' + idIndividual;    
    }

    return true;
}
