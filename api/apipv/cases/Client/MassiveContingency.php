<?php

use Backend\dto\ApiTransaction;
use Backend\dto\Area;
use Backend\dto\AuditoriaGeneral;
use Backend\dto\UsuarioLog;
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
use Backend\mysql\AuditoriaGeneralMySqlDAO;
use Backend\mysql\UsuarioLogMySqlDAO;
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
 * Client/MassiveContingency
 *
 * Este script permite activar contingencias masivas para usuarios.
 *
 * @param object $params Objeto que contiene los siguientes valores:
 * @params string $params->CSV Cadena codificada en base64 con los IDs de los usuarios.
 * @params int $params->Type Tipo de operación (0 para usuarios online, 1 para puntos de venta).
 * @params string $params->Description Descripción de la contingencia.
 * @params array $params->Contingencies Lista de contingencias a activar.
 *
 *
 *  * @return array $response Respuesta con los siguientes valores:
 * - HasError (boolean): Indica si ocurrió un error.
 * - AlertType (string): Tipo de alerta (e.g., "success", "danger").
 * - AlertMessage (string): Mensaje de alerta.
 * - ModelErrors (array): Lista de errores del modelo.
 * - Data (array): Datos adicionales.
 *
 * @throws Exception Si los usuarios no pertenecen al partner.
 * @throws Exception Si ocurre un error al procesar las contingencias.
 *
 */

/**
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
 *                   property="Data",
 *                   description="",
 *                   type="Array",
 *                   example= {}
 *               ),
 *             )
 *         ),
 *         )
 * ),
 * @OA\Response (response="404", description="Not found")
 * ),
 */


/* lee datos JSON de una solicitud y extrae 'CSV' y 'Type'. */
$params = file_get_contents('php://input');
//$params = base64_decode($params);
$params = json_decode($params);

$ClientIdCsv = $params->CSV;
$type = $params->Type;

/* Extrae y convierte contingencias a números desde los parámetros de entrada. */
$Description = $params->Description;
$Contingencies = $params->Contingencies;

$ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
$ip = explode(",", $ip)[0];


if (isset($Contingencies) && $Contingencies !== null) {
    // Extraer los números de la cadena "Contingencies"
    $contingencies = explode(',', $params->Contingencies);
    $Contingencies = array_map('intval', $contingencies);
}


/* extrae, decodifica y reemplaza caracteres en un CSV codificado en base64. */
$ClientIdCsv = explode("base64,", $ClientIdCsv);
$ClientIdCsv = $ClientIdCsv[1];

$ClientIdCsv = base64_decode($ClientIdCsv);

$ClientIdCsv = str_replace(";", ",", $ClientIdCsv);


/* divide un CSV en líneas y las convierte a un array. */
$lines = explode(PHP_EOL, $ClientIdCsv);
$lines = preg_split('/\r\n|\r|\n/', $ClientIdCsv);


$array = array();
foreach ($lines as $line) {
    $array[] = str_getcsv($line);

}

/* cuenta elementos de un array y extrae columnas en un nuevo array. */
$countArray = count($array[0]);

for ($i = 0; $i <= $countArray; $i++) {
    $arrayfinal = array();
    $arrayfinal = array_column($array, $i);
}

/* extrae y convierte columnas de un arreglo a JSON, almacenando posiciones finales. */
$arrayfinal = array_column($array, '0');

$posiciones = array_keys($arrayfinal);

$ultima = strval(end($posiciones));
$arrayfinal = json_decode(json_encode($arrayfinal));
//unset($arrayfinal[$ultima]);


/* Convierte un arreglo en una cadena y la separa nuevamente en un arreglo. */
$ids = implode(",", $arrayfinal);
if ($ids != "") {

    $clients = $ids;
    $clients = explode(",", $clients);
}


// Si el usuario esta condicionado por País

/* verifica condiciones de sesión para asignar variables de país y mandante. */
if ($_SESSION['PaisCond'] == "S") {
    $Pais = $_SESSION['pais_id'];
}
// Si el usuario esta condicionado por el mandante y no es de Global
if ($_SESSION['Global'] == "N") {
    $Mandante = $_SESSION['mandante'];
} else {
    /* Verifica si "mandanteLista" tiene valor válido en la sesión y lo asigna. */

    if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
        $Mandante = $_SESSION["mandanteLista"];
    }

}


