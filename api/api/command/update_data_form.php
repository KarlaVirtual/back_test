<?php

use Backend\dto\CategoriaMandante;
use Backend\dto\Ciudad;
use Backend\dto\EquipoFavorito;
use Backend\dto\Usuario;
use Backend\dto\Registro;
use Backend\dto\UsuarioLog2;
use Backend\dto\Clasificador;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioOtrainfo;
use Backend\dto\MandanteDetalle;
use Backend\dto\UsuarioVerificacion;
use Backend\mysql\UsuarioLog2MySqlDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Deporte;
use Backend\dto\Mandante;
use Backend\dto\Template;
use Backend\mysql\RegistroMySqlDAO;
use Backend\mysql\UsuarioMandanteMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\UsuarioOtrainfoMySqlDAO;
use Backend\mysql\UsuarioVerificacionMySqlDAO;

/**
 * Divide un nombre completo en nombres y apellidos.
 *
 * Esta función toma un nombre completo como entrada y lo separa en nombres y apellidos
 * dividiéndolo en dos partes aproximadamente iguales. Se asume que los nombres y apellidos
 * están separados por espacios y que el nombre puede tener uno o más espacios en blanco adicionales.
 *
 * @param string $value El nombre completo a procesar.
 * @return array Un arreglo asociativo con las siguientes claves:
 *               - first_name: El primer nombre.
 *               - second_name: El segundo nombre (si existe, o vacío en caso contrario).
 *               - last_name: El primer apellido.
 *               - second_last_name: El segundo apellido (si existe, o vacío en caso contrario).
 * @throws no No contiene manejo de excepciones.
 */

function getNamesData($value)
{
    $parts = explode(' ', str_replace('  ', '', $value));
    $names = oldCount($parts) % 2 === 0 ? array_slice($parts, 0, oldCount($parts) / 2) : array_slice($parts, 0, ceil(oldCount($parts) / 2));
    $last_names = oldCount($parts) % 2 === 0 ? array_slice($parts, oldCount($parts) / 2, oldCount($parts)) : array_slice($parts, ceil(oldCount($parts) / 2), oldCount($parts));

    return [
        'first_name' => $names[0],
        'second_name' => $names[1] . (isset($names[2]) ? ' ' . $names[2] : ''),
        'last_name' => $last_names[0],
        'second_last_name' => $last_names[1] . (isset($last_names[2]) ? ' ' . $last_names[2] : '')
    ];
}

/**
 * command/update_data_form
 *
 * Actualizar información dentro de un formulario
 *
 * @param string $name_info : Nombre completo del usuario
 * @param string $email : Email del usuario
 * @param string $phone : Telefono del usuario
 * @param string $adress : Direccion del usuario
 * @param string $favoritesport : Deporte mas usado por el usuario
 * @param string $favoritecasino : Juego de casino mas usado por el usuario
 * @param string $departmentupdate : Departamento actualizado del usuario
 * @param string $cityupdate : Ciudad a actualizar del usuario
 * @param string $favorite_team : Equipo favorito del usuario
 *
 * El objeto $response es un array con los siguientes atributos:
 *  - *code* (int): Codigo de error desde el proveedor 0 si no hay ningun error
 *
 * @throws Exception El email ya esta registrado
 * @throws Exception El celular ya existe
 * @throws Exception Su cuenta ya se encuentra en estado de verificacion
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */


/* extrae datos de un objeto JSON y obtiene información sobre nombres. */
$params = $json->params;

$name_info = getNamesData($params->name);

$email = $params->email;
$phone = $params->phone;

/* Asignación de variables a partir de parámetros para actualizar información de usuario. */
$adress = $params->adress;
$favoritesport = $params->favoritesport;
$favoritecasino = $params->favoritecasino;
$departmentupdate = $params->departmentupdate;
$cityupdate = $params->cityupdate;
$favorite_team = $params->favorite_team;


/* asigna nombres y crea un objeto UsuarioMandante usando información de sesión. */
$first_name = $name_info['first_name'];
$second_name = $name_info['second_name'];
$last_name = $name_info['last_name'];
$second_last_name = $name_info['second_last_name'];

$UsuarioMandante = new UsuarioMandante($json->session->usuario);


/* instancia objetos relacionados con un usuario y actualiza su login. */
$Usuario = new Usuario($UsuarioMandante->usuarioMandante);
$Registro = new Registro('', $Usuario->usuarioId);
$UsuarioOtraInfo = new UsuarioOtrainfo($Usuario->usuarioId);

