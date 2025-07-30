<?php

/**
 * Contiene métodos para establecer conexiones a una URL específica.
 *
 * @category Seguridad
 * @package  Auth
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-03-18
 */

namespace Backend\integrations\auth;

use Exception;
use Backend\dto\Pais;
use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\Registro;
use Backend\dto\Template;
use Backend\dto\PuntoVenta;
use Backend\dto\UsuarioLog;
use Backend\dto\BonoInterno;
use Backend\dto\UsuarioLog2;
use Backend\dto\Clasificador;
use Backend\dto\Subproveedor;
use Backend\dto\SitioTracking;
use Backend\dto\UsuarioMensaje;
use Backend\dto\MandanteDetalle;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioOtrainfo;
use Backend\dto\VerificacionLog;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\RegistroMySqlDAO;
use Backend\dto\UsuarioVerificacion;
use Backend\dto\UsuarioConfiguracion;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\mysql\UsuarioLog2MySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\UsuarioOtrainfoMySqlDAO;
use Backend\mysql\VerificacionLogMySqlDAO;
use Backend\mysql\UsuarioVerificacionMySqlDAO;
use Backend\mysql\UsuarioConfiguracionMySqlDAO;

/**
 * JUMIOSERVICES
 *
 * La clase `JUMIOSERVICES` en PHP contiene métodos para establecer conexiones a una URL específica,
 * procesar los resultados de la verificación de la API de Sumsub, y actualizar la información del usuario
 * basándose en el resultado de la verificación.
 */
class JUMIOSERVICES
{

    /**
     * Nombre de usuario para la autenticación en ambiente de Producción.
     *
     * @var string $clientId .
     */
    private $clientId = "";

    /**
     * Clave de API del cliente para la autenticación.
     *
     * @var string $clientAPI .
     */
    private $clientAPI = "";

    /**
     * Secreto del cliente para la autenticación.
     *
     * @var string $clientSecret .
     */
    private $clientSecret = "";

    /**
     * Contraseña para la API.
     *
     * @var string $password .
     */
    private $password = "";

    /**
     * Token de autenticación.
     *
     * @var string $token .
     */
    private $token = "";

    /**
     * Metodo de la solicitud (e.g., "GET", "POST").
     *
     * @var string $url .
     */
    private $url = "";

    /**
     * URL base para la API de recuperación de Jumio.
     *
     * @var string $urlRetrieval .
     */
    private $urlRetrieval = "";

    /**
     * URL para obtener el token de autenticación.
     *
     * @var string $urltoken .
     */
    private $urltoken = "";

    /**
     * URL de retorno (callback) para la API.
     *
     * @var string $callbackUrl .
     */
    private $callbackUrl = "";

    /**
     * URL de retorno (callback) para la API en ambiente de Desarrollo.
     *
     * @var string $callbackUrl .
     */
    private $callbackUrlDEV = "https://apidev.virtualsoft.tech/integrations/auth/jumio/api/";

    /**
     * URL de retorno (callback) para la API en ambiente de Producción.
     *
     * @var string $callbackUrl .
     */
    private $callbackUrlPROD = "https://integrations.virtualsoft.tech/auth/jumio/api/";

    /**
     * Función constructor
     *
     * Constructor de la clase. Configura las URLs, credenciales y opciones de la API según el entorno (desarrollo o
     * producción).
     *
     * No devuelven ningún valor, el constructor se encargan de inicializar un objeto, En lugar de @return.
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->callbackUrl = $this->callbackUrlDEV;
        } else {
            $this->callbackUrl = $this->callbackUrlPROD;
        }
    }

    /**
     *  La función `accessToken` genera y devuelve un token de acceso.
     *
     * La función `accessToken` genera y devuelve un token de acceso basado en el objeto
     * `` país del objeto y los detalles del cliente.
     *
     * @param object $UsuarioMandante El parámetro `UsuarioMandante` parece representar una entidad usuario o cliente
     *                                con propiedades como `paisId` y `mandante`. Basándonos en el fragmento de código
     *                                proporcionado, la función
     *                                `accessToken` es responsable de establecer diferentes credenciales API de cliente
     *                                basadas en el `paisId` y `mandante.
     *
     * @return string La función `accessToken` devuelve el token de acceso después de obtenerlo a través del metodo
     * metodo `connectionToken` y decodificar la respuesta. El token de acceso se almacena en la clase
     * propiedad de la clase `->token` y devuelto por la función.
     */
    public function accesToken($UsuarioMandante = null)
    {
        if ($UsuarioMandante != null) {
            $Subproveedor = new Subproveedor("", "JUMIO");
            $SubproveedorMandantePais = new SubproveedorMandantePais('', $Subproveedor->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
            $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

            $this->clientAPI = $Credentials->CLIENT_API;
            $this->password = $Credentials->PASSWORD;
            $this->clientId = $Credentials->CLIENT_ID;
            $this->clientSecret = $Credentials->CLIENT_SECRET;
            $this->urltoken = $Credentials->URL_TOKEN;
            $this->url = $Credentials->URL;
            $this->urlRetrieval = $Credentials->URL_RETRIEVAL;
        }

        $response = $this->connectionToken();

        $response = json_decode($response);
        $this->token = $response->access_token;

        return $this->token;
    }

    /**
     * Maneja la creación de cuentas de usuario con configuraciones específicas
     *
     * La función `AccountCreation` en PHP maneja la creación de cuentas de usuario con configuraciones específicas
     * basadas en el país y el tipo de cliente del usuario. configuraciones específicas basadas en el país del usuario
     * y el tipo de cliente.
     *
     * @param string $token               La función `AccountCreation` es la responsable de crear una nueva cuenta
     *                                    basada en los parámetros proporcionados. He aquí un desglose de la función y
     *                                    sus parámetros.
     * @param object $UsuarioMandante     El parametro `UsuarioMandante` es un objeto que contiene información sobre el
     *                                    usuario y el cliente. Probablemente incluye propiedades como.
     *                                    - [int] paisId: ID del país del usuario.
     *                                    - [int] mandante: Mandante del usuario.
     *                                    - [string] idioma: Idioma preferido del usuario en la pataforma.
     * @param object $UsuarioVerificacion El parámetro `UsuarioVerificacion` de la función `AccountCreation
     *                                    es un parámetro opcional que representa al usuario para la verificación. Si
     *                                    se proporciona, se utiliza para registrar el proceso de verificación y
     *                                    almacenar la información relevante en la base de datos. Si no se proporciona,
     *                                    la función omitirá el paso de registro de verificación.
     *
     * @return array La función `AccountCreation` devuelve un array con dos claves.
     * 1. [bool] success: Un valor que indica si la operación se ha realizado correctamente.
     * 2. [string] url: Una cadena que contiene la URL obtenida de la respuesta para su posterior procesamiento.
     */
    public function AccountCreation($token, $UsuarioMandante, $UsuarioVerificacion = null)
    {
        $this->token = $token;

        $Mandante = new Mandante($UsuarioMandante->mandante);
        $Pais = new Pais($UsuarioMandante->paisId);
        $Registro = new Registro("", $UsuarioMandante->usuarioMandante);
        $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

        $Subproveedor = new Subproveedor("", "JUMIO");
        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Subproveedor->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $this->clientAPI = $Credentials->CLIENT_API;
        $this->password = $Credentials->PASSWORD;
        $this->clientId = $Credentials->CLIENT_ID;
        $this->clientSecret = $Credentials->CLIENT_SECRET;
        $this->urltoken = $Credentials->URL_TOKEN;
        $this->url = $Credentials->URL;
        $this->urlRetrieval = $Credentials->URL_RETRIEVAL;

        // Convierte el tipo de documento del registro a un formato específico en la variable $tipoDoc.
        switch ($Registro->tipoDoc) {
            case "C":
                $tipoDoc = "ID_CARD";
                break;
            case "P":
                $tipoDoc = "PASSPORT";
                break;
            case "E":
                $tipoDoc = "DRIVING_LICENSE";
                break;
        }

        switch ($Pais->iso) {
            case "PE":
                $Iso = "PER";
                break;
            case "EC":
                $Iso = "ECU";
                break;
            case "MX":
                $Iso = "MEX";
                break;
        }

        // ID Verificación, Identidad en la verficiación
        $workflowDefinition = array(
            "key" => "10011"
        );

        $json = array(
            "customerInternalReference" => $Usuario->usuarioId,
            "workflowDefinition" => $workflowDefinition,
            "userReference" => $Usuario->usuarioId,
            "reportingCriteria" => $Mandante->mandante . "_" . $Pais->paisNom,
            "tokenLifetime" => "5m",
            "callbackUrl" => $this->callbackUrl,
            "web" => array(
                "successUrl" => $Mandante->baseUrl,
                "errorUrl" => $Mandante->baseUrl,
                "locale" => $Usuario->idioma,
            ),
        );

        $response = $this->connectionlink(json_encode($json));

        $response = json_decode($response);

        if ($_ENV['debug']) {
            print_r($this);
            print_r('connectionlink');
            print_r(json_encode($response));
        }

        if ($UsuarioVerificacion != null) {
            $VerificacionLog = new VerificacionLog();
            $VerificacionLog->setUsuverificacionId($UsuarioVerificacion->getUsuverificacionId());
            $VerificacionLog->setTipo('URLREDIRECTION');
            $VerificacionLog->setJson((json_encode($response)));

            $VerificacionLogMySqlDAO = new VerificacionLogMySqlDAO();
            $VerificacionLogMySqlDAO->insert($VerificacionLog);
            $VerificacionLogMySqlDAO->getTransaction()->commit();

            $accountId = $response->account->id;

            $UsuarioMySqlDAO = new UsuarioMySqlDAO();
            $Transaction = $UsuarioMySqlDAO->getTransaction();

            $Usuario->setAccountIdJumio($accountId);

            $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);
            $UsuarioMySqlDAO->update($Usuario);
            $Transaction->commit();
        }

        $data = array();
        $data["success"] = true;
        $data["url"] = $response->web->href;
        return $data;
    }

    /**
     * Maneja la creación de cuentas con credenciales específicas
     *
     * La función `AccountCreationPV` en PHP maneja la creación de cuentas con credenciales específicas de la API de
     * cliente. credenciales basadas en el país del usuario y el tipo de cliente, e inicia un proceso de verificación
     * con URLs de retorno.
     *
     * @param string $token               La función `AccountCreationPV` toma tres parámetros.
     * @param object $Usuario             El parámetro `Usuario` de la función `AccountCreationPV` Representa un
     *                                    objeto usuario o datos relacionados con un usuario. Se utiliza para
     *                                    determinar la API de cliente, contraseña, cliente ID de cliente, y secreto de
     *                                    cliente basado en el ID de país y el ID de mandante (tenant) del usuario.
     * @param int    $UsuarioVerificacion El parametro `$UsuarioVerificacion` en la funcion `AccountCreationPV`
     *                                    se utiliza para pasar una instancia de la clase `UsuarioVerificacion` a la
     *                                    función. Si el parámetro
     *                                    `$UsuarioVerificacion` no es nulo, la función realiza ciertas acciones
     *                                    relacionadas con el registro de la información de verificación.
     *
     * @return array La función `AccountCreationPV` devuelve un array con dos claves.
     * 1. [bool] success: Un valor booleano que indica si la operación se ha realizado correctamente.
     * 2. [string] url: La URL obtenida de los datos de respuesta, concretamente de la propiedad `web->href`.
     */
    public function AccountCreationPV($token, $Usuario, $UsuarioVerificacion = null)
    {
        $this->token = $token;

        $Mandante = new Mandante($Usuario->mandante);
        $Pais = new Pais($Usuario->paisId);

        $Subproveedor = new Subproveedor("", "JUMIO");
        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Subproveedor->subproveedorId, $Usuario->mandante, $Usuario->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $this->clientAPI = $Credentials->CLIENT_API;
        $this->password = $Credentials->PASSWORD;
        $this->clientId = $Credentials->CLIENT_ID;
        $this->clientSecret = $Credentials->CLIENT_SECRET;
        $this->urltoken = $Credentials->URL_TOKEN;
        $this->url = $Credentials->URL;
        $this->urlRetrieval = $Credentials->URL_RETRIEVAL;

        // ID Verificacion, Identidada de Verificacion
        $workflowDefinition = array(
            "key" => "10011",
        );

        $json = array(
            "customerInternalReference" => $Usuario->usuarioId,
            "workflowDefinition" => $workflowDefinition,
            "userReference" => $Usuario->usuarioId,
            "reportingCriteria" => $Mandante->mandante . "_" . $Pais->paisNom,
            "tokenLifetime" => "5m",
            "callbackUrl" => $this->callbackUrl,
            "web" => array(
                "successUrl" => $Mandante->baseUrl,
                "errorUrl" => $Mandante->baseUrl,
                "locale" => $Usuario->idioma,
            ),
        );

        $response = $this->connectionlink(json_encode($json));

        if ($UsuarioVerificacion != null) {
            $VerificacionLog = new VerificacionLog();
            $VerificacionLog->setUsuverificacionId($UsuarioVerificacion->getUsuverificacionId());
            $VerificacionLog->setTipo('URLREDIRECTION');
            $VerificacionLog->setJson((($response)));

            $VerificacionLogMySqlDAO = new VerificacionLogMySqlDAO();
            $VerificacionLogMySqlDAO->insert($VerificacionLog);
            $VerificacionLogMySqlDAO->getTransaction()->commit();
        }

        $response = json_decode($response);

        $data = array();
        $data["success"] = true;
        $data["url"] = $response->web->href;
        return $data;
    }

    /**
     * Actualiza una cuenta con credenciales específicas de la API del cliente.
     *
     * La función `AccountUpdate` en PHP actualiza una cuenta con credenciales específicas de la API del cliente
     * basado en el país del usuario y los detalles del cliente, inicia un flujo de trabajo de verificación de ID, y
     * devuelve un estado de éxito junto con una URL para su posterior procesamiento.
     *
     * @param string $token               La función `AccountUpdate` Actualiza una cuenta con configuraciones
     *                                    específicas basadas en el país del usuario y los detalles del cliente.
     *                                    Establece diferentes credenciales API credenciales basadas en el país del
     *                                    usuario y el tipo de cuenta.
     * @param object $UsuarioMandante     UsuarioMandante es un objeto que contiene información sobre el usuario y el
     *                                    cliente. el cliente. Incluye propiedades como.
     *                                    [int] mandante: Mandante del usuario.
     *                                    [int] paisId: ID del país del usuario.
     *                                    [int] usuarioMandante: Mandante del cliente.
     *                                    [string] idioma: Idioma preferido del usuario en la pataforma.
     * @param int    $accountId           La función `AccountUpdate` Actualiza una cuenta con los parámetros
     *                                    proporcionados. parámetros proporcionados. El parámetro `accountId` se
     *                                    utiliza para identificar la cuenta que necesita ser actualizada. Es probable
     *                                    que sea un identificador único para la cuenta dentro del sistema.
     * @param int    $UsuarioVerificacion El parámetro `UsuarioVerificacion` en la función `AccountUpdate
     *                                    se utiliza para especificar el usuario para la verificación. Si este
     *                                    parámetro se proporciona con un valor no nulo
     *                                    , la función realizará acciones adicionales relacionadas con la verificación
     *                                    para ese usuario.
     *
     * @return array Se devuelve un array con dos claves.
     * - [bool] success: establecido en true.
     * - [string] url: contiene la URL obtenida de los datos JSON de la respuesta
     */
    public function AccountUpdate($token, $UsuarioMandante, $accountId, $UsuarioVerificacion = null)
    {
        $this->token = $token;

        $Mandante = new Mandante($UsuarioMandante->mandante);
        $Pais = new Pais($UsuarioMandante->paisId);
        $Registro = new Registro("", $UsuarioMandante->usuarioMandante);
        $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

        $Subproveedor = new Subproveedor("", "JUMIO");
        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Subproveedor->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $this->clientAPI = $Credentials->CLIENT_API;
        $this->password = $Credentials->PASSWORD;
        $this->clientId = $Credentials->CLIENT_ID;
        $this->clientSecret = $Credentials->CLIENT_SECRET;
        $this->urltoken = $Credentials->URL_TOKEN;
        $this->url = $Credentials->URL;
        $this->urlRetrieval = $Credentials->URL_RETRIEVAL;

        // Convierte el tipo de documento del registro a un formato específico en la variable $tipoDoc.
        switch ($Registro->tipoDoc) {
            case "C":
                $tipoDoc = "ID_CARD";
                break;
            case "P":
                $tipoDoc = "PASSPORT";
                break;
            case "E":
                $tipoDoc = "DRIVING_LICENSE";
                break;
        }

        switch ($Pais->iso) {
            case "PE":
                $Iso = "PER";
                break;
            case "EC":
                $Iso = "ECU";
                break;
            case "MX":
                $Iso = "MEX";
                break;
        }

        // ID Verificacion, Identidad de verificacion.
        $workflowDefinition = array(
            "key" => "10011"
        );

        $json = array(
            "customerInternalReference" => $Usuario->usuarioId,
            "workflowDefinition" => $workflowDefinition,
            "userReference" => $Usuario->usuarioId,
            "reportingCriteria" => $Mandante->mandante . "_" . $Pais->paisNom,
            "tokenLifetime" => "5m",
            "callbackUrl" => $this->callbackUrl,
            "web" => array(
                "successUrl" => $Mandante->baseUrl,
                "errorUrl" => $Mandante->baseUrl,
                "locale" => $Usuario->idioma,
            ),
        );

        $response = $this->connectionlinkUpdate(json_encode($json), $accountId);

        if ($_ENV['debug']) {
            print_r('connectionlinkUpdate');
            print_r($response);
        }

        if ($UsuarioVerificacion != null) {
            $VerificacionLog = new VerificacionLog();
            $VerificacionLog->setUsuverificacionId($UsuarioVerificacion->getUsuverificacionId());
            $VerificacionLog->setTipo('URLREDIRECTION');
            $VerificacionLog->setJson((($response)));

            $VerificacionLogMySqlDAO = new VerificacionLogMySqlDAO();
            $VerificacionLogMySqlDAO->insert($VerificacionLog);
            $VerificacionLogMySqlDAO->getTransaction()->commit();
        }

        $response = json_decode($response);

        $data = array();
        $data["success"] = true;
        $data["url"] = $response->web->href;
        return $data;
    }

    /**
     * Actualiza la información de la cuenta basándose en los datos del usuario
     *
     * La función `AccountUpdatePV` en PHP actualiza la información de la cuenta basándose en los datos del usuario y
     * del entorno. datos de usuario y entorno, generando una definición de flujo de trabajo y devolviendo un estado de
     * éxito junto con una URL para procesamiento posterior.
     *
     * @param string $token   La función `AccountUpdatePV` Actualiza una cuenta con configuraciones específicas basadas
     *                        en el país del usuario y los detalles del cliente.
     * @param object $Usuario El parámetro `Usuario` en la función `AccountUpdatePV` Representa un
     *                        objeto usuario con propiedades como.
     *                        -[int] paisId: ID del país del usuario.
     *                        -[int] usuarioId: ID del usuario.
     *                        -[int] mandante: Mandante del usuario.
     *                        -[string] accountIdJumio: Id retornado por el proveedor, identifcador unico.
     *                        -[string] idioma: Idioma preferido del usuario en la pataforma.
     *                        Se utiliza para determinar los valores de `clientAPI`, `password.
     *
     * @return array La función `AccountUpdatePV` devuelve un array con dos claves.
     * 1. [bool] success: Un valor booleano que indica si la operación se ha realizado correctamente.
     * 2. [string] url: La URL obtenida de la respuesta para su posterior procesamiento.
     */
    public function AccountUpdatePV($token, $Usuario)
    {
        $this->token = $token;

        $Mandante = new Mandante($Usuario->mandante);
        $Pais = new Pais($Usuario->paisId);
        $accountId = $Usuario->accountIdJumio;

        $Subproveedor = new Subproveedor("", "JUMIO");
        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Subproveedor->subproveedorId, $Usuario->mandante, $Usuario->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $this->clientAPI = $Credentials->CLIENT_API;
        $this->password = $Credentials->PASSWORD;
        $this->clientId = $Credentials->CLIENT_ID;
        $this->clientSecret = $Credentials->CLIENT_SECRET;
        $this->urltoken = $Credentials->URL_TOKEN;
        $this->url = $Credentials->URL;
        $this->urlRetrieval = $Credentials->URL_RETRIEVAL;

        // ID Verification, Identity Verification, Screening
        $workflowDefinition = array(
            "key" => "10011",
        );

        $json = array(
            "customerInternalReference" => $Usuario->usuarioId,
            "workflowDefinition" => $workflowDefinition,
            "userReference" => $Usuario->usuarioId,
            "reportingCriteria" => $Mandante->mandante . "_" . $Pais->paisNom,
            "tokenLifetime" => "5m",
            "callbackUrl" => $this->callbackUrl,
            "web" => array(
                "successUrl" => $Mandante->baseUrl,
                "errorUrl" => $Mandante->baseUrl,
                "locale" => $Usuario->idioma,
            ),
        );

        $response = $this->connectionlinkUpdate(json_encode($json), $accountId);

        $response = json_decode($response);

        $data = array();
        $data["success"] = true;
        $data["url"] = $response->web->href;
        return $data;
    }

    /**
     * Gestiona el proceso de verificación de un usuario mediante la API de Jumio.
     *
     * La función `process` en PHP gestiona el proceso de verificación de un usuario mediante la API de Jumio,
     * actualizando la información del usuario y enviando notificaciones según el estado de la verificación.
     *
     * @param string $account             La función `process` gestiona el procesamiento de los datos de verificación
     *                                    del usuario según diversas condiciones. Interactúa con el usuario y los
     *                                    objetos de verificación, actualiza sus estados, registra información y activa
     *                                    notificaciones según el resultado de la verificación.
     * @param string $status              El parámetro `status` indica el estado del proceso de verificación.
     *                                    Puede tener valores como "TOKEN_EXPIRED", "SESSION_EXPIRED" o "NOT_EXECUTED"
     *                                    según el resultado del proceso de verificación.
     * @param string $token               La función `process` gesitona el procesamiento de los datos de verificación
     *                                    del usuario según diversas condiciones y respuestas de una API de Jumio.
     *                                    Realiza diferentes acciones según el estado de la verificación, como aprobar,
     *                                    rechazar o marcar la verificación como pendiente.
     * @param string $accountId           La función `process` gestiona el procesamiento de los datos de verificación
     *                                    del usuario según diversas condiciones y respuestas de un servicio de
     *                                    verificación de Jumio. Realiza diferentes acciones según el estado de la
     *                                    verificación, como actualizar la información del usuario, registrar los
     *                                    detalles de la verificación
     *                                    , gestionar los casos de rechazo y enviar notificaciones.
     * @param string $workflowExecutionId La función `process` gestiona el procesamiento de los datos de verificación
     *                                    del usuario según diversas condiciones. Interactúa con los objetos de
     *                                    verificación del usuario, los registros y actualiza la información del
     *                                    usuario según los resultados de la verificación.
     *
     * @return array una variable de respuesta codificada en JSON.
     */
    public function process($account, $status, $token, $accountId, $workflowExecutionId)
    {
        $Usuario = new Usuario($account);
        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);

        $Subproveedor = new Subproveedor("", "JUMIO");
        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Subproveedor->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $this->clientAPI = $Credentials->CLIENT_API;
        $this->password = $Credentials->PASSWORD;
        $this->clientId = $Credentials->CLIENT_ID;
        $this->clientSecret = $Credentials->CLIENT_SECRET;
        $this->urltoken = $Credentials->URL_TOKEN;
        $this->url = $Credentials->URL;
        $this->urlRetrieval = $Credentials->URL_RETRIEVAL;

        $Registro = new Registro("", $Usuario->usuarioId);
        $UsuarioOtraInfo = new UsuarioOtrainfo($Usuario->usuarioId);

        try {
            $Clasificador = new Clasificador('', 'RECHAZADOVERIF');
            $ClasificadorId = $Clasificador->getClasificadorId();
            $UsuarioConfiguracion = new UsuarioConfiguracion($Usuario->usuarioId, 'A', $ClasificadorId);
        } catch (Exception $e) {
        }

        $Nombres = strtolower($Registro->getNombre1() . " " . $Registro->getNombre2());
        $Apellidos = strtolower($Registro->getApellido1() . " " . $Registro->getApellido2());
        $Clasificador = new Clasificador("", "VERIFICAJUMIO");
        $this->token = $token;
        $string = $accountId . "/workflow-executions/" . $workflowExecutionId . "/status";

        $Response = $this->connectionGET($string);
        $Response = json_decode($Response);

        if ($_ENV['debug']) {
            print_r('RICO CONNECTION GET');
            print_r($this);
            print_r($Response);
        }

        $UrlDetail = $Response->workflowExecution->href;

        $Response2 = $this->connectionGETDetail($UrlDetail);

        $log = "\r\n" . "-------------------------" . "\r\n";
        $log = $log . $Response2;
        $log = $log . "-------------------------";
        //Save string to log, use FILE_APPEND to append.
        fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

        $body = file_get_contents('php://input');

        $Response2 = json_decode($Response2);

        if ($_ENV['debug']) {
            print_r('RICO connectionGETDetail');
            print_r(json_encode($Response2));
        }

        $VerificaFiltro = "A";

        // Determina el valor de 'VerificaFiltro' basado en la configuración del mandante y el usuario.
        try {
            $ClasificadorFiltro = new Clasificador("", "VERIFICANUMDOC");
            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $ClasificadorFiltro->getClasificadorId(), $Usuario->paisId, $Usuario->estado);

