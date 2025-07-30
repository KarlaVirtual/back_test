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
 * @param string $json->params->usuario_id \* ID del usuario
 * @param string $json->params->login \* Nombre de usuario para el login
 * @param string $json->params->nombre \* Nombre completo del usuario
 * @param string $json->params->clave \* Contraseña del usuario
 * @param string $json->params->perfil_id \* ID del perfil del usuario
 * @param string $json->params->permite_recarga \* Indica si se permite recarga (S/N)
 * @param string $json->params->pinagent \* Pin del agente
 * @param string $json->params->recibo_caja \* Indica si se permite recibo de caja (S/N)
 * @param string $json->params->bloqueo_ventas \* Indica si se permite bloqueo de ventas (S/N)
 * @param string $json->params->permite_activareg \* Indica si se permite activar registro (S/N)
 * @param string $json->params->estado \* Estado del usuario (A/I/R)
 * @param string $json->params->estado_esp \* Estado especial del usuario (A/I)
 * @param string $json->params->observ \* Observaciones sobre el usuario
 * @param string $json->params->intentos \* Número de intentos de login
 * @param string $json->params->pais_id \* ID del país del usuario
 * @param string $json->params->moneda \* Moneda del usuario
 * @param string $json->params->idioma \* Idioma del usuario
 * @param string $json->params->descripcion \* Descripción del usuario
 * @param string $json->params->nombre_contacto \* Nombre del contacto del usuario
 * @param string $json->params->ciudad_id \* ID de la ciudad del usuario
 * @param string $json->params->direccion \* Dirección del usuario
 * @param string $json->params->barrio \* Barrio del usuario
 * @param string $json->params->telefono \* Teléfono del usuario
 * @param string $json->params->email \* Email del usuario
 * @param string $json->params->periodicidad_id \* ID de la periodicidad
 * @param string $json->params->clasificador1_id \* ID del primer clasificador
 * @param string $json->params->clasificador2_id \* ID del segundo clasificador
 * @param string $json->params->clasificador3_id \* ID del tercer clasificador
 * @param string $json->params->premio_max \* Premio máximo
 * @param string $json->params->premio_max1 \* Premio máximo 1
 * @param string $json->params->premio_max2 \* Premio máximo 2
 * @param string $json->params->premio_max3 \* Premio máximo 3
 * @param string $json->params->cant_lineas \* Cantidad de líneas
 * @param string $json->params->apuesta_min \* Apuesta mínima
 * @param string $json->params->valor_directo \* Valor directo
 * @param string $json->params->valor_evento \* Valor del evento
 * @param string $json->params->valor_diario \* Valor diario
 * @param string $json->params->usupadre_id \* ID del usuario padre
 * @param string $json->params->usupadre2_id \* ID del segundo usuario padre
 * @param string $json->params->valor_cupo \* Valor del cupo
 * @param string $json->params->valor_cupo2 \* Valor del segundo cupo
 * @param string $json->params->porcen_comision \* Porcentaje de comisión
 * @param string $json->params->porcen_comision2 \* Segundo porcentaje de comisión
 * @param string $json->params->cant_terminal \* Cantidad de terminales
 * @param string $json->params->clave_terminal \* Clave del terminal
 *
 * @throws Exception Si hay un error en la validación de los campos
 * @throws Exception Si el usuario solicitante es 126457
 *
 *
 * @return array
 *  -code : int Código de respuesta
 *  -message : string Mensaje de respuesta
 *  -data : array
 *  -auth_token : string Token de autenticación
 */

exit();
$usuario_id = $json->params->usuario_id;
$login = $json->params->login;
$nombre = $json->params->nombre;
$clave = $json->params->clave;
$perfil_id = $json->params->perfil_id;
$permite_recarga = $json->params->permite_recarga;
$pinagent = $json->params->pinagent;
$recibo_caja = $json->params->recibo_caja;
$bloqueo_ventas = $json->params->bloqueo_ventas;
$permite_activareg = $json->params->permite_activareg;
$estado = $json->params->estado;
$estado_esp = $json->params->estado_esp;
$observ = $json->params->observ;
$intentos = $json->params->intentos;
$pais_usuario = $json->params->pais_id;
$moneda_usuario = $json->params->moneda;
$idioma_usuario = $json->params->idioma;
$descripcion = $json->params->descripcion;
$nombre_contacto = $json->params->nombre_contacto;
$ciudad_id = $json->params->ciudad_id;
$direccion = $json->params->direccion;
$barrio = $json->params->barrio;
$telefono = $json->params->telefono;
$email = $json->params->email;
$periodicidad_id = $json->params->periodicidad_id;
$clasificador1_id = $json->params->clasificador1_id;
$clasificador2_id = $json->params->clasificador2_id;
$clasificador3_id = $json->params->clasificador3_id;
$premio_max = $json->params->premio_max;
$premio_max1 = $json->params->premio_max1;
$premio_max2 = $json->params->premio_max2;
$premio_max3 = $json->params->premio_max3;
$cant_lineas = $json->params->cant_lineas;
$apuesta_min = $json->params->apuesta_min;
$valor_directo = $json->params->valor_directo;
$valor_evento = $json->params->valor_evento;
$valor_diario = $json->params->valor_diario;
$optimizar_parrilla = "N";
$texto_op1 = "";
$texto_op2 = "";
$url_op2 = "";
$texto_op3 = "";
$url_op3 = "";
$usupadre_id = $json->params->usupadre_id;
$usupadre2_id = $json->params->usupadre2_id;
$valor_cupo = $json->params->valor_cupo;
$valor_cupo2 = $json->params->valor_cupo2;
$porcen_comision = $json->params->porcen_comision;
$porcen_comision2 = $json->params->porcen_comision2;
$cant_terminal = $json->params->cant_terminal;
$clave_terminal = $json->params->clave_terminal;

