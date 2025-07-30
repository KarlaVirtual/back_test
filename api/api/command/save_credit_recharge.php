<?php

use Backend\dto\ApiTransaction;
use Backend\dto\Area;
use Backend\dto\Bono;
use Backend\dto\BonoDetalle;
use Backend\dto\BonoInterno;
use Backend\dto\BonoLog;
use Backend\dto\Cargo;
use Backend\dto\Categoria;
use Backend\dto\CategoriaProducto;
use Backend\dto\CentroCosto;
use Backend\dto\Clasificador;
use Backend\dto\CodigoPromocional;
use Backend\dto\Competencia;
use Backend\dto\CompetenciaPuntos;
use Backend\dto\Concepto;
use Backend\dto\Concesionario;
use Backend\dto\Consecutivo;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\ContactoComercial;
use Backend\dto\ContactoComercialLog;
use Backend\dto\CuentaCobro;
use Backend\dto\CuentaContable;
use Backend\dto\CupoLog;
use Backend\dto\Descarga;
use Backend\dto\DocumentoUsuario;
use Backend\dto\Egreso;
use Backend\dto\Empleado;
use Backend\dto\FlujoCaja;
use Backend\dto\Flujocajafact;
use Backend\dto\Ingreso;
use Backend\dto\IntApuesta;
use Backend\dto\IntApuestaDetalle;
use Backend\dto\IntCompetencia;
use Backend\dto\IntDeporte;
use Backend\dto\IntEquipo;
use Backend\dto\IntEvento;
use Backend\dto\IntEventoApuesta;
use Backend\dto\IntEventoApuestaDetalle;
use Backend\dto\IntEventoDetalle;
use Backend\dto\IntRegion;
use Backend\dto\ItTicketEnc;
use Backend\dto\LenguajeMandante;
use Backend\dto\Mandante;
use Backend\dto\MandanteDetalle;
use Backend\dto\Pais;
use Backend\dto\PaisMandante;
use Backend\dto\Perfil;
use Backend\dto\PerfilSubmenu;
use Backend\dto\ProdMandanteTipo;
use Backend\dto\Producto;
use Backend\dto\ProductoComision;
use Backend\dto\ProductoInterno;
use Backend\dto\ProductoTercero;
use Backend\dto\ProductoterceroUsuario;
use Backend\dto\PromocionalLog;
use Backend\dto\Proveedor;
use Backend\dto\ProveedorMandante;
use Backend\dto\ProveedorTercero;
use Backend\dto\PuntoVenta;
use Backend\dto\ProductoMandante;
use Backend\dto\Registro;
use Backend\dto\SaldoUsuonlineAjuste;
use Backend\dto\Submenu;
use Backend\dto\TransaccionApi;
use Backend\dto\TransaccionApiMandante;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransaccionSportsbook;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransprodLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioAlerta;
use Backend\dto\UsuarioBanco;
use Backend\dto\UsuarioBloqueado;
use Backend\dto\UsuarioBono;
use Backend\dto\UsuarioCierrecaja;
use Backend\dto\UsuarioComision;
use Backend\dto\UsuarioConfig;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioLog;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioMensaje;
use Backend\dto\UsuarioNotas;
use Backend\dto\UsuarioPerfil;
use Backend\dto\UsuarioPremiomax;
use Backend\dto\UsuarioPublicidad;
use Backend\dto\UsuarioRecarga;
use Backend\dto\UsuarioRecargaResumen;
use Backend\dto\UsuarioRetiroResumen;
use Backend\dto\UsuarioSaldo;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioTokenInterno;
use Backend\dto\UsucomisionResumen;
use Backend\dto\UsumarketingResumen;
use Backend\imports\Google\GoogleAuthenticator;
use Backend\integrations\mensajeria\Okroute;
use Backend\integrations\payout\LPGSERVICES;
use Backend\mysql\AreaMySqlDAO;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\mysql\BonoLogMySqlDAO;
use Backend\mysql\CargoMySqlDAO;
use Backend\mysql\CategoriaMySqlDAO;
use Backend\mysql\CategoriaProductoMySqlDAO;
use Backend\mysql\CentroCostoMySqlDAO;
use Backend\mysql\CiudadMySqlDAO;
use Backend\mysql\ClasificadorMySqlDAO;
use Backend\mysql\CodigoPromocionalMySqlDAO;
use Backend\mysql\CompetenciaPuntosMySqlDAO;
use Backend\mysql\ConceptoMySqlDAO;
use Backend\mysql\ConcesionarioMySqlDAO;
use Backend\mysql\ConsecutivoMySqlDAO;
use Backend\mysql\ContactoComercialLogMySqlDAO;
use Backend\mysql\ContactoComercialMySqlDAO;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\CuentaContableMySqlDAO;
use Backend\mysql\CupoLogMySqlDAO;
use Backend\mysql\DocumentoUsuarioMySqlDAO;
use Backend\mysql\EgresoMySqlDAO;
use Backend\mysql\EmpleadoMySqlDAO;
use Backend\mysql\FlujoCajaMySqlDAO;
use Backend\mysql\IngresoMySqlDAO;
use Backend\mysql\IntApuestaDetalleMySqlDAO;
use Backend\mysql\IntApuestaMySqlDAO;
use Backend\mysql\IntCompetenciaMySqlDAO;
use Backend\mysql\IntDeporteMySqlDAO;
use Backend\mysql\IntEventoApuestaDetalleMySqlDAO;
use Backend\mysql\IntEventoApuestaMySqlDAO;
use Backend\mysql\IntEventoDetalleMySqlDAO;
use Backend\mysql\IntEventoMySqlDAO;
use Backend\mysql\IntRegionMySqlDAO;
use Backend\mysql\LenguajeMandanteMySqlDAO;
use Backend\mysql\MandanteDetalleMySqlDAO;
use Backend\mysql\MandanteMySqlDAO;
use Backend\mysql\PerfilSubmenuMySqlDAO;
use Backend\mysql\ProdMandanteTipoMySqlDAO;
use Backend\mysql\ProductoComisionMySqlDAO;
use Backend\mysql\ProductoTerceroMySqlDAO;
use Backend\mysql\ProductoterceroUsuarioMySqlDAO;
use Backend\mysql\ProveedorMandanteMySqlDAO;
use Backend\mysql\ProveedorMySqlDAO;
use Backend\mysql\ProveedorTerceroMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\mysql\RegistroMySqlDAO;
use Backend\mysql\SaldoUsuonlineAjusteMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\mysql\UsuarioAlertaMySqlDAO;
use Backend\mysql\UsuarioBancoMySqlDAO;
use Backend\mysql\UsuarioBloqueadoMySqlDAO;
use Backend\mysql\UsuarioBonoMySqlDAO;
use Backend\mysql\UsuarioCierrecajaMySqlDAO;
use Backend\mysql\UsuarioConfigMySqlDAO;
use Backend\mysql\UsuarioConfiguracionMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\mysql\UsuarioMandanteMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\ProductoMySqlDAO;
use Backend\mysql\ProductoMandanteMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\UsuarioNotasMySqlDAO;
use Backend\mysql\UsuarioPerfilMySqlDAO;
use Backend\mysql\UsuarioPremiomaxMySqlDAO;
use Backend\mysql\UsuarioPublicidadMySqlDAO;
use Backend\mysql\UsuarioRecargaMySqlDAO;
use Backend\mysql\UsuarioTokenInternoMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\mysql\UsucomisionResumenMySqlDAO;
use Backend\websocket\WebsocketUsuario;

