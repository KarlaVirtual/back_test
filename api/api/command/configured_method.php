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
use Backend\dto\UsuarioInformacion;
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
use Backend\dto\UsuarioLog2;
use Backend\integrations\casino\IES;
use Backend\integrations\casino\JOINSERVICES;
use Backend\mysql\ConsecutivoMySqlDAO;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\DocumentoUsuarioMySqlDAO;
use Backend\mysql\PromocionalLogMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\mysql\RegistroMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\UsuarioInformacionMySqlDAO;
use Backend\mysql\UsuarioBancoMySqlDAO;
use Backend\mysql\UsuarioBannerMySqlDAO;
use Backend\mysql\UsuarioConfiguracionMySqlDAO;
use Backend\mysql\UsuarioLog2MySqlDAO;
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
use \Backend\integrations\payment\FRISERVICES;

/**
 * Propósito: Este archivo contiene la lógica para la configuración de un método de pago.
 *
 *@param int $json->params->id
 *@param string $json->params->friUsername
 *@param string $json->params->friPhoneNumber
 *@param int $json->params->site_id
 *@param bool $json->params->isMobile
 *
 * @return array $response
 *  - code : int Código de respuesta, 0 indica éxito o estado inicial.
 *  - rid : int Identificador de solicitud.
 */
$id = $json->params->id; // Se obtiene el ID del usuario
$friUsername = $json->params->friUsername; // Se obtiene el nombre de usuario del amigo
$friPhoneNumber = $json->params->friPhoneNumber; // Se obtiene el número de teléfono del amigo
$site_id = $json->params->site_id; // Se obtiene el ID del sitio
$isMobile = $json->params->isMobile; // Se determina si es un dispositivo móvil

$friPhoneNumber = strval($friPhoneNumber); // Se convierte el número de teléfono a cadena de texto

$UsuarioMandante = new UsuarioMandante($json->session->usuario); // Se crea una instancia de UsuarioMandante utilizando el usuario de la sesión
$Usuario = new Usuario ($UsuarioMandante->getUsuarioMandante()); // Se crea una instancia de Usuario utilizando el usuario mandante

/**
 * Propósito: la funcion FRISERVICES permite instanciar la clase y poder desde el recurso hacer llamados a diferentes funciones que usaremos como login con fri y busequeda o search
 *
 * Descripción de variables:
 *    - dataLogin2: es un array asociativo en el cual utilizamos unas credenciales dadas por fri que el sistema posteriormente validara para hacer el login en fri
 */


$fri = new FRISERVICES(); // Se instancia la clase FRISERVICES

$ConfigurationEnviroment = new ConfigurationEnvironment(); // Se crea una instancia de ConfigurationEnvironment
if($ConfigurationEnviroment->isDevelopment()){ // Se verifica si está en entorno de desarrollo
    $dataLogin2 = array(

        "username"=> "doradobet-sucursal1-prueba",
        "password"=> "XTKENSAOex"

);

}else{
    $dataLogin2 = array(

        "username"=> "doradobet-guatemala",
        "password"=> "FLMfdgilVy",

    );
}


/**
 * Propósito: la funcion loginfri recibe el array asociativo del que se hablo en la parte de arriba y que contiene las credenciales dadas por fri como administrador
 *    - $ResultLogin contiene todos los datos relacionados a la sesion en fri
 *    - $sessionId: contiene el id o lo que seria el token en fri luego del inicio de sesion
 */


$ResultLogin = $fri->loginfri($dataLogin2);

$ResultLogin = json_decode($ResultLogin);

$sessionId =  $ResultLogin->responseContent->sessionId;


/**
 * Propósito: se realiza la validacion de si el usuario lleno el campo de usuario para saber si buscar por usuario o por telefono
 *    - $dataSearch es un array indexado en el cual lo que hacemos es pasarle el campo de usuario que el usuario lleno
 *    - $ResultSearch: esta variable permite instaciar searchfri que es una funcion para mirar si un usuario existe en fri solo pasando dataSearch y el id de la session que previamente fri nos habia dado
 *    - $ResultSearch esta variable permite guardar los datos obtenidos por medio de la funcion searchfri
 */

