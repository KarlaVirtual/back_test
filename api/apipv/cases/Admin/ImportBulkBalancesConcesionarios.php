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
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioBanco;
use Backend\dto\UsuarioConfig;
use Backend\dto\UsuarioConfiguracion;
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
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\UsuarioBancoMySqlDAO;
use Backend\mysql\UsuarioBannerMySqlDAO;
use Backend\mysql\UsuarioConfigMySqlDAO;
use Backend\mysql\UsuarioConfiguracionMySqlDAO;
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
 * Importa saldos en bloque para concesionarios.
 */

/* configura el entorno para mostrar errores y establece la codificación de caracteres. */
header("Content-type: text/html");
error_reporting(E_ALL);
ini_set("display_errors", "ON");
ini_set('default_charset', 'windows-1251');

$fichero_subido = '/home/home2/backend/api/apipv/cases/Admin/import.csv';

/* `exit();` finaliza la ejecución del script en PHP. */
exit();
if (isset($_POST["submit"])) {

    if (isset($_FILES["file"])) {

        //if there was an error uploading the file

        /* Verifica si hay errores al subir un archivo y muestra el código de error. */
        if ($_FILES["file"]["error"] > 0) {
            echo "Return Code: " . $_FILES["file"]["error"] . "<br />";

        } else {
            //Print file details
            /* muestra información sobre un archivo subido y verifica su existencia. */
            echo "Upload: " . $_FILES["file"]["name"] . "<br />";
            echo "Type: " . $_FILES["file"]["type"] . "<br />";
            echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
            echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";

            //if file already exists
            if (file_exists("upload/" . $_FILES["file"]["name"])) {
                echo $_FILES["file"]["name"] . " already exists. ";
            } else {
                /* mueve un archivo subido a un directorio específico y lo renombra. */


                //Store file in directory "upload" with the name of "uploaded_file.txt"
                $storagename = "uploaded_file.txt";
                move_uploaded_file($_FILES["file"]["tmp_name"], $fichero_subido);
                echo "Stored in: " . "upload/" . $_FILES["file"]["name"] . "<br />";
            }

            if (isset($storagename) && $file = fopen($fichero_subido, r)) {

                echo "File opened.<br />";


                /* lee la primera línea de un archivo CSV y cuenta los campos. */
                $firstline = fgets($file, 4096);
                //Gets the number of fields, in CSV-files the names of the fields are mostly given in the first line
                $num = strlen($firstline) - strlen(str_replace(";", "", $firstline));

                //save the different fields of the firstline in an array called fields
                $fields = array();

                /* lee un archivo CSV y almacena los registros en un array bidimensional. */
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


                /* Un bucle que recorre un array basado en un número definido. */
                for ($k = 0; $k != ($num + 1); $k++) {


                    // echo "<td>" . $fields[$k] . "</td>";
                }
                echo "</tr>";

                foreach ($dsatz as $key => $number) {

                    /* imprime un número y asigna valores de un arreglo a variables. */
                    print_r($number);

                    $k = 0;
                    $login = $number[$k];
                    $k++;
                    $saldodeposito = $number[$k];
                    $k++;

                    /* asigna un saldo de juego y define un género masculino. */
                    $saldojuego = $number[$k];
                    $k++;

                    $genero = 'M';
                    //$telefono_fijo = '';


                    try {

                        /* Se definen dos variables: '$mandante' como '8' y '$tipo' como 'E'. */
                        $mandante = '8';
                        $tipo = 'E';
                        if (($saldodeposito != '' && floatval($saldodeposito) > 0) || ($saldojuego != '' && floatval($saldojuego) > 0)) {


                            /* Se crea un objeto DAO y se obtiene una transacción; luego se inicializan Usuario y PuntoVenta. */
                            $CupoLogMySqlDAO = new CupoLogMySqlDAO();
                            $Transaction = $CupoLogMySqlDAO->getTransaction();


                            $Usuario = new Usuario("", $login, '0', $mandante);
                            $PuntoVenta = new PuntoVenta("", $Usuario->usuarioId);


                            /* Se crea un objeto 'Concesionario' y un 'PuntoVentaMySqlDAO' para transacciones. */
                            $Concesionario = new Concesionario($Usuario->usuarioId, '0');

                            $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO($Transaction);

                            if ($saldodeposito != '' && floatval($saldodeposito) > 0) {


                                /* Asigna 'R' o 'A' a $tipoCupo según el valor de $Type. */
                                $Type = '0';

                                if ($Type == 0) {
                                    $tipoCupo = 'R';
                                } else {
                                    $tipoCupo = 'A';

                                }


                                /* Se crea un registro de cupo con datos de usuario y saldo depositado. */
                                $CupoLog = new CupoLog();
                                $CupoLog->setUsuarioId($Usuario->usuarioId);
                                $CupoLog->setFechaCrea(date('Y-m-d H:i:s'));
                                $CupoLog->setTipoId($tipo);
                                $CupoLog->setValor(floatval($saldodeposito));
                                $CupoLog->setUsucreaId(0);

                                /* Registrando un cupo en la base de datos con saldo de depósito actualizado. */
                                $CupoLog->setMandante($Usuario->mandante);
                                $CupoLog->setTipocupoId($tipoCupo);
                                $CupoLog->setObservacion('');
                                $CupoLogMySqlDAO->insert($CupoLog);

                                $PuntoVenta->setBalanceCupoRecarga(floatval($saldodeposito));


                            }


                            if ($saldojuego != '' && floatval($saldojuego) > 0) {


                                /* Determina el valor de $tipoCupo según el valor de $Type. */
                                $Type = '1';

                                if ($Type == 0) {
                                    $tipoCupo = 'R';
                                } else {
                                    $tipoCupo = 'A';

                                }


                                /* Registro de acciones de usuario con datos como ID, fecha y saldo. */
                                $CupoLog = new CupoLog();
                                $CupoLog->setUsuarioId($Usuario->usuarioId);
                                $CupoLog->setFechaCrea(date('Y-m-d H:i:s'));
                                $CupoLog->setTipoId($tipo);
                                $CupoLog->setValor(floatval($saldojuego));
                                $CupoLog->setUsucreaId($userfrom);

                                /* Se registra un cupo y se actualiza el saldo del punto de venta. */
                                $CupoLog->setMandante(0);
                                $CupoLog->setTipocupoId($tipoCupo);
                                $CupoLog->setObservacion('');
                                $CupoLogMySqlDAO->insert($CupoLog);

                                $PuntoVenta->setBalanceCreditosBase(floatval($saldojuego));


                            }


                            /* Actualiza el registro de Punto de Venta y confirma la transacción en MySQL. */
                            $PuntoVentaMySqlDAO->update($PuntoVenta);

                            $Transaction->commit();


                        }


                    } catch (Exception $e) {
                        /* Captura excepciones en PHP y muestra información sobre el error ocurrido. */

                        print_r($e);
                    }

                    //new table row for every record
                    echo "<tr>";

                    /* itera sobre $number, generando celdas de tabla para cada contenido. */
                    foreach ($number as $k => $content) {
                        //new table cell for every field of the record
                        //echo "<td>" . $content . "</td>";
                    }
                }

                echo "</table>";
            }

        }
    } else {
        /* muestra un mensaje si no se selecciona un archivo. */

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
