<?php

use Backend\dto\ApiTransaction;
use Backend\dto\Area;
use Backend\dto\AuditoriaGeneral;
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
use Backend\dto\Subproveedor;
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
use Backend\mysql\AuditoriaGeneralMySqlDAO;
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
use Backend\sql\QueryExecutor;
use Backend\sql\SqlQuery;
use Backend\websocket\WebsocketUsuario;

/**
 * PartnerGames/SaveOrderPartnersGames
 *
 * Este script guarda el orden de los juegos asociados a un socio (partner) y realiza actualizaciones en la base de datos.
 *
 * @param object $params Objeto que contiene los siguientes parámetros:
 * @param int $params->count Número de elementos a mostrar en la tabla.
 * @param int $params->OrderedItem Elemento ordenado para la paginación.
 * @param string $params->OrderPartnersGames Cadena con el orden de los juegos de socios.
 * @param string $params->Partner Identificador del socio.
 * @param string $params->Categorie Categoría de los juegos.
 * @param string $params->Note Nota asociada al cambio de orden.
 * @param int $params->Order Orden específico para los juegos.
 * @param string $params->CountrySelect País seleccionado.
 * 
 *
 * @return array $response Respuesta en formato JSON con los siguientes campos:
 * - HasError: (boolean) Indica si ocurrió un error.
 * - AlertType: (string) Tipo de alerta ('success' o 'error').
 * - AlertMessage: (string) Mensaje de alerta.
 * - ModelErrors: (array) Lista de errores de validación.
 */

