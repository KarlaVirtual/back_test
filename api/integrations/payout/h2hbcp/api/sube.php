<?php

/**
 * Este archivo contiene un script para generar un archivo de texto con una estructura específica,
 * enviarlo a un servidor remoto mediante SFTP y manejar conexiones SSH.
 *
 * @category   Integración
 * @package    API
 * @subpackage Payout
 * @author     Desconocido
 * @version    1.0.0
 * @since      2025-05-09
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed $conn                  Variable que almacena la conexión a la base de datos o servidor.
 * @var mixed $Canal                 Variable que almacena el canal de comunicación o transacción.
 * @var mixed $Flujo                 Variable que almacena el flujo de un proceso o transacción.
 * @var mixed $SubFlujo              Variable que almacena un subflujo dentro de un flujo mayor.
 * @var mixed $RubroNombre           Variable que almacena el nombre del rubro o categoría de un servicio o producto.
 * @var mixed $EmpresaNombre         Variable que almacena el nombre de la empresa asociada a una transacción.
 * @var mixed $ServicioNombre        Variable que almacena el nombre del servicio ofrecido en una transacción.
 * @var mixed $IndentiLote           Variable que almacena el identificador único de un lote de productos o servicios.
 * @var mixed $FechaAndHora          Esta variable almacena una fecha, que puede indicar la creación, modificación o vencimiento de un registro.
 * @var mixed $Extencion             Variable que almacena la extensión o formato de un archivo o dato.
 * @var mixed $Codigo                Variable que almacena un código asociado a un proceso o entidad.
 * @var mixed $RubroCabecera         Variable que almacena la cabecera del rubro, utilizada en la categorización.
 * @var mixed $EmpresaCabecera       Variable que almacena la cabecera de la empresa para clasificación o agrupamiento.
 * @var mixed $ServicioCabecera      Variable que almacena la cabecera del servicio para clasificación o agrupamiento.
 * @var mixed $CuentaCargo           Variable que almacena la cuenta asociada al cargo realizado.
 * @var mixed $TipoCuenta            Variable que almacena el tipo de cuenta (por ejemplo, corriente, ahorro).
 * @var mixed $Moneda                Variable que almacena la moneda utilizada en una transacción.
 * @var mixed $Nombre                Variable que almacena el nombre de una persona o entidad.
 * @var mixed $FechaCreacion         Esta variable almacena una fecha, que puede indicar la creación, modificación o vencimiento de un registro.
 * @var mixed $TipoProceso           Variable que almacena el tipo de proceso (por ejemplo, pago, reembolso).
 * @var mixed $FechaProceso          Esta variable almacena una fecha, que puede indicar la creación, modificación o vencimiento de un registro.
 * @var mixed $Nregistro             Variable que almacena el número de registros involucrados en una operación.
 * @var mixed $TotalSoles            Variable que almacena el total de una transacción en soles.
 * @var mixed $TotalDolares          Variable que almacena el total de una transacción en dólares.
 * @var mixed $VersionMacro          Variable que almacena la versión de un macro o proceso automatizado.
 * @var mixed $CodigoRegistro        Variable que almacena el código asociado a un registro de operación.
 * @var mixed $CodigoBeneficiario    Variable que almacena el código único del beneficiario de un pago.
 * @var mixed $TipoDocPago           Variable que almacena el tipo de documento utilizado para el pago.
 * @var mixed $NumDocPago            Variable que almacena el número del documento utilizado para el pago.
 * @var mixed $FechaVencimientoDoc   Esta variable almacena una fecha, que puede indicar la creación, modificación o vencimiento de un registro.
 * @var mixed $MonedaAbono           Variable que almacena la moneda utilizada para realizar el abono.
 * @var mixed $MontoAbono            Variable que almacena el monto total del abono realizado.
 * @var mixed $IndicadorBanco        Variable que almacena el indicador del banco asociado a la transacción.
 * @var mixed $num                   Variable que almacena un número genérico o identificador.
 * @var mixed $TipoAbono             Variable que almacena el tipo de abono realizado (por ejemplo, efectivo, transferencia).
 * @var mixed $MonedaCuenta          Variable que almacena la moneda de la cuenta asociada a una transacción.
 * @var mixed $OficinaCuenta         Variable que almacena la oficina o sucursal de la cuenta.
 * @var mixed $NumeroCuenta          Variable que almacena el número de cuenta bancario asociado a una transacción.
 * @var mixed $TipoPersonas          Variable que almacena el tipo de persona (por ejemplo, natural, jurídica).
 * @var mixed $TipoDocIdentidad      Variable que almacena el tipo de documento de identidad de una persona.
 * @var mixed $NumDocIdentidad       Variable que almacena el número del documento de identidad de una persona.
 * @var mixed $NombreBeneficiario    Variable que almacena el nombre del beneficiario de una transacción o pago.
 * @var mixed $MonedaMontoIntangible Variable que almacena la moneda asociada al monto intangible en una transacción.
 * @var mixed $MontoIntangible       Variable que almacena el monto intangible en una transacción o proceso.
 * @var mixed $filler                Variable utilizada como espacio reservado o de relleno en un proceso de datos.
 * @var mixed $NumCelular            Variable que almacena el número de teléfono móvil de una persona.
 * @var mixed $Correo                Variable que almacena la dirección de correo electrónico de una persona o entidad.
 * @var mixed $miarchivo             Variable que almacena la referencia a un archivo específico en un proceso.
 * @var mixed $texto                 Variable que almacena un texto genérico.
 * @var mixed $fp                    Variable que almacena información sobre la forma de pago.
 * @var mixed $ftp_server            Variable que almacena la dirección o nombre del servidor FTP utilizado para la transferencia de archivos.
 * @var mixed $usuario               Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $passphrase            Variable que almacena una contraseña o frase secreta utilizada en un proceso de encriptación.
 * @var mixed $pass                  Variable que almacena una contraseña o clave de acceso.
 * @var mixed $puerto                Variable que almacena el número de puerto utilizado en una conexión de red.
 * @var mixed $connection            Variable que almacena una conexión establecida, generalmente en un contexto de base de datos o red.
 * @var mixed $tunnel                Variable que almacena información relacionada con un túnel de red, utilizado para cifrar datos.
 * @var mixed $result                Variable que almacena el resultado de una operación o transacción.
 * @var mixed $sftp                  Variable que almacena la referencia a un servidor SFTP (protocolo seguro de transferencia de archivos).
 * @var mixed $nombreArchi           Variable que almacena el nombre de un archivo específico.
 * @var mixed $Envio                 Variable que almacena la información relacionada con el envío de datos o archivos.
 * @var mixed $e                     Esta variable se utiliza para capturar excepciones o errores en bloques try-catch.
 * @var mixed $RR                    Variable que podría estar relacionada con un registro de respuesta o estado en un proceso.
 * @var mixed $EEEE                  Variable que podría representar un valor o indicador específico en un contexto determinado.
 * @var mixed $Puerto                Variable que almacena el número de puerto utilizado para establecer una conexión.
 * @var mixed $conexion              Variable que almacena la conexión activa a una base de datos, servidor u otro servicio.
 * @var mixed $Usuario               Esta variable representa la información del usuario, empleada para identificarlo dentro del sistema.
 * @var mixed $password              Variable que almacena la contraseña.
 */

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\sql\ConnectionProperty;


