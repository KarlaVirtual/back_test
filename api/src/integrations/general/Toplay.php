<?php

/**
 * Esta clase contiene métodos para gestionar juegos.
 *
 * @category Red
 * @package  API
 * @author   Desconocido
 * @version  1.0.0
 * @since    2025-04-17
 */

namespace Backend\integrations\general;

use Backend\cms\CMSCategoria;
use Backend\cms\CMSProveedor;
use Backend\dto\Mandante;
use Backend\dto\PromocionalLog;
use Backend\dto\PuntoVenta;
use Backend\dto\Registro;
use Backend\dto\TransaccionApiUsuario;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransaccionProducto;
use Backend\dto\TransapiusuarioLog;
use Backend\dto\TransjuegoLog;
use Backend\dto\TransaccionApi;
use Backend\dto\TransprodLog;
use Backend\dto\Usuario;
use Backend\dto\UsuarioHistorial;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioPremiomax;
use Backend\dto\UsuarioToken;
use Backend\dto\Producto;
use Backend\dto\ProductoMandante;
use Backend\dto\Proveedor;
use Backend\mysql\TransaccionApiUsuarioMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransaccionProductoMySqlDAO;
use Backend\mysql\TransaccionApiMySqlDAO;
use Backend\mysql\TransapiusuarioLogMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;
use Backend\websocket\WebsocketUsuario;

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

use Backend\dto\MandanteDetalle;
use Backend\dto\PaisMoneda;

use Backend\dto\UsuarioBanco;
use Backend\dto\UsuarioConfig;
use Backend\dto\UsuarioConfiguracion;
use Backend\dto\UsuarioLog;
use Backend\dto\UsuarioOtrainfo;
use Backend\dto\UsuarioPerfil;
use Backend\dto\UsuarioPublicidad;
use Backend\dto\UsuarioRecarga;
use Backend\dto\UsuarioMensaje;
use Backend\dto\ProdMandanteTipo;
use Backend\dto\Pais;
use Backend\integrations\casino\IES;
use Backend\integrations\casino\JOINSERVICES;
use Backend\mysql\ConsecutivoMySqlDAO;
use Backend\mysql\CuentaCobroMySqlDAO;
use Backend\mysql\DocumentoUsuarioMySqlDAO;
use Backend\mysql\PromocionalLogMySqlDAO;
use Backend\mysql\PuntoVentaMySqlDAO;
use Backend\mysql\RegistroMySqlDAO;
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

use DateTime;
use Exception;

/**
 * Clase Toplay
 *
 * Esta clase contiene métodos para gestionar juegos, transacciones,
 * historial de apuestas, balance y otras operaciones relacionadas
 * con la integración de un sistema de juegos.
 */
class Toplay
{

    /**
     * Identificador del operador asociado.
     *
     * @var mixed
     */
    private $operadorId;

    /**
     * Token utilizado para la autenticación.
     *
     * @var mixed
     */
    private $token;

    /**
     * Identificador único del usuario.
     *
     * @var mixed
     */
    private $uid;

    /**
     * Firma utilizada para validar las solicitudes.
     *
     * @var mixed
     */
    private $sign;

    /**
     * Objeto que representa la transacción API actual.
     *
     * @var TransaccionApi
     */
    private $transaccionApi;

    /**
     * Datos asociados a la transacción o solicitud.
     *
     * @var mixed
     */
    private $data;

    /**
     * Identificador del round en el sistema.
     *
     * @var mixed
     */
    private $roundIdSuper;

    /**
     * Nombre de usuario asociado a la operación.
     *
     * @var string
     */
    private $UserName;

    /**
     * Identificador del establecimiento asociado.
     *
     * @var string
     */
    private $EstablishmentId;

    /**
     * Identificador del socio asociado. Por defecto es '0'.
     *
     * @var string
     */
    private $Partner = '0';

    /**
     * Constructor de la clase Toplay.
     *
     * Inicializa los valores necesarios para la operación de la clase.
     *
     * @param string $token           Token utilizado para la autenticación.
     * @param string $sign            Firma utilizada para validar las solicitudes.
     * @param string $UserName        Nombre de usuario asociado a la operación (opcional).
     * @param string $EstablishmentId Identificador del establecimiento asociado (opcional).
     */
    public function __construct($token, $sign, $UserName = '', $EstablishmentId = '')
    {
        $this->token = $token;
        $this->sign = $sign;

        $this->UserName = $UserName;
        $this->UserName = str_replace("Usuario", "", $this->UserName);
        $this->EstablishmentId = $EstablishmentId;
    }

    /**
     * Obtiene el identificador del operador asociado.
     *
     * @return mixed El identificador del operador.
     */
    public function getOperadorId()
    {
        return $this->operadorId;
    }

