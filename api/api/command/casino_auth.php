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

if ($json->session->logueado) {
    /** Procesa las validaciones requeridas para la autenticación de casino
     * @return array
     *  - code: Código de respuesta
     *  - data: Datos de respuesta
     *    - result: Resultado de la autenticación
     *    - has_error: Indica si hubo un error
     *  - error_id: ID del error
     *  - id: ID del usuario
     *  - external_id: ID externo del usuario
     *  - username: Nombre de usuario
     *  - name: Nombre completo del usuario
     *  - gender: Género del usuario
     *  - balance: Saldo del usuario
     *  - virtual_amount: Monto virtual del usuario
     *  - coin: Moneda del usuario
     *  - currency: Moneda utilizada
     *  - partner_id: ID del socio
     *  - email: Correo electrónico del usuario
     *  - locked: Indica si la cuenta está bloqueada
     *  - token: Token de sesión
     */

    /*El código inicializa objetos UsuarioMandante y UsuarioToken utilizando datos de la sesión, y prepara una estructura de respuesta con un código
    de éxito y arreglos vacíos para los datos y resultados.*/
    $UsuarioMandante = new UsuarioMandante($json->session->usuario);
    $UsuarioToken = new UsuarioToken("", '0', $json->session->usuario);

    $response = array();

    $response['code'] = 0;

    $data = array();
    $result = array();

    /*El código seleccionado inicializa un arreglo $result con información del usuario autenticado, incluyendo su ID, nombre, saldo, moneda, y token de sesión.*/
    $result["has_error"] = "False";
    $result["error_id"] = "0";
    $result["id"] = $UsuarioMandante->getUsumandanteId();
    $result["external_id"] = "";
    $result["username"] = $UsuarioMandante->getUsumandanteId();
    $result["name"] = $UsuarioMandante->getNombres() . " " . $UsuarioMandante->getApellidos();
    $result["gender"] = "False";
    $result["balance"] = $UsuarioMandante->getSaldo();
    $result["virtual_amount"] = "0.0";
    $result["coin"] = "0.0";
    $result["currency"] = $UsuarioMandante->getMoneda();
    $result["partner_id"] = $json->session->mandante;
    $result["email"] = "";
    $result["locked"] = "False";
    $result["token"] = $UsuarioToken->getToken();

    $data['result'] = $result;

    $response['data'] = $data;

}

if($json->session->usuario  == 'FUN') {

    // Inicialización de un array para almacenar la respuesta
    $response = array();

    // Código de respuesta
    $response['code'] = 0;

    // Inicialización de un array para almacenar los datos
    $data = array();
    $result = array();

    // Información del resultado
    $result["has_error"] = "False"; // Indica si hubo un error
    $result["error_id"] = "0"; // ID del error, 0 significa sin errores
    $result["id"] = 0; // ID del usuario
    $result["external_id"] = ""; // ID externo del usuario
    $result["username"] = 0; // Nombre de usuario
    $result["name"] = 'TEST' . " " . 'TEST'; // Nombre completo del usuario
    $result["gender"] = "False"; // Género del usuario
    $result["balance"] = 0; // Saldo del usuario
    $result["virtual_amount"] = "0.0"; // Monto virtual del usuario
    $result["coin"] = "0.0"; // Moneda del usuario
    $result["currency"] = 'USD'; // Moneda utilizada
    $result["partner_id"] = 0; // ID del socio
    $result["email"] = ""; // Correo electrónico del usuario
    $result["locked"] = "False"; // Indica si la cuenta está bloqueada
    $result["token"] = 'FUN'; // Token de sesión

    // Asignación del resultado a los datos
    $data['result'] = $result;

    // Asignación de los datos a la respuesta
    $response['data'] = $data;
}
