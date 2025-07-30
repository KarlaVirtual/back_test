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
 * Obtiene productos asociados a una categoría de socios.
 *
 * Este script procesa una solicitud para recuperar productos asociados a una categoría, 
 * aplicando filtros y devolviendo los resultados en un formato estructurado.
 *
 * @param array $_REQUEST Parámetros de solicitud HTTP. Contiene:
 * @param int $_REQUEST->count Número máximo de registros a recuperar.
 * @param int $_REQUEST->start Índice inicial para la paginación.
 * @param int $_REQUEST->Categorie Identificador de la categoría.
 * @param int $_REQUEST->Partner Identificador del socio.
 * @param int $_REQUEST->Product Identificador del producto.
 * @param int $_REQUEST->ProviderId Identificador del proveedor.
 * @param int $_REQUEST->SubProviderId Identificador del sub
 * @param string $_REQUEST->Name Nombre del producto.
 * @param int $_REQUEST->CountrySelect Identificador del país seleccionado.
 * @param string $_REQUEST->Desktop Estado del producto en escritorio (A: Activo, I: Inactivo).
 * @param string $_REQUEST->Mobile Estado del producto en móvil (A: Activo, I: Inactivo).
 * 
 *
 * @return array $response Respuesta en formato JSON con los siguientes valores:
 *                         - HasError (bool): Indica si ocurrió un error.
 *                         - AlertType (string): Tipo de alerta (success o error).
 *                         - AlertMessage (string): Mensaje de alerta.
 *                         - ModelErrors (array): Lista de errores del modelo.
 *                         - Data (array): Datos estructurados de productos y categorías.
 *
 * @throws Exception Captura cualquier excepción durante la recuperación de productos, 
 *                   devolviendo un error en la respuesta.
 */

