<?php namespace Backend\mysql;

use Backend\dao\UsuarioDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\Helpers;
use Backend\dto\Usuario;
use Backend\sql\QueryExecutor;
use Backend\sql\SqlQuery;
use Backend\sql\Transaction;

use Backend\sql\ConnectionProperty;
use PDO;
/**
 * Clase 'UsuarioMySqlDAO'
 *
 * Esta clase provee las consultas del modelo o tabla 'Usuario'
 *
 * Ejemplo de uso:
 * $UsuarioMySqlDAO = new UsuarioMySqlDAO();
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class UsuarioMySqlDAO implements UsuarioDAO
{

    /**
     * Clave de encriptación
     *
     * @var string
     */
    public $claveEncrypt;



    /**
     * Atributo Transaction transacción
     *
     * @var object
     */
    private $transaction;

    /**
     * Obtener la transacción de un objeto
     *
     * @return Objeto Transaction transacción
     *
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * Modificar el atributo transacción del objeto
     *
     * @param Objeto $Transaction transacción
     *
     * @return no
     *
     */
    public function setTransaction($transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Constructor de clase
     *
     *
     * @param Objeto $transaction transaccion
     *
     * @return no
     * @throws no
     *
     * @access public
     * @see no
     * @since no
     * @deprecated no
     */
    public function __construct($transaction = "")
    {
        $this->claveEncrypt = base64_decode($_ENV['APP_PASSKEY']);
        if ($transaction == "") {
            $transaction = new Transaction();
            $this->transaction = $transaction;
        } else {
            $this->transaction = $transaction;
        }
    }


    /**
     * Obtener todos los registros condicionados por la
     * llave primaria que se pasa como parámetro
     *
     * @param String $id llave primaria
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function load($id)
    {
        $sql = 'SELECT * FROM usuario WHERE usuario_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($id);
        return $this->getRow($sqlQuery);
    }

    /**
     * Obtener todos los registros condicionados por la
     * llave primaria que se pasa como parámetro
     *
     * @param String $id llave primaria
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryAll()
    {
        $sql = 'SELECT * FROM usuario';
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros
     * ordenadas por el nombre de la columna
     * que se pasa como parámetro
     *
     * @param String $orderColumn nombre de la columna
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryAllOrderBy($orderColumn)
    {
        $sql = 'SELECT * FROM usuario ORDER BY ' . $orderColumn;
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }

    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $usuario_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function delete($usuario_id)
    {
        $sql = 'DELETE FROM usuario WHERE usuario_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($usuario_id);
        return $this->executeUpdate($sqlQuery);
    }

    public function querySQL($sql)
    {
        $sqlQuery = new SqlQuery($sql);
        return $this->execute2($sqlQuery);
    }
    /**
     * Insertar un registro en la base de datos
     *
     * @param Objeto usuario usuario
     *
     * @return String $id resultado de la consulta
     *
     */
    public function insert($usuario)
    {
        $sql = 'INSERT INTO usuario (login, clave, nombre, estado, fecha_ult, clave_tv, estado_ant, intentos, estado_esp, observ, fecha_crea, dir_ip, eliminado, mandante, usucrea_id, usumodif_id, fecha_modif, clave_casino, token_itainment, fecha_clave, retirado, fecha_retiro, hora_retiro, usuretiro_id, bloqueo_ventas, info_equipo, estado_jugador, tokenCasino, sponsor_id, verif_correo, pais_id, moneda, idioma, permite_activareg,test,puntoventa_id,tiempo_limitedeposito,tiempo_autoexclusion,cambios_aprobacion,timezone,celular,origen,fecha_actualizacion,documento_validado,fecha_docvalido,usu_docvalido,estado_valida,usuvalida_id,fecha_valida,contingencia,contingencia_deportes,contingencia_casino,contingencia_casvivo,contingencia_virtuales,contingencia_poker,contingencia_retiro,restriccion_ip,ubicacion_longitud,ubicacion_latitud,usuario_ip,token_google,token_local,salt_google,moneda_reporte,verifcedula_ant,verifcedula_post,creditos_afiliacion,fecha_primerdeposito,fecha_cierrecaja,skype,plataforma,maxima_comision,tiempo_comision,arrastra_negativo,estado_import,verif_celular,fecha_verif_celular,puntos_lealtad,nivel_lealtad,verifdomicilio,billetera_id,puntos_aexpirar,monto_primerdeposito,pago_comisiones,equipo_id,permite_enviopublicidad,account_id_jumio) VALUES (?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,?,?,?,?,?,?,?,?)';
        $sqlQuery = new SqlQuery($sql);
        $Helpers = new Helpers();
        $sqlQuery->set($Helpers->set_custom_secret_key('SECRET_PASSPHRASE_LOGIN', true)->encode_data(strtolower($usuario->login)));
        $sqlQuery->set($usuario->clave);
        $sqlQuery->set($Helpers->set_custom_secret_key('SECRET_PASSPHRASE_NAME', true)->encode_data($usuario->nombre));
        $sqlQuery->set($usuario->estado);

        if($usuario->fechaUlt == ''){
            $sqlQuery->setSIN('null');
        }else{
            $sqlQuery->set($usuario->fechaUlt);
        }

        $sqlQuery->set($usuario->claveTv);
        $sqlQuery->set($usuario->estadoAnt);
        $sqlQuery->set($usuario->intentos);
        $sqlQuery->set($usuario->estadoEsp);
        $sqlQuery->set($usuario->observ);

        if($usuario->fechaCrea == ''){
            $sqlQuery->setSIN('null');
        }else{
            $sqlQuery->set($usuario->fechaCrea);
        }

        $sqlQuery->set($usuario->dirIp);
        $sqlQuery->set($usuario->eliminado);
        $sqlQuery->set($usuario->mandante);
        $sqlQuery->set($usuario->usucreaId);
        $sqlQuery->set($usuario->usumodifId);


        if($usuario->fechaModif == ''){
            $sqlQuery->setSIN('null');
        }else{
            $sqlQuery->set($usuario->fechaModif);
        }

        $sqlQuery->set($usuario->claveCasino);
        $sqlQuery->set($usuario->tokenItainment);


        if($usuario->fechaClave == ''){
            $sqlQuery->setSIN('null');
        }else{
            $sqlQuery->set($usuario->fechaClave);
        }


        $sqlQuery->set($usuario->retirado);



        if($usuario->fechaRetiro == ''){
            $sqlQuery->setSIN('null');
        }else{
            $sqlQuery->set($usuario->fechaRetiro);
        }


        $sqlQuery->set($usuario->horaRetiro);
        $sqlQuery->set($usuario->usuretiroId);
        $sqlQuery->set($usuario->bloqueoVentas);
        $sqlQuery->set($usuario->infoEquipo);
        $sqlQuery->set($usuario->estadoJugador);
        $sqlQuery->set($usuario->tokenCasino);
        $sqlQuery->set($usuario->sponsorId);
        $sqlQuery->set($usuario->verifCorreo);
        $sqlQuery->set($usuario->paisId);
        $sqlQuery->set($usuario->moneda);
        $sqlQuery->set($usuario->idioma);
        $sqlQuery->set($usuario->permiteActivareg);
        $sqlQuery->set($usuario->test);
        if($usuario->puntoventaId ==''){
            $usuario->puntoventaId='0';
        }
        $sqlQuery->set($usuario->puntoventaId);
        $sqlQuery->set($usuario->tiempoLimitedeposito);
        $sqlQuery->set($usuario->tiempoAutoexclusion);
        $sqlQuery->set($usuario->cambiosAprobacion);
        $sqlQuery->set($usuario->timezone);
        $sqlQuery->set($usuario->celular);
        $sqlQuery->set($usuario->origen);

        if($usuario->fechaActualizacion == ''){
            $sqlQuery->setSIN('null');
        }else{
            $sqlQuery->set($usuario->fechaActualizacion);
        }

        $sqlQuery->set($usuario->documentoValidado);


        if($usuario->fechaDocvalido == ''){
            $sqlQuery->setSIN('null');
        }else{
            $sqlQuery->set($usuario->fechaDocvalido);
        }

        $sqlQuery->set($usuario->usuDocvalido);
        $sqlQuery->set($usuario->estadoValida);
        $sqlQuery->set($usuario->usuvalidaId);


        if($usuario->fechaValida == ''){
            $sqlQuery->setSIN('null');
        }else{
            $sqlQuery->set($usuario->fechaValida);
        }

        $sqlQuery->set($usuario->contingencia);
        $sqlQuery->set($usuario->contingenciaDeportes);
        $sqlQuery->set($usuario->contingenciaCasino);
        $sqlQuery->set($usuario->contingenciaCasvivo);
        $sqlQuery->set($usuario->contingenciaVirtuales);
        $sqlQuery->set($usuario->contingenciaPoker);

        if($usuario->contingenciaRetiro ==''){
            $usuario->contingenciaRetiro='I';
        }
        $sqlQuery->set($usuario->contingenciaRetiro);

        $sqlQuery->set($usuario->restriccionIp);
        $sqlQuery->set($usuario->ubicacionLongitud);
        $sqlQuery->set($usuario->ubicacionLatitud);
        $sqlQuery->set($usuario->usuarioIp);
        $sqlQuery->set($usuario->tokenGoogle);
        $sqlQuery->set($usuario->tokenLocal);
        $sqlQuery->set($usuario->saltGoogle);
        $sqlQuery->set($usuario->monedaReporte);
        if ($usuario->verifcedulaAnt == "") {
            $usuario->verifcedulaAnt = 'N';
        }
        if ($usuario->verifcedulaPost == "") {
            $usuario->verifcedulaPost = 'N';

        }
        $sqlQuery->set($usuario->verifcedulaAnt);
        $sqlQuery->set($usuario->verifcedulaPost);

        if ($usuario->creditosAfiliacion == "") {
            $usuario->creditosAfiliacion = 0;

        }
        $sqlQuery->set($usuario->creditosAfiliacion);


        if($usuario->fechaPrimerdeposito == ''){
            $sqlQuery->setSIN('null');
        }else{
            $sqlQuery->set($usuario->fechaPrimerdeposito);
        }


        if($usuario->fechaCierrecaja == ''){
            $sqlQuery->setSIN('null');
        }else{
            $sqlQuery->set($usuario->fechaCierrecaja);
        }

        $sqlQuery->set($usuario->skype);

        if ($usuario->plataforma == "") {
            $usuario->plataforma = 0;

        }
        $sqlQuery->set($usuario->plataforma);

        if ($usuario->maximaComision == "") {
            $usuario->maximaComision = 0;

        }
        $sqlQuery->set($usuario->maximaComision);


        if ($usuario->tiempoComision == "") {
            $usuario->tiempoComision = 0;

        }
        $sqlQuery->set($usuario->tiempoComision);

        if ($usuario->arrastraNegativo == "") {
            $usuario->arrastraNegativo = 1;

        }
        $sqlQuery->set($usuario->arrastraNegativo);


        if ($usuario->estadoImport == "") {
            $usuario->estadoImport = 0;

        }
        $sqlQuery->set($usuario->estadoImport);

        if ($usuario->verifCelular == "") {
            $usuario->verifCelular = 'N';

        }
        $sqlQuery->set($usuario->verifCelular);

        if($usuario->fechaVerifCelular == ''){
            $sqlQuery->setSIN('null');
        }else{
            $sqlQuery->set($usuario->fechaVerifCelular);
        }

        if ($usuario->puntosLealtad == "") {
            $usuario->puntosLealtad = 0;
        }
        $sqlQuery->set($usuario->puntosLealtad);

        if ($usuario->nivelLealtad == "") {
            $usuario->nivelLealtad = 0;
        }
        $sqlQuery->set($usuario->nivelLealtad);

        if ($usuario->verifDomicilio == "") {
            $usuario->verifDomicilio = 'N';

        }
        $sqlQuery->set($usuario->verifDomicilio);
        if($usuario->billeteraId == ''){
            $usuario->billeteraId='0';
        }
        $sqlQuery->set($usuario->billeteraId);

        if ($usuario->puntosAexpirar == "") {
            $usuario->puntosAexpirar = 0;
        }

        $sqlQuery->set($usuario->puntosAexpirar);


        if ($usuario->montoPrimerdeposito == "") {
            $usuario->montoPrimerdeposito = 0;
        }
        $sqlQuery->set($usuario->montoPrimerdeposito);



        if($usuario->pagoComisiones==''){
            $usuario->pagoComisiones='F';
        }
        $sqlQuery->set($usuario->pagoComisiones);

        if($usuario->equipoId==''){
            $usuario->equipoId='0';
        }
        $sqlQuery->set($usuario->equipoId);

        if ($usuario->accountIdJumio == "") {
            $usuario->accountIdJumio = '';

        }

        if($usuario->permite_enviarpublicidad ==''){
            $usuario->permite_enviarpublicidad='S';
        }
        $sqlQuery->set($usuario->permite_enviarpublicidad);

        $sqlQuery->set($usuario->accountIdJumio);


        $id = $this->executeInsert($sqlQuery);
        $usuario->usuarioId = $id;
        return $id;
    }

    /**
     * Editar un registro en la base de datos
     *
     * @param Objeto usuario usuario
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function update($usuario)
    {
        $sql = 'UPDATE usuario SET login = ?, nombre = ?, estado = ?, fecha_ult = ?, clave_tv = ?, estado_ant = ?, intentos = ?, estado_esp = ?, observ = ?, fecha_crea = ?, dir_ip = ?, eliminado = ?, mandante = ?, usucrea_id = ?, usumodif_id = ?, fecha_modif = ?, clave_casino = ?, token_itainment = ?, fecha_clave = ?, retirado = ?, fecha_retiro = ?, hora_retiro = ?, usuretiro_id = ?, bloqueo_ventas = ?, info_equipo = ?, estado_jugador = ?, tokenCasino = ?, sponsor_id = ?, verif_correo = ?, pais_id = ?, moneda = ?, idioma = ?, permite_activareg = ?, test = ?, puntoventa_id= ?,tiempo_limitedeposito= ?,tiempo_autoexclusion= ?,cambios_aprobacion= ?,timezone=?, celular=?, origen=?, fecha_actualizacion=?,documento_validado = ?,fecha_docvalido = ?,usu_docvalido = ?,estado_valida = ?,usuvalida_id = ?,fecha_valida = ?, contingencia = ?,contingencia_deportes = ?,contingencia_casino = ?,contingencia_casvivo = ?,contingencia_virtuales = ?,contingencia_poker = ?,restriccion_ip = ?,ubicacion_longitud = ?,ubicacion_latitud = ?,usuario_ip = ?, token_google = ?, token_local = ?, salt_google = ?, moneda_reporte = ?,verifcedula_ant = ? ,verifcedula_post = ?, creditos_afiliacion = ?,fecha_primerdeposito = ?,fecha_cierrecaja = ?,skype = ?, maxima_comision = ?,tiempo_comision = ?,arrastra_negativo = ?,token_quisk = ?,estado_import = ?,verif_celular = ?,fecha_verif_celular = ?, puntos_lealtad = ?, nivel_lealtad = ?, verifdomicilio = ?, billetera_id = ?, puntos_aexpirar = ?,puntos_expirados = ?,monto_primerdeposito = ?, puntos_redimidos = ?, pago_comisiones = ?,equipo_id = ? , verificado = ?, fecha_verificado = ?,contingencia_deposito = ?, contingencia_retiro = ?,permite_enviopublicidad = ?, account_id_jumio = ? WHERE usuario_id = ?';
        $sqlQuery = new SqlQuery($sql);

        $Helpers = new Helpers();

        $sqlQuery->set($Helpers->set_custom_secret_key('SECRET_PASSPHRASE_LOGIN', true)->encode_data(strtolower($usuario->login)));

        if(strlen($usuario->nombre)>150){
            $usuario->nombre = substr($usuario->nombre, 0, 149);
        }

        $sqlQuery->set($Helpers->set_custom_secret_key('SECRET_PASSPHRASE_NAME', true)->encode_data($usuario->nombre));
        $sqlQuery->set($usuario->estado);


        if($usuario->fechaUlt == ''){
            $sqlQuery->setSIN('null');
        }else{
            $sqlQuery->set($usuario->fechaUlt);
        }

        $sqlQuery->set($usuario->claveTv);
        $sqlQuery->set($usuario->estadoAnt);
        if ($usuario->intentos == "") {
            $usuario->intentos = 0;
        }
        $sqlQuery->set($usuario->intentos);
        $sqlQuery->set($usuario->estadoEsp);
        $sqlQuery->set($usuario->observ);


        if($usuario->fechaCrea == ''){
            $sqlQuery->setSIN('null');
        }else{
            $sqlQuery->set($usuario->fechaCrea);
        }



        $sqlQuery->set($usuario->dirIp);
        $sqlQuery->set($usuario->eliminado);
        $sqlQuery->set($usuario->mandante);
        $sqlQuery->set($usuario->usucreaId);
        $sqlQuery->set($usuario->usumodifId);


        if($usuario->fechaModif == ''){
            $sqlQuery->setSIN('null');
        }else{
            $sqlQuery->set($usuario->fechaModif);
        }

        $sqlQuery->set($usuario->claveCasino);
        $sqlQuery->set($usuario->tokenItainment);


        if($usuario->fechaClave == ''){
            $sqlQuery->setSIN('null');
        }else{
            $sqlQuery->set($usuario->fechaClave);
        }

        $sqlQuery->set($usuario->retirado);


        if($usuario->fechaRetiro == ''){
            $sqlQuery->setSIN('null');
        }else{
            $sqlQuery->set($usuario->fechaRetiro);
        }

        $sqlQuery->set($usuario->horaRetiro);
        $sqlQuery->set($usuario->usuretiroId);
        $sqlQuery->set($usuario->bloqueoVentas);
        $sqlQuery->set($usuario->infoEquipo);
        $sqlQuery->set($usuario->estadoJugador);
        $sqlQuery->set($usuario->tokenCasino);
        $sqlQuery->set($usuario->sponsorId);
        $sqlQuery->set($usuario->verifCorreo);
        $sqlQuery->set($usuario->paisId);
        $sqlQuery->set($usuario->moneda);
        $sqlQuery->set($usuario->idioma);
        $sqlQuery->set($usuario->permiteActivareg);
        $sqlQuery->set($usuario->test);
        if($usuario->puntoventaId ==''){
            $usuario->puntoventaId='0';
        }
        $sqlQuery->set($usuario->puntoventaId);
        $sqlQuery->set($usuario->tiempoLimitedeposito);
        $sqlQuery->set($usuario->tiempoAutoexclusion);
        $sqlQuery->set($usuario->cambiosAprobacion);
        $sqlQuery->set($usuario->timezone);
        $sqlQuery->set($usuario->celular);
        $sqlQuery->set($usuario->origen);

        if($usuario->fechaActualizacion == ''){
            $sqlQuery->setSIN('null');
        }else{
            $sqlQuery->set($usuario->fechaActualizacion);
        }

        $sqlQuery->set($usuario->documentoValidado);

        if($usuario->fechaDocvalido == ''){
            $sqlQuery->setSIN('null');
        }else{
            $sqlQuery->set($usuario->fechaDocvalido);
        }

        $sqlQuery->set($usuario->usuDocvalido);
        $sqlQuery->set($usuario->estadoValida);
        $sqlQuery->set($usuario->usuvalidaId);

        if($usuario->fechaValida == ''){
            $sqlQuery->setSIN('null');
        }else{
            $sqlQuery->set($usuario->fechaValida);
        }


        $sqlQuery->set($usuario->contingencia);
        $sqlQuery->set($usuario->contingenciaDeportes);
        $sqlQuery->set($usuario->contingenciaCasino);
        $sqlQuery->set($usuario->contingenciaCasvivo);
        $sqlQuery->set($usuario->contingenciaVirtuales);
        $sqlQuery->set($usuario->contingenciaPoker);
        $sqlQuery->set($usuario->restriccionIp);
        $sqlQuery->set($usuario->ubicacionLongitud);
        $sqlQuery->set($usuario->ubicacionLatitud);
        $sqlQuery->set($usuario->usuarioIp);
        $sqlQuery->set($usuario->tokenGoogle);
        $sqlQuery->set($usuario->tokenLocal);
        $sqlQuery->set($usuario->saltGoogle);
        $sqlQuery->set($usuario->monedaReporte);

        $sqlQuery->set($usuario->verifcedulaAnt);
        $sqlQuery->set($usuario->verifcedulaPost);

        if ($usuario->creditosAfiliacion == "") {
            $usuario->creditosAfiliacion = 0;

        }
        $sqlQuery->setSIN($usuario->creditosAfiliacion);


        if($usuario->fechaPrimerdeposito == ''){
            $sqlQuery->setSIN('null');
        }else{
            $sqlQuery->set($usuario->fechaPrimerdeposito);
        }



        if($usuario->fechaCierrecaja == ''){
            $sqlQuery->setSIN('null');
        }else{
            $sqlQuery->set($usuario->fechaCierrecaja);
        }

        $sqlQuery->set($usuario->skype);

        if ($usuario->maximaComision == "") {
            $usuario->maximaComision = 0;

        }
        $sqlQuery->set($usuario->maximaComision);


        if ($usuario->tiempoComision == "") {
            $usuario->tiempoComision = 0;

        }
        $sqlQuery->set($usuario->tiempoComision);

        if ($usuario->arrastraNegativo == "") {
            $usuario->arrastraNegativo = 1;

        }
        $sqlQuery->set($usuario->arrastraNegativo);
        $sqlQuery->set($usuario->tokenQuisk);


        if ($usuario->estadoImport == "") {
            $usuario->estadoImport = 0;

        }
        $sqlQuery->set($usuario->estadoImport);

        if ($usuario->verifCelular == "") {
            $usuario->verifCelular = 'N';

        }
        $sqlQuery->set($usuario->verifCelular);



        if($usuario->fechaVerifCelular == ''){
            $sqlQuery->setSIN('null');
        }else{
            $sqlQuery->set($usuario->fechaVerifCelular);
        }

        if ($usuario->puntosLealtad == "") {
            $usuario->puntosLealtad = 0;

        }
        $sqlQuery->set($usuario->puntosLealtad);

        if ($usuario->nivelLealtad == "") {
            $usuario->nivelLealtad = 0;

        }
        $sqlQuery->set($usuario->nivelLealtad);
        if ($usuario->verifDomicilio == "") {
            $usuario->verifDomicilio = 'N';

        }
        $sqlQuery->set($usuario->verifDomicilio);
        if($usuario->billeteraId == ''){
            $usuario->billeteraId='0';
        }
        $sqlQuery->set($usuario->billeteraId);


        if ($usuario->puntosAexpirar == "") {
            $usuario->puntosAexpirar = 0;

        }
        $sqlQuery->set($usuario->puntosAexpirar);

        if ($usuario->puntosExpirados == "") {
            $usuario->puntosExpirados = 0;

        }
        $sqlQuery->set($usuario->puntosExpirados);


        if ($usuario->montoPrimerdeposito == "") {
            $usuario->montoPrimerdeposito = 0;
        }
        $sqlQuery->set($usuario->montoPrimerdeposito);

        if ($usuario->puntosRedimidos == "") {
            $usuario->puntosRedimidos = 0;

        }
        $sqlQuery->set($usuario->puntosRedimidos);

        if($usuario->pagoComisiones==''){
            $usuario->pagoComisiones='F';
        }
        $sqlQuery->set($usuario->pagoComisiones);

        if($usuario->equipoId==''){
            $usuario->equipoId='0';
        }
        $sqlQuery->set($usuario->equipoId);

        if($usuario->verificado == ''){
            $sqlQuery->setSIN('verificado');

        }else{
            $sqlQuery->set($usuario->verificado);
        }

        if($usuario->fechaVerificado == ''){
            $sqlQuery->setSIN('fecha_verificado');

        }else{
            $sqlQuery->set($usuario->fechaVerificado);
        }


        if($usuario->contingenciaDeposito==''){
            $usuario->contingenciaDeposito='I';
        }

        $sqlQuery->set($usuario->contingenciaDeposito);


        if($usuario->contingenciaRetiro==''){
            $usuario->contingenciaRetiro='I';
        }

        $sqlQuery->set($usuario->contingenciaRetiro);

        if ($usuario->accountIdJumio == "") {
            $usuario->accountIdJumio = '';
        }

        if($usuario->permite_enviarpublicidad ==''){
            $usuario->permite_enviarpublicidad='S';
        }

        $sqlQuery->set($usuario->permite_enviarpublicidad);
        $sqlQuery->set($usuario->accountIdJumio);



        $sqlQuery->set($usuario->usuarioId);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Actualiza la información de inicio de sesión de un usuario en la base de datos.
     *
     * @param object $usuario Objeto que contiene la información del usuario a actualizar.
     *
     * @return int Número de filas afectadas por la actualización.
     */
    public function updateLogin($usuario)
    {

        $sql = 'UPDATE usuario SET  fecha_ult = ?,estado = ?, intentos = ?, dir_ip = ?, billetera_id = ?  WHERE usuario_id = ?';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->set($usuario->fechaUlt);
        $sqlQuery->set($usuario->estado);

        if ($usuario->intentos == "") {
            $usuario->intentos = 0;
        }
        $sqlQuery->set($usuario->intentos);

        $sqlQuery->set($usuario->dirIp);


        if($usuario->billeteraId == ''){
            $usuario->billeteraId='0';
        }
        $sqlQuery->set($usuario->billeteraId);

        $sqlQuery->setNumber($usuario->usuarioId);
        return $this->executeUpdate($sqlQuery);

        if(false) {


            $sql = 'UPDATE usuario SET  fecha_ult = ?,estado = ?, intentos = ?, dir_ip = ?, billetera_id = ?  WHERE usuario_id = ?';
            $sqlQuery = new SqlQuery($sql);

            $sqlQuery->set($usuario->fechaUlt);
            $sqlQuery->set($usuario->estado);

            if ($usuario->intentos == "") {
                $usuario->intentos = 0;
            }
            $sqlQuery->set($usuario->intentos);

            $sqlQuery->set($usuario->dirIp);


            if ($usuario->billeteraId == '') {
                $usuario->billeteraId = '0';
            }
            $sqlQuery->set($usuario->billeteraId);

            $sqlQuery->set($usuario->usuarioId);
            return $this->executeUpdate($sqlQuery);
        }
    }

    /**
     * Editar la contraseña de un registro en la base de datos
     *
     * @param Objeto usuario usuario
     * @param Objeto clave nueva clave
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function updateClave($usuario, $clave)
    {
        $inTransaction=true;
        if (!$this->transaction->getConnection()->inTransaction()) {

            $transaction = new Transaction();
            $this->transaction = $transaction;
            $inTransaction=false;

        }
        $sql = "SET @@SESSION.block_encryption_mode = 'aes-128-ecb';UPDATE usuario SET clave = aes_encrypt(?,'$this->claveEncrypt') WHERE usuario_id = ?";
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->set($clave);
        $sqlQuery->set($usuario->usuarioId);

        if($inTransaction){
            $idd2 = $this->executeUpdate($sqlQuery);
            $sqlQuery2 = new SqlQuery("SET @@SESSION.block_encryption_mode = 'aes-128-cbc';");
            $this->execute2($sqlQuery2);

            return $idd2;

        }else{
            $idd= $this->executeUpdate($sqlQuery);
            $this->transaction->commit();
            $sqlQuery2 = new SqlQuery("SET @@SESSION.block_encryption_mode = 'aes-128-cbc';");
            $this->execute2($sqlQuery2);
            return $idd;
        }
    }

    /**
     * Actualiza el balance de créditos de afiliación de un usuario.
     *
     * @param int $usuarioId El ID del usuario.
     * @param string $creditos La cantidad de créditos a restar. Por defecto es una cadena vacía.
     * @param bool $withValidation Si es verdadero, valida que el balance de créditos no sea menor a -0.01. Por defecto es falso.
     * @return bool Devuelve verdadero si la actualización fue exitosa, de lo contrario devuelve falso.
     */
    public function updateBalanceCreditosAfiliacion($usuarioId, $creditos="", $withValidation=false) {
        $fields="1=1";
        $where ="";

        if($creditos != ""){
            $fields .= ", creditos_afiliacion = creditos_afiliacion - '".$creditos."' ";

            if($withValidation){
                $where .= " AND creditos_afiliacion - '".$creditos."' > -0.01 ";
            }
        }

        if($fields != "1=1"){
            $fields = str_replace("1=1,","",$fields);
            $sql = 'UPDATE usuario SET ' . $fields.' WHERE usuario_id = ? '.$where;
            $sqlQuery = new SqlQuery($sql);

            $sqlQuery->set($usuarioId);
            return $this->executeUpdate($sqlQuery);
        }

        return false;
    }


    /**
     * Actualiza los puntos de balance de un usuario.
     *
     * @param int $UsuarioId El ID del usuario.
     * @param int $creditos Créditos del usuario (opcional).
     * @param int $puntos_lealtad Puntos de lealtad del usuario (opcional).
     * @param bool $withValidation Indica si se debe realizar validación (opcional).
     * @return int|bool Retorna el ID de la inserción si se insertan puntos de lealtad positivos, 
     *                  el número de filas afectadas si se actualizan puntos de lealtad negativos, 
     *                  o false en caso de error.
     */
    public function updateBalancePoints($UsuarioId,$creditos="",$puntos_lealtad="",$withValidation=false)
    {

        if($puntos_lealtad == 0){
            return 0;
        }
        if($puntos_lealtad > 0){

            $sql = '
                INSERT INTO usuario_puntoslealtad (usuario_id, puntos_lealtad, nivel_lealtad) VALUES
(?, '.$puntos_lealtad.' ,0) ON DUPLICATE KEY UPDATE puntos_lealtad = puntos_lealtad + '.$puntos_lealtad.' ;
                ';

            $sqlQuery = new SqlQuery($sql);

            $sqlQuery->set($UsuarioId);
            return $this->executeInsert($sqlQuery);

        }else{
            $puntos_lealtad= -$puntos_lealtad;

            $sql = '
            UPDATE usuario_puntoslealtad
SET puntos_lealtad  = CASE
                          WHEN puntos_aexpirar > 0 AND puntos_aexpirar - '.$puntos_lealtad.' < 0 THEN
                              puntos_lealtad - '.$puntos_lealtad.' + puntos_aexpirar

                          WHEN puntos_aexpirar > 0 AND puntos_aexpirar - '.$puntos_lealtad.' > 0 THEN puntos_lealtad
                          ELSE puntos_lealtad - '.$puntos_lealtad.' END
  , puntos_aexpirar = CASE
                          WHEN puntos_aexpirar > 0 AND puntos_aexpirar - '.$puntos_lealtad.' < 0 THEN 0
                          WHEN puntos_aexpirar > 0 AND puntos_aexpirar - '.$puntos_lealtad.' > 0 THEN puntos_aexpirar - '.$puntos_lealtad.'
                          ELSE 0 END


WHERE usuario_id = '.$UsuarioId.' AND puntos_lealtad + puntos_aexpirar - '.$puntos_lealtad.' >= 0;
            
            ';

            $sqlQuery = new SqlQuery($sql);

            //$sqlQuery->set($UsuarioId);
            return $this->executeUpdate($sqlQuery);

        }


        return false;
    }

    /**
     * Actualiza los puntos redimidos del balance de un usuario en la base de datos.
     *
     * @param int $UsuarioId El ID del usuario.
     * @param string $creditos (Opcional) Créditos del usuario. No se utiliza en la función actual.
     * @param string $puntos_redimidos (Opcional) Puntos redimidos a agregar al balance del usuario.
     * @param bool $withValidation (Opcional) Indica si se debe realizar validación. No se utiliza en la función actual.
     * @return int|bool Retorna el número de filas afectadas si se actualiza correctamente, 0 si no se proporcionan puntos redimidos, o false en caso de error.
     */
    public function updateBalancePointsRedeemed($UsuarioId,$creditos="",$puntos_redimidos="",$withValidation=false)
    {

        if($puntos_redimidos != ""){

            $sql = 'UPDATE usuario_puntoslealtad
SET puntos_redimidos = puntos_redimidos + '.$puntos_redimidos.'
WHERE usuario_id = '.$UsuarioId.' ;          
            ';

            $sqlQuery = new SqlQuery($sql);

            //$sqlQuery->set($UsuarioId);
            return $this->executeUpdate($sqlQuery);
        }else{
            return 0;
        }

        return false;
    }

    /**
     * Eliminar todas los registros de la base de datos
     *
     * @param no
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function clean()
    {
        $sql = 'DELETE FROM usuario';
        $sqlQuery = new SqlQuery($sql);
        return $this->executeUpdate($sqlQuery);
    }


    /**
     * Obtener todos los registros donde se encuentre que
     * la columna login sea igual al valor pasado como parámetro
     *
     * @param String $value login requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByLogin($value, $plataforma = '0', $mandante = -1, $paisId='')
    {
        $condPaisId='';
        $Helpers = new Helpers();
        //$loginField = $Helpers->set_custom_secret_key('SECRET_PASSPHRASE_LOGIN', true)->set_custom_field('usuario.login');
        $value=strtolower($value);
        $value = $Helpers->encode_data_with_key($value, $_ENV['SECRET_PASSPHRASE_LOGIN']);

        if($paisId != ''){
            $condPaisId = " and usuario.pais_id= '".$paisId."' ";
        }

        // and mandante='" . $mandante . "
        if($mandante==-1 || $mandante == ''){
            if($mandante==-1){
                $sql = "SELECT * FROM usuario INNER JOIN usuario_perfil ON (usuario.usuario_id=usuario_perfil.usuario_id) WHERE usuario.login = ? and usuario.plataforma='" . $plataforma . "' and usuario.eliminado='N' and usuario_perfil.global='S' ".$condPaisId;
            }else{
                $sql = "SELECT * FROM usuario WHERE usuario.login = ? and plataforma='" . $plataforma . "' and eliminado='N' ".$condPaisId;
            }

        }else{
            $sql = "SELECT * FROM usuario WHERE usuario.login = ? and plataforma='" . $plataforma . "'  and mandante='" . $mandante . "' and eliminado='N'".$condPaisId;

        }

        if($_REQUEST['isDebug'] =='1'){
            print_r($_ENV);
            print_r($value);
            print_r($sql);
        }
        //print_r($sql);
        //$sqlQuery = new SqlQuery("SELECT @@SESSION.block_encryption_mode;");
        //print_r($this->execute2($sqlQuery));

        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna clave sea igual al valor pasado como parámetro
     *
     * @param String $value clave requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByClave($value)
    {
        $sql = 'SELECT * FROM usuario WHERE clave = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna nombre sea igual al valor pasado como parámetro
     *
     * @param String $value nombre requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByNombre($value)
    {
        $Helpers = new Helpers();
        $field = 'usuario.nombre';
        $field2 = $Helpers->set_custom_field($field);
        $sql = "SELECT * FROM usuario WHERE $field2 = ?";
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna estado sea igual al valor pasado como parámetro
     *
     * @param String $value estado requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByEstado($value)
    {
        $sql = 'SELECT * FROM usuario WHERE estado = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna fecha_ult sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_ult requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByFechaUlt($value)
    {
        $sql = 'SELECT * FROM usuario WHERE fecha_ult = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna clave_tv sea igual al valor pasado como parámetro
     *
     * @param String $value clave_tv requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByClaveTv($value)
    {
        $sql = 'SELECT * FROM usuario WHERE clave_tv = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna estado_ant sea igual al valor pasado como parámetro
     *
     * @param String $value estado_ant requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByEstadoAnt($value)
    {
        $sql = 'SELECT * FROM usuario WHERE estado_ant = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna intentos sea igual al valor pasado como parámetro
     *
     * @param String $value intentos requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByIntentos($value)
    {
        $sql = 'SELECT * FROM usuario WHERE intentos = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna estado_esp sea igual al valor pasado como parámetro
     *
     * @param String $value estado_esp requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByEstadoEsp($value)
    {
        $sql = 'SELECT * FROM usuario WHERE estado_esp = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna observ sea igual al valor pasado como parámetro
     *
     * @param String $value observ requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByObserv($value)
    {
        $sql = 'SELECT * FROM usuario WHERE observ = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna fecha_crea sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_crea requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByFechaCrea($value)
    {
        $sql = 'SELECT * FROM usuario WHERE fecha_crea = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna dir_ip sea igual al valor pasado como parámetro
     *
     * @param String $value dir_ip requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByDirIp($value)
    {
        $sql = 'SELECT * FROM usuario WHERE dir_ip = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna eliminado sea igual al valor pasado como parámetro
     *
     * @param String $value eliminado requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByEliminado($value)
    {
        $sql = 'SELECT * FROM usuario WHERE eliminado = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna mandante sea igual al valor pasado como parámetro
     *
     * @param String $value mandante requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByMandante($value)
    {
        $sql = 'SELECT * FROM usuario WHERE mandante = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna usucrea_id sea igual al valor pasado como parámetro
     *
     * @param String $value usucrea_id requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByUsucreaId($value)
    {
        $sql = 'SELECT * FROM usuario WHERE usucrea_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna usumodif_id sea igual al valor pasado como parámetro
     *
     * @param String $value usumodif_id requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByUsumodifId($value)
    {
        $sql = 'SELECT * FROM usuario WHERE usumodif_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna fecha_modif sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_modif requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByFechaModif($value)
    {
        $sql = 'SELECT * FROM usuario WHERE fecha_modif = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna clave_casino sea igual al valor pasado como parámetro
     *
     * @param String $value clave_casino requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByClaveCasino($value)
    {
        $sql = 'SELECT * FROM usuario WHERE clave_casino = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna token_itainment sea igual al valor pasado como parámetro
     *
     * @param String $value token_itainment requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByTokenItainment($value)
    {
        $sql = 'SELECT * FROM usuario WHERE token_itainment = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna fecha_clave sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_clave requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByFechaClave($value)
    {
        $sql = 'SELECT * FROM usuario WHERE fecha_clave = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna retirado sea igual al valor pasado como parámetro
     *
     * @param String $value retirado requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByRetirado($value)
    {
        $sql = 'SELECT * FROM usuario WHERE retirado = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna fecha_retiro sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_retiro requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByFechaRetiro($value)
    {
        $sql = 'SELECT * FROM usuario WHERE fecha_retiro = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna hora_retiro sea igual al valor pasado como parámetro
     *
     * @param String $value hora_retiro requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByHoraRetiro($value)
    {
        $sql = 'SELECT * FROM usuario WHERE hora_retiro = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna usuretiro_id sea igual al valor pasado como parámetro
     *
     * @param String $value usuretiro_id requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByUsuretiroId($value)
    {
        $sql = 'SELECT * FROM usuario WHERE usuretiro_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna bloqueo_ventas sea igual al valor pasado como parámetro
     *
     * @param String $value bloqueo_ventas requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByBloqueoVentas($value)
    {
        $sql = 'SELECT * FROM usuario WHERE bloqueo_ventas = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna info_equipo sea igual al valor pasado como parámetro
     *
     * @param String $value info_equipo requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByInfoEquipo($value)
    {
        $sql = 'SELECT * FROM usuario WHERE info_equipo = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna estado_jugador sea igual al valor pasado como parámetro
     *
     * @param String $value estado_jugador requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByEstadoJugador($value)
    {
        $sql = 'SELECT * FROM usuario WHERE estado_jugador = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna tokenCasino sea igual al valor pasado como parámetro
     *
     * @param String $value tokenCasino requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByTokenCasino($value)
    {
        $sql = 'SELECT * FROM usuario WHERE tokenCasino = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna sponsor_id sea igual al valor pasado como parámetro
     *
     * @param String $value sponsor_id requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryBySponsorId($value)
    {
        $sql = 'SELECT * FROM usuario WHERE sponsor_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna verif_correo sea igual al valor pasado como parámetro
     *
     * @param String $value verif_correo requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByVerifCorreo($value)
    {
        $sql = 'SELECT * FROM usuario WHERE verif_correo = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna pais_id sea igual al valor pasado como parámetro
     *
     * @param String $value pais_id requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByPaisId($value)
    {
        $sql = 'SELECT * FROM usuario WHERE pais_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna moneda sea igual al valor pasado como parámetro
     *
     * @param String $value moneda requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByMoneda($value)
    {
        $sql = 'SELECT * FROM usuario WHERE moneda = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna idioma sea igual al valor pasado como parámetro
     *
     * @param String $value idioma requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByIdioma($value)
    {
        $sql = 'SELECT * FROM usuario WHERE idioma = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna permite_activareg sea igual al valor pasado como parámetro
     *
     * @param String $value permite_activareg requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByPermiteActivareg($value)
    {
        $sql = 'SELECT * FROM usuario WHERE permite_activareg = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Iniciar sesión
     *
     *
     * @param String $username nombre de usuario
     * @param String $clave contraseña
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryForLogin($usuario, $clave, $plataforma = '0')
    {
        $queryPassword = !(isset($GLOBALS['Sitebuilder']) && $GLOBALS['Sitebuilder'] == true);

        $sqlQuery = new SqlQuery("SET @@SESSION.block_encryption_mode = 'aes-128-ecb';");
        $this->execute2($sqlQuery);

        $sql = "select a.usuario_id,a.timezone,a.token_itainment,a.nombre,b.perfil_id,d.optimizar_parrilla,d.texto_op1,d.texto_op2,h.tipo tipo_perfil,d.url_op2,d.cant_lineas,'S' parlay,a.moneda,a.idioma,i.iso pais,i.utc,a.pais_id,j.descripcion moneda_nom,case when f.pinagent is null then 'N' else f.pinagent end pinagent,
       case when g.ciudad_id is null then 0 else g.ciudad_id end ciudad_id,
       'N'  requiere_cambio,h.tipo tipo_login,
       i.req_cheque,i.req_doc,i.pais_nom,a.permite_activareg
      from usuario a
      inner join usuario_perfil b on (a.mandante=b.mandante and a.usuario_id=b.usuario_id)
      inner join usuario_premiomax d on (a.mandante=d.mandante and a.usuario_id=d.usuario_id)
      left outer join usuario_config f on (a.mandante=f.mandante and a.usuario_id=f.usuario_id)
      left outer join registro g on (a.mandante=g.mandante and a.usuario_id=g.usuario_id)
      inner join perfil h on (b.perfil_id=h.perfil_id)
      inner join pais i on (a.pais_id=i.pais_id)
      inner join moneda j on (a.moneda=j.moneda)
      where a.mandante=" . $usuario->mandante . " and a.usuario_id='" . $usuario->usuarioId . "' " . ($queryPassword ? " and aes_decrypt(a.clave,'" . $this->claveEncrypt . "') = ? " : "") .
            "and a.estado='A' and a.estado_esp='A' and plataforma='" . $plataforma . "'";

        if($_ENV["debugFixed2"] == '1'){
            print_r($sql);
        }

        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($clave);

        $return= $this->execute2($sqlQuery);
        $sqlQuery = new SqlQuery("SET @@SESSION.block_encryption_mode = 'aes-128-cbc';");

        $this->execute2($sqlQuery);
        return $return;
    }


    /**
     * Iniciar sesión
     *
     *
     * @param String $username nombre de usuario
     * @param String $clave contraseña
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryForIPAndPerfil($ip, $perfil,$mandante='')
    {
        $innerJoin = '';
        $whereJoin = '';
        if ($perfil != "") {
            $innerJoin = 'INNER JOIN usuario_perfil ON (usuario.usuario_id = usuario_perfil.usuario_id)';
            $whereJoin = " AND usuario_perfil.perfil_id= '" . $perfil . "'";
        }

        if ($mandante != "") {
            $whereJoin = $whereJoin." AND usuario.mandante= '" . $mandante . "' ";
        }
        $sql = "select usuario.usuario_id,usuario.mandante FROM usuario " . $innerJoin . "
      where usuario.usuario_ip='" . $ip . "' " . $whereJoin;

        if($_ENV["debugFixed2"]){
            print_r($sql);
        }

        $sqlQuery = new SqlQuery($sql);

        return $this->execute2($sqlQuery);
    }

    /**
     * Obtener estádísticas mediante el usuario_id y el mandante
     *
     * @param String $value usuario_id requerido
     * @param String $value mandante requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryForEstadisticas($usuario_id, $mandante)
    {

        $sql = "select round(sum(c.vlr_apuesta)/count(*),0) apuesta_prom,count(*) tot_tickets,sum(case when c.premiado='S' then 1 else 0 end) tot_premiados,sum(c.vlr_apuesta) tot_apostado,sum(case when c.premiado='S' then c.vlr_premio else 0 end) tot_premio,(select sum(x.valor) from usuario_recarga x where x.mandante=" . $mandante . " and x.mandante=a.mandante and x.usuario_id=a.usuario_id) tot_recarga,(select sum(x.vlr_apuesta) from it_ticket_enc x where x.mandante=" . $mandante . " and x.mandante=a.mandante and x.usuario_id=a.usuario_id and x.estado='A') tot_abiertos from usuario a inner join registro b on (a.mandante=b.mandante and a.usuario_id=b.usuario_id) inner join it_ticket_enc c on (a.mandante=c.mandante and a.usuario_id=c.usuario_id) where a.mandante=" . $mandante . " and a.usuario_id=" . $usuario_id;

        $sqlQuery = new SqlQuery($sql);

        return $this->execute2($sqlQuery);
    }

    /**
     * Obtener detalles para el usuario administrador
     *
     * @param String $usuario_id usuario_id requerido
     * @param String $mandante mandante requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryForAdminDetails($usuario_id, $mandante)
    {

        $sql = "select a.usuario_id,a.login,a.nombre,a.fecha_crea,a.retirado,a.fecha_ult,a.fecha_modif,j.nombre usuario_modif,a.dir_ip,a.estado,a.estado_esp,a.intentos,a.observ,c.perfil_id,e.cupo_recarga,e.creditos_base,e.descripcion,e.nombre_contacto,e.ciudad_id,f.depto_id,e.direccion,e.barrio,e.telefono,e.valor_cupo,e.valor_cupo2,e.porcen_comision,e.porcen_comision2,e.periodicidad_id,g.premio_max,g.premio_max1,g.premio_max2,g.premio_max3,g.cant_lineas,g.apuesta_min,g.valor_directo,g.valor_evento,g.valor_diario,g.optimizar_parrilla,g.texto_op1,g.texto_op2,g.url_op2,g.texto_op3,g.url_op3,h.usupadre_id,h.usupadre2_id,e.email,e.clasificador1_id,e.clasificador2_id,e.clasificador3_id,(select count(*)+1 from punto_fijo where mandante=" . $mandante . " and puntoventa_id=" . $usuario_id . ") nodos,case when i.permite_recarga is null or i.permite_recarga='' then 'N' else i.permite_recarga end permite_recarga,case when i.pinagent is null or i.pinagent='' then 'N' else i.pinagent end pinagent,case when i.recibo_caja is null or i.recibo_caja='' then 'N' else i.recibo_caja end recibo_caja,a.bloqueo_ventas,a.permite_activareg,a.pais_id,a.moneda,a.idioma from usuario a inner join usuario_perfil c on (a.mandante=c.mandante and a.usuario_id=c.usuario_id and c.perfil_id<>'USUONLINE') left outer join punto_venta e on (a.mandante=e.mandante and a.usuario_id=e.usuario_id) left outer join ciudad f on (e.ciudad_id=f.ciudad_id) left outer join usuario_premiomax g on (a.mandante=g.mandante and a.usuario_id=g.usuario_id) left outer join concesionario h on (a.mandante=h.mandante and a.usuario_id=h.usuhijo_id) left outer join usuario_config i on (a.mandante=i.mandante and a.usuario_id=i.usuario_id) left outer join usuario j on (a.mandante=j.mandante and a.usumodif_id=j.usuario_id) where a.mandante=" . $mandante . " and a.usuario_id=" . $usuario_id;
        $sqlQuery = new SqlQuery($sql);

        $Helpers = new Helpers();
        $result = $Helpers->process_data($this->execute2($sqlQuery));
        return $result ;
    }

    /**
     * Obtener todos detalles del perfil de un usuario
     *
     * @param String $perfil perfil requerido
     * @param String $pais pais requerido
     * @param String $moneda moneda requerido
     * @param String $mandante mandante requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryForPerfiles($perfil, $pais, $moneda, $mandante)
    {

        $sql = "select a.* from usuario a inner join usuario_perfil b on (a.mandante=b.mandante and a.usuario_id=b.usuario_id) where a.mandante=" . $mandante . " and a.estado='A' and a.eliminado='N' and b.perfil_id ='" . $perfil . "' and b.perfil_id<>'PUNTOFIJO' and b.perfil_id<>'USUONLINE' and a.pais_id=" . $pais . " and a.moneda='" . $moneda . "' order by a.nombre";

        $sqlQuery = new SqlQuery($sql);

        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna concesionario_id sea igual al valor pasado como parámetro
     *
     * @param String $value concesionario_id requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryForSubconcesionarios($concesionario_id)
    {

        $sql = "select a.* from usuario a inner join usuario_perfil b on (a.mandante=b.mandante and a.usuario_id=b.usuario_id) INNER JOIN concesionario c ON (c.usupadre_id = '" . $concesionario_id . "' AND c.usuhijo_id = a.usuario_id ) where a.estado='A' and a.eliminado='N' and b.perfil_id ='SUBCONCESIONARIO' order by a.nombre";

        $sqlQuery = new SqlQuery($sql);

        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los menús para el usuario pasado como parámetro
     *
     * @param String $value usuario requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryMenus($usuario,$mandante=0)
    {

        $sql = "select d.menu_id,d.descripcion menu,b.submenu_id,b.descripcion submenu, b.pagina,a.adicionar,a.editar,a.eliminar
from perfil_submenu a
 inner join submenu b on (a.submenu_id=b.submenu_id)
 inner join usuario_perfil c on (a.perfil_id=c.perfil_id)
 inner join menu d on (b.menu_id=d.menu_id)
where c.usuario_id=" . $usuario . " and a.mandante=" . $mandante . "
order by d.orden,b.orden,b.descripcion";

        $sqlQuery = new SqlQuery($sql);

        return $this->execute2($sqlQuery);
    }


    /**
     * Realizar una consulta en la tabla de Usuario 'Usuario'
     * de una manera personalizada
     *
     * @param String $select campos de consulta
     * @param String $sidx columna para ordenar
     * @param String $sord orden los datos asc | desc
     * @param String $start inicio de la consulta
     * @param String $limit limite de la consulta
     * @param String $filters condiciones de la consulta
     * @param boolean $searchOn utilizar los filtros o no
     *
     * @return Array $json resultado de la consulta
     *
     */
    public function queryUsuarios2($usuarioId, $mandante, $miperfil, $estado, $perfil_id, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {
        $Helpers = new Helpers();
        $where = " where 1=1 ";

        if ($searchOn) {
            // Construye el where
            $filters = json_decode($filters);
            $whereArray = array();
            $rules = $filters->rules;
            $groupOperation = $filters->groupOp;
            $cont = 0;

            foreach ($rules as $rule) {
                $fieldName = $Helpers->set_custom_field($rule->field);
                $fieldData = $rule->data;

                $Helpers = new Helpers();

                if($fieldName == 'registro.cedula'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_DOCUMENT']);
                }
                if($fieldName == 'registro.celular'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_PHONE']);
                }
                if($fieldName == 'usuario.cedula'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_DOCUMENT']);
                }
                if($fieldName == 'usuario.login'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }
                if($fieldName == 'usuario_mandante.email'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }
                if($fieldName == 'punto_venta.email'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }
                if($fieldName == 'usuario_sitebuilder.login'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }
                switch ($rule->op) {
                    case "eq":
                        $fieldOperation = " = '" . $fieldData . "'";
                        break;
                    case "ne":
                        $fieldOperation = " != '" . $fieldData . "'";
                        break;
                    case "lt":
                        $fieldOperation = " < '" . $fieldData . "'";
                        break;
                    case "gt":
                        $fieldOperation = " > '" . $fieldData . "'";
                        break;
                    case "le":
                        $fieldOperation = " <= '" . $fieldData . "'";
                        break;
                    case "ge":
                        $fieldOperation = " >= '" . $fieldData . "'";
                        break;
                    case "nu":
                        $fieldOperation = " = ''";
                        break;
                    case "nn":
                        $fieldOperation = " != ''";
                        break;
                    case "in":
                        $fieldOperation = " IN (" . $fieldData . ")";
                        break;
                    case "ni":
                        $fieldOperation = " NOT IN '" . $fieldData . "'";
                        break;
                    case "bw":
                        $fieldOperation = " LIKE '" . $fieldData . "%'";
                        break;
                    case "bn":
                        $fieldOperation = " NOT LIKE '" . $fieldData . "%'";
                        break;
                    case "ew":
                        $fieldOperation = " LIKE '%" . $fieldData . "'";
                        break;
                    case "en":
                        $fieldOperation = " NOT LIKE '%" . $fieldData . "'";
                        break;
                    case "cn":
                        $fieldOperation = " LIKE '%" . $fieldData . "%'";
                        break;
                    case "nc":
                        $fieldOperation = " NOT LIKE '%" . $fieldData . "%'";
                        break;
                    default:
                        $fieldOperation = "";
                        break;
                }
                if ($fieldOperation != "") {
                    $whereArray[] = $fieldName . $fieldOperation;
                }

                if (oldCount($whereArray) > 0) {
                    $where = $where . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
                } else {
                    $where = "";
                }
            }

        }

        //Verifica el filtro del tipo de usuario
        $strTipoUsu = " ";

        //Verifica si debe filtrar por algun perfil en especial
        $strPerfil = "";
        if (strlen($perfil_id) > 0) {
            $strPerfil = " and e.perfil_id='" . $perfil_id . "' ";
        }

        if ($estado == "") {
            $estado = "A";
        }
        //Verifica el estado
        $strEstado = " and a.estado='" . $estado . "' ";
        if ($estado == "R") {
            $strEstado = " and a.retirado='S' ";

        }

        if ($miperfil == "SA") {
            $sql = "select count(*) AS count from usuario a INNER JOIN registro ON (registro.usuario_id =a.usuario_id) left outer join usuario_perfil e on (a.mandante=e.mandante and a.usuario_id=e.usuario_id) left outer join punto_venta f on (a.mandante=f.mandante and a.usuario_id=f.usuario_id) left outer join ciudad g on (f.ciudad_id=g.ciudad_id) inner join perfil h on (e.perfil_id=h.perfil_id) left outer join concesionario i on (a.mandante=i.mandante and a.usuario_id=i.usuhijo_id) left outer join usuario j on (i.mandante=j.mandante and i.usupadre_id=j.usuario_id) left outer join usuario k on (i.mandante=k.mandante and i.usupadre2_id=k.usuario_id)" . $where . $strTipoUsu . $strPerfil . $strEstado . " and a.eliminado='N' and a.mandante=" . $mandante;
        } else {
            if ($miperfil == "ADMIN") {
                $sql = "select count(*) AS count from usuario k on (i.mandante=k.mandante and i.usupadre2_id=k.usuario_id)" . $where . $strTipoUsu . $strPerfil . $strEstado . " and e.perfil_id<>'SA' and e.perfil_id<>'ADMIN' and a.eliminado='N' and a.mandante=" . $mandante;
            } else {
                $sql = "select count(*) AS count from  usuario a left outer join usuario_perfil e on (a.mandante=e.mandante and a.usuario_id=e.usuario_id) left outer join punto_venta f on (a.mandante=f.mandante and a.usuario_id=f.usuario_id) left outer join ciudad g on (f.ciudad_id=g.ciudad_id) inner join perfil h on (e.perfil_id=h.perfil_id) left outer join concesionario i on (a.mandante=i.mandante and a.usuario_id=i.usuhijo_id) left outer join usuario j on (i.mandante=j.mandante and i.usupadre_id=j.usuario_id) left outer join usuario k on (i.mandante=k.mandante and i.usupadre2_id=k.usuario_id)" . $where . $strTipoUsu . $strPerfil . $strEstado . " and a.eliminado='N' and h.tipo='C' and a.mandante=" . $mandante;
            }

        }

        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);

        if ($miperfil == "SA") {
            $sql = "SELECT a.usuario_id,a.login,a.dir_ip,a.estado,a.estado_esp,case when uc.permite_recarga is null or uc.permite_recarga='' then 'N' else uc.permite_recarga end permite_recarga,case when uc.recibo_caja is null or uc.recibo_caja='' then 'N' else uc.recibo_caja end recibo_caja,a.pais_id,a.idioma,a.nombre,e.perfil_id,a.intentos,a.observ,case when uc.pinagent is null or uc.pinagent='' then 'N' else uc.pinagent end pinagent,a.bloqueo_ventas,a.moneda,a.permite_activareg,a.fecha_crea,CASE when a.fecha_ult is null then '' else a.fecha_ult end fecha_ult,f.nombre_contacto,f.telefono,g.ciudad_nom,CASE when f.email is null then '' else f.email end email,CASE when j.nombre is null then '' else j.nombre end concesionario,CASE when k.nombre is null then '' else k.nombre end subconcesionario,registro.direccion,registro.email,registro.ciudnacim_id,a.moneda,a.fecha_crea,registro.nombre1,registro.apellido1,registro.nombre2,registro.cedula,registro.sexo,a.idioma,registro.celular,registro.telefono,registro.nacionalidad_id,registro.codigo_postal,usuario_otrainfo.fecha_nacim FROM usuario a   INNER JOIN registro ON (registro.usuario_id =a.usuario_id) INNER JOIN usuario_otrainfo ON (usuario_otrainfo.usuario_id=a.usuario_id) left outer join usuario_perfil e on (a.mandante=e.mandante and a.usuario_id=e.usuario_id) left outer join punto_venta f on (a.mandante=f.mandante and a.usuario_id=f.usuario_id) left outer join ciudad g on (f.ciudad_id=g.ciudad_id) inner join perfil h on (e.perfil_id=h.perfil_id) left outer join concesionario i on (a.mandante=i.mandante and a.usuario_id=i.usuhijo_id) left outer join usuario j on (i.mandante=j.mandante and i.usupadre_id=j.usuario_id) left outer join usuario k on (i.mandante=k.mandante and i.usupadre2_id=k.usuario_id) left outer join usuario_config uc on (a.mandante=uc.mandante and a.usuario_id=uc.usuario_id) " . $where . $strTipoUsu . $strPerfil . $strEstado . " and a.eliminado='N' and a.mandante=" . $mandante . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;
        } else {
            if ($miperfil == "ADMIN") {
                $sql = "select a.usuario_id,a.login,a.nombre,a.fecha_crea,case when a.fecha_ult is null then '' else a.fecha_ult end fecha_ult,f.nombre_contacto,f.telefono,g.ciudad_nom,case when f.email is null then '' else f.email end email,case when j.nombre is null then '' else j.nombre end concesionario,case when k.nombre is null then '' else k.nombre end subconcesionario from usuario a left outer join usuario_perfil e on (a.mandante=e.mandante and a.usuario_id=e.usuario_id) left outer join punto_venta f on (a.mandante=f.mandante and a.usuario_id=f.usuario_id) left outer join ciudad g on (f.ciudad_id=g.ciudad_id) inner join perfil h on (e.perfil_id=h.perfil_id) left outer join concesionario i on (a.mandante=i.mandante and a.usuario_id=i.usuhijo_id) left outer join usuario j on (i.mandante=j.mandante and i.usupadre_id=j.usuario_id) left outer join usuario k on (i.mandante=k.mandante and i.usupadre2_id=k.usuario_id)" . $where . $strTipoUsu . $strPerfil . $strEstado . " and e.perfil_id<>'SA' and e.perfil_id<>'ADMIN' and a.eliminado='N' and a.mandante=" . $mandante . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;
            } else {
                $sql = "select a.usuario_id,a.login,a.nombre,a.fecha_crea,case when a.fecha_ult is null then '' else a.fecha_ult end fecha_ult,f.nombre_contacto,f.telefono,g.ciudad_nom,case when f.email is null then '' else f.email end email,case when j.nombre is null then '' else j.nombre end concesionario,case when k.nombre is null then '' else k.nombre end subconcesionario from usuario a left outer join usuario_perfil e on (a.mandante=e.mandante and a.usuario_id=e.usuario_id) left outer join punto_venta f on (a.mandante=f.mandante and a.usuario_id=f.usuario_id) left outer join ciudad g on (f.ciudad_id=g.ciudad_id) inner join perfil h on (e.perfil_id=h.perfil_id) left outer join concesionario i on (a.mandante=i.mandante and a.usuario_id=i.usuhijo_id) left outer join usuario j on (i.mandante=j.mandante and i.usupadre_id=j.usuario_id) left outer join usuario k on (i.mandante=k.mandante and i.usupadre2_id=k.usuario_id)" . $where . $strTipoUsu . $strPerfil . $strEstado . " and a.eliminado='N' and h.tipo='C' and a.mandante=" . $mandante . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;
            }

        }

        $sqlQuery = new SqlQuery($sql);

        $result = $Helpers->process_data($this->execute2($sqlQuery));

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }


    /**
     * Realizar una consulta en la tabla de Usuario 'Usuario'
     * de una manera personalizada
     *
     * @param String $select campos de consulta
     * @param String $sidx columna para ordenar
     * @param String $sord orden los datos asc | desc
     * @param String $start inicio de la consulta
     * @param String $limit limite de la consulta
     * @param String $filters condiciones de la consulta
     * @param boolean $searchOn utilizar los filtros o no
     *
     * @return Array $json resultado de la consulta
     *
     */
    public function queryUsuarios($usuarioId, $mandante, $miperfil, $estado, $perfil_id, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $Helpers = new Helpers();
        $where = " where 1=1 ";

        if ($searchOn) {
            // Construye el where
            $filters = json_decode($filters);
            $whereArray = array();
            $rules = $filters->rules;
            $groupOperation = $filters->groupOp;
            $cont = 0;

            foreach ($rules as $rule) {
                $fieldName = $Helpers->set_custom_field($rule->field);
                $fieldData = $rule->data;


                $Helpers = new Helpers();

                if($fieldName == 'registro.cedula'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_DOCUMENT']);
                }
                if($fieldName == 'registro.celular'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_PHONE']);
                }
                if($fieldName == 'usuario.cedula'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_DOCUMENT']);
                }
                if($fieldName == 'usuario.login'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }
                if($fieldName == 'usuario_mandante.email'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }
                if($fieldName == 'punto_venta.email'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }
                if($fieldName == 'usuario_sitebuilder.login'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }
                switch ($rule->op) {
                    case "eq":
                        $fieldOperation = " = '" . $fieldData . "'";
                        break;
                    case "ne":
                        $fieldOperation = " != '" . $fieldData . "'";
                        break;
                    case "lt":
                        $fieldOperation = " < '" . $fieldData . "'";
                        break;
                    case "gt":
                        $fieldOperation = " > '" . $fieldData . "'";
                        break;
                    case "le":
                        $fieldOperation = " <= '" . $fieldData . "'";
                        break;
                    case "ge":
                        $fieldOperation = " >= '" . $fieldData . "'";
                        break;
                    case "nu":
                        $fieldOperation = " = ''";
                        break;
                    case "nn":
                        $fieldOperation = " != ''";
                        break;
                    case "in":
                        $fieldOperation = " IN (" . $fieldData . ")";
                        break;
                    case "ni":
                        $fieldOperation = " NOT IN '" . $fieldData . "'";
                        break;
                    case "bw":
                        $fieldOperation = " LIKE '" . $fieldData . "%'";
                        break;
                    case "bn":
                        $fieldOperation = " NOT LIKE '" . $fieldData . "%'";
                        break;
                    case "ew":
                        $fieldOperation = " LIKE '%" . $fieldData . "'";
                        break;
                    case "en":
                        $fieldOperation = " NOT LIKE '%" . $fieldData . "'";
                        break;
                    case "cn":
                        $fieldOperation = " LIKE '%" . $fieldData . "%'";
                        break;
                    case "nc":
                        $fieldOperation = " NOT LIKE '%" . $fieldData . "%'";
                        break;
                    default:
                        $fieldOperation = "";
                        break;
                }
                if ($fieldOperation != "") {
                    $whereArray[] = $fieldName . $fieldOperation;
                }

                if (oldCount($whereArray) > 0) {
                    $where = $where . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
                } else {
                    $where = "";
                }
            }

        }

        $sql = "select count(*) count from usuario a inner join registro b on (a.mandante=b.mandante and a.usuario_id=b.usuario_id) left outer join usuario_otrainfo c on (a.mandante=c.mandante and a.usuario_id=c.usuario_id) left outer join ciudad d on (b.ciudad_id=d.ciudad_id) left outer join ciudad e on (b.ciudexped_id=e.ciudad_id) inner join usuario_premiomax f on (a.mandante=f.mandante and a.usuario_id=f.usuario_id) left outer join ciudad g on (b.ciudnacim_id=g.ciudad_id) left outer join usuario h on (a.mandante=h.mandante and a.sponsor_id=h.usuario_id) " . $where . " " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);

        $sql = "select a.usuario_id,b.cedula,b.fecha_exped,b.nacionalidad_id,a.nombre,b.nombre1,b.nombre2,b.apellido1,b.apellido2,b.sexo,b.tipo_doc,b.ciudexped_id,b.ciudnacim_id,b.telefono,b.email,a.estado,b.ciudad,b.celular,c.fecha_nacim,b.direccion,c.banco_id,c.tipo_cuenta,c.num_cuenta,case when c.anexo_doc is null then 'N' else c.anexo_doc end anexo_doc,d.depto_id,d.ciudad_id,f.premio_max,f.premio_max1,f.premio_max2,f.premio_max3,f.cant_lineas,f.apuesta_min,f.valor_directo,f.valor_evento,f.valor_diario,e.depto_id deptoexped_id,g.depto_id deptonacim_id,b.codigo_postal,concat(h.nombre,' (',h.usuario_id,')') referido_por,b.paisnacim_id,b.ocupacion,b.rangoingreso_id,b.origen_fondos,a.pais_id,a.moneda from usuario a inner join registro b on (a.mandante=b.mandante and a.usuario_id=b.usuario_id) left outer join usuario_otrainfo c on (a.mandante=c.mandante and a.usuario_id=c.usuario_id) left outer join ciudad d on (b.ciudad_id=d.ciudad_id) left outer join ciudad e on (b.ciudexped_id=e.ciudad_id) inner join usuario_premiomax f on (a.mandante=f.mandante and a.usuario_id=f.usuario_id) left outer join ciudad g on (b.ciudnacim_id=g.ciudad_id) left outer join usuario h on (a.mandante=h.mandante and a.sponsor_id=h.usuario_id) " . $where . " " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;


        $sqlQuery = new SqlQuery($sql);

        $result = $Helpers->process_data($this->execute2($sqlQuery));

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }

    /**
     * Realizar una consulta en la tabla de Usuario 'Usuario'
     * de una manera personalizada
     *
     * @param String $select campos de consulta
     * @param String $sidx columna para ordenar
     * @param String $sord orden los datos asc | desc
     * @param String $start inicio de la consulta
     * @param String $limit limite de la consulta
     * @param String $filters condiciones de la consulta
     * @param boolean $searchOn utilizar los filtros o no
     *
     * @return Array $json resultado de la consulta
     *
     */
    public function queryUsuariosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping = "")
    {

        $Helpers = new Helpers();
        $where = " where 1=1 ";
        $innconcesionario=true;

        if ($searchOn) {
            // Construye el where
            $filters = json_decode($filters);
            $whereArray = array();
            $rules = $filters->rules;
            $groupOperation = $filters->groupOp;
            $cont = 0;
            $innUsuarioConfiguracionPEP=false;

            foreach ($rules as $rule) {
                $fieldName = (string)$Helpers->set_custom_field($rule->field);
                $fieldData = $rule->data;

                if($fieldName =='c.persona_expuesta'){
                    $fieldName='ucpep.valor';
                    $innUsuarioConfiguracionPEP=true;
                }

                if($fieldName =='c.fecha_aceptacion'){
                    $fieldName='ucpep.fecha_crea';
                    $innUsuarioConfiguracionPEP=true;
                }

                $cond="concesionario";
                if ( strpos($fieldName, $cond) !== false || strpos($sidx, $cond) !== false || strpos($grouping, $cond) !== false  || strpos($grouping, $cond) !== false)
                {
                    $innconcesionario=true;
                }
                $cond="concesionario_punto";
                if ( strpos($fieldName, $cond) !== false || strpos($sidx, $cond) !== false || strpos($grouping, $cond) !== false  || strpos($grouping, $cond) !== false)
                {
                    $innconcesionario=true;
                }

                $Helpers = new Helpers();

                if($fieldName == 'registro.cedula'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_DOCUMENT']);
                }
                if($fieldName == 'registro.celular'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_PHONE']);
                }
                if($fieldName == 'usuario.cedula'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_DOCUMENT']);
                }
                if($fieldName == 'usuario.login'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }
                if($fieldName == 'usuario_mandante.email'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }
                if($fieldName == 'punto_venta.email'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }
                if($fieldName == 'usuario_sitebuilder.login'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }

                switch ($rule->op) {
                    case "eq":
                        $fieldOperation = " = '" . $fieldData . "'";
                        break;
                    case "ne":
                        $fieldOperation = " != '" . $fieldData . "'";
                        break;
                    case "lt":
                        $fieldOperation = " < '" . $fieldData . "'";
                        break;
                    case "gt":
                        $fieldOperation = " > '" . $fieldData . "'";
                        break;
                    case "le":
                        $fieldOperation = " <= '" . $fieldData . "'";
                        break;
                    case "ge":
                        $fieldOperation = " >= '" . $fieldData . "'";
                        break;
                    case "nu":
                        $fieldOperation = " = ''";
                        break;
                    case "nn":
                        $fieldOperation = " != ''";
                        break;
                    case "in":
                        $fieldOperation = " IN (" . $fieldData . ")";
                        break;
                    case "ni":
                        $fieldOperation = " NOT IN '" . $fieldData . "'";
                        break;
                    case "bw":
                        $fieldOperation = " LIKE '" . $fieldData . "%'";
                        break;
                    case "bn":
                        $fieldOperation = " NOT LIKE '" . $fieldData . "%'";
                        break;
                    case "ew":
                        $fieldOperation = " LIKE '%" . $fieldData . "'";
                        break;
                    case "en":
                        $fieldOperation = " NOT LIKE '%" . $fieldData . "'";
                        break;
                    case "cn":
                        $fieldOperation = " LIKE '%" . $fieldData . "%'";
                        break;
                    case "nc":
                        $fieldOperation = " NOT LIKE '%" . $fieldData . "%'";
                        break;
                    default:
                        $fieldOperation = "";
                        break;
                }
                if ($fieldOperation != "") {
                    $whereArray[] = $fieldName . $fieldOperation;
                }

                if (oldCount($whereArray) > 0) {
                    $where = $where . " " . $groupOperation . " ((" . $fieldName . ")) " . strtoupper($fieldOperation);
                } else {
                    $where = "";
                }
            }

        }

        $innconcesionarioStr="";
        if($innconcesionario){
            $innconcesionarioStr=" LEFT OUTER JOIN concesionario  ON (concesionario.usuhijo_id = registro.afiliador_id  and concesionario.prodinterno_id=0  AND concesionario.estado='A') 
LEFT OUTER JOIN concesionario concesionario_punto  ON (concesionario_punto.usuhijo_id = usuario.usuario_id  and concesionario_punto.prodinterno_id=0) ";
        }

        if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null ){
            $connOriginal = $_ENV["connectionGlobal"]->getConnection();




            $connDB5 = null;


            if($_ENV['ENV_TYPE'] =='prod') {

                $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()
                    , array(
                        PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/ca-bundle.crt',
                        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
                    )
                );
            }else{

                $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()

                );
            }

            $connDB5->exec("set names utf8");
            $connDB5->exec("set use_secondary_engine=off");

            try{

                if($_ENV["TIMEZONE"] !=null && $_ENV["TIMEZONE"] !='') {
                    $connDB5->exec('SET time_zone = "'.$_ENV["TIMEZONE"].'";');
                }

                if($_ENV["NEEDINSOLATIONLEVEL"] != null && $_ENV["NEEDINSOLATIONLEVEL"] == '1') {
                    $connDB5->exec('SET TRANSACTION ISOLATION LEVEL READ COMMITTED;');
                }
                if($_ENV["ENABLEDSETLOCKWAITTIMEOUT"] != null && $_ENV["ENABLEDSETLOCKWAITTIMEOUT"] == '1') {
                    // $connDB5->exec('SET SESSION innodb_lock_wait_timeout = 5;');
                }
                if($_ENV["ENABLEDSETMAX_EXECUTION_TIME"] != null && $_ENV["ENABLEDSETMAX_EXECUTION_TIME"] == '1') {
                    // $connDB5->exec('SET SESSION MAX_EXECUTION_TIME = 120000;');
                }
                if($_ENV["DBNEEDUTF8"] != null && $_ENV["DBNEEDUTF8"] == '1') {
                    $connDB5->exec("SET NAMES utf8mb4");
                }
            }catch (\Exception $e){

            }
            $_ENV["connectionGlobal"]->setConnection($connDB5);
            $this->transaction->setConnection($_ENV["connectionGlobal"]);

        }

        $innUsuarioConfiguracionStr="";
        if($innUsuarioConfiguracionPEP){
            $innUsuarioConfiguracionStr=" 
            INNER JOIN clasificador  clasificadorpep
                ON (clasificadorpep.abreviado='PEP') 
            LEFT OUTER JOIN usuario_configuracion  ucpep
                ON (ucpep.usuario_id = usuario.usuario_id  and ucpep.estado='A'  AND clasificadorpep.clasificador_id=ucpep.tipo) 

";
        }


        $sqlQuery2 = new SqlQuery("SET @@SESSION.block_encryption_mode = 'aes-128-cbc'");
        $this->execute2($sqlQuery2);

        $sql = "SELECT /*+ MAX_EXECUTION_TIME(50000) */  count(*) count FROM usuario force index (usuario_pais_id_index) INNER JOIN pais ON (usuario.pais_id=pais.pais_id) 
