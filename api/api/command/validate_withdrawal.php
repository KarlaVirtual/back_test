<?php

use Backend\dto\Banco;
use Backend\dto\BonoInterno;
use Backend\dto\Clasificador;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\CuentaCobro;
use Backend\dto\GeneralLog;
use Backend\dto\Mandante;
use Backend\dto\MandanteDetalle;
use Backend\dto\Producto;
use Backend\dto\Proveedor;
use Backend\dto\Registro;
use Backend\dto\Template;
use Backend\dto\Usuario;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioRecarga;
use Backend\integrations\payout\PAYBROKERSSERVICES;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\mysql\CuentaCobroMySqlDAO;
    use Backend\mysql\GeneralLogMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Dompdf\Dompdf;

/**
 * command/validate_withdrawal
 *
 * Este recurso sirve para validar los retiros de los usuario ingresando el codigo OTP que es enviado a su correo
 *
 * @param string $code : codigo OTP que se envia al correo del usuario
 * @param string $WithdrawId : ID de la nota de retiro
 * @param string $service : Tipo de servicio que se utilizo para realizar el retiro
 *
 * @return object El objeto $response es un array con los siguientes atributos:
 * - *code* (int): Codigo de error desde el proveedor 0 en caso de exito
 * - *data* (array): Contiene toda la información referente del usuario y sus notas de retiro
 *
 * @throws Exception No existe nota de retiro por validar (300058)
 * @throws Exception Nota de retiro expirada por tiempo de codigo otp (300057)
 * @throws Exception Error general (100000)
 * @throws Exception Cancelado por ingreso de codigo OPT malo (300059)
 *
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* Asignación de valores de un JSON a variables y creación de objetos de usuario. */
$code = $json->params->code ?: '';
$WithdrawId = $json->params->WithdrawId ?: '';
$service = $json->params->service;

$UsuarioMandante = new UsuarioMandante($json->session->usuario);
$Usuario = new Usuario($UsuarioMandante->usuarioMandante);


/* Verifica el estado y usuario de una cuenta de cobro; lanza excepción si no coincide. */
$CuentaCobro = new CuentaCobro($WithdrawId);

if ($CuentaCobro->estado !== 'O' || $CuentaCobro->usuarioId != $Usuario->usuarioId) {
    throw new Exception('No existe nota de retiro por validar', 300058);
}

$expire_time = 0;


/* Código que inicializa objetos y maneja excepciones para obtener un tiempo de expiración. */
try {
    $Clasificador = new Clasificador('', 'MAXTIMEOTPCODE');
    $MandanteDetalle = new MandanteDetalle('', $Usuario->mandante, $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
    $expire_time = $MandanteDetalle->valor;
} catch (Exception $ex) {
}


/* verifica si un código OTP ha expirado antes de procesarlo. */
$expire_time = $expire_time / 1000;

if (date('Y-m-d H:i:s', strtotime($CuentaCobro->fechaCrea) + $expire_time) < date('Y-m-d H:i:s')) {
    throw new Exception('Nota de retiro expirada por tiempo de codigo otp', 300057);
}

$opt_code = intval($CuentaCobro->cuentaId) + strtotime($CuentaCobro->fechaCrea);

/* invierte, recorta y obtiene una transacción de la base de datos. */
$opt_code = substr(strrev(strval($opt_code)), 0, 6);

$CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();
$Transaction = $CuentaCobroMySqlDAO->getTransaction();

if ($opt_code != $code) {

    /* Cambia el estado de cuenta a 'Z' y actualiza en la base de datos. */
    $beforeValue = $CuentaCobro->estado;
    $CuentaCobro->estado = 'Z';
    $afterValue = $CuentaCobro->estado;

    $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO($Transaction);
    $rowAffected = $CuentaCobroMySqlDAO->update($CuentaCobro, ' AND cuenta_cobro.estado = "O"');


    /* verifica afectaciones y realiza un rollback si no hay cambios. */
    if ($rowAffected == 0) {
        $Transaction->rollback();
        throw new Exception('Error general', 100000);
    }

    $Usuario->creditWin2($CuentaCobro->valor, $Transaction, true);


    /* Se registra un historial de usuario por un retiro rechazado. */
    $UsuarioHistorial = new UsuarioHistorial();
    $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
    $UsuarioHistorial->setDescripcion('Nota de retiro rechazada por codigo OTP');
    $UsuarioHistorial->setMovimiento('E');
    $UsuarioHistorial->setUsucreaId(0);
    $UsuarioHistorial->setUsumodifId(0);

    /* Se está insertando un historial de usuario en una base de datos MySql. */
    $UsuarioHistorial->setTipo(40);
    $UsuarioHistorial->setValor($CuentaCobro->valor);
    $UsuarioHistorial->setExternoId($CuentaCobro->cuentaId);

    $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
    $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);


    /* Se crea un objeto 'GeneralLog' y se configuran sus propiedades relacionadas al usuario. */
    $GeneralLog = new GeneralLog();
    $GeneralLog->setUsuarioId($Usuario->usuarioId);
    $GeneralLog->setUsuarioIp(0);
    $GeneralLog->setUsuariosolicitaId(0);
    $GeneralLog->setUsuariosolicitaIp(0);
    $GeneralLog->setTipo('CHANGESTATE');

    /* Configura un registro general con valores antes y después, usuario y dispositivo. */
    $GeneralLog->setEstado('A');
    $GeneralLog->setValorAntes($beforeValue);
    $GeneralLog->setValorDespues($afterValue);
    $GeneralLog->setUsucreaId(0);
    $GeneralLog->setUsumodifId(0);
    $GeneralLog->setDispositivo('');

    /* Código que configura un registro de log general para transacciones en "cuenta_cobro". */
    $GeneralLog->setSoperativo('');
    $GeneralLog->setSversion('');
    $GeneralLog->setTabla('cuenta_cobro');
    $GeneralLog->setCampo('estado');
    $GeneralLog->setExternoId(0);
    $GeneralLog->setMandante($Usuario->mandante);

    /* Registro de una nota de retiro cancelada debido a un código incorrecto. */
    $GeneralLog->setExplicacion('Nota de retiro cancelada por ingreso malo del codigo OPT');

    $GeneralLogMySqlDAO = new GeneralLogMySqlDAO($Transaction);
    $GeneralLogMySqlDAO->insert($GeneralLog);

    $Transaction->commit();
    throw new
    /* crea una excepción por un código OTP incorrecto ingresado por el usuario. */
    Exception('Cancelado por ingreso de codigo OPT malo', 300059);
}


