<?php

use Backend\dto\Banco;
use Backend\dto\BonoDetalle;
use Backend\dto\BonoInterno;
use Backend\dto\AuditoriaGeneral;
use Backend\dto\Categoria;
use Backend\dto\Clasificador;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Consecutivo;
use Backend\dto\CriptoRed;
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
use Backend\dto\Template;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioBanco;
use Backend\dto\UsuarioConfig;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\UsuarioHistorial;
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
use Backend\dto\UsuarioBono;
use Backend\integrations\casino\IES;
use Backend\integrations\casino\JOINSERVICES;
use Backend\integrations\payment\PRONTOPAGASERVICES;
use Backend\integrations\payout\EZZEPAYSERVICES;
use Backend\integrations\payout\PAYBROKERSSERVICES;
use \Backend\integrations\risk\RISKSERVICES;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\ConsecutivoMySqlDAO;
use Backend\mysql\AuditoriaGeneralMySqlDAO;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\DocumentoUsuarioMySqlDAO;
use Backend\mysql\PromocionalLogMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\mysql\RegistroMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\UsuarioBancoMySqlDAO;
use Backend\mysql\UsuarioBannerMySqlDAO;
use Backend\mysql\UsuarioConfiguracionMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
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
use Dompdf\Dompdf;

/**
 * command/withdraw
 *
 * Recurso que administra retiros, regargas para los usuarios, puntos de ventas, Agentes ...
 *
 *
 * @param string $amount : Monto a acreditar
 * @param string $service : Servicio que genera la nota de retiro (Local, Agente, Banco ...)
 * @param string $id : Id dinamico (Puede ser de usuario, punto de venta, banco ...)
 * @param string $latitud : Coordenada de la ubicación
 * @param string $longitud : Coordenada de la ubicación
 * @param string $site_id : Partner asociado
 * @param string $balance : 0 es Recargas 1 es Retiros
 * @param string $infoExtra : Información extra de la nota de retiro
 *
 * @return object El objeto $response es un array con los siguientes atributos:
 * - 'code' => int Código de respuesta (0 si es exitoso).
 * - 'data' => array Datos de la respuesta:
 * - 'msg' => string Mensaje de confirmación.
 * - 'codeTime' => int Tiempo de expiración del código OTP.
 * - 'WithdrawId' => string Identificador del retiro.
 * - 'confirmOTP' => bool Indica si se requiere confirmación OTP.
 * - 'service' => string Nombre del servicio asociado.
 * - 'rid' => string Identificador único de la solicitud.
 * - 'data' => array Detalles adicionales:
 * - 'result' => int Resultado del proceso (0 si es exitoso).
 * - 'details' => array Información detallada:
 * - 'method' => string Método utilizado.
 * - 'status_message' => string Mensaje de estado.
 * - 'status_messagePdf' => string Mensaje de estado en PDF.
 * - 'status_messageHTML' => string Mensaje de estado en HTML.
 * - 'data' => array Información específica del usuario:
 * - 'WithdrawId' => string Identificador del retiro.
 * - 'UserId' => string Identificador del usuario.
 * - 'Name' => string Nombre del usuario.
 * - 'date_time' => string Fecha y hora de la operación.
 * - 'Key' => string Clave generada.
 * - 'Amount' => float Monto de la transacción.
 *
 *
 * @throws Exception Inusual Detected (100001)
 * @throws Exception We are currently in the process of maintaining the site. (30004)
 * @throws Exception El usuario ingresado está autoexcluido. (20027)
 * @throws Exception El usuario debe tener mínimo un depósito para poder retirar. (300053)
 * @throws Exception El usuario debe verificar el correo para poder retirar. (300054)
 * @throws Exception El usuario debe verificar el celular para poder retirar. (300055)
 * @throws Exception La cuenta necesita estar verificada para poder retirar. (21004)
 * @throws Exception El registro debe de estar aprobado para poder retirar. (21005)
 * @throws Exception No puede realizar retiros porque no tiene activo el permiso de ubicación. (21032)
 * @throws Exception Fondos insuficientes. (20001)
 * @throws Exception Celular no verificado. (100095)
 * @throws Exception Valor menor al mínimo permitido para retirar. (21002)
 * @throws Exception No puedes generar una nota de retiro hasta no cumplir 24 horas de tu registro. (300015)
 * @throws Exception No puedes generar una nota de retiro hasta no realizar tu primer depósito. (300016)
 * @throws Exception No es posible realizar retiros en el sitio actualmente. (300006)
 * @throws Exception Tienes el máximo de notas de retiro permitidas activas. (21011)
 * @throws Exception Ha excedido el valor máximo de retiro por punto de venta por día. (21026)
 * @throws Exception No puede realizar retiros por este medio después de las 9:00 pm. (21031)
 */


/* Extrae valores específicos del objeto JSON en variables PHP. */
$amount = $json->params->amount;
$service = $json->params->service;
$id = $json->params->id;
$latitud = $json->params->lat;
$longitud = $json->params->lng;
$site_id = $json->params->site_id;
$remove_bonuses = $json->params->remove_bonuses;


/* Construye ubicaciones y maneja datos de balance y información adicional desde JSON. */
$ubicacion = $longitud . " " . $latitud;

$localizacion = '{"lat":"' . $latitud . '","lng":"' . $longitud . '"}';

$balance = $json->params->balance; //0 es Recargas, 1 es Retiros
//$player = $json->params->player;
//$status_url = $player->status_url;
//$cancel_url = $status_url->status;
//$fail_url = $status_url->fail;
//$success_url = $status_url->success;

$infoExtra = $json->params->infoExtra;

/* asigna información y define una clave y un monto final. */
$info1 = $infoExtra->info1;
$info2 = $infoExtra->info2;

$claveEncrypt_Retiro = "12hur12b";

$valorFinal = $amount;

/* Se inicializan variables y se asigna "amount" a "creditos". */
$valorImpuesto = 0;
$valorImpuesto2 = 0;
$valorPenalidad = 0;
$creditos = 0;
$creditosBase = 0;

/*if ($balance == 1) {
$creditos = $amount;

} elseif ($balance == 0) {
$creditosBase = $amount;

}*/
$creditos = $amount;


/* crea objetos para usuario y registro, y verifica montos negativos. */
$UsuarioMandante = new UsuarioMandante($json->session->usuario);

$ClientId = $UsuarioMandante->getUsuarioMandante();
$Usuario = new Usuario($ClientId);
$Registro = new Registro("", $ClientId);

$UsuarioBono = new UsuarioBono();

try {
    $Clasificador = new Clasificador('', 'CONTINGENCIARETIROSRETAIL');
    $UsuarioConfiguracion = new UsuarioConfiguracion($ClientId, 'A', $Clasificador->getClasificadorId());
    $IsActivateContingencyRetailWithdrawals = $UsuarioConfiguracion->getEstado();
    // Verificación de contingencia de retiros retail
    if ($IsActivateContingencyRetailWithdrawals == 'A' && $service == 'local') {
        throw new Exception("Este usuario no puede usar puntos de venta o red aliadas, comuníquese con soporte.", 300152);
    }
} catch (Exception $e) {
    if ($e->getCode() == 300152) throw $e;
}


/* Lógica para cancelar bonos con rollover de Altenar si se va a crear una nota de retiro */
if ($remove_bonuses === true) $UsuarioBono->cancelarBonoActivoAltenar($Usuario->usuarioId, $Usuario->mandante);
else if ($UsuarioBono->existeBonoActivoAltenar($Usuario->usuarioId, $Usuario->mandante)) throw new Exception('Tienes un bono activo, al crear tu nota de retiro será cancelado de manera permanente ¿Quieres continuar?', 300060);

if ($amount < 0) {
    throw new Exception("Inusual Detected", "100001");
}

if ($UsuarioMandante->paisId == 66) {
    //throw new Exception('We are currently in the process of maintaining the site.', 30004);
}


/* El bloque "try" se utiliza para manejar excepciones en código, intentando ejecutar instrucciones. */
try {
    $Clasificador = new Clasificador('', 'TOTALCONTINGENCEWITHDRAWAL');
    $MandanteDetalle = new MandanteDetalle('', '-1', $Clasificador->getClasificadorId(), '0', 'A');

    if ($MandanteDetalle->getValor() == 1) throw new Exception('We are currently in the process of maintaining the site.', 30004);
} catch (Exception $ex) {
    if ($ex->getCode() == 30004) throw $ex;
}


/* Bloque de código en programación utilizado para manejar excepciones y errores potenciales. */
try {
    $Clasificador = new Clasificador('', 'TOTALCONTINGENCEWITHDRAWAL');
    $MandanteDetalle = new MandanteDetalle('', '-1', $Clasificador->getClasificadorId(), '0', 'A');

    if ($MandanteDetalle->getValor() == 1) throw new Exception('We are currently in the process of maintaining the site.', 30004);
} catch (Exception $ex) {
    if ($ex->getCode() == 30004) throw $ex;
}

if ($Usuario->contingenciaRetiro == 'A') {
    throw new Exception("El usuario ingresado esta autoexcluido. ", "20027");
}


