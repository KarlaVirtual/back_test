<?php

/**
 * Clase que gestiona los servicios de integración con Interbank.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-19
 */


namespace Backend\integrations\payout;

use Exception;
use Backend\dto\Banco;
use Backend\dto\Usuario;
use phpseclib3\Net\SFTP;
use phpseclib3\Net\SSH2;
use Backend\dto\Producto;
use Backend\dto\Registro;
use phpseclib3\Crypt\RSA;
use Backend\dto\UsuarioBanco;
use Backend\dto\TransaccionProducto;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\TransaccionProductoMySqlDAO;

/**
 * Clase que gestiona los servicios de integración con Interbank.
 * Proporciona métodos para realizar pagos, enviar y recibir archivos mediante SFTP.
 */
class INTERBANKSERVICES
{

    /**
     * Puerto SSH para la conexión SFTP.
     *
     * @var integer
     */
    private $ssh_port = 22220;

    /**
     * Puerto SSH para el entorno de desarrollo.
     *
     * @var integer
     */
    private $ssh_port_DEV = 22221;

    /**
     * Puerto SSH para el entorno de producción.
     *
     * @var integer
     */
    private $ssh_port_PROD = 22221;

    /**
     * Dirección del servidor SSH.
     *
     * @var string
     */
    private $ssh_server_fp = '';

    /**
     * Dirección del servidor SSH para el entorno de desarrollo.
     *
     * @var string
     */
    private $ssh_server_fp_DEV = 'sftpempa.interbank.pe';

    /**
     * Dirección del servidor SSH para el entorno de producción.
     *
     * @var string
     */
    private $ssh_server_fp_PROD = 'sftpemp.interbank.pe';

    /**
     * Código del rubro utilizado en las transacciones.
     *
     * @var string
     */
    private $RR = '';

    /**
     * Código del rubro para el entorno de desarrollo.
     *
     * @var string
     */
    private $RR_DEV = '03';

    /**
     * Código del rubro para el entorno de producción.
     *
     * @var string
     */
    private $RR_PROD = '03';

    /**
     * Código de la empresa proporcionado por Interbank.
     *
     * @var string
     */
    private $EEEE = '';

    /**
     * Código de la empresa para el entorno de desarrollo.
     *
     * @var string
     */
    private $EEEE_DEV = 'AAD0';

    /**
     * Código de la empresa para el entorno de producción.
     *
     * @var string
     */
    private $EEEE_PROD = 'FX43';

    /**
     * Usuario de autenticación SSH.
     *
     * @var string
     */
    private $ssh_auth_user = '';

    /**
     * Usuario de autenticación SSH para el entorno de desarrollo.
     *
     * @var string
     */
    private $ssh_auth_user_DEV = 'PPA_03AAD0';

    /**
     * Usuario de autenticación SSH para el entorno de producción.
     *
     * @var string
     */
    private $ssh_auth_user_PROD = 'PPA_03AAD0';

    /**
     * Ruta del archivo de clave pública SSH.
     *
     * @var string
     */
    private $ssh_auth_pub = '';

    /**
     * Ruta del archivo de clave pública SSH para el entorno de desarrollo.
     *
     * @var string
     */
    private $ssh_auth_pub_DEV = __DIR__ . '/../../integrations/payout/interbank/api/LlavepublicaUAT';

    /**
     * Ruta del archivo de clave pública SSH para el entorno de producción.
     *
     * @var string
     */
    private $ssh_auth_pub_PROD = __DIR__ . '/../../imports/interbank/keypublicprod';

    /**
     * Ruta del archivo de clave privada SSH.
     *
     * @var string
     */
    private $ssh_auth_priv = '';

    /**
     * Ruta del archivo de clave privada SSH para el entorno de desarrollo.
     *
     * @var string
     */
    private $ssh_auth_priv_DEV = __DIR__ . '/../../integrations/payout/interbank/api/LlaveprivadaUAT.pem';

    /**
     * Ruta del archivo de clave privada SSH para el entorno de producción.
     *
     * @var string
     */
    private $ssh_auth_priv_PROD = __DIR__ . '/../../imports/interbank/keyprivateprod.pem';

    /**
     * Código del servicio proporcionado por Interbank.
     *
     * @var string
     */
    private $service_code = '';

    /**
     * Código del servicio para el entorno de desarrollo.
     *
     * @var string
     */
    private $service_code_DEV = '01';

    /**
     * Código del servicio para el entorno de producción.
     *
     * @var string
     */
    private $service_code_PROD = '01';


