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
use Backend\dto\GeneralLog;
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
use Backend\mysql\GeneralLogMySqlDAO;
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

/**
 * PartnerProduct/UpdatePartnerProductDetails
 *
 * Guardar Detalle de partner de producto
 *
 * @param no
 *
 * @return
 *{"HasError":boolean,"AlertType": string,"AlertMessage": string,"url": string,"success": string}
 *
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/**
 * Normaliza una cadena de texto reemplazando caracteres especiales por sus equivalentes.
 *
 * @param string $string La cadena de texto a normalizar.
 * @return string La cadena de texto normalizada.
 */
function normalize($string)
{
    $table = array(
        'Š' => 'S', 'š' => 's', 'Đ' => 'Dj', 'đ' => 'dj', 'Ž' => 'Z', 'ž' => 'z', 'Č' => 'C', 'č' => 'c', 'Ć' => 'C', 'ć' => 'c',
        'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E',
        'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O',
        'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss',
        'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'a', 'ç' => 'c', 'è' => 'e', 'é' => 'e',
        'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o',
        'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ý' => 'y', 'ý' => 'y', 'þ' => 'b',
        'ÿ' => 'y', 'Ŕ' => 'R', 'ŕ' => 'r',
    );

    /* Se utiliza `strtr` para realizar una traducción de caracteres en `$string`. */
    return strtr($string, $table);
}

/**
 * Actualiza los detalles de un producto asociado a un socio (PartnerProduct).
 *
 * Este script procesa datos enviados a través de un formulario HTML o parámetros
 * para actualizar los detalles de un producto asociado a un socio. Realiza validaciones,
 * normaliza cadenas de texto, registra cambios en logs y actualiza la base de datos.
 *
 * @param object $params Objeto que contiene los siguientes parámetros:
 * @param string $params->Id Identificador del producto.
 * @param string $params->IsActivate Estado de activación del producto ('A' para activo, 'I' para inactivo).
 * @param string $params->IsVerified Estado de verificación del producto ('A' para verificado, 'I' para no verificado).
 * @param string $params->Maximum Valor máximo permitido para el producto.
 * @param string $params->Minimum Valor mínimo permitido para el producto.
 * @param string $params->ProcessingTime Tiempo de procesamiento del producto.
 * @param int $params->Order Orden del producto.
 * @param int $params->FeaturedOrder Orden destacado del producto.
 * @param int $params->Rows Número de filas asociadas al producto.
 * @param int $params->Columns Número de columnas asociadas al producto.
 * @param string $params->Reason Razón o explicación del cambio.
 * @param string $params->BorderColor Color del borde del producto.
 * @param string $params->Partner Identificador del socio asociado al producto.
 * @param string $params->Country País asociado al producto.
 * @param string $params->FilterCountry Filtro de país para el producto.
 * 
 *
 * @return array Respuesta en formato JSON con los siguientes valores:
 *  - HasError (boolean): Indica si ocurrió un error durante el procesamiento.
 *  - AlertType (string): Tipo de alerta ('success', 'error', etc.).
 *  - AlertMessage (string): Mensaje de alerta para el usuario.
 *  - ModelErrors (array): Lista de errores de validación, si los hay.
 *
 * @throws Exception Si ocurre un error durante la transacción o actualización de la base de datos.
 */
$Id = $params->Id;

