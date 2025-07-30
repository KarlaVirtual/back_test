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
 * DesactiveMassiveContingency
 *
 * Desactiva contingencias masivas para clientes.
 *
 * @param object $params Objeto que contiene los parámetros necesarios para la operación.
 * @param string $params ->CSV Lista de identificadores de clientes en formato CSV.
 * @param int $params ->Type Tipo de contingencia a desactivar.
 * @param string $params ->Description Descripción de la operación.
 * @param int $params ->Contingencies Tipo de contingencia a desactivar.
 *
 * @return array $response Respuesta con los siguientes valores:
 * - "HasError" (boolean): Indica si ocurrió un error.
 * - "AlertType" (string): Tipo de alerta (e.g., "success", "error").
 * - "AlertMessage" (string): Mensaje de alerta.
 * - "ModelErrors" (array): Lista de errores del modelo, si los hay.
 * - "Data" (array): Datos adicionales relacionados con la operación.
 *
 * @throws Exception Si ocurre un error durante la desactivación de contingencias.
 */

/* obtiene datos JSON de la entrada y los asigna a variables. */
$params = file_get_contents('php://input');
//$params = base64_decode($params);
$params = json_decode($params);

$ClientIdCsv = $params->CSV;
$type = $params->Type;

/* extrae la descripción, contingencias y dirección IP del cliente. */
$Description = $params->Description;
$Contingencies = $params->Contingencies;

$ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
$ip = explode(",", $ip)[0];

$ClientIdCsv = explode("base64,", $ClientIdCsv);

/* Decodifica un ID de cliente en base64 y reemplaza caracteres. */
$ClientIdCsv = $ClientIdCsv[1];

$ClientIdCsv = base64_decode($ClientIdCsv);


$ClientIdCsv = str_replace(";", ",", $ClientIdCsv);


/* Separa un CSV en líneas y las convierte en un arreglo de arrays. */
$lines = explode(PHP_EOL, $ClientIdCsv);
$lines = preg_split('/\r\n|\r|\n/', $ClientIdCsv);


$array = array();
foreach ($lines as $line) {
    $array[] = str_getcsv($line);

}

/* cuenta elementos de un array y extrae columnas específicas. */
$countArray = count($array[0]);

for ($i = 0; $i <= $countArray; $i++) {
    $arrayfinal = array();
    $arrayfinal = array_column($array, $i);
}

/* extrae la primera columna de un array y convierte sus claves a un array. */
$arrayfinal = array_column($array, '0');

$posiciones = array_keys($arrayfinal);

$ultima = strval(end($posiciones));
$arrayfinal = json_decode(json_encode($arrayfinal));
//unset($arrayfinal[$ultima]);


/* Convierte un array en cadena y luego lo separa nuevamente en un array. */
$ids = implode(",", $arrayfinal);
if ($ids != "") {

    $clients = $ids;
    $clients = explode(",", $clients);
}


// Si el usuario esta condicionado por País

/* Condiciona la asignación de variables basadas en sesiones de usuario y país. */
if ($_SESSION['PaisCond'] == "S") {
    $Pais = $_SESSION['pais_id'];
}
// Si el usuario esta condicionado por el mandante y no es de Global
if ($_SESSION['Global'] == "N") {
    $Mandante = $_SESSION['mandante'];
} else {
    /* Verifica si "mandanteLista" existe y no está vacío para asignarlo a $Mandante. */

    if ($_SESSION["mandanteLista"] != "" && ($_SESSION["mandanteLista"] != "-1")) {
        $Mandante = $_SESSION["mandanteLista"];
    }

}


