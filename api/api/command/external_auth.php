<?php

use Backend\dto\BonoInterno;
use Backend\dto\Clasificador;
use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\mysql\SitioTrackingMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\MandanteDetalle;
use Backend\dto\Pais;
use Backend\dto\PaisMoneda;
use Backend\dto\Registro;
use Backend\dto\Template;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\UsuarioLog2;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioOtrainfo;
use Backend\dto\UsuarioPerfil;
use Backend\dto\UsuarioPremiomax;
use Backend\dto\UsuarioRestriccion;
use Backend\mysql\RegistroMySqlDAO;
use Backend\mysql\UsuarioConfiguracionMySqlDAO;
use Backend\mysql\UsuarioLog2MySqlDAO;
use Backend\mysql\UsuarioMandanteMySqlDAO;
use Backend\mysql\UsuarioOtrainfoMySqlDAO;
use Backend\mysql\UsuarioPerfilMySqlDAO;
use Backend\mysql\UsuarioPremiomaxMySqlDAO;
use Backend\websocket\WebsocketUsuario;

/**
 * Autenticación externa de usuario.
 *
 * @param object $json Objeto JSON que contiene los parámetros de entrada.
 * @param string $json->params->id_token Token de identificación.
 * @param bool $json->params->in_app Indica si la solicitud se realiza desde la aplicación.
 * @param string $json->params->site_id Identificador del sitio.
 * @param string $json->params->country País del usuario.
 * @param int $json->params->type Tipo de autenticación (1: Google, 2: Facebook).
 *
 * @return array Respuesta con el código de resultado y los datos.
 *  - code: int Código de resultado.
 *  - message: string Mensaje de resultado.
 *  - data: array Datos de la respuesta.
 *      - auth_token: string Token de autenticación.
 *      - user_id: int ID del usuario.
 *      - id_platform: int ID de la plataforma.
 *      - channel_id: int ID del canal.
 *      - tokenSB: string Token SB.
 *      - user_menu: array Menú del usuario.
 *      - redirectUrl: string URL de redirección.
 *      - in_app: int Indica si la solicitud se realiza desde la aplicación (1: sí, 0: no).
 *  - rid: string Identificador de la solicitud.
 *
 * @throws Exception Si se detecta un comportamiento inusual o hay un error de autenticación.
 */

/**
 * Obtiene la URL de autenticación externa basada en el tipo de autenticación.
 *
 * @param int $value Tipo de autenticación (1: Google, 2: Facebook, 3: Otro).
 * @return string URL de autenticación correspondiente al tipo.
 */
function getUrl($value) {
    $urlInfo = [
        1 => 'https://oauth2.googleapis.com/tokeninfo?id_token=',
        2 => 'https://graph.facebook.com/me?fields=email,name&access_token=',
        3 => ''
    ];

    return isset($urlInfo) ? $urlInfo[$value] : [];
}


/**
 * Verifica si un usuario está restringido.
 *
 * @param string $email Correo electrónico del usuario.
 * @param int $partner ID del socio.
 * @param int $country ID del país.
 * @return bool `true` si el usuario está restringido, `false` en caso contrario.
 */
function isRestrictedUser($email, $partner, $country) {
    $select = 'SELECT * from usuario_restriccion';
    $where = "usuario_restriccion.email = '{$email}' AND usuario_restriccion.mandante IN(-1, {$partner}) AND usuario_restriccion.pais_id IN(0, {$country}) LIMIT 0, 1";

    $query = "{$select} WHERE {$where}";

    $UsuarioRestriccion = new UsuarioRestriccion();
    $data = $UsuarioRestriccion->getQueryCustom($query);
    $data = json_decode($data, true);

    return oldCount($data['result']) > 0 ? true : false;
}


