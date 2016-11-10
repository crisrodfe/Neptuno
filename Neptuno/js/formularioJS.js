window.onload = function()
{      
  //Inicio de relleno de las listas desplegables a través de llamadas al servicio NeptunoService.
    //Para cada registro creamos un nuevo elemento Option. El texto será el campo con el nombre
    //y el value el campo de id (tanto en el caso de los productos como de los proveedores.)
    $.post("NeptunoService.ini.php",{tipo:1},function(datos)
    {
        
        for (var strIndice in datos)
        {
            var oOpcion = new Option(datos[strIndice].NombreCategoria,datos[strIndice].idCategoria);
            document.getElementById("lstProductos").appendChild(oOpcion);
        }
    },"json");
    
    $.post("NeptunoService.ini.php",{tipo:2},function(datos)
    {
        for (var strIndice in datos)
        {
            var oOpcion = new Option(datos[strIndice].NombreCompania,datos[strIndice].idProveedor);
            document.getElementById("lstProveedores").appendChild(oOpcion);
        }
    },"json");
  //Fin de relleno de listas desplegables.
      
    hideTableShowButton();//Ejecutamos la función para que de entrada el usuario no vea la tabla hasta que haga una consulta.
};

    //Cada vez que se pulse el botón consultar haremos una peticón al servicio para que nos devuelva los productos
    //que cumplan las condiciones especificadas según el value del elemento de las listas desplegables seleccionado
    //y si está o no marcado el checkbox.
    document.getElementById("btnConsultar").onclick = function()
    {
        $.post("NeptunoService.ini.php",
        {tipo:3,
         producto:document.getElementById("lstProductos").value,//Cogemos los valores de las dos listas
         proveedor:document.getElementById("lstProveedores").value,
         soloStock:(document.getElementById("chkStock").checked)?true:false},//Si el checkbox esta marcado mandamos un 1,si no un 0.
        function(datos)
        {
            //Eliminamos los datos del cuerpo de la tabla(si los hubiera)
            $("#tabProductos > tbody tr").remove(); 
            document.getElementById("tabProductos").style.display = "table";//Mostramos la tabla
            var oTabla = document.getElementById("tabProductos");  
            
            
            var oTableBody = oTabla.getElementsByTagName('tbody')[0];
            for (var strIndice in datos)
            {
                //Por cada registro creamos una nueva fila y la añadimos al cuerpo de la tabla.
                var row = oTableBody.insertRow(strIndice);
                //Cada columna contiene el texto de cada uno de los campos del registro.
                row.insertCell(0).innerHTML = datos[strIndice].idProducto;
                row.insertCell(1).innerHTML = datos[strIndice].NombreProducto;
                row.insertCell(2).innerHTML = datos[strIndice].UnidadesEnExistencia;
                row.insertCell(3).innerHTML = new Intl.NumberFormat("de-DE", { maximumFractionDigits: 2,minimumFractionDigits: 2 }).format(datos[strIndice].PrecioUnidad);   //con es-ES no aparecen los miles separados
            }    

        },"json");
        
        this.disabled = true;//Deshabilitamos el botón(se volverá a habilitar cuando se haga click sobre alguna
                            // de las listas o sobre el check box.)
    };
      
    //Una vez que se haya mostrado una tabla con una consulta el botón queda deshabilitado
    //Queremos que si hay un cambio en las listas o en el checkbox se habilite el botón y desaparezcan los datos mostrados.
    //Es lo que hará la siguiente función:
    function hideTableShowButton()
    {
        //En la corrección me ponías que por qué usaba display = none en lugar de eliminar toda la tabla,y
        //es simplemente porque tengo la cabecera de la tabla ya definida en el html,si borro toda la tabla
        //tendría que implementar la cabecera desde aquí con JavaScript.
        document.getElementById("tabProductos").style.display = "none";        
        document.getElementById("btnConsultar").disabled = false;
        
    }
    //Atribuimos la función creada con el evento onclick de las listas y del checkbox.
    document.getElementById("lstProductos").onclick = function(){hideTableShowButton();};
    document.getElementById("lstProveedores").onclick = function(){hideTableShowButton();};
    document.getElementById("chkStock").onclick = hideTableShowButton;