//Incializa valores por defecto
$premio_max = 100;
$premio_max1 = 100;
$premio_max2 = 100;
$premio_max3 = 100;
$cant_lineas = 14;
$apuesta_min = 2;
$valor_directo = 100;
$valor_evento = 0;
$valor_diario = 0;
$valor_cupo = 0;
$valor_cupo2 = 0;
$porcen_comision = 0;
$porcen_comision2 = 0;

// Valida los parametros ingresados
$seguir = true; // Bandera para continuar el proceso
if (!ValidarCampo($usuario_id, "N", "N", 20)) { // Valida el campo usuario_id
    $seguir = false; // Si no es válido, cambia la bandera a false
}

if (!ValidarCampo($login, "S", "T", 15)) {
    $seguir = false;
}

// Se valida el campo $nombre
if (!ValidarCampo($nombre, "S", "T", 150)) {
    $seguir = false;
}

// Se verifica si $usuario_id tiene longitud mayor a 0
if (strlen($usuario_id) > 0) {
    // Se valida el campo $clave cuando $usuario_id tiene valor
    if (!ValidarCampo($clave, "N", "T", 25)) {
        $seguir = false; // Si la validación falla, se establece $seguir como falso
    }

} else {
    // Se valida el campo $clave cuando $usuario_id está vacío
    if (!ValidarCampo($clave, "S", "T", 25)) {
        $seguir = false; // Si la validación falla, se establece $seguir como falso
    }

}

// Se valida el campo $perfil_id
if (!ValidarCampo($perfil_id, "S", "T", 15)) {
    $seguir = false; // Si la validación falla, se establece $seguir como falso
}

// Se valida el campo $permite_recarga
if (!ValidarCampo($permite_recarga, "S", "T", 1)) {
    $seguir = false; // Si la validación falla, se establece $seguir como falso
} else {
    // Se verifica si $permite_recarga tiene un valor no válido
    if ($permite_recarga != "S" and $permite_recarga != "N") {
        $seguir = false; // Si el valor es inválido, se establece $seguir como falso
    }

}

// Se valida el campo $pinagent
if (!ValidarCampo($pinagent, "S", "T", 1)) {
    $seguir = false; // Si la validación falla, se establece $seguir como falso
} else {
    // Se verifica si $pinagent tiene un valor no válido
    if ($pinagent != "S" and $pinagent != "N") {
        $seguir = false; // Si el valor es inválido, se establece $seguir como falso
    }

}

// Se valida el campo $recibo_caja
if (!ValidarCampo($recibo_caja, "S", "T", 1)) {
    $seguir = false; // Si la validación falla, se establece $seguir como falso
} else {
    // Se verifica si $recibo_caja tiene un valor no válido
    if ($recibo_caja != "S" and $recibo_caja != "N") {
        $seguir = false; // Si el valor es inválido, se establece $seguir como falso
    }

}

// Se valida el campo $bloqueo_ventas
if (!ValidarCampo($bloqueo_ventas, "S", "T", 1)) {
    $seguir = false; // Si la validación falla, se establece $seguir como falso
} else {
    // Verifica si $bloqueo_ventas no es "S" ni "N", en cuyo caso se establece $seguir como false.
    if ($bloqueo_ventas != "S" and $bloqueo_ventas != "N") {
        $seguir = false;
    }

}

// Valida el campo $permite_activareg, debe ser "S" o "T" con longitud mínima de 1, si no, se establece $seguir como false.
if (!ValidarCampo($permite_activareg, "S", "T", 1)) {
    $seguir = false;
} else {
    // Verifica si $permite_activareg no es "S" ni "N", en cuyo caso se establece $seguir como false.
    if ($permite_activareg != "S" and $permite_activareg != "N") {
        $seguir = false;
    }

}

// Valida el campo $pais_usuario, debe ser "S" o "N" con longitud máxima de 20, si no, se establece $seguir como false.
if (!ValidarCampo($pais_usuario, "S", "N", 20)) {
    $seguir = false;
}

// Valida el campo $moneda_usuario, debe ser "S" o "T" con longitud máxima de 3, si no, se establece $seguir como false.
if (!ValidarCampo($moneda_usuario, "S", "T", 3)) {
    $seguir = false;
}

// Valida el campo $idioma_usuario, debe ser "S" o "T" con longitud máxima de 2, si no, se establece $seguir como false.
if (!ValidarCampo($idioma_usuario, "S", "T", 2)) {
    $seguir = false;
}