$currentLogin = $Usuario->login;
$Usuario->login = $email;

/*Obtención información de contacto*/
$Registro->celular = $phone;


if ($Usuario->login !== $currentLogin && $Usuario->exitsLogin()) throw new Exception('El email ya esta registrado', '19001');

/*Verificación validez de celular*/
if ($Registro->celular !== $phone && $Registro->existeCelular()) throw new Exception('El celular ya existe', '19002');


/* Revisión estado de verificación */
try {
    $UsuarioVerificacion = new UsuarioVerificacion('', $Usuario->usuarioId, 'P', 'USUACTUALIZACIONDATOS');
} catch (Exception $ex) {
}

if (isset($UsuarioVerificacion)) throw new Exception('Su cuenta ya se encuentra en estado de verificacion', 100086);


/* crea objetos para manejar configuraciones y obtener un valor de aprobación. */
$needs_approval = 'A';

$ConfigurationEnvironment = new ConfigurationEnvironment();

try {
    $Clasificador = new Clasificador('', 'APPCHANPERSONALINF');
    $MandanteDetalle = new MandanteDetalle('', $Usuario->mandante, $Clasificador->getClasificadorId(), $Usuario->paisId, 'A');

    $needs_approval = $MandanteDetalle->getValor();
} catch (Exception $ex) {
    /* Manejo de excepciones en PHP, captura de errores sin realizar acciones adicionales. */

}


/* Código que inicializa objetos DAO para manejar transacciones en MySQL. */
$UsuarioVerificacionMySqlDAO = new UsuarioVerificacionMySqlDAO();
$Transaction = $UsuarioVerificacionMySqlDAO->getTransaction();

$UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);
$RegistroMySqlDAO = new RegistroMySqlDAO($Transaction);
$UsuarioMandanteMySqlDAO = new UsuarioMandanteMySqlDAO($Transaction);

/* Se inicializan variables para rastrear cambios en el usuario y su información. */
$UsuarioOtrainfoMySqlDAO = new UsuarioOtrainfoMySqlDAO($Transaction);

$changes_user = false;
$changes_register = false;
$changes_other_info = false;
$changes_usupartner = false;


/* Crea y guarda un registro de verificación de usuario bajo ciertas condiciones. */
if (in_array($UsuarioMandante->mandante, ['15']) && in_array($UsuarioMandante->paisId, ['102']) && $needs_approval !== 'I') {
    try {
        $Clasificador = new Clasificador('', 'VERIFICAMANUAL');
    } catch (Exception $ex) {
    }

    $UsuarioVerificacion = new UsuarioVerificacion();
    $UsuarioVerificacion->setUsuarioId($UsuarioMandante->usuarioMandante);
    $UsuarioVerificacion->setMandante($UsuarioMandante->mandante);
    $UsuarioVerificacion->setPaisId($UsuarioMandante->paisId);
    $UsuarioVerificacion->setTipo('USUACTUALIZACIONDATOS');
    $UsuarioVerificacion->setEstado('NA');
    $UsuarioVerificacion->setObservacion('');
    $UsuarioVerificacion->setUsucreaId($UsuarioMandante->usuarioMandante);
    $UsuarioVerificacion->setClasificadorId($Clasificador->getClasificadorId() ?: 0);

    $UsuarioVerificacionMySqlDAO = new UsuarioVerificacionMySqlDAO($Transaction);
    $ID = $UsuarioVerificacionMySqlDAO->insert($UsuarioVerificacion);
}


