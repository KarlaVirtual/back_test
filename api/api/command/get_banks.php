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
 * Obtiene la lista de bancos y sus detalles.
 *
 * @param object $params Parámetros de la solicitud.
 *  - int $MaxRows Número máximo de filas a obtener.
 *  - int $OrderedItem Elemento ordenado.
 *  - int $SkeepRows Número de filas a omitir.
 *  - string $type Tipo de banco.
 * @throws Exception Si ocurre un error al obtener los datos de los bancos.
 * @return array
 *  - code: int Código de respuesta.
 *  - rid: string Id de la petición.
 *  - data: array Datos de los bancos.
 */

$UsuarioMandante = $UsuarioMandanteSite;
$Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
//$Pais = new Pais($Usuario->paisId);

$params = file_get_contents('php://input');
$params = json_decode($params);

$MaxRows = $params->MaxRows;
$OrderedItem = $params->OrderedItem;
$SkeepRows = $params->SkeepRows;
$type = $params->params->type;

if ($SkeepRows == "") {
    $SkeepRows = 0;
}

if ($OrderedItem == "") {
    $OrderedItem = 1;
}

if ($MaxRows == "") {
    $MaxRows = 1000;
}

/*Definición filtros para la solicitud de información vinculada al ID del usuario y el banco*/
$rules = [];
array_push($rules, array("field" => "banco_mandante.pais_id", "data" => "$Usuario->paisId", "op" => "eq"));
array_push($rules, array("field" => "banco_mandante.mandante", "data" => "$Usuario->mandante", "op" => "eq"));
array_push($rules, array("field" => "banco_mandante.estado", "data" => "A", "op" => "eq"));
array_push($rules, array("field" => "banco.estado", "data" => "A", "op" => "eq"));

if($Usuario->paisId == 33 && $Usuario->mandante != 18){
    array_push($rules, array("field" => "banco.banco_id", "data" => "314", "op" => "eq"));

}

if (!empty($type) && $type === "digitals") {
    array_push($rules, array("field" => "banco.tipo", "data" => "Digital", "op" => "eq"));
}

$filtro = array("rules" => $rules, "groupOp" => "AND");
$json2 = json_encode($filtro);

$Banco = new Banco();
$BancoMandante = new \Backend\dto\BancoMandante();

/*Obtención personalizada de los bancos*/
//$Bancos = $Banco->getBancosCustom(" banco.* ", "banco.banco_id", "asc", $SkeepRows, $MaxRows, $json2, true);
//$Bancos = json_decode($bancos);

$Bancos = $BancoMandante->getBancosMandanteCustom(" banco_mandante.*,banco.*,mandante.* ", "banco.banco_id", "asc", $SkeepRows, $MaxRows, $json2, true);

$Bancos = json_decode($Bancos);

$BancosData = array();

