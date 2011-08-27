$(document).ready(function($) {
	$("#insere_funcionario").click( function() {
		validar_funcionario();
	});

	$("#func_nome").focus();
});

function validar_funcionario() {
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
            func_email: 'Informe um E-mail v&aacute;lido do funcion&aacute;rio'
		},
		submitHandler: function(form) {
			salvar();
		}
	});

    $('#frmFunc').submit();
}

function salvar() {
	$("#div_salvando").show("fast",function() {
		$(this).html("<font color='red'><b>Salvando. Aguarde um momento...</b></font>");
	});

	parametros = $('#frmFunc').serialize();
	$.ajax({
		type: "POST",
		url: "RespAddEmployee.php",
		data: parametros,
		success: analisarResposta
	});

    $().ajaxStop(function(){
		$("#div_salvando").fadeOut("slow",function(){
			$(this).html("");
		});
	});
}

function analisarResposta(txt) {
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
