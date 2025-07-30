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
 *Elimina una exclusión para un tipo de juego específico
 *
 * @param int $json->params[0]->id_exclusion_typeGame ID del tipo de exclusión de juego
 * @param string $json->session->usuario Usuario de la sesión
 *
 * @return array
 *  - code (int) Código de respuesta
 *  - rid (int) ID de la solicitud
 *  - data (array) Datos de respuesta
 */

// Obtener el tipo de exclusión del juego desde el objeto JSON
$id_exclusion_typeGame = $json->params[0]->id_exclusion_typeGame;

// Crear una nueva instancia de UsuarioConfiguracion con el tipo de exclusión
$UsuarioConfiguracion = new UsuarioConfiguracion("", "", "", "", $id_exclusion_typeGame);
$UserID = $UsuarioConfiguracion->getUsuarioId();
$IsAutoExclusion = false;
$Valor = $UsuarioConfiguracion->getValor(); // Obtener el valor
if (strtotime($Valor) !== false) {
    $isAutoExclusion = true;
}

// Comparar la fecha de creación con la hora actual y verificar condiciones de autoexclusión
if(date('Y-m-d H:i:s', strtotime($UsuarioConfiguracion->getFechaCrea() . '+24 hours')) > date('Y-m-d H:i:s') || ($IsAutoExclusion && $UsuarioConfiguracion->getValor() < date('Y-m-d H:i:s'))) {
    throw new Exception('Error general', 300009); // Lanzar excepción si la condición se cumple
}

// Crear una nueva instancia de UsuarioMandante con el usuario de la sesión
$UsuarioMandante = new UsuarioMandante($json->session->usuario);

if ($UserID == $UsuarioMandante->getUsuarioMandante()){
    $UsuarioConfiguracion->setEstado("C"); // Cambiar el estado de la configuración a "C"
    $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO(); // Crear DAO para interacción con MySQL

    // Actualizar la configuración en la base de datos
    $UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);
    $UsuarioConfiguracionMySqlDAO->getTransaction()->commit();
}

// Preparar la respuesta en formato de array
$response = array();
$response["code"] = 0; // Código de respuesta
$response["rid"] = $json->rid; // ID de la solicitud
$response["data"] = array(); // Datos de respuesta