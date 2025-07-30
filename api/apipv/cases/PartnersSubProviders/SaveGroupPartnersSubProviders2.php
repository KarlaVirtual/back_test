<?php

use Backend\sql\SqlQuery;
use Backend\cms\CMSProveedor;
use Backend\sql\QueryExecutor;
use Backend\dto\SubproveedorMandantePais;
use Backend\dto\Producto;
use Backend\dto\Subproveedor;
use Backend\dto\AuditoriaGeneral;
use Backend\mysql\AuditoriaGeneralMySqlDAO;
use Backend\mysql\ProductoMySqlDAO;
use Backend\mysql\SubproveedorMySqlDAO;
use Backend\mysql\SubproveedorMandantePaisMySqlDAO;

/**
 * PartnersSubProviders/SaveGroupPartnersSubProviders2
 *
 * Gestión de Subproveedores 2
 *
 * Este recurso permite la activación y desactivación de subproveedores asociados a un `Partner`.
 * Se registran cambios en la base de datos y se mantiene un registro de auditoría detallado.
 * Si se realizan modificaciones, la información también se actualiza en la base de datos de casinos (`CMSProveedor`).
 *
 *
 * @param object $params : Objeto con los parámetros de entrada.
 *     - *Partner* (string): Identificador del partner.
 *     - *CountrySelect* (string): País seleccionado.
 *     - *Note* (string): Nota descriptiva del cambio realizado.
 *     - *IncludedProvidersList* (string): Lista de proveedores a incluir, separados por comas.
 *     - *ExcludedProvidersList* (string): Lista de proveedores a excluir, separados por comas.
 *
 * @returns object El objeto $response es un array con los siguientes atributos:
 *  - *HasError* (bool): Indica si hubo un error en la operación.
 *  - *AlertType* (string): Especifica el tipo de alerta que se mostrará en la vista.
 *  - *AlertMessage* (string): Contiene el mensaje que se mostrará en la vista.
 *  - *ModelErrors*  (array): Lista de errores generados, vacío si no hay errores.
 *  - *Data* (array): Contiene el resultado de la operación, vacío si no hay datos específicos a devolver.
 *
 * Objeto en caso de error:
 *
 * ```php
 * $response["HasError"] = true;
 * $response["AlertType"] = "danger";
 * $response["AlertMessage"] = "Ocurrió un error en la operación.";
 * $response["ModelErrors"] = [$e->getMessage()];
 * ```
 *
 * @throws Exception Si ocurre un error en la actualización de datos o auditoría.
 * @throws Exception si la nota esta vacia o nula
 *
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* asigna parámetros y obtiene la IP del usuario. */
$Partner = $params->Partner;
$CountrySelect = $params->CountrySelect;
$Note = $params->Note;
$IncludedProvidersList = $params->IncludedProvidersList;
$ExcludedProvidersList = $params->ExcludedProvidersList;


if($Note == '' || $Note == null) {
    throw new exception("La observacion es obligatoria", 300163);
}

$ip = !empty($_SERVER['HTTP_X_FORWADED_']) ? $_SERVER['HTTP_X_FORWADED_FOR'] : $SERVER['REMOTE_ADDR'];

/* identifica dispositivos móviles usando expresiones regulares en el user agent. */
$ip = explode(",", $ip)[0];


function detectarTipoDispositivo($userAgent)
{
    $dispositivosMoviles = array(
        '/iphone/i',
        '/ipod/i',
        '/ipad/i',
        '/android/i',
        '/blackberry/i',
        '/webos/i'
    );


    /* Determina si el usuario está en un dispositivo móvil o de escritorio. */
    foreach ($dispositivosMoviles as $pattern) {
        if (preg_match($pattern, $userAgent)) {
            return 'mobile';
        }
    }
    return 'desktop';
}


$userAgent = $_SERVER['HTTP_USER_AGENT'];

/* La variable almacena el tipo de dispositivo según el agente del usuario. */
$tipoDispositivo = detectarTipoDispositivo($userAgent);

