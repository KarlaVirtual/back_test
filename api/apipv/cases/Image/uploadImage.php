<?php

use Backend\dto\ApiTransaction;
use Backend\dto\Area;
use Backend\dto\Bono;
use Backend\dto\BonoDetalle;
use Backend\dto\BonoInterno;
use Backend\dto\BonoLog;
use Backend\dto\Cargo;
use Backend\dto\Categoria;
use Backend\dto\CategoriaProducto;
use Backend\dto\CentroCosto;
use Backend\dto\Clasificador;
use Backend\dto\CodigoPromocional;
use Backend\dto\Competencia;
use Backend\dto\CompetenciaPuntos;
use Backend\dto\Concepto;
use Backend\dto\Concesionario;
use Backend\dto\Consecutivo;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\ContactoComercial;
use Backend\dto\ContactoComercialLog;
use Backend\dto\CuentaCobro;
use Backend\dto\CuentaContable;
use Backend\dto\CupoLog;
use Backend\dto\Descarga;
use Backend\dto\DocumentoUsuario;
use Backend\dto\Egreso;
use Backend\dto\Empleado;
use Backend\dto\FlujoCaja;
use Backend\dto\Flujocajafact;
use Backend\dto\Ingreso;
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
use Backend\dto\PaisMandante;
use Backend\dto\Perfil;
use Backend\dto\PerfilSubmenu;
use Backend\dto\ProdMandanteTipo;
use Backend\dto\Producto;
use Backend\dto\ProductoComision;
use Backend\dto\ProductoInterno;
use Backend\dto\ProductoTercero;
use Backend\dto\ProductoterceroUsuario;
use Backend\dto\PromocionalLog;
use Backend\dto\Proveedor;
use Backend\dto\ProveedorMandante;
use Backend\dto\ProveedorTercero;
use Backend\dto\PuntoVenta;
use Backend\dto\ProductoMandante;
use Backend\dto\Registro;
use Backend\dto\SaldoUsuonlineAjuste;
use Backend\dto\Submenu;
use Backend\dto\TransaccionApi;
use Backend\dto\TransaccionApiMandante;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransaccionSportsbook;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransprodLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioAlerta;
use Backend\dto\UsuarioBanco;
use Backend\dto\UsuarioBloqueado;
use Backend\dto\UsuarioBono;
use Backend\dto\UsuarioCierrecaja;
use Backend\dto\UsuarioComision;
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
use Backend\dto\UsuarioRecargaResumen;
use Backend\dto\UsuarioRetiroResumen;
use Backend\dto\UsuarioSaldo;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioTokenInterno;
use Backend\dto\UsucomisionResumen;
use Backend\dto\UsumarketingResumen;
use Backend\imports\Google\GoogleAuthenticator;
use Backend\integrations\mensajeria\Okroute;
use Backend\integrations\payout\LPGSERVICES;
use Backend\mysql\AreaMySqlDAO;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\mysql\BonoLogMySqlDAO;
use Backend\mysql\CargoMySqlDAO;
use Backend\mysql\CategoriaMySqlDAO;
use Backend\mysql\CategoriaProductoMySqlDAO;
use Backend\mysql\CentroCostoMySqlDAO;
use Backend\mysql\ClasificadorMySqlDAO;
use Backend\mysql\CodigoPromocionalMySqlDAO;
use Backend\mysql\CompetenciaPuntosMySqlDAO;
use Backend\mysql\ConceptoMySqlDAO;
use Backend\mysql\ConcesionarioMySqlDAO;
use Backend\mysql\ConsecutivoMySqlDAO;
use Backend\mysql\ContactoComercialLogMySqlDAO;
use Backend\mysql\ContactoComercialMySqlDAO;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\CuentaContableMySqlDAO;
use Backend\mysql\CupoLogMySqlDAO;
use Backend\mysql\DocumentoUsuarioMySqlDAO;
use Backend\mysql\EgresoMySqlDAO;
use Backend\mysql\EmpleadoMySqlDAO;
use Backend\mysql\FlujoCajaMySqlDAO;
use Backend\mysql\IngresoMySqlDAO;
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
use Backend\mysql\ProductoTerceroMySqlDAO;
use Backend\mysql\ProductoterceroUsuarioMySqlDAO;
use Backend\mysql\ProveedorMandanteMySqlDAO;
use Backend\mysql\ProveedorMySqlDAO;
use Backend\mysql\ProveedorTerceroMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\mysql\RegistroMySqlDAO;
use Backend\mysql\SaldoUsuonlineAjusteMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransprodLogMySqlDAO;
use Backend\mysql\UsuarioAlertaMySqlDAO;
use Backend\mysql\UsuarioBancoMySqlDAO;
use Backend\mysql\UsuarioBloqueadoMySqlDAO;
use Backend\mysql\UsuarioBonoMySqlDAO;
use Backend\mysql\UsuarioCierrecajaMySqlDAO;
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
use Backend\mysql\UsuarioTokenInternoMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\mysql\UsucomisionResumenMySqlDAO;
use Backend\websocket\WebsocketUsuario;

