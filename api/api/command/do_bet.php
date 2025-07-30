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
 * Habilita la realización de una apuesta
 * @param int \$params->amount  Monto de la apuesta
 * @param array $params->bets Arreglo de apuestas a realizar
 * @param int $params->each_way  Indica si la apuesta es de tipo "each way"
 * @param string $params->mode  Modo de la apuesta
 * @param string $params->source  Fuente de la apuesta
 * @param string $params->type  Tipo de apuesta
 *
 * @return array
 *  - result:string Estado de la transacción
 *  - details:
 *     - number:int Número de la transacción
 */

//Recepción de parámetros
$params = $json->params;
$amount = $params->amount;
$bets = $params->bets;
$each_way = $params->each_way;
$mode = $params->mode;
$source = $params->source;
$type = $params->type;

//Solicitud objeto del usuario
$UsuarioMandante = new UsuarioMandante($json->session->usuario);// Se crea una nueva instancia de UsuarioMandante utilizando la información de sesión

$Mandante = new Mandante($UsuarioMandante->getMandante()); // Se crea una nueva instancia de Mandante utilizando el mandante del UsuarioMandante

if ($Mandante->propio == "S") {// Verifica si el mandante es propio

    $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante()); // Se crea una nueva instancia de Usuario utilizando el usuario del UsuarioMandante

    $Balance = $Usuario->getBalance(); // Se obtiene el balance del usuario

}
$response = array(); // Se inicializa un array para la respuesta
$response["code"] = 0; // Se establece el código de respuesta
$response["rid"] = $json->rid; // Se asigna el identificador de la solicitud
$response["data"] = array(); // Se inicializa el array de datos en la respuesta

if ($amount <= $Balance) {

    $Proveedor = new Proveedor("", "INSPIRED");

    /*  Obtenemos el producto con el gameId  */
    $Producto = new Producto("", "1", $Proveedor->getProveedorId());

    /*  Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego  */
    $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

    //Configuramos la transacción juego
    $TransaccionJuego = new TransaccionJuego();
    $TransaccionJuego->setProductoId($ProductoMandante->prodmandanteId);
    $TransaccionJuego->setTransaccionId('0');
    $TransaccionJuego->setTicketId('0');
    $TransaccionJuego->setValorTicket($amount);
    $TransaccionJuego->setValorPremio(0);
    $TransaccionJuego->setMandante($UsuarioMandante->mandante);
    $TransaccionJuego->setUsuarioId($UsuarioMandante->usumandanteId);
    $TransaccionJuego->setEstado("A");
    $TransaccionJuego->setPremiado("N");
    $TransaccionJuego->setFechaPago(date('Y-m-d H:i:s', time()));
    $TransaccionJuego->setUsucreaId(0);
    $TransaccionJuego->setUsumodifId(0);

    //Almacenamos la transacción
    $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO();
    $Transaction = $TransaccionJuegoMySqlDAO->getTransaction();

    $transaccion_id = $TransaccionJuego->insert($Transaction);

    $tipoTransaccion = "DEBIT";

    /*  Creamos el log de la transaccion juego para auditoria  */
    $TransjuegoLog = new TransjuegoLog();
    $TransjuegoLog->setTransjuegoId($transaccion_id);
    $TransjuegoLog->setTransaccionId('0');
    $TransjuegoLog->setTipo($tipoTransaccion);
    $TransjuegoLog->setTValue(json_encode('{}'));
    $TransjuegoLog->setUsucreaId(0);
    $TransjuegoLog->setUsumodifId(0);
    $TransjuegoLog->setValor($amount);

    $TransjuegoLog_id = $TransjuegoLog->insert($Transaction);

    foreach ($bets as $bet) {

        $arrayBet = array(
            ""
        );
        /*  Creamos el log de la transaccion juego para auditoria  */
        $TransjuegoLog = new TransjuegoLog();
        $TransjuegoLog->setTransjuegoId($transaccion_id);
        $TransjuegoLog->setTransaccionId('0');
        $TransjuegoLog->setTipo("EVENTO");
        $TransjuegoLog->setTValue(json_encode('{}'));
        $TransjuegoLog->setUsucreaId(0);
        $TransjuegoLog->setUsumodifId(0);
        $TransjuegoLog->setValor($amount);

        $TransjuegoLog_id = $TransjuegoLog->insert($Transaction);

    }

    /*  Obtenemos nuestro Usuario y hacemos el debito  */
    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
    $Usuario->debit($amount, $Transaction);

    $Transaction->commit();
    /*  Consultamos de nuevo el usuario para obtener el saldo  */
    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
    $Balance = $Usuario->getBalance();

    //Generamos el formato de respuesta
    $response["data"] = array(
        "result" => "OK",
        "details" => array(
            "number" => 1000
        )
    );


}


