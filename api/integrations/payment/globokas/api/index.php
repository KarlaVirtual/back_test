<?php

/**
 * Este archivo contiene un script para procesar y generar un informe de cuotas totales
 * basado en datos de usuarios, transacciones y actividades relacionadas.
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
 * @var mixed $_SERVER                       Variable superglobal que contiene información del servidor y entorno de ejecución.
 * @var mixed $timezone                      Variable que almacena la zona horaria.
 * @var mixed $_SESSION                      Variable que almacena datos de sesión del usuario.
 * @var mixed $URI                           Esta variable contiene el URI de la petición actual.
 * @var mixed $URL_ITAINMENT                 Variable que almacena la URL del servicio ITAINMENT.
 * @var mixed $currencies_valor              Variable que almacena el valor de diferentes monedas.
 * @var mixed $params                        Variable que contiene los parámetros de una solicitud, generalmente en forma de array.
 * @var mixed $response                      Esta variable almacena la respuesta generada por una operación o petición.
 * @var mixed $ENCRYPTION_KEY                Variable que almacena la clave de cifrado.
 * @var mixed $arraySuper                    Variable que almacena una lista extendida de datos.
 * @var mixed $log                           Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $headers                       Variable que almacena los encabezados HTTP de la solicitud.
 * @var mixed $ConfigurationEnvironment      Esta variable se utiliza para almacenar y manipular la configuración del entorno.
 * @var mixed $usuario                       Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $clave                         Esta variable guarda la clave o contraseña para autenticación y acceso seguro al sistema (generalmente encriptada).
 * @var mixed $HoraActual2                   Variable que almacena la segunda referencia de la hora actual.
 * @var mixed $HoFinal                       Variable que almacena la hora final de un proceso.
 * @var mixed $horaTotal                     Variable que almacena la duración total en horas.
 * @var mixed $Hora                          Variable que almacena una hora específica.
 * @var mixed $token                         Esta variable contiene el token de autenticación, utilizado para verificar y autorizar peticiones de forma segura.
 * @var mixed $respuesta                     Esta variable se utiliza para almacenar y manipular la respuesta de una operación.
 * @var mixed $Signature                     Variable que almacena la firma digital de un proceso.
 * @var mixed $nroSumin                      Variable que almacena el número de suministro.
 * @var mixed $_GET                          Arreglo global que contiene los datos enviados mediante el método GET.
 * @var mixed $Usuario                       Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $rules                         Esta variable contiene las reglas de validación o negocio, utilizadas para controlar el flujo de operaciones.
 * @var mixed $filtro                        Esta variable contiene criterios de filtrado para la búsqueda o procesamiento de datos.
 * @var mixed $jsonfiltro                    Variable que almacena un filtro en formato JSON.
 * @var mixed $Registro                      Variable que almacena información sobre un registro.
 * @var mixed $datas                         Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $UserId                        Esta variable se utiliza para almacenar y manipular el identificador del usuario.
 * @var mixed $traceConsulta                 Variable que almacena el rastro de una consulta.
 * @var mixed $fechaConsulta                 Esta variable almacena una fecha, que puede indicar la creación, modificación o vencimiento de un registro.
 * @var mixed $horaConsulta                  Variable que almacena la hora en que se realiza una consulta.
 * @var mixed $oDate                         Variable que almacena una fecha específica.
 * @var mixed $fecha                         Esta variable almacena una fecha, que puede indicar la creación, modificación o vencimiento de un registro.
 * @var mixed $hora                          Variable que almacena una hora específica.
 * @var mixed $codEmpresa                    Variable que almacena el código de una empresa.
 * @var mixed $codServicio                   Variable que almacena el código de un servicio.
 * @var mixed $codAgencia                    Variable que almacena el código de una agencia.
 * @var mixed $codCanal                      Variable que almacena el código de un canal.
 * @var mixed $terminal                      Variable que almacena la identificación de un terminal.
 * @var mixed $transactionId                 Variable que almacena el identificador único de una transacción.
 * @var mixed $nota                          Variable que almacena una nota o comentario.
 * @var mixed $pais                          Variable que almacena el país asociado a un proceso.
 * @var mixed $amount                        Variable que almacena un monto o cantidad.
 * @var mixed $UsuarioPuntoVenta             Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $ClientId                      Variable que almacena el identificador de un cliente.
 * @var mixed $FromDateLocal                 Variable que almacena la fecha local de inicio de un proceso o intervalo.
 * @var mixed $ToDateLocal                   Variable que almacena la fecha local de finalización de un proceso o intervalo.
 * @var mixed $OrderedItem                   Variable que representa un elemento ordenado en una lista.
 * @var mixed $MaxRows                       Variable que define el número máximo de registros a retornar en una consulta.
 * @var mixed $json                          Esta variable contiene datos en formato JSON, que pueden ser decodificados para su procesamiento.
 * @var mixed $SkeepRows                     Variable que indica el número de registros a omitir en una consulta.
 * @var mixed $State                         Variable que almacena el estado general de un proceso o entidad.
 * @var mixed $UsuarioRecarga                Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $UsuarioMandante               Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $usuarios                      Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $recargas                      Variable que almacena información sobre recargas.
 * @var mixed $json2                         Variable que almacena un segundo conjunto de datos en formato JSON.
 * @var mixed $Time                          Variable que almacena una referencia temporal.
 * @var mixed $cuentasData                   Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $key                           Esta variable se utiliza para almacenar y manipular claves genéricas.
 * @var mixed $value                         Esta variable se utiliza para almacenar y manipular valores asociados a claves.
 * @var mixed $arraybet                      Variable que almacena un arreglo de apuestas.
 * @var mixed $fecha_actual                  Esta variable almacena una fecha, que puede indicar la creación, modificación o vencimiento de un registro.
 * @var mixed $tiempo_inicial                Variable que almacena el tiempo inicial de un proceso.
 * @var mixed $expires_in                    Variable que almacena el tiempo de expiración en segundos.
 * @var mixed $numFactura                    Variable que almacena el número de una factura.
 * @var mixed $tracePago                     Variable que almacena el rastro de un pago.
 * @var mixed $fechaPago                     Esta variable almacena una fecha, que puede indicar la creación, modificación o vencimiento de un registro.
 * @var mixed $horaPago                      Variable que almacena la hora en que se realizó un pago.
 * @var mixed $montoDeudaConvertido          Variable que almacena el monto de una deuda convertido a otra moneda.
 * @var mixed $comisionConvertido            Variable que almacena el valor de una comisión convertida a otra moneda.
 * @var mixed $UsuarioRecargaMySqlDAO        Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $Transaction                   Esta variable contiene información de una transacción, utilizada para el seguimiento y procesamiento de operaciones.
 * @var mixed $consecutivo_recarga           Variable que almacena el consecutivo de una recarga.
 * @var mixed $UsuarioMySqlDAO2              Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $TransaccionApiUsuario         Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $TransaccionApiUsuarioMySqlDAO Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $TransapiusuarioLog            Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $TransapiusuarioLogMySqlDAO    Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $Transapiusuariolog_id         Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $UsuarioHistorial              Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $UsuarioHistorialMySqlDAO      Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $PuntoVenta                    Variable que almacena información sobre un punto de venta.
 * @var mixed $rowsUpdate                    Variable que almacena el número de filas actualizadas en una consulta.
 * @var mixed $PuntoVentaMySqlDAO            Variable que representa la capa de acceso a datos MySQL para puntos de venta.
 * @var mixed $FlujoCaja                     Variable que almacena información sobre el flujo de caja.
 * @var mixed $FlujoCajaMySqlDAO             Variable que representa la capa de acceso a datos MySQL para el flujo de caja.
 * @var mixed $montoTotalConvertidoRes       Variable que almacena el monto total convertido y su resultado.
 * @var mixed $numOperacionEmpresa           Variable que almacena el número de operación dentro de una empresa.
 * @var mixed $e                             Esta variable se utiliza para capturar excepciones o errores en bloques try-catch.
 * @var mixed $nombreConcepto                Variable que almacena el nombre de un concepto financiero.
 * @var mixed $montoConceptoConvertido       Variable que almacena el monto de un concepto convertido a otra moneda.
 * @var mixed $montoTotalConceptoConv        Variable que almacena el monto total convertido de un concepto.
 * @var mixed $montoTotalConvertido          Variable que almacena el monto total convertido a otra moneda.
 * @var mixed $IdRecarga                     Variable que almacena el identificador de una recarga.
 * @var mixed $valor                         Variable que almacena un valor monetario o numérico.
 * @var mixed $puntoventa_id                 Variable que almacena el identificador de un punto de venta.
 * @var mixed $SaldoUsuonlineAjuste          Variable que almacena el ajuste del saldo de un usuario en línea.
 * @var mixed $dir_ip                        Variable que almacena la dirección IP de un usuario o servidor.
 * @var mixed $SaldoUsuonlineAjusteMysql     Variable que representa la capa de acceso a datos MySQL para ajustes de saldo en línea.
 * @var mixed $_REQUEST                      Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $code                          Variable que almacena un código de referencia, error o identificación.
 * @var mixed $message                       Variable que almacena un mensaje informativo o de error dentro del sistema.
 * @var mixed $codeProveedor                 Variable que almacena un código asociado a un proveedor.
 * @var mixed $messageProveedor              Variable que almacena un mensaje proveniente de un proveedor.
 * @var mixed $from_Currency                 Variable que almacena la moneda de origen en una conversión.
 * @var mixed $to_Currency                   Variable que almacena la moneda de destino en una conversión.
 * @var mixed $convertido                    Variable que indica si un valor ha sido convertido.
 * @var mixed $bool                          Variable que almacena un valor booleano.
 * @var mixed $encode_amount                 Variable que almacena un monto codificado.
 * @var mixed $rawdata                       Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $sport                         Variable que almacena información sobre un deporte.
 * @var mixed $region                        Variable que almacena información sobre una región geográfica.
 * @var mixed $competition                   Variable que almacena información sobre una competencia o torneo.
 * @var mixed $fecha_inicial                 Esta variable almacena una fecha, que puede indicar la creación, modificación o vencimiento de un registro.
 * @var mixed $fecha_final                   Esta variable almacena una fecha, que puede indicar la creación, modificación o vencimiento de un registro.
 * @var mixed $data                          Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $datos                         Variable que almacena datos genéricos.
 * @var mixed $array                         Variable que almacena una lista o conjunto de datos.
 * @var mixed $item                          Variable que almacena un elemento genérico en una lista o estructura de datos.
 * @var mixed $item2                         Variable que almacena un segundo elemento en una lista o estructura de datos.
 * @var mixed $item3                         Variable que almacena un tercer elemento en una lista.
 * @var mixed $item4                         Variable que almacena un cuarto elemento en una lista.
 * @var mixed $item_data                     Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $header                        Variable que almacena un encabezado HTTP individual.
 * @var mixed $payload                       Variable que almacena los datos del cuerpo de una solicitud, usualmente en JSON.
 * @var mixed $signature                     Esta variable se utiliza para almacenar y manipular la firma digital.
 * @var mixed $length                        Variable que almacena la longitud de un valor o estructura.
 * @var mixed $characters                    Variable que almacena una cadena de caracteres.
 * @var mixed $randomString                  Variable que almacena una cadena de caracteres aleatoria.
 * @var mixed $i                             Variable que almacena un índice en una iteración.
 * @var mixed $action                        Variable que almacena la acción que se debe ejecutar.
 * @var mixed $string                        Esta variable contiene una cadena de texto, utilizada para representar información textual.
 * @var mixed $output                        Variable que almacena la salida o resultado de un proceso.
 * @var mixed $encrypt_method                Variable que almacena el método de encriptación utilizado.
 * @var mixed $secret_key                    Variable que almacena la clave secreta para encriptación.
 * @var mixed $secret_iv                     Variable que almacena el vector de inicialización para encriptación.
 * @var mixed $iv                            Variable que almacena el vector de inicialización (IV) para cifrado.
 * @var mixed $ipaddress                     Variable que almacena la dirección IP de un usuario o servidor.
 * @var mixed $temp_array                    Variable que almacena un arreglo temporal de valores.
 * @var mixed $key_array                     Variable que almacena un arreglo de claves.
 * @var mixed $val                           Variable que almacena un valor genérico.
 * @var mixed $cadena                        Variable que almacena una cadena de texto.
 * @var mixed $no_permitidas                 Variable que contiene una lista de valores no permitidos.
 * @var mixed $permitidas                    Variable que contiene una lista de valores permitidos.
 * @var mixed $texto                         Variable que almacena un texto genérico.
 * @var mixed $encryption_key                Variable que almacena la clave de cifrado.
 * @var mixed $encrypted_string              Variable que almacena una cadena de texto encriptada.
 * @var mixed $iv_strlen                     Variable que almacena la longitud del vector de inicialización.
 * @var mixed $regs                          Variable que almacena registros en un proceso.
 * @var mixed $crypted_string                Variable que almacena una cadena cifrada.
 * @var mixed $decrypted_string              Variable que almacena una cadena de texto desencriptada.
 * @var mixed $texto_depurar                 Variable que almacena un texto para depuración.
 * @var mixed $texto_retornar                Variable que almacena un texto a retornar en un proceso.
 * @var mixed $c                             Variable de uso general que puede almacenar diferentes valores.
 * @var mixed $ipv6                          Variable que almacena una dirección IPv6.
 * @var mixed $ipv6Addr                      Variable que almacena una dirección específica en formato IPv6.
 * @var mixed $ipv4Addr                      Variable que almacena una dirección específica en formato IPv4.
 * @var mixed $ipv4                          Variable que almacena una dirección IPv4.
 */