LEFT OUTER JOIN usuario_mandante ON (usuario_mandante.usuario_mandante=usuario.usuario_id and usuario_mandante.mandante = usuario.mandante )  
INNER JOIN usuario_perfil   ON (usuario_perfil.usuario_id=usuario.usuario_id)  
INNER JOIN perfil ON (usuario_perfil.perfil_id=perfil.perfil_id )  
LEFT OUTER JOIN registro ON (registro.usuario_id=usuario.usuario_id)  
LEFT OUTER JOIN data_completa2 ON (usuario_mandante.usumandante_id = data_completa2.usuario_id)
left outer join usuario_otrainfo c on (usuario.mandante=c.mandante and usuario.usuario_id=c.usuario_id) 
left outer join ciudad d on (registro.ciudad_id=d.ciudad_id) left outer join ciudad e on (registro.ciudexped_id=e.ciudad_id) 
LEFT OUTER JOIN usuario_premiomax f on (usuario.mandante=f.mandante and usuario.usuario_id=f.usuario_id) 
left outer join ciudad g on (registro.ciudnacim_id=g.ciudad_id) left outer join usuario h on (usuario.mandante=h.mandante and usuario.sponsor_id=h.usuario_id) 
 ".$innconcesionarioStr .$innUsuarioConfiguracionStr . $where;


        if ($grouping != "") {
            $where = $where . " GROUP BY " . $grouping;
        }
        if($_ENV["debugFixed2"]){
            print_r($sql);
            exit();
        }

        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
        $sql = "SELECT /*+ MAX_EXECUTION_TIME(50000) */  " . $select . " FROM usuario force index (usuario_pais_id_index) INNER JOIN pais ON (usuario.pais_id=pais.pais_id) 
        LEFT OUTER JOIN usuario_mandante ON (usuario_mandante.usuario_mandante=usuario.usuario_id and usuario_mandante.mandante = usuario.mandante )    
        INNER JOIN usuario_perfil   ON (usuario_perfil.usuario_id=usuario.usuario_id) 
        INNER JOIN perfil ON (usuario_perfil.perfil_id=perfil.perfil_id )  
        LEFT OUTER JOIN data_completa2 ON (usuario_mandante.usumandante_id = data_completa2.usuario_id)
        LEFT OUTER JOIN registro ON (registro.usuario_id=usuario.usuario_id) 
        LEFT OUTER JOIN usuario_puntoslealtad ON (usuario_puntoslealtad.usuario_id=usuario.usuario_id) 
        left outer join usuario_otrainfo c on (usuario.mandante=c.mandante and usuario.usuario_id=c.usuario_id) 
        left outer join ciudad d on (registro.ciudad_id=d.ciudad_id) left outer join ciudad e on (registro.ciudexped_id=e.ciudad_id) 
        LEFT OUTER JOIN usuario_premiomax f on (usuario.mandante=f.mandante and usuario.usuario_id=f.usuario_id) 
        left outer join ciudad g on (registro.ciudnacim_id=g.ciudad_id) 
        left outer join usuario h on (usuario.mandante=h.mandante and usuario.sponsor_id=h.usuario_id) ".$innconcesionarioStr ."  ".$innUsuarioConfiguracionStr ." 
        LEFT OUTER JOIN punto_venta  ON (punto_venta.usuario_id = usuario.puntoventa_id  )  " . $where . " " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery2 = new SqlQuery("SET @@SESSION.block_encryption_mode = 'aes-128-cbc'");
        $this->execute2($sqlQuery2);
        $sqlQuery = new SqlQuery($sql);

        $result = $Helpers->process_data($this->execute2($sqlQuery));
        //$result = $this->execute2($sqlQuery);

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null ) {
            $connDB5 = null;
            $_ENV["connectionGlobal"]->setConnection($connOriginal);
            $this->transaction->setConnection($_ENV["connectionGlobal"]);
        }

        if($_ENV["debugFixed2"]=='1'){
            print_r($sql);
            exit();
        }



        return $json;
    }

    public function queryUsuariosCustom2($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping = "")
    {

        $Helpers = new Helpers();
        $where = " where 1=1 ";

        $innconcesionario=true;

        if ($searchOn) {
            // Construye el where
            $filters = json_decode($filters);
            $whereArray = array();
            $rules = $filters->rules;
            $groupOperation = $filters->groupOp;
            $cont = 0;

            foreach ($rules as $rule) {
                $fieldName = $Helpers->set_custom_field($rule->field);
                $fieldData = $rule->data;

                $cond="concesionario";
                if ( strpos($fieldName, $cond) !== false || strpos($sidx, $cond) !== false || strpos($grouping, $cond) !== false  || strpos($grouping, $cond) !== false)
                {
                    $innconcesionario=true;
                }
                $cond="concesionario_punto";
                if ( strpos($fieldName, $cond) !== false || strpos($sidx, $cond) !== false || strpos($grouping, $cond) !== false  || strpos($grouping, $cond) !== false)
                {
                    $innconcesionario=true;
                }
                $Helpers = new Helpers();

                if($fieldName == 'registro.cedula'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_DOCUMENT']);
                }
                if($fieldName == 'registro.celular'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_PHONE']);
                }
                if($fieldName == 'usuario.cedula'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_DOCUMENT']);
                }
                if($fieldName == 'usuario.login'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }
                if($fieldName == 'usuario_mandante.email'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }
                if($fieldName == 'punto_venta.email'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }
                if($fieldName == 'usuario_sitebuilder.login'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }
                switch ($rule->op) {
                    case "eq":
                        $fieldOperation = " = '" . $fieldData . "'";
                        break;
                    case "ne":
                        $fieldOperation = " != '" . $fieldData . "'";
                        break;
                    case "lt":
                        $fieldOperation = " < '" . $fieldData . "'";
                        break;
                    case "gt":
                        $fieldOperation = " > '" . $fieldData . "'";
                        break;
                    case "le":
                        $fieldOperation = " <= '" . $fieldData . "'";
                        break;
                    case "ge":
                        $fieldOperation = " >= '" . $fieldData . "'";
                        break;
                    case "nu":
                        $fieldOperation = " = ''";
                        break;
                    case "nn":
                        $fieldOperation = " != ''";
                        break;
                    case "in":
                        $fieldOperation = " IN (" . $fieldData . ")";
                        break;
                    case "ni":
                        $fieldOperation = " NOT IN '" . $fieldData . "'";
                        break;
                    case "bw":
                        $fieldOperation = " LIKE '" . $fieldData . "%'";
                        break;
                    case "bn":
                        $fieldOperation = " NOT LIKE '" . $fieldData . "%'";
                        break;
                    case "ew":
                        $fieldOperation = " LIKE '%" . $fieldData . "'";
                        break;
                    case "en":
                        $fieldOperation = " NOT LIKE '%" . $fieldData . "'";
                        break;
                    case "cn":
                        $fieldOperation = " LIKE '%" . $fieldData . "%'";
                        break;
                    case "nc":
                        $fieldOperation = " NOT LIKE '%" . $fieldData . "%'";
                        break;
                    default:
                        $fieldOperation = "";
                        break;
                }
                if ($fieldOperation != "") {
                    $whereArray[] = $fieldName . $fieldOperation;
                }

                if (oldCount($whereArray) > 0) {
                    $where = $where . " " . $groupOperation . " ((" . $fieldName . ")) " . strtoupper($fieldOperation);
                } else {
                    $where = "";
                }
            }

        }

        $innconcesionarioStr="";
        if($innconcesionario){
            $innconcesionarioStr=" LEFT OUTER JOIN concesionario  ON (concesionario.usuhijo_id = registro.afiliador_id  and concesionario.prodinterno_id=0  AND concesionario.estado='A') 
LEFT OUTER JOIN concesionario concesionario_punto  ON (concesionario_punto.usuhijo_id = usuario.usuario_id  and concesionario_punto.prodinterno_id=0) ";
        }

        if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null ){
            $connOriginal = $_ENV["connectionGlobal"]->getConnection();




            $connDB5 = null;


            if($_ENV['ENV_TYPE'] =='prod') {

                $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()
                    , array(
                        PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/ca-bundle.crt',
                        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
                    )
                );
            }else{

                $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()

                );
            }

            $connDB5->exec("set names utf8");
            $connDB5->exec("set use_secondary_engine=off");

            try{

                if($_ENV["TIMEZONE"] !=null && $_ENV["TIMEZONE"] !='') {
                    $connDB5->exec('SET time_zone = "'.$_ENV["TIMEZONE"].'";');
                }

                if($_ENV["NEEDINSOLATIONLEVEL"] != null && $_ENV["NEEDINSOLATIONLEVEL"] == '1') {
                    $connDB5->exec('SET TRANSACTION ISOLATION LEVEL READ COMMITTED;');
                }
                if($_ENV["ENABLEDSETLOCKWAITTIMEOUT"] != null && $_ENV["ENABLEDSETLOCKWAITTIMEOUT"] == '1') {
                    // $connDB5->exec('SET SESSION innodb_lock_wait_timeout = 5;');
                }
                if($_ENV["ENABLEDSETMAX_EXECUTION_TIME"] != null && $_ENV["ENABLEDSETMAX_EXECUTION_TIME"] == '1') {
                    // $connDB5->exec('SET SESSION MAX_EXECUTION_TIME = 120000;');
                }
                if($_ENV["DBNEEDUTF8"] != null && $_ENV["DBNEEDUTF8"] == '1') {
                    $connDB5->exec("SET NAMES utf8mb4");
                }
            }catch (\Exception $e){

            }
            $_ENV["connectionGlobal"]->setConnection($connDB5);
            $this->transaction->setConnection($_ENV["connectionGlobal"]);

        }



        $sql = "SELECT /*+ MAX_EXECUTION_TIME(50000) */  count(*) count FROM usuario force index (usuario_pais_id_index) INNER JOIN pais ON (usuario.pais_id=pais.pais_id) 
