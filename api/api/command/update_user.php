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
use Backend\mysql\UsuarioMandanteMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\UsuarioOtrainfoMySqlDAO;
use Backend\mysql\UsuarioPerfilMySqlDAO;
use Backend\mysql\UsuarioPremiomaxMySqlDAO;
use Backend\mysql\UsuarioRecargaMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\websocket\WebsocketUsuario;

/**
 * command/update_user
 *
 * Actualizar un usuario en la plataforma
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
 *
 *
 * @return object El objeto $response es un array con los siguientes atributos:
 *  - *code* (int): Codigo de error desde el proveedor 0 para exito
 *  - *data* (array): contiene estado de la solicitud y token de autentificacion del usuario.
 *
 * @throws Exception Si no existen los parametros
 * @throws Exception cantidad de caracteres para RFC no valida
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* Obtención información del usuario*/
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


        $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
        $UsuarioTokenMySqlDAO->insert($UsuarioToken);
        $UsuarioTokenMySqlDAO->getTransaction()->commit();

    }

    $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
    $Registro = new Registro("", $Usuario->usuarioId);
    $UsuarioOtrainfo = new UsuarioOtrainfo($Usuario->usuarioId);

// Se extraen los datos del usuario de un objeto JSON.
    $clave = $json->params->user_info->password;
    $direccion = $json->params->user_info->address;
    $ciudad = $json->params->user_info->city_id;
    $ciudad = $json->params->user_info->city_id;
    $docnumber2 = $json->params->user_info->doc_number2;
    $celular = $json->params->user_info->phone;
    $receive_advertising = $json->params->user_info->receive_advertising;
    $birth_date = $json->params->user_info->birth_date;
    $second_name = $json->params->user_info->second_name;
    $second_sur_name = $json->params->user_info->second_sur_name;

    if ($Usuario->origen == 2 && ($Usuario->fechaCrea == $Usuario->fechaActualizacion)) {

        /**
         * Obtiene la información del usuario a partir de un objeto JSON.
         * La información se extrae del parámetro 'user_info'.
         *
         * @var object $user_info Información del usuario.
         * @var string $address Dirección del usuario.
         * @var string $birth_date Fecha de nacimiento del usuario.
         * @var string $country_code Código del país del usuario.
         * @var string $currency_name Nombre de la moneda del usuario.
         * @var int $department_id ID del departamento del usuario.
         * @var string $docnumber Número de documento del usuario.
         * @var string $docnumber2 Segundo número de documento del usuario.
         * @var bool $receive_advertising Indica si el usuario acepta recibir publicidad.
         * @var int $doctype_id ID del tipo de documento del usuario.
         * @var string $email Correo electrónico del usuario.
         * @var string $email2 Segundo correo electrónico del usuario.
         * @var int $expedition_day Día de expedición del documento del usuario.
         * @var int $expedition_month Mes de expedición del documento del usuario.
         * @var int $expedition_year Año de expedición del documento del usuario.
         * @var string $first_name Primer nombre del usuario.
         * @var string $gender Género del usuario.
         * @var string $landline_number Número de teléfono fijo del usuario.
         * @var string $lang_code Código de idioma del usuario.
         * @var string $language Idioma preferido del usuario.
         * @var string $last_name Apellido del usuario.
         * @var int $limit_deposit_day Día límite de depósito del usuario.
         * @var int $limit_deposit_month Mes límite de depósito del usuario.
         * @var int $limit_deposit_week Semana límite de depósito del usuario.
         * @var string $middle_name Segundo nombre del usuario.
         * @var int $nationality_id ID de nacionalidad del usuario.
         * @var string $password Contraseña del usuario.
         */
        $user_info = $json->params->user_info;

        $address = $user_info->address;
        $birth_date = $user_info->birth_date;
        $country_code = $user_info->country_code;
        $currency_name = $user_info->currency_name;
        $department_id = $user_info->department_id;
        $docnumber = $user_info->docnumber;
        $docnumber2 = $user_info->doc_number2;
        $receive_advertising = $user_info->receive_advertising;
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
        // Se llama a la propiedad 'phone' del objeto $user_info y se asigna a la variable $phone
        $phone = $user_info->phone;
        // Se llama a la propiedad 'second_last_name' del objeto $user_info y se asigna a la variable $second_last_name
        $second_last_name = $user_info->second_last_name;
        // Se llama a la propiedad 'site_id' del objeto $user_info y se asigna a la variable $site_id
        $site_id = $user_info->site_id;

        // Se concatenan los nombres y apellidos para formar el nombre completo y se asigna a la variable $nombre
        $nombre = $first_name . " " . $middle_name . " " . $last_name . " " . $second_last_name;

        // Se asigna el nombre completo al objeto $Usuario
        $Usuario->nombre = $nombre;


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
        $Registro->setDirIp($dir_ip);

        $UsuarioMySqlDAO = new UsuarioMySqlDAO();
        $Transaction = $UsuarioMySqlDAO->getTransaction();

        // Se actualiza el usuario en la base de datos
        $UsuarioMySqlDAO->update($Usuario);

        // Se crea una instancia de RegistroMySqlDAO con la transacción actual
        $RegistroMySqlDAO = new RegistroMySqlDAO($Transaction);

        // Se actualiza el registro en la base de datos
        $RegistroMySqlDAO->update($Registro);

        // Se confirma la transacción
        $Transaction->commit();


    } else {
        // Se obtienen los datos del usuario desde el objeto JSON recibido
        $direccion = $json->params->user_info->address;
        $celular = $json->params->user_info->phone;
        $city_id = $json->params->user_info->city_id;
        $department_id = $json->params->user_info->department_id;
        $cp = $json->params->user_info->cp;
        $email = $json->params->user_info->email;
        $docnumber2 = $json->params->user_info->doc_number2;
        $cantidad = strlen($docnumber2);

        // Se verifica si la cantidad de caracteres del RFC es válida para el mandante 22
        if ($cantidad < 12 and $UsuarioMandante->mandante == 22) {
            throw new Exception("cantidad de caracteres para RFC no valida");
        }

        $ciudad = $city_id->Id;
        $codigopostal = $cp;

        // $response = array("code" => 0, "rid" => "15063077673908", "data" => array("subid" => "-7031402156054098668", "data" => array("profile" => array("26678955" => array("id" => 26678955, "unique_id" => 26678955, "username" => "danielftg@hotmail.com", "name" => "Daniel Tamayo", "first_name" => "Daniel", "last_name" => "Tamayo", "gender" => "M", "email" => "danielftg@hotmail.com", "phone" => "573012976239", "reg_info_incomplete" => false, "address" => "calle 100 c sur", "reg_date" => "2017-08-13", "birth_date" => "1994-11-20", "doc_number" => "1026152151", "casino_promo" => null, "currency_name" => "USD", "currency_id" => "USD", "balance" => 5.0, "casino_balance" => 5.0, "exclude_date" => null, "bonus_id" => -1, "games" => 0, "super_bet" => -1, "country_code" => "CO", "doc_issued_by" => null, "doc_issue_date" => null, "doc_issue_code" => null, "province" => null, "iban" => null, "active_step" => null, "active_step_state" => null, "subscribed_to_news" => false, "bonus_balance" => 0.0, "frozen_balance" => 0.0, "bonus_win_balance" => 0.0, "city" => "Manizales", "has_free_bets" => false, "loyalty_point" => 0.0, "loyalty_earned_points" => 0.0, "loyalty_exchanged_points" => 0.0, "loyalty_level_id" => null, "affiliate_id" => null, "is_verified" => false, "incorrect_fields" => null, "loyalty_point_usage_period" => 0, "loyalty_min_exchange_point" => 0, "loyalty_max_exchange_point" => 0, "active_time_in_casino" => null, "last_read_message" => null, "unread_count" => 0, "last_login_date" => 1506281782, "swift_code" => null, "bonus_money" => 0.0, "loyalty_last_earned_points" => 0.0)))));

        try {
            $Clasificador = new Clasificador('', 'APPCHANPERSONALINF');
            $MandanteDetalle = new MandanteDetalle('', $UsuarioMandante->mandante, $Clasificador->getClasificadorId(), $UsuarioMandante->paisId);
            $needs_aproval = $MandanteDetalle->getValor();
        } catch (Exception $ex) {
        }


        $UsuarioClave = $Usuario->checkClave($clave);


        $UsuarioLog2MySqlDAO = new UsuarioLog2MySqlDAO();
        $Transaction = $UsuarioLog2MySqlDAO->getTransaction();

        $enable_transaction = false;
        /**
        * Verifica si el email no está vacío y si es diferente del email registrado.
         * Si se requiere aprobación, se registra un cambio en el log de usuarios.
         * Si no, se actualiza el email del usuario, registrando la modificación correspondiente.
         */
        if (!empty($email) && $email !== $Registro->getEmail()) {
            if (!isset($needs_aproval) || (isset($needs_aproval) && $needs_aproval === 'A')) {

                $UsuarioLog2 = new UsuarioLog2();
                $UsuarioLog2->setUsuarioId($Usuario->usuarioId);
                $UsuarioLog2->setUsuarioIp('');

                $UsuarioLog2->setUsuariosolicitaId($Usuario->usuarioId);
                $UsuarioLog2->setUsuariosolicitaIp($json->session->usuarioip);

                $UsuarioLog2->setTipo('USUEMAIL');
                $UsuarioLog2->setEstado('P');
                $UsuarioLog2->setValorAntes($Registro->getEmail());
                $UsuarioLog2->setValorDespues($email);
                $UsuarioLog2->setUsucreaId(0);
                $UsuarioLog2->setUsumodifId(0);

                $UsuarioLog2MySqlDAO = new UsuarioLog2MySqlDAO($Transaction);
                $UsuarioLog2MySqlDAO->insert($UsuarioLog2);
            } else {

                $UsuarioLog2 = new UsuarioLog2();
                $UsuarioLog2->setUsuarioId($Usuario->usuarioId);
                $UsuarioLog2->setUsuarioIp('');

                $UsuarioLog2->setUsuariosolicitaId($Usuario->usuarioId);
                $UsuarioLog2->setUsuariosolicitaIp($json->session->usuarioip);

                $UsuarioLog2->setTipo('USUEMAIL');
                $UsuarioLog2->setEstado('A');
                $UsuarioLog2->setValorAntes($Registro->getEmail());
                $UsuarioLog2->setValorDespues($email);
                $UsuarioLog2->setUsucreaId(0);
                $UsuarioLog2->setUsumodifId(0);

                $UsuarioLog2MySqlDAO = new UsuarioLog2MySqlDAO($Transaction);
                $UsuarioLog2MySqlDAO->insert($UsuarioLog2);

                $Usuario->login = $email;
                $UsuarioMandante->email = $email;
                $Registro->email = $email;
            }
            $enable_transaction = true;
        }
        /**
         * Verifica si la dirección ha cambiado y si no está vacía.
         * Si la dirección ha cambiado y necesita aprobación,
         * se registra un nuevo log de usuario con estado "P".
         * Si no requiere aprobación, se registra con estado "A"
         * y se actualiza la dirección en el registro y la información del usuario.
         */
        if ($direccion != $Registro->getDireccion() && $direccion != '') {
            if (!isset($needs_aproval) || (isset($needs_aproval) && $needs_aproval === 'A')) {
                $UsuarioLog2 = new UsuarioLog2();
                $UsuarioLog2->setUsuarioId($Usuario->usuarioId);
                $UsuarioLog2->setUsuarioIp('');

                $UsuarioLog2->setUsuariosolicitaId($Usuario->usuarioId);
                $UsuarioLog2->setUsuariosolicitaIp($json->session->usuarioip);

                $UsuarioLog2->setTipo('USUDIRECCION');
                $UsuarioLog2->setEstado("P");
                $UsuarioLog2->setValorAntes($Registro->getDireccion());
                $UsuarioLog2->setValorDespues($direccion);
                $UsuarioLog2->setUsucreaId(0);
                $UsuarioLog2->setUsumodifId(0);

                $UsuarioLog2MySqlDAO = new UsuarioLog2MySqlDAO($Transaction);
                $UsuarioLog2MySqlDAO->insert($UsuarioLog2);
            } else {
                $UsuarioLog2 = new UsuarioLog2();
                $UsuarioLog2->setUsuarioId($Usuario->usuarioId);
                $UsuarioLog2->setUsuarioIp('');

                $UsuarioLog2->setUsuariosolicitaId($Usuario->usuarioId);
                $UsuarioLog2->setUsuariosolicitaIp($json->session->usuarioip);

                $UsuarioLog2->setTipo('USUDIRECCION');
                $UsuarioLog2->setEstado("A");
                $UsuarioLog2->setValorAntes($Registro->getDireccion());
                $UsuarioLog2->setValorDespues($direccion);
                $UsuarioLog2->setUsucreaId(0);
                $UsuarioLog2->setUsumodifId(0);

                $UsuarioLog2MySqlDAO = new UsuarioLog2MySqlDAO($Transaction);
                $UsuarioLog2MySqlDAO->insert($UsuarioLog2);
                $Registro->direccion = $direccion;
                $UsuarioOtrainfo->direccion = $direccion;
            }
            $enable_transaction = true;
        }

        /**
         * Verifica si el número de celular proporcionado es diferente del número registrado
         * y si no está vacío. Si es el caso, se crea un registro en el log de usuario para
         * el cambio de celular. Dependiendo del estado de aprobación, el registro puede
         * ser de tipo "P" (pendiente) o "A" (aprobado). Finalmente, se habilita la
         * transacción para el registro.
         */
        if ($celular != $Registro->getCelular() && $celular != '') {
            if (!isset($needs_aproval) || (isset($needs_aproval) && $needs_aproval === 'A')) {
                $UsuarioLog2 = new UsuarioLog2();
                $UsuarioLog2->setUsuarioId($Usuario->usuarioId);
                $UsuarioLog2->setUsuarioIp('');

                $UsuarioLog2->setUsuariosolicitaId($Usuario->usuarioId);
                $UsuarioLog2->setUsuariosolicitaIp($json->session->usuarioip);

                $UsuarioLog2->setTipo("USUCELULAR");
                $UsuarioLog2->setEstado("P");
                $UsuarioLog2->setValorAntes($Registro->getCelular());
                $UsuarioLog2->setValorDespues($celular);
                $UsuarioLog2->setUsucreaId(0);
                $UsuarioLog2->setUsumodifId(0);

                $UsuarioLog2MySqlDAO = new UsuarioLog2MySqlDAO($Transaction);
                $UsuarioLog2MySqlDAO->insert($UsuarioLog2);
            } else {
                $UsuarioLog2 = new UsuarioLog2();
                $UsuarioLog2->setUsuarioId($Usuario->usuarioId);
                $UsuarioLog2->setUsuarioIp('');

                $UsuarioLog2->setUsuariosolicitaId($Usuario->usuarioId);
                $UsuarioLog2->setUsuariosolicitaIp($json->session->usuarioip);

                $UsuarioLog2->setTipo("USUCELULAR");
                $UsuarioLog2->setEstado("A");
                $UsuarioLog2->setValorAntes($Registro->getCelular());
                $UsuarioLog2->setValorDespues($celular);
                $UsuarioLog2->setUsucreaId(0);
                $UsuarioLog2->setUsumodifId(0);

                $UsuarioLog2MySqlDAO = new UsuarioLog2MySqlDAO($Transaction);
                $UsuarioLog2MySqlDAO->insert($UsuarioLog2);
                $Usuario->celular = $celular;
                $Registro->celular = $celular;
            }
            $enable_transaction = true;
        }

        /**
         * Verifica si la ciudad ha cambiado y si la nueva ciudad no está vacía.
         * Si requiere aprobación, se inserta un registro en UsuarioLog2 con estado "P".
         * De lo contrario, se inserta con estado "A" y se actualiza la ciudad en el registro.
         *
         */
        if ($ciudad != $Registro->getCiudadId() && $ciudad != '') {
            if (!isset($needs_aproval) || (isset($needs_aproval) && $needs_aproval === 'A')) {
                $UsuarioLog2 = new UsuarioLog2();
                $UsuarioLog2->setUsuarioId($Usuario->usuarioId);
                $UsuarioLog2->setUsuarioIp('');

                $UsuarioLog2->setUsuariosolicitaId($Usuario->usuarioId);
                $UsuarioLog2->setUsuariosolicitaIp($json->session->usuarioip);

                $UsuarioLog2->setTipo("USUCIUDAD");
                $UsuarioLog2->setEstado("P");
                $UsuarioLog2->setValorAntes($Registro->getCiudadId());
                $UsuarioLog2->setValorDespues($ciudad);
                $UsuarioLog2->setUsucreaId(0);
                $UsuarioLog2->setUsumodifId(0);

                $UsuarioLog2MySqlDAO = new UsuarioLog2MySqlDAO($Transaction);
                $UsuarioLog2MySqlDAO->insert($UsuarioLog2);
            } else {
                $UsuarioLog2 = new UsuarioLog2();
                $UsuarioLog2->setUsuarioId($Usuario->usuarioId);
                $UsuarioLog2->setUsuarioIp('');

                $UsuarioLog2->setUsuariosolicitaId($Usuario->usuarioId);
                $UsuarioLog2->setUsuariosolicitaIp($json->session->usuarioip);

                $UsuarioLog2->setTipo("USUCIUDAD");
                $UsuarioLog2->setEstado("A");
                $UsuarioLog2->setValorAntes($Registro->getCiudadId());
                $UsuarioLog2->setValorDespues($ciudad);
                $UsuarioLog2->setUsucreaId(0);
                $UsuarioLog2->setUsumodifId(0);

                $UsuarioLog2MySqlDAO = new UsuarioLog2MySqlDAO($Transaction);
                $UsuarioLog2MySqlDAO->insert($UsuarioLog2);
                $Registro->ciudad = $ciudad;
            }
            $enable_transaction = true;
        }

        /**
         * Verifica si el id del código postal es diferente al código postal del registro
         * y si el id del código postal no está vacío. Si se necesitan aprobaciones o
         * si no se ha establecido necesidad de aprobación, se crea un nuevo registro
         * de log de usuario.
         */
        if ($cp->Id != $Registro->getCodigoPostal() && $cp->Id != '') {
            if (!isset($needs_aproval) || (isset($needs_aproval) && $needs_aproval === 'A')) {
                $UsuarioLog2 = new UsuarioLog2();
                $UsuarioLog2->setUsuarioId($Usuario->usuarioId);
                $UsuarioLog2->setUsuarioIp('');

                $UsuarioLog2->setUsuariosolicitaId($Usuario->usuarioId);
                $UsuarioLog2->setUsuariosolicitaIp($json->session->usuarioip);

                $UsuarioLog2->setTipo("USUCODIGOPOSTAL");
                $UsuarioLog2->setEstado("P");
                $UsuarioLog2->setValorAntes($Registro->getCodigoPostal());
                $UsuarioLog2->setValorDespues($cp->Id);
                $UsuarioLog2->setUsucreaId(0);
                $UsuarioLog2->setUsumodifId(0);

                $UsuarioLog2MySqlDAO = new UsuarioLog2MySqlDAO($Transaction);
                $UsuarioLog2MySqlDAO->insert($UsuarioLog2);
            } else {
                $UsuarioLog2 = new UsuarioLog2();
                $UsuarioLog2->setUsuarioId($Usuario->usuarioId);
                $UsuarioLog2->setUsuarioIp('');

                $UsuarioLog2->setUsuariosolicitaId($Usuario->usuarioId);
                $UsuarioLog2->setUsuariosolicitaIp($json->session->usuarioip);

                $UsuarioLog2->setTipo("USUCODIGOPOSTAL");
                $UsuarioLog2->setEstado("A");
                $UsuarioLog2->setValorAntes($Registro->getCodigoPostal());
                $UsuarioLog2->setValorDespues($cp->Id);
                $UsuarioLog2->setUsucreaId(0);
                $UsuarioLog2->setUsumodifId(0);

                $UsuarioLog2MySqlDAO = new UsuarioLog2MySqlDAO($Transaction);
                $UsuarioLog2MySqlDAO->insert($UsuarioLog2);
                $Registro->codigoPostal = $codigopostal;
            }
            $enable_transaction = true;
        }

        /**
         * Verifica si el segundo nombre ha cambiado y si no está vacío.
         * Si el segundo nombre necesita aprobación, se registra un log con estado "P".
         * De lo contrario, se registra el cambio con estado "A" y se actualiza el registro
         * y el objeto de usuario correspondiente.
         */
        if ($second_name != $Registro->getNombre2() && $second_name != "") {
            if (!isset($needs_aproval) || (isset($needs_aproval) && $needs_aproval === 'A')) {
                $UsuarioLog2 = new UsuarioLog2();
                $UsuarioLog2->setUsuarioId($Usuario->usuarioId);
                $UsuarioLog2->setUsuarioIp('');

                $UsuarioLog2->setUsuariosolicitaId($Usuario->usuarioId);
                $UsuarioLog2->setUsuariosolicitaIp($json->session->usuarioip);

                $UsuarioLog2->setTipo("USUNOMBRE2");
                $UsuarioLog2->setEstado("P");
                $UsuarioLog2->setValorAntes($Registro->getNombre2());
                $UsuarioLog2->setValorDespues($second_name);
                $UsuarioLog2->setUsucreaId(0);
                $UsuarioLog2->setUsumodifId(0);

                $UsuarioLog2MySqlDAO = new UsuarioLog2MySqlDAO($Transaction);
                $UsuarioLog2MySqlDAO->insert($UsuarioLog2);
            } else {
                $UsuarioLog2 = new UsuarioLog2();
                $UsuarioLog2->setUsuarioId($Usuario->usuarioId);
                $UsuarioLog2->setUsuarioIp('');

                $UsuarioLog2->setUsuariosolicitaId($Usuario->usuarioId);
                $UsuarioLog2->setUsuariosolicitaIp($json->session->usuarioip);

                $UsuarioLog2->setTipo("USUNOMBRE2");
                $UsuarioLog2->setEstado("A");
                $UsuarioLog2->setValorAntes($Registro->getNombre2());
                $UsuarioLog2->setValorDespues($second_name);
                $UsuarioLog2->setUsucreaId(0);
                $UsuarioLog2->setUsumodifId(0);

                $UsuarioLog2MySqlDAO = new UsuarioLog2MySqlDAO($Transaction);
                $UsuarioLog2MySqlDAO->insert($UsuarioLog2);
                $Registro->nombre2 = $second_name;
                $Registro->nombre = $Registro->nombre1 . ' ' . $Registro->nombre2 . ' ' . $Registro->apellido1 . ' ' . $Registro->apellido2;
                $Usuario->nombre = $Registro->nombre;
                $UsuarioMandante->nombres = $Registro->nombre1 . ' ' . $Registro->nombre2;
                $UsuarioMandante->apellidos = $Registro->apellido1 . ' ' . $Registro->apellido2;
            }
            $enable_transaction = true;
        }

        /**
         * Verifica si el segundo apellido proporcionado es diferente al existente en el registro
         * y si no está vacío. Si se cumplen las condiciones, crea un registro de log de usuario
         * ya sea en estado P (Pendiente) o A (Aprobado) según la necesidad de aprobación.
         * Actualiza el nombre completo del registro y del usuario.
         */
        if ($second_sur_name != $Registro->getApellido2() && $second_sur_name != "") {
            if (!isset($needs_aproval) || (isset($needs_aproval) && $needs_aproval === 'A')) {
                $UsuarioLog2 = new UsuarioLog2();
                $UsuarioLog2->setUsuarioId($Usuario->usuarioId);
                $UsuarioLog2->setUsuarioIp('');

                $UsuarioLog2->setUsuariosolicitaId($Usuario->usuarioId);
                $UsuarioLog2->setUsuariosolicitaIp($json->session->usuarioip);

                $UsuarioLog2->setTipo("USUAPELLIDO2");
                $UsuarioLog2->setEstado("P");
                $UsuarioLog2->setValorAntes($Registro->getApellido2());
                $UsuarioLog2->setValorDespues($second_sur_name);
                $UsuarioLog2->setUsucreaId(0);
                $UsuarioLog2->setUsumodifId(0);

                $UsuarioLog2MySqlDAO = new UsuarioLog2MySqlDAO($Transaction);
                $UsuarioLog2MySqlDAO->insert($UsuarioLog2);
            } else {
                $UsuarioLog2 = new UsuarioLog2();
                $UsuarioLog2->setUsuarioId($Usuario->usuarioId);
                $UsuarioLog2->setUsuarioIp('');

                $UsuarioLog2->setUsuariosolicitaId($Usuario->usuarioId);
                $UsuarioLog2->setUsuariosolicitaIp($json->session->usuarioip);

                $UsuarioLog2->setTipo("USUAPELLIDO2");
                $UsuarioLog2->setEstado("A");
                $UsuarioLog2->setValorAntes($Registro->getApellido2());
                $UsuarioLog2->setValorDespues($second_sur_name);
                $UsuarioLog2->setUsucreaId(0);
                $UsuarioLog2->setUsumodifId(0);

                $UsuarioLog2MySqlDAO = new UsuarioLog2MySqlDAO($Transaction);
                $UsuarioLog2MySqlDAO->insert($UsuarioLog2);
                $Registro->apellido2 = $second_sur_name;
                $Registro->nombre = $Registro->nombre1 . ' ' . $Registro->nombre2 . ' ' . $Registro->apellido1 . ' ' . $Registro->apellido2;
                $Usuario->nombre = $Registro->nombre;
                $UsuarioMandante->nombres = $Registro->nombre1 . $Registro->nombre2;
                $UsuarioMandante->apellidos = $Registro->apellido1 . ' ' . $Registro->apellido2;
            }
            $enable_transaction = true;
        }

        /**
         * Verifica si la fecha de nacimiento ha cambiado para un usuario.
         * Si es así, registra un cambio en el log de usuario dependiendo de
         * si necesita aprobación o no.
         */
        if ($birth_date != $UsuarioOtrainfo->getFechaNacim() && $birth_date != "") {
            if (!isset($needs_aproval) || (isset($needs_aproval) && $needs_aproval === 'A')) {
                $UsuarioLog2 = new UsuarioLog2();
                $UsuarioLog2->setUsuarioId($Usuario->usuarioId);
                $UsuarioLog2->setUsuarioIp('');

                $UsuarioLog2->setUsuariosolicitaId($Usuario->usuarioId);
                $UsuarioLog2->setUsuariosolicitaIp($json->session->usuarioip);

                $UsuarioLog2->setTipo("USUFECHANACIM");
                $UsuarioLog2->setEstado("P");
                $UsuarioLog2->setValorAntes($UsuarioOtrainfo->getFechaNacim());
                $UsuarioLog2->setValorDespues($birth_date);
                $UsuarioLog2->setUsucreaId(0);
                $UsuarioLog2->setUsumodifId(0);

                $UsuarioLog2MySqlDAO = new UsuarioLog2MySqlDAO($Transaction);
                $UsuarioLog2MySqlDAO->insert($UsuarioLog2);
            } else {
                $UsuarioLog2 = new UsuarioLog2();
                $UsuarioLog2->setUsuarioId($Usuario->usuarioId);
                $UsuarioLog2->setUsuarioIp('');

                $UsuarioLog2->setUsuariosolicitaId($Usuario->usuarioId);
                $UsuarioLog2->setUsuariosolicitaIp($json->session->usuarioip);

                $UsuarioLog2->setTipo("USUFECHANACIM");
                $UsuarioLog2->setEstado("A");
                $UsuarioLog2->setValorAntes($UsuarioOtrainfo->getFechaNacim());
                $UsuarioLog2->setValorDespues($birth_date);
                $UsuarioLog2->setUsucreaId(0);
                $UsuarioLog2->setUsumodifId(0);

                $UsuarioLog2MySqlDAO = new UsuarioLog2MySqlDAO($Transaction);
                $UsuarioLog2MySqlDAO->insert($UsuarioLog2);
                $UsuarioOtrainfo->fechaNacim = $birth_date;
            }
            $enable_transaction = true;
        }        // Verifica si el número de documento es diferente del info2 del usuario y si no está vacío
        if ($docnumber2 != $UsuarioOtrainfo->info2 and $docnumber2 != "") {
            // Verifica si necesita aprobación o si el estado es 'A'
            if (!isset($needs_aproval) || (isset($needs_aproval) && $needs_aproval === 'A')) {
                $UsuarioLog2 = new UsuarioLog2();
                // Establece el ID del usuario
                $UsuarioLog2->setUsuarioId($Usuario->usuarioId);
                // Establece la IP del usuario
                $UsuarioLog2->setUsuarioIp('');

                // Establece el ID y la IP del usuario que solicita
                $UsuarioLog2->setUsuariosolicitaId($Usuario->usuarioId);
                $UsuarioLog2->setUsuariosolicitaIp($json->session->usuarioip);

                // Establece el tipo y el estado de la acción
                $UsuarioLog2->setTipo("USURFC");
                $UsuarioLog2->setEstado("P");
                // Establece los valores antes y después del cambio
                $UsuarioLog2->setValorAntes($UsuarioOtrainfo->getInfo2());
                $UsuarioLog2->setValorDespues($docnumber2);
                // Establece los IDs de creación y modificación
                $UsuarioLog2->setUsucreaId(0);
                $UsuarioLog2->setUsumodifId(0);

                // Crea un nuevo DAO para la inserción en MySQL
                $UsuarioLog2MySqlDAO = new UsuarioLog2MySqlDAO($Transaction);
                // Inserta el registro en la base de datos
                $UsuarioLog2MySqlDAO->insert($UsuarioLog2);
            } else {
                $UsuarioLog2 = new UsuarioLog2();
                // Establece el ID del usuario
                $UsuarioLog2->setUsuarioId($Usuario->usuarioId);
                // Establece la IP del usuario
                $UsuarioLog2->setUsuarioIp('');

                // Establece el ID y la IP del usuario que solicita
                $UsuarioLog2->setUsuariosolicitaId($Usuario->usuarioId);
                $UsuarioLog2->setUsuariosolicitaIp($json->session->usuarioip);

                // Establece el tipo y el estado de la acción
                $UsuarioLog2->setTipo("USURFC");
                $UsuarioLog2->setEstado("A");
                // Establece los valores antes y después del cambio
                $UsuarioLog2->setValorAntes($UsuarioOtrainfo->getInfo2());
                $UsuarioLog2->setValorDespues($docnumber2);
                // Establece los IDs de creación y modificación
                $UsuarioLog2->setUsucreaId(0);
                $UsuarioLog2->setUsumodifId(0);

                $UsuarioLog2MySqlDAO = new UsuarioLog2MySqlDAO($Transaction);
                // Inserta el registro en la base de datos
                $UsuarioLog2MySqlDAO->insert($UsuarioLog2);
                // Actualiza el campo info2 del usuario
                $UsuarioOtrainfo->info2 = $docnumber2;
            }
            // Habilita la transacción
            $enable_transaction = true;
        }

        // Establece si el usuario permite recibir publicidad
        $Usuario->setPermiteEnviarPublicidad($receive_advertising);

        // Crea un nuevo DAO para el usuario en MySQL
        $UsuarioMySqlDAO = new UsuarioMySqlDAO();
        // Obtiene la transacción
        $Transaction = $UsuarioMySqlDAO->getTransaction();
        // Actualiza el usuario en la base de datos
        $UsuarioMySqlDAO->update($Usuario);
        // Confirma la transacción
        $UsuarioMySqlDAO->getTransaction()->commit();

        // Si la transacción está habilitada, confirma el cambio
        if ($enable_transaction === true) $Transaction->commit();
    }

// Inicializa el arreglo de respuesta
    $response = array();

// Asigna un código de respuesta
    $response['code'] = 0;

// Inicializa el arreglo de datos
    $data = array();

// Obtiene el token de autenticación del usuario
    $data["auth_token"] = $UsuarioToken->getToken();
// Asigna un resultado inicial
    $data["result"] = 0;

// Asigna los datos a la respuesta
    $response['data'] = $data;
}