use Backend\dto\ApiTransaction;
use Backend\dto\Bono;
use Backend\dto\BonoDetalle;
use Backend\dto\BonoInterno;
use Backend\dto\BonoLog;
use Backend\dto\Categoria;
use Backend\dto\CategoriaProducto;
use Backend\dto\Clasificador;
use Backend\dto\CompetenciaPuntos;
use Backend\dto\Concesionario;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Consecutivo;
use Backend\dto\CuentaCobro;
use Backend\dto\CupoLog;
use Backend\dto\Descarga;
use Backend\dto\DocumentoUsuario;
use Backend\dto\FlujoCaja;
use Backend\dto\Flujocajafact;
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
use Backend\dto\Perfil;
use Backend\dto\PerfilSubmenu;
use Backend\dto\ProdMandanteTipo;
use Backend\dto\Producto;
use Backend\dto\ProductoComision;
use Backend\dto\ProductoInterno;
use Backend\dto\PromocionalLog;
use Backend\dto\ProveedorMandante;
use Backend\dto\PuntoVenta;
use Backend\dto\ProductoMandante;
use Backend\dto\Registro;
use Backend\dto\SaldoUsuonlineAjuste;
use Backend\dto\Submenu;
use Backend\dto\Template;
use Backend\dto\TransaccionApi;
use Backend\dto\TransaccionApiUsuario;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransapiusuarioLog;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransprodLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioAlerta;
use Backend\dto\UsuarioBanco;
use Backend\dto\UsuarioBloqueado;
use Backend\dto\UsuarioBono;
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
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioTokenInterno;
use Backend\dto\UsucomisionResumen;
use Backend\dto\UsumarketingResumen;
use Backend\imports\Google\GoogleAuthenticator;
use Backend\integrations\mensajeria\Okroute;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\mysql\CategoriaMySqlDAO;
use Backend\mysql\CompetenciaPuntosMySqlDAO;
use Backend\mysql\ConcesionarioMySqlDAO;
use Backend\mysql\ConsecutivoMySqlDAO;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\CupoLogMySqlDAO;
use Backend\mysql\DocumentoUsuarioMySqlDAO;
use Backend\mysql\FlujoCajaMySqlDAO;
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
use Backend\mysql\ProveedorMandanteMySqlDAO;
use Backend\dto\Proveedor;
use Backend\mysql\ProveedorMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\mysql\RegistroMySqlDAO;
use Backend\mysql\SaldoUsuonlineAjusteMySqlDAO;
use Backend\mysql\TransaccionApiUsuarioMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransapiusuarioLogMySqlDAO;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\mysql\UsuarioAlertaMySqlDAO;
use Backend\mysql\UsuarioBancoMySqlDAO;
use Backend\mysql\UsuarioBloqueadoMySqlDAO;
use Backend\mysql\UsuarioBonoMySqlDAO;
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
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\mysql\UsucomisionResumenMySqlDAO;
use Backend\websocket\WebsocketUsuario;
use Firebase\JWT\JWT;

