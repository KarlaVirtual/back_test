<?php

/**
 * Este archivo contiene la implementación de un servicio para gestionar transacciones de usuarios
 * en el sistema Netabet. Incluye la validación de tokens, la consulta de movimientos financieros
 * y la generación de respuestas en formato JSON.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $UsuarioMandante          Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $userNow                  Variable que almacena la información del usuario actualmente autenticado.
 * @var mixed $Mandante                 Esta variable se utiliza para almacenar y manipular el mandante asociado.
 * @var mixed $params                   Variable que contiene los parámetros de una solicitud, generalmente en forma de array.
 * @var mixed $headers                  Variable que almacena los encabezados HTTP de la solicitud.
 * @var mixed $ConfigurationEnvironment Esta variable se utiliza para almacenar y manipular la configuración del entorno.
 * @var mixed $usuario                  Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $header                   Variable que almacena un encabezado HTTP individual.
 * @var mixed $payload                  Variable que almacena los datos del cuerpo de una solicitud, usualmente en JSON.
 * @var mixed $key                      Esta variable se utiliza para almacenar y manipular claves genéricas.
 * @var mixed $signature                Esta variable se utiliza para almacenar y manipular la firma digital.
 * @var mixed $token                    Esta variable contiene el token de autenticación, utilizado para verificar y autorizar peticiones de forma segura.
 * @var mixed $TokenHeader              Esta variable contiene el token de autenticación, utilizado para verificar y autorizar peticiones de forma segura.
 * @var mixed $dateFrom                 Variable que representa una fecha de inicio en un rango de fechas.
 * @var mixed $dateTo                   Variable que representa una fecha de finalización en un rango de fechas.
 * @var mixed $seguir                   Variable que indica si se debe continuar con una operación o proceso.
 * @var mixed $MaxRows                  Variable que define el número máximo de registros a retornar en una consulta.
 * @var mixed $OrderedItem              Variable que representa un elemento ordenado en una lista.
 * @var mixed $SkeepRows                Variable que indica el número de registros a omitir en una consulta.
 * @var mixed $_REQUEST                 Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $rules                    Esta variable contiene las reglas de validación o negocio, utilizadas para controlar el flujo de operaciones.
 * @var mixed $sql                      Variable que almacena una consulta SQL a ejecutar en la base de datos.
 * @var mixed $UsuarioHistorial         Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $UsuarioHistorialMySqlDAO Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $transaccion              Variable que almacena datos relacionados con una transacción.
 * @var mixed $count                    Esta variable indica la cantidad total de elementos o registros, útil para iteraciones o validaciones.
 * @var mixed $movimientos              Variable que almacena una lista de movimientos financieros.
 * @var mixed $movimientosData          Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $value                    Esta variable se utiliza para almacenar y manipular valores asociados a claves.
 * @var mixed $array                    Variable que almacena una lista o conjunto de datos.
 * @var mixed $response                 Esta variable almacena la respuesta generada por una operación o petición.
 */

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


//$UsuarioMandante = new UsuarioMandante($userNow);
$Mandante = new Mandante(6);

$params = file_get_contents('php://input');
$params = json_decode($params);

header('Content-Type: application/json');
$headers = getallheaders();
$ConfigurationEnvironment = new ConfigurationEnvironment();
if ($ConfigurationEnvironment->isDevelopment()) {
    $usuario = 'netabetVirtualSoft';
} else {
    $usuario = 'netabetVirtualSoft';
}
$header = json_encode([
    'alg' => 'HS256',
    'typ' => 'JWT'
]);

$payload = json_encode([
    'codigo' => 0,
    'mensaje' => 'OK',
    "usuario" => $usuario
]);

$key = 'netabetVirtualS';

$signature = hash('sha256', $header . $payload . $key);

$token = base64_encode($header) . '.' . base64_encode($payload) . '.' . $signature;
$token = "Bearer " . $token;

$TokenHeader = $headers["token"];

if ($TokenHeader == "") {
    $TokenHeader = $headers["Token"];
}

