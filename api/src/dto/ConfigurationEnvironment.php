<?php

namespace Backend\dto;

use Backend\integrations\mensajeria\Flynode;
use Backend\integrations\mensajeria\Infobip;

use Backend\integrations\mensajeria\Intico;
use Backend\integrations\mensajeria\Okroute;
use Backend\mysql\AuditoriaGeneralMySqlDAO;
use Backend\mysql\EgresoMySqlDAO;
use Backend\mysql\IngresoMySqlDAO;
use Backend\mysql\UsuarioCierrecajaMySqlDAO;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\mysql\UsuarioMensajeMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\dto\PaisMandante;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Messaging\CloudMessage;
use Dompdf\Dompdf;
use Error;
use Exception;
use PHPMailer;

/**
 * Object represents table 'ConfigurationEnvironment'
 *
 * @author: DT
 * @date: 2017-09-06 18:52
 * @category No
 * @package No
 * @version     1.0
 */
class ConfigurationEnvironment
{
    /** @var string $enviroment Entorno bajo el cual se ejecuta la plataforma Productivo | Desarrollo  */
    protected $environment;


    /** @var string $URL_ITAINMENT URl por defecto proveedor sportbook */
    var $URL_ITAINMENT = 'https://dataexport-altenar.biahosted.com';

    /** @var string $URL_ITAINMENTDEV URL de desarrollo proveedor sportbook */
    var $URL_ITAINMENTDEV = 'https://dataexport-altenar.biahosted.com';

    /** @var string $URL_ITAINMENTPROD URL producción proveedor sportbook */
    var $URL_ITAINMENTPROD = 'https://dataexport-altenar.biahosted.com';

    /** @var string $SendGridAPIKEY KEY proveedor mensajería */
    var $SendGridAPIKEY = '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';

    /**
     * ConfigurationEnvironment constructor.
     */
    public function __construct()
    {
        $this->environment = $_ENV['ENV_TYPE'];
    }

    /**
     * Verifica si el usuario ha validado su correo electrónico y envía un correo de verificación si es necesario.
     *
     * @param object $Usuario Objeto que contiene la información del usuario.
     * @param bool $validate Indica si se debe validar el correo electrónico del usuario. Por defecto es false.
     * @param bool $revalidate Indica si se debe reenviar el correo de verificación. Por defecto es false.
     * @throws Exception Si el usuario no ha validado su correo electrónico.
     */
    public function isCheckdUsuOnlineEmail($Usuario, $validate = false, $revalidate = false)
    {
        if ($Usuario->verifCorreo !== 'S') {
            try {
                $Mandante = new Mandante($Usuario->mandante);
                $Registro = new Registro('', $Usuario->usuarioId);
                $Clasificador = new Clasificador('', 'TEMPEMAIL');
                $Template = new Template('', $Mandante->mandante, $Clasificador->getClasificadorId(), $Usuario->paisId, strtolower($Usuario->idioma));
                $PaisMandante = new PaisMandante(null, $Usuario->mandante, $Usuario->paisId);
                $html = $Template->templateHtml;

                $subject = '';
                switch ($Usuario->idioma) {
                    case 'PT':
                        $subject = 'Verificação de e-mail';
                        break;
                    case 'EN':
                        $subject = 'Mail Verification';
                        break;
                    default:
                        $subject = 'Verificacion De Correo';
                        break;
                }

                $token = $this->encrypt($Usuario->usuarioId);
                $resource = $PaisMandante->baseUrl . "verificar-email/" . $Usuario->login . "/{$token}";
                $link = $resource;

                $html = str_replace('#userid#', $Usuario->usuarioId, $html);
                $html = str_replace('#name#', $Registro->nombre1, $html);
                $html = str_replace('#identification#', $Registro->cedula, $html);
                $html = str_replace('#lastname#', $Registro->apellido1, $html);
                $html = str_replace('#login#', $Usuario->login, $html);
                $html = str_replace('#fullname#', $Usuario->nombre, $html);
                $html = str_replace('#link#', $link, $html);

                if ($revalidate) {
                    $this->EnviarCorreoVersion3($Usuario->login, '', '', $subject, '', $subject, $html, '', '', '', $Mandante->mandante);
                }
                if (!$validate) throw new Exception('Usuario debe validar email', 10028);
            } catch (Exception $ex) {
                if ($ex->getCode() === 10028) {
                    throw new Exception('Usuario debe validar email', 10028);
                }
            }
        }
    }


    /**
     * Genera un código de validación de teléfono para un usuario.
     *
     * @param Usuario $Usuario El objeto Usuario para el cual se generará el código de validación.
     * @return string El código de validación generado.
     * @throws Exception Si el usuario no tiene un ID o si se ha sobrepasado el límite de intentos de validación.
     */
    public function generatePhoneValidationCode(Usuario $Usuario)
    {
        if (empty($Usuario->usuarioId)) throw new Exception('Error general', 100000);

        $rules = [];

        array_push($rules, ['field' => 'usuario_log.usuario_id', 'data' => $Usuario->usuarioId, 'op' => 'eq']);
        array_push($rules, ['field' => 'usuario_log.tipo', 'data' => 'VERIFYPHONE', 'op' => 'eq']);
        array_push($rules, ['field' => 'usuario_log.fecha_crea', 'data' => date('Y-m-d 00:00:00'), 'op' => 'ge']);
        array_push($rules, ['field' => 'usuario_log.fecha_crea', 'data' => date('Y-m-d 23:59:59'), 'op' => 'le']);

        $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

        $UsuarioLog = new UsuarioLog();
        $query = (string)$UsuarioLog->getUsuarioLogsCustom('usuario_log.usuariolog_id', 'usuario_log.fecha_crea', 'ASC', 0, 3, $filters, true);

        $query = json_decode($query, true);

        if ($query['count'][0]['.count'] < 10) {
            $hash = uniqid(rand(100000, 999999), true);
            $array = explode('.', $hash);
            $code = substr(array_pop($array), 0, 6);

            $UsuarioLog->setUsuarioId($Usuario->usuarioId);
            $UsuarioLog->setUsuarioIp('');
            $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
            $UsuarioLog->setUsuariosolicitaIp('');
            $UsuarioLog->setUsuarioaprobarId(0);
            $UsuarioLog->setUsuarioaprobarIp('');
            $UsuarioLog->setTipo('VERIFYPHONE');
            $UsuarioLog->setValorAntes('');
            $UsuarioLog->setValorDespues($this->encrypt_decrypt('encrypt', $code));
            $UsuarioLog->setUsucreaId(0);
            $UsuarioLog->setUsumodifId(0);
            $UsuarioLog->setEstado('P');

            $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
            $UsuarioLogMySqlDAO->insert($UsuarioLog);
            $UsuarioLogMySqlDAO->getTransaction()->commit();

            return $code;
        } else throw new Exception('Ha sobrepasado el limite de intentos para la validacion', 100092);
    }


    /**
     * Valida el código de teléfono del usuario.
     *
     * @param Usuario $Usuario Objeto que contiene la información del usuario.
     * @param string $user_code Código de verificación proporcionado por el usuario.
     * @return bool Retorna true si el código es válido, de lo contrario false.
     * @throws Exception Si el código de verificación ha expirado.
     */
    public function validatePhoneCode($Usuario, $user_code)
    {
        if ($Usuario->verifCelular === 'S') return true;
        $rules = [];

        array_push($rules, ['field' => 'usuario_log.usuario_id', 'data' => $Usuario->usuarioId, 'op' => 'eq']);
        array_push($rules, ['field' => 'usuario_log.tipo', 'data' => 'VERIFYPHONE', 'op' => 'eq']);
        array_push($rules, ['field' => 'usuario_log.valor_despues', 'data' => $this->encrypt_decrypt('encrypt', $user_code), 'op' => 'eq']);
        array_push($rules, ['field' => 'usuario_log.estado', 'data' => 'P', 'op' => 'eq']);

        $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

        $UsuarioLog = new UsuarioLog();
        $query = (string)$UsuarioLog->getUsuarioLogsCustom('usuario_log.usuariolog_id, usuario_log.fecha_crea', 'usuario_log.fecha_crea', 'ASC', 0, 1, $filters, true);
        $query = json_decode($query, true);

        if ($query['count'][0]['.count'] > 0) {
            $date_limit = strtotime($query['data'][0]['usuario_log.fecha_crea'] . ' + 1 minute');

            if (time() >= $date_limit) throw new Exception('El codigo de verificacion ha expirado', 100093);

            $UsuarioLog = new UsuarioLog($query['data'][0]['usuario_log.usuariolog_id']);
            $UsuarioLog->setEstado('A');
            $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
            $UsuarioLogMySqlDAO->getTransaction()->commit();
            return true;
        }

        return false;
    }

    /**
     * Verifica los permisos del usuario para un recurso específico.
     *
     * @param string $resource El recurso al que se desea acceder.
     * @param string $profile El perfil del usuario.
     * @param int $userId El ID del usuario.
     * @param string $permission (Opcional) El permiso específico requerido.
     * @return bool Devuelve true si el usuario tiene permiso para acceder al recurso, de lo contrario, false.
     */
    public function checkUserPermission($resource, $profile, $userId, $permission = '')
    {
        if (empty($resource) || empty($profile) || empty($userId)) return false;

        $restrictResources = [
            'Account/Logout',
            'Account/CheckAuthentication',
            'Account/GetUserPermissions',
            'Accounting/GetProvidersThird',
            'Accounting/GetConceptsIncomesSelect',
            'Setting/SavePartnerUser',
            'Setting/saveSetting2',
            'Setting/GetSetting',
            'Setting/GetPartnerSettingsConfig',
            'Setting/UpdatePartnerSettingsConfig',
            'Configuration/ChangeMyPassword',
            'Templates/GetTemplateTypes'
        ];

        if (in_array($resource, $restrictResources)) return true;

        $rules = [];

        array_push($rules, ['field' => 'perfil_submenu.perfil_id', 'data' => 'CUSTOM', 'op' => 'eq']);
        array_push($rules, ['field' => 'perfil_submenu.usuario_id', 'data' => $userId, 'op' => 'eq']);
        array_push($rules, ['field' => 'submenu.version', 'data' => 3, 'op' => 'eq']);
        array_push($rules, ['field' => 'submenu_recurso.recurso', 'data' => $resource, 'op' => 'eq']);
        if (!empty($permission)) array_push($rules, ['field' => 'submenu.pagina', 'data' => $permission, 'op' => 'eq']);

        $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

        $PerfilSubmenu = new PerfilSubmenu();
        $query = $PerfilSubmenu->getPerfilSubmenusRecursoCustom('submenu.submenu_id', 'submenu.submenu_id', 'ASC', 0, 1, $filters, true);

        $permissionsCustom = json_decode($query, true);

        if ($profile !== 'CUSTOM') {
            $rules = [];

            array_push($rules, ['field' => 'perfil_submenu.perfil_id', 'data' => $profile, 'op' => 'eq']);
            array_push($rules, ['field' => 'perfil_submenu.usuario_id', 'data' => 0, 'op' => 'eq']);
            array_push($rules, ['field' => 'submenu.version', 'data' => 3, 'op' => 'eq']);
            array_push($rules, ['field' => 'submenu_recurso.recurso', 'data' => $resource, 'op' => 'eq']);
            if (!empty($permission)) array_push($rules, ['field' => 'submenu.pagina', 'data' => $permission, 'op' => 'eq']);

            $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

            $PerfilSubmenu = new PerfilSubmenu();
            $query = $PerfilSubmenu->getPerfilSubmenusRecursoCustom('submenu.submenu_id', 'submenu.submenu_id', 'ASC', 0, 1, $filters, true);

            $permissionsProfile = json_decode($query, true);
        }


        $data = isset($permissionsProfile) ? array_merge($permissionsCustom['data'], $permissionsProfile['data']) : $permissionsCustom['data'];

        if (count($data) == 0) {
            syslog(LOG_WARNING, "ERRORPERMISOS : PROFILE" . $profile . ' - USUARIO ' . $userId . ' - PERMISO ' . $permission . ' - RECUS ' . $resource);

        }
        return count($data) > 0;
    }