    /**
     * Autentica al usuario y obtiene información relevante.
     *
     * Este metodo realiza la autenticación del usuario utilizando un token y
     * devuelve información como el balance, nombre, apellidos, tipo de documento,
     * entre otros datos asociados al usuario autenticado.
     *
     * @return string JSON con los datos del usuario autenticado, incluyendo:
     *                - userName: Nombre de usuario.
     *                - isActive: Estado activo del usuario.
     *                - documentType: Tipo de documento.
     *                - firstName: Primer nombre.
     *                - secondName: Segundo nombre.
     *                - firstSurname: Primer apellido.
     *                - secondSurname: Segundo apellido.
     *                - balance: Balance del usuario.
     *                - email: Correo electrónico del usuario.
     * @throws Exception Si ocurre algún error durante la autenticación.
     * @throws Exception Si el token está vacío (código 10011).
     */
    public function Auth()
    {
        try {
            $Proveedor = new Proveedor("", "TOPLAY");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("AUTH");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);


            if ($this->token == "") {
                throw new Exception("Token vacio", "10011");
            }

            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $Mandante = new Mandante($UsuarioMandante->mandante);

            if ($Mandante->propio == "S") {
                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

                $Balance = ($Usuario->getBalance());

                $token_explode = explode("VSSV", $this->token);

                $Registro = new Registro("", $Usuario->usuarioId);

                $tipo = 'CC';

                if ($Registro->tipoDoc == 'E') {
                    $tipo = 'CE';
                }

                $return = array(

                    "userName" => "Usuario" . $UsuarioMandante->usumandanteId,
                    "isActive" => true,
                    "documentType" => $tipo,
                    "firstName" => $Registro->nombre1,
                    "secondName" => $Registro->nombre2,
                    "firstSurname" => $Registro->apellido1,
                    "secondSurname" => $Registro->apellido2,
                    "balance" => $Balance,
                    "email" => $Usuario->login
                );

                return json_encode($return);
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Autentica a un usuario utilizando su documento y tipo de documento.
     *
     * Este metodo busca un usuario en el sistema basado en su documento y tipo de documento,
     * y devuelve información relevante como su balance, nombre, apellidos, tipo de documento,
     * entre otros datos asociados al usuario autenticado.
     *
     * @param string $document Documento del usuario.
     * @param string $type     Tipo de documento (por ejemplo, "CE" para cédula de extranjería).
     *
     * @return string JSON con los datos del usuario autenticado, incluyendo:
     *                - userName: Nombre de usuario.
     *                - isActive: Estado activo del usuario.
     *                - documentType: Tipo de documento.
     *                - firstName: Primer nombre.
     *                - secondName: Segundo nombre.
     *                - firstSurname: Primer apellido.
     *                - secondSurname: Segundo apellido.
     *                - balance: Balance del usuario.
     *                - email: Correo electrónico del usuario.
     * @throws Exception Si no se encuentra el usuario (código 100010).
     */
    public function AuthByDocument($document, $type)
    {
        try {
            $Proveedor = new Proveedor("", "TOPLAY");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("AUTH");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);

            $tipodoc = 'C';

            switch ($type) {
                case "CE":
                    $tipodoc = 'E';
                    break;
            }

            $Usuario = new Usuario();

            $rules = array();
            array_push($rules, array("field" => "registro.cedula", "data" => $document, "op" => 'eq'));
            array_push($rules, array("field" => "registro.tipo_doc", "data" => $tipodoc, "op" => 'eq'));
            array_push($rules, array("field" => "usuario.mandante", "data" => $this->Partner, "op" => 'eq'));


            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);


            $usuarios = $Usuario->getUsuariosCustom(
                " usuario.usuario_id ",
                "usuario.usuario_id",
                "asc",
                0,
                1,
                $json,
                true
            );
            $usuarios = json_decode($usuarios);

            if (intval($usuarios->count[0]->{".count"}) > 0) {
                $Usuario = new Usuario($usuarios->data[0]->{"usuario.usuario_id"});


                $UsuarioMandante = new UsuarioMandante('', $Usuario->usuarioId, $Usuario->mandante);

                $Balance = round(floatval(intval($Usuario->getBalance() * 100)) / 100, 2);

                $Registro = new Registro("", $Usuario->usuarioId);

                $tipo = 'CC';

                if ($Registro->tipoDoc == 'E') {
                    $tipo = 'CE';
                }

                $return = array(

                    "userName" => "Usuario" . $UsuarioMandante->usumandanteId,
                    "isActive" => true,
                    "documentType" => $tipo,
                    "firstName" => $Registro->nombre1,
                    "secondName" => $Registro->nombre2,
                    "firstSurname" => $Registro->apellido1,
                    "secondSurname" => $Registro->apellido2,
                    "balance" => $Balance,
                    "email" => $Usuario->login
                );
            } else {
                throw new Exception("No se encontro el usuario", "100010");
            }


            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Vincula una cuenta de usuario con el sistema.
     *
     * Este metodo realiza la vinculación de una cuenta de usuario, generando un código de verificación
     * y enviándolo al correo electrónico del usuario. También registra un log de la operación.
     *
     * @return string JSON con el resultado de la operación, incluyendo:
     *                - IsSuccess: Indica si la operación fue exitosa.
     * @throws Exception Si el nombre de usuario está vacío (código 10011).
     */
    public function LINKACCOUNT()
    {
        try {
            $ConfigurationEnvironment = new ConfigurationEnvironment();

            $Proveedor = new Proveedor("", "TOPLAY");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("AUTH");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);


            if ($this->UserName == "") {
                throw new Exception("Token vacio", "10011");
            }

            $UsuarioMandante = new UsuarioMandante($this->UserName);

            $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

            $Registro = new Registro('', $Usuario->usuarioId);

            $Mandante = new Mandante($UsuarioMandante->mandante);


            $UsuarioLog = new UsuarioLog();
            $UsuarioLog->setUsuarioId($Usuario->usuarioId);
            $UsuarioLog->setUsuarioIp('');

            $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
            $UsuarioLog->setUsuariosolicitaIp('');

            $UsuarioLog->setTipo("VERIFYACCOUNTTOPLAY");


            $UsuarioLog->setEstado("P");
            $UsuarioLog->setValorAntes('');
            $UsuarioLog->setValorDespues('');
            $UsuarioLog->setUsucreaId(0);
            $UsuarioLog->setUsumodifId(0);


            $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
            $usuariologId = $UsuarioLogMySqlDAO->insert($UsuarioLog);
            $UsuarioLogMySqlDAO->getTransaction()->commit();

            $codigo = $ConfigurationEnvironment->GenerarClaveTicket2(6);
            $code = ($ConfigurationEnvironment->encryptWithoutRandom($Usuario->usuarioId . "_" . $codigo));


            $UsuarioLog->setValorDespues($code);

            $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
            $usuariologId = $UsuarioLogMySqlDAO->update($UsuarioLog);
            $UsuarioLogMySqlDAO->getTransaction()->commit();


            $email = $Usuario->login;

            $Mandante = new Mandante($Usuario->mandante);
            switch (strtolower($Usuario->idioma)) {
                case "pt":
                    //Arma el mensaje para el usuario que se registra
                    $mensaje_txt = "Olá, digite este código para validar seu cuenta:";

                    $msubjetc = "";
                    $msubjetc = "";
                    break;

                case "en":
                    //Arma el mensaje para el usuario que se registra
                    $mensaje_txt = "Hello, enter this code to validate your qccount:";

                    $msubjetc = "";
                    $msubjetc = "";
                    break;

                default:

                    //Arma el mensaje para el usuario que se registra

                    $mensaje_txt = "DoradoBet | xxxxxx es el código para verificar tu cuenta. Para cualquier consulta ingresa al chat.";

                    $msubjetc = "Verificación de cuenta";
                    $mtitle = "Verificación de cuenta";
                    break;
            }

            $mensaje_txt = str_replace("xxxxxx", $codigo, $mensaje_txt);
            //Destinatarios
            $destinatarios = $email;

            //Envia el mensaje de correo
            //$envio = $ConfigurationEnvironment->EnviarMensajeTexto($mensaje_txt, '', $Registro->getCelular(), 0,$UsuarioMandante);
            $envio = $ConfigurationEnvironment->EnviarCorreoVersion2(
                $destinatarios,
                'noreply@doradobet.com',
                'Doradobet',
                $msubjetc,
                'mail_registro.php',
                $mtitle,
                $mensaje_txt,
                $dominio,
                $compania,
                $color_email,
                $Mandante->mandante
            );


            $return = array(
                "IsSuccess" => true
            );


            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Verifica la cuenta de un usuario utilizando un código de verificación.
     *
     * Este método valida un código de verificación asociado a un usuario, actualiza el estado
     * del registro correspondiente y genera un token de usuario si la verificación es exitosa.
     *
     * @param string $VerificationCode Código de verificación proporcionado por el usuario.
     * @param string $DeviceId         Identificador del dispositivo del usuario.
     * @param string $CellPhone        Número de teléfono del usuario.
     *
     * @return string JSON con el resultado de la operación, incluyendo:
     *                - IsSuccess: Indica si la operación fue exitosa.
     * @throws Exception Si el recurso ha expirado o no se encuentra (código 100011).
     * @throws Exception Si ocurre un error al generar el token de usuario.
     *
     * @throws Exception Si el nombre de usuario está vacío (código 10011).
     */
    public function VERIFYACCOUNT($VerificationCode, $DeviceId, $CellPhone)
    {
        $ConfigurationEnvironment = new ConfigurationEnvironment();

        try {
            $Proveedor = new Proveedor("", "TOPLAY");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("AUTH");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);


            if ($this->UserName == "") {
                throw new Exception("Token vacio", "10011");
            }

            $UsuarioMandante = new UsuarioMandante($this->UserName);

            $Usuario = new Usuario($UsuarioMandante->getUsuarioMandante());

            $Registro = new Registro('', $Usuario->usuarioId);

            $Mandante = new Mandante($UsuarioMandante->mandante);

            $rules = [];
            array_push($rules, array("field" => "usuario.usuario_id", "data" => "$Usuario->usuarioId", "op" => "eq"));
            array_push($rules, array("field" => "usuario_log.tipo", "data" => "VERIFYACCOUNTTOPLAY", "op" => "eq"));
            array_push($rules, array("field" => "usuario_log.estado", "data" => "P", "op" => "eq"));
            array_push(
                $rules,
                array(
                    "field" => "usuario_log.valor_despues",
                    "data" => $ConfigurationEnvironment->encryptWithoutRandom(
                        $Usuario->usuarioId . "_" . $VerificationCode
                    ),
                    "op" => "eq"
                )
            );

            $filtro = array("rules" => $rules, "groupOp" => "AND");

            $json = json_encode($filtro);

            setlocale(LC_ALL, 'czech');


            $select = " usuario_log.* ";


            $UsuarioLog = new UsuarioLog();
            $data = $UsuarioLog->getUsuarioLogsCustom(
                $select,
                "usuario_log.usuariolog_id",
                "asc",
                0,
                1,
                $json,
                true,
                ''
            );

            $data = json_decode($data);

            if (oldCount($data->data) > 0) {
                $usuariologId = $data->data[0]->{"usuario_log.usuariolog_id"};
                $UsuarioLog = new UsuarioLog($usuariologId);


                $UsuarioLog->setEstado('A');

                $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
                $UsuarioLogMySqlDAO->update($UsuarioLog);
                $UsuarioLogMySqlDAO->getTransaction()->commit();
            }

            if ($usuariologId != '') {
                //Preguntar a Daniel donde va la siguiente linea de zona horaria
                date_default_timezone_set("America/Bogota");
                $UsuarioLog = new UsuarioLog($usuariologId);

                $hourdiff = round((strtotime($UsuarioLog->fechaCrea) - time()) / 3600, 2);
                $hourdiff = ($hourdiff * -1);


                if ($hourdiff > 0.5 || $UsuarioLog->getEstado() == 'P') {
                    throw new Exception("El recurso ha expirado", "100011");
                }

                if ($UsuarioLog->getTipo() != 'VERIFYACCOUNTTOPLAY') {
                    throw new Exception("El recurso ha expirado", "100011");
                }


                $Usuario = new Usuario ($UsuarioLog->getUsuarioId());


                $UsuarioLog->setEstado('A');
                $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
                $UsuarioLogMySqlDAO->update($UsuarioLog);


                try {
                    $UsuarioToken = new UsuarioToken("", $Proveedor->getProveedorId(), $UsuarioMandante->usumandanteId);
                } catch (Exception $e) {
                    if ($e->getCode() == 21) {
                        $UsuarioToken = new UsuarioToken();
                        $UsuarioToken->setProveedorId($Proveedor->getProveedorId());
                        $UsuarioToken->setCookie('0');
                        $UsuarioToken->setRequestId('0');
                        $UsuarioToken->setUsucreaId(0);
                        $UsuarioToken->setUsumodifId(0);
                        $UsuarioToken->setUsuarioId($UsuarioMandante->usumandanteId);
                        $UsuarioToken->setToken(
                            $this->EstablishmentId . '-' . $this->UserName . '-' . $DeviceId . '-' . $CellPhone
                        );
                        $UsuarioToken->setSaldo(0);
                        $UsuarioToken->setProductoId(0);

                        $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

                        $UsuarioTokenMySqlDAO->insert($UsuarioToken);

                        $UsuarioTokenMySqlDAO->getTransaction()->commit();
                    } else {
                        throw $e;
                    }
                }
            } else {
                throw new Exception("El recurso ha expirado", "100011");
            }

            $return = array(
                "IsSuccess" => true
            );


            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza el proceso de afiliación de un usuario.
     *
     * Este metodo registra un nuevo usuario en el sistema, validando los datos proporcionados
     * y configurando los valores iniciales necesarios para su cuenta. También maneja límites
     * de depósito y registra información adicional del usuario.
     *
     * @param object $dataRegister Objeto que contiene los datos del usuario a registrar, incluyendo:
     *                             - countryBirthIso: Código ISO del país de nacimiento.
     *                             - address: Dirección del usuario.
     *                             - birthdDate: Fecha de nacimiento del usuario.
     *                             - identificationNumber: Número de identificación del usuario.
     *                             - identificationTypeId: Tipo de identificación del usuario.
     *                             - email: Correo electrónico del usuario.
     *                             - expeditionDate: Fecha de expedición del documento de identificación.
     *                             - firstName: Primer nombre del usuario.
     *                             - secondName: Segundo nombre del usuario.
     *                             - firstLastName: Primer apellido del usuario.
     *                             - secondLastName: Segundo apellido del usuario.
     *                             - genderId: Género del usuario (1 para masculino, otro valor para femenino).
     *                             - phone: Teléfono fijo del usuario.
     *                             - cellPhone: Teléfono móvil del usuario.
     *                             - residentCityId: ID de la ciudad de residencia.
     *                             - cityBirthId: ID de la ciudad de nacimiento.
     *                             - postalCode: Código postal del usuario.
     *                             - expeditionCityId: ID de la ciudad de expedición del documento.
     *
     * @return string JSON con el resultado de la operación, incluyendo:
     *                - IsSuccess: Indica si la operación fue exitosa.
     * @throws Exception Si el correo electrónico ya est�� registrado (código 19001).
     * @throws Exception Si el número de identificación ya existe (código 19000).
     *
     * @throws Exception Si algún dato requerido está vacío o no es válido (código 100001).
     */
    public function PERFORMAFFILIATION($dataRegister)
    {
        try {
            $ConfigurationEnvironment = new ConfigurationEnvironment();

            $Proveedor = new Proveedor("", "TOPLAY");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("AUTH");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);


            $site_id = $this->Partner;


            $idioma = strtoupper('es');

            $type_register = 0;


            $Mandante = new Mandante($this->Partner);

            $Pais = new Pais(173);
            $PaisMoneda = new PaisMoneda($Pais->paisId);

            $moneda_default = $PaisMoneda->moneda;

            $estadoUsuarioDefault = 'I';

            $Clasificador = new Clasificador("", "REGISTERACTIVATION");

            try {
                $MandanteDetalle = new MandanteDetalle(
                    "",
                    $Mandante->mandante,
                    $Clasificador->getClasificadorId(),
                    $Pais->paisId,
                    'A'
                );

                $estadoUsuarioDefault = (intval($MandanteDetalle->getValor()) == 1) ? "A" : "I";
            } catch (Exception $e) {
                if ($e->getCode() == 34) {
                } else {
                }
            }

            $PaisBirth = new Pais("", strtolower($dataRegister->countryBirthIso));


            $address = $dataRegister->address;
            $birth_date = explode("T", $dataRegister->birthdDate)[0];

            $docnumber = $dataRegister->identificationNumber;

            $doctype_id = $dataRegister->identificationTypeId;
            $email = $dataRegister->email;

            $expedition_day = date('d', strtotime($dataRegister->expeditionDate));
            $expedition_month = date('m', strtotime($dataRegister->expeditionDate));
            $expedition_year = date('Y', strtotime($dataRegister->expeditionDate));
            $first_name = $dataRegister->firstName;
            $middle_name = $dataRegister->secondName;

            $last_name = $dataRegister->firstLastName;

            $second_last_name = $dataRegister->secondLastName;


            $gender = ($dataRegister->genderId == 1) ? 'M' : 'F';

            $landline_number = $dataRegister->phone;

            $phone = $dataRegister->cellPhone;

            $language = 'es';

            $limit_deposit_day = '';
            $limit_deposit_month = '';
            $limit_deposit_week = '';

            $nationality_id = $PaisBirth->paisId;
            $password = '000000';


            $city_id = $dataRegister->residentCityId;

            $countrybirth_id = $PaisBirth->paisId;
            $departmentbirth_id = '0';

            $citybirth_id = $dataRegister->cityBirthId;

            $cp = $dataRegister->postalCode;

            $expcity_id = $dataRegister->expeditionCityId;

            $nombre = $first_name . " " . $middle_name . " " . $last_name . " " . $second_last_name;
            $clave_activa = $ConfigurationEnvironment->GenerarClaveTicket(15);


            $pais_residencia = $Pais->paisId;
            $depto_nacimiento = $departmentbirth_id;
            $ciudad_nacimiento = $citybirth_id;

            $idioma = 'ES';

            $ciudad_id = $city_id;


            if ($pais_residencia == "") {
                throw new Exception("Inusual Detected", "100001");
            }

            if ($birth_date == "") {
                throw new Exception("Inusual Detected", "100001");
            }

            if ($depto_nacimiento == "") {
                throw new Exception("Inusual Detected", "100001");
            }

            if ($ciudad_nacimiento == "") {
                throw new Exception("Inusual Detected", "100001");
            }

            if ($countrybirth_id == "") {
                throw new Exception("Inusual Detected", "100001");
            }

            if ($idioma == "") {
                throw new Exception("Inusual Detected", "100001");
            }

            if ($gender == "") {
                throw new Exception("Inusual Detected", "100001");
            }

            if ($nationality_id == "") {
                throw new Exception("Inusual Detected", "100001");
            }

            if ($ciudad_id == "") {
                throw new Exception("Inusual Detected", "100001");
            }

            if ($city_id == "") {
                throw new Exception("Inusual Detected", "100001");
            }

            switch ($doctype_id) {
                case 0:
                    $doctype_id = "C";
                    break;

                case 1:
                    $doctype_id = "E";

                    break;

                case 2:

                    $doctype_id = "P";

                    break;

                default:
                    throw new Exception("Inusual Detected", "100001");

                    break;
            }

            if ($email == '') {
                throw new Exception("Inusual Detected", "100001");
            }

            $Usuario = new Usuario();
            $Usuario->login = $email;
            $Usuario->mandante = $Mandante->mandante;

            /* Verificamos si existe el email para el partner */
            $checkLogin = $Usuario->exitsLogin();
            if ($checkLogin) {
                throw new Exception("El email ya esta registrado", "19001");

                throw new Exception("Inusual Detected", "100001");
            }


            $Registro = new Registro();
            $Registro->setCedula($docnumber);
            $Registro->setMandante($Mandante->mandante);

            if ( ! $Registro->existeCedula()) {
                $premio_max = "";
                $premio_max1 = "";
                $premio_max2 = "";
                $premio_max3 = "";
                $cant_lineas = "";
                $lista_id = "";
                $regalo_registro = "";
                $valor_directo = "";
                $valor_evento = "";
                $valor_diario = "";
                $destin1 = "";
                $destin2 = "";
                $destin3 = "";

                $apuesta_min = "";

                $token_itainment = $ConfigurationEnvironment->GenerarClaveTicket2(12);

                $dir_ip = '';

                $RegistroMySqlDAO = new RegistroMySqlDAO();
                $Transaction = $RegistroMySqlDAO->getTransaction();


                $Usuario->login = $email;

                $Usuario->nombre = $nombre;

                $Usuario->estado = $estadoUsuarioDefault;

                $Usuario->fechaUlt = date('Y-m-d H:i:s');

                $Usuario->claveTv = '';

                $Usuario->estadoAnt = 'I';

                $Usuario->intentos = 0;

                $Usuario->estadoEsp = $estadoUsuarioDefault;

                $Usuario->observ = '';

                $Usuario->dirIp = $json->session->usuarioip;

                $Usuario->eliminado = 'N';

                $Usuario->mandante = $Mandante->mandante;

                $Usuario->usucreaId = '0';

                $Usuario->usumodifId = '0';

                $Usuario->claveCasino = '';

                $Usuario->tokenItainment = $token_itainment;

                $Usuario->fechaClave = '';

                $Usuario->retirado = '';

                $Usuario->fechaRetiro = '';

                $Usuario->horaRetiro = '';

                $Usuario->usuretiroId = '0';

                $Usuario->bloqueoVentas = 'N';

                $Usuario->infoEquipo = '';

                $Usuario->estadoJugador = 'NN';

                $Usuario->tokenCasino = '';

                $Usuario->sponsorId = 0;

                $Usuario->verifCorreo = 'N';

                $Usuario->paisId = $Pais->paisId;

                $Usuario->moneda = $moneda_default;

                $Usuario->idioma = $idioma;

                $Usuario->permiteActivareg = 'N';

                $Usuario->test = 'N';

                $Usuario->tiempoLimitedeposito = 0;

                $Usuario->tiempoAutoexclusion = 0;

                $Usuario->cambiosAprobacion = 'S';

                $Usuario->timezone = '-5';

                $Usuario->puntoventaId = 0;
                $Usuario->usucreaId = 0;
                $Usuario->usumodifId = 0;
                $Usuario->usuretiroId = 0;
                $Usuario->tokenItainment = 0;
                $Usuario->sponsorId = (0);

                $Usuario->puntoventaId = 0;

                $Usuario->fechaCrea = date('Y-m-d H:i:s');

                $Usuario->origen = 0;

                $Usuario->fechaActualizacion = $Usuario->fechaCrea;
                $Usuario->documentoValidado = "I";
                $Usuario->fechaDocvalido = $Usuario->fechaCrea;
                $Usuario->usuDocvalido = 0;


                $Usuario->estadoValida = 'N';
                $Usuario->usuvalidaId = 0;
                $Usuario->fechaValida = date('Y-m-d H:i:s');
                $Usuario->contingencia = 'I';
                $Usuario->contingenciaDeportes = 'I';
                $Usuario->contingenciaCasino = 'I';
                $Usuario->contingenciaCasvivo = 'I';
                $Usuario->contingenciaVirtuales = 'I';
                $Usuario->contingenciaPoker = 'I';
                $Usuario->restriccionIp = 'I';
                $Usuario->ubicacionLongitud = '';
                $Usuario->ubicacionLatitud = '';
                $Usuario->usuarioIp = '';
                $Usuario->tokenGoogle = "I";
                $Usuario->tokenLocal = "I";
                $Usuario->saltGoogle = '';


                $Usuario->skype = '';
                $Usuario->plataforma = 0;


                $Usuario->fechaActualizacion = $Usuario->fechaCrea;
                $Usuario->documentoValidado = "A";
                $Usuario->fechaDocvalido = $Usuario->fechaCrea;
                $Usuario->usuDocvalido = 0;

                $UsuarioMySqlDAO = new UsuarioMySqlDAO($Transaction);

                $UsuarioMySqlDAO->insert($Usuario);
                $consecutivo_usuario = $Usuario->usuarioId;

                $Registro->setNombre($nombre);
                $Registro->setEmail($email);
                $Registro->setClaveActiva($clave_activa);
                $Registro->setEstado($estadoUsuarioDefault);
                $Registro->usuarioId = $consecutivo_usuario;
                $Registro->setCelular($phone);
                $Registro->setCreditosBase(0);
                $Registro->setCreditos(0);
                $Registro->setCreditosAnt(0);
                $Registro->setCreditosBaseAnt(0);
                $Registro->setCiudadId($ciudad_id);
                $Registro->setCasino(0);
                $Registro->setCasinoBase(0);
                $Registro->setMandante($Mandante->mandante);
                $Registro->setNombre1($first_name);
                $Registro->setNombre2($middle_name);
                $Registro->setApellido1($last_name);
                $Registro->setApellido2($second_last_name);
                $Registro->setSexo($gender);
                $Registro->setTipoDoc($doctype_id);
                $Registro->setDireccion($address);
                $Registro->setTelefono($landline_number);
                $Registro->setCiudnacimId($ciudad_nacimiento);
                $Registro->setNacionalidadId($nationality_id);
                $Registro->setDirIp($dir_ip);
                $Registro->setOcupacionId(0);
                $Registro->setRangoingresoId(0);
                $Registro->setOrigenfondosId(0);
                $Registro->setPaisnacimId($countrybirth_id);
                $Registro->setPuntoVentaId(0);
                $Registro->setPreregistroId(0);
                $Registro->setCreditosBono(0);
                $Registro->setCreditosBonoAnt(0);
                $Registro->setPreregistroId(0);
                $Registro->setUsuvalidaId(0);
                $Registro->setFechaValida('');
                $Registro->setCodigoPostal($cp);

                $Registro->setCiudexpedId($expcity_id);
                $Registro->setFechaExped($expedition_year . "-" . $expedition_month . "-" . $expedition_day);
                $Registro->setPuntoventaId(0);
                $Registro->setEstadoValida("I");

                $Registro->setAfiliadorId(0);


                $RegistroMySqlDAO->insert($Registro);

                $Transaccion = $RegistroMySqlDAO->getTransaction();


                $UsuarioOtrainfo = new UsuarioOtrainfo();

                $UsuarioOtrainfo->usuarioId = $consecutivo_usuario;
                $UsuarioOtrainfo->fechaNacim = $birth_date;
                $UsuarioOtrainfo->mandante = $Mandante->mandante;
                $UsuarioOtrainfo->bancoId = '0';
                $UsuarioOtrainfo->numCuenta = '0';
                $UsuarioOtrainfo->anexoDoc = 'N';
                $UsuarioOtrainfo->direccion = '';
                $UsuarioOtrainfo->tipoCuenta = '0';


                $UsuarioOtrainfoMySqlDAO = new UsuarioOtrainfoMySqlDAO($Transaccion);

                $UsuarioOtrainfoMySqlDAO->insert($UsuarioOtrainfo);

                $UsuarioPerfil = new UsuarioPerfil();

                $UsuarioPerfil->setUsuarioId($consecutivo_usuario);
                $UsuarioPerfil->setPerfilId('USUONLINE');
                $UsuarioPerfil->setMandante($Mandante->mandante);
                $UsuarioPerfil->setPais('N');
                $UsuarioPerfil->setGlobal('N');


                $UsuarioPerfilMySqlDAO = new UsuarioPerfilMySqlDAO($Transaccion);
                $UsuarioPerfilMySqlDAO->insert($UsuarioPerfil);

                $UsuarioPremiomax = new UsuarioPremiomax();

                $premio_max1 = 0;
                $premio_max2 = 0;
                $premio_max3 = 0;
                $apuesta_min = 0;
                $cant_lineas = 0;
                $valor_directo = 0;
                $valor_evento = 0;
                $valor_diario = 0;

                $UsuarioPremiomax->usuarioId = $consecutivo_usuario;

                $UsuarioPremiomax->premioMax = $premio_max1;

                $UsuarioPremiomax->usumodifId = '0';


                $UsuarioPremiomax->cantLineas = $cant_lineas;

                $UsuarioPremiomax->premioMax1 = $premio_max1;

                $UsuarioPremiomax->premioMax2 = $premio_max2;

                $UsuarioPremiomax->premioMax3 = $premio_max3;

                $UsuarioPremiomax->apuestaMin = $apuesta_min;

                $UsuarioPremiomax->valorDirecto = $valor_directo;
                $UsuarioPremiomax->premioDirecto = $valor_directo;


                $UsuarioPremiomax->mandante = $Mandante->mandante;
                $UsuarioPremiomax->optimizarParrilla = 'N';


                $UsuarioPremiomax->valorEvento = $valor_evento;

                $UsuarioPremiomax->valorDiario = $valor_diario;

                $UsuarioPremiomaxMySqlDAO = new UsuarioPremiomaxMySqlDAO($Transaccion);
                $UsuarioPremiomaxMySqlDAO->insert($UsuarioPremiomax);

                if ($limit_deposit_day != 0 && $limit_deposit_day != '') {
                    $ClientId = $consecutivo_usuario;

                    $Clasificador = new Clasificador("", "LIMITEDEPOSITODIARIO");
                    $tipo = $Clasificador->getClasificadorId();

                    $UsuarioLog = new UsuarioLog();
                    $UsuarioLog->setUsuarioId($ClientId);
                    $UsuarioLog->setUsuarioIp($json->session->usuarioip);
                    $UsuarioLog->setUsuariosolicitaId($ClientId);
                    $UsuarioLog->setUsuariosolicitaIp($json->session->usuarioip);
                    $UsuarioLog->setTipo($tipo);
                    $UsuarioLog->setEstado("P");
                    $UsuarioLog->setValorAntes("");
                    $UsuarioLog->setValorDespues($limit_deposit_day);
                    $UsuarioLog->setUsucreaId(0);
                    $UsuarioLog->setUsumodifId(0);
                    $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO($Transaccion);
                    $UsuarioLogMySqlDAO->insert($UsuarioLog);
                }


                if ($limit_deposit_week != 0 && $limit_deposit_week != '') {
                    $ClientId = $consecutivo_usuario;

                    $Clasificador = new Clasificador("", "LIMITEDEPOSITOSEMANA");
                    $tipo = $Clasificador->getClasificadorId();

                    $UsuarioLog = new UsuarioLog();
                    $UsuarioLog->setUsuarioId($ClientId);
                    $UsuarioLog->setUsuarioIp($json->session->usuarioip);
                    $UsuarioLog->setUsuariosolicitaId($ClientId);
                    $UsuarioLog->setUsuariosolicitaIp($json->session->usuarioip);
                    $UsuarioLog->setTipo($tipo);
                    $UsuarioLog->setEstado("P");
                    $UsuarioLog->setValorAntes("");
                    $UsuarioLog->setValorDespues($limit_deposit_week);
                    $UsuarioLog->setUsucreaId(0);
                    $UsuarioLog->setUsumodifId(0);
                    $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO($Transaccion);
                    $UsuarioLogMySqlDAO->insert($UsuarioLog);
                }


                if ($limit_deposit_month != 0 && $limit_deposit_month != '') {
                    $ClientId = $consecutivo_usuario;

                    $Clasificador = new Clasificador("", "LIMITEDEPOSITOMENSUAL");
                    $tipo = $Clasificador->getClasificadorId();

                    $UsuarioLog = new UsuarioLog();
                    $UsuarioLog->setUsuarioId($ClientId);
                    $UsuarioLog->setUsuarioIp($json->session->usuarioip);
                    $UsuarioLog->setUsuariosolicitaId($ClientId);
                    $UsuarioLog->setUsuariosolicitaIp($json->session->usuarioip);
                    $UsuarioLog->setTipo($tipo);
                    $UsuarioLog->setEstado("P");
                    $UsuarioLog->setValorAntes("");
                    $UsuarioLog->setValorDespues($limit_deposit_month);
                    $UsuarioLog->setUsucreaId(0);
                    $UsuarioLog->setUsumodifId(0);
                    $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO($Transaccion);
                    $UsuarioLogMySqlDAO->insert($UsuarioLog);
                }

                $UsuarioMandante = new UsuarioMandante();

                $UsuarioMandante->mandante = $Usuario->mandante;
                $UsuarioMandante->nombres = $Usuario->nombre;
                $UsuarioMandante->apellidos = $Usuario->nombre;
                $UsuarioMandante->estado = 'A';
                $UsuarioMandante->email = $Usuario->login;
                $UsuarioMandante->moneda = $Usuario->moneda;
                $UsuarioMandante->paisId = $Usuario->paisId;
                $UsuarioMandante->saldo = 0;
                $UsuarioMandante->usuarioMandante = $Usuario->usuarioId;
                $UsuarioMandante->usucreaId = 0;
                $UsuarioMandante->usumodifId = 0;
                $UsuarioMandante->propio = 'S';

                $UsuarioMandanteMySqlDAO = new UsuarioMandanteMySqlDAO($Transaccion);
                $UsuarioMandanteMySqlDAO->insert($UsuarioMandante);

                $Transaccion->commit();

                $Usuario->changeClave($password);


                $return = array(
                    "IsSuccess" => true
                );


                return json_encode($return);
            } else {
                throw new Exception("La cedula ya existe", "19000");
            }
        } catch
        (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un depósito para un usuario en el sistema.
     *
     * Este metodo procesa un depósito, actualiza los registros correspondientes y valida las condiciones necesarias.
     *
     * @param string $transactionId Identificador único de la transacción.
     * @param float  $amount        Monto del depósito.
     * @param array  $params        Parámetros adicionales relacionados con la transacción.
     *
     * @return string JSON con el resultado de la operación, incluyendo:
     *                - IsSuccess: Indica si la operación fue exitosa.
     *                - Message: Mensaje adicional sobre la operación.
     *                - Code: Código de la transacción registrada.
     * @throws Exception Si el monto del depósito está vacío (código 50001).
     * @throws Exception Si el identificador de la transacción está vacío (código 50001).
     * @throws Exception Si el usuario no pertenece al país correspondiente (código 50001).
     * @throws Exception Si el usuario no pertenece al partner correspondiente (código 50001).
     *
     * @throws Exception Si el token del usuario está vacío (código 10011).
     */
    public function DEPOSIT2($transactionId, $amount, $params)
    {
        try {
            $ConfigurationEnvironment = new ConfigurationEnvironment();

            $Proveedor = new Proveedor("", "TOPLAY");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("AUTH");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);


            if ($this->UserName == "") {
                throw new Exception("Token vacio", "10011");
            }


            $UsuarioMandante = new UsuarioMandante($this->UserName);

            $Mandante = new Mandante($UsuarioMandante->mandante);

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

            $Registro = new Registro('', $Usuario->usuarioId);


            $userid = $Usuario->usuarioId;

            $shopReference = $this->EstablishmentId;
            $shop = $this->EstablishmentId;

            $shopReference = $this->EstablishmentId;
            $shop = $this->EstablishmentId;

            if ($amount == "") {
                throw new Exception("Field: Valor", "50001");
            }

            if ($transactionId == "") {
                throw new Exception("Field: transactionId", "50001");
            }


            $MaxRows = 1;
            $OrderedItem = 1;
            $SkeepRows = 0;

            $rules = [];

            $UsuarioPuntoVenta = new Usuario($shop);

            if ($Usuario->paisId != $UsuarioPuntoVenta->paisId) {
                throw new Exception("Usuario no pertenece al pais", "50001");
            }

            if ($Usuario->mandante != $UsuarioPuntoVenta->mandante) {
                throw new Exception("Usuario no pertenece al partner", "50001");
            }

            $UsuarioRecargaMySqlDAO = new UsuarioRecargaMySqlDAO();
            $Transaction = $UsuarioRecargaMySqlDAO->getTransaction();


            $UsuarioRecarga = new UsuarioRecarga();
            $UsuarioRecarga->setRecargaId($consecutivo_recarga);
            $UsuarioRecarga->setUsuarioId($Usuario->usuarioId);
            $UsuarioRecarga->setFechaCrea(date('Y-m-d H:i:s'));
            $UsuarioRecarga->setPuntoventaId($UsuarioPuntoVenta->usuarioId);
            $UsuarioRecarga->setValor($amount);
            $UsuarioRecarga->setPorcenRegaloRecarga(0);
            $UsuarioRecarga->setDirIp(0);
            $UsuarioRecarga->setPromocionalId(0);
            $UsuarioRecarga->setValorPromocional(0);
            $UsuarioRecarga->setHost(0);
            $UsuarioRecarga->setMandante(0);
            $UsuarioRecarga->setPedido(0);
            $UsuarioRecarga->setPorcenIva(0);
            $UsuarioRecarga->setMediopagoId(0);
            $UsuarioRecarga->setValorIva(0);
            $UsuarioRecarga->setEstado('A');
            $UsuarioRecarga->setVersion(2);

            $UsuarioRecargaMySqlDAO->insert($UsuarioRecarga);
            $consecutivo_recarga = $UsuarioRecarga->recargaId;


            $TransaccionApiUsuario = new TransaccionApiUsuario();

            $TransaccionApiUsuario->setUsuarioId($Usuario->usuarioId);
            $TransaccionApiUsuario->setUsuariogeneraId($UsuarioPuntoVenta->usuarioId);
            $TransaccionApiUsuario->setValor($amount);
            $TransaccionApiUsuario->setTipo(0);
            $TransaccionApiUsuario->setTValue(json_encode($params));
            $TransaccionApiUsuario->setRespuestaCodigo("OK");
            $TransaccionApiUsuario->setRespuesta("OK");
            $TransaccionApiUsuario->setTransaccionId($transactionId);

            $TransaccionApiUsuario->setUsucreaId(0);
            $TransaccionApiUsuario->setUsumodifId(0);

            $TransaccionApiUsuario->setIdentificador($consecutivo_recarga);
            $TransaccionApiUsuarioMySqlDAO = new TransaccionApiUsuarioMySqlDAO($Transaction);
            $TransaccionApiUsuarioMySqlDAO->insert($TransaccionApiUsuario);

            $TransapiusuarioLog = new TransapiusuarioLog();

            $TransapiusuarioLog->setIdentificador($consecutivo_recarga);
            $TransapiusuarioLog->setTransaccionId($transactionId);
            $TransapiusuarioLog->setTValue(json_encode($params));
            $TransapiusuarioLog->setTipo(0);
            $TransapiusuarioLog->setValor($amount);
            $TransapiusuarioLog->setUsuariogeneraId($UsuarioPuntoVenta->usuarioId);
            $TransapiusuarioLog->setUsuarioId($Usuario->usuarioId);


            $TransapiusuarioLog->setUsucreaId(0);
            $TransapiusuarioLog->setUsumodifId(0);


            $TransapiusuarioLogMySqlDAO = new TransapiusuarioLogMySqlDAO($Transaction);
            $Transapiusuariolog_id = $TransapiusuarioLogMySqlDAO->insert($TransapiusuarioLog);


            $UsuarioHistorial = new UsuarioHistorial();
            $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
            $UsuarioHistorial->setDescripcion('');
            $UsuarioHistorial->setMovimiento('E');
            $UsuarioHistorial->setUsucreaId(0);
            $UsuarioHistorial->setUsumodifId(0);
            $UsuarioHistorial->setTipo(10);
            $UsuarioHistorial->setValor($amount);
            $UsuarioHistorial->setExternoId($consecutivo_recarga);

            $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
            $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);


            $Usuario->credit($amount, $Transaction);


            $PuntoVenta = new PuntoVenta("", $UsuarioPuntoVenta->usuarioId);

            $PuntoVenta->setBalanceCreditosBase(-$amount);

            $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO($Transaction);
            $PuntoVentaMySqlDAO->updateCreditosConCheck($PuntoVenta);


            $Transaction->commit();

            $return = array(
                "IsSuccess" => true,
                "Message" => "",
                "Code" => $Transapiusuariolog_id
            );


            return json_encode($return);
        } catch
        (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un depósito para un usuario en el sistema.
     *
     * Este metodo procesa un depósito, valida las condiciones necesarias y actualiza los registros correspondientes.
     *
     * @param string $transactionId Identificador único de la transacción.
     * @param float  $amount        Monto del depósito.
     * @param array  $params        Parámetros adicionales relacionados con la transacción.
     *
     * @return string JSON con el resultado de la operación, incluyendo:
     *                - IsSuccess: Indica si la operación fue exitosa.
     *                - Message: Mensaje adicional sobre la operación.
     *                - Code: Código de la transacción registrada.
     * @throws Exception Si el monto del depósito está vacío (código 50001).
     * @throws Exception Si el identificador de la transacción está vacío (código 50001).
     * @throws Exception Si el usuario no pertenece al país correspondiente (código 50001).
     * @throws Exception Si el usuario no pertenece al partner correspondiente (código 50001).
     *
     * @throws Exception Si el token del usuario está vacío (código 10011).
     */
    public function DEPOSIT($transactionId, $amount, $params)
    {
        $this->EstablishmentId = 160;

        try {
            $ConfigurationEnvironment = new ConfigurationEnvironment();

            $Proveedor = new Proveedor("", "TOPLAY");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("AUTH");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);


            if ($this->UserName == "") {
                throw new Exception("Token vacio", "10011");
            }


            $UsuarioMandante = new UsuarioMandante($this->UserName);

            $Mandante = new Mandante($UsuarioMandante->mandante);

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

            $Registro = new Registro('', $Usuario->usuarioId);


            $userid = $Usuario->usuarioId;

            $shopReference = $this->EstablishmentId;
            $shop = $this->EstablishmentId;

            if ($amount == "") {
                throw new Exception("Field: Valor", "50001");
            }

            if ($transactionId == "") {
                throw new Exception("Field: transactionId", "50001");
            }


            $MaxRows = 1;
            $OrderedItem = 1;
            $SkeepRows = 0;


            $rules = [];

            $UsuarioPuntoVenta = new Usuario($shop);

            if ($Usuario->paisId != $UsuarioPuntoVenta->paisId) {
                throw new Exception("Usuario no pertenece al pais", "50001");
            }

            if ($Usuario->mandante != $UsuarioPuntoVenta->mandante) {
                throw new Exception("Usuario no pertenece al partner", "50001");
            }


            $UsuarioRecargaMySqlDAO = new UsuarioRecargaMySqlDAO();
            $Transaction = $UsuarioRecargaMySqlDAO->getTransaction();


            $UsuarioRecarga = new UsuarioRecarga();
            $UsuarioRecarga->setRecargaId($consecutivo_recarga);
            $UsuarioRecarga->setUsuarioId($Usuario->usuarioId);
            $UsuarioRecarga->setFechaCrea(date('Y-m-d H:i:s'));
            $UsuarioRecarga->setPuntoventaId($UsuarioPuntoVenta->usuarioId);
            $UsuarioRecarga->setValor($amount);
            $UsuarioRecarga->setPorcenRegaloRecarga(0);
            $UsuarioRecarga->setDirIp(0);
            $UsuarioRecarga->setPromocionalId(0);
            $UsuarioRecarga->setValorPromocional(0);
            $UsuarioRecarga->setHost(0);
            $UsuarioRecarga->setMandante(0);
            $UsuarioRecarga->setPedido(0);
            $UsuarioRecarga->setPorcenIva(0);
            $UsuarioRecarga->setMediopagoId(0);
            $UsuarioRecarga->setValorIva(0);
            $UsuarioRecarga->setEstado('A');
            $UsuarioRecarga->setVersion(2);


            $UsuarioRecargaMySqlDAO->insert($UsuarioRecarga);
            $consecutivo_recarga = $UsuarioRecarga->recargaId;

            $TransaccionApiUsuario = new TransaccionApiUsuario();

            $TransaccionApiUsuario->setUsuarioId($Usuario->usuarioId);
            $TransaccionApiUsuario->setUsuariogeneraId($UsuarioPuntoVenta->usuarioId);
            $TransaccionApiUsuario->setValor($amount);
            $TransaccionApiUsuario->setTipo(0);
            $TransaccionApiUsuario->setTValue(json_encode($params));
            $TransaccionApiUsuario->setRespuestaCodigo("OK");
            $TransaccionApiUsuario->setRespuesta("OK");
            $TransaccionApiUsuario->setTransaccionId($transactionId);

            $TransaccionApiUsuario->setUsucreaId(0);
            $TransaccionApiUsuario->setUsumodifId(0);

            $TransaccionApiUsuario->setIdentificador($consecutivo_recarga);
            $TransaccionApiUsuarioMySqlDAO = new TransaccionApiUsuarioMySqlDAO($Transaction);
            $TransaccionApiUsuarioMySqlDAO->insert($TransaccionApiUsuario);

            $TransapiusuarioLog = new TransapiusuarioLog();

            $TransapiusuarioLog->setIdentificador($consecutivo_recarga);
            $TransapiusuarioLog->setTransaccionId($transactionId);
            $TransapiusuarioLog->setTValue(json_encode($params));
            $TransapiusuarioLog->setTipo(0);
            $TransapiusuarioLog->setValor($amount);
            $TransapiusuarioLog->setUsuariogeneraId($UsuarioPuntoVenta->usuarioId);
            $TransapiusuarioLog->setUsuarioId($Usuario->usuarioId);


            $TransapiusuarioLog->setUsucreaId(0);
            $TransapiusuarioLog->setUsumodifId(0);


            $TransapiusuarioLogMySqlDAO = new TransapiusuarioLogMySqlDAO($Transaction);
            $Transapiusuariolog_id = $TransapiusuarioLogMySqlDAO->insert($TransapiusuarioLog);


            $UsuarioHistorial = new UsuarioHistorial();
            $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
            $UsuarioHistorial->setDescripcion('');
            $UsuarioHistorial->setMovimiento('E');
            $UsuarioHistorial->setUsucreaId(0);
            $UsuarioHistorial->setUsumodifId(0);
            $UsuarioHistorial->setTipo(10);
            $UsuarioHistorial->setValor($amount);
            $UsuarioHistorial->setExternoId($consecutivo_recarga);

            $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
            $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);

            $Usuario->credit($amount, $Transaction);


            $PuntoVenta = new PuntoVenta("", $UsuarioPuntoVenta->usuarioId);

            $PuntoVenta->setBalanceCreditosBase(-$amount);

            $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO($Transaction);
            $PuntoVentaMySqlDAO->updateCreditosConCheck($PuntoVenta);


            $Transaction->commit();

            $return = array(
                "IsSuccess" => true,
                "Message" => "",
                "Code" => $Transapiusuariolog_id
            );


            return json_encode($return);
        } catch
        (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un retiro para un usuario en el sistema.
     *
     * Este metodo procesa un retiro, valida las condiciones necesarias y actualiza los registros correspondientes.
     *
     * @param string $transactionId Identificador único de la transacción.
     * @param float  $amount        Monto del retiro.
     * @param object $params        Parámetros adicionales relacionados con la transacción.
     *
     * @return string JSON con el resultado de la operación, incluyendo:
     *                - message: Mensaje adicional sobre la operación.
     *                - IsSuccess: Indica si la operación fue exitosa.
     *                - requestValue: Valor solicitado para el retiro.
     *                - realValue: Valor final después de aplicar impuestos y deducciones.
     *                - telephoneNumber: Número de teléfono del usuario.
     *                - email: Correo electrónico del usuario.
     *
     * @throws Exception Si el token del usuario está vacío (código 10011).
     * @throws Exception Si el monto del retiro está vacío (código 50001).
     * @throws Exception Si la cuenta del usuario no está verificada (código 21004).
     * @throws Exception Si el registro del usuario no está aprobado (código 21005).
     * @throws Exception Si los fondos son insuficientes (código 20001).
     * @throws Exception Si el monto es menor al mínimo permitido para retirar (código 21002).
     * @throws Exception Si el monto es mayor al máximo permitido para retirar (código 21003).
     */
    public function WIDTHDRAWAL($transactionId, $amount, $params)
    {
        $this->EstablishmentId = 160;

        try {
            $ConfigurationEnvironment = new ConfigurationEnvironment();

            $Proveedor = new Proveedor("", "TOPLAY");

            $ConfigurationEnvironment = new ConfigurationEnvironment();


            $valorFinal = $amount;
            $valorImpuesto = 0;
            $valorPenalidad = 0;
            $creditos = 0;
            $creditosBase = 0;

            $creditos = $amount;


            $Proveedor = new Proveedor("", "TOPLAY");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("AUTH");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);


            if ($this->UserName == "") {
                throw new Exception("Token vacio", "10011");
            }


            $UsuarioMandante = new UsuarioMandante($this->UserName);

            $Mandante = new Mandante($UsuarioMandante->mandante);

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

            $Registro = new Registro('', $Usuario->usuarioId);


            $userid = $Usuario->usuarioId;

            $shopReference = $this->EstablishmentId;
            $shop = $this->EstablishmentId;

            if ($amount == "") {
                throw new Exception("Field: Valor", "50001");
            }

            if ($transactionId == "") {
                // throw new Exception("Field: transactionId", "50001");
            }


            $transactionId = $params->transactionId;


            if ($transactionId == "") {
                //throw new Exception("Field: transactionId", "50001");

            }


            $MaxRows = 1;
            $OrderedItem = 1;
            $SkeepRows = 0;


            $rules = [];

            /* array_push($rules, array("field" => "usuario.usuario_id", "data" => "$shop", "op" => "eq"));
             array_push($rules, array("field" => "usuario_log.tipo", "data" => "CODIGOAGENT", "op" => "eq"));
             array_push($rules, array("field" => "usuario_log.estado", "data" => "P,A", "op" => "in"));
             array_push($rules, array("field" => "usuario_log.valor_despues", "data" => $token, "op" => "eq"));

             $filtro = array("rules" => $rules, "groupOp" => "AND");

             $json = json_encode($filtro);

             setlocale(LC_ALL, 'czech');


             $select = " usuario_log.* ";


             $UsuarioLog = new UsuarioLog();
             $data = $UsuarioLog->getUsuarioLogsCustom($select, "usuario_log.usuariolog_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping);

             $data = json_decode($data);*/

            /*  array_push($rules, array("field" => "usuario.usuario_id", "data" => "$shop", "op" => "eq"));
              array_push($rules, array("field" => "usuario_token_interno.tipo", "data" => "0", "op" => "eq"));
              array_push($rules, array("field" => "usuario_token_interno.estado", "data" => "'A'", "op" => "in"));
              array_push($rules, array("field" => "usuario_token_interno.valor", "data" => $token, "op" => "eq"));

              $filtro = array("rules" => $rules, "groupOp" => "AND");

              $json = json_encode($filtro);

              setlocale(LC_ALL, 'czech');


              $select = " usuario_token_interno.* ";


              $UsuarioTokenInterno = new UsuarioTokenInterno();
              $data = $UsuarioTokenInterno->getUsuarioTokenInternosCustom($select, "usuario_token_interno.usutokeninterno_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping);

              $data = json_decode($data);*/


            try {
                $Clasificador = new Clasificador("", "ACCVERIFFORWITHDRAW");
                $minimoMontoPremios = 0;

                $MandanteDetalle = new MandanteDetalle(
                    "",
                    $UsuarioMandante->getMandante(),
                    $Clasificador->getClasificadorId(),
                    $Usuario->paisId,
                    'A'
                );


                if ($Usuario->verifcedulaPost != "S" || $Usuario->verifcedulaAnt != 'S') {
                    throw new Exception("La cuenta necesita estar verificada para poder retirar", "21004");
                }
            } catch (Exception $e) {
                if ($e->getCode() != 34 && $e->getCode() != 41) {
                    throw $e;
                }
            }

            try {
                $Clasificador = new Clasificador("", "ACTREGFORWITHDRAW");
                $minimoMontoPremios = 0;

                $MandanteDetalle = new MandanteDetalle(
                    "",
                    $UsuarioMandante->getMandante(),
                    $Clasificador->getClasificadorId(),
                    $Usuario->paisId,
                    'A'
                );

                if ($Registro->estadoValida != 'A') {
                    throw new Exception("El registro debe de estar aprobado para poder retirar", "21005");
                }
            } catch (Exception $e) {
                if ($e->getCode() != 34 && $e->getCode() != 41) {
                    throw $e;
                }
            }


            if ($creditosBase > 0) {
                if ($Registro->getCreditosBase() < $creditosBase) {
                    throw new Exception("Fondos insuficientes", "20001");
                }
            }
            if ($creditos > 0) {
                if ($Registro->getCreditos() < $creditos) {
                    throw new Exception("Fondos insuficientes", "20001");
                }
            }


            //$UsuarioMandante = new UsuarioMandante($json->session->usuario);
            //$Usuario = new Usuario ($UsuarioMandante->getUsuarioMandante());

            //Verificamos limite de minimo retiro
            try {
                $Clasificador = new Clasificador("", "MINWITHDRAW");
                $minimoMontoPremios = 0;

                $MandanteDetalle = new MandanteDetalle(
                    "",
                    $UsuarioMandante->getMandante(),
                    $Clasificador->getClasificadorId(),
                    $Usuario->paisId,
                    'A'
                );
                $minimoMontoPremios = $MandanteDetalle->getValor();
            } catch (Exception $e) {
            }

            if ($amount < $minimoMontoPremios) {
                throw new Exception(
                    "Valor menor al minimo permitido para retirar" . $amount . "-" . $minimoMontoPremios, "21002"
                );
            }


            //Verificamos limite de minimo retiro por punto de venta

            try {
                $Clasificador = new Clasificador("", "MINWITHDRAWBETSHOP");
                $minimoMontoPuntodeVenta = 0;

                $MandanteDetalle = new MandanteDetalle(
                    "",
                    $UsuarioMandante->getMandante(),
                    $Clasificador->getClasificadorId(),
                    $Usuario->paisId,
                    'A'
                );
                $minimoMontoPuntodeVenta = $MandanteDetalle->getValor();
            } catch (Exception $e) {
            }

            if ($amount < $minimoMontoPuntodeVenta) {
                throw new Exception(
                    "Valor menor al minimo permitido para retirar" . $amount . "-" . $minimoMontoPuntodeVenta, "21002"
                );
            }


            //Verificamos limite de maximo retiro
            try {
                $Clasificador = new Clasificador("", "MAXWITHDRAW");
                $maximooMontoPremios = -1;

                $MandanteDetalle = new MandanteDetalle(
                    "",
                    $UsuarioMandante->getMandante(),
                    $Clasificador->getClasificadorId(),
                    $Usuario->paisId,
                    'A'
                );
                $maximooMontoPremios = $MandanteDetalle->getValor();
            } catch (Exception $e) {
            }

            if ($amount > $maximooMontoPremios && $maximooMontoPremios != -1) {
                throw new Exception(
                    "Valor mayor al máximo permitido para retirar" . $amount . "-" . $minimoMontoPremios, "21003"
                );
            }


//Verificamos impuesto retiro

//Si es de Saldo Premios
            if ($creditos > 0) {
                try {
                    $Clasificador = new Clasificador("", "TAXWITHDRAWAWARD");
                    $impuesto = -1;

                    $MandanteDetalle = new MandanteDetalle(
                        "",
                        $UsuarioMandante->getMandante(),
                        $Clasificador->getClasificadorId(),
                        $Usuario->paisId,
                        'A'
                    );
                    $impuesto = $MandanteDetalle->getValor();
                } catch (Exception $e) {
                }

                if ($impuesto > 0) {
                    try {
                        $Clasificador = new Clasificador("", "TAXWITHDRAWAWARDFROM");
                        $impuestoDesde = -1;

                        $MandanteDetalle = new MandanteDetalle(
                            "",
                            $UsuarioMandante->getMandante(),
                            $Clasificador->getClasificadorId(),
                            $Usuario->paisId,
                            'A'
                        );
                        $impuestoDesde = $MandanteDetalle->getValor();
                    } catch (Exception $e) {
                    }

                    if ($impuestoDesde != -1) {
                        if ($amount >= $impuestoDesde) {
                            $valorImpuesto = ($impuesto / 100) * $valorFinal;
                            $valorFinal = $valorFinal - $valorImpuesto;
                        }
                    }
                }
            }

//Si es de Saldo Creditos
            if ($creditosBase > 0) {
                try {
                    $Clasificador = new Clasificador("", "TAXWITHDRAWDEPOSIT");
                    $impuesto = -1;

                    $MandanteDetalle = new MandanteDetalle(
                        "",
                        $UsuarioMandante->getMandante(),
                        $Clasificador->getClasificadorId(),
                        $Usuario->paisId,
                        'A'
                    );
                    $impuesto = $MandanteDetalle->getValor();
                } catch (Exception $e) {
                }

                if ($impuesto > 0) {
                    try {
                        $Clasificador = new Clasificador("", "TAXWITHDRAWAWARDFROM");
                        $impuestoDesde = -1;

                        $MandanteDetalle = new MandanteDetalle(
                            "",
                            $UsuarioMandante->getMandante(),
                            $Clasificador->getClasificadorId(),
                            $Usuario->paisId,
                            'A'
                        );
                        $impuestoDesde = $MandanteDetalle->getValor();
                    } catch (Exception $e) {
                    }

                    if ($impuestoDesde != -1) {
                        if ($amount >= $impuestoDesde) {
                            $valorImpuesto = ($impuesto / 100) * $valorFinal;
                            $valorFinal = $valorFinal - $valorImpuesto;
                        }
                    }
                }
            }


            if ($this->UserName == "") {
                throw new Exception("Token vacio", "10011");
            }


            $UsuarioLog = new UsuarioLog();
            $UsuarioLog->setUsuarioId($Usuario->usuarioId);
            $UsuarioLog->setUsuarioIp('');

            $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
            $UsuarioLog->setUsuariosolicitaIp('');

            $UsuarioLog->setTipo("PREWITHDRAW");


            $UsuarioLog->setEstado("P");
            $UsuarioLog->setValorAntes(json_encode($params));
            $UsuarioLog->setValorDespues('');
            $UsuarioLog->setUsucreaId(0);
            $UsuarioLog->setUsumodifId(0);


            $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
            $usuariologId = $UsuarioLogMySqlDAO->insert($UsuarioLog);
            $UsuarioLogMySqlDAO->getTransaction()->commit();

            $codigo = $ConfigurationEnvironment->GenerarClaveTicket2(6);

            $code = ($ConfigurationEnvironment->encryptWithoutRandom($Usuario->usuarioId . "_" . $codigo));


            $UsuarioLog->setValorDespues($code);


            $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
            $usuariologId = $UsuarioLogMySqlDAO->update($UsuarioLog);
            $UsuarioLogMySqlDAO->getTransaction()->commit();


            $email = $Usuario->login;

            $Mandante = new Mandante($Usuario->mandante);
            switch (strtolower($Usuario->idioma)) {
                case "pt":
                    //Arma el mensaje para el usuario que se registra
                    $mensaje_txt = "Olá, digite este código para validar proceso:";

                    $msubjetc = "";
                    $msubjetc = "";
                    break;

                case "en":
                    //Arma el mensaje para el usuario que se registra
                    $mensaje_txt = "Hello, enter this code to validate process:";

                    $msubjetc = "";
                    $msubjetc = "";
                    break;

                default:

                    //Arma el mensaje para el usuario que se registra

                    $mensaje_txt = "DoradoBet | xxxxxx es el código para completar el proceso de retiro. Para cualquier consulta ingresa al chat.";

                    $msubjetc = "Verificación de solicitud de retiro";
                    $mtitle = "Verificación de solicitud de retiro";
                    break;
            }

            $mensaje_txt = str_replace("xxxxxx", $codigo, $mensaje_txt);
            //Destinatarios
            $destinatarios = $email;


            //Envia el mensaje de correo
            //$envio = $ConfigurationEnvironment->EnviarMensajeTexto($mensaje_txt, '', $Registro->getCelular(), 0,$UsuarioMandante);
            $envio = $ConfigurationEnvironment->EnviarCorreoVersion2(
                $destinatarios,
                'noreply@doradobet.com',
                'Doradobet',
                $msubjetc,
                'mail_registro.php',
                $mtitle,
                $mensaje_txt,
                $dominio,
                $compania,
                $color_email,
                $Mandante->mandante
            );


            $return = array(
                "message" => "",
                "IsSuccess" => true,
                "requestValue" => $amount,
                "realValue" => $valorFinal,
                "telephoneNumber" => $Registro->getCelular(),
                "email" => $Usuario->login
            );


            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Confirma una solicitud de retiro, validando múltiples condiciones y aplicando impuestos si corresponde.
     *
     * @param string $transactionId Identificador de la transacción.
     * @param float  $amount        Monto a retirar.
     * @param object $params        Parámetros adicionales de la solicitud.
     *
     * @return string JSON con el resultado de la operación, incluyendo el estado y el identificador de la transacción.
     * @throws Exception Si el token de usuario está vacío (código 10011).
     * @throws Exception Si el campo `amount` está vacío (código 50001).
     * @throws Exception Si la cuenta no está verificada (código 21004).
     * @throws Exception Si el registro no está aprobado (código 21005).
     * @throws Exception Si los fondos son insuficientes (código 20001).
     * @throws Exception Si el monto es menor al mínimo permitido para retirar (código 21002).
     * @throws Exception Si el monto es mayor al máximo permitido para retirar (código 21003).
     * @throws Exception Si el usuario no pertenece al país (código 50001).
     * @throws Exception Si el usuario no pertenece al partner (código 50001).
     * @throws Exception Si los datos de login son incorrectos (código 86).
     */
    public function WIDTHDRAWALCONFIRMATION2($transactionId, $amount, $params)
    {
        try {
            $ConfigurationEnvironment = new ConfigurationEnvironment();


            $valorFinal = $amount;
            $valorImpuesto = 0;
            $valorPenalidad = 0;
            $creditos = 0;
            $creditosBase = 0;

            $creditos = $amount;


            $Proveedor = new Proveedor("", "TOPLAY");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("AUTH");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);


            if ($this->UserName == "") {
                throw new Exception("Token vacio", "10011");
            }


            $UsuarioMandante = new UsuarioMandante($this->UserName);

            $Mandante = new Mandante($UsuarioMandante->mandante);

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

            $Registro = new Registro('', $Usuario->usuarioId);


            $userid = $Usuario->usuarioId;

            $shopReference = $this->EstablishmentId;
            $shop = $this->EstablishmentId;

            if ($amount == "") {
                throw new Exception("Field: Valor", "50001");
            }

            if ($transactionId == "") {
                // throw new Exception("Field: transactionId", "50001");
            }


            $transactionId = $params->transactionId;


            if ($transactionId == "") {
                // throw new Exception("Field: transactionId", "50001");

            }


            $MaxRows = 1;
            $OrderedItem = 1;
            $SkeepRows = 0;


            $rules = [];

            /* array_push($rules, array("field" => "usuario.usuario_id", "data" => "$shop", "op" => "eq"));
             array_push($rules, array("field" => "usuario_log.tipo", "data" => "CODIGOAGENT", "op" => "eq"));
             array_push($rules, array("field" => "usuario_log.estado", "data" => "P,A", "op" => "in"));
             array_push($rules, array("field" => "usuario_log.valor_despues", "data" => $token, "op" => "eq"));

             $filtro = array("rules" => $rules, "groupOp" => "AND");

             $json = json_encode($filtro);

             setlocale(LC_ALL, 'czech');


             $select = " usuario_log.* ";


             $UsuarioLog = new UsuarioLog();
             $data = $UsuarioLog->getUsuarioLogsCustom($select, "usuario_log.usuariolog_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping);

             $data = json_decode($data);*/

            /*  array_push($rules, array("field" => "usuario.usuario_id", "data" => "$shop", "op" => "eq"));
              array_push($rules, array("field" => "usuario_token_interno.tipo", "data" => "0", "op" => "eq"));
              array_push($rules, array("field" => "usuario_token_interno.estado", "data" => "'A'", "op" => "in"));
              array_push($rules, array("field" => "usuario_token_interno.valor", "data" => $token, "op" => "eq"));

              $filtro = array("rules" => $rules, "groupOp" => "AND");

              $json = json_encode($filtro);

              setlocale(LC_ALL, 'czech');


              $select = " usuario_token_interno.* ";


              $UsuarioTokenInterno = new UsuarioTokenInterno();
              $data = $UsuarioTokenInterno->getUsuarioTokenInternosCustom($select, "usuario_token_interno.usutokeninterno_id", "asc", $SkeepRows, $MaxRows, $json, true, $grouping);

              $data = json_decode($data);*/


            if (true) {
                try {
                    $Clasificador = new Clasificador("", "ACCVERIFFORWITHDRAW");
                    $minimoMontoPremios = 0;

                    $MandanteDetalle = new MandanteDetalle(
                        "",
                        $UsuarioMandante->getMandante(),
                        $Clasificador->getClasificadorId(),
                        $Usuario->paisId,
                        'A'
                    );


                    if ($Usuario->verifcedulaPost != "S" || $Usuario->verifcedulaAnt != 'S') {
                        throw new Exception("La cuenta necesita estar verificada para poder retirar", "21004");
                    }
                } catch (Exception $e) {
                    if ($e->getCode() != 34 && $e->getCode() != 41) {
                        throw $e;
                    }
                }

                try {
                    $Clasificador = new Clasificador("", "ACTREGFORWITHDRAW");
                    $minimoMontoPremios = 0;

                    $MandanteDetalle = new MandanteDetalle(
                        "",
                        $UsuarioMandante->getMandante(),
                        $Clasificador->getClasificadorId(),
                        $Usuario->paisId,
                        'A'
                    );

                    if ($Registro->estadoValida != 'A') {
                        throw new Exception("El registro debe de estar aprobado para poder retirar", "21005");
                    }
                } catch (Exception $e) {
                    if ($e->getCode() != 34 && $e->getCode() != 41) {
                        throw $e;
                    }
                }


                if ($creditosBase > 0) {
                    if ($Registro->getCreditosBase() < $creditosBase) {
                        throw new Exception("Fondos insuficientes", "20001");
                    }
                }
                if ($creditos > 0) {
                    if ($Registro->getCreditos() < $creditos) {
                        throw new Exception("Fondos insuficientes", "20001");
                    }
                }


                //$UsuarioMandante = new UsuarioMandante($json->session->usuario);
                //$Usuario = new Usuario ($UsuarioMandante->getUsuarioMandante());

                //Verificamos limite de minimo retiro
                try {
                    $Clasificador = new Clasificador("", "MINWITHDRAW");
                    $minimoMontoPremios = 0;

                    $MandanteDetalle = new MandanteDetalle(
                        "",
                        $UsuarioMandante->getMandante(),
                        $Clasificador->getClasificadorId(),
                        $Usuario->paisId,
                        'A'
                    );
                    $minimoMontoPremios = $MandanteDetalle->getValor();
                } catch (Exception $e) {
                }

                if ($amount < $minimoMontoPremios) {
                    throw new Exception(
                        "Valor menor al minimo permitido para retirar" . $amount . "-" . $minimoMontoPremios, "21002"
                    );
                }


                //Verificamos limite de minimo retiro por punto de venta

                try {
                    $Clasificador = new Clasificador("", "MINWITHDRAWBETSHOP");
                    $minimoMontoPuntodeVenta = 0;

                    $MandanteDetalle = new MandanteDetalle(
                        "",
                        $UsuarioMandante->getMandante(),
                        $Clasificador->getClasificadorId(),
                        $Usuario->paisId,
                        'A'
                    );
                    $minimoMontoPuntodeVenta = $MandanteDetalle->getValor();
                } catch (Exception $e) {
                }

                if ($amount < $minimoMontoPuntodeVenta) {
                    throw new Exception(
                        "Valor menor al minimo permitido para retirar" . $amount . "-" . $minimoMontoPuntodeVenta,
                        "21002"
                    );
                }


                //Verificamos limite de maximo retiro
                try {
                    $Clasificador = new Clasificador("", "MAXWITHDRAW");
                    $maximooMontoPremios = -1;

                    $MandanteDetalle = new MandanteDetalle(
                        "",
                        $UsuarioMandante->getMandante(),
                        $Clasificador->getClasificadorId(),
                        $Usuario->paisId,
                        'A'
                    );
                    $maximooMontoPremios = $MandanteDetalle->getValor();
                } catch (Exception $e) {
                }

                if ($amount > $maximooMontoPremios && $maximooMontoPremios != -1) {
                    throw new Exception(
                        "Valor mayor al máximo permitido para retirar" . $amount . "-" . $minimoMontoPremios, "21003"
                    );
                }


//Verificamos impuesto retiro

//Si es de Saldo Premios
                if ($creditos > 0) {
                    try {
                        $Clasificador = new Clasificador("", "TAXWITHDRAWAWARD");
                        $impuesto = -1;

                        $MandanteDetalle = new MandanteDetalle(
                            "",
                            $UsuarioMandante->getMandante(),
                            $Clasificador->getClasificadorId(),
                            $Usuario->paisId,
                            'A'
                        );
                        $impuesto = $MandanteDetalle->getValor();
                    } catch (Exception $e) {
                    }

                    if ($impuesto > 0) {
                        try {
                            $Clasificador = new Clasificador("", "TAXWITHDRAWAWARDFROM");
                            $impuestoDesde = -1;

                            $MandanteDetalle = new MandanteDetalle(
                                "",
                                $UsuarioMandante->getMandante(),
                                $Clasificador->getClasificadorId(),
                                $Usuario->paisId,
                                'A'
                            );
                            $impuestoDesde = $MandanteDetalle->getValor();
                        } catch (Exception $e) {
                        }

                        if ($impuestoDesde != -1) {
                            if ($amount >= $impuestoDesde) {
                                $valorImpuesto = ($impuesto / 100) * $valorFinal;
                                $valorFinal = $valorFinal - $valorImpuesto;
                            }
                        }
                    }
                }

//Si es de Saldo Creditos
                if ($creditosBase > 0) {
                    try {
                        $Clasificador = new Clasificador("", "TAXWITHDRAWDEPOSIT");
                        $impuesto = -1;

                        $MandanteDetalle = new MandanteDetalle(
                            "",
                            $UsuarioMandante->getMandante(),
                            $Clasificador->getClasificadorId(),
                            $Usuario->paisId,
                            'A'
                        );
                        $impuesto = $MandanteDetalle->getValor();
                    } catch (Exception $e) {
                    }

                    if ($impuesto > 0) {
                        try {
                            $Clasificador = new Clasificador("", "TAXWITHDRAWAWARDFROM");
                            $impuestoDesde = -1;

                            $MandanteDetalle = new MandanteDetalle(
                                "",
                                $UsuarioMandante->getMandante(),
                                $Clasificador->getClasificadorId(),
                                $Usuario->paisId,
                                'A'
                            );
                            $impuestoDesde = $MandanteDetalle->getValor();
                        } catch (Exception $e) {
                        }

                        if ($impuestoDesde != -1) {
                            if ($amount >= $impuestoDesde) {
                                $valorImpuesto = ($impuesto / 100) * $valorFinal;
                                $valorFinal = $valorFinal - $valorImpuesto;
                            }
                        }
                    }
                }


                /*$Consecutivo = new Consecutivo("", "RET", "");

                $consecutivo_recarga = $Consecutivo->numero;

                $consecutivo_recarga++;

                $ConsecutivoMySqlDAO = new ConsecutivoMySqlDAO();

                $Consecutivo->setNumero($consecutivo_recarga);


                $ConsecutivoMySqlDAO->update($Consecutivo);

                $ConsecutivoMySqlDAO->getTransaction()->commit();*/


                $CuentaCobro = new CuentaCobro();


                //$CuentaCobro->cuentaId = $consecutivo_recarga;

                $CuentaCobro->usuarioId = $Usuario->usuarioId;

                $CuentaCobro->valor = $valorFinal;

                $CuentaCobro->fechaPago = '';

                $CuentaCobro->fechaCrea = date('Y-m-d H:i:s');


                $CuentaCobro->usucambioId = 0;
                $CuentaCobro->usurechazaId = 0;
                $CuentaCobro->usupagoId = 0;

                $CuentaCobro->fechaCambio = $CuentaCobro->fechaCrea;
                $CuentaCobro->fechaAccion = $CuentaCobro->fechaCrea;


                $CuentaCobro->estado = 'A';
                $clave = $ConfigurationEnvironment->GenerarClaveTicket2(5);

                $CuentaCobro->clave = "";

                $CuentaCobro->mandante = '0';

                $CuentaCobro->dirIp = '';

                $CuentaCobro->impresa = 'S';

                $CuentaCobro->mediopagoId = 0;
                $CuentaCobro->puntoventaId = 0;

                $CuentaCobro->costo = $valorPenalidad;
                $CuentaCobro->impuesto = $valorImpuesto;
                $CuentaCobro->creditos = $creditos;
                $CuentaCobro->creditosBase = $creditosBase;

                $CuentaCobro->transproductoId = 0;


                $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();

                $CuentaCobroMySqlDAO->insert($CuentaCobro);
                $consecutivo_recarga = $CuentaCobro->cuentaId;

                if ($creditosBase > 0) {
                    $Usuario->credit(-$creditosBase, $CuentaCobroMySqlDAO->getTransaction());
                    /*$Registro->setCreditosBase("creditos_base - " . $creditosBase);

                    $RegistroMySqlDAO = new RegistroMySqlDAO($CuentaCobroMySqlDAO->getTransaction());

                    $RegistroMySqlDAO->update($Registro);*/
                }

                if ($creditos > 0) {
                    $Usuario->creditWin(-$creditos, $CuentaCobroMySqlDAO->getTransaction());
                    /*$Registro->setCreditos("creditos - " . $creditos);

                    $RegistroMySqlDAO = new RegistroMySqlDAO($CuentaCobroMySqlDAO->getTransaction());

                    $RegistroMySqlDAO->update($Registro);*/
                }

                $UsuarioHistorial = new UsuarioHistorial();
                $UsuarioHistorial->setUsuarioId($CuentaCobro->usuarioId);
                $UsuarioHistorial->setDescripcion('');
                $UsuarioHistorial->setMovimiento('S');
                $UsuarioHistorial->setUsucreaId(0);
                $UsuarioHistorial->setUsumodifId(0);
                $UsuarioHistorial->setTipo(40);
                $UsuarioHistorial->setValor($CuentaCobro->valor);
                $UsuarioHistorial->setExternoId($consecutivo_recarga);

                $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($CuentaCobroMySqlDAO->getTransaction());
                $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);


                $cuentacobroId = $CuentaCobroMySqlDAO->getTransaction()->commit();

                $CuentaCobro = new CuentaCobro($cuentacobroId);
                $Usuario = new Usuario($CuentaCobro->getUsuarioId());

                $UsuarioPuntoVenta = new Usuario($shop);

                if ($Usuario->paisId != $UsuarioPuntoVenta->paisId) {
                    throw new Exception("Usuario no pertenece al pais", "50001");
                }

                if ($Usuario->mandante != $UsuarioPuntoVenta->mandante) {
                    throw new Exception("Usuario no pertenece al partner", "50001");
                }


                $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();
                $Transaction = $CuentaCobroMySqlDAO->getTransaction();
                $Amount = $CuentaCobro->getValor();


                $TransaccionApiUsuario = new TransaccionApiUsuario();

                $TransaccionApiUsuario->setUsuarioId($Usuario->usuarioId);
                $TransaccionApiUsuario->setUsuariogeneraId($UsuarioPuntoVenta->usuarioId);
                $TransaccionApiUsuario->setValor($amount);
                $TransaccionApiUsuario->setTipo(1);
                $TransaccionApiUsuario->setTValue(json_encode($params));
                $TransaccionApiUsuario->setRespuestaCodigo("OK");
                $TransaccionApiUsuario->setRespuesta("OK");
                $TransaccionApiUsuario->setTransaccionId($transactionId);

                $TransaccionApiUsuario->setUsucreaId(0);
                $TransaccionApiUsuario->setUsumodifId(0);

                $TransaccionApiUsuario->setIdentificador($CuentaCobro->getCuentaId());
                $TransaccionApiUsuarioMySqlDAO = new TransaccionApiUsuarioMySqlDAO($Transaction);
                $TransaccionApiUsuarioMySqlDAO->insert($TransaccionApiUsuario);

                $TransapiusuarioLog = new TransapiusuarioLog();

                $TransapiusuarioLog->setIdentificador($CuentaCobro->getCuentaId());
                $TransapiusuarioLog->setTransaccionId($transactionId);
                $TransapiusuarioLog->setTValue(json_encode($params));
                $TransapiusuarioLog->setTipo(1);
                $TransapiusuarioLog->setValor($Amount);
                $TransapiusuarioLog->setUsuariogeneraId($UsuarioPuntoVenta->usuarioId);
                $TransapiusuarioLog->setUsuarioId($Usuario->usuarioId);


                $TransapiusuarioLog->setUsucreaId(0);
                $TransapiusuarioLog->setUsumodifId(0);


                $TransapiusuarioLogMySqlDAO = new TransapiusuarioLogMySqlDAO($Transaction);
                $Transapiusuariolog_id = $TransapiusuarioLogMySqlDAO->insert($TransapiusuarioLog);

                $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO($Transaction);

                $CuentaCobro->setEstado('I');
                $CuentaCobro->setPuntoventaId($UsuarioPuntoVenta->usuarioId);
                $CuentaCobro->setFechaPago(date('Y-m-d H:i:s'));
                $CuentaCobro->setVersion(2);


                $CuentaCobroMySqlDAO->update($CuentaCobro);


                $PuntoVenta = new PuntoVenta("", $UsuarioPuntoVenta->usuarioId);

                // $PuntoVenta->setBalanceCupoRecarga($Amount);

                // $PuntoVentaMySqlDAO->updateCreditosConCheck($PuntoVenta);


                $Transaction->commit();

                $response["transactionId"] = $Transapiusuariolog_id;
            } else {
                throw new Exception("Datos de login incorrectos", "86");
            }


            //$response["depositId"] = $consecutivo_recarga;


            $return = array(
                "IsSuccess" => true,
                "Message" => "",
                "Code" => $Transapiusuariolog_id
            );


            return json_encode($return);
        } catch
        (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Confirma una solicitud de retiro verificando el código de verificación y procesando la transacción.
     *
     * @param string $VerificationCode Código de verificación para confirmar el retiro.
     *
     * @return string JSON con el resultado de la operación, incluyendo el ID de la transacción.
     * @throws Exception Si el token está vacío.
     * @throws Exception Si el recurso ha expirado.
     * @throws Exception Si la cuenta necesita estar verificada para retirar.
     * @throws Exception Si el registro no está aprobado para retirar.
     * @throws Exception Si los fondos son insuficientes.
     * @throws Exception Si el valor es menor al mínimo permitido para retirar.
     * @throws Exception Si el valor es mayor al máximo permitido para retirar.
     * @throws Exception Si los datos de login son incorrectos.
     */
    public function WIDTHDRAWALCONFIRMATION($VerificationCode)
    {
        $this->EstablishmentId = 160;
        try {
            $ConfigurationEnvironment = new ConfigurationEnvironment();
            $Proveedor = new Proveedor("", "TOPLAY");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("AUTH");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);


            if ($this->UserName == "") {
                throw new Exception("Token vacio", "10011");
            }


            $UsuarioMandante = new UsuarioMandante($this->UserName);

            $Mandante = new Mandante($UsuarioMandante->mandante);


            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

            $rules = [];

            array_push($rules, array("field" => "usuario.usuario_id", "data" => "$Usuario->usuarioId", "op" => "eq"));
            array_push($rules, array("field" => "usuario_log.tipo", "data" => "PREWITHDRAW", "op" => "eq"));
            array_push($rules, array("field" => "usuario_log.estado", "data" => "P", "op" => "eq"));
            array_push(
                $rules,
                array(
                    "field" => "usuario_log.valor_despues",
                    "data" => $ConfigurationEnvironment->encryptWithoutRandom(
                        $Usuario->usuarioId . "_" . $VerificationCode
                    ),
                    "op" => "eq"
                )
            );

            $filtro = array("rules" => $rules, "groupOp" => "AND");

            $json = json_encode($filtro);

            setlocale(LC_ALL, 'czech');


            $select = " usuario_log.* ";


            $UsuarioLog = new UsuarioLog();
            $data = $UsuarioLog->getUsuarioLogsCustom(
                $select,
                "usuario_log.usuariolog_id",
                "asc",
                0,
                1,
                $json,
                true,
                ''
            );

            $data = json_decode($data);
            if (oldCount($data->data) > 0) {
                $usuariologId = $data->data[0]->{"usuario_log.usuariolog_id"};

                $UsuarioLog->setEstado('A');

                $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
                $UsuarioLogMySqlDAO->update($UsuarioLog);
                $UsuarioLogMySqlDAO->getTransaction()->commit();
            }

            if ($usuariologId != '') {
                $UsuarioLog = new UsuarioLog($usuariologId);


                $hourdiff = round((strtotime($UsuarioLog->fechaCrea) - time()) / 3600, 2);

                if ($hourdiff > (0.5) || $UsuarioLog->getEstado() != 'P') {
                    throw new Exception("El recurso ha expirado", "100011");
                }

                if ($UsuarioLog->getTipo() != 'PREWITHDRAW') {
                    throw new Exception("El recurso ha expirado", "100011");
                }

                $params = json_decode($UsuarioLog->getValorAntes());
                $transactionId = $params->transactionId;
                $amount = $params->value;

                $this->EstablishmentId = $params->establishmentId;

                $this->UserName = $params->userName;

                $UsuarioLog->setEstado('A');
                $UsuarioLogMySqlDAO = new UsuarioLogMySqlDAO();
                $UsuarioLogMySqlDAO->update($UsuarioLog);
                $UsuarioLogMySqlDAO->getTransaction()->commit();
            } else {
                throw new Exception("El recurso ha expirado", "100011");
            }

            $valorFinal = $amount;
            $valorImpuesto = 0;
            $valorPenalidad = 0;
            $creditos = 0;
            $creditosBase = 0;

            $creditos = $amount;


            $Proveedor = new Proveedor("", "TOPLAY");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("AUTH");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);


            if ($this->UserName == "") {
                throw new Exception("Token vacio", "10011");
            }


            $UsuarioMandante = new UsuarioMandante($this->UserName);

            $Mandante = new Mandante($UsuarioMandante->mandante);

            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);

            $Registro = new Registro('', $Usuario->usuarioId);


            $userid = $Usuario->usuarioId;

            $shopReference = $this->EstablishmentId;
            $shop = $this->EstablishmentId;

            if ($amount == "") {
                throw new Exception("Field: Valor", "50001");
            }

            if ($transactionId == "") {
                // throw new Exception("Field: transactionId", "50001");
            }


            $transactionId = $params->transactionId;


            if ($transactionId == "") {
                // throw new Exception("Field: transactionId", "50001");

            }


            $MaxRows = 1;
            $OrderedItem = 1;
            $SkeepRows = 0;


            $rules = [];

            if (true) {
                try {
                    $Clasificador = new Clasificador("", "ACCVERIFFORWITHDRAW");
                    $minimoMontoPremios = 0;

                    $MandanteDetalle = new MandanteDetalle(
                        "",
                        $UsuarioMandante->getMandante(),
                        $Clasificador->getClasificadorId(),
                        $Usuario->paisId,
                        'A'
                    );


                    if ($Usuario->verifcedulaPost != "S" || $Usuario->verifcedulaAnt != 'S') {
                        throw new Exception("La cuenta necesita estar verificada para poder retirar", "21004");
                    }
                } catch (Exception $e) {
                    if ($e->getCode() != 34 && $e->getCode() != 41) {
                        throw $e;
                    }
                }

                try {
                    $Clasificador = new Clasificador("", "ACTREGFORWITHDRAW");
                    $minimoMontoPremios = 0;

                    $MandanteDetalle = new MandanteDetalle(
                        "",
                        $UsuarioMandante->getMandante(),
                        $Clasificador->getClasificadorId(),
                        $Usuario->paisId,
                        'A'
                    );

                    if ($Registro->estadoValida != 'A') {
                        throw new Exception("El registro debe de estar aprobado para poder retirar", "21005");
                    }
                } catch (Exception $e) {
                    if ($e->getCode() != 34 && $e->getCode() != 41) {
                        throw $e;
                    }
                }


                if ($creditosBase > 0) {
                    if ($Registro->getCreditosBase() < $creditosBase) {
                        throw new Exception("Fondos insuficientes", "20001");
                    }
                }
                if ($creditos > 0) {
                    if ($Registro->getCreditos() < $creditos) {
                        throw new Exception("Fondos insuficientes", "20001");
                    }
                }

                //Verificamos limite de minimo retiro
                try {
                    $Clasificador = new Clasificador("", "MINWITHDRAW");
                    $minimoMontoPremios = 0;

                    $MandanteDetalle = new MandanteDetalle(
                        "",
                        $UsuarioMandante->getMandante(),
                        $Clasificador->getClasificadorId(),
                        $Usuario->paisId,
                        'A'
                    );
                    $minimoMontoPremios = $MandanteDetalle->getValor();
                } catch (Exception $e) {
                }

                if ($amount < $minimoMontoPremios) {
                    throw new Exception(
                        "Valor menor al minimo permitido para retirar" . $amount . "-" . $minimoMontoPremios, "21002"
                    );
                }


                //Verificamos limite de minimo retiro por punto de venta

                try {
                    $Clasificador = new Clasificador("", "MINWITHDRAWBETSHOP");
                    $minimoMontoPuntodeVenta = 0;

                    $MandanteDetalle = new MandanteDetalle(
                        "",
                        $UsuarioMandante->getMandante(),
                        $Clasificador->getClasificadorId(),
                        $Usuario->paisId,
                        'A'
                    );
                    $minimoMontoPuntodeVenta = $MandanteDetalle->getValor();
                } catch (Exception $e) {
                }

                if ($amount < $minimoMontoPuntodeVenta) {
                    throw new Exception(
                        "Valor menor al minimo permitido para retirar" . $amount . "-" . $minimoMontoPuntodeVenta,
                        "21002"
                    );
                }


                //Verificamos limite de maximo retiro
                try {
                    $Clasificador = new Clasificador("", "MAXWITHDRAW");
                    $maximooMontoPremios = -1;

                    $MandanteDetalle = new MandanteDetalle(
                        "",
                        $UsuarioMandante->getMandante(),
                        $Clasificador->getClasificadorId(),
                        $Usuario->paisId,
                        'A'
                    );
                    $maximooMontoPremios = $MandanteDetalle->getValor();
                } catch (Exception $e) {
                }

                if ($amount > $maximooMontoPremios && $maximooMontoPremios != -1) {
                    throw new Exception(
                        "Valor mayor al máximo permitido para retirar" . $amount . "-" . $minimoMontoPremios, "21003"
                    );
                }


//Verificamos impuesto retiro

//Si es de Saldo Premios
                if ($creditos > 0) {
                    try {
                        $Clasificador = new Clasificador("", "TAXWITHDRAWAWARD");
                        $impuesto = -1;

                        $MandanteDetalle = new MandanteDetalle(
                            "",
                            $UsuarioMandante->getMandante(),
                            $Clasificador->getClasificadorId(),
                            $Usuario->paisId,
                            'A'
                        );
                        $impuesto = $MandanteDetalle->getValor();
                    } catch (Exception $e) {
                    }

                    if ($impuesto > 0) {
                        try {
                            $Clasificador = new Clasificador("", "TAXWITHDRAWAWARDFROM");
                            $impuestoDesde = -1;

                            $MandanteDetalle = new MandanteDetalle(
                                "",
                                $UsuarioMandante->getMandante(),
                                $Clasificador->getClasificadorId(),
                                $Usuario->paisId,
                                'A'
                            );
                            $impuestoDesde = $MandanteDetalle->getValor();
                        } catch (Exception $e) {
                        }

                        if ($impuestoDesde != -1) {
                            if ($amount >= $impuestoDesde) {
                                $valorImpuesto = ($impuesto / 100) * $valorFinal;
                                $valorFinal = $valorFinal - $valorImpuesto;
                            }
                        }
                    }
                }

//Si es de Saldo Creditos
                if ($creditosBase > 0) {
                    try {
                        $Clasificador = new Clasificador("", "TAXWITHDRAWDEPOSIT");
                        $impuesto = -1;

                        $MandanteDetalle = new MandanteDetalle(
                            "",
                            $UsuarioMandante->getMandante(),
                            $Clasificador->getClasificadorId(),
                            $Usuario->paisId,
                            'A'
                        );
                        $impuesto = $MandanteDetalle->getValor();
                    } catch (Exception $e) {
                    }

                    if ($impuesto > 0) {
                        try {
                            $Clasificador = new Clasificador("", "TAXWITHDRAWAWARDFROM");
                            $impuestoDesde = -1;

                            $MandanteDetalle = new MandanteDetalle(
                                "",
                                $UsuarioMandante->getMandante(),
                                $Clasificador->getClasificadorId(),
                                $Usuario->paisId,
                                'A'
                            );
                            $impuestoDesde = $MandanteDetalle->getValor();
                        } catch (Exception $e) {
                        }

                        if ($impuestoDesde != -1) {
                            if ($amount >= $impuestoDesde) {
                                $valorImpuesto = ($impuesto / 100) * $valorFinal;
                                $valorFinal = $valorFinal - $valorImpuesto;
                            }
                        }
                    }
                }

                $CuentaCobro = new CuentaCobro();

                $CuentaCobro->usuarioId = $Usuario->usuarioId;

                $CuentaCobro->valor = $valorFinal;

                $CuentaCobro->fechaPago = '';

                $CuentaCobro->fechaCrea = date('Y-m-d H:i:s');


                $CuentaCobro->usucambioId = 0;
                $CuentaCobro->usurechazaId = 0;
                $CuentaCobro->usupagoId = 0;

                $CuentaCobro->fechaCambio = $CuentaCobro->fechaCrea;
                $CuentaCobro->fechaAccion = $CuentaCobro->fechaCrea;


                $CuentaCobro->estado = 'A';
                $clave = $ConfigurationEnvironment->GenerarClaveTicket2(5);

                $CuentaCobro->clave = "''";

                $CuentaCobro->mandante = '0';

                $CuentaCobro->dirIp = '';

                $CuentaCobro->impresa = 'S';

                $CuentaCobro->mediopagoId = 0;
                $CuentaCobro->puntoventaId = 0;

                $CuentaCobro->costo = $valorPenalidad;
                $CuentaCobro->impuesto = $valorImpuesto;
                $CuentaCobro->creditos = $creditos;
                $CuentaCobro->creditosBase = $creditosBase;

                $CuentaCobro->transproductoId = 0;


                $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();
                $CuentaCobroMySqlDAO->insert($CuentaCobro);

                $cuentacobroId = $CuentaCobro->cuentaId;
                $consecutivo_recarga = $CuentaCobro->cuentaId;

                if ($creditosBase > 0) {
                    $Usuario->credit(-$creditosBase, $CuentaCobroMySqlDAO->getTransaction());
                }

                if ($creditos > 0) {
                    $Usuario->creditWin(-$creditos, $CuentaCobroMySqlDAO->getTransaction());
                }

                $UsuarioHistorial = new UsuarioHistorial();
                $UsuarioHistorial->setUsuarioId($CuentaCobro->usuarioId);
                $UsuarioHistorial->setDescripcion('');
                $UsuarioHistorial->setMovimiento('S');
                $UsuarioHistorial->setUsucreaId(0);
                $UsuarioHistorial->setUsumodifId(0);
                $UsuarioHistorial->setTipo(40);
                $UsuarioHistorial->setValor($CuentaCobro->valor);
                $UsuarioHistorial->setExternoId($consecutivo_recarga);

                $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($CuentaCobroMySqlDAO->getTransaction());
                $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);


                $CuentaCobroMySqlDAO->getTransaction()->commit();

                $CuentaCobro = new CuentaCobro($cuentacobroId);
                $Usuario = new Usuario($CuentaCobro->getUsuarioId());

                $UsuarioPuntoVenta = new Usuario($shop);

                if ($Usuario->paisId != $UsuarioPuntoVenta->paisId) {
                    throw new Exception("Usuario no pertenece al pais", "50001");
                }

                if ($Usuario->mandante != $UsuarioPuntoVenta->mandante) {
                    throw new Exception("Usuario no pertenece al partner", "50001");
                }


                $CuentaCobroMySqlDAO = new CuentaCobroMySqlDAO();
                $Transaction = $CuentaCobroMySqlDAO->getTransaction();
                $Amount = $CuentaCobro->getValor();


                $TransaccionApiUsuario = new TransaccionApiUsuario();

                $TransaccionApiUsuario->setUsuarioId($Usuario->usuarioId);
                $TransaccionApiUsuario->setUsuariogeneraId($UsuarioPuntoVenta->usuarioId);
                $TransaccionApiUsuario->setValor($amount);
                $TransaccionApiUsuario->setTipo(1);
                $TransaccionApiUsuario->setTValue(json_encode($params));
                $TransaccionApiUsuario->setRespuestaCodigo("OK");
                $TransaccionApiUsuario->setRespuesta("OK");
                $TransaccionApiUsuario->setTransaccionId($transactionId);

                $TransaccionApiUsuario->setUsucreaId(0);
                $TransaccionApiUsuario->setUsumodifId(0);

                $TransaccionApiUsuario->setIdentificador($CuentaCobro->getCuentaId());
                $TransaccionApiUsuarioMySqlDAO = new TransaccionApiUsuarioMySqlDAO($Transaction);
                $TransaccionApiUsuarioMySqlDAO->insert($TransaccionApiUsuario);

                $TransapiusuarioLog = new TransapiusuarioLog();

                $TransapiusuarioLog->setIdentificador($CuentaCobro->getCuentaId());
                $TransapiusuarioLog->setTransaccionId($transactionId);
                $TransapiusuarioLog->setTValue(json_encode($params));
                $TransapiusuarioLog->setTipo(1);
                $TransapiusuarioLog->setValor($Amount);
                $TransapiusuarioLog->setUsuariogeneraId($UsuarioPuntoVenta->usuarioId);
                $TransapiusuarioLog->setUsuarioId($Usuario->usuarioId);


                $TransapiusuarioLog->setUsucreaId(0);
                $TransapiusuarioLog->setUsumodifId(0);


                $TransapiusuarioLogMySqlDAO = new TransapiusuarioLogMySqlDAO($Transaction);
                $Transapiusuariolog_id = $TransapiusuarioLogMySqlDAO->insert($TransapiusuarioLog);

                $PuntoVentaMySqlDAO = new PuntoVentaMySqlDAO($Transaction);

                $CuentaCobro->setEstado('I');
                $CuentaCobro->setPuntoventaId($UsuarioPuntoVenta->usuarioId);
                $CuentaCobro->setFechaPago(date('Y-m-d H:i:s'));
                $CuentaCobro->setVersion(2);


                $CuentaCobroMySqlDAO->update($CuentaCobro);


                $PuntoVenta = new PuntoVenta("", $UsuarioPuntoVenta->usuarioId);

                $Transaction->commit();

                $response["transactionId"] = $Transapiusuariolog_id;
            } else {
                throw new Exception("Datos de login incorrectos", "86");
            }


            $return = array(
                "IsSuccess" => true,
                "Message" => "",
                "Code" => $Transapiusuariolog_id
            );


            return json_encode($return);
        } catch
        (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene las categorías de juegos disponibles para un tipo de lobby específico.
     *
     * @param string $tipoLobby Tipo de lobby para el cual se obtendrán las categorías (por defecto 'CASINO').
     *
     * @return string JSON con el estado de la operación y las categorías de juegos disponibles.
     *
     * @throws Exception Si ocurre un error durante la obtención de las categorías.
     */
    public function getCategories($tipoLobby = 'CASINO')
    {
        try {
            $CMSCategoria = new CMSCategoria("", $tipoLobby);


            $Categorias = $CMSCategoria->getCategorias();


            $Categorias = json_decode($Categorias);

            $data = $Categorias->data;

            $return_array = array();
            $return_array["status"] = "ok";
            $return_array["total_count"] = $Categorias->total;

            $categories = array();

            foreach ($data as $categoria) {
                if ($categoria->estado == "A") {
                    $game = array();
                    $game["CategoryID"] = $categoria->id;
                    $game["CategoryName"] = $categoria->descripcion;
                    $game["IconField"] = '';


                    array_push($categories, $game);
                }
            }

            $return = array(
                "IsSuccess" => true,
                "GameCategories" => $categories
            );


            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene una lista de juegos disponibles según los filtros proporcionados.
     *
     * @param string $Keyword   Palabra clave para buscar juegos (opcional).
     * @param string $isMobile  Indica si la búsqueda es para dispositivos móviles ('true' o 'false').
     * @param string $offset    Desplazamiento para la paginación (opcional, por defecto 0).
     * @param string $limit     Límite de resultados a devolver (opcional, por defecto 10000).
     * @param string $provider  Proveedor de los juegos (opcional).
     * @param string $category  Categoría de los juegos (opcional).
     * @param string $tipoLobby Tipo de lobby para el cual se obtendrán los juegos (por defecto 'CASINO').
     *
     * @return string JSON con la lista de juegos disponibles.
     *
     * @throws Exception Si el casino está inactivo (código 20023).
     * @throws Exception Si el casino está en contingencia (código 20024).
     * @throws Exception Si el LiveCasino está inactivo (código 20025).
     * @throws Exception Si el LiveCasino está en contingencia (código 20026).
     */
    public function getGames(
        $Keyword = "",
        $isMobile = "",
        $offset = "",
        $limit = "",
        $provider = "",
        $category = "",
        $tipoLobby = 'CASINO'
    ) {
        try {
            $ConfigurationEnvironment = new ConfigurationEnvironment();
            $offset = $ConfigurationEnvironment->DepurarCaracteres($offset);
            $limit = $ConfigurationEnvironment->DepurarCaracteres($limit);
            $provider = $ConfigurationEnvironment->DepurarCaracteres($provider);
            $category = $ConfigurationEnvironment->DepurarCaracteres($category);

            $offset = ($offset != '') ? $offset : 0;
            $limit = ($limit != '') ? $limit : 10000;

            $partner_id = $this->Partner;
            $search = ($Keyword);
            $isMobile = ($isMobile == 'true') ? true : false;


            if ($category == 3) {
                $ConfigurationEnvironment = new \Backend\dto\ConfigurationEnvironment();

                if ($ConfigurationEnvironment->isDevelopment()) {
                    $ProdMandanteTipo = new ProdMandanteTipo($tipoLobby, $partner_id);
                }

                $id = $ConfigurationEnvironment->DepurarCaracteres($_GET["id"]);

                if ($id != "") {
                    $partner_id = $partner_id . " AND producto_mandante.prodmandante_id =" . $id;
                }


                $Proveedor = new CMSProveedor($tipoLobby, "", $partner_id);
            } else {
                $ProdMandanteTipo = new ProdMandanteTipo($tipoLobby, $partner_id);

                if ($ProdMandanteTipo->estado == "I") {
                    throw new Exception("Casino Inactivo", "20023");
                }
                if ($ProdMandanteTipo->contingencia == "A") {
                    throw new Exception("Casino en contingencia", "20024");
                }

                $id = $ConfigurationEnvironment->DepurarCaracteres($_GET["id"]);

                if ($id != "") {
                    $partner_id = $partner_id . " AND producto_mandante.prodmandante_id =" . $id;
                }


                $Proveedor = new CMSProveedor($tipoLobby, "", $partner_id);
            }

            $Productos = $Proveedor->getProductos($category, '', $offset, $limit, $search, $isMobile, $provider);


            $Productos = json_decode($Productos);


            $data = $Productos->data;

            $games = array();

            foreach ($data as $producto) {
                $game = array();
                $game["id"] = $producto->id;
                $game["name"] = $producto->descripcion;
                $game["description"] = '';
                $game["urlImage"] = str_replace("http:", "https:", $producto->image);
                $game["categoryId"] = $producto->categoria->id;
                $game["demo"] = false;
                $game["demo"] = false;
                $game["state"] = true;

                array_push($games, $game);
            }


            $return = $games;


            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Lanza un juego específico en modo real o demo.
     *
     * @param string $GameID ID del juego que se desea lanzar.
     * @param string $Demo   Indica si el juego se lanza en modo demo ('true') o real ('false').
     * @param string $Ip     Dirección IP del usuario que lanza el juego.
     *
     * @return string JSON con el estado de la operación y la URL del juego.
     *
     * @throws Exception Si ocurre un error al obtener el token del usuario.
     * @throws Exception Si ocurre un error al insertar o actualizar el token del usuario.
     * @throws Exception Si ocurre un error al obtener los datos del juego.
     */
    public function launchGame($GameID, $Demo, $Ip)
    {
        try {
            $mode = "real";
            if ($Demo == 'true') {
                $mode = "fun";
            }
            try {
                $UsuarioToken = new UsuarioToken("", "0", $this->UserName);
            } catch (Exception $e) {
                if ($e->getCode() == 21) {
                    $UsuarioToken = new UsuarioToken();
                    $UsuarioToken->setProveedorId('0');
                    $UsuarioToken->setCookie('0');
                    $UsuarioToken->setRequestId('0');
                    $UsuarioToken->setUsucreaId(0);
                    $UsuarioToken->setUsumodifId(0);
                    $UsuarioToken->setUsuarioId($this->UserName);
                    $token = $UsuarioToken->createToken();
                    $UsuarioToken->setToken($token);
                    $UsuarioToken->setSaldo(0);
                    $UsuarioToken->setProductoId(0);

                    $UsuarioTokenMySqlDAO = new UsuarioTokenMySqlDAO();

                    $UsuarioTokenMySqlDAO->insert($UsuarioToken);

                    $UsuarioTokenMySqlDAO->getTransaction()->commit();
                } else {
                    throw $e;
                }
            }

            $ProductoMandante = new ProductoMandante($GameID);

            $Producto = new Producto($ProductoMandante->productoId);

            $Game = new \Backend\integrations\casino\Game(
                $GameID,
                $mode,
                $Producto->proveedorId,
                'spa',
                $this->Partner,
                $UsuarioToken->getToken(),
                'true'
            );


            $URL = $Game->getURL();

            $return = array(
                "IsSuccess" => true,
                "url" => $URL
            );


            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene el historial de apuestas realizadas por un usuario en un rango de fechas y con filtros específicos.
     *
     * @param string $StartDate Fecha de inicio del rango de búsqueda (formato YYYY-MM-DD).
     * @param string $EndDate   Fecha de fin del rango de búsqueda (formato YYYY-MM-DD).
     * @param string $State     Estado de las apuestas (opcional, no utilizado en el código actual).
     * @param string $GameType  Tipo de juego (opcional, no utilizado en el código actual).
     * @param string $tipoLobby Tipo de lobby para el cual se obtendrán las apuestas (por defecto 'CASINO').
     *
     * @return string JSON con el historial de apuestas, incluyendo detalles como ID de apuesta, ID de juego, nombre
     *                del juego, fecha, estado, monto apostado y premios.
     *
     * @throws Exception Si ocurre un error durante la obtención de las transacciones.
     */
    public function getBettingHistory($StartDate, $EndDate, $State, $GameType, $tipoLobby = 'CASINO')
    {
        try {
            $rules = [];

            if ($StartDate != "") {
                array_push(
                    $rules,
                    array("field" => "transaccion_juego.fecha_crea", "data" => "$StartDate ", "op" => "ge")
                );
            }

            if ($EndDate != "") {
                array_push($rules, array("field" => "transaccion_juego.fecha_crea", "data" => "$EndDate", "op" => "le")
                );
            }

            array_push(
                $rules,
                array("field" => "usuario_mandante.usumandante_id", "data" => $this->UserName, "op" => "eq")
            );
            array_push($rules, array("field" => "subproveedor.tipo", "data" => $tipoLobby, "op" => "eq"));

            $filtro = array("rules" => $rules, "groupOp" => "AND");
            $json = json_encode($filtro);


            $select = " usuario_mandante.moneda,producto.*,producto_mandante.*, transaccion_juego.premiado,transaccion_juego.tipo,transaccion_juego.fecha_crea,transaccion_juego.transjuego_id,transaccion_juego.usuario_id,usuario_mandante.usuario_mandante, 1 count,CASE WHEN transaccion_juego.tipo != 'FREESPIN' THEN transaccion_juego.valor_ticket ELSE 0 END apuestas,CASE WHEN transaccion_juego.tipo != 'FREESPIN' THEN transaccion_juego.valor_premio ELSE 0 END premios,CASE WHEN transaccion_juego.tipo = 'FREESPIN' THEN transaccion_juego.valor_ticket ELSE 0 END apuestasBonus,CASE WHEN transaccion_juego.tipo = 'FREESPIN' THEN transaccion_juego.valor_premio ELSE 0 END premiosBonus,proveedor.* ";

            $TransaccionJuego = new TransaccionJuego();
            $data = $TransaccionJuego->getTransaccionesCustom(
                $select,
                "transaccion_juego.transjuego_id",
                "desc",
                0,
                1000,
                $json,
                true,
                ''
            );
            $data = json_decode($data);


            $final = [];

            $papuestas = 0;
            $ppremios = 0;
            $pcont = 0;

            foreach ($data->data as $key => $value) {
                $CurrencyId = $value->{"usuario_mandante.moneda"};
                $array = [];


                $array["betId"] = $value->{"transaccion_juego.transjuego_id"};
                $array["gameId"] = $value->{"producto_mandante.prodmandante_id"};

                $array["name"] = $value->{"producto.descripcion"};
                $array["date"] = $value->{"transaccion_juego.fecha_crea"};

                $array["urlimage"] = $value->{"producto.image_url"};

                $array["state"] = ($value->{"transaccion_juego.premiado"} == 'S') ? "Ganado" : "Perdido";

                $array["amount"] = $value->{".apuestas"};
                $array["wins"] = $value->{".premios"};

                array_push($final, $array);
            }
            $return = $final;


            return json_encode($return);
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Obtiene el balance actual del usuario asociado al token proporcionado.
     *
     * @return string JSON con el balance del usuario en formato multiplicado por 1000.
     * @throws Exception Si ocurre un error al procesar el balance.
     *
     * @throws Exception Si el token está vacío.
     */
    public function getBalance()
    {
        try {
            $Proveedor = new Proveedor("", "TOPLAY");

            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('0');
            $this->transaccionApi->setTipo("BALANCE");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue('');
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);


            if ($this->token == "") {
                throw new Exception("Token vacio", "10011");
            }

            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $Mandante = new Mandante($UsuarioMandante->mandante);

            if ($Mandante->propio == "S") {
                $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                $Balance = intval($Usuario->getBalance() * 1000);

                if ($Usuario->getBalance() <= 0.2) {
                    $Balance = 1;
                    $Balance = intval($Balance * 1000);
                }


                $return = array(

                    "balance" => $Balance
                );
                return json_encode($return);
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un débito en el balance del usuario asociado a un juego específico.
     *
     * @param string $gameId        ID del juego en el que se realiza el débito.
     * @param float  $debitAmount   Monto a debitar del balance del usuario.
     * @param string $roundId       ID de la ronda asociada al débito.
     * @param string $transactionId ID de la transacción asociada al débito.
     * @param array  $datos         Datos adicionales relacionados con la transacción.
     *
     * @return string JSON con el ID de la transacción y el balance actualizado.
     * @throws Exception Si ocurre un error al realizar el débito.
     *
     * @throws Exception Si el token está vacío.
     */
    public function Debit($gameId, $debitAmount, $roundId, $transactionId, $datos)
    {
        $this->data = $datos;

        try {
            if ($this->token == "") {
                throw new Exception("Token vacio", "10011");
            }

            /*  Obtenemos el Proveedor con el abreviado PRAGMATIC */
            $Proveedor = new Proveedor("", "TOPLAY");

            /*  Obtenemos el Usuario Token con el token */
            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());
            /*  Obtenemos el Usuario Mandante con el Usuario Token */
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);


            /*  Obtenemos el producto con el gameId  */
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());


            // Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());


            /*  Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("DEBIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($debitAmount);
            $this->transaccionApi->setIdentificador($roundId . "TOPLAY" . $UsuarioMandante->getUsumandanteId());

            $Game = new Game();

            $isfreeSpin = false;


            $responseG = $Game->debit($UsuarioMandante, $Producto, $this->transaccionApi, $isfreeSpin);

            $this->transaccionApi = $responseG->transaccionApi;

            $saldo = intval($responseG->saldo * 1000);

            $respuesta = json_encode(array(
                "transactionId" => $responseG->transaccionId,
                "balance" => $saldo
            ));


            /*  Guardamos la Transaccion Api necesaria de estado OK   */
            $this->transaccionApi->setRespuesta($respuesta);
            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->update($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();


            return $respuesta;
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un débito en el balance del usuario asociado a un juego específico.
     *
     * @param string $gameId        ID del juego en el que se realiza el débito.
     * @param float  $debitAmount   Monto a debitar del balance del usuario.
     * @param string $roundId       ID de la ronda asociada al débito.
     * @param string $transactionId ID de la transacción asociada al débito.
     * @param array  $datos         Datos adicionales relacionados con la transacción.
     *
     * @return string JSON con el balance actualizado y el ID de la transacción.
     * @throws Exception Si el token está vacío (código 10011).
     * @throws Exception Si el monto a debitar es negativo (código 10002).
     * @throws Exception Si la transacción ya fue procesada (código 10001).
     * @throws Exception Si la transacción tiene un rollback previo (código 10004).
     * @throws Exception Si el ticket de transacción ya existe (código 10010).
     */
    public function Debit2($gameId, $debitAmount, $roundId, $transactionId, $datos)
    {
        $this->data = $datos;

        try {
            if ($this->token == "") {
                throw new Exception("Token vacio", "10011");
            }

            // Obtenemos el Proveedor con el abreviado TOPLAY
            $Proveedor = new Proveedor("", "TOPLAY");

            // Creamos la Transaccion API
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("DEBIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($debitAmount);

            // Verificamos que el monto a debitar sea positivo
            if ($debitAmount < 0) {
                throw new Exception("No puede ser negativo el monto a debitar.", "10002");
            }

            // Obtenemos el Usuario Token con el token
            $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

            // Obtenemos el Usuario Mandante con el Usuario Token
            $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);

            $this->transaccionApi->setIdentificador($roundId . "TOPLAY" . $UsuarioMandante->getUsumandanteId());


            // Obtenemos el producto con el gameId
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            // Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            // Agregamos Elementos a la Transaccion API
            $this->transaccionApi->setProductoId($ProductoMandante->prodmandanteId);
            $this->transaccionApi->setUsuarioId($UsuarioMandante->getUsumandanteId());


            // Verificamos que la transaccionId no se haya procesado antes
            if ($this->transaccionApi->existsTransaccionIdAndProveedor("OK")) {
                // Si la transaccionId ha sido procesada, reportamos el error
                throw new Exception("Transaccion ya procesada", "10001");
            }

            // Creamos la Transaccion API Para verificar si hay antes hubo algun ROLLBACK antes
            $TransaccionApiRollback = new TransaccionApi();
            $TransaccionApiRollback->setProveedorId($Proveedor->getProveedorId());
            $TransaccionApiRollback->setProductoId($ProductoMandante->prodmandanteId);
            $TransaccionApiRollback->setUsuarioId($UsuarioMandante->getUsumandanteId());
            $TransaccionApiRollback->setTransaccionId('ROLLBACK' . $transactionId);
            $TransaccionApiRollback->setTipo("ROLLBACK");
            $TransaccionApiRollback->setTValue(json_encode($datos));
            $TransaccionApiRollback->setUsucreaId(0);
            $TransaccionApiRollback->setUsumodifId(0);


            $DebitConRollbackAntes = false;
            // Verificamos que la transaccionId no se haya procesado antes
            if ($TransaccionApiRollback->existsTransaccionIdAndProveedor("ERROR")) {
                // Si la transaccionId tiene un Rollback antes, reportamos el error
                $DebitConRollbackAntes = true;

                throw new Exception("Transaccion con Rollback antes", "10004");
            }

            // Creamos la Transaccion por el Juego
            $TransaccionJuego = new TransaccionJuego();
            $TransaccionJuego->setProductoId($ProductoMandante->prodmandanteId);
            $TransaccionJuego->setTransaccionId($transactionId);
            $TransaccionJuego->setTicketId($roundId . "TOPLAY" . $UsuarioMandante->getUsumandanteId());
            $TransaccionJuego->setValorTicket($debitAmount);
            $TransaccionJuego->setValorPremio(0);
            $TransaccionJuego->setMandante($UsuarioMandante->mandante);
            $TransaccionJuego->setUsuarioId($UsuarioMandante->usumandanteId);
            $TransaccionJuego->setEstado("A");
            $TransaccionJuego->setUsucreaId(0);
            $TransaccionJuego->setUsumodifId(0);
            $TransaccionJuego->setPremiado("N");
            $TransaccionJuego->setFechaPago(date('Y-m-d H:i:s'));

            $ExisteTicket = false;

            // Verificamos si existe el ticket_id antes, de ser positivo, tendriamos que combinar las apuestas
            if ($TransaccionJuego->existsTicketId()) {
                $this->roundIdSuper = $roundId . "TOPLAY" . $UsuarioMandante->getUsumandanteId();
                throw new Exception("Transaccion Juego Existe", "10010");
            }

            // Obtenemos el mandante para verificar sus caracteristicas
            $Mandante = new Mandante($UsuarioMandante->mandante);

            // Verificamos que el mandante sea Propio, para proceder con nuestros Usuarios
            if ($Mandante->propio == "S") {
                // Obtenemos la Transaccion de la BD para crear y registrar el Debito con esta transaccion
                $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO();
                $Transaction = $TransaccionJuegoMySqlDAO->getTransaction();

                // Verificamos que la Transaccion si este conectada y lista para usarse
                if ($Transaction->isIsconnected()) {
                    // Verificamos si Existe el ticket para combinar las apuestas.
                    if ($ExisteTicket) {
                        // Obtenemos la Transaccion Juego y combinamos las aúestas.
                        $TransaccionJuego = new TransaccionJuego(
                            "",
                            $roundId . "TOPLAY" . $UsuarioMandante->getUsumandanteId(),
                            ""
                        );

                        if ($TransaccionJuego->getTransaccionId() != $transactionId) {
                            $TransaccionJuego->setValorTicket($TransaccionJuego->getValorTicket() + $debitAmount);
                            $TransaccionJuego->update($Transaction);
                        }

                        $transaccion_id = $TransaccionJuego->getTransjuegoId();
                    } else {
                        $transaccion_id = $TransaccionJuego->insert($Transaction);
                    }

                    // Obtenemos el tipo de Transaccion dependiendo de el betTypeID
                    $tipoTransaccion = "DEBIT";

                    // Creamos el log de la transaccion juego para auditoria
                    $TransjuegoLog = new TransjuegoLog();
                    $TransjuegoLog->setTransjuegoId($transaccion_id);
                    $TransjuegoLog->setTransaccionId($transactionId);
                    $TransjuegoLog->setTipo($tipoTransaccion);
                    $TransjuegoLog->setTValue(json_encode($datos));
                    $TransjuegoLog->setUsucreaId(0);
                    $TransjuegoLog->setUsumodifId(0);
                    $TransjuegoLog->setValor($debitAmount);
                    $TransjuegoLog_id = $TransjuegoLog->insert($Transaction);

                    if ( ! $DebitConRollbackAntes) {
                        // Obtenemos nuestro Usuario y hacemos el debito
                        $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                        $Usuario->debit($debitAmount, $Transaction);

                        $UsuarioHistorial = new UsuarioHistorial();
                        $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                        $UsuarioHistorial->setDescripcion('');
                        $UsuarioHistorial->setMovimiento('S');
                        $UsuarioHistorial->setUsucreaId(0);
                        $UsuarioHistorial->setUsumodifId(0);
                        $UsuarioHistorial->setTipo(30);
                        $UsuarioHistorial->setValor($debitAmount);
                        $UsuarioHistorial->setExternoId($TransjuegoLog_id);

                        $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                        $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);
                    }

                    //Commit de la transacción
                    $Transaction->commit();

                    // Consultamos de nuevo el usuario para obtener el saldo
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                    $Balance = intval($Usuario->getBalance() * 1000);

                    // Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket
                    $UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

                    // Enviamos el mensaje Websocket al Usuario para que actualice el saldo
                    $data = $Usuario->getWSMessage($UsuarioToken->getRequestId());
                    $WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
                    $WebsocketUsuario->sendWSMessage();

                    // Guardamos la Transaccion Api necesaria de estado OK
                    $this->transaccionApi->setRespuestaCodigo("OK");
                    $this->transaccionApi->setRespuesta(json_encode(array("balance" => $Balance)));
                    $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
                    $TransaccionApiMySqlDAO->insert($this->transaccionApi);
                    $TransaccionApiMySqlDAO->getTransaction()->commit();

                    $return = array(
                        "balance" => $Balance,
                        "transactionId" => $this->transaccionApi->transapiId
                    );


                    $free = false;
                    if ( ! $free) {


                        exec(
                            "php -f " . __DIR__ . "/VerificarTorneo.php CASINO " . $this->transaccionApi->transapiId . " " . $UsuarioMandante->usuarioMandante . " > /dev/null &"
                        );
                    }

                    return json_encode($return);
                }
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un rollback en el balance del usuario asociado a un juego específico.
     *
     * @param string $gameId         ID del juego en el que se realiza el rollback.
     * @param float  $rollbackAmount Monto a devolver al balance del usuario.
     * @param string $roundId        ID de la ronda asociada al rollback.
     * @param string $transactionId  ID de la transacción asociada al rollback.
     * @param string $player         Identificador del jugador (incluye el prefijo "Usuario").
     * @param array  $datos          Datos adicionales relacionados con la transacción.
     *
     * @return string JSON con el balance actualizado y el ID de la transacción.
     * @throws Exception Si la transacción ya fue procesada (código 10001).
     * @throws Exception Si la transacción no existe (código 10005).
     * @throws Exception Si la transacción no es de tipo "DEBIT" (código 10006).
     * @throws Exception Si el valor del ticket es diferente al valor del rollback (código 10003).
     */
    public function Rollback($gameId, $rollbackAmount, $roundId, $transactionId, $player, $datos)
    {
        $usuarioid = explode("Usuario", $player)[1];

        $this->data = $datos;


        try {
            // Obtenemos el Proveedor con el abreviado TOPLAY
            $Proveedor = new Proveedor("", "TOPLAY");

            // Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId('ROLLBACK' . $transactionId);
            $this->transaccionApi->setTipo("ROLLBACK");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($rollbackAmount);
            $UsuarioMandante = new UsuarioMandante($usuarioid);

            $this->transaccionApi->setIdentificador($roundId . "TOPLAY" . $UsuarioMandante->getUsumandanteId());

            // Obtenemos el producto con el gameId
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            // Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            // Agregamos Elementos a la Transaccion API
            $this->transaccionApi->setProductoId($ProductoMandante->prodmandanteId);
            $this->transaccionApi->setUsuarioId($UsuarioMandante->getUsumandanteId());


            // Verificamos que la transaccionId no se haya procesado antes
            if ($this->transaccionApi->existsTransaccionIdAndProveedor("OK")) {
                // Si la transaccionId ha sido procesada, reportamos el error
                throw new Exception("Transaccion ya procesada", "10001");
            }


            $transaccionNoExiste = false;

            try {
                $TransaccionApi2 = new TransaccionApi("", $transactionId, $Proveedor->getProveedorId());
                $jsonValue = json_decode($TransaccionApi2->getTValue());
                $valorTransaction = 0;

                if (strpos($TransaccionApi2->getTipo(), 'DEBIT') !== false) {
                } else {
                    throw new Exception("Transaccion no es Debit", "10006");
                }
            } catch (Exception $e) {
                $transaccionNoExiste = true;
                throw new Exception("Transaccion no existe", "10005");
            }

            if ( ! $transaccionNoExiste) {
                // Creamos la Transaccion por el Juego
                $TransaccionJuego = new TransaccionJuego("", $TransaccionApi2->getIdentificador());
                $valorTransaction = $TransaccionJuego->getValorTicket();

                // Verificamos que el valor del ticket sea igual al valor del Rollback
                if ($valorTransaction != $rollbackAmount) {
                    throw new Exception("Valor ticket diferente al Rollback", "10003");
                }


                // Obtenemos Mandante para verificar sus caracteristicas
                $Mandante = new Mandante($UsuarioMandante->mandante);

                // Verificamos si el mandante es Propio
                if ($Mandante->propio == "S") {
                    // Obtenemos la Transaccion de la BD para crear y registrar el Debito con esta transaccion
                    $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO();
                    $Transaction = $TransaccionJuegoMySqlDAO->getTransaction();

                    //  Verificamos que la Transaccion si este conectada y lista para usarse
                    if ($Transaction->isIsconnected()) {
                        //  Actualizamos Transaccion Juego
                        $TransaccionJuego->setEstado("I");
                        $TransaccionJuego->update($Transaction);


                        //  Obtenemos el Transaccion Juego ID
                        $TransJuegoId = $TransaccionJuego->getTransjuegoId();

                        //  Creamos el Log de Transaccion Juego
                        $TransjuegoLog = new TransjuegoLog();
                        $TransjuegoLog->setTransjuegoId($TransJuegoId);
                        $TransjuegoLog->setTransaccionId("ROLLBACK" . $transactionId);
                        $TransjuegoLog->setTipo("ROLLBACK");
                        $TransjuegoLog->setTValue(json_encode($datos));
                        $TransjuegoLog->setUsucreaId(0);
                        $TransjuegoLog->setUsumodifId(0);
                        $TransjuegoLog->setValor($rollbackAmount);

                        $TransjuegoLog_id = $TransjuegoLog->insert($Transaction);

                        //  Obtenemos el Usuario para hacerle el credito
                        $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                        $Usuario->credit($TransaccionJuego->getValorTicket(), $Transaction);

                        $UsuarioHistorial = new UsuarioHistorial();
                        $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                        $UsuarioHistorial->setDescripcion('');
                        $UsuarioHistorial->setMovimiento('C');
                        $UsuarioHistorial->setUsucreaId(0);
                        $UsuarioHistorial->setUsumodifId(0);
                        $UsuarioHistorial->setTipo(30);
                        $UsuarioHistorial->setValor($TransaccionJuego->getValorTicket());
                        $UsuarioHistorial->setExternoId($TransjuegoLog_id);

                        $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                        $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);

                        //Commit de la transacción
                        $Transaction->commit();

                        $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                        $Balance = intval($Usuario->getBalance() * 1000);


                        // Guardamos la Transaccion Api necesaria de estado OK
                        $this->transaccionApi->setRespuestaCodigo("OK");
                        $this->transaccionApi->setRespuesta(json_encode(array("balance" => $Balance)));
                        $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
                        $TransaccionApiMySqlDAO->insert($this->transaccionApi);
                        $TransaccionApiMySqlDAO->getTransaction()->commit();

                        $return = array(
                            "balance" => $Balance,
                            "transactionId" => $this->transaccionApi->transapiId
                        );

                        return json_encode($return);
                    }
                }
            } else {
                // Obtenemos Mandante para verificar sus caracteristicas
                $Mandante = new Mandante($UsuarioMandante->mandante);

                // Verificamos si el mandante es Propio
                if ($Mandante->propio == "S") {
                    // Obtenemos la Transaccion de la BD para crear y registrar el Debito con esta transaccion
                    $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO();
                    $Transaction = $TransaccionJuegoMySqlDAO->getTransaction();

                    //  Verificamos que la Transaccion si este conectada y lista para usarse
                    if ($Transaction->isIsconnected()) {
                        $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                        $Balance = intval($Usuario->getBalance() * 1000);


                        // Guardamos la Transaccion Api necesaria de estado OK
                        $this->transaccionApi->setRespuestaCodigo("OK");
                        $this->transaccionApi->setRespuesta(json_encode(array("balance" => $Balance)));

                        $trans = $this->transaccionApi->insert($Transaction);

                        $Transaction->commit();

                        $return = array(
                            "balance" => $Balance,
                            "transactionId" => $trans
                        );

                        return json_encode($return);
                    }
                }
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Realiza un crédito en el balance del usuario asociado a un juego específico.
     *
     * @param string $gameId        ID del juego en el que se realiza el crédito.
     * @param float  $creditAmount  Monto a acreditar al balance del usuario.
     * @param string $roundId       ID de la ronda asociada al crédito.
     * @param string $transactionId ID de la transacción asociada al crédito.
     * @param array  $datos         Datos adicionales relacionados con la transacción.
     *
     * @return string JSON con el balance actualizado y el ID de la transacción.
     * @throws Exception Si ocurre un error al obtener el usuario token (código 21).
     *
     * @throws Exception Si la transacción ya fue procesada (código 10001).
     */
    public function Credit($gameId, $creditAmount, $roundId, $transactionId, $datos)
    {
        $this->data = $datos;

        if ($transactionId == "") {
            $transactionId = "ZERO" . $roundId;
        }

        try {
            // Obtenemos el Proveedor con el abreviado TOPLAY
            $Proveedor = new Proveedor("", "TOPLAY");

            // Creamos la Transaccion API  */
            $this->transaccionApi = new TransaccionApi();
            $this->transaccionApi->setTransaccionId($transactionId);
            $this->transaccionApi->setTipo("CREDIT");
            $this->transaccionApi->setProveedorId($Proveedor->getProveedorId());
            $this->transaccionApi->setTValue(json_encode($datos));
            $this->transaccionApi->setUsucreaId(0);
            $this->transaccionApi->setUsumodifId(0);
            $this->transaccionApi->setValor($creditAmount);

            /*  Obtenemos el Usuario Token con el token */
            try {
                // Obtenemos el Usuario Token con el token
                $UsuarioToken = new UsuarioToken($this->token, $Proveedor->getProveedorId());

                //  Obtenemos el Usuario Mandante con el Usuario Token
                $UsuarioMandante = new UsuarioMandante($UsuarioToken->usuarioId);
            } catch (Exception $e) {
                if ($e->getCode() == 21) {
                    $TransaccionJuego = new TransaccionApi("", $roundId, $Proveedor->getProveedorId());

                    $UsuarioMandante = new UsuarioMandante($TransaccionJuego->getUsuarioId());
                }
            }

            $this->transaccionApi->setIdentificador($roundId . "TOPLAY" . $UsuarioMandante->getUsumandanteId());


            //  Obtenemos el producto con el gameId
            $Producto = new Producto("", $gameId, $Proveedor->getProveedorId());

            //  Obtenemos el producto Mandante para saber si el mandante tiene habilitado el juego
            $ProductoMandante = new ProductoMandante($Producto->getProductoId(), $UsuarioMandante->getMandante());

            //  Agregamos Elementos a la Transaccion API
            $this->transaccionApi->setProductoId($ProductoMandante->prodmandanteId);
            $this->transaccionApi->setUsuarioId($UsuarioMandante->getUsumandanteId());


            //  Verificamos que la transaccionId no se haya procesado antes
            if ($this->transaccionApi->existsTransaccionIdAndProveedor("OK")) {
                //  Si la transaccionId ha sido procesada, reportamos el error
                throw new Exception("Transaccion ya procesada", "10001");
            }


            //  Obtenemos la Transaccion Juego
            $TransaccionJuego = new TransaccionJuego("", $roundId . "TOPLAY" . $UsuarioMandante->getUsumandanteId());

            //  Obtenemos el mandante para verificar sus caracteristicas
            $Mandante = new Mandante($UsuarioMandante->mandante);


            //  Verificamos si el mandante es propio
            if ($Mandante->propio == "S") {
                // Obtenemos la Transaccion de la BD para crear y registrar el Debito con esta transaccion
                $TransaccionJuegoMySqlDAO = new TransaccionJuegoMySqlDAO();
                $Transaction = $TransaccionJuegoMySqlDAO->getTransaction();

                //  Verificamos que la Transaccion si este conectada y lista para usarse
                if ($Transaction->isIsconnected()) {
                    //  Obtenemos el ID de la TransaccionJuego
                    $TransJuegoId = $TransaccionJuego->getTransjuegoId();

                    //  Obtenemos el tipo de transacción dependiendo a betTypeID y si se le suma los creditos o no
                    $sumaCreditos = true;
                    $tipoTransaccion = "CREDIT";

                    //  Creamos el respectivo Log de la transaccion Juego
                    $TransjuegoLog = new TransjuegoLog();
                    $TransjuegoLog->setTransjuegoId($TransJuegoId);
                    $TransjuegoLog->setTransaccionId($transactionId);
                    $TransjuegoLog->setTipo($tipoTransaccion);
                    $TransjuegoLog->setTValue(json_encode($datos));
                    $TransjuegoLog->setUsucreaId(0);
                    $TransjuegoLog->setUsumodifId(0);
                    $TransjuegoLog->setValor($creditAmount);

                    $TransjuegoLog_id = $TransjuegoLog->insert($Transaction);


                    //  Actualizamos la Transaccion Juego con los respectivas actualizaciones
                    $TransaccionJuego->setValorPremio($TransaccionJuego->getValorPremio() + $creditAmount);

                    if ($TransaccionJuego->getValorPremio() > 0) {
                        $TransaccionJuego->setPremiado("S");
                        $TransaccionJuego->setFechaPago(date('Y-m-d H:i:s'));
                    }

                    $TransaccionJuego->setEstado("I");

                    $TransaccionJuego->update($Transaction);

                    //  Si suma los creditos, hacemos el respectivo CREDIT
                    if ($sumaCreditos) {
                        if ($creditAmount > 0) {
                            $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                            $Usuario->creditWin($creditAmount, $Transaction);
                            $UsuarioHistorial = new UsuarioHistorial();
                            $UsuarioHistorial->setUsuarioId($Usuario->usuarioId);
                            $UsuarioHistorial->setDescripcion('');
                            $UsuarioHistorial->setMovimiento('E');
                            $UsuarioHistorial->setUsucreaId(0);
                            $UsuarioHistorial->setUsumodifId(0);
                            $UsuarioHistorial->setTipo(30);
                            $UsuarioHistorial->setValor($creditAmount);
                            $UsuarioHistorial->setExternoId($TransjuegoLog_id);

                            $UsuarioHistorialMySqlDAO = new UsuarioHistorialMySqlDAO($Transaction);
                            $UsuarioHistorialMySqlDAO->insert($UsuarioHistorial);
                        }
                    }

                    // Commit de la transacción
                    $Transaction->commit();

                    // Consultamos de nuevo el usuario para obtener el saldo
                    $Usuario = new Usuario($UsuarioMandante->usuarioMandante);
                    $Balance = intval($Usuario->getBalance() * 1000);

                    //  Consultamos de nuevo el usuario Token para obtener el RequestId actual para el WebSocket
                    $UsuarioToken = new UsuarioToken("", '0', $UsuarioMandante->getUsumandanteId());

                    //  Enviamos el mensaje Websocket al Usuario para que actualice el saldo
                    $data = $Usuario->getWSMessage($UsuarioToken->getRequestId());
                    $WebsocketUsuario = new WebsocketUsuario($UsuarioToken->getRequestId(), $data);
                    $WebsocketUsuario->sendWSMessage();

                    //  Retornamos el mensaje satisfactorio

                    //  Guardamos la Transaccion Api necesaria de estado OK
                    $this->transaccionApi->setRespuestaCodigo("OK");
                    $this->transaccionApi->setRespuesta(json_encode(array("balance" => $Balance)));
                    $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
                    $TransaccionApiMySqlDAO->insert($this->transaccionApi);
                    $TransaccionApiMySqlDAO->getTransaction()->commit();


                    if ($sumaCreditos) {
                        $promolog = $Usuario->verificarBono(
                            "casino",
                            $ProductoMandante->prodmandanteId,
                            $TransaccionJuego->getValorTicket()
                        );
                    }

                    if ($roundId == 0) {
                        $return = array(

                            "balance" => $Balance,
                            "transactionId" => "0"
                        );
                    } else {
                        $return = array(

                            "balance" => $Balance,
                            "transactionId" => $this->transaccionApi->transapiId
                        );
                    }


                    if ($sumaCreditos) {
                        exec(
                            "php -f " . __DIR__ . "/VerificarTorneoPremio.php CASINO " . $this->transaccionApi->transapiId . " " . $UsuarioMandante->usuarioMandante . " > /dev/null &"
                        );
                    }


                    return json_encode($return);
                }
            }
        } catch (Exception $e) {
            return $this->convertError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Verifica un parámetro y devuelve un arreglo con información adicional.
     *
     * @param mixed $param Parámetro a verificar.
     *
     * @return string JSON con el nodo, el parámetro recibido y la firma.
     */
    public function Check($param)
    {
        $return = array(

            "nodeId" => 123,
            "param" => $param,
            "sign" => $this->sign
        );

        return json_encode($return);
    }

    /**
     * Convierte un código de error y un mensaje en una respuesta JSON con información adicional.
     *
     * @param int    $code    Código de error que se desea convertir.
     * @param string $message Mensaje asociado al código de error.
     *
     * @return string JSON con la respuesta que incluye el balance, el ID de la transacción y otros detalles.
     *
     * @throws Exception Si ocurre un error al procesar la transacción o al interactuar con la base de datos.
     */
    public function convertError($code, $message)
    {
        $codeProveedor = "";
        $messageProveedor = "";
        $response = array(
            "isSuccess" => false
        );


        $Proveedor = new Proveedor("", "TOPLAY");

        switch ($code) {
            case 10011:
                $codeProveedor = 6;
                $codeProveedor = "TOKEN_NOT_FOUND";
                $messageProveedor = "Token not found";
                http_response_code(404);
                break;

            case 21:
                $codeProveedor = 6;
                $codeProveedor = "TOKEN_NOT_FOUND";
                $messageProveedor = "Token not found";
                http_response_code(404);
                break;

            case 10013:
                $codeProveedor = 7;
                $codeProveedor = "TOKEN_NOT_FOUND";
                $messageProveedor = "User not found";
                http_response_code(404);
                break;

            case 22:
                $codeProveedor = 7;
                $codeProveedor = "TOKEN_NOT_FOUND";
                $messageProveedor = "User not found";
                http_response_code(404);
                break;

            case 20001:
                $codeProveedor = "INSUFFICIENT_FUNDS";
                $messageProveedor = "Player has insufficient funds";
                http_response_code(402);
                break;

            case 0:
                $codeProveedor = "INTERNAL_ERROR";
                $messageProveedor = "General Error. (" . $code . ")";
                http_response_code(500);
                break;

            case 27:
                $codeProveedor = "INTERNAL_ERROR";
                $messageProveedor = "General Error. (" . $code . ")";
                http_response_code(500);
                break;

            case 28:
                $codeProveedor = "INTERNAL_ERROR";
                $messageProveedor = "General Error. (" . $code . ")";
                http_response_code(500);
                break;

            case 29:
                $codeProveedor = "INTERNAL_ERROR";
                $messageProveedor = "General Error. (" . $code . ")";
                http_response_code(500);
                break;

            case 10001:
                $codeProveedor = "INTERNAL_ERROR";
                $messageProveedor = "General Error. (" . $code . ")";
                http_response_code(500);


                $TransaccionApi = new TransaccionApi(
                    "",
                    $this->transaccionApi->getTransaccionId(),
                    $Proveedor->getProveedorId()
                );

                $codeProveedor = "";
                $messageProveedor = "";

                $Game = new Game();
                /*  Obtenemos el Usuario Mandante con el Usuario Token */
                $UsuarioMandante = new UsuarioMandante($TransaccionApi->getUsuarioId());

                $responseG = $Game->getBalance($UsuarioMandante);

                $saldo = str_replace(',', '', number_format(round($responseG->saldo, 2), 2, '.', null));
                $saldo = intval($saldo * 1000);

                /*  Retornamos el mensaje satisfactorio  */
                $response = array(
                    "balance" => $saldo,
                    "transactionId" => $TransaccionApi->getTransapiId()
                );
                http_response_code(200);

                break;

            case 10004:
                $codeProveedor = "";
                $messageProveedor = "General Error. (" . $code . ")";

                /*  Retornamos el mensaje satisfactorio  */
                $response = array(
                    "balance" => 0
                );


                http_response_code(200);
                break;

            case 10005:
                $codeProveedor = "";
                $messageProveedor = "General Error. (" . $code . ")";

                /*  Retornamos el mensaje satisfactorio  */
                $response = array(
                    "balance" => 0
                );


                http_response_code(200);

                break;

            case 10014:
                $codeProveedor = "INTERNAL_ERROR";
                $messageProveedor = "General Error. (" . $code . ")";
                http_response_code(500);
                break;

            case 10010:
                $codeProveedor = "INTERNAL_ERROR";
                $messageProveedor = "General Error. (" . $code . ")";
                http_response_code(500);
                break;

            case 100010:
                $codeProveedor = "USER_NOT_FOUND";
                $messageProveedor = "No se encontro el usuario consultado";
                http_response_code(400);

                break;

            case 100011:
                $codeProveedor = "INTERNAL_ERROR";
                $messageProveedor = "El recurso ha expirado o no se ha encontrado";
                http_response_code(400);

                break;


            default:
                $codeProveedor = "INTERNAL_ERROR";
                $messageProveedor = "General Error. (" . $code . "). (" . $message . ")";
                http_response_code(500);
                break;
        }


        if ($codeProveedor != "") {
            $respuesta = json_encode(array_merge($response, array(
                "result" => $codeProveedor,
                "message" => $code,
                "description" => $messageProveedor
            )));
        } else {
            $respuesta = json_encode(array_merge($response));
        }

        if ($this->transaccionApi != null) {
            $this->transaccionApi->setTipo("R" . $this->transaccionApi->getTipo());
            $this->transaccionApi->setRespuestaCodigo("ERROR");
            $this->transaccionApi->setRespuesta($respuesta);

            if ($this->transaccionApi->getValor == "") {
                $this->transaccionApi->setValor(0);
            }

            $TransaccionApiMySqlDAO = new TransaccionApiMySqlDAO();
            $TransaccionApiMySqlDAO->insert($this->transaccionApi);
            $TransaccionApiMySqlDAO->getTransaction()->commit();


            if ($this->transaccionApi->getTipo() == "RROLLBACK" && ($code == 10004 || $code == 10005)) {
                $respuesta = json_encode(array_merge($response, array(
                    "balance" => 0,
                    "transactionId" => $this->transaccionApi->transapiId
                )));

                http_response_code(200);
            }
        }


        return $respuesta;
    }


}
