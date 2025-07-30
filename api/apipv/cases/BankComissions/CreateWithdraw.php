<?php

use Backend\dto\Clasificador;
use Backend\dto\Consecutivo;
use Backend\dto\CuentaCobro;
use Backend\dto\MandanteDetalle;
use Backend\dto\Usuario;
use Backend\dto\UsuarioBanco;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioToken;
use Backend\dto\UsuarioRecarga;
use Backend\dto\UsuarioPerfil;
use Backend\dto\UsuarioHistorial;
use Backend\dto\PuntoVenta;
use Backend\dto\Plantilla;
use Backend\dto\Mandante;
use Backend\dto\CupoLog;
use Backend\dto\Concesionario;
use Backend\mysql\ConsecutivoMySqlDAO;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\UsuarioRecargaMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\mysql\CupoLogMySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\UsuarioInformacion;
use Backend\mysql\UsuarioInformacionMySqlDAO;

/**
 * Procesa la creación de un retiro basándose en los datos proporcionados en la solicitud.
 *
 * @param string $params JSON codificado que contiene los datos de la solicitud, incluyendo:
 * @param int $params->UserId Identificador del usuario que realiza el retiro.
 * @param int|null $params->AccountBank Identificador de la cuenta bancaria asociada.
 * @param float $params->Value Valor del retiro solicitado.
 * @param string $params->WithdrawType Tipo de retiro ('1' para retiro normal, '0' para transferencia).
 * @param float $params->ConfirmValue Valor confirmado del retiro.
 * @param int|null $params->BankId Identificador del banco asociado.
 * @param string $params->Invoice Factura asociada al retiro.
 * @param string $params->IsForRefund Indica si el retiro es un reembolso ('S' o 'N').
 * 
 * 
 * @return array $response Contiene los datos de la respuesta, incluyendo:
 *                         - HasError (bool): Indica si ocurrió un error.
 *                         - AlertType (string): Tipo de alerta ('success' en caso de éxito).
 *                         - AlertMessage (string): Mensaje de alerta.
 *                         - ModelErrors (array): Lista de errores del modelo.
 *                         - Data (array): Datos adicionales de la respuesta.
 *
 * @throws Exception Si el saldo del usuario es insuficiente.
 *                   Código: '58', Mensaje: 'Insufficient balance'.
 * @throws Exception Si la cuenta bancaria no existe o no pertenece al usuario.
 *                   Código: '67', Mensaje: 'No existe Cuenta Bancaria'.
 * @throws Exception Si el monto del retiro es menor al mínimo permitido.
 *                   Código: '54', Mensaje: 'MINIMO MONTO PARA RETIROS'.
 * @throws Exception Si el monto del retiro excede el máximo permitido.
 *                   Código: '55', Mensaje: 'MAXIMO MONTO PARA RETIROS'.
 * @throws Exception Si no tiene saldo suficiente para transferir.
 *                   Código: '111', Mensaje: 'No tiene saldo para transferir'.
 * @throws Exception Si no se puede transferir a un usuario específico.
 *                   Código: '111', Mensaje: 'No puedo transferir a ese usuario'.
 *
 */
//error_reporting(E_ALL);
//ini_set('display_errors', 'ON');


/* obtiene y decodifica datos JSON enviados a través de una solicitud HTTP. */

$params = file_get_contents('php://input');
$params = json_decode($params);


$ConfigurationEnvironment = new ConfigurationEnvironment();

$UserId = $params->UserId;

/* Asignación de parámetros de un objeto a variables para procesar una transacción bancaria. */
$AccountBank = $params->AccountBank;
$Value = $params->Value;
$WithdrawType = $params->WithdrawType;
$ConfirmValue = $params->ConfirmValue;
$BankId = $params->BankId;
$valorFinal = $Value;

/* Se asignan valores de parámetros para manejar una factura y si es reembolso. */
$Invoice = $params->Invoice;
$IsForRefund = $params->IsForRefund;


$valorImpuesto = 0;
$valorPenalidad = 0;