LEFT OUTER JOIN usuario_mandante ON (usuario_mandante.usuario_mandante=usuario.usuario_id and usuario_mandante.mandante = usuario.mandante )  
INNER JOIN usuario_perfil   ON (usuario_perfil.usuario_id=usuario.usuario_id)  
INNER JOIN perfil ON (usuario_perfil.perfil_id=perfil.perfil_id )  
LEFT OUTER JOIN registro ON (registro.usuario_id=usuario.usuario_id)  
left outer join usuario_otrainfo c on (usuario.mandante=c.mandante and usuario.usuario_id=c.usuario_id) 
left outer join ciudad d on (registro.ciudad_id=d.ciudad_id) left outer join ciudad e on (registro.ciudexped_id=e.ciudad_id) 
LEFT OUTER JOIN usuario_premiomax f on (usuario.mandante=f.mandante and usuario.usuario_id=f.usuario_id) 
left outer join ciudad g on (registro.ciudnacim_id=g.ciudad_id) left outer join usuario h on (usuario.mandante=h.mandante and usuario.sponsor_id=h.usuario_id) 
left outer join departamento  on (departamento.depto_id=g.depto_id)
 ".$innconcesionarioStr . $where;

        if($_ENV["debugFixed2"]){
            print_r($sql);
            exit();
        }

        if ($grouping != "") {
            $where = $where . " GROUP BY " . $grouping;
        }

        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
        $sql = "SELECT /*+ MAX_EXECUTION_TIME(50000) */  " . $select . " FROM usuario force index (usuario_pais_id_index) INNER JOIN pais ON (usuario.pais_id=pais.pais_id) 
        LEFT OUTER JOIN usuario_mandante ON (usuario_mandante.usuario_mandante=usuario.usuario_id and usuario_mandante.mandante = usuario.mandante )    
        INNER JOIN usuario_perfil   ON (usuario_perfil.usuario_id=usuario.usuario_id) 
        INNER JOIN perfil ON (usuario_perfil.perfil_id=perfil.perfil_id )  
        LEFT OUTER JOIN registro ON (registro.usuario_id=usuario.usuario_id) 
        left outer join usuario_otrainfo c on (usuario.mandante=c.mandante and usuario.usuario_id=c.usuario_id) 
        left outer join ciudad d on (registro.ciudad_id=d.ciudad_id) left outer join ciudad e on (registro.ciudexped_id=e.ciudad_id) 
        LEFT OUTER JOIN usuario_premiomax f on (usuario.mandante=f.mandante and usuario.usuario_id=f.usuario_id) 
        left outer join ciudad g on (registro.ciudnacim_id=g.ciudad_id) 
        left outer join usuario h on (usuario.mandante=h.mandante and usuario.sponsor_id=h.usuario_id) ".$innconcesionarioStr ." 
        LEFT OUTER JOIN punto_venta  ON (punto_venta.usuario_id = usuario.puntoventa_id ) left outer join departamento  on (departamento.depto_id=g.depto_id) " . $where . " " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);
        $result = $Helpers->process_data($this->execute2($sqlQuery));
        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null ) {
            $connDB5 = null;
            $_ENV["connectionGlobal"]->setConnection($connOriginal);
            $this->transaction->setConnection($_ENV["connectionGlobal"]);
        }

        return $json;
    }

    /**
     * Consulta personalizada de resumen de usuarios afiliados.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Índice de ordenación.
     * @param string $sord Orden de clasificación (ascendente o descendente).
     * @param int $start Inicio de los registros a recuperar.
     * @param int $limit Límite de registros a recuperar.
     * @param string $filters Filtros en formato JSON para aplicar a la consulta.
     * @param bool $searchOn Indica si se deben aplicar los filtros.
     * @param string $grouping Campo(s) para agrupar los resultados.
     * @return string JSON con el conteo y los datos resultantes de la consulta.
     */
    public function queryUsuariosResumenAfiliadosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping = "")
    {

        $where = " where 1=1 ";

        $Helpers = new Helpers();

        if ($searchOn) {
            // Construye el where
            $filters = json_decode($filters);
            $whereArray = array();
            $rules = $filters->rules;
            $groupOperation = $filters->groupOp;
            $cont = 0;

            foreach ($rules as $rule) {
                $fieldName = $Helpers->set_custom_field($rule->field);
                $fieldData = $rule->data;


                $Helpers = new Helpers();

                if($fieldName == 'registro.cedula'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_DOCUMENT']);
                }
                if($fieldName == 'registro.celular'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_PHONE']);
                }
                if($fieldName == 'usuario.cedula'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_DOCUMENT']);
                }
                if($fieldName == 'usuario.login'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }
                if($fieldName == 'usuario_mandante.email'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }
                if($fieldName == 'punto_venta.email'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }
                if($fieldName == 'usuario_sitebuilder.login'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }

                switch ($rule->op) {
                    case "eq":
                        $fieldOperation = " = '" . $fieldData . "'";
                        break;
                    case "ne":
                        $fieldOperation = " != '" . $fieldData . "'";
                        break;
                    case "lt":
                        $fieldOperation = " < '" . $fieldData . "'";
                        break;
                    case "gt":
                        $fieldOperation = " > '" . $fieldData . "'";
                        break;
                    case "le":
                        $fieldOperation = " <= '" . $fieldData . "'";
                        break;
                    case "ge":
                        $fieldOperation = " >= '" . $fieldData . "'";
                        break;
                    case "nu":
                        $fieldOperation = " = ''";
                        break;
                    case "nn":
                        $fieldOperation = " != ''";
                        break;
                    case "in":
                        $fieldOperation = " IN (" . $fieldData . ")";
                        break;
                    case "ni":
                        $fieldOperation = " NOT IN '" . $fieldData . "'";
                        break;
                    case "bw":
                        $fieldOperation = " LIKE '" . $fieldData . "%'";
                        break;
                    case "bn":
                        $fieldOperation = " NOT LIKE '" . $fieldData . "%'";
                        break;
                    case "ew":
                        $fieldOperation = " LIKE '%" . $fieldData . "'";
                        break;
                    case "en":
                        $fieldOperation = " NOT LIKE '%" . $fieldData . "'";
                        break;
                    case "cn":
                        $fieldOperation = " LIKE '%" . $fieldData . "%'";
                        break;
                    case "nc":
                        $fieldOperation = " NOT LIKE '%" . $fieldData . "%'";
                        break;
                    default:
                        $fieldOperation = "";
                        break;
                }
                if ($fieldOperation != "") {
                    $whereArray[] = $fieldName . $fieldOperation;
                }

                if (oldCount($whereArray) > 0) {
                    $where = $where . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
                } else {
                    $where = "";
                }
            }

        }


        if ($grouping != "") {
            $where = $where . " GROUP BY " . $grouping;
        }


        //$sql = "SELECT count(*) count FROM usuario LEFT OUTER JOIN usuario_perfil ON (usuario_perfil.usuario_id=usuario.usuario_id) LEFT OUTER JOIN registro ON (registro.usuario_id=usuario.usuario_id)  left outer join usuario_otrainfo c on (usuario.mandante=c.mandante and usuario.usuario_id=c.usuario_id) left outer join ciudad d on (registro.ciudad_id=d.ciudad_id) left outer join ciudad e on (registro.ciudexped_id=e.ciudad_id) LEFT OUTER JOIN usuario_premiomax f on (usuario.mandante=f.mandante and usuario.usuario_id=f.usuario_id) left outer join ciudad g on (registro.ciudnacim_id=g.ciudad_id) left outer join usuario h on (usuario.mandante=h.mandante and usuario.sponsor_id=h.usuario_id) LEFT OUTER JOIN concesionario  ON (concesionario.usuhijo_id = registro.afiliador_id  and concesionario.prodinterno_id=0) LEFT OUTER JOIN concesionario concesionario_punto  ON (concesionario.usuhijo_id = usuario.usuario_id  and concesionario_punto.prodinterno_id=0) " . $where;


        // $sqlQuery = new SqlQuery($sql);

        //$count = $this->execute2($sqlQuery);
        $sql = "SELECT  /*+ MAX_EXECUTION_TIME(50000) */  " . $select . "
FROM (
       SELECT 'Cantidad afiliados' tipo, count(*) ayer,0 mes_actual,0 mes_anterior,0 acumulado_anio, afiliador_id
       FROM registro
              INNER JOIN usuario ON (usuario.usuario_id = registro.usuario_id)
       WHERE afiliador_id > 0
         AND DATE_FORMAT(usuario.fecha_crea, '%Y-%m-%d') = DATE_FORMAT(DATE_SUB(now(), INTERVAL 1 DAY), '%Y-%m-%d')

       UNION
       SELECT 'Cantidad afiliados' tipo, 0 ayer,count(*) mes_actual,0 mes_anterior,0 acumulado_anio, afiliador_id
       FROM registro
              INNER JOIN usuario ON (usuario.usuario_id = registro.usuario_id)
       WHERE afiliador_id > 0
         AND DATE_FORMAT(usuario.fecha_crea, '%Y-%m') = DATE_FORMAT(DATE_SUB(now(), INTERVAL 0 DAY), '%Y-%m')
       UNION
       SELECT 'Cantidad afiliados' tipo, 0 ayer,0 mes_actual,count(*) mes_anterior,0 acumulado_anio, afiliador_id
       FROM registro
              INNER JOIN usuario ON (usuario.usuario_id = registro.usuario_id)
       WHERE afiliador_id > 0
         AND DATE_FORMAT(usuario.fecha_crea, '%Y-%m') = DATE_FORMAT(DATE_SUB(now(), INTERVAL 1 MONTH), '%Y-%m')

       UNION
       SELECT 'Cantidad afiliados' tipo, 0 ayer,0 mes_actual,0 mes_anterior,count(*) acumulado_anio, afiliador_id
       FROM registro
              INNER JOIN usuario ON (usuario.usuario_id = registro.usuario_id)
       WHERE afiliador_id > 0
         AND DATE_FORMAT(usuario.fecha_crea, '%Y') = DATE_FORMAT(DATE_SUB(now(), INTERVAL 0 DAY), '%Y')

              UNION
       SELECT 'Cantidad Apuestas Deportivas' tipo, SUM(cantidad) ayer,0 mes_actual,0 mes_anterior,0 acumulado_anio, afiliador_id
       FROM usuario_deporte_resumen
              INNER JOIN registro ON (registro.usuario_id = usuario_deporte_resumen.usuario_id)
       WHERE afiliador_id > 0
         AND DATE_FORMAT(usuario_deporte_resumen.fecha_crea, '%Y-%m-%d') =
             DATE_FORMAT(DATE_SUB(now(), INTERVAL 1 DAY), '%Y-%m-%d') AND tipo='1'

       UNION
       SELECT 'Cantidad Apuestas Deportivas' tipo, 0 ayer,SUM(cantidad) mes_actual,0 mes_anterior,0 acumulado_anio, afiliador_id
       FROM usuario_deporte_resumen
              INNER JOIN registro ON (registro.usuario_id = usuario_deporte_resumen.usuario_id)
       WHERE afiliador_id > 0
         AND DATE_FORMAT(usuario_deporte_resumen.fecha_crea, '%Y-%m') =
             DATE_FORMAT(DATE_SUB(now(), INTERVAL 0 DAY), '%Y-%m') AND tipo='1'
       UNION
       SELECT 'Cantidad Apuestas Deportivas' tipo, 0 ayer,0 mes_actual,SUM(cantidad) mes_anterior,0 acumulado_anio, afiliador_id
       FROM usuario_deporte_resumen
              INNER JOIN registro ON (registro.usuario_id = usuario_deporte_resumen.usuario_id)
       WHERE afiliador_id > 0
         AND DATE_FORMAT(usuario_deporte_resumen.fecha_crea, '%Y-%m') =
             DATE_FORMAT(DATE_SUB(now(), INTERVAL 1 MONTH), '%Y-%m') AND tipo='1'

       UNION
       SELECT 'Cantidad Apuestas Deportivas' tipo, 0 ayer,0 mes_actual,0 mes_anterior,SUM(cantidad) acumulado_anio, afiliador_id
       FROM usuario_deporte_resumen
              INNER JOIN registro ON (registro.usuario_id = usuario_deporte_resumen.usuario_id)
       WHERE afiliador_id > 0
         AND DATE_FORMAT(usuario_deporte_resumen.fecha_crea, '%Y') = DATE_FORMAT(DATE_SUB(now(), INTERVAL 0 DAY), '%Y') AND tipo='1'



       UNION
       SELECT 'Cantidad Apuestas Deportivas' tipo, SUM(valor) ayer,0 mes_actual,0 mes_anterior,0 acumulado_anio, afiliador_id
       FROM usuario_deporte_resumen
              INNER JOIN registro ON (registro.usuario_id = usuario_deporte_resumen.usuario_id)
       WHERE afiliador_id > 0
         AND DATE_FORMAT(usuario_deporte_resumen.fecha_crea, '%Y-%m-%d') =
             DATE_FORMAT(DATE_SUB(now(), INTERVAL 1 DAY), '%Y-%m-%d') AND tipo='1'

       UNION
       SELECT 'Apuestas Deportivas' tipo, 0 ayer,SUM(valor) mes_actual,0 mes_anterior,0 acumulado_anio, afiliador_id
       FROM usuario_deporte_resumen
              INNER JOIN registro ON (registro.usuario_id = usuario_deporte_resumen.usuario_id)
       WHERE afiliador_id > 0
         AND DATE_FORMAT(usuario_deporte_resumen.fecha_crea, '%Y-%m') =
             DATE_FORMAT(DATE_SUB(now(), INTERVAL 0 DAY), '%Y-%m') AND tipo='1'
       UNION
       SELECT 'Apuestas Deportivas' tipo, 0 ayer,0 mes_actual,SUM(valor) mes_anterior,0 acumulado_anio, afiliador_id
       FROM usuario_deporte_resumen
              INNER JOIN registro ON (registro.usuario_id = usuario_deporte_resumen.usuario_id)
       WHERE afiliador_id > 0
         AND DATE_FORMAT(usuario_deporte_resumen.fecha_crea, '%Y-%m') =
             DATE_FORMAT(DATE_SUB(now(), INTERVAL 1 MONTH), '%Y-%m') AND tipo='1'

       UNION
       SELECT 'Apuestas Deportivas' tipo, 0 ayer,0 mes_actual,0 mes_anterior,SUM(valor) acumulado_anio, afiliador_id
       FROM usuario_deporte_resumen
              INNER JOIN registro ON (registro.usuario_id = usuario_deporte_resumen.usuario_id)
       WHERE afiliador_id > 0
         AND DATE_FORMAT(usuario_deporte_resumen.fecha_crea, '%Y') = DATE_FORMAT(DATE_SUB(now(), INTERVAL 0 DAY), '%Y') AND tipo='1'



       UNION
       SELECT 'Premios Deportivas' tipo, SUM(valor) ayer,0 mes_actual,0 mes_anterior,0 acumulado_anio, afiliador_id
       FROM usuario_deporte_resumen
              INNER JOIN registro ON (registro.usuario_id = usuario_deporte_resumen.usuario_id)
       WHERE afiliador_id > 0
         AND DATE_FORMAT(usuario_deporte_resumen.fecha_crea, '%Y-%m-%d') =
             DATE_FORMAT(DATE_SUB(now(), INTERVAL 1 DAY), '%Y-%m-%d') AND tipo='2'

       UNION
       SELECT 'Premios Deportivas' tipo, 0 ayer,SUM(valor) mes_actual,0 mes_anterior,0 acumulado_anio, afiliador_id
       FROM usuario_deporte_resumen
              INNER JOIN registro ON (registro.usuario_id = usuario_deporte_resumen.usuario_id)
       WHERE afiliador_id > 0
         AND DATE_FORMAT(usuario_deporte_resumen.fecha_crea, '%Y-%m') =
             DATE_FORMAT(DATE_SUB(now(), INTERVAL 0 DAY), '%Y-%m') AND tipo='2'
       UNION
       SELECT 'Premios Deportivas' tipo, 0 ayer,0 mes_actual,SUM(valor) mes_anterior,0 acumulado_anio, afiliador_id
       FROM usuario_deporte_resumen
              INNER JOIN registro ON (registro.usuario_id = usuario_deporte_resumen.usuario_id)
       WHERE afiliador_id > 0
         AND DATE_FORMAT(usuario_deporte_resumen.fecha_crea, '%Y-%m') =
             DATE_FORMAT(DATE_SUB(now(), INTERVAL 1 MONTH), '%Y-%m') AND tipo='2'

       UNION
       SELECT 'Premios Deportivas' tipo, 0 ayer,0 mes_actual,0 mes_anterior,SUM(valor) acumulado_anio, afiliador_id
       FROM usuario_deporte_resumen
              INNER JOIN registro ON (registro.usuario_id = usuario_deporte_resumen.usuario_id)
       WHERE afiliador_id > 0
         AND DATE_FORMAT(usuario_deporte_resumen.fecha_crea, '%Y') = DATE_FORMAT(DATE_SUB(now(), INTERVAL 0 DAY), '%Y') AND tipo='2'

     ) data " . $where;


        $sqlQuery = new SqlQuery($sql);

        $result = $Helpers->process_data($this->execute2($sqlQuery));

        $json = '{ "count" : 1, "data" : ' . json_encode($result) . '}';

        return $json;
    }


    /**
     * Consulta personalizada de usuarios KPI.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Campo por el cual ordenar.
     * @param string $sord Orden de la consulta (ASC o DESC).
     * @param int $start Inicio del límite de la consulta.
     * @param int $limit Cantidad de registros a obtener.
     * @param string $filters Filtros en formato JSON.
     * @param bool $searchOn Indica si la búsqueda está activa.
     * @param string $grouping Agrupamiento de la consulta.
     * @param int $usuarioId ID del usuario.
     * @return string JSON con el conteo y los datos obtenidos.
     */
    public function queryUsuariosKPICustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $grouping, $usuarioId)
    {

        $where = " where 1=1 ";

        $Helpers = new Helpers();

        if ($searchOn) {
            // Construye el where
            $filters = json_decode($filters);
            $whereArray = array();
            $rules = $filters->rules;
            $groupOperation = $filters->groupOp;
            $cont = 0;

            foreach ($rules as $rule) {
                $fieldName = $Helpers->set_custom_field($rule->field);
                $fieldData = $rule->data;


                $Helpers = new Helpers();

                if($fieldName == 'registro.cedula'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_DOCUMENT']);
                }
                if($fieldName == 'registro.celular'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_PHONE']);
                }
                if($fieldName == 'usuario.cedula'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_DOCUMENT']);
                }
                if($fieldName == 'usuario.login'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }
                if($fieldName == 'usuario_mandante.email'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }
                if($fieldName == 'punto_venta.email'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }
                if($fieldName == 'usuario_sitebuilder.login'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }

                switch ($rule->op) {
                    case "eq":
                        $fieldOperation = " = '" . $fieldData . "'";
                        break;
                    case "ne":
                        $fieldOperation = " != '" . $fieldData . "'";
                        break;
                    case "lt":
                        $fieldOperation = " < '" . $fieldData . "'";
                        break;
                    case "gt":
                        $fieldOperation = " > '" . $fieldData . "'";
                        break;
                    case "le":
                        $fieldOperation = " <= '" . $fieldData . "'";
                        break;
                    case "ge":
                        $fieldOperation = " >= '" . $fieldData . "'";
                        break;
                    case "nu":
                        $fieldOperation = " = ''";
                        break;
                    case "nn":
                        $fieldOperation = " != ''";
                        break;
                    case "in":
                        $fieldOperation = " IN (" . $fieldData . ")";
                        break;
                    case "ni":
                        $fieldOperation = " NOT IN '" . $fieldData . "'";
                        break;
                    case "bw":
                        $fieldOperation = " LIKE '" . $fieldData . "%'";
                        break;
                    case "bn":
                        $fieldOperation = " NOT LIKE '" . $fieldData . "%'";
                        break;
                    case "ew":
                        $fieldOperation = " LIKE '%" . $fieldData . "'";
                        break;
                    case "en":
                        $fieldOperation = " NOT LIKE '%" . $fieldData . "'";
                        break;
                    case "cn":
                        $fieldOperation = " LIKE '%" . $fieldData . "%'";
                        break;
                    case "nc":
                        $fieldOperation = " NOT LIKE '%" . $fieldData . "%'";
                        break;
                    default:
                        $fieldOperation = "";
                        break;
                }
                if ($fieldOperation != "") {
                    $whereArray[] = $fieldName . $fieldOperation;
                }

                if (oldCount($whereArray) > 0) {
                    $where = $where . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
                } else {
                    $where = "";
                }
            }

        }

        if ($grouping != "") {
            $where = $where . " GROUP BY " . $grouping;
        }


        $sql = "SELECT /*+ MAX_EXECUTION_TIME(120000) */   " . $select . " FROM usuario  LEFT OUTER JOIN usuario_perfil ON (usuario_perfil.usuario_id=usuario.usuario_id) LEFT OUTER JOIN registro ON (registro.usuario_id=usuario.usuario_id) left outer join usuario_otrainfo c on (usuario.mandante=c.mandante and usuario.usuario_id=c.usuario_id) left outer join ciudad d on (registro.ciudad_id=d.ciudad_id) left outer join ciudad e on (registro.ciudexped_id=e.ciudad_id) LEFT OUTER JOIN usuario_premiomax f on (usuario.mandante=f.mandante and usuario.usuario_id=f.usuario_id) left outer join ciudad g on (registro.ciudnacim_id=g.ciudad_id) left outer join usuario h on (usuario.mandante=h.mandante and usuario.sponsor_id=h.usuario_id) LEFT OUTER JOIN concesionario  ON (concesionario.usuhijo_id = registro.afiliador_id) LEFT OUTER JOIN concesionario concesionario_punto  ON (concesionario.usuhijo_id = usuario.usuario_id and concesionario.prodinterno_id=0  and  concesionario.estado='A')   LEFT OUTER JOIN punto_venta  ON (punto_venta.usuario_id = usuario.usuario_id  and concesionario_punto.prodinterno_id=0)  " . $where . " " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sql = "
SELECT /*+ MAX_EXECUTION_TIME(120000) */   " . $select . " FROM (
SELECT 'Apuesta Deportiva' tipo,ticket_id referencia,usuario_id usuario,vlr_apuesta valor,CONCAT(fecha_crea,' ',hora_crea) fecha
FROM it_ticket_enc

WHERE usuario_id = '" . $usuarioId . "'
  AND eliminado = 'N'

UNION

SELECT /*+ MAX_EXECUTION_TIME(120000) */   'Premio Apuesta Deportiva' tipo,ticket_id referencia,usuario_id usuario,vlr_premio valor,CONCAT(fecha_cierre,' ',hora_cierre) fecha
FROM it_ticket_enc

WHERE usuario_id = '" . $usuarioId . "'
  AND eliminado = 'N'
  and premiado = 'S'


UNION

SELECT /*+ MAX_EXECUTION_TIME(120000) */   'Deposito' tipo,recarga_id referencia,usuario_id usuario,valor,fecha_crea fecha
FROM usuario_recarga

WHERE usuario_id = '" . $usuarioId . "'

UNION

SELECT /*+ MAX_EXECUTION_TIME(120000) */   'Retiro Pendiente' tipo,cuenta_id referencia,usuario_id usuario,valor,fecha_pago fecha
FROM cuenta_cobro

WHERE usuario_id = '" . $usuarioId . "'
  AND (estado = 'A' OR estado = 'S' OR estado = 'P')

UNION

SELECT /*+ MAX_EXECUTION_TIME(120000) */   'Retiro Pagado' tipo,cuenta_id referencia,usuario_id usuario,valor,fecha_pago fecha
FROM cuenta_cobro

WHERE usuario_id = '" . $usuarioId . "'
  AND estado = 'I'

UNION

SELECT /*+ MAX_EXECUTION_TIME(120000) */   'Apuesta Casino' tipo,transjuego_id referencia,usuario_mandante usuario,valor_ticket valor,transaccion_juego.fecha_crea fecha
FROM transaccion_juego
INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id =usumandante_id)

WHERE usuario_mandante = '" . $usuarioId . "' AND tipo!='FREESPIN'

UNION

SELECT /*+ MAX_EXECUTION_TIME(120000) */   'Premio Casino' tipo,transjuego_id referencia,usuario_mandante usuario,valor_premio valor,fecha_pago fecha
FROM transaccion_juego
INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id =usumandante_id)