/* Actualiza el estado de CuentaCobro basado en el tipo de servicio y excepciones. */
$afterValue = $CuentaCobro->estado;
$CuentaCobro->estado = 'A';

if ($service === 'local') {
    try {
        $Clasificador = new Clasificador('', 'ACTIVATEWITHDRAWALNOTES');
        $tipoDetalle = $Clasificador->getClasificadorId();
        $MandanteDetalle = new MandanteDetalle('', $Usuario->mandante, $tipoDetalle, $Usuario->paisId, 'A');
        if (isset($MandanteDetalle)) $CuentaCobro->estado = 'M';
    } catch (Exception $e) {
    }
}

if ($CuentaCobro->estado !== 'M') {

    /* Asignación del estado de una cuenta cobro según condiciones del usuario y servicio. */
    if ($Usuario->mandante == 8 && $service === 'local') $CuentaCobro->estado = 'M';
    else if (in_array($Usuario->mandante, [3, 4, 5, 6, 7]) && $service === 'local') $CuentaCobro->estado = 'M';
    else if ($Usuario->mandante == 8 && $service === 'local' && $CuentaCobro->valor >= 300) $CuentaCobro->estado = 'M';
    else if ($Usuario->mandante == 8 && $Usuario->paisId == 66 && $CuentaCobro->estado == 'A' && $service === 'local') {
        $rules = [];

        array_push($rules, ['field' => 'cuenta_cobro.estado', 'data' => '"A","I","P","S","M"', 'op' => 'in']);
        array_push($rules, ['field' => 'cuenta_cobro.usuario_id', 'data' => $UsuarioMandante->usuarioMandante, 'op' => 'eq']);
        array_push($rules, ['field' => 'cuenta_cobro.fecha_crea', 'data' => date('Y-m-d'), 'op' => 'bw']);

        $filters = json_encode(['rules' => $rules, 'groupOp' => "AND"]);

        $CuentaCobro2 = new CuentaCobro();

        $queryWithdraw = (string)$CuentaCobro2->getCuentasCobroCustom('sum(cuenta_cobro.valor) sum, count(cuenta_cobro.cuenta_id) cant ', 'cuenta_cobro.cuenta_id', 'asc', 0, 1, $filters, true, 'cuenta_cobro.usuario_id');
        $queryWithdraw = json_decode($queryWithdraw, true);

        $cant = $queryWithdraw['count'][0]['.count'];

        if ((floatval($cant)) >= 1) $CuentaCobro->estado = 'M';
    }
}


/* Actualiza el estado de 'CuentaCobro' y maneja errores con transacciones. */
$beforeValue = $CuentaCobro->estado;
$CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO($Transaction);
$rowAffected = $CuentaCobroMySqlDAO->update($CuentaCobro, ' AND cuenta_cobro.estado = "O"');

if ($rowAffected == 0) {
    $Transaction->rollback();
    throw new Exception('Error general', 100000);
} else {

    /* Crea un registro general con información del usuario y tipo 'CHANGESTATE'. */
    $GeneralLog = new GeneralLog();
    $GeneralLog->setUsuarioId($Usuario->usuarioId);
    $GeneralLog->setUsuarioIp(0);
    $GeneralLog->setUsuariosolicitaId(0);
    $GeneralLog->setUsuariosolicitaIp(0);
    $GeneralLog->setTipo('CHANGESTATE');

    /* Se registra un cambio en el log general con valores previos y posteriores. */
    $GeneralLog->setEstado('A');
    $GeneralLog->setValorAntes($beforeValue);
    $GeneralLog->setValorDespues($afterValue);
    $GeneralLog->setUsucreaId(0);
    $GeneralLog->setUsumodifId(0);
    $GeneralLog->setDispositivo('');

    /* Configuración de log general para la tabla "cuenta_cobro" y campo "estado". */
    $GeneralLog->setSoperativo('');
    $GeneralLog->setSversion('');
    $GeneralLog->setTabla('cuenta_cobro');
    $GeneralLog->setCampo('estado');
    $GeneralLog->setExternoId(0);
    $GeneralLog->setMandante($Usuario->mandante);

    /* registra una nota aprobada tras validar un código OTP en la base de datos. */
    $GeneralLog->setExplicacion('Nota aprovada con validacion de codigo OTP');

    $GeneralLogMySqlDAO = new GeneralLogMySqlDAO($Transaction);
    $GeneralLogMySqlDAO->insert($GeneralLog);

    $Transaction->commit();
}


/* descifra una clave de base de datos utilizando una clave de encriptación. */
$encryptKey = "12hur12b";

    $Bonointerno = new BonoInterno();
    $BonointernoMySqlDAO = new BonoInternoMySqlDAO();
    $transaccion = $BonointernoMySqlDAO->getTransaction();
    $sqlQuery2 = "SET @@SESSION.block_encryption_mode = 'aes-128-ecb';";
    $Bonointerno->execQuery($transaccion,$sqlQuery2);


$BonoInterno = new BonoInterno();
    $data = json_encode($BonoInterno->execQuery($transaccion, "SELECT aes_decrypt(clave, '{$encryptKey}') AS clave FROM cuenta_cobro WHERE cuenta_id = {$WithdrawId}"));
$queryData = json_decode($data, true)[0];

    $sqlQuery2 = "SET @@SESSION.block_encryption_mode = 'aes-128-cbc';";

    $Bonointerno->execQuery($transaccion,$sqlQuery2);


$CuentaCobro = new CuentaCobro($WithdrawId);

/* Se inicializan variables relacionadas con un registro y valores de impuestos. */
$Registro = new Registro('', $Usuario->usuarioId);

$valorImpuesto = 0;
$valorImpuesto2 = 0;
$impuesto = 0;
$impuesto2 = 0;

/* Calcula valores de impuestos basados en clasificaciones y detalles del mandante. */
$valorPenalidad = 0;

try {
    $Clasificador = new Clasificador("", "TAXWITHDRAWAWARD");
    $impuesto = -1;

    $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
    $impuesto = $MandanteDetalle->getValor();

    $Clasificador = new Clasificador("", "TAXWITHDRAWAWARDISR");
    $impuesto2 = -1;

    $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
    $impuesto2 = $MandanteDetalle->getValor();
    $valorImpuesto2 = $CuentaCobro->valor * ($impuesto2 / 100);


} catch (Exception $ex) {
    /* Captura excepciones en PHP, permitiendo manejar errores sin interrumpir la ejecución. */

}


