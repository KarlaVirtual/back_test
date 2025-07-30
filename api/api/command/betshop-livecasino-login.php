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

/** Obtiene la información de sesión para el usuario requerido
 * @param string $params->token
 *
 * @return array
 *  -code: int Estado de la petición
 *  -data: array Datos de la petición
 *  -data.token: string Token de la sesión
 *  -data.user_id: int ID del usuario
 */

// Obtener los parámetros del JSON
$params = $json->params;

// Asignar el token de autenticación
$auth_token = $params->token;

// Verificar si el token está vacío
if ($auth_token == "") {
    // Lanza una excepción si el token está vacío
    throw new Exception("Token vacio", "01");
}

// Inicializa la variable que indica si se cumple la condición
$cumple = true;

// Crear una nueva instancia de Proveedor con parámetros específicos
$Proveedor = new Proveedor("", "IES");

// Crear una nueva instancia de ProdMandanteTipo con parámetros específicos
$ProdMandanteTipo = new ProdMandanteTipo('CASINO', '0');

// Verificar el estado del ProdMandanteTipo
if ($ProdMandanteTipo->estado == "I") {
    // Si el estado es "I", cambiar el cumplimiento a falso
    $cumple = false;
} elseif ($ProdMandanteTipo->estado == "A") {
    // Crear una nueva instancia de UsuarioToken si el estado es "A"
    $UsuarioToken = new UsuarioToken($auth_token, $Proveedor->getProveedorId());
} else {
    // Crear una nueva instancia de UsuarioToken si el estado no es "A"
    $UsuarioToken = new UsuarioToken($auth_token, $Proveedor->getProveedorId());

    // Verificar el estado del UsuarioToken
    if ($UsuarioToken->estado != "NR") {
        // Cambia el cumplimiento a falso si el estado no es "NR"
        $cumple = false;
    }
}