if ($_POST["Id"] != "") {

    /* valida y asigna valores de un formulario POST para procesar datos. */
    $Id = $_POST["Id"];

    $IsActivate = ($_POST["IsActivate"] != "A" && $_POST["IsActivate"] != "I") ? "" : $_POST["IsActivate"];
    $IsVerified = ($_POST["IsVerified"] != "A" && $_POST["IsVerified"] != "I") ? "" : $_POST["IsVerified"];
    $Maximum = $_POST["Maximum"];
    $Minimum = $_POST["Minimum"];

    /* obtiene y convierte datos enviados por un formulario HTML mediante POST. */
    $ProcessingTime = $_POST["ProcessingTime"];
    $Order = intval($_POST["Order"]);
    $FeaturedOrder = intval($_POST["FeaturedOrder"]);
    $Rows = intval($_POST["Rows"]);
    $Columns = intval($_POST["Columns"]);
    $Reason = $_POST["Reason"];

    /* Se asignan valores de un formulario a variables PHP para su posterior procesamiento. */
    $BorderColor = $_POST["BorderColor"];
    $Partner = $_POST["Partner"];
    $Country = $_POST["Country"];

    $Image = $_POST["upload_fullpath"];
    $FilterCountry = $_POST["FilterCountry"];


    if ($Image != "") {


        /* Se crean objetos ProductoMandante y Producto, y se obtienen datos de un archivo subido. */
        $productoMandante = new ProductoMandante('', '', $Id, '');
        $Producto = new Producto($productoMandante->productoId);

        $Filename = $_FILES['upload']['name'];

        $Filetype = $_FILES['upload']['type'];


        /* asigna un tipo de archivo basado en su extensión: PNG o GIF. */
        $fileTypeName = "";

        if ($Filetype != "image/gif") {
            $fileTypeName = "png";
        } else {
            $fileTypeName = "gift";
        }

        /* reemplaza espacios y caracteres específicos en la descripción de un producto. */
        $name = $Producto->getDescripcion();
        $name = str_replace(' ', '-', $Producto->getDescripcion());
        $name = str_replace('(', '-', $name);
        $name = str_replace(')', '-', $name);
        $name = str_replace('&', '-', $name);
        $name = str_replace("'", '', $name);

        /* sanitiza un nombre y crea un nombre de archivo único. */
        $name = str_replace("'", '', $name);
        $name = str_replace(":", '', $name);
        $name = normalize($name);

        $filename = $name . "-" . time() . '.' . $fileTypeName;

        $dirsave = '/tmp/' . $filename;


        /* Verifica tipo de archivo, mueve la imagen y actualiza la URL en la base de datos. */
        if ($Filetype == "image/jpeg" || $Filetype == "image/png" || $Filetype == 'image/gif') {

            if (move_uploaded_file($_FILES['upload']['tmp_name'], $dirsave)) {
                shell_exec('export BOTO_CONFIG=/home/backend/.config/gcloud/legacy_credentials/admin@bpoconsultores.com.co/.boto && export PATH=/sbin:/bin:/usr/sbin:/usr/bin:/home/backend/google-cloud-sdk/bin && gsutil -h "Cache-Control:public,max-age=604800" cp /tmp/' . $filename . ' gs://virtualcdnrealbucket/productos/');
                $productoUrl = 'https://images.virtualsoft.tech/productos/' . $filename;
            }

            //    $productoUrl="https://images.virtualsoft.tech/productos/".$filename;

            $productoMandante->setImageUrl2($productoUrl);

            $ProductoMandanteMySqlDAO = new ProductoMandanteMySqlDAO();
            $transaction = $ProductoMandanteMySqlDAO->getTransaction();
            $ProductoMandanteMySqlDAO->update($productoMandante);
            $ProductoMandanteMySqlDAO->getTransaction()->commit();

        }

    }


    /* Se crea un objeto ProductoMandante y se obtiene su transacción para usarla. */
    $ProductoMandante = new ProductoMandante("", "", $Id);
    $ProductoMandanteMySqlDAO = new ProductoMandanteMySqlDAO();
    $Transaction = $ProductoMandanteMySqlDAO->getTransaction();
    $ClientId = $_SESSION['usuario2'];

    $Producto = new Producto($ProductoMandante->productoId);

    /* Código inicializa un DAO y obtiene una transacción, preparando para inserción o actualización. */
    $ProductoMySqlDAO = new ProductoMySqlDAO();
    $Transaction = $ProductoMySqlDAO->getTransaction();

    $insertOrUpdate = false;

    if ($IsActivate != "" && $ProductoMandante->estado != $IsActivate) {


        /* Se registra un cambio en el campo "estado" de un producto mandante. */
        $tipo = 'CHANGEFIELD';
        $tabla = 'producto_mandante';
        $campo = 'estado';
        $valorAntes = $ProductoMandante->estado;
        $valorDespues = $IsActivate;

        $GeneralLog = new GeneralLog();

        /* Establece parámetros en un objeto de registro general para un usuario específico. */
        $GeneralLog->setUsuarioId($ClientId);
        $GeneralLog->setUsuarioIp($Global_IP);
        $GeneralLog->setUsuariosolicitaId($ClientId);
        $GeneralLog->setUsuariosolicitaIp($Global_IP);
        $GeneralLog->setTipo($tipo);
        $GeneralLog->setEstado("A");


        /* establece valores y atributos en un objeto de registro general. */
        $GeneralLog->setValorAntes($valorAntes);
        $GeneralLog->setValorDespues($valorDespues);

        $GeneralLog->setUsucreaId(0);
        $GeneralLog->setUsumodifId(0);
        $GeneralLog->setDispositivo($Global_dispositivo);

        /* Configura parámetros de un objeto GeneralLog en base a variables globales y atributos de producto. */
        $GeneralLog->setSoperativo($Global_soperativo);
        $GeneralLog->setSversion($Global_sversion);
        $GeneralLog->setTabla($tabla);
        $GeneralLog->setCampo($campo);
        $GeneralLog->setExternoId($ProductoMandante->prodmandanteId);
        $GeneralLog->setMandante($ProductoMandante->mandante);

        /* registra un log general y actualiza el estado de un producto. */
        $GeneralLog->setExplicacion($Reason);
        $GeneralLogMySqlDAO = new GeneralLogMySqlDAO($Transaction);
        $GeneralLogMySqlDAO->insert($GeneralLog);


        $ProductoMandante->estado = $IsActivate;


    }

    if ($IsVerified != "" && $ProductoMandante->verifica != $IsVerified) {


        /* Código que registra un cambio en el campo 'verifica' de 'producto_mandante'. */
        $tipo = 'CHANGEFIELD';
        $tabla = 'producto_mandante';
        $campo = 'verifica';
        $valorAntes = $ProductoMandante->verifica;
        $valorDespues = $IsVerified;

        $GeneralLog = new GeneralLog();

        /* Establece propiedades del objeto GeneralLog relacionadas con usuario y estado. */
        $GeneralLog->setUsuarioId($ClientId);
        $GeneralLog->setUsuarioIp($Global_IP);
        $GeneralLog->setUsuariosolicitaId($ClientId);
        $GeneralLog->setUsuariosolicitaIp($Global_IP);
        $GeneralLog->setTipo($tipo);
        $GeneralLog->setEstado("A");


        /* establece valores y configuraciones para un objeto de registro general. */
        $GeneralLog->setValorAntes($valorAntes);
        $GeneralLog->setValorDespues($valorDespues);

        $GeneralLog->setUsucreaId(0);
        $GeneralLog->setUsumodifId(0);
        $GeneralLog->setDispositivo($Global_dispositivo);

        /* establece atributos en un objeto de registro general. */
        $GeneralLog->setSoperativo($Global_soperativo);
        $GeneralLog->setSversion($Global_sversion);
        $GeneralLog->setTabla($tabla);
        $GeneralLog->setCampo($campo);
        $GeneralLog->setExternoId($ProductoMandante->prodmandanteId);
        $GeneralLog->setMandante($ProductoMandante->mandante);

        /* registra un log general y verifica un producto mandante. */
        $GeneralLog->setExplicacion($Reason);
        $GeneralLogMySqlDAO = new GeneralLogMySqlDAO($Transaction);
        $GeneralLogMySqlDAO->insert($GeneralLog);

        $ProductoMandante->verifica = $IsVerified;
    }

    if ($FilterCountry != "" && $ProductoMandante->filtroPais != $FilterCountry) {


        /* registra un cambio en el campo 'filtroPais' de 'producto_mandante'. */
        $tipo = 'CHANGEFIELD';
        $tabla = 'producto_mandante';
        $campo = 'filtroPais';
        $valorAntes = $ProductoMandante->filtroPais;
        $valorDespues = $FilterCountry;

        $GeneralLog = new GeneralLog();

        /* Configuración de un registro general con IDs y IPs de usuario y estado activo. */
        $GeneralLog->setUsuarioId($ClientId);
        $GeneralLog->setUsuarioIp($Global_IP);
        $GeneralLog->setUsuariosolicitaId($ClientId);
        $GeneralLog->setUsuariosolicitaIp($Global_IP);
        $GeneralLog->setTipo($tipo);
        $GeneralLog->setEstado("A");


        /* Configura valores y usuarios en el registro general de modificaciones de datos. */
        $GeneralLog->setValorAntes($valorAntes);
        $GeneralLog->setValorDespues($valorDespues);

        $GeneralLog->setUsucreaId(0);
        $GeneralLog->setUsumodifId(0);
        $GeneralLog->setDispositivo($Global_dispositivo);

        /* asigna valores a propiedades de un objeto GeneralLog. */
        $GeneralLog->setSoperativo($Global_soperativo);
        $GeneralLog->setSversion($Global_sversion);
        $GeneralLog->setTabla($tabla);
        $GeneralLog->setCampo($campo);
        $GeneralLog->setExternoId($ProductoMandante->prodmandanteId);
        $GeneralLog->setMandante($ProductoMandante->mandante);

        /* Se registra un log general y se aplica un filtro por país al producto. */
        $GeneralLog->setExplicacion($Reason);
        $GeneralLogMySqlDAO = new GeneralLogMySqlDAO($Transaction);
        $GeneralLogMySqlDAO->insert($GeneralLog);

        $ProductoMandante->filtroPais = $FilterCountry;
    }

    if ($Maximum != "" && $ProductoMandante->max != $Maximum) {


        /* Código para registrar un cambio en el campo 'max' de 'producto_mandante'. */
        $tipo = 'CHANGEFIELD';
        $tabla = 'producto_mandante';
        $campo = 'max';
        $valorAntes = $ProductoMandante->max;
        $valorDespues = $Maximum;

        $GeneralLog = new GeneralLog();

        /* Código para configurar un registro general de usuario con identificadores y estado activos. */
        $GeneralLog->setUsuarioId($ClientId);
        $GeneralLog->setUsuarioIp($Global_IP);
        $GeneralLog->setUsuariosolicitaId($ClientId);
        $GeneralLog->setUsuariosolicitaIp($Global_IP);
        $GeneralLog->setTipo($tipo);
        $GeneralLog->setEstado("A");


        /* Se establecen valores y usuarios para un registro en GeneralLog. */
        $GeneralLog->setValorAntes($valorAntes);
        $GeneralLog->setValorDespues($valorDespues);

        $GeneralLog->setUsucreaId(0);
        $GeneralLog->setUsumodifId(0);
        $GeneralLog->setDispositivo($Global_dispositivo);

        /* Configura un registro general con datos específicos de la operación y producto. */
        $GeneralLog->setSoperativo($Global_soperativo);
        $GeneralLog->setSversion($Global_sversion);
        $GeneralLog->setTabla($tabla);
        $GeneralLog->setCampo($campo);
        $GeneralLog->setExternoId($ProductoMandante->prodmandanteId);
        $GeneralLog->setMandante($ProductoMandante->mandante);

        /* registra un log y establece un máximo en un producto. */
        $GeneralLog->setExplicacion($Reason);
        $GeneralLogMySqlDAO = new GeneralLogMySqlDAO($Transaction);
        $GeneralLogMySqlDAO->insert($GeneralLog);

        $ProductoMandante->max = $Maximum;
    }

    if ($Minimum != "" && $ProductoMandante->min != $Minimum) {


        /* Código que registra un cambio en el campo 'min' de 'producto_mandante'. */
        $tipo = 'CHANGEFIELD';
        $tabla = 'producto_mandante';
        $campo = 'min';
        $valorAntes = $ProductoMandante->min;
        $valorDespues = $Minimum;

        $GeneralLog = new GeneralLog();

        /* Código que establece propiedades en el objeto GeneralLog con datos del usuario y estado. */
        $GeneralLog->setUsuarioId($ClientId);
        $GeneralLog->setUsuarioIp($Global_IP);
        $GeneralLog->setUsuariosolicitaId($ClientId);
        $GeneralLog->setUsuariosolicitaIp($Global_IP);
        $GeneralLog->setTipo($tipo);
        $GeneralLog->setEstado("A");


        /* establece valores y usuarios en un objeto de registro general. */
        $GeneralLog->setValorAntes($valorAntes);
        $GeneralLog->setValorDespues($valorDespues);

        $GeneralLog->setUsucreaId(0);
        $GeneralLog->setUsumodifId(0);
        $GeneralLog->setDispositivo($Global_dispositivo);

        /* configura propiedades de un objeto `GeneralLog` con valores específicos. */
        $GeneralLog->setSoperativo($Global_soperativo);
        $GeneralLog->setSversion($Global_sversion);
        $GeneralLog->setTabla($tabla);
        $GeneralLog->setCampo($campo);
        $GeneralLog->setExternoId($ProductoMandante->prodmandanteId);
        $GeneralLog->setMandante($ProductoMandante->mandante);

        /* Se registran datos en log y se establece un mínimo para ProductoMandante. */
        $GeneralLog->setExplicacion($Reason);
        $GeneralLogMySqlDAO = new GeneralLogMySqlDAO($Transaction);
        $GeneralLogMySqlDAO->insert($GeneralLog);

        $ProductoMandante->min = $Minimum;
    }

    if ($ProcessingTime != "" && $ProductoMandante->tiempoProcesamiento != $ProcessingTime) {


        /* Código para registrar un cambio en el campo 'tiempoProcesamiento' de productos. */
        $tipo = 'CHANGEFIELD';
        $tabla = 'producto_mandante';
        $campo = 'tiempoProcesamiento';
        $valorAntes = $ProductoMandante->tiempoProcesamiento;
        $valorDespues = $ProcessingTime;

        $GeneralLog = new GeneralLog();

        /* establece propiedades de un objeto GeneralLog utilizando identificadores y direcciones IP. */
        $GeneralLog->setUsuarioId($ClientId);
        $GeneralLog->setUsuarioIp($Global_IP);
        $GeneralLog->setUsuariosolicitaId($ClientId);
        $GeneralLog->setUsuariosolicitaIp($Global_IP);
        $GeneralLog->setTipo($tipo);
        $GeneralLog->setEstado("A");


        /* Se establecen valores y metadatos en un objeto de registro general. */
        $GeneralLog->setValorAntes($valorAntes);
        $GeneralLog->setValorDespues($valorDespues);

        $GeneralLog->setUsucreaId(0);
        $GeneralLog->setUsumodifId(0);
        $GeneralLog->setDispositivo($Global_dispositivo);

        /* Se configuran propiedades del objeto GeneralLog con datos importantes del sistema. */
        $GeneralLog->setSoperativo($Global_soperativo);
        $GeneralLog->setSversion($Global_sversion);
        $GeneralLog->setTabla($tabla);
        $GeneralLog->setCampo($campo);
        $GeneralLog->setExternoId($ProductoMandante->prodmandanteId);
        $GeneralLog->setMandante($ProductoMandante->mandante);

        /* Código para registrar un log general y actualizar tiempo de procesamiento de producto. */
        $GeneralLog->setExplicacion($Reason);
        $GeneralLogMySqlDAO = new GeneralLogMySqlDAO($Transaction);
        $GeneralLogMySqlDAO->insert($GeneralLog);

        $ProductoMandante->tiempoProcesamiento = $ProcessingTime;
    }

    if ($Order != "" && $ProductoMandante->orden != $Order) {


        /* Código para registrar un cambio en el campo 'orden' de 'producto_mandante'. */
        $tipo = 'CHANGEFIELD';
        $tabla = 'producto_mandante';
        $campo = 'orden';
        $valorAntes = $ProductoMandante->orden;
        $valorDespues = $Order;

        $GeneralLog = new GeneralLog();

        /* configura parámetros de registro para un usuario en un sistema. */
        $GeneralLog->setUsuarioId($ClientId);
        $GeneralLog->setUsuarioIp($Global_IP);
        $GeneralLog->setUsuariosolicitaId($ClientId);
        $GeneralLog->setUsuariosolicitaIp($Global_IP);
        $GeneralLog->setTipo($tipo);
        $GeneralLog->setEstado("A");


        /* Se configuran valores y usuarios en un objeto de registro general. */
        $GeneralLog->setValorAntes($valorAntes);
        $GeneralLog->setValorDespues($valorDespues);

        $GeneralLog->setUsucreaId(0);
        $GeneralLog->setUsumodifId(0);
        $GeneralLog->setDispositivo($Global_dispositivo);

        /* configura propiedades de un objeto `GeneralLog` utilizando variables globales y datos de producto. */
        $GeneralLog->setSoperativo($Global_soperativo);
        $GeneralLog->setSversion($Global_sversion);
        $GeneralLog->setTabla($tabla);
        $GeneralLog->setCampo($campo);
        $GeneralLog->setExternoId($ProductoMandante->prodmandanteId);
        $GeneralLog->setMandante($ProductoMandante->mandante);

        /* Código para registrar un log general y asignar un orden a un producto. */
        $GeneralLog->setExplicacion($Reason);
        $GeneralLogMySqlDAO = new GeneralLogMySqlDAO($Transaction);
        $GeneralLogMySqlDAO->insert($GeneralLog);

        $ProductoMandante->orden = $Order;
    }

    if ($FeaturedOrder != "" && $ProductoMandante->ordenDestacado != $FeaturedOrder) {


        /* Código para registrar el cambio de un campo en la tabla 'producto_mandante'. */
        $tipo = 'CHANGEFIELD';
        $tabla = 'producto_mandante';
        $campo = 'ordenDestacado';
        $valorAntes = $ProductoMandante->ordenDestacado;
        $valorDespues = $FeaturedOrder;

        $GeneralLog = new GeneralLog();

        /* registra datos de usuario y estado en un objeto GeneralLog. */
        $GeneralLog->setUsuarioId($ClientId);
        $GeneralLog->setUsuarioIp($Global_IP);
        $GeneralLog->setUsuariosolicitaId($ClientId);
        $GeneralLog->setUsuariosolicitaIp($Global_IP);
        $GeneralLog->setTipo($tipo);
        $GeneralLog->setEstado("A");


        /* Se configuran valores y usuarios en el objeto GeneralLog para registro de cambios. */
        $GeneralLog->setValorAntes($valorAntes);
        $GeneralLog->setValorDespues($valorDespues);

        $GeneralLog->setUsucreaId(0);
        $GeneralLog->setUsumodifId(0);
        $GeneralLog->setDispositivo($Global_dispositivo);

        /* Se configuran propiedades de un objeto de registro general con datos específicos. */
        $GeneralLog->setSoperativo($Global_soperativo);
        $GeneralLog->setSversion($Global_sversion);
        $GeneralLog->setTabla($tabla);
        $GeneralLog->setCampo($campo);
        $GeneralLog->setExternoId($ProductoMandante->prodmandanteId);
        $GeneralLog->setMandante($ProductoMandante->mandante);

        /* registra un log y establece un producto destacado en la transacción. */
        $GeneralLog->setExplicacion($Reason);
        $GeneralLogMySqlDAO = new GeneralLogMySqlDAO($Transaction);
        $GeneralLogMySqlDAO->insert($GeneralLog);

        $ProductoMandante->ordenDestacado = $FeaturedOrder;
    }

    if ($Rows != "" && $ProductoMandante->numFila != $Rows) {


        /* Asignación y cambio de valor en la columna 'numFila' de 'producto_mandante'. */
        $tipo = 'CHANGEFIELD';
        $tabla = 'producto_mandante';
        $campo = 'numFila';

        $valorAntes = $ProductoMandante->numFila;
        $valorDespues = $Rows;


        /* Registra información del usuario y su IP en un objeto GeneralLog. */
        $GeneralLog = new GeneralLog();
        $GeneralLog->setUsuarioId($ClientId);
        $GeneralLog->setUsuarioIp($Global_IP);
        $GeneralLog->setUsuariosolicitaId($ClientId);
        $GeneralLog->setUsuariosolicitaIp($Global_IP);
        $GeneralLog->setTipo($tipo);

        /* establece valores y estado para un registro en GeneralLog. */
        $GeneralLog->setEstado("A");

        $GeneralLog->setValorAntes($valorAntes);
        $GeneralLog->setValorDespues($valorDespues);

        $GeneralLog->setUsucreaId(0);

        /* Configura un registro general con diversos parámetros relacionados a un dispositivo y operación. */
        $GeneralLog->setUsumodifId(0);
        $GeneralLog->setDispositivo($Global_dispositivo);
        $GeneralLog->setSoperativo($Global_soperativo);
        $GeneralLog->setSversion($Global_sversion);
        $GeneralLog->setTabla($tabla);
        $GeneralLog->setCampo($campo);

        /* Se registra un log general en la base de datos con detalles específicos. */
        $GeneralLog->setExternoId($ProductoMandante->prodmandanteId);
        $GeneralLog->setMandante($ProductoMandante->mandante);
        $GeneralLog->setExplicacion($Reason);

        $GeneralLogMySqlDAO = new GeneralLogMySqlDAO($Transaction);
        $GeneralLogMySqlDAO->insert($GeneralLog);


        /* Asigna el valor de $Rows a la propiedad numFila de ProductoMandante. */
        $ProductoMandante->numFila = $Rows;
    }

    if ($Order != "" && $ProductoMandante->numColumna != $Columns) {


        /* Código para registrar un cambio en el campo 'numColumna' de 'producto_mandante'. */
        $tipo = 'CHANGEFIELD';
        $tabla = 'producto_mandante';
        $campo = 'numColumna';
        $valorAntes = $ProductoMandante->numColumna;
        $valorDespues = $Columns;

        $GeneralLog = new GeneralLog();

        /* Código establece parámetros de registro general para un usuario y su actividad. */
        $GeneralLog->setUsuarioId($ClientId);
        $GeneralLog->setUsuarioIp($Global_IP);
        $GeneralLog->setUsuariosolicitaId($ClientId);
        $GeneralLog->setUsuariosolicitaIp($Global_IP);
        $GeneralLog->setTipo($tipo);
        $GeneralLog->setEstado("A");


        /* Se establecen valores y usuario para un registro en GeneralLog. */
        $GeneralLog->setValorAntes($valorAntes);
        $GeneralLog->setValorDespues($valorDespues);

        $GeneralLog->setUsucreaId(0);
        $GeneralLog->setUsumodifId(0);
        $GeneralLog->setDispositivo($Global_dispositivo);

        /* Configura un registro general con información específica de un producto y su mandante. */
        $GeneralLog->setSoperativo($Global_soperativo);
        $GeneralLog->setSversion($Global_sversion);
        $GeneralLog->setTabla($tabla);
        $GeneralLog->setCampo($campo);
        $GeneralLog->setExternoId($ProductoMandante->prodmandanteId);
        $GeneralLog->setMandante($ProductoMandante->mandante);

        /* registra un log general y asigna un número de columna a un producto. */
        $GeneralLog->setExplicacion($Reason);
        $GeneralLogMySqlDAO = new GeneralLogMySqlDAO($Transaction);
        $GeneralLogMySqlDAO->insert($GeneralLog);

        $ProductoMandante->numColumna = $Columns;
    }


    if ($BorderColor != "" && $productoMandante->borde != $BorderColor) {

        /* define variables para registrar un cambio en un campo de una tabla. */
        $tipo = 'CHANGEFIELD';
        $tabla = 'producto_mandante';
        $campo = 'borde';
        $valorAntes = $productoMandante->borde;
        $valorDespues = $BorderColor;
        $GeneralLog = new GeneralLog();

        /* configura un registro general con información del usuario y estado. */
        $GeneralLog->setUsuarioId($ClientId);
        $GeneralLog->setUsuarioIp($Global_IP);
        $GeneralLog->setUsuariosolicitaId($ClientId);
        $GeneralLog->setUsuariosolicitaIp($Global_IP);
        $GeneralLog->setTipo($tipo);
        $GeneralLog->setEstado("A");


        /* Se asignan valores y usuarios a un registro general de log. */
        $GeneralLog->setValorAntes($valorAntes);
        $GeneralLog->setValorDespues($valorDespues);

        $GeneralLog->setUsucreaId(0);
        $GeneralLog->setUsumodifId(0);
        $GeneralLog->setDispositivo($Global_dispositivo);

        /* establece propiedades en un objeto GeneralLog usando datos globales y externos. */
        $GeneralLog->setSoperativo($Global_soperativo);
        $GeneralLog->setSversion($Global_sversion);
        $GeneralLog->setTabla($tabla);
        $GeneralLog->setCampo($campo);
        $GeneralLog->setExternoId($ProductoMandante->prodmandanteId);
        $GeneralLog->setMandante($ProductoMandante->mandante);

        /* registra un log general y actualiza el borde de un producto. */
        $GeneralLog->setExplicacion($Reason);
        $GeneralLogMySqlDAO = new GeneralLogMySqlDAO($Transaction);
        $GeneralLogMySqlDAO->insert($GeneralLog);
        $productoMandante->borde = $BorderColor;

        $ProductoMandanteMySqlDAO = new ProductoMandanteMySqlDAO();


        /* Actualiza un producto y, si se realiza con éxito, actualiza la base de datos del casino. */
        $ProductoMandanteMySqlDAO->update($ProductoMandante);
        $ProductoMandanteMySqlDAO->getTransaction()->commit();
        $insertOrUpdate = true;


        if ($insertOrUpdate) {
            $CMSProveedor = new \Backend\cms\CMSProveedor('CASINO', '', $Partner);
            $CMSProveedor->updateDatabaseCasino();
        }
    }


    /* inicializa una respuesta sin errores y un mensaje de éxito. */
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];
} else {


    if ($Id != "") {


        /* Se asignan parámetros a variables para su posterior uso en el código. */
        $IsActivate = $params->IsActivate;
        $FilterCountry = $params->FilterCountry;
        $IsVerified = $params->IsVerified;
        $Maximum = $params->Maximum;
        $Minimum = $params->Minimum;
        $ProcessingTime = $params->ProcessingTime;

        /* asigna valores de parámetros a variables para su uso posterior. */
        $Maximum = $params->Maximum;
        $Order = intval($params->Order);
        $FeaturedOrder = intval($params->FeaturedOrder);
        $Rows = intval($params->Rows);
        $Columns = intval($params->Columns);
        $Reason = $params->Reason;


        /* valida y asigna valores a variables según condiciones específicas. */
        $IsActivate = ($IsActivate != 'A' && $IsActivate != "I") ? '' : $IsActivate;
        $IsVerified = ($IsVerified != 'A' && $IsVerified != "I") ? '' : $IsVerified;
        $FilterCountry = ($FilterCountry != 'A' && $FilterCountry != "I") ? '' : $FilterCountry;


        $ProductoMandante = new ProductoMandante("", "", $Id);

        /* Se crea un DAO para manejar transacciones de productos y se inicializa un cliente. */
        $ProductoMandanteMySqlDAO = new ProductoMandanteMySqlDAO();
        $Transaction = $ProductoMandanteMySqlDAO->getTransaction();
        $ClientId = $_SESSION['usuario2'];

        $insertOrUpdate = false;

        if ($IsActivate != "" && $ProductoMandante->estado != $IsActivate) {


            /* Registra el cambio del campo 'estado' en 'producto_mandante' utilizando GeneralLog. */
            $tipo = 'CHANGEFIELD';
            $tabla = 'producto_mandante';
            $campo = 'estado';
            $valorAntes = $ProductoMandante->estado;
            $valorDespues = $IsActivate;

            $GeneralLog = new GeneralLog();

            /* Establece valores para un log general relacionado con un usuario y su actividad. */
            $GeneralLog->setUsuarioId($ClientId);
            $GeneralLog->setUsuarioIp($Global_IP);
            $GeneralLog->setUsuariosolicitaId($ClientId);
            $GeneralLog->setUsuariosolicitaIp($Global_IP);
            $GeneralLog->setTipo($tipo);
            $GeneralLog->setEstado("A");


            /* establece valores y usuarios en un registro de log general. */
            $GeneralLog->setValorAntes($valorAntes);
            $GeneralLog->setValorDespues($valorDespues);

            $GeneralLog->setUsucreaId(0);
            $GeneralLog->setUsumodifId(0);
            $GeneralLog->setDispositivo($Global_dispositivo);

            /* establece propiedades en un objeto GeneralLog utilizando datos globales y específicos. */
            $GeneralLog->setSoperativo($Global_soperativo);
            $GeneralLog->setSversion($Global_sversion);
            $GeneralLog->setTabla($tabla);
            $GeneralLog->setCampo($campo);
            $GeneralLog->setExternoId($ProductoMandante->prodmandanteId);
            $GeneralLog->setMandante($ProductoMandante->mandante);

            /* registra un log general y actualiza el estado de un producto. */
            $GeneralLog->setExplicacion($Reason);
            $GeneralLogMySqlDAO = new GeneralLogMySqlDAO($Transaction);
            $GeneralLogMySqlDAO->insert($GeneralLog);


            $ProductoMandante->estado = $IsActivate;


        }

        if ($IsVerified != "" && $ProductoMandante->verifica != $IsVerified) {


            /* Código configura parámetros para registrar un cambio en el campo 'verifica' de 'producto_mandante'. */
            $tipo = 'CHANGEFIELD';
            $tabla = 'producto_mandante';
            $campo = 'verifica';
            $valorAntes = $ProductoMandante->verifica;
            $valorDespues = $IsVerified;

            $GeneralLog = new GeneralLog();

            /* Configura un registro general con información del usuario, IP, tipo y estado. */
            $GeneralLog->setUsuarioId($ClientId);
            $GeneralLog->setUsuarioIp($Global_IP);
            $GeneralLog->setUsuariosolicitaId($ClientId);
            $GeneralLog->setUsuariosolicitaIp($Global_IP);
            $GeneralLog->setTipo($tipo);
            $GeneralLog->setEstado("A");


            /* establece valores y datos de usuario para un registro de log general. */
            $GeneralLog->setValorAntes($valorAntes);
            $GeneralLog->setValorDespues($valorDespues);

            $GeneralLog->setUsucreaId(0);
            $GeneralLog->setUsumodifId(0);
            $GeneralLog->setDispositivo($Global_dispositivo);

            /* establece parámetros para un registro general en un sistema. */
            $GeneralLog->setSoperativo($Global_soperativo);
            $GeneralLog->setSversion($Global_sversion);
            $GeneralLog->setTabla($tabla);
            $GeneralLog->setCampo($campo);
            $GeneralLog->setExternoId($ProductoMandante->prodmandanteId);
            $GeneralLog->setMandante($ProductoMandante->mandante);

            /* Código que registra un log general y verifica un producto mandante. */
            $GeneralLog->setExplicacion($Reason);
            $GeneralLogMySqlDAO = new GeneralLogMySqlDAO($Transaction);
            $GeneralLogMySqlDAO->insert($GeneralLog);

            $ProductoMandante->verifica = $IsVerified;
        }

        if ($FilterCountry != "" && $ProductoMandante->filtroPais != $FilterCountry) {


            /* Código cambia el campo 'filtroPais' en 'producto_mandante' y registra la acción. */
            $tipo = 'CHANGEFIELD';
            $tabla = 'producto_mandante';
            $campo = 'filtroPais';
            $valorAntes = $ProductoMandante->filtroPais;
            $valorDespues = $FilterCountry;

            $GeneralLog = new GeneralLog();

            /* Configure el registro general con detalles de usuario, IP, tipo y estado. */
            $GeneralLog->setUsuarioId($ClientId);
            $GeneralLog->setUsuarioIp($Global_IP);
            $GeneralLog->setUsuariosolicitaId($ClientId);
            $GeneralLog->setUsuariosolicitaIp($Global_IP);
            $GeneralLog->setTipo($tipo);
            $GeneralLog->setEstado("A");


            /* establece valores y atributos para un objeto `GeneralLog`. */
            $GeneralLog->setValorAntes($valorAntes);
            $GeneralLog->setValorDespues($valorDespues);

            $GeneralLog->setUsucreaId(0);
            $GeneralLog->setUsumodifId(0);
            $GeneralLog->setDispositivo($Global_dispositivo);

            /* establece parámetros en un objeto de registro general. */
            $GeneralLog->setSoperativo($Global_soperativo);
            $GeneralLog->setSversion($Global_sversion);
            $GeneralLog->setTabla($tabla);
            $GeneralLog->setCampo($campo);
            $GeneralLog->setExternoId($ProductoMandante->prodmandanteId);
            $GeneralLog->setMandante($ProductoMandante->mandante);

            /* Registro de log general y filtro de país para procesos de transacción. */
            $GeneralLog->setExplicacion($Reason);
            $GeneralLogMySqlDAO = new GeneralLogMySqlDAO($Transaction);
            $GeneralLogMySqlDAO->insert($GeneralLog);

            $ProductoMandante->filtroPais = $FilterCountry;
        }

        if ($Maximum != "" && $ProductoMandante->max != $Maximum) {


            /* Código para registrar cambios en el campo 'max' de un producto mandante. */
            $tipo = 'CHANGEFIELD';
            $tabla = 'producto_mandante';
            $campo = 'max';
            $valorAntes = $ProductoMandante->max;
            $valorDespues = $Maximum;

            $GeneralLog = new GeneralLog();

            /* establece atributos para un registro general de usuario en un sistema. */
            $GeneralLog->setUsuarioId($ClientId);
            $GeneralLog->setUsuarioIp($Global_IP);
            $GeneralLog->setUsuariosolicitaId($ClientId);
            $GeneralLog->setUsuariosolicitaIp($Global_IP);
            $GeneralLog->setTipo($tipo);
            $GeneralLog->setEstado("A");


            /* establece valores y usuarios en un objeto de registro general. */
            $GeneralLog->setValorAntes($valorAntes);
            $GeneralLog->setValorDespues($valorDespues);

            $GeneralLog->setUsucreaId(0);
            $GeneralLog->setUsumodifId(0);
            $GeneralLog->setDispositivo($Global_dispositivo);

            /* establece propiedades de un objeto GeneralLog utilizando variables globales y datos de ProductoMandante. */
            $GeneralLog->setSoperativo($Global_soperativo);
            $GeneralLog->setSversion($Global_sversion);
            $GeneralLog->setTabla($tabla);
            $GeneralLog->setCampo($campo);
            $GeneralLog->setExternoId($ProductoMandante->prodmandanteId);
            $GeneralLog->setMandante($ProductoMandante->mandante);

            /* establece una explicación y registra un log en MySQL. */
            $GeneralLog->setExplicacion($Reason);
            $GeneralLogMySqlDAO = new GeneralLogMySqlDAO($Transaction);
            $GeneralLogMySqlDAO->insert($GeneralLog);

            $ProductoMandante->max = $Maximum;
        }

        if ($Minimum != "" && $ProductoMandante->min != $Minimum) {


            /* Se registra un cambio en el campo 'min' de 'producto_mandante'. */
            $tipo = 'CHANGEFIELD';
            $tabla = 'producto_mandante';
            $campo = 'min';
            $valorAntes = $ProductoMandante->min;
            $valorDespues = $Minimum;

            $GeneralLog = new GeneralLog();

            /* Configura propiedades de registro general con ID de usuario, IP y estado activo. */
            $GeneralLog->setUsuarioId($ClientId);
            $GeneralLog->setUsuarioIp($Global_IP);
            $GeneralLog->setUsuariosolicitaId($ClientId);
            $GeneralLog->setUsuariosolicitaIp($Global_IP);
            $GeneralLog->setTipo($tipo);
            $GeneralLog->setEstado("A");


            /* Se configuran valores y usuarios en el objeto GeneralLog para registro de cambios. */
            $GeneralLog->setValorAntes($valorAntes);
            $GeneralLog->setValorDespues($valorDespues);

            $GeneralLog->setUsucreaId(0);
            $GeneralLog->setUsumodifId(0);
            $GeneralLog->setDispositivo($Global_dispositivo);

            /* Configuración de parámetros para un registro en un sistema de logging general. */
            $GeneralLog->setSoperativo($Global_soperativo);
            $GeneralLog->setSversion($Global_sversion);
            $GeneralLog->setTabla($tabla);
            $GeneralLog->setCampo($campo);
            $GeneralLog->setExternoId($ProductoMandante->prodmandanteId);
            $GeneralLog->setMandante($ProductoMandante->mandante);

            /* registra un evento en el log y establece un valor mínimo para un producto. */
            $GeneralLog->setExplicacion($Reason);
            $GeneralLogMySqlDAO = new GeneralLogMySqlDAO($Transaction);
            $GeneralLogMySqlDAO->insert($GeneralLog);

            $ProductoMandante->min = $Minimum;
        }

        if ($ProcessingTime != "" && $ProductoMandante->tiempoProcesamiento != $ProcessingTime) {


            /* Se registra un cambio en el campo 'tiempoProcesamiento' de 'producto_mandante'. */
            $tipo = 'CHANGEFIELD';
            $tabla = 'producto_mandante';
            $campo = 'tiempoProcesamiento';
            $valorAntes = $ProductoMandante->tiempoProcesamiento;
            $valorDespues = $ProcessingTime;

            $GeneralLog = new GeneralLog();

            /* Configura un registro general con datos de usuario e información específica del cliente. */
            $GeneralLog->setUsuarioId($ClientId);
            $GeneralLog->setUsuarioIp($Global_IP);
            $GeneralLog->setUsuariosolicitaId($ClientId);
            $GeneralLog->setUsuariosolicitaIp($Global_IP);
            $GeneralLog->setTipo($tipo);
            $GeneralLog->setEstado("A");


            /* Configura valores, usuarios y dispositivo en un objeto GeneralLog. */
            $GeneralLog->setValorAntes($valorAntes);
            $GeneralLog->setValorDespues($valorDespues);

            $GeneralLog->setUsucreaId(0);
            $GeneralLog->setUsumodifId(0);
            $GeneralLog->setDispositivo($Global_dispositivo);

            /* Se configuran parámetros de un objeto de registro general. */
            $GeneralLog->setSoperativo($Global_soperativo);
            $GeneralLog->setSversion($Global_sversion);
            $GeneralLog->setTabla($tabla);
            $GeneralLog->setCampo($campo);
            $GeneralLog->setExternoId($ProductoMandante->prodmandanteId);
            $GeneralLog->setMandante($ProductoMandante->mandante);

            /* registra un log general y establece el tiempo de procesamiento de un producto. */
            $GeneralLog->setExplicacion($Reason);
            $GeneralLogMySqlDAO = new GeneralLogMySqlDAO($Transaction);
            $GeneralLogMySqlDAO->insert($GeneralLog);

            $ProductoMandante->tiempoProcesamiento = $ProcessingTime;
        }

        if ($Order != "" && $ProductoMandante->orden != $Order) {


            /* Se configura un cambio en un campo de la tabla 'producto_mandante'. */
            $tipo = 'CHANGEFIELD';
            $tabla = 'producto_mandante';
            $campo = 'orden';
            $valorAntes = $ProductoMandante->orden;
            $valorDespues = $Order;

            $GeneralLog = new GeneralLog();

            /* configura un registro general con detalles de usuario y estado. */
            $GeneralLog->setUsuarioId($ClientId);
            $GeneralLog->setUsuarioIp($Global_IP);
            $GeneralLog->setUsuariosolicitaId($ClientId);
            $GeneralLog->setUsuariosolicitaIp($Global_IP);
            $GeneralLog->setTipo($tipo);
            $GeneralLog->setEstado("A");


            /* Configura valores y usuarios para un objeto de registro general en el log. */
            $GeneralLog->setValorAntes($valorAntes);
            $GeneralLog->setValorDespues($valorDespues);

            $GeneralLog->setUsucreaId(0);
            $GeneralLog->setUsumodifId(0);
            $GeneralLog->setDispositivo($Global_dispositivo);

            /* Se configuran atributos de un objeto GeneralLog con datos globales y específicos. */
            $GeneralLog->setSoperativo($Global_soperativo);
            $GeneralLog->setSversion($Global_sversion);
            $GeneralLog->setTabla($tabla);
            $GeneralLog->setCampo($campo);
            $GeneralLog->setExternoId($ProductoMandante->prodmandanteId);
            $GeneralLog->setMandante($ProductoMandante->mandante);

            /* inserta un registro de log y asigna una orden a un producto. */
            $GeneralLog->setExplicacion($Reason);
            $GeneralLogMySqlDAO = new GeneralLogMySqlDAO($Transaction);
            $GeneralLogMySqlDAO->insert($GeneralLog);

            $ProductoMandante->orden = $Order;
        }

        if ($FeaturedOrder != "" && $ProductoMandante->ordenDestacado != $FeaturedOrder) {


            /* Código para registrar un cambio en el campo 'ordenDestacado' de 'producto_mandante'. */
            $tipo = 'CHANGEFIELD';
            $tabla = 'producto_mandante';
            $campo = 'ordenDestacado';
            $valorAntes = $ProductoMandante->ordenDestacado;
            $valorDespues = $FeaturedOrder;

            $GeneralLog = new GeneralLog();

            /* configura un registro general con información de usuario y estado. */
            $GeneralLog->setUsuarioId($ClientId);
            $GeneralLog->setUsuarioIp($Global_IP);
            $GeneralLog->setUsuariosolicitaId($ClientId);
            $GeneralLog->setUsuariosolicitaIp($Global_IP);
            $GeneralLog->setTipo($tipo);
            $GeneralLog->setEstado("A");


            /* establece valores y usuarios en un objeto de registro general. */
            $GeneralLog->setValorAntes($valorAntes);
            $GeneralLog->setValorDespues($valorDespues);

            $GeneralLog->setUsucreaId(0);
            $GeneralLog->setUsumodifId(0);
            $GeneralLog->setDispositivo($Global_dispositivo);

            /* Se configuran propiedades del objeto GeneralLog con datos de operación y producto. */
            $GeneralLog->setSoperativo($Global_soperativo);
            $GeneralLog->setSversion($Global_sversion);
            $GeneralLog->setTabla($tabla);
            $GeneralLog->setCampo($campo);
            $GeneralLog->setExternoId($ProductoMandante->prodmandanteId);
            $GeneralLog->setMandante($ProductoMandante->mandante);

            /* Registra un log general en base de datos y asigna un campo destacado a un producto. */
            $GeneralLog->setExplicacion($Reason);
            $GeneralLogMySqlDAO = new GeneralLogMySqlDAO($Transaction);
            $GeneralLogMySqlDAO->insert($GeneralLog);

            $ProductoMandante->ordenDestacado = $FeaturedOrder;
        }

        if ($Rows != "" && $ProductoMandante->numFila != $Rows) {


            /* Cambio de valor del campo 'numFila' en la tabla 'producto_mandante'. */
            $tipo = 'CHANGEFIELD';
            $tabla = 'producto_mandante';
            $campo = 'numFila';

            $valorAntes = $ProductoMandante->numFila;
            $valorDespues = $Rows;


            /* Crea un registro general configurando usuario, IP y tipo de solicitud. */
            $GeneralLog = new GeneralLog();
            $GeneralLog->setUsuarioId($ClientId);
            $GeneralLog->setUsuarioIp($Global_IP);
            $GeneralLog->setUsuariosolicitaId($ClientId);
            $GeneralLog->setUsuariosolicitaIp($Global_IP);
            $GeneralLog->setTipo($tipo);

            /* establece propiedades en un objeto 'GeneralLog', incluyendo estado y valores. */
            $GeneralLog->setEstado("A");

            $GeneralLog->setValorAntes($valorAntes);
            $GeneralLog->setValorDespues($valorDespues);

            $GeneralLog->setUsucreaId(0);

            /* Configuración de un registro general con datos específicos en un sistema. */
            $GeneralLog->setUsumodifId(0);
            $GeneralLog->setDispositivo($Global_dispositivo);
            $GeneralLog->setSoperativo($Global_soperativo);
            $GeneralLog->setSversion($Global_sversion);
            $GeneralLog->setTabla($tabla);
            $GeneralLog->setCampo($campo);

            /* Se registra un log general con datos de un producto mandante y razón. */
            $GeneralLog->setExternoId($ProductoMandante->prodmandanteId);
            $GeneralLog->setMandante($ProductoMandante->mandante);
            $GeneralLog->setExplicacion($Reason);

            $GeneralLogMySqlDAO = new GeneralLogMySqlDAO($Transaction);
            $GeneralLogMySqlDAO->insert($GeneralLog);


            /* Asigna el valor de $Rows a la propiedad numFila del objeto $ProductoMandante. */
            $ProductoMandante->numFila = $Rows;
        }

        if ($Order != "" && $ProductoMandante->numColumna != $Columns) {


            /* Modifica el campo 'numColumna' en 'producto_mandante' y registra el cambio. */
            $tipo = 'CHANGEFIELD';
            $tabla = 'producto_mandante';
            $campo = 'numColumna';
            $valorAntes = $ProductoMandante->numColumna;
            $valorDespues = $Columns;

            $GeneralLog = new GeneralLog();

            /* Configura un registro general con datos de usuario, IP y estado activo. */
            $GeneralLog->setUsuarioId($ClientId);
            $GeneralLog->setUsuarioIp($Global_IP);
            $GeneralLog->setUsuariosolicitaId($ClientId);
            $GeneralLog->setUsuariosolicitaIp($Global_IP);
            $GeneralLog->setTipo($tipo);
            $GeneralLog->setEstado("A");


            /* Se asignan valores y usuarios a un registro de log general. */
            $GeneralLog->setValorAntes($valorAntes);
            $GeneralLog->setValorDespues($valorDespues);

            $GeneralLog->setUsucreaId(0);
            $GeneralLog->setUsumodifId(0);
            $GeneralLog->setDispositivo($Global_dispositivo);

            /* establece propiedades de un objeto `GeneralLog` utilizando variables globales y datos de producto. */
            $GeneralLog->setSoperativo($Global_soperativo);
            $GeneralLog->setSversion($Global_sversion);
            $GeneralLog->setTabla($tabla);
            $GeneralLog->setCampo($campo);
            $GeneralLog->setExternoId($ProductoMandante->prodmandanteId);
            $GeneralLog->setMandante($ProductoMandante->mandante);

            /* registra un log y establece un número de columna en un producto. */
            $GeneralLog->setExplicacion($Reason);
            $GeneralLogMySqlDAO = new GeneralLogMySqlDAO($Transaction);
            $GeneralLogMySqlDAO->insert($GeneralLog);

            $ProductoMandante->numColumna = $Columns;
        }


        /* Actualiza un producto y gestiona la base de datos de un proveedor de casino. */
        $ProductoMandanteMySqlDAO->update($ProductoMandante);
        $ProductoMandanteMySqlDAO->getTransaction()->commit();
        $insertOrUpdate = true;


        if ($insertOrUpdate) {
            $CMSProveedor = new \Backend\cms\CMSProveedor('CASINO', '', $Partner);
            $CMSProveedor->updateDatabaseCasino();
        }

        /* Código establece una respuesta sin errores, con tipo de alerta "éxito". */
        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];


    }
}