include "includes.php";
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Access-Control-Allow-Headers, Access-Control-Allow-Credentials,Authentication, Origin, X-Requested-With, Content-Type,x-token');
header('Access-Control-Expose-Headers: Authentication');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS,PUT');
header('Content-Type: application/json, application/x-www-form-urlencoded');
ini_set('memory_limit', '-1');
header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);

$timezone = $_SESSION["timezone"];
$timezone = -5 - ($timezone);
$timezone = 0;

$URI = $_SERVER["REQUEST_URI"];
$URL_ITAINMENT = 'https://dataexport-altenar.biahosted.com';

$currencies_valor = array();


$params = file_get_contents('php://input');
$params = json_decode($params);
$response = array();
$response["error"] = false;
$response["code"] = 0;

$ENCRYPTION_KEY = "D!@#$%^&*";

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit();
}

$arraySuper = explode("/", current(explode("?", $URI)));


$log = "\r\n" . "-------------------------" . "\r\n";
$log = $log . date('Y-m-d H:i:s');
$log = $log . $URI;
$log = $log . file_get_contents('php://input');
fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

try {
    $headers = getallheaders();
    switch ($arraySuper[oldCount($arraySuper) - 2] . "/" . $arraySuper[oldCount($arraySuper) - 1]) {
        case 'api/auth':


            $ConfigurationEnvironment = new ConfigurationEnvironment();

            if ($ConfigurationEnvironment->isDevelopment()) {
                $usuario = 'KasnetVirtualSoft';
                $clave = 'cUz&RqYENra';
            } else {
                $usuario = 'KasnetVirtualSoft';
                $clave = '%3RZvwBqoTKf';
            }


            if ($params->usuario == $usuario && $params->clave = $clave) {
                date_default_timezone_set("America/Bogota");


                $HoraActual2 = date("H:i");
                $HoFinal = "23:59:59";

                $horaTotal = $HoFinal - $HoraActual2;

                $Hora = $horaTotal * 60;


                $token = token($Hora);
                $respuesta = array(
                    'codigo' => '00',
                    'mensaje' => 'Exitoso',
                    'expires_in' => strval($Hora),
                    'token' => $token,
                    'refresh_token' => $token,
                );

                $respuesta = json_decode(json_encode($respuesta));
            } else {
                throw new Exception("Login con contraseña incorrectos", "30003");
            }


            break;

        case 'api/debtconsult':

            $Signature = $headers['Authorization'];
            date_default_timezone_set("America/Bogota");
            $HoraActual2 = date("H:i");
            $HoFinal = "23:59:59";

            $horaTotal = $HoFinal - $HoraActual2;

            $Hora = $horaTotal * 60;


            $token = token($Hora);

            $nroSumin = $_GET['nroSumin'];

            $rules = [];


            array_push($rules, array("field" => "registro.usuario_id", "data" => "$nroSumin", "op" => "eq"));
            array_push($rules, array("field" => "usuario.pais_id", "data" => "173", "op" => "eq"));
            array_push($rules, array("field" => "usuario.mandante", "data" => "0", "op" => "eq"));
            array_push($rules, array("field" => "usuario.estado", "data" => "A", "op" => "eq"));


            $filtro = array("rules" => $rules, "groupOp" => "AND");

            $jsonfiltro = json_encode($filtro);
            $Registro = new Registro();
            $datas = $Registro->getRegistroCustom("registro.usuario_id", "registro.usuario_id", "asc", 0, 1, $jsonfiltro, true);
            $datas = json_decode($datas);


            if ($datas->data[0] == "") {
                $rules = [];
                array_push($rules, array("field" => "registro.cedula", "data" => "$nroSumin", "op" => "eq"));
                array_push($rules, array("field" => "usuario.pais_id", "data" => "173", "op" => "eq"));
                array_push($rules, array("field" => "usuario.mandante", "data" => "0", "op" => "eq"));
                array_push($rules, array("field" => "usuario.estado", "data" => "A", "op" => "eq"));
                $filtro = array("rules" => $rules, "groupOp" => "AND");

                $jsonfiltro = json_encode($filtro);
                $Registro = new Registro();
                $datas = $Registro->getRegistroCustom("registro.usuario_id", "registro.usuario_id", "asc", 0, 1, $jsonfiltro, true);
                $datas = json_decode($datas);
            }


            if ($datas->count[0]->{".count"} > 0 && $datas->count[0]->{".count"} != "") {
                $UserId = ($datas->data[0]->{"registro.usuario_id"});
                $Usuario = new Usuario($UserId);
                $traceConsulta = $_GET['traceConsulta'];
                $fechaConsulta = $_GET['fechaConsulta'];
                $horaConsulta = $_GET['horaConsulta'];
                $oDate = new Datetime($fechaConsulta);
                $fecha = $oDate->format('Y-m-d');

                $codEmpresa = $_GET['codEmpresa'];
                $codServicio = $_GET['codServicio'];
                $codAgencia = $_GET['codAgencia'];
                $codCanal = $_GET['codCanal'];
                $terminal = $_GET['terminal'];
                $transactionId = $traceConsulta;
                $clave = $_GET['password'];
                $amount = $_GET['montoTotalConvertido'];


                $ConfigurationEnvironment = new ConfigurationEnvironment();

                if ($ConfigurationEnvironment->isDevelopment()) {
                    $UsuarioPuntoVenta = new Usuario(20721);
                } else {
                    $UsuarioPuntoVenta = new Usuario('');
                }

                $ClientId = $Usuario->usuarioId;

                $FromDateLocal = $fecha . "  00:00:00";

                $ToDateLocal = $fecha . " 23:59:59";

                $OrderedItem = $params->OrderedItem;

                $MaxRows = $json->params->count;
                $SkeepRows = $json->params->start;

                $State = $json->params->State;

                if ($SkeepRows == "") {
                    $SkeepRows = 0;
                }

                if ($OrderedItem == "") {
                    $OrderedItem = 1;
                }

                $MaxRows = 1;


                $rules = [];
                array_push($rules, array("field" => "usuario_mandante.usuario_mandante", "data" => "$ClientId", "op" => "eq"));


                array_push($rules, array("field" => "usuario_mandante.estado", "data" => "A", "op" => "eq"));


                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json = json_encode($filtro);

                $UsuarioRecarga = new UsuarioRecarga();
                $UsuarioMandante = new UsuarioMandante();
                $usuarios = $UsuarioMandante->getUsuariosMandantesCustom("usuario_mandante.* ", "usuario_mandante.usumandante_id", "asc", $SkeepRows, $MaxRows, $json, true);
                $fecha = new DateTime();
                $Time = $fecha->getTimestamp();
                $recargas = json_decode($usuarios);


                $cuentasData = array();

                foreach ($recargas->data as $key => $value) {
                    $arraybet = array();
                    $arraybet["numFactura"] = (strval($Time));
                    $arraybet["tipoMoneda"] = '';

                    switch ($value->{"usuario_mandante.moneda"}) {
                        case 'PEN':
                            $arraybet["tipoMoneda"] = '604';
                            break;
                        case 'USD':
                            $arraybet["tipoMoneda"] = '840';
                            break;
                    }

                    $arraybet["montoTipoCambio"] = 1.00;
                    $arraybet["montoDeuda"] = 0.0;
                    $arraybet["montoDeudaConvertido"] = 0.0;

                    $arraybet["comision"] = 0.00;
                    $arraybet["ComisionConvertido"] = 0.00;

                    $arraybet["conceptosAdicionales"] = array();

                    $arraybet["montoTotalConcepto"] = 0.00;
                    $arraybet["montoTotalConceptoConv"] = 0.00;
                    $arraybet["montoTotal"] = 99999.99;
                    $arraybet["montoTotalConvertido"] = 99999.99;
                    $arraybet["formaPago"] = 'ABIERTO';
                    $arraybet["montoMinimo"] = 100.0;
                    $arraybet["montoMinimoConvertido"] = 10.0;
                    $arraybet["montoMaximo"] = 0.0;
                    $arraybet["montoMaximoConvertido"] = 0.0;
                    $arraybet["fechaEmision"] = date('Ymd');
                    $fecha_actual = date("d-m-Y");
                    $arraybet["fechaVencimiento"] = date("Ymd", strtotime($fecha_actual . "+ 1 days"));
                    $arraybet["tipoIntegracion"] = 'Online';
                    $arraybet["glosa"] = "Ante una consulta o reclamo contactar a DoradoBet al correo contacto@doradobet.com";
                    $arraybet["orden"] = 1;


                    array_push($cuentasData, $arraybet);
                }

                $respuesta["codigo"] = '00';
                $respuesta["mensaje"] = 'Exitoso';

                $respuesta["nombreCliente"] = str_pad($Usuario->nombre, 50);
                $respuesta["nroSumin"] = $nroSumin;
                $respuesta["ordenPrelacion"] = 'INDISTINTO';
                $respuesta["lstdebt"] = $cuentasData;

                $respuesta = json_decode(json_encode($respuesta));
            } else {
                $respuesta["codigo"] = '24';
                $respuesta["mensaje"] = 'Usuario no encontrado';
            }

            break;


        case 'api/create':
            try {
                $tiempo_inicial = microtime(true);
                $Signature = $headers['Authorization'];
                date_default_timezone_set("America/Bogota");

                $HoraActual2 = date("H:i");
                $HoFinal = "23:59:59";

                $horaTotal = $HoFinal - $HoraActual2;

                $Hora = $horaTotal * 60;


                $token = token($Hora);

                $nroSumin = $params->nroSumin;
                $numFactura = $params->numFactura;

                $tracePago = $params->tracePago;
                $traceConsulta = $params->traceConsulta;
                $fechaPago = $params->fechaPago;
                $horaPago = $params->horaPago;
                $oDate = new Datetime($fechaPago);
                $fecha = $oDate->format('Y-m-d');

                $codEmpresa = $params->codEmpresa;
                $codServicio = $params->codServicio;
                $codAgencia = $params->codAgencia;
                $codCanal = $params->codCanal;
                $terminal = $params->terminal;
                $transactionId = $tracePago;
                $clave = $params->password;
                $montoDeudaConvertido = $params->conceptosAdicionales->montoDeudaConvertido;
                $comisionConvertido = $params->conceptosAdicionales->comisionConvertido;
                $amount = $params->montoTotalConvertido;

                $rules = [];


                array_push($rules, array("field" => "registro.usuario_id", "data" => "$nroSumin", "op" => "eq"));
                array_push($rules, array("field" => "usuario.pais_id", "data" => "173", "op" => "eq"));
                array_push($rules, array("field" => "usuario.mandante", "data" => "0", "op" => "eq"));
                array_push($rules, array("field" => "usuario.estado", "data" => "A", "op" => "eq"));

                $filtro = array("rules" => $rules, "groupOp" => "AND");

                $jsonfiltro = json_encode($filtro);
                $Registro = new Registro();
                $datas = $Registro->getRegistroCustom("registro.usuario_id", "registro.usuario_id", "asc", 0, 1, $jsonfiltro, true);
                $datas = json_decode($datas);

                if ($datas->data[0] == "") {
                    $rules = [];
                    array_push($rules, array("field" => "registro.cedula", "data" => "$nroSumin", "op" => "eq"));
                    array_push($rules, array("field" => "usuario.pais_id", "data" => "173", "op" => "eq"));
                    array_push($rules, array("field" => "usuario.mandante", "data" => "0", "op" => "eq"));
                    array_push($rules, array("field" => "usuario.estado", "data" => "A", "op" => "eq"));
                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    $jsonfiltro = json_encode($filtro);
                    $Registro = new Registro();
                    $datas = $Registro->getRegistroCustom("registro.usuario_id", "registro.usuario_id", "asc", 0, 1, $jsonfiltro, true);
                    $datas = json_decode($datas);
                }

                if ($datas->count[0]->{".count"} > 0) {
                    $UserId = ($datas->data[0]->{"registro.usuario_id"});

                    $Usuario = new Usuario($UserId);

                    $ConfigurationEnvironment = new ConfigurationEnvironment();

                    if ($ConfigurationEnvironment->isDevelopment()) {
                        $UsuarioPuntoVenta = new Usuario(20721);
                    } else {
                        $UsuarioPuntoVenta = new Usuario(2088007);
                    }


                    $UsuarioRecargaMySqlDAO = new UsuarioRecargaMySqlDAO();
                    $Transaction = $UsuarioRecargaMySqlDAO->getTransaction();


                    $UsuarioRecarga = new UsuarioRecarga();
                    $UsuarioRecarga->setUsuarioId($Usuario->usuarioId);
                    $UsuarioRecarga->setFechaCrea(date('Y-m-d H:i:s'));
                    $UsuarioRecarga->setPuntoventaId($UsuarioPuntoVenta->usuarioId);
                    $UsuarioRecarga->setValor($amount);
                    $UsuarioRecarga->setPorcenRegaloRecarga(0);
                    $UsuarioRecarga->setDirIp(0);
                    $UsuarioRecarga->setPromocionalId(0);
                    $UsuarioRecarga->setValorPromocional(0);
                    $UsuarioRecarga->setHost(0);
                    $UsuarioRecarga->setMandante($Usuario->mandante);
                    $UsuarioRecarga->setPedido(0);
                    $UsuarioRecarga->setPorcenIva(0);
                    $UsuarioRecarga->setMediopagoId(0);
                    $UsuarioRecarga->setValorIva(0);
                    $UsuarioRecarga->setEstado('A');
                    $UsuarioRecarga->setVersion(2);

                    $UsuarioRecargaMySqlDAO->insert($UsuarioRecarga);

                    $consecutivo_recarga = $UsuarioRecarga->recargaId;


                    if ($Usuario->fechaPrimerdeposito == "") {
                        $Usuario->fechaPrimerdeposito = date('Y-m-d H:i:s');
                        $Usuario->montoPrimerdeposito = $UsuarioRecarga->getValor();
                        $UsuarioMySqlDAO2 = new UsuarioMySqlDAO($Transaction);
                        $UsuarioMySqlDAO2->update($Usuario);
                    }


                    $TransaccionApiUsuario = new TransaccionApiUsuario();

                    $TransaccionApiUsuario->setUsuarioId($Usuario->usuarioId);
                    $TransaccionApiUsuario->setUsuariogeneraId($UsuarioPuntoVenta->usuarioId);
                    $TransaccionApiUsuario->setValor(($amount));
                    $TransaccionApiUsuario->setTipo(0);
                    $TransaccionApiUsuario->setTValue(json_encode($params));
                    $TransaccionApiUsuario->setRespuestaCodigo("OK");
                    $TransaccionApiUsuario->setRespuesta("OK");
                    $TransaccionApiUsuario->setTransaccionId($transactionId);

                    $TransaccionApiUsuario->setUsucreaId(0);
                    $TransaccionApiUsuario->setUsumodifId(0);


                    if ($TransaccionApiUsuario->existsTransaccionIdAndProveedor("OK")) {
                        throw new Exception("Transaccion ya procesada", "10001");
                    }

                    $TransaccionApiUsuario->setIdentificador($consecutivo_recarga);
                    $TransaccionApiUsuarioMySqlDAO = new TransaccionApiUsuarioMySqlDAO($Transaction);
                    $TransaccionApiUsuarioMySqlDAO->insert($TransaccionApiUsuario);

                    $TransapiusuarioLog = new TransapiusuarioLog();

                    $TransapiusuarioLog->setIdentificador($consecutivo_recarga);
                    $TransapiusuarioLog->setTransaccionId($transactionId);
                    $TransapiusuarioLog->setTValue(json_encode($params));
                    $TransapiusuarioLog->setTipo(0);
                    $TransapiusuarioLog->setValor($amount);
                    $TransapiusuarioLog->setUsuariogeneraId($UsuarioPuntoVenta->usuarioId);
                    $TransapiusuarioLog->setUsuarioId($Usuario->usuarioId);


                    $TransapiusuarioLog->setUsucreaId(0);
                    $TransapiusuarioLog->setUsumodifId(0);


                    $TransapiusuarioLogMySqlDAO = new TransapiusuarioLogMySqlDAO($Transaction);
                    $Transapiusuariolog_id = $TransapiusuarioLogMySqlDAO->insert($TransapiusuarioLog);


                    $UsuarioHistorial = new UsuarioHistorial();
                    $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                    $UsuarioHistorial->setDescripcion('');
                    $UsuarioHistorial->setMovimiento('E');
                    $UsuarioHistorial->setUsucreaId(0);
                    $UsuarioHistorial->setUsumodifId(0);
                    $UsuarioHistorial->setTipo(10);
                    $UsuarioHistorial->setValor($amount);
                    $UsuarioHistorial->setExternoId($consecutivo_recarga);

                    $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                    $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);


                    $Usuario->credit($amount, $Transaction);


                    $PuntoVenta = new PuntoVenta("", $UsuarioPuntoVenta->usuarioId);

                    if ($PuntoVenta->getCreditosBase() >= $amount) {
                        $rowsUpdate = $PuntoVenta->setBalanceCreditosBase(-$amount, $Transaction);

                        if ($rowsUpdate == null || $rowsUpdate <= 0) {
                            throw new Exception("Error General", "100000");
                        }
                    } else {
                        throw new Exception("no tiene cupo disponible para realizar la recarga", "100002");
                    }


                    $rowsUpdate = 0;

                    $FlujoCaja = new FlujoCaja();
                    $FlujoCaja->setFechaCrea(date('Y-m-d'));
                    $FlujoCaja->setHoraCrea(date('H:i'));
                    $FlujoCaja->setUsucreaId($UsuarioPuntoVenta->usuarioId);
                    $FlujoCaja->setTipomovId('E');
                    $FlujoCaja->setValor($UsuarioRecarga->getValor());
                    $FlujoCaja->setRecargaId($UsuarioRecarga->getRecargaId());
                    $FlujoCaja->setMandante($UsuarioRecarga->getMandante());
                    $FlujoCaja->setTraslado('N');
                    $FlujoCaja->setFormapago1Id(1);
                    $FlujoCaja->setCuentaId('0');

                    if ($FlujoCaja->getFormapago2Id() == "") {
                        $FlujoCaja->setFormapago2Id(0);
                    }

                    if ($FlujoCaja->getValorForma1() == "") {
                        $FlujoCaja->setValorForma1(0);
                    }

                    if ($FlujoCaja->getValorForma2() == "") {
                        $FlujoCaja->setValorForma2(0);
                    }

                    if ($FlujoCaja->getCuentaId() == "") {
                        $FlujoCaja->setCuentaId(0);
                    }

                    if ($FlujoCaja->getPorcenIva() == "") {
                        $FlujoCaja->setPorcenIva(0);
                    }

                    if ($FlujoCaja->getValorIva() == "") {
                        $FlujoCaja->setValorIva(0);
                    }

                    $FlujoCajaMySqlDAO = new FlujoCajaMySqlDAO($Transaction);


                    $rowsUpdate = $FlujoCajaMySqlDAO->insert($FlujoCaja);

                    if ($rowsUpdate > 0) {
                        $UsuarioHistorial = new UsuarioHistorial();
                        $UsuarioHistorial->setUsuarioId($UsuarioPuntoVenta->usuarioId);
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
                        throw new Exception("Error General", "100000");
                    }


                    $ClientId = $Usuario->usuarioId;

                    $FromDateLocal = $fecha . "  00:00:00";

                    $ToDateLocal = $fecha . " 23:59:59";


                    $OrderedItem = $params->OrderedItem;

                    $MaxRows = $json->params->count;
                    $SkeepRows = $json->params->start;

                    $State = $json->params->State;

                    if ($SkeepRows == "") {
                        $SkeepRows = 0;
                    }

                    if ($OrderedItem == "") {
                        $OrderedItem = 1;
                    }

                    $MaxRows = 1;


                    $rules = [];
                    array_push($rules, array("field" => "usuario_recarga.usuario_id", "data" => "$ClientId", "op" => "eq"));


                    array_push($rules, array("field" => "usuario_recarga.estado", "data" => "'P','A'", "op" => "in"));

                    if ($FromDateLocal != "") {
                        array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$FromDateLocal ", "op" => "ge"));
                    }
                    if ($ToDateLocal != "") {
                        array_push($rules, array("field" => "usuario_recarga.fecha_crea", "data" => "$ToDateLocal", "op" => "le"));
                    }


                    $filtro = array("rules" => $rules, "groupOp" => "AND");
                    $json2 = json_encode($filtro);

                    $UsuarioRecarga = new UsuarioRecarga();

                    $recargas = $UsuarioRecarga->getUsuarioRecargasCustom("usuario_recarga.*", "usuario_recarga.recarga_id", "desc", $SkeepRows, $MaxRows, $json2, true, "");


                    $recargas = json_decode($recargas);

                    $cuentasData = array();

                    foreach ($recargas->data as $key => $value) {
                        $montoTotalConvertidoRes = floatval($value->{"usuario_recarga.valor"});

                        $numOperacionEmpresa = $value->{"usuario_recarga.recarga_id"};
                    }

                    $respuesta["codigo"] = '00';
                    $respuesta["mensaje"] = 'Exitoso';
                    $respuesta["nroSumin"] = $nroSumin;
                    $respuesta["numOperacionEmpresa"] = $numOperacionEmpresa;
                    $respuesta["nombreCliente"] = str_pad($Usuario->nombre, 50);
                    $respuesta["glosa"] = "Ante una consulta o reclamo contactar a DoradoBet al correo contacto@doradobet.com";
                    $respuesta["montoDeudaConvertido"] = 0.00;;
                    $respuesta["comisionConvertido"] = 0.00;;
                    $respuesta["montoTotalConvertido"] = $montoTotalConvertidoRes;
                    $respuesta["montoTotalCoceptoConv"] = 0.00;
                    $respuesta["conceptosAdicionales"] = array();


                    $respuesta = json_decode(json_encode($respuesta));
                } else {
                    throw new Exception("Usuario no encontrado", "24");
                }
            } catch (Exception $e) {
                convertError($e->getCode(), $e->getMessage());
            }


            break;

        case 'api/cancel':
            try {
                $Signature = $headers['Authorization'];
                date_default_timezone_set("America/Bogota");

                $HoraActual2 = date("H:i");
                $HoFinal = "23:59:59";

                $horaTotal = $HoFinal - $HoraActual2;

                $Hora = $horaTotal * 60;


                $token = token($Hora);


                $nroSumin = $params->nroSumin;

                $tracePago = $params->tracePago;
                $traceConsulta = $params->traceConsulta;
                $fechaPago = $params->fechaPago;
                $horaPago = $params->horaPago;
                $oDate = new Datetime($fechaPago);
                $fecha = $oDate->format('Y-m-d');


                $codEmpresa = $params->codEmpresa;
                $codServicio = $params->codServicio;
                $codAgencia = $params->codAgencia;
                $codCanal = $params->codCanal;
                $terminal = $params->terminal;
                $transactionId = $traceConsulta;
                $clave = $params->password;
                $montoDeudaConvertido = $params->montoDeudaConvertido;
                $comisionConvertido = $params->comisionConvertido;
                $nombreConcepto = $params->conceptosAdicionales->nombreConcepto;
                $montoConceptoConvertido = $params->conceptosAdicionales->montoConceptoConvertido;

                $montoTotalConceptoConv = $params->montoTotalConceptoConv;
                $montoTotalConvertido = $params->montoTotalConvertido;
                $amount = $params->montoTotalConvertido;

                $rules = [];


                array_push($rules, array("field" => "registro.usuario_id", "data" => "$nroSumin", "op" => "eq"));


                $filtro = array("rules" => $rules, "groupOp" => "AND");

                $jsonfiltro = json_encode($filtro);
                $Registro = new Registro();
                $datas = $Registro->getRegistroCustom("registro.usuario_id", "registro.usuario_id", "asc", 0, 1, $jsonfiltro, true);
                $datas = json_decode($datas);


                if ($datas->data[0] == "") {
                    $rules = [];
                    array_push($rules, array("field" => "registro.cedula", "data" => "$nroSumin", "op" => "eq"));
                    array_push($rules, array("field" => "usuario.pais_id", "data" => "173", "op" => "eq"));
                    array_push($rules, array("field" => "usuario.mandante", "data" => "0", "op" => "eq"));
                    array_push($rules, array("field" => "usuario.estado", "data" => "A", "op" => "eq"));
                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    $jsonfiltro = json_encode($filtro);
                    $Registro = new Registro();
                    $datas = $Registro->getRegistroCustom("registro.usuario_id", "registro.usuario_id", "asc", 0, 1, $jsonfiltro, true);
                    $datas = json_decode($datas);
                }

                $UserId = ($datas->data[0]->{"registro.usuario_id"});


                $Usuario = new Usuario($UserId);

                if ($Usuario->usuarioId != "") {
                    $ConfigurationEnvironment = new ConfigurationEnvironment();

                    if ($ConfigurationEnvironment->isDevelopment()) {
                        $UsuarioPuntoVenta = new Usuario(20721);
                    } else {
                        $UsuarioPuntoVenta = new Usuario('');
                    }


                    $UsuarioRecargaMySqlDAO = new UsuarioRecargaMySqlDAO();
                    $Transaction = $UsuarioRecargaMySqlDAO->getTransaction();


                    $TransaccionApiUsuario = new TransaccionApiUsuario('', $tracePago, $UsuarioPuntoVenta->usuarioId, '0');


                    $IdRecarga = $TransaccionApiUsuario->identificador;
                    $UsuarioRecarga = new UsuarioRecarga($IdRecarga);

                    if ($UsuarioRecarga->getEstado() == "A") {
                        $UsuarioRecargaMySqlDAO = new UsuarioRecargaMySqlDAO();
                        $Transaction = $UsuarioRecargaMySqlDAO->getTransaction();
                        $UsuarioMandante = new UsuarioMandante("", $UsuarioRecarga->getUsuarioId(), $UsuarioRecarga->getMandante());
                        $valor = $UsuarioRecarga->getValor();

                        $puntoventa_id = $UsuarioRecarga->getPuntoventaId();
                        $UsuarioPuntoVenta = new Usuario($puntoventa_id);
                        $Usuario = new Usuario($UsuarioRecarga->getUsuarioId());

                        $UsuarioRecarga->setEstado('I');
                        $UsuarioRecarga->setFechaElimina(date("Y-m-d H:i:s"));
                        $UsuarioRecarga->setUsueliminaId($UsuarioMandante->getUsuarioMandante());

                        $UsuarioRecargaMySqlDAO->update($UsuarioRecarga);

                        $FlujoCaja = new FlujoCaja();
                        $FlujoCaja->setFechaCrea(date('Y-m-d'));
                        $FlujoCaja->setHoraCrea(date('H:i'));
                        $FlujoCaja->setUsucreaId($UsuarioRecarga->getPuntoventaId());
                        $FlujoCaja->setTipomovId('S');
                        $FlujoCaja->setValor($valor);
                        $FlujoCaja->setRecargaId($UsuarioRecarga->getRecargaId());
                        $FlujoCaja->setMandante($UsuarioRecarga->getMandante());

                        if ($FlujoCaja->getFormapago1Id() == "") {
                            $FlujoCaja->setFormapago1Id(0);
                        }

                        if ($FlujoCaja->getFormapago2Id() == "") {
                            $FlujoCaja->setFormapago2Id(0);
                        }

                        if ($FlujoCaja->getValorForma1() == "") {
                            $FlujoCaja->setValorForma1(0);
                        }

                        if ($FlujoCaja->getValorForma2() == "") {
                            $FlujoCaja->setValorForma2(0);
                        }

                        if ($FlujoCaja->getCuentaId() == "") {
                            $FlujoCaja->setCuentaId(0);
                        }

                        if ($FlujoCaja->getPorcenIva() == "") {
                            $FlujoCaja->setPorcenIva(0);
                        }

                        if ($FlujoCaja->getValorIva() == "") {
                            $FlujoCaja->setValorIva(0);
                        }
                        $FlujoCaja->setDevolucion('S');

                        $FlujoCajaMySqlDAO = new FlujoCajaMySqlDAO($Transaction);
                        $FlujoCajaMySqlDAO->insert($FlujoCaja);


                        $PuntoVenta = new PuntoVenta("", $UsuarioPuntoVenta->puntoventaId);

                        $rowsUpdate = $PuntoVenta->setBalanceCreditosBase($valor, $Transaction);


                        if ($rowsUpdate == null || $rowsUpdate <= 0) {
                            throw new Exception("Error General", "100000");
                        }
                        $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO($Transaction);

                        $PuntoVentaMySqlDAO->update($PuntoVenta);


                        $SaldoUsuonlineAjuste = new SaldoUsuonlineAjuste();

                        $SaldoUsuonlineAjuste->setTipoId('S');
                        $SaldoUsuonlineAjuste->setUsuarioId($UsuarioRecarga->getUsuarioId());
                        $SaldoUsuonlineAjuste->setValor($valor);
                        $SaldoUsuonlineAjuste->setFechaCrea(date('Y-m-d H:i:s'));
                        $SaldoUsuonlineAjuste->setUsucreaId($UsuarioMandante->getUsuarioMandante());
                        $SaldoUsuonlineAjuste->setSaldoAnt($Usuario->getBalance());
                        $SaldoUsuonlineAjuste->setObserv("Reversion recarga " . $UsuarioRecarga->getRecargaId());
                        if ($SaldoUsuonlineAjuste->getMotivoId() == "") {
                            $SaldoUsuonlineAjuste->setMotivoId(0);
                        }
                        $dir_ip = explode(",", $_SERVER["HTTP_X_FORWARDED_FOR"])[0];

                        $SaldoUsuonlineAjuste->setDirIp($dir_ip);
                        $SaldoUsuonlineAjuste->setMandante($UsuarioRecarga->getMandante());
                        $SaldoUsuonlineAjuste->setTipo(10);


                        $SaldoUsuonlineAjusteMysql = new SaldoUsuonlineAjusteMySqlDAO($Transaction);

                        $SaldoUsuonlineAjusteMysql->insert($SaldoUsuonlineAjuste);


                        $Usuario->debit($valor, $Transaction);


                        $UsuarioHistorial = new UsuarioHistorial();
                        $UsuarioHistorial->setUsuarioId($UsuarioRecarga->getUsuarioId());
                        $UsuarioHistorial->setDescripcion('');
                        $UsuarioHistorial->setMovimiento('S');
                        $UsuarioHistorial->setUsucreaId(0);
                        $UsuarioHistorial->setUsumodifId(0);
                        $UsuarioHistorial->setTipo(10);
                        $UsuarioHistorial->setValor($valor);
                        $UsuarioHistorial->setExternoId($UsuarioRecarga->getRecargaId());

                        $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                        $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);

                        $UsuarioHistorial = new UsuarioHistorial();
                        $UsuarioHistorial->setUsuarioId($UsuarioPuntoVenta->puntoventaId);
                        $UsuarioHistorial->setDescripcion('');
                        $UsuarioHistorial->setMovimiento('E');
                        $UsuarioHistorial->setUsucreaId(0);
                        $UsuarioHistorial->setUsumodifId(0);
                        $UsuarioHistorial->setTipo(10);
                        $UsuarioHistorial->setValor($UsuarioRecarga->getValor());
                        $UsuarioHistorial->setExternoId($UsuarioRecarga->getRecargaId());

                        $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                        $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial, '1');

                        $Transaction->commit();

                        $respuesta["codigo"] = '00';
                        $respuesta["mensaje"] = 'Transacción Exitosa';
                        $respuesta = json_decode(json_encode($respuesta));
                    } else {
                        throw new Exception("Estado de recarga inactivo", "45");
                    }
                } else {
                    throw new Exception("usuario no existe", "24");
                }
            } catch (Exception $e) {
                convertError($e->getCode(), $e->getMessage());
            }


            break;

        default:
            break;
    }


    $response = $respuesta;
    $response = json_encode($response);
    if ($response === "null") {
        $response = "";
    } else {
        print_r($response);
    }
} catch (Exception $e) {
    convertError($e->getCode(), $e->getMessage());
    syslog(LOG_WARNING, "ERRORAPIOPERATOR :" . $e->getCode() . ' - ' . $e->getMessage() . json_encode($params) . json_encode($_SERVER) . json_encode($_REQUEST));
}