print_r("Integracion H2hbcp");
print_r(PHP_EOL);
print_r("\r\n");
//$conn = new PDO("mysql:host=" . ConnectionProperty::getHost() . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword());
//$conn->exec("set names utf8");


//Crear archivo con la estructura .txt, guardar temporalmente en el servidor nuestro, luego enviar al servidor del proveedor y luego eliminar en archivo temporal
//if (file_exists("prueba2.txt")) echo "El archivo existe";

//Campos para Nombre de Archivo de entrada:

$Canal = "H2H"; //Constante 'H2H'
$Flujo = "H";  //Utilizar la letra que le corresponde al flujo: H, M, S, X, R
$SubFlujo = "000"; //Si un flujo tiene diferentes rutas de recepción de archivos. Si no tiene enviar ceros.
$RubroNombre = 03;  //Código del rubro, proporcionado por H2hbcp    //03: Proveedores; 04: Remuneraciones; 05: Varios; 06: CTS
$EmpresaNombre = "";  //Código de Empresa. Proporcionado por H2hbcp
$ServicioNombre = "";  //Código del servicio. Proporcionado por H2hbcp
$IndentiLote = "";  //Código único que identifica al lote (generado por la Empresa)
$FechaAndHora = date("YmdHis");  //Guión (bajo) Fecha y hora de creación del archivo   Formato: _SSAAMMDDHHMMSS
$Extencion = ".txt";


//Campos para Cabecera  de Archivo de entrada:

