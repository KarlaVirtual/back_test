<?php

/**
 * Esta clase proporciona métodos para enviar mensajes a través de diferentes APIs de mensajería.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-18
 */

namespace Backend\integrations\mensajeria;

use Backend\dto\Mandante;
use Backend\dto\Pais;
use Backend\dto\Proveedor;
use Backend\dto\Subproveedor;
use Backend\dto\SubproveedorMandantePais;
use Backend\dto\TransaccionApi;
use Backend\dto\Usuario;
use Backend\dto\Registro;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioMensaje;
use Backend\dto\UsuarioToken;
use \CurlWrapper;

/**
 * Clase Intico.
 *
 * Esta clase proporciona métodos para enviar mensajes a través de diferentes APIs de mensajería.
 * Incluye funcionalidades para enviar mensajes individuales, masivos y consultar el estado de los mensajes.
 */
class Intico
{
    /**
     * Constructor de la clase Intico.
     */
    public function __construct()
    {
    }

    /**
     * Envía un mensaje a un número de teléfono específico.
     *
     * @param string         $phone          Número de teléfono al que se enviará el mensaje.
     * @param string         $message        Contenido del mensaje a enviar.
     * @param UsuarioMensaje $UsuarioMensaje Objeto que contiene información del mensaje y usuario.
     *
     * @return void
     * @throws Exception Si ocurre un error al procesar la solicitud o al decodificar la respuesta.
     */
    public function sendMessage($phone, $message, UsuarioMensaje $UsuarioMensaje)
    {
        $UsuarioMandante = new UsuarioMandante($UsuarioMensaje->getUsutoId());
        $Proveedor = new Proveedor("", "INTICO");
        $Subproveedor = new Subproveedor("", "INTICO");
        $SubproveedorMandantePais = new SubproveedorMandantePais(
            "",
            $Subproveedor->subproveedorId,
            $UsuarioMandante->mandante,
            $UsuarioMandante->paisId
        );
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());
        $Pais = new Pais($UsuarioMandante->paisId);

        $Usuario = $Credentials->USUARIO_UNO_A_UNO;
        $Password = $Credentials->PASSWORD_UNO_A_UNO;

        if ($UsuarioMandante->mandante == 8) {
            if (strlen((string)$phone) == 9) {
                $phone = ltrim($phone, '0');
            }
        }