try {
    if($friUsername!=="") {
        // Inicializa el arreglo que almacenará la información de búsqueda
        $dataSearch['info'] = array();

        // Contenido de la solicitud para buscar información del usuario en FRI
        $dataSearch['requestContent'] = array(

            "username" => $friUsername
        );

        // Realiza la búsqueda del amigo usando el método searchfri
        $ResultSearch = $fri->searchfri($dataSearch, $sessionId);

        // Decodifica la respuesta JSON obtenida de la búsqueda
        $ResultSearch = json_decode($ResultSearch); // 99557788 55555555.test2

        /**
         * Propósito: Capturar el nombre de un usuario para posteriormente mirar si en la base de datos de VirtualSoft existe
         *  $NombreUsuarioFri: capturar el nombre de el usuario de fri
         *  $TelefonoUsuarioFri: capturar el numero de telefono que obtenemos desde Fri para luego verificar si se encuentra en nuestra BD
         *
         */

        $NombreUsuarioFri = $ResultSearch->responseContent->user->username;
        $TelefonoUsuarioFri = $ResultSearch->responseContent->user->phoneNumber;

        /**
         * Propósito: se realiza el siguiente condicional para en caso de que el usuario de fri no exista lanzar un codigo de error al front end
         */

        if ($ResultSearch->info->type == "error") {
            throw new Exception("Usuario no encontrado en FRI", "100111");
        }

    }

    if($friUsername == ""){
        throw new Exception("Usuario no encontrado en FRI", "100111");
    }


    if($friPhoneNumber != ""){
        $dataSearch['info'] = array();
        $dataSearch['requestContent'] = array(

            "phoneNumber"=>  $friPhoneNumber
        );



        $ResultSearch = $fri->searchfri($dataSearch,$sessionId);

        $ResultSearch = json_decode($ResultSearch); // 99557788

        /*  $NombreUsuarioFri: capturar el nombre de el usuario de fri
    *  $TelefonoUsuarioFri: capturar el numero de telefono que obtenemos desde Fri para luego verificar si se encuentra en nuestra BD */
        // Captura el nombre de usuario de FRI
        $NombreUsuarioFri = $ResultSearch->responseContent->user->username;
        // Captura el número de teléfono del usuario de FRI
        $TelefonoUsuarioFri = $ResultSearch->responseContent->user->phoneNumber;


        /**
         * Propósito: se realiza el siguiente condicional para en caso de que el usuario de fri no exista lanzar un codigo de error al front end
         */

        if($ResultSearch->info->type == "error"){

            throw new Exception("Usuario no encontrado en FRI", "100111");

        }
    }


    /**
     * Propósito: se realiza el siguiente condicional para en caso de que el usuario de fri no exista lanzar un codigo de error al front end
     */
}catch (Exception $e){
    if($e->getCode() == 100111){

        /**
         * Propósito: se realiza el siguiente condicional para verificar si el usuario lleno el campo de numero de telefono y en caso de que asi haya sido meter esto en un array asociativo llamado $dataSearch.
         *
         * $ResultSearch se instancia la funcion searchfri con la cual basta con pasar el numero de telefono para obtener los datos de el usuario
         *
         * $ResultSearch = json_decode($ResultSearch); // 99557788
         * $ResultSearch se decodifica los datos obtenidos por parte de fri
         *
         *
         */


        if($friPhoneNumber != ""){
            $dataSearch['info'] = array();
            $dataSearch['requestContent'] = array(

                "phoneNumber"=>  $friPhoneNumber
            );



            $ResultSearch = $fri->searchfri($dataSearch,$sessionId);

            $ResultSearch = json_decode($ResultSearch); // 99557788

            /*  $NombreUsuarioFri: capturar el nombre de el usuario de fri
        *  $TelefonoUsuarioFri: capturar el numero de telefono que obtenemos desde Fri para luego verificar si se encuentra en nuestra BD */

            $NombreUsuarioFri = $ResultSearch->responseContent->user->username;
            $TelefonoUsuarioFri = $ResultSearch->responseContent->user->phoneNumber;


            /**
             * Propósito: se realiza el siguiente condicional para en caso de que el usuario de fri no exista lanzar un codigo de error al front end
             */

            if($ResultSearch->info->type == "error"){

                throw new Exception("Usuario no encontrado en FRI", "100111");

            }
        }else{
            throw $e;
        }

        if($friUsername!=="") {
            $dataSearch['info'] = array();
            $dataSearch['requestContent'] = array(

                "username" => $friUsername
            );


            $ResultSearch = $fri->searchfri($dataSearch, $sessionId);


            $ResultSearch = json_decode($ResultSearch); // 99557788 55555555.test2

            /**
             * Propósito: Capturar el nombre de un usuario para posteriormente mirar si en la base de datos de VirtualSoft existe
             *  $NombreUsuarioFri: capturar el nombre de el usuario de fri
             *  $TelefonoUsuarioFri: capturar el numero de telefono que obtenemos desde Fri para luego verificar si se encuentra en nuestra BD
             *
             */

            $NombreUsuarioFri = $ResultSearch->responseContent->user->username;
            $TelefonoUsuarioFri = $ResultSearch->responseContent->user->phoneNumber;

            /**
             * Propósito: se realiza el siguiente condicional para en caso de que el usuario de fri no exista lanzar un codigo de error al front end
             */

            if ($ResultSearch->info->type == "error") {
                throw new Exception("Usuario no encontrado en FRI", "100111");
            }

        }

    }
}