/*Actualización del primer nombre*/
if (!empty($first_name) && $first_name !== $Registro->nombre1) {
    if ($needs_approval === 'I') {
        $Registro->nombre1 = $first_name;
        $Registro->nombre = $Registro->nombre1 . ' ' . $Registro->nombre2 . ' ' . $Registro->apellido1 . ' ' . $Registro->apellido2;
        $Usuario->nombre = $Registro->nombre;
        $UsuarioMandante->nombres = $Registro->nombre1 . ' ' . $Registro->nombre2;
        $UsuarioMandante->apellidos = $Registro->apellido1 . ' ' . $Registro->apellido2;

        $changes_user = true;
        $changes_register = true;
        $changes_usupartner = true;
    } else {
        $UsuarioLog = new UsuarioLog2();
        $UsuarioLog->setUsuarioId($Usuario->usuarioId);
        $UsuarioLog->setUsuarioIp('');
        $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
        $UsuarioLog->setUsuariosolicitaIp($json->session->usuarioip);
        $UsuarioLog->setTipo('USUNOMBRE1');
        $UsuarioLog->setEstado('P');
        $UsuarioLog->setValorAntes($Registro->nombre1);
        $UsuarioLog->setValorDespues($first_name);
        $UsuarioLog->setUsucreaId(0);
        $UsuarioLog->setUsumodifId(0);
        if (in_array($UsuarioMandante->mandante, ['15']) && in_array($UsuarioMandante->paisId, ['102'])) $UsuarioLog->setSversion($ID);

        $UsuarioLog2MySqlDAO = new UsuarioLog2MySqlDAO($Transaction);
        $UsuarioLog2MySqlDAO->insert($UsuarioLog);
    }
}


/* No se proporcionó ningún código para explicar. Por favor, compártelo. */
if (!empty($second_name) && $second_name !== $Registro->nombre2) {
    if ($needs_approval === 'I') {
        $Registro->nombre2 = $second_name;
        $Registro->nombre = $Registro->nombre1 . ' ' . $Registro->nombre2 . ' ' . $Registro->apellido1 . ' ' . $Registro->apellido2;
        $Usuario->nombre = $Registro->nombre;
        $UsuarioMandante->nombres = $Registro->nombre1 . ' ' . $Registro->nombre2;
        $UsuarioMandante->apellidos = $Registro->apellido1 . ' ' . $Registro->apellido2;

        $changes_user = true;
        $changes_register = true;
        $changes_usupartner = true;
    } else {
        $UsuarioLog = new UsuarioLog2();
        $UsuarioLog->setUsuarioId($Usuario->usuarioId);
        $UsuarioLog->setUsuarioIp('');
        $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
        $UsuarioLog->setUsuariosolicitaIp($jso->session->usuarioip);
        $UsuarioLog->setTipo('USUNOMBRE2');
        $UsuarioLog->setEstado('P');
        $UsuarioLog->setValorAntes($Registro->nombre2);
        $UsuarioLog->setValorDespues($second_name);
        $UsuarioLog->setUsucreaId(0);
        $UsuarioLog->setUsumodifId(0);
        if (in_array($UsuarioMandante->mandante, ['15']) && in_array($UsuarioMandante->paisId, ['102'])) $UsuarioLog->setSversion($ID);

        $UsuarioLog2MySqlDAO = new UsuarioLog2MySqlDAO($Transaction);
        $UsuarioLog2MySqlDAO->insert($UsuarioLog);
    }
}


/*Actualización primer apellido */
if (!empty($last_name) && $last_name !== $Registro->apellido1) {
    if ($needs_approval === 'I') {
        $Registro->apellido1 = $last_name;
        $Registro->nombre = $Registro->nombre1 . ' ' . $Registro->nombre2 . ' ' . $Registro->apellido1 . ' ' . $Registro->apellido2;
        $Usuario->nombre = $Registro->nombre;
        $UsuarioMandante->nombres = $Registro->nombre1 . ' ' . $Registro->nombre2;
        $UsuarioMandante->apellidos = $Registro->apellido1 . ' ' . $Registro->apellido2;

        $changes_user = true;
        $changes_register = true;
        $changes_usupartner = true;
    } else {
        $UsuarioLog = new UsuarioLog2();
        $UsuarioLog->setUsuarioId($Usuario->usuarioId);
        $UsuarioLog->setUsuarioIp('');
        $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
        $UsuarioLog->setUsuariosolicitaIp($jso->session->usuarioip);
        $UsuarioLog->setTipo('USUAPELLIDO1');
        $UsuarioLog->setEstado('P');
        $UsuarioLog->setValorAntes($Registro->apellido1);
        $UsuarioLog->setValorDespues($last_name);
        $UsuarioLog->setUsucreaId(0);
        $UsuarioLog->setUsumodifId(0);
        if (in_array($UsuarioMandante->mandante, ['15']) && in_array($UsuarioMandante->paisId, ['102'])) $UsuarioLog->setSversion($ID);

        $UsuarioLog2MySqlDAO = new UsuarioLog2MySqlDAO($Transaction);
        $UsuarioLog2MySqlDAO->insert($UsuarioLog);
    }
}