WHERE usuario_mandante = '" . $usuarioId . "' AND premiado='S' 


UNION

SELECT /*+ MAX_EXECUTION_TIME(120000) */   'Bono' tipo,bonolog_id referencia,usuario_id usuario,valor valor,fecha_crea fecha
FROM bono_log

WHERE usuario_id = '" . $usuarioId . "'

  ) data   " . $where . " " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sql = "
SELECT " . $select . " FROM (
SELECT /*+ MAX_EXECUTION_TIME(120000) */   'Apuesta Deportiva' tipo,ticket_id referencia,usuario_id usuario,vlr_apuesta valor,CONCAT(fecha_crea,' ',hora_crea) fecha
FROM it_ticket_enc

WHERE usuario_id = '" . $usuarioId . "'
  AND eliminado = 'N'

UNION

SELECT /*+ MAX_EXECUTION_TIME(120000) */   'Premio Apuesta Deportiva' tipo,ticket_id referencia,usuario_id usuario,vlr_premio valor,CONCAT(fecha_cierre,' ',hora_cierre) fecha
FROM it_ticket_enc

WHERE usuario_id = '" . $usuarioId . "'
  AND eliminado = 'N'
  and premiado = 'S'


UNION

SELECT /*+ MAX_EXECUTION_TIME(120000) */   'Deposito' tipo,recarga_id referencia,usuario_id usuario,valor,fecha_crea fecha
FROM usuario_recarga

WHERE usuario_id = '" . $usuarioId . "'

UNION

SELECT /*+ MAX_EXECUTION_TIME(120000) */   'Retiro Pendiente' tipo,cuenta_id referencia,usuario_id usuario,valor,fecha_pago fecha
FROM cuenta_cobro

WHERE usuario_id = '" . $usuarioId . "'
  AND (estado = 'A' OR estado = 'S' OR estado = 'P')
  
  
UNION

SELECT /*+ MAX_EXECUTION_TIME(120000) */   'Ajuste Entrada' tipo,ajuste_id referencia,usuario_id usuario,valor,fecha_crea fecha
FROM saldo_usuonline_ajuste

WHERE usuario_id = '" . $usuarioId . "'
  AND tipo_id = 'E'
  
    
UNION

SELECT /*+ MAX_EXECUTION_TIME(120000) */   'Ajuste Salida' tipo,ajuste_id referencia,usuario_id usuario,valor,fecha_crea fecha
FROM saldo_usuonline_ajuste

WHERE usuario_id = '" . $usuarioId . "'
  AND tipo_id = 'S'

UNION

SELECT /*+ MAX_EXECUTION_TIME(120000) */   'Retiro Pagado' tipo,cuenta_id referencia,usuario_id usuario,valor,fecha_pago fecha
FROM cuenta_cobro

WHERE usuario_id = '" . $usuarioId . "'
  AND estado = 'I'

      UNION
      SELECT /*+ MAX_EXECUTION_TIME(120000) */   'Apuesta Casino'             tipo,
             transjuego_log.transjuego_id                referencia,
             usuario_mandante             usuario,
             valor                 valor,
             transjuego_log.fecha_crea fecha
      FROM transjuego_log
        INNER JOIN transaccion_juego ON (transjuego_log.transjuego_id =transaccion_juego.transjuego_id)
             INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id = usumandante_id)
      WHERE usuario_mandante = '" . $usuarioId . "'
        AND transjuego_log.tipo LIKE '%DEBIT%'
      UNION
      SELECT /*+ MAX_EXECUTION_TIME(120000) */   'Premio Casino' tipo,transjuego_log.transjuego_id referencia,usuario_mandante usuario,valor valor,transjuego_log.fecha_crea fecha
      FROM transjuego_log
        INNER JOIN transaccion_juego ON (transjuego_log.transjuego_id =transaccion_juego.transjuego_id)
             INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id = usumandante_id)
      WHERE usuario_mandante = '" . $usuarioId . "'
        AND (transjuego_log.tipo LIKE '%CREDIT%'OR transjuego_log.tipo like '%ROLLBACK%')


