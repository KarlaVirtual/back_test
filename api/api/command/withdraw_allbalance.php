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
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\websocket\WebsocketUsuario;

/**
 * command/withdraw_allbalance
 *
 * Procesar solicitud de retiro
 *
 * Este recurso maneja la solicitud de retiro, verificando límites, calculando impuestos, y generando el recibo de retiro para el usuario.
 *
 * @param object $json : Objeto JSON con los parámetros necesarios para la solicitud.
 * @param float $json ->params->amount : Monto de la solicitud de retiro.
 * @param string $json ->params->service : Servicio asociado al retiro.
 * @param int $json ->params->id : Identificador del retiro.
 * @param int $json ->params->balance : Indica si es una recarga o retiro (0 = recargas, 1 = retiros).
 * @param string $json ->session->usuario : Usuario que realiza la solicitud.
 * @param string $json ->session->usuarioip : Dirección IP del usuario.
 *
 * @return array $response : Respuesta con el resultado de la operación.
 *  Objeto de respuesta:
 *  - *code* (int): Código de estado de la operación (0 si exitosa).
 *  - *result* (string): Mensaje de estado del proceso.
 *  - *data* (array): Detalles de la operación, incluyendo monto y fecha.
 *
 * @throws Exception : Fondos insuficientes (20001)
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* Código extrae parámetros de un JSON y asigna valores según el balance. */
$amount = $json->params->amount;
$service = $json->params->service;
$id = $json->params->id;
$balance = $json->params->balance; //0 es Recargas, 1 es Retiros
//$player = $json->params->player;
//$status_url = $player->status_url;
//$cancel_url = $status_url->status;
//$fail_url = $status_url->fail;
//$success_url = $status_url->success;


if ($balance == 1) {
    $creditos = $amount;

} elseif ($balance == 0) {
    /* Asigna el valor de $amount a $creditosBase si $balance es igual a cero. */

    $creditosBase = $amount;

}


/* Se instancia un usuario y un punto de venta para obtener el saldo disponible. */
$UsuarioMandante = new UsuarioMandante($json->session->usuario);

$ClientId = $UsuarioMandante->getUsuarioMandante();
$Usuario = new Usuario($ClientId);
$PuntoVenta = new PuntoVenta("", $ClientId);
//$Registro = new Registro("", $ClientId);

$amount = $Usuario->getBalance();

/* Se inicializan variables para calcular impuestos y penalidades sobre un monto. */
$amount = 100;

$valorFinal = $amount;
$valorImpuesto = 0;
$valorPenalidad = 0;
$creditos = 0;

/* Variable que inicializa el valor de créditos base en cero. */
$creditosBase = 0;

//$UsuarioMandante = new UsuarioMandante($json->session->usuario);
//$Usuario = new Usuario ($UsuarioMandante->getUsuarioMandante());

//Verificamos limite de minimo retiro
/*    $Clasificador = new Clasificador("", "MINWITHDRAW");
$minimoMontoPremios = 0;
try {
    $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
    $minimoMontoPremios = $MandanteDetalle->getValor();
} catch (Exception $e) {
}

if ($amount < $minimoMontoPremios) {
    throw new Exception("MINIMO MONTO PARA RETIROS" . $amount . "-" . $minimoMontoPremios, "54");
}

//Verificamos limite de maximo retiro
$Clasificador = new Clasificador("", "MAXWITHDRAW");
$maximooMontoPremios = -1;
try {
    $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
    $maximooMontoPremios = $MandanteDetalle->getValor();
} catch (Exception $e) {
}

if ($amount > $maximooMontoPremios && $maximooMontoPremios != -1) {
    throw new Exception("MAXIMO MONTO PARA RETIROS" . $amount . "-" . $minimoMontoPremios, "55");
}*/


//Verificamos impuesto retiro

