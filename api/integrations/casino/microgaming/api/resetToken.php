<?php
/**
 * Este archivo contiene un script para resetear los tokens de usuarios en la API de casino 'microgaming'.
 * También registra los cambios realizados en los tokens en un log para su seguimiento.
 *
 * @category   Casino
 * @package    API
 * @subpackage Integrations
 * @author     Daniel Tamayo <it@virtualsoft.tech>
 * @version    1.0.0
 * @since      2025-05-11
 */

/**
 * Variables globales utilizadas en el script:
 *
 * @var mixed  $usuariotoken1          Objeto que representa el token del usuario con ID 174.
 * @var mixed  $usuariotoken2          Objeto que representa el token del usuario con ID 175.
 * @var mixed  $UsumandanteLog         Objeto que registra los cambios realizados en los tokens.
 * @var mixed  $UsuarioTokenMySqlDAO   Objeto que maneja las operaciones de base de datos para los tokens de usuario.
 * @var mixed  $UsumandanteLogMySqlDAO Objeto que maneja las operaciones de base de datos para los logs de usuario mandante.
 * @var string $token1                 Token generado para el usuario con ID 174.
 * @var string $token2                 Token generado para el usuario con ID 175.
 */

ini_set('display_errors', 'OFF');

require(__DIR__ . '../../../../../vendor/autoload.php');

use Backend\dto\UsumandanteLog;
use Backend\integrations\casino\Microgaming;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioToken;
use Backend\mysql\UsuarioTokenMySqlDAO;
use Backend\mysql\UsumandanteLogMySqlDAO;


$usuariotoken1 = new UsuarioToken("", "17", "174");

$UsumandanteLog = new UsumandanteLog();
$UsumandanteLog->setUsuarioId(174);
$UsumandanteLog->setProveedorId(17);
$UsumandanteLog->setTipo("TOKEN");
$UsumandanteLog->setUsucreaId(0);
$UsumandanteLog->setUsumodifId(0);
$UsumandanteLog->setValorAntes($usuariotoken1->getToken());

$usuariotoken1->setToken($usuariotoken1->createToken());


$UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

$UsuarioTokenMySqlDAO->update($usuariotoken1);

$UsuarioTokenMySqlDAO->getTransaction()->commit();

$UsumandanteLog->setValorDespues($usuariotoken1->getToken());


$UsumandanteLogMySqlDAO = new UsumandanteLogMySqlDAO();

$UsumandanteLogMySqlDAO->insert($UsumandanteLog);

$UsumandanteLogMySqlDAO->getTransaction()->commit();


$usuariotoken2 = new UsuarioToken("", "17", "175");

$UsumandanteLog = new UsumandanteLog();
$UsumandanteLog->setUsuarioId(175);
$UsumandanteLog->setProveedorId(17);
$UsumandanteLog->setTipo("TOKEN");
$UsumandanteLog->setUsucreaId(0);
$UsumandanteLog->setUsumodifId(0);
$UsumandanteLog->setValorAntes($usuariotoken2->getToken());

$usuariotoken2->setToken($usuariotoken2->createToken());

$UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

$UsuarioTokenMySqlDAO->update($usuariotoken2);

$UsuarioTokenMySqlDAO->getTransaction()->commit();

$UsumandanteLog->setValorDespues($usuariotoken2->getToken());


$UsumandanteLogMySqlDAO = new UsumandanteLogMySqlDAO();

$UsumandanteLogMySqlDAO->insert($UsumandanteLog);

$UsumandanteLogMySqlDAO->getTransaction()->commit();

$token1 = $usuariotoken1->getToken();
$token2 = $usuariotoken2->getToken();

?>

<style>
    td {
        padding: 15px;
    }
</style>

<table>
    <thead>
    <tr style="
    font-weight:  bold;
">
        <td>
            Usuario
        </td>
        <td>
            Moneda
        </td>
        <td>
            Token
        </td>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>
            Usuario 174
        </td>
        <td>
            USD
        </td>
        <td>
            <?php
            echo $token1; ?>
        </td>
    </tr>
    <tr>
        <td>
            Usuario 175
        </td>
        <td>
            PEN
        </td>
        <td>
            <?php
            echo $token2; ?>
        </td>
    </tr>
    </tbody>

</table>
