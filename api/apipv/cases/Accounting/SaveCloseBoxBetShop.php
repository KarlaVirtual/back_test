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
 * Cierra la caja de un punto de venta.
 *
 * @param array $params Parámetros necesarios para cerrar la caja, incluyendo:
 * @param $params ->usuario2: ID del usuario.
 * @param $params ->fecha: Fecha del cierre.
 * @param $params ->SkeepRows: Número de filas a omitir.
 * @param $params ->MaxRows: Número máximo de filas a obtener.
 * @param $params ->grouping: Agrupación de datos.
 * @param $params ->usuarioId: ID del usuario.
 * @param $params ->TipoTickets: Objeto Clasificador para tickets.
 * @param $params ->TipoPremios: Objeto Clasificador para premios.
 * @param $params ->TipoNotasRetiros: Objeto Clasificador para notas de retiro.
 * @param $params ->TipoRecargas: Objeto Clasificador para recargas.
 * @param $params ->TipoDineroInicial: Objeto Clasificador para dinero inicial.
 *
 *
 * @return array $response Respuesta con los siguientes valores:
 *                         - `Pdf`: PDF generado en base64.
 *                         - `Pdf2`: HTML del PDF.
 *                         - `PdfPOS`: PDF generado en base64 para POS.
 *                         - `PdfPOS2`: HTML del PDF para POS.
 *                         - `HasError`: Indica si hubo un error.
 *                         - `AlertType`: Tipo de alerta.
 *                         - `AlertMessage`: Mensaje de alerta.
 *                         - `ModelErrors`: Errores del modelo.
 */

