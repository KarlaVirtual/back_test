<?php namespace Backend\dto;

use Backend\mysql\Registro2MySqlDAO;
use Exception;


class Registro2{
     /**
   * representacion de la columna registro2_Id de la tabla registro2
   * @var string
   */
  var $Registro2Id;

   /**
   * representacion de la columna cedula de la tabla registro2
   * @var string
   */

    var  $cedula;

    /**
   * representacion de la columna nombre de la tabla registro2
   * @var string
   */

    var $nombre;
    /**
   * representacion de la columna apellido de la tabla registro2
   * @var string
   */

    var $apellido;
    /**
   * representacion de la columna telefono de la tabla registro2
   * @var string
   */

    var $telefono;
    /**
   * representacion de la columna email de la tabla registro2
   * @var string
   */

    /**
   * representacion de la columna fechaCrea de la tabla registro2
   * @var string
   */
    var $email;

    /**
   * representacion de la columna fechaModif de la tabla registro2
   * @var string
   */
    var $fechaCrea;

    /**
   * representacion de la columna tipo de la tabla registro2
   * @var string
   */
    var $fechaModif;

    /**
   * representacion de la columna tipo de la tabla registro2
   * @var string
   */
    var $tipo;
    

    
    /**
     * Constructor de la clase Registro2.
     *
     * @param string $Registro2Id ID del registro2. Si se proporciona, se cargará el registro correspondiente desde la base de datos.
     * @param string $cedula Cédula del registro2. Si se proporciona y no se proporciona $Registro2Id, se buscará el registro correspondiente por cédula.
     * @param string $telefono Teléfono del registro2. Si se proporciona y no se proporciona $Registro2Id ni $cedula, se buscará el registro correspondiente por teléfono.
     * 
     * @throws Exception Si no se encuentra un registro2 correspondiente a los parámetros proporcionados.
     */
    public function __construct($Registro2Id = '',$cedula = '',$telefono=''){
        if($Registro2Id != ""){
            $this->Registro2Id = $Registro2Id;

            $Registro2MySqlDAO = new Registro2MySqlDAO();
            $Registro = $Registro2MySqlDAO->load($Registro2Id);
            if($Registro != "" and $Registro != "null" and $Registro != NULL){
                $this->Registro2Id = $Registro->Registro2Id;
                $this->cedula = $Registro->cedula;
                $this->nombre = $Registro->nombre;
                $this->apellido = $Registro->apellido;
                $this->telefono = $Registro->telefono;
                $this->email = $Registro->email;
                $this->tipo = $Registro->tipo;
                $this->fechaCrea = $Registro->fechaCrea;
                $this->fechaModif = $Registro->fechaModif;
            }else{
                throw new Exception("No existe registro2".get_class($this),"100096");
            }    

        }else if($cedula != ""){
            $Registro2 = new Registro2MySqlDAO();
            $Registro1 = $Registro2->queryByDocument($cedula);
            $Registro1 = $Registro1[0];
            
            if($Registro1 != "" and $Registro1 != null and $Registro1 != "NULL"){
                $this->Registro2Id = $Registro1->Registro2Id;
                $this->cedula = $Registro1->cedula;
                $this->nombre = $Registro1->nombre;
                $this->apellido = $Registro1->apellido;
                $this->telefono = $Registro1->telefono;
                $this->email = $Registro1->email;
                $this->tipo = $Registro1->tipo;
                $this->fechaCrea = $Registro1->fechaCrea;
                $this->fechaModif = $Registro1->fechaModif;
            }else{
                throw new Exception("No existe registro2".get_class($this),"100096");
            }
      
        }else if($telefono != ""){
            $Registro3 = new Registro2MySqlDAO();
            $datos = $Registro3->queryByPhone($telefono);
            $datos = $datos[0];

            if($datos != "" and $datos != "null" and $datos != NULL){
                $this->Registro2Id = $datos->Registro2Id;
                $this->cedula = $datos->cedula;
                $this->nombre = $datos->nombre;
                $this->apellido = $datos->apellido;
                $this->telefono = $datos->telefono;
                $this->email = $datos->email;
                $this->tipo = $datos->tipo;
                $this->fechaCrea = $datos->fechaCrea;
                $this->fechaModif = $datos->fechaModif;
            }else{
                throw new Exception("No existe registro2".get_class($this),"100096");
            }

        }

    }

/**
     * Verifica si existe un registro con la cédula actual.
     *
     * @return bool True si existe un registro con la cédula, false en caso contrario.
     */
    public function verificarCedula(){
        $Registro2MySqlDAO = new Registro2MySqlDAO();
        $datos = $Registro2MySqlDAO->queryByDocument($this->cedula);
        if(count($datos) > 0){
           return true;
        }else{
            return false;
        }
    }

    /**
     * Obtiene registros personalizados de la tabla registro2.
     *
     * @param string $select Columnas a seleccionar.
     * @param string $sidx Índice de ordenación.
     * @param string $sord Orden de clasificación (ascendente o descendente).
     * @param int $start Inicio de los registros.
     * @param int $limit Límite de registros.
     * @param array $filters Filtros a aplicar.
     * @param bool $searchOn Indica si la búsqueda está activada.
     * @param bool $grouping Indica si se agrupan los resultados.
     *
     * @return array Datos obtenidos de la consulta.
     * @throws Exception Si no existen registros.
     */
    public function getRegistro2Custom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping){

        $Registro2 = new Registro2MySqlDAO();

        $datos = $Registro2->queryRegistros2Custom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping);

        if($datos != "null" and $datos != "" and $datos != NULL){
            return $datos;
        }else{
            throw new Exception("No existe".get_class($this),"115");
        }

    }

/**
     * Establece el teléfono del registro2.
     *
     * @param string $phone Teléfono del registro2.
     */
    public function setPhone($phone){
        $this->telefono = $phone;
    }

    /**
     * Obtiene el teléfono del registro2.
     *
     * @return string Teléfono del registro2.
     */
    public function getPhone(){
        return $this->telefono;
    }

    /**
     * Establece la cédula del registro2.
     *
     * @param string $cedula Cédula del registro2.
     */
    public function setDocument($cedula){
        $this->cedula = $cedula;
    }

    /**
     * Establece el nombre del registro2.
     *
     * @param string $Name Nombre del registro2.
     */
    public function setName($Name){
        $this->nombre = $Name;
    }

    /**
     * Obtiene el nombre del registro2.
     *
     * @return string Nombre del registro2.
     */
    public function getName(){
        return $this->nombre;
    }

    /**
     * Establece el tipo del registro2.
     *
     * @param string $valor Tipo del registro2.
     */
    public function setTipo($valor){
        $this->tipo = $valor;
    }

    /**
     * Obtiene el tipo del registro2.
     *
     * @return string Tipo del registro2.
     */
    public function getTipo(){
        return $this->tipo;
    }

    /**
     * Establece el apellido del registro2.
     *
     * @param string $lastName Apellido del registro2.
     */
    public function setApellido($lastName){
        $this->apellido = $lastName;
    }

    /**
     * Obtiene el apellido del registro2.
     *
     * @return string Apellido del registro2.
     */
    public function getApellido(){
        return $this->apellido;
    }

    /**
     * Establece el email del registro2.
     *
     * @param string $value Email del registro2.
     */
    public function setEmail($value){
        $this->email = $value;
    }

    /**
     * Obtiene el email del registro2.
     *
     * @return string Email del registro2.
     */
    public function getEmail(){
        return $this->email;
    }


}



?>