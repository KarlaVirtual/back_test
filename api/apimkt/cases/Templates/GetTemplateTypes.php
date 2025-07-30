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
use Backend\dto\Template;
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
use Backend\dto\UsuarioMensajecampana;
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
 * Templates/GetTemplateTypes
 *
 * Obtener los mensajes enviados a un usuario
 *
 * @param object $params Parámetros de entrada que incluyen
 *                       - length: número máximo de filas a recuperar
 *                       - OrderedItem: ítem ordenado
 *                       - start: filas a omitir
 *                       - LanguageSelect: idioma seleccionado
 *                       - CountrySelect: país seleccionado
 *                       - Id: identificador del usuario
 *                       - Type: tipo de mensaje
 *                       - Section: sección del mensaje
 *
 * @return array Devuelve un array con la siguiente estructura:
 *               {"HasError":boolean,"AlertType": string,
 *                "AlertMessage": string,"url": string,"success": string}
 *
 * @throws no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* Asignación de parámetros necesarios para el procesamiento de datos en una aplicación. */
$MaxRows = $params->length;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->start;
$LanguageSelect = $params->LanguageSelect;
$LanguageSelect = strtolower($LanguageSelect);
$CountrySelect = $params->CountrySelect;

/* obtiene valores de parámetros y de la sesión del usuario. */
$Id = $params->Id;
$Type = $params->Type;
$Section = $params->Section;


$mandante = $_SESSION["mandante"];