try {

    /* inicializa variables de paginación basadas en parámetros de solicitud. */
    $MaxRows = $_REQUEST["count"];
    $OrderedItem = $params->OrderedItem;
    $SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];

    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }


    /* inicializa variables si están vacías, estableciendo valores predeterminados. */
    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }

    if ($MaxRows == "") {
        $MaxRows = 10000;
    }


    /* asigna el valor de "Categorie" desde parámetros y solicitudes HTTP. */
    $Categorie = $params->Categorie;
    $Categorie = $_REQUEST["Categorie"];

    if ($Categorie != '') {

        /* asigna valores de parámetros a variables en un contexto determinado. */
        $Id = $params->Id;
        $IsActivate = $params->IsActivate;
        $IsVerified = $params->IsVerified;
        $FilterCountry = $params->FilterCountry;
        $Products = $params->Products;
        $Partner = $params->Partner;

        /* asigna parámetros de entrada a variables para su posterior uso. */
        $Minimum = $params->Minimum;
        $Maximum = $params->Maximum;
        $Product = $params->Product;


        $Id = $_REQUEST["Id"];

        /* asigna valores a variables según condiciones específicas de entrada. */
        $IsActivate = ($_REQUEST["IsActivate"] == "A" || $_REQUEST["IsActivate"] == "I") ? $_REQUEST["IsActivate"] : '';;
        $IsVerified = ($_REQUEST["IsVerified"] == "A" || $_REQUEST["IsVerified"] == "I") ? $_REQUEST["IsVerified"] : '';
        $FilterCountry = ($_REQUEST["FilterCountry"] == "A" || $_REQUEST["FilterCountry"] == "I") ? $_REQUEST["FilterCountry"] : '';
        $Products = $_REQUEST["Products"];
        $Partner = $_REQUEST["Partner"];
        $PartnerReference = $_REQUEST["PartnerReference"];


        /* obtiene y valida parámetros de entrada de una solicitud HTTP. */
        $Minimum = $_REQUEST["Minimum"];
        $Maximum = $_REQUEST["Maximum"];
        $Product = $_REQUEST["Product"];
        $ProviderId = ($_REQUEST["ProviderId"] > 0 && is_numeric($_REQUEST["ProviderId"]) && $_REQUEST["ProviderId"] != '') ? $_REQUEST["ProviderId"] : '';
        $SubProviderId = ($_REQUEST["SubProviderId"] > 0 && is_numeric($_REQUEST["SubProviderId"]) && $_REQUEST["SubProviderId"] != '') ? $_REQUEST["SubProviderId"] : '';
        $Name = $_REQUEST["Name"];


        /* captura y valida selecciones de país y dispositivos para configurar valores. */
        $CountrySelect = $_REQUEST["CountrySelect"];
        $Desktop = ($_REQUEST["Desktop"] == "A" || $_REQUEST["Desktop"] == "I") ? $_REQUEST["Desktop"] : '';
        $Mobile = ($_REQUEST["Mobile"] == "A" || $_REQUEST["Mobile"] == "I") ? $_REQUEST["Mobile"] : '';
        if ($Desktop == "A") {
            $Desktop = 'S';
        } elseif ($Desktop == "I") {
            /* Asigna 'N' a $Desktop si su valor es igual a "I". */

            $Desktop = 'N';
        }


        /* asigna valores a la variable $Mobile basado en condiciones específicas. */
        if ($Mobile == "A") {
            $Mobile = 'S';
        } elseif ($Mobile == "I") {
            $Mobile = 'N';
        }


        $ProductoMandante = new ProductoMandante();


        /* Se crean reglas para validar el campo "mandante" según el valor de $Partner. */
        $rules = [];

        if ($Partner != "") {

            array_push($rules, array("field" => "categoria_producto.mandante", "data" => "$Partner", "op" => "eq"));
        }


        /* Agrega reglas a un array según el valor de $Product y la sesión Global. */
        if ($Product != "") {
            if ($_SESSION["Global"] == "S") {
                array_push($rules, array("field" => "producto.producto_id", "data" => "$Product", "op" => "eq"));

            } else {
                array_push($rules, array("field" => "producto_mandante.prodmandante_id", "data" => "$Product", "op" => "eq"));

            }

        }


        /* Agrega reglas de filtrado según los IDs de proveedor o subproveedor proporcionados. */
        if ($ProviderId != "") {

            array_push($rules, array("field" => "producto.proveedor_id", "data" => "$ProviderId", "op" => "eq"));
        }
        if ($SubProviderId != "") {

            array_push($rules, array("field" => "producto.subproveedor_id", "data" => "$SubProviderId", "op" => "eq"));
        }


        /* Añade reglas a un arreglo según condiciones de nombre y país seleccionados. */
        if ($Name != "") {

            array_push($rules, array("field" => "producto.descripcion", "data" => "$Name", "op" => "cn"));
        }
        if ($CountrySelect != "") {

            array_push($rules, array("field" => "categoria_producto.pais_id", "data" => "$CountrySelect", "op" => "eq"));
        }


        /* agrega reglas para un filtro basado en condiciones específicas. */
        if ($Categorie != "") {

            array_push($rules, array("field" => "categoria_producto.categoria_id", "data" => "$Categorie", "op" => "eq"));
        }


// Si el usuario esta condicionado por el mandante y no es de Global
        if ($_SESSION['Global'] == "N") {
            //array_push($rules, array("field" => "producto_mandante.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
        }


        /* Se define un filtro de reglas para buscar productos con estado "A". */
        array_push($rules, array("field" => "categoria_producto.estado", "data" => "A", "op" => "eq"));

        $orden = "producto.descripcion";
        $ordenTipo = "asc";


        $filtro = array("rules" => $rules, "groupOp" => "AND");

        /* convierte un filtro a JSON y obtiene productos de una categoría específica. */
        $jsonfiltro = json_encode($filtro);

        $CategoriaProducto = new CategoriaProducto();
        $productos = $CategoriaProducto->getCategoriaProductosCustom(" categoria_producto.* ", $orden, $ordenTipo, $SkeepRows, $MaxRows, $jsonfiltro, true);

        $productos = json_decode($productos);


        /* Se inicializan variables para gestionar productos y sus categorías en un array. */
        $productosString = '##';

        $final = [];

        $children_final = [];
        $children_final2 = [];


        /* recorre productos y concatena IDs de categorías en una cadena. */
        foreach ($productos->data as $key => $value) {

            $productosString = $productosString . "," . $value->{"categoria_producto.producto_id"};

        }


        $MaxRows = $_REQUEST["count"];

        /* establece la cantidad de filas a omitir en una solicitud. */
        $OrderedItem = $params->OrderedItem;
        $SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];

        if ($SkeepRows == "") {
            $SkeepRows = 0;
        }


        /* Establece valores predeterminados para $OrderedItem y $MaxRows si están vacíos. */
        if ($OrderedItem == "") {
            $OrderedItem = 1;
        }

        if ($MaxRows == "") {
            $MaxRows = 10000000;
        }


        /* Código que asigna parámetros a variables en un contexto de programación. */
        $Desktop = $params->Desktop;
        $ExternalId = $params->ExternalId;
        $Id = $params->Id;
        $Image = $params->Image;
        $IsActivate = $params->IsActivate;
        $IsVerified = $params->IsVerified;

        /* Asignación y validación de parámetros relacionados con un proveedor y un pedido. */
        $Mobile = $params->Mobile;
        $Name = $params->Name;
        $Order = $params->Order;
        $ProviderId = $params->ProviderId;
        $Visible = $params->Visible;

        $ProviderId = ($_REQUEST["ProviderId"] > 0 && is_numeric($_REQUEST["ProviderId"]) && $_REQUEST["ProviderId"] != '') ? $_REQUEST["ProviderId"] : '';

        /* Validación y asignación de parámetros de entrada en una solicitud HTTP. */
        $SubProviderId = ($_REQUEST["SubProviderId"] > 0 && is_numeric($_REQUEST["SubProviderId"]) && $_REQUEST["SubProviderId"] != '') ? $_REQUEST["SubProviderId"] : '';

        $Product = $_REQUEST["Product"];
        $ProductId = $_REQUEST["ProductId"];

        $ExternalId = $_REQUEST["ExternalId"];

        /* obtiene datos de una solicitud HTTP en variables PHP. */
        $Id = $_REQUEST["Id"];
        $Image = $_REQUEST["Image"];
        $IsActivate = $_REQUEST["IsActivate"];
        $IsVerified = $_REQUEST["IsVerified"];

        $Name = $_REQUEST["Name"];

        /* procesa datos de entrada para variables sobre orden y visibilidad. */
        $Order = $_REQUEST["Order"];
        $Visible = $_REQUEST["Visible"];

        $Desktop = ($_REQUEST["Desktop"] == "A" || $_REQUEST["Desktop"] == "I") ? $_REQUEST["Desktop"] : '';
        $Mobile = ($_REQUEST["Mobile"] == "A" || $_REQUEST["Mobile"] == "I") ? $_REQUEST["Mobile"] : '';
        if ($Desktop == "A") {
            $Desktop = 'S';
        } elseif ($Desktop == "I") {
            /* Si $Desktop es "I", se asigna el valor 'N' a $Desktop. */

            $Desktop = 'N';
        }


        /* asigna valores a la variable $Mobile según su contenido y crea un objeto Producto. */
        if ($Mobile == "A") {
            $Mobile = 'S';
        } elseif ($Mobile == "I") {
            $Mobile = 'N';
        }


        $Producto = new Producto();


        /* Se crean reglas de validación para campos de producto según condiciones específicas. */
        $rules = [];

        if ($Desktop != "") {
            array_push($rules, array("field" => "producto.desktop", "data" => "$Desktop", "op" => "eq"));
        }

        if ($Mobile != "") {
            array_push($rules, array("field" => "producto.mobile", "data" => "$Mobile", "op" => "eq"));
        }

        /* Añade reglas de filtrado basadas en condiciones de $ExternalId y $Id. */
        if ($ExternalId != "") {
            array_push($rules, array("field" => "producto.externo_id", "data" => "$ExternalId", "op" => "eq"));
        }
        if ($Id != "") {
            array_push($rules, array("field" => "producto.producto_id", "data" => "$Id", "op" => "eq"));
        }


        /* valida y ajusta estados de activación y verificación para productos. */
        if ($IsActivate != "" && $IsActivate != null) {
            $IsActivate = ($IsActivate == 'A') ? 'A' : 'I';

            array_push($rules, array("field" => "producto.estado", "data" => "$IsActivate", "op" => "eq"));
        }
        if ($IsVerified != "" && $IsVerified != null) {
            $IsVerified = ($IsVerified == 'A') ? 'A' : 'I';

            array_push($rules, array("field" => "producto.verifica", "data" => "$IsVerified", "op" => "eq"));
        }

        /* Agrega condiciones de búsqueda a un arreglo según valores de $Name y $Order. */
        if ($Name != "") {
            array_push($rules, array("field" => "producto.descripcion", "data" => "$Name", "op" => "cn"));
        }
        if ($Order != "") {
            array_push($rules, array("field" => "producto.orden", "data" => "$Order", "op" => "eq"));
        }

        /* Agrega reglas de filtrado según los IDs proporcionados para productos en un array. */
        if ($ProviderId != "") {
            array_push($rules, array("field" => "producto.proveedor_id", "data" => "$ProviderId", "op" => "eq"));
        }
        if ($SubProviderId != "") {

            array_push($rules, array("field" => "producto.subproveedor_id", "data" => "$SubProviderId", "op" => "eq"));
        }

        /* Condicional que añade una regla si el producto y la sesión son válidos. */
        if ($Product != "") {
            if ($_SESSION["Global"] == "S") {
                array_push($rules, array("field" => "producto.producto_id", "data" => "$Product", "op" => "eq"));
            } else {

            }
        }


        /* verifica un producto y aplica reglas basadas en la sesión global. */
        if ($ProductId != "") {
            if ($_SESSION["Global"] == "S") {
                array_push($rules, array("field" => "producto.producto_id", "data" => "$ProductId", "op" => "eq"));
            } else {

            }
        }

        /* verifica visibilidad y establece condiciones basadas en la sesión del usuario. */
        if ($Visible != "") {
            $Visible = ($Visible == 'A') ? 'S' : 'N';

            array_push($rules, array("field" => "producto.mostrar", "data" => "$Visible", "op" => "eq"));
        }

        // Si el usuario esta condicionado por el mandante y no es de Global
        if ($_SESSION['Global'] == "N") {
            $Partner = $_SESSION['mandante'];
        } else {
            /* Estructura condicional 'else' vacía, sin instrucciones dentro para ejecutar. */


        }


        /* Filtra y obtiene productos personalizados en formato JSON desde una base de datos. */
        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $jsonfiltro = json_encode($filtro);


        $productos = $Producto->getProductosCustomMandante(" producto.*,proveedor.*,subproveedor.descripcion ", "producto.descripcion", "asc", $SkeepRows, $MaxRows, $jsonfiltro, true, $Partner);

        $productos = json_decode($productos);


        /* construye un array final con detalles de productos y sus subproveedores. */
        $final = [];

        foreach ($productos->data as $key => $value) {

            $array = [];

            $children = [];
            $children["id"] = $value->{"producto.producto_id"};
            $children["value"] = $value->{"subproveedor.descripcion"} . ' - ' . $value->{"producto.descripcion"} . " (" . $value->{"producto.producto_id"} . ")";

            array_push($children_final, $children);

        }

        if ($PartnerReference != "" && $PartnerReference != "-1") {

            /* Se añade una regla si existe un PartnerReference para comparar categoría de producto. */
            $rules = [];

            if ($PartnerReference != "") {

                array_push($rules, array("field" => "categoria_producto.mandante", "data" => "$PartnerReference", "op" => "eq"));
            }


            /* Se añade una regla al array según condiciones sobre referencia y producto. */
            if ($PartnerReference == '0') {
                array_push($rules, array("field" => "categoria_producto.pais_id", "data" => "173", "op" => "eq"));

            }
            if ($Product != "") {
                if ($_SESSION["Global"] == "S") {
                    array_push($rules, array("field" => "producto.producto_id", "data" => "$Product", "op" => "eq"));

                } else {
                    array_push($rules, array("field" => "producto_mandante.prodmandante_id", "data" => "$Product", "op" => "eq"));

                }

            }


            /* Añade reglas de filtro para proveedor y subproveedor si los IDs no están vacíos. */
            if ($ProviderId != "") {

                array_push($rules, array("field" => "producto.proveedor_id", "data" => "$ProviderId", "op" => "eq"));
            }
            if ($SubProviderId != "") {

                array_push($rules, array("field" => "producto.subproveedor_id", "data" => "$SubProviderId", "op" => "eq"));
            }


            /* Agrega reglas condicionales a un arreglo basadas en variables no vacías. */
            if ($Name != "") {

                array_push($rules, array("field" => "producto.descripcion", "data" => "$Name", "op" => "cn"));
            }


            if ($Categorie != "") {

                array_push($rules, array("field" => "categoria_producto.categoria_id", "data" => "$Categorie", "op" => "eq"));
            }


// Si el usuario esta condicionado por el mandante y no es de Global

            /* gestiona reglas y ordena productos según un estado específico. */
            if ($_SESSION['Global'] == "N") {
                //array_push($rules, array("field" => "producto_mandante.mandante", "data" => $_SESSION['mandante'], "op" => "eq"));
            }

            array_push($rules, array("field" => "categoria_producto.estado", "data" => "A", "op" => "eq"));

            $orden = "producto.descripcion";

            /* Código define una consulta con filtro y ordenamiento para categorías de productos. */
            $ordenTipo = "asc";


            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $jsonfiltro = json_encode($filtro);

            $CategoriaProducto = new CategoriaProducto();

            /* Se obtienen productos personalizados de una categoría y se decodifican en JSON. */
            $productos = $CategoriaProducto->getCategoriaProductosCustom(" categoria_producto.*,producto.* ", $orden, $ordenTipo, $SkeepRows, $MaxRows, $jsonfiltro, true);

            $productos = json_decode($productos);

            $final = [];

            $children_final = [];

            /* crea un array de productos con información de sus categorías y descripciones. */
            $children_final2 = [];


            foreach ($productos->data as $key => $value) {

                $array = [];

                $children = [];
                $children["id"] = $value->{"categoria_producto.producto_id"};
                $children["value"] = $value->{"subproveedor.descripcion"} . ' - ' . $value->{"producto.descripcion"} . " (" . $value->{"producto.producto_id"} . ")";

                array_push($children_final, $children);

            }

        }


        /* inicializa un arreglo de respuesta con un estado exitoso y datos específicos. */
        $response["HasError"] = false;
        $response["AlertType"] = "success";
        $response["AlertMessage"] = "";
        $response["ModelErrors"] = [];

//$response["Data"] = array("Objects" => $final, "Count" => $productos->count[0]->{".count"});
        $response["Data"]["ExcludedCategoriesList"] = $children_final;

        /* Código que procesa datos de productos, limpiando cadenas y organizando la respuesta. */
        $response["Data"]["IncludedCategoriesList"] = str_replace("##", "", str_replace("##,", "", $productosString));

        $response["pos"] = $SkeepRows;
        $response["total_count"] = $productos->count[0]->{".count"};
        $response["data"] = $final;

    }
} catch (Exception $e) {
    /* Captura excepciones y muestra información sobre el error en el código. */

    print_r($e);
}