//Si es de Saldo Premios
/* if ($creditos > 0) {

 $Clasificador = new Clasificador("", "TAXWITHDRAWAWARD");
 $impuesto = -1;
 try {
     $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
     $impuesto = $MandanteDetalle->getValor();
 } catch (Exception $e) {
 }

 if ($impuesto > 0) {
     $Clasificador = new Clasificador("", "TAXWITHDRAWAWARDFROM");
     $impuestoDesde = -1;
     try {
         $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
         $impuestoDesde = $MandanteDetalle->getValor();
     } catch (Exception $e) {
     }

     if ($impuestoDesde != -1) {
         if ($amount >= $impuestoDesde) {
             $valorImpuesto = ($impuesto / 100) * $valorFinal;
             $valorFinal = $valorFinal - $valorImpuesto;
         }
     }
 }
}*/

//Si es de Saldo Creditos
/* if ($creditosBase > 0) {
 $Clasificador = new Clasificador("", "TAXWITHDRAWDEPOSIT");
 $impuesto = -1;
 try {
     $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
     $impuesto = $MandanteDetalle->getValor();
 } catch (Exception $e) {
 }

 if ($impuesto > 0) {
     $Clasificador = new Clasificador("", "TAXWITHDRAWAWARDFROM");
     $impuestoDesde = -1;
     try {
         $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
         $impuestoDesde = $MandanteDetalle->getValor();
     } catch (Exception $e) {
     }

     if ($impuestoDesde != -1) {
         if ($amount >= $impuestoDesde) {
             $valorImpuesto = ($impuesto / 100) * $valorFinal;
             $valorFinal = $valorFinal - $valorImpuesto;
         }
     }
 }
}*/


/*$Consecutivo = new Consecutivo("", "RET", "");

$consecutivo_recarga = $Consecutivo->numero;

$consecutivo_recarga++;

$ConsecutivoMySqlDAO = new ConsecutivoMySqlDAO();

$Consecutivo->setNumero($consecutivo_recarga);


$ConsecutivoMySqlDAO->update($Consecutivo);

$ConsecutivoMySqlDAO->getTransaction()->commit();*/


/* Se crea un objeto CuentaCobro y se asigna un identificador de usuario. */
$CuentaCobro = new CuentaCobro();


//$CuentaCobro->cuentaId = $consecutivo_recarga;

$CuentaCobro->usuarioId = $ClientId;


/* Asignación de valores a propiedades del objeto `$CuentaCobro`, incluyendo fecha y usuario. */
$CuentaCobro->valor = $valorFinal;

$CuentaCobro->fechaPago = '';

$CuentaCobro->fechaCrea = date('Y-m-d H:i:s');


$CuentaCobro->usucambioId = 0;

/* Se inicializan propiedades de un objeto CuentaCobro para registrar información de pago. */
$CuentaCobro->usurechazaId = 0;
$CuentaCobro->usupagoId = 0;

$CuentaCobro->fechaCambio = $CuentaCobro->fechaCrea;
$CuentaCobro->fechaAccion = $CuentaCobro->fechaCrea;


$CuentaCobro->estado = 'A';

/* Se genera una clave encriptada para una cuenta de cobro utilizando AES. */
$clave = GenerarClaveTicket2(5);
$claveEncrypt_Retiro = "12hur12b";
$CuentaCobro->clave = "aes_encrypt('" . $clave . "','" . $claveEncrypt_Retiro . "')";

$CuentaCobro->mandante = '0';

$CuentaCobro->dirIp = $json->session->usuarioip;


/* Se asignan valores a propiedades del objeto CuentaCobro para su configuración. */
$CuentaCobro->impresa = 'S';

$CuentaCobro->mediopagoId = 0;
$CuentaCobro->puntoventaId = 0;

$CuentaCobro->costo = $valorPenalidad;

/* Asignación de valores a un objeto y creación de instancia para manejo de datos. */
$CuentaCobro->impuesto = $valorImpuesto;
$CuentaCobro->creditos = $creditos;
$CuentaCobro->creditosBase = $creditosBase;

$CuentaCobro->transproductoId = 0;

$CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();


/* Se inserta un registro y se genera una nota de retiro en HTML. */
$CuentaCobroMySqlDAO->insert($CuentaCobro);
$consecutivo_recarga = $CuentaCobro->cuentaId;