/* Actualización segundo apellido */
if (!empty($second_last_name) && $second_last_name !== $Registro->apellido2) {
    if ($needs_approval === 'I') {
        $Registro->apellido2 = $second_last_name;
        $Registro->nombre = $Registro->nombre1 . ' ' . $Registro->nombre2 . ' ' . $Registro->apellido1 . ' ' . $Registro->apellido2;
        $Usuario->nombre = $Registro->nombre;
        $UsuarioMandante->nombres = $Registro->nombre1 . ' ' . $Registro->nombre2;
        $UsuarioMandante->apellidos = $Registro->apellido1 . ' ' . $Registro->apellido2;

        $changes_user = true;
        $changes_register = true;
        $changes_usupartner = true;
    } else {
        $UsuarioLog = new UsuarioLog2();
        $UsuarioLog->setUsuarioId($Usuario->usuarioId);
        $UsuarioLog->setUsuarioIp('');
        $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
        $UsuarioLog->setUsuariosolicitaIp($jso->session->usuarioip);
        $UsuarioLog->setTipo('USUAPELLIDO2');
        $UsuarioLog->setEstado('P');
        $UsuarioLog->setValorAntes($Registro->apellido2);
        $UsuarioLog->setValorDespues($second_last_name);
        $UsuarioLog->setUsucreaId(0);
        $UsuarioLog->setUsumodifId(0);
        if (in_array($UsuarioMandante->mandante, ['15']) && in_array($UsuarioMandante->paisId, ['102'])) $UsuarioLog->setSversion($ID);

        $UsuarioLog2MySqlDAO = new UsuarioLog2MySqlDAO($Transaction);
        $UsuarioLog2MySqlDAO->insert($UsuarioLog);
    }
}


/* Actualización dirección email */
if (!empty($email) && $email !== $currentLogin) {
    if ($needs_approval === 'I') {
        $Usuario->login = $email;
        $Registro->email = $email;

        $changes_user = true;
        $changes_register = true;
    } else {
        $UsuarioLog = new UsuarioLog2();
        $UsuarioLog->setUsuarioId($Usuario->usuarioId);
        $UsuarioLog->setUsuarioIp('');
        $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
        $UsuarioLog->setUsuariosolicitaIp($jso->session->usuarioip);
        $UsuarioLog->setTipo('USUEMAIL');
        $UsuarioLog->setEstado('P');
        $UsuarioLog->setValorAntes($currentLogin);
        $UsuarioLog->setValorDespues($email);
        $UsuarioLog->setUsucreaId(0);
        $UsuarioLog->setUsumodifId(0);
        if (in_array($UsuarioMandante->mandante, ['15']) && in_array($UsuarioMandante->paisId, ['102'])) $UsuarioLog->setSversion($ID);

        $UsuarioLog2MySqlDAO = new UsuarioLog2MySqlDAO($Transaction);
        $UsuarioLog2MySqlDAO->insert($UsuarioLog);
    }
}


/* No se presentó ningún código para analizar o explicar. */
if (!empty($phone) && $phone !== $Registro->celular) {
    if ($needs_approval === 'I') {
        $Registro->celular = $phone;

        $changes_register = true;
    } else {
        $UsuarioLog = new UsuarioLog2();
        $UsuarioLog->setUsuarioId($Usuario->usuarioId);
        $UsuarioLog->setUsuarioIp('');
        $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
        $UsuarioLog->setUsuariosolicitaIp($jso->session->usuarioip);
        $UsuarioLog->setTipo('USUCELULAR');
        $UsuarioLog->setEstado('P');
        $UsuarioLog->setValorAntes($Registro->celular);
        $UsuarioLog->setValorDespues($phone);
        $UsuarioLog->setUsucreaId(0);
        $UsuarioLog->setUsumodifId(0);
        if (in_array($UsuarioMandante->mandante, ['15']) && in_array($UsuarioMandante->paisId, ['102'])) $UsuarioLog->setSversion($ID);

        $UsuarioLog2MySqlDAO = new UsuarioLog2MySqlDAO($Transaction);
        $UsuarioLog2MySqlDAO->insert($UsuarioLog);
    }
}


