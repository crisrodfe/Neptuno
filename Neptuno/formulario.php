<?php

    session_name("xxx");
    session_start();
    //Si se intenta acceder a esta página sin un usuario o con un usuario que no ha sido validado,
    //se redirigirá a la página de login.
    if(!isset($_SESSION["login"]))
    {
        header("Location:login.php");      
        exit;
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Proyecto Neptuno</title>
        <link href="css/index.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <h2>SINCA S.A</h2>
        <p>usuario:  <?php echo $_SESSION["login"] ?><p>
        <div>
            Consulta de Productos
        </div>   
        <div class="consulta">
                <label for="producto">tipo de producto:</label>
                <select id="lstProductos"name="producto">
                    <option value="0">&lt;Todos los tipos&gt;</option>
                </select></br>
                
                <label for="proveedor">proveedor:</label> 
                <select id="lstProveedores" type="text" name="proveedor">
                    <option value="0">&lt;Todos los proveedores&gt;</option>
                </select></br>
                
                <input id="chkStock" type="checkbox" name="soloStock" >solo producto en stock</input>
                
                <input id="btnConsultar" type="button" value="Consultar">
                
                <a href="logout.php">desconectar</a>
        </div>
        <table id="tabProductos">
            <thead>
                <tr>
                    <th class="id">Id</th>
                    <th class="nombre">Nombre</th>
                    <th class="stock">Stock</th>
                    <th class="precio">Precio</th>
                </tr>
            </thead>
            <tbody>               
            </tbody>
        </table>
    </body>
    <script src="js/jquery.js" type="text/javascript"></script>
    <script src="js/formularioJS.js" type="text/javascript"></script>
</html>