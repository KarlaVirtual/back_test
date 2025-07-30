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
 * Bonus/AddBonusBalance
 *
 * Agregar saldo de bonos especifico a un saldo directamente
 *
 * @param no
 *
 * @return no
 * {"HasError":boolean,"AlertType": string,"AlertMessage": string,"url": string,"success": string}
 *
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */

/**
 * @OA\Post(path="apipv/Bonus/AddBOnusBalance", tags={"Bonus"}, description = "",
 *
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="json",
 *             @OA\Schema(required={},
 *               @OA\Property(
 *                   property="UserId",
 *                   description="Identificador nuemrico del usuario",
 *                   type="integer",
 *                   example= 469
 *               ),
 *               @OA\Property(
 *                   property="PlayerId",
 *                   description="Identificador del jugador",
 *                   type="integer",
 *                   example= "12"
 *               ),
 *               @OA\Property(
 *                   property="dateTo",
 *                   description="indice del registro",
 *                   type="integer",
 *                   example= "2016-10-31 23:59:59"
 *               ),
 *               @OA\Property(
 *                   property="dateFrom",
 *                   description="fecha inicio",
 *                   type="integer",
 *                   example= "2016-10-01 00:00:00"
 *               ),
 *               @OA\Property(
 *                   property="count",
 *                   description="total de registros",
 *                   type="integer",
 *                   example= "30"
 *               ),
 *             )
 *         ),
 *     ),
 *
 * @OA\Response (
 *      response="200",
 *      description="Success",
 *         @OA\MediaType(
 *             mediaType="json",
 *             @OA\Schema(
 *               @OA\Property(
 *                   property="HasError",
 *                   description="Hay error",
 *                   type="boolean",
 *                   example= false
 *               ),
 *               @OA\Property(
 *                   property="AlertType",
 *                   description="Mensaje de la API",
 *                   type="string",
 *                   example= "success"
 *               ),
 *               @OA\Property(
 *                   property="AlertMessage",
 *                   description="Mensaje con el error especifico",
 *                   type="string",
 *                   example= "0"
 *               ),
 *               @OA\Property(
 *                   property="ModelErrors",
 *                   description="",
 *                   type="Array",
 *                   example= {}
 *               ),
 *               @OA\Property(
 *                   property="Result",
 *                   description="Total registros",
 *                   type="Array",
 *                   example= {}
 *               ),
 *             )
 *         ),
 *         )
 * ),
 * @OA\Response (response="404", description="Not found")
 * )
 */

/**
 * Bonus/MassiveAddBonusBalance
 *
 * Este script agrega saldos de bonos masivos a los usuarios especificados en un archivo CSV.
 *
 * @param object $params Objeto que contiene los siguientes parámetros:
 * @param string $params ->CSV Archivo CSV codificado en base64 con los datos de los usuarios y montos.
 * @param float $params ->Value Monto del bono.
 * @param string $params ->Reference Referencia o descripción del bono.
 * @param int $params ->TypeBalance Tipo de saldo (0 para torneo casino, 1 para torneo deportivas).
 * @param int $params ->Type Tipo de bono (0-7).
 * @param int $params ->UserId Identificador del usuario.
 *
 *
 * @return array Respuesta en formato JSON con los siguientes campos:
 *  - HasError (boolean): Indica si ocurrió un error.
 *  - AlertType (string): Tipo de alerta (e.g., 'success', 'danger').
 *  - AlertMessage (string): Mensaje de alerta.
 *  - ModelErrors (array): Lista de errores del modelo.
 *  - Result (array): Resultado de la operación.
 *
 * @throws Exception Si ocurre un error durante la ejecución o si los parámetros son inválidos.
 */

/* Asignación de parámetros relacionados con un cliente y su balance de tipo específico. */
$ClientIdCsv = $params->CSV;

$Amount = $params->Value;
$Description = $params->Reference;
$TypeBalance = $params->TypeBalance;

/*
 * 0 es torneo casino
 * 1 es torneo deportivas
 */
$Type = $params->Type;

/* Decodifica un CSV de ClientId base64 y reemplaza puntos y comas por comas. */
$UserId = $params->UserId;

$ClientIdCsv = explode("base64,", $ClientIdCsv);
$ClientIdCsv = $ClientIdCsv[1];
$ClientIdCsv = base64_decode($ClientIdCsv);
$ClientIdCsv = str_replace(";", ",", $ClientIdCsv);


/* divide un CSV en líneas y asigna una cantidad a una variable. */
$lines = explode(PHP_EOL, $ClientIdCsv);
$lines = preg_split('/\r\n|\r|\n/', $ClientIdCsv);

$AmountCVS = $ClientIdCsv[1];


$array = array();

/* convierte líneas de texto en un array usando CSV y cuenta elementos. */
foreach ($lines as $line) {
    $array[] = str_getcsv($line);

}

$countArray = oldCount($array[0]);


/* recorre un array y extrae columnas específicas, almacenando resultados en otro array. */
for ($i = 0; $i <= $countArray; $i++) {
    $arrayfinal = array();
    $arrayfinal = array_column($array, $i);
}

//$arrayfinal =  array_column($array,'0'); //columna Id de clientes
//$arrayfinal3 =  array_column($array,'1'); // Valor del bono

//$primera = substr($arrayfinal[0], 3);

//$arrayfinal[0] = $primera;

$posiciones = array_keys($array);


/* manipula arrays y convierte datos, eliminando un elemento específico. */
$ultima = strval(end($posiciones));
$arrayfinal = json_decode(json_encode($array));


