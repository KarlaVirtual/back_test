<?php

use Backend\dto\Banco;
use Backend\dto\BonoDetalle;
use Backend\dto\Categoria;
use Backend\dto\Clasificador;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Consecutivo;
use Backend\dto\ContactoComercial;
use Backend\dto\CuentaCobro;
use Backend\dto\Departamento;
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
use Backend\mysql\ConsecutivoMySqlDAO;
use Backend\mysql\ContactoComercialMySqlDAO;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\DocumentoUsuarioMySqlDAO;
use Backend\mysql\PromocionalLogMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\mysql\RegistroMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\UsuarioBancoMySqlDAO;
use Backend\mysql\UsuarioBannerMySqlDAO;
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
 * Recibe los usuarios que ingresen sus datos en la sección de trabaja con nosotros
 *
 * Este recurso recibe los parámetros necesarios para enviar los datos de un usuario que se contacte por medio de la
 * sección trabaja con nosotros en el sistema.
 * Valida que los parámetros estén completos, crea un objeto de tipo `ContactoComercial` y realiza un
 * insert en la base de datos. Además, en función del mandante y país, envía un correo de notificación
 * a los destinatarios específicos. Si la operación es exitosa, se realiza un commit de la transacción.
 *
 *
 * @param string $address : Dirección del contacto comercial. No puede estar vacío.
 * @param string $country ->Id : ID del país del contacto comercial. Si no se incluye, se usa el nombre del país.
 * @param string $country ->paisId : ID del país.
 * @param string $department ->Id : ID del departamento.
 * @param string $site_id : ID del sitio. Se convierte a minúsculas.
 * @param string $email : Correo electrónico del contacto comercial. No puede estar vacío.
 * @param string $first_name : Nombre del contacto comercial. No puede estar vacío.
 * @param string $last_name : Apellido del contacto comercial. No puede estar vacío.
 * @param string $phone : Teléfono del contacto comercial. No puede estar vacío.
 * @param string $observation : Observaciones adicionales sobre el contacto.
 * @param string $company : Nombre de la empresa del contacto comercial.
 * @param string $skype : ID de Skype del contacto comercial.
 *
 * El objeto $response es un array con los siguientes atributos:
 *  - *code* (int): Código de error desde el proveedor.
 *  - *result* (string): Contiene el mensaje de error.
 *  - *data* (array): Contiene el resultado de la consulta.
 *
 * @throws Exception Si algún parámetro obligatorio está vacío o si ocurre un error durante la creación del contacto comercial. Códigos de error posibles:
 *  - "10000": Parámetro vacío (como dirección, país, departamento, email, nombre, apellido, teléfono).
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


