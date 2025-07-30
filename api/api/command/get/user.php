<?php

use Backend\dto\Pais;
use Backend\dto\Moneda;
use Backend\dto\Usuario;
use Backend\dto\Mandante;
use Backend\dto\Registro;
use Backend\dto\Proveedor;
use Backend\dto\PuntoVenta;
use Backend\dto\BonoDetalle;
use Backend\dto\UsuarioBono;
use Backend\dto\UsuarioLog2;
use Backend\dto\Clasificador;
use Backend\dto\Subproveedor;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioPerfil;
use Backend\dto\UsuarioMensaje;
use Backend\dto\UsuarioSession;
use Backend\dto\MandanteDetalle;
use Backend\dto\UsuarioOtrainfo;
use Backend\dto\DocumentoUsuario;
use Backend\dto\UsuarioBilletera;
use Backend\sql\ConnectionProperty;
use Backend\dto\UsuarioVerificacion;
use Backend\dto\UsuarioConfiguracion;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\dto\UsuarioMensajecampana;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\SubproveedorMandantePais;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\UsuarioConfiguracionMySqlDAO;
use Backend\mysql\UsuarioMensajecampanaMySqlDAO;


/**
 * Este script maneja la validación y gestión de usuarios en línea, incluyendo operaciones como retiros,
 * depósitos, configuración de perfiles, y generación de menús personalizados según el mandante y país.
 *
 * @param object $params Objeto que contiene los parámetros de entrada, incluyendo:
 * @param object $params-json Objeto JSON que contiene la información de la sesión del usuario y otros datos relevantes.
 * @param object $params->session Información de la sesión del usuario, como el estado de logueo y el ID de sesión.
 * @param object $params->session->logueado Indica si el usuario está logueado o no.
 *
 * @return array $response Respuesta generada por el script, que incluye:
 *  - code: Código de estado de la operación.
 *  - data: Información del perfil del usuario, configuración, y menús personalizados.
 *  - user_menus: Menús y submenús disponibles para el usuario según su perfil.
 *  - subid: Identificador único generado para la sesión del usuario.
 *
 * @throws Exception Si los datos proporcionados son inválidos o si ocurre un error en la ejecución.
 */


/**
 * Valida si un usuario en línea puede realizar un retiro.
 *
 * @param string $usuarioId ID del usuario.
 * @param string $mandante Identificador del mandante.
 * @param string $paisId ID del país.
 * @return bool Retorna `true` si el usuario está bloqueado para realizar retiros, `false` en caso contrario.
 * @throws Exception Si los datos proporcionados son inválidos.
 */
function validateUsuOnlineWithdraw($usuarioId = '', $mandante = '', $paisId = '')
{
    if (!empty($usuarioId) && $mandante != '' && !empty($paisId)) {
        $isBlocked = true;
        $diligenciado = '';

        try {
            // Consulta SQL para obtener información sobre cuentas de cobro y formularios genéricos.
            $sql =
                "
SELECT COUNT(((cuenta_cobro.cuenta_id)))              countCuentaCobro,
       SUM(cuenta_cobro.valor) valorTotal,
       COUNT((formularios_genericos.formgenerico_id)) countFormulariosGenerico
FROM cuenta_cobro
         LEFT OUTER JOIN formularios_genericos
                         ON cuenta_cobro.usuario_id = formularios_genericos.usuario_id AND
                            formularios_genericos.tipo = 'SPLAFT' AND formularios_genericos.anio = YEAR(CURDATE())
WHERE YEAR(cuenta_cobro.fecha_pago) = YEAR(CURDATE())
  and cuenta_cobro.estado = 'I'  AND formularios_genericos.formgenerico_id is null
  AND cuenta_cobro.usuario_id = {$usuarioId}";

            // Ejecuta la consulta y procesa los resultados.
            $BonoInterno = new \Backend\dto\BonoInterno();
            $sqlRS = $BonoInterno->execQuery("", $sql);

            $value = $sqlRS[0];
            $totalSaldo = $value->{'.valorTotal'};
            $countFormulariosGenerico = $value->{'.countFormulariosGenerico'};

            // Verifica si el usuario ha diligenciado más de un formulario genérico.
            if(intval($countFormulariosGenerico)>=1){
                $diligenciado = 'S';
            }

            // Determina si el usuario está bloqueado o no.
            if (!empty($diligenciado) && $diligenciado === 'S') {
                $isBlocked = false;
            } else {
                $Clasificador = new Clasificador('', 'LIMSALDFORMSPLAFT');
                $MandanteDetalle = new MandanteDetalle('', $mandante, $Clasificador->clasificadorId, $paisId, 'A');
                if ($totalSaldo < $MandanteDetalle->valor) $isBlocked = false;
            }
        } catch (Exception $e) {
            // En caso de error, desbloquea al usuario por defecto.
            $isBlocked = false;
        }

        return $isBlocked;
    } else {
        // Lanza una excepción si los datos proporcionados son inválidos.
        throw new Exception('Error al validar los datos', 01);
    }
}

