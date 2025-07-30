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
 * Recurso que obtiene los detalles del usuario administrador.
 *
 * @param int $json->params->user_id ID del usuario.
 *
 * @return array Respuesta en formato JSON:
 * - code (int) Código de respuesta.
 * - data (array) Datos del usuario administrador:
 *   - usuario_id (int) ID del usuario.
 *   - fecha_crea (string) Fecha de creación del usuario.
 *   - fecha_ult (string) Fecha de última actividad del usuario.
 *   - fecha_modif (string) Fecha de última modificación del usuario.
 *   - perfil_id (array) Perfil del usuario:
 *     - key (string) ID del perfil.
 *   - mostrar_infopunto (array) Información del punto:
 *     - key (string) Estilo para mostrar información del punto.
 *   - dir_ip (string) Dirección IP del usuario.
 *   - login (string) Login del usuario.
 *   - nombre (string) Nombre del usuario.
 *   - usuario_modif (string) Usuario que realizó la modificación.
 *   - clave (string) Clave del usuario.
 *   - estado (array) Estado del usuario:
 *     - key (string) Estado.
 *   - estado_esp (array) Estado especial del usuario:
 *     - key (string) Estado especial.
 *   - retirado (array) Indica si el usuario está retirado:
 *     - key (bool) Estado de retiro.
 *   - observ (string) Observaciones del usuario.
 *   - intentos (int) Número de intentos de acceso.
 *   - texto_punto (string) Texto asociado al tipo de perfil del usuario.
 *   - pais_id (array) Código del país del usuario:
 *     - key (string) Código del país.
 *   - moneda (array) Código de la moneda del usuario:
 *     - key (string) Código de la moneda.
 *   - idioma (array) Idioma preferido del usuario:
 *     - key (string) Idioma.
 *   - permite_recarga (array) Indica si se permite la recarga:
 *     - key (bool) Permiso de recarga.
 *   - pinagent (array) PIN del agente:
 *     - key (string) PIN del agente.
 *   - recibo_caja (array) Recibo de caja:
 *     - key (string) Recibo de caja.
 *   - bloqueo_ventas (array) Indica si hay bloqueo de ventas:
 *     - key (bool) Bloqueo de ventas.
 *   - permite_activareg (array) Indica si se permite la activación de registro:
 *     - key (bool) Permiso de activación de registro.
 *   - descripcion (string) Descripción del usuario.
 *   - nombre_contacto (string) Nombre del contacto.
 *   - depto_id (array) ID del departamento:
 *     - key (int) ID del departamento.
 *   - ciudad_id (array) ID de la ciudad:
 *     - key (int) ID de la ciudad.
 *   - direccion (string) Dirección del usuario.
 *   - barrio (string) Barrio del usuario.
 *   - telefono (string) Teléfono del usuario.
 *   - email (string) Correo electrónico del usuario.
 *   - periodicidad_id (array) ID de periodicidad:
 *     - key (int) ID de periodicidad.
 *   - premio_max (float) Premio máximo permitido.
 *   - premio_max1 (float) Premio máximo permitido nivel 1.
 *   - premio_max2 (float) Premio máximo permitido nivel 2.
 *   - premio_max3 (float) Premio máximo permitido nivel 3.
 *   - cant_lineas (int) Cantidad de líneas.
 *   - apuesta_min (float) Apuesta mínima.
 *   - valor_directo (float) Valor directo.
 *   - valor_evento (float) Valor por evento.
 *   - valor_diario (float) Valor diario.
 *   - optimizar_parrilla (string) Indica si se optimiza la parrilla.
 *   - mostrar_optimizar_parrilla (string) Estilo para mostrar optimización de parrilla.
 *   - valor_cupo (float) Valor del cupo.
 *   - valor_cupo2 (float) Valor del segundo cupo.
 *   - porcen_comision (float) Porcentaje de comisión.
 *   - porcen_comision2 (float) Segundo porcentaje de comisión.
 *   - usupadre_id (array) ID del usuario padre:
 *     - key (int) ID del usuario padre.
 *   - usupadre2_id (array) ID del segundo usuario padre:
 *     - key (int) ID del segundo usuario padre.
 *   - nodos (string) Nodos del usuario.
 *   - texto_op1 (string) Texto de la opción 1.
 *   - texto_op2 (string) Texto de la opción 2.
 *   - url_op2 (string) URL de la opción 2.
 *   - texto_op3 (string) Texto de la opción 3.
 *   - url_op3 (string) URL de la opción 3.
 *   - clasificador1_id (array) ID del clasificador 1:
 *     - key (int) ID del clasificador 1.
 *   - clasificador2_id (array) ID del clasificador 2:
 *     - key (int) ID del clasificador 2.
 *   - clasificador3_id (array) ID del clasificador 3:
 *     - key (int) ID del clasificador 3.
 *
 * @throws Exception "No se encontró el usuario" con código "11".
 */


