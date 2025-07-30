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
 * Este script genera un menú dinámico basado en los permisos y datos del usuario.
 *
 * @param object $json Objeto JSON que contiene los parámetros de la solicitud, incluyendo la sesión del usuario.
 * @param object $json->session Objeto JSON que contiene los parámetros de la sesión del usuario.
 * @param int $json->session->usuario ID del usuario en la sesión.
 *
 * @return array $response Respuesta en formato JSON que incluye:
 *                         - code: Código de estado de la respuesta (0 para éxito).
 *                         - data: Datos estructurados del menú, incluyendo:
 *                           - menus: Lista de menús y submenús con detalles como título, icono y permisos.
 *                           - subid: Identificador único generado a partir del ID de sesión.
 */

/* Se crea un usuario y se obtienen su saldo, moneda y país. */
$UsuarioMandante = new UsuarioMandante($json->session->usuario);

$saldo = $UsuarioMandante->getSaldo();
$moneda = $UsuarioMandante->getMoneda();
$paisId = $UsuarioMandante->getPaisId();

$Mandante = new Mandante($UsuarioMandante->getMandante());


/* Verifica si el mandante es propio y obtiene los menús del usuario correspondiente. */
if ($Mandante->propio == "S") {

    $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

    $menus = $Usuario->getMenus();

}


/* Se inicializan variables para construir un menú y sus submenús. */
$menu = "";
$menu_contador = 0;

$menu_array_final = [];
$menu_array = [];
$submenus = [];

foreach ($menus as $row) {

    /* Crea un menú y submenús dinámicamente a partir de datos de base de datos. */
    $submenu = [];

    if ($menu == "") {
        $menu = $row["d.menu_id"];
        $menu_provisional = array(
            "MENU_ID" => $row["d.menu_id"],
            "title" => $row["d.menu"],
            "icon" => 'ion-document',
            "subMenu" => []
        );
        $submenu = array(
            "fixedHref" => str_replace('.php', '', $row["b.pagina"]),
            "title" => $row["b.submenu"],
            "editar" => $row["a.editar"] === 'true' ? true : false,
            "eliminar" => $row["a.eliminar"] === 'true' ? true : false,
            "adicionar" => $row["a.adicionar"] === 'true' ? true : false,
        );
        array_push($submenus, $submenu);
        $menu_array = $menu_provisional;

    } else {

        /* verifica coincidencias en un menú y crea un submenú asociado. */
        if ($menu == $row["d.menu_id"]) {
            $submenu = array(
                "fixedHref" => str_replace('.php', '', $row["b.pagina"]),
                "title" => $row["b.submenu"],
                "editar" => $row["a.editar"] === 'true' ? true : false,
                "eliminar" => $row["a.eliminar"] === 'true' ? true : false,
                "adicionar" => $row["a.adicionar"] === 'true' ? true : false,
            );
            array_push($submenus, $submenu);

        } else {


            /* Se agrupan submenús en un array y se preparan para otra estructura. */
            $menu_array["subMenu"] = $submenus;
            $menu = "";

            array_push($menu_array_final, $menu_array);
            $menu_array = [];
            $submenus = [];


            /* Se crea un arreglo PHP para un menú provisional con ID, título e icono. */
            $menu_provisional = array(
                "MENU_ID" => $row["d.menu_id"],
                "title" => $row["d.menu"],
                "icon" => 'ion-document',
                "subMenu" => []
            );


            /* Crea un submenú con opciones según valores de la base de datos. */
            $submenu = array(
                "fixedHref" => str_replace('.php', '', $row["b.pagina"]),
                "title" => $row["b.submenu"],
                "editar" => $row["a.editar"] === 'true' ? true : false,
                "eliminar" => $row["a.eliminar"] === 'true' ? true : false,
                "adicionar" => $row["a.adicionar"] === 'true' ? true : false,
            );


            /* Agrega un elemento al array $submenus y asigna $menu_provisional a $menu_array. */
            array_push($submenus, $submenu);
            $menu_array = $menu_provisional;
        }

    }

}

/* crea un menú y establece permisos booleanos para diferentes secciones. */
$menu_array["subMenu"] = $submenus;
array_push($menu_array_final, $menu_array);

$perm_punto_venta = !false;
$perm_concesionario = !false;
$perm_depto = !false;

/* Se asigna verdadero a las variables de permiso para ciudad y país. */
$perm_ciudad = !false;
$perm_pais = !false;

/*if ($_SESSION["win_perfil"] == "SA" or $_SESSION["win_perfil"] == "ADMIN" or $_SESSION["win_perfil"] == "ADMIN2" or $_SESSION["win_perfil"] == "ADMIN3" or $_SESSION["win_perfil"] == "CONCESIONARIO" or $_SESSION["win_perfil"] == "CONCESIONARIO2" or $_SESSION["win_perfil"] == "OPERCOM") {
$perm_punto_venta = true;
}

if ($_SESSION["win_perfil"] == "SA" or $_SESSION["win_perfil"] == "ADMIN" or $_SESSION["win_perfil"] == "ADMIN2" or $_SESSION["win_perfil"] == "ADMIN3" or $_SESSION["win_perfil"] == "CONCESIONARIO" or $_SESSION["win_perfil"] == "OPERCOM") {
$perm_concesionario = true;
}

if ($_SESSION["win_perfil"] == "SA" or $_SESSION["win_perfil"] == "ADMIN" or $_SESSION["win_perfil"] == "ADMIN2" or $_SESSION["win_perfil"] == "ADMIN3" or $_SESSION["win_perfil"] == "CONCESIONARIO" or $_SESSION["win_perfil"] == "CONCESIONARIO2" or $_SESSION["win_perfil"] == "OPERCOM") {
$perm_depto = true;
}

if ($_SESSION["win_perfil"] == "SA" or $_SESSION["win_perfil"] == "ADMIN" or $_SESSION["win_perfil"] == "ADMIN2" or $_SESSION["win_perfil"] == "ADMIN3" or $_SESSION["win_perfil"] == "CONCESIONARIO" or $_SESSION["win_perfil"] == "CONCESIONARIO2" or $_SESSION["win_perfil"] == "OPERCOM") {
$perm_ciudad = true;
}

if ($_SESSION["win_perfil"] == "SA" or $_SESSION["win_perfil"] == "ADMIN" or $_SESSION["win_perfil"] == "ADMIN2" or $_SESSION["win_perfil"] == "ADMIN3" or $_SESSION["win_perfil"] == "OPERCOM") {
$perm_pais = true;
}*/


/* inicializa un arreglo de respuesta y define estructuras para datos y perfil. */
$response = array();

$response['code'] = 0;

$data = array();
$profile = array();

/* Crea un arreglo de perfil y estructura datos para una respuesta JSON. */
$profile_id = array();

$data["menus"] = $menu_array_final;

$response["data"] = array(
    "data" => $data,
    "subid" => "7040" . $json->session->sid . "4",
);
