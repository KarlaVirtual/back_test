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
 * Elimina la exclusión de un producto de casino para un usuario especificado.
 * @param string $json->params[0]->id_exclusion_casino ID de exclusión de casino.
 *
 * @return array
 *  - code (int) Código de respuesta.
 *  - rid (string) Identificador de la solicitud.
 *  - data (array) Datos de respuesta.
 */

// Se obtiene el tipo de exclusión de juego del objeto JSON.
$id_exclusion_typeGame = $json->params[0]->id_exclusion_casino;

// Se crea una nueva instancia de UsuarioConfiguracion con el id de exclusión de juego.
$UsuarioConfiguracion = new UsuarioConfiguracion("", "", "", "", $id_exclusion_typeGame);

// Se establece el estado del usuario a "C".
$UsuarioConfiguracion->setEstado("C");

// Se crea una nueva instancia del DAO para la configuración del usuario en MySQL.
$UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();

// Se actualiza la configuración del usuario en la base de datos.
$UsuarioConfiguracionMySqlDAO->update($UsuarioConfiguracion);
$UsuarioConfiguracionMySqlDAO->getTransaction()->commit();

// Se prepara la respuesta para enviar al cliente.
$response = array();
$response["code"] = 0; // Código de respuesta.
$response["rid"] = $json->rid; // Identificador de la solicitud.
$response["data"] = array(); // Datos de respuesta.