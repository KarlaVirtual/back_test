<?php

/**
 * Esta clase gestiona la integración con el servicio H2H BCP para realizar operaciones.
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
use Backend\dto\Producto;
use Backend\dto\Registro;
use Backend\dto\UsuarioBanco;
use Backend\dto\TransaccionProducto;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\mysql\TransaccionProductoMySqlDAO;

/**
 * Clase `H2HBCPSERVICES`.
 *
 * Esta clase gestiona la integración con el servicio H2H BCP para realizar operaciones
 * como transferencias, encriptación y desencriptación de archivos, y conexión SFTP.
 * Proporciona métodos para manejar archivos de entrada y salida, así como para
 * interactuar con el servidor SFTP.
 */
class H2HBCPSERVICES
{
    /**
     * Puerto del servidor SFTP.
     *
     * @var integer
     */
    private $ssh_port = 1722;

    /**
     * Puerto del servidor SFTP en desarrollo.
     *
     * @var integer
     */
    private $ssh_port_DEV = 1722;

    /**
     * Puerto del servidor SFTP en producción.
     *
     * @var integer
     */
    private $ssh_port_PROD = 1722;

    /**
     * Dirección del servidor SFTP.
     *
     * @var string
     */
    private $ssh_server_fp = '';

    /**
     * Dirección del servidor SFTP en desarrollo.
     *
     * @var string
     */
    private $ssh_server_fp_DEV = 'bcp.filetransferbcp.com';

    /**
     * Dirección del servidor SFTP en producción.
     *
     * @var string
     */
    private $ssh_server_fp_PROD = 'bcp.filetransferbcp.com';

    /**
     * Usuario de autenticación para el servidor SFTP.
     *
     * @var string
     */
    private $ssh_auth_user = '';

    /**
     * Usuario de autenticación para el servidor SFTP en desarrollo.
     *
     * @var string
     */
    private $ssh_auth_user_DEV = 'FTINTP01';

    /**
     * Usuario de autenticación para el servidor SFTP en producción.
     *
     * @var string
     */
    private $ssh_auth_user_PROD = 'FTINTP01';

    /**
     * Contraseña de autenticación para el servidor SFTP.
     *
     * @var string
     */
    private $ssh_auth_pws = '';

    /**
     * Contraseña de autenticación para el servidor SFTP en desarrollo.
     *
     * @var string
     */
    private $ssh_auth_pws_DEV = 'cT9vE0QTJTlciB';

    /**
     * Contraseña de autenticación para el servidor SFTP en producción.
     *
     * @var string
     */
    private $ssh_auth_pws_PROD = 'cT9vE0QTJTlciB';

    /**
     * Ruta de la clave pública PGP.
     *
     * @var string
     */
    private $pgp_public_key = '';

    /**
     * Ruta de la clave pública PGP en desarrollo.
     *
     * @var string
     */
    private $pgp_public_key_DEV = __DIR__ . '/../../../integrations/payout/h2hbcp/api/PGP_PUBLIC_KEY.asc';

    /**
     * Ruta de la clave pública PGP en producción.
     *
     * @var string
     */
    private $pgp_public_key_PROD = __DIR__ . '/../../imports/h2hbcp/PGP_PUBLIC_KEY.asc';

    /**
     * Ruta de la clave privada PGP.
     *
     * @var string
     */
    private $pgp_private_key = '';

    /**
     * Ruta de la clave privada PGP en desarrollo.
     *
     * @var string
     */
    private $pgp_private_key_DEV = __DIR__ . '/../../../integrations/payout/h2hbcp/api/PGP_PRIVATE_KEY.asc';

    /**
     * Ruta de la clave privada PGP en producción.
     *
     * @var string
     */
    private $pgp_private_key_PROD = __DIR__ . '/../../imports/h2hbcp/PGP_PRIVATE_KEY.asc';

    /**
     * Frase de paso para la clave privada PGP.
     *
     * @var string
     */
    private $passphrase = '';

    /**
     * Frase de paso para la clave privada PGP en desarrollo.
     *
     * @var string
     */
    private $passphrase_DEV = 'd00pololo00DOR00Pen';

    /**
     * Frase de paso para la clave privada PGP en producción.
     *
     * @var string
     */
    private $passphrase_PROD = 'd00pololo00DOR00Pen';

