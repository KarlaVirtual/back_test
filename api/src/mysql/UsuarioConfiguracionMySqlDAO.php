<?php namespace Backend\mysql;

use Backend\dao\UsuarioConfiguracionDAO;
use Backend\dto\Clasificador;
use Backend\dto\Helpers;
use Backend\dto\UsuarioConfiguracion;
use Backend\sql\QueryExecutor;
use Backend\sql\SqlQuery;
use Backend\sql\Transaction;

/**
 * Clase 'UsuarioConfiguracionMySqlDAO'
 *
 * Esta clase provee las consultas del modelo o tabla 'UsuarioConfiguracion'
 *
 * Ejemplo de uso:
 * $UsuarioConfiguracionMySqlDAO = new UsuarioConfiguracionMySqlDAO();
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class UsuarioConfiguracionMySqlDAO implements UsuarioConfiguracionDAO
{


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
        $sql = 'SELECT * FROM usuario_configuracion WHERE usuconfig_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($id);
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
        $sql = 'SELECT * FROM usuario_configuracion';
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
        $sql = 'SELECT * FROM usuario_configuracion ORDER BY ' . $orderColumn;
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }


    /**
     * Verificar si el usuario tiene limites de deposito y excede estos con el nuevo deposito
     *
     * @param String $orderColumn nombre de la columna
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryVerifiyLimitesDeposito(UsuarioConfiguracion $UsuarioConfiguracion, $valor)
    {

        $Clasificador = new Clasificador("", "LIMITEDEPOSITOSIMPLE");

        $sql = "SELECT count(*) FROM usuario_configuracion WHERE tipo = '" . $Clasificador->getClasificadorId() . "' AND usuario_id='" . $UsuarioConfiguracion->getUsuarioId() . "'  AND estado='A' AND valor < '" . $valor . "'";
        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);

        $count = $count[0]['.count(*)'];

        if ($count > 0) {
            return 20008;
            return $Clasificador->getClasificadorId();
        }

        // $Clasificador = new Clasificador("", "LIMITEDEPOSITODIARIO");

        $sql = "SELECT COUNT(*) FROM usuario_configuracion INNER JOIN (SELECT SUM(usuario_recarga.valor) valor,usuario_id FROM usuario_recarga WHERE usuario_id ='" . $UsuarioConfiguracion->getUsuarioId() . "'   AND date_format(fecha_crea, '%Y-%m-%d') =  date_format(now(), '%Y-%m-%d') )data ON (usuario_configuracion.usuario_id = data.usuario_id) WHERE tipo = '" . $Clasificador->getClasificadorId() . "' AND usuario_configuracion.usuario_id='" . $UsuarioConfiguracion->getUsuarioId() . "'  AND estado='A' AND usuario_configuracion.valor < data.valor + '" . $valor . "'";
        $fechaModif = "SELECT usuario_configuracion.fecha_modif as fechaModif, clasificador.abreviado as abreviado FROM usuario_configuracion INNER JOIN clasificador ON ( clasificador.clasificador_id = usuario_configuracion.tipo) WHERE usuario_configuracion.usuario_id ='" . $UsuarioConfiguracion->getUsuarioId() . "' AND usuario_configuracion.estado= 'A'";

        $sqlQuery = new SqlQuery($fechaModif);
        $fechaModif = $this->execute2($sqlQuery);
        $fechaModifDiario = "";
        $fechaModifDiario2 = "";
        $fechaModifSemana = "";
        $fechaModifSemana2 = "";
        $fechaModifMensual = "";
        $fechaModifMensual2 = "";
        $fechaModifAnual = "";
        $fechaModifAnual2 = "";

        foreach ($fechaModif as $fecha) {

            if ($fecha['clasificador.abreviado'] == "LIMITEDEPOSITODIARIO") {

                $diff = ((time() - strtotime($fecha['usuario_configuracion.fechaModif'])) % 86400);
                $fechaModifDiario = date("Y-m-d H:i:s", strtotime("- $diff sec"));
                $diff2 = ((time() - strtotime($fecha['usuario_configuracion.fechaModif'])) % 3600);
                $fechaModifDiario2 = date("Y-m-d H:i:s", strtotime("- $diff2 sec"));

            }

            if ($fecha['clasificador.abreviado'] == "LIMITEDEPOSITOSEMANA") {
                $diff = ((time() - strtotime($fecha['usuario_configuracion.fechaModif'])) % 604800);
                $fechaModifSemana = date("Y-m-d H:i:s", strtotime("- $diff sec"));
                $diff2 = ((time() - strtotime($fecha['usuario_configuracion.fechaModif'])) % 3600);
                $fechaModifSemana2 = date("Y-m-d H:i:s", strtotime("- $diff2 sec"));
            }

            if ($fecha['clasificador.abreviado'] == "LIMITEDEPOSITOMENSUAL") {
                $diff = ((time() - strtotime($fecha['usuario_configuracion.fechaModif'])) % 2592000);
                $fechaModifMensual = date("Y-m-d H:i:s", strtotime("- $diff sec"));
                $diff2 = ((time() - strtotime($fecha['usuario_configuracion.fechaModif'])) % 3600);
                $fechaModifMensual2 = date("Y-m-d H:i:s", strtotime("- $diff2 sec"));
            }
            if ($fecha['clasificador.abreviado'] == "LIMITEDEPOSITOANUAL") {
                $diff = ((time() - strtotime($fecha['usuario_configuracion.fechaModif'])) % 31536000);
                $fechaModifAnual = date("Y-m-d H:i:s", strtotime("- $diff sec"));
                $diff2 = ((time() - strtotime($fecha['usuario_configuracion.fechaModif'])) % 3600);
                $fechaModifAnual2 = date("Y-m-d H:i:s", strtotime("- $diff2 sec"));
            }

        }

        if ($fechaModifDiario != '' && $fechaModifDiario2 != '') {

            $sql = "SELECT count(*)
FROM (SELECT SUM(valor) valor,valorconfig
      FROM (
       SELECT 0 valor, usuario_configuracion.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_configuracion
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMITEDEPOSITODIARIO' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = " . $UsuarioConfiguracion->getUsuarioId() . "
              AND usuario_configuracion.estado = 'A'

        UNION      
      SELECT SUM(usuario_configuracion_resumen.valor) valor, usuario_configuracion.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_configuracion_resumen
                     INNER JOIN usuario_configuracion
                                ON (usuario_configuracion.usuconfig_id =
                                    usuario_configuracion_resumen.usuconfig_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMITEDEPOSITODIARIO' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = " . $UsuarioConfiguracion->getUsuarioId() . "
              AND usuario_configuracion.estado = 'A'
              AND usuario_configuracion_resumen.fecha_crea >= '$fechaModifDiario' GROUP BY usuario_configuracion.usuario_id

            UNION

            SELECT SUM(usuario_recarga.valor) valor, usuario_recarga.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_recarga
                     INNER JOIN usuario_configuracion
                                ON (usuario_configuracion.usuario_id = usuario_recarga.usuario_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMITEDEPOSITODIARIO' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = " . $UsuarioConfiguracion->getUsuarioId() . "
              AND usuario_configuracion.estado = 'A'
              AND usuario_recarga.fecha_crea >= '$fechaModifDiario2' GROUP BY usuario_configuracion.usuario_id
                              
            UNION

            SELECT SUM(-usuario_recarga.valor) valor, usuario_recarga.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_recarga
                     INNER JOIN usuario_configuracion
                                ON (usuario_configuracion.usuario_id = usuario_recarga.usuario_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMITEDEPOSITODIARIO' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = " . $UsuarioConfiguracion->getUsuarioId() . "
              AND usuario_configuracion.estado = 'A'
              AND usuario_recarga.estado = 'I'
              AND usuario_recarga.fecha_elimina >= '$fechaModifDiario2' GROUP BY usuario_configuracion.usuario_id
           ) data2) data

WHERE data.valorconfig < valor +  '" . $valor . "'
";


            $sqlQuery = new SqlQuery($sql);

            $count = $this->execute2($sqlQuery);
            $count = $count[0]['.count(*)'];

            if ($count > 0) {
                return 20009;
                return $Clasificador->getClasificadorId();
            }
        }
        if ($fechaModifSemana != '' && $fechaModifSemana2 != '') {
            //$Clasificador = new Clasificador("", "LIMITEDEPOSITOSEMANA");

            $sql = "SELECT count(*) FROM usuario_configuracion INNER JOIN (SELECT SUM(usuario_recarga.valor) valor,usuario_id FROM usuario_recarga WHERE usuario_id ='" . $UsuarioConfiguracion->getUsuarioId() . "'   AND date_format(fecha_crea, '%Y-%m-%d') >=  date_format(DATE_SUB(now(), INTERVAL 7 DAY), '%Y-%m-%d') )data ON (usuario_configuracion.usuario_id = data.usuario_id) WHERE tipo = '" . $Clasificador->getClasificadorId() . "' AND usuario_configuracion.usuario_id='" . $UsuarioConfiguracion->getUsuarioId() . "'  AND estado='A' AND usuario_configuracion.valor < data.valor + '" . $valor . "'";
            $sql = "SELECT count(*)
FROM (SELECT SUM(valor) valor,valorconfig
      FROM (
       SELECT 0 valor, usuario_configuracion.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_configuracion
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMITEDEPOSITOSEMANA' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = " . $UsuarioConfiguracion->getUsuarioId() . "
              AND usuario_configuracion.estado = 'A'

        UNION   
        SELECT SUM(usuario_configuracion_resumen.valor) valor, usuario_configuracion.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_configuracion_resumen
                     INNER JOIN usuario_configuracion
                                ON (usuario_configuracion.usuconfig_id =
                                    usuario_configuracion_resumen.usuconfig_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMITEDEPOSITOSEMANA' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = " . $UsuarioConfiguracion->getUsuarioId() . "
              AND usuario_configuracion.estado = 'A'
              AND usuario_configuracion_resumen.fecha_crea >= '$fechaModifSemana' GROUP BY usuario_configuracion.usuario_id

            UNION

            SELECT SUM(usuario_recarga.valor) valor, usuario_recarga.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_recarga
                     INNER JOIN usuario_configuracion
                                ON (usuario_configuracion.usuario_id = usuario_recarga.usuario_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMITEDEPOSITOSEMANA' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = " . $UsuarioConfiguracion->getUsuarioId() . "
              AND usuario_configuracion.estado = 'A'
              AND usuario_recarga.fecha_crea >= '$fechaModifSemana2' GROUP BY usuario_configuracion.usuario_id
                              
            UNION

            SELECT SUM(-usuario_recarga.valor) valor, usuario_recarga.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_recarga
                     INNER JOIN usuario_configuracion
                                ON (usuario_configuracion.usuario_id = usuario_recarga.usuario_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMITEDEPOSITOSEMANA' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = " . $UsuarioConfiguracion->getUsuarioId() . "
              AND usuario_configuracion.estado = 'A'
              AND usuario_recarga.estado = 'I'
              AND usuario_recarga.fecha_elimina >= '$fechaModifSemana2' GROUP BY usuario_configuracion.usuario_id
           ) data2) data

WHERE data.valorconfig < valor +  '" . $valor . "'
";

            $sqlQuery = new SqlQuery($sql);

            $count = $this->execute2($sqlQuery);
            $count = $count[0]['.count(*)'];

            if ($count > 0) {
                return 20010;
                return $Clasificador->getClasificadorId();
            }
        }

        //$Clasificador = new Clasificador("", "LIMITEDEPOSITOMENSUAL");
        if ($fechaModifMensual != '' && $fechaModifMensual2 != '') {

            $sql = "SELECT count(*) FROM usuario_configuracion INNER JOIN (SELECT SUM(usuario_recarga.valor) valor,usuario_id FROM usuario_recarga WHERE usuario_id ='" . $UsuarioConfiguracion->getUsuarioId() . "'  AND date_format(fecha_crea, '%Y-%m-%d') >=  date_format(DATE_SUB(now(), INTERVAL 30 DAY), '%Y-%m-%d') )data ON (usuario_configuracion.usuario_id = data.usuario_id) WHERE tipo = '" . $Clasificador->getClasificadorId() . "' AND usuario_configuracion.usuario_id='" . $UsuarioConfiguracion->getUsuarioId() . "'  AND estado='A' AND usuario_configuracion.valor < data.valor + '" . $valor . "'";
            $sql = "SELECT count(*)
FROM (SELECT SUM(valor) valor,valorconfig
      FROM (
       SELECT 0 valor, usuario_configuracion.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_configuracion
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMITEDEPOSITOMENSUAL' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = " . $UsuarioConfiguracion->getUsuarioId() . "
              AND usuario_configuracion.estado = 'A'

        UNION   
        SELECT SUM(usuario_configuracion_resumen.valor) valor, usuario_configuracion.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_configuracion_resumen
                     INNER JOIN usuario_configuracion
                                ON (usuario_configuracion.usuconfig_id =
                                    usuario_configuracion_resumen.usuconfig_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMITEDEPOSITOMENSUAL' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = " . $UsuarioConfiguracion->getUsuarioId() . "
              AND usuario_configuracion.estado = 'A'
              AND usuario_configuracion_resumen.fecha_crea >= '$fechaModifMensual' GROUP BY usuario_configuracion.usuario_id

            UNION

            SELECT SUM(usuario_recarga.valor) valor, usuario_recarga.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_recarga
                     INNER JOIN usuario_configuracion
                                ON (usuario_configuracion.usuario_id = usuario_recarga.usuario_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMITEDEPOSITOMENSUAL' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = " . $UsuarioConfiguracion->getUsuarioId() . "
              AND usuario_configuracion.estado = 'A'
              AND usuario_recarga.fecha_crea >= '$fechaModifMensual2' GROUP BY usuario_configuracion.usuario_id
                              
            UNION

            SELECT SUM(-usuario_recarga.valor) valor, usuario_recarga.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_recarga
                     INNER JOIN usuario_configuracion
                                ON (usuario_configuracion.usuario_id = usuario_recarga.usuario_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMITEDEPOSITOMENSUAL' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = " . $UsuarioConfiguracion->getUsuarioId() . "
              AND usuario_configuracion.estado = 'A'
              AND usuario_recarga.estado = 'I'
              AND usuario_recarga.fecha_elimina >= '$fechaModifMensual2' GROUP BY usuario_configuracion.usuario_id
           ) data2) data

WHERE data.valorconfig < valor +  '" . $valor . "'
";

            $sqlQuery = new SqlQuery($sql);

            $count = $this->execute2($sqlQuery);
            $count = $count[0]['.count(*)'];

            if ($count > 0) {
                return 20011;
                return $Clasificador->getClasificadorId();
            }
        }

        if ($fechaModifAnual != '' && $fechaModifAnual2 != '') {


            $Clasificador = new Clasificador("", "LIMITEDEPOSITOANUAL");

            $sql = "SELECT count(*) FROM usuario_configuracion INNER JOIN (SELECT SUM(usuario_recarga.valor) valor,usuario_id FROM usuario_recarga WHERE usuario_id ='" . $UsuarioConfiguracion->getUsuarioId() . "'   AND date_format(fecha_crea, '%Y-%m-%d') >=  date_format(DATE_SUB(now(), INTERVAL 365 DAY), '%Y-%m-%d') )data ON (usuario_configuracion.usuario_id = data.usuario_id) WHERE tipo = '" . $Clasificador->getClasificadorId() . "' AND usuario_configuracion.usuario_id='" . $UsuarioConfiguracion->getUsuarioId() . "'  AND estado='A' AND usuario_configuracion.valor < data.valor + '" . $valor . "'";
            $sql = "SELECT count(*)
FROM (SELECT SUM(valor) valor,valorconfig
      FROM (
       SELECT 0 valor, usuario_configuracion.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_configuracion
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMITEDEPOSITOANUAL' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = " . $UsuarioConfiguracion->getUsuarioId() . "
              AND usuario_configuracion.estado = 'A'

        UNION   
        SELECT SUM(usuario_configuracion_resumen.valor) valor, usuario_configuracion.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_configuracion_resumen
                     INNER JOIN usuario_configuracion
                                ON (usuario_configuracion.usuconfig_id =
                                    usuario_configuracion_resumen.usuconfig_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMITEDEPOSITOANUAL' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = " . $UsuarioConfiguracion->getUsuarioId() . "
              AND usuario_configuracion.estado = 'A'
              AND usuario_configuracion_resumen.fecha_crea >= '$fechaModifAnual' GROUP BY usuario_configuracion.usuario_id

            UNION

            SELECT SUM(usuario_recarga.valor) valor, usuario_recarga.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_recarga
                     INNER JOIN usuario_configuracion
                                ON (usuario_configuracion.usuario_id = usuario_recarga.usuario_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMITEDEPOSITOANUAL' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = " . $UsuarioConfiguracion->getUsuarioId() . "
              AND usuario_configuracion.estado = 'A'
              AND usuario_recarga.fecha_crea >= '$fechaModifAnual2' GROUP BY usuario_configuracion.usuario_id
                              
            UNION

            SELECT SUM(-usuario_recarga.valor) valor, usuario_recarga.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_recarga
                     INNER JOIN usuario_configuracion
                                ON (usuario_configuracion.usuario_id = usuario_recarga.usuario_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMITEDEPOSITOANUAL' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = " . $UsuarioConfiguracion->getUsuarioId() . "
              AND usuario_configuracion.estado = 'A'
              AND usuario_recarga.estado = 'I'
              AND usuario_recarga.fecha_elimina >= '$fechaModifAnual2' GROUP BY usuario_configuracion.usuario_id
           ) data2) data

WHERE data.valorconfig < valor +  '" . $valor . "'
";

            $sqlQuery = new SqlQuery($sql);

            $count = $this->execute2($sqlQuery);
            $count = $count[0]['.count(*)'];

            if ($count > 0) {
                return 20012;
                return $Clasificador->getClasificadorId();
            }
        }


        return 0;
    }


    /**
     * Verifica los límites del casino para un usuario específico.
     *
     * @param UsuarioConfiguracion $UsuarioConfiguracion Objeto que contiene la configuración del usuario.
     * @param mixed $valor Valor a verificar contra los límites del casino.
     * @return int Código de resultado que indica si se ha superado algún límite.
     */
    public function queryVerifiyLimitesCasino33(UsuarioConfiguracion $UsuarioConfiguracion, $valor)
    {
        $msc = microtime(true);
        echo ("automations: " . $msc) . PHP_EOL;

        $Clasificador = new Clasificador("", "LIMAPUCASINOSIMPLE");

        $sql = "SELECT count(*) FROM usuario_configuracion WHERE tipo = '" . $Clasificador->getClasificadorId() . "' AND usuario_id='" . $UsuarioConfiguracion->getUsuarioId() . "'  AND estado='A' AND valor < '" . $valor . "'";
        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);

        $count = $count[0]['.count(*)'];

        if ($count > 0) {
            return 20013;
            return $Clasificador->getClasificadorId();
        }
        echo ("automations:1 " . (microtime(true) - $msc)) . PHP_EOL;
        $msc = microtime(true);

        // $Clasificador = new Clasificador("", "LIMAPUCASINODIARIO");

        // $sql = "SELECT COUNT(*) FROM usuario_configuracion INNER JOIN (SELECT SUM(usuario_recarga.valor) valor,usuario_id FROM usuario_recarga WHERE usuario_id ='" . $UsuarioConfiguracion->getUsuarioId() . "'   AND date_format(fecha_crea, '%Y-%m-%d') =  date_format(now(), '%Y-%m-%d') )data ON (usuario_configuracion.usuario_id = data.usuario_id) WHERE tipo = '" . $Clasificador->getClasificadorId() . "' AND usuario_configuracion.usuario_id='" . $UsuarioConfiguracion->getUsuarioId() . "'  AND estado='A' AND usuario_configuracion.valor < data.valor + '" . $valor . "'";
        $sql1 = "SELECT  DATE_SUB(now(), INTERVAL MOD( TIMESTAMPDIFF(SECOND, usuario_configuracion.fecha_modif, now()),  86400)SECOND) FROM usuario_configuracion INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINODIARIO' AND clasificador.clasificador_id = usuario_configuracion.tipo) WHERE usuario_configuracion.usuario_id ='" . $UsuarioConfiguracion->getUsuarioId() . "' AND usuario_configuracion.estado= 'A' AND usuario_configuracion.tipo = clasificador.clasificador_id";
        $sqlQuery = new SqlQuery($sql1);
        $fechaModif1 = $this->execute2($sqlQuery);

        echo ("automations:2 " . (microtime(true) - $msc)) . PHP_EOL;
        $msc = microtime(true);

        $fechaModif1 = $fechaModif1[0][0];
        $sql2 = "SELECT DATE_SUB(now(), INTERVAL MOD( TIMESTAMPDIFF(SECOND, usuario_configuracion.fecha_modif, now()),  3600)SECOND) FROM usuario_configuracion INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINODIARIO' AND clasificador.clasificador_id = usuario_configuracion.tipo) WHERE usuario_configuracion.usuario_id ='" . $UsuarioConfiguracion->getUsuarioId() . "' AND usuario_configuracion.estado= 'A' AND usuario_configuracion.tipo = clasificador.clasificador_id";
        $sqlQuery = new SqlQuery($sql2);
        $fechaModif2 = $this->execute2($sqlQuery);

        echo ("automations:3 " . (microtime(true) - $msc)) . PHP_EOL;
        $msc = microtime(true);

        $fechaModif2 = $fechaModif2[0][0];
        $sql = "SELECT count(*)
