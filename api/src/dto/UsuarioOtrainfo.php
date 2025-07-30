<?php namespace Backend\dto;
use Backend\mysql\UsuarioOtrainfoMySqlDAO;
use Backend\sql\Transaction;
use Exception;
/** 
* Clase 'UsuarioBono'
* 
* Esta clase provee una manera de instanciar un objeto de transferencia de datos (dto)
* para la tabla 'UsuarioBono'
* 
* Ejemplo de uso: 
* $UsuarioBono = new UsuarioBono();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class UsuarioOtrainfo
{

    /**
    * Representación de la columna 'usuarioId' de la tabla 'UsuarioOtrainfo'
    *
    * @var string
    */		
	var $usuarioId;

    /**
    * Representación de la columna 'fechaNacim' de la tabla 'UsuarioOtrainfo'
    *
    * @var string
    */
	var $fechaNacim;

    /**
    * Representación de la columna 'direccion' de la tabla 'UsuarioOtrainfo'
    *
    * @var string
    */
	var $direccion;

    /**
    * Representación de la columna 'bancoId' de la tabla 'UsuarioOtrainfo'
    *
    * @var string
    */
	var $bancoId;

    /**
    * Representación de la columna 'tipoCuenta' de la tabla 'UsuarioOtrainfo'
    *
    * @var string
    */
	var $tipoCuenta;

    /**
    * Representación de la columna 'numCuenta' de la tabla 'UsuarioOtrainfo'
    *
    * @var string
    */
	var $numCuenta;

    /**
    * Representación de la columna 'mandante' de la tabla 'UsuarioOtrainfo'
    *
    * @var string
    */
	var $mandante;

    /**
    * Representación de la columna 'anexoDoc' de la tabla 'UsuarioOtrainfo'
    *
    * @var string
    */
	var $anexoDoc;

    /**
     * Representación de la columna 'anexoDoc' de la tabla 'UsuarioOtrainfo'
     *
     * @var string
     */
    var $info1;

    /**
     * Representación de la columna 'anexoDoc' de la tabla 'UsuarioOtrainfo'
     *
     * @var string
     */
    var $info2;

    /**
     * Representación de la columna 'anexoDoc' de la tabla 'UsuarioOtrainfo'
     *
     * @var string
     */
    var $info3;

    /**
     * Representación de la columna 'deporteFavorito' de la tabla 'UsuarioOtrainfo'
     *
     * @var string
     */
    var $deporteFavorito;

    /**
     * Representación de la columna 'casinoFavorito' de la tabla 'UsuarioOtrainfo'
     *
     * @var string
     */
    var $casinoFavorito;

    /**
     * Representación de la columna 'referenteAvalado' de la tabla 'UsuarioOtrainfo'
     *
     * @var string
     */
    var $referenteAvalado;

    /**
     * Representación de la columna 'usuidReferente' de la tabla 'UsuarioOtrainfo'
     *
     * @var string
     */
    var $usuidReferente;

    /**
    * Constructor de clase
    *
    *
    * @param String $usuarioId usuarioId
    *
    * @return no
    * @throws Exception si UsuarioOtrainfo no existe
    *
    * @access public
    * @see no
    * @since no
    * @deprecated no
    */
    public function __construct($usuarioId="")
    {
        if($usuarioId != ""){
            $UsuarioOtrainfoMySqlDAO = new UsuarioOtrainfoMySqlDAO();

            $UsuarioOtrainfo = $UsuarioOtrainfoMySqlDAO->load($usuarioId);

            if ($UsuarioOtrainfo != null && $UsuarioOtrainfo != "") 
            {

                $this->usuarioId = $UsuarioOtrainfo->usuarioId;
                $this->fechaNacim=$UsuarioOtrainfo->fechaNacim;
                $this->direccion=$UsuarioOtrainfo->direccion;
                $this->bancoId=$UsuarioOtrainfo->bancoId;
                $this->tipoCuenta=$UsuarioOtrainfo->tipoCuenta;
                $this->numCuenta=$UsuarioOtrainfo->numCuenta;
                $this->mandante=$UsuarioOtrainfo->mandante;
                $this->anexoDoc=$UsuarioOtrainfo->anexoDoc;

                $this->info1=$UsuarioOtrainfo->info1;
                $this->info2=$UsuarioOtrainfo->info2;
                $this->info3=$UsuarioOtrainfo->info3;
                $this->deporteFavorito = $UsuarioOtrainfo->deporteFavorito;
                $this->casinoFavorito = $UsuarioOtrainfo->casinoFavorito;
                $this->referenteAvalado = $UsuarioOtrainfo->referenteAvalado;
                $this->usuidReferente = $UsuarioOtrainfo->usuidReferente;

            }
            else 
            {
                throw new Exception("No existe " . get_class($this), "53");    
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
     * Obtener el campo fechaNacim de un objeto
     *
     * @return String fechaNacim fechaNacim
     * 
     */ 
    public function getFechaNacim()
    {
        return $this->fechaNacim;
    }

    /**
     * Modificar el campo 'fechaNacim' de un objeto
     *
     * @param String $fechaNacim fechaNacim
     *
     * @return no
     *
     */
    public function setFechaNacim($fechaNacim)
    {
        $this->fechaNacim = $fechaNacim;
    }

    /**
     * Obtener el campo direccion de un objeto
     *
     * @return String direccion direccion
     * 
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Modificar el campo 'direccion' de un objeto
     *
     * @param String $direccion direccion
     *
     * @return no
     *
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;
    }

    /**
     * Obtener el campo bancoId de un objeto
     *
     * @return String bancoId bancoId
     * 
     */
    public function getBancoId()
    {
        return $this->bancoId;
    }

    /**
     * Modificar el campo 'bancoId' de un objeto
     *
     * @param String $bancoId bancoId
     *
     * @return no
     *
     */
    public function setBancoId($bancoId)
    {
        $this->bancoId = $bancoId;
    }

    /**
     * Obtener el campo tipoCuenta de un objeto
     *
     * @return String tipoCuenta tipoCuenta
     * 
     */
    public function getTipoCuenta()
    {
        return $this->tipoCuenta;
    }

    /**
     * Modificar el campo 'tipoCuenta' de un objeto
     *
     * @param String $tipoCuenta tipoCuenta
     *
     * @return no
     *
     */
    public function setTipoCuenta($tipoCuenta)
    {
        $this->tipoCuenta = $tipoCuenta;
    }

    /**
     * Obtener el campo numCuenta de un objeto
     *
     * @return String numCuenta numCuenta
     * 
     */
    public function getNumCuenta()
    {
        return $this->numCuenta;
    }
    
    /**
     * Modificar el campo 'numCuenta' de un objeto
     *
     * @param String $numCuenta numCuenta
     *
     * @return no
     *
     */
    public function setNumCuenta($numCuenta)
    {
        $this->numCuenta = $numCuenta;
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
     * Obtener el campo anexoDoc de un objeto
     *
     * @return String anexoDoc anexoDoc
     * 
     */
    public function getAnexoDoc()
    {
        return $this->anexoDoc;
    }

    /**
     * Modificar el campo 'anexoDoc' de un objeto
     *
     * @param String $anexoDoc anexoDoc
     *
     * @return no
     *
     */
    public function setAnexoDoc($anexoDoc)
    {
        $this->anexoDoc = $anexoDoc;
    }

/**
         * Obtener el campo info1 de un objeto
         *
         * @return String info1 info1
         */
        public function getInfo1()
        {
            return $this->info1;
        }

        /**
         * Modificar el campo 'info1' de un objeto
         *
         * @param String $info1 info1
         *
         * @return no
         */
        public function setInfo1($info1)
        {
            $this->info1 = $info1;
        }

        /**
         * Obtener el campo info2 de un objeto
         *
         * @return String info2 info2
         */
        public function getInfo2()
        {
            return $this->info2;
        }

        /**
         * Modificar el campo 'info2' de un objeto
         *
         * @param String $info2 info2
         *
         * @return no
         */
        public function setInfo2($info2)
        {
            $this->info2 = $info2;
        }

        /**
         * Obtener el campo info3 de un objeto
         *
         * @return String info3 info3
         */
        public function getInfo3()
        {
            return $this->info3;
        }

        /**
         * Modificar el campo 'info3' de un objeto
         *
         * @param String $info3 info3
         *
         * @return no
         */
        public function setInfo3($info3)
        {
            $this->info3 = $info3;
        }

        /**
         * Obtener el campo referenteAvalado de un objeto
         *
         * @return String referenteAvalado referenteAvalado
         */
        public function getReferenteAvalado()
        {
            return $this->referenteAvalado;
        }

        /**
         * Modificar el campo 'referenteAvalado' de un objeto
         *
         * @param String $referenteAvalado referenteAvalado
         *
         * @return no
         */
        public function setReferenteAvalado($referenteAvalado)
        {
            $this->referenteAvalado = $referenteAvalado;
        }

        /**
         * Obtener el campo usuidReferente de un objeto
         *
         * @return String usuidReferente usuidReferente
         */
        public function getUsuidReferente()
        {
            return $this->usuidReferente;
        }

        /**
         * Modificar el campo 'usuidReferente' de un objeto
         *
         * @param String $usuidReferente usuidReferente
         *
         * @return no
         */
        public function setUsuidReferente($usuidReferente)
        {
            $this->usuidReferente = $usuidReferente;
        }


    /** Valida si el usuario es un referente avalado
     *@throws Exception Lanza error 4009 si usuario no es un referente avalado
     *@return int Retorna 1 si el referente sí está avalado
     */
    public function validarReferenteAvalado() {
        if(!$this->getUsuarioId()) {
            throw new Exception("Objeto " . get_class($this) . " no presenta un UsuarioId definido", 4005);
        }
        if(!$this->getReferenteAvalado()) {
            throw new Exception('Usuario no es un referente avalado', 4009);
        }
        return 1;
    }


    /** Encripta el UsuarioId del referente cargado mediante esta clase y retorna una cadena encriptada
     *
     *@return String LinkReferente
     */
    public function generarLinkReferente($cadenaAdicional = '') {
        if(!$this->getUsuarioId()) {
            throw new Exception("Objeto " . get_class($this) . " no presenta un UsuarioId definido", 4005);
        }
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $cadenaFinal = empty($cadenaAdicional) ? $this->getUsuarioId() : $this->getUsuarioId() . '_' . $cadenaAdicional;
        $link = $ConfigurationEnvironment->encrypt_decrypt('encrypt', $cadenaFinal);
        if(!$link) {
            throw new Exception("Error en proceso de encriptacion", 4006);
        }
        return $link;
    }

    /** Desencripta un link recibido mediante parámetro y devuelve el UsuarioId correspondiente
     *
     *@param String Link del referente
     *@return int UsuarioId del referente
     */
    public function identificarLinkReferente(String $link) {
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        $decryptedLink = $ConfigurationEnvironment->encrypt_decrypt('decrypt', $link);
        if(!$decryptedLink) {
            throw new Exception("Error en proceso de encriptacion", 4006);
        }

        $info = [];
        if (str_contains($decryptedLink, '_')) {
            $invitationInfo = explode('_', $decryptedLink);
            $info['usuarioId'] = $invitationInfo[0];
            $info['refinvitacionId'] = $invitationInfo[1];
        }
        else {
            $info['usuarioId'] = $decryptedLink;
        }
        return $info;
    }


    /** Verifica si el correo del usuario ingresado ya se encuentra participando con otros
     * registros/usuarios en los programas de referidos o afiliados del PaisMandante
     * al cual pertenece el Usuario
     * @throws Exception Lanza error 4014 si encuentra una colisión o conflicto.
     * @return int Retorna 1 si no se encontraron coliciones o conflictos.
     */
    public function validarColisionReferidoAfiliado(Usuario $Usuario) {
        $Transaction = new Transaction();
        $BonoInterno = new BonoInterno();

        /** Preparando y ejecutando solicitud */
        $usuarioId = $Usuario->usuarioId;
        $email = $Usuario->login;
        $paisId = $Usuario->paisId;
        $mandante = $Usuario->mandante;
        $sql = 'SELECT usuario.usuario_id, usuario_otrainfo.usuid_referente, registro.afiliador_id FROM usuario INNER JOIN usuario_otrainfo ON (usuario.usuario_id = usuario_otrainfo.usuario_id) INNER JOIN registro ON (usuario.usuario_id = registro.usuario_id) WHERE usuario.login = "' . $email . '" AND usuario.pais_id = ' . $paisId . ' AND usuario.mandante = ' . $mandante . ' AND usuario.usuario_id != ' . $usuarioId;
        $possibleCollisions = $BonoInterno->execQuery($Transaction, $sql);

        /** Verificando posibles colisiones entre el programa de referidos */
        $verifiedCollision = false;
        foreach($possibleCollisions as $register) {
            if(!empty($register->{'usuario_otrainfo.usuid_referente'})) $verifiedCollision = true;
            if(!empty($register->{'registro.afiliador_id'})) $verifiedCollision = true;
            if ($verifiedCollision) break;
        }

        if($verifiedCollision) {
            throw new Exception('Información del usuario ha sido registrada anteriormente para participar en programas de referidos o afiliados', 4014);
        }
        return 1;
    }



}
?>