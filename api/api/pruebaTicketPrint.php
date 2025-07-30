<?php
/**
* Prueba ticket print
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
use Backend\dto\Proveedor;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioSession;
use Backend\dto\UsuarioToken;
use Backend\dto\Usuario;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\websocket\WebsocketUsuario;
require_once __DIR__ . '../../vendor/autoload.php';

syslog(LOG_WARNING, "TEST");

error_reporting(E_ALL);
ini_set("display_errors","ON");

$UsuarioMandante = new UsuarioMandante('524430');

/*  Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket  */
$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());
//$data = $Usuario->getWSMessage($UsuarioToken->getRequestId());

/*  Enviamos el mensaje Websocket al Usuario para que actualice el saldo  */

$ConfigurationEnvironment=new \Backend\dto\ConfigurationEnvironment();
$id = $ConfigurationEnvironment->encrypt('48808717');
print_r($id);

$UsuarioSession = new UsuarioSession();
$rules = [];

array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

$filtro = array("rules" => $rules, "groupOp" => "AND");
$json2 = json_encode($filtro);
$Usuario = new Usuario($UsuarioMandante->usuarioMandante);

$data = $Usuario->getWSMessage($UsuarioToken->getRequestId());


$usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 1000000, $json2, true);

$usuarios = json_decode($usuarios);

$usuariosFinal = [];

$requestsIds=array();
foreach ($usuarios->data as $key => $value) {
    array_push($requestsIds,$value->{'usuario_session.request_id'});

}

foreach ($usuarios->data as $key => $value) {
    $dataF = str_replace("7040" . $UsuarioToken->getRequestId() . "1","7040" . $value->{'usuario_session.request_id'} . "1",json_encode($data));
    $dataF = json_decode($dataF);
    $dataF = str_replace("99100","7040" . $value->{'usuario_session.request_id'} . "1",json_encode($dataF));
    $dataF = json_decode($dataF);

    //$dataF = $UsuarioMandante->getWSProfileSite($value->{'usuario_session.request_id'});
    //$dataF = $UsuarioMandante->getWSProfileSite($value->{'usuario_session.request_id'});

    $data2 = array(
        "machinePrint" => '',
        "machinePrint22" => 'https://operatorapi.virtualsoft.tech/machineprint/deposit?id='.$id,
        "messageIntern" => '',
        "continueToFront" => 1,
        "machinePrintURL" => 'https://operatorapi.virtualsoft.tech/machineprint/deposit?id='.$id

    );

    $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data2);
    $WebsocketUsuario->sendWSMessage($value->{'usuario_session.tipo'});


    foreach ($requestsIds as $requestsId) {
        $WebsocketUsuario = new WebsocketUsuario($requestsId, $dataF);
        $WebsocketUsuario->sendWSMessage($value->{'usuario_session.tipo'});

    }


}

syslog(LOG_WARNING, "COMMAND :" . "MACHINE ENTRO");
exit();

exit();
$mensajesRecibidos=[];
$array = [];

$array["body"] = '¡ Bien :thumbsup: ! Sumaste '. '11'.' puntos en '.'22'.' :clap:';

array_push($mensajesRecibidos, $array);
$data = array();
$data["messages"] = $mensajesRecibidos;
//$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

$UsuarioSession = new UsuarioSession();
$rules = [];

array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
array_push($rules, array("field" => "usuario_session.usuario_id", "data" => 16, "op" => "eq"));

$filtro = array("rules" => $rules, "groupOp" => "AND");
$json = json_encode($filtro);


$usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

$usuarios = json_decode($usuarios);
print_r($usuarios);
$usuariosFinal = [];

foreach ($usuarios->data as $key => $value) {

    $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data);
    $WebsocketUsuario->sendWSMessage();

}

exit();

$ticket_id = $argv[1];

if($ticket_id ==""){
    $ticket_id=$_REQUEST["ticketid"];
    $clave=$_REQUEST["clave"];

}


$html="<table style='width:180px;height:280px;border:1px solid black;'><tr><td align='center' valign='top'><img style=\"max-height:  60px;\" src='http://198.199.120.164/assets/images/logo.png'></td></tr><tr><td align='center' valign='top'><div style='height:1px;'>&nbsp;</div></td></tr><tr><td align='center' valign='top'><font style='text-align:center;font-size:20px;font-weight:bold;'>RECARGA</font></td></tr><tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:13px;font-weight:normal;'>Recarga No.:&nbsp;&nbsp;0000000863</font></td></tr><tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:13px;font-weight:normal;'>No. de Cliente:&nbsp;&nbsp;".$Usuario->usuarioId."</font></td></tr><tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:13px;font-weight:normal;'>Nombre:&nbsp;&nbsp;".$Usuario->nombre."</font></td></tr><tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:13px;font-weight:normal;'>Fecha:&nbsp;&nbsp;".date('Y-m-d H:i:s')."</font></td></tr><tr><td align='center' valign='top'><div style='height:1px;'>&nbsp;</div></td></tr><tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:15px;font-weight:bold;'>Valor :&nbsp;&nbsp;".$creditAmount."</font></td></tr></table>";

