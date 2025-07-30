<?php

/**
 * Procesamiento de documentos y carga de documentos mediante API externas.
 *
 * @category Seguridad
 * @package  Auth
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-03-11
 */

namespace Backend\integrations\auth;

use Backend\dto\Categoria;
use Backend\dto\CategoriaProducto;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Descarga;
use Backend\dto\DocumentoUsuario;
use Backend\dto\Mandante;
use Backend\dto\Pais;
use Backend\dto\Producto;
use Backend\dto\ProductoDetalle;
use Backend\dto\Proveedor;
use Backend\dto\PuntoVenta;
use Backend\dto\Registro;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransprodLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioLog2;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioMensaje;
use Backend\dto\UsuarioOtrainfo;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioVerificacion;
use Backend\dto\VerificacionLog;
use Backend\integrations\poker\ESAGAMING;
use Backend\mysql\DocumentoUsuarioMySqlDAO;
use Backend\mysql\ProductoMySqlDAO;
use Backend\mysql\RegistroMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\UsuarioLog2MySqlDAO;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\UsuarioOtrainfoMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\mysql\UsuarioVerificacionMySqlDAO;
use Backend\mysql\VerificacionLogMySqlDAO;
use Exception;

/**
 * AUCOSERVICES
 *
 * La clase AUCOSERVICES en PHP maneja varias funcionalidades relacionadas con la verificación de usuarios.
 * Procesamiento de documentos y carga de documentos mediante API externas.
 */
class AUCOSERVICES
{
    /**
     * Metodo de la solicitud.
     *
     * @var string $method .
     */
    private $method = "";

    /**
     * URL base de la API
     *
     * @var string $url .
     */
    private $url = "";

    /**
     * URL base de la API en entorno de desarrollo
     *
     * @var string $urlDev .
     */
    private $urlDev = "https://dev.auco.ai/v1/ext/";

    /**
     * URL base de la API en entorno de produccion.
     *
     * @var string $urlProd .
     */
    private $urlProd = "https://api.auco.ai/v1/ext/";

    /**
     * Nombre de usuario para la API.
     *
     * @var string $user .
     */
    private $user = "";

    /**
     * Nombre de usuario para la API en entorno de desarrollo.
     *
     * @var string $userDev .
     */
    private $userDev = "";

    /**
     * Nombre de usuario para la API en entorno de produccion.
     *
     * @var string $userProd .
     */
    private $userProd = "";

    /**
     * Contraseña para la API.
     *
     * @var string $password .
     */
    private $password = "";

    /**
     * Contraseña para la API en entorno de desarrollo.
     *
     * @var string $passwordDev
     */
    private $passwordDev = "";

    /**
     * Contraseña para la API en entorno de produccion.
     *
     * @var string $passwordProd .
     */
    private $passwordProd = '';

    /**
     * Extension de la URL para la API.
     *
     * @var string $extencion .
     */
    private $extencion;

    /**
     * Token de autenticacion.
     *
     * @var string $token .
     */
    private $token = "";

    /**
     * Clave privada de la API.
     *
     * @var string $keyPrivate .
     */
    private $keyPrivate = "";

    /**
     * Clave publica de la API.
     *
     * @var string $keyPublic .
     */
    private $keyPublic = '';

    /**
     * Función constructora.
     *
     * La función constructora establece diferentes valores de URL, usuario y contraseña dependiendo de si el
     * es de desarrollo o de producción.
     *
     * No devuelven ningún valor, el constructor se encargan de inicializar un objeto, En lugar de @return.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->url = $this->urlDev;
            $this->user = $this->userDev;
            $this->password = $this->passwordDev;
        } else {
            $this->url = $this->urlProd;
            $this->user = $this->userProd;
            $this->password = $this->passwordProd;
        }
    }

    /**
     * Maneja la verificación de usuarios basada en varias condiciones.
     *
     * La función `validate` maneja la verificación de usuarios basada en varias condiciones
     * tales como país, tipo de usuario y tipo de documento, y registra el proceso de verificación.
     *
     * @param object  $Usuario               Objeto que contiene varias propiedades relacionadas con un usuario, como:
     *                                       - [string|int] mandante: Mandante del usuario.
     *                                       - [int] paisId: ID del país del usuario.
     *                                       - [int] UsuarioId: ID del usuario.
     *                                       - [string] nombre: Nombre del usuario.
     *                                       - [string] celular: Número de celular del usuario.
     *                                       - [string] email: Correo electrónico del usuario.
     *                                       Se utiliza para determinar configuraciones y claves en función del usuario.
     * @param integer $UsuarioVerificacionId ID del proceso de verificación del usuario. Se utiliza para rastrear y
     *                                       gestionar el proceso de verificación para un usuario específico.
     *
     * @return object Objeto de respuesta con información sobre el proceso de verificación, como el código de verificación, la clave y otros detalles.
     */
    public function validate($Usuario, $UsuarioVerificacionId)
    {

        $Pais = new Pais($Usuario->paisId);

        $ConfigurationEnvironment = new ConfigurationEnvironment();

        /**
         * Determina el tipo de credenciales a usar basado en el mandante y el país.
         */
        if ($ConfigurationEnvironment->isDevelopment()) {
            if ($Usuario->mandante == "0" && $Pais->paisId == "173") {
                $this->keyPublic = "puk_meBcYEZ9UsEeDkho9mg0XaEuxnzCZuWk";
                $this->keyPrivate = "prk_9s80mqUeh6EwO5wzKaoSCotWcdneB3dE";
                $emailAdmin = "jerson.polo@virtualsoft.tech";
            }
        } else {
            if ($Usuario->mandante == "0" && $Pais->paisId == "173") {
                $this->keyPublic = "puk_knL8eX4GtyGrmcWqOf5Ox8TFgTIARAZw";
                $this->keyPrivate = "prk_LEhkLliJiTUh6C4jm8wlESxsRtIFm5XU";
                $emailAdmin = "verificacionesdoradobetperu@virtualsoft.tech";
            }

            if ($Usuario->mandante == "8" && $Pais->paisId == "66") {
                $this->keyPublic = "puk_NaqIsQxKxQFkeE280s7S5RN6AJTBODjO";
                $this->keyPrivate = "prk_eDZegi19sI7v92VrHCAThVWEQqxudlrB";
                $emailAdmin = "verificacionesecuabet@virtualsoft.tech";
            }

            if ($Usuario->mandante == "18" && $Pais->paisId == "173") {
                $this->keyPublic = "puk_dj1UVGcyMk6YNbu4bcZlhV6IouYp1cW6";
                $this->keyPrivate = "prk_i7tekDvj0XPeM1MSXR6xOaRrPyFW727v";
                $emailAdmin = "adm_riesgo.peru@gangabet.com";
            }

            if ($Usuario->mandante == "18" && $Pais->paisId == "46") {
                $this->keyPublic = "puk_dtXhdD0HZbkEO1WeoKvup4qnfS8nJ17w";
                $this->keyPrivate = "prk_OZmr3IXLESBIraIu3aUmRoMHLy3rB1wV";
                $emailAdmin = "oficina.chile@gangabet.com";
            }

            if ($Usuario->mandante == "18" && $Pais->paisId == "33") {
                $this->keyPublic = "puk_3bvuCOAtKbytvU4CHHTmBnNyheoUu4tY";
                $this->keyPrivate = "prk_giXkWU9e32nrFS9dtPEvmqerAQnIyeMn";
                $emailAdmin = "info.gangabetbrasil@gmail.com";
            }

            if ($Usuario->mandante == "18" && $Pais->paisId == "146") {
                $this->keyPublic = "puk_sad82SbC5uZic6k9L5OhgOOjEdfvznO0";
                $this->keyPrivate = "prk_nxkDtAsX5Snxo3Xn9X5sdpLd6yQQzQEZ";
                $emailAdmin = "felix.navarro@gangabet.com";
            }

            if ($Usuario->mandante == "18" && $Pais->paisId == "232") {
                $this->keyPublic = "puk_X2KnCI3R47hAdcSmouteTmCXfU5IMP3x";
                $this->keyPrivate = "prk_e2X0mlJXPS3fSKvvnBeU5DqOxrgYJ8RZ";
                $emailAdmin = "oficina.mexico@gangabet.mx";
            }

            if ($Usuario->mandante == "19" && $Pais->paisId == "173") {
                $this->keyPublic = "puk_gqXGCIDL45dfC6NgZpwe0UK8Rxqn8xrn";
                $this->keyPrivate = "prk_a0q0EYpI7z4QiTgjXGreM9ttjIAvoErC";
                $emailAdmin = "samantha.cano@virtualsoft.tech";
            }
        }

        /**
         * Determina el tipo de documento (`typeDoc`) basado en el país y el tipo de documento del registro.
         */

        $Registro = new Registro("", $Usuario->usuarioId);
        if ($Pais->paisId == "173") {
            switch ($Registro->tipoDoc) {
                case "C":
                    $typeDoc = "DNI";
                    break;
                case "P":
                    $typeDoc = "PA";
                    break;
                case "E":
                    $typeDoc = "CE";
                    break;
            }
        }

        if ($Pais->paisId == "46") {
            switch ($Registro->tipoDoc) {
                case "C":
                    $typeDoc = "RUT";
                    break;
                case "P":
                    $typeDoc = "PA";
                    break;
                case "E":
                    $typeDoc = "CE";
                    break;
            }
        }

        if ($Pais->paisId == "33") {
            switch ($Registro->tipoDoc) {
                case "C":
                    $typeDoc = "CPF";
                    break;
                case "P":
                    $typeDoc = "PA";
                    break;
                case "E":
                    $typeDoc = "CE";
                    break;
            }
        }

        if ($Pais->paisId == "146") {
            switch ($Registro->tipoDoc) {
                case "C":
                    $typeDoc = "CURP ";
                    break;
                case "P":
                    $typeDoc = "PA";
                    break;
                case "E":
                    $typeDoc = "CE";
                    break;
            }
        }

        if ($Pais->paisId == "232") {
            switch ($Registro->tipoDoc) {
                case "C":
                    $typeDoc = "CI";
                    break;
                case "P":
                    $typeDoc = "PA";
                    break;
                case "E":
                    $typeDoc = "CE";
                    break;
            }
        }

        if ($Pais->paisId == "170") {
            switch ($Registro->tipoDoc) {
                case "C":
                    $typeDoc = "CCPA";
                    break;
                case "P":
                    $typeDoc = "PA";
                    break;
                case "E":
                    $typeDoc = "CE";
                    break;
            }
        }

        $fecha = date("Y-m-d H:i:s");
        $fechaExpex = date("Y-m-d H:i:s", strtotime($fecha . "+3 days"));
        $fechaExpex = date("Y-m-d H:i:s", strtotime($fechaExpex . "+5 minutes"));
        $fechaExpex = str_replace(" ", "T", $fechaExpex);
        $ExpiredDateFormat = date("Y-m-d\TH:i:s", strtotime($fechaExpex));

        /**
         * Crea un array con datos de verificación, lo codifica a JSON, y lo envía a través de una conexión.
         */

        $array = array(
            "country" => $Pais->iso,
            "type" => $typeDoc,
            "identification" => $Registro->cedula,
            "name" => $Registro->nombre1,
            "phone" => "+" . $Pais->prefijoCelular . $Registro->celular,
            "userEmail" => $Registro->email,
            "email" => $emailAdmin,
            "platform" => "web",
            "expiredDate" => $ExpiredDateFormat . ".000Z",
            "custom" => array(
                "useridverification" => $UsuarioVerificacionId
            )
        );

        $this->method = "veriface";

        $response = $this->connectionlinks(json_encode($array));

        $response = json_decode($response);

        /**
         * Registra el log de verificación, actualiza el ID de cuenta Jumio del usuario y confirma las transacciones.
         */

        $VerificacionLog = new VerificacionLog();
        $VerificacionLog->setUsuverificacionId($UsuarioVerificacionId);
        $VerificacionLog->setTipo('USUVERIFICACION');
        $VerificacionLog->setJson(json_encode($response));

        $VerificacionLogMySqlDAO = new VerificacionLogMySqlDAO();
        $VerificacionLogMySqlDAO->insert($VerificacionLog);
        $VerificacionLogMySqlDAO->getTransaction()->commit();

        $UsuarioMySqlDAO = new UsuarioMySqlDAO();
        $Transaction = $UsuarioMySqlDAO->getTransaction();

        $Usuario->setAccountIdJumio($response->code);
        $response->key = $this->keyPublic;
        $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);
        $UsuarioMySqlDAO->update($Usuario);
        $Transaction->commit();

