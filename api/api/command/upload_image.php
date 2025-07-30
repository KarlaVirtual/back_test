<?php

use Backend\dto\Banco;
use Backend\dto\BonoDetalle;
use Backend\dto\Categoria;
use Backend\dto\Clasificador;
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
use Backend\dto\UsuarioLog2;
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
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\DocumentoUsuarioMySqlDAO;
use Backend\mysql\PromocionalLogMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\mysql\RegistroMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\UsuarioBancoMySqlDAO;
use Backend\mysql\UsuarioBannerMySqlDAO;
use Backend\mysql\UsuarioConfiguracionMySqlDAO;
use Backend\mysql\UsuarioLog2MySqlDAO;
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
 *
 * command/upload_image
 *
 * Procesamiento y almacenamiento de imágenes de usuario
 *
 * Este proceso recibe una imagen en base64, la decodifica y la almacena en la base de datos junto con un registro de auditoría.
 * Dependiendo del tipo de imagen proporcionado, se asigna una categoría específica y se ajusta el estado del usuario.
 * Luego, se registra la operación en la tabla de logs y se actualiza la información del usuario en la base de datos.
 *
 * @param object $json : Objeto que contiene la sesión del usuario y los parámetros de la imagen.
 * @param string $json ->session->usuario : Identificador del usuario en sesión.
 * @param string $json ->session->usuarioip : Dirección IP del usuario en sesión.
 * @param string $json ->params->image_data : Imagen en formato base64 enviada por el usuario.
 * @param string $json ->params->type : Tipo de imagen (frontal, posterior o servicios).
 *
 * @return object  $response es un array con los siguientes atributos:
 *  - *code* (int): Código de error, donde `0` indica éxito.
 *  - *data* (array): Contiene el resultado de la operación.
 *
 * @throws no No contiene manejo de excepciones.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* Se inicializan objetos para manejar configuración y usuario a partir de JSON. */
$ConfigurationEnvironment = new ConfigurationEnvironment();
$UsuarioMandante = new UsuarioMandante($json->session->usuario);
$Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());


$image_data = $json->params->image_data;

/* asigna un valor de tipo a la variable $tipo desde un JSON. */
$type = $json->params->type;
$tipo = 'USUDNIANTERIOR';


if ($image_data != "") {

    /* asigna valores a variables según el tipo correspondiente. */
    if ($type == 'back') {
        $type = 'P';
        $tipo = 'USUDNIPOSTERIOR';
    } elseif ($type == 'services') {
        $tipo = 'USUVERDOM';
    } else {
        /* asigna el valor 'A' a la variable $type si la condición anterior es falsa. */

        $type = 'A';
    }


    /* decodifica una imagen en base64 y escapa caracteres especiales. */
    $pos = strpos($image_data, 'base64,');
    $file_contents1 = base64_decode(substr($image_data, $pos + 7));
    $file_contents1 = addslashes($file_contents1);


    /* $name=$json->session->usuario.time().$type.".png";
     $data1=base64ToImage($image_data, $name);
     $data1 = file_get_contents( $name);

     $file_contents1 = addslashes($data1);

     unlink($name);*/

}

/* modifica el estado del jugador según el tipo especificado. */
if ($type != 'services') {
    if ($type == 'back') {

        $Usuario->estadoJugador = substr($Usuario->estadoJugador, 0, 1) . 'P';
    } else {
        $Usuario->estadoJugador = 'P' . substr($Usuario->estadoJugador, 1, 1);

    }

}


/* Registro de log de usuario en el sistema con identificadores y direcciones IP. */
$UsuarioLog = new UsuarioLog2();
$UsuarioLog->setUsuarioId($Usuario->usuarioId);
$UsuarioLog->setUsuarioIp($json->session->usuarioip);
$UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
$UsuarioLog->setUsuariosolicitaIp($json->session->usuarioip);
$UsuarioLog->setUsuarioaprobarId(0);

/* establece valores para un objeto UsuarioLog, configurando su estado y tipo. */
$UsuarioLog->setTipo($tipo);
$UsuarioLog->setEstado("P");
$UsuarioLog->setValorAntes('');
$UsuarioLog->setValorDespues('');
$UsuarioLog->setUsucreaId(0);
$UsuarioLog->setUsumodifId(0);

/* inserta un registro de usuario con una imagen en SQL. */
$UsuarioLog->setImagen($file_contents1);

$UsuarioLogMySqlDAO = new UsuarioLog2MySqlDAO();
$UsuarioMySqlDAO = new UsuarioMySqlDAO($UsuarioLogMySqlDAO->getTransaction());

$UsuarioLogMySqlDAO->insert($UsuarioLog);

/* Actualiza un usuario y confirma la transacción, devolviendo un código de respuesta. */
$UsuarioMySqlDAO->update($Usuario);
$UsuarioLogMySqlDAO->getTransaction()->commit();


$response = array();

$response['code'] = 0;


/* Convierte una cadena Base64 en una imagen y la guarda en un archivo. */
$data = array();

$data["result"] = 0;

$response['data'] = $data;

/**
 * Convierte una cadena en formato Base64 a un archivo de imagen.
 *
 * Esta función toma una cadena codificada en Base64, la decodifica y la guarda en un archivo
 * de salida especificado. Es útil para almacenar imágenes enviadas como Base64 en el sistema
 * de archivos del servidor.
 *
 * @param string $base64_string La cadena Base64 que representa la imagen.
 * @param string $output_file La ruta y nombre del archivo donde se guardará la imagen.
 * @return string La ruta del archivo de imagen generado.
 * @throws no No contiene manejo de excepciones.
 */

function base64ToImage($base64_string, $output_file)
{

    /* decodifica una cadena base64 y guarda el archivo resultante. */
    $base64_string = str_replace(" ", "+", $base64_string);
    $file = fopen($output_file, "wb");

    $data = explode(',', $base64_string);

    fwrite($file, base64_decode($data[1]));

    /* cierra un archivo previamente abierto en PHP. */
    fclose($file);

    return $output_file;
}