/**
 * Convierte un código de error y mensaje en una respuesta estándar para el proveedor.
 *
 * @param integer $code    Código de error recibido.
 * @param string  $message Mensaje de error recibido.
 *
 * Este método utiliza un `switch` para mapear códigos de error internos a códigos y mensajes
 * estándar definidos por el proveedor. Luego, genera una respuesta en formato JSON con el
 * código y mensaje correspondientes.
 *
 * @return void
 */
function convertError($code, $message)
{
    $codeProveedor = "";
    $messageProveedor = "";

    switch ($code) {
        case 20:
            $codeProveedor = "20";
            $messageProveedor = "CLIENTE NO ENCONTRADO";
            break;
        case 22:
            $codeProveedor = "22";
            $messageProveedor = "El usuario no existe";
            break;
        case 24:
            $codeProveedor = "24";
            $messageProveedor = "El usuario no existe";
            break;

        case 20001:
            $codeProveedor = "21"; //
            $messageProveedor = "Fondos insuficientes";
            break;

        case 0:
            $codeProveedor = "30";
            $messageProveedor = "Error interno";
            break;

        case 30003:
            $codeProveedor = "33";
            $messageProveedor = "Login con contraseña incorrectos";
            break;

        case 28:
            $codeProveedor = "28";
            $messageProveedor = "No Existe transacción";
            break;

        case 44:
            $codeProveedor = "44";
            $messageProveedor = "No existe la recarga";
            break;
        case 45:
            $codeProveedor = "45";
            $messageProveedor = "Estado de recarga inactivo";
            break;
        case 29:
            $codeProveedor = "29";
            $messageProveedor = "La transacción no existe";
            break;

        case 10001:
            $codeProveedor = "11";
            $messageProveedor = "Transaccion ya procesada";

            break;

        case 10005:
            $codeProveedor = "15";
            $messageProveedor = "No existe la transacción";

            break;

        case 100002:
            $codeProveedor = "12";
            $messageProveedor = "No tiene cupo disponible para realizar la recarga";

            break;
        case 100000:
            $codeProveedor = "01";
            $messageProveedor = "Error General";

            break;
        case 20003:
            $codeProveedor = "23";
            $messageProveedor = "Usuario inactivo";

            break;

        default:
            $codeProveedor = "10";
            $messageProveedor = "Error interno en el servicio";
            break;
    }
    $respuesta["codigo"] = $codeProveedor;
    $respuesta["mensaje"] = $messageProveedor;

    $respuesta = json_encode(array_merge($respuesta));
    print_r($respuesta);
}

