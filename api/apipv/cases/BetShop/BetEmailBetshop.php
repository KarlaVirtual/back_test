<?php

use Backend\dto\Usuario;
use Backend\dto\ItTicketEnc;
use Backend\dto\CuentaCobro;
use Backend\dto\UsuarioRecarga;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Mandante;

/**
 * Envía un correo electrónico relacionado con operaciones de apuestas o transacciones basándose en los datos proporcionados en la solicitud.
 *
 * @param string $params JSON codificado que contiene los datos de la solicitud, incluyendo:
 * @param string $params ->Type Tipo de operación ('MakeDeposit', 'PayNoteWithdrawal', 'PayWinningTicket', etc.).
 * @param string $params ->Code Código asociado a la operación (ID de recarga, cobro o ticket).
 * @param string $params ->Email Dirección de correo electrónico del usuario.
 *
 * @return array $response Contiene los datos de la respuesta, incluyendo:
 *                         - HasError (bool): Indica si ocurrió un error.
 *                         - AlertType (string): Tipo de alerta ('Success' en caso de éxito, 'Error' en caso de fallo).
 *                         - AlertMessage (string): Mensaje de alerta.
 *                         - ModelErrors (array): Lista de errores del modelo.
 *
 * @throws Exception Si no existe la recarga asociada al código proporcionado.
 *                   Código: '44', Mensaje: 'No existe la recarga'.
 * @throws Exception Si no existe el cobro asociado al código proporcionado.
 *                   Código: '44', Mensaje: 'No existe el cobro'.
 * @throws Exception Si no existe el ticket asociado al código proporcionado.
 *                   Código: '44', Mensaje: 'No existe el ticket'.
 */

// Asignación de valores del objeto $params a variables locales
$Type = $params->Type;
$Code = $params->Code;
$Email = $params->Email;

if (!empty($Type) && !empty($Email)) {

        // Inicializa una cadena vacía para el título
        $title = '';
        // Inicializa una cadena vacía para el mensaje
        $message = '';
        // Crea una nueva instancia de la clase ConfigurationEnvironment
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        // Crea un nuevo usuario utilizando un correo electrónico y un nombre vacío
        $Usuario = new Usuario('', $Email);
        // Crea un nuevo mandante basado en el mandante del usuario
        $Mandate = new Mandante($Usuario->mandante);

    $partnerName = $Mandate->nombre;
    $currency = $Usuario->moneda;

    switch ($Type) {
        case 'MakeDeposit':
            /*Obtención notificación por depoósito*/
            $UsuarioRecarga = new UsuarioRecarga($Code);
            if (empty($UsuarioRecarga->recargaId)) throw new Exception('No existe la recarga', 44);

            $ID = $UsuarioRecarga->recargaId;
            $value = $UsuarioRecarga->valor;
            $message = "{$partnerName} le informa su deposito por {$currency} {$value} ID del deposito ({$ID})";
            $title = 'Deposito';
            break;
        case 'PayNoteWithdrawal':
            /*Obtención notificación por retiro*/
            $CuentaCobro = new CuentaCobro($Code);
            if (empty($CuentaCobro->cuentaId)) throw new Exception('No existe el cobro', 44);

            $ID = $CuentaCobro->cuentaId;
            $value = $CuentaCobro->valor;
            $paymentDate = $CuentaCobro->fechaPago;
            $message = "{$partenrName} ha pagado exitosamente su notas de retiro por valor {$value} a las {$paymentDate} ID {$ID}";
            $title = 'Pago retiro';
            break;
        case 'PayWinningTicket':
            /*Obtención notificación por pago de ticket*/
            $ItTicketEnc = new ItTicketEnc($Code);
            if (empty($ItTicketEnc->ticketId)) throw new Exception('No existe el ticket', 44);

            $ID = $ItTicketEnc->ticketId;
            $value = $ItTicketEnc->vlrPremio;
            $message = "{$partnerName} le informa pago de su apuesta {$ID} por {$currency} {$value}";
            $title = 'Pago apuesta';
            break;
        default:
            /*Obtención notificación por apuesta*/
            $ItTicketEnc = new ItTicketEnc($Code);
            if (empty($ItTicketEnc->ticketId)) throw new Exception('No existe el ticket', 44);

            $ID = $ItTicketEnc->ticketId;
            $value = $ItTicketEnc->vlrApuesta;
            $pass = $ItTicketEnc->clave;
            $URL = $Mandate->baseUrl . '/deportes';
            $message = "{$partnerName} le informa su apuesta {$ID} por {$currency} {$value} clave: ({$pass}) consulta en {$URL}";
            $title = 'Pago apuesta';
            break;
    }

    /*Envío del correo*/
    $ConfigurationEnvironment->EnviarCorreoVersion2($Email, '', '', $title, '', $title, $message, '', '', '', $Mandate->mandante);

    /*Formato respuesta exitosa*/
    $response['HasError'] = false;
    $response['AlertType'] = 'Succes';
    $response['AlertMessage'] = '';
    $response['ModelErrors'] = [];
} else {
    /*Formato respuesta fallida*/
    $response['HasError'] = true;
    $response['AlertType'] = 'Error';
    $response['AlertMessage'] = '';
    $response['ModelErrors'] = [];
}