/* Asigna valores y determina el tipo de reembolso y una subcadena de la factura. */
$creditos = $Value;
$tipo = "S";

$IsForRefund = ($IsForRefund == "S") ? "S" : "N";

$extension = substr($Invoice2, 17, 3);

// if($extension != "pdf"){
//    throw new Exception("archivo no valido", 35);
// }
//$UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);
//$ClientId = $UsuarioMandante->getUsuarioMandante();

//$UsuarioMandante = new UsuarioMandante($_SESSION["usuario2"]);
//$Usuario = new Usuario ($UsuarioMandante->getUsuarioMandante());
//$ClientId=$Usuario->usuarioId;

if ($WithdrawType == '1') {


    /* Código que verifica créditos de un usuario y lanza una excepción si son insuficientes. */
    $UsuarioMandante = new UsuarioMandante('', $_SESSION['usuario'], $_SESSION['mandante']);
    // cambiar el 0 por $session['mandante']

    $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
    $ClientId = $Usuario->usuarioId;

    if ($creditos > 0) {
        if ($Usuario->creditosAfiliacion < $creditos) {

            throw new Exception("Insufficient balance", "58");
        }
    } else {
        /* Lanza una excepción si el saldo es insuficiente, indicando un error específico. */


        throw new Exception("Insufficient balance", "58");
    }


    /* Valida si hay cuenta bancaria y verifica la coincidencia de usuario, lanzando excepción. */
    if ($AccountBank != "" || $AccountBank != null) {

        $UsuarioBanco = new UsuarioBanco($AccountBank);

        if ($UsuarioBanco->usuarioId != $Usuario->usuarioId) {
            throw new Exception("No existe Cuenta Bancaria", "67");
        }
    } else {
        /* asigna 0 a $AccountBank si no se cumple la condición anterior. */

        $AccountBank = 0;
    }


    /* inicia variables y prepara la verificación del mínimo retiro en un banco. */
    $Amount = $Value;
    $service = "UserBank";
    $id = $AccountBank;
    $balance = 0;


    //Verificamos limite de minimo retiro
    $Clasificador = new Clasificador("", "MINWITHDRAW");


    /* Se inicializa un monto mínimo y se obtiene un valor de MandanteDetalle. */
    $minimoMontoPremios = 0;

    try {
        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
        $minimoMontoPremios = $MandanteDetalle->getValor();
    } catch (Exception $e) {
        /* Bloque para manejar excepciones en PHP, atrapando errores sin realizar acciones específicas. */
    }


    /* verifica si el monto es menor al mínimo permitido y lanza excepción. */
    if ($Amount < $minimoMontoPremios) {
        throw new Exception("MINIMO MONTO PARA RETIROS" . $Amount . "-" . $minimoMontoPremios, "54");
    }

    //Verificamos limite de maximo retiro
    $Clasificador = new Clasificador("", "MAXWITHDRAW");

    /* Inicializa una variable y obtiene un valor de un objeto en un bloque try. */
    $maximooMontoPremios = -1;

    try {
        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
        $maximooMontoPremios = $MandanteDetalle->getValor();
    } catch (Exception $e) {
        /* Captura excepciones en PHP y evita que se interrumpa la ejecución del script. */
    }


    /* Controla el monto de premios permitidos para el usuario mandante con ID 8. */
    if ($Usuario->mandante == 8) {
        $maximooMontoPremios = 5000;

        if ($Amount > $maximooMontoPremios && $maximooMontoPremios != -1) {
            throw new Exception("MAXIMO MONTO PARA RETIROS" . $Amount . "-" . $minimoMontoPremios, "55");
        }
    }


    //Verificamos impuesto retiro

    //Si es de Saldo Premios
    if ($creditos > 0) {


        /* intenta obtener un valor impositivo mediante instancias de clases. */
        $impuesto = -1;
        try {
            $Clasificador = new Clasificador("", "TAXWITHDRAWAWARD");

            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
            $impuesto = $MandanteDetalle->getValor();
        } catch (Exception $e) {
            /* Manejo de excepciones en PHP, capturando errores sin realizar acciones específicas. */
        }

        /* Calcula el valor del impuesto si es mayor a cero y cumple condiciones específicas. */
        if ($impuesto > 0) {
            $impuestoDesde = -1;
            try {
                $Clasificador = new Clasificador("", "TAXWITHDRAWAWARDFROM");
                $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
                $impuestoDesde = $MandanteDetalle->getValor();
            } catch (Exception $e) {
            }

            if ($impuestoDesde != -1) {
                if ($Amount >= $impuestoDesde) {
                    $valorImpuesto = ($impuesto / 100) * $valorFinal;
                    //$valorFinal = $valorFinal - $valorImpuesto;
                }
            }
        }
    }


    /* incrementa el número de un objeto "Consecutivo" y prepara una base de datos. */
    $Consecutivo = new Consecutivo("", "RET", "");

    $consecutivo_recarga = $Consecutivo->numero;

    $consecutivo_recarga++;

    $ConsecutivoMySqlDAO = new ConsecutivoMySqlDAO();


    /* Actualiza el número de un objeto Consecutivo y confirma la transacción en MySQL. */
    $Consecutivo->setNumero($consecutivo_recarga);

    $ConsecutivoMySqlDAO->update($Consecutivo);

    $ConsecutivoMySqlDAO->getTransaction()->commit();

    $CuentaCobro = new CuentaCobro();


    //$CuentaCobro->cuentaId = $consecutivo_recarga;

    /* Establece propiedades de un objeto 'CuentaCobro' relacionados con un pago. */
    $CuentaCobro->usuarioId = $ClientId;
    $CuentaCobro->valor = $valorFinal;
    $CuentaCobro->fechaPago = '';
    $CuentaCobro->fechaCrea = date('Y-m-d H:i:s');
    $CuentaCobro->usucambioId = 0;
    $CuentaCobro->usurechazaId = 0;

    /* Actualiza información de usuario en una cuenta de cobro y guarda en base de datos. */
    $CuentaCobro->usupagoId = 0;
    $CuentaCobro->fechaCambio = date('Y-m-d H:i:s');
    $CuentaCobro->fechaAccion = date('Y-m-d H:i:s');
    $CuentaCobro->setFactura($Invoice);

    try {

        $Clasificador = new Clasificador('', 'REMBOLS');
        $ClasificadorId = $Clasificador->getClasificadorId();

        $UsuarioInformacion = new UsuarioInformacion('', $ClasificadorId, $ClientId, "", 0);

        $UsuarioInformacion->setClasificadorId($ClasificadorId);
        $UsuarioInformacion->setUsuarioId($ClientId);
        $UsuarioInformacion->setValor($IsForRefund);
        $UsuarioInformacion->setUsuCreaId($ClientId);
        $UsuarioInformacion->setUsuModif($_SESSION["usuario"]);
        $UsuarioInformacion->setMandante($Usuario->mandante);

        $UsuarioInformacionMySqlDAO = new UsuarioInformacionMySqlDAO();
        $Transaction = $UsuarioInformacionMySqlDAO->getTransaction();
        $UsuarioInformacionMySqlDAO->update($UsuarioInformacion);
        $UsuarioInformacionMySqlDAO->getTransaction()->commit();
    } catch (\Exception $e) {
        /* Captura excepciones y maneja un caso específico para insertar información del usuario. */

        if ($e->getCode() == "115") {

            $Clasificador = new Clasificador('', 'REMBOLS');
            $ClasificadorId2 = $Clasificador->getClasificadorId();

            $UsuarioInformacion = new UsuarioInformacion();
            $UsuarioInformacion->setClasificadorId($ClasificadorId2);
            $UsuarioInformacion->setUsuarioId($ClientId);
            $UsuarioInformacion->setValor($IsForRefund);
            $UsuarioInformacion->setusucreaId($ClientId);
            $UsuarioInformacion->setUsuModif($_SESSION['usuario']);
            $UsuarioInformacion->setMandante($Usuario->mandante);

            $UsuarioInformacionMySqlDAO = new UsuarioInformacionMySqlDAO();
            $UsuarioInformacionMySqlDAO->insert($UsuarioInformacion);
            $UsuarioInformacionMySqlDAO->getTransaction()->commit();
        }
    }


    /* Establece estado, genera clave y asigna mandante a CuentaCobro. */
    $CuentaCobro->estado = 'A';

    $clave = $ConfigurationEnvironment->GenerarClaveTicket2(5);

    $CuentaCobro->clave = "aes_encrypt('$clave','CLAVE_ENCRYPT_RETIRO')";

    $CuentaCobro->mandante = $_SESSION['mandante'];

    /* Asignación de valores a propiedades de un objeto CuentaCobro en PHP. */
    $CuentaCobro->dirIp = '';
    $CuentaCobro->impresa = 'S';
    $CuentaCobro->mediopagoId = $AccountBank;
    $CuentaCobro->puntoventaId = $ClientId;
    $CuentaCobro->costo = $valorPenalidad;
    $CuentaCobro->impuesto = $valorImpuesto;

    /* Se asignan valores a propiedades del objeto CuentaCobro y se define un método. */
    $CuentaCobro->creditos = $creditos;
    $CuentaCobro->creditosBase = 0;
    $CuentaCobro->transproductoId = 0;
    $CuentaCobro->impuesto2 = 0;
    $CuentaCobro->version = '3';

    $method = "0";

    /* Se inserta un registro de pago en la base de datos usando DAO. */
    $status_message = "";

    $CuentaCobro->mediopagoId = $id;

    $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();

    $CuentaCobroMySqlDAO->insert($CuentaCobro);


    /* Actualiza el balance de créditos del usuario y maneja falta de fondos. */
    if ($creditos > 0) {

        $UsuarioMySqlDAO = new UsuarioMySqlDAO($CuentaCobroMySqlDAO->getTransaction());

        $result = $UsuarioMySqlDAO->updateBalanceCreditosAfiliacion($ClientId, $creditos, true);

        if ($result === false) {

            throw new Exception('Insufficient balance', 58);
        }
    }


    /* Código para confirmar una transacción y preparar respuesta exitosa en PHP. */
    $CuentaCobroMySqlDAO->getTransaction()->commit();

    $respuestafinal = "";

    $response["HasError"] = false;
    $response["AlertType"] = "success";

    /* organiza respuestas en un array con mensajes, errores y datos. */
    $response["AlertMessage"] = $respuestafinal;
    $response["ModelErrors"] = [];
    $response["Data"] = [];
} elseif ($WithdrawType == '0') {


    /* asigna un valor y determina si es entrada o salida, ajustando el signo. */
    $Amount = $Value;

    if ($Amount < 0) {
        $tipo = 'S'; //Tipo salida
        $Amount = -$Amount;
    }


    /* Creación de objetos para manejar usuarios y sus atributos en una aplicación. */
    $UsuarioMandante = new UsuarioMandante("", $_SESSION['usuario'], $_SESSION['mandante']);
    $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
    $ClientId = $Usuario->usuarioId;
    $Note = " ";
    $tipoCupo = 'A'; //Apuesta
    $Type = 1;


    /* Asigna el valor de $AccountBank a $id o lo establece en 0. */
    if ($AccountBank != "" || $AccountBank != null) {
        $id = $AccountBank;
    } else {
        $AccountBank = 0;
        $id = $AccountBank;
    }


    /* Condicional para agregar $BankId a $Note y crear un nuevo objeto CupoLog. */
    if ($BankId != '' && $BankId != '0') {
        $Note = $Note . '_' . $BankId;
    }

    $CupoLog = new CupoLog();
    $CupoLog->setUsuarioId($ClientId);

    /* Código para establecer propiedades de un objeto CupoLog basado en datos de sesión. */
    $CupoLog->setFechaCrea(date('Y-m-d H:i:s'));
    $CupoLog->setTipoId($tipo);
    $CupoLog->setValor($Amount);
    $CupoLog->setUsucreaId($ClientId);
    $CupoLog->setMandante($_SESSION['mandante']);
    $CupoLog->setTipocupoId($tipoCupo);

    /* establece una observación y crea instancias de DAO para transacciones. */
    $CupoLog->setObservacion($Note);


    $CupoLogMySqlDAO = new CupoLogMySqlDAO();
    $Transaction = $CupoLogMySqlDAO->getTransaction();

    $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO($Transaction);


    /* Inserta un registro y verifica permisos de transferencia entre usuarios en la sesión. */
    $CupoLogMySqlDAO->insert($CupoLog);

    $ConcesionarioU = new Concesionario($ClientId, '0');

    if ($_SESSION["win_perfil"] == "CONCESIONARIO") {
        if ($ConcesionarioU->getUsupadreId() != $UsuarioMandante->getUsuarioMandante()) {
            throw new Exception("No puedo transferir a ese usuario", "111");
        }
    } elseif ($_SESSION["win_perfil"] == "CONCESIONARIO2") {
        /* Verifica permisos para transferir entre usuarios de concesionarios específicos. */

        if ($ConcesionarioU->getUsupadre2Id() != $UsuarioMandante->getUsuarioMandante()) {
            throw new Exception("No puedo transferir a ese usuario", "111");
        }
    } elseif ($_SESSION["win_perfil"] == "CONCESIONARIO3") {
        /* Condiciona la transferencia de usuarios según su perfil y validación de IDs. */

        if ($ConcesionarioU->getUsupadre3Id() != $UsuarioMandante->getUsuarioMandante()) {
            throw new Exception("No puedo transferir a ese usuario", "111");
        }
    }


    if ($_SESSION["win_perfil"] == "CONCESIONARIO" or $_SESSION["win_perfil"] == "CONCESIONARIO2" or $_SESSION["win_perfil"] == "CONCESIONARIO3") {


        /* Verifica saldo antes de permitir transferencia en el sistema de puntos de venta. */
        $PuntoVentaSuper = new PuntoVenta("", $UsuarioMandante->getUsuarioMandante());

        if (($PuntoVentaSuper->getCreditosBase() < $Amount && $Type == 1 && $tipo != "S") || ($PuntoVentaSuper->getCupoRecarga() < $Amount && $Type == 0 && $tipo != "S")) {
            throw new Exception("No tiene saldo para transferir", "111");
        }

        $PuntoVenta = new PuntoVenta("", $ClientId);


        /* Controla el balance de recargas o créditos según tipo y cantidad en transacciones. */
        if ($tipo == "S") {
            if ($Type == 0) {
                $cant = $PuntoVenta->setBalanceCupoRecarga(-$Amount, $Transaction);
                $cant2 = $PuntoVentaSuper->setBalanceCupoRecarga($Amount, $Transaction);
            } elseif ($Type == 1) {
                $cant = $PuntoVenta->setBalanceCreditosBase(-$Amount, $Transaction);
                $cant2 = $PuntoVentaSuper->setBalanceCreditosBase($Amount, $Transaction);
            }
        }


        /* Lanza una excepción si las cantidades para transferir son cero. */
        if ($cant == 0) {
            throw new Exception("No tiene saldo para transferir", "111");
        }


        if ($cant2 == 0) {
            throw new Exception("No tiene saldo para transferir", "111");
        }
    } else {
        /* maneja saldo en un sistema de punto de venta y lanza excepciones. */

        $PuntoVenta = new PuntoVenta("", $ClientId);

        if ($tipo == "S") {
            if ($Type == 0) {
                $cant = $PuntoVenta->setBalanceCupoRecarga(-$Amount, $Transaction);
            } else {
                $cant = $PuntoVenta->setBalanceCreditosBase(-$Amount, $Transaction);
            }
        }

        if ($cant == 0) {
            throw new Exception("No tiene saldo para transferir", "111");
        }
    }


    /* Crea un registro de historial de usuario con datos de un log específico. */
    $UsuarioHistorial = new UsuarioHistorial();
    $UsuarioHistorial->setUsuarioId($CupoLog->getUsuarioId());
    $UsuarioHistorial->setDescripcion('');
    $UsuarioHistorial->setMovimiento($CupoLog->getTipoId());
    $UsuarioHistorial->setUsucreaId(0);
    $UsuarioHistorial->setUsumodifId(0);

    /* Crea y guarda un historial de usuario con datos de un registro específico. */
    $UsuarioHistorial->setTipo(60);
    $UsuarioHistorial->setValor($CupoLog->getValor());
    $UsuarioHistorial->setExternoId($CupoLog->getCupologId());

    $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
    $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial, '1');


    /* gestiona el historial de usuario para concesionarios específicos, registrando movimientos. */
    if ($_SESSION["win_perfil"] == "CONCESIONARIO" or $_SESSION["win_perfil"] == "CONCESIONARIO2" or $_SESSION["win_perfil"] == "CONCESIONARIO3") {

        $tipoConce = 'E';

        if ($CupoLog->getTipoId() == "E") {
            $tipoConce = 'S';
        }
        $UsuarioHistorial2 = new UsuarioHistorial();
        $UsuarioHistorial2->setUsuarioId($CupoLog->getUsucreaId());
        $UsuarioHistorial2->setDescripcion('');
        $UsuarioHistorial2->setMovimiento($tipoConce);
        $UsuarioHistorial2->setUsucreaId(0);
        $UsuarioHistorial2->setUsumodifId(0);
        $UsuarioHistorial2->setTipo(60);
        $UsuarioHistorial2->setValor($CupoLog->getValor());
        $UsuarioHistorial2->setExternoId($CupoLog->getCupologId());

        $UsuarioHistorialMySqlDAO2 = new UsuarioHistorialMySqlDAO($Transaction);
        $UsuarioHistorialMySqlDAO2->insert($UsuarioHistorial2, '1');
    }


    /* maneja transacciones y verifica el límite mínimo de retiro para usuarios. */
    $Transaction->commit();

    $Usuario = new Usuario($PuntoVenta->usuarioId);
    $Mandante = new Mandante($Usuario->mandante);


    //Verificamos limite de minimo retiro
    $Clasificador = new Clasificador("", "MINWITHDRAW");


    /* inicializa un monto mínimo y obtiene un valor de MandanteDetalle. */
    $minimoMontoPremios = 0;
    try {
        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
        $minimoMontoPremios = $MandanteDetalle->getValor();
    } catch (Exception $e) {
        /* Manejo de excepciones en PHP, donde se captura un error sin realizar acciones. */
    }


    /* valida montos de retiro y lanza excepción si es menor al mínimo. */
    if ($Amount < $minimoMontoPremios) {
        throw new Exception("MINIMO MONTO PARA RETIROS" . $Amount . "-" . $minimoMontoPremios, "54");
    }

    //Verificamos limite de maximo retiro
    $Clasificador = new Clasificador("", "MAXWITHDRAW");

    /* Se inicializa una variable y se obtiene un valor de un objeto "MandanteDetalle". */
    $maximooMontoPremios = -1;

    try {
        $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
        $maximooMontoPremios = $MandanteDetalle->getValor();
    } catch (Exception $e) {
        /* captura excepciones en PHP sin realizar ninguna acción específica. */
    }


    /* Lanza una excepción si el monto excede el máximo permitido para retiros. */
    if ($Amount > $maximooMontoPremios && $maximooMontoPremios != -1) {
        throw new Exception("MAXIMO MONTO PARA RETIROS" . $Amount . "-" . $minimoMontoPremios, "55");
    }


    //Verificamos impuesto retiro

    //Si es de Saldo Premios
    if ($creditos > 0) {


        /* calcula un impuesto usando una clase y gestiona posibles errores. */
        $impuesto = -1;
        try {
            $Clasificador = new Clasificador("", "TAXWITHDRAWAWARD");

            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
            $impuesto = $MandanteDetalle->getValor();
        } catch (Exception $e) {
            /* Código en PHP para manejar excepciones sin realizar ninguna acción. */
        }

        /* Calcula un impuesto si es mayor a cero y se cumplen ciertas condiciones. */
        if ($impuesto > 0) {
            $impuestoDesde = -1;
            try {
                $Clasificador = new Clasificador("", "TAXWITHDRAWAWARDFROM");
                $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');
                $impuestoDesde = $MandanteDetalle->getValor();
            } catch (Exception $e) {
            }

            if ($impuestoDesde != -1) {
                if ($Amount >= $impuestoDesde) {
                    $valorImpuesto = ($impuesto / 100) * $valorFinal;
                    //$valorFinal = $valorFinal - $valorImpuesto;
                }
            }
        }
    }


    /* crea un objeto de consecutivo y aumenta su número. */
    $Consecutivo = new Consecutivo("", "RET", "");

    $consecutivo_recarga = $Consecutivo->numero;

    $consecutivo_recarga++;

    $ConsecutivoMySqlDAO = new ConsecutivoMySqlDAO();


    /* Actualiza un número de consecutivo y crea una nueva instancia de CuentaCobro. */
    $Consecutivo->setNumero($consecutivo_recarga);

    $ConsecutivoMySqlDAO->update($Consecutivo);

    $ConsecutivoMySqlDAO->getTransaction()->commit();

    $CuentaCobro = new CuentaCobro();


    /* Se asignan valores a las propiedades de un objeto CuentaCobro en PHP. */
    $CuentaCobro->cuentaId = $consecutivo_recarga;
    $CuentaCobro->usuarioId = $ClientId;
    $CuentaCobro->valor = $valorFinal;
    $CuentaCobro->fechaPago = '';
    $CuentaCobro->fechaCrea = date('Y-m-d H:i:s');
    $CuentaCobro->usucambioId = 0;

    /* inicializa propiedades de un objeto 'CuentaCobro' con valores específicos. */
    $CuentaCobro->usurechazaId = 0;
    $CuentaCobro->usupagoId = 0;
    $CuentaCobro->fechaCambio = date('Y-m-d H:i:s');
    $CuentaCobro->fechaAccion = date('Y-m-d H:i:s');

    $CuentaCobro->estado = 'A';


    /* Genera una clave de ticket y la almacena cifrada en la cuenta de cobro. */
    $clave = $ConfigurationEnvironment->GenerarClaveTicket2(5);

    $CuentaCobro->clave = "aes_encrypt('$clave','CLAVE_ENCRYPT_RETIRO')";

    $CuentaCobro->mandante = $_SESSION['mandante'];
    $CuentaCobro->dirIp = '';

    /* asigna valores a propiedades de un objeto CuentaCobro. */
    $CuentaCobro->impresa = 'S';
    $CuentaCobro->mediopagoId = 0;
    $CuentaCobro->puntoventaId = 0;
    $CuentaCobro->costo = $valorPenalidad;
    $CuentaCobro->impuesto = $valorImpuesto;
    $CuentaCobro->creditos = $creditos;

    /* Inicializa propiedades de $CuentaCobro y establece una factura desde $Invoice. */
    $CuentaCobro->creditosBase = 0;
    $CuentaCobro->transproductoId = 0;
    $CuentaCobro->impuesto2 = 0;
    $CuentaCobro->version = '4';
    $CuentaCobro->setFactura($Invoice);


    $method = "0";

    /* Se asigna un ID de medio de pago y se inserta en la base de datos. */
    $status_message = "";

    $CuentaCobro->mediopagoId = $id;

    $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();

    $CuentaCobroMySqlDAO->insert($CuentaCobro);


    /* finaliza una transacción y establece que no hubo errores en la respuesta. */
    $CuentaCobroMySqlDAO->getTransaction()->commit();


    $respuestafinal = "";

    $response["HasError"] = false;

    /* Se configura una respuesta exitosa con mensajes y datos vacíos. */
    $response["AlertType"] = "success";
    $response["AlertMessage"] = $respuestafinal;
    $response["ModelErrors"] = [];
    $response["Data"] = [];
}