$start = microtime(true);   // marca el inicio de la ejecución
if ($json->session->logueado) {
    $timeInit = time();


    try {

        //syslog(LOG_WARNING, "1 STARTRIDABK  :" . $json->rid . '_' . $UsuarioMandanteSite->usumandanteId . ' ' . (microtime(true) - $start));
    } catch (Exception $e) {

    }


    /* Se crean instancias de clases para manejar configuraciones y usuarios en un sistema. */
    $ConfigurationEnvironment = new ConfigurationEnvironment();

    $UsuarioMandante = $UsuarioMandanteSite;
    $Mandante = new Mandante($UsuarioMandante->getMandante());
    $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
    $Registro = new Registro("", $UsuarioMandante->getUsuarioMandante());

    /* Se crean objetos de usuario y se generan tokens de autenticación en un bloque try. */
    $UsuarioPerfil = new UsuarioPerfil($UsuarioMandante->usuarioMandante);
    $UsuarioOtraInfo = new UsuarioOtrainfo($UsuarioMandante->getUsuarioMandante());

    // Fecha ultima sesion
    $sql2 = // solicitud de informacion desde tabla auxiliarr
        "SELECT data_completa2.ultimo_inicio_sesion
                FROM data_completa2
                    inner join usuario_mandante on (usuario_mandante.usumandante_id = data_completa2.usuario_id)
                    WHERE usuario_mandante.usuario_mandante = {$Usuario->usuarioId}";
    $BonoInterno = new \Backend\dto\BonoInterno(); //instanciar clase bonointerno
    $sqlRS = $BonoInterno->execQuery("", $sql2); //tomar el resultado de $sql2, ejecutar y guardar en una nueva variable $sqlRS
    //  print_r($sqlRS);

    $value2=$sqlRS[0];
    $completaFecha=$value2->{'data_completa2.ultimo_inicio_sesion'}; // obtencion del ultimo inicio de sesion
/*
 * se evalua $completFecha que no sea null, undefined y vacio, dependiendo se refleja la fecha actual, sino la consultada.
 */
    if(!isset($completaFecha) || empty($completaFecha) || is_null($completaFecha) ){
    $ultimaFechaInicio = date('Y-m-d H:i:s');
    }else{
        $ultimaFechaInicio = $completaFecha;
    }

    try {
        $UsuarioToken1 = new UsuarioToken("", '1', $UsuarioMandante->getUsumandanteId());
        $token = $UsuarioToken1->getToken();

        $UsuarioToken0 = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());
        $tokenM = $UsuarioToken0->getToken();

    } catch (Exception $e) {
        /* Captura excepciones en PHP para manejar errores sin interrumpir la ejecución. */


    }


    /* obtiene diferentes saldos del usuario y establece valores a cero. */
    $saldo = $UsuarioMandante->getSaldo();
    $saldoRecargas = $UsuarioMandante->getSaldo();
    $saldoRetiros = $UsuarioMandante->getSaldo();
    $saldoBonos = $UsuarioMandante->getSaldo();
    $saldoFreebet = 0;
    $bonoPendientePorRollover = 0;


    /* obtiene información de un usuario y concatena su nombre completo. */
    $moneda = $UsuarioMandante->getMoneda();
    $paisId = $UsuarioMandante->getPaisId();
    $usuario_id = $UsuarioMandante->getUsumandanteId();

    $nombres = $UsuarioMandante->getNombres() . " " . $UsuarioMandante->getApellidos();
    $nombres = $Usuario->nombre;

    /* Asignación de variables a partir de propiedades de objetos Usuario y Registro. */
    $moneda = $Usuario->moneda;

    $primer_nombre = $Registro->nombre1;
    $segundo_nombre = $Registro->nombre2;
    $primer_apellido = $Registro->apellido1;
    $segundo_apellido = $Registro->apellido2;

    /* Se asignan valores de un objeto "Registro" a variables específicas en PHP. */
    $nombre = $Registro->nombre;
    $celular = $Registro->celular;
    $email = $Registro->email;
    $doc_type = $Registro->tipoDoc;
    $gender = $Registro->sexo;
    $identification = $Registro->cedula;

    /* Se crean variables relacionadas con la información del usuario y su país. */
    $Pais = new Pais($Usuario->paisId);
    $birthdate = $UsuarioOtraInfo->getFechaNacim();

    $fecha_ultima = $ultimaFechaInicio;
    $ip_ultima = $Usuario->dirIp;
    $idioma = $Usuario->idioma;

    /* Verifica si el celular del usuario está activo, y obtiene el nombre de su país. */
    $verifcelular = $Usuario->verifCelular === 'S' ? true : false;

    $activateCountMsg = true;
    $hour = date('H');


    try {
        $PaisNacionalidad = new Pais($Registro->nacionalidadId);
        $nationalityName = $PaisNacionalidad->paisNom;
    } catch (Exception $e) {
        /* captura excepciones para manejar errores sin interrumpir la ejecución del programa. */


    }


    /* Asignación de variables con información sobre país y nacionalidad del registro. */
    $countryName = $Pais->paisNom;
    $countryId = $Pais->paisId;
    $countryNatId = $Registro->nacionalidadId;

    $utc = $Pais->utc;
    $req_cheque = $Pais->reqCheque;

    /* asigna valores a variables desde objetos de países y usuarios. */
    $req_doc = $Pais->reqDoc;

    $fecha_crea = $Usuario->fechaCrea;
    $origen = $Usuario->origen;
    $fecha_actualizacion = $Usuario->fechaActualizacion;


    $usuario_id = $UsuarioMandante->usumandanteId;

    /* Código para obtener el token FCM utilizando la sesión de un usuario mandante. */
    $usuario_idPlatform = $UsuarioMandante->usuarioMandante;

    $mandante = $UsuarioMandante->mandante;

    $tokenFCM = '';

    try {
        $UsuarioSession = new UsuarioSession('3', '', 'A', '', $UsuarioMandante->usumandanteId);
        $tokenFCM = $UsuarioSession->requestId;
    } catch (Exception $ex) {
        /* Bloque que captura excepciones, manejando errores en el código sin interrumpir la ejecución. */

    }


    /* Variable inicialización para saldo y puntos de lealtad del usuario en un casino. */
    $saldoFreecasino = 0;

    $dniFrontBack = 0;
    $dniFront = 0;


    //$wallet = $Usuario->billeteraId;
    $puntosLealtad = $Usuario->puntosLealtad;
    $puntosAExpirar = $Usuario->puntosAexpirar;

    /* asigna valores a la variable $wallet según condiciones del usuario. */
    $nivelLealtad = $Usuario->nivelLealtad;

    if ($Usuario->mandante != '2') {
        $wallet = '0';
    } else {
        $wallet = $UsuarioTokenSite->cookie;
        if ($wallet != '0' && $wallet != '1') {
            $wallet = '';
        }
        if ($wallet == '1') {
            try {
                $UsuarioBilletera = new UsuarioBilletera('', $UsuarioMandanteSite->usuarioMandante, '1');
            } catch (Exception $e) {
                $wallet = '';
            }
        }
    }



    /* Registra mensajes de advertencia en el sistema, incluyendo información de tiempo y usuario. */
    try {
        //syslog(LOG_WARNING, "3 STARTRIDABK  :" . $json->rid . '_' . $UsuarioMandanteSite->usumandanteId . ' ' . (microtime(true) - $start));
    } catch (Exception $e) {

    }


    try {

        //syslog(LOG_WARNING, "M2 STARTRIDABK  :" . $json->rid . '_' . $UsuarioMandanteSite->usumandanteId . ' ' . (microtime(true) - $start));
    } catch (Exception $e) {
        /* Captura excepciones en PHP, permitiendo manejar errores sin interrumpir la ejecución. */


    }


    /* Crea un objeto Proveedor y un UsuarioToken usando su ID. */
    $Proveedor = new Proveedor('', 'ITN');
    try {
        $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $UsuarioMandante->usumandanteId);
    } catch (Exception $e) {

        if ($e->getCode() == 21) {


            /* Inicializa un objeto UsuarioToken y establece varios parámetros relacionados. */
            $UsuarioToken = new UsuarioToken();
            $UsuarioToken->setProveedorId($Proveedor->getProveedorId());
            $UsuarioToken->setCookie('0');
            $UsuarioToken->setRequestId('0');
            $UsuarioToken->setUsucreaId(0);
            $UsuarioToken->setUsumodifId(0);

            /* Se establece un token para el usuario con saldo inicial de cero. */
            $UsuarioToken->setUsuarioId($UsuarioMandante->usumandanteId);
            $token = $UsuarioToken->createToken();
            $UsuarioToken->setToken($token);
            $UsuarioToken->setSaldo(0);

            $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();


            /* Inserta un token de usuario y obtiene la transacción actual en MySQL. */
            $UsuarioTokenMySqlDAO->insert($UsuarioToken);

            $UsuarioTokenMySqlDAO->getTransaction()->commit();


        } else {
            /* lanza una excepción si no se cumple una condición previa. */

            throw $e;
        }
    }

    /* Se obtiene un token de usuario y se inicializan variables para bloqueo de usuarios. */
    $tokenSB = $UsuarioToken->getToken();


    $blocked_user = false;
    $blocked_user2 = false;
    $blocked_user3 = false;


    /* Verifica condiciones de usuario antes de realizar validación y registra en el log. */
    if (($Usuario->mandante == 0 && $Usuario->paisId == 173) || ($Usuario->mandante == 19)) {
        if (($Usuario->mandante == 0 && $Usuario->paisId == 173) || ($Usuario->mandante == 19)) {
            $blocked_user = validateUsuOnlineWithdraw($Usuario->usuarioId, $Usuario->mandante, $Usuario->paisId, $UsuarioMandante);
        }
    }

    try {
        //syslog(LOG_WARNING, "4 STARTRIDABK  :" . $json->rid . '_' . $UsuarioMandanteSite->usumandanteId . ' ' . (microtime(true) - $start));
    } catch (Exception $e) {
        /* Bloque que captura excepciones en PHP sin realizar ninguna acción específica. */


    }


    /* verifica si un usuario está bloqueado según su configuración. */
    try {
        $Clasificador = new Clasificador("", "PEP");

        $UsuarioConfiguracion = new UsuarioConfiguracion($Usuario->usuarioId, "A", $Clasificador->getClasificadorId());
        if ($UsuarioConfiguracion->getValor() == 'S') {
            $blocked_user3 = true;
        }
    } catch (Exception $e) {
        /* Captura excepciones en PHP, permitiendo manejar errores sin interrumpir la ejecución. */

    }


    /*Obtención tema de interfaz preferido del usuario*/
    $theme_color_mode = 2;
    try {
        $Clasificador = new Clasificador("", "FAVORITEINTERFACETHEME");
        $UsuarioConfiguracion = new UsuarioConfiguracion($Usuario->usuarioId, "A", $Clasificador->getClasificadorId());
        $theme_color_mode = match (strtolower($UsuarioConfiguracion->getValor())) {
            'claro' => 1,
            default => 2
        };
    } catch (Exception $e) {

    }


    /* Contador de mensajes no leídos para un usuario específico, manejando posibles excepciones. */
    $mensajes_no_leidos = 0;

    if ($activateCountMsg) {
        try {
            $mensajes_no_leidos = (new UsuarioMensaje())->getUsuarioMensajesCountNoRead($UsuarioMandante->usuarioMandante, $Usuario->paisId, $Usuario->fechaCrea, $Usuario->mandante, $UsuarioMandanteSite->usumandanteId);

        } catch (Exception $e) {

        }
    }


    /* registra un mensaje de advertencia en un sistema de logs. */
    try {
        //syslog(LOG_WARNING, "5 STARTRIDABK  :" . $json->rid . '_' . $UsuarioMandanteSite->usumandanteId . ' ' . (microtime(true) - $start));
    } catch (Exception $e) {

    }

    if ($Usuario->verificado != 'S') {


        /* configura una consulta para obtener un registro específico de usuario. */
        $Order = "desc";
        $Maxrows = 1;
        $SkeepRows = 0;

        $rules = [];

        array_push($rules, array("field" => "usuario_verificacion.usuario_id", "data" => $Usuario->usuarioId, "op" => "eq"));

        /* Agrega una regla y filtra datos en formato JSON para la clase UsuarioVerificacion. */
        array_push($rules, array("field" => "usuario_verificacion.tipo", "data" => "USUVERIFICACION", "op" => "eq"));

        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);

        $UsuarioVerificacion = new UsuarioVerificacion();

        /* extrae datos de usuario y los convierte en un formato estructurado. */
        $Datos = $UsuarioVerificacion->getUsuarioVerificacionCustom("usuario_verificacion.*", "usuario_verificacion.usuverificacion_id", $Order, $SkeepRows, $Maxrows, $json, true);
        $Datos = json_decode($Datos);


        $final = [];
        foreach ($Datos->data as $key => $value) {
            $array = [];

            $array["estado"] = $value->{"usuario_verificacion.estado"};
            $array["Motivo"] = $value->{"usuario_verificacion.observacion"};

            array_push($final, $array);
        }


        /* Asigna el valor del estado del primer elemento del array $final a $Estado. */
        $Estado = $final[0]['estado'];
    } else {
        /* Asigna el valor 'A' a la variable $Estado si no se cumple la condición anterior. */

        $Estado = 'A';
    }


    /* determina el valor de `$dniFront` y `$dniFrontBack` según condiciones del usuario. */
    if ($Usuario->verifcedulaAnt == "S") {
        $dniFront = 3;
    } else if ($Usuario->verifcedulaAnt != "S" and $Estado == "P" || $Estado == "I" and $Estado != "R" and $Estado != "A") {
        $dniFront = 2;
    }

    if ($Usuario->verifcedulaPost == "S") {
        $dniFrontBack = 3;
    } else if ($Usuario->verifcedulaPost != "S" and $Estado == "P" || $Estado == "I" and $Estado != "R" and $Estado != "A") {
        /* Condición para establecer el valor de `$dniFrontBack` basado en estados y verificación. */

        $dniFrontBack = 2;
    }


    /* verifica el estado del jugador y ajusta variables relacionadas con DNI. */
    if (($dniFront == 0 || $dniFrontBack == 0)) {

        if ($Usuario->estadoJugador == 'NN') {

        }
        if (substr($Usuario->estadoJugador, 0, 1) == 'P') {
            $dniFront = 2;
        }

        if (substr($Usuario->estadoJugador, 1, 1) == 'P') {
            $dniFrontBack = 2;
        }
    }


    /* registra una advertencia en el sistema de registro, manejando posibles excepciones. */
    try {
        //syslog(LOG_WARNING, "6 STARTRIDABK  :" . $json->rid . '_' . $UsuarioMandanteSite->usumandanteId . ' ' . (microtime(true) - $start));
    } catch (Exception $e) {

    }

    switch ($UsuarioPerfil->getPerfilId()) {
        case "USUONLINE":


            /* Calcula y redondea el saldo total, incluyendo recargas, retiros y bonos. */
            $saldo = round((floatval(($Registro->getCreditosBase() * 100)) + floatval(($Registro->getCreditos() * 100))) / 100, 2);
            $saldoRecargas = $Registro->getCreditosBase();
            $saldoRetiros = $Registro->getCreditos();
            $saldoBonos = $Registro->getCreditosBono();

            if (true) {
                if ($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null) {

                    /* Conecta a una base de datos MySQL usando PDO, solo en entornos de producción. */
                    $connOriginal = $_ENV["connectionGlobal"]->getConnection();
                    $connDB5 = null;
                    if ($_ENV['ENV_TYPE'] == 'prod') {
                        $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()
                            , array(
                                PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/ca-bundle.crt',
                                PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
                            )
                        );
                    } else {
                        /* Conecta a una base de datos usando parámetros de entorno y propiedades de conexión. */

                        $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()

                        );
                    }

                    /* configura la conexión a la base de datos según variables de entorno. */
                    $connDB5->exec("set names utf8");

                    try {

                        if ($_ENV["TIMEZONE"] != null && $_ENV["TIMEZONE"] != '') {
                            $connDB5->exec('SET time_zone = "' . $_ENV["TIMEZONE"] . '";');
                        }

                        if ($_ENV["NEEDINSOLATIONLEVEL"] != null && $_ENV["NEEDINSOLATIONLEVEL"] == '1') {
                            $connDB5->exec('SET TRANSACTION ISOLATION LEVEL READ COMMITTED;');
                        }
                        if ($_ENV["ENABLEDSETLOCKWAITTIMEOUT"] != null && $_ENV["ENABLEDSETLOCKWAITTIMEOUT"] == '1') {
                            // $connDB5->exec('SET SESSION innodb_lock_wait_timeout = 5;');
                        }
                        if ($_ENV["ENABLEDSETMAX_EXECUTION_TIME"] != null && $_ENV["ENABLEDSETMAX_EXECUTION_TIME"] == '1') {
                            // $connDB5->exec('SET SESSION MAX_EXECUTION_TIME = 120000;');
                        }
                        if ($_ENV["DBNEEDUTF8"] != null && $_ENV["DBNEEDUTF8"] == '1') {
                            $connDB5->exec("SET NAMES utf8mb4");
                        }
                    } catch (\Exception $e) {
                        /* Captura excepciones en PHP sin realizar ninguna acción dentro del bloque. */

                    }

                    /* Establece una conexión a la base de datos usando una instancia de conexión global. */
                    $_ENV["connectionGlobal"]->setConnection($connDB5);
                }

                $apmin2Sql = "
            SELECT *
            FROM (SELECT /*+ MAX_EXECUTION_TIME(1000) */
                  CASE
                      WHEN usuario_bono.rollower_requerido > 0 THEN SUM(usuario_bono.valor)
                      ELSE 0 END      bonoPendientePorRollover,
                  SUM(CASE
                          WHEN bono_interno.tipo = 6 THEN CASE
                                                              WHEN usuario_bono.apostado != '' AND usuario_bono.apostado != '0'
                                                                  THEN usuario_bono.apostado
                                                              ELSE bd2.valor END
                          ELSE 0 END) saldoFreebet,
                  SUM(CASE
                          WHEN bono_interno.tipo = 5 THEN (usuario_bono.valor_base - usuario_bono.valor)
                          ELSE 0 END) saldoFreeCasino
        
        
              FROM usuario_bono
                       INNER JOIN usuario ON (usuario.usuario_id = usuario_bono.usuario_id)
                       INNER JOIN bono_interno ON (bono_interno.bono_id = usuario_bono.bono_id)
                       INNER JOIN bono_detalle bd
                                  ON (bono_interno.bono_id = bd.bono_id AND (bd.tipo = 'EXPDIA' OR bd.tipo = 'EXPFECHA'))
                       INNER JOIN bono_detalle bd2 ON (bono_interno.bono_id = bd2.bono_id AND (bd2.tipo = 'MINAMOUNT'))
        
        
              WHERE usuario_bono.estado = 'A'
                AND (usuario_bono.fecha_expiracion > NOW())
        
                AND usuario_bono.usuario_id = {$Usuario->usuarioId}
              ) xx;";


                /* crea un objeto y ejecuta una consulta estableciendo una conexión global. */
                $BonoInterno = new \Backend\dto\BonoInterno();
                $apmin2_RS = $BonoInterno->execQuery("", $apmin2Sql);

                if ($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null) {
                    $connDB5 = null;
                    $_ENV["connectionGlobal"]->setConnection($connOriginal);
                }

                /* Asignación de valores de saldo y bono de un objeto PHP. */
                $value=$apmin2_RS[0];
                $saldoFreebet = $value->{'.saldoFreebet'};
                $saldoFreecasino = $value->{'.saldoFreeCasino'};
                $bonoPendientePorRollover = $value->{'.bonoPendientePorRollover'};


            }

            break;

        case "MAQUINAANONIMA":
            /* obtiene saldos de usuario y registros relacionados con recargas y bonos. */



            $saldo = $Usuario->getBalance();
            $saldoRecargas = $Registro->getCreditosBase();
            $saldoRetiros = $Registro->getCreditos();
            $saldoBonos = $Registro->getCreditosBono();


            break;

        case "PUNTOVENTA":



            /* Se calcula el saldo total sumando recargas y créditos base en PuntoVenta. */
            $PuntoVenta = new PuntoVenta("", $UsuarioMandante->usuarioMandante);

            $SaldoRecargas = $PuntoVenta->getCupoRecarga();
            $SaldoJuego = $PuntoVenta->getCreditosBase();

            $saldo = $SaldoJuego + $SaldoRecargas;

            /* Asignación de variables para saldo y nombres de usuario en un sistema de gestión. */
            $saldoRecargas = $SaldoRecargas;
            $saldoRetiros = $SaldoJuego;
            $saldoBonos = $SaldoJuego;

            $primer_nombre = $Usuario->nombre;
            $segundo_nombre = '';

            /* Declaración de variables vacías para almacenar apellidos y número de celular. */
            $primer_apellido = '';
            $segundo_apellido = '';
            $celular = '';


            break;


        case "CAJERO":



            /* crea un objeto "PuntoVenta" y calcula el saldo total disponible. */
            $PuntoVenta = new PuntoVenta("", $Usuario->puntoventaId);

            $SaldoRecargas = $PuntoVenta->getCupoRecarga();
            $SaldoJuego = $PuntoVenta->getCreditosBase();

            $saldo = $SaldoJuego + $SaldoRecargas;

            /* Variables que almacenan saldos y nombres del usuario en un sistema. */
            $saldoRecargas = $SaldoRecargas;
            $saldoRetiros = $SaldoJuego;
            $saldoBonos = $SaldoJuego;

            $primer_nombre = $Usuario->nombre;
            $segundo_nombre = '';

            /* Se declaran variables para almacenar el primer apellido, segundo apellido y número de celular. */
            $primer_apellido = '';
            $segundo_apellido = '';
            $celular = '';


            break;
    }







    /* Se define una respuesta con un array de configuraciones de límites y tasas financieras. */
    $response = array();

    $response['code'] = 0;

    $data = array();

    $clasificadorArray = [
        "MINWITHDRAW", "MAXWITHDRAW", "MINWITHDRAWBETSHOP", "MINWITHDRAWACCBANK", "MAXWITHDRAWBETSHOP", "MAXWITHDRAWDAYKASNET",
        "MINWITHDRAWDAYKASNET", "DAYSEXPIREPASSWORD", "DAYSNOTIFYPASSEXPIRE", "MINLENPASSWORD", "TAXWITHDRAWDEPOSIT", "TAXWITHDRAWDEPOSITFROM",
        "TAXWITHDRAWAWARD", "TAXWITHDRAWAWARDFROM", 'SPORTUSUONLINE', 'MINDEPOSIT', 'DEFAULTAMOUNTPAYMENTGATEWAYS'
    ];


    /* Se inicializan arreglos y objetos para filtrar y obtener datos de clasificadores y mandantes. */
    $filterDetalle = array();


    $clasificador = new Clasificador("", $clasificadorArray);
    $clasificadores = $clasificador->getAllArray();

    $MandanteDetalle = new MandanteDetalle('', $UsuarioMandante->mandante, '', $Usuario->paisId, $Usuario->estado);

    /* Filtra detalles de mandante y obtiene el valor mínimo de retiro. */
    $MandanteDetalles = $MandanteDetalle->getAllArray();

    foreach ($clasificadores as $clasificadorObject) {
        $filterDetalle[$clasificadorObject->getAbreviado()] = $MandanteDetalles[$clasificadorObject->getClasificadorId()]->valor;
    }


    $minimoRetiro = $filterDetalle["MINWITHDRAW"];



    /* asigna valores de un array a variables de retiro. */
    $MaxRetiro = $filterDetalle["MAXWITHDRAW"];


    $MinRetiroPuntoVenta = $filterDetalle["MINWITHDRAWBETSHOP"];


    $RequestMinAmountWithdrawBankAccount = $filterDetalle["MINWITHDRAWACCBANK"];


    /* Variables que almacenan límites de retiro y depósito del sistema de apuestas. */
    $MAXWITHDRAWBETSHOP = $filterDetalle["MAXWITHDRAWBETSHOP"];

    $MaxWithdrawBetKashnet = $filterDetalle["MAXWITHDRAWDAYKASNET"];

    $MinWithdrawBetkashnet = $filterDetalle["MINWITHDRAWDAYKASNET"];

    $MinDeposit = $filterDetalle["MINWITHDRAWDAYKASNET"];


    /* Asigna el valor de días de expiración de contraseñas a la variable `$ExpirationDays`. */
    $ExpirationDays = $filterDetalle["DAYSEXPIREPASSWORD"];

    if ($ExpirationDays != '' && $ExpirationDays != '0') {


        /* Verifica si la contraseña del usuario ha expirado y determina si necesita actualización. */
        $fecha_actual = date("Y-m-d H:i:s");

        $fechaEx = $Usuario->fechaClave;

        /* Separamos logica de visualización de landing para cambio de clave por expiracion de contraseña*/
        //Si el ususario ha actualizado alguna vez su contraseña
        if ($fechaEx) {
            $fecha_Vencimiento = date("Y-m-d ", strtotime($fechaEx . "+ $ExpirationDays days"));
            $message = $fecha_Vencimiento;
            if ($fecha_Vencimiento && $fecha_Vencimiento < $fecha_actual) {
                $update_password = true;
            } else {
                $update_password = false;
            }
        } else {
            //Si el usuario nunca ha actualizado su contraseña

            /* calcula años y días restantes de una duración de expiración especificada. */
            $date1 = new DateTime($Usuario->fechaCrea);
            $date2 = new DateTime();
            //Aproximamos dias a meses y años de la solicitud de expiración
            // Calcular años
            $years = floor($ExpirationDays / 365);
            // Calcular los días restantes después de extraer los años
            $remainingDays = $ExpirationDays % 365;
            // Calcular meses

            /* Calcula meses y días restantes entre dos fechas utilizando una aproximación de días por mes. */
            $months = floor($remainingDays / 30.44); // Aproximación de 30.44 días por mes
            // Calcular los días restantes después de extraer los meses
            $days = round($remainingDays % 30.44);

            // Calcular la diferencia exacta
            $diff = $date1->diff($date2);

            /* Calcula una fecha de vencimiento y verifica si se cumple un criterio específico. */
            $fecha_Vencimiento2 = date("Y-m-d ", strtotime($Usuario->fechaCrea . "+ $ExpirationDays days"));
            $message = $fecha_Vencimiento2;
            //La landing se mostrará cada vez que el dia sea el indicado por la configuracion del sitio y solo se mostrarán esos dias
            //Ejemplo si se configuraron 20 dias, el dia que se cumplan 20 dias se muestra(solo ese dia), el dia que se cumpla 1 mes y 20 dias se muestra tambien(solo ese dia), el dia que se cumplan 2 meses y 20 dias tambien(solo ese dia)...
            if ($diff->y >= intval($years) && $diff->m >= intval($months) && $diff->d == intval($days)) {
                $update_password = true;
            } else {
                /* establece una variable que indica que no se actualizará la contraseña. */

                $update_password = false;
            }
        }
    }


    /* Asignación de valores de un array a variables para configuración de notificaciones y impuestos. */
    $DAYSNOTIFYPASSEXPIRE = $filterDetalle["DAYSNOTIFYPASSEXPIRE"];

    $MinPassword = $filterDetalle["MINLENPASSWORD"];

    $TaxWithdrawalDeposit = $filterDetalle["TAXWITHDRAWDEPOSIT"];

    $TaxWithdrawalDepositFrom = $filterDetalle["TAXWITHDRAWDEPOSITFROM"];


    /* Asignación de valores desde un array a variables relacionadas con pagos y condiciones. */
    $TaxWithdrawalDepositFrom = $filterDetalle["TAXWITHDRAWAWARD"];

    $TaxWithdrawalRewardsFrom = $filterDetalle["TAXWITHDRAWAWARDFROM"];

    $SportbookContingence = ($filterDetalle["SPORTUSUONLINE"] == 'A');

    $defaultValorPasarela = $filterDetalle["DEFAULTAMOUNTPAYMENTGATEWAYS"];


    /* configura datos basados en condiciones del usuario y país. */
    $ConfigBack = [];

    if (($Usuario->mandante == 18 and ($countryId == 146 || $countryId == 232)) || ($Usuario->mandante == 23)) {
        $ConfigBack['OpenUpdateData2'] = $openUpdateData1;
    }
    $ConfigBack['TaxWithdrawalDeposit'] = $TaxWithdrawalDeposit ?: 0;

    /* Asigna valores o cero a variables de configuración, limita el retiro máximo. */
    $ConfigBack['TaxWithdrawalDepositFrom'] = $TaxWithdrawalDepositFrom ?: 0;
    $ConfigBack['TaxWithdrawalPrizes'] = $TaxWithdrawalPrizes ?: 0;
    $ConfigBack['TaxWithdrawalRewardsFrom'] = $TaxWithdrawalRewardsFrom ?: 0;
    $ConfigBack['SportbookContingence'] = $SportbookContingence ?? false;

    if ($MaxRetiro >= $MAXWITHDRAWBETSHOP) {
        $ConfigBack["MaxWithdraw"] = $MAXWITHDRAWBETSHOP;
    } else if ($MaxRetiro == 0) {
        /* Asigna un valor máximo de retiro si no hay retiros permitidos. */

        $ConfigBack["MaxWithdraw"] = $MAXWITHDRAWBETSHOP;
    } else {
        /* Asignación del valor de $MaxRetiro a la clave "MaxWithdraw" en $ConfigBack. */

        $ConfigBack["MaxWithdraw"] = $MaxRetiro;
    }


    /* Ajusta el mínimo de retiro según condiciones definidas. */
    if ($minimoRetiro < $MinRetiroPuntoVenta) {
        $ConfigBack["MinWithdraw"] = $MinRetiroPuntoVenta;
    } else {
        $ConfigBack["MinWithdraw"] = $minimoRetiro;
    }

    if ($minimoRetiro < $RequestMinAmountWithdrawBankAccount) {
        $ConfigBack["MinWithdrawBank"] = $RequestMinAmountWithdrawBankAccount;
    } else {
        /* Asigna el valor de $minimoRetiro a MinWithdrawBank si no se cumple una condición. */

        $ConfigBack["MinWithdrawBank"] = $minimoRetiro;
    }


    /* Establece límites de retiro y verifica el estado del usuario para actualizaciones. */
    $MaxWithdrawBankAccount = 0;

    if ($MaxWithdrawBankAccount == 0) {
        $ConfigBack["MaxWithdrawBankAccount"] = $MaxRetiro;
    }



    if (in_array($Usuario->mandante, ['15']) && in_array($Usuario->paisId, ['102'])) {

        $check_data = '';

        try {
            $UsuarioVerificacion = new UsuarioVerificacion('', $Usuario->usuarioId, '', 'USUACTUALIZACIONDATOS');
            $check_data = $UsuarioVerificacion->getEstado();
        } catch (Exception $ex) {
        }

        $ConfigBack['openUpdateData'] = in_array($check_data, ['P', 'A']) ? $check_data : 'NA';
    }



    /* verifica si un usuario está verificado o no. */
    try {
        if ($Usuario->verificado == 'N' || (($Usuario->verificado == "" || $Usuario->verificado == NULL) && ($Usuario->verifcedulaAnt != "S" || $Usuario->verifcedulaPost != "S"))) {

            $check_data2 = true;
        } else {
            $check_data2 = false;

        }
    } catch (Exception $ex) {
        /* captura excepciones en PHP sin realizar ninguna acción. */


    }


    /* Se intenta configurar un clasificador y obtener un tiempo de exclusión del usuario. */
    try {
        $Clasificador = new Clasificador('', 'EXCTIMEOUT');
        $UsuarioConfiguracion = new UsuarioConfiguracion($Usuario->usuarioId, 'A', $Clasificador->getClasificadorId());
        $exclution_time = $UsuarioConfiguracion->getValor();
    } catch (Exception $ex) {
    }


    /* Verifica condiciones para habilitar actualización de datos si ciertos campos están vacíos. */
    if ($Usuario->mandante == 20 && (empty($Registro->cedula) || empty($Registro->celular) || empty($Registro->direccion) || empty($Registro->ciudadId))) {
        $ConfigBack['OpenUpdateData2'] = true;
    }

    if ($Usuario->mandante == 23 && (empty($Registro->cedula) || empty($Registro->celular) || empty($Registro->direccion) || empty($Registro->ciudadId))) {
        $ConfigBack['OpenUpdateData2'] = true;
    }



    /* inicializa un objeto Moneda y configura límites de retiro para Bekashnet. */
    $symbolMoneda = new Moneda($moneda);
    $symbolMoneda = $symbolMoneda->symbol;


    $ConfigBack["MaxWithdrawBetKashnet"] = $MaxWithdrawBetKashnet;
    $ConfigBack["MinWithdrawBekashnet"] = $MinWithdrawBetkashnet;

    /* configura parámetros de seguridad y notificación para gestión de contraseñas. */
    $ConfigBack["DaysExpirePassword"] = $message;
    $ConfigBack["DaysNotifyPassExpire"] = $DAYSNOTIFYPASSEXPIRE;
    $ConfigBack["MinLenPassword"] = $MinPassword;
    $ConfigBack["MinDeposit"] = $MinDeposit;
    $ConfigBack["temporarySelf-exclusion"] = $fechaRegreso;
    if ($blocked_user2) {
        $ConfigBack["title"] = "Mincetur";
        $ConfigBack["blocked_user2"] = $blocked_user2;
    } else {
        /* Asigna el valor de $blocked_user2 a la clave "blocked_user2" del arreglo $ConfigBack. */

        $ConfigBack["blocked_user2"] = $blocked_user2;
    }


    /* asigna valores a un arreglo según la condición del usuario bloqueado. */
    if ($blocked_user3) {
        $ConfigBack["title2"] = "Pep";
        $ConfigBack["blocked_user3"] = $blocked_user3;
    } else {
        $ConfigBack["blocked_user3"] = $blocked_user3;
    }


    /* Inicializa arreglos y asigna un valor si el usuario es de prueba. */
    $profile = array();
    $profile_id = array();
    $min_bet_stakes = array();

    $usuarioTest = $Usuario->test;

    if ($usuarioTest === "S") {
        $profile_id['user_test'] = "1";
    } else {
        /* Asignación de "0" a 'user_test' si no cumple una condición previa. */

        $profile_id['user_test'] = "0";
    }

    /* Se asignan valores a un array asociativo de perfil de usuario. */
    $profile_id['id'] = $usuario_id;
    $profile_id['id_platform'] = $usuario_idPlatform;
    $profile_id['unique_id'] = $usuario_id;
    $profile_id['username'] = $usuario_id;
    $profile_id['name'] = $nombres;
    $profile_id['first_name'] = $primer_nombre . " " . $segundo_nombre;

    /* Asigna valores a un perfil usando información personal como nombres, género y contacto. */
    $profile_id['last_name'] = $primer_apellido . " " . $segundo_apellido;
    $profile_id['paternal_last_name'] = $primer_apellido;
    $profile_id['mother_last_name'] = $segundo_apellido;
    $profile_id['gender'] = $gender;
    $profile_id['email'] = $email;
    $profile_id['phone'] = $celular;

    /* Asigna valores a un array asociado para un perfil de usuario. */
    $profile_id['doc_type'] = $doc_type;
    $profile_id['reg_info_incomplete'] = false;
    $profile_id['address'] = "";

    $profile_id['language'] = $idioma;
    $profile_id['theme_color_mode'] = $theme_color_mode;


    $profile_id["reg_date"] = "";

    /* Asigna valores a un array asociativo para un perfil de usuario. */
    $profile_id["birth_date"] = $birthdate;
    $profile_id["identification"] = $identification;
    $profile_id["doc_number"] = "";
    $profile_id["casino_promo"] = null;
    $profile_id["currency_name"] = $moneda;
    $profile_id["currency_symbol"] = $symbolMoneda;



    /* Se asignan valores a un perfil financiero, incluyendo moneda y balances. */
    $profile_id["currency_id"] = $moneda;
    $profile_id["DefaultAmountDeposits"] = $defaultValorPasarela;
    $profile_id["balance"] = $saldo;
    $profile_id["pendingWithdrawals"] = $PendingWithdrawls;
    $profile_id["balanceDeposit"] = floatval($saldoRecargas);
    $profile_id["balanceWinning"] = $saldoRetiros;

    /* Asigna valores a un perfil de usuario, incluyendo balances y datos del país. */
    $profile_id["balanceBonus"] = $bonoPendientePorRollover;
    $profile_id["balanceFreebet"] = $saldoFreebet;

    $profile_id["balanceFreecasino"] = $saldoFreecasino;
    $profile_id["countryName"] = $countryName;
    $profile_id["countryid"] = intval($countryId);

    /* Asignación de valores a un array para almacenar información de perfil de usuario. */
    $profile_id["nationalityid"] = intval($countryNatId);
    $profile_id["nationality_id"] = intval($countryNatId);
    $profile_id['nationality'] = $nationalityName;

    $profile_id['country'] = $Pais;
    $profile_id['utc'] = $utc;

    /* Asignación de valores a un array asociativo relacionado con un perfil. */
    $profile_id['token'] = $token;
    $profile_id['req_cheque'] = $req_cheque;
    $profile_id['req_doc'] = $req_doc;
    $profile_id['fecha_crea'] = $fecha_crea;
    $profile_id['origen'] = $origen;

    $profile_id['fecha_actualizacion'] = date('Y-m-d H:i:s', strtotime(($fecha_actualizacion)));



    /* Código que asigna valores de tokens a un perfil de usuario en un sistema. */
    $profile_id['casino_enabled'] = '';
    $profile_id['tokenM'] = $tokenM;
    $profile_id['tokenSB'] = $tokenSB;



    $profile_id['tokenFCM'] = $tokenFCM;

    /* Se asignan valores a un array asociativo llamado $profile_id. */
    $profile_id["dniFront"] = $dniFront;
    $profile_id["dniFrontBack"] = $dniFrontBack;
    $profile_id["wallet"] = $wallet;
    $profile_id["loyaltyPoints"] = intval($puntosLealtad) + intval($puntosAExpirar);
    $profile_id["loyaltyLevel"] = intval($nivelLealtad);
    $profile_id['blocked_user'] = $blocked_user;