/* Verifica si hay un valor de impuesto definido*/
if ($impuesto > 0) {
    try {
        $Clasificador = new Clasificador("", "TAXWITHDRAWAWARDFROM");
        $impuestoDesde = -1;

        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
        $impuestoDesde = $MandanteDetalle->getValor();
    } catch (Exception $ex) {
    }

    if ($impuestoDesde != -1) {
        if ($amount >= $impuestoDesde) {
            $valorImpuesto = ($impuesto / 100) * $CuentaCobro->valor;
            if ($impuesto2 > 0) $valorImpuesto2 = ($amount - $valorImpuesto) * ($impuesto2 / 100);
        }
    }
}


/* Continúa un proceso en caso de que el usuario cuente con créditos */
if ($Registro->creditosBase > 0) {
    try {
        $Clasificador = new Clasificador("", "TAXWITHDRAWDEPOSIT");
        $impuesto = -1;

        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
        $impuesto = $MandanteDetalle->getValor();
    } catch (Exception $ex) {
    }

    if ($impuesto > 0) {
        try {
            $Clasificador = new Clasificador("", "TAXWITHDRAWAWARDFROM");
            $impuestoDesde = -1;

            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
            $impuestoDesde = $MandanteDetalle->getValor();
        } catch (Exception $ex) {
        }

        if ($impuestoDesde != -1) {
            if ($amount >= $impuestoDesde) {
                $valorImpuesto = ($impuesto / 100) * $CuentaCobro->valor;
                if ($impuesto2 > 0) $valorImpuesto2 = ($amount - $valorImpuesto) * ($impuesto2 / 100);
            }
        }
    }
}