/**
 * command/save_credit_recharge
 *
 * Generar desde un punto de venta una recarga
 *
 * @param string $Amount : Monto a recargar
 * @param string $Id : Id del cliente a recargar
 *
 * El objeto $response es un array con los siguientes atributos:
 *  - *code* (int): Codigo de error desde el proveedor
 *  - *rid* (string): Contiene el mensaje de error.
 *  - *data* (array): Contiene el id de la recarga y el pdf.
 *  - *Pdf* (array): Pdf de la recarga encriptado en base 64
 *  - *PdfPOS* (array): Pdf de la recarga encriptado en base 64
 *
 * @throws Exception Error en los parametros enviados
 * @throws Exception Punto de venta no tiene cupo disponible para realizar la recarga
 * @throws Exception Error General
 *
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* Creación de objetos para gestionar usuarios y sus perfiles a partir de un JSON. */
$UsuarioMandante = new UsuarioMandante($json->session->usuario);
$UsuarioPuntoVenta = new Usuario($UsuarioMandante->getUsuarioMandante());
$UsuarioPerfilUsuario = new UsuarioPerfil($UsuarioMandante->getUsuarioMandante());


if (true) {


    /* Asignación de variables desde un objeto JSON y creación de un perfil de usuario. */
    $Amount = $json->params->amountRecharge;
//$Amount = -$Amount;
    $Id = $json->params->nroClient;
    $tipo = 'E';

    $UsuarioPerfil = new UsuarioPerfil($Id);

    /* Se crea una nueva instancia de la clase Usuario utilizando un identificador específico. */
    $Usuario = new Usuario($Id);

    if ($UsuarioPerfil->getPerfilId() == "USUONLINE" && $Usuario->mandante == $UsuarioPuntoVenta->mandante) {


        /* Valida cantidad y disponibilidad de crédito en el punto de venta antes de proceder. */
        if ($Amount <= 0) {
            throw new Exception("Error en los parametros enviados", "100001");
        }

        $PuntoVenta = new PuntoVenta("", $UsuarioPuntoVenta->puntoventaId);

        if (floatval($PuntoVenta->getCreditosBase()) - floatval($Amount) < 0) {
            throw new Exception("Punto de venta no tiene cupo disponible para realizar la recarga", "100002");
        }


        /* Verifica límites de depósito en entornos de desarrollo y lanza excepción si excede. */
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if ($ConfigurationEnvironment->isDevelopment()) {

            $UsuarioConfiguracion = new UsuarioConfiguracion();

            $UsuarioConfiguracion->setUsuarioId($Id);
            $result = $UsuarioConfiguracion->verifyLimitesDeposito($Amount);

            if ($result != '0') {
                throw new Exception("Limite de deposito", $result);
            }
        }

        /*$Consecutivo = new Consecutivo("", "REC", "");


        $consecutivo_recarga = $Consecutivo->numero;

        /**
         * Actualizamos consecutivo Recarga
         */
        /*
                $consecutivo_recarga++;

                $ConsecutivoMySqlDAO = new ConsecutivoMySqlDAO();

                $Consecutivo->setNumero($consecutivo_recarga);


                $ConsecutivoMySqlDAO->update($Consecutivo);

                $ConsecutivoMySqlDAO->getTransaction()->commit();*/


        /* Se inicializa un objeto y se asignan valores para una recarga de usuario. */
        $rowsUpdate = 0;

        $UsuarioRecarga = new UsuarioRecarga();
        $UsuarioRecarga->setRecargaId($consecutivo_recarga);
        $UsuarioRecarga->setUsuarioId($Id);
        $UsuarioRecarga->setFechaCrea(date('Y-m-d H:i:s'));

        /* configura un nuevo objeto de recarga con diversos atributos. */
        $UsuarioRecarga->setPuntoventaId($UsuarioMandante->getUsuarioMandante());
        $UsuarioRecarga->setValor($Amount);
        $UsuarioRecarga->setPorcenRegaloRecarga(0);
        $UsuarioRecarga->setDirIp(0);
        $UsuarioRecarga->setPromocionalId(0);
        $UsuarioRecarga->setValorPromocional(0);

        /* Se configuran properties de un objeto "UsuarioRecarga" con valores iniciales. */
        $UsuarioRecarga->setHost(0);
        $UsuarioRecarga->setMandante($Usuario->mandante);
        $UsuarioRecarga->setPedido(0);
        $UsuarioRecarga->setPorcenIva(0);
        $UsuarioRecarga->setMediopagoId(0);
        $UsuarioRecarga->setValorIva(0);

        /* configura el estado de usuario y gestiona transacciones en una base de datos. */
        $UsuarioRecarga->setEstado('A');

        $UsuarioRecargaMySqlDAO = new UsuarioRecargaMySqlDAO();
        $Transaction = $UsuarioRecargaMySqlDAO->getTransaction();
        $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO($Transaction);


        $Registro = new Registro('', $Usuario->usuarioId);


        /* Códigos para cargar ciudades y contar depósitos de un usuario en MySQL. */
        $CiudadMySqlDAO = new CiudadMySqlDAO();

        $Ciudad = $CiudadMySqlDAO->load($Registro->ciudadId);
        $CiudadPuntoVenta = $CiudadMySqlDAO->load($PuntoVenta->ciudadId);


        $detalleDepositos = $UsuarioRecargaMySqlDAO->querySQL("SELECT COUNT(*) cantidad_depositos FROM usuario_recarga WHERE usuario_id='" . $Usuario->usuarioId . "'");


        /* crea un arreglo con detalles de un depósito y datos del usuario. */
        $detalleDepositos = $detalleDepositos[0][".cantidad_depositos"];


        $detalles = array(
            "Depositos" => $detalleDepositos,
            "DepositoEfectivo" => true,
            "MetodoPago" => 0,
            "ValorDeposito" => $UsuarioRecarga->getValor(),
            "PaisPV" => $UsuarioPuntoVenta->paisId,
            "DepartamentoPV" => $CiudadPuntoVenta->deptoId,
            "CiudadPV" => $PuntoVenta->ciudadId,
            "PuntoVenta" => $UsuarioPuntoVenta->puntoventaId,
            "PaisUSER" => $Usuario->paisId,
            "DepartamentoUSER" => $Ciudad->deptoId,
            "CiudadUSER" => $Registro->ciudadId,
            "MonedaUSER" => $Usuario->moneda,

        );


        /* Se crea un bono interno y se agrega detalle, luego se inserta en la base de datos. */
        $BonoInterno = new BonoInterno();
        $detalles = json_decode(json_encode($detalles));

        $respuestaBono = $BonoInterno->agregarBono('2', $Usuario->usuarioId, $Usuario->mandante, $detalles, $Transaction);

        $recarga_id = $UsuarioRecargaMySqlDAO->insert($UsuarioRecarga);

        //$UsuarioRecarga->setRecargaId($consecutivo_recarga);

        /* gestiona una recarga de crédito, verificando la actualización correcta en la base de datos. */
        $consecutivo_recarga = $UsuarioRecarga->recargaId;

        $rowsUpdate = 0;

        $rowsUpdate = $Usuario->credit($Amount, $Transaction);

        if ($rowsUpdate == null || $rowsUpdate <= 0) {
            throw new Exception("Error General", "100000");
        }


        /* Se inicializa un objeto de historial de usuario y se establece información. */
        $rowsUpdate = 0;

        $UsuarioHistorial = new UsuarioHistorial();
        $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
        $UsuarioHistorial->setDescripcion('');
        $UsuarioHistorial->setMovimiento('E');

        /* Se establece un historial de usuario con datos y transacción específicos. */
        $UsuarioHistorial->setUsucreaId(0);
        $UsuarioHistorial->setUsumodifId(0);
        $UsuarioHistorial->setTipo(10);
        $UsuarioHistorial->setValor($Amount);
        $UsuarioHistorial->setExternoId($UsuarioRecarga->getRecargaId());

        $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);

        /* Inserta un historial de usuario y maneja errores en la operación. */
        $rowsUpdate = $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);

        if ($rowsUpdate == null || $rowsUpdate <= 0) {
            throw new Exception("Error General", "100000");
        }

        $rowsUpdate = 0;

        /* Condiciona actualizaciones de balance según el perfil del usuario y tipo de transacción. */
        if ($UsuarioPerfilUsuario->perfilId == "CONCESIONARIO" or $UsuarioPerfilUsuario->perfilId == "CONCESIONARIO2" or $UsuarioPerfilUsuario->perfilId == "PUNTOVENTA" or $UsuarioPerfilUsuario->perfilId == "CAJERO") {

            if ($tipo == "S") {
                $rowsUpdate = $PuntoVenta->setBalanceCreditosBase($Amount, $Transaction);

            } else {
                $rowsUpdate = $PuntoVenta->setBalanceCreditosBase(-$Amount, $Transaction);
            }

            //$PuntoVenta->update($PuntoVenta);

        }


        /* valida si no se actualizaron filas, lanzando una excepción en caso afirmativo. */
        if ($rowsUpdate == null || $rowsUpdate <= 0) {
            throw new Exception("Error General", "100000");
        }

        $rowsUpdate = 0;

        $FlujoCaja = new FlujoCaja();

        /* establece atributos de un objeto FlujoCaja con datos de fecha y usuario. */
        $FlujoCaja->setFechaCrea(date('Y-m-d'));
        $FlujoCaja->setHoraCrea(date('H:i'));
        $FlujoCaja->setUsucreaId($UsuarioMandante->getUsuarioMandante());
        $FlujoCaja->setTipomovId('E');
        $FlujoCaja->setValor($UsuarioRecarga->getValor());
        $FlujoCaja->setRecargaId($UsuarioRecarga->getRecargaId());

        /* Establece propiedades en el objeto FlujoCaja según el usuario y condiciones dadas. */
        $FlujoCaja->setMandante($UsuarioRecarga->getMandante());
        $FlujoCaja->setTraslado('N');
        $FlujoCaja->setFormapago1Id(1);
        $FlujoCaja->setCuentaId('0');

        if ($FlujoCaja->getFormapago2Id() == "") {
            $FlujoCaja->setFormapago2Id(0);
        }


        /* verifica y asigna valor cero si forma1 o forma2 están vacíos. */
        if ($FlujoCaja->getValorForma1() == "") {
            $FlujoCaja->setValorForma1(0);
        }

        if ($FlujoCaja->getValorForma2() == "") {
            $FlujoCaja->setValorForma2(0);
        }


        /* establece valores predeterminados si las propiedades son vacías. */
        if ($FlujoCaja->getCuentaId() == "") {
            $FlujoCaja->setCuentaId(0);
        }

        if ($FlujoCaja->getPorcenIva() == "") {
            $FlujoCaja->setPorcenIva(0);
        }


        /* Se verifica el valor del IVA y se inserta en la base de datos. */
        if ($FlujoCaja->getValorIva() == "") {
            $FlujoCaja->setValorIva(0);
        }

        $FlujoCajaMySqlDAO = new FlujoCajaMySqlDAO($Transaction);


        $rowsUpdate = $FlujoCajaMySqlDAO->insert($FlujoCaja);


        /* Inserta un historial de usuario si se actualizan filas y confirma la transacción. */
        if ($rowsUpdate > 0) {

            $UsuarioHistorial = new UsuarioHistorial();
            $UsuarioHistorial->setUsuarioId($UsuarioMandante->getUsuarioMandante());
            $UsuarioHistorial->setDescripcion('');
            $UsuarioHistorial->setMovimiento('S');
            $UsuarioHistorial->setUsucreaId(0);
            $UsuarioHistorial->setUsumodifId(0);
            $UsuarioHistorial->setTipo(10);
            $UsuarioHistorial->setValor($UsuarioRecarga->getValor());
            $UsuarioHistorial->setExternoId($UsuarioRecarga->getRecargaId());

            $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
            $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial, '1');


            $Transaction->commit();
        } else {
            /* Lanza una excepción con un mensaje y un código de error específico. */

            throw new Exception("Error General", "100000");
        }

        /* <tr style="width: 50%; display: inline-block;">
                 <td align="center" valign="top"><img style="width: 140px; padding-left: 20px;"
                                                      src="' . $Mandante->logoPdf . '" alt="logo">
                 </td>
                 <td align="center" valign="top" style="display: block;text-align:center;"><font
                         style="text-align:center;font-size:20px;font-weight:bold;">RECIBO<br>DE RECARGA</font>
                 </td>
             </tr>*/


        /* Crea un objeto Mandante utilizando el mandante del usuario proporcionado. */
        $Mandante = new Mandante($Usuario->mandante);
        $pdf = '<head>
    <style>
        body {
            font-family: \'Roboto\', sans-serif;
            text-decoration: none;
            font-size: 14px;
        }

        tr td:first-child {
            text-align: left;
        }

        tr td:last-child {
            text-align: right;
        }
    </style>