try {

    /* establece variables para manejar paginación de datos en una solicitud. */
    $MaxRows = $_REQUEST["count"];
    $OrderedItem = $params->OrderedItem;
    $SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];

    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }


    /* asigna valores predeterminados a variables si están vacías. */
    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }

    if ($MaxRows == "") {
        $MaxRows = 10000;
    }


    /* Asignación de parámetros para manejar juegos de socios y notas en una operación. */
    $OrderPartnersGames = $params->OrderPartnersGames;
    $Partner = $params->Partner;
    $Categorie = $params->Categorie;
    $Partner = $params->Partner;
    $insertOrUpdate = false;
    $Note = $params->Note;

    /* asigna un valor a $Order y valida $CountrySelect lanzando una excepción si está vacío. */
    $Order = $params->Order ?: '';

    $CountrySelect = $params->CountrySelect;
    if ($CountrySelect == '') {
        throw new Exception("Inusual Detected", "11");

    }


    /* asigna una cadena vacía si la categoría es '0' y crea un subproveedor. */
    if ($Categorie == '0') {
        $Categorie = "";
    }

    $Subproveedor = new Subproveedor();
    $Subproveedor->tipo = 'CASINO';

    /* obtiene productos de un subproveedor y almacena sus IDs en un array. */
    $Productos = $Subproveedor->getProductosTipoMandante($Categorie, '', '0', '2000', '', strtolower($Partner), '', $CountrySelect);

    $productosstring = '##';

    $final = [];
    foreach ($Productos as $producto) {
        array_push(
            $final, $producto['producto.producto_id']
        );
    }

    /* Se convierten productos en cadena y se establecen condiciones para el orden. */
    $productosstring = implode(", ", $final);

    $orden = $Order !== '' ? $Order : 1;

    $ordenNuevo = array();
    $ordenProductoNuevo = array();


    /* separa un string y compara elementos con un arreglo, almacenando resultados. */
    $OrderPartnersGamesArray = explode(",", $OrderPartnersGames);

    $cont = 0;
    foreach ($OrderPartnersGamesArray as $item) {
        if ($item != $final[$cont] || $Order !== '') {
            array_push($ordenNuevo, $orden);
            array_push($ordenProductoNuevo, $item);
        }

        $orden = $orden + 2;
        $cont++;
    }
    if ($Categorie != '') {


        /* inicializa un contador y crea una instancia de CategoriaProductoMySqlDAO. */
        $cont = 0;
        $CategoriaProductoMySqlDAO = new CategoriaProductoMySqlDAO();

        if (count($ordenProductoNuevo) > 0) {

            /* Código que define variables de sesión para gestionar órdenes de juegos en una tabla. */
            $userId = $_SESSION['usuario2'];
            $dirIp = $_SESSION['dir_ip'];
            $type = 'CHANGEORDERGAMES';
            $device = $Global_dispositivo;
            $field = 'orden';
            $table = 'categoria_producto';


            /* Inserta registros en la tabla general_log utilizando información de usuarios y cambios realizados. */
            $sqlGeneralLog = 'INSERT general_log (usuario_id, usuario_ip, usuariosolicita_id, usuariosolicita_ip, usuarioaprobar_id, usuarioaprobar_ip, tipo, valor_antes, valor_despues, usucrea_id, usumodif_id, estado, dispositivo, externo_id, campo, tabla, explicacion, mandante) VALUES';
            $sqlValuesGeneralLog = " ({$userId}, '{$dirIp}', {$userId}, '{$dirIp}', 0, '', '{$type}', $1, $2, 0, 0, 'A', '{$device}', $3, '{$field}', '{$table}', '{$Note}', {$Partner}),";
            $Transaction = $CategoriaProductoMySqlDAO->getTransaction();

            foreach ($ordenProductoNuevo as $item) {


                /* Código para actualizar el orden de un producto en la base de datos. */
                $before = 0;
                $after = $ordenNuevo[$cont];

                try {
                    $CategoriaProducto = new CategoriaProducto("", $item, "", $Categorie, $Partner, $CountrySelect);

                    $before = $CategoriaProducto->getOrden() ?: 0;
                    $CategoriaProducto->setUsumodifId($_SESSION['usuario2']);
                    $CategoriaProducto->setOrden($Order === '' ? $ordenNuevo[$cont] : $Order);

                    $CategoriaProductoMySqlDAO = new CategoriaProductoMySqlDAO($Transaction);
                    $CategoriaProductoMySqlDAO->update($CategoriaProducto);
                    $insertOrUpdate = true;

                } catch (Exception $e) {


                    /* Se inserta una nueva categoría de producto si el código de error es "49". */
                    if ($e->getCode() == "49") {
                        $CategoriaProducto = new CategoriaProducto();

                        $CategoriaProducto->setCategoriaId($Categorie);
                        $CategoriaProducto->setProductoId($item);

                        $CategoriaProducto->setUsucreaId($_SESSION['usuario2']);
                        $CategoriaProducto->setUsumodifId($_SESSION['usuario2']);

                        $CategoriaProducto->setEstado('A');
                        $CategoriaProducto->setOrden($ordenNuevo[$cont]);
                        $CategoriaProducto->setMandante($Partner);
                        $CategoriaProducto->paisId = $CountrySelect;

                        $CategoriaProductoMySqlDAO = new CategoriaProductoMySqlDAO($Transaction);
                        $CategoriaProductoMySqlDAO->insert($CategoriaProducto);
                        $insertOrUpdate = true;
                    }
                }


                /* Reemplaza marcadores en una cadena SQL con variables específicas antes de ejecutarla. */
                $sqlGeneralLog .= str_replace(['$1', '$2', '$3'], [$before, $after, $item], $sqlValuesGeneralLog);

                $cont++;
            }


            /* ejecuta una consulta SQL y confirma una transacción en la base de datos. */
            $SqlQuery = new SqlQuery(rtrim($sqlGeneralLog, ','));
            QueryExecutor::executeInsert($Transaction, $SqlQuery);

            $Transaction->commit();
        }
    } else {

        /* Se inicializa un contador y se crea una instancia del DAO para manejo de productos. */
        $cont = 0;
        $ProductoMandanteMySqlDAO = new ProductoMandanteMySqlDAO();

        if (count($ordenProductoNuevo) > 0) {

            /* Se obtiene una transacción utilizando el método getTransaction del objeto ProductoMandanteMySqlDAO. */
            $Transaction = $ProductoMandanteMySqlDAO->getTransaction();
            foreach ($ordenProductoNuevo as $item) {


                /* Actualiza un objeto ProductoMandante y guarda su valor antes y después del cambio. */
                try {
                    $ProductoMandante = new ProductoMandante($item, $Partner, '', $CountrySelect);
                    $before = $ProductoMandante ? $ProductoMandante->orden : 0; // Guardamos el valor viejo

                    $ProductoMandante->usumodifId = $_SESSION['usuario2'];
                    $ProductoMandante->orden = $Order === '' ? $ordenNuevo[$cont] : $Order;

                    $ProductoMandanteMySqlDAO = new ProductoMandanteMySqlDAO($Transaction);
                    $ProductoMandanteMySqlDAO->update($ProductoMandante);
                    $insertOrUpdate = true;

                    $after = $ProductoMandante->orden; // Guardamos el valor nuevo

                } catch (Exception $e) {
                    /* Captura excepciones en PHP para manejar errores sin interrumpir la ejecución. */


                }
                $cont++;

            }


            /* obtiene la IP del usuario y inicia la función para detectar el dispositivo. */
            $ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
            $ip = explode(",", $ip)[0];

/**
             * Detecta el tipo de dispositivo del usuario basado en el User-Agent.
             *
             * @return string 'Móvil' si se detecta un dispositivo móvil, 'PC' en caso contrario.
             */
            function detectarDispositivo()
            {
                $userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);

                // Lista de palabras clave comunes en User-Agent de dispositivos móviles
                $movilKeywords = [
                    'android', 'iphone', 'ipad', 'ipod', 'blackberry', 'windows phone', 'opera mini', 'mobile', 'silk'
                ];

                // Verificar si alguna palabra clave está en el User-Agent
                foreach ($movilKeywords as $keyword) {
                    if (strpos($userAgent, $keyword) !== false) {
                        return 'Móvil';
                    }
                }

                return 'PC';
            }
// Uso de la función

            /* detecta el sistema operativo del usuario basándose en el User Agent. */
            $dispositivo = detectarDispositivo();

            $userAgent = $_SERVER['HTTP_USER_AGENT'];

/**
             * Detecta el sistema operativo del usuario basado en el User-Agent.
             *
             * @param string $userAgent El User-Agent del navegador del usuario.
             * @return string El nombre del sistema operativo detectado.
             */
            function getOS($userAgent)
            {
                $os = "Desconocido";

                if (stripos($userAgent, 'Windows') !== false) {
                    $os = 'Windows';
                } elseif (stripos($userAgent, 'Linux') !== false) {
                    $os = 'Linux';
                } elseif (stripos($userAgent, 'Macintosh') !== false || stripos($userAgent, 'Mac OS X') !== false) {
                    $os = 'Mac';
                } elseif (stripos($userAgent, 'Android') !== false) {
                    $os = 'Android';
                } elseif (stripos($userAgent, 'iPhone') !== false || stripos($userAgent, 'iPad') !== false) {
                    $os = 'iOS';
                }

                return $os;
            }

            /* registra información del usuario y su sistema operativo en auditoría. */
            $so = getOS($userAgent);

            $AuditoriaGeneral = new AuditoriaGeneral();
            $AuditoriaGeneral->usuarioId = $_SESSION['usuario'];
            $AuditoriaGeneral->usuarioIp = $ip;
            $AuditoriaGeneral->usuariosolicitaId = $_SESSION['usuario'];

            /* Registro de auditoría para actualización de configuración en el sistema. */
            $AuditoriaGeneral->usuarioaprobarIp = '';
            $AuditoriaGeneral->tipo = 'ACTUALIZACION_CONFIGURACION';
            $AuditoriaGeneral->valorAntes = $before;
            $AuditoriaGeneral->valorDespues = $after;
            $AuditoriaGeneral->usucreaId = $_SESSION['usuario'];
            $AuditoriaGeneral->usumodifId = 0;

            /* asigna valores a propiedades de un objeto sobre auditoría general. */
            $AuditoriaGeneral->estado = 'A';
            $AuditoriaGeneral->dispositivo = $dispositivo;
            $AuditoriaGeneral->soperativo = $so;
            $AuditoriaGeneral->imagen = '';
            $AuditoriaGeneral->observacion = "Cambio en orden de producto";
            $AuditoriaGeneral->data = '';

            /* Inserta un registro del estado en auditoría y confirma la transacción. */
            $AuditoriaGeneral->campo = 'Estado';

            $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO($Transaction);
            $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);

            $Transaction->commit();
        }

    }


    /* Actualiza la base de datos del casino si se inserta o actualiza correctamente. */
    if ($insertOrUpdate) {
        $CMSProveedor = new \Backend\cms\CMSProveedor('CASINO', '', strtolower($Partner), $CountrySelect);
        $CMSProveedor->updateDatabaseCasino();
    }
    $response["HasError"] = false;
    $response["AlertType"] = "success";

    /* Se inicializan un mensaje de alerta y un array para errores en la respuesta. */
    $response["AlertMessage"] = "";
    $response["ModelErrors"] = [];

} catch (Exception $e) {
    /* Gestiona excepciones, estableciendo un mensaje de error y un indicador de fallo. */

    $response["HasError"] = true;
    $response["AlertType"] = "Error";
    $response["AlertMessage"] = "Error general";
    $response["ModelErrors"] = [];
}