// Valida el campo $estado, debe ser "S" o "T" con longitud máxima de 1, si no, se establece $seguir como false.
if (!ValidarCampo($estado, "S", "T", 1)) {
    $seguir = false;
} else {
    // Verifica si $estado no es "A", "I" o "R", en cuyo caso se establece $seguir como false.
    if ($estado != "A" and $estado != "I" and $estado != "R") {
        $seguir = false;
    }

}

// Valida el campo $estado_esp, debe ser "S" o "T" con longitud máxima de 1, si no, se establece $seguir como false.
if (!ValidarCampo($estado_esp, "S", "T", 1)) {
    $seguir = false;
} else {
    // Verifica si $estado_esp no es "A" o "I", en cuyo caso se establece $seguir como false.
    if ($estado_esp != "A" and $estado_esp != "I") {
        $seguir = false;
    }

}

// Valida el campo $observ, debe ser "N" o "T" con longitud máxima de 150, si no, se establece $seguir como false.
if (!ValidarCampo($observ, "N", "T", 150)) {
    $seguir = false;
}

// Verifica si el campo $intentos es válido, asignando false a $seguir si no lo es
if (!ValidarCampo($intentos, "S", "N", 1)) {
    $seguir = false;
}

// Verifica si el campo $premio_max es válido, asignando false a $seguir si no lo es
if (!ValidarCampo($premio_max, "S", "N", 10)) {
    $seguir = false;
} else {
    // Verifica si el valor de $premio_max es menor o igual a 0, asignando false a $seguir si es así
    if (floatval($premio_max) <= 0) {
        $seguir = false;
    }

}

// Verifica si el campo $premio_max1 es válido, asignando false a $seguir si no lo es
if (!ValidarCampo($premio_max1, "S", "N", 10)) {
    $seguir = false;
} else {
    // Verifica si el valor de $premio_max1 es menor o igual a 0, asignando false a $seguir si es así
    if (floatval($premio_max1) <= 0) {
        $seguir = false;
    }

}
if (!ValidarCampo($premio_max2, "S", "N", 10)) {
    $seguir = false;
} else {
    // Verifica si el valor de $premio_max2 es menor o igual a 0, asignando false a $seguir si es así
    if (floatval($premio_max2) <= 0) {
        $seguir = false;
    }

}

// Verifica si el campo $premio_max3 es válido, asignando false a $seguir si no lo es
if (!ValidarCampo($premio_max3, "S", "N", 10)) {
    $seguir = false;
} else {
    // Verifica si el valor de $premio_max3 es menor o igual a 0, asignando false a $seguir si es así
    if (floatval($premio_max3) <= 0) {
        $seguir = false;
    }

}

// Verifica si el campo $valor_directo es válido, asignando false a $seguir si no lo es
if (!ValidarCampo($valor_directo, "S", "N", 10)) {
    $seguir = false;
} else {
    // Validar si el valor directo es menor o igual a 0
    if (floatval($valor_directo) <= 0) {
        $seguir = false; // Si es menor o igual a 0, se detiene el proceso
    }

}

// Validar el campo de evento
if (!ValidarCampo($valor_evento, "S", "N", 10)) {
    $seguir = false; // Si no pasa la validación, se detiene el proceso
} else {
    // Validar si el valor del evento es negativo
    if (floatval($valor_evento) < 0) {
        $seguir = false; // Si es negativo, se detiene el proceso
    }

}

// Validar el campo diario
if (!ValidarCampo($valor_diario, "S", "N", 10)) {
    $seguir = false; // Si no pasa la validación, se detiene el proceso
} else {
    // Validar si el valor diario es negativo
    if (floatval($valor_diario) < 0) {
        $seguir = false; // Si es negativo, se detiene el proceso
    }

}

// Validar el campo de líneas
if (!ValidarCampo($cant_lineas, "S", "N", 2)) {
    $seguir = false; // Si no pasa la validación, se detiene el proceso
} else {
    // Validar si la cantidad de líneas es menor o igual a 0
    if (floatval($cant_lineas) <= 0) {
        $seguir = false; // Si es menor o igual a 0, se detiene el proceso
    }

}

// Validar el campo de apuesta mínima
if (!ValidarCampo($apuesta_min, "S", "N", 10)) {
    $seguir = false; // Si no pasa la validación, se detiene el proceso
} else {
    // Validar si la apuesta mínima es menor o igual a 0
    if (floatval($apuesta_min) <= 0) {
        $seguir = false; // Si es menor o igual a 0, se detiene el proceso
    }

}

// Validar el campo de optimización de parrilla
if (!ValidarCampo($optimizar_parrilla, "S", "T", 1)) {
    $seguir = false; // Si no pasa la validación, se detiene el proceso
} else {
    // Verifica si la variable $optimizar_parrilla es diferente de "S" y "N"
    if ($optimizar_parrilla != "S" and $optimizar_parrilla != "N") {
        return false; // Retorna falso si la validación falla
    }

}