</head>
<body>
<div style="width:330px; border:1px solid grey; padding: 15px;">

        <div style="text-align:center;font-size:12px;"><font
                    style="text-align:center;font-size:20px;font-weight:bold;">' . $Mandante->nombre . '</font>
        </div>
        <div style="text-align:center;font-size:12px;"><font
                    style="text-align:center;font-size:20px;font-weight:bold;">RECIBO DE RECARGA</font>
        </div>
    <table style="width:100%;height: 355px;">
        <tbody>
        
        <tr>
            <td style="border-top: 2px solid black; padding-top: 10px;width:50%;" align="left" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">Recibo de Recarga No.:</font>
            </td>
            <td style="border-top: 2px solid black; padding-top: 10px;" align="right" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">' . $consecutivo_recarga . ' </font>
            </td>
        </tr>
        
        <tr>
            <td align="left" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;width:50%;">Fecha:</font>
            </td>
            <td align="right" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">' . $UsuarioRecarga->getFechaCrea() . ' </font>
            </td>
        </tr>
        <tr>
            <td align="left" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;width:50%;">Punto de Venta:</font>
            </td>
            <td align="right" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">' . $UsuarioPuntoVenta->nombre . ' </font>
            </td>
        </tr>
        <tr>
            <td align="left" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;width:50%;">No. de Cliente</font>
            </td>
            <td align="right" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">' . $Usuario->usuarioId . ' </font>
            </td>
        </tr>
        <tr>
            <td align="left" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;width:50%;">Nombre Cliente:</font>
            </td>
            <td align="right" valign="top">
                <font style="padding-left:5px;text-align:left;font-size:16px;font-weight:normal;">' . $Usuario->nombre . '</font>
            </td>
        </tr>
        
        <tr>
            <td align="center" valign="top">
                <div style="height:1px;">&nbsp;</div>
            </td>
        </tr>
        <tr>
            <td align="left" valign="top"><font
                    style="padding-left:5px;text-align:left;font-size:18px;font-weight:bold;width:50%;">Valor recarga :</font>
            </td>
            <td align="right" valign="top"><font
                    style="padding-left:5px;text-align:left;font-size:18px;">' . $Usuario->moneda . ' ' . $UsuarioRecarga->getValor() . '</font></td>
        </tr>
        
        <tr>
            <td align="center" valign="top">
                <div style="height:1px;">&nbsp;</div>
            </td>
        </tr>
                </tbody>
    </table>
 <div style="text-align:center;font-size:12px;">' . $Mandante->descripcion . '</font>
        </div>
        <div style="text-align:center;font-size:12px;">Disfruta del juego en vivo</font>
        </div>
        ';


        /* muestra un mensaje en PDF si el país del usuario es 173. */
        if ($Usuario->paisId == 173) {
            $pdf .= '
        <div style="text-align:center;font-size:12px;">Interplay Word SAC RUC: 20602190103</font>
        </div>';

        }


        /* Genera un código PDF que incluye un código de barras basado en un ID de recarga. */
        $pdf .= '

    <div style="width:100%; padding-top: 20px;">
        <div id="barcodeTarget" class="barcodeTarget" style="padding: 0px; overflow: auto; width: 324px;">
      <div class="barcodecell" style="  text-align: center;"><barcode code="' . $UsuarioRecarga->getRecargaId() . '" type="I25" class="barcode" /></div>
  </div>
    </div>
