<?php 
namespace Backend\dto;
use Backend\mysql\UsuarioPerfilMySqlDAO;
use Exception;
/** 
* Clase 'UsuarioPerfil'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'UsuarioPerfil'
* 
* Ejemplo de uso: 
* $UsuarioPerfil = new UsuarioPerfil();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class UsuarioPerfil
{

    /**
    * Representación de la columna 'usuarioId' de la tabla 'UsuarioPerfil'
    *
    * @var string
    */
    public $usuarioId;

    /**
    * Representación de la columna 'perfilId' de la tabla 'UsuarioPerfil'
    *
    * @var string
    */
    public $perfilId;

    /**
    * Representación de la columna 'mandante' de la tabla 'UsuarioPerfil'
    *
    * @var string
    */
    public $mandante;

    /**
    * Representación de la columna 'pais' de la tabla 'UsuarioPerfil'
    *
    * @var string
    */
    public $pais;

    /**
    * Representación de la columna 'global' de la tabla 'UsuarioPerfil'
    *
    * @var string
    */
    public $global;

    /**
     * Representación de la columna 'globalMandante' de la tabla 'UsuarioPerfil'
     * @var string
     */
    public $globalMandante;

    /**
     * Representación de la columna 'mandanteLista' de la tabla 'UsuarioPerfil'
     * @var string
     */
    public $mandanteLista;

    /**
     * Representación de la columna 'consultaAgente' de la tabla 'UsuarioPerfil'
     * @var string
     */
    public $consultaAgente;

    /**
     * Representación de la columna 'region' de la tabla 'UsuarioPerfil'
     * @var string
     */
    public $region;

    /**
     * Representación de la columna 'consentimientoEmail' de la tabla 'UsuarioPerfil'
     * @var string
     */
    public $consentimientoEmail;

    /**
     * Representación de la columna 'consentimientoSms' de la tabla 'UsuarioPerfil'
     * @var string
     */
    public $consentimientoSms;

    /**
     * Representación de la columna 'consentimientoTelefono' de la tabla 'UsuarioPerfil'
     * @var string
     */
    public $consentimientoTelefono;

    /**
     * Representación de la columna 'consentimientoPush' de la tabla 'UsuarioPerfil'
     * @var string
     */
    public $consentimientoPush;



    /**
    * Constructor de clase
    *
    *
    * @param String $usuarioId usuarioId
    * @param String $perfilId perfilId
    *
    * @return no
    * @throws Exception si UsuarioPerfil no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($usuarioId="",$perfilId="")
    {
        if ($usuarioId != "") 
        {
            $this->usuarioId = $usuarioId;

            $UsuarioPerfilMySqlDAO = new UsuarioPerfilMySqlDAO();
            $UsuarioPerfil = $UsuarioPerfilMySqlDAO->load($usuarioId);

            if ($UsuarioPerfil != "" && $UsuarioPerfil != null) 
            {

                $this->perfilId = $UsuarioPerfil->perfilId;
                $this->usuarioId = $UsuarioPerfil->usuarioId;
                $this->mandante = $UsuarioPerfil->mandante;
                $this->pais = $UsuarioPerfil->pais;
                $this->global = $UsuarioPerfil->global;
                $this->globalMandante = $UsuarioPerfil->globalMandante;
                $this->mandanteLista = $UsuarioPerfil->mandanteLista;
                $this->consultaAgente = $UsuarioPerfil->consultaAgente;
                $this->region = $UsuarioPerfil->region;
                $this->consentimientoEmail = $UsuarioPerfil->consentimientoEmail;
                $this->consentimientoSms = $UsuarioPerfil->consentimientoSms;
                $this->consentimientoTelefono = $UsuarioPerfil->consentimientoTelefono;
                $this->consentimientoPush = $UsuarioPerfil->consentimientoPush;


            } 
            else 
            {
                throw new Exception("No existe " . get_class($this), "01");

            }

        }
    }





    /**
     * Obtener el campo usuarioId de un objeto
     *
     * @return String usuarioId usuarioId
     * 
     */
    public function getUsuarioId()
    {
        return $this->usuarioId;
    }

    /**
     * Modificar el campo 'usuarioId' de un objeto
     *
     * @param String $usuarioId usuarioId
     *
     * @return no
     *
     */
    public function setUsuarioId($usuarioId)
    {
        $this->usuarioId = $usuarioId;
    }

    /**
     * Obtener el campo perfilId de un objeto
     *
     * @return String perfilId perfilId
     * 
     */
    public function getPerfilId()
    {
        return $this->perfilId;
    }

    /**
     * Modificar el campo 'perfilId' de un objeto
     *
     * @param String $perfilId perfilId
     *
     * @return no
     *
     */
    public function setPerfilId($perfilId)
    {
        $this->perfilId = $perfilId;
    }

    /**
     * Obtener el campo mandante de un objeto
     *
     * @return String mandante mandante
     * 
     */
    public function getMandante()
    {
        return $this->mandante;
    }

    /**
     * Modificar el campo 'mandante' de un objeto
     *
     * @param String $mandante mandante
     *
     * @return no
     *
     */
    public function setMandante($mandante)
    {
        $this->mandante = $mandante;
    }

    /**
     * Obtener el campo pais de un objeto
     *
     * @return String pais pais
     * 
     */
    public function getPais()
    {
        return $this->pais;
    }

    /**
     * Modificar el campo 'pais' de un objeto
     *
     * @param String $pais pais
     *
     * @return no
     *
     */
    public function setPais($pais)
    {
        $this->pais = $pais;
    }
    
    /**
     * Obtener el campo global de un objeto
     *
     * @return String global global
     * 
     */
    public function getGlobal()
    {
        return $this->global;
    }

    /**
     * Modificar el campo 'global' de un objeto
     *
     * @param String $global global
     *
     * @return no
     *
     */
    public function setGlobal($global)
    {
        $this->global = $global;
    }

