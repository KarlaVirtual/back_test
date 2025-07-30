<?php namespace Backend\dto;

use Backend\mysql\UsuarioSorteo2MySqlDAO;
use Exception;


/** 
* Clase 'UsuarioSorteo'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'UsuarioSorteo'
* 
* Ejemplo de uso: 
* $UsuarioSorteo = new UsuarioSorteo();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/

class UsuarioSorteo2{
    /**
    * Representación de la columna 'ususorteo2_id' de la tabla 'UsuarioSorteo'
    *
    * @var string
    */

    var $ususorteo2Id;
    
    /**
    * Representación de la columna 'Sorteo2_id' de la tabla 'UsuarioSorteo'
    *
    * @var string
    */

    var $Sorteo2Id;
    

    /**
    * Representación de la columna 'Registro2_id' de la tabla 'UsuarioSorteo'
    *
    * @var string
    */

    var $Registro2Id;

    /**
    * Representación de la columna 'valor' de la tabla 'UsuarioSorteo'
    *
    * @var string
    */


    var $valor;

    /**
    * Representación de la columna 'posicion' de la tabla 'UsuarioSorteo'
    *
    * @var string
    */


    var $posicion;


    /**
    * Representación de la columna 'fecha_crea' de la tabla 'UsuarioSorteo'
    *
    * @var string
    */

    var $fechaCrea;

    /**
    * Representación de la columna 'usucrea_id' de la tabla 'UsuarioSorteo'
    *
    * @var string
    */

    var $usucreaId;


    /**
    * Representación de la columna 'fecha_modif' de la tabla 'UsuarioSorteo'
    *
    * @var string
    */

    var $fechaModif;


    /**
    * Representación de la columna 'estado' de la tabla 'UsuarioSorteo'
    *
    * @var string
    */

    var $estado;


    /**
    * Representación de la columna 'error_id' de la tabla 'UsuarioSorteo'
    *
    * @var string
    */

    var $errorId;

    /**
    * Representación de la columna 'externo_id' de la tabla 'UsuarioSorteo'
    *
    * @var string
    */

    var $externoId;

    /**
    * Representación de la columna 'mandante' de la tabla 'UsuarioSorteo'
    *
    * @var string
    */

    var $mandante;


    /**
    * Representación de la columna 'pais_id' de la tabla 'UsuarioSorteo'
    *
    * @var string
    */

    var $paisId;

    /**
    * Representación de la columna 'premio' de la tabla 'UsuarioSorteo'
    *
    * @var string
    */

    var $premio;

    /**
    * Representación de la columna 'apostado' de la tabla 'UsuarioSorteo'
    *
    * @var string
    */

    var $apostado;

    /**
    * Representación de la columna 'valor_base' de la tabla 'UsuarioSorteo'
    *
    * @var string
    */

    var $valorBase;

    /**
    * Representación de la columna 'valor_base' de la tabla 'UsuarioSorteo'
    *
    * @var string
    */

    var $usumodifId;

    /**
    * Representación de la columna 'id_externo' de la tabla 'UsuarioSorteo'
    *
    * @var string
    */


    var $idExterno;
    /**
    * Representación de la columna 'version' de la tabla 'UsuarioSorteo'
    *
    * @var string
    */

    var $version;
    /**
    * Representación de la columna 'valor_premio' de la tabla 'UsuarioSorteo'
    *
    * @var string
    */

    var $valorPremio;
    /**
    * Representación de la columna 'premio_id' de la tabla 'UsuarioSorteo'
    *
    * @var string
    */

    var $PremioId;
    /**
    * Representación de la columna 'codigo' de la tabla 'UsuarioSorteo'
    *
    * @var string
    */
    var $codigo;


    /**
     * Constructor de la clase UsuarioSorteo2.
     *
     * @param string $ususorteo2Id ID del UsuarioSorteo2.
     * @param string $Registro2Id ID del Registro2.
     * @throws Exception Si no se encuentra el UsuarioSorteo2 con el ID proporcionado.
     *
     * Este constructor inicializa un objeto UsuarioSorteo2 basado en el ID proporcionado.
     * Si se proporciona un ususorteo2Id, se carga el objeto correspondiente desde la base de datos.
     * Si se proporciona un Registro2Id, se carga el primer objeto correspondiente desde la base de datos.
     * Si no se encuentra un objeto correspondiente en la base de datos, se lanza una excepción.
     */
    public function __construct($ususorteo2Id='',$Registro2Id=''){

        if($ususorteo2Id != '' ){


            $UsuarioSorteo2MySqlDAO = new UsuarioSorteo2MySqlDAO();
            $UsuarioSorteo2 = $UsuarioSorteo2MySqlDAO->load($ususorteo2Id);

            if($UsuarioSorteo2 != null and $UsuarioSorteo2 != "null" and $UsuarioSorteo2 != ""){
                $this->ususorteo2Id = $UsuarioSorteo2->ususorteo2Id;
                $this->Sorteo2Id = $UsuarioSorteo2->Sorteo2Id;
                $this->Registro2Id = $UsuarioSorteo2->Registro2Id;
                $this->valor = $UsuarioSorteo2->valor;
                $this->posicion = $UsuarioSorteo2->posicion;
                $this->valorBase = $UsuarioSorteo2->valorBase;
                $this->fechaCrea = $UsuarioSorteo2->fechaCrea;
                $this->usucreaId = $UsuarioSorteo2->usucreaId;
                $this->fechaModif = $UsuarioSorteo2->fechaModif;
                $this->usumodifId = $UsuarioSorteo2->usumodifId;
                $this->estado = $UsuarioSorteo2->estado;
                $this->errorId = $UsuarioSorteo2->errorId;
                $this->idExterno = $UsuarioSorteo2->idExterno;
                $this->mandante = $UsuarioSorteo2->mandante;
                $this->version = $UsuarioSorteo2->version;
                $this->apostado = $UsuarioSorteo2->apostado;
                $this->PremioId = $UsuarioSorteo2->PremioId;
                $this->valorPremio = $UsuarioSorteo2->valorPremio;
                $this->paisId = $UsuarioSorteo2->paisId;
                $this->codigo = $UsuarioSorteo2->codigo;
                $this->premio = $UsuarioSorteo2->premio;
                $this->externoId = $UsuarioSorteo2->externoId;

            }else{
                throw new Exception("no existe".get_class($this),"100098");                
            }

        }else if($Registro2Id != ''){


            $UsuarioSorteo2MySqlDAO = new UsuarioSorteo2MySqlDAO();
            $UsuarioSorteo2 = $UsuarioSorteo2MySqlDAO->queryByRegistroId($Registro2Id);
            $UsuarioSorteo2 = $UsuarioSorteo2[0];
            if($UsuarioSorteo2 != "" and $UsuarioSorteo2 != "null" and $UsuarioSorteo2 != null){
                $this->ususorteo2Id = $UsuarioSorteo2->ususorteo2Id;
                $this->Sorteo2Id = $UsuarioSorteo2->Sorteo2Id;
                $this->Registro2Id = $UsuarioSorteo2->Registro2Id;
                $this->valor = $UsuarioSorteo2->valor;
                $this->posicion = $UsuarioSorteo2->posicion;
                $this->valorBase = $UsuarioSorteo2->valorBase;
                $this->fechaCrea = $UsuarioSorteo2->fechaCrea;
                $this->usucreaId = $UsuarioSorteo2->usucreaId;
                $this->fechaModif = $UsuarioSorteo2->fechaModif;
                $this->usumodifId = $UsuarioSorteo2->usumodifId;
                $this->estado = $UsuarioSorteo2->estado;
                $this->errorId = $UsuarioSorteo2->errorId;
                $this->idExterno = $UsuarioSorteo2->idExterno;
                $this->mandante = $UsuarioSorteo2->mandante;
                $this->version = $UsuarioSorteo2->version;
                $this->apostado = $UsuarioSorteo2->apostado;
                $this->PremioId = $UsuarioSorteo2->PremioId;
                $this->valorPremio = $UsuarioSorteo2->valorPremio;
                $this->paisId = $UsuarioSorteo2->paisId;
                $this->premio = $UsuarioSorteo2->premio;
                $this->codigo = $UsuarioSorteo2->codigo;
                $this->externoId = $UsuarioSorteo2->externoId;
            }else{
                throw new Exception("no existe".get_class($this),"100098");
            }
        }
    }


    /**
     * Obtiene colección de los sorteos de usuario personalizados según los parámetros proporcionados.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Índice de ordenación.
     * @param string $sord Orden de clasificación (ascendente o descendente).
     * @param int $start Índice de inicio para la paginación.
     * @param int $limit Límite de registros a obtener.
     * @param array $filters Filtros a aplicar en la consulta.
     * @param bool $searchOn Indica si la búsqueda está activada.
     * @return mixed Resultados de la consulta de sorteos de usuario personalizados.
     */
    public function getUsuarioSorteosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn){
        $UsuarioSorteo2MySqlDAO = new UsuarioSorteo2MySqlDAO();
        $usuarioSorteo2 = $UsuarioSorteo2MySqlDAO->queryUsuarioSorteosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if($usuarioSorteo2 != "" and $usuarioSorteo2 != null and $usuarioSorteo2 != "null"){
            return $usuarioSorteo2;
        }
        
    }

    /**
     * Consulta si un código existe en la base de datos.
     *
     * @param string $codigo El código a verificar.
     * @return bool Retorna true si el código existe, de lo contrario false.
     */
    public function queryBycode($codigo){
        $UsuarioSorteo2MySqlDAO = new UsuarioSorteo2MySqlDAO();
        $datos = $UsuarioSorteo2MySqlDAO->CheckCode($codigo);
        if(count($datos) > 0){
            return true;
        }else{
           false;
        }
    }

