<?php

use Backend\dto\Banco;
use Backend\dto\BonoDetalle;
use Backend\dto\Categoria;
use Backend\dto\Clasificador;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Consecutivo;
use Backend\dto\ContactoComercial;
use Backend\dto\CuentaCobro;
use Backend\dto\Departamento;
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
use Backend\dto\UsuarioBilletera;
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
use Backend\mysql\ContactoComercialMySqlDAO;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\DocumentoUsuarioMySqlDAO;
use Backend\mysql\PromocionalLogMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\mysql\RegistroMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\UsuarioBancoMySqlDAO;
use Backend\mysql\UsuarioBannerMySqlDAO;
use Backend\mysql\UsuarioBilleteraMySqlDAO;
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
 * command/wallet_config
 *
 * Actualización de billetera del usuario
 *
 * Este código valida el parámetro `setWallet` y actualiza la billetera del usuario.
 * Si el parámetro es 0, se desactiva la billetera, se actualiza el token de usuario y
 * se desasocia la billetera del usuario. Si el parámetro `wallet` es válido,
 * se actualiza la billetera del usuario en la base de datos y se realiza la transacción correspondiente.
 * Además, si la billetera es propia o Quisk, se realizan diferentes validaciones y actualizaciones en la base de datos.
 *
 * @param object $json : Objeto que contiene los parámetros necesarios para la operación
 * @param int $setWallet : Id de la billetera
 * @param int $wallet : Id de la billetera
 *
 *
 * El objeto `$response` es un array con los siguientes atributos:
 *  - *code* (int): Código de respuesta, donde 0 indica éxito.
 *  - *msg* (string): Mensaje de respuesta.
 *
 * @throws Exception Parametro no es entero (50001) - Si el id del wallet no es un número entero
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* Se asigna el usuario mandante y se valida un parámetro numérico. */
$UsuarioMandante = $UsuarioMandanteSite;

$Usuario = new Usuario($UsuarioMandante->usuarioMandante);


//validamos si el parametro setWallet  es entero.
if (is_numeric($json->params->setWallet)) {

    $setWallet = $json->params->setWallet;
}

//Respondemos al frontend para que seleccione la billetera

/* Actualiza información de usuario y billetera si el valor de $setWallet es '0'. */
if ($setWallet == '0') {

    $response = array("code" => 0, "msg" => "OK");

    //Update Usuariotoken cookie proveedor 0
    //$UsuarioToken = new UsuarioToken("", '0',$UsuarioMandante->usumandanteId);
    $UsuarioToken = $UsuarioTokenSite;
    $UsuarioToken->cookie = "-1";
    $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
    $UsuarioTokenMySqlDAO->update($UsuarioToken);
    $UsuarioTokenMySqlDAO->getTransaction()->commit();

    //update billetera_id-> usuario
    $Usuario->billeteraId = $setWallet;
    $UsuarioMySqlDAO = new UsuarioMySqlDAO();
    $UsuarioMySqlDAO->update($Usuario);
    $UsuarioMySqlDAO->getTransaction()->commit();

} else {

    //validamos si el parametro wallet es entero.

    /* Verifica si el parámetro "wallet" es numérico y lanza excepción si no lo es. */
    if (is_numeric($json->params->wallet)) {

        $wallet = $json->params->wallet;
    } else {
        throw new Exception("Parametro no es entero", "50001");
    }
    //instanciamos  el objeto usuario para obtener billeteraId y tokenQuisk


    /* Actualiza la billetera y token de un usuario en una base de datos. */
    $billeteraId = $Usuario->billeteraId;
    $tokenQuisk = $Usuario->tokenQuisk;

    $Usuario->billeteraId = $wallet;
    $UsuarioMySqlDAO = new UsuarioMySqlDAO();
    $UsuarioMySqlDAO->update($Usuario);

    /* Se confirma una transacción, se actualiza la billetera y se inicializa UsuarioToken. */
    $UsuarioMySqlDAO->getTransaction()->commit();

    $_SESSION["billetera"] = $wallet;

    //Update Usuariotoken cookie proveedor 0
    //$UsuarioToken = new UsuarioToken("", '0',$UsuarioMandante->usumandanteId);
    $UsuarioToken = $UsuarioTokenSite;


    /* Actualiza la billetera de un usuario y verifica su propiedad. */
    $UsuarioToken->cookie = $wallet;
    $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();
    $UsuarioTokenMySqlDAO->update($UsuarioToken);
    $UsuarioTokenMySqlDAO->getTransaction()->commit();

    //procesamos para retornar si la billetera es propia o Quisk
    if ($wallet == 0) {

        $response = array("code" => 0, "msg" => "");

    } elseif ($wallet == 1) {

        /* inicializa un arreglo de respuesta y crea un objeto UsuarioBilletera. */
        $response = array("code" => 0, "msg" => "");

        try {
            $UsuarioBilletera = new UsuarioBilletera('', $Usuario->usuarioId, '1');

        } catch (Exception $e) {
            /* Maneja excepciones, verifica token y crea un registro de billetera de usuario. */

            if ($Usuario->tokenQuisk == '') {
                $response = array("code" => 0, "msg" => "configWallet");
            } else {
                $UsuarioBilletera = new UsuarioBilletera();
                $UsuarioBilletera->setBilleteraId(1);
                $UsuarioBilletera->setUsuarioId($UsuarioMandanteSite->usuarioMandante);
                $UsuarioBilletera->setEstado('A');
                $UsuarioBilletera->setUsucreaId($UsuarioMandanteSite->usuarioMandante);
                $UsuarioBilletera->setUsucreaId(0);

                $UsuarioBilleteraMySqlDAO = new UsuarioBilleteraMySqlDAO();
                $UsuarioBilleteraMySqlDAO->insert($UsuarioBilletera);
                $UsuarioBilleteraMySqlDAO->getTransaction()->commit();

            }


        }


    }
}















