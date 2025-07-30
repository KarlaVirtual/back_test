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
 * Guarda o actualiza las categorías de socios en el sistema.
 *
 * Este script procesa una solicitud para incluir o excluir categorías de socios,
 * actualizando la base de datos según los parámetros proporcionados.
 *
 * @param object $params Datos de entrada en formato JSON. Contiene:
 * @param int $params->Partner Identificador del socio.
 * @param int $params->Categorie Identificador de la categoría.
 * @param string $params->ExcludedCategoriesList Lista de categorías excluidas (separadas por comas).
 * @param string $params->IncludedCategoriesList Lista de categorías incluidas (separadas por comas).
 * @param string $params->permAdd Permisos para agregar (separados por comas).
 * @param string $params->permDelete Permisos para eliminar (separados por comas).
 * @param int $params->CountrySelect Identificador del país seleccionado.
 * @param string $params->Note Nota o comentario del cambio.
 *
 *
 * @return array $response Respuesta en formato JSON con los siguientes valores:
 *                         - HasError (bool): Indica si ocurrió un error.
 *                         - AlertType (string): Tipo de alerta (success o error).
 *                         - AlertMessage (string): Mensaje de alerta.
 *                         - ModelErrors (array): Lista de errores del modelo.
 *                         - Data (array): Datos adicionales de la respuesta.
 *
 * @throws Exception Captura cualquier excepción durante la actualización o inserción de categorías,
 *                   devolviendo un error en la respuesta.
 *
 * @throws Exception Si la nota está vacía o nula.
 */