try {

    /* Se crean objetos de usuario y se obtienen fechas actuales en diferentes formatos. */
    $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);
    $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

    $fechaHoy = date("Y-m-d");
    $fechaHoyConHora = date("Y-m-d 00:00:00");
    $fechaHoyConHoraSegundos = date("Y-m-d H:i:s");


    /* Código que verifica condición de sesión y define variables de fecha, sin ejecución. */
    if ($_SESSION['usuario2'] == '10119' && false) {
        $UsuarioMandante = new UsuarioMandante('39445');
        $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

        $fechaHoy = '2019-11-09';
        $fechaHoyConHora = '2019-11-09 00:00:00';
        $fechaHoyConHoraSegundos = '2019-11-09 22:00:00';

    }


    /* define varios objetos "Clasificador" con distintos tipos de datos. */
    $TipoTickets = new Clasificador("", "ACCBETTICKET");
    $TipoPremios = new Clasificador("", "ACCWINTICKET");
    $TipoNotasRetiros = new Clasificador("", "ACCPAYWD");
    $TipoRecargas = new Clasificador("", "ACCREC");
    $TipoDineroInicial = new Clasificador("", "ACCAMOUNTDAY");

    $TipoTicketsId = 0;

    /* Variables inicializadas en cero para gestionar diferentes tipos de premios y transacciones financieras. */
    $TipoPremiosId = 0;
    $TipoNotasRetirosId = 0;
    $TipoRecargasId = 0;
    $dineroInicial = 0;

    $otrosIngresosTarjetasCreditos = 0;


    /* Define reglas de filtrado para productos y proveedores en una consulta. */
    $rules = [];
    array_push($rules, array("field" => "producto_tercero.tipo_id", "data" => "'" . $TipoTickets->getClasificadorId() . "','" . $TipoPremios->getClasificadorId() . "','" . $TipoNotasRetiros->getClasificadorId() . "','" . $TipoRecargas->getClasificadorId() . "'", "op" => "in"));
    array_push($rules, array("field" => "proveedor_tercero.pais_id", "data" => $UsuarioMandante->getPaisId(), "op" => "eq"));


    $filtro = array("rules" => $rules, "groupOp" => "AND");

    /* Codifica un filtro a JSON, obtiene datos de productos y los decodifica. */
    $json = json_encode($filtro);

    $ProductoTercero = new ProductoTercero();

    $data = $ProductoTercero->getProductoTercerosCustom("  producto_tercero.* ", "producto_tercero.productoterc_id", "asc", 0, 1000, $json, true);

    $data = json_decode($data);

    /* Inicializa un arreglo vacío llamado "final" para almacenar datos posteriores. */
    $final = [];
    foreach ($data->data as $key => $value) {
        switch ($value->{"producto_tercero.tipo_id"}) {
            case $TipoTickets->getClasificadorId():
                /* asigna un ID de producto según el clasificador de tipos de tickets. */

                $TipoTicketsId = $value->{"producto_tercero.productoterc_id"};

                break;

            case $TipoPremios->getClasificadorId():
                /* asigna un ID de producto basado en la clasificación de premios. */

                $TipoPremiosId = $value->{"producto_tercero.productoterc_id"};

                break;

            case $TipoNotasRetiros->getClasificadorId():
                /* asigna un identificador de producto basado en un clasificador específico. */

                $TipoNotasRetirosId = $value->{"producto_tercero.productoterc_id"};

                break;

            case $TipoRecargas->getClasificadorId():
                /* asigna un valor a $TipoRecargasId según el clasificador de recargas. */

                $TipoRecargasId = $value->{"producto_tercero.productoterc_id"};

                break;
        }
    }


    /* crea un filtro con reglas para validar datos de usuario. */
    $rules = [];
    array_push($rules, array("field" => "usuario.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
//array_push($rules, array("field" => "usuario.usuario_id", "data" => 5703, "op" => "eq"));
//array_push($rules, array("field" => "usuario_cierrecaja.fecha_crea", "data" => (date("Y-m-d 00:00:00") . ' - 1 days'), "op" => "ge"));


    $filtro = array("rules" => $rules, "groupOp" => "AND");

    /* Convierte datos a JSON, consulta usuarios y decodifica el resultado. */
    $json = json_encode($filtro);

    $UsuarioCierrecaja = new UsuarioCierrecaja();

    $data = $UsuarioCierrecaja->getUsuarioCierrecajasCustom("  usuario.login,usuario_cierrecaja.* ", "usuario_cierrecaja.fecha_crea", "desc", 0, 1, $json, true);

    $data = json_decode($data);

    foreach ($data->data as $key => $value) {


        /* Se crea un array asociativo con datos del usuario extraídos de un objeto. */
        $array = [];


        $array["Id"] = $value->{"usuario_cierrecaja.usucierrecaja_id"};
        $array["User"] = $value->{"usuario_cierrecaja.usuario_id"};
        $array["UserName"] = $value->{"usuario.login"};

        /* asigna valores de un objeto a un array asociativo en PHP. */
        $array["Date"] = date('Y-m-d', strtotime($value->{"usuario_cierrecaja.fecha_cierre"}));
        $array["AmountBegin"] = $value->{"usuario_cierrecaja.dinero_inicial"};
        $array["ProperIncomes"] = $value->{"usuario_cierrecaja.ingresos_propios"};
        $array["ProperExpenses"] = $value->{"usuario_cierrecaja.egresos_propios"};
        $array["ProductsIncomes"] = $value->{"usuario_cierrecaja.ingresos_productos"};
        $array["ProductsExpenses"] = $value->{"usuario_cierrecaja.egresos_productos"};

        /* calcula el total del dinero inicial restando y sumando diferentes ingresos y egresos. */
        $array["OthersIncomes"] = $value->{"usuario_cierrecaja.ingresos_otros"};
        $array["OthersExpenses"] = $value->{"usuario_cierrecaja.egresos_otros"};
        $array["IncomesCreditCards"] = $value->{"usuario_cierrecaja.ingresos_tarjetacredito"};
        $array["Total"] = $array["AmountBegin"] + $array["ProperIncomes"] + $array["ProductsIncomes"] + $array["OthersIncomes"]
            - $array["ProperExpenses"] - $array["ProductsExpenses"] - $array["OthersExpenses"] - $array["IncomesCreditCards"];

        $dineroInicial = $array["Total"];

    }


    /* Inicializa variables para totalizar ingresos y egresos de diferentes categorías. */
    $TotalIngresosPropios = 0;
    $TotalEgresosPropios = 0;

    $TotalIngresosProductos = 0;
    $TotalEgresosProductos = 0;

    $TotalIngresosOtros = 0;

    /* Inicializa variables y asigna productos, gastos e ingresos desde los parámetros suministrados. */
    $TotalEgresosOtros = 0;
    $otrosIngresosTarjetasCreditos = 0;

    $products = $params->products;
    $expenses = $params->expenses;
    $incomes = $params->incomes;

    /* Se instancia un objeto DAO y se obtiene una transacción de la base de datos. */
    $IngresoMySqlDAO = new IngresoMySqlDAO();
    $Transaction = $IngresoMySqlDAO->getTransaction();


    foreach ($incomes as $income) {

        /* asigna propiedades de un ingreso a variables y crea una instancia de Ingreso. */
        $Concept = $income->Concept;
        $Description = $income->Description;
        $Reference = $income->Reference;
        $Value = $income->Value;

        $Ingreso = new Ingreso();

        /* Configura un objeto "Ingreso" con varios atributos como tipo, descripción y estado. */
        $Ingreso->setTipoId(0);
        $Ingreso->setDescripcion($Description);
        $Ingreso->setCentrocostoId(0);
        $Ingreso->setDocumento($Reference);
        $Ingreso->setEstado("A");
        $Ingreso->setValor($Value);

        /* establece parámetros para un objeto de ingreso en un sistema. */
        $Ingreso->setImpuesto(0);
        $Ingreso->setRetraccion(0);
        $Ingreso->setUsuarioId($Usuario->puntoventaId);
        $Ingreso->setConceptoId($Concept);
        $Ingreso->setProductotercId(0);
        $Ingreso->setUsucajeroId($UsuarioMandante->getUsuarioMandante());

        /* Establece valores iniciales y guarda un objeto Ingreso en la base de datos. */
        $Ingreso->setProveedortercId(0);

        $Ingreso->setUsucreaId(0);
        $Ingreso->setUsumodifId(0);


        $IngresoMySqlDAO->insert($Ingreso);


        /* Suma el valor a la variable TotalIngresosOtros acumulando ingresos adicionales. */
        $TotalIngresosOtros = $TotalIngresosOtros + $Value;

    }


    foreach ($products as $product) {

        /* Se inicializan variables y se crea una instancia de la clase Ingreso. */
        $Concept = 0;
        $Description = '';
        $Reference = '';
        $Value = $product->Bets;

        $Ingreso = new Ingreso();

        /* establece propiedades de un objeto "Ingreso" con datos específicos. */
        $Ingreso->setTipoId(0);
        $Ingreso->setDescripcion($Description);
        $Ingreso->setCentrocostoId(0);
        $Ingreso->setDocumento($Reference);
        $Ingreso->setEstado("A");
        $Ingreso->setValor($Value);

        /* Código para establecer propiedades de un objeto "Ingreso" con datos relacionados. */
        $Ingreso->setImpuesto(0);
        $Ingreso->setRetraccion(0);
        $Ingreso->setUsuarioId($Usuario->puntoventaId);
        $Ingreso->setConceptoId($Concept);
        $Ingreso->setProductotercId($product->ProductId);
        $Ingreso->setUsucajeroId($UsuarioMandante->getUsuarioMandante());

        /* Código para establecer IDs y realizar una inserción en la base de datos. */
        $Ingreso->setProveedortercId(0);

        $Ingreso->setUsucreaId(0);
        $Ingreso->setUsumodifId(0);


        $IngresoMySqlDAO->insert($Ingreso);


        /* Se suma el valor de ingresos de productos a un total acumulado. */
        $TotalIngresosProductos = $TotalIngresosProductos + $Value;

    }


    /* Se crea una instancia de EgresoMySqlDAO utilizando una transacción. */
    $EgresoMySqlDAO = new EgresoMySqlDAO($Transaction);

    foreach ($expenses as $expense) {

        /* Extrae propiedades de un objeto de gasto para crear un nuevo objeto Egreso. */
        $Concept = $expense->Concept;
        $Description = $expense->Description;
        $Reference = $expense->Reference;
        $Value = $expense->Value;

        $Egreso = new Egreso();

        /* Código para establecer propiedades de un objeto "Egreso" en programación. */
        $Egreso->setTipoId(0);
        $Egreso->setDescripcion($Description);
        $Egreso->setCentrocostoId(0);
        $Egreso->setDocumento($Reference);
        $Egreso->setEstado("A");
        $Egreso->setValor($Value);

        /* Se configuran propiedades de un objeto Egreso con datos específicos del usuario y concepto. */
        $Egreso->setImpuesto(0);
        $Egreso->setRetraccion(0);
        $Egreso->setUsuarioId($Usuario->puntoventaId);
        $Egreso->setConceptoId($Concept);
        $Egreso->setProductotercId(0);
        $Egreso->setUsucajeroId($UsuarioMandante->getUsuarioMandante());

        /* Se establece proveedor y usuario en cero antes de insertar un egreso en la base de datos. */
        $Egreso->setProveedortercId(0);

        $Egreso->setUsucreaId(0);
        $Egreso->setUsumodifId(0);


        $EgresoMySqlDAO->insert($Egreso);

        /* Suma el valor de $Value a la variable $TotalEgresosOtros, acumulando egresos. */
        $TotalEgresosOtros = $TotalEgresosOtros + $Value;

    }

    foreach ($products as $product) {

        /* Inicializa variables y crea una instancia de la clase Egreso. */
        $Concept = 0;
        $Description = '';
        $Reference = '';
        $Value = $product->Prize;

        $Egreso = new Egreso();

        /* Configura propiedades de un objeto Egreso con valores específicos para su registro. */
        $Egreso->setTipoId(0);
        $Egreso->setDescripcion($Description);
        $Egreso->setCentrocostoId(0);
        $Egreso->setDocumento($Reference);
        $Egreso->setEstado("A");
        $Egreso->setValor($Value);

        /* Código que configura un objeto 'Egreso' con varios parámetros relacionados a transacciones. */
        $Egreso->setImpuesto(0);
        $Egreso->setRetraccion(0);
        $Egreso->setUsuarioId($Usuario->puntoventaId);
        $Egreso->setConceptoId($Concept);
        $Egreso->setProductotercId($product->ProductId);
        $Egreso->setUsucajeroId($UsuarioMandante->getUsuarioMandante());

        /* establece valores en un objeto y lo inserta en la base de datos. */
        $Egreso->setProveedortercId(0);

        $Egreso->setUsucreaId(0);
        $Egreso->setUsumodifId(0);


        $EgresoMySqlDAO->insert($Egreso);

        /* Acumula el valor de egresos de productos en la variable TotalEgresosProductos. */
        $TotalEgresosProductos = $TotalEgresosProductos + $Value;

    }


    /* Se configuran variables para controlar filas y reglas en un procesamiento de datos. */
    $SkeepRows = 0;
    $OrderedItem = 1;
    $MaxRows = 1000;


    $rules = [];

    /* Se construye un filtro JSON con reglas para consultas de datos. */
    array_push($rules, array("field" => "usuario.puntoventa_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
    array_push($rules, array("field" => "it_ticket_enc.fecha_crea", "data" => $fechaHoy, "op" => "eq"));
    array_push($rules, array("field" => "it_ticket_enc.eliminado", "data" => 'N', "op" => "eq"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);


    /* obtiene y decodifica datos de tickets personalizados a formato JSON. */
    $ItTicketEnc = new ItTicketEnc();
    $data = $ItTicketEnc->getTicketsCustom("  SUM(it_ticket_enc.vlr_apuesta) vlr_apuesta, usuario.puntoventa_id ", "usuario.puntoventa_id", "asc", $SkeepRows, $MaxRows, $json, true, "", "", false, 0, true);

    $data = json_decode($data);


    foreach ($data->data as $key => $value) {

        /* verifica si ".vlr_apuesta" está vacío y lo establece en cero. */
        if ($value->{".vlr_apuesta"} == "") {
            $value->{".vlr_apuesta"} = 0;
        }

        if ($value->{".vlr_apuesta"} > 0) {

            /* Inicializa variables y obtiene un valor mediante una clase llamada MandanteDetalle. */
            $Concept = 0;
            $Description = '';
            $Reference = '';
            $Value = $value->{".vlr_apuesta"};

            try {
                $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $TipoTickets->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
                $Concept = $MandanteDetalle->getValor();
            } catch (Exception $e) {
                /* captura excepciones en PHP sin realizar ninguna acción específica. */


            }


            /* Crea un objeto "Ingreso" y establece sus propiedades basadas en los valores proporcionados. */
            $Ingreso = new Ingreso();
            $Ingreso->setTipoId($TipoTickets->getClasificadorId());
            $Ingreso->setDescripcion($Description);
            $Ingreso->setCentrocostoId(0);
            $Ingreso->setDocumento($Reference);
            $Ingreso->setEstado("A");

            /* configura propiedades de un objeto "Ingreso" con valores específicos. */
            $Ingreso->setValor($Value);
            $Ingreso->setImpuesto(0);
            $Ingreso->setRetraccion(0);
            $Ingreso->setUsuarioId($Usuario->puntoventaId);
            $Ingreso->setConceptoId($Concept);
            $Ingreso->setProductotercId($TipoTicketsId);

            /* Código para configurar propiedades de un objeto y luego insertarlo en una base de datos. */
            $Ingreso->setUsucajeroId($UsuarioMandante->getUsuarioMandante());
            $Ingreso->setProveedortercId(0);

            $Ingreso->setUsucreaId(0);
            $Ingreso->setUsumodifId(0);


            $IngresoMySqlDAO->insert($Ingreso);

            /* Suma el valor a la variable total de ingresos propios. */
            $TotalIngresosPropios = $TotalIngresosPropios + $Value;

        }
    }


    /* Se definen reglas de filtro para una consulta en base de datos. */
    $rules = [];
    array_push($rules, array("field" => "usuario.puntoventa_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
    array_push($rules, array("field" => "it_ticket_enc.fecha_pago", "data" => $fechaHoy, "op" => "eq"));


    $filtro = array("rules" => $rules, "groupOp" => "AND");

    /* convierte un filtro a JSON y obtiene tickets personalizados desde una base de datos. */
    $json = json_encode($filtro);

    $ItTicketEnc = new ItTicketEnc();

    $data = $ItTicketEnc->getTicketsCustom("  SUM(it_ticket_enc.vlr_premio) vlr_premio, usuario.puntoventa_id ", "usuario.puntoventa_id", "asc", $SkeepRows, $MaxRows, $json, true, "", "", false, 1, true);

    $data = json_decode($data);


    foreach ($data->data as $key => $value) {

        /* Asigna 0 a ".vlr_premio" si su valor actual es una cadena vacía. */
        if ($value->{".vlr_premio"} == "") {
            $value->{".vlr_premio"} = 0;
        }


        if ($value->{".vlr_premio"} > 0) {


            /* Se obtiene un valor mediante la clase MandanteDetalle y se almacena en $Concept. */
            $Concept = 0;
            $Description = '';
            $Reference = '';
            $Value = $value->{".vlr_premio"};
            try {
                $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $TipoPremios->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
                $Concept = $MandanteDetalle->getValor();
            } catch (Exception $e) {
                /* Captura excepciones en PHP, permitiendo manejar errores sin interrumpir el flujo del programa. */


            }


            /* Se crea un objeto Egreso y se establecen sus propiedades correspondientes. */
            $Egreso = new Egreso();
            $Egreso->setTipoId($TipoPremios->getClasificadorId());
            $Egreso->setDescripcion($Description);
            $Egreso->setCentrocostoId(0);
            $Egreso->setDocumento($Reference);
            $Egreso->setEstado("A");

            /* establece propiedades de un objeto "Egreso" con valores específicos. */
            $Egreso->setValor($Value);
            $Egreso->setImpuesto(0);
            $Egreso->setRetraccion(0);
            $Egreso->setUsuarioId($Usuario->puntoventaId);
            $Egreso->setConceptoId($Concept);
            $Egreso->setProductotercId($TipoPremiosId);

            /* Código para establecer datos de un egreso e insertarlo en la base de datos. */
            $Egreso->setUsucajeroId($UsuarioMandante->getUsuarioMandante());
            $Egreso->setProveedortercId(0);

            $Egreso->setUsucreaId(0);
            $Egreso->setUsumodifId(0);


            $EgresoMySqlDAO->insert($Egreso);


            /* Suma el valor de $Value a la variable $TotalEgresosPropios acumulando egresos. */
            $TotalEgresosPropios = $TotalEgresosPropios + $Value;

        }

    }

    /* Se añaden reglas y datos a los arrays mediante `array_push` en PHP. */
    array_push($final, $array);

    $rules = [];
    array_push($rules, array("field" => "usuario_punto.puntoventa_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
    //array_push($rules, array("field" => "DATE_FORMAT(usuario_recarga.fecha_crea,'%Y-%m-%d') ", "data" => $fechaHoy, "op" => "eq"));
    array_push($rules, array("field" => "time_dimension.timestampint ", "data" => strtotime(($fechaHoy . " 00:00:00")), "op" => "ge"));

    /* Se agregan reglas a un filtro y se convierten a formato JSON. */
    array_push($rules, array("field" => "time_dimension.timestampint", "data" => strtotime(($fechaHoy . " 23:59:59")), "op" => "le"));


    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);

    $UsuarioRecarga = new UsuarioRecarga();


    /* obtiene y decodifica datos de recargas de usuarios, inicializando un contador. */
    $data = $UsuarioRecarga->getUsuarioRecargasCustom("  SUM(usuario_recarga.valor) total, usuario_punto.puntoventa_id ", "usuario_punto.puntoventa_id", "asc", $SkeepRows, $MaxRows, $json, true, "", "", false);

    $data = json_decode($data);

    $depositos = 0;

    $array = [];


    /* inicializa un arreglo y procesa datos para calcular un total de depósitos. */
    $array["Id"] = 0;
    $array["Product"] = "Doradobet Recargas - Pago Notas";
    $array["Bets"] = 0;
    $array["Prize"] = 0;
    foreach ($data->data as $key => $value) {
        if ($value->{".total"} == "") {
            $value->{".total"} = 0;
        }


        if ($value->{".total"} > 0) {
            $Concept = 0;
            $Description = '';
            $Reference = '';
            $depositos = $depositos + $value->{".total"};


        }
    }


    /* Se definen reglas de filtrado para una consulta, utilizando condiciones de igualdad. */
    $rules = [];
    array_push($rules, array("field" => "usuario_punto.puntoventa_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
    array_push($rules, array("field" => "DATE_FORMAT(usuario_recarga.fecha_elimina,'%Y-%m-%d') ", "data" => $fechaHoy, "op" => "eq"));
    //array_push($rules, array("field" => "time_dimension.timestampint ", "data" => strtotime(($fechaHoy." 00:00:00")), "op" => "ge"));
    //array_push($rules, array("field" => "time_dimension.timestampint", "data" => strtotime(($fechaHoy." 23:59:59")), "op" => "le"));


    $filtro = array("rules" => $rules, "groupOp" => "AND");

    /* Codifica un filtro a JSON y obtiene recargas de usuarios desde la base de datos. */
    $json = json_encode($filtro);

    $UsuarioRecarga = new UsuarioRecarga();

    $data = $UsuarioRecarga->getUsuarioRecargasCustom("  SUM(usuario_recarga.valor) total, usuario_punto.puntoventa_id ", "usuario_punto.puntoventa_id", "asc", $SkeepRows, $MaxRows, $json, true, "", "", false);

    $data = json_decode($data);


    /* Define un arreglo con información sobre un producto y sus propiedades. */
    $array = [];


    $array["Id"] = 0;
    $array["Product"] = "Doradobet Recargas - Pago Notas";
    $array["Bets"] = 0;

    /* inicializa un premio y ajusta depósitos según valores totales. */
    $array["Prize"] = 0;
    foreach ($data->data as $key => $value) {
        if ($value->{".total"} == "") {
            $value->{".total"} = 0;
        }


        if ($value->{".total"} > 0) {
            $Concept = 0;
            $Description = '';
            $Reference = '';
            $depositos = $depositos - $value->{".total"};


        }
    }


    /* Crea un objeto MandanteDetalle y obtiene su valor, gestionando excepciones. */
    try {
        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $TipoRecargas->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
        $Concept = $MandanteDetalle->getValor();
    } catch (Exception $e) {

    }

    /* crea un objeto "Ingreso" y establece sus propiedades. */
    $Ingreso = new Ingreso();
    $Ingreso->setTipoId($TipoRecargas->getClasificadorId());
    $Ingreso->setDescripcion($Description);
    $Ingreso->setCentrocostoId(0);
    $Ingreso->setDocumento($Reference);
    $Ingreso->setEstado("A");

    /* Configuración de atributos para un objeto de ingresos en un sistema. */
    $Ingreso->setValor($depositos);
    $Ingreso->setImpuesto(0);
    $Ingreso->setRetraccion(0);
    $Ingreso->setUsuarioId($Usuario->puntoventaId);
    $Ingreso->setConceptoId($Concept);
    $Ingreso->setProductotercId($TipoRecargasId);

    /* Código para insertar un objeto de ingreso en la base de datos. */
    $Ingreso->setUsucajeroId($UsuarioMandante->getUsuarioMandante());
    $Ingreso->setProveedortercId(0);

    $Ingreso->setUsucreaId(0);
    $Ingreso->setUsumodifId(0);


    $IngresoMySqlDAO->insert($Ingreso);


    /* Se actualizan ingresos y se definen reglas para una consulta de datos. */
    $TotalIngresosPropios = $TotalIngresosPropios + $depositos;


    $rules = [];
    array_push($rules, array("field" => "usuario_punto.puntoventa_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
    //array_push($rules, array("field" => "DATE_FORMAT(cuenta_cobro.fecha_pago,'%Y-%m-%d') ", "data" => $fechaHoy, "op" => "eq"));
    array_push($rules, array("field" => "time_dimension.timestampint ", "data" => strtotime(($fechaHoy . " 00:00:00")), "op" => "ge"));

    /* Agrega una regla de filtrado y convierte el arreglo a formato JSON. */
    array_push($rules, array("field" => "time_dimension.timestampint", "data" => strtotime(($fechaHoy . " 23:59:59")), "op" => "le"));


    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);

    $CuentaCobro = new CuentaCobro();


    /* obtiene y decodifica cuentas de cobro en formato JSON. */
    $data = $CuentaCobro->getCuentasCobroCustom("  SUM(cuenta_cobro.valor) total, usuario_punto.puntoventa_id ", "usuario_punto.puntoventa_id", "asc", $SkeepRows, $MaxRows, $json, true, "", "", false, true, false);

    $data = json_decode($data);


    foreach ($data->data as $key => $value) {

        /* Asigna 0 a ".total" si está vacío en la estructura de datos. */
        if ($value->{".total"} == "") {
            $value->{".total"} = 0;
        }


        if ($value->{".total"} > 0) {


            /* inicializa variables y obtiene un valor de MandanteDetalle basado en parámetros. */
            $Concept = 0;
            $Description = '';
            $Reference = '';
            $Value = $value->{".total"};

            try {
                $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $TipoNotasRetiros->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
                $Concept = $MandanteDetalle->getValor();
            } catch (Exception $e) {
                /* Captura excepciones en PHP, permite manejar errores sin interrumpir la ejecución. */


            }


            /* Crea un objeto 'Egreso' y establece sus propiedades mediante métodos específicos. */
            $Egreso = new Egreso();
            $Egreso->setTipoId($TipoNotasRetiros->getClasificadorId());
            $Egreso->setDescripcion($Description);
            $Egreso->setCentrocostoId(0);
            $Egreso->setDocumento($Reference);
            $Egreso->setEstado("A");

            /* establece propiedades de un objeto 'Egreso' con valores específicos. */
            $Egreso->setValor($Value);
            $Egreso->setImpuesto(0);
            $Egreso->setRetraccion(0);
            $Egreso->setUsuarioId($Usuario->puntoventaId);
            $Egreso->setConceptoId($Concept);
            $Egreso->setProductotercId($TipoNotasRetirosId);

            /* Código que establece propiedades de un objeto y lo inserta en la base de datos. */
            $Egreso->setUsucajeroId($UsuarioMandante->getUsuarioMandante());
            $Egreso->setProveedortercId(0);

            $Egreso->setUsucreaId(0);
            $Egreso->setUsumodifId(0);


            $EgresoMySqlDAO->insert($Egreso);


            /* Suma el valor a la variable total de egresos propios. */
            $TotalEgresosPropios = $TotalEgresosPropios + $Value;

        }
    }


    /* Condicionalmente añade reglas basadas en el perfil de sesión del usuario. */
    $rules = [];

    if ($_SESSION["win_perfil2"] == "PUNTOVENTA") {

        array_push($rules, array("field" => "egreso.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
    } else {
        /* Agrega una regla al arreglo si no se cumple una condición. */

        array_push($rules, array("field" => "egreso.usucajero_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

    }


    /* Se agrega una regla para filtrar registros de egreso por fecha y hora. */
    array_push($rules, array("field" => "egreso.fecha_crea", "data" => $fechaHoyConHora, "op" => "ge"));


    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json = json_encode($filtro);

    $Egreso = new Egreso();


    /* Calcula totales de egresos según condiciones de productos y su estado. */
    $data = $Egreso->getEgresosCustom("  egreso.*,producto_tercero.* ", "egreso.egreso_id", "asc", $SkeepRows, $MaxRows, $json, true);
    $data = json_decode($data);


    foreach ($data->data as $key => $value) {
        if ($value->{"egreso.productoterc_id"} != "0" && $value->{"producto_tercero.tiene_cupo"} == "N") {
            if ($value->{"producto_tercero.interno"} == "S") {
                $TotalEgresosPropios = $TotalEgresosPropios + $value->{"egreso.valor"};

            } else {
                $TotalEgresosProductos = $TotalEgresosProductos + $value->{"egreso.valor"};
            }

        } else {
            if ($value->{"producto_tercero.tiene_cupo"} == "N") {
                $TotalEgresosOtros = $TotalEgresosOtros + $value->{"egreso.valor"};
            }
        }

    }


    /* Configura reglas de validación según el perfil de sesión "PUNTOVENTA". */
    $rules = [];

    if ($_SESSION["win_perfil2"] == "PUNTOVENTA") {

        array_push($rules, array("field" => "ingreso.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
    } else {
        /* Se añade una regla al arreglo si la condición del "else" se cumple. */

        array_push($rules, array("field" => "ingreso.usucajero_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

    }


    /* Se construye un filtro con reglas para filtrar datos por tipo y fecha. */
    array_push($rules, array("field" => "ingreso.tipo_id", "data" => 0, "op" => "eq"));

    array_push($rules, array("field" => "ingreso.fecha_crea", "data" => $fechaHoyConHora, "op" => "ge"));


    $filtro = array("rules" => $rules, "groupOp" => "AND");

    /* convierte un filtro a JSON y obtiene ingresos personalizados de la base de datos. */
    $json = json_encode($filtro);

    $Ingreso = new Ingreso();

    $data = $Ingreso->getIngresosCustom("  ingreso.*,producto_tercero.* ", "ingreso.ingreso_id", "asc", $SkeepRows, $MaxRows, $json, true);

    $data = json_decode($data);

    foreach ($data->data as $key => $value) {


        /* Suma ingresos a diferentes totales según condiciones específicas de productos. */
        if ($value->{"ingreso.productoterc_id"} != "0") {
            if ($value->{"producto_tercero.interno"} == "S") {
                $TotalIngresosPropios = $TotalIngresosPropios + $value->{"ingreso.valor"};

            } else {
                $TotalIngresosProductos = $TotalIngresosProductos + $value->{"ingreso.valor"};
            }

        } else {
            /* clasifica ingresos y acumula totales según el tipo especificado. */

            if ($value->{"ingreso.tipo_id"} != "0") {

                $Tipo = new Clasificador($value->{"ingreso.tipo_id"});

                switch ($Tipo->getTipo()) {
                    case "TARJCRED":
                        $otrosIngresosTarjetasCreditos += $value->{"ingreso.valor"};
                        break;
                }
            } else {
                $TotalIngresosOtros = $TotalIngresosOtros + $value->{"ingreso.valor"};

            }
        }

    }


    /* define reglas basadas en la sesión de usuario en un punto de venta. */
    $rules = [];

    if ($_SESSION["win_perfil2"] == "PUNTOVENTA") {

        array_push($rules, array("field" => "ingreso.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
    } else {
        /* Agregar una regla de filtro al array si no se cumple una condición previa. */

        array_push($rules, array("field" => "ingreso.usucajero_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));

    }


    /* Se crean reglas de filtrado para una consulta utilizando un array en PHP. */
    array_push($rules, array("field" => "ingreso.tipo_id", "data" => 0, "op" => "ne"));

    array_push($rules, array("field" => "ingreso.fecha_crea", "data" => $fechaHoyConHora, "op" => "ge"));


    $filtro = array("rules" => $rules, "groupOp" => "AND");

    /* convierte datos a JSON, consulta ingresos y decodifica la respuesta. */
    $json = json_encode($filtro);

    $Ingreso = new Ingreso();

    $data = $Ingreso->getIngresosCustom("  ingreso.*,producto_tercero.* ", "ingreso.ingreso_id", "asc", $SkeepRows, $MaxRows, $json, true);

    $data = json_decode($data);

    foreach ($data->data as $key => $value) {

        /* suma ingresos dependiendo de condiciones sobre productos y su estado. */
        if ($value->{"ingreso.productoterc_id"} != "0") {
            if ($value->{"producto_tercero.interno"} == "S") {
                $TotalIngresosPropios = $TotalIngresosPropios + $value->{"ingreso.valor"};

            } else {
                $TotalIngresosProductos = $TotalIngresosProductos + $value->{"ingreso.valor"};
            }

        } else {
            /* gestiona ingresos según el tipo, acumulando valores en diferentes variables. */

            if ($value->{"ingreso.tipo_id"} != "0") {

                $Tipo = new Clasificador($value->{"ingreso.tipo_id"});

                switch ($Tipo->getTipo()) {
                    case "TARJCRED":
                        $otrosIngresosTarjetasCreditos += $value->{"ingreso.valor"};
                        break;
                }
            } else {
                $TotalIngresosOtros = $TotalIngresosOtros + $value->{"ingreso.valor"};

            }
        }
    }


    /* Define reglas de filtrado para una consulta sobre ingresos en una base de datos. */
    $rules = [];
    array_push($rules, array("field" => "ingreso.usucajero_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
    array_push($rules, array("field" => "ingreso.fecha_crea", "data" => date("Y-m-d 00:00:00"), "op" => "ge"));
    array_push($rules, array("field" => "ingreso.tipo_id", "data" => $TipoDineroInicial->getClasificadorId(), "op" => "eq"));


    $filtro = array("rules" => $rules, "groupOp" => "AND");

    /* convierte un filtro a JSON y recupera datos de ingresos personalizados. */
    $json = json_encode($filtro);

    $Ingreso = new Ingreso();

    $data = $Ingreso->getIngresosCustom("  ingreso.* ", "ingreso.ingreso_id", "asc", $SkeepRows, $MaxRows, $json, true);

    $data = json_decode($data);


    /* itera sobre datos y establece un usuario para cerrar caja. */
    foreach ($data->data as $key => $value) {
        $dineroInicial = $value->{"ingreso.valor"};
    }

    $UsuarioCierrecaja = new UsuarioCierrecaja();

    $UsuarioCierrecaja->setUsuarioId($UsuarioMandante->getUsuarioMandante());

    /* asigna valores de ingresos y egresos a un objeto de cierre de caja. */
    $UsuarioCierrecaja->setFechaCierre($fechaHoy);
    $UsuarioCierrecaja->setIngresosPropios($TotalIngresosPropios);
    $UsuarioCierrecaja->setEgresosPropios($TotalEgresosPropios);
    $UsuarioCierrecaja->setIngresosProductos($TotalIngresosProductos);
    $UsuarioCierrecaja->setEgresosProductos($TotalEgresosProductos);
    $UsuarioCierrecaja->setIngresosOtros($TotalIngresosOtros);

    /* Se configuran propiedades de un objeto relacionado con transacciones financieras. */
    $UsuarioCierrecaja->setEgresosOtros($TotalEgresosOtros);
    $UsuarioCierrecaja->setUsucreaId($UsuarioMandante->getUsuarioMandante());
    $UsuarioCierrecaja->setUsumodifId($UsuarioMandante->getUsuarioMandante());
    $UsuarioCierrecaja->setDineroInicial($dineroInicial);
    $UsuarioCierrecaja->setIngresosTarjetacredito($otrosIngresosTarjetasCreditos);

    $UsuarioCierrecajaMySqlDAO = new UsuarioCierrecajaMySqlDAO($Transaction);

    /* Inserta un objeto en la base de datos y verifica condición de sesión. */
    $UsuarioCierrecajaMySqlDAO->insert($UsuarioCierrecaja);

    if ($_SESSION['usuario2'] == '10119' && false) {
        print_r($UsuarioCierrecaja);
    }
    $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());


    /* verifica si la fecha de cierre es posterior a hoy. */
    $seguir = true;

    if (date("Y-m-d", strtotime($Usuario->fechaCierrecaja)) >= date("Y-m-d", strtotime($fechaHoy))) {
        $seguir = false;
    }

    if ($seguir) {


        /* Actualiza la fecha de cierre de caja del usuario en la base de datos y confirma transacción. */
        $Usuario->fechaCierrecaja = $fechaHoyConHoraSegundos;

        $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);
        $UsuarioMySqlDAO->update($Usuario);


        $Transaction->commit();


        /* Código inicializa una respuesta con éxito, sin errores y sin mensaje de alerta. */
        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

        $response["PdfPOS"] = "";


        /**
         * PDF GENERATION
         */


        /* Se crean clasificadores para diferentes tipos de tickets, premios y transacciones financieras. */
        $TipoTickets = new Clasificador("", "ACCBETTICKET");
        $TipoPremios = new Clasificador("", "ACCWINTICKET");
        $TipoNotasRetiros = new Clasificador("", "ACCPAYWD");
        $TipoRecargas = new Clasificador("", "ACCREC");
        $TipoDineroInicial = new Clasificador("", "ACCAMOUNTDAY");


        $response["Data"] = array();

        /* Inicializa variables para total, fecha, usuario y registros de ingresos y egresos. */
        $total = 0;
        $fecha = "";
        $usuarioId = 0;

        $ingresosProductos = 0;
        $egresosProductos = 0;

        /* Inicializa variables para gestionar ingresos y egresos en un cierre de caja. */
        $otrosIngresosTarjetasCreditos = 0;
        $otrosIngresos = 0;
        $otrosEgresos = 0;
        $dineroInicial = 0;

        $id = $UsuarioCierrecaja->getUsucierrecajaId();

        /* obtiene valores de parámetros y solicitudes para paginación de datos. */
        $MaxRows = $params->MaxRows;
        $OrderedItem = $params->OrderedItem;
        $SkeepRows = $params->SkeepRows;

        $MaxRows = $_REQUEST["count"];
        $SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];


        /* inicializa variables en caso de que estén vacías. */
        if ($SkeepRows == "") {
            $SkeepRows = 0;
        }

        if ($OrderedItem == "") {
            $OrderedItem = 1;
        }


        /* Establece un valor predeterminado y define reglas para filtrar datos. */
        if ($MaxRows == "") {
            $MaxRows = 1000;
        }

        $rules = [];

        array_push($rules, array("field" => "usuario_cierrecaja.usucierrecaja_id", "data" => $id, "op" => "eq"));


        /* Se crea un filtro JSON y se obtienen datos de usuario en "Cierrecaja". */
        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);

        $UsuarioCierrecaja = new UsuarioCierrecaja();

        $data = $UsuarioCierrecaja->getUsuarioCierrecajasCustom("  usuario.login,usuario_cierrecaja.* ", "usuario_cierrecaja.usucierrecaja_id", "asc", $SkeepRows, $MaxRows, $json, true);


        /* Convierte datos JSON a un objeto y crea un array vacío para resultados. */
        $data = json_decode($data);
        $final = [];

        foreach ($data->data as $key => $value) {


            /* Crea un arreglo con información de usuario a partir de un objeto. */
            $array = [];


            $array["Id"] = $value->{"usuario_cierrecaja.usucierrecaja_id"};
            $array["User"] = $value->{"usuario_cierrecaja.usuario_id"};
            $array["UserName"] = $value->{"usuario.login"};

            /* transforma datos de un objeto en un array estructurado. */
            $array["Date"] = date('Y-m-d', strtotime($value->{"usuario_cierrecaja.fecha_cierre"}));
            $array["AmountBegin"] = $value->{"usuario_cierrecaja.dinero_inicial"};
            $array["ProperIncomes"] = $value->{"usuario_cierrecaja.ingresos_propios"};
            $array["ProperExpenses"] = $value->{"usuario_cierrecaja.egresos_propios"};
            $array["ProductsIncomes"] = $value->{"usuario_cierrecaja.ingresos_productos"};
            $array["ProductsExpenses"] = $value->{"usuario_cierrecaja.egresos_productos"};

            /* Asigna ingresos y gastos a un array y calcula el total. */
            $array["OthersIncomes"] = $value->{"usuario_cierrecaja.ingresos_otros"};
            $array["OthersExpenses"] = $value->{"usuario_cierrecaja.egresos_otros"};
            $array["IncomesCreditCards"] = $value->{"usuario_cierrecaja.ingresos_tarjetacredito"};
            $array["Total"] = $array["AmountBegin"] + $array["ProperIncomes"] + $array["ProductsIncomes"] + $array["OthersIncomes"]
                - $array["ProperExpenses"] - $array["ProductsExpenses"] - $array["OthersExpenses"] - $array["IncomesCreditCards"];

            array_push($final, $array);


            /* asigna valores de un array a variables relacionadas con usuario y finanzas. */
            $fecha = $array["Date"];
            $usuarioId = $value->{"usuario_cierrecaja.usuario_id"};

            $total = $array["AmountBegin"];
            $dineroInicial = $array["Total"];


        }

        /* inicializa un arreglo de respuesta con diversas secciones relacionadas. */
        $response["Data"]["SquareDay"] = $final;
        $response["Data"]["Products"] = array();
        $response["Data"]["Incomes"] = array();
        $response["Data"]["Expenses"] = array();
        $response["Data"]["Tickets"] = array();
        $response["Data"]["Deposit"] = array();

        /* Se inicializa un arreglo para retiros y se configuran parámetros de paginación. */
        $response["Data"]["Withdraw"] = array();

        $SkeepRows = 0;
        $OrderedItem = 1;
        $MaxRows = 1000;

        $UsuarioPerfil = new UsuarioPerfil($usuarioId);

        /* Se crea un usuario y se establece una regla de ingreso según su perfil. */
        $Usuario = new Usuario($usuarioId);

        $rules = [];
        $grouping = "";


        if ($UsuarioPerfil->getPerfilId() == "PUNTOVENTA") {

            array_push($rules, array("field" => "ingreso.usuario_id", "data" => $usuarioId, "op" => "eq"));
        } else {
            /* Se agrega una regla si no se cumple una condición anterior en el código. */

            array_push($rules, array("field" => "ingreso.usucajero_id", "data" => $usuarioId, "op" => "eq"));
        }


        /* Se agrega una regla de filtro y se codifica en formato JSON. */
        array_push($rules, array("field" => "DATE_FORMAT(ingreso.fecha_crea,'%Y-%m-%d')", "data" => $fecha, "op" => "eq"));


        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);

        $Ingreso = new Ingreso();


        /* obtiene ingresos y productos, los decodifica y prepara para uso. */
        $data = $Ingreso->getIngresosCustom("  ingreso.*,producto_tercero.* ", "ingreso.ingreso_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping);

        $data = json_decode($data);
        $final = [];

        foreach ($data->data as $key => $value) {


            /* asigna valores a un array basado en condiciones específicas. */
            $array = [];


            $array["Id"] = $value->{"ingreso.ingreso_id"};
            $array["Description"] = $value->{"ingreso.descripcion"};

            if ($value->{"producto_tercero.descripcion"} != "") {
                $array["ProductId"] = $value->{"producto_tercero.productoterc_id"};
                $array["Description"] = $value->{"producto_tercero.descripcion"};

            }


            /* asigna valores de un objeto a un array asociativo en PHP. */
            $array["Reference"] = $value->{"ingreso.documento"};
            $array["Amount"] = $value->{"ingreso.valor"};

            if ($value->{"producto_tercero.descripcion"} != "") {

                if ($value->{"producto_tercero.interno"} == "S") {


                    /* Se crea un nuevo objeto de la clase Clasificador usando un tipo de producto específico. */
                    $Tipo = new Clasificador($value->{"producto_tercero.tipo_id"});

                    // switch ($Tipo->getAbreviado()) {
                    switch ($value->{"producto_tercero.tipo_id"}) {

                        case $TipoTickets->getClasificadorId():
                            /* asigna valores a un arreglo basado en el tipo de tickets. */

                            $array["Description"] = "Tickets";
                            $array["AmountWin"] = 0;
                            array_push($response["Data"]["Tickets"], $array);

                            break;

                        case $TipoPremios->getClasificadorId():
                            /* Estructura de control que actúa según el identificador del clasificador de premios. */


                            break;

                        case $TipoNotasRetiros->getClasificadorId():
                            /* Es un fragmento de código que utiliza una estructura de control `case` en PHP. */


                            break;

                        case $TipoRecargas->getClasificadorId():
                            /* Asigna "Recargas" a un elemento del array según el clasificador. */

                            $array["Description"] = "Recargas";
                            array_push($response["Data"]["Deposit"], $array);

                            break;

                    }
                } else {
                    /* Actualiza la cantidad de un producto en un arreglo o lo agrega si no existe. */

                    $array["AmountWin"] = 0;

                    $encontroProducto = false;
                    foreach ($response["Data"]["Products"] as $key => $product) {

                        if ($product["ProductId"] == $array["ProductId"]) {
                            $response["Data"]["Products"][$key]["Amount"] = $response["Data"]["Products"][$key]["Amount"] + $array["Amount"];

                            $encontroProducto = true;

                        }
                    }
                    if (!$encontroProducto) {
                        array_push($response["Data"]["Products"], $array);
                    }
                }

            } else {

                /* Condicional que suma ingresos de tarjetas de crédito basado en tipo específico. */
                if ($value->{"ingreso.tipo_id"} != "0") {

                    if ($TipoDineroInicial->getClasificadorId() == $value->{"ingreso.tipo_id"}) {
                        //$dineroInicial = $value->{"ingreso.valor"};
                    }


                    $Tipo = new Clasificador($value->{"ingreso.tipo_id"});

                    switch ($Tipo->getTipo()) {
                        case "TARJCRED":
                            $otrosIngresosTarjetasCreditos += $array["Amount"];
                            break;
                    }

                } else {
                    /* Añade un nuevo ingreso al array y suma su cantidad a otros ingresos. */

                    array_push($response["Data"]["Incomes"], $array);
                    $otrosIngresos += $array["Amount"];
                }

            }


            /* Suma el valor de "Amount" en el arreglo a la variable total. */
            $total = $total + $array["Amount"];

        }


        /* Se establece una regla de permisos para el perfil "PUNTOVENTA". */
        $rules = [];
        $grouping = "";


        if ($UsuarioPerfil->getPerfilId() == "PUNTOVENTA") {

            array_push($rules, array("field" => "egreso.usuario_id", "data" => $usuarioId, "op" => "eq"));
        } else {
            /* Agrega una regla al array si no se cumple una condición específica. */

            array_push($rules, array("field" => "egreso.usucajero_id", "data" => $usuarioId, "op" => "eq"));
        }


        /* Agrega una regla de filtro y convierte la configuración a formato JSON. */
        array_push($rules, array("field" => "DATE_FORMAT(egreso.fecha_crea,'%Y-%m-%d')", "data" => $fecha, "op" => "eq"));


        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);

        $Egreso = new Egreso();


        /* obtiene datos de egresos y los decodifica en formato JSON. */
        $data = $Egreso->getEgresosCustom("  egreso.*,producto_tercero.* ", "egreso.egreso_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping);

        $data = json_decode($data);
        $final = [];

        foreach ($data->data as $key => $value) {


            /* Se crea un arreglo que almacena información sobre egresos y productos relacionados. */
            $array = [];


            $array["Id"] = $value->{"egreso.ingreso_id"};
            $array["Description"] = $value->{"egreso.descripcion"};

            if ($value->{"producto_tercero.descripcion"} != "") {
                $array["ProductId"] = $value->{"producto_tercero.productoterc_id"};
                $array["Description"] = $value->{"producto_tercero.descripcion"};

            }


            /* Se asignan valores a un arreglo y se calcula un total restando la cantidad. */
            $array["Reference"] = $value->{"egreso.documento"};
            $array["Amount"] = $value->{"egreso.valor"};


            $total = $total - $array["Amount"];

            if ($value->{"producto_tercero.descripcion"} != "" && $value->{"producto_tercero.tiene_cupo"} == "N") {
                if ($value->{"producto_tercero.interno"} == "S") {


                    /* Se inicializa un objeto "Clasificador" con el tipo de producto especificado. */
                    $Tipo = new Clasificador($value->{"producto_tercero.tipo_id"});

                    // switch ($Tipo->getAbreviado()) {
                    switch ($value->{"producto_tercero.tipo_id"}) {

                        case $TipoTickets->getClasificadorId():
                            /* evalúa el clasificador de tickets y no ejecuta acciones. */


                            break;

                        case $TipoPremios->getClasificadorId():
                            /* Asignación del monto de premio al primer ticket en la respuesta. */

                            $response["Data"]["Tickets"][0]["AmountWin"] = $array["Amount"];

                            break;

                        case $TipoNotasRetiros->getClasificadorId():
                            /* asigna una descripción y agrega un pago a un arreglo de respuestas. */

                            $array["Description"] = "Pago Notas de Retiro";
                            array_push($response["Data"]["Withdraw"], $array);

                            break;

                        case $TipoRecargas->getClasificadorId():
                            /* Estructura de control que evalúa el clasificador de recargas sin acción definida. */


                            break;
                        case "ACCWINTICKET":
                            /* Asigna el valor de "Amount" a "AmountWin" para un ticket específico. */

                            $response["Data"]["Tickets"][0]["AmountWin"] = $array["Amount"];

                            break;
                        case "ACCPAYWD":
                            /* asigna una descripción y agrega datos a un arreglo de respuestas. */

                            $array["Description"] = "Pago Notas de Retiro";
                            array_push($response["Data"]["Withdraw"], $array);

                            break;
                    }
                } else {
                    /* verifica y actualiza productos en un arreglo, añadiendo o modificando cantidades. */

                    $encontroProducto = false;
                    foreach ($response["Data"]["Products"] as $key => $product) {

                        if ($product["ProductId"] == $array["ProductId"]) {
                            $response["Data"]["Products"][$key]["AmountWin"] = $response["Data"]["Products"][$key]["AmountWin"] + $array["Amount"];

                            $encontroProducto = true;

                        }
                    }
                    if (!$encontroProducto) {
                        $array["AmountWin"] = $array["Amount"];
                        $array["Amount"] = 0;

                        array_push($response["Data"]["Products"], $array);
                    }
                }


            } else {


                /* Condiciona el tipo de egreso y asigna valores a la respuesta basada en su clasificación. */
                if ($value->{"egreso.tipo_id"} != "0") {
                    $Tipo = new Clasificador($value->{"egreso.tipo_id"});

                    switch ($Tipo->getAbreviado()) {
                        case "ACCWINTICKET":
                            $response["Data"]["Tickets"][0]["AmountWin"] = $array["Amount"];

                            break;
                        case "ACCPAYWD":
                            $array["Description"] = "Pago Notas de Retiro";
                            array_push($response["Data"]["Withdraw"], $array);

                            break;
                    }

                } else {
                    /* verifica condiciones y agrega datos a un array en caso de ser determinadas. */

                    if ($value->{"producto_tercero.tiene_cupo"} == "N") {
                        array_push($response["Data"]["Expenses"], $array);
                        $otrosEgresos += $value->{"egreso.valor"};
                    }
                }


            }


        }


        /* Variables para almacenar contenido HTML de productos y gastos, posiblemente para un sistema POS. */
        $htmlProduct = "";
        $htmlProductExpense = "";

        $htmlProductPOS = "";
        $htmlProductExpensePOS = "";

        foreach ($response["Data"]["Products"] as $product) {

            /* Genera filas HTML para mostrar descripciones y montos de productos y gastos. */
            $htmlProduct .= '<tr>
                    <td width="70%" align="left">' . $product["Description"] . '</td>
                    <td align="left">S/ ' . $product["Amount"] . '</td>

                </tr>';
            $htmlProductExpense .= '<tr>
                    <td width="70%" align="left">' . $product["Description"] . '</td>
                    <td align="left">S/ ' . $product["AmountWin"] . '</td>

                </tr>';


            /* Genera filas HTML para mostrar descripción y monto de productos y gastos. */
            $htmlProductPOS .= '<tr>
                    <td width="70%" align="left">' . $product["Description"] . '</td></tr><tr>
                    <td align="left">S/ ' . $product["Amount"] . '</td>

                </tr>';
            $htmlProductExpensePOS .= '<tr>
                    <td width="70%" align="left">' . $product["Description"] . '</td></tr><tr>
                    <td align="left">S/ ' . $product["AmountWin"] . '</td>

                </tr>';

            /* Suma el monto de ingresos y egresos de productos en variables respectivas. */
            $ingresosProductos += $product["Amount"];
            $egresosProductos += $product["AmountWin"];


        }


        /* suma ingresos y egresos de productos desde una respuesta JSON. */
        $ingresosProductos += $response["Data"]["Tickets"][0]["Amount"];
        $ingresosProductos += $response["Data"]["Deposit"][0]["Amount"];
        $egresosProductos += $response["Data"]["Tickets"][0]["AmountWin"];
        $egresosProductos += $response["Data"]["Withdraw"][0]["AmountWin"];


        $pdf = ' <html> <body>  <style>
 td{
  font-size:12px;
 }
</style><table style="
    width: 1000px;
    margin: 0 auto;
    border-collapse: collapse;
    width: 1000px;
    max-width: 1000px;
    margin: 0 auto;
"><tbody><tr><td align="center"><img src="https://images.virtualsoft.tech/site/doradobet/logo-invoice.svg" style="width:120px;"></td><td><div style="font-weight: bold;border: 0px;font-size: 20px;">CIERRE DIARIO DE
    CAJA</div></td></tr></tbody></table> 
<table style="/* width:430px; */height: 355px;/* border:1px solid black; */border-collapse: collapse;width: 800px;max-width: 800px;margin: 0 auto;padding: 0px 10px;border: 0px;">
    <tbody>
    
    
    <tr>

        <td width="50%" align="left">Local: ' . $Usuario->nombre . '</td>
        <td width="50%" align="left">Fecha: ' . $fecha . '</td>
    </tr>
    <tr style="
    /* border-collapse: collapse; */ 
">
        <td width="50%" align="left"  valign="top" style="
        border: 1px solid;
        padding: 0px 10px;
    "><font style="padding-left:5px;text-align:center;font-size:13px;font-weight:normal;">Ingresos diarios por
            Juegos</font>
            <table >
                <tbody>
                <tr>
                    <td width="70%" align="left">Doradobet Tickets</td>
                    <td align="left">S/ ' . $response["Data"]["Tickets"][0]["Amount"] . '</td>

                </tr>
                <tr>
                    <td width="70%" align="left">Doradobet Recargas</td>
                    <td align="left">S/  ' . $response["Data"]["Deposit"][0]["Amount"] . '</td>

                </tr>
                ' . $htmlProduct . '

                <tr style="
        font-weight: bold;
        /* padding-top: 6px; */
    ">
                    <td width="70%" align="left" style="font-weight: bold;">(1) INGRESO TOTAL DE JUEGOS</td>
                    <td style="
        border: 2px solid;padding:0px 5px;
    " align="left">S/ ' . $ingresosProductos . '
                    </td>

                </tr>
                </tbody>
            </table>
        </td>
        <td align="left" width="50%"  valign="top" style="
        border: 1px solid;
        padding: 0px 10px;
    "><font style="padding-left:5px;text-align:center;font-size:13px;font-weight:normal;">Salidas diarias por premios o
            devoluciones </font>
            <table style="
    ">
                <tbody>
                <tr>
                    <td width="70%" align="left">Pago Doradobet Tickets</td>
                    <td align="left">S/ ' . $response["Data"]["Tickets"][0]["AmountWin"] . '</td>

                </tr>
                <tr>
                    <td width="70%" align="left">Doradobet Retiros</td>
                    <td align="left">S/ ' . $response["Data"]["Withdraw"][0]["AmountWin"] . '</td>

                </tr>
 ' . $htmlProductExpense . '
                <tr style="
        font-weight: bold;
    ">
                    <td width="70%" align="left"  style="font-weight: bold;font-size:12px;">(2) SALIDAS TOTALES DE JUEGOS</td>
                    <td style="
        border: 2px solid;padding:0px 5px;
    " align="left">S/ ' . $egresosProductos . '
                    </td>

                </tr>
                </tbody>
            </table>
        </td>
    </tr>


    <tr>
        <td width="50%" align="left" valign="top">
            <table style="
        width: 100%;
    ">
                <tbody>
                <tr>
                    <td align="left" width="70%"  style="font-size:12px;">IMPORTE TOTAL DE CAJA (1) - (2)</td>
                    <td align="left" style="
        border: 2px solid;padding:0px 5px;
    ">S/ ' . ($ingresosProductos - $egresosProductos) . '
                    </td>

                </tr>
                </tbody>
            </table>
        </td>
        <td align="center" valign="top"></td>
    </tr>
    <tr>
        <td width="50%" align="center" valign="top">
            <table style="
        width: 100%;
    ">
                <tbody>
                <tr style="
    ">
                    <td align="left" width="70%" style="font-weight: bold;font-size:12px;">(4) GASTOS ADICIONALES DEL DÍA</td>


                </tr>

                </tbody>
            </table>
        </td>
        <td align="center" valign="top"></td>
    </tr>
    <tr>
        <td width="50%" align="center" valign="top" style="padding-left: 15px;">
            <table style="8
        width: 100%;
    ">
                <tbody>

                <tr>
                    <td align="left" width="70%" style="font-size:12px;">GASTOS CON FAC</td>
                    <td align="left" >S/ ' . $otrosEgresos . '</td>

                </tr>

                </tbody>
            </table>
        </td>
        <td align="center" valign="top"></td>
    </tr>
    
    <tr>
        <td width="50%" align="center" valign="top">
            <table style="
        width: 100%;
    ">
                <tbody>
                <tr style="
    ">
                    <td align="left" width="70%" style="font-weight: bold;font-size:12px;">(5) OTROS INGRESOS</td>

                    <td align="left" >S/ ' . $otrosIngresos . '</td>

                </tr>

                </tbody>
            </table>
        </td>
        <td align="center" valign="top"></td>
    </tr>
    <tr>
        <td width="50%" align="center" valign="top">
            <table style="
        width: 100%;
    ">
                <tbody>

                <tr>
                    <td align="left" width="70%"  style="font-size:13px;">CIERRE TOTAL CAJA EFECTIVO (3)-(4)+(5)</td>
                    <td align="left"   style="
        border: 2px solid;padding:0px 5px;
    ">S/ ' . ($ingresosProductos + $otrosIngresos - $egresosProductos - $otrosEgresos) . '
                    </td>

                </tr>

                </tbody>
            </table>
        </td>
        <td align="center" valign="top"></td>
    </tr>
    <tr>

        <td width="50%" align="center" valign="top">
            <table style="
        width: 100%;
    ">
                <tbody>

                <tr>
                    <td align="left" width="70%"  style="font-size:13px;">CIERRE TOTAL DE CAJA TARJETAS</td>
                    <td align="left"   style="
        border: 2px solid;padding:0px 5px;
    ">S/ ' . $otrosIngresosTarjetasCreditos . '
                    </td>

                </tr>
                </tbody>
            </table>
        </td>
        <td align="center" valign="top">
            <div style="
        width: 100%;
        border-top: 1px solid;
    ">Cajero
            </div>
            <table style="
        width: 100%;
    ">

            </table>
        </td>
    </tr>
    <tr>

        <td width="50%" align="left" valign="top">
            <table style="
        width: 100%;
    ">
                <tbody>

                <tr>
                    <td width="70%"  style="font-size:10px;">SALDO INICIAL</td>
                    <td  style="font-size:11px;" >S/ ' . $dineroInicial . '</td>

                </tr>
                </tbody>
            </table>
        </td>
        <td align="center" valign="top">
            <table style="
        width: 100%;
    ">
                <tbody>

                <tr>
                    <td width="70%"  style="font-size:11px;">SALDO FINAL</td>
                    <td  style="font-size:11px;" >S/ ' . ($dineroInicial + $ingresosProductos + $otrosIngresos - $egresosProductos - $otrosEgresos - $otrosIngresosTarjetasCreditos) . '</td>

                </tr>
                </tbody>
            </table>
        </td>
    </tr>


    </tbody>
</table>';


        /* Genera una tabla HTML para un cierre diario de caja con estilo CSS. */
        $pdf2 = '<table style="/* width:430px; */height: 355px;/* border:1px solid black; */border-collapse: collapse;width: 500px;max-width: 500px;margin: 0 auto;">
    <tbody>
    <tr>

<td width="100%">CIERRE DIARIO DE
            CAJA</td><td>C</td>    </tr>





    </tbody>
</table>
</body> 
</html>';


        $pdfPOS = ' <html> <body>  <style>
 td{
  font-size:13px;
 }
         @page {
            margin: 0mm;
            margin-header: 0mm;
            margin-footer: 0mm;
        }

</style><table style="
    width: 290px;
    margin: 0 auto;
    border-collapse: collapse;
    width: 290px;
    max-width: 290px;
    margin: 0 auto;
    margin-top: -50px;
"><tbody><tr><td align="center"><img src="https://images.virtualsoft.tech/site/doradobet/logo-invoice.svg" style="width:120px;"></td></tr><tr><td><div style="font-weight: bold;border: 0px;font-size: 20px;">CIERRE DIARIO DE
    CAJA</div></td></tr></tbody></table> 
<table style="/* width:430px; */height: 400px;/* border:1px solid black; */border-collapse: collapse;width: 290px;max-width: 290px;margin: 0 auto;padding: 0px 10px;border: 0px;">
    <tbody>
    
    
    <tr>

        <td width="290px" align="left">Local: ' . $Usuario->nombre . '</td></tr><tr>
        <td width="290px" align="left">Fecha: ' . $fecha . '</td>
    </tr>
    <tr style="
    /* border-collapse: collapse; */ 
">
        <td width="100%" align="left"  valign="top" style="
        border: 1px solid;
        padding: 0px 10px;
    "><font style="padding-left:5px;text-align:center;font-size:13px;font-weight:normal;">Ingresos diarios por
            Juegos</font>
            <table >
                <tbody>
                <tr>
                    <td width="100%" align="left">Doradobet Tickets</td></tr><tr>
                    <td align="left">S/ ' . $response["Data"]["Tickets"][0]["Amount"] . '</td>

                </tr>
                <tr>
                    <td width="100%" align="left">Doradobet Recargas</td></tr><tr>
                    <td align="left">S/  ' . $response["Data"]["Deposit"][0]["Amount"] . '</td>

                </tr>
                ' . $htmlProductPOS . '

                <tr style="
        font-weight: bold;
        /* padding-top: 6px; */
    ">
                    <td width="100%" align="left" style="font-weight: bold;">(1) INGRESO TOTAL DE JUEGOS</td></tr><tr>
                    <td style="
        border: 2px solid;padding:0px 5px;
    " align="left">S/ ' . $ingresosProductos . '
                    </td>

                </tr>
                </tbody>
            </table>
        </td>
        </tr><tr>
        <td align="left" width="100%"  valign="top" style="
        border: 1px solid;
        padding: 0px 10px;
    "><font style="padding-left:5px;text-align:center;font-size:13px;font-weight:normal;">Salidas diarias por premios o
            devoluciones </font>
            <table style="
    ">
                <tbody>
                <tr>
                    <td width="100%" align="left">Pago Doradobet Tickets</td></tr><tr>
                    <td align="left">S/ ' . $response["Data"]["Tickets"][0]["AmountWin"] . '</td>

                </tr>
                <tr>
                    <td width="100%" align="left">Doradobet Retiros</td></tr><tr>
                    <td align="left">S/ ' . $response["Data"]["Withdraw"][0]["AmountWin"] . '</td>

                </tr>
 ' . $htmlProductExpensePOS . '
                <tr style="
        font-weight: bold;
    ">
                    <td width="100%" align="left"  style="font-weight: bold;font-size:13px;">(2) SALIDAS TOTALES DE JUEGOS</td></tr><tr>
                    <td style="
        border: 2px solid;padding:0px 5px;
    " align="left">S/ ' . $egresosProductos . '
                    </td>

                </tr>
                </tbody>
            </table>
        </td>
    </tr>


    <tr>
        <td width="100%" align="left" valign="top">
            <table style="
        width: 100%;
    ">
                <tbody>
                <tr>
                    <td align="left" width="100%"  style="font-size:13px;">IMPORTE TOTAL DE CAJA (1) - (2)</td></tr><tr>
                    <td align="left" style="
        border: 2px solid;padding:0px 5px;
    ">S/ ' . ($ingresosProductos - $egresosProductos) . '
                    </td>

                </tr>
                </tbody>
            </table>
        </td>
        <td align="center" valign="top"></td>
    </tr>
    <tr>
        <td width="100%" align="center" valign="top">
            <table style="
        width: 100%;
    ">
                <tbody>
                <tr style="
    ">
                    <td align="left" width="100%" style="font-weight: bold;font-size:13px;">(4) GASTOS ADICIONALES DEL DÍA</td>


                </tr>

                </tbody>
            </table>
        </td>
        <td align="center" valign="top"></td>
    </tr>
    <tr>
        <td width="100%" align="center" valign="top" style="padding-left: 15px;">
            <table style="8
        width: 100%;
    ">
                <tbody>

                <tr>
                    <td align="left" width="100%" style="font-size:13px;">GASTOS CON FAC</td></tr><tr>
                    <td align="left" >S/ ' . $otrosEgresos . '</td>

                </tr>

                </tbody>
            </table>
        </td>
        <td align="center" valign="top"></td>
    </tr>
    
    <tr>
        <td width="100%" align="center" valign="top">
            <table style="
        width: 100%;
    ">
                <tbody>
                <tr style="
    ">
                    <td align="left" width="100%" style="font-weight: bold;font-size:13px;">(5) OTROS INGRESOS</td></tr><tr>

                    <td align="left" >S/ ' . $otrosIngresos . '</td>

                </tr>

                </tbody>
            </table>
        </td>
        <td align="center" valign="top"></td>
    </tr>
    <tr>
        <td width="100%" align="center" valign="top">
            <table style="
        width: 100%;
    ">
                <tbody>

                <tr>
                    <td align="left" width="100%"  style="font-size:13px;">CIERRE TOTAL CAJA EFECTIVO (3)-(4)+(5)</td></tr><tr>
                    <td align="left"   style="
        border: 2px solid;padding:0px 5px;
    ">S/ ' . ($ingresosProductos + $otrosIngresos - $egresosProductos - $otrosEgresos) . '
                    </td>

                </tr>

                </tbody>
            </table>
        </td>
        <td align="center" valign="top"></td>
    </tr>
    <tr>

        <td width="100%" align="center" valign="top">
            <table style="
        width: 100%;
    ">
                <tbody>

                <tr>
                    <td align="left" width="100%"  style="font-size:13px;">CIERRE TOTAL DE CAJA TARJETAS</td></tr><tr>
                    <td align="left"   style="
        border: 2px solid;padding:0px 5px;
    ">S/ ' . $otrosIngresosTarjetasCreditos . '
                    </td>

                </tr>
                </tbody>
            </table>
        </td>
        
    </tr>
    <tr>

        <td width="100%" align="left" valign="top">
            <table style="
        width: 100%;
    ">
                <tbody>

                <tr>
                    <td width="100%"  style="font-size:14px;">SALDO INICIAL</td></tr><tr>
                    <td  style="font-size:14px;" >S/ ' . $dineroInicial . '</td>

                </tr>
                </tbody>
            </table>
        </td></tr><tr>
        <td align="left" valign="top">
            <table style="
        width: 100%;
    ">
                <tbody>

                <tr>
                    <td width="100%"  style="font-size:14px;">SALDO FINAL</td></tr><tr>
                    <td  style="font-size:14px;" >S/ ' . ($dineroInicial + $ingresosProductos + $otrosIngresos - $egresosProductos - $otrosEgresos - $otrosIngresosTarjetasCreditos) . '</td>

                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
    <td align="center" valign="top">
            <div style="
        width: 100%;
        border-top: 1px solid;
    ">Cajero
            </div>
            <table style="
        width: 100%;
    ">

            </table>
        </td>
</tr>
    <tr>
    <td align="center" valign="top">
            <div style="
        width: 100%;
        border-top: 1px solid;
    ">
            </div>
            <table style="
        width: 100%;
    ">

            </table>
        </td>
</tr>


    </tbody>
</table>';


        /* genera un PDF utilizando el paquete mPDF en PHP. */
        $response["PdfPOS2"] = ($pdfPOS);

        require_once "mpdf6.1/mpdf.php";
        $mpdf = new mPDF('c', array(45, 150), 0, 0, 0, 0);
//$mpdf = new mPDF('c', 'A4-L');

        $mpdf->mirrorMargins = 1; // Use different Odd/Even headers and footers and mirror margins (1 or 0)


        /* Configura la visualización de PDF y carga una hoja de estilo CSS. */
        $mpdf->SetDisplayMode('fullpage', 'two');

// LOAD a stylesheet
//$stylesheet = file_get_contents('mdpdf/stylemdpdf.css');
//$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text

        $mpdf->WriteHTML($pdf);


        /* genera un PDF y lo guarda en la ruta especificada. */
        $mpdf->Output('/tmp' . "/mpdf.pdf", "F");

        $path = '/tmp' . '/mpdf.pdf';

        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);

        /* Codifica datos y PDFs en base64 para incluirlos en respuestas JSON. */
        $base64 = 'data:application/' . $type . ';base64,' . base64_encode($data);

        $encoded_html = base64_encode($pdf);

        $response["Pdf"] = base64_encode($data);

        $response["Pdf2"] = $pdf;


        /* Se configura mPDF para generar un documento PDF con márgenes personalizados y visualizarlo. */
        $mpdf = new mPDF('c', array(80, 700), 0, 0, 0, 0);
//$mpdf = new mPDF('c', 'A4-L');

        $mpdf->mirrorMargins = 0; // Use different Odd/Even headers and footers and mirror margins (1 or 0)

        $mpdf->SetDisplayMode('fullpage', 'continuous');

// LOAD a stylesheet
//$stylesheet = file_get_contents('mdpdf/stylemdpdf.css');
//$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text


        /* Genera un PDF a partir de HTML y lo guarda en una ruta específica. */
        $mpdf->WriteHTML($pdfPOS);

        $mpdf->Output('/tmp' . "/mpdf.pdf", "F");

        $path = '/tmp' . '/mpdf.pdf';

        $type = pathinfo($path, PATHINFO_EXTENSION);

        /* convierte archivos a formato base64 para ser utilizados en respuestas JSON. */
        $data = file_get_contents($path);
        $base64 = 'data:application/' . $type . ';base64,' . base64_encode($data);

        $encoded_html = base64_encode($pdf);

        $response["PdfPOS"] = base64_encode($data);
    } else {
        /* maneja un error, estableciendo un mensaje de éxito y campos vacíos. */

        $response["HasError"] = true;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "Ya se cerro el día anteriormente.";
        $response["ModelErrors"] = [];

        $response["PdfPOS"] = "";

    }
} catch (Exception $e) {
    /* Captura excepciones y muestra información sobre el error en PHP. */

    print_r($e);
}