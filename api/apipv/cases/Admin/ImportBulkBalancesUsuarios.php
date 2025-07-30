<?php

use Backend\dto\Banco;
use Backend\dto\BonoDetalle;
use Backend\dto\Categoria;
use Backend\dto\Clasificador;
use Backend\dto\Concesionario;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Consecutivo;
use Backend\dto\CuentaCobro;
use Backend\dto\CupoLog;
use Backend\dto\Descarga;
use Backend\dto\DocumentoUsuario;
use Backend\dto\IntApuestaDetalle;
use Backend\dto\IntCompetencia;
use Backend\dto\IntDeporte;
use Backend\dto\IntEventoApuesta;
use Backend\dto\IntEventoApuestaDetalle;
use Backend\dto\IntEventoDetalle;
use Backend\dto\IntRegion;
use Backend\dto\ItTicketEnc;
use Backend\dto\Mandante;
use Backend\dto\MandanteDetalle;
use Backend\dto\PaisMoneda;
use Backend\dto\PromocionalLog;
use Backend\dto\PuntoVenta;
use Backend\dto\Registro;
use Backend\dto\SaldoUsuonlineAjuste;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioBanco;
use Backend\dto\UsuarioConfig;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioLog;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioOtrainfo;
use Backend\dto\UsuarioPerfil;
use Backend\dto\UsuarioPremiomax;
use Backend\dto\UsuarioPublicidad;
use Backend\dto\UsuarioRecarga;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioMensaje;
use Backend\dto\ProductoMandante;
use Backend\dto\ProdMandanteTipo;
use Backend\dto\Proveedor;
use Backend\dto\Producto;
use Backend\dto\Pais;
use Backend\integrations\casino\IES;
use Backend\integrations\casino\JOINSERVICES;
use Backend\mysql\ConcesionarioMySqlDAO;
use Backend\mysql\ConsecutivoMySqlDAO;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\CupoLogMySqlDAO;
use Backend\mysql\DocumentoUsuarioMySqlDAO;
use Backend\mysql\PromocionalLogMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\mysql\RegistroMySqlDAO;
use Backend\mysql\SaldoUsuonlineAjusteMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\UsuarioBancoMySqlDAO;
use Backend\mysql\UsuarioBannerMySqlDAO;
use Backend\mysql\UsuarioConfigMySqlDAO;
use Backend\mysql\UsuarioConfiguracionMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\mysql\UsuarioMandanteMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\UsuarioOtrainfoMySqlDAO;
use Backend\mysql\UsuarioPerfilMySqlDAO;
use Backend\mysql\UsuarioPremiomaxMySqlDAO;
use Backend\mysql\UsuarioRecargaMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\websocket\WebsocketUsuario;


/**
 * Decodifica un CSV recibido en formato base64 desde una entrada HTTP y procesa los datos.
 *
 * @param string $params JSON codificado que contiene el CSV en formato base64.
 * @param string $params ->CSV CSV codificado en base64.
 *
 * @return array $response Arreglo con los resultados del procesamiento, incluyendo errores y mensajes.
 * @throws Exception Si ocurre un error durante el procesamiento de los datos.
 */

/**
 * Genera una cadena aleatoria de una longitud especificada.
 *
 * @param int $length_of_string La longitud de la cadena aleatoria.
 * @return string La cadena aleatoria generada.
 */
function random_strings($length_of_string)
{
    // Cadena de todos los caracteres alfanuméricos
    $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

    // Mezcla $str_result y devuelve una subcadena
    // de la longitud especificada
    return substr(str_shuffle($str_result), 0, $length_of_string);
}

/* decodifica un CSV recibido en formato base64 desde una entrada HTTP. */
$ConfigurationEnvironment = new ConfigurationEnvironment();

$params = file_get_contents('php://input');
$params = json_decode($params);
$ClientIdCsv = $params->CSV;


$ClientIdCsv = explode("base64,", $ClientIdCsv);

/* decodifica un CSV base64 y lo separa en líneas. */
$ClientIdCsv = $ClientIdCsv[1];
$ClientIdCsv = base64_decode($ClientIdCsv);
//$ClientIdCsv = str_replace(",",";",$ClientIdCsv);