/* Código que intenta ejecutar una acción y maneja posibles excepciones o errores. */
try {
    $Clasificador = new Clasificador('', 'FIRSTDEPOSITWITHDRAW');
    $MandanteDetalle = new MandanteDetalle('', $Usuario->mandante, $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');

    if ($MandanteDetalle->getValor() === 'A') {
        $UsuarioRecarga = new UsuarioRecarga();

        $rules = [];
        array_push($rules, ['field' => 'usuario_recarga.usuario_id', 'data' => $Usuario->usuarioId, 'op' => 'eq']);
        array_push($rules, ['field' => 'usuario_recarga.estado', 'data' => 'A', 'op' => 'eq']);
        array_push($rules, ['field' => 'usuario_recarga.mandante', 'data' => $Usuario->mandante, 'op' => 'eq']);

        $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

        $queryUserDeposit = (string)$UsuarioRecarga->getUsuarioRecargasCustom('usuario_recarga.*', 'usuario_recarga.recarga_id', 'ASC', 0, 1, $filters, true);
        $queryUserDeposit = json_decode($queryUserDeposit, true);

        if ($queryUserDeposit['count'][0]['.count'] == 0) throw new Exception('El usuario debe tener minimo un deposito para poder retirar', 300053);
    }

} catch (Exception $ex) {
    if ($ex->getCode() === 300053) throw $ex;
}


/* inicia un bloque para manejar excepciones en programación. */
try {
    $Clasificador = new Clasificador('', 'VERIFMAILWITHDRAW');
    $MandanteDetalle = new MandanteDetalle('', $Usuario->mandante, $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');

    if ($MandanteDetalle->valor === 'A' && $Usuario->verifCorreo !== 'S') throw new Exception('El usuario debe verificar el correo para porder retirar', 300054);
} catch (Exception $ex) {
    if ($ex->getCode() === 300054) throw $ex;
}


/* Intento ejecutar código, capturando errores si suceden. */
try {
    $Clasificador = new Clasificador('', 'VERIFPHONEWITHDRAW');
    $MandanteDetalle = new MandanteDetalle('', $Usuario->mandante, $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');

    if ($MandanteDetalle->valor === 'A' && $Usuario->verifCelular !== 'S') throw new Exception('El usuario debe verificar el celular para poder retirar', 300055);
} catch (Exception $ex) {
    if ($ex->getCode() === 300055) throw $ex;
}


/* crea un clasificador y obtiene un valor del detalle del mandante. */
try {
    $Clasificador = new Clasificador("", "RISKSTATUS");
    $MandanteDetalle = new MandanteDetalle('', $Usuario->mandante, $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
    $valor = $MandanteDetalle->getValor();
} catch (Exception $e) {

}


if ($valor == 1) {


    try {

        /* obtiene datos de riesgo del usuario y los organiza en un array. */
        $RiskAPI = new RISKSERVICES();

        $datosRiesgo = $RiskAPI->riskData($Usuario->usuarioId);

        $data = $datosRiesgo[0];
        $params = [
            'userid' => $data['user_id'],  // Tomando el valor desde el array $data
            'deposits' => $data['deposits'],
            'withdrawals' => $data['withdrawals'],
            'profit' => $data['profit'],
            'wager' => $data['wager'],
            'bonus' => $data['bonus'],
            'withdraw_similarity' => $data['withdraw_similarity'],
            'sports_margin' => $data['sports_margin'],
            'casino_margin' => $data['casino_margin'],
            'days_registration' => $data['days_registration']
        ];


// Enviar los datos al servicio y recibir la respuesta

        /* envía datos a una API y maneja errores si ocurren. */
        $response = $RiskAPI->sendData($params);


        $errorData = [];

        if (isset($response['coderror'])) {
            $errorData['coderror'] = $response['coderror'];
            $errorData['code'] = $response['code'];

            $codigoError = $errorData['coderror'];
            $mensajeError = $errorData['code'];

        }


        /* Actualiza la claveTV del usuario si el riesgo predictivo ha cambiado. */
        if (isset($response["Predictions"])) {
            $predictionsStr = $response["Predictions"];

            $parts = explode(": ", $predictionsStr);

            if (count($parts) == 2 && is_numeric($parts[1])) {
                $riskValue = $parts[1];

                if ($Usuario->claveTv != $riskValue) {
                    $Usuario->claveTv = $riskValue;

                    $UsuarioMySqlDAO = new UsuarioMySqlDAO();
                    $transaction = $UsuarioMySqlDAO->getTransaction();
                    $UsuarioMySqlDAO->update($Usuario);
                    $transaction->commit();
                }
            }
        }

    } catch (Exception $e) {
        /* Captura excepciones en PHP y permite manejar errores sin interrumpir la ejecución. */


    }

}


/* Verifica condiciones específicas para enviar correo al usuario en un entorno de configuración. */
$ConfigurationEnvironment = new ConfigurationEnvironment();


if (($Usuario->mandante == 14) && date('Y-m-d H:i:s', strtotime($Usuario->fechaCrea)) >= date('Y-m-d H:i:s', strtotime('2023-04-17 00:00:00')) && $Usuario->verifCorreo == "N" && false) {
    $ConfigurationEnvironment->isCheckdUsuOnlineEmail($Usuario);
}

/* Condiciones que verifican el estado de un usuario y su verificación de correo electrónico. */
if (($Usuario->mandante == 17) && date('Y-m-d H:i:s', strtotime($Usuario->fechaCrea)) >= date('Y-m-d H:i:s', strtotime('2023-04-17 00:00:00')) && $Usuario->verifCorreo == "N") {
// $ConfigurationEnvironment->isCheckdUsuOnlineEmail($Usuario);
}
if (($Usuario->mandante == 0 && $Usuario->paisId == 2) && $Usuario->verifCorreo == "N") {
    $ConfigurationEnvironment->isCheckdUsuOnlineEmail($Usuario);
}

/* Verifica condiciones específicas del usuario antes de comprobar su correo electrónico. */
if (($Usuario->mandante == 0 && $Usuario->paisId == 46) && $Usuario->verifCorreo == "N") {
    $ConfigurationEnvironment->isCheckdUsuOnlineEmail($Usuario);
}
if (($Usuario->mandante == 0 && $Usuario->paisId == 66) && $Usuario->verifCorreo == "N") {
    $ConfigurationEnvironment->isCheckdUsuOnlineEmail($Usuario);
}


/* verifica la cuenta del usuario antes de permitir retiros. */
try {
    $Clasificador = new Clasificador("", "ACCVERIFFORWITHDRAW");
    $minimoMontoPremios = 0;

    $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');


    if (($Usuario->verifcedulaPost != "S" || $Usuario->verifcedulaAnt != 'S') && $MandanteDetalle->valor == "1") {
        throw new Exception("La cuenta necesita estar verificada para poder retirar", "21004");
    }

} catch (Exception $e) {
    /* Manejo de excepciones, relanza errores excepto códigos 34 y 41. */

    if ($e->getCode() != 34 && $e->getCode() != 41) {
        throw $e;
    }
}


/* valida un registro antes de permitir un retiro basado en condiciones específicas. */
try {
    $Clasificador = new Clasificador("", "ACTREGFORWITHDRAW");
    $minimoMontoPremios = 0;

    $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');

    if ($Registro->estadoValida != 'A' && $MandanteDetalle->valor == "1") {
        throw new Exception("El registro debe de estar aprobado para poder retirar", "21005");
    }

} catch (Exception $e) {
    /* maneja excepciones filtrando ciertos códigos de error. */

    if ($e->getCode() != 34 && $e->getCode() != 41) {
        throw $e;
    }
}


/* Verifica que las coordenadas no estén vacías y valida permisos de ubicación del usuario. */
if ($longitud == '' || $latitud == '') {
    if ($Usuario->mandante == 13 && false) {
        throw new Exception("No puede realizar retiros porque no tiene activo el permiso de ubicación.", "21032");

    }
}


/* Verifica si los créditos son suficientes y lanza una excepción si no lo son. */
if ($creditosBase > 0) {
    if ($Registro->getCreditosBase() < $creditosBase) {
        throw new Exception("Fondos insuficientes", "20001");
    }
}
if ($creditos > 0) {
    if ($Registro->getCreditos() < $creditos) {
        throw new Exception("Fondos insuficientes", "20001");
    }
}


/* verifica si el celular del usuario está validado tras crear un clasificador. */
try {
    $Clasificador = new Clasificador('', 'PHONEVERIFICATION');
    $tipoDetalle = $Clasificador->getClasificadorId();
    $MandanteDetalle = new MandanteDetalle('', $site_id, $tipoDetalle, $Usuario->paisId, '', 3);
    if ($MandanteDetalle->estado == 'A') {
        if ($Usuario->verifCelular == 'N') {
            throw new Exception('Celular no verificado.', 100095);
        }
    }
} catch (Exception $e) {
    if ($e->getCode() == 100095) throw $e;
}


//$UsuarioMandante = new UsuarioMandante($json->session->usuario);
//$Usuario = new Usuario ($UsuarioMandante->getUsuarioMandante());

//Verificamos limite de minimo retiro

/* establece el monto mínimo de premios según el servicio del usuario. */
$minimoMontoPremios = 0;

try {
    if ($service == "UserAgent") {
        $Clasificador = new Clasificador("", "MINWITHDRAWDAYKASNET");
        $minimoMontoPremios = 0;

        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
        $minimoMontoPremios = $MandanteDetalle->getValor();
    } else {

        $Clasificador = new Clasificador("", "MINWITHDRAW");
        $minimoMontoPremios = 0;

        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
        $minimoMontoPremios = $MandanteDetalle->getValor();
    }
} catch (Exception $e) {
    /* Captura excepciones en PHP sin realizar ninguna acción dentro del bloque. */

}

/* Verifica mínimos de retiro y restricciones según fecha de registro y depósito del usuario. */
if ($minimoMontoPremios > 0 && $amount < $minimoMontoPremios) {
    throw new Exception("Valor menor al minimo permitido para retirar" . $amount . "-" . $minimoMontoPremios, "21002");
}

if ($Usuario->mandante == '8') {
    if (strpos($Usuario->fechaCrea, date("Y-m-d")) !== false) {

        throw new Exception("No puedes generar una nota de retiro hasta no cumplir 24 horas de tu registro", "300015");

    }

    if (strpos($Usuario->fechaPrimerdeposito, date("Y-m-d")) !== false) {

        throw new Exception("No puedes generar una nota de retiro hasta no realizar tu primer deposito", "300016");

    }

}

/* Valida condiciones para permitir un retiro después de 24 horas y tras primer depósito. */
if ($Usuario->mandante == '0' && $Usuario->paisId == '66') {
    if (strpos($Usuario->fechaCrea, date("Y-m-d")) !== false) {

        throw new Exception("No puedes generar una nota de retiro hasta no cumplir 24 horas de tu registro", "300015");

    }

    if (strpos($Usuario->fechaPrimerdeposito, date("Y-m-d")) !== false) {

        throw new Exception("No puedes generar una nota de retiro hasta no realizar tu primer deposito", "300016");

    }

}

/* verifica condiciones antes de permitir un retiro de un usuario. */
if ($Usuario->mandante == '0' && $Usuario->paisId == '94') {
    if (strpos($Usuario->fechaCrea, date("Y-m-d")) !== false) {

        throw new Exception("No puedes generar una nota de retiro hasta no cumplir 24 horas de tu registro", "300015");

    }

    if (strpos($Usuario->fechaPrimerdeposito, date("Y-m-d")) !== false) {

        throw new Exception("No puedes generar una nota de retiro hasta no realizar tu primer deposito", "300016");

    }

}

//Verificamos limite de minimo retiro por punto de venta

if ($service == "local") {

    /* Se crea un clasificador y se obtiene un valor mínimo del punto de venta. */
    try {
        $Clasificador = new Clasificador("", "MINWITHDRAWBETSHOP");
        $minimoMontoPuntodeVenta = 0;

        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
        $minimoMontoPuntodeVenta = $MandanteDetalle->getValor();
    } catch (Exception $e) {
        /* Bloque para manejar excepciones en PHP sin realizar ninguna acción. */

    }


    /* verifica montos de retiro, lanzando excepciones si son insuficientes. */
    if ($amount < 30 && $id == '5758546') {
        throw new Exception("Valor menor al minimo permitido para retirar" . $amount . "-" . 30, "21002");
    }
    if ($amount < $minimoMontoPuntodeVenta && $id != '853460') {
        throw new Exception("Valor menor al minimo permitido para retirar" . $amount . "-" . $minimoMontoPuntodeVenta, "21002");
    }


    /* Lanza una excepción si las condiciones de usuario y monto no se cumplen. */
    if ($UsuarioMandante->mandante == '0' and $UsuarioMandante->paisId == '173' and floatval($amount) > 3000) {
        throw new Exception("Valor menor al minimo permitido para retirar" . $amount . "-", "21003");

    }

}


/* Validación de monto mínimo para retiros, lanzando excepción si no se cumple. */
if ($service != "local" && $id != '18625') {
    try {
        $Clasificador = new Clasificador("", "MINWITHDRAWACCBANK");
        $minimoMontoCuentaBancaria = 0;

        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
        $minimoMontoCuentaBancaria = $MandanteDetalle->getValor();
    } catch (Exception $e) {
    }

    if ($amount < $minimoMontoCuentaBancaria) {
        throw new Exception("Valor menor al minimo permitido para retirar" . $amount . "-" . $minimoMontoPuntodeVenta, "21002");
    }
}

/* Valida si el monto de retiro cumple el mínimo requerido para servicios no locales. */
if ($service != "local" && $id == '18625') {
    $minimoMontoCuentaBancaria = 30;
    if ($amount < $minimoMontoCuentaBancaria) {
        throw new Exception("Valor menor al minimo permitido para retirar" . $amount . "-" . $minimoMontoPuntodeVenta, "21002");
    }
}

//Verificamos limite de maximo retiro

/* Se inicializa un clasificador y obtiene el monto máximo de premios. */
try {
    $Clasificador = new Clasificador("", "MAXWITHDRAW");
    $maximooMontoPremios = -1;

    $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
    $maximooMontoPremios = $MandanteDetalle->getValor();
} catch (Exception $e) {
    /* Maneja excepciones en PHP sin realizar ninguna acción cuando ocurre un error. */

}


/* Valida si el monto a cobrar supera el límite permitido y lanza una excepción. */
if ($amount > $maximooMontoPremios && $maximooMontoPremios != -1 && $maximooMontoPremios != 0) {
    throw new Exception("Valor mayor al máximo permitido para retirar" . $amount . "-" . $maximooMontoPremios, "21003");
}

if ($Usuario->mandante == 0 && $Usuario->paisId == 173 && $service != "local") {
    $maximooMontoPremios = 70000;
    if ($amount > $maximooMontoPremios && $maximooMontoPremios != -1 && $maximooMontoPremios != 0) {
        throw new Exception("Valor mayor al máximo permitido para retirar" . $amount . "-" . $maximooMontoPremios, "21003");
    }

}

/* Verifica si el monto de retiro excede el límite máximo permitido. */
if ($service == "local") {

//Verificamos limite de maximo retiro
    try {
        $Clasificador = new Clasificador("", "MAXWITHDRAWBETSHOP");
        $maximooMontoPremios = -1;

        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
        $maximooMontoPremios = $MandanteDetalle->getValor();
    } catch (Exception $e) {
    }

    if ($amount > $maximooMontoPremios && $maximooMontoPremios != -1 && $maximooMontoPremios != 0) {
        throw new Exception("Valor mayor al máximo permitido para retirar" . $amount . "-" . $maximooMontoPremios, "21003");
    }
}


// validacion de clasificador de retiro usuario online


/* verifica si un usuario puede realizar un retiro y lanza una excepción si no. */
try {
    $Clasificador = new Clasificador("", "WITHDRAWALUSUONLINE");

    $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');

    if ($MandanteDetalle->valor == "A") {
        throw new Exception("No es posible realizar retiros en el sitio actualmente", "300006");
    }

} catch (Exception $e) {
    /* Captura excepciones, re-lanza si el código no es 34 o 41. */

    if ($e->getCode() != 34 && $e->getCode() != 41) {
        throw $e;
    }
}

//Verificamos maximo de retiros activos

/* inicializa un clasificador y obtiene la cantidad máxima de solicitudes activas. */
try {
    $Clasificador = new Clasificador("", "MAXWITHDRAWACTIVEREQUEST");
    $maximoCantidadSolicitudesActivas = 0;

    $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
    $maximoCantidadSolicitudesActivas = $MandanteDetalle->getValor();
} catch (Exception $e) {
    /* Captura excepciones en PHP y evita errores sin procesar, aunque no maneja el error. */

}
//Verificar Maximos de Retiros Usuario Prueba

/* Verifica límites de usuario en pruebas y lanza excepción si excede permitido. */
try {
    $Clasificador = new Clasificador('', 'LIMITPERCLIENTTEST');
    $UsuarioConfiguracion = new UsuarioConfiguracion($ClientId, 'A', $Clasificador->getClasificadorId(), 5);
    if (floatval($UsuarioConfiguracion->getValor()) > 0 && $UsuarioConfiguracion->getValor() < $amount) {
        throw new Exception('Limites para usuarios de pruebas', 300018);
    }
} catch (Exception $e) {
    if ($e->getCode() == 300018) throw $e;
}

if ($maximoCantidadSolicitudesActivas != 0 && $maximoCantidadSolicitudesActivas != '') {


    /* Se inicializan variables para manejar límites y reglas en un proceso de datos. */
    $MaxRows = 1;
    $OrderedItem = 1;
    $SkeepRows = 0;


    $rules = [];


    /* crea un filtro en formato JSON para consultas de datos. */
    array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "A", "op" => "eq"));
    array_push($rules, array("field" => "cuenta_cobro.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $jsonfiltro = json_encode($filtro);

    $CuentaCobro = new CuentaCobro();


    /* Se obtienen y decodifican datos de cuentas de cobro, sumando valores y contando elementos. */
    $cuentas = $CuentaCobro->getCuentasCobroCustom("sum(cuenta_cobro.valor) sum, count(cuenta_cobro.cuenta_id) cant ", "cuenta_cobro.cuenta_id", "asc", $SkeepRows, $MaxRows, $jsonfiltro, true, "cuenta_cobro.usuario_id");

    $cuentas = json_decode($cuentas);

    $sum = $cuentas->data[0]->{'.sum'};
    $cant = $cuentas->count[0]->{'.count'};


    /* Verifica si la cantidad supera el máximo permitido y lanza una excepción si es cierto. */
    if (intval($cant) != 0 && intval($cant) >= $maximoCantidadSolicitudesActivas) {
        throw new Exception("Tienes el máximo de notas de retiro permitidas activas", "21011");

    }
}

//Verificamos maximo de retiros activos

/* inicializa un clasificador y obtiene solicitudes activas del mandante. */
try {
    $Clasificador = new Clasificador("", "MAXWITHDRAWDAY");
    $maximoCantidadSolicitudesActivasDia = 0;

    $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
    $maximoCantidadSolicitudesActivasDia = $MandanteDetalle->getValor();
} catch (Exception $e) {
    /* Captura excepciones en PHP, permitiendo manejar errores sin interrumpir el flujo del programa. */

}

if ($maximoCantidadSolicitudesActivasDia != '' && $maximoCantidadSolicitudesActivasDia != '0') {


    /* Se definen variables para controlar filas y órdenes en un conjunto de reglas. */
    $MaxRows = 1;
    $OrderedItem = 1;
    $SkeepRows = 0;


    $rules = [];


    /* Se crean reglas de filtrado para consulta de datos JSON en PHP. */
    array_push($rules, array("field" => "cuenta_cobro.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
    array_push($rules, array("field" => "cuenta_cobro.fecha_crea", "data" => date('Y-m-d'), "op" => "bw"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $jsonfiltro = json_encode($filtro);


    /* Se crea una instancia y se obtienen cuentas de cobro resumen en formato JSON. */
    $CuentaCobro = new CuentaCobro();

    $cuentas = $CuentaCobro->getCuentasCobroCustom("sum(cuenta_cobro.valor) sum, count(cuenta_cobro.cuenta_id) cant ", "cuenta_cobro.cuenta_id", "asc", $SkeepRows, $MaxRows, $jsonfiltro, true, "cuenta_cobro.usuario_id");

    $cuentas = json_decode($cuentas);

    $sum = $cuentas->data[0]->{'.sum'};

    /* Verifica si el número de solicitudes activas supera el límite permitido y lanza una excepción. */
    $cant = $cuentas->count[0]->{'.count'};

    if (intval($cant) != 0 && intval($cant) >= $maximoCantidadSolicitudesActivasDia) {
        throw new Exception("Tienes el máximo de notas de retiro permitidas activas", "21011");

    }
}

if ($service == "local") {

    if ($Usuario->mandante == 8 && $Usuario->paisId == 66 && $service == "local") {


        /* Variables inicializan límites y configuraciones para gestionar solicitudes y reglas en el código. */
        $maximoCantidadSolicitudesActivas = 1;

        $MaxRows = 1;
        $OrderedItem = 1;
        $SkeepRows = 0;


        $rules = [];


        /* Se generan reglas de filtrado para la consulta de cuentas de cobro. */
        array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "'A','I','P','S','M'", "op" => "in"));
        array_push($rules, array("field" => "cuenta_cobro.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
        array_push($rules, array("field" => "cuenta_cobro.fecha_crea", "data" => date('Y-m-d'), "op" => "bw"));
// array_push($rules, array("field" => "cuenta_cobro.puntoventa_id", "data" => '0', "op" => "ne"));
// array_push($rules, array("field" => "cuenta_cobro.version", "data" => '2', "op" => "eq"));

        $filtro = array("rules" => $rules, "groupOp" => "AND");

        /* codifica un filtro JSON y obtiene cuentas de cobro específicas. */
        $jsonfiltro = json_encode($filtro);

        $CuentaCobro = new CuentaCobro();

        $cuentas = $CuentaCobro->getCuentasCobroCustom("sum(cuenta_cobro.valor) sum, count(cuenta_cobro.cuenta_id) cant ", "cuenta_cobro.cuenta_id", "asc", $SkeepRows, $MaxRows, $jsonfiltro, true, "cuenta_cobro.usuario_id");

        $cuentas = json_decode($cuentas);


        /* verifica si el retiro supera el límite diario permitido y lanza una excepción. */
        $sum = $cuentas->data[0]->{'.sum'};
        $cant = $cuentas->count[0]->{'.count'};

        if ((floatval($sum) + $valorFinal) > 10000) {
            throw new Exception("Ha excedido el valor maximo de retiro por punto de venta por día.", "21026");

        }
    }


    if ($id != '') {


        if ($Usuario->mandante == 8 && $Usuario->paisId == 66 && $service == "local") {


            /* Variables inicializan límites y reglas para gestionar solicitudes activas en un sistema. */
            $maximoCantidadSolicitudesActivas = 1;

            $MaxRows = 1;
            $OrderedItem = 1;
            $SkeepRows = 0;


            $rules = [];


            /* Crea un filtro de reglas para consultas sobre cuentas por cobrar. */
            array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "'A','I','P','S'", "op" => "in"));
            array_push($rules, array("field" => "cuenta_cobro.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
            array_push($rules, array("field" => "cuenta_cobro.fecha_crea", "data" => date('Y-m-d'), "op" => "bw"));
            array_push($rules, array("field" => "cuenta_cobro.puntoventa_id", "data" => '0', "op" => "ne"));
// array_push($rules, array("field" => "cuenta_cobro.version", "data" => '2', "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");

            /* procesa datos de cuentas de cobro, generando un JSON para filtrado. */
            $jsonfiltro = json_encode($filtro);

            $CuentaCobro = new CuentaCobro();

            $cuentas = $CuentaCobro->getCuentasCobroCustom("sum(cuenta_cobro.valor) sum, count(cuenta_cobro.cuenta_id) cant ", "cuenta_cobro.cuenta_id", "asc", $SkeepRows, $MaxRows, $jsonfiltro, true, "cuenta_cobro.usuario_id");

            $cuentas = json_decode($cuentas);


            /* verifica si el retiro excede un límite diario, lanzando una excepción si es así. */
            $sum = $cuentas->data[0]->{'.sum'};
            $cant = $cuentas->count[0]->{'.count'};

            if ((floatval($sum) + $valorFinal) > 500) {
                throw new Exception("Ha excedido el valor maximo de retiro por punto de venta por día.", "21026");

            }
        }


        /* Restricción para no permitir retiros después de las 9:00 pm para ciertos usuarios. */
        if ($Usuario->mandante == 8 && $Usuario->paisId == 66 && $service == "local" && date('H:i:s') >= '21:00:00') {
            throw new Exception("No puede realizar retiros por este medio después de las 9:00 pm", "21031");
        }
    }

}

/** Verificando si usuario excede límite máximo valor a retirar por día para usuarios online */
$currentDate = date('Y-m-d');
$CuentaCobro = new CuentaCobro();
$booleanMaxAmountUserWithdrawal = $CuentaCobro->usuarioSuperaMaximoMontoRetiroDiario($Usuario->usuarioId, $valorFinal, $currentDate);
if ($booleanMaxAmountUserWithdrawal) throw new Exception('Límite valor en retiros alcanzado', 300034);

//Verificamos impuesto retiro

//Si es de Saldo Premios
if ($creditos > 0) {


    /* Calcula impuestos utilizando clasificadores y detalles de mandante en un bloque try. */
    try {
        $Clasificador = new Clasificador("", "TAXWITHDRAWAWARD");
        $impuesto = -1;

        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
        $impuesto = $MandanteDetalle->getValor();
//$valorImpuesto = $amount * ($impuesto/100);


        $Clasificador = new Clasificador("", "TAXWITHDRAWAWARDISR");
        $impuesto2 = -1;

        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
        $impuesto2 = $MandanteDetalle->getValor();
        $valorImpuesto2 = $amount * ($impuesto2 / 100);


    } catch (Exception $e) {
        /* Captura excepciones en PHP sin realizar ninguna acción dentro del bloque catch. */

    }

    if ($impuesto > 0) {

        /* Crea un clasificador y obtiene un valor de impuesto desde un detalle mandante. */
        try {
            $Clasificador = new Clasificador("", "TAXWITHDRAWAWARDFROM");
            $impuestoDesde = -1;

            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
            $impuestoDesde = $MandanteDetalle->getValor();
        } catch (Exception $e) {
            /* Bloque que captura excepciones en PHP sin realizar ninguna acción específica. */

        }


        /* Calcula impuestos conditionales basados en el monto y dos porcentajes de impuesto. */
        if ($impuestoDesde != -1) {
            if ($amount >= $impuestoDesde) {
                $valorImpuesto = ($impuesto / 100) * $valorFinal;
                if ($impuesto2 > 0) {
                    $valorImpuesto2 = ($amount - $valorImpuesto) * ($impuesto2 / 100);
//$valorFinal = $valorFinal - $valorImpuesto;

                }
            }
        }
    }
}

//Si es de Saldo Creditos
if ($creditosBase > 0) {

    /* Código que crea un clasificador y obtiene el valor del impuesto asociado a un mandante. */
    try {
        $Clasificador = new Clasificador("", "TAXWITHDRAWDEPOSIT");
        $impuesto = -1;

        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
        $impuesto = $MandanteDetalle->getValor();
    } catch (Exception $e) {
        /* Bloque que captura excepciones en PHP, permitiendo manejar errores sin interrumpir la ejecución. */

    }


    /* Calcula impuestos basados en condiciones específicas y valores proporcionados. */
    if ($impuesto > 0) {
        try {
            $Clasificador = new Clasificador("", "TAXWITHDRAWAWARDFROM");
            $impuestoDesde = -1;

            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
            $impuestoDesde = $MandanteDetalle->getValor();
        } catch (Exception $e) {
        }

        if ($impuestoDesde != -1) {
            if ($amount >= $impuestoDesde) {
                $valorImpuesto = ($impuesto / 100) * $valorFinal;
                if ($impuesto2 > 0) {
                    $valorImpuesto2 = ($amount - $valorImpuesto) * ($impuesto2 / 100);
//$valorFinal = $valorFinal - $valorImpuesto;
                }
            }
        }
    }
}

/*
$Consecutivo = new Consecutivo("", "RET", "");

$consecutivo_recarga = $Consecutivo->numero;

$consecutivo_recarga++;

$ConsecutivoMySqlDAO = new ConsecutivoMySqlDAO();

$Consecutivo->setNumero($consecutivo_recarga);


$ConsecutivoMySqlDAO->update($Consecutivo);

$ConsecutivoMySqlDAO->getTransaction()->commit();*/


$CuentaCobro = new CuentaCobro();


//$CuentaCobro->cuentaId = $consecutivo_recarga;


/* Se están asignando valores a un objeto CuentaCobro en PHP. */
$CuentaCobro->usuarioId = $ClientId;

$CuentaCobro->valor = $valorFinal;

$CuentaCobro->fechaPago = '';

$CuentaCobro->fechaCrea = date('Y-m-d H:i:s');


/* Se inicializan identificadores y fechas en el objeto CuentaCobro. */
$CuentaCobro->usucambioId = 0;
$CuentaCobro->usurechazaId = 0;
$CuentaCobro->usupagoId = 0;

$CuentaCobro->fechaCambio = $CuentaCobro->fechaCrea;
$CuentaCobro->fechaAccion = $CuentaCobro->fechaCrea;


/* asigna 'M' o 'A' a estadoCuentaCobro según condiciones específicas. */
$estadoCuentaCobro = 'A';

if (in_array($Usuario->mandante, array(8)) && ($service == "local")) {
    $estadoCuentaCobro = 'M';
}

if (in_array($Usuario->mandante, array(0)) && $Usuario->paisId == 66 && ($service == "local")) {
    $estadoCuentaCobro = 'M';
}


/* Se establece 'M' en estado de cuenta según condiciones del mandante y servicio. */
if (in_array($Usuario->mandante, array(3, 4, 5, 6, 7)) && ($service == "local")) {
    $estadoCuentaCobro = 'M';
}
if (in_array($Usuario->mandante, array(8)) && ($service == "local") && $CuentaCobro->valor >= 300) {
    $estadoCuentaCobro = 'M';
}

/* asigna 'M' a estadoCuentaCobro si se cumplen ciertas condiciones. */
if (in_array($Usuario->mandante, array(23)) && $CuentaCobro->valor > 1000 && $service != "local") {
    $estadoCuentaCobro = 'M';
}

if ($Usuario->mandante == 8 && $Usuario->paisId == 66 && $estadoCuentaCobro == "A" && ($service == "local")) {


    /* Se inician variables para controlar solicitudes activas y configuración de filas en un sistema. */
    $maximoCantidadSolicitudesActivas = 1;

    $MaxRows = 1;
    $OrderedItem = 1;
    $SkeepRows = 0;


    $rules = [];


    /* Se definen reglas de filtrado para cuentas de cobro usando un array. */
    array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "'A','I','P','S','M'", "op" => "in"));
    array_push($rules, array("field" => "cuenta_cobro.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
    array_push($rules, array("field" => "cuenta_cobro.fecha_crea", "data" => date('Y-m-d'), "op" => "bw"));
    // array_push($rules, array("field" => "cuenta_cobro.puntoventa_id", "data" => '0', "op" => "ne"));
    // array_push($rules, array("field" => "cuenta_cobro.version", "data" => '2', "op" => "eq"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");

    /* realiza una consulta y devuelve datos filtrados en formato JSON. */
    $jsonfiltro = json_encode($filtro);

    $CuentaCobro2 = new CuentaCobro();

    $cuentas = $CuentaCobro2->getCuentasCobroCustom("sum(cuenta_cobro.valor) sum, count(cuenta_cobro.cuenta_id) cant ", "cuenta_cobro.cuenta_id", "asc", $SkeepRows, $MaxRows, $jsonfiltro, true, "cuenta_cobro.usuario_id");

    $cuentas = json_decode($cuentas);


    /* Calcula el estado de cuenta según la cantidad de registros. */
    $sum = $cuentas->data[0]->{'.sum'};
    $cant = $cuentas->count[0]->{'.count'};

    if ((floatval($cant)) >= 1) {
        $estadoCuentaCobro = 'M';
    }
}

//SE VERIFICA SI EL MANDANTE TIENE ACTIVO LA APROBACION DE LAS NOTAS DE RETIROS POR PUNTO DE VENTA

/* Verifica el servicio y configura detalles de un mandante en caso de éxito. */
if ($service == "local") {
    try {
        $Clasificador = new Clasificador('', 'ACTIVATEWITHDRAWALNOTES');
        $tipoDetalle = $Clasificador->getClasificadorId();
        $MandanteDetalle = new MandanteDetalle('', $Usuario->mandante, $tipoDetalle, $Usuario->paisId, 'A');
        if (isset($MandanteDetalle)) {
            $estadoCuentaCobro = 'M';
        }
    } catch (Exception $e) {

    }
}


/* Establece el estado y genera una clave encriptada para la cuenta de cobro. */
$CuentaCobro->estado = $estadoCuentaCobro;
$clave = GenerarClaveTicket2(5);

$CuentaCobro->clave = "aes_encrypt('" . $clave . "','" . $claveEncrypt_Retiro . "')";

$CuentaCobro->mandante = $Usuario->mandante;


/* Asignación de propiedades en el objeto CuentaCobro para manejar información de sesión. */
$CuentaCobro->dirIp = $json->session->usuarioip;

$CuentaCobro->impresa = 'S';

$CuentaCobro->mediopagoId = '0';
$CuentaCobro->puntoventaId = '0';


/* Asignación de valores a propiedades del objeto CuentaCobro. */
$CuentaCobro->costo = $valorPenalidad;
$CuentaCobro->impuesto = $valorImpuesto;
$CuentaCobro->impuesto2 = $valorImpuesto2;
$CuentaCobro->creditos = $creditos;
$CuentaCobro->creditosBase = $creditosBase;

$CuentaCobro->transproductoId = 0;

/* Asignación de puntaje y obtención de dirección IP del cliente en un entorno configurado. */
$CuentaCobro->puntajeJugador = $riskValue;


$ConfigurationEnvironment = new ConfigurationEnvironment();

$dirIp = substr($ConfigurationEnvironment->get_client_ip(), 0, 50);

/* gestiona el estado y medio de pago basándose en el ID del servicio. */
$CuentaCobro->setDirIp($dirIp);

$status_messagePDF = "";
$status_messageHTML = "";

if ($service == "local") {

    if ($id != '') {
        if ($id == '5996264') {
            $CuentaCobro->puntoventaId = $id;
            $CuentaCobro->estado = 'P';

        }
        $CuentaCobro->mediopagoId = $id;
        $CuentaCobro->version = 2;
    }
    if ($id == '2088007') {
        $CuentaCobro->estado = 'M';

    }

}

if ($CuentaCobro->valor <= 500 && date('Y-m-d H:i:s') >= '2023-08-02 00:00:00') {
    if ($UsuarioTokenSite != null && false) {

        $cookie = $UsuarioTokenSite->getCookie();

        if ($cookie != '' && $cookie != null) {
            $cookie = json_decode($cookie);
            if ($cookie != null && boolval($cookie->esSeguro) == true) {

                if (boolval($cookie->esSeguro) == true) {

                    if (($CuentaCobro->getMediopagoId() == "" || $CuentaCobro->getMediopagoId() == "0")) {
                        $CuentaCobro->setEstado('A');
                    } else {
                        $CuentaCobro->setEstado('P');
                    }
                    if ($CuentaCobro->getMediopagoId() != "" && ($CuentaCobro->getVersion() == "2")) {
                        $CuentaCobro->setEstado('A');
                    }
                    if ($CuentaCobro->getMediopagoId() == "2088007" && ($CuentaCobro->getVersion() == "2")) {
                        $CuentaCobro->setEstado('P');
                    }
                    if ($service == "UserBank") {
                        $CuentaCobro->setEstado('P');
                    }

                    exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . 'esSeguro ' . $Usuario->usuarioId . "' '#dev' > /dev/null & ");

                }
            }
        }
    }
    if (date('Y-m-d H:i:s') >= '2023-06-22 00:00:00' &&
        $Usuario->mandante == 8 && false
    ) {

        /* Se crea un objeto y consulta datos de usuarios en una base de datos MySQL. */
        $BonoInterno = new BonoInterno();

        $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();


        $sql = "SELECT * FROM usuario_saldoresumen where usuario_id='" . $Usuario->usuarioId . "'";

        /* ejecuta una consulta SQL y inicializa saldos a cero. */
        $data = $BonoInterno->execQuery('', $sql);
        $data = $data[0];

        $saldo_recarga = 0;
        $saldo_apuestas = 0;
        $saldo_premios = 0;

        /* Variables inicializan saldos para diferentes tipos de transacciones y ajustes financieros. */
        $saldo_notaret_pagadas = 0;
        $saldo_notaret_pend = 0;
        $saldo_ajustes_entrada = 0;
        $saldo_ajustes_salida = 0;
        $saldo_bono = 0;
        $saldo_notaret_creadas = 0;

        /* Variables inicializan saldos para apuestas y premios en un sistema de casino. */
        $saldo_apuestas_casino = 0;
        $saldo_premios_casino = 0;
        $saldo_notaret_eliminadas = 0;
        $saldo_bono_free_ganado = 0;
        $saldo_bono_casino_free_ganado = 0;
        $saldo_bono_casino_vivo = 0;

        /* Variables inicializan saldos relacionados con bonos y apuestas en casino virtual y vivo. */
        $saldo_bono_casino_vivo_free_ganado = 0;
        $saldo_bono_virtual = 0;
        $saldo_bono_virtual_free_ganado = 0;
        $saldo_apuestas_casino_vivo = 0;

        if ($data != null) {


            /* Se convierten saldos de un objeto JSON a valores flotantes en variables. */
            $saldo_recarga = floatval($data->{'usuario_saldoresumen.saldo_recarga'});
            $saldo_apuestas = floatval($data->{'usuario_saldoresumen.saldo_apuestas'});
            $saldo_premios = floatval($data->{'usuario_saldoresumen.saldo_premios'});
            $saldo_notaret_pagadas = floatval($data->{'usuario_saldoresumen.saldo_notaret_pagadas'});
            $saldo_notaret_pend = floatval($data->{'usuario_saldoresumen.saldo_notaret_pend'});
            $saldo_ajustes_entrada = floatval($data->{'usuario_saldoresumen.saldo_ajustes_entrada'});

            /* Se convierten a float diferentes saldos del resumen del usuario para su tratamiento. */
            $saldo_ajustes_salida = floatval($data->{'usuario_saldoresumen.saldo_ajustes_salida'});
            $saldo_bono = floatval($data->{'usuario_saldoresumen.saldo_bono'});
            $saldo_notaret_creadas = floatval($data->{'usuario_saldoresumen.saldo_notaret_creadas'});
            $saldo_apuestas_casino = floatval($data->{'usuario_saldoresumen.saldo_apuestas_casino'});
            $saldo_premios_casino = floatval($data->{'usuario_saldoresumen.saldo_premios_casino'});
            $saldo_notaret_eliminadas = floatval($data->{'usuario_saldoresumen.saldo_notaret_eliminadas'});

            /* Se extraen y convierten a float los saldos de bonos del usuario. */
            $saldo_bono_free_ganado = floatval($data->{'usuario_saldoresumen.saldo_bono_free_ganado'});
            $saldo_bono_casino_free_ganado = floatval($data->{'usuario_saldoresumen.saldo_bono_casino_free_ganado'});
            $saldo_bono_casino_vivo = floatval($data->{'usuario_saldoresumen.saldo_bono_casino_vivo'});
            $saldo_bono_casino_vivo_free_ganado = floatval($data->{'usuario_saldoresumen.saldo_bono_casino_vivo_free_ganado'});
            $saldo_bono_virtual = floatval($data->{'usuario_saldoresumen.saldo_bono_virtual'});
            $saldo_bono_virtual_free_ganado = floatval($data->{'usuario_saldoresumen.saldo_bono_virtual_free_ganado'});

            /* Convierte el saldo de apuestas en casino vivo a formato float. */
            $saldo_apuestas_casino_vivo = floatval($data->{'usuario_saldoresumen.saldo_apuestas_casino_vivo'});
        }


        /* Realiza una consulta SQL para sumar apuestas y premios por usuario en una fecha específica. */
        $sql = "
select usuario_id,sum(vlr_apuesta) as vlr_apuesta,sum(vlr_premio) as vlr_premio
from it_ticket_enc
where fecha_cierre = '" . date('Y-m-d') . "' and usuario_id = '" . $Usuario->usuarioId . "'";
        $data = $BonoInterno->execQuery('', $sql);
        $data = $data[0];

        /* suma apuestas y premios según tipos de transacciones en una consulta SQL. */
        if ($data != null) {
            $saldo_apuestas = $saldo_apuestas + floatval($data->{'it_ticket_enc.vlr_apuesta'});
            $saldo_premios = $saldo_premios + floatval($data->{'it_ticket_enc.vlr_premio'});
        }

        $sql = "
        SELECT usuario_mandante.moneda,
       COUNT(transaccion_juego.transjuego_id)                                                             count,
       SUM(CASE WHEN transaccion_juego.tipo != 'FREESPIN' THEN transaccion_juego.valor_ticket ELSE 0 END) apuestas,
       SUM(CASE WHEN transaccion_juego.tipo != 'FREESPIN' THEN transaccion_juego.valor_premio ELSE 0 END) premios,
       SUM(CASE WHEN transaccion_juego.tipo = 'FREESPIN' THEN transaccion_juego.valor_ticket ELSE 0 END)  apuestasBonus,
       SUM(CASE WHEN transaccion_juego.tipo = 'FREESPIN' THEN transaccion_juego.valor_premio ELSE 0 END)  premiosBonus,
       SUM(transaccion_juego.valor_gratis)                                                                apuestasSaldogratis
FROM transaccion_juego
         INNER JOIN usuario_mandante ON (usuario_mandante.usumandante_id = transaccion_juego.usuario_id)
where 1 = 1
  AND ((transaccion_juego.fecha_crea)) >= '" . date('Y-m-d') . " 00:00:00'
  AND ((transaccion_juego.fecha_crea)) < '" . date('Y-m-d') . " 23:59:59'
  AND ((usuario_mandante.usuario_mandante)) = '" . $Usuario->usuarioId . "'
  
  ";

        /* consulta datos y actualiza los saldos de apuestas y premios en casino. */
        $data = $BonoInterno->execQuery('', $sql);
        $data = $data[0];
        if ($data != null) {
            $saldo_apuestas_casino = $saldo_apuestas_casino + floatval($data->{'.apuestas'});
            $saldo_premios_casino = $saldo_premios_casino + floatval($data->{'.premios'});
            $saldo_premios_casino = $saldo_premios_casino + floatval($data->{'.premiosBonus'});
        }


        /* Consulta la suma de recargas de un usuario en un día específico. */
        $sql = "
select usuario_id,sum(usuario_recarga.valor) as valor
from usuario_recarga
where fecha_crea LIKE '" . date('Y-m-d') . "%' and usuario_id = '" . $Usuario->usuarioId . "'";
        $data = $BonoInterno->execQuery('', $sql);
        $data = $data[0];

        /* suma el valor de recarga si $data no es nulo y realiza una consulta SQL. */
        if ($data != null) {
            $saldo_recarga = $saldo_recarga + floatval($data->{'usuario_recarga.valor'});
        }


        $sql = "
select usuario_id,sum(cuenta_cobro.valor) as valor
from cuenta_cobro
where fecha_pago  LIKE '" . date('Y-m-d') . "%' and usuario_id = '" . $Usuario->usuarioId . "'";
        $data = $BonoInterno->execQuery('', $sql);
        $data = $data[0];
        if ($data != null) {
            $saldo_notaret_pagadas = $saldo_notaret_pagadas + floatval($data->{'cuenta_cobro.valor'});
        }


        /* Código verifica si el saldo retenido permite un retiro seguro del usuario. */
        $esSeguro = true;

//Usuario no haya retirado menos del 90% de lo depositado
        if ($saldo_notaret_pagadas > ($saldo_recarga * 0.85)) {
            $esSeguro = false;
        }

//Usuario tiene que jugar un 1.6 el valor depositado

        /* evalúa condiciones para determinar si una apuesta es segura o no. */
        if (($saldo_apuestas + $saldo_apuestas_casino + $saldo_apuestas_casino_vivo) < ($saldo_recarga * 1.6)) {
            $esSeguro = false;
        }

//Usuario tiene que perder un 30% del valor depositado
        if ((($saldo_apuestas + $saldo_apuestas_casino) - ($saldo_premios + $saldo_premios_casino)) < 300) {
            $esSeguro = false;
        }


        /* ajusta el estado de una cuenta de cobro según condiciones específicas. */
        if ($esSeguro) {

            if (($CuentaCobro->getMediopagoId() == "" || $CuentaCobro->getMediopagoId() == "0")) {
                $CuentaCobro->setEstado('A');
            } else {
                $CuentaCobro->setEstado('P');
            }
            if ($CuentaCobro->getMediopagoId() != "" && ($CuentaCobro->getVersion() == "2")) {
                $CuentaCobro->setEstado('A');
            }
            if ($CuentaCobro->getMediopagoId() == "2088007" && ($CuentaCobro->getVersion() == "2")) {
                $CuentaCobro->setEstado('P');
            }
            if ($service == "UserBank") {
                $CuentaCobro->setEstado('P');
            }

            exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . 'esSeguro ' . $Usuario->usuarioId . "' '#dev' > /dev/null & ");

        }


    }

}

if (date('Y-m-d H:i:s') < '2023-08-02 00:00:00') {

    if (date('Y-m-d H:i:s') >= '2023-06-22 00:00:00' &&
        $Usuario->mandante == 8 && false
    ) {
        $BonoInterno = new BonoInterno();

        $BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();


        if (date('Y-m-d H:i:s') < '2023-07-17 00:00:00') {

            /* Se consulta saldo del usuario en la base de datos y se inicializan variables. */
            $sql = "SELECT * FROM usuario_saldoresumen where usuario_id='" . $Usuario->usuarioId . "'";
            $data = $BonoInterno->execQuery('', $sql);
            $data = $data[0];

            $saldo_recarga = 0;
            $saldo_apuestas = 0;

            /* Inicializa variables para gestionar saldos de premios, notas y ajustes financieros. */
            $saldo_premios = 0;
            $saldo_notaret_pagadas = 0;
            $saldo_notaret_pend = 0;
            $saldo_ajustes_entrada = 0;
            $saldo_ajustes_salida = 0;
            $saldo_bono = 0;

            /* Variables que almacenan diferentes saldos relacionados con apuestas y bonos. */
            $saldo_notaret_creadas = 0;
            $saldo_apuestas_casino = 0;
            $saldo_apuestas_casino = 0;
            $saldo_notaret_eliminadas = 0;
            $saldo_bono_free_ganado = 0;
            $saldo_bono_casino_free_ganado = 0;

            /* Variables inicializan saldos para bonos y apuestas en un casino en línea. */
            $saldo_bono_casino_vivo = 0;
            $saldo_bono_casino_vivo_free_ganado = 0;
            $saldo_bono_virtual = 0;
            $saldo_bono_virtual_free_ganado = 0;
            $saldo_apuestas_casino_vivo = 0;

            if ($data != null) {


                /* convierte datos de saldo en formato float para su procesamiento. */
                $saldo_recarga = floatval($data->{'usuario_saldoresumen.saldo_recarga'});
                $saldo_apuestas = floatval($data->{'usuario_saldoresumen.saldo_apuestas'});
                $saldo_premios = floatval($data->{'usuario_saldoresumen.saldo_premios'});
                $saldo_notaret_pagadas = floatval($data->{'usuario_saldoresumen.saldo_notaret_pagadas'});
                $saldo_notaret_pend = floatval($data->{'usuario_saldoresumen.saldo_notaret_pend'});
                $saldo_ajustes_entrada = floatval($data->{'usuario_saldoresumen.saldo_ajustes_entrada'});

                /* Asigna valores de saldos a variables usando datos de un objeto. */
                $saldo_ajustes_salida = floatval($data->{'usuario_saldoresumen.saldo_ajustes_salida'});
                $saldo_bono = floatval($data->{'usuario_saldoresumen.saldo_bono'});
                $saldo_notaret_creadas = floatval($data->{'usuario_saldoresumen.saldo_notaret_creadas'});
                $saldo_apuestas_casino = floatval($data->{'usuario_saldoresumen.saldo_apuestas_casino'});
                $saldo_apuestas_casino = floatval($data->{'usuario_saldoresumen.saldo_premios_casino'});
                $saldo_notaret_eliminadas = floatval($data->{'usuario_saldoresumen.saldo_notaret_eliminadas'});

                /* Asigna valores de saldo a variables desde un objeto de datos. */
                $saldo_bono_free_ganado = floatval($data->{'usuario_saldoresumen.saldo_bono_free_ganado'});
                $saldo_bono_casino_free_ganado = floatval($data->{'usuario_saldoresumen.saldo_bono_casino_free_ganado'});
                $saldo_bono_casino_vivo = floatval($data->{'usuario_saldoresumen.saldo_bono_casino_vivo'});
                $saldo_bono_casino_vivo_free_ganado = floatval($data->{'usuario_saldoresumen.saldo_bono_casino_vivo_free_ganado'});
                $saldo_bono_virtual = floatval($data->{'usuario_saldoresumen.saldo_bono_virtual'});
                $saldo_bono_virtual_free_ganado = floatval($data->{'usuario_saldoresumen.saldo_bono_virtual_free_ganado'});

                /* Se convierte el saldo de apuestas en vivo a tipo de dato float. */
                $saldo_apuestas_casino_vivo = floatval($data->{'usuario_saldoresumen.saldo_apuestas_casino_vivo'});
            }


            /* Establece un indicador de seguridad basado en el saldo no retirado del 90%. */
            $esSeguro = true;

//Usuario no haya retirado menos del 90% de lo depositado
            if ($saldo_notaret_pagadas > ($saldo_recarga * 0.9)) {
                $esSeguro = false;
            }

//Usuario tiene que jugar un 1.6 el valor depositado

            /* verifica condiciones para determinar si una apuesta es segura. */
            if ($saldo_apuestas < ($saldo_recarga * 1.6)) {
                $esSeguro = false;
            }

//Usuario tiene que perder un 30% del valor depositado
            if (($saldo_apuestas - $saldo_premios) < 300) {
                $esSeguro = false;
            }


            /* establece el estado de $CuentaCobro según condiciones específicas de pago y usuario. */
            if ($esSeguro) {

                if (($CuentaCobro->getMediopagoId() == "" || $CuentaCobro->getMediopagoId() == "0")) {
                    $CuentaCobro->setEstado('A');
                } else {
                    $CuentaCobro->setEstado('P');
                }
                if ($CuentaCobro->getMediopagoId() != "" && ($CuentaCobro->getVersion() == "2")) {
                    $CuentaCobro->setEstado('A');
                }
                if ($CuentaCobro->getMediopagoId() == "2088007" && ($CuentaCobro->getVersion() == "2")) {
                    $CuentaCobro->setEstado('P');
                }
                if ($service == "UserBank") {
                    $CuentaCobro->setEstado('P');
                }

                exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . 'esSeguro ' . $Usuario->usuarioId . "' '#dev' > /dev/null & ");

            }

        }

        if (date('Y-m-d H:i:s') >= '2023-07-17 00:00:00') {

            /* Consulta saldo de usuario y asigna valores iniciales a recargas y apuestas. */
            $sql = "SELECT * FROM usuario_saldoresumen where usuario_id='" . $Usuario->usuarioId . "'";
            $data = $BonoInterno->execQuery('', $sql);
            $data = $data[0];

            $saldo_recarga = 0;
            $saldo_apuestas = 0;

            /* Variables inicializan saldos para premios, notas, ajustes y bonos en un sistema financiero. */
            $saldo_premios = 0;
            $saldo_notaret_pagadas = 0;
            $saldo_notaret_pend = 0;
            $saldo_ajustes_entrada = 0;
            $saldo_ajustes_salida = 0;
            $saldo_bono = 0;

            /* Variables inicializan saldos para apuestas y premios en un sistema de casino. */
            $saldo_notaret_creadas = 0;
            $saldo_apuestas_casino = 0;
            $saldo_premios_casino = 0;
            $saldo_notaret_eliminadas = 0;
            $saldo_bono_free_ganado = 0;
            $saldo_bono_casino_free_ganado = 0;

            /* Variables inicializan saldos de bonos y apuestas en un casino virtual. */
            $saldo_bono_casino_vivo = 0;
            $saldo_bono_casino_vivo_free_ganado = 0;
            $saldo_bono_virtual = 0;
            $saldo_bono_virtual_free_ganado = 0;
            $saldo_apuestas_casino_vivo = 0;

            if ($data != null) {


                /* Se convierten saldos de un objeto JSON a valores flotantes para su uso. */
                $saldo_recarga = floatval($data->{'usuario_saldoresumen.saldo_recarga'});
                $saldo_apuestas = floatval($data->{'usuario_saldoresumen.saldo_apuestas'});
                $saldo_premios = floatval($data->{'usuario_saldoresumen.saldo_premios'});
                $saldo_notaret_pagadas = floatval($data->{'usuario_saldoresumen.saldo_notaret_pagadas'});
                $saldo_notaret_pend = floatval($data->{'usuario_saldoresumen.saldo_notaret_pend'});
                $saldo_ajustes_entrada = floatval($data->{'usuario_saldoresumen.saldo_ajustes_entrada'});

                /* convierte saldos de un objeto JSON a float. */
                $saldo_ajustes_salida = floatval($data->{'usuario_saldoresumen.saldo_ajustes_salida'});
                $saldo_bono = floatval($data->{'usuario_saldoresumen.saldo_bono'});
                $saldo_notaret_creadas = floatval($data->{'usuario_saldoresumen.saldo_notaret_creadas'});
                $saldo_apuestas_casino = floatval($data->{'usuario_saldoresumen.saldo_apuestas_casino'});
                $saldo_premios_casino = floatval($data->{'usuario_saldoresumen.saldo_premios_casino'});
                $saldo_notaret_eliminadas = floatval($data->{'usuario_saldoresumen.saldo_notaret_eliminadas'});

                /* Se convierten saldos de bonos a tipo float desde un objeto de datos. */
                $saldo_bono_free_ganado = floatval($data->{'usuario_saldoresumen.saldo_bono_free_ganado'});
                $saldo_bono_casino_free_ganado = floatval($data->{'usuario_saldoresumen.saldo_bono_casino_free_ganado'});
                $saldo_bono_casino_vivo = floatval($data->{'usuario_saldoresumen.saldo_bono_casino_vivo'});
                $saldo_bono_casino_vivo_free_ganado = floatval($data->{'usuario_saldoresumen.saldo_bono_casino_vivo_free_ganado'});
                $saldo_bono_virtual = floatval($data->{'usuario_saldoresumen.saldo_bono_virtual'});
                $saldo_bono_virtual_free_ganado = floatval($data->{'usuario_saldoresumen.saldo_bono_virtual_free_ganado'});

                /* Asigna el saldo de apuestas en vivo a una variable como número decimal. */
                $saldo_apuestas_casino_vivo = floatval($data->{'usuario_saldoresumen.saldo_apuestas_casino_vivo'});
            }


            /* Verifica si el saldo no retirado supera el 90% del saldo depositado. */
            $esSeguro = true;

//Usuario no haya retirado menos del 90% de lo depositado
            if ($saldo_notaret_pagadas > ($saldo_recarga * 0.9)) {
                $esSeguro = false;
            }

//Usuario tiene que jugar un 1.6 el valor depositado

            /* verifica condiciones de seguridad en apuestas y saldo de usuario. */
            if (($saldo_apuestas + $saldo_apuestas_casino + $saldo_apuestas_casino_vivo) < ($saldo_recarga * 1.6)) {
                $esSeguro = false;
            }

//Usuario tiene que perder un 30% del valor depositado
            if ((($saldo_apuestas + $saldo_apuestas_casino) - ($saldo_premios + $saldo_premios_casino)) < 300) {
                $esSeguro = false;
            }


            /* establece el estado de una cuenta según condiciones específicas de pago y usuario. */
            if ($esSeguro) {

                if (($CuentaCobro->getMediopagoId() == "" || $CuentaCobro->getMediopagoId() == "0")) {
                    $CuentaCobro->setEstado('A');
                } else {
                    $CuentaCobro->setEstado('P');
                }
                if ($CuentaCobro->getMediopagoId() != "" && ($CuentaCobro->getVersion() == "2")) {
                    $CuentaCobro->setEstado('A');
                }
                if ($CuentaCobro->getMediopagoId() == "2088007" && ($CuentaCobro->getVersion() == "2")) {
                    $CuentaCobro->setEstado('P');
                }
                if ($service == "UserBank") {
                    $CuentaCobro->setEstado('P');
                }

                exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . 'esSeguro ' . $Usuario->usuarioId . "' '#dev' > /dev/null & ");

            }

        }

    }

}
if ($service == "UserBank") {

    /* verifica un ID vacío y lanza una excepción si es así. */
    if ($id == '') {
        throw new Exception("Inusual Detected", "100001");
    }
    $method = "0";
    $status_message = "";
    $UsuarioBanco = new UsuarioBanco($id);


    /* Actualiza datos del banco del usuario si el mandante es '2' y el código está vacío. */
    if ($Usuario->mandante == '2') {

        if ($UsuarioBanco->getCodigo() == '') {
            $UsuarioBanco->setCodigo($info1);
            $UsuarioBanco->setTipoCliente($info2);

            $UsuarioBancoMySqlDAO = new UsuarioBancoMySqlDAO();

            $UsuarioBancoMySqlDAO->update($UsuarioBanco);
            $UsuarioBancoMySqlDAO->getTransaction()->commit();

        }


    }


    /* Asigna el identificador del medio de pago a la propiedad mediopagoId de CuentaCobro. */
    $CuentaCobro->mediopagoId = $id;


}


/* Valida un servicio de tarjeta de crédito y lanza excepción si el ID está vacío. */
if ($service == "CreditCard") {
    if ($id == '') {
        throw new Exception("Inusual Detected", "100001");
    }
    $method = "0";
    $status_message = "";
    $UsuarioBanco = new UsuarioBanco($id);

    $CuentaCobro->mediopagoId = $id;
}


/* Asignación de variables y configuración de un objeto según el servicio "UserAgent". */
$method = "";

if ($service == "UserAgent") {
    $method = "0";
    $status_message = "";

    $CuentaCobro->mediopagoId = 0;
//$CuentaCobro->estado = "S";
    $CuentaCobro->productoPagoId = $id;
    $CuentaCobro->version = 2;


}


/* Variables booleanas que almacenan el estado de validación de códigos OTP. */
$otp_code_validation_sales_point = false;
$otp_code_validation_sales_point_sms = false;
$otp_code_validation_sales_point_email = false;
$otp_code_validation_user_agent = false;
$otp_code_validation_user_agent_sms = false;
$otp_code_validation_user_agent_email = false;

/* Define una variable que almacena el tiempo de expiración de un OTP, inicializándola en cero. */
$otp_expire_time = 0;

try {

    /* valida OTP para cuentas bancarias usando diferentes métodos de verificación. */
    $Clasificador = new Clasificador('', 'OTPCODEBANKACCOUNT');
    $MandanteDetalle = new MandanteDetalle('', $Usuario->mandante, $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
    $otp_code_validation_user_agent = $MandanteDetalle->getValor() === 'A';

    if ($otp_code_validation_user_agent) {
        $Clasificador = new Clasificador('', 'OTPCODEBANKACCOUNTSMS');
        $MandanteDetalle = new MandanteDetalle('', $Usuario->mandante, $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
        $otp_code_validation_user_agent_sms = $MandanteDetalle->getValor() === 'A';

        $Clasificador = new Clasificador('', 'OTPCODEBANKACCOUNTEMAIL');
        $MandanteDetalle = new MandanteDetalle('', $Usuario->mandante, $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
        $otp_code_validation_user_agent_email = $MandanteDetalle->getValor() === 'A';
    }


    /* Valida códigos OTP para puntos de venta usando SMS y correo electrónico. */
    $Clasificador = new Clasificador('', 'OTPCODESALESPOINT');
    $MandanteDetalle = new MandanteDetalle('', $Usuario->mandante, $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
    $otp_code_validation_sales_point = $MandanteDetalle->getValor() === 'A';

    if ($otp_code_validation_sales_point) {
        $Clasificador = new Clasificador('', 'OTPCODESALESPOINTSMS');
        $MandanteDetalle = new MandanteDetalle('', $Usuario->mandante, $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
        $otp_code_validation_sales_point_sms = $MandanteDetalle->getValor() === 'A';

        $Clasificador = new Clasificador('', 'OTPCODESALESPOINTEMAIL');
        $MandanteDetalle = new MandanteDetalle('', $Usuario->mandante, $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
        $otp_code_validation_sales_point_email = $MandanteDetalle->getValor() === 'A';
    }
} catch (Exception $ex) {
    /* Captura cualquier excepción y no realiza ninguna acción dentro del bloque catch. */

}


/* intenta obtener un tiempo de expiración de OTP del mandante. */
try {
    $Clasificador = new Clasificador('', 'MAXTIMEOTPCODE');
    $MandanteDetalle = new MandanteDetalle('', $Usuario->mandante, $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
    $otp_expire_time = $MandanteDetalle->getValor();
} catch (Exception $ex) {
}

if (($otp_code_validation_sales_point && $service == 'local' && ($otp_code_validation_sales_point_email || $otp_code_validation_sales_point_sms)) && $otp_expire_time && $otp_expire_time > 0) $CuentaCobro->estado = 'O';

if (($otp_code_validation_user_agent && $service == 'UserBank' && ($otp_code_validation_user_agent_email || $otp_code_validation_user_agent_sms)) && $otp_expire_time && $otp_expire_time > 0) $CuentaCobro->estado = 'O';

if (($otp_code_validation_sales_point && $service == 'UserAgent' && ($otp_code_validation_sales_point_email || $otp_code_validation_sales_point_sms)) && $otp_expire_time && $otp_expire_time > 0) $CuentaCobro->estado = 'O';


/* Se inserta un objeto "CuentaCobro" en la base de datos y obtiene su ID. */
$CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();

$CuentaCobroMySqlDAO->insert($CuentaCobro);
$consecutivo_recarga = $CuentaCobro->cuentaId;

switch ($service) {
    case "local":

        /* Genera un mensaje de estado en HTML basado en el permiso del usuario. */
        $method = "pdf";
        $status_message = '<table style="width:430px;height: 355px;/* border:1px solid black; */">
<tbody><tr><td align="center" valign="top"><div style="height:5px;">&nbsp;</div></td></tr>';
        if (in_array($Usuario->mandante, array(3, 4, 5, 6, 7, 10))) {
            $status_message = $status_message . "<tr>";
            $status_message = $status_message . "<td align='center' valign='top'>";
            $status_message = $status_message . "<font style='padding-left:5px;text-align:center;font-size:14px;'>PERMISO SEGOB 8.S.7.1/DGG/SN/94, OFICIO DE AUTORIZACION No. DGJS/0223/2020 DE FECHA 12 DE MARZO DE 2020</font>";
            $status_message = $status_message . "</td>";
            $status_message = $status_message . "</tr><tr><td align='center' valign='top'><div style='height:2px;'>&nbsp;</div></td></tr>";
        }

        /* Genera un mensaje de estado para un usuario en inglés sobre un retiro. */
        if (strtolower($Usuario->idioma) == "en") {
            $status_message .= '
            <tr><td align="center" valign="top"><font style="text-align:center;font-size:20px;font-weight:bold;">Withdrawal Note</font></td></tr>
            <tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Withdrawal note number.:&nbsp;&nbsp;' . $consecutivo_recarga . '</font></td></tr>
            <tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Client number:&nbsp;&nbsp;' . $ClientId . '</font></td></tr>
            <tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Name:&nbsp;&nbsp;' . $Usuario->nombre . '</font></td></tr>
            <tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Date:&nbsp;&nbsp;' . $CuentaCobro->fechaCrea . '</font></td></tr>
            <tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Password:&nbsp;&nbsp;' . $clave . '</font></td></tr>';
        } else {
            /* Genera una tabla HTML con detalles de una nota de retiro. */

            $status_message .= '
<tr><td align="center" valign="top"><font style="text-align:center;font-size:20px;font-weight:bold;">NOTA DE RETIRO</font></td></tr>
<tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Nota de retiro No.:&nbsp;&nbsp;' . $consecutivo_recarga . '</font></td></tr>
<tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">No. de Cliente:&nbsp;&nbsp;' . $ClientId . '</font></td></tr>
<tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Nombre:&nbsp;&nbsp;' . $Usuario->nombre . '</font></td></tr>
<tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Fecha:&nbsp;&nbsp;' . $CuentaCobro->fechaCrea . '</font></td></tr>
<tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Clave:&nbsp;&nbsp;' . $clave . '</font></td></tr>';
        }
        if ($Usuario->paisId == 173) {


            /* asigna nombres a tipos de documentos según su código abreviado. */
            $tipoDoc = $Registro->tipoDoc;

            switch ($tipoDoc) {
                case "P":
                    $tipoDoc = 'Pasaporte';
                    break;
                case "C":
                    $tipoDoc = 'DNI';
                    break;
                case "E":
                    $tipoDoc = 'Carnet de extranjeria';
                    break;

            }


            /* Se genera un mensaje HTML que muestra tipo de documento y número de cédula. */
            $status_message .= "
  <tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:13px;font-weight:normal;'>Tipo de Doc: :&nbsp;&nbsp;" . $tipoDoc . "</font></td></tr>
    <tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:13px;font-weight:normal;'>Documento:&nbsp;&nbsp;" . $Registro->cedula . "</font></td></tr>
 ";
        }


        /* Verifica si el mandante es uno de los valores especificados para generar un mensaje. */
        if (in_array($Usuario->mandante, array(3, 4, 5, 6, 7, 10))) {
            $status_message = $status_message . "<tr>";
            $status_message = $status_message . "<td align='center' valign='top'>";
            $status_message = $status_message . "<font style='padding-left:5px;text-align:center;font-size:14px;'>RECIBO DE NETABET SA DE CV LA CANTIDAD INDICADA, POR CONCEPTO DE PAGO DE APUESTA GANADA Y/O RETIRO.</font>";
            $status_message = $status_message . "</td>";
            $status_message = $status_message . "</tr><tr><td align='center' valign='top'><div style='height:2px;'>&nbsp;</div></td></tr>";
        }


        /* Genera un mensaje en inglés con detalles de un retiro y sus costos asociados. */
        if (strtolower($Usuario->idioma) == "en") {
            $status_message .= '<tr><td align="center" valign="top"><div style="height:1px;">&nbsp;</div></td></tr>
            <tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Value to withdraw:&nbsp;&nbsp;' . $amount . '</font></td></tr>
            <tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Tax:&nbsp;&nbsp;' . $valorImpuesto . '</font></td></tr>
            <tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Cost:&nbsp;&nbsp;' . $valorPenalidad . '</font></td></tr>
            <tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Value to deliver:&nbsp;&nbsp;' . $valorFinal . '</font></td></tr>
            <tr><td align="center" valign="top"><div style="height:5px;">&nbsp;</div></td></tr>
            </tbody></table>';
        } else {
            /* Genera una tabla HTML con datos financieros sobre valores a retirar y costos. */

            $status_message .= '<tr><td align="center" valign="top"><div style="height:1px;">&nbsp;</div></td></tr>
<tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Valor a retirar:&nbsp;&nbsp;' . number_format($amount, '2', ',', '.') . '</font></td></tr>
<tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Impuesto:&nbsp;&nbsp;' . $valorImpuesto . '</font></td></tr>
<tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Costo:&nbsp;&nbsp;' . $valorPenalidad . '</font></td></tr>
<tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Valor a entregar:&nbsp;&nbsp;' . number_format($valorFinal, '2', ',', '.') . '</font></td></tr>
<tr><td align="center" valign="top"><div style="height:5px;">&nbsp;</div></td></tr>
</tbody></table>';
        }

        if ((($Usuario->mandante == 0 || $Usuario->mandante == 8 || $Usuario->mandante == 6) && false) || $Usuario->mandante == '0') {
            try {

                /* Se crea un clasificador y un template, luego se genera un código HTML. */
                $Clasificador = new Clasificador("", "TEMRECNORE");

                $Template = new Template('', $Usuario->mandante, $Clasificador->clasificadorId, $Usuario->paisId, strtolower($Usuario->idioma));
                $html_barcode = $Template->templateHtml;

                if ($html_barcode != '') {

                    /* Genera un código HTML con estilos para un documento según su tipo. */
                    $html_barcode .= $Template->templateHtmlCSSPrint;
                    $html_barcode .= '<style>.bodytmp {width: 300px !important;}</style>';

                    $tipoDoc = $Registro->tipoDoc;

                    switch ($tipoDoc) {
                        case "P":
                            $tipoDoc = 'Pasaporte';
                            break;
                        case "C":
                            $tipoDoc = 'DNI';
                            break;
                        case "E":
                            $tipoDoc = 'Carnet de extranjeria';
                            break;

                    }


                    /* Se reemplazan marcadores en un código HTML por valores de una cuenta de cobro. */
                    $html_barcode = str_replace("#idnotewithdrawal#", $CuentaCobro->cuentaId, $html_barcode);
                    $html_barcode = str_replace("#withdrawalnotenumber#", $CuentaCobro->cuentaId, $html_barcode);
                    $html_barcode = str_replace("#value#", $Usuario->moneda . ' ' . $CuentaCobro->valor, $html_barcode);
                    $html_barcode = str_replace("#totalvalue#", $Usuario->moneda . ' ' . (floatval($CuentaCobro->valor) - floatval($CuentaCobro->impuesto)), $html_barcode);
                    $html_barcode = str_replace("#tax#", $Usuario->moneda . ' ' . $CuentaCobro->impuesto, $html_barcode);
                    $html_barcode = str_replace("#keynotewithdrawal#", $clave, $html_barcode);

                    /* Sustituye etiquetas en HTML con datos específicos de la cuenta y usuario. */
                    $html_barcode = str_replace("#creationdate#", $CuentaCobro->fechaCrea, $html_barcode);
                    $html_barcode = str_replace("#userid#", $CuentaCobro->usuarioId, $html_barcode);
                    $html_barcode = str_replace("#name#", $Usuario->nombre, $html_barcode);

                    $html_barcode = str_replace("#typedoc#", $tipoDoc, $html_barcode);
                    $html_barcode = str_replace("#identification#", $Registro->cedula, $html_barcode);


                    if ($Usuario->mandante == 8) {

                        /* Modifica un código HTML según el medio de pago seleccionado para la cuenta de cobro. */
                        if ($CuentaCobro->mediopagoId == '693978') {
                            $html_barcode = str_replace("#typewithdraw#", 'Facilito', $html_barcode);
                            $html_barcode = str_replace("#descriptionFixed#", 'Esta nota de retiro solo podrá ser cobrada en agencias de Ecuabet.com o en las agencias de la red Facilito.', $html_barcode);
                        } elseif ($CuentaCobro->mediopagoId == '853460') {
                            $html_barcode = str_replace("#typewithdraw#", 'Western Union', $html_barcode);
                            $html_barcode = str_replace("#descriptionFixed#", 'Este retiro solo podrá ser cobrado 30 minutos después de generado y solo será pagado en locales autorizado de Red Activa Western Unión. (No podrá ser cobrado en franquicias). ', $html_barcode);
                        } elseif ($CuentaCobro->mediopagoId == '1211624') {
                            /* Modifica contenido HTML para mostrar información específica de un retiro por Bemovil. */

                            $html_barcode = str_replace("#typewithdraw#", 'Bemovil', $html_barcode);
                            $html_barcode = str_replace("#descriptionFixed#", 'Este retiro solo podrá ser cobrado 30 minutos después de generado y solo será pagado en locales autorizado de Bemovil. ', $html_barcode);
                        } elseif ($CuentaCobro->mediopagoId == '1784692') {
                            /* Actualiza el HTML con condiciones específicas para el medio de pago Bakan. */

                            $html_barcode = str_replace("#typewithdraw#", 'Bakan', $html_barcode);
                            $html_barcode = str_replace("#descriptionFixed#", 'Este retiro solo podrá ser cobrado 30 minutos después de generado y solo será pagado en locales autorizado de Bakan. ', $html_barcode);
                        } elseif ($CuentaCobro->mediopagoId == '2894342') {
                            /* Modifica el HTML del código de barras según el método de pago "FullCarga". */

                            $html_barcode = str_replace("#typewithdraw#", 'FullCarga', $html_barcode);
                            $html_barcode = str_replace("#descriptionFixed#", 'Este retiro solo podrá ser cobrado 30 minutos después de generado y solo será pagado en locales autorizado de FullCarga. ', $html_barcode);
                        } else {
                            /* reemplaza marcadores en $html_barcode con información específica de Ecuabet. */

                            $html_barcode = str_replace("#typewithdraw#", 'Agencia Ecuabet', $html_barcode);
                            $html_barcode = str_replace("#descriptionFixed#", 'Esta nota de retiro solo podrá ser cobrada en agencias de Ecuabet.com o en las agencias de la red Facilito.', $html_barcode);
                        }


                    } else {
                        /* Reemplaza marcadores de texto en una cadena HTML con valores específicos. */

                        $html_barcode = str_replace("#typewithdraw#", 'Punto de Venta', $html_barcode);
                        $html_barcode = str_replace("#descriptionFixed#", '', $html_barcode);
                    }

                    if ((($Usuario->mandante == 0 || $Usuario->mandante == 8) || $Usuario->mandante == 6)) {


                        /* Instancia de Mandante y configuración de Dompdf para generar un documento HTML. */
                        $Mandante = new Mandante($Usuario->mandante);

// instantiate and use the dompdf class
                        $dompdf = new Dompdf();
                        $dompdf->loadHtml($html_barcode);

// (Optional) Setup the paper size and orientation
                        $width = 90; //mm!

                        /* Convierte dimensiones de milímetros a puntos para configurar un formato de papel en dompdf. */
                        $height = 150; //mm!


//convert mm to points
                        $paper_format = array(0, 0, ($width / 25.4) * 72, ($height / 25.4) * 72);
                        $dompdf->setPaper($paper_format);

// Render the HTML as PDF

                        /* genera un PDF y lo muestra en el navegador utilizando Dompdf. */
                        $dompdf->render();

// Output the generated PDF to Browser


// Instantiate canvas instance
                        $canvas = $dompdf->getCanvas();

// Get height and width of page

                        /* Código que obtiene dimensiones de un lienzo y especifica la imagen de la marca de agua. */
                        $w = $canvas->get_width();
                        $h = $canvas->get_height();

// Specify watermark image
                        $imageURL = $Mandante->logoPdf;
                        $imgWidth = 200;

                        /* ajusta la opacidad y altura de imagen según la condición del usuario. */
                        $imgHeight = 100;

// Set image opacity
                        $canvas->set_opacity(.3);

                        if ($Usuario->mandante == 8) {
                            $canvas->set_opacity(.2);
                            $imgHeight = 70;
                        }
// Specify horizontal and vertical position

                        /* Calcula posición centrada para agregar una imagen en un PDF usando Dompdf. */
                        $x = (($w - $imgWidth) / 2);
                        $y = (($h - $imgHeight) / 2) - 30;

// Add an image to the pdf
//$canvas->image($imageURL, $x, $y, $imgWidth, $imgHeight);


                        $data = $dompdf->output();


                        /* codifica datos PDF y almacena mensajes en formato Base64. */
                        $base64 = 'data:application/pdf;base64,' . base64_encode($data);

                        $status_messagePDF = base64_encode($data);
                        $status_messageHTML = $html_barcode;

                    }
                }
            } catch (Exception $e) {
                /* Captura excepciones en PHP para manejar errores sin interrumpir la ejecución del script. */


            }
        }


        /* Asigna $id a mediopagoId si $id no está vacío. */
        if ($id != '') {
            $CuentaCobro->mediopagoId = $id;
        }

        break;

    case "UserBank":
        /* Asignación de valores a variables en un caso específico dentro de un switch. */

        $method = "0";
        $status_message = "";

        $CuentaCobro->mediopagoId = $id;


        break;
}


if ($creditosBase > 0 && false) {
    $rowsUpdate = $Usuario->credit(-$creditosBase, $CuentaCobroMySqlDAO->getTransaction(), true);
    /*$Registro->setCreditosBase("creditos_base - " . $creditosBase);

    $RegistroMySqlDAO = new RegistroMySqlDAO($CuentaCobroMySqlDAO->getTransaction());

    $RegistroMySqlDAO->update($Registro);*/
    if ($rowsUpdate == 0 || $rowsUpdate == false) {
        throw new Exception("Fondos insuficientes", "20001");

    }

}

$rowsUpdate = $Usuario->creditWin2(-$creditos, $CuentaCobroMySqlDAO->getTransaction(), true);
/*$Registro->setCreditos("creditos - " . $creditos);

$RegistroMySqlDAO = new RegistroMySqlDAO($CuentaCobroMySqlDAO->getTransaction());

$RegistroMySqlDAO->update($Registro);*/
if ($rowsUpdate == 0 || $rowsUpdate == false) {
    throw new Exception("Fondos insuficientes", "20001");

}

$UsuarioHistorial = new UsuarioHistorial();
$UsuarioHistorial->setUsuarioId($CuentaCobro->usuarioId);
$UsuarioHistorial->setDescripcion('');
$UsuarioHistorial->setMovimiento('S');
$UsuarioHistorial->setUsucreaId(0);
$UsuarioHistorial->setUsumodifId(0);
$UsuarioHistorial->setTipo(40);
$UsuarioHistorial->setValor($CuentaCobro->valor);
$UsuarioHistorial->setExternoId($consecutivo_recarga);
$UsuarioHistorial->setCustoms($localizacion);


$UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($CuentaCobroMySqlDAO->getTransaction());
$UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);


$CuentaCobroMySqlDAO->getTransaction()->commit();

try {

    $useragent = $_SERVER['HTTP_USER_AGENT'];
    $jsonServer = json_encode($_SERVER);
    $serverCodif = base64_encode($jsonServer);


    $ismobile = '';

    if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {

        $ismobile = '1';

    }
//Detect special conditions devices
    $iPod = stripos($_SERVER['HTTP_USER_AGENT'], "iPod");
    $iPhone = stripos($_SERVER['HTTP_USER_AGENT'], "iPhone");
    $iPad = stripos($_SERVER['HTTP_USER_AGENT'], "iPad");
    $Android = stripos($_SERVER['HTTP_USER_AGENT'], "Android");
    $webOS = stripos($_SERVER['HTTP_USER_AGENT'], "webOS");


//do something with this information
    if ($iPod || $iPhone) {
        $ismobile = '1';
    } else if ($iPad) {
        $ismobile = '1';
    } else if ($Android) {
        $ismobile = '1';
    }

    //exec("php -f " . __DIR__ . "/../../src/integrations/crm/AgregarCrm.php " . $Usuario->usuarioId . " " . "RETIROCREADOCRM" . " " . $CuentaCobro->cuentaId . " " . $serverCodif . " " . $ismobile . " > /dev/null &");

} catch (Exception $e) {

}

if ($service == "local" || $service == "UserAgent") {


    /* Envía un mensaje de texto sobre una Nota de Retiro a un usuario específico. */
    try {
        if ($Usuario->test == 'S' && $Usuario->mandante == '0' && $Usuario->paisId == '94' && false) {
            $Mandante = new \Backend\dto\Mandante($Usuario->mandante);
            $UsuarioMandante = new \Backend\dto\UsuarioMandante('', $Usuario->usuarioId, $Usuario->mandante);
            $mensaje_txt = $Mandante->nombre . ', se generó su Nota de Retiro con ID (' . $CuentaCobro->cuentaId . ') por un valor de (' . $Usuario->moneda . ' ' . $CuentaCobro->valor . '). Clave: (' . $clave . '). Por favor no comparta este mensaje con nadie. Aplican TyCs.';

            $ConfigurationEnvironment = new \Backend\dto\ConfigurationEnvironment();
            //Envia el mensaje de correo
            $envio = $ConfigurationEnvironment->EnviarMensajeTexto($mensaje_txt, '', $Registro->getCelular(), 0, $UsuarioMandante);
        }

    } catch (Exception $e) {
        /* Manejo de excepciones en PHP; captura errores sin procesar en el bloque vacío. */


    }
    try {


        try {

            /* configura un clasificador y un template basado en el idioma del usuario. */
            $Clasificador = new Clasificador('', 'TEMPEMAILNOTRET');
            $Template = new Template('', $Usuario->mandante, $Clasificador->getClasificadorId(), $Usuario->paisId, strtolower($Usuario->idioma));

            $title = '';

            switch ($Usuario->idioma) {
                case 'EN':
                    $title = 'Transaction confirmation';
                    break;
                case 'PT':
                    $title = 'Confirmação da transação';
                    break;
                default:
                    $title = 'Confirmación de transacción';
                    break;
            }


            /* reemplaza marcadores en un template HTML con valores específicos de objetos. */
            $html = $Template->templateHtml;

            $html = str_replace("#idnotewithdrawal#", $CuentaCobro->cuentaId, $html);
            $html = str_replace("#withdrawalnotenumber#", $CuentaCobro->cuentaId, $html);
            $html = str_replace("#value#", $Usuario->moneda . ' ' . $CuentaCobro->valor, $html);
            $html = str_replace("#totalvalue#", $Usuario->moneda . ' ' . (floatval($CuentaCobro->valor) - floatval($CuentaCobro->impuesto)), $html);

            /* Reemplaza variables en una cadena HTML con datos del usuario y cuenta de cobro. */
            $html = str_replace("#tax#", $Usuario->moneda . ' ' . $CuentaCobro->impuesto, $html);
            $html = str_replace("#keynotewithdrawal#", $clave, $html);
            $html = str_replace("#creationdate#", $CuentaCobro->fechaCrea, $html);
            $html = str_replace("#userid#", $CuentaCobro->usuarioId, $html);
            $html = str_replace("#name#", $Usuario->nombre, $html);

            $html = str_replace("#typedoc#", $tipoDoc, $html);

            /* Reemplaza un identificador en HTML y envía un correo si las validaciones fallan. */
            $html = str_replace("#identification#", $Registro->cedula, $html);


            if (!($otp_code_validation_sales_point && $otp_code_validation_sales_point_email && $otp_expire_time > 0)) {

                $ConfigurationEnvironment->EnviarCorreoVersion3($Usuario->login, '', '', $title, '', $title, $html, '', '', '', $Usuario->mandante);
            }
        } catch (Exception $ex) {
            /* Es un bloque de código en PHP que captura excepciones sin realizar acción alguna. */

        }


    } catch (Exception $e) {
        /* Captura excepciones en PHP para manejar errores sin detener la ejecución del programa. */


    }


}


/*  Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket  */
/*$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());*/

/*  Enviamos el mensaje Websocket al Usuario para que actualice el saldo  */
//$data = $Usuario->getWSMessage($UsuarioToken->getRequestId());
/*$data = $UsuarioMandante->getWSProfileSite($UsuarioToken->getRequestId());
$WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
$WebsocketUsuario->sendWSMessage();*/


/**
 * Verifica si el usuario está utilizando un dispositivo móvil.
 *
 * Esta función analiza el agente de usuario (\$_SERVER['HTTP_USER_AGENT'])
 * para determinar si pertenece a un dispositivo móvil conocido.
 *
 * @return bool Devuelve `true` si el agente de usuario corresponde a un dispositivo móvil, de lo contrario `false`.
 */
function esMovil()
{
    $userAgent = $_SERVER['HTTP_USER_AGENT'];

    $dispositivosMoviles = array(
        'iPhone', 'iPad', 'Android', 'BlackBerry', 'Windows Phone',
        'Opera Mini', 'Mobile Safari', 'webOS'
    );

    foreach ($dispositivosMoviles as $dispositivo) {
        if (stripos($userAgent, $dispositivo) !== false) {
            return true;
        }
    }

    return false;
}

if (esMovil()) {
    $dispositivo = 'Mobile';
} else {
    $dispositivo = "Desktop";
}


$userAgent = $_SERVER['HTTP_USER_AGENT'];

/**
 * Determina el sistema operativo basado en el agente de usuario proporcionado.
 *
 * @param string $userAgent El agente de usuario (\$_SERVER['HTTP_USER_AGENT']).
 * @return string El nombre del sistema operativo detectado (Windows, Linux, Mac) o "Desconocido" si no se puede determinar.
 */
function getOS($userAgent)
{
    $os = "Desconocido";

    if (stripos($userAgent, 'Windows') !== false) {
        $os = 'Windows';
    } elseif (stripos($userAgent, 'Linux') !== false) {
        $os = 'Linux';
    } elseif (stripos($userAgent, 'Macintosh') !== false || stripos($userAgent, 'Mac OS X') !== false) {
        $os = 'Mac';
    }

    return $os;
}

$so = getOS($userAgent);

$ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
$ip = explode(",", $ip)[0];


if ($codigoError and $mensajeError != "") {

    /* Configura los datos del usuario en una auditoría general. */
    $AuditoriaGeneral = new AuditoriaGeneral();
    $AuditoriaGeneral->setUsuarioId($Usuario->usuarioId);
    $AuditoriaGeneral->setUsuarioIp($ip);
    $AuditoriaGeneral->setUsuariosolicitaId($Usuario->usuarioId);
    $AuditoriaGeneral->setUsuariosolicitaIp($ip);
    $AuditoriaGeneral->setUsuarioaprobarId($Usuario->usuarioId);

    /* Se registra una auditoría con cambios en valores y usuario creador. */
    $AuditoriaGeneral->setUsuarioaprobarIp(0);
    $AuditoriaGeneral->setTipo("ERRORAPIRIESGO");
    $AuditoriaGeneral->setValorAntes(0);
    $AuditoriaGeneral->setValorDespues($consecutivo_recarga);
    $AuditoriaGeneral->setUsucreaId($Usuario->usuarioId);
    $AuditoriaGeneral->setUsumodifId(0);

    /* Se establecen propiedades del objeto AuditoriaGeneral, configurando estado, dispositivo y observaciones. */
    $AuditoriaGeneral->setEstado("A");
    $AuditoriaGeneral->setDispositivo($dispositivo);
    $AuditoriaGeneral->setSoperativo($so);
    $AuditoriaGeneral->setSversion(0);
    $AuditoriaGeneral->setImagen("");
    $AuditoriaGeneral->setObservacion("mensaje error.' '.$mensajeError");

    /* configura datos y campos para insertar en una base de datos. */
    $AuditoriaGeneral->setData("");
    $AuditoriaGeneral->setCampo("");

    $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO();
    $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);
    $AuditoriaGeneralMySqlDAO->getTransaction()->commit();
} else {

    /* Se crea una auditoría general, configurando información del usuario y su IP. */
    $AuditoriaGeneral = new AuditoriaGeneral();
    $AuditoriaGeneral->setUsuarioId($Usuario->usuarioId);
    $AuditoriaGeneral->setUsuarioIp($ip);
    $AuditoriaGeneral->setUsuariosolicitaId($Usuario->usuarioId);
    $AuditoriaGeneral->setUsuariosolicitaIp($ip);
    $AuditoriaGeneral->setUsuarioaprobarId($Usuario->usuarioId);

    /* Código que configura valores para una auditoría de registro de API de riesgo. */
    $AuditoriaGeneral->setUsuarioaprobarIp(0);
    $AuditoriaGeneral->setTipo("REGISTROAPIRIESGO");
    $AuditoriaGeneral->setValorAntes(0);
    $AuditoriaGeneral->setValorDespues($consecutivo_recarga);
    $AuditoriaGeneral->setUsucreaId($Usuario->usuarioId);
    $AuditoriaGeneral->setUsumodifId(0);

    /* Configura los parámetros de auditoría general incluyendo estado, dispositivo, sistema operativo y observaciones. */
    $AuditoriaGeneral->setEstado("A");
    $AuditoriaGeneral->setDispositivo($dispositivo);
    $AuditoriaGeneral->setSoperativo($so);
    $AuditoriaGeneral->setSversion(0);
    $AuditoriaGeneral->setImagen("");
    $AuditoriaGeneral->setObservacion("nivel de riesgo: $riskValue");

    /* Código que configura datos de auditoría y realiza una inserción en la base de datos. */
    $AuditoriaGeneral->setData("");
    $AuditoriaGeneral->setCampo("");

    $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO();
    $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);
    $AuditoriaGeneralMySqlDAO->getTransaction()->commit();
}

if (false) {
    // Registro de auditoría cuando la solicitud de retiro es de tipo Criptomoneda
    $criptored = null;
    try {
        $UsuarioBanco = new UsuarioBanco($id);
        $criptored = new CriptoRed('', '', '', '', $UsuarioBanco->getBancoId());
    } catch (Exception $e) {
    }

    if ($criptored != null) {

        $informacion = [
            "usuario" => $Usuario->usuarioId,
            "cripto_id" => $criptored->getCriptomonedaId(),
            "redblockchain_id" => $criptored->getRedBlockchain(),
            "wallet" => $UsuarioBanco->getCuenta(),
            "monto" => $amount,
            "estado_inicial" => 'A',
            "fecha_hora" => date('Y-m-d H:i:s')
        ];

        $AuditoriaGeneral = new AuditoriaGeneral();
        $AuditoriaGeneral->setUsuarioId($Usuario->usuarioId);
        $AuditoriaGeneral->setUsuarioIp($ip);
        $AuditoriaGeneral->setUsuariosolicitaId($Usuario->usuarioId);
        $AuditoriaGeneral->setUsuariosolicitaIp($ip);
        $AuditoriaGeneral->setUsuarioaprobarId($Usuario->usuarioId);

        /* Código que configura valores para una auditoría de registro de API de riesgo. */
        $AuditoriaGeneral->setUsuarioaprobarIp(0);
        $AuditoriaGeneral->setTipo("CREACION NOTA RETIRO CRIPTO");
        $AuditoriaGeneral->setValorAntes(0);
        $AuditoriaGeneral->setUsucreaId($Usuario->usuarioId);
        $AuditoriaGeneral->setUsumodifId(0);

        /* Configura los parámetros de auditoría general incluyendo estado, dispositivo, sistema operativo y observaciones. */
        $AuditoriaGeneral->setEstado("A");
        $AuditoriaGeneral->setDispositivo($dispositivo);
        $AuditoriaGeneral->setSoperativo($so);
        $AuditoriaGeneral->setSversion(0);
        $AuditoriaGeneral->setImagen("");
        $AuditoriaGeneral->setObservacion(json_encode($informacion));

        /* Código que configura datos de auditoría y realiza una inserción en la base de datos. */
        $AuditoriaGeneral->setData("");
        $AuditoriaGeneral->setCampo("");

        $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO();
        $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);
        $AuditoriaGeneralMySqlDAO->getTransaction()->commit();
    }

}

/*
Tener presente:
$service == 'UserAgent' && $id =='5758546'  = RETIROS TIENDAS TAMBO
 */
if (($otp_code_validation_user_agent && $service == 'UserBank') || ($otp_code_validation_sales_point && $service == 'local') && $otp_expire_time > 0 || $otp_code_validation_sales_point && ($service == 'UserAgent') && $otp_expire_time > 0) {

    /* Genera un código OTP y personaliza una plantilla HTML con datos del usuario. */
    $otp_code = intval($consecutivo_recarga) + strtotime($CuentaCobro->fechaCrea);
    $otp_code = substr(strrev(strval($otp_code)), 0, 6);

    try {
        $Clasificador = new Clasificador('', 'TEMPOTPCODE');
        $Template = new Template('', $Usuario->mandante, $Clasificador->getClasificadorId(), $Usuario->paisId, $Usuario->idioma);

        $name = $UsuarioMandante->nombres . ' ' . $UsuarioMandante->apellidos;
        $html = $Template->templateHtml;
        $html = str_replace('#name#', $name, $html);
        $html = str_replace('#code#', $otp_code, $html);
        $html = str_replace('#idWithdraw#', $consecutivo_recarga, $html);

    } catch (Exception $e) {
        /* Captura excepciones en PHP sin realizar ninguna acción ante errores ocurridos. */
    }


    /* Asignación del código OTP y título según el idioma del usuario. */
    $html = $html ?: $otp_code;
    $title = match ($Usuario->idioma) {
        'EN' => 'Withdrawal confirmation code',
        'PT' => 'Código de confirmação de saque',
        default => 'Codigo confirmacion de retiro',
    };


    /* Envía un correo basado en validaciones de códigos OTP y servicio específico. */
    if ($otp_code_validation_user_agent && $service == 'UserBank' && $otp_code_validation_user_agent_email) {
        $ConfigurationEnvironment->EnviarCorreoVersion3($Usuario->login, '', '', $title, '', $title, $html, '', '', '', $Usuario->mandante);
    }

    if ($otp_code_validation_sales_point && $service == 'local' && $otp_code_validation_sales_point_email) {
        $ConfigurationEnvironment->EnviarCorreoVersion3($Usuario->login, '', '', $title, '', $title, $html, '', '', '', $Usuario->mandante);
    }

    /* OTP TIENDAS TAMBO */
    if ($otp_code_validation_sales_point && ($service == 'UserAgent') && $otp_code_validation_sales_point_email) {
        $ConfigurationEnvironment->EnviarCorreoVersion3($Usuario->login, '', '', $title, '', $title, $html, '', '', '', $Usuario->mandante);
    }


    /* reemplaza marcadores en una plantilla HTML con información del usuario. */
    try {
        $html = null;
        $Clasificador = new Clasificador('', 'TEMPSMSNOTRET');
        $Template = new Template('', $Usuario->mandante, $Clasificador->getClasificadorId(), $Usuario->paisId, $Usuario->idioma, false);

        $name = $UsuarioMandante->nombres . ' ' . $UsuarioMandante->apellidos;
        $html = $Template->templateHtml;
        $html = str_replace('#nombre#', $name, $html);
        $html = str_replace('#cuentaCobroId#', $CuentaCobro->cuentaId, $html);
        $html = str_replace('#usuarioMoneda#', $Usuario->moneda, $html);
        $html = str_replace('#cuentaCobroValor#', $CuentaCobro->valor, $html);
        $html = str_replace('#usuarioMoneda#', $consecutivo_recarga, $html);
        $html = str_replace('#codigo#', $otp_code, $html);

    } catch (Exception $e) {
        /* Captura y maneja excepciones en PHP sin realizar ninguna acción específica. */
    }


    /* envía un mensaje de texto si se cumplen ciertas condiciones de validación. */
    $html = $html ?: $otp_code;
    $ConfigurationEnvironment = new \Backend\dto\ConfigurationEnvironment();
    $UsuarioMandante = new \Backend\dto\UsuarioMandante('', $Usuario->usuarioId, $Usuario->mandante);

    if ($otp_code_validation_user_agent && $service == 'UserBank' && $otp_code_validation_user_agent_sms) {
        $envio = $ConfigurationEnvironment->EnviarMensajeTexto($html, '', $Registro->getCelular(), $Usuario->mandante, $UsuarioMandante);
    }


    /* valida OTP y envía mensajes según el servicio y condiciones específicas. */
    if ($otp_code_validation_sales_point && $service == 'local' && $otp_code_validation_sales_point_sms) {
        $envio = $ConfigurationEnvironment->EnviarMensajeTexto($html, '', $Registro->getCelular(), $Usuario->mandante, $UsuarioMandante);
    }
    /* LOGICA TIENDAS TAMBO */
    if ($otp_code_validation_sales_point && ($service == 'UserAgent') && $otp_code_validation_sales_point_sms) {
        $envio = $ConfigurationEnvironment->EnviarMensajeTexto($html, '', $Registro->getCelular(), $Usuario->mandante, $UsuarioMandante);
    }

    $mensaje_txt = '';

    if ($otp_code_validation_user_agent && $service == 'UserBank') {
        if ($otp_code_validation_user_agent_email && $otp_code_validation_user_agent_sms) {
            $mensaje_txt = 'Para confirmar el retiro debe ingresar el código que se ha enviado a su correo y SMS.';
        } elseif ($otp_code_validation_user_agent_email) {
            $mensaje_txt = 'Para confirmar el retiro debe ingresar el código que se ha enviado a su correo.';
        } elseif ($otp_code_validation_user_agent_sms) {
            $mensaje_txt = 'Para confirmar el retiro debe ingresar el código que se ha enviado a su SMS.';
        }
    } elseif ($otp_code_validation_sales_point && $service == 'local') {
        /* determina mensajes según la validación del OTP y el servicio. */

        if ($otp_code_validation_sales_point_email && $otp_code_validation_sales_point_sms) {
            $mensaje_txt = 'Para confirmar el retiro debe ingresar el código que se ha enviado a su correo y SMS.';
        } elseif ($otp_code_validation_sales_point_email) {
            $mensaje_txt = 'Para confirmar el retiro debe ingresar el código que se ha enviado a su correo.';
        } elseif ($otp_code_validation_sales_point_sms) {
            $mensaje_txt = 'Para confirmar el retiro debe ingresar el código que se ha enviado a su SMS.';
        }
    } elseif ($otp_code_validation_sales_point && ($service == 'UserAgent')) { /* LOGICA TIENDAS TAMBO  */
        /* determina mensajes según la validación del OTP y el servicio. */

        if ($otp_code_validation_sales_point_email && $otp_code_validation_sales_point_sms) {
            $mensaje_txt = 'Para confirmar el retiro debe ingresar el código que se ha enviado a su correo y SMS.';
        } elseif ($otp_code_validation_sales_point_email) {
            $mensaje_txt = 'Para confirmar el retiro debe ingresar el código que se ha enviado a su correo.';
        } elseif ($otp_code_validation_sales_point_sms) {
            $mensaje_txt = 'Para confirmar el retiro debe ingresar el código que se ha enviado a su SMS.';
        }
    }


    /* Genera una respuesta estructurada con código, datos y detalles del servicio. */
    $response = [
        'code' => 0,
        'data' => [
            'msg' => $mensaje_txt,
            'codeTime' => intval($otp_expire_time),
            'WithdrawId' => $consecutivo_recarga,
            'confirmOTP' => true,
            'service' => $service
        ],
        'rid' => $json->rid,
    ];
} else {

    /* Genera una respuesta en formato JSON con detalles de una transacción. */
    $response = array();
    $response["code"] = 0;
    $response["rid"] = $json->rid;
    $response["data"] = array(
        "result" => 0,
        "details" => array(
            "method" => $method,
            "status_message" => $status_message,
            "status_messagePdf" => $status_messagePDF,
            "status_messageHTML" => $status_messageHTML,
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


    /* Genera una nota de retiro en formato HTML con detalles del cliente y autorización. */
    $html_barcode = '<table style="width:430px;height: 355px;/* border:1px solid black; */"><tbody><tr><td align="center" valign="top"><font style="text-align:center;font-size:20px;font-weight:bold;">NOTA DE RETIRO</font></td></tr><tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Nota de retiro No.:&nbsp;&nbsp;' . $consecutivo_recarga . '</font></td></tr><tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">No. de Cliente:&nbsp;&nbsp;' . $ClientId . '</font></td></tr><tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Nombre:&nbsp;&nbsp;' . $Usuario->nombre . '</font></td></tr><tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Fecha:&nbsp;&nbsp;' . $CuentaCobro->fechaCrea . '</font></td></tr><tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Clave:&nbsp;&nbsp;' . $clave . '</font></td></tr><tr><td align="center" valign="top"><div style="height:1px;">&nbsp;</div></td></tr><tr><td align="center" valign="top"><font style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;">Valor a retirar:&nbsp;&nbsp;' . $amount . '</font></td></tr></tbody></table>';

    $html_barcode = "
    <table style='width:180px;height:280px;border:1px solid black;'>
    <tr><td align='center' valign='top'><div style='height:5px;'>&nbsp;</div></td></tr>";

    if (in_array($Usuario->mandante, array(3, 4, 5, 6, 7, 10))) {
        $html_barcode = $html_barcode . "<tr>";
        $html_barcode = $html_barcode . "<td align='center' valign='top'>";
        $html_barcode = $html_barcode . "<font style='padding-left:5px;text-align:center;font-size:14px;'>PERMISO SEGOB 8.S.7.1/DGG/SN/94, OFICIO DE AUTORIZACION No. DGJS/0223/2020 DE FECHA 12 DE MARZO DE 2020</font>";
        $html_barcode = $html_barcode . "</td>";
        $html_barcode = $html_barcode . "</tr>";
    }


    /* Genera una tabla HTML con información de una nota de retiro y cliente. */
    $html_barcode = $html_barcode . "
    <tr><td align='center' valign='top'>
        <font style='text-align:center;font-size:20px;font-weight:bold;'>Nota de Retiro</font></td>
    </tr>
    <tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:13px;font-weight:normal;'>Nota No: :&nbsp;&nbsp;" . $consecutivo_recarga . "</font></td></tr>
    <tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:13px;font-weight:normal;'>No. de Cliente:&nbsp;&nbsp;" . $ClientId . "</font></td></tr>";

    if ($Usuario->paisId == 173) {


        /* asigna un nombre legible a un tipo de documento basado en un código. */
        $tipoDoc = $Registro->tipoDoc;

        switch ($tipoDoc) {
            case "P":
                $tipoDoc = 'Pasaporte';
                break;
            case "C":
                $tipoDoc = 'DNI';
                break;
            case "E":
                $tipoDoc = 'Carnet de extranjeria';
                break;

        }


        /* Genera HTML para mostrar tipo de documento y número de documento en una tabla. */
        $html_barcode = $html_barcode . "
        <tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:13px;font-weight:normal;'>Tipo de Doc: :&nbsp;&nbsp;" . $tipoDoc . "</font></td></tr>
            <tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:13px;font-weight:normal;'>Documento:&nbsp;&nbsp;" . $Registro->cedula . "</font></td></tr>
        ";
    }

    /* Genera una tabla HTML con información sobre fecha, monto, impuestos y costos. */
    $html_barcode = $html_barcode . "  <tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:13px;font-weight:normal;'>Fecha:&nbsp;&nbsp;" . date('Y-m-d H:i:s') . "</font></td></tr>
    <tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:13px;font-weight:normal;'>Valor a Retirar:&nbsp;&nbsp;" . number_format($amount, '2', ',', '.') . "</font></td></tr>
    <tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:13px;font-weight:normal;'>Impuesto:&nbsp;&nbsp;" . $valorImpuesto . "</font></td></tr>
    <tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:13px;font-weight:normal;'>Costo:&nbsp;&nbsp;" . $valorPenalidad . "</font></td></tr>
    <tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:13px;font-weight:normal;'>Valor a entregar:&nbsp;&nbsp;" . number_format($valorFinal, '2', ',', '.') . "</font></td></tr>
    <tr><td align='center' valign='top'><div style='height:5px;'>&nbsp;</div></td></tr>
    </table>";


    /* traduce etiquetas de un documento a inglés si el idioma del usuario es inglés. */
    if (strtoupper($Usuario->idioma) == 'EN') {
        $html_barcode = str_replace('Nota de Retiro', 'Withdrawal Note', $html_barcode);
        $html_barcode = str_replace('Nota No', 'Withdraw No', $html_barcode);
        $html_barcode = str_replace('No. de Cliente', 'Client No', $html_barcode);

        $html_barcode = str_replace('Valor a Retirar', 'Amount to Withdraw', $html_barcode);
        $html_barcode = str_replace('Impuesto', 'Tax', $html_barcode);
        $html_barcode = str_replace('Costo', 'Cost', $html_barcode);
        $html_barcode = str_replace('Valor a entregar', 'Amount Final', $html_barcode);

    }


    /* Crea un array en PHP que contiene un código HTML de código de barras. */
    $data = array(
        "html" => $html_barcode

    );
    $data = array(
        "html" => $html_barcode

    );
    /*$Proveedor = new Proveedor("", "IES");
    $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), 1);
    $WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
    $WebsocketUsuario->sendWSMessage();*/


    if ($service == "UserBank") {

        /* Establece un valor máximo de 500 para la aprobación de procesos o solicitudes. */
        $valorMaximoParaAprobacion = 500;
        if ($Usuario->mandante == 14 && $CuentaCobro->getValor() <= $valorMaximoParaAprobacion) {
            //Aprobacion nota de retiro


            /* establece el estado de CuentaCobro según el medio de pago y usuario. */
            if ($CuentaCobro->getMediopagoId() == "" || $CuentaCobro->getMediopagoId() == "0") {
                $CuentaCobro->setEstado('A');
            } else {
                $CuentaCobro->setEstado('P');
            }


            $CuentaCobro->setUsucambioId($_SESSION['usuario2']);
            //$CuentaCobro->setFechaPago(date('Y-m-d H:i:s'));


            /* Establece valores predeterminados para usucambioId y usupagoId en cero si son nulos. */
            if ($CuentaCobro->usucambioId == "" || $CuentaCobro->usucambioId == "null" || $CuentaCobro->usucambioId == null) {
                $CuentaCobro->usucambioId = 0;
            }
            if ($CuentaCobro->usupagoId == "" || $CuentaCobro->usupagoId == "null" || $CuentaCobro->usupagoId == null) {
                $CuentaCobro->usupagoId = 0;
            }

            /* verifica y establece valores predeterminados en propiedades de $CuentaCobro. */
            if ($CuentaCobro->usurechazaId == "" || $CuentaCobro->usurechazaId == "null" || $CuentaCobro->usurechazaId == null) {
                $CuentaCobro->usurechazaId = 0;
            }
            if ($CuentaCobro->fechaCambio == "" || $CuentaCobro->fechaCambio == "0000-00-00 00:00:00" || $CuentaCobro->fechaCambio == null) {
                $CuentaCobro->fechaCambio = date("Y-m-d H:i:s");
            }


            /* Asignar fecha actual a "fechaAccion" y "fechaCambio" si están vacías o nulas. */
            if ($CuentaCobro->fechaAccion == "" || $CuentaCobro->fechaAccion == "0000-00-00 00:00:00" || $CuentaCobro->fechaAccion == null) {
                $CuentaCobro->fechaAccion = date("Y-m-d H:i:s");
            }
            $CuentaCobro->fechaCambio = date("Y-m-d H:i:s");


            $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();

            /* Actualiza la cuenta, confirma transacción y ejecuta un pago, verificando el proveedor. */
            $CuentaCobroMySqlDAO->update($CuentaCobro);
            $CuentaCobroMySqlDAO->getTransaction()->commit();


//Pago nota de retiro

            try {

                $Banco = new Banco($UsuarioBanco->bancoId);
                $Producto = new Producto($Banco->productoPago);
                $Proveedor = new Proveedor($Producto->getProveedorId());


                if ($Proveedor->getAbreviado() == "PBROKERSPA") {

                    $PAYBROKERSSERVICES = new PAYBROKERSSERVICES();

                    $PAYBROKERSSERVICES->cashOut($CuentaCobro);
                }

            } catch (Exception $e) {
                /* captura excepciones y lanza nuevamente si el código es '100000'. */

                throw $e;
                if ($e->getCode() == '100000') {

                }
            }


            /* Se establece el estado y los IDs de pago y cambio en una cuenta de cobro. */
            $CuentaCobro->setEstado('S');
            $CuentaCobro->setUsupagoId(0);
            //$CuentaCobro->setFechaPago(date('Y-m-d H:i:s'));

            if ($CuentaCobro->usucambioId == "" || $CuentaCobro->usucambioId == "null" || $CuentaCobro->usucambioId == null) {
                $CuentaCobro->usucambioId = 0;
            }

            /* Asigna 0 a usupagoId y usurechazaId si están vacíos o nulos. */
            if ($CuentaCobro->usupagoId == "" || $CuentaCobro->usupagoId == "null" || $CuentaCobro->usupagoId == null) {
                $CuentaCobro->usupagoId = 0;
            }
            if ($CuentaCobro->usurechazaId == "" || $CuentaCobro->usurechazaId == "null" || $CuentaCobro->usurechazaId == null) {
                $CuentaCobro->usurechazaId = 0;
            }

            /* Asigna la fecha actual si las fechas son vacías o inválidas. */
            if ($CuentaCobro->fechaCambio == "" || $CuentaCobro->fechaCambio == "0000-00-00 00:00:00" || $CuentaCobro->fechaCambio == null) {
                $CuentaCobro->fechaCambio = date("Y-m-d H:i:s");
            }

            if ($CuentaCobro->fechaAccion == "" || $CuentaCobro->fechaAccion == "0000-00-00 00:00:00" || $CuentaCobro->fechaAccion == null) {
                $CuentaCobro->fechaAccion = date("Y-m-d H:i:s");
            }


            /* Actualiza una cuenta de cobro y obtiene la transacción relacionada en MySQL. */
            $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();
            $CuentaCobroMySqlDAO->update($CuentaCobro);
            $CuentaCobroMySqlDAO->getTransaction()->commit();


        }


        /* Envía un mensaje a Slack si ciertas condiciones del usuario y cuenta se cumplen. */
        if ($Usuario->mandante == 14 && $CuentaCobro->getValor() > $valorMaximoParaAprobacion) {

            try {

                $message = "Saque pendente *Usuario:* " . $Usuario->usuarioId . " - *Valor:* " . $Usuario->moneda . " " . $CuentaCobro->getValor();

                exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . $message . "' '#lotosports-virtualsoft' > /dev/null & ");
            } catch (Exception $e) {

            }

        }
    }


    if ($service == "UserBank" && false) {
        if ($Usuario->mandante == 17 && $CuentaCobro->getValor() <= 30000) {
            //Aprobacion nota de retiro


            /* establece el estado de una cuenta según el medio de pago. */
            if ($CuentaCobro->getMediopagoId() == "" || $CuentaCobro->getMediopagoId() == "0") {
                $CuentaCobro->setEstado('A');
            } else {
                $CuentaCobro->setEstado('P');
            }
            $CuentaCobro->setUsucambioId($_SESSION['usuario2']);
            //$CuentaCobro->setFechaPago(date('Y-m-d H:i:s'));


            /* Asigna 0 a usucambioId y usupagoId si están vacíos o nulos. */
            if ($CuentaCobro->usucambioId == "" || $CuentaCobro->usucambioId == "null" || $CuentaCobro->usucambioId == null) {
                $CuentaCobro->usucambioId = 0;
            }
            if ($CuentaCobro->usupagoId == "" || $CuentaCobro->usupagoId == "null" || $CuentaCobro->usupagoId == null) {
                $CuentaCobro->usupagoId = 0;
            }

            /* asigna valores predeterminados a propiedades de un objeto si están vacías. */
            if ($CuentaCobro->usurechazaId == "" || $CuentaCobro->usurechazaId == "null" || $CuentaCobro->usurechazaId == null) {
                $CuentaCobro->usurechazaId = 0;
            }
            if ($CuentaCobro->fechaCambio == "" || $CuentaCobro->fechaCambio == "0000-00-00 00:00:00" || $CuentaCobro->fechaCambio == null) {
                $CuentaCobro->fechaCambio = date("Y-m-d H:i:s");
            }


            /* Asigna la fecha actual si fechaAccion está vacía o nula y establece fechaCambio. */
            if ($CuentaCobro->fechaAccion == "" || $CuentaCobro->fechaAccion == "0000-00-00 00:00:00" || $CuentaCobro->fechaAccion == null) {
                $CuentaCobro->fechaAccion = date("Y-m-d H:i:s");
            }
            $CuentaCobro->fechaCambio = date("Y-m-d H:i:s");


            $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();

            /* Actualiza una cuenta de cobro y obtiene la transacción asociada en MySQL. */
            $CuentaCobroMySqlDAO->update($CuentaCobro);
            $CuentaCobroMySqlDAO->getTransaction()->commit();


            //Pago nota de retiro

            try {


                /* Se realiza un retiro de efectivo si el proveedor es PBROKERSPA. */
                $Banco = new Banco($UsuarioBanco->bancoId);
                $Producto = new Producto($Banco->productoPago);
                $Proveedor = new Proveedor($Producto->getProveedorId());


                if ($Proveedor->getAbreviado() == "PBROKERSPA") {

                    $PAYBROKERSSERVICES = new PAYBROKERSSERVICES();

                    $PAYBROKERSSERVICES->cashOut($CuentaCobro);
                }

                /* Valida el proveedor y ejecuta un servicio de cashOut para EZZEPAY. */
                if ($Proveedor->getAbreviado() == "EZZEPAY") {

                    $EZZEPAYSERVICES = new EZZEPAYSERVICES();

                    $EZZEPAYSERVICES->cashOut($CuentaCobro);
                }

            } catch (Exception $e) {
                /* Manejo de excepciones en PHP, re-lanzando errores y verificando un código específico. */

                throw $e;
                if ($e->getCode() == '100000') {

                }
            }


            /* establece estado y usuario de pago, manejando un ID vacío correctamente. */
            $CuentaCobro->setEstado('S');
            $CuentaCobro->setUsupagoId(0);
            //$CuentaCobro->setFechaPago(date('Y-m-d H:i:s'));

            if ($CuentaCobro->usucambioId == "" || $CuentaCobro->usucambioId == "null" || $CuentaCobro->usucambioId == null) {
                $CuentaCobro->usucambioId = 0;
            }

            /* Asigna 0 a usupagoId y usurechazaId si están vacíos o nulos. */
            if ($CuentaCobro->usupagoId == "" || $CuentaCobro->usupagoId == "null" || $CuentaCobro->usupagoId == null) {
                $CuentaCobro->usupagoId = 0;
            }
            if ($CuentaCobro->usurechazaId == "" || $CuentaCobro->usurechazaId == "null" || $CuentaCobro->usurechazaId == null) {
                $CuentaCobro->usurechazaId = 0;
            }

            /* Asigna la fecha actual a propiedades si están vacías o nulas. */
            if ($CuentaCobro->fechaCambio == "" || $CuentaCobro->fechaCambio == "0000-00-00 00:00:00" || $CuentaCobro->fechaCambio == null) {
                $CuentaCobro->fechaCambio = date("Y-m-d H:i:s");
            }

            if ($CuentaCobro->fechaAccion == "" || $CuentaCobro->fechaAccion == "0000-00-00 00:00:00" || $CuentaCobro->fechaAccion == null) {
                $CuentaCobro->fechaAccion = date("Y-m-d H:i:s");
            }


            /* actualiza una cuenta de cobro y obtiene la transacción asociada. */
            $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();
            $CuentaCobroMySqlDAO->update($CuentaCobro);
            $CuentaCobroMySqlDAO->getTransaction()->commit();


        }


    }


    if ($Usuario->mandante == 0 && $Usuario->paisId == 173) {


        /* Se definen variables para limitar y gestionar solicitudes activas y filas. */
        $maximoCantidadSolicitudesActivas = 1;

        $MaxRows = 1;
        $OrderedItem = 1;
        $SkeepRows = 0;


        $rules = [];


        /* Se definen reglas de filtrado para una consulta sobre cuentas de cobro. */
        array_push($rules, array("field" => "cuenta_cobro.estado", "data" => "'A','I','P','S'", "op" => "in"));
        array_push($rules, array("field" => "cuenta_cobro.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
        array_push($rules, array("field" => "cuenta_cobro.fecha_crea", "data" => date('Y-m-d'), "op" => "bw"));
        // array_push($rules, array("field" => "cuenta_cobro.version", "data" => '2', "op" => "eq"));

        $filtro = array("rules" => $rules, "groupOp" => "AND");

        /* convierte un filtro a JSON y obtiene cuentas de cobro personalizadas. */
        $jsonfiltro = json_encode($filtro);

        $CuentaCobro = new CuentaCobro();

        $cuentas = $CuentaCobro->getCuentasCobroCustom("sum(cuenta_cobro.valor) sum, count(cuenta_cobro.cuenta_id) cant ", "cuenta_cobro.cuenta_id", "asc", $SkeepRows, $MaxRows, $jsonfiltro, true, "cuenta_cobro.usuario_id");

        $cuentas = json_decode($cuentas);


        /* obtiene la suma y el conteo de cuentas desde un objeto. */
        $sum = $cuentas->data[0]->{'.sum'};
        $cant = $cuentas->count[0]->{'.count'};

        if ((floatval($sum)) >= 8000) {


            /* Se definen variables para controlar la paginación y un arreglo para reglas. */
            $MaxRows = 1;
            $OrderedItem = 1;
            $SkeepRows = 0;


            $rules = [];


            /* Se construye un filtro en formato JSON para consultas con reglas específicas. */
            array_push($rules, array("field" => "usuario_recarga.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
            array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => date('Y-m-d'), "op" => "bw"));
            // array_push($rules, array("field" => "cuenta_cobro.version", "data" => '2', "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $jsonfiltro = json_encode($filtro);


            /* Se procesa y suma el valor de las recargas de un usuario específico. */
            $UsuarioRecarga = new UsuarioRecarga();

            $recargas = $UsuarioRecarga->getUsuarioRecargasCustom("sum(usuario_recarga.valor) sum, count(usuario_recarga.recarga_id) cant ", "usuario_recarga.recarga_id", "asc", $SkeepRows, $MaxRows, $jsonfiltro, true, "usuario_recarga.usuario_id");

            $recargas = json_decode($recargas);

            $sumRecargas = $recargas->data[0]->{'.sum'};

            /* cuenta recargas y asigna un destinatario para notificaciones. */
            $cantRecargas = $recargas->count[0]->{'.count'};


            $destinatarios = 'oficialdecumplimiento@doradobet.com';


            $ConfigurationEnvironment = new ConfigurationEnvironment();

            try {

                /* Se crean objetos para un mandante, clasificador y plantilla, generando un mensaje HTML. */
                $Mandante = new Mandante($Usuario->mandante);

                $clasificador = new Clasificador("", "TEMPALERTARETIR");

                $template = new Template("", $Mandante->mandante, $clasificador->getClasificadorId(), $Usuario->paisId, $Usuario->idioma);

                $mensaje_txt = $template->templateHtml;


                /* Verifica si las variables están vacías y las inicializa a cero. */
                if ($sumRecargas == '') {
                    $sumRecargas = '0';
                }
                if ($cantRecargas == '') {
                    $cantRecargas = '0';
                }


                /* Reemplaza marcadores de posición en un mensaje con datos del usuario y mandante. */
                $mensaje_txt = str_replace("#userid#", $Usuario->usuarioId, $mensaje_txt);
                $mensaje_txt = str_replace("#name#", $Usuario->nombre, $mensaje_txt);
                $mensaje_txt = str_replace("#partner#", $Mandante->descripcion, $mensaje_txt);
                $mensaje_txt = str_replace("#email#", $Usuario->login, $mensaje_txt);
                $mensaje_txt = str_replace("#amountWithdrawals#", $sum, $mensaje_txt);
                $mensaje_txt = str_replace("#cantWithdrawals#", $cant, $mensaje_txt);

                /* Reemplaza variables en un mensaje y envía un correo de alerta sobre retiros. */
                $mensaje_txt = str_replace("#amountDeposits#", $sumRecargas, $mensaje_txt);
                $mensaje_txt = str_replace("#cantDeposits#", $cantRecargas, $mensaje_txt);

                $ConfigurationEnvironment = new ConfigurationEnvironment();

                $envio = $ConfigurationEnvironment->EnviarCorreoVersion3($destinatarios, '', $Mandante->descripcion, 'Alerta Retiros Usuario ' . $Usuario->usuarioId, '', 'Alerta Retiros Usuario ' . $Usuario->usuarioId, $mensaje_txt, '', '', '', $Usuario->mandante);

            } catch (\Exception $e) {
                /* Captura excepciones en PHP sin realizar ninguna acción dentro del bloque. */


            }
        }
    }
}