$status_message = '<table style="width:430px;height: 355px;/* border:1px solid black; */"><tbody><tr><td align="center" valign="top"><font style="text-align:center;font-size:20px;font-weight:bold;">NOTA DE RETIRO</font></td></tr><tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Nota de retiro No.:&nbsp;&nbsp;' . $consecutivo_recarga . '</font></td></tr><tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">No. de Cliente:&nbsp;&nbsp;' . $ClientId . '</font></td></tr><tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Nombre:&nbsp;&nbsp;' . $Usuario->nombre . '</font></td></tr><tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Fecha:&nbsp;&nbsp;' . $CuentaCobro->fechaCrea . '</font></td></tr><tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Clave:&nbsp;&nbsp;' . $clave . '</font></td></tr><tr><td align="center" valign="top"><div style="height:1px;">&nbsp;</div></td></tr>
<tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Valor a retirar:&nbsp;&nbsp;' . $amount . '</font></td></tr>
<tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Impuesto:&nbsp;&nbsp;' . $valorImpuesto . '</font></td></tr>
<tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Costo:&nbsp;&nbsp;' . $valorPenalidad . '</font></td></tr>
<tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Valor a entregar:&nbsp;&nbsp;' . $valorFinal . '</font></td></tr>

</tbody></table>';


/* Actualiza el crédito de usuario y lanza excepción si los fondos son insuficientes. */
$rowsUpdate = $Usuario->creditWin2(-$amount, $CuentaCobroMySqlDAO->getTransaction(), true);
if ($rowsUpdate == 0 || $rowsUpdate == false) {
    throw new Exception("Fondos insuficientes", "20001");
}
/*$PuntoVenta->setBalanceCreditosBase(-$amount);

$PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO($CuentaCobroMySqlDAO->getTransaction());

$PuntoVentaMySqlDAO->update($PuntoVenta);*/

$CuentaCobroMySqlDAO->getTransaction()->commit();

/*  Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket  */
/*$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());*/

/*  Enviamos el mensaje Websocket al Usuario para que actualice el saldo  */
/*$data = $Usuario->getWSMessage($UsuarioToken->getRequestId());
$WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
$WebsocketUsuario->sendWSMessage();*/

/*  Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket  */

/* Se genera un mensaje Websocket para actualizar el saldo del usuario. */
$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());
$data = $Usuario->getWSMessage($UsuarioToken->getRequestId());

/*  Enviamos el mensaje Websocket al Usuario para que actualice el saldo  */
$UsuarioSession = new UsuarioSession();
$rules = [];


/* Crea filtros para consultar usuarios en la sesión y los convierte a formato JSON. */
array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

$filtro = array("rules" => $rules, "groupOp" => "AND");
$json2 = json_encode($filtro);


$usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json2, true);


/* Se decodifica JSON, se inicializa un array y se encripta un ID de cuenta. */
$usuarios = json_decode($usuarios);

$usuariosFinal = [];
$ConfigurationEnvironment = new ConfigurationEnvironment();
$idW = $ConfigurationEnvironment->encrypt($CuentaCobro->cuentaId);

foreach ($usuarios->data as $key => $value) {


    /* Verifica la coincidencia de IDs y envía un mensaje a través de WebSocket. */
    if ($value->{'usuario_session.request_id'} == $UsuarioToken->getRequestId()) {

        $data2 = array(
            "machinePrint" => '',
            "messageIntern" => '',
            "continueToFront" => 1,
            "machinePrintURL" => 'https://operatorapi.virtualsoft.tech/machineprint/withdraw?id=' . $idW

        );
        $WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data2);
        $WebsocketUsuario->sendWSMessage($value->{'usuario_session.tipo'});

    }


    /* Reemplaza un ID en datos y envía un mensaje a través de WebSocket. */
    $dataF = str_replace("7040" . $UsuarioToken->getRequestId() . "1", "7040" . $value->{'usuario_session.request_id'} . "1", $data);
    $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $dataF);
    $WebsocketUsuario->sendWSMessage($value->{'usuario_session.tipo'});

}


/* Genera una respuesta JSON con detalles sobre una transacción o un retiro. */
$response = array();
$response["code"] = 0;
$response["rid"] = $json->rid;
$response["data"] = array(
    "result" => 0,
    "details" => array(
        "method" => $method,
        "status_message" => $status_message,
        "data" => array(
            "WithdrawId" => $consecutivo_recarga,
            "UserId" => $ClientId,
            "Name" => $Usuario->nombre,
            "date_time" => $CuentaCobro->fechaCrea,
            "Key" => $clave,
            "Amount" => $amount
        )

    )

);