FROM (SELECT SUM(valor) valor,valorconfig
      FROM (
       SELECT 0 valor, usuario_configuracion.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_configuracion
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINODIARIO' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = " . $UsuarioConfiguracion->getUsuarioId() . "
              AND usuario_configuracion.estado = 'A'

        UNION  SELECT SUM(usuario_configuracion_resumen.valor) valor, usuario_configuracion.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_configuracion_resumen
                     INNER JOIN usuario_configuracion
                                ON (usuario_configuracion.usuconfig_id =
                                    usuario_configuracion_resumen.usuconfig_id)
                    INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINODIARIO' AND
                                                clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "'
              AND usuario_configuracion.estado = 'A' 
              AND usuario_configuracion_resumen.fecha_crea >= '$fechaModif1'

            UNION

            SELECT SUM(CASE
               WHEN transjuego_log.tipo LIKE ('%DEBIT%') THEN transjuego_log.valor
               WHEN transjuego_log.tipo LIKE ('ROLLBACK%') THEN transjuego_log.valor
               ELSE 0 END)                                                               valor, usuario_mandante.usuario_mandante, usuario_configuracion.valor valorconfig
            FROM transjuego_log
                     INNER JOIN transaccion_juego ON (transaccion_juego.transjuego_id = transjuego_log.transjuego_id)
                     INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id = usuario_mandante.usumandante_id)
                     INNER JOIN producto_mandante ON (producto_mandante.prodmandante_id = transaccion_juego.producto_id)
                     INNER JOIN producto ON (producto.producto_id = producto_mandante.producto_id)
                     INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id)
                     INNER JOIN usuario_configuracion ON (usuario_mandante.usuario_mandante = usuario_configuracion.usuario_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINODIARIO' AND clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_mandante.usumandante_id = '16'  AND proveedor.tipo ='CASINO'
              AND usuario_configuracion.estado = 'A'
              AND transjuego_log.fecha_crea >= '$fechaModif2'
                  
                              
           ) data2) data

WHERE data.valorconfig < valor +  '" . $valor . "'
";

        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
        $count = $count[0]['.count(*)'];

        echo ("automations:4 " . (microtime(true) - $msc)) . PHP_EOL;
        $msc = microtime(true);
        if ($count > 0) {
            return 20014;
            return $Clasificador->getClasificadorId();
        }

        //$Clasificador = new Clasificador("", "LIMAPUCASINOSEMANA");

        //$sql = "SELECT count(*) FROM usuario_configuracion INNER JOIN (SELECT SUM(usuario_recarga.valor) valor,usuario_id FROM usuario_recarga WHERE usuario_id ='" . $UsuarioConfiguracion->getUsuarioId() . "'   AND date_format(fecha_crea, '%Y-%m-%d') >=  date_format(DATE_SUB(now(), INTERVAL 7 DAY), '%Y-%m-%d') )data ON (usuario_configuracion.usuario_id = data.usuario_id) WHERE tipo = '" . $Clasificador->getClasificadorId() . "' AND usuario_configuracion.usuario_id='" . $UsuarioConfiguracion->getUsuarioId() . "'  AND estado='A' AND usuario_configuracion.valor < data.valor + '" . $valor . "'";
        $sql1 = "SELECT  DATE_SUB(now(), INTERVAL MOD( TIMESTAMPDIFF(SECOND, usuario_configuracion.fecha_modif, now()),  604800)SECOND) FROM usuario_configuracion INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOSEMANA' AND clasificador.clasificador_id = usuario_configuracion.tipo) WHERE usuario_configuracion.usuario_id ='" . $UsuarioConfiguracion->getUsuarioId() . "' AND usuario_configuracion.estado= 'A' AND usuario_configuracion.tipo = clasificador.clasificador_id";
        $sqlQuery = new SqlQuery($sql1);
        $fechaModif1 = $this->execute2($sqlQuery);

        echo ("automations:5 " . (microtime(true) - $msc)) . PHP_EOL;
        $msc = microtime(true);
        $fechaModif1 = $fechaModif1[0][0];
        $sql2 = "SELECT DATE_SUB(now(), INTERVAL MOD( TIMESTAMPDIFF(SECOND, usuario_configuracion.fecha_modif, now()),  3600)SECOND) FROM usuario_configuracion INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOSEMANA' AND clasificador.clasificador_id = usuario_configuracion.tipo) WHERE usuario_configuracion.usuario_id ='" . $UsuarioConfiguracion->getUsuarioId() . "' AND usuario_configuracion.estado= 'A' AND usuario_configuracion.tipo = clasificador.clasificador_id";
        $sqlQuery = new SqlQuery($sql2);
        $fechaModif2 = $this->execute2($sqlQuery);

        echo ("automations:6 " . (microtime(true) - $msc)) . PHP_EOL;
        $msc = microtime(true);
        $fechaModif2 = $fechaModif2[0][0];
        $sql = "SELECT count(*)
FROM (SELECT SUM(valor) valor,valorconfig
      FROM (SELECT 0 valor, usuario_configuracion.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_configuracion
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOSEMANA' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = " . $UsuarioConfiguracion->getUsuarioId() . "
              AND usuario_configuracion.estado = 'A'

        UNION
        SELECT SUM(usuario_configuracion_resumen.valor) valor, usuario_configuracion.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_configuracion_resumen
                     INNER JOIN usuario_configuracion
                                ON (usuario_configuracion.usuconfig_id =
                                    usuario_configuracion_resumen.usuconfig_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOSEMANA' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "'
              AND usuario_configuracion.estado = 'A'
              AND usuario_configuracion_resumen.fecha_crea >= '$fechaModif1'

            UNION

            SELECT SUM(CASE
               WHEN transjuego_log.tipo LIKE ('%DEBIT%') THEN transjuego_log.valor
               WHEN transjuego_log.tipo LIKE ('%ROLLBACK%') THEN transjuego_log.valor
               ELSE 0 END)                                                               valor, usuario_mandante.usuario_mandante, usuario_configuracion.valor valorconfig
            FROM transjuego_log
                     INNER JOIN transaccion_juego ON (transaccion_juego.transjuego_id = transjuego_log.transjuego_id)
                     INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id = usuario_mandante.usumandante_id)
                     INNER JOIN producto_mandante ON (producto_mandante.prodmandante_id = transaccion_juego.producto_id)
                     INNER JOIN producto ON (producto.producto_id = producto_mandante.producto_id)
                     INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id)
                     INNER JOIN usuario_configuracion ON (usuario_mandante.usuario_mandante = usuario_configuracion.usuario_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOSEMANA' AND clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_mandante.usumandante_id = '16'  AND proveedor.tipo ='CASINO'
              AND usuario_configuracion.estado = 'A' 
              AND transjuego_log.fecha_crea >= '$fechaModif2'
                              
           ) data2) data

WHERE data.valorconfig < valor +  '" . $valor . "'
";

        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
        $count = $count[0]['.count(*)'];

        echo ("automations:7 " . (microtime(true) - $msc)) . PHP_EOL;
        $msc = microtime(true);
        if ($count > 0) {
            return 20015;
            return $Clasificador->getClasificadorId();
        }


        //$Clasificador = new Clasificador("", "LIMAPUCASINOMENSUAL");
        //$sql="SELECT count(*) FROM usuario_configuracion INNER JOIN (SELECT SUM(usuario_recarga.valor) valor,usuario_id FROM usuario_recarga WHERE usuario_id ='" . $UsuarioConfiguracion->getUsuarioId() . "'  AND date_format(fecha_crea, '%Y-%m-%d') >=  date_format(DATE_SUB(now(), INTERVAL 30 DAY), '%Y-%m-%d') )data ON (usuario_configuracion.usuario_id = data.usuario_id) WHERE tipo = '" . $Clasificador->getClasificadorId() . "' AND usuario_configuracion.usuario_id='" . $UsuarioConfiguracion->getUsuarioId() . "'  AND estado='A' AND usuario_configuracion.valor < data.valor + '" . $valor . "'";

        $sql1 = "SELECT  DATE_SUB(now(), INTERVAL MOD( TIMESTAMPDIFF(SECOND, usuario_configuracion.fecha_modif, now()),  2592000)SECOND) FROM usuario_configuracion INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOMENSUAL' AND clasificador.clasificador_id = usuario_configuracion.tipo) WHERE usuario_configuracion.usuario_id ='" . $UsuarioConfiguracion->getUsuarioId() . "' AND usuario_configuracion.estado= 'A' AND usuario_configuracion.tipo = clasificador.clasificador_id";
        $sqlQuery = new SqlQuery($sql1);
        $fechaModif1 = $this->execute2($sqlQuery);

        echo ("automations:8 " . (microtime(true) - $msc)) . PHP_EOL;
        $msc = microtime(true);
        $fechaModif1 = $fechaModif1[0][0];
        $sql2 = "SELECT DATE_SUB(now(), INTERVAL MOD( TIMESTAMPDIFF(SECOND, usuario_configuracion.fecha_modif, now()),  3600)SECOND) FROM usuario_configuracion INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOMENSUAL' AND clasificador.clasificador_id = usuario_configuracion.tipo) WHERE usuario_configuracion.usuario_id ='" . $UsuarioConfiguracion->getUsuarioId() . "' AND usuario_configuracion.estado= 'A' AND usuario_configuracion.tipo = clasificador.clasificador_id";
        $sqlQuery = new SqlQuery($sql2);
        $fechaModif2 = $this->execute2($sqlQuery);

        echo ("automations:9 " . (microtime(true) - $msc)) . PHP_EOL;
        $msc = microtime(true);
        $fechaModif2 = $fechaModif2[0][0];
        $sql = "SELECT count(*)
FROM (SELECT SUM(valor) valor,valorconfig
      FROM (SELECT 0 valor, usuario_configuracion.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_configuracion
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOMENSUAL' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = " . $UsuarioConfiguracion->getUsuarioId() . "
              AND usuario_configuracion.estado = 'A'

        UNION
        SELECT SUM(usuario_configuracion_resumen.valor) valor, usuario_configuracion.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_configuracion_resumen
                     INNER JOIN usuario_configuracion
                                ON (usuario_configuracion.usuconfig_id =
                                    usuario_configuracion_resumen.usuconfig_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOMENSUAL' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "'
              AND usuario_configuracion.estado = 'A'
              AND usuario_configuracion_resumen.fecha_crea >= '$fechaModif1'
                 

            UNION

            SELECT SUM(CASE
               WHEN transjuego_log.tipo LIKE ('%DEBIT%') THEN transjuego_log.valor
               WHEN transjuego_log.tipo LIKE ('%ROLLBACK%') THEN transjuego_log.valor
               ELSE 0 END)                                                               valor, usuario_mandante.usuario_mandante, usuario_configuracion.valor valorconfig
            FROM transjuego_log
                     INNER JOIN transaccion_juego ON (transaccion_juego.transjuego_id = transjuego_log.transjuego_id)
                     INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id = usuario_mandante.usumandante_id)
                     INNER JOIN producto_mandante ON (producto_mandante.prodmandante_id = transaccion_juego.producto_id)
                     INNER JOIN producto ON (producto.producto_id = producto_mandante.producto_id)
                     INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id)
                     INNER JOIN usuario_configuracion ON (usuario_mandante.usuario_mandante = usuario_configuracion.usuario_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOMENSUAL' AND clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_mandante.usumandante_id = '16'  AND proveedor.tipo ='CASINO'
              AND usuario_configuracion.estado = 'A' 
              AND transjuego_log.fecha_crea >= '$fechaModif2'
                              
           ) data2) data

WHERE data.valorconfig < valor +  '" . $valor . "'
";


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
        $count = $count[0]['.count(*)'];

        echo ("automations:10 " . (microtime(true) - $msc)) . PHP_EOL;
        $msc = microtime(true);
        if ($count > 0) {
            return 20016;
            return $Clasificador->getClasificadorId();
        }


        // $Clasificador = new Clasificador("", "LIMAPUCASINOANUAL");

        //$sql = "SELECT count(*) FROM usuario_configuracion INNER JOIN (SELECT SUM(usuario_recarga.valor) valor,usuario_id FROM usuario_recarga WHERE usuario_id ='" . $UsuarioConfiguracion->getUsuarioId() . "'   AND date_format(fecha_crea, '%Y-%m-%d') >=  date_format(DATE_SUB(now(), INTERVAL 365 DAY), '%Y-%m-%d') )data ON (usuario_configuracion.usuario_id = data.usuario_id) WHERE tipo = '" . $Clasificador->getClasificadorId() . "' AND usuario_configuracion.usuario_id='" . $UsuarioConfiguracion->getUsuarioId() . "'  AND estado='A' AND usuario_configuracion.valor < data.valor + '" . $valor . "'";
        $sql1 = "SELECT  DATE_SUB(now(), INTERVAL MOD( TIMESTAMPDIFF(SECOND, usuario_configuracion.fecha_modif, now()),  31536000)SECOND) FROM usuario_configuracion INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOANUAL' AND clasificador.clasificador_id = usuario_configuracion.tipo) WHERE usuario_configuracion.usuario_id ='" . $UsuarioConfiguracion->getUsuarioId() . "' AND usuario_configuracion.estado= 'A' AND usuario_configuracion.tipo = clasificador.clasificador_id";
        $sqlQuery = new SqlQuery($sql1);
        $fechaModif1 = $this->execute2($sqlQuery);

        echo ("automations:11 " . (microtime(true) - $msc)) . PHP_EOL;
        $msc = microtime(true);
        $fechaModif1 = $fechaModif1[0][0];
        $sql2 = "SELECT DATE_SUB(now(), INTERVAL MOD( TIMESTAMPDIFF(SECOND, usuario_configuracion.fecha_modif, now()),  3600)SECOND) FROM usuario_configuracion INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOANUAL' AND clasificador.clasificador_id = usuario_configuracion.tipo) WHERE usuario_configuracion.usuario_id ='" . $UsuarioConfiguracion->getUsuarioId() . "' AND usuario_configuracion.estado= 'A' AND usuario_configuracion.tipo = clasificador.clasificador_id";
        $sqlQuery = new SqlQuery($sql2);
        $fechaModif2 = $this->execute2($sqlQuery);

        echo ("automations:12 " . (microtime(true) - $msc)) . PHP_EOL;
        $msc = microtime(true);
        $fechaModif2 = $fechaModif2[0][0];
        $sql = "SELECT count(*)
FROM (SELECT SUM(valor) valor,valorconfig
      FROM (SELECT 0 valor, usuario_configuracion.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_configuracion
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOANUAL' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = " . $UsuarioConfiguracion->getUsuarioId() . "
              AND usuario_configuracion.estado = 'A'

        UNION
        SELECT SUM(usuario_configuracion_resumen.valor) valor, usuario_configuracion.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_configuracion_resumen
                     INNER JOIN usuario_configuracion
                                ON (usuario_configuracion.usuconfig_id =
                                    usuario_configuracion_resumen.usuconfig_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOANUAL' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "'
              AND usuario_configuracion.estado = 'A'
              AND usuario_configuracion_resumen.fecha_crea >= '$fechaModif1'

            UNION

            SELECT SUM(CASE
               WHEN transjuego_log.tipo LIKE ('%DEBIT%') THEN transjuego_log.valor
               WHEN transjuego_log.tipo LIKE ('%ROLLBACK%') THEN transjuego_log.valor
               ELSE 0 END)                                                               valor, usuario_mandante.usuario_mandante, usuario_configuracion.valor valorconfig
            FROM transjuego_log
                     INNER JOIN transaccion_juego ON (transaccion_juego.transjuego_id = transjuego_log.transjuego_id)
                     INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id = usuario_mandante.usumandante_id)
                     INNER JOIN producto_mandante ON (producto_mandante.prodmandante_id = transaccion_juego.producto_id)
                     INNER JOIN producto ON (producto.producto_id = producto_mandante.producto_id)
                     INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id)
                     INNER JOIN usuario_configuracion ON (usuario_mandante.usuario_mandante = usuario_configuracion.usuario_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOANUAL' AND clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_mandante.usumandante_id = '16'  AND proveedor.tipo ='CASINO'
              AND usuario_configuracion.estado = 'A'
              AND transjuego_log.fecha_crea >= '$fechaModif2'
                              
           ) data2) data