    /**
     * Constructor de la clase.
     * Configura las credenciales y parámetros según el entorno (desarrollo o producción).
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->ssh_auth_priv = $this->ssh_auth_priv_DEV;
            $this->ssh_auth_pub = $this->ssh_auth_pub_DEV;
            $this->ssh_auth_user = $this->ssh_auth_user_DEV;
            $this->ssh_server_fp = $this->ssh_server_fp_DEV;
            $this->ssh_port = $this->ssh_port_DEV;
            $this->RR = $this->RR_DEV;
            $this->EEEE = $this->EEEE_DEV;
            $this->service_code = $this->service_code_DEV;
        } else {
            $this->ssh_auth_priv = $this->ssh_auth_priv_PROD;
            $this->ssh_auth_pub = $this->ssh_auth_pub_PROD;
            $this->ssh_auth_user = $this->ssh_auth_user_PROD;
            $this->ssh_server_fp = $this->ssh_server_fp_PROD;
            $this->ssh_port = $this->ssh_port_PROD;
            $this->RR = $this->RR_PROD;
            $this->EEEE = $this->EEEE_PROD;
            $this->service_code = $this->service_code_PROD;
        }

        // Habilitar logging detallado para diagnóstico
        if (!defined('NET_SSH2_LOGGING')) {
            define('NET_SSH2_LOGGING', SFTP::LOG_COMPLEX);
        }
    }

    public function remove_accents($string)
    {
        if (!preg_match('/[\x80-\xff]/', $string))
            return $string;

        $chars = array(
            // Decompositions for Latin-1 Supplement
            chr(195) . chr(128) => 'A',
            chr(195) . chr(129) => 'A',
            chr(195) . chr(130) => 'A',
            chr(195) . chr(131) => 'A',
            chr(195) . chr(132) => 'A',
            chr(195) . chr(133) => 'A',
            chr(195) . chr(135) => 'C',
            chr(195) . chr(136) => 'E',
            chr(195) . chr(137) => 'E',
            chr(195) . chr(138) => 'E',
            chr(195) . chr(139) => 'E',
            chr(195) . chr(140) => 'I',
            chr(195) . chr(141) => 'I',
            chr(195) . chr(142) => 'I',
            chr(195) . chr(143) => 'I',
            chr(195) . chr(145) => 'N',
            chr(195) . chr(146) => 'O',
            chr(195) . chr(147) => 'O',
            chr(195) . chr(148) => 'O',
            chr(195) . chr(149) => 'O',
            chr(195) . chr(150) => 'O',
            chr(195) . chr(153) => 'U',
            chr(195) . chr(154) => 'U',
            chr(195) . chr(155) => 'U',
            chr(195) . chr(156) => 'U',
            chr(195) . chr(157) => 'Y',
            chr(195) . chr(159) => 's',
            chr(195) . chr(160) => 'a',
            chr(195) . chr(161) => 'a',
            chr(195) . chr(162) => 'a',
            chr(195) . chr(163) => 'a',
            chr(195) . chr(164) => 'a',
            chr(195) . chr(165) => 'a',
            chr(195) . chr(167) => 'c',
            chr(195) . chr(168) => 'e',
            chr(195) . chr(169) => 'e',
            chr(195) . chr(170) => 'e',
            chr(195) . chr(171) => 'e',
            chr(195) . chr(172) => 'i',
            chr(195) . chr(173) => 'i',
            chr(195) . chr(174) => 'i',
            chr(195) . chr(175) => 'i',
            chr(195) . chr(177) => 'n',
            chr(195) . chr(178) => 'o',
            chr(195) . chr(179) => 'o',
            chr(195) . chr(180) => 'o',
            chr(195) . chr(181) => 'o',
            chr(195) . chr(182) => 'o',
            chr(195) . chr(182) => 'o',
            chr(195) . chr(185) => 'u',
            chr(195) . chr(186) => 'u',
            chr(195) . chr(187) => 'u',
            chr(195) . chr(188) => 'u',
            chr(195) . chr(189) => 'y',
            chr(195) . chr(191) => 'y',
            // Decompositions for Latin Extended-A
            chr(196) . chr(128) => 'A',
            chr(196) . chr(129) => 'a',
            chr(196) . chr(130) => 'A',
            chr(196) . chr(131) => 'a',
            chr(196) . chr(132) => 'A',
            chr(196) . chr(133) => 'a',
            chr(196) . chr(134) => 'C',
            chr(196) . chr(135) => 'c',
            chr(196) . chr(136) => 'C',
            chr(196) . chr(137) => 'c',
            chr(196) . chr(138) => 'C',
            chr(196) . chr(139) => 'c',
            chr(196) . chr(140) => 'C',
            chr(196) . chr(141) => 'c',
            chr(196) . chr(142) => 'D',
            chr(196) . chr(143) => 'd',
            chr(196) . chr(144) => 'D',
            chr(196) . chr(145) => 'd',
            chr(196) . chr(146) => 'E',
            chr(196) . chr(147) => 'e',
            chr(196) . chr(148) => 'E',
            chr(196) . chr(149) => 'e',
            chr(196) . chr(150) => 'E',
            chr(196) . chr(151) => 'e',
            chr(196) . chr(152) => 'E',
            chr(196) . chr(153) => 'e',
            chr(196) . chr(154) => 'E',
            chr(196) . chr(155) => 'e',
            chr(196) . chr(156) => 'G',
            chr(196) . chr(157) => 'g',
            chr(196) . chr(158) => 'G',
            chr(196) . chr(159) => 'g',
            chr(196) . chr(160) => 'G',
            chr(196) . chr(161) => 'g',
            chr(196) . chr(162) => 'G',
            chr(196) . chr(163) => 'g',
            chr(196) . chr(164) => 'H',
            chr(196) . chr(165) => 'h',
            chr(196) . chr(166) => 'H',
            chr(196) . chr(167) => 'h',
            chr(196) . chr(168) => 'I',
            chr(196) . chr(169) => 'i',
            chr(196) . chr(170) => 'I',
            chr(196) . chr(171) => 'i',
            chr(196) . chr(172) => 'I',
            chr(196) . chr(173) => 'i',
            chr(196) . chr(174) => 'I',
            chr(196) . chr(175) => 'i',
            chr(196) . chr(176) => 'I',
            chr(196) . chr(177) => 'i',
            chr(196) . chr(178) => 'IJ',
            chr(196) . chr(179) => 'ij',
            chr(196) . chr(180) => 'J',
            chr(196) . chr(181) => 'j',
            chr(196) . chr(182) => 'K',
            chr(196) . chr(183) => 'k',
            chr(196) . chr(184) => 'k',
            chr(196) . chr(185) => 'L',
            chr(196) . chr(186) => 'l',
            chr(196) . chr(187) => 'L',
            chr(196) . chr(188) => 'l',
            chr(196) . chr(189) => 'L',
            chr(196) . chr(190) => 'l',
            chr(196) . chr(191) => 'L',
            chr(197) . chr(128) => 'l',
            chr(197) . chr(129) => 'L',
            chr(197) . chr(130) => 'l',
            chr(197) . chr(131) => 'N',
            chr(197) . chr(132) => 'n',
            chr(197) . chr(133) => 'N',
            chr(197) . chr(134) => 'n',
            chr(197) . chr(135) => 'N',
            chr(197) . chr(136) => 'n',
            chr(197) . chr(137) => 'N',
            chr(197) . chr(138) => 'n',
            chr(197) . chr(139) => 'N',
            chr(197) . chr(140) => 'O',
            chr(197) . chr(141) => 'o',
            chr(197) . chr(142) => 'O',
            chr(197) . chr(143) => 'o',
            chr(197) . chr(144) => 'O',
            chr(197) . chr(145) => 'o',
            chr(197) . chr(146) => 'OE',
            chr(197) . chr(147) => 'oe',
            chr(197) . chr(148) => 'R',
            chr(197) . chr(149) => 'r',
            chr(197) . chr(150) => 'R',
            chr(197) . chr(151) => 'r',
            chr(197) . chr(152) => 'R',
            chr(197) . chr(153) => 'r',
            chr(197) . chr(154) => 'S',
            chr(197) . chr(155) => 's',
            chr(197) . chr(156) => 'S',
            chr(197) . chr(157) => 's',
            chr(197) . chr(158) => 'S',
            chr(197) . chr(159) => 's',
            chr(197) . chr(160) => 'S',
            chr(197) . chr(161) => 's',
            chr(197) . chr(162) => 'T',
            chr(197) . chr(163) => 't',
            chr(197) . chr(164) => 'T',
            chr(197) . chr(165) => 't',
            chr(197) . chr(166) => 'T',
            chr(197) . chr(167) => 't',
            chr(197) . chr(168) => 'U',
            chr(197) . chr(169) => 'u',
            chr(197) . chr(170) => 'U',
            chr(197) . chr(171) => 'u',
            chr(197) . chr(172) => 'U',
            chr(197) . chr(173) => 'u',
            chr(197) . chr(174) => 'U',
            chr(197) . chr(175) => 'u',
            chr(197) . chr(176) => 'U',
            chr(197) . chr(177) => 'u',
            chr(197) . chr(178) => 'U',
            chr(197) . chr(179) => 'u',
            chr(197) . chr(180) => 'W',
            chr(197) . chr(181) => 'w',
            chr(197) . chr(182) => 'Y',
            chr(197) . chr(183) => 'y',
            chr(197) . chr(184) => 'Y',
            chr(197) . chr(185) => 'Z',
            chr(197) . chr(186) => 'z',
            chr(197) . chr(187) => 'Z',
            chr(197) . chr(188) => 'z',
            chr(197) . chr(189) => 'Z',
            chr(197) . chr(190) => 'z',
            chr(197) . chr(191) => 's'
        );

        $string = strtr($string, $chars);
        return $string;
    }

    /**
     * Realiza un pago masivo a través de Interbank.
     *
     * @param array $CuentasCobro Lista de cuentas a procesar.
     * @param int   $ProductoId   ID del producto asociado al pago.
     *
     * @return void
     * @throws Exception Si ocurre un error en la transferencia o en los datos.
     */
    public function cashOutInterbank($CuentasCobro, $ProductoId)
    {
        //Campos para Nombre de Archivo de entrada:

        //Ya se encuentran correctos
        if (true) {
            //Tener en cuenta esta cedula 199287D9023
            //Tener en cuennta el PaisId
            //Tener en cuenta el RUT

            $Canal = "H2H"; //REVISADO //Constante 'H2H' #Longitud OK
            $Flujo = "H"; //REVISADO //Utilizar la letra que le corresponde al flujo: H, M, S, X, R para el caso es H=H2H (Host to host) #Longitud OK
            $SubFlujo = "000"; //REVISADO //Si un flujo tiene diferentes rutas de recepción de archivos. Si no tiene enviar ceros. #Longitud OK

            //$this->RR = '03';//REVISADO //El rubro 03 el proveedor lo envio por chat, confirmado  //Código del rubro, proporcionado por Interbank    //03: Proveedores; 04: Remuneraciones; 05: Varios; 06: CTS #Longitud OK
            $RubroNombre = $this->RR;

            //$this->EEEE = "FX43";  //El codigo FX43 fue el que el proveedor envio por chat, confirmado //Código de Empresa. Proporcionado por Interbank #Longitud OK
            //$this->EEEE = "AAD0"; //REVISADO //El codigo FX43 fue el que el proveedor envio por chat, confirmado //Código de Empresa. Proporcionado por Interbank #Longitud OK
            $EmpresaNombre = $this->EEEE;  //Código de Empresa. Proporcionado por Interbank

            //$this->service_code = '01'; //Es desconocido preguntar //Se debe cambiar por el asignado en credenciales //Código del servicio. Proporcionado por Interbank #Longitud OK
            $ServicioNombre = $this->service_code;  //Código del servicio. Proporcionado por Interbank

            /*Identificacion del lote
                Todas las cuentas de cobro que conforman el lote estaran identificadas con el mismo $transproducto->externoId.
                El externo id de transaccion produccion sera el identificador del lote.
            */

            $IndentiLote = md5(time());
            $IndentiLote = substr(
                $IndentiLote,
                0,
                25
            ); //Código único que identifica al lote (generado por la Empresa) #Longitud OK
            $FechaAndHora = date(
                "YmdHis"
            );  //Guión (bajo) Fecha y hora de creación del archivo   Formato: _SSAAMMDDHHMMSS
            $Extencion = ".txt"; //Revisar si en minuscula o mayuscula funciona igual.
            //$Extencion = ".TXT";
            $NombreCompleto = $Canal . $Flujo . $SubFlujo . $RubroNombre . $EmpresaNombre . $ServicioNombre . $IndentiLote . '_' . $FechaAndHora . $Extencion;

            $NombreCompleto = strtoupper($NombreCompleto);
        }

        //Campos para Cabecera  de Archivo de entrada:
        //Ya se encuentran correctos
        if (true) {
            $Producto = new Producto($ProductoId);

            $CodigoRegistro = '01'; //REVISADO //Constante '01' #Longitud OK

            //$this->RR = '03';  //REVISADO //Valor fijo (ver columna valores), código del rubro al que se refiere el pago. Proporcionado por Interbank  ---> //03: Proveedores; 04: Remuneraciones; 05: Varios; 06: CTS #Longitud OK
            $RubroCabecera = $this->RR;

            //$this->EEEE = "FX43";  //Código de Empresa. Proporcionado por Interbank #Longitud OK
            //$this->EEEE = "AAD0"; //REVISADO //Código de Empresa. Proporcionado por Interbank #Longitud OK
            $EmpresaCabecera = $this->EEEE;  //Código de Empresa. Proporcionado por Interbank

            //$this->service_code = '01'; //PREGUNTAR //Código del servicio. Proporcionado por Interbank
            $ServicioCabecera = $this->service_code;  //Código del servicio. Proporcionado por Interbank

            //La cuenta Cuenta Cargo es proporcionada por Interbank y debe contener 13 digitos.
            //Observacion, interbank nos proporciona tipos de cuentas empresariales
            //Corriente soles	Corriente dólares	Ahorro soles	Ahorro dólares
            //1007001233250	      1007001233268	    1007007488061	1007007488079
            //La propuesta es crear productos diferentes y pagar notas de retiro masivas con el mismo producto

            $CuentaCargo = $Producto->externoId;  //Cuenta de la Empresa en Interbank en la que se aplicará el cargo por los pagos a realizar

            //REVISADO
            if ($Producto->externoId == '1007001233250') { //CORRIENTE SOLES
                $TipoCuentaCargo = '001'; //Valor fijo (ver columna Valores) ----->  //  001: Cuenta Corriente;  002: Cuenta de Ahorros //Se refiere al tipo de cuenta de Virtualsoft con Interbank de momento este parametro esta quemado, debe cambiarse. #Longitud OK
                $MonedaCuentaCargo = '01'; //Valor fijo (ver columna Valores) ----->  01: Soles;  10: Dólares //Se refiere a la moneda de la cuenta de Virtualsoft con Interbank de momento este parametro esta quemado, debe cambiarse. #Longitud OK

            } elseif ($Producto->externoId == '1007001233268') { //CORRIENTE DOLARES
                $TipoCuentaCargo = '001'; //Valor fijo (ver columna Valores) ----->  //  001: Cuenta Corriente;  002: Cuenta de Ahorros //Se refiere al tipo de cuenta de Virtualsoft con Interbank de momento este parametro esta quemado, debe cambiarse. #Longitud OK
                $MonedaCuentaCargo = '10'; //Valor fijo (ver columna Valores) ----->  01: Soles;  10: Dólares //Se refiere a la moneda de la cuenta de Virtualsoft con Interbank de momento este parametro esta quemado, debe cambiarse. #Longitud OK

            } elseif ($Producto->externoId == '1007007488061') { //AHORROS SOLES
                $TipoCuentaCargo = '002'; //Valor fijo (ver columna Valores) ----->  //  001: Cuenta Corriente;  002: Cuenta de Ahorros //Se refiere al tipo de cuenta de Virtualsoft con Interbank de momento este parametro esta quemado, debe cambiarse. #Longitud OK
                $MonedaCuentaCargo = '01'; //Valor fijo (ver columna Valores) ----->  01: Soles;  10: Dólares //Se refiere a la moneda de la cuenta de Virtualsoft con Interbank de momento este parametro esta quemado, debe cambiarse. #Longitud OK

            } elseif ($Producto->externoId == '1007007488079') { //AHORROS DOLARES
                $TipoCuentaCargo = '002'; //Valor fijo (ver columna Valores) ----->  //  001: Cuenta Corriente;  002: Cuenta de Ahorros //Se refiere al tipo de cuenta de Virtualsoft con Interbank de momento este parametro esta quemado, debe cambiarse. #Longitud OK
                $MonedaCuentaCargo = '10'; //Valor fijo (ver columna Valores) ----->  01: Soles;  10: Dólares //Se refiere a la moneda de la cuenta de Virtualsoft con Interbank de momento este parametro esta quemado, debe cambiarse. #Longitud OK

            } elseif ($Producto->externoId == '1083001509276') { //CORRIENTE SOLES
                $TipoCuentaCargo = '001'; //Valor fijo (ver columna Valores) ----->  //  001: Cuenta Corriente;  002: Cuenta de Ahorros //Se refiere al tipo de cuenta de Virtualsoft con Interbank de momento este parametro esta quemado, debe cambiarse. #Longitud OK
                $MonedaCuentaCargo = '01'; //Valor fijo (ver columna Valores) ----->  01: Soles;  10: Dólares //Se refiere a la moneda de la cuenta de Virtualsoft con Interbank de momento este parametro esta quemado, debe cambiarse. #Longitud OK

            } //REVISADO

            $NombreLote = "PAGOSRETIROS"; //REVISADO //Descripción que el cliente quiera poner al lote a modo de Nombre #Longitud OK
            //$FechaCreacion = date("YmdHis"); //REVISADO //Fecha y hora de creación del TXT #Longitud OK
            $FechaCreacion = $FechaAndHora;

            $TipoProceso = 0; //REVISADO //Valor fijo ---->  0: En Línea;  1: En Diferido //De momento este parametro esta quemado, debe cambiarse. #Longitud OK
            $FechaProceso = date(
                "Ymd"
            );  //REVISADO  //Si tipo de proceso en línea, la fecha es del día enviado. Si es Diferido la fecha es futura, no Domingos ni feriados.  ----->Formato: SSAAMMDD #Longitud OK

            $TotalCuentasCobro = str_pad(
                strval(count($CuentasCobro)),
                6,
                "0",
                STR_PAD_LEFT
            ); //REVISADO //Suma del campo Importe moneda soles ---->  000000000000022
            $Nregistro = $TotalCuentasCobro;  //contador de registros detalle ( cuantos registros a procesar contiene el lote)  ----> 123456 #Longitud OK

            $TotalSoles = '000000000000000';  //Suma del campo Importe moneda dolares ----> 000000000000022 #Longitud OK
            $TotalDolares = '000000000000000';  //Suma del campo Importe moneda dolares ----> 000000000000022 #Longitud OK
            $Total = 0;

            foreach ($CuentasCobro as $key => $Id) {
                $Total = $Total + ($CuentasCobro[$key]->getValor() - $CuentasCobro[$key]->getImpuesto() - $CuentasCobro[$key]->getImpuesto2());
            }

            $Total = number_format($Total, 2, '.', '');
            $Total = explode(".", $Total);
            $Total = $Total[0] . $Total[1];
            $TotalConCeros = str_pad(
                $Total,
                15,
                "0",
                STR_PAD_LEFT
            ); //Suma del campo Importe moneda soles ---->  000000000000022 #Longitud OK

            if ($MonedaCuentaCargo == '01') {
                $TotalSoles = $TotalConCeros; //Suma del campo Importe moneda dolares ----> 000000000000022 #Longitud OK
            } elseif ($MonedaCuentaCargo == '10') {
                $TotalDolares = $TotalConCeros; //Suma del campo Importe moneda dolares ----> 000000000000022 #Longitud OK
            } //REVISADO

            $VersionMacro = "MC001";  //Constante "MC001" ---> MC001 #Longitud OK

            $CabeceraCompleta = $CodigoRegistro . $RubroCabecera . $EmpresaCabecera . $ServicioCabecera . $CuentaCargo . $TipoCuentaCargo . $MonedaCuentaCargo . $NombreLote . $FechaCreacion . $TipoProceso . $FechaProceso . $Nregistro . $TotalSoles . $TotalDolares . $VersionMacro;

            $CabeceraCompleta = strtoupper($CabeceraCompleta);
        }

        print_r($_SERVER);
        print_r('URL1');
        print_r(__DIR__ . '/../../../integrations-int/payout/interbank/api/archivos/' . $NombreCompleto);
        print_r(PHP_EOL);

        $Archivo = fopen(__DIR__ . '/../../../integrations-int/payout/interbank/api/archivos/' . $NombreCompleto, 'a'); //abrir archivo, nombre archivo, modo apertura

        fwrite($Archivo, $CabeceraCompleta . PHP_EOL) or die("No se pudo escribir en el archivo");

        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
        $Transaction = $TransaccionProductoMySqlDAO->getTransaction();

        foreach ($CuentasCobro as $key => $value) {
            $Usuario = new Usuario($CuentasCobro[$key]->usuarioId);
            $Registro = new Registro("", $CuentasCobro[$key]->usuarioId);
            $UsuarioBanco = new UsuarioBanco($CuentasCobro[$key]->mediopagoId);
            $Banco = new Banco($UsuarioBanco->bancoId);

            $valorFinal = $CuentasCobro[$key]->getValor() - $CuentasCobro[$key]->getImpuesto() - $CuentasCobro[$key]->getImpuesto2();

            $TransaccionProducto = new TransaccionProducto();
            $TransaccionProducto->setProductoId($Producto->productoId);
            $TransaccionProducto->setUsuarioId($Usuario->usuarioId);
            $TransaccionProducto->setValor($valorFinal);
            $TransaccionProducto->setEstado('A');
            $TransaccionProducto->setTipo('T');
            $TransaccionProducto->setExternoId($IndentiLote);
            $TransaccionProducto->setEstadoProducto('E');
            $TransaccionProducto->setMandante($Usuario->mandante);
            $TransaccionProducto->setFinalId($CuentasCobro[$key]->getCuentaId());
            $TransaccionProducto->setFinalId(0);

            $transproductoId = $TransaccionProductoMySqlDAO->insert($TransaccionProducto);

            $CuentasCobro[$key]->setTransproductoId($transproductoId); //

            $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO($Transaction);

            $rowsUpdate = $CuentaCobroMySqlDAO->update($CuentasCobro[$key]);
            if (intval($rowsUpdate) == 0) {
                exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . 'INTERBANK NO UPDATE ' . $CuentasCobro[$key]->getCuentaId() . "' '#alertas-integraciones' > /dev/null & ");
            }

            //Campos para DETALLE  de Archivo de entrada:
            if (true) {
                $CodigoRegistro = '02'; //REVISADO //Identifica que el registro es parte del detalle de la instrucción de pago.--->constante '02' #Longitud OK

                $CodigoBeneficiario = str_pad($Usuario->usuarioId, 20, "0", STR_PAD_LEFT); //Código único que identifica al beneficiario asignado por la Empresa cliente.  ---> //Ejem. RUC del Beneficiario  #Longitud OK

                $TipoDocPago = "F"; //Tipo de documento de pago, aplica solo para los productos: Proveedores y Varios --> F= Factura (+); C= Nota de Crédito (+); D= Nota de Débito (-)

                $NumDocPago = str_pad($transproductoId, 20, "0", STR_PAD_LEFT); //Número de documento a pagar (Obligatorio para Orden de pago: Efectivo o Cheque de gerencia)  #Longitud OK

                $FechaVencimientoDoc = "";
                //$FechaVencimientoDoc = "        "; //PREGUNTAR // Este parametro no es obligatorio //Fecha del vencimiento del documento, formato: AAAAMMDD
                $FechaVencimientoDoc = str_pad($FechaVencimientoDoc, 8, " ", STR_PAD_RIGHT);

                switch ($Usuario->moneda) //Moneda en el que se afectuará la operación ----->     01: Soles;  10: Dólares  #Longitud OK
                {
                    case "PEN":
                        $MonedaCuenta = '01';
                        $MonedaDeAbono = '01'; //Valor fijo (ver columna Valores) ----->  01: Soles;  10: Dólares //Se refiere a la moneda de la cuenta de Virtualsoft con Interbank de momento este parametro esta quemado, debe cambiarse. #Longitud OK
                        break;
                    case "USD":
                        $MonedaCuenta = '10';
                        $MonedaDeAbono = '10'; //Valor fijo (ver columna Valores) ----->  01: Soles;  10: Dólares //Se refiere a la moneda de la cuenta de Virtualsoft con Interbank de momento este parametro esta quemado, debe cambiarse. #Longitud OK
                        break;
                    default:
                        throw new Exception("Tipo de moneda no encontrada", "100000"); //Cambiar el codigo de error
                }

                $MontoAbono = number_format($valorFinal, 2, '.', '');
                $MontoAbono = explode(".", $MontoAbono);
                $MontoAbono = $MontoAbono[0] . $MontoAbono[1];
                $MontoAbonoConCeros = str_pad(
                    $MontoAbono,
                    15,
                    "0",
                    STR_PAD_LEFT
                ); //REVISADO  ////Importe numérico positivo (13 enteros y 2 decimales) ----->  99 #Longitud OK
                $IndicadorBanco = 0; //REVISADO  //Completar con ceros ('0') #Longitud OK
                $TipoAbono = '09'; //REVISADO  //ES el medio por el que se le pagará al Beneficiario ---->  00=Efectivo; 09=Abono en cuenta; 11=Cheque de Gerencia y 99=Interbancario #Longitud OK

                switch ($UsuarioBanco->getTipoCuenta()) //Indica si es Cuenta Corriente, Ahorros o CTS (Obligatorio solo si tipo de abono=09) ----->  //  001=Cuenta Corriente; 002=Arhorros; 007=CTS #Longitud OK
                {
                    case "0":
                        $TipoCuenta = '002';
                        break;
                    case "1":
                        $TipoCuenta = '001';
                        break;
                    case "Ahorros":
                        $TipoCuenta = '002';
                        break;
                    case "Corriente":
                        $TipoCuenta = '001';
                        break;
                    default:
                        throw new Exception("Tipo de cuenta bancaria no encontrada", "100000");
                        break;
                }

                #$OficinaCuenta = substr($Producto->externoId, 0, 3); //Completar con ceros ( '0') -> 0 ¿? Cual es la oficina de la cuenta? Esto debe ser longitud 3 y se debe completar con ceros
                $OficinaCuenta = preg_replace("/[^0-9]/", "", $UsuarioBanco->cuenta);
                $OficinaCuenta = substr(
                    $OficinaCuenta,
                    0,
                    3
                ); //Completar con ceros ( '0') -> 0 ¿? Cual es la oficina de la cuenta? Esto debe ser longitud 3 y se debe completar con ceros

                $NumeroCuenta = preg_replace(
                    "/[^0-9]/",
                    "",
                    $UsuarioBanco->cuenta
                ); //Cuenta mismo banco IBK= 13 dígitos; Interbancaria CCI= 20 dígitos (Obligatorio solo si tipo de abono=09 o 99 ---> Cta IBK=2000012345678

                $NumeroCuenta = substr($NumeroCuenta, 3, strlen($NumeroCuenta));

                $NumeroCuenta = str_pad($NumeroCuenta, 20, " ", STR_PAD_RIGHT);

                $TipoPersonas = 'P';

                switch ($Registro->getTipoDoc(
                )) { //Tipo de documento de identidad del beneficiario -->01=DNI; 02=RUC; 03=Carnet de extrajería; 05=Pasaporte #Longitud OK
                    case "E":
                        $TipoDocIdentidad = "03";
                        break;
                    case "P":
                        $TipoDocIdentidad = "05";
                        break;
                    case "C":
                        $TipoDocIdentidad = "01";
                        break;
                    case "R":
                        $TipoDocIdentidad = "02";
                        break;
                    default:
                        $TipoDocIdentidad = "01";
                        break;
                }

                $NumDocIdentidad = str_pad($Registro->cedula, 15, " ", STR_PAD_RIGHT); //Número de documento de identidad del beneficiario #Longitud OK
                $Name1 = $this->remove_accents(trim($Registro->nombre1));
                $Name2 = $this->remove_accents(trim($Registro->nombre2));
                $LastName = $this->remove_accents(trim($Registro->apellido1));

                //$LastName = trim($CuentasCobro[$key]->getCuentaId().'#'.$Registro->apellido1);
                //$LastName = trim($transproductoId.'#'.$Registro->apellido1);
                //$LastName2 = trim($Registro->apellido2);
                //$LastName2 = $transproductoId;

                $LastName2 = $CuentasCobro[$key]->getCuentaId();

                $NombreBeneficiario = $LastName2 . ';' . $LastName . ';' . $Name1 . ';' . $Name2;  //Nombre del Beneficiario o razón social --> P=A.Paterno;A.Materno;Nombre;  C=Razon social

                $NombreBeneficiario = str_pad(
                    $NombreBeneficiario,
                    60,
                    " ",
                    STR_PAD_RIGHT
                ); //Número de documento de identidad del beneficiario #Longitud OK

                $MonedaMontoIntangible = '00'; //Moneda del monto intangible (Solo rubro CTS)  --->01: Soles;  10: Dólares

                $MontoIntangible = "";
                //$MontoIntangible = "               "; //Importe numérico positivo (13 enteros y 2 decimales), último sueldo bruto multiplicado * 4 (según ley vigente)  --->01: Soles;  10: Dólares
                $MontoIntangible = str_pad($MontoIntangible, 15, " ", STR_PAD_RIGHT); //

                $filler = "";
                //$filler = '      ';  //Espacios -->Seis espacios '      '
                $filler = str_pad($filler, 6, " ", STR_PAD_RIGHT);

                $NumCelular = $Registro->celular;   //Números de celular, podría ingresar hasta 4 separados por punto y coma.--->Ejm: 994000111;999000222

                $NumCelular = str_pad(
                    $NumCelular,
                    40,
                    " ",
                    STR_PAD_RIGHT
                ); //Número de documento de identidad del beneficiario #Longitud OK

                $Correo = $Registro->email;  //Dirección de correo electrónico del beneficiario, permite varios correos separados por punto y coma -->Ejm: Jperez@hotmail.com;Cperez@Hotmail.com

                $Correo = str_pad(
                    $Correo,
                    140,
                    " ",
                    STR_PAD_RIGHT
                ); //Número de documento de identidad del beneficiario #Longitud OK

                $DetalleCompleto = $CodigoRegistro . $CodigoBeneficiario . $TipoDocPago . $NumDocPago . $FechaVencimientoDoc . $MonedaDeAbono . $MontoAbonoConCeros . $IndicadorBanco . $TipoAbono . $TipoCuenta . $MonedaCuenta . $OficinaCuenta . $NumeroCuenta . $TipoPersonas . $TipoDocIdentidad . $NumDocIdentidad . $NombreBeneficiario . $MonedaMontoIntangible . $MontoIntangible . $filler . $NumCelular . $Correo;
            }

            fwrite($Archivo, $DetalleCompleto . PHP_EOL) or die("No se pudo escribir en el archivo");
        }

