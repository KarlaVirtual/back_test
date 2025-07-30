<?php

use Backend\dto\Banco;
use Backend\dto\BonoDetalle;
use Backend\dto\Categoria;
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
 * command/save_user_bank
 *
 * Guarda el banco que quiere asociar el usuario a su cuenta
 *
 * @param string $account : cuenta del usuario
 * @param string $user_id : id del usuario
 * @param string $account_type : tipo de cuenta del usuario
 * @param string $bank : Banco asociado a la cuenta
 * @param string $client_type : tipo de cliente
 * @param string $cod_interbank : codigo intenbarcario
 * @param string $conf_account : conf_account
 * @param string $conf_bank : conf_bank
 * @param string $branch : Tipo de cliente
 *
 * El objeto $response es un array con los siguientes atributos:
 *  - *code* (int): Codigo de error desde el proveedor
 *  - *result* (string): Contiene el mensaje de error.
 *  - *data* (array): Contiene el resultado de la consulta
 *
 * Objeto en caso de error:
 *
 * "code" => 12,
 * "result" => "El usuario excede el número de cuentas bancarias registradas permitidas",
 * "data" => array(),
 * );
 *
 * @throws Exception El usuario excede el número de cuentas bancarias registradas permitidas
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */

/* Se asigna un usuario mandante y se inicializan variables de restricciones. */
$UsuarioMandante = $UsuarioMandanteSite;
$Usuario = new Usuario ($UsuarioMandante->getUsuarioMandante());


$tieneRestricciones = 0;
$cumpleRestricciones = false;


/* Se instancia un clasificador y se obtiene un detalle de mandante con restricciones. */
try {
    $Clasificador = new Clasificador("", "MAXACCOUNTSBANK");

    $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');

    $tieneRestricciones = $MandanteDetalle->getValor();


} catch (Exception $e) {
/* Manejo de excepciones que ajusta restricciones según códigos específicos de error. */


    if ($e->getCode() == 34) {
        $tieneRestricciones = 0;
    } else if ($e->getCode() == 41) {
        $tieneRestricciones = 0;
    } else {
        throw $e;
    }
}


