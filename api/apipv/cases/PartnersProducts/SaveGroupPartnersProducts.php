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
use Backend\websocket\WebsocketUsuario;

/**
 * PartnerProducts/SaveGroupPartnersProducts
 *
 * Guarda o actualiza la configuración de productos asociados a un partner
 * 
 * @param array $params Parámetros de entrada:
 *   - Partner: string ID del partner
 *   - CountrySelect: string País seleccionado
 *   - ExcludedProductsList: string Lista de productos excluidos separados por coma
 *   - IncludedProductsList: string Lista de productos incluidos separados por coma
 *   - permAdd: string Lista de permisos para agregar separados por coma
 *   - permDelete: string Lista de permisos para eliminar separados por coma
 *
 * @return array {
 *   "HasError": boolean Indica si hubo error,
 *   "AlertType": string Tipo de alerta (success|error|warning),
 *   "AlertMessage": string Mensaje descriptivo,
 *   "data": array[] Datos actualizados {
 *     "Partner": string ID del partner,
 *     "Country": string País,
 *     "ExcludedProducts": array[] Lista de productos excluidos,
 *     "IncludedProducts": array[] Lista de productos incluidos,
 *     "Permissions": array {
 *       "Add": array[] Permisos para agregar,
 *       "Delete": array[] Permisos para eliminar
 *     }
 *   }
 * }
 *
 * @throws Exception Si hay error en la actualización de la base de datos
 *
 * @access public
 */