if ($TokenHeader === $token) {
    $dateFrom = $params->fromDate;
    $dateTo = $params->toDate;


    $seguir = true;


    if ($seguir) {
        $MaxRows = $params->MaxRows;
        $OrderedItem = $params->OrderedItem;
        $SkeepRows = $params->SkeepRows;

        $MaxRows = $_REQUEST["count"];
        $SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];

        if ($SkeepRows == "") {
            $SkeepRows = 0;
        }

        if ($OrderedItem == "") {
            $OrderedItem = 1;
        }

        if ($MaxRows == "") {
            $MaxRows = 1000;
        }


        $rules = [];


        $sql =
            "SELECT count(*) count
FROM usuario_historial as usuario_historial
         INNER JOIN usuario ON (usuario_historial.usuario_id = usuario.usuario_id)
where 1 = 1
  AND ((usuario.mandante)) = '6' AND usuario_historial.tipo IN (10,40,41,15)
  AND ((usuario_historial.fecha_crea)) >= '$dateFrom'
  AND ((usuario_historial.fecha_crea)) <= '$dateTo'";


        $UsuarioHistorial = new UsuarioHistorial();
        $UsuarioHistorialMySqlDAO = new \Backend\mysql\UsuarioHistorialMySqlDAO();
        $transaccion = $UsuarioHistorialMySqlDAO->getTransaction();

        $count = $UsuarioHistorial->execQuery($transaccion, $sql);


        $sql =
            "SELECT usuario_historial.*, usuario.nombre, usuario.moneda
FROM usuario_historial as usuario_historial
         INNER JOIN usuario ON (usuario_historial.usuario_id = usuario.usuario_id)
where 1 = 1
  AND ((usuario.mandante)) = '6' AND usuario_historial.tipo IN (10,40,41,15)
  AND ((usuario_historial.fecha_crea)) >= '$dateFrom'
  AND ((usuario_historial.fecha_crea)) <= '$dateTo'";


        $UsuarioHistorial = new UsuarioHistorial();
        $UsuarioHistorialMySqlDAO = new \Backend\mysql\UsuarioHistorialMySqlDAO();
        $transaccion = $UsuarioHistorialMySqlDAO->getTransaction();

        $movimientos = $UsuarioHistorial->execQuery($transaccion, $sql);

        $movimientosData = array();

        foreach ($movimientos as $key => $value) {
            $array = array();
            $array["MovementId"] = $value->{"usuario_historial.usuhistorial_id"};
            $array["UserId"] = $value->{"usuario_historial.usuario_id"};
            $array["Amount"] = ($value->{"usuario_historial.valor"});
            $array["ActualBalance"] = ($value->{"usuario_historial.creditos_base"});
            switch ($value->{"usuario_historial.tipo"}) {
                case "10":
                    if ($value->{"usuario_historial.movimiento"} == 'E') {
                        $array["MovementType"] = "Deposito";
                        $array["TransactionType"] = "Recarga";
                        $array["TransactionStatus"] = "Success";
                        $array["ActualBalance"] = ($value->{"usuario_historial.creditos_base"} + $value->{"usuario_historial.valor"});
                    } else {
                        $array["MovementType"] = "Cancelacion Deposito";
                        $array["TransactionType"] = "Recarga Cancelada";
                        $array["TransactionStatus"] = "Cancel";
                    }

                    break;

                case "15":
                    if ($value->{"usuario_historial.movimiento"} == 'E') {
                        $array["MovementType"] = "Ajuste de saldo";
                        $array["TransactionType"] = "Aumento de saldo";
                        $array["TransactionStatus"] = "Success";
                        $array["ActualBalance"] = ($value->{"usuario_historial.creditos_base"} + $value->{"usuario_historial.valor"});
                    } else {
                        $array["MovementType"] = "Ajuste de saldo";
                        $array["TransactionType"] = "Disminución de saldo";
                        $array["TransactionStatus"] = "Success";
                        $array["ActualBalance"] = ($value->{"usuario_historial.creditos_base"} - $value->{"usuario_historial.valor"});
                    }

                    break;

                case "20":
                    if ($value->{"usuario_historial.movimiento"} == 'S') {
                        $array["MovementType"] = "Apuesta Deportiva";
                        $array["TransactionType"] = "Apuesta";
                        $array["TransactionStatus"] = "Success";
                        $array["ActualBalance"] = ($value->{"usuario_historial.creditos_base"} - $value->{"usuario_historial.valor"});
                    } elseif ($value->{"usuario_historial.movimiento"} == 'E') {
                        $array["MovementType"] = "Ganancia Deportiva";
                        $array["TransactionType"] = "Ganancia";
                        $array["TransactionStatus"] = "Success";
                        $array["ActualBalance"] = ($value->{"usuario_historial.creditos_base"} + $value->{"usuario_historial.valor"});
                    } else {
                        $array["MovementType"] = "Cancelacion Deportiva";
                        $array["TransactionType"] = "Apuesta cancelada";
                        $array["TransactionStatus"] = "Cancel";
                        $array["ActualBalance"] = ($value->{"usuario_historial.creditos_base"} + $value->{"usuario_historial.valor"});
                    }

                    break;

                case "30":
                    if ($value->{"usuario_historial.movimiento"} == 'S') {
                        $array["MovementType"] = "Apuesta Casino";
                        $array["TransactionType"] = "Apuesta";
                        $array["TransactionStatus"] = "Success";
                        $array["ActualBalance"] = ($value->{"usuario_historial.creditos_base"} - $value->{"usuario_historial.valor"});
                    } elseif ($value->{"usuario_historial.movimiento"} == 'E') {
                        $array["MovementType"] = "Ganancia Casino";
                        $array["TransactionType"] = "Ganancia";
                        $array["TransactionStatus"] = "Success";
                        $array["ActualBalance"] = ($value->{"usuario_historial.creditos_base"} + $value->{"usuario_historial.valor"});
                    } else {
                        $array["MovementType"] = "Cancelación Casino";
                        $array["TransactionType"] = "Apuesta cancelada";
                        $array["TransactionStatus"] = "Cancel";
                        $array["ActualBalance"] = ($value->{"usuario_historial.creditos_base"} + $value->{"usuario_historial.valor"});
                    }


                    break;

                case "40":
                    if ($value->{"usuario_historial.movimiento"} == 'S') {
                        $array["MovementType"] = "Retiro Creado";
                        $array["TransactionType"] = "Retiro";
                        $array["TransactionStatus"] = "Success";
                        $array["ActualBalance"] = ($value->{"usuario_historial.creditos_base"} - $value->{"usuario_historial.valor"});
                    } else {
                        $array["MovementType"] = "Retiro Cancelado";
                        $array["TransactionType"] = "Retiro Cancelado";
                        $array["TransactionStatus"] = "Cancel";
                        $array["ActualBalance"] = ($value->{"usuario_historial.creditos_base"} + $value->{"usuario_historial.valor"});
                    }


                    break;
                case "41":

                    $array["MovementType"] = "Retiro Pagado";
                    $array["TransactionType"] = "Retiro Pagado";
                    $array["TransactionStatus"] = "Success";
                    $array["ActualBalance"] = ($value->{"usuario_historial.creditos_base"} - $value->{"usuario_historial.valor"});

                    break;
                case "50":
                    if ($value->{"usuario_historial.movimiento"} == 'E') {
                        $array["MovementType"] = "Bono";
                        $array["TransactionType"] = "Bono realizado";
                        $array["TransactionStatus"] = "Success";
                        $array["ActualBalance"] = ($value->{"usuario_historial.creditos_base"} + $value->{"usuario_historial.valor"});
                    }

                    break;
                case "60":
                    if ($value->{"usuario_historial.movimiento"} == 'E') {
                        $array["MovementType"] = "Movimiento de cupo";
                        $array["TransactionType"] = "Aumento de cupo";
                        $array["TransactionStatus"] = "Success";
                        $array["ActualBalance"] = ($value->{"usuario_historial.creditos_base"} + $value->{"usuario_historial.valor"});
                    } else {
                        $array["MovementType"] = "Movimiento de cupo";
                        $array["TransactionType"] = "Disminución de cupo";
                        $array["TransactionStatus"] = "Success";
                        $array["ActualBalance"] = ($value->{"usuario_historial.creditos_base"} - $value->{"usuario_historial.valor"});
                    }

                    break;
            }

            $array["ActualBalance"] = ($value->{"usuario_historial.creditos_base"} + $value->{"usuario_historial.creditos"});
            $array["Tax"] = 0;
            $array["Currency"] = $value->{"usuario.moneda"};

            $array["MovementDate"] = ($value->{"usuario_historial.fecha_crea"});

            $array["TransactionNumber"] = $value->{"usuario_historial.externo_id"};

            array_push($movimientosData, $array);
        }


        $response = array();
        $response["Error"] = false;
        $response["Mensaje"] = "success";
        $response["TotalCount"] = $count[0]->{".count"};
        $response["Data"] = $movimientosData;
    } else {
        $response["Error"] = false;
        $response["Mensaje"] = "No hay transacciones en este rango de fechas";
        $response["TotalCount"] = 0;
        $response["Data"] = [];
    }
} else {
    throw new Exception("Usuario no coincide con token", "30012");
}
