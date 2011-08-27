function validarDouble(valor) {
	// var regEx = /^\d{1,2}\.\d{1,2}$/;
	var regEx = /^\d{2}\.\d{2}$/;
	bValid = valor.match(regEx);
	if (!bValid) {
		alert('incorrect format');
		return false;
	} else
		return true;
}

function validarFormularioIndividual(form) {
	var elementos = form.elements;

	for ( var i = 0; i < elementos.length; i++) {
		if (elementos[i].getAttribute("type") == "text"
				|| elementos[i].getAttribute("type") == "password") {
			if (elementos[i].getAttribute("name") == "email") {
				// VALIDAÇÃO DE EMAIL
				var filter = /^[\w-]+(\.[\w-]+)*@(([\w-]{2,63}\.)+[A-Za-z]{2,6}|\[\d{1,3}(\.\d{1,3}){3}\])$/;

				if (!filter.test(elementos[i].value)) {
					alert("Este endereco de e-mail nao e valido!");
					elementos[i].style.background = "#FFFFCC";
					elementos[i].focus();
					return false;
				}
			} else if (document.getElementById('senha').value != document
					.getElementById('confirmacao_senha').value) {
				alert("As senhas nao conferem");
				document.getElementById('senha').focus();
				return false;
			} else {
				if (elementos[i].value == "") {
					alert("O campo " + elementos[i].getAttribute("name")
							+ " e obrigatorio.");
					elementos[i].focus();
					return false;
				}
			}
		}
	}

	return true;
}

function mascara(o, f) {
	v_obj = o;
	v_fun = f;
	setTimeout("execmascara()", 1);
}

function execmascara() {
	v_obj.value = v_fun(v_obj.value);
}

function apenasNumeros(v) {
	v = v.replace(/\D/g, ""); // Remove tudo o que não é dígito
	return v;
}

// COLOCAR (/) NAS DATAS
function data(v) {
	v = v.replace(/\D/g, ""); // Remove tudo o que não é dígito
	v = v.replace(/(\d{2})(\d)/, "$1/$2"); // Coloca um ponto entre o terceiro
											// e o quarto dígitos
	v = v.replace(/(\d{2})(\d)/, "$1/$2"); // Coloca um ponto entre o terceiro
											// e o quarto dígitos
	v = v.replace(/(\d{4})(\d)/, "$1/$2"); // de novo (para o segundo bloco de
											// números)
	return v;
}

function validaData(campo) {
	if (campo.value != "") {
		erro = 0;
		hoje = new Date();
		anoAtual = hoje.getFullYear();
		barras = campo.value.split("/");
		if (barras.length == 3) {
			dia = barras[0];
			mes = barras[1];
			ano = barras[2];
			resultado = (!isNaN(dia) && (dia > 0) && (dia < 32))
					&& (!isNaN(mes) && (mes > 0) && (mes < 13))
					&& (!isNaN(ano) && (ano.length == 4));
			if (!resultado) {
				alert("Data invalida.");
				campo.focus();
				return false;
			}
		} else {
			alert("Data invalida.");
			campo.focus();
			return false;
		}
		return true;
	}
}

function comparaDatas(data_inicio, data_fim) {
	var data_inicio = (data_inicio).split("/");
	var data_fim = (data_fim).split("/");

	var dataInicioInformada = new Date(data_inicio[2], data_inicio[1] - 1,
			data_inicio[0]);
	var dataFimInformada = new Date(data_fim[2], data_fim[1] - 1, data_fim[0]);

	if (dataFimInformada < dataInicioInformada) {
		alert("Data de inicio nao pode ser superior a data final");
		exit;
	}
}
