<?php

use Backend\dto\Banco;
use Backend\dto\BonoDetalle;
use Backend\dto\BonoInterno;
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
use Backend\dto\Subproveedor;
use Backend\dto\TransaccionApi;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioBanco;
use Backend\dto\UsuarioConfig;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\UsuarioLog;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioMarketing;
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
use Backend\integrations\casino\Game;
use Backend\integrations\casino\IES;
use Backend\integrations\casino\JOINSERVICES;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\mysql\ConsecutivoMySqlDAO;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\DocumentoUsuarioMySqlDAO;
use Backend\mysql\PromocionalLogMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\mysql\RegistroMySqlDAO;
use Backend\mysql\TransaccionApiMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\UsuarioBancoMySqlDAO;
use Backend\mysql\UsuarioBannerMySqlDAO;
use Backend\mysql\UsuarioConfiguracionMySqlDAO;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\mysql\UsuarioMandanteMySqlDAO;
use Backend\mysql\UsuarioMarketingMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\UsuarioOtrainfoMySqlDAO;
use Backend\mysql\UsuarioPerfilMySqlDAO;
use Backend\mysql\UsuarioPremiomaxMySqlDAO;
use Backend\mysql\UsuarioRecargaMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\websocket\WebsocketUsuario;

/**
 * command/debit_user_balance
 *
 * Realiza un débito del saldo del usuario para la compra de tarjetas en el juego IESGAMES.
 *
 * @param string $site_id : Identificador del sitio.
 * @param string $gameCode : Código del juego.
 * @param float $amount : Monto por tarjeta.
 * @param int $NumCards : Número de tarjetas a comprar.
 *
 * El objeto $response es un array con los siguientes atributos:
 *  - *code* (int): Código de error desde el proveedor.
 *  - *rid* (string): Identificador de la solicitud.
 *  - *error_code* (int, opcional): Código de error en caso de fallo.
 *
 * Objeto en caso de error:
 *
 * "error_code" => 20001, // Saldo insuficiente
 * "rid" => "[RID de la solicitud]",
 *
 * "error_code" => 300020, // Número de tarjetas inválido
 * "rid" => "[RID de la solicitud]",
 *
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* Crea un array de respuesta con código, ID y datos resultantes. */
$response = array();
$response["code"] = 0;
$response["rid"] = $json->rid;
$response["data"] = array(
    "result" => ""

);


/* inicializa objetos de usuario y obtiene parámetros de un objeto JSON. */
$params = $json->params;
$UsuarioMandante = new UsuarioMandante($json->session->usuario);
$Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

$site_id = $json->params->site_id;
$GameCode = $json->params->gameCode;

/* Se calcula el monto total de débito multiplicando la cantidad por el número de tarjetas. */
$amount = $json->params->amount;


$transactionId = ""; // como crear la transacción ?
$NumCards = $json->params->NumCards;

$DebitAmount = $NumCards * $amount;

/* Genera un ID de transacción único y obtiene el saldo de un usuario. */
$transactionId = md5(time());
$transactionId = substr($transactionId, 0, 10);

$saldo = $Usuario->getBalance();

if ($saldo >= $DebitAmount) {

    if ($NumCards > 1) {

        /* Se crean instancias de Proveedor, Producto y UsuarioToken con referencias adecuadas. */
        $Proveedor = new Proveedor("", "IESGAMES");

        $Producto = new Producto("", "IESGAMES", $Proveedor->getProveedorId());
        try {
            $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $UsuarioMandante->getUsumandanteId());

        } catch (Exception $e) {
            if ($e->getCode() == 21) {


                /* Crea un objeto UsuarioToken y establece varios atributos relacionados con el proveedor. */
                $UsuarioToken = new UsuarioToken();
                $UsuarioToken->setProveedorId($Proveedor->getProveedorId());
                $UsuarioToken->setCookie('0');
                $UsuarioToken->setRequestId('0');
                $UsuarioToken->setUsucreaId(0);
                $UsuarioToken->setUsumodifId(0);

                /* establece un nuevo token de usuario en la base de datos. */
                $UsuarioToken->setUsuarioId($UsuarioMandante->getUsumandanteId());
                $UsuarioToken->setToken($UsuarioToken->createToken());
                $UsuarioToken->setSaldo(0);
                $UsuarioToken->setProductoId(0);


                $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();


                /* Inserta un token de usuario y obtiene la transacción asociada en MySQL. */
                $UsuarioTokenMySqlDAO->insert($UsuarioToken);

                $UsuarioTokenMySqlDAO->getTransaction()->commit();


            } else {
                /* maneja excepciones lanzando el error si se cumple una condición. */

                throw $e;
            }
        }


        /* obtiene un token de usuario mediante el método `getToken()`. */
        $Token = $UsuarioToken->getToken();

        /*   $data= array(
               "playerId"=>$UsuarioMandante->getUsumandanteId(),
               "currency"=>$UsuarioMandante->getMoneda(),
               "amount"=>floatval($DebitAmount),
               "gameCode"=>$GameCode,
               "platformTransactionId"=>$transactionId,
               "numCards"=>$NumCards
           );*/


        //$IESGAMESSERVICES = new \Backend\integrations\casino\IESGAMESSERVICES();


        //$respon = $IESGAMESSERVICES->SecuencialExternalBet($data,$Usuario,$Producto);

        //if($respon->error == false){

        /*   $GameCode = $respon->response->gameCode;
           $debitAmount = floatval($respon->response->debitAmount);
           $transactionId = $respon->response->transactionId;*/

        /* Código que realiza una transacción de débito en un juego de casino. */
        $freespin = false;

        $IESGAMES = new \Backend\integrations\casino\IESGAMES($Token);


        $respuestaDebit = $IESGAMES->DebitLocal("IESGAMES", $DebitAmount, $GameCode, $transactionId, "", $freespin, $NumCards);


        /* crea un array de respuesta con un código y un identificador. */
        $response = array();
        $response["code"] = 0;

        $response["rid"] = $json->rid;


        /*  }else{

              $response = array();
              $response["code"] = 1;
              $response["error_msj"] = $respon->response;

              $response["rid"] = $json->rid;
          }*/
    } else {
        /* maneja un error, estableciendo código y identificador en una respuesta JSON. */

        $response = array();
        $response["error_code"] = 300020;
        $response["rid"] = $json->rid;

    }


} else {
    /* maneja un caso en el que se genera una respuesta con un error específico. */

    $response = array();
    $response["error_code"] = 20001;


    $response["rid"] = $json->rid;

}