//$profile_id["casino_balance"] = $saldo;

    /* Asignación de valores a claves en un array para un perfil de usuario. */
    $profile_id["exclude_date"] = null;
    $profile_id["bonus_id"] = -1;
    $profile_id["games"] = 0;
    $profile_id["super_bet"] = -1;
    $profile_id["country_code"] = $Pais->iso;
    $profile_id["doc_issued_by"] = null;

    /* Inicializa variables relacionadas con un perfil, estableciendo valores nulos y asignando una provincia. */
    $profile_id["doc_issue_date"] = null;
    $profile_id["doc_issue_code"] = null;
    $profile_id["province"] = $depto_nom;
    $profile_id["iban"] = null;
    $profile_id["active_step"] = null;
    $profile_id["active_step_state"] = null;

    /* inicializa diferentes atributos del perfil de usuario. */
    $profile_id["subscribed_to_news"] = false;
    $profile_id["bonus_balance"] = 0.0;
    $profile_id["frozen_balance"] = 0.0;
    $profile_id["bonus_win_balance"] = 0.0;
    $profile_id["city"] = $ciudad_nom;


    $profile_id["has_free_bets"] = false;

    /* Inicializa variables de perfil de lealtad con valores predeterminados. */
    $profile_id["loyalty_point"] = 0.0;
    $profile_id["loyalty_earned_points"] = 0.0;
    $profile_id["loyalty_exchanged_points"] = 0.0;
    $profile_id["loyalty_level_id"] = null;
    $profile_id["affiliate_id"] = null;
    $profile_id["is_verified"] = false;

    /* Inicializa campos de perfil con valores nulos o cero en un array asociativo. */
    $profile_id["incorrect_fields"] = null;
    $profile_id["loyalty_point_usage_period"] = 0;
    $profile_id["loyalty_min_exchange_point"] = 0;
    $profile_id["loyalty_max_exchange_point"] = 0;
    $profile_id["active_time_in_casino"] = null;
    $profile_id["last_read_message"] = null;

    /* Asignación de valores a un array sobre un perfil de usuario. */
    $profile_id["unread_count"] = $mensajes_no_leidos;
    $profile_id["newnotification_count"] = $notificacion_nuevas;
    $profile_id["last_login_date"] = strtotime($fecha_ultima);
    $profile_id["last_login_ip"] = $ip_ultima;

    $profile_id["swift_code"] = null;

    /* Inicializa valores de bonificaciones y estado de perfil en un sistema. */
    $profile_id["bonus_money"] = 0.0;
    $profile_id["loyalty_last_earned_points"] = 0.0;

    $profile_id["registration_status"] = "1";


    $profile_id["state"] = 1;

    /* Inicializa diferentes tipos de contingencias en el perfil con valor cero. */
    $profile_id["contingency"] = 0;
    $profile_id["contingencySports"] = 0;
    $profile_id["contingencyCasino"] = 0;
    $profile_id["contingencyLiveCasino"] = 0;
    $profile_id["contingencyVirtuals"] = 0;
    $profile_id["contingencyPoker"] = 0;
    $profile_id['verifcelular'] = $verifcelular;
    $profile_id['exclution_time'] = isset($exclution_time) && !empty($exclution_time) ? $exclution_time : null;
    if ($Usuario->mandante == 0 && $Usuario->paisId == 173) $profile_id['user_verified'];


    /* Condicional que asigna URLs a banners según el país y usuario específicos. */
    if ($Usuario->paisId == 173) {
        $profile_id["iframemini_bannerright"] = 'https://casino.virtualsoft.tech/game/play/?gameid=4068&mode=real&provider=PLAYNGO&miniGame=true';
        $profile_id["iframemini_bannerleft"] = '';
        $profile_id["iframemini_bannerleft"] = '';
        if ($Usuario->usuarioId == 886) {
            $profile_id["iframemini_bannerright"] = 'https://casino.virtualsoft.tech/game/play/?gameid=29565&mode=real&provider=PRAGMATIC&lan=es&partnerid=' . $Usuario->mandante . '&token=' . $tokenM . '&balance=0&currency=PEN&userid=886&isMobile=false&miniGame=true';
            $profile_id["iframemini_bannerleft"] = '';
            $profile_id["iframemini_bannerleft"] = '';

        }

    }


    if ($Usuario != "") {


        /* verifica condiciones del usuario y asigna valores a un array. */
        if ($Usuario->estado == "I") {
            $profile_id["state"] = 0;
        }
        if ($Usuario->contingencia == "A") {
            $profile_id["contingency"] = 1;
        }

        /* Asigna valores 1 a perfiles según las contingencias del usuario en deportes y casino. */
        if ($Usuario->contingenciaDeportes == "A") {
            $profile_id["contingencySports"] = 1;
        }
        if ($Usuario->contingenciaCasino == "A") {
            $profile_id["contingencyCasino"] = 1;
        }

        /* Asigna valores a un perfil según las contingencias del usuario. */
        if ($Usuario->contingenciaCasvivo == "A") {
            $profile_id["contingencyLiveCasino"] = 1;
        }
        if ($Usuario->contingenciaVirtuales == "A") {
            $profile_id["contingencyVirtuals"] = 1;
        }

        /* Asignación de valor 1 a contingencyPoker si contingenciaPoker es "A". */
        if ($Usuario->contingenciaPoker == "A") {
            $profile_id["contingencyPoker"] = 1;
        }

    }

    /* gestiona la visibilidad de menús según condiciones del perfil del usuario. */
    $profile_id["showMenuReadTickets"] = ($profile_id["contingencySports"] == 0 ? 1 : 0);
    $profile_id["showMenuWithdraw"] = 1;

    /* Asigna diferentes skins e integraciones según el país y la condición del usuario. */
    $SubproveedorItn = new Subproveedor("", "ITN");
    $SubproveedorMandantePais = new SubproveedorMandantePais('', $SubproveedorItn->subproveedorId, $Usuario->mandante, $Usuario->paisId);
    $Credentials = json_decode($SubproveedorMandantePais->getCredentials());
    $skinItainment = $Credentials->SKIN_ID;
    $skinJsITN = $Credentials->SKIN_JS;

    $profile_id["skinItn"] = $skinItainment;
    $profile_id["skinJsITN"] = $skinJsITN;

    $PaisMandante = new \Backend\dto\PaisMandante('', $Usuario->mandante, $Usuario->paisId);

    /* Valida el estado del país y asigna un perfil si es válido. */
    if ($PaisMandante->estado != 'A') {
        throw new Exception("No existe Token", "20000");
    }

    /* Asignación de URL según país y mandante en un perfil de usuario específico. */
    if ($profile_id["countryid"] == "146" && $Usuario->mandante == "18") {
        $profile_id["skinJsITN"] = "https://sb2integration-altenar2.biahosted.com/api/Integration/gangabet.mx";
    }
    if ($profile_id["countryid"] == "232" && $Usuario->mandante == "18") {
        $profile_id["skinJsITN"] = "https://sb2integration-altenar2.biahosted.com/api/Integration/gangabet.mx";
    }

    /* verifica el ID de usuario y establece una variable según esa condición. */
    if ($Usuario->usuarioId == 886) {
        $profile_id["update_password"] = false;

    }