WHERE data.valorconfig < valor +  '" . $valor . "'
";

        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
        $count = $count[0]['.count(*)'];

        echo ("automations:13 " . (microtime(true) - $msc)) . PHP_EOL;
        $msc = microtime(true);
        if ($count > 0) {
            return 20017;
            return $Clasificador->getClasificadorId();
        }


        return 0;
    }


    public function queryVerifiyLimitesCasino44(UsuarioConfiguracion $UsuarioConfiguracion, $valor)
    {
        date_default_timezone_set('America/Bogota');

        $msc = microtime(true);
        echo ("automations: " . $msc) . PHP_EOL;

        $Clasificador = new Clasificador("", "LIMAPUCASINOSIMPLE");

        $sql = "SELECT count(*) FROM usuario_configuracion WHERE tipo = '" . $Clasificador->getClasificadorId() . "' AND usuario_id='" . $UsuarioConfiguracion->getUsuarioId() . "'  AND estado='A' AND valor < '" . $valor . "'";
        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);

        $count = $count[0]['.count(*)'];

        if ($count > 0) {
            return 20013;
            return $Clasificador->getClasificadorId();
        }
        echo ("automations:1 " . (microtime(true) - $msc)) . PHP_EOL;
        $msc = microtime(true);

        // $Clasificador = new Clasificador("", "LIMAPUCASINODIARIO");
        $fechaModif = "SELECT usuario_configuracion.fecha_modif as fechaModif, clasificador.abreviado as abreviado FROM usuario_configuracion INNER JOIN clasificador ON ( clasificador.clasificador_id = usuario_configuracion.tipo) WHERE usuario_configuracion.usuario_id ='" . $UsuarioConfiguracion->getUsuarioId() . "' AND usuario_configuracion.estado= 'A'";
        $sqlQuery = new SqlQuery($fechaModif);
        $fechaModif = $this->execute2($sqlQuery);
        $fechaModifDiario = "";
        $fechaModifDiario2 = "";
        $fechaModifSemana = "";
        $fechaModifSemana2 = "";
        $fechaModifMensual = "";
        $fechaModifMensual2 = "";
        $fechaModifAnual = "";
        $fechaModifAnual2 = "";
        foreach ($fechaModif as $fecha) {

            if ($fecha['clasificador.abreviado'] == "LIMAPUCASINODIARIO") {

                $diff = ((time() - strtotime($fecha['usuario_configuracion.fechaModif'])) % 86400);
                $fechaModifDiario = date("Y-m-d H:i:s", strtotime("- $diff sec"));
                $diff2 = ((time() - strtotime($fecha['usuario_configuracion.fechaModif'])) % 3600);
                $fechaModifDiario2 = date("Y-m-d H:i:s", strtotime("- $diff2 sec"));

            }
            if ($fecha['clasificador.abreviado'] == "LIMAPUCASINOSEMANA") {
                $diff = ((time() - strtotime($fecha['usuario_configuracion.fechaModif'])) % 604800);
                $fechaModifSemana = date("Y-m-d H:i:s", strtotime("- $diff sec"));
                $diff2 = ((time() - strtotime($fecha['usuario_configuracion.fechaModif'])) % 3600);
                $fechaModifSemana2 = date("Y-m-d H:i:s", strtotime("- $diff2 sec"));
            }

            if ($fecha['clasificador.abreviado'] == "LIMAPUCASINOMENSUAL") {
                $diff = ((time() - strtotime($fecha['usuario_configuracion.fechaModif'])) % 2592000);
                $fechaModifMensual = date("Y-m-d H:i:s", strtotime("- $diff sec"));
                $diff2 = ((time() - strtotime($fecha['usuario_configuracion.fechaModif'])) % 3600);
                $fechaModifMensual2 = date("Y-m-d H:i:s", strtotime("- $diff2 sec"));
            }
            if ($fecha['clasificador.abreviado'] == "LIMAPUCASINOANUAL") {
                $diff = ((time() - strtotime($fecha['usuario_configuracion.fechaModif'])) % 31536000);
                $fechaModifAnual = date("Y-m-d H:i:s", strtotime("- $diff sec"));
                $diff2 = ((time() - strtotime($fecha['usuario_configuracion.fechaModif'])) % 3600);
                $fechaModifAnual2 = date("Y-m-d H:i:s", strtotime("- $diff2 sec"));
            }

        }
        echo ("automations:2 " . (microtime(true) - $msc)) . PHP_EOL;
        $msc = microtime(true);

        // $sql = "SELECT COUNT(*) FROM usuario_configuracion INNER JOIN (SELECT SUM(usuario_recarga.valor) valor,usuario_id FROM usuario_recarga WHERE usuario_id ='" . $UsuarioConfiguracion->getUsuarioId() . "'   AND date_format(fecha_crea, '%Y-%m-%d') =  date_format(now(), '%Y-%m-%d') )data ON (usuario_configuracion.usuario_id = data.usuario_id) WHERE tipo = '" . $Clasificador->getClasificadorId() . "' AND usuario_configuracion.usuario_id='" . $UsuarioConfiguracion->getUsuarioId() . "'  AND estado='A' AND usuario_configuracion.valor < data.valor + '" . $valor . "'";
        /*  $sql1 = "SELECT  DATE_SUB(now(), INTERVAL MOD( TIMESTAMPDIFF(SECOND, usuario_configuracion.fecha_modif, now()),  86400)SECOND) FROM usuario_configuracion INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINODIARIO' AND clasificador.clasificador_id = usuario_configuracion.tipo) WHERE usuario_configuracion.usuario_id ='" . $UsuarioConfiguracion->getUsuarioId()."' AND usuario_configuracion.estado= 'A'";
          $sqlQuery = new SqlQuery($sql1);
          $fechaModif1 = $this->execute2($sqlQuery);
          $fechaModif1 = $fechaModif1[0][0];
          $sql2 = "SELECT DATE_SUB(now(), INTERVAL MOD( TIMESTAMPDIFF(SECOND, usuario_configuracion.fecha_modif, now()),  3600)SECOND) FROM usuario_configuracion INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINODIARIO' AND clasificador.clasificador_id = usuario_configuracion.tipo) WHERE usuario_configuracion.usuario_id ='" . $UsuarioConfiguracion->getUsuarioId()."' AND usuario_configuracion.estado= 'A'";
          $sqlQuery = new SqlQuery($sql2);
          $fechaModif2 = $this->execute2($sqlQuery);
          $fechaModif2 = $fechaModif2[0][0];
        */
        $sql = "SELECT count(*)
FROM (SELECT SUM(valor) valor,valorconfig
      FROM (SELECT 0 valor, usuario_configuracion.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_configuracion
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINODIARIO' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = " . $UsuarioConfiguracion->getUsuarioId() . "
              AND usuario_configuracion.estado = 'A'

        UNION
        SELECT SUM(usuario_configuracion_resumen.valor) valor, usuario_configuracion.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_configuracion_resumen
                     INNER JOIN usuario_configuracion
                                ON (usuario_configuracion.usuconfig_id =
                                    usuario_configuracion_resumen.usuconfig_id)
                    INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINODIARIO' AND
                                                clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "'
              AND usuario_configuracion.estado = 'A' 
              AND usuario_configuracion_resumen.fecha_crea >= '$fechaModifDiario'

            UNION

            SELECT SUM(CASE
               WHEN transjuego_log.tipo LIKE ('DEBIT%') THEN transjuego_log.valor
               WHEN transjuego_log.tipo LIKE ('ROLLBACK%') THEN transjuego_log.valor
               ELSE 0 END)                                                               valor, usuario_mandante.usuario_mandante, usuario_configuracion.valor valorconfig
            FROM transjuego_log
                     INNER JOIN transaccion_juego ON (transaccion_juego.transjuego_id = transjuego_log.transjuego_id)
                     INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id = usuario_mandante.usumandante_id)
                     INNER JOIN producto_mandante ON (producto_mandante.prodmandante_id = transaccion_juego.producto_id)
                     INNER JOIN producto ON (producto.producto_id = producto_mandante.producto_id)
                     INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id)
                     INNER JOIN usuario_configuracion ON (usuario_mandante.usuario_mandante = usuario_configuracion.usuario_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINODIARIO' AND clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_mandante.usumandante_id = '" . 16 . "' AND proveedor.tipo ='CASINO'
              AND usuario_configuracion.estado = 'A'
              AND transjuego_log.fecha_crea >= '$fechaModifDiario2'
                  
                              
           ) data2) data

WHERE data.valorconfig < valor +  '" . $valor . "'
";

        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
        $count = $count[0]['.count(*)'];

        if ($count > 0) {
            return 20014;
            return $Clasificador->getClasificadorId();
        }
        echo ("automations:3 " . (microtime(true) - $msc)) . PHP_EOL;
        $msc = microtime(true);

        //$Clasificador = new Clasificador("", "LIMAPUCASINOSEMANA");

        //$sql = "SELECT count(*) FROM usuario_configuracion INNER JOIN (SELECT SUM(usuario_recarga.valor) valor,usuario_id FROM usuario_recarga WHERE usuario_id ='" . $UsuarioConfiguracion->getUsuarioId() . "'   AND date_format(fecha_crea, '%Y-%m-%d') >=  date_format(DATE_SUB(now(), INTERVAL 7 DAY), '%Y-%m-%d') )data ON (usuario_configuracion.usuario_id = data.usuario_id) WHERE tipo = '" . $Clasificador->getClasificadorId() . "' AND usuario_configuracion.usuario_id='" . $UsuarioConfiguracion->getUsuarioId() . "'  AND estado='A' AND usuario_configuracion.valor < data.valor + '" . $valor . "'";
        /*   $sql1 = "SELECT  DATE_SUB(now(), INTERVAL MOD( TIMESTAMPDIFF(SECOND, usuario_configuracion.fecha_modif, now()),  604800)SECOND) FROM usuario_configuracion INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOSEMANA' AND clasificador.clasificador_id = usuario_configuracion.tipo) WHERE usuario_configuracion.usuario_id ='" . $UsuarioConfiguracion->getUsuarioId()."' AND usuario_configuracion.estado= 'A' AND usuario_configuracion.tipo = clasificador.clasificador_id";
           $sqlQuery = new SqlQuery($sql1);
           $fechaModif1 = $this->execute2($sqlQuery);
           $fechaModif1 = $fechaModif1[0][0];
           $sql2 = "SELECT DATE_SUB(now(), INTERVAL MOD( TIMESTAMPDIFF(SECOND, usuario_configuracion.fecha_modif, now()),  3600)SECOND) FROM usuario_configuracion INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOSEMANA' AND clasificador.clasificador_id = usuario_configuracion.tipo) WHERE usuario_configuracion.usuario_id ='" . $UsuarioConfiguracion->getUsuarioId()."' AND usuario_configuracion.estado= 'A' AND usuario_configuracion.tipo = clasificador.clasificador_id";
           $sqlQuery = new SqlQuery($sql2);
           $fechaModif2 = $this->execute2($sqlQuery);
           $fechaModif2 = $fechaModif2[0][0]; */
        $sql = "SELECT count(*)
FROM (SELECT SUM(valor) valor,valorconfig
      FROM (SELECT 0 valor, usuario_configuracion.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_configuracion
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOSEMANA' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = " . $UsuarioConfiguracion->getUsuarioId() . "
              AND usuario_configuracion.estado = 'A'

        UNION
        SELECT SUM(usuario_configuracion_resumen.valor) valor, usuario_configuracion.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_configuracion_resumen
                     INNER JOIN usuario_configuracion
                                ON (usuario_configuracion.usuconfig_id =
                                    usuario_configuracion_resumen.usuconfig_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOSEMANA' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "'
              AND usuario_configuracion.estado = 'A'
              AND usuario_configuracion_resumen.fecha_crea >= '$fechaModifSemana'

            UNION

            SELECT SUM(CASE
               WHEN transjuego_log.tipo LIKE ('DEBIT%') THEN transjuego_log.valor
               WHEN transjuego_log.tipo LIKE ('ROLLBACK%') THEN transjuego_log.valor
               ELSE 0 END)                                                               valor, usuario_mandante.usuario_mandante, usuario_configuracion.valor valorconfig
            FROM transjuego_log
                     INNER JOIN transaccion_juego ON (transaccion_juego.transjuego_id = transjuego_log.transjuego_id)
                     INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id = usuario_mandante.usumandante_id)
                     INNER JOIN producto_mandante ON (producto_mandante.prodmandante_id = transaccion_juego.producto_id)
                     INNER JOIN producto ON (producto.producto_id = producto_mandante.producto_id)
                     INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id)
                     INNER JOIN usuario_configuracion ON (usuario_mandante.usuario_mandante = usuario_configuracion.usuario_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOSEMANA' AND clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_mandante.usumandante_id = '" . 16 . "' AND proveedor.tipo ='CASINO'
              AND usuario_configuracion.estado = 'A' 
              AND transjuego_log.fecha_crea >= '$fechaModifSemana2'
                              
           ) data2) data

WHERE data.valorconfig < valor +  '" . $valor . "'
";

        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
        $count = $count[0]['.count(*)'];

        if ($count > 0) {
            return 20015;
            return $Clasificador->getClasificadorId();
        }
        echo ("automations:4 " . (microtime(true) - $msc)) . PHP_EOL;
        $msc = microtime(true);


        //$Clasificador = new Clasificador("", "LIMAPUCASINOMENSUAL");
        //$sql="SELECT count(*) FROM usuario_configuracion INNER JOIN (SELECT SUM(usuario_recarga.valor) valor,usuario_id FROM usuario_recarga WHERE usuario_id ='" . $UsuarioConfiguracion->getUsuarioId() . "'  AND date_format(fecha_crea, '%Y-%m-%d') >=  date_format(DATE_SUB(now(), INTERVAL 30 DAY), '%Y-%m-%d') )data ON (usuario_configuracion.usuario_id = data.usuario_id) WHERE tipo = '" . $Clasificador->getClasificadorId() . "' AND usuario_configuracion.usuario_id='" . $UsuarioConfiguracion->getUsuarioId() . "'  AND estado='A' AND usuario_configuracion.valor < data.valor + '" . $valor . "'";

        /* $sql1 = "SELECT  DATE_SUB(now(), INTERVAL MOD( TIMESTAMPDIFF(SECOND, usuario_configuracion.fecha_modif, now()),  2592000)SECOND) FROM usuario_configuracion INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOMENSUAL' AND clasificador.clasificador_id = usuario_configuracion.tipo) WHERE usuario_configuracion.usuario_id ='" . $UsuarioConfiguracion->getUsuarioId()."' AND usuario_configuracion.estado= 'A' AND usuario_configuracion.tipo = clasificador.clasificador_id";
        $sqlQuery = new SqlQuery($sql1);
        $fechaModif1 = $this->execute2($sqlQuery);
        $fechaModif1 = $fechaModif1[0][0];
        $sql2 = "SELECT DATE_SUB(now(), INTERVAL MOD( TIMESTAMPDIFF(SECOND, usuario_configuracion.fecha_modif, now()),  3600)SECOND) FROM usuario_configuracion INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOMENSUAL' AND clasificador.clasificador_id = usuario_configuracion.tipo) WHERE usuario_configuracion.usuario_id ='" . $UsuarioConfiguracion->getUsuarioId()."' AND usuario_configuracion.estado= 'A' AND usuario_configuracion.tipo = clasificador.clasificador_id";
        $sqlQuery = new SqlQuery($sql2);
        $fechaModif2 = $this->execute2($sqlQuery);
        $fechaModif2 = $fechaModif2[0][0]; */
        $sql = "SELECT count(*) 
FROM (SELECT SUM(valor) valor,valorconfig
      FROM (SELECT 0 valor, usuario_configuracion.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_configuracion
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOMENSUAL' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = " . $UsuarioConfiguracion->getUsuarioId() . "
              AND usuario_configuracion.estado = 'A'

        UNION
        SELECT SUM(usuario_configuracion_resumen.valor) valor, usuario_configuracion.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_configuracion_resumen
                     INNER JOIN usuario_configuracion
                                ON (usuario_configuracion.usuconfig_id =
                                    usuario_configuracion_resumen.usuconfig_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOMENSUAL' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "'
              AND usuario_configuracion.estado = 'A' 
              AND usuario_configuracion_resumen.fecha_crea >= '$fechaModifMensual'
                 

            UNION

            SELECT SUM(CASE
               WHEN transjuego_log.tipo LIKE ('DEBIT%') THEN transjuego_log.valor
               WHEN transjuego_log.tipo LIKE ('ROLLBACK%') THEN transjuego_log.valor
               ELSE 0 END)                                                               valor, usuario_mandante.usuario_mandante, usuario_configuracion.valor valorconfig
            FROM transjuego_log
                     INNER JOIN transaccion_juego ON (transaccion_juego.transjuego_id = transjuego_log.transjuego_id)
                     INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id = usuario_mandante.usumandante_id)
                     INNER JOIN producto_mandante ON (producto_mandante.prodmandante_id = transaccion_juego.producto_id)
                     INNER JOIN producto ON (producto.producto_id = producto_mandante.producto_id)
                     INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id)
                     INNER JOIN usuario_configuracion ON (usuario_mandante.usuario_mandante = usuario_configuracion.usuario_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOMENSUAL' AND clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_mandante.usumandante_id = '" . 16 . "' AND proveedor.tipo ='CASINO'
              AND usuario_configuracion.estado = 'A' 
              AND transjuego_log.fecha_crea >= '$fechaModifMensual2'
                              
           ) data2) data