if ($tieneRestricciones > 0) {

/* Se crean reglas para filtrar usuarios en una base de datos. */
    $rules = [];
    array_push($rules, array("field" => "usuario_banco.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
    array_push($rules, array("field" => "usuario_banco.estado", "data" => "A", "op" => "eq"));


    $filtro = array("rules" => $rules, "groupOp" => "AND");

/* Se codifica un filtro a JSON y luego se obtiene información de usuario. */
    $json2 = json_encode($filtro);

    $UsuarioBanco = new UsuarioBanco();

    $configuraciones = $UsuarioBanco->getUsuarioBancosCustom(" usuario_banco.* ", "usuario_banco.usubanco_id", "asc", 0, 100, $json2, true);

    $configuraciones = json_decode($configuraciones);

    
/* Verifica si el conteo supera restricciones y establece una variable en consecuencia. */
if (intval($configuraciones->count[0]->{'.count'}) >= intval($tieneRestricciones)) {

    } else {
        $cumpleRestricciones = true;
    }
} else {
/* La condición establece que si no se cumplen restricciones, se activa la variable $cumpleRestricciones. */

    $cumpleRestricciones = true;
}

if ($cumpleRestricciones) {

/* Extrae parámetros de un objeto JSON relacionado con una cuenta bancaria y usuario. */
    $account = $json->params->account;
    $user_id = $json->params->user_id;
    $account_type = $json->params->account_type;
    $bank = $json->params->bank;
    $client_type = $json->params->client_type;
    $cod_interbank = $json->params->cod_interbank;

/* asigna tipos de cuenta basados en el ID y el mandante. */
    $conf_account = $json->params->conf_account;
    $conf_bank = $json->params->conf_bank;

    $branch = $json->params->branch;

    if ($UsuarioMandante->mandante == 14) {

        if ($account_type->Id == '0') {
            $account_type = 'CPF';
        } elseif ($account_type->Id == '1') {
            $account_type = 'EMAIL';
        } elseif ($account_type->Id == '2') {
            $account_type = 'PHONE'; //Celular
        } elseif ($account_type->Id == '3') {
            $account_type = 'EVP';
        } else {
            $account_type = 'CPF';
        }
    } elseif ($UsuarioMandante->mandante == 17) {
/* Asignación de tipo de cuenta según el ID del usuario si mandante es 17. */


        if ($account_type->Id == '0') {
            $account_type = 'CPF';
        } elseif ($account_type->Id == '1') {
            $account_type = 'EMAIL';
        } elseif ($account_type->Id == '2') {
            $account_type = 'PHONE'; //Celular
        } elseif ($account_type->Id == '3') {
            $account_type = 'EVP';
        } else {
            $account_type = 'CPF';
        }
    } elseif ($UsuarioMandante->mandante == 0 && $UsuarioMandante->paisId == 33) {
/* Condicional que define el tipo de cuenta según el país y su identificador. */


        if ($account_type->Id == '0') {
            $account_type = 'CPF';
        } elseif ($account_type->Id == '1') {
            $account_type = 'EMAIL';
        } elseif ($account_type->Id == '2') {
            $account_type = 'PHONE'; //Celular
        } elseif ($account_type->Id == '3') {
            $account_type = 'EVP';
        } else {
            $account_type = 'CPF';
        }
    } else {
/* Asignación de tipos de cuenta basada en condiciones específicas de ID y país. */


        if ($account_type->Id == "1") {
            $account_type = 1;
        } elseif ($account_type->Id == "3" && $Usuario->paisId == 46) {
            $account_type = 'Vista';

        } elseif ($account_type->Id == "3" && $Usuario->paisId == 94) {
            $account_type = 'Vista';

        } else {
            $account_type = 0;

        }
    }


    
/* asigna nombres a tipos de cuentas según su valor numérico. */
if ($account_type == '0') {
        $account_type = 'Ahorros';
    }

    if ($account_type == '1') {
        $account_type = 'Corriente';
    }



/* Asigna "PERSONA" a ClienteTipo si su valor original es "0". */
    $ClienteTipo = $client_type->Id;
    if ($ClienteTipo == "0") {
        $ClienteTipo = "PERSONA";
    }

    if ($user_id == $UsuarioMandante->getUsuarioMandante() || $user_id == "") {


/* Creación y configuración de un objeto UsuarioBanco con datos específicos del usuario y cuenta. */
        $UsuarioBanco = new UsuarioBanco();
        $UsuarioBanco->setUsuarioId($UsuarioMandante->getUsuarioMandante());
        $UsuarioBanco->setBancoId($bank->Id);
        $UsuarioBanco->setCuenta($account);
        $UsuarioBanco->setTipoCuenta($account_type);
        $UsuarioBanco->setTipoCliente($ClienteTipo);
        
/* Se configuran propiedades del objeto UsuarioBanco utilizando datos de UsuarioMandante. */
$UsuarioBanco->setUsucreaId($UsuarioMandante->getUsuarioMandante());
        $UsuarioBanco->setUsumodifId($UsuarioMandante->getUsuarioMandante());
        $UsuarioBanco->setEstado('A');
        $UsuarioBanco->setCodigo($cod_interbank);
        $UsuarioBanco->setToken('0');
        $UsuarioBanco->setProductoId('0');

        
/* Verifica condiciones y asigna tipo de cliente en un objeto de usuario. */
if ($UsuarioMandante->mandante == '2' && $branch != '') {
            $UsuarioBanco->setTipoCliente($branch);
        }


        $UsuarioBancoMySqlDAO = new UsuarioBancoMySqlDAO();

        
/* Insertar un usuario en la base de datos y obtener la transacción correspondiente. */
$UsuarioBancoMySqlDAO->insert($UsuarioBanco);
        $UsuarioBancoMySqlDAO->getTransaction()->commit();
    } else {
        
/* Intenta crear una cuenta asociada y maneja excepciones para obtener el usuario. */
try {
            $CuentaAsociada = new CuentaAsociada('', $user_id);
            $UsuarioId = $CuentaAsociada->usuarioId;
        } catch (Exception $e) {
            if ($e->getCode() == 110008) {
                $CuentaAsociada = new CuentaAsociada('', '', $user_id);
                $UsuarioId = $CuentaAsociada->usuarioId;
            }
        }


/* Crea un objeto 'UsuarioBanco' y establece sus propiedades relacionadas a un usuario. */
        $UsuarioBanco = new UsuarioBanco();
        $UsuarioBanco->setUsuarioId($UsuarioId);
        $UsuarioBanco->setBancoId($bank->Id);
        $UsuarioBanco->setCuenta($account);
        $UsuarioBanco->setTipoCuenta($account_type);
        $UsuarioBanco->setTipoCliente($ClienteTipo);
        
/* configura propiedades de un objeto UsuarioBanco usando datos de UsuarioMandante. */
$UsuarioBanco->setUsucreaId($UsuarioMandante->getUsuarioMandante());
        $UsuarioBanco->setUsumodifId($UsuarioMandante->getUsuarioMandante());
        $UsuarioBanco->setEstado('A');
        $UsuarioBanco->setCodigo($cod_interbank);
        $UsuarioBanco->setToken('0');
        $UsuarioBanco->setProductoId('0');

        
/* Verifica condiciones para establecer tipo de cliente y crea un DAO de usuario. */
if ($UsuarioMandante->mandante == '2' && $branch != '') {
            $UsuarioBanco->setTipoCliente($branch);
        }


        $UsuarioBancoMySqlDAO = new UsuarioBancoMySqlDAO();

        
/* Inserta un usuario en la base de datos y obtiene la transacción correspondiente. */
$UsuarioBancoMySqlDAO->insert($UsuarioBanco);
        $UsuarioBancoMySqlDAO->getTransaction()->commit();
    }


/* Define una respuesta inicial con un código 0 y un array de datos vacío. */
    $response = array(
        "code" => 0,
        "data" => array()
    );
} else {
/* Lanza una excepción si el usuario supera el límite de cuentas bancarias permitidas. */

    throw new Exception("El usuario excede el número de cuentas bancarias registradas permitidas", "30006");

    $response = array(
        "code" => 12,
        "result" => "El usuario excede el número de cuentas bancarias registradas permitidas",
        "data" => array(),
    );
}