UNION

SELECT /*+ MAX_EXECUTION_TIME(120000) */   'Bono' tipo,bonolog_id referencia,usuario_id usuario,valor valor,fecha_crea fecha
FROM bono_log

WHERE usuario_id = '" . $usuarioId . "' AND tipo != 'F' AND estado ='L'


UNION

SELECT /*+ MAX_EXECUTION_TIME(120000) */   'Bono Freebet' tipo,bonolog_id referencia,usuario_id usuario,valor valor,fecha_crea fecha
FROM bono_log

WHERE usuario_id = '" . $usuarioId . "' AND tipo = 'F' AND estado ='L'


UNION

SELECT /*+ MAX_EXECUTION_TIME(120000) */   'Bono Freebet Eliminado' tipo,bonolog_id referencia,usuario_id usuario,valor valor,fecha_crea fecha
FROM bono_log

WHERE usuario_id = '" . $usuarioId . "' AND tipo = 'F' AND estado ='E'


UNION

SELECT /*+ MAX_EXECUTION_TIME(120000) */   'Bono Free Ganado' tipo,bonolog_id referencia,usuario_id usuario,valor valor,fecha_crea fecha
FROM bono_log

WHERE usuario_id = '" . $usuarioId . "' AND estado ='W'


  ) data   " . $where . " " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;


        if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null ){
            $connOriginal = $_ENV["connectionGlobal"]->getConnection();




            $connDB5 = null;


            if($_ENV['ENV_TYPE'] =='prod') {

                $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()
                    , array(
                        PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/ca-bundle.crt',
                        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
                    )
                );
            }else{

                $connDB5 = new \PDO("mysql:host=" . $_ENV['DB_HOST_BACKUP'] . ";dbname=" . ConnectionProperty::getDatabase(), ConnectionProperty::getUser(), ConnectionProperty::getPassword()

                );
            }

            $connDB5->exec("set names utf8");
            $connDB5->exec("set use_secondary_engine=off");

            try{

                if($_ENV["TIMEZONE"] !=null && $_ENV["TIMEZONE"] !='') {
                    $connDB5->exec('SET time_zone = "'.$_ENV["TIMEZONE"].'";');
                }

                if($_ENV["NEEDINSOLATIONLEVEL"] != null && $_ENV["NEEDINSOLATIONLEVEL"] == '1') {
                    $connDB5->exec('SET TRANSACTION ISOLATION LEVEL READ COMMITTED;');
                }
                if($_ENV["ENABLEDSETLOCKWAITTIMEOUT"] != null && $_ENV["ENABLEDSETLOCKWAITTIMEOUT"] == '1') {
                    // $connDB5->exec('SET SESSION innodb_lock_wait_timeout = 5;');
                }
                if($_ENV["ENABLEDSETMAX_EXECUTION_TIME"] != null && $_ENV["ENABLEDSETMAX_EXECUTION_TIME"] == '1') {
                    // $connDB5->exec('SET SESSION MAX_EXECUTION_TIME = 120000;');
                }
                if($_ENV["DBNEEDUTF8"] != null && $_ENV["DBNEEDUTF8"] == '1') {
                    $connDB5->exec("SET NAMES utf8mb4");
                }
            }catch (\Exception $e){

            }
            $_ENV["connectionGlobal"]->setConnection($connDB5);
            $this->transaction->setConnection($_ENV["connectionGlobal"]);

        }

        $sql = "
        