// recibimos los parametros y validamos que no esten vacios
try {

    /* Valida dirección y país en un objeto JSON; lanza excepción si están vacíos. */
    if ($json->params->address != '') {
        $address = $json->params->address;
    } else {
        throw new Exception("Address empty ", "10000");
    }

    if ($json->params->country->Id != '') {
        $country = new Pais($json->params->country->Id);
    } elseif ($json->params->country != '') {
        /* Verifica si el país no está vacío y crea un objeto "Pais". */

        $country = new Pais('', $json->params->country);
    } else {
        /* Lanza una excepción si el país está vacío, con un mensaje y código específico. */

        throw new Exception("Country empty", "10000");
    }


    /* Verifica si el ID del departamento no está vacío y crea un objeto Departamento. */
    if ($json->params->department->Id != '') {

        $department = new Departamento($json->params->department->Id);

    } else {
        throw new Exception("department empty", "10000");
    }

    /* Convierte el site_id a minúsculas y crea un objeto Mandante. Verifica email. */
    $site_id = strtolower($json->params->site_id);

    $Mandante = new Mandante($site_id);

    if ($json->params->email != '') {
        $email = $json->params->email;
    } else {
        /* Lanza una excepción si el correo electrónico está vacío, con un código específico. */

        throw new Exception("email empty", "10000");
    }


    /* Valida y asigna nombres desde un JSON; lanza excepción si falta algún nombre. */
    if ($json->params->first_name != '') {
        $first_name = $json->params->first_name;
    } else {
        throw new Exception("Names empty", "10000");
    }

    if ($json->params->last_name != '') {
        $last_name = $json->params->last_name;
    } else {
        /* Lanza una excepción si el apellido está vacío, con un mensaje y código específicos. */

        throw new Exception("Last name empty", "10000");
    }

    /* Verifica si el teléfono está vacío; lanza excepción si es así, obtiene observación. */
    if ($json->params->phone != '') {
        $phone = $json->params->phone;
    } else {
        throw new Exception("Phone empty", "10000");
    }
    $observation = $json->params->observation;

    /* depura y elimina emojis de la variable observation */
    $ConfigurationEnvironment = new ConfigurationEnvironment();
    $observation = $ConfigurationEnvironment->remove_emoji($observation);

    /* Se extraen datos JSON y se insertan en un objeto de contacto comercial. */
    $company = $json->params->company;
    $skype = $json->params->skype;

//se crea el objeto y se realiza el insert en la base de datos.
    $ContactoComercial = new ContactoComercial();

    $ContactoComercial->direccion = $address;

    /* Asignación de datos a un objeto ContactoComercial desde diferentes fuentes de información. */
    $ContactoComercial->paisId = $country->paisId;
    $ContactoComercial->deptoId = $json->params->department->Id;
    $ContactoComercial->email = $email;
    $ContactoComercial->nombres = $first_name;
    $ContactoComercial->apellidos = $last_name;
    $ContactoComercial->observacion = $observation;

    /* Se asignan valores a atributos de un objeto de contacto comercial. */
    $ContactoComercial->telefono = $phone;
    $ContactoComercial->empresa = $company;
    $ContactoComercial->skype = $skype;
    $ContactoComercial->fechaCrea = date("Y-m-d H:i:s");
    $ContactoComercial->fechaModif = date("Y-m-d H:i:s");
    $ContactoComercial->usumodifId = '0';

    /* asigna un mandante y un tipo a un contacto comercial. */
    $ContactoComercial->mandante = $Mandante->mandante;
    $ContactoComercial->tipo = "T";


    if ($Mandante->mandante == '6') {

        /* Genera un correo con detalles de una solicitud para trabajar con la empresa. */
        $msubjetc = "Trabaja con nosotros " . $Mandante->nombre . ' - ' . $json->params->nombre;
        $mtitle = "Trabaja con nosotros";

        $textoAEnviar = "<div style='text-align: left'>Han enviado una nueva solicitud de Trabaja con nosotros<br><br>

Email: " . $ContactoComercial->email . "
<br><br>
Nombres: " . $ContactoComercial->nombres . "
<br><br>
Apellidos: " . $ContactoComercial->apellidos . "
<br><br>
Telefono: " . $ContactoComercial->telefono . "
<br><br>
Empresa: " . $ContactoComercial->empresa . "
<br><br>
Skype: " . $ContactoComercial->skype . "
<br><br>
Mensaje: " . $ContactoComercial->observacion . "
<br><br>
Fecha: " . $ContactoComercial->fechaCrea . "
<br><br><div style='font-weight: bold;text-transform: uppercase;text-align: center;'>" . $Mandante->nombre . "</div></div>
";

        //Destinatarios

        /* Código para enviar un correo utilizando una configuración específica. */
        $destinatarios = "info@netabet.com.mx";

        $ConfigurationEnvironment = new ConfigurationEnvironment();
        //Envia el mensaje de correo
        $envio = $ConfigurationEnvironment->EnviarCorreoVersion2($destinatarios, '', '', $msubjetc, '', $mtitle, $textoAEnviar, "", "", "", $Mandante->mandante);

    }


    if ($Mandante->mandante == '0' && $country->paisId == 46) {

        /* Genera un correo con detalles de una solicitud de empleo y datos del candidato. */
        $msubjetc = "Trabaja con nosotros " . $Mandante->nombre . ' - ' . $json->params->nombre;
        $mtitle = "Trabaja con nosotros";

        $textoAEnviar = "<div style='text-align: left'>Han enviado una nueva solicitud de Trabaja con nosotros<br><br>

Email: " . $ContactoComercial->email . "
<br><br>
Nombres: " . $ContactoComercial->nombres . "
<br><br>
Apellidos: " . $ContactoComercial->apellidos . "
<br><br>
Telefono: " . $ContactoComercial->telefono . "
<br><br>
Empresa: " . $ContactoComercial->empresa . "
<br><br>
Skype: " . $ContactoComercial->skype . "
<br><br>
Mensaje: " . $ContactoComercial->observacion . "
<br><br>
Fecha: " . $ContactoComercial->fechaCrea . "
<br><br><div style='font-weight: bold;text-transform: uppercase;text-align: center;'>" . $Mandante->nombre . "</div></div>
";

        //Destinatarios

        /* Envía un correo utilizando la configuración definida y destinatarios específicos. */
        $destinatarios = "soporte@doradobet.cl";

        $ConfigurationEnvironment = new ConfigurationEnvironment();
        //Envia el mensaje de correo
        $envio = $ConfigurationEnvironment->EnviarCorreoVersion2($destinatarios, '', '', $msubjetc, '', $mtitle, $textoAEnviar, "", "", "", $Mandante->mandante);

    }
    if ($Mandante->mandante == '0' && $country->paisId == 60) {

        /* Construye un mensaje HTML con detalles de una solicitud de empleo. */
        $msubjetc = "Trabaja con nosotros " . $Mandante->nombre . ' - ' . $json->params->nombre;
        $mtitle = "Trabaja con nosotros";

        $textoAEnviar = "<div style='text-align: left'>Han enviado una nueva solicitud de Trabaja con nosotros<br><br>

Email: " . $ContactoComercial->email . "
<br><br>
Nombres: " . $ContactoComercial->nombres . "
<br><br>
Apellidos: " . $ContactoComercial->apellidos . "
<br><br>
Telefono: " . $ContactoComercial->telefono . "
<br><br>
Empresa: " . $ContactoComercial->empresa . "
<br><br>
Skype: " . $ContactoComercial->skype . "
<br><br>
Mensaje: " . $ContactoComercial->observacion . "
<br><br>
Fecha: " . $ContactoComercial->fechaCrea . "
<br><br><div style='font-weight: bold;text-transform: uppercase;text-align: center;'>" . $Mandante->nombre . "</div></div>
";

        //Destinatarios

        /* envía un correo electrónico utilizando la clase ConfigurationEnvironment. */
        $destinatarios = "servicioalcliente@doradobet.cr";

        $ConfigurationEnvironment = new ConfigurationEnvironment();
        //Envia el mensaje de correo
        $envio = $ConfigurationEnvironment->EnviarCorreoVersion2($destinatarios, '', '', $msubjetc, '', $mtitle, $textoAEnviar, "", "", "", $Mandante->mandante);

    }


    /* Se inserta un contacto comercial en MySQL y se prepara una respuesta. */
    $ContactoComercialMySqlDAO = new ContactoComercialMySqlDAO();
    $ContactoComercialMySqlDAO->insert($ContactoComercial);
    $ContactoComercialMySqlDAO->getTransaction()->commit();

    $response = array(
        "code" => 0,
        "data" => array(
            "result" => 0,
            "result_text" => null,
            "data" => array(),
        ),
    );
} catch (Exception $e) {
    /* Manejo de excepciones en PHP que imprime información del error capturado. */

    print_r($e);
}