/*Iteración de la colección personalizada de bancos*/
foreach ($Bancos->data as $key => $value) {


    $arraybanco = array();
    $arraybanco["Id"] = ($value->{"banco.banco_id"});
    $arraybanco["Name"] = ($value->{"banco.descripcion"});

    if ($Usuario->paisId == '113') {
        $arraybanco["branches"] = array();
        $arraybanco["interbankCodes"] = array();

        switch ($value->{"banco.banco_id"}) {

            //Asignación colección de códigos cuando el bancoId es 109
            case '109':
                //Bank of Nova Scotia
                array_push($arraybanco["interbankCodes"], array(
                    "value" => 'NOSCJMKN',
                    "name" => 'NOSCJMKN'
                ));



                $valueBr = "00000 - BANK OF NOVA SCOTIA (JAMAICA)";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "30015 - KING STREET";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "62075 - DATA CENTRE";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "40105 - BLACK RIVER";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "20115 - BROWN'S TOWN";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00125 - CHRISTIANA";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "80135 - CROSS ROADS";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "60145 - HALF WAY TREE";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "30155 - SHARED SERVICES";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "10165 - EAST QUEEN STREET";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "90175 - HAGLEY PARK ROAD";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "70185 - LINSTEAD";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "50195 - MANDEVILLE";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "30205 - MAY PEN";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "10215 - MONTEGO BAY";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "09225 - PRIVATE BANKING";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "90225 - OCHO RIOS";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "70235 - PORT ANTONIO";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "50245 - PORT MARIA";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "20255 - ST. ANN'S BAY";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00265 - SAVANNA-LA-MAR";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "80275 - SPANISH TOWN";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "56275 - SCOTIA CREDIT CARD CENTRE";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "01305 - FALMOUTH";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "81315 - SANTA CRUZ";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "61325 - PREMIER BRANCH";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "41335 - OLD HARBOUR";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "21345 - LUCEA";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "78345 - CASH PROCESSING UNIT";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "10355 - VICTORIA AND BLAKE";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "90365 - LIGUANEA";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "18465 - UNIVERSITY";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "22475 - JUNCTION";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "81505 - OXFORD ROAD";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "95505 - PORTMORE";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "36525 - SCOTIA JA. BUILDING SOCIETY";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "50575 - NEW KINGSTON";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "31575 - RIVERTON CITY";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "90605 - WESTGATE";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "61655 - MORANT BAY";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "60665 - HIGHGATE";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "20685 - NEW PORT WEST";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "21725 - CONSTANT SPRING FINANCIAL CENT";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "38745 - IRONSHORE";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "07765 - TELEPHONE BANKING";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "50765 - SCOTIA CENTRE HEAD OFFICE";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "29785 - BNS CORPORATE AND COMMERCIAL C";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "90795 - COMPTROLLERS DEPT.";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "92825 - NEGRIL";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "57935 - CENTRALISED ACCOUNTING UNIT";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));

                break;
            //Asignación colección de códigos cuando el bancoId es 112
            case '112':
                //National Commercial Bank
                array_push($arraybanco["interbankCodes"], array(
                    "value" => 'JNCBJMKX',
                    "name" => 'JNCBJMKX'
                ));



                $valueBr = "00001 - OPERATIONS CENTRE";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00002 - NCB CARD SERVICES - ISSUING";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00003 - HUMAN RESOURCES DEPARTMENT";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00004 - CENTRALIZED FOREIGN EXCHANGE";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00005 - NCB CARD CENTRE - ACQUIRING";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00006 - DUKE STREET";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00007 - NETWORK OPERATIONS";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00010 - DUKE AND BARRY";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00013 - BOULEVARD SUPER CENTRE";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00017 - HAGLEY PARK";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00018 - STAFF TRAINING CENTRE";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00020 - WINDWARD ROAD";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00021 - OXFORD PLACE";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00023 - CROSS ROADS";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00024 - 30 KNUTSFORD BLVD.";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00025 - YALLAHS";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00029 - PRIVATE BANKING";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00030 - HALF WAY TREE";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00031 - BOULEVARD SUPER CENTRE";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00032 - RED HILLS ROAD";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00033 - MANOR PARK";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00035 - 1-7 KNUTSFORD BOULEVARD";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00036 - PORTMORE";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00037 - MATILDA'S CORNER";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00039 - NEWPORT WEST";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00040 - UNIVERSITY";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00043 - MONTEGO BAY";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00044 - FALMOUTH";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00047 - ST JAGO SHOPPING CENTRE";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00049 - HALF MOON";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00050 - MANDEVILLE";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00051 - LEGAL, AML & CORP. COMP. DIV.";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00054 - ST ANN'S BAY";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00056 - MAY PEN";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00057 - CHAPELTON";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00058 - OCHO RIOS";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00060 - NEGRIL";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00061 - SAVANNA-LA-MAR";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00064 - MORANT BAY";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00067 - BLACK RIVER";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00068 - LINSTEAD";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00071 - BROWN'S TOWN";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00075 - LUCEA";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00078 - ANNOTTO BAY";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00081 - PORT MARIA";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00084 - PORT ANTONIO";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00085 - CHRISTIANA";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00087 - OLD HARBOUR";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00088 - JUNCTION";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00089 - SANTA CRUZ";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00090 - HEAD OFFICE";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00106 - TREASURY CORRESPONDENT BANKING";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00109 - CORPORATE BANKING DIVISION";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00119 - INFORMATION TECHNOLOGY";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00121 - SERVICE QUALITY UNIT";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00122 - NCB I L & ORGAN. DEVELOPMENT";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00124 - CARD SERV & ECHANNELS SUPPORT";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00125 - E CHANNELS";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00128 - MIDDLE MARKET UNIT";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00129 - DEBT COLLECTION UNIT";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00200 - BANKING OPERATIONS";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00201 - CENTRALISED OPERATIONS";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00202 - SPECIALISED OPERATIONS";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00203 - CENTRALISED CASH MANAGEMENT";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00204 - DIRECT BANKING UNIT";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00207 - CENTRAL DISBURSE & SECURITIES";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00212 - INTERNATIONAL BUSINESS - ADMIN";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00213 - INTERNATIONAL BUS - US OFFICE";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00901 - NCB CAPITAL MARKETS";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00904 - NCB INSURANCE SERVICES";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));

                break;
            //Asignación colección de códigos cuando el bancoId es 115
            case '115':
                //Sagicor Bank Dominica
                array_push($arraybanco["interbankCodes"], array(
                    "value" => 'JNCBJMKX',
                    "name" => 'JNCBJMKX'
                ));


                $valueBr = "00000 - SAGICOR BANK DOMINICA";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "01001 - TOWER STREET";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "01002 - HALF WAY TREE";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "01003 - MONTEGO BAY";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "01004 - MANDEVILLE";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "01005 - LINSTEAD";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "01006 - BLACK RIVER";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "01008 - SAVANNA LA MAR";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "01009 - ST. LUCIA";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "01011 - MAY PEN";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "01012 - SPANISH TOWN";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "01014 - PORTMORE";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "01015 - OCHO RIOS";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "01017 - FAIRVIEW SHOPPING CENTRE";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "01020 - UP-PARK CAMP";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "01023 - SOUTHFIELD";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "01026 - LIONEL TOWN";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "01031 - BROWN'S TOWN";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "01032 - CROSS ROADS";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "01034 - DOMINICA";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "01035 - PORT ANTONIO";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "01036 - SANTA CRUZ";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "01037 - STONY HILL";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "01050 - LIGUANEA";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "01051 - HARBOUR STREET";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "01063 - TROPICAL PLAZA";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "01080 - TREASURY";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "01085 - CORPORATE BANKING CENTRE";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "01089 - CREDIT CARD CENTRE";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "01100 - HEAD OFFICE";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "01103 - PRIVATE BANKING";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "01110 - CENTRALISED OPERATIONS";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                break;
            //Asignación colección de códigos cuando el bancoId es 118
            case '118':
                //First Global Bank
                array_push($arraybanco["interbankCodes"], array(
                    "value" => 'FILBJMKN',
                    "name" => 'FILBJMKN'
                ));


                $valueBr = "00000 - FIRST GLOBAL BANK LIMITED";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "99075 - NEW KINGSTON";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "99080 - GLOUCESTER AVENUE";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "99082 - MANOR PARK";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "99084 - MANDEVILLE";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "99085 - LIGUANEA";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "99086 - SANTA CRUZ";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "99089 - DUKE &  HARBOUR";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "99094 - OCHO RIOS";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "99095 - CROSS ROADS";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00098 - GLOUCESTER AVENUE";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00099 - BARBADOS AVENUE";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));

                break;
            //Asignación colección de códigos cuando el bancoId es 121
            case '121':
                //JN Bank
                array_push($arraybanco["interbankCodes"], array(
                    "value" => 'JNBSJMKN',
                    "name" => 'JNBSJMKN'
                ));




                $valueBr = "00001 - HEAD OFFICE";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00002 - INTERNAL PROCESSING CENTRE";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00023 - FINANCE";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00036 - CENTRALISED OPERATIONS";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00051 - HALF WAY TREE";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00052 - NEW KINGSTON";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00053 - DUKE STREET";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00055 - PAPINE";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00056 - SPANISH TOWN ROAD(TIVOLI)";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00057 - BARBICAN";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00058 - WHITEHOUSE";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00059 - KNUTSFORD BLVD";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00060 - OLD HARBOUR";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00092 - HIGHGATE";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00093 - HWT TRANSPORT CENTRE";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00094 - UWI";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00095 - SOVEREIGN (WASHINGTON BLVD)";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00120 - PORTMORE PINES";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00121 - SPANISH TOWN";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00122 - LINSTEAD";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00148 - PREMIER";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00191 - MAY PEN";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00261 - MANDEVILLE";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00262 - CHRISTIANA";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00331 - SANTA CRUZ";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00332 - JUNCTION";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00401 - SAVANNA-LA-MAR";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00450 - FALMOUTH";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00471 - LUCEA";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00541 - MONTEGO BAY";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00681 - BROWNS TOWN";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00682 - ST ANN'S BAY";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00683 - OCHO RIOS";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00751 - PORT MARIA";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00752 - ANNOTTO BAY";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00753 - GAYLE";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00821 - PORT ANTONIO";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00891 - MORANT BAY";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));

                break;
            //Asignación colección de códigos cuando el bancoId es 124
            case '124':
                //First Caribbean International Bank (CIBC)
                array_push($arraybanco["interbankCodes"], array(
                    "value" => 'FCIBJMKN',
                    "name" => 'FCIBJMKN'
                ));


                $valueBr = "00000 - FIRSTCARIBBEAN INTERNATIONAL B";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "09004 - WEALTH MANAGEMENT";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "09076 - MANOR PARK";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "09156 - KING STREET";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "09176 - NEWPORT WEST";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "07406 - PROCESSING";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "07426 - CORPORATE";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "09495 - MONTEGO BAY";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "27502 - SMALL BUSINESS UNIT";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "09516 - PORT ANTONIO";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "09526 - OCHO RIOS";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "09536 - HALF WAY TREE";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "09546 - FAIRVIEW";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "09596 - MAY PEN";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "09597 - FCIB SANTA CRUZ";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "09656 - TWIN GATES";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "09666 - DUKE & LAWS STREETS";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "09676 - KNUTSFORD BLVD";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "09677 - SAVANAH-LA-MAR";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "09746 - MANDEVILLE";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "09747 - PORTMORE";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "09748 - FIRST CARIBBEAN LIGUANEA";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "09866 - HEAD OFFICE";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));

                break;
            //Asignación colección de códigos cuando el bancoId es 127
            case '127':
                //JMMB Bank
                array_push($arraybanco["interbankCodes"], array(
                    "value" => 'JMJAJMKN',
                    "name" => 'JMJAJMKN'
                ));


                $valueBr = "00002 - HEAD OFFICE";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00003 - KINGSTON";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00004 - MONTEGO BAY";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00024 - OCHO RIOS";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00060 - HAUGHTON";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00061 - PORTMORE";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00064 - MANDEVILLE";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));

                break;
            //Asignación colección de códigos cuando el bancoId es 132
            case '132':
                //Citibank N.A
                array_push($arraybanco["interbankCodes"], array(
                    "value" => 'SAJAJMKN',
                    "name" => 'SAJAJMKN'
                ));


                $valueBr = "00000 - CITIBANK N. A.";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00001 - HEAD OFFICE";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));

                break;
            //Asignación colección de códigos cuando el bancoId es 135
            case '135':
                //Sagicor Bank Jamaica LTD
                array_push($arraybanco["interbankCodes"], array(
                    "value" => 'SAJAJMKN',
                    "name" => 'SAJAJMKN'
                ));


                $valueBr = "00021 - NEW KINGSTON";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00023 - HEAD OFFICE";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00024 - HOPE RD";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00025 - MONTEGO BAY";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00026 - SAVANNA-LA-MAR";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00027 - OCHO RIOS";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));
                $valueBr = "00028 - MANDEVILLE";


                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));

                break;
            //Asignación colección de códigos cuando el bancoId es 138
            case '138':
                //Bank o f Jamaica
                array_push($arraybanco["interbankCodes"], array(
                    "value" => 'BAJAJMKN',
                    "name" => 'BAJAJMKN'
                ));

                $valueBr = "00000 - BOJ DATA CENTRE";

                array_push($arraybanco["branches"], array(
                    "value" => $valueBr,
                    "name" => $valueBr
                ));

                break;
        }

    }

    array_push($BancosData, $arraybanco);


}

//Formateo de respuesta
$response = array();
$response["code"] = 0;
$response["rid"] = $json->rid;
$response["data"] = $BancosData;