    /**
     * Envía una notificación a los usuarios especificados o a un tema general.
     *
     * @param mixed $values Lista de IDs de usuarios a los que se enviará la notificación. Puede ser una cadena separada por comas o un array.
     * @param string $title Título de la notificación.
     * @param string $body Cuerpo de la notificación.
     * @param int|null $countryID ID del país para el tema de la notificación. Por defecto es null.
     * @param string $partner Nombre del socio para el tema de la notificación. Por defecto es una cadena vacía.
     * @return bool Devuelve true si la notificación se envió correctamente, false en caso contrario.
     */
    public function sendNotification($values, $title, $body, $countryID = null, $partner = '')
    {
        try {
            if ($countryID == 0) $countryID = null;
            $apiKeys = base64_decode($_ENV['GOOGLE_API_KEYS']);
            $apiKeys = json_decode($apiKeys, true);

            if (!empty($values)) {
                $users = is_array($values) ? implode(',', $values) : $values;
                $countData = is_array($values) ? count($values) : count(explode(',', $values));

                $rules = [];

                array_push($rules, ['field' => 'usuario_session.tipo', 'data' => 3, 'op' => 'eq']);
                array_push($rules, ['field' => 'usuario_session.estado', 'data' => 'A', 'op' => 'eq']);
                array_push($rules, ['field' => 'usuario_session.usuario_id', 'data' => $users, 'op' => 'in']);

                $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

                $UsuarioSession = new UsuarioSession();
                $query = (string)$UsuarioSession->getUsuariosCustom('usuario_session.request_id', 'usuario_session.fecha_crea', 'desc', 0, $countData, $filters, true, 'usuario_session.usuario_id');

                $query = json_decode($query, true);

                $tokens = array_map(function ($item) {
                    return $item['usuario_session.request_id'];
                }, $query['data']);

                if (oldCount($tokens) > 0) {
                    $factory = (new Factory)->withServiceAccount(json_encode($apiKeys));
                    $messaging = $factory->createMessaging();

                    foreach ($tokens as $value) {
                        $message = CloudMessage::withTarget('token', $value)
                            ->withNotification(Notification::fromArray(['title' => $title, 'body' => $body]));
                        $messaging->send($message);
                    }
                }
            } else {
                $topic = $countryID !== null ? "{$partner}-{$countryID}" : "{$partner}-all";
                $factory = (new Factory)->withServiceAccount(json_encode($apiKeys));
                $messaging = $factory->createMessaging();
                $message = CloudMessage::fromArray(['topic' => $topic, 'title' => $title, 'body' => $body]);
                $messaging->send($message);
            }

            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    /** Envío de una notificación de escritorio para las sesiones especificadas
     * @param mixed $values Lista de IDs de usuarios a los que se enviará la notificación. Puede ser una cadena separada por comas o un array.
     * @param string $title Título de la notificación.
     * @param string $body Cuerpo de la notificación.
     *
     * @return bool Devuelve true si la notificación se envió correctamente, false en caso contrario.
      */
    public function sendDesktopNotification($values, $title, $body)
    {
        $users = is_array($values) ? implode(',', $values) : $values;
        $countData = is_array($values) ? oldCount($values) : count(explode(',', $values));

        $rules = [];

        array_push($rules, ['field' => 'usuario_session.tipo', 'data' => 3, 'op' => 'eq']);
        array_push($rules, ['field' => 'usuario_session.estado', 'data' => 'A', 'op' => 'eq']);
        array_push($rules, ['field' => 'usuario_session.usuario_id', 'data' => $users, 'op' => 'in']);

        $filters = json_encode(['rules' => $rules, 'groupOp' => 'AND']);

        $UsuarioSession = new UsuarioSession();
        $query = (string)$UsuarioSession->getUsuariosCustom('usuario_session.request_id', 'usuario_session.fecha_crea', 'desc', 0, $countData, $filters, true, 'usuario_session.usuario_id');

        $query = json_decode($query, true);

        $tokens = array_map(function ($item) {
            return $item['usuario_session.request_id'];
        }, $query['data']);

        $data = [
            'type' => 'service_account',
            'project_id' => 'notifications-virtualsoft',
            'private_key_id' => '6c665ef478c025150f5930b6519b6d6504a1f1af',
            'private_key' => '-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCvnhkRB7wp3idz\nCgG8chY3ZvWKLyiCvOWYX5+o24VebXwUFhGcJw82senRxj+Fj06kxhiiUPH+nK5N\nWgfSrMY4m5qo0AuVuxOMEx3EYMAo93Hg5O+SKPmY2kYMGm6ABjSPs/0OoBtio/qi\nijDXyOs0Nr8Im+CvpJvZBOA2OxQN2JXboXXTntHqeyIrorm+JorPRFElnUszhmVc\nzE/LmjIij9/QNeyMDxvaeJVFP2Vc75Vr06rUY8AYv4PFEJ7KGdgJ3VkJ9+x8p4vL\nTO6LStgFQNTHs2p193EhrtMxQbdc4ZR9G+l1XNGj/4/v1QzcuHYxxrxD+Gubpo1W\nB3QdwhE/AgMBAAECggEAA1zsr/XM9aRKbxqG8/vfTwpKs225wd8qKmPSY+JoSGmi\nUaQmjC33TZ3Uk27PcphO8jrU5+7S44ROli6eLbufRCA+svLg9eoSMsF+9W7A30f1\nx0+7ArqEJRgw9TRgX1U/Fm1k8C3CuH3ZY9lvnAI99s8Bk4GgqNxnG53LSV6SQL2X\n1MKDGt2nE7IGAMJG7v7dPwGAU/2cpOEbgsqcfECVi31P94XSAf66d0bWvcXey/+U\nlNbkk/ZSfbi3N3+2DAbn8n6bt0n5bZ9+4zKJd6G0d8AS8lZgtAW5YaBYChv1dcIX\nNfQUG9FjpYGottyMoPCVKvFogIsss4MHF/sZUb+I6QKBgQD0s/wnIbE5M2pRMg3q\nJxS6FFsEKvalCCGqFEfcAhAH5iC2y0bbovq/S0xHN01SVAN7ABPoiKf+ALTCl0yk\nNWaEIO6TKi5QvVP8V1yZe5WeStpsaWyhjRZn7B5J2OBkOFhBshqgBlHq+59oGTon\nGoKnHe/UZw2Svt7Fu6eIR2MeqwKBgQC3uaDY60wNhuesTVyS84P/Q9YO3hr+mTAG\nKoG+eSfi052TVB+BjB3a0wmfui/4IsBVFSm+25ADXCFoz1XrihpYUNnscrgfmmu6\n9s9a8Ud2N/GWLkYqIOMyi+fo69xqQCp6JyWVVkJKOSRX31KWTx68nJhlUq6fMuq9\nz18LSDlHvQKBgGTgYd2b5RxolracROI3dDL1u0OvlngYLxCXRbxr2UH4W5ofmLlo\nqaf8mZhuMuDyo3Csaoic9WwfzGS/zKeRIA7uKIvggrFkK2BGf3UCXn6f6wVwPIko\nyYjT7PSShIasN631h0Za9KBDVMasR63hHuVX0Qul5BGPv/SR1JG4pCL7AoGBAJM2\nwYSu3YzFOSOeBXyWdYD3uTDGlXjGBG4cZ0PZTrV4/P1NXcVvIYjyqvZ6uO8p4VUL\nlRyiT/3xN7AE2oLWcQ+tEUFeFtzz3ji8hSAIz0sRvpmo0H33RjV9V0ESpNXaPm8M\nqRfFO26/5LgocMOR+D4HeDFQFC3qHaAj5rNilss9AoGAJUIUdXWfV3hSDZuzChN1\njBHKZn5Xq7dCOSDtiehniH0Q7tiQpJKMHj1P1AykglhuUJ6Uh3md4j02Fi0E1Kbh\n5xgnrvonYw9kDyoUWqDmEFCuF/rsCxbLl7Uwe6FXlcBCodNoRaxHnHEbzbNI+gzO\nSc6hDEmGo2zn2h6UGYfHMxg=\n-----END PRIVATE KEY-----\n',
            'client_email' => 'firebase-adminsdk-jgd3f@notifications-virtualsoft.iam.gserviceaccount.com',
            'client_id' => '109501193967558883129',
            'auth_uri' => 'https://accounts.google.com/o/oauth2/auth',
            'token_uri' => 'https://oauth2.googleapis.com/token',
            'auth_provider_x509_cert_url' => 'https://www.googleapis.com/oauth2/v1/certs',
            'client_x509_cert_url' => 'https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-jgd3f%40notifications-virtualsoft.iam.gserviceaccount.com',
            'universe_domain' => 'googleapis.com'
        ];

        $token = '';

        if (oldCount($tokens) > 0) {
            $url = 'https://fcm.googleapis.com/v1/projects/notifications-virtualsoft/messages:send';
            $body = [
                'message' => [
                    'token' => implode(',', $tokens),
                    'notification' => [
                        'body' => trim($title),
                        'title' => trim($body)
                    ]
                ]
            ];

            $headers = [
                'Content-Type: application/json',
                "Authentication: Beaber {$token}"
            ];

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($body));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($curl);
            $response = json_decode($response, true);

            var_dump($response);

            return empty($res) || $res['failure'] == true ? false : true;
        }

        return false;
    }


    /** Almacena cambios en la configuración de las plataformas
     * @param array $beforeData Datos de configuración antes de la actualización.
     * @param array $afterData Datos de configuración después de la actualización.
     *
     * @return void
     */
    public function generalAuditing($beforeData, $afterData)
    {
        if (empty($afterData)) return;

        $oldConfig = $this->getConfigSegment($beforeData, $afterData);

        $AuditoriaGeneral = new AuditoriaGeneral();
        $AuditoriaGeneral->usuarioId = $_SESSION['usuario'];
        $AuditoriaGeneral->usuarioIp = $_SESSION['dir_ip'];
        $AuditoriaGeneral->usuariosolicitaId = $_SESSION['usuario'];
        $AuditoriaGeneral->usuariosolicitaIp = $_SESSION['dir_ip'];
        $AuditoriaGeneral->usuarioaprobarId = 0;
        $AuditoriaGeneral->usuarioaprobarIp = 0;
        $AuditoriaGeneral->tipo = 'ACTUALIZACION_CONFIGURACION';
        $AuditoriaGeneral->valorAntes = json_encode($oldConfig);
        $AuditoriaGeneral->valorDespues = json_encode($afterData);
        $AuditoriaGeneral->usucreaId = $_SESSION['usuario'];
        $AuditoriaGeneral->usumodifId = 0;
        $AuditoriaGeneral->estado = 'A';
        $AuditoriaGeneral->dispositivo = $_SESSION['sistema'] === 'D' ? 'Desktop' : 'Mobile';
        $AuditoriaGeneral->observacion = '';
        $AuditoriaGeneral->data = '';
        $AuditoriaGeneral->campo = '';

        $AuditoriaGeneralMySqlDAO = new AuditoriaGeneralMySqlDAO();
        $AuditoriaGeneralMySqlDAO->insert($AuditoriaGeneral);
        $AuditoriaGeneralMySqlDAO->getTransaction()->commit();
    }


    /**
     * Obtiene un segmento de configuración basado en los datos nuevos proporcionados.
     *
     * @param array $config La configuración original.
     * @param array $newData Los nuevos datos para actualizar la configuración.
     * @return array El segmento de configuración actualizado.
     */
    private function getConfigSegment($config, $newData)
    {
        $data = [];
        foreach ($newData as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->getConfigSegment($config[$key], $value);
            } else $data[$key] = $config[$key];
        }

        return $data;
    }


    /**
     * Actualiza la configuración del constructor de sitios con los datos proporcionados.
     *
     * @param array $config La configuración actual del constructor de sitios.
     * @param array $data Los nuevos datos para actualizar la configuración.
     * @return array La configuración actualizada del constructor de sitios.
     */
    public function updateSiteBuilderg($config, $data)
    {
        if (empty($config)) return $data;
        if (empty($data)) return $config;

        foreach ($data as $key => $value) {
            if ($key === 'backgroundCardsTopEvents') $config[$key] = $value;
            else if (isset($config[$key])) {
                if ($value === 'null') unset($config[$key]);
                elseif (is_array($config[$key])) $config[$key] = $this->updateSiteBuilderg($config[$key], $value);
                else $config[$key] = $value;
            } else $config[$key] = $value;
        }

        return $config;
    }

    /**
     * @return string
     */
    public function getEnvironment()
    {
        return $this->environment;
    }


    /**
     * @return boolean
     */
    public function isDevelopment()
    {
        if ($this->environment == "dev") {
            return true;
        }
        return false;
    }


    /**
     * @return boolean
     */
    public function isProduction()
    {
        if ($this->environment == "prod") {
            return true;
        }
        return false;
    }

    /**
     * Obtiene la relación seleccionada basada en la cadena de selección dada.
     *
     * @param string $select La cadena de selección que contiene los campos a seleccionar, separados por comas.
     * @param mixed $relationship La relación actual que se está procesando.
     * @param array $relationshipArray El arreglo de relaciones que se está procesando.
     * @return mixed La relación actualizada después de procesar la selección.
     */
    public function getRelationshipSelect($select, $relationship, $relationshipArray)
    {
        $arraySelect = explode(",", $select);
        foreach ($arraySelect as $sel) {
            if (strpos($sel, "SUM")) {
                $sel = str_replace(' ', '', $sel);
                $sel = substr(explode(".", $sel)[0], 4);
            } elseif (strpos($sel, "COUNT")) {
                $sel = str_replace(' ', '', $sel);
                $sel = substr(explode(".", $sel)[0], 6);
            } else {
                $sel = explode(".", $sel)[0];
            }
            $result = $this->getRelationship($sel, $relationship, $relationshipArray);
            $relationship = $result['relationship'];
            $relationshipArray = $result['relationshipArray'];
        }

        return $relationship;
    }

    /** Función centraliza el conjunto de relaciones entre entidades (Tablas) con base en las rules para las colecciones
     * de relaciones entregadas
     * @param string $rule Entidades objetivo de la nueva relación
     * @param string $relationship Relaciones previas
     * @param array $relationshipArray Arreglo de relaciones que ya fueron agregadas a relationShip
     * */
    public function getRelationship($rule, $relationship, $relationshipArray)
    {
        if (!in_array($rule, $relationshipArray)) {
            switch ($rule) {
                case 'usuario_mandante':
                    $relationship .= " INNER JOIN usuario_mandante ON (usuario_mandante.usuario_mandante = usuario.usuario_id) ";
                    break;
                    // case 'punto_venta':
                    //     $relationship .= "INNER JOIN punto_venta ON (usuario_recarga.puntoventa_id = punto_venta.usuario_id) ";
                    //     break;
                case 'pais':
                    $relationship .= " INNER JOIN pais ON (pais.pais_id=usuario.pais_id)";
                    break;
                    // case 'concesionario':
                    //     $relationship .= " INNER JOIN concesionario ON (usuario.usuario_id = concesionario.usuhijo_id  and concesionario.prodinterno_id =0 and concesionario.estado='A')";
                    //     break;
                case 'transaccion_producto':
                    // $relationship .= " INNER JOIN transaccion_producto ON (transaccion_producto.final_id=usuario_recarga.recarga_id)";
                    $relationship .= " INNER JOIN transaccion_producto ON (transaccion_producto.transproducto_id=cuenta_cobro.transproducto_id) ";
                    break;
                    // case 'producto':
                    //     // $relationship .= " INNER JOIN producto ON (producto.producto_id=transaccion_producto.producto_id)";
                    //     // $relationship .= " INNER JOIN producto ON (producto.producto_id=producto_mandante.producto_id)";
                    //     break;
                    // case 'proveedor':
                    //     // $relationship .= " INNER JOIN proveedor ON (producto.proveedor_id=proveedor.proveedor_id)";
                    //     // $relationship .= " INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id)";
                    //     break;
                case 'usuario_perfil':
                    $relationship .= " INNER JOIN usuario_perfil ON (usuario.usuario_id = usuario_perfil.usuario_id)";
                    break;
                case 'producto_mandante':
                    // $relationship .= " INNER JOIN producto_mandante ON (producto_mandante.prodmandante_id=transaccion_juego.producto_id)";
                    break;
                case 'subproveedor':
                    $relationship .= " INNER JOIN subproveedor ON (subproveedor.subproveedor_id = producto.subproveedor_id)";
                    break;
                case 'registro':
                    $relationship .= " INNER JOIN registro ON (registro.usuario_id=usuario.usuario_id)";
                    break;
                case 'usuario_banco':
                    $relationship .= " INNER JOIN usuario_banco ON (usuario_banco.usubanco_id = cuenta_cobro.mediopago_id)";
                    break;
                case 'banco':
                    $this->getRelationship('usuario_banco', $relationship, $relationshipArray);
                    $relationship .= " INNER JOIN banco ON (usuario_banco.banco_id = banco.banco_id)";
                    break;
                case 'ciudad':
                    $relationship .= " INNER JOIN ciudad ON (ciudad.ciudad_id=cuenta_cobro.ciudad_id) ";
                    break;
                case 'departamento':
                    $this->getRelationship('ciudad', $relationship, $relationshipArray);
                    $relationship .= " INNER JOIN departamento ON (departamento.depto_id=ciudad.depto_id)";
                    break;
            }

            array_push($relationshipArray, $rule);
        }
        return [
            'relationship' => $relationship,
            'relationshipArray' => $relationshipArray
        ];
    }

    /**
     * Enviar un correo
     *
     * @param String $c_address c_address
     * @param String $c_from c_from
     * @param String $c_fromname c_fromname
     * @param String $c_subject c_subject
     * @param String $c_include c_include
     * @param String $c_title c_title
     * @param String $c_mensaje c_mensaje
     * @param String $c_dominio c_dominio
     * @param String $c_compania c_compania
     * @param String $c_color c_color
     * @param int $mandante ID del mandante remitente
     * @param string $paisId Objeto que contiene la información del usuario.
     *
     * @return boolean $ resultado de la operación
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    function EnviarCorreo($c_address, $c_from, $c_fromname, $c_subject, $c_include, $c_title, $c_mensaje, $c_dominio, $c_compania, $c_color, $mandante = 0, $paisId = null)
    {

        $mandanteBg = 'https://images.virtualsoft.tech/site/doradobet/email/bg.jpg';

        if (!empty($paisId)) {
            $PaisMandante = new PaisMandante(null, $mandante, $paisId);
            $c_from = $PaisMandante->emailNoreply;
        }
        else {
            $Mandante = new Mandante($mandante);
            $c_from = $Mandante->emailNoreply;
        }

        $mandanteBg = 'https://images.virtualsoft.tech/site/doradobet/email/bg.jpg';

        $mandanteFooterColor = '#b48303';


        $mandanteFooterColor = $Mandante->colorPrincipal;

        $c_fromname = $Mandante->nombre;
        $mandanteBg = $Mandante->emailFondo;

        /* switch ($mandante) {
             case 0:
                 $c_from = "noreply@doradobet.com";
                 $c_fromname = "Doradobet";
                 $mandanteBg = 'https://images.virtualsoft.tech/site/doradobet/email/bg.jpg';
                 break;

             case 1:
                 $c_from = "noreply@doradobet.com";
                 $c_fromname = "iBetsupreme";

                 break;

             case 2:
                 $c_from = "noreply@doradobet.com";
                 $c_fromname = "Justbet";
                 $mandanteBg = 'https://images.virtualsoft.tech/site/justbet/email/bg.jpg';
                 break;

             default:
                 $c_from = "noreply@virtualsoft.tech";
                 $c_from = "noreply@doradobet.com";
                 $c_fromname = "Apuestas";
                 $mandanteBg = 'https://images.virtualsoft.tech/site/virtualsoft/email/bg.jpg';

                 break;
         }
 */

        require(__DIR__ . "/../imports/phpmailer/class.phpmailer.php");
        require(__DIR__ . "/../imports/phpmailer/class.smtp.php");


        $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
        try {
            //Server settings
            $mail->SMTPDebug = 2;                                 // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'localhost';  // Specify main and backup SMTP servers
            //$mail->SMTPAuth = true;                               // Enable SMTP authentication
            //$mail->From = 'aa@aa.com';
            //$mail->FromName = "daniel";
            $mail->Subject = "tEST";
            $mail->SMTPDebug = 1;

            //Recipients
            $mail->setFrom($c_from, $c_fromname);
            $mail->addAddress($c_address, $c_address);     // Add a recipient
            //$mail->addAddress('ellen@example.com');               // Name is optional
            //$mail->addReplyTo('info@example.com', 'Information');
            //$mail->addCC('cc@example.com');
            // $mail->addBCC('bcc@example.com');

            //Attachments
            // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            $message = '
<html >
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<style>
		@import url(https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.css);

		body {
			font-family: \'Roboto\', sans-serif;
			text-decoration: none;
			font-size: 14px;
		}
		p {
			padding: 2rem;
			margin: 0;
		}
		.container {
			height: 600px;
			width: 100%;
		}
		.container .header{
			height: 330px;
			width: 100%;

		}
		.container .header div{
			height: 330px;
			background-size:  auto 102%;
			background-repeat: no-repeat;
			background-position: bottom;
		}


		.contain{
			height: auto;
		}
		.contain p{
			text-align: center;
			color: grey;
			line-height: 20px;
			padding-top: 1rem;
			padding-bottom: 1rem;
		}
		.contain h1{
			text-align: center;
			color: #b48303;
			margin: 0;
			padding-top: 15px;
		}

		.footer{
			height: 50px;
			background: #b48303;
		}
		.contain .social {
			height: 40px;
		}
		.contain div:first-child{
			height: auto;
		}
		.contain .social #l1 li{
			display:list-item;
			xlist-style:none;
		}

