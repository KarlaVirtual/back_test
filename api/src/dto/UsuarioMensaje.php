<?php namespace Backend\dto;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Exception;
/** 
* Clase 'UsuarioMensaje'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'UsuarioMensaje'
* 
* Ejemplo de uso: 
* $UsuarioMensaje = new UsuarioMensaje();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class UsuarioMensaje
{

    /**
    * Representación de la columna 'usumensajeId' de la tabla 'UsuarioMensaje'
    *
    * @var string
    */
    public $usumensajeId;

    /**
    * Representación de la columna 'usufromId' de la tabla 'UsuarioMensaje'
    *
    * @var string
    */
    public $usufromId;

    /**
    * Representación de la columna 'usutoId' de la tabla 'UsuarioMensaje'
    *
    * @var string
    */
    public $usutoId;

    /**
    * Representación de la columna 'msubject' de la tabla 'UsuarioMensaje'
    *
    * @var string
    */
    public $msubject;

    /**
    * Representación de la columna 'body' de la tabla 'UsuarioMensaje'
    *
    * @var string
    */
    public $body;

    /**
    * Representación de la columna 'isRead' de la tabla 'UsuarioMensaje'
    *
    * @var string
    */
    public $isRead;

    /**
    * Representación de la columna 'parentId' de la tabla 'UsuarioMensaje'
    *
    * @var string
    */
    public $parentId;

    /**
    * Representación de la columna 'fechaCrea' de la tabla 'UsuarioMensaje'
    *
    * @var string
    */
    public $fechaCrea;

    /**
    * Representación de la columna 'fechaModif' de la tabla 'UsuarioMensaje'
    *
    * @var string
    */
    public $fechaModif;

    /**
    * Representación de la columna 'usucreaId' de la tabla 'UsuarioMensaje'
    *
    * @var string
    */
    public $usucreaId;

    /**
    * Representación de la columna 'usumodifId' de la tabla 'UsuarioMensaje'
    *
    * @var string
    */
    public $usumodifId;

    /**
    * Representación de la columna 'tipo' de la tabla 'UsuarioMensaje'
    *
    * @var string
    */
    public $tipo;

    /**
    * Representación de la columna 'externoId' de la tabla 'UsuarioMensaje'
    *
    * @var string
    */
    public $externoId;

    /**
    * Representación de la columna 'proveedorId' de la tabla 'UsuarioMensaje'
    *
    * @var string
    */
    public $proveedorId;

    /**
     * Representación de la columna 'paisId' de la tabla 'UsuarioMensaje'
     *
     * @var string
     */
    public $paisId;

    /**
     * Representación de la columna 'fecha_expiracion' de la tabla 'UsuarioMensaje'
     *
     * @var string
     */
    public $fechaExpiracion;

    /**
     * Representación de la columna 'usumencampana_id de la tabla 'UsuarioMensaje'
     *
     * @var string
     */
    public $usumencampanaId;

    /**
     * Representación de la columna 'valor1' de la tabla 'UsuarioMensaje'
     *
     * @var string
     */
    public $valor1;

    /**
     * Representación de la columna 'valor2' de la tabla 'UsuarioMensaje'
     *
     * @var string
     */
    public $valor2;

    /**
     * Representación de la columna 'valor3' de la tabla 'UsuarioMensaje'
     *
     * @var string
     */
    public $valor3;




    /**
    * Constructor de clase
    *
    *
    * @param String $usumensajeId usumensajeId
    *
    * @return no
    * @throws Exception si UsuarioMensaje no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($usumensajeId="")
    {

        if ($usumensajeId != "") 
        {

            $this->usumensajeId = $usumensajeId;

            $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();

            $UsuarioMensaje = $UsuarioMensajeMySqlDAO->load($this->usumensajeId);

            $this->success = false;

            if ($UsuarioMensaje != null && $UsuarioMensaje != "") 
            {
            
                $this->usumensajeId = $UsuarioMensaje->usumensajeId;
                $this->usufromId = $UsuarioMensaje->usufromId;
                $this->usutoId = $UsuarioMensaje->usutoId;
                $this->msubject = $UsuarioMensaje->msubject;
                $this->body = $UsuarioMensaje->body;
                $this->isRead = $UsuarioMensaje->isRead;
                $this->parentId = $UsuarioMensaje->parentId;
                $this->fechaCrea = $UsuarioMensaje->fechaCrea;
                $this->fechaModif = $UsuarioMensaje->fechaModif;
                $this->usucreaId = $UsuarioMensaje->usucreaId;
                $this->usumodifId = $UsuarioMensaje->usumodifId;
                $this->tipo = $UsuarioMensaje->tipo;
                $this->externoId = $UsuarioMensaje->externoId;
                $this->proveedorId = $UsuarioMensaje->proveedorId;
                $this->paisId = $UsuarioMensaje->paisId;
                $this->fechaExpiracion = $UsuarioMensaje->fechaExpiracion;
                $this->usumencampanaId = $UsuarioMensaje->usumencampanaId;
                $this->valor1 = $UsuarioMensaje->valor1;
                $this->valor2 = $UsuarioMensaje->valor2;
                $this->valor3 = $UsuarioMensaje->valor3;

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

        $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
        $Usuario = $UsuarioMensajeMySqlDAO->queryUsuarioMensajesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$userToSpecific,$grouping);

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
     * Obtiene los mensajes personalizados de campaña para un usuario específico.
     *
     * @param string $usuario El identificador del usuario.
     * @param int $paisId El identificador del país.
     * @param string $fechaCrea (Opcional) La fecha de creación en formato YYYY-MM-DD.
     * @param string $mandante (Opcional) El mandante.
     * @param int $usumandanteId (Opcional) El identificador del usuario mandante.
     * @return mixed Los mensajes personalizados de campaña del usuario.
     * @throws Exception Si no existen mensajes personalizados de campaña para el usuario.
     */
    public function getUsuarioMensajesCustomCampana($usuario,$paisId,$fechaCrea="",$mandante="",$usumandanteId="")
    {

        $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
        $Usuario = $UsuarioMensajeMySqlDAO->queryUsuarioMensajesCustomCampana($usuario,$paisId,$fechaCrea,$mandante,$usumandanteId);

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
     * Obtiene el conteo de mensajes de usuario no leídos.
     *
     * @param string $usuario El nombre de usuario.
     * @param int $paisId El ID del país.
     * @param string $fechaCrea (Opcional) La fecha de creación del mensaje.
     * @param string $mandante (Opcional) El mandante del mensaje.
     * @param int $usumandanteId (Opcional) El ID del usuario mandante.
     * @return int El conteo de mensajes no leídos.
     */
    public function getUsuarioMensajesCountNoRead($usuario,$paisId,$fechaCrea="",$mandante="",$usumandanteId="")
    {

        $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
        return $UsuarioMensajeMySqlDAO->queryUsuarioMensajesCustomCampanaCountNoRead($usuario,$paisId,$fechaCrea,$mandante,$usumandanteId);
    }

    /**
     * Establece el ID del mensaje de usuario.
     *
     * @param mixed $usumensajeId El ID del mensaje de usuario.
     */
    public function setUsumensajeId($usumensajeId) {
        $this->usumensajeId = $usumensajeId;
    }

    /**
     * Obtener el campo usumensajeId de un objeto
     *
     * @return String usumensajeId usumensajeId
     * 
     */
    public function getUsumensajeId()
    {
        return $this->usumensajeId;
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
     * @return string paisId
     */
    public function getPaisId()
    {
        return $this->paisId;
    }

    /**
     * Modificar el campo 'paisId' de un objeto
     *
     * @param string $paisId
     */
    public function setPaisId($paisId)
    {
        $this->paisId = $paisId;
    }

    /**
     * Obtener el campo fechaExpiracion de un objeto
     *
     * @return string fechaExpiracion
     */
    public function getFechaExpiracion()
    {
        return $this->fechaExpiracion;
    }

    /**
     * Modificar el campo 'fechaExpiracion' de un objeto
     *
     * @param string $fechaExpiracion
     */
    public function setFechaExpiracion($fechaExpiracion)
    {
        $this->fechaExpiracion = $fechaExpiracion;
    }

    /**
     * Obtener el campo usumencampanaId de un objeto
     *
     * @return string usumencampanaId
     */
    public function getUsumencampanaId()
    {
        return $this->usumencampanaId;
    }

    /**
     * Modificar el campo 'usumencampanaId' de un objeto
     *
     * @param string $usumencampanaId
     */
    public function setUsumencampanaId($usumencampanaId)
    {
        $this->usumencampanaId = $usumencampanaId;
    }

    /**
     * Obtener el campo valor1 de un objeto
     *
     * @return string valor1
     */
    public function getValor1()
    {
        return $this->valor1;
    }

    /**
     * Modificar el campo 'valor1' de un objeto
     *
     * @param string $valor1
     */
    public function setValor1($valor1)
    {
        $this->valor1 = $valor1;
    }

    /**
     * Obtener el campo valor2 de un objeto
     *
     * @return string valor2
     */
    public function getValor2()
    {
        return $this->valor2;
    }

    /**
     * Modificar el campo 'valor2' de un objeto
     *
     * @param string $valor2
     */
    public function setValor2($valor2)
    {
        $this->valor2 = $valor2;
    }

    /**
     * Obtener el campo valor3 de un objeto
     *
     * @return string valor3
     */
    public function getValor3()
    {
        return $this->valor3;
    }

    /**
     * Modificar el campo 'valor3' de un objeto
     *
     * @param string $valor3
     */
    public function setValor3($valor3)
    {
        $this->valor3 = $valor3;
    }



}