/* No se presentó ningún código para explicar. Por favor, proporciona el código. */
if (!empty($adress) && $adress !== $Registro->direccion) {
    if ($needs_approval === 'I') {
        $Registro->direccion = $adress;

        $changes_register = true;
    } else {
        $UsuarioLog = new UsuarioLog2();
        $UsuarioLog->setUsuarioId($Usuario->usuarioId);
        $UsuarioLog->setUsuarioIp('');
        $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
        $UsuarioLog->setUsuariosolicitaIp($jso->session->usuarioip);
        $UsuarioLog->setTipo('USUDIRECCION');
        $UsuarioLog->setEstado('P');
        $UsuarioLog->setValorAntes($Registro->direccion);
        $UsuarioLog->setValorDespues($adress);
        $UsuarioLog->setUsucreaId(0);
        $UsuarioLog->setUsumodifId(0);
        if (in_array($UsuarioMandante->mandante, ['15']) && in_array($UsuarioMandante->paisId, ['102'])) $UsuarioLog->setSversion($ID);

        $UsuarioLog2MySqlDAO = new UsuarioLog2MySqlDAO($Transaction);
        $UsuarioLog2MySqlDAO->insert($UsuarioLog);
    }
}


/*Actualización deporter favoritos*/
if (!empty($favoritesport) && $favoritesport !== $UsuarioOtraInfo->deporteFavorito) {
    if ($needs_approval === 'I') {
        $UsuarioOtraInfo->deporteFavorito = $favoritesport;

        $changes_register = true;
    } else {
        $UsuarioLog = new UsuarioLog2();
        $UsuarioLog->setUsuarioId($Usuario->usuarioId);
        $UsuarioLog->setUsuarioIp('');
        $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
        $UsuarioLog->setUsuariosolicitaIp($jso->session->usuarioip);
        $UsuarioLog->setTipo('FVSPORT');
        $UsuarioLog->setEstado('P');
        $UsuarioLog->setValorAntes($UsuarioOtraInfo->deporteFavorito);
        $UsuarioLog->setValorDespues($favoritesport);
        $UsuarioLog->setUsucreaId(0);
        $UsuarioLog->setUsumodifId(0);
        if (in_array($UsuarioMandante->mandante, ['15']) && in_array($UsuarioMandante->paisId, ['102'])) $UsuarioLog->setSversion($ID);

        $UsuarioLog2MySqlDAO = new UsuarioLog2MySqlDAO($Transaction);
        $UsuarioLog2MySqlDAO->insert($UsuarioLog);
    }
}


/*Actualización producto de casino favorito*/
if (!empty($favoritecasino) && $favoritecasino !== $UsuarioOtraInfo->casinoFavorito) {
    if ($needs_approval === 'I') {
        $UsuarioOtraInfo->casinoFavorito = $favoritecasino;

        $changes_register = true;
    } else {
        $UsuarioLog = new UsuarioLog2();
        $UsuarioLog->setUsuarioId($Usuario->usuarioId);
        $UsuarioLog->setUsuarioIp('');
        $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
        $UsuarioLog->setUsuariosolicitaIp($jso->session->usuarioip);
        $UsuarioLog->setTipo('FVCASINO');
        $UsuarioLog->setEstado('P');
        $UsuarioLog->setValorAntes($UsuarioOtraInfo->casinoFavorito);
        $UsuarioLog->setValorDespues($favoritecasino);
        $UsuarioLog->setUsucreaId(0);
        $UsuarioLog->setUsumodifId(0);
        if (in_array($UsuarioMandante->mandante, ['15']) && in_array($UsuarioMandante->paisId, ['102'])) $UsuarioLog->setSversion($ID);

        $UsuarioLog2MySqlDAO = new UsuarioLog2MySqlDAO($Transaction);
        $UsuarioLog2MySqlDAO->insert($UsuarioLog);
    }
}


/*Actualización ciudad de residencia*/
if (!empty($cityupdate) && $Registro->ciudadId) {
    if ($needs_approval === 'I') {
        $Ciudad = new Ciudad($cityupdate);
        $Registro->ciudadId = $Ciudad->ciudadId;
        $changes_register = true;
    } else {
        $Ciudad = new Ciudad($cityupdate);
        $UsuarioLog = new UsuarioLog2();
        $UsuarioLog->setUsuarioId($Usuario->usuarioId);
        $UsuarioLog->setUsuarioIp('');
        $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
        $UsuarioLog->setUsuariosolicitaIp($jso->session->usuarioip);
        $UsuarioLog->setTipo('USUCIUDAD');
        $UsuarioLog->setEstado('P');
        $UsuarioLog->setValorAntes($Registro->ciudadId);
        $UsuarioLog->setValorDespues($Ciudad->ciudadId);
        $UsuarioLog->setUsucreaId(0);
        $UsuarioLog->setUsumodifId(0);
        if (in_array($UsuarioMandante->mandante, ['15']) && in_array($UsuarioMandante->paisId, ['102'])) $UsuarioLog->setSversion($ID);

        $UsuarioLog2MySqlDAO = new UsuarioLog2MySqlDAO($Transaction);
        $UsuarioLog2MySqlDAO->insert($UsuarioLog);
    }
}


