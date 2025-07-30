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
use Backend\dto\descarga_version;
use Backend\dto\DescargaVersion;
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
use Backend\mysql\DescargaMySqlDAO;
use Backend\mysql\DescargaVersionMySqlDAO;
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

/**
 * RegisteredDocument/AddDocument
 *
 * AddDocument
 *
 * Este recurso se encarga de manejar la creación y actualización de documentos de descarga, gestionando
 * sus versiones y validaciones asociadas. Dependiendo del tipo de método y estado, se insertan o actualizan
 * registros en la base de datos para las descargas y sus versiones.
 *
 * @param string $Name : Nombre del documento de descarga.
 * @param string $Route : Ruta o ubicación del documento.
 * @param string $Version : Versión del documento.
 * @param string $Type : Tipo de clasificador para la descarga.
 * @param string $TypeMethod : Método de tipo de descarga (0 o cualquier otro valor).
 * @param bool $IsActivate : Estado de activación de la descarga.
 * @param string $EncryptionMethod : Método de encriptación utilizado.
 * @param string $EncryptionValue : Valor de encriptación del documento.
 * @param string $ExternalId : Identificador externo para la descarga.
 * @param string $Profile : Perfil asociado a la descarga.
 *
 * @returns object El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error en la operación.
 *  - *AlertType* (string): Tipo de alerta (success, danger, etc.).
 *  - *AlertMessage* (string): Mensaje de la alerta a mostrar.
 *  - *ModelErrors* (array): Errores del modelo si existen.
 *
 * Objeto de respuesta en caso de error:
 *  "HasError" => true,
 *  "AlertType" => "danger",
 *  "AlertMessage" => "[Mensaje de error]",
 *  "ModelErrors" => []
 *
 * @throws Exception Si ocurre un error durante el proceso de inserción o actualización del documento.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* asigna valores de parámetros a variables en un script. */
$Name = $params->Name;
$Route = $params->Route;
$Version = $params->Version;
$Type = $params->Type;
$TypeMethod = $params->TypeMethod;
$IsActivate = $params->IsActivate;

/* Asignación de parámetros relacionados con el método y valor de cifrado en variables. */
$EncryptionMethod = $params->EncryptionMethod;
$EncryptionValue = $params->EncryptionValue;
$ExternalId = $params->ExternalId;
$Profile = $params->Profile;

