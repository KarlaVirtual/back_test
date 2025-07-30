<?php

/**
 * Propósito: Este recurso actualiza parametros de configuracion de sesion a los usuario
 * Descripción de variables:
 *    - Partner: Mandante al cual se le va a aplicar la configuracion (obligatorio)
 *    - CountrySelected: Pais al cual se le va a aplicar la configuracion (obligatorio)
 *    - TypeRegister: Tipo de registro
 *    - ApproveChangesInformation: Autorizar cambios en informacion personal
 *    - AutomaticallyActive: Activacion automatica de usuario post-registro
 *    - ActivateRegisterUser: Para iniciar sesion el usuario debe estar activo
 *    - DaysNotifyBeforePasswordExpire: Dias de aviso antes de la expiracion de la sesion
 *    - DaysAlertUpdateData: Aviso de actualizacion de datos por dias en la sesion
 *    - SessionInativityLength: Duracion de la sesion incativa
 *    - UserWrongLoginAttempts: Limite de intentos de inicio de sesion
 *    - SessionLength: Minutos de sesion activa
 *    - DaysAlertChangePassword: Alerta de cambio de contraseña por dias
 *    - UserPasswordExpireDays: Duracion de contraseña en dias
 *    - UserTempPasswordExpireDays: Duracion de contaseña temporal en dias
 *    - UserPasswordMinLength: Logitud minima de caracteres para contraseña
 **/


use Backend\dto\Clasificador;
use Backend\dto\MandanteDetalle;
use Backend\sql\Transaction;
use Backend\mysql\MandanteDetalleMySqlDAO;


/**
 * @OA\Post(
 *     path="/apipv/cases/PartnerSettingsSitebuilder/UpdatePartnerSettingsSitebuilder",
 *     summary="Este recurso actualiza parametros de configuracion de sesion a los usuario",
 *     description="Este recurso actualiza parametros de configuracion de sesion a los usuario",
 *     tags={"PartnerSettingsSitebuilder"},
 *
 *     @OA\Parameter(
 *         name="Partner",
 *         in="query",
 *         required=true,
 *         description="Mandante al cual se le va a aplicar la configuracion",
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="CountrySelected",
 *         in="query",
 *         required=true,
 *         description="Pais al cual se le va a aplicar la configuracion",
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="TypeRegister",
 *         in="query",
 *         required=true,
 *         description="Tipo de registro",
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="ApproveChangesInformation",
 *         in="query",
 *         required=false,
 *         description="Autorizar cambios en informacion personal",
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="AutomaticallyActive",
 *         in="query",
 *         required=false,
 *         description="Activacion automatica de usuario post-registro",
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="ActivateRegisterUser",
 *         in="query",
 *         required=false,
 *         description="Para iniciar sesion el usuario debe estar activo",
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="DaysNotifyBeforePasswordExpire",
 *         in="query",
 *         required=false,
 *         description="Días para notificar antes de que expire la sesion",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="SessionInativityLength",
 *         in="query",
 *         required=false,
 *         description="Duración de la inactividad de la sesión",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="UserWrongLoginAttempts",
 *         in="query",
 *         required=false,
 *         description="Limite de intentos de inicio de sesión",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="SessionLength",
 *         in="query",
 *         required=false,
 *         description="Duración de la sesión activa",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="DaysAlertChangePassword",
 *         in="query",
 *         required=false,
 *         description="Días de alerta para cambio de contraseña",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="UserPasswordExpireDays",
 *         in="query",
 *         required=false,
 *         description="Días para que expire la contraseña del usuario",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="UserTempPasswordExpireDays",
 *         in="query",
 *         required=false,
 *         description="Días para que expire la contraseña temporal del usuario",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="UserPasswordMinLength",
 *         in="query",
 *         required=false,
 *         description="Longitud mínima de caracteres para la contraseña del usuario",
 *         @OA\Schema(type="integer")
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Respuesta exitosa",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="HasError", description="Indica si hubo error", type="boolean", example=false),
 *             @OA\Property(property="AlertType", description="Tipo de alerta", type="string", example="success"),
 *             @OA\Property(property="AlertMessage", description="Mensaje de alerta", type="string", example=""),
 *             @OA\Property(property="ModelErrors", description="Errores del modelo", type="array", @OA\Items(type="string")),
 *             @OA\Property(property="Data", description="Datos de respuesta", type="array", @OA\Items(type="string"))
 *         )
 *     )
 * )
 */