        if ($UsuarioMandante->mandante == 0 && $UsuarioMandante->paisId == "173") {
            $arrayReq = array();

            $arrayReq['usuario'] = $Usuario;
            $arrayReq['password'] = $Password;
            $arrayReq['celular'] = $Pais->prefijoCelular . $phone;
            $arrayReq['mensaje'] = $message;
            $arrayReq['senderId'] = $UsuarioMensaje->getUsumensajeId();

            $urlService = $Credentials->URL_SERVICE_UNO_A_UNO;

            if ($UsuarioMandante->mandante == 0 && $UsuarioMandante->paisId == "173") {
                $arrayReq['celular'] = $phone;
                $urlService = $Credentials->URL_SERVICE_UNO_A_UNO;
            }

            $request = json_encode($arrayReq);
            syslog(LOG_WARNING, "INTICODATA : " . $request);

            $header = array("Content-Type: application/json");

            $curl = new CurlWrapper($urlService);

            $curl->setOptionsArray(array(
                CURLOPT_URL => $urlService,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => 1,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_POSTFIELDS => $request,
                CURLOPT_HTTPHEADER => $header
            ),
            );

            $result = $curl->execute();

            syslog(LOG_WARNING, "INTICORESPONSE : " . $result);
            $UsuarioMensaje->setValor1($request . $result);

            $result = json_decode($result);

            $UsuarioMensaje->setExternoId($result->codigo);
            $UsuarioMensaje->setProveedorId($Proveedor->getProveedorId());
        } else {
            $Pais = new Pais($UsuarioMandante->paisId);

            $Usuario = $Credentials->USUARIO_UNO_A_UNO;
            $Password = $Credentials->PASSWORD_UNO_A_UNO;

            $arrayReq = array();

            $arrayReq['usuario'] = $Usuario;
            $arrayReq['password'] = $Password;
            $arrayReq['celular'] = $Pais->prefijoCelular . $phone;
            $arrayReq['mensaje'] = $message;
            $arrayReq['senderId'] = $UsuarioMensaje->getUsumensajeId();

            $request = json_encode($arrayReq);
            syslog(LOG_WARNING, "INTICODATA : " . $request);

            $urlService = $Credentials->URL_SERVICE_UNO_A_UNO;

            if ($UsuarioMandante->mandante == 0 && $UsuarioMandante->paisId == "173") {
                $urlService = $Credentials->URL_SERVICE_UNO_A_UNO;
            }

            $header = array("Content-Type: application/x-www-form-urlencoded");

            $curl = new CurlWrapper($urlService);
            $curl->setOptionsArray(array(
                CURLOPT_URL => $urlService,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => 1,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_POSTFIELDS => http_build_query($arrayReq),
                CURLOPT_HTTPHEADER => $header
            ),
            );

            $result = $curl->execute();

            syslog(LOG_WARNING, "INTICORESPONSE : " . $result);
            $UsuarioMensaje->setValor1($request . $result);

            $result = json_decode($result);

            $UsuarioMensaje->setExternoId($result->codigo);
            $UsuarioMensaje->setProveedorId($Proveedor->getProveedorId());
        }
    }

    /**
     * Verifica si una cadena comienza con un número.
     *
     * @param string $string Cadena a verificar.
     *
     * @return boolean Devuelve true si la cadena comienza con un número, de lo contrario false.
     */
    function startsWithNumber($string)
    {
        return strlen($string) > 0 && ctype_digit(substr($string, 0, 1));
    }

    /**
     * Envía un mensaje con un enlace a múltiples usuarios.
     *
     * @param string $message         Contenido del mensaje a enviar.
     * @param array  $UsuarioMensajes Lista de objetos UsuarioMensaje.
     * @param int    $mandante        Identificador del mandante.
     * @param string $paisId          Opcional Identificador del país.
     *
     * @return void
     * @throws Exception Si ocurre un error al procesar la solicitud o al decodificar la respuesta.
     */
    public function sendMessageLink($message, $UsuarioMensajes, $mandante, $paisId = '')
    {
        $UsuarioMandante = new UsuarioMandante($UsuarioMensajes->getUsutoId());
        $Subproveedor = new Subproveedor("", "INTICO");
        $SubproveedorMandantePais = new SubproveedorMandantePais(
            "",
            $Subproveedor->subproveedorId,
            $UsuarioMandante->mandante,
            $UsuarioMandante->paisId
        );
        $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

        if ($mandante == 8) {
            $arrayReq = array();
            $arrayReq['api_key'] = $Credentials->API_KEY_PPAL_LINK;

            $arrayReq['api_key'] = $Credentials->API_KEY_SECOND_LINK;

            $arrayReq['data'] = array();
            $variables = array();

            foreach ($UsuarioMensajes as $UsuarioMensaje) {
                $phone = $UsuarioMensaje->getValor1();
                if (substr($phone, 0, 1) == '0') {
                    $phone = ltrim($phone, '0');
                }
                if (substr($phone, 0, 1) == '9' && strlen((string)$phone) == 9) {
                    if (strlen((string)$phone) == 9) {
                        // $phone = ltrim($phone, '0');
                        // $phone= str_pad($phone,'','',);
                    }

                    $var = array();
                    $var['correlative'] = $UsuarioMensaje->getUsumensajeId();
                    $var['phone'] = $phone;
                    $var['prefix'] = '593';
                    $var['rut'] = '';
                    $var['var1'] = '';
                    $var['var2'] = '';
                    $var['var3'] = '';
                    $var['var4'] = '';
                    $var['var5'] = '';
                    $var['var6'] = '';
                    $var['var7'] = '';
                    $var['var8'] = '';
                    $var['var9'] = '';
                    $var['var10'] = '';
                    $var['var11'] = '';
                    $var['var12'] = '';
                    $var['var13'] = '';
                    $var['var14'] = '';
                    $var['var15'] = '';
                    $var['var16'] = $UsuarioMensaje->getValor2();
                    array_push($variables, $var);
                } else {
                    if (true) {
                        if (strlen((string)$phone) == 9) {
                        }

                        $var = array();
                        $var['correlative'] = $UsuarioMensaje->getUsumensajeId();
                        $var['phone'] = $phone;
                        $var['prefix'] = '502';
                        $var['rut'] = '';
                        $var['var1'] = '';
                        $var['var2'] = '';
                        $var['var3'] = '';
                        $var['var4'] = '';
                        $var['var5'] = '';
                        $var['var6'] = '';
                        $var['var7'] = '';
                        $var['var8'] = '';
                        $var['var9'] = '';
                        $var['var10'] = '';
                        $var['var11'] = '';
                        $var['var12'] = '';
                        $var['var13'] = '';
                        $var['var14'] = '';
                        $var['var15'] = '';
                        $var['var16'] = $UsuarioMensaje->getValor2();
                        array_push($variables, $var);
                    }
                }
            }

            $arrayReq['data']['variables'] = $variables;

            $arrayReq['data']['message_gnr'] = $message;
            $arrayReq['data']['smslargo'] = 0;
            $arrayReq['data']['userRut'] = 7082;

            $arrayReq['data']['hour'] = date('H:i:s');
            $arrayReq['data']['delivery_date'] = date('d/m/Y');
            $arrayReq['data']['delivery_type'] = '0';
            $arrayReq['data']['user'] = array(
                'user_id' => 7082
            );

            $request = json_encode($arrayReq);

            syslog(LOG_WARNING, "INTICODATABULK : " . $request);

            $header = array("Content-Type: application/x-www-form-urlencoded");

            $curl = new CurlWrapper($Credentials->URL_SERVICE_LINK);
            $curl->setOptionsArray(array(
                CURLOPT_URL => $Credentials->URL_SERVICE_LINK,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => 1,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_POSTFIELDS => $request,
                CURLOPT_HTTPHEADER => $header
            ),
            );
            $result = $curl->execute();

            $result = json_decode($result);
        } else {
            $arrayReq = array();
            $arrayReq['api_key'] = $Credentials->API_KEY_PPAL_LINK;

            $arrayReq['api_key'] = $Credentials->API_KEY_SECOND_LINK;

            $userIdIntico = $Credentials->USUARIO_API_LINK;

            $arrayReq['data'] = array();

            $variables = array();


            foreach ($UsuarioMensajes as $UsuarioMensaje) {
                $phone = $UsuarioMensaje->getValor1();
                if (substr($phone, 0, 1) == '0') {
                    $phone = ltrim($phone, '0');
                }
                if (substr($phone, 0, 1) == '9' && strlen((string)$phone) == 9) {
                    if (strlen((string)$phone) == 9) {
                        // $phone = ltrim($phone, '0');
                        // $phone= str_pad($phone,'','',);
                    }

                    $var = array();
                    $var['correlative'] = $UsuarioMensaje->getUsumensajeId();
                    $var['phone'] = $phone;
                    $var['prefix'] = '593';
                    $var['rut'] = '';
                    $var['var1'] = '';
                    $var['var2'] = '';
                    $var['var3'] = '';
                    $var['var4'] = '';
                    $var['var5'] = '';
                    $var['var6'] = '';
                    $var['var7'] = '';
                    $var['var8'] = '';
                    $var['var9'] = '';
                    $var['var10'] = '';
                    $var['var11'] = '';
                    $var['var12'] = '';
                    $var['var13'] = '';
                    $var['var14'] = '';
                    $var['var15'] = '';
                    $var['var16'] = $UsuarioMensaje->getValor2();
                    array_push($variables, $var);
                } else {
                    if (strlen((string)$phone) == 9) {
                    }

                    $var = array();
                    $var['correlative'] = $UsuarioMensaje->getUsumensajeId();
                    $var['phone'] = $phone;
                    $var['prefix'] = '502';
                    $var['rut'] = '';
                    $var['var1'] = '';
                    $var['var2'] = '';
                    $var['var3'] = '';
                    $var['var4'] = '';
                    $var['var5'] = '';
                    $var['var6'] = '';
                    $var['var7'] = '';
                    $var['var8'] = '';
                    $var['var9'] = '';
                    $var['var10'] = '';
                    $var['var11'] = '';
                    $var['var12'] = '';
                    $var['var13'] = '';
                    $var['var14'] = '';
                    $var['var15'] = '';
                    $var['var16'] = $UsuarioMensaje->getValor2();
                    array_push($variables, $var);
                }
            }

            $arrayReq['data']['variables'] = $variables;

            $arrayReq['data']['message_gnr'] = $message;
            $arrayReq['data']['smslargo'] = 0;

            $arrayReq['data']['hour'] = date('H:i:s');
            $arrayReq['data']['delivery_date'] = date('d/m/Y');
            $arrayReq['data']['delivery_type'] = '0';
            $arrayReq['data']['prefix'] = '0';
            $arrayReq['data']['user'] = array(
                'userRut' => $userIdIntico
            );

            $request = json_encode($arrayReq);

            syslog(LOG_WARNING, "INTICODATABULK : " . $request);

            $header = array(
                "Content-Type: application/x-www-form-urlencoded",
                "api_key:" . $arrayReq['api_key']
            );

            $curl = new CurlWrapper($Credentials->URL_SERVICE_LINK);
            $curl->setOptionsArray(array(
                CURLOPT_URL => $Credentials->URL_SERVICE_LINK,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => 1,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_POSTFIELDS => $request,
                CURLOPT_HTTPHEADER => $header
            ),
            );
            $result = $curl->execute();

            syslog(LOG_WARNING, "ININTICORESPONSEBUTICORESPONSEBULK : " . $result);

            $result = json_decode($result);
        }
    }

    /**
     * Envía mensajes masivos utilizando la API Bulk.
     *
     * @param string $phone           Número de teléfono del destinatario.
     * @param string $messages        Contenido del mensaje a enviar.
     * @param array  $UsuarioMensajes Lista de objetos UsuarioMensaje.
     *
     * @return object Respuesta de la API en formato JSON.
     * @throws Exception Si ocurre un error al procesar la solicitud o al decodificar la respuesta.
     */
    public function EnviarMensajeMasivoApiBulk($phone, $messages, $UsuarioMensajes)
    {
        $Subproveedor = new Subproveedor("", "INTICO");

        $variables = [];

        foreach ($UsuarioMensajes as $UsuarioMensaje) {
            $UsuarioMandante = new UsuarioMandante($UsuarioMensaje->getUsutoId());

            $Registro = new Registro('', $UsuarioMandante->usuarioMandante);
            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
            $Pais = new Pais($Usuario->paisId);

            $phone = $Registro->celular;

            // Valida un número telefónico según las reglas de cada país.
            $ResponseValidate = $this->validatePhoneNumber($phone, $Pais->iso);

            if ($ResponseValidate) {
                if ($variables[$Subproveedor->subproveedorId . '##' . $UsuarioMandante->mandante . '##' . $UsuarioMandante->paisId] == null) {
                    $variables[$Subproveedor->subproveedorId . '##' . $UsuarioMandante->mandante . '##' . $UsuarioMandante->paisId] = array(
                        'credentials' => array(),
                        'data' => array(),
                        'CredentialsObj' => ''
                    );
                    $SubproveedorMandantePais = new SubproveedorMandantePais(
                        "",
                        $Subproveedor->subproveedorId,
                        $UsuarioMandante->mandante,
                        $UsuarioMandante->paisId
                    );
                    $Credentials = json_decode($SubproveedorMandantePais->getCredentials());

                    $variables[$Subproveedor->subproveedorId . '##' . $UsuarioMandante->mandante . '##' . $UsuarioMandante->paisId]['credentials'] = [
                        'api_key' => $Credentials->KEY_API_BULK,
                        'data' => [
                            'hour' => date('H:i:s'),
                            'delivery_date' => date('d/m/Y'),
                            'delivery_type' => '0',
                            'smslargo' => 0,

                            'user' => array(
                                'userRut' => $Credentials->USUARIO_API_BULK

                            )
                        ]
                    ];
                    $variables[$Subproveedor->subproveedorId . '##' . $UsuarioMandante->mandante . '##' . $UsuarioMandante->paisId]['CredentialsObj'] = $Credentials;
                }

                $prefix = $Pais->prefijoCelular;

                // Ajustar el número de teléfono según las reglas del mandante
                if ($UsuarioMandante->mandante == 8) {
                    if (substr($phone, 0, 1) === '0') {
                        $phone = ltrim($phone, '0');
                    }
                    if (substr($phone, 0, 1) === '9' && strlen((string)$phone) === 9) {
                        // Regla adicional si es necesario (comentada como referencia)
                    }
                }

                array_push(
                    $variables[$Subproveedor->subproveedorId . '##' . $UsuarioMandante->mandante . '##' . $UsuarioMandante->paisId]['data'],
                    array(
                        'correlative' => intval($UsuarioMensaje->getUsumensajeId()),
                        'phone' => $phone,
                        'message' => $messages,
                        "prefix" => $prefix
                    )
                );
            }
        }

        foreach ($variables as $variable) {
            // Dividir mensajes en paquetes de 30,000
            $tamanoPaquete = 30000;
            $totalMensajes = count($variable['data']);
            $paquetesEnviados = 0;

            for ($i = 0; $i < $totalMensajes; $i += $tamanoPaquete) {
                // Extraer un paquete de 30,000 mensajes
                $paquete = array_slice($variable['data'], $i, $tamanoPaquete);
                $arrayReq = [
                    'data' => [
                        'messages' => $paquete,
                        'hour' => date('H:i:s'),
                        'delivery_date' => date('d/m/Y'),
                        'delivery_type' => 0,
                        'smslargo' => 0,
                        'user' => array(
                            'userRut' => $variable['CredentialsObj']->USUARIO_API_BULK
                        )
                    ]
                ];

                // Convertir el paquete a JSON
                $request = json_encode($arrayReq);

                // Enviar el paquete a la API
                $Response = $this->connectionApiBulk($request, $variable['CredentialsObj']);

                $paquetesEnviados++;
                syslog(LOG_WARNING, "Paquete #{$paquetesEnviados} enviado con éxito: " . json_encode($paquete));
            }

            $data = [
                "success" => true,
                "response" => $Response ?? null
            ];
        }


        return json_decode(json_encode($data));
    }

    /**
     * Valida un número de teléfono según las reglas de un país.
     *
     * @param string $numero Número de teléfono a validar.
     * @param string $iso    Código ISO del país.
     *
     * @return boolean Devuelve true si el número es válido, de lo contrario false.
     */
    public function validatePhoneNumber($numero, $iso)
    {
        // Definir reglas de validación por país
        $reglas = [
            'CR' => ['longitud' => 8, 'inicia' => ['5', '6', '7', '8']],  // Costa Rica (506)
            'EC' => ['longitud' => 9, 'inicia' => ['9']],                 // Ecuador (593)
            'SV' => ['longitud' => 8, 'inicia' => ['6', '7']],            // El Salvador (503)
            'GT' => ['longitud' => 8, 'inicia' => ['3', '4', '5']],       // Guatemala (502)
            'NI' => ['longitud' => 8, 'inicia' => ['5', '7', '8']],       // Nicaragua (505)
            'PE' => ['longitud' => 9, 'inicia' => ['9']],                 // Perú (51)
            'CL' => ['longitud' => 9, 'inicia' => ['9']],                 // Chile (56)
            'HN' => ['longitud' => 8, 'inicia' => ['3', '7', '8', '9']],  // Honduras (504)
            'BR' => ['longitud' => 11, 'inicia' => ['1', '2', '3', '4', '5', '6', '7', '8', '9']] // Brasil (55)
        ];

        // Convertir ISO a mayúsculas por seguridad
        $iso = strtoupper($iso);

        // Verificar si el país está en la lista
        if ( ! isset($reglas[$iso])) {
            return false;
        }

        // Obtener la regla del país
        $regla = $reglas[$iso];

        // Validar longitud
        if (strlen($numero) !== $regla['longitud']) {
            return false;
        }

        // Validar primer dígito
        if ( ! in_array($numero[0], $regla['inicia'])) {
            return false;
        }

        return true;
    }

    /**
     * Realiza la conexión con la API Bulk para enviar mensajes.
     *
     * @param string $request     Solicitud en formato JSON.
     * @param object $Credentials Credenciales necesarias para la conexión.
     *
     * @return object Respuesta de la API en formato JSON.
     * @throws Exception Si ocurre un error al procesar la solicitud o al decodificar la respuesta.
     */
    public function connectionApiBulk($request, $Credentials)
    {
        $header = [
            "Content-Type: application/json",
            "api_key: " . $Credentials->KEY_API_BULK
        ];

        $curl = new CurlWrapper($Credentials->URL_SERVICE_API_BULK);

        $curl->setOptionsArray([
            CURLOPT_URL => $Credentials->URL_SERVICE_API_BULK,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $request,
            CURLOPT_HTTPHEADER => $header
        ]);

        $result = $curl->execute();

        syslog(LOG_WARNING, "INTICORESPONSEAPIBULK : " . $result);

        return json_decode($result);
    }

    /**
     * Consulta el estado de un mensaje enviado.
     *
     * @param string $fileRegister Identificador del archivo de registro.
     * @param int    $mandante     Identificador del mandante.
     * @param string $status       Estado del mensaje a consultar.
     *
     * @return object Respuesta de la API en formato JSON.
     * @throws Exception Si ocurre un error al procesar la solicitud o al decodificar la respuesta.
     */
    public function StatusMessage($fileRegister, $mandante, $status)
    {
        $Proveedor = new Proveedor("", "INTICO");

        //$Usuario = new Usuario($usuarioId);

        //$Registro = new Registro('',$usuarioId);
        //$Pais = new Pais($Usuario->paisId);

        $arrayReq = array();
        $arrayReq['api_key'] = 'ZYWKOoPbGyfMeBFQ4crl7CqhDtsaum31nJ2jSxVgXw65HdUEivN9LkTRp0AzI804';

        switch ($mandante) {
            case '2':
                $arrayReq['api_key'] = 'yKn2rBlzA74RpvoOmQfsNJVa0YwG9icPLx1WDIbMFZtT5E6SeUqukh3HdjXCg839';

                break;
            case '33':
                $arrayReq['api_key'] = 'DfMy2EmUxgi4chGjv7FQuOZHRWXYolw8IseapPSdq6Cb5rKVkNBLJTnzA13t9026';

                break;
            case '46':
                $arrayReq['api_key'] = 'ywjh0WOCKqVtm5LzA3E1IalSH2QYx9Rrd4i6NZbMsgT8vk7PcDuGnXUfBepoFJ51';

                break;
            case '60':
                $arrayReq['api_key'] = 'JUdpi5h0nRraz6lXDc4tqkWeHTQ7fN31sVw8EoKPyM9YvujIS2OCLBFmbgGAZx59';

                break;
            case '66':
                $arrayReq['api_key'] = 'q8YthFVikMIxpZsyBXWdAUaKNHCuG5ornP3b9J40jRLmSz62eOcvg71wTlfQDE02';

                break;
            case '94':
                $arrayReq['api_key'] = 'kWd6itZOUIroG0LpSFqBKsN27Dc3uRxyPXmvz1nMlEVCb5JjY4e8h9faQgwTAH32';

                break;
        }

        $arrayReq['data'] = array(
            "file_register" => $fileRegister,
            "PageNumber" => 2,
            "PageSize" => 2,
            "status_confirmation" => $status,
        );


        $request = json_encode($arrayReq);

        syslog(LOG_WARNING, "INTICODATA : " . $request);

        $header = array("Content-Type: application/x-www-form-urlencoded");


        $curl = new CurlWrapper('https://inticosms.com:8193/api/sent_sms_detail');
        $curl->setOptionsArray(array(
            CURLOPT_URL => 'https://inticosms.com:8193/api/sent_sms_detail',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => 1,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_POSTFIELDS => $request,
            CURLOPT_HTTPHEADER => $header
        ),
        );
        $result = $curl->execute();

        syslog(LOG_WARNING, "INTICORESPONSE : " . $result);

        $result = json_decode($result);

        return $result;
    }

    /**
     * Envía un mensaje a múltiples números de teléfono.
     *
     * @param string         $numbers        Números de teléfono separados por comas.
     * @param string         $message        Contenido del mensaje a enviar.
     * @param UsuarioMensaje $UsuarioMensaje Objeto que contiene información del mensaje y usuario.
     *
     * @return void
     * @throws Exception Si ocurre un error al procesar la solicitud o al decodificar la respuesta.
     */
    public function sendMessageWithNumbers($numbers, $message, $UsuarioMensaje)
    {
        $Proveedor = new Proveedor("", "INTICO");

        $request = "?usuario=dorabe56&password=jojxpryb";
        $request = $request . "&senderId=" . $UsuarioMensaje->getUsumensajeId();
        $request = $request . "&celular=" . $numbers;
        $request = $request . "&mensaje=" . $message;
        $request = $request . "&senderId=submit";

        $result = file_get_contents($this->URL . $request);

        $result = json_decode($result);

        $UsuarioMensaje->setExternoId($result->codigo);
        $UsuarioMensaje->setProveedorId($Proveedor->getProveedorId());
    }

}




