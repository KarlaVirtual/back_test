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
 * command/transfer
 *
 * Transferencia entre productos (Sport y Poker)
 *
 * Este recurso gestiona las transferencias de saldo entre los productos "Sport" y "Poker".
 * Dependiendo de los parámetros recibidos, se debita o acredita el saldo del usuario
 * en uno de los productos y realiza las operaciones correspondientes en el sistema
 * de base de datos y en el servicio externo de proveedores.
 *
 * @param string $fromproduct : Producto desde el cual se realiza la transferencia (Sport o Poker).
 * @param string $to_product : Producto al cual se realiza la transferencia (Sport o Poker).
 * @param float $amount : Monto de la transferencia a realizar.
 *
 * @retrun object $response es un objeto con los siguientes atributos:
 *  - *code* (int): Código de error desde el proveedor.
 *  - *result* (string): Contiene el mensaje de error.
 *  - *data* (array): Contiene el resultado de la consulta.
 *  - Tambien retorna la respuesta directa desde la api JOINSERVICES
 *
 *
 * @throws Exception Si hay errores en la obtención de datos o en la manipulación de objetos.
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* Se extraen parámetros JSON y se crean objetos de usuario. */
$fromproduct = $json->params->from_product;
$to_product = $json->params->to_product;
$amount = $json->params->amount;

$UsuarioMandante = new UsuarioMandante($json->session->usuario);

$Usuario = new Usuario($UsuarioMandante->usuarioMandante);

if ($fromproduct == "Sport" && $to_product == "Poker") {

    /* Código que gestiona una transacción para debitar dinero de un usuario en MySQL. */
    $UsuarioMySqlDAO = new UsuarioMySqlDAO();

    $Transaction = $UsuarioMySqlDAO->getTransaction();

    $Usuario->debit($amount, $Transaction);

    $Transaction->commit();


    /* Crea un objeto de servicio, obtiene un saldo y lo convierte a XML. */
    $JOINSERVICES = new JOINSERVICES();


    $response = $JOINSERVICES->getBalance2($UsuarioMandante->getUsumandanteId());

    $saldoXML = new SimpleXMLElement($response);


    /* Verifica si el resultado es exitoso y realiza un depósito para el usuario. */
    if ($saldoXML->RESPONSE->RESULT != "KO") {
        $saldo = $saldoXML->RESPONSE->BALANCE->__toString();

    }

    $response = $JOINSERVICES->depositUser($UsuarioMandante->getUsumandanteId(), $amount);


    /* Se crea un objeto SimpleXMLElement a partir de la variable $response. */
    $insertXML = new SimpleXMLElement($response);

    if ($insertXML->RESPONSE->RESULT != "KO") {


        /* Código que actualiza el saldo de un usuario en una base de datos. */
        $Proveedor = new Proveedor("", "JOINPOKER");

        try {
            $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $UsuarioMandante->getUsumandanteId());
            $UsuarioToken->saldo = $saldo + $amount;
            $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

            $Transaction = $UsuarioTokenMySqlDAO->getTransaction();
            $UsuarioTokenMySqlDAO->update($UsuarioToken);
            $Transaction->commit();


        } catch (Exception $e) {


            /* Crea un token de usuario y lo guarda en la base de datos si el código es 21. */
            if ($e->getCode() == 21) {

                $UsuarioToken = new UsuarioToken();
                $UsuarioToken->setProveedorId($Proveedor->getProveedorId());
                $UsuarioToken->setCookie('0');
                $UsuarioToken->setRequestId('0');
                $UsuarioToken->setUsucreaId(0);
                $UsuarioToken->setUsumodifId(0);
                $UsuarioToken->setUsuarioId($UsuarioTokenSite->getUsuarioId());
                $UsuarioToken->setToken($UsuarioToken->createToken());
                $UsuarioToken->saldo = $amount;

                $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

                $UsuarioTokenMySqlDAO->insert($UsuarioToken);

                $UsuarioTokenMySqlDAO->getTransaction()->commit();


            } else {
                /* lanza una excepción si se produce un error en el bloque anterior. */

                throw $e;
            }
        }

    }


    /* Crea un arreglo de respuesta estándar con código, resultado y datos vacíos. */
    $response = array(
        "code" => 0,
        "data" => array(
            "result" => 0,
            "result_text" => null,
            "data" => array(),
        ),
    );

}

if ($fromproduct == "Poker" && $to_product == "Sport") {

    /* gestiona una transacción para acreditar una cantidad a un usuario en MySQL. */
    $UsuarioMySqlDAO = new UsuarioMySqlDAO();

    $Transaction = $UsuarioMySqlDAO->getTransaction();

    $Usuario->creditWin($amount, $Transaction);

    $Transaction->commit();


    /* obtiene el saldo de un usuario en formato XML y lo procesa. */
    $JOINSERVICES = new JOINSERVICES();

    $response = $JOINSERVICES->getBalance2($UsuarioMandante->getUsumandanteId());

    $saldoXML = new SimpleXMLElement($response);

    if ($saldoXML->RESPONSE->RESULT != "KO") {
        $saldo = $saldoXML->RESPONSE->BALANCE->__toString();

    }


    /* Se retira dinero del usuario y se convierte la respuesta en XML. */
    $response = $JOINSERVICES->withdrawUser($UsuarioMandante->getUsumandanteId(), $amount);

    $insertXML = new SimpleXMLElement($response);

    if ($insertXML->RESPONSE->RESULT != "KO") {


        /* gestiona un usuario y actualiza su saldo en una transacción. */
        $Proveedor = new Proveedor("", "JOINPOKER");

        try {
            $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $UsuarioMandante->getUsumandanteId());
            $UsuarioToken->saldo = $saldo - $amount;
            $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

            $Transaction = $UsuarioTokenMySqlDAO->getTransaction();
            $UsuarioTokenMySqlDAO->update($UsuarioToken);
            $Transaction->commit();


        } catch (Exception $e) {


            /* Crea y almacena un nuevo token de usuario en la base de datos. */
            if ($e->getCode() == 21) {

                $UsuarioToken = new UsuarioToken();
                $UsuarioToken->setProveedorId($Proveedor->getProveedorId());
                $UsuarioToken->setCookie('0');
                $UsuarioToken->setRequestId('0');
                $UsuarioToken->setUsucreaId(0);
                $UsuarioToken->setUsumodifId(0);
                $UsuarioToken->setUsuarioId($UsuarioTokenSite->getUsuarioId());
                $UsuarioToken->setToken($UsuarioToken->createToken());
                $UsuarioToken->saldo = $amount;

                $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

                $UsuarioTokenMySqlDAO->insert($UsuarioToken);

                $UsuarioTokenMySqlDAO->getTransaction()->commit();


            } else {
                /* Maneja excepciones lanzando el error si no se cumple una condición previa. */

                throw $e;
            }
        }


    }


    /* Crea un arreglo en PHP para estructurar una respuesta con código y datos. */
    $response = array(
        "code" => 0,
        "data" => array(
            "result" => 0,
            "result_text" => null,
            "data" => array(),
        ),
    );

}