switch ($service) {
    case 'local':
        $method = 'pdf';
        $status_message = "<table style='width:430px;height: 355px;/* border:1px solid black; */'><tbody><tr><td align='center' valign='top'><div style='height:5px;'>&nbsp;</div></td></tr>";
        if (in_array($Usuario->mandante, [3, 4, 5, 6, 7, 10])) {
            $status_message = $status_message . "<tr>";
            $status_message = $status_message . "<td align='enter' valign='top'>";
            $status_message = $status_message . "<font style='padding-left:5px;text-align:center;font-size:14px;'>PERMISO SEGOB 8.S.7.1/DGG/SN/94, OFICIO DE AUTORIZACION No. DGJS/0223/2020 DE FECHA 12 DE MARZO DE 2020</font>";
            $status_message = $status_message . "</td>";
            $status_message = $status_message . "</tr><tr><td align='center' valign='top'><div style='height:2px;'>&nbsp;</div></td></tr>";
        }
        if (strtolower($Usuario->idioma) == 'en') {
            $status_message .= "
                <tr><td align='center' valign='top'><font style='text-align:center;font-size:20px;font-weight:bold;'>Withdrawal Note</font></td></tr>
                <tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:16px;font-weight:normal;'>Withdrawal note number.:&nbsp;&nbsp;" . $CuentaCobro->cuentaId . "</font></td></tr>
                <tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:16px;font-weight:normal;'>Client number:&nbsp;&nbsp;" . $Usuario->usuarioId . "</font></td></tr>
                <tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:16px;font-weight:normal;'>Name:&nbsp;&nbsp;" . $Usuario->nombre . "</font></td></tr>
                <tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:16px;font-weight:normal;'>Date:&nbsp;&nbsp;" . $CuentaCobro->fechaCrea . "</font></td></tr>
                <tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:16px;font-weight:normal;'>Password:&nbsp;&nbsp;" . $queryData['.clave'] . "</font></td></tr>";
        } else {
            $status_message .= "
                <tr><td align='center' valign='top'><font style='text-align:center;font-size:20px;font-weight:bold;'>NOTA DE RETIRO</font></td></tr>
                <tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:16px;font-weight:normal;'>Nota de retiro No.:&nbsp;&nbsp;" . $CuentaCobro->cuentaId . "</font></td></tr>
                <tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:16px;font-weight:normal;'>No. de Cliente:&nbsp;&nbsp;" . $Usuario->usuarioId . "</font></td></tr>
                <tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:16px;font-weight:normal;'>Nombre:&nbsp;&nbsp;" . $Usuario->nombre . "</font></td></tr>
                <tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:16px;font-weight:normal;'>Fecha:&nbsp;&nbsp;" . $CuentaCobro->fechaCrea . "</font></td></tr>
                <tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:16px;font-weight:normal;'>Clave:&nbsp;&nbsp;" . $queryData['.clave'] . "</font></td></tr>";
        }

        if ($Usuario->paisId == 173) {
            $tipoDoc = $Registro->tipoDoc;
            switch ($tipoDoc) {
                case 'P':
                    $tipoDoc = 'Pasaporte';
                    break;
                case 'C':
                    $tipoDoc = 'DNI';
                    break;
                case 'E':
                    $tipoDoc = 'Carnet de extranjeria';
                    break;
            }

            $status_message .= "<tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:13px;font-weight:normal;'>Tipo de Doc: :&nbsp;&nbsp;" . $tipoDoc .
                "</font></td></tr><tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:13px;font-weight:normal;'>Documento:&nbsp;&nbsp;" . $Registro->cedula .
                "</font></td></tr>";
        }

        if (in_array($Usuario->mandante, [3, 4, 5, 6, 7, 10])) {
            $status_message = $status_message . "<tr>";
            $status_message = $status_message . "<td align='center' valign='top'>";
            $status_message = $status_message . "<font style='padding-left:5px;text-align:center;font-size:14px;'>RECIBO DE NETABET SA DE CV LA CANTIDAD INDICADA, POR CONCEPTO DE PAGO DE APUESTA GANADA Y/O RETIRO.</font>";
            $status_message = $status_message . "</td>";
            $status_message = $status_message . "</tr><tr><td align='center' valign='top'><div style='height:2px;'>&nbsp;</div></td></tr>";
        }

        if (strtolower($Usuario->idioma) == 'en') {
            $status_message .= "<tr><td align='center' valign='top'><div style='height:1px;''>&nbsp;</div></td></tr><tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:18px;font-weight:bold;'>Value to withdraw:&nbsp;&nbsp;" . $CuentaCobro->valor .
                "</font></td></tr><tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:18px;font-weight:bold;'>Tax:&nbsp;&nbsp;" . $valorImpuesto .
                "</font></td></tr><tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:18px;font-weight:bold;'>Cost:&nbsp;&nbsp;" . $valorPenalidad .
                "</font></td></tr><tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:18px;font-weight:bold;'>Value to deliver:&nbsp;&nbsp;" . $CuentaCobro->valor .
                "</font></td></tr><tr><td align='center' valign='top'><div style='height:5px;'>&nbsp;</div></td></tr></tbody></table>";
        } else {
            $status_message .= "<tr><td align='center' valign='top'><div style='height:1px;'>&nbsp;</div></td></tr><tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:18px;font-weight:bold;'>Valor a retirar:&nbsp;&nbsp;" . number_format($CuentaCobro->valor, '2', ',', '.') .
                "</font></td></tr><tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:18px;font-weight:bold;'>Impuesto:&nbsp;&nbsp;" . $valorImpuesto .
                "</font></td></tr><tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:18px;font-weight:bold;'>Costo:&nbsp;&nbsp;" . $valorPenalidad .
                "</font></td></tr><tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:18px;font-weight:bold;'>Valor a entregar:&nbsp;&nbsp;" . number_format($CuentaCobro->valor, '2', ',', '.') .
                "</font></td></tr><tr><td align='center' valign='top'><div style='height:5px;'>&nbsp;</div></td></tr></tbody></table>";
        }

        if ((($Usuario->mandante == 0 || $Usuario->mandante == 8 || $Usuario->mandante == 6) && false) || $Usuario->mandante == '0') {
            try {

                // Se crea una nueva instancia de la clase Clasificador con parámetros específicos
                $Clasificador = new Clasificador('', 'TEMRECNORE');

                $Template = new Template('', $Usuario->mandante, $Clasificador->clasificadorId, $Usuario->paisId, strtolower($Usuario->idioma));
                $html_barcode = $Template->templateHtml;

                if ($html_barcode != '') {
                    /**
                     * Genera el código HTML para un boleto de retiro, incluyendo información relevante como tipo de documento,
                     * valor del retiro, impuestos, etc. También configura los ajustes para diferentes métodos de pago.
                     */

                    $html_barcode .= $Template->templateHtmlCSSPrint;
                    $html_barcode .= "<style>.bodytmp {width: 300px !important;}</style>";

                    $tipoDoc = $Registro->tipoDoc;

// Se determina el tipo de documento en texto a partir del código
                    switch ($tipoDoc) {
                        case 'P':
                            $tipoDoc = 'Pasaporte'; // Tipo de documento Pasaporte
                            break;
                        case 'C':
                            $tipoDoc = 'DNI'; // Tipo de documento DNI
                            break;
                        case 'E':
                            $tipoDoc = 'Carnet de extranjeria'; // Tipo de documento Carnet de extranjería
                            break;
                    }

                    $html_barcode = str_replace('#idnotewithdrawal#', $CuentaCobro->cuentaId, $html_barcode);
                    $html_barcode = str_replace('#withdrawalnotenumber#', $CuentaCobro->cuentaId, $html_barcode);
                    $html_barcode = str_replace('#value#', $Usuario->moneda . ' ' . $CuentaCobro->valor, $html_barcode);
                    $html_barcode = str_replace('#totalvalue#', $Usuario->moneda . ' ' . (floatval($CuentaCobro->valor) - floatval($CuentaCobro->impuesto)), $html_barcode);
                    $html_barcode = str_replace('#tax#', $Usuario->moneda . ' ' . $CuentaCobro->impuesto, $html_barcode);
                    $html_barcode = str_replace('#keynotewithdrawal#', $queryData['.clave'], $html_barcode);
                    $html_barcode = str_replace('#creationdate#', $CuentaCobro->fechaCrea, $html_barcode);
                    $html_barcode = str_replace('#userid#', $CuentaCobro->usuarioId, $html_barcode);
                    $html_barcode = str_replace('#name#', $Usuario->nombre, $html_barcode);
                    $html_barcode = str_replace('#typedoc#', $tipoDoc, $html_barcode);
                    $html_barcode = str_replace('#identification#', $Registro->cedula, $html_barcode);

// Se configura el tipo de retiro y su descripción dependiendo del medio de pago
                    if ($Usuario->mandante == 8) {
                        if ($CuentaCobro->mediopagoId == '693978') {
                            $html_barcode = str_replace('#typewithdraw#', 'Facilito', $html_barcode); // Reemplaza el tipo de retiro Facilito
                            $html_barcode = str_replace('#descriptionFixed#', 'Esta nota de retiro solo podrá ser cobrada en agencias de Ecuabet.com o en las agencias de la red Facilito.', $html_barcode); // Descripción de Facilito
                        } elseif ($CuentaCobro->mediopagoId == '853460') {
                            $html_barcode = str_replace('#typewithdraw#', 'Western Union', $html_barcode); // Reemplaza el tipo de retiro Western Union
                            $html_barcode = str_replace('#descriptionFixed#', 'Este retiro solo podrá ser cobrado 30 minutos después de generado y solo será pagado en locales autorizado de Red Activa Western Unión. (No podrá ser cobrado en franquicias). ', $html_barcode); // Descripción de Western Union
                        } elseif ($CuentaCobro->mediopagoId == '1211624') {
                            $html_barcode = str_replace('#typewithdraw#', 'Bemovil', $html_barcode); // Reemplaza el tipo de retiro Bemovil
                            $html_barcode = str_replace('#descriptionFixed#', 'Este retiro solo podrá ser cobrado 30 minutos después de generado y solo será pagado en locales autorizado de Bemovil. ', $html_barcode); // Descripción de Bemovil
                        } elseif ($CuentaCobro->mediopagoId == '1784692') {
                            $html_barcode = str_replace('#typewithdraw#', 'Bakan', $html_barcode); // Reemplaza el tipo de retiro Bakan
                            $html_barcode = str_replace('#descriptionFixed#', 'Este retiro solo podrá ser cobrado 30 minutos después de generado y solo será pagado en locales autorizado de Bakan. ', $html_barcode); // Descripción de Bakan
                        } elseif ($CuentaCobro->mediopagoId == '2894342') {
                            $html_barcode = str_replace('#typewithdraw#', 'FullCarga', $html_barcode); // Reemplaza el tipo de retiro FullCarga
                            $html_barcode = str_replace('#descriptionFixed#', 'Este retiro solo podrá ser cobrado 30 minutos después de generado y solo será pagado en locales autorizado de FullCarga. ', $html_barcode); // Descripción de FullCarga
                        } else {
                            $html_barcode = str_replace('#typewithdraw#', 'Agencia Ecuabet', $html_barcode); // Reemplaza el tipo de retiro Agencia Ecuabet
                            $html_barcode = str_replace('#descriptionFixed#', 'Esta nota de retiro solo podrá ser cobrada en agencias de Ecuabet.com o en las agencias de la red Facilito.', $html_barcode); // Descripción por defecto
                        }
                    } else {
                        // Reemplaza '#typewithdraw#' en el contenido HTML por 'Punto de Venta'.
                        $html_barcode = str_replace('#typewithdraw#', 'Punto de Venta', $html_barcode);
                        // Reemplaza '#descriptionFixed#' en el contenido HTML por una cadena vacía.
                        $html_barcode = str_replace('#descriptionFixed#', '', $html_barcode);
                    }

                    if ((($Usuario->mandante == 0 || $Usuario->mandante == 8) || $Usuario->mandante == 6)) {

                        $Mandante = new Mandante($Usuario->mandante);

                        $dompdf = new Dompdf();
                        $dompdf->loadHtml($html_barcode);

                        // Define el ancho y alto del documento en milímetros.
                        $width = 90;
                        $height = 150;

                        $paper_format = array(0, 0, ($width / 25.4) * 72, ($height / 25.4) * 72);
                        $dompdf->setPaper($paper_format);
                        $dompdf->render();
                        // Obtiene el lienzo del objeto Dompdf.
                        $canvas = $dompdf->getCanvas();

                        // Obtiene el ancho y alto del lienzo.
                        $w = $canvas->get_width();
                        $h = $canvas->get_height();

                        // Obtiene la URL de la imagen del logo del mandante.
                        $imageURL = $Mandante->logoPdf;
                        $imgWidth = 200;
                        $imgHeight = 100;

                        // Establece la opacidad del lienzo.
                        $canvas->set_opacity(.3);

                        if ($Usuario->mandante == 8) {
                            $canvas->set_opacity(.2);
                            $imgHeight = 70;
                        }
                        // Calcula las posiciones x y y para centrar la imagen en el lienzo.
                        $x = (($w - $imgWidth) / 2);
                        $y = (($h - $imgHeight) / 2) - 30;

                        // Obtiene la salida del documento generado por Dompdf.
                        $data = $dompdf->output();

                        // Codifica el contenido del PDF en base64.
                        $base64 = 'data:application/pdf;base64,' . base64_encode($data);

                        // Almacena el mensaje de estado del PDF y HTML después de codificarlos en base64.
                        $status_messagePDF = base64_encode($data);
                        $status_messageHTML = $html_barcode;
                    }
                }
            } catch (Exception $e) {
            }
        }
        // Verifica si el identificador ($id) no está vacío y asigna su valor a mediopagoId del objeto CuentaCobro
        if ($id != '') $CuentaCobro->mediopagoId = $id;
        break;
    case 'UserBank':
        $method = '0';
        $status_message = '';
        $CuentaCobro->mediopagoId = $id;
        break;
}

