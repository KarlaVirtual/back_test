<?php

    use Backend\dto\Mandante;
    use Backend\dto\Usuario;
    use Backend\dto\UsuarioMandante;
    use Backend\dto\UsuarioRecarga;
    use Backend\dto\CuentaCobro;
    use Backend\dto\ItTicketEnc;
    use Backend\dto\ConfigurationEnvironment;

    /**
     * Genera mensajes de texto para notificar al usuario sobre una operación
     * @param int $json->params->phone  Celular destinatario
     * @param int $json->params->type  Tipo de operación
     * @param int $json->params->site_id  Identificador del sitio
     * @param int $json->params->code   Código asociado a la operación
     *
     * @return array
     *  -code: int Código de respuesta
     *  -rid: int ID de la solicitud
     */

    // Obtiene los parámetros necesarios del JSON
    $phone = $json->params->phone; // Teléfono asociado al usuario
    $type = $json->params->type; // Tipo de operación (ej. depósito, retiro, etc.)
    $site_id = $json->params->site_id; // Identificador del sitio
    $code = $json->params->code; // Código asociado a la operación
    
    // Valida que el tipo y teléfono no estén vacíos, de lo contrario lanza una excepción
    if(empty($type) || empty($phone)) throw new Exception('Error en la solicitud', 10000);

    // Inicializa objetos de las clases Mandante y UsuarioMandante
    $Mandate = new Mandante($site_id);
    $UsuarioMandante = new UsuarioMandante($json->session->usuario);
    $Usuario = new Usuario($UsuarioMandante->usuarioMandante); // Usuario actual

    $message = ''; // Mensaje a enviar
    $partner_name = $Mandate->nombre; // Nombre del socio
    $currency = $Usuario->moneda; // Moneda del usuario
    $value = 0; // Valor de la operación a inicializar

    switch($type) {
        case 'MakeDeposit':
            // Inicializa el objeto UsuarioRecarga para la operación de depósito
            $UsuarioRecarga = new UsuarioRecarga($code);
            $value = $UsuarioRecarga->valor;
            switch($Usuario->idioma) {
                case 'en':
                    $message = "{$partner_name} informs you of your deposit to your account by {$currency} {$value} ID {$code}";
                    break;
                case 'pt';
                    $message = "{$partner_name} informa sobre seu depósito em sua conta por {$currency} {$value} ID {$code}";
                    break;
                default:
                    $message = "{$partner_name} le informa su deposito a su cuenta por {$currency} {$value} ID {$code}";
                    break;
            }
            break;
        case 'PayNoteWithdrawal':
            // Inicializa el objeto CuentaCobro para la operación de retiro
            $CuentaCobro = new CuentaCobro($code);

            $value = $CuentaCobro->valor;
            $payment_date = $CuentaCobro->fechaPago;
            switch($Usuario->idioma) {
                case 'en':
                    $message = "{$partner_name} has successfully paid its withdrawal note for a value of {$value} at {$payment_date}. ID {$code}";
                    break;
                case 'pt';
                    $message = "{$partner_name} pagou com sucesso sua nota de retirada no valor de {$value} a {$payment_date}. ID {$code}";
                    break;
                default:
                    $message = "{$partner_name} a pagado exitosamente su nota de retiro por valor {$value} a las {$payment_date}. ID {$code}";
                    break;
            }
            break;
        case 'PayWinningTicket':
            // Inicializa el objeto ItTicketEnc para la operación de pago de boleto ganador
            $ItTicketEnc = new ItTicketEnc($code);
            $ward = $ItTicketEnc->vlrPremio;
            switch($Usuario->idioma) {
                case 'en':
                    $message = "{$partner_name} informs you of your bet payment {$code} for {$currency} {$ward}";
                    break;
                case 'pt';
                    $message = "{$partner_name} lhe informa sobre o pagamento de sua participação {$code} por {$currency} {$ward}";
                    break;
                default:
                    $message = "{$partner_name} le informa pago de su apuesta {$code} por {$currency} {$ward}";
                    break;
            }
            break;
        default:
            // Maneja el caso por defecto utilizando ItTicketEnc
            $ItTicketEnc = new ItTicketEnc($code);
            $value = $ItTicketEnc->vlrApuesta; // Obtiene el valor de la apuesta
            $pass = $ItTicketEnc->clave; // Obtiene la clave de la apuesta
            $url = $Mandate->baseUrl; // Obtiene la URL base

            // Genera el mensaje según el idioma del usuario
            switch($Usuario->idioma) {
                case 'en':
                    $message = "{$partner_name} informs you of your bet {$code} for {$currency} {$value} key: ({$pass}) Consultation in {$url}deportes";
                    break;
                case 'pt':
                    $message = "{$partner_name} lhe informa sobre sua aposta {$code} por {$currency} {$value} Chave: ({$pass}) Consulta em {$url}deportes";
                    break;
                default:
                    $message = "{$partner_name} le informa su apuesta {$code} por {$currency} {$value} Clave: ({$pass}) Consulta en {$url}deportes";
                    $mensaje_txt = $partner_name . ' le informa: Pronostico ID ' . $ItTicketEnc->ticketId . ' - IB ' . $pass . ' por ' . $Usuario->moneda . ' ' . $ItTicketEnc->vlrApuesta . ', WIN ' . $Usuario->moneda . ' ' . $ItTicketEnc->vlrPremio   . '(Cuota '.($ItTicketEnc->vlrPremio/$ItTicketEnc->vlrApuesta).')';
                    break;
            }
            break;
    }

    $ConfigurationEnvironment = new ConfigurationEnvironment();
    $ConfigurationEnvironment->EnviarMensajeTexto($message, '', $phone, $Mandate->mandante, $UsuarioMandante);

    $response = [];
    $response['code'] = 0;
    $response['rid'] = $json->rid;

    $response['data'] = [];

?>