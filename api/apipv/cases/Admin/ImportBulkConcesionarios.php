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
 * Procesa la importación de concesionarios desde un archivo CSV.
 *
 * @param array $params Parámetros de configuración y datos necesarios para la importación.
 * @return array $response Respuesta con el estado de la operación, incluyendo errores y mensajes.
 * @throws Exception Si ocurre un error durante la importación de datos.
 */

/* Configura el entorno PHP para mostrar errores y establece la codificación de caracteres. */
header("Content-type: text/html");
error_reporting(E_ALL);
ini_set("display_errors", "ON");
ini_set('default_charset', 'windows-1251');

$fichero_subido = '/home/home2/backend/api/apipv/cases/Admin/import.csv';


/* La función "exit()" detiene la ejecución del script actual en programación. */
exit();
if (isset($_POST["submit"])) {

    if (isset($_FILES["file"])) {

        //if there was an error uploading the file

        /* Verifica errores en la carga de un archivo y muestra el código de error. */
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
                /* almacena un archivo subido como "uploaded_file.txt" en la carpeta "upload". */


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

                /* procesa un archivo CSV, separando registros y campos por punto y coma. */
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

                /* imprime una tabla HTML a partir de un conjunto de datos. */
                print_r($dsatz);

                echo "<table>";
                echo "<tr>";


                for ($k = 0; $k != ($num + 1); $k++) {


                    // echo "<td>" . $fields[$k] . "</td>";
                }
                echo "</tr>";

                foreach ($dsatz as $key => $number) {

                    /* imprime un arreglo y asigna valores a variables desde el mismo. */
                    print_r($number);

                    $k = 0;
                    $PlayerId = $number[$k];
                    $k++;
                    $celular = $number[$k];
                    $k++;

                    /* asigna valores de un arreglo a variables personales sucesivas. */
                    $cedula = $number[$k];
                    $k++;
                    $nombre1 = $number[$k];
                    $k++;
                    $nombre2 = $number[$k];
                    $k++;
                    $apellido1 = $number[$k];
                    $k++;

                    /* Asigna valores a variables desde un array, incrementando el índice en cada paso. */
                    $apellido2 = $number[$k];
                    $k++;
                    /*$apellido2=$number[$k];
                    $k++;*/
                    $fecha_nacim = $number[$k];
                    $k++;
                    $email = $number[$k];
                    $k++;

                    /* Extrae y asigna valores de un arreglo a variables de ubicación. */
                    $pais = $number[$k];
                    $k++;
                    $departamento = $number[$k];
                    $k++;
                    $ciudad = $number[$k];
                    $k++;
                    $direccion = $number[$k];
                    $k++;

                    /* asigna valores de un array a variables específicas incrementando un índice. */
                    $codigo_postal = $number[$k];
                    $k++;
                    $estado = $number[$k];
                    $k++;
                    $registro_ip = $number[$k];
                    $k++;
                    $registro_fecha = $number[$k];
                    $k++;

                    /* Asignación de variables a partir de un array secuencial de datos. */
                    $telefono_fijo = $number[$k];
                    $k++;
                    $tipo_documento = $number[$k];
                    $k++;
                    $login = $number[$k];
                    $k++;
                    $genero = 'M';
                    //$telefono_fijo = '';


                    try {
                        if (($cedula != "" && $celular != "") || true) {


                            /* extrae información del usuario y define parámetros para un registro. */
                            $user_info = $json->params->user_info;
                            $type_register = 0;

                            //Mandante
                            $site_id = '14';
                            $Partner = '14';


                            //Pais de residencia

                            /* asigna variables para país e idioma, y crea una instancia de Mandante. */
                            $countryResident_id = $pais;

                            //Idioma
                            $lang_code = 'ES';
                            $idioma = strtoupper($lang_code);


                            $Mandante = new Mandante($site_id);

                            /* Se inicializan objetos para país y moneda, y se establece estado de usuario por defecto. */
                            $Pais = new Pais($pais);
                            $PaisMoneda = new PaisMoneda($pais);

                            $moneda_default = $PaisMoneda->moneda;

                            $estadoUsuarioDefault = 'I';


                            /* Se crea un clasificador y se inicializa un detalle de mandante según condiciones. */
                            $Clasificador = new Clasificador("", "REGISTERACTIVATION");

                            try {
                                $MandanteDetalle = new MandanteDetalle("", $Mandante->mandante, $Clasificador->getClasificadorId(), $Pais->paisId, 'A');

                                $estadoUsuarioDefault = (intval($MandanteDetalle->getValor()) == 1) ? "A" : "I";


                            } catch (Exception $e) {
                                /* Manejo de excepciones en PHP, verificando un código específico en el error. */


                                if ($e->getCode() == 34) {
                                } else {
                                }
                            }


                            /* Asignación de variables para dirección, fecha de nacimiento y documento de identificación. */
                            $address = $direccion;
                            $birth_date = $fecha_nacim;


                            $department_id = $departamento;
                            $docnumber = $cedula;

                            /* Asignación de variables para un documento, correo y fecha de Expedición en PHP. */
                            $doctype_id = 1;

                            $email = $email;

                            $expedition_day = '00';
                            $expedition_month = '00';

                            /* asigna valores a variables relacionadas con una expedición y datos personales. */
                            $expedition_year = '0000';
                            $first_name = $nombre1;
                            $middle_name = $nombre2;
                            $last_name = $apellido1;
                            $second_last_name = $apellido2;

                            $gender = $genero;


                            /* Asignación de variables para número telefónico y límites de depósito en español. */
                            $landline_number = $telefono_fijo;
                            $language = 'ES';


                            $limit_deposit_day = 0;
                            $limit_deposit_month = 0;

                            /* inicializa límite de depósito y configura variables de nacionalidad, contraseña y teléfono. */
                            $limit_deposit_week = 0;

                            $nationality_id = $pais;

                            $password = 'raphiw-mirfu4-jopweB';
                            $phone = $celular;


                            /* Asigna valores a variables relacionadas con ubicación y código postal. */
                            $city_id = $ciudad;

                            $countrybirth_id = 0;
                            $departmentbirth_id = 0;
                            $citybirth_id = 0;
                            $cp = $codigo_postal;


                            /* genera un nombre completo y una clave de ticket aleatoria. */
                            $expdept_id = 0;
                            $expcity_id = 0;

                            $nombre = $first_name . " " . $middle_name . " " . $last_name . " " . $second_last_name;
                            $ConfigurationEnvironment = new ConfigurationEnvironment();
                            $clave_activa = $ConfigurationEnvironment->GenerarClaveTicket(15);


                            /* valida y asigna valores "S" o "N" a variables. */
                            $CanReceipt = ($CanReceipt == "S") ? "S" : "N";
                            $CanDeposit = ($CanDeposit == "S") ? "S" : "N";
                            $CanActivateRegister = ($CanActivateRegister == "S") ? "S" : "N";
                            $Lockedsales = ($Lockedsales == "S") ? "S" : "N";
                            $Pinagent = ($Pinagent == "S") ? "S" : "N";

                            $Pinagent = 'N';

                            /*$Consecutivo = new Consecutivo("", "USU", "");

                            $consecutivo_usuario = $Consecutivo->numero;

                            $consecutivo_usuario++;

                            $ConsecutivoMySqlDAO = new ConsecutivoMySqlDAO();

                            $Consecutivo->setNumero($consecutivo_usuario);


                            $ConsecutivoMySqlDAO->update($Consecutivo);

                            $ConsecutivoMySqlDAO->getTransaction()->commit();*/


                            /* Creación de instancias de PuntoVenta y Usuario, asignando el valor de login. */
                            $PuntoVenta = new PuntoVenta();


                            $Usuario = new Usuario();


                            //$Usuario->usuarioId = $consecutivo_usuario;

                            $Usuario->login = $login;


                            /* Asigna valores a propiedades de un objeto Usuario y establece fecha y estado. */
                            $Usuario->nombre = $nombre1 . ' ' . $nombre2 . ' ' . $apellido1 . ' ' . $apellido2;

                            $Usuario->estado = 'A';

                            $Usuario->fechaUlt = date('Y-m-d H:i:s');

                            $Usuario->claveTv = '';


                            /* Se inicializan propiedades de un objeto Usuario, configurando estado, intentos y observaciones. */
                            $Usuario->estadoAnt = 'I';

                            $Usuario->intentos = 0;

                            $Usuario->estadoEsp = 'A';

                            $Usuario->observ = '';


                            /* Se asignan valores a propiedades del objeto $Usuario. */
                            $Usuario->dirIp = '';

                            $Usuario->eliminado = 'N';

                            $Usuario->mandante = $Partner;

                            $Usuario->usucreaId = '0';


                            /* Asignación de valores a propiedades de un objeto Usuario y generación de un token. */
                            $Usuario->usumodifId = '0';

                            $Usuario->claveCasino = '';
                            $token_itainment = (new ConfigurationEnvironment())->GenerarClaveTicket2(12);

                            $Usuario->tokenItainment = $token_itainment;


                            /* Inicializa propiedades relacionadas con el usuario, como fechas y estado de retiro. */
                            $Usuario->fechaClave = '';

                            $Usuario->retirado = '';

                            $Usuario->fechaRetiro = '';

                            $Usuario->horaRetiro = '';


                            /* Código asigna valores a propiedades de un objeto "Usuario". */
                            $Usuario->usuretiroId = '0';

                            $Usuario->bloqueoVentas = 'N';

                            $Usuario->infoEquipo = '';

                            $Usuario->estadoJugador = 'AC';


                            /* Se inicializan propiedades del objeto Usuario, incluyendo token, patrocinador, verificación de correo y país. */
                            $Usuario->tokenCasino = '';

                            $Usuario->sponsorId = 0;

                            $Usuario->verifCorreo = 'N';


                            $Usuario->paisId = $pais;


                            /* Asignación de propiedades en un objeto Usuario, configurando moneda, idioma y permisos. */
                            $Usuario->moneda = $moneda_default;

                            $Usuario->idioma = $idioma;

                            $Usuario->permiteActivareg = $CanActivateRegister;

                            $Usuario->test = 'N';


                            /* configura propiedades de un objeto Usuario relacionadas con tiempo y aprobación. */
                            $Usuario->tiempoLimitedeposito = '0';

                            $Usuario->tiempoAutoexclusion = '0';

                            $Usuario->cambiosAprobacion = 'S';

                            $Usuario->timezone = '-5';


                            /* Inicializa propiedades del objeto Usuario con valores predeterminados y fecha actual. */
                            $Usuario->puntoventaId = '0';

                            $Usuario->fechaCrea = date('Y-m-d H:i:s');

                            $Usuario->origen = 0;

                            $Usuario->fechaActualizacion = $Usuario->fechaCrea;

                            /* Se asignan valores para la validación de documentos de un usuario. */
                            $Usuario->documentoValidado = "A";
                            $Usuario->fechaDocvalido = $Usuario->fechaCrea;
                            $Usuario->usuDocvalido = 0;


                            $Usuario->estadoValida = 'N';

                            /* Se inicializan propiedades de un objeto Usuario con valores específicos. */
                            $Usuario->usuvalidaId = 0;
                            $Usuario->fechaValida = date('Y-m-d H:i:s');
                            $Usuario->contingencia = 'I';
                            $Usuario->contingenciaDeportes = 'I';
                            $Usuario->contingenciaCasino = 'I';
                            $Usuario->contingenciaCasvivo = 'I';

                            /* Asignación de valores y configuración de propiedades del objeto Usuario. */
                            $Usuario->contingenciaVirtuales = 'I';
                            $Usuario->contingenciaPoker = 'I';
                            $Usuario->restriccionIp = 'I';
                            $Usuario->ubicacionLongitud = '';
                            $Usuario->ubicacionLatitud = '';
                            $Usuario->usuarioIp = $IP;

                            /* Se asignan tokens y se inserta un usuario en la base de datos MySQL. */
                            $Usuario->tokenGoogle = "I";
                            $Usuario->tokenLocal = "I";
                            $Usuario->saltGoogle = '';

                            $UsuarioMySqlDAO = new UsuarioMySqlDAO();

                            $UsuarioMySqlDAO->insert($Usuario);


                            /* asigna configuraciones al usuario, incluyendo permisos y un PIN. */
                            $consecutivo_usuario = $Usuario->usuarioId;


                            $UsuarioConfig = new UsuarioConfig();
                            $UsuarioConfig->permiteRecarga = $CanDeposit;
                            $UsuarioConfig->pinagent = $Pinagent;

                            /* Asignación de propiedades a un objeto de configuración de usuario y creación de concesionario. */
                            $UsuarioConfig->reciboCaja = $CanReceipt;
                            $UsuarioConfig->mandante = $Partner;
                            $UsuarioConfig->usuarioId = $consecutivo_usuario;

                            $tipoUsuario = 'CONCESIONARIO2';

                            $Concesionario = new Concesionario();


                            /* Se crea un nuevo perfil de usuario con información específica. */
                            $UsuarioPerfil = new UsuarioPerfil();
                            $UsuarioPerfil->usuarioId = $consecutivo_usuario;

                            $UsuarioPerfil->perfilId = $tipoUsuario;
                            $UsuarioPerfil->mandante = $Partner;
                            $UsuarioPerfil->pais = 'S';

                            /* asigna un valor y establece un identificador para un concesionario. */
                            $UsuarioPerfil->global = 'N';


                            // $ConcesionarioU = new Concesionario($_SESSION["usuario"], 0);

                            $Concesionario->setUsupadreId(73737);

                            /* establece propiedades relacionadas con un concesionario y sus usuarios. */
                            $Concesionario->setUsuhijoId($consecutivo_usuario);
                            $Concesionario->setusupadre2Id(0);
                            $Concesionario->setusupadre3Id(0);
                            $Concesionario->setusupadre4Id(0);
                            $Concesionario->setPorcenhijo(0);
                            $Concesionario->setPorcenpadre1(0);

                            /* inicializa propiedades del objeto "Concesionario" con valores cero. */
                            $Concesionario->setPorcenpadre2(0);
                            $Concesionario->setPorcenpadre3(0);
                            $Concesionario->setPorcenpadre4(0);
                            $Concesionario->setProdinternoId(0);
                            $Concesionario->setMandante(0);
                            $Concesionario->setUsucreaId(0);

                            /* Se asignan valores a un concesionario y se crea un nuevo usuario. */
                            $Concesionario->setUsumodifId(0);
                            $Concesionario->setEstado("A");


                            $UsuarioPremiomax = new UsuarioPremiomax();


                            $UsuarioPremiomax->usuarioId = $consecutivo_usuario;


                            /* Inicializa propiedades de un objeto relacionado con premios y modificaciones de usuario. */
                            $UsuarioPremiomax->premioMax = 0;

                            $UsuarioPremiomax->usumodifId = 0;

                            $UsuarioPremiomax->fechaModif = "";

                            $UsuarioPremiomax->cantLineas = 0;


                            /* Se inicializan variables de un objeto relacionado a premios y apuestas. */
                            $UsuarioPremiomax->premioMax1 = 0;

                            $UsuarioPremiomax->premioMax2 = 0;

                            $UsuarioPremiomax->premioMax3 = 0;

                            $UsuarioPremiomax->apuestaMin = 0;


                            /* Inicializa atributos de un objeto UsuarioPremiomax con valores predeterminados. */
                            $UsuarioPremiomax->valorDirecto = 0;

                            $UsuarioPremiomax->premioDirecto = 0;

                            $UsuarioPremiomax->mandante = $Partner;

                            $UsuarioPremiomax->optimizarParrilla = "N";


                            /* Inicializa propiedades de un objeto con valores vacíos y cero. */
                            $UsuarioPremiomax->textoOp1 = "";

                            $UsuarioPremiomax->textoOp2 = "";

                            $UsuarioPremiomax->urlOp2 = "";

                            $UsuarioPremiomax->textoOp3 = 0;


                            /* Se inicializan valores de un objeto UsuarioPremiomax y se crea un nuevo PuntoVenta. */
                            $UsuarioPremiomax->urlOp3 = 0;

                            $UsuarioPremiomax->valorEvento = 0;

                            $UsuarioPremiomax->valorDiario = 0;


                            $PuntoVenta = new PuntoVenta();

                            /* Assigna valores de contacto y ubicación a un objeto PuntoVenta. */
                            $PuntoVenta->descripcion = $nombre1 . ' ' . $nombre2 . ' ' . $apellido1 . ' ' . $apellido2;
                            $PuntoVenta->nombreContacto = $nombre1 . ' ' . $nombre2 . ' ' . $apellido1 . ' ' . $apellido2;
                            $PuntoVenta->ciudadId = $ciudad;
                            $PuntoVenta->direccion = $direccion;
                            $PuntoVenta->barrio = '';
                            $PuntoVenta->telefono = $celular;

                            /* Asignación de propiedades a un objeto PuntoVenta con valores predeterminados. */
                            $PuntoVenta->email = $email;
                            $PuntoVenta->periodicidadId = 0;
                            $PuntoVenta->clasificador1Id = 0;
                            $PuntoVenta->clasificador2Id = 0;
                            $PuntoVenta->clasificador3Id = 0;
                            $PuntoVenta->valorRecarga = 0;

                            /* Asignación de valores iniciales a atributos del objeto PuntoVenta. */
                            $PuntoVenta->valorCupo = '0';
                            $PuntoVenta->valorCupo2 = '0';
                            $PuntoVenta->porcenComision = '0';
                            $PuntoVenta->porcenComision2 = '0';
                            $PuntoVenta->estado = 'A';
                            $PuntoVenta->usuarioId = '0';

                            /* asigna valores a propiedades de un objeto "PuntoVenta". */
                            $PuntoVenta->mandante = $Partner;
                            $PuntoVenta->moneda = $moneda_default;
                            //$PuntoVenta->moneda = $CurrencyId->Id;
                            $PuntoVenta->idioma = $idioma;
                            $PuntoVenta->cupoRecarga = 0;
                            $PuntoVenta->creditosBase = 0;

                            /* Se inicializan variables relacionadas a créditos y se crea una instancia de UsuarioConfig. */
                            $PuntoVenta->creditos = 0;
                            $PuntoVenta->creditosAnt = 0;
                            $PuntoVenta->creditosBaseAnt = 0;
                            $PuntoVenta->usuarioId = $consecutivo_usuario;


                            $UsuarioConfigMySqlDAO = new UsuarioConfigMySqlDAO($UsuarioMySqlDAO->getTransaction());


                            /* Código para insertar un concesionario y un perfil de usuario en la base de datos. */
                            $ConcesionarioMySqlDAO = new ConcesionarioMySqlDAO($UsuarioMySqlDAO->getTransaction());

                            $ConcesionarioMySqlDAO->insert($Concesionario);

                            $UsuarioPerfilMySqlDAO = new UsuarioPerfilMySqlDAO($UsuarioMySqlDAO->getTransaction());

                            $UsuarioPerfilMySqlDAO->insert($UsuarioPerfil);


                            /* Se realizan inserciones de datos en bases de datos mediante objetos DAO en transacciones. */
                            $UsuarioPremiomaxMySqlDAO = new UsuarioPremiomaxMySqlDAO($UsuarioMySqlDAO->getTransaction());

                            $UsuarioPremiomaxMySqlDAO->insert($UsuarioPremiomax);

                            $UsuarioConfigMySqlDAO->insert($UsuarioConfig);

                            $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO($UsuarioMySqlDAO->getTransaction());

                            /* Inserta un punto de venta y actualiza la contraseña del usuario en la base de datos. */
                            $PuntoVentaMySqlDAO->insert($PuntoVenta);

                            $UsuarioMySqlDAO->getTransaction()->commit();


                            $UsuarioMySqlDAO->updateClave($Usuario, $password);


                        }

                    } catch (Exception $e) {
                        /* Captura excepciones y muestra información sobre el error ocurrido en el script. */

                        print_r($e);
                    }

                    //new table row for every record
                    echo "<tr>";

                    /* Itera sobre un arreglo, creando celdas de tabla para cada campo del registro. */
                    foreach ($number as $k => $content) {
                        //new table cell for every field of the record
                        //echo "<td>" . $content . "</td>";
                    }
                }

                echo "</table>";
            }

        }
    } else {
        /* Muestra un mensaje si no se selecciona ningún archivo. */

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
