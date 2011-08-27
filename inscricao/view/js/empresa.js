$(document).ready(function($) {
	$("#gravar").click( function() {
		validar_empresa();
	});
    
	$("#insere_funcionario").click( function() {
		validar_funcionario();
	});

	$("#nome").focus();
});

function validar_empresa() {
    var container = $('#div_msg_empresa');

	$('#form').validate({
        errorContainer: container,
		errorLabelContainer: $("ol", container),
		wrapper: 'li',
		meta: "validate",
		
		rules: {
			nome: {
				required: true
			},
            responsavel: {
				required: true
			},
			email:{
				required: true,
				email: true
			},
            cep: {
				required: true,
				digits: true,
                minlength: 8
			}
		},
		
		messages: {
            nome: 'Informe o Nome da Empresa',
            responsavel: 'Informe o Nome do Respons&aacute;vel',
            email: 'Informe um E-mail v&aacute;lido',
            cep: 'Informe o CEP'
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
		$(this).html("<b>Salvando. Aguarde um momento...</b>");
	});

	parametros = $('#form').serialize();
	$.ajax({
		type: "POST",
		url:  "RespCadastrarInscricaoEmpresa.php",
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
        idEmpresa = $('id', xml).text();

        alert($('msg', xml).text());
        window.location = 'pagamentoEmpresa.php?id=' + idEmpresa;
    }

    return true;
}

function validar_funcionario(){
    var container = $('#div_msg_funcionario');

	$('#frmFunc').validate({
        errorContainer: container,
		errorLabelContainer: $("ol", container),
		wrapper: 'li',
		meta: "validate",
		//  define regras para os campos
		rules: {
			func_nome: {
				required: true
			},
			func_email:{
				required: true,
				email: true
			}
		},
		// define messages para cada campo
		messages: {
            func_nome: 'Informe o nome do funcion&aacute;rio',
            func_email: 'Informe um E-mail v&aacute;lido do funcion&aacute;rio',
		},
		submitHandler: function(form) {
			atualizaFuncionarioAjax(0, 'incluir');
		}
	});

    $('#frmFunc').submit();
}

function atualizaFuncionarioAjax(codigo, acao) {
    if (acao == 'incluir') {
    	func_id_tipo_inscricao = $('#func_id_tipo_inscricao').val();
        func_nome = $('#func_nome').val();
        func_email = $('#func_email').val();
        func_sexo = $('#func_sexo').val();

        parametros = 'acao=' + acao +
            '&func_id_tipo_inscricao=' + func_id_tipo_inscricao +
            '&func_nome=' + func_nome +
            '&func_email=' + func_email +
            '&func_sexo=' + func_sexo;
    } else if (acao == 'excluir') {
       	parametros = 'acao=' + acao + '&codigo=' + codigo;
    }

	$.ajax({
		type: "POST",
		url: "atualizaGradeFuncionariosAjax.php",
		data: parametros,
        success: analisarRespostaFuncionario
	});
}

function analisarRespostaFuncionario(txt) {
    mensagem = txt;
    
    if (mensagem.toString().substr(0, 7) == 'Atencao') {
        alert(mensagem);

        return false;
    } else {
        $('#div_grade_funcionarios').html(txt);
        $('#div_grade_funcionarios').show();
        
        $('#frmFunc')[0].reset();
        $('#func_nome').focus();
    }

    return true;
}