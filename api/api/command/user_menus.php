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
use Backend\dto\PerfilSubmenu;
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
use Backend\dto\UsuarioMensajecampana;
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
use Backend\dto\PaisMandante;
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
 * command/user_menus
 *
 * @param object $UsuarioMandanteSite : contiene la información del partner vinculado al usuario
 *
 * @return object El objeto $response es un array con los siguientes atributos:
 *  - *code* (int): Codigo de error desde el proveedor 0 en caso de exito
 *  - *data* (array): Devuelve los menus relacionados a la mandante y el usuario
 *
 * @throws Exception Si falla la obtención de parametros o si no existe token vinculado al usuario
 *
 * @access public
 * @see no
 * @since no
 * @deprecated no
 */

if (true) {

    /* Asigna perfil basado en usuario mandante; cambia según condición específica. */
    $UsuarioMandante = $UsuarioMandanteSite;
    $UsuarioPerfil = new UsuarioPerfil($UsuarioMandante->getUsuarioMandante());
    $perfil = $UsuarioPerfil->getPerfilId();
    $rules = [];

    if ($UsuarioMandante->mandante == '2') {
        $perfil = 'USUONLINE2';
    }


    /* Se añaden reglas de filtrado a una consulta usando condiciones específicas. */
    array_push($rules, array("field" => "perfil_submenu.perfil_id", "data" => $perfil, "op" => "eq"));
    array_push($rules, array("field" => "perfil_submenu.mandante", "data" => $UsuarioMandante->getMandante(), "op" => "eq"));


    array_push($rules, array("field" => "menu.version", "data" => "1", "op" => "eq"));
    $filtro = array("rules" => $rules, "groupOp" => "AND");

    /* obtiene submenús de un perfil utilizando filtros en formato JSON. */
    $jsonfiltro = json_encode($filtro);

    $select = "perfil_submenu.*,menu.*,submenu.*";

    $perfilsubmenu = new PerfilSubmenu();

    $data = $perfilsubmenu->getPerfilSubmenusCustom($select, "menu.orden  asc ,submenu.orden ", 'asc', '0', '10000', $jsonfiltro, true);

    /* decodifica datos JSON y prepara un array vacío para almacenarlos. */
    $menus = json_decode($data);

    $menusData = array();


    foreach ($menus->data as $key => $value) {


        /* omite ciertas páginas basándose en condiciones de referencia específicas. */
        if (str_replace('.php', '', $value->{"submenu.pagina"}) == "consulta_tickets_online" && strpos($_SERVER['HTTP_REFERER'], "acropolis") !== FALSE) {
            continue;
        }
        if (str_replace('.php', '', $value->{"submenu.pagina"}) == "consulta_tickets_casino" && strpos($_SERVER['HTTP_REFERER'], "justbetja") !== FALSE) {
            continue;
        }


        /* Se crea un array para almacenar datos de un submenú en PHP. */
        $arraySubmenu = array();
        $arraySubmenu["SUBMENU_ID"] = ($value->{"submenu.submenu_id"});
        $arraySubmenu["SUBMENU_URL"] = str_replace('.php', '', $value->{"submenu.pagina"});
        $arraySubmenu["SUBMENU_TITLE"] = ($value->{"submenu.descripcion"});

        $menue = false;

        /* agrega submenús a un menú específico en un array. */
        $cont = 0;
        foreach ($menusData as $menu) {
            if ($menu["MENU_ID"] == ($value->{"menu.menu_id"})) {

                array_push($menusData[$cont]["SUBMENUS"], $arraySubmenu);

                $menue = true;
            }
            $cont++;

        }

        /* Crea un array de menú con propiedades y submenús si no hay menú existente. */
        if (!$menue) {
            $array = array();
            $array["MENU_ID"] = ($value->{"menu.menu_id"});
            $array["MENU_TITLE"] = ($value->{"menu.descripcion"});
            $array["MENU_SLUG"] = str_replace('.php', '', $value->{"menu.pagina"});
            $array["MENU_EDITAR"] = ($value->{"perfil_submenu.editar"});
            $array["MENU_ELIMINAR"] = ($value->{"perfil_submenu.eliminar"});
            $array["MENU_ADICIONAR"] = ($value->{"perfil_submenu.adicionar"});

            $array["SUBMENUS"] = array();
            array_push($array["SUBMENUS"], $arraySubmenu);

            array_push($menusData, $array);
        }
    }

    if (in_array($UsuarioMandante->mandante, array('0', 8)) && false) {


        /* Inicializa una cadena vacía y un array, gestionando filas a omitir. */
        $dataSend = '';

        $boxes = [];
        if ($SkeepRows == "") {
            $SkeepRows = 0;
        }


        /* Asigna valores predeterminados a $OrderedItem y $MaxRows si están vacíos. */
        if ($OrderedItem == "") {
            $OrderedItem = 1;
        }

        if ($MaxRows == "") {
            $MaxRows = 100;
        }


        /* Construye un JSON con reglas para filtrar mensajes según ciertos criterios. */
        $mensajesEnviados = [];
        $mensajesRecibidos = [];

        $boxes = [];

        $json2 = '{"rules" : [{"field" : "usuario_mensaje.proveedor_id", "data": "' . $UsuarioMandante->getMandante() . '","op":"eq"},{"field" : "usuario_mensaje.usuto_id", "data": "' . $UsuarioMandanteSite->usumandanteId . '","op":"eq"},{"field" : "usuario_mensaje.tipo", "data": "PUSHNOTIFICACION","op":"eq"},{"field" : "usuario_mensaje.is_read", "data": "0","op":"eq"},{"field" : "usuario_mensaje.fecha_crea", "data": "' . $Usuario->fechaUlt . '","op":"ge"}] ,"groupOp" : "AND"}';

        /* Genera una consulta personalizada sobre mensajes de usuario usando condiciones en JSON. */
        $json2 = '{"rules" : [{"field" : "usuario_mensaje.usuto_id", "data": "' . $json->session->usuario . '","op":"in"},{"field" : "usuario_mensaje.tipo", "data": "STRIPETOP","op":"eq"},{"field" : "usuario_mensaje.is_read", "data": "0","op":"eq"},{"field" : "usuario_mensaje.fecha_expiracion", "data": "' . date('Y-m-d H:i:s') . '","op":"ge"}] ,"groupOp" : "AND"}';
        $UsuarioMensaje = new UsuarioMensaje();
        $usuarios = $UsuarioMensaje->getUsuarioMensajesCustom(" usuario_mensaje.* ", "usuario_mensaje.usumensaje_id", "asc", $SkeepRows, $MaxRows, $json2, true);

        $usuarios = json_decode($usuarios);

        foreach ($usuarios->data as $key => $value) {


            /* Crea un array con datos de mensajes de usuario y lo agrega a otro array. */
            $array = [];

            $array["title"] = $value->{"usuario_mensaje.body"};
            $array["url"] = $value->{"usuario_mensaje.msubject"};
            /* $array["checked"] = intval($value->{"usuario_mensaje.is_read"});
             $array["open"] = false;
             $array["date"] = $value->{"usuario_mensaje.fecha_crea"};
             $array["id"] = $value->{"usuario_mensaje.usumensaje_id"};
             $array["thread_id"] = $value->{"usuario_mensaje.parent_id"};*/

            /*$UsuarioMensaje = new UsuarioMensaje($value->{"usuario_mensaje.usumensaje_id"});
            $UsuarioMensaje->setIsRead(1);

            $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
            $UsuarioMensajeMySqlDAO->update($UsuarioMensaje);
            $UsuarioMensajeMySqlDAO->getTransaction()->commit();*/

            array_push($boxes, $array);

        }


        /* Genera una consulta JSON para filtrar mensajes de usuarios según condiciones específicas. */
        $json2 = '{"rules" : [{"field" : "usuario_mensajecampana.mandante", "data": "' . $UsuarioMandante->getMandante() . '","op":"eq"},{"field" : "usuario_mensajecampana.usuto_id", "data": "' . $UsuarioMandanteSite->usumandanteId . ',0","op":"in"},{"field" : "usuario_mensajecampana.pais_id", "data": "' . $UsuarioMandante->paisId . ',0","op":"in"},{"field" : "usuario_mensajecampana.tipo", "data": "STRIPETOP","op":"eq"},{"field" : "usuario_mensajecampana.is_read", "data": "0","op":"eq"},{"field" : "usuario_mensajecampana.fecha_expiracion", "data": "' . date('Y-m-d H:i:s') . '","op":"ge"},{"field" : "usuario_mensajecampana.fecha_envio", "data": "' . date('Y-m-d H:i:s') . '","op":"le"}] ,"groupOp" : "AND"}';
        $UsuarioMensajecampana = new UsuarioMensajecampana();
        $usuarios = $UsuarioMensajecampana->getUsuarioMensajesCustom(" usuario_mensajecampana.* ", "usuario_mensajecampana.usumencampana_id", "asc", $SkeepRows, $MaxRows, $json2, true);

        $usuarios = json_decode($usuarios);

        foreach ($usuarios->data as $key => $value) {


            /* crea un arreglo con datos de un mensaje de usuario y lo agrega a una lista. */
            $array = [];

            $array["title"] = $value->{"usuario_mensajecampana.body"};
            $array["url"] = json_decode($value->{"usuario_mensajecampana.t_value"})->Redirection;
            /* $array["checked"] = intval($value->{"usuario_mensaje.is_read"});
             $array["open"] = false;
             $array["date"] = $value->{"usuario_mensaje.fecha_crea"};
             $array["id"] = $value->{"usuario_mensaje.usumensaje_id"};
             $array["thread_id"] = $value->{"usuario_mensaje.parent_id"};*/

            /*$UsuarioMensaje = new UsuarioMensaje($value->{"usuario_mensaje.usumensaje_id"});
            $UsuarioMensaje->setIsRead(1);

            $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
            $UsuarioMensajeMySqlDAO->update($UsuarioMensaje);
            $UsuarioMensajeMySqlDAO->getTransaction()->commit();*/

            array_push($boxes, $array);

        }

        /* Se inicializa un arreglo vacío y un objeto Mandante con el usuario correspondiente. */
        $bannerInv = [];

        $seguirBanner = true;

        $Mandante = new Mandante($UsuarioMandante->getMandante());

        /* Se instancia el objeto de la tabla pais_mandante para el uso correcto de base_url*/
        $PaisMandante = new PaisMandante(null, $UsuarioMandante->getMandante(), $UsuarioMandante->getPaisId());

        if ($Mandante->propio == 'S' && $UsuarioMandante->getPaisId() == '173') {

            /* Se crea un objeto Usuario utilizando información de UsuarioMandante. */
            $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());
            if (floatval($Usuario->getBalance()) < 1) {


                /* Construye una consulta JSON para recuperar mensajes de usuario según criterios específicos. */
                $json2 = '{"rules" : [{"field" : "usuario_mensaje.proveedor_id", "data": "' . $UsuarioMandante->getMandante() . '","op":"eq"},{"field" : "usuario_mensaje.usuto_id", "data": "' . $json->session->usuario . '","op":"in"},{"field" : "usuario_mensaje.tipo", "data": "MESSAGEINV","op":"eq"},{"field" : "usuario_mensaje.fecha_crea", "data": "' . date('Y-m-d') . '","op":"cn"}] ,"groupOp" : "AND"}';
                $UsuarioMensaje = new UsuarioMensaje();
                $usuarios = $UsuarioMensaje->getUsuarioMensajesCustom(" usuario_mensaje.* ", "usuario_mensaje.usumensaje_id", "asc", $SkeepRows, $MaxRows, $json2, true, $json->session->usuario);

                $usuarios = json_decode($usuarios);

                $mensajepornoSaldo = false;


                /* Verifica si el conteo de usuarios es cero o mayor a doce. */
                if (intval($usuarios->count[0]->{".count"}) == 0) {
                    $mensajepornoSaldo = true;
                }

                if (intval($usuarios->count[0]->{".count"}) >= 12) {
                    $mensajepornoSaldo = false;
                }


                /* Verifica si hay más de 12 usuarios y si la última fecha es mayor a 2 horas. */
                if (intval($usuarios->count[0]->{".count"}) >= 12) {
                    $ultmFecha = '';
                    foreach ($usuarios->data as $datum) {
                        $ultmFecha = $datum->{'usuario_mensaje.fecha_crea'};
                    }
                    $hourdiff = round((time() - strtotime($ultmFecha)) / 3600, 1);

                    if ($hourdiff >= 2) {
                        $mensajepornoSaldo = true;

                    }

                }


                if ($mensajepornoSaldo) {


                    /* crea un array con un enlace de depósito y mensajes de promoción. */
                    $array = [];
                    array_push($array, 'https://images.virtualsoft.tech/site/doradobet/invasive-banner.png');
                    array_push($array, '¡ CLICK PARA DEPOSITAR !');
                    array_push($array, ':star: ¿TE QUEDASTE SIN SALDO? :moneybag: DEPOSITA Y SIGUE GANANDO ! :point_right: ¡HAZ CLICK AQUI! :point_left:');
                    array_push($array, $PaisMandante->baseUrl . '/gestion/deposito?utm_source=Directa&utm_medium=MensajeInv&utm_campaign=MensajeInv_Deposito&utm_term=MensajeInv&utm_content=MensajeInv');
                    array_push($array, '_self');

                    /* agrega elementos a un arreglo y crea un objeto de UsuarioMensaje. */
                    array_push($array, '');
                    array_push($array, "isMessage");
                    array_push($array, "¡ CLICK PARA DEPOSITAR !");

                    $bannerInv = $array;

                    $UsuarioMensaje = new UsuarioMensaje();

                    /* Se crea un mensaje de usuario informando sobre saldo y opción para depositar. */
                    $UsuarioMensaje->usufromId = 0;
                    $UsuarioMensaje->usutoId = $UsuarioMandante->usumandanteId;
                    $UsuarioMensaje->isRead = 1;
                    $UsuarioMensaje->body = ':star: ¿TE QUEDASTE SIN SALDO? DEPOSITA Y SIGUE GANANDO ! :point_right: ¡HAZ CLICK AQUI! :point_left:';
                    $UsuarioMensaje->msubject = '¡ CLICK PARA DEPOSITAR !';
                    $UsuarioMensaje->tipo = "MESSAGEINV";

                    /* Código que inicializa un objeto y lo prepara para interacción con una base de datos. */
                    $UsuarioMensaje->parentId = 0;
                    $UsuarioMensaje->proveedorId = 0;
                    $UsuarioMensaje->setExternoId(0);


                    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();

                    /* Inserta un mensaje en la base de datos y confirma la transacción, deteniendo un banner. */
                    $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                    $UsuarioMensajeMySqlDAO->getTransaction()->commit();

                    $seguirBanner = false;

                }


            } else {
                if ($Usuario->usuarioId == 886 || true) {


                    /* Genera un JSON para filtrar mensajes de usuario con diversas condiciones. */
                    $json2 = '{"rules" : [{"field" : "usuario_mensaje.proveedor_id", "data": "' . $UsuarioMandante->getMandante() . '","op":"eq"},{"field" : "usuario_mensaje.usuto_id", "data": "' . $json->session->usuario . ',0","op":"in"},{"field" : "usuario_mensaje2.usumensaje_id", "data": "NULL","op":"isnull"},{"field" : "usuario_mensaje.pais_id", "data": "' . $UsuarioMandante->paisId . ',0","op":"in"},{"field" : "usuario_mensaje.tipo", "data": "MESSAGEINV","op":"eq"},{"field" : "usuario_mensaje.is_read", "data": "0","op":"eq"},{"field" : "usuario_mensaje.fecha_expiracion", "data": "' . date('Y-m-d H:i:s') . '","op":"ge"}] ,"groupOp" : "AND"}';
                    //$json2 = '{"rules" : [{"field" : "usuario_mensaje.usuto_id", "data": "' . $json->session->usuario . ',0","op":"in"},{"field" : "usuario_mensaje.tipo", "data": "MESSAGEINV","op":"eq"},{"field" : "usuario_mensaje.is_read", "data": "0","op":"eq"},] ,"groupOp" : "AND"}';
                    $UsuarioMensaje = new UsuarioMensaje();
                    $usuarios = $UsuarioMensaje->getUsuarioMensajesCustom(" usuario_mensaje.* ", "usuario_mensaje.usumensaje_id", "asc", $SkeepRows, $MaxRows, $json2, true, $json->session->usuario);

                    $usuarios = json_decode($usuarios);

                    foreach ($usuarios->data as $key => $value) {


                        /* divide cadenas de texto en partes usando '##FIX##' como delimitador. */
                        $imagen = explode('##FIX##', $value->{"usuario_mensaje.msubject"})[0];
                        $URL = explode('##FIX##', $value->{"usuario_mensaje.msubject"})[1];
                        $target = explode('##FIX##', $value->{"usuario_mensaje.msubject"})[2];

                        $body = explode('##FIX##', $value->{"usuario_mensaje.body"})[0];
                        $botonTexto = explode('##FIX##', $value->{"usuario_mensaje.body"})[1];


                        /* almacena elementos en un array utilizando la función `array_push`. */
                        $array = [];
                        array_push($array, $imagen);
                        array_push($array, $botonTexto);
                        array_push($array, $body);
                        array_push($array, $URL);
                        array_push($array, $target);

                        /* Se agregan elementos a un array y se asigna a otra variable. */
                        array_push($array, '');
                        array_push($array, "isMessage");

                        $bannerInv = $array;

                        /*                    $UsuarioMensaje = new UsuarioMensaje();
                                            $UsuarioMensaje->usufromId = 0;
                                            $UsuarioMensaje->usutoId = $UsuarioMandante->usumandanteId;
                                            $UsuarioMensaje->isRead = 1;
                                            $UsuarioMensaje->body = ':star: ¿TE QUEDASTE SIN SALDO? DEPOSITA Y SIGUE GANANDO ! :point_right: ¡HAZ CLICK AQUI! :point_left:##FIX##¡ CLICK PARA DEPOSITAR !';
                                            $UsuarioMensaje->msubject = 'https://images.virtualsoft.tech/site/doradobet/invasive-banner.png##FIX##https://doradobet.com/gestion/deposito?utm_source=Directa&utm_medium=MensajeInv&utm_campaign=MensajeInv_Deposito&utm_term=MensajeInv&utm_content=MensajeInv##FIX##_self';
                                            $UsuarioMensaje->tipo = "MESSAGEINV";
                                            $UsuarioMensaje->parentId = 0;
                                            $UsuarioMensaje->proveedorId = 0;
                                            $UsuarioMensaje->setExternoId(0);




                                            $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                                            $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                                            $UsuarioMensajeMySqlDAO->getTransaction()->commit();*/


                        /* Crea un nuevo mensaje de usuario y lo inserta en la base de datos. */
                        if ($value->{"usuario_mensaje.usuto_id"} == '0') {
                            $UsuarioMensaje = new UsuarioMensaje();
                            $UsuarioMensaje->usufromId = 0;
                            $UsuarioMensaje->usutoId = $UsuarioMandante->usumandanteId;
                            $UsuarioMensaje->isRead = 1;
                            $UsuarioMensaje->body = $value->{"usuario_mensaje.body"};
                            $UsuarioMensaje->body = str_replace("'", '"', $UsuarioMensaje->body);
                            $UsuarioMensaje->msubject = $value->{"usuario_mensaje.msubject"};
                            $UsuarioMensaje->tipo = "MESSAGEINV";
                            $UsuarioMensaje->parentId = $value->{"usuario_mensaje.usumensaje_id"};
                            $UsuarioMensaje->proveedorId = 0;
                            $UsuarioMensaje->setExternoId(0);


                            $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                            $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                            $UsuarioMensajeMySqlDAO->getTransaction()->commit();

                        } else {
                            /* marca un mensaje como leído y actualiza la base de datos. */

                            $UsuarioMensaje = new UsuarioMensaje($value->{"usuario_mensaje.usumensaje_id"});
                            $UsuarioMensaje->isRead = 1;
                            $UsuarioMensaje->body = str_replace("'", '"', $UsuarioMensaje->body);


                            $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                            $UsuarioMensajeMySqlDAO->update($UsuarioMensaje);
                            $UsuarioMensajeMySqlDAO->getTransaction()->commit();

                        }


                        /* Variable que indica si se debe continuar mostrando un banner. Inicialmente, es falso. */
                        $seguirBanner = false;

                    }


                    if (in_array($Usuario->usuarioId, array(886, 42947))) {

                        /* Genera un JSON con reglas para filtrar mensajes de usuario en una campaña. */
                        $json2 = '{"rules" : [{"field" : "usuario_mensajecampana.mandante", "data": "' . $UsuarioMandante->getMandante() . '","op":"eq"},{"field" : "usuario_mensajecampana.usuto_id", "data": "0","op":"in"},{"field" : "usuario_mensaje2.usumensaje_id", "data": "NULL","op":"isnull"},{"field" : "usuario_mensajecampana.pais_id", "data": "' . $UsuarioMandante->paisId . ',0","op":"in"},{"field" : "usuario_mensajecampana.tipo", "data": "MESSAGEINV","op":"eq"},{"field" : "usuario_mensajecampana.is_read", "data": "0","op":"eq"},{"field" : "usuario_mensajecampana.fecha_expiracion", "data": "' . date('Y-m-d H:i:s') . '","op":"ge"},{"field" : "usuario_mensajecampana.fecha_envio", "data": "' . date('Y-m-d H:i:s') . '","op":"le"}] ,"groupOp" : "AND"}';
                        //$json2 = '{"rules" : [{"field" : "usuario_mensaje.usuto_id", "data": "' . $json->session->usuario . ',0","op":"in"},{"field" : "usuario_mensaje.tipo", "data": "MESSAGEINV","op":"eq"},{"field" : "usuario_mensaje.is_read", "data": "0","op":"eq"},] ,"groupOp" : "AND"}';
                        $UsuarioMensajecampana = new UsuarioMensajecampana();
                        $usuarios = $UsuarioMensajecampana->getUsuarioMensajesCustom(" usuario_mensajecampana.* ", "usuario_mensajecampana.usumencampana_id", "asc", $SkeepRows, $MaxRows, $json2, true, $json->session->usuario);

                        $usuarios = json_decode($usuarios);

                        foreach ($usuarios->data as $key => $value) {


                            /* Extrae y decodifica información de un objeto JSON para usar en un mensaje. */
                            $imagen = json_decode($value->{"usuario_mensajecampana.t_value"})->Image;
                            $URL = json_decode($value->{"usuario_mensajecampana.t_value"})->Redirection;
                            $target = json_decode($value->{"usuario_mensajecampana.t_value"})->Target;

                            $body = $value->{"usuario_mensajecampana.body"};
                            $botonTexto = json_decode($value->{"usuario_mensajecampana.t_value"})->ButtonText;


                            /* agrega elementos a un array utilizando la función `array_push`. */
                            $array = [];
                            array_push($array, $imagen);
                            array_push($array, $botonTexto);
                            array_push($array, $body);
                            array_push($array, $URL);
                            array_push($array, $target);

                            /* Se añaden elementos a un array, incluyendo un valor de un objeto. */
                            array_push($array, '');
                            array_push($array, "isMessage");
                            array_push($array, $value->{"usuario_mensajecampana.parent_id"});

                            $bannerInv = $array;

                            /*                    $UsuarioMensaje = new UsuarioMensaje();
                                                $UsuarioMensaje->usufromId = 0;
                                                $UsuarioMensaje->usutoId = $UsuarioMandante->usumandanteId;
                                                $UsuarioMensaje->isRead = 1;
                                                $UsuarioMensaje->body = ':star: ¿TE QUEDASTE SIN SALDO? DEPOSITA Y SIGUE GANANDO ! :point_right: ¡HAZ CLICK AQUI! :point_left:##FIX##¡ CLICK PARA DEPOSITAR !';
                                                $UsuarioMensaje->msubject = 'https://images.virtualsoft.tech/site/doradobet/invasive-banner.png##FIX##https://doradobet.com/gestion/deposito?utm_source=Directa&utm_medium=MensajeInv&utm_campaign=MensajeInv_Deposito&utm_term=MensajeInv&utm_content=MensajeInv##FIX##_self';
                                                $UsuarioMensaje->tipo = "MESSAGEINV";
                                                $UsuarioMensaje->parentId = 0;
                                                $UsuarioMensaje->proveedorId = 0;
                                                $UsuarioMensaje->setExternoId(0);




                                                $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                                                $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                                                $UsuarioMensajeMySqlDAO->getTransaction()->commit();*/


                            /* Crea y almacena un mensaje de usuario si su ID es '0'. */
                            if ($value->{"usuario_mensajecampana.usuto_id"} == '0') {
                                $UsuarioMensaje = new UsuarioMensaje();
                                $UsuarioMensaje->usufromId = 0;
                                $UsuarioMensaje->usutoId = $UsuarioMandante->usumandanteId;
                                $UsuarioMensaje->isRead = 1;
                                $UsuarioMensaje->body = $value->{"usuario_mensajecampana.body"};
                                $UsuarioMensaje->body = str_replace("'", '"', $UsuarioMensaje->body);
                                $UsuarioMensaje->msubject = $value->{"usuario_mensajecampana.msubject"};
                                $UsuarioMensaje->tipo = "MESSAGEINV";
                                $UsuarioMensaje->parentId = $value->{"usuario_mensajecampana.usumensaje_id"};
                                $UsuarioMensaje->proveedorId = 0;
                                $UsuarioMensaje->setExternoId(0);


                                $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                                $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                                $UsuarioMensajeMySqlDAO->getTransaction()->commit();

                            }


                            /* Variable booleana que indica si se debe continuar mostrando un banner. */
                            $seguirBanner = false;

                        }

                    }
                }

            }

            if ($Usuario->usuarioId == 886 && false) {


                /* Construye un filtro en JSON para obtener mensajes de usuario según criterios específicos. */
                $json2 = '{"rules" : [{"field" : "usuario_mensaje.proveedor_id", "data": "' . $UsuarioMandante->getMandante() . '","op":"eq"},{"field" : "usuario_mensaje.usuto_id", "data": "' . $json->session->usuario . '","op":"in"},{"field" : "usuario_mensaje.tipo", "data": "MESSAGEINV","op":"eq"},{"field" : "usuario_mensaje.fecha_crea", "data": "' . date('Y-m-d') . '","op":"cn"}] ,"groupOp" : "AND"}';
                $UsuarioMensaje = new UsuarioMensaje();
                $usuarios = $UsuarioMensaje->getUsuarioMensajesCustom(" usuario_mensaje.* ", "usuario_mensaje.usumensaje_id", "asc", $SkeepRows, $MaxRows, $json2, true, $json->session->usuario);

                $usuarios = json_decode($usuarios);

                if (intval($usuarios->count[0]->{".count"}) > 0) {


                    /* Se crea un array con un enlace y mensajes promocionales de depósito. */
                    $array = [];
                    array_push($array, 'https://images.virtualsoft.tech/site/doradobet/invasive-banner.png');
                    array_push($array, '¡ CLICK PARA DEPOSITAR !');
                    array_push($array, ':star: ¿TE QUEDASTE SIN SALDO? :moneybag: DEPOSITA Y SIGUE GANANDO ! :credit_card: ¡ Ha llegado VISA ! :smiley: :credit_card: :point_right: ¡HAZ CLICK AQUI! :point_left:');
                    array_push($array, 'https://doradobet.com/gestion/deposito?utm_source=Directa&utm_medium=MensajeInv&utm_campaign=MensajeInv_Deposito&utm_term=MensajeInv&utm_content=MensajeInv');
                    array_push($array, '_self');

                    /* Se agregan mensajes a un array y se instancia una clase de usuario. */
                    array_push($array, '');
                    array_push($array, "isMessage");
                    array_push($array, "¡ CLICK PARA DEPOSITAR !");

                    $bannerInv = $array;

                    $UsuarioMensaje = new UsuarioMensaje();

                    /* Se asignan valores a un objeto de mensaje para notificar sobre depósitos disponibles. */
                    $UsuarioMensaje->usufromId = 0;
                    $UsuarioMensaje->usutoId = $UsuarioMandante->usumandanteId;
                    $UsuarioMensaje->isRead = 1;
                    $UsuarioMensaje->body = ':star: ¿TE QUEDASTE SIN SALDO? DEPOSITA Y SIGUE GANANDO ! :point_right: ¡HAZ CLICK AQUI! :point_left:##FIX##¡ CLICK PARA DEPOSITAR !';
                    $UsuarioMensaje->msubject = 'https://images.virtualsoft.tech/site/doradobet/invasive-banner.png##FIX##https://doradobet.com/gestion/deposito?utm_source=Directa&utm_medium=MensajeInv&utm_campaign=MensajeInv_Deposito&utm_term=MensajeInv&utm_content=MensajeInv##FIX##_self';
                    $UsuarioMensaje->tipo = "MESSAGEINV";

                    /* Se inicializan propiedades de un objeto y se crea un DAO para interactuar con MySQL. */
                    $UsuarioMensaje->parentId = 0;
                    $UsuarioMensaje->proveedorId = 0;
                    $UsuarioMensaje->setExternoId(0);


                    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();

                    /* Se inserta un mensaje de usuario en la base de datos y se confirma la transacción. */
                    $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                    $UsuarioMensajeMySqlDAO->getTransaction()->commit();

                    $seguirBanner = false;

                }


            }

        }


        if ($seguirBanner) {


            /* Genera una consulta JSON para filtrar mensajes de usuario según condiciones específicas. */
            $json2 = '{"rules" : [{"field" : "usuario_mensaje.proveedor_id", "data": "' . $UsuarioMandante->getMandante() . '","op":"eq"},{"field" : "usuario_mensaje.usuto_id", "data": "' . $json->session->usuario . '","op":"eq"},{"field" : "usuario_mensaje.tipo", "data": "PUSHNOTIFICACION","op":"eq"},{"field" : "usuario_mensaje.is_read", "data": "0","op":"eq"},{"field" : "usuario_mensaje.fecha_crea", "data": "' . $Usuario->fechaUlt . '","op":"ge"}] ,"groupOp" : "AND"}';
            $json2 = '{"rules" : [{"field" : "usuario_mensaje.proveedor_id", "data": "' . $UsuarioMandante->getMandante() . '","op":"eq"},{"field" : "usuario_mensaje.usuto_id", "data": "' . $json->session->usuario . ',0","op":"in"},{"field" : "usuario_mensaje2.usumensaje_id", "data": "NULL","op":"isnull"},{"field" : "usuario_mensaje.pais_id", "data": "' . $UsuarioMandante->paisId . ',0","op":"in"},{"field" : "usuario_mensaje.tipo", "data": "BANNERINV","op":"eq"},{"field" : "usuario_mensaje.is_read", "data": "0","op":"eq"},{"field" : "usuario_mensaje.fecha_expiracion", "data": "' . date('Y-m-d H:i:s') . '","op":"ge"}] ,"groupOp" : "AND"}';
            $UsuarioMensaje = new UsuarioMensaje();
            $usuarios = $UsuarioMensaje->getUsuarioMensajesCustom(" usuario_mensaje.* ", "usuario_mensaje.usumensaje_id", "asc", $SkeepRows, $MaxRows, $json2, true, $json->session->usuario);

            $usuarios = json_decode($usuarios);

            foreach ($usuarios->data as $key => $value) {


                /* crea un array con datos de usuario y URL de mensajes. */
                $array = [];

                $array["title"] = $value->{"usuario_mensaje.body"};
                $array["url"] = $value->{"usuario_mensaje.msubject"};
                /* $array["checked"] = intval($value->{"usuario_mensaje.is_read"});
                 $array["open"] = false;
                 $array["date"] = $value->{"usuario_mensaje.fecha_crea"};
                 $array["id"] = $value->{"usuario_mensaje.usumensaje_id"};
                 $array["thread_id"] = $value->{"usuario_mensaje.parent_id"};*/

                /*$UsuarioMensaje = new UsuarioMensaje($value->{"usuario_mensaje.usumensaje_id"});
                $UsuarioMensaje->setIsRead(1);

                $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                $UsuarioMensajeMySqlDAO->update($UsuarioMensaje);
                $UsuarioMensajeMySqlDAO->getTransaction()->commit();*/

                $urlFrame = explode('##URL##', $value->{"usuario_mensaje.msubject"})[0];

                /* procesa un mensaje de usuario, generando URLs basado en un ID de juego. */
                $url = explode('##URL##', $value->{"usuario_mensaje.msubject"})[1];

                $seguirProducto = true;
                $proveedorReq = 0;

                if (strpos($urlFrame, 'GAME') !== false) {

                    $gameid = explode('GAME', $urlFrame)[1];

                    if (is_numeric($gameid)) {
                        try {
                            $Producto = new Producto($gameid);
                            $ProductoMandante = new ProductoMandante($gameid, $UsuarioMandante->getMandante());
                            $Proveedor = new Proveedor($Producto->getProveedorId());
                            $urlFrame = 'https://casino.virtualsoft.tech/game/play/?gameid=' . $ProductoMandante->prodmandanteId . '&mode=real&provider=' . $Proveedor->getAbreviado() . '&lang=es&mode=real&partnerid=' . $UsuarioMandante->getMandante();
                            $url = $PaisMandante->baseUrl . '/' . 'casino' . '/' . $ProductoMandante->prodmandanteId;
                            $proveedorReq = $Proveedor->getProveedorId();
                        } catch (Exception $e) {
                            $seguirProducto = false;

                        }
                    }
                } else {
                    /* Asigna el ID del proveedor desde un objeto si no se cumple una condición. */

                    $proveedorReq = $value->{"usuario_mensaje.proveedor_id"};
                }

                if ($seguirProducto) {
                    if ($proveedorReq != 0 && $proveedorReq != '') {


                        /* genera un token para un usuario utilizando su identificador. */
                        $token = '';


                        try {

                            $UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

                            $token = $UsuarioToken->getToken();
                        } catch (Exception $e) {


                            /* Condición para crear y almacenar un token de usuario en la base de datos. */
                            if ($e->getCode() == "21") {

                                /* $UsuarioToken = new UsuarioToken();

                                 $UsuarioToken->setRequestId('');
                                 $UsuarioToken->setProveedorId(0);
                                 $UsuarioToken->setUsuarioId($UsuarioMandante->getUsumandanteId());
                                 $UsuarioToken->setToken($UsuarioToken->createToken());
                                 //$UsuarioToken->setCookie(encrypt($UsuarioMandante->getUsumandanteId() . "#" . time()));
                                 $UsuarioToken->setUsumodifId(0);
                                 $UsuarioToken->setUsucreaId(0);
                                 $UsuarioToken->setSaldo(0);

                                 $UsuarioTokenMySqlDAO = new \Backend\mysql\UsuarioTokenMySqlDAO();
                                 $UsuarioTokenMySqlDAO->insert($UsuarioToken);
                                 $UsuarioTokenMySqlDAO->getTransaction()->commit();
                                 $token = $UsuarioToken->getToken();*/


                            }
                        }

                        /* Concatena un token a una URL existente para autenticar solicitudes. */
                        $urlFrame = $urlFrame . '&token=' . $token;
                        // $url = $url . '&token='.$token;

                    }

                    //'https://demogamesfree.pragmaticplay.net/gs2c/openGame.do?lang=en&cur=USD&gameSymbol=vs40beowulf&lobbyURL=https://doradobet.com/new-casino'

                    /* crea un array y le añade varias cadenas y un valor dinámico. */
                    $array = [];
                    array_push($array, 'https://images.virtualsoft.tech/site/doradobet/pet/pet-doradobet.png');
                    array_push($array, 'Hola');
                    array_push($array, $value->{"usuario_mensaje.body"});
                    array_push($array, $url);
                    array_push($array, '_self');

                    /* Se agregan elementos a un array y se asigna a una nueva variable. */
                    array_push($array, $urlFrame);
                    array_push($array, "isInvasive");

                    // $array["title"] = $value->{"usuario_mensaje.body"};
                    // $array["url"] = $value->{"usuario_mensaje.msubject"};

                    $bannerInv = $array;


                    /* crea y guarda un mensaje de usuario si el destinatario es '0'. */
                    if ($value->{"usuario_mensaje.usuto_id"} == '0') {
                        $UsuarioMensaje = new UsuarioMensaje();
                        $UsuarioMensaje->usufromId = 0;
                        $UsuarioMensaje->usutoId = $UsuarioMandante->usumandanteId;
                        $UsuarioMensaje->isRead = 1;
                        $UsuarioMensaje->body = str_replace("'", '"', $value->{"usuario_mensaje.body"});
                        $UsuarioMensaje->msubject = $value->{"usuario_mensaje.msubject"};
                        $UsuarioMensaje->tipo = "BANNERINV";
                        $UsuarioMensaje->parentId = $value->{"usuario_mensaje.usumensaje_id"};
                        $UsuarioMensaje->proveedorId = 0;
                        $UsuarioMensaje->setExternoId(0);


                        $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                        $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                        $UsuarioMensajeMySqlDAO->getTransaction()->commit();

                    } else {
                        /* Actualiza el estado de un mensaje de usuario y guarda cambios en la base de datos. */

                        $UsuarioMensaje = new UsuarioMensaje($value->{"usuario_mensaje.usumensaje_id"});
                        $UsuarioMensaje->isRead = 1;
                        $UsuarioMensaje->body = str_replace("'", '"', $UsuarioMensaje->body);


                        $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                        $UsuarioMensajeMySqlDAO->update($UsuarioMensaje);
                        $UsuarioMensajeMySqlDAO->getTransaction()->commit();

                    }
                }

            }


            if (in_array($Usuario->usuarioId, array(886, 42947))) {


                /* Construye un JSON con reglas para filtrar mensajes de usuario en una consulta. */
                $json2 = '{"rules" : [{"field" : "usuario_mensajecampana.mandante", "data": "' . $UsuarioMandante->getMandante() . '","op":"eq"},{"field" : "usuario_mensajecampana.usuto_id", "data": "0","op":"in"},{"field" : "usuario_mensaje2.usumensaje_id", "data": "NULL","op":"isnull"},{"field" : "usuario_mensajecampana.pais_id", "data": "' . $UsuarioMandante->paisId . ',0","op":"in"},{"field" : "usuario_mensajecampana.tipo", "data": "BANNERINV","op":"eq"},{"field" : "usuario_mensajecampana.is_read", "data": "0","op":"eq"},{"field" : "usuario_mensajecampana.fecha_expiracion", "data": "' . date('Y-m-d H:i:s') . '","op":"ge"},{"field" : "usuario_mensajecampana.fecha_envio", "data": "' . date('Y-m-d H:i:s') . '","op":"le"}] ,"groupOp" : "AND"}';
                $UsuarioMensajecampana = new UsuarioMensajecampana();
                $usuarios = $UsuarioMensajecampana->getUsuarioMensajesCustom(" usuario_mensajecampana.* ", "usuario_mensajecampana.usumencampana_id", "asc", $SkeepRows, $MaxRows, $json2, true, $json->session->usuario);

                $usuarios = json_decode($usuarios);

                foreach ($usuarios->data as $key => $value) {


                    /* Crea un array con datos de mensajes y procesa un URL específico. */
                    $array = [];

                    $array["title"] = $value->{"usuario_mensajecampana.body"};
                    $array["url"] = $value->{"usuario_mensajecampana.msubject"};
                    /* $array["checked"] = intval($value->{"usuario_mensajecampana.is_read"});
                     $array["open"] = false;
                     $array["date"] = $value->{"usuario_mensajecampana.fecha_crea"};
                     $array["id"] = $value->{"usuario_mensajecampana.usumensaje_id"};
                     $array["thread_id"] = $value->{"usuario_mensajecampana.parent_id"};*/

                    /*$UsuarioMensaje = new UsuarioMensaje($value->{"usuario_mensajecampana.usumensaje_id"});
                    $UsuarioMensaje->setIsRead(1);

                    $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                    $UsuarioMensajeMySqlDAO->update($UsuarioMensaje);
                    $UsuarioMensajeMySqlDAO->getTransaction()->commit();*/

                    $urlFrame = explode('##URL##', $value->{"usuario_mensajecampana.msubject"})[0];

                    /* Obtiene y procesa información de un producto basado en un mensaje JSON específico. */
                    $url = json_decode($value->{"usuario_mensajecampana.t_value"})->Redirection;
                    $Redirection2 = json_decode($value->{"usuario_mensajecampana.t_value"})->Frame . '##URL##' . json_decode($value->{"usuario_mensajecampana.t_value"})->Redirection;

                    $seguirProducto = true;
                    $proveedorReq = 0;

                    if (json_decode($value->{"usuario_mensajecampana.t_value"})->IsGame === true || json_decode($value->{"usuario_mensajecampana.t_value"})->IsGame == "true") {

                        $gameid = json_decode($value->{"usuario_mensajecampana.t_value"})->Product;

                        if (is_numeric($gameid)) {
                            try {
                                $Producto = new Producto($gameid);
                                $ProductoMandante = new ProductoMandante($gameid, $UsuarioMandante->getMandante());
                                $Proveedor = new Proveedor($Producto->getProveedorId());
                                $urlFrame = 'https://casino.virtualsoft.tech/game/play/?gameid=' . $ProductoMandante->prodmandanteId . '&mode=real&provider=' . $Proveedor->getAbreviado() . '&lang=es&mode=real&partnerid=' . $UsuarioMandante->getMandante();
                                $url = $PaisMandante->baseUrl . '/' . 'casino' . '/' . $ProductoMandante->prodmandanteId;
                                $proveedorReq = $Proveedor->getProveedorId();
                            } catch (Exception $e) {
                                $seguirProducto = false;

                            }
                        }
                    } else {
                        /* Asigna el proveedor ID de un mensaje a una variable si la condición anterior no se cumple. */

                        $proveedorReq = $value->{"usuario_mensajecampana.proveedor_id"};
                    }

                    if ($seguirProducto) {
                        if ($proveedorReq != 0 && $proveedorReq != '') {


                            /* Token generado para un usuario a partir de su ID de mandante. */
                            $token = '';


                            try {

                                $UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

                                $token = $UsuarioToken->getToken();
                            } catch (Exception $e) {


                                /* Condicional para manejar un código específico y crear un nuevo token de usuario. */
                                if ($e->getCode() == "21") {

                                    /*$UsuarioToken = new UsuarioToken();

                                    $UsuarioToken->setRequestId('');
                                    $UsuarioToken->setProveedorId(0);
                                    $UsuarioToken->setUsuarioId($UsuarioMandante->getUsumandanteId());
                                    $UsuarioToken->setToken($UsuarioToken->createToken());
                                    //$UsuarioToken->setCookie(encrypt($UsuarioMandante->getUsumandanteId() . "#" . time()));
                                    $UsuarioToken->setUsumodifId(0);
                                    $UsuarioToken->setUsucreaId(0);
                                    $UsuarioToken->setSaldo(0);

                                    $UsuarioTokenMySqlDAO = new \Backend\mysql\UsuarioTokenMySqlDAO();
                                    $UsuarioTokenMySqlDAO->insert($UsuarioToken);
                                    $UsuarioTokenMySqlDAO->getTransaction()->commit();
                                    $token = $UsuarioToken->getToken();*/


                                }
                            }

                            /* Se concatena un token a la URL para autenticación o seguimiento. */
                            $urlFrame = $urlFrame . '&token=' . $token;
                            // $url = $url . '&token='.$token;

                        }

                        /* concatena URL y guarda elementos en un arreglo. */
                        $Redirection2 = $urlFrame . '##URL##' . json_decode($value->{"usuario_mensajecampana.t_value"})->Redirection;

                        //'https://demogamesfree.pragmaticplay.net/gs2c/openGame.do?lang=en&cur=USD&gameSymbol=vs40beowulf&lobbyURL=https://doradobet.com/new-casino'
                        $array = [];
                        array_push($array, 'https://images.virtualsoft.tech/site/doradobet/pet/pet-doradobet.png');
                        array_push($array, 'Hola');

                        /* Agrega elementos a un array usando la función `array_push` en PHP. */
                        array_push($array, $value->{"usuario_mensajecampana.body"});
                        array_push($array, $url);
                        array_push($array, '_self');
                        array_push($array, $urlFrame);
                        array_push($array, "isInvasive");
                        array_push($array, $value->{"usuario_mensajecampana.parent_id"});

                        // $array["title"] = $value->{"usuario_mensajecampana.body"};
                        // $array["url"] = $value->{"usuario_mensajecampana.msubject"};


                        /* Crea y guarda un mensaje de usuario si el ID del destinatario es cero. */
                        $bannerInv = $array;

                        if ($value->{"usuario_mensajecampana.usuto_id"} == '0') {
                            $UsuarioMensaje = new UsuarioMensaje();
                            $UsuarioMensaje->usufromId = 0;
                            $UsuarioMensaje->usutoId = $UsuarioMandante->usumandanteId;
                            $UsuarioMensaje->isRead = 1;
                            $UsuarioMensaje->body = $value->{"usuario_mensajecampana.body"};
                            $UsuarioMensaje->body = str_replace("'", '"', $UsuarioMensaje->body);
                            $UsuarioMensaje->msubject = $Redirection2;
                            $UsuarioMensaje->tipo = "BANNERINV";
                            $UsuarioMensaje->parentId = $value->{"usuario_mensajecampana.usumensaje_id"};
                            $UsuarioMensaje->proveedorId = 0;
                            $UsuarioMensaje->setExternoId(0);


                            $UsuarioMensajeMySqlDAO = new UsuarioMensajeMySqlDAO();
                            $UsuarioMensajeMySqlDAO->insert($UsuarioMensaje);
                            $UsuarioMensajeMySqlDAO->getTransaction()->commit();

                        }
                    }

                }
            }
        }

        $dataSend =

            /* organiza datos y envía un mensaje mediante WebSocket bajo ciertas condiciones. */
            array(
                "messages" => $mensajesRecibidos,
                "boxes" => $boxes,
                "bannerInv" => $bannerInv
            );


        if (in_array($UsuarioMandante->mandante, array('0', 8)) && false) {

            $WebsocketUsuario = new WebsocketUsuario('', '');
            $WebsocketUsuario->sendWSPieSocket($UsuarioMandante, $dataSend);

        }
    }
}


/* Crea una respuesta JSON con un código y datos de menú. */
$response = array();
$response["code"] = 0;
$response["data"] = $menusData;