$html_barcode="

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
  background: url(/app/img/common/logo_redes.png) no-repeat;
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

  <script>
  window.console = window.console || function(t) {};
</script>

  
  
  <script>
  if (document.location.search.match(/type=embed/gi)) {
    window.parent.postMessage(\"resize\", \"*\");
  }
</script>


</head>

<body translate=\"no\" >

  
  <div id=\"invoice-POS\">
    
    <center id=\"top\">
      <div class=\"logo\"></div>
      <!--<div class=\"info\"> 
        <h2>SBISTechs Inc</h2>
      </div>-->
    </center>
    
    <div id=\"mid\">
      <div class=\"info\">
        <h2 align='center'>Recarga</h2>
        <p> 
            <b>Fecha:</b> 2019-04-02 08:47:44</br>
            <b>Transacción:</b> 00056</br>
        </p>
      </div>
    </div><!--End Invoice Mid-->
    
    <div id=\"bot\">

					<div id=\"table\">
						<table>
							<tr class=\"tabletitle\">
								<td class=\"item\"><h2>Valor</h2></td>
								<td class=\"Hours\"><h2>2.000</h2></td>
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
						</p>
					</div>

				</div><!--End InvoiceBot-->
  </div><!--End Invoice-->
  
  </body>

</html>
 

";


/*
$UsuarioLog = new UsuarioLog();
$UsuarioLog->setUsuarioId(0);
$UsuarioLog->setUsuarioIp('222');
$UsuarioLog->setUsuariosolicitaId(0);
$UsuarioLog->setUsuariosolicitaIp('222');
$UsuarioLog->setTipo("ESTADOUSUARIO");
$UsuarioLog->setEstado("A");
$UsuarioLog->setValorAntes("CREATETICKET");
$UsuarioLog->setValorDespues("");
$UsuarioLog->setUsucreaId(0);
$UsuarioLog->setUsumodifId(0);
$UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO($Transaction);
$UsuarioLogMySqlDAO->insert($UsuarioLog);
$UsuarioLogMySqlDAO->getTransaction()->commit();
*/
$Proveedor = new Proveedor("", "IES");

$data = array(
    "messageIntern"=>"updBase",
    "value"=>"https://devadmin.doradobet.com/api/api/pruebadescarga/upd.zip"

);
$data = array(
    "messageIntern"=>"updApp",
    "value"=>"https://devadmin.doradobet.com/api/api/pruebadescarga/machine.zip"

);

$data = array(
    "messageIntern"=>"execCommand",
    "value"=>"sudo reboot"

);
$data = array(
    "machinePrint"=>$html_barcode

);

$data2 = array(
    "messageIntern"=>"execCommand",
    "value"=>"sudo unzip /home/pc/Escritorio/CARP/machine.zip"

);

$data = array(
    "machinePrint"=>$html_barcode

);

//$UsuarioToken = new UsuarioToken("",$Proveedor->getProveedorId(), 203);
$UsuarioToken = new UsuarioToken("",$Proveedor->getProveedorId(), 524373);

$WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);

$WebsocketUsuario->sendWSMessage();


$UsuarioToken = new UsuarioToken("",'0', 524373);
$UsuarioMandante = new UsuarioMandante(524373);
$Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
//$data = $Usuario->getWSMessage($UsuarioToken->getRequestId());

print_r($data);


/*  Enviamos el mensaje Websocket al Usuario para que actualice el saldo  */
$UsuarioSession = new UsuarioSession();
$rules = [];

array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));
array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $UsuarioMandante->getUsumandanteId(), "op" => "eq"));

$filtro = array("rules" => $rules, "groupOp" => "AND");
$json2 = json_encode($filtro);


$usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json2, true);

$usuarios = json_decode($usuarios);

$usuariosFinal = [];

foreach ($usuarios->data as $key => $value) {

    $dataF = str_replace("7040" . $UsuarioToken->getRequestId() . "1","7040" . $value->{'usuario_session.request_id'} . "1",$data);
    $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $dataF);
    $WebsocketUsuario->sendWSMessage($value->{'usuario_session.tipo'});

}

//print_r($data);
//print_r($UsuarioToken);

//$WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);

//$WebsocketUsuario->sendWSMessage();