$lines = explode(PHP_EOL, $ClientIdCsv);
$lines = preg_split('/\r\n|\r|\n/', $ClientIdCsv);

if (isset($ClientIdCsv) && $ClientIdCsv != '') {


    /* convierte un CSV en un array multidimensional separando registros y campos. */
    $line = array();
    $i = 0;

    $linee = str_getcsv($ClientIdCsv, "\n");

    //CSV: one line is one record and the cells/fields are seperated by ";"
    //so $dsatz is an two dimensional array saving the records like this: $dsatz[number of record][number of cell]
    foreach ($linee as $line) {
        if ($i > 0) {
            $dsatz[$i] = array();
            $dsatz[$i] = explode(";", $line);

        }


        $i++;
    }

    foreach ($dsatz as $key => $number) {


        /* procesa un array, asignando valores y convirtiendo el formato de número. */
        $k = 0;
        $login = $number[$k];
        $k++;
        $saldodeposito = $number[$k];
        $saldodeposito = str_replace(",", '.', $saldodeposito);
        $k++;
        $saldoretiro = $number[$k];

        /* reemplaza comas por puntos en una variable y asigna valores de un array. */
        $saldoretiro = str_replace(",", '.', $saldoretiro);
        $k++;
        $paisId = $number[$k];
        $k++;
        $UsuarioID = $number[$k];

        try {

            /* Se recupera el 'mandante' de la sesión y se asigna el tipo 'E'. */
            $mandante = $_SESSION['mandante'];
            $tipo = 'E';

            if (($saldodeposito != '' && floatval($saldodeposito) > 0) || ($saldodeposito != '' && floatval($saldodeposito) < 0) || ($saldoretiro != '' && floatval($saldoretiro) > 0) || ($saldoretiro != '' && floatval($saldoretiro) < 0)) {


                /* obtiene información del usuario a partir de su ID, verificando su validez. */
                $SaldoUsuonlineAjusteMysql = new SaldoUsuonlineAjusteMySqlDAO();

                $Transaction = $SaldoUsuonlineAjusteMysql->getTransaction();


                if ($UsuarioID != '' && is_numeric($UsuarioID)) {
                    $Usuario = new Usuario($UsuarioID);
                    $paisId = $Usuario->paisId;
                    $login = $Usuario->login;
                    $mandante = $Usuario->mandante;

                } else {
                    /* Crea un objeto Usuario con parámetros predeterminados si no se cumple una condición. */

                    $Usuario = new Usuario("", $login, '0', $mandante, $paisId);
                }

                /* Asigna el identificador del usuario a la variable $UserId. */
                $UserId = $Usuario->usuarioId;


                if ($saldodeposito != '' && floatval($saldodeposito) > 0) {

                    /* ajusta el saldo del usuario tras un depósito. */
                    $TypeBalance = 0;
                    $Amount = floatval($saldodeposito);

                    $Usuario->credit($Amount, $Transaction);

                    $SaldoUsuonlineAjuste = new SaldoUsuonlineAjuste();


                    /* Código para establecer propiedades de un objeto SaldoUsuonlineAjuste. */
                    $SaldoUsuonlineAjuste->setTipoId($tipo);
                    $SaldoUsuonlineAjuste->setUsuarioId($UserId);
                    $SaldoUsuonlineAjuste->setValor($Amount);
                    $SaldoUsuonlineAjuste->setFechaCrea(date('Y-m-d H:i:s'));
                    $SaldoUsuonlineAjuste->setUsucreaId(0);
                    $SaldoUsuonlineAjuste->setSaldoAnt(0);

                    /* Establece observaciones y motivos, y guarda la dirección IP del usuario. */
                    $SaldoUsuonlineAjuste->setObserv('Migración de saldos');
                    if ($SaldoUsuonlineAjuste->getMotivoId() == "") {
                        $SaldoUsuonlineAjuste->setMotivoId(0);
                    }
                    $dir_ip = explode(",", $_SERVER["HTTP_X_FORWARDED_FOR"])[0];

                    $SaldoUsuonlineAjuste->setDirIp($dir_ip);

                    /* Código que configura y guarda ajustes de saldo para un usuario en la base de datos. */
                    $SaldoUsuonlineAjuste->setMandante($Usuario->mandante);
                    $SaldoUsuonlineAjuste->setTipoSaldo($TypeBalance);

                    $ajusteId = $SaldoUsuonlineAjusteMysql->insert($SaldoUsuonlineAjuste);


                }


                if ($saldoretiro != '' && floatval($saldoretiro) > 0) {

                    /* ajusta el saldo de un usuario tras una transacción específica. */
                    $TypeBalance = 1;
                    $Amount = floatval($saldoretiro);

                    $Usuario->creditWin($Amount, $Transaction);

                    $SaldoUsuonlineAjuste = new SaldoUsuonlineAjuste();


                    /* establece atributos para un objeto de ajuste de saldo de usuario. */
                    $SaldoUsuonlineAjuste->setTipoId($tipo);
                    $SaldoUsuonlineAjuste->setUsuarioId($UserId);
                    $SaldoUsuonlineAjuste->setValor($Amount);
                    $SaldoUsuonlineAjuste->setFechaCrea(date('Y-m-d H:i:s'));
                    $SaldoUsuonlineAjuste->setUsucreaId(0);
                    $SaldoUsuonlineAjuste->setSaldoAnt(0);

                    /* Establece observaciones y dirige la migración de saldos con información IP. */
                    $SaldoUsuonlineAjuste->setObserv('Migración de saldos');
                    if ($SaldoUsuonlineAjuste->getMotivoId() == "") {
                        $SaldoUsuonlineAjuste->setMotivoId(0);
                    }
                    $dir_ip = explode(",", $_SERVER["HTTP_X_FORWARDED_FOR"])[0];

                    $SaldoUsuonlineAjuste->setDirIp($dir_ip);

                    /* configura un ajuste de saldo y lo inserta en la base de datos. */
                    $SaldoUsuonlineAjuste->setMandante($Usuario->mandante);
                    $SaldoUsuonlineAjuste->setTipoSaldo($TypeBalance);

                    $ajusteId = $SaldoUsuonlineAjusteMysql->insert($SaldoUsuonlineAjuste);


                }

                if ($saldodeposito != '' && floatval($saldodeposito) < 0) {

                    /* Código que ajusta el saldo de un usuario tras un depósito. */
                    $TypeBalance = 0;
                    $tipo = 'S';
                    $Amount = floatval($saldodeposito);

                    $Usuario->credit($Amount, $Transaction);

                    $SaldoUsuonlineAjuste = new SaldoUsuonlineAjuste();


                    /* ajusta el saldo de un usuario negativo con información relevante. */
                    $SaldoUsuonlineAjuste->setTipoId($tipo);
                    $SaldoUsuonlineAjuste->setUsuarioId($UserId);
                    $SaldoUsuonlineAjuste->setValor(-$Amount);
                    $SaldoUsuonlineAjuste->setFechaCrea(date('Y-m-d H:i:s'));
                    $SaldoUsuonlineAjuste->setUsucreaId(0);
                    $SaldoUsuonlineAjuste->setSaldoAnt(0);

                    /* Configura observaciones y datos de usuario en un saldo, incluyendo dirección IP. */
                    $SaldoUsuonlineAjuste->setObserv('Migración de saldos');
                    if ($SaldoUsuonlineAjuste->getMotivoId() == "") {
                        $SaldoUsuonlineAjuste->setMotivoId(0);
                    }
                    $dir_ip = explode(",", $_SERVER["HTTP_X_FORWARDED_FOR"])[0];

                    $SaldoUsuonlineAjuste->setDirIp($dir_ip);

                    /* asigna valores y realiza una inserción en base de datos. */
                    $SaldoUsuonlineAjuste->setMandante($Usuario->mandante);
                    $SaldoUsuonlineAjuste->setTipoSaldo($TypeBalance);

                    $ajusteId = $SaldoUsuonlineAjusteMysql->insert($SaldoUsuonlineAjuste);


                }


                if ($saldoretiro != '' && floatval($saldoretiro) < 0) {

                    /* ajusta el saldo de un usuario tras una transacción de retiro. */
                    $TypeBalance = 1;
                    $tipo = 'S';
                    $Amount = floatval($saldoretiro);

                    $Usuario->creditWin($Amount, $Transaction);

                    $SaldoUsuonlineAjuste = new SaldoUsuonlineAjuste();


                    /* Código que ajusta el saldo de un usuario online en un sistema. */
                    $SaldoUsuonlineAjuste->setTipoId($tipo);
                    $SaldoUsuonlineAjuste->setUsuarioId($UserId);
                    $SaldoUsuonlineAjuste->setValor(-$Amount);
                    $SaldoUsuonlineAjuste->setFechaCrea(date('Y-m-d H:i:s'));
                    $SaldoUsuonlineAjuste->setUsucreaId(0);
                    $SaldoUsuonlineAjuste->setSaldoAnt(0);

                    /* Configura ajustes de saldo y dirección IP del usuario en un sistema. */
                    $SaldoUsuonlineAjuste->setObserv('Migración de saldos');
                    if ($SaldoUsuonlineAjuste->getMotivoId() == "") {
                        $SaldoUsuonlineAjuste->setMotivoId(0);
                    }
                    $dir_ip = explode(",", $_SERVER["HTTP_X_FORWARDED_FOR"])[0];

                    $SaldoUsuonlineAjuste->setDirIp($dir_ip);

                    /* ajusta el saldo de un usuario y registra en la base de datos. */
                    $SaldoUsuonlineAjuste->setMandante($Usuario->mandante);
                    $SaldoUsuonlineAjuste->setTipoSaldo($TypeBalance);

                    $ajusteId = $SaldoUsuonlineAjusteMysql->insert($SaldoUsuonlineAjuste);


                }


                /* Se crea un historial de usuario con datos iniciales y sin descripciones. */
                $UsuarioHistorial = new UsuarioHistorial();
                $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                $UsuarioHistorial->setDescripcion('');
                $UsuarioHistorial->setMovimiento($tipo);
                $UsuarioHistorial->setUsucreaId(0);
                $UsuarioHistorial->setUsumodifId(0);

                /* Se establece un historial de usuario y se guarda en la base de datos MySQL. */
                $UsuarioHistorial->setTipo(15);
                $UsuarioHistorial->setValor($Amount);
                $UsuarioHistorial->setExternoId($ajusteId);

                $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);


                /* finaliza y guarda los cambios de una transacción en una base de datos. */
                $Transaction->commit();


            }


        } catch (Exception $e) {
            /* Captura excepciones y muestra su información para depuración en PHP. */

            print_r($e);
        }

    }
}

