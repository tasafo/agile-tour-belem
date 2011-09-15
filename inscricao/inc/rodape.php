<div id="formContato">
    <form id="formID" class="formular" action="mail.php" method ="post">
        <p>
            <label>Nome:</label>
            <input type="text" name="name" id="name" value="">
        </p>
        <p>
            <label>E-mail:</label>
            <input type="text" name="email" id="email" value="">
        </p>
        <p>
            <label>Mensagem:</label>
            <textarea name="mensagem" id="mensagem" ROWS="5" COLS="25"></textarea>
        </p>
        <p class="button">
            <input type="button" onclick="Valida(this.form);" title="Enviar" value="">        
        </p>
    </form>
</div>
     
     