/* Verifica condiciones y genera una respuesta vacía en caso de ser verdadero. */
$Pais = new Pais($CountrySelect);
if (($Section == "" || $LanguageSelect == "" || $CountrySelect == "") && $Id == '') {

    $response = array();
    $response["data"] = array(
        "messages" => array()
    );
    $response["data"] = array();
    $response["pos"] = 0;
    $response["total_count"] = 0;


} else {


    /* Asigna fechas si no están vacías en el objeto $params. */
    if ($params->DateFrom != "") {
        $DateFrom = $params->DateFrom;
    }

    if ($params->DateTo != "") {
        $DateTo = $params->DateTo;
    }

    /* asigna valores predeterminados a variables si están vacías. */
    if ($MaxRows == "") {

        $MaxRows = $params->length;
    }

    if ($SkeepRows == "") {

        $SkeepRows = $params->start;
    }


    /* Asigna valores predeterminados a variables si no están definidas. */
    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }

    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }


    /* Establece un valor predeterminado de 100 para $MaxRows si está vacío. */
    if ($MaxRows == "") {
        $MaxRows = 100;
    }

    $plantillasRecibidas = [];
    $plantillasnew = [];


    /* crea reglas condicionales según el tipo de sección especificada. */
    $rules = [];

    /*if ($Type != "") {

        array_push($rules, array("field" => "clasificador.clasificador_id", "data" => $Type, "op" => "eq"));
    }*/
    if ($Section == "emails") {
        array_push($rules, array("field" => "clasificador.tipo", "data" => "TPE", "op" => "eq"));

    }

    /* Añade reglas a un array según el valor de la sección especificada. */
    if ($Section == "receipts") {

        array_push($rules, array("field" => "clasificador.tipo", "data" => "TPR", "op" => "eq"));
    }
    if ($Section == "sms") {

        array_push($rules, array("field" => "clasificador.tipo", "data" => "TPS", "op" => "eq"));
    }

    /* añade reglas basadas en la sección actual: "inbox" o "popup". */
    if ($Section == "inbox") {

        array_push($rules, array("field" => "clasificador.tipo", "data" => "TPI", "op" => "eq"));
    }
    if ($Section == "popup") {

        array_push($rules, array("field" => "clasificador.tipo", "data" => "TPP", "op" => "eq"));
    }


    /* Condicional para agregar reglas y seleccionar idioma según la opción elegida. */
    if ($Type != "") {
        array_push($rules, array("field" => "clasificador.clasificador_id", "data" => "$Type", "op" => "eq"));
    }


    switch ($LanguageSelect) {
        case "es":
            $lenguaje = "ESPAÑOL";
            break;
        case "en":
            $lenguaje = "INGLES";
            break;
        case "pt":
            $lenguaje = "PORTUGUES";
            break;
    }


    /* Se crea un filtro JSON y se obtiene clasificadores personalizados mediante un objeto. */
    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json2 = json_encode($filtro);

    $clasificador = new Clasificador();

    $plantillas1 = $clasificador->getClasificadoresCustom("clasificador.*", "clasificador.clasificador_id", "desc", $SkeepRows, $MaxRows, $json2, true);


    /* Convierte el JSON en la variable $plantillas1 a un objeto PHP. */
    $plantillas1 = json_decode($plantillas1);


    // $final = [];

    // foreach($plantillas1->data as $key=>$value){
    //     $array = [];
    //     $array["id"] = $value->{"clasificador.clasificador_id"};
    //     $array["tipo"] = $value->{"clasificador.tipo"};
    //     $array["descripcion"] = $value->{"clasificador.descripcion"};

    //     array_push($final,$array);
    // }


    foreach ($plantillas1->data as $key => $value) {


        /* Crea un arreglo asociativo con información de país y clasificador. */
        $array = [];
        $array["CountryId"] = $CountrySelect;
        $array["CountrySelect"] = $Pais->paisNom;
        $array["Id"] = "";
        $array["TypeName"] = $value->{"clasificador.descripcion"};
        $array["TypeId"] = $value->{"clasificador.clasificador_id"};

        /* Código asigna valores a un array para gestionar opciones de idioma y plantillas. */
        $array["LanguageSelect"] = $lenguaje;
        $array["LanguageId"] = $LanguageSelect;


        $array["Template"] = "";
        $array["MergeTags"] = array();

        /* Define etiquetas de fusión para usuario y nombre en un array asociativo. */
        $array["MergeTags"]["#userid#"] = array(
            "name" => "Id usuario",
            "value" => "#userid#"
        );

        $array["MergeTags"]["#name#"] = array(
            "name" => "Nombre",
            "value" => "#name#"
        );

        /* Código PHP define un arreglo con etiqueta de identificación y propiedades asociadas. */
        $array["MergeTags"]["#identification#"] = array(
            "name" => "Cedula",
            "value" => "#identification#"
        );

        $variablesTemp = array();

        /* Define un arreglo con un marcador de posición para el apellido. */
        $array["MergeTags"]["#lastname#"] = array(
            "name" => "Apellido",
            "value" => "#lastname#"
        );

        $variablesTemp = array();

        /* define etiquetas de fusión y añade campos según una condición específica. */
        $array["MergeTags"]["#login#"] = array(
            "name" => "Login",
            "value" => "#login#"
        );

        if ($value->{'clasificador.abreviado'} === 'TEMPSESIEXTE') {
            $array['MergeTags']['#type'] = ['name' => 'Tipo de inicio', 'value' => '#type'];
            $array['MergeTags']['#password'] = ['name' => 'Contraseña', 'value' => '#password'];
        }


        /* asigna etiquetas de fusión a un arreglo según un clasificador específico. */
        if ($value->{'clasificador.abreviado'} === 'TEMPEMAIL') {
            $array['MergeTags']['#user#'] = ['name' => 'Nombre completo', 'value' => '#fullname#'];
            $array['MergeTags']['#link#'] = ['name' => 'Link', 'value' => '#link#'];
        }

        if ($value->{'clasificador.abreviado'} === 'TEMPDEPOSIT') {
            $array['MergeTags']['#Value#'] = ['name' => 'valor', 'value' => '#Value#'];
            $array['MergeTags']['#MethodPayment#'] = ['name' => 'Metodo De Pago', 'value' => '#MethodPayment#'];
            $array['MergeTags']['#Transaction#'] = ['name' => 'Transaccion', 'value' => '#Transaction#'];
        }


        /* asigna una contraseña a un array basado en condiciones específicas. */
        if ($value->{'clasificador.abreviado'} === 'TEMPRESPASS') {
            $array['MergeTags']['#password#'] = ['name' => 'Contraseña', 'value' => '#password#'];
        }

        if ($value->{'clasificador.abreviado'} === 'TEMPPVREG') {
            $array['MergeTags']['#password#'] = ['name' => 'Contraseña', 'value' => '#password#'];
        }

        if ($value->{"clasificador.abreviado"} == "TEMEMREG") {

            /* Código PHP que define un arreglo asociativo para etiquetas de fusión. */
            $variablesTemp = array();

            $variablesTemp = array();
            $array["MergeTags"]["#Name#"] = array(
                "name" => "nombre",
                "value" => "#Name#"
            );


            /* inicializa un arreglo con una etiqueta de correo y su valor. */
            $variablesTemp = array();
            $array["MergeTags"]["#email#"] = array(
                "name" => "Correo",
                "value" => "#email#"
            );

            $variablesTemp = array();

            /* Se crea un arreglo para almacenar etiquetas de fusión, incluyendo contraseñas. */
            $array["MergeTags"]["#password#"] = array(
                "name" => "Contraseña",
                "value" => "#password#"
            );

            $variablesTemp = array();

            /* Crea un array para almacenar etiquetas de fusión con nombre y valor. */
            $array["MergeTags"]["#Mandante#"] = array(
                "name" => "Marca",
                "value" => "#Mandante#"
            );

            $variablesTemp = array();

            /* Código que define un enlace en un array asociativo con nombre y valor. */
            $array["MergeTags"]["#link#"] = array(
                "name" => "url",
                "value" => "#link#"
            );

        }


        if ($value->{"clasificador.abreviado"} == "TEMEMPASS") {

            /* define un arreglo para almacenar etiquetas de fusión relacionadas con enlaces. */
            $variablesTemp = array();

            $variablesTemp = array();
            $array["MergeTags"]["#link#"] = array(
                "name" => "Link",
                "value" => "#link#"
            );


            /* Se inicia un array para almacenar variables temporales y se configura un MergeTag. */
            $variablesTemp = array();
            $array["MergeTags"]["#IdUser#"] = array(
                "name" => "idusuario",
                "value" => "#IdUser#"
            );

            $variablesTemp = array();

            /* Define un array de etiquetas de fusión con un nombre y valor específico. */
            $array["MergeTags"]["#name#"] = array(
                "name" => "nombre",
                "value" => "#name#"
            );

            $variablesTemp = array();

            /* Define etiquetas de fusión con nombres y valores asociados en un array. */
            $array["MergeTags"]["#email#"] = array(
                "name" => "correo electronico",
                "value" => "#email#"
            );

            $array['MergeTags']['#banners#'] = [
                'name' => 'banners',
                'value' => '#banners#'
            ];


        }


        if ($value->{"clasificador.abreviado"} == "INICIOSES") {

            /* Se crean variables y se asignan etiquetas de fusión en un array. */
            $variablesTemp = array();
            $array["MergeTags"]["#NamePv#"] = array(
                "name" => "nombre",
                "value" => "#NamePv#"
            );

            $variablesTemp = array();

            /* asigna un valor a una clave en el array "MergeTags". */
            $array["MergeTags"]["#Email#"] = array(
                "name" => "correo",
                "value" => "#Email#"
            );

            $variablesTemp = array();

            /* Código que define una etiqueta de fusión para un país en un array. */
            $array["MergeTags"]["#Country#"] = array(
                "name" => "Pais",
                "value" => "#Country#"
            );

            $variablesTemp = array();

            /* asigna un valor a una etiqueta de fusión "Partner" en un array. */
            $array["MergeTags"]["#Parnert#"] = array(
                "name" => "Contraseña",
                "value" => "#Partner#"
            );

        }

        if ($value->{"clasificador.abreviado"} == "TEMPCONTAFI") {

            /* Código que inicializa un arreglo con etiquetas de fusión para nombres. */
            $variablesTemp = array();

            $variablesTemp = array();
            $array["MergeTags"]["#name#"] = array(
                "name" => "Nombre",
                "value" => "#name#"
            );


            /* crea un array asociado con una etiqueta de combinación llamada "Partner". */
            $variablesTemp = array();
            $array["MergeTags"]["#partner#"] = array(
                "name" => "Partner",
                "value" => "#partner#"
            );


            $variablesTemp = array();

            /* define un enlace en un arreglo asociativo para su uso posterior. */
            $array["MergeTags"]["#link#"] = array(
                "name" => "Link",
                "value" => "#link#"
            );


            $variablesTemp = array();

            /* crea un arreglo que almacena una etiqueta para el correo electrónico. */
            $array["MergeTags"]["#email#"] = array(
                "name" => "Email",
                "value" => "#email#"
            );

            $variablesTemp = array();

            /* Se define un arreglo con una etiqueta de fusión para el ID del usuario. */
            $array["MergeTags"]["#userid#"] = array(
                "name" => "ID Usuario",
                "value" => "#userid#"
            );


        }


        /* establece condiciones para asignar valores a un arreglo basado en una clave. */
        if ($value->{"clasificador.abreviado"} == "TEMPREGMAS") {
            $variablesTemp = array();
            $array["MergeTags"]["#Name#"] = array(
                "name" => "nombre",
                "value" => "#Name#"
            );

            $variablesTemp = array();
            $array["MergeTags"]["#Password#"] = array(
                "name" => "contraseña",
                "value" => "#password#"
            );


        }

        if ($value->{"clasificador.abreviado"} == "TEMPVEAFI") {

            /* Código para crear un array con etiquetas de fusión y sus valores correspondientes. */
            $variablesTemp = array();
            $array["MergeTags"]["#Name#"] = array(
                "name" => "Nombre",
                "value" => "#Name#",

            );


            /* Se definen variables y un array con etiquetas de fusión en PHP. */
            $variablesTemp = array();
            $array["MergeTags"]["#Mandante#"] = array(
                "name" => "Marca",
                "value" => "#Mandante#"
            );

            $variablesTemp = array();

            /* define un arreglo para gestionar etiquetas de un país en variables. */
            $array["MergeTags"]["#Country#"] = array(
                "name" => "Pais",
                "value" => "#Country#"
            );

            $variablesTemp = array();

            /* Se define un arreglo que asocia un tag con un nombre y un valor. */
            $array["MergeTags"]["#Email#"] = array(
                "name" => "Correo",
                "value" => "#Email#"
            );

        }

        if ($value->{"clasificador.abreviado"} == "APROEMAILJUMIO") {


            /* define un array para variables y un merge tag con nombre y valor. */
            $variablesTemp = array();
            $array["MergeTags"]["#mandante#"] = array(
                "name" => "Mandante",
                "value" => "#mandante#"
            );

            $variablesTemp = array();

            /* Se define un arreglo con etiquetas de fusión y un array temporal vacío. */
            $array["MergeTags"]["#creationdate#"] = array(
                "name" => "Fecha creación",
                "value" => "#creationdate#"
            );

            $variablesTemp = array();

            /* Se define un arreglo con etiquetas de fusión, específico para el correo electrónico. */
            $array["MergeTags"]["#email"] = array(
                "name" => "Correo",
                "value" => "#email#"
            );

            $variablesTemp = array();

            /* Se define una variable de país en un array asociativo. */
            $array["MergeTags"]["#pais#"] = array(
                "name" => "Pais",
                "value" => "#pais#"
            );


            $variablesTemp = array();

            /* define un array para manejar URL con un marcador de posición. */
            $array["MergeTags"]["#link#"] = array(
                "name" => "Url",
                "value" => "#link#"
            );

            $variablesTemp = array();

            /* Se define un array con un campo de merge tag para teléfono. */
            $array["MergeTags"]["#telefono#"] = array(
                "name" => "Telefono",
                "value" => "#telefono#"
            );
        }

        if ($value->{"clasificador.abreviado"} == "PENDEMAILJUMIO") {


            /* inicializa un array con un "MergeTag" llamado "Mandante". */
            $variablesTemp = array();
            $array["MergeTags"]["#Mandante#"] = array(
                "name" => "Mandante",
                "value" => "#Mandante#"
            );

            $variablesTemp = array();

            /* define un array con etiquetas de fusión para una fecha de creación. */
            $array["MergeTags"]["#creationdate#"] = array(
                "name" => "Fecha creación",
                "value" => "#creationdate#"
            );

            $variablesTemp = array();

            /* asigna un valor de etiqueta de fusión para un correo electrónico en un array. */
            $array["MergeTags"]["#email"] = array(
                "name" => "Correo",
                "value" => "#email#"
            );

            $variablesTemp = array();

            /* Se asigna un país a un array usando etiquetas de fusión. */
            $array["MergeTags"]["#pais#"] = array(
                "name" => "Pais",
                "value" => "#pais#"
            );

            $variablesTemp = array();

            /* define un enlace en un array con nombre y valor. */
            $array["MergeTags"]["#link#"] = array(
                "name" => "Url",
                "value" => "#link#"
            );

            $variablesTemp = array();

            /* define un array con información sobre un campo de teléfono. */
            $array["MergeTags"]["#telefono#"] = array(
                "name" => "Telefono",
                "value" => "#telefono#"
            );
        }


        if ($value->{"clasificador.abreviado"} == "RECEMAILJUMIO") {


            /* Código crea un arreglo con etiquetas de fusión para un mandante específico. */
            $variablesTemp = array();
            $array["MergeTags"]["#mandante#"] = array(
                "name" => "Mandante",
                "value" => "#mandante#"
            );
            $variablesTemp = array();

            /* asigna información sobre la fecha de creación a un arreglo. */
            $array["MergeTags"]["#creationdate#"] = array(
                "name" => "Fecha creación",
                "value" => "#creationdate#"
            );

            $variablesTemp = array();

            /* asigna un valor a un marcador de fusión para un correo electrónico. */
            $array["MergeTags"]["#email"] = array(
                "name" => "Correo",
                "value" => "#email#"
            );

            $variablesTemp = array();

            /* Asigna un país a un array de etiquetas de fusión en PHP. */
            $array["MergeTags"]["#pais#"] = array(
                "name" => "Pais",
                "value" => "#pais#"
            );

            $variablesTemp = array();

            /* define una etiqueta de fusión para una URL en un arreglo. */
            $array["MergeTags"]["#link#"] = array(
                "name" => "Url",
                "value" => "#link#"
            );

            $variablesTemp = array();

            /* Define un array con un tag de fusión para almacenar un número de teléfono. */
            $array["MergeTags"]["#telefono#"] = array(
                "name" => "Telefono",
                "value" => "#telefono#"
            );

        }

        if ($value->{"clasificador.abreviado"} == "RECOVERACCOUNT") {

            /* Inicializa un arreglo para almacenar etiquetas de fusión con un enlace específico. */
            $variablesTemp = array();
            $array["MergeTags"]["#enlace#"] = array(
                "name" => "enlace",
                "value" => "#enlace#"
            );
            $variablesTemp = array();

            /* Se define un array para almacenar etiquetas de fusión y un array temporal vacío. */
            $array["MergeTags"]["#mandante#"] = array(
                "name" => "partner",
                "value" => "#mandante#"
            );

            $variablesTemp = array();

            /* asigna un usuario a un array en formato específico. */
            $array["#MergeTags"]["#usuario#"] = array(
                "name" => "usuario",
                "value" => "#usuario#"
            );

            $variablesTemp = array();

            /* Asignación de un token en un array con nombre y valor específicos. */
            $array["MergeTags"]["#token#"] = array(
                "name" => "token",
                "value" => "#token#"
            );


        }

        if ($value->{"clasificador.abreviado"} == "APROSMSJUMIO") {


            /* Inicializa un array con etiquetas de combinación para un mando específico. */
            $variablesTemp = array();
            $array["MergeTags"]["#mandante#"] = array(
                "name" => "Mandante",
                "value" => "#mandante#"
            );
            $variablesTemp = array();

            /* Se crea un arreglo para almacenar etiquetas de fusión con fecha de creación. */
            $array["MergeTags"]["#creationdate#"] = array(
                "name" => "Fecha creación",
                "value" => "#creationdate#"
            );

            $variablesTemp = array();

            /* Se define un array con una etiqueta de fusión para el correo electrónico. */
            $array["MergeTags"]["#email"] = array(
                "name" => "Correo",
                "value" => "#email#"
            );

            $variablesTemp = array();

            /* Se define un tag para el país en un array de variables. */
            $array["MergeTags"]["#pais#"] = array(
                "name" => "Pais",
                "value" => "#pais#"
            );

            $variablesTemp = array();

            /* asigna un enlace a un array de etiquetas de fusión. */
            $array["MergeTags"]["#link#"] = array(
                "name" => "Url",
                "value" => "#link#"
            );

            $variablesTemp = array();

            /* Asignación de un número de teléfono a la clave "MergeTags" en un array. */
            $array["MergeTags"]["#telefono#"] = array(
                "name" => "Telefono",
                "value" => "#telefono#"
            );
        }

        if ($value->{"clasificador.abreviado"} == "PENDSMSJUMIO") {


            /* inicializa un array para manejar etiquetas de fusión relacionadas con "mandante". */
            $variablesTemp = array();
            $array["MergeTags"]["#mandante#"] = array(
                "name" => "Mandante",
                "value" => "#mandante#"
            );
            $variablesTemp = array();

            /* define una etiqueta de fusión para la fecha de creación en un arreglo. */
            $array["MergeTags"]["#creationdate#"] = array(
                "name" => "Fecha creación",
                "value" => "#creationdate#"
            );

            $variablesTemp = array();

            /* Se define un array para almacenar etiquetas de fusión relacionadas con correos. */
            $array["MergeTags"]["#email"] = array(
                "name" => "Correo",
                "value" => "#email#"
            );

            $variablesTemp = array();

            /* Define un array que mapea un 'merge tag' de país con su nombre y valor. */
            $array["MergeTags"]["#pais#"] = array(
                "name" => "Pais",
                "value" => "#pais#"
            );

            $variablesTemp = array();

            /* Se define un array asociativo para manejar etiquetas de enlace y variables temporales. */
            $array["MergeTags"]["#link#"] = array(
                "name" => "Url",
                "value" => "#link#"
            );

            $variablesTemp = array();

            /* asigna un arreglo con información sobre un "telefono" en MergeTags. */
            $array["MergeTags"]["#telefono#"] = array(
                "name" => "Telefono",
                "value" => "#telefono#"
            );
        }


        if ($value->{"clasificador.abreviado"} == "RECSMSJUMIO") {


            /* Asignación de etiquetas de fusión en un array con un nombre y un valor. */
            $variablesTemp = array();
            $array["MergeTags"]["#mandante#"] = array(
                "name" => "Mandante",
                "value" => "#mandante#"
            );
            $variablesTemp = array();

            /* define una etiqueta de fusión con fecha de creación en un array. */
            $array["MergeTags"]["#creationdate#"] = array(
                "name" => "Fecha creación",
                "value" => "#creationdate#"
            );

            $variablesTemp = array();

            /* Se define un array para etiquetas de fusión, incluyendo un correo electrónico. */
            $array["MergeTags"]["#email"] = array(
                "name" => "Correo",
                "value" => "#email#"
            );

            $variablesTemp = array();

            /* Se define un arreglo para almacenar etiquetas de combinación con un país. */
            $array["MergeTags"]["#pais#"] = array(
                "name" => "Pais",
                "value" => "#pais#"
            );

            $variablesTemp = array();

            /* Se crea un arreglo de etiquetas de fusión con una URL placeholder. */
            $array["MergeTags"]["#link#"] = array(
                "name" => "Url",
                "value" => "#link#"
            );

            $variablesTemp = array();

            /* Código que asigna un valor de teléfono a un array asociativo en PHP. */
            $array["MergeTags"]["#telefono#"] = array(
                "name" => "Telefono",
                "value" => "#telefono#"
            );

        }

        if ($value->{"clasificador.abreviado"} == "APROINBOXJUMIO") {


            /* Inicializa un array y establece una etiqueta de fusión para "mandante". */
            $variablesTemp = array();
            $array["MergeTags"]["#mandante#"] = array(
                "name" => "Mandante",
                "value" => "#mandante#"
            );
            $variablesTemp = array();

            /* asigna una etiqueta de creación con su nombre y valor en un array. */
            $array["MergeTags"]["#creationdate#"] = array(
                "name" => "Fecha creación",
                "value" => "#creationdate#"
            );

            $variablesTemp = array();

            /* Se define un array con etiquetas de fusión que incluye un correo electrónico. */
            $array["MergeTags"]["#email"] = array(
                "name" => "Correo",
                "value" => "#email#"
            );

            $variablesTemp = array();

            /* Se crea un arreglo asociativo para gestionar etiquetas de país en un sistema. */
            $array["MergeTags"]["#pais#"] = array(
                "name" => "Pais",
                "value" => "#pais#"
            );

            $variablesTemp = array();

            /* Se asigna un enlace a una estructura de datos PHP para usarse más tarde. */
            $array["MergeTags"]["#link#"] = array(
                "name" => "Url",
                "value" => "#link#"
            );

            $variablesTemp = array();

            /* asigna un teléfono a un array de etiquetas de fusión. */
            $array["MergeTags"]["#telefono#"] = array(
                "name" => "Telefono",
                "value" => "#telefono#"
            );
        }

        if ($value->{"clasificador.abreviado"} == "PENDINBOXJUMIO") {


            /* define un array para almacenar etiquetas de fusión relacionadas con "mandante". */
            $variablesTemp = array();
            $array["MergeTags"]["#mandante#"] = array(
                "name" => "Mandante",
                "value" => "#mandante#"
            );
            $variablesTemp = array();

            /* Se define un array con etiquetas de fusión para la fecha de creación. */
            $array["MergeTags"]["#creationdate#"] = array(
                "name" => "Fecha creación",
                "value" => "#creationdate#"
            );

            $variablesTemp = array();

            /* asigna un valor a un array asociativo de "MergeTags". */
            $array["MergeTags"]["#email"] = array(
                "name" => "Correo",
                "value" => "#email#"
            );

            $variablesTemp = array();

            /* define un array para almacenar etiquetas de fusión relacionadas con países. */
            $array["MergeTags"]["#pais#"] = array(
                "name" => "Pais",
                "value" => "#pais#"
            );

            $variablesTemp = array();

            /* asigna un enlace a un array para usar en plantillas. */
            $array["MergeTags"]["#link#"] = array(
                "name" => "Url",
                "value" => "#link#"
            );

            $variablesTemp = array();

            /* Se asigna un teléfono como etiqueta de fusión en un array. */
            $array["MergeTags"]["#telefono#"] = array(
                "name" => "Telefono",
                "value" => "#telefono#"
            );
        }


        if ($value->{"clasificador.abreviado"} == "RECINBOXJUMIO") {


            /* define un array con variables de fusión para reemplazo en plantillas. */
            $variablesTemp = array();
            $array["MergeTags"]["#mandante#"] = array(
                "name" => "Mandante",
                "value" => "#mandante#"
            );
            $variablesTemp = array();

            /* Se define un array con una etiqueta de fecha de creación y un valor específico. */
            $array["MergeTags"]["#creationdate#"] = array(
                "name" => "Fecha creación",
                "value" => "#creationdate#"
            );

            $variablesTemp = array();

            /* Se establece un array con etiquetas y un correo electrónico como valor. */
            $array["MergeTags"]["#email"] = array(
                "name" => "Correo",
                "value" => "#email#"
            );

            $variablesTemp = array();

            /* Define un array con etiquetas de fusión para el país en variables temporales. */
            $array["MergeTags"]["#pais#"] = array(
                "name" => "Pais",
                "value" => "#pais#"
            );

            $variablesTemp = array();

            /* define un arreglo con un enlace y una variable temporal. */
            $array["MergeTags"]["#link#"] = array(
                "name" => "Url",
                "value" => "#link#"
            );

            $variablesTemp = array();

            /* Define un arreglo con etiquetas de fusión para un número de teléfono. */
            $array["MergeTags"]["#telefono#"] = array(
                "name" => "Telefono",
                "value" => "#telefono#"
            );

        }

        if ($value->{"clasificador.abreviado"} == "APROPOPUPJUMIO") {


            /* Se define un array con etiquetas de fusión y se inicializa otra variable. */
            $variablesTemp = array();
            $array["MergeTags"]["#mandante#"] = array(
                "name" => "Mandante",
                "value" => "#mandante#"
            );
            $variablesTemp = array();

            /* Se define un array para almacenar una etiqueta de fecha de creación. */
            $array["MergeTags"]["#creationdate#"] = array(
                "name" => "Fecha creación",
                "value" => "#creationdate#"
            );

            $variablesTemp = array();

            /* Se almacena el correo en un arreglo con etiquetas de fusión. */
            $array["MergeTags"]["#email"] = array(
                "name" => "Correo",
                "value" => "#email#"
            );

            $variablesTemp = array();

            /* Define una etiqueta de fusión para el país en un array asociativo. */
            $array["MergeTags"]["#pais#"] = array(
                "name" => "Pais",
                "value" => "#pais#"
            );


            $variablesTemp = array();

            /* define un array con etiquetas de fusión para enlaces. */
            $array["MergeTags"]["#link#"] = array(
                "name" => "Url",
                "value" => "#link#"
            );

            $variablesTemp = array();

            /* Define un array asociativo para almacenar información de un campo de teléfono. */
            $array["MergeTags"]["#telefono#"] = array(
                "name" => "Telefono",
                "value" => "#telefono#"
            );
        }

        if ($value->{"clasificador.abreviado"} == "PENDPOPUPJUMIO") {


            /* define un arreglo con etiquetas de fusión para personalización. */
            $variablesTemp = array();
            $array["MergeTags"]["#mandante#"] = array(
                "name" => "Mandante",
                "value" => "#mandante#"
            );
            $variablesTemp = array();

            /* Asigna una etiqueta de fecha de creación a un arreglo en PHP. */
            $array["MergeTags"]["#creationdate#"] = array(
                "name" => "Fecha creación",
                "value" => "#creationdate#"
            );

            $variablesTemp = array();

            /* Se define un array con etiquetas de fusión para correos electrónicos. */
            $array["MergeTags"]["#email"] = array(
                "name" => "Correo",
                "value" => "#email#"
            );

            $variablesTemp = array();

            /* Se define un arreglo que asigna un país a una etiqueta de fusión. */
            $array["MergeTags"]["#pais#"] = array(
                "name" => "Pais",
                "value" => "#pais#"
            );

            $variablesTemp = array();

            /* Código que asigna un enlace a un arreglo y crea una variable temporal. */
            $array["MergeTags"]["#link#"] = array(
                "name" => "Url",
                "value" => "#link#"
            );

            $variablesTemp = array();

            /* Se crea un array que almacena un tag de merge para teléfono. */
            $array["MergeTags"]["#telefono#"] = array(
                "name" => "Telefono",
                "value" => "#telefono#"
            );
        }


        if ($value->{"clasificador.abreviado"} == "RECPOPUPJUMIO") {


            /* Crea un array para almacenar variables de merge tags con un valor específico. */
            $variablesTemp = array();
            $array["MergeTags"]["#mandante#"] = array(
                "name" => "Mandante",
                "value" => "#mandante#"
            );
            $variablesTemp = array();

            /* asigna una fecha de creación a un array de variables en PHP. */
            $array["MergeTags"]["#creationdate#"] = array(
                "name" => "Fecha creación",
                "value" => "#creationdate#"
            );

            $variablesTemp = array();

            /* Se define un arreglo con etiquetas de fusión para correos electrónicos. */
            $array["MergeTags"]["#email"] = array(
                "name" => "Correo",
                "value" => "#email#"
            );

            $variablesTemp = array();

            /* Se define un array para asignar etiquetas de fusión relacionadas con países. */
            $array["MergeTags"]["#pais#"] = array(
                "name" => "Pais",
                "value" => "#pais#"
            );

            $variablesTemp = array();

            /* Código crea un array para almacenar etiquetas de enlace y sus valores correspondientes. */
            $array["MergeTags"]["#link#"] = array(
                "name" => "Url",
                "value" => "#link#"
            );

            $variablesTemp = array();

            /* Define un arreglo que asocia un número de teléfono con su nombre y valor. */
            $array["MergeTags"]["#telefono#"] = array(
                "name" => "Telefono",
                "value" => "#telefono#"
            );

        }
        if ($value->{"clasificador.abreviado"} == "TEMEMPASS") {

            /* Se define un array que contiene una etiqueta de enlace con nombre y valor. */
            $variablesTemp = array();

            $variablesTemp = array();
            $array["MergeTags"]["#link#"] = array(
                "name" => "Link",
                "value" => "#link#"
            );


            /* Código PHP que define un arreglo para almacenar variables de fusión con un ID de usuario. */
            $variablesTemp = array();
            $array["MergeTags"]["#IdUser#"] = array(
                "name" => "idusuario",
                "value" => "#IdUser#"
            );

            $variablesTemp = array();

            /* Se define un arreglo que mapea etiquetas de fusión con variables y valores asociados. */
            $array["MergeTags"]["#name#"] = array(
                "name" => "nombre",
                "value" => "#name#"
            );

            $variablesTemp = array();

            /* Define un arreglo con etiquetas de fusión para correo electrónico y banners. */
            $array["MergeTags"]["#email#"] = array(
                "name" => "correo electronico",
                "value" => "#email#"
            );

            $array['MergeTags']['#banners#'] = [
                'name' => 'banners',
                'value' => '#banners#'
            ];
        }


        if ($value->{"clasificador.abreviado"} == "TEMPACTDA") {

            /* Se crea un array para almacenar etiquetas de fusión personalizadas con nombre de usuario. */
            $variablesTemp = array();
            $array["MergeTags"]["#Name#"] = array(
                "name" => "Usuario",
                "value" => "#Name#"

            );


            /* Se crean variables para manejar etiquetas de fusión en un array. */
            $variablesTemp = array();
            $array["MergeTags"]["#mandante#"] = array(
                "name" => "Mandante",
                "value" => "#mandante#"
            );

            $variablesTemp = array();

            /* asigna un valor a la etiqueta de un apellido en un array. */
            $array["MergeTags"]["#apellido#"] = array(
                "name" => "Apellido",
                "value" => "#apellido#"
            );

            $variablesTemp = array();

            /* Se asigna un valor de contraseña a un array de etiquetas de fusión. */
            $array["MergeTags"]["#Contraseña#"] = array(
                "name" => "contraseña",
                "value" => "#password#"
            );


        }


        /* verifica una condición y asigna una fecha de creación a un arreglo. */
        if ($value->{"clasificador.abreviado"} == "TEMEMVEACCO") {
            $variablesTemp = array();

            $variablesTemp = array();
            $array["MergeTags"]["#creationdate#"] = array(
                "name" => "Fecha creación",
                "value" => "#creationdate#"
            );
        }


        /* Compara un valor y asigna una fecha de creación en un array asociativo. */
        if ($value->{"clasificador.abreviado"} == "TEMEMVEACIN") {
            $variablesTemp = array();

            $variablesTemp = array();
            $array["MergeTags"]["#creationdate#"] = array(
                "name" => "Fecha creación",
                "value" => "#creationdate#"
            );

        }

        if ($value->{"clasificador.abreviado"} == "TEMRECRE") {


            /* Se define un arreglo para almacenar etiquetas de fusión con su valor correspondiente. */
            $variablesTemp = array();
            $array["MergeTags"]["#idpointsale#"] = array(
                "name" => "Id punto venta",
                "value" => "#idpointsale#"
            );


            $variablesTemp = array();

            /* Define un array asociativo para etiquetas de fusión con nombre y valor. */
            $array["MergeTags"]["#namepointsale#"] = array(
                "name" => "Nombre punto venta",
                "value" => "#namepointsale#"
            );


            $variablesTemp = array();

            /* Configuración de etiquetas de fusión para almacenar un valor en un array. */
            $array["MergeTags"]["#value#"] = array(
                "name" => "Valor",
                "value" => "#value#"
            );


            $variablesTemp = array();

            /* Se define un array con etiquetas de fusión y una variable temporal vacía. */
            $array["MergeTags"]["#creationdate#"] = array(
                "name" => "Fecha creación",
                "value" => "#creationdate#"
            );

            $variablesTemp = array();

            /* Define un array para manejar etiquetas de fusión con un número de depósito. */
            $array["MergeTags"]["#depositnumber#"] = array(
                "name" => "Numero deposito",
                "value" => "#depositnumber#"
            );

            $variablesTemp = array();

            /* Código define un array con etiquetas de fusión, incluyendo impuestos y sus valores. */
            $array["MergeTags"]["#tax#"] = array(
                "name" => "Impuesto",
                "value" => "#tax#"
            );

            $variablesTemp = array();

            /* Crea un arreglo que almacena un valor total con su nombre asociado. */
            $array["MergeTags"]["#totalvalue#"] = array(
                "name" => "Valor total",
                "value" => "#totalvalue#"
            );


        }


        /* Condicional que verifica un valor y asigna un nombre a un array. */
        if ($value->{"clasificador.abreviado"} == "APROBJUMIO") {
            $variablesTemp = array();
            $array["MergeTags"]["#Name#"] = array(
                "name" => "Nombre",
                "value" => "#Name#"
            );
        }


        /* Verifica una condición y asigna un valor a un array en caso de cumplirse. */
        if ($value->{"clasificador.abreviado"} == "PENDAPROBJUMIO") {
            $variablesTemp = array();
            $array["MergeTags"]["#Name#"] = array(
                "name" => "Nombre",
                "value" => "#Name#"
            );
        }


        if ($value->{"clasificador.abreviado"} == "TEMEMREGAFI") {

            /* Se crea un arreglo con etiquetas de fusión para almacenar nombres. */
            $variablesTemp = array();
            $array["MergeTags"]["#Name#"] = array(
                "name" => "Nombre",
                "value" => "#Name#"
            );


            $variablesTemp = array();

            /* define una etiqueta de fusión con su nombre y valor asociado. */
            $array["MergeTags"]["#Mandante#"] = array(
                "name" => "Marca",
                "value" => "#Mandante#"
            );

            $variablesTemp = array();

            /* Código define un array con etiquetas de fusión, incluyendo un correo electrónico. */
            $array["MergeTags"]["#Email#"] = array(
                "name" => "Correo",
                "value" => "#Email#"
            );


            $variablesTemp = array();

            /* Define un array con etiquetas de fusión para el país en una estructura PHP. */
            $array["MergeTags"]["#Country#"] = array(
                "name" => "Pais",
                "value" => "#Country#"
            );


        }


        if ($value->{"clasificador.abreviado"} == "TEMEMREG") {

            /* Se inicializa un arreglo para almacenar etiquetas de combinación con sus respectivos valores. */
            $variablesTemp = array();

            $variablesTemp = array();
            $array["MergeTags"]["#name#"] = array(
                "name" => "nombre",
                "value" => "#name#"
            );


            /* define un array con etiquetas de combinación, específicamente para un correo electrónico. */
            $variablesTemp = array();
            $array["MergeTags"]["#email"] = array(
                "name" => "Correo",
                "value" => "#email#"
            );

            $variablesTemp = array();

            /* define un array con una etiqueta de merge para contraseñas. */
            $array["MergeTags"]["#password#"] = array(
                "name" => "Contraseña",
                "value" => "#password#"
            );

            $variablesTemp = array();

            /* Se define un array que asocia un "Mandante" a una marca. */
            $array["MergeTags"]["#Mandante#"] = array(
                "name" => "Marca",
                "value" => "#Mandante#"
            );


            $variablesTemp = array();

            /* Asignación de un enlace con nombre y valor en un array. */
            $array["MergeTags"]["#link#"] = array(
                "name" => "Url",
                "value" => "#link#"
            );
        }

        if ($value->{"clasificador.abreviado"} == "TEMPCONTAFI") {

            /* Se define un array para almacenar etiquetas y sus valores asociados. */
            $variablesTemp = array();

            $variablesTemp = array();
            $array["MergeTags"]["#Name#"] = array(
                "name" => "Name",
                "value" => "#Name#"
            );


            /* crea un arreglo con etiquetas de fusión, asignando un enlace. */
            $variablesTemp = array();
            $array["MergeTags"]["#Link#"] = array(
                "name" => "url",
                "value" => "#Link#"
            );

            $variablesTemp = array();

            /* Se crea un array asociativo para almacenar información del "Mandante". */
            $array["MergeTags"]["#Mandante#"] = array(
                "name" => "Mandante",
                "value" => "#Mandante#"
            );

            $variablesTemp = array();

            /* Código PHP que define un arreglo con una etiqueta de fusión para contraseñas. */
            $array["MergeTags"]["#password#"] = array(
                "name" => "contraseña",
                "value" => "#password#"
            );


        }


        /* Asignación de etiquetas de fusión basadas en un valor específico en PHP. */
        if ($value->{"clasificador.abreviado"} == "TEMPREGMAS") {
            $variablesTemp = array();
            $array["MergeTags"]["#Name#"] = array(
                "name" => "nombre",
                "value" => "#Name#"
            );

            $variablesTemp = array();
            $array["MergeTags"]["#Password#"] = array(
                "name" => "contraseña",
                "value" => "#password#"
            );


        }

        if ($value->{"clasificador.abreviado"} == "TEMPVEAFI") {

            /* Se crea un arreglo con etiquetas de fusión para un sistema de plantillas. */
            $variablesTemp = array();
            $array["MergeTags"]["#Name#"] = array(
                "name" => "Nombre",
                "value" => "#Name#",

            );


            /* crea un array con etiquetas de fusión para personalización. */
            $variablesTemp = array();
            $array["MergeTags"]["#Mandante#"] = array(
                "name" => "Marca",
                "value" => "#Mandante#"
            );

            $variablesTemp = array();

            /* Se asigna información sobre el país a un array en formato específico. */
            $array["MergeTags"]["#Country#"] = array(
                "name" => "Pais",
                "value" => "#Country#"
            );

            $variablesTemp = array();

            /* Define un array asociativo para almacenar un correo como etiqueta de fusión. */
            $array["MergeTags"]["#Email#"] = array(
                "name" => "Correo",
                "value" => "#Email#"
            );


        }

        if ($value->{"clasificador.abreviado"} == "TEMPACTDA") {

            /* Código PHP que define un arreglo para etiquetas de fusión con nombre de usuario. */
            $variablesTemp = array();
            $array["MergeTags"]["#Name#"] = array(
                "name" => "Usuario",
                "value" => "#Name#"

            );


            /* Se crea un array para almacenar variables y definir etiquetas de fusión. */
            $variablesTemp = array();
            $array["MergeTags"]["#mandante#"] = array(
                "name" => "Mandante",
                "value" => "#mandante#"
            );

            $variablesTemp = array();

            /* Asignación de un valor a una clave en un array asociativo en PHP. */
            $array["MergeTags"]["#apellido#"] = array(
                "name" => "Apellido",
                "value" => "#apellido#"
            );

            $variablesTemp = array();

            /* crea un arreglo con la clave "#Contraseña#" que incluye nombre y valor. */
            $array["MergeTags"]["#Contraseña#"] = array(
                "name" => "contraseña",
                "value" => "#Contraseña#"
            );


        }


        /* Condicional que asigna valores a un array según una clave específica. */
        if ($value->{"clasificador.abreviado"} == "RECJUMIO") {
            $variablesTemp = array();
            $array["MergeTags"]["#Name#"] = array(
                "name" => "nombre",
                "value" => "#Name#"
            );

            $variablesTemp = array();
            $array["MergeTags"]["#Mandante#"] == array(
                "name" => "Mandante",
                "value" => "#Mandante#"
            );

        }


        if ($value->{"clasificador.abreviado"} == "TEMRECNORE") {

            /* Se crea un array para almacenar datos de "MergeTags" en una nota de retiro. */
            $variablesTemp = array();
            $array["MergeTags"]["#idnotewithdrawal#"] = array(
                "name" => "Id nota retiro",
                "value" => "#idnotewithdrawal#"
            );

            $variablesTemp = array();

            /* asigna un array con etiquetas de fusión y variables temporales. */
            $array["MergeTags"]["#value#"] = array(
                "name" => "Valor",
                "value" => "#value#"
            );


            $variablesTemp = array();

            /* Se define un arreglo que asigna el nombre y valor de un impuesto. */
            $array["MergeTags"]["#tax#"] = array(
                "name" => "Impuesto",
                "value" => "#tax#"
            );

            $variablesTemp = array();

            /* asigna un valor total a una variable en un array. */
            $array["MergeTags"]["#totalvalue#"] = array(
                "name" => "Valor total",
                "value" => "#totalvalue#"
            );

            $variablesTemp = array();

            /* define un array con una clave y su respectivo valor asociado. */
            $array["MergeTags"]["#keynotewithdrawal#"] = array(
                "name" => "Clave nota retiro",
                "value" => "#keynotewithdrawal#"
            );

            $variablesTemp = array();

            /* asigna un dato sobre la fecha de creación a un array. */
            $array["MergeTags"]["#creationdate#"] = array(
                "name" => "Fecha creación",
                "value" => "#creationdate#"
            );


        }

        if ($value->{"clasificador.abreviado"} == "TEMPEMAILNOTRET") {

            /* inicializa un array para fusionar etiquetas con un identificador de nota de retiro. */
            $variablesTemp = array();
            $array["MergeTags"]["#idnotewithdrawal#"] = array(
                "name" => "Id nota retiro",
                "value" => "#idnotewithdrawal#"
            );

            $variablesTemp = array();

            /* Define un arreglo para almacenar etiquetas de fusión con nombre y valor correspondiente. */
            $array["MergeTags"]["#value#"] = array(
                "name" => "Valor",
                "value" => "#value#"
            );


            $variablesTemp = array();

            /* Define un array para etiquetas de fusión relacionadas con impuestos en PHP. */
            $array["MergeTags"]["#tax#"] = array(
                "name" => "Impuesto",
                "value" => "#tax#"
            );

            $variablesTemp = array();

            /* asocia un valor de etiqueta a una estructura de datos en PHP. */
            $array["MergeTags"]["#totalvalue#"] = array(
                "name" => "Valor total",
                "value" => "#totalvalue#"
            );

            $variablesTemp = array();

            /* Define un arreglo con una clave y su respectivo valor en un formato específico. */
            $array["MergeTags"]["#keynotewithdrawal#"] = array(
                "name" => "Clave nota retiro",
                "value" => "#keynotewithdrawal#"
            );

            $variablesTemp = array();

            /* define un arreglo con una etiqueta de fusión para la fecha de creación. */
            $array["MergeTags"]["#creationdate#"] = array(
                "name" => "Fecha creación",
                "value" => "#creationdate#"
            );


        }


        if ($value->{"clasificador.abreviado"} == "TEMREPANORE") {


            /* define un array con etiquetas de fusión y su valor asociado. */
            $variablesTemp = array();
            $array["MergeTags"]["#idpointsale#"] = array(
                "name" => "Id punto venta",
                "value" => "#idpointsale#"
            );


            $variablesTemp = array();

            /* Se define un array para almacenar información sobre un punto de venta. */
            $array["MergeTags"]["#namepointsale#"] = array(
                "name" => "Nombre punto venta",
                "value" => "#namepointsale#"
            );


            $variablesTemp = array();

            /* Se crea un array asociativo para almacenar etiquetas de fusión y sus valores. */
            $array["MergeTags"]["#value#"] = array(
                "name" => "Valor",
                "value" => "#value#"
            );


            $variablesTemp = array();

            /* Se define un array que almacena una etiqueta de fecha de creación. */
            $array["MergeTags"]["#creationdate#"] = array(
                "name" => "Fecha creación",
                "value" => "#creationdate#"
            );

            $variablesTemp = array();

            /* Se define un arreglo para almacenar etiquetas de fusión, específicamente un número de retiro. */
            $array["MergeTags"]["#withdrawalnotenumber#"] = array(
                "name" => "Numero nota retiro",
                "value" => "#withdrawalnotenumber#"
            );

            $variablesTemp = array();

            /* Código define una etiqueta de fusión para impuestos en un array. */
            $array["MergeTags"]["#tax#"] = array(
                "name" => "Impuesto",
                "value" => "#tax#"
            );

            $variablesTemp = array();

            /* Define un arreglo con una clave "MergeTags" que almacena información del valor total. */
            $array["MergeTags"]["#totalvalue#"] = array(
                "name" => "Valor total",
                "value" => "#totalvalue#"
            );
        }
        if ($value->{"clasificador.abreviado"} == "TEMREPAPREMIO") {


            /* Se crea una variable para asignar etiquetas de fusión con valores específicos. */
            $variablesTemp = array();
            $array["MergeTags"]["#idpointsale#"] = array(
                "name" => "Id punto venta",
                "value" => "#idpointsale#"
            );


            $variablesTemp = array();

            /* define un arreglo para etiquetas de fusión relacionadas con puntos de venta. */
            $array["MergeTags"]["#namepointsale#"] = array(
                "name" => "Nombre punto venta",
                "value" => "#namepointsale#"
            );


            $variablesTemp = array();

            /* define un arreglo con etiquetas de fusión y una variable temporal. */
            $array["MergeTags"]["#value#"] = array(
                "name" => "Valor Premio",
                "value" => "#value#"
            );


            $variablesTemp = array();

            /* Código define un array con etiquetas de fusión para almacenar información de fecha. */
            $array["MergeTags"]["#creationdate#"] = array(
                "name" => "Fecha Pago",
                "value" => "#creationdate#"
            );

            $variablesTemp = array();

            /* Se define un array que almacena un número de ticket para uso posterior. */
            $array["MergeTags"]["#ticketnumber#"] = array(
                "name" => "Numero Ticket",
                "value" => "#ticketnumber#"
            );

            /* Se define un array que almacena un número de ticket del proveedor para uso posterior. */
            $array["MergeTags"]["#ticketnumber2#"] = array(
                "name" => "Numero Ticket proveedor",
                "value" => "#ticketnumber2#"
            );

            $variablesTemp = array();

            /* Se asigna un impuesto a un array de etiquetas de fusión. */
            $array["MergeTags"]["#tax#"] = array(
                "name" => "Impuesto",
                "value" => "#tax#"
            );

            $variablesTemp = array();

            /* Define un arreglo con una etiqueta de fusión para el valor total. */
            $array["MergeTags"]["#totalvalue#"] = array(
                "name" => "Valor total",
                "value" => "#totalvalue#"
            );
        }


        if ($value->{"clasificador.abreviado"} == "PREMSINTIENDA") {

            /* Se declara un arreglo para almacenar etiquetas de fusión relacionadas con premios. */
            $variablesTemp = array();
            $array["MergeTags"]["#IdPrice#"] = array(
                "name" => "Id Premio",
                "value" => "#IdPrice#"
            );

            $variablesTemp = array();

            /* Se define un array para manejar el número de nota de cobro en variables. */
            $array["MergeTags"]["#billingNoteNumber#"] = array(
                "name" => "numero nota de cobro",
                "value" => "#billingNoteNumber#"
            );

            $variablesTemp = array();

            /* Define un array con una etiqueta y un valor asociado para contraseñas. */
            $array["MergeTags"]["#PasswordPrice#"] = array(
                "name" => "Contraseña",
                "value" => "#Key#"
            );


            $variablesTemp = array();

            /* Se define un array para almacenar información sobre el número de cliente. */
            $array["MergeTags"]["#numberCustomer#"] = array(
                "name" => "numero de cliente",
                "value" => "#numberCustomer#"
            );

            $variablesTemp = array();

            /* Código define un elemento de arreglo con etiquetas de fusión para un cliente. */
            $array["MergeTags"]["#nameCustomer#"] = array(
                "name" => " nombre cliente",
                "value" => "#nameCustomer#"
            );

            $variablesTemp = array();

            /* Se asigna un valor de fecha en un array para su uso posterior. */
            $array["MergeTags"]["#Date#"] = array(
                "name" => "fecha",
                "value" => "#Date#"
            );

            $variablesTemp = array();

            /* Define un elemento en un array con nombre y valor específico. */
            $array["MergeTags"]["#Key#"] = array(
                "name" => "Clave",
                "value" => "#Key#"
            );

            $variablesTemp = array();

            /* asigna un tag de premio a un array para su uso posterior. */
            $array["MergeTags"]["#PrizeToClaim#"] = array(
                "name" => "premio a reclamar",
                "value" => "#PrizeToClaim#"
            );


            $variablesTemp = array();

            /* define un array con un marcador de posición para el tipo de premio. */
            $array["MergeTags"]["#PrizeType#"] = array(
                "name" => "tipo de premio",
                "value" => "#PrizeType#"
            );

            // array_push($plantillasnew,$array);

        }


        if ($value->{"clasificador.abreviado"} == "PREMLEALTAD") {

            /* define un array con un merge tag para un premio específico. */
            $variablesTemp = array();
            $array["MergeTags"]["#IdPrice#"] = array(
                "name" => "Id Premio",
                "value" => "#IdPrice#"
            );

            $variablesTemp = array();

            /* Se define un array con etiquetas de fusión y sus respectivos valores. */
            $array["MergeTags"]["#billingNoteNumber#"] = array(
                "name" => "numero nota de cobro",
                "value" => "#billingNoteNumber#"
            );

            $variablesTemp = array();

            /* Define un arreglo para manejar etiquetas de fusión relacionadas con contraseñas. */
            $array["MergeTags"]["#PasswordPrice#"] = array(
                "name" => "Contraseña",
                "value" => "#PasswordPrice#"
            );


            $variablesTemp = array();

            /* Se define un array para almacenar etiquetas de fusión con información del cliente. */
            $array["MergeTags"]["#numberCustomer#"] = array(
                "name" => "numero de cliente",
                "value" => "#numberCustomer#"
            );

            $variablesTemp = array();

            /* define una etiqueta de fusión para un nombre de cliente en un array. */
            $array["MergeTags"]["#nameCustomer#"] = array(
                "name" => " nombre cliente",
                "value" => "#nameCustomer#"
            );

            $variablesTemp = array();

            /* Se define un arreglo con una etiqueta de fecha y su valor correspondiente. */
            $array["MergeTags"]["#Date#"] = array(
                "name" => "fecha",
                "value" => "#Date#"
            );

            $variablesTemp = array();

            /* Configura un arreglo de etiquetas de fusión con nombre y valor definidos. */
            $array["MergeTags"]["#Key#"] = array(
                "name" => "Clave",
                "value" => "#Key#"
            );

            $variablesTemp = array();

            /* Define un array para manejar etiquetas de fusión relacionadas con premios a reclamar. */
            $array["MergeTags"]["#PrizeToClaim#"] = array(
                "name" => "premio a reclamar",
                "value" => "#PrizeToClaim#"
            );


            $variablesTemp = array();

            /* Se define un array para almacenar información sobre el tipo de premio. */
            $array["MergeTags"]["#PrizeType#"] = array(
                "name" => "tipo de premio",
                "value" => "#PrizeType#"
            );

            $variablesTemp = array();

            /* Se asigna un "MergeTag" para la ciudad de entrega en un array. */
            $array["MergeTags"]["#deliveryCity#"] = array(
                "name" => "ciudad de entrega",
                "value" => "#deliveryCity#"
            );

            $variablesTemp = array();

            /* asigna un arreglo a la clave "#Premises#" en $array. */
            $array["MergeTags"]["#Premises#"] = array(
                "name" => "Local",
                "value" => "#Premises#"
            );


        }


        /* asigna nombres y valores a etiquetas de fusión basadas en una condición. */
        if ($value->{'clasicador.abreviado'} === 'TEMOTPCODE') {
            $array['MergeTags']['#Name#'] = [
                'name' => 'Nomnbre de usuario',
                'value' => '#Name#'
            ];

            $array['MergeTags']['#Code#'] = [
                'name' => 'Codigo',
                'value' => '#Code#'
            ];

            $array['MergeTags']['#IdRetiro#'] = [
                'name' => 'Id retiro',
                'value' => '#IdRetiro',
            ];
        }

        if ($value->{'clasificador.abreviado'} === 'TEMPEXPAUTOWITHDRAW') {

            /* define un array para etiquetar usuarios con identificador y nombre. */
            $array['Mergetags']['#userId#'] = [
                'name' => 'usuario id',
                'value' => '#userId#'
            ];

            $array['Mergetags']['#name#'] = [
                'name' => 'nombre del usuario',
                'value' => '#name#'
            ];


            /* Define merge tags para tiempo de expiración y ID de nota de retiro. */
            $array['Mergetags']['#expireTime#'] = [
                'name' => 'tiempo de expiracion',
                'value' => '#expireTime#'
            ];

            $array['Mergetags']['#withdrawId'] = [
                'name' => 'id nota de retiro',
                'value' => '#withdrawId#'
            ];
        }


        /* verifica un valor y crea un array de etiquetas de fusión. */
        if ($value->{"clasificador.abreviado"} == "TMPMINCETURBLACKLIST") {

            $variablesTemp = array();
            $array["MergeTags"]["#mandante#"] = array(
                "name" => "Mandante",
                "value" => "#mandante#"
            );

            $array['MergeTags']['#user#'] = ['name' => 'Nombre completo', 'value' => '#fullname#'];
        }

        if($value->{"clasificador.abreviado"} == "PROVSMS"){

            $variablesTemp = array();
            $array["MergeTags"]["#nombre#"] = array(
                "name" => "Nombre",
                "value" => "#nombre#"
            );

            $variablesTemp = array();
            $array["MergeTags"]["#cuentaCobroId#"] = array(
                "name" => "Id cuenta de cobro",
                "value" => "#cuentaCobroId#"
            );

            $variablesTemp = array();
            $array["MergeTags"]["#usuarioMoneda#"] = array(
                "name" => "Moneda usuario",
                "value" => "#usuarioMoneda#"
            );

            $variablesTemp = array();
            $array["MergeTags"]["#cuentaCobroValor#"] = array(
                "name" => "Valor cuenta de cobro",
                "value" => "#cuentaCobroValor#"
            );

            $variablesTemp = array();
            $array["MergeTags"]["#codigo#"] = array(
                "name" => "Codigo",
                "value" => "#codigo#"
            );
        }


        /* Añade el contenido de `$array` al final de `$plantillasnew`. */
        array_push($plantillasnew, $array);
    }


    /* asigna un idioma basado en la selección del usuario. */
    $rules = [];

    switch ($LanguageSelect) {
        case "es":
            $lenguaje = "ESPAÑOL";
            break;
        case "en":
            $lenguaje = "INGLES";
            break;
        case "pt":
            $lenguaje = "PORTUGUES";
            break;
    }

    /* Condicional que agrupa reglas basadas en el valor de $Id. */
    $grouping = '';

    if ($Id != "") {

        array_push($rules, array("field" => "template.template_id", "data" => $Id, "op" => "eq"));
        $grouping = 'template.template_id';
    }

    /* agrega reglas a un array dependiendo de condiciones específicas. */
    if ($Type != "") {

        array_push($rules, array("field" => "clasificador.clasificador_id", "data" => $Type, "op" => "eq"));
    }
    if ($Section == "emails") {
        array_push($rules, array("field" => "clasificador.tipo", "data" => "TPE", "op" => "eq"));

    }

    /* Agrega reglas según la sección: "receipts" o "sms" en un arreglo. */
    if ($Section == "receipts") {

        array_push($rules, array("field" => "clasificador.tipo", "data" => "TPR", "op" => "eq"));
    }
    if ($Section == "sms") {

        array_push($rules, array("field" => "clasificador.tipo", "data" => "TPS", "op" => "eq"));
    }


    /* Agrega reglas de filtro basadas en selecciones de lenguaje y país. */
    if ($LanguageSelect != '') {
        array_push($rules, array("field" => "template.lenguaje", "data" => $LanguageSelect, "op" => "eq"));

    }
    if ($CountrySelect != '') {
        array_push($rules, array("field" => "template.pais_id", "data" => $CountrySelect, "op" => "eq"));

    }

    /* Se crea un filtro en formato JSON para un sistema de plantillas. */
    array_push($rules, array("field" => "template.mandante", "data" => $mandante, "op" => "eq"));

    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $json2 = json_encode($filtro);

    $Template = new Template();

    /* Se obtienen plantillas personalizadas y se decodifican de JSON a objeto. */
    $plantillas = $Template->getTemplateCustom2("template.*,pais.*,clasificador.*", "template.template_id", "desc", $SkeepRows, $MaxRows, $json2, true, '', $grouping);
    $plantillas = json_decode($plantillas);


    foreach ($plantillas->data as $key => $value) {


        /* Se crea un array con información de país y plantilla a partir de un objeto. */
        $array = [];
        $array["CountryId"] = $value->{"template.pais_id"};
        $array["CountrySelect"] = $value->{"pais.pais_nom"};
        $array["Id"] = $value->{"template.template_id"};
        $array["TypeName"] = $value->{"template.nombre"};
        $array["TypeId"] = $value->{"clasificador.clasificador_id"};

        /* asigna valores a un array y modifica cadenas de texto. */
        $array["LanguageSelect"] = $lenguaje;
        $array["LanguageId"] = $value->{"template.lenguaje"};
        /** Se agrega Lógica para framework de plantillas de email Marketing*/
        if ($value->{"template.template_html"} != $value->{"template.template_array"}){
            $html = str_replace(['á', 'é', 'í', 'ó', 'ú', '¡'], ['&#225;', '&#233;', '&#237;', '&#243;', '&#250;', '&iexcl;'], $value->{"template.template_html"});
            $array["Template2"] = $value->{"template.template_array"};
        }
        else {
            $html = str_replace(['á', 'é', 'í', 'ó', 'ú', '¡'], ['&#225;', '&#233;', '&#237;', '&#243;', '&#250;', '&iexcl;'], $value->{"template.template_array"});
            $array["Template2"] = '';

        }
//        $html = str_replace('<&iexcl;', '¡', $html);
        $array["Template"] = ($html);

//        $str = str_replace('"', "'", $value->{"template.template_array"});
//        $str = str_replace('">', '\">', $str);
        //  $array["Template"] = base64_encode($templateContent);
        $array["MergeTags"] = array();


        /* Defines etiquetas de fusión para usuario y nombre en un arreglo asociativo. */
        $array["MergeTags"]["#userid#"] = array(
            "name" => "Id usuario",
            "value" => "#userid#"
        );

        $array["MergeTags"]["#name#"] = array(
            "name" => "Nombre",
            "value" => "#name#"
        );

        /* define un array que almacena etiquetas de fusión con información de identificación. */
        $array["MergeTags"]["#identification#"] = array(
            "name" => "Cedula",
            "value" => "#identification#"
        );

        $variablesTemp = array();

        /* Se define un elemento de array para manejar etiquetas de fusión de apellidos. */
        $array["MergeTags"]["#lastname#"] = array(
            "name" => "Apellido",
            "value" => "#lastname#"
        );

        $variablesTemp = array();

        /* Crea un arreglo de etiquetas con datos de login y condiciones específicas. */
        $array["MergeTags"]["#login#"] = array(
            "name" => "Login",
            "value" => "#login#"
        );

        if ($value->{'clasificador.abreviado'} === 'TEMPSESIEXTE') {
            $array['MergeTags']['#type'] = ['name' => 'Tipo de inicio', 'value' => '#type'];
            $array['MergeTags']['#password'] = ['name' => 'Contraseña', 'value' => '#password'];
        }


        /* asigna etiquetas de fusión basadas en un clasificador específico. */
        if ($value->{'clasificador.abreviado'} === 'TEMPEMAIL') {
            $array['MergeTags']['#user#'] = ['name' => 'Nombre completo', 'value' => '#fullname#'];
            $array['MergeTags']['#link#'] = ['name' => 'Link', 'value' => '#link#'];
        }

        if ($value->{'clasificador.abreviado'} === 'TEMPDEPOSIT') {
            $array['MergeTags']['#Value#'] = ['name' => 'valor', 'value' => '#Value#'];
            $array['MergeTags']['#MethodPayment#'] = ['name' => 'Metodo De Pago', 'value' => '#MethodPayment#'];
            $array['MergeTags']['#Transaction#'] = ['name' => 'Transaccion', 'value' => '#Transaction#'];
        }

        if ($value->{"clasificador.abreviado"} == "TEMEMREG") {

            /* crea un arreglo para almacenar etiquetas de fusión con nombre y valor. */
            $variablesTemp = array();

            $variablesTemp = array();
            $array["MergeTags"]["#name#"] = array(
                "name" => "nombre",
                "value" => "#name#"
            );


            /* inicializa una variable de array para fusionar etiquetas de correo. */
            $variablesTemp = array();
            $array["MergeTags"]["#email"] = array(
                "name" => "Correo",
                "value" => "#email#"
            );

            $variablesTemp = array();

            /* Se define un array con una etiqueta de contraseña y su valor correspondiente. */
            $array["MergeTags"]["#password#"] = array(
                "name" => "Contraseña",
                "value" => "#password#"
            );

            $variablesTemp = array();

            /* Define un arreglo con etiquetas de fusión y variables temporales. */
            $array["MergeTags"]["#Mandante#"] = array(
                "name" => "Marca",
                "value" => "#Mandante#"
            );


            $variablesTemp = array();

            /* Asigna un enlace a un arreglo con nombre y valor específico. */
            $array["MergeTags"]["#link#"] = array(
                "name" => "Url",
                "value" => "#link#"
            );


            // este no es

        }


        /* Condicional que asigna valores a un array según una condición específica. */
        if ($value->{"clasificador.abreviado"} == "RECJUMIO") {
            $variablesTemp = array();
            $array["MergeTags"]["#Name#"] = array(
                "name" => "nombre",
                "value" => "#Name#"
            );

            $variablesTemp = array();
            $array["MergeTags"]["#Mandante#"] == array(
                "name" => "mandante",
                "value" => "#Mandante#"
            );


        }


        if ($value->{"clasificador.abreviado"} == "TEMPACTDA") {

            /* Se crea un array con etiquetas de fusión para personalizar mensajes. */
            $variablesTemp = array();
            $array["MergeTags"]["#Name#"] = array(
                "name" => "Usuario",
                "value" => "#Name#"

            );


            /* Se crea un array que mapea una etiqueta de fusión con su nombre y valor. */
            $variablesTemp = array();
            $array["MergeTags"]["#mandante#"] = array(
                "name" => "Mandante",
                "value" => "#mandante#"
            );

            $variablesTemp = array();

            /* Se define una etiqueta de fusión para el apellido en un array. */
            $array["MergeTags"]["#apellido#"] = array(
                "name" => "Apellido",
                "value" => "#apellido#"
            );

            $variablesTemp = array();

            /* Asigna una contraseña en un array asociativo bajo la clave "#Contraseña#". */
            $array["MergeTags"]["#Contraseña#"] = array(
                "name" => "contraseña",
                "value" => "#Contraseña#"
            );


        }


        /* asigna etiquetas de fusión basadas en una condición específica. */
        if ($value->{"clasificador.abreviado"} == "TEMEMPASS") {
            $variablesTemp = array();

            $variablesTemp = array();
            $array["MergeTags"]["#link#"] = array(
                "name" => "Link",
                "value" => "#link#"
            );
            $array['MergeTags']['#banners#'] = [
                'name' => 'banners',
                'value' => '#banners#'
            ];


        }


        /* verifica un valor y asigna una fecha de creación a un arreglo. */
        if ($value->{"clasificador.abreviado"} == "TEMEMVEACCO") {
            $variablesTemp = array();

            $variablesTemp = array();
            $array["MergeTags"]["#creationdate#"] = array(
                "name" => "Fecha creación",
                "value" => "#creationdate#"
            );
        }


        /* Condicional que verifica un clasificador y asigna una fecha de creación. */
        if ($value->{"clasificador.abreviado"} == "TEMEMVEACIN") {
            $variablesTemp = array();

            $variablesTemp = array();
            $array["MergeTags"]["#creationdate#"] = array(
                "name" => "Fecha creación",
                "value" => "#creationdate#"
            );

        }

        if ($value->{"clasificador.abreviado"} == "TEMRECRE") {


            /* Asignación de etiquetas de fusión con un identificador para un punto de venta. */
            $variablesTemp = array();
            $array["MergeTags"]["#idpointsale#"] = array(
                "name" => "Id punto venta",
                "value" => "#idpointsale#"
            );


            $variablesTemp = array();

            /* Código asigna un valor a un tag de fusión en un array para puntos de venta. */
            $array["MergeTags"]["#namepointsale#"] = array(
                "name" => "Nombre punto venta",
                "value" => "#namepointsale#"
            );


            $variablesTemp = array();

            /* crea un array con un valor específico y un espacio para variables temporales. */
            $array["MergeTags"]["#value#"] = array(
                "name" => "Valor",
                "value" => "#value#"
            );


            $variablesTemp = array();

            /* Se crea un array que almacena la fecha de creación con etiquetas de fusión. */
            $array["MergeTags"]["#creationdate#"] = array(
                "name" => "Fecha creación",
                "value" => "#creationdate#"
            );

            $variablesTemp = array();

            /* Se asigna un número de depósito a una estructura de array en PHP. */
            $array["MergeTags"]["#depositnumber#"] = array(
                "name" => "Numero deposito",
                "value" => "#depositnumber#"
            );

            $variablesTemp = array();

            /* Se define un array que relaciona "MergeTags" con un impuesto específico. */
            $array["MergeTags"]["#tax#"] = array(
                "name" => "Impuesto",
                "value" => "#tax#"
            );

            $variablesTemp = array();

            /* Crea un arreglo con información sobre un marcador de fusión llamado "Valor total". */
            $array["MergeTags"]["#totalvalue#"] = array(
                "name" => "Valor total",
                "value" => "#totalvalue#"
            );

        }

        if ($value->{"clasificador.abreviado"} == "TEMRECNORE") {

            /* define un array para almacenar etiquetas de fusión con un identificador. */
            $variablesTemp = array();
            $array["MergeTags"]["#idnotewithdrawal#"] = array(
                "name" => "Id nota retiro",
                "value" => "#idnotewithdrawal#"
            );

            $variablesTemp = array();

            /* Define un arreglo con etiquetas de fusión y una variable temporal vacía. */
            $array["MergeTags"]["#value#"] = array(
                "name" => "Valor",
                "value" => "#value#"
            );


            $variablesTemp = array();

            /* Se define un arreglo para manejar etiquetas de impuestos en un sistema. */
            $array["MergeTags"]["#tax#"] = array(
                "name" => "Impuesto",
                "value" => "#tax#"
            );

            $variablesTemp = array();

            /* define una estructura de datos para almacenar etiquetas de fusión y sus valores. */
            $array["MergeTags"]["#totalvalue#"] = array(
                "name" => "Valor total",
                "value" => "#totalvalue#"
            );

            $variablesTemp = array();

            /* Se define una clave y valor en un arreglo asociativo en PHP. */
            $array["MergeTags"]["#keynotewithdrawal#"] = array(
                "name" => "Clave nota retiro",
                "value" => "#keynotewithdrawal#"
            );

            $variablesTemp = array();

            /* define una etiqueta de fusión para la fecha de creación en un array. */
            $array["MergeTags"]["#creationdate#"] = array(
                "name" => "Fecha creación",
                "value" => "#creationdate#"
            );


        }

        if ($value->{"clasificador.abreviado"} == "TEMREPANORE") {


            /* define un array con un marcador de fusión para un punto de venta. */
            $variablesTemp = array();
            $array["MergeTags"]["#idpointsale#"] = array(
                "name" => "Id punto venta",
                "value" => "#idpointsale#"
            );


            $variablesTemp = array();

            /* asigna etiquetas de fusión y define una variable temporal. */
            $array["MergeTags"]["#namepointsale#"] = array(
                "name" => "Nombre punto venta",
                "value" => "#namepointsale#"
            );


            $variablesTemp = array();

            /* Se define un array que almacena etiquetas de fusión con nombre y valor. */
            $array["MergeTags"]["#value#"] = array(
                "name" => "Valor",
                "value" => "#value#"
            );


            $variablesTemp = array();

            /* Se asigna un valor y nombre a la etiqueta de fecha de creación en un array. */
            $array["MergeTags"]["#creationdate#"] = array(
                "name" => "Fecha creación",
                "value" => "#creationdate#"
            );

            $variablesTemp = array();

            /* Se define un array que almacena información sobre un número de nota de retiro. */
            $array["MergeTags"]["#withdrawalnotenumber#"] = array(
                "name" => "Numero nota retiro",
                "value" => "#withdrawalnotenumber#"
            );

            $variablesTemp = array();

            /* asigna una etiqueta de impuesto a un array de variables. */
            $array["MergeTags"]["#tax#"] = array(
                "name" => "Impuesto",
                "value" => "#tax#"
            );

            $variablesTemp = array();

            /* Asigna un valor total a un arreglo usando etiquetas de fusión. */
            $array["MergeTags"]["#totalvalue#"] = array(
                "name" => "Valor total",
                "value" => "#totalvalue#"
            );

        }

        if (in_array($value->{"clasificador.abreviado"}, ["TEMPPAGOVIRTUAL", "TEMPAPUESTAVIRTUAL"])) {

            /* Se definen etiquetas para reemplazar en un sistema de tickets y premios. */
            $mergeTags = [
                "#LogoMandante#" => "Logo del Mandante",
                "#ticketnumber#" => "Numero de Ticket",
                "#ticketnumber2#" => "Numero de Ticket GoldenRace",
                "#keyTicket#" => "Clave del Ticket",
                "#dateTicket#" => "Fecha creación del Ticket",
                "#idpointsale#" => "Nombre Punto de Venta",
                "#user#" => "ID Usuario Relacionado",
                "#value#" => "Valor del Premio",
                "#tax#" => "Valor Impuesto",
                "#totalvalue#" => "Valor Total Premio",
                "#game#" => "Nombre del Juego",
                "#partnert#" => "Nombre del Partnert",
                "#partnertRuc#" => "Ruc del Partnert",
                "#barcode#" => "ID Ticket Codigo de Barras",
                "#barcode2#" => "ID clave Codigo de Barras 2",
            ];


            /* Itera sobre $mergeTags, asignando nombres y valores a un nuevo array. */
            foreach ($mergeTags as $tag => $name) {
                $array["MergeTags"][$tag] = [
                    "name" => $name,
                    "value" => $tag
                ];
            }
        }


        /* Condicional que asigna etiquetas de fusión según un valor específico. */
        if ($value->{'clasicador.abreviado'} === 'TEMOTPCODE') {
            $array['MergeTags']['#Name#'] = [
                'name' => 'Nomnbre de usuario',
                'value' => '#name#'
            ];

            $array['MergeTags']['#Code#'] = [
                'name' => 'Codigo OTP',
                'value' => '#code#'
            ];

            $array['MergeTags']['#IdRetiro#'] = [
                'name' => 'Id retiro',
                'value' => '#idWithdraw#',
            ];
        }


        /* Verifica un valor y asigna etiquetas de fusión a un array si coincide. */
        if ($value->{"clasificador.abreviado"} == "TMPMINCETURBLACKLIST") {

            $variablesTemp = array();
            $array["MergeTags"]["#mandante#"] = array(
                "name" => "Mandante",
                "value" => "#mandante#"
            );

            $array['MergeTags']['#user#'] = ['name' => 'Nombre completo', 'value' => '#fullname#'];
        }


        /* Agrega un elemento al final del array "plantillasRecibidas". */
        array_push($plantillasRecibidas, $array);

    }


    /* actualiza elementos en un arreglo basado en coincidencias de identificador de tipo. */
    foreach ($plantillasnew as $key => $value) {

        foreach ($plantillasRecibidas as $key2 => $value2) {


                if($value["TypeId"] == $value2["TypeId"]){

                    $plantillasnew[$key]["Id"] = $value2["Id"];
                    $plantillasnew[$key]["CountryId"] = $value2["CountryId"];
                    $plantillasnew[$key]["CountrySelect"] = $value2["CountrySelect"];
                    $plantillasnew[$key]["LanguageSelect"] = $value2["LanguageSelect"];
                    $plantillasnew[$key]["LanguageId"] = $value2["LanguageId"];
                    $plantillasnew[$key]["Template"] = $value2["Template"];
                    $plantillasnew[$key]["Template2"] = $value2["Template2"];//Se agrega Template2 a la respuesta
                }
            }

    }


    /* Filtra elementos de $plantillasnew, eliminando aquellos con 'Id' vacío. */
    if ($Id != '') {
        $plantillasnew2 = array();
        foreach ($plantillasnew as $item) {
            if ($item['Id'] != '') {
                array_push($plantillasnew2, $item);
            }
        }
        $plantillasnew = $plantillasnew2;

    }


    /* crea un arreglo de respuesta con datos y posiciones específicas. */
    $response = array();
    $response["data"] = array(
        "messages" => array()
    );
    $response["data"] = $plantillasnew;
    $response["pos"] = $SkeepRows;

    /* Asigna el conteo total de plantillas a la variable de respuesta. */
    $response["total_count"] = oldCount($plantillasnew);


}