try {


    /* verifica condiciones de país y obtiene parámetros de socio y categoría. */
    $insertOrUpdate = false;

    $Partner = $params->Partner;
    $Categorie = $params->Categorie;


// Si el usuario esta condicionado por País
    if ($_SESSION["PaisCondS"] != '') {
        $CountrySelect = $_SESSION['PaisCondS'];
    } else {
        /* Selecciona el país basado en condiciones de sesión o solicitudes del usuario. */

        if ($_SESSION['PaisCond'] == "S") {
            $CountrySelect = $_SESSION['pais_id'];
        } else {
            $CountrySelect = $_REQUEST["CountrySelect"];

            if ($CountrySelect == '') {
                $CountrySelect = $params->CountrySelect;

            }
        }
    }


    /* convierte cadenas de texto en arreglos, basándose en valores de parámetros. */
    $ExcludedCategoriesList = ($params->ExcludedCategoriesList != "") ? explode(",", $params->ExcludedCategoriesList) : array();
    $IncludedCategoriesList = ($params->IncludedCategoriesList != "") ? explode(",", $params->IncludedCategoriesList) : array();
    $Note = $params->Note;

    $ip = !empty($_SERVER['HTTP_X_FORWADED_']) ? $_SERVER['HTTP_X_FORWADED_FOR'] : $SERVER['REMOTE_ADDR'];
    /* extrae la primera IP y define una función para detectar dispositivos móviles. */
    $ip = explode(",", $ip)[0];

    function detectarTipoDispositivo($userAgent)
    {
        $dispositivosMoviles = array(
            '/iphone/i',
            '/ipod/i',
            '/ipad/i',
            '/android/i',
            '/blackberry/i',
            '/webos/i'
        );


        /* determina si el usuario utiliza un dispositivo móvil o escritorio. */
        foreach ($dispositivosMoviles as $pattern) {
            if (preg_match($pattern, $userAgent)) {
                return 'mobile';
            }
        }
        return 'desktop';
    }


    $userAgent = $_SERVER['HTTP_USER_AGENT'];

    /* Detecta el tipo de dispositivo utilizando la información del agente de usuario. */
    $tipoDispositivo = detectarTipoDispositivo($userAgent);



    if($Note == "" || $Note == null){ /*Verificamos si la Nota se encuentra vacia o null*/
        throw new exception("La observacion es obligatoria",300164); /*En caso de que el comentario este vacio se lanza la excepcion*/
    }

    $permAdd = ($params->permAdd != "") ? explode(",", $params->permAdd) : array();
    $permDelete = ($params->permDelete != "") ? explode(",", $params->permDelete) : array();

    if ($Partner != '' && $Categorie != '') {


        if (oldCount($ExcludedCategoriesList) > 0) {


            /* Actualiza categorías excluidas en una base de datos, manejando excepciones. */
            $CategoriaProductoMySqlDAO = new CategoriaProductoMySqlDAO();
            $Transaction = $CategoriaProductoMySqlDAO->getTransaction();

            foreach ($ExcludedCategoriesList as $key => $value) {
                try {
                    $CategoriaProducto = new CategoriaProducto('',$value, '',$Categorie,$Partner,$CountrySelect,'A');

                    $CategoriaProducto->estado = 'I';
                    $CategoriaProductoMySqlDAO = new CategoriaProductoMySqlDAO($Transaction);
                    $CategoriaProductoMySqlDAO->update($CategoriaProducto);

                    $insertOrUpdate = true;

                    $AuditoriaGeneral = new AuditoriaGeneral();

                    /* Código que configura datos de usuario e IP para una auditoría general. */
                    $AuditoriaGeneral->setUsuarioId($_SESSION["usuario"]);
                    $AuditoriaGeneral->setUsuarioIp($ip);
                    $AuditoriaGeneral->setUsuariosolicitaId($_SESSION["usuario"]);
                    $AuditoriaGeneral->setUsuariosolicitaIp($ip);
                    $AuditoriaGeneral->setUsuariosolicitaId(0);
                    $AuditoriaGeneral->setUsuarioaprobarIp(0);

                    /* Registro de auditoría para desactivación de subproveedor, indicando cambios y usuario responsable. */
                    $AuditoriaGeneral->setTipo("DESACTIVACIONDECATEGORIAPRODUCTO");
                    $AuditoriaGeneral->setValorAntes("A");
                    $AuditoriaGeneral->setValorDespues("I");
                    $AuditoriaGeneral->setUsucreaId($_SESSION["usuario"]);
                    $AuditoriaGeneral->setUsumodifId(0);
                    $AuditoriaGeneral->setEstado("A");

                    /* Configura un dispositivo y observa, luego inserta auditoría en MySQL. */
                    $AuditoriaGeneral->setDispositivo($tipoDispositivo);
                    $AuditoriaGeneral->setObservacion($Note); /*Se guarda en el campo observacion la descripcion del cambio*/


                    $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO($Transaction);
                    $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);
                    $AuditoriaGeneralMySqlDAO->getTransaction()->commit();



                } catch (Exception $e) {
                    if ($e->getCode() == "49") {

                    }

                }

            }


            /* finaliza exitosamente una transacción en una base de datos, guardando cambios. */
            $Transaction->commit();

        }

        if (oldCount($IncludedCategoriesList) > 0) {

            /* Inicializa un objeto DAO y obtiene una transacción de la base de datos. */
            $CategoriaProductoMySqlDAO = new CategoriaProductoMySqlDAO();
            $Transaction = $CategoriaProductoMySqlDAO->getTransaction();

            foreach ($IncludedCategoriesList as $key => $value) {


                /* actualiza la categoría de un producto en la base de datos. */
                try {
                    $CategoriaProducto = new CategoriaProducto('', $value, '', $Categorie, $Partner, $CountrySelect);

                    $beforeState = $CategoriaProducto->getEstado();

                    $CategoriaProducto->estado = 'A';

                    if($beforeState != $CategoriaProducto->getEstado()){
                        $CategoriaProductoMySqlDAO = new CategoriaProductoMySqlDAO($Transaction);
                        $CategoriaProductoMySqlDAO->update($CategoriaProducto);

                        $insertOrUpdate = true;


                        $AuditoriaGeneral = new AuditoriaGeneral();




                        /* Código establece identificadores de usuario y IP para auditoría general. */
                        $AuditoriaGeneral->setUsuarioId($_SESSION["usuario"]);
                        $AuditoriaGeneral->setUsuarioIp($ip);
                        $AuditoriaGeneral->setUsuariosolicitaId($_SESSION["usuario"]);
                        $AuditoriaGeneral->setUsuariosolicitaIp($ip);
                        $AuditoriaGeneral->setUsuariosolicitaId(0);
                        $AuditoriaGeneral->setUsuarioaprobarIp(0);

                        /* Código para registrar una auditoría de activación de subproveedor en el sistema. */
                        $AuditoriaGeneral->setTipo("ACTIVACIONDECATEGORIAPRODUCTO");
                        $AuditoriaGeneral->setValorAntes("I");
                        $AuditoriaGeneral->setValorDespues("A");
                        $AuditoriaGeneral->setUsucreaId($_SESSION["usuario"]);
                        $AuditoriaGeneral->setUsumodifId(0);
                        $AuditoriaGeneral->setEstado("A");

                        /* Código que establece parámetros y registra auditoría en base de datos. */
                        $AuditoriaGeneral->setDispositivo($tipoDispositivo);
                        $AuditoriaGeneral->setObservacion($Note); /*Se guarda en el campo observacion la descripcion del cambio*/

                        $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO($Transaction);
                        $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);
                        $AuditoriaGeneralMySqlDAO->getTransaction()->commit();

                    }

                } catch (Exception $e) {
                    if ($e->getCode() == "49") {


                        /* crea un objeto de categoría de producto y asigna valores específicos. */
                        $CategoriaProducto = new CategoriaProducto();


                        $CategoriaProducto->setCategoriaId($Categorie);
                        $CategoriaProducto->setProductoId($value);

                        $CategoriaProducto->setUsucreaId($_SESSION['usuario2']);

                        /* Configuración de atributos para un objeto "CategoriaProducto" en una sesión de usuario. */
                        $CategoriaProducto->setUsumodifId($_SESSION['usuario2']);


                        $CategoriaProducto->setEstado('A');
                        $CategoriaProducto->setOrden(100000);
                        $CategoriaProducto->setMandante($Partner);

                        /* Se inserta un producto de categoría en la base de datos. */
                        $CategoriaProducto->paisId = $CountrySelect;

                        $CategoriaProductoMySqlDAO = new CategoriaProductoMySqlDAO($Transaction);
                        $CategoriaProductoMySqlDAO->insert($CategoriaProducto);

                        $insertOrUpdate = true;


                        $AuditoriaGeneral = new AuditoriaGeneral();




                        /* Código establece identificadores de usuario y IP para auditoría general. */
                        $AuditoriaGeneral->setUsuarioId($_SESSION["usuario"]);
                        $AuditoriaGeneral->setUsuarioIp($ip);
                        $AuditoriaGeneral->setUsuariosolicitaId($_SESSION["usuario"]);
                        $AuditoriaGeneral->setUsuariosolicitaIp($ip);
                        $AuditoriaGeneral->setUsuariosolicitaId(0);
                        $AuditoriaGeneral->setUsuarioaprobarIp(0);

                        /* Código para registrar una auditoría de activación de subproveedor en el sistema. */
                        $AuditoriaGeneral->setTipo("ACTIVACIONDECATEGORIAPRODUCTO");
                        $AuditoriaGeneral->setValorAntes("I");
                        $AuditoriaGeneral->setValorDespues("A");
                        $AuditoriaGeneral->setUsucreaId($_SESSION["usuario"]);
                        $AuditoriaGeneral->setUsumodifId(0);
                        $AuditoriaGeneral->setEstado("A");

                        /* Código que establece parámetros y registra auditoría en base de datos. */
                        $AuditoriaGeneral->setDispositivo($tipoDispositivo);
                        $AuditoriaGeneral->setObservacion($Note); /*Se guarda en el campo observacion la descripcion del cambio*/

                        $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO($Transaction);
                        $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);
                        $AuditoriaGeneralMySqlDAO->getTransaction()->commit();

                    }

                }

            }

            /* finaliza una transacción, guardando todos los cambios realizados. */
            $Transaction->commit();

        }

        /* actualiza la base de datos de un casino si se requiere. */
        if ($insertOrUpdate) {
            $CMSProveedor = new \Backend\cms\CMSProveedor('CASINO', '', $Partner, $CountrySelect);
            $CMSProveedor->updateDatabaseCasino();
        }

        $response["HasError"] = false;

        /* define una respuesta en formato JSON con mensajes y datos vacíos. */
        $response["AlertType"] = "success";
        $response["AlertMessage"] = $msg;
        $response["ModelErrors"] = [];

        $response["Data"] = [];
    } else {
        /* establece una respuesta JSON indicando un error sin detalles específicos. */

        $response["HasError"] = true;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = $msg;
        $response["ModelErrors"] = [];

    }
} catch (Exception $e) {
    /* Captura excepciones y muestra detalles del error en formato legible. */

    print_r($e);
}