$Codigo = 01; //Constante '01'
$RubroCabecera = 04; //Valor fijo (ver columna valores), código del rubro al que se refiere el pago. Proporcionado por H2hbcp  ---> //03: Proveedores; 04: Remuneraciones; 05: Varios; 06: CTS
$EmpresaCabecera = "EE01"; //Código de la Empresa. Proporcionado por H2hbcp
$ServicioCabecera = 01; //Código del servicio. Proporcionado por H2hbcp
$CuentaCargo = 6003035650390;  //Cuenta de la Empresa en H2hbcp en la que se aplicará el cargo por los pagos a realizar
$TipoCuenta = 001; //Valor fijo (ver columna Valores) ----->  //  001: Cuenta Corriente;  002: Cuenta de Ahorros
$Moneda = 01;   //Valor fijo (ver columna Valores) ----->     01: Soles;  10: Dólares
$Nombre = "Rem.Mayo2016";  //Descripción que el cliente quiera poner al lote a modo de Nombre
$FechaCreacion = 20160731093059;  //Fecha y hora de creación del TXT
$TipoProceso = 0; //Valor fijo ---->  0: En Línea;  1: En Diferido
$FechaProceso = 20160815;  //Si tipo de proceso en línea, la fecha es del día enviado. Si es Diferido la fecha es futura, no Domingos ni feriados.  ----->Formato: SSAAMMDD
$Nregistro = 123456;  //contador de registros detalle ( cuantos registros a procesar contiene el lote)  ----> 123456
$TotalSoles = 000000000000022; //Suma del campo Importe moneda soles ---->  000000000000022
$TotalDolares = 000000000000022;  //Suma del campo Importe moneda dolares ----> 000000000000022
$VersionMacro = "MC001";  //Constante "MC001" ---> MC001

//Campos para DETALLE  de Archivo de entrada:
$CodigoRegistro = 02; //Identifica que el registro es parte del detalle de la instrucción de pago.--->constante '02'

$CodigoBeneficiario = 20100053455; //Código único que identifica al beneficiario asignado por la Empresa cliente.  ---> //Ejem. RUC del Beneficiario  20100053455

$TipoDocPago = "F"; //Tipo de documento de pago, aplica solo para los productos: Proveedores y Varios --> F= Factura (+); C= Nota de Crédito (+); D= Nota de Débito (-)

$NumDocPago = 12345; //Número de documento a pagar (Obligatorio para Orden de pago: Efectivo o Cheque de gerencia) ---> Ejm. El nro de una factura 12345

$FechaVencimientoDoc = 20141223;  //Fecha del vencimiento del documento, formato: AAAAMMDD  20141223

$MonedaAbono = 01;   //Moneda en el que se afectuará la operación ----->     01: Soles;  10: Dólares

$MontoAbono = 99;   //Importe numérico positivo (13 enteros y 2 decimales) ----->    99

$IndicadorBanco = 0;  //Completar con ceros ( '0')

$num = '09';
$TipoAbono = floatval($num); //ES el medio por el que se le pagará al Beneficiario ---->  00=Efectivo; 09=Abono en cuenta; 11=Cheque de Gerencia y 99=Interbancario

$TipoCuenta = 001; //Indica si es Cuenta Corriente, Ahorros o CTS (Obligatorio solo si tipo de abono=09) ----->  //  001=Cuenta Corriente; 002=Arhorros; 007=CTS

$MonedaCuenta = 01;   //Indica moneda de la cuenta del Benenficiario (Obligatorio solo si tipo de abono=09) ----->   01=Soles;  10=Dólares

$OficinaCuenta = 0; //Completar con ceros ( '0') -> 0

$NumeroCuenta = 2000012345678; //Cuenta mismo banco IBK= 13 dígitos; Interbancaria CCI= 20 dígitos (Obligatorio solo si tipo de abono=09 o 99 ---> Cta IBK=2000012345678

$TipoPersonas = 0;  //Completar con ceros ( '0')

$TipoDocIdentidad = 01; //Tipo de documento de identidad del beneficiario -->01=DNI; 02=RUC; 03=Carnet de extrajería; 05=Pasaporte

$NumDocIdentidad = 12345678; //Número de documento de identidad del beneficiario--> Ejem: DNI=12345678

$NombreBeneficiario = "Polo;Muñoz;Jerson";  //Nombre del Beneficiario o razón social --> P=A.Paterno;A.Materno;Nombre;  C=Razon social

$MonedaMontoIntangible = 01; //Moneda del monto intangible (Solo rubro CTS)  --->01: Soles;  10: Dólares

$MontoIntangible = 99; //Importe numérico positivo (13 enteros y 2 decimales), último sueldo bruto multiplicado * 4 (según ley vigente)  --->01: Soles;  10: Dólares

$filler = '      ';  //Espacios -->Seis espacios '      '

$NumCelular = '994000111';   //Números de celular, podría ingresar hasta 4 separados por punto y coma.--->Ejm: 994000111;999000222

$Correo = 'David.polomu@gmail.com';  //Dirección de correo electrónico del beneficiario, permite varios correos separados por punto y coma -->Ejm: Jperez@hotmail.com;Cperez@Hotmail.com


print_r("Esta es la RUTA");


print_r(__DIR__ . '/archivos/');


