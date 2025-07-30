<?php


use Backend\dto\Banco;
use Backend\dto\BonoDetalle;
use Backend\dto\Categoria;
use Backend\dto\Clasificador;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Consecutivo;
use Backend\dto\CuentaAsociada;
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
use Backend\dto\UsuarioSession;
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
use Backend\mysql\UsuarioSessionMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\websocket\WebsocketUsuario;

/**
 * Cambio en cuenta de usuarios con cuentaAsociada
 *
 * @param int $json->params->in_app Indicativo de si se está utilizando la aplicación.
 * @param int $json->params->auth_token Token de autenticación del usuario
 *
 * @return array
 * @throws Exception
 *
 * @version 1.0
 */

// Se obtienen los parámetros del JSON.
$params = $json->params;

// Se obtiene el valor de in_app de los parámetros.
$inApp = $json->params->in_app;

// Si in_app es verdadero, se establece inApp como 1.
if($inApp == true){
    $inApp=1;
}

// Se obtiene el token de autenticación de los parámetros.
$auth_token = $params->auth_token;

// Si el token de autenticación está vacío, se lanza una excepción.
if ($auth_token == "") {
    throw new Exception("Token vacio", "01");
}

// Se valida el campo de seguridad del token de autenticación.
$auth_token = validarCampoSecurity($auth_token, true);

// Se crea una nueva instancia de UsuarioToken con el token de autenticación y un valor "0".
$UsuarioToken = new UsuarioToken($auth_token,"0");

// Se obtiene el ID del usuario del mandante a partir del token de usuario.
$UsuarioMandanteId = $UsuarioToken->usuarioId;

// Se obtiene el estado del token del usuario.
$estadoToken = $UsuarioToken->estado;

// Se crea una nueva instancia de UsuarioMandante a partir del ID del mandante.
$UsuarioMandante = new UsuarioMandante($UsuarioMandanteId);

// Se obtiene el ID del usuario del mandante.
$UsuarioId = $UsuarioMandante->usuarioMandante;