SELECT /*+ MAX_EXECUTION_TIME(120000) */   " . $select . " FROM (


 SELECT  /*+ MAX_EXECUTION_TIME(120000) */  'Apuesta Deportiva' tipo,'' referencia,'' usuario, saldo_apuestas valor,'' fecha
         from usuario_saldoresumen
         WHERE usuario_id = '" . $usuarioId . "'

         UNION


         SELECT /*+ MAX_EXECUTION_TIME(120000) */   'Premio Apuesta Deportiva' tipo,'' referencia,'' usuario, saldo_premios valor,'' fecha
         from usuario_saldoresumen
         WHERE usuario_id = '" . $usuarioId . "'

         union
         SELECT /*+ MAX_EXECUTION_TIME(120000) */   'Deposito' tipo,'' referencia,'' usuario, saldo_recarga valor,'' fecha
         from usuario_saldoresumen
         WHERE usuario_id = '" . $usuarioId . "'
         union


         SELECT /*+ MAX_EXECUTION_TIME(120000) */   'Ajuste Entrada' tipo,'' referencia,'' usuario, saldo_ajustes_entrada valor,'' fecha
         from usuario_saldoresumen
         WHERE usuario_id = '" . $usuarioId . "'
         union



         SELECT /*+ MAX_EXECUTION_TIME(120000) */   'Ajuste Salida' tipo,'' referencia,'' usuario, saldo_ajustes_salida valor,'' fecha
         from usuario_saldoresumen
         WHERE usuario_id = '" . $usuarioId . "'
         union


         SELECT /*+ MAX_EXECUTION_TIME(120000) */   'Retiro Pagado' tipo,'' referencia,'' usuario, saldo_notaret_pagadas valor,'' fecha
         from usuario_saldoresumen
         WHERE usuario_id = '" . $usuarioId . "'
         union


         SELECT /*+ MAX_EXECUTION_TIME(120000) */   'Apuesta Casino' tipo,'' referencia,'' usuario, saldo_apuestas_casino valor,'' fecha
         from usuario_saldoresumen
         WHERE usuario_id = '" . $usuarioId . "'
         union


         SELECT /*+ MAX_EXECUTION_TIME(120000) */   'Premio Casino' tipo,'' referencia,'' usuario, saldo_premios_casino valor,'' fecha
         from usuario_saldoresumen
         WHERE usuario_id = '" . $usuarioId . "'
         union


         SELECT /*+ MAX_EXECUTION_TIME(120000) */   'Bono' tipo,'' referencia,'' usuario, saldo_bono+saldo_bono_casino_vivo valor,'' fecha
         from usuario_saldoresumen
         WHERE usuario_id = '" . $usuarioId . "'
         union




         SELECT /*+ MAX_EXECUTION_TIME(120000) */   'Bono Free Ganado' tipo,'' referencia,'' usuario, saldo_bono_free_ganado valor,'' fecha
         from usuario_saldoresumen
         WHERE usuario_id = '" . $usuarioId . "'
         union







         SELECT /*+ MAX_EXECUTION_TIME(120000) */   'Apuesta Deportiva'                tipo,
                ticket_id                          referencia,
                usuario_id                         usuario,
                vlr_apuesta                        valor,
                CONCAT(fecha_crea, ' ', hora_crea) fecha
         FROM it_ticket_enc

         WHERE usuario_id = '" . $usuarioId . "'
           AND eliminado = 'N'
           AND fecha_crea = '" . date("Y-m-d") . "'

         UNION

         SELECT /*+ MAX_EXECUTION_TIME(120000) */   'Premio Apuesta Deportiva'             tipo,
                ticket_id                              referencia,
                usuario_id                             usuario,
                SUM(vlr_premio)                             valor,
                CONCAT(fecha_cierre, ' ', hora_cierre) fecha
         FROM it_ticket_enc

         WHERE usuario_id = '" . $usuarioId . "'
           AND eliminado = 'N'
           and premiado = 'S'
           AND fecha_cierre = '" . date("Y-m-d") . "'


         UNION

         SELECT /*+ MAX_EXECUTION_TIME(120000) */   'Deposito' tipo, recarga_id referencia, usuario_id usuario, valor, fecha_crea fecha
         FROM usuario_recarga

         WHERE usuario_id = '" . $usuarioId . "'
           AND fecha_crea >= '" . date("Y-m-d 00:00:00") . "'

         UNION

         SELECT /*+ MAX_EXECUTION_TIME(120000) */   'Retiro Pendiente' tipo, cuenta_id referencia, usuario_id usuario, valor, fecha_pago fecha
         FROM cuenta_cobro

         WHERE usuario_id = '" . $usuarioId . "'
           AND (estado = 'A' OR estado = 'S' OR estado = 'M' OR estado = 'P')


         UNION

         SELECT /*+ MAX_EXECUTION_TIME(120000) */   'Ajuste Entrada' tipo, ajuste_id referencia, usuario_id usuario, valor, fecha_crea fecha
         FROM saldo_usuonline_ajuste

         WHERE usuario_id = '" . $usuarioId . "'
           AND tipo_id = 'E'
           AND fecha_crea >= '" . date("Y-m-d 00:00:00") . "'


         UNION

         SELECT /*+ MAX_EXECUTION_TIME(120000) */   'Ajuste Salida' tipo, ajuste_id referencia, usuario_id usuario, valor, fecha_crea fecha
         FROM saldo_usuonline_ajuste

         WHERE usuario_id = '" . $usuarioId . "'
           AND tipo_id = 'S'
           AND fecha_crea >= '" . date("Y-m-d 00:00:00") . "'

         UNION

         SELECT /*+ MAX_EXECUTION_TIME(120000) */   'Retiro Pagado' tipo, cuenta_id referencia, usuario_id usuario, valor, fecha_pago fecha
         FROM cuenta_cobro

         WHERE usuario_id = '" . $usuarioId . "'
           AND estado = 'I'
           AND fecha_pago >= '" . date("Y-m-d 00:00:00") . "'

         UNION
         SELECT /*+ MAX_EXECUTION_TIME(120000) */   CASE
                    WHEN transjuego_log.tipo LIKE 'DEBIT%' THEN 'Apuesta Casino'
                    WHEN (transjuego_log.tipo LIKE 'CREDIT%' OR transjuego_log.tipo like 'ROLLBACK%')
                        THEN 'Premio Casino' END tipo,
                transjuego_log.transjuego_id     referencia,
                usuario_mandante                 usuario,
                valor                            valor,
                transjuego_log.fecha_crea        fecha
         FROM transjuego_log
                  INNER JOIN transaccion_juego ON (transjuego_log.transjuego_id = transaccion_juego.transjuego_id)
                  INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id = usumandante_id)
         WHERE usuario_mandante = '" . $usuarioId . "'
           AND transjuego_log.fecha_crea >= '" . date("Y-m-d 00:00:00") . "'


         UNION

         SELECT /*+ MAX_EXECUTION_TIME(120000) */   'Bono' tipo, bonolog_id referencia, usuario_id usuario, valor valor, fecha_crea fecha
         FROM bono_log

         WHERE usuario_id = '" . $usuarioId . "'
           AND tipo != 'F'
           AND estado = 'L'
           AND fecha_crea >= '" . date("Y-m-d 00:00:00") . "'


         UNION

         SELECT /*+ MAX_EXECUTION_TIME(120000) */   'Bono Freebet' tipo, bonolog_id referencia, usuario_id usuario, valor valor, fecha_crea fecha
         FROM bono_log

         WHERE usuario_id = '" . $usuarioId . "'
           AND tipo = 'F'
           AND estado = 'L'
           AND fecha_crea >= '" . date("Y-m-d 00:00:00") . "'


         UNION

         SELECT /*+ MAX_EXECUTION_TIME(120000) */     'Bono Freebet Eliminado' tipo, bonolog_id referencia, usuario_id usuario, valor valor, fecha_crea fecha
         FROM bono_log

         WHERE usuario_id = '" . $usuarioId . "'
           AND tipo = 'F'
           AND estado = 'E'
           AND fecha_crea >= '" . date("Y-m-d 00:00:00") . "'


         UNION

         SELECT /*+ MAX_EXECUTION_TIME(120000) */   'Bono Free Ganado' tipo, bonolog_id referencia, usuario_id usuario, valor valor, fecha_crea fecha
         FROM bono_log

         WHERE usuario_id = '" . $usuarioId . "'
           AND estado = 'W'
           AND fecha_crea >= '" . date("Y-m-d 00:00:00") . "'

  ) data   " . $where . " " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;


        $sqlQuery = new SqlQuery($sql);

        $result = $Helpers->process_data($this->execute2($sqlQuery));

        if($_ENV["connectionGlobal"] != '' && $_ENV["connectionGlobal"] != null ) {
            $connDB5 = null;
            $_ENV["connectionGlobal"]->setConnection($connOriginal);
            $this->transaction->setConnection($_ENV["connectionGlobal"]);
        }
        $json = '{ "count" : 1, "data" : ' . json_encode($result) . '}';

        return $json;
    }


    /**
     * Realizar una consulta en la tabla de Usuario 'Usuario'
     * de una manera personalizada
     *
     * @param String $select campos de consulta
     * @param String $sidx columna para ordenar
     * @param String $sord orden los datos asc | desc
     * @param String $start inicio de la consulta
     * @param String $limit limite de la consulta
     * @param String $filters condiciones de la consulta
     * @param boolean $searchOn utilizar los filtros o no
     *
     * @return Array $json resultado de la consulta
     *
     */
    public function queryUsuariosSuperCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
    {

        $where = " where 1=1 ";
        $Helpers = new Helpers();
        if ($searchOn) {
            // Construye el where
            $filters = json_decode($filters);
            $whereArray = array();
            $rules = $filters->rules;
            $groupOperation = $filters->groupOp;
            $cont = 0;

            foreach ($rules as $rule) {
                $fieldName = $Helpers->set_custom_field($rule->field);
                $fieldData = $rule->data;

                $Helpers = new Helpers();

                if($fieldName == 'registro.cedula'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_DOCUMENT']);
                }
                if($fieldName == 'registro.celular'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_PHONE']);
                }
                if($fieldName == 'usuario.cedula'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_DOCUMENT']);
                }
                if($fieldName == 'usuario.login'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }
                if($fieldName == 'usuario_mandante.email'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }
                if($fieldName == 'punto_venta.email'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }
                if($fieldName == 'usuario_sitebuilder.login'){
                    $fieldData = $Helpers->encode_data_with_key($fieldData, $_ENV['SECRET_PASSPHRASE_LOGIN']);
                }

                switch ($rule->op) {
                    case "eq":
                        $fieldOperation = " = '" . $fieldData . "'";
                        break;
                    case "ne":
                        $fieldOperation = " != '" . $fieldData . "'";
                        break;
                    case "lt":
                        $fieldOperation = " < '" . $fieldData . "'";
                        break;
                    case "gt":
                        $fieldOperation = " > '" . $fieldData . "'";
                        break;
                    case "le":
                        $fieldOperation = " <= '" . $fieldData . "'";
                        break;
                    case "ge":
                        $fieldOperation = " >= '" . $fieldData . "'";
                        break;
                    case "nu":
                        $fieldOperation = " = ''";
                        break;
                    case "nn":
                        $fieldOperation = " != ''";
                        break;
                    case "in":
                        $fieldOperation = " IN (" . $fieldData . ")";
                        break;
                    case "ni":
                        $fieldOperation = " NOT IN '" . $fieldData . "'";
                        break;
                    case "bw":
                        $fieldOperation = " LIKE '" . $fieldData . "%'";
                        break;
                    case "bn":
                        $fieldOperation = " NOT LIKE '" . $fieldData . "%'";
                        break;
                    case "ew":
                        $fieldOperation = " LIKE '%" . $fieldData . "'";
                        break;
                    case "en":
                        $fieldOperation = " NOT LIKE '%" . $fieldData . "'";
                        break;
                    case "cn":
                        $fieldOperation = " LIKE '%" . $fieldData . "%'";
                        break;
                    case "nc":
                        $fieldOperation = " NOT LIKE '%" . $fieldData . "%'";
                        break;
                    default:
                        $fieldOperation = "";
                        break;
                }
                if ($fieldOperation != "") {
                    $whereArray[] = $fieldName . $fieldOperation;
                }

                if (oldCount($whereArray) > 0) {
                    $where = $where . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
                } else {
                    $where = "";
                }
            }

        }

        $sql = "SELECT /*+ MAX_EXECUTION_TIME(50000) */  count(*) count FROM usuario    LEFT OUTER JOIN punto_venta  ON (punto_venta.usuario_id = usuario.usuario_id) LEFT OUTER JOIN usuario_perfil ON (usuario_perfil.usuario_id=usuario.usuario_id)  left outer join usuario_otrainfo c on (usuario.mandante=c.mandante and usuario.usuario_id=c.usuario_id) left outer join ciudad  on (punto_venta.ciudad_id=ciudad.ciudad_id)  LEFT OUTER JOIN departamento ON (departamento.depto_id=ciudad.depto_id) LEFT OUTER JOIN pais ON (departamento.pais_id=pais.pais_id)   LEFT OUTER JOIN usuario_premiomax f on (usuario.mandante=f.mandante and usuario.usuario_id=f.usuario_id)  LEFT OUTER JOIN usuario_config  on (usuario.usuario_id=usuario_config.usuario_id ) LEFT OUTER JOIN concesionario ON (concesionario.usuhijo_id = usuario.usuario_id and concesionario.prodinterno_id=0   AND concesionario.estado='A') " . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
        $sql = "SELECT /*+ MAX_EXECUTION_TIME(50000) */  " . $select . " FROM usuario    LEFT OUTER JOIN punto_venta  ON (punto_venta.usuario_id = usuario.usuario_id) LEFT OUTER JOIN usuario_perfil ON (usuario_perfil.usuario_id=usuario.usuario_id)  left outer join usuario_otrainfo c on (usuario.mandante=c.mandante and usuario.usuario_id=c.usuario_id) left outer join ciudad  on (punto_venta.ciudad_id=ciudad.ciudad_id)  LEFT OUTER JOIN departamento ON (departamento.depto_id=ciudad.depto_id) LEFT OUTER JOIN pais ON (departamento.pais_id=pais.pais_id)   LEFT OUTER JOIN usuario_premiomax f on (usuario.mandante=f.mandante and usuario.usuario_id=f.usuario_id)  LEFT OUTER JOIN usuario_config  on (usuario.usuario_id=usuario_config.usuario_id )  LEFT OUTER JOIN concesionario ON (concesionario.usuhijo_id = usuario.usuario_id and concesionario.prodinterno_id=0 AND concesionario.estado='A')  " . $where . " " . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);

        $result = $Helpers->process_data($this->execute2($sqlQuery));

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }

    /**
     * Obtener un resumen para el usuario identificado
     * con el id pasado como parámetro
     *
     * @param String $fechaInicio fechaInicio
     * @param String $fechaFin fechaFin
     * @param String $usuarioId id del usuario
     * @param String $tipo tipo
     *
     * @return Array $json resultado de la consulta
     *
     */
    public function queryUsuarioResume($fechaInicio, $fechaFin, $usuarioId, $tipo)
    {

        $where = "";

        switch ($tipo) {
            case "3":
                $sql = "SELECT movimientos.fecha,movimientos.valor,movimientos.tipo FROM ((SELECT
         usuario_recarga.fecha_crea  fecha,
         usuario_recarga.valor valor,
         'Depositos'               tipo
       FROM usuario_recarga
         INNER JOIN usuario ON (usuario.usuario_id = usuario_recarga.usuario_id)
       WHERE  usuario.usuario_id = '" . $usuarioId . "')) movimientos ORDER BY movimientos.fecha DESC ;