// validacion previa para mirar que el usuario no se encuentre registrado de nuestro lado en usuario_informacion



/**
 * Propósito: se realiza la instancia a la clase clasificador para asi en usuario_informacion poder identificar facil de que se trata el tipo de registro
 *
 * $Clasificador  Se instancia clasificador y se le pasa el abreviado FRIUSERNAME
 * $ClasificadorId: se obtiene el id del clasificador que es para usuarios de fri
 * $ConfirmarRegistro: iniciamos esta variable en true que indica que el usuario se debe registrar en Virtual la iniciamos en true pero mas adelante puede ir cambiando de valor
 *
 */


$Clasificador = new Clasificador('', 'FRIUSERNAME');
$ClasificadorId = $Clasificador->getClasificadorId();

try {

    $confirmarRegistro = true;

    /**
     * Propósito: $verificacionUsuario: esta variable permite obtener los datos que haya guardados en usuario_informacion y poder verificar si el usuario esta registrado en nuestra BD.
     *
     * se realiza condicional utilizando el isset que permite saber si la variable verificacionUsuario contiene algun dato en caso que si se setea confirmar registro en falso ya que como el usuario esta guardado no es necesario guardarlo de nuevo
     *
     */


    $verificacionUsuario = new UsuarioInformacion();
    $verificacionUsuario->verificacionUsuarioExistente($ClasificadorId,$NombreUsuarioFri,$Usuario->mandante);

    if ($verificacionUsuario) {
        throw new Exception("Usuario ya está verificado del lado de virtualsoft", "300028");
        $confirmarRegistro = false;
    } else {
        $confirmarRegistro = true;
    }


    /*El código verifica si un número de teléfono de un usuario ya está registrado en la base de datos de VirtualSoft. Si el número está registrado,
    lanza una excepción; de lo contrario, permite continuar con el registro del usuario.*/
    $Clasificador = new Clasificador('', 'FRIPHONE');
    $ClasificadorId = $Clasificador->getClasificadorId();

    $verificacionTelefono = new UsuarioInformacion;
    $verificacionTelefono = $verificacionTelefono->verificacionUsuarioExistente($ClasificadorId, $friPhoneNumber, $Usuario->mandante);


    if ($verificacionTelefono) {
        throw new Exception("Usuario ya está verificado del lado de virtualsoft", "300028");
        $confirmarRegistro = false;
    } else {
        $confirmarRegistro = true;
    }



}catch (Exception $e){
    if($e->getCode() == "300028") {


            $Clasificador = new Clasificador('', 'FRIUSERNAME');
            $ClasificadorId = $Clasificador->getClasificadorId();

            $verificacionUsuario = new UsuarioInformacion();
            $usuarioExistente = $verificacionUsuario->verificacionUsuarioExistente($ClasificadorId, $NombreUsuarioFri, $Usuario->mandante);

            if ($usuarioExistente) {
                throw new Exception("Usuario ya está verificado del lado de virtualsoft", "300028");
                $confirmarRegistro = false;
            } else {
                $confirmarRegistro = true;
            }



        $confirmarRegistro = true;

        $Clasificador = new Clasificador('', 'FRIPHONE');
        $ClasificadorId = $Clasificador->getClasificadorId();

        $verificacionTelefono = new UsuarioInformacion;
        $verificacionTelefono = $verificacionTelefono->verificacionUsuarioExistente($ClasificadorId, $friPhoneNumber, $Usuario->mandante);


        if ($verificacionTelefono and $verificacionTelefono != NULL) {
            throw new Exception("Usuario ya está verificado del lado de virtualsoft", "300028");
            $confirmarRegistro = false;
        } else {
            $confirmarRegistro = true;
        }

    }

}