/*echo exec('pwd');
echo exec('sh /home/home2/backend/api/apipv/cases/Image/gs-upload.sh');
print_r('cd /tmp/ && gsutil -h "Cache-Control:public,max-age=604800" cp m/msjT1599849562.png gs://virtualcdnrealbucket/m/ >  /dev/null &');
exit();*/
/**
 * Image/uploadImage
 *
 * Este script permite subir una imagen al servidor, verificar su tamaño y almacenarla en Google Cloud Storage.
 *
 * @param array $_FILES['upload'] Archivo subido que contiene:
 * @param string $_FILES['upload']['name'] Nombre del archivo.
 * @param string $_FILES['upload']['type'] Tipo MIME del archivo.
 * @param int $_FILES['upload']['size'] Tamaño del archivo en bytes.
 * @param string $_FILES['upload']['tmp_name'] Ruta temporal del archivo.
 * @param string $_REQUEST['postedFile'] Archivo enviado como parámetro adicional.
 *
 * @return array $response Respuesta del sistema con los siguientes valores:
 *  - HasError (bool): Indica si ocurrió un error.
 *  - AlertType (string): Tipo de alerta ('Error' en caso de error).
 *  - AlertMessage (string): Mensaje de alerta.
 *  - url (string): URL de la imagen subida.
 *  - success (string): Estado de éxito ('success' en caso de éxito)
 */


// Obtenemos los archivos a subir en el parametro 'upload'

/* verifica el tamaño de un archivo subido, rechazando si supera 250 KB. */
$filename = $_FILES['upload']['name'];
$filetype = $_FILES['upload']['type'];
$filesize = $_FILES['upload']['size'];


$tamaño = $filesize / 1024;

if ($tamaño > 250) {
    throw new Exception("Error Processing Request", 110002);
}


/* asigna el tipo de archivo a una variable si no está definido. */
$postedFile = $_REQUEST['postedFile'];

if ($filetype == "") {
    $filetype = $_FILES['postedFile']['type'];

}

/* Genera un nombre de archivo basado en la sesión y tipo de imagen. */
$name = "msj" . (intval($_SESSION['mandante'])) . '212';

// Creamos el nombre de el archivo
$filename = $name . "T" . time() . '.png';
if ($filetype == 'image/svg' || $filetype == 'image/svg+xml') {
    $filename = $name . "T" . time() . '.svg';

}

/* Genera un nombre de archivo basado en tipo y tiempo para imágenes WebP y WebM. */
if ($filetype == 'image/webp' || $filetype == 'image/webp') {
    $filename = $name . "T" . time() . '.webp';

}
if ($filetype == 'image/webm' || $filetype == 'image/webm') {
    $filename = $name . "T" . time() . '.webm';

}


/* verifica el tipo de archivo y genera un nombre para GIF. */
if ($filetype == 'image/gif') {
    $filename = $name . "T" . time() . '.gif';

}


//$filename = $name . "T" . time() . '.' . 'png';

$url = '';


/* gestiona la carga y almacenamiento de imágenes en un servidor temporal. */
$response["HasError"] = false;

// Guardamos la imagen
/*$dirsave = '/tmp/' . $filename;
if ($filetype == 'image/jpeg' or $filetype == 'image/png' or $filetype == 'image/gif') {
    if (move_uploaded_file($_FILES['upload']['tmp_name'], $dirsave)) {
        $url = 'https://images.virtualsoft.tech/m/' . $filename;
    } else {
        $response["HasError"] = true;
    }
}*/


$dirsave = '/tmp/' . $filename;
if ($filetype == 'image/jpeg' or $filetype == 'image/png' or $filetype == 'image/gif' or $filetype == 'image/svg' or $filetype == 'image/svg+xml' or $filetype == 'image/webm' or $filetype == 'image/webp') {

    if (true) {
        if (move_uploaded_file($_FILES['upload']['tmp_name'], $dirsave)) {


            /* Código para autenticación y configuración de cliente de Google Cloud Storage. */
            $bucketName = 'virtualcdnrealbucket';
            $objectName = 'm/' . $filename;
// Authenticate your API Client
            $client = new Google_Client();
            $client->setAuthConfig('/etc/private/virtual.json');
            $client->useApplicationDefaultCredentials();

            /* Configuración de cliente para acceder a Google Cloud Storage con permisos completos. */
            $client->addScope(Google_Service_Storage::DEVSTORAGE_FULL_CONTROL);

            $storage = new Google_Service_Storage($client);

            $file_name = 'm/' . $filename;

            $obj = new Google_Service_Storage_StorageObject();

            /* sube un archivo a un sistema de almacenamiento en la nube. */
            $obj->setName($file_name);

            $obj->setCacheControl('public, max-age=604800');
            $obj->setMetadata(['contentType' => $filetype]);

            $storage->objects->insert(
                $bucketName,
                $obj,
                ['mimeType' => $filetype, 'name' => $file_name, 'data' => file_get_contents('/tmp/' . $filename), 'uploadType' => 'media']
            );

            /* Construye una URL basada en un nombre de archivo para acceder a una imagen. */
            $url = 'https://images.virtualsoft.tech/m/' . $filename;


            //shell_exec('export BOTO_CONFIG=/home/backend/.config/gcloud/legacy_credentials/admin@bpoconsultores.com.co/.boto && export PATH=/sbin:/bin:/usr/sbin:/usr/bin:/home/backend/google-cloud-sdk/bin && gsutil -h "Cache-Control:public,max-age=604800" cp /tmp/' . $filename.' gs://virtualcdnrealbucket/m/');
            //shell_exec('cd /tmp/ && gsutil -h "Cache-Control:public,max-age=604800" cp m/' . $filename.' gs://virtualcdnrealbucket/m/');
            $url = 'https://images.virtualsoft.tech/m/' . $filename;
        } else {
            /* establece un valor de error en la respuesta si no se cumple una condición. */

            $response["HasError"] = true;
        }

    }
}


/* verifica si hay un archivo enviado y configura un mensaje de error. */
if ($postedFile != '') {

}

$response["AlertType"] = "Error";
$response["AlertMessage"] = "2";

/* Asigna una URL y un estado de éxito a un arreglo de respuesta. */
$response["url"] = $url;
$response["success"] = "success";
