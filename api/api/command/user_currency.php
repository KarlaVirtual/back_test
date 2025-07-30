<?php

use Backend\dto\Banco;
use Backend\dto\BonoDetalle;
use Backend\dto\Categoria;
use Backend\dto\CategoriaProducto;
use Backend\dto\Clasificador;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Consecutivo;
use Backend\dto\CuentaAsociada;
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
use Backend\dto\UsuarioInformacion;
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
 *
 * command/user_currency
 *
 * Consulta de créditos y moneda de usuario
 *
 * Este recurso permite obtener la información de créditos y moneda de un usuario y sus cuentas asociadas.
 * Se realizan consultas para recuperar los datos tanto del usuario principal como de sus cuentas asociadas.
 * En caso de que no se encuentre la cuenta asociada, se intenta obtener la información de la cuenta principal.
 *
 * @param string $usuario : Nombre de usuario en la sesión activa.
 *
 * @return object $response es un array con los siguientes atributos:
 *  - *code* (int): Código de éxito o error de la operación.
 *  - *msg* (string): Mensaje de éxito o error de la operación.
 *  - *data* (array): Contiene los resultados de la consulta de créditos y moneda.
 *
 * @throws Exception "110008" Si no se encuentra una cuenta asociada válida para el usuario.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* Se crea un usuario mandante y un registro asociado a ese usuario. */
$UsuarioMandante = new UsuarioMandante($json->session->usuario);

$registro = new Registro('', $UsuarioMandante->usuarioMandante);

$UserId = $registro->usuarioId;

try {


    /* Se crea una cuenta asociada y se define una regla para el usuario. */
    $cuentaAsociada = new CuentaAsociada('', $UserId);

    $idSegundaCuenta = $cuentaAsociada->usuarioId2;

    $rules = [];

    array_push($rules, ["field" => "registro.usuario_id", "data" => $idSegundaCuenta, "op" => "eq"]);


    /* Se crea un filtro y se obtienen registros personalizados en formato JSON. */
    $filtro = ["rules" => $rules, "groupOp" => "AND"];
    $json2 = json_encode($filtro);

    $registro = new Registro();
    $datos = $registro->getRegistroCustom("registro.usuario_id,registro.creditos,registro.creditos_base,usuario.moneda", "registro.registro_id", "desc", 0, 10, $json2, true);

    $datos = json_decode($datos);


    /* transforma datos en un arreglo estructurado con información específica. */
    $final = [];


    foreach ($datos->data as $key => $value) {
        $array = [];
        $array["Id"] = $value->{"registro.usuario_id"};
        $array["Currency"] = $value->{"usuario.moneda"};
        $array["Credits"] = $value->{"registro.creditos_base"};
        $array["WithdrawalCredits"] = $value->{"registro.creditos"};

        $final[] = $array;
    }

// Realiza la segunda consulta

    /* Crea un filtro JSON para validar la igualdad de un ID de usuario. */
    $rules = [];

    array_push($rules, ["field" => "registro.usuario_id", "data" => $UserId, "op" => "eq"]);

    $filtro = ["rules" => $rules, "groupOp" => "AND"];
    $json2 = json_encode($filtro);


    /* Se crea un registro, se obtienen datos en formato JSON y se decodifican. */
    $registro = new Registro();
    $datos = $registro->getRegistroCustom("registro.usuario_id,registro.creditos_base,registro.creditos,usuario.moneda", "registro.registro_id", "desc", 0, 10, $json2, true);

    $datos = json_decode($datos);


    $final2 = [];


    /* recorre datos y crea un nuevo arreglo con información específica. */
    foreach ($datos->data as $key => $value) {
        $array2 = [];
        $array2["Id"] = $value->{"registro.usuario_id"};
        $array2["Currency"] = $value->{"usuario.moneda"};
        $array2["Credits"] = $value->{"registro.creditos_base"};
        $array2["WithdrawalCredits"] = $value->{"registro.creditos"};
        $final[] = $array2; // Agrega los datos de la segunda consulta al mismo arreglo
    }


} catch (\Exception $e) {
    if ($e->getCode() == "110008") {


        /* Se crea una cuenta asociada y se establece una regla con usuario ID. */
        $cuentaAsociada2 = new CuentaAsociada("", "", $UserId);

        $idPrimeraCuenta = $cuentaAsociada2->usuarioId;

        $rules = [];

        array_push($rules, ["field" => "registro.usuario_id", "data" => $idPrimeraCuenta, "op" => "eq"]);


        /* Se crea un filtro y se obtienen registros personalizados desde una base de datos. */
        $filtro = ["rules" => $rules, "groupOp" => "AND"];
        $json2 = json_encode($filtro);

        $registro = new Registro();
        $datos = $registro->getRegistroCustom("registro.usuario_id,registro.creditos_base,registro.creditos,usuario.moneda", "registro.registro_id", "desc", 0, 10, $json2, true);

        $datos = json_decode($datos);


        /* convierte datos en un formato estructurado, almacenándolos en un array final. */
        $final = [];

        foreach ($datos->data as $key => $value) {
            $array = [];
            $array["Id"] = $value->{"registro.usuario_id"};
            $array["Currency"] = $value->{"usuario.moneda"};
            $array["Credits"] = $value->{"registro.creditos_base"};
            $array["WithdrawalCredits"] = $value->{"registro.creditos"};
            $final[] = $array;
        }

        // Realiza la segunda consulta

        /* Crea un filtro JSON para validar condiciones de usuario en un registro. */
        $rules = [];

        array_push($rules, ["field" => "registro.usuario_id", "data" => $UserId, "op" => "eq"]);

        $filtro = ["rules" => $rules, "groupOp" => "AND"];
        $json2 = json_encode($filtro);


        /* obtiene registros de usuario y decodifica los datos JSON. */
        $registro = new Registro();
        $datos = $registro->getRegistroCustom("registro.usuario_id,registro.creditos,registro.creditos_base,usuario.moneda", "registro.registro_id", "desc", 0, 10, $json2, true);

        $datos = json_decode($datos);

        $final2 = [];


        /* Recorre datos y organiza información en un nuevo arreglo asociativo. */
        foreach ($datos->data as $key => $value) {
            $array2 = [];
            $array2["Id"] = $value->{"registro.usuario_id"};
            $array2["Currency"] = $value->{"usuario.moneda"};
            $array2["Credits"] = $value->{"registro.creditos_base"};
            $array2["WithdrawalCredits"] = $value->{"registro.creditos"};
            $final[] = $array2; // Agrega los datos de la segunda consulta al mismo arreglo
        }

    }
}


/* prepara una respuesta con estado, mensaje y datos finales. */
$response = [];
$response['code'] = 0;
$response['msg'] = 'Success';
$response["data"] = $final;


// $rules = [];

// array_push($rules,array("field"=>"cuenta_asociada.usuario_id","data"=>"","op"=>"eq"));


// $filtro = array("rules" => $rules, "groupOp" => "AND");
// $json2 = json_encode($filtro);


?>