$html_barcode = "<table style='width:430px;height: 355px;/* border:1px solid black; */'><tbody><tr><td align='center' valign='top'><font style='text-align:center;font-size:20px;font-weight:bold;'>NOTA DE RETIRO</font></td></tr><tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:16px;font-weight:normal;'>Nota de retiro No.:&nbsp;&nbsp;" . $CuentaCobro->cuentaId .
    "</font></td></tr><tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:16px;font-weight:normal;'>No. de Cliente:&nbsp;&nbsp;" . $Usuario->usuarioId .
    "</font></td></tr><tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:16px;font-weight:normal;'>Nombre:&nbsp;&nbsp;" . $Usuario->nombre .
    "</font></td></tr><tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:16px;font-weight:normal;'>Fecha:&nbsp;&nbsp;" . $CuentaCobro->fechaCrea .
    "</font></td></tr><tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:16px;font-weight:normal;'>Clave:&nbsp;&nbsp;" . $queryData['.clave'] .
    "</font></td></tr><tr><td align='center' valign='top'><div style='height:1px;'>&nbsp;</div></td></tr><tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:18px;font-weight:bold;'>Valor a retirar:&nbsp;&nbsp;" . $CuentaCobro->valor .
    "</font></td></tr></tbody></table>";
$html_barcode = "<table style='width:180px;height:280px;border:1px solid black;'><tr><td align='center' valign='top'><div style='height:5px;'>&nbsp;</div></td></tr>";

if (in_array($Usuario->mandante, array(3, 4, 5, 6, 7, 10))) {
    $html_barcode = $html_barcode . "<tr>";
    $html_barcode = $html_barcode . "<td align='center' valign='top'>";
    $html_barcode = $html_barcode . "<font style='padding-left:5px;text-align:center;font-size:14px;'>PERMISO SEGOB 8.S.7.1/DGG/SN/94, OFICIO DE AUTORIZACION No. DGJS/0223/2020 DE FECHA 12 DE MARZO DE 2020</font>";
    $html_barcode = $html_barcode . "</td>";
    $html_barcode = $html_barcode . "</tr>";
}

$html_barcode = $html_barcode . " <tr><td align='center' valign='top'><font style='text-align:center;font-size:20px;font-weight:bold;'>Nota de Retiro</font></td></tr><tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:13px;font-weight:normal;'>Nota No: :&nbsp;&nbsp;" . $CuentaCobro->cuentaId .
    "</font></td></tr><tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:13px;font-weight:normal;'>No. de Cliente:&nbsp;&nbsp;" . $Usuario->usuarioId .
    "</font></td></tr>";

