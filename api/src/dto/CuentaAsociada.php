<?php 
namespace Backend\dto;

use Backend\mysql\CuentaAsociadaMySqlDAO;
use Exception;

class CuentaAsociada{


/**
* Representación de la columna 'cuentaasociada_id' de la tabla 'cuenta_asociada'
*
* @var string
*/ 

var $CuentaAsociadaId;

/**
* Representación de la columna 'usuarioId' de la tabla 'cuenta_asociada'
*
* @var string
*/ 

var $usuarioId;

/**
* Representación de la columna 'usuarioId2' de la tabla 'cuenta_asociada'
*
* @var string
*/ 

var $usuarioId2;


/**
* Representación de la columna 'fecha_crea' de la tabla 'cuenta_asociada'
*
* @var string
*/ 


var $fechaCrea;



/**
* Representación de la columna 'fecha_modif' de la tabla 'cuenta_asociada'
*
* @var string
*/ 

 var $fechaModif;


 
/**
* Representación de la columna 'usucrea_id' de la tabla 'cuenta_asociada'
*
* @var string
*/ 

var $usucreaId;


/**
* Representación de la columna 'usumodif_id' de la tabla 'cuenta_asociada'
*
* @var string
*/ 

var $usumodifId;


/**
 * Constructor de la clase CuentaAsociada. Asigna valores a los parámetros del DTO
 *
 * @param string $CuentaAsociadaId ID de la cuenta asociada.
 * @param string $usuarioId ID del usuario.
 * @param string $usuarioId2 Segundo ID del usuario.
 * 
 * @throws Exception Si no se encuentra la cuenta asociada o si hay un error en la solicitud.
 */
public function __construct($CuentaAsociadaId="",$usuarioId = "",$usuarioId2 = ""){
    if($CuentaAsociadaId != ""){
        $CuentaAsociadaMySqlDao = new CuentaAsociadaMySqlDAO();
        $CuentasAsociadas = $CuentaAsociadaMySqlDao->load($CuentaAsociadaId);
        if($CuentasAsociadas != "" and $CuentasAsociadas != "null" and $CuentasAsociadas != "NULL"){
            $this->CuentaAsociadaId = $CuentasAsociadas->CuentaAsociadaId;
            $this->usuarioId = $CuentasAsociadas->usuarioId;
            $this->usuarioId2 = $CuentasAsociadas->usuarioId2;
            $this->fechaCrea = $CuentasAsociadas->fechaCrea;
            $this->fechaModif = $CuentasAsociadas->fechaModif;
            $this->usucreaId = $CuentasAsociadas->usucreaId;
            $this->usumodifId = $CuentasAsociadas->usumodifId;
        }else{
            throw new Exception("No existe", 110008);
        }

        
    }else if($usuarioId != ""){

        $CuentaAsociadaMySqlDao = new CuentaAsociadaMySqlDAO();
        $datos = $CuentaAsociadaMySqlDao->queryByUsuarioId($usuarioId)[0];

        if($datos != "" and $datos != "null" and $datos != null){
            $this->CuentaAsociadaId = $datos->CuentaAsociadaId;
            $this->usuarioId = $datos->usuarioId;
            $this->usuarioId2 = $datos->usuarioId2;
            $this->fechaCrea = $datos->fechaCrea;
            $this->fechaModif  = $datos->fechaModif;
            $this->usucreaId = $datos->usucreaId;
            $this->usumodifId = $datos->usumodifId;
        }else{
            throw new Exception("Error Processing Request", 110008);
        }

    }else if ($usuarioId2 != ""){
        
        $CuentaAsociadaMySqlDao = new CuentaAsociadaMySqlDAO();
        $CuentasAsociadas = $CuentaAsociadaMySqlDao->queryByUsuarioId2($usuarioId2);
        $CuentasAsociadas3 =  $CuentasAsociadas[0];

        if($CuentasAsociadas3 != "" and $CuentasAsociadas3 != "null" and $CuentasAsociadas3 != null){
           $this->CuentaAsociadaId = $CuentasAsociadas3->CuentaAsociadaId;
           $this->usuarioId = $CuentasAsociadas3->usuarioId;
           $this->usuarioId2 = $CuentasAsociadas3->usuarioId2;
           $this->fechaCrea = $CuentasAsociadas3->fechaCrea;
           $this->fechaModif = $CuentasAsociadas3->fechaModif;
           $this->usucreaId = $CuentasAsociadas3->usucreaId;
           $this->usumodifId = $CuentasAsociadas3->usumodifId;

        }else{
            throw new Exception("Error Processing Request", 1568468);
        }
    }
 }

    /**
     * Consulta personalizada de cuentas asociadas.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Índice de ordenación.
     * @param string $sord Orden de clasificación (ascendente o descendente).
     * @param int $start Inicio de los registros a consultar.
     * @param int $limit Límite de registros a consultar.
     * @param array $filters Filtros a aplicar en la consulta.
     * @param bool $searchOn Indica si la búsqueda está activada.
     * @param bool $grouping Indica el parámetro respecto al cual agrupar los resultados.
     * @return object Datos obtenidos de la consulta.
     * @throws Exception Si no se obtienen datos de la consulta.
     */
    public function queryCuentaAsociadaCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping){

     $CuentasAsociadasMySqlDAO = new CuentaAsociadaMySqlDAO();
     $datos = $CuentasAsociadasMySqlDAO->queryCuentaAsociadaCustom($select,$sidx,$sord,$start,$limit,$filters,$searchOn,$grouping);

        if($datos != "" and $datos != "null" and $datos != null){
            return $datos;
        }else{
            throw new Exception("Error Processing Request", 1); 
        }

    }


    /**
     * Establece el ID del usuario.
     *
     * @param mixed $UsuarioId El ID del usuario.
     */
    public function setUsuarioId($UsuarioId){
        $this->usuarioId = $UsuarioId;
    }

    /**
     * Obtiene el ID del usuario.
     *
     * @return mixed El ID del usuario.
     */
    public function getUserId(){
        return $this->usuarioId;
    }

    /**
     * Establece el segundo ID del usuario.
     *
     * @param mixed $user El segundo ID del usuario.
     */
    public function setUsuarioId2($user){
        $this->usuarioId2 = $user;
    }

    /**
     * Obtiene el segundo ID del usuario.
     *
     * @return mixed El segundo ID del usuario.
     */
    public function getUserId2(){
        return $this->usuarioId2;
    }

    /**
     * Establece el UsucreaId.
     *
     * @param mixed $value UsucreaId.
     */
    public function SetUsucreaId($value){
        $this->usucreaId = $value;
    }

    /**
     * Obtiene el ID del usuario que creó la cuenta asociada.
     *
     * @return int El ID del usuario creador.
     */
    public function getUsucreaId(){
        return $this->usucreaId;
    }

    /**
     * Establece el ID del usuario que modificó la cuenta asociada.
     *
     * @param int $value1 El ID del usuario modificador.
     */
    public function setUsumodifId($value1){
        $this->usumodifId = $value1;
    }

    /**
     * Obtiene el ID del usuario que modificó la cuenta asociada.
     *
     * @return int El ID del usuario modificador.
     */
    public function getUsumodifId(){
        return $this->usumodifId;
    }


}


?>