/**
 * Convierte una cantidad de una moneda a otra.
 *
 * @param string $from_Currency Código de la moneda de origen.
 * @param string $to_Currency   Código de la moneda de destino.
 * @param float  $amount        Cantidad a convertir.
 *
 * @return float Cantidad convertida.
 */
function currencyConverter($from_Currency, $to_Currency, $amount)
{
    if ($from_Currency == $to_Currency) {
        return $amount;
    }
    global $currencies_valor;
    $convertido = -1;
    $bool = false;

    foreach ($currencies_valor as $key => $valor) {
        if ($key == ($from_Currency . "" . $to_Currency)) {
            $convertido = $amount * $valor;
            $bool = true;
        } elseif ($key == ($from_Currency . "" . $to_Currency)) {
            $convertido = ($amount) / $valor;
            $bool = true;
        }
    }
    if ( ! $bool) {
        $from_Currency = urlencode($from_Currency);
        $to_Currency = urlencode($to_Currency);
        $encode_amount = 1;

        $rawdata = file_get_contents("http://api.currencies.zone/v1/quotes/$from_Currency/$to_Currency/json?quantity=$encode_amount&key=44|YSqBgkAbvbGfenoxx62OaSnoD~rF8rw~");
        if ($_SESSION["usuario2"] == 5) {
        }
        $rawdata = json_decode($rawdata);
        $currencies_valor += [$from_Currency . "" . $to_Currency => $rawdata->result->amount];

        $convertido = $amount * $rawdata->result->amount;
    }


    return $convertido;
}

