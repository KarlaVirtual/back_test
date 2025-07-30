<?php

use Backend\dto\Banco;
use Backend\dto\BonoDetalle;
use Backend\dto\Categoria;
use Backend\dto\Clasificador;
use Backend\dto\Concesionario;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Consecutivo;
use Backend\dto\CuentaCobro;
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
 * Procesa un archivo CSV y almacena las entidades como puntos de venta
 *
 * @param array $param Arreglo que contiene información del archivo subido y otros parámetros necesarios.
 * @return void
 * @throws Exception Si ocurre un error durante la ejecución del script.
 */

/* configura la cabecera para HTML y muestra errores en PHP. */
header("Content-type: text/html");
error_reporting(E_ALL);
ini_set("display_errors", "ON");
ini_set('default_charset', 'windows-1251');

$fichero_subido = '/home/home2/backend/api/apipv/cases/Admin/import.csv';

/* La función `exit()` detiene la ejecución del script en programación. */
exit();

if (isset($_POST["submit"])) {

    if (isset($_FILES["file"])) {

        //if there was an error uploading the file

        /* verifica errores en la carga de archivos y los muestra. */
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
                /* Guarda un archivo subido como "uploaded_file.txt" en la carpeta "upload". */


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

                /* procesa un archivo CSV, separando registros y campos usando el delimitador ';'. */
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


                /* itera desde 0 hasta num, comentando la salida de celdas de tabla. */
                for ($k = 0; $k != ($num + 1); $k++) {


                    // echo "<td>" . $fields[$k] . "</td>";
                }
                echo "</tr>";

                foreach ($dsatz as $key => $number) {

                    /* imprime un array y asigna valores a variables desde él. */
                    print_r($number);

                    $k = 0;
                    $PlayerId = $number[$k];
                    $k++;
                    $celular = $number[$k];
                    $k++;

                    /* asigna valores de un array a variables sucesivas. */
                    $cedula = $number[$k];
                    $k++;
                    $nombre1 = $number[$k];
                    $k++;
                    $nombre2 = $number[$k];
                    $k++;
                    $apellido1 = $number[$k];
                    $k++;

                    /* Se extraen valores de un array y se asignan a variables específicas. */
                    $apellido2 = $number[$k];
                    $k++;
                    /*$apellido2=$number[$k];
                    $k++;*/
                    $fecha_nacim = $number[$k];
                    $k++;
                    $email = $number[$k];
                    $k++;

                    /* asigna valores de un array a variables consecutivas. */
                    $pais = $number[$k];
                    $k++;
                    $departamento = $number[$k];
                    $k++;
                    $ciudad = $number[$k];
                    $k++;
                    $direccion = $number[$k];
                    $k++;

                    /* Asigna valores de un array a variables, avanzando en cada paso. */
                    $codigo_postal = $number[$k];
                    $k++;
                    $estado = $number[$k];
                    $k++;
                    $registro_ip = $number[$k];
                    $k++;
                    $registro_fecha = $number[$k];
                    $k++;

                    /* Asignación de variables a partir de un array en secuencia específica. */
                    $telefono_fijo = $number[$k];
                    $k++;
                    $tipo_documento = $number[$k];
                    $k++;
                    $login = $number[$k];
                    $k++;
                    $subconcesionario = $number[$k];
                    $k++;


                    /* Se asigna el valor 'M' a la variable $genero, representando género masculino. */
                    $genero = 'M';
                    //$telefono_fijo = '';


                    try {
                        if (($cedula != "" && $celular != "") || true) {


                            /* Se asignan valores de usuario y parámetros para un registro específico en el sistema. */
                            $user_info = $json->params->user_info;
                            $type_register = 0;

                            //Mandante
                            $site_id = '8';
                            $Partner = '8';


                            //Pais de residencia

                            /* Se inicializan variables para país y idioma en un objeto Mandante. */
                            $countryResident_id = $pais;

                            //Idioma
                            $lang_code = 'ES';
                            $idioma = strtoupper($lang_code);


                            $Mandante = new Mandante($site_id);

                            /* Se crean objetos para un país y su moneda, definiendo valores predeterminados. */
                            $Pais = new Pais($pais);
                            $PaisMoneda = new PaisMoneda($pais);

                            $moneda_default = $PaisMoneda->moneda;

                            $estadoUsuarioDefault = 'I';


                            /* Creación de un clasificador y estado de un usuario basado en valores de un mandante. */
                            $Clasificador = new Clasificador("", "REGISTERACTIVATION");

                            try {
                                $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $Clasificador->getClasificadorId(), $Pais->paisId, 'A');

                                $estadoUsuarioDefault = (intval($MandanteDetalle->getValor()) == 1) ? "A" : "I";


                            } catch (Exception $e) {
                                /* Manejo de excepciones que verifica el código de error específico 34. */


                                if ($e->getCode() == 34) {
                                } else {
                                }
                            }


                            /* asigna valores de variables relacionadas con una dirección y datos personales. */
                            $address = $direccion;
                            $birth_date = $fecha_nacim;


                            $department_id = $departamento;
                            $docnumber = $cedula;

                            /* Código asigna un ID de documento y inicializa variables para un correo y fecha. */
                            $doctype_id = 1;

                            $email = $email;

                            $expedition_day = '00';
                            $expedition_month = '00';

                            /* Asignación de variables para almacenar información personal y de género en PHP. */
                            $expedition_year = '0000';
                            $first_name = $nombre1;
                            $middle_name = $nombre2;
                            $last_name = $apellido1;
                            $second_last_name = $apellido2;

                            $gender = $genero;


                            /* Asigna valores a variables para un número de teléfono y límites de depósito. */
                            $landline_number = $telefono_fijo;
                            $language = 'ES';


                            $limit_deposit_day = 0;
                            $limit_deposit_month = 0;

                            /* inicializa variables para límite de depósito, nacionalidad, contraseña y teléfono. */
                            $limit_deposit_week = 0;

                            $nationality_id = $pais;

                            $password = 'raphiw-mirfu4-jopweB';
                            $phone = $celular;


                            /* Variables asignadas para almacenar identificadores de ciudad, país, departamento y código postal. */
                            $city_id = $ciudad;

                            $countrybirth_id = 0;
                            $departmentbirth_id = 0;
                            $citybirth_id = 0;
                            $cp = $codigo_postal;


                            /* Se generan valores iniciales y se crea un nombre junto con una clave de ticket. */
                            $expdept_id = 0;
                            $expcity_id = 0;

                            $nombre = $first_name . " " . $middle_name . " " . $last_name . " " . $second_last_name;
                            $ConfigurationEnvironment = new ConfigurationEnvironment();
                            $clave_activa = $ConfigurationEnvironment->GenerarClaveTicket(15);


                            /* asigna valores "S" o "N" dependiendo de condiciones previas. */
                            $CanReceipt = ($CanReceipt == "S") ? "S" : "N";
                            $CanDeposit = ($CanDeposit == "S") ? "S" : "N";
                            $CanActivateRegister = ($CanActivateRegister == "S") ? "S" : "N";
                            $Lockedsales = ($Lockedsales == "S") ? "S" : "N";
                            $Pinagent = ($Pinagent == "S") ? "S" : "N";

                            $Pinagent = 'N';


                            /* Código para incrementar un consecutivo de usuario y preparar acceso a base de datos. */
                            $Consecutivo = new Consecutivo("", "USU", "");

                            $consecutivo_usuario = $Consecutivo->numero;

                            $consecutivo_usuario++;

                            $ConsecutivoMySqlDAO = new ConsecutivoMySqlDAO();


                            /* Actualiza un objeto "Consecutivo" en la base de datos y gestiona la transacción. */
                            $Consecutivo->setNumero($consecutivo_usuario);


                            $ConsecutivoMySqlDAO->update($Consecutivo);

                            $ConsecutivoMySqlDAO->getTransaction()->commit();


                            /* Se crean instancias de las clases PuntoVenta y Usuario, asignando un ID al usuario. */
                            $PuntoVenta = new PuntoVenta();


                            $Usuario = new Usuario();


                            $Usuario->usuarioId = $consecutivo_usuario;


                            /* Asigna valores a las propiedades de un objeto Usuario en PHP. */
                            $Usuario->login = $login;

                            $Usuario->nombre = $nombre1 . ' ' . $nombre2 . ' ' . $apellido1 . ' ' . $apellido2;

                            $Usuario->estado = 'A';

                            $Usuario->fechaUlt = date('Y-m-d H:i:s');


                            /* Código inicializa propiedades de un objeto usuario, estableciendo estado y valores predeterminados. */
                            $Usuario->claveTv = '';

                            $Usuario->estadoAnt = 'I';

                            $Usuario->intentos = 0;

                            $Usuario->estadoEsp = 'A';


                            /* Código que inicializa propiedades de un objeto Usuario con valores específicos. */
                            $Usuario->observ = '';

                            $Usuario->dirIp = '';

                            $Usuario->eliminado = 'N';

                            $Usuario->mandante = $Partner;


                            /* Se asignan valores iniciales a propiedades del usuario y se genera un token. */
                            $Usuario->usucreaId = '0';

                            $Usuario->usumodifId = '0';

                            $Usuario->claveCasino = '';
                            $token_itainment = (new ConfigurationEnvironment())->GenerarClaveTicket2(12);


                            /* Asignación de propiedades a un objeto `$Usuario` en programación orientada a objetos. */
                            $Usuario->tokenItainment = $token_itainment;

                            $Usuario->fechaClave = '';

                            $Usuario->retirado = '';

                            $Usuario->fechaRetiro = '';


                            /* Código que inicializa propiedades de un objeto Usuario con valores predeterminados. */
                            $Usuario->horaRetiro = '';

                            $Usuario->usuretiroId = '0';

                            $Usuario->bloqueoVentas = 'N';

                            $Usuario->infoEquipo = '';


                            /* Inicializa propiedades de un objeto usuario para un sistema de gestión de jugadores. */
                            $Usuario->estadoJugador = 'AC';

                            $Usuario->tokenCasino = '';

                            $Usuario->sponsorId = 0;

                            $Usuario->verifCorreo = 'N';


                            /* Asignación de valores a propiedades de un objeto Usuario en programación. */
                            $Usuario->paisId = $pais;

                            $Usuario->moneda = $moneda_default;

                            $Usuario->idioma = $idioma;

                            $Usuario->permiteActivareg = $CanActivateRegister;


                            /* asigna valores a propiedades de un objeto "Usuario". */
                            $Usuario->test = 'N';

                            $Usuario->tiempoLimitedeposito = '0';

                            $Usuario->tiempoAutoexclusion = '0';

                            $Usuario->cambiosAprobacion = 'S';


                            /* Asigna propiedades a un objeto usuario, incluyendo zona horaria y fecha de creación. */
                            $Usuario->timezone = '-5';

                            $Usuario->puntoventaId = $consecutivo_usuario;

                            $Usuario->fechaCrea = date('Y-m-d H:i:s');

                            $Usuario->origen = 0;


                            /* asigna valores a atributos del objeto $Usuario relacionados con fechas y validaciones. */
                            $Usuario->fechaActualizacion = $Usuario->fechaCrea;
                            $Usuario->documentoValidado = "A";
                            $Usuario->fechaDocvalido = $Usuario->fechaCrea;
                            $Usuario->usuDocvalido = 0;


                            $Usuario->estadoValida = 'N';

                            /* Asignación de valores a propiedades del objeto Usuario relacionadas con validaciones y contingencias. */
                            $Usuario->usuvalidaId = 0;
                            $Usuario->fechaValida = date('Y-m-d H:i:s');
                            $Usuario->contingencia = 'I';
                            $Usuario->contingenciaDeportes = 'I';
                            $Usuario->contingenciaCasino = 'I';
                            $Usuario->contingenciaCasvivo = 'I';

                            /* Asignación de valores a propiedades del objeto Usuario, incluyendo restricciones y ubicación. */
                            $Usuario->contingenciaVirtuales = 'I';
                            $Usuario->contingenciaPoker = 'I';
                            $Usuario->restriccionIp = 'I';
                            $Usuario->ubicacionLongitud = '';
                            $Usuario->ubicacionLatitud = '';
                            $Usuario->usuarioIp = $IP;

                            /* Asignación de tokens y configuración de usuario en un sistema. */
                            $Usuario->tokenGoogle = "I";
                            $Usuario->tokenLocal = "I";
                            $Usuario->saltGoogle = '';

                            $UsuarioConfig = new UsuarioConfig();
                            $UsuarioConfig->permiteRecarga = $CanDeposit;

                            /* Se configuran propiedades de un usuario, estableciendo pin, recibo y tipo. */
                            $UsuarioConfig->pinagent = $Pinagent;
                            $UsuarioConfig->reciboCaja = $CanReceipt;
                            $UsuarioConfig->mandante = $Partner;
                            $UsuarioConfig->usuarioId = $consecutivo_usuario;

                            $tipoUsuario = 'PUNTOVENTA';


                            /* Se crean instancias de Concesionario y UsuarioPerfil, asignando identificadores específicos. */
                            $Concesionario = new Concesionario();

                            $UsuarioPerfil = new UsuarioPerfil();
                            $UsuarioPerfil->usuarioId = $consecutivo_usuario;

                            $UsuarioPerfil->perfilId = $tipoUsuario;

                            /* Configura perfil de usuario y asigna concesionario con ID específico. */
                            $UsuarioPerfil->mandante = $Partner;
                            $UsuarioPerfil->pais = 'S';
                            $UsuarioPerfil->global = 'N';


                            // $ConcesionarioU = new Concesionario($_SESSION["usuario"], 0);

                            $Concesionario->setUsupadreId(73737);

                            /* Se configuran identificadores y porcentajes en un objeto Concesionario. */
                            $Concesionario->setUsuhijoId($consecutivo_usuario);
                            $Concesionario->setusupadre2Id($subconcesionario);
                            $Concesionario->setusupadre3Id(0);
                            $Concesionario->setusupadre4Id(0);
                            $Concesionario->setPorcenhijo(0);
                            $Concesionario->setPorcenpadre1(0);

                            /* establece valores predeterminados en varias propiedades de un objeto "Concesionario". */
                            $Concesionario->setPorcenpadre2(0);
                            $Concesionario->setPorcenpadre3(0);
                            $Concesionario->setPorcenpadre4(0);
                            $Concesionario->setProdinternoId(0);
                            $Concesionario->setMandante(0);
                            $Concesionario->setUsucreaId(0);

                            /* establece valores y crea una instancia de UsuarioPremiomax con usuarioId. */
                            $Concesionario->setUsumodifId(0);
                            $Concesionario->setEstado("A");


                            $UsuarioPremiomax = new UsuarioPremiomax();


                            $UsuarioPremiomax->usuarioId = $consecutivo_usuario;


                            /* Se inicializan variables de un objeto relacionado con premios de usuario. */
                            $UsuarioPremiomax->premioMax = 0;

                            $UsuarioPremiomax->usumodifId = 0;

                            $UsuarioPremiomax->fechaModif = "";

                            $UsuarioPremiomax->cantLineas = 0;


                            /* inicializa variables de premio y apuesta en un objeto de usuario. */
                            $UsuarioPremiomax->premioMax1 = 0;

                            $UsuarioPremiomax->premioMax2 = 0;

                            $UsuarioPremiomax->premioMax3 = 0;

                            $UsuarioPremiomax->apuestaMin = 0;


                            /* Se inicializan variables en un objeto de usuario para gestionar premios y parámetros. */
                            $UsuarioPremiomax->valorDirecto = 0;

                            $UsuarioPremiomax->premioDirecto = 0;

                            $UsuarioPremiomax->mandante = $Partner;

                            $UsuarioPremiomax->optimizarParrilla = "N";


                            /* Se están inicializando propiedades de un objeto UsuarioPremiomax con valores vacíos o cero. */
                            $UsuarioPremiomax->textoOp1 = "";

                            $UsuarioPremiomax->textoOp2 = "";

                            $UsuarioPremiomax->urlOp2 = "";

                            $UsuarioPremiomax->textoOp3 = 0;


                            /* inicializa propiedades de un objeto y crea una instancia de PuntoVenta. */
                            $UsuarioPremiomax->urlOp3 = 0;

                            $UsuarioPremiomax->valorEvento = 0;

                            $UsuarioPremiomax->valorDiario = 0;


                            $PuntoVenta = new PuntoVenta();

                            /* Asigna valores a propiedades del objeto PuntoVenta usando variables de contacto y ubicación. */
                            $PuntoVenta->descripcion = $nombre1 . ' ' . $nombre2 . ' ' . $apellido1 . ' ' . $apellido2;
                            $PuntoVenta->nombreContacto = $nombre1 . ' ' . $nombre2 . ' ' . $apellido1 . ' ' . $apellido2;
                            $PuntoVenta->ciudadId = $ciudad;
                            $PuntoVenta->direccion = $direccion;
                            $PuntoVenta->barrio = '';
                            $PuntoVenta->telefono = $celular;

                            /* Asigna valores a las propiedades del objeto $PuntoVenta en PHP. */
                            $PuntoVenta->email = $email;
                            $PuntoVenta->periodicidadId = 0;
                            $PuntoVenta->clasificador1Id = 0;
                            $PuntoVenta->clasificador2Id = 0;
                            $PuntoVenta->clasificador3Id = 0;
                            $PuntoVenta->valorRecarga = 0;

                            /* Inicializa propiedades de un objeto "PuntoVenta" con valores predeterminados. */
                            $PuntoVenta->valorCupo = '0';
                            $PuntoVenta->valorCupo2 = '0';
                            $PuntoVenta->porcenComision = '0';
                            $PuntoVenta->porcenComision2 = '0';
                            $PuntoVenta->estado = 'A';
                            $PuntoVenta->usuarioId = '0';

                            /* Se asignan valores a las propiedades del objeto PuntoVenta. */
                            $PuntoVenta->mandante = $Partner;
                            $PuntoVenta->moneda = $moneda_default;
                            //$PuntoVenta->moneda = $CurrencyId->Id;
                            $PuntoVenta->idioma = $idioma;
                            $PuntoVenta->cupoRecarga = 0;
                            $PuntoVenta->creditosBase = 0;

                            /* Se inicializan variables de créditos y se crea una instancia de UsuarioMySqlDAO. */
                            $PuntoVenta->creditos = 0;
                            $PuntoVenta->creditosAnt = 0;
                            $PuntoVenta->creditosBaseAnt = 0;
                            $PuntoVenta->usuarioId = $consecutivo_usuario;


                            $UsuarioMySqlDAO = new UsuarioMySqlDAO();


                            /* Se insertan datos de usuario y se inicializan configuraciones y concesionarios relacionados. */
                            $UsuarioMySqlDAO->insert($Usuario);


                            $UsuarioConfigMySqlDAO = new UsuarioConfigMySqlDAO($UsuarioMySqlDAO->getTransaction());

                            $ConcesionarioMySqlDAO = new ConcesionarioMySqlDAO($UsuarioMySqlDAO->getTransaction());


                            /* Inserta registros en base de datos utilizando transacciones para garantizar consistencia. */
                            $ConcesionarioMySqlDAO->insert($Concesionario);

                            $UsuarioPerfilMySqlDAO = new UsuarioPerfilMySqlDAO($UsuarioMySqlDAO->getTransaction());

                            $UsuarioPerfilMySqlDAO->insert($UsuarioPerfil);

                            $UsuarioPremiomaxMySqlDAO = new UsuarioPremiomaxMySqlDAO($UsuarioMySqlDAO->getTransaction());


                            /* Inserta datos de usuario y punto de venta en la base de datos usando DAO. */
                            $UsuarioPremiomaxMySqlDAO->insert($UsuarioPremiomax);

                            $UsuarioConfigMySqlDAO->insert($UsuarioConfig);

                            $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO($UsuarioMySqlDAO->getTransaction());
                            $PuntoVentaMySqlDAO->insert($PuntoVenta);


                            /* Confirma transacción y actualiza la contraseña del usuario en MySQL. */
                            $UsuarioMySqlDAO->getTransaction()->commit();


                            $UsuarioMySqlDAO->updateClave($Usuario, $password);


                        }

                    } catch (Exception $e) {
                        /* Captura excepciones y muestra su información utilizando `print_r`. */

                        print_r($e);
                    }

                    //new table row for every record
                    echo "<tr>";

                    /* itera sobre elementos, pero no muestra contenido en la tabla. */
                    foreach ($number as $k => $content) {
                        //new table cell for every field of the record
                        //echo "<td>" . $content . "</td>";
                    }
                }

                echo "</table>";
            }

        }
    } else {
        /* muestra un mensaje cuando no se ha seleccionado un archivo. */

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