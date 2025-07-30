

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
use Backend\mysql\UsuarioLogMySqlDAO;
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
 * Realiza el proceso de login bajo la plataforma de facebook
 * @param string $json->params->access_token Token de acceso de Facebook
 *
 * @return array
 *  -code:int Código de respuesta
 *  -rid:string ID de la solicitud
 *  -data:array Información adicional
 *      -auth_token:string Token de autenticación
 *      -user_id:int ID del usuario
 *      -user_id:int ID del usuario
 */

// Se obtiene el token de acceso desde el objeto JSON de parámetros
$access_token = $json->params->access_token;

// Se crea una instancia de la clase Facebook desde el espacio de nombres Backend\imports\Facebook
$Facebook = new Backend\imports\Facebook\Facebook([
    'app_id' => '444176019354141',
    'app_secret' => '082f36ae3a11d753445e6e9d274a79f7',
    'default_graph_version' => 'v3.1',
]);

// URL para obtener los detalles del usuario utilizando el token de acceso
$user_details = "https://graph.facebook.com/me?access_token=" . $access_token;

// Se realiza una solicitud para obtener los detalles del usuario y se decodifica la respuesta JSON
$responseFB = file_get_contents($user_details);
$responseFB = json_decode($responseFB);

// Se obtiene el objeto de usuario de Facebook, solicitando campos específicos
$responseFB = ($Facebook->get('/me?fields=id,name,email,birthday', $access_token));


