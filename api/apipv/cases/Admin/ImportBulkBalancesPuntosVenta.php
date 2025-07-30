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
 * Procesa la carga de un archivo CSV y actualiza los balances de los puntos de venta.
 *
 * @param array $_POST Contiene los datos enviados por el formulario, incluyendo el archivo subido.
 * @param array $_FILES Contiene la información del archivo subido.
 * @param string $fichero_subido Ruta del archivo subido.
 *
 * @return void Actualiza los balances de los puntos de venta y genera una respuesta.
 */

/* Configura el encabezado de contenido, errores y ruta de archivo CSV para carga. */
header("Content-type: text/html");
error_reporting(E_ALL);
ini_set("display_errors", "ON");
ini_set('default_charset', 'windows-1251');

$fichero_subido = '/home/home2/backend/api/apipv/cases/Admin/import.csv';

if (isset($_POST["submit"])) {

    if (isset($_FILES["file"])) {

        //if there was an error uploading the file

        /* maneja errores en la carga de archivos en PHP. */
        if ($_FILES["file"]["error"] > 0) {
            echo "Return Code: " . $_FILES["file"]["error"] . "<br />";

        } else {
            //Print file details
            /* muestra detalles de un archivo subido y verifica si ya existe. */
            echo "Upload: " . $_FILES["file"]["name"] . "<br />";
            echo "Type: " . $_FILES["file"]["type"] . "<br />";
            echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
            echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";

            //if file already exists
            if (file_exists("upload/" . $_FILES["file"]["name"])) {
                echo $_FILES["file"]["name"] . " already exists. ";
            } else {
                /* mueve un archivo subido y lo guarda como "uploaded_file.txt". */


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

                /* lee un archivo CSV, separando registros por líneas y campos por "punto y coma". */
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


                /* La estructura recorre un arreglo hasta el índice definido por `$num`. */
                for ($k = 0; $k != ($num + 1); $k++) {


                    // echo "<td>" . $fields[$k] . "</td>";
                }
                echo "</tr>";

                foreach ($dsatz as $key => $number) {

                    /* Imprime el primer número y almacena el segundo en saldo de depósito. */
                    print_r($number);

                    $k = 0;
                    $login = $number[$k];
                    $k++;
                    $saldodeposito = $number[$k];
                    $k++;

                    /* Se asigna un saldo y se establece el género como masculino. */
                    $saldojuego = $number[$k];
                    $k++;

                    $genero = 'M';
                    //$telefono_fijo = '';


                    try {

                        /* Se definen variables: $mandante con valor '8' y $tipo con valor 'E'. */
                        $mandante = '8';
                        $tipo = 'E';
                        if (($saldodeposito != '' && floatval($saldodeposito) > 0) || ($saldojuego != '' && floatval($saldojuego) > 0)) {


                            /* Se crean instancias de DAO, Usuario, PuntoVenta y Concesionario con datos específicos. */
                            $CupoLogMySqlDAO = new CupoLogMySqlDAO();
                            $Transaction = $CupoLogMySqlDAO->getTransaction();

                            $Usuario = new Usuario("", $login, '0', $mandante);
                            $PuntoVenta = new PuntoVenta("", $Usuario->usuarioId);

                            $Concesionario = new Concesionario($Usuario->usuarioId, '0');


                            /* Se crea una instancia de PuntoVentaMySqlDAO utilizando la transacción proporcionada. */
                            $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO($Transaction);

                            if ($saldodeposito != '' && floatval($saldodeposito) > 0) {


                                /* asigna 'R' o 'A' a $tipoCupo según el valor de $Type. */
                                $Type = '0';

                                if ($Type == 0) {
                                    $tipoCupo = 'R';
                                } else {
                                    $tipoCupo = 'A';

                                }


                                /* Se crea un registro de log con información del usuario y depósito. */
                                $CupoLog = new CupoLog();
                                $CupoLog->setUsuarioId($Usuario->usuarioId);
                                $CupoLog->setFechaCrea(date('Y-m-d H:i:s'));
                                $CupoLog->setTipoId($tipo);
                                $CupoLog->setValor(floatval($saldodeposito));
                                $CupoLog->setUsucreaId(0);

                                /* Código que registra un cupo en la base de datos y actualiza saldo de punto de venta. */
                                $CupoLog->setMandante($Usuario->mandante);
                                $CupoLog->setTipocupoId($tipoCupo);
                                $CupoLog->setObservacion('');
                                $CupoLogMySqlDAO->insert($CupoLog);

                                $PuntoVenta->setBalanceCupoRecarga(floatval($saldodeposito));


                            }


                            if ($saldojuego != '' && floatval($saldojuego) > 0) {


                                /* Asigna 'R' o 'A' a $tipoCupo según el valor de $Type. */
                                $Type = '1';

                                if ($Type == 0) {
                                    $tipoCupo = 'R';
                                } else {
                                    $tipoCupo = 'A';

                                }


                                /* Crea un registro de log de cupo con información del usuario y saldo. */
                                $CupoLog = new CupoLog();
                                $CupoLog->setUsuarioId($Usuario->usuarioId);
                                $CupoLog->setFechaCrea(date('Y-m-d H:i:s'));
                                $CupoLog->setTipoId($tipo);
                                $CupoLog->setValor(floatval($saldojuego));
                                $CupoLog->setUsucreaId($userfrom);

                                /* establece valores y guarda registros en la base de datos relacionadas con Cupo. */
                                $CupoLog->setMandante(0);
                                $CupoLog->setTipocupoId($tipoCupo);
                                $CupoLog->setObservacion('');
                                $CupoLogMySqlDAO->insert($CupoLog);

                                $PuntoVenta->setBalanceCreditosBase(floatval($saldojuego));


                            }


                            /* Crea un historial de usuario con información del registro de un cupo. */
                            $UsuarioHistorial = new UsuarioHistorial();
                            $UsuarioHistorial->setUsuarioId($CupoLog->getUsuarioId());
                            $UsuarioHistorial->setDescripcion('');
                            $UsuarioHistorial->setMovimiento($CupoLog->getTipoId());
                            $UsuarioHistorial->setUsucreaId(0);
                            $UsuarioHistorial->setUsumodifId(0);

                            /* Se inserta un nuevo historial de usuario en la base de datos. */
                            $UsuarioHistorial->setTipo(60);
                            $UsuarioHistorial->setValor($CupoLog->getValor());
                            $UsuarioHistorial->setExternoId($CupoLog->getCupologId());

                            $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                            $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial, '1');


                            /* Actualiza un registro en la base de datos y confirma la transacción. */
                            $PuntoVentaMySqlDAO->update($PuntoVenta);

                            $Transaction->commit();


                        }


                    } catch (Exception $e) {
                        /* Bloque para capturar y mostrar errores en el manejo de excepciones en PHP. */

                        print_r($e);
                    }

                    //new table row for every record
                    echo "<tr>";

                    /* crea una celda de tabla para cada contenido en el arreglo `$number`. */
                    foreach ($number as $k => $content) {
                        //new table cell for every field of the record
                        //echo "<td>" . $content . "</td>";
                    }
                }

                echo "</table>";
            }

        }
    } else {
        /* Muestra un mensaje si no se ha seleccionado un archivo. */

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