/**
     * Establece el ID del sorteo.
     *
     * @param string $sorteo ID del sorteo.
     */
    public function setSorteo($sorteo){
        $this->Sorteo2Id = $sorteo;
    }

    /**
     * Obtiene el ID del sorteo.
     *
     * @return string ID del sorteo.
     */
    public function getSorteo(){
        return $this->Sorteo2Id;
    }

    /**
     * Establece el ID del registro.
     *
     * @param string $id ID del registro.
     */
    public function setRegistro2($id){
        $this->Registro2Id = $id;
    }

    /**
     * Obtiene el ID del registro.
     *
     * @return string ID del registro.
     */
    public function getRegistro(){
        return $this->Registro2Id;
    }

    /**
     * Establece el valor.
     *
     * @param string $value Valor.
     */
    public function setValor($value){
        $this->valor = $value;
    }

    /**
     * Obtiene el valor.
     *
     * @return string Valor.
     */
    public function getValor(){
        return $this->valor;
    }

    /**
     * Establece la posición.
     *
     * @param string $posicion Posición.
     */
    public function setPosicion($posicion){
        $this->posicion = $posicion;
    }

    /**
     * Obtiene la posición.
     *
     * @return string Posición.
     */
    public function getPosicion(){
        return $this->posicion;
    }

    /**
     * Establece el valor base.
     *
     * @param string $value Valor base.
     */
    public function setValorBase($value){
        $this->valorBase = $value;
    }

    /**
     * Obtiene el valor base.
     *
     * @return string Valor base.
     */
    public function getValorBase(){
        return $this->valorBase;
    }

    /**
     * Establece el ID del usuario creador.
     *
     * @param string $usucrea ID del usuario creador.
     */
    public function setUsuCreaId($usucrea){
        $this->usucreaId = $usucrea;
    }

    /**
     * Obtiene el ID del usuario creador.
     *
     * @return string ID del usuario creador.
     */
    public function getUsucreaId(){
        return $this->usucreaId;
    }

    /**
     * Establece el ID del usuario modificador.
     *
     * @param string $value1 ID del usuario modificador.
     */
    public function setUsumodifId($value1){
        $this->usumodifId = $value1;
    }

    /**
     * Obtiene el ID del usuario modificador.
     *
     * @return string ID del usuario modificador.
     */
    public function getUsumodif(){
        return $this->usumodifId;
    }

    /**
     * Establece el estado.
     *
     * @param string $state Estado.
     */
    public function setEstado($state){
        $this->estado = $state;
    }

    /**
     * Obtiene el estado.
     *
     * @return string Estado.
     */
    public function getEstado(){
        return $this->estado;
    }

    /**
     * Establece el ID del error.
     *
     * @param string $value ID del error.
     */
    public function setErrorId($value){
        $this->errorId = $value;
    }

    /**
     * Obtiene el ID del error.
     *
     * @return string ID del error.
     */
    public function getErrorId(){
        return $this->errorId;
    }

    /**
     * Establece el ID externo.
     *
     * @param string $value1 ID externo.
     */
    public function setIdExterno($value1){
        $this->idExterno = $value1;
    }

    /**
     * Obtiene el ID externo.
     *
     * @return string ID externo.
     */
    public function getIdExterno($value){
        return $this->idExterno;
    }

    /**
     * Establece el mandante.
     *
     * @param string $partner Mandante.
     */
    public function SetMandante($partner){
        $this->mandante = $partner;
    }

    /**
     * Obtiene el mandante.
     *
     * @return string Mandante.
     */
    public function getMandante(){
        return $this->mandante;
    }

    /**
     * Establece la versión.
     *
     * @param string $version Versión.
     */
    public function setVersion($version){
        $this->version = $version;
    }

    /**
     * Establece el ID del país.
     *
     * @param string $country ID del país.
     */
    public function setPaisId($country){
        $this->paisId = $country;
    }

    /**
     * Obtiene el ID del país.
     *
     * @return string ID del país.
     */
    public function getPais(){
        return $this->paisId;
    }

    /**
     * Establece el ID del premio.
     *
     * @param string $premioId ID del premio.
     */
    public function SetPriceId($premioId){
        $this->PremioId = $premioId;
    }

    /**
     * Obtiene el ID del premio.
     *
     * @return string ID del premio.
     */
    public function getPriceId(){
        return $this->PremioId;
    }

    /**
     * Establece el valor del premio.
     *
     * @param string $value Valor del premio.
     */
    public function priceValue($value){
        $this->valorPremio = $value;
    }

    /**
     * Obtiene el valor del premio.
     *
     * @return string Valor del premio.
     */
    public function getPriceValue(){
        return $this->valorPremio;
    }

    /**
     * Establece el premio.
     *
     * @param string $valor Premio.
     */
    public function SetPrice($valor){
        $this->premio = $valor;
    }

    /**
     * Obtiene el premio.
     *
     * @return string Premio.
     */
    public function getPrice(){
        return $this->premio;
    }

    /**
     * Establece el valor apostado.
     *
     * @param string $valor Valor apostado.
     */
    public function setApostado($valor){
        $this->apostado = $valor;
    }

    /**
     * Obtiene el valor apostado.
     *
     * @return string Valor apostado.
     */
    public function getApostado(){
        return $this->apostado;
    }

    /**
     * Establece el código.
     *
     * @param string $code Código.
     */
    public function setCodigo($code){
        $this->codigo = $code;
    }

    /**
     * Obtiene el código.
     *
     * @return string Código.
     */
    public function getCodigo(){
        return $this->codigo;
    }

    /**
     * Establece el ID externo.
     *
     * @param string $valor ID externo.
     */
    public function setExternoId($valor){
        $this->externoId = $valor;
    }

    /**
     * Obtiene el ID externo.
     *
     * @return string ID externo.
     */
    public function getExternoId(){
        return $this->externoId;
    }


    /**
     * Obtiene una lista personalizada de sorteos de usuarios.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Índice de ordenación.
     * @param string $sord Orden de clasificación (ascendente o descendente).
     * @param int $star Índice de inicio para la paginación.
     * @param int $limit Límite de registros a obtener.
     * @param string $filters Filtros a aplicar en la consulta.
     * @param bool $searchOn Indica si la búsqueda está activada.
     * @param bool $withPosition Indica si se debe incluir la posición en el resultado.
     * @return array Lista de sorteos de usuarios.
     * @throws Exception Si ocurre un error al procesar la solicitud.
     */
    public function getusuarioSorteoCustom($select,$sidx,$sord,$star,$limit,$filters,$searchOn,$withPosition=false){
        $UsuarioSorteo2MySqlDAO = new UsuarioSorteo2MySqlDAO();
        $productos = $UsuarioSorteo2MySqlDAO->queryUsuarioSorteosCustom($select,$sidx,$sord,$star,$limit,$filters,$searchOn,$withPosition);
    
        if($productos != null and $productos != ""){
            return $productos;
        }else{
            throw new Exception("Error Processing Request", 110000);
        }
    }


    /**
     * Obtiene una colección de los sorteos de usuario personalizados con posición.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Índice de ordenación.
     * @param string $sord Orden de clasificación (ascendente o descendente).
     * @param int $start Posición inicial para la consulta.
     * @param int $limit Límite de registros a obtener.
     * @param array $filters Filtros a aplicar en la consulta.
     * @param bool $searchOn Indica si la búsqueda está activada.
     * 
     * @return array Resultados de la consulta de sorteos de usuario.
     * @throws Exception Si no existen resultados, lanza una excepción con el código 110000.
     */
    public function getUsuarioSorteoCustomWithPosition($select, $sidx, $sord, $start, $limit, $filters, $searchOn){
        $UsuarioSorteo2MySqlDAO = new UsuarioSorteo2MySqlDAO();
        $Productos1 = $UsuarioSorteo2MySqlDAO->queryUsuarioSorteosCustomWithoutPosition($select, $sidx, $sord, $start, $limit, $filters, $searchOn);
        if($Productos1 != "" and $Productos1 != null){
            return $Productos1;
        }else{
            throw new Exception("No existe", 110000);
        }
    }

}

?>