try {

/**
 * Inicializa una nueva instancia de la clase CuentaAsociada con un ID de usuario.
 * Luego, obtiene el ID del segundo usuario asociado y crea instancias de Usuario y UsuarioMandante.
 * Finalmente, se obtiene el ID del usuario mandante de la segunda cuenta.
 */

// Se crea una nueva cuenta asociada con ID vacío y el ID de usuario proporcionado.
$cuentaAsociada = new CuentaAsociada('',$UsuarioId);

// Se obtiene el segundo ID de usuario asociado a la cuenta.
$Cuenta2 = $cuentaAsociada->usuarioId2;

// Se crea una nueva instancia de Usuario con el segundo ID de usuario.
$Usuario = new Usuario($Cuenta2);

// Se crea una nueva instancia de UsuarioMandante con ID vacío, el segundo ID de usuario y el mandante del usuario.
$UsuarioMandante2 = new UsuarioMandante('',$Cuenta2,$Usuario->mandante);

// Se obtiene el ID del usuario mandante de la segunda cuenta.
$UsumandanteCuenta2 = $UsuarioMandante2->getUsumandanteId();


if($estadoToken == "A"){

    // Establecer el estado del token de usuario a "Inactivo"
    $UsuarioToken->setEstado("I");

    // Crear una instancia del Data Access Object (DAO) para UsuarioToken
    $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

    // Obtener la transacción para realizar cambios en la base de datos
    $transaction = $UsuarioTokenMySqlDAO->getTransaction();
    // Actualizar el token de usuario en la base de datos
    $UsuarioTokenMySqlDAO->update($UsuarioToken);

    // Confirmar la transacción
    $UsuarioTokenMySqlDAO->getTransaction()->commit();

    // Crear una nueva instancia de UsuarioToken
    $UsuarioToken2 = new UsuarioToken();


    $UsuarioToken2->setUsuarioId($UsumandanteCuenta2);
    $UsuarioToken2->setProveedorId(0);
    $token2 = $UsuarioToken2->createToken();

    // Establecer los demás atributos del nuevo token
    $UsuarioToken2->setUsucreaId(0);
    $UsuarioToken2->setUsumodifId(0);
    $UsuarioToken2->setEstado("A");
    $UsuarioToken2->setRequestId("0");
    $UsuarioToken2->setcookie(0);
    $UsuarioToken2->setUsuarioProveedor(0);
    $UsuarioToken2->setSaldo("0.0");
    $UsuarioToken2->setProductoId(0);
    $UsuarioToken2->setToken($token2);

    // Crear otra instancia del DAO para insertar el nuevo token
    $UsuarioTokenMySqlDAO2 = new UsuarioTokenMySqlDAO();
    // Insertar el nuevo token en la base de datos
    $UsuarioTokenMySqlDAO2->insert($UsuarioToken2);
    // Confirmar la transacción
    $UsuarioTokenMySqlDAO2->getTransaction()->commit();

    // Crear una instancia de UsuarioToken utilizando el nuevo token generado
    $UsuarioToken3 = new UsuarioToken($token2,"0");

    // $UsuarioToken3 = new UsuarioToken('','',$Id,'','',"0");

    //------------------------------------------------------------------------

    // Calcular la diferencia en tiempo entre la fecha de creación del token y el tiempo actual
    $diff = abs(time() - strtotime($UsuarioToken->getFechaCrea()));
    // Calcular los años transcurridos
    $years = floor($diff / (365*60*60*24));
    // Calcular los meses transcurridos
    $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
    // Calcular los días transcurridos
    $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

    // Verifica si el valor de $days es mayor o igual a 1, si el mandante pertenece al arreglo especificado
    // o si la condición 'true' es verdadera, y si el usuario mandante no está en el arreglo de exclusión.
    if(floatval($days) >= 1 && (in_array($UsuarioMandanteSite->mandante,array('0','6','8','2','12',3,4,5,6,7))  || true) && !in_array($UsuarioMandanteSite->usuarioMandante,array(17884 ,242068 ,255499, 255528, 255547 ,255584,242055,242048 ))){
        throw new Exception("No existe Token", "21"); // Lanza una excepción si las condiciones anteriores se cumplen
    }

    $cumple = true; // Inicializa la variable $cumple como verdadera

    // Verifica si $UsuarioMandanteSite está vacío
    if($UsuarioMandanteSite == ''){
        // Si está vacío, crea una nueva instancia de UsuarioMandante usando el usuarioId de $UsuarioToken3
        $UsuarioMandanteSite = new UsuarioMandante($UsuarioToken3->usuarioId);
    }
    
    if($cumple){

        // Asigna el valor de $UsuarioMandanteSite a la variable $UsuarioMandante
        $UsuarioMandante = $UsuarioMandanteSite;

        // Obtiene el saldo del usuario mandante
        $saldo = $UsuarioMandante->getSaldo();
        // Obtiene la moneda del usuario mandante
        $moneda = $UsuarioMandante->getMoneda();
        // Obtiene el ID del país del usuario mandante
        $paisId = $UsuarioMandante->getPaisId();
    
        // Se establece el ID de la sesión en el token del usuario, actualmente es un comentario
        //$UsuarioToken->setRequestId($json->session->sid);
        // Si el proveedor del usuario en el token está vacío, se establece su valor en 0
        if ($UsuarioToken->getUsuarioProveedor() == "") {
            $UsuarioToken->setUsuarioProveedor(0);
        }

        // Inicializa un array para la respuesta
        $response = array();

        // Establece el código de la respuesta
        $response['code'] = 0;

        // Inicializa arrays para datos, socio y ID de socio
        $data = array();
        $partner = array();
        $partner_id = array();

        // Inicializa un array para las apuestas mínimas
        $min_bet_stakes = array();

        // Asigna valores al array de ID de socio
        $partner_id['partner_id'] = $json->session->mandante;
        $partner_id['currency'] = $moneda;
        $partner_id['is_cashout_live'] = 0;
        $partner_id['is_cashout_prematch'] = 0;
        $partner_id['cashout_percetage'] = 0;
        $partner_id['maximum_odd_for_cashout'] = 0;
        $partner_id['is_counter_offer_available'] = 0;
        $partner_id['sports_book_profile_ids'] = [1, 2, 5];
        $partner_id['odds_raised_percent'] = 0;
        $partner_id['minimum_offer_amount'] = 0;
        $partner_id['minimum_offer_amount'] = 0;

        // Establece el monto mínimo de apuestas para la moneda actual
        $min_bet_stakes[$moneda] = 0.1;

        // Establece la longitud mínima de la contraseña del usuario
        $partner_id['user_password_min_length'] = 6;
        $partner_id['id'] = $json->session->mandante;

        // Asigna las apuestas mínimas al ID de socio
        $partner_id['min_bet_stakes'] = $min_bet_stakes;
        $partner[$json->session->mandante] = $partner_id;

        /**
         * Se prepara el arreglo de datos que incluye el socio y el ID del usuario.
         */
        $data["partner"] = $partner;
        $data["usuario"] = $UsuarioToken->getUsuarioId();

        $response["data"] = $data;

        /**
         * Se resetea la respuesta para garantizar que se inicie como un arreglo vacío.
         */
        $response = array();
        $response["code"] = 0; // Se establece el código de respuesta en 0, que generalmente indica éxito.
        $response["rid"] = $json->rid; // Se asigna el identificador de solicitud de la sesión.

        /**
         * Se estructura nuevamente el arreglo de datos de respuesta con información de autenticación y usuario.
         */
        $response["data"] = array(
            "auth_token" => $UsuarioToken3->getToken(),  // Se obtiene el token de autenticación.
            "user_id" => $UsuarioToken3->getUsuarioId(),  // Se obtiene el ID del usuario.
            "channel_id" => $UsuarioToken3->getUsuarioId(),  // Se obtiene el ID del canal (mismo que el ID del usuario).
            "id_platform" => $UsuarioMandante->getUsuarioMandante() // Se obtiene el ID de la plataforma del mandante.
        );


    }


}


} catch (\Exception $e) {
    if ($e->getCode() == "110008") {
        //Se valida el campo de seguridad del token de autenticación.

        $auth_token = validarCampoSecurity($auth_token, true);
        //Se crea un objeto UsuarioToken con el token de autenticación y un valor por defecto.

        $UsuarioToken = new UsuarioToken($auth_token, "0");
        //Se obtiene el ID del usuario asociado al token.

        $UsuarioMandanteId = $UsuarioToken->usuarioId;
        //Se obtiene el estado del token de usuario.

        $estadoToken = $UsuarioToken->estado;
        //Se crea una cuenta asociada utilizando el ID del usuario.

        $cuentaAsociada = new CuentaAsociada('', '', $UsuarioId);
        //Se obtiene el ID del usuario desde la cuenta asociada.

        $Cuenta1 = $cuentaAsociada->usuarioId;
        //Se crea un objeto Usuario utilizando el ID obtenido de la cuenta asociada.

        $Usuario = new Usuario($Cuenta1);
        //Se crea un objeto UsuarioMandante utilizando la información del usuario.
        $UsuarioMandante2 = new UsuarioMandante('', $Cuenta1, $Usuario->mandante);
        //Se obtiene el ID del usuario mandante desde el objeto UsuarioMandante.
        $UsumandanteCuenta1 = $UsuarioMandante2->getUsumandanteId();

        if ($estadoToken == "A") {
            $UsuarioToken->setEstado("I");

            // Crea una instancia del DAO para UsuarioToken
            $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

            // Obtiene la transacción actual del DAO
            $transaction = $UsuarioTokenMySqlDAO->getTransaction();
            // Actualiza el UsuarioToken en la base de datos
            $UsuarioTokenMySqlDAO->update($UsuarioToken);

            // Realiza el commit de la transacción
            $UsuarioTokenMySqlDAO->getTransaction()->commit();

            // Crea una nueva instancia de UsuarioToken
            $UsuarioToken2 = new UsuarioToken();

            // Establece el ID de usuario para el nuevo token
            $UsuarioToken2->setUsuarioId($UsumandanteCuenta1);
            // Establece el proveedor ID a 0
            $UsuarioToken2->setProveedorId(0);
            // Genera un nuevo token
            $token3 = $UsuarioToken2->createToken();
            // Establece el nuevo token en el objeto
            $UsuarioToken2->setToken($token3);
            // Establece el ID de quien crea el usuario token a 0
            $UsuarioToken2->setUsucreaId(0);
            // Establece el ID de quien modifica el usuario token a 0
            $UsuarioToken2->setUsumodifId(0);
            // Establece el estado del usuario token a "Activo"
            $UsuarioToken2->setEstado("A");
            // Establece el ID de la solicitud a "0"
            $UsuarioToken2->setRequestId("0");
            // Establece la cookie a 0
            $UsuarioToken2->setcookie(0);
            // Establece el ID de proveedor de usuario a 0
            $UsuarioToken2->setUsuarioProveedor(0);
            // Establece el saldo a "0.0"
            $UsuarioToken2->setSaldo("0.0");
            // Establece el ID del producto a 0
            $UsuarioToken2->setProductoId(0);

            // Crea una nueva instancia del DAO para insertar el nuevo UsuarioToken
            $UsuarioTokenMySqlDAO2 = new UsuarioTokenMySqlDAO();
            // Inserta el nuevo UsuarioToken en la base de datos
            $UsuarioTokenMySqlDAO2->insert($UsuarioToken2);
            // Realiza el commit de la transacción
            $UsuarioTokenMySqlDAO2->getTransaction()->commit();

            // Crea una nueva instancia de UsuarioToken con el nuevo token y estado "0"
            $UsuarioToken4 = new UsuarioToken($token3,"0");

            // Calcula la diferencia en tiempo entre la fecha de creación y el tiempo actual
            $diff = abs(time() - strtotime($UsuarioToken->getFechaCrea()));
            $years = floor($diff / (365 * 60 * 60 * 24));
            $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
            $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));

            // Verifica si han pasado al menos 1 día y si se cumplen ciertas condiciones
            if (floatval($days) >= 1 && (in_array($UsuarioMandanteSite->mandante, array('0', '6', '8', '2', '12', 3, 4, 5, 6, 7)) || true) && !in_array($UsuarioMandanteSite->usuarioMandante, array(17884, 242068, 255499, 255528, 255547, 255584, 242055, 242048))) {
                // Lanza una excepción si no existe un token
                throw new Exception("No existe Token", "21");
            }

            // Variable para verificar ciertas condiciones
            $cumple = true;

            // Si $UsuarioMandanteSite está vacío, se crea una nueva instancia de UsuarioMandante
            if ($UsuarioMandanteSite == '') {
                $UsuarioMandanteSite = new UsuarioMandante($UsuarioToken3->usuarioId);
            }

            if ($cumple) {
                // Asigna el objeto UsuarioMandante desde UsuarioMandanteSite
                $UsuarioMandante = $UsuarioMandanteSite;

                $saldo = $UsuarioMandante->getSaldo();
                $moneda = $UsuarioMandante->getMoneda();
                $paisId = $UsuarioMandante->getPaisId();

                //$UsuarioToken->setRequestId($json->session->sid);
                if ($UsuarioToken->getUsuarioProveedor() == "") {
                    $UsuarioToken->setUsuarioProveedor(0);
                }

                // Inicializa un arreglo para la respuesta
                $response = array();

                $response['code'] = 0;

                $data = array();
                $partner = array();
                $partner_id = array();

                $min_bet_stakes = array();

                // Configura los detalles del partner
                $partner_id['partner_id'] = $json->session->mandante; // ID del mandante
                $partner_id['currency'] = $moneda; // Moneda utilizada
                $partner_id['is_cashout_live'] = 0; // Indica si el cashout está disponible en vivo
                $partner_id['is_cashout_prematch'] = 0; // Indica si el cashout está disponible en prepartido
                $partner_id['cashout_percetage'] = 0; // Porcentaje del cashout
                $partner_id['maximum_odd_for_cashout'] = 0; // Máxima cuota para cashout
                $partner_id['is_counter_offer_available'] = 0; // Indica si la oferta contrapropuesta está disponible
                $partner_id['sports_book_profile_ids'] = [1, 2, 5]; // IDs de perfiles de casas de apuestas
                $partner_id['odds_raised_percent'] = 0; // Porcentaje de cuotas elevadas
                $partner_id['minimum_offer_amount'] = 0; // Monto mínimo de la oferta
                $partner_id['minimum_offer_amount'] = 0; // Monto mínimo de la oferta (repetido, probablemente un error)

                $min_bet_stakes[$moneda] = 0.1; // Apuesta mínima para la moneda especificada

                $partner_id['user_password_min_length'] = 6;
                $partner_id['id'] = $json->session->mandante;

                $partner_id['min_bet_stakes'] = $min_bet_stakes;

                $partner[$json->session->mandante] = $partner_id;

                $data["partner"] = $partner;

                $data["usuario"] = $UsuarioToken->getUsuarioId();

                $response["data"] = $data;
                //Inicializando formato de respuesta final
                $response = array();
                $response["code"] = 0;
                $response["rid"] = $json->rid;


                $response["data"] = array(
                    "auth_token" => $UsuarioToken4->getToken(),
                    "user_id" => $UsuarioToken4->getUsuarioId(),
                    "channel_id" => $UsuarioToken4->getUsuarioId(),
                    "id_platform" => $UsuarioMandante->getUsuarioMandante()
                );


            }


        }

    }
}

?>