//Validaciones especificas cuando existe optimizaci�n de parrilla
if ($optimizar_parrilla == "S") {
    // Valida el campo $texto_op1
    if (!ValidarCampo($texto_op1, "S", "T", 100)) {
        $seguir = false; // Marca que no se debe continuar si la validación falla
    }

    // Valida el campo $texto_op2
    if (!ValidarCampo($texto_op2, "S", "T", 100)) {
        $seguir = false; // Marca que no se debe continuar si la validación falla
    }

    // Valida el campo $url_op2
    if (!ValidarCampo($url_op2, "S", "T", 150)) {
        $seguir = false; // Marca que no se debe continuar si la validación falla
    }

    //Valida si es un punto de venta para validar los textos de los nodos
    if ($perfil_id == "PUNTOVENTA") {
        // Valida el campo $texto_op3
        if (!ValidarCampo($texto_op3, "S", "T", 100)) {
            $seguir = false; // Marca que no se debe continuar si la validación falla
        }

        // Valida el campo $url_op3
        if (!ValidarCampo($url_op3, "S", "T", 150)) {
            $seguir = false; // Marca que no se debe continuar si la validación falla
        }

    }
}

//Validaciones especificas de informacion de punto de venta o concesionario
if (stristr($perfil_id, 'PUNTO') or stristr($perfil_id, 'CONCESIONARIO')) {
    /*El código valida los campos descripcion, nombre_contacto y ciudad_id para un punto de venta o concesionario, y si alguna validación falla, establece la bandera $seguir en false.*/
    if (!ValidarCampo($descripcion, "S", "T", 150)) {
        $seguir = false;
    }

    if (!ValidarCampo($nombre_contacto, "S", "T", 150)) {
        $seguir = false;
    }

    if (!ValidarCampo($ciudad_id, "S", "N", 20)) {
        $seguir = false;
    }

    /*El código valida los campos direccion, barrio y telefono utilizando la función ValidarCampo. Si alguna validación falla, establece la bandera $seguir en false.*/
    if (!ValidarCampo($direccion, "S", "T", 150)) {
        $seguir = false;
    }

    if (!ValidarCampo($barrio, "N", "T", 100)) {
        $seguir = false;
    }

    if (!ValidarCampo($telefono, "S", "T", 20)) {
        $seguir = false;
    }

    /*El código valida los campos email, periodicidad_id y clasificador1_id utilizando la función ValidarCampo. Si alguna validación falla, establece la bandera $seguir en false.*/
    if (!ValidarCampo($email, "N", "E", 150)) {
        $seguir = false;
    }

    if (!ValidarCampo($periodicidad_id, "S", "N", 20)) {
        $seguir = false;
    }

    if (!ValidarCampo($clasificador1_id, "S", "N", 20)) {
        $seguir = false;
    }

    /*El código valida los campos clasificador2_id, clasificador3_id y usupadre_id utilizando la función ValidarCampo. Si alguna validación falla, establece la bandera $seguir en false.*/
    if (!ValidarCampo($clasificador2_id, "S", "N", 20)) {
        $seguir = false;
    }

    if (!ValidarCampo($clasificador3_id, "S", "N", 20)) {
        $seguir = false;
    }

    if (!ValidarCampo($usupadre_id, "N", "N", 20)) {
        $seguir = false;
    }

    /*El código valida los campos usupadre2_id y valor_cupo, asegurándose de que cumplan con ciertos criterios antes de continuar con el proceso*/
    if (!ValidarCampo($usupadre2_id, "N", "N", 20)) {
        $seguir = false;
    }

    if (strlen($usupadre2_id) > 0 and strlen($usupadre_id) <= 0) {
        $seguir = false;
    }

    if (!ValidarCampo($valor_cupo, "S", "N", 10)) {
        $seguir = false;
    } else {
        if (floatval($valor_cupo) < 0) {
            $seguir = false;
        }

    }

    /*El código valida los campos valor_cupo2 y porcen_comision, asegurándose de que cumplan con ciertos criterios antes de continuar con el proceso*/
    if (!ValidarCampo($valor_cupo2, "S", "N", 10)) {
        $seguir = false;
    } else {
        if (floatval($valor_cupo2) < 0) {
            $seguir = false;
        }

    }
    if (!ValidarCampo($porcen_comision, "S", "N", 5)) {
        $seguir = false;
    } else {
        if (floatval($porcen_comision) < 0 or floatval($porcen_comision) > 100) {
            $seguir = false;
        }

    }
    /*El código valida el campo porcen_comision2 y asigna false a la variable seguir si no pasa la validación o si su valor no está entre 0 y 100.
    Luego, verifica si el campo barrio está vacío y lo asigna a NULL si es así, o lo envuelve en comillas simples si no.*/
    if (!ValidarCampo($porcen_comision2, "S", "N", 5)) {
        $seguir = false;
    } else {
        if (floatval($porcen_comision2) < 0 or floatval($porcen_comision2) > 100) {
            $seguir = false;
        }

    }
    if (strlen($barrio) <= 0) {
        $barrio = "NULL";
    } else {
        $barrio = "'" . $barrio . "'";
    }

}

//Validaciones especificas cuando hay creaci�n autom�tica de terminales nodo
/*El código valida y asigna valores a las variables $cant_terminal y $clave_terminal para la creación automática de terminales nodo,
 asegurándose de que cumplan con ciertos criterios antes de continuar con el proceso.*/
$cant_terminal = 0;
$clave_terminal = "123";
if (strlen($usuario_id) <= 0 and stristr($perfil_id, 'PUNTO')) {
    if (!ValidarCampo($cant_terminal, "S", "N", 2)) {
        $seguir = false;
    } else {
        if (floatval($cant_terminal) > 0) {
            if (!ValidarCampo($clave_terminal, "S", "T", 25)) {
                $seguir = false;
            }

        }
    }
}