/**
 * PartnerSettingsSitebuilder/UpdatePartnerSettingsSitebuilder
 *
 * Este recurso actualiza los parámetros de configuración de sesión para los usuarios.
 *
 * @param object $params Objeto con los siguientes valores:
 * @param int $params->Partner Mandante al cual se aplicará la configuración (obligatorio).
 * @param string $params->CountrySelected País al cual se aplicará la configuración (obligatorio).
 * @param string $params->TypeRegister Tipo de registro (opcional).
 * @param string $params->ApproveChangesInformation Autorizar cambios en información personal (opcional).
 * @param string $params->AutomaticallyActive Activación automática de usuario post-registro (opcional).
 * @param string $params->ActivateRegisterUser Requiere activación para iniciar sesión (opcional).
 * @param int $params->DaysNotifyBeforePasswordExpire Días de aviso antes de expiración de contraseña (opcional).
 * @param int $params->SessionInativityLength Duración de la inactividad de la sesión (opcional).
 * @param int $params->UserWrongLoginAttempts Límite de intentos fallidos de inicio de sesión (opcional).
 * @param int $params->SessionLength Duración de la sesión activa en minutos (opcional).
 * @param int $params->DaysAlertChangePassword Días de alerta para cambio de contraseña (opcional).
 * @param int $params->UserPasswordExpireDays Días para que expire la contraseña (opcional).
 * @param int $params->UserTempPasswordExpireDays Días para que expire la contraseña temporal (opcional).
 * @param int $params->UserPasswordMinLength Longitud mínima de la contraseña (opcional).
 * 
 * 
 * @return array $response Respuesta con los siguientes valores:
 *     - HasError: Indica si hubo un error (boolean).
 *     - AlertType: Tipo de alerta (string).
 *     - AlertMessage: Mensaje de alerta (string).
 *     - ModelErrors: Errores del modelo (array).
 *     - Data: Datos de respuesta (array vacío).
 * @throws Exception Si faltan parámetros obligatorios o se detecta un error en la base de datos.
 */
$Partner = $params->Partner;
$Country = $params->CountrySelected;

if ($Partner === '' || empty($Country)) throw new Exception('Error general', 30001);


/* Array de configuración con parámetros relacionados a registro y seguridad de usuarios. */
$typesData = [
    'TYPEREGISTER' => $params->TypeRegister,
    'APPCHANPERSONALINF' => $params->ApproveChangesInformation,
    'REGISTERACTIVATION' => $params->AutomaticallyActive,
    'REQREGACT' => $params->ActivateRegisterUser,
    'DAYSNOTIFYPASSEXPIRE' => $params->DaysNotifyBeforePasswordExpire,
    'SESSIONINACTIVITYMIN' => $params->SessionInativityLength,
    'WRONGATTEMPTSLOGIN' => $params->UserWrongLoginAttempts,
    'SESSIONDURATIONMIN' => $params->SessionLength,
    'DAYALERTCHANGEPASS' => $params->DaysAlertChangePassword,
    'DAYSEXPIREPASSWORD' => $params->UserPasswordExpireDays,
    'DAYSEXPIRETEMPPASS' => $params->UserTempPasswordExpireDays,
    'MINLENPASSWORD' => $params->UserPasswordMinLength
];


/* Se crea una nueva instancia de la clase Transaction en PHP. */
$Transaction = new Transaction();

foreach ($typesData as $key => $value) {
    if ($value !== '') {

        /* valida un detalle y actualiza su estado si no coincide con un valor. */
        try {
            $Clasificador = new Clasificador('', $key);
            $MandanteDetalle = new MandanteDetalle('', $Partner, $Clasificador->clasificadorId, $Country, 'A');
            if ($MandanteDetalle->valor !== $value) {
                $MandanteDetalle->estado = 'I';
                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->update($MandanteDetalle);
                throw new Exception('No existe', '34');
            }
        } catch (Exception $ex) {
            /* Manejo de excepciones para insertar un nuevo MandanteDetalle en base de datos. */

            if ($ex->getCode() == 34) {
                $MandanteDetalle = new MandanteDetalle();
                $MandanteDetalle->setMandante($Partner);
                $MandanteDetalle->setTipo($Clasificador->clasificadorId);
                $MandanteDetalle->setValor($value);
                $MandanteDetalle->setUsucreaId($_SESSION["usuario"]);
                $MandanteDetalle->setUsumodifId($_SESSION["usuario"]);
                $MandanteDetalle->setPaisId($Country);
                $MandanteDetalle->setEstado('A');

                $MandanteDetalleMySqlDAO = new MandanteDetalleMySqlDAO($Transaction);
                $MandanteDetalleMySqlDAO->insert($MandanteDetalle);
            }
        }
    }
}


/* Confirma la transacción y establece una respuesta exitosa sin errores. */
$Transaction->commit();

$response['HasError'] = false;
$response['AlertType'] = 'success';
$response['AlertMessage'] = '';
$response['ModelErrors'] = [];

/* Inicializa un array vacío llamado 'Data' en el arreglo $response. */
$response['Data'] = [];
?>