/**
 * Obtiene una lista de eventos deportivos según los parámetros proporcionados.
 *
 * @param string $sport         Deporte a buscar.
 * @param string $region        Región del deporte.
 * @param string $competition   Competencia específica.
 * @param string $fecha_inicial Fecha inicial del rango de búsqueda.
 * @param string $fecha_final   Fecha final del rango de búsqueda.
 *
 * @return array Lista de eventos encontrados.
 */
function getMatches($sport, $region, $competition, $fecha_inicial, $fecha_final)
{
    global $URL_ITAINMENT;

    $rawdata = file_get_contents($URL_ITAINMENT . "/Export/GetEvents?importerId=1&from=" . $fecha_inicial . "&to=" . $fecha_final . "");
    $data = simplexml_load_string($rawdata);
    $datos = json_decode($rawdata);
    $array = array();
    foreach ($datos as $item) {
        if ($sport == $item->SportId) {
            foreach ($item->Categories as $item2) {
                if ($item2->CategoryId == $region) {
                    foreach ($item2->Championships as $item3) {
                        if ($item3->ChampionshipId == $competition) {
                            foreach ($item3->Events as $item4) {
                                $item_data = array(
                                    "Id" => $item4->EventId,
                                    "Name" => $item4->Name
                                );
                                array_push($array, $item_data);
                            }
                        }
                    }
                }
            }
        }
    }


    return $array;
}

