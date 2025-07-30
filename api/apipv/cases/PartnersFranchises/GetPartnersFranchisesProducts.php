<?php


use Backend\dto\Franquicia;
use Backend\dto\FranquiciaMandante;
use Backend\dto\FranquiciaProducto;

/**
 * Obtener Franquicias y productos asociados a un partner
 *
 * Este recurso permite obtener los franquicias y productos asociados a un partner,
 * aplicando filtros como país, estado, proveedor, nombre del franquicia, entre otros.
 *
 * @param object $params : Objeto con los siguientes atributos:
 *   - int $params->Id : ID del franquicia asociado.
 *   - string $params->Products : Productos para filtro.
 *   - int $params->Partner : ID del partner asociado.
 *   - int $params->Product : ID del producto asociado.
 *   - int $params->FranchiseId : ID del franquicia.
 *
 * @param array $_REQUEST : Arreglo con parámetros HTTP:
 *   - string $_REQUEST["Id"] : ID del franquicia asociado.
 *   - string $_REQUEST["Products"] : Productos para filtro.
 *   - string $_REQUEST["Partner"] : ID del partner asociado.
 *   - string $_REQUEST["Product"] : ID del producto asociado.
 *   - string $_REQUEST["FranchiseId"] : ID del franquicia.
 *   - string $_REQUEST["ProviderId"] : ID del proveedor (opcional).
 *   - string $_REQUEST["IsActivate"] : Estado del franquicia (A: Activo, I: Inactivo).
 *   - string $_REQUEST["Name"] : Nombre del franquicia.
 *   - string $_REQUEST["CountrySelect"] : ID del país asociado.
 *   - string $_REQUEST["TypeDevice"] : Tipo de dispositivo (opcional).
 *   - string $_REQUEST["count"] : Número máximo de filas (opcional, predeterminado: 1000).
 *   - string $_REQUEST["start"] : Número de filas a omitir (opcional, predeterminado: 0).
 *   - string $_REQUEST["sort[Order]"] : Orden de los datos (asc o desc, opcional).
 *
 * @return array $response Objeto con los atributos de respuesta requeridos:
 *   - *HasError* (bool): Indica si hubo un error en la operación.
 *   - *AlertType* (string): Especifica el tipo de alerta que se mostrará en la vista.
 *   - *AlertMessage* (string): Contiene el mensaje que se mostrará en la vista.
 *   - *ModelErrors* (array): Retorna array vacío.
 *   - *pos* (int): Número de filas omitidas (inicio de la paginación).
 *   - *total_count* (int): Total de registros encontrados según los filtros aplicados.
 *   - *data* (array): Arreglo de franquicias con sus productos asociados. Cada elemento incluye:
 *       - *Id* (int): ID del franquicia-producto.
 *       - *FranchiseId* (string): Nombre e ID del franquicia.
 *       - *Abreviado* (string): Nombre abreviado del producto.
 *       - *Product* (string): Nombre e ID del producto.
 *       - *ProviderId* (string): Descripción del proveedor.
 *       - *Partner* (string): Descripción del partner.
 *       - *TypeProduct* (string): Tipo de producto del proveedor.
 *       - *IsActivate* (string): Estado del franquicia-producto.
 *       - *Imagen* (string): Ruta o nombre de la imagen asociada.
 *
 * @throws Exception Si el partner no está autorizado o se detecta una anomalía en la solicitud.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


//Verificamos data enviada por Frontend

/* obtiene parámetros de solicitud para manejar paginación de datos. */
$MaxRows = $_REQUEST["count"];
$OrderedItem = $params->OrderedItem;
$SkeepRows = ($_REQUEST["start"] == "") ? $_REQUEST["?start"] : $_REQUEST["start"];

if ($SkeepRows == "") {
    $SkeepRows = 0;
}


/* Establece valores predeterminados para `$OrderedItem` y `$MaxRows` si están vacíos. */
if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 1000;
}


/* Asigna valores de parámetros a variables para su uso posterior en el código. */
$Id = $params->Id;
$Products = $params->Products;
$Partner = $params->Partner;
$Product = $params->Product;
$FranchiseId = $params->FranchiseId;


$Id = $_REQUEST["Id"];

/* Valida y asigna valores de entrada según condiciones específicas en PHP. */
$IsActivate = ($_REQUEST["IsActivate"] == "A" || $_REQUEST["IsActivate"] == "I") ? $_REQUEST["IsActivate"] : '';;
$Products = $_REQUEST["Products"];
$Partner = $_REQUEST["Partner"];
$Product = $_REQUEST["Product"];
$FranchiseId = $_REQUEST["FranchiseId"];
$ProviderId = ($_REQUEST["ProviderId"] > 0 && is_numeric($_REQUEST["ProviderId"]) && $_REQUEST["ProviderId"] != '') ? $_REQUEST["ProviderId"] : '';

/* obtiene y verifica datos de solicitudes HTTP. */
$Name = $_REQUEST["Name"];
$CountrySelect = $_REQUEST["CountrySelect"];


/* Código inicializa un array y establece una respuesta sin errores y éxito. */
$final = array();


$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";

/* inicializa un arreglo de respuesta con errores y datos procesados. */
$response["ModelErrors"] = [];


$response["pos"] = $SkeepRows;
$response["total_count"] = 0;
$response["data"] = $final;

