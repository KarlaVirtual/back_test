<?php

use Backend\dto\Banco;
use Backend\dto\BonoDetalle;
use Backend\dto\Categoria;
use Backend\dto\Clasificador;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Consecutivo;
use Backend\dto\CuentaCobro;
use Backend\dto\Descarga;
use Backend\dto\DocumentoUsuario;
use Backend\dto\IntApuestaDetalle;
use Backend\dto\IntCompetencia;
use Backend\dto\IntDeporte;
use Backend\dto\IntEventoApuesta;
use Backend\dto\IntEventoApuestaDetalle;
use Backend\dto\IntEventoDetalle;
use Backend\dto\IntRegion;
use Backend\dto\ItTicketEnc;
use Backend\dto\Mandante;
use Backend\dto\MandanteDetalle;
use Backend\dto\PaisMoneda;
use Backend\dto\PromocionalLog;
use Backend\dto\PuntoVenta;
use Backend\dto\Registro;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioBanco;
use Backend\dto\UsuarioConfig;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\UsuarioLog;
use Backend\dto\UsuarioLog2;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioOtrainfo;
use Backend\dto\UsuarioPerfil;
use Backend\dto\UsuarioPremiomax;
use Backend\dto\UsuarioPublicidad;
use Backend\dto\UsuarioRecarga;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioMensaje;
use Backend\dto\ProductoMandante;
use Backend\dto\ProdMandanteTipo;
use Backend\dto\Proveedor;
use Backend\dto\Producto;
use Backend\dto\Pais;
use Backend\dto\UsuarioVerificacion;
use Backend\integrations\auth\OcrBigId;
use Backend\integrations\casino\IES;
use Backend\integrations\casino\JOINSERVICES;
use Backend\mysql\ConsecutivoMySqlDAO;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\DocumentoUsuarioMySqlDAO;
use Backend\mysql\PromocionalLogMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\mysql\RegistroMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\UsuarioBancoMySqlDAO;
use Backend\mysql\UsuarioBannerMySqlDAO;
use Backend\mysql\UsuarioConfiguracionMySqlDAO;
use Backend\mysql\UsuarioLog2MySqlDAO;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\mysql\UsuarioMandanteMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\UsuarioOtrainfoMySqlDAO;
use Backend\mysql\UsuarioPerfilMySqlDAO;
use Backend\mysql\UsuarioPremiomaxMySqlDAO;
use Backend\mysql\UsuarioRecargaMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\mysql\UsuarioVerificacionMySqlDAO;
use Backend\websocket\WebsocketUsuario;

/**
 * command/update_user2
 *
 * Actualizar un usuario en la plataforma versión 2
 *
 * @param string $clave : clave del usuario para verificar el usuario
 * @param string $direccion : direccion del usuario a actualizar
 * @param string $ciudad : ciudad del usuario a actualizar
 * @param string $docnumber2 : Documento de identidad del usuario a actualizar
 * @param string $celular : numero celular del usuario a actualizar
 * @param string $receive_advertising : si permite o no envio de publicidad (S o N)
 * @param string $birth_date : Fecha de cumpleaños del usuario a actualizar
 * @param string $second_name : segundo nombre del usuario a actualizar
 * @param string $second_sur_name : segundo apellido del usuario a actualizar
 * @param string $user_info : Información general del usuario a actualizar
 * @param string $city_id : id de la ciudad del usuario a actualizar
 * @param string $department_id : id del departamento del usuario a actualizar
 * @param string $cp : Codigo postal del usuario a actualizar
 * @param string $email : Email del usuario a actualizar
 * @param string $nationality_id : id de la nacionalidad del usuario
 * @param string $genero : genero del usuario
 * @param string $rfc : Ocupación del usuario
 * @param string $image_data : Imagen del usuario DNI
 * @param string $image_data_back :  Imagen posterior del DNI del usuario
 * @param string $image_data_services : Imagen de los servicios del usario
 * @param string $image_data_selfie : Imagen selfie del usuario
 *
 * @return object El objeto $response es un array con los siguientes atributos:
 *  - *code* (int): Codigo de error desde el proveedor 0 para exito
 *  - *data* (array): contiene estado de la solicitud y token de autentificacion del usuario.
 *
 * @throws Exception Si no existen los parametros
 * @throws Exception Su cuenta ya se encuentra en estado de verificacion
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */

/* Obtiene la hora actual en formato de 24 horas */
$hour = date('H');

// Obtiene el día actual del mes
$day = date('d');

$UsuarioMandante = new UsuarioMandante($json->session->usuario);