try {
    // Se crea una nueva instancia de Usuario con el email obtenido de la respuesta de Facebook
    $Usuario = new Usuario("", $responseFB->getGraphNode()->getField('email'));
    $UsuarioMandante = new UsuarioMandante("", $Usuario->usuarioId, "0");
    $UsuarioToken = new UsuarioToken("", "1", $UsuarioMandante->getUsumandanteId());

} catch (Exception $e) {
    // Manejo de excepciones (sin implementación en el código proporcionado)

    if ($e->getCode() == 24) {

        /**
         * Inicialización de variables para almacenar información del usuario.
         */
        $address = '';
        $birth_date = $responseFB->getGraphNode()->getField('birthday');
        $country_code = 173;
        $currency_name = '';
        $department_id = '';
        $docnumber = '0';
        $doctype_id = 1;
        $email = $responseFB->getGraphNode()->getField('email');
        $email2 = $responseFB->getGraphNode()->getField('email');
        $expedition_day = '';
        $expedition_month = '';
        $expedition_year = '';
        $first_name = $responseFB->getGraphNode()->getField('name');
        $gender = '';
        $landline_number = '';
        $lang_code = 'es';
        $language = 'es';
        $last_name = '';
        $limit_deposit_day = '0';
        $limit_deposit_month = '0';
        $limit_deposit_week = '0';
        $middle_name = '';
        $nationality_id = 0;
        $password = '00000';
        $phone = '';
        $second_last_name = '';
        $site_id = '';


        $nombre = $responseFB->getGraphNode()->getField('name');
        $clave_activa = GenerarClaveTicket(15);


        /**
         * Se establece el tipo de documento según el ID proporcionado.
         */
        switch ($doctype_id) {
            case 1:
                $doctype_id = "C";
                break;

            case 2:
                $doctype_id = "E";

                break;

            case 3:
                $doctype_id = "P";

                break;
        }

        /**
         * Clase para manejar el registro de información.
         */
        $Registro = new Registro(); // Se crea una nueva instancia de la clase Registro.
        $Registro->setCedula($docnumber); // Se establece el número de cédula en el registro.

        if (true) {

            $Consecutivo = new Consecutivo("", "USU", "");

            $consecutivo_usuario = $Consecutivo->numero;

            $consecutivo_usuario++;

            $ConsecutivoMySqlDAO = new ConsecutivoMySqlDAO();

            $Consecutivo->setNumero($consecutivo_usuario);


            $ConsecutivoMySqlDAO->update($Consecutivo);

            $ConsecutivoMySqlDAO->getTransaction()->commit();


            $premio_max = "";
            $premio_max1 = "";
            $premio_max2 = "";
            $premio_max3 = "";
            $cant_lineas = "";
            $lista_id = "";
            $regalo_registro = "";
            $valor_directo = "";
            $valor_evento = "";
            $valor_diario = "";
            $destin1 = "";
            $destin2 = "";
            $destin3 = "";

            $apuesta_min = "";
            $moneda_default = "USD";

            $token_itainment = GenerarClaveTicket2(12);

            $dir_ip = $json->session->usuarioip;

            $RegistroMySqlDAO = new RegistroMySqlDAO();
            $Transaction = $RegistroMySqlDAO->getTransaction();

            // Configuración de los detalles del registro
            $Registro->setNombre($nombre);
            $Registro->setEmail($email);
            $Registro->setClaveActiva($clave_activa);
            $Registro->setEstado("A");
            $Registro->usuarioId = $consecutivo_usuario;
            $Registro->setCelular($phone);
            $Registro->setCreditosBase(0);
            $Registro->setCreditos(0);
            $Registro->setCreditosAnt(0);
            $Registro->setCreditosBaseAnt(0);
            //$Registro->setCiudadId($department_id->cities[0]->id);
            $Registro->setCiudadId(1);
            $Registro->setCasino(0);
            $Registro->setCasinoBase(0);
            $Registro->setMandante('0');
            $Registro->setNombre1($first_name);
            $Registro->setNombre2($middle_name);
            $Registro->setApellido1($last_name);
            $Registro->setApellido2($second_last_name);
            $Registro->setSexo($gender);
            $Registro->setTipoDoc($doctype_id);
            $Registro->setDireccion($address);
            $Registro->setTelefono($landline_number);
            $Registro->setCiudnacimId(1);
            $Registro->setNacionalidadId($nationality_id);
            $Registro->setDirIp($dir_ip);
            $Registro->setOcupacionId(0);
            $Registro->setRangoingresoId(0);
            $Registro->setOrigenfondosId(0);
            $Registro->setPaisnacimId(1);
            $Registro->setPuntoVentaId(0);
            $Registro->setPreregistroId(0);
            $Registro->setCreditosBono(0);
            $Registro->setCreditosBonoAnt(0);
            $Registro->setPreregistroId(0);
            $Registro->setUsuvalidaId(0);
            $Registro->setFechaValida(date('Y-m-d H:i:s'));

            $Registro->setCiudexpedId(0);
            $Registro->setPuntoventaId(0);
            $Registro->setEstadoValida("A");

            $Registro->setAfiliadorId(0);

            /**
             * Se inserta el objeto $Registro en la base de datos utilizando
             * el método insert del objeto $RegistroMySqlDAO.
             */
            $RegistroMySqlDAO->insert($Registro);

            $Transaccion = $RegistroMySqlDAO->getTransaction();

            /**
             * Creación de una nueva instancia de la clase Usuario.
             * Se inicializan diferentes propiedades del objeto Usuario
             * usando los datos proporcionados.
             */
            $Usuario = new Usuario();


            $Usuario->usuarioId = $consecutivo_usuario;

            $Usuario->login = $email;

            $Usuario->nombre = $nombre;

            $Usuario->estado = 'I';

            $Usuario->fechaUlt = date('Y-m-d H:i:s');

            $Usuario->claveTv = '';

            $Usuario->estadoAnt = 'I';

            $Usuario->intentos = 0;

            $Usuario->estadoEsp = 'I';

            $Usuario->observ = '';

            $Usuario->dirIp = $json->session->usuarioip;

            $Usuario->eliminado = 'N';

            $Usuario->mandante = '0';

            $Usuario->usucreaId = '0';

            $Usuario->usumodifId = '0';

            $Usuario->claveCasino = '';

            $Usuario->tokenItainment = $token_itainment;

            $Usuario->fechaClave = '';

            $Usuario->retirado = '';

            $Usuario->fechaRetiro = '';

            $Usuario->horaRetiro = '';

            $Usuario->usuretiroId = '0';

            $Usuario->bloqueoVentas = 'N';

            $Usuario->infoEquipo = '';

            $Usuario->estadoJugador = 'NN';

            $Usuario->tokenCasino = '';

            $Usuario->sponsorId = 0;

            $Usuario->verifCorreo = 'N';

            $Usuario->paisId = '1';

            $Usuario->moneda = $moneda_default;

            $Usuario->idioma = $idioma;

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
            $Usuario->tokenItainment = 0;
            $Usuario->sponsorId = (0);


            $Usuario->origen = 2;
            $Usuario->fechaCrea = date('Y-m-d H:i:s');
            $Usuario->fechaActualizacion = $Usuario->fechaCrea;
            // Se crea una instancia del DAO para usuario, pasando la transacción como parámetro
            $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaccion);
            //$UsuarioMySqlDAO = new UsuarioMySqlDAO();

            // Se inserta el usuario en la base de datos
            $UsuarioMySqlDAO->insert($Usuario);


            //$UsuarioMySqlDAO->getTransaction()->commit();

            // Se crea una instancia del objeto UsuarioOtrainfo
            $UsuarioOtrainfo = new UsuarioOtrainfo();
            // Se asignan valores a las propiedades del objeto UsuarioOtrainfo
            $UsuarioOtrainfo->usuarioId = $consecutivo_usuario;
            $UsuarioOtrainfo->fechaNacim = date('Y-m-d', strtotime($birth_date));
            $UsuarioOtrainfo->mandante = '0';
            $UsuarioOtrainfo->bancoId = '0';
            $UsuarioOtrainfo->numCuenta = '0';
            $UsuarioOtrainfo->anexoDoc = 'N';

            // Se crea una instancia del DAO para UsuarioOtrainfo, pasando la transacción como parámetro
            $UsuarioOtrainfoMySqlDAO = new UsuarioOtrainfoMySqlDAO($Transaccion);
            //$UsuarioOtrainfoMySqlDAO = new UsuarioOtrainfoMySqlDAO();

            $UsuarioOtrainfoMySqlDAO->insert($UsuarioOtrainfo);
            //$UsuarioOtrainfoMySqlDAO->getTransaction()->commit();

            // Se crea una instancia del objeto UsuarioPerfil
            $UsuarioPerfil = new UsuarioPerfil();
            // Se configuran los atributos del objeto UsuarioPerfil
            $UsuarioPerfil->setUsuarioId($consecutivo_usuario);
            $UsuarioPerfil->setPerfilId('USUONLINE');
            $UsuarioPerfil->setMandante('0');


            $UsuarioPerfilMySqlDAO = new UsuarioPerfilMySqlDAO($Transaccion);
            //$UsuarioPerfilMySqlDAO = new UsuarioPerfilMySqlDAO();
            $UsuarioPerfilMySqlDAO->insert($UsuarioPerfil);
            //$UsuarioPerfilMySqlDAO->getTransaction()->commit();

            $UsuarioPremiomax = new UsuarioPremiomax();

            $premio_max1 = 0;
            $premio_max2 = 0;
            $premio_max3 = 0;
            $apuesta_min = 0;
            $cant_lineas = 0;
            $valor_directo = 0;
            $valor_evento = 0;
            $valor_diario = 0;

            $UsuarioPremiomax->usuarioId = $consecutivo_usuario;

            $UsuarioPremiomax->premioMax = $premio_max1;

            $UsuarioPremiomax->usumodifId = '0';


            $UsuarioPremiomax->cantLineas = $cant_lineas;

            $UsuarioPremiomax->premioMax1 = $premio_max1;

            $UsuarioPremiomax->premioMax2 = $premio_max2;

            $UsuarioPremiomax->premioMax3 = $premio_max3;

            $UsuarioPremiomax->apuestaMin = $apuesta_min;

            $UsuarioPremiomax->valorDirecto = $valor_directo;
            $UsuarioPremiomax->premioDirecto = $valor_directo;


            $UsuarioPremiomax->mandante = '0';
            $UsuarioPremiomax->optimizarParrilla = 'N';


            $UsuarioPremiomax->valorEvento = $valor_evento;

            $UsuarioPremiomax->valorDiario = $valor_diario;

            $UsuarioPremiomaxMySqlDAO = new UsuarioPremiomaxMySqlDAO($Transaccion);
            //$UsuarioPremiomaxMySqlDAO = new UsuarioPremiomaxMySqlDAO();
            $UsuarioPremiomaxMySqlDAO->insert($UsuarioPremiomax);
            //$UsuarioPremiomaxMySqlDAO->getTransaction()->commit();

            $Transaccion->commit();

            $Usuario->changeClave($password);


            $UsuarioMandante = new UsuarioMandante();

            $UsuarioMandante->mandante = $Usuario->mandante;
            //$UsuarioMandante->dirIp = $dir_ip;
            $UsuarioMandante->nombres = $Usuario->nombre;
            $UsuarioMandante->apellidos = '';
            $UsuarioMandante->estado = 'A';
            $UsuarioMandante->email = $Usuario->login;
            $UsuarioMandante->moneda = $Usuario->moneda;
            $UsuarioMandante->paisId = $Usuario->paisId;
            $UsuarioMandante->saldo = 0;
            $UsuarioMandante->usuarioMandante = intval($consecutivo_usuario);
            $UsuarioMandante->usucreaId = 0;
            $UsuarioMandante->usumodifId = 0;

            $UsuarioMandanteMySqlDAO = new UsuarioMandanteMySqlDAO();
            $usuario_id = $UsuarioMandanteMySqlDAO->insert($UsuarioMandante);

            $UsuarioMandanteMySqlDAO->getTransaction()->getConnection()->commit();


            $UsuarioToken = new UsuarioToken();

            $UsuarioToken->setRequestId($json->session->sid);
            $UsuarioToken->setProveedorId(1);
            $UsuarioToken->setUsuarioId($usuario_id);
            $UsuarioToken->setToken($UsuarioToken->createToken());

            $UsuarioToken->setCookie('');
            $UsuarioToken->setUsumodifId(0);
            $UsuarioToken->setUsucreaId(0);
            $UsuarioToken->setSaldo(0);


            $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
            $UsuarioTokenMySqlDAO->insert($UsuarioToken);
            $UsuarioTokenMySqlDAO->getTransaction()->commit();


        }

    }
    throw $e;
}

/*Inicialización de una respuesta*/
$response = array();
$response["code"] = 0;
$response["rid"] = $json->rid;
$response["data"] = array(
    "auth_token" => $UsuarioToken->getToken(),
    "user_id" => $UsuarioToken->getUsuarioId(),
    "user_id" => $UsuarioToken->getUsuarioId()
);
