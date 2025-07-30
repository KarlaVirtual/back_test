<?php

/**
 * Este archivo contiene un script para procesar archivos de respuesta de pagos
 * provenientes del sistema Interbank. Realiza operaciones como la conexión a
 * servicios SFTP, la lectura y procesamiento de archivos, y la actualización
 * de estados en la base de datos.
 *
 * @category   Red
 * @package    API
 * @subpackage Integrations
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $BonoInterno                     Variable que representa un bono interno en el sistema.
 * @var mixed $sqlProcesoInterno2              Variable que almacena una segunda instancia del proceso SQL interno.
 * @var mixed $data                            Esta variable contiene datos que se procesan o retornan, pudiendo incluir estructuras complejas (arrays u objetos).
 * @var mixed $line                            Variable que almacena información relacionada con una línea específica en un conjunto de datos o archivo.
 * @var mixed $fechaL1                         Esta variable almacena una fecha, que puede indicar la creación, modificación o vencimiento de un registro.
 * @var mixed $fechaL2                         Esta variable almacena una fecha, que puede indicar la creación, modificación o vencimiento de un registro.
 * @var mixed $INTERBANKSERVICES               Variable que hace referencia a los servicios bancarios del sistema Interbank.
 * @var mixed $filesTotal                      Variable que almacena el total de archivos involucrados en un proceso o transacción.
 * @var mixed $files                           Variable que almacena una lista de archivos involucrados en una operación.
 * @var mixed $file                            Variable que almacena un archivo específico en un proceso.
 * @var mixed $dateFile                        Variable que almacena la fecha de un archivo.
 * @var mixed $BonoDetalleMySqlDAO             Variable que hace referencia a una clase o componente DAO relacionado con la base de datos MySQL para bonos.
 * @var mixed $transaccion                     Variable que almacena datos relacionados con una transacción.
 * @var mixed $NombreCompleto                  Variable que almacena el nombre completo de una persona o entidad.
 * @var mixed $NombreCompletoDesglosado        Variable que almacena el nombre completo desglosado en sus componentes (primer nombre, apellido, etc.).
 * @var mixed $FechaCreacionResponse           Esta variable almacena una fecha, que puede indicar la creación, modificación o vencimiento de un registro.
 * @var mixed $CuerpoNombre                    Variable que almacena el cuerpo o formato del nombre de una persona.
 * @var mixed $Canal                           Variable que almacena el canal de comunicación o transacción.
 * @var mixed $Flujo                           Variable que almacena el flujo de un proceso o transacción.
 * @var mixed $SubFlujo                        Variable que almacena un subflujo dentro de un flujo mayor.
 * @var mixed $Rubro                           Variable que almacena la categoría o rubro de un servicio o producto.
 * @var mixed $Empresa                         Variable que almacena el nombre o identificador de una empresa.
 * @var mixed $Servicio                        Variable que almacena el servicio ofrecido en una transacción.
 * @var mixed $Identificador                   Variable que almacena un identificador único para un objeto o entidad.
 * @var mixed $FechaCreacionEntrada            Esta variable almacena una fecha, que puede indicar la creación, modificación o vencimiento de un registro.
 * @var mixed $FinNombre                       Variable que almacena el final de un nombre o identificador.
 * @var mixed $Indicador_Status                Variable que almacena el indicador de estado de un proceso o transacción.
 * @var mixed $extensi                         Variable que almacena la extensión de un archivo o dato.
 * @var mixed $rules                           Esta variable contiene las reglas de validación o negocio, utilizadas para controlar el flujo de operaciones.
 * @var mixed $filtro                          Esta variable contiene criterios de filtrado para la búsqueda o procesamiento de datos.
 * @var mixed $SkeepRows                       Variable que indica el número de registros a omitir en una consulta.
 * @var mixed $OrderedItem                     Variable que representa un elemento ordenado en una lista.
 * @var mixed $MaxRows                         Variable que define el número máximo de registros a retornar en una consulta.
 * @var mixed $json                            Esta variable contiene datos en formato JSON, que pueden ser decodificados para su procesamiento.
 * @var mixed $TransaccionProducto             Variable que almacena información sobre una transacción de producto.
 * @var mixed $transacciones                   Variable que almacena un conjunto de transacciones.
 * @var mixed $Archivo                         Variable que almacena un archivo específico.
 * @var mixed $DetalleRespuesta                Variable que almacena detalles sobre la respuesta de una transacción o solicitud.
 * @var mixed $estado                          Variable que almacena el estado de un proceso o entidad.
 * @var mixed $erroresTotales                  Variable que almacena el número total de errores ocurridos en un proceso.
 * @var mixed $log                             Esta variable se utiliza para registrar mensajes y eventos de log en el sistema, facilitando la depuración y seguimiento.
 * @var mixed $_REQUEST                        Variable superglobal que contiene datos enviados a través del método REQUEST.
 * @var mixed $IdentificadorDelBeneficiario    Variable que almacena el identificador único del beneficiario de un pago o transacción.
 * @var mixed $NombreDelBeneficiario           Variable que almacena el nombre del beneficiario de un pago o transacción.
 * @var mixed $NombreDelBeneficiarioDesglosado Variable que almacena el nombre del beneficiario desglosado en partes.
 * @var mixed $CuentaId                        Variable que almacena el identificador de cuenta asociada a una transacción.
 * @var mixed $Name1                           Variable que almacena el primer nombre de una persona o entidad.
 * @var mixed $Name2                           Variable que almacena el segundo nombre de una persona o entidad.
 * @var mixed $LastName                        Variable que almacena el primer apellido de una persona.
 * @var mixed $LastName2                       Variable que almacena el segundo apellido de una persona.
 * @var mixed $TipoDeAbono                     Variable que almacena el tipo de abono realizado en una cuenta.
 * @var mixed $Tienda                          Variable que almacena el nombre o identificador de una tienda.
 * @var mixed $NroDeCuentaDeAbono              Variable que almacena el número de cuenta a la cual se realiza el abono.
 * @var mixed $MonedaDeLaCuentaDeAbono         Variable que almacena la moneda asociada a la cuenta de abono.
 * @var mixed $MontoDelAbono                   Variable que almacena el monto del abono realizado en una cuenta.
 * @var mixed $MonedaDeMontoIntangible         Variable que almacena la moneda asociada al monto intangible en una transacción.
 * @var mixed $MontoIntangible                 Variable que almacena el monto intangible en una transacción o proceso.
 * @var mixed $FechaDeVencimiento              Esta variable almacena una fecha, que puede indicar la creación, modificación o vencimiento de un registro.
 * @var mixed $EstadoDelPago                   Variable que almacena el estado del pago realizado (por ejemplo, pendiente, completado).
 * @var mixed $MotivoDeLaObservaci             Variable que almacena el motivo de una observación realizada durante una transacción.
 * @var mixed $NumeroDeDocumentoComercial      Variable que almacena el número de documento comercial de una entidad o cliente.
 * @var mixed $CuentaCobro                     Variable que almacena información sobre una cuenta de cobro.
 * @var mixed $transactionid                   Variable que almacena el identificador único de una transacción.
 * @var mixed $TransprodLogMysqlDAO            Variable que representa la capa de acceso a datos MySQL para TransprodLog.
 * @var mixed $Transaction                     Esta variable contiene información de una transacción, utilizada para el seguimiento y procesamiento de operaciones.
 * @var mixed $TransaccionProductoMySqlDAO     Variable que representa la capa de acceso a datos MySQL para transacciones de productos.
 * @var mixed $TransprodLog                    Variable que almacena registros de transacciones de productos.
 * @var mixed $TransprodLog_id                 Variable que almacena el identificador de un registro en TransprodLog.
 * @var mixed $rowsUpdate                      Variable que almacena el número de filas actualizadas en una consulta.
 * @var mixed $CuentaCobroMySqlDAO             Variable que representa la capa de acceso a datos MySQL para cuentas de cobro.
 * @var mixed $Usuario                         Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $UsuarioHistorial                Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $UsuarioHistorialMySqlDAO        Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $useragent                       Variable que almacena información sobre el User-Agent de un usuario.
 * @var mixed $_SERVER                         Variable superglobal que contiene información del servidor y entorno de ejecución.
 * @var mixed $jsonServer                      Variable que almacena datos en formato JSON relacionados con un servidor.
 * @var mixed $serverCodif                     Variable que almacena información codificada de un servidor.
 * @var mixed $ismobile                        Variable que indica si el acceso se realiza desde un dispositivo móvil.
 * @var mixed $iPod                            Variable que almacena información sobre dispositivos iPod.
 * @var mixed $iPhone                          Variable que almacena información sobre dispositivos iPhone.
 * @var mixed $iPad                            Variable que almacena información sobre dispositivos iPad.
 * @var mixed $Android                         Variable que almacena información sobre dispositivos Android.
 * @var mixed $webOS                           Variable que almacena información sobre sistemas webOS.
 * @var mixed $e                               Esta variable se utiliza para capturar excepciones o errores en bloques try-catch.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\dto\CuentaCobro;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransprodLog;
use Backend\dto\Usuario;
use Backend\dto\BonoInterno;
use Backend\dto\UsuarioHistorial;
use Backend\integrations\payout\INTERBANKSERVICES;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\mysql\BonoDetalleMySqlDAO;


$BonoInterno = new BonoInterno();

$sqlProcesoInterno2 = "

SELECT * FROM proceso_interno2 WHERE tipo='INTERBANK'
";


$data = $BonoInterno->execQuery('', $sqlProcesoInterno2);
$data = $data[0];
$line = $data->{'proceso_interno2.fecha_ultima'};

if ($line == '') {
    exit();
}


$fechaL1 = date('Y-m-d H:i:00', strtotime($line . '+1 hours'));
$fechaL2 = date('Y-m-d H:i:s', strtotime($line . '+1 hours'));


// foreach ($filesTotal as $file) {

//     $dateFile = date('Y-m-d H:i:s', $file['mtime']);
//     if($dateFile>$fechaL2){
//        //El NO se ha procesado
//         array_push($files, $file['filename']);
//     }else{
//         //El archivo ya se proceso
//     }
// }


$BonoInterno = new BonoInterno();
$BonoDetalleMySqlDAO = new BonoDetalleMySqlDAO();
$transaccion = $BonoDetalleMySqlDAO->getTransaction();


$sqlProcesoInterno2 = "
UPDATE proceso_interno2 SET fecha_ultima='" . $fechaL1 . "' WHERE  tipo='INTERBANK';

";

$data = $BonoInterno->execQuery($transaccion, $sqlProcesoInterno2);
$transaccion->commit();

print_r('antesfiles');
try {

    $INTERBANKSERVICES = new INTERBANKSERVICES();
    $filesTotal = $INTERBANKSERVICES->connectionSFTPReceiver($fechaL2);
    print_r($filesTotal);
    $files = array();
    foreach ($filesTotal as $file) {
        print_r($file);

        $NombreCompleto = $file['filename'];


        //Desglose del nombre del archivo

        $NombreCompletoDesglosado = explode("_", $NombreCompleto);

        //Fecha y hora de creación + guion bajo Fecha y hora de creación del TXT de respuesta + guion bajo  (creado por procesos del banco)
        $FechaCreacionResponse = $NombreCompletoDesglosado[0];

        //Canal Constante 'H2H'
        $CuerpoNombre = $NombreCompletoDesglosado[1]; //variable que contiene la informacion de la mitad del nombre del archivo
        $Canal = substr($CuerpoNombre, 0, 3);

        //Flujo Utilizar la letra que le corresponde al flujo: H, M, S, X, R
        $Flujo = substr($CuerpoNombre, 3, 1);

        //Sub Flujo Si un flujo tiene diferentes rutas de recepción de archivos. Si no tiene enviar ceros.
        $SubFlujo = substr($CuerpoNombre, 4, 3);

        //Rubro Código del rubro, proporcionado por Interbank
        $Rubro = substr($CuerpoNombre, 7, 2);

        //Empresa Código de Empresa. Proporcionado por Interbank
        $Empresa = substr($CuerpoNombre, 9, 4);

        //Servicio Código del servicio. Proporcionado por Interbank
        $Servicio = substr($CuerpoNombre, 13, 2);

        //Identificador de lote Código único que identifica al lote (generado por la Empresa)
        $Identificador = substr($CuerpoNombre, 15, 25);

        //Guión Fecha y hora de creación del archivo Guión (bajo) Fecha y hora de creación del archivo de Entrada
        $FechaCreacionEntrada = $NombreCompletoDesglosado[2];
        //Indicador de resultado (se antepone guion bajo) Indica el resultato de procesamiento del lote (Ver columna de valores)
        $FinNombre = explode(".", $NombreCompletoDesglosado[3]);

        $Indicador_Status = $FinNombre[0];

        //Punto y extensión del archivo Punto y extensión correspondiente al tipo de archivo
        $extensión = $FinNombre[1];


    $Archivo = fopen(__DIR__ . '/../../../../integrations-int/payout/interbank/api/archivos/response/' . $NombreCompleto, 'r');//abrir archivo, nombre archivo, modo apertura
    print_r($Archivo);
    while ( ! feof($Archivo)) {
        try {
            $DetalleRespuesta = fgets($Archivo);
            print_r('DetalleRespuesta');
            print_r($DetalleRespuesta);

            if ($DetalleRespuesta !== null && $DetalleRespuesta !== "" && $DetalleRespuesta !== false) {
                $estado = 'P';
                $erroresTotales = ['1013', '1016', '8808', '1343', '8001', '8002', '8003', '8004', '8005'];

                /* Obtenemos Variables que nos llegan */
                $log = "\r\n" . "-------------------------" . "\r\n";
                $log = $log . json_encode($_REQUEST);
                $log = $log . $NombreCompleto;
                $log = $log . "RESPONSE";
                $log = $log . $DetalleRespuesta;
                //Save string to log, use FILE_APPEND to append.
                fwriteCustom('log_' . date("Y-m-d") . '.log', $log);

                //Verificamos si el detalle corresponde a un error total o parcial
                if (in_array($DetalleRespuesta, $erroresTotales)) {
                    $estado = 'R';
                } else {
                    $IdentificadorDelBeneficiario = substr($DetalleRespuesta, 0, 20);

                    //Nombre del Beneficiario*	Nombre del Beneficiario o razón social
                    $NombreDelBeneficiario = substr($DetalleRespuesta, 20, 20);

                    $NombreDelBeneficiarioDesglosado = explode(" ", $NombreDelBeneficiario);

                    $CuentaId = $NombreDelBeneficiarioDesglosado[0];

                    $LastName = $NombreDelBeneficiarioDesglosado[1];

                    $Name1 = $NombreDelBeneficiarioDesglosado[2];

                    //$Name2 = $NombreDelBeneficiarioDesglosado[3];

                    //Tipo de abono*	Tipo de abono
                    $TipoDeAbono = substr($DetalleRespuesta, 40, 2);

                    //Tienda*	Oficina de IBK
                    $Tienda = substr($DetalleRespuesta, 42, 3);

                    //Nro de cuenta de abono*	Número de cuenta de abono
                    $NroDeCuentaDeAbono = substr($DetalleRespuesta, 45, 20);

                    //Moneda de la cuenta de abono*	Valor fijo (ver columna Valores)
                    $MonedaDeLaCuentaDeAbono = substr($DetalleRespuesta, 65, 2);

                    //Monto del abono*	Monto del abono
                    $MontoDelAbono = substr($DetalleRespuesta, 67, 15);

                    //Moneda de monto intangible	Moneda de monto intangible (solo para rubro 06=CTS)
                    $MonedaDeMontoIntangible = substr($DetalleRespuesta, 82, 2);

                    //Monto Intangible	Monto intangible (solo para rubro 06=CTS)
                    $MontoIntangible = substr($DetalleRespuesta, 84, 15);

                    //Fecha de vencimiento	Fecha de vencimiento Formato: SSAAMMDD
                    $FechaDeVencimiento = substr($DetalleRespuesta, 99, 8);

                    //Estado del pago*	Estado del pago, si es 0000 el pago se ha procesado satisfactoriamente, diferente a cero tiene obsevaciones
                    $EstadoDelPago = substr($DetalleRespuesta, 107, 4);

                    //Motivo de la observación*	Motivo de la observación o rechazo del pago
                    $MotivoDeLaObservación = substr($DetalleRespuesta, 111, 70);

                    //Número de documento comercial	Número de documento comercial (Factura, crédito o débito)
                    $NumeroDeDocumentoComercial = substr($DetalleRespuesta, 181, 20);
                }

                if (true) {
                    // $estado = 'P';

                    switch ($EstadoDelPago) {
                        case "0000":
                            $estado = 'A';
                            break;
                        default :
                            $estado = 'R';
                            break;
                    }
                    print_r(PHP_EOL);
                    print_r($CuentaId);
                    print_r(PHP_EOL);

                        $CuentaCobro = new CuentaCobro($CuentaId);

                        $transactionid = $CuentaCobro->transproductoId;

                    if ($estado != "P") {
                        $TransprodLogMysqlDAO = new TransprodLogMySqlDAO();

                            $TransaccionProducto = new TransaccionProducto($transactionid);
                            $Transaction = $TransprodLogMysqlDAO->getTransaction();

                            $TransaccionProducto->setEstado("I");
                            $TransaccionProducto->setEstadoProducto($estado);
                            $TransaccionProductoMySqlDAO = new TransaccionProductoMySqlDAO($Transaction);
                            $TransaccionProductoMySqlDAO->update($TransaccionProducto);

                            $TransprodLog = new TransprodLog();
                            $TransprodLog->setTransproductoId($transactionid);
                            $TransprodLog->setEstado($estado);
                            $TransprodLog->setTipoGenera('A');

                        if ($MotivoDeLaObservación !== false && $MotivoDeLaObservación !== null && $MotivoDeLaObservación !== "") {
                            $TransprodLog->setComentario($MotivoDeLaObservación);
                        } else {
                            $TransprodLog->setComentario($DetalleRespuesta);
                        }

                            $TransprodLog->setTValue($DetalleRespuesta);
                            $TransprodLog->setUsucreaId(0);
                            $TransprodLog->setUsumodifId(0);

                            $TransprodLog_id = $TransprodLogMysqlDAO->insert($TransprodLog);

                            $rowsUpdate = 0;

                        if ($CuentaCobro->getEstado() == "S") {
                            if ($estado == "A") {
                                $CuentaCobro->setEstado("I");
                                $CuentaCobro->setFechaPago(date('Y-m-d H:i:s'));
                                $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO($Transaction);
                                $rowsUpdate = $CuentaCobroMySqlDAO->update($CuentaCobro, " AND (estado = 'S') ");
                            }

                                if ($estado == "R") {
                                    $CuentaCobro->setEstado("R");
                                    $CuentaCobro->setFechaAccion(date('Y-m-d H:i:s'));

                                    $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO($Transaction);
                                    $rowsUpdate = $CuentaCobroMySqlDAO->update($CuentaCobro, " AND (estado = 'S') ");
                                }


                            if ($estado == "R" && $rowsUpdate > 0) {
                                $Usuario = new Usuario($TransaccionProducto->usuarioId);
                                $Usuario->creditWin(floatval($CuentaCobro->getValor()) + floatval($CuentaCobro->getImpuesto()), $Transaction);

                                    $UsuarioHistorial = new UsuarioHistorial();
                                    $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                                    $UsuarioHistorial->setDescripcion('');
                                    $UsuarioHistorial->setMovimiento('E');
                                    $UsuarioHistorial->setUsucreaId(0);
                                    $UsuarioHistorial->setUsumodifId(0);
                                    $UsuarioHistorial->setTipo(40);
                                    $UsuarioHistorial->setValor($TransaccionProducto->getValor());
                                    $UsuarioHistorial->setExternoId($CuentaCobro->getCuentaId());


                                $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                                $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);
                            }

                                $Transaction->commit();

                                if ($estado == "A") {
                                    $useragent = $_SERVER['HTTP_USER_AGENT'];
                                    $jsonServer = json_encode($_SERVER);
                                    $serverCodif = base64_encode($jsonServer);


                                    $ismobile = '';

                                    if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {

                                    $ismobile = '1';
                                }

                                    //Detect special conditions devices
                                    $iPod = stripos($_SERVER['HTTP_USER_AGENT'], "iPod");
                                    $iPhone = stripos($_SERVER['HTTP_USER_AGENT'], "iPhone");
                                    $iPad = stripos($_SERVER['HTTP_USER_AGENT'], "iPad");
                                    $Android = stripos($_SERVER['HTTP_USER_AGENT'], "Android");
                                    $webOS = stripos($_SERVER['HTTP_USER_AGENT'], "webOS");


                                //do something with this information
                                if ($iPod || $iPhone) {
                                    $ismobile = '1';
                                } elseif ($iPad) {
                                    $ismobile = '1';
                                } elseif ($Android) {
                                    $ismobile = '1';
                                }
                            }
                        }
                    }
                }
            }
        } catch (Exception $e) {
        }
    }

    fclose($Archivo);
}


} catch (Exception $e) {
    print_r($e);

}