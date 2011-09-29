$(document).ready(function($) {
    $("#mudar").click( function() {
        mudar_nome_instituicao();
    });
    
    $("#novo_nome").focus();
});

function mudar_nome_instituicao() {
    if ($("#novo_nome").val() == "") {
        alert("Informe o novo nome");
        $("#novo_nome").focus();
        return false;
    }
        
    jConfirm("Deseja realizar a troca do nome?", null, function(r) {
        if (r == true) {
            parametros = $('#form').serialize();
            $.ajax({
                type: "POST",
                url: "trocarNomeInstituicao.php",
                dataType: "xml",
                data: parametros,
                success: analisarRespostaTrocar
            });
        }
    });
}

function analisarRespostaTrocar(xml) {
    erro = $('erro', xml).text();
    if (erro) {
        alert(erro);
        
        return false;
    } else {
        alert($('msg', xml).text());
    }

    return true;
}