/* Genera el HTML para una nota de retiro con información variable. */
$html_barcode = '<table style="width:430px;height: 355px;/* border:1px solid black; */"><tbody><tr><td align="center" valign="top"><font style="text-align:center;font-size:20px;font-weight:bold;">NOTA DE RETIRO</font></td></tr><tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Nota de retiro No.:&nbsp;&nbsp;' . $consecutivo_recarga . '</font></td></tr><tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">No. de Cliente:&nbsp;&nbsp;' . $ClientId . '</font></td></tr><tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Nombre:&nbsp;&nbsp;' . $Usuario->nombre . '</font></td></tr><tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Fecha:&nbsp;&nbsp;' . $CuentaCobro->fechaCrea . '</font></td></tr><tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Clave:&nbsp;&nbsp;' . $clave . '</font></td></tr><tr><td align="center" valign="top"><div style="height:1px;">&nbsp;</div></td></tr><tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Valor a retirar:&nbsp;&nbsp;' . $amount . '</font></td></tr></tbody></table>';

$html_barcode = "
<table style='width:180px;height:280px;border:1px solid black;'>
    <tr><td align='center' valign='top'><div style='height:1px;'>&nbsp;</div></td></tr>
    <tr><td align='center' valign='top'>
        <font style='text-align:center;font-size:20px;font-weight:bold;'>Nota de Retiro</font></td>
    </tr>
    <tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:13px;font-weight:normal;'>Nota No: :&nbsp;&nbsp;" . $consecutivo_recarga . "</font></td></tr>
    <tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:13px;font-weight:normal;'>No. de Cliente:&nbsp;&nbsp;" . $ClientId . "</font></td></tr>
    <tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:13px;font-weight:normal;'>Fecha:&nbsp;&nbsp;" . date('Y-m-d H:i:s') . "</font></td></tr>
    <tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:13px;font-weight:normal;'>Valor a Retirar:&nbsp;&nbsp;" . $amount . "</font></td></tr>
    <tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:13px;font-weight:normal;'>Impuesto:&nbsp;&nbsp;" . $valorImpuesto . "</font></td></tr>
    <tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:13px;font-weight:normal;'>Costo:&nbsp;&nbsp;" . $valorPenalidad . "</font></td></tr>
    <tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:13px;font-weight:normal;'>Valor a entregar:&nbsp;&nbsp;" . $valorFinal . "</font></td></tr>
    <tr><td align='center' valign='top'><div style='height:1px;'>&nbsp;</div></td></tr></table>";


/* crea un arreglo asociativo que almacena un código HTML de código de barras. */
$data = array(
    "html" => $html_barcode

);
$data = array(
    "html" => $html_barcode

);

$html = "

<!DOCTYPE html>
<html lang=\"en\" >

<head>

  <meta charset=\"UTF-8\">
  <title></title>
  
  
  
  
      <style>
      
      body {
    margin: 0px;
    display: inline-block;
}

      #invoice-POS {
  /*box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5);*/
  padding: 2mm;
  width: 44mm;
  background: #FFF;
}
#invoice-POS ::selection {
  background: #f31544;
  color: #FFF;
}
#invoice-POS ::moz-selection {
  background: #f31544;
  color: #FFF;
}
#invoice-POS h1 {
  font-size: 1.5em;
  color: #222;
}
#invoice-POS h2 {
  font-size: .9em;
}
#invoice-POS h3 {
  font-size: 1.2em;
  font-weight: 300;
  line-height: 2em;
}
#invoice-POS p {
  font-size: .7em;
  color: #171717;
  line-height: 1.2em;
}
#invoice-POS #top, #invoice-POS #mid, #invoice-POS #bot {
  /* Targets all id with 'col-' */
  border-bottom: 1px solid #EEE;
}
#invoice-POS #top {
  min-height: 70px;
}
#invoice-POS #mid {
  /*min-height: 80px;*/
} 
#invoice-POS #bot {
  min-height: 50px;
  margin-top: 5px;
}
#invoice-POS #top .logo {
  height: 60px;
  width: 60px;
  background: url(https://wplay.co/app/img/common/logo_redes.png) no-repeat;
  background-size: 60px 60px;
}
#invoice-POS .clientlogo {
  float: left;
  height: 60px;
  width: 60px;
  background: url(http://michaeltruong.ca/images/client.jpg) no-repeat;
  background-size: 60px 60px;
  border-radius: 50px;
}
#invoice-POS .info {
  display: block;
  margin-left: 0;
}
#invoice-POS .title {
  float: right;
}
#invoice-POS .title p {
  text-align: right;
}
#invoice-POS table {
  width: 100%;
  border-collapse: collapse;
}
#invoice-POS .tabletitle {
  font-size: .8em;
  background: #EEE;
}
#invoice-POS .service {
  border-bottom: 1px solid #EEE;
}
#invoice-POS .item {
  width: 24mm;
}
#invoice-POS .itemtext {
  font-size: .5em;
}
#invoice-POS #legalcopy {
  margin-top: 5mm;
}

    </style>
 
