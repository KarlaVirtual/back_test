<?php

use Backend\dto\AuditoriaGeneral;
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
use Backend\dto\UsuarioHistorial;
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
use Backend\mysql\AuditoriaGeneralMySqlDAO;
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
use Backend\mysql\UsuarioHistorialMySqlDAO;
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
 * command/withdraw_cancel
 *
 * Rechazar cuenta de cobro
 *
 * Este recurso maneja el rechazo de una cuenta de cobro en el sistema. Realiza validaciones de estado, actualiza la cuenta de cobro,
 * otorga crédito al usuario y registra la acción en el historial. También ejecuta un proceso en segundo plano para actualizar el CRM.
 *
 * @param object $json : Objeto que contiene los parámetros necesarios para el procesamiento de la cuenta de cobro. Debe incluir los siguientes atributos:
 * @param string $params ->id : ID de la cuenta de cobro a rechazar.
 * @param string $session ->usuario : ID del usuario que realiza la acción.
 *
 * El objeto $response es un array con los siguientes atributos:
 *  - *code* (int): Código de error desde el proveedor.
 *  - *result* (string): Contiene el mensaje de error.
 *  - *data* (array): Contiene el resultado de la consulta.
 *
 *
 * @throws Exception Si ocurre un error durante el procesamiento de la cuenta de cobro.
 * @throws Exception  El retiro ya ha sido rechazado.(300031)
 * @throws Exception El retiro está aprobado y pendiente por pagar.(21012)
 * @throws Exception No se encontró la cuenta de cobro o no se pudo actualizar.(21001)
 * @throws Exception Error al encontrar la cuenta de cobro.(21000)
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* Se crea un objeto CuentaCobro si cuenta_id es válido y no está vacío. */
$cuenta_id = $json->params->id;
//$observacion = $json->params->observacion;

if ($cuenta_id != "" && $cuenta_id != null && $cuenta_id != "undefined") {
    $CuentaCobro = new CuentaCobro($cuenta_id);
}