if ($cumple) {
    $tipoUsuarioSession="1"; // Inicializa el tipo de usuario de la sesión como "1".
    if($json->session->typeC != "" && $json->session->typeC != null){
        // Si el tipoC de la sesión no está vacío ni es nulo, se asigna a tipoUsuarioSession.
        $tipoUsuarioSession=$json->session->typeC;
    }
    $UsuarioMandante = new UsuarioMandante($UsuarioToken->getUsuarioId(), "");

    $saldo = $UsuarioMandante->getSaldo(); // Obtiene el saldo del UsuarioMandante.
    $moneda = $UsuarioMandante->getMoneda(); // Obtiene la moneda del UsuarioMandante.
    $paisId = $UsuarioMandante->getPaisId(); // Obtiene el ID del país del UsuarioMandante.

    $UsuarioToken->setRequestId($json->session->sid); // Asigna el SID de la sesión al UsuarioToken.
    $UsuarioToken->setSaldo(0); // Establece el saldo del UsuarioToken a 0.

    $UsuarioTokenMySqlDAO = new \Backend\mysql\UsuarioTokenMySqlDAO(); // Crea una nueva instancia del DAO para UsuarioToken.
    $UsuarioTokenMySqlDAO->update($UsuarioToken); // Actualiza el UsuarioToken en la base de datos.
    $UsuarioTokenMySqlDAO->getTransaction()->commit(); // Realiza el commit de la transacción.

    try {
        $UsuarioSession = new UsuarioSession($tipoUsuarioSession, $json->session->sid, "A"); // Crea una nueva instancia de UsuarioSession.

        if ($UsuarioSession->getUsuarioId() != $UsuarioToken->getUsuarioId()) {
            // Si el ID del usuario de la sesión no coincide con el ID del UsuarioToken.
            $UsuarioSession->setUsuarioId($UsuarioToken->usuarioId); // Establece el ID del UsuarioToken en la sesión.

            $UsuarioSessionMySqlDAO = new UsuarioSessionMySqlDAO(); // Crea una nueva instancia del DAO para UsuarioSession.
            $UsuarioSessionMySqlDAO->update($UsuarioSession); // Actualiza el UsuarioSession en la base de datos.
            $UsuarioSessionMySqlDAO->getTransaction()->commit(); // Realiza el commit de la transacción.
        }

    } catch (Exception $e) {
        // Manejo de excepciones en caso de errores durante la creación o actualización de la sesión.
        if ($e->getCode() == "99") {


            $UsuarioSession = new UsuarioSession();
            $UsuarioSession->setTipo($tipoUsuarioSession);
            $UsuarioSession->setRequestId($json->session->sid);
            $UsuarioSession->setUsuarioId($UsuarioToken->usuarioId);
            $UsuarioSession->setEstado('A');
            $UsuarioSession->setPerfil('');
            $UsuarioSession->setUsucreaId('0');
            $UsuarioSession->setUsumodifId('0');

            $UsuarioSessionMySqlDAO = new UsuarioSessionMySqlDAO();
            $UsuarioSessionMySqlDAO->insert($UsuarioSession);
            $UsuarioSessionMySqlDAO->getTransaction()->commit();

            /*
                            $UsuarioSession2 = new UsuarioSession();

                            $rules = [];

                            array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
                            array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioSession->getUsuarioId(), "op" => "ne"));
                            array_push($rules, array("field" => "usuario_session.request_id", "data" => $UsuarioSession->getRequestId(), "op" => "eq"));


                            $filtro = array("rules" => $rules, "groupOp" => "AND");
                            $json = json_encode($filtro);


                            $usuarios = $UsuarioSession2->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

                            $usuarios = json_decode($usuarios);
                            $usuariosFinal = [];

                            foreach ($usuarios->data as $key => $value) {

                                $UsuarioSession3 = new UsuarioSession("", "", "",$value->{'usuario_session.ususession_id'});

                                $UsuarioSession3->setEstado('I');

                                $UsuarioSessionMySqlDAO = new UsuarioSessionMySqlDAO();
                                $UsuarioSessionMySqlDAO->update($UsuarioSession3);
                                $UsuarioSessionMySqlDAO->getTransaction()->commit();


                            }*/

        }
    }

    $response = array(); // Inicializa el arreglo de respuesta.

    $response['code'] = 0; // Establece el código de respuesta a 0.

    $data = array(); // Inicializa el arreglo de datos.
    $partner = array(); // Inicializa el arreglo de socios.
    $partner_id = array(); // Inicializa el arreglo del id del socio.

    $min_bet_stakes = array(); // Inicializa el arreglo de las apuestas mínimas.

    $partner_id['partner_id'] = $json->session->mandante; // Establece el id del socio desde el JSON.
    $partner_id['currency'] = $moneda; // Establece la moneda.
    $partner_id['is_cashout_live'] = 0; // Establece si el cashout en vivo está disponible.
    $partner_id['is_cashout_prematch'] = 0; // Establece si el cashout prematch está disponible.
    $partner_id['cashout_percetage'] = 0; // Establece el porcentaje de cashout.
    $partner_id['maximum_odd_for_cashout'] = 0; // Establece la máxima cuota para cashout.
    $partner_id['is_counter_offer_available'] = 0; // Establece si la oferta contraoferta está disponible.
    $partner_id['sports_book_profile_ids'] = [1, 2, 5]; // Establece los IDs de perfil de sportsbook.
    $partner_id['odds_raised_percent'] = 0; // Establece el porcentaje de cuotas incrementadas.
    $partner_id['minimum_offer_amount'] = 0; // Establece el monto mínimo de la oferta.
    $partner_id['minimum_offer_amount'] = 0; // Repetición del establecimiento del monto mínimo de la oferta.

    $min_bet_stakes[$moneda] = 0.1; // Establece las apuestas mínimas.

    $partner_id['user_password_min_length'] = 6; // Establece la longitud mínima de la contraseña de usuario.
    $partner_id['id'] = $json->session->mandante; // Establece el id del socio.

    $partner_id['min_bet_stakes'] = $min_bet_stakes; // Asigna las apuestas mínimas al id del socio.

    $partner[$json->session->mandante] = $partner_id; // Asigna el id del socio al arreglo de socios.

    //$data["partner"] = $partner;

    //$data["usuario"] = $UsuarioToken->getUsuarioId();

    $response["data"] = $data; // Asigna los datos a la respuesta.

    $response = array(); // Reinicializa el arreglo de respuesta.
    $response["code"] = 0; // Establece nuevamente el código de respuesta a 0.
    $response["rid"] = $json->rid; // Asigna el rid del JSON a la respuesta.

    $response["data"] = array(
        "token" => $UsuarioToken->getToken(),
        "user_id" => $UsuarioToken->getUsuarioId()
    );

} else {

    throw new Exception("Restringido", "01");
}