</head>

<body translate=\"no\" >

  
  <div id=\"invoice-POS\">
    
  
    <div id=\"mid\">
      <div class=\"info\">
        <h2 align='center'>Retiro Total</h2>
        <p> 
            <b>Fecha:</b> " . $CuentaCobro->fechaCrea . "</br>
            <b>Transacción:</b> " . $consecutivo_recarga . "</br>
        </p>
      </div>
    </div><!--End Invoice Mid-->
    
    <div id=\"bot\">

					<div id=\"table\">
						<table>
							<tr class=\"tabletitle\">
								<td class=\"item\"><h2>Valor</h2></td>
								<td class=\"Hours\"><h2>" . $CuentaCobro->valor . "</h2></td>
							</tr>
							<tr class=\"tabletitle\">
								<td class=\"item\" colspan=\"2\"><img style='    width: 100%; height: 70px;' src='http://198.199.120.164/api/barcode.php?f=png&s=code-128&d=" . $clave . "'></td>
							</tr>

							<!--<tr class=\"service\">
								<td class=\"tableitem\"><p class=\"itemtext\">Communication</p></td>
								<td class=\"tableitem\"><p class=\"itemtext\">5</p></td>
								<td class=\"tableitem\"><p class=\"itemtext\">$375.00</p></td>
							</tr>


							<tr class=\"tabletitle\">
								<td></td>
								<td class=\"Rate\"><h2>tax</h2></td>
								<td class=\"payment\"><h2>$419.25</h2></td>
							</tr>

							<tr class=\"tabletitle\">
								<td></td>
								<td class=\"Rate\"><h2>Total</h2></td>
								<td class=\"payment\"><h2>$3,644.25</h2></td>
							</tr>-->

						</table>
					</div><!--End Table-->

					<div id=\"legalcopy\">
						<p class=\"legal\" align='center'>  <strong>Vive la emoción de ganar</strong>
						<p class=\"legal\" align='center'>  <strong>Solo es demostrativo</strong>
						</p>
					</div>

				</div><!--End InvoiceBot-->
  </div><!--End Invoice-->
  
  </body>

</html>
 

";


/* Construye una respuesta estructurada con detalles sobre un proceso de transacción. */
$response["data"] = array(
    "result" => 0,
    "details" => array(
        "method" => $method,
        "status_message" => $status_message,
        "data" => array(
            "WithdrawId" => $consecutivo_recarga,
            "UserId" => $ClientId,
            "Name" => $Usuario->nombre,
            "date_time" => $CuentaCobro->fechaCrea,
            "Key" => $clave,
            "Amount" => $amount
        )

    ),
    "machinePrint" => $html,
    "continueToFront" => 1

);
/*  Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket  */
/*$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());
$data = $Usuario->getWSMessage($UsuarioToken->getRequestId());*/

/*  Enviamos el mensaje Websocket al Usuario para que actualice el saldo  */


/*$Proveedor = new Proveedor("", "IES");
$UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $UsuarioMandante->getUsumandanteId());
$WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
$WebsocketUsuario->sendWSMessage();*/

/* $Proveedor = new Proveedor("", "IES");
$UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $UsuarioMandante->getUsumandanteId());
$WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
$WebsocketUsuario->sendWSMessage();


*/