try {



/* extrae parámetros y la dirección IP del usuario, gestionando productos excluidos. */
    $Partner = $params->Partner;
    $CountrySelect = $params->CountrySelect;
    $Note = $params->Note;


    if($Note == "" || $Note == null){
        throw new exception("debe de ingresar una observacion",3001391);
    }


    if (strlen($Note) < 10) {
        throw new exception("La observación debe tener al menos 10 caracteres", 3001392);
    }


    $ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
    $ip = explode(",", $ip)[0];

    $ExcludedProductsList = ($params->ExcludedProductsList != "") ? explode(",", $params->ExcludedProductsList) : array();

/* procesa listas de productos y permisos desde parámetros, creando arreglos. */
    $IncludedProductsList = ($params->IncludedProductsList != "") ? explode(",", $params->IncludedProductsList) : array();

    $permAdd = ($params->permAdd != "") ? explode(",", $params->permAdd) : array();
    $permDelete = ($params->permDelete != "") ? explode(",", $params->permDelete) : array();

    $insertOrUpdate=false;

    if ($Partner != '' && $CountrySelect != '') {



        if (oldCount($ExcludedProductsList) > 0) {


/* Se crea un objeto DAO y se obtiene una transacción de la base de datos. */
            $ProductoMandanteMySqlDAO = new ProductoMandanteMySqlDAO();
            $Transaction = $ProductoMandanteMySqlDAO->getTransaction();

            foreach ($ExcludedProductsList as $key => $value) {
                try {

/* Se crea y actualiza un objeto ProductoMandante en la base de datos. */
                    $ProductoMandante = new ProductoMandante($value, $Partner,'',$CountrySelect);

                    $ProductoMandante->estado = 'I';
                    $ProductoMandanteMySqlDAO = new ProductoMandanteMySqlDAO($Transaction);
                    $ProductoMandanteMySqlDAO->update($ProductoMandante);
                    $insertOrUpdate=true;


/* Código para registrar actividad de auditoría con datos del usuario y su IP. */
                    $AuditoriaGeneral = new AuditoriaGeneral();
                    $AuditoriaGeneral->setUsuarioId($_SESSION["usuario"]);
                    $AuditoriaGeneral->setUsuarioIp($ip);
                    $AuditoriaGeneral->setUsuariosolicitaId($_SESSION["usuario"]);
                    $AuditoriaGeneral->setUsuariosolicitaIp($ip);
                    $AuditoriaGeneral->setUsuariosolicitaId(0);
                    
                    /* Configura auditoría para desactivación de producto, registrando cambios y usuarios involucrados. */
                    $AuditoriaGeneral->setUsuarioaprobarIp(0);
                    $AuditoriaGeneral->setTipo("DESACTIVACIONDEPRODUCTOMANDANTE");
                    $AuditoriaGeneral->setValorAntes("A");
                    $AuditoriaGeneral->setValorDespues("I");
                    $AuditoriaGeneral->setUsucreaId($_SESSION["usuario"]);
                    $AuditoriaGeneral->setUsumodifId(0);
                    
                    /* Configuración de auditoría general con estado, dispositivo y observación en MySQL. */
                    $AuditoriaGeneral->setEstado("A");
                    $AuditoriaGeneral->setDispositivo(0);
                    $AuditoriaGeneral->setObservacion($Note);
                    $AuditoriaGeneral->setData($ProductoMandante->prodmandanteId);


                    $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO($Transaction);
                    
                    /* Esta línea inserta un objeto de auditoría en una base de datos MySQL. */
                    $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);




                } catch (Exception $e) {
/* Manejo de excepciones en PHP, verifica si el código del error es "27". */

                    if ($e->getCode() == "27") {

                    }

                }

            }

            
            /* confirma transacciones en bases de datos, guardando cambios realizados. */
            $Transaction->commit();

        }

        if (oldCount($IncludedProductsList) > 0) {


/* Se crea una instancia de ProductoMandanteMySqlDAO y se obtiene una transacción. */
            $ProductoMandanteMySqlDAO = new ProductoMandanteMySqlDAO();
            $Transaction = $ProductoMandanteMySqlDAO->getTransaction();

            foreach ($IncludedProductsList as $key => $value) {
                try {

/* Se crea y actualiza un objeto ProductoMandante en la base de datos. */
                    $ProductoMandante = new ProductoMandante($value, $Partner,'',$CountrySelect);


                    $ProductoMandante->estado = 'A';
                    $ProductoMandanteMySqlDAO = new ProductoMandanteMySqlDAO($Transaction);
                    $ProductoMandanteMySqlDAO->update($ProductoMandante);
                    $insertOrUpdate=true;


/* Crea una instancia de AuditoriaGeneral y establece atributos relacionados al usuario. */
                    $AuditoriaGeneral = new AuditoriaGeneral();
                    $AuditoriaGeneral->setUsuarioId($_SESSION["usuario"]);
                    $AuditoriaGeneral->setUsuarioIp($ip);
                    $AuditoriaGeneral->setUsuariosolicitaId($_SESSION["usuario"]);
                    $AuditoriaGeneral->setUsuariosolicitaIp($ip);
                    $AuditoriaGeneral->setUsuariosolicitaId(0);
                    
                    /* Configura auditoría de activación de producto con valores anteriores y posteriores. */
                    $AuditoriaGeneral->setUsuarioaprobarIp(0);
                    $AuditoriaGeneral->setTipo("ACTIVACIONDEPRODUCTOMANDANTE");
                    $AuditoriaGeneral->setValorAntes("I");
                    $AuditoriaGeneral->setValorDespues("A");
                    $AuditoriaGeneral->setUsucreaId($_SESSION["usuario"]);
                    $AuditoriaGeneral->setUsumodifId(0);
                    
                    /* Código que registra una auditoría general en la base de datos. */
                    $AuditoriaGeneral->setEstado("A");
                    $AuditoriaGeneral->setDispositivo(0);
                    $AuditoriaGeneral->setObservacion($Note);
                    $AuditoriaGeneral->setData($ProductoMandante->prodmandanteId);

                    $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO($Transaction);
                    $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);





                } catch (Exception $e) {
                    
                    /* Imprime de forma legible la estructura y contenido de la variable $e en PHP. */
                    print_r($e);

                    if ($e->getCode() == "27") {


/* Se crea un objeto ProductoMandante y se asignan propiedades específicas. */
                        $ProductoMandante = new ProductoMandante();

                        $ProductoMandante->mandante = $Partner;
                        $ProductoMandante->productoId = $value;
                        $ProductoMandante->estado = 'A';
                        $ProductoMandante->verifica = 'I';

/* configura atributos de un objeto ProductoMandante, estableciendo filtros y valores. */
                        $ProductoMandante->filtroPais = 'I';
                        $ProductoMandante->filtroPais = 'I';
                        $ProductoMandante->max = 0;
                        $ProductoMandante->min = 0;
                        $ProductoMandante->detalle = '';
                        $ProductoMandante->orden = 100000;

/* Asigna valores a propiedades del objeto ProductoMandante para inicializarlo. */
                        $ProductoMandante->numFila = 1;
                        $ProductoMandante->numColumna = 1;
                        $ProductoMandante->ordenDestacado = 0;
                        $ProductoMandante->usucreaId = $_SESSION["usuario"];
                        $ProductoMandante->usumodifId = $_SESSION["usuario"];
                        $ProductoMandante->paisId = $CountrySelect;


/* Se crea una auditoría general registrando usuario e IP en sesión. */
                        $AuditoriaGeneral = new AuditoriaGeneral();
                        $AuditoriaGeneral->setUsuarioId($_SESSION["usuario"]);
                        $AuditoriaGeneral->setUsuarioIp($ip);
                        $AuditoriaGeneral->setUsuariosolicitaId($_SESSION["usuario"]);
                        $AuditoriaGeneral->setUsuariosolicitaIp($ip);
                        $AuditoriaGeneral->setUsuariosolicitaId(0);
                        
                        /* Configura los parámetros de auditoría para la activación de un producto. */
                        $AuditoriaGeneral->setUsuarioaprobarIp(0);
                        $AuditoriaGeneral->setTipo("ACTIVACIONDEPRODUCTOMANDANTE");
                        $AuditoriaGeneral->setValorAntes("I");
                        $AuditoriaGeneral->setValorDespues("A");
                        $AuditoriaGeneral->setUsucreaId($_SESSION["usuario"]);
                        $AuditoriaGeneral->setUsumodifId(0);
                        
                        /* Se registra una auditoría general con estado, dispositivo y observación en la base de datos. */
                        $AuditoriaGeneral->setEstado("A");
                        $AuditoriaGeneral->setDispositivo(0);
                        $AuditoriaGeneral->setObservacion($Note);
                        $AuditoriaGeneral->setData($ProductoMandante->prodmandanteId);

                        $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO($Transaction);
                        $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);


/* Se inserta un producto en la base de datos utilizando un DAO. */
                        $ProductoMandanteMySqlDAO = new ProductoMandanteMySqlDAO($Transaction);
                        $ProductoMandanteMySqlDAO->insert($ProductoMandante);
                        $insertOrUpdate=true;


                    }

                }

            }

            
            /* Confirma los cambios realizados en una transacción de base de datos. */
            $Transaction->commit();


        }

            
            /* Actualiza la base de datos de un proveedor si se cumple una condición. */
            if($insertOrUpdate){
                $CMSProveedor = new \Backend\cms\CMSProveedor('CASINO','',$Partner,$CountrySelect);
                $CMSProveedor->updateDatabaseCasino();
            }


        $response["HasError"] = false;

/* Código PHP que configura una respuesta de éxito con mensaje y sin errores. */
        $response["AlertType"] = "success";
        $response["AlertMessage"] = $msg;
        $response["ModelErrors"] = [];

        $response["Data"] = [];
    }else{
/* maneja una respuesta exitosa sin errores en una estructura JSON. */

        $response["HasError"] = true;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = $msg;
        $response["ModelErrors"] = [];

    }
} catch (Exception $e) {
/* Captura excepciones y muestra información detallada sobre el error en PHP. */

    print_r($e);
}