/**
     * Obtener el campo globalMandante de un objeto
     *
     * @return String globalMandante
     */
    public function getGlobalMandante()
    {
        return $this->globalMandante;
    }

    /**
     * Modificar el campo 'globalMandante' de un objeto
     *
     * @param String $globalMandante
     */
    public function setGlobalMandante($globalMandante)
    {
        $this->globalMandante = $globalMandante;
    }

    /**
     * Obtener el campo mandanteLista de un objeto
     *
     * @return String mandanteLista
     */
    public function getMandanteLista()
    {
        return $this->mandanteLista;
    }

    /**
     * Modificar el campo 'mandanteLista' de un objeto
     *
     * @param String $mandanteLista
     */
    public function setMandanteLista($mandanteLista)
    {
        $this->mandanteLista = $mandanteLista;
    }

    /**
     * Obtener el campo consultaAgente de un objeto
     *
     * @return String consultaAgente
     */
    public function getConsultaAgente()
    {
        return $this->consultaAgente;
    }

    /**
     * Modificar el campo 'consultaAgente' de un objeto
     *
     * @param String $consultaAgente
     */
    public function setConsultaAgente($consultaAgente)
    {
        $this->consultaAgente = $consultaAgente;
    }

    /**
     * Obtener el campo region de un objeto
     *
     * @return String region
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Modificar el campo 'region' de un objeto
     *
     * @param String $region
     */
    public function setRegion($region)
    {
        $this->region = $region;
    }

    /**
     * Obtener el campo consentimientoEmail de un objeto
     *
     * @return String consentimientoEmail
     */
    public function getConsentimientoEmail()
    {
        return $this->consentimientoEmail;
    }

    /**
     * Modificar el campo 'consentimientoEmail' de un objeto
     *
     * @param String $consentimientoEmail
     */
    public function setConsentimientoEmail($consentimientoEmail)
    {
        $this->consentimientoEmail = $consentimientoEmail;
    }

    /**
     * Obtener el campo consentimientoSms de un objeto
     *
     * @return String consentimientoSms
     */
    public function getConsentimientoSms()
    {
        return $this->consentimientoSms;
    }

    /**
     * Modificar el campo 'consentimientoSms' de un objeto
     *
     * @param String $consentimientoSms
     */
    public function setConsentimientoSms($consentimientoSms)
    {
        $this->consentimientoSms = $consentimientoSms;
    }

    /**
     * Obtener el campo consentimientoTelefono de un objeto
     *
     * @return String consentimientoTelefono
     */
    public function getConsentimientoTelefono()
    {
        return $this->consentimientoTelefono;
    }

    /**
     * Modificar el campo 'consentimientoTelefono' de un objeto
     *
     * @param String $consentimientoTelefono
     */
    public function setConsentimientoTelefono($consentimientoTelefono)
    {
        $this->consentimientoTelefono = $consentimientoTelefono;
    }

    /**
     * Obtener el campo consentimientoPush de un objeto
     *
     * @return String consentimientoPush
     */
    public function getconsentimientoPush()
    {
        return $this->consentimientoPush;
    }

    /**
     * Modificar el campo 'consentimientoPush' de un objeto
     *
     * @param String $consentimientoPush
     */
    public function setconsentimientoPush($consentimientoPush)
    {
        $this->consentimientoPush = $consentimientoPush;
    }



    /**
     * Ejecuta una consulta SQL utilizando la transacción proporcionada.
     *
     * @param mixed $transaccion La transacción a utilizar para la consulta.
     * @param string $sql La consulta SQL a ejecutar.
     * @return mixed El resultado de la consulta SQL.
     */
    public function execQuery($transaccion, $sql)
    {

        $UsuarioPerfilMySqlDAO = new UsuarioPerfilMySqlDAO($transaccion);
        $return = $UsuarioPerfilMySqlDAO->querySQL($sql);

        //$return = json_decode(json_encode($return), FALSE);

        return $return;
    }
    
    /**
    * Realizar una consulta en la tabla de UsuarioPerfil 'UsuarioPerfil'
    * de una manera personalizada
    *
    *
    * @param String $select campos de consulta
    * @param String $sidx columna para ordenar
    * @param String $sord orden los datos asc | desc
    * @param String $start inicio de la consulta
    * @param String $limit limite de la consulta
    * @param String $filters condiciones de la consulta 
    * @param boolean $searchOn utilizar los filtros o no
    *
    * @return Array resultado de la consulta
    * @throws Exception si el usuario no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getUsuarioPerfilesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $UsuarioPerfilMySqlDAO = new UsuarioPerfilMySqlDAO();

        $Usuario = $UsuarioPerfilMySqlDAO->queryUsuarioPerfilesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($Usuario != null && $Usuario != "") 
        {
            return $Usuario;
        } 
        else 
        {
            throw new Exception("No existe " . get_class($this), "01");
        }

    }

    /**
     * Obtiene perfiles de usuario personalizados con perfil.
     *
     * @param string $select Columnas a seleccionar.
     * @param string $sidx Índice de ordenación.
     * @param string $sord Orden de clasificación (ascendente o descendente).
     * @param int $start Índice de inicio para la paginación.
     * @param int $limit Límite de registros a obtener.
     * @param array $filters Filtros a aplicar en la consulta.
     * @param bool $searchOn Indica si la búsqueda está activada.
     * @param string $perfil Perfil a buscar.
     * @return mixed Perfiles de usuario obtenidos.
     * @throws Exception Si no se encuentran perfiles de usuario.
     */
    public function getUsuarioPerfilesCustomWithPerfil($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$perfil)
    {

        $UsuarioPerfilMySqlDAO = new UsuarioPerfilMySqlDAO();

        $Usuario = $UsuarioPerfilMySqlDAO->queryUsuarioPerfilesCustomWithPerfil($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$perfil);

        if ($Usuario != null && $Usuario != "")
        {
            return $Usuario;
        }
        else
        {
            throw new Exception("No existe " . get_class($this), "01");
        }

    }

    /**
    * Realizar una consulta en la tabla de UsuarioPerfil 'UsuarioPerfil'
    * de una manera personalizada
    *
    *
    * @param String $select campos de consulta
    * @param String $sidx columna para ordenar
    * @param String $sord orden los datos asc | desc
    * @param String $start inicio de la consulta
    * @param String $limit limite de la consulta
    * @param String $filters condiciones de la consulta 
    * @param boolean $searchOn utilizar los filtros o no
    *
    * @return Array resultado de la consulta
    * @throws Exception si el usuario no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getChilds($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $UsuarioPerfilMySqlDAO = new UsuarioPerfilMySqlDAO();

        $Usuario = $UsuarioPerfilMySqlDAO->queryChilds($select, $sidx, $sord, $start, $limit, $filters, $searchOn);

        if ($Usuario != null && $Usuario != "") 
        {
            return $Usuario;
        } 
        else 
        {
            throw new Exception("No existe " . get_class($this), "01");
        }

    }

}