try {


    /* Se crea un clasificador y se obtiene el ID del usuario de la sesión. */
    $TipoClasificador = new Clasificador($Type);
    $idUser = $_SESSION["usuario"];

    $id = "";


    try {


        /* crea instancias de clases para manejar clasificaciones y proveedores en un sistema. */
        $Clasificador = new Clasificador("", "PROVSIGNATURE");
        $MandanteDetalle = new MandanteDetalle('', $_SESSION['mandante'], $Clasificador->clasificadorId, $_SESSION['pais_id'], 'A');
        $Proveedor = new Proveedor($MandanteDetalle->valor);

        if ($TypeMethod == "0") {

            /* Crea un objecto "Descarga" con propiedades mediante métodos de configuración. */
            $Descarga1 = new Descarga("", $TipoClasificador->abreviado);

            $Descarga1->setDescripcion($Name);
            $Descarga1->setRuta($Route);
            $Descarga1->setVersion($Version);
            $Descarga1->setTipo($TipoClasificador->abreviado);

            /* establece propiedades de un objeto "Descarga1" usando valores específicos. */
            $Descarga1->setEstado($IsActivate);
            $Descarga1->setPlataforma('0');
            $Descarga1->setMandante($_SESSION['mandante']);
            $Descarga1->setEncriptacionMetodo($EncryptionMethod);
            $Descarga1->setEncriptacionValor($EncryptionValue);
            $Descarga1->setExternalId($ExternalId);

            /* establece datos en un objeto y crea una instancia de DescargaMySqlDAO. */
            $Descarga1->setPaisId($_SESSION['pais_id']);
            $Descarga1->setProveedorId($Proveedor->proveedorId);
            $Descarga1->setJson("prueba");
            $Descarga1->setPerfilId($Profile);

            $descargaMysqlDAO1 = new DescargaMySqlDAO();

            /* Inicia una transacción, actualiza datos y crea una nueva versión de descarga. */
            $Transaction = $descargaMysqlDAO1->getTransaction();
            $descargaMysqlDAO1->update($Descarga1);
            $descargaMysqlDAO1->getTransaction()->commit();

            $idUpdate = $Descarga1->descargaId;

            $descargaVersion2 = new DescargaVersion("", $idUser, "", $idUpdate);


            /* establece propiedades para un objeto relacionado con una descarga de documento. */
            $descargaVersion2->setUserId($idUser);
            $descargaVersion2->setDocumentoId($idUpdate);
            $descargaVersion2->setVersion($Version);
            $descargaVersion2->setFechaCrea(date("Y-m-d"));
            $descargaVersion2->setFechaModif(date("Y-m-d"));
            $descargaVersion2->setUrl($Route);

            /* Configuración de encriptación y obtención de transacción en la base de datos. */
            $descargaVersion2->setEncriptacion($EncryptionMethod);
            $descargaVersion2->setEncriptacion($EncryptionMethod);


            $descargaVersionMysqlDao2 = new DescargaVersionMySqlDAO();
            $Transaction = $descargaVersionMysqlDao2->getTransaction();

            /* Actualiza un registro en la base de datos y gestiona una transacción. */
            $descargaVersionMysqlDao2->update($descargaVersion2);
            $descargaVersionMysqlDao2->getTransaction()->commit();

        } else {

            try {

                /* Se inicializa un objeto y se establecen variables para controlar filas a procesar. */
                $Descarga1 = new Descarga("", $TipoClasificador->abreviado);

                $SkeepRows = "";
                $MaxRows = "";
                if ($SkeepRows == "") {
                    $SkeepRows = 0;
                }


                /* asigna 1000 a $MaxRows si está vacío y crea un arreglo $rules. */
                if ($MaxRows == "") {
                    $MaxRows = 1000;
                }


                $rules = [];


                /* Se crean reglas de filtro y se codifican en formato JSON. */
                array_push($rules, array("field" => "descarga.estado", "data" => "A", "op" => "eq"));
                array_push($rules, array("field" => "descarga.external_id", "data" => $ExternalId, "op" => "eq"));


                $filtro = array("rules" => $rules, "groupOp" => "AND");
                $json_filter = json_encode($filtro);


                /* Crea una descarga y valida si existe un externalId asociado a un documento. */
                $Descarga = new Descarga();
                $documentos = $Descarga->getDescargasCustom(" descarga.* ", "descarga.descarga_id", "asc", $SkeepRows, $MaxRows, $json_filter, true);

                $documentos = json_decode($documentos);
                if (oldCount($documentos) > 0) {

                    throw new Exception("Ya existe un externalId asociado a un documento", 10024);

                }


            } catch (Exception $e) {


                if ($e->getcode() == 30) {

                    /* Código para inicializar clases y variables relacionadas con un clasificador y proveedor. */
                    $Clasificador = new Clasificador("", "PROVSIGNATURE");
                    $MandanteDetalle = new MandanteDetalle('', $_SESSION['mandante'], $Clasificador->clasificadorId, $_SESSION['pais_id'], 'A');
                    $Proveedor = new Proveedor($MandanteDetalle->valor);

                    $SkeepRows = "";
                    $MaxRows = "";

                    /* asigna valores predeterminados a variables si están vacías. */
                    if ($SkeepRows == "") {
                        $SkeepRows = 0;
                    }

                    if ($MaxRows == "") {
                        $MaxRows = 1000;
                    }


                    /* crea un filtro con condiciones para una consulta de datos. */
                    $rules = [];

                    array_push($rules, array("field" => "descarga.estado", "data" => "A", "op" => "eq"));
                    array_push($rules, array("field" => "descarga.external_id", "data" => $ExternalId, "op" => "eq"));


                    $filtro = array("rules" => $rules, "groupOp" => "AND");

                    /* procesa un filtro JSON para obtener y decodificar descargas personalizadas. */
                    $json_filter = json_encode($filtro);


                    $Descarga = new Descarga();
                    $documentos = $Descarga->getDescargasCustom(" descarga.* ", "descarga.descarga_id", "asc", $SkeepRows, $MaxRows, $json_filter, true);

                    $documentos = json_decode($documentos);


                    /* Lanza una excepción si ya hay un externalId asociado a un documento. */
                    if (oldCount($documentos) > 0) {


                        throw new Exception("Ya existe un externalId asociado a un documento", 10024);

                    } else {


                        /* crea un objeto de descarga y establece sus propiedades. */
                        $Descarga = new Descarga();
                        $Descarga->setDescripcion($Name);
                        $Descarga->setRuta($Route);
                        $Descarga->setVersion($Version);
                        $Descarga->setTipo($TipoClasificador->abreviado);
                        $Descarga->setEstado($IsActivate);

                        /* Asignación de valores a propiedades del objeto "Descarga" para procesar datos. */
                        $Descarga->setPlataforma('0');
                        $Descarga->setMandante($_SESSION['mandante']);
                        $Descarga->setEncriptacionMetodo($EncryptionMethod);
                        $Descarga->setEncriptacionValor($EncryptionValue);

                        $Descarga->setExternalId($ExternalId);

                        /* configura datos de una descarga y crea un objeto DAO para MySQL. */
                        $Descarga->setPaisId($_SESSION['pais_id']);
                        $Descarga->setProveedorId($Proveedor->proveedorId);
                        $Descarga->setJson("Pruebas");
                        $Descarga->setPerfilId($Profile);

                        $DescargaMySqlDAO = new DescargaMySqlDAO();

                        /* Inserta un registro y establece propiedades para una nueva descarga y su versión. */
                        $id = $DescargaMySqlDAO->insert($Descarga);
                        $descargaVersion = new DescargaVersion();
                        $descargaVersion->setUserId($idUser);
                        $descargaVersion->setDocumentoId($id);
                        $descargaVersion->setVersion($Version);
                        $descargaVersion->setFechaCrea(date('Y-m-d H:i:s'));

                        /* Establece atributos de un objeto y lo inserta en la base de datos MySQL. */
                        $descargaVersion->setFechaModif(date('Y-m-d H:i:s'));
                        $descargaVersion->setUrl($Route);
                        $descargaVersion->setEncriptacion($EncryptionMethod);

                        $descargaVersionMysqlDao = new DescargaVersionMySqlDAO($DescargaMySqlDAO->getTransaction());
                        $descargaVersionMysqlDao->insert($descargaVersion);


                        /* efectúa la confirmación de una transacción en MySQL. */
                        $DescargaMySqlDAO->getTransaction()->commit();


                    }


                }

                /* Código inicializa respuesta con error falso y configuración de alerta vacía. */
                $response["HasError"] = false;
                $response["AlertType"] = "danger";
                $response["AlertMessage"] = "";
                $response["ModelErrors"] = [];
            }


        }

    } catch (Exception $e) {

        if ($e->getcode() == 30) {

            /* Se crean instancias de clasificador, mandante y proveedor en un contexto específico. */
            $Clasificador = new Clasificador("", "PROVSIGNATURE");
            $MandanteDetalle = new MandanteDetalle('', $_SESSION['mandante'], $Clasificador->clasificadorId, $_SESSION['pais_id'], 'A');
            $Proveedor = new Proveedor($MandanteDetalle->valor);

            $SkeepRows = "";
            $MaxRows = "";

            /* asigna valores predeterminados a variables si están vacías. */
            if ($SkeepRows == "") {
                $SkeepRows = 0;
            }

            if ($MaxRows == "") {
                $MaxRows = 1000;
            }


            /* Define reglas de filtrado para descargas basadas en estado y ID externo. */
            $rules = [];

            array_push($rules, array("field" => "descarga.estado", "data" => "A", "op" => "eq"));
            array_push($rules, array("field" => "descarga.external_id", "data" => $ExternalId, "op" => "eq"));


            $filtro = array("rules" => $rules, "groupOp" => "AND");

            /* genera un filtro JSON y obtiene documentos paginados de descargas. */
            $json_filter = json_encode($filtro);


            $Descarga = new Descarga();
            $documentos = $Descarga->getDescargasCustom(" descarga.* ", "descarga.descarga_id", "asc", $SkeepRows, $MaxRows, $json_filter, true);

            $documentos = json_decode($documentos);


            /* Lanza una excepción si hay documentos existentes con un externalId. */
            if (oldCount($documentos) > 0) {


                throw new Exception("Ya existe un externalId asociado a un documento", 10024);

            } else {


                /* Se crea un objeto 'Descarga' y se establecen sus propiedades. */
                $Descarga = new Descarga();
                $Descarga->setDescripcion($Name);
                $Descarga->setRuta($Route);
                $Descarga->setVersion($Version);
                $Descarga->setTipo($TipoClasificador->abreviado);
                $Descarga->setEstado($IsActivate);

                /* Código que configura atributos de un objeto de descarga, incluyendo plataformas y encriptaciones. */
                $Descarga->setPlataforma('0');
                $Descarga->setMandante($_SESSION['mandante']);
                $Descarga->setEncriptacionMetodo($EncryptionMethod);
                $Descarga->setEncriptacionValor($EncryptionValue);

                $Descarga->setExternalId($ExternalId);

                /* establece propiedades en un objeto y crea una instancia de DescargaMySqlDAO. */
                $Descarga->setPaisId($_SESSION['pais_id']);
                $Descarga->setProveedorId($Proveedor->proveedorId);
                $Descarga->setJson("Pruebas");
                $Descarga->setPerfilId($Profile);

                $DescargaMySqlDAO = new DescargaMySqlDAO();

                /* Se inserta un registro y se crea una versión de descarga asociada a un usuario. */
                $id = $DescargaMySqlDAO->insert($Descarga);
                $descargaVersion = new DescargaVersion();
                $descargaVersion->setUserId($idUser);
                $descargaVersion->setDocumentoId($id);
                $descargaVersion->setVersion($Version);
                $descargaVersion->setFechaCrea(date('Y-m-d H:i:s'));

                /* Establece fecha, URL y método de encriptación, luego inserta en la base de datos. */
                $descargaVersion->setFechaModif(date('Y-m-d H:i:s'));
                $descargaVersion->setUrl($Route);
                $descargaVersion->setEncriptacion($EncryptionMethod);

                $descargaVersionMysqlDao = new DescargaVersionMySqlDAO($DescargaMySqlDAO->getTransaction());
                $descargaVersionMysqlDao->insert($descargaVersion);


                /* confirma una transacción en una base de datos MySQL. */
                $DescargaMySqlDAO->getTransaction()->commit();


            }


        }

        if ($e->getcode() == 34) {


            /* crea una instancia de "Descarga" y establece sus propiedades. */
            $Descarga = new Descarga();
            $Descarga->setDescripcion($Name);
            $Descarga->setRuta($Route);
            $Descarga->setVersion($Version);
            $Descarga->setTipo($TipoClasificador->abreviado);
            $Descarga->setEstado($IsActivate);

            /* Configuración de parámetros para un objeto de descarga en un sistema. */
            $Descarga->setPlataforma('0');
            $Descarga->setMandante($_SESSION['mandante']);
            $Descarga->setEncriptacionMetodo($EncryptionMethod);
            $Descarga->setEncriptacionValor($EncryptionValue);

            $Descarga->setExternalId($ExternalId);

            /* establece valores en un objeto y crea una instancia de DescargaMySqlDAO. */
            $Descarga->setPaisId($_SESSION['pais_id']);
            $Descarga->setProveedorId(0);
            $Descarga->setJson("Pruebas");
            $Descarga->setPerfilId($Profile);

            $DescargaMySqlDAO = new DescargaMySqlDAO();

            /* Inserta datos en la base de datos y crea una nueva versión de descarga. */
            $id = $DescargaMySqlDAO->insert($Descarga);
            $descargaVersion = new DescargaVersion();
            $descargaVersion->setUserId($idUser);
            $descargaVersion->setDocumentoId($id);
            $descargaVersion->setVersion($Version);
            $descargaVersion->setFechaCrea(date('Y-m-d H:i:s'));

            /* establece atributos y guarda un objeto en la base de datos. */
            $descargaVersion->setFechaModif(date('Y-m-d H:i:s'));
            $descargaVersion->setUrl($Route);
            $descargaVersion->setEncriptacion($EncryptionMethod);

            $descargaVersionMysqlDao = new DescargaVersionMySqlDAO($DescargaMySqlDAO->getTransaction());
            $descargaVersionMysqlDao->insert($descargaVersion);


            /* confirma una transacción en una base de datos MySQL usando un objeto DAO. */
            $DescargaMySqlDAO->getTransaction()->commit();


        }


        /* Se inicializan variables para manejar respuestas y errores en una aplicación. */
        $response["HasError"] = false;
        $response["AlertType"] = "danger";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];
    }

} catch (Exception $e) {
    /* Maneja excepciones, generando una respuesta con estado de error y mensaje. */


    $response["HasError"] = true;
    $response["AlertType"] = "danger";
    $response["AlertMessage"] = $e->getMessage();
    $response["ModelErrors"] = [];

}