$miarchivo = fopen(__DIR__ . '/archivos/' . $Canal . $Flujo . $SubFlujo . $RubroNombre . $EmpresaNombre . $ServicioNombre . $IndentiLote . '_' . $FechaAndHora . $Extencion, 'w');//abrir archivo, nombre archivo, modo apertura

$texto = <<<_END
$Codigo$RubroCabecera$EmpresaCabecera$ServicioCabecera$CuentaCargo$TipoCuenta$Moneda$Nombre$FechaCreacion$TipoProceso$FechaProceso$Nregistro$TotalSoles$TotalDolares$VersionMacro
$CodigoRegistro$CodigoBeneficiario$TipoDocPago$NumDocPago$FechaVencimientoDoc$MonedaAbono$MontoAbono$IndicadorBanco$TipoAbono$TipoCuenta$MonedaCuenta$OficinaCuenta$NumeroCuenta$TipoPersonas$TipoDocIdentidad$NumDocIdentidad$NombreBeneficiario$MonedaMontoIntangible$MontoIntangible$filler$NumCelular$Correo
_END;

print_r("Este es mi archivo");
print_r($miarchivo);
print_r("Este es el texto");
print_r($texto);


fwrite($miarchivo, $texto) or die("No se pudo escribir en el archivo");
/*fwrite($miarchivo, // escribir
    $Codigo.$RubroCabecera.$EmpresaCabecera.$ServicioCabecera.$CuentaCargo.$TipoCuenta.$Moneda.$Nombre.$FechaCreacion.$TipoProceso.$FechaProceso.$Nregistro.$TotalSoles.$TotalDolares.$VersionMacro."
    ".
    $CodigoRegistro.$CodigoBeneficiario.$TipoDocPago.$NumDocPago.$FechaVencimientoDoc.$MonedaAbono.$MontoAbono.$IndicadorBanco.$TipoAbono.$TipoCuenta.$MonedaCuenta.$OficinaCuenta.$NumeroCuenta.$TipoPersonas.$TipoDocIdentidad.$NumDocIdentidad.$NombreBeneficiario.$MonedaMontoIntangible.$MontoIntangible.$filler.$NumCelular.$Correo);


echo "Tu archivo se ha guardado con el nombre \"$Canal.$Flujo.$SubFlujo.$RubroNombre.$EmpresaNombre.$ServicioNombre.$IndentiLote.'_'.$FechaAndHora.$Extencion\"";*/
fclose($miarchivo); //cerrar archivo

//$fp = fopen('archivos/prueba.txt', 'a') or die("Se produjo un error al crear el archivo");;


//echo "Se ha escrito sin problemas";

//Conectarme con SSH2


//$ftp_server = "127.0.0.1";
//$ftp_server = "66.228.40.60";
//$ftp_server = "198.199.120.164";


$usuario = "root";
//$usuario = "jerson";
//$passphrase = "jerson2020*";
$passphrase = "jerson2020*";

$pass = '=bvdZ2):<~G#wK7Z';
//$pass = '';


$ftp_server = "sftpa.intercorp.com.pe";
$puerto = 22220;
try {
    // Creando conexión a servidor SSH, puerto 22
    $connection = ssh2_connect($ftp_server, $puerto, array('hostkey' => 'ssh-dss'));


    var_dump($connection);

    if ( ! $connection) {
        print_r("Error NO se ha conectado");
    } else {
        print_r("Se ha conectado");
    }


    ssh2_auth_pubkey_file($connection, $usuario, 'id_dsa.pub', '/home/backend/public_html/api/integrations/payout/h2hbcp/api/id_dsa');

    $tunnel = ssh2_tunnel($connection, $ftp_server, 22);
    //$result = ssh2_auth_pubkey_file($connection, $usuario , __DIR__.'/llavePublica',__DIR__.'/llaveprivada.ppk',$passphrase);
    ssh2_auth_password($connection, $usuario, $pass);  // or use any of the ssh2_auth_* methods

    $sftp = ssh2_sftp($connection);
    //print_r($result);

    $nombreArchi = $Canal . $Flujo . $SubFlujo . $RubroNombre . $EmpresaNombre . $ServicioNombre . $IndentiLote . '_' . $FechaAndHora . $Extencion;


    $Envio = ssh2_scp_send($connection, '/home/backend/public_html/api/integrations/payout/h2hbcp/api/archivos/' . $nombreArchi, __DIR__ . '/opt/pentaho/etl/Jerson/' . $nombreArchi, 0644);
    if ( ! $Envio) {
        die('Envio fallido');
    }
    print_r("llego");
} catch (Exception $e) {
    print_r($e);
}

/*$RR = "";
$EEEE ="";
$Puerto = 22220;

$conexion = "sftpa.intercorp.com.pe";
$Usuario = "usr_".$RR.$EEEE;
$password = "llave_privada";*/


?>