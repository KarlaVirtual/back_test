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
 * Obtiene la colección de países y monedas asociadas a un país específico.
 * @param int $json->params->pais_id ID del país.
 *
 * @return array
 *  - code: int Código de respuesta.
 *  - data: array Información de departamentos y monedas.
 *  - data.provincias: array Colección de departamentos.
 *  - data.moneda: array Colección de monedas.
 */

// Se obtiene el ID del país desde el objeto JSON recibido.
$pais_id = $json->params->pais_id;

// Se crea una instancia de DepartamentoMySqlDAO para consultas de departamentos.
$DepartamentoMySqlDAO = new Backend\mysql\DepartamentoMySqlDAO();

// Se consultan los departamentos asociados al ID de país.
$Departamentos = $DepartamentoMySqlDAO->queryByPaisId($pais_id);

// Se crea una instancia de PaisMonedaMySqlDAO para consultas de monedas.
$PaisMonedaMySqlDAO = new Backend\mysql\PaisMonedaMySqlDAO();

// Se consultan las monedas asociadas al ID de país.
$PaisMonedas = $PaisMonedaMySqlDAO->queryByPaisId($pais_id);

// Se inicializan arreglos para almacenar departamentos y monedas.
$array_departamentos = [];
$array_monedas = [];

// Se itera sobre los departamentos obtenidos para formatear los datos.
foreach ($Departamentos as $departamento) {
    $array_departamento = array(
        "key" => $departamento->deptoId,
        "name" => $departamento->deptoNom,
    );

    // Se agrega el departamento formateado al arreglo de departamentos.
    array_push($array_departamentos, $array_departamento);

}

// Se crea una instancia de MonedaMySqlDAO para cargar detalles de la moneda.
$MonedaMySqlDAO = new \Backend\mysql\MonedaMySqlDAO();

foreach ($PaisMonedas as $paismoneda) {
    // Se carga la información de la moneda utilizando su identificador.
    $Moneda = $MonedaMySqlDAO->load($paismoneda->moneda);
    $array_moneda = array(
        "key" => $Moneda->moneda,
        "name" => $Moneda->descripcion,
    );

    // Se agrega la moneda formateada al arreglo de monedas.
    array_push($array_monedas, $array_moneda);

}

// Se prepara la respuesta que incluye el código de éxito y los datos obtenidos.
$response = array("code" => 0, "data" => array(
    "provincias" => $array_departamentos,
    "monedas" => $array_monedas,
));

