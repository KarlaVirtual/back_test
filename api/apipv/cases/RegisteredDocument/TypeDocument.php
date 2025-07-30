<?php

use Backend\dto\Banco;
use Backend\dto\BonoDetalle;
use Backend\dto\Categoria;
use Backend\dto\Clasificador;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Consecutivo;
use Backend\dto\CuentaCobro;
use Backend\dto\Descarga;
use Backend\dto\DocumentoUsuario;
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
use Backend\dto\UsuarioOtrainfo;
use Backend\dto\UsuarioPerfil;
use Backend\dto\UsuarioPremiomax;
use Backend\dto\UsuarioPublicidad;
use Backend\dto\UsuarioRecarga;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioMensaje;
use Backend\dto\ProductoMandante;
use Backend\dto\ProdMandanteTipo;
use Backend\dto\Proveedor;
use Backend\dto\Producto;
use Backend\dto\Pais;
use Backend\integrations\casino\IES;
use Backend\integrations\casino\JOINSERVICES;
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
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\UsuarioOtrainfoMySqlDAO;
use Backend\mysql\UsuarioPerfilMySqlDAO;
use Backend\mysql\UsuarioPremiomaxMySqlDAO;
use Backend\mysql\UsuarioRecargaMySqlDAO;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\websocket\WebsocketUsuario;

/**
 * RegisteredDocument/TypeDocument
 *
 * Obtener clasificadores de tipo documento
 *
 * Este recurso obtiene una lista de clasificadores de tipo "DOCUMENTO" que están activos en el sistema.
 * Los parámetros `SkeepRows` y `MaxRows` controlan la paginación de los resultados. El servicio consulta los clasificadores
 * y los retorna con su ID y descripción.
 *
 * @param int $SkeepRows : Número de filas a omitir para la paginación (por defecto 0).
 * @param int $MaxRows : Número máximo de filas a retornar (por defecto 1000).
 *
 * @returns object El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error al obtener los clasificadores.
 *  - *AlertType* (string): Tipo de alerta generada (por ejemplo, "success").
 *  - *AlertMessage* (string): Mensaje de alerta relacionado con la operación.
 *  - *ModelErrors* (array): Retorna array vacío.
 *  - *Data* (array): Contiene los clasificadores obtenidos, cada uno con los siguientes campos:
 *    - *id* (int): ID del clasificador.
 *    - *value* (string): Descripción del clasificador.
 *
 * Objeto en caso de error:
 *  - *HasError* => true,
 *  - *AlertType* => "danger",
 *  - *AlertMessage* => "[Mensaje de error]",
 *  - *ModelErrors* => array(),
 *  - *Data* => array().
 *
 * @throws Exception no
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* asigna valores predeterminados a variables si están vacías. */
$SkeepRows = "";
$MaxRows = "";
if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($MaxRows == "") {
    $MaxRows = 1000;
}


/* Se crea un filtro con reglas para clasificar documentos activos. */
$rules = [];

array_push($rules, array("field" => "clasificador.estado", "data" => "A", "op" => "eq"));
array_push($rules, array("field" => "clasificador.tipo", "data" => "DOCUMENTO", "op" => "eq"));


$filtro = array("rules" => $rules, "groupOp" => "AND");

/* genera clasificadores personalizables y los decodifica en JSON. */
$json_filter = json_encode($filtro);


$Clasificador = new Clasificador();
$clasificadores = $Clasificador->getClasificadoresCustom(" clasificador.* ", "clasificador.clasificador_id", "asc", $SkeepRows, $MaxRows, $json_filter, true);

$clasificadores = json_decode($clasificadores);

/* crea un array de clasificadores a partir de datos ingresados. */
$arrayClasificador = [];

foreach ($clasificadores->data as $key => $value) {

    $array = [];

    $array["id"] = $value->{"clasificador.clasificador_id"};
    $array["value"] = $value->{"clasificador.descripcion"};


    array_push($arrayClasificador, $array);
}


/* Código PHP que configura una respuesta JSON sin errores y con datos. */
$response["HasError"] = false;
$response["AlertType"] = "success";
$response["AlertMessage"] = "";
$response["ModelErrors"] = [];
$response["Data"] = $arrayClasificador;

