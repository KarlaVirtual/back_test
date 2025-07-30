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

/**
 * PromotionalCodes/SaveCode
 *
 * Crea un código promocional si no existe previamente en la base de datos.
 *
 * Este método valida si los campos necesarios están presentes, luego consulta si ya existe un código promocional
 * para la marca y país proporcionados. Si no existe, crea un nuevo código promocional y lo guarda en la base de datos.
 *
 * @param object $params : Objeto que contiene los parámetros para la creación del código promocional.
 *
 * El objeto $params contiene los siguientes atributos:
 *  - *Name* (string): Nombre del código promocional.
 *  - *Code* (string): Código único del código promocional.
 *  - *State* (string): Estado del código promocional.
 *  - *User* (int): ID del usuario que está creando el código promocional.
 *  - *Function* (string): Función asociada al código promocional.
 *  - *Country* (int): ID del país asociado al código promocional.
 *
 * @returns object El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error en la operación.
 *  - *AlertType* (string): Especifica el tipo de alerta que se mostrará en la vista ("warning" si hay campos vacíos, "success" en caso contrario).
 *  - *AlertMessage* (string): Contiene el mensaje que se mostrará en la vista.
 *  - *ModelErrors* (array): Retorna array vacío en este caso.
 *
 * Objeto en caso de error (si los campos están vacíos):
 *
 * "HasError" => true,
 * "AlertType" => "warning",
 * "AlertMessage" => "Evite enviar campos vacios - Error 10020",
 * "ModelErrors" => array(),
 *
 * Objeto en caso de que el código promocional ya exista:
 *
 * "HasError" => true,
 * "AlertType" => "success",
 * "AlertMessage" => "El codigo ya existe.",
 * "ModelErrors" => array(),
 *
 * @throws Exception Si ocurre un error al intentar crear el código promocional o al validar los datos.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* asigna valores de parámetros a variables en un script. */
$Name = $params->Name;
$Code = $params->Code;
$State = $params->State;
$User = $params->User;
$Function = $params->Function;
$Country = $params->Country;

try {
    /**Validando que los campos no se encuentren vacíos */

    /* Valida que los parámetros no estén vacíos y genera un mensaje de error. */
    foreach ($params as $param) {
        if ($param == null || $param == "") {
            $response["HasError"] = true;
            $response["AlertType"] = "warning";
            $response["AlertMessage"] = "Evite enviar campos vacios - Error 10020";
            $response["ModelErrors"] = [];
            return;
        }
    }


    /**Consultando si el código promocional ya existe para esa marca/país */

    /* Se construye una consulta con filtros para códigos promocionales específicos. */
    $select = "codigo_promocional.*";
    $rules = [];
    $filters = [];
    array_push($rules, ["field" => "codigo_promocional.codigo", "data" => $Code, "op" => "eq"]);
    array_push($rules, ["field" => "codigo_promocional.mandante", "data" => $_SESSION["mandante"], "op" => "eq"]);
    array_push($rules, ["field" => "codigo_promocional.pais_id", "data" => $Country, "op" => "eq"]);

    /* Filtra y consulta códigos promocionales en la base de datos utilizando PHP y MySQL. */
    $filters = ["rules" => $rules, "groupOp" => "AND"];
    $CodigoPromocionalMySqlDAO = new CodigoPromocionalMySqlDAO();
    $codigosPromocionales = $CodigoPromocionalMySqlDAO->queryCodigoPromocionalsCustom($select, "codigo_promocional.codpromocional_id", "desc", 0, 1, json_encode($filters), true);
    $codigosPromocionales = json_decode($codigosPromocionales)->data;

    $promotionalCodeExist = false;

    /* Verifica si existe un código promocional para un país específico en la lista. */
    if (count($codigosPromocionales) > 0) {
        foreach ($codigosPromocionales as $codigoPromocional) {
            if ($codigoPromocional->{'codigo_promocional.pais_id'} == $Country) {
                $promotionalCodeExist = true;
                break;
            }
        }
    }


    /* verifica si un código promocional existe, lanzando una excepción si no. */
    if (!$promotionalCodeExist) {
        $CodigoPromocional = new CodigoPromocional();
        throw new Exception('No existe ' . get_class($CodigoPromocional), "85");
    }

    $response["HasError"] = true;

    /* Código que establece una respuesta de éxito con un mensaje de advertencia y errores vacíos. */
    $response["AlertType"] = "success";
    $response["AlertMessage"] = "El codigo ya existe.";
    $response["ModelErrors"] = [];
} catch (Exception $e) {

    if ($e->getCode() == "85") {

        /* Crea un objeto "CodigoPromocional" con sus propiedades definidas. */
        $CodigoPromocional = new CodigoPromocional();
        $CodigoPromocional->setEstado($State);
        $CodigoPromocional->setDescripcion($Name);
        $CodigoPromocional->setCodigo($Code);
        $CodigoPromocional->setUsuarioId($User);
        $CodigoPromocional->setFuncion($Function);

        /* Configura datos para un objeto de código promocional en PHP y lo instancia. */
        $CodigoPromocional->setUsucreaId(0);
        $CodigoPromocional->setUsumodifId(0);
        $CodigoPromocional->setLinkId(0);
        $CodigoPromocional->setMandante($_SESSION["mandante"]);
        $CodigoPromocional->setPaisId($Country);

        $CodigoPromocionalMySqlDAO = new CodigoPromocionalMySqlDAO();

        /* Inserta un código promocional y confirma la transacción sin errores. */
        $CodigoPromocionalMySqlDAO->insert($CodigoPromocional);
        $CodigoPromocionalMySqlDAO->getTransaction()->commit();

        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";

        /* Inicializa un array vacío para almacenar errores de modelo en la respuesta. */
        $response["ModelErrors"] = [];
    } else {
        /* maneja excepciones, lanzando un error si ocurre una excepción. */

        throw $e;
    }

}
