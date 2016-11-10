<?php
/**
 * Clase que encapsula la comunicación con la base de datos.
 * Al instanciarse la clase, se intenta conectar directamente con la base de datos,
 * si no lo consigue, lanza una excepción.
 */
class NeptunoClass
{
    private $conBBDD;
    /**
     * Constructor de la clase.
     * 
     * @param type $host direccion del servidor de la base de datos puede ser localhost,una cadena vacia o una direccion IP. 
     * @param type $usuario nombre del usuario de la base de datos debe ser una cadena de caracteres alfanuméricos
     * @param type $password nombre del usuario de la base de datos debe ser una cadena de caracteres alfanuméricos
     * @param type $baseDatos
     * @throws Exception
     */
    public function __construct($host,$usuario,$password,$baseDatos="neptuno") 
    {
        //Filtrado de los parámetros para establecer la conexión.
        if(!isset($host) || !($host=="" || $host == "localhost" || filter_var($host,FILTER_VALIDATE_IP)))
        {
            throw new Exception("Valor incorrecto en el nombre del host.");
        }         
        if( !preg_match("/^[0-9A-Za-z]{1,10}$/", $password))
        {
            throw new Exception("Valor incorrecto en el valor de la contraseña.");
        } 
       
        if( !preg_match("/^[0-9A-Za-z]{1,15}$/", $usuario))
        {
            throw new Exception("Valor incorrecto en el valor del usuario.");
        } 
        
               
        $this -> conBBDD = new mysqli($host, $usuario, $password, $baseDatos);
      
        if($this -> conBBDD -> connect_errno)
        {
           throw new Exception("Error en la conexión.No se pudo conectar con la base de datos.");
        }
      
        $this -> conBBDD ->set_charset("utf8");
    }
  
    /**
     * Recibe un array como argumento.
     * 
     * @param type $Datos
     * @return type
     * @throws Exception
     */
    public function getProductos($Datos)
    {
        if(!is_array($Datos))
        {
            throw new Exception("Error en la los argumentos de getProductos(),debe ser de tipo Array.");
        }
       
        //Si no viene tipoProducto o proveedor ponemos un cero. Si vienen en los argumentos realizamos el filtrado.
        //Comtemplamos al filtrar que sus valores tienen que ser o un entero o una cadena que represente un entero.
        if(! isset($Datos["tipoProducto"]))
        {
            $Datos["tipoProducto"] = 0; 
        }
        elseif(!preg_match("/[0-9]+/",$Datos["tipoProducto"]) && !is_int($Datos["tipoProducto"]))
        {
            throw new Exception("El valor del tipoProducto en el argumento de getProductos() no es correcto, debe ser un entero.");
        } 
        
        if(! isset($Datos["proveedor"]))
        {
            $Datos["proveedor"] = 0; 
        }
        elseif(!preg_match("/[0-9]+/",$Datos["proveedor"]) && !is_int($Datos["proveedor"]))
        {
            throw new Exception("El valor del proveedor en el argumento de getProductos() no es correcto, debe ser un entero.");
        }
        
        //Si no viene soloStock ponemos true.Además si no es un valor numérico lanzamos una excepción.
        if(! isset($Datos["soloStock"]))
        {
            $Datos["soloStock"] = true; 
        }
        if(isset($Datos["soloStock"]) && !is_bool($Datos["soloStock"]))
        {
            throw new Exception("El valor del soloStock en el argumento de getProductos() no es correcto, debe ser true o false.");
        } 
        
        $aTipoProductos = [];
        //Llamada al procedimiento almacenado en la base de datos.
        //Devolverá una serie de registros que iremos almacenando en un array, que será la variable que devolverá este método.
        $rcsDatos = $this->conBBDD ->query("call getTipoProductos(".$Datos["tipoProducto"].",".$Datos["proveedor"].",'".$Datos["soloStock"]."');");
        while($filFila = $rcsDatos -> fetch_object())
        {
            $aTiposProductos[] = $filFila;
        }         
        
        return $aTiposProductos;
    }   
  
    /**
     * Hace una llamada a un procedimiento almacenado en la base de datos que devuelve todos los registros
     * de la tabla categorías con los campos de idVategoría(lo usaremos para el value del elemento option)
     *  y el NombreCategoria(que lo añadiremos a la lista desplegable de los productos.)
     * @return type
     */
    public function getTiposProductos()
    {
        $aTiposProducto = [];
        try
        {        
            $rcsDatos = $this->conBBDD ->query("call getProductos();");
        }catch(Exception $e){
            echo "Error al llamar a procedimiento almacenado de la base de datos(getProductos())";
        }
        while($filFila = $rcsDatos -> fetch_object())
        {            
            $aTiposProducto[] = $filFila;
        }         
        
        return $aTiposProducto;
    }   
  
    /**
     * Hace una llamada a un procedimiento almacenado de la bd.
     * Devuelve de todos los registro de la tabla proveedores los campos idCompania y NombreCompania.
     * @return type
     */
    public function getProveedores()
    {
        $aProveedores = [];
        try{
            $rcsDatos = $this -> conBBDD -> query("call getProveedores()");
        }catch(Exception $e){
            echo "Error al llamar a procedimiento almacenado de la base de datos(getProveedores())";
        }
        while($filFila = $rcsDatos -> fetch_object())
        {
            $aProveedores[] = $filFila;
        }         
        
        return $aProveedores;
    }
  
    /**
     * Hace una llamada a un procedimiento almacenado de la bd. Este procedimiento devuelve un entero.
     * Un 1 si el usuario y contraseña introducidos por parámetro existe en la bd o un 0 si no es así.
     * 
     * @param type $user
     * @param type $password
     * @return type
     */
    public function isValidUser($user,$password)
    {
        if(!is_string($password) || !is_string($user)){
            throw new Exception("Error en los argumentos de validación del usuario.");
        }
        /*En la anterior entrega hacía la validación a través de una función almacenada en la bd.
        * Esta vez lo he hecho como sugeriste: he implementado un procedimiento almacenado
        * que hace un select con el nombre y usuario introducido por parámetro.
        */
        /*
        *$stmt = $this -> conBBDD -> prepare("SELECT isValidUser('".$user."','".$password."')");   
        *$stmt -> execute();
        *$stmt->bind_result($result);//Almacenamos el parámetro de salida que nos devolverá el procedimiento
        *$stmt->fetch();
        *return $result;
        */
        $rcsDatos = $this -> conBBDD -> query("call isValidUser('".$user."','".$password."')");        
        
        return ($rcsDatos -> num_rows  > 0);
    }   
    
    public function __destruct() 
    {
        $this -> conBBDD -> close();
    }
}



?>