/* Bloque de actualización del equipo favorito*/
if (!empty($favorite_team) && $favorite_team !== $Usuario->equipoId) {
    if ($needs_approval === 'I') {
        $EquipoFavorito = new EquipoFavorito($favorite_team);
        $Usuario->equipoId = $EquipoFavorito->equipoId;

        $changes_user = true;
    } else {
        $EquipoFavorito = new EquipoFavorito($favorite_team);
        $UsuarioLog = new UsuarioLog2();
        $UsuarioLog->setUsuarioId($Usuario->usuarioId);
        $UsuarioLog->setUsuarioIp('');
        $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
        $UsuarioLog->setUsuariosolicitaIp($jso->session->usuarioip);
        $UsuarioLog->setTipo('FVTEAM');
        $UsuarioLog->setEstado('P');
        $UsuarioLog->setValorAntes($Usuario->equipoId);
        $UsuarioLog->setValorDespues($EquipoFavorito->equipoId);
        $UsuarioLog->setUsucreaId(0);
        $UsuarioLog->setUsumodifId(0);
        if (in_array($UsuarioMandante->mandante, ['0']) && in_array($UsuarioMandante->paisId, ['173'])) $UsuarioLog->setSversion($ID);

        $UsuarioLog2MySqlDAO = new UsuarioLog2MySqlDAO($Transaction);
        $UsuarioLog2MySqlDAO->insert($UsuarioLog);
    }
}


/*Actualización data conjunta del usuario*/
if ($changes_user) $UsuarioMySqlDAO->update($Usuario);

/*Actualización data conjunta del registro*/
if ($changes_register) $RegistroMySqlDAO->update($Registro);

/*Actualización data conjunta del UsuarioMandante*/
if ($changes_usupartner) $UsuarioMandanteMySqlDAO->update($UsuarioMandante);

/*Actualización data conjunta del UsuarioOtraInfo*/
if ($changes_other_info) $UsuarioOtrainfoMySqlDAO->update($UsuarioOtraInfo);


/*Commit de la transacción*/
$Transaction->commit();

if ($needs_approval === 'A') {
    try {
        $Mandante = new Mandante($Usuario->mandante);
        $Clasificador = new Clasificador('', 'TEMPEMAIL');
        $Temmplate = new Template('', $Usuario->mandante, $Clasificador->clasificadorId, $Usuario->paisId, strtolower($Usuario->idioma));
        $html = $Temmplate->templateHtml;

        $title = '';

        switch ($Usuario->idioma) {
            case 'PT':
                $title = 'Atualização De Dados ';
                break;
            case 'EN':
                $title = 'Update data ';
                break;
            default:
                $title = 'Actualizar Datos ';
                break;
        }

        $code = urlencode($ConfigurationEnvironment->encrypt("{$ID}_" . time()));
        $link = $Mandante->baseUrl . "validar-actualizacion/{$code}";
        $link = "<a href='{$link}'>{$link}</a>";

        $html = str_replace('#userid#', $Usuario->usuarioId, $html);
        $html = str_replace('#name#', $Registro->nombre1, $html);
        $html = str_replace('#identification#', $Registro->cedula, $html);
        $html = str_replace('#lastname#', $Registro->apellido1, $html);
        $html = str_replace('#login#', $Usuario->login, $html);
        $html = str_replace('#fullname#', $Usuario->nombre, $html);
        $html = str_replace('#link#', $link, $html);

        $ConfigurationEnvironment->EnviarCorreoVersion3($email, '', '', $title, '', $title, $html, '', '', '', $Usuario->mandante);
    } catch (Exception $ex) {
    }
}


/* Inicializa un arreglo vacío y asigna un código de respuesta 0. */
$response = [];
$response['code'] = 0;
?>