//Para maquina
    /*
    * 1 -> readticket
    *
    */

//Solo desarollo

    $MaxRows = 1;

    /* define variables y agrega reglas a un arreglo para procesar datos. */
    $OrderedItem = 1;
    $SkeepRows = 0;


    $rules = [];

    array_push($rules, array("field" => "usuario_log2.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

    /* Se crea una estructura de filtro en formato JSON para consultas SQL. */
    array_push($rules, array("field" => "usuario_log2.tipo", "data" => "ESTADOUSUARIO", "op" => "eq"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");

    $json2 = json_encode($filtro);

    $select = " usuario_log2.* ";


    if ($ConfigurationEnvironment->isDevelopment()) {


        /* gestiona y valida el estado de lectura de un ticket. */
        $UsuarioLog = new UsuarioLog2();
        $data2 = $UsuarioLog->getUsuarioLog2sCustom($select, "usuario_log2.fecha_crea", "desc", $SkeepRows, $MaxRows, $json2, true, "");
        $data2 = json_decode($data2);

        $stateM = 0;

        if ($data2->data[0]->{"usuario_log2.valor_antes"} == "READTICKET") {
            if ($data2->data[0]->{"usuario_log2.estado"} == "A") {
                $profile_id["message"] = array();
                $profile_id["message"]["type"] = "success";
                $profile_id["message"]["title"] = "Ticket leido";
                $profile_id["message"]["content"] = "Ticket leido satisfactoriamente";

                $stateM = 2;

            } else {
                $stateM = 1;

            }
        }


        /* Verifica si un depósito fue exitoso y establece el estado correspondiente. */
        if ($data2->data[0]->{"usuario_log2.valor_antes"} == "DEPOSIT") {
            if ($data2->data[0]->{"usuario_log2.estado"} == "A") {
                $profile_id["message"] = array();
                $profile_id["message"]["type"] = "success";
                $profile_id["message"]["title"] = "Deposito";
                $profile_id["message"]["content"] = "Deposito satisfactorio";

                $stateM = 2;

            } else {
                $stateM = 1;

            }
        }
    }



    /* Se configuran propiedades en un array relacionado con el perfil de usuario. */
    $profile_id["StateM"] = $stateM;

    $profile_id["messagingIsEnabled"] = true;
    $profile_id["printTicketIsEnabled"] = false;


    $profile_id["typeUser"] = $stateM;

    switch ($UsuarioPerfil->getPerfilId()) {
        case "USUONLINE":
            /* Asigna el tipo de usuario '0' si se selecciona "USUONLINE". */


            $profile_id["typeUser"] = '0';
            break;

        case "PUNTOVENTA":
            /* asigna propiedades específicas a un perfil en caso de venta. */



            $profile_id["typeUser"] = '1';
            $profile_id["printTicketIsEnabled"] = true;


            break;

        case "CAJERO":
            /* asigna un perfil específico para el tipo de usuario "CAJERO". */



            $profile_id["typeUser"] = '1';
            $profile_id["printTicketIsEnabled"] = true;


            break;
    }



    /* Crea un clasificador y obtiene el valor de cambio de clave del usuario. */
    $Clasificador = new Clasificador("", "DAYALERTCHANGEPASS");
    $minimodiasCambioClave = 0;
    try {
        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
        $minimodiasCambioClave = $MandanteDetalle->getValor();
    } catch (Exception $e) {
        /* Captura excepciones en PHP sin realizar ninguna acción en caso de error. */

    }

    /* Establece el valor de alerta de cambio de contraseña en el perfil a 0. */
    $profile_id["alertChangePassword"] = 0;

    if ($minimodiasCambioClave != "" && $minimodiasCambioClave != '0') {


        /* Se definen variables para el manejo de filas y reglas en un proceso. */
        $MaxRows = 1;
        $OrderedItem = 1;
        $SkeepRows = 0;


        $rules = [];


        /* crea un filtro JSON con reglas para consultar datos de usuario. */
        array_push($rules, array("field" => "usuario_log2.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
        array_push($rules, array("field" => "usuario_log2.tipo", "data" => "ALERTCAMBIOCONTRASEÑA", "op" => "eq"));

        $filtro = array("rules" => $rules, "groupOp" => "AND");

        $json2 = json_encode($filtro);


        /* Se consulta y decodifica en JSON información de registros de usuarios. */
        $select = " usuario_log2.* ";


        $UsuarioLog = new UsuarioLog2();
        $data2 = $UsuarioLog->getUsuarioLog2sCustom($select, "usuario_log2.fecha_crea", "desc", $SkeepRows, $MaxRows, $json2, true, "");
        $data2 = json_decode($data2);



        /* Determina si se debe actualizar la contraseña basándose en la fecha de creación. */
        $stateACP = 0;

        if ($data2->data[0]->{"usuario_log2.fecha_crea"} != "") {

            $date1 = $data2->data[0]->{"usuario_log2.fecha_crea"};
            $date1 = new DateTime($date1);
            $date2 = new DateTime();

            // Calcular la diferencia exacta
            $diff = $date1->diff($date2);

            if ($diff->days >= $minimodiasCambioClave) {
                $stateACP = 1;
                if ($diff->days >= $ExpirationDays) $stateACP = 0; //Evita desborde despues de fecha de expiracion de contraseña
            } else {
                $stateACP = 0;
            }


        } else {

            /* asigna una fecha en función de si existe 'fechaClave' o no. */
            if ($Usuario->fechaClave == "" || $Usuario->fechaClave == null) {
                $date1 = new DateTime($Usuario->fechaCrea);
            } else {
                $date1 = new DateTime($Usuario->fechaClave);
            }

            $date2 = new DateTime();

            // Calcular la diferencia exacta

            /* calcula la diferencia de días entre dos fechas y establece estados de contraseña. */
            $diff = $date1->diff($date2);

            if ($diff->days >= $minimodiasCambioClave) {
                $stateACP = 1;
                //if ($diff->days >= $ExpirationDays) $stateACP = 0; //Evita desborde despues de fecha de expiracion de contraseña
            } else {
                /* establece $stateACP en 0 si no se cumple una condición anterior. */

                $stateACP = 0;

            }


        }

        if ($stateACP == 1) {

            /* Logica para enviarle al usuario un mensaje cuando el dia de vencimiento de contraseña está próximo*/

            // Verificamos si ha recibido previamente un mensaje de notificacion de vencimiento de contraseña

            /* Crea un filtro JSON para obtener mensajes de usuario específicos según criterios definidos. */
            $UsuarioMensaje = new UsuarioMensaje();
            $json2 = '{"rules" : [{"field" : "usuario_mensaje.usuto_id", "data": "' . $UsuarioMandante->getUsumandanteId() . '","op":"eq"},{"field" : "usuario_mensaje.usufrom_id", "data": "0","op":"eq"},{"field" : "usuario_mensaje.tipo", "data": "MENSAJE","op":"eq"}] ,"groupOp" : "AND"}';

            $usuarios = $UsuarioMensaje->getUsuarioMensajesCustomCampana($UsuarioMandante->getUsuarioMandante(), $UsuarioMandante->getPaisId(), $Usuario->fechaCrea, $UsuarioMandante->getUsuarioMandante(), $UsuarioMandante->getUsumandanteId());
            $usuarios = json_decode($usuarios);
            $mensajeContra = true;


            /* verifica mensajes de expiración y establece un aviso si el tiempo no ha pasado. */
            foreach ($usuarios->data as $key => $value) {
                if ($value->{"m.usumensaje_id"} != '' && $value->{"m.usumensaje_id"} != '0') {
                    if ($value->{"umc.nombre"} == "Expiracion contraseña") {
                        $fecha_2 = new DateTime($value->{"umc.fecha_crea"});
                        $fecha_2 = $fecha_2->modify("+5 minutes");
                        if (new DateTime() < $fecha_2) {
                            $mensajeContra = false;
                            break;
                        }
                        break;
                    }
                }
            }
            // Si no ha recibido el mensaje procedemos a enviarlo
            if ($mensajeContra) {

                /* Se crean objetos para manejar la configuración y el registro de usuario en MySQL. */
                $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
                //$UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);

                $UsuarioLog = new UsuarioLog2();
                $UsuarioLog->setUsuarioId($UsuarioMandante->getUsuarioMandante());
                $UsuarioLog->setUsuarioIp($UsuarioMandanteSite->usumandanteIdip);

                /* Código que registra un cambio de contraseña en el sistema de logs. */
                $UsuarioLog->setUsuariosolicitaId($UsuarioMandante->getUsuarioMandante());
                $UsuarioLog->setUsuariosolicitaIp($UsuarioMandanteSite->usumandanteIdip);
                $UsuarioLog->setTipo("ALERTCAMBIOCONTRASEÑA");
                $UsuarioLog->setEstado("A");
                $UsuarioLog->setValorAntes('');
                $UsuarioLog->setValorDespues('');

                /* Se establece un nuevo registro de usuario en la base de datos. */
                $UsuarioLog->setUsucreaId(0);
                $UsuarioLog->setUsumodifId(0);
                $UsuarioLog2MySqlDAO = new \Backend\mysql\UsuarioLog2MySqlDAO();
                $UsuarioLog2MySqlDAO->insert($UsuarioLog);
                $UsuarioLog2MySqlDAO->getTransaction()->commit();

                $proveedorId = '0';

                // Obtener la fecha de hoy en formato 'Y-m-d'
                $fechaProxima = new \DateTime();
                $fechaProxima->setTime(0, 0);

                /* Modifica la fecha, luego genera un mensaje personalizado según el idioma del usuario. */
                $fechaProxima->modify('+7 days');
                $fechaProxima->format('Y-m-d');

                $title = '';
                $messageBody = '';

                switch (strtolower($Usuario->idioma)) {
                    case 'es':
                        $title = 'Notificacion cambio contraseña';
                        $messageBody = "Su contraseña expira en $message";
                        break;
                    case 'en':
                        $title = 'Notification password change';
                        $messageBody = "Your password expires on $message";
                        break;
                    case 'pt':
                        $title = 'Notificação mudança senha';
                        $messageBody = "Sua senha expira em $message";
                        break;
                }


                /* Se crea un objeto de mensaje con propiedades inicializadas para un usuario. */
                $UsuarioMensajecampana = new UsuarioMensajecampana();
                $UsuarioMensajecampana->usufromId = 0;
                $UsuarioMensajecampana->usutoId = "-1";
                $UsuarioMensajecampana->isRead = 0;
                $UsuarioMensajecampana->body = $messageBody;
                $UsuarioMensajecampana->msubject = "";

                /* Se configuran propiedades de un objeto para una campaña de notificaciones push. */
                $UsuarioMensajecampana->parentId = 0;
                $UsuarioMensajecampana->proveedorId = $proveedorId;
                $UsuarioMensajecampana->tipo = "PUSHNOTIFICACION";
                $UsuarioMensajecampana->estado = "A";
                $UsuarioMensajecampana->paisId = $Usuario->paisId;
                $UsuarioMensajecampana->fechaExpiracion = $fechaProxima->format('Y-m-d');

                /* Se asignan valores a propiedades de un objeto relacionado con la expiración de contraseña. */
                $UsuarioMensajecampana->usucreaId = $_SESSION["usuario"];
                $UsuarioMensajecampana->usumodifId = $_SESSION["usuario"];;
                $UsuarioMensajecampana->nombre = "Expiracion contraseña";
                $UsuarioMensajecampana->descripcion = "Expiracion contraseña";
                $UsuarioMensajecampana->t_value = "";
                $UsuarioMensajecampana->mandante = $Usuario->mandante;

                /* Se establece la fecha de envío y se inserta un registro en la base de datos. */
                $UsuarioMensajecampana->fechaEnvio = date('Y-m-d H:i:s', time());
                //$UsuarioMensajecampana->estado = 'A';

                $UsuarioMensajecampanaMySqlDAO = new UsuarioMensajecampanaMySqlDAO();

                $UsuarioMensajecampanaMySqlDAO->insert($UsuarioMensajecampana);

                /* gestiona transacciones y asigna un ID a un mensaje de usuario. */
                $UsuarioMensajecampanaMySqlDAO->getTransaction()->commit();

                $usumencampanaId = $UsuarioMensajecampana->usumencampanaId; /*descripcion de la variable: contiene el id de la campaña del mensaje*/

                $UsuarioMensaje = new UsuarioMensaje();
                $UsuarioMensaje->usufromId = 0;

                /* Se asignan valores a propiedades de un objeto UsuarioMensaje. */
                $UsuarioMensaje->usutoId = $UsuarioMandante->getUsumandanteId();
                $UsuarioMensaje->isRead = 0;
                $UsuarioMensaje->body = $messageBody;
                $UsuarioMensaje->msubject = $title;
                $UsuarioMensaje->parentId = 0;
                $UsuarioMensaje->proveedorId = $proveedorId;

                /* Se crea un objeto mensaje de usuario con datos específicos para una notificación. */
                $UsuarioMensaje->tipo = "PUSHNOTIFICACION";
                $UsuarioMensaje->paisId = $Usuario->paisId;
                $UsuarioMensaje->fechaExpiracion = $fechaProxima->format('Y-m-d');
                $UsuarioMensaje->usumencampanaId = $usumencampanaId;

                $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();

                /* Se insertan datos de usuario y se confirma la transacción en MySQL. */
                $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                $UsuarioMensajeMySqlDAO->getTransaction()->commit();

                $UsuarioMensajecampana->usumensajeId = $UsuarioMensaje->usumensajeId;

                $UsuarioMensajecampanaMySqlDAO = new UsuarioMensajecampanaMySqlDAO();

                /* Actualiza datos de campaña y maneja transacciones en una base de datos MySQL. */
                $UsuarioMensajecampanaMySqlDAO->update($UsuarioMensajecampana);
                $UsuarioMensajecampanaMySqlDAO->getTransaction()->commit();
            }


            /* Asigna el estado de alerta de cambio de contraseña a un perfil específico. */
            $profile_id["alertChangePassword"] = $stateACP;

        }
    }


    /* Condicionalmente, obtiene y prepara información sobre documentos no procesados para el usuario. */
    if ($ConfigurationEnvironment->isDevelopment() || $Mandante->mandante == 19 || $Mandante->mandante == 18) {
        $DocumentoUsuario = new DocumentoUsuario();
        $DocumentoUsuario->usuarioId = $UsuarioMandante->getUsuarioMandante();
        $Documentos = $DocumentoUsuario->getDocumentosNoProcesados();

        if (oldCount($Documentos) > 0) {
            $Documentos = json_decode(json_encode($Documentos))[0];
            $data["document"] = array(
                "accept" => false,
                "slug" => $Documentos->{'descarga.ruta'},
                "id" => $Documentos->{'descarga.descarga_id'},
                "title" => $Documentos->{'descarga.descripcion'},
                "checksum" => $Documentos->{'descarga.encriptacion_valor'}
            );
        } else {
            $data["document"] = array(
                "accept" => true
            );
        }
    }


    /* Verifica condiciones y establece permisos relacionados con un perfil de usuario. */
    if ($dniFront == 3 && $dniFrontBack == 3) {
        $profile_id["is_verified"] = true;
    }

    $profile_id["withdeawPermitShop"] = true;
    $profile_id["withdeawPermitBanck"] = true;

    /* Asignación de valores a un arreglo asociativo para actualizar perfil y configuración. */
    $profile_id["update_password"] = $update_password;
    $profile_id["user_verification"] = $check_data2;
    $profile_id["valueDefaultShop"] = 50;
    if (in_array($Usuario->mandante, ['0', '8', '17']) && in_array($Usuario->paisId, ['46', '66', '33'])) $profile_id['is_verified'] = empty($Usuario->verificado) ? false : true;

    $bono_headerf = array();

    if ($Usuario->mandante == 19) {



        /* Se configuran condiciones para filtrar datos de bonos en una consulta. */
        $MaxRows = 1000;
        $OrderedItem = 1;
        $SkeepRows = 0;
        $rules = [];
        array_push($rules, array("field" => "bono_interno.tipo", "data" => '5,6,2,3', "op" => "in"));
        array_push($rules, array("field" => "usuario_bono.estado", "data" => 'A', "op" => "eq"));

        /* Se crea un filtro JSON para validar condiciones sobre `usuario_bono`. */
        array_push($rules, array("field" => "usuario_bono.usuario_id", "data" => $Usuario->usuarioId, "op" => "eq"));


        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json2 = json_encode($filtro);

        $UsuarioBono = new UsuarioBono();


        /* Consulta bonos de usuario con detalles y expiración en formato JSON. */
        $bonos = $UsuarioBono->getUsuarioBonosCustom("   usuario.moneda,
                     bono_interno.nombre,bono_interno.tipo,bono_interno.descripcion,usuario_bono.bono_id,
                     usuario_bono.valor valor_actual,usuario_bono.valor_bono,
                     usuario_bono.usuario_id,usuario_bono.estado,
                      CASE bono_detalle2.tipo WHEN 'EXPDIA' THEN  DATE_FORMAT((DATE_ADD(DATE_FORMAT(usuario_bono.fecha_crea,'%Y-%m-%d %H:%i:%s'), INTERVAL bono_detalle2.valor DAY)),'%Y-%m-%d %H:%i:%s') ELSE DATE_FORMAT(usuario_bono.fecha_crea,'%Y-%m-%d %H:%i:%s') END AS fecha_expiracion  ", "usuario_bono.usubono_id", "desc", $SkeepRows, $MaxRows, $json2, true, '', 'MINAMOUNT', "'EXPDIA','EXPFECHA'");

        $bonos = json_decode($bonos);

        foreach ($bonos->data as $key => $value) {

            /* Se inicializa un arreglo vacío llamado "bono_header" en PHP. */
            $bono_header = array();
            try {
                //$bono_detalle = new BonoDetalle('',$value->{"usuario_bono.bono_id"},'VALORROLLOWER');

                /* Se generan filtros para obtener detalles de bonos de usuarios específicos. */
                $bonorules = [];
                array_push($bonorules, array("field" => "bono_detalle.bono_id", "data" => $value->{"usuario_bono.bono_id"}, "op" => "eq"));
                $filters = json_encode(['rules' => $bonorules, 'groupOp' => 'AND']);
                $bono_detalles = new BonoDetalle();
                $bono_detalles = $bono_detalles->getBonoDetallesCustom("bono_detalle . *", "bonodetalle_id", "ASC", 0, 1000, $filters, true);
                $bono_detalles = json_decode($bono_detalles);

                foreach ($bono_detalles->data as $detalle) {
                    switch ($detalle->{"bono_detalle.tipo"}) {
                        case 'ITAINMENT82':
                            /* Asigna tipo de apuesta según el valor del bono en la variable $bono_header. */

                            if ($detalle->{"bono_detalle.valor"} == 1) {
                                $bono_header["tipo_apuesta"] = 'simple';
                            }
                            if ($detalle->{"bono_detalle.valor"} == 2) {
                                $bono_header["tipo_apuesta"] = 'combinada';
                            }
                            break;
                        case 'MINSELCOUNT':
                            /* Establece un mínimo seleccionable si su valor es mayor a cero. */

                            $minimo_select = $detalle->{"bono_detalle.valor"};
                            if ($minimo_select > 0) {
                                $bono_header["minimo_select"] = $minimo_select;
                            }
                            break;
                        case 'MINSELPRICE':
                            /* Asigna un valor mínimo a 'minimo_cuota' si es mayor que cero. */

                            $minimo_cuota = $detalle->{"bono_detalle.valor"};
                            if ($minimo_cuota > 0) {
                                $bono_header["minimo_cuota"] = $minimo_cuota;
                            }
                            break;
                        case 'MINSELPRICETOTAL':
                            /* Asigna valor a "minimo_total" si es mayor que cero. */

                            $minimo_total = $detalle->{"bono_detalle.valor"};
                            if ($minimo_total > 0) {
                                $bono_header["minimo_total"] = $minimo_total;
                            }
                            break;
                        case 'MINBETPRICE':
                            /* Establece el mínimo de apuesta si es mayor que cero. */

                            $minimo_apuesta = $detalle->{"bono_detalle.valor"};
                            if ($minimo_apuesta > 0) {
                                $bono_header["minimo_apuesta"] = $minimo_apuesta;
                            }
                            break;
                        case 'VALORROLLOWER':
                            /* Calcula el porcentaje de bono basado en un valor total. */

                            $valor_total = $detalle->{"bono_detalle.valor"};
                            if ($valor_total == '' || floatval($valor_total) == 0) {
                                $BonusPercentage = 0;
                            } else {
                                $BonusPercentage = (floatval($value->{"usuario_bono.valor_actual"}) / floatval($valor_total)) * 100;
                            }
                            break;
                        case 'MINAMOUNT':
                            /* Asigna valor mínimo del bono si es mayor que cero. */

                            $minamount = $detalle->{"bono_detalle.valor"};
                            if ($minamount > 0) {
                                $bono_header['valor_bono_minimo'] = $detalle->{"bono_detalle.valor"} . ' ' . $detalle->{"bono_detalle.moneda"};
                            }
                            break;
                        case 'MAXAMOUNT':
                            /* Asigna el valor y moneda de bono si existe una cantidad máxima. */

                            $maxamount = $detalle->{"bono_detalle.valor"};
                            if ($maxamount) {
                                $bono_header['valor_bono'] = $detalle->{"bono_detalle.valor"} . ' ' . $detalle->{"bono_detalle.moneda"};
                            }
                            break;
                    }
                }
            } catch (Exception $e) {
                /* Bloque para capturar excepciones en PHP sin realizar ninguna acción en caso de error. */

            }


            /* Asigna un tipo de bono basado en el valor de "bono_interno.tipo". */
            $bono_header["name"] = $value->{1};

            if ($value->{"bono_interno.tipo"} == "2") {
                $bono_header["type"] = "Deposito";
            } else {
                if ($value->{"bono_interno.tipo"} == "3") {
                    $bono_header["type"] = "No Deposito";
                } else if ($value->{"bono_interno.tipo"} == "6") {
                    $bono_header["type"] = "FreeBet";
                } else if ($value->{"bono_interno.tipo"} == "5") {
                    $bono_header["type"] = "FreeCash";
                } else {
                    $bono_header["type"] = "";
                }
            }

            /* Asigna un porcentaje de bono y un texto descriptivo basado en condiciones. */
            if ($BonusPercentage) {
                $bono_header["porcent"] = $BonusPercentage;
            } else {
                $bono_header["porcent"] = ($value->{"usuario_bono.estado"} == 'A') ? 100 : 0;
            }

            $bono_header["progress_text"] = $value->{"bono_interno.descripcion"};



            /* Asignación de fecha de expiración a un array y almacenamiento en un nuevo array. */
            $bono_header['expireDate'] = $value->{".fecha_expiracion"};

            array_push($bono_headerf, ($bono_header));

        }

    }



    /* Asignación de encabezado y verificación de acceso a funciones de mensajería según perfil. */
    $profile_id["bono_header"] = $bono_headerf;

    if ($UsuarioPerfil->getPerfilId() != 'USUONLINE') {
        $profile_id["messagingIsEnabled"] = false;

    }



    /* Asigna un perfil a un usuario y configura datos en un arreglo. */
    $profile[$usuario_id] = $profile_id;


    $data["profile"] = $profile;

    $data["config"] = $ConfigBack;


}


if ($UsuarioMandanteSite->usumandanteId == 'FUN') {


    /* asigna un ID de usuario y prepara una respuesta inicial. */
    $usuario_id = $UsuarioMandanteSite->usumandanteId;


    $response = array();

    $response['code'] = 0;


    /* Inicializa arreglos vacíos y obtiene datos de prueba de un objeto usuario. */
    $data = array();
    $profile = array();
    $profile_id = array();

    $min_bet_stakes = array();


    $usuarioTest = $Usuario->test;


    /* establece el perfil de usuario según una condición de prueba. */
    if ($usuarioTest === "S") {
        $profile_id['user_test'] = "1";
    } else {
        $profile_id['user_test'] = "0";
    }

    $profile_id['id'] = $usuario_id;

    /* Se crea un arreglo con datos de perfil de usuario para una prueba. */
    $profile_id['user_test'] = $usuarioTest;
    $profile_id['id_platform'] = $usuario_id;
    $profile_id['unique_id'] = $usuario_id;
    $profile_id['username'] = $usuario_id;
    $profile_id['name'] = 'TEST';
    $profile_id['first_name'] = 'TEST';

    /* asigna valores iniciales a un perfil de usuario. */
    $profile_id['last_name'] = 'TEST';
    $profile_id['gender'] = "";
    $profile_id['email'] = "";
    $profile_id['phone'] = 0;
    $profile_id['reg_info_incomplete'] = false;
    $profile_id['address'] = "";


    /* asigna valores vacíos a variables de perfil en español. */
    $profile_id['language'] = 'es';


    $profile_id["reg_date"] = "";
    $profile_id["birth_date"] = "";
    $profile_id["doc_number"] = "";

    /* inicializa un perfil con datos de moneda y balance en cero. */
    $profile_id["casino_promo"] = null;
    $profile_id["currency_name"] = 'USD';
    $profile_id["currency_symbol"] = $symbolMoneda;

    $profile_id["currency_id"] = 'USD';
    $profile_id["balance"] = 0;

    /* Inicializa balances y fecha de exclusión para un perfil de usuario. */
    $profile_id["balanceDeposit"] = floatval(0);
    $profile_id["balanceWinning"] = 0;
    $profile_id["balanceBonus"] = 0;
    $profile_id["balanceFreebet"] = 0;

    $profile_id["exclude_date"] = null;

    /* Inicializa datos de un perfil, incluyendo bonificaciones y localización. */
    $profile_id["bonus_id"] = -1;
    $profile_id["games"] = 0;
    $profile_id["super_bet"] = -1;
    $profile_id["country_code"] = 'PE';
    $profile_id["doc_issued_by"] = null;
    $profile_id["doc_issue_date"] = null;

    /* Se inicializan propiedades de un perfil con valores nulos o predeterminados. */
    $profile_id["doc_issue_code"] = null;
    $profile_id["province"] = null;
    $profile_id["iban"] = null;
    $profile_id["active_step"] = null;
    $profile_id["active_step_state"] = null;
    $profile_id["subscribed_to_news"] = false;

    /* Se inicializan saldos y datos de perfil en un sistema de apuestas. */
    $profile_id["bonus_balance"] = 0.0;
    $profile_id["frozen_balance"] = 0.0;
    $profile_id["bonus_win_balance"] = 0.0;
    $profile_id["city"] = "city";


    $profile_id["has_free_bets"] = false;

    /* Inicializa un perfil con puntos de lealtad y verificación a valores predeterminados. */
    $profile_id["loyalty_point"] = 0.0;
    $profile_id["loyalty_earned_points"] = 0.0;
    $profile_id["loyalty_exchanged_points"] = 0.0;
    $profile_id["loyalty_level_id"] = null;
    $profile_id["affiliate_id"] = null;
    $profile_id["is_verified"] = false;

    /* Inicializa campos del perfil con valores por defecto o nulos en un array. */
    $profile_id["incorrect_fields"] = null;
    $profile_id["loyalty_point_usage_period"] = 0;
    $profile_id["loyalty_min_exchange_point"] = 0;
    $profile_id["loyalty_max_exchange_point"] = 0;
    $profile_id["active_time_in_casino"] = null;
    $profile_id["last_read_message"] = null;

    /* Código inicializa variables relacionadas con un perfil de usuario, como conteos y datos de inicio. */
    $profile_id["unread_count"] = 0;
    $profile_id["newnotification_count"] = 0;
    $profile_id["last_login_date"] = strtotime('');
    $profile_id["last_login_ip"] = '';

    $profile_id["swift_code"] = null;

    /* inicializa variables de perfil con valores predeterminados para bonificaciones y estado. */
    $profile_id["bonus_money"] = 0.0;
    $profile_id["loyalty_last_earned_points"] = 0.0;

    $profile_id["registration_status"] = "1";


    $profile_id["state"] = 1;

    /* Inicializa valores de contingencia en un perfil para diferentes categorías de juegos. */
    $profile_id["contingency"] = 0;
    $profile_id["contingencySports"] = 0;
    $profile_id["contingencyCasino"] = 0;
    $profile_id["contingencyLiveCasino"] = 0;
    $profile_id["contingencyVirtuals"] = 0;
    $profile_id["contingencyPoker"] = 0;


    /* Código que configura opciones de menú y un valor predeterminado para un usuario. */
    $profile_id["showMenuReadTickets"] = ($profile_id["contingencySports"] == 0 ? 1 : 0);
    $profile_id["showMenuWithdraw"] = 1;

    $profile_id["valueDefaultShop"] = 50;


    $profile[$usuario_id] = $profile_id;



    /* Asigna valores a un arreglo asociativo en PHP para perfil y configuración. */
    $data["profile"] = $profile;
    $data["config"] = $ConfigBack;

}



/* decodifica un menú en formato JSON, incluyendo submenús y atributos. */
$usersMenu = json_decode((
'[
{"MENU_ID":"3","MENU_TITLE":"Gesti\u00f3n","MENU_SLUG":"gestion","MENU_EDITAR":"true","MENU_ELIMINAR":"false","MENU_ADICIONAR":"true","SUBMENUS":[{"SUBMENU_ID":"136","SUBMENU_URL":"deposito","SUBMENU_TITLE":"Depositar"},{"SUBMENU_ID":"102","SUBMENU_URL":"cuenta_cobro_anular","SUBMENU_TITLE":"Anular Nota Retiro"},{"SUBMENU_ID":"189","SUBMENU_URL":"cuentasbancarias","SUBMENU_TITLE":"Cuentas bancarias"},{"SUBMENU_ID":"41","SUBMENU_URL":"cuenta_cobro","SUBMENU_TITLE":"Retirar"},{"SUBMENU_ID":"500","SUBMENU_URL":"verificar_cuenta","SUBMENU_TITLE":"Verificar Cuenta"},{"SUBMENU_ID":"121","SUBMENU_URL":"cambiar-clave","SUBMENU_TITLE":"Cambiar Contrase\u00f1a"},{"SUBMENU_ID":"195","SUBMENU_URL":"misbonos","SUBMENU_TITLE":"Mis Bonos"},{"SUBMENU_ID":"87","SUBMENU_URL":"gestion_cuenta","SUBMENU_TITLE":"Mi Cuenta"}]},

{"MENU_ID":"5","MENU_TITLE":"Consultas","MENU_SLUG":"consulta","MENU_EDITAR":"false","MENU_ELIMINAR":"false","MENU_ADICIONAR":"true","SUBMENUS":[{"SUBMENU_ID":"100","SUBMENU_URL":"consulta_tickets_online","SUBMENU_TITLE":"Consulta de apuestas deportivas"},{"SUBMENU_ID":"184","SUBMENU_URL":"consulta_tickets_casino","SUBMENU_TITLE":"Informe de apuestas casino"},{"SUBMENU_ID":"186","SUBMENU_URL":"consulta_depositos","SUBMENU_TITLE":"Consultar depositos"},{"SUBMENU_ID":"188","SUBMENU_URL":"consulta_retiros","SUBMENU_TITLE":"Consultar retiros"}]}



]'
));


/* Carga un menú de usuario específico si el mandante es '8'. */
if ($Mandante->mandante == '8') {

    $usersMenu = json_decode((
    '[{"MENU_ID":"3","MENU_TITLE":"Gesti\u00f3n","MENU_SLUG":"gestion","MENU_EDITAR":"true","MENU_ELIMINAR":"false","MENU_ADICIONAR":"true","SUBMENUS":[{"SUBMENU_ID":"136","SUBMENU_URL":"deposito","SUBMENU_TITLE":"Depositar"},{"SUBMENU_ID":"102","SUBMENU_URL":"cuenta_cobro_anular","SUBMENU_TITLE":"Anular Nota Retiro"},{"SUBMENU_ID":"189","SUBMENU_URL":"cuentasbancarias","SUBMENU_TITLE":"Cuentas bancarias"},{"SUBMENU_ID":"41","SUBMENU_URL":"cuenta_cobro","SUBMENU_TITLE":"Retirar"},{"SUBMENU_ID":"500","SUBMENU_URL":"verificar_cuenta","SUBMENU_TITLE":"Verificar Cuenta"},{"SUBMENU_ID":"121","SUBMENU_URL":"cambiar-clave","SUBMENU_TITLE":"Cambiar Contrase\u00f1a"},{"SUBMENU_ID":"195","SUBMENU_URL":"misbonos","SUBMENU_TITLE":"Mis Bonos"},{"SUBMENU_ID":"87","SUBMENU_URL":"gestion_cuenta","SUBMENU_TITLE":"Mi Cuenta"},{"SUBMENU_ID":"88","SUBMENU_URL":"shop-bonuses","SUBMENU_TITLE":"Tienda de premios"}]},{"MENU_ID":"5","MENU_TITLE":"Consultas","MENU_SLUG":"consulta","MENU_EDITAR":"false","MENU_ELIMINAR":"false","MENU_ADICIONAR":"true","SUBMENUS":[{"SUBMENU_ID":"100","SUBMENU_URL":"consulta_tickets_online","SUBMENU_TITLE":"Consulta de apuestas deportivas"},{"SUBMENU_ID":"184","SUBMENU_URL":"consulta_tickets_casino","SUBMENU_TITLE":"Informe de apuestas casino"},{"SUBMENU_ID":"186","SUBMENU_URL":"consulta_depositos","SUBMENU_TITLE":"Consultar depositos"},{"SUBMENU_ID":"188","SUBMENU_URL":"consulta_retiros","SUBMENU_TITLE":"Consultar retiros"},{"SUBMENU_ID":"88","SUBMENU_URL":"mi_lealtad","SUBMENU_TITLE":"Mi Lealtad"}]}]'
    ));
}

/* Carga un menú de usuario específico si el mandante es '21'. */
if (($Mandante->mandante == '21' && $UsuarioMandante->paisId == 243) || $Mandante->mandante == 19) {
    $usersMenu = json_decode(
        '[{"MENU_ID":"3","MENU_TITLE":"Gesti\u00f3n","MENU_SLUG":"gestion","MENU_EDITAR":"true","MENU_ELIMINAR":"false","MENU_ADICIONAR":"true","SUBMENUS":[{"SUBMENU_ID":"136","SUBMENU_URL":"deposito","SUBMENU_TITLE":"Depositar"},{"SUBMENU_ID":"102","SUBMENU_URL":"cuenta_cobro_anular","SUBMENU_TITLE":"Anular Nota Retiro"},{"SUBMENU_ID":"189","SUBMENU_URL":"cuentasbancarias","SUBMENU_TITLE":"Cuentas bancarias"},{"SUBMENU_ID":"2944","SUBMENU_URL":"cuentasdigitales","SUBMENU_TITLE":"Cuentas digitales"},{"SUBMENU_ID":"41","SUBMENU_URL":"cuenta_cobro","SUBMENU_TITLE":"Retirar"},{"SUBMENU_ID":"500","SUBMENU_URL":"verificar_cuenta","SUBMENU_TITLE":"Verificar Cuenta"},{"SUBMENU_ID":"121","SUBMENU_URL":"cambiar-clave","SUBMENU_TITLE":"Cambiar Contrase\u00f1a"},{"SUBMENU_ID":"195","SUBMENU_URL":"misbonos","SUBMENU_TITLE":"Mis Bonos"},{"SUBMENU_ID":"87","SUBMENU_URL":"gestion_cuenta","SUBMENU_TITLE":"Mi Cuenta"}]},{"MENU_ID":"5","MENU_TITLE":"Consultas","MENU_SLUG":"consulta","MENU_EDITAR":"false","MENU_ELIMINAR":"false","MENU_ADICIONAR":"true","SUBMENUS":[{"SUBMENU_ID":"100","SUBMENU_URL":"consulta_tickets_online","SUBMENU_TITLE":"Consulta de apuestas deportivas"},{"SUBMENU_ID":"184","SUBMENU_URL":"consulta_tickets_casino","SUBMENU_TITLE":"Informe de apuestas casino"},{"SUBMENU_ID":"186","SUBMENU_URL":"consulta_depositos","SUBMENU_TITLE":"Consultar depositos"},{"SUBMENU_ID":"188","SUBMENU_URL":"consulta_retiros","SUBMENU_TITLE":"Consultar retiros"}]}]'
    );
}

/* define menús y submenús en formato JSON para un usuario específico. */
if ($Mandante->mandante == '0') {


    $usersMenu = json_decode((
    '[
{"MENU_ID":"3","MENU_TITLE":"Gesti\u00f3n","MENU_SLUG":"gestion","MENU_EDITAR":"true","MENU_ELIMINAR":"false","MENU_ADICIONAR":"true","SUBMENUS":[{"SUBMENU_ID":"136","SUBMENU_URL":"deposito","SUBMENU_TITLE":"Depositar"},{"SUBMENU_ID":"102","SUBMENU_URL":"cuenta_cobro_anular","SUBMENU_TITLE":"Anular Nota Retiro"},{"SUBMENU_ID":"189","SUBMENU_URL":"cuentasbancarias","SUBMENU_TITLE":"Cuentas bancarias"},{"SUBMENU_ID":"500","SUBMENU_URL":"verificar_cuenta","SUBMENU_TITLE":"Verificar Cuenta"},{"SUBMENU_ID":"41","SUBMENU_URL":"cuenta_cobro","SUBMENU_TITLE":"Retirar"},{"SUBMENU_ID":"121","SUBMENU_URL":"cambiar-clave","SUBMENU_TITLE":"Cambiar Contrase\u00f1a"},{"SUBMENU_ID":"195","SUBMENU_URL":"misbonos","SUBMENU_TITLE":"Mis Bonos"},{"SUBMENU_ID":"87","SUBMENU_URL":"gestion_cuenta","SUBMENU_TITLE":"Mi Cuenta"},{"SUBMENU_ID":"88","SUBMENU_URL":"shop-bonuses","SUBMENU_TITLE":"Tienda de premios"}]},

{"MENU_ID":"5","MENU_TITLE":"Consultas","MENU_SLUG":"consulta","MENU_EDITAR":"false","MENU_ELIMINAR":"false","MENU_ADICIONAR":"true","SUBMENUS":[{"SUBMENU_ID":"100","SUBMENU_URL":"consulta_tickets_online","SUBMENU_TITLE":"Consulta de apuestas deportivas"},{"SUBMENU_ID":"184","SUBMENU_URL":"consulta_tickets_casino","SUBMENU_TITLE":"Informe de apuestas casino"},{"SUBMENU_ID":"186","SUBMENU_URL":"consulta_depositos","SUBMENU_TITLE":"Consultar depositos"},{"SUBMENU_ID":"188","SUBMENU_URL":"consulta_retiros","SUBMENU_TITLE":"Consultar retiros"},{"SUBMENU_ID":"88","SUBMENU_URL":"mi_lealtad","SUBMENU_TITLE":"Mi Lealtad"}]}



]'
    ));
}


if ($Mandante->mandante == 19 || ($ConfigurationEnvironment->isDevelopment() && in_array($Mandante->mandante, [10, 18, 0]))) {  // nuevo cambio menu
    $usersMenu = json_decode((
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
                    "SUBMENU_URL": "mi_lealtad",
                    "SUBMENU_TITLE": "Mi Lealtad"
                },
                
                {
                    "SUBMENU_ID": "89",
                    "SUBMENU_URL": "autoexclusion-producto",
                    "SUBMENU_TITLE": "Autoexclusion"
                },
                {
                    "SUBMENU_ID": "90",
                    "SUBMENU_URL": "limitaciones",
                    "SUBMENU_TITLE": "Limitaciones"
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
}



/* verifica condiciones y crea un menú de usuario en formato JSON. */
if ($Mandante->mandante == '0' && $UsuarioMandante->paisId != '173') {

    $usersMenu = json_decode((
    '[
{"MENU_ID":"3","MENU_TITLE":"Gesti\u00f3n","MENU_SLUG":"gestion","MENU_EDITAR":"true","MENU_ELIMINAR":"false","MENU_ADICIONAR":"true","SUBMENUS":[{"SUBMENU_ID":"136","SUBMENU_URL":"deposito","SUBMENU_TITLE":"Depositar"},{"SUBMENU_ID":"102","SUBMENU_URL":"cuenta_cobro_anular","SUBMENU_TITLE":"Anular Nota Retiro"},{"SUBMENU_ID":"189","SUBMENU_URL":"cuentasbancarias","SUBMENU_TITLE":"Cuentas bancarias"},{"SUBMENU_ID":"41","SUBMENU_URL":"cuenta_cobro","SUBMENU_TITLE":"Retirar"},{"SUBMENU_ID":"500","SUBMENU_URL":"verificar_cuenta","SUBMENU_TITLE":"Verificar Cuenta"},{"SUBMENU_ID":"121","SUBMENU_URL":"cambiar-clave","SUBMENU_TITLE":"Cambiar Contrase\u00f1a"},{"SUBMENU_ID":"195","SUBMENU_URL":"misbonos","SUBMENU_TITLE":"Mis Bonos"},{"SUBMENU_ID":"87","SUBMENU_URL":"gestion_cuenta","SUBMENU_TITLE":"Mi Cuenta"}]},

{"MENU_ID":"5","MENU_TITLE":"Consultas","MENU_SLUG":"consulta","MENU_EDITAR":"false","MENU_ELIMINAR":"false","MENU_ADICIONAR":"true","SUBMENUS":[{"SUBMENU_ID":"100","SUBMENU_URL":"consulta_tickets_online","SUBMENU_TITLE":"Consulta de apuestas deportivas"},{"SUBMENU_ID":"184","SUBMENU_URL":"consulta_tickets_casino","SUBMENU_TITLE":"Informe de apuestas casino"},{"SUBMENU_ID":"186","SUBMENU_URL":"consulta_depositos","SUBMENU_TITLE":"Consultar depositos"},{"SUBMENU_ID":"188","SUBMENU_URL":"consulta_retiros","SUBMENU_TITLE":"Consultar retiros"}]}



]'
    ));
}


/* Se verifica una condición para definir un menú de usuario específico en JSON. */
if ($Mandante->mandante == '0' && $UsuarioMandante->paisId == '2') {

    $usersMenu = json_decode((
    '[
{"MENU_ID":"3","MENU_TITLE":"Gesti\u00f3n","MENU_SLUG":"gestion","MENU_EDITAR":"true","MENU_ELIMINAR":"false","MENU_ADICIONAR":"true","SUBMENUS":[{"SUBMENU_ID":"136","SUBMENU_URL":"deposito","SUBMENU_TITLE":"Depositar"},{"SUBMENU_ID":"102","SUBMENU_URL":"cuenta_cobro_anular","SUBMENU_TITLE":"Anular Nota Retiro"},{"SUBMENU_ID":"189","SUBMENU_URL":"cuentasbancarias","SUBMENU_TITLE":"Cuentas bancarias"},{"SUBMENU_ID":"41","SUBMENU_URL":"cuenta_cobro","SUBMENU_TITLE":"Retirar"},{"SUBMENU_ID":"121","SUBMENU_URL":"cambiar-clave","SUBMENU_TITLE":"Cambiar Contrase\u00f1a"},{"SUBMENU_ID":"195","SUBMENU_URL":"misbonos","SUBMENU_TITLE":"Mis Bonos"},{"SUBMENU_ID":"87","SUBMENU_URL":"gestion_cuenta","SUBMENU_TITLE":"Mi Cuenta"}]},

{"MENU_ID":"5","MENU_TITLE":"Consultas","MENU_SLUG":"consulta","MENU_EDITAR":"false","MENU_ELIMINAR":"false","MENU_ADICIONAR":"true","SUBMENUS":[{"SUBMENU_ID":"100","SUBMENU_URL":"consulta_tickets_online","SUBMENU_TITLE":"Consulta de apuestas deportivas"},{"SUBMENU_ID":"184","SUBMENU_URL":"consulta_tickets_casino","SUBMENU_TITLE":"Informe de apuestas casino"},{"SUBMENU_ID":"186","SUBMENU_URL":"consulta_depositos","SUBMENU_TITLE":"Consultar depositos"},{"SUBMENU_ID":"188","SUBMENU_URL":"consulta_retiros","SUBMENU_TITLE":"Consultar retiros"}]}



]'
    ));
}


/* Asigna un menú de opciones basado en la condición del objeto Mandante. */
if ($Mandante->mandante == '2') {

    $usersMenu = json_decode((
    '[{"MENU_ID":"3","MENU_TITLE":"Gesti\u00f3n","MENU_SLUG":"gestion","MENU_EDITAR":"true","MENU_ELIMINAR":"false","MENU_ADICIONAR":"true","SUBMENUS":[{"SUBMENU_ID":"136","SUBMENU_URL":"deposito","SUBMENU_TITLE":"Depositar"},{"SUBMENU_ID":"102","SUBMENU_URL":"cuenta_cobro_anular","SUBMENU_TITLE":"Anular Nota Retiro"},{"SUBMENU_ID":"189","SUBMENU_URL":"cuentasbancarias","SUBMENU_TITLE":"Cuentas bancarias"},{"SUBMENU_ID":"41","SUBMENU_URL":"cuenta_cobro","SUBMENU_TITLE":"Retirar"},{"SUBMENU_ID":"500","SUBMENU_URL":"verificar_cuenta","SUBMENU_TITLE":"Verificar Cuenta"},{"SUBMENU_ID":"121","SUBMENU_URL":"cambiar-clave","SUBMENU_TITLE":"Cambiar Contrase\u00f1a"},{"SUBMENU_ID":"195","SUBMENU_URL":"misbonos","SUBMENU_TITLE":"Mis Bonos"},{"SUBMENU_ID":"87","SUBMENU_URL":"gestion_cuenta","SUBMENU_TITLE":"Mi Cuenta"},{"SUBMENU_ID":"88","SUBMENU_URL":"tarjetas-credito","SUBMENU_TITLE":"Manage Cards"}]},{"MENU_ID":"5","MENU_TITLE":"Consultas","MENU_SLUG":"consulta","MENU_EDITAR":"false","MENU_ELIMINAR":"false","MENU_ADICIONAR":"true","SUBMENUS":[{"SUBMENU_ID":"100","SUBMENU_URL":"consulta_tickets_online","SUBMENU_TITLE":"Consulta de apuestas deportivas"},{"SUBMENU_ID":"184","SUBMENU_URL":"consulta_tickets_casino","SUBMENU_TITLE":"Informe de apuestas casino"},{"SUBMENU_ID":"186","SUBMENU_URL":"consulta_depositos","SUBMENU_TITLE":"Consultar depositos"},{"SUBMENU_ID":"188","SUBMENU_URL":"consulta_retiros","SUBMENU_TITLE":"Consultar retiros"}]}]'
    ));

}


/* Define menús y submenús en formato JSON para un usuario específico. */
if ($Mandante->mandante == '14') {

    $usersMenu = json_decode((
    '[{"MENU_ID":"3","MENU_TITLE":"Gesti\u00f3n","MENU_SLUG":"gestion","MENU_EDITAR":"true","MENU_ELIMINAR":"false","MENU_ADICIONAR":"true","SUBMENUS":[{"SUBMENU_ID":"136","SUBMENU_URL":"deposito","SUBMENU_TITLE":"Depositar"},{"SUBMENU_ID":"189","SUBMENU_URL":"cuentasbancarias","SUBMENU_TITLE":"Cuentas bancarias"},{"SUBMENU_ID":"41","SUBMENU_URL":"cuenta_cobro","SUBMENU_TITLE":"Retirar"},{"SUBMENU_ID":"500","SUBMENU_URL":"verificar_cuenta","SUBMENU_TITLE":"Verificar Cuenta"},{"SUBMENU_ID":"121","SUBMENU_URL":"cambiar-clave","SUBMENU_TITLE":"Cambiar Contrase\u00f1a"},{"SUBMENU_ID":"195","SUBMENU_URL":"misbonos","SUBMENU_TITLE":"Mis Bonos"},{"SUBMENU_ID":"87","SUBMENU_URL":"gestion_cuenta","SUBMENU_TITLE":"Mi Cuenta"}]},{"MENU_ID":"5","MENU_TITLE":"Consultas","MENU_SLUG":"consulta","MENU_EDITAR":"false","MENU_ELIMINAR":"false","MENU_ADICIONAR":"true","SUBMENUS":[{"SUBMENU_ID":"100","SUBMENU_URL":"consulta_tickets_online","SUBMENU_TITLE":"Consulta de apuestas deportivas"},{"SUBMENU_ID":"184","SUBMENU_URL":"consulta_tickets_casino","SUBMENU_TITLE":"Informe de apuestas casino"},{"SUBMENU_ID":"186","SUBMENU_URL":"consulta_depositos","SUBMENU_TITLE":"Consultar depositos"},{"SUBMENU_ID":"188","SUBMENU_URL":"consulta_retiros","SUBMENU_TITLE":"Consultar retiros"}]}]'
    ));

}

/* Condicional que define un menú de usuario basado en su identificación de mandante. */
if ($Mandante->mandante == '16') {


    $usersMenu = json_decode((
    '[
{"MENU_ID":"3","MENU_TITLE":"Gesti\u00f3n","MENU_SLUG":"gestion","MENU_EDITAR":"true","MENU_ELIMINAR":"false","MENU_ADICIONAR":"true","SUBMENUS":[{"SUBMENU_ID":"136","SUBMENU_URL":"deposito","SUBMENU_TITLE":"Depositar"},{"SUBMENU_ID":"102","SUBMENU_URL":"cuenta_cobro_anular","SUBMENU_TITLE":"Anular Nota Retiro"},{"SUBMENU_ID":"189","SUBMENU_URL":"cuentasbancarias","SUBMENU_TITLE":"Cuentas bancarias"},{"SUBMENU_ID":"41","SUBMENU_URL":"cuenta_cobro","SUBMENU_TITLE":"Retirar"},{"SUBMENU_ID":"121","SUBMENU_URL":"cambiar-clave","SUBMENU_TITLE":"Cambiar Contrase\u00f1a"},{"SUBMENU_ID":"195","SUBMENU_URL":"misbonos","SUBMENU_TITLE":"Mis Bonos"},{"SUBMENU_ID":"87","SUBMENU_URL":"gestion_cuenta","SUBMENU_TITLE":"Mi Cuenta"}]},

{"MENU_ID":"5","MENU_TITLE":"Consultas","MENU_SLUG":"consulta","MENU_EDITAR":"false","MENU_ELIMINAR":"false","MENU_ADICIONAR":"true","SUBMENUS":[{"SUBMENU_ID":"100","SUBMENU_URL":"consulta_tickets_online","SUBMENU_TITLE":"Consulta de apuestas deportivas"},{"SUBMENU_ID":"186","SUBMENU_URL":"consulta_depositos","SUBMENU_TITLE":"Consultar depositos"},{"SUBMENU_ID":"188","SUBMENU_URL":"consulta_retiros","SUBMENU_TITLE":"Consultar retiros"}]}



]'
    ));

}

/* Se decodifica un menú JSON para un mandante específico en el sistema. */
if ($Mandante->mandante == '17') {


    $usersMenu = json_decode((
    '[
{"MENU_ID":"3","MENU_TITLE":"Gesti\u00f3n","MENU_SLUG":"gestion","MENU_EDITAR":"true","MENU_ELIMINAR":"false","MENU_ADICIONAR":"true","SUBMENUS":[{"SUBMENU_ID":"136","SUBMENU_URL":"deposito","SUBMENU_TITLE":"Depositar"},{"SUBMENU_ID":"189","SUBMENU_URL":"cuentasbancarias","SUBMENU_TITLE":"Cuentas bancarias"},{"SUBMENU_ID":"41","SUBMENU_URL":"cuenta_cobro","SUBMENU_TITLE":"Retirar"},{"SUBMENU_ID":"500","SUBMENU_URL":"verificar_cuenta","SUBMENU_TITLE":"Verificar Cuenta"},{"SUBMENU_ID":"121","SUBMENU_URL":"cambiar-clave","SUBMENU_TITLE":"Cambiar Contrase\u00f1a"},{"SUBMENU_ID":"195","SUBMENU_URL":"misbonos","SUBMENU_TITLE":"Mis Bonos"},{"SUBMENU_ID":"87","SUBMENU_URL":"gestion_cuenta","SUBMENU_TITLE":"Mi Cuenta"}]},

{"MENU_ID":"5","MENU_TITLE":"Consultas","MENU_SLUG":"consulta","MENU_EDITAR":"false","MENU_ELIMINAR":"false","MENU_ADICIONAR":"true","SUBMENUS":[{"SUBMENU_ID":"100","SUBMENU_URL":"consulta_tickets_online","SUBMENU_TITLE":"Consulta de apuestas deportivas"},{"SUBMENU_ID":"184","SUBMENU_URL":"consulta_tickets_casino","SUBMENU_TITLE":"Informe de apuestas casino"},{"SUBMENU_ID":"186","SUBMENU_URL":"consulta_depositos","SUBMENU_TITLE":"Consultar depositos"},{"SUBMENU_ID":"188","SUBMENU_URL":"consulta_retiros","SUBMENU_TITLE":"Consultar retiros"}]}



]'
    ));

}



/* crea una respuesta con datos y registra información en el sistema de logs. */
$response["data"] = array(
    "data" => $data,
    "user_menus" => $usersMenu,
    "subid" => "7040" . $json->session->sid . "1",
);

try {

    //syslog(LOG_WARNING, "7 STARTRIDABK  :" . $json->rid . '_' . $UsuarioMandanteSite->usumandanteId . ' ' . (microtime(true) - $start));
} catch (Exception $e) {
    /* Manejo de excepciones en PHP, captura errores sin realizar ninguna acción específica. */
}