unset($arrayfinal[$ultima]);


/*
$ids = implode(",",$arrayfinal);
$valores = implode(",",$arrayfinal3);
if($ids != ""){

    $clients = $ids;
    $clients = explode(",",$clients);
}

if($valores != ""){

    $Amounts = $valores;
    $Amounts = explode(",",$Amounts);
}*/


$seguir = true;


/* valida que $TypeBalance y $Type estén en rangos permitidos. */
if ($TypeBalance != 0 && $TypeBalance != 1) {
    $seguir = false;
}


if ($Type != 0 && $Type != 1 && $Type != 2 && $Type != 3 && $Type != 4 && $Type != 5 && $Type != 6 && $Type != 7) {
    $seguir = false;
}


/* verifica si la variable $Description está vacía y desactiva $seguir. */
if ($Description == "") {
    $seguir = false;
}


if ($seguir) {


    /* asigna letras "TC" o "TD" basadas en el valor de $Type. */
    if ($Type == 0) {
        $Type = "TC";
    }

    if ($Type == 1) {
        $Type = "TD";
    }


    /* asigna nombres descriptivos a tipos numéricos específicos. */
    if ($Type == 2) {
        $Type = "TV";
    }

    if ($Type == 3) {
        $Type = "TL";
    }


    /* cambia el valor de `$Type` según su valor inicial. */
    if ($Type == 4) {
        $Type = "S";
    }


    if ($Type == 5) {
        $Type = "SC";
    }


    /* asigna valores de texto a variables según su número correspondiente. */
    if ($Type == 6) {
        $Type = "SV";
    }


    if ($Type == 7) {
        $Type = "SL";
    }

    //$UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

    /* Se crea un objeto UsuarioMandante utilizando el usuario de sesión y se define tipo. */
    $UsuarioMandante = new UsuarioMandante($_SESSION['usuario2']);

    $tipo = 'E';

    if (!empty($arrayfinal)) {


        foreach ($arrayfinal as $key => $valueClient) {

            /* limpia y formatea el identificador del cliente eliminando caracteres no deseados. */
            $ClientId = $valueClient[0];
            $ClientId = preg_replace('/[\xE2\x80\xAF]/', '', $ClientId);
            $ClientId = str_replace(" ", '', $ClientId);
            $ClientId = preg_replace("/[^0-9.]/", "", $ClientId);

            if ($ClientId != "" && $ClientId != "0") {

                try {


                    /* Se crea un usuario y un registro de bono asociado a él. */
                    $Usuario = new Usuario($ClientId);


                    $BonoLog = new BonoLog();
                    $BonoLog->setUsuarioId($Usuario->usuarioId);
                    $BonoLog->setTipo($Type);

                    /* configura un objeto BonoLog con valores y estado específicos. */
                    $BonoLog->setValor($valueClient[1]);
                    $BonoLog->setFechaCrea(date('Y-m-d H:i:s'));
                    $BonoLog->setEstado('L');
                    $BonoLog->setErrorId(0);
                    $BonoLog->setIdExterno($Description);
                    $BonoLog->setMandante($Usuario->mandante);

                    /* Código establece propiedades de un objeto BonoLog y crea un DAO para manipulación. */
                    $BonoLog->setFechaCierre('');
                    $BonoLog->setTransaccionId('');
                    $BonoLog->setTipobonoId(4);
                    $BonoLog->setTiposaldoId($TypeBalance);


                    $BonoLogMySqlDAO = new BonoLogMySqlDAO();


                    /* registra una transacción y creditos a un usuario basado en condiciones. */
                    $Transaction = $BonoLogMySqlDAO->getTransaction();

                    $bonologId = $BonoLogMySqlDAO->insert($BonoLog);


                    if ($TypeBalance == 0) {

                        $Usuario->credit($valueClient[1], $Transaction);

                    } elseif ($TypeBalance == 1) {
                        /* Condicional que ejecuta la función creditWin si el tipo de balance es 1. */

                        $Usuario->creditWin($valueClient[1], $Transaction);

                    }


                    /* Se crea un nuevo registro de historial de usuario con datos específicos. */
                    $UsuarioHistorial = new UsuarioHistorial();
                    $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                    $UsuarioHistorial->setDescripcion('');
                    $UsuarioHistorial->setMovimiento($tipo);
                    $UsuarioHistorial->setUsucreaId($UsuarioMandante->usuarioMandante);
                    $UsuarioHistorial->setUsumodifId(0);

                    /* Inserta un registro en la base de datos con datos del historial del usuario. */
                    $UsuarioHistorial->setTipo(50);
                    $UsuarioHistorial->setValor($valueClient[1]);
                    $UsuarioHistorial->setExternoId($bonologId);

                    $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                    $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);


                    /* confirma una transacción y prepara una respuesta sin errores. */
                    $Transaction->commit();

                    $response["HasError"] = false;
                    $response["AlertType"] = "danger";
                    $response["AlertMessage"] = "";
                    $response["ModelErrors"] = [];

                    /* Crea un array vacío llamado "Result" en la respuesta. */
                    $response["Result"] = array();


                } catch (Exception $e) {
                    /* Captura excepciones y almacena el mensaje de error en la variable $msg. */


                    $msg = $e->getMessage();

                }

            }
        }
    } else {
        /* gestiona errores, configurando una respuesta con alertas y mensajes vacíos. */

        $response["HasError"] = true;
        $response["AlertType"] = "danger";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];
        $response["Result"] = array();

    }
}