</div>
</body>

';


        require_once __DIR__ . "/../../src/imports/mpdf6.1/mpdf.php";


        /* Configura mPDF para crear documentos PDF con márgenes y visualización específica. */
        $mpdf = new mPDF('c', array(80, 150), 0, 0, 0, 0);
//$mpdf = new mPDF('c', 'A4-L');

        $mpdf->mirrorMargins = 1; // Use different Odd/Even headers and footers and mirror margins (1 or 0)

        $mpdf->SetDisplayMode('fullpage', 'two');

// LOAD a stylesheet
//$stylesheet = file_get_contents('mdpdf/stylemdpdf.css');
//$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text


        /* Genera un PDF y crea una carpeta si no existe. */
        $mpdf->WriteHTML($pdf);


        if (!is_dir(__DIR__ . "/pdf/")) {

            mkdir(__DIR__ . "/pdf/", 0777);
        }

        /* Genera un archivo PDF y obtiene su extensión a partir de su ruta. */
        $pdfFile = "ds" . $consecutivo_recarga . ".pdf";

        $mpdf->Output(__DIR__ . "/pdf/" . $pdfFile, "F");

        $path = __DIR__ . '/pdf/' . $pdfFile;


        $type = pathinfo($path, PATHINFO_EXTENSION);

        /* lee un archivo y lo convierte a formato base64 para respuestas. */
        $data = file_get_contents($path);
        $base64 = 'data:application/' . $type . ';base64,' . base64_encode($data);

        $response["Pdf"] = base64_encode($data);
        $response["PdfPOS"] = base64_encode($data);

        $response = array();

        /* asigna valores a un array de respuesta en formato JSON. */
        $response["code"] = 0;
        $response["rid"] = $json->rid;

        $response["data"] =
            array(
                "htmlPOS" => $pdf,
                'id' => $recarga_id
            );


        /* elimina un archivo PDF específico del directorio indicado. */
        unlink(__DIR__ . '/pdf/' . $pdfFile);


    } else {
        /* Lanza una excepción con un mensaje de error y un código específico. */

        throw new Exception("Error General", "100000");
    }


}