/* Código inicializa un array de respuesta sin errores, con tipo y mensaje de alerta. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = '';
$response["ModelErrors"] = [];

$response["Data"] = [];

if ((count($clients) > 0 || (count($clients) == 0))) {

    foreach ($clients as $key => $ClientId) {


        /* limpia y formatea la variable $ClientId eliminando caracteres no deseados. */
        $ClientId = preg_replace('/[\xE2\x80\xAF]/', '', $ClientId);
        $ClientId = str_replace(" ", '', $ClientId);
        $ClientId = preg_replace("/[^0-9.]/", "", $ClientId);

        if ($ClientId != "" && $ClientId != "0") {


            /* Se crea una nueva instancia de la clase UsuarioPerfil utilizando un ClientId específico. */
            $UsuarioPerfil = new UsuarioPerfil($ClientId);

            try {

                if ($type == 1 and $UsuarioPerfil->perfilId == "PUNTOVENTA") {


                    /* Se crea una instancia de la clase Usuario con el identificador del cliente proporcionado. */
                    $Usuario = new Usuario($ClientId);

                    if ($Pais == $Usuario->paisId || $Pais == "") {


                        foreach ($Contingencies as $valor) {
                            switch ($valor) {
                                case '0':
                                    /* establece el estado de contingencia del usuario a 'A'. */

                                    $valorAntesContingenciaUsuario = $Usuario->contingencia;
                                    $Usuario->contingencia = 'A';
                                    break;
                                case '1':
                                    /* Asigna el valor 'A' a la propiedad contingenciaDeportes del objeto Usuario. */

                                    $valorAntesContingenciaDeportes = $Usuario->contingenciaDeportes = 'A';
                                    $Usuario->contingenciaDeportes = 'A';
                                    break;
                                case '2':
                                    /* Asigna el valor 'A' a la propiedad contingenciaCasino del objeto Usuario. */

                                    $valorAntesContingenciaCasino = $Usuario->contingenciaCasino = 'A';
                                    $Usuario->contingenciaCasino = 'A';
                                    break;
                                case '3':
                                    /* asigna el valor 'A' a la propiedad contingenciaCasvivo del objeto Usuario. */

                                    $valorAntesContingenciaCasvivo = $Usuario->contingenciaCasvivo = 'A';
                                    $Usuario->contingenciaCasvivo = 'A';
                                    break;
                                case '4':
                                    /* Se asigna el valor 'A' a la propiedad contingenciaVirtuales del objeto Usuario. */

                                    $valorAntesContingenciaVirtuales = $Usuario->contingenciaVirtuales = 'A';
                                    $Usuario->contingenciaVirtuales = 'A';
                                    break;
                                case '5':
                                    /* Asigna el valor 'A' a la propiedad contingenciaPoker del objeto Usuario. */

                                    $valorAntesContingenciaPoker = $Usuario->contingenciaPoker = 'A';
                                    $Usuario->contingenciaPoker = 'A';
                                    break;
                                case '6':
                                    /* Asignación de valor 'A' a la propiedad contingenciaRetiro del objeto Usuario. */

                                    $valorAntesContingenciaRetiro = $Usuario->contingenciaRetiro = 'A';
                                    $Usuario->contingenciaRetiro = 'A';
                                    break;
                                case '7':
                                    /* Asignación de valor 'A' a la propiedad contingenciaDeposito del objeto Usuario. */

                                    $valorAntesContingenciaDeposito = $Usuario->contingenciaDeposito = 'A';
                                    $Usuario->contingenciaDeposito = 'A';
                                    break;
                                case '8':

                                    /* Actualiza el estado de configuración de usuario si está inactivo. */
                                    $valorAntesAbusadorBonos = '';
                                    $UsuarioConfiguracion = new UsuarioConfiguracion();
                                    $UsuarioConfiguracion = $UsuarioConfiguracion->getUserBondABuser($Usuario->usuarioId);
                                    if (!empty($UsuarioConfiguracion->usuconfigId)) {
                                        if ($UsuarioConfiguracion->estado === 'I') {
                                            $valorAntesAbusadorBonos = $UsuarioConfiguracion->estado;
                                            $UsuarioConfiguracion->estado = 'A';
                                            $UsuarioConfiguracion->valor = 'A';
                                            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
                                            $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);
                                            $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();
                                        }
                                    } else {
                                        /* inserta una nueva configuración de usuario en la base de datos. */

                                        $Clasificador = new Clasificador('', 'BONDABUSER');
                                        $UsuarioConfiguracion = new UsuarioConfiguracion();
                                        $UsuarioConfiguracion->usuarioId = $Usuario->usuarioId;
                                        $UsuarioConfiguracion->valor = 'A';
                                        $UsuarioConfiguracion->usucreaId = $Usuario->usuarioId;
                                        $UsuarioConfiguracion->tipo = $Clasificador->getClasificadorId();
                                        $UsuarioConfiguracion->usumodifId = 0;
                                        $UsuarioConfiguracion->productoId = 0;
                                        $UsuarioConfiguracion->estado = 'A';
                                        $UsuarioConfiguracion->nota = '';
                                        $UsuarioConfiguracion->fechaCrea = date('Y-m-d H:i:s');
                                        $UsuarioConfiguracion->fechaModif = date('Y-m-d H:i:s');

                                        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
                                        $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);
                                        $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();
                                    }
                                    break;
                            }

                        }


                        /* concatena una descripción a las observaciones del usuario si no está vacía. */
                        $msg = "entro5";

                        if ($Description != '') {
                            $Usuario->observ = $Usuario->observ . ' ' . $Description;
                        }

                        $UsuarioMySqlDAO = new UsuarioMySqlDAO();

                        /* Actualiza un usuario y confirma la transacción; inicia registro de contingencias. */
                        $UsuarioMySqlDAO->update($Usuario);
                        $UsuarioMySqlDAO->getTransaction()->commit();

                        //* Crear un foreach de logs para cada caso de contigencias

                        $contingenciaLogs = [];

                        foreach ($Contingencies as $valor) {
                            switch ($valor) {
                                case '0':
                                    /* registra un log de contingencia con valores específicos del usuario. */

                                    $contingenciaLogs[] = [
                                        'tipo' => 'CONTINGENCIAUSUARIO',
                                        'valorAntes' => $valorAntesContingenciaUsuario,
                                        'valorDespues' => 'A'
                                    ];
                                    break;
                                case '1':
                                    /* Añade un registro de contingencia en un arreglo según un caso específico. */

                                    $contingenciaLogs[] = [
                                        'tipo' => 'CONTINGENCIADEPORTEUSUARIO',
                                        'valorAntes' => $valorAntesContingenciaDeportes,
                                        'valorDespues' => 'A'
                                    ];
                                    break;
                                case '2':
                                    /* Agrega un registro de contingencia con valores antes y después para el usuario del casino. */

                                    $contingenciaLogs[] = [
                                        'tipo' => 'CONTINGENCIACASINOUSUARIO',
                                        'valorAntes' => $valorAntesContingenciaCasino,
                                        'valorDespues' => 'A'
                                    ];
                                    break;
                                case '3':
                                    /* Registra una contingencia con valores antes y después para usuario sin vivo. */

                                    $contingenciaLogs[] = [
                                        'tipo' => 'CONTINGENCIACASINOVIVOUSUARIO',
                                        'valorAntes' => $valorAntesContingenciaCasvivo,
                                        'valorDespues' => 'A'
                                    ];
                                    break;
                                case '4':
                                    /* Agrega un registro de contingencia virtual al array según condición específica. */

                                    $contingenciaLogs[] = [
                                        'tipo' => 'CONTINGENCIAVIRTUALESUSUARIO',
                                        'valorAntes' => $valorAntesContingenciaVirtuales,
                                        'valorDespues' => 'A'
                                    ];
                                    break;
                                case '5':
                                    /* agrega un registro de contingencia a un array según una condición específica. */

                                    $contingenciaLogs[] = [
                                        'tipo' => 'CONTINGENCIAPOKERUSUARIO',
                                        'valorAntes' => $valorAntesContingenciaPoker,
                                        'valorDespues' => 'A'
                                    ];
                                    break;
                                case '6':
                                    /* Añade un registro de contingencia de retiro de usuario al array correspondiente. */

                                    $contingenciaLogs[] = [
                                        'tipo' => 'CONTINGENCIARETIROUSUARIO',
                                        'valorAntes' => $valorAntesContingenciaRetiro,
                                        'valorDespues' => 'A'
                                    ];
                                    break;
                                case '7':
                                    /* Código que registra un evento de contingencia para un depósito de usuario. */

                                    $contingenciaLogs[] = [
                                        'tipo' => 'CONTINGENCIADEPOSITOUSUARIO',
                                        'valorAntes' => $valorAntesContingenciaDeposito,
                                        'valorDespues' => 'A'
                                    ];
                                    break;
                                case '8':
                                    /* agrega un registro de abuso a un array según un caso específico. */

                                    $contingenciaLogs[] = [
                                        'tipo' => 'BONDABUSER',
                                        'valorAntes' => $valorAntesAbusadorBonos,
                                        'valorDespues' => 'A'
                                    ];
                                    break;
                            }
                        }

                        // Después de crear los logs, insertarlos en la base de datos

                        /* Inserta registros de logs de usuario en la base de datos. */
                        foreach ($contingenciaLogs as $log) {
                            $UsuarioLog = new UsuarioLog();
                            $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                            $UsuarioLog->setUsuarioIp('');
                            $UsuarioLog->setUsuariosolicitaId($_SESSION["usuario"]);
                            $UsuarioLog->setUsuariosolicitaIp($ip);
                            $UsuarioLog->setUsuarioaprobarIp(0);
                            $UsuarioLog->setTipo($log['tipo']);
                            $UsuarioLog->setValorAntes($log['valorAntes']);
                            $UsuarioLog->setValorDespues($log['valorDespues']);
                            $UsuarioLog->setUsucreaId($_SESSION["usuario"]);
                            $UsuarioLog->setUsumodifId(0);
                            $UsuarioLog->setEstado("A");
                            $UsuarioLog->setDispositivo(0);

                            $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
                            $UsuarioLogMySqlDAO->insert($UsuarioLog);
                            $UsuarioLogMySqlDAO->getTransaction()->commit();
                        }


                        /* Se asigna el valor "entro6" a la variable $msg en PHP. */
                        $msg = "entro6";
                    }

                } elseif ($type == 0) {

                    /* Se crea un nuevo objeto Usuario con el ClientId en minúsculas. */
                    $Usuario = new Usuario(strtolower($ClientId));
                    if ($UsuarioPerfil->perfilId == "USUONLINE" and $Pais == $Usuario->paisId || $Pais == "") {
                        foreach ($Contingencies as $valor) {
                            switch ($valor) {
                                case '0':
                                    /* Cambia el valor de contingencia del usuario a 'A' si es '0'. */

                                    $valorAntesContingenciaUsuario = $Usuario->contingencia;
                                    $Usuario->contingencia = 'A';
                                    break;
                                case '1':
                                    /* Asignación de valor 'A' a la propiedad contingenciaDeportes del usuario si el caso es 1. */

                                    $valorAntesContingenciaDeportes = $Usuario->contingenciaDeportes;
                                    $Usuario->contingenciaDeportes = 'A';
                                    break;
                                case '2':
                                    /* Asigna 'A' a contingenciaCasino del usuario si el caso es '2'. */

                                    $valorAntesContingenciaCasino = $Usuario->contingenciaCasino;
                                    $Usuario->contingenciaCasino = 'A';
                                    break;
                                case '3':
                                    /* Asignación de valor 'A' a contingenciaCasvivo de Usuario en caso de ser '3'. */

                                    $valorAntesContingenciaCasvivo = $Usuario->contingenciaCasvivo;
                                    $Usuario->contingenciaCasvivo = 'A';
                                    break;
                                case '4':
                                    /* asigna 'A' a la propiedad contingenciaVirtuales del usuario. */

                                    $valorAntesContingenciaVirtuales = $Usuario->contingenciaVirtuales;
                                    $Usuario->contingenciaVirtuales = 'A';
                                    break;
                                case '5':
                                    /* Cambia el valor de contingenciaPoker del usuario a 'A' si es caso '5'. */

                                    $valorAntesContingenciaPoker = $Usuario->contingenciaPoker;
                                    $Usuario->contingenciaPoker = 'A';
                                    break;
                                case '6':
                                    /* asigna un valor en caso de contingencia deportiva. */

                                    $valorAntesContingenciaRetiro = $Usuario->contingenciaDeportes;
                                    $Usuario->contingenciaRetiro = 'A';
                                    break;
                                case '7':
                                    /* Asigna 'A' a contingenciaDeposito del usuario si el caso es '7'. */

                                    $valorAntesContingenciaDeposito = $Usuario->contingenciaDeposito;
                                    $Usuario->contingenciaDeposito = 'A';
                                    break;
                                case '8':

                                    /* Actualiza el estado de configuración de usuario si está inactivo. */
                                    $valorAntesAbusadorBonos = '';
                                    $UsuarioConfiguracion = new UsuarioConfiguracion();
                                    $UsuarioConfiguracion = $UsuarioConfiguracion->getUserBondABuser($Usuario->usuarioId);
                                    if (!empty($UsuarioConfiguracion->usuconfigId)) {
                                        if ($UsuarioConfiguracion->estado === 'I') {
                                            $valorAntesAbusadorBonos = $UsuarioConfiguracion->estado;
                                            $UsuarioConfiguracion->estado = 'A';
                                            $UsuarioConfiguracion->valor = 'A';
                                            $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
                                            $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);
                                            $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();
                                        }
                                    } else {
                                        /* Crea y guarda una nueva configuración de usuario en la base de datos. */

                                        $Clasificador = new Clasificador('', 'BONDABUSER');
                                        $UsuarioConfiguracion = new UsuarioConfiguracion();
                                        $UsuarioConfiguracion->usuarioId = $Usuario->usuarioId;
                                        $UsuarioConfiguracion->valor = 'A';
                                        $UsuarioConfiguracion->usucreaId = $Usuario->usuarioId;
                                        $UsuarioConfiguracion->tipo = $Clasificador->getClasificadorId();
                                        $UsuarioConfiguracion->usumodifId = 0;
                                        $UsuarioConfiguracion->productoId = 0;
                                        $UsuarioConfiguracion->estado = 'A';
                                        $UsuarioConfiguracion->nota = '';
                                        $UsuarioConfiguracion->fechaCrea = date('Y-m-d H:i:s');
                                        $UsuarioConfiguracion->fechaModif = date('Y-m-d H:i:s');

                                        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
                                        $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);
                                        $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();
                                    }
                                    break;
                            }
                        }


                        /* agrega una descripción a las observaciones de un usuario si no está vacía. */
                        $msg = "entro5";

                        if ($Description != '') {
                            $Usuario->observ = $Usuario->observ . ' ' . $Description;
                        }

                        $UsuarioMySqlDAO = new UsuarioMySqlDAO();

                        /* Actualiza un usuario y confirma la transacción, preparando un registro de contingencias. */
                        $UsuarioMySqlDAO->update($Usuario);
                        $UsuarioMySqlDAO->getTransaction()->commit();

                        //* Crear un foreach de logs para cada caso de contigencias

                        $contingenciaLogs = [];

                        foreach ($Contingencies as $valor) {
                            switch ($valor) {
                                case '0':
                                    /* agrega un registro de contingencia con valores específicos en un array. */

                                    $contingenciaLogs[] = [
                                        'tipo' => 'CONTINGENCIAUSUARIO',
                                        'valorAntes' => $valorAntesContingenciaUsuario,
                                        'valorDespues' => 'A'
                                    ];
                                    break;
                                case '1':
                                    /* asigna un log de contingencia al tipo específico 'CONTINGENCIADEPORTEUSUARIO'. */

                                    $contingenciaLogs[] = [
                                        'tipo' => 'CONTINGENCIADEPORTEUSUARIO',
                                        'valorAntes' => $valorAntesContingenciaDeportes,
                                        'valorDespues' => 'A'
                                    ];
                                    break;
                                case '2':
                                    /* Registro de logs de contingencia para usuario de casino y valores asociados. */

                                    $contingenciaLogs[] = [
                                        'tipo' => 'CONTINGENCIACASINOUSUARIO',
                                        'valorAntes' => $valorAntesContingenciaCasino,
                                        'valorDespues' => 'A'
                                    ];
                                    break;
                                case '3':
                                    /* añade un registro de contingencia al array `contingenciaLogs`. */

                                    $contingenciaLogs[] = [
                                        'tipo' => 'CONTINGENCIACASINOVIVOUSUARIO',
                                        'valorAntes' => $valorAntesContingenciaCasvivo,
                                        'valorDespues' => 'A'
                                    ];
                                    break;
                                case '4':
                                    /* Agrega un registro de contingencia virtual al array con valores antes y después. */

                                    $contingenciaLogs[] = [
                                        'tipo' => 'CONTINGENCIAVIRTUALESUSUARIO',
                                        'valorAntes' => $valorAntesContingenciaVirtuales,
                                        'valorDespues' => 'A'
                                    ];
                                    break;
                                case '5':
                                    /* El fragmento almacena un log de contingencia para poker del usuario. */

                                    $contingenciaLogs[] = [
                                        'tipo' => 'CONTINGENCIAPOKERUSUARIO',
                                        'valorAntes' => $valorAntesContingenciaPoker,
                                        'valorDespues' => 'A'
                                    ];
                                    break;
                                case '6':
                                    /* Agrega un log de contingencia al array, especificando tipo y valores antes/después. */

                                    $contingenciaLogs[] = [
                                        'tipo' => 'CONTINGENCIARETIROUSUARIO',
                                        'valorAntes' => $valorAntesContingenciaRetiro,
                                        'valorDespues' => 'A'
                                    ];
                                    break;
                                case '7':
                                    /* agrega un registro de contingencia para un depósito de usuario. */

                                    $contingenciaLogs[] = [
                                        'tipo' => 'CONTINGENCIADEPOSITOUSUARIO',
                                        'valorAntes' => $valorAntesContingenciaDeposito,
                                        'valorDespues' => 'A'
                                    ];
                                    break;
                                case '8':
                                    /* Registro de cambios para un usuario, actualizando valor de bonos. */

                                    $contingenciaLogs[] = [
                                        'tipo' => 'BONDABUSER',
                                        'valorAntes' => $valorAntesAbusadorBonos,
                                        'valorDespues' => 'A'
                                    ];
                                    break;
                            }
                        }

                        // Después de crear los logs, insertarlos en la base de datos

                        /* Registra logs de usuario en la base de datos para un proceso específico. */
                        foreach ($contingenciaLogs as $log) {
                            $UsuarioLog = new UsuarioLog();
                            $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                            $UsuarioLog->setUsuarioIp('');
                            $UsuarioLog->setUsuariosolicitaId($_SESSION["usuario"]);
                            $UsuarioLog->setUsuariosolicitaIp($ip);
                            $UsuarioLog->setUsuarioaprobarIp(0);
                            $UsuarioLog->setTipo($log['tipo']);
                            $UsuarioLog->setValorAntes($log['valorAntes']);
                            $UsuarioLog->setValorDespues($log['valorDespues']);
                            $UsuarioLog->setUsucreaId($_SESSION["usuario"]);
                            $UsuarioLog->setUsumodifId(0);
                            $UsuarioLog->setEstado("A");
                            $UsuarioLog->setDispositivo(0);

                            $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
                            $UsuarioLogMySqlDAO->insert($UsuarioLog);
                            $UsuarioLogMySqlDAO->getTransaction()->commit();
                        }


                        /* asigna el valor "entro6" a la variable $msg en PHP. */
                        $msg = "entro6";
                    } else {
                        /* Lanzo una excepción si los usuarios no son del socio correspondiente. */

                        throw new Exception("Usuarios no pertenecen al partner", '60003');
                    }

                }
            } catch (Exception $e) {
                /* Captura excepciones y almacena el mensaje de error en la variable $msg. */

                $msg = $e->getMessage();

            }

        } else {
            /* se encarga de gestionar respuestas sin errores en un sistema. */

            /* $response["HasError"] = false;
             $response["AlertType"] = "success";
             $response["AlertMessage"] = $msg;
             $response["ModelErrors"] = [];

             $response["Data"] = [];*/
        }
    }


    /* Se instancia un objeto 'AuditoriaGeneral' y se configuran propiedades relacionadas con el usuario. */
    $AuditoriaGeneral = new AuditoriaGeneral();

    $AuditoriaGeneral->setUsuarioId($_SESSION["usuario"]);
    $AuditoriaGeneral->setUsuarioIp($ip);
    $AuditoriaGeneral->setUsuariosolicitaId($_SESSION["usuario"]); // ajuste
    $AuditoriaGeneral->setUsuariosolicitaIp($ip);

    /* Configura atributos de una auditoría para un registro de activación de contingencias. */
    $AuditoriaGeneral->setUsuariosolicitaId(0);
    $AuditoriaGeneral->setUsuarioaprobarIp(0);
    $AuditoriaGeneral->setTipo("Activacion de contingencias masivas");
    $AuditoriaGeneral->setValorAntes("I");
    $AuditoriaGeneral->setValorDespues("A");
    $AuditoriaGeneral->setUsucreaId($_SESSION["usuario"]);

    /* Se inicializan propiedades de AuditoriaGeneral y se crea una instancia de DAO. */
    $AuditoriaGeneral->setUsumodifId(0);
    $AuditoriaGeneral->setEstado("A");
    $AuditoriaGeneral->setDispositivo(0);
    $AuditoriaGeneral->setObservacion("");

    $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO();

    /* Insertar auditoría en base de datos y obtener la transacción actual. */
    $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);
    $AuditoriaGeneralMySqlDAO->getTransaction()->commit();

} else {
    /* maneja errores en una respuesta, indicando éxito sin mensajes específicos. */


    $response["HasError"] = true;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

    $response["Data"] = [];

}