if ($CuentaCobro != null) {


    /* Verifica el estado de un retiro y lanza excepciones según el caso. */
    if ($CuentaCobro->getEstado() == 'E') {
        throw new Exception("El retiro ya ha sido rechazado", "300031");
    }

    if ($CuentaCobro->getEstado() == 'P') {
        throw new Exception("El retiro esta aprobado y pendiente por pagar", "21012");
    }


    /* Verifica estado de CuentaCobro y crea instancias de Usuario y UsuarioMandante. */
    if ($CuentaCobro->getEstado() != "A" && $CuentaCobro->getEstado() != "M") {
        throw new Exception("No se encontro la cuenta de cobro", "21001");
    }

    $Usuario = new Usuario($CuentaCobro->getUsuarioId());

    $UsuarioMandante = new UsuarioMandante($json->session->usuario);


    /* establece el estado de 'CuentaCobro' y asigna IDs por defecto. */
    $CuentaCobro->setEstado('E');

    if ($CuentaCobro->getUsucambioId() == "") {
        $CuentaCobro->setUsucambioId(0);
    }

    if ($CuentaCobro->getUsurechazaId() == "") {
        $CuentaCobro->setUsurechazaId(0);
    }


    /* asigna 0 a UsupagoId si está vacío en CuentaCobro. */
    if ($CuentaCobro->getUsupagoId() == "") {
        $CuentaCobro->setUsupagoId(0);
    }

    if ($CuentaCobro->getUsupagoId() == "") {
        $CuentaCobro->setUsupagoId(0);
    }


    /* verifica si ciertas fechas están vacías en un objeto. */
    if ($CuentaCobro->getFechaCambio() == "") {
        //$CuentaCobro->setFechaCambio($CuentaCobro->getFechaCrea());
    }

    if ($CuentaCobro->getFechaAccion() == "") {
    }

    /* Actualizar fechas de acción y eliminación en una cuenta de cobro activa o modificada. */
    $CuentaCobro->setFechaAccion(date('Y-m-d H:i:s'));
    $CuentaCobro->setFechaEliminacion(date('Y-m-d H:i:s'));


    $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();

    $rowsUpdate = $CuentaCobroMySqlDAO->update($CuentaCobro, " AND (estado='A' OR  estado='M')");


    /* Lanza una excepción si no se encuentra la cuenta de cobro antes de continuar. */
    if ($rowsUpdate <= 0) {
        throw new Exception("No se encontro la cuenta de cobro", "21001");
    }


    $Usuario->creditWin($CuentaCobro->getValor(), $CuentaCobroMySqlDAO->getTransaction());


    /* Se crea un historial de usuario para una cuenta de cobro específica. */
    $UsuarioHistorial = new UsuarioHistorial();
    $UsuarioHistorial->setUsuarioId($CuentaCobro->usuarioId);
    $UsuarioHistorial->setDescripcion('');
    $UsuarioHistorial->setMovimiento('E');
    $UsuarioHistorial->setUsucreaId(0);
    $UsuarioHistorial->setUsumodifId(0);

    /* Se establece un historial de usuario y se inserta en la base de datos. */
    $UsuarioHistorial->setTipo(40);
    $UsuarioHistorial->setValor($CuentaCobro->valor);
    $UsuarioHistorial->setExternoId($CuentaCobro->getCuentaId());

    $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($CuentaCobroMySqlDAO->getTransaction());
    $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);


    /* confirma una transacción en una base de datos MySQL. */
    $CuentaCobroMySqlDAO->getTransaction()->commit();

    if(false){
        try {
            // Registro de auditoría cuando la solicitud de retiro es de tipo Criptomoneda
            $UsuarioBanco = new UsuarioBanco($CuentaCobro->getMediopagoId());
            if ($UsuarioBanco->getTipoCuenta() == 'Crypto') {

                $informacion = [
                    "usuario" => $Usuario->usuarioId,
                    "retiro_anulado_id" => $CuentaCobro->getCuentaId(),
                    "fecha_hora" => date('Y-m-d H:i:s')
                ];

                $AuditoriaGeneral = new AuditoriaGeneral();
                $AuditoriaGeneral->setUsuarioId($Usuario->usuarioId);
                $AuditoriaGeneral->setUsuariosolicitaId($Usuario->usuarioId);
                $AuditoriaGeneral->setUsuarioaprobarId($Usuario->usuarioId);

                /* Código que configura valores para una auditoría de registro de API de riesgo. */
                $AuditoriaGeneral->setUsuarioaprobarIp(0);
                $AuditoriaGeneral->setTipo("ANULACION NOTA RETIRO CRIPTO");
                $AuditoriaGeneral->setValorAntes(0);
                $AuditoriaGeneral->setUsucreaId($Usuario->usuarioId);
                $AuditoriaGeneral->setUsumodifId(0);

                $AuditoriaGeneral->setEstado("A");
                $AuditoriaGeneral->setObservacion(json_encode($informacion));

                /* Código que configura datos de auditoría y realiza una inserción en la base de datos. */
                $AuditoriaGeneral->setData("");
                $AuditoriaGeneral->setCampo("");

                $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO();
                $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);
                $AuditoriaGeneralMySqlDAO->getTransaction()->commit();
            }
        } catch (\Throwable $th) {
        }
    }


    try {

        /* obtiene el user agent y codifica información del servidor en base64. */
        $useragent = $_SERVER['HTTP_USER_AGENT'];
        $jsonServer = json_encode($_SERVER);
        $serverCodif = base64_encode($jsonServer);


        $ismobile = '';


        /* detecta dispositivos móviles mediante expresiones regulares en el User Agent. */
        if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {

            $ismobile = '1';

        }
//Detect special conditions devices
        $iPod = stripos($_SERVER['HTTP_USER_AGENT'], "iPod");

        /* Detecta si el usuario utiliza un dispositivo móvil en el servidor web. */
        $iPhone = stripos($_SERVER['HTTP_USER_AGENT'], "iPhone");
        $iPad = stripos($_SERVER['HTTP_USER_AGENT'], "iPad");
        $Android = stripos($_SERVER['HTTP_USER_AGENT'], "Android");
        $webOS = stripos($_SERVER['HTTP_USER_AGENT'], "webOS");


//do something with this information
        if ($iPod || $iPhone) {
            $ismobile = '1';
        } else if ($iPad) {
            /* verifica si es un iPad y establece la variable $ismobile en '1'. */

            $ismobile = '1';
        } else if ($Android) {
            /* verifica si el dispositivo es Android, asignando '1' si es móvil. */

            $ismobile = '1';
        }


        /* Ejecuta un script PHP en segundo plano para agregar información al CRM. */
        exec("php -f " . __DIR__ . "/../../src/integrations/crm/AgregarCrm.php " . $Usuario->usuarioId . " " . "RETIROELIMINADOCRM" . " " . $CuentaCobro->cuentaId . " " . $serverCodif . " " . $ismobile . " > /dev/null &");

    } catch (Exception $e) {
        /* Captura excepciones en PHP sin realizar ninguna acción específica. */


    }


    /*  Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket  */
    //$UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());


    /* $CuentaCobroEliminar = new \Backend\dto\CuentaCobroEliminada();
 $CuentaCobroEliminar->setCuentaId($CuentaCobro->getCuentaId());
 $CuentaCobroEliminar->setValor($CuentaCobro->getValor());
 $CuentaCobroEliminar->setMandante($CuentaCobro->getMandante());
 $CuentaCobroEliminar->setObserv($observacion);
 $CuentaCobroEliminar->setUsuarioId($CuentaCobro->getUsuarioId());
 $CuentaCobroEliminar->setUsucreaId($UsuarioMandante->getUsuarioMandante());
 $CuentaCobroEliminar->setFechaCuenta($CuentaCobro->getFechaCrea());
 $CuentaCobroEliminar->setFechaCrea(date('Y-m-d H:i:s'));

 $CuentaCobroEliminadaMySqlDAO = new \Backend\mysql\CuentaCobroEliminadaMySqlDAO();

 $CuentaCobroEliminadaMySqlDAO->insert($CuentaCobroEliminar);

 $Usuario->creditWin($CuentaCobro->getValor(), $CuentaCobroEliminadaMySqlDAO->getTransaction());

 $CuentaCobroMySqlDAO = new \Backend\mysql\CuentaCobroMySqlDAO($CuentaCobroEliminadaMySqlDAO->getTransaction());

 $CuentaCobroMySqlDAO->delete($CuentaCobro->getCuentaId());

 $CuentaCobroEliminadaMySqlDAO->getTransaction()->commit();
*/


    /* crea una respuesta JSON con un código y un identificador. */
    $response = array();
    $response["code"] = 0;
    $response["rid"] = $json->rid;
    $response["data"] = array();

} else {
    /* Lanza una excepción si no se encuentra la cuenta de cobro especificada. */

    throw new Exception("No se encontro la cuenta de cobro", "21000");
}