if ($Partner != '' && !empty($IncludedProvidersList)) {

    /* Se procesa una lista de proveedores y se obtiene una transacción específica. */
    $IncludedProvidersList = explode(',', $IncludedProvidersList);
    $SubproveedorMandantePaisMySqlDAO = new SubproveedorMandantePaisMySqlDAO();
    $Transaction = $SubproveedorMandantePaisMySqlDAO->getTransaction();
    $userId = $_SESSION['usuario2'];
    $dirIp = !empty($_SERVER['HTTP_X_FORWADED_']) ? $_SERVER['HTTP_X_FORWADED_FOR'] : $_SERVER['REMOTE_ADDR'];
    $dirIp = explode(",", $dirIp)[0];
    $type = 'CHANGESUBPROVIDER';



    /* Inserta registros en el log general con detalles de transacciones y usuarios. */
    $device = $Global_dispositivo;
    $field = 'orden';
    $table = 'subproveedor_mandante_pais';

    $sqlGeneralLog = 'INSERT general_log (usuario_id, usuario_ip, usuariosolicita_id, usuariosolicita_ip, usuarioaprobar_id, usuarioaprobar_ip, tipo, valor_antes, valor_despues, usucrea_id, usumodif_id, estado, dispositivo, externo_id, campo, tabla, explicacion, mandante) VALUES';

    $sqlValuesGeneralLog = " ({$userId}, '{$dirIp}', {$userId}, '{$dirIp}', 0, '', '{$type}', $1, $2, 0, 0, 'A', '{$device}', $3, '{$field}', '{$table}', '{$Note}', {$Partner}),";

    foreach ($IncludedProvidersList as $key => $value) {

        /* Se inicializan variables para almacenar valores antes y después de una operación. */
        $beforeValue = 0;
        $afterValue = 0;

        try {

            /* Se crea un objeto y se modifican sus propiedades antes de guardar cambios. */
            $SubproveedorMandantePais = new SubproveedorMandantePais('', $value, $Partner, $CountrySelect);
            $beforeValue = $SubproveedorMandantePais->getOrden();
            $beforeState = $SubproveedorMandantePais->getEstado();


            $SubproveedorMandantePais->setEstado('A');
            $SubproveedorMandantePais->setUsumodifId($_SESSION['usuario']);
            $SubproveedorMandantePais->setDetalle('');

            /* Código establece orden y registra auditoría para un subproveedor basado en sesión activa. */
            $SubproveedorMandantePais->setOrden($key + 1);
            if($beforeValue != $SubproveedorMandantePais->getOrden() and $beforeState != $SubproveedorMandantePais->getEstado()) {
                $Subproveedor = new Subproveedor($SubproveedorMandantePais->getSubproveedorId());
                $NameSubproviderIncluded = $Subproveedor->descripcion;
                $AuditoriaGeneral = new AuditoriaGeneral();



                $AuditoriaGeneral->setUsuarioId($_SESSION["usuario"]);

                /* Código para registrar auditoría de actividad de usuario en un sistema. */
                $AuditoriaGeneral->setUsuarioIp($ip);
                $AuditoriaGeneral->setUsuariosolicitaId($_SESSION["usuario"]); // ajuste
                $AuditoriaGeneral->setUsuariosolicitaIp($ip);
                $AuditoriaGeneral->setUsuariosolicitaId(0);
                $AuditoriaGeneral->setUsuarioaprobarIp(0);
                $AuditoriaGeneral->setTipo("ACTIVACIONDEPASARELA");

                /* Configura valores y usuario para una auditoría general en un sistema. */
                $AuditoriaGeneral->setValorAntes("I");
                $AuditoriaGeneral->setValorDespues("A");
                $AuditoriaGeneral->setUsucreaId($_SESSION["usuario"]);
                $AuditoriaGeneral->setUsumodifId(0);
                $AuditoriaGeneral->setEstado("A");
                $AuditoriaGeneral->setDispositivo($tipoDispositivo);

                /* registra una auditoría y gestiona transacciones en la base de datos. */
                $AuditoriaGeneral->setObservacion($Note); /*Se guarda en el campo observacion la descripcion del cambio*/
                $AuditoriaGeneral->setData($SubproveedorMandantePais->getProvmandanteId());


                $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO();
                $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);
                $AuditoriaGeneralMySqlDAO->getTransaction()->commit();



            }
            $SubproveedorMandantePaisMySqlDAO = new SubproveedorMandantePaisMySqlDAO($Transaction);

            /* Actualiza un registro de SubproveedorMandantePais en la base de datos MySQL. */
            $SubproveedorMandantePaisMySqlDAO->update($SubproveedorMandantePais);
            if ($beforeValue != $SubproveedorMandantePais->getOrden()) {
                $afterValue = 0;
                $afterValue = $SubproveedorMandantePais->getOrden();
                $sqlGeneralLog .= str_replace(['$1', '$2', '$3'], [$beforeValue, $afterValue, $value], $sqlValuesGeneralLog);
            }

        } catch (Exception $ex) {
            if ($ex->getCode() == 107) {

                /* Se crea un objeto para gestionar subproveedores con estado activo y sin detalles. */
                $SubproveedorMandantePais = new SubproveedorMandantePais();

                $SubproveedorMandantePais->setMandante($Partner);
                $SubproveedorMandantePais->setSubproveedorId($value);
                $SubproveedorMandantePais->setEstado('A');
                $SubproveedorMandantePais->setDetalle('');

                /* Se configuran propiedades de un objeto SubproveedorMandantePais basado en la sesión actual. */
                $SubproveedorMandantePais->setUsucreaId($_SESSION['usuario']);
                $SubproveedorMandantePais->setUsumodifId('0');
                $SubproveedorMandantePais->setVerifica('I');
                $SubproveedorMandantePais->setFiltroPais('I');
                $SubproveedorMandantePais->setMax('0');
                $SubproveedorMandantePais->setMin('0');

                /* inserta un registro de subproveedor en la base de datos. */
                $SubproveedorMandantePais->setOrden($key + 1);
                $SubproveedorMandantePais->setPaisId($CountrySelect);

                $SubproveedorMandantePaisMySqlDAO = new SubproveedorMandantePaisMySqlDAO($Transaction);
                $SubproveedorMandantePaisMySqlDAO->insert($SubproveedorMandantePais);


                $Subproveedor = new Subproveedor($SubproveedorMandantePais->getSubproveedorId());

                /* asigna valores y configura una auditoría en el sistema. */
                $NameSubproviderIncluded = $Subproveedor->descripcion;
                $AuditoriaGeneral = new AuditoriaGeneral();

                $AuditoriaGeneral->setUsuarioId($_SESSION["usuario"]);
                $AuditoriaGeneral->setUsuarioIp($ip);
                $AuditoriaGeneral->setUsuariosolicitaId($_SESSION["usuario"]);

                /* Registro de auditoría de activación de pasarela con información de IP y estado. */
                $AuditoriaGeneral->setUsuariosolicitaIp($ip);
                $AuditoriaGeneral->setUsuariosolicitaId(0);
                $AuditoriaGeneral->setUsuarioaprobarIp(0);
                $AuditoriaGeneral->setTipo("ACTIVACIONDEPASARELA");
                $AuditoriaGeneral->setValorAntes("I");
                $AuditoriaGeneral->setValorDespues("A");

                /* Configura los datos de auditoría general utilizando sesiones y valores específicos. */
                $AuditoriaGeneral->setUsucreaId($_SESSION["usuario"]);
                $AuditoriaGeneral->setUsumodifId(0);
                $AuditoriaGeneral->setEstado("A");
                $AuditoriaGeneral->setDispositivo($tipoDispositivo);
                $AuditoriaGeneral->setObservacion($Note); /*Se guarda en el campo observacion la descripcion del cambio*/


                $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO();

                /* Inserta un registro de auditoría en la base de datos y maneja la transacción. */
                $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);
                $AuditoriaGeneralMySqlDAO->getTransaction()->commit();

                $sqlGeneralLog .= str_replace(['$1', '$2', '$3'], [$beforeValue, $afterValue, $value], $sqlValuesGeneralLog);
            }
        }


        /* Reemplaza marcadores en una consulta SQL con valores antes y después de una operación. */
        $afterValue = $key + 1;

    }


    /* Se ejecuta una inserción en la base de datos y se actualiza un proveedor. */
    $SqlQuery = new SqlQuery(rtrim($sqlGeneralLog, ','));
    QueryExecutor::executeInsert($Transaction, $SqlQuery);
    $Transaction->commit();
    $CMSProveedor = new CMSProveedor('CASINO', '', $Partner, $CountrySelect);
    $CMSProveedor->updateDatabaseCasino();

    $response["HasError"] = false;

    /* Código define un arreglo de respuesta con un mensaje de éxito y sin errores. */
    $response["AlertType"] = "success";
    $response["AlertMessage"] = 'success';
    $response["ModelErrors"] = [];
} elseif ($Partner != '' && !empty($ExcludedProvidersList)) {


    /* Divide la cadena de proveedores excluidos en un array utilizando comas como delimitador. */
    $ExcludedProvidersList = explode(',', $ExcludedProvidersList);
    foreach ($ExcludedProvidersList as $key => $value) {

        try {


            /* Código instanciando un objeto y configurando su estado a 'Inactivo'. */
            $SubproveedorMandantePaisMySqlDAO = new SubproveedorMandantePaisMySqlDAO();
            $Transaction = $SubproveedorMandantePaisMySqlDAO->getTransaction();

            $SubproveedorMandantePais = new SubproveedorMandantePais('', $value, $Partner, $CountrySelect);

            $SubproveedorMandantePais->setEstado('I');

            /* Actualiza un objeto SubproveedorMandantePais en la base de datos con nuevos valores. */
            $SubproveedorMandantePais->setUsumodifId($_SESSION['usuario']);
            $SubproveedorMandantePais->setDetalle('');
            $SubproveedorMandantePais->setOrden(0);

            $SubproveedorMandantePaisMySqlDAO = new SubproveedorMandantePaisMySqlDAO($Transaction);
            $SubproveedorMandantePaisMySqlDAO->update($SubproveedorMandantePais);


            /* Se crea una auditoría general con datos del usuario y su IP. */
            $AuditoriaGeneral = new AuditoriaGeneral();

            $AuditoriaGeneral->setUsuarioId($_SESSION["usuario"]);
            $AuditoriaGeneral->setUsuarioIp($ip);
            $AuditoriaGeneral->setUsuariosolicitaId($_SESSION["usuario"]);
            $AuditoriaGeneral->setUsuariosolicitaIp($ip);

            /* configura una auditoría de cambios en un registro de proveedor. */
            $AuditoriaGeneral->setUsuariosolicitaId(0);
            $AuditoriaGeneral->setUsuarioaprobarIp(0);
            $AuditoriaGeneral->setTipo("CAMBIOSUBPROVEEDORMANDANTEPAIS");
            $AuditoriaGeneral->setValorAntes("A");
            $AuditoriaGeneral->setValorDespues("I");
            $AuditoriaGeneral->setUsucreaId($_SESSION["usuario"]);

            /* Configura los parámetros de AuditoriaGeneral y crea un DAO para manejarlo. */
            $AuditoriaGeneral->setUsumodifId(0);
            $AuditoriaGeneral->setEstado("A");
            $AuditoriaGeneral->setDispositivo(0);
            $AuditoriaGeneral->setObservacion($Note); /*Se guarda en el campo observacion la descripcion del cambio*/
            $AuditoriaGeneral->setData($SubproveedorMandantePais->getProvmandanteId());


            $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO();

            /* Inserta un objeto de auditoría general en la base de datos MySQL. */
            $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);

        } catch (Exception $e) {
            /* Manejo de excepciones en PHP para gestionar errores de ejecución. */


        }
    }

    /* confirma una transacción y prepara una respuesta exitosa sin errores. */
    $Transaction->commit();

    $response["HasError"] = false;
    $response["AlertType"] = "success";
    $response["AlertMessage"] = 'success';
    $response["ModelErrors"] = [];
} else {
    /* Código que maneja errores, estableciendo un mensaje y señalando presencia de errores. */

    $response["HasError"] = true;
    $response["AlertType"] = "error";
    $response["AlertMessage"] = 'error';
    $response["ModelErrors"] = [];
}
?>