// Si no se ha seleccionado pais, se returna data vacia
if ($CountrySelect != '' && $CountrySelect != '0') {

    // Instanciamos la clase Franquicia Producto para obtener los productos a asociar a un Franquicia que ya están asociados a un partner y pais

    /* Inicializa un objeto y aplica un filtro basado en una variable de escritorio. */
    $FranquiciaProducto = new FranquiciaProducto();

    //Inicializamos los filtros
    $rules = [];

    if ($Id != "") {
        array_push($rules, array("field" => "franquicia_mandante_pais.franquiciamandante_id", "data" => "$Id", "op" => "eq"));
    }

    /* Agrega condiciones a un array de reglas según selecciones de país y estado. */
    if ($CountrySelect != "") {
        array_push($rules, array("field" => "franquicia_mandante_pais.pais_id", "data" => "$CountrySelect", "op" => "eq"));
    }


    if ($IsActivate == "I") {
        array_push($rules, array("field" => "franquicia_producto.estado", "data" => "I", "op" => "eq"));
    } else if ($IsActivate == "A") {
        /* Añade una regla si $IsActivate es "A", verificando el estado de franquicia_producto. */

        array_push($rules, array("field" => "franquicia_producto.estado", "data" => "A", "op" => "eq"));
    }


    /* Valida si el socio pertenece a una lista nativa y agrega regla si es cierto. */
    if ($Partner != "") {


        if (!in_array($Partner, explode(',', $_SESSION["mandanteLista"]))) {
            throw new Exception("Inusual Detected", "11");
        }

        array_push($rules, array("field" => "franquicia_mandante_pais.mandante", "data" => "$Partner", "op" => "eq"));
    } else {
        /* Lanza una excepción si se detecta una anomalía en el código. */

        throw new Exception("Inusual Detected", "11");
    }


    /* Condicionalmente agrega reglas a un arreglo según el valor de $FranchiseId y $_SESSION["Global"]. */
    if ($FranchiseId != "") {
        if ($_SESSION["Global"] == "S") {
            array_push($rules, array("field" => "Franquicia.franquicia_id", "data" => "$FranchiseId", "op" => "eq"));

        } else {
            array_push($rules, array("field" => "franquicia_mandante_pais.franquicia_id", "data" => "$FranchiseId", "op" => "eq"));

        }

    }


    /* agrega una regla basada en el estado de una variable de sesión. */
    if ($Product != "") {
        if ($_SESSION["Global"] == "S") {
            array_push($rules, array("field" => "producto.producto_id", "data" => "$Product", "op" => "eq"));

        } else {
            array_push($rules, array("field" => "franquicia_producto.producto_id", "data" => "$Product", "op" => "eq"));

        }

    }


    /* Agrega reglas de filtrado a un arreglo según los valores de ProviderId y Name. */
    if ($ProviderId != "") {

        array_push($rules, array("field" => "franquicia.proveedor_id", "data" => "$ProviderId", "op" => "eq"));
    }

    if ($Name != "") {

        array_push($rules, array("field" => "franquicia.descripcion", "data" => "$Name", "op" => "cn"));
    }

    $orden = "franquicia_mandante_pais.franquiciamandante_id";

    /* Establece el criterio de ordenamiento basado en la solicitud del usuario. */
    $ordenTipo = "asc";

    if ($_REQUEST["sort[Order]"] != "") {
        $orden = "franquicia_mandante_pais.orden";
        $ordenTipo = ($_REQUEST["sort[Order]"] == "asc") ? "asc" : "desc";

    }

    /* filtra y obtiene datos de Franquicias, productos, partners y países. */
    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $jsonfiltro = json_encode($filtro);

    //Obtenemos los Franquicias que ya han sido asignados a un partner y pais y los productos que ya han sido asignados a este Franquicia
    $Franquicias = $FranquiciaProducto->getFranquiciasMandanteProductosCustom(" franquicia_mandante_pais.*,mandante.*,franquicia.*,proveedor.*,producto.*,franquicia_producto.*", $orden, $ordenTipo, $SkeepRows, $MaxRows, $jsonfiltro, true, $Partner, $CountrySelect);

    $Franquicias = json_decode($Franquicias);


    /* Se inicializa un array vacío en la variable $final para almacenar elementos. */
    $final = [];


    /* Itera sobre Franquicias, creando un array con Productos específicos de cada uno. */
    foreach ($Franquicias->data as $key => $value) {

        $array = [];

        $array["Id"] = $value->{"franquicia_producto.franquiciaproducto_id"};
        $array["FranchiseId"] = $value->{"franquicia.descripcion"} . " (" . $value->{"franquicia.franquicia_id"} . ")";
        $array["Abreviado"] = $value->{"franquicia_producto.abreviado"};
        $array["Product"] = array(
            "Id" => $value->{"producto.producto_id"},
            "Name" => $value->{"producto.descripcion"}
        );
        $array["Product"] = $value->{"producto.descripcion"} . " (" . $value->{"producto.producto_id"} . ")";
        $array["ProviderId"] = $value->{"proveedor.descripcion"};
        $array["Partner"] = $value->{"mandante.descripcion"};
        $array["TypeProduct"] = $value->{"proveedor.tipo"};
        $array["IsActivate"] = $value->{"franquicia_producto.estado"};
        $array["Imagen"] = $value->{"franquicia_producto.imagen"};

        array_push($final, $array);

    }

    //Devolvemos los Franquicias y la cantidad de estos que ya han sido asignados a un partner y pais y los productos que ya han sido asignados a este Franquicia

    /* asigna el conteo de Franquicias y datos finales a un array de respuesta. */
    $response["total_count"] = $Franquicias->count[0]->{".count"};
    $response["data"] = $final;

}