";
                break;

            default:
                $sql = "SELECT movimientos.fecha,movimientos.valor,movimientos.tipo FROM ((SELECT
   CONCAT(it_ticket_enc.fecha_crea,' ',it_ticket_enc.hora_crea)  fecha,
   (-it_ticket_enc.vlr_apuesta) valor,
   'Apuestas'                tipo
 FROM it_ticket_enc
   INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
 WHERE usuario.usuario_id = '" . $usuarioId . "')
UNION (SELECT
   CONCAT(it_ticket_enc.fecha_crea,' ',it_ticket_enc.hora_crea)  fecha,
         it_ticket_enc.vlr_apuesta valor,
         'Ganadoras'               tipo
       FROM it_ticket_enc
         INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       WHERE it_ticket_enc.premiado = 'S' AND usuario.usuario_id = '" . $usuarioId . "')
UNION (SELECT
         usuario_recarga.fecha_crea  fecha,
         usuario_recarga.valor valor,
         'Depositos'               tipo
       FROM usuario_recarga
         INNER JOIN usuario ON (usuario.usuario_id = usuario_recarga.usuario_id)
       WHERE  usuario.usuario_id = '" . $usuarioId . "')
UNION (SELECT
         cuenta_cobro.fecha_crea  fecha,
         (-cuenta_cobro.valor) valor,
         'Retiros'               tipo
       FROM cuenta_cobro
         INNER JOIN usuario ON (usuario.usuario_id = cuenta_cobro.usuario_id)
       WHERE  cuenta_cobro.estado='I' AND usuario.usuario_id = '" . $usuarioId . "')
       UNION (SELECT
         cuenta_cobro.fecha_crea  fecha,
         (-cuenta_cobro.valor) valor,
         'RetirosPendientes'               tipo
       FROM cuenta_cobro
         INNER JOIN usuario ON (usuario.usuario_id = cuenta_cobro.usuario_id)
       WHERE  cuenta_cobro.estado='I' AND usuario.usuario_id = '" . $usuarioId . "')) movimientos ORDER BY movimientos.fecha DESC ;
";

                $sql="SELECT movimientos.fecha, movimientos.valor, movimientos.tipo
FROM ((SELECT CONCAT(it_transaccion.fecha_crea, ' ', it_transaccion.hora_crea) fecha,
              CASE
                  WHEN tipo = 'BET' THEN -valor
                  WHEN tipo = 'STAKEDECREASE' THEN valor
                  WHEN tipo = 'REFUND' THEN valor
                  WHEN tipo = 'WIN' THEN valor
                  WHEN tipo = 'NEWCREDIT' THEN valor
                  WHEN tipo = 'CASHOUT' THEN valor
                  WHEN tipo = 'NEWDEBIT' THEN -valor
                  ELSE 'Otro' END                                              valor,
              CASE
                  WHEN tipo = 'BET' THEN 'Apuestas'
                  WHEN tipo = 'STAKEDECREASE' THEN 'Disminución apuesta'
                  WHEN tipo = 'REFUND' THEN 'Devolución apuesta'
                  WHEN tipo = 'WIN' THEN 'Ganadoras'
                  WHEN tipo = 'NEWCREDIT' THEN 'Ajuste Premio'
                  WHEN tipo = 'CASHOUT' THEN 'Cashout'
                  WHEN tipo = 'NEWDEBIT' THEN 'Ajuste Premio'
                  ELSE 'Otro' END                                              tipo

       FROM it_transaccion
                INNER JOIN usuario ON (usuario.usuario_id = it_transaccion.usuario_id)
       WHERE usuario.usuario_id = '" . $usuarioId . "')

      UNION
      (SELECT usuario_recarga.fecha_crea fecha,
              usuario_recarga.valor      valor,
              'Depositos'                tipo
       FROM usuario_recarga
                INNER JOIN usuario ON (usuario.usuario_id = usuario_recarga.usuario_id)
       WHERE usuario.usuario_id = '" . $usuarioId . "')
      UNION
      (SELECT cuenta_cobro.fecha_crea fecha,
              (-cuenta_cobro.valor)   valor,
              'Retiros'               tipo
       FROM cuenta_cobro
                INNER JOIN usuario ON (usuario.usuario_id = cuenta_cobro.usuario_id)
       WHERE cuenta_cobro.estado = 'I'
         AND usuario.usuario_id = '" . $usuarioId . "')
      UNION
      (SELECT cuenta_cobro.fecha_crea fecha,
              (-cuenta_cobro.valor)   valor,
              'RetirosPendientes'     tipo
       FROM cuenta_cobro
                INNER JOIN usuario ON (usuario.usuario_id = cuenta_cobro.usuario_id)
       WHERE cuenta_cobro.estado = 'I'
         AND usuario.usuario_id = '" . $usuarioId . "')) movimientos
ORDER BY movimientos.fecha DESC;";

                $sql="SELECT movimientos.fecha, movimientos.valor, movimientos.tipo
FROM ((SELECT transsportsbook_api.fecha_crea fecha,
              CASE
                  WHEN tipo = 'BET' THEN -valor
                  WHEN tipo = 'STAKEDECREASE' THEN valor
                  WHEN tipo = 'REFUND' THEN valor
                  WHEN tipo = 'WIN' THEN valor
                  WHEN tipo = 'NEWCREDIT' THEN valor
                  WHEN tipo = 'CASHOUT' THEN valor
                  WHEN tipo = 'NEWDEBIT' THEN -valor
                  ELSE 0 END                                              valor,
/*              CASE
                  WHEN tipo = 'BET' THEN 'Apuestas'
                  WHEN tipo = 'STAKEDECREASE' THEN 'Disminución apuesta'
                  WHEN tipo = 'REFUND' THEN 'Devolución apuesta'
                  WHEN tipo = 'WIN' THEN 'Ganadoras'
                  WHEN tipo = 'NEWCREDIT' THEN 'Ajuste Premio'
                  WHEN tipo = 'CASHOUT' THEN 'Cashout'
                  WHEN tipo = 'NEWDEBIT' THEN 'Ajuste Premio'
                  ELSE 'Otro' END                                              tipo*/
                  tipo,
                  transsportsbook_api.transsport_id
                  

       FROM transsportsbook_api
                INNER JOIN usuario_mandante ON (usuario_mandante.usumandante_id = transsportsbook_api.usuario_id)
                INNER JOIN usuario ON (usuario.usuario_id = usuario_mandante.usuario_mandante)
       WHERE usuario.usuario_id = '" . $usuarioId . "')


      UNION
      
      

      SELECT transaccion_api.fecha_crea fecha,
             CASE
                 WHEN tipo LIKE '%DEBIT%' THEN valor
                 WHEN tipo LIKE '%CREDIT%' THEN valor
                 WHEN tipo LIKE '%ROLLBACK%' THEN valor
                 ELSE 0 END        valor,
             CASE
                 WHEN tipo LIKE '%DEBIT%' THEN 'DEBIT'
                 WHEN tipo LIKE '%CREDIT%' THEN 'CREDIT'
                 WHEN tipo LIKE '%ROLLBACK%' THEN 'ROLLBACK'
                 ELSE 'OTRO' END        tipo,
                 transaccion_api.transapi_id


      FROM transaccion_api
               INNER JOIN usuario_mandante ON (usuario_mandante.usumandante_id = transaccion_api.usuario_id)
               INNER JOIN usuario ON (usuario.usuario_id = usuario_mandante.usuario_mandante)
      WHERE usuario.usuario_id = '" . $usuarioId . "'

      UNION

      (SELECT usuario_recarga.fecha_crea fecha,
              usuario_recarga.valor      valor,
              'Depositos'                tipo,
              usuario_recarga.recarga_id
       FROM usuario_recarga
                INNER JOIN usuario ON (usuario.usuario_id = usuario_recarga.usuario_id)
       WHERE usuario.usuario_id = '" . $usuarioId . "')
      UNION
      (SELECT cuenta_cobro.fecha_crea fecha,
              (-cuenta_cobro.valor)   valor,
              'Retiros'               tipo,
              cuenta_cobro.cuenta_id
       FROM cuenta_cobro
                INNER JOIN usuario ON (usuario.usuario_id = cuenta_cobro.usuario_id)
       WHERE cuenta_cobro.estado = 'I'
         AND usuario.usuario_id = '" . $usuarioId . "')
      UNION
      (SELECT cuenta_cobro.fecha_crea fecha,
              (-cuenta_cobro.valor)   valor,
              'RetirosPendientes'     tipo,
              cuenta_cobro.cuenta_id
       FROM cuenta_cobro
                INNER JOIN usuario ON (usuario.usuario_id = cuenta_cobro.usuario_id)
       WHERE cuenta_cobro.estado = 'I'
         AND usuario.usuario_id = '" . $usuarioId . "')) movimientos
ORDER BY movimientos.fecha DESC;";
                break;
        }

        if ($tipo != "") {
            $where = $where . " AND ";
        }


        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : 0, "data" : ' . json_encode($result) . '}';

        return $json;
    }

    /**
     * Obtener un resumen para el usuario identificado
     * con el id pasado como parámetro
     *
     * @param String $value usuario_id requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryUsuarioTotalResume($usuarioId = "")
    {


        $sql = "SELECT movimientos.valor,movimientos.tipo FROM ((SELECT
   SUM(it_ticket_enc.vlr_apuesta) valor,
   'Apuestas'                tipo
 FROM it_ticket_enc
   INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
 WHERE usuario.usuario_id = '" . $usuarioId . "')
UNION (SELECT
   SUM(it_ticket_enc.vlr_premio) valor,
         'Ganadoras'               tipo
       FROM it_ticket_enc
         INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
       WHERE it_ticket_enc.premiado = 'S' AND usuario.usuario_id = '" . $usuarioId . "')
UNION (SELECT
         SUM(usuario_recarga.valor) valor,
         'Depositos'               tipo
       FROM usuario_recarga
         INNER JOIN usuario ON (usuario.usuario_id = usuario_recarga.usuario_id)
       WHERE  usuario.usuario_id = '" . $usuarioId . "')
UNION (SELECT
         SUM(cuenta_cobro.valor) valor,
         'Retiros'               tipo
       FROM cuenta_cobro
         INNER JOIN usuario ON (usuario.usuario_id = cuenta_cobro.usuario_id)
       WHERE  cuenta_cobro.estado='I' AND usuario.usuario_id = '" . $usuarioId . "')
       UNION (SELECT
         SUM(cuenta_cobro.valor) valor,
         'RetirosPendientes'               tipo
       FROM cuenta_cobro
         INNER JOIN usuario ON (usuario.usuario_id = cuenta_cobro.usuario_id)
       WHERE  cuenta_cobro.estado='I' AND usuario.usuario_id = '" . $usuarioId . "')) movimientos;
";


        $ConfigurationEnvironment = new ConfigurationEnvironment();

        if($ConfigurationEnvironment->isDevelopment()){
            $sql="SELECT movimientos.fecha, SUM(movimientos.valor) valor, movimientos.tipo
FROM ((SELECT transsportsbook_api.fecha_crea fecha,
              CASE
                  WHEN tipo = 'BET' THEN valor
                  WHEN tipo = 'STAKEDECREASE' THEN valor
                  WHEN tipo = 'REFUND' THEN valor
                  WHEN tipo = 'WIN' THEN valor
                  WHEN tipo = 'NEWCREDIT' THEN valor
                  WHEN tipo = 'CASHOUT' THEN valor
                  WHEN tipo = 'NEWDEBIT' THEN valor
                  ELSE 0 END                                              valor,
/*              CASE
                  WHEN tipo = 'BET' THEN 'Apuestas'
                  WHEN tipo = 'STAKEDECREASE' THEN 'Disminución apuesta'
                  WHEN tipo = 'REFUND' THEN 'Devolución apuesta'
                  WHEN tipo = 'WIN' THEN 'Ganadoras'
                  WHEN tipo = 'NEWCREDIT' THEN 'Ajuste Premio'
                  WHEN tipo = 'CASHOUT' THEN 'Cashout'
                  WHEN tipo = 'NEWDEBIT' THEN 'Ajuste Premio'
                  ELSE 'Otro' END                                              tipo*/
                  tipo,
                  transsportsbook_api.transsport_id
                  

       FROM transsportsbook_api
                INNER JOIN usuario_mandante ON (usuario_mandante.usumandante_id = transsportsbook_api.usuario_id)
                INNER JOIN usuario ON (usuario.usuario_id = usuario_mandante.usuario_mandante)
       WHERE usuario.usuario_id = '" . $usuarioId . "' AND transsportsbook_api.respuesta_codigo='OK')
      UNION

      SELECT transaccion_api.fecha_crea fecha,
             CASE
                 WHEN tipo LIKE '%DEBIT%' THEN valor
                 WHEN tipo LIKE '%CREDIT%' THEN valor
                 WHEN tipo LIKE '%ROLLBACK%' THEN valor
                 ELSE 0 END        valor,
             CASE
                 WHEN tipo LIKE '%DEBIT%' THEN 'DEBIT'
                 WHEN tipo LIKE '%CREDIT%' THEN 'CREDIT'
                 WHEN tipo LIKE '%ROLLBACK%' THEN 'ROLLBACK'
                 ELSE 'OTRO' END        tipo,
                 transaccion_api.transapi_id


      FROM transaccion_api
               INNER JOIN usuario_mandante ON (usuario_mandante.usumandante_id = transaccion_api.usuario_id)
               INNER JOIN usuario ON (usuario.usuario_id = usuario_mandante.usuario_mandante)
      WHERE usuario.usuario_id = '" . $usuarioId . "'

      UNION

      (SELECT usuario_recarga.fecha_crea fecha,
              usuario_recarga.valor      valor,
              'Depositos'                tipo,
              usuario_recarga.recarga_id
       FROM usuario_recarga
                INNER JOIN usuario ON (usuario.usuario_id = usuario_recarga.usuario_id)
       WHERE usuario.usuario_id = '" . $usuarioId . "')
      UNION
      (SELECT cuenta_cobro.fecha_crea fecha,
              (cuenta_cobro.valor)   valor,
              'Retiros'               tipo,
              cuenta_cobro.cuenta_id
       FROM cuenta_cobro
                INNER JOIN usuario ON (usuario.usuario_id = cuenta_cobro.usuario_id)
       WHERE cuenta_cobro.estado = 'I'
         AND usuario.usuario_id = '" . $usuarioId . "')
      UNION
      (SELECT cuenta_cobro.fecha_crea fecha,
              (cuenta_cobro.valor)   valor,
              'RetirosPendientes'     tipo,
              cuenta_cobro.cuenta_id
       FROM cuenta_cobro
                INNER JOIN usuario ON (usuario.usuario_id = cuenta_cobro.usuario_id)
       WHERE cuenta_cobro.estado = 'A'
         AND usuario.usuario_id = '" . $usuarioId . "')) movimientos