if ($seguir) {

    if (strlen($usuario_id) > 0 && $usuario_id > 0) {

        /*El código crea una instancia de la clase Usuario, asigna valores a sus propiedades y establece una cadena de estado basada en el valor de la propiedad estado.*/
        $Usuario = new Usuario($usuario_id);

        $Usuario->usuarioId = $usuario_id;
        $Usuario->retirado = "N";
        $Usuario->fechaRetiro = '';
        $Usuario->horaRetiro = '';
        $Usuario->usuretiroId = 0;

        //Verifica cual es el estado
        $strEstado = ",retirado='N',fecha_retiro='',hora_retiro='',usuretiro_id=0 ";
        /*El código verifica si el estado del usuario es "R" (retirado). Si el estado actual del usuario es "N" (nuevo), actualiza la fecha y hora de retiro,
        el ID del usuario que realiza el retiro y cambia el estado a "S" (activo). Luego, establece el estado y el estado especial del usuario a "I" (inactivo)
         y agrega una observación de "Retirado".*/
        if ($estado == "R") {
            if ($Usuario->estado == "N") {

                $Usuario->fechaRetiro = date('Y-m-d');
                $Usuario->horaRetiro = date('H:i');
                $Usuario->usuretiroId = $json->session->usuario;
                $Usuario->estado = "S";

            }

            $Usuario->estado = "I";
            $Usuario->estadoEsp = "I";
            $Usuario->observ = "Retirado";

            $estado = "I";
            $estado_esp = "I";
            $observ = "Retirado";

        }
        /*El código asigna valores a las propiedades de un objeto Usuario utilizando variables proporcionadas.*/
        $Usuario->login = $login;
        $Usuario->nombre = $nombre;
        $Usuario->estado = $estado;
        $Usuario->estadoEsp = $estado_esp;
        $Usuario->bloqueoVentas = $bloqueo_ventas;
        $Usuario->permiteActivareg = $permite_activareg;
        $Usuario->observ = $observ;
        $Usuario->estadoAnt = $estado;
        $Usuario->usucreaId = $json->session->usuario;
        $Usuario->paisId = $pais_usuario;

        /*El código asigna valores a las propiedades de un objeto Usuario, crea una instancia del DAO UsuarioMySqlDAO, actualiza la información del usuario
         en la base de datos y obtiene la transacción actual.*/
        $Usuario->moneda = $moneda_usuario;
        $Usuario->idioma = $idioma_usuario;
        $Usuario->mandante = $json->session->mandante;

        // Creación de una instancia del DAO para usuarios en MySQL
        $UsuarioMySqlDAO = new Backend\mysql\UsuarioMySqlDAO();
        // Actualización de la información del usuario en la base de datos
        $UsuarioMySqlDAO->update($Usuario);

        $Transaccion = $UsuarioMySqlDAO->getTransaction();

        $UsuarioConfig = new UsuarioConfig($usuario_id);
        // Asignación de propiedades al objeto $UsuarioConfig
        $UsuarioConfig->permiteRecarga = $permite_recarga; // Indica si se permite recarga
        $UsuarioConfig->pinagent = $pinagent; // Pin del agente asociado al usuario
        $UsuarioConfig->reciboCaja = $recibo_caja; // Recibo de caja asociado al usuario
        $UsuarioConfig->mandante = $json->session->mandante; // Mandante del usuario

        // Creación de una instancia del DAO para la configuración del usuario en MySQL con transacción
        $UsuarioConfigMySqlDAO = new \Backend\mysql\UsuarioConfigMySqlDAO($Transaccion);
        // Actualización de la configuración del usuario en la base de datos
        $UsuarioConfigMySqlDAO->update($UsuarioConfig);

        if (stristr($perfil_id, 'PUNTO') or stristr($perfil_id, 'CONCESIONARIO')) {

            /*El código crea una instancia de la clase PuntoVenta y asigna valores a sus propiedades utilizando variables proporcionadas.*/
            $PuntoVenta = new PuntoVenta("", $usuario_id);
            $PuntoVenta->descripcion = $descripcion;
            $PuntoVenta->nombreContacto = $nombre_contacto;
            $PuntoVenta->ciudadId = $ciudad_id;
            $PuntoVenta->direccion = $direccion;
            $PuntoVenta->barrio = $barrio;
            $PuntoVenta->telefono = $telefono;
            $PuntoVenta->email = $email;
            $PuntoVenta->periodicidadId = $periodicidad_id;
            $PuntoVenta->clasificador1Id = $clasificador1_id;

            /*El código asigna valores a las propiedades de un objeto PuntoVenta utilizando variables proporcionadas.*/
            $PuntoVenta->clasificador2Id = $clasificador2_id;
            $PuntoVenta->clasificador3Id = $clasificador3_id;
            $PuntoVenta->valorCupo = $valor_cupo;
            $PuntoVenta->valorCupo2 = $valor_cupo2;
            $PuntoVenta->porcenComision = $porcen_comision;
            $PuntoVenta->porcenComision2 = $porcen_comision2;
            $PuntoVenta->estado = $estado;
            $PuntoVenta->usuarioId = $numero;
            $PuntoVenta->mandante = $json->session->mandante;

            /*El código crea una instancia de PuntoVentaMySqlDAO con una transacción y actualiza un objeto PuntoVenta en la base de datos.*/
            $PuntoVentaMySqlDAO = new \Backend\mysql\PuntoVentaMySqlDAO($Transaccion);
            $PuntoVentaMySqlDAO->update($PuntoVenta);

        }

        //Verifica si es un punto de venta
        if (stristr($perfil_id, 'PUNTO')) {

            //Valida si hay un concesionario seleccionado
            if (strlen($usupadre_id) > 0) {
                //Valida subconcesionario
                if (strlen($usupadre2_id) <= 0) {
                    $usupadre2_id = 0;
                }

                try {
                    // Crea una nueva instancia de la clase Concesionario
                    $Concesionario = new \Backend\dto\Concesionario($usuario_id);
                    $Concesionario->usupadreId = $usupadre_id; // Asigna el ID del padre
                    $Concesionario->usupadre2Id = $usupadre2_id; // Asigna el ID del subconcesionario
                    $Concesionario->usuhijoId = $usuario_id; // Asigna el ID del hijo
                    $Concesionario->mandante = $json->session->mandante; // Asigna el mandante desde la sesión

                    // Crea una instancia del DAO para realizar operaciones en la base de datos
                    $ConcesionarioMySqlDAO = new \Backend\mysql\ConcesionarioMySqlDAO($Transaccion);
                    $ConcesionarioMySqlDAO->update($Concesionario);

                } catch (Exception $e) {
                    // En caso de error, crea una nueva instancia de la clase Concesionario
                    $Concesionario = new \Backend\dto\Concesionario();
                    $Concesionario->usupadreId = $usupadre_id; // Asigna el ID del padre
                    $Concesionario->usupadre2Id = $usupadre2_id; // Asigna el ID del subconcesionario
                    $Concesionario->usuhijoId = $usuario_id; // Asigna el ID del hijo
                    $Concesionario->mandante = $json->session->mandante; // Asigna el mandante desde la sesión

                    // Crea una instancia del DAO para realizar operaciones en la base de datos
                    $ConcesionarioMySqlDAO = new \Backend\mysql\ConcesionarioMySqlDAO($Transaccion);
                    $ConcesionarioMySqlDAO->insert($Concesionario);
                }

            } else {
                try {
                    // Crea una nueva instancia de la clase Concesionario
                    $Concesionario = new \Backend\dto\Concesionario($usuario_id);
                    $Concesionario->usupadreId = $usupadre_id; // Asigna el ID del padre
                    $Concesionario->usupadre2Id = $usupadre2_id; // Asigna el ID del subconcesionario
                    $Concesionario->usuhijoId = $usuario_id; // Asigna el ID del hijo
                    $Concesionario->mandante = $json->session->mandante; // Asigna el mandante desde la sesión

                    // Crea una instancia del DAO para realizar operaciones en la base de datos
                    $ConcesionarioMySqlDAO = new \Backend\mysql\ConcesionarioMySqlDAO($Transaccion);
                    $ConcesionarioMySqlDAO->delete($Concesionario);

                } catch (Exception $e) {
                    // Manejo de excepciones vacío en caso de error al eliminar
                }

            }
        }

        //Verifica si es un subconcesionario
        if ($perfil_id == 'CONCESIONARIO2') {
            //Valida si hay un concesionario seleccionado
            if (strlen($usupadre_id) > 0) {
                //Valida subconcesionario
                if (strlen($usupadre2_id) <= 0) {
                    $usupadre2_id = 0;
                }

/*El código intenta actualizar un objeto Concesionario en la base de datos. Si ocurre una excepción, se captura y maneja el error.*/
                try {

                    $Concesionario = new \Backend\dto\Concesionario($usuario_id);
                    $Concesionario->usupadreId = $usupadre_id;
                    $Concesionario->usupadre2Id = $usupadre2_id;
                    $Concesionario->usuhijoId = $usuario_id;
                    $Concesionario->mandante = $json->session->mandante;

                    $ConcesionarioMySqlDAO = new \Backend\mysql\ConcesionarioMySqlDAO($Transaccion);
                    $ConcesionarioMySqlDAO->update($Concesionario);

                } catch (Exception $e) {
                    /*El código maneja una excepción al intentar actualizar un objeto Concesionario en la base de datos. Si ocurre una excepción,
                     crea un nuevo objeto Concesionario y lo inserta en la base de datos.*/
                    $Concesionario = new \Backend\dto\Concesionario();
                    $Concesionario->usupadreId = $usupadre_id;
                    $Concesionario->usupadre2Id = $usupadre2_id;
                    $Concesionario->usuhijoId = $usuario_id;
                    $Concesionario->mandante = $json->session->mandante;

                    $ConcesionarioMySqlDAO = new \Backend\mysql\ConcesionarioMySqlDAO($Transaccion);
                    $ConcesionarioMySqlDAO->insert($Concesionario);
                }

            } else {

                /*El código intenta eliminar un registro de la tabla Concesionario en la base de datos utilizando un objeto ConcesionarioMySqlDAO.
                Si ocurre una excepción, se captura y maneja el error.*/
                try {

                    $Concesionario = new \Backend\dto\Concesionario($usuario_id);
                    $Concesionario->usupadreId = $usupadre_id;
                    $Concesionario->usupadre2Id = $usupadre2_id;
                    $Concesionario->usuhijoId = $usuario_id;
                    $Concesionario->mandante = $json->session->mandante;

                    $ConcesionarioMySqlDAO = new \Backend\mysql\ConcesionarioMySqlDAO($Transaccion);
                    $ConcesionarioMySqlDAO->delete($Concesionario);

                } catch (Exception $e) {
                    /*El código maneja una excepción al intentar eliminar un registro de la tabla Concesionario en la base de datos utilizando un objeto ConcesionarioMySqlDAO.
                     Si ocurre una excepción, se captura y maneja el error.*/

                }

            }
        }

        /*El código realiza una confirmación de transacción y cambia la clave del usuario si se cumplen ciertas condiciones.*/
        $Transaccion->commit();

        //Verifica si fue pasada la clave para cambiarla
        $strClave = "";
        if (strlen($clave) > 3 and $clave != "****") {

            $Usuario2 = new Usuario($usuario_id);
            $UsuarioCambioClave = $Usuario2->changeClave($clave);

        }

        /*El código crea una respuesta JSON con un código de estado y un token de autenticación.*/
        $response = array();

        $response['code'] = 0;

        $data = array();

        $data["auth_token"] = "543456ASDASDA";
        $data["result"] = 0;

        $response['data'] = $data;

    } else {
        /*El código crea una instancia de la clase Usuario y asigna valores a sus propiedades utilizando variables proporcionadas.*/
        $Usuario = new Usuario();
        $Usuario->login = $login;
        $Usuario->nombre = $nombre;
        $Usuario->estado = $estado;
        $Usuario->estadoEsp = $estado_esp;
        $Usuario->bloqueoVentas = $bloqueo_ventas;
        $Usuario->permiteActivareg = $permite_activareg;
        $Usuario->observ = $observ;
        $Usuario->estadoAnt = $estado;
        $Usuario->usucreaId = $json->session->usuario;

        /*El código asigna valores a las propiedades de un objeto Usuario y lanza una excepción si el usuarioId es igual a 126457.*/
        $Usuario->paisId = $pais_usuario;
        $Usuario->moneda = $moneda_usuario;
        $Usuario->idioma = $idioma_usuario;
        $Usuario->mandante = $json->session->mandante;

        // Verifica si el ID del usuario es 126457, en cuyo caso lanza una excepción.
        if ($Usuario->usuarioId == 126457) {

            throw new Exception("Error", "100001");

        }
        // Comprueba si el login del usuario ya existe en el sistema.
        if ($Usuario->exitsLogin()) {

            $seguir = false;
        }

        if ($seguir) {

            /*El código genera un nuevo número de usuario, lo actualiza en la base de datos y asigna este número al objeto Usuario.*/
            $Consecutivo = new Consecutivo("", "USU", "");

            $numero = $Consecutivo->getNumero();
            $Consecutivo->setNumero($numero + 1);

            $ConsecutivoMySqlDAO = new ConsecutivoMySqlDAO();
            $ConsecutivoMySqlDAO->update($Consecutivo);
            $ConsecutivoMySqlDAO->getTransaction()->commit();

            $Usuario->usuarioId = $numero;

            /*El código crea una instancia de UsuarioMySqlDAO, inserta un usuario, obtiene la transacción, crea una instancia de UsuarioConfig, y asigna valores a sus propiedades.*/
            $UsuarioMySqlDAO = new Backend\mysql\UsuarioMySqlDAO();
            $UsuarioMySqlDAO->insert($Usuario);

            $Transaccion = $UsuarioMySqlDAO->getTransaction();

            $UsuarioConfig = new UsuarioConfig();
            $UsuarioConfig->usuarioId = $numero;
            $UsuarioConfig->permiteRecarga = $permite_recarga;
            $UsuarioConfig->pinagent = $pinagent;
            $UsuarioConfig->reciboCaja = $recibo_caja;
            $UsuarioConfig->mandante = $json->session->mandante;

            $UsuarioConfigMySqlDAO = new \Backend\mysql\UsuarioConfigMySqlDAO($Transaccion);
            $UsuarioConfigMySqlDAO->insert($UsuarioConfig);

            //Generación de objeto UsuarioPremiomax y asignación de valores
            $UsuarioPremiomax = new \Backend\dto\UsuarioPremiomax();
            $UsuarioPremiomax->usuarioId = $numero;
            $UsuarioPremiomax->premioMax = $premio_max;
            $UsuarioPremiomax->premioMax1 = $premio_max1;
            $UsuarioPremiomax->premioMax2 = $premio_max2;
            $UsuarioPremiomax->premioMax3 = $premio_max3;
            $UsuarioPremiomax->cantLineas = $cant_lineas;
            $UsuarioPremiomax->apuestaMin = $apuesta_min;
            $UsuarioPremiomax->valorDirecto = $valor_directo;
            $UsuarioPremiomax->valorEvento = $valor_evento;
            $UsuarioPremiomax->valorDiario = $valor_diario;
            $UsuarioPremiomax->optimizarParrilla = $optimizar_parrilla;
            $UsuarioPremiomax->textoOp1 = $texto_op1;
            $UsuarioPremiomax->textoOp2 = $texto_op2;
            $UsuarioPremiomax->textoOp3 = $texto_op3;
            $UsuarioPremiomax->urlOp2 = $url_op2;
            $UsuarioPremiomax->fechaModif = date('Y-m-d H:i:s');
            $UsuarioPremiomax->mandante = $json->session->mandante;
            $UsuarioPremiomax->usumodifId = $json->session->usuario;

            //Generación de nuevo MySqlDAO e inserción de UsuarioPremiomax
            $UsuarioPremiomaxMySqlDAO = new \Backend\mysql\UsuarioPremiomaxMySqlDAO($Transaccion);
            $UsuarioPremiomaxMySqlDAO->insert($UsuarioPremiomax);

            $UsuarioPerfil = new \Backend\dto\UsuarioPerfil();
            $UsuarioPerfil->usuarioId = $numero;
            $UsuarioPerfil->perfilId = $perfil_id;
            $UsuarioPerfil->mandante = $json->session->mandante;

            $UsuarioPerfilMySqlDAO = new \Backend\mysql\UsuarioPerfilMySqlDAO($Transaccion);
            $UsuarioPerfilMySqlDAO->insert($UsuarioPerfil);

            //Inserta la informaci�n de punto de venta si aplica el perfil seleccionado
            if (stristr($perfil_id, 'PUNTO') or stristr($perfil_id, 'CONCESIONARIO')) {

                //El código crea una instancia de la clase PuntoVenta y asigna valores a sus propiedades utilizando variables proporcionadas.
                $PuntoVenta = new PuntoVenta();
                $PuntoVenta->descripcion = $descripcion;
                $PuntoVenta->nombreContacto = $nombre_contacto;
                $PuntoVenta->ciudadId = $ciudad_id;
                $PuntoVenta->direccion = $direccion;
                $PuntoVenta->barrio = $barrio;
                $PuntoVenta->telefono = $telefono;
                $PuntoVenta->email = $email;
                $PuntoVenta->periodicidadId = $periodicidad_id;
                $PuntoVenta->clasificador1Id = $clasificador1_id;
                $PuntoVenta->clasificador2Id = $clasificador2_id;
                $PuntoVenta->clasificador3Id = $clasificador3_id;
                $PuntoVenta->valorCupo = $valor_cupo;
                $PuntoVenta->valorCupo2 = $valor_cupo2;
                $PuntoVenta->porcenComision = $porcen_comision;
                $PuntoVenta->porcenComision2 = $porcen_comision2;
                $PuntoVenta->estado = $estado;
                $PuntoVenta->usuarioId = $numero;
                $PuntoVenta->mandante = $json->session->mandante;

                $PuntoVentaMySqlDAO = new \Backend\mysql\PuntoVentaMySqlDAO($Transaccion);
                $PuntoVentaMySqlDAO->insert($PuntoVenta);

            }

            //Verifica si es un punto de venta y fue seleccionado alg�n concesionario para proceder a guardarlo
            /*Valida y asigna IDs de concesionarios y subconcesionarios, luego inserta un nuevo registro en la base de datos.*/
            if ((stristr($perfil_id, 'PUNTO') or $perfil_id == "CONCESIONARIO2") and strlen($usupadre_id) > 0) {
                //Valida subconcesionario
                if (strlen($usupadre2_id) <= 0) {
                    $usupadre2_id = 0;
                }

                $Concesionario = new \Backend\dto\Concesionario();
                $Concesionario->usupadreId = $usupadre_id;
                $Concesionario->usupadre2Id = $usupadre2_id;
                $Concesionario->usuhijoId = $numero;
                $Concesionario->mandante = $json->session->mandante;

                $ConcesionarioMySqlDAO = new \Backend\mysql\ConcesionarioMySqlDAO($Transaccion);
                $ConcesionarioMySqlDAO->insert($Concesionario);

            }

            /*El código realiza una confirmación de transacción, cambia la clave del usuario si es necesario, y crea una respuesta JSON
             con un código de estado y un token de autenticación.*/
            $Transaccion->commit();

            $Usuario2 = new Usuario($numero);

            $UsuarioCambioClave = $Usuario2->changeClave($clave);

            $response = array();

            $response['code'] = 0;

            $data = array();

            $data["auth_token"] = "543456ASDASDA";
            $data["result"] = 0;

            $response['data'] = $data;

        } else {
            /*El código crea una respuesta JSON con un código de estado y un token de autenticación.*/
            $response = array();

            $response['code'] = -1;

            $data = array();

            $data["auth_token"] = "543456ASDASDA";
            $data["login"] = $login;
            $data["result"] = -1;

            $response['data'] = $data;
        }

    }
} else {
    /*El código crea una respuesta JSON con un código de estado, un token de autenticación, un mensaje y un resultado cuando no se cumplen ciertas condiciones.*/
    $response = array();

    $response['code'] = -1;

    $data = array();

    $data["auth_token"] = "543456ASDASDA";
    $data["message"] = "2";
    $data["result"] = -1;

    $response['data'] = $data;
}