		.contain .social #l2 li{
			display: inline;
		}

		.contain .social #l1, .contain .social #l2{
			text-align: center;
			padding: 0;
			margin: 0;
		}

		.contain .social .social-icons li {
			font-size: 1.2em;
			padding: 0.8em;
			margin: 0;
		}
		.contain .social .social-icons a{
			color: #848484;
		}
		.footer p {
			font-weight: 300;
			text-align: center;
			color: white;
			padding: 1rem 2rem;
		}
	</style>
</head>
<body style="    max-width: 500px;">
	<div class="container">
		<div class="header" style="height: auto;">
					<div>

<img sr="" src="' . $mandanteBg . '" style="
    width: 100%;
">		
		</div>
		</div>
        <div class="contain">
			<div>
				<h1>' . $c_title . '</h1>
				<p>' . $c_mensaje . '</p>
			</div>
			</div>
		

		<div class="footer">
							
		</div>
	</div>
</body>
</html>

';

            /*<div class="contain">
            <div>
                <h1>' . $c_title . '</h1>
                <p>' . $c_mensaje . '</p>
            </div>

            <div class="social">
                <ul class="social-icons" id="l2">
                      <li><a target="_blank" href="https://www.facebook.com/doradobetcom/" class="social-icon" style=" width: 25px; height: 25px; display: inline-block; "> <i class="fa fa-facebook" ><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" width="25px" height="25px" viewBox="0 0 96.227 96.227" style="enable-background:new 0 0 96.227 96.227;" xml:space="preserve">
    <g>
    <path d="M73.099,15.973l-9.058,0.004c-7.102,0-8.477,3.375-8.477,8.328v10.921h16.938l-0.006,17.106H55.564v43.895H37.897V52.332   h-14.77V35.226h14.77V22.612C37.897,7.972,46.84,0,59.9,0L73.1,0.021L73.099,15.973L73.099,15.973z" fill="#084848"/>
    </g>

    </svg>
                      </i></a></li>
                      <li><a target="_blank" href="https://twitter.com/doradobet/" class="social-icon" style=" width: 25px; height: 25px; display: inline-block; "> <i class="fa fa-twitter"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" width="25px" height="25px" viewBox="0 0 94.135 94.135" style="enable-background:new 0 0 94.135 94.135;" xml:space="preserve">
    <g>
    <path d="M39.11,67.145c2.201,2.27,4.872,3.404,8.011,3.404h22.612c3.135,0,5.83,1.159,8.072,3.475   c2.245,2.312,3.364,5.084,3.364,8.32s-1.119,6.018-3.364,8.324c-2.242,2.311-4.928,3.467-8.07,3.467H47.131   c-9.416,0-17.462-3.445-24.143-10.344c-6.686-6.895-10.026-15.202-10.026-24.919v-47.07c0-3.329,1.114-6.13,3.34-8.4   C18.527,1.136,21.247,0,24.457,0c3.115,0,5.796,1.155,8.016,3.473c2.229,2.309,3.344,5.081,3.344,8.321v11.791h33.885   c3.148,0,5.847,1.158,8.098,3.471c2.253,2.311,3.373,5.086,3.373,8.325c0,3.233-1.12,6.009-3.365,8.321   c-2.242,2.311-4.936,3.468-8.072,3.468H35.814v11.691C35.814,62.107,36.911,64.867,39.11,67.145z" fill="#084848"/>
    </g>

    </svg>
                      </i></a></li>
                      <li><a target="_blank" href="https://www.instagram.com/doradobetlatam/" class="social-icon" style=" width: 25px; height: 25px; display: inline-block; "> <i class="fa fa-instagram"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" width="25px" height="25px" viewBox="0 0 97.395 97.395" style="enable-background:new 0 0 97.395 97.395;" xml:space="preserve">
    <g>
    <path d="M12.501,0h72.393c6.875,0,12.5,5.09,12.5,12.5v72.395c0,7.41-5.625,12.5-12.5,12.5H12.501C5.624,97.395,0,92.305,0,84.895   V12.5C0,5.09,5.624,0,12.501,0L12.501,0z M70.948,10.821c-2.412,0-4.383,1.972-4.383,4.385v10.495c0,2.412,1.971,4.385,4.383,4.385   h11.008c2.412,0,4.385-1.973,4.385-4.385V15.206c0-2.413-1.973-4.385-4.385-4.385H70.948L70.948,10.821z M86.387,41.188h-8.572   c0.811,2.648,1.25,5.453,1.25,8.355c0,16.2-13.556,29.332-30.275,29.332c-16.718,0-30.272-13.132-30.272-29.332   c0-2.904,0.438-5.708,1.25-8.355h-8.945v41.141c0,2.129,1.742,3.872,3.872,3.872h67.822c2.13,0,3.872-1.742,3.872-3.872V41.188   H86.387z M48.789,29.533c-10.802,0-19.56,8.485-19.56,18.953c0,10.468,8.758,18.953,19.56,18.953   c10.803,0,19.562-8.485,19.562-18.953C68.351,38.018,59.593,29.533,48.789,29.533z" fill="#084848"/>
    </g>
    <g>
    </g>
    </svg>
                      </i></a></li>
                      <li><a target="_blank" href="https://www.youtube.com/channel/UCuxJjrf89zWId29oOBq7Iqg" class="social-icon" style=" width: 25px; height: 25px; display: inline-block; "> <i class="fa fa-youtube"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" width="25px" height="25px" viewBox="0 0 90.677 90.677" style="enable-background:new 0 0 90.677 90.677;" xml:space="preserve">
    <g>
    <g>
        <path d="M82.287,45.907c-0.937-4.071-4.267-7.074-8.275-7.521c-9.489-1.06-19.098-1.065-28.66-1.06    c-9.566-0.005-19.173,0-28.665,1.06c-4.006,0.448-7.334,3.451-8.27,7.521c-1.334,5.797-1.35,12.125-1.35,18.094    c0,5.969,0,12.296,1.334,18.093c0.936,4.07,4.264,7.073,8.272,7.521c9.49,1.061,19.097,1.065,28.662,1.061    c9.566,0.005,19.171,0,28.664-1.061c4.006-0.448,7.336-3.451,8.272-7.521c1.333-5.797,1.34-12.124,1.34-18.093    C83.61,58.031,83.62,51.704,82.287,45.907z M28.9,50.4h-5.54v29.438h-5.146V50.4h-5.439v-4.822H28.9V50.4z M42.877,79.839h-4.629    v-2.785c-1.839,2.108-3.585,3.136-5.286,3.136c-1.491,0-2.517-0.604-2.98-1.897c-0.252-0.772-0.408-1.994-0.408-3.796V54.311    h4.625v18.795c0,1.084,0,1.647,0.042,1.799c0.111,0.718,0.462,1.082,1.082,1.082c0.928,0,1.898-0.715,2.924-2.166v-19.51h4.629    L42.877,79.839L42.877,79.839z M60.45,72.177c0,2.361-0.159,4.062-0.468,5.144c-0.618,1.899-1.855,2.869-3.695,2.869    c-1.646,0-3.234-0.914-4.781-2.824v2.474h-4.625V45.578h4.625v11.189c1.494-1.839,3.08-2.769,4.781-2.769    c1.84,0,3.078,0.969,3.695,2.88c0.311,1.027,0.468,2.715,0.468,5.132V72.177z M77.907,67.918h-9.251v4.525    c0,2.363,0.773,3.543,2.363,3.543c1.139,0,1.802-0.619,2.066-1.855c0.043-0.251,0.104-1.279,0.104-3.134h4.719v0.675    c0,1.491-0.057,2.518-0.099,2.98c-0.155,1.024-0.519,1.953-1.08,2.771c-1.281,1.854-3.179,2.768-5.595,2.768    c-2.42,0-4.262-0.871-5.599-2.614c-0.981-1.278-1.485-3.29-1.485-6.003v-8.941c0-2.729,0.447-4.725,1.43-6.015    c1.336-1.747,3.177-2.617,5.54-2.617c2.321,0,4.161,0.87,5.457,2.617c0.969,1.29,1.432,3.286,1.432,6.015v5.285H77.907z" fill="#084848"/>
        <path d="M70.978,58.163c-1.546,0-2.321,1.181-2.321,3.541v2.362h4.625v-2.362C73.281,59.344,72.508,58.163,70.978,58.163z" fill="#084848"/>
        <path d="M53.812,58.163c-0.762,0-1.534,0.36-2.307,1.125v15.559c0.772,0.774,1.545,1.14,2.307,1.14    c1.334,0,2.012-1.14,2.012-3.445V61.646C55.824,59.344,55.146,58.163,53.812,58.163z" fill="#084848"/>
        <path d="M56.396,34.973c1.705,0,3.479-1.036,5.34-3.168v2.814h4.675V8.82h-4.675v19.718c-1.036,1.464-2.018,2.188-2.953,2.188    c-0.626,0-0.994-0.37-1.096-1.095c-0.057-0.153-0.057-0.722-0.057-1.817V8.82h-4.66v20.4c0,1.822,0.156,3.055,0.414,3.836    C53.854,34.363,54.891,34.973,56.396,34.973z" fill="#084848"/>
        <path d="M23.851,20.598v14.021h5.184V20.598L35.271,0h-5.242l-3.537,13.595L22.812,0h-5.455c1.093,3.209,2.23,6.434,3.323,9.646    C22.343,14.474,23.381,18.114,23.851,20.598z" fill="#084848"/>
        <path d="M42.219,34.973c2.342,0,4.162-0.881,5.453-2.641c0.981-1.291,1.451-3.325,1.451-6.067v-9.034    c0-2.758-0.469-4.774-1.451-6.077c-1.291-1.765-3.11-2.646-5.453-2.646c-2.33,0-4.149,0.881-5.443,2.646    c-0.993,1.303-1.463,3.319-1.463,6.077v9.034c0,2.742,0.47,4.776,1.463,6.067C38.069,34.092,39.889,34.973,42.219,34.973z     M39.988,16.294c0-2.387,0.724-3.577,2.231-3.577c1.507,0,2.229,1.189,2.229,3.577v10.852c0,2.387-0.722,3.581-2.229,3.581    c-1.507,0-2.231-1.194-2.231-3.581V16.294z" fill="#084848"/>
    </g>
    </g>

    </svg>
                      </i></a></li>
                      <li><a target="_blank" href="https://plus.google.com/u/0/109119436366679125879/" class="social-icon" style=" width: 25px; height: 25px; display: inline-block; "> <i class="fa fa-google-plus"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" width="25px" height="25px" viewBox="0 0 96.669 96.669" style="enable-background:new 0 0 96.669 96.669;" xml:space="preserve">
    <g>
    <g>
        <path d="M50.91,55.189l-4.533-3.522c-1.38-1.144-3.27-2.656-3.27-5.422c0-2.778,1.889-4.544,3.527-6.18    c5.279-4.157,10.557-8.581,10.557-17.903c0-9.586-6.031-14.629-8.923-17.022h7.795L64.244,0H39.459    C32.658,0,22.856,1.608,15.68,7.533c-5.408,4.666-8.046,11.099-8.046,16.892c0,9.831,7.548,19.798,20.88,19.798    c1.259,0,2.636-0.124,4.022-0.252c-0.623,1.515-1.252,2.777-1.252,4.917c0,3.905,2.006,6.299,3.774,8.567    c-5.663,0.39-16.237,1.018-24.033,5.809c-7.424,4.415-9.684,10.84-9.684,15.377c0,9.334,8.8,18.028,27.045,18.028    c21.636,0,33.089-11.971,33.089-23.823C61.477,64.139,56.447,59.854,50.91,55.189z M34.431,40.691    c-10.824,0-15.727-13.992-15.727-22.434c0-3.288,0.623-6.682,2.763-9.333C23.486,6.4,27,4.762,30.281,4.762    c10.434,0,15.846,14.118,15.846,23.197c0,2.271-0.251,6.296-3.144,9.207C40.96,39.187,37.574,40.691,34.431,40.691z     M34.555,91.387c-13.46,0-22.139-6.438-22.139-15.392c0-8.949,8.048-11.978,10.816-12.979c5.281-1.777,12.076-2.024,13.21-2.024    c1.258,0,1.887,0,2.889,0.126c9.568,6.81,13.721,10.203,13.721,16.65C53.053,85.573,46.635,91.387,34.555,91.387z" fill="#084848"/>
        <polygon points="82.679,40.499 82.679,27.894 76.455,27.894 76.455,40.499 63.869,40.499 63.869,46.793 76.455,46.793     76.455,59.477 82.679,59.477 82.679,46.793 95.328,46.793 95.328,40.499   " fill="#084848"/>
    </g>
    </g>

    </svg></i></a></li>
                </ul>
            </div>
        </div>*/


            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $c_subject;
            //$mail->Body = 'This is the HTML message body <b>in bold!</b>';
            $mail->msgHTML($message);

            $ret = $mail->send();

            return true;
        } catch (Exception $e) {

            return false;
        }
    }

    /**
     * Realiza el envío de un correo electrónico mediante el cliente del proveedor Sendgrid, introduciendo el mensaje
     * en una plantilla HTML predefinida con estilos concretos.
     *
     * @param String $c_address Destinatario del correo
     * @param String $c_from Email remitente del correo
     * @param String $c_fromname Nombre remitente del correo
     * @param String $c_subject Asunto del correo
     * @param String $c_include
     * @param String $c_title Titular del contenido (Se presentará en medio de un H1)
     * @param String $c_mensaje Contenido del correo
     * @param String $c_dominio
     * @param String $c_compania
     * @param String $c_color
     * @param int $mandante ID del mandante remitente
     * @param boolean $fullHtml Define si el mensaje será enviado en medio de la plantilla predefinida de la función o en solitario
     * @param boolean $sendPdf Define si el contenido del correo se adjuntará a su vez como un archivo PDF
     * @param mixed $paisId Objeto que contiene la información del usuario.
     *
     * @return boolean $ resultado del envío
     */
    function EnviarCorreoVersion2($c_address, $c_from, $c_fromname, $c_subject, $c_include, $c_title, $c_mensaje, $c_dominio, $c_compania, $c_color, $mandante = 0, $fullHtml = false, $sendPdf = false, $paisId = null)
    {
        if (!empty($paisId)) {
            $PaisMandante = new PaisMandante(null, $mandante, $paisId);
            $c_from = $PaisMandante->emailNoreply;
        }
        else {
            $Mandante = new Mandante($mandante);
            $c_from = $Mandante->emailNoreply;
        }

        $mandanteBg = 'https://images.virtualsoft.tech/site/doradobet/email/bg.jpg';

        $mandanteFooterColor = '#b48303';

        $mandanteFooterColor = $Mandante->colorPrincipal;

        $c_fromname = $Mandante->nombre;
        $mandanteBg = $Mandante->emailFondo;

        $logoMandante = $Mandante->logo;

        if ($mandanteBg == "") {
            $mandanteBg = $logoMandante;
        }


        if (strpos($_SERVER['HTTP_REFERER'], "acropolis") !== FALSE && $Mandante->mandante == '2') {
            $mandanteFooterColor = '#990618';

            $c_subject = str_replace("Justbetja", "Acropolis", $c_subject);
            $c_title = str_replace("Justbetja", "Acropolis", $c_title);
            $c_mensaje = str_replace("Justbetja", "Acropolis", $c_mensaje);
            $c_mensaje = str_replace("mobile.justbetja.com", "www.acropolisonline.com", $c_mensaje);

            $c_fromname = 'Acropolis';
            $mandanteBg = $Mandante->emailFondo;

            $logoMandante = 'https://images.virtualsoft.tech/m/msjT1623425307.png';

            if ($mandanteBg == "") {
                $mandanteBg = $logoMandante;
            }
        }

        /*switch ($mandante) {
            case 0:
                $c_from = "noreply@doradobet.com";
                $c_fromname = "Doradobet";
                $mandanteBg = 'https://images.virtualsoft.tech/site/doradobet/email/bg.jpg';
                break;

            case 1:
                $c_from = "noreply@doradobet.com";
                $c_from = "noreply@virtualsoft.tech";
                $c_fromname = "iBetsupreme";

                break;

            case 2:
                $c_from = "noreply@doradobet.com";
                $c_from = "noreply@virtualsoft.tech";

                $c_fromname = "Justbet";
                $mandanteBg = 'https://images.virtualsoft.tech/site/justbet/email/bg.jpg';
                break;

            case 3:
                $c_from = "noreply@doradobet.com";
                $c_from = "noreply@casinomiravallepalace.com";
                $mandanteFooterColor='#ab0000';

                $c_fromname = "Casino Miravalle Palace";
                $mandanteBg = 'https://images.virtualsoft.tech/site/virtualsoft/email/bg.jpg';
                break;

            case 4:
                $c_from = "noreply@doradobet.com";
                $c_from = "noreply@casinogranpalaciomx.com";
                $mandanteFooterColor='#888888';

                $c_fromname = "Casino Gran Palacio Palace";
                $mandanteBg = 'https://images.virtualsoft.tech/site/virtualsoft/email/bg.jpg';
                break;


            default:
                $c_from = "noreply@virtualsoft.tech";
                $c_from = "noreply@virtualsoft.tech";
                $c_fromname = "Apuestas";
                $mandanteBg = 'https://images.virtualsoft.tech/site/virtualsoft/email/bg.jpg';

                break;
        }*/

        try {
            $imgBg = '';
            if ($mandanteBg != '') {
                $imgBg = '<img sr="" src="' . $mandanteBg . '" style="
    width: 100%;
">';
            }

            $message = $fullHtml === false ? '
<html >
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<style>
		@import url(https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.css);

		body {
			font-family: \'Roboto\', sans-serif;
			text-decoration: none;
			font-size: 14px;
		}
		p {
			padding: 2rem;
			margin: 0;
		}
		.container {
			height: 600px;
			width: 100%;
		}
		.container .header{
			height: 330px;
			width: 100%;

		}
		.container .header div{
			height: 330px;
			background-size:  auto 102%;
			background-repeat: no-repeat;
			background-position: bottom;
		}


		.contain{
			height: auto;
		}
		.contain p{
			text-align: center;
			color: grey;
			line-height: 20px;
			padding-top: 1rem;
			padding-bottom: 1rem;
		}
		.contain h1{
			text-align: center;
			color: ' . $mandanteFooterColor . ';
			margin: 0;
			padding-top: 15px;
		}

		.header{
			background: ' . $mandanteFooterColor . ';
		}
		.footer{
			height: 50px;
			background: ' . $mandanteFooterColor . ';
		}
		.contain .social {
			height: 40px;
		}
		.contain div:first-child{
			height: auto;
		}
		.contain .social #l1 li{
			display:list-item;
			xlist-style:none;
		}

		.contain .social #l2 li{
			display: inline;
		}

		.contain .social #l1, .contain .social #l2{
			text-align: center;
			padding: 0;
			margin: 0;
		}

		.contain .social .social-icons li {
			font-size: 1.2em;
			padding: 0.8em;
			margin: 0;
		}
		.contain .social .social-icons a{
			color: #848484;
		}
		.footer p {
			font-weight: 300;
			text-align: center;
			color: white;
			padding: 1rem 2rem;
		}
	</style>