// Obtiene el ID de usuario del objeto JSON de parámetros.
$user_id = $json->params->user_id;

// Verifica que el ID de usuario no sea una cadena vacía, no esté definido y no sea la cadena "undefined".
if ($user_id != "" && $user_id != undefined && $user_id != "undefined") {
    $Usuario = new Usuario($user_id);
}

if ($Usuario != null) {
    /*Obtención detalles administrativos del usuario y almacenamiento
     de los mismos*/
    $UsuarioAdminDetails = $Usuario->getAdminDetails();

    $fecha_crea = $UsuarioAdminDetails["a.fecha_crea"];
    $fecha_ult = $UsuarioAdminDetails["a.fecha_ult"];
    $fecha_modif = $UsuarioAdminDetails["a.fecha_modif"];
    $perfil_id = $UsuarioAdminDetails["c.perfil_id"];
    if (stristr($perfil_id, 'PUNTO') or stristr($perfil_id, 'CONCESIONARIO')) {
        $mostrar_infopunto = "display:block;";
    }

    $dir_ip = $UsuarioAdminDetails["a.dir_ip"];
    if (stristr($perfil_id, 'ADMIN') or stristr($perfil_id, 'SA')) {
        $dir_ip = "";
    }

    $login = $UsuarioAdminDetails["a.login"];
    $nombre = $UsuarioAdminDetails["a.nombre"];
    $usuario_modif = $UsuarioAdminDetails[".usuario_modif"];
    $estado = $UsuarioAdminDetails["a.estado"];
    $estado_esp = $UsuarioAdminDetails["a.estado_esp"];
    $retirado = $UsuarioAdminDetails["a.retirado"];
    $observ = $UsuarioAdminDetails["a.observ"];
    $intentos = $UsuarioAdminDetails["a.intentos"];
    $texto_punto = "";
    if (stristr($perfil_id, 'PUNTO')) {
        $texto_punto = " PUNTO DE VENTA";
    } else {
        if ($perfil_id == 'CONCESIONARIO2') {
            $texto_punto = " SUBCONCESIONARIO";
        } else {
            if ($perfil_id == 'CONCESIONARIO') {
                $texto_punto = " CONCESIONARIO";
            }

        }
    }

    /*Almacenamiento información administrativa del usuario*/
    $pais_usuario = $UsuarioAdminDetails["a.pais_id"];
    $moneda_usuario = $UsuarioAdminDetails["a.moneda"];
    $idioma_usuario = $UsuarioAdminDetails["a.idioma"];
    $permite_recarga = $UsuarioAdminDetails[".permite_recarga"];
    $pinagent = $UsuarioAdminDetails[".pinagent"];
    $recibo_caja = $UsuarioAdminDetails[".recibo_caja"];
    $bloqueo_ventas = $UsuarioAdminDetails["a.bloqueo_ventas"];
    $permite_activareg = $UsuarioAdminDetails["a.permite_activareg"];
    $descripcion = $UsuarioAdminDetails["e.descripcion"];
    $nombre_contacto = $UsuarioAdminDetails["e.nombre_contacto"];
    $depto_id = $UsuarioAdminDetails["f.depto_id"];
    $ciudad_id = $UsuarioAdminDetails["e.ciudad_id"];
    $direccion = $UsuarioAdminDetails["e.direccion"];
    $barrio = $UsuarioAdminDetails["e.barrio"];
    $telefono = $UsuarioAdminDetails["e.telefono"];
    $email = $UsuarioAdminDetails["e.email"];
    $periodicidad_id = $UsuarioAdminDetails["e.periodicidad_id"];
    $premio_max = $UsuarioAdminDetails["g.premio_max"];
    $premio_max1 = $UsuarioAdminDetails["g.premio_max1"];
    $premio_max2 = $UsuarioAdminDetails["g.premio_max2"];
    $premio_max3 = $UsuarioAdminDetails["g.premio_max3"];
    $cant_lineas = $UsuarioAdminDetails["g.cant_lineas"];
    $apuesta_min = $UsuarioAdminDetails["g.apuesta_min"];
    $valor_directo = $UsuarioAdminDetails["g.valor_directo"];
    $valor_evento = $UsuarioAdminDetails["g.valor_evento"];
    $valor_diario = $UsuarioAdminDetails["g.valor_diario"];
    $optimizar_parrilla = $UsuarioAdminDetails["g.optimizar_parrilla"];
    if ($optimizar_parrilla == "S") {
        $mostrar_optimizar_parrilla = "block";
    } else {
        $mostrar_optimizar_parrilla = "none";
    }

    if ($optimizar_parrilla == "S" and stristr($perfil_id, 'PUNTO')) {
        $mostrar_optimizar_parrilla2 = "block";
    } else {
        $mostrar_optimizar_parrilla2 = "none";
    }

    $valor_cupo = $UsuarioAdminDetails["e.valor_cupo"];
    $valor_cupo2 = $UsuarioAdminDetails["e.valor_cupo2"];
    $porcen_comision = $UsuarioAdminDetails["e.porcen_comision"];
    $porcen_comision2 = $UsuarioAdminDetails["e.porcen_comision2"];
    $usupadre_id = $UsuarioAdminDetails["h.usupadre_id"];
    $usupadre2_id = $UsuarioAdminDetails["h.usupadre2_id"];
    $nodos = $UsuarioAdminDetails[".nodos"];
    $texto_op1 = $UsuarioAdminDetails["g.texto_op1"];
    $texto_op2 = $UsuarioAdminDetails["g.texto_op2"];
    $url_op2 = $UsuarioAdminDetails["g.url_op2"];
    $texto_op3 = $UsuarioAdminDetails["g.texto_op3"];
    $url_op3 = $UsuarioAdminDetails["g.url_op3"];
    $clasificador1_id = $UsuarioAdminDetails["e.clasificador1_id"];
    $clasificador2_id = $UsuarioAdminDetails["e.clasificador2_id"];
    $clasificador3_id = $UsuarioAdminDetails["e.clasificador3_id"];

    // Creación de la respuesta estructurada en un array.
    $response = array(
        "code" => 0,
        "data" => array(

            "usuario_id" => $user_id,
            "fecha_crea" => $fecha_crea,
            "fecha_ult" => $fecha_ult,
            "fecha_modif" => $fecha_modif,
            "perfil_id" => array(
                "key" => $perfil_id,
            ),
            "mostrar_infopunto" => array(
                "key" => $mostrar_infopunto,
            ),
            "dir_ip" => $dir_ip,
            "login" => $login,
            "nombre" => $nombre,
            "usuario_modif" => $usuario_modif,
            'clave' => '****',
            "estado" => array(
                "key" => $estado,
            ),
            "estado_esp" => array(
                "key" => $estado_esp,
            ),
            "retirado" => array(
                "key" => $retirado,
            ),
            "observ" => $observ,
            "intentos" => $intentos,
            "texto_punto" => $texto_punto,
            "pais_id" => array(
                "key" => $pais_usuario,
            ),
            "moneda" => array(
                "key" => $moneda_usuario,
            ),
            "idioma" => array(
                "key" => $idioma_usuario,
            ),
            "permite_recarga" => array(
                "key" => $permite_recarga,
            ),
            "pinagent" => array(
                "key" => $pinagent,
            ),
            "recibo_caja" => array(
                "key" => $recibo_caja,
            ),
            "bloqueo_ventas" => array(
                "key" => $bloqueo_ventas,
            ),
            "permite_activareg" => array(
                "key" => $permite_activareg,
            ),
            "descripcion" => $descripcion,
            "nombre_contacto" => $nombre_contacto,
            "depto_id" => array(
                "key" => $depto_id,
            ),
            "ciudad_id" => array(
                "key" => $ciudad_id,
            ),
            "direccion" => $direccion,
            "barrio" => $barrio,
            "telefono" => $telefono,
            "email" => $email,
            "periodicidad_id" => array(
                "key" => $periodicidad_id,
            ),
            "premio_max" => $premio_max,
            "premio_max1" => $premio_max1,
            "premio_max2" => $premio_max2,
            "premio_max3" => $premio_max3,
            "cant_lineas" => $cant_lineas,
            "apuesta_min" => $apuesta_min,
            "valor_directo" => $valor_directo,
            "valor_evento" => $valor_evento,
            "valor_diario" => $valor_diario,
            "optimizar_parrilla" => $optimizar_parrilla,
            "mostrar_optimizar_parrilla" => $mostrar_optimizar_parrilla,

            "valor_cupo" => $valor_cupo,
            "valor_cupo2" => $valor_cupo2,
            "porcen_comision" => $porcen_comision,
            "porcen_comision2" => $porcen_comision2,
            "usupadre_id" => array(
                "key" => $usupadre_id,
            ),
            "usupadre2_id" => array(
                "key" => $usupadre2_id,
            ),
            "nodos" => $nodos,
            "texto_op1" => $texto_op1,
            "texto_op2" => $texto_op2,
            "url_op2" => $url_op2,
            "texto_op3" => $texto_op3,
            "url_op3" => $url_op3,
            "clasificador1_id" => array(
                "key" => $clasificador1_id,
            ),
            "clasificador2_id" => array(
                "key" => $clasificador2_id,
            ),
            "clasificador3_id" => array(
                "key" => $clasificador3_id,
            ),

        ),
    );
} else {
    /*Lanza una excepción si no se encuentra el usuario.*/
    throw new Exception("No se encontro el usuario", "11");
}