        fclose($Archivo);

        $result = $this->connectionSFTPSend($NombreCompleto);

        if ($result === true) {
            $Transaction->commit();
        } else {
            throw new Exception("La transferencia no fue procesada", "10000");
        }
    }

    /**
     * Envía un archivo a través de SFTP.
     *
     * @param string $Nombre Nombre del archivo a enviar.
     *
     * @return boolean Resultado de la operación (true si fue exitoso, false en caso contrario).
     * @throws Exception Si falla la autenticación o el envío del archivo.
     */
    public function connectionSFTPSend($Nombre)
    {
        $result = false;

        try {
            // Cargar claves
            $ssh_auth_pub = file_get_contents($this->ssh_auth_pub);
            $ssh_auth_priv = file_get_contents($this->ssh_auth_priv);

            $priv_key = RSA::load($ssh_auth_priv);
            $pub_key = RSA::load($ssh_auth_pub);

            // Conexión SFTP
            $ssh = new SFTP($this->ssh_server_fp, $this->ssh_port);

            if (!$ssh->login($this->ssh_auth_user, $pub_key, $priv_key)) {
                throw new Exception("Login Failed. Log: " . $ssh->getLog());
            }

            syslog(LOG_WARNING, "INTERBANK SEND VERSION: " . $Nombre . ' ' . $ssh->getServerIdentification());

            // Verificar directorio /OUT/
            if (!$ssh->file_exists('/IN/')) {
                throw new Exception("El directorio /IN/ no existe en el servidor.");
            }

            // Subir archivo
            $localFile = __DIR__ . '/../../../integrations-int/payout/interbank/api/archivos/' . $Nombre;
            $remoteFile = '/IN/' . $Nombre;

            if (!$ssh->put($remoteFile, $localFile, SFTP::SOURCE_LOCAL_FILE)) {
                throw new Exception("Error al subir el archivo. Log: " . $ssh->getLog());
            }

            $result = true;
            syslog(LOG_WARNING, "INTERBANK SEND: " . $Nombre . ' ' . "Archivo subido correctamente.");
        } catch (Exception $e) {
            syslog(LOG_WARNING, "INTERBANK SEND ERROR: " . $Nombre . ' ' . $e->getMessage());
            $result = false;
        }

        return $result;
    }

    /**
     * Recibe archivos desde el servidor SFTP.
     *
     * @param string $fechaL2 Fecha límite para filtrar los archivos.
     *
     * @return array Lista de archivos descargados con sus metadatos.
     * @throws Exception Si falla la autenticación o la descarga de archivos.
     */
    public function connectionSFTPReceiver($fechaL2)
    {
        $finalTotal = [];

        try {
            // Validar existencia de archivos de claves
            if (!file_exists($this->ssh_auth_pub) || !file_exists($this->ssh_auth_priv)) {
                throw new Exception('Archivos de claves SSH no encontrados');
            }

            // Cargar claves con manejo de errores
            $ssh_auth_pub = file_get_contents($this->ssh_auth_pub);
            $ssh_auth_priv = file_get_contents($this->ssh_auth_priv);

            if (empty($ssh_auth_pub) || empty($ssh_auth_priv)) {
                throw new Exception('Las claves SSH están vacías o no se pueden leer');
            }

            // Crear conexión SFTP
            $ssh = new SFTP($this->ssh_server_fp, $this->ssh_port);

            // Cargar claves RSA
            $pub_key = RSA::load($ssh_auth_pub);
            $priv_key = RSA::load($ssh_auth_priv);

            // Autenticación
            if (!$ssh->login($this->ssh_auth_user, $pub_key, $priv_key)) {
                throw new Exception('Falló la autenticación SSH: ' . $ssh->getLog());
            }

            // Verificar existencia del directorio remoto
            if (!$ssh->file_exists('/OUT/')) {
                throw new Exception('El directorio /OUT/ no existe en el servidor remoto');
            }

            // Obtener lista de archivos con metadata
            $files = $ssh->rawlist('/OUT/');

            if (!is_array($files)) {
                throw new Exception('No se pudo obtener la lista de archivos o el directorio está vacío');
            }

            // Directorio local para descargas
            $localDir = '/home/home2/backend/api/integrations-int/payout/interbank/api/archivos/response/';

            // Procesar archivos
            foreach ($files as $fileInfo) {
                // Saltar si no es un archivo regular
                if ($fileInfo['type'] !== 1) { // 1 = archivo regular
                    continue;
                }

                $filename = $fileInfo['filename'];

                // Filtrar solo archivos .txt
                if (!preg_match("/\.txt$/i", $filename)) {
                    continue;
                }

                // Convertir fecha de modificación
                $dateFile = date('Y-m-d H:i:s', $fileInfo['mtime']);

                // Verificar si el archivo es más reciente que $fechaL2
                if ($dateFile > $fechaL2) {
                    $localPath = $localDir . $filename;

                    // Descargar archivo
                    if ($ssh->get('/OUT/' . $filename, $localPath)) {
                        // Verificar que el archivo se descargó correctamente
                        if (file_exists($localPath) && filesize($localPath) > 0) {
                            $finalTotal[] = [
                                'filename' => $filename,
                                'mtime' => $fileInfo['mtime'],
                                'local_path' => $localPath,
                                'size' => $fileInfo['size']
                            ];
                        } else {
                            syslog(LOG_WARNING, "INTERBANK GET ERROR: " . $fechaL2 . ' ' . "Archivo descargado pero no válido: $filename");
                        }
                    } else {
                        syslog(LOG_WARNING, "INTERBANK GET ERROR: " . $fechaL2 . ' ' . "Falló la descarga del archivo: $filename");
                    }
                }
            }

            return $finalTotal;
        } catch (Exception $e) {
            syslog(LOG_WARNING, "INTERBANK GET ERROR: " . $fechaL2 . ' ' . $e->getMessage());
            return $finalTotal;
        }
    }
}