/**
 * Genera un token JWT con un tiempo de expiración especificado.
 *
 * @param integer $expires_in Tiempo en segundos para que el token expire.
 *
 * @return string Token generado.
 */
function token($expires_in)
{
    $header = json_encode([
        'alg' => 'HS256',
        'typ' => 'JWT'
    ]);

    $payload = json_encode([
        'codigo' => 0,
        'mensaje' => 'OK',
        'expires_in' => $expires_in
    ]);

    $key = 'virtualsoft';

    $signature = hash('sha256', $header . $payload . $key);

    $token = base64_encode($header) . '.' . base64_encode($payload) . '.' . $signature;

    return $token;
}

/**
 * Genera una clave aleatoria alfanumérica.
 *
 * @param integer $length Longitud de la clave.
 *
 * @return string Clave generada.
 */
function GenerarClaveTicket($length)
{
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

/**
 * Genera una clave aleatoria numérica.
 *
 * @param integer $length Longitud de la clave.
 *
 * @return string Clave generada.
 */
function GenerarClaveTicket2($length)
{
    $characters = '0123456789';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

/**
 * Encripta o desencripta una cadena de texto.
 *
 * @param string $action Acción a realizar: 'encrypt' o 'decrypt'.
 * @param string $string Cadena a procesar.
 *
 * @return string|false Cadena procesada o false en caso de error.
 */
function encrypt_decrypt($action, $string)
{
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $secret_key = 'D0RAD0';
    $secret_iv = 'D0RAD0';
    // hash
    $key = hash('sha256', $secret_key);

    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    if ($action == 'encrypt') {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    } elseif ($action == 'decrypt') {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
    return $output;
}

/**
 * Obtiene la dirección IP del cliente.
 *
 * @return string Dirección IP del cliente o 'UNKNOWN' si no se puede determinar.
 */
function get_client_ip()
{
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP')) {
        $ipaddress = getenv('HTTP_CLIENT_IP');
    } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    } elseif (getenv('HTTP_X_FORWARDED')) {
        $ipaddress = getenv('HTTP_X_FORWARDED');
    } elseif (getenv('HTTP_FORWARDED_FOR')) {
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    } elseif (getenv('HTTP_FORWARDED')) {
        $ipaddress = getenv('HTTP_FORWARDED');
    } elseif (getenv('REMOTE_ADDR')) {
        $ipaddress = getenv('REMOTE_ADDR');
    } else {
        $ipaddress = 'UNKNOWN';
    }
    return $ipaddress;
}

/**
 * Elimina duplicados de un array multidimensional basado en una clave específica.
 *
 * @param array  $array Array a procesar.
 * @param string $key   Clave para identificar duplicados.
 *
 * @return array Array sin duplicados.
 */
function unique_multidim_array($array, $key)
{
    $temp_array = array();
    $i = 0;
    $key_array = array();

    foreach ($array as $val) {
        if ( ! in_array($val[$key], $key_array)) {
            $key_array[$i] = $val[$key];
            $temp_array[$i] = $val;
        }
        $i++;
    }
    return $temp_array;
}

/**
 * Elimina tildes y caracteres especiales de una cadena.
 *
 * @param string $cadena Cadena a procesar.
 *
 * @return string Cadena sin tildes ni caracteres especiales.
 */
function quitar_tildes($cadena)
{
    $no_permitidas = array("á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú", "ñ", "À", "Ã", "Ì", "Ò", "Ù", "Ã™", "Ã ", "Ã¨", "Ã¬", "Ã²", "Ã¹", "ç", "Ç", "Ã¢", "ê", "Ã®", "Ã´", "Ã»", "Ã‚", "ÃŠ", "ÃŽ", "Ã”", "Ã›", "ü", "Ã¶", "Ã–", "Ã¯", "Ã¤", "«", "Ò", "Ã", "Ã„", "Ã‹");
    $permitidas = array("a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "n", "N", "A", "E", "I", "O", "U", "a", "e", "i", "o", "u", "c", "C", "a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "u", "o", "O", "i", "a", "e", "U", "I", "A", "E");
    $texto = str_replace($no_permitidas, $permitidas, $cadena);
    return $texto;
}

/**
 * Encripta una cadena de texto utilizando AES-128-CTR.
 *
 * @param string $data           Cadena a encriptar.
 * @param string $encryption_key Clave de encriptación (opcional).
 *
 * @return string Cadena encriptada.
 */
function encrypt($data, $encryption_key = "")
{
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('AES-128-CTR'));
    $encrypted_string = bin2hex($iv) . openssl_encrypt($data, 'AES-128-CTR', gethostname() . "|" . ip2long($_SERVER['SERVER_ADDR']), 0, $iv);
    return $encrypted_string;
}