WHERE data.valorconfig < valor +  '" . $valor . "'
";
        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
        $count = $count[0]['.count(*)'];

        if ($count > 0) {
            return 20016;
            return $Clasificador->getClasificadorId();
        }

        echo ("automations:5 " . (microtime(true) - $msc)) . PHP_EOL;
        $msc = microtime(true);


        // $Clasificador = new Clasificador("", "LIMAPUCASINOANUAL");

        //$sql = "SELECT count(*) FROM usuario_configuracion INNER JOIN (SELECT SUM(usuario_recarga.valor) valor,usuario_id FROM usuario_recarga WHERE usuario_id ='" . $UsuarioConfiguracion->getUsuarioId() . "'   AND date_format(fecha_crea, '%Y-%m-%d') >=  date_format(DATE_SUB(now(), INTERVAL 365 DAY), '%Y-%m-%d') )data ON (usuario_configuracion.usuario_id = data.usuario_id) WHERE tipo = '" . $Clasificador->getClasificadorId() . "' AND usuario_configuracion.usuario_id='" . $UsuarioConfiguracion->getUsuarioId() . "'  AND estado='A' AND usuario_configuracion.valor < data.valor + '" . $valor . "'";
        /* $sql1 = "SELECT  DATE_SUB(now(), INTERVAL MOD( TIMESTAMPDIFF(SECOND, usuario_configuracion.fecha_modif, now()),  31536000)SECOND) FROM usuario_configuracion INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOANUAL' AND clasificador.clasificador_id = usuario_configuracion.tipo) WHERE usuario_configuracion.usuario_id ='" . $UsuarioConfiguracion->getUsuarioId()."' AND usuario_configuracion.estado= 'A' AND usuario_configuracion.tipo = clasificador.clasificador_id";
         $sqlQuery = new SqlQuery($sql1);
         $fechaModif1 = $this->execute2($sqlQuery);
         $fechaModif1 = $fechaModif1[0][0];
         $sql2 = "SELECT DATE_SUB(now(), INTERVAL MOD( TIMESTAMPDIFF(SECOND, usuario_configuracion.fecha_modif, now()),  3600)SECOND) FROM usuario_configuracion INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOANUAL' AND clasificador.clasificador_id = usuario_configuracion.tipo) WHERE usuario_configuracion.usuario_id ='" . $UsuarioConfiguracion->getUsuarioId()."' AND usuario_configuracion.estado= 'A' AND usuario_configuracion.tipo = clasificador.clasificador_id";
         $sqlQuery = new SqlQuery($sql2);
         $fechaModif2 = $this->execute2($sqlQuery);
         $fechaModif2 = $fechaModif2[0][0]; */
        $sql = "SELECT count(*)
FROM (SELECT SUM(valor) valor,valorconfig
      FROM (SELECT 0 valor, usuario_configuracion.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_configuracion
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOANUAL' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = " . $UsuarioConfiguracion->getUsuarioId() . "
              AND usuario_configuracion.estado = 'A'

        UNION
        SELECT SUM(usuario_configuracion_resumen.valor) valor, usuario_configuracion.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_configuracion_resumen
                     INNER JOIN usuario_configuracion
                                ON (usuario_configuracion.usuconfig_id =
                                    usuario_configuracion_resumen.usuconfig_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOANUAL' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "'
              AND usuario_configuracion.estado = 'A'
              AND usuario_configuracion_resumen.fecha_crea >= '$fechaModifAnual'

            UNION

            SELECT SUM(CASE
               WHEN transjuego_log.tipo LIKE ('DEBIT%') THEN transjuego_log.valor
               WHEN transjuego_log.tipo LIKE ('ROLLBACK%') THEN transjuego_log.valor
               ELSE 0 END)                                                               valor, usuario_mandante.usuario_mandante, usuario_configuracion.valor valorconfig
            FROM transjuego_log
                     INNER JOIN transaccion_juego ON (transaccion_juego.transjuego_id = transjuego_log.transjuego_id)
                     INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id = usuario_mandante.usumandante_id)
                     INNER JOIN producto_mandante ON (producto_mandante.prodmandante_id = transaccion_juego.producto_id)
                     INNER JOIN producto ON (producto.producto_id = producto_mandante.producto_id)
                     INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id)
                     INNER JOIN usuario_configuracion ON (usuario_mandante.usuario_mandante = usuario_configuracion.usuario_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOANUAL' AND clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_mandante.usumandante_id = '" . 16 . "'  AND proveedor.tipo ='CASINO'
              AND usuario_configuracion.estado = 'A' 
              AND transjuego_log.fecha_crea >= '$fechaModifAnual2'
                              
           ) data2) data

