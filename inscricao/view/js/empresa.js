$(document).ready(function($) {
	$("#gravar").click( function() {
		validar_empresa();
	});
    
	$("#insere_funcionario").click( function() {
		validar_funcionario();
	});

	$("#razao_social").focus();
});

function validar_empresa() {
    var container = $('#div_msg_empresa');

	$('#form').validate({
        errorContainer: container,
		errorLabelContainer: $("ol", container),
		wrapper: 'li',
		meta: "validate",
		
		rules: {
			razao_social: {
				required: true
			},
			nome_fantasia: {
				required: true
			},
            nome_responsavel: {
				required: true
			},
			cnpj:{
				required: true,
				verificaCNPJ: true
			},
			email:{
				required: true,
				email: true
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
		
		messages: {
            razao_social: 'Informe a Raz&atilde;o Social',
            nome_fantasia: 'Informe o Nome Fantasia',
            nome_responsavel: 'Informe o Nome do Respons&aacute;vel',
            cnpj: 'Informe um CNPJ v&aacute;lido',
            email: 'Informe um E-mail v&aacute;lido',
            ddd: 'Informe o DDD',
            telefone: 'Informe o Telefone',
            endereco: 'Informe o Endere&ccedil;o',
            numero: 'Informe o n&uacute;mero',
            bairro: 'Informe o Bairro',
            cep: 'Informe o CEP',
            cidade: 'Informe a Cidade'
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
		$(this).html("<font color='red'><b>Salvando. Aguarde um momento...</b></font>");
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
			func_cpf:{
				required: true,
				verificaCPF: true
			},
			func_email:{
				required: true,
				email: true
			},
            func_nome_cracha: {
				required: true
			},
            func_ddd: {
				required: true,
				digits: true,
                minlength:2
			},
            func_telefone: {
				required: true,
				digits: true,
                minlength: 8
			}
		},
		// define messages para cada campo
		messages: {
            func_nome: 'Informe o nome do funcion&aacute;rio',
            func_cpf: 'Informe um CPF v&aacute;lido do funcion&aacute;rio',
            func_email: 'Informe um E-mail v&aacute;lido do funcion&aacute;rio',
            func_nome_cracha: 'Informe a Identifica&ccedil;&atilde;o para o Crach&aacute; do funcion&aacute;rio',
            func_ddd: 'Informe o DDD do telefone funcion&aacute;rio',
            func_telefone: 'Informe o Telefone do funcion&aacute;rio'
		},
		submitHandler: function(form) {
			atualizaFuncionarioAjax(0, 'incluir');
		}
	});

    $('#frmFunc').submit();
}

function atualizaFuncionarioAjax(codigo, acao) {
    if (acao == 'incluir') {
        func_categoria_inscricao = $('#func_categoria_inscricao').val();
        func_nome = $('#func_nome').val();
        func_cpf = $('#func_cpf').val();
        func_email = $('#func_email').val();
        func_nome_cracha = $('#func_nome_cracha').val();
        func_ddd = $('#func_ddd').val();
        func_telefone = $('#func_telefone').val();
        func_sexo = $('#func_sexo').val();

        parametros = 'acao=' + acao +
            '&func_categoria_inscricao=' + func_categoria_inscricao +
            '&func_nome=' + func_nome +
            '&func_cpf=' + func_cpf +
            '&func_email=' + func_email +
            '&func_nome_cracha=' + func_nome_cracha +
            '&func_ddd=' + func_ddd +
            '&func_telefone=' + func_telefone +
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