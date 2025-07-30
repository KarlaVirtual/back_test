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
 * Este script se encarga de guardar y actualizar los detalles de un producto asociado a un partner.
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
 * @param string $params->Reason Razón o explicación para los cambios realizados.
 * @param string $params->FilterCountry Filtro de país asociado al producto.
 * @param string $params->BorderColor Color del borde del producto.
 * @param string $params->Partner Identificador del partner asociado al producto.
 * @param string $params->Country País asociado al producto.
 * @param string $params->Product Descripción del producto.
 * @param string $params->Commission Comisión asociada al producto.
 * @param string $params->Info Información adicional del producto.
 * 
 *
 * @return array $response Respuesta en formato JSON con los siguientes valores:
 *  - HasError (boolean): Indica si ocurrió un error.
 *  - AlertType (string): Tipo de alerta ('success', 'error', etc.).
 *  - AlertMessage (string): Mensaje de alerta.
 *  - ModelErrors (array): Lista de errores del modelo, si los hay.
 */

/* Se asigna el valor de $params->Id a la variable $Id. */
$Id = $params->Id;


if ($_POST["Id"] != "") {

    /* procesa datos POST y valida ciertos campos de activación y verificación. */
    $Id = $_POST["Id"];

    $IsActivate = ($_POST["IsActivate"] != "A" && $_POST["IsActivate"] != "I") ? "" : $_POST["IsActivate"];
    $IsVerified = ($_POST["IsVerified"] != "A" && $_POST["IsVerified"] != "I") ? "" : $_POST["IsVerified"];
    $Maximum = $_POST["Maximum"];
    $Minimum = $_POST["Minimum"];

    /* recibe y convierte valores de un formulario HTML mediante PHP. */
    $ProcessingTime = $_POST["ProcessingTime"];
    $Order = intval($_POST["Order"]);
    $FeaturedOrder = intval($_POST["FeaturedOrder"]);
    $Rows = intval($_POST["Rows"]);
    $Columns = intval($_POST["Columns"]);
    $Reason = $_POST["Reason"];

    /* obtiene datos enviados mediante POST, incluyendo colores, socios y países. */
    $BorderColor = $_POST["BorderColor"];
    $Partner = $_POST["Partner"];
    $Country = $_POST["Country"];

    $Image = $_POST["upload_fullpath"];
    $FilterCountry = $_POST["FilterCountry"];

    /* Recibe datos de un formulario HTML mediante el método POST en PHP. */
    $Product = $_POST["Product"];
    $Commission = $_POST["Commission"];

    $Info = $_POST["Info"];
    if ($Image != "") {


        /* Se crea un objeto ProductoMandante y se obtiene el nombre y tipo de un archivo subido. */
        $productoMandante = new ProductoMandante('', '', $Id, '');
        $Producto = new Producto($productoMandante->productoId);

        $Filename = $_FILES['upload']['name'];

        $Filetype = $_FILES['upload']['type'];


        /* asigna "png" o "gift" según el tipo de archivo. */
        $fileTypeName = "";

        if ($Filetype != "image/gif") {
            $fileTypeName = "png";
        } else {
            $fileTypeName = "gift";
        }

        /* Genera un nombre de archivo a partir de la descripción del producto, reemplazando caracteres. */
        $name = $Producto->getDescripcion();
        $name = str_replace('-', '', $name);
        $name = str_replace(' ', '-', $name);

        $filename = $name . "-" . time() . '.' . $fileTypeName;

        $dirsave = '/tmp/' . $filename;


        /* Verifica tipo de archivo, mueve y sube imagen a Google Cloud, actualiza la base de datos. */
        if ($Filetype == "image/jpeg" || $Filetype == "image/png" || $Filetype == 'image/gif') {

            if (move_uploaded_file($_FILES['upload']['tmp_name'], $dirsave)) {
                shell_exec('export BOTO_CONFIG=/home/backend/.config/gcloud/legacy_credentials/admin@bpoconsultores.com.co/.boto && export PATH=/sbin:/bin:/usr/sbin:/usr/bin:/home/backend/google-cloud-sdk/bin && gsutil -h "Cache-Control:public,max-age=604800" cp /tmp/' . $filename . ' gs://virtualcdnrealbucket/productos/');
                $productoUrl = 'https://images.virtualsoft.tech/productos/' . $filename;
            }

            //    $productoUrl="https://images.virtualsoft.tech/productos/".$filename;

            $productoMandante->setImage($productoUrl);

            $ProductoMandanteMySqlDAO = new ProductoMandanteMySqlDAO();
            $transaction = $ProductoMandanteMySqlDAO->getTransaction();
            $ProductoMandanteMySqlDAO->update($productoMandante);
            $ProductoMandanteMySqlDAO->getTransaction()->commit();

        }

    }


    /* Se crean instancias para manejar productos y transacciones en una base de datos. */
    $ProductoMandante = new ProductoMandante("", "", $Id);
    $ProductoMandanteMySqlDAO = new ProductoMandanteMySqlDAO();
    $Transaction = $ProductoMandanteMySqlDAO->getTransaction();
    $ClientId = $_SESSION['usuario2'];

    $Producto = new Producto($ProductoMandante->productoId);

    /* Inicializa un objeto DAO y obtiene una transacción para insertar o actualizar datos. */
    $ProductoMySqlDAO = new ProductoMySqlDAO();
    $Transaction = $ProductoMySqlDAO->getTransaction();

    $insertOrUpdate = false;

    if ($IsActivate != "" && $ProductoMandante->estado != $IsActivate) {


        /* Registro de cambios en el campo 'estado' de 'producto_mandante' mediante GeneralLog. */
        $tipo = 'CHANGEFIELD';
        $tabla = 'producto_mandante';
        $campo = 'estado';
        $valorAntes = $ProductoMandante->estado;
        $valorDespues = $IsActivate;

        $GeneralLog = new GeneralLog();

        /* Configura un registro general con información del usuario y tipo de acción. */
        $GeneralLog->setUsuarioId($ClientId);
        $GeneralLog->setUsuarioIp($Global_IP);
        $GeneralLog->setUsuariosolicitaId($ClientId);
        $GeneralLog->setUsuariosolicitaIp($Global_IP);
        $GeneralLog->setTipo($tipo);
        $GeneralLog->setEstado("A");


        /* Establece valores y asigna identificaciones en un objeto de registro general. */
        $GeneralLog->setValorAntes($valorAntes);
        $GeneralLog->setValorDespues($valorDespues);

        $GeneralLog->setUsucreaId(0);
        $GeneralLog->setUsumodifId(0);
        $GeneralLog->setDispositivo($Global_dispositivo);

        /* establece propiedades en el objeto GeneralLog utilizando variables globales y parámetros. */
        $GeneralLog->setSoperativo($Global_soperativo);
        $GeneralLog->setSversion($Global_sversion);
        $GeneralLog->setTabla($tabla);
        $GeneralLog->setCampo($campo);
        $GeneralLog->setExternoId($ProductoMandante->prodmandanteId);
        $GeneralLog->setMandante($ProductoMandante->mandante);

        /* Código registra un log general y actualiza el estado de un producto. */
        $GeneralLog->setExplicacion($Reason);
        $GeneralLogMySqlDAO = new GeneralLogMySqlDAO($Transaction);
        $GeneralLogMySqlDAO->insert($GeneralLog);


        $ProductoMandante->estado = $IsActivate;


    }

    if ($Info != "" && $Info != $ProductoMandante->extraInfo) {

        /* Código para registrar un cambio en el campo 'extra_info' de 'producto_mandante'. */
        $tipo = 'CHANGEFIELD';
        $tabla = 'producto_mandante';
        $campo = 'extra_info';
        $valorAntes = $ProductoMandante->extrainfo;
        $valorDespues = $Info;

        $GeneralLog = new GeneralLog();

        /* Registro de información de usuario y estado en el sistema de logs general. */
        $GeneralLog->setUsuarioId($ClientId);
        $GeneralLog->setUsuarioIp($Global_IP);
        $GeneralLog->setUsuariosolicitaId($ClientId);
        $GeneralLog->setUsuariosolicitaIp($Global_IP);
        $GeneralLog->setTipo($tipo);
        $GeneralLog->setEstado("A");


        /* Configura valores previos y posteriores en un registro general de logs. */
        $GeneralLog->setValorAntes($valorAntes);
        $GeneralLog->setValorDespues($valorDespues);

        $GeneralLog->setUsucreaId(0);
        $GeneralLog->setUsumodifId(0);
        $GeneralLog->setDispositivo($Global_dispositivo);

        /* Configura propiedades de un objeto 'GeneralLog' usando datos globales y un producto específico. */
        $GeneralLog->setSoperativo($Global_soperativo);
        $GeneralLog->setSversion($Global_sversion);
        $GeneralLog->setTabla($tabla);
        $GeneralLog->setCampo($campo);
        $GeneralLog->setExternoId($ProductoMandante->prodmandanteId);
        $GeneralLog->setMandante($ProductoMandante->mandante);

        /* registra información en un log y asigna datos adicionales a un producto. */
        $GeneralLog->setExplicacion($Reason);
        $GeneralLogMySqlDAO = new GeneralLogMySqlDAO($Transaction);
        $GeneralLogMySqlDAO->insert($GeneralLog);

        $ProductoMandante->extrainfo = $Info;
    }


    if ($IsVerified != "" && $ProductoMandante->verifica != $IsVerified) {


        /* Código para registrar un cambio en el campo 'verifica' de 'producto_mandante'. */
        $tipo = 'CHANGEFIELD';
        $tabla = 'producto_mandante';
        $campo = 'verifica';
        $valorAntes = $ProductoMandante->verifica;
        $valorDespues = $IsVerified;

        $GeneralLog = new GeneralLog();

        /* configura un registro general con identificadores y estado del usuario. */
        $GeneralLog->setUsuarioId($ClientId);
        $GeneralLog->setUsuarioIp($Global_IP);
        $GeneralLog->setUsuariosolicitaId($ClientId);
        $GeneralLog->setUsuariosolicitaIp($Global_IP);
        $GeneralLog->setTipo($tipo);
        $GeneralLog->setEstado("A");


        /* Código que establece valores y configuraciones para un registro general de log. */
        $GeneralLog->setValorAntes($valorAntes);
        $GeneralLog->setValorDespues($valorDespues);

        $GeneralLog->setUsucreaId(0);
        $GeneralLog->setUsumodifId(0);
        $GeneralLog->setDispositivo($Global_dispositivo);

        /* Configuración de parámetros en un objeto `GeneralLog` para registro de datos. */
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


        /* asigna valores para registrar un cambio en el campo 'filtroPais'. */
        $tipo = 'CHANGEFIELD';
        $tabla = 'producto_mandante';
        $campo = 'filtroPais';
        $valorAntes = $ProductoMandante->filtroPais;
        $valorDespues = $FilterCountry;

        $GeneralLog = new GeneralLog();

        /* Se establece información del usuario y estado en el registro general. */
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

        /* Asignación de valores a propiedades de un objeto GeneralLog en PHP. */
        $GeneralLog->setSoperativo($Global_soperativo);
        $GeneralLog->setSversion($Global_sversion);
        $GeneralLog->setTabla($tabla);
        $GeneralLog->setCampo($campo);
        $GeneralLog->setExternoId($ProductoMandante->prodmandanteId);
        $GeneralLog->setMandante($ProductoMandante->mandante);

        /* Registra un log y aplica un filtro por país en el producto mandante. */
        $GeneralLog->setExplicacion($Reason);
        $GeneralLogMySqlDAO = new GeneralLogMySqlDAO($Transaction);
        $GeneralLogMySqlDAO->insert($GeneralLog);

        $ProductoMandante->filtroPais = $FilterCountry;
    }

    if ($Maximum != "" && $ProductoMandante->max != $Maximum) {


        /* Código que registra un cambio en el campo 'max' de 'producto_mandante'. */
        $tipo = 'CHANGEFIELD';
        $tabla = 'producto_mandante';
        $campo = 'max';
        $valorAntes = $ProductoMandante->max;
        $valorDespues = $Maximum;

        $GeneralLog = new GeneralLog();

        /* Establece atributos de un registro general de usuario en un sistema. */
        $GeneralLog->setUsuarioId($ClientId);
        $GeneralLog->setUsuarioIp($Global_IP);
        $GeneralLog->setUsuariosolicitaId($ClientId);
        $GeneralLog->setUsuariosolicitaIp($Global_IP);
        $GeneralLog->setTipo($tipo);
        $GeneralLog->setEstado("A");


        /* Establece valores y usuarios en un registro general de cambios. */
        $GeneralLog->setValorAntes($valorAntes);
        $GeneralLog->setValorDespues($valorDespues);

        $GeneralLog->setUsucreaId(0);
        $GeneralLog->setUsumodifId(0);
        $GeneralLog->setDispositivo($Global_dispositivo);

        /* configura propiedades del objeto GeneralLog utilizando datos globales y específicos. */
        $GeneralLog->setSoperativo($Global_soperativo);
        $GeneralLog->setSversion($Global_sversion);
        $GeneralLog->setTabla($tabla);
        $GeneralLog->setCampo($campo);
        $GeneralLog->setExternoId($ProductoMandante->prodmandanteId);
        $GeneralLog->setMandante($ProductoMandante->mandante);

        /* Se establece una explicación, se inserta en la base de datos y se define un máximo. */
        $GeneralLog->setExplicacion($Reason);
        $GeneralLogMySqlDAO = new GeneralLogMySqlDAO($Transaction);
        $GeneralLogMySqlDAO->insert($GeneralLog);

        $ProductoMandante->max = $Maximum;
    }

    if ($Minimum != "" && $ProductoMandante->min != $Minimum) {


        /* Código establece variables para registrar un cambio en un campo de base de datos. */
        $tipo = 'CHANGEFIELD';
        $tabla = 'producto_mandante';
        $campo = 'min';
        $valorAntes = $ProductoMandante->min;
        $valorDespues = $Minimum;

        $GeneralLog = new GeneralLog();

        /* Se configura un registro general con datos de usuario y estado activo. */
        $GeneralLog->setUsuarioId($ClientId);
        $GeneralLog->setUsuarioIp($Global_IP);
        $GeneralLog->setUsuariosolicitaId($ClientId);
        $GeneralLog->setUsuariosolicitaIp($Global_IP);
        $GeneralLog->setTipo($tipo);
        $GeneralLog->setEstado("A");


        /* Código para establecer valores y usuarios en un registro general de log. */
        $GeneralLog->setValorAntes($valorAntes);
        $GeneralLog->setValorDespues($valorDespues);

        $GeneralLog->setUsucreaId(0);
        $GeneralLog->setUsumodifId(0);
        $GeneralLog->setDispositivo($Global_dispositivo);

        /* configura propiedades de un objeto GeneralLog con datos específicos. */
        $GeneralLog->setSoperativo($Global_soperativo);
        $GeneralLog->setSversion($Global_sversion);
        $GeneralLog->setTabla($tabla);
        $GeneralLog->setCampo($campo);
        $GeneralLog->setExternoId($ProductoMandante->prodmandanteId);
        $GeneralLog->setMandante($ProductoMandante->mandante);

        /* registra un log general y establece un mínimo en un producto. */
        $GeneralLog->setExplicacion($Reason);
        $GeneralLogMySqlDAO = new GeneralLogMySqlDAO($Transaction);
        $GeneralLogMySqlDAO->insert($GeneralLog);

        $ProductoMandante->min = $Minimum;
    }

    if ($ProcessingTime != "" && $ProductoMandante->tiempoProcesamiento != $ProcessingTime) {


        /* Registro de cambio en el campo 'tiempoProcesamiento' de 'producto_mandante'. */
        $tipo = 'CHANGEFIELD';
        $tabla = 'producto_mandante';
        $campo = 'tiempoProcesamiento';
        $valorAntes = $ProductoMandante->tiempoProcesamiento;
        $valorDespues = $ProcessingTime;

        $GeneralLog = new GeneralLog();

        /* registra información del usuario y su estado en un log general. */
        $GeneralLog->setUsuarioId($ClientId);
        $GeneralLog->setUsuarioIp($Global_IP);
        $GeneralLog->setUsuariosolicitaId($ClientId);
        $GeneralLog->setUsuariosolicitaIp($Global_IP);
        $GeneralLog->setTipo($tipo);
        $GeneralLog->setEstado("A");


        /* establece valores y atributos en un objeto GeneralLog. */
        $GeneralLog->setValorAntes($valorAntes);
        $GeneralLog->setValorDespues($valorDespues);

        $GeneralLog->setUsucreaId(0);
        $GeneralLog->setUsumodifId(0);
        $GeneralLog->setDispositivo($Global_dispositivo);

        /* configura propiedades de un objeto de registro general. */
        $GeneralLog->setSoperativo($Global_soperativo);
        $GeneralLog->setSversion($Global_sversion);
        $GeneralLog->setTabla($tabla);
        $GeneralLog->setCampo($campo);
        $GeneralLog->setExternoId($ProductoMandante->prodmandanteId);
        $GeneralLog->setMandante($ProductoMandante->mandante);

        /* Se registra un log general y se establece un tiempo de procesamiento para el producto. */
        $GeneralLog->setExplicacion($Reason);
        $GeneralLogMySqlDAO = new GeneralLogMySqlDAO($Transaction);
        $GeneralLogMySqlDAO->insert($GeneralLog);

        $ProductoMandante->tiempoProcesamiento = $ProcessingTime;
    }

    if ($Order != "" && $ProductoMandante->orden != $Order) {


        /* Código para registrar un cambio de campo en la tabla 'producto_mandante'. */
        $tipo = 'CHANGEFIELD';
        $tabla = 'producto_mandante';
        $campo = 'orden';
        $valorAntes = $ProductoMandante->orden;
        $valorDespues = $Order;

        $GeneralLog = new GeneralLog();

        /* Configura propiedades de un registro general relacionado con un cliente y su IP. */
        $GeneralLog->setUsuarioId($ClientId);
        $GeneralLog->setUsuarioIp($Global_IP);
        $GeneralLog->setUsuariosolicitaId($ClientId);
        $GeneralLog->setUsuariosolicitaIp($Global_IP);
        $GeneralLog->setTipo($tipo);
        $GeneralLog->setEstado("A");


        /* Se configuran registros de log general con valores y usuario asociado. */
        $GeneralLog->setValorAntes($valorAntes);
        $GeneralLog->setValorDespues($valorDespues);

        $GeneralLog->setUsucreaId(0);
        $GeneralLog->setUsumodifId(0);
        $GeneralLog->setDispositivo($Global_dispositivo);

        /* Se configuran propiedades del objeto GeneralLog con datos del contexto actual. */
        $GeneralLog->setSoperativo($Global_soperativo);
        $GeneralLog->setSversion($Global_sversion);
        $GeneralLog->setTabla($tabla);
        $GeneralLog->setCampo($campo);
        $GeneralLog->setExternoId($ProductoMandante->prodmandanteId);
        $GeneralLog->setMandante($ProductoMandante->mandante);

        /* registra un log general y establece un orden para un producto. */
        $GeneralLog->setExplicacion($Reason);
        $GeneralLogMySqlDAO = new GeneralLogMySqlDAO($Transaction);
        $GeneralLogMySqlDAO->insert($GeneralLog);

        $ProductoMandante->orden = $Order;
    }

    if ($FeaturedOrder != "" && $ProductoMandante->ordenDestacado != $FeaturedOrder) {


        /* Se define un cambio en el campo 'ordenDestacado' de la tabla 'producto_mandante'. */
        $tipo = 'CHANGEFIELD';
        $tabla = 'producto_mandante';
        $campo = 'ordenDestacado';
        $valorAntes = $ProductoMandante->ordenDestacado;
        $valorDespues = $FeaturedOrder;

        $GeneralLog = new GeneralLog();

        /* Configura el registro general con detalles del usuario y estado inicial. */
        $GeneralLog->setUsuarioId($ClientId);
        $GeneralLog->setUsuarioIp($Global_IP);
        $GeneralLog->setUsuariosolicitaId($ClientId);
        $GeneralLog->setUsuariosolicitaIp($Global_IP);
        $GeneralLog->setTipo($tipo);
        $GeneralLog->setEstado("A");


        /* Configura valores anteriores y posteriores en el objeto GeneralLog, incluyendo usuario y dispositivo. */
        $GeneralLog->setValorAntes($valorAntes);
        $GeneralLog->setValorDespues($valorDespues);

        $GeneralLog->setUsucreaId(0);
        $GeneralLog->setUsumodifId(0);
        $GeneralLog->setDispositivo($Global_dispositivo);

        /* Configura propiedades de un objeto GeneralLog usando variables globales y datos del producto. */
        $GeneralLog->setSoperativo($Global_soperativo);
        $GeneralLog->setSversion($Global_sversion);
        $GeneralLog->setTabla($tabla);
        $GeneralLog->setCampo($campo);
        $GeneralLog->setExternoId($ProductoMandante->prodmandanteId);
        $GeneralLog->setMandante($ProductoMandante->mandante);

        /* Se registra un log general en la base de datos y se establece un orden destacado. */
        $GeneralLog->setExplicacion($Reason);
        $GeneralLogMySqlDAO = new GeneralLogMySqlDAO($Transaction);
        $GeneralLogMySqlDAO->insert($GeneralLog);

        $ProductoMandante->ordenDestacado = $FeaturedOrder;
    }

    if ($Rows != "" && $ProductoMandante->numFila != $Rows) {


        /* Asigna valores antes y después de un cambio de campo en una tabla específica. */
        $tipo = 'CHANGEFIELD';
        $tabla = 'producto_mandante';
        $campo = 'numFila';

        $valorAntes = $ProductoMandante->numFila;
        $valorDespues = $Rows;


        /* crea un registro general con información del usuario y su IP. */
        $GeneralLog = new GeneralLog();
        $GeneralLog->setUsuarioId($ClientId);
        $GeneralLog->setUsuarioIp($Global_IP);
        $GeneralLog->setUsuariosolicitaId($ClientId);
        $GeneralLog->setUsuariosolicitaIp($Global_IP);
        $GeneralLog->setTipo($tipo);

        /* Código para registrar un cambio en el log general con sus valores y creador. */
        $GeneralLog->setEstado("A");

        $GeneralLog->setValorAntes($valorAntes);
        $GeneralLog->setValorDespues($valorDespues);

        $GeneralLog->setUsucreaId(0);

        /* configura un objeto de registro general con varios parámetros específicos. */
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


        /* Asigna el valor de $Rows a la propiedad numFila del objeto $ProductoMandante. */
        $ProductoMandante->numFila = $Rows;
    }

    if ($Order != "" && $ProductoMandante->numColumna != $Columns) {


        /* Código para registrar un cambio en un campo específico de una tabla de productos. */
        $tipo = 'CHANGEFIELD';
        $tabla = 'producto_mandante';
        $campo = 'numColumna';
        $valorAntes = $ProductoMandante->numColumna;
        $valorDespues = $Columns;

        $GeneralLog = new GeneralLog();

        /* Se establece información del usuario y estado en el registro general. */
        $GeneralLog->setUsuarioId($ClientId);
        $GeneralLog->setUsuarioIp($Global_IP);
        $GeneralLog->setUsuariosolicitaId($ClientId);
        $GeneralLog->setUsuariosolicitaIp($Global_IP);
        $GeneralLog->setTipo($tipo);
        $GeneralLog->setEstado("A");


        /* establece valores y registros de un objeto GeneralLog. */
        $GeneralLog->setValorAntes($valorAntes);
        $GeneralLog->setValorDespues($valorDespues);

        $GeneralLog->setUsucreaId(0);
        $GeneralLog->setUsumodifId(0);
        $GeneralLog->setDispositivo($Global_dispositivo);

        /* Configura propiedades de un objeto GeneralLog utilizando variables globales y atributos de ProductoMandante. */
        $GeneralLog->setSoperativo($Global_soperativo);
        $GeneralLog->setSversion($Global_sversion);
        $GeneralLog->setTabla($tabla);
        $GeneralLog->setCampo($campo);
        $GeneralLog->setExternoId($ProductoMandante->prodmandanteId);
        $GeneralLog->setMandante($ProductoMandante->mandante);

        /* registra un log y asigna un número de columna a un producto. */
        $GeneralLog->setExplicacion($Reason);
        $GeneralLogMySqlDAO = new GeneralLogMySqlDAO($Transaction);
        $GeneralLogMySqlDAO->insert($GeneralLog);

        $ProductoMandante->numColumna = $Columns;
    }


    /* Se crea una nueva instancia de la clase Producto utilizando un identificador específico. */
    $Producto = new Producto($ProductoMandante->productoId);
    if ($Product != "" && $Product != $Producto->descripcion . " (" . $Producto->productoId . ")") {


        /* reemplaza una cadena en la descripción del producto y obtiene una transacción. */
        $cadena_a_quitar = " (" . $Producto->productoId . ")";
        $newDescripcion = str_replace($cadena_a_quitar, '', $Product);
        $ProductoMySqlDAO = new ProductoMySqlDAO();
        $Transaction = $ProductoMySqlDAO->getTransaction();
        $tipo = 'CHANGEFIELD';
        $tabla = 'producto';

        /* Código para registrar cambios en la descripción de un producto en el log general. */
        $campo = 'descripcion';
        $valorAntes = $ProductoMandante->numColumna;
        $valorDespues = $newDescripcion;

        $GeneralLog = new GeneralLog();
        $GeneralLog->setUsuarioId($ClientId);

        /* registra información de un usuario en el sistema de logs general. */
        $GeneralLog->setUsuarioIp($Global_IP);
        $GeneralLog->setUsuariosolicitaId($ClientId);
        $GeneralLog->setUsuariosolicitaIp($Global_IP);
        $GeneralLog->setTipo($tipo);
        $GeneralLog->setEstado("A");

        $GeneralLog->setValorAntes($valorAntes);

        /* Se configuran valores y datos de usuario en un objeto GeneralLog. */
        $GeneralLog->setValorDespues($valorDespues);

        $GeneralLog->setUsucreaId(0);
        $GeneralLog->setUsumodifId(0);
        $GeneralLog->setDispositivo($Global_dispositivo);
        $GeneralLog->setSoperativo($Global_soperativo);

        /* Se configuran parámetros en un objeto GeneralLog para registro de información. */
        $GeneralLog->setSversion($Global_sversion);
        $GeneralLog->setTabla($tabla);
        $GeneralLog->setCampo($campo);
        $GeneralLog->setExternoId($ProductoMandante->prodmandanteId);
        $GeneralLog->setMandante($ProductoMandante->mandante);
        $GeneralLog->setExplicacion($Reason);

        /* Se inserta un registro en el log y se actualiza la descripción del producto. */
        $GeneralLogMySqlDAO = new GeneralLogMySqlDAO($Transaction);
        $GeneralLogMySqlDAO->insert($GeneralLog);

        $Producto->setDescripcion($newDescripcion);
        $ProductoMySqlDAO->update($Producto);
        $ProductoMySqlDAO->getTransaction()->commit();
    }


    if ($BorderColor != "" && $productoMandante->borde != $BorderColor) {

        /* asigna valores relacionados a una modificación en la tabla 'producto_mandante'. */
        $tipo = 'CHANGEFIELD';
        $tabla = 'producto_mandante';
        $campo = 'borde';
        $valorAntes = $productoMandante->borde;
        $valorDespues = $BorderColor;
        $GeneralLog = new GeneralLog();

        /* Registro de actividad del usuario con información de ID, IP, tipo y estado. */
        $GeneralLog->setUsuarioId($ClientId);
        $GeneralLog->setUsuarioIp($Global_IP);
        $GeneralLog->setUsuariosolicitaId($ClientId);
        $GeneralLog->setUsuariosolicitaIp($Global_IP);
        $GeneralLog->setTipo($tipo);
        $GeneralLog->setEstado("A");


        /* Se establecen valores y usuario en el objeto GeneralLog. */
        $GeneralLog->setValorAntes($valorAntes);
        $GeneralLog->setValorDespues($valorDespues);

        $GeneralLog->setUsucreaId(0);
        $GeneralLog->setUsumodifId(0);
        $GeneralLog->setDispositivo($Global_dispositivo);

        /* Asignación de valores a propiedades del objeto GeneralLog utilizando datos globales y específicos. */
        $GeneralLog->setSoperativo($Global_soperativo);
        $GeneralLog->setSversion($Global_sversion);
        $GeneralLog->setTabla($tabla);
        $GeneralLog->setCampo($campo);
        $GeneralLog->setExternoId($ProductoMandante->prodmandanteId);
        $GeneralLog->setMandante($ProductoMandante->mandante);

        /* establece una explicación y registra datos en una base de datos MySQL. */
        $GeneralLog->setExplicacion($Reason);
        $GeneralLogMySqlDAO = new GeneralLogMySqlDAO($Transaction);
        $GeneralLogMySqlDAO->insert($GeneralLog);
        $productoMandante->borde = $BorderColor;

        $ProductoMandanteMySqlDAO = new ProductoMandanteMySqlDAO();


        /* Actualiza producto en base de datos y gestiona transacción con CMSProveedor. */
        $ProductoMandanteMySqlDAO->update($ProductoMandante);
        $ProductoMandanteMySqlDAO->getTransaction()->commit();
        $insertOrUpdate = true;


        if ($insertOrUpdate) {
            $CMSProveedor = new \Backend\cms\CMSProveedor('CASINO', '', $Partner);
            $CMSProveedor->updateDatabaseCasino();
        }
    }


    /* Código que inicializa un objeto de respuesta sin errores y con mensajes de éxito. */
    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];
} else {
    if ($Id != "") {

        /* extrae varios parámetros de un objeto para su posterior procesamiento. */
        $IsActivate = $params->IsActivate;
        $FilterCountry = $params->FilterCountry;
        $IsVerified = $params->IsVerified;
        $Maximum = $params->Maximum;
        $Minimum = $params->Minimum;
        $ProcessingTime = $params->ProcessingTime;

        /* Asignación de parámetros a variables con diferentes tipos de datos en PHP. */
        $Maximum = $params->Maximum;
        $Order = intval($params->Order);
        $FeaturedOrder = intval($params->FeaturedOrder);
        $Rows = intval($params->Rows);
        $Columns = intval($params->Columns);
        $Reason = $params->Reason;

        /* Valida y asigna valores a variables basadas en condiciones específicas. */
        $Info = $params->Info;
        $Commission = $params->Commission;

        $IsActivate = ($IsActivate != 'A' && $IsActivate != "I") ? '' : $IsActivate;
        $IsVerified = ($IsVerified != 'A' && $IsVerified != "I") ? '' : $IsVerified;
        $FilterCountry = ($FilterCountry != 'A' && $FilterCountry != "I") ? '' : $FilterCountry;


        /* Se crea un objeto y se prepara para interactuar con la base de datos. */
        $ProductoMandante = new ProductoMandante("", "", $Id);
        $ProductoMandanteMySqlDAO = new ProductoMandanteMySqlDAO();
        $Transaction = $ProductoMandanteMySqlDAO->getTransaction();
        $ClientId = $_SESSION['usuario2'];

        $insertOrUpdate = false;

        if ($IsActivate != "" && $ProductoMandante->estado != $IsActivate) {


            /* Se registra un cambio en el campo 'estado' de 'producto_mandante'. */
            $tipo = 'CHANGEFIELD';
            $tabla = 'producto_mandante';
            $campo = 'estado';
            $valorAntes = $ProductoMandante->estado;
            $valorDespues = $IsActivate;

            $GeneralLog = new GeneralLog();

            /* establece valores para un objeto de registro general, incluyendo usuario y estado. */
            $GeneralLog->setUsuarioId($ClientId);
            $GeneralLog->setUsuarioIp($Global_IP);
            $GeneralLog->setUsuariosolicitaId($ClientId);
            $GeneralLog->setUsuariosolicitaIp($Global_IP);
            $GeneralLog->setTipo($tipo);
            $GeneralLog->setEstado("A");


            /* establece valores y detalles de un registro general en un sistema. */
            $GeneralLog->setValorAntes($valorAntes);
            $GeneralLog->setValorDespues($valorDespues);

            $GeneralLog->setUsucreaId(0);
            $GeneralLog->setUsumodifId(0);
            $GeneralLog->setDispositivo($Global_dispositivo);

            /* Código que establece parámetros en un objeto GeneralLog para su registro. */
            $GeneralLog->setSoperativo($Global_soperativo);
            $GeneralLog->setSversion($Global_sversion);
            $GeneralLog->setTabla($tabla);
            $GeneralLog->setCampo($campo);
            $GeneralLog->setExternoId($ProductoMandante->prodmandanteId);
            $GeneralLog->setMandante($ProductoMandante->mandante);

            /* Se registran logs generales y se actualiza el estado del producto mandante. */
            $GeneralLog->setExplicacion($Reason);
            $GeneralLogMySqlDAO = new GeneralLogMySqlDAO($Transaction);
            $GeneralLogMySqlDAO->insert($GeneralLog);


            $ProductoMandante->estado = $IsActivate;
        }

        if ($Commission != "" and $ProductoMandante->valor != $Commission) {


            /* Se define un cambio de estado en la tabla 'producto_mandante' y se registra. */
            $tipo = 'CHANGEFIELD';
            $tabla = 'producto_mandante';
            $campo = 'estado';
            $valorAntes = $ProductoMandante->valor;
            $valorDespues = $Commission;

            $GeneralLog = new GeneralLog();

            /* registra información del usuario en un objeto de registro general. */
            $GeneralLog->setUsuarioId($ClientId);
            $GeneralLog->setUsuarioIp($Global_IP);
            $GeneralLog->setUsuariosolicitaId($ClientId);
            $GeneralLog->setUsuariosolicitaIp($Global_IP);
            $GeneralLog->setTipo($tipo);
            $GeneralLog->setEstado("A");


            /* establece valores y usuarios para un registro de log general. */
            $GeneralLog->setValorAntes($valorAntes);
            $GeneralLog->setValorDespues($valorDespues);

            $GeneralLog->setUsucreaId(0);
            $GeneralLog->setUsumodifId(0);
            $GeneralLog->setDispositivo($Global_dispositivo);

            /* Configura un objeto de registro general con parámetros específicos de un producto. */
            $GeneralLog->setSoperativo($Global_soperativo);
            $GeneralLog->setSversion($Global_sversion);
            $GeneralLog->setTabla($tabla);
            $GeneralLog->setCampo($campo);
            $GeneralLog->setExternoId($ProductoMandante->prodmandanteId);
            $GeneralLog->setMandante($ProductoMandante->mandante);

            /* registra un log general y asigna un valor a un producto. */
            $GeneralLog->setExplicacion($Reason);
            $GeneralLogMySqlDAO = new GeneralLogMySqlDAO($Transaction);
            $GeneralLogMySqlDAO->insert($GeneralLog);


            $ProductoMandante->valor = $Commission;
        }


        if ($IsVerified != "" && $ProductoMandante->verifica != $IsVerified) {


            /* Código que establece un cambio en el campo 'verifica' de 'producto_mandante'. */
            $tipo = 'CHANGEFIELD';
            $tabla = 'producto_mandante';
            $campo = 'verifica';
            $valorAntes = $ProductoMandante->verifica;
            $valorDespues = $IsVerified;

            $GeneralLog = new GeneralLog();

            /* establece propiedades del objeto GeneralLog usando datos del cliente y tipo. */
            $GeneralLog->setUsuarioId($ClientId);
            $GeneralLog->setUsuarioIp($Global_IP);
            $GeneralLog->setUsuariosolicitaId($ClientId);
            $GeneralLog->setUsuariosolicitaIp($Global_IP);
            $GeneralLog->setTipo($tipo);
            $GeneralLog->setEstado("A");


            /* Se asignan valores y atributos a un objeto GeneralLog para registro de cambios. */
            $GeneralLog->setValorAntes($valorAntes);
            $GeneralLog->setValorDespues($valorDespues);

            $GeneralLog->setUsucreaId(0);
            $GeneralLog->setUsumodifId(0);
            $GeneralLog->setDispositivo($Global_dispositivo);

            /* establece propiedades en un objeto GeneralLog utilizando variables globales y datos específicos. */
            $GeneralLog->setSoperativo($Global_soperativo);
            $GeneralLog->setSversion($Global_sversion);
            $GeneralLog->setTabla($tabla);
            $GeneralLog->setCampo($campo);
            $GeneralLog->setExternoId($ProductoMandante->prodmandanteId);
            $GeneralLog->setMandante($ProductoMandante->mandante);

            /* inserta un registro en el log general y verifica un producto. */
            $GeneralLog->setExplicacion($Reason);
            $GeneralLogMySqlDAO = new GeneralLogMySqlDAO($Transaction);
            $GeneralLogMySqlDAO->insert($GeneralLog);

            $ProductoMandante->verifica = $IsVerified;
        }

        if ($FilterCountry != "" && $ProductoMandante->filtroPais != $FilterCountry) {


            /* configura variables para actualizar un campo en la base de datos. */
            $tipo = 'CHANGEFIELD';
            $tabla = 'producto_mandante';
            $campo = 'filtroPais';
            $valorAntes = $ProductoMandante->filtroPais;
            $valorDespues = $FilterCountry;

            $GeneralLog = new GeneralLog();

            /* Se configura un registro general con datos del usuario y su estado. */
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

            /* Configura un registro general con parámetros de operación y producto mandante. */
            $GeneralLog->setSoperativo($Global_soperativo);
            $GeneralLog->setSversion($Global_sversion);
            $GeneralLog->setTabla($tabla);
            $GeneralLog->setCampo($campo);
            $GeneralLog->setExternoId($ProductoMandante->prodmandanteId);
            $GeneralLog->setMandante($ProductoMandante->mandante);

            /* registra un log general y establece un filtro de país para un producto. */
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

            /* Código que establece propiedades de un registro general de usuario en el sistema. */
            $GeneralLog->setUsuarioId($ClientId);
            $GeneralLog->setUsuarioIp($Global_IP);
            $GeneralLog->setUsuariosolicitaId($ClientId);
            $GeneralLog->setUsuariosolicitaIp($Global_IP);
            $GeneralLog->setTipo($tipo);
            $GeneralLog->setEstado("A");


            /* Se establecen valores y atributos en un objeto de registro general. */
            $GeneralLog->setValorAntes($valorAntes);
            $GeneralLog->setValorDespues($valorDespues);

            $GeneralLog->setUsucreaId(0);
            $GeneralLog->setUsumodifId(0);
            $GeneralLog->setDispositivo($Global_dispositivo);

            /* Configuración de un objeto de log con propiedades específicas de un producto. */
            $GeneralLog->setSoperativo($Global_soperativo);
            $GeneralLog->setSversion($Global_sversion);
            $GeneralLog->setTabla($tabla);
            $GeneralLog->setCampo($campo);
            $GeneralLog->setExternoId($ProductoMandante->prodmandanteId);
            $GeneralLog->setMandante($ProductoMandante->mandante);

            /* Se establece una explicación y se registra en la base de datos. */
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

            /* establece propiedades de registro para un usuario en un sistema. */
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

            /* Se configuran propiedades de un objeto GeneralLog con datos específicos. */
            $GeneralLog->setSoperativo($Global_soperativo);
            $GeneralLog->setSversion($Global_sversion);
            $GeneralLog->setTabla($tabla);
            $GeneralLog->setCampo($campo);
            $GeneralLog->setExternoId($ProductoMandante->prodmandanteId);
            $GeneralLog->setMandante($ProductoMandante->mandante);

            /* registra un log y establece un mínimo para un producto. */
            $GeneralLog->setExplicacion($Reason);
            $GeneralLogMySqlDAO = new GeneralLogMySqlDAO($Transaction);
            $GeneralLogMySqlDAO->insert($GeneralLog);

            $ProductoMandante->min = $Minimum;
        }


        if ($Info != "" && $Info != $ProductoMandante->extrainfo) {

            /* define variables para registrar un cambio en un campo de una tabla. */
            $tipo = 'CHANGEFIELD';
            $tabla = 'producto_mandante';
            $campo = 'tiempoProcesamiento';
            $valorAntes = $ProductoMandante->extrainfo;
            $valorDespues = $Info;
            $GeneralLog = new GeneralLog();

            /* Configura un registro general con información del usuario y estado activo. */
            $GeneralLog->setUsuarioId($ClientId);
            $GeneralLog->setUsuarioIp($Global_IP);
            $GeneralLog->setUsuariosolicitaId($ClientId);
            $GeneralLog->setUsuariosolicitaIp($Global_IP);
            $GeneralLog->setTipo($tipo);
            $GeneralLog->setEstado("A");


            /* Se establecen valores y propiedades en un objeto GeneralLog. */
            $GeneralLog->setValorAntes($valorAntes);
            $GeneralLog->setValorDespues($valorDespues);

            $GeneralLog->setUsucreaId(0);
            $GeneralLog->setUsumodifId(0);
            $GeneralLog->setDispositivo($Global_dispositivo);

            /* Configura registros generales utilizando datos específicos de un producto mandante. */
            $GeneralLog->setSoperativo($Global_soperativo);
            $GeneralLog->setSversion($Global_sversion);
            $GeneralLog->setTabla($tabla);
            $GeneralLog->setCampo($campo);
            $GeneralLog->setExternoId($ProductoMandante->prodmandanteId);
            $GeneralLog->setMandante($ProductoMandante->mandante);

            /* registra un log y establece información adicional para un producto. */
            $GeneralLog->setExplicacion($Reason);
            $GeneralLogMySqlDAO = new GeneralLogMySqlDAO($Transaction);
            $GeneralLogMySqlDAO->insert($GeneralLog);
            $ProductoMandante->setExtraInfo($Info);
        }


        if ($ProcessingTime != "" && $ProductoMandante->tiempoProcesamiento != $ProcessingTime) {


            /* Código para registrar un cambio en el campo 'tiempoProcesamiento' de 'producto_mandante'. */
            $tipo = 'CHANGEFIELD';
            $tabla = 'producto_mandante';
            $campo = 'tiempoProcesamiento';
            $valorAntes = $ProductoMandante->tiempoProcesamiento;
            $valorDespues = $ProcessingTime;

            $GeneralLog = new GeneralLog();

            /* Configura un registro general con información del usuario y su estado. */
            $GeneralLog->setUsuarioId($ClientId);
            $GeneralLog->setUsuarioIp($Global_IP);
            $GeneralLog->setUsuariosolicitaId($ClientId);
            $GeneralLog->setUsuariosolicitaIp($Global_IP);
            $GeneralLog->setTipo($tipo);
            $GeneralLog->setEstado("A");


            /* Asignación de valores y configuración de propiedades en el objeto GeneralLog. */
            $GeneralLog->setValorAntes($valorAntes);
            $GeneralLog->setValorDespues($valorDespues);

            $GeneralLog->setUsucreaId(0);
            $GeneralLog->setUsumodifId(0);
            $GeneralLog->setDispositivo($Global_dispositivo);

            /* Configura propiedades de un objeto GeneralLog con datos globales y específicos. */
            $GeneralLog->setSoperativo($Global_soperativo);
            $GeneralLog->setSversion($Global_sversion);
            $GeneralLog->setTabla($tabla);
            $GeneralLog->setCampo($campo);
            $GeneralLog->setExternoId($ProductoMandante->prodmandanteId);
            $GeneralLog->setMandante($ProductoMandante->mandante);

            /* Se registra un log general y se establece tiempo de procesamiento del producto. */
            $GeneralLog->setExplicacion($Reason);
            $GeneralLogMySqlDAO = new GeneralLogMySqlDAO($Transaction);
            $GeneralLogMySqlDAO->insert($GeneralLog);

            $ProductoMandante->tiempoProcesamiento = $ProcessingTime;
        }

        if ($Order != "" && $ProductoMandante->orden != $Order) {


            /* Código para registrar un cambio de orden en el producto mandante. */
            $tipo = 'CHANGEFIELD';
            $tabla = 'producto_mandante';
            $campo = 'orden';
            $valorAntes = $ProductoMandante->orden;
            $valorDespues = $Order;

            $GeneralLog = new GeneralLog();

            /* Registra información del usuario y estado en un objeto GeneralLog. */
            $GeneralLog->setUsuarioId($ClientId);
            $GeneralLog->setUsuarioIp($Global_IP);
            $GeneralLog->setUsuariosolicitaId($ClientId);
            $GeneralLog->setUsuariosolicitaIp($Global_IP);
            $GeneralLog->setTipo($tipo);
            $GeneralLog->setEstado("A");


            /* Se establecen valores y usuarios en el objeto GeneralLog para registro de cambios. */
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

            /* registra una explicación y guarda datos de un log en MySQL. */
            $GeneralLog->setExplicacion($Reason);
            $GeneralLogMySqlDAO = new GeneralLogMySqlDAO($Transaction);
            $GeneralLogMySqlDAO->insert($GeneralLog);

            $ProductoMandante->orden = $Order;
        }

        if ($FeaturedOrder != "" && $ProductoMandante->ordenDestacado != $FeaturedOrder) {


            /* establece variables para registrar un cambio en un campo de base de datos. */
            $tipo = 'CHANGEFIELD';
            $tabla = 'producto_mandante';
            $campo = 'ordenDestacado';
            $valorAntes = $ProductoMandante->ordenDestacado;
            $valorDespues = $FeaturedOrder;

            $GeneralLog = new GeneralLog();

            /* Configura un registro general con información del usuario y estado de actividad. */
            $GeneralLog->setUsuarioId($ClientId);
            $GeneralLog->setUsuarioIp($Global_IP);
            $GeneralLog->setUsuariosolicitaId($ClientId);
            $GeneralLog->setUsuariosolicitaIp($Global_IP);
            $GeneralLog->setTipo($tipo);
            $GeneralLog->setEstado("A");


            /* Se configura un registro general con valores, usuarios y dispositivo específicos. */
            $GeneralLog->setValorAntes($valorAntes);
            $GeneralLog->setValorDespues($valorDespues);

            $GeneralLog->setUsucreaId(0);
            $GeneralLog->setUsumodifId(0);
            $GeneralLog->setDispositivo($Global_dispositivo);

            /* Configura registros generales con información específica del producto y mandante. */
            $GeneralLog->setSoperativo($Global_soperativo);
            $GeneralLog->setSversion($Global_sversion);
            $GeneralLog->setTabla($tabla);
            $GeneralLog->setCampo($campo);
            $GeneralLog->setExternoId($ProductoMandante->prodmandanteId);
            $GeneralLog->setMandante($ProductoMandante->mandante);

            /* Se registra una explicación y se inserta un log en la base de datos. */
            $GeneralLog->setExplicacion($Reason);
            $GeneralLogMySqlDAO = new GeneralLogMySqlDAO($Transaction);
            $GeneralLogMySqlDAO->insert($GeneralLog);

            $ProductoMandante->ordenDestacado = $FeaturedOrder;
        }

        if ($Rows != "" && $ProductoMandante->numFila != $Rows) {


            /* asigna variables relacionadas con un cambio de campo en una tabla. */
            $tipo = 'CHANGEFIELD';
            $tabla = 'producto_mandante';
            $campo = 'numFila';

            $valorAntes = $ProductoMandante->numFila;
            $valorDespues = $Rows;


            /* Registra información de usuario en un objeto GeneralLog con varios parámetros. */
            $GeneralLog = new GeneralLog();
            $GeneralLog->setUsuarioId($ClientId);
            $GeneralLog->setUsuarioIp($Global_IP);
            $GeneralLog->setUsuariosolicitaId($ClientId);
            $GeneralLog->setUsuariosolicitaIp($Global_IP);
            $GeneralLog->setTipo($tipo);

            /* Se configura un registro de log con estado, valores anteriores y posteriores. */
            $GeneralLog->setEstado("A");

            $GeneralLog->setValorAntes($valorAntes);
            $GeneralLog->setValorDespues($valorDespues);

            $GeneralLog->setUsucreaId(0);

            /* Establece propiedades de un objeto GeneralLog con valores globales y específicos. */
            $GeneralLog->setUsumodifId(0);
            $GeneralLog->setDispositivo($Global_dispositivo);
            $GeneralLog->setSoperativo($Global_soperativo);
            $GeneralLog->setSversion($Global_sversion);
            $GeneralLog->setTabla($tabla);
            $GeneralLog->setCampo($campo);

            /* Se registran datos del producto en el log general de la base de datos. */
            $GeneralLog->setExternoId($ProductoMandante->prodmandanteId);
            $GeneralLog->setMandante($ProductoMandante->mandante);
            $GeneralLog->setExplicacion($Reason);

            $GeneralLogMySqlDAO = new GeneralLogMySqlDAO($Transaction);
            $GeneralLogMySqlDAO->insert($GeneralLog);


            /* Asignación del número de fila a la propiedad 'numFila' del objeto 'ProductoMandante'. */
            $ProductoMandante->numFila = $Rows;
        }

        if ($Order != "" && $ProductoMandante->numColumna != $Columns) {


            /* Código que registra un cambio en el campo 'numColumna' de 'producto_mandante'. */
            $tipo = 'CHANGEFIELD';
            $tabla = 'producto_mandante';
            $campo = 'numColumna';
            $valorAntes = $ProductoMandante->numColumna;
            $valorDespues = $Columns;

            $GeneralLog = new GeneralLog();

            /* establece parámetros para registrar un evento en el log general. */
            $GeneralLog->setUsuarioId($ClientId);
            $GeneralLog->setUsuarioIp($Global_IP);
            $GeneralLog->setUsuariosolicitaId($ClientId);
            $GeneralLog->setUsuariosolicitaIp($Global_IP);
            $GeneralLog->setTipo($tipo);
            $GeneralLog->setEstado("A");


            /* configura valores y atributos para un objeto GeneralLog. */
            $GeneralLog->setValorAntes($valorAntes);
            $GeneralLog->setValorDespues($valorDespues);

            $GeneralLog->setUsucreaId(0);
            $GeneralLog->setUsumodifId(0);
            $GeneralLog->setDispositivo($Global_dispositivo);

            /* Configura propiedades de un objeto GeneralLog usando datos globales y específicos. */
            $GeneralLog->setSoperativo($Global_soperativo);
            $GeneralLog->setSversion($Global_sversion);
            $GeneralLog->setTabla($tabla);
            $GeneralLog->setCampo($campo);
            $GeneralLog->setExternoId($ProductoMandante->prodmandanteId);
            $GeneralLog->setMandante($ProductoMandante->mandante);

            /* establece una explicación, guarda un registro y asigna un número de columna a un producto. */
            $GeneralLog->setExplicacion($Reason);
            $GeneralLogMySqlDAO = new GeneralLogMySqlDAO($Transaction);
            $GeneralLogMySqlDAO->insert($GeneralLog);

            $ProductoMandante->numColumna = $Columns;
        }


        /* Actualiza un producto y confirma la transacción, luego actualiza la base de datos. */
        $ProductoMandanteMySqlDAO->update($ProductoMandante);
        $ProductoMandanteMySqlDAO->getTransaction()->commit();
        $insertOrUpdate = true;


        if ($insertOrUpdate) {
            $CMSProveedor = new \Backend\cms\CMSProveedor('CASINO', '', $Partner);
            $CMSProveedor->updateDatabaseCasino();
        }

        /* inicializa un arreglo de respuesta sin errores ni mensajes. */
        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];


    }
}