</head>
<body style="    max-width: 500px;">
	<div class="container">
		<div class="header" style="height: auto;">' . $imgBg . '</div>
        <div class="contain">
			<div>
				<h1>' . $c_title . '</h1>
				<p>' . $c_mensaje . '</p>
			</div>
			</div>
		

		<div class="footer">
							
		</div>
	</div>
</body>
</html>

' : $c_mensaje;

            include_once(__DIR__ . "/../imports/SendGridClient/Client.php");
            include_once(__DIR__ . "/../imports/SendGridClient/Response.php");
            include_once(__DIR__ . "/../imports/SendGrid/SendGrid.php");
            include_once(__DIR__ . "/../imports/SendGrid/loader.php");

            $email = new \SendGrid\Mail\Mail();
            $email->setFrom($c_from, $c_fromname);
            $email->setSubject($c_subject);
            $email->addTo($c_address, $c_address);

            if ($sendPdf) {
                $dompPDF = new Dompdf();
                $dompPDF->loadHtml($message);
                $dompPDF->render();
                $PDF = base64_encode($dompPDF->output());
                $email->addAttachment($PDF, 'application/pdf', "{$c_subject}.pdf", 'attachment');
            }

            $email->addContent(
                "text/html",
                $message
            );
            $sendgrid = new \SendGrid($this->SendGridAPIKEY);
            try {
                $response = $sendgrid->send($email);
            } catch (Exception $e) {
                return false;
            }
            return true;
        } catch (Error $e) {
            return false;
        } catch (Exception $e) {

            return false;
        }
    }

    /** Realiza el envío de un correo mediante el cliente de SendGrid
     * @param object $Usuario Objeto que contiene la información del usuario.
     * @param string $c_address Receptor del email
     * @param string $c_from Email del remitente
     * @param string $c_fromname Nombre del remitente
     * @param string $c_subject Asunto del email
     * @param string $c_include Archivo a incluir en el email
     * @param string $c_title Titular de envío
     * @param string $c_mensaje Cuerpo del email
     * @param string $c_dominio
     * @param string $c_compania
     * @param string $c_color
     * @param int $mandante Mandante remitente del email
     * @param string $paisId Objeto que contiene la información del usuario.
     *
     * @return bool
     *
     *
     *
     */
    function EnviarCorreoVersion3($c_address, $c_from, $c_fromname, $c_subject, $c_include, $c_title, $c_mensaje, $c_dominio, $c_compania, $c_color, $mandante = 0, $paisId = null )
    {
        if ($mandante != '-1') {

            if (!empty($paisId)) {
                $PaisMandante = new PaisMandante(null, $mandante, $paisId);
                $c_from = $PaisMandante->emailNoreply;
            }
            else {
                $Mandante = new Mandante($mandante);
                $c_from = $Mandante->emailNoreply;
            }

            $mandanteBg = 'https://images.virtualsoft.tech/site/doradobet/email/bg.jpg';

            $mandanteFooterColor = '#b48303';

            $mandanteFooterColor = $Mandante->colorPrincipal;

            $c_fromname = $Mandante->nombre;
            $mandanteBg = $Mandante->emailFondo;

            $logoMandante = $Mandante->logo;

            if ($mandanteBg == "") {
                $mandanteBg = $logoMandante;
            }
        } else {


            $mandanteBg = '';

            $mandanteFooterColor = '#b48303';

            $c_from = 'noreply@virtualsoft.tech';
            $mandanteFooterColor = '';

            $c_fromname = 'Virtualsoft';
            $mandanteBg = '';

            $logoMandante = '';
        }


        if (strpos($_SERVER['HTTP_REFERER'], "acropolis") !== FALSE && $Mandante->mandante == '2') {
            $mandanteFooterColor = '#990618';

            $c_subject = str_replace("Justbetja", "Acropolis", $c_subject);
            $c_title = str_replace("Justbetja", "Acropolis", $c_title);
            $c_mensaje = str_replace("Justbetja", "Acropolis", $c_mensaje);
            $c_mensaje = str_replace("mobile.justbetja.com", "www.acropolisonline.com", $c_mensaje);

            $c_fromname = 'Acropolis';
            $mandanteBg = $Mandante->emailFondo;

            $logoMandante = 'https://images.virtualsoft.tech/m/msjT1623425307.png';

            if ($mandanteBg == "") {
                $mandanteBg = $logoMandante;
            }
        }


        try {
            $imgBg = '';
            if ($mandanteBg != '') {
                $imgBg = '<img sr="" src="' . $mandanteBg . '" style="
    width: 100%;
">';
            }

            $message = $c_mensaje;

            include_once(__DIR__ . "/../imports/SendGridClient/Client.php");
            include_once(__DIR__ . "/../imports/SendGridClient/Response.php");
            include_once(__DIR__ . "/../imports/SendGrid/SendGrid.php");
            include_once(__DIR__ . "/../imports/SendGrid/loader.php");

            $email = new \SendGrid\Mail\Mail();
            $email->setFrom($c_from, $c_fromname);
            $email->setSubject($c_subject);
            $email->addTo($c_address, $c_address);
            $email->addContent(
                "text/html", $message
            );
            $sendgrid = new \SendGrid($this->SendGridAPIKEY);
            try {
                $response = $sendgrid->send($email);
                if ($_ENV['debug']) {
                    print_r($response);
                }
            } catch (Exception $e) {
                return false;
            }
            return true;
        } catch (Error $e) {
            return false;
        } catch (Exception $e) {

            return false;
        }
    }


    /**
     * Envía un correo electrónico utilizando SendGrid y con la implementación opcional de una plantilla simple
     *
     * @param string $c_address Dirección de correo electrónico del destinatario.
     * @param string $c_from Dirección de correo electrónico del remitente.
     * @param string $c_fromname Nombre del remitente.
     * @param string $c_subject Asunto del correo electrónico.
     * @param string $c_include
     * @param string $c_title Título del correo electrónico.
     * @param string $c_mensaje Contenido del mensaje del correo electrónico.
     * @param string $c_dominio
     * @param string $c_compania
     * @param string $color_email
     * @param int $mandante ID del mandante remitente
     * @param bool $fullHtml Indica si c_mensaje cuenta con es un HTML completo que puede ser enviado de forma independiente
     * @param bool $sendPdf Indica si el contenido del mensaje debe ser enviado a su vez en un archivo PDF
     * @param string $paisId Objeto que contiene la información del usuario.
     * @return bool Devuelve true si el correo se envía correctamente, de lo contrario false.
     */
    function EnviarCorreoVersion4($c_address, $c_from, $c_fromname, $c_subject, $c_include, $c_title, $c_mensaje, $c_dominio, $c_compania, $color_email, $mandante = 0, $fullHtml = false, $sendPdf = false, $paisId = null)
    {
        $c_address = trim($c_address);

        if (!empty($paisId)) {
            $PaisMandante = new PaisMandante(null, $mandante, $paisId);
            $c_from = $PaisMandante->emailNoreply;
        }
        else {
            $Mandante = new Mandante($mandante);
            $c_from = $Mandante->emailNoreply;
        }

        $c_fromname = $Mandante->nombre;

        $logoMandante = $Mandante->logo;

        try {
            $message = $fullHtml === false ? $c_mensaje : '
<html >
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <style>
        body {
            font-family: \'Roboto\', sans-serif;
            text-decoration: none;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div>
        <h1>' . $c_title . '</h1>
        <p>' . $c_mensaje . '</p>
    </div>
</body>
</html>

';

            include_once(__DIR__ . "/../imports/SendGridClient/Client.php");
            include_once(__DIR__ . "/../imports/SendGridClient/Response.php");
            include_once(__DIR__ . "/../imports/SendGrid/SendGrid.php");
            include_once(__DIR__ . "/../imports/SendGrid/loader.php");

            $email = new \SendGrid\Mail\Mail();
            $email->setFrom($c_from, $c_fromname);
            $email->setSubject($c_subject);
            $email->addTo($c_address, $c_address);

            if ($sendPdf) {
                $dompPDF = new Dompdf();
                $dompPDF->loadHtml($message);
                $dompPDF->render();
                $PDF = base64_encode($dompPDF->output());
                $email->addAttachment($PDF, 'application/pdf', "{$c_subject}.pdf", 'attachment');
            }

            $email->addContent(
                "text/html", $message
            );
            $sendgrid = new \SendGrid($this->SendGridAPIKEY);
            try {
                $response = $sendgrid->send($email);
            } catch (Exception $e) {
                return false;
            }
            return true;
        } catch (Error $e) {
            return false;
        } catch (Exception $e) {

            return false;
        }
    }


    /**
     * Envía un mensaje de texto de prueba utilizando la API de Twilio.
     *
     * @param string $message El contenido del mensaje de texto.
     * @param string $fromphone El número de teléfono desde el cual se envía el mensaje.
     * @param string $tophone El número de teléfono al cual se envía el mensaje.
     * @param int $mandante Partner remitente del correo.
     * @return bool Retorna true si el mensaje se envió correctamente, de lo contrario false.
     */
    function EnviarMensajeTextoTest($message, $fromphone, $tophone, $mandante = 0)
    {

        try {

            require(__DIR__ . "/../imports/Twilio/Twilio/Rest/Client.php");
            require(__DIR__ . "/../imports/Twilio/Twilio/Domain.php");
            require(__DIR__ . "/../imports/Twilio/Twilio/Version.php");
            require(__DIR__ . "/../imports/Twilio/Twilio/InstanceContext.php");
            require(__DIR__ . "/../imports/Twilio/Twilio/Rest/Api/V2010/AccountContext.php");
            require(__DIR__ . "/../imports/Twilio/Twilio/Values.php");
            require(__DIR__ . "/../imports/Twilio/Twilio/Serialize.php");
            require(__DIR__ . "/../imports/Twilio/Twilio/VersionInfo.php");
            require(__DIR__ . "/../imports/Twilio/Twilio/ListResource.php");

            require(__DIR__ . "/../imports/Twilio/Twilio/Rest/Api/V2010/Account/MessageList.php");
            require(__DIR__ . "/../imports/Twilio/Twilio/Rest/Api/V2010.php");

            require(__DIR__ . "/../imports/Twilio/Twilio/Rest/Api.php");
            require(__DIR__ . "/../imports/Twilio/Twilio/Exceptions/TwilioException.php");

            require(__DIR__ . "/../imports/Twilio/Twilio/Exceptions/RestException.php");

            require(__DIR__ . "/../imports/Twilio/Twilio/Http/Response.php");
            require(__DIR__ . "/../imports/Twilio/Twilio/Http/Client.php");
            require(__DIR__ . "/../imports/Twilio/Twilio/Http/CurlClient.php");

            $sid = "AC27513dfb148b864884835349a6934dc3"; // Your Account SID from www.twilio.com/console
            $token = "f7a17892131f6cdb6a85136dd64cc219"; // Your Auth Token from www.twilio.com/console

            $client = new \Twilio\Rest\Client($sid, $token);

            $message = $client->messages->create(
                '+573012976239', // Text this number
                array(
                    'from' => $tophone, // From a valid Twilio number
                    'body' => $message
                )
            );
            print $message->sid;

            return true;
        } catch (Exception $e) {
            

            return false;
        }
    }

    /**
     * Envía un mensaje de texto a través de diferentes proveedores de SMS vinculados a procesos de CRM.
     *
     * @param string $message El contenido del mensaje a enviar.
     * @param string $fromphone El número de teléfono del remitente.
     * @param string $tophone El número de teléfono del destinatario.
     * @param int $mandante (Opcional) El ID del mandante.
     * @param UsuarioMandante $UsuarioMandante Objeto DTO del destinatario
     * @param UsuarioMensaje $UsuarioMensaje (Opcional) El mensaje del usuario.
     *
     * @return bool Retorna true si el mensaje fue enviado exitosamente, false en caso de error.
     */
    function EnviarMensajeTextoCRM($message, $fromphone, $tophone, $mandante = 0, UsuarioMandante $UsuarioMandante, $UsuarioMensaje = "")
    {

        try {
            //configurar el proveedor que tiene activado para envio de sms desde partner ajuste.

            $Clasificador = new Clasificador('', 'PROVSMS');
            $MandanteDetalle = new MandanteDetalle('', $UsuarioMandante->mandante, $Clasificador->getClasificadorId(), $UsuarioMandante->paisId, 'A');

            $Proveedor = new Proveedor($MandanteDetalle->valor);
            //$Proveedor = new Proveedor(138);

            switch ($Proveedor->abreviado) {

                case "OKROUTE":
                    $Okroute = new Okroute();

                    $UsuarioMensaje = new UsuarioMensaje();
                    $UsuarioMensaje->usufromId = 0;
                    $UsuarioMensaje->usutoId = $UsuarioMandante->usumandanteId;
                    $UsuarioMensaje->isRead = 0;
                    $UsuarioMensaje->body = $message;
                    $UsuarioMensaje->msubject = 'Mensaje';
                    $UsuarioMensaje->tipo = "MENSAJE";
                    $UsuarioMensaje->parentId = 0;
                    $UsuarioMensaje->proveedorId = 0;
                    $UsuarioMensaje->tipo = "SMS";
                    $UsuarioMensaje->fechaModif = date('Y-m-d H:i:s');

                    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                    $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                    $UsuarioMensajeMySqlDAO->getTransaction()->commit();


                    $Okroute->sendMessage($tophone, $message, $UsuarioMensaje);
                    $UsuarioMensaje->setPaisId($UsuarioMandante->getPaisId());
                    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                    $UsuarioMensajeMySqlDAO->update($UsuarioMensaje);
                    $UsuarioMensajeMySqlDAO->getTransaction()->commit();
                    break;

                case "INFOBIP":

                    $Infobip = new Infobip();

                    $UsuarioMensaje = new UsuarioMensaje();
                    $UsuarioMensaje->usufromId = 0;
                    $UsuarioMensaje->usutoId = $UsuarioMandante->usumandanteId;
                    $UsuarioMensaje->isRead = 0;
                    $UsuarioMensaje->body = $message;
                    $UsuarioMensaje->msubject = 'Mensaje';
                    $UsuarioMensaje->tipo = "MENSAJE";
                    $UsuarioMensaje->parentId = 0;
                    $UsuarioMensaje->proveedorId = 0;
                    $UsuarioMensaje->tipo = "SMS";

                    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                    $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                    $UsuarioMensajeMySqlDAO->getTransaction()->commit();

                    $Infobip->sendMessage($tophone, $message, $UsuarioMensaje);

                    $UsuarioMensaje->setPaisId($UsuarioMandante->getPaisId());
                    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                    $UsuarioMensajeMySqlDAO->update($UsuarioMensaje);
                    $UsuarioMensajeMySqlDAO->getTransaction()->commit();
                    break;

                case "FLYNODE":

                    $Flynode = new Flynode();

                    $UsuarioMensaje = new UsuarioMensaje();
                    $UsuarioMensaje->usufromId = 0;
                    $UsuarioMensaje->usutoId = $UsuarioMandante->usumandanteId;
                    $UsuarioMensaje->isRead = 0;
                    $UsuarioMensaje->body = $message;
                    $UsuarioMensaje->msubject = 'Mensaje';
                    $UsuarioMensaje->tipo = "SMS";
                    $UsuarioMensaje->parentId = 0;
                    $UsuarioMensaje->proveedorId = 0;
                    $UsuarioMensaje->tipo = "SMS";

                    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                    $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                    $UsuarioMensajeMySqlDAO->getTransaction()->commit();

                    $Flynode->sendMessage($tophone, $message, $UsuarioMensaje);

                    $UsuarioMensaje->setPaisId($UsuarioMandante->getPaisId());
                    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                    $UsuarioMensajeMySqlDAO->update($UsuarioMensaje);
                    $UsuarioMensajeMySqlDAO->getTransaction()->commit();
                    break;

                default:
                    $Intico = new Intico();
                    //validar si $usuarioMensaje == null crear
                    if (empty($UsuarioMensaje)) {

                        $UsuarioMensaje = new UsuarioMensaje();
                        $UsuarioMensaje->usufromId = 0;
                        $UsuarioMensaje->usutoId = $UsuarioMandante->usumandanteId;
                        $UsuarioMensaje->isRead = 0;
                        $UsuarioMensaje->body = $message;
                        $UsuarioMensaje->msubject = 'Mensaje';
                        $UsuarioMensaje->tipo = "MENSAJE";
                        $UsuarioMensaje->parentId = 0;
                        $UsuarioMensaje->proveedorId = 0;
                        $UsuarioMensaje->tipo = "SMS";

                        $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                        $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                        $UsuarioMensajeMySqlDAO->getTransaction()->commit();


                        $Intico->sendMessage($tophone, $message, $UsuarioMensaje);
                        $UsuarioMensaje->setPaisId($UsuarioMandante->getPaisId());
                        $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                        $UsuarioMensajeMySqlDAO->update($UsuarioMensaje);
                        $UsuarioMensajeMySqlDAO->getTransaction()->commit();
                        break;
                    } else {
                        $Intico->sendMessage($tophone, $message, $UsuarioMensaje);
                        $UsuarioMensaje->setPaisId($UsuarioMandante->getPaisId());
                        $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                        $UsuarioMensajeMySqlDAO->update($UsuarioMensaje);
                        $UsuarioMensajeMySqlDAO->getTransaction()->commit();
                    }
            }


            //return $response;
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Envía un mensaje de texto a través de diferentes proveedores de SMS.
     *
     * @param string $message El contenido del mensaje a enviar.
     * @param string $fromphone El número de teléfono del remitente.
     * @param string $tophone El número de teléfono del destinatario.
     * @param int $mandante (Opcional) El ID del mandante.
     * @param UsuarioMandante $UsuarioMandante Objeto que contiene información del usuario.
     * @param string $usumencampanaId (Opcional) El ID de la campaña de mensajes del usuario. Valor por defecto es '0'.
     *
     * @return bool Retorna true si el mensaje fue enviado exitosamente.
     *
     */
    function EnviarMensajeTexto($message, $fromphone, $tophone, $mandante = 0, UsuarioMandante $UsuarioMandante, $usumencampanaId = '0')
    {

        try {

            $Clasificador = new Clasificador('', 'PROVSMS');
            $MandanteDetalle = new MandanteDetalle('', $UsuarioMandante->mandante, $Clasificador->getClasificadorId(), $UsuarioMandante->paisId, 'A');

            $Proveedor = new Proveedor($MandanteDetalle->valor);

            switch ($Proveedor->abreviado) {

                case "OKROUTE":
                    $Okroute = new Okroute();

                    $UsuarioMensaje = new UsuarioMensaje();
                    $UsuarioMensaje->usufromId = 0;
                    $UsuarioMensaje->usutoId = $UsuarioMandante->usumandanteId;
                    $UsuarioMensaje->isRead = 0;
                    $UsuarioMensaje->body = $message;
                    $UsuarioMensaje->msubject = 'Mensaje';
                    $UsuarioMensaje->tipo = "MENSAJE";
                    $UsuarioMensaje->parentId = 0;
                    $UsuarioMensaje->proveedorId = 0;
                    $UsuarioMensaje->tipo = "SMS";
                    $UsuarioMensaje->usumencampanaId = $usumencampanaId;

                    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                    $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                    $UsuarioMensajeMySqlDAO->getTransaction()->commit();


                    $Okroute->sendMessage($tophone, $message, $UsuarioMensaje);

                    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                    $UsuarioMensajeMySqlDAO->update($UsuarioMensaje);
                    $UsuarioMensajeMySqlDAO->getTransaction()->commit();
                    break;

                case "INFOBIP":

                    $Infobip = new Infobip();

                    $UsuarioMensaje = new UsuarioMensaje();
                    $UsuarioMensaje->usufromId = 0;
                    $UsuarioMensaje->usutoId = $UsuarioMandante->usumandanteId;
                    $UsuarioMensaje->isRead = 0;
                    $UsuarioMensaje->body = $message;
                    $UsuarioMensaje->msubject = 'Mensaje';
                    $UsuarioMensaje->tipo = "MENSAJE";
                    $UsuarioMensaje->parentId = 0;
                    $UsuarioMensaje->proveedorId = 0;
                    $UsuarioMensaje->tipo = "SMS";
                    $UsuarioMensaje->usumencampanaId = $usumencampanaId;

                    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                    $usumensajeId = $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                    $UsuarioMensajeMySqlDAO->getTransaction()->commit();


                    $result = $Infobip->sendMessage($tophone, $message, $UsuarioMensaje);
                    $UsuarioMensaje = new UsuarioMensaje($usumensajeId);
                    $UsuarioMensaje->setExternoId($result->messages[0]->messageId);
                    $UsuarioMensaje->setProveedorId($Proveedor->getProveedorId());
                    $UsuarioMensaje->setPaisId($UsuarioMandante->getPaisId());
                    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                    $UsuarioMensajeMySqlDAO->update($UsuarioMensaje);
                    $UsuarioMensajeMySqlDAO->getTransaction()->commit();

                    break;


                case "FLYNODE":

                    $Flynode = new Flynode();

                    $UsuarioMensaje = new UsuarioMensaje();
                    $UsuarioMensaje->usufromId = 0;
                    $UsuarioMensaje->usutoId = $UsuarioMandante->usumandanteId;
                    $UsuarioMensaje->isRead = 0;
                    $UsuarioMensaje->body = $message;
                    $UsuarioMensaje->msubject = 'Mensaje';
                    $UsuarioMensaje->tipo = "SMS";
                    $UsuarioMensaje->parentId = 0;
                    $UsuarioMensaje->proveedorId = 0;
                    $UsuarioMensaje->tipo = "SMS";

                    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                    $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                    $UsuarioMensajeMySqlDAO->getTransaction()->commit();

                    $Flynode->sendMessage($tophone, $message, $UsuarioMensaje);

                    $UsuarioMensaje->setPaisId($UsuarioMandante->getPaisId());
                    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                    $UsuarioMensajeMySqlDAO->update($UsuarioMensaje);
                    $UsuarioMensajeMySqlDAO->getTransaction()->commit();
                    break;

                default:
                    $Intico = new Intico();

                    $UsuarioMensaje = new UsuarioMensaje();
                    $UsuarioMensaje->usufromId = 0;
                    $UsuarioMensaje->usutoId = $UsuarioMandante->usumandanteId;
                    $UsuarioMensaje->isRead = 0;
                    $UsuarioMensaje->body = $message;
                    $UsuarioMensaje->msubject = 'Mensaje';
                    $UsuarioMensaje->tipo = "MENSAJE";
                    $UsuarioMensaje->parentId = 0;
                    $UsuarioMensaje->proveedorId = 0;
                    $UsuarioMensaje->tipo = "SMS";
                    $UsuarioMensaje->usumencampanaId = $usumencampanaId;

                    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                    $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                    $UsuarioMensajeMySqlDAO->getTransaction()->commit();


                    $UsuarioMensaje = $Intico->sendMessage($tophone, $message, $UsuarioMensaje);
                    if ($UsuarioMensaje != null) {

                        $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                        $UsuarioMensajeMySqlDAO->update($UsuarioMensaje);
                        $UsuarioMensajeMySqlDAO->getTransaction()->commit();
                    }
                    break;
            }


            //return $response;
            return true;
        } catch (Exception $e) {


            $Intico = new Intico();

            $UsuarioMensaje = new UsuarioMensaje();
            $UsuarioMensaje->usufromId = 0;
            $UsuarioMensaje->usutoId = $UsuarioMandante->usumandanteId;
            $UsuarioMensaje->isRead = 0;
            $UsuarioMensaje->body = $message;
            $UsuarioMensaje->msubject = 'Mensaje';
            $UsuarioMensaje->tipo = "MENSAJE";
            $UsuarioMensaje->parentId = 0;
            $UsuarioMensaje->proveedorId = 0;
            $UsuarioMensaje->tipo = "SMS";
            $UsuarioMensaje->usumencampanaId = $usumencampanaId;

            $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
            $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
            $UsuarioMensajeMySqlDAO->getTransaction()->commit();


            $UsuarioMensaje = $Intico->sendMessage($tophone, $message, $UsuarioMensaje);

            if ($UsuarioMensaje != null) {

                $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                $UsuarioMensajeMySqlDAO->update($UsuarioMensaje);
                $UsuarioMensajeMySqlDAO->getTransaction()->commit();
            }

            return true;
        }

    }

    function EnviarMensajeMasivoApiBulk($message, $fromphone, $tophone, $mandante = 0, $usumencampanaId = '0', $Usuarios = array())
    {

        try {

            $Clasificador = new Clasificador('', 'PROVSMS');
            $UsuarioMandante = new UsuarioMandante($Usuarios[0]);

            $MandanteDetalle = new MandanteDetalle('', $UsuarioMandante->mandante, $Clasificador->getClasificadorId(), $UsuarioMandante->paisId, 'A');

            $Proveedor = new Proveedor($MandanteDetalle->valor);

            $UsuarioMensajes = array();
            $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
            $Transaction = $UsuarioMensajeMySqlDAO->getTransaction();
            foreach ($Usuarios as $usuario) {

                $UsuarioMandante = new UsuarioMandante($usuario);
                $UsuarioMensaje = new UsuarioMensaje();
                $UsuarioMensaje->usufromId = 0;
                $UsuarioMensaje->usutoId = $UsuarioMandante->usumandanteId;
                $UsuarioMensaje->isRead = 0;
                $UsuarioMensaje->body = $message;
                $UsuarioMensaje->msubject = 'Mensaje';
                $UsuarioMensaje->parentId = 0;
                $UsuarioMensaje->proveedorId = 0;
                $UsuarioMensaje->tipo = "SMS";
                $UsuarioMensaje->usumencampanaId = $usumencampanaId;
                $UsuarioMensajeMySqlDAO2 = new UsuarioMensajeMySqlDAO($Transaction);

                $UsuarioMensajeMySqlDAO2->insert($UsuarioMensaje);
                array_push($UsuarioMensajes, $UsuarioMensaje);
            }
            $Transaction->commit();

            switch ($Proveedor->abreviado) {

                case "INFOBIP":

                    $Infobip = new Infobip();
                    $result = $Infobip->sendMessageBulk($tophone, $message, $UsuarioMensajes);
                    break;

                case "INTICO":

                    $Intico = new Intico();
                    $UsuarioMensaje = $Intico->EnviarMensajeMasivoApiBulk($tophone, $message, $UsuarioMensajes, $mandante);
                    break;
            }

            return true;

        } catch
        (Exception $e) {
            return false;
        }
    }

    /**
     * Envía un mensaje de texto masivo con un enlace a múltiples usuarios mediante el cliente Intico.
     *
     * @param string $message El contenido del mensaje a enviar.
     * @param string $fromphone
     * @param int $mandante (Opcional) El ID del mandante. Valor por defecto es 0.
     * @param array $UsuarioMensajes Una lista de los UsuarioMensaje que serán enviados.
     * @param string $paisId (Opcional) El ID del país. Valor por defecto es una cadena vacía.
     *
     * @return bool Retorna true si el mensaje se envió correctamente, de lo contrario retorna false.
     */
    function EnviarMensajeTextoMasivoLink($message, $fromphone, $mandante = 0, $UsuarioMensajes, $paisId = '')
    {


        try {

            $UsuarioMensajes2 = array();

            $Intico = new Intico();

            foreach ($UsuarioMensajes as $usuarioMensaje) {

                $UsuarioMensaje = new UsuarioMensaje();
                $UsuarioMensaje->usufromId = 0;
                $UsuarioMensaje->usutoId = $usuarioMensaje['usumandanteId'];
                $UsuarioMensaje->isRead = 0;
                $UsuarioMensaje->body = $message;
                $UsuarioMensaje->msubject = 'Mensaje';
                $UsuarioMensaje->tipo = "MENSAJE";
                $UsuarioMensaje->parentId = 0;
                $UsuarioMensaje->proveedorId = 0;
                $UsuarioMensaje->tipo = "SMS";

                $UsuarioMensaje->valor1 = $usuarioMensaje['tophone'];
                $UsuarioMensaje->valor2 = $usuarioMensaje['link'];

                $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                $UsuarioMensajeMySqlDAO->getTransaction()->commit();


                $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                $UsuarioMensajeMySqlDAO->update($UsuarioMensaje);
                $UsuarioMensajeMySqlDAO->getTransaction()->commit();

                array_push($UsuarioMensajes2, $UsuarioMensaje);
            }


            $Intico->sendMessageLink($message, $UsuarioMensajes2, $mandante, $paisId);


            return true;
        } catch (Exception $e) {
            

            return false;
        }
    }

    /**
     * Convertir divisas
     *
     * @param array $from_Currency from_Currency
     * @param String $to_Currency to_Currency
     * @param String $amount amounts
     *
     * @return String $convertido convertido
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    function currencyConverter($from_Currency, $to_Currency, $amount)
    {
        return $amount;


        if ($from_Currency == $to_Currency) {
            return $amount;
        }
        global $currencies_valor;
        $convertido = -1;
        $bool = false;

        foreach ($currencies_valor as $key => $valor) {
            if ($key == ($from_Currency . "" . $to_Currency)) {
                $convertido = $amount * $valor;
                $bool = true;
            } elseif ($key == ($from_Currency . "" . $to_Currency)) {
                $convertido = ($amount) / $valor;
                $bool = true;
            }
        }
        if (!$bool) {
            $from_Currency = urlencode($from_Currency);
            $to_Currency = urlencode($to_Currency);
            $encode_amount = 1;

            $rawdata = file_get_contents("http://api.currencies.zone/v1/quotes/$from_Currency/$to_Currency/json?quantity=$encode_amount&key=44|YSqBgkAbvbGfenoxx62OaSnoD~rF8rw~");
            if ($_SESSION["usuario2"] == 5) {
            }
            $rawdata = json_decode($rawdata);
            $currencies_valor += [$from_Currency . "" . $to_Currency => $rawdata->result->amount];

            $convertido = $amount * $rawdata->result->amount;
        }


        return $convertido;
    }

    /**
     * Obtener los deportes en el intervalo de dos fechas
     *
     * @param String $fecha_inicial fecha_inicial
     * @param String $fecha_final fecha_final
     *
     * @return array $array array
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    function getSports($fecha_inicial, $fecha_final)
    {
        return array();

        $rawdata = file_get_contents($this->URL_ITAINMENT . "/Export/GetEvents?importerId=1&from=" . $fecha_inicial . "&to=" . $fecha_final . "");
        $data = simplexml_load_string($rawdata);
        $datos = json_decode($rawdata);

        $array = array();
        foreach ($datos as $item) {
            $item_data = array(
                "Id" => $item->SportId,
                "Name" => $item->Name
            );
            array_push($array, $item_data);
        }


        return $array;
    }

    /**
     * Obtener los market types de un deporte
     *
     * @param String $sport sport
     * @param String $fecha_inicial fecha_inicial
     * @param String $fecha_final fecha_final
     *
     * @return array $array array
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    function getMarketTypes($sport, $fecha_inicial, $fecha_final)
    {
        return array();

        $rawdata = file_get_contents($this->URL_ITAINMENT . "/Export/GetEvents?importerId=1&from=" . $fecha_inicial . "&to=" . $fecha_final . "");
        $data = simplexml_load_string($rawdata);
        $datos = json_decode($rawdata);
        $array = array();

        $existeMarcadorCorrecto = false;
        foreach ($datos as $item) {
            if ($sport == $item->SportId) {
                $rawdata2 = file_get_contents($this->URL_ITAINMENT . "/Export/GetMarkets?importerId=1&eventId=" . $item->Categories[0]->Championships[0]->Events[0]->EventId);
                $datos2 = json_decode($rawdata2);

                foreach ($datos2 as $item2) {
                    $item_data = array(
                        "Id" => $item->SportId . "M" . $item2->MarketTypeid,
                        "Name" => $item2->Name
                    );
                    array_push($array, $item_data);

                    if ($item2->MarketTypeid == 3 && $item->SportId == 1) {
                        $existeMarcadorCorrecto = true;
                    }
                }
            }
        }

        if (!$existeMarcadorCorrecto && $sport == 1) {
            $item_data = array(
                "Id" => "1M3",
                "Name" => "Marcador Correcto(F)"
            );
            array_push($array, $item_data);
        }


        return $array;
    }


    /**
     * Obtener las regiones de un deporte
     *
     * @param String $sport sport
     * @param String $fecha_inicial fecha_inicial
     * @param String $fecha_final fecha_final
     *
     * @return array $array array
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    function getRegions($sport, $fecha_inicial, $fecha_final)
    {

        return array();

        $rawdata = file_get_contents($this->URL_ITAINMENT . "/Export/GetEvents?importerId=1&from=" . $fecha_inicial . "&to=" . $fecha_final . "");
        $data = simplexml_load_string($rawdata);
        $datos = json_decode($rawdata);
        $array = array();

        foreach ($datos as $item) {

            if ($sport == $item->SportId) {
                foreach ($item->Categories as $item2) {
                    $item_data = array(
                        "Id" => $item2->CategoryId,
                        "Name" => $item2->Name
                    );
                    array_push($array, $item_data);
                }
            }
        }


        return $array;
    }

    /**
     * Obtener las competencias de un deporte
     *
     * @param String $sport sport
     * @param String $region region
     * @param String $fecha_inicial fecha_inicial
     * @param String $fecha_final fecha_final
     *
     * @return array $array array
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    function getCompetitions($sport, $region, $fecha_inicial, $fecha_final)
    {

        return array();
        $rawdata = file_get_contents($this->URL_ITAINMENT . "/Export/GetEvents?importerId=1&from=" . $fecha_inicial . "&to=" . $fecha_final . "");
        $data = simplexml_load_string($rawdata);
        $datos = json_decode($rawdata);
        $array = array();
        foreach ($datos as $item) {

            if ($sport == $item->SportId) {
                foreach ($item->Categories as $item2) {
                    if ($item2->CategoryId == $region) {
                        foreach ($item2->Championships as $item3) {
                            $item_data = array(
                                "Id" => $item3->ChampionshipId,
                                "Name" => $item3->Name
                            );
                            array_push($array, $item_data);
                        }
                    }
                }
            }
        }


        return $array;
    }


    /**
     * Obtener información sobre un deporte
     *
     * @param String $sport sport
     * @param String $region region
     * @param String $competition competition
     * @param String $fecha_inicial fecha_inicial
     * @param String $fecha_final fecha_final
     *
     * @return array $array array
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    function getMatches($sport, $region, $competition, $fecha_inicial, $fecha_final)
    {

        return array();
        $rawdata = file_get_contents($this->URL_ITAINMENT . "/Export/GetEvents?importerId=1&from=" . $fecha_inicial . "&to=" . $fecha_final . "");
        $data = simplexml_load_string($rawdata);
        $datos = json_decode($rawdata);
        $array = array();
        foreach ($datos as $item) {

            if ($sport == $item->SportId) {
                foreach ($item->Categories as $item2) {
                    if ($item2->CategoryId == $region) {
                        foreach ($item2->Championships as $item3) {

                            if ($item3->ChampionshipId == $competition) {
                                foreach ($item3->Events as $item4) {
                                    $item_data = array(
                                        "Id" => $item4->EventId,
                                        "Name" => $item4->Name
                                    );
                                    array_push($array, $item_data);
                                }
                            }
                        }
                    }
                }
            }
        }


        return $array;
    }


    /**
     * Generar una clave alfanumérica del ticket
     *
     * @param int $length length
     *
     * @return String $randomString randomString
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    function GenerarClaveTicket($length)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    /**
     * Generar una clave númera de ticket
     *
     * @param int $length length
     *
     * @return String $randomString randomString
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    function GenerarClaveTicket2($length)
    {
        $characters = '0123456789';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }


    /**
     * Encriptar o desencriptar según el caso
     *
     * @param String $action action
     * @param String $string string
     *
     * @return String $output output
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    function encrypt_decrypt($action, $string)
    {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'D0RAD0';
        $secret_iv = 'D0RAD0';
        // hash
        $key = hash('sha256', $secret_key);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        if ($action == 'encrypt') {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } else if ($action == 'decrypt') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
        return $output;
    }


    /**
     * Encripta un número de cliente.
     *
     * Esta función toma un número de cliente, genera un número aleatorio y lo concatena
     * con el número de cliente original. Luego, encripta el resultado utilizando un
     * array de caracteres y números aleatorios.
     *
     * @param string $num El número de cliente a encriptar.
     * @return string El número de cliente encriptado.
     */
    function encryptCusNum($num)
    {
        $arrayp = array('v', 'q', 'C', 9, 'l', 'W', 'U', 'I', 'f', 'c', 'g', 'u', 2, 'S', 'K', 'n', 7, 'A', 'P', 'e', 0, 'k', 'i', 'T', 'L', 'G', 4, 'N', 'r', 'z', 'B', 'H', 'a', 'Q', 3, 'X', 'M', 'd', 'w', 'D', 1, 'Y', 'y', 's', 'm', 5, 'x', 'R', 'V', 'F', 6, 'j', 'b', 't', 'O', 'h', 'o', 8, 'J', 'E');

        $length = strlen($num);

        $y = rand(0, ((pow(10, $length - 1) - 1)));
        for ($i = 0; $i < ($length - strlen($y) - 1); $i++) {
            $y = '0' . $y;
        }
        $result = $num . "_" . $y;
        $strEnc = '';

        foreach (str_split($result) as $key => $itemar) {
            $array = array(
                "_" => $arrayp[rand(0, 6)],
                "1" => $arrayp[rand(7, 11)],
                "2" => $arrayp[rand(12, 16)],
                "3" => $arrayp[rand(17, 22)],
                "4" => $arrayp[rand(23, 27)],
                "5" => $arrayp[rand(28, 33)],
                "6" => $arrayp[rand(34, 38)],
                "7" => $arrayp[rand(39, 43)],
                "8" => $arrayp[rand(44, 49)],
                "9" => $arrayp[rand(50, 54)],
                "0" => $arrayp[rand(55, 59)]
            );
            $itemar2 = '';
            foreach ($array as $key2 => $itemk) {
                if (strpos($itemar, strtolower($key2)) !== false) {
                    $itemar2 = str_replace($key2, $itemk, $itemar);
                }
            }

            $strEnc = $strEnc . $itemar2;
        }

        return $strEnc;
    }

    /**
     * Desencripta un número de cliente.
     *
     * Esta función toma un número de cliente encriptado, lo desencripta y devuelve el número original.
     *
     * @param string $strEnc El número de cliente encriptado.
     * @return string El número de cliente desencriptado.
     */
    function decryptCusNum($strEnc)
    {
        $arrayp = array('v', 'q', 'C', 9, 'l', 'W', 'U', 'I', 'f', 'c', 'g', 'u', 2, 'S', 'K', 'n', 7, 'A', 'P', 'e', 0, 'k', 'i', 'T', 'L', 'G', 4, 'N', 'r', 'z', 'B', 'H', 'a', 'Q', 3, 'X', 'M', 'd', 'w', 'D', 1, 'Y', 'y', 's', 'm', 5, 'x', 'R', 'V', 'F', 6, 'j', 'b', 't', 'O', 'h', 'o', 8, 'J', 'E');

        foreach (str_split($strEnc) as $key => $itemar) {

            $itemar2 = '';
            foreach ($arrayp as $i => $itemk) {
                $itemar = (string)$itemar;
                $itemk = (string)$itemk;


                if (strpos($itemar, $itemk) !== false) {

                    if ($i >= 0 && $i <= 6) {
                        $itemar2 = str_replace($itemk, '_', $itemar);
                    }
                    if ($i >= 7 && $i <= 11) {
                        $itemar2 = str_replace($itemk, '1', $itemar);
                    }
                    if ($i >= 12 && $i <= 16) {
                        $itemar2 = str_replace($itemk, '2', $itemar);
                    }
                    if ($i >= 17 && $i <= 22) {
                        $itemar2 = str_replace($itemk, '3', $itemar);
                    }
                    if ($i >= 23 && $i <= 27) {
                        $itemar2 = str_replace($itemk, '4', $itemar);
                    }
                    if ($i >= 28 && $i <= 33) {
                        $itemar2 = str_replace($itemk, '5', $itemar);
                    }
                    if ($i >= 34 && $i <= 38) {
                        $itemar2 = str_replace($itemk, '6', $itemar);
                    }
                    if ($i >= 39 && $i <= 43) {
                        $itemar2 = str_replace($itemk, '7', $itemar);
                    }
                    if ($i >= 44 && $i <= 49) {
                        $itemar2 = str_replace($itemk, '8', $itemar);
                    }
                    if ($i >= 50 && $i <= 54) {
                        $itemar2 = str_replace($itemk, '9', $itemar);
                    }
                    if ($i >= 55 && $i <= 59) {
                        $itemar2 = str_replace($itemk, '0', $itemar);
                    }
                }
            }

            $strEnc2 = $strEnc2 . $itemar2;
        }

        return $strEnc2;
    }

    /**
     * Obtener la ip del cliente
     *
     *
     * @return String $ipaddress ip del cliente
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    function get_client_ip()
    {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    /**
     * Crear arreglo unico a partir de uno multidimensiona
     *
     * @param array $array array
     * @param String $key key
     *
     * @return String $temp_array temp_array
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    function unique_multidim_array($array, $key)
    {
        $temp_array = array();
        $i = 0;
        $key_array = array();

        foreach ($array as $val) {
            if (!in_array($val[$key], $key_array)) {
                $key_array[$i] = $val[$key];
                $temp_array[$i] = $val;
            }
            $i++;
        }
        return $temp_array;
    }

    /**
     * Quitar tildes
     *
     * @param String $cadena cadena con tildes
     *
     * @return String $texto cadena sin tildes
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    function quitar_tildes($cadena)
    {
        $no_permitidas = array("á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú", "ñ", "À", "Ã", "Ì", "Ò", "Ù", "Ã™", "Ã ", "Ã¨", "Ã¬", "Ã²", "Ã¹", "ç", "Ç", "Ã¢", "ê", "Ã®", "Ã´", "Ã»", "Ã‚", "ÃŠ", "ÃŽ", "Ã”", "Ã›", "ü", "Ã¶", "Ã–", "Ã¯", "Ã¤", "«", "Ò", "Ã", "Ã„", "Ã‹");
        $permitidas = array("a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "n", "N", "A", "E", "I", "O", "U", "a", "e", "i", "o", "u", "c", "C", "a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "u", "o", "O", "i", "a", "e", "U", "I", "A", "E");
        $texto = str_replace($no_permitidas, $permitidas, $cadena);
        return $texto;
    }


    /**
     * Desencriptar con el método AES-128-CTR
     *
     * @param array data data
     * @param String encryption_key encryption_key
     *
     * @return boolean|String $decrypted_string decrypted_string
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    function decrypt2($data, $encryption_key = "")
    {

        $iv_strlen = 2 * openssl_cipher_iv_length('AES-128-CTR');
        if (preg_match("/^(.{" . $iv_strlen . "})(.+)$/", $data, $regs)) {
            list(, $iv, $crypted_string) = $regs;
            $decrypted_string = openssl_decrypt($crypted_string, 'AES-128-CTR', gethostname() . "|" . ip2long($_SERVER['SERVER_ADDR']), 0, hex2bin($iv));
            return $decrypted_string;
        } else {
            return FALSE;
        }
    }

    /**
     * Desencriptar con el método AES-128-CTR
     *
     * @param string $data cadena encriptada
     * @param String $encryption_key Llave de encriptación
     *
     * @return boolean|String $decrypted_string decrypted_string
     */
    function decrypt($data, $encryption_key = "")
    {
        $data = str_replace("vSfTp", "/", $data);

        $passEncryt = 'li1296-151.members.linode.com|3232279913';

        $iv_strlen = 2 * openssl_cipher_iv_length('AES-128-CTR');
        if (preg_match("/^(.{" . $iv_strlen . "})(.+)$/", $data, $regs)) {
            list(, $iv, $crypted_string) = $regs;
            $decrypted_string = openssl_decrypt($crypted_string, 'AES-128-CTR', $passEncryt, 0, hex2bin($iv));
            return $decrypted_string;
        } else {
            return FALSE;
        }
    }

    /**
     * Encriptar con el método AES-128-CTR
     *
     * @param array data data
     * @param String encryption_key encryption_key
     *
     * @return boolean|String $encrypted_string encrypted_string
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    function encrypt($data, $encryption_key = "")
    {
        $passEncryt = 'li1296-151.members.linode.com|3232279913';
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('AES-128-CTR'));
        $encrypted_string = bin2hex($iv) . openssl_encrypt($data, 'AES-128-CTR', $passEncryt, 0, $iv);
        $encrypted_string = str_replace("/", "vSfTp", $encrypted_string);
        return $encrypted_string;
    }

    /**
     * Encriptar con el método AES-128-CTR
     *
     * @param array data data
     * @param String encryption_key encryption_key
     *
     * @return boolean|String $encrypted_string encrypted_string
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    function encryptWithoutRandom($data, $encryption_key = "")
    {
        $passEncryt = 'li1296-151.members.linode.com|3232279913';
        $iv = (openssl_cipher_iv_length('AES-128-CTR'));
        $encrypted_string = bin2hex($iv) . openssl_encrypt($data, 'AES-128-CTR', $passEncryt, 0, $iv);
        $encrypted_string = str_replace("/", "vSfTp", $encrypted_string);
        return $encrypted_string;
    }


    /**
     * Encripta una cadena con el método sha256
     *
     * @param array data data
     * @param String encryption_key encryption_key
     *
     * @return boolean|String $encrypted_string encrypted_string
     */
    function encrypt_decrypt2($action, $string)
    {
        $output = false;

        $encrypt_method = "AES-256-CBC";
        $secret_key = 'li1296-151.members.linode.com|3232279913';
        $secret_iv = 'vIrtualSoft';

        // hash
        $key = hash('sha256', $secret_key);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        if ($action == 'encrypt') {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } else if ($action == 'decrypt') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }

        return $output;
    }

    /**
     * Convierte una cadena base64 en una imagen y la guarda en un archivo.
     *
     * @param string $base64_string La cadena base64 que representa la imagen.
     * @param string $output_file La ruta del archivo donde se guardará la imagen.
     * @return string La ruta del archivo de salida.
     */
    function base64ToImage($base64_string, $output_file)
    {
        $base64_string = str_replace(" ", "+", $base64_string);
        $file = fopen($output_file, "wb");

        $data = explode(',', $base64_string);

        fwrite($file, base64_decode($data[1]));
        fclose($file);

        return $output_file;
    }


    /**
     * Depura una cadena de texto eliminando ciertos caracteres no deseados.
     *
     * @param string $texto_depurar La cadena de texto a depurar.
     * @return string La cadena de texto depurada.
     */
    function DepurarCaracteres($texto_depurar)
    {

        /* $texto_depurar = str_replace("'", "", $texto_depurar);
         $texto_depurar = str_replace('"', "", $texto_depurar);
         $texto_depurar = str_replace(">", "", $texto_depurar);
         $texto_depurar = str_replace("<", "", $texto_depurar);
         $texto_depurar = str_replace("[", "", $texto_depurar);
         $texto_depurar = str_replace("]", "", $texto_depurar);
         $texto_depurar = str_replace("{", "", $texto_depurar);
         $texto_depurar = str_replace("}", "", $texto_depurar);
         $texto_depurar = str_replace("�", "", $texto_depurar);
         $texto_depurar = str_replace("`", "", $texto_depurar);
         $texto_depurar = str_replace("|", "", $texto_depurar);
         $texto_depurar = str_replace("�", "", $texto_depurar);
         $texto_depurar = str_replace("�", "", $texto_depurar);
         $texto_depurar = str_replace("%", "", $texto_depurar);
         $texto_depurar = str_replace("&", "", $texto_depurar);
         $texto_depurar = str_replace("�", "", $texto_depurar);
         $texto_depurar = str_replace("~", "", $texto_depurar);
         $texto_depurar = str_replace("+", "", $texto_depurar);
         $texto_depurar = str_replace("^", "", $texto_depurar);*/

        $texto_depurar = mb_convert_encoding($texto_depurar, 'UTF-8', 'UTF-8');

        $texto_depurar = str_replace("'", "", $texto_depurar);
        $texto_depurar = str_replace("'", "", $texto_depurar);
        $texto_depurar = str_replace('"', "", $texto_depurar);

        $texto_depurar = str_replace("�", "", $texto_depurar);
        $texto_depurar = str_replace("`", "", $texto_depurar);

        $texto_depurar = str_replace("�", "", $texto_depurar);
        $texto_depurar = str_replace("�", "", $texto_depurar);

        $texto_depurar = str_replace("�", "", $texto_depurar);
        $texto_depurar = str_replace("~", "", $texto_depurar);
        //$texto_depurar = preg_replace('/[^(\x20-\x7F)]*/','', $texto_depurar);

        return $texto_depurar;
    }

    /**
     * Elimina los emojis de una cadena de texto.
     *
     * @param string $string La cadena de texto que puede contener emojis.
     * @return string La cadena de texto sin emojis.
     */
    function remove_emoji($string)
    {
        $string = json_decode('"' . $string . '"');


        // Match Emoticons
        $regex_emoticons = '/[\x{1F600}-\x{1F64F}]/u';
        $clear_string = preg_replace($regex_emoticons, '', $string);

        // Match Miscellaneous Symbols and Pictographs
        $regex_symbols = '/[\x{1F300}-\x{1F5FF}]/u';
        $clear_string = preg_replace($regex_symbols, '', $clear_string);

        // Match Transport And Map Symbols
        $regex_transport = '/[\x{1F680}-\x{1F6FF}]/u';
        $clear_string = preg_replace($regex_transport, '', $clear_string);

        // Match Miscellaneous Symbols
        $regex_misc = '/[\x{2600}-\x{26FF}]/u';
        $clear_string = preg_replace($regex_misc, '', $clear_string);

        // Match Dingbats
        $regex_dingbats = '/[\x{2700}-\x{27BF}]/u';
        $clear_string = preg_replace($regex_dingbats, '', $clear_string);

       // Match General
        $regex_general = '/[\x{1F600}-\x{1F64F}|\x{1F300}-\x{1F5FF}|\x{1F680}-\x{1F6FF}|\x{1F700}-\x{1F77F}|\x{1F900}-\x{1F9FF}|\x{2600}-\x{26FF}|\x{2700}-\x{27BF}|\p{So}]/u';
        $clear_string = preg_replace($regex_general, '', $clear_string);

        return $clear_string;
    }


    /** Flujo realiza el consolidado de transacciones para la fecha indicada a los usuarios con perfil PUNTOVENTA
     * con base en criterios de apuestas, premios, retiros.
     * @param int $usumandanteId Id del usuario mandante
     * @param array $products Productos
     * @param array $expenses Gastos
     * @param array $incomes Ingresos
     * @param string $fechaHoy Fecha límite pago de transacciones (Apuestas, ingresos, egresos ETC)
     * @param string $fechaHoyConHora Fecha de hoy con hora
     * @param string $fechaHoyConHoraSegundos Fecha de hoy con hora y segundos
     * @param boolean $esEspecifico Indica la implementación de actualizaciones con timestamp específica a nivel de segundos
     * @return array $array array
     */
    function CierreCaja($usumandanteId, $products, $expenses, $incomes, $fechaHoy, $fechaHoyConHora, $fechaHoyConHoraSegundos, $esEspecifico = false)
    {
        $UsuarioMandante = new UsuarioMandante($usumandanteId);
        $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());


        $TipoTickets = new Clasificador("", "ACCBETTICKET");
        $TipoPremios = new Clasificador("", "ACCWINTICKET");
        $TipoNotasRetiros = new Clasificador("", "ACCPAYWD");
        $TipoRecargas = new Clasificador("", "ACCREC");
        $TipoDineroInicial = new Clasificador("", "ACCAMOUNTDAY");

        $TipoTicketsId = 0;
        $TipoPremiosId = 0;
        $TipoNotasRetirosId = 0;
        $TipoRecargasId = 0;
        $dineroInicial = 0;

        $otrosIngresosTarjetasCreditos = 0;

        $rules = [];
        array_push($rules, array("field" => "producto_tercero.tipo_id", "data" => "'" . $TipoTickets->getClasificadorId() . "','" . $TipoPremios->getClasificadorId() . "','" . $TipoNotasRetiros->getClasificadorId() . "','" . $TipoRecargas->getClasificadorId() . "'", "op" => "in"));
        array_push($rules, array("field" => "proveedor_tercero.pais_id", "data" => $UsuarioMandante->getPaisId(), "op" => "eq"));


        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);

        $ProductoTercero = new ProductoTercero();

        $data = $ProductoTercero->getProductoTercerosCustom("  producto_tercero.* ", "producto_tercero.productoterc_id", "asc", 0, 1000, $json, true);

        $data = json_decode($data);
        $final = [];
        foreach ($data->data as $key => $value) {
            switch ($value->{"producto_tercero.tipo_id"}) {
                case $TipoTickets->getClasificadorId():
                    $TipoTicketsId = $value->{"producto_tercero.productoterc_id"};

                    break;

                case $TipoPremios->getClasificadorId():
                    $TipoPremiosId = $value->{"producto_tercero.productoterc_id"};

                    break;

                case $TipoNotasRetiros->getClasificadorId():
                    $TipoNotasRetirosId = $value->{"producto_tercero.productoterc_id"};

                    break;

                case $TipoRecargas->getClasificadorId():
                    $TipoRecargasId = $value->{"producto_tercero.productoterc_id"};

                    break;
            }
        }


        $rules = [];
        array_push($rules, array("field" => "usuario.puntoventa_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
        //array_push($rules, array("field" => "usuario.usuario_id", "data" => 5703, "op" => "eq"));
        //array_push($rules, array("field" => "usuario_cierrecaja.fecha_crea", "data" => (date("Y-m-d 00:00:00") . ' - 1 days'), "op" => "ge"));


        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);

        $UsuarioCierrecaja = new UsuarioCierrecaja();

        $data = $UsuarioCierrecaja->getUsuarioCierrecajasCustom("  usuario.login,usuario_cierrecaja.* ", "usuario_cierrecaja.fecha_crea", "desc", 0, 1, $json, true);

        $data = json_decode($data);

        foreach ($data->data as $key => $value) {

            $array = [];


            $array["Id"] = $value->{"usuario_cierrecaja.usucierrecaja_id"};
            $array["User"] = $value->{"usuario_cierrecaja.usuario_id"};
            $array["UserName"] = $value->{"usuario.login"};
            $array["Date"] = date('Y-m-d', strtotime($value->{"usuario_cierrecaja.fecha_cierre"}));
            $array["AmountBegin"] = $value->{"usuario_cierrecaja.dinero_inicial"};
            $array["ProperIncomes"] = $value->{"usuario_cierrecaja.ingresos_propios"};
            $array["ProperExpenses"] = $value->{"usuario_cierrecaja.egresos_propios"};
            $array["ProductsIncomes"] = $value->{"usuario_cierrecaja.ingresos_productos"};
            $array["ProductsExpenses"] = $value->{"usuario_cierrecaja.egresos_productos"};
            $array["OthersIncomes"] = $value->{"usuario_cierrecaja.ingresos_otros"};
            $array["OthersExpenses"] = $value->{"usuario_cierrecaja.egresos_otros"};
            $array["IncomesCreditCards"] = $value->{"usuario_cierrecaja.ingresos_tarjetacredito"};
            $array["Total"] = $array["AmountBegin"] + $array["ProperIncomes"] + $array["ProductsIncomes"] + $array["OthersIncomes"]
                - $array["ProperExpenses"] - $array["ProductsExpenses"] - $array["OthersExpenses"] - $array["IncomesCreditCards"];

            $dineroInicial = $array["Total"];
        }

        $TotalIngresosPropios = 0;
        $TotalEgresosPropios = 0;

        $TotalIngresosProductos = 0;
        $TotalEgresosProductos = 0;

        $TotalIngresosOtros = 0;
        $TotalEgresosOtros = 0;
        $otrosIngresosTarjetasCreditos = 0;


        $IngresoMySqlDAO = new IngresoMySqlDAO();
        $Transaction = $IngresoMySqlDAO->getTransaction();


        foreach ($incomes as $income) {
            $Concept = $income->Concept;
            $Description = $income->Description;
            $Reference = $income->Reference;
            $Value = $income->Value;

            $Ingreso = new Ingreso();
            $Ingreso->setTipoId(0);
            $Ingreso->setDescripcion($Description);
            $Ingreso->setCentrocostoId(0);
            $Ingreso->setDocumento($Reference);
            $Ingreso->setEstado("A");
            $Ingreso->setValor($Value);
            $Ingreso->setImpuesto(0);
            $Ingreso->setRetraccion(0);
            $Ingreso->setUsuarioId($Usuario->puntoventaId);
            $Ingreso->setConceptoId($Concept);
            $Ingreso->setProductotercId(0);
            $Ingreso->setUsucajeroId($UsuarioMandante->getUsuarioMandante());
            $Ingreso->setProveedortercId(0);
            $Ingreso->fechaCrea = $fechaHoyConHoraSegundos;

            $Ingreso->setUsucreaId(0);
            $Ingreso->setUsumodifId(0);


            $IngresoMySqlDAO->insert($Ingreso);

            $TotalIngresosOtros = $TotalIngresosOtros + $Value;
        }


        foreach ($products as $product) {
            $Concept = 0;
            $Description = '';
            $Reference = '';
            $Value = $product->Bets;

            $Ingreso = new Ingreso();
            $Ingreso->setTipoId(0);
            $Ingreso->setDescripcion($Description);
            $Ingreso->setCentrocostoId(0);
            $Ingreso->setDocumento($Reference);
            $Ingreso->setEstado("A");
            $Ingreso->setValor($Value);
            $Ingreso->setImpuesto(0);
            $Ingreso->setRetraccion(0);
            $Ingreso->setUsuarioId($Usuario->puntoventaId);
            $Ingreso->setConceptoId($Concept);
            $Ingreso->setProductotercId($product->ProductId);
            $Ingreso->setUsucajeroId($UsuarioMandante->getUsuarioMandante());
            $Ingreso->setProveedortercId(0);
            $Ingreso->fechaCrea = $fechaHoyConHoraSegundos;

            $Ingreso->setUsucreaId(0);
            $Ingreso->setUsumodifId(0);


            $IngresoMySqlDAO->insert($Ingreso);

            $TotalIngresosProductos = $TotalIngresosProductos + $Value;
        }

        $EgresoMySqlDAO = new EgresoMySqlDAO($Transaction);

        foreach ($expenses as $expense) {
            $Concept = $expense->Concept;
            $Description = $expense->Description;
            $Reference = $expense->Reference;
            $Value = $expense->Value;

            $Egreso = new Egreso();
            $Egreso->setTipoId(0);
            $Egreso->setDescripcion($Description);
            $Egreso->setCentrocostoId(0);
            $Egreso->setDocumento($Reference);
            $Egreso->setEstado("A");
            $Egreso->setValor($Value);
            $Egreso->setImpuesto(0);
            $Egreso->setRetraccion(0);
            $Egreso->setUsuarioId($Usuario->puntoventaId);
            $Egreso->setConceptoId($Concept);
            $Egreso->setProductotercId(0);
            $Egreso->setUsucajeroId($UsuarioMandante->getUsuarioMandante());
            $Egreso->setProveedortercId(0);

            $Egreso->setUsucreaId(0);
            $Egreso->setUsumodifId(0);
            $Egreso->fechaCrea = $fechaHoyConHoraSegundos;


            $EgresoMySqlDAO->insert($Egreso);
            $TotalEgresosOtros = $TotalEgresosOtros + $Value;
        }

        foreach ($products as $product) {
            $Concept = 0;
            $Description = '';
            $Reference = '';
            $Value = $product->Prize;

            $Egreso = new Egreso();
            $Egreso->setTipoId(0);
            $Egreso->setDescripcion($Description);
            $Egreso->setCentrocostoId(0);
            $Egreso->setDocumento($Reference);
            $Egreso->setEstado("A");
            $Egreso->setValor($Value);
            $Egreso->setImpuesto(0);
            $Egreso->setRetraccion(0);
            $Egreso->setUsuarioId($Usuario->puntoventaId);
            $Egreso->setConceptoId($Concept);
            $Egreso->setProductotercId($product->ProductId);
            $Egreso->setUsucajeroId($UsuarioMandante->getUsuarioMandante());
            $Egreso->setProveedortercId(0);

            $Egreso->setUsucreaId(0);
            $Egreso->setUsumodifId(0);
            $Egreso->fechaCrea = $fechaHoyConHoraSegundos;


            $EgresoMySqlDAO->insert($Egreso);
            $TotalEgresosProductos = $TotalEgresosProductos + $Value;
        }

        $SkeepRows = 0;
        $OrderedItem = 1;
        $MaxRows = 1000;


        $rules = [];
        array_push($rules, array("field" => "usuario.puntoventa_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
        array_push($rules, array("field" => "it_ticket_enc.fecha_crea", "data" => $fechaHoy, "op" => "eq"));
        array_push($rules, array("field" => "it_ticket_enc.eliminado", "data" => 'N', "op" => "eq"));

        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);

        $ItTicketEnc = new ItTicketEnc();
        $data = $ItTicketEnc->getTicketsCustom("  SUM(it_ticket_enc.vlr_apuesta) vlr_apuesta, usuario.puntoventa_id ", "usuario.puntoventa_id", "asc", $SkeepRows, $MaxRows, $json, true);

        $data = json_decode($data);


        foreach ($data->data as $key => $value) {
            if ($value->{".vlr_apuesta"} == "") {
                $value->{".vlr_apuesta"} = 0;
            }

            if ($value->{".vlr_apuesta"} > 0) {
                $Concept = 0;
                $Description = '';
                $Reference = '';
                $Value = $value->{".vlr_apuesta"};

                try {
                    $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $TipoTickets->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
                    $Concept = $MandanteDetalle->getValor();
                } catch (Exception $e) {
                }


                $Ingreso = new Ingreso();
                $Ingreso->setTipoId($TipoTickets->getClasificadorId());
                $Ingreso->setDescripcion($Description);
                $Ingreso->setCentrocostoId(0);
                $Ingreso->setDocumento($Reference);
                $Ingreso->setEstado("A");
                $Ingreso->setValor($Value);
                $Ingreso->setImpuesto(0);
                $Ingreso->setRetraccion(0);
                $Ingreso->setUsuarioId($Usuario->puntoventaId);
                $Ingreso->setConceptoId($Concept);
                $Ingreso->setProductotercId($TipoTicketsId);
                $Ingreso->setUsucajeroId($UsuarioMandante->getUsuarioMandante());
                $Ingreso->setProveedortercId(0);
                $Ingreso->fechaCrea = $fechaHoyConHoraSegundos;

                $Ingreso->setUsucreaId(0);
                $Ingreso->setUsumodifId(0);


                $IngresoMySqlDAO->insert($Ingreso);
                $TotalIngresosPropios = $TotalIngresosPropios + $Value;
            }
        }

        $rules = [];
        array_push($rules, array("field" => "usuario.puntoventa_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
        array_push($rules, array("field" => "it_ticket_enc.fecha_pago", "data" => $fechaHoy, "op" => "eq"));


        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);

        $ItTicketEnc = new ItTicketEnc();

        $data = $ItTicketEnc->getTicketsCustom("  SUM(it_ticket_enc.vlr_premio) vlr_premio, usuario.puntoventa_id ", "usuario.puntoventa_id", "asc", $SkeepRows, $MaxRows, $json, true);

        $data = json_decode($data);


        foreach ($data->data as $key => $value) {
            if ($value->{".vlr_premio"} == "") {
                $value->{".vlr_premio"} = 0;
            }


            if ($value->{".vlr_premio"} > 0) {

                $Concept = 0;
                $Description = '';
                $Reference = '';
                $Value = $value->{".vlr_premio"};
                try {
                    $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $TipoPremios->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
                    $Concept = $MandanteDetalle->getValor();
                } catch (Exception $e) {
                }

                $Egreso = new Egreso();
                $Egreso->setTipoId($TipoPremios->getClasificadorId());
                $Egreso->setDescripcion($Description);
                $Egreso->setCentrocostoId(0);
                $Egreso->setDocumento($Reference);
                $Egreso->setEstado("A");
                $Egreso->setValor($Value);
                $Egreso->setImpuesto(0);
                $Egreso->setRetraccion(0);
                $Egreso->setUsuarioId($Usuario->puntoventaId);
                $Egreso->setConceptoId($Concept);
                $Egreso->setProductotercId($TipoPremiosId);
                $Egreso->setUsucajeroId($UsuarioMandante->getUsuarioMandante());
                $Egreso->setProveedortercId(0);

                $Egreso->setUsucreaId(0);
                $Egreso->setUsumodifId(0);
                $Egreso->fechaCrea = $fechaHoyConHoraSegundos;


                $EgresoMySqlDAO->insert($Egreso);

                $TotalEgresosPropios = $TotalEgresosPropios + $Value;
            }
        }
        array_push($final, $array);

        $rules = [];
        array_push($rules, array("field" => "usuario_punto.puntoventa_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
        //array_push($rules, array("field" => "DATE_FORMAT(usuario_recarga.fecha_crea,'%Y-%m-%d') ", "data" => $fechaHoy, "op" => "eq"));
        array_push($rules, array("field" => "time_dimension.timestampint ", "data" => strtotime(($fechaHoy . " 00:00:00")), "op" => "ge"));
        array_push($rules, array("field" => "time_dimension.timestampint", "data" => strtotime(($fechaHoy . " 23:59:59")), "op" => "le"));


        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);

        $UsuarioRecarga = new UsuarioRecarga();

        $data = $UsuarioRecarga->getUsuarioRecargasCustom("  SUM(usuario_recarga.valor) total, usuario_punto.puntoventa_id ", "usuario_punto.puntoventa_id", "asc", $SkeepRows, $MaxRows, $json, true);

        $data = json_decode($data);

        $depositos = 0;

        $array = [];


        $array["Id"] = 0;
        $array["Product"] = "Doradobet Recargas - Pago Notas";
        $array["Bets"] = 0;
        $array["Prize"] = 0;
        foreach ($data->data as $key => $value) {
            if ($value->{".total"} == "") {
                $value->{".total"} = 0;
            }


            if ($value->{".total"} > 0) {
                $Concept = 0;
                $Description = '';
                $Reference = '';
                $depositos = $depositos + $value->{".total"};
            }
        }

        $rules = [];
        array_push($rules, array("field" => "usuario_punto.puntoventa_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
        array_push($rules, array("field" => "DATE_FORMAT(usuario_recarga.fecha_elimina,'%Y-%m-%d') ", "data" => $fechaHoy, "op" => "eq"));
        //array_push($rules, array("field" => "time_dimension.timestampint ", "data" => strtotime(($fechaHoy." 00:00:00")), "op" => "ge"));
        //array_push($rules, array("field" => "time_dimension.timestampint", "data" => strtotime(($fechaHoy." 23:59:59")), "op" => "le"));


        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);

        $UsuarioRecarga = new UsuarioRecarga();

        $data = $UsuarioRecarga->getUsuarioRecargasCustom("  SUM(usuario_recarga.valor) total, usuario_punto.puntoventa_id ", "usuario_punto.puntoventa_id", "asc", $SkeepRows, $MaxRows, $json, true);

        $data = json_decode($data);

        $array = [];


        $array["Id"] = 0;
        $array["Product"] = "Doradobet Recargas - Pago Notas";
        $array["Bets"] = 0;
        $array["Prize"] = 0;
        foreach ($data->data as $key => $value) {
            if ($value->{".total"} == "") {
                $value->{".total"} = 0;
            }


            if ($value->{".total"} > 0) {
                $Concept = 0;
                $Description = '';
                $Reference = '';
                $depositos = $depositos - $value->{".total"};
            }
        }

        try {
            $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $TipoRecargas->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
            $Concept = $MandanteDetalle->getValor();
        } catch (Exception $e) {
        }
        $Ingreso = new Ingreso();
        $Ingreso->setTipoId($TipoRecargas->getClasificadorId());
        $Ingreso->setDescripcion($Description);
        $Ingreso->setCentrocostoId(0);
        $Ingreso->setDocumento($Reference);
        $Ingreso->setEstado("A");
        $Ingreso->setValor($depositos);
        $Ingreso->setImpuesto(0);
        $Ingreso->setRetraccion(0);
        $Ingreso->setUsuarioId($Usuario->puntoventaId);
        $Ingreso->setConceptoId($Concept);
        $Ingreso->setProductotercId($TipoRecargasId);
        $Ingreso->setUsucajeroId($UsuarioMandante->getUsuarioMandante());
        $Ingreso->setProveedortercId(0);
        $Ingreso->fechaCrea = $fechaHoyConHoraSegundos;

        $Ingreso->setUsucreaId(0);
        $Ingreso->setUsumodifId(0);


        $IngresoMySqlDAO->insert($Ingreso);

        $TotalIngresosPropios = $TotalIngresosPropios + $depositos;


        $rules = [];
        array_push($rules, array("field" => "usuario_punto.puntoventa_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
        //array_push($rules, array("field" => "DATE_FORMAT(cuenta_cobro.fecha_pago,'%Y-%m-%d') ", "data" => $fechaHoy, "op" => "eq"));
        array_push($rules, array("field" => "time_dimension.timestampint ", "data" => strtotime(($fechaHoy . " 00:00:00")), "op" => "ge"));
        array_push($rules, array("field" => "time_dimension.timestampint", "data" => strtotime(($fechaHoy . " 23:59:59")), "op" => "le"));


        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);

        $CuentaCobro = new CuentaCobro();

        $data = $CuentaCobro->getCuentasCobroCustom("  SUM(cuenta_cobro.valor) total, usuario_punto.puntoventa_id ", "usuario_punto.puntoventa_id", "asc", $SkeepRows, $MaxRows, $json, true, "");

        $data = json_decode($data);


        foreach ($data->data as $key => $value) {
            if ($value->{".total"} == "") {
                $value->{".total"} = 0;
            }


            if ($value->{".total"} > 0) {

                $Concept = 0;
                $Description = '';
                $Reference = '';
                $Value = $value->{".total"};

                try {
                    $MandanteDetalle = new MandanteDetalle("", $UsuarioMandante->getMandante(), $TipoNotasRetiros->getClasificadorId(), $UsuarioMandante->getPaisId(), 'A');
                    $Concept = $MandanteDetalle->getValor();
                } catch (Exception $e) {
                }

                $Egreso = new Egreso();
                $Egreso->setTipoId($TipoNotasRetiros->getClasificadorId());
                $Egreso->setDescripcion($Description);
                $Egreso->setCentrocostoId(0);
                $Egreso->setDocumento($Reference);
                $Egreso->setEstado("A");
                $Egreso->setValor($Value);
                $Egreso->setImpuesto(0);
                $Egreso->setRetraccion(0);
                $Egreso->setUsuarioId($Usuario->puntoventaId);
                $Egreso->setConceptoId($Concept);
                $Egreso->setProductotercId($TipoNotasRetirosId);
                $Egreso->setUsucajeroId($UsuarioMandante->getUsuarioMandante());
                $Egreso->setProveedortercId(0);

                $Egreso->setUsucreaId(0);
                $Egreso->setUsumodifId(0);
                $Egreso->fechaCrea = $fechaHoyConHoraSegundos;


                $EgresoMySqlDAO->insert($Egreso);

                $TotalEgresosPropios = $TotalEgresosPropios + $Value;
            }
        }


        $rules = [];

        //if ($_SESSION["win_perfil2"] == "PUNTOVENTA") {
        if (true) {

            array_push($rules, array("field" => "egreso.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
        } else {
            array_push($rules, array("field" => "egreso.usucajero_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
        }

        array_push($rules, array("field" => "egreso.fecha_crea", "data" => $fechaHoyConHora, "op" => "ge"));


        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);

        $Egreso = new Egreso();

        $data = $Egreso->getEgresosCustom("  egreso.*,producto_tercero.* ", "egreso.egreso_id", "asc", $SkeepRows, $MaxRows, $json, true);
        $data = json_decode($data);


        foreach ($data->data as $key => $value) {
            if ($value->{"egreso.productoterc_id"} != "0") {
                if ($value->{"producto_tercero.interno"} == "S") {
                    $TotalEgresosPropios = $TotalEgresosPropios + $value->{"egreso.valor"};
                } else {
                    $TotalEgresosProductos = $TotalEgresosProductos + $value->{"egreso.valor"};
                }
            } else {
                $TotalEgresosOtros = $TotalEgresosOtros + $value->{"egreso.valor"};
            }
        }


        $rules = [];

        //if ($_SESSION["win_perfil2"] == "PUNTOVENTA") {
        if (true) {

            array_push($rules, array("field" => "ingreso.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
        } else {
            array_push($rules, array("field" => "ingreso.usucajero_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
        }

        array_push($rules, array("field" => "ingreso.tipo_id", "data" => 0, "op" => "eq"));

        array_push($rules, array("field" => "ingreso.fecha_crea", "data" => $fechaHoyConHora, "op" => "ge"));


        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);

        $Ingreso = new Ingreso();

        $data = $Ingreso->getIngresosCustom("  ingreso.*,producto_tercero.* ", "ingreso.ingreso_id", "asc", $SkeepRows, $MaxRows, $json, true);

        $data = json_decode($data);

        foreach ($data->data as $key => $value) {

            if ($value->{"ingreso.productoterc_id"} != "0") {
                if ($value->{"producto_tercero.interno"} == "S") {
                    $TotalIngresosPropios = $TotalIngresosPropios + $value->{"ingreso.valor"};
                } else {
                    $TotalIngresosProductos = $TotalIngresosProductos + $value->{"ingreso.valor"};
                }
            } else {
                if ($value->{"ingreso.tipo_id"} != "0") {

                    $Tipo = new Clasificador($value->{"ingreso.tipo_id"});

                    switch ($Tipo->getTipo()) {
                        case "TARJCRED":
                            $otrosIngresosTarjetasCreditos += $value->{"ingreso.valor"};
                            break;
                    }
                } else {
                    $TotalIngresosOtros = $TotalIngresosOtros + $value->{"ingreso.valor"};
                }
            }
        }


        $rules = [];

        //if ($_SESSION["win_perfil2"] == "PUNTOVENTA") {
        if (true) {

            array_push($rules, array("field" => "ingreso.usuario_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
        } else {
            array_push($rules, array("field" => "ingreso.usucajero_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
        }

        array_push($rules, array("field" => "ingreso.tipo_id", "data" => 0, "op" => "ne"));

        array_push($rules, array("field" => "ingreso.fecha_crea", "data" => $fechaHoyConHora, "op" => "ge"));


        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);

        $Ingreso = new Ingreso();

        $data = $Ingreso->getIngresosCustom("  ingreso.*,producto_tercero.* ", "ingreso.ingreso_id", "asc", $SkeepRows, $MaxRows, $json, true);

        $data = json_decode($data);

        foreach ($data->data as $key => $value) {
            if ($value->{"ingreso.productoterc_id"} != "0") {
                if ($value->{"producto_tercero.interno"} == "S") {
                    $TotalIngresosPropios = $TotalIngresosPropios + $value->{"ingreso.valor"};
                } else {
                    $TotalIngresosProductos = $TotalIngresosProductos + $value->{"ingreso.valor"};
                }
            } else {
                if ($value->{"ingreso.tipo_id"} != "0") {

                    $Tipo = new Clasificador($value->{"ingreso.tipo_id"});

                    switch ($Tipo->getTipo()) {
                        case "TARJCRED":
                            $otrosIngresosTarjetasCreditos += $value->{"ingreso.valor"};
                            break;
                    }
                } else {
                    $TotalIngresosOtros = $TotalIngresosOtros + $value->{"ingreso.valor"};
                }
            }
        }

        $rules = [];
        array_push($rules, array("field" => "ingreso.usucajero_id", "data" => $UsuarioMandante->getUsuarioMandante(), "op" => "eq"));
        array_push($rules, array("field" => "ingreso.fecha_crea", "data" => date("Y-m-d 00:00:00"), "op" => "ge"));
        array_push($rules, array("field" => "ingreso.tipo_id", "data" => $TipoDineroInicial->getClasificadorId(), "op" => "eq"));


        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);

        $Ingreso = new Ingreso();

        $data = $Ingreso->getIngresosCustom("  ingreso.* ", "ingreso.ingreso_id", "asc", $SkeepRows, $MaxRows, $json, true);

        $data = json_decode($data);

        foreach ($data->data as $key => $value) {
            $dineroInicial = $value->{"ingreso.valor"};
        }

        $UsuarioCierrecaja = new UsuarioCierrecaja();

        $UsuarioCierrecaja->setUsuarioId($UsuarioMandante->getUsuarioMandante());
        $UsuarioCierrecaja->setFechaCierre($fechaHoy);
        $UsuarioCierrecaja->setIngresosPropios($TotalIngresosPropios);
        $UsuarioCierrecaja->setEgresosPropios($TotalEgresosPropios);
        $UsuarioCierrecaja->setIngresosProductos($TotalIngresosProductos);
        $UsuarioCierrecaja->setEgresosProductos($TotalEgresosProductos);
        $UsuarioCierrecaja->setIngresosOtros($TotalIngresosOtros);
        $UsuarioCierrecaja->setEgresosOtros($TotalEgresosOtros);
        $UsuarioCierrecaja->setUsucreaId($UsuarioMandante->getUsuarioMandante());
        $UsuarioCierrecaja->setUsumodifId($UsuarioMandante->getUsuarioMandante());
        $UsuarioCierrecaja->setDineroInicial($dineroInicial);
        $UsuarioCierrecaja->setIngresosTarjetacredito($otrosIngresosTarjetasCreditos);

        $UsuarioCierrecajaMySqlDAO = new UsuarioCierrecajaMySqlDAO($Transaction);
        $UsuarioCierrecajaMySqlDAO->insert($UsuarioCierrecaja);

        $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

        $seguir = true;

        if (date("Y-m-d", strtotime($Usuario->fechaCierrecaja)) >= date("Y-m-d", strtotime($fechaHoy)) && !$esEspecifico) {
            $seguir = false;
        }

        if ($seguir) {

            $Usuario->fechaCierrecaja = $fechaHoyConHoraSegundos;

            $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);
            $UsuarioMySqlDAO->update($Usuario);


            $Transaction->commit();

            return true;
        } else {
            return false;
        }
    }
}

?>
