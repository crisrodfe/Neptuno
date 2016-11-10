<?php
    session_name("xxx");
    session_start();
    session_destroy();
    header("Location:login.php");
//Al pulsar en el enlace 'desconectar' de la página de usuario
//se redirige a esta página, que eliminará los datos de sesión y a su vez
// redirige a la página de login.    