if ($json->session->usuario != "") {
    try {
        $UsuarioToken = new UsuarioToken("", "1", $UsuarioMandante->getUsumandanteId());

    } catch (
    Exception $e
    ) {
        $UsuarioToken = new UsuarioToken();

        $UsuarioToken->setRequestId(0);
        $UsuarioToken->setProveedorId(1);
        $UsuarioToken->setUsuarioId($UsuarioMandante->getUsumandanteId());
        $UsuarioToken->setToken($UsuarioToken->createToken());

        $UsuarioToken->setCookie('');
        $UsuarioToken->setUsumodifId(0);
        $UsuarioToken->setUsucreaId(0);
        $UsuarioToken->setSaldo(0);

        /**
         * Interactúa con la capa de acceso a datos para insertar el token de usuario
         * y confirmar la transacción en la base de datos.
         *
         */
        $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
        $UsuarioTokenMySqlDAO->insert($UsuarioToken);
        $UsuarioTokenMySqlDAO->getTransaction()->commit();
    }

    $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
    $Registro = new Registro("", $Usuario->usuarioId);
    $UsuarioOtrainfo = new UsuarioOtrainfo($Usuario->usuarioId);

    $clave = $json->params->user_info->password;
    $direccion = $json->params->user_info->address;
    $ciudad = $json->params->user_info->city_id;
    $ciudad = $json->params->user_info->city_id;
    $celular = $json->params->user_info->phone;

    $nationality_id = $json->params->user_info->nationality_id;

    $birth_date = $json->params->user_info->birth_date;
    $second_name = $json->params->user_info->second_name;
    $second_sur_name = $json->params->user_info->second_sur_name;

    if ($Usuario->origen == 2 && ($Usuario->fechaCrea == $Usuario->fechaActualizacion)) {

        /**
         * Obtiene la información del usuario a partir de los parámetros JSON.
         */

        $user_info = $json->params->user_info;

        $address = $user_info->address;
        $birth_date = $user_info->birth_date;
        $country_code = $user_info->country_code;
        $rfc = $user_info->rfc;
        $code = $user_info->code;
        $currency_name = $user_info->currency_name;
        $department_id = $user_info->department_id;
        $docnumber = $user_info->docnumber;
        $doctype_id = $user_info->doctype_id;
        $email = $user_info->email;
        $email2 = $user_info->email2;
        $expedition_day = $user_info->expedition_day;
        $expedition_month = $user_info->expedition_month;
        $expedition_year = $user_info->expedition_year;
        $first_name = $user_info->first_name;
        $gender = $user_info->gender;
        $landline_number = $user_info->landline_number;
        $lang_code = $user_info->lang_code;
        $language = $user_info->language;
        $last_name = $user_info->last_name;
        $limit_deposit_day = $user_info->limit_deposit_day;
        $limit_deposit_month = $user_info->limit_deposit_month;
        $limit_deposit_week = $user_info->limit_deposit_week;
        $middle_name = $user_info->middle_name;
        $nationality_id = $user_info->nationality_id;
        $password = $user_info->password;
        $phone = $user_info->phone;
        $second_last_name = $user_info->second_last_name;
        $site_id = $user_info->site_id;


        $nombre = $first_name . " " . $middle_name . " " . $last_name . " " . $second_last_name;

        $Usuario->nombre = $nombre;

        // Se establecen los datos del registro utilizando los métodos de la clase $Registro

        $Registro->setNombre($nombre);
        $Registro->setCelular($phone);
        $Registro->setCiudadId(1);
        $Registro->setNombre1($first_name);
        $Registro->setNombre2($middle_name);
        $Registro->setApellido1($last_name);
        $Registro->setApellido2($second_last_name);
        $Registro->setSexo($gender);
        $Registro->setTipoDoc($doctype_id);
        $Registro->setDireccion($address);
        $Registro->setTelefono($landline_number);
        $Registro->setCiudnacimId(1);
        $Registro->setNacionalidadId($nationality_id->code);
        //$Registro->setDirIp($dir_ip);

        $UsuarioMySqlDAO = new UsuarioMySqlDAO();
        $Transaction = $UsuarioMySqlDAO->getTransaction();

        $UsuarioMySqlDAO->update($Usuario);

        $RegistroMySqlDAO = new RegistroMySqlDAO($Transaction);

        $RegistroMySqlDAO->update($Registro);

        $Transaction->commit();


    } else {

        if ($UsuarioMandante->mandante == 6) {
            $genero = $json->params->user_info->sex;
            $direccion = $json->params->user_info->address;
            $celular = $json->params->user_info->phone;
            $city_id = $json->params->user_info->city_id;
            $department_id = $json->params->user_info->department_id;
            $cp = $json->params->user_info->cp;
            $rfc = $json->params->user_info->rfc;
            $nationality_id = $json->params->user_info->nationality_id;

            if (is_object($city_id)) {
                $ciudad = $city_id->Id;
            }

            $codigopostal = $cp;

            $image_data = $json->params->image_data;
            $image_data_back = $json->params->image_data_back;
            $image_data_services = $json->params->image_data_services;

        } else {


            $genero = $json->params->user_info->sex;
            $direccion = $json->params->user_info->address;
            $celular = $json->params->user_info->phone;
            $city_id = $json->params->user_info->city_id;
            $department_id = $json->params->user_info->department_id;
            $cp = $json->params->user_info->cp;

            if (is_object($city_id)) {
                $ciudad = $city_id->Id;
            }
            $codigopostal = $cp;

            $image_data = $json->params->image_data;
            $image_data_back = $json->params->image_data_back;
            $image_data_services = $json->params->image_data_services;
            $image_data_selfie = $json->params->image_data_selfie;
        }

        // $response = array("code" => 0, "rid" => "15063077673908", "data" => array("subid" => "-7031402156054098668", "data" => array("profile" => array("26678955" => array("id" => 26678955, "unique_id" => 26678955, "username" => "danielftg@hotmail.com", "name" => "Daniel Tamayo", "first_name" => "Daniel", "last_name" => "Tamayo", "gender" => "M", "email" => "danielftg@hotmail.com", "phone" => "573012976239", "reg_info_incomplete" => false, "address" => "calle 100 c sur", "reg_date" => "2017-08-13", "birth_date" => "1994-11-20", "doc_number" => "1026152151", "casino_promo" => null, "currency_name" => "USD", "currency_id" => "USD", "balance" => 5.0, "casino_balance" => 5.0, "exclude_date" => null, "bonus_id" => -1, "games" => 0, "super_bet" => -1, "country_code" => "CO", "doc_issued_by" => null, "doc_issue_date" => null, "doc_issue_code" => null, "province" => null, "iban" => null, "active_step" => null, "active_step_state" => null, "subscribed_to_news" => false, "bonus_balance" => 0.0, "frozen_balance" => 0.0, "bonus_win_balance" => 0.0, "city" => "Manizales", "has_free_bets" => false, "loyalty_point" => 0.0, "loyalty_earned_points" => 0.0, "loyalty_exchanged_points" => 0.0, "loyalty_level_id" => null, "affiliate_id" => null, "is_verified" => false, "incorrect_fields" => null, "loyalty_point_usage_period" => 0, "loyalty_min_exchange_point" => 0, "loyalty_max_exchange_point" => 0, "active_time_in_casino" => null, "last_read_message" => null, "unread_count" => 0, "last_login_date" => 1506281782, "swift_code" => null, "bonus_money" => 0.0, "loyalty_last_earned_points" => 0.0)))));
        $UsuarioClave = $Usuario->checkClave($clave);


        try {
            $UsuarioVerificacion = new UsuarioVerificacion('', $Usuario->usuarioId, 'USUVERIFICACION', 'P');
        } catch (Exception $ex) {
        }
        // Verifica si ya existe un proceso de verificación para el usuario y lanza una excepción si es así.
        if (isset($UsuarioVerificacion)) throw new Exception('Su cuenta ya se encuentra en estado de verificacion', 100086);

        $needs_approval = '';

        $ConfigurationEnvironment = new ConfigurationEnvironment();

        try {
            // Crea un objeto Clasificador para la aplicación "APPCHANPERSONALINF"
            $Clasificador = new Clasificador('', 'APPCHANPERSONALINF');
            // Crea un objeto MandanteDetalle utilizando información del usuario y clasificador
            $MandanteDetalle = new MandanteDetalle('', $Usuario->mandante, $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');

            // Obtiene un valor de detalle del mandante
            $needs_approval = $MandanteDetalle->getValor();
        } catch (Exception $ex) {
        }
        // Crea una instancia de UsuarioLog2MySqlDAO para manejar las transacciones
        $UsuarioLog2MySqlDAO = new UsuarioLog2MySqlDAO();
        $Transaction = $UsuarioLog2MySqlDAO->getTransaction();

        // Crea instancias de DAOs para manejar diferentes aspectos de los usuarios en la base de datos
        $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);
        $RegistroMySqlDAO = new RegistroMySqlDAO($Transaction);
        $UsuarioMandanteMySqlDAO = new UsuarioMandanteMySqlDAO($Transaction);
        $UsuarioOtrainfoMySqlDAO = new UsuarioOtrainfoMySqlDAO($Transaction);

        // Inicializa flags para actualizaciones
        $UpdateUSER = false;
        $UpdateREGISTRO = false;
        $UpdateUSUMANDANTE = false;
        $UpdateUSUOTRAINFO = false;

        // Verifica si el mandante cumple con ciertas condiciones para la aprobación
        if (
            (
                ($Usuario->mandante == 8) ||
                ($Usuario->mandante == 0 && $Usuario->paisId == 46) ||
                ($Usuario->mandante == 0 && $Usuario->paisId == 66) || ($Usuario->mandante == 0 && $Usuario->paisId == 94) ||
                ($Usuario->mandante == 13) || ($Usuario->mandante == 14)
            )) {

            try {
                // Crea un objeto Clasificador para la verificación manual
                $Clasificador = new Clasificador('', 'VERIFICAMANUAL');
            } catch (Exception $ex) {
            }


            try {
                // Crea un nuevo objeto de verificación de usuario con el estado 'P' (pendiente)
                $UsuarioVerificacion = new UsuarioVerificacion('', $Usuario->usuarioId, 'P');
            } catch (Exception $ex) {
            }
            // Verifica nuevamente si el usuario ya está en estado de verificación y lanza una excepción
            if (isset($UsuarioVerificacion)) throw new Exception('Su cuenta ya se encuentra en estado de verificacion', 100086);

            // Se establecen los atributos del objeto UsuarioVerificacion con los datos del objeto UsuarioMandante

            $UsuarioVerificacion = new UsuarioVerificacion();
            $UsuarioVerificacion->setUsuarioId($UsuarioMandante->usuarioMandante);
            $UsuarioVerificacion->setMandante($UsuarioMandante->mandante);
            $UsuarioVerificacion->setPaisId($UsuarioMandante->paisId);
            $UsuarioVerificacion->setTipo('USUVERIFICACION');
            $UsuarioVerificacion->setEstado('P');
            $UsuarioVerificacion->setObservacion('');
            $UsuarioVerificacion->setUsucreaId($UsuarioMandante->usuarioMandante);
            $UsuarioVerificacion->setClasificadorId($Clasificador->clasificadorId ?: '0');

            $UsuarioVerificacionMySqlDAO = new UsuarioVerificacionMySqlDAO($Transaction);
            $ID = $UsuarioVerificacionMySqlDAO->insert($UsuarioVerificacion);
        }

        if (empty((array) $image_data))  throw new Exception('Error al cargar imagen', 300192);

        if ($image_data != "") {
            /**
             * Actualiza el estado del usuario y registra información en el log de usuarios.
             *
             * Si se necesita aprobación (código 'I'), se actualizan ciertos atributos del usuario.
             * De lo contrario, se prepara un registro en el log de usuarios con base en los datos proporcionados.
             */
            $UpdateUSER = true;
            if ($needs_approval === 'I') {
                $Usuario->verifcedulaAnt = 'S';
                $Usuario->estadoJugador = 'A' . substr($Usuario->estadoJugador, 1, 1);
            } else {
                $tipo = 'USUDNIANTERIOR';
                $pos = strpos($image_data, 'base64,');
                $file_contents1 = base64_decode(substr($image_data, $pos + 7));
                $file_contents1 = addslashes($file_contents1);

                $Usuario->estadoJugador = 'P' . substr($Usuario->estadoJugador, 1, 1);

                $UsuarioLog = new UsuarioLog2();
                $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                $UsuarioLog->setUsuarioIp($json->session->usuarioip);
                $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
                $UsuarioLog->setUsuariosolicitaIp($json->session->usuarioip);
                $UsuarioLog->setUsuarioaprobarId(0);
                $UsuarioLog->setTipo($tipo);
                $UsuarioLog->setEstado("P");
                $UsuarioLog->setValorAntes('');
                $UsuarioLog->setValorDespues('');
                $UsuarioLog->setUsucreaId(0);
                $UsuarioLog->setUsumodifId(0);
                $UsuarioLog->setImagen($file_contents1);
                if (
                    (
                        ($Usuario->mandante == 8) ||
                        ($Usuario->mandante == 0 && $Usuario->paisId == 46) ||
                        ($Usuario->mandante == 0 && $Usuario->paisId == 66) || ($Usuario->mandante == 0 && $Usuario->paisId == 94) ||
                        ($Usuario->mandante == 13)
                    )


                ) {
                    $UsuarioLog->setSversion($ID);
                }

                $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);

                $UsuarioLogMySqlDAO2->insert($UsuarioLog);


            }

            // Verifica si se han recibido datos de imagen

            if (empty((array) $image_data_back))  throw new Exception('Error al cargar imagen', 3001923);

            if ($image_data_back != "") {
                $UpdateUSER = true;
                if ($needs_approval === 'I') {
                    // Establece la verificación de cédula y modifica el estado del jugador
                    $Usuario->verifcedulaPost = 'S';
                    $Usuario->estadoJugador = substr($Usuario->estadoJugador, 0, 1) . 'A';
                } else {
                    // Se define el tipo de usuario para el log
                    $tipo = 'USUDNIPOSTERIOR';
                    $pos = strpos($image_data_back, 'base64,');
                    $file_contents1 = base64_decode(substr($image_data_back, $pos + 7));
                    $file_contents1 = addslashes($file_contents1);


                    $Usuario->estadoJugador = substr($Usuario->estadoJugador, 0, 1) . 'P';

                    $UsuarioLog = new UsuarioLog2();
                    $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                    $UsuarioLog->setUsuarioIp($json->session->usuarioip);
                    $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
                    $UsuarioLog->setUsuariosolicitaIp($json->session->usuarioip);
                    $UsuarioLog->setUsuarioaprobarId(0);
                    $UsuarioLog->setTipo($tipo);
                    $UsuarioLog->setEstado("P");
                    $UsuarioLog->setValorAntes('');
                    $UsuarioLog->setValorDespues('');
                    $UsuarioLog->setUsucreaId(0);
                    $UsuarioLog->setUsumodifId(0);
                    $UsuarioLog->setImagen($file_contents1);
                    // Verifica condiciones para establecer la versión del usuario
                    if (
                        (
                            ($Usuario->mandante == 8) ||
                            ($Usuario->mandante == 0 && $Usuario->paisId == 46) ||
                            ($Usuario->mandante == 0 && $Usuario->paisId == 66) || ($Usuario->mandante == 0 && $Usuario->paisId == 94) ||
                            ($Usuario->mandante == 13)
                        )) {
                        $UsuarioLog->setSversion($ID);
                    }


                    $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);

                    $UsuarioLogMySqlDAO2->insert($UsuarioLog);

                }
            }

            // Verifica si hay datos de imagen
            if ($image_data_services != "") {
                if ($needs_approval === 'I') {
                    $UpdateUSER = true; // Marca que el usuario debe ser actualizado
                    $Usuario->verifDomicilio = 'S'; // Establece verificación de domicilio
                } else {
                    $tipo = 'USUVERDOM'; // Tipo de log de usuario
                    // Encuentra la posición de 'base64,' en los datos de imagen
                    $pos = strpos($image_data_services, 'base64,');
                    // Decodifica la imagen desde base64
                    $file_contents1 = base64_decode(substr($image_data_services, $pos + 7));
                    // Escapa los caracteres especiales para la base de datos
                    $file_contents1 = addslashes($file_contents1);

                    // Crea una nueva instancia de UsuarioLog2
                    $UsuarioLog = new UsuarioLog2();
                    // Establece el ID del usuario
                    $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                    // Establece la IP del usuario
                    $UsuarioLog->setUsuarioIp($json->session->usuarioip);
                    // Establece el ID del usuario que solicita
                    $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
                    // Establece la IP del usuario que solicita
                    $UsuarioLog->setUsuariosolicitaIp($json->session->usuarioip);
                    // Establece el ID del usuario que aprueba
                    $UsuarioLog->setUsuarioaprobarId(0);
                    // Establece el tipo de log
                    $UsuarioLog->setTipo($tipo);
                    // Establece el estado como "P" (pendiente)
                    $UsuarioLog->setEstado("P");
                    // Establece valores antes del cambio
                    $UsuarioLog->setValorAntes('');
                    // Establece valores después del cambio
                    $UsuarioLog->setValorDespues('');
                    // Establece ID de quien crea el log
                    $UsuarioLog->setUsucreaId(0);
                    // Establece ID de quien modifica el log
                    $UsuarioLog->setUsumodifId(0);
                    // Establece la imagen decodificada
                    $UsuarioLog->setImagen($file_contents1);
                    // Condiciones para establecer la versión del usuario
                    if (
                        (
                            ($Usuario->mandante == 8) ||
                            ($Usuario->mandante == 0 && $Usuario->paisId == 46) ||
                            ($Usuario->mandante == 0 && $Usuario->paisId == 66) || ($Usuario->mandante == 0 && $Usuario->paisId == 94) ||
                            ($Usuario->mandante == 13)
                        )) {
                        // Establece la versión de usuario
                        $UsuarioLog->setSversion($ID);
                    }

                    // Crea una nueva instancia del DAO para UsuarioLog2 con la transacción
                    $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);
                    // Inserta el log de usuario en la base de datos
                    $UsuarioLogMySqlDAO2->insert($UsuarioLog);

                }
            }

            /**
             * Verifica si la dirección ha cambiado y no está vacía.
             * Si necesita aprobación, se actualiza el registro.
             * De lo contrario, se registra un log del cambio de dirección.
             */
            if ($direccion != $Registro->getDireccion() && $direccion != '') {
                if ($needs_approval === 'I') {
                    $UpdateREGISTRO = true;
                    $Registro->direccion = $direccion;
                } else {
                    $UsuarioLog = new UsuarioLog2();

                    $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                    $UsuarioLog->setUsuarioIp('');

                    $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
                    $UsuarioLog->setUsuariosolicitaIp($json->session->usuarioip);

                    $UsuarioLog->setTipo("USUDIRECCION");
                    $UsuarioLog->setEstado("P");
                    $UsuarioLog->setValorAntes($Registro->getDireccion());
                    $UsuarioLog->setValorDespues($direccion);
                    $UsuarioLog->setUsucreaId(0);
                    $UsuarioLog->setUsumodifId(0);
                    if (
                        (
                            ($Usuario->mandante == 8) ||
                            ($Usuario->mandante == 0 && $Usuario->paisId == 46) ||
                            ($Usuario->mandante == 0 && $Usuario->paisId == 66) || ($Usuario->mandante == 0 && $Usuario->paisId == 94) ||
                            ($Usuario->mandante == 13)
                        )) {
                        $UsuarioLog->setSversion($ID);
                    }


                    $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);
                    $UsuarioLogMySqlDAO2->insert($UsuarioLog);

                }

            }
            /**
             * Verifica si el RFC es diferente del origen de fondos registrado y si el RFC no está vacío.
             * Si necesita aprobación, se actualiza el registro; de lo contrario, se registra un log del usuario.
             */
            if ($rfc != $Registro->getOrigenFondos() && $rfc != '') {
                if ($needs_approval === 'I') {
                    $UpdateREGISTRO = true;
                    $Registro->origenFondos = $rfc;
                } else {
                    $UsuarioLog = new UsuarioLog2();
                    $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                    $UsuarioLog->setUsuarioIp('');

                    $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
                    $UsuarioLog->setUsuariosolicitaIp($json->session->usuarioip);

                    $UsuarioLog->setTipo("RFC");
                    $UsuarioLog->setEstado("P");
                    $UsuarioLog->setValorAntes($Registro->getOrigenFondos());
                    $UsuarioLog->setValorDespues($rfc);
                    $UsuarioLog->setUsucreaId(0);
                    $UsuarioLog->setUsumodifId(0);
                    if (
                        (
                            ($Usuario->mandante == 8) ||
                            ($Usuario->mandante == 0 && $Usuario->paisId == 46) ||
                            ($Usuario->mandante == 0 && $Usuario->paisId == 66) || ($Usuario->mandante == 0 && $Usuario->paisId == 94) ||
                            ($Usuario->mandante == 13)
                        )) {
                        $UsuarioLog->setSversion($ID);
                    }

                    $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);
                    $UsuarioLogMySqlDAO2->insert($UsuarioLog);
                }

            }
            /**
             * Verifica si el género proporcionado es diferente al género del registro
             * y si este no es una cadena vacía. Si es así, se determina si se necesita
             * aprobación para actualizar el registro o crear un log de usuario.
             */
            if ($genero != $Registro->getSexo() && $genero != '') {
                if ($needs_approval === 'I') {
                    $UpdateREGISTRO = true;
                    $Registro->sexo = $genero;
                } else {
                    $UsuarioLog = new UsuarioLog2();
                    $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                    $UsuarioLog->setUsuarioIp('');

                    $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
                    $UsuarioLog->setUsuariosolicitaIp($json->session->usuarioip);

                    $UsuarioLog->setTipo("USUGENERO");
                    $UsuarioLog->setEstado("P");
                    $UsuarioLog->setValorAntes($Registro->getSexo());
                    $UsuarioLog->setValorDespues($genero);
                    $UsuarioLog->setUsucreaId(0);
                    $UsuarioLog->setUsumodifId(0);
                    if (
                        (
                            ($Usuario->mandante == 8) ||
                            ($Usuario->mandante == 0 && $Usuario->paisId == 46) ||
                            ($Usuario->mandante == 0 && $Usuario->paisId == 66) || ($Usuario->mandante == 0 && $Usuario->paisId == 94) ||
                            ($Usuario->mandante == 13)
                        )) {
                        $UsuarioLog->setSversion($ID);
                    }

                    $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);
                    $UsuarioLogMySqlDAO2->insert($UsuarioLog);
                }
            }
            // Verificar si el celular es diferente al registrado y no está vacío
            if ($celular != $Registro->getCelular() && $celular != '') {
                if ($needs_approval === 'I') {
                    $UpdateREGISTRO = true; // Se marca para actualización
                    $Registro->celular = $celular; // Se actualiza el celular en el registro
                } else {
                    $UsuarioLog = new UsuarioLog2(); // Se crea un nuevo registro de log de usuario
                    $UsuarioLog->setUsuarioId($Usuario->usuarioId); // Establece el ID del usuario
                    $UsuarioLog->setUsuarioIp(''); // Establece la IP del usuario (vacía)

                    $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId); // ID del usuario que solicita el cambio
                    $UsuarioLog->setUsuariosolicitaIp($json->session->usuarioip); // IP del usuario que solicita el cambio

                    $UsuarioLog->setTipo("USUCELULAR"); // Establece el tipo de log
                    $UsuarioLog->setEstado("P"); // Establece el estado como 'Pendiente'
                    $UsuarioLog->setValorAntes($Registro->getCelular()); // Valor del celular antes del cambio
                    $UsuarioLog->setValorDespues($celular); // Valor del celular después del cambio
                    $UsuarioLog->setUsucreaId(0); // ID del usuario que crea el log
                    $UsuarioLog->setUsumodifId(0); // ID del usuario que modifica el log
                    if (
                        (
                            ($Usuario->mandante == 8) || // Condiciones específicas para setear la versión
                            ($Usuario->mandante == 0 && $Usuario->paisId == 46) ||
                            ($Usuario->mandante == 0 && $Usuario->paisId == 66) ||
                            ($Usuario->mandante == 0 && $Usuario->paisId == 94) ||
                            ($Usuario->mandante == 13)
                        )) {
                        $UsuarioLog->setSversion($ID); // Se establece la versión
                    }

                    $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction); // Crea un objeto de acceso a datos para insertar el log
                    $UsuarioLogMySqlDAO2->insert($UsuarioLog); // Inserta el log en la base de datos
                }
            }
            // Verifica si la ciudad es diferente de la ciudad registrada y si la ciudad no está vacía
            if ($ciudad != $Registro->getCiudadId() && $ciudad != '') {
                if ($needs_approval === 'I') {
                    $UsuarioLog = new UsuarioLog2();
                    $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                    $UsuarioLog->setUsuarioIp('');

                    $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
                    $UsuarioLog->setUsuariosolicitaIp($json->session->usuarioip);

                    $UsuarioLog->setTipo("USUCIUDAD");
                    $UsuarioLog->setEstado("P");
                    $UsuarioLog->setValorAntes($Registro->getCiudadId());
                    $UsuarioLog->setValorDespues($ciudad);
                    $UsuarioLog->setUsucreaId(0);
                    $UsuarioLog->setUsumodifId(0);
                    if (
                        (
                            ($Usuario->mandante == 8) ||
                            ($Usuario->mandante == 0 && $Usuario->paisId == 46) ||
                            ($Usuario->mandante == 0 && $Usuario->paisId == 66) || ($Usuario->mandante == 0 && $Usuario->paisId == 94) ||
                            ($Usuario->mandante == 13)
                        )) {
                        $UsuarioLog->setSversion($ID);
                    }


                    $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);
                    $UsuarioLogMySqlDAO2->insert($UsuarioLog);
                } else {
                    $Registro->ciudad = $ciudad;
                }
            }

            /**
             * Verifica si el ID de nacionalidad es diferente al actual y no está vacío.
             * Si necesita aprobación, actualiza el registro; de lo contrario, crea un log de usuario.
             */
            if ($nationality_id != $Registro->getNacionalidadId() && $nationality_id != '') {
                // Si necesita aprobación ('I'), se actualiza el registro.
                if ($needs_approval === 'I') {
                    $UpdateREGISTRO = true;
                    $Registro->nacionalidadId = $nationality_id;
                } else {
                    $UsuarioLog = new UsuarioLog2();
                    $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                    $UsuarioLog->setUsuarioIp('');

                    $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
                    $UsuarioLog->setUsuariosolicitaIp($json->session->usuarioip);

                    $UsuarioLog->setTipo("USUNACIONALIDAD");
                    $UsuarioLog->setEstado("P");
                    // Se registran los valores antes y después del cambio.
                    $UsuarioLog->setValorAntes($Registro->getNacionalidadId());
                    $UsuarioLog->setValorDespues($nationality_id);
                    $UsuarioLog->setUsucreaId(0);
                    $UsuarioLog->setUsumodifId(0);
                    // Se verifica si el mandante cumple ciertas condiciones para agregar la versión.
                    if (
                        (
                            ($Usuario->mandante == 8) ||
                            ($Usuario->mandante == 0 && $Usuario->paisId == 46) ||
                            ($Usuario->mandante == 0 && $Usuario->paisId == 66) || ($Usuario->mandante == 0 && $Usuario->paisId == 94) ||
                            ($Usuario->mandante == 13)
                        )) {
                        $UsuarioLog->setSversion($ID);
                    }


                    $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);
                    $UsuarioLogMySqlDAO2->insert($UsuarioLog);
                }
            }

            /**
             * Verifica si el código postal proporcionado es diferente del código postal
             * almacenado en el registro y si no está vacío. Si necesita aprobación,
             * actualiza el registro; de lo contrario, registra un log de usuario.
             */

            if ($codigopostal != $Registro->getCodigoPostal() && $codigopostal != '') {
                if ($needs_approval === 'I') {
                    $UpdateREGISTRO = true;
                    $Registro->codigoPostal = $codigopostal;
                } else {
                    $UsuarioLog = new UsuarioLog2();
                    $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                    $UsuarioLog->setUsuarioIp('');

                    $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
                    $UsuarioLog->setUsuariosolicitaIp($json->session->usuarioip);

                    $UsuarioLog->setTipo("USUCODIGOPOSTAL");
                    $UsuarioLog->setEstado("P");
                    $UsuarioLog->setValorAntes($Registro->getCodigoPostal());
                    $UsuarioLog->setValorDespues($codigopostal);
                    $UsuarioLog->setUsucreaId(0);
                    $UsuarioLog->setUsumodifId(0);
                    if (
                        (
                            ($Usuario->mandante == 8) ||
                            ($Usuario->mandante == 0 && $Usuario->paisId == 46) ||
                            ($Usuario->mandante == 0 && $Usuario->paisId == 66) || ($Usuario->mandante == 0 && $Usuario->paisId == 94) ||
                            ($Usuario->mandante == 13)
                        )) {
                        $UsuarioLog->setSversion($ID);
                    }


                    $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);
                    $UsuarioLogMySqlDAO2->insert($UsuarioLog);
                }
            }

            /**
             * Verifica si el segundo nombre ha cambiado y si se requiere aprobación.
             * Si se necesita aprobación, se actualizan los nombres y apellidos del usuario,
             * de lo contrario, se registra un log de cambio.
             */
            if ($second_name != "" && $second_name != $Registro->getNombre2()) {
                if ($needs_approval === 'I') {
                    $UpdateUSER = true;
                    $UpdateREGISTRO = true;
                    $UpdateUSUMANDANTE = true;

                    $Registro->nombre2 = $second_name;
                    $Usuario->nombre = $Registro->nombre1 . ' ' . $Registro->nombre2 . ' ' . $Registro->apellido1 . ' ' . $Registro->apellido2;
                    $Registro->nombre = $Usuario->nombre;
                    $UsuarioMandante->nombres = $Registro->nombre1 . ' ' . $Registro->nombre2;
                    $UsuarioMandante->apellidos = $Registro->apellido1 . ' ' . $Registro->apellido2;
                } else {


                    $UsuarioLog = new UsuarioLog2();
                    $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                    $UsuarioLog->setUsuarioIp('');

                    $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
                    $UsuarioLog->setUsuariosolicitaIp($json->session->usuarioip);

                    $UsuarioLog->setTipo("USUNOMBRE2");
                    $UsuarioLog->setEstado("P");
                    $UsuarioLog->setValorAntes($Registro->getNombre2());
                    $UsuarioLog->setValorDespues($second_name);
                    $UsuarioLog->setUsucreaId(0);
                    $UsuarioLog->setUsumodifId(0);
                    if (
                        (
                            ($Usuario->mandante == 8) ||
                            ($Usuario->mandante == 0 && $Usuario->paisId == 46) ||
                            ($Usuario->mandante == 0 && $Usuario->paisId == 66) || ($Usuario->mandante == 0 && $Usuario->paisId == 94) ||
                            ($Usuario->mandante == 13)
                        )) {
                        $UsuarioLog->setSversion($ID);
                    }

                    $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);
                    $UsuarioLogMySqlDAO2->insert($UsuarioLog);
                }
            }

            /**
             * Verifica si el segundo apellido proporcionado no está vacío y es diferente al apellido 2 registrado.
             * Si el estado necesita aprobación ('I'), se actualizan los datos del registro y del usuario.
             * Si no, se registra un log de usuario con la solicitud de cambio de apellido.
             */
            if ($second_sur_name != "" && $second_sur_name != $Registro->getApellido2()) {
                if ($needs_approval === 'I') {
                    $UpdateUSER = true;
                    $UpdateREGISTRO = true;
                    $UpdateUSUMANDANTE = true;

                    $Registro->apellido2 = $second_sur_name;
                    $Usuario->nombre = $Registro->nombre1 . ' ' . $Registro->nombre2 . ' ' . $Registro->apellido1 . ' ' . $Registro->apellido2;
                    $Registro->nombre = $Usuario->nombre;
                    $UsuarioMandante->nombres = $Registro->nombre1 . ' ' . $Registro->nombre2;
                    $UsuarioMandante->apellidos = $Registro->apellido1 . ' ' . $Registro->apellido2;
                } else {


                    $UsuarioLog = new UsuarioLog2();
                    $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                    $UsuarioLog->setUsuarioIp('');

                    $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
                    $UsuarioLog->setUsuariosolicitaIp($json->session->usuarioip);

                    $UsuarioLog->setTipo("USUAPELLIDO2");
                    $UsuarioLog->setEstado("P");
                    $UsuarioLog->setValorAntes($Registro->getApellido2());
                    $UsuarioLog->setValorDespues($second_sur_name);
                    $UsuarioLog->setUsucreaId(0);
                    $UsuarioLog->setUsumodifId(0);
                    if (
                        (
                            ($Usuario->mandante == 8) ||
                            ($Usuario->mandante == 0 && $Usuario->paisId == 46) ||
                            ($Usuario->mandante == 0 && $Usuario->paisId == 66) || ($Usuario->mandante == 0 && $Usuario->paisId == 94) ||
                            ($Usuario->mandante == 13)
                        )) {
                        $UsuarioLog->setSversion($ID);
                    }

                    $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);
                    $UsuarioLogMySqlDAO2->insert($UsuarioLog);
                }
            }

            /**
             * Verifica si la fecha de nacimiento proporcionada es diferente a la almacenada
             * en el objeto $UsuarioOtrainfo y si es necesario aprobación.
             *
             * Si 'needs_approval' es igual a 'I', actualiza la fecha de nacimiento.
             * En caso contrario, registra el cambio en el log de usuarios.
             */
            if ($birth_date != "" && $UsuarioOtrainfo->getFechaNacim() != $birth_date) {
                if ($needs_approval === 'I') {
                    $UpdateUSUOTRAINFO = true;
                    $UsuarioOtrainfo->fechaNacim = $birth_date;
                } else {


                    $UsuarioLog = new UsuarioLog2();
                    $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                    $UsuarioLog->setUsuarioIp('');

                    $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
                    $UsuarioLog->setUsuariosolicitaIp($json->session->usuarioip);

                    $UsuarioLog->setTipo("USUFECHANACIM");
                    $UsuarioLog->setEstado("P");
                    $UsuarioLog->setValorAntes($UsuarioOtrainfo->getFechaNacim());
                    $UsuarioLog->setValorDespues($birth_date);
                    $UsuarioLog->setUsucreaId(0);
                    $UsuarioLog->setUsumodifId(0);
                    if (
                        (
                            ($Usuario->mandante == 8) ||
                            ($Usuario->mandante == 0 && $Usuario->paisId == 46) ||
                            ($Usuario->mandante == 0 && $Usuario->paisId == 66) || ($Usuario->mandante == 0 && $Usuario->paisId == 94) ||
                            ($Usuario->mandante == 13)
                        )) {
                        $UsuarioLog->setSversion($ID);
                    }

                    $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);
                    $UsuarioLogMySqlDAO2->insert($UsuarioLog);
                }
            }


            if (!empty($image_data_selfie)) {
                if ($needs_approval === 'I') {
                    $UpdateUSER = true;
                    $Usuario->verifFotoUsuario = 'S';
                } else {
                    $tipo = 'USUVERFOTO';
                    $pos = strpos($image_data_selfie, 'base64,');
                    $file_contents = base64_decode(substr($image_data_selfie, $pos + 7));
                    $file_contents = addslashes($file_contents);

                    $UsuarioLog = new UsuarioLog2();
                    $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                    $UsuarioLog->setUsuarioIp('');
                    $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
                    $UsuarioLog->setUsuariosolicitaIp($json->session->usuarioip);
                    $UsuarioLog->setUsuarioaprobarId(0);
                    $UsuarioLog->setTipo($tipo);
                    $UsuarioLog->setEstado('P');
                    $UsuarioLog->setValorAntes('');
                    $UsuarioLog->setValorDespues('');
                    $UsuarioLog->setUsucreaId(0);
                    $UsuarioLog->setUsumodifId(0);
                    $UsuarioLog->setImagen($file_contents);
                    if (in_array($Usuario->mandante, ['0', '8', '17']) && in_array($Usuario->paisId, ['46', '66', '33'])) $UsuarioLog->setSversion($ID);

                    $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($Transaction);
                    $UsuarioLogMySqlDAO2->insert($UsuarioLog);
                }
            }


            if ($UpdateUSER === true) $UsuarioMySqlDAO->update($Usuario);
            if ($UpdateREGISTRO === true) $RegistroMySqlDAO->update($Registro);
            if ($UpdateUSUMANDANTE === true) $UsuarioMandanteMySqlDAO->update($UsuarioMandante);
            if ($UpdateUSUOTRAINFO === true) $UsuarioOtrainfoMySqlDAO->update($UsuarioOtrainfo);

            $Transaction->commit();


            $Regis = new Registro("", $Usuario->usuarioId);
            $cedula = $Regis->cedula;
            $tipoD = $Registro->tipoDoc;


            if ($UsuarioMandante->getMandante() == 14 && $image_data_back != "" && $image_data != "") {

                $tipoD = 'RG';
                $verify = new OcrBigId();

                $response = $verify->ocr($image_data, $image_data_back, $tipoD, $cedula);

                $UsuarioLog = new UsuarioLog2();

                $rules = [];

                array_push($rules, ['field' => 'usuario_log2.usuario_id', 'data' => $Usuario->usuarioId, 'op' => 'eq']);
                array_push($rules, ['field' => 'usuario_log2.estado', 'data' => 'P', 'op' => 'eq']);

                $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);
                $result = $UsuarioLog->getUsuarioLog2sCustom('usuario_log2.*', 'usuario_log2.usuariolog2_id', 'desc', 0, 3, $filters, true);
                $UsuarioLogMySqlDAO = new UsuarioLog2MySqlDAO();
                $Transaction = $UsuarioLogMySqlDAO->getTransaction();
                $result = json_decode($result);

                foreach ($result->data as $key => $value) {
                    $UsuarioLog = new UsuarioLog2($value->{'usuario_log2.usuariolog2_id'});


                    if ($response['Code'] == 0) {
                        $UsuarioLog->estado = 'A';
                        $Usuario->verifcedulaAnt = 'A';
                        $Usuario->verifcedulaPost = 'A';
                    } else {
                        $UsuarioLog->estado = 'NA';
                    }

                    $UsuarioLogMySqlDAO = new UsuarioLog2MySqlDAO($Transaction);

                    $UsuarioLogMySqlDAO->update($UsuarioLog);

                }

                $User = new Usuario($UsuarioMandante->getUsuarioMandante());

                if ($response['Code'] == 0) {
                    $User->verifcedulaAnt = 'S';
                    $User->verifcedulaPost = 'S';
                } else {
                    $User->verifcedulaAnt = 'N';
                    $User->verifcedulaPost = 'N';
                    $User->estadoJugador = 'NN';
                }

                $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);
                $UsuarioMySqlDAO->update($User);

                $UsuarioMySqlDAO->getTransaction()->commit();

            }
            if ($response['Code'] != 0) {
                /**
                 * Verifica si el código de respuesta es diferente de 0.
                 * Si es así, se lanza una excepción con el mensaje de resultado.
                 * La excepción no está activa en esta versión del código.
                 */
                // throw new Exception($response['resultMessage'], "21015"); //21015

            }

        }

        $response = array();

        $response['code'] = 0;

        $data = array();

        $data["auth_token"] = $UsuarioToken->getToken();
        $data["result"] = 0;

        $response['data'] = $data;
    }
}