try {
    $ConfigurationEnvironment = new ConfigurationEnvironment();

    /**
     * Obtiene los parámetros del objeto JSON.
     *
     * @var object $params Parámetros extraídos del JSON.
     */
    $params = $json->params;
    $id_token = ($params->id_token);
    $in_app = $params->in_app;
    $site_id = $params->site_id;
    $country = $params->country;
    $type = $params->type;

    // Verificación de parámetros requeridos.
    if($site_id == '') throw new Exception('Inusual Detected', 100001);
    if($site_id == '13') throw new Exception('Inusual Detected', 100001);
    if($country == '') throw new Exception('Inusual Detected', 100001);

    $Mandante = new Mandante($site_id);
    $Pais = new Pais('', strtoupper($country));

    /*Este archivo maneja la autenticación externa de usuarios mediante tokens de identificación de Google y Facebook, verificando restricciones y
     configuraciones del usuario, y gestionando la creación de nuevos usuarios en el sistema.*/
    try {
        $Clasificador = new Clasificador('', 'TOTALCONTINGENCE');
        $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $Clasificador->getClasificadorId(), $Pais->paisId, 'A');

        if($MandanteDetalle->getValor() == 1) throw new Exception('We are currently in the process of maintaining the site.',30004);
    } catch (Exception $ex) { if($ex->getCode() == 30004) throw $ex; }

    $url = getUrl($type) . "{$id_token}";

    $curl = curl_init();

    // Configura las opciones de cURL.
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 500);
    curl_setopt($curl, CURLOPT_HTTPHEADER, [ 'Content-Type' => 'application/json' ]);

    $request = curl_exec($curl);
    curl_close($curl);

    $response = json_decode($request, true);

    /*El código verifica la autenticación de un usuario mediante tokens de Google o Facebook, lanza excepciones si hay errores de autenticación
     y maneja la creación de nuevos usuarios en el sistema.*/
    if($type == 1 && !isset($response['email_verified']) && $response['email_verified'] !== true) throw new Exception('Error de autenticacion', 7000);
    if($type == 2 && !isset($response['email']) && !isset($response['name'])) throw new Exception('Error de autenticacion', 7000);
    if($response['email'] =='') throw new Exception('Error de autenticacion', 7000);

    /*El código intenta autenticar a un usuario utilizando un token de identificación. Si la autenticación falla, lanza una excepción.
     Si tiene éxito, obtiene los datos de inicio de sesión del usuario.*/
    $origin = 0;
    $redirect = '/deportes?frm=lgn';

    $new_user = false;

    try {
        $Usuario = new Usuario();
        $login_data = (object)$Usuario->login($response['email'], '', '', $Mandante->mandante, '', true);
    } catch (Exception $ex) {
        if($ex->getCode() == 30002) {
            /*El código verifica si un usuario está restringido, genera claves, obtiene la fecha actual y la dirección IP del usuario, y determina la fuente de fondos según la referencia HTTP.*/
            if(isRestrictedUser($response['email'], $Mandante->mandante, $Pais->paisId)) throw new Exception('user restrict', 20028);

            $PaisMoneda = new PaisMoneda($Pais->paisId);

            $token_itainment = $ConfigurationEnvironment->GenerarClaveTicket2(12);
            $active_key = $ConfigurationEnvironment->GenerarClaveTicket2(15);
            $current_date = date('Y-m-d H:i:s');
            $dir_ip = isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']);
            $dir_ip = explode(",", $dir_ip)[0];
            $source_funds = strpos($_SERVER['HTTP_REFERER'], 'acropolis') !== false ? 2 : 0;

            $UsuarioMysqlDAO = new UsuarioMySqlDAO();
            $Transaction = $UsuarioMysqlDAO->getTransaction();

            /*Instancia el objeto usuario y asigna los valores correspondientes*/
            $Usuario = new Usuario();
            $Usuario->login = $response['email'];
            $Usuario->nombre = $response['name'];
            $Usuario->clave = '';
            $Usuario->estado = 'A';
            $Usuario->fechaUlt = $current_date;
            $Usuario->claveTv = '';
            $Usuario->estadoAnt = 'I';
            $Usuario->intentos = 0;
            $Usuario->estadoEsp = 'A';
            $Usuario->observ = '';
            $Usuario->dirIp = $dir_ip;
            $Usuario->eliminado = 'N';
            $Usuario->mandante = $Mandante->mandante;
            $Usuario->claveCasino = '';
            $Usuario->tokenItainment = $token_itainment;
            $Usuario->fechaClave = '';
            $Usuario->retirado = 'N';
            $Usuario->fechaRetiro = '';
            $Usuario->horaRetiro = '';
            $Usuario->bloqueoVentas = 'N';
            $Usuario->infoEquipo = '';
            $Usuario->estadoJugador = 'NN';
            $Usuario->tokenCasino = '';
            $Usuario->sponsorId = 0;
            $Usuario->verifCorreo = 'N';
            $Usuario->paisId = $Pais->paisId;
            $Usuario->moneda = $PaisMoneda->moneda;
            $Usuario->idioma = $Pais->idioma;
            $Usuario->permiteActivareg = 'N';
            $Usuario->test = 'N';
            $Usuario->tiempoLimitedeposito = 0;
            $Usuario->tiempoAutoexclusion = 0;
            $Usuario->cambiosAprobacion = 'S';
            $Usuario->timezone = '-5';
            $Usuario->puntoventaId = 0;
            $Usuario->usucreaId = 0;
            $Usuario->usumodifId = 0;
            $Usuario->usuretiroId = 0;
            $Usuario->fechaCrea = $current_date;
            $Usuario->origen = $origin;
            $Usuario->fechaActualizacion = $current_date;
            $Usuario->documentoValidado = 'I';
            $Usuario->usuDocvalido = 0;
            $Usuario->estadoValida = 'N';
            $Usuario->usuvalidaId = 0;
            $Usuario->fechaValida = $current_date;
            $Usuario->contingencia = 'I';
            $Usuario->contingenciaDeportes = 'I';
            $Usuario->contingenciaCasino = 'I';
            $Usuario->contingenciaCasvivo = 'I';
            $Usuario->contingenciaVirtuales = 'I';
            $Usuario->contingenciaPoker = 'I';
            $Usuario->restriccionIp = 'I';
            $Usuario->ubicacionLongitud = '';
            $Usuario->ubicacionLatitud = '';
            $Usuario->usuarioIp = '';
            $Usuario->tokenGoogle = 'I';
            $Usuario->tokenLocal = 'I';
            $Usuario->saltGoogle = '';
            $Usuario->skype = '';
            $Usuario->plataforma = 0;
            $Usuario->fechaDocvalido = '1970-01-01 00:00:00';
            $Usuario->equipoId = 0;
            $Usuario->verifCelular = $Mandante->mandante == 14 ? 'S' : '';
            $Usuario->fechaVerificado = $current_date;

            $user_id = $UsuarioMysqlDAO->insert($Usuario);

            $name_parts = explode(' ', $response['name']);

            $first_name = oldCount($name_parts) % 2 === 0 ? implode(' ', array_slice($name_parts, 0, round((oldCount($name_parts) - 1) / 2))) : $name_parts[0];
            $firts_surname = oldCount($name_parts) % 2 === 0 ? implode(' ', array_slice($name_parts, round((oldCount($name_parts) - 1) / 2), oldCount($name_parts) - 1)) : $name_parts[oldCount($name_parts) - 1];

            // Crea una nueva instancia de Registro y asigna valor a sus atributos
            $Registro = new Registro();
            $Registro->cedula = '';
            $Registro->celular = '';
            $Registro->mandante = $Mandante->mandante;
            $Registro->nombre = $response['name'];
            $Registro->email = $response['email'];
            $Registro->claveActiva = $active_key;
            $Registro->estado = 'A';
            $Registro->usuarioId = $user_id;
            $Registro->creditosBase = 0;
            $Registro->creditos = 0;
            $Registro->creditosAnt = 0;
            $Registro->creditosBaseAnt = 0;
            $Registro->ciudadId = 0;
            $Registro->casino = 0;
            $Registro->casinoBase = 0;
            $Registro->nombre1 = $first_name ?: '';
            $Registro->nombre2 = '';
            $Registro->apellido1 = $firts_surname ?: '';
            $Registro->apellido2 = '';
            $Registro->sexo = 'M';
            $Registro->tipoDoc = '';
            $Registro->direccion = '';
            $Registro->telefono = '';
            $Registro->ciudnacimId = 0;
            $Registro->nacionalidadId = 0;
            $Registro->dirIp = $dir_ip;
            $Registro->ocupacion = '';
            $Registro->rangoingresoId = 0;
            $Registro->origenfondosId = $source_funds;
            $Registro->origenFondos = '';
            $Registro->paisnacimId = 0;
            $Registro->puntoventaId = 0;
            $Registro->preregistroId = 0;
            $Registro->creditosBono = 0;
            $Registro->creditosBonoAnt = 0;
            $Registro->usuvalidaId = 0;
            $Registro->fechaValida = $current_date;
            $Registro->codigoPostal = '';
            $Registro->ciudexpedId = 0;
            $Registro->fechaExped = '';
            $Registro->estadoValida = $Mandante->mandante == 13 ? 'A' : 'I';
            $Registro->afiliadorId = 0;
            $Registro->bannerId = 0;
            $Registro->linkId = 0;
            $Registro->codpromocionalId = 0;
            $Registro->ocupacionId = 0;

            $RegistroMySqlDAO = new RegistroMySqlDAO($Transaction);
            $RegistroMySqlDAO->insert($Registro);

            /* Instancia el objeto UsuarioOtrainfo y asigna valores a sus atributos */
            $UsuarioOtraInfo = new UsuarioOtrainfo();
            $UsuarioOtraInfo->usuarioId = $user_id;
            $UsuarioOtraInfo->fechaNacim = '';
            $UsuarioOtraInfo->mandante = $Mandante->mandante;
            $UsuarioOtraInfo->info2 = '';
            $UsuarioOtraInfo->bancoId = 0;
            $UsuarioOtraInfo->numCuenta = 0;
            $UsuarioOtraInfo->anexoDoc = 'N';
            $UsuarioOtraInfo->direccion = '';
            $UsuarioOtraInfo->tipoCuenta = 0;

            $UsuarioOtraInfoMySqlDAO = new UsuarioOtrainfoMySqlDAO($Transaction);
            $UsuarioOtraInfoMySqlDAO->insert($UsuarioOtraInfo);

            /*Instancia el objeto UsuarioPerfil y asigna valores a sus atributos */
            $UsuarioPerfil = new UsuarioPerfil();
            $UsuarioPerfil->usuarioId = $user_id;
            $UsuarioPerfil->mandante = $Mandante->mandante;
            $UsuarioPerfil->perfilId = 'USUONLINE';
            $UsuarioPerfil->pais = 'N';
            $UsuarioPerfil->global = 'N';

            $UsuarioPerfilMySqlDAO = new UsuarioPerfilMySqlDAO($Transaction);
            $UsuarioPerfilMySqlDAO->insert($UsuarioPerfil);

            /*Instancia el objeto UsuarioPremiomax y asigna valores a sus atributos*/
            $UsuarioPremioMax = new UsuarioPremiomax();
            $UsuarioPremioMax->usuarioId = $user_id;
            $UsuarioPremioMax->premioMax = 0;
            $UsuarioPremioMax->usumodifId = 0;
            $UsuarioPremioMax->cantLineas = 0;
            $UsuarioPremioMax->premioMax1 = 0;
            $UsuarioPremioMax->premioMax2 = 0;
            $UsuarioPremioMax->premioMax3 = 0;
            $UsuarioPremioMax->apuestaMin = 0;
            $UsuarioPremioMax->valorDirecto = 0;
            $UsuarioPremioMax->premioDirecto = 0;
            $UsuarioPremioMax->mandante = $Mandante->mandante;
            $UsuarioPremioMax->optimizarParrilla = 'N';
            $UsuarioPremioMax->valorEvento = 0;
            $UsuarioPremioMax->valorDiario = 0;

            $UsuarioPremioMaxMySqlDAO = new UsuarioPremiomaxMySqlDAO($Transaction);
            $UsuarioPremioMaxMySqlDAO->insert($UsuarioPremioMax);

            try {
                $Clasificador = new Clasificador('', 'LIMITEDEPOSITODIARIODEFT');
                $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $Clasificador->getClasificadorId(), $Pais->paisId, 'A');
                $limit_deposit_day = $MandanteDetalle->getValor();

                if(empty($limit_deposit_day)) throw new Exception('', 0);

                $UsuarioConfiguracion = new UsuarioConfiguracion();
                $UsuarioConfiguracion->usuarioId = $user_id;
                $UsuarioConfiguracion->tipo = $Clasificador->getClasificadorId();
                $UsuarioConfiguracion->valor = $limit_deposit_day ?: 0;
                $UsuarioConfiguracion->usucreaId = $user_id;
                $UsuarioConfiguracion->usumodifId = 0;
                $UsuarioConfiguracion->productoId = 0;
                $UsuarioConfiguracion->estado = 'A';
                $UsuarioConfiguracion->fechaModif = date('Y-m-d H:i:s');

                $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);
                $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);

                $UsuarioLog2 = new UsuarioLog2();
                $UsuarioLog2->setUsuarioId($user_id);
                $UsuarioLog2->setUsuarioIp($json->session->usuarioip);
                $UsuarioLog2->setUsuariosolicitaId($user_id);
                $UsuarioLog2->setUsuariosolicitaIp($json->session->usuarioip);
                $UsuarioLog2->setTipo('USUDEPOSITODIARIO');
                $UsuarioLog2->setEstado('P');
                $UsuarioLog2->setValorAntes('');
                $UsuarioLog2->setValorDespues($limit_deposit_day);
                $UsuarioLog2->setUsucreaId(0);
                $UsuarioLog2->setUsumodifId(0);

                $UsuarioLogMySqlDAO = new UsuarioLog2MySqlDAO($Transaction);
                $UsuarioLogMySqlDAO->insert($UsuarioLog2);
            } catch (Exception $ex) { }


            try {
                $Clasificador = new Clasificador('', 'LIMITEDEPOSITOSEMANADEFT');
                $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $Clasificador->clasificadorId, $Pais->paisId, 'A');
                $limit_deposit_week = $MandanteDetalle->getValor();

                if(empty($limit_deposit_week)) throw new Exception('', 0);

                $UsuarioConfiguracion = new UsuarioConfiguracion();
                $UsuarioConfiguracion->usuarioId = $user_id;
                $UsuarioConfiguracion->tipo = $Clasificador->getClasificadorId();
                $UsuarioConfiguracion->valor = $limit_deposit_week ?: 0;
                $UsuarioConfiguracion->usucreaId = $user_id;
                $UsuarioConfiguracion->usumodifId = 0;
                $UsuarioConfiguracion->productoId = 0;
                $UsuarioConfiguracion->estado = 'A';
                $UsuarioConfiguracion->fechaModif = date('Y-m-d H:i:s');

                $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);
                $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);

                $UsuarioLog2 = new UsuarioLog2();
                $UsuarioLog2->setUsuarioId($user_id);
                $UsuarioLog2->setUsuarioIp($json->session->usuarioip);
                $UsuarioLog2->setUsuariosolicitaId($user_id);
                $UsuarioLog2->setUsuariosolicitaIp($json->session->usuarioip);
                $UsuarioLog2->setTipo('USUDEPOSITOSEMANA');
                $UsuarioLog2->setEstado('P');
                $UsuarioLog2->setValorAntes('');
                $UsuarioLog2->setValorDespues($limit_deposit_week);
                $UsuarioLog2->setUsucreaId(0);
                $UsuarioLog2->setUsumodifId(0);

                $UsuarioLogMySqlDAO = new UsuarioLog2MySqlDAO($Transaction);
                $UsuarioLogMySqlDAO->insert($UsuarioLog2);
            } catch (Exception $ex) { }


        try {
                /**
                 * Se crea un objeto de tipo Clasificador para obtener el ID del clasificador
                 * correspondiente al límite de depósito mensual definido por el tipo 'LIMITEDEPOSITOMENSUALDEFT'.
                 */
                $Clasificador = new Clasificador('', 'LIMITEDEPOSITOMENSUALDEFT');
                $MandanteDetalle = new MandanteDetalle('', $Mandante->mandante, $Clasificador->getClasificadorId(), $Pais->paisId, 'A');
                $limit_deposit_month = $MandanteDetalle->getValor() ?: 0;

                // Verifica si el límite de depósito mensual está vacío y lanza una excepción en caso afirmativo.
                if(empty($limit_deposit_month)) throw new Exception('', 0);

                /**
                 * Se crea un objeto de tipo UsuarioConfiguracion para almacenar la configuración del usuario
                 * relacionada al límite de depósito mensual.
                 */
                $UsuarioConfiguracion = new UsuarioConfiguracion();
                $UsuarioConfiguracion->usuarioId = $user_id;
                $UsuarioConfiguracion->tipo = $Clasificador->getClasificadorId();
                $UsuarioConfiguracion->valor = $limit_deposit_month;
                $UsuarioConfiguracion->usucreaId = $user_id;
                $UsuarioConfiguracion->usumodifId = 0;
                $UsuarioConfiguracion->productoId = 0;
                $UsuarioConfiguracion->estado = 'A';
                $UsuarioConfiguracion->fechaModif = date('Y-m-d H:i:s');

                $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO($Transaction);
                $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);

                /**
                 * Se crea un objeto de tipo UsuarioLog2 para registrar la actividad del usuario
                 * respecto al límite de depósito mensual.
                 */
                $UsuarioLog2 = new UsuarioLog2();
                $UsuarioLog2->setUsuarioId($user_id);
                $UsuarioLog2->setUsuarioIp($json->session->usuarioip);
                $UsuarioLog2->setUsuariosolicitaId($user_id);
                $UsuarioLog2->setUsuariosolicitaIp($json->session->usuarioip);
                $UsuarioLog2->setTipo('USUDEPOSITOMES');
                $UsuarioLog2->setEstado('P');
                $UsuarioLog2->setValorAntes('');
                $UsuarioLog2->setValorDespues($limit_deposit_month);
                $UsuarioLog2->setUsucreaId(0);
                $UsuarioLog2->setUsumodifId(0);

                $UsuarioLogMySqlDAO = new UsuarioLog2MySqlDAO($Transaction);
                $UsuarioLogMySqlDAO->insert($UsuarioLog2);
            } catch (Exception $ex) { }
            // Se crea una nueva instancia de la clase UsuarioMandante
            $UsuarioMandante = new UsuarioMandante();
            $UsuarioMandante->mandante = $Mandante->mandante;
            /*Se asignan los valores del objeto*/
            $UsuarioMandante->nombres = $response['name'];
            $UsuarioMandante->apellidos = '';
            $UsuarioMandante->estado = 'A';
            $UsuarioMandante->email = $response['email'];
            $UsuarioMandante->moneda = $PaisMoneda->moneda;
            $UsuarioMandante->paisId = $Pais->paisId;
            $UsuarioMandante->saldo = 0;
            $UsuarioMandante->usuarioMandante = $user_id;
            $UsuarioMandante->usucreaId = 0;
            $UsuarioMandante->usumodifId = 0;
            $UsuarioMandante->propio = 'S';

            $UsuarioMandanteMySqlDAO = new UsuarioMandanteMySqlDAO($Transaction);
            $UsuarioMandanteMySqlDAO->insert($UsuarioMandante);

            try {

                // Verifica si la campaña UTM no está vacía y no es 'undefined_undefined'
                if ($json->params->vs_utm_campaign != '' && $json->params->vs_utm_campaign != 'undefined_undefined') {


                    try {

                        // Crea un objeto estándar para almacenar los parámetros de seguimiento
                        $objectST = new stdClass();
                        $objectST->vs_utm_campaign = $json->params->vs_utm_campaign;
                        $objectST->vs_utm_campaign2 = $json->params->vs_utm_campaign2;
                        $objectST->vs_utm_source = $json->params->vs_utm_source;
                        $objectST->vs_utm_content = $json->params->vs_utm_content;
                        $objectST->vs_utm_term = $json->params->vs_utm_term;
                        $objectST->vs_utm_medium = $json->params->vs_utm_medium;
                        $SitioTracking = new \Backend\dto\SitioTracking();

                        // Configura valores en el objeto SitioTracking
                        $SitioTracking->setTabla('registro'); // Establece la tabla como 'registro'
                        $SitioTracking->setTablaId($user_id); // Establece el ID de la tabla
                        $SitioTracking->setTipo('2'); // Establece el tipo
                        $SitioTracking->setTvalue(json_encode($objectST)); // Establece el valor como JSON codificado
                        $SitioTracking->valueInd = substr($objectST->vs_utm_campaign, 0, 49); // Indica el valor de la campaña
                        $SitioTracking->setUsucreaId('0'); // ID del usuario que crea
                        $SitioTracking->setUsumodifId('0'); // ID del usuario que modifica

                        // Crea una instancia del DAO para la base de datos
                        $SitioTrackingMySqlDAO = new \Backend\mysql\SitioTrackingMySqlDAO();
                        $SitioTrackingMySqlDAO->insert($SitioTracking);
                        $SitioTrackingMySqlDAO->getTransaction()->commit();
                    } catch (Exception $e) {
                        // Manejo de excepciones en la inserción
                    }

                }
            } catch (Exception $e) {
                // Manejo de excepciones en el bloque principal
            }


            try {


                    // Se inicia un bloque try principal para manejar excepciones.
                    try {

                    // Se crea un nuevo objeto stdClass.
                    $objectST = new stdClass();

                    // Se instancia un objeto de la clase SitioTracking y se asigna valor a sus parámetros.
                    $SitioTracking = new \Backend\dto\SitioTracking();

                        $SitioTracking->setTabla('registro_origen');
                        $SitioTracking->setTablaId($user_id);
                        $SitioTracking->setTipo('2');
                        $SitioTracking->setTvalue($type == 1 ? 'Google' : 'Facebook');
                        $SitioTracking->valueInd = $type == 1 ? 'Google' : 'Facebook';
                        $SitioTracking->setUsucreaId('0');
                        $SitioTracking->setUsumodifId('0');


                        $SitioTrackingMySqlDAO = new \Backend\mysql\SitioTrackingMySqlDAO();
                        $SitioTrackingMySqlDAO->insert($SitioTracking);
                        $SitioTrackingMySqlDAO->getTransaction()->commit();
                    } catch (Exception $e) {

                    }

            } catch (Exception $e) {

            }

            // Se confirma la transacción de la operación principal.
            $Transaction->commit();

            if($Usuario->mandante == 23){

                // Inicializa un arreglo con detalles específicos para el bono interno
                $detalles = array(
                    "Depositos" => 0, // Cantidad de depósitos
                    "DepositoEfectivo" => false, // Indica si es depósito en efectivo
                    "MetodoPago" => 0, // Método de pago utilizado
                    "ValorDeposito" => 0, // Valor del depósito
                    "PaisPV" => 0, // País del punto de venta
                    "DepartamentoPV" => 0, // Departamento del punto de venta
                    "CiudadPV" => 0, // Ciudad del punto de venta
                    "PuntoVenta" => 0, // Identificación del punto de venta
                    "PaisUSER" => $Usuario->paisId, // País del usuario
                    "DepartamentoUSER" => 0, // Departamento del usuario
                    "CiudadUSER" => $Registro->ciudadId, // Ciudad del usuario
                    "MonedaUSER" => $Usuario->moneda, // Moneda del usuario
                    "CodePromo" => '' // Código de promoción
                );

                // Convierte el arreglo en un objeto
                $detalles = json_decode(json_encode($detalles));

                $BonoInterno = new BonoInterno();
                $BonoInternoMySqlDAO = new BonoInternoMySqlDAO();
                $Transaction = $BonoInternoMySqlDAO->getTransaction();

                /*Realiza la entrega de un bono*/
                $responseBonus = $BonoInterno->agregarBonoFree(52228, $Usuario->usuarioId, $Usuario->mandante, $detalles, true, '', $Transaction);

                if ($responseBonus->WinBonus) {
                    $Transaction->commit();
                }
            }

            // Genera una nueva clave de acceso aleatoria, codificada en base64 y recortada a 12 caracteres
            $password = substr(base64_encode(bin2hex(openssl_random_pseudo_bytes(16))), 0, 12);
            $Usuario->changeClave($password);

            // Realiza el login del usuario y almacena los datos en un objeto
            $login_data = (object)$Usuario->login($response['email'], '', '', $Mandante->mandante, '', true);

            $new_user = true;
        }
    }

    // Verifica si los datos de inicio de sesión están vacíos y lanza una excepción si es así.
    if(($login_data == '')) throw new Exception('Error de autenticacion', 7000);

    $UsuarioMandante = new UsuarioMandante($login_data->user_id);

    try {
        // Crea un nuevo clasificador con un tipo específico 'EXCTOTAL'.
        $Clasificador = new Clasificador('', 'EXCTOTAL');
        $UsuarioConfiguracion = new UsuarioConfiguracion($UsuarioMandante->usuarioMandante, 'A', $Clasificador->getClasificadorId());

        // Verifica si el valor de la configuración del usuario es mayor que la fecha y hora actual,
        // en cuyo caso lanza una excepción.
        if($UsuarioConfiguracion->getValor() > date('Y-m-d H:i:s')) throw new Exception('User self-excluded total excluded for time', 20027);
    } catch (Exception $ex) { if($ex->getCode() == 20027) throw $ex; }

    try {
        // Comprueba el valor de mandante y establece la redirección y el origen en consecuencia.
        if($Mandante->mandante == 2) {
            $redirect = strpos($_SERVER['HTTP_REFERER'], 'acropolis') !== false ? '/new-casino?frm=lgn'  : '/home?frm=lgn';
            $origin = 2;
        } elseif($Mandante->mandante == 0 && $UsuarioMandante->paisId == 60) $redirect = '/home?frm=lgn';
    } catch (Exception $ex) { }

    /*$usersMenu = json_decode(
        '[{"MENU_ID":"3","MENU_TITLE":"Gesti\u00f3n","MENU_SLUG":"gestion","MENU_EDITAR":"true","MENU_ELIMINAR":"false","MENU_ADICIONAR":"true","SUBMENUS":[{"SUBMENU_ID":"136","SUBMENU_URL":"deposito","SUBMENU_TITLE":"Depositar"},{"SUBMENU_ID":"102","SUBMENU_URL":"cuenta_cobro_anular","SUBMENU_TITLE":"Anular Nota Retiro"},{"SUBMENU_ID":"189","SUBMENU_URL":"cuentasbancarias","SUBMENU_TITLE":"Cuentas bancarias"},{"SUBMENU_ID":"41","SUBMENU_URL":"cuenta_cobro","SUBMENU_TITLE":"Retirar"},{"SUBMENU_ID":"500","SUBMENU_URL":"verificar_cuenta","SUBMENU_TITLE":"Verificar Cuenta"},{"SUBMENU_ID":"121","SUBMENU_URL":"cambiar-clave","SUBMENU_TITLE":"Cambiar Contrase\u00f1a"},{"SUBMENU_ID":"195","SUBMENU_URL":"misbonos","SUBMENU_TITLE":"Mis Bonos"},{"SUBMENU_ID":"87","SUBMENU_URL":"gestion_cuenta","SUBMENU_TITLE":"Mi Cuenta"}]},{"MENU_ID":"5","MENU_TITLE":"Consultas","MENU_SLUG":"consulta","MENU_EDITAR":"false","MENU_ELIMINAR":"false","MENU_ADICIONAR":"true","SUBMENUS":[{"SUBMENU_ID":"100","SUBMENU_URL":"consulta_tickets_online","SUBMENU_TITLE":"Consulta de apuestas deportivas"},{"SUBMENU_ID":"184","SUBMENU_URL":"consulta_tickets_casino","SUBMENU_TITLE":"Informe de apuestas casino"},{"SUBMENU_ID":"186","SUBMENU_URL":"consulta_depositos","SUBMENU_TITLE":"Consultar depositos"},{"SUBMENU_ID":"188","SUBMENU_URL":"consulta_retiros","SUBMENU_TITLE":"Consultar retiros"}]}]'
    );*/

    $usersMenu=json_decode((
    '[
        {
            "MENU_ID": "3",
            "MENU_TITLE": "Gesti\u00f3n",
            "MENU_SLUG": "gestion",
            "MENU_EDITAR": "true",
            "MENU_ELIMINAR": "false",
            "MENU_ADICIONAR": "true",
            "SUBMENUS": [
                {
                    "SUBMENU_ID": "136",
                    "SUBMENU_URL": "deposito",
                    "SUBMENU_TITLE": "Depositar"
                },
                {
                    "SUBMENU_ID": "102",
                    "SUBMENU_URL": "cuenta_cobro_anular",
                    "SUBMENU_TITLE": "Anular Nota Retiro"
                },
                {
                    "SUBMENU_ID": "189",
                    "SUBMENU_URL": "cuentasbancarias",
                    "SUBMENU_TITLE": "Cuentas bancarias"
                },
                {
                    "SUBMENU_ID": "41",
                    "SUBMENU_URL": "cuenta_cobro",
                    "SUBMENU_TITLE": "Retirar"
                },
                {
                    "SUBMENU_ID": "500",
                    "SUBMENU_URL": "verificar_cuenta",
                    "SUBMENU_TITLE": "Verificar Cuenta"
                },
                {
                    "SUBMENU_ID": "121",
                    "SUBMENU_URL": "cambiar-clave",
                    "SUBMENU_TITLE": "Cambiar Contrase\u00f1a"
                },
                {
                    "SUBMENU_ID": "195",
                    "SUBMENU_URL": "misbonos",
                    "SUBMENU_TITLE": "Mis Bonos"
                },
                {
                    "SUBMENU_ID": "87",
                    "SUBMENU_URL": "gestion_cuenta",
                    "SUBMENU_TITLE": "Mi Cuenta"
                },
                {
                    "SUBMENU_ID": "88",
                    "SUBMENU_URL": "autoexclusion",
                    "SUBMENU_TITLE": "Autoexclusion Parcial"
                },
                {
                    "SUBMENU_ID": "89",
                    "SUBMENU_URL": "autoexclusion-producto",
                    "SUBMENU_TITLE": "Autoexclusion por vertical"
                },
                {
                    "SUBMENU_ID": "90",
                    "SUBMENU_URL": "limitedeposito",
                    "SUBMENU_TITLE": "Limites de deposito   "
                }
            ]
        },
        {
            "MENU_ID": "5",
            "MENU_TITLE": "Consultas",
            "MENU_SLUG": "consulta",
            "MENU_EDITAR": "false",
            "MENU_ELIMINAR": "false",
            "MENU_ADICIONAR": "true",
            "SUBMENUS": [
                {
                    "SUBMENU_ID": "100",
                    "SUBMENU_URL": "consulta_tickets_online",
                    "SUBMENU_TITLE": "Consulta de apuestas deportivas"
                },
                {
                    "SUBMENU_ID": "184",
                    "SUBMENU_URL": "consulta_tickets_casino",
                    "SUBMENU_TITLE": "Informe de apuestas casino"
                },
                {
                    "SUBMENU_ID": "186",
                    "SUBMENU_URL": "consulta_depositos",
                    "SUBMENU_TITLE": "Consultar depositos"
                },
                {
                    "SUBMENU_ID": "188",
                    "SUBMENU_URL": "consulta_retiros",
                    "SUBMENU_TITLE": "Consultar retiros"
                }
            ]
        }
    ]'
    ));


    $redirectUrl='/deportes?frm=lgn';


    /*El código verifica el valor de mandante del objeto UsuarioMandante y establece la URL de redirección (redirectUrl) en
    función de ciertas condiciones.*/
    try{
        //Define la redirección que implementará el sitio
        $UsuarioMandante = new UsuarioMandante($login_data->user_id);
        if($UsuarioMandante->mandante==2){
            $redirectUrl='/home?frm=lgn';
            if (strpos($_SERVER['HTTP_REFERER'], "acropolis") !== FALSE) {
                $redirectUrl='/new-casino?frm=lgn';
            }

        }

        if($UsuarioMandante->mandante==0 && $UsuarioMandante->paisId == 60){
            $redirectUrl='/home?frm=lgn';
        }

        /*Realiza una notificación mediante WebSocket*/
        if($UsuarioMandante->mandante==13 ){
            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

            if($Usuario->fechaUlt >=  date('Y-m-d H:i:s', strtotime('-8 seconds'))){
                $redirectUrl='/gestion/deposito?frm=lgn';

                $dataSend = array(
                    "redirectUrl" => $redirectUrl
                );
                $WebsocketUsuario = new WebsocketUsuario('', '');
                $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend);

            }
        }

        if((in_array($UsuarioMandante->mandante,array('0','6','8','2','12',3,4,5,6,7)) || true)  && !in_array($UsuarioMandante->usuarioMandante,array(17884 ,242068 ,255499, 255528, 255547 ,255584 )) && false) {

            $dataSend = array(
                "logout" => true
            );
            $WebsocketUsuario = new WebsocketUsuario('', '');
            $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend);
        }

    }catch (Exception $e){

    }

    /*Genera el formato de respuesta*/
    $response = [];
    $response['code'] = 0;
    $response['message'] = 'success';
    $response['data'] = [
        'auth_token' => $login_data->auth_token,
        'user_id' => $login_data->user_id,
        'id_platform' => $login_data->user_id2,
        'channel_id' => $login_data->user_id,
        'tokenSB' => $login_data->token_itn,
        'user_menu' => $usersMenu,
        'redirectUrl' => $redirect,
        'in_app' => $in_app == true ? 1 : 0
    ];

    $response['rid'] = $json->rid;

    if($new_user === true) {
        try {
            /*Verificación y envío del proceso de notificación*/
            $Clasificador = new clasificador('', 'TEMPSESIEXTE');
            $Template = new Template('', $Mandante->mandante, $Clasificador->getClasificadorId(), $Pais->paisId, strtolower($Pais->idioma));

            $html = $Template->templateHtml;
        } catch (Exception $e) { }

        /*Sustitución de etiquetas*/
        switch($Usuario->idioma) {
            case 'EN':
                $title = 'Login in' . $Mandante->nombre;
                $subject = 'Login in ' . $Mandante->nombre;
                break;
            case 'PT':
                $title = 'Faça o login em ' . $Mandante->nombre;
                $subject = 'Faça o login em ' . $Mandante->nombre;
                break;
            default:
                $title = 'Inicio de sesion en ' . $Mandante->nombre;
                $subject = 'Inicio de sesion en ' . $Mandante->nombre;
                break;
        }

        $html = str_replace('#Type#', $type == 1 ? 'Google' : 'Facebook', $html);
        $html = str_replace('#Password#', $password, $html);

        /*Envío de correos*/
        $envio = $ConfigurationEnvironment->EnviarCorreoVersion2($Usuario->login, '', '', $subject, '', $title, $html, '', '', '', $Mandante->mandante);
    }

} catch (Exception $ex) {
    $response = [];
    $response['code'] = $ex->getCode() != 100001 ? 10000 : $ex->getCode();
    $response['message'] = $ex->getCode() != 100001 ? 'auth error' : $ex->getMessage();
    $response['data'] = [];
    $response['rid'] = $json->rid;
}
?>