            if ($MandanteDetalle->valor == 1) {
                $VerificaFiltro = "A";
            } else {
                $VerificaFiltro = "I";
            }
        } catch (Exception $e) {
        }

        // * Obtiene el número de rechazos y el número de rechazos de documentos para un usuario y mandante específico.
        // * Si ocurre una excepción durante la obtención, se captura y se ignora.
        try {
            $ClasificadorFiltro = new Clasificador("", "NUMRECHAZOS");
            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $ClasificadorFiltro->getClasificadorId(), $Usuario->paisId, "A");
            $NumRechazos = $MandanteDetalle->valor;

            $ClasificadorFiltro = new Clasificador("", "NUMRECHAZOSDOCUMENT");
            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $ClasificadorFiltro->getClasificadorId(), $Usuario->paisId, "A");
            $NumRechazosDocument = $MandanteDetalle->valor;
        } catch (Exception $e) {
        }

        if ($_ENV['debug']) {
            print_r('RICO DECISION');
            print_r($Response2->decision->type);
            print_r('VERIFICADO');
            print_r($Usuario->verificado);
        }

        try {
            $Verifica = false;
            $Rechaza = false;
            $documentIncorrect = false;

            //Verifica y procesa el número de identificación personal extraído de una respuesta de API, si 'VerificaFiltro' es "A".
            if ($VerificaFiltro == "A") {
                try {
                    if (isset($Response2->capabilities->extraction[0]->data->personalIdentificationNumber)) {
                        $DocumentJumioPersonal = $Response2->capabilities->extraction[0]->data->personalIdentificationNumber;
                        $DocumentJumioPersonal = preg_replace("/[ _\-*.]/", "", $DocumentJumioPersonal);
                        $Response2->capabilities->extraction[0]->data->personalIdentificationNumber = $DocumentJumioPersonal;

                        if ($Response2->capabilities->extraction[0]->data->personalIdentificationNumber == $Registro->getCedula()) {
                            $DocumentJumio = $Response2->capabilities->extraction[0]->data->personalIdentificationNumber;
                            $personalIdentificationNumber = true;
                        }
                    }
                } catch (Exception $e) {
                }

                if ($UsuarioMandante->paisId == 2 && $UsuarioMandante->mandante == 0) {
                    $DocumentJumio = $Response2->capabilities->extraction[0]->data->documentNumber;
                    $Verifica = false;
                } elseif ($UsuarioMandante->paisId == 33 && $UsuarioMandante->mandante == 14) {
                    $DocumentJumio = $Response2->capabilities->extraction[0]->data->cpf;
                    $Verifica = false;
                    if ($DocumentJumio == "") {
                        $Rechaza = true;
                    }
                } elseif ($UsuarioMandante->paisId == 33 && $UsuarioMandante->mandante == 0) {
                    $DocumentJumio = $Response2->capabilities->extraction[0]->data->cpf;
                    $Verifica = false;
                    if ($DocumentJumio == "") {
                        $Rechaza = true;
                    }
                } elseif (($UsuarioMandante->paisId == 46 && $UsuarioMandante->mandante == 0) || ($UsuarioMandante->paisId == 46 && $UsuarioMandante->mandante == 18)) {
                    if ($Response2->capabilities->extraction[0]->data->optionalMrzField2 != "") {
                        $DocumentJumio = $Response2->capabilities->extraction[0]->data->optionalMrzField2;
                        $DocumentJumio = str_replace(" ", "", $DocumentJumio);
                        $Verifica = false;
                    } else {
                        $Rechaza = true;
                    }
                } else {
                    if ($UsuarioMandante->paisId == 102 && $UsuarioMandante->mandante == 23) {
                        $DocumentJumioPani = $Response2->capabilities->extraction[0]->data->documentNumber;
                        $DocumentJumioPani = str_replace(" ", "", $DocumentJumioPani);
                        $Response2->capabilities->extraction[0]->data->documentNumber = $DocumentJumioPani;
                    }

                    if ($UsuarioMandante->paisId == 68 && $UsuarioMandante->mandante == 0) {
                        $DocumentJumioSalva = $Response2->capabilities->extraction[0]->data->documentNumber;
                        $DocumentJumioSalva = str_replace(" ", "", $DocumentJumioSalva);
                        $Response2->capabilities->extraction[0]->data->documentNumber = $DocumentJumioSalva;
                    }

                    if (in_array($UsuarioMandante->paisId, [232, 243]) && $UsuarioMandante->mandante == 21) {
                        $DocumentJumioCamanbet = $Response2->capabilities->extraction[0]->data->documentNumber;
                        $DocumentJumioCamanbet = preg_replace('/\D/', '', $DocumentJumioCamanbet);
                        $Response2->capabilities->extraction[0]->data->documentNumber = $DocumentJumioCamanbet;
                    }

                    if (($Response2->capabilities->extraction[0]->data->documentNumber == $Registro->getCedula()) || ($Response2->capabilities->extraction[0]->data->optionalMrzField1 != '' && $Response2->capabilities->extraction[0]->data->optionalMrzField1 == $Registro->getCedula())) {
                        if ($Response2->capabilities->extraction[0]->data->documentNumber == $Registro->getCedula()) {
                            $DocumentJumio = $Response2->capabilities->extraction[0]->data->documentNumber;
                        }
                        if ($Response2->capabilities->extraction[0]->data->optionalMrzField1 == $Registro->getCedula()) {
                            $DocumentJumio = $Response2->capabilities->extraction[0]->data->optionalMrzField1;
                        }
                        $Verifica = false;
                    } else {
                        if (mb_substr($Response2->capabilities->extraction[0]->data->documentNumber, 0, 1) == 'N') {
                            $Response2->capabilities->extraction[0]->data->documentNumber = substr($Response2->capabilities->extraction[0]->data->documentNumber, 1);
                        }
                        if ($personalIdentificationNumber) {
                            $DocumentJumio = str_split($Response2->capabilities->extraction[0]->data->personalIdentificationNumber);
                        } else {
                            $DocumentJumio = str_split($Response2->capabilities->extraction[0]->data->documentNumber);
                        }

                        $DocumentSistema = str_split($Registro->getCedula());

                        $Conteo = 0;
                        foreach ($DocumentJumio as $Key => $value) {
                            if ($value != $DocumentSistema[$Key]) {
                                $Conteo = $Conteo + 1;
                            }
                        }

                        if ($Conteo > 1 && $Response2->capabilities->extraction[0]->data->optionalMrzField1 != '') {
                            if (mb_substr($Response2->capabilities->extraction[0]->data->optionalMrzField1, 0, 1) == 'N') {
                                $Response2->capabilities->extraction[0]->data->optionalMrzField1 = substr($Response2->capabilities->extraction[0]->data->optionalMrzField1, 1);
                            }

                            $DocumentJumio = str_split($Response2->capabilities->extraction[0]->data->optionalMrzField1);
                            $DocumentSistema = str_split($Registro->getCedula());

                            $Conteo = 0;
                            foreach ($DocumentJumio as $Key => $value) {
                                if ($value != $DocumentSistema[$Key]) {
                                    $Conteo = $Conteo + 1;
                                }
                            }

                            if ($Conteo > 1) {
                                //Mas de 1 digito del documento no coincide
                                $Rechaza = true;
                                $documentIncorrect = true;
                            }
                        } else {
                            if ($Conteo > 1) {
                                //Mas de 1 digito del documento no coincide
                                $Rechaza = true;
                                $documentIncorrect = true;
                            }
                        }
                    }
                }
            }


            $Edad = $Response2->capabilities->extraction[0]->data->currentAge;

            //Valida si el usuario es Menor de 18 años
            if ($Edad != 0 && $Edad != null && $Edad < 18) {
                $Rechaza = true;
                $Verifica = false;
                $Observacion = "Menor de edad";

                $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                $Transaction = $UsuarioMySqlDAO->getTransaction();

                $Usuario->setEstado("I");

                $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO($Transaction);

                $UsuarioLog = new UsuarioLog();
                $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                $UsuarioLog->setUsuarioIp('');

                $UsuarioLog->setUsuariosolicitaId(1);
                $UsuarioLog->setUsuariosolicitaIp('');

                $UsuarioLog->setTipo("ESTADOUSUARIO");
                $UsuarioLog->setEstado("A");
                $UsuarioLog->setValorAntes($Usuario->estado);
                $UsuarioLog->setValorDespues('I');
                $UsuarioLog->setUsucreaId(0);
                $UsuarioLog->setUsumodifId(0);


                $UsuarioLogMySqlDAO->insert($UsuarioLog);


                $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);
                $UsuarioMySqlDAO->update($Usuario);
                $Transaction->commit();
            }

            //RECHAZADO CON STATUS PROCESSED Y DECISION REJECTED

            if ($status == "PROCESSED" && $Response2->decision->type == "REJECTED") {
                $decisionType = true;
                $Rechaza = true;
            }

            //ID DE DOCUMENTO EN USABILIDAD  DIFERENTE DE PASSED
            foreach ($Response2->capabilities->usability[0]->credentials as $Key => $value) {
                if ($value->category == "ID") {
                    if ($Response2->capabilities->usability[0]->decision->type != "PASSED") {
                        if ($Response2->capabilities->usability[0]->decision->type == "REJECTED") {
                            $usability = true;
                        }
                        $Rechaza = true;
                    }
                }
            }


            //FACEMAP DIFERENTE DE PASSED
            foreach ($Response2->capabilities->liveness[0]->credentials as $Key => $value) {
                if ($value->category == "FACEMAP") {
                    if ($Response2->capabilities->liveness[0]->decision->type != "PASSED") {
                        if ($Response2->capabilities->liveness[0]->decision->type == "REJECTED") {
                            $liveness = true;
                        }
                        $Rechaza = true;
                    }
                }
            }

            //SELFIE DIFERENTE DE PASSED
            foreach ($Response2->capabilities->similarity[0]->credentials as $Key => $value) {
                if ($value->category == "SELFIE") {
                    if ($Response2->capabilities->similarity[0]->decision->type != "PASSED") {
                        $Verifica = true;
                    }
                }
            }

            //ID DE DOCUMENTO EN DATOS CHEQUEADOS  DIFERENTE DE PASSED
            foreach ($Response2->capabilities->dataChecks[0]->credentials as $Key => $value) {
                if ($value->category == "ID") {
                    if ($Response2->capabilities->dataChecks[0]->decision->type != "PASSED") {
                        if ($Response2->capabilities->dataChecks[0]->decision->type == "REJECTED") {
                            $dataChecks = true;
                        }
                        $Verifica = true;
                    }
                }
            }

            //ID DE DOCUMENTO en CHEQUEO DE IMAGENES  DIFERENTE DE PASSED
            foreach ($Response2->capabilities->imageChecks[0]->credentials as $Key => $value) {
                if ($value->category == "ID") {
                    if ($Response2->capabilities->imageChecks[0]->decision->type != "PASSED") {
                        if (($Response2->capabilities->imageChecks[0]->decision->type == "WARNING" && $Response2->capabilities->imageChecks[0]->decision->details->label == "REPEATED_FACE")) {
                            $repeatedFace = true;
                        }
                        if ($Response2->capabilities->imageChecks[0]->decision->type == "REJECTED") {
                            $imageChecks = true;
                        }
                        $Verifica = true;
                    }
                }
            }

            //ID DE DOCUMENTO EN EXTRACCIÓN DIFERENTE DE PASSED
            foreach ($Response2->capabilities->extraction[0]->credentials as $Key => $value) {
                if ($value->category == "ID") {
                    if ($Response2->capabilities->extraction[0]->decision->type != "PASSED") {
                        if ($Response2->capabilities->extraction[0]->decision->type == "REJECTED") {
                            $extraction = true;
                        }
                        $Verifica = true;
                    }
                }
            }

            //ID DE DOCUMENTO EN SIMILITUD DIFERENTE DE PASSED
            foreach ($Response2->capabilities->similarity[0]->credentials as $Key => $value) {
                if ($value->category == "ID") {
                    if ($Response2->capabilities->similarity[0]->decision->type != "PASSED") {
                        if ($Response2->capabilities->similarity[0]->decision->type == "REJECTED") {
                            $similarity = true;
                        }
                        $Verifica = true;
                    }
                }
            }

            //SELFIE EN USABILIDAD DIFERENTE DE PASSED
            foreach ($Response2->capabilities->usability[0]->credentials as $Key => $value) {
                if ($value->category == "SELFIE") {
                    if ($Response2->capabilities->usability[0]->decision->type != "PASSED") {
                        $Verifica = true;
                    }
                }
            }

            /* Verifica si el usuario no está verificado, no se ha realizado verificación y no ha sido rechazado, si cumple
            con lo mencionado se realiza el proceso de aprobación de la verificación. */
            if ($Usuario->verificado != "S" && $Verifica == false && $Rechaza == false) {
                if ($_ENV['debug']) {
                    print_r('ENTRO APROBADO');
                    print_r($Verifica);
                    print_r($Rechaza);
                }

                $UsuarioVerificacion = new UsuarioVerificacion('', $Usuario->usuarioId, 'I', 'USUVERIFICACION');

                $UsuarioVerificacion->setEstado('A');
                $UsuarioVerificacion->setObservacion('Aprobado por Jumio');

                $UsuarioVerificacionMySqlDAO = new UsuarioVerificacionMySqlDAO();
                $UsuarioVerificacionMySqlDAO->update($UsuarioVerificacion);
                $UsuarioVerificacionMySqlDAO->getTransaction()->commit();

                $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                $Transaction = $UsuarioMySqlDAO->getTransaction();

                $VerificacionLog = new VerificacionLog();
                $VerificacionLog->setUsuverificacionId($UsuarioVerificacion->getUsuverificacionId());
                $VerificacionLog->setJson(json_encode($Response2));
                $VerificacionLog->setTipo('FINALDECISION');

                $VerificacionLogMySqlDAO = new VerificacionLogMySqlDAO($Transaction);
                $VerificacionLogMySqlDAO->insert($VerificacionLog);
                $Transaction->commit();

                $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                $Transaction = $UsuarioMySqlDAO->getTransaction();

                $Usuario->setAccountIdJumio($Response2->account->id);
                $Usuario->setVerificado("S");
                $Usuario->setVerifcedulaAnt("S");
                $Usuario->setVerifcedulaPost("S");

                $Usuario->setFechaVerificado(date("Y-m-d H:i:s"));

                $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);
                $UsuarioMySqlDAO->update($Usuario);
                $Transaction->commit();

                $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                $Transaction = $UsuarioMySqlDAO->getTransaction();

                $idLogA = 0;
                $idLogP = 0;

                //Guardar DNI Anterior y posterior en base de datos extraido de la data de JUMIO
                foreach ($Response2->credentials as $key1 => $value1) {
                    foreach ($value1->parts as $key => $value) {
                        if ($value->classifier == "FRONT") {
                            $tipo = 'USUDNIANTERIOR';

                            $Imagen = $this->connectionGETDetail($value->href);

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

                            if ( ! file_exists('/home/home2/backend/images/c/')) {
                                mkdir('/home/home2/backend/images/c/', 0755, true);
                            }

                            $dirsave = '/home/home2/backend/images/c/' . $filename;
                            file_put_contents($dirsave, $data);

                            shell_exec('export BOTO_CONFIG=/home/backend/.config/gcloud/legacy_credentials/admin@bpoconsultores.com.co/.boto && export PATH=/sbin:/bin:/usr/sbin:/usr/bin:/home/backend/google-cloud-sdk/bin && gsutil -h "Cache-Control:public,max-age=604800" cp ' . $dirsave . ' gs://cedulas-1/c/');
                        }

                        if ($value->classifier == "BACK") {
                            $tipo = 'USUDNIPOSTERIOR';

                            $Imagen = $this->connectionGETDetail($value->href);

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

                            if ( ! file_exists('/home/home2/backend/images/c/')) {
                                mkdir('/home/home2/backend/images/c/', 0755, true);
                            }

                            $dirsave = '/home/home2/backend/images/c/' . $filename;
                            file_put_contents($dirsave, $data);

                            shell_exec('export BOTO_CONFIG=/home/backend/.config/gcloud/legacy_credentials/admin@bpoconsultores.com.co/.boto && export PATH=/sbin:/bin:/usr/sbin:/usr/bin:/home/backend/google-cloud-sdk/bin && gsutil -h "Cache-Control:public,max-age=604800" cp ' . $dirsave . ' gs://cedulas-1/c/');
                        }
                    }
                }

                //Guardar Primer nombre extraido de la data de Jumio
                if ($Response2->capabilities->extraction[0]->data->firstName != "") {
                    $Nombre = explode(" ", $Response2->capabilities->extraction[0]->data->firstName);
                    $firstName = str_replace(["ñ", "Ñ"], ["n", "N"], $Nombre[0]);
                    $secondName = str_replace(["ñ", "Ñ"], ["n", "N"], $Nombre[1]);

                    $UsuarioLog = new UsuarioLog2();
                    $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                    $UsuarioLog->setUsuarioIp('');
                    $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
                    $UsuarioLog->setUsuariosolicitaIp("");
                    $UsuarioLog->setTipo("USUNOMBRE1");
                    $UsuarioLog->setEstado("A");
                    $UsuarioLog->setValorAntes($Registro->getNombre1());
                    $UsuarioLog->setValorDespues($firstName);
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
                    $UsuarioLog->setValorDespues($secondName);
                    $UsuarioLog->setUsucreaId(0);
                    $UsuarioLog->setUsumodifId(0);
                    $UsuarioLog->setSversion($UsuarioVerificacion->getUsuverificacionId());
                    $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);
                    $UsuarioLogMySqlDAO2->insert($UsuarioLog);

                    $Nombre = explode(" ", $Response2->capabilities->extraction[0]->data->firstName);
                    $Apellidos = explode(" ", $Response2->capabilities->extraction[0]->data->lastName);
                    $Usuario->SetNombre($Nombre[0] . " " . $Apellidos[0]);

                    $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);
                    $UsuarioMySqlDAO->update($Usuario);
                }

                if ($Response2->capabilities->extraction[0]->data->lastName != "") {
                    $Apellidos = explode(" ", $Response2->capabilities->extraction[0]->data->lastName);
                    $firstLastName = str_replace(["ñ", "Ñ"], ["n", "N"], $Apellidos[0]);
                    $secondLastName = str_replace(["ñ", "Ñ"], ["n", "N"], $Apellidos[1]);

                    $UsuarioLog = new UsuarioLog2();
                    $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                    $UsuarioLog->setUsuarioIp('');
                    $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
                    $UsuarioLog->setUsuariosolicitaIp("");
                    $UsuarioLog->setTipo("USUAPELLIDO1");
                    $UsuarioLog->setEstado("A");
                    $UsuarioLog->setValorAntes($Registro->getApellido1());
                    $UsuarioLog->setValorDespues($firstLastName);
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
                    $UsuarioLog->setValorDespues($secondLastName);
                    $UsuarioLog->setUsucreaId(0);
                    $UsuarioLog->setUsumodifId(0);
                    $UsuarioLog->setSversion($UsuarioVerificacion->getUsuverificacionId());

                    $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);
                    $UsuarioLogMySqlDAO2->insert($UsuarioLog);

                    $Nombre = explode(" ", $Response2->capabilities->extraction[0]->data->firstName);
                    $Apellidos = explode(" ", $Response2->capabilities->extraction[0]->data->lastName);
                    $Registro->setNombre($Nombre[0] . " " . $Nombre[1] . " " . $Apellidos[0] . " " . $Apellidos[1]);
                    $Registro->setNombre1($Nombre[0]);
                    $Registro->setNombre2($Nombre[1]);
                    $Registro->setApellido1($Apellidos[0]);
                    $Registro->setApellido2($Apellidos[1]);

                    $RegistroMySqlDAO = new RegistroMySqlDAO($Transaction);
                    $RegistroMySqlDAO->update($Registro);
                }

                if ($Response2->capabilities->extraction[0]->data->dateOfBirth != "") {
                    $UsuarioLog = new UsuarioLog2();
                    $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                    $UsuarioLog->setUsuarioIp('');
                    $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
                    $UsuarioLog->setUsuariosolicitaIp("");
                    $UsuarioLog->setTipo("USUFECHANACIM");
                    $UsuarioLog->setEstado("A");
                    $UsuarioLog->setValorAntes($UsuarioOtraInfo->getFechaNacim());
                    $UsuarioLog->setValorDespues($Response2->capabilities->extraction[0]->data->dateOfBirth);
                    $UsuarioLog->setUsucreaId(0);
                    $UsuarioLog->setUsumodifId(0);
                    $UsuarioLog->setSversion($UsuarioVerificacion->getUsuverificacionId());

                    $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);
                    $UsuarioLogMySqlDAO2->insert($UsuarioLog);

                    $fechaNacimiento = $Response2->capabilities->extraction[0]->data->dateOfBirth;
                    $UsuarioOtraInfo->setFechaNacim($fechaNacimiento);

                    $UsuarioOtrainfoMySqlDAO = new UsuarioOtrainfoMySqlDAO($Transaction);
                    $UsuarioOtrainfoMySqlDAO->update($UsuarioOtraInfo);
                }

                if ($Response2->account->id != "") {
                    $UsuarioLog = new UsuarioLog2();
                    $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                    $UsuarioLog->setUsuarioIp('');
                    $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
                    $UsuarioLog->setUsuariosolicitaIp("");
                    $UsuarioLog->setTipo("ACCOUNTIDJUMIO");
                    $UsuarioLog->setEstado("A");
                    $UsuarioLog->setValorAntes($Usuario->getAccountIdJumio());
                    $UsuarioLog->setValorDespues($Response2->account->id);
                    $UsuarioLog->setUsucreaId(0);
                    $UsuarioLog->setUsumodifId(0);
                    $UsuarioLog->setSversion($UsuarioVerificacion->getUsuverificacionId());

                    $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);
                    $UsuarioLogMySqlDAO2->insert($UsuarioLog);
                }

                $Transaction->commit();

                //Asignación de bonos por registro y verificación
                try {
                    $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);

                    $jsonW = '{"rules" : [{"field" : "sitio_tracking.tabla", "data": "registro_type_gift","op":"eq"},{"field" : "sitio_tracking.tabla_id", "data": "' . $Usuario->usuarioId . '","op":"eq"}] ,"groupOp" : "AND"}';

                    $SitioTracking = new SitioTracking();
                    $sitiosTracking = $SitioTracking->getSitioTrackingesCustom(" sitio_tracking.* ", "sitio_tracking.sitiotracking_id", "asc", 0, 1, $jsonW, true);
                    $sitiosTracking = json_decode($sitiosTracking);

                    $type_gift = $sitiosTracking->data[0]->{'sitio_tracking.tvalue'};

                    if ($type_gift != '') {
                        $asignacionDinamica = false;
                        $bonoIdd = null;

                        //Verificando existencia de bonos dinámicos
                        try {
                            $tipoBonoSeleccionado = null;
                            $Clasificador = new Clasificador('', 'BONUSFORLANDING');
                            $MandanteDetalle = new MandanteDetalle('', $Usuario->mandante, $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');

                            $patronesBono = [
                                3 => '#deportiva#', //deportiva
                                5 => '#casino#', //FreeCasino
                                6 => '#deportiva#', //FreeBet
                                8 => '#casino#' //FreeCasino
                            ];

                            foreach ($patronesBono as $tipoBonoId => $patronBono) {
                                //Identificando bono seleccionado por el usuario
                                if (preg_match($patronBono, $type_gift)) {
                                    $tipoBonoSeleccionado = $tipoBonoId;
                                    break;
                                }
                            }

                            //Verificando que haya un bono del tipo seleccionado por el usuario
                            $ofertaBonos = explode(',', $MandanteDetalle->valor);
                            if (empty($ofertaBonos)) {
                                throw new Exception('', 34);
                            }

                            foreach ($ofertaBonos as $bonoOfertado) {
                                $BonoInterno = new BonoInterno($bonoOfertado);
                                foreach ($patronesBono as $tipoBonoId => $patronBono) {
                                    //Identificando bono seleccionado por el usuario
                                    if (preg_match($patronBono, $type_gift)) {
                                        $tipoBonoSeleccionado = $tipoBonoId;
                                        if ($BonoInterno->tipo == $tipoBonoSeleccionado) {
                                            $bonoIdd = $bonoOfertado;
                                        }
                                    }
                                }
                            }
                            if (empty($bonoIdd)) {
                                throw new Exception('', 34);
                            }

                            $detalles = array(
                                "Depositos" => 0,
                                "DepositoEfectivo" => false,
                                "MetodoPago" => 0,
                                "ValorDeposito" => 0,
                                "PaisPV" => 0,
                                "DepartamentoPV" => 0,
                                "CiudadPV" => 0,
                                "PuntoVenta" => 0,
                                "PaisUSER" => $Usuario->paisId,
                                "DepartamentoUSER" => 0,
                                "CiudadUSER" => $Registro->ciudadId,
                                "MonedaUSER" => $Usuario->moneda,
                                "CodePromo" => ''
                            );

                            $detalles = json_decode(json_encode($detalles));

                            $BonoInterno = new BonoInterno();
                            $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();
                            $Transaction = $BonoInternoMySqlDAO->getTransaction();

                            $responseBonus = $BonoInterno->agregarBonoFree($bonoIdd, $Usuario->usuarioId, $Usuario->mandante, $detalles, true, "", $Transaction);

                            if ($responseBonus->WinBonus) {
                            }
                            $asignacionDinamica = true;
                            $Transaction->commit();
                        } catch (Exception $e) {
                            //Si el bono dinámico no está configurado o falla se continúa con los bonos estáticos
                        }
                    }
                } catch (Exception $e) {
                }

                $codigoBD = $Registro->getCodpromocionalId();

                if ($codigoBD == '2898') {
                    $detalles = array(
                        "Depositos" => 0,
                        "DepositoEfectivo" => false,
                        "MetodoPago" => 0,
                        "ValorDeposito" => 0,
                        "PaisPV" => 0,
                        "DepartamentoPV" => 0,
                        "CiudadPV" => 0,
                        "PuntoVenta" => 0,
                        "PaisUSER" => $Usuario->paisId,
                        "DepartamentoUSER" => 0,
                        "CiudadUSER" => $Registro->ciudadId,
                        "MonedaUSER" => $Usuario->moneda,
                        "CodePromo" => $codigoBD
                    );

                    $detalles = json_decode(json_encode($detalles));

                    $BonoInterno = new BonoInterno();
                    $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();
                    $Transaction = $BonoInternoMySqlDAO->getTransaction();
                    $responseBonus = $BonoInterno->agregarBono("", $Usuario->usuarioId, $Usuario->mandante, $detalles, $Transaction);
                    if ($responseBonus->WinBonus) {
                        $Transaction->commit();
                    }
                }

                //$DocumentJumio = $Response2->capabilities->extraction[0]->data->documentNumber;
                if (is_array($DocumentJumio)) {
                    $DocumentJumio = implode($DocumentJumio);
                } else {
                    $DocumentJumio = $Response2->capabilities->extraction[0]->data->documentNumber;
                }

                try {
                    if (isset($Response2->capabilities->extraction[0]->data->expiryDate)) {
                        $ClasificadorFiltro = new Clasificador("", "EXPIRYDATE");
                        $expiryDate = $Response2->capabilities->extraction[0]->data->expiryDate;

                        try {
                            $UsuarioConfiguracion = new UsuarioConfiguracion($Usuario->usuarioId, "A", $ClasificadorFiltro->getClasificadorId());
                        } catch (Exception $e) {
                            if ($e->getCode() == 46) {
                                $UsuarioConfiguracion = new UsuarioConfiguracion();
                                $UsuarioConfiguracion->tipo = $ClasificadorFiltro->clasificadorId;
                                $UsuarioConfiguracion->valor = $expiryDate;
                                $UsuarioConfiguracion->usuarioId = $Usuario->usuarioId;
                                $UsuarioConfiguracion->estado = 'A';

                                $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
                                $Transaction = $UsuarioConfiguracionMySqlDAO->getTransaction();
                                $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);
                                $Transaction->commit();
                            }
                        }
                    }
                } catch (Exception $e) {
                }

                try {
                    //ENVIO POPUP ESTADO EXITOSO - TEMPLATE
                    $ClasificadorFiltro = new Clasificador("", "ISACTIVEPOPUP");
                    $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $ClasificadorFiltro->getClasificadorId(), $Usuario->paisId, "A");
                    $Mandante = new Mandante($Usuario->mandante);

                    if ($MandanteDetalle->valor == 1) {
                        $IsActivePopUp = "A";
                    } else {
                        $IsActivePopUp = "I";
                    }
                    if ($IsActivePopUp == "A") {
                        $abreviado = "VERIFICACIONEXITOSA";
                        $msg = 'Respuesta de Verificación Exitosa';
                        $tipo = "MESSAGEINV";

                        $envioPopups = $this->envioPopups($abreviado, $msg, $UsuarioMandante, $Usuario, $tipo, $Registro, $Mandante);
                    }
                } catch (Exception $e) {
                }

                $response = json_encode($Response2);

                try {
                    try {
                        //ENVIO SMS APROBADA- TEMPLATE
                        $ClasificadorFiltro = new Clasificador("", "ISACTIVESMS");
                        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $ClasificadorFiltro->getClasificadorId(), $Usuario->paisId, $Usuario->estado);
                        $Mandante = new Mandante($Usuario->mandante);

                        if ($MandanteDetalle->valor == 1) {
                            $IsActiveSms = "A";
                        } else {
                            $IsActiveSms = "I";
                        }

                        if ($IsActiveSms == "A") {
                            $abreviado = "APROSMSJUMIO";
                            $msg = 'Respuesta de Verificación Exitosa';
                            $tipo = "SMS";

                            $envioPopups = $this->envioPopups($abreviado, $msg, $UsuarioMandante, $Usuario, $tipo, $Registro, $Mandante);
                        }
                    } catch (Exception $e) {
                    }

                    try {
                        //ENVIO EMAIL APROBADA - TEMPLATE
                        $ClasificadorFiltro = new Clasificador("", "ISACTIVEEMAIL");
                        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $ClasificadorFiltro->getClasificadorId(), $Usuario->paisId, $Usuario->estado);
                        $Mandante = new Mandante($Usuario->mandante);

                        if ($MandanteDetalle->valor == 1) {
                            $IsActiveEmail = "A";
                        } else {
                            $IsActiveEmail = "I";
                        }

                        if ($IsActiveEmail == "A") {
                            $abreviado = "APROEMAILJUMIO";
                            $msg = 'Respuesta de Verificación Exitosa';
                            $tipo = "MENSAJE";

                            $envioPopups = $this->envioPopups($abreviado, $msg, $UsuarioMandante, $Usuario, $tipo, $Registro, $Mandante);
                        }
                    } catch (Exception $e) {
                    }

                    try {
                        //ENVIO INBOX APROBADA - TEMPLATE
                        $ClasificadorFiltro = new Clasificador("", "ISACTIVEINBOX");
                        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $ClasificadorFiltro->getClasificadorId(), $Usuario->paisId, $Usuario->estado);
                        $Mandante = new Mandante($Usuario->mandante);

                        if ($MandanteDetalle->valor == 1) {
                            $IsActiveInbox = "A";
                        } else {
                            $IsActiveInbox = "I";
                        }

                        if ($IsActiveInbox == "A") {
                            $abreviado = "APROINBOXJUMIO";
                            $msg = 'Respuesta de Verificación Exitosa';
                            $tipo = "MENSAJE";

                            $envioPopups = $this->envioPopups($abreviado, $msg, $UsuarioMandante, $Usuario, $tipo, $Registro, $Mandante);
                        }
                    } catch (Exception $e) {
                    }
                } catch (Exception $e) {
                }
                return $response;
            } elseif ($Usuario->verificado != "S" && $Verifica == true && $Rechaza == false && $documentIncorrect == false) {
                if ($_REQUEST['test'] == '2') {
                    print_r('ENTRO PENDIENTE');
                    print_r($Verifica);
                    print_r($Rechaza);
                    print_r($documentIncorrect);
                }

                $UsuarioVerificacion = new UsuarioVerificacion('', $Usuario->usuarioId, 'I', 'USUVERIFICACION');
                $Usuario = new Usuario($UsuarioVerificacion->getUsuarioId());

                $UsuarioVerificacion->setEstado('P');
                $UsuarioVerificacion->setObservacion('Pendiente Verificación Manual por Repeated Face Usuario: ' . $Usuario->usuarioId);

                $UsuarioVerificacionMySqlDAO = new UsuarioVerificacionMySqlDAO();
                $UsuarioVerificacionMySqlDAO->update($UsuarioVerificacion);
                $UsuarioVerificacionMySqlDAO->getTransaction()->commit();

                $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                $Transaction = $UsuarioMySqlDAO->getTransaction();

                $Usuario->setAccountIdJumio($accountId);

                $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);
                $UsuarioMySqlDAO->update($Usuario);
                $Transaction->commit();

                $VerificacionLog = new VerificacionLog();
                $VerificacionLog->setUsuverificacionId($UsuarioVerificacion->getUsuverificacionId());
                $VerificacionLog->setTipo('FINALDECISION');
                $VerificacionLog->setJson(json_encode($Response2));

                $UsuarioVerificaId = $UsuarioVerificacion->getUsuverificacionId();
                $VerificacionLogMySqlDAO = new VerificacionLogMySqlDAO($Transaction);
                $VerificacionLogMySqlDAO->insert($VerificacionLog);

                foreach ($Response2->credentials as $key1 => $value1) {
                    foreach ($value1->parts as $key => $value) {
                        /* Registra un log de usuario con la imagen de identificación frontal y la guarda en un directorio y en Google Cloud Storage.*/
                        if ($value->classifier == "FRONT") {
                            $tipo = 'USUDNIANTERIOR';

                            $Imagen = $this->connectionGETDetail($value->href);

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
                            $UsuarioLog->setSversion($UsuarioVerificaId);

                            $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);

                            $UsuarioLogMySqlDAO2->insert($UsuarioLog);
                        }

                        /* Registra un log de usuario con la imagen de identificación posterior y la guarda en un directorio y en Google Cloud Storage.*/
                        if ($value->classifier == "BACK") {
                            $tipo = 'USUDNIPOSTERIOR';

                            $Imagen = $this->connectionGETDetail($value->href);

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
                            $UsuarioLog->setSversion($UsuarioVerificaId);
                            $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);

                            $UsuarioLogMySqlDAO2->insert($UsuarioLog);
                        }
                    }
                }
                //Guardar Primer nombre extraído de la data de Jumio
                if ($Response2->capabilities->extraction[0]->data->firstName != "") {
                    $Nombre = explode(" ", $Response2->capabilities->extraction[0]->data->firstName);
                    $firstName = str_replace(["ñ", "Ñ"], ["n", "N"], $Nombre[0]);
                    $secondName = str_replace(["ñ", "Ñ"], ["n", "N"], $Nombre[1]);

                    $UsuarioLog = new UsuarioLog2();
                    $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                    $UsuarioLog->setUsuarioIp('');
                    $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
                    $UsuarioLog->setUsuariosolicitaIp("");
                    $UsuarioLog->setTipo("USUNOMBRE1");
                    $UsuarioLog->setEstado("P");
                    $UsuarioLog->setValorAntes($Registro->getNombre1());
                    $UsuarioLog->setValorDespues($firstName);
                    $UsuarioLog->setUsucreaId(0);
                    $UsuarioLog->setUsumodifId(0);
                    $UsuarioLog->setSversion($UsuarioVerificaId);
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
                    $UsuarioLog->setValorDespues($secondName);
                    $UsuarioLog->setUsucreaId(0);
                    $UsuarioLog->setUsumodifId(0);
                    $UsuarioLog->setSversion($UsuarioVerificaId);
                    $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);
                    $UsuarioLogMySqlDAO2->insert($UsuarioLog);
                }
                //Guardar Segundo nombre extraído de la data de Jumio
                if ($Response2->capabilities->extraction[0]->data->lastName != "") {
                    $Apellidos = explode(" ", $Response2->capabilities->extraction[0]->data->lastName);
                    $firstLastName = str_replace(["ñ", "Ñ"], ["n", "N"], $Apellidos[0]);
                    $secondLastName = str_replace(["ñ", "Ñ"], ["n", "N"], $Apellidos[1]);

                    $UsuarioLog = new UsuarioLog2();
                    $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                    $UsuarioLog->setUsuarioIp('');
                    $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
                    $UsuarioLog->setUsuariosolicitaIp("");
                    $UsuarioLog->setTipo("USUAPELLIDO1");
                    $UsuarioLog->setEstado("P");
                    $UsuarioLog->setValorAntes($Registro->getApellido1());
                    $UsuarioLog->setValorDespues($firstLastName);
                    $UsuarioLog->setUsucreaId(0);
                    $UsuarioLog->setUsumodifId(0);
                    $UsuarioLog->setSversion($UsuarioVerificaId);
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
                    $UsuarioLog->setValorDespues($secondLastName);
                    $UsuarioLog->setUsucreaId(0);
                    $UsuarioLog->setUsumodifId(0);
                    $UsuarioLog->setSversion($UsuarioVerificaId);
                    $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);
                    $UsuarioLogMySqlDAO2->insert($UsuarioLog);
                }
                //Guardar fecha nacimiento extraído de la data de Jumio
                if ($Response2->capabilities->extraction[0]->data->dateOfBirth != "") {
                    $UsuarioLog = new UsuarioLog2();
                    $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                    $UsuarioLog->setUsuarioIp('');
                    $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
                    $UsuarioLog->setUsuariosolicitaIp("");
                    $UsuarioLog->setTipo("USUFECHANACIM");
                    $UsuarioLog->setEstado("P");
                    $UsuarioLog->setValorAntes($UsuarioOtraInfo->getFechaNacim());
                    $UsuarioLog->setValorDespues($Response2->capabilities->extraction[0]->data->dateOfBirth);
                    $UsuarioLog->setUsucreaId(0);
                    $UsuarioLog->setUsumodifId(0);
                    $UsuarioLog->setSversion($UsuarioVerificaId);

                    $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);
                    $UsuarioLogMySqlDAO2->insert($UsuarioLog);
                }
                //Guardar id de Verificación extraído de la data de Jumio
                if ($Response2->account->id != "") {
                    $UsuarioLog = new UsuarioLog2();
                    $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                    $UsuarioLog->setUsuarioIp('');
                    $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
                    $UsuarioLog->setUsuariosolicitaIp("");
                    $UsuarioLog->setTipo("ACCOUNTIDJUMIO");
                    $UsuarioLog->setEstado("P");
                    $UsuarioLog->setValorAntes($Usuario->getAccountIdJumio());
                    $UsuarioLog->setValorDespues($Response2->account->id);
                    $UsuarioLog->setUsucreaId(0);
                    $UsuarioLog->setUsumodifId(0);
                    $UsuarioLog->setSversion($UsuarioVerificacion->getUsuverificacionId());

                    $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);
                    $UsuarioLogMySqlDAO2->insert($UsuarioLog);
                }

                try {
                    //ENVIO POPUP ESTADO PENDIENTE - TEMPLATE
                    $ClasificadorFiltro = new Clasificador("", "ISACTIVEPOPUP");
                    $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $ClasificadorFiltro->getClasificadorId(), $Usuario->paisId, "A");
                    $Mandante = new Mandante($Usuario->mandante);

                    if ($MandanteDetalle->valor == 1) {
                        $IsActivePopUp = "A";
                    } else {
                        $IsActivePopUp = "I";
                    }

                    if ($IsActivePopUp == "A") {
                        $abreviado = "PENDPOPUPJUMIO";
                        $msg = 'Respuesta de Verificación Pendiente';
                        $tipo = "MESSAGEINV";

                        $envioPopups = $this->envioPopups($abreviado, $msg, $UsuarioMandante, $Usuario, $tipo, $Registro, $Mandante);
                    }
                } catch (Exception $e) {
                }

                $Transaction->commit();

                $response = json_encode($Response2);

                try {
                    try {
                        //ENVIO SMS ESTADO PENDIENTE - TEMPLATE
                        $ClasificadorFiltro = new Clasificador("", "ISACTIVESMS");
                        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $ClasificadorFiltro->getClasificadorId(), $Usuario->paisId, $Usuario->estado);
                        $Mandante = new Mandante($Usuario->mandante);

                        if ($MandanteDetalle->valor == 1) {
                            $IsActiveSms = "A";
                        } else {
                            $IsActiveSms = "I";
                        }

                        if ($IsActiveSms == "A") {
                            $abreviado = "PENDSMSJUMIO";
                            $msg = 'Respuesta de Verificación Pendiente';
                            $tipo = "SMS";

                            $envioPopups = $this->envioPopups($abreviado, $msg, $UsuarioMandante, $Usuario, $tipo, $Registro, $Mandante);
                        }
                    } catch (Exception $e) {
                    }

                    try {
                        //ENVIO EMAIL PENDIENTE - TEMPLATE
                        $ClasificadorFiltro = new Clasificador("", "ISACTIVEEMAIL");
                        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $ClasificadorFiltro->getClasificadorId(), $Usuario->paisId, $Usuario->estado);
                        $Mandante = new Mandante($Usuario->mandante);

                        if ($MandanteDetalle->valor == 1) {
                            $IsActiveEmail = "A";
                        } else {
                            $IsActiveEmail = "I";
                        }

                        if ($IsActiveEmail == "A") {
                            $abreviado = "PENDEMAILJUMIO";
                            $msg = 'Respuesta de Verificación Pendiente';
                            $tipo = "MENSAJE";

                            $envioPopups = $this->envioPopups($abreviado, $msg, $UsuarioMandante, $Usuario, $tipo, $Registro, $Mandante);
                        }
                    } catch (Exception $e) {
                    }

                    try {
                        //ENVIO INBOX PENDIENTE - TEMPLATE
                        $ClasificadorFiltro = new Clasificador("", "ISACTIVEINBOX");
                        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $ClasificadorFiltro->getClasificadorId(), $Usuario->paisId, $Usuario->estado);
                        $Mandante = new Mandante($Usuario->mandante);

                        if ($MandanteDetalle->valor == 1) {
                            $IsActiveInbox = "A";
                        } else {
                            $IsActiveInbox = "I";
                        }

                        if ($IsActiveInbox == "A") {
                            $abreviado = "PENDINBOXJUMIO";
                            $msg = 'Respuesta de Verificación Pendiente';
                            $tipo = "MENSAJE";

                            $envioPopups = $this->envioPopups($abreviado, $msg, $UsuarioMandante, $Usuario, $tipo, $Registro, $Mandante);
                        }
                    } catch (Exception $e) {
                    }
                } catch (Exception $e) {
                }
                return $response;
                /* Verifica si el usuario está verificado o ha sido rechazado o el documento es incorrecto.
                 si cumple con lo mencionado el usuario queda en estado Rechazado.*/
            } elseif ($Usuario->verificado == "S" || $Rechaza == true || $documentIncorrect == true) {
                if ($_REQUEST['test'] == '3') {
                    print_r('ENTRO RECHAZADO 1');
                    print_r(PHP_EOL);
                    print_r($Verifica);
                    print_r($Rechaza);
                    print_r($documentIncorrect);
                }

                $Temp = '';
                if ($Usuario->verificado == "S") {
                    $Temp = " Usuario ya esta verificado";
                }

                $UsuarioVerificacion = new UsuarioVerificacion('', $Usuario->usuarioId, 'I', 'USUVERIFICACION');

                $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                $Transaction = $UsuarioMySqlDAO->getTransaction();

                /* Verifica el valor de la variable `$status`. Si el valor de `$status` es "TOKEN_EXPIRED",
                "SESSION_EXPIRED" o "NOT_EXECUTED", entonces establece el estado de `$UsuarioVerificacion->setEstado`
                en 'NE' y la observación en "No ejecutado". */
                if ($status == "TOKEN_EXPIRED" || $status == "SESSION_EXPIRED" || $status == "NOT_EXECUTED") {
                    $UsuarioVerificacion->setEstado('NE');
                    $UsuarioVerificacion->setObservacion("No ejecutado");

                    $UsuarioVerificacionMySqlDAO = new UsuarioVerificacionMySqlDAO();
                    $UsuarioVerificacionMySqlDAO->update($UsuarioVerificacion);
                    $UsuarioVerificacionMySqlDAO->getTransaction()->commit();

                    try {
                        $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                        $Transaction = $UsuarioMySqlDAO->getTransaction();

                        $Usuario->setAccountIdJumio($accountId);
                        $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);
                        $UsuarioMySqlDAO->update($Usuario);
                        $Transaction->commit();

                        //ENVIO POPUP ESTADO RECHAZO POR NO EJECUTADO - TEMPLATE
                        $ClasificadorFiltro = new Clasificador("", "ISACTIVEPOPUP");
                        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $ClasificadorFiltro->getClasificadorId(), $Usuario->paisId, "A");
                        $Mandante = new Mandante($Usuario->mandante);

                        if ($MandanteDetalle->valor == 1) {
                            $IsActivePopUp = "A";
                        } else {
                            $IsActivePopUp = "I";
                        }

                        if ($IsActivePopUp == "A") {
                            $abreviado = "RECPOPUPNOEJECUTADO";
                            $msg = 'Respuesta de Verificación No Ejecutada';
                            $tipo = "MESSAGEINV";

                            $envioPopups = $this->envioPopups($abreviado, $msg, $UsuarioMandante, $Usuario, $tipo, $Registro, $Mandante);
                        }
                    } catch (Exception $e) {
                    }
                } else {
                    $UsuarioVerificacion->setEstado('R');

                    //Validación de número de rechazos.
                    if ($NumRechazos > 0) {
                        try {
                            $Clasificador = new Clasificador('', 'RECHAZADOVERIF');
                            $ClasificadorId = $Clasificador->getClasificadorId();
                            $UsuarioConfiguracion = new UsuarioConfiguracion($Usuario->usuarioId, 'A', $ClasificadorId);

                            if ($UsuarioConfiguracion->valor < $NumRechazos) {
                                $UsuarioConfiguracion->valor += 1;

                                $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                                $Transaction = $UsuarioMySqlDAO->getTransaction();
                                $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);
                                $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);
                                $Transaction->commit();
                            } else {
                                if ($UsuarioConfiguracion->valor >= $NumRechazos) {
                                    try {
                                        //ENVIO POPUP ESTADO RECHAZO POR INTENTOS - TEMPLATE
                                        $ClasificadorFiltro = new Clasificador("", "ISACTIVEPOPUP");
                                        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $ClasificadorFiltro->getClasificadorId(), $Usuario->paisId, "A");
                                        $Mandante = new Mandante($Usuario->mandante);

                                        if ($MandanteDetalle->valor == 1) {
                                            $IsActivePopUp = "A";
                                        } else {
                                            $IsActivePopUp = "I";
                                        }

                                        if ($IsActivePopUp == "A") {
                                            $abreviado = "RECPOPUPJUMIOINTENTOS";
                                            $msg = 'Respuesta de Verificación Rechazada por Intentos';
                                            $tipo = "MESSAGEINV";

                                            $envioPopups = $this->envioPopups($abreviado, $msg, $UsuarioMandante, $Usuario, $tipo, $Registro, $Mandante);
                                        }
                                    } catch (Exception $e) {
                                    }

                                    $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                                    $Transaction = $UsuarioMySqlDAO->getTransaction();

                                    $Usuario->setEstado("I");
                                    $Usuario->setAccountIdJumio($accountId);

                                    $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO($Transaction);

                                    $UsuarioLog = new UsuarioLog();
                                    $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                                    $UsuarioLog->setUsuarioIp('');

                                    $UsuarioLog->setUsuariosolicitaId(2);
                                    $UsuarioLog->setUsuariosolicitaIp('');

                                    $UsuarioLog->setTipo("ESTADOUSUARIO");
                                    $UsuarioLog->setEstado("A");
                                    $UsuarioLog->setValorAntes($Usuario->estado);
                                    $UsuarioLog->setValorDespues('I');
                                    $UsuarioLog->setUsucreaId(0);
                                    $UsuarioLog->setUsumodifId(0);


                                    $UsuarioLogMySqlDAO->insert($UsuarioLog);


                                    $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);
                                    $UsuarioMySqlDAO->update($Usuario);
                                    $Transaction->commit();
                                }
                            }
                        } catch (Exception $e) {
                            $Clasificador = new Clasificador('', 'RECHAZADOVERIF');
                            $ClasificadorId = $Clasificador->getClasificadorId();

                            $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                            $Transaction = $UsuarioMySqlDAO->getTransaction();
                            $UsuarioConfiguracion = new UsuarioConfiguracion();
                            $UsuarioConfiguracion->setUsuarioId($Usuario->usuarioId);
                            $UsuarioConfiguracion->setEstado('A');
                            $UsuarioConfiguracion->setTipo($ClasificadorId);
                            $UsuarioConfiguracion->setValor(1);
                            $UsuarioConfiguracion->setUsucreaId(0);
                            $UsuarioConfiguracion->setUsumodifId(0);
                            $UsuarioConfiguracion->setNota("");
                            $UsuarioConfiguracion->setProductoId(0);

                            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);
                            $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);
                            $Transaction->commit();
                        }
                    } elseif ($extraction == true) {
                        $UsuarioVerificacion->setObservacion("Rechazado por extracción de datos");

                        try {
                            //ENVIO POPUP ESTADO RECHAZADO POR DATOS - TEMPLATE
                            $ClasificadorFiltro = new Clasificador("", "ISACTIVEPOPUP");
                            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $ClasificadorFiltro->getClasificadorId(), $Usuario->paisId, "A");
                            $Mandante = new Mandante($Usuario->mandante);

                            if ($MandanteDetalle->valor == 1) {
                                $IsActivePopUp = "A";
                            } else {
                                $IsActivePopUp = "I";
                            }

                            if ($IsActivePopUp == "A") {
                                $abreviado = "RECPOPUPJUMIOEXTRACCION";
                                $msg = 'Respuesta de Verificación Rechazada por Extracción de Datos';
                                $tipo = "MESSAGEINV";

                                $envioPopups = $this->envioPopups($abreviado, $msg, $UsuarioMandante, $Usuario, $tipo, $Registro, $Mandante);
                            }
                        } catch (Exception $e) {
                        }
                    } elseif ($dataChecks == true) {
                        $UsuarioVerificacion->setObservacion("Rechazado por datos");

                        try {
                            //ENVIO POPUP ESTADO RECHAZADO POR DATOS - TEMPLATE
                            $ClasificadorFiltro = new Clasificador("", "ISACTIVEPOPUP");
                            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $ClasificadorFiltro->getClasificadorId(), $Usuario->paisId, "A");
                            $Mandante = new Mandante($Usuario->mandante);

                            if ($MandanteDetalle->valor == 1) {
                                $IsActivePopUp = "A";
                            } else {
                                $IsActivePopUp = "I";
                            }

                            if ($IsActivePopUp == "A") {
                                $abreviado = "RECPOPUPJUMIODATOS";
                                $msg = 'Respuesta de Verificación Rechazada por Extracción de Datos';
                                $tipo = "MESSAGEINV";

                                $envioPopups = $this->envioPopups($abreviado, $msg, $UsuarioMandante, $Usuario, $tipo, $Registro, $Mandante);
                            }
                        } catch (Exception $e) {
                        }
                    } elseif ($imageChecks == true) {
                        $UsuarioVerificacion->setObservacion("Rechazado por imagen");

                        try {
                            //ENVIO POPUP ESTADO RECHAZADO POR IMAGEN - TEMPLATE
                            $ClasificadorFiltro = new Clasificador("", "ISACTIVEPOPUP");
                            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $ClasificadorFiltro->getClasificadorId(), $Usuario->paisId, "A");
                            $Mandante = new Mandante($Usuario->mandante);

                            if ($MandanteDetalle->valor == 1) {
                                $IsActivePopUp = "A";
                            } else {
                                $IsActivePopUp = "I";
                            }

                            if ($IsActivePopUp == "A") {
                                $abreviado = "RECPOPUPJUMIOIMAGEN";
                                $msg = 'Respuesta de Verificación Rechazada por Imagen';
                                $tipo = "MESSAGEINV";

                                $envioPopups = $this->envioPopups($abreviado, $msg, $UsuarioMandante, $Usuario, $tipo, $Registro, $Mandante);
                            }
                        } catch (Exception $e) {
                        }
                    } elseif ($usability == true) {
                        $UsuarioVerificacion->setObservacion("Rechazado por usabilidad");

                        try {
                            //ENVIO POPUP ESTADO RECHAZADO POR USABILIDAD - TEMPLATE
                            $ClasificadorFiltro = new Clasificador("", "ISACTIVEPOPUP");
                            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $ClasificadorFiltro->getClasificadorId(), $Usuario->paisId, "A");
                            $Mandante = new Mandante($Usuario->mandante);

                            if ($MandanteDetalle->valor == 1) {
                                $IsActivePopUp = "A";
                            } else {
                                $IsActivePopUp = "I";
                            }

                            if ($IsActivePopUp == "A") {
                                $abreviado = "RECPOPUPJUMIOUSABILIDAD";
                                $msg = 'Respuesta de Verificación Rechazada por Usabilidad';
                                $tipo = "MESSAGEINV";

                                $envioPopups = $this->envioPopups($abreviado, $msg, $UsuarioMandante, $Usuario, $tipo, $Registro, $Mandante);
                            }
                        } catch (Exception $e) {
                        }
                    } elseif ($documentIncorrect == true) {
                        $UsuarioVerificacion->setObservacion("Rechazado por documento incorrecto");

                        try {
                            //ENVIO POPUP ESTADO RECHAZADO POR COINCIDENCIA - TEMPLATE
                            $ClasificadorFiltro = new Clasificador("", "ISACTIVEPOPUP");
                            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $ClasificadorFiltro->getClasificadorId(), $Usuario->paisId, "A");
                            $Mandante = new Mandante($Usuario->mandante);

                            if ($MandanteDetalle->valor == 1) {
                                $IsActivePopUp = "A";
                            } else {
                                $IsActivePopUp = "I";
                            }

                            if ($IsActivePopUp == "A") {
                                $abreviado = "RECPOPUPJUMIOCOINCIDENCIA";
                                $msg = 'Respuesta de Verificación Rechazada por Coincidencia';
                                $tipo = "MESSAGEINV";

                                $envioPopups = $this->envioPopups($abreviado, $msg, $UsuarioMandante, $Usuario, $tipo, $Registro, $Mandante);
                            }
                        } catch (Exception $e) {
                        }

                        if ($NumRechazosDocument > 0) {
                            try {
                                //RECHAZADO POR DOCUMENTO INCORRECTO
                                $Clasificador = new Clasificador('', 'RECHAZADOVERIFDOC');
                                $ClasificadorId = $Clasificador->getClasificadorId();
                                $UsuarioConfiguracion = new UsuarioConfiguracion($Usuario->usuarioId, 'A', $ClasificadorId);

                                if ($UsuarioConfiguracion->valor < $NumRechazosDocument) {
                                    $UsuarioConfiguracion->valor += 1;

                                    $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                                    $Transaction = $UsuarioMySqlDAO->getTransaction();
                                    $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);
                                    $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);
                                    $Transaction->commit();
                                } else {
                                    if ($UsuarioConfiguracion->valor >= $NumRechazosDocument) {
                                        try {
                                            //ENVIO POPUP ESTADO RECHAZO POR INTENTOS - TEMPLATE
                                            $ClasificadorFiltro = new Clasificador("", "ISACTIVEPOPUP");
                                            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $ClasificadorFiltro->getClasificadorId(), $Usuario->paisId, "A");
                                            $Mandante = new Mandante($Usuario->mandante);

                                            if ($MandanteDetalle->valor == 1) {
                                                $IsActivePopUp = "A";
                                            } else {
                                                $IsActivePopUp = "I";
                                            }

                                            if ($IsActivePopUp == "A") {
                                                $abreviado = "RECPOPUPJUMIOINTENTOS";
                                                $msg = 'Respuesta de Verificación Rechazada por Intentos';
                                                $tipo = "MESSAGEINV";

                                                $envioPopups = $this->envioPopups($abreviado, $msg, $UsuarioMandante, $Usuario, $tipo, $Registro, $Mandante);
                                            }
                                        } catch (Exception $e) {
                                        }

                                        $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                                        $Transaction = $UsuarioMySqlDAO->getTransaction();

                                        $Usuario->setEstado("I");

                                        $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO($Transaction);

                                        $UsuarioLog = new UsuarioLog();
                                        $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                                        $UsuarioLog->setUsuarioIp('');

                                        $UsuarioLog->setUsuariosolicitaId(3);
                                        $UsuarioLog->setUsuariosolicitaIp('');

                                        $UsuarioLog->setTipo("ESTADOUSUARIO");
                                        $UsuarioLog->setEstado("A");
                                        $UsuarioLog->setValorAntes($Usuario->estado);
                                        $UsuarioLog->setValorDespues('I');
                                        $UsuarioLog->setUsucreaId(0);
                                        $UsuarioLog->setUsumodifId(0);


                                        $UsuarioLogMySqlDAO->insert($UsuarioLog);

                                        $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);
                                        $UsuarioMySqlDAO->update($Usuario);
                                        $Transaction->commit();
                                    }
                                }
                            } catch (Exception $e) {
                                $Clasificador = new Clasificador('', 'RECHAZADOVERIFDOC');
                                $ClasificadorId = $Clasificador->getClasificadorId();

                                $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                                $Transaction = $UsuarioMySqlDAO->getTransaction();
                                $UsuarioConfiguracion = new UsuarioConfiguracion();
                                $UsuarioConfiguracion->setUsuarioId($Usuario->usuarioId);
                                $UsuarioConfiguracion->setEstado('A');
                                $UsuarioConfiguracion->setTipo($ClasificadorId);
                                $UsuarioConfiguracion->setValor(1);
                                $UsuarioConfiguracion->setUsucreaId(0);
                                $UsuarioConfiguracion->setUsumodifId(0);
                                $UsuarioConfiguracion->setNota("");
                                $UsuarioConfiguracion->setProductoId(0);

                                $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);
                                $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);
                                $Transaction->commit();
                            }
                        }
                    } elseif ($liveness == true) {
                        $UsuarioVerificacion->setObservacion("Rechazado por vivacidad");
                    } elseif ($decisionType == true) {
                        $UsuarioVerificacion->setObservacion("Rechazado por Puntuación de alto riesgo");
                    } elseif ($similarity == true) {
                        $UsuarioVerificacion->setObservacion("Rechazado por semejanza");
                    }
                }

                $UsuarioVerificacionMySqlDAO = new UsuarioVerificacionMySqlDAO();
                $UsuarioVerificacionMySqlDAO->update($UsuarioVerificacion);
                $UsuarioVerificacionMySqlDAO->getTransaction()->commit();

                $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                $Transaction = $UsuarioMySqlDAO->getTransaction();

                $VerificacionLog = new VerificacionLog();
                $VerificacionLog->setUsuverificacionId($UsuarioVerificacion->getUsuverificacionId());
                $VerificacionLog->setTipo('FINALDECISION');
                $VerificacionLog->setJson(json_encode($Response2));


                $VerificacionLogMySqlDAO = new VerificacionLogMySqlDAO($Transaction);
                $VerificacionLogMySqlDAO->insert($VerificacionLog);

                //Guardar DNI Anterior y posterior en base de datos extraido de la data de JUMIO
                foreach ($Response2->credentials as $key1 => $value1) {
                    foreach ($value1->parts as $key => $value) {
                        if ($value->classifier == "FRONT") {
                            $tipo = 'USUDNIANTERIOR';

                            $Imagen = $this->connectionGETDetail($value->href);

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
                        }

                        if ($value->classifier == "BACK") {
                            $tipo = 'USUDNIPOSTERIOR';
                            //$file_contents1  = file_get_contents($value->href);

                            $Imagen = $this->connectionGETDetail($value->href);

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
                        }
                    }
                }
                //Guardar Primer y segundo nombre extraído de la data de Jumio
                if ($Response2->capabilities->extraction[0]->data->firstName != "") {
                    $Nombre = explode(" ", $Response2->capabilities->extraction[0]->data->firstName);
                    $firstName = str_replace(["ñ", "Ñ"], ["n", "N"], $Nombre[0]);
                    $secondName = str_replace(["ñ", "Ñ"], ["n", "N"], $Nombre[1]);

                    $UsuarioLog = new UsuarioLog2();
                    $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                    $UsuarioLog->setUsuarioIp('');
                    $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
                    $UsuarioLog->setUsuariosolicitaIp("");
                    $UsuarioLog->setTipo("USUNOMBRE1");
                    $UsuarioLog->setEstado("R");
                    $UsuarioLog->setValorAntes($Registro->getNombre1());
                    $UsuarioLog->setValorDespues($firstName);
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
                    $UsuarioLog->setValorDespues($secondName);
                    $UsuarioLog->setUsucreaId(0);
                    $UsuarioLog->setUsumodifId(0);
                    $UsuarioLog->setSversion($UsuarioVerificacion->getUsuverificacionId());
                    $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);
                    $UsuarioLogMySqlDAO2->insert($UsuarioLog);
                }
                //Guardar apellidos extraídos de la data de Jumio
                if ($Response2->capabilities->extraction[0]->data->lastName != "") {
                    $Apellidos = explode(" ", $Response2->capabilities->extraction[0]->data->lastName);
                    $firstLastName = str_replace(["ñ", "Ñ"], ["n", "N"], $Apellidos[0]);
                    $secondLastName = str_replace(["ñ", "Ñ"], ["n", "N"], $Apellidos[1]);

                    $UsuarioLog = new UsuarioLog2();
                    $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                    $UsuarioLog->setUsuarioIp('');
                    $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
                    $UsuarioLog->setUsuariosolicitaIp("");
                    $UsuarioLog->setTipo("USUAPELLIDO1");
                    $UsuarioLog->setEstado("R");
                    $UsuarioLog->setValorAntes($Registro->getApellido1());
                    $UsuarioLog->setValorDespues($firstLastName);
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
                    $UsuarioLog->setValorDespues($secondLastName);
                    $UsuarioLog->setUsucreaId(0);
                    $UsuarioLog->setUsumodifId(0);
                    $UsuarioLog->setSversion($UsuarioVerificacion->getUsuverificacionId());
                    $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);
                    $UsuarioLogMySqlDAO2->insert($UsuarioLog);
                }
                //Guardar fecha nacimiento extraído de la data de Jumio
                if ($Response2->capabilities->extraction[0]->data->dateOfBirth != "") {
                    $UsuarioLog = new UsuarioLog2();
                    $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                    $UsuarioLog->setUsuarioIp('');
                    $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
                    $UsuarioLog->setUsuariosolicitaIp("");
                    $UsuarioLog->setTipo("USUFECHANACIM");
                    $UsuarioLog->setEstado("R");
                    $UsuarioLog->setValorAntes($UsuarioOtraInfo->getFechaNacim());
                    $UsuarioLog->setValorDespues($Response2->capabilities->extraction[0]->data->dateOfBirth);
                    $UsuarioLog->setUsucreaId(0);
                    $UsuarioLog->setUsumodifId(0);
                    $UsuarioLog->setSversion($UsuarioVerificacion->getUsuverificacionId());

                    $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);
                    $UsuarioLogMySqlDAO2->insert($UsuarioLog);
                }

                //Guardar Id unico extraído de la data de Jumio
                if ($Response2->account->id != "") {
                    $UsuarioLog = new UsuarioLog2();
                    $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                    $UsuarioLog->setUsuarioIp('');
                    $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
                    $UsuarioLog->setUsuariosolicitaIp("");
                    $UsuarioLog->setTipo("ACCOUNTIDJUMIO");
                    $UsuarioLog->setEstado("R");
                    $UsuarioLog->setValorAntes($Usuario->getAccountIdJumio());
                    $UsuarioLog->setValorDespues($Response2->account->id);
                    $UsuarioLog->setUsucreaId(0);
                    $UsuarioLog->setUsumodifId(0);
                    $UsuarioLog->setSversion($UsuarioVerificacion->getUsuverificacionId());

                    $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);
                    $UsuarioLogMySqlDAO2->insert($UsuarioLog);
                }

                $Transaction->commit();

                try {
                    try {
                        //ENVIO SMS ESTADO RECHAZADO - TEMPLATE
                        $ClasificadorFiltro = new Clasificador("", "ISACTIVESMS");
                        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $ClasificadorFiltro->getClasificadorId(), $Usuario->paisId, $Usuario->estado);
                        $Mandante = new Mandante($Usuario->mandante);

                        if ($MandanteDetalle->valor == 1) {
                            $IsActiveSms = "A";
                        } else {
                            $IsActiveSms = "I";
                        }

                        if ($IsActiveSms == "A") {
                            $abreviado = "RECSMSJUMIO";
                            $msg = 'Respuesta de Verificación Rechazada por Intentos';
                            $tipo = "SMS";

                            $envioPopups = $this->envioPopups($abreviado, $msg, $UsuarioMandante, $Usuario, $tipo, $Registro, $Mandante);
                        }
                    } catch (Exception $e) {
                    }

                    try {
                        //ENVIO EMAIL ESTADO RECHAZADO - TEMPLATE
                        $ClasificadorFiltro = new Clasificador("", "ISACTIVEEMAIL");
                        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $ClasificadorFiltro->getClasificadorId(), $Usuario->paisId, $Usuario->estado);
                        $Mandante = new Mandante($Usuario->mandante);

                        if ($MandanteDetalle->valor == 1) {
                            $IsActiveEmail = "A";
                        } else {
                            $IsActiveEmail = "I";
                        }

                        if ($IsActiveEmail == "A") {
                            $abreviado = "RECEMAILJUMIO";
                            $msg = 'Respuesta de Verificación Rechazada por Intentos';
                            $tipo = "MENSAJE";

                            $envioPopups = $this->envioPopups($abreviado, $msg, $UsuarioMandante, $Usuario, $tipo, $Registro, $Mandante);
                        }
                    } catch (Exception $e) {
                    }

                    try {
                        //ENVIO INBOX ESTADO RECHAZADO - TEMPLATE
                        $ClasificadorFiltro = new Clasificador("", "ISACTIVEINBOX");
                        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $ClasificadorFiltro->getClasificadorId(), $Usuario->paisId, $Usuario->estado);
                        $Mandante = new Mandante($Usuario->mandante);

                        if ($MandanteDetalle->valor == 1) {
                            $IsActiveInbox = "A";
                        } else {
                            $IsActiveInbox = "I";
                        }

                        if ($IsActiveInbox == "A") {
                            $abreviado = "RECINBOXJUMIO";
                            $msg = 'Respuesta de Verificación Rechazada por Intentos';
                            $tipo = "MENSAJE";

                            $envioPopups = $this->envioPopups($abreviado, $msg, $UsuarioMandante, $Usuario, $tipo, $Registro, $Mandante);
                        }
                    } catch (Exception $e) {
                    }
                } catch (Exception $e) {
                }

                $response = json_encode($Response2);
                return $response;
            }
        } catch (Exception $e) {
        }
    }

    /**
     * Gestiona el proceso de verificación de un usuario mediante la API de Jumio.
     *
     * La función `processPV` en PHP gestiona el proceso de verificación de un usuario mediante la API de Jumio,
     * actualizando la información del usuario y enviando notificaciones según el estado de la verificación.
     *
     * @param string $account             La función `processPV` gestiona el procesamiento de los datos de verificación
     *                                    del usuario según diversas condiciones. Interactúa con el usuario y los
     *                                    objetos de verificación, actualiza sus estados, registra información y activa
     *                                    notificaciones según el resultado de la verificación.
     * @param string $status              El parámetro `status` indica el estado del proceso de verificación.
     *                                    Puede tener valores como "TOKEN_EXPIRED", "SESSION_EXPIRED" o "NOT_EXECUTED"
     *                                    según el resultado del proceso de verificación.
     * @param string $token               La función `processPV` gesitona el procesamiento de los datos de verificación
     *                                    del usuario según diversas condiciones y respuestas de una API de Jumio.
     *                                    Realiza diferentes acciones según el estado de la verificación, como aprobar,
     *                                    rechazar o marcar la verificación como pendiente.
     * @param string $accountId           La función `processPV` gestiona el procesamiento de los datos de verificación
     *                                    del usuario según diversas condiciones y respuestas de un servicio de
     *                                    verificación de Jumio. Realiza diferentes acciones según el estado de la
     *                                    verificación, como actualizar la información del usuario, registrar los
     *                                    detalles de la verificación gestionar los casos de rechazo y enviar
     *                                    notificaciones.
     * @param string $workflowExecutionId La función `processPV` gestiona el procesamiento de los datos de verificación
     *                                    del usuario según diversas condiciones. Interactúa con los objetos de
     *                                    verificación del usuario, los registros y actualiza la información del
     *                                    usuario según los resultados de la verificación.
     *
     * @return array una variable de respuesta codificada en JSON.
     */
    public function processPV($account, $status, $token, $accountId, $workflowExecutionId)
    {
        $Usuario = new Usuario($account);
        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
        $ConfigurationEnviroment = new ConfigurationEnvironment();

        $Subproveedor = new Subproveedor("", "JUMIO");
        $SubproveedorMandantePais = new SubproveedorMandantePais('', $Subproveedor->subproveedorId, $UsuarioMandante->mandante, $UsuarioMandante->paisId);
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

        $this->clientAPI = $Credentials->CLIENT_API;
        $this->password = $Credentials->PASSWORD;
        $this->clientId = $Credentials->CLIENT_ID;
        $this->clientSecret = $Credentials->CLIENT_SECRET;
        $this->urltoken = $Credentials->URL_TOKEN;
        $this->url = $Credentials->URL;
        $this->urlRetrieval = $Credentials->URL_RETRIEVAL;

        $PuntoVenta = new PuntoVenta("", $Usuario->usuarioId);
        $Registro = new Registro("", $Usuario->usuarioId);

        $Clasificador = new Clasificador("", "VERIFICAJUMIO");
        $this->token = $token;
        $string = $accountId . "/workflow-executions/" . $workflowExecutionId . "/status";

        $Response = $this->connectionGET($string);
        $Response = json_decode($Response);

        $UrlDetail = $Response->workflowExecution->href; // creo que esta linea esta redirigiendo a que puedan verificar mas de una vez
        $Response2 = $this->connectionGETDetail($UrlDetail);

        // $Response2 = file_get_contents('php://input');
        $Response2 = json_decode($Response2);
        $VerificaFiltroPV = "A";

        try {
            $ClasificadorFiltro = new Clasificador("", "VERIFICANUMDOCPV");
            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $ClasificadorFiltro->getClasificadorId(), $Usuario->paisId, $Usuario->estado);


            if ($MandanteDetalle->valor == 1) {
                $VerificaFiltroPV = "A";
            } else {
                $VerificaFiltroPV = "I";
            }
        } catch (Exception $e) {
            //print_r($e);
        }

        try {
            $ClasificadorFiltro = new Clasificador("", "NUMRECHAZOS");
            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $ClasificadorFiltro->getClasificadorId(), $Usuario->paisId, "A");
            $NumRechazos = $MandanteDetalle->valor;
        } catch (Exception $e) {
        }

        if ($_ENV['debug']) {
            print_r('RICO DECISION PV');
            print_r($Response2->decision->type);
            print_r('VERIFICADO PV');
            print_r($Usuario->verificado);
        }

        if ((($Response2->decision->type == 'PASSED' || $Response2->decision->type == 'WARNING') && ($Usuario->verificado != "S"))) {
            try {
                $Verifica = false;
                $Rechaza = false;
                $documentIncorrect = false;
                if ($Usuario->paisId == 2 && $Usuario->mandante == 0) {
                    $DocumentJumio = $Response2->capabilities->extraction[0]->data->documentNumber;
                    $Verifica = false;
                } elseif ($Usuario->paisId == 33 && $Usuario->mandante == 14) {
                    $DocumentJumio = $Response2->capabilities->extraction[0]->data->cpf;
                    $Verifica = false;
                    if ($DocumentJumio == "") {
                        $Rechaza = true;
                    }
                } elseif ($Usuario->paisId == 33 && $Usuario->mandante == 0) {
                    $DocumentJumio = $Response2->capabilities->extraction[0]->data->cpf;
                    $Verifica = false;
                    if ($DocumentJumio == "") {
                        $Rechaza = true;
                    }
                } elseif ($Usuario->paisId == 46 && $Usuario->mandante == 0 || $Usuario->paisId == 46 && $Usuario->mandante == 18) {
                    if ($Response2->capabilities->extraction[0]->data->optionalMrzField2 != "") {
                        $DocumentJumio = $Response2->capabilities->extraction[0]->data->optionalMrzField2;
                        $Verifica = false;
                    } else {
                        $Rechaza = true;
                    }
                } else {
                    if ($VerificaFiltroPV == "A") {
                        if (($Response2->capabilities->extraction[0]->data->documentNumber == $PuntoVenta->getCedula()) || ($Response2->capabilities->extraction[0]->data->optionalMrzField1 != '' && $Response2->capabilities->extraction[0]->data->optionalMrzField1 == $PuntoVenta->getCedula())) {
                            if ($Response2->capabilities->extraction[0]->data->documentNumber == $PuntoVenta->getCedula()) {
                                $DocumentJumio = $Response2->capabilities->extraction[0]->data->documentNumber;
                            }
                            if ($Response2->capabilities->extraction[0]->data->optionalMrzField1 == $PuntoVenta->getCedula()) {
                                $DocumentJumio = $Response2->capabilities->extraction[0]->data->optionalMrzField1;
                            }
                            $Verifica = false;
                        } else {
                            if (mb_substr($Response2->capabilities->extraction[0]->data->documentNumber, 0, 1) == 'N') {
                                $Response2->capabilities->extraction[0]->data->documentNumber = substr($Response2->capabilities->extraction[0]->data->documentNumber, 1);
                            }
                            $DocumentJumio = str_split($Response2->capabilities->extraction[0]->data->documentNumber);
                            $DocumentSistema = str_split($PuntoVenta->getCedula());


                            $Conteo = 0;
                            foreach ($DocumentJumio as $Key => $value) {
                                if ($value != $DocumentSistema[$Key]) {
                                    $Conteo = $Conteo + 1;
                                }
                            }

                            if ($Conteo > 1 && $Response2->capabilities->extraction[0]->data->optionalMrzField1 != '') {
                                if (mb_substr($Response2->capabilities->extraction[0]->data->optionalMrzField1, 0, 1) == 'N') {
                                    $Response2->capabilities->extraction[0]->data->optionalMrzField1 = substr($Response2->capabilities->extraction[0]->data->optionalMrzField1, 1);
                                }

                                $DocumentJumio = str_split($Response2->capabilities->extraction[0]->data->optionalMrzField1);
                                $DocumentSistema = str_split($PuntoVenta->getCedula());

                                $Conteo = 0;
                                foreach ($DocumentJumio as $Key => $value) {
                                    if ($value != $DocumentSistema[$Key]) {
                                        $Conteo = $Conteo + 1;
                                    }
                                }

                                if ($Conteo > 1) {
                                    //Mas de 1 digito del documento no coincide
                                    $Rechaza = true;
                                    $documentIncorrect = true;
                                    $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                                    $Transaction = $UsuarioMySqlDAO->getTransaction();

                                    $UsuarioMensaje = new UsuarioMensaje();
                                    $UsuarioMensaje->usufromId = 0;
                                    $UsuarioMensaje->usutoId = $Usuario->getUsuarioId();
                                    $UsuarioMensaje->isRead = 0;
                                    $UsuarioMensaje->body = 'La verificación de cuenta ha sido rechazada, intentelo nuevamente por favor en caso de requerir más informacón comuniquese con nuestro equipo de soporte';
                                    $UsuarioMensaje->msubject = 'Respuesta de Verificación';
                                    $UsuarioMensaje->parentId = 0;
                                    $UsuarioMensaje->proveedorId = 0;
                                    $UsuarioMensaje->tipo = "MENSAJE";
                                    $UsuarioMensaje->paisId = $Usuario->paisId;
                                    $UsuarioMensaje->fechaExpiracion = '';


                                    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($Transaction);
                                    $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                                    $Transaction->commit();
                                }
                            } else {
                                if ($Conteo > 1) {
                                    //Mas de 1 digito del documento no coincide
                                    $Rechaza = true;
                                    $documentIncorrect = true;

                                    $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                                    $Transaction = $UsuarioMySqlDAO->getTransaction();

                                    $UsuarioMensaje = new UsuarioMensaje();
                                    $UsuarioMensaje->usufromId = 0;
                                    $UsuarioMensaje->usutoId = $Usuario->getUsuarioId();
                                    $UsuarioMensaje->isRead = 0;
                                    $UsuarioMensaje->body = 'La verificación de cuenta ha sido rechazada, intentelo nuevamente por favor en caso de requerir más informacón comuniquese con nuestro chat online';
                                    $UsuarioMensaje->msubject = 'Respuesta de Verificación';
                                    $UsuarioMensaje->parentId = 0;
                                    $UsuarioMensaje->proveedorId = 0;
                                    $UsuarioMensaje->tipo = "MENSAJE";
                                    $UsuarioMensaje->paisId = $Usuario->paisId;
                                    $UsuarioMensaje->fechaExpiracion = '';


                                    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($Transaction);
                                    $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                                    $Transaction->commit();
                                }
                            }
                        }
                    }
                }

                //RECHAZADO CON STATUS PROCESSED Y DECISION REJECTED
                if ($status == "PROCESSED" && $Response2->decision->type == "REJECTED") {
                    $decisionType = true;
                    $Rechaza = true;
                }

                //ID DE DOCUMENTO en USABILIDAD  DIFERENTE DE PASSED
                foreach ($Response2->capabilities->usability[0]->credentials as $Key => $value) {
                    if ($value->category == "ID") {
                        if ($Response2->capabilities->usability[0]->decision->type != "PASSED") {
                            $Rechaza = true;
                        } elseif ($Response2->capabilities->usability[0]->decision->type == "REJECTED") {
                            $usability = true;
                        }
                    }
                }

                //FACEMAP DIFERENTE DE PASSED
                foreach ($Response2->capabilities->liveness[0]->credentials as $Key => $value) {
                    if ($value->category == "FACEMAP") {
                        if ($Response2->capabilities->liveness[0]->decision->type != "PASSED") {
                            $Rechaza = true;
                        } elseif ($Response2->capabilities->liveness[0]->decision->type == "REJECTED") {
                            $liveness = true;
                        }
                    }
                }

                //SELFIE DIFERENTE DE PASSED
                foreach ($Response2->capabilities->similarity[0]->credentials as $Key => $value) {
                    if ($value->category == "SELFIE") {
                        if ($Response2->capabilities->similarity[0]->decision->type != "PASSED") {
                            $Verifica = true;
                            $Observacion = "similarity = " . $Response2->capabilities->similarity[0]->decision->type;
                        }
                    }
                }

                //ID DE DOCUMENTO en DATOS CHEQUEADOS  DIFERENTE DE PASSED
                foreach ($Response2->capabilities->dataChecks[0]->credentials as $Key => $value) {
                    if ($value->category == "ID") {
                        if ($Response2->capabilities->dataChecks[0]->decision->type != "PASSED") {
                            $Verifica = true;
                        } elseif ($Response2->capabilities->dataChecks[0]->decision->type == "REJECTED") {
                            $dataChecks = true;
                        }
                    }
                }

                //ID DE DOCUMENTO en CHEQUEO DE IMAGENES  DIFERENTE DE PASSED
                foreach ($Response2->capabilities->imageChecks[0]->credentials as $Key => $value) {
                    if ($value->category == "ID") {
                        if ($Response2->capabilities->imageChecks[0]->decision->type != "PASSED") {
                            $Verifica = true;
                        } elseif ($Response2->capabilities->imageChecks[0]->decision->type == "REJECTED") {
                            $imageChecks = true;
                        } elseif ($Response2->capabilities->imageChecks[0]->decision->type == "WARNING" && $Response2->capabilities->imageChecks[0]->details->label == "REPEATED_FACE") {
                            $repeatedFace = true;
                        }
                    }
                }

                //ID DE DOCUMENTO en Extración  DIFERENTE DE PASSED
                foreach ($Response2->capabilities->extraction[0]->credentials as $Key => $value) {
                    if ($value->category == "ID") {
                        if ($Response2->capabilities->extraction[0]->decision->type != "PASSED") {
                            $Verifica = true;
                        } elseif ($Response2->capabilities->extraction[0]->decision->type == "REJECTED") {
                            $extraction = true;
                        }
                    }
                }

                //ID DE DOCUMENTO en similitud DIFERENTE DE PASSED
                foreach ($Response2->capabilities->similarity[0]->credentials as $Key => $value) {
                    if ($value->category == "ID") {
                        if ($Response2->capabilities->similarity[0]->decision->type != "PASSED") {
                            $Verifica = true;
                        } elseif ($Response2->capabilities->similarity[0]->decision->type == "REJECTED") {
                            $similarity = true;
                        }
                    }
                }

                //SELFIE en usabilidad DIFERENTE DE PASSED
                foreach ($Response2->capabilities->usability[0]->credentials as $Key => $value) {
                    if ($value->category == "SELFIE") {
                        if ($Response2->capabilities->usability[0]->decision->type != "PASSED") {
                            $Verifica = true;
                        }
                    }
                }

                $DocumentJumio = $Response2->capabilities->extraction[0]->data->documentNumber;

                if ($Verifica == false && $Rechaza == false) {
                    if ($_ENV['debug']) {
                        print_r('ENTRO APROBADO PV');
                        print_r($Verifica);
                        print_r($Rechaza);
                    }

                    $UsuarioVerificacion = new UsuarioVerificacion("", $Usuario->usuarioId, "I", "USUVERIFICACION");

                    $UsuarioVerificacion->setEstado('A');
                    $UsuarioVerificacion->setObservacion('Aprobado por Jumio');

                    $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                    $Transaction = $UsuarioMySqlDAO->getTransaction();

                    $UsuarioVerificacionMySqlDAO = new UsuarioVerificacionMySqlDAO($Transaction);
                    $UsuarioVerificacionMySqlDAO->update($UsuarioVerificacion);
                    $Transaction->commit();

                    $PuntoVenta = new PuntoVenta();

                    $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO();
                    $Transaction = $PuntoVentaMySqlDAO->getTransaction();
                    $PuntoVentaMySqlDAO->insert($PuntoVenta);
                    $Transaction->commit();

                    $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                    $Transaction = $UsuarioMySqlDAO->getTransaction();

                    $VerificacionLog = new VerificacionLog();
                    $VerificacionLog->setUsuverificacionId($UsuarioVerificacion->getUsuverificacionId());
                    $VerificacionLog->setTipo('FINALDECISION');
                    $VerificacionLog->setJson(json_encode($Response2));


                    $VerificacionLogMySqlDAO = new VerificacionLogMySqlDAO($Transaction);
                    $VerificacionLogMySqlDAO->insert($VerificacionLog);
                    $Transaction->commit();

                    $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                    $Transaction = $UsuarioMySqlDAO->getTransaction();

                    $Usuario->setAccountIdJumio($accountId);
                    $Usuario->setVerificado("S");
                    $Usuario->setVerifcedulaAnt("S");
                    $Usuario->setVerifcedulaPost("S");

                    $Usuario->setFechaVerificado(date("Y-m-d H:i:s"));


                    $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);
                    $UsuarioMySqlDAO->update($Usuario);
                    $Transaction->commit();

                    $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                    $Transaction = $UsuarioMySqlDAO->getTransaction();

                    $idLogA = 0;
                    $idLogP = 0;
                    foreach ($Response2->credentials as $key1 => $value1) {
                        foreach ($value1->parts as $key => $value) {
                            if ($value->classifier == "FRONT") {
                                $tipo = 'USUDNIANTERIOR';

                                $Imagen = $this->connectionGETDetail($value->href);

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

                                if ( ! file_exists('/home/home2/backend/images/c/')) {
                                    mkdir('/home/home2/backend/images/c/', 0755, true);
                                }

                                $dirsave = '/home/home2/backend/images/c/' . $filename;
                                file_put_contents($dirsave, $data);

                                shell_exec('export BOTO_CONFIG=/home/backend/.config/gcloud/legacy_credentials/admin@bpoconsultores.com.co/.boto && export PATH=/sbin:/bin:/usr/sbin:/usr/bin:/home/backend/google-cloud-sdk/bin && gsutil -h "Cache-Control:public,max-age=604800" cp ' . $dirsave . ' gs://cedulas-1/c/');
                            }

                            if ($value->classifier == "BACK") {
                                $tipo = 'USUDNIPOSTERIOR';
                                //$file_contents1  = file_get_contents($value->href);

                                $Imagen = $this->connectionGETDetail($value->href);

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

                                if ( ! file_exists('/home/home2/backend/images/c/')) {
                                    mkdir('/home/home2/backend/images/c/', 0755, true);
                                }

                                $dirsave = '/home/home2/backend/images/c/' . $filename;
                                file_put_contents($dirsave, $data);

                                shell_exec('export BOTO_CONFIG=/home/backend/.config/gcloud/legacy_credentials/admin@bpoconsultores.com.co/.boto && export PATH=/sbin:/bin:/usr/sbin:/usr/bin:/home/backend/google-cloud-sdk/bin && gsutil -h "Cache-Control:public,max-age=604800" cp ' . $dirsave . ' gs://cedulas-1/c/');
                            }
                        }
                    }


                    if ($Response2->capabilities->extraction[0]->data->firstName != "") {
                        $Nombre = explode(" ", $Response2->capabilities->extraction[0]->data->firstName);
                        $firstName = str_replace(["ñ", "Ñ"], ["n", "N"], $Nombre[0]);
                        $secondName = str_replace(["ñ", "Ñ"], ["n", "N"], $Nombre[1]);

                        $NombreAnterior = explode(" ", $PuntoVenta->nombreContacto);
                        $UsuarioLog = new UsuarioLog2();
                        $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                        $UsuarioLog->setUsuarioIp('');
                        $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
                        $UsuarioLog->setUsuariosolicitaIp("");
                        $UsuarioLog->setTipo("USUNOMBRE1");
                        $UsuarioLog->setEstado("A");
                        $UsuarioLog->setValorAntes($NombreAnterior[0]);
                        $UsuarioLog->setValorDespues($firstName);
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
                        $UsuarioLog->setValorAntes($NombreAnterior[1]);
                        $UsuarioLog->setValorDespues($secondName);
                        $UsuarioLog->setUsucreaId(0);
                        $UsuarioLog->setUsumodifId(0);
                        $UsuarioLog->setSversion($UsuarioVerificacion->getUsuverificacionId());
                        $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);
                        $UsuarioLogMySqlDAO2->insert($UsuarioLog);
                    }

                    if ($Response2->capabilities->extraction[0]->data->lastName != "") {
                        $Apellidos = explode(" ", $Response2->capabilities->extraction[0]->data->lastName);
                        $firstLastName = str_replace(["ñ", "Ñ"], ["n", "N"], $Apellidos[0]);
                        $secondLastName = str_replace(["ñ", "Ñ"], ["n", "N"], $Apellidos[1]);

                        $ApellidosAnterior = explode(" ", $PuntoVenta->nombreContacto);
                        $UsuarioLog = new UsuarioLog2();
                        $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                        $UsuarioLog->setUsuarioIp('');
                        $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
                        $UsuarioLog->setUsuariosolicitaIp("");
                        $UsuarioLog->setTipo("USUAPELLIDO1");
                        $UsuarioLog->setEstado("A");
                        $UsuarioLog->setValorAntes($ApellidosAnterior[2]);
                        $UsuarioLog->setValorDespues($firstLastName);
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
                        $UsuarioLog->setValorAntes($ApellidosAnterior[2]);
                        $UsuarioLog->setValorDespues($secondLastName);
                        $UsuarioLog->setUsucreaId(0);
                        $UsuarioLog->setUsumodifId(0);
                        $UsuarioLog->setSversion($UsuarioVerificacion->getUsuverificacionId());
                        $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);
                        $UsuarioLogMySqlDAO2->insert($UsuarioLog);
                    }


                    if ($DocumentJumio != "") {
                        if (is_array($DocumentJumio)) {
                            $DocumentJumio = implode($DocumentJumio);
                        }
                        $UsuarioLog = new UsuarioLog2();
                        $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                        $UsuarioLog->setUsuarioIp('');
                        $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
                        $UsuarioLog->setUsuariosolicitaIp("");
                        $UsuarioLog->setTipo("USUCEDULA");
                        $UsuarioLog->setEstado("A");
                        $UsuarioLog->setValorAntes($PuntoVenta->cedula);
                        $UsuarioLog->setValorDespues($DocumentJumio);
                        $UsuarioLog->setUsucreaId(0);
                        $UsuarioLog->setUsumodifId(0);
                        $UsuarioLog->setSversion($UsuarioVerificacion->getUsuverificacionId());
                        $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);
                        $UsuarioLogMySqlDAO2->insert($UsuarioLog);
                    }
                    $Transaction->commit();


                    $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO();
                    $Transaction = $PuntoVentaMySqlDAO->getTransaction();

                    if (is_array($DocumentJumio)) {
                        $DocumentJumio = implode($DocumentJumio);
                    }
                    $PuntoVenta->setCedula($DocumentJumio);

                    $Nombre = explode(" ", $Response2->capabilities->extraction[0]->data->firstName);
                    $Apellidos = explode(" ", $Response2->capabilities->extraction[0]->data->lastName);
                    $PuntoVenta->setNombreContacto($Nombre[0] . " " . $Nombre[1] . " " . $Apellidos[0] . " " . $Apellidos[1]);


                    $PuntoVentaMySqlDAO = new $PuntoVentaMySqlDAO($Transaction);
                    $PuntoVentaMySqlDAO->update($PuntoVenta);
                    $Transaction->commit();


                    $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                    $Transaction = $UsuarioMySqlDAO->getTransaction();

                    try {
                        //ENVIO POPUP ESTADO EXITOSA - TEMPLATE
                        $ClasificadorFiltro = new Clasificador("", "ISACTIVEPOPUP");
                        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $ClasificadorFiltro->getClasificadorId(), $Usuario->paisId, "A");
                        $Mandante = new Mandante($Usuario->mandante);

                        if ($MandanteDetalle->valor == 1) {
                            $IsActivePopUp = "A";
                        } else {
                            $IsActivePopUp = "I";
                        }

                        if ($IsActivePopUp == "A") {
                            $popup = '';

                            $clasificador = new Clasificador("", "VERIFICACIONEXITOSA");

                            $template = new Template("", $UsuarioMandante->mandante, $clasificador->getClasificadorId(), $Usuario->paisId, strtolower($Usuario->idioma));

                            $popup .= $template->templateHtml;
                            $popup = str_replace("#userid#", $Usuario->usuarioId, $popup);

                            $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                            $Transaction = $UsuarioMySqlDAO->getTransaction();
                            $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
                            $UsuarioMensaje = new UsuarioMensaje();
                            $UsuarioMensaje->usufromId = 0;
                            $UsuarioMensaje->usutoId = $UsuarioMandante->getUsumandanteId();
                            $UsuarioMensaje->isRead = 0;
                            $UsuarioMensaje->body = $popup;
                            $UsuarioMensaje->msubject = 'Respuesta de Verificación Exitosa';
                            $UsuarioMensaje->parentId = 0;
                            $UsuarioMensaje->proveedorId = $UsuarioMandante->mandante;
                            $UsuarioMensaje->tipo = "MESSAGEINV";
                            $UsuarioMensaje->paisId = $UsuarioMandante->paisId;
                            $UsuarioMensaje->fechaExpiracion = date("Y-m-d H:i:s", strtotime("+" . '1' . " days"));

                            $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($Transaction);
                            $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                            $Transaction->commit();
                        }
                    } catch (Exception $e) {
                    }

                    $response = json_encode($Response2);
                    try {
                        try {
                            //ENVIO SMS APROBADA- TEMPLATE
                            $ClasificadorFiltro = new Clasificador("", "ISACTIVESMS");
                            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $ClasificadorFiltro->getClasificadorId(), $Usuario->paisId, $Usuario->estado);
                            $Mandante = new Mandante($Usuario->mandante);

                            if ($MandanteDetalle->valor == 1) {
                                $IsActiveSms = "A";
                            } else {
                                $IsActiveSms = "I";
                            }

                            if ($IsActiveSms == "A") {
                                $mensaje_txt = '';

                                $clasificador = new Clasificador("", "APROSMSJUMIO");

                                $template = new Template("", $UsuarioMandante->mandante, $clasificador->getClasificadorId(), $Usuario->paisId, strtolower($Usuario->idioma));

                                $mensaje_txt .= $template->templateHtml;

                                $mensaje_txt = str_replace("#userid#", $Usuario->usuarioId, $mensaje_txt);
                                $mensaje_txt = str_replace("#name#", $Usuario->nombre, $mensaje_txt);
                                $mensaje_txt = str_replace("#identification#", $Registro->cedula, $mensaje_txt);
                                $mensaje_txt = str_replace("#lastname#", $Registro->apellido1 . ' ' . $Registro->apellido2, $mensaje_txt);
                                $mensaje_txt = str_replace("#login#", $Usuario->login, $mensaje_txt);
                                $mensaje_txt = str_replace("#mandante#", $Usuario->mandante, $mensaje_txt);
                                $mensaje_txt = str_replace("#creationdate#", $Usuario->fechaCrea, $mensaje_txt);
                                $mensaje_txt = str_replace("#email#", $Registro->email, $mensaje_txt);
                                $mensaje_txt = str_replace("#pais#", $Usuario->paisId, $mensaje_txt);
                                $mensaje_txt = str_replace("#link#", $Mandante->baseUrl, $mensaje_txt);
                                $mensaje_txt = str_replace("#telefono#", $Registro->celular, $mensaje_txt);

                                $ConfigurationEnviroment = new ConfigurationEnvironment();

                                $msubjetc = "Solicitud de verificacion aprobada";
                                $mtitle = "Verificacion de cuenta";

                                $envio = $ConfigurationEnviroment->EnviarCorreoVersion3($Usuario->login, "", "", $msubjetc, '', $mtitle, $mensaje_txt, "", "", '', $Usuario->mandante);
                            }
                        } catch (Exception $e) {
                        }

                        try {
                            //ENVIO POPUP APROBADA - TEMPLATE
                            $ClasificadorFiltro = new Clasificador("", "ISACTIVEPOPUP");
                            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $ClasificadorFiltro->getClasificadorId(), $Usuario->paisId, $Usuario->estado);
                            $Mandante = new Mandante($Usuario->mandante);

                            if ($MandanteDetalle->valor == 1) {
                                $IsActivePopUp = "A";
                            } else {
                                $IsActivePopUp = "I";
                            }

                            if ($IsActivePopUp == "A") {
                                $popup = '';

                                $clasificador = new Clasificador("", "APROPOPUPJUMIO");

                                $template = new Template("", $UsuarioMandante->mandante, $clasificador->getClasificadorId(), $Usuario->paisId, strtolower($Usuario->idioma));

                                $popup .= $template->templateHtml;

                                $popup = str_replace("#userid#", $Usuario->usuarioId, $popup);
                                $popup = str_replace("#name#", $Usuario->nombre, $popup);
                                $popup = str_replace("#identification#", $Registro->cedula, $popup);
                                $popup = str_replace("#lastname#", $Registro->apellido1 . ' ' . $Registro->apellido2, $popup);
                                $popup = str_replace("#login#", $Usuario->login, $popup);
                                $popup = str_replace("#mandante#", $Usuario->mandante, $popup);
                                $popup = str_replace("#creationdate#", $Usuario->fechaCrea, $popup);
                                $popup = str_replace("#email#", $Registro->email, $popup);
                                $popup = str_replace("#pais#", $Usuario->paisId, $popup);
                                $popup = str_replace("#link#", $Mandante->baseUrl, $popup);
                                $popup = str_replace("#telefono#", $Registro->celular, $popup);

                                $ConfigurationEnviroment = new ConfigurationEnvironment();

                                $msubjetc = "Solicitud de verificacion aprobada";
                                $mtitle = "Verificacion de cuenta";

                                $envio = $ConfigurationEnviroment->EnviarCorreoVersion3($Usuario->login, "", "", $msubjetc, '', $mtitle, $popup, "", "", '', $Usuario->mandante);
                            }
                        } catch (Exception $e) {
                        }

                        try {
                            //ENVIO EMAIL APROBADA - TEMPLATE
                            $ClasificadorFiltro = new Clasificador("", "ISACTIVEEMAIL");
                            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $ClasificadorFiltro->getClasificadorId(), $Usuario->paisId, $Usuario->estado);
                            $Mandante = new Mandante($Usuario->mandante);

                            if ($MandanteDetalle->valor == 1) {
                                $IsActiveEmail = "A";
                            } else {
                                $IsActiveEmail = "I";
                            }

                            if ($IsActiveEmail == "A") {
                                $email = '';

                                $clasificador = new Clasificador("", "APROEMAILJUMIO");

                                $template = new Template("", $UsuarioMandante->mandante, $clasificador->getClasificadorId(), $Usuario->paisId, strtolower($Usuario->idioma));

                                $email .= $template->templateHtml;

                                $email = str_replace("#userid#", $Usuario->usuarioId, $email);
                                $email = str_replace("#name#", $Usuario->nombre, $email);
                                $email = str_replace("#identification#", $Registro->cedula, $email);
                                $email = str_replace("#lastname#", $Registro->apellido1 . ' ' . $Registro->apellido2, $email);
                                $email = str_replace("#login#", $Usuario->login, $email);
                                $email = str_replace("#mandante#", $Usuario->mandante, $email);
                                $email = str_replace("#creationdate#", $Usuario->fechaCrea, $email);
                                $email = str_replace("#email#", $Registro->email, $email);
                                $email = str_replace("#pais#", $Usuario->paisId, $email);
                                $email = str_replace("#link#", $Mandante->baseUrl, $email);
                                $email = str_replace("#telefono#", $Registro->celular, $email);

                                $ConfigurationEnviroment = new ConfigurationEnvironment();

                                $msubjetc = "Solicitud de verificacion aprobada";
                                $mtitle = "Verificacion de cuenta";

                                $envio = $ConfigurationEnviroment->EnviarCorreoVersion3($Usuario->login, "", "", $msubjetc, '', $mtitle, $email, "", "", '', $Usuario->mandante);
                            }
                        } catch (Exception $e) {
                        }

                        try {
                            //ENVIO INBOX APROBADA - TEMPLATE
                            $ClasificadorFiltro = new Clasificador("", "ISACTIVEINBOX");
                            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $ClasificadorFiltro->getClasificadorId(), $Usuario->paisId, $Usuario->estado);
                            $Mandante = new Mandante($Usuario->mandante);

                            if ($MandanteDetalle->valor == 1) {
                                $IsActiveInbox = "A";
                            } else {
                                $IsActiveInbox = "I";
                            }

                            if ($IsActiveInbox == "A") {
                                $inbox = '';

                                $clasificador = new Clasificador("", "APROINBOXJUMIO");

                                $template = new Template("", $UsuarioMandante->mandante, $clasificador->getClasificadorId(), $Usuario->paisId, strtolower($Usuario->idioma));

                                $inbox .= $template->templateHtml;

                                $inbox = str_replace("#userid#", $Usuario->usuarioId, $inbox);
                                $inbox = str_replace("#name#", $Usuario->nombre, $inbox);
                                $inbox = str_replace("#identification#", $Registro->cedula, $inbox);
                                $inbox = str_replace("#lastname#", $Registro->apellido1 . ' ' . $Registro->apellido2, $inbox);
                                $inbox = str_replace("#login#", $Usuario->login, $inbox);
                                $inbox = str_replace("#mandante#", $Usuario->mandante, $inbox);
                                $inbox = str_replace("#creationdate#", $Usuario->fechaCrea, $inbox);
                                $inbox = str_replace("#email#", $Registro->email, $inbox);
                                $inbox = str_replace("#pais#", $Usuario->paisId, $inbox);
                                $inbox = str_replace("#link#", $Mandante->baseUrl, $inbox);
                                $inbox = str_replace("#telefono#", $Registro->celular, $inbox);

                                $ConfigurationEnviroment = new ConfigurationEnvironment();

                                $msubjetc = "Solicitud de verificacion aprobada";
                                $mtitle = "Verificacion de cuenta";

                                $envio = $ConfigurationEnviroment->EnviarCorreoVersion3($Usuario->login, "", "", $msubjetc, '', $mtitle, $inbox, "", "", '', $Usuario->mandante);
                            }
                        } catch (Exception $e) {
                        }
                    } catch (Exception $e) {
                    }

                    return $response;
                } elseif ($Verifica == true && $Rechaza == false && $documentIncorrect == false) {
                    if ($_ENV['debug']) {
                        print_r('ENTRO PENDIENTE PV');
                        print_r($Verifica);
                        print_r($Rechaza);
                        print_r($documentIncorrect);
                    }

                    try {
                        $UsuarioVerificacion = new UsuarioVerificacion('', $Usuario->usuarioId, 'I', 'USUVERIFICACION');
                    } catch (Exception $e) {
                        $UsuarioVerificacion = new UsuarioVerificacion('', $Usuario->usuarioId, 'P', 'USUVERIFICACION');
                    }
                    $UsuarioVerificacion->setEstado('P');
                    $UsuarioVerificacion->setObservacion('Pendiente Verificación Manual');

                    if ($repeatedFace == true) {
                        $UsuarioVerificacionMySqlDAO = new UsuarioVerificacionMySqlDAO();
                        $UsuarioVerificacionMySqlDAO->update($UsuarioVerificacion);
                        $UsuarioVerificacionMySqlDAO->getTransaction()->commit();

                        $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                        $Transaction = $UsuarioMySqlDAO->getTransaction();

                        $Usuario->setAccountIdJumio($accountId);

                        $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);
                        $UsuarioMySqlDAO->update($Usuario);
                        $Transaction->commit();
                    }

                    $VerificacionLog = new VerificacionLog();
                    $VerificacionLog->setUsuverificacionId($UsuarioVerificacion->getUsuverificacionId());
                    $VerificacionLog->setTipo('FINALDECISION');
                    $VerificacionLog->setJson(json_encode($Response2));


                    $VerificacionLogMySqlDAO = new VerificacionLogMySqlDAO($Transaction);
                    $VerificacionLogMySqlDAO->insert($VerificacionLog);


                    foreach ($Response2->credentials as $key1 => $value1) {
                        foreach ($value1->parts as $key => $value) {
                            if ($value->classifier == "FRONT") {
                                $tipo = 'USUDNIANTERIOR';

                                $Imagen = $this->connectionGETDetail($value->href);

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
                            }

                            if ($value->classifier == "BACK") {
                                $tipo = 'USUDNIPOSTERIOR';
                                //$file_contents1  = file_get_contents($value->href);

                                $Imagen = $this->connectionGETDetail($value->href);

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
                            }
                        }
                    }


                    if ($Response2->capabilities->extraction[0]->data->firstName != "") {
                        $Nombre = explode(" ", $Response2->capabilities->extraction[0]->data->firstName);
                        $firstName = str_replace(["ñ", "Ñ"], ["n", "N"], $Nombre[0]);
                        $secondName = str_replace(["ñ", "Ñ"], ["n", "N"], $Nombre[1]);

                        $NombreAnterior = explode(" ", $PuntoVenta->nombreContacto);
                        $UsuarioLog = new UsuarioLog2();
                        $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                        $UsuarioLog->setUsuarioIp('');
                        $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
                        $UsuarioLog->setUsuariosolicitaIp("");
                        $UsuarioLog->setTipo("USUNOMBRE1");
                        $UsuarioLog->setEstado("P");
                        $UsuarioLog->setValorAntes($NombreAnterior[0]);
                        $UsuarioLog->setValorDespues($firstName);
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
                        $UsuarioLog->setValorAntes($NombreAnterior[1]);
                        $UsuarioLog->setValorDespues($secondName);
                        $UsuarioLog->setUsucreaId(0);
                        $UsuarioLog->setUsumodifId(0);
                        $UsuarioLog->setSversion($UsuarioVerificacion->getUsuverificacionId());
                        $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);
                        $UsuarioLogMySqlDAO2->insert($UsuarioLog);
                    }

                    if ($Response2->capabilities->extraction[0]->data->lastName != "") {
                        $Apellidos = explode(" ", $Response2->capabilities->extraction[0]->data->lastName);
                        $firstLastName = str_replace(["ñ", "Ñ"], ["n", "N"], $Apellidos[0]);
                        $secondLastName = str_replace(["ñ", "Ñ"], ["n", "N"], $Apellidos[1]);

                        $ApellidosAnterior = explode(" ", $PuntoVenta->nombreContacto);
                        $UsuarioLog = new UsuarioLog2();
                        $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                        $UsuarioLog->setUsuarioIp('');
                        $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
                        $UsuarioLog->setUsuariosolicitaIp("");
                        $UsuarioLog->setTipo("USUAPELLIDO1");
                        $UsuarioLog->setEstado("P");
                        $UsuarioLog->setValorAntes($ApellidosAnterior[2]);
                        $UsuarioLog->setValorDespues($firstLastName);
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
                        $UsuarioLog->setValorAntes($ApellidosAnterior[2]);
                        $UsuarioLog->setValorDespues($secondLastName);
                        $UsuarioLog->setUsucreaId(0);
                        $UsuarioLog->setUsumodifId(0);
                        $UsuarioLog->setSversion($UsuarioVerificacion->getUsuverificacionId());
                        $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);
                        $UsuarioLogMySqlDAO2->insert($UsuarioLog);
                    }


                    if ($DocumentJumio != "") {
                        if (is_array($DocumentJumio)) {
                            $DocumentJumio = implode($DocumentJumio);
                        }
                        $UsuarioLog = new UsuarioLog2();
                        $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                        $UsuarioLog->setUsuarioIp('');
                        $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
                        $UsuarioLog->setUsuariosolicitaIp("");
                        $UsuarioLog->setTipo("USUCEDULA");
                        $UsuarioLog->setEstado("P");
                        $UsuarioLog->setValorAntes($PuntoVenta->getCedula());
                        $UsuarioLog->setValorDespues($DocumentJumio);
                        $UsuarioLog->setUsucreaId(0);
                        $UsuarioLog->setUsumodifId(0);
                        $UsuarioLog->setSversion($UsuarioVerificacion->getUsuverificacionId());
                        $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);
                        $UsuarioLogMySqlDAO2->insert($UsuarioLog);
                    }


                    $Transaction->commit();

                    $response = json_encode($Response2);

                    try {
                        //ENVIO POPUP ESTADO PENDIENTE - TEMPLATE
                        $ClasificadorFiltro = new Clasificador("", "ISACTIVEPOPUP");
                        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $ClasificadorFiltro->getClasificadorId(), $Usuario->paisId, "A");
                        $Mandante = new Mandante($Usuario->mandante);

                        if ($MandanteDetalle->valor == 1) {
                            $IsActivePopUp = "A";
                        } else {
                            $IsActivePopUp = "I";
                        }

                        if ($IsActivePopUp == "A") {
                            $popup = '';

                            $clasificador = new Clasificador("", "PENDPOPUPJUMIO");

                            $template = new Template("", $UsuarioMandante->mandante, $clasificador->getClasificadorId(), $Usuario->paisId, strtolower($Usuario->idioma));

                            $popup .= $template->templateHtml;
                            $popup = str_replace("#userid#", $Usuario->usuarioId, $popup);

                            $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                            $Transaction = $UsuarioMySqlDAO->getTransaction();
                            $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
                            $UsuarioMensaje = new UsuarioMensaje();
                            $UsuarioMensaje->usufromId = 0;
                            $UsuarioMensaje->usutoId = $UsuarioMandante->getUsumandanteId();
                            $UsuarioMensaje->isRead = 0;
                            $UsuarioMensaje->body = $popup;
                            $UsuarioMensaje->msubject = 'Respuesta de Verificación Pendiente';
                            $UsuarioMensaje->parentId = 0;
                            $UsuarioMensaje->proveedorId = $UsuarioMandante->mandante;
                            $UsuarioMensaje->tipo = "MESSAGEINV";
                            $UsuarioMensaje->paisId = $UsuarioMandante->paisId;
                            $UsuarioMensaje->fechaExpiracion = date("Y-m-d H:i:s", strtotime("+" . '1' . " days"));

                            $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($Transaction);
                            $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                            $Transaction->commit();
                        }
                    } catch (Exception $e) {
                    }


                    $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                    $Transaction = $UsuarioMySqlDAO->getTransaction();

                    $UsuarioMensaje = new UsuarioMensaje();
                    $UsuarioMensaje->usufromId = 0;
                    $UsuarioMensaje->usutoId = $Usuario->getUsuarioId();
                    $UsuarioMensaje->isRead = 0;
                    $UsuarioMensaje->body = '<div style="margin:50px;">La verificación de cuenta se encuentra en estado pendiente de revisión, la cuenta será verificada en las proximas 24 a 48 horas</div>';
                    $UsuarioMensaje->msubject = 'Respuesta de Verificación';
                    $UsuarioMensaje->parentId = 0;
                    $UsuarioMensaje->proveedorId = $Usuario->mandante;
                    $UsuarioMensaje->tipo = "MENSAJE";
                    $UsuarioMensaje->paisId = $Usuario->paisId;
                    $UsuarioMensaje->fechaExpiracion = date("Y-m-d H:i:s", strtotime("+" . '1' . " days"));


                    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($Transaction);
                    $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                    $Transaction->commit();

                    $mensaje_txt = '';

                    try {
                        try {
                            //ENVIO SMS ESTADO PENDIENTE - TEMPLATE
                            $ClasificadorFiltro = new Clasificador("", "ISACTIVESMS");
                            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $ClasificadorFiltro->getClasificadorId(), $Usuario->paisId, $Usuario->estado);
                            $Mandante = new Mandante($Usuario->mandante);

                            if ($MandanteDetalle->valor == 1) {
                                $IsActiveSms = "A";
                            } else {
                                $IsActiveSms = "I";
                            }

                            if ($IsActiveSms == "A") {
                                $mensaje_txt = '';

                                $clasificador = new Clasificador("", "PENDSMSJUMIO");

                                $template = new Template("", $UsuarioMandante->mandante, $clasificador->getClasificadorId(), $Usuario->paisId, strtolower($Usuario->idioma));

                                $mensaje_txt .= $template->templateHtml;

                                $mensaje_txt = str_replace("#userid#", $Usuario->usuarioId, $mensaje_txt);
                                $mensaje_txt = str_replace("#name#", $Usuario->nombre, $mensaje_txt);
                                $mensaje_txt = str_replace("#identification#", $Registro->cedula, $mensaje_txt);
                                $mensaje_txt = str_replace("#lastname#", $Registro->apellido1 . ' ' . $Registro->apellido2, $mensaje_txt);
                                $mensaje_txt = str_replace("#login#", $Usuario->login, $mensaje_txt);
                                $mensaje_txt = str_replace("#mandante#", $Usuario->mandante, $mensaje_txt);
                                $mensaje_txt = str_replace("#creationdate#", $Usuario->fechaCrea, $mensaje_txt);
                                $mensaje_txt = str_replace("#email#", $Registro->email, $mensaje_txt);
                                $mensaje_txt = str_replace("#pais#", $Usuario->paisId, $mensaje_txt);
                                $mensaje_txt = str_replace("#link#", $Mandante->baseUrl, $mensaje_txt);
                                $mensaje_txt = str_replace("#telefono#", $Registro->celular, $mensaje_txt);

                                $ConfigurationEnviroment = new ConfigurationEnvironment();

                                $msubjetc = "Solicitud de verificacion Pendiente de aprobación";
                                $mtitle = "Verificacion de cuenta";

                                $envio = $ConfigurationEnviroment->EnviarCorreoVersion3($Usuario->login, "", "", $msubjetc, '', $mtitle, $mensaje_txt, "", "", '', $Usuario->mandante);
                            }
                        } catch (Exception $e) {
                        }

                        try {
                            //ENVIO POPUP PENDIENTE - TEMPLATE
                            $ClasificadorFiltro = new Clasificador("", "ISACTIVEPOPUP");
                            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $ClasificadorFiltro->getClasificadorId(), $Usuario->paisId, $Usuario->estado);
                            $Mandante = new Mandante($Usuario->mandante);

                            if ($MandanteDetalle->valor == 1) {
                                $IsActivePopUp = "A";
                            } else {
                                $IsActivePopUp = "I";
                            }

                            if ($IsActivePopUp == "A") {
                                $popup = '';

                                $clasificador = new Clasificador("", "PENDPOPUPJUMIO");

                                $template = new Template("", $UsuarioMandante->mandante, $clasificador->getClasificadorId(), $Usuario->paisId, strtolower($Usuario->idioma));

                                $popup .= $template->templateHtml;

                                $popup = str_replace("#userid#", $Usuario->usuarioId, $popup);
                                $popup = str_replace("#name#", $Usuario->nombre, $popup);
                                $popup = str_replace("#identification#", $Registro->cedula, $popup);
                                $popup = str_replace("#lastname#", $Registro->apellido1 . ' ' . $Registro->apellido2, $popup);
                                $popup = str_replace("#login#", $Usuario->login, $popup);
                                $popup = str_replace("#mandante#", $Usuario->mandante, $popup);
                                $popup = str_replace("#creationdate#", $Usuario->fechaCrea, $popup);
                                $popup = str_replace("#email#", $Registro->email, $popup);
                                $popup = str_replace("#pais#", $Usuario->paisId, $popup);
                                $popup = str_replace("#link#", $Mandante->baseUrl, $popup);
                                $popup = str_replace("#telefono#", $Registro->celular, $popup);

                                $ConfigurationEnviroment = new ConfigurationEnvironment();

                                $msubjetc = "Solicitud de verificacion Pendiente de aprobación";
                                $mtitle = "Verificacion de cuenta";

                                $envio = $ConfigurationEnviroment->EnviarCorreoVersion3($Usuario->login, "", "", $msubjetc, '', $mtitle, $popup, "", "", '', $Usuario->mandante);
                            }
                        } catch (Exception $e) {
                        }

                        try {
                            //ENVIO EMAIL PENDIENTE - TEMPLATE
                            $ClasificadorFiltro = new Clasificador("", "ISACTIVEEMAIL");
                            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $ClasificadorFiltro->getClasificadorId(), $Usuario->paisId, $Usuario->estado);
                            $Mandante = new Mandante($Usuario->mandante);

                            if ($MandanteDetalle->valor == 1) {
                                $IsActiveEmail = "A";
                            } else {
                                $IsActiveEmail = "I";
                            }

                            if ($IsActiveEmail == "A") {
                                $email = '';

                                $clasificador = new Clasificador("", "PENDEMAILJUMIO");

                                $template = new Template("", $UsuarioMandante->mandante, $clasificador->getClasificadorId(), $Usuario->paisId, strtolower($Usuario->idioma));

                                $email .= $template->templateHtml;

                                $email = str_replace("#userid#", $Usuario->usuarioId, $email);
                                $email = str_replace("#name#", $Usuario->nombre, $email);
                                $email = str_replace("#identification#", $Registro->cedula, $email);
                                $email = str_replace("#lastname#", $Registro->apellido1 . ' ' . $Registro->apellido2, $email);
                                $email = str_replace("#login#", $Usuario->login, $email);
                                $email = str_replace("#mandante#", $Usuario->mandante, $email);
                                $email = str_replace("#creationdate#", $Usuario->fechaCrea, $email);
                                $email = str_replace("#email#", $Registro->email, $email);
                                $email = str_replace("#pais#", $Usuario->paisId, $email);
                                $email = str_replace("#link#", $Mandante->baseUrl, $email);
                                $email = str_replace("#telefono#", $Registro->celular, $email);

                                $ConfigurationEnviroment = new ConfigurationEnvironment();

                                $msubjetc = "Solicitud de verificacion Pendiente de aprobación";
                                $mtitle = "Verificacion de cuenta";

                                $envio = $ConfigurationEnviroment->EnviarCorreoVersion3($Usuario->login, "", "", $msubjetc, '', $mtitle, $email, "", "", '', $Usuario->mandante);
                            }
                        } catch (Exception $e) {
                        }

                        try {
                            //ENVIO INBOX PENDIENTE - TEMPLATE
                            $ClasificadorFiltro = new Clasificador("", "ISACTIVEINBOX");
                            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $ClasificadorFiltro->getClasificadorId(), $Usuario->paisId, $Usuario->estado);
                            $Mandante = new Mandante($Usuario->mandante);

                            if ($MandanteDetalle->valor == 1) {
                                $IsActiveInbox = "A";
                            } else {
                                $IsActiveInbox = "I";
                            }

                            if ($IsActiveInbox == "A") {
                                $inbox = '';

                                $clasificador = new Clasificador("", "PENDINBOXJUMIO");

                                $template = new Template("", $UsuarioMandante->mandante, $clasificador->getClasificadorId(), $Usuario->paisId, strtolower($Usuario->idioma));

                                $inbox .= $template->templateHtml;

                                $inbox = str_replace("#userid#", $Usuario->usuarioId, $inbox);
                                $inbox = str_replace("#name#", $Usuario->nombre, $inbox);
                                $inbox = str_replace("#identification#", $Registro->cedula, $inbox);
                                $inbox = str_replace("#lastname#", $Registro->apellido1 . ' ' . $Registro->apellido2, $inbox);
                                $inbox = str_replace("#login#", $Usuario->login, $inbox);
                                $inbox = str_replace("#mandante#", $Usuario->mandante, $inbox);
                                $inbox = str_replace("#creationdate#", $Usuario->fechaCrea, $inbox);
                                $inbox = str_replace("#email#", $Registro->email, $inbox);
                                $inbox = str_replace("#pais#", $Usuario->paisId, $inbox);
                                $inbox = str_replace("#link#", $Mandante->baseUrl, $inbox);
                                $inbox = str_replace("#telefono#", $Registro->celular, $inbox);

                                $ConfigurationEnviroment = new ConfigurationEnvironment();

                                $msubjetc = "Solicitud de verificacion Pendiente de aprobación";
                                $mtitle = "Verificacion de cuenta";

                                $envio = $ConfigurationEnviroment->EnviarCorreoVersion3($Usuario->login, "", "", $msubjetc, '', $mtitle, $inbox, "", "", '', $Usuario->mandante);
                            }
                        } catch (Exception $e) {
                        }
                    } catch (Exception $e) {
                    }

                    return $response;
                } elseif (($Verifica == false && $Rechaza == true) || ($Verifica == true && $Rechaza == true) || $documentIncorrect == true) {
                    if ($_ENV['debug']) {
                        print_r('ENTRO RECHAZADO 1 PV');
                        print_r($Verifica);
                        print_r($Rechaza);
                        print_r($documentIncorrect);
                    }

                    try {
                        $UsuarioVerificacion = new UsuarioVerificacion('', $Usuario->usuarioId, 'I', 'USUVERIFICACION');
                    } catch (Exception $e) {
                        $UsuarioVerificacion = new UsuarioVerificacion('', $Usuario->usuarioId, 'P', 'USUVERIFICACION');
                    }
                    if ($status == "TOKEN_EXPIRED" || $status == "SESSION_EXPIRED" || $status == "NOT_EXECUTED") {
                        $UsuarioVerificacion->setEstado('NE');
                        $UsuarioVerificacion->setObservacion("No ejecutado");
                    } else {
                        $UsuarioVerificacion->setEstado('R');
                        $UsuarioVerificacion->setObservacion("Rechazado");

                        if ($NumRechazos > 0) {
                            try {
                                try {
                                    $Clasificador = new Clasificador('', 'RECHAZADOVERIF');
                                    $ClasificadorId = $Clasificador->getClasificadorId();
                                    $UsuarioConfiguracion = new UsuarioConfiguracion($Usuario->usuarioId, 'A', $ClasificadorId);
                                } catch (Exception $e) {
                                }
                                if ($UsuarioConfiguracion != null && $UsuarioConfiguracion->valor < $NumRechazos) {
                                    $UsuarioConfiguracion->valor += 1;

                                    $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                                    $Transaction = $UsuarioMySqlDAO->getTransaction();
                                    $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);
                                    $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);
                                    $Transaction->commit();
                                } else {
                                    if ($UsuarioConfiguracion->valor >= $NumRechazos) {
                                        $Clasificador = new Clasificador('', 'RECHAZADOVERIF');
                                        $ClasificadorId = $Clasificador->getClasificadorId();
                                        $UsuarioConfiguracion = new UsuarioConfiguracion($Usuario->usuarioId, 'A', $ClasificadorId);

                                        try {
                                            //ENVIO POPUP ESTADO RECHAZO POR INTENTOS - TEMPLATE
                                            $ClasificadorFiltro = new Clasificador("", "ISACTIVEPOPUP");
                                            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $ClasificadorFiltro->getClasificadorId(), $Usuario->paisId, "A");
                                            $Mandante = new Mandante($Usuario->mandante);

                                            if ($MandanteDetalle->valor == 1) {
                                                $IsActivePopUp = "A";
                                            } else {
                                                $IsActivePopUp = "I";
                                            }

                                            if ($IsActivePopUp == "A") {
                                                $popup = '';

                                                $clasificador = new Clasificador("", "RECPOPUPJUMIOINTENTOS");

                                                $template = new Template("", $UsuarioMandante->mandante, $clasificador->getClasificadorId(), $Usuario->paisId, strtolower($Usuario->idioma));

                                                $popup .= $template->templateHtml;
                                                $popup = str_replace("#userid#", $Usuario->usuarioId, $popup);

                                                $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                                                $Transaction = $UsuarioMySqlDAO->getTransaction();
                                                $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
                                                $UsuarioMensaje = new UsuarioMensaje();
                                                $UsuarioMensaje->usufromId = 0;
                                                $UsuarioMensaje->usutoId = $UsuarioMandante->getUsumandanteId();
                                                $UsuarioMensaje->isRead = 0;
                                                $UsuarioMensaje->body = $popup;
                                                $UsuarioMensaje->msubject = 'Respuesta de Verificación Rechazada por Intentos';
                                                $UsuarioMensaje->parentId = 0;
                                                $UsuarioMensaje->proveedorId = $UsuarioMandante->mandante;
                                                $UsuarioMensaje->tipo = "MESSAGEINV";
                                                $UsuarioMensaje->paisId = $UsuarioMandante->paisId;
                                                $UsuarioMensaje->fechaExpiracion = date("Y-m-d H:i:s", strtotime("+" . '1' . " days"));

                                                $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($Transaction);
                                                $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                                                $Transaction->commit();
                                            }
                                        } catch (Exception $e) {
                                        }
                                    }
                                }
                            } catch (Exception $e) {
                                $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                                $Transaction = $UsuarioMySqlDAO->getTransaction();
                                $UsuarioConfiguracion = new UsuarioConfiguracion();
                                $UsuarioConfiguracion->setUsuarioId($Usuario->usuarioId);
                                $UsuarioConfiguracion->setEstado('A');
                                $UsuarioConfiguracion->setTipo(410);
                                $UsuarioConfiguracion->setValor(1);
                                $UsuarioConfiguracion->setUsucreaId(0);
                                $UsuarioConfiguracion->setUsumodifId(0);
                                $UsuarioConfiguracion->setNota("");
                                $UsuarioConfiguracion->setProductoId(0);

                                $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);
                                $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);
                                $Transaction->commit();
                            }
                        }

                        if ($extraction == true) {
                            $UsuarioVerificacion->setObservacion("Rechazado por extracción de datos");

                            try {
                                //ENVIO POPUP ESTADO RECHAZADO POR EXTRACCION DE DATOS - TEMPLATE
                                $ClasificadorFiltro = new Clasificador("", "ISACTIVEPOPUP");
                                $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $ClasificadorFiltro->getClasificadorId(), $Usuario->paisId, "A");
                                $Mandante = new Mandante($Usuario->mandante);

                                if ($MandanteDetalle->valor == 1) {
                                    $IsActivePopUp = "A";
                                } else {
                                    $IsActivePopUp = "I";
                                }

                                if ($IsActivePopUp == "A") {
                                    $popup = '';

                                    $clasificador = new Clasificador("", "RECPOPUPJUMIOEXTRACCION");

                                    $template = new Template("", $UsuarioMandante->mandante, $clasificador->getClasificadorId(), $Usuario->paisId, strtolower($Usuario->idioma));

                                    $popup .= $template->templateHtml;
                                    $popup = str_replace("#userid#", $Usuario->usuarioId, $popup);

                                    $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                                    $Transaction = $UsuarioMySqlDAO->getTransaction();
                                    $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
                                    $UsuarioMensaje = new UsuarioMensaje();
                                    $UsuarioMensaje->usufromId = 0;
                                    $UsuarioMensaje->usutoId = $UsuarioMandante->getUsumandanteId();
                                    $UsuarioMensaje->isRead = 0;
                                    $UsuarioMensaje->body = $popup;
                                    $UsuarioMensaje->msubject = 'Respuesta de Verificación Rechazada por Extracción de datos';
                                    $UsuarioMensaje->parentId = 0;
                                    $UsuarioMensaje->proveedorId = $UsuarioMandante->mandante;
                                    $UsuarioMensaje->tipo = "MESSAGEINV";
                                    $UsuarioMensaje->paisId = $UsuarioMandante->paisId;
                                    $UsuarioMensaje->fechaExpiracion = date("Y-m-d H:i:s", strtotime("+" . '1' . " days"));

                                    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($Transaction);
                                    $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                                    $Transaction->commit();
                                }
                            } catch (Exception $e) {
                            }
                        } elseif ($dataChecks == true) {
                            $UsuarioVerificacion->setObservacion("Rechazado por datos");

                            try {
                                //ENVIO POPUP ESTADO RECHAZADO POR DATOS - TEMPLATE
                                $ClasificadorFiltro = new Clasificador("", "ISACTIVEPOPUP");
                                $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $ClasificadorFiltro->getClasificadorId(), $Usuario->paisId, "A");
                                $Mandante = new Mandante($Usuario->mandante);

                                if ($MandanteDetalle->valor == 1) {
                                    $IsActivePopUp = "A";
                                } else {
                                    $IsActivePopUp = "I";
                                }

                                if ($IsActivePopUp == "A") {
                                    $popup = '';

                                    $clasificador = new Clasificador("", "RECPOPUPJUMIODATOS");

                                    $template = new Template("", $UsuarioMandante->mandante, $clasificador->getClasificadorId(), $Usuario->paisId, strtolower($Usuario->idioma));

                                    $popup .= $template->templateHtml;
                                    $popup = str_replace("#userid#", $Usuario->usuarioId, $popup);

                                    $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                                    $Transaction = $UsuarioMySqlDAO->getTransaction();
                                    $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
                                    $UsuarioMensaje = new UsuarioMensaje();
                                    $UsuarioMensaje->usufromId = 0;
                                    $UsuarioMensaje->usutoId = $UsuarioMandante->getUsumandanteId();
                                    $UsuarioMensaje->isRead = 0;
                                    $UsuarioMensaje->body = $popup;
                                    $UsuarioMensaje->msubject = 'Respuesta de Verificación Rechazada por Datos';
                                    $UsuarioMensaje->parentId = 0;
                                    $UsuarioMensaje->proveedorId = $UsuarioMandante->mandante;
                                    $UsuarioMensaje->tipo = "MESSAGEINV";
                                    $UsuarioMensaje->paisId = $UsuarioMandante->paisId;
                                    $UsuarioMensaje->fechaExpiracion = date("Y-m-d H:i:s", strtotime("+" . '1' . " days"));

                                    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($Transaction);
                                    $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                                    $Transaction->commit();
                                }
                            } catch (Exception $e) {
                            }
                        } elseif ($imageChecks == true) {
                            $UsuarioVerificacion->setObservacion("Rechazado por imagen");

                            try {
                                //ENVIO POPUP ESTADO RECHAZADO POR IMAGEN - TEMPLATE
                                $ClasificadorFiltro = new Clasificador("", "ISACTIVEPOPUP");
                                $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $ClasificadorFiltro->getClasificadorId(), $Usuario->paisId, "A");
                                $Mandante = new Mandante($Usuario->mandante);

                                if ($MandanteDetalle->valor == 1) {
                                    $IsActivePopUp = "A";
                                } else {
                                    $IsActivePopUp = "I";
                                }

                                if ($IsActivePopUp == "A") {
                                    $popup = '';

                                    $clasificador = new Clasificador("", "RECPOPUPJUMIOIMAGEN");

                                    $template = new Template("", $UsuarioMandante->mandante, $clasificador->getClasificadorId(), $Usuario->paisId, strtolower($Usuario->idioma));

                                    $popup .= $template->templateHtml;
                                    $popup = str_replace("#userid#", $Usuario->usuarioId, $popup);

                                    $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                                    $Transaction = $UsuarioMySqlDAO->getTransaction();
                                    $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
                                    $UsuarioMensaje = new UsuarioMensaje();
                                    $UsuarioMensaje->usufromId = 0;
                                    $UsuarioMensaje->usutoId = $UsuarioMandante->getUsumandanteId();
                                    $UsuarioMensaje->isRead = 0;
                                    $UsuarioMensaje->body = $popup;
                                    $UsuarioMensaje->msubject = 'Respuesta de Verificación Rechazada por Imagen';
                                    $UsuarioMensaje->parentId = 0;
                                    $UsuarioMensaje->proveedorId = $UsuarioMandante->mandante;
                                    $UsuarioMensaje->tipo = "MESSAGEINV";
                                    $UsuarioMensaje->paisId = $UsuarioMandante->paisId;
                                    $UsuarioMensaje->fechaExpiracion = date("Y-m-d H:i:s", strtotime("+" . '1' . " days"));

                                    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($Transaction);
                                    $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                                    $Transaction->commit();
                                }
                            } catch (Exception $e) {
                            }
                        } elseif ($usability == true) {
                            $UsuarioVerificacion->setObservacion("Rechazado por usabilidad");

                            try {
                                //ENVIO POPUP ESTADO RECHAZADO POR USABILIDAD - TEMPLATE
                                $ClasificadorFiltro = new Clasificador("", "ISACTIVEPOPUP");
                                $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $ClasificadorFiltro->getClasificadorId(), $Usuario->paisId, "A");
                                $Mandante = new Mandante($Usuario->mandante);

                                if ($MandanteDetalle->valor == 1) {
                                    $IsActivePopUp = "A";
                                } else {
                                    $IsActivePopUp = "I";
                                }

                                if ($IsActivePopUp == "A") {
                                    $popup = '';

                                    $clasificador = new Clasificador("", "RECPOPUPJUMIOUSABILIDAD");

                                    $template = new Template("", $UsuarioMandante->mandante, $clasificador->getClasificadorId(), $Usuario->paisId, strtolower($Usuario->idioma));

                                    $popup .= $template->templateHtml;
                                    $popup = str_replace("#userid#", $Usuario->usuarioId, $popup);

                                    $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                                    $Transaction = $UsuarioMySqlDAO->getTransaction();
                                    $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
                                    $UsuarioMensaje = new UsuarioMensaje();
                                    $UsuarioMensaje->usufromId = 0;
                                    $UsuarioMensaje->usutoId = $UsuarioMandante->getUsumandanteId();
                                    $UsuarioMensaje->isRead = 0;
                                    $UsuarioMensaje->body = $popup;
                                    $UsuarioMensaje->msubject = 'Respuesta de Verificación Rechazada por Usabilidad';
                                    $UsuarioMensaje->parentId = 0;
                                    $UsuarioMensaje->proveedorId = $UsuarioMandante->mandante;
                                    $UsuarioMensaje->tipo = "MESSAGEINV";
                                    $UsuarioMensaje->paisId = $UsuarioMandante->paisId;
                                    $UsuarioMensaje->fechaExpiracion = date("Y-m-d H:i:s", strtotime("+" . '1' . " days"));

                                    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($Transaction);
                                    $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                                    $Transaction->commit();
                                }
                            } catch (Exception $e) {
                            }
                        } elseif ($similarity == true) {
                            $UsuarioVerificacion->setObservacion("Rechazado por semejanza");
                        } elseif ($documentIncorrect == true) {
                            $UsuarioVerificacion->setObservacion("Rechazado por documento incorrecto");

                            try {
                                //ENVIO POPUP ESTADO RECHAZADO POR DOCUMENTO INCORRECTO - TEMPLATE
                                $ClasificadorFiltro = new Clasificador("", "ISACTIVEPOPUP");
                                $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $ClasificadorFiltro->getClasificadorId(), $Usuario->paisId, "A");
                                $Mandante = new Mandante($Usuario->mandante);

                                if ($MandanteDetalle->valor == 1) {
                                    $IsActivePopUp = "A";
                                } else {
                                    $IsActivePopUp = "I";
                                }

                                if ($IsActivePopUp == "A") {
                                    $popup = '';

                                    $clasificador = new Clasificador("", "RECPOPUPJUMIOCOINCIDENCIA");

                                    $template = new Template("", $UsuarioMandante->mandante, $clasificador->getClasificadorId(), $Usuario->paisId, strtolower($Usuario->idioma));

                                    $popup .= $template->templateHtml;
                                    $popup = str_replace("#userid#", $Usuario->usuarioId, $popup);

                                    $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                                    $Transaction = $UsuarioMySqlDAO->getTransaction();
                                    $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
                                    $UsuarioMensaje = new UsuarioMensaje();
                                    $UsuarioMensaje->usufromId = 0;
                                    $UsuarioMensaje->usutoId = $UsuarioMandante->getUsumandanteId();
                                    $UsuarioMensaje->isRead = 0;
                                    $UsuarioMensaje->body = $popup;
                                    $UsuarioMensaje->msubject = 'Respuesta de Verificación Rechazada por Coincidencia';
                                    $UsuarioMensaje->parentId = 0;
                                    $UsuarioMensaje->proveedorId = $UsuarioMandante->mandante;
                                    $UsuarioMensaje->tipo = "MESSAGEINV";
                                    $UsuarioMensaje->paisId = $UsuarioMandante->paisId;
                                    $UsuarioMensaje->fechaExpiracion = date("Y-m-d H:i:s", strtotime("+" . '1' . " days"));

                                    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($Transaction);
                                    $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                                    $Transaction->commit();
                                }
                            } catch (Exception $e) {
                            }

                            try {
                                $Clasificador = new Clasificador('', 'RECHAZADOVERIFDOC');
                                $ClasificadorId = $Clasificador->getClasificadorId();
                                $UsuarioConfiguracion = new UsuarioConfiguracion($Usuario->usuarioId, 'A', $ClasificadorId);

                                if ($UsuarioConfiguracion->valor < 3) {
                                    $UsuarioConfiguracion->valor += 1;

                                    $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                                    $Transaction = $UsuarioMySqlDAO->getTransaction();
                                    $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);
                                    $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);
                                    $Transaction->commit();
                                } else {
                                    if ($UsuarioConfiguracion->valor >= 3) {
                                        try {
                                            //ENVIO POPUP ESTADO RECHAZO POR INTENTOS - TEMPLATE
                                            $ClasificadorFiltro = new Clasificador("", "ISACTIVEPOPUP");
                                            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $ClasificadorFiltro->getClasificadorId(), $Usuario->paisId, "A");
                                            $Mandante = new Mandante($Usuario->mandante);

                                            if ($MandanteDetalle->valor == 1) {
                                                $IsActivePopUp = "A";
                                            } else {
                                                $IsActivePopUp = "I";
                                            }

                                            if ($IsActivePopUp == "A") {
                                                $popup = '';

                                                $clasificador = new Clasificador("", "RECPOPUPJUMIOINTENTOS");

                                                $template = new Template("", $UsuarioMandante->mandante, $clasificador->getClasificadorId(), $Usuario->paisId, strtolower($Usuario->idioma));

                                                $popup .= $template->templateHtml;
                                                $popup = str_replace("#userid#", $Usuario->usuarioId, $popup);

                                                $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                                                $Transaction = $UsuarioMySqlDAO->getTransaction();
                                                $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
                                                $UsuarioMensaje = new UsuarioMensaje();
                                                $UsuarioMensaje->usufromId = 0;
                                                $UsuarioMensaje->usutoId = $UsuarioMandante->getUsumandanteId();
                                                $UsuarioMensaje->isRead = 0;
                                                $UsuarioMensaje->body = $popup;
                                                $UsuarioMensaje->msubject = 'Respuesta de Verificación Rechazada por Intentos';
                                                $UsuarioMensaje->parentId = 0;
                                                $UsuarioMensaje->proveedorId = $UsuarioMandante->mandante;
                                                $UsuarioMensaje->tipo = "MESSAGEINV";
                                                $UsuarioMensaje->paisId = $UsuarioMandante->paisId;
                                                $UsuarioMensaje->fechaExpiracion = date("Y-m-d H:i:s", strtotime("+" . '1' . " days"));

                                                $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($Transaction);
                                                $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                                                $Transaction->commit();
                                            }
                                        } catch (Exception $e) {
                                        }

                                        $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                                        $Transaction = $UsuarioMySqlDAO->getTransaction();

                                        $Usuario->setEstado("I");

                                        $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);
                                        $UsuarioMySqlDAO->update($Usuario);
                                        $Transaction->commit();
                                    }
                                }
                            } catch (Exception $e) {
                                $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                                $Transaction = $UsuarioMySqlDAO->getTransaction();
                                $UsuarioConfiguracion = new UsuarioConfiguracion();
                                $UsuarioConfiguracion->setUsuarioId($Usuario->usuarioId);
                                $UsuarioConfiguracion->setEstado('A');
                                $UsuarioConfiguracion->setTipo(410);
                                $UsuarioConfiguracion->setValor(1);
                                $UsuarioConfiguracion->setUsucreaId(0);
                                $UsuarioConfiguracion->setUsumodifId(0);
                                $UsuarioConfiguracion->setNota("");
                                $UsuarioConfiguracion->setProductoId(0);

                                $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);
                                $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);
                                $Transaction->commit();
                            }
                        } elseif ($liveness == true) {
                            $UsuarioVerificacion->setObservacion("Rechazado por Vivacidad");
                        } elseif ($decisionType == true) {
                            $UsuarioVerificacion->setObservacion("Rechazado por Puntuación de alto riesgo");
                        }
                    }


                    $UsuarioVerificacionMySqlDAO = new UsuarioVerificacionMySqlDAO();
                    $UsuarioVerificacionMySqlDAO->update($UsuarioVerificacion);
                    $UsuarioVerificacionMySqlDAO->getTransaction()->commit();

                    $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                    $Transaction = $UsuarioMySqlDAO->getTransaction();

                    $VerificacionLog = new VerificacionLog();
                    $VerificacionLog->setUsuverificacionId($UsuarioVerificacion->getUsuverificacionId());
                    $VerificacionLog->setTipo('FINALDECISION');
                    $VerificacionLog->setJson(json_encode($Response2));


                    $VerificacionLogMySqlDAO = new VerificacionLogMySqlDAO($Transaction);
                    $VerificacionLogMySqlDAO->insert($VerificacionLog);


                    foreach ($Response2->credentials as $key1 => $value1) {
                        foreach ($value1->parts as $key => $value) {
                            if ($value->classifier == "FRONT") {
                                $tipo = 'USUDNIANTERIOR';

                                $Imagen = $this->connectionGETDetail($value->href);

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
                            }

                            if ($value->classifier == "BACK") {
                                $tipo = 'USUDNIPOSTERIOR';
                                //$file_contents1  = file_get_contents($value->href);

                                $Imagen = $this->connectionGETDetail($value->href);

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
                            }
                        }
                    }


                    if ($Response2->capabilities->extraction[0]->data->firstName != "") {
                        $Nombre = explode(" ", $Response2->capabilities->extraction[0]->data->firstName);
                        $firstName = str_replace(["ñ", "Ñ"], ["n", "N"], $Nombre[0]);
                        $secondName = str_replace(["ñ", "Ñ"], ["n", "N"], $Nombre[1]);

                        $NombreAnterior = explode(" ", $PuntoVenta->nombreContacto);
                        $UsuarioLog = new UsuarioLog2();
                        $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                        $UsuarioLog->setUsuarioIp('');
                        $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
                        $UsuarioLog->setUsuariosolicitaIp("");
                        $UsuarioLog->setTipo("USUNOMBRE1");
                        $UsuarioLog->setEstado("R");
                        $UsuarioLog->setValorAntes($NombreAnterior[0]);
                        $UsuarioLog->setValorDespues($firstName);
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
                        $UsuarioLog->setValorAntes($NombreAnterior[1]);
                        $UsuarioLog->setValorDespues($secondName);
                        $UsuarioLog->setUsucreaId(0);
                        $UsuarioLog->setUsumodifId(0);
                        $UsuarioLog->setSversion($UsuarioVerificacion->getUsuverificacionId());
                        $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);
                        $UsuarioLogMySqlDAO2->insert($UsuarioLog);
                    }

                    if ($Response2->capabilities->extraction[0]->data->lastName != "") {
                        $Apellidos = explode(" ", $Response2->capabilities->extraction[0]->data->lastName);
                        $firstLastName = str_replace(["ñ", "Ñ"], ["n", "N"], $Apellidos[0]);
                        $secondLastName = str_replace(["ñ", "Ñ"], ["n", "N"], $Apellidos[1]);

                        $ApellidosAnterior = explode(" ", $PuntoVenta->nombreContacto);
                        $UsuarioLog = new UsuarioLog2();
                        $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                        $UsuarioLog->setUsuarioIp('');
                        $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
                        $UsuarioLog->setUsuariosolicitaIp("");
                        $UsuarioLog->setTipo("USUAPELLIDO1");
                        $UsuarioLog->setEstado("R");
                        $UsuarioLog->setValorAntes($ApellidosAnterior[2]);
                        $UsuarioLog->setValorDespues($firstLastName);
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
                        $UsuarioLog->setValorAntes($ApellidosAnterior[2]);
                        $UsuarioLog->setValorDespues($secondLastName);
                        $UsuarioLog->setUsucreaId(0);
                        $UsuarioLog->setUsumodifId(0);
                        $UsuarioLog->setSversion($UsuarioVerificacion->getUsuverificacionId());
                        $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);
                        $UsuarioLogMySqlDAO2->insert($UsuarioLog);
                    }


                    if ($DocumentJumio != "") {
                        if (is_array($DocumentJumio)) {
                            $DocumentJumio = implode($DocumentJumio);
                        }
                        $UsuarioLog = new UsuarioLog2();
                        $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                        $UsuarioLog->setUsuarioIp('');
                        $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
                        $UsuarioLog->setUsuariosolicitaIp("");
                        $UsuarioLog->setTipo("USUCEDULA");
                        $UsuarioLog->setEstado("R");
                        $UsuarioLog->setValorAntes($PuntoVenta->getCedula());
                        $UsuarioLog->setValorDespues($DocumentJumio);
                        $UsuarioLog->setUsucreaId(0);
                        $UsuarioLog->setUsumodifId(0);
                        $UsuarioLog->setSversion($UsuarioVerificacion->getUsuverificacionId());
                        $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);
                        $UsuarioLogMySqlDAO2->insert($UsuarioLog);
                    }


                    $Transaction->commit();


                    $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                    $Transaction = $UsuarioMySqlDAO->getTransaction();

                    $UsuarioMensaje = new UsuarioMensaje();
                    $UsuarioMensaje->usufromId = 0;
                    $UsuarioMensaje->usutoId = $Usuario->getUsuarioId();
                    $UsuarioMensaje->isRead = 0;
                    $UsuarioMensaje->body = '<div style="margin:50px;">La verificación de cuenta ha sido rechazada, intentelo nuevamente por favor en caso de requerir más información comuniquese con nuestro chat online</div>';
                    $UsuarioMensaje->msubject = 'Respuesta de Verificación';
                    $UsuarioMensaje->parentId = 0;
                    $UsuarioMensaje->proveedorId = $Usuario->mandante;
                    $UsuarioMensaje->tipo = "MENSAJE";
                    $UsuarioMensaje->paisId = $Usuario->paisId;
                    $UsuarioMensaje->fechaExpiracion = date("Y-m-d H:i:s", strtotime("+" . '1' . " days"));


                    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($Transaction);
                    $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                    $Transaction->commit();

                    try {
                        try {
                            //ENVIO SMS ESTADO RECHAZADO - TEMPLATE
                            $ClasificadorFiltro = new Clasificador("", "ISACTIVESMS");
                            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $ClasificadorFiltro->getClasificadorId(), $Usuario->paisId, $Usuario->estado);
                            $Mandante = new Mandante($Usuario->mandante);

                            if ($MandanteDetalle->valor == 1) {
                                $IsActiveSms = "A";
                            } else {
                                $IsActiveSms = "I";
                            }

                            if ($IsActiveSms == "A") {
                                $mensaje_txt = '';

                                $clasificador = new Clasificador("", "RECSMSJUMIO");

                                $template = new Template("", $UsuarioMandante->mandante, $clasificador->getClasificadorId(), $Usuario->paisId, strtolower($Usuario->idioma));

                                $mensaje_txt .= $template->templateHtml;

                                $mensaje_txt = str_replace("#userid#", $Usuario->usuarioId, $mensaje_txt);
                                $mensaje_txt = str_replace("#name#", $Usuario->nombre, $mensaje_txt);
                                $mensaje_txt = str_replace("#identification#", $Registro->cedula, $mensaje_txt);
                                $mensaje_txt = str_replace("#lastname#", $Registro->apellido1 . ' ' . $Registro->apellido2, $mensaje_txt);
                                $mensaje_txt = str_replace("#login#", $Usuario->login, $mensaje_txt);
                                $mensaje_txt = str_replace("#mandante#", $Usuario->mandante, $mensaje_txt);
                                $mensaje_txt = str_replace("#creationdate#", $Usuario->fechaCrea, $mensaje_txt);
                                $mensaje_txt = str_replace("#email#", $Registro->email, $mensaje_txt);
                                $mensaje_txt = str_replace("#pais#", $Usuario->paisId, $mensaje_txt);
                                $mensaje_txt = str_replace("#link#", $Mandante->baseUrl, $mensaje_txt);
                                $mensaje_txt = str_replace("#telefono#", $Registro->celular, $mensaje_txt);

                                $ConfigurationEnviroment = new ConfigurationEnvironment();

                                $msubjetc = "Solicitud de verificacion rechazada";
                                $mtitle = "Verificacion de cuenta";

                                $envio = $ConfigurationEnviroment->EnviarCorreoVersion3($Usuario->login, "", "", $msubjetc, '', $mtitle, $mensaje_txt, "", "", '', $Usuario->mandante);
                            }
                        } catch (Exception $e) {
                        }

                        try {
                            //ENVIO POPUP ESTADO RECHAZADO - TEMPLATE
                            $ClasificadorFiltro = new Clasificador("", "ISACTIVEPOPUP");
                            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $ClasificadorFiltro->getClasificadorId(), $Usuario->paisId, $Usuario->estado);
                            $Mandante = new Mandante($Usuario->mandante);

                            if ($MandanteDetalle->valor == 1) {
                                $IsActivePopUp = "A";
                            } else {
                                $IsActivePopUp = "I";
                            }

                            if ($IsActivePopUp == "A") {
                                $popup = '';

                                $clasificador = new Clasificador("", "RECPOPUPJUMIO");

                                $template = new Template("", $UsuarioMandante->mandante, $clasificador->getClasificadorId(), $Usuario->paisId, strtolower($Usuario->idioma));

                                $popup .= $template->templateHtml;

                                $popup = str_replace("#userid#", $Usuario->usuarioId, $popup);
                                $popup = str_replace("#name#", $Usuario->nombre, $popup);
                                $popup = str_replace("#identification#", $Registro->cedula, $popup);
                                $popup = str_replace("#lastname#", $Registro->apellido1 . ' ' . $Registro->apellido2, $popup);
                                $popup = str_replace("#login#", $Usuario->login, $popup);
                                $popup = str_replace("#mandante#", $Usuario->mandante, $popup);
                                $popup = str_replace("#creationdate#", $Usuario->fechaCrea, $popup);
                                $popup = str_replace("#email#", $Registro->email, $popup);
                                $popup = str_replace("#pais#", $Usuario->paisId, $popup);
                                $popup = str_replace("#link#", $Mandante->baseUrl, $popup);
                                $popup = str_replace("#telefono#", $Registro->celular, $popup);

                                $ConfigurationEnviroment = new ConfigurationEnvironment();

                                $msubjetc = "Solicitud de verificacion rechazada";
                                $mtitle = "Verificacion de cuenta";

                                $envio = $ConfigurationEnviroment->EnviarCorreoVersion3($Usuario->login, "", "", $msubjetc, '', $mtitle, $popup, "", "", '', $Usuario->mandante);
                            }
                        } catch (Exception $e) {
                        }

                        try {
                            //ENVIO EMAIL ESTADO RECHAZADO - TEMPLATE
                            $ClasificadorFiltro = new Clasificador("", "ISACTIVEEMAIL");
                            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $ClasificadorFiltro->getClasificadorId(), $Usuario->paisId, $Usuario->estado);
                            $Mandante = new Mandante($Usuario->mandante);

                            if ($MandanteDetalle->valor == 1) {
                                $IsActiveEmail = "A";
                            } else {
                                $IsActiveEmail = "I";
                            }

                            if ($IsActiveEmail == "A") {
                                $email = '';

                                $clasificador = new Clasificador("", "RECEMAILJUMIO");

                                $template = new Template("", $UsuarioMandante->mandante, $clasificador->getClasificadorId(), $Usuario->paisId, strtolower($Usuario->idioma));

                                $email .= $template->templateHtml;

                                $email = str_replace("#userid#", $Usuario->usuarioId, $email);
                                $email = str_replace("#name#", $Usuario->nombre, $email);
                                $email = str_replace("#identification#", $Registro->cedula, $email);
                                $email = str_replace("#lastname#", $Registro->apellido1 . ' ' . $Registro->apellido2, $email);
                                $email = str_replace("#login#", $Usuario->login, $email);
                                $email = str_replace("#mandante#", $Usuario->mandante, $email);
                                $email = str_replace("#creationdate#", $Usuario->fechaCrea, $email);
                                $email = str_replace("#email#", $Registro->email, $email);
                                $email = str_replace("#pais#", $Usuario->paisId, $email);
                                $email = str_replace("#link#", $Mandante->baseUrl, $email);
                                $email = str_replace("#telefono#", $Registro->celular, $email);

                                $ConfigurationEnviroment = new ConfigurationEnvironment();

                                $msubjetc = "Solicitud de verificacion rechazada";
                                $mtitle = "Verificacion de cuenta";

                                $envio = $ConfigurationEnviroment->EnviarCorreoVersion3($Usuario->login, "", "", $msubjetc, '', $mtitle, $email, "", "", '', $Usuario->mandante);
                            }
                        } catch (Exception $e) {
                        }

                        try {
                            //ENVIO INBOX ESTADO RECHAZADO - TEMPLATE
                            $ClasificadorFiltro = new Clasificador("", "ISACTIVEINBOX");
                            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $ClasificadorFiltro->getClasificadorId(), $Usuario->paisId, $Usuario->estado);
                            $Mandante = new Mandante($Usuario->mandante);

                            if ($MandanteDetalle->valor == 1) {
                                $IsActiveInbox = "A";
                            } else {
                                $IsActiveInbox = "I";
                            }

                            if ($IsActiveInbox == "A") {
                                $inbox = '';

                                $clasificador = new Clasificador("", "RECINBOXJUMIO");

                                $template = new Template("", $UsuarioMandante->mandante, $clasificador->getClasificadorId(), $Usuario->paisId, strtolower($Usuario->idioma));

                                $inbox .= $template->templateHtml;

                                $inbox = str_replace("#userid#", $Usuario->usuarioId, $inbox);
                                $inbox = str_replace("#name#", $Usuario->nombre, $inbox);
                                $inbox = str_replace("#identification#", $Registro->cedula, $inbox);
                                $inbox = str_replace("#lastname#", $Registro->apellido1 . ' ' . $Registro->apellido2, $inbox);
                                $inbox = str_replace("#login#", $Usuario->login, $inbox);
                                $inbox = str_replace("#mandante#", $Usuario->mandante, $inbox);
                                $inbox = str_replace("#creationdate#", $Usuario->fechaCrea, $inbox);
                                $inbox = str_replace("#email#", $Registro->email, $inbox);
                                $inbox = str_replace("#pais#", $Usuario->paisId, $inbox);
                                $inbox = str_replace("#link#", $Mandante->baseUrl, $inbox);
                                $inbox = str_replace("#telefono#", $Registro->celular, $inbox);

                                $ConfigurationEnviroment = new ConfigurationEnvironment();

                                $msubjetc = "Solicitud de verificacion rechazada";
                                $mtitle = "Verificacion de cuenta";

                                $envio = $ConfigurationEnviroment->EnviarCorreoVersion3($Usuario->login, "", "", $msubjetc, '', $mtitle, $inbox, "", "", '', $Usuario->mandante);
                            }
                        } catch (Exception $e) {
                        }
                    } catch (Exception $e) {
                    }

                    $response = json_encode($Response2);
                    //print_r(" Verificación Rechazada");
                    return $response;
                }
            } catch (Exception $e) {
                //print_r($e);
            }
        } else {
            if ($_ENV['debug']) {
                print_r('ENTRO RECHAZADO 2 PV');
                print_r($status);
                print_r($Response2->decision->type);
            }

            $Temp = "";

            try {
                $UsuarioVerificacion = new UsuarioVerificacion('', $Usuario->usuarioId, 'I', 'USUVERIFICACION');
            } catch (Exception $e) {
                $UsuarioVerificacion = new UsuarioVerificacion('', $Usuario->usuarioId, 'P', 'USUVERIFICACION');
            }
            //RECHAZADO CON STATUS PROCESSED Y DECISION REJECTED
            if ($status == "PROCESSED" && $Response2->decision->type == "REJECTED") {
                $decisionType = true;
            }

            //ID DE DOCUMENTO en USABILIDAD  REJECTED
            foreach ($Response2->capabilities->usability[0]->credentials as $Key => $value) {
                if ($value->category == "ID") {
                    if ($Response2->capabilities->usability[0]->decision->type == "REJECTED") {
                        $usability = true;
                    }
                }
            }

            //ID DE DOCUMENTO en Extración  REJECTED
            foreach ($Response2->capabilities->extraction[0]->credentials as $Key => $value) {
                if ($value->category == "ID") {
                    if ($Response2->capabilities->extraction[0]->decision->type == "REJECTED") {
                        $extraction = true;
                    }
                }
            }

            //ID DE DOCUMENTO en DATOS CHEQUEADOS  REJECTED
            foreach ($Response2->capabilities->dataChecks[0]->credentials as $Key => $value) {
                if ($value->category == "ID") {
                    if ($Response2->capabilities->dataChecks[0]->decision->type == "REJECTED") {
                        $dataChecks = true;
                    }
                }
            }

            //ID DE DOCUMENTO en CHEQUEO DE IMAGENES REJECTED
            foreach ($Response2->capabilities->imageChecks[0]->credentials as $Key => $value) {
                if ($value->category == "ID") {
                    if ($Response2->capabilities->imageChecks[0]->decision->type == "REJECTED") {
                        $imageChecks = true;
                    }
                }
            }

            //SELFIE DIFERENTE DE PASSED
            foreach ($Response2->capabilities->similarity[0]->credentials as $Key => $value) {
                if ($value->category == "ID") {
                    if ($Response2->capabilities->similarity[0]->decision->type == "REJECTED") {
                        $similarity = true;
                    }
                }
            }

            //FACEMAP DIFERENTE DE PASSED
            foreach ($Response2->capabilities->liveness[0]->credentials as $Key => $value) {
                if ($value->category == "FACEMAP") {
                    if ($Response2->capabilities->liveness[0]->decision->type == "REJECTED") {
                        $liveness = true;
                    }
                }
            }

            if ($status == "TOKEN_EXPIRED" || $status == "SESSION_EXPIRED" || $status == "NOT_EXECUTED") {
                $UsuarioVerificacion->setEstado('NE');
                $UsuarioVerificacion->setObservacion("No ejecutado");

                try {
                    //ENVIO POPUP ESTADO RECHAZO POR NO EJECUTADO - TEMPLATE
                    $ClasificadorFiltro = new Clasificador("", "ISACTIVEPOPUP");
                    $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $ClasificadorFiltro->getClasificadorId(), $Usuario->paisId, "A");
                    $Mandante = new Mandante($Usuario->mandante);

                    if ($MandanteDetalle->valor == 1) {
                        $IsActivePopUp = "A";
                    } else {
                        $IsActivePopUp = "I";
                    }

                    if ($IsActivePopUp == "A") {
                        $popup = '';

                        $clasificador = new Clasificador("", "RECPOPUPNOEJECUTADO");

                        $template = new Template("", $UsuarioMandante->mandante, $clasificador->getClasificadorId(), $Usuario->paisId, strtolower($Usuario->idioma));

                        $popup .= $template->templateHtml;
                        $popup = str_replace("#userid#", $Usuario->usuarioId, $popup);

                        $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                        $Transaction = $UsuarioMySqlDAO->getTransaction();
                        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
                        $UsuarioMensaje = new UsuarioMensaje();
                        $UsuarioMensaje->usufromId = 0;
                        $UsuarioMensaje->usutoId = $UsuarioMandante->getUsumandanteId();
                        $UsuarioMensaje->isRead = 0;
                        $UsuarioMensaje->body = $popup;
                        $UsuarioMensaje->msubject = 'Respuesta de Verificación No Ejecutada';
                        $UsuarioMensaje->parentId = 0;
                        $UsuarioMensaje->proveedorId = $UsuarioMandante->mandante;
                        $UsuarioMensaje->tipo = "MESSAGEINV";
                        $UsuarioMensaje->paisId = $UsuarioMandante->paisId;
                        $UsuarioMensaje->fechaExpiracion = date("Y-m-d H:i:s", strtotime("+" . '1' . " days"));

                        $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($Transaction);
                        $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                        $Transaction->commit();
                    }
                } catch (Exception $e) {
                }
            } else {
                $UsuarioVerificacion->setEstado('R');

                if ($NumRechazos > 0) {
                    try {
                        try {
                            $Clasificador = new Clasificador('', 'RECHAZADOVERIF');
                            $ClasificadorId = $Clasificador->getClasificadorId();
                            $UsuarioConfiguracion = new UsuarioConfiguracion($Usuario->usuarioId, 'A', $ClasificadorId);
                        } catch (Exception $e) {
                        }
                        if ($UsuarioConfiguracion != null && $UsuarioConfiguracion->valor < $NumRechazos) {
                            $UsuarioConfiguracion->valor += 1;

                            $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                            $Transaction = $UsuarioMySqlDAO->getTransaction();
                            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);
                            $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);
                            $Transaction->commit();
                        } else {
                            if ($UsuarioConfiguracion->valor >= $NumRechazos) {
                                $Clasificador = new Clasificador('', 'RECHAZADOVERIF');
                                $ClasificadorId = $Clasificador->getClasificadorId();
                                $UsuarioConfiguracion = new UsuarioConfiguracion($Usuario->usuarioId, 'A', $ClasificadorId);

                                try {
                                    //ENVIO POPUP ESTADO RECHAZO POR INTENTOS - TEMPLATE
                                    $ClasificadorFiltro = new Clasificador("", "ISACTIVEPOPUP");
                                    $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $ClasificadorFiltro->getClasificadorId(), $Usuario->paisId, "A");
                                    $Mandante = new Mandante($Usuario->mandante);

                                    if ($MandanteDetalle->valor == 1) {
                                        $IsActivePopUp = "A";
                                    } else {
                                        $IsActivePopUp = "I";
                                    }

                                    if ($IsActivePopUp == "A") {
                                        $popup = '';

                                        $clasificador = new Clasificador("", "RECPOPUPJUMIOINTENTOS");

                                        $template = new Template("", $UsuarioMandante->mandante, $clasificador->getClasificadorId(), $Usuario->paisId, strtolower($Usuario->idioma));

                                        $popup .= $template->templateHtml;
                                        $popup = str_replace("#userid#", $Usuario->usuarioId, $popup);

                                        $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                                        $Transaction = $UsuarioMySqlDAO->getTransaction();
                                        $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
                                        $UsuarioMensaje = new UsuarioMensaje();
                                        $UsuarioMensaje->usufromId = 0;
                                        $UsuarioMensaje->usutoId = $UsuarioMandante->getUsumandanteId();
                                        $UsuarioMensaje->isRead = 0;
                                        $UsuarioMensaje->body = $popup;
                                        $UsuarioMensaje->msubject = 'Respuesta de Verificación Rechazada por Intentos';
                                        $UsuarioMensaje->parentId = 0;
                                        $UsuarioMensaje->proveedorId = $UsuarioMandante->mandante;
                                        $UsuarioMensaje->tipo = "MESSAGEINV";
                                        $UsuarioMensaje->paisId = $UsuarioMandante->paisId;
                                        $UsuarioMensaje->fechaExpiracion = date("Y-m-d H:i:s", strtotime("+" . '1' . " days"));

                                        $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($Transaction);
                                        $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                                        $Transaction->commit();
                                    }
                                } catch (Exception $e) {
                                }
                            }
                        }
                    } catch (Exception $e) {
                        $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                        $Transaction = $UsuarioMySqlDAO->getTransaction();
                        $UsuarioConfiguracion = new UsuarioConfiguracion();
                        $UsuarioConfiguracion->setUsuarioId($Usuario->usuarioId);
                        $UsuarioConfiguracion->setEstado('A');
                        $UsuarioConfiguracion->setTipo(410);
                        $UsuarioConfiguracion->setValor(1);
                        $UsuarioConfiguracion->setUsucreaId(0);
                        $UsuarioConfiguracion->setUsumodifId(0);
                        $UsuarioConfiguracion->setNota("");
                        $UsuarioConfiguracion->setProductoId(0);

                        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);
                        $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);
                        $Transaction->commit();
                    }
                }

                if ($extraction == true) {
                    $UsuarioVerificacion->setObservacion("Rechazado por extracción de datos");

                    try {
                        //ENVIO POPUP ESTADO RECHAZADO POR EXTRACCION DE DATOS - TEMPLATE
                        $ClasificadorFiltro = new Clasificador("", "ISACTIVEPOPUP");
                        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $ClasificadorFiltro->getClasificadorId(), $Usuario->paisId, "A");
                        $Mandante = new Mandante($Usuario->mandante);

                        if ($MandanteDetalle->valor == 1) {
                            $IsActivePopUp = "A";
                        } else {
                            $IsActivePopUp = "I";
                        }

                        if ($IsActivePopUp == "A") {
                            $popup = '';

                            $clasificador = new Clasificador("", "RECPOPUPJUMIOEXTRACCION");

                            $template = new Template("", $UsuarioMandante->mandante, $clasificador->getClasificadorId(), $Usuario->paisId, strtolower($Usuario->idioma));

                            $popup .= $template->templateHtml;
                            $popup = str_replace("#userid#", $Usuario->usuarioId, $popup);

                            $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                            $Transaction = $UsuarioMySqlDAO->getTransaction();
                            $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
                            $UsuarioMensaje = new UsuarioMensaje();
                            $UsuarioMensaje->usufromId = 0;
                            $UsuarioMensaje->usutoId = $UsuarioMandante->getUsumandanteId();
                            $UsuarioMensaje->isRead = 0;
                            $UsuarioMensaje->body = $popup;
                            $UsuarioMensaje->msubject = 'Respuesta de Verificación Rechazada por Extracción de Datos';
                            $UsuarioMensaje->parentId = 0;
                            $UsuarioMensaje->proveedorId = $UsuarioMandante->mandante;
                            $UsuarioMensaje->tipo = "MESSAGEINV";
                            $UsuarioMensaje->paisId = $UsuarioMandante->paisId;
                            $UsuarioMensaje->fechaExpiracion = date("Y-m-d H:i:s", strtotime("+" . '1' . " days"));

                            $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($Transaction);
                            $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                            $Transaction->commit();
                        }
                    } catch (Exception $e) {
                    }
                } elseif ($dataChecks == true) {
                    $UsuarioVerificacion->setObservacion("Rechazado por datos");

                    try {
                        //ENVIO POPUP ESTADO RECHAZADO POR DATOS - TEMPLATE
                        $ClasificadorFiltro = new Clasificador("", "ISACTIVEPOPUP");
                        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $ClasificadorFiltro->getClasificadorId(), $Usuario->paisId, "A");
                        $Mandante = new Mandante($Usuario->mandante);

                        if ($MandanteDetalle->valor == 1) {
                            $IsActivePopUp = "A";
                        } else {
                            $IsActivePopUp = "I";
                        }

                        if ($IsActivePopUp == "A") {
                            $popup = '';

                            $clasificador = new Clasificador("", "RECPOPUPJUMIODATOS");

                            $template = new Template("", $UsuarioMandante->mandante, $clasificador->getClasificadorId(), $Usuario->paisId, strtolower($Usuario->idioma));

                            $popup .= $template->templateHtml;
                            $popup = str_replace("#userid#", $Usuario->usuarioId, $popup);

                            $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                            $Transaction = $UsuarioMySqlDAO->getTransaction();
                            $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
                            $UsuarioMensaje = new UsuarioMensaje();
                            $UsuarioMensaje->usufromId = 0;
                            $UsuarioMensaje->usutoId = $UsuarioMandante->getUsumandanteId();
                            $UsuarioMensaje->isRead = 0;
                            $UsuarioMensaje->body = $popup;
                            $UsuarioMensaje->msubject = 'Respuesta de Verificación Rechazada por Datos';
                            $UsuarioMensaje->parentId = 0;
                            $UsuarioMensaje->proveedorId = $UsuarioMandante->mandante;
                            $UsuarioMensaje->tipo = "MESSAGEINV";
                            $UsuarioMensaje->paisId = $UsuarioMandante->paisId;
                            $UsuarioMensaje->fechaExpiracion = date("Y-m-d H:i:s", strtotime("+" . '1' . " days"));

                            $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($Transaction);
                            $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                            $Transaction->commit();
                        }
                    } catch (Exception $e) {
                    }
                } elseif ($imageChecks == true) {
                    $UsuarioVerificacion->setObservacion("Rechazado por imagen");

                    try {
                        //ENVIO POPUP ESTADO RECHAZADO POR IMAGEN - TEMPLATE
                        $ClasificadorFiltro = new Clasificador("", "ISACTIVEPOPUP");
                        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $ClasificadorFiltro->getClasificadorId(), $Usuario->paisId, "A");
                        $Mandante = new Mandante($Usuario->mandante);

                        if ($MandanteDetalle->valor == 1) {
                            $IsActivePopUp = "A";
                        } else {
                            $IsActivePopUp = "I";
                        }

                        if ($IsActivePopUp == "A") {
                            $popup = '';

                            $clasificador = new Clasificador("", "RECPOPUPJUMIOIMAGEN");

                            $template = new Template("", $UsuarioMandante->mandante, $clasificador->getClasificadorId(), $Usuario->paisId, strtolower($Usuario->idioma));

                            $popup .= $template->templateHtml;
                            $popup = str_replace("#userid#", $Usuario->usuarioId, $popup);

                            $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                            $Transaction = $UsuarioMySqlDAO->getTransaction();
                            $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
                            $UsuarioMensaje = new UsuarioMensaje();
                            $UsuarioMensaje->usufromId = 0;
                            $UsuarioMensaje->usutoId = $UsuarioMandante->getUsumandanteId();
                            $UsuarioMensaje->isRead = 0;
                            $UsuarioMensaje->body = $popup;
                            $UsuarioMensaje->msubject = 'Respuesta de Verificación Rechazada por Imagen';
                            $UsuarioMensaje->parentId = 0;
                            $UsuarioMensaje->proveedorId = $UsuarioMandante->mandante;
                            $UsuarioMensaje->tipo = "MESSAGEINV";
                            $UsuarioMensaje->paisId = $UsuarioMandante->paisId;
                            $UsuarioMensaje->fechaExpiracion = date("Y-m-d H:i:s", strtotime("+" . '1' . " days"));

                            $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($Transaction);
                            $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                            $Transaction->commit();
                        }
                    } catch (Exception $e) {
                    }
                } elseif ($usability == true) {
                    $UsuarioVerificacion->setObservacion("Rechazado por usabilidad");

                    try {
                        //ENVIO POPUP ESTADO RECHAZADO POR USABILIDAD - TEMPLATE
                        $ClasificadorFiltro = new Clasificador("", "ISACTIVEPOPUP");
                        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $ClasificadorFiltro->getClasificadorId(), $Usuario->paisId, "A");
                        $Mandante = new Mandante($Usuario->mandante);

                        if ($MandanteDetalle->valor == 1) {
                            $IsActivePopUp = "A";
                        } else {
                            $IsActivePopUp = "I";
                        }

                        if ($IsActivePopUp == "A") {
                            $popup = '';

                            $clasificador = new Clasificador("", "RECPOPUPJUMIOUSABILIDAD");

                            $template = new Template("", $UsuarioMandante->mandante, $clasificador->getClasificadorId(), $Usuario->paisId, strtolower($Usuario->idioma));

                            $popup .= $template->templateHtml;
                            $popup = str_replace("#userid#", $Usuario->usuarioId, $popup);

                            $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                            $Transaction = $UsuarioMySqlDAO->getTransaction();
                            $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
                            $UsuarioMensaje = new UsuarioMensaje();
                            $UsuarioMensaje->usufromId = 0;
                            $UsuarioMensaje->usutoId = $UsuarioMandante->getUsumandanteId();
                            $UsuarioMensaje->isRead = 0;
                            $UsuarioMensaje->body = $popup;
                            $UsuarioMensaje->msubject = 'Respuesta de Verificación Rechazada por Usabilidad';
                            $UsuarioMensaje->parentId = 0;
                            $UsuarioMensaje->proveedorId = $UsuarioMandante->mandante;
                            $UsuarioMensaje->tipo = "MESSAGEINV";
                            $UsuarioMensaje->paisId = $UsuarioMandante->paisId;
                            $UsuarioMensaje->fechaExpiracion = date("Y-m-d H:i:s", strtotime("+" . '1' . " days"));

                            $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($Transaction);
                            $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                            $Transaction->commit();
                        }
                    } catch (Exception $e) {
                    }
                } elseif ($similarity == true) {
                    $UsuarioVerificacion->setObservacion("Rechazado por semejanza");
                } elseif ($liveness == true) {
                    $UsuarioVerificacion->setObservacion("Rechazado por vivacidad");
                } elseif ($decisionType == true) {
                    $UsuarioVerificacion->setObservacion("Rechazado por Puntuación de alto riesgo");
                } elseif ($Usuario->verificado == "S") {
                    $Temp = " Usuario ya esta verificado";
                    $UsuarioVerificacion->setObservacion('Rechazada por Jumio' . $Temp);
                }
            }


            $UsuarioVerificacionMySqlDAO = new UsuarioVerificacionMySqlDAO();
            $UsuarioVerificacionMySqlDAO->update($UsuarioVerificacion);
            $Transaction = $UsuarioVerificacionMySqlDAO->getTransaction();

            foreach ($Response2->credentials as $key1 => $value1) {
                foreach ($value1->parts as $key => $value) {
                    if ($value->classifier == "FRONT") {
                        $tipo = 'USUDNIANTERIOR';

                        $Imagen = $this->connectionGETDetail($value->href);

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
                    }

                    if ($value->classifier == "BACK") {
                        $tipo = 'USUDNIPOSTERIOR';
                        //$file_contents1  = file_get_contents($value->href);

                        $Imagen = $this->connectionGETDetail($value->href);

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
                    }
                }
            }


            if ($Response2->capabilities->extraction[0]->data->firstName != "") {
                $Nombre = explode(" ", $Response2->capabilities->extraction[0]->data->firstName);
                $firstName = str_replace(["ñ", "Ñ"], ["n", "N"], $Nombre[0]);
                $secondName = str_replace(["ñ", "Ñ"], ["n", "N"], $Nombre[1]);

                $NombreAnterior = explode(" ", $PuntoVenta->nombreContacto);
                $UsuarioLog = new UsuarioLog2();
                $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                $UsuarioLog->setUsuarioIp('');
                $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
                $UsuarioLog->setUsuariosolicitaIp("");
                $UsuarioLog->setTipo("USUNOMBRE1");
                $UsuarioLog->setEstado("R");
                $UsuarioLog->setValorAntes($NombreAnterior[0]);
                $UsuarioLog->setValorDespues($firstName);
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
                $UsuarioLog->setValorAntes($NombreAnterior[1]);
                $UsuarioLog->setValorDespues($secondName);
                $UsuarioLog->setUsucreaId(0);
                $UsuarioLog->setUsumodifId(0);
                $UsuarioLog->setSversion($UsuarioVerificacion->getUsuverificacionId());
                $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);
                $UsuarioLogMySqlDAO2->insert($UsuarioLog);
            }

            if ($Response2->capabilities->extraction[0]->data->lastName != "") {
                $Apellidos = explode(" ", $Response2->capabilities->extraction[0]->data->lastName);
                $firstLastName = str_replace(["ñ", "Ñ"], ["n", "N"], $Apellidos[0]);
                $secondLastName = str_replace(["ñ", "Ñ"], ["n", "N"], $Apellidos[1]);

                $ApellidosAnterior = explode(" ", $PuntoVenta->nombreContacto);
                $UsuarioLog = new UsuarioLog2();
                $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                $UsuarioLog->setUsuarioIp('');
                $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
                $UsuarioLog->setUsuariosolicitaIp("");
                $UsuarioLog->setTipo("USUAPELLIDO1");
                $UsuarioLog->setEstado("R");
                $UsuarioLog->setValorAntes($ApellidosAnterior[2]);
                $UsuarioLog->setValorDespues($firstLastName);
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
                $UsuarioLog->setValorAntes($ApellidosAnterior[2]);
                $UsuarioLog->setValorDespues($secondLastName);
                $UsuarioLog->setUsucreaId(0);
                $UsuarioLog->setUsumodifId(0);
                $UsuarioLog->setSversion($UsuarioVerificacion->getUsuverificacionId());
                $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);
                $UsuarioLogMySqlDAO2->insert($UsuarioLog);
            }


            if ($Response2->capabilities->extraction[0]->data->documentNumber != "") {
                $UsuarioLog = new UsuarioLog2();
                $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                $UsuarioLog->setUsuarioIp('');
                $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
                $UsuarioLog->setUsuariosolicitaIp("");
                $UsuarioLog->setTipo("USUCEDULA");
                $UsuarioLog->setEstado("R");
                $UsuarioLog->setValorAntes($PuntoVenta->getCedula());
                $UsuarioLog->setValorDespues($Response2->capabilities->extraction[0]->data->documentNumber);
                $UsuarioLog->setUsucreaId(0);
                $UsuarioLog->setUsumodifId(0);
                $UsuarioLog->setSversion($UsuarioVerificacion->getUsuverificacionId());
                $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);
                $UsuarioLogMySqlDAO2->insert($UsuarioLog);
            }

            $Transaction->commit();


            $UsuarioMySqlDAO = new UsuarioMySqlDAO();
            $Transaction = $UsuarioMySqlDAO->getTransaction();

            $VerificacionLog = new VerificacionLog();
            $VerificacionLog->setUsuverificacionId($UsuarioVerificacion->getUsuverificacionId());
            $VerificacionLog->setTipo('FINALDECISION');
            $VerificacionLog->setJson(json_encode($Response2));


            $VerificacionLogMySqlDAO = new VerificacionLogMySqlDAO($Transaction);
            $VerificacionLogMySqlDAO->insert($VerificacionLog);

            $Transaction->commit();


            $UsuarioMySqlDAO = new UsuarioMySqlDAO();
            $Transaction = $UsuarioMySqlDAO->getTransaction();

            $UsuarioMensaje = new UsuarioMensaje();
            $UsuarioMensaje->usufromId = 0;
            $UsuarioMensaje->usutoId = $Usuario->getUsuarioId();
            $UsuarioMensaje->isRead = 0;
            $UsuarioMensaje->body = '<div style="margin:50px;">La verificación de cuenta ha sido rechazada, intentelo nuevamente por favor en caso de requerir más información comuniquese con nuestro chat online</div>';
            $UsuarioMensaje->msubject = 'Respuesta de Verificación';
            $UsuarioMensaje->parentId = 0;
            $UsuarioMensaje->proveedorId = $Usuario->mandante;
            $UsuarioMensaje->tipo = "MENSAJE";
            $UsuarioMensaje->paisId = $Usuario->paisId;
            $UsuarioMensaje->fechaExpiracion = date("Y-m-d H:i:s", strtotime("+" . '1' . " days"));


            $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($Transaction);
            $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
            $Transaction->commit();

            try {
                try {
                    //ENVIO SMS ESTADO RECHAZADO - TEMPLATE
                    $ClasificadorFiltro = new Clasificador("", "ISACTIVESMS");
                    $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $ClasificadorFiltro->getClasificadorId(), $Usuario->paisId, $Usuario->estado);
                    $Mandante = new Mandante($Usuario->mandante);

                    if ($MandanteDetalle->valor == 1) {
                        $IsActiveSms = "A";
                    } else {
                        $IsActiveSms = "I";
                    }

                    if ($IsActiveSms == "A") {
                        $mensaje_txt = '';

                        $clasificador = new Clasificador("", "RECSMSJUMIO");

                        $template = new Template("", $UsuarioMandante->mandante, $clasificador->getClasificadorId(), $Usuario->paisId, strtolower($Usuario->idioma));

                        $mensaje_txt .= $template->templateHtml;

                        $mensaje_txt = str_replace("#userid#", $Usuario->usuarioId, $mensaje_txt);
                        $mensaje_txt = str_replace("#name#", $Usuario->nombre, $mensaje_txt);
                        $mensaje_txt = str_replace("#identification#", $Registro->cedula, $mensaje_txt);
                        $mensaje_txt = str_replace("#lastname#", $Registro->apellido1 . ' ' . $Registro->apellido2, $mensaje_txt);
                        $mensaje_txt = str_replace("#login#", $Usuario->login, $mensaje_txt);
                        $mensaje_txt = str_replace("#mandante#", $Usuario->mandante, $mensaje_txt);
                        $mensaje_txt = str_replace("#creationdate#", $Usuario->fechaCrea, $mensaje_txt);
                        $mensaje_txt = str_replace("#email#", $Registro->email, $mensaje_txt);
                        $mensaje_txt = str_replace("#pais#", $Usuario->paisId, $mensaje_txt);
                        $mensaje_txt = str_replace("#link#", $Mandante->baseUrl, $mensaje_txt);
                        $mensaje_txt = str_replace("#telefono#", $Registro->celular, $mensaje_txt);

                        $ConfigurationEnviroment = new ConfigurationEnvironment();

                        $msubjetc = "Solicitud de verificacion rechazada";
                        $mtitle = "Verificacion de cuenta";

                        $envio = $ConfigurationEnviroment->EnviarCorreoVersion3($Usuario->login, "", "", $msubjetc, '', $mtitle, $mensaje_txt, "", "", '', $Usuario->mandante);
                    }
                } catch (Exception $e) {
                }

                try {
                    //ENVIO POPUP ESTADO RECHAZADO - TEMPLATE
                    $ClasificadorFiltro = new Clasificador("", "ISACTIVEPOPUP");
                    $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $ClasificadorFiltro->getClasificadorId(), $Usuario->paisId, $Usuario->estado);
                    $Mandante = new Mandante($Usuario->mandante);

                    if ($MandanteDetalle->valor == 1) {
                        $IsActivePopUp = "A";
                    } else {
                        $IsActivePopUp = "I";
                    }

                    if ($IsActivePopUp == "A") {
                        $popup = '';

                        $clasificador = new Clasificador("", "RECPOPUPJUMIO");

                        $template = new Template("", $UsuarioMandante->mandante, $clasificador->getClasificadorId(), $Usuario->paisId, strtolower($Usuario->idioma));

                        $popup .= $template->templateHtml;

                        $popup = str_replace("#userid#", $Usuario->usuarioId, $popup);
                        $popup = str_replace("#name#", $Usuario->nombre, $popup);
                        $popup = str_replace("#identification#", $Registro->cedula, $popup);
                        $popup = str_replace("#lastname#", $Registro->apellido1 . ' ' . $Registro->apellido2, $popup);
                        $popup = str_replace("#login#", $Usuario->login, $popup);
                        $popup = str_replace("#mandante#", $Usuario->mandante, $popup);
                        $popup = str_replace("#creationdate#", $Usuario->fechaCrea, $popup);
                        $popup = str_replace("#email#", $Registro->email, $popup);
                        $popup = str_replace("#pais#", $Usuario->paisId, $popup);
                        $popup = str_replace("#link#", $Mandante->baseUrl, $popup);
                        $popup = str_replace("#telefono#", $Registro->celular, $popup);

                        $ConfigurationEnviroment = new ConfigurationEnvironment();

                        $msubjetc = "Solicitud de verificacion rechazada";
                        $mtitle = "Verificacion de cuenta";

                        $envio = $ConfigurationEnviroment->EnviarCorreoVersion3($Usuario->login, "", "", $msubjetc, '', $mtitle, $popup, "", "", '', $Usuario->mandante);
                    }
                } catch (Exception $e) {
                }

                try {
                    //ENVIO EMAIL ESTADO RECHAZADO - TEMPLATE
                    $ClasificadorFiltro = new Clasificador("", "ISACTIVEEMAIL");
                    $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $ClasificadorFiltro->getClasificadorId(), $Usuario->paisId, $Usuario->estado);
                    $Mandante = new Mandante($Usuario->mandante);

                    if ($MandanteDetalle->valor == 1) {
                        $IsActiveEmail = "A";
                    } else {
                        $IsActiveEmail = "I";
                    }

                    if ($IsActiveEmail == "A") {
                        $email = '';

                        $clasificador = new Clasificador("", "RECEMAILJUMIO");

                        $template = new Template("", $UsuarioMandante->mandante, $clasificador->getClasificadorId(), $Usuario->paisId, strtolower($Usuario->idioma));

                        $email .= $template->templateHtml;

                        $email = str_replace("#userid#", $Usuario->usuarioId, $email);
                        $email = str_replace("#name#", $Usuario->nombre, $email);
                        $email = str_replace("#identification#", $Registro->cedula, $email);
                        $email = str_replace("#lastname#", $Registro->apellido1 . ' ' . $Registro->apellido2, $email);
                        $email = str_replace("#login#", $Usuario->login, $email);
                        $email = str_replace("#mandante#", $Usuario->mandante, $email);
                        $email = str_replace("#creationdate#", $Usuario->fechaCrea, $email);
                        $email = str_replace("#email#", $Registro->email, $email);
                        $email = str_replace("#pais#", $Usuario->paisId, $email);
                        $email = str_replace("#link#", $Mandante->baseUrl, $email);
                        $email = str_replace("#telefono#", $Registro->celular, $email);

                        $ConfigurationEnviroment = new ConfigurationEnvironment();

                        $msubjetc = "Solicitud de verificacion rechazada";
                        $mtitle = "Verificacion de cuenta";

                        $envio = $ConfigurationEnviroment->EnviarCorreoVersion3($Usuario->login, "", "", $msubjetc, '', $mtitle, $email, "", "", '', $Usuario->mandante);
                    }
                } catch (Exception $e) {
                }

                try {
                    //ENVIO INBOX ESTADO RECHAZADO - TEMPLATE
                    $ClasificadorFiltro = new Clasificador("", "ISACTIVEINBOX");
                    $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->mandante, $ClasificadorFiltro->getClasificadorId(), $Usuario->paisId, $Usuario->estado);
                    $Mandante = new Mandante($Usuario->mandante);

                    if ($MandanteDetalle->valor == 1) {
                        $IsActiveInbox = "A";
                    } else {
                        $IsActiveInbox = "I";
                    }

                    if ($IsActiveInbox == "A") {
                        $inbox = '';

                        $clasificador = new Clasificador("", "RECINBOXJUMIO");

                        $template = new Template("", $UsuarioMandante->mandante, $clasificador->getClasificadorId(), $Usuario->paisId, strtolower($Usuario->idioma));

                        $inbox .= $template->templateHtml;

                        $inbox = str_replace("#userid#", $Usuario->usuarioId, $inbox);
                        $inbox = str_replace("#name#", $Usuario->nombre, $inbox);
                        $inbox = str_replace("#identification#", $Registro->cedula, $inbox);
                        $inbox = str_replace("#lastname#", $Registro->apellido1 . ' ' . $Registro->apellido2, $inbox);
                        $inbox = str_replace("#login#", $Usuario->login, $inbox);
                        $inbox = str_replace("#mandante#", $Usuario->mandante, $inbox);
                        $inbox = str_replace("#creationdate#", $Usuario->fechaCrea, $inbox);
                        $inbox = str_replace("#email#", $Registro->email, $inbox);
                        $inbox = str_replace("#pais#", $Usuario->paisId, $inbox);
                        $inbox = str_replace("#link#", $Mandante->baseUrl, $inbox);
                        $inbox = str_replace("#telefono#", $Registro->celular, $inbox);

                        $ConfigurationEnviroment = new ConfigurationEnvironment();

                        $msubjetc = "Solicitud de verificacion rechazada";
                        $mtitle = "Verificacion de cuenta";

                        $envio = $ConfigurationEnviroment->EnviarCorreoVersion3($Usuario->login, "", "", $msubjetc, '', $mtitle, $inbox, "", "", '', $Usuario->mandante);
                    }
                } catch (Exception $e) {
                }
            } catch (Exception $e) {
            }


            $response = json_encode($Response2);
            //print_r(" Verificación Rechazada");
            return $response;
        }
    }

    /**
     * La función `envioPopups` envía mensajes emergentes o correos electrónicos a los usuarios según ciertas
     * condiciones y plantillas.
     *
     * @param string $abreviado       La función `envioPopups` toma varios parámetros para enviar ventanas emergentes o
     *                                correos electrónicos a los usuarios según una abreviatura específica. La
     *                                abreviatura se utiliza para determinar el tipo de mensaje a enviar A
     *                                continuación, se detallan los parámetros.
     * @param string $msg             La función `envioPopups` gestiona el envío de ventanas emergentes o correos
     *                                electrónicos a los usuarios según los parámetros proporcionados. El parámetro
     *                                `msg` se utiliza para especificar el contenido del mensaje que se incluirá en la
     *                                ventana emergente o correo electrónico que se envía al usuario.
     * @param object $UsuarioMandante Es un objeto que representa la información de la cuenta del usuario como.
     *                                - [int] ID: Es un identificador único que se asigna a cada usuario.
     *                                - [string] nombre: Representa el nombre completo del usuario..
     *                                - [string] login: Es el nombre de usuario que se utiliza para acceder al sistema.
     *                                - [string] creation date: ndica la fecha y hora en que se creó la cuenta del
     *                                usuario.
     *                                - [string] email: Es la dirección de correo electrónico del usuario.
     *                                - [int] country: Indica el país de residencia del usuario.
     *                                - [string] phone number: Se utiliza para la comunicación con el usuario y para la
     *                                verificación de identidad.
     * @param int    $Usuario         La función `envioPopups` toma varios parámetros para enviar ventanas emergentes o
     *                                correos electrónicos a los usuarios. A continuación, se detallan los parámetros.
     *                                -[int] paisId: ID del país del usuario.
     *                                -[int] usuarioId: ID del usuario.
     *                                -[int] mandante: Mandante del usuario.
     *                                -[string] email: Correo electrónico del usuario.
     *                                -[string] cedula: Cédula del usuario.
     *                                -[string] celular: Número de celular del usuario.
     * @param string $tipo            El parámetro "tipo" en la función `envioPopups` se utiliza para especificar el
     *                                tipo de mensaje que se envía. Sirve para diferenciar entre los diferentes tipos
     *                                de notificaciones o ventanas emergentes que se deben enviar al usuario. El valor
     *                                de "tipo" determinará el contenido y el propósito.
     * @param object $Registro        El parámetro `$Registro` representa la información de registro del usuario, como.
     *                                -[string] nombre: Nombre del usuario.
     *                                -[string] identificación: Cédula del usuario.
     *                                -[string] apellido: apellido del usuario.
     *                                -[string] email: Correo electrónico del usuario.
     * @param int    $Mandante        La función `envioPopups` toma varios parámetros y realiza diferentes acciones
     *                                según el valor del parámetro `$Mandante`.
     *
     * @return void
     */
    public function envioPopups($abreviado, $msg, $UsuarioMandante, $Usuario, $tipo, $Registro, $Mandante)
    {
        if (
            $abreviado == 'APROSMSJUMIO' || $abreviado == 'PENDSMSJUMIO' || $abreviado == 'RECSMSJUMIO' ||
            $abreviado == 'APROEMAILJUMIO' || $abreviado == 'PENDEMAILJUMIO' || $abreviado == 'RECEMAILJUMIO' ||
            $abreviado == 'APROINBOXJUMIO' || $abreviado == 'PENDINBOXJUMIO' || $abreviado == 'RECINBOXJUMIO'
        ) {
            $templateHtml = '';

            $clasificador = new Clasificador("", $abreviado);

            $template = new Template("", $UsuarioMandante->mandante, $clasificador->getClasificadorId(), $Usuario->paisId, strtolower($Usuario->idioma));

            $templateHtml .= $template->templateHtml;

            $templateHtml = str_replace("#userid#", $Usuario->usuarioId, $templateHtml);
            $templateHtml = str_replace("#name#", $Usuario->nombre, $templateHtml);
            $templateHtml = str_replace("#identification#", $Registro->cedula, $templateHtml);
            $templateHtml = str_replace("#lastname#", $Registro->apellido1 . ' ' . $Registro->apellido2, $templateHtml);
            $templateHtml = str_replace("#login#", $Usuario->login, $templateHtml);
            $templateHtml = str_replace("#mandante#", $Usuario->mandante, $templateHtml);
            $templateHtml = str_replace("#creationdate#", $Usuario->fechaCrea, $templateHtml);
            $templateHtml = str_replace("#email#", $Registro->email, $templateHtml);
            $templateHtml = str_replace("#pais#", $Usuario->paisId, $templateHtml);
            $templateHtml = str_replace("#link#", $Mandante->baseUrl, $templateHtml);
            $templateHtml = str_replace("#telefono#", $Registro->celular, $templateHtml);

            $UsuarioMySqlDAO = new UsuarioMySqlDAO();
            $Transaction = $UsuarioMySqlDAO->getTransaction();
            $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
            $UsuarioMensaje = new UsuarioMensaje();
            $UsuarioMensaje->usufromId = 0;
            $UsuarioMensaje->usutoId = $UsuarioMandante->getUsumandanteId();
            $UsuarioMensaje->isRead = 0;
            $UsuarioMensaje->body = $templateHtml;
            $UsuarioMensaje->msubject = $msg;
            $UsuarioMensaje->parentId = 0;
            $UsuarioMensaje->proveedorId = $UsuarioMandante->mandante;
            $UsuarioMensaje->tipo = $tipo;
            $UsuarioMensaje->paisId = $UsuarioMandante->paisId;
            $UsuarioMensaje->fechaExpiracion = date("Y-m-d H:i:s", strtotime("+" . '1' . " days"));

            $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($Transaction);
            $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
            $Transaction->commit();

            $ConfigurationEnviroment = new ConfigurationEnvironment();

            $msubjetc = $msg;
            $mtitle = "Verificacion de cuenta";

            $envio = $ConfigurationEnviroment->EnviarCorreoVersion3($Usuario->login, "", "", $msubjetc, '', $mtitle, $templateHtml, "", "", '', $Usuario->mandante);
        } else {
            $popup = '';

            $clasificador = new Clasificador("", $abreviado);

            $template = new Template("", $UsuarioMandante->mandante, $clasificador->getClasificadorId(), $Usuario->paisId, strtolower($Usuario->idioma));

            $popup .= $template->templateHtml;
            $popup = str_replace("#userid#", $Usuario->usuarioId, $popup);

            $UsuarioMySqlDAO = new UsuarioMySqlDAO();
            $Transaction = $UsuarioMySqlDAO->getTransaction();
            $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, $Usuario->mandante);
            $UsuarioMensaje = new UsuarioMensaje();
            $UsuarioMensaje->usufromId = 0;
            $UsuarioMensaje->usutoId = $UsuarioMandante->getUsumandanteId();
            $UsuarioMensaje->isRead = 0;
            $UsuarioMensaje->body = $popup;
            $UsuarioMensaje->msubject = $msg;
            $UsuarioMensaje->parentId = 0;
            $UsuarioMensaje->proveedorId = $UsuarioMandante->mandante;
            $UsuarioMensaje->tipo = $tipo;
            $UsuarioMensaje->paisId = $UsuarioMandante->paisId;
            $UsuarioMensaje->fechaExpiracion = date("Y-m-d H:i:s", strtotime("+" . '1' . " days"));

            $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO($Transaction);
            $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
            $Transaction->commit();

            $ConfigurationEnviroment = new ConfigurationEnvironment();

            $msubjetc = $msg;
            $mtitle = "Verificacion de cuenta";

            $envio = $ConfigurationEnviroment->EnviarCorreoVersion3($Usuario->login, "", "", $msubjetc, '', $mtitle, $popup, "", "", '', $Usuario->mandante);
        }
    }


    /**
     * Recupera la dirección IP del cliente.
     *
     * La función `get_client_ip` en PHP recupera la dirección IP del cliente verificando varias variables del
     * servidor.
     * * servidor
     *
     * @return string La función `get_client_ip()` devuelve la dirección IP del cliente. Si la dirección IP no puede
     * Si la dirección IP no puede ser determinada desde ninguna de las cabeceras HTTP o la dirección remota, devolverá
     * 'UNKNOWN'.
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
     * La función `connectionGET` usa cURL para enviar una solicitud GET.
     *
     * La función `connectionGET` usa cURL para enviar una solicitud GET con los encabezados y parámetros
     * especificados, y devuelve la respuesta.
     *
     * @param string $string La función `connectionGET` es una función PHP que usa cURL para realizar
     *                       una solicitud GET a una URL específica con algunos encabezados y un token de autorización.
     *                       También incluye información de depuración si la variable de entorno `debug` se establece
     *                       en verdadero.
     *
     * @return string La función `connectionGET` devuelve el resultado de la solicitud cURL realizada a la
     * URL especificada con los parámetros proporcionados. El resultado es la respuesta recibida del servidor
     * tras realizar la solicitud GET.
     */
    public function connectionGET($string)
    {
        $ch = curl_init($this->urlRetrieval . $string);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Accept: application/json", "Authorization: Bearer " . $this->token));

        $result = (curl_exec($ch));

        if ($_ENV['debug']) {
            print_r(PHP_EOL);
            print_r('DANIEL');
            print_r(PHP_EOL);
            print_r(str_replace('//', '/', $this->urlRetrieval . $string));
            print_r(PHP_EOL);
            print_r($this->token);
            print_r(PHP_EOL);
            print_r(PHP_EOL);
            print_r($result);
        }
        return ($result);
    }


    /**
     * La función `connectionGETDetail` realiza una solicitud GET.
     *
     * La función `connectionGETDetail` realiza una solicitud GET a una URL específica con autenticación básica usando
     * cURL en PHP.
     *
     * @param string $UrlDetail La función `connectionGETDetail` se utiliza para realizar una solicitud GET a una URL
     *                          específica con autenticación básica. La función toma el detalle de la URL como
     *                          parámetro.
     *
     * @return string La función `connectionGETDetail` devuelve el resultado de la solicitud cURL realizada a la URL
     * especificada en el parámetro ``. El resultado es la respuesta obtenida del servidor tras realizar una
     * solicitud GET con los encabezados y detalles de autenticación especificados.
     */
    public function connectionGETDetail($UrlDetail)
    {
        $Auth = base64_encode($this->clientAPI . ":" . $this->password);

        $ch = curl_init($UrlDetail);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Accept: application/json", "Authorization: Basic " . $Auth));

        $result = (curl_exec($ch));
        return ($result);
    }


    /**
     * La función `connectionToken` usa cURL para realizar una solicitud POST.
     *
     * La función `connectionToken` usa cURL para realizar una solicitud POST y obtener un token de conexión para la
     * autenticación.
     *
     * @return string La función `connectionToken()` realiza una solicitud POST a una URL específica con ciertos
     *                parámetros y encabezados. Utiliza cURL para enviar la solicitud y obtener la respuesta. La
     *                respuesta se almacena en la variable `$result` y luego la función la devuelve.
     */
    public function connectionToken()
    {
        $ch = curl_init($this->urltoken);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, $this->clientId . ":" . $this->clientSecret);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded", "Accept: application/json"));

        if ($_ENV['debug']) {
            print_r(PHP_EOL);
            print_r($this->clientId . ":" . $this->clientSecret);
            print_r(PHP_EOL);
        }

        $result = (curl_exec($ch));

        if ($_ENV['debug']) {
            print_r($result);
        }

        syslog(LOG_WARNING, "JUMIO TOKEN" . " " . $result);
        return ($result);
    }


    /**
     * La función `connectionlink` usa cURL para realizar una solicitud POST.
     *
     * La función `connectionlink` usa cURL para realizar una solicitud POST con los encabezados y parámetros
     * especificados, y registra los datos de la solicitud y la respuesta si la depuración está habilitada.
     *
     * @param string $string La función `connectionlink` es una función PHP que realiza una solicitud POST usando cURL.
     *                       Establece varias opciones de cURL, como el tipo de solicitud, el agente de usuario,
     *                       el cuerpo de la solicitud, los encabezados y ejecuta la solicitud.
     *
     * @return string La función `connectionlink` devuelve el resultado de la solicitud cURL realizada a la URL
     *                especificada con los datos de la cadena proporcionada. El resultado es la respuesta recibida del
     *                servidor después de realizar la solicitud POST.
     */
    public function connectionlink($string)
    {
        if ($_ENV['debug']) {
            print_r('URL URL connectionlink');
            print_r($this->url);
            print_r($string);
        }

        $ch = curl_init($this->url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Content-Length: " . strlen($string), "Accept: application/json", "Authorization: Bearer " . $this->token));

        if ($_ENV['debug']) {
            print_r('tokentoken');
            print_r($this->token);
        }

        $result = (curl_exec($ch));

        syslog(LOG_WARNING, "JUMIO SERVICE DATA link" . " " . $string);
        if ($_ENV['debug']) {
            print_r($result);
        }
        syslog(LOG_WARNING, "JUMIO SERVICE RESPONSE link" . " " . $result);

        return ($result);
    }


    /**
     * La función `connectionlinkUpdate` envía una solicitud PUT.
     *
     * La función `connectionlinkUpdate` envía una solicitud PUT con los datos especificados a una URL usando cURL
     * en PHP.
     *
     * @param string $string    La función `connectionlinkUpdate` es una función PHP que
     *                          actualiza un enlace de conexión usando cURL.
     * @param string $accountId La función `connectionlinkUpdate` es una función PHP que
     *                          actualiza un enlace de conexión usando cURL. Envía una solicitud PUT a una URL
     *                          específica con los datos proporcionados y algunos encabezados, incluyendo un token de
     *                          autorización.
     *
     * @return object La función `connectionlinkUpdate` devuelve el resultado de la solicitud cURL después
     * de ejecutarla. El resultado se procesa usando `json_decode` para convertirlo en un objeto
     * antes de devolverlo.
     */
    public function connectionlinkUpdate($string, $accountId)
    {
        if ($_ENV['debug']) {
            print_r('URL URL');
            print_r($this->url . $accountId);
        }

        $ch = curl_init($this->url . $accountId);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Content-Length: " . strlen($string), "Accept: application/json", "Authorization: Bearer " . $this->token));

        syslog(LOG_WARNING, "JUMIO SERVICE DATA " . $accountId . " " . $string);
        $result = (curl_exec($ch));

        if ($_ENV['debug']) {
            print_r('URL URL result');
            print_r($result);
        }

        syslog(LOG_WARNING, "JUMIO SERVICE RESPONSE " . $accountId . " " . $result);
        return (json_decode(json_encode($result)));
    }
}