/* establece una respuesta sin errores, indicando éxito y sin mensajes de alerta. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = '';
$response["ModelErrors"] = [];

//$response["Data"] = [];
if ((count($clients) > 0 || (count($clients) == 0))) {

    foreach ($clients as $key => $ClientId) {


        /* limpia y filtra el identificador del cliente, eliminando caracteres no deseados. */
        $ClientId = preg_replace('/[\xE2\x80\xAF]/', '', $ClientId);
        $ClientId = str_replace(" ", '', $ClientId);
        $ClientId = preg_replace("/[^0-9.]/", "", $ClientId);

        if ($ClientId != "" && $ClientId != "0") {


            /* Se crean instancias de UsuarioPerfil y Usuario, obteniendo el estado del usuario. */
            $UsuarioPerfil = new UsuarioPerfil($ClientId);

            $Usuario = new Usuario(strtolower($ClientId));
            $state = $Usuario->estado;

            try {

                if ($type == 1 and $UsuarioPerfil->perfilId == "PUNTOVENTA") {


                    /* Se crea una nueva instancia de la clase Usuario usando un identificador de cliente. */
                    $Usuario = new Usuario($ClientId);

                    if ($Pais == $Usuario->paisId || $Pais == "") {

                        /* Se inicializa un arreglo vacío llamado "contingenciaLogs". */
                        $contingenciaLogs = [];
                        switch ($Contingencies) {
                            case '0':
                                /* Actualiza el estado de contingencia del usuario y registra cambios en un log. */

                                if ($Usuario->contingencia != "I") {
                                    $contingenciaLogs[] = [
                                        'tipo' => 'CONTINGENCIAUSUARIO',
                                        'valorAntes' => $Usuario->contingencia,
                                        'valorDespues' => 'I'
                                    ];
                                    $Usuario->contingencia = 'I';
                                }
                                break;
                            case '1':
                                /* Modifica el estado de 'contingenciaDeportes' y registra el cambio si es necesario. */

                                if ($Usuario->contingenciaDeportes != "I") {
                                    $contingenciaLogs[] = [
                                        'tipo' => 'CONTINGENCIADEPORTEUSUARIO',
                                        'valorAntes' => $Usuario->contingenciaDeportes,
                                        'valorDespues' => 'I'
                                    ];
                                    $Usuario->contingenciaDeportes = 'I';
                                }
                                break;
                            case '2':
                                /* cambia el estado de 'contingenciaCasino' y registra el cambio. */

                                if ($Usuario->contingenciaCasino != "I") {
                                    $contingenciaLogs[] = [
                                        'tipo' => 'CONTINGENCIACASINOUSUARIO',
                                        'valorAntes' => $Usuario->contingenciaCasino,
                                        'valorDespues' => 'I'
                                    ];
                                    $Usuario->contingenciaCasino = 'I';
                                }
                                break;
                            case '3':
                                /* Actualiza el estado de contingencia del usuario y registra el cambio. */

                                if ($Usuario->contingenciaCasvivo != "I") {
                                    $contingenciaLogs[] = [
                                        'tipo' => 'CONTINGENCIACASVIVOUSUARIO',
                                        'valorAntes' => $Usuario->contingenciaCasvivo,
                                        'valorDespues' => 'I'
                                    ];
                                    $Usuario->contingenciaCasvivo = 'I';
                                }
                                break;
                            case '4':
                                /* Actualiza el estado de 'contingenciaVirtuales' a 'I' y registra el cambio. */

                                if ($Usuario->contingenciaVirtuales != "I") {
                                    $contingenciaLogs[] = [
                                        'tipo' => 'CONTINGENCIAVIRTUALESUSUARIO',
                                        'valorAntes' => $Usuario->contingenciaVirtuales,
                                        'valorDespues' => 'I'
                                    ];
                                    $Usuario->contingenciaVirtuales = 'I';
                                }
                                break;
                            case '5':
                                /* Actualiza el estado de contingencia en un objeto usuario si no está en "I". */

                                if ($Usuario->contingenciaPoker != "I") {
                                    $contingenciaLogs[] = [
                                        'tipo' => 'CONTINGENCIAPOKERUSUARIO',
                                        'valorAntes' => $Usuario->contingenciaPoker,
                                        'valorDespues' => 'I'
                                    ];
                                    $Usuario->contingenciaPoker = 'I';
                                }
                                break;
                            case '6':
                                /* Modifica el estado de contingencia de un usuario y registra el cambio. */

                                if ($Usuario->contingenciaRetiro != "I") {
                                    $contingenciaLogs[] = [
                                        'tipo' => 'CONTINGENCIARETIROUSUARIO',
                                        'valorAntes' => $Usuario->contingenciaRetiro,
                                        'valorDespues' => 'I'
                                    ];
                                    $Usuario->contingenciaRetiro = 'I';
                                }
                                break;
                            case '7':
                                /* actualiza el estado de contingencia de un usuario y registra cambios. */

                                if ($Usuario->contingenciaDeposito != "I") {
                                    $contingenciaLogs[] = [
                                        'tipo' => 'CONTINGENCIADEPOSITOUSUARIO',
                                        'valorAntes' => $Usuario->contingenciaDeposito,
                                        'valorDespues' => 'I'
                                    ];
                                    $Usuario->contingenciaDeposito = 'I';
                                }
                                break;

                            case '8':

                                /* Actualiza el estado de configuración de usuario de 'A' a 'I' y registra cambios. */
                                $UsuarioConfiguracion = new UsuarioConfiguracion();
                                $UsuarioConfiguracion = $UsuarioConfiguracion->getUserBondABuser($Usuario->usuarioId);
                                if (!empty($UsuarioConfiguracion->usuconfigId)) {
                                    if ($UsuarioConfiguracion->estado === 'A') {
                                        $contingenciaLogs[] = [
                                            'tipo' => 'BONDABUSER',
                                            'valorAntes' => $UsuarioConfiguracion->estado,
                                            'valorDespues' => 'I'
                                        ];

                                        $UsuarioConfiguracion->estado = 'I';
                                        $UsuarioConfiguracion->valor = 'I';
                                        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
                                        $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);
                                        $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();
                                    }
                                } else {
                                    /* Código que crea y almacena configuración de usuario en base de datos. */

                                    $Clasificador = new Clasificador('', 'BONDABUSER');
                                    $UsuarioConfiguracion = new UsuarioConfiguracion();
                                    $UsuarioConfiguracion->usuarioId = $Usuario->usuarioId;
                                    $UsuarioConfiguracion->valor = 'I';
                                    $UsuarioConfiguracion->usucreaId = $Usuario->usuarioId;
                                    $UsuarioConfiguracion->tipo = $Clasificador->getClasificadorId();
                                    $UsuarioConfiguracion->usumodifId = 0;
                                    $UsuarioConfiguracion->productoId = 0;
                                    $UsuarioConfiguracion->estado = 'I';
                                    $UsuarioConfiguracion->nota = '';
                                    $UsuarioConfiguracion->fechaCrea = date('Y-m-d H:i:s');
                                    $UsuarioConfiguracion->fechaModif = date('Y-m-d H:i:s');

                                    $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
                                    $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);
                                    $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();
                                }
                                break;
                        }


                        /* asigna una descripción a un usuario si no está vacía. */
                        $msg = "entro5";

                        if ($Description != '') {
                            $Usuario->observ = $Usuario->observ . ' ' . $Description;
                        }

                        $UsuarioMySqlDAO = new UsuarioMySqlDAO();

                        /* Actualiza un usuario en la base de datos y gestiona una transacción. */
                        $UsuarioMySqlDAO->update($Usuario);
                        $UsuarioMySqlDAO->getTransaction()->commit();

                        // Después de crear los logs, insertarlos en la base de datos

                        /* Se registra un log de usuario para cada entrada en contingenciaLogs. */
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


                    /* Crea una instancia de AuditoriaGeneral y establece los datos del usuario. */
                    $AuditoriaGeneral = new AuditoriaGeneral();

                    $AuditoriaGeneral->setUsuarioId($_SESSION["usuario"]);
                    $AuditoriaGeneral->setUsuarioIp($ip);
                    $AuditoriaGeneral->setUsuariosolicitaId($_SESSION["usuario"]); // ajuste
                    $AuditoriaGeneral->setUsuariosolicitaIp($ip);

                    /* Configuración de auditoría para desactivación de contingencias con cambios de estado. */
                    $AuditoriaGeneral->setUsuariosolicitaId(0);
                    $AuditoriaGeneral->setUsuarioaprobarIp(0);
                    $AuditoriaGeneral->setTipo("Desactivacion de contingencias");
                    $AuditoriaGeneral->setValorAntes("A");
                    $AuditoriaGeneral->setValorDespues("I");
                    $AuditoriaGeneral->setUsucreaId($_SESSION["usuario"]);

                    /* Configura propiedades iniciales para una entidad de auditoría en un sistema. */
                    $AuditoriaGeneral->setUsumodifId(0);
                    $AuditoriaGeneral->setEstado("A");
                    $AuditoriaGeneral->setDispositivo(0);
                    $AuditoriaGeneral->setObservacion("");

                    $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO();

                    /* Se inserta un registro en Auditoría y se inicia una transacción en MySQL. */
                    $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);
                    $AuditoriaGeneralMySqlDAO->getTransaction()->commit();

                } elseif ($type == 0) {


                    /* Se crea una nueva instancia de Usuario con el ClientId en minúsculas. */
                    $Usuario = new Usuario(strtolower($ClientId));
                    if ($UsuarioPerfil->perfilId == "USUONLINE" and $Pais == $Usuario->paisId || $Pais == "") {

                        /* Inicializa un arreglo vacío llamado `$contingenciaLogs` para almacenar datos. */
                        $contingenciaLogs = [];
                        switch ($Contingencies) {
                            case '0':
                                /* Actualiza el estado de 'contingencia' de un usuario y registra el cambio. */

                                if ($Usuario->contingencia != "I") {
                                    $contingenciaLogs[] = [
                                        'tipo' => 'CONTINGENCIAUSUARIO',
                                        'valorAntes' => $Usuario->contingencia,
                                        'valorDespues' => 'I'
                                    ];
                                    $Usuario->contingencia = 'I';
                                }
                                break;
                            case '1':
                                /* Actualiza el estado de contingencia deportiva del usuario si no está en 'I'. */

                                if ($Usuario->contingenciaDeportes != "I") {
                                    $contingenciaLogs[] = [
                                        'tipo' => 'CONTINGENCIADEPORTEUSUARIO',
                                        'valorAntes' => $Usuario->contingenciaDeportes,
                                        'valorDespues' => 'I'
                                    ];
                                    $Usuario->contingenciaDeportes = 'I';
                                }
                                break;
                            case '2':
                                /* Actualiza el estado de contingencia del usuario a 'I' si no está ya establecido. */

                                if ($Usuario->contingenciaCasino != "I") {
                                    $contingenciaLogs[] = [
                                        'tipo' => 'CONTINGENCIACASINOUSUARIO',
                                        'valorAntes' => $Usuario->contingenciaCasino,
                                        'valorDespues' => 'I'
                                    ];
                                    $Usuario->contingenciaCasino = 'I';
                                }
                                break;
                            case '3':
                                /* Actualiza el estado de contingencia de un usuario si no es "I". */

                                if ($Usuario->contingenciaCasvivo != "I") {
                                    $contingenciaLogs[] = [
                                        'tipo' => 'CONTINGENCIACASVIOUSUARIO',
                                        'valorAntes' => $Usuario->contingenciaCasvivo,
                                        'valorDespues' => 'I'
                                    ];
                                    $Usuario->contingenciaCasvivo = 'I';
                                }
                                break;
                            case '4':
                                /* Actualiza el estado de 'contingenciaVirtuales' a 'I' y registra el cambio. */

                                if ($Usuario->contingenciaVirtuales != "I") {
                                    $contingenciaLogs[] = [
                                        'tipo' => 'CONTINGENCIAVIRTUALESUSUARIO',
                                        'valorAntes' => $Usuario->contingenciaVirtuales,
                                        'valorDespues' => 'I'
                                    ];
                                    $Usuario->contingenciaVirtuales = 'I';
                                }
                                break;
                            case '5':
                                /* Actualiza el estado 'contingenciaPoker' del usuario y registra el cambio. */

                                if ($Usuario->contingenciaPoker != "I") {
                                    $contingenciaLogs[] = [
                                        'tipo' => 'CONTINGENCIAPOKERUSUARIO',
                                        'valorAntes' => $Usuario->contingenciaPoker,
                                        'valorDespues' => 'I'
                                    ];
                                    $Usuario->contingenciaPoker = 'I';
                                }
                                break;
                            case '6':
                                /* Actualiza el estado de contingencia de retiro del usuario a "I" si es necesario. */

                                if ($Usuario->contingenciaRetiro != "I") {
                                    $contingenciaLogs[] = [
                                        'tipo' => 'CONTINGENCIARETIROUSUARIO',
                                        'valorAntes' => $Usuario->contingenciaRetiro,
                                        'valorDespues' => 'I'
                                    ];
                                    $Usuario->contingenciaRetiro = 'I';
                                }
                                break;
                            case '7':
                                /* Actualiza el estado de contingenciaDeposito y registra el cambio en logs. */

                                if ($Usuario->contingenciaDeposito != "I") {
                                    $contingenciaLogs[] = [
                                        'tipo' => 'CONTINGENCIADEPOSUARIO',
                                        'valorAntes' => $Usuario->contingenciaDeposito,
                                        'valorDespues' => 'I'
                                    ];
                                    $Usuario->contingenciaDeposito = 'I';
                                }
                                break;
                            case '8':

                                /* Actualiza el estado de configuración de usuario si está activo, registrando cambios. */
                                $UsuarioConfiguracion = new UsuarioConfiguracion();
                                $UsuarioConfiguracion = $UsuarioConfiguracion->getUserBondABuser($Usuario->usuarioId);
                                if (!empty($UsuarioConfiguracion->usuconfigId)) {
                                    if ($UsuarioConfiguracion->estado === 'A') {
                                        $contingenciaLogs[] = [
                                            'tipo' => 'BONDABUSER',
                                            'valorAntes' => $UsuarioConfiguracion->estado,
                                            'valorDespues' => 'I'
                                        ];

                                        $UsuarioConfiguracion->estado = 'I';
                                        $UsuarioConfiguracion->valor = 'I';
                                        $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
                                        $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);
                                        $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();
                                    }
                                } else {
                                    /* Insertar configuración de usuario en la base de datos con estado 'Inactivo'. */

                                    $Clasificador = new Clasificador('', 'BONDABUSER');
                                    $UsuarioConfiguracion = new UsuarioConfiguracion();
                                    $UsuarioConfiguracion->usuarioId = $Usuario->usuarioId;
                                    $UsuarioConfiguracion->valor = 'I';
                                    $UsuarioConfiguracion->usucreaId = $Usuario->usuarioId;
                                    $UsuarioConfiguracion->tipo = $Clasificador->getClasificadorId();
                                    $UsuarioConfiguracion->usumodifId = 0;
                                    $UsuarioConfiguracion->productoId = 0;
                                    $UsuarioConfiguracion->estado = 'I';
                                    $UsuarioConfiguracion->nota = '';
                                    $UsuarioConfiguracion->fechaCrea = date('Y-m-d H:i:s');
                                    $UsuarioConfiguracion->fechaModif = date('Y-m-d H:i:s');

                                    $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
                                    $UsuarioConfiguracionMySqlDAO->insert($UsuarioConfiguracion);
                                    $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();
                                }
                                break;
                        }


                        /* agrega una descripción al objeto Usuario si no está vacío. */
                        $msg = "entro5";

                        if ($Description != '') {
                            $Usuario->observ = $Usuario->observ . ' ' . $Description;
                        }

                        $UsuarioMySqlDAO = new UsuarioMySqlDAO();

                        /* Actualiza un usuario en MySQL y obtiene la transacción correspondiente. */
                        $UsuarioMySqlDAO->update($Usuario);
                        $UsuarioMySqlDAO->getTransaction()->commit();

                        // Después de crear los logs, insertarlos en la base de datos

                        /* Registra logs de usuario en la base de datos dentro de un bucle. */
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
                    } else {
                        /* Se lanza una excepción si los usuarios no corresponden al socio asignado. */

                        throw new Exception("Usuarios no pertenecen al partner", '60003');
                    }


                    /* Se crea una instancia de AuditoriaGeneral y se establecen atributos de usuario e IP. */
                    $AuditoriaGeneral = new AuditoriaGeneral();

                    $AuditoriaGeneral->setUsuarioId($_SESSION["usuario"]);
                    $AuditoriaGeneral->setUsuarioIp($ip);
                    $AuditoriaGeneral->setUsuariosolicitaId($_SESSION["usuario"]); // ajuste
                    $AuditoriaGeneral->setUsuariosolicitaIp($ip);

                    /* Se configuran propiedades para una auditoría de desactivación de contingencia. */
                    $AuditoriaGeneral->setUsuariosolicitaId(0);
                    $AuditoriaGeneral->setUsuarioaprobarIp(0);
                    $AuditoriaGeneral->setTipo("Desactivacion de contingencia");
                    $AuditoriaGeneral->setValorAntes("A");
                    $AuditoriaGeneral->setValorDespues("I");
                    $AuditoriaGeneral->setUsucreaId($_SESSION["usuario"]);

                    /* Configura parámetros de auditoría en un objeto y inicializa un DAO correspondiente. */
                    $AuditoriaGeneral->setUsumodifId(0);
                    $AuditoriaGeneral->setEstado("A");
                    $AuditoriaGeneral->setDispositivo(0);
                    $AuditoriaGeneral->setObservacion("");

                    $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO();

                    /* realiza una inserción de datos y posteriormente obtiene la transacción activa. */
                    $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);
                    $AuditoriaGeneralMySqlDAO->getTransaction()->commit();

                }

            } catch (Exception $e) {
                /* Captura excepciones y almacena el mensaje de error en la variable $msg. */

                $msg = $e->getMessage();

            }

        } else {
            /* Se configura una respuesta exitosa sin errores ni datos adicionales. */

            $response["HasError"] = false;
            $response["AlertType"] = "success";
            $response["AlertMessage"] = $msg;
            $response["ModelErrors"] = [];

            $response["Data"] = [];
        }
    }
} else {
    /* maneja una respuesta con error, estableciendo alertas y errores del modelo. */


    $response["HasError"] = true;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

//    $response["Data"] = [];

}