<?php

use Backend\dto\Banco;
use Backend\dto\BonoDetalle;
use Backend\dto\BonoInterno;
use Backend\dto\Categoria;
use Backend\dto\CategoriaMandante;
use Backend\dto\Clasificador;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Consecutivo;
use Backend\dto\CuentaCobro;
use Backend\dto\Descarga;
use Backend\dto\DocumentoUsuario;
use Backend\dto\EquipoFavorito;
use Backend\dto\IntApuestaDetalle;
use Backend\dto\IntCompetencia;
use Backend\dto\IntDeporte;
use Backend\dto\IntEventoApuesta;
use Backend\dto\IntEventoApuestaDetalle;
use Backend\dto\IntEventoDetalle;
use Backend\dto\IntRegion;
use Backend\dto\ItTicketEnc;
use Backend\dto\Mandante;
use Backend\dto\MandanteDetalle;
use Backend\dto\PaisMoneda;
use Backend\dto\PromocionalLog;
use Backend\dto\PuntoVenta;
use Backend\dto\Registro;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioBanco;
use Backend\dto\UsuarioConfig;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\UsuarioLog;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioMarketing;
use Backend\dto\UsuarioOtrainfo;
use Backend\dto\UsuarioPerfil;
use Backend\dto\UsuarioPremiomax;
use Backend\dto\UsuarioPublicidad;
use Backend\dto\UsuarioRecarga;
use Backend\dto\UsuarioTarjetacredito;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioMensaje;
use Backend\dto\ProductoMandante;
use Backend\dto\ProdMandanteTipo;
use Backend\dto\Proveedor;
use Backend\dto\Producto;
use Backend\dto\Pais;
use Backend\integrations\casino\IES;
use Backend\integrations\casino\JOINSERVICES;
use Backend\mysql\BonoInternoMySqlDAO;
use Backend\mysql\ConsecutivoMySqlDAO;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\DocumentoUsuarioMySqlDAO;
use Backend\mysql\PromocionalLogMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\mysql\RegistroMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\UsuarioBancoMySqlDAO;
use Backend\mysql\UsuarioBannerMySqlDAO;
use Backend\mysql\UsuarioConfiguracionMySqlDAO;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\mysql\UsuarioMandanteMySqlDAO;
use Backend\mysql\UsuarioMarketingMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\UsuarioOtrainfoMySqlDAO;
use Backend\mysql\UsuarioPerfilMySqlDAO;
use Backend\mysql\UsuarioPremiomaxMySqlDAO;
use Backend\mysql\UsuarioRecargaMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\websocket\WebsocketUsuario;

/**
 * Obtiene las categorías de productos de un casino, las organiza en una estructura jerárquica con subcategorías y juegos,
 * y devuelve esta información en una respuesta JSON.
 *
 * @param object $json Objeto JSON que contiene los parámetros de la solicitud.
 * @param object $json->session Objeto que contiene la sesión del usuario.
 * @param object $json->session->usuario Usuario de la sesión actual.
 * @param object $json->params Objeto que contiene los parámetros de la solicitud.
 * @param int $json->params->MaxRows Número máximo de filas.
 * @param int $json->params->OrderedItem Ítem ordenado.
 * @param int $json->params->SkeepRows Filas a omitir.
 * @param int $json->params->count Número máximo de filas.
 * @param int $json->params->start Filas a omitir.
 * @param int $json->params->site_id ID del sitio.
 * @param int $json->rid Identificador de la solicitud.
 *
 * @throws Exception Si los parámetros son inválidos.
 *
 * @return array
 *  - code:int Código de respuesta.
 *  - rid:int Identificador de la solicitud.
 *  - data:array Datos de la respuesta.
 *  - total_count:int Conteo total de categorías.
 */

// Inicializa un arreglo de respuesta con código, identificador de solicitud y datos.
$response = array(); // Inicializa un arreglo para almacenar la respuesta.
$response["code"] = 0; // Establece el código de respuesta a 0, indicando éxito.
$response["rid"] = $json->rid; // Asigna el identificador de solicitud desde el objeto JSON.
$response["data"] = array( // Crea un arreglo de datos dentro de la respuesta.
    "result" => "" // Inicializa el resultado como una cadena vacía.
);

// Crea una instancia de UsuarioMandante basado en el usuario de la sesión actual.
$UsuarioMandante = new UsuarioMandante($json->session->usuario);
$Usuario = new Usuario($UsuarioMandante->usuarioMandante);
$params = $json->params;
$site_id = $json->params->site_id;

$MaxRows = $params->MaxRows; // Asigna el número máximo de filas desde los parámetros.
$OrderedItem = $params->OrderedItem; // Asigna el ítem ordenado desde los parámetros.
$SkeepRows = $params->SkeepRows; // Asigna las filas a omitir desde los parámetros.

// Asigna el número máximo de filas desde el objeto JSON sin importar la otra asignación.
$MaxRows = $json->params->count;
$SkeepRows = $json->params->start;

    // Si no se especifica el número de filas a omitir, se establece en 0.
    if ($SkeepRows == "") {
        $SkeepRows = 0;
    }

    // Si no se especifica el ítem ordenado, se establece en 1.
    if ($OrderedItem == "") {
        $OrderedItem = 1;
    }

// Si no se especifica el número máximo de filas, se establece en 10.
    if ($MaxRows == "") {
        $MaxRows = 10;
    }

    $rules = [];


    array_push($rules, array("field" => "categoria_mandante.mandante", "data" => '-1', "op" => "eq"));
    array_push($rules, array("field" => "categoria_mandante.pais_id", "data" => '0', "op" => "eq"));
    array_push($rules, array("field" => "categoria_mandante.tipo", "data" => "CASINO", "op" => "eq"));
    array_push($rules, array("field" => "categoria_mandante.estado", "data" => "A", "op" => "eq"));

    // Se crea un filtro que combina las reglas con el operador lógico AND
    $filtro = array("rules" => $rules, "groupOp" => "AND");
    $jsonfiltro = json_encode($filtro);

    // Se seleccionan todos los campos de la tabla categoria_mandante
    $select = "categoria_mandante.*";

    // Se instancia la clase CategoriaMandante
    $CategoriaMandante = new CategoriaMandante();

    // Se obtienen los datos filtrados de categoria_mandante
    $data = $CategoriaMandante->getCategoriaMandanteCustom($select, "categoria_mandante.catmandante_id", 'asc', $SkeepRows, $MaxRows, $jsonfiltro, true);
    $categorias = json_decode($data);
    $categoriasData = array();

    // Se recorren los datos obtenidos para estructurar el arreglo final
    foreach ($categorias->data as $key => $value) {
        $array = array();

        // Se almacenan el ID y el nombre de la categoria en el arreglo
        $array["Id"] = $value->{"categoria_mandante.catmandante_id"};
        $array["Name"] = $value->{"categoria_mandante.descripcion"};

        // Se agrega el arreglo con datos de la categoria al arreglo de datos finales
        array_push($categoriasData, $array);
    }

    // Se prepara la respuesta final que incluye el código, los datos y el conteo total
    $response = array();
    $response["code"] = 0;
    $response["data"] = $categoriasData;

    $response["total_count"] = $categorias->count[0]->{".count"};

    $response["rid"] = $json->rid;


