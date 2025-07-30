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

/** Obtiene la colección de ciudades vinculadas a una provincia
 * @param int $depto_id ID de la provincia
 *
 *@return array
 * - code: int Código de respuesta.
 * - data: array Información de las ciudades.
 * - data.ciudades: array Lista de ciudades.
 */

// Obtiene el ID de la provincia desde el objeto JSON.
$depto_id = $json->params->provincia_id;

// Crea una instancia del DAO para realizar consultas sobre las ciudades.
$CiudadMySqlDAO = new Backend\mysql\CiudadMySqlDAO();

// Consulta las ciudades asociadas al ID de la provincia.
$Ciudades = $CiudadMySqlDAO->queryByDeptoId($depto_id);

// Inicializa un array para almacenar las ciudades en formato específico.
$array_ciudades = [];

// Recorre el conjunto de ciudades y construye un nuevo array con el código y nombre de cada ciudad.
foreach ($Ciudades as $ciudad) {
    $array = array(
        "key" => $ciudad->ciudadCod, // Código de la ciudad.
        "name" => $ciudad->ciudadNom, // Nombre de la ciudad.
    );

    // Agrega la información de la ciudad al array de ciudades.
    array_push($array_ciudades, $array);
}

// Prepara la respuesta con el código de éxito y los datos de las ciudades.
$response = array("code" => 0, "data" => array(
    "ciudades" => $array_ciudades, // Lista de ciudades.
));