if ($Usuario->paisId == 173) {
    $tipoDoc = $Registro->tipoDoc;
    switch ($tipoDoc) {
        case 'P':
            $tipoDoc = 'Pasaporte';
            break;
        case 'C':
            $tipoDoc = 'DNI';
            break;
        case 'E':
            $tipoDoc = 'Carnet de extranjeria';
            break;
    }

    $html_barcode = $html_barcode . " <tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:13px;font-weight:normal;'>Tipo de Doc: :&nbsp;&nbsp;" . $tipoDoc .
        "</font></td></tr><tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:13px;font-weight:normal;'>Documento:&nbsp;&nbsp;" . $Registro->cedula .
        "</font></td></tr>";
}
$html_barcode = $html_barcode . "  <tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:13px;font-weight:normal;'>Fecha:&nbsp;&nbsp;" . date('Y-m-d H:i:s') .
    "</font></td></tr><tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:13px;font-weight:normal;'>Valor a Retirar:&nbsp;&nbsp;" . number_format($CuentaCobro->valor, '2', ',', '.') .
    "</font></td></tr><tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:13px;font-weight:normal;'>Impuesto:&nbsp;&nbsp;" . $valorImpuesto .
    "</font></td></tr><tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:13px;font-weight:normal;'>Costo:&nbsp;&nbsp;" . $valorPenalidad .
    "</font></td></tr><tr><td align='center' valign='top'><font style='padding-left:5px;text-align:left;font-size:13px;font-weight:normal;'>Valor a entregar:&nbsp;&nbsp;" . number_format($CuentaCobro->valor, '2', ',', '.') .
    "</font></td></tr><tr><td align='center' valign='top'><div style='height:5px;'>&nbsp;</div></td></tr></table>";

/**
 * Traduce ciertos textos en el código de barras dependiendo del idioma del usuario.
 * Si el idioma del usuario es inglés, se reemplazan los textos en español por sus
 * equivalentes en inglés dentro de la variable $html_barcode.
 */
if (strtoupper($Usuario->idioma) == 'EN') {
    $html_barcode = str_replace('Nota de Retiro', 'Withdrawal Note', $html_barcode);
    $html_barcode = str_replace('Nota No', 'Withdraw No', $html_barcode);
    $html_barcode = str_replace('No. de Cliente', 'Client No', $html_barcode);

    $html_barcode = str_replace('Valor a Retirar', 'Amount to Withdraw', $html_barcode);
    $html_barcode = str_replace('Impuesto', 'Tax', $html_barcode);
    $html_barcode = str_replace('Costo', 'Cost', $html_barcode);
    $html_barcode = str_replace('Valor a entregar', 'Amount Final', $html_barcode);
}

$data = [
    'html' => $html_barcode
];

if ($service == 'UserBank') {
    $valorMaximoParaAprobacion = 500;
    if ($Usuario->mandante == 14 && $CuentaCobro->getValor() <= $valorMaximoParaAprobacion) {
        // Establece el estado de la cuenta de cobro como 'A' si el medio de pago es vacío o '0'
        if ($CuentaCobro->getMediopagoId() == '' || $CuentaCobro->getMediopagoId() == '0') $CuentaCobro->estado = 'A';
        else $CuentaCobro->estado = 'P';

        $CuentaCobro->setUsucambioId($_SESSION['usuario2']);
        // Si el id de usuario que cambió es vacío o nulo, se establece como 0
        if ($CuentaCobro->usucambioId == '' || $CuentaCobro->usucambioId == 'null' || $CuentaCobro->usucambioId == null) {
            $CuentaCobro->usucambioId = 0;
        }

        // Si el id del usuario que pagó es vacío o nulo, se establece como 0
        if ($CuentaCobro->usupagoId == '' || $CuentaCobro->usupagoId == 'null' || $CuentaCobro->usupagoId == null) {
            $CuentaCobro->usupagoId = 0;
        }

        // Si el id del usuario que rechaza es vacío o nulo, se establece como 0
        if ($CuentaCobro->usurechazaId == '' || $CuentaCobro->usurechazaId == 'null' || $CuentaCobro->usurechazaId == null) {
            $CuentaCobro->usurechazaId = 0;
        }

        // Si la fecha de cambio es vacía, nula o tiene un valor predeterminado, se establece la fecha actual
        if ($CuentaCobro->fechaCambio == '' || $CuentaCobro->fechaCambio == '0000-00-00 00:00:00' || $CuentaCobro->fechaCambio == null) {
            $CuentaCobro->fechaCambio = date('Y-m-d H:i:s');
        }

        // Si la fecha de acción es vacía, nula o tiene un valor predeterminado, se establece la fecha actual
        if ($CuentaCobro->fechaAccion == '' || $CuentaCobro->fechaAccion == '0000-00-00 00:00:00' || $CuentaCobro->fechaAccion == null) {
            $CuentaCobro->fechaAccion = date('Y-m-d H:i:s');
        }

        // Se actualiza la fecha de cambio a la fecha actual
        $CuentaCobro->fechaCambio = date('Y-m-d H:i:s');
        $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();
        $CuentaCobroMySqlDAO->update($CuentaCobro);
        $CuentaCobroMySqlDAO->getTransaction()->commit();

        try {
            // Crea instancias de Banco, Producto y Proveedor
            $Banco = new Banco($UsuarioBanco->bancoId);
            $Producto = new Producto($Banco->productoPago);
            $Proveedor = new Proveedor($Producto->getProveedorId());

            // Si el proveedor es 'PBROKERSPA', se realiza la operación de cash out
            if ($Proveedor->getAbreviado() == 'PBROKERSPA') {
                $PAYBROKERSSERVICES = new PAYBROKERSSERVICES();
                $PAYBROKERSSERVICES->cashOut($CuentaCobro);
            }
        } catch (Exception $ex) {
            throw new $ex;
        }

        $CuentaCobro->setEstado('S');
        // Establece el id del usuario que pagó como 0
        $CuentaCobro->setUsupagoId(0);

        // Si el id de usuario que cambió es vacío o nulo, se establece como 0
        if ($CuentaCobro->usucambioId == '' || $CuentaCobro->usucambioId == 'null' || $CuentaCobro->usucambioId == null) {
            $CuentaCobro->usucambioId = 0;
        }

        // Si el id del usuario que pagó es vacío o nulo, se establece como 0
        if ($CuentaCobro->usupagoId == '' || $CuentaCobro->usupagoId == 'null' || $CuentaCobro->usupagoId == null) {
            $CuentaCobro->usupagoId = 0;
        }

        if ($CuentaCobro->usurechazaId == '' || $CuentaCobro->usurechazaId == 'null' || $CuentaCobro->usurechazaId == null) {
            $CuentaCobro->usurechazaId = 0;
        }

        if ($CuentaCobro->fechaCambio == '' || $CuentaCobro->fechaCambio == '0000-00-00 00:00:00' || $CuentaCobro->fechaCambio == null) {
            $CuentaCobro->fechaCambio = date('Y-m-d H:i:s');
        }

        if ($CuentaCobro->fechaAccion == '' || $CuentaCobro->fechaAccion == '0000-00-00 00:00:00' || $CuentaCobro->fechaAccion == null) {
            $CuentaCobro->fechaAccion = date('Y-m-d H:i:s');
        }

        /**
         * Creación de una instancia del objeto CuentaCobroMySqlDAO para realizar operaciones en la base de datos.
         * Se actualiza la información de la cuenta de cobro y se confirma la transacción.
         */
        $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();
        $CuentaCobroMySqlDAO->update($CuentaCobro);
        $CuentaCobroMySqlDAO->getTransaction()->commit();
    }

    if ($Usuario->mandante == 14 && $CuentaCobro->getValor() > $valorMaximoParaAprobacion) {
        try {
            $message = "Saque pendente *Usuario:* " . $Usuario->usuarioId . " - *Valor:* " . $Usuario->moneda . " " . $CuentaCobro->getValor();
            exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . $message . "' '#lotosports-virtualsoft' > /dev/null & ");
        } catch (Exception $ex) {
        }
    }
}

