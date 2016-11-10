<?php
    //Nombramos y abrimos la sesión.
    session_name("xxx");
    session_start();
    
    //Si la sesión ya está iniciada con el usuario validado reenviamos directamente a la página del formulario.
    if(isset($_SESSION["login"]))
    {
        header("Location:formulario.php");
        exit;
    } else
    
    //Si se ha mandado los datos de los campos usuario y contraseña intentamos conectar con la base de datos.    
    if(isset($_POST["usuario"]) && isset($_POST["password"]))
    {
        try//Si no se consigue conectar recogemos la excepción y mostramos un mensaje al usuario.
        {
            include_once "inc/NeptunoConstants.inc.php";
            include "NeptunoClass.php";
            $oNeptuno = new NeptunoClass(DBHOST, DBUSER, DBPASS,DBNAME);
            
            try{//Una vez conectados a la base de datos validamos el usuario.
                //Si la validacion no se puede llevar a cabo (por ejemplo que el valor de user sea un entero),
                //recogemos la excepción y mandamos un mensaje para el usuario.
                if($oNeptuno -> isValidUser($_POST["usuario"],$_POST["password"]) == 1)
                {
                    header("Location:formulario.php");
                    $_SESSION["login"] = $_POST["usuario"];
                    exit;
                } 
            }catch(Exception $e)
            {
                //Especificamos que el error se ha producido en el proceso de validación.
                //En el método isValidUser es donde comprobamos que los dos valores sean cadenas.
                echo "Error en el proceso de validación.";
                exit;
            }    
            
        } catch (Exception $ex)
        {
            //Especificamos que el error ha ocurrido al intentar conectarnos a la base de datos.
            echo "Error en la conexión a la base de datos.";
            exit;
        }
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
        <h1>SINCA S.A</h1>
        <div class="login">
            <form action=<?php  echo $_SERVER["PHP_SELF"];?> method="POST">
                <label for="user">usuario:</label>
                <input id="txtUser" type="text" name="usuario" maxlength="10"
                       pattern="[a-zA-Z0-9]{1,10}" required 
                       title="Solo permitidos números y letras del alfabeto inglés.Máximo 10 caracteres."></br>
                
                <label for="password">password:</label> 
                <input id="txtPassword" type="text" name="password" maxlength="15"
                       pattern="[a-zA-Z0-9\ªº|!@#~$%&¬=?¿¡+*€]{1,15}" required
                       title="Solo permitidos números y letras del alfabeto inglés.Máximo 15 caracteres."></br>               
                <input type="submit" value="Login">
            </form>    
        </div>
    </body>
    <script src="js/filtrado.js" type="text/javascript"></script>
</html>
