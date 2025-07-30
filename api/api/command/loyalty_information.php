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
use Backend\dto\LealtadHistorial;
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
 * Inicializa configuraciones y crea un objeto "Mandante" utilizando parámetros JSON.
 *
 * @param object $json Objeto JSON que contiene los parámetros y la sesión del usuario.
 * @param object $json ->params Objeto que contiene los parámetros de la solicitud.
 * @param int $json ->params->site_id Identificador del sitio.
 * @param string $json ->params->country Código ISO del país.
 * @param object $json ->session Objeto que contiene la sesión del usuario.
 * @param string $json ->session->usuario Identificador del usuario en la sesión.
 *
 *
 * @return array $response Respuesta estructurada con código y datos de lealtad.
 *  -code:int Código de respuesta.
 *  -data:array Arreglo con datos de lealtad.
 *    -loyalty:array Arreglo con datos de lealtad.
 */

/* inicializa configuraciones y crea un objeto "Mandante" utilizando parámetros JSON. */
$ConfigurationEnvironment = new ConfigurationEnvironment();

//$UsuarioMandante = new UsuarioMandante(1);
$site_id = $json->params->site_id;
$paisIso = strtoupper($json->params->country);


$Mandante = new Mandante($site_id);


/* Verifica si el usuario está definido y crea objetos con datos de usuario. */
if ($json->session->usuario != "" && $json->session->usuario != null) {
    $UsuarioMandante = new UsuarioMandante($json->session->usuario);
    $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
    $UsuarioId = $Usuario->usuarioId;
    $PuntosUsuario = $Usuario->puntosLealtad;
    $Pais = new Pais($Usuario->paisId);

} else {
    /* Se crea una nueva instancia de la clase "Pais" con un código ISO específico. */

    $Pais = new Pais("", $paisIso);

}

/* Se crea un objeto y se agregan reglas basadas en el usuario proporcionado. */
$MandanteDetalle = new MandanteDetalle();

$rules = [];
if ($Usuario != "" && $Usuario != null) {

    array_push($rules, array("field" => "mandante_detalle.mandante", "data" => "$Usuario->mandante", "op" => "eq"));
} else {
    /* Se añade una regla al arreglo si la condición no se cumple. */

    array_push($rules, array("field" => "mandante_detalle.mandante", "data" => "$Mandante->mandante", "op" => "eq"));
}

/* Se define un filtro con reglas de comparación para un conjunto de datos. */
array_push($rules, array("field" => "mandante_detalle.estado", "data" => "A", "op" => "eq"));
array_push($rules, array("field" => "mandante_detalle.pais_id", "data" => $Pais->paisId, "op" => "eq"));

//array_push($rules, array("field" => "casificador.tipo", "data" => "LDN", "op" => "eq"));


$filtro = array("rules" => $rules, "groupOp" => "AND");

/* convierte datos a JSON, obtiene detalles y los decodifica en un array. */
$json2 = json_encode($filtro);

$mandanteDetalles = $MandanteDetalle->getMandanteDetallesCustom("mandante_detalle.valor as valor, clasificador.abreviado as abreviado ", "mandante_detalle.manddetalle_id", "asc", '0', '10000', $json2, true);

$mandanteDetalles = json_decode($mandanteDetalles);

