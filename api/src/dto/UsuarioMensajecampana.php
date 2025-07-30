<?php 
namespace Backend\dto;
use Backend\mysql\UsuarioMensajecampanaMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Exception;
/** 
* Clase 'UsuarioMensajecampana'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'UsuarioMensaje'
* 
* Ejemplo de uso: 
* $UsuarioMensaje = new UsuarioMensajecampana();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class UsuarioMensajecampana
{

    /**
    * Representación de la columna 'usumensajeId' de la tabla 'UsuarioMensajecampana'
    *
    * @var string
    */
    public $usumencampanaId;

    /**
    * Representación de la columna 'usufromId' de la tabla 'UsuarioMensajecampana'
    *
    * @var string
    */
    public $usufromId;

    /**
    * Representación de la columna 'usutoId' de la tabla 'UsuarioMensajecampana'
    *
    * @var string
    */
    public $usutoId;

    /**
    * Representación de la columna 'msubject' de la tabla 'UsuarioMensajecampana'
    *
    * @var string
    */
    public $msubject;

    /**
    * Representación de la columna 'body' de la tabla 'UsuarioMensajecampana'
    *
    * @var string
    */
    public $body;

    /**
    * Representación de la columna 'isRead' de la tabla 'UsuarioMensajecampana'
    *
    * @var string
    */
    public $isRead;

    /**
    * Representación de la columna 'parentId' de la tabla 'UsuarioMensajecampana'
    *
    * @var string
    */
    public $parentId;

    /**
    * Representación de la columna 'fechaCrea' de la tabla 'UsuarioMensajecampana'
    *
    * @var string
    */
    public $fechaCrea;

    /**
    * Representación de la columna 'fechaModif' de la tabla 'UsuarioMensajecampana'
    *
    * @var string
    */
    public $fechaModif;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'UsuarioMensajecampana'
    *
    * @var string
    */
    public $usucreaId;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'UsuarioMensajecampana'
    *
    * @var string
    */
    public $usumodifId;

    /**
    * Representación de la columna 'tipo' de la tabla 'UsuarioMensajecampana'
    *
    * @var string
    */
    public $tipo;

    /**
    * Representación de la columna 'externoId' de la tabla 'UsuarioMensajecampana'
    *
    * @var string
    */
    public $externoId;

    /**
    * Representación de la columna 'proveedorId' de la tabla 'UsuarioMensajecampana'
    *
    * @var string
    */
    public $proveedorId;

    /**
     * Representación de la columna 'paisId' de la tabla 'UsuarioMensajecampana'
     *
     * @var string
     */
    public $paisId;

    /**
     * Representación de la columna 'fecha_expiracion' de la tabla 'UsuarioMensajecampana'
     *
     * @var string
     */
    public $fechaExpiracion;

    /**
     * Representación de la columna 'noombre' de la tabla 'UsuarioMensajecampana'
     *
     * @var string
     */
    public $nombre;
    /**
     * Representación de la columna 'descripcion' de la tabla 'UsuarioMensajecampana'
     *
     * @var string
     */
    public $descripcion;
    /**
     * Representación de la columna 't_value' de la tabla 'UsuarioMensajecampana'
     *
     * @var string
     */
    public $t_value;


    /**
     * Representación de la columna 't_value' de la tabla 'UsuarioMensajecampana'
     *
     * @var string
     */
    public $mandante;

    /**
     * Representación de la columna 'fecha_envio' de la tabla 'UsuarioMensajecampana'
     *
     * @var string
     */
    public $fechaEnvio;

    /**
     * Representación de la columna 'usumensajeId' de la tabla 'UsuarioMensajecampana'
     *
     * @var string
     */
    public $usumensajeId;

    /**
     * Representación de la columna 'estado' de la tabla 'UsuarioMensajecampana'
     *
     * @var string
     */
    public $estado;
    /**
    * Constructor de clase
    *
    *
    * @param String $usumencampanaId usumencampanaId
    *
    * @return no
    * @throws Exception si UsuarioMensajecampana no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($usumencampanaId="")
    {

        if ($usumencampanaId != "")
        {

            $this->usumencampanaId = $usumencampanaId;

            $UsuarioMensajecampanaMySqlDAO = new UsuarioMensajecampanaMySqlDAO();

            $UsuarioMensajecampana = $UsuarioMensajecampanaMySqlDAO->load($this->usumencampanaId);

            $this->success = false;

            if ($UsuarioMensajecampana != null && $UsuarioMensajecampana != "")
            {
            
                $this->usumencampanaId = $UsuarioMensajecampana->usumencampanaId;
                $this->usufromId = $UsuarioMensajecampana->usufromId;
                $this->usutoId = $UsuarioMensajecampana->usutoId;
                $this->msubject = $UsuarioMensajecampana->msubject;
                $this->body = $UsuarioMensajecampana->body;
                $this->isRead = $UsuarioMensajecampana->isRead;
                $this->parentId = $UsuarioMensajecampana->parentId;
                $this->fechaCrea = $UsuarioMensajecampana->fechaCrea;
                $this->fechaModif = $UsuarioMensajecampana->fechaModif;
                $this->usucreaId = $UsuarioMensajecampana->usucreaId;
                $this->usumodifId = $UsuarioMensajecampana->usumodifId;
                $this->tipo = $UsuarioMensajecampana->tipo;
                $this->externoId = $UsuarioMensajecampana->externoId;
                $this->proveedorId = $UsuarioMensajecampana->proveedorId;
                $this->paisId = $UsuarioMensajecampana->paisId;
                $this->fechaExpiracion = $UsuarioMensajecampana->fechaExpiracion;
                $this->nombre = $UsuarioMensajecampana->nombre;
                $this->descripcion = $UsuarioMensajecampana->descripcion;
                $this->t_value = $UsuarioMensajecampana->t_value;
                $this->mandante = $UsuarioMensajecampana->mandante;
                $this->fechaEnvio = $UsuarioMensajecampana->fechaEnvio;
                $this->usumensajeId = $UsuarioMensajecampana->usumensajeId;
                $this->estado = $UsuarioMensajecampana->estado;

            } 
            else 
            {
                throw new Exception("No existe " . get_class($this), "22");
            }
        }
    }

    /**
    * Obtener mensaje WS
    *
    *
    *
    * @return Array $data data
    * @throws no
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function getWSMessage()
    {
        $profile_id = array();
        $profile_id['id'] = 26678955;
        $profile_id['unique_id'] = 26678955;
        $profile_id['username'] = 26678955;
        $profile_id['name'] = 'TEST';
        $profile_id['first_name'] = 'TEST';
        $profile_id['last_name'] = 'TEST';
        $profile_id['gender'] = "";
        $profile_id['email'] = "";
        $profile_id['phone'] = "";
        $profile_id['reg_info_incomplete'] = false;
        $profile_id['address'] = "";

        $profile_id["reg_date"] = "";
        $profile_id["birth_date"] = "";
        $profile_id["doc_number"] = "";
        $profile_id["casino_promo"] = null;
        $profile_id["currency_name"] = 'USD';

        $profile_id["currency_id"] = 'USD';
        $profile_id["balance"] = '2200';
        $profile_id["casino_balance"] = '2200';
        $profile_id["exclude_date"] = null;
        $profile_id["bonus_id"] = -1;
        $profile_id["games"] = 0;
        $profile_id["super_bet"] = -1;
        $profile_id["country_code"] = '3';
        $profile_id["doc_issued_by"] = null;
        $profile_id["doc_issue_date"] = null;
        $profile_id["doc_issue_code"] = null;
        $profile_id["province"] = null;
        $profile_id["iban"] = null;
        $profile_id["active_step"] = null;
        $profile_id["active_step_state"] = null;
        $profile_id["subscribed_to_news"] = false;
        $profile_id["bonus_balance"] = 0.0;
        $profile_id["frozen_balance"] = 0.0;
        $profile_id["bonus_win_balance"] = 0.0;
        $profile_id["city"] = "Manizales";
        $profile_id["has_free_bets"] = false;
        $profile_id["loyalty_point"] = 0.0;
        $profile_id["loyalty_earned_points"] = 0.0;
        $profile_id["loyalty_exchanged_points"] = 0.0;
        $profile_id["loyalty_level_id"] = null;
        $profile_id["affiliate_id"] = null;
        $profile_id["is_verified"] = false;
        $profile_id["incorrect_fields"] = null;
        $profile_id["loyalty_point_usage_period"] = 0;
        $profile_id["loyalty_min_exchange_point"] = 0;
        $profile_id["loyalty_max_exchange_point"] = 0;
        $profile_id["active_time_in_casino"] = null;
        $profile_id["last_read_message"] = null;
        $profile_id["unread_count"] = 0;
        $profile_id["last_login_date"] = 1506281782;
        $profile_id["swift_code"] = null;
        $profile_id["bonus_money"] = 0.0;
        $profile_id["loyalty_last_earned_points"] = 0.0;

        $data = array(
            "7372873025621876707" => array(
                "profile" => array(
                    "26678955" => $profile_id,
                ),
            ),

        );

        return $data;
    }

    /**
    * Realizar una consulta en la tabla de UsuarioMensaje 'UsuarioMensaje'
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
    public function getUsuarioMensajesCustom($select, $sidx, $sord, $start, $limit, $filters,$searchOn,$userToSpecific='',$grouping="")
    {

        $UsuarioMensajecampanaMySqlDAO = new UsuarioMensajecampanaMySqlDAO();
        $Usuario = $UsuarioMensajecampanaMySqlDAO->queryUsuarioMensajesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$userToSpecific,$grouping);

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
     * Obtiene mensajes de usuario personalizados con varios parámetros de filtrado y ordenación.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Índice de ordenación.
     * @param string $sord Orden de clasificación (ascendente o descendente).
     * @param int $start Índice de inicio para la paginación.
     * @param int $limit Límite de registros a devolver.
     * @param array $filters Filtros a aplicar en la consulta.
     * @param bool $searchOn Indica si la búsqueda está activada.
     * @param string $userToSpecific (Opcional) Usuario específico para filtrar.
     * @param string $grouping (Opcional) Agrupación de resultados.
     * 
     * @return mixed Resultados de la consulta de mensajes de usuario.
     * @throws Exception Si no se encuentran resultados.
     */
    public function getUsuarioMensajesCustom2($select, $sidx, $sord, $start, $limit, $filters,$searchOn,$userToSpecific='',$grouping="")
    {

        $UsuarioMensajecampanaMySqlDAO = new UsuarioMensajecampanaMySqlDAO();
        $Usuario = $UsuarioMensajecampanaMySqlDAO->queryUsuarioMensajesCustom2($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$userToSpecific,$grouping);

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
     * Obtener el campo usumensajeId de un objeto
     *
     * @return String usumensajeId usumensajeId
     * 
     */
    public function getUsumencampanaId()
    {
        return $this->usumencampanaId;
    }


    /**
     * Obtener el campo usufromId de un objeto
     *
     * @return String usufromId usufromId
     * 
     */
    public function getUsufromId()
    {
        return $this->usufromId;
    }

    /**
     * Modificar el campo 'usufromId' de un objeto
     *
     * @param String $usufromId usufromId
     *
     * @return no
     *
     */
    public function setUsufromId($usufromId)
    {
        $this->usufromId = $usufromId;
    }

    /**
     * Obtener el campo usutoId de un objeto
     *
     * @return String usutoId usutoId
     * 
     */
    public function getUsutoId()
    {
        return $this->usutoId;
    }

    /**
     * Modificar el campo 'usutoId' de un objeto
     *
     * @param String $usutoId usutoId
     *
     * @return no
     *
     */
    public function setUsutoId($usutoId)
    {
        $this->usutoId = $usutoId;
    }

    /**
     * Obtener el campo msubject de un objeto
     *
     * @return String msubject msubject
     * 
     */
    public function getMsubject()
    {
        return $this->msubject;
    }

    /**
     * Modificar el campo 'msubject' de un objeto
     *
     * @param String $msubject msubject
     *
     * @return no
     *
     */
    public function setMsubject($msubject)
    {
        $this->msubject = $msubject;
    }

    /**
     * Obtener el campo body de un objeto
     *
     * @return String body body
     * 
     */
    public function getBody()
    {
        return $this->body;
    }
    
    /**
     * Modificar el campo 'body' de un objeto
     *
     * @param String $body body
     *
     * @return no
     *
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * Obtener el campo isRead de un objeto
     *
     * @return String isRead isRead
     * 
     */
    public function getisRead()
    {
        return $this->isRead;
    }

    /**
     * Modificar el campo 'isRead' de un objeto
     *
     * @param String $isRead isRead
     *
     * @return no
     *
     */
    public function setIsRead($isRead)
    {
        $this->isRead = $isRead;
    }

    /**
     * Obtener el campo parentId de un objeto
     *
     * @return String parentId parentId
     * 
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * Modificar el campo 'parentId' de un objeto
     *
     * @param String $parentId parentId
     *
     * @return no
     *
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;
    }

    /**
     * Obtener el campo fechaCrea de un objeto
     *
     * @return String fechaCrea fechaCrea
     * 
     */
    public function getFechaCrea()
    {
        return $this->fechaCrea;
    }

    /**
     * Modificar el campo 'fechaCrea' de un objeto
     *
     * @param String $fechaCrea fechaCrea
     *
     * @return no
     *
     */
    public function setFechaCrea($fechaCrea)
    {
        $this->fechaCrea = $fechaCrea;
    }

    /**
     * Obtener el campo fechaModif de un objeto
     *
     * @return String fechaModif fechaModif
     * 
     */
    public function getFechaModif()
    {
        return $this->fechaModif;
    }

    /**
     * Modificar el campo 'fechaModif' de un objeto
     *
     * @param String $fechaModif fechaModif
     *
     * @return no
     *
     */
    public function setFechaModif($fechaModif)
    {
        $this->fechaModif = $fechaModif;
    }

    /**
     * Obtener el campo usucreaId de un objeto
     *
     * @return String usucreaId usucreaId
     * 
     */
    public function getUsucreaId()
    {
        return $this->usucreaId;
    }

    /**
     * Modificar el campo 'usucreaId' de un objeto
     *
     * @param String $usucreaId usucreaId
     *
     * @return no
     *
     */
    public function setUsucreaId($usucreaId)
    {
        $this->usucreaId = $usucreaId;
    }

    /**
     * Obtener el campo usumodifId de un objeto
     *
     * @return String usumodifId usumodifId
     * 
     */
    public function getUsumodifId()
    {
        return $this->usumodifId;
    }

    /**
     * Modificar el campo 'usumodifId' de un objeto
     *
     * @param String $usumodifId usumodifId
     *
     * @return no
     *
     */
    public function setUsumodifId($usumodifId)
    {
        $this->usumodifId = $usumodifId;
    }

    /**
     * Obtener el campo tipo de un objeto
     *
     * @return String tipo tipo
     * 
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Modificar el campo 'tipo' de un objeto
     *
     * @param String $tipo tipo
     *
     * @return no
     *
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    /**
     * Obtener el campo externoId de un objeto
     *
     * @return String externoId externoId
     * 
     */
    public function getExternoId()
    {
        return $this->externoId;
    }

    /**
     * Modificar el campo 'externoId' de un objeto
     *
     * @param String $externoId externoId
     *
     * @return no
     *
     */
    public function setExternoId($externoId)
    {
        $this->externoId = $externoId;
    }

    /**
     * Obtener el campo proveedorId de un objeto
     *
     * @return String proveedorId proveedorId
     * 
     */
    public function getProveedorId()
    {
        return $this->proveedorId;
    }

    /**
     * Modificar el campo 'proveedorId' de un objeto
     *
     * @param String $proveedorId proveedorId
     *
     * @return no
     *
     */
    public function setProveedorId($proveedorId)
    {
        $this->proveedorId = $proveedorId;
    }


/**
     * Obtener el campo paisId de un objeto
     *
     * @return String paisId
     */
    public function getPaisId()
    {
        return $this->paisId;
    }

    /**
     * Modificar el campo 'paisId' de un objeto
     *
     * @param String $paisId
     */
    public function setPaisId($paisId)
    {
        $this->paisId = $paisId;
    }

    /**
     * Obtener el campo fechaExpiracion de un objeto
     *
     * @return String fechaExpiracion
     */
    public function getFechaExpiracion()
    {
        return $this->fechaExpiracion;
    }

    /**
     * Modificar el campo 'fechaExpiracion' de un objeto
     *
     * @param String $fechaExpiracion
     */
    public function setFechaExpiracion($fechaExpiracion)
    {
        $this->fechaExpiracion = $fechaExpiracion;
    }

    /**
     * Obtener el campo nombre de un objeto
     *
     * @return String nombre
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Modificar el campo 'nombre' de un objeto
     *
     * @param String $nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    /**
     * Obtener el campo descripcion de un objeto
     *
     * @return String descripcion
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Modificar el campo 'descripcion' de un objeto
     *
     * @param String $descripcion
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    /**
     * Obtener el campo t_value de un objeto
     *
     * @return String t_value
     */
    public function getT_value()
    {
        return $this->t_value;
    }

    /**
     * Modificar el campo 't_value' de un objeto
     *
     * @param String $T_value
     */
    public function setT_value($T_value)
    {
        $this->t_value = $T_value;
    }

    /**
     * Obtener el campo mandante de un objeto
     *
     * @return String mandante
     */
    public function getMandante()
    {
        return $this->mandante;
    }

    /**
     * Modificar el campo 'mandante' de un objeto
     *
     * @param String $Mandante
     */
    public function setMandante($Mandante)
    {
        $this->mandante = $Mandante;
    }

    /**
     * Obtener el campo fechaEnvio de un objeto
     *
     * @return String fechaEnvio
     */
    public function getFechaEnvio()
    {
        return $this->fechaEnvio;
    }

    /**
     * Modificar el campo 'fechaEnvio' de un objeto
     *
     * @param String $FechaEnvio
     */
    public function setfechaEnvio($FechaEnvio)
    {
        $this->fechaEnvio = $FechaEnvio;
    }

    /**
     * Obtener el campo usumensajeId de un objeto
     *
     * @return String usumensajeId
     */
    public function getUsumensajeId()
    {
        return $this->usumensajeId;
    }

    /**
     * Modificar el campo 'usumensajeId' de un objeto
     *
     * @param String $usumensajeId
     */
    public function setUsumensajeId($usumensajeId)
    {
        $this->usumensajeId = $usumensajeId;
    }

    /**
     * Obtener el campo estado de un objeto
     *
     * @return String estado
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Modificar el campo 'estado' de un objeto
     *
     * @param String $estado
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

}