/**
 * Desencripta una cadena de texto encriptada con AES-128-CTR.
 *
 * @param string $data           Cadena encriptada.
 * @param string $encryption_key Clave de encriptación (opcional).
 *
 * @return string|false Cadena desencriptada o false en caso de error.
 */
function decrypt($data, $encryption_key = "")
{
    $iv_strlen = 2 * openssl_cipher_iv_length('AES-128-CTR');
    if (preg_match("/^(.{" . $iv_strlen . "})(.+)$/", $data, $regs)) {
        list(, $iv, $crypted_string) = $regs;
        $decrypted_string = openssl_decrypt($crypted_string, 'AES-128-CTR', gethostname() . "|" . ip2long($_SERVER['SERVER_ADDR']), 0, hex2bin($iv));
        return $decrypted_string;
    } else {
        return false;
    }
}

/**
 * Depura una cadena eliminando caracteres no deseados.
 *
 * @param string $texto_depurar Cadena a depurar.
 *
 * @return string Cadena depurada.
 */
function DepurarCaracteres($texto_depurar)
{
    $texto_depurar = str_replace("'", "", $texto_depurar);
    $texto_depurar = str_replace('"', "", $texto_depurar);
    $texto_depurar = str_replace(">", "", $texto_depurar);
    $texto_depurar = str_replace("<", "", $texto_depurar);
    $texto_depurar = str_replace("[", "", $texto_depurar);
    $texto_depurar = str_replace("]", "", $texto_depurar);
    $texto_depurar = str_replace("{", "", $texto_depurar);
    $texto_depurar = str_replace("}", "", $texto_depurar);
    $texto_depurar = str_replace("�", "", $texto_depurar);
    $texto_depurar = str_replace("`", "", $texto_depurar);
    $texto_depurar = str_replace("|", "", $texto_depurar);
    $texto_depurar = str_replace("�", "", $texto_depurar);
    $texto_depurar = str_replace("�", "", $texto_depurar);
    $texto_depurar = str_replace("%", "", $texto_depurar);
    $texto_depurar = str_replace("&", "", $texto_depurar);
    $texto_depurar = str_replace("�", "", $texto_depurar);
    $texto_depurar = str_replace("~", "", $texto_depurar);
    $texto_depurar = str_replace("+", "", $texto_depurar);
    $texto_depurar = str_replace("^", "", $texto_depurar);
    $texto_depurar = str_replace("/", "", $texto_depurar);
    //$texto_retornar = addslashes($texto_depurar);

    $c = null;
    return $texto_depurar;
}

/**
 * Obtiene la dirección IP del cliente utilizando variables de entorno.
 *
 * @return string Dirección IP del cliente o 'UNKNOWN' si no se puede determinar.
 */
function get_client_ip_env()
{
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP')) {
        $ipaddress = getenv('HTTP_CLIENT_IP');
    } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    } elseif (getenv('HTTP_X_FORWARDED')) {
        $ipaddress = getenv('HTTP_X_FORWARDED');
    } elseif (getenv('HTTP_FORWARDED_FOR')) {
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    } elseif (getenv('HTTP_FORWARDED')) {
        $ipaddress = getenv('HTTP_FORWARDED');
    } elseif (getenv('REMOTE_ADDR')) {
        $ipaddress = getenv('REMOTE_ADDR');
    } else {
        $ipaddress = 'UNKNOWN';
    }

    return $ipaddress;
}

/**
 * Convierte una dirección IPv6 a IPv4.
 *
 * @param string $ipv6 Dirección IPv6 a convertir.
 *
 * @return string Dirección IPv4 convertida.
 */
function convertIP6($ipv6)
{
    $ipv6Addr = @inet_pton($ipv6);
    if ($ipv6Addr === false || strlen($ipv6Addr) !== 16) {
    }
    if (strpos($ipv6Addr, chr(0x20) . chr(0x02)) === 0) { // 6to4 addresses starting with 2002:
        $ipv4Addr = substr($ipv6Addr, 2, 4);
    } else {
        $ipv4Addr = '';
        for ($i = 0; $i < 8; $i += 2) { // Get first 8 bytes because the most of ISP provide addresses with mask /64
            $ipv4Addr .= chr(ord($ipv6Addr[$i]) ^ ord($ipv6Addr[$i + 1]));
        }
        $ipv4Addr[0] = chr(ord($ipv4Addr[0]) | 240); // Class E space
    }
    $ipv4 = inet_ntop($ipv4Addr);
    return $ipv4;
}


