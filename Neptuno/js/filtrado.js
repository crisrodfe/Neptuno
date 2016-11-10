     /**
     * Filtrados de caracter.
     * En el usuario solo permitimos número y letras del alfabeto inglés.
     * En contraseña además dejamos caracteres como @ o # para que sea más compleja.
     * 
     */
    document.getElementById("txtUser").addEventListener("keypress",function(evento)
        {
            if(evento.keyCode == 0 && !/[a-zA-Z0-9]/.test(String.fromCharCode(evento.charCode)))
            {
                evento.preventDefault();
            };
        });
    document.getElementById("txtUser").addEventListener("keypress",function(evento)
        {
            if(evento.keyCode == 0 && !/[a-zA-Z0-9\ªº|!@#~$%&¬=?¿¡+*€]/.test(String.fromCharCode(evento.charCode)))
            {
                evento.preventDefault();
            };
        });
      