if ($service == "UserBank") {
    if ($Usuario->mandante == 17 && $CuentaCobro->getValor() <= 5000) {
        /**
         * Verifica si el mediopagoId está vacío o es igual a "0" y establece el estado de la cuenta de cobro a 'A'.
         */
        if ($CuentaCobro->getMediopagoId() == "" || $CuentaCobro->getMediopagoId() == "0") $CuentaCobro->setEstado('A');
        else $CuentaCobro->setEstado('P');

        $CuentaCobro->setUsucambioId($_SESSION['usuario2']);

        /**
         * Verifica si el usucambioId está vacío o nulo y lo establece a 0 en caso afirmativo.
         */
        if ($CuentaCobro->usucambioId == '' || $CuentaCobro->usucambioId == 'null' || $CuentaCobro->usucambioId == null) {
            $CuentaCobro->usucambioId = 0;
        }

        /**
         * Verifica si el usupagoId está vacío o nulo y lo establece a 0 en caso afirmativo.
         */
        if ($CuentaCobro->usupagoId == '' || $CuentaCobro->usupagoId == 'null' || $CuentaCobro->usupagoId == null) {
            $CuentaCobro->usupagoId = 0;
        }

        /**
         * Verifica si el usurechazaId está vacío o nulo y lo establece a 0 en caso afirmativo.
         */
        if ($CuentaCobro->usurechazaId == '' || $CuentaCobro->usurechazaId == 'null' || $CuentaCobro->usurechazaId == null) {
            $CuentaCobro->usurechazaId = 0;
        }

        /**
         * Verifica si la fecha de cambio está vacía, es igual a '0000-00-00 00:00:00' o es nula,
         * y establece la fecha actual en caso afirmativo.
         */
        if ($CuentaCobro->fechaCambio == '' || $CuentaCobro->fechaCambio == '0000-00-00 00:00:00' || $CuentaCobro->fechaCambio == null) {
            $CuentaCobro->fechaCambio = date('Y-m-d H:i:s');
        }

        /**
         * Verifica si la fecha de acción está vacía, es igual a '0000-00-00 00:00:00' o es nula,
         * y establece la fecha actual en caso afirmativo.
         */
        if ($CuentaCobro->fechaAccion == '' || $CuentaCobro->fechaAccion == '0000-00-00 00:00:00' || $CuentaCobro->fechaAccion == null) {
            $CuentaCobro->fechaAccion = date('Y-m-d H:i:s');
        }

        /**
         * Establece la fecha de cambio a la fecha actual.
         */
        $CuentaCobro->fechaCambio = date('Y-m-d H:i:s');
        $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();
        $CuentaCobroMySqlDAO->update($CuentaCobro);
        $CuentaCobroMySqlDAO->getTransaction()->commit();

        try {
            /**
             * Crea una nueva instancia de Banco, Producto y Proveedor a partir de los datos del usuario.
             */
            $Banco = new Banco($UsuarioBanco->bancoId);
            $Producto = new Producto($Banco->productoPago);
            $Proveedor = new Proveedor($Producto->getProveedorId());

            /**
             * Si el proveedor tiene la abreviatura 'PBROKERSPA', se realiza un cash out con el servicio PAYBROKERSSERVICES.
             */
            if ($Proveedor->getAbreviado() == 'PBROKERSPA') {
                $PAYBROKERSSERVICES = new PAYBROKERSSERVICES();
                $PAYBROKERSSERVICES->cashOut($CuentaCobro);
            }
        } catch (Exception $ex) {
            throw new $ex;
        }

        /**
         * Establece el estado de la cuenta de cobro a 'S' y el usupagoId a 0.
         */
        $CuentaCobro->setEstado('S');
        $CuentaCobro->setUsupagoId(0);

        /**
         * Verifica si el usucambioId está vacío o nulo y lo establece a 0 en caso afirmativo.
         */
        if ($CuentaCobro->usucambioId == '' || $CuentaCobro->usucambioId == 'null' || $CuentaCobro->usucambioId == null) {
            $CuentaCobro->usucambioId = 0;
        }

        /**
         * Verifica y asigna valores por defecto a los campos usupagoId y usurechazaId en la
         * instancia de CuentaCobro si estos están vacíos o nulos.
         */
        if ($CuentaCobro->usupagoId == '' || $CuentaCobro->usupagoId == 'null' || $CuentaCobro->usupagoId == null) {
            $CuentaCobro->usupagoId = 0;
        }

        /**
         * Verifica y asigna un valor por defecto a usurechazaId en la instancia de CuentaCobro
         * si este está vacío o nulo.
         */
        if ($CuentaCobro->usurechazaId == '' || $CuentaCobro->usurechazaId == 'null' || $CuentaCobro->usurechazaId == null) {
            $CuentaCobro->usurechazaId = 0;
        }

        /**
         * Verifica y asigna la fecha y hora actual a fechaCambio en la instancia de
         * CuentaCobro si este campo está vacío, tiene valor por defecto o es nulo.
         */
        if ($CuentaCobro->fechaCambio == '' || $CuentaCobro->fechaCambio == '0000-00-00 00:00:00' || $CuentaCobro->fechaCambio == null) {
            $CuentaCobro->fechaCambio = date('Y-m-d H:i:s');
        }

        /**
         * Verifica y asigna la fecha y hora actual a fechaAccion en la instancia de
         * CuentaCobro si este campo está vacío, tiene valor por defecto o es nulo.
         */
        if ($CuentaCobro->fechaAccion == '' || $CuentaCobro->fechaAccion == '0000-00-00 00:00:00' || $CuentaCobro->fechaAccion == null) {
            $CuentaCobro->fechaAccion = date('Y-m-d H:i:s');
        }

        $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();
        $CuentaCobroMySqlDAO->update($CuentaCobro);
        $CuentaCobroMySqlDAO->getTransaction()->commit();
    }
}

