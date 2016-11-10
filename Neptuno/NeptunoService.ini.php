<?php

include_once "NeptunoClass.php";

if(empty($_POST["tipo"]))
{
    echo -2;
    exit;
}

try
{
    $oNeptuno = new NeptunoClass("localhost","usuario","123456","neptuno");
}
catch(Exception $ex)
{
    echo $ex ->getMessage();
    exit;
} 

define("TIPOPRODUCTO",1);
define("PROVEEDOR",2);
define("PRODUCTO",3);

/*
 * Cada vez que se llame a un método lo haremos dentro de un bloque try/catch.
 * Se mostrarán los mensajes que lancen las excepciones en cada uno de los métodos
 * en caso de haber un error.
 */
switch ($_POST["tipo"]) 
{
    case TIPOPRODUCTO:
        try{
            echo json_encode($oNeptuno ->getTiposProductos());
        }catch(Exception $e){
            echo $e ->getMessage();
        }    
        break;

    case PROVEEDOR:
        try{
            echo json_encode($oNeptuno ->getProveedores());
        }catch(Exception $e){
            echo $e ->getMessage();
        }
        break;
    
    case PRODUCTO:
        //El filtrado de argumentos está implementado en el método getProductos().
        //Pero configuramos mandando 0,0 y true si están vacíos,como dicen las especificaciones.
        
        $intTipoProducto = empty($_POST["producto"])?0:$_POST["producto"];
        $intProveedor = empty($_POST["proveedor"])?0:$_POST["proveedor"];
        $intSoloStock = empty($_POST["soloStock"])?true:($_POST["soloStock"]=="true")?true:false;
        
        try
        {
            echo json_encode($oNeptuno ->getProductos(["tipoProducto" => $intTipoProducto,
                                                   "proveedor" => $intProveedor,
                                                   "soloStock" => $intSoloStock])); 
        }catch(Exception $e){
            echo $e ->getMessage();
        }                
        break;
    default:
        echo -3;
        exit;
        break;
}

?>