$loyalty = array();
foreach ($mandanteDetalles->data as $key => $value) {


    switch ($value->{'clasificador.abreviado'}) {
        case "POINTSLEVELONE":
            /* Asigna el valor de "mandante_detalle.valor" a "ValorNivel1" en puntos nivel uno. */


            $ValorNivel1 = $value->{'mandante_detalle.valor'};
            break;
        case "POINTSLEVELTWO":
            /* asigna un valor específico a una variable bajo una condición. */

            $ValorNivel2 = $value->{'mandante_detalle.valor'};
            break;
        case "POINTSLEVELTHREE":
            /* Asigna el valor de 'mandante_detalle.valor' a $ValorNivel3 en un caso específico. */

            $ValorNivel3 = $value->{'mandante_detalle.valor'};
            break;
        case "POINTSLEVELFOUR":
            /* asigna un valor de "mandante_detalle.valor" a $ValorNivel4. */

            $ValorNivel4 = $value->{'mandante_detalle.valor'};
            break;
        case "POINTSLEVELFIVE":
            /* Extrae el valor de "mandante_detalle.valor" para el caso "POINTSLEVELFIVE". */

            $ValorNivel5 = $value->{'mandante_detalle.valor'};
            break;
        case "POINTSLEVELSIX":
            /* asigna un valor a la variable $ValorNivel6 desde un objeto específico. */

            $ValorNivel6 = $value->{'mandante_detalle.valor'};
            break;
        case "POINTSLEVELSEVEN":
            /* Asigna el valor de 'mandante_detalle.valor' a $ValorNivel7 para un caso específico. */

            $ValorNivel7 = $value->{'mandante_detalle.valor'};
            break;
        case "POINTSLEVELEIGHT":
            /* Asignación del valor de nivel 8 desde un objeto específico en un caso. */

            $ValorNivel8 = $value->{'mandante_detalle.valor'};
            break;
        case "POINTSLEVELNIVE":
            /* Asigna valor a $ValorNivel9 desde 'mandante_detalle.valor' en un caso específico. */

            $ValorNivel9 = $value->{'mandante_detalle.valor'};
            break;
        case "POINTSLEVELTEN":
            /* Asigna el valor de 'mandante_detalle.valor' a $ValorNivel10 en un caso específico. */

            $ValorNivel10 = $value->{'mandante_detalle.valor'};
            break;

        case "LOYALTYDEPOSIT":
            /* Asigna el valor del depósito de lealtad a la variable $ValorDeposito. */

            $ValorDeposito = $value->{'mandante_detalle.valor'};
            break;

        case "LOYALTYBETTINGSPORTSSIMPLE":
            /* Asignación de valor de apuesta simple desde un objeto en un caso específico. */

            $ValorApuestaSimple = $value->{'mandante_detalle.valor'};
            break;
        case "LOYALTYBETCASINO":
            /* Se asigna un valor de apuesta de casino basado en una condición específica. */

            $ValorApuestaCasino = $value->{'mandante_detalle.valor'};
            break;
        case "LOYALTYWITHDRAWAL":
            /* asigna un valor de retiro de una estructura de datos específica. */

            $ValorRetiro = $value->{'mandante_detalle.valor'};
            break;
        case "LOYALTYBASEVALUE":
            /* Se asigna el valor de 'mandante_detalle.valor' a $ValorBase si coincide con "LOYALTYBASEVALUE". */

            $ValorBase = $value->{'mandante_detalle.valor'};
            break;
        case "LOYALTYBETTINGSPORTSCOMBINEDTWO":
            /* asigna un valor basado en una condición específica de apuestas deportivas. */

            $ValorApuestaComb2 = $value->{'mandante_detalle.valor'};
            break;
        case "LOYALTYBETTINGSPORTSCOMBINEDTHREE":
            /* Asigna valor de apuesta a una variable según el caso específico en PHP. */

            $ValorApuestaComb3 = $value->{'mandante_detalle.valor'};
            break;
        case "LOYALTYBETTINGSPORTSCOMBINEDFOUR":
            /* Asignación del valor de la apuesta en un caso específico de un juego. */

            $ValorApuestaComb4 = $value->{'mandante_detalle.valor'};
            break;
        case "LOYALTYBETTINGSPORTSCOMBINEDFIVE":
            /* Se asigna el valor de 'mandante_detalle.valor' a $ValorApuestaComb5. */

            $ValorApuestaComb5 = $value->{'mandante_detalle.valor'};
            break;
        case "LOYALTYBETTINGSPORTSCOMBINEDSIX":
            /* Asignación de valor a una variable según un caso específico en programación. */

            $ValorApuestaComb6 = $value->{'mandante_detalle.valor'};
            break;
        case "LOYALTYBETTINGSPORTSCOMBINEDSEVEN":
            /* Asigna el valor de "mandante_detalle.valor" a $ValorApuestaComb7 para un caso específico. */

            $ValorApuestaComb7 = $value->{'mandante_detalle.valor'};
            break;
        case "LOYALTYBETTINGSPORTSCOMBINEDEIGHT":
            /* asigna un valor a una variable según un caso específico en PHP. */

            $ValorApuestaComb8 = $value->{'mandante_detalle.valor'};
            break;
        case "LOYALTYBETTINGSPORTSCOMBINEDNINE":
            /* asigna un valor de apuesta basado en un tipo específico. */

            $ValorApuestaComb9 = $value->{'mandante_detalle.valor'};
            break;
        case "LOYALTYBETTINGSPORTSCOMBINEDTEN":
            /* Asignación del valor de apuesta en un caso específico de una estructura switch. */

            $ValorApuestaComb10 = $value->{'mandante_detalle.valor'};
            break;
        case "LOYALTYEXPIRATIONDATE":
            /* Asignación de valor a la variable para fecha de expiración de lealtad. */

            $DiasExpiration = $value->{'mandante_detalle.valor'};
            break;
        case "LOYALTYBETCASVIVO":
            /* asigna un valor específico de apuesta a una variable en un caso. */

            $ValorApuestaCasinoEnVivo = $value->{'mandante_detalle.valor'};
            break;
        case "LOYALTYBETVIRTUALES":
            /* Asignación de valor de apuesta virtual en la variable según el caso específico. */

            $ValorApuestaVirtuales = $value->{"mandante_detalle.valor"};
            break;


    }

}