    /**
     * Constructor de la clase H2HBCPSERVICES.
     * Configura las variables según el entorno (desarrollo o producción).
     */
    public function __construct()
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();
        if ($ConfigurationEnvironment->isDevelopment()) {
            $this->ssh_port = $this->ssh_port_DEV;
            $this->ssh_server_fp = $this->ssh_server_fp_DEV;
            $this->ssh_auth_user = $this->ssh_auth_user_DEV;
            $this->ssh_auth_pws = $this->ssh_auth_pws_DEV;
            $this->pgp_public_key = $this->pgp_public_key_DEV;
            $this->pgp_private_key = $this->pgp_private_key_DEV;
            $this->passphrase = $this->passphrase_DEV;
        } else {
            $this->ssh_port = $this->ssh_port_PROD;
            $this->ssh_server_fp = $this->ssh_server_fp_PROD;
            $this->ssh_auth_user = $this->ssh_auth_user_PROD;
            $this->ssh_auth_pws = $this->ssh_auth_pws_PROD;
            $this->pgp_public_key = $this->pgp_public_key_PROD;
            $this->pgp_private_key = $this->pgp_private_key_PROD;
            $this->passphrase = $this->passphrase_PROD;
        }
    }

    /**
     * Elimina los acentos de una cadena de texto.
     *
     * @param string $string Cadena de texto a procesar.
     *
     * @return string Cadena de texto sin acentos.
     */
    public function remove_accents($string)
    {
        if ( ! preg_match('/[\x80-\xff]/', $string)) {
            return $string;
        }

        $chars = array(
            // Decomposiciones para Latin-1 Supplement
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
            // Decomposiciones para Latin Extended-A
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
     * Genera un archivo de entrada para realizar pagos masivos a través del servicio H2H BCP.
     *
     * Este metodo realiza las siguientes acciones:
     * - Genera un nombre único para el archivo de entrada.
     * - Crea la cabecera del archivo con información de las transacciones.
     * - Procesa cada cuenta de cobro para generar los detalles del archivo.
     * - Guarda el archivo generado en el sistema de archivos.
     * - Encripta el archivo utilizando GPG.
     * - Envía el archivo encriptado al servidor SFTP.
     * - Actualiza las transacciones en la base de datos.
     *
     * @param array $CuentasCobro Lista de cuentas de cobro a procesar.
     * @param int   $ProductoId   ID del producto asociado a las transacciones.
     *
     * @return void
     * @throws Exception Si ocurre un error durante el proceso de generación, encriptación o envío del archivo.
     */
    public function cashOutH2hbcp($CuentasCobro, $ProductoId)
    {
        if ($_ENV['debug']) {
            error_reporting(E_ALL);
            ini_set('display_errors', 'ON');
        }

        //Campos para Nombre de Archivo de entrada:
        if (true) {
            $Tipo = "P"; //Tipo de archivo
            $Date = date('Ymd'); //Fecha de creacion del archivo
            $IndentiLote = md5(time());
            $IndentiLote = preg_replace('/[^0-9]/', '', $IndentiLote);
            $IndentiLote = substr(
                $IndentiLote,
                0,
                6
            ); //Código único que identifica al lote (generado por la Empresa) #Longitud OK

            $FechaAndHora = date("YmdHis"); //Fecha y hora de creación del archivo Formato: _SSAAMMDDHHMMSS
            $Extencion = ".txt"; //$Extencion = ".TXT";

            $NombreCompleto_ = $Tipo . $Date . $IndentiLote . $Tipo;

            $NombreCompleto = strtoupper($NombreCompleto_ . '-DEC') . $Extencion;
            $NombreCompleto2 = strtoupper($NombreCompleto_) . $Extencion;
        }

        //Campos para Cabecera  de Archivo de entrada:
        if (true) {
            $Producto = new Producto($ProductoId);

            $CodigoRegistro = '1'; //Tipo de Registro

            $Total = 0;
            foreach ($CuentasCobro as $key => $Id) {
                $Total++;
            }
            $TotalAbonos = str_pad($Total, 6, '0', STR_PAD_LEFT); //Total de abonos

            $FechaProceso = date("Ymd"); //Fecha de Proceso

            if ($Producto->externoId == '1942436931063') { //CORRIENTE SOLES
                $TipoCuentaCargo = 'C';
                $MonedaCuentaCargo = '0001';
                $Moneda = '0';
            } elseif ($Producto->externoId == '1942436931064') { //CORRIENTE DOLARES
                $TipoCuentaCargo = 'C';
                $MonedaCuentaCargo = '1001';
                $Moneda = '1';
            } elseif ($Producto->externoId == '1942436931065') { //AHORROS SOLES
                $TipoCuentaCargo = 'M';
                $MonedaCuentaCargo = '0001';
                $Moneda = '0';
            } elseif ($Producto->externoId == '1942436931066') { //AHORROS DOLARES
                $TipoCuentaCargo = 'M';
                $MonedaCuentaCargo = '1001';
                $Moneda = '1';
            }

            $Cuenta = $Producto->externoId;

            $CuentaCargoSin = $Cuenta;
            $CuentaCargo = str_pad($CuentaCargoSin, 20, ' ', STR_PAD_RIGHT);

            $MontoTotal = 0;
            foreach ($CuentasCobro as $key => $Id) {
                $MontoTotal = $MontoTotal + ($CuentasCobro[$key]->getValor() - $CuentasCobro[$key]->getImpuesto(
                        ) - $CuentasCobro[$key]->getImpuesto2());
            }
            $importeFormateado = number_format($MontoTotal, 2, '.', '');
            $importeFinal = str_pad($importeFormateado, 17, '0', STR_PAD_LEFT);

            $Referencia = 'PAGOSRETIROS-000000000-' . $importeFinal;

            $Flag = 'N';

            $SumaCuentas = 0;
            foreach ($CuentasCobro as $key => $Id) {
                $UsuarioBanco = new UsuarioBanco($CuentasCobro[$key]->mediopagoId);
                $Usuario = new Usuario($CuentasCobro[$key]->usuarioId);

                switch ($UsuarioBanco->getTipoCuenta()) {
                    case "0":
                        $TipoCuenta = 'A';
                        break;
                    case "1":
                        $TipoCuenta = 'C';
                        break;
                    case "Ahorros":
                        $TipoCuenta = 'A';
                        break;
                    case "Corriente":
                        $TipoCuenta = 'C';
                        break;
                    default:
                        $TipoCuenta = 'B';
                        break;
                }

                $CuentaAbono = $UsuarioBanco->cuenta;
                $CuentaStr = number_format($CuentaAbono, 0, '', '');
                if ($TipoCuenta == "A") {
                    $Cuentas = substr($CuentaStr, -11);
                } else {
                    $Cuentas = substr($CuentaStr, -10);
                }
                $SumaCuentas += intval($Cuentas);
            }

            $CuentaCargoS = number_format($CuentaCargoSin, 0, '', '');
            if ($TipoCuentaCargo == "M") {
                $CuentaCargoSin = substr($CuentaCargoS, -11);
            } else {
                $CuentaCargoSin = substr($CuentaCargoS, -10);
            }

            $SumaCuentas = str_pad($SumaCuentas + $CuentaCargoSin, 15, '0', STR_PAD_LEFT);

            $CabeceraCompleta = $CodigoRegistro . $TotalAbonos . $FechaProceso . $TipoCuentaCargo . $MonedaCuentaCargo . $CuentaCargo . $importeFinal . $Referencia . $Flag . $SumaCuentas;

            $CabeceraCompleta = strtoupper($CabeceraCompleta);
        }

        print_r('URL1');
        print_r(__DIR__ . '/../../../integrations-int/payout/h2hbcp/api/archivos/' . $NombreCompleto);
        print_r(PHP_EOL);

        $Archivo = fopen(
            __DIR__ . '/../../../integrations-int/payout/h2hbcp/api/archivos/' . $NombreCompleto,
            'a'
        ); //abrir archivo, nombre archivo, modo apertura

        fwrite($Archivo, $CabeceraCompleta . PHP_EOL) or die("No se pudo escribir en el archivo");

        $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO();
        $Transaction = $TransaccionProductoMySqlDAO->getTransaction();

        foreach ($CuentasCobro as $key => $value) {
            $Usuario = new Usuario($CuentasCobro[$key]->usuarioId);
            $Registro = new Registro("", $CuentasCobro[$key]->usuarioId);
            $UsuarioBanco = new UsuarioBanco($CuentasCobro[$key]->mediopagoId);
            $Banco = new Banco($UsuarioBanco->bancoId);

            $valorFinal = $CuentasCobro[$key]->getValor() - $CuentasCobro[$key]->getImpuesto(
                ) - $CuentasCobro[$key]->getImpuesto2();

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

            $CuentasCobro[$key]->setTransproductoId($transproductoId);

            $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO($Transaction);

            $rowsUpdate = $CuentaCobroMySqlDAO->update($CuentasCobro[$key]);

            if (intval($rowsUpdate) == 0) {
                exec(
                    "php -f /home/home2/backend/api/src/imports/Slack/message.php '" . 'H2HBCP NO UPDATE ' . $CuentasCobro[$key]->getCuentaId(
                    ) . "' '#alertas-integraciones' > /dev/null & "
                );
            }

            //Campos para DETALLE  de Archivo de entrada:
            if (true) {
                $CodigoRegistro = '2';

                switch ($UsuarioBanco->getTipoCuenta()) {
                    case "0":
                        $TipoCuenta = 'A';
                        break;
                    case "1":
                        $TipoCuenta = 'C';
                        break;
                    case "Ahorros":
                        $TipoCuenta = 'A';
                        break;
                    case "Corriente":
                        $TipoCuenta = 'C';
                        break;
                    default:
                        $TipoCuenta = 'B';
                        break;
                }

                switch ($Usuario->moneda) {
                    case "PEN":
                        $MonedaCuenta = '0';
                        $MonedaCuentaC = '0001';
                        break;
                    case "USD":
                        $MonedaCuenta = '1';
                        $MonedaCuentaC = '1001';
                        break;
                    default:
                        throw new Exception("Tipo de moneda no encontrada", "100000");
                }

                $UserCuentaAbono = $UsuarioBanco->cuenta;

                $CuentaAbonoSin = $UserCuentaAbono;
                $CuentaAbono = str_pad($CuentaAbonoSin, 20, ' ', STR_PAD_RIGHT);

                $ModalidadPago = 1;

                switch ($Registro->getTipoDoc()) {
                    case "E":
                        $TipoDocIdentidad = "3"; //CE
                        break;
                    case "P":
                        $TipoDocIdentidad = "4"; //PAS
                        break;
                    case "C":
                        $TipoDocIdentidad = "1"; //DNI
                        break;
                    case "R":
                        $TipoDocIdentidad = "6"; //RUC
                        break;
                    default:
                        $TipoDocIdentidad = "1";
                        break;
                }

                $NumDocIdentidad = str_pad($Registro->cedula, 12, " ", STR_PAD_RIGHT);

                $Correlativo = '   ';

                $Name1 = $this->remove_accents(trim($Registro->nombre1));
                $Name2 = $this->remove_accents(trim($Registro->nombre2));
                $LastName1 = $this->remove_accents(trim($Registro->apellido1));
                $LastName2 = $this->remove_accents(trim($Registro->apellido2));

                $NombreBeneficiario = $LastName1 . ' ' . $LastName2 . ' ' . $Name1 . ' ' . $Name2;
                $NombreBeneficiario = str_pad($NombreBeneficiario, 75, " ", STR_PAD_RIGHT);

                $CuentaUser = $CuentasCobro[$key]->getCuentaId();
                $RefenceUser = str_pad($transproductoId . '##' . $CuentaUser, 40, "0", STR_PAD_LEFT);
                $Refence = str_pad($transproductoId, 20, "0", STR_PAD_LEFT);

                $ImporteTotal = number_format($valorFinal, 2, '.', '');
                $ImporteTotal = str_pad($ImporteTotal, 17, "0", STR_PAD_LEFT);

                $FlagValidator = 'N';

                $DetalleCompleto = $CodigoRegistro . $TipoCuenta . $CuentaAbono . $ModalidadPago . $TipoDocIdentidad . $NumDocIdentidad . $Correlativo . $NombreBeneficiario . $RefenceUser . $Refence . $MonedaCuentaC . $ImporteTotal . $FlagValidator;

                $CodigoRegistroB = '3';
                $TipoDocPago = 'C';
                $NumDocId = str_pad($Registro->cedula, 15, "0", STR_PAD_RIGHT);

                $DetalleBeneficiario = $CodigoRegistroB . $TipoDocPago . $NumDocId . $ImporteTotal;
            }

            fwrite($Archivo, $DetalleCompleto . PHP_EOL) or die("No se pudo escribir en el archivo");

            fwrite($Archivo, $DetalleBeneficiario . PHP_EOL) or die("No se pudo escribir en el archivo");
        }

        fclose($Archivo);

        $encryptedFile = $this->encryptFileWithGnupg($NombreCompleto, $NombreCompleto2);

        if ($encryptedFile != 'ERROR') {
            $result = $this->connectionSFTPSend($encryptedFile, $NombreCompleto2);
            if ($result === true) {
                $Transaction->commit();
            } else {
                throw new Exception("La transferencia no fue procesada", "10000");
            }
        } else {
            throw new Exception("La transferencia no fue procesada", "10000");
        }
    }

    /**
     * Encripta un archivo utilizando GPG (GNU Privacy Guard).
     *
     * Este metodo realiza las siguientes acciones:
     * - Configura el entorno de GPG.
     * - Verifica si la extensión `gnupg` está habilitada.
     * - Lee el archivo de entrada que se desea encriptar.
     * - Importa la clave pública desde un archivo.
     * - Encripta el contenido del archivo utilizando la clave pública.
     * - Guarda el archivo encriptado en el sistema de archivos.
     *
     * @param string $Nombre  Nombre del archivo de entrada que se desea encriptar.
     * @param string $Nombre2 Nombre del archivo de salida encriptado.
     *
     * @return string Ruta del archivo encriptado o "ERROR" si ocurre un fallo.
     */
    public function encryptFileWithGnupg($Nombre, $Nombre2)
    {
        try {
            putenv('GNUPGHOME=/usr/share/httpd/.gnupg');
            $gpg = new \gnupg();

            // Lanzar una excepción si ocurre un error
            $gpg->seterrormode(\gnupg::ERROR_EXCEPTION);

            if (extension_loaded('gnupg')) {
                $caso1 = "La extensión gnupg está habilitada.\n";
            } else {
                $caso2 = "La extensión gnupg no está habilitada.\n";
            }

            // Ruta al archivo que deseas encriptar
            $inputFile = __DIR__ . '/../../../integrations-int/payout/h2hbcp/api/archivos/' . $Nombre;
            $encryptedFile = __DIR__ . '/../../../integrations-int/payout/h2hbcp/api/archivos/' . $Nombre2;

            // Ruta a la clave pública (en formato PGP)
            $publicKeyPath = $this->pgp_public_key;

            if (is_readable($publicKeyPath)) {
                $caso3 = "El archivo es accesible para PHP.\n";
            } else {
                $caso4 = "PHP no tiene acceso al archivo.\n";
            }

            // Verificar si el archivo de la clave pública existe
            if ( ! file_exists($publicKeyPath)) {
                $caso5 = "Error: El archivo de clave pública no se encuentra en la ruta especificada.\n";
            }

            // Leer la clave pública desde el archivo
            $publicKey = file_get_contents($publicKeyPath);

            if ($publicKey === false) {
                $caso6 = "Error al leer el archivo de clave pública.\n";
            }

            // Intentar importar la clave pública
            $importResult = $gpg->import($publicKey);

            if ($importResult === false) {
                $caso7 = "Error al importar la clave pública. Verifica que el archivo .asc sea válido y accesible.\n";
            }

            // Verificar si la clave fue importada correctamente
            if ($importResult['unchanged'] == 1) {
                $caso8 = "Clave ya importada: " . json_encode($importResult) . ".\n";
            } elseif (isset($importResult['imported']) && $importResult['imported'] > 0) {
                $caso9 = "Clave importada exitosamente: " . json_encode($importResult) . ".\n";
            } else {
                $caso10 = "No se importó ninguna clave: " . json_encode($importResult) . ".\n";
            }

            if ($_ENV['debug']) {
                print_r('Result: ');
                print_r(PHP_EOL);
                print_r($caso1 . $caso2 . $caso3 . $caso4 . $caso5 . $caso6 . $caso7 . $caso8 . $caso9 . $caso10);
            }

            $gpg->addencryptkey($importResult['fingerprint']);

            // Leer el contenido del archivo que deseas encriptar
            $plaintext = file_get_contents($inputFile);

            // Encriptar el archivo
            $encryptedData = $gpg->encrypt($plaintext);

            if ($encryptedData === false) {
                $caso11 = "Error al encriptar el archivo.\n";
            } else {
                // Guardar el archivo encriptado
                file_put_contents($encryptedFile, $encryptedData);
                $caso12 = "Archivo encriptado exitosamente y guardado como: $encryptedFile\n";
            }

            $message = $caso1 . $caso2 . $caso3 . $caso4 . $caso5 . $caso6 . $caso7 . $caso8 . $caso9 . $caso10 . $caso11 . $caso12;

            if ($_ENV['debug']) {
                print_r('H2HBCP ENC DATA: ');
                print_r(PHP_EOL);
                print_r($message);
            }

            syslog(LOG_WARNING, "H2HBCP ENC DATA: " . $Nombre2 . ': ' . $message);

            return $encryptedFile;
        } catch (Exception $e) {
            if ($_ENV['debug']) {
                print_r('H2HBCP ENC ERROR: ');
                print_r(PHP_EOL);
                print_r($e);
            }
            syslog(LOG_WARNING, "H2HBCP ENC ERROR: " . $Nombre2 . ': ' . json_encode($e));
            return "ERROR";
        }
    }

    /**
     * Envía un archivo encriptado al servidor SFTP.
     *
     * Este metodo realiza las siguientes acciones:
     * - Establece una conexión SFTP con el servidor.
     * - Verifica las credenciales de autenticación.
     * - Sube el archivo encriptado a la carpeta remota especificada.
     * - Maneja errores en caso de fallos durante la conexión o transferencia.
     *
     * @param string $encryptedFile   Ruta local del archivo encriptado que se desea enviar.
     * @param string $NombreCompleto2 Nombre del archivo en el servidor remoto.
     *
     * @return boolean|string Devuelve `true` si la transferencia es exitosa, o "ERROR" si ocurre un fallo.
     * @throws Exception Si ocurre un error durante la conexión o transferencia.
     */
    public function connectionSFTPSend($encryptedFile, $NombreCompleto2)
    {
        // Conexión SFTP
        $sftp = new SFTP($this->ssh_server_fp, $this->ssh_port);

        try {
            if ($sftp->login($this->ssh_auth_user, $this->ssh_auth_pws)) {
                syslog(
                    LOG_WARNING,
                    "H2HBCP SEND DATA: " . $NombreCompleto2 . ': ' . "Conexión exitosa al servidor SFTP $this->ssh_server_fp en el puerto $this->ssh_port."
                );

                // Ruta local del archivo que quieres subir
                $localFile = $encryptedFile;

                // Ruta remota en la carpeta IN del servidor
                $remoteFile = '/sftp/FTINTP01/TLC_PagosMasivos/IN/' . $NombreCompleto2;

                // Verifica si el archivo local existe antes de intentar la subida
                if (file_exists($localFile)) {
                    // Subir el archivo usando el método put()
                    if ($sftp->put($remoteFile, file_get_contents($localFile))) {
                        syslog(
                            LOG_WARNING,
                            "H2HBCP SEND DATA: " . $NombreCompleto2 . ': ' . "Archivo subido exitosamente a $remoteFile."
                        );
                    } else {
                        throw new Exception("Error al subir el archivo a $remoteFile.", "10000");
                    }
                } else {
                    throw new Exception("El archivo local $localFile no existe.", "10000");
                }
            } else {
                throw new Exception(
                    "Error al conectar al servidor SFTP $this->ssh_server_fp en el puerto $this->ssh_port. Verifica las credenciales.",
                    "10000"
                );
            }
            return true;
        } catch (Exception $e) {
            if ($_ENV['debug']) {
                print_r('H2HBCP SEND ERROR: ');
                print_r(PHP_EOL);
                print_r($e);
            }
            syslog(LOG_WARNING, "H2HBCP SEND ERROR: " . $NombreCompleto2 . ': ' . json_encode($e));
            return "ERROR";
        }
    }

    /**
     * Recibe archivos desde el servidor SFTP.
     *
     * Este metodo realiza las siguientes acciones:
     * - Establece una conexión SFTP con el servidor.
     * - Lista los archivos disponibles en la carpeta remota.
     * - Descarga los archivos a una carpeta local.
     * - Desencripta los archivos descargados.
     * - Elimina los archivos del servidor SFTP después de procesarlos.
     *
     * @param string $fechaL2 Fecha utilizada para el registro de logs y procesamiento.
     *
     * @return array|string Devuelve un arreglo con los nombres de los archivos procesados o "ERROR" si ocurre un fallo.
     * @throws Exception Si ocurre un error durante la conexión, descarga, desencriptación o eliminación de archivos.
     */
    public function connectionSFTPReceiver($fechaL2)
    {
        // Conexión SFTP
        $sftp = new SFTP($this->ssh_server_fp, $this->ssh_port);

        try {
            if ($sftp->login($this->ssh_auth_user, $this->ssh_auth_pws)) {
                syslog(
                    LOG_WARNING,
                    "H2HBCP GET DATA: " . $fechaL2 . ': ' . "Conexión exitosa al servidor SFTP $this->ssh_server_fp en el puerto $this->ssh_port."
                );

                // Especifica la carpeta remota de donde quieres descargar los archivos
                $remoteDir = '/sftp/FTINTP01/TLC_PagosMasivos/OUT/';

                // Lista los archivos en la carpeta OUT
                $archivos = $sftp->nlist($remoteDir);

                if ($archivos === false) {
                    throw new Exception("Error al listar los archivos en la carpeta $remoteDir.", "10000");
                }

                // Carpeta local donde guardarás los archivos descargados
                $localDir = __DIR__ . '/../../../integrations-int/payout/h2hbcp/api/archivos/response/';

                // Asegúrate de que la carpeta local exista
                if ( ! is_dir($localDir)) {
                    mkdir($localDir, 0777, true);
                }

                $final = array();
                $finalTotal = array();

                // Descargar y eliminar los archivos uno por uno
                foreach ($archivos as $archivo) {
                    // Ignorar directorios o entradas especiales como '.' y '..'
                    if ($archivo == '.' || $archivo == '..') {
                        continue;
                    }

                    // Definir la ruta remota completa
                    $remoteFile = $remoteDir . $archivo;

                    // Definir la ruta local completa
                    $localFile = $localDir . $archivo;

                    // Descargar el archivo
                    if ($sftp->get($remoteFile, $localFile)) {
                        syslog(
                            LOG_WARNING,
                            "H2HBCP GET DATA: " . $fechaL2 . ': ' . "Archivo $archivo descargado exitosamente a $localFile."
                        );

                        // Definir la ruta local completa para el archivo desencriptado
                        $localDecryptedFile = $localFile;
                        $decrypted = $this->decryptFileWithGnupg($localDecryptedFile, $fechaL2);

                        if ($decrypted != 'ERROR') {
                            // Eliminar el archivo del servidor SFTP después de descargarlo
                            if ($sftp->delete($remoteFile)) {
                                syslog(
                                    LOG_WARNING,
                                    "H2HBCP GET DATA: " . $fechaL2 . ': ' . "Archivo $archivo eliminado exitosamente de $remoteDir."
                                );

                                $nameFile = basename($decrypted);
                                array_push($finalTotal, $nameFile);
                            } else {
                                throw new Exception(
                                    "Error al eliminar el archivo $archivo del servidor $remoteDir.",
                                    "10000"
                                );
                            }
                        } else {
                            throw new Exception("Error al desencriptar el archivo: " . $fechaL2);
                        }
                    } else {
                        throw new Exception("Error al descargar el archivo $archivo desde $remoteFile.", "10000");
                    }
                }
            } else {
                throw new Exception(
                    "Error al conectar al servidor SFTP $this->ssh_server_fp en el puerto $this->ssh_port. Verifica las credenciales.",
                    "10000"
                );
            }
            return $finalTotal;
        } catch (Exception $e) {
            if ($_ENV['debug']) {
                print_r('H2HBCP GET ERROR: ');
                print_r(PHP_EOL);
                print_r($e);
            }
            syslog(LOG_WARNING, "H2HBCP GET ERROR: " . $fechaL2 . ': ' . json_encode($e));
            return "ERROR";
        }
    }

    /**
     * Desencripta un archivo utilizando GPG (GNU Privacy Guard).
     *
     * Este metodo realiza las siguientes acciones:
     * - Configura el entorno de GPG.
     * - Importa la clave privada desde un archivo.
     * - Desencripta el contenido del archivo encriptado.
     * - Guarda el contenido desencriptado en un nuevo archivo.
     *
     * @param string $localDecryptedFile Ruta local del archivo encriptado que se desea desencriptar.
     * @param string $fechaL2            Fecha utilizada para el registro de logs y procesamiento.
     *
     * @return string Ruta del archivo desencriptado o "ERROR" si ocurre un fallo.
     * @throws Exception Si ocurre un error durante la importación de la clave, lectura o desencriptación del archivo.
     */
    public function decryptFileWithGnupg($localDecryptedFile, $fechaL2)
    {
        try {
            putenv('GNUPGHOME=/usr/share/httpd/.gnupg');
            $gpg = new \gnupg();

            // Lanzar una excepción si ocurre un error
            $gpg->seterrormode(\gnupg::ERROR_EXCEPTION);

            // Ruta a la clave private (en formato PGP)
            $privateKeyPath = $this->pgp_private_key;

            // Importar la clave desde el archivo .asc (si es necesario)
            $importResult = $gpg->import(file_get_contents($privateKeyPath));

            if ( ! $importResult || ! isset($importResult['fingerprint'])) {
                throw new Exception("No se pudo importar la clave desde el archivo .asc");
            }

            // Establecer la clave privada importada para desencriptar
            $gpg->adddecryptkey($importResult['fingerprint'], $this->passphrase);

            // Lee el contenido del archivo encriptado
            $encryptedContent = file_get_contents($localDecryptedFile);

            if ($encryptedContent === false) {
                throw new Exception("No se pudo leer el archivo encriptado: $localDecryptedFile");
            }

            // Intenta desencriptar el contenido
            $decryptedContent = $gpg->decrypt($encryptedContent);

            if ($decryptedContent === false) {
                // Si falla la desencriptación
                throw new Exception("Error al desencriptar el archivo: " . $gpg->geterror());
            }

            // Define la ruta para guardar el archivo desencriptado
            $decryptedFilePath = str_replace('.txt', '_dec.txt', $localDecryptedFile);

            // Guarda el contenido desencriptado en un archivo
            file_put_contents($decryptedFilePath, $decryptedContent);

            syslog(LOG_WARNING, "H2HBCP DEC DATA: " . "Archivo desencriptado y guardado en: " . $decryptedFilePath);

            return $decryptedFilePath;
        } catch (Exception $e) {
            if ($_ENV['debug']) {
                print_r('H2HBCP DEC ERROR: ');
                print_r(PHP_EOL);
                print_r($e);
            }
            syslog(LOG_WARNING, "H2HBCP DEC ERROR: " . $fechaL2 . ': ' . json_encode($e));
            return "ERROR";
        }
    }

    /**
     * Prueba la conexión al servidor SFTP.
     *
     * Este metodo realiza las siguientes acciones:
     * - Establece una conexión SFTP con el servidor.
     * - Verifica si la conexión es exitosa.
     * - Comprueba la existencia de una ruta específica en el servidor.
     * - Lista los archivos en las carpetas remotas IN y OUT.
     * - Lee el contenido de los archivos listados.
     * - Devuelve un JSON con la información de los archivos y su contenido.
     *
     * @return string JSON con los detalles de los archivos en las carpetas IN y OUT,
     *                o un mensaje de error si la conexión falla.
     */
    public function probarConexion()
    {
        $sftp = new SFTP($this->ssh_server_fp, $this->ssh_port);

        try {
            if ($sftp->login($this->ssh_auth_user, $this->ssh_auth_pws)) {
                echo "Conexión exitosa al servidor SFTP $this->ssh_server_fp en el puerto $this->ssh_port.\n";

                $ruta = '/sftp/FTINTP01/TLC_PagosMasivos/IN/';
                if ($sftp->is_dir($ruta)) {
                    echo "La ruta '$ruta' existe en el servidor SFTP.\n";
                } else {
                    echo "La ruta '$ruta' no existe en el servidor SFTP.\n";
                }

                // Listar archivos en las carpetas
                $remoteDirIN = '/sftp/FTINTP01/TLC_PagosMasivos/IN/';
                $remoteDirOUT = '/sftp/FTINTP01/TLC_PagosMasivos/OUT/';

                $archivosIN = $sftp->nlist($remoteDirIN) ?: [];
                $archivosOUT = $sftp->nlist($remoteDirOUT) ?: [];

                if ($archivosIN === false) {
                    echo "Error al listar archivos en $remoteDirIN.\n";
                }
                if ($archivosOUT === false) {
                    echo "Error al listar archivos en $remoteDirOUT.\n";
                }

                // Leer el contenido de los archivos
                $contenidoIN = $this->leerArchivosSFTP($sftp, $remoteDirIN, $archivosIN);
                $contenidoOUT = $this->leerArchivosSFTP($sftp, $remoteDirOUT, $archivosOUT);

                // Retornar en formato JSON para fácil manejo
                return json_encode([
                    'IN' => [
                        'archivos' => $archivosIN,
                        'contenido' => $contenidoIN
                    ],
                    'OUT' => [
                        'archivos' => $archivosOUT,
                        'contenido' => $contenidoOUT
                    ]
                ], JSON_PRETTY_PRINT);
            } else {
                return "Error al conectar al servidor SFTP $this->ssh_server_fp en el puerto $this->ssh_port. Verifica las credenciales.\n";
            }
        } catch (Exception $e) {
            return "Excepción lanzada: " . $e->getMessage() . "\n";
        }
    }

    /**
     * Lee el contenido de los archivos en un directorio remoto SFTP.
     *
     * Este metodo realiza las siguientes acciones:
     * - Abre cada archivo en el directorio remoto especificado.
     * - Lee el contenido de los archivos en bloques de 4KB.
     * - Devuelve un arreglo asociativo con los nombres de los archivos como claves
     *   y su contenido como valores.
     *
     * @param SFTP   $sftp       Conexión SFTP establecida.
     * @param string $directorio Ruta del directorio remoto en el servidor SFTP.
     * @param array  $archivos   Lista de nombres de archivos en el directorio remoto.
     *
     * @return array Arreglo asociativo con el contenido de los archivos.
     */
    private function leerArchivosSFTP($sftp, $directorio, $archivos)
    {
        $contenidoArchivos = [];

        foreach ($archivos as $archivo) {
            $rutaArchivo = "ssh2.sftp://$sftp$directorio$archivo";
            $stream = fopen($rutaArchivo, 'r');

            if ( ! $stream) {
                echo "Error al abrir el archivo: $archivo\n";
                continue;
            }

            $contenido = '';
            while ( ! feof($stream)) {
                $contenido .= fread($stream, 4096); // Leer en bloques de 4KB
            }

            fclose($stream);
            $contenidoArchivos[$archivo] = $contenido;
        }

        return $contenidoArchivos;
    }
}