        return $response;
    }

    /**
     * Crea objetos para Mandante.
     *
     * La función Check en PHP crea objetos para Mandante, Pais, y Registro, envía una petición con
     * datos específicos, y devuelve una respuesta JSON con una bandera de éxito y una URL.
     *
     * @param string $token           Token de autenticación.
     * @param object $UsuarioMandante Objeto que contiene información del mandante y usuario, Debe tener las propiedades 'mandante', 'paisId', y 'usuarioMandante'.
     *
     * @return object La función `Check` devuelve un objeto JSON con dos propiedades:
     *  1.  [bool] success: puesto a true
     *  2. [mixed] url: que contiene la URL obtenida de la respuesta del metodo `connectionlink` después de
     *  enviar un array codificado en JSON como parámetro.
     */
    public function Check($token, $UsuarioMandante)
    {

        $this->token = $token;

        $Mandante = new Mandante($UsuarioMandante->mandante);
        $Pais = new Pais($UsuarioMandante->paisId);
        $Registro = new Registro("", $UsuarioMandante->usuarioMandante);

        /**
         * Crea un array con datos de identificación para una solicitud de verificación.
         */
        $array = array(
            "country" => $Pais->iso,
            "identificacion" => $Registro->cedula,
            "identificationCard" => "",
            "photo" => ""
        );

        $this->method = "check";
        $response = $this->connectionlink(json_encode($array));


        $data = array();
        $data["success"] = true;
        $data["url"] = $response->link->url;
        return json_decode(json_encode($data));
    }

    /**
     * La función procesa los datos del usuario.
     *
     * La función procesa los datos del usuario basándose en el estado de su perfil, actualizando la información de verificación
     * y los registros correspondientes.
     *
     * @param array $data La función comprueba el estado del perfil y realiza diferentes acciones basadas en el estado.
     *
     * @return string un mensaje de respuesta basado en el estado de los datos del perfil. Si el estado es
     * «VALIDADO» o «APROBADO», devuelve «Verificación Aprobada». Si el estado es «BLOQUEADO», devuelve
     * devuelve «Verificación Pendiente». Si el estado es «RECHAZADA» o «EXPIRADA», devuelve «Verificación
     * Rechazada».
     */
    public function process($data)
    {
        if ($data->profile->status == "VALIDATED" || $data->profile->status == "APPROVED") {

            /**
             * Procesa los datos de verificación, actualiza el estado del usuario y su información adicional.             *
             */
            $nombre = explode(" ", $data->profile->firstName);
            $apellido = explode(" ", $data->profile->lastName);
            $fechaNacimiento = explode("T", $data->profile->birthdate);
            $fechaNacimiento = $fechaNacimiento[0];
            $Documento = $data->profile->identificationNumber;
            $UsuarioVerificacion = new UsuarioVerificacion($data->profile->custom->useridverification);

            $UsuarioVerificacion->setEstado('A');
            $UsuarioVerificacion->setObservacion('Aprobado por AUCO');

            $UsuarioMySqlDAO = new UsuarioMySqlDAO();
            $Transaction = $UsuarioMySqlDAO->getTransaction();

            $UsuarioVerificacionMySqlDAO = new UsuarioVerificacionMySqlDAO($Transaction);
            $UsuarioVerificacionMySqlDAO->update($UsuarioVerificacion);
            $Transaction->commit();

            $UsuarioMySqlDAO = new UsuarioMySqlDAO();
            $Transaction = $UsuarioMySqlDAO->getTransaction();
            $Usuario = new Usuario($UsuarioVerificacion->getUsuarioId());
            $Registro = new Registro("", $Usuario->usuarioId);

            $UsuarioOtraInfo = new UsuarioOtrainfo($Usuario->usuarioId);
            $Usuario->setVerificado("S");
            $Usuario->setVerifcedulaAnt("S");
            $Usuario->setVerifcedulaPost("S");
            $Usuario->setFechaVerificado(date("Y-m-d H:i:s"));

            $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);
            $UsuarioMySqlDAO->update($Usuario);
            $Transaction->commit();

            /**
             * Registra un log de usuario con la imagen de identificacion frontal y la guarda en un directorio y en Google Cloud Storage.
             */
            $tipo = 'USUDNIANTERIOR';

            $Imagen = $data->profile->identificationCardFront;

            $file_contents1 = addslashes($Imagen);

            $UsuarioLog = new UsuarioLog2();
            $UsuarioLog->setUsuarioId($Usuario->usuarioId);
            $UsuarioLog->setUsuarioIp("");
            $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
            $UsuarioLog->setUsuariosolicitaIp("");
            $UsuarioLog->setUsuarioaprobarId(0);
            $UsuarioLog->setTipo($tipo);
            $UsuarioLog->setEstado("A");
            $UsuarioLog->setValorAntes('');
            $UsuarioLog->setValorDespues('');
            $UsuarioLog->setUsucreaId(0);
            $UsuarioLog->setUsumodifId(0);
            $UsuarioLog->setImagen($file_contents1);
            $UsuarioLog->setSversion($UsuarioVerificacion->getUsuverificacionId());

            $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);

            $idLogA = $UsuarioLogMySqlDAO2->insert($UsuarioLog);
            $data = $Imagen;
            $filename = "c" . $UsuarioLog->usuarioId;

            $filename = $filename . 'A';

            $filename = $filename . '.png';

            if (!file_exists('/home/home2/backend/images/c/')) {
                mkdir('/home/home2/backend/images/c/', 0755, true);
            }

            $dirsave = '/home/home2/backend/images/c/' . $filename;
            file_put_contents($dirsave, $data);

            shell_exec('export BOTO_CONFIG=/home/backend/.config/gcloud/legacy_credentials/admin@bpoconsultores.com.co/.boto &&
             export PATH=/sbin:/bin:/usr/sbin:/usr/bin:/home/backend/google-cloud-sdk/bin && gsutil -h "Cache-Control:public,max-age=604800" cp ' . $dirsave . ' gs://cedulas-1/c/');

            $tipo = 'USUDNIPOSTERIOR';

            $Imagen = $data->profile->identificationCardBack;

            $file_contents1 = addslashes($Imagen);
            $UsuarioLog = new UsuarioLog2();
            $UsuarioLog->setUsuarioId($Usuario->usuarioId);
            $UsuarioLog->setUsuarioIp("");
            $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
            $UsuarioLog->setUsuariosolicitaIp("");
            $UsuarioLog->setUsuarioaprobarId(0);
            $UsuarioLog->setTipo($tipo);
            $UsuarioLog->setEstado("A");
            $UsuarioLog->setValorAntes('');
            $UsuarioLog->setValorDespues('');
            $UsuarioLog->setUsucreaId(0);
            $UsuarioLog->setUsumodifId(0);
            $UsuarioLog->setImagen($file_contents1);
            $UsuarioLog->setSversion($UsuarioVerificacion->getUsuverificacionId());
            $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);

            $idLogP = $UsuarioLogMySqlDAO2->insert($UsuarioLog);

            $data = $Imagen;
            $filename = "c" . $UsuarioLog->usuarioId;

            $filename = $filename . 'P';

            $filename = $filename . '.png';

            if (!file_exists('/home/home2/backend/images/c/')) {
                mkdir('/home/home2/backend/images/c/', 0755, true);
            }

            $dirsave = '/home/home2/backend/images/c/' . $filename;
            file_put_contents($dirsave, $data);

            shell_exec('export BOTO_CONFIG=/home/backend/.config/gcloud/legacy_credentials/admin@bpoconsultores.com.co/.boto && export PATH=/sbin:/bin:/usr/sbin:/usr/bin:/home/backend/google-cloud-sdk/bin && gsutil -h "Cache-Control:public,max-age=604800" cp ' . $dirsave . ' gs://cedulas-1/c/');

            $UsuarioLog = new UsuarioLog2();
            $UsuarioLog->setUsuarioId($Usuario->usuarioId);
            $UsuarioLog->setUsuarioIp('');
            $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
            $UsuarioLog->setUsuariosolicitaIp("");
            $UsuarioLog->setTipo("USUNOMBRE1");
            $UsuarioLog->setEstado("A");
            $UsuarioLog->setValorAntes($Registro->getNombre1());
            $UsuarioLog->setValorDespues($nombre[0]);
            $UsuarioLog->setUsucreaId(0);
            $UsuarioLog->setUsumodifId(0);
            $UsuarioLog->setSversion($UsuarioVerificacion->getUsuverificacionId());
            $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);
            $UsuarioLogMySqlDAO2->insert($UsuarioLog);


            $UsuarioLog = new UsuarioLog2();
            $UsuarioLog->setUsuarioId($Usuario->usuarioId);
            $UsuarioLog->setUsuarioIp('');

            $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
            $UsuarioLog->setUsuariosolicitaIp("");

            $UsuarioLog->setTipo("USUNOMBRE2");
            $UsuarioLog->setEstado("A");
            $UsuarioLog->setValorAntes($Registro->getNombre2());
            $UsuarioLog->setValorDespues($nombre[1]);
            $UsuarioLog->setUsucreaId(0);
            $UsuarioLog->setUsumodifId(0);
            $UsuarioLog->setSversion($UsuarioVerificacion->getUsuverificacionId());
            $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);
            $UsuarioLogMySqlDAO2->insert($UsuarioLog);

            $UsuarioLog = new UsuarioLog2();
            $UsuarioLog->setUsuarioId($Usuario->usuarioId);
            $UsuarioLog->setUsuarioIp('');
            $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
            $UsuarioLog->setUsuariosolicitaIp("");
            $UsuarioLog->setTipo("USUAPELLIDO1");
            $UsuarioLog->setEstado("A");
            $UsuarioLog->setValorAntes($Registro->getApellido1());
            $UsuarioLog->setValorDespues($apellido[0]);
            $UsuarioLog->setUsucreaId(0);
            $UsuarioLog->setUsumodifId(0);
            $UsuarioLog->setSversion($UsuarioVerificacion->getUsuverificacionId());
            $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);
            $UsuarioLogMySqlDAO2->insert($UsuarioLog);

            $UsuarioLog = new UsuarioLog2();
            $UsuarioLog->setUsuarioId($Usuario->usuarioId);
            $UsuarioLog->setUsuarioIp('');
            $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
            $UsuarioLog->setUsuariosolicitaIp("");
            $UsuarioLog->setTipo("USUAPELLIDO2");
            $UsuarioLog->setEstado("A");
            $UsuarioLog->setValorAntes($Registro->getApellido2());
            $UsuarioLog->setValorDespues($apellido[1]);
            $UsuarioLog->setUsucreaId(0);
            $UsuarioLog->setUsumodifId(0);
            $UsuarioLog->setSversion($UsuarioVerificacion->getUsuverificacionId());
            $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);
            $UsuarioLogMySqlDAO2->insert($UsuarioLog);

            $UsuarioLog = new UsuarioLog2();
            $UsuarioLog->setUsuarioId($Usuario->usuarioId);
            $UsuarioLog->setUsuarioIp('');
            $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
            $UsuarioLog->setUsuariosolicitaIp("");
            $UsuarioLog->setTipo("USUFECHANACIM");
            $UsuarioLog->setEstado("A");
            $UsuarioLog->setValorAntes($UsuarioOtraInfo->getFechaNacim());
            $UsuarioLog->setValorDespues($fechaNacimiento);
            $UsuarioLog->setUsucreaId(0);
            $UsuarioLog->setUsumodifId(0);
            $UsuarioLog->setSversion($UsuarioVerificacion->getUsuverificacionId());

            $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);
            $UsuarioLogMySqlDAO2->insert($UsuarioLog);

            $UsuarioLog = new UsuarioLog2();
            $UsuarioLog->setUsuarioId($Usuario->usuarioId);
            $UsuarioLog->setUsuarioIp('');
            $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
            $UsuarioLog->setUsuariosolicitaIp("");
            $UsuarioLog->setTipo("USUCEDULA");
            $UsuarioLog->setEstado("A");
            $UsuarioLog->setValorAntes($Registro->getCedula());
            $UsuarioLog->setValorDespues($Documento);
            $UsuarioLog->setUsucreaId(0);
            $UsuarioLog->setUsumodifId(0);
            $UsuarioLog->setSversion($UsuarioVerificacion->getUsuverificacionId());
            $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);
            $UsuarioLogMySqlDAO2->insert($UsuarioLog);

            if ($idLogA != '' && $idLogA != '0') {
                $UsuarioLog = new UsuarioLog2($idLogA);
                $data = $Imagen;
                $filename = "c" . $UsuarioLog->usuarioId;

                $filename = $filename . 'A';

                $filename = $filename . '.png';

                if (!file_exists('/home/home2/backend/images/c/')) {
                    mkdir('/home/home2/backend/images/c/', 0755, true);
                }

                $dirsave = '/home/home2/backend/images/c/' . $filename;
                file_put_contents($dirsave, $data);

                shell_exec('export BOTO_CONFIG=/home/backend/.config/gcloud/legacy_credentials/admin@bpoconsultores.com.co/.boto && export PATH=/sbin:/bin:/usr/sbin:/usr/bin:/home/backend/google-cloud-sdk/bin && gsutil -h "Cache-Control:public,max-age=604800" cp ' . $dirsave . ' gs://cedulas-1/c/');
            }
            if ($idLogP != '' && $idLogP != '0') {
                $UsuarioLog = new UsuarioLog2($idLogP);
                $data = $Imagen;
                $filename = "c" . $UsuarioLog->usuarioId;

                $filename = $filename . 'P';

                $filename = $filename . '.png';

                if (!file_exists('/home/home2/backend/images/c/')) {
                    mkdir('/home/home2/backend/images/c/', 0755, true);
                }

                $dirsave = '/home/home2/backend/images/c/' . $filename;
                file_put_contents($dirsave, $data);

                shell_exec('export BOTO_CONFIG=/home/backend/.config/gcloud/legacy_credentials/admin@bpoconsultores.com.co/.boto && export PATH=/sbin:/bin:/usr/sbin:/usr/bin:/home/backend/google-cloud-sdk/bin && gsutil -h "Cache-Control:public,max-age=604800" cp ' . $dirsave . ' gs://cedulas-1/c/');
            }

            /**
             * Actualiza la información del registro, la información adicional del usuario y envía un mensaje de verificación aprobada.
             */
            $Registro->setCedula($Documento);
            $Registro->setNombre($nombre[0] . " " . $nombre[1] . " " . $apellido[0] . " " . $apellido[1]);
            $Registro->setNombre1($nombre[0]);
            $Registro->setNombre2($nombre[1]);
            $Registro->setApellido1($apellido[0]);
            $Registro->setApellido2($apellido[1]);

            $RegistroMySqlDAO = new RegistroMySqlDAO($Transaction);
            $RegistroMySqlDAO->update($Registro);
            $Transaction->commit();

            $UsuarioOtrainfoMySqlDAO = new UsuarioOtrainfoMySqlDAO();
            $Transaction = $UsuarioOtrainfoMySqlDAO->getTransaction();
            $UsuarioOtraInfo->setFechaNacim($fechaNacimiento);
            $UsuarioOtrainfoMySqlDAO->update($UsuarioOtraInfo);
            $Transaction->commit();

            $UsuarioMySqlDAO = new UsuarioMySqlDAO();
            $Transaction = $UsuarioMySqlDAO->getTransaction();
            $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
            $UsuarioMensaje = new UsuarioMensaje();
            $UsuarioMensaje->usufromId = 0;
            $UsuarioMensaje->usutoId = $UsuarioMandante->getUsumandanteId();
            $UsuarioMensaje->isRead = 0;
            $UsuarioMensaje->body = '<div style="margin:50px;">La verificación de cuenta ha sido completada y cuenta se ha verificado exitosamente.</div>';
            $UsuarioMensaje->msubject = 'Respuesta de Verificación';
            $UsuarioMensaje->parentId = 0;
            $UsuarioMensaje->proveedorId = $UsuarioMandante->mandante;
            $UsuarioMensaje->tipo = "MESSAGEINV";
            $UsuarioMensaje->paisId = $UsuarioMandante->paisId;
            $UsuarioMensaje->fechaExpiracion = '';

            $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($Transaction);
            $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
            $Transaction->commit();
            $response = "Verificación Aprobada";

            return $response;
        } elseif ($data->profile->status == "BLOCKED") {

            /**
             * Maneja el caso cuando el estado del perfil es "BLOCKED".
             */
            $nombre = explode(" ", $data->profile->firstName);
            $apellido = explode(" ", $data->profile->lastName);
            $fechaNacimiento = explode("T", $data->profile->birthdate);
            $fechaNacimiento = $fechaNacimiento[0];
            $Documento = $data->profile->identificationNumber;
            $UsuarioVerificacion = new UsuarioVerificacion($data->profile->custom->useridverification);

            $UsuarioVerificacion->setEstado('P');
            $UsuarioVerificacion->setObservacion('Pendiente Verificación Manual');

            $UsuarioMySqlDAO = new UsuarioMySqlDAO();
            $Transaction = $UsuarioMySqlDAO->getTransaction();
            $UsuarioVerificacionMySqlDAO = new UsuarioVerificacionMySqlDAO($Transaction);
            $UsuarioVerificacionMySqlDAO->update($UsuarioVerificacion);
            $Transaction->commit();

            $VerificacionLog = new VerificacionLog();
            $VerificacionLog->setUsuverificacionId($UsuarioVerificacion->getUsuverificacionId());
            $VerificacionLog->setJson(json_encode($data));

            $VerificacionLogMySqlDAO = new VerificacionLogMySqlDAO();
            $Transaction = $VerificacionLogMySqlDAO->getTransaction();
            $VerificacionLogMySqlDAO->insert($VerificacionLog);
            $Transaction->commit();

            $UsuarioMySqlDAO = new UsuarioMySqlDAO();
            $Transaction = $UsuarioMySqlDAO->getTransaction();
            $Usuario = new Usuario($UsuarioVerificacion->getUsuarioId());
            $Registro = new Registro("", $Usuario->usuarioId);
            $UsuarioOtraInfo = new UsuarioOtrainfo($Usuario->usuarioId);

            /**
             * Registra un log de usuario con la imagen de identificación frontal y la guarda en un directorio y en Google Cloud Storage.
             */
            $tipo = 'USUDNIANTERIOR';

            $Imagen = $data->profile->identificationCardFront;

            $file_contents1 = addslashes($Imagen);

            $UsuarioLog = new UsuarioLog2();
            $UsuarioLog->setUsuarioId($Usuario->usuarioId);
            $UsuarioLog->setUsuarioIp("");
            $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
            $UsuarioLog->setUsuariosolicitaIp("");
            $UsuarioLog->setUsuarioaprobarId(0);
            $UsuarioLog->setTipo($tipo);
            $UsuarioLog->setEstado("P");
            $UsuarioLog->setValorAntes('');
            $UsuarioLog->setValorDespues('');
            $UsuarioLog->setUsucreaId(0);
            $UsuarioLog->setUsumodifId(0);
            $UsuarioLog->setImagen($file_contents1);
            $UsuarioLog->setSversion($UsuarioVerificacion->getUsuverificacionId());

            $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);

            $UsuarioLogMySqlDAO2->insert($UsuarioLog);

            $data = $Imagen;
            $filename = "c" . $UsuarioLog->usuarioId;

            $filename = $filename . 'A';

            $filename = $filename . '.png';

            if (!file_exists('/home/home2/backend/images/c/')) {
                mkdir('/home/home2/backend/images/c/', 0755, true);
            }

            $dirsave = '/home/home2/backend/images/c/' . $filename;
            file_put_contents($dirsave, $data);

            shell_exec('export BOTO_CONFIG=/home/backend/.config/gcloud/legacy_credentials/admin@bpoconsultores.com.co/.boto && export PATH=/sbin:/bin:/usr/sbin:/usr/bin:/home/backend/google-cloud-sdk/bin && gsutil -h "Cache-Control:public,max-age=604800" cp ' . $dirsave . ' gs://cedulas-1/c/');

            $tipo = 'USUDNIPOSTERIOR';

            $Imagen = $data->profile->identificationCardBack;

            $file_contents1 = addslashes($Imagen);
            $UsuarioLog = new UsuarioLog2();
            $UsuarioLog->setUsuarioId($Usuario->usuarioId);
            $UsuarioLog->setUsuarioIp("");
            $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
            $UsuarioLog->setUsuariosolicitaIp("");
            $UsuarioLog->setUsuarioaprobarId(0);
            $UsuarioLog->setTipo($tipo);
            $UsuarioLog->setEstado("P");
            $UsuarioLog->setValorAntes('');
            $UsuarioLog->setValorDespues('');
            $UsuarioLog->setUsucreaId(0);
            $UsuarioLog->setUsumodifId(0);
            $UsuarioLog->setImagen($file_contents1);
            $UsuarioLog->setSversion($UsuarioVerificacion->getUsuverificacionId());
            $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);

            $UsuarioLogMySqlDAO2->insert($UsuarioLog);

            $data = $Imagen;
            $filename = "c" . $UsuarioLog->usuarioId;

            $filename = $filename . 'P';

            $filename = $filename . '.png';

            if (!file_exists('/home/home2/backend/images/c/')) {
                mkdir('/home/home2/backend/images/c/', 0755, true);
            }

            $dirsave = '/home/home2/backend/images/c/' . $filename;
            file_put_contents($dirsave, $data);

            shell_exec('export BOTO_CONFIG=/home/backend/.config/gcloud/legacy_credentials/admin@bpoconsultores.com.co/.boto && export PATH=/sbin:/bin:/usr/sbin:/usr/bin:/home/backend/google-cloud-sdk/bin && gsutil -h "Cache-Control:public,max-age=604800" cp ' . $dirsave . ' gs://cedulas-1/c/');


            $UsuarioLog = new UsuarioLog2();
            $UsuarioLog->setUsuarioId($Usuario->usuarioId);
            $UsuarioLog->setUsuarioIp('');
            $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
            $UsuarioLog->setUsuariosolicitaIp("");
            $UsuarioLog->setTipo("USUNOMBRE1");
            $UsuarioLog->setEstado("P");
            $UsuarioLog->setValorAntes($Registro->getNombre1());
            $UsuarioLog->setValorDespues($nombre[0]);
            $UsuarioLog->setUsucreaId(0);
            $UsuarioLog->setUsumodifId(0);
            $UsuarioLog->setSversion($UsuarioVerificacion->getUsuverificacionId());
            $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);
            $UsuarioLogMySqlDAO2->insert($UsuarioLog);


            $UsuarioLog = new UsuarioLog2();
            $UsuarioLog->setUsuarioId($Usuario->usuarioId);
            $UsuarioLog->setUsuarioIp('');

            $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
            $UsuarioLog->setUsuariosolicitaIp("");

            $UsuarioLog->setTipo("USUNOMBRE2");
            $UsuarioLog->setEstado("P");
            $UsuarioLog->setValorAntes($Registro->getNombre2());
            $UsuarioLog->setValorDespues($nombre[1]);
            $UsuarioLog->setUsucreaId(0);
            $UsuarioLog->setUsumodifId(0);
            $UsuarioLog->setSversion($UsuarioVerificacion->getUsuverificacionId());
            $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);
            $UsuarioLogMySqlDAO2->insert($UsuarioLog);


            $UsuarioLog = new UsuarioLog2();
            $UsuarioLog->setUsuarioId($Usuario->usuarioId);
            $UsuarioLog->setUsuarioIp('');
            $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
            $UsuarioLog->setUsuariosolicitaIp("");
            $UsuarioLog->setTipo("USUAPELLIDO1");
            $UsuarioLog->setEstado("P");
            $UsuarioLog->setValorAntes($Registro->getApellido1());
            $UsuarioLog->setValorDespues($apellido[0]);
            $UsuarioLog->setUsucreaId(0);
            $UsuarioLog->setUsumodifId(0);
            $UsuarioLog->setSversion($UsuarioVerificacion->getUsuverificacionId());
            $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);
            $UsuarioLogMySqlDAO2->insert($UsuarioLog);


            $UsuarioLog = new UsuarioLog2();
            $UsuarioLog->setUsuarioId($Usuario->usuarioId);
            $UsuarioLog->setUsuarioIp('');
            $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
            $UsuarioLog->setUsuariosolicitaIp("");
            $UsuarioLog->setTipo("USUAPELLIDO2");
            $UsuarioLog->setEstado("P");
            $UsuarioLog->setValorAntes($Registro->getApellido2());
            $UsuarioLog->setValorDespues($apellido[1]);
            $UsuarioLog->setUsucreaId(0);
            $UsuarioLog->setUsumodifId(0);
            $UsuarioLog->setSversion($UsuarioVerificacion->getUsuverificacionId());
            $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);
            $UsuarioLogMySqlDAO2->insert($UsuarioLog);


            $UsuarioLog = new UsuarioLog2();
            $UsuarioLog->setUsuarioId($Usuario->usuarioId);
            $UsuarioLog->setUsuarioIp('');
            $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
            $UsuarioLog->setUsuariosolicitaIp("");
            $UsuarioLog->setTipo("USUFECHANACIM");
            $UsuarioLog->setEstado("P");
            $UsuarioLog->setValorAntes($UsuarioOtraInfo->getFechaNacim());
            $UsuarioLog->setValorDespues($fechaNacimiento);
            $UsuarioLog->setUsucreaId(0);
            $UsuarioLog->setUsumodifId(0);
            $UsuarioLog->setSversion($UsuarioVerificacion->getUsuverificacionId());

            $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);
            $UsuarioLogMySqlDAO2->insert($UsuarioLog);

            $UsuarioLog = new UsuarioLog2();
            $UsuarioLog->setUsuarioId($Usuario->usuarioId);
            $UsuarioLog->setUsuarioIp('');
            $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
            $UsuarioLog->setUsuariosolicitaIp("");
            $UsuarioLog->setTipo("USUCEDULA");
            $UsuarioLog->setEstado("P");
            $UsuarioLog->setValorAntes($Registro->getCedula());
            $UsuarioLog->setValorDespues($Documento);
            $UsuarioLog->setUsucreaId(0);
            $UsuarioLog->setUsumodifId(0);
            $UsuarioLog->setSversion($UsuarioVerificacion->getUsuverificacionId());
            $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);
            $UsuarioLogMySqlDAO2->insert($UsuarioLog);

            $Transaction->commit();


            $UsuarioMySqlDAO = new UsuarioMySqlDAO();
            $Transaction = $UsuarioMySqlDAO->getTransaction();
            $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
            $UsuarioMensaje = new UsuarioMensaje();
            $UsuarioMensaje->usufromId = 0;
            $UsuarioMensaje->usutoId = $UsuarioMandante->getUsumandanteId();
            $UsuarioMensaje->isRead = 0;
            $UsuarioMensaje->body = '<div style="margin:50px;">La verificación de cuenta esta pendiente por aprobación manual, por favor en caso de requerir más información comuniquese con nuestro chat online</div>';
            $UsuarioMensaje->msubject = 'Respuesta de Verificación';
            $UsuarioMensaje->parentId = 0;
            $UsuarioMensaje->proveedorId = $UsuarioMandante->mandante;
            $UsuarioMensaje->tipo = "MESSAGEINV";
            $UsuarioMensaje->paisId = $UsuarioMandante->paisId;
            $UsuarioMensaje->fechaExpiracion = '';


            $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($Transaction);
            $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
            $Transaction->commit();

            $response = "Verificación Pendiente";

            return $response;
        } elseif ($data->profile->status == "REJECT" || $data->profile->status == "EXPIRED") {

            /**
             * Maneja el caso cuando el estado del perfil es "REJECT O EXPIRED".
             */
            $nombre = explode(" ", $data->profile->firstName);
            $apellido = explode(" ", $data->profile->lastName);
            $fechaNacimiento = explode("T", $data->profile->birthdate);
            $fechaNacimiento = $fechaNacimiento[0];
            $Documento = $data->profile->identificationNumber;
            $UsuarioVerificacion = new UsuarioVerificacion($data->profile->custom->useridverification);

            $UsuarioVerificacion->setEstado('R');
            $UsuarioVerificacion->setObservacion('Rechazado por AUCO');

            $UsuarioMySqlDAO = new UsuarioMySqlDAO();
            $Transaction = $UsuarioMySqlDAO->getTransaction();
            $UsuarioVerificacionMySqlDAO = new UsuarioVerificacionMySqlDAO($Transaction);
            $UsuarioVerificacionMySqlDAO->update($UsuarioVerificacion);
            $Transaction->commit();


            $VerificacionLog = new VerificacionLog();
            $VerificacionLog->setUsuverificacionId($UsuarioVerificacion->getUsuverificacionId());
            $VerificacionLog->setJson(json_encode($data));


            $VerificacionLogMySqlDAO = new VerificacionLogMySqlDAO($Transaction);
            $VerificacionLogMySqlDAO->insert($VerificacionLog);
            $Transaction->commit();

            $UsuarioMySqlDAO = new UsuarioMySqlDAO();
            $Transaction = $UsuarioMySqlDAO->getTransaction();
            $Usuario = new Usuario($UsuarioVerificacion->getUsuarioId());
            $Registro = new Registro("", $Usuario->usuarioId);
            $UsuarioOtraInfo = new UsuarioOtrainfo($Usuario->usuarioId);

            $tipo = 'USUDNIANTERIOR';

            $Imagen = $data->profile->identificationCardFront;

            $file_contents1 = addslashes($Imagen);


            $UsuarioLog = new UsuarioLog2();
            $UsuarioLog->setUsuarioId($Usuario->usuarioId);
            $UsuarioLog->setUsuarioIp("");
            $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
            $UsuarioLog->setUsuariosolicitaIp("");
            $UsuarioLog->setUsuarioaprobarId(0);
            $UsuarioLog->setTipo($tipo);
            $UsuarioLog->setEstado("R");
            $UsuarioLog->setValorAntes('');
            $UsuarioLog->setValorDespues('');
            $UsuarioLog->setUsucreaId(0);
            $UsuarioLog->setUsumodifId(0);
            $UsuarioLog->setImagen($file_contents1);
            $UsuarioLog->setSversion($UsuarioVerificacion->getUsuverificacionId());

            $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);

            $UsuarioLogMySqlDAO2->insert($UsuarioLog);
            $data = $Imagen;
            $filename = "c" . $UsuarioLog->usuarioId;

            $filename = $filename . 'A';

            $filename = $filename . '.png';

            if (!file_exists('/home/home2/backend/images/c/')) {
                mkdir('/home/home2/backend/images/c/', 0755, true);
            }

            $dirsave = '/home/home2/backend/images/c/' . $filename;
            file_put_contents($dirsave, $data);

            shell_exec('export BOTO_CONFIG=/home/backend/.config/gcloud/legacy_credentials/admin@bpoconsultores.com.co/.boto && export PATH=/sbin:/bin:/usr/sbin:/usr/bin:/home/backend/google-cloud-sdk/bin && gsutil -h "Cache-Control:public,max-age=604800" cp ' . $dirsave . ' gs://cedulas-1/c/');


            $tipo = 'USUDNIPOSTERIOR';

            $Imagen = $data->profile->identificationCardBack;

            $file_contents1 = addslashes($Imagen);
            $UsuarioLog = new UsuarioLog2();
            $UsuarioLog->setUsuarioId($Usuario->usuarioId);
            $UsuarioLog->setUsuarioIp("");
            $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
            $UsuarioLog->setUsuariosolicitaIp("");
            $UsuarioLog->setUsuarioaprobarId(0);
            $UsuarioLog->setTipo($tipo);
            $UsuarioLog->setEstado("R");
            $UsuarioLog->setValorAntes('');
            $UsuarioLog->setValorDespues('');
            $UsuarioLog->setUsucreaId(0);
            $UsuarioLog->setUsumodifId(0);
            $UsuarioLog->setImagen($file_contents1);
            $UsuarioLog->setSversion($UsuarioVerificacion->getUsuverificacionId());
            $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);

            $UsuarioLogMySqlDAO2->insert($UsuarioLog);

            $data = $Imagen;
            $filename = "c" . $UsuarioLog->usuarioId;

            $filename = $filename . 'P';

            $filename = $filename . '.png';

            if (!file_exists('/home/home2/backend/images/c/')) {
                mkdir('/home/home2/backend/images/c/', 0755, true);
            }

            $dirsave = '/home/home2/backend/images/c/' . $filename;
            file_put_contents($dirsave, $data);

            shell_exec('export BOTO_CONFIG=/home/backend/.config/gcloud/legacy_credentials/admin@bpoconsultores.com.co/.boto && export PATH=/sbin:/bin:/usr/sbin:/usr/bin:/home/backend/google-cloud-sdk/bin && gsutil -h "Cache-Control:public,max-age=604800" cp ' . $dirsave . ' gs://cedulas-1/c/');


            $UsuarioLog = new UsuarioLog2();
            $UsuarioLog->setUsuarioId($Usuario->usuarioId);
            $UsuarioLog->setUsuarioIp('');
            $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
            $UsuarioLog->setUsuariosolicitaIp("");
            $UsuarioLog->setTipo("USUNOMBRE1");
            $UsuarioLog->setEstado("R");
            $UsuarioLog->setValorAntes($Registro->getNombre1());
            $UsuarioLog->setValorDespues($nombre[0]);
            $UsuarioLog->setUsucreaId(0);
            $UsuarioLog->setUsumodifId(0);
            $UsuarioLog->setSversion($UsuarioVerificacion->getUsuverificacionId());
            $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);
            $UsuarioLogMySqlDAO2->insert($UsuarioLog);


            $UsuarioLog = new UsuarioLog2();
            $UsuarioLog->setUsuarioId($Usuario->usuarioId);
            $UsuarioLog->setUsuarioIp('');

            $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
            $UsuarioLog->setUsuariosolicitaIp("");

            $UsuarioLog->setTipo("USUNOMBRE2");
            $UsuarioLog->setEstado("R");
            $UsuarioLog->setValorAntes($Registro->getNombre2());
            $UsuarioLog->setValorDespues($nombre[1]);
            $UsuarioLog->setUsucreaId(0);
            $UsuarioLog->setUsumodifId(0);
            $UsuarioLog->setSversion($UsuarioVerificacion->getUsuverificacionId());
            $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);
            $UsuarioLogMySqlDAO2->insert($UsuarioLog);


            $UsuarioLog = new UsuarioLog2();
            $UsuarioLog->setUsuarioId($Usuario->usuarioId);
            $UsuarioLog->setUsuarioIp('');
            $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
            $UsuarioLog->setUsuariosolicitaIp("");
            $UsuarioLog->setTipo("USUAPELLIDO1");
            $UsuarioLog->setEstado("R");
            $UsuarioLog->setValorAntes($Registro->getApellido1());
            $UsuarioLog->setValorDespues($apellido[0]);
            $UsuarioLog->setUsucreaId(0);
            $UsuarioLog->setUsumodifId(0);
            $UsuarioLog->setSversion($UsuarioVerificacion->getUsuverificacionId());
            $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);
            $UsuarioLogMySqlDAO2->insert($UsuarioLog);


            $UsuarioLog = new UsuarioLog2();
            $UsuarioLog->setUsuarioId($Usuario->usuarioId);
            $UsuarioLog->setUsuarioIp('');
            $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
            $UsuarioLog->setUsuariosolicitaIp("");
            $UsuarioLog->setTipo("USUAPELLIDO2");
            $UsuarioLog->setEstado("R");
            $UsuarioLog->setValorAntes($Registro->getApellido2());
            $UsuarioLog->setValorDespues($apellido[1]);
            $UsuarioLog->setUsucreaId(0);
            $UsuarioLog->setUsumodifId(0);
            $UsuarioLog->setSversion($UsuarioVerificacion->getUsuverificacionId());
            $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);
            $UsuarioLogMySqlDAO2->insert($UsuarioLog);


            $UsuarioLog = new UsuarioLog2();
            $UsuarioLog->setUsuarioId($Usuario->usuarioId);
            $UsuarioLog->setUsuarioIp('');
            $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
            $UsuarioLog->setUsuariosolicitaIp("");
            $UsuarioLog->setTipo("USUFECHANACIM");
            $UsuarioLog->setEstado("R");
            $UsuarioLog->setValorAntes($UsuarioOtraInfo->getFechaNacim());
            $UsuarioLog->setValorDespues($fechaNacimiento);
            $UsuarioLog->setUsucreaId(0);
            $UsuarioLog->setUsumodifId(0);
            $UsuarioLog->setSversion($UsuarioVerificacion->getUsuverificacionId());

            $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);
            $UsuarioLogMySqlDAO2->insert($UsuarioLog);

            $UsuarioLog = new UsuarioLog2();
            $UsuarioLog->setUsuarioId($Usuario->usuarioId);
            $UsuarioLog->setUsuarioIp('');
            $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
            $UsuarioLog->setUsuariosolicitaIp("");
            $UsuarioLog->setTipo("USUCEDULA");
            $UsuarioLog->setEstado("R");
            $UsuarioLog->setValorAntes($Registro->getCedula());
            $UsuarioLog->setValorDespues($Documento);
            $UsuarioLog->setUsucreaId(0);
            $UsuarioLog->setUsumodifId(0);
            $UsuarioLog->setSversion($UsuarioVerificacion->getUsuverificacionId());
            $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);
            $UsuarioLogMySqlDAO2->insert($UsuarioLog);

            $Transaction->commit();


            $UsuarioMySqlDAO = new UsuarioMySqlDAO();
            $Transaction = $UsuarioMySqlDAO->getTransaction();
            $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
            $UsuarioMensaje = new UsuarioMensaje();
            $UsuarioMensaje->usufromId = 0;
            $UsuarioMensaje->usutoId = $UsuarioMandante->getUsumandanteId();
            $UsuarioMensaje->isRead = 0;
            $UsuarioMensaje->body = '<div style="margin:50px;">La verificación de cuenta ha sido rechazada, intentelo nuevamente por favor en caso de requerir más información comuniquese con nuestro chat online</div>';
            $UsuarioMensaje->msubject = 'Respuesta de Verificación';
            $UsuarioMensaje->parentId = 0;
            $UsuarioMensaje->proveedorId = $UsuarioMandante->mandante;
            $UsuarioMensaje->tipo = "MESSAGEINV";
            $UsuarioMensaje->paisId = $UsuarioMandante->paisId;
            $UsuarioMensaje->fechaExpiracion = '';

            $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($Transaction);
            $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
            $Transaction->commit();

            $response = "Verificación Rechazada";
            return $response;
        }
    }

    /**
     * Crea un nuevo documento con datos y configuraciones específicas.
     *
     * La función `CreateDocument` en PHP crea un nuevo documento con datos y configuraciones específicas basadas en la información del usuario y del país.
     * en la información del usuario y del país.
     *
     * @param int    $Usuarioid El parámetro `Usuarioid` en la función `CreateDocument` parece representar
     * el ID del usuario para el que se está creando el documento. Este parámetro se utiliza para obtener
     * información relacionada con el usuario, como su país, mandante, y otros detalles necesarios para la
     * la creación del documento.
     * @param int    $id        El parámetro `id` en la función `CreateDocument` parece ser utilizado para identificar un
     * documento específico para su procesamiento. Se pasa a la función como un argumento y luego se utiliza para
     * Obtener los detalles del documento de la base de datos o de una fuente externa.
     * @param string $tilulo    El parámetro `tilulo` en la función `CreateDocument` parece representar el
     * título del documento que se está creando. Se utiliza para establecer el nombre del documento en el proceso de
     * crearlo. Si tiene alguna pregunta específica o necesita más ayuda con esta función o
     * cualquier otra parte.
     * @param string $ruta      El parámetro `ruta` en la función `CrearDocumento` parece representar la carpeta
     * ruta donde se guardará el documento. Se utiliza en la matriz para especificar la ubicación de la carpeta para
     * el documento a guardar.
     *
     * @return object JSON Object con las claves «success» y «code».
     *  1. [bool] «success» puesto a true
     *  2. [mixed] «code» contiene el ID del documento devuelto por la llamada a la API.     *
     */
    public function CreateDocument($Usuarioid, $id, $tilulo, $ruta)
    {

        try {
            $PuntoVenta = new PuntoVenta("", $Usuarioid);

            $Usuario = new Usuario($Usuarioid);

            $Pais = new Pais($Usuario->paisId);

            /**
             * Configura las claves públicas y privadas según el entorno, el mandante del usuario y el país.
             */
            $ConfigurationEnvironment = new ConfigurationEnvironment();
            if ($ConfigurationEnvironment->isDevelopment()) {
                if ($Usuario->mandante == "0" && $Pais->paisId == "173") {
                    $this->keyPublic = "puk_hbR5aDqnKbN6A3nvizBOn8KPF3lHkhRz";
                    $this->keyPrivate = "prk_W1hsMII8LvDFnEgzIrUp3xikXl3cAllE";
                }

                if ($Usuario->mandante == "18" && $Pais->paisId == "146") {
                    $this->keyPublic = "";
                    $this->keyPrivate = "";
                }
            } else {
                if ($Usuario->mandante == "0" && $Pais->paisId == "173") {
                    $this->keyPublic = "puk_knL8eX4GtyGrmcWqOf5Ox8TFgTIARAZw";
                    $this->keyPrivate = "prk_LEhkLliJiTUh6C4jm8wlESxsRtIFm5XU";
                }

                if ($Usuario->mandante == "18" && $Pais->paisId == "146") {
                    $this->keyPublic = "";
                    $this->keyPrivate = "";
                }

                if ($Usuario->mandante == "8" && $Pais->paisId == "66") {
                    $this->keyPublic = "puk_NaqIsQxKxQFkeE280s7S5RN6AJTBODjO";
                    $this->keyPrivate = "prk_eDZegi19sI7v92VrHCAThVWEQqxudlrB";
                    $emailAdmin = "verificacionesecuabet@virtualsoft.tech";
                }

                if ($Usuario->mandante == "19" && $Pais->paisId == "173") {
                    $this->keyPublic = "puk_gqXGCIDL45dfC6NgZpwe0UK8Rxqn8xrn";
                    $this->keyPrivate = "prk_a0q0EYpI7z4QiTgjXGreM9ttjIAvoErC";
                }
            }

            $Descarga = new Descarga($id);
            $ExternalId = $Descarga->externalId;
            $this->method = "document?custom=true&code=";

            $respuesta = $this->connectionGETTemplates($ExternalId);
            $respuesta = json_decode($respuesta);

            $respuesta = $respuesta[0]->config;
            $Pais = new Pais($Usuario->paisId);

            /**
             * Determina el tipo de documento (`documentType`) basado en el país y el tipo de documento del registro.
             */
            switch ($Pais->iso) {
                case "PE":
                    $documentType = "DNI";
                    break;

                case "EC":
                    $documentType = "C";
                    break;

                case "GT":
                    $documentType = "DPI";
                    break;
            }

            $arrayFinal = array_column($respuesta, "name");
            $final = array();

            /**
             * Procesa un array de cadenas y crea un nuevo array asociativo basado en la búsqueda de subcadenas específicas.
             */
            foreach ($arrayFinal as $value) {
                $v1 = strpos($value, "name");
                $arrayTemp = array();
                if ($v1 !== false) {
                    $arrayTemp["key"] = $value;
                    $arrayTemp["value"] = $PuntoVenta->nombreContacto;
                    array_push($final, $arrayTemp);
                }
                $v2 = strpos($value, "document_type");
                if ($v2 !== false) {
                    $arrayTemp["key"] = $value;
                    $arrayTemp["value"] = $documentType;
                    array_push($final, $arrayTemp);
                }
                $v3 = strpos($value, "cedula");
                if ($v3 !== false) {
                    $arrayTemp["key"] = $value;
                    $arrayTemp["value"] = $PuntoVenta->cedula;
                    array_push($final, $arrayTemp);
                }
                $v4 = strpos($value, "email");
                if ($v4 !== false) {
                    $arrayTemp["key"] = $value;
                    $arrayTemp["value"] = $PuntoVenta->email;
                    array_push($final, $arrayTemp);
                }
                $v5 = strpos($value, "phone");
                if ($v5 !== false) {
                    $arrayTemp["key"] = $value;
                    $arrayTemp["value"] = $Pais->prefijoCelular . $PuntoVenta->telefono;
                    array_push($final, $arrayTemp);
                }
            }

            /**
             * Crea un array con datos de usuairo, lo codifica a JSON, y lo envía a través de una conexión.
             */
            $array = array(
                "document" => $ExternalId,
                "sign" => true,
                "folder" => "/" . $ruta,
                "email" => $emailAdmin,
                "name" => $tilulo,
                "data" => $final,
                "camera" => true,
                "otpCode" => true,
                "options" => array(
                    "camera" => "identification",
                    "otpCode" => "email",
                    "whatsapp" => false,
                ),
            );

            $this->method = "document/save";
            $response = $this->connection(json_encode($array));
            $response = json_decode($response);

            if ($response->document != "") {
                $DocumentoUsuario = new DocumentoUsuario();
                $DocumentoUsuario->setUsuarioId($Usuario->usuarioId);
                $DocumentoUsuario->setDocumentoId($Descarga->descargaId);
                $DocumentoUsuario->setVersion($Descarga->version);
                $DocumentoUsuario->setFechaCrea(date('Y-m-d H:i:s'));
                $DocumentoUsuario->setFechaModif(date('Y-m-d H:i:s'));
                $DocumentoUsuario->setEstadoAprobacion('P');
                $DocumentoUsuario->setExternalId($response->document);

                $DocumentoUsuarioMySqlDAO = new DocumentoUsuarioMySqlDAO();
                $DocumentoUsuarioMySqlDAO->insert($DocumentoUsuario);
                $DocumentoUsuarioMySqlDAO->getTransaction()->commit();
            }

            $data = array();
            $data["success"] = true;
            $data["code"] = $response->document;
            return json_decode(json_encode($data));
        } catch (Exception $e) {
        }
    }

    /**
     * La función procesa una firma basada en los datos proporcionados.
     *
     * La función procesa una firma basada en los datos proporcionados, actualizando el estado de aprobación del usuario del documento si el estado es «FINALIZADO».
     * estado de aprobación del usuario del documento si el estado es «FINISH».
     *
     * @param array $data Objeto con las propiedades.
     * [int] code: Esta variable almacena un identificador externo.
     * [string] name: Esta variable almacena el estado de un proceso, registro o entidad.
     * [string] status: Esta variable almacena un nombre.
     * [string] url: Esta variable almacena una URL (Uniform Resource Locator).
     *
     * @return void
     */
    public function processSignature($data)
    {

        $externalId = $data->code;
        $estado = $data->status;
        $name = $data->name;
        $url = $data->url;

        if ($estado == "FINISH") {
            $DocumentoUsuario = new DocumentoUsuario("", "", "", $externalId);

            $DocumentoUsuario->setFechaModif(date('Y-m-d H:i:s'));
            $DocumentoUsuario->setEstadoAprobacion('A');

            $DocumentoUsuarioMySqlDAO = new DocumentoUsuarioMySqlDAO();
            $DocumentoUsuarioMySqlDAO->update($DocumentoUsuario);
            $DocumentoUsuarioMySqlDAO->getTransaction()->commit();
        }
    }

    /**
     * Carga un documento asociado a un usuario y retorna un código de respuesta.
     *
     * @param object $Usuario Objeto que representa al usuario con las propiedades.
     * -[int] paisId: ID del país del usuario.
     * -[int] usuarioId: ID del usuario.
     * -[int] mandante: Mandante del usuario.
     * -[string] email: Correo electrónico del usuario.
     * -[string] cedula: Cédula del usuario.
     * -[string] celular: Número de celular del usuario.
     * @param string $file    Contenido del archivo a cargar codificado en base64.
     *
     * @return object JSON decodificado con el resultado de la carga.
     * -[bool] success: Indica si la carga fue exitosa.
     * -[string] Code: Código de respuesta de la carga.
     */
    public function UploadDocument($Usuario, $file)
    {

        $Pais = new Pais($Usuario->paisId);
        $Registro = new Registro("", $Usuario->usuarioId);
        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
        $Mandante = new Mandante($UsuarioMandante->mandante);

        $array = array(
            "name" => "",
            "email" => $Registro->email,
            "message" => "",
            "subject" => "Documento importante",
            "remember" => 3,
            "signProfile" => array(),
            "camera" => true,
            "otpCode" => true,
            "options" => array(
                "camera" => $Registro->cedula,
                "otpCode" => $Registro->celular,
                "whatsapp" => false,
            ),
            "file" => base64_encode($file)
        );

        $this->method = "/document/save";
        $response = $this->connection(json_encode($array));


        $data = array();
        $data["success"] = true;
        $data["Code"] = $response->code;
        return json_decode(json_encode($data));
    }

    /**
     * Recupera documentos basándose en el ID de usuario
     *
     * La función `GetDocuments` recupera documentos basándose en el ID de usuario, ID de cliente e ID de país, con
     * diferentes claves API utilizadas dependiendo del entorno y las combinaciones cliente-país.
     *
     * @param int    $Usuarioid ID del usuario.
     * @param string $mandante  Mandante del usuario.
     * @param int    $paisId    ID del país del usuario.
     *
     * @return array de documentos con sus IDs y nombres en formato JSON.
     *  - id: ID del documento (tipo: string).
     *  - value: Nombre del documento (tipo: string).
     */
    public function GetDocuments($Usuarioid, $mandante, $paisId)
    {

        $Usuario = new Usuario($Usuarioid);
        $Pais = new Pais($Usuario->paisId);
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        if ($ConfigurationEnvironment->isDevelopment()) {
            if ($mandante == "0" && $paisId == "173") {
                $this->keyPublic = "puk_meBcYEZ9UsEeDkho9mg0XaEuxnzCZuWk";
                $this->keyPrivate = "prk_9s80mqUeh6EwO5wzKaoSCotWcdneB3dE";
            }

            if ($mandante == "18" && $paisId == "146") {
                $this->keyPublic = "";
                $this->keyPrivate = "";
            }
        } else {
            if ($mandante == "0" && $paisId == "173") {
                $this->keyPublic = "puk_knL8eX4GtyGrmcWqOf5Ox8TFgTIARAZw";
                $this->keyPrivate = "prk_LEhkLliJiTUh6C4jm8wlESxsRtIFm5XU";
            }

            if ($mandante == "18" && $paisId == "146") {
                $this->keyPublic = "";
                $this->keyPrivate = "";
            }

            if ($mandante == "8" && $paisId == "66") {
                $this->keyPublic = "puk_NaqIsQxKxQFkeE280s7S5RN6AJTBODjO";
                $this->keyPrivate = "prk_eDZegi19sI7v92VrHCAThVWEQqxudlrB";
            }
        }

        $this->method = "document";
        $response = $this->connectionGET();
        $response = json_decode($response);
        $data = array();
        foreach ($response as $value) {
            $array = array();
            $array["id"] = $value->_id;
            $array["value"] = $value->name;
            array_push($data, $array);
        }

        return json_decode(json_encode($data));
    }

    /**
     * Recupera la dirección IP del cliente
     *
     * La función `get_client_ip` en PHP recupera la dirección IP del cliente usando varias variables del servidor
     * servidor.
     *
     * @return string La función `get_client_ip()` devuelve la dirección IP del cliente. Comprueba varias variables
     * servidor para determinar la direccion IP del cliente y devuelve 'UNKNOWN' si la direccion IP no puede ser determinada.
     * determinar.
     */
    public function get_client_ip()
    {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP')) {
            $ipaddress = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_X_FORWARDED')) {
            $ipaddress = getenv('HTTP_X_FORWARDED');
        } elseif (getenv('HTTP_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        } elseif (getenv('HTTP_FORWARDED')) {
            $ipaddress = getenv('HTTP_FORWARDED');
        } elseif (getenv('REMOTE_ADDR')) {
            $ipaddress = getenv('REMOTE_ADDR');
        } else {
            $ipaddress = 'UNKNOWN';
        }

        return $ipaddress;
    }

    /**
     * Envía una petición POST usando cURL con los parámetros especificados
     *
     * La función `connectionlinks` envía una petición POST usando cURL con los parámetros especificados y
     * devuelve la respuesta JSON decodificada.
     *
     * @param string $string Es una función PHP que envía una petición POST
     * usando cURL. La función toma una cadena como parámetro y la envía como cuerpo de la petición. Después de
     * Después de enviar la petición, registra la petición y la respuesta usando syslog.
     *
     * @return string La función `connectionlinks` devuelve el resultado de la petición cURL después de
     * de ejecutarla. El resultado se registra como un mensaje de advertencia usando `syslog` y luego se devuelve
     * después de decodificarlo como JSON. Sin embargo, hay un error en la sentencia return. La sentencia.
     */
    public function connectionlinks($string)
    {

        syslog(LOG_WARNING, "AUCODATA : " . $string);
        $ch = curl_init($this->url . $this->method);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Content-Length: " . strlen($string), "Accept: application/json", "Authorization:" . $this->keyPrivate));

        $result = (curl_exec($ch));
        syslog(LOG_WARNING, "AUCORESPONSE : " . $result);
        return (json_decode(json_encode($result)));
    }

    /**
     * Envía una petición POST usando cURL con los parámetros especificados
     *
     * La función `connectionlink` envía una petición POST usando cURL con los parámetros especificados y
     * devuelve la respuesta JSON decodificada.
     *
     * @param string $string Es una función PHP que envía una petición POST
     * usando cURL. Envía una cadena JSON a una URL especificada junto con algunas cabeceras como
     * Content-Type y Authorization.
     *
     * @return string La función `connectionlink` devuelve el resultado de la petición cURL después de
     *  de ejecutarla. El resultado se registra como un mensaje de advertencia antes de ser devuelto. Adicionalmente, el
     *  resultado está siendo decodificado del formato JSON a un objeto usando `json_decode`.
     */
    public function connectionlink($string)
    {
        syslog(LOG_WARNING, "AUCODATA : " . $string);
        $ch = curl_init($this->url . "/cm/v1/");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer" . $this->token));

        $result = (curl_exec($ch));
        syslog(LOG_WARNING, "AUCORESPONSE : " . $result);
        return (json_decode(json_encode($result)));
    }

    /**
     * Envía una petición POST con las cabeceras y parámetros especificados.
     *
     * La función `connection` en PHP usa cURL para enviar una petición POST con las cabeceras y parámetros especificados.
     * parámetros, registrando la petición y la respuesta.
     *
     * @param string $string Es una función PHP que envía una petición POST usando cURL. Envía una cadena JSON como cuerpo
     * de la petición a una URL especificada.
     *
     * @return object el resultado de la petición cURL después de ejecutarla. El resultado se decodifica de JSON
     * a un objeto usando `json_decode`.
     */
    public function connection($string)
    {

        syslog(LOG_WARNING, "AUCOFIRMADATA : " . $string);
        $ch = curl_init($this->url . $this->method);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Content-Length: " . strlen($string), "Accept: application/json", "Authorization:" . $this->keyPrivate));

        $result = (curl_exec($ch));
        syslog(LOG_WARNING, "AUCOFIRMARESPONSE : " . $result);
        return (json_decode(json_encode($result)));
    }

    /**
     * Envía una petición POST con las cabeceras y parámetros especificados.
     *
     * La función `connectionGET` en PHP usa cURL para enviar una petición GET a una URL especificada con
     * cabeceras específicas y devuelve la respuesta decodificada JSON.
     *
     * @return array La función `connectionGET` realiza una petición GET usando cURL a una URL y metodo especificados.
     * metodo especificado. Establece varias opciones cURL como tipo de petición, agente de usuario, metodo de autenticación,
     * cabeceras, etc. La respuesta de la petición se almacena en la variable `` y luego se registra
     * usando `syslog`. Finalmente, la función intenta decodificar la respuesta como JSON y devolverla.
     */
    public function connectionGET()
    {

        syslog(LOG_WARNING, "AUCOFIRMADATAGET : " . $this->url . $this->method . " KEYPUBLIC " . $this->keyPublic);

        $ch = curl_init($this->url . $this->method);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Accept: application/json", "Authorization:" . $this->keyPublic));

        $result = (curl_exec($ch));
        syslog(LOG_WARNING, "AUCOFIRMARESPONSEGET : " . $result);

        return (json_decode(json_encode($result)));
    }

    /**
     * Envía una petición POST con las cabeceras y parámetros especificados.
     *
     * La función `connectionGETTemplates` hace una petición GET usando cURL con los parámetros especificados
     * y devuelve la respuesta JSON decodificada.
     *
     * @param int $Id La función `connectionGETTemplates` es un metodo que realiza una petición GET
     * usando cURL a una URL específica con el `` proporcionado. Establece varias opciones de cURL como
     * tipo de petición, agente de usuario, cabeceras y autenticación.
     *
     * @return array La función realiza una petición GET usando cURL a una URL específica con un parámetro ID.
     * Establece varias opciones cURL como tipo de petición, agente de usuario, metodo de autenticación, cabeceras,
     * y tiempo de espera. A continuación, ejecuta la solicitud y registra la respuesta antes de devolver el resultado después de
     * decodificarlo desde el formato JSON.
     */
    public function connectionGETTemplates($Id)
    {

        syslog(LOG_WARNING, "AUCOFIRMADATA : " . $Id);

        $ch = curl_init($this->url . $this->method . $Id);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Accept: application/json", "Authorization:" . $this->keyPublic));

        $result = (curl_exec($ch));

        syslog(LOG_WARNING, "AUCOFIRMARESPONSE : " . $result);
        return (json_decode(json_encode($result)));
    }
}