/* inicializa un array y asigna valores enteros a diferentes niveles. */
$array = array();

$array['Level1'] = intval($ValorNivel1);
$array['Level2'] = intval($ValorNivel2);
$array['Level3'] = intval($ValorNivel3);
$array['Level4'] = intval($ValorNivel4);

/* Se convierten variables en enteros y se almacenan en un array asociativo. */
$array['Level5'] = intval($ValorNivel5);
$array['Level6'] = intval($ValorNivel6);
$array['Level7'] = intval($ValorNivel7);
$array['Level8'] = intval($ValorNivel8);
$array['Level9'] = intval($ValorNivel9);
$array['Level10'] = intval($ValorNivel10);


/* formatea valores monetarios concatenando la moneda del país correspondiente. */
$array['Deposit'] = $Pais->moneda . " " . doubleval($ValorDeposito);
$array['Withdrawal'] = $Pais->moneda . " " . doubleval($ValorRetiro);
$array['SimpleBet'] = $Pais->moneda . " " . doubleval($ValorApuestaSimple);
$array['CombinationBet2'] = $Pais->moneda . " " . doubleval($ValorApuestaComb2);
$array['CombinationBet3'] = $Pais->moneda . " " . doubleval($ValorApuestaComb3);
$array['CombinationBet4'] = $Pais->moneda . " " . doubleval($ValorApuestaComb4);

/* Asigna valores de apuestas combinadas a un arreglo con formato de moneda. */
$array['CombinationBet5'] = $Pais->moneda . " " . doubleval($ValorApuestaComb5);
$array['CombinationBet6'] = $Pais->moneda . " " . doubleval($ValorApuestaComb6);
$array['CombinationBet7'] = $Pais->moneda . " " . doubleval($ValorApuestaComb7);
$array['CombinationBet8'] = $Pais->moneda . " " . doubleval($ValorApuestaComb8);
$array['CombinationBet9'] = $Pais->moneda . " " . doubleval($ValorApuestaComb9);
$array['CombinationBet10'] = $Pais->moneda . " " . doubleval($ValorApuestaComb10);

/* Se construye un arreglo con datos de apuestas y se agrega a otro arreglo. */
$array['Casino'] = $Pais->moneda . " " . doubleval($ValorApuestaCasino);
$array['CasinoLive'] = $Pais->moneda . " " . doubleval($ValorApuestaCasinoEnVivo);
$array['Virtuals'] = $Pais->moneda . " " . doubleval($ValorApuestaVirtuales);
$array['ExpirationDays'] = intval($DiasExpiration);
$array['BasePoints'] = intval($ValorBase);

array_push($loyalty, $array);


/* Genera un array de respuesta con código y datos de lealtad. */
$response = array();
$response["code"] = 0;
$response["data"] = array(
    "loyalty" => $loyalty[0]
);