if (false) {


    /* Define la ruta del archivo CSV que se va a importar en el sistema. */
    $fichero_subido = '/home/home2/backend/api/apipv/cases/Admin/import.csv';

    if (isset($_POST["submit"])) {

        if (isset($_FILES["file"])) {

            //if there was an error uploading the file

            /* Verifica errores al subir un archivo y muestra el código de error correspondiente. */
            if ($_FILES["file"]["error"] > 0) {
                echo "Return Code: " . $_FILES["file"]["error"] . "<br />";

            } else {
                //Print file details
                    /* Muestra información sobre un archivo subido y verifica si ya existe en el servidor. */
                echo "Upload: " . $_FILES["file"]["name"] . "<br />";
                echo "Type: " . $_FILES["file"]["type"] . "<br />";
                echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
                echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";

                //if file already exists
                if (file_exists("upload/" . $_FILES["file"]["name"])) {
                    echo $_FILES["file"]["name"] . " already exists. ";
                } else {
                    /* mueve un archivo subido a la carpeta "upload" y lo renombra. */


                    //Store file in directory "upload" with the name of "uploaded_file.txt"
                    $storagename = "uploaded_file.txt";
                    move_uploaded_file($_FILES["file"]["tmp_name"], $fichero_subido);
                    echo "Stored in: " . "upload/" . $_FILES["file"]["name"] . "<br />";
                }

                if (isset($storagename) && $file = fopen($fichero_subido, r)) {

                    echo "File opened.<br />";


                    /* lee la primera línea de un archivo CSV y cuenta sus campos. */
                    $firstline = fgets($file, 4096);
                    //Gets the number of fields, in CSV-files the names of the fields are mostly given in the first line
                    $num = strlen($firstline) - strlen(str_replace(";", "", $firstline));

                    //save the different fields of the firstline in an array called fields
                    $fields = array();

                    /* lee un archivo CSV y lo almacena en una matriz bidimensional. */
                    $fields = explode(";", $firstline, ($num + 1));

                    $line = array();
                    $i = 0;

                    //CSV: one line is one record and the cells/fields are seperated by ";"
                    //so $dsatz is an two dimensional array saving the records like this: $dsatz[number of record][number of cell]
                    while ($line[$i] = fgets($file, 4096)) {

                        $dsatz[$i] = array();
                        $dsatz[$i] = explode(";", $line[$i], ($num + 1));


                        $i++;
                    }

                    echo "<table>";
                    echo "<tr>";


                    /* Un bucle que itera desde 0 hasta num, comentando la impresión de campos. */
                    for ($k = 0; $k != ($num + 1); $k++) {


                        // echo "<td>" . $fields[$k] . "</td>";
                    }
                    echo "</tr>";

                    foreach ($dsatz as $key => $number) {

                        /* Imprime un array y asigna sus valores a variables consecutivas. */
                        print_r($number);

                        $k = 0;
                        $login = $number[$k];
                        $k++;
                        $saldodeposito = $number[$k];

                        /* reemplaza comas por puntos y extrae valores de un arreglo. */
                        $saldodeposito = str_replace(",", '.', $saldodeposito);
                        $k++;
                        $saldoretiro = $number[$k];
                        $saldoretiro = str_replace(",", '.', $saldoretiro);

                        $k++;
                        $paisId = $number[$k];
                        $k++;

                        /* Asignación del valor de un número específico al identificador de usuario. */
                        $UsuarioID = $number[$k];

                        try {

                            /* Se asignan valores de sesión a variables en PHP para uso posterior. */
                            $mandante = $_SESSION['mandante'];
                            $tipo = 'E';
                            //print_r("ENTRO");
                            //print_r(floatval($saldoretiro));
                            if (($saldodeposito != '' && floatval($saldodeposito) > 0) || ($saldoretiro != '' && floatval($saldoretiro) > 0)) {


                                /* Se crea una instancia de DAO y se obtiene información de un usuario. */
                                $SaldoUsuonlineAjusteMysql = new SaldoUsuonlineAjusteMySqlDAO();

                                $Transaction = $SaldoUsuonlineAjusteMysql->getTransaction();
                                //print_r($login);
                                //print_r($mandante);

                                if ($UsuarioID != '' && is_numeric($UsuarioID)) {
                                    $Usuario = new Usuario($UsuarioID);
                                    $paisId = $Usuario->paisId;
                                    $login = $Usuario->login;
                                    $mandante = $Usuario->mandante;

                                } else {
                                    /* Crea un objeto Usuario con parámetros específicos si no se cumple una condición. */

                                    $Usuario = new Usuario("", $login, '0', $mandante, $paisId);
                                }


                                /* Asignación del identificador de usuario a la variable $UserId desde el objeto $Usuario. */
                                $UserId = $Usuario->usuarioId;


                                //print_r($Usuario);
                                if ($saldodeposito != '' && floatval($saldodeposito) > 0) {

                                    /* ajusta el saldo de un usuario tras un depósito. */
                                    $TypeBalance = 0;
                                    $Amount = floatval($saldodeposito);

                                    $Usuario->credit($Amount, $Transaction);

                                    $SaldoUsuonlineAjuste = new SaldoUsuonlineAjuste();


                                    /* configura un objeto con datos de saldo y usuario. */
                                    $SaldoUsuonlineAjuste->setTipoId($tipo);
                                    $SaldoUsuonlineAjuste->setUsuarioId($UserId);
                                    $SaldoUsuonlineAjuste->setValor($Amount);
                                    $SaldoUsuonlineAjuste->setFechaCrea(date('Y-m-d H:i:s'));
                                    $SaldoUsuonlineAjuste->setUsucreaId(0);
                                    $SaldoUsuonlineAjuste->setSaldoAnt(0);

                                    /* ajusta saldo y registra la dirección IP del usuario. */
                                    $SaldoUsuonlineAjuste->setObserv('Migración de saldos');
                                    if ($SaldoUsuonlineAjuste->getMotivoId() == "") {
                                        $SaldoUsuonlineAjuste->setMotivoId(0);
                                    }
                                    $dir_ip = explode(",", $_SERVER["HTTP_X_FORWARDED_FOR"])[0];

                                    $SaldoUsuonlineAjuste->setDirIp($dir_ip);

                                    /* Se asignan valores a un objeto y se inserta en la base de datos. */
                                    $SaldoUsuonlineAjuste->setMandante($Usuario->mandante);
                                    $SaldoUsuonlineAjuste->setTipoSaldo($TypeBalance);

                                    $ajusteId = $SaldoUsuonlineAjusteMysql->insert($SaldoUsuonlineAjuste);


                                }


                                if ($saldoretiro != '' && floatval($saldoretiro) > 0) {

                                    /* Se ajusta el saldo del usuario tras un crédito de ganancias. */
                                    $TypeBalance = 1;
                                    $Amount = floatval($saldoretiro);

                                    $Usuario->creditWin($Amount, $Transaction);

                                    $SaldoUsuonlineAjuste = new SaldoUsuonlineAjuste();


                                    /* establece propiedades de un objeto "SaldoUsuonlineAjuste" con datos específicos. */
                                    $SaldoUsuonlineAjuste->setTipoId($tipo);
                                    $SaldoUsuonlineAjuste->setUsuarioId($UserId);
                                    $SaldoUsuonlineAjuste->setValor($Amount);
                                    $SaldoUsuonlineAjuste->setFechaCrea(date('Y-m-d H:i:s'));
                                    $SaldoUsuonlineAjuste->setUsucreaId(0);
                                    $SaldoUsuonlineAjuste->setSaldoAnt(0);

                                    /* ajusta saldos y registra motivo e IP del usuario. */
                                    $SaldoUsuonlineAjuste->setObserv('Migración de saldos');
                                    if ($SaldoUsuonlineAjuste->getMotivoId() == "") {
                                        $SaldoUsuonlineAjuste->setMotivoId(0);
                                    }
                                    $dir_ip = explode(",", $_SERVER["HTTP_X_FORWARDED_FOR"])[0];

                                    $SaldoUsuonlineAjuste->setDirIp($dir_ip);

                                    /* Se establece mandante y tipo de saldo, luego se inserta un ajuste en MySQL. */
                                    $SaldoUsuonlineAjuste->setMandante($Usuario->mandante);
                                    $SaldoUsuonlineAjuste->setTipoSaldo($TypeBalance);

                                    $ajusteId = $SaldoUsuonlineAjusteMysql->insert($SaldoUsuonlineAjuste);


                                }


                                /* Crea un historial de usuario inicializando atributos correspondientes a un movimiento específico. */
                                $UsuarioHistorial = new UsuarioHistorial();
                                $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                                $UsuarioHistorial->setDescripcion('');
                                $UsuarioHistorial->setMovimiento($tipo);
                                $UsuarioHistorial->setUsucreaId(0);
                                $UsuarioHistorial->setUsumodifId(0);

                                /* Código para establecer y guardar un historial de usuario en una base de datos. */
                                $UsuarioHistorial->setTipo(15);
                                $UsuarioHistorial->setValor($Amount);
                                $UsuarioHistorial->setExternoId($ajusteId);

                                $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                                $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);


                                /* confirma una transacción en una base de datos, guardando los cambios realizados. */
                                $Transaction->commit();


                            }


                        } catch (Exception $e) {
                            /* Código PHP que captura excepciones sin imprimir errores, permitiendo manejar errores silenciosamente. */

                            //print_r($e);
                        }

                        //new table row for every record
                        echo "<tr>";

                        /* Itera sobre números y crea celdas de tabla para cada contenido de registro. */
                        foreach ($number as $k => $content) {
                            //new table cell for every field of the record
                            //echo "<td>" . $content . "</td>";
                        }
                    }

                    echo "</table>";
                }

            }
        } else {
            /* muestra un mensaje cuando no se selecciona ningún archivo. */

            echo "No file selected <br />";
        }
    }
    ?>

    <table width="600">
        <form action="" method="post" enctype="multipart/form-data">

            <tr>
                <td width="20%">Select file</td>
                <td width="80%"><input type="file" name="file" id="file"/></td>
            </tr>

            <tr>
                <td>Submit</td>
                <td><input type="submit" name="submit"/></td>
            </tr>

        </form>
    </table>
    <?php
}


/* Código que inicializa una respuesta exitosa sin errores ni mensajes. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];