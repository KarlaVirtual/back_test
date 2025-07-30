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
use Backend\integrations\casino\IES;
use Backend\integrations\casino\JOINSERVICES;
use Backend\mysql\BonoInternoMySqlDAO;
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
use Backend\mysql\UsuarioMarketingMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\UsuarioOtrainfoMySqlDAO;
use Backend\mysql\UsuarioPerfilMySqlDAO;
use Backend\mysql\UsuarioPremiomaxMySqlDAO;
use Backend\mysql\UsuarioRecargaMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\websocket\WebsocketUsuario;



$response = array();
$response["code"] = 0;
$response["rid"] = $json->rid;
$response["data"] = array(
    "result" => ""

);
$UsuarioMandante = new UsuarioMandante($json->session->usuario);
$Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

$id = $json->params->id;




if($Usuario->mandante == "0"){
    $Proveedor = new  Proveedor("","PAYMENTEZ");
}
if($Usuario->mandante == "8"){
    $Proveedor = new  Proveedor("","PAYMENTEZ");
}
if($Usuario->mandante == "13"){
    $Proveedor = new  Proveedor("","PAYMENTEZ");
}
if($Usuario->mandante == "2"){
    $Proveedor = new  Proveedor("","SAGICOR");
}

if($Usuario->mandante == "15"){
    $Proveedor = new  Proveedor("","N1CO");
}

switch ($Proveedor->getAbreviado()) {

    case 'PAYMENTEZ':

        $PAYMENTEZSERVICES = new Backend\integrations\payment\PAYMENTEZSERVICES();

        $data = $PAYMENTEZSERVICES->deleteCard($Usuario, $id);

        break;

    case 'N1CO':

        $N1COSERVICES = new Backend\integrations\payment\N1COSERVICES();

        $data = $N1COSERVICES->deleteCard($Usuario, $id);

        break;

    case 'SAGICOR':

        $SAGICORSERVICES = new Backend\integrations\payment\SAGICORSERVICES();

        $data = $SAGICORSERVICES->deleteCard($Usuario, $id);

        break;

}

if($data->success == "true"){
    $response = array();
    $response["code"] = 0;
    $response["rid"] = $json->rid;
    $response["data"] = array(
        "result" => $data->Message,

    );
}else{

    $response = array();
    $response["code"] = 1;
    $response["rid"] = $json->rid;
    $response["data"] = array(
        "result" => $data->Message);
}