if($friUsername !== "" && $friUsername !== null and $confirmarRegistro == true) {
    // Verifica si el nombre de usuario es diferente de vacío y no es nulo,
    // y si se debe confirmar el registro.

    $Clasificador = new Clasificador('', 'FRIUSERNAME');
    // Crea una instancia de Clasificador con el tipo 'FRIUSERNAME' para obtener el ID del clasificador.
    $ClasificadorId = $Clasificador->getClasificadorId();
    // Obtiene el ID del clasificador.

    $UsuarioInformacion = new UsuarioInformacion();
    // Crea una nueva instancia de UsuarioInformacion.
    $UsuarioInformacion->setClasificadorId($ClasificadorId);
    // Establece el ID del clasificador en la información del usuario.
    $UsuarioInformacion->setUsuarioId($Usuario->usuarioId);
    // Establece el ID del usuario.
    $UsuarioInformacion->setValor($friUsername);
    // Establece el valor del usuario con el nombre proporcionado.
    $UsuarioInformacion->setusucreaId($Usuario->usuarioId);
    // Establece el ID del creador del usuario.
    $UsuarioInformacion->setUsuModif($_SESSION['usuario']);
    // Establece el ID del usuario que modifica la información.
    $UsuarioInformacion->setMandante($Usuario->mandante);
    // Establece el mandante del usuario.

    $UsuarioInformacionMySqlDAO = new UsuarioInformacionMySqlDAO();
    // Crea una instancia del Data Access Object para UsuarioInformacion.
    $UsuarioInformacionMySqlDAO->insert($UsuarioInformacion);
    // Inserta la información del usuario en la base de datos.
    $UsuarioInformacionMySqlDAO->getTransaction()->commit();
    // Realiza el commit de la transacción.
}


if($friPhoneNumber !== "" && $friPhoneNumber !== null and $confirmarRegistro == true) {
    // Verifica si el número de teléfono es diferente de vacío y no es nulo,
    // y si se debe confirmar el registro.

    $Clasificador = new Clasificador('', 'FRIPHONE');
    // Crea una instancia de Clasificador con el tipo 'FRIPHONE' para obtener el ID del clasificador.
    $ClasificadorId = $Clasificador->getClasificadorId();
    // Obtiene el ID del clasificador.

    // Crea una nueva instancia de UsuarioInformacion.
        $UsuarioInformacion = new UsuarioInformacion();
        $UsuarioInformacion->setClasificadorId($ClasificadorId);// Establece el ID del clasificador en la información del usuario.
        $UsuarioInformacion->setUsuarioId($Usuario->usuarioId);// Establece el ID del usuario.
        $UsuarioInformacion->setValor($friPhoneNumber); // Establece el valor del usuario con el número de teléfono proporcionado.
        $UsuarioInformacion->setusucreaId($Usuario->usuarioId); // Establece el ID del creador del usuario.
        $UsuarioInformacion->setUsuModif($_SESSION['usuario']); // Establece el ID del usuario que modifica la información.
        $UsuarioInformacion->setMandante($Usuario->mandante); // Establece el mandante del usuario.

        //Almacena el registro
        $UsuarioInformacionMySqlDAO = new UsuarioInformacionMySqlDAO();
        $UsuarioInformacionMySqlDAO->insert($UsuarioInformacion);
        $UsuarioInformacionMySqlDAO->getTransaction()->commit();

    }

/**
 * Inicializa un arreglo de respuesta.
 *
 * Este arreglo incluye un código de respuesta y un identificador de solicitud (rid) extraído de un objeto JSON.
 */

$response = array();
$response["code"] = 0; // Código de respuesta, 0 indica éxito o estado inicial.
$response["rid"] = $json->rid; // Recupera el identificador de solicitud (rid) del objeto JSON.