if ($Usuario->mandante == 0 && $Usuario->paisId == 173) {
    $maximoCantidadSolicitudesActivas = 1;
    $MaxRows = 1;
    $OrderedItem = 1;
    $SkeepRows = 0;

    $rules = [];

    // Agrega una regla para filtrar por el estado de la cuenta de cobro
    array_push($rules, ['field' => 'cuenta_cobro.estado', 'data' => "'A','I','P','S'", 'op' => 'in']);
    array_push($rules, ['field' => 'cuenta_cobro.usuario_id', 'data' => $UsuarioMandante->usuarioMandante, 'op' => 'eq']);
    array_push($rules, ['field' => 'cuenta_cobro.fecha_crea', 'data' => date('Y-m-d'), 'op' => 'bw']);

    // Crea el filtro con las reglas definidas utilizando una operación de grupo AND
    $filtro = array('rules' => $rules, 'groupOp' => 'AND');
    $jsonfiltro = json_encode($filtro);

    // Obtiene las cuentas de cobro personalizadas basadas en el filtro y parámetros definidos
    $cuentas = (string)$CuentaCobro->getCuentasCobroCustom('sum(cuenta_cobro.valor) sum, count(cuenta_cobro.cuenta_id) cant ', 'cuenta_cobro.cuenta_id', 'asc', $SkeepRows, $MaxRows, $jsonfiltro, true, 'cuenta_cobro.usuario_id');
    $cuentas = json_decode($cuentas);

    // Almacena la suma del valor de las cuentas de cobro en la variable $sum
    $sum = $cuentas->data[0]->{'.sum'};
    $cant = $cuentas->count[0]->{'.count'};

    if ((floatval($sum)) >= 8000) {
        $MaxRows = 1;
        $OrderedItem = 1;
        $SkeepRows = 0;

        $rules = []; // Reglas de filtrado

        // Agrega reglas para la consulta de recargas
        array_push($rules, ['field' => 'usuario_recarga.usuario_id', 'data' => $UsuarioMandante->usuarioMandante, 'op' => 'eq']);
        array_push($rules, ['field' => 'usuario_recarga.fecha_crea', 'data' => date('Y-m-d'), 'op' => 'bw']);

        $filtro = array('rules' => $rules, 'groupOp' => 'AND');
        $jsonfiltro = json_encode($filtro);

        $UsuarioRecarga = new UsuarioRecarga(); // Instancia de UsuarioRecarga

        // Obtiene las recargas personalizadas basadas en el filtro
        $recargas = (string)$UsuarioRecarga->getUsuarioRecargasCustom('sum(usuario_recarga.valor) sum, count(usuario_recarga.recarga_id) cant ', 'usuario_recarga.recarga_id', 'asc', $SkeepRows, $MaxRows, $jsonfiltro, true, 'usuario_recarga.usuario_id');
        $recargas = json_decode($recargas);

        $sumRecargas = $recargas->data[0]->{'.sum'};
        $cantRecargas = $recargas->count[0]->{'.count'};
        $destinatarios = 'oficialdecumplimiento@doradobet.com';
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        try {
            // Se crea una instancia del mandante y otros elementos necesarios
            $Mandante = new Mandante($Usuario->mandante);
            $clasificador = new Clasificador('', 'TEMPALERTARETIR');
            $template = new Template('', $Mandante->mandante, $clasificador->getClasificadorId(), $Usuario->paisId, $Usuario->idioma);
            $mensaje_txt = $template->templateHtml;

            // Reemplaza valores en el mensaje
            if ($sumRecargas == '') $sumRecargas = '0'; // Asigna 0 si no hay recargas
            if ($cantRecargas == '') $cantRecargas = '0'; // Asigna 0 si no hay recargas

            // Reemplazo de los marcadores en el mensaje
            $mensaje_txt = str_replace('#userid#', $Usuario->usuarioId, $mensaje_txt);
            $mensaje_txt = str_replace('#name#', $Usuario->nombre, $mensaje_txt);
            $mensaje_txt = str_replace('#partner#', $Mandante->descripcion, $mensaje_txt);
            $mensaje_txt = str_replace('#email#', $Usuario->login, $mensaje_txt);
            $mensaje_txt = str_replace('#amountWithdrawals#', $sum, $mensaje_txt);
            $mensaje_txt = str_replace('#cantWithdrawals#', $cant, $mensaje_txt);
            $mensaje_txt = str_replace('#amountDeposits#', $sumRecargas, $mensaje_txt);
            $mensaje_txt = str_replace('#cantDeposits#', $cantRecargas, $mensaje_txt);

            $ConfigurationEnvironment = new ConfigurationEnvironment();

            // Envía el correo con el mensaje personalizado
            $envio = $ConfigurationEnvironment->EnviarCorreoVersion3($destinatarios, '', $Mandante->descripcion, 'Alerta Retiros Usuario ' . $Usuario->usuarioId, '', 'Alerta Retiros Usuario ' . $Usuario->usuarioId, $mensaje_txt, '', '', '', $Usuario->mandante);
        } catch (Exception $ex) {
        }
    }
}

$response = [
    'code' => 0,
    'data' => [
        'result' => 0,
        'details' => [
            'method' => $method,
            'status_message' => $status_message,
            'status_messagePdf' => $status_messagePDF,
            'status_messageHTML' => $status_messageHTML,
            'data' => [
                'WithdrawId' => $CuentaCobro->cuentaId,
                'service' => $service,
                'UserId' => $Usuario->usuarioId,
                'Name' => $Usuario->nombre,
                'date_time' => $CuentaCobro->fechaCrea,
                'Key' => $queryData['.clave'],
                'Amount' => $CuentaCobro->valor
            ]
        ],
    ],
    'rid' => $json->rid,
];
?>