GROUP BY movimientos.tipo
ORDER BY movimientos.fecha DESC;";

        }else{
            $sql="SELECT movimientos.fecha, SUM(movimientos.valor) valor, movimientos.tipo
FROM ((SELECT it_transaccion.fecha_crea fecha,
              CASE
                  WHEN tipo = 'BET' THEN valor
                  WHEN tipo = 'STAKEDECREASE' THEN valor
                  WHEN tipo = 'REFUND' THEN valor
                  WHEN tipo = 'WIN' THEN valor
                  WHEN tipo = 'NEWCREDIT' THEN valor
                  WHEN tipo = 'CASHOUT' THEN valor
                  WHEN tipo = 'NEWDEBIT' THEN valor
                  ELSE 0 END                                              valor,
/*              CASE
                  WHEN tipo = 'BET' THEN 'Apuestas'
                  WHEN tipo = 'STAKEDECREASE' THEN 'Disminución apuesta'
                  WHEN tipo = 'REFUND' THEN 'Devolución apuesta'
                  WHEN tipo = 'WIN' THEN 'Ganadoras'
                  WHEN tipo = 'NEWCREDIT' THEN 'Ajuste Premio'
                  WHEN tipo = 'CASHOUT' THEN 'Cashout'
                  WHEN tipo = 'NEWDEBIT' THEN 'Ajuste Premio'
                  ELSE 'Otro' END                                              tipo*/
                  tipo,
                  it_transaccion.transaccion_id
                  

       FROM it_transaccion
                INNER JOIN usuario ON (usuario.usuario_id = it_transaccion.usuario_id)
       WHERE usuario.usuario_id = '" . $usuarioId . "')
      UNION

      SELECT transaccion_api.fecha_crea fecha,
             CASE
                 WHEN tipo LIKE '%DEBIT%' THEN valor
                 WHEN tipo LIKE '%CREDIT%' THEN valor
                 WHEN tipo LIKE '%ROLLBACK%' THEN valor
                 ELSE 0 END        valor,
             CASE
                 WHEN tipo LIKE '%DEBIT%' THEN 'DEBIT'
                 WHEN tipo LIKE '%CREDIT%' THEN 'CREDIT'
                 WHEN tipo LIKE '%ROLLBACK%' THEN 'ROLLBACK'
                 ELSE 'OTRO' END        tipo,
                 transaccion_api.transapi_id


      FROM transaccion_api
               INNER JOIN usuario_mandante ON (usuario_mandante.usumandante_id = transaccion_api.usuario_id)
               INNER JOIN usuario ON (usuario.usuario_id = usuario_mandante.usuario_mandante)
      WHERE usuario.usuario_id = '" . $usuarioId . "' and respuesta_codigo='OK'

      UNION

      (SELECT usuario_recarga.fecha_crea fecha,
              usuario_recarga.valor      valor,
              'Depositos'                tipo,
              usuario_recarga.recarga_id
       FROM usuario_recarga
                INNER JOIN usuario ON (usuario.usuario_id = usuario_recarga.usuario_id)
       WHERE usuario.usuario_id = '" . $usuarioId . "')
      UNION
      (SELECT cuenta_cobro.fecha_crea fecha,
              (cuenta_cobro.valor+cuenta_cobro.impuesto)   valor,
              'Retiros'               tipo,
              cuenta_cobro.cuenta_id
       FROM cuenta_cobro
                INNER JOIN usuario ON (usuario.usuario_id = cuenta_cobro.usuario_id)
       WHERE cuenta_cobro.estado = 'I'
         AND usuario.usuario_id = '" . $usuarioId . "')
      UNION
      (SELECT cuenta_cobro.fecha_crea fecha,
              (cuenta_cobro.valor)   valor,
              'RetirosPendientes'     tipo,
              cuenta_cobro.cuenta_id
       FROM cuenta_cobro
                INNER JOIN usuario ON (usuario.usuario_id = cuenta_cobro.usuario_id)
       WHERE cuenta_cobro.estado = 'A'
         AND usuario.usuario_id = '" . $usuarioId . "')) movimientos
GROUP BY movimientos.tipo
ORDER BY movimientos.fecha DESC;";

        }

        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : 0, "data" : ' . json_encode($result) . '}';

        return $json;
    }


    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna login sea igual al valor pasado como parámetro
     *
     * @param String $value login requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByLogin($value)
    {
        $sql = 'DELETE FROM usuario WHERE login = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna clave sea igual al valor pasado como parámetro
     *
     * @param String $value clave requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByClave($value)
    {
        $sql = 'DELETE FROM usuario WHERE clave = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna nombre sea igual al valor pasado como parámetro
     *
     * @param String $value nombre requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByNombre($value)
    {
        $sql = 'DELETE FROM usuario WHERE nombre = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna estado sea igual al valor pasado como parámetro
     *
     * @param String $value estado requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByEstado($value)
    {
        $sql = 'DELETE FROM usuario WHERE estado = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna fecha_ult sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_ult requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByFechaUlt($value)
    {
        $sql = 'DELETE FROM usuario WHERE fecha_ult = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna clave_tv sea igual al valor pasado como parámetro
     *
     * @param String $value clave_tv requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByClaveTv($value)
    {
        $sql = 'DELETE FROM usuario WHERE clave_tv = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna estado_ant sea igual al valor pasado como parámetro
     *
     * @param String $value estado_ant requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByEstadoAnt($value)
    {
        $sql = 'DELETE FROM usuario WHERE estado_ant = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna intentos sea igual al valor pasado como parámetro
     *
     * @param String $value intentos requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByIntentos($value)
    {
        $sql = 'DELETE FROM usuario WHERE intentos = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna estado_esp sea igual al valor pasado como parámetro
     *
     * @param String $value estado_esp requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByEstadoEsp($value)
    {
        $sql = 'DELETE FROM usuario WHERE estado_esp = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna observ sea igual al valor pasado como parámetro
     *
     * @param String $value observ requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByObserv($value)
    {
        $sql = 'DELETE FROM usuario WHERE observ = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna fecha_crea sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_crea requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByFechaCrea($value)
    {
        $sql = 'DELETE FROM usuario WHERE fecha_crea = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna dir_ip sea igual al valor pasado como parámetro
     *
     * @param String $value dir_ip requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByDirIp($value)
    {
        $sql = 'DELETE FROM usuario WHERE dir_ip = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna eliminado sea igual al valor pasado como parámetro
     *
     * @param String $value eliminado requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByEliminado($value)
    {
        $sql = 'DELETE FROM usuario WHERE eliminado = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna mandante sea igual al valor pasado como parámetro
     *
     * @param String $value mandante requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByMandante($value)
    {
        $sql = 'DELETE FROM usuario WHERE mandante = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna usucrea_id sea igual al valor pasado como parámetro
     *
     * @param String $value usucrea_id requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByUsucreaId($value)
    {
        $sql = 'DELETE FROM usuario WHERE usucrea_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna usumodif_id sea igual al valor pasado como parámetro
     *
     * @param String $value usumodif_id requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByUsumodifId($value)
    {
        $sql = 'DELETE FROM usuario WHERE usumodif_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna fecha_modif sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_modif requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByFechaModif($value)
    {
        $sql = 'DELETE FROM usuario WHERE fecha_modif = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna clave_casino sea igual al valor pasado como parámetro
     *
     * @param String $value clave_casino requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByClaveCasino($value)
    {
        $sql = 'DELETE FROM usuario WHERE clave_casino = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna token_itainment sea igual al valor pasado como parámetro
     *
     * @param String $value token_itainment requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByTokenItainment($value)
    {
        $sql = 'DELETE FROM usuario WHERE token_itainment = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna fecha_clave sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_clave requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByFechaClave($value)
    {
        $sql = 'DELETE FROM usuario WHERE fecha_clave = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna retirado sea igual al valor pasado como parámetro
     *
     * @param String $value retirado requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByRetirado($value)
    {
        $sql = 'DELETE FROM usuario WHERE retirado = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna fecha_retiro sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_retiro requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByFechaRetiro($value)
    {
        $sql = 'DELETE FROM usuario WHERE fecha_retiro = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna hora_retiro sea igual al valor pasado como parámetro
     *
     * @param String $value hora_retiro requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByHoraRetiro($value)
    {
        $sql = 'DELETE FROM usuario WHERE hora_retiro = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna usuretiro_id sea igual al valor pasado como parámetro
     *
     * @param String $value usuretiro_id requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByUsuretiroId($value)
    {
        $sql = 'DELETE FROM usuario WHERE usuretiro_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna bloqueo_ventas sea igual al valor pasado como parámetro
     *
     * @param String $value bloqueo_ventas requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByBloqueoVentas($value)
    {
        $sql = 'DELETE FROM usuario WHERE bloqueo_ventas = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna info_equipo sea igual al valor pasado como parámetro
     *
     * @param String $value info_equipo requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByInfoEquipo($value)
    {
        $sql = 'DELETE FROM usuario WHERE info_equipo = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna estado_jugador sea igual al valor pasado como parámetro
     *
     * @param String $value estado_jugador requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByEstadoJugador($value)
    {
        $sql = 'DELETE FROM usuario WHERE estado_jugador = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna tokenCasino sea igual al valor pasado como parámetro
     *
     * @param String $value tokenCasino requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByTokenCasino($value)
    {
        $sql = 'DELETE FROM usuario WHERE tokenCasino = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna sponsor_id sea igual al valor pasado como parámetro
     *
     * @param String $value sponsor_id requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteBySponsorId($value)
    {
        $sql = 'DELETE FROM usuario WHERE sponsor_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna verif_correo sea igual al valor pasado como parámetro
     *
     * @param String $value verif_correo requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByVerifCorreo($value)
    {
        $sql = 'DELETE FROM usuario WHERE verif_correo = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna pais_id sea igual al valor pasado como parámetro
     *
     * @param String $value pais_id requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByPaisId($value)
    {
        $sql = 'DELETE FROM usuario WHERE pais_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna moneda sea igual al valor pasado como parámetro
     *
     * @param String $value moneda requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByMoneda($value)
    {
        $sql = 'DELETE FROM usuario WHERE moneda = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna idioma sea igual al valor pasado como parámetro
     *
     * @param String $value idioma requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByIdioma($value)
    {
        $sql = 'DELETE FROM usuario WHERE idioma = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna permite_activareg sea igual al valor pasado como parámetro
     *
     * @param String $value permite_activareg requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByPermiteActivareg($value)
    {
        $sql = 'DELETE FROM usuario WHERE permite_activareg = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }


    /**
     * Crear y devolver un objeto del tipo Usuario
     * con los valores de una consulta sql
     *
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Objeto $usuario Usuario
     *
     * @access protected
     *
     */
    protected function readRow($row)
    {
        $usuario = new Usuario();
        $Helpers = new Helpers();

        $usuario->usuarioId = $row['usuario_id'];
        $usuario->login = $Helpers->set_custom_secret_key('SECRET_PASSPHRASE_LOGIN', true)->decode_data($row['login']);
        $usuario->clave = $row['clave'];
        $usuario->nombre = $Helpers->set_custom_secret_key('SECRET_PASSPHRASE_NAME', true)->decode_data($row['nombre']);
        $usuario->estado = $row['estado'];
        $usuario->fechaUlt = $row['fecha_ult'];
        $usuario->claveTv = $row['clave_tv'];
        $usuario->estadoAnt = $row['estado_ant'];
        $usuario->intentos = $row['intentos'];
        $usuario->estadoEsp = $row['estado_esp'];
        $usuario->observ = $row['observ'];
        $usuario->fechaCrea = $row['fecha_crea'];
        $usuario->dirIp = $row['dir_ip'];
        $usuario->eliminado = $row['eliminado'];
        $usuario->mandante = $row['mandante'];
        $usuario->usucreaId = $row['usucrea_id'];
        $usuario->usumodifId = $row['usumodif_id'];
        $usuario->fechaModif = $row['fecha_modif'];
        $usuario->claveCasino = $row['clave_casino'];
        $usuario->tokenItainment = $row['token_itainment'];
        $usuario->fechaClave = $row['fecha_clave'];
        $usuario->retirado = $row['retirado'];
        $usuario->fechaRetiro = $row['fecha_retiro'];
        $usuario->horaRetiro = $row['hora_retiro'];
        $usuario->usuretiroId = $row['usuretiro_id'];
        $usuario->bloqueoVentas = $row['bloqueo_ventas'];
        $usuario->infoEquipo = $row['info_equipo'];
        $usuario->estadoJugador = $row['estado_jugador'];
        $usuario->tokenCasino = $row['tokenCasino'];
        $usuario->sponsorId = $row['sponsor_id'];
        $usuario->verifCorreo = $row['verif_correo'];
        $usuario->paisId = $row['pais_id'];
        $usuario->moneda = $row['moneda'];
        $usuario->idioma = $row['idioma'];
        $usuario->permiteActivareg = $row['permite_activareg'];
        $usuario->test = $row['test'];
        $usuario->puntoventaId = $row['puntoventa_id'];
        $usuario->tiempoLimitedeposito = $row['tiempo_limitedeposito'];
        $usuario->tiempoAutoexclusion = $row['tiempo_autoexclusion'];
        $usuario->cambiosAprobacion = $row['cambios_aprobacion'];
        $usuario->timezone = $row['timezone'];
        $usuario->celular = $Helpers->set_custom_secret_key('SECRET_PASSPHRASE_PHONE', true)->decode_data($row['celular']);
        $usuario->origen = $row['origen'];
        $usuario->fechaActualizacion = $row['fecha_actualizacion'];
        $usuario->fechaCrea = $row['fecha_crea'];
        $usuario->documentoValidado = $row['documento_validado'];
        $usuario->fechaDocvalido = $row['fecha_docvalido'];
        $usuario->usuDocvalido = $row['usu_docvalido'];
        $usuario->estadoValida = $row['estado_valida'];
        $usuario->usuvalidaId = $row['usuvalida_id'];
        $usuario->fechaValida = $row['fecha_valida'];
        $usuario->contingencia = $row['contingencia'];
        $usuario->contingenciaDeportes = $row['contingencia_deportes'];
        $usuario->contingenciaCasino = $row['contingencia_casino'];
        $usuario->contingenciaCasvivo = $row['contingencia_casvivo'];
        $usuario->contingenciaVirtuales = $row['contingencia_virtuales'];
        $usuario->contingenciaPoker = $row['contingencia_poker'];

        $usuario->contingenciaRetiro = $row['contingencia_retiro'];
        $usuario->contingenciaDeposito = $row['contingencia_deposito'];

        $usuario->restriccionIp = $row['restriccion_ip'];
        $usuario->ubicacionLongitud = $row['ubicacion_longitud'];
        $usuario->ubicacionLatitud = $row['ubicacion_latitud'];
        $usuario->usuarioIp = $row['usuario_ip'];
        $usuario->tokenGoogle = $row['token_google'];
        $usuario->tokenLocal = $row['token_local'];
        $usuario->saltGoogle = $row['salt_google'];
        $usuario->monedaReporte = $row['moneda_reporte'];
        $usuario->verifcedulaAnt = $row['verifcedula_ant'];
        $usuario->verifcedulaPost = $row['verifcedula_post'];
        //$usuario->verifFotoUsuario = $row['veriffoto_usuario'];
        $usuario->verifFotoUsuario = '';
        $usuario->creditosAfiliacion = $row['creditos_afiliacion'];
        $usuario->fechaPrimerdeposito = $row['fecha_primerdeposito'];
        $usuario->montoPrimerdeposito = $row['monto_primerdeposito'];
        $usuario->fechaCierrecaja = $row['fecha_cierrecaja'];
        $usuario->skype = $row['skype'];
        $usuario->plataforma = $row['plataforma'];
        $usuario->maximaComision = $row['maxima_comision'];

        $usuario->tiempoComision = $row['tiempo_comision'];

        $usuario->arrastraNegativo = $row['arrastra_negativo'];
        $usuario->tokenQuisk = $row['token_quisk'];
        $usuario->estadoImport = $row['estado_import'];

        $usuario->verifCelular = $row['verif_celular'];
        $usuario->fechaVerifCelular = $row['fecha_verif_celular'];


        $usuario->billeteraId = $row['billetera_id'];

        $usuario->puntosLealtad = 0;
        $usuario->nivelLealtad = 0;
        $usuario->verifDomicilio = $row['verifdomicilio'];
        $usuario->puntosAexpirar = 0;
        $usuario->puntosExpirados = 0;
        $usuario->puntosRedimidos = 0;

        if($_ENV["NOLOYALTY"] ==null ) {

            if ($row['usuario_id'] != '') {

                $sqlLealtad = 'SELECT * FROM usuario_puntoslealtad WHERE usuario_id = ?';
                $sqlQueryLealtad = new SqlQuery($sqlLealtad);
                $sqlQueryLealtad->set($row['usuario_id']);

                $tabLealtad = QueryExecutor::execute($this->transaction, $sqlQueryLealtad);
                $rowLealtad = $tabLealtad[0];

                if ($rowLealtad != null) {
                    $usuario->puntosLealtad = $rowLealtad['puntos_lealtad'];
                    $usuario->nivelLealtad = $rowLealtad['nivel_lealtad'];
                    $usuario->puntosAexpirar = $rowLealtad['puntos_aexpirar'];
                    $usuario->puntosExpirados = $rowLealtad['puntos_expirados'];
                    $usuario->puntosRedimidos = $rowLealtad['puntos_redimidos'];

                }
            }
        }


        $usuario->pagoComisiones = $row['pago_comisiones'];
        $usuario->equipoId = $row['equipo_id'];
        $usuario->verificado = $row['verificado'];
        $usuario->fechaVerificado = $row['fecha_verificado'];
        $usuario->permite_enviarpublicidad = $row['permite_enviopublicidad'];
        $usuario->accountIdJumio = $row['account_id_jumio'];
        return $usuario;
    }

    /**
     * Ejecutar una consulta sql y devolver los datos
     * como un arreglo asociativo
     *
     *
     * @param String $sqlQuery consulta sql
     *
     * @return Array $ret arreglo indexado
     *
     * @access protected
     *
     */
    protected function getList($sqlQuery)
    {
        $tab = QueryExecutor::execute($this->transaction, $sqlQuery);
        $ret = array();
        for ($i = 0; $i < oldCount($tab); $i++) {
            $ret[$i] = $this->readRow($tab[$i]);
        }
        return $ret;
    }

    /**
     * Ejecutar una consulta sql y devolver el resultado como un arreglo
     *
     *
     * @param String $sqlQuery consulta sql
     *
     * @return Array $ resultado de la ejecución
     *
     * @access protected
     *
     */
    protected function getRow($sqlQuery)
    {
        $tab = QueryExecutor::execute($this->transaction, $sqlQuery);
        if (oldCount($tab) == 0) {
            return null;
        }
        return $this->readRow($tab[0]);
    }

    /**
     * Ejecutar una consulta sql
     *
     *
     * @param String $sqlQuery consulta sql
     *
     * @return Array $ resultado de la ejecución
     *
     * @access protected
     *
     */
    protected function execute2($sqlQuery)
    {
        return QueryExecutor::execute2($this->transaction, $sqlQuery);
    }

    /**
     * Ejecutar una consulta sql
     *
     *
     * @param String $sqlQuery consulta sql
     *
     * @return Array $ resultado de la ejecución
     *
     * @access protected
     *
     */
    protected function execute($sqlQuery)
    {
        return QueryExecutor::execute($this->transaction, $sqlQuery);
    }


    /**
     * Ejecutar una consulta sql como update
     *
     *
     * @param String $sqlQuery consulta sql
     *
     * @return Array $ resultado de la ejecución
     *
     * @access protected
     *
     */
    protected function executeUpdate($sqlQuery)
    {
        return QueryExecutor::executeUpdate($this->transaction, $sqlQuery);
    }

    /**
     * Ejecutar una consulta sql como select
     *
     *
     * @param String $sqlQuery consulta sql
     *
     * @return Array $ resultado de la ejecución
     *
     * @access protected
     *
     */
    protected function querySingleResult($sqlQuery)
    {
        return QueryExecutor::queryForString($this->transaction, $sqlQuery);
    }

    /**
     * Ejecutar una consulta sql como insert
     *
     *
     * @param String $sqlQuery consulta sql
     *
     * @return Array $ resultado de la ejecución
     *
     * @access protected
     *
     */
    protected function executeInsert($sqlQuery)
    {
        return QueryExecutor::executeInsert($this->transaction, $sqlQuery);
    }
}