WHERE data.valorconfig < valor +  '" . $valor . "'
";

        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
        $count = $count[0]['.count(*)'];

        if ($count > 0) {
            return 20017;
            return $Clasificador->getClasificadorId();
        }
        echo ("automations:6 " . (microtime(true) - $msc)) . PHP_EOL;
        $msc = microtime(true);


        return 0;
    }


    /**
     * Verificar si el usuario tiene limites de apuesta de casino  y excede estos con el nueva apuesta casino
     *
     * @param String $orderColumn nombre de la columna
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryVerifiyLimitesCasino22(UsuarioConfiguracion $UsuarioConfiguracion, $valor)
    {

        $Clasificador = new Clasificador("", "LIMAPUCASINOSIMPLE");

        $sql = "SELECT count(*) FROM usuario_configuracion WHERE tipo = '" . $Clasificador->getClasificadorId() . "' AND usuario_id='" . $UsuarioConfiguracion->getUsuarioId() . "'  AND estado='A' AND valor < '" . $valor . "'";
        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);

        $count = $count[0]['.count(*)'];

        if ($count > 0) {
            return 20013;
            return $Clasificador->getClasificadorId();
        }

        $sql = "SELECT count(*)
FROM (SELECT SUM(valor) valor,valorconfig
      FROM (SELECT 0 valor, usuario_configuracion.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_configuracion
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINODIARIO' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = " . $UsuarioConfiguracion->getUsuarioId() . "
              AND usuario_configuracion.estado = 'A'

        UNION
        SELECT SUM(usuario_configuracion_resumen.valor) valor, usuario_configuracion.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_configuracion_resumen
                     INNER JOIN usuario_configuracion
                                ON (usuario_configuracion.usuconfig_id =
                                    usuario_configuracion_resumen.usuconfig_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINODIARIO' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "'
              AND usuario_configuracion.estado = 'A'
              AND date_format(usuario_configuracion_resumen.fecha_crea, '%Y-%m-%d %H:%i:%s') >=
                  date_format(
                          DATE_SUB(now(), INTERVAL MOD(
                                  TIMESTAMPDIFF(SECOND, usuario_configuracion.fecha_modif, now()),
                                  86400)
                                   SECOND),
                          '%Y-%m-%d  %H:%i:%s')

            UNION

            SELECT SUM(CASE
               WHEN transjuego_log.tipo LIKE ('%DEBIT%') THEN transjuego_log.valor
               WHEN transjuego_log.tipo LIKE ('%ROLLBACK%') THEN transjuego_log.valor
               ELSE 0 END)                                                               valor, usuario_mandante.usuario_mandante, usuario_configuracion.valor valorconfig
            FROM transjuego_log
                     INNER JOIN transaccion_juego ON (transaccion_juego.transjuego_id = transjuego_log.transjuego_id)
                     INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id = usuario_mandante.usumandante_id)
                     INNER JOIN producto_mandante ON (producto_mandante.prodmandante_id = transaccion_juego.producto_id)
                     INNER JOIN producto ON (producto.producto_id = producto_mandante.producto_id)
                     INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id)
                     INNER JOIN usuario_configuracion ON (usuario_mandante.usuario_mandante = usuario_configuracion.usuario_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINODIARIO' AND clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "' AND proveedor.tipo ='CASINO'
              AND usuario_configuracion.estado = 'A'
              AND date_format(transjuego_log.fecha_crea, '%Y-%m-%d %H:%i:%s') >=
                  date_format(DATE_SUB(
                                      DATE_SUB(now(), INTERVAL
                                               MOD(
                                                       TIMESTAMPDIFF(SECOND, usuario_configuracion.fecha_modif, now()),
                                                       3600)
                                               SECOND),
                                      INTERVAL 0
                                      SECOND),
                              '%Y-%m-%d %H:%i:%s')
                              
           ) data2) data

WHERE data.valorconfig < valor +  '" . $valor . "'
";
        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
        $count = $count[0]['.count(*)'];

        if ($count > 0) {
            return 20014;
            return $Clasificador->getClasificadorId();
        }

        $sql = "SELECT count(*)
FROM (SELECT SUM(valor) valor,valorconfig
      FROM (SELECT 0 valor, usuario_configuracion.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_configuracion
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOSEMANA' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = " . $UsuarioConfiguracion->getUsuarioId() . "
              AND usuario_configuracion.estado = 'A'

        UNION
        SELECT SUM(usuario_configuracion_resumen.valor) valor, usuario_configuracion.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_configuracion_resumen
                     INNER JOIN usuario_configuracion
                                ON (usuario_configuracion.usuconfig_id =
                                    usuario_configuracion_resumen.usuconfig_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOSEMANA' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "'
              AND usuario_configuracion.estado = 'A'
              AND date_format(usuario_configuracion_resumen.fecha_crea, '%Y-%m-%d %H:%i:%s') >=
                  date_format(
                          DATE_SUB(now(), INTERVAL MOD(
                                  TIMESTAMPDIFF(SECOND, usuario_configuracion.fecha_modif, now()),
                                  604800)
                                   SECOND),
                          '%Y-%m-%d  %H:%i:%s')

            UNION

            SELECT SUM(CASE
               WHEN transjuego_log.tipo LIKE ('%DEBIT%') THEN transjuego_log.valor
               WHEN transjuego_log.tipo LIKE ('%ROLLBACK%') THEN transjuego_log.valor
               ELSE 0 END)                                                               valor, usuario_mandante.usuario_mandante, usuario_configuracion.valor valorconfig
            FROM transjuego_log
                     INNER JOIN transaccion_juego ON (transaccion_juego.transjuego_id = transjuego_log.transjuego_id)
                     INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id = usuario_mandante.usumandante_id)
                     INNER JOIN producto_mandante ON (producto_mandante.prodmandante_id = transaccion_juego.producto_id)
                     INNER JOIN producto ON (producto.producto_id = producto_mandante.producto_id)
                     INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id)
                     INNER JOIN usuario_configuracion ON (usuario_mandante.usuario_mandante = usuario_configuracion.usuario_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOSEMANA' AND clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "' AND proveedor.tipo ='CASINO'
              AND usuario_configuracion.estado = 'A'
              AND date_format(transjuego_log.fecha_crea, '%Y-%m-%d %H:%i:%s') >=
                  date_format(DATE_SUB(
                                      DATE_SUB(now(), INTERVAL
                                               MOD(
                                                       TIMESTAMPDIFF(SECOND, usuario_configuracion.fecha_modif, now()),
                                                       3600)
                                               SECOND),
                                      INTERVAL 0
                                      SECOND),
                              '%Y-%m-%d %H:%i:%s')
                              
           ) data2) data

WHERE data.valorconfig < valor +  '" . $valor . "'
";


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
        $count = $count[0]['.count(*)'];

        if ($count > 0) {
            return 20015;
            return $Clasificador->getClasificadorId();
        }


        $sql = "SELECT count(*)
FROM (SELECT SUM(valor) valor,valorconfig
      FROM (SELECT 0 valor, usuario_configuracion.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_configuracion
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOMENSUAL' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = " . $UsuarioConfiguracion->getUsuarioId() . "
              AND usuario_configuracion.estado = 'A'

        UNION
        SELECT SUM(usuario_configuracion_resumen.valor) valor, usuario_configuracion.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_configuracion_resumen
                     INNER JOIN usuario_configuracion
                                ON (usuario_configuracion.usuconfig_id =
                                    usuario_configuracion_resumen.usuconfig_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOMENSUAL' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "'
              AND usuario_configuracion.estado = 'A'
              AND date_format(usuario_configuracion_resumen.fecha_crea, '%Y-%m-%d %H:%i:%s') >=
                  date_format(
                          DATE_SUB(now(), INTERVAL MOD(
                                  TIMESTAMPDIFF(SECOND, usuario_configuracion.fecha_modif, now()),
                                  2592000)
                                   SECOND),
                          '%Y-%m-%d  %H:%i:%s')

            UNION

            SELECT SUM(CASE
               WHEN transjuego_log.tipo LIKE ('%DEBIT%') THEN transjuego_log.valor
               WHEN transjuego_log.tipo LIKE ('%ROLLBACK%') THEN transjuego_log.valor
               ELSE 0 END)                                                               valor, usuario_mandante.usuario_mandante, usuario_configuracion.valor valorconfig
            FROM transjuego_log
                     INNER JOIN transaccion_juego ON (transaccion_juego.transjuego_id = transjuego_log.transjuego_id)
                     INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id = usuario_mandante.usumandante_id)
                     INNER JOIN producto_mandante ON (producto_mandante.prodmandante_id = transaccion_juego.producto_id)
                     INNER JOIN producto ON (producto.producto_id = producto_mandante.producto_id)
                     INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id)
                     INNER JOIN usuario_configuracion ON (usuario_mandante.usuario_mandante = usuario_configuracion.usuario_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOMENSUAL' AND clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "' AND proveedor.tipo ='CASINO'
              AND usuario_configuracion.estado = 'A'
              AND date_format(transjuego_log.fecha_crea, '%Y-%m-%d %H:%i:%s') >=
                  date_format(DATE_SUB(
                                      DATE_SUB(now(), INTERVAL
                                               MOD(
                                                       TIMESTAMPDIFF(SECOND, usuario_configuracion.fecha_modif, now()),
                                                       3600)
                                               SECOND),
                                      INTERVAL 0
                                      SECOND),
                              '%Y-%m-%d %H:%i:%s')
                              
           ) data2) data

WHERE data.valorconfig < valor +  '" . $valor . "'
";

        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
        $count = $count[0]['.count(*)'];

        if ($count > 0) {
            return 20016;
            return $Clasificador->getClasificadorId();
        }



        $sql = "SELECT count(*)
FROM (SELECT SUM(valor) valor,valorconfig
      FROM (SELECT 0 valor, usuario_configuracion.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_configuracion
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOANUAL' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = " . $UsuarioConfiguracion->getUsuarioId() . "
              AND usuario_configuracion.estado = 'A'

        UNION
        SELECT SUM(usuario_configuracion_resumen.valor) valor, usuario_configuracion.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_configuracion_resumen
                     INNER JOIN usuario_configuracion
                                ON (usuario_configuracion.usuconfig_id =
                                    usuario_configuracion_resumen.usuconfig_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOANUAL' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "'
              AND usuario_configuracion.estado = 'A'
              AND date_format(usuario_configuracion_resumen.fecha_crea, '%Y-%m-%d %H:%i:%s') >=
                  date_format(
                          DATE_SUB(now(), INTERVAL MOD(
                                  TIMESTAMPDIFF(SECOND, usuario_configuracion.fecha_modif, now()),
                                  31536000)
                                   SECOND),
                          '%Y-%m-%d  %H:%i:%s')

            UNION

            SELECT SUM(CASE
               WHEN transjuego_log.tipo LIKE ('%DEBIT%') THEN transjuego_log.valor
               WHEN transjuego_log.tipo LIKE ('%ROLLBACK%') THEN transjuego_log.valor
               ELSE 0 END)                                                               valor, usuario_mandante.usuario_mandante, usuario_configuracion.valor valorconfig
            FROM transjuego_log
                     INNER JOIN transaccion_juego ON (transaccion_juego.transjuego_id = transjuego_log.transjuego_id)
                     INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id = usuario_mandante.usumandante_id)
                     INNER JOIN producto_mandante ON (producto_mandante.prodmandante_id = transaccion_juego.producto_id)
                     INNER JOIN producto ON (producto.producto_id = producto_mandante.producto_id)
                     INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id)
                     INNER JOIN usuario_configuracion ON (usuario_mandante.usuario_mandante = usuario_configuracion.usuario_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOANUAL' AND clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "'  AND proveedor.tipo ='CASINO'
              AND usuario_configuracion.estado = 'A'
              AND date_format(transjuego_log.fecha_crea, '%Y-%m-%d %H:%i:%s') >=
                  date_format(DATE_SUB(
                                      DATE_SUB(now(), INTERVAL
                                               MOD(
                                                       TIMESTAMPDIFF(SECOND, usuario_configuracion.fecha_modif, now()),
                                                       3600)
                                               SECOND),
                                      INTERVAL 0
                                      SECOND),
                              '%Y-%m-%d %H:%i:%s')
                              
           ) data2) data

WHERE data.valorconfig < valor +  '" . $valor . "'
";
        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
        $count = $count[0]['.count(*)'];

        if ($count > 0) {
            return 20017;
            return $Clasificador->getClasificadorId();
        }


        return 0;
    }


    /**
     * Verificar si el usuario tiene limites de apuesta de casino vivo y excede estos con el nueva apuesta casino vivo
     *
     * @param String $orderColumn nombre de la columna
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryVerifiyLimitesCasinoVivo44(UsuarioConfiguracion $UsuarioConfiguracion, $valor)
    {

        $Clasificador = new Clasificador("", "LIMAPUCASINOVIVOSIMPLE");

        $sql = "SELECT count(*) FROM usuario_configuracion WHERE tipo = '" . $Clasificador->getClasificadorId() . "' AND usuario_id='" . $UsuarioConfiguracion->getUsuarioId() . "'  AND estado='A' AND valor < '" . $valor . "'";
        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);

        $count = $count[0]['.count(*)'];

        if ($count > 0) {
            return 20018;
            return $Clasificador->getClasificadorId();
        }

        $sql = "SELECT count(*)
FROM (SELECT SUM(valor) valor,valorconfig
      FROM (SELECT 0 valor, usuario_configuracion.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_configuracion
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOVIVODIARIO' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = " . $UsuarioConfiguracion->getUsuarioId() . "
              AND usuario_configuracion.estado = 'A'

        UNION
        SELECT SUM(usuario_configuracion_resumen.valor) valor, usuario_configuracion.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_configuracion_resumen
                     INNER JOIN usuario_configuracion
                                ON (usuario_configuracion.usuconfig_id =
                                    usuario_configuracion_resumen.usuconfig_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOVIVODIARIO' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "'
              AND usuario_configuracion.estado = 'A'
              AND date_format(usuario_configuracion_resumen.fecha_crea, '%Y-%m-%d %H:%i:%s') >=
                  date_format(
                          DATE_SUB(now(), INTERVAL MOD(
                                  TIMESTAMPDIFF(SECOND, usuario_configuracion.fecha_modif, now()),
                                  86400)
                                   SECOND),
                          '%Y-%m-%d  %H:%i:%s')

            UNION

            SELECT SUM(CASE
               WHEN transjuego_log.tipo LIKE ('%DEBIT%') THEN transjuego_log.valor
               WHEN transjuego_log.tipo LIKE ('%ROLLBACK%') THEN transjuego_log.valor
               ELSE 0 END)                                                               valor, usuario_mandante.usuario_mandante, usuario_configuracion.valor valorconfig
            FROM transjuego_log
                     INNER JOIN transaccion_juego ON (transaccion_juego.transjuego_id = transjuego_log.transjuego_id)
                     INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id = usuario_mandante.usumandante_id)
                     INNER JOIN producto_mandante ON (producto_mandante.prodmandante_id = transaccion_juego.producto_id)
                     INNER JOIN producto ON (producto.producto_id = producto_mandante.producto_id)
                     INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id)
                     INNER JOIN usuario_configuracion ON (usuario_mandante.usuario_mandante = usuario_configuracion.usuario_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOVIVODIARIO' AND clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "' AND proveedor.tipo ='LIVECASINO'
              AND usuario_configuracion.estado = 'A'
              AND date_format(transjuego_log.fecha_crea, '%Y-%m-%d %H:%i:%s') >=
                  date_format(DATE_SUB(
                                      DATE_SUB(now(), INTERVAL
                                               MOD(
                                                       TIMESTAMPDIFF(SECOND, usuario_configuracion.fecha_modif, now()),
                                                       3600)
                                               SECOND),
                                      INTERVAL 0
                                      SECOND),
                              '%Y-%m-%d %H:%i:%s')
                              
           ) data2) data

WHERE data.valorconfig < valor +  '" . $valor . "'
";
        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
        $count = $count[0]['.count(*)'];

        if ($count > 0) {
            return 20019;
            return $Clasificador->getClasificadorId();
        }

        $sql = "SELECT count(*)
FROM (SELECT SUM(valor) valor,valorconfig
      FROM (SELECT 0 valor, usuario_configuracion.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_configuracion
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOVIVOSEMANA' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = " . $UsuarioConfiguracion->getUsuarioId() . "
              AND usuario_configuracion.estado = 'A'

        UNION
        SELECT SUM(usuario_configuracion_resumen.valor) valor, usuario_configuracion.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_configuracion_resumen
                     INNER JOIN usuario_configuracion
                                ON (usuario_configuracion.usuconfig_id =
                                    usuario_configuracion_resumen.usuconfig_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOVIVOSEMANA' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "'
              AND usuario_configuracion.estado = 'A'
              AND date_format(usuario_configuracion_resumen.fecha_crea, '%Y-%m-%d %H:%i:%s') >=
                  date_format(
                          DATE_SUB(now(), INTERVAL MOD(
                                  TIMESTAMPDIFF(SECOND, usuario_configuracion.fecha_modif, now()),
                                  604800)
                                   SECOND),
                          '%Y-%m-%d  %H:%i:%s')

            UNION

            SELECT SUM(CASE
               WHEN transjuego_log.tipo LIKE ('%DEBIT%') THEN transjuego_log.valor
               WHEN transjuego_log.tipo LIKE ('%ROLLBACK%') THEN transjuego_log.valor
               ELSE 0 END)                                                               valor, usuario_mandante.usuario_mandante, usuario_configuracion.valor valorconfig
            FROM transjuego_log
                     INNER JOIN transaccion_juego ON (transaccion_juego.transjuego_id = transjuego_log.transjuego_id)
                     INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id = usuario_mandante.usumandante_id)
                     INNER JOIN producto_mandante ON (producto_mandante.prodmandante_id = transaccion_juego.producto_id)
                     INNER JOIN producto ON (producto.producto_id = producto_mandante.producto_id)
                     INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id)
                     INNER JOIN usuario_configuracion ON (usuario_mandante.usuario_mandante = usuario_configuracion.usuario_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOVIVOSEMANA' AND clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "' AND proveedor.tipo ='LIVECASINO'
              AND usuario_configuracion.estado = 'A'
              AND date_format(transjuego_log.fecha_crea, '%Y-%m-%d %H:%i:%s') >=
                  date_format(DATE_SUB(
                                      DATE_SUB(now(), INTERVAL
                                               MOD(
                                                       TIMESTAMPDIFF(SECOND, usuario_configuracion.fecha_modif, now()),
                                                       3600)
                                               SECOND),
                                      INTERVAL 0
                                      SECOND),
                              '%Y-%m-%d %H:%i:%s')
                              
           ) data2) data

WHERE data.valorconfig < valor +  '" . $valor . "'
";

        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
        $count = $count[0]['.count(*)'];

        if ($count > 0) {
            return 20020;
            return $Clasificador->getClasificadorId();
        }


        $sql = "SELECT count(*)
FROM (SELECT SUM(valor) valor,valorconfig
      FROM (SELECT 0 valor, usuario_configuracion.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_configuracion
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOVIVOMENSUAL' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = " . $UsuarioConfiguracion->getUsuarioId() . "
              AND usuario_configuracion.estado = 'A'

        UNION
        SELECT SUM(usuario_configuracion_resumen.valor) valor, usuario_configuracion.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_configuracion_resumen
                     INNER JOIN usuario_configuracion
                                ON (usuario_configuracion.usuconfig_id =
                                    usuario_configuracion_resumen.usuconfig_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOVIVOMENSUAL' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "'
              AND usuario_configuracion.estado = 'A'
              AND date_format(usuario_configuracion_resumen.fecha_crea, '%Y-%m-%d %H:%i:%s') >=
                  date_format(
                          DATE_SUB(now(), INTERVAL MOD(
                                  TIMESTAMPDIFF(SECOND, usuario_configuracion.fecha_modif, now()),
                                  2592000)
                                   SECOND),
                          '%Y-%m-%d  %H:%i:%s')

            UNION

            SELECT SUM(CASE
               WHEN transjuego_log.tipo LIKE ('%DEBIT%') THEN transjuego_log.valor
               WHEN transjuego_log.tipo LIKE ('%ROLLBACK%') THEN transjuego_log.valor
               ELSE 0 END)                                                               valor, usuario_mandante.usuario_mandante, usuario_configuracion.valor valorconfig
            FROM transjuego_log
                     INNER JOIN transaccion_juego ON (transaccion_juego.transjuego_id = transjuego_log.transjuego_id)
                     INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id = usuario_mandante.usumandante_id)
                     INNER JOIN producto_mandante ON (producto_mandante.prodmandante_id = transaccion_juego.producto_id)
                     INNER JOIN producto ON (producto.producto_id = producto_mandante.producto_id)
                     INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id)
                     INNER JOIN usuario_configuracion ON (usuario_mandante.usuario_mandante = usuario_configuracion.usuario_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOVIVOMENSUAL' AND clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "' AND proveedor.tipo ='LIVECASINO'
              AND usuario_configuracion.estado = 'A'
              AND date_format(transjuego_log.fecha_crea, '%Y-%m-%d %H:%i:%s') >=
                  date_format(DATE_SUB(
                                      DATE_SUB(now(), INTERVAL
                                               MOD(
                                                       TIMESTAMPDIFF(SECOND, usuario_configuracion.fecha_modif, now()),
                                                       3600)
                                               SECOND),
                                      INTERVAL 0
                                      SECOND),
                              '%Y-%m-%d %H:%i:%s')
                              
           ) data2) data

WHERE data.valorconfig < valor +  '" . $valor . "'
";

        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
        $count = $count[0]['.count(*)'];

        if ($count > 0) {
            return 20021;
            return $Clasificador->getClasificadorId();
        }


        $sql = "SELECT count(*)
FROM (SELECT SUM(valor) valor,valorconfig
      FROM (SELECT 0 valor, usuario_configuracion.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_configuracion
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOVIVOANUAL' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = " . $UsuarioConfiguracion->getUsuarioId() . "
              AND usuario_configuracion.estado = 'A'

        UNION
        SELECT SUM(usuario_configuracion_resumen.valor) valor, usuario_configuracion.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_configuracion_resumen
                     INNER JOIN usuario_configuracion
                                ON (usuario_configuracion.usuconfig_id =
                                    usuario_configuracion_resumen.usuconfig_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOVIVOANUAL' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "'
              AND usuario_configuracion.estado = 'A'
              AND date_format(usuario_configuracion_resumen.fecha_crea, '%Y-%m-%d %H:%i:%s') >=
                  date_format(
                          DATE_SUB(now(), INTERVAL MOD(
                                  TIMESTAMPDIFF(SECOND, usuario_configuracion.fecha_modif, now()),
                                  31536000)
                                   SECOND),
                          '%Y-%m-%d  %H:%i:%s')

            UNION

            SELECT SUM(CASE
               WHEN transjuego_log.tipo LIKE ('%DEBIT%') THEN transjuego_log.valor
               WHEN transjuego_log.tipo LIKE ('%ROLLBACK%') THEN transjuego_log.valor
               ELSE 0 END)                                                               valor, usuario_mandante.usuario_mandante, usuario_configuracion.valor valorconfig
            FROM transjuego_log
                     INNER JOIN transaccion_juego ON (transaccion_juego.transjuego_id = transjuego_log.transjuego_id)
                     INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id = usuario_mandante.usumandante_id)
                     INNER JOIN producto_mandante ON (producto_mandante.prodmandante_id = transaccion_juego.producto_id)
                     INNER JOIN producto ON (producto.producto_id = producto_mandante.producto_id)
                     INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id)
                     INNER JOIN usuario_configuracion ON (usuario_mandante.usuario_mandante = usuario_configuracion.usuario_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOVIVOANUAL' AND clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "'  AND proveedor.tipo ='LIVECASINO'
              AND usuario_configuracion.estado = 'A'
              AND date_format(transjuego_log.fecha_crea, '%Y-%m-%d %H:%i:%s') >=
                  date_format(DATE_SUB(
                                      DATE_SUB(now(), INTERVAL
                                               MOD(
                                                       TIMESTAMPDIFF(SECOND, usuario_configuracion.fecha_modif, now()),
                                                       3600)
                                               SECOND),
                                      INTERVAL 0
                                      SECOND),
                              '%Y-%m-%d %H:%i:%s')
                              
           ) data2) data

WHERE data.valorconfig < valor +  '" . $valor . "'
";
        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);
        $count = $count[0]['.count(*)'];

        if ($count > 0) {
            return 20022;
            return $Clasificador->getClasificadorId();
        }


        return 0;
    }


    /**
     * Verificar si el usuario tiene limites de apuesta de casino  y excede estos con el nueva apuesta casino
     *
     * @param String $orderColumn nombre de la columna
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryVerifiyLimitesCasino(UsuarioConfiguracion $UsuarioConfiguracion, $valor, $UsuarioMandante)
    {

        //$msc = microtime(true);
        //echo ("automations: " . $msc) . PHP_EOL;
        $usumandanteId = $UsuarioMandante->usumandanteId;

        $Clasificador = new Clasificador("", "LIMAPUCASINOSIMPLE");

        $sql = "SELECT count(*) FROM usuario_configuracion WHERE tipo = '" . $Clasificador->getClasificadorId() . "' AND usuario_id='" . $UsuarioConfiguracion->getUsuarioId() . "'  AND estado='A' AND valor < '" . $valor . "'";
        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);

        $count = $count[0]['.count(*)'];

        if ($count > 0) {
            return 20013;
            return $Clasificador->getClasificadorId();
        }
        //echo ("automations:1 " . (microtime(true) -$msc)) . PHP_EOL;
        //$msc = microtime(true);

        // $Clasificador = new Clasificador("", "LIMAPUCASINODIARIO");
        $fechaModif = "SELECT usuario_configuracion.fecha_modif as fechaModif, clasificador.abreviado as abreviado FROM usuario_configuracion INNER JOIN clasificador ON ( clasificador.clasificador_id = usuario_configuracion.tipo) WHERE usuario_configuracion.usuario_id ='" . $UsuarioConfiguracion->getUsuarioId() . "' AND usuario_configuracion.estado= 'A'";
        $sqlQuery = new SqlQuery($fechaModif);
        $fechaModif = $this->execute2($sqlQuery);
        $fechaModifDiario = "";
        $fechaModifDiario2 = "";
        $fechaModifSemana = "";
        $fechaModifSemana2 = "";
        $fechaModifMensual = "";
        $fechaModifMensual2 = "";
        $fechaModifAnual = "";
        $fechaModifAnual2 = "";


        foreach ($fechaModif as $fecha) {

            if ($fecha['clasificador.abreviado'] == "LIMAPUCASINODIARIO") {

                $diff = ((time() - strtotime($fecha['usuario_configuracion.fechaModif'])) % 86400);
                $fechaModifDiario = date("Y-m-d H:i:s", strtotime("- $diff sec"));
                $diff2 = ((time() - strtotime($fecha['usuario_configuracion.fechaModif'])) % 3600);
                $fechaModifDiario2 = date("Y-m-d H:i:s", strtotime("- $diff2 sec"));

            }
            if ($fecha['clasificador.abreviado'] == "LIMAPUCASINOSEMANA") {
                $diff = ((time() - strtotime($fecha['usuario_configuracion.fechaModif'])) % 604800);
                $fechaModifSemana = date("Y-m-d H:i:s", strtotime("- $diff sec"));
                $diff2 = ((time() - strtotime($fecha['usuario_configuracion.fechaModif'])) % 3600);
                $fechaModifSemana2 = date("Y-m-d H:i:s", strtotime("- $diff2 sec"));
            }

            if ($fecha['clasificador.abreviado'] == "LIMAPUCASINOMENSUAL") {
                $diff = ((time() - strtotime($fecha['usuario_configuracion.fechaModif'])) % 2592000);
                $fechaModifMensual = date("Y-m-d H:i:s", strtotime("- $diff sec"));
                $diff2 = ((time() - strtotime($fecha['usuario_configuracion.fechaModif'])) % 3600);
                $fechaModifMensual2 = date("Y-m-d H:i:s", strtotime("- $diff2 sec"));
            }
            if ($fecha['clasificador.abreviado'] == "LIMAPUCASINOANUAL") {
                $diff = ((time() - strtotime($fecha['usuario_configuracion.fechaModif'])) % 31536000);
                $fechaModifAnual = date("Y-m-d H:i:s", strtotime("- $diff sec"));
                $diff2 = ((time() - strtotime($fecha['usuario_configuracion.fechaModif'])) % 3600);
                $fechaModifAnual2 = date("Y-m-d H:i:s", strtotime("- $diff2 sec"));
            }

        }


        if ($fechaModifDiario != '' && $fechaModifDiario2 != '') {

            $sql = "SELECT count(*)
FROM (SELECT SUM(valor) valor,valorconfig
      FROM (SELECT 0 valor, usuario_configuracion.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_configuracion
                    INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINODIARIO' AND
                                                clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "'
              AND usuario_configuracion.estado = 'A' 
            UNION 
              SELECT SUM(usuario_configuracion_resumen.valor) valor, usuario_configuracion.usuario_id, 0 valorconfig
            FROM usuario_configuracion_resumen
                     INNER JOIN usuario_configuracion
                                ON (usuario_configuracion.usuconfig_id =
                                    usuario_configuracion_resumen.usuconfig_id)
                    INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINODIARIO' AND
                                                clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "'
              AND usuario_configuracion.estado = 'A' 
              AND usuario_configuracion_resumen.fecha_crea >= '$fechaModifDiario'

            UNION

            SELECT SUM(CASE
               WHEN transjuego_log.tipo LIKE ('DEBIT%') THEN transjuego_log.valor
               WHEN transjuego_log.tipo LIKE ('ROLLBACK%') THEN transjuego_log.valor
               ELSE 0 END)                                                               valor, usuario_mandante.usuario_mandante, usuario_configuracion.valor valorconfig
            FROM transjuego_log
                     INNER JOIN transaccion_juego ON (transaccion_juego.transjuego_id = transjuego_log.transjuego_id)
                     INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id = usuario_mandante.usumandante_id)
                     INNER JOIN producto_mandante ON (producto_mandante.prodmandante_id = transaccion_juego.producto_id)
                     INNER JOIN producto ON (producto.producto_id = producto_mandante.producto_id)
                     INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id)
                     INNER JOIN usuario_configuracion ON (usuario_mandante.usuario_mandante = usuario_configuracion.usuario_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINODIARIO' AND clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_mandante.usumandante_id = $usumandanteId AND proveedor.tipo ='CASINO'
              AND usuario_configuracion.estado = 'A'
              AND transjuego_log.fecha_crea >= '$fechaModifDiario2'
                  
                              
           ) data2) data

WHERE data.valorconfig < valor +  '" . $valor . "'
";


            $sqlQuery = new SqlQuery($sql);

            $count = $this->execute2($sqlQuery);
            $count = $count[0]['.count(*)'];

            if ($count > 0) {
                return 20014;
                return $Clasificador->getClasificadorId();
            }
        }
        if ($fechaModifSemana != '' && $fechaModifSemana2 != '') {
            $sql = "SELECT count(*)
FROM (SELECT SUM(valor) valor,valorconfig
      FROM (SELECT 0 valor, usuario_configuracion.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_configuracion
                    INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOSEMANA' AND
                                                clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "'
              AND usuario_configuracion.estado = 'A' 
            UNION 
              SELECT SUM(usuario_configuracion_resumen.valor) valor, usuario_configuracion.usuario_id, 0 valorconfig
            FROM usuario_configuracion_resumen
                     INNER JOIN usuario_configuracion
                                ON (usuario_configuracion.usuconfig_id =
                                    usuario_configuracion_resumen.usuconfig_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOSEMANA' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "'
              AND usuario_configuracion.estado = 'A'
              AND usuario_configuracion_resumen.fecha_crea >= '$fechaModifSemana'

            UNION

            SELECT SUM(CASE
               WHEN transjuego_log.tipo LIKE ('DEBIT%') THEN transjuego_log.valor
               WHEN transjuego_log.tipo LIKE ('ROLLBACK%') THEN transjuego_log.valor
               ELSE 0 END)                                                               valor, usuario_mandante.usuario_mandante, usuario_configuracion.valor valorconfig
            FROM transjuego_log
                     INNER JOIN transaccion_juego ON (transaccion_juego.transjuego_id = transjuego_log.transjuego_id)
                     INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id = usuario_mandante.usumandante_id)
                     INNER JOIN producto_mandante ON (producto_mandante.prodmandante_id = transaccion_juego.producto_id)
                     INNER JOIN producto ON (producto.producto_id = producto_mandante.producto_id)
                     INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id)
                     INNER JOIN usuario_configuracion ON (usuario_mandante.usuario_mandante = usuario_configuracion.usuario_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOSEMANA' AND clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_mandante.usumandante_id = $usumandanteId AND proveedor.tipo ='CASINO'
              AND usuario_configuracion.estado = 'A' 
              AND transjuego_log.fecha_crea >= '$fechaModifSemana2'
                              
           ) data2) data

WHERE data.valorconfig < valor +  '" . $valor . "'
";

            $sqlQuery = new SqlQuery($sql);

            $count = $this->execute2($sqlQuery);
            $count = $count[0]['.count(*)'];

            if ($count > 0) {
                return 20015;
                return $Clasificador->getClasificadorId();
            }
        }

        if ($fechaModifMensual != '' && $fechaModifMensual2 != '') {
            $sql = "SELECT count(*) 
FROM (SELECT SUM(valor) valor,valorconfig
      FROM (SELECT 0 valor, usuario_configuracion.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_configuracion
                    INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOMENSUAL' AND
                                                clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "'
              AND usuario_configuracion.estado = 'A' 
            UNION 
              SELECT SUM(usuario_configuracion_resumen.valor) valor, usuario_configuracion.usuario_id, 0 valorconfig
            FROM usuario_configuracion_resumen
                     INNER JOIN usuario_configuracion
                                ON (usuario_configuracion.usuconfig_id =
                                    usuario_configuracion_resumen.usuconfig_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOMENSUAL' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "'
              AND usuario_configuracion.estado = 'A' 
              AND usuario_configuracion_resumen.fecha_crea >= '$fechaModifMensual'
                 

            UNION

            SELECT SUM(CASE
               WHEN transjuego_log.tipo LIKE ('DEBIT%') THEN transjuego_log.valor
               WHEN transjuego_log.tipo LIKE ('ROLLBACK%') THEN transjuego_log.valor
               ELSE 0 END)                                                               valor, usuario_mandante.usuario_mandante, usuario_configuracion.valor valorconfig
            FROM transjuego_log
                     INNER JOIN transaccion_juego ON (transaccion_juego.transjuego_id = transjuego_log.transjuego_id)
                     INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id = usuario_mandante.usumandante_id)
                     INNER JOIN producto_mandante ON (producto_mandante.prodmandante_id = transaccion_juego.producto_id)
                     INNER JOIN producto ON (producto.producto_id = producto_mandante.producto_id)
                     INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id)
                     INNER JOIN usuario_configuracion ON (usuario_mandante.usuario_mandante = usuario_configuracion.usuario_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOMENSUAL' AND clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_mandante.usumandante_id = $usumandanteId AND proveedor.tipo ='CASINO'
              AND usuario_configuracion.estado = 'A' 
              AND transjuego_log.fecha_crea >= '$fechaModifMensual2'
                              
           ) data2) data

WHERE data.valorconfig < valor +  '" . $valor . "'
";
            $sqlQuery = new SqlQuery($sql);

            $count = $this->execute2($sqlQuery);
            $count = $count[0]['.count(*)'];

            if ($count > 0) {
                return 20016;
                return $Clasificador->getClasificadorId();
            }

        }

        if ($fechaModifAnual != '' && $fechaModifAnual2 != '') {


            $sql = "SELECT count(*)
FROM (SELECT SUM(valor) valor,valorconfig
      FROM (SELECT 0 valor, usuario_configuracion.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_configuracion
                    INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOANUAL' AND
                                                clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "'
              AND usuario_configuracion.estado = 'A' 
            UNION 
              SELECT SUM(usuario_configuracion_resumen.valor) valor, usuario_configuracion.usuario_id, 0 valorconfig
            FROM usuario_configuracion_resumen
                     INNER JOIN usuario_configuracion
                                ON (usuario_configuracion.usuconfig_id =
                                    usuario_configuracion_resumen.usuconfig_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOANUAL' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "'
              AND usuario_configuracion.estado = 'A'
              AND usuario_configuracion_resumen.fecha_crea >= '$fechaModifAnual'

            UNION

            SELECT SUM(CASE
               WHEN transjuego_log.tipo LIKE ('DEBIT%') THEN transjuego_log.valor
               WHEN transjuego_log.tipo LIKE ('ROLLBACK%') THEN transjuego_log.valor
               ELSE 0 END)                                                               valor, usuario_mandante.usuario_mandante, usuario_configuracion.valor valorconfig
            FROM transjuego_log
                     INNER JOIN transaccion_juego ON (transaccion_juego.transjuego_id = transjuego_log.transjuego_id)
                     INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id = usuario_mandante.usumandante_id)
                     INNER JOIN producto_mandante ON (producto_mandante.prodmandante_id = transaccion_juego.producto_id)
                     INNER JOIN producto ON (producto.producto_id = producto_mandante.producto_id)
                     INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id)
                     INNER JOIN usuario_configuracion ON (usuario_mandante.usuario_mandante = usuario_configuracion.usuario_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOANUAL' AND clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_mandante.usumandante_id = $usumandanteId  AND proveedor.tipo ='CASINO'
              AND usuario_configuracion.estado = 'A' 
              AND transjuego_log.fecha_crea >= '$fechaModifAnual2'
                              
           ) data2) data

WHERE data.valorconfig < valor +  '" . $valor . "'
";

            $sqlQuery = new SqlQuery($sql);

            $count = $this->execute2($sqlQuery);
            $count = $count[0]['.count(*)'];

            if ($count > 0) {
                return 20017;
                return $Clasificador->getClasificadorId();
            }
        }


        return 0;
    }


    /**
     * Verificar si el usuario tiene limites de apuesta de casino  y excede estos con el nueva apuesta casino
     *
     * @param String $orderColumn nombre de la columna
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryVerifiyLimitesVirtuales(UsuarioConfiguracion $UsuarioConfiguracion, $valor, $UsuarioMandante)
    {

        //$msc = microtime(true);
        //echo ("automations: " . $msc) . PHP_EOL;
        $usumandanteId = $UsuarioMandante->usumandanteId;

        $Clasificador = new Clasificador("", "LIMAPUVIRTUALESSIMPLE");

        $sql = "SELECT count(*) FROM usuario_configuracion WHERE tipo = '" . $Clasificador->getClasificadorId() . "' AND usuario_id='" . $UsuarioConfiguracion->getUsuarioId() . "'  AND estado='A' AND valor < '" . $valor . "'";
        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);

        $count = $count[0]['.count(*)'];

        if ($count > 0) {
            return 20013;
            return $Clasificador->getClasificadorId();
        }
        //echo ("automations:1 " . (microtime(true) -$msc)) . PHP_EOL;
        //$msc = microtime(true);

        // $Clasificador = new Clasificador("", "LIMAPUCASINODIARIO");
        $fechaModif = "SELECT usuario_configuracion.fecha_modif as fechaModif, clasificador.abreviado as abreviado FROM usuario_configuracion INNER JOIN clasificador ON ( clasificador.clasificador_id = usuario_configuracion.tipo) WHERE usuario_configuracion.usuario_id ='" . $UsuarioConfiguracion->getUsuarioId() . "' AND usuario_configuracion.estado= 'A'";
        $sqlQuery = new SqlQuery($fechaModif);
        $fechaModif = $this->execute2($sqlQuery);
        $fechaModifDiario = "";
        $fechaModifDiario2 = "";
        $fechaModifSemana = "";
        $fechaModifSemana2 = "";
        $fechaModifMensual = "";
        $fechaModifMensual2 = "";
        $fechaModifAnual = "";
        $fechaModifAnual2 = "";


        foreach ($fechaModif as $fecha) {

            if ($fecha['clasificador.abreviado'] == "LIMAPUVIRTUALESDIARIO") {

                $diff = ((time() - strtotime($fecha['usuario_configuracion.fechaModif'])) % 86400);
                $fechaModifDiario = date("Y-m-d H:i:s", strtotime("- $diff sec"));
                $diff2 = ((time() - strtotime($fecha['usuario_configuracion.fechaModif'])) % 3600);
                $fechaModifDiario2 = date("Y-m-d H:i:s", strtotime("- $diff2 sec"));

            }
            if ($fecha['clasificador.abreviado'] == "LIMAPUVIRTUALESSEMANA") {
                $diff = ((time() - strtotime($fecha['usuario_configuracion.fechaModif'])) % 604800);
                $fechaModifSemana = date("Y-m-d H:i:s", strtotime("- $diff sec"));
                $diff2 = ((time() - strtotime($fecha['usuario_configuracion.fechaModif'])) % 3600);
                $fechaModifSemana2 = date("Y-m-d H:i:s", strtotime("- $diff2 sec"));
            }

            if ($fecha['clasificador.abreviado'] == "LIMAPUVIRTUALESMENSUAL") {
                $diff = ((time() - strtotime($fecha['usuario_configuracion.fechaModif'])) % 2592000);
                $fechaModifMensual = date("Y-m-d H:i:s", strtotime("- $diff sec"));
                $diff2 = ((time() - strtotime($fecha['usuario_configuracion.fechaModif'])) % 3600);
                $fechaModifMensual2 = date("Y-m-d H:i:s", strtotime("- $diff2 sec"));
            }
            if ($fecha['clasificador.abreviado'] == "LIMAPUVIRTUALESANUAL") {
                $diff = ((time() - strtotime($fecha['usuario_configuracion.fechaModif'])) % 31536000);
                $fechaModifAnual = date("Y-m-d H:i:s", strtotime("- $diff sec"));
                $diff2 = ((time() - strtotime($fecha['usuario_configuracion.fechaModif'])) % 3600);
                $fechaModifAnual2 = date("Y-m-d H:i:s", strtotime("- $diff2 sec"));
            }

        }


        if ($fechaModifDiario != '' && $fechaModifDiario2 != '') {

            $sql = "SELECT count(*)
FROM (SELECT SUM(valor) valor,valorconfig
      FROM (SELECT 0 valor, usuario_configuracion.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_configuracion
                    INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUVIRTUALESDIARIO' AND
                                                clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "'
              AND usuario_configuracion.estado = 'A' 
            UNION 
              SELECT SUM(usuario_configuracion_resumen.valor) valor, usuario_configuracion.usuario_id, 0 valorconfig
            FROM usuario_configuracion_resumen
                     INNER JOIN usuario_configuracion
                                ON (usuario_configuracion.usuconfig_id =
                                    usuario_configuracion_resumen.usuconfig_id)
                    INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUVIRTUALESDIARIO' AND
                                                clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "'
              AND usuario_configuracion.estado = 'A' 
              AND usuario_configuracion_resumen.fecha_crea >= '$fechaModifDiario'

            UNION

            SELECT SUM(CASE
               WHEN transjuego_log.tipo LIKE ('DEBIT%') THEN transjuego_log.valor
               WHEN transjuego_log.tipo LIKE ('ROLLBACK%') THEN transjuego_log.valor
               ELSE 0 END)                                                               valor, usuario_mandante.usuario_mandante, usuario_configuracion.valor valorconfig
            FROM transjuego_log
                     INNER JOIN transaccion_juego ON (transaccion_juego.transjuego_id = transjuego_log.transjuego_id)
                     INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id = usuario_mandante.usumandante_id)
                     INNER JOIN producto_mandante ON (producto_mandante.prodmandante_id = transaccion_juego.producto_id)
                     INNER JOIN producto ON (producto.producto_id = producto_mandante.producto_id)
                     INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id)
                     INNER JOIN usuario_configuracion ON (usuario_mandante.usuario_mandante = usuario_configuracion.usuario_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUVIRTUALESDIARIO' AND clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_mandante.usumandante_id = $usumandanteId AND proveedor.tipo ='VIRTUAL'
              AND usuario_configuracion.estado = 'A'
              AND transjuego_log.fecha_crea >= '$fechaModifDiario2'
                  
                              
           ) data2) data

WHERE data.valorconfig < valor +  '" . $valor . "'
";


            $sqlQuery = new SqlQuery($sql);

            $count = $this->execute2($sqlQuery);
            $count = $count[0]['.count(*)'];

            if ($count > 0) {
                return 20014;
                return $Clasificador->getClasificadorId();
            }
        }
        if ($fechaModifSemana != '' && $fechaModifSemana2 != '') {
            $sql = "SELECT count(*)
FROM (SELECT SUM(valor) valor,valorconfig
      FROM (SELECT 0 valor, usuario_configuracion.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_configuracion
                    INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUVIRTUALESSEMANA' AND
                                                clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "'
              AND usuario_configuracion.estado = 'A' 
            UNION 
              SELECT SUM(usuario_configuracion_resumen.valor) valor, usuario_configuracion.usuario_id, 0 valorconfig
            FROM usuario_configuracion_resumen
                     INNER JOIN usuario_configuracion
                                ON (usuario_configuracion.usuconfig_id =
                                    usuario_configuracion_resumen.usuconfig_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUVIRTUALESSEMANA' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "'
              AND usuario_configuracion.estado = 'A'
              AND usuario_configuracion_resumen.fecha_crea >= '$fechaModifSemana'

            UNION

            SELECT SUM(CASE
               WHEN transjuego_log.tipo LIKE ('DEBIT%') THEN transjuego_log.valor
               WHEN transjuego_log.tipo LIKE ('ROLLBACK%') THEN transjuego_log.valor
               ELSE 0 END)                                                               valor, usuario_mandante.usuario_mandante, usuario_configuracion.valor valorconfig
            FROM transjuego_log
                     INNER JOIN transaccion_juego ON (transaccion_juego.transjuego_id = transjuego_log.transjuego_id)
                     INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id = usuario_mandante.usumandante_id)
                     INNER JOIN producto_mandante ON (producto_mandante.prodmandante_id = transaccion_juego.producto_id)
                     INNER JOIN producto ON (producto.producto_id = producto_mandante.producto_id)
                     INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id)
                     INNER JOIN usuario_configuracion ON (usuario_mandante.usuario_mandante = usuario_configuracion.usuario_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUVIRTUALESSEMANA' AND clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_mandante.usumandante_id = $usumandanteId AND proveedor.tipo ='VIRTUAL'
              AND usuario_configuracion.estado = 'A' 
              AND transjuego_log.fecha_crea >= '$fechaModifSemana2'
                              
           ) data2) data

WHERE data.valorconfig < valor +  '" . $valor . "'
";

            $sqlQuery = new SqlQuery($sql);

            $count = $this->execute2($sqlQuery);
            $count = $count[0]['.count(*)'];

            if ($count > 0) {
                return 20015;
                return $Clasificador->getClasificadorId();
            }
        }

        if ($fechaModifMensual != '' && $fechaModifMensual2 != '') {
            $sql = "SELECT count(*) 
FROM (SELECT SUM(valor) valor,valorconfig
      FROM (SELECT 0 valor, usuario_configuracion.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_configuracion
                    INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUVIRTUALESMENSUAL' AND
                                                clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "'
              AND usuario_configuracion.estado = 'A' 
            UNION 
              SELECT SUM(usuario_configuracion_resumen.valor) valor, usuario_configuracion.usuario_id, 0 valorconfig
            FROM usuario_configuracion_resumen
                     INNER JOIN usuario_configuracion
                                ON (usuario_configuracion.usuconfig_id =
                                    usuario_configuracion_resumen.usuconfig_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUVIRTUALESMENSUAL' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "'
              AND usuario_configuracion.estado = 'A' 
              AND usuario_configuracion_resumen.fecha_crea >= '$fechaModifMensual'
                 

            UNION

            SELECT SUM(CASE
               WHEN transjuego_log.tipo LIKE ('DEBIT%') THEN transjuego_log.valor
               WHEN transjuego_log.tipo LIKE ('ROLLBACK%') THEN transjuego_log.valor
               ELSE 0 END)                                                               valor, usuario_mandante.usuario_mandante, usuario_configuracion.valor valorconfig
            FROM transjuego_log
                     INNER JOIN transaccion_juego ON (transaccion_juego.transjuego_id = transjuego_log.transjuego_id)
                     INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id = usuario_mandante.usumandante_id)
                     INNER JOIN producto_mandante ON (producto_mandante.prodmandante_id = transaccion_juego.producto_id)
                     INNER JOIN producto ON (producto.producto_id = producto_mandante.producto_id)
                     INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id)
                     INNER JOIN usuario_configuracion ON (usuario_mandante.usuario_mandante = usuario_configuracion.usuario_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUVIRTUALESMENSUAL' AND clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_mandante.usumandante_id = $usumandanteId AND proveedor.tipo ='VIRTUAL'
              AND usuario_configuracion.estado = 'A' 
              AND transjuego_log.fecha_crea >= '$fechaModifMensual2'
                              
           ) data2) data

WHERE data.valorconfig < valor +  '" . $valor . "'
";
            $sqlQuery = new SqlQuery($sql);

            $count = $this->execute2($sqlQuery);
            $count = $count[0]['.count(*)'];

            if ($count > 0) {
                return 20016;
                return $Clasificador->getClasificadorId();
            }

        }

        if ($fechaModifAnual != '' && $fechaModifAnual2 != '') {


            $sql = "SELECT count(*)
FROM (SELECT SUM(valor) valor,valorconfig
      FROM (SELECT 0 valor, usuario_configuracion.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_configuracion
                    INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUVIRTUALESANUAL' AND
                                                clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "'
              AND usuario_configuracion.estado = 'A' 
            UNION 
              SELECT SUM(usuario_configuracion_resumen.valor) valor, usuario_configuracion.usuario_id, 0 valorconfig
            FROM usuario_configuracion_resumen
                     INNER JOIN usuario_configuracion
                                ON (usuario_configuracion.usuconfig_id =
                                    usuario_configuracion_resumen.usuconfig_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUVIRTUALESANUAL' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "'
              AND usuario_configuracion.estado = 'A'
              AND usuario_configuracion_resumen.fecha_crea >= '$fechaModifAnual'

            UNION

            SELECT SUM(CASE
               WHEN transjuego_log.tipo LIKE ('DEBIT%') THEN transjuego_log.valor
               WHEN transjuego_log.tipo LIKE ('ROLLBACK%') THEN transjuego_log.valor
               ELSE 0 END)                                                               valor, usuario_mandante.usuario_mandante, usuario_configuracion.valor valorconfig
            FROM transjuego_log
                     INNER JOIN transaccion_juego ON (transaccion_juego.transjuego_id = transjuego_log.transjuego_id)
                     INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id = usuario_mandante.usumandante_id)
                     INNER JOIN producto_mandante ON (producto_mandante.prodmandante_id = transaccion_juego.producto_id)
                     INNER JOIN producto ON (producto.producto_id = producto_mandante.producto_id)
                     INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id)
                     INNER JOIN usuario_configuracion ON (usuario_mandante.usuario_mandante = usuario_configuracion.usuario_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUVIRTUALESANUAL' AND clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_mandante.usumandante_id = $usumandanteId  AND proveedor.tipo ='VIRTUAL'
              AND usuario_configuracion.estado = 'A' 
              AND transjuego_log.fecha_crea >= '$fechaModifAnual2'
                              
           ) data2) data

WHERE data.valorconfig < valor +  '" . $valor . "'
";

            $sqlQuery = new SqlQuery($sql);

            $count = $this->execute2($sqlQuery);
            $count = $count[0]['.count(*)'];

            if ($count > 0) {
                return 20017;
                return $Clasificador->getClasificadorId();
            }
        }


        return 0;
    }


    /**
     * Verificar si el usuario tiene limites de apuesta de casino  y excede estos con el nueva apuesta casino
     *
     * @param String $orderColumn nombre de la columna
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryVerifiyLimitesDeportivas(UsuarioConfiguracion $UsuarioConfiguracion, $valor, $UsuarioMandante)
    {

        //$msc = microtime(true);
        //echo ("automations: " . $msc) . PHP_EOL;
        $usumandanteId = $UsuarioMandante->usumandanteId;

        $Clasificador = new Clasificador("", "LIMAPUCASINOSIMPLE");

        $sql = "SELECT count(*) FROM usuario_configuracion WHERE tipo = '" . $Clasificador->getClasificadorId() . "' AND usuario_id='" . $UsuarioConfiguracion->getUsuarioId() . "'  AND estado='A' AND valor < '" . $valor . "'";
        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);

        $count = $count[0]['.count(*)'];

        if ($count > 0) {
            return 20013;
            return $Clasificador->getClasificadorId();
        }
        //echo ("automations:1 " . (microtime(true) -$msc)) . PHP_EOL;
        //$msc = microtime(true);

        // $Clasificador = new Clasificador("", "LIMAPUCASINODIARIO");
        $fechaModif = "SELECT usuario_configuracion.fecha_modif as fechaModif, clasificador.abreviado as abreviado FROM usuario_configuracion INNER JOIN clasificador ON ( clasificador.clasificador_id = usuario_configuracion.tipo) WHERE usuario_configuracion.usuario_id ='" . $UsuarioConfiguracion->getUsuarioId() . "' AND usuario_configuracion.estado= 'A'";
        $sqlQuery = new SqlQuery($fechaModif);
        $fechaModif = $this->execute2($sqlQuery);
        $fechaModifDiario = "";
        $fechaModifDiario2 = "";
        $fechaModifSemana = "";
        $fechaModifSemana2 = "";
        $fechaModifMensual = "";
        $fechaModifMensual2 = "";
        $fechaModifAnual = "";
        $fechaModifAnual2 = "";


        foreach ($fechaModif as $fecha) {

            if ($fecha['clasificador.abreviado'] == "LIMAPUDEPORTIVADIARIO") {

                $diff = ((time() - strtotime($fecha['usuario_configuracion.fechaModif'])) % 86400);
                $fechaModifDiario = date("Y-m-d H:i:s", strtotime("- $diff sec"));
                $diff2 = ((time() - strtotime($fecha['usuario_configuracion.fechaModif'])) % 3600);
                $fechaModifDiario2 = date("Y-m-d H:i:s", strtotime("- $diff2 sec"));

            }
            if ($fecha['clasificador.abreviado'] == "LIMAPUDEPORTIVASEMANA") {
                $diff = ((time() - strtotime($fecha['usuario_configuracion.fechaModif'])) % 604800);
                $fechaModifSemana = date("Y-m-d H:i:s", strtotime("- $diff sec"));
                $diff2 = ((time() - strtotime($fecha['usuario_configuracion.fechaModif'])) % 3600);
                $fechaModifSemana2 = date("Y-m-d H:i:s", strtotime("- $diff2 sec"));
            }

            if ($fecha['clasificador.abreviado'] == "LIMAPUDEPORTIVAMENSUAL") {
                $diff = ((time() - strtotime($fecha['usuario_configuracion.fechaModif'])) % 2592000);
                $fechaModifMensual = date("Y-m-d H:i:s", strtotime("- $diff sec"));
                $diff2 = ((time() - strtotime($fecha['usuario_configuracion.fechaModif'])) % 3600);
                $fechaModifMensual2 = date("Y-m-d H:i:s", strtotime("- $diff2 sec"));
            }
            if ($fecha['clasificador.abreviado'] == "LIMAPUDEPORTIVAANUAL") {
                $diff = ((time() - strtotime($fecha['usuario_configuracion.fechaModif'])) % 31536000);
                $fechaModifAnual = date("Y-m-d H:i:s", strtotime("- $diff sec"));
                $diff2 = ((time() - strtotime($fecha['usuario_configuracion.fechaModif'])) % 3600);
                $fechaModifAnual2 = date("Y-m-d H:i:s", strtotime("- $diff2 sec"));
            }

        }


        if ($fechaModifDiario2 != '') {


            $sql = "SELECT count(*)
FROM (SELECT SUM(valor) valor,valorconfig
      FROM (SELECT 0 valor, usuario_configuracion.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_configuracion
                    INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUDEPORTIVADIARIO' AND
                                                clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "'
              AND usuario_configuracion.estado = 'A' 
            UNION 
              SELECT SUM(usuario_configuracion_resumen.valor) valor, usuario_configuracion.usuario_id, 0 valorconfig
            FROM usuario_configuracion_resumen
                     INNER JOIN usuario_configuracion
                                ON (usuario_configuracion.usuconfig_id =
                                    usuario_configuracion_resumen.usuconfig_id)
                    INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUDEPORTIVADIARIO' AND
                                                clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "'
              AND usuario_configuracion.estado = 'A' 
              AND usuario_configuracion_resumen.fecha_crea >= '$fechaModifDiario'

            UNION

            SELECT SUM(CASE
               WHEN it_transaccion.tipo IN ('BET', 'STAKEDECREASE', 'REFUND') THEN it_transaccion.valor
               WHEN it_transaccion.tipo  IN ('WIN', 'NEWCREDIT', 'CASHOUT') THEN it_transaccion.valor
               ELSE 0 END)                                                               valor, usuario_mandante.usuario_mandante, usuario_configuracion.valor valorconfig
            FROM it_transaccion
                     INNER JOIN it_ticket_enc ON (it_transaccion.ticket_id = it_ticket_enc.ticket_id)
                     INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
                     INNER JOIN usuario_mandante ON (usuario.usuario_id = usuario_mandante.usuario_mandante)
                     INNER JOIN usuario_configuracion
                                ON (usuario.usuario_id = usuario_configuracion.usuario_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUDEPORTIVADIARIO' AND clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_mandante.usumandante_id = $usumandanteId
              AND usuario_configuracion.estado = 'A'
              AND it_transaccion.fecha_crea >= '$fechaModifDiario2'
                  
                              
           ) data2) data

WHERE data.valorconfig < valor +  '" . $valor . "'
";


            $sqlQuery = new SqlQuery($sql);

            $count = $this->execute2($sqlQuery);
            $count = $count[0]['.count(*)'];

            if ($count > 0) {
                return 20014;
                return $Clasificador->getClasificadorId();
            }
        }

        if ($fechaModifSemana2 != '') {

            $sql = "SELECT count(*)
FROM (SELECT SUM(valor) valor,valorconfig
      FROM (SELECT 0 valor, usuario_configuracion.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_configuracion
                    INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUDEPORTIVASEMANA' AND
                                                clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "'
              AND usuario_configuracion.estado = 'A' 
            UNION 
              SELECT SUM(usuario_configuracion_resumen.valor) valor, usuario_configuracion.usuario_id, 0 valorconfig
            FROM usuario_configuracion_resumen
                     INNER JOIN usuario_configuracion
                                ON (usuario_configuracion.usuconfig_id =
                                    usuario_configuracion_resumen.usuconfig_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUDEPORTIVASEMANA' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "'
              AND usuario_configuracion.estado = 'A'
              AND usuario_configuracion_resumen.fecha_crea >= '$fechaModifSemana'

            UNION

            SELECT SUM(CASE
               WHEN it_transaccion.tipo IN ('BET', 'STAKEDECREASE', 'REFUND') THEN it_transaccion.valor
               WHEN it_transaccion.tipo  IN ('WIN', 'NEWCREDIT', 'CASHOUT') THEN it_transaccion.valor
               ELSE 0 END)                                                               valor, usuario.usuario_id, usuario_configuracion.valor valorconfig
            FROM it_transaccion
                     INNER JOIN it_ticket_enc ON (it_transaccion.ticket_id = it_ticket_enc.ticket_id)
                     INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
                     INNER JOIN usuario_mandante ON (usuario.usuario_id = usuario_mandante.usuario_mandante)
                     INNER JOIN usuario_configuracion
                                ON (usuario.usuario_id = usuario_configuracion.usuario_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUDEPORTIVASEMANA' AND clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_mandante.usumandante_id = $usumandanteId 
              AND usuario_configuracion.estado = 'A' 
              AND it_transaccion.fecha_crea >= '$fechaModifSemana2'
                              
           ) data2) data

WHERE data.valorconfig < valor +  '" . $valor . "'
";

            $sqlQuery = new SqlQuery($sql);

            $count = $this->execute2($sqlQuery);
            $count = $count[0]['.count(*)'];

            if ($count > 0) {
                return 20015;
                return $Clasificador->getClasificadorId();
            }

        }

        if ($fechaModifMensual2 != '') {


            $sql = "SELECT count(*) 
FROM (SELECT SUM(valor) valor,valorconfig
      FROM (SELECT 0 valor, usuario_configuracion.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_configuracion
                    INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUDEPORTIVAMENSUAL' AND
                                                clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "'
              AND usuario_configuracion.estado = 'A' 
            UNION 
              SELECT SUM(usuario_configuracion_resumen.valor) valor, usuario_configuracion.usuario_id, 0 valorconfig
            FROM usuario_configuracion_resumen
                     INNER JOIN usuario_configuracion
                                ON (usuario_configuracion.usuconfig_id =
                                    usuario_configuracion_resumen.usuconfig_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUDEPORTIVAMENSUAL' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "'
              AND usuario_configuracion.estado = 'A' 
              AND usuario_configuracion_resumen.fecha_crea >= '$fechaModifMensual'
                 

            UNION

            SELECT SUM(CASE
               WHEN it_transaccion.tipo IN ('BET', 'STAKEDECREASE', 'REFUND') THEN it_transaccion.valor
               WHEN it_transaccion.tipo  IN ('WIN', 'NEWCREDIT', 'CASHOUT') THEN it_transaccion.valor
               ELSE 0 END)                                                               valor, usuario_mandante.usuario_mandante, usuario_configuracion.valor valorconfig
            FROM it_transaccion
                     INNER JOIN it_ticket_enc ON (it_transaccion.ticket_id = it_ticket_enc.ticket_id)
                     INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
                     INNER JOIN usuario_mandante ON (usuario.usuario_id = usuario_mandante.usuario_mandante)
                     INNER JOIN usuario_configuracion
                                ON (usuario.usuario_id = usuario_configuracion.usuario_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUDEPORTIVAMENSUAL' AND clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_mandante.usumandante_id = $usumandanteId
              AND usuario_configuracion.estado = 'A' 
              AND it_transaccion.fecha_crea >= '$fechaModifMensual2'
                              
           ) data2) data

WHERE data.valorconfig < valor +  '" . $valor . "'
";
            $sqlQuery = new SqlQuery($sql);

            $count = $this->execute2($sqlQuery);
            $count = $count[0]['.count(*)'];

            if ($count > 0) {
                return 20016;
                return $Clasificador->getClasificadorId();
            }

        }

        if ($fechaModifAnual2 != '') {

            $sql = "SELECT count(*)
FROM (SELECT SUM(valor) valor,valorconfig
      FROM (SELECT 0 valor, usuario_configuracion.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_configuracion
                    INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUDEPORTIVAANUAL' AND
                                                clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "'
              AND usuario_configuracion.estado = 'A' 
            UNION 
              SELECT SUM(usuario_configuracion_resumen.valor) valor, usuario_configuracion.usuario_id, 0 valorconfig
            FROM usuario_configuracion_resumen
                     INNER JOIN usuario_configuracion
                                ON (usuario_configuracion.usuconfig_id =
                                    usuario_configuracion_resumen.usuconfig_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUDEPORTIVAANUAL' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "'
              AND usuario_configuracion.estado = 'A'
              AND usuario_configuracion_resumen.fecha_crea >= '$fechaModifAnual'

            UNION

            SELECT SUM(CASE
               WHEN it_transaccion.tipo IN ('BET', 'STAKEDECREASE', 'REFUND') THEN it_transaccion.valor
               WHEN it_transaccion.tipo  IN ('WIN', 'NEWCREDIT', 'CASHOUT') THEN it_transaccion.valor
               ELSE 0 END)                                                               valor, usuario_mandante.usuario_mandante, usuario_configuracion.valor valorconfig
            FROM it_transaccion
                     INNER JOIN it_ticket_enc ON (it_transaccion.ticket_id = it_ticket_enc.ticket_id)
                     INNER JOIN usuario ON (usuario.usuario_id = it_ticket_enc.usuario_id)
                     INNER JOIN usuario_mandante ON (usuario.usuario_id = usuario_mandante.usuario_mandante)
                     INNER JOIN usuario_configuracion
                                ON (usuario.usuario_id = usuario_configuracion.usuario_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUDEPORTIVAANUAL' AND clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_mandante.usumandante_id = $usumandanteId 
              AND usuario_configuracion.estado = 'A' 
              AND it_transaccion.fecha_crea >= '$fechaModifAnual2'
                              
           ) data2) data

WHERE data.valorconfig < valor +  '" . $valor . "'
";

            $sqlQuery = new SqlQuery($sql);

            $count = $this->execute2($sqlQuery);
            $count = $count[0]['.count(*)'];

            if ($count > 0) {
                return 20017;
                return $Clasificador->getClasificadorId();
            }
        }


        return 0;
    }


    /**
     * Verificar si el usuario tiene limites de apuesta de casino vivo y excede estos con el nueva apuesta casino vivo
     *
     * @param String $orderColumn nombre de la columna
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryVerifiyLimitesCasinoVivo(UsuarioConfiguracion $UsuarioConfiguracion, $valor, $UsuarioMandante)
    {

        $usumandanteId = $UsuarioMandante->usumandanteId;
        $Clasificador = new Clasificador("", "LIMAPUCASINOVIVOSIMPLE");

        $sql = "SELECT count(*) FROM usuario_configuracion WHERE tipo = '" . $Clasificador->getClasificadorId() . "' AND usuario_id='" . $UsuarioConfiguracion->getUsuarioId() . "'  AND estado='A' AND valor < '" . $valor . "'";
        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);

        $count = $count[0]['.count(*)'];

        if ($count > 0) {
            return 20018;
            return $Clasificador->getClasificadorId();
        }

        //$Clasificador = new Clasificador("", "LIMAPUCASINOVIVODIARIO");

        // $sql = "SELECT COUNT(*) FROM usuario_configuracion INNER JOIN (SELECT SUM(usuario_recarga.valor) valor,usuario_id FROM usuario_recarga WHERE usuario_id ='" . $UsuarioConfiguracion->getUsuarioId() . "'   AND date_format(fecha_crea, '%Y-%m-%d') =  date_format(now(), '%Y-%m-%d') )data ON (usuario_configuracion.usuario_id = data.usuario_id) WHERE tipo = '" . $Clasificador->getClasificadorId() . "' AND usuario_configuracion.usuario_id='" . $UsuarioConfiguracion->getUsuarioId() . "'  AND estado='A' AND usuario_configuracion.valor < data.valor + '" . $valor . "'";
        $fechaModif = "SELECT usuario_configuracion.fecha_modif as fechaModif, clasificador.abreviado as abreviado FROM usuario_configuracion INNER JOIN clasificador ON ( clasificador.clasificador_id = usuario_configuracion.tipo) WHERE usuario_configuracion.usuario_id ='" . $UsuarioConfiguracion->getUsuarioId() . "' AND usuario_configuracion.estado= 'A'";
        $sqlQuery = new SqlQuery($fechaModif);
        $fechaModif = $this->execute2($sqlQuery);
        $fechaModifDiario = "";
        $fechaModifDiario2 = "";
        $fechaModifSemana = "";
        $fechaModifSemana2 = "";
        $fechaModifMensual = "";
        $fechaModifMensual2 = "";
        $fechaModifAnual = "";
        $fechaModifAnual2 = "";


        foreach ($fechaModif as $fecha) {

            if ($fecha['clasificador.abreviado'] == "LIMAPUCASINOVIVODIARIO") {
                $diff = ((time() - strtotime($fecha['usuario_configuracion.fechaModif'])) % 86400);
                $fechaModifDiario = date("Y-m-d H:i:s", strtotime("- $diff sec"));
                $diff2 = ((time() - strtotime($fecha['usuario_configuracion.fechaModif'])) % 3600);
                $fechaModifDiario2 = date("Y-m-d H:i:s", strtotime("- $diff2 sec"));
            }
            if ($fecha['clasificador.abreviado'] == "LIMAPUCASINOVIVOSEMANA") {
                $diff = ((time() - strtotime($fecha['usuario_configuracion.fechaModif'])) % 604800);
                $fechaModifSemana = date("Y-m-d H:i:s", strtotime("- $diff sec"));
                $diff2 = ((time() - strtotime($fecha['usuario_configuracion.fechaModif'])) % 3600);
                $fechaModifSemana2 = date("Y-m-d H:i:s", strtotime("- $diff2 sec"));
            }

            if ($fecha['clasificador.abreviado'] == "LIMAPUCASINOVIVOMENSUAL") {
                $diff = ((time() - strtotime($fecha['usuario_configuracion.fechaModif'])) % 2592000);
                $fechaModifMensual = date("Y-m-d H:i:s", strtotime("- $diff sec"));
                $diff2 = ((time() - strtotime($fecha['usuario_configuracion.fechaModif'])) % 3600);
                $fechaModifMensual2 = date("Y-m-d H:i:s", strtotime("- $diff2 sec"));
            }
            if ($fecha['clasificador.abreviado'] == "LIMAPUCASINOVIVOANUAL") {
                $diff = ((time() - strtotime($fecha['usuario_configuracion.fechaModif'])) % 31536000);
                $fechaModifAnual = date("Y-m-d H:i:s", strtotime("- $diff sec"));
                $diff2 = ((time() - strtotime($fecha['usuario_configuracion.fechaModif'])) % 3600);
                $fechaModifAnual2 = date("Y-m-d H:i:s", strtotime("- $diff2 sec"));
            }

        }

        if ($fechaModifDiario != '' && $fechaModifDiario2 != '') {

            $sql = "SELECT count(*)
FROM (SELECT SUM(valor) valor,valorconfig
      FROM (SELECT 0 valor, usuario_configuracion.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_configuracion
                    INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOVIVODIARIO' AND
                                                clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "'
              AND usuario_configuracion.estado = 'A' 
            UNION 
              SELECT SUM(usuario_configuracion_resumen.valor) valor, usuario_configuracion.usuario_id, 0 valorconfig
            FROM usuario_configuracion_resumen
                     INNER JOIN usuario_configuracion
                                ON (usuario_configuracion.usuconfig_id =
                                    usuario_configuracion_resumen.usuconfig_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOVIVODIARIO' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "'
              AND usuario_configuracion.estado = 'A'
              AND usuario_configuracion_resumen.fecha_crea >= '$fechaModifDiario'

            UNION

            SELECT SUM(CASE
               WHEN transjuego_log.tipo LIKE ('DEBIT%') THEN transjuego_log.valor
               WHEN transjuego_log.tipo LIKE ('ROLLBACK%') THEN transjuego_log.valor
               ELSE 0 END)                                                               valor, usuario_mandante.usuario_mandante, usuario_configuracion.valor valorconfig
            FROM transjuego_log
                     INNER JOIN transaccion_juego ON (transaccion_juego.transjuego_id = transjuego_log.transjuego_id)
                     INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id = usuario_mandante.usumandante_id)
                     INNER JOIN producto_mandante ON (producto_mandante.prodmandante_id = transaccion_juego.producto_id)
                     INNER JOIN producto ON (producto.producto_id = producto_mandante.producto_id)
                     INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id)
                     INNER JOIN usuario_configuracion ON (usuario_mandante.usuario_mandante = usuario_configuracion.usuario_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOVIVODIARIO' AND clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_mandante.usumandante_id = $usumandanteId AND proveedor.tipo ='LIVECASINO'
              AND usuario_configuracion.estado = 'A' 
              AND transjuego_log.fecha_crea >= '$fechaModifDiario2'
                              
           ) data2) data

WHERE data.valorconfig < valor +  '" . $valor . "'
";

            $sqlQuery = new SqlQuery($sql);

            $count = $this->execute2($sqlQuery);
            $count = $count[0]['.count(*)'];

            if ($count > 0) {
                return 20019;
                return $Clasificador->getClasificadorId();
            }
        }
        if ($fechaModifSemana != '' && $fechaModifSemana2 != '') {
            $sql = "SELECT count(*)
FROM (SELECT SUM(valor) valor,valorconfig
      FROM (SELECT 0 valor, usuario_configuracion.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_configuracion
                    INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOVIVOSEMANA' AND
                                                clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "'
              AND usuario_configuracion.estado = 'A' 
            UNION 
              SELECT SUM(usuario_configuracion_resumen.valor) valor, usuario_configuracion.usuario_id, 0 valorconfig
            FROM usuario_configuracion_resumen
                     INNER JOIN usuario_configuracion
                                ON (usuario_configuracion.usuconfig_id =
                                    usuario_configuracion_resumen.usuconfig_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOVIVOSEMANA' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "'
              AND usuario_configuracion.estado = 'A'
              AND usuario_configuracion_resumen.fecha_crea >='$fechaModifSemana'

            UNION

            SELECT SUM(CASE
               WHEN transjuego_log.tipo LIKE ('DEBIT%') THEN transjuego_log.valor
               WHEN transjuego_log.tipo LIKE ('ROLLBACK%') THEN transjuego_log.valor
               ELSE 0 END)                                                               valor, usuario_mandante.usuario_mandante, usuario_configuracion.valor valorconfig
            FROM transjuego_log
                     INNER JOIN transaccion_juego ON (transaccion_juego.transjuego_id = transjuego_log.transjuego_id)
                     INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id = usuario_mandante.usumandante_id)
                     INNER JOIN producto_mandante ON (producto_mandante.prodmandante_id = transaccion_juego.producto_id)
                     INNER JOIN producto ON (producto.producto_id = producto_mandante.producto_id)
                     INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id)
                     INNER JOIN usuario_configuracion ON (usuario_mandante.usuario_mandante = usuario_configuracion.usuario_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOVIVOSEMANA' AND clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_mandante.usumandante_id = $usumandanteId AND proveedor.tipo ='LIVECASINO'
              AND usuario_configuracion.estado = 'A' 
              AND transjuego_log.fecha_crea >='$fechaModifSemana2'
                              
           ) data2) data

WHERE data.valorconfig < valor +  '" . $valor . "'
";

            $sqlQuery = new SqlQuery($sql);

            $count = $this->execute2($sqlQuery);
            $count = $count[0]['.count(*)'];

            if ($count > 0) {
                return 20020;
                return $Clasificador->getClasificadorId();
            }
        }


        if ($fechaModifMensual != '' && $fechaModifMensual2 != '') {
            $sql = "SELECT count(*) 
FROM (SELECT SUM(valor) valor,valorconfig
      FROM (SELECT 0 valor, usuario_configuracion.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_configuracion
                    INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOVIVOMENSUAL' AND
                                                clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "'
              AND usuario_configuracion.estado = 'A' 
            UNION 
              SELECT SUM(usuario_configuracion_resumen.valor) valor, usuario_configuracion.usuario_id, 0 valorconfig
            FROM usuario_configuracion_resumen
                     INNER JOIN usuario_configuracion
                                ON (usuario_configuracion.usuconfig_id =
                                    usuario_configuracion_resumen.usuconfig_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOVIVOMENSUAL' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "'
              AND usuario_configuracion.estado = 'A'
              AND usuario_configuracion_resumen.fecha_crea >= '$fechaModifMensual'

            UNION

            SELECT SUM(CASE
               WHEN transjuego_log.tipo LIKE ('DEBIT%') THEN transjuego_log.valor
               WHEN transjuego_log.tipo LIKE ('ROLLBACK%') THEN transjuego_log.valor
               ELSE 0 END)                                                               valor, usuario_mandante.usuario_mandante, usuario_configuracion.valor valorconfig
            FROM transjuego_log
                     INNER JOIN transaccion_juego ON (transaccion_juego.transjuego_id = transjuego_log.transjuego_id)
                     INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id = usuario_mandante.usumandante_id)
                     INNER JOIN producto_mandante ON (producto_mandante.prodmandante_id = transaccion_juego.producto_id)
                     INNER JOIN producto ON (producto.producto_id = producto_mandante.producto_id)
                     INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id)
                     INNER JOIN usuario_configuracion ON (usuario_mandante.usuario_mandante = usuario_configuracion.usuario_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOVIVOMENSUAL' AND clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_mandante.usumandante_id = $usumandanteId AND proveedor.tipo ='LIVECASINO'
              AND usuario_configuracion.estado = 'A'
              AND transjuego_log.fecha_crea>='$fechaModifMensual2'
                              
           ) data2) data

WHERE data.valorconfig < valor +  '" . $valor . "'
";
            $sqlQuery = new SqlQuery($sql);

            $count = $this->execute2($sqlQuery);
            $count = $count[0]['.count(*)'];

            if ($count > 0) {
                return 20021;
                return $Clasificador->getClasificadorId();
            }

        }

        if ($fechaModifAnual != '' && $fechaModifAnual2 != '') {


            $sql = "SELECT count(*)
FROM (SELECT SUM(valor) valor,valorconfig
      FROM (SELECT 0 valor, usuario_configuracion.usuario_id, usuario_configuracion.valor valorconfig
            FROM usuario_configuracion
                    INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOVIVOANUAL' AND
                                                clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "'
              AND usuario_configuracion.estado = 'A' 
            UNION 
              SELECT SUM(usuario_configuracion_resumen.valor) valor, usuario_configuracion.usuario_id, 0 valorconfig
            FROM usuario_configuracion_resumen
                     INNER JOIN usuario_configuracion
                                ON (usuario_configuracion.usuconfig_id =
                                    usuario_configuracion_resumen.usuconfig_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOVIVOANUAL' AND
                                                 clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_configuracion.usuario_id = '" . $UsuarioConfiguracion->getUsuarioId() . "'
              AND usuario_configuracion.estado = 'A'
              AND usuario_configuracion_resumen.fecha_crea >='$fechaModifAnual'

            UNION

            SELECT SUM(CASE
               WHEN transjuego_log.tipo LIKE ('DEBIT%') THEN transjuego_log.valor
               WHEN transjuego_log.tipo LIKE ('ROLLBACK%') THEN transjuego_log.valor
               ELSE 0 END)                                                               valor, usuario_mandante.usuario_mandante, usuario_configuracion.valor valorconfig
            FROM transjuego_log
                     INNER JOIN transaccion_juego ON (transaccion_juego.transjuego_id = transjuego_log.transjuego_id)
                     INNER JOIN usuario_mandante ON (transaccion_juego.usuario_id = usuario_mandante.usumandante_id)
                     INNER JOIN producto_mandante ON (producto_mandante.prodmandante_id = transaccion_juego.producto_id)
                     INNER JOIN producto ON (producto.producto_id = producto_mandante.producto_id)
                     INNER JOIN proveedor ON (proveedor.proveedor_id = producto.proveedor_id)
                     INNER JOIN usuario_configuracion ON (usuario_mandante.usuario_mandante = usuario_configuracion.usuario_id)
                     INNER JOIN clasificador ON (clasificador.abreviado = 'LIMAPUCASINOVIVOANUAL' AND clasificador.clasificador_id = usuario_configuracion.tipo)
            WHERE usuario_mandante.usumandante_id = $usumandanteId  AND proveedor.tipo ='LIVECASINO'
              AND usuario_configuracion.estado = 'A' 
              AND transjuego_log.fecha_crea >= '$fechaModifAnual2'
                              
           ) data2) data

WHERE data.valorconfig < valor +  '" . $valor . "'
";

            $sqlQuery = new SqlQuery($sql);

            $count = $this->execute2($sqlQuery);
            $count = $count[0]['.count(*)'];

            if ($count > 0) {
                return 20022;
                return $Clasificador->getClasificadorId();
            }
        }


        return 0;
    }

    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $usuconfig_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function delete($usuconfig_id)
    {
        $sql = 'DELETE FROM usuario_configuracion WHERE usuconfig_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($usuconfig_id);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Insertar un registro en la base de datos
     *
     * @param Objeto usuarioConfiguracion usuarioConfiguracion
     *
     * @return String $id resultado de la consulta
     *
     */
    public function insert($usuarioConfiguracion)
    {
        $sql = 'INSERT INTO usuario_configuracion (usuario_id, tipo, valor, usucrea_id, usumodif_id,producto_id,estado,fecha_modif,nota,fecha_inicio,fecha_fin) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?,?,?)';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($usuarioConfiguracion->usuarioId);
        $sqlQuery->set($usuarioConfiguracion->tipo);
        $sqlQuery->set($usuarioConfiguracion->valor);
        $sqlQuery->setNumber($usuarioConfiguracion->usucreaId);
        $sqlQuery->setNumber($usuarioConfiguracion->usumodifId);
        if ($usuarioConfiguracion->productoId == "") {
            $usuarioConfiguracion->productoId = 0;
        }
        $sqlQuery->set($usuarioConfiguracion->productoId);
        $sqlQuery->set($usuarioConfiguracion->estado);
        if ($usuarioConfiguracion->fechaModif == "") {
            $usuarioConfiguracion->fechaModif = date("Y-m-d H:i:s");
        }
        $sqlQuery->set($usuarioConfiguracion->fechaModif);
        $sqlQuery->set($usuarioConfiguracion->nota);
        $sqlQuery->set($usuarioConfiguracion->fechaInicio);
        $sqlQuery->set($usuarioConfiguracion->fechaFin);

        $id = $this->executeInsert($sqlQuery);
        $usuarioConfiguracion->usuconfigId = $id;
        return $id;
    }

    /**
     * Editar un registro en la base de datos
     *
     * @param Objeto usuarioConfiguracion usuarioConfiguracion
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function update($usuarioConfiguracion)
    {


        $sql = 'UPDATE usuario_configuracion SET usuario_id = ?, tipo = ?, valor = ?, nota = ?, usucrea_id = ?, usumodif_id = ?, producto_id = ?, estado = ?,fecha_inicio = ?, fecha_fin = ? WHERE usuconfig_id = ?';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($usuarioConfiguracion->usuarioId);
        $sqlQuery->set($usuarioConfiguracion->tipo);
        $sqlQuery->set($usuarioConfiguracion->valor);
        $sqlQuery->set($usuarioConfiguracion->nota);
        $sqlQuery->setNumber($usuarioConfiguracion->usucreaId);
        $sqlQuery->setNumber($usuarioConfiguracion->usumodifId);
        $sqlQuery->set($usuarioConfiguracion->productoId);
        $sqlQuery->set($usuarioConfiguracion->estado);
        $sqlQuery->set($usuarioConfiguracion->fechaInicio);
        $sqlQuery->set($usuarioConfiguracion->fechaFin);
        $sqlQuery->setNumber($usuarioConfiguracion->usuconfigId);
        return $this->executeUpdate($sqlQuery);
    }


    /**
     * Realizar una consulta en la tabla de UsuarioConfiguracion 'UsuarioConfiguracion'
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
    public function queryUsuarioConfiguracionesCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn)
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
                if ($fieldOperation != "") $whereArray[] = $fieldName . $fieldOperation;
                if (oldCount($whereArray) > 0) {
                    $where = $where . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
                } else {
                    $where = "";
                }
            }

        }


        $sql = 'SELECT count(*) count FROM usuario_configuracion INNER JOIN usuario ON (usuario.usuario_id = usuario_configuracion.usuario_id) INNER JOIN clasificador ON (clasificador.clasificador_id = usuario_configuracion.tipo)  LEFT OUTER JOIN categoria ON (categoria.categoria_id = usuario_configuracion.producto_id) LEFT OUTER JOIN producto ON (producto.producto_id = usuario_configuracion.producto_id) ' . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT ' . $select . '  FROM usuario_configuracion INNER JOIN usuario ON (usuario.usuario_id = usuario_configuracion.usuario_id) INNER JOIN clasificador ON (clasificador.clasificador_id = usuario_configuracion.tipo) LEFT OUTER JOIN categoria ON (categoria.categoria_id = usuario_configuracion.producto_id) LEFT OUTER JOIN producto ON (producto.producto_id = usuario_configuracion.producto_id) ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        $sqlQuery = new SqlQuery($sql);

        $result = $Helpers->process_data($this->execute2($sqlQuery));

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
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
        $sql = 'DELETE FROM usuario_configuracion';
        $sqlQuery = new SqlQuery($sql);
        return $this->executeUpdate($sqlQuery);
    }


    /**
     * Obtener todos los registros donde se encuentre que
     * la columna usuario_id sea igual al valor pasado como parámetro
     *
     * @param String $value usuario_id requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByUsuarioId($value)
    {
        $sql = 'SELECT * FROM usuario_configuracion WHERE usuario_id = ?';
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
        $sql = 'SELECT * FROM usuario_configuracion WHERE usucrea_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
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
        $sql = 'SELECT * FROM usuario_configuracion WHERE usumodif_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
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
        $sql = 'SELECT * FROM usuario_configuracion WHERE fecha_crea = ?';
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
        $sql = 'SELECT * FROM usuario_configuracion WHERE fecha_modif = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * las colomunas usuarioId, tipo y estado sean iguales
     * a los valores pasados como parámetros
     *
     * @param String $value usuario_id requerido
     * @param String $value tipo requerido
     * @param String $value estado requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByUsuarioIdAndTipoAndEstado($usuarioId, $tipo, $estado)
    {
        $sql = 'SELECT * FROM usuario_configuracion WHERE usuario_id = ? AND tipo=? AND estado = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($usuarioId);
        $sqlQuery->set($tipo);
        $sqlQuery->set($estado);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * las colomunas usuarioId, tipo y producto sean iguales
     * a los valores pasados como parámetros
     *
     * @param String $value usuario_id requerido
     * @param String $value tipo requerido
     * @param String $value producto requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByUsuarioIdAndTipoAndProducto($usuarioId, $tipo, $productoId)
    {
        $sql = 'SELECT * FROM usuario_configuracion WHERE usuario_id = ? AND tipo=? AND producto_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($usuarioId);
        $sqlQuery->set($tipo);
        $sqlQuery->set($productoId);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * las colomunas usuario_id, tipo, productoId y estado
     * sean iguales a los valores pasados como parámetros
     *
     * @param String $value usuarioId requerido
     * @param String $value tipo requerido
     * @param String $value productoId requerido
     * @param String $value estado requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByUsuarioIdAndTipoAndProductoIdAndEstado($usuarioId, $tipo, $productoId, $estado)
    {
        $sql = 'SELECT * FROM usuario_configuracion WHERE usuario_id = ? AND tipo=? AND producto_id=? AND estado = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($usuarioId);
        $sqlQuery->set($tipo);
        $sqlQuery->set($productoId);
        $sqlQuery->set($estado);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * las colomunas usuarioId, tipo sean iguales
     * a los valores pasados como parámetros
     *
     * @param String $value usuario_id requerido
     * @param String $value tipo requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByUsuarioIdAndTipo($usuarioId, $tipo)
    {
        $sql = 'SELECT * FROM usuario_configuracion WHERE usuario_id = ? AND tipo=? AND estado="A" ';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($usuarioId);
        $sqlQuery->set($tipo);
        return $this->getList($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna usuarioId sea igual al valor pasado como parámetro
     *
     * @param String $value usuarioId requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByNombre($value)
    {
        $sql = 'DELETE FROM usuario_configuracion WHERE usuarioId = ?';
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
        $sql = 'DELETE FROM usuario_configuracion WHERE usucrea_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
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
        $sql = 'DELETE FROM usuario_configuracion WHERE usumodif_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
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
        $sql = 'DELETE FROM usuario_configuracion WHERE fecha_crea = ?';
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
        $sql = 'DELETE FROM usuario_configuracion WHERE fecha_modif = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }


    /**
     * Crear y devolver un objeto del tipo UsuarioConfiguracion
     * con los valores de una consulta sql
     *
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Objeto $usuarioConfiguracion UsuarioConfiguracion
     *
     * @access protected
     *
     */
    protected function readRow($row)
    {
        $usuarioConfiguracion = new UsuarioConfiguracion();

        $usuarioConfiguracion->usuconfigId = $row['usuconfig_id'];
        $usuarioConfiguracion->usuarioId = $row['usuario_id'];
        $usuarioConfiguracion->tipo = $row['tipo'];
        $usuarioConfiguracion->valor = $row['valor'];
        $usuarioConfiguracion->usucreaId = $row['usucrea_id'];
        $usuarioConfiguracion->usumodifId = $row['usumodif_id'];
        $usuarioConfiguracion->fechaCrea = $row['fecha_crea'];
        $usuarioConfiguracion->fechaModif = $row['fecha_modif'];
        $usuarioConfiguracion->productoId = $row['producto_id'];
        $usuarioConfiguracion->estado = $row['estado'];
        $usuarioConfiguracion->nota = $row['nota'];
        $usuarioConfiguracion->fechaInicio = $row['fecha_inicio'];
        $usuarioConfiguracion->fechaFin = $row['fecha_fin'];
        return $usuarioConfiguracion;
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
    protected function execute($sqlQuery)
    {
        return QueryExecutor::execute($this->transaction, $sqlQuery);
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

?>
