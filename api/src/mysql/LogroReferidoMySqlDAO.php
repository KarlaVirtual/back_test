<?php

namespace Backend\mysql;

use Backend\dao\LogroReferidoDAO;
use Backend\dto\Helpers;
use Backend\dto\LogroReferido;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Exception;

/**
 * Clase 'LogroReferidoMySqlDAO'
 *
 * Esta clase provee las consultas del modelo o tabla 'LogroReferido'
 *
 * Ejemplo de uso:
 * $LogroReferidoMySqlDAO = new LogroReferidoMySqlDAO();
 *
 *
 * @package ninguno
 * @version ninguna
 * @access public
 * @see no
 *
 */

class LogroReferidoMySqlDAO implements LogroReferidoDAO
{
    var $Transaction;


    /**
     * Constructor de la clase LogroReferidoMySqlDAO.
     *
     * Inicializa la transacción para las operaciones de la base de datos.
     *
     * @param Transaction|string $Transaction La transacción a utilizar. Si es nulo o vacío, se crea una nueva transacción.
     */
    public function __construct($Transaction = "")
    {
        if ($Transaction == null || $Transaction == "") {
            $this->Transaction = new Transaction();
        } else {
            $this->Transaction = $Transaction;
        }
    }

    /**
     * Obtiene la transacción actual.
     *
     * @return Transaction|mixed|string La transacción actual.
     */
    public function getTransaction()
    {
        return $this->Transaction;
    }


    /**
     * Establece la transacción actual.
     *
     * @param Transaction|mixed|string $Transaction La transacción a establecer.
     */
    public function setTransaction($Transaction): void
    {
        $this->Transaction = $Transaction;
    }


    /**
     * Carga un registro específico de la tabla 'logro_referido' basado en su ID.
     *
     * @param int $logroreferidoId El ID del registro a cargar.
     * @return LogroReferido|null El objeto LogroReferido correspondiente, o null si no se encuentra el registro.
     */
    public function load($logroreferidoId)
    {
        $sql = 'SELECT * FROM logro_referido WHERE logroreferido_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($logroreferidoId);
        return $this->getRow($sqlQuery);
    }

   /**
    * Obtiene todos los registros de la tabla 'logro_referido'.
    *
    * @return LogroReferido[] Lista de objetos LogroReferido.
    */
   public function queryAll()
   {
       $sql = 'SELECT * FROM logro_referido';
       $sqlQuery = new SqlQuery($sql);
       return $this->getList($sqlQuery);
   }

   /**
    * Obtiene todos los registros de la tabla 'logro_referido' ordenados por una columna específica.
    *
    * @param string $orderColumn El nombre de la columna por la cual ordenar los registros.
    * @return LogroReferido[] Lista de objetos LogroReferido ordenados.
    */
   public function queryAllOrderBy($orderColumn)
   {
       $sql = 'SELECT * FROM logro_referido ORDER BY ' . $orderColumn;
       $sqlQuery = new SqlQuery($sql);
       return $this->getList($sqlQuery);
   }

    /**
     * Inserta un nuevo registro en la tabla 'logro_referido'.
     *
     * @param LogroReferido $LogroReferido El objeto LogroReferido a insertar.
     * @return int El ID del registro insertado.
     */
    public function insert(LogroReferido $LogroReferido)
    {
        $sql = 'insert into logro_referido (usuid_referido, usuid_referente, usuid_ganador, tipo_condicion, valor_condicion, tipo_premio, valor_premio, fecha_uso, estado, estado_grupal, usucrea_id, usumodif_id, fecha_expira, fecha_expira_premio) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';

        $sqlQuery = new sqlQuery($sql);
        $sqlQuery->setNumber($LogroReferido->getUsuidReferido());
        $sqlQuery->setNumber($LogroReferido->getUsuidReferente());
        $sqlQuery->setNumber($LogroReferido->getUsuidGanador());
        $sqlQuery->setNumber($LogroReferido->getTipoCondicion());
        if($LogroReferido->getValorCondicion() == null || $LogroReferido->getValorCondicion() == ''){
            $sqlQuery->setSIN('null');
        }
        else {
            $sqlQuery->set($LogroReferido->getValorCondicion());
        }
        $sqlQuery->setNumber($LogroReferido->getTipoPremio());
        $sqlQuery->setNumber($LogroReferido->getValorPremio());
        if(!$LogroReferido->getFechaUso()) {
            $sqlQuery->setSIN('null');
        }
        else {
            $sqlQuery->set($LogroReferido->getFechaUso());
        }
        if(!$LogroReferido->getEstado()) {
            $sqlQuery->setSIN('null');
        }
        else {
            $sqlQuery->set($LogroReferido->getEstado());
        }
        if(!$LogroReferido->getEstadoGrupal()) {
            $sqlQuery->setSIN('null');
        }
        else {
            $sqlQuery->set($LogroReferido->getEstadoGrupal());
        }
        $sqlQuery->setNumber($LogroReferido->getUsucreaId());
        $sqlQuery->setNumber($LogroReferido->getUsumodifId());
        if (!$LogroReferido->getFechaExpira()) {
            $sqlQuery->setSIN('null');
        }
        else {
            $sqlQuery->set($LogroReferido->getFechaExpira());
        }
        if (!$LogroReferido->getFechaExpiraPremio()) {
            $sqlQuery->setSIN('null');
        }
        else {
            $sqlQuery->set($LogroReferido->getFechaExpiraPremio());
        }

        $logroReferidoId = $this->executeInsert($sqlQuery);
        $LogroReferido->setLogroreferidoId($logroReferidoId);
        return $logroReferidoId;
    }

    /**
     * Actualiza un registro existente en la tabla 'logro_referido'.
     *
     * @param LogroReferido $LogroReferido El objeto LogroReferido con los datos actualizados.
     * @return boolean Resultado de la ejecución de la actualización.
     */
    public function update(LogroReferido $LogroReferido)
    {
        $sql = 'update logro_referido SET usuid_referido = ?, usuid_referente = ?, usuid_ganador = ?, tipo_condicion = ?, valor_condicion = ?, tipo_premio = ?, valor_premio = ?, fecha_uso = ?, estado = ?, estado_grupal = ?, usucrea_id = ?, usumodif_id = ?, fecha_expira = ?, fecha_expira_premio = ? WHERE logroreferido_id = ?';

        $sqlQuery = new sqlQuery($sql);
        $sqlQuery->setNumber($LogroReferido->getUsuidReferido());
        $sqlQuery->setNumber($LogroReferido->getUsuidReferente());
        $sqlQuery->setNumber($LogroReferido->getUsuidGanador());
        $sqlQuery->setNumber($LogroReferido->getTipoCondicion());
        if($LogroReferido->getValorCondicion() == null || $LogroReferido->getValorCondicion() == ''){
            $sqlQuery->setSIN('null');
        }
        else {
            $sqlQuery->set($LogroReferido->getValorCondicion());
        }
        $sqlQuery->setNumber($LogroReferido->getTipoPremio());
        $sqlQuery->setNumber($LogroReferido->getValorPremio());
        if(!$LogroReferido->getFechaUso()) {
            $sqlQuery->setSIN('null');
        }
        else {
            $sqlQuery->set($LogroReferido->getFechaUso());
        }
        if(!$LogroReferido->getEstado()) {
            $sqlQuery->setSIN('null');
        }
        else {
            $sqlQuery->set($LogroReferido->getEstado());
        }
        if(!$LogroReferido->getEstadoGrupal()) {
            $sqlQuery->setSIN('null');
        }
        else {
            $sqlQuery->set($LogroReferido->getEstadoGrupal());
        }
        $sqlQuery->setNumber($LogroReferido->getUsucreaId());
        $sqlQuery->setNumber($LogroReferido->getUsumodifId());
        if(!$LogroReferido->getFechaExpira()) {
            $sqlQuery->setSIN('null');
        }
        else {
            $sqlQuery->set($LogroReferido->getFechaExpira());
        }
        if(!$LogroReferido->getFechaExpiraPremio()) {
            $sqlQuery->setSIN('null');
        }
        else {
            $sqlQuery->set($LogroReferido->getFechaExpiraPremio());
        }

        $sqlQuery->setNumber($LogroReferido->getLogroreferidoId());

        return $this->executeUpdate($sqlQuery);
    }

   /**
      * Elimina un registro específico de la tabla 'logro_referido' basado en su ID.
      *
      * @param int $logroreferidoId El ID del registro a eliminar.
      * @return boolean Resultado de la ejecución de la eliminación.
      */

    public function delete($logroreferidoId)
    {
        $sql = 'DELETE FROM logro_referido WHERE logroreferido_id = ?';

        $SqlQuery = new SqlQuery($sql);
        $SqlQuery->setNumber($logroreferidoId);

        return $this->executeUpdate($SqlQuery);
    }

    /**
     * Devuelve los valores de los campos de la base de datos
     * y crea un objeto LogroReferido con esos valores.
     *
     * @param array $row Arreglo asociativo con los datos de la consulta.
     * @return LogroReferido El objeto LogroReferido creado a partir de los datos.
     * @access protected
     */
    protected function readRow($row)
    {
        $LogroReferido = new LogroReferido();

        $LogroReferido->logroreferidoId = $row['logroreferido_id'];
        $LogroReferido->usuidReferido = $row['usuid_referido'];
        $LogroReferido->usuidReferente = $row['usuid_referente'];
        $LogroReferido->usuidGanador = $row['usuid_ganador'];
        $LogroReferido->tipoCondicion = $row['tipo_condicion'];
        $LogroReferido->valorCondicion = $row['valor_condicion'];
        $LogroReferido->tipoPremio = $row['tipo_premio'];
        $LogroReferido->valorPremio = $row['valor_premio'];
        $LogroReferido->fechaUso = $row['fecha_uso'];
        $LogroReferido->estado = $row['estado'];
        $LogroReferido->estadoGrupal = $row['estado_grupal'];
        $LogroReferido->usucreaId = $row['usucrea_id'];
        $LogroReferido->usumodifId = $row['usumodif_id'];
        $LogroReferido->fechaCrea = $row['fecha_crea'];
        $LogroReferido->fechaModif = $row['fecha_modif'];
        $LogroReferido->fechaExpira = $row['fecha_expira'];
        $LogroReferido->fechaExpiraPremio = $row['fecha_expira_premio'];

        return $LogroReferido;
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
        $tab = QueryExecutor::execute($this->Transaction, $sqlQuery);
        if (count($tab) == 0) {
            return null;
        }
        return $this->readRow($tab[0]);
    }


    /**
     * Ejecutar una consulta sql y devolver los datos
     * como un arreglo asociativo
     *
     *
     * @param String $sqlQuery consulta sql
     *
     * @return Array $ret arreglo asociativo
     *
     * @access protected
     *
     */
    protected function getList($sqlQuery)
    {
        $tab = QueryExecutor::execute($this->Transaction, $sqlQuery);
        $ret = array();
        for ($i = 0; $i < count($tab); $i++) {
            $ret[$i] = $this->readRow($tab[$i]);
        }
        return $ret;
    }


    /**
     * Ejecutar una consulta sql
     *
     *
     * @param String $sql consulta sql
     *
     * @return Array $ resultado de la ejecución
     *
     * @access protected
     *
     */
    public function querySQL($sql)
    {
        $sqlQuery = new SqlQuery($sql);
        return $this->execute2($sqlQuery);
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
        return QueryExecutor::execute2($this->Transaction, $sqlQuery);
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
        return QueryExecutor::executeInsert($this->Transaction, $sqlQuery);
    }


    /**
     * Ejecutar una consulta sql como update
     *
     * @param String $sqlQuery consulta sql
     * @return Array $ resultado de la ejecución
     * @access protected
     */
    protected function executeUpdate($sqlQuery)
    {
        return QueryExecutor::executeUpdate($this->Transaction, $sqlQuery);
    }


    /**
     * Realiza una consulta personalizada en la tabla 'logro_referido' con filtros y paginación.
     *
     * @param string $select Columnas a seleccionar en la consulta.
     * @param string $sidx Columna por la cual ordenar los resultados.
     * @param string $sord Orden de los resultados (ASC o DESC).
     * @param int $start Índice de inicio para la paginación.
     * @param int $limit Límite de registros a devolver.
     * @param string $filters Filtros en formato JSON para aplicar en la consulta.
     * @param boolean $searchOn Indica si se deben aplicar los filtros.
     * @param boolean $onlyCount Indica si solo se debe devolver el conteo de registros.
     * @return string Resultado de la consulta en formato JSON.
     */
    public function queryLogrosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $onlyCount = false)
    {
        $where = " where 1=1 ";


        if($searchOn) {
            // Construye el where
            $filters = json_decode($filters);
            $whereArray = array();
            $rules = $filters->rules;
            $groupOperation = $filters->groupOp;
            $cont = 0;

            foreach($rules as $rule)
            {
                $fieldName = $rule->field;
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
                        $fieldOperation = " = '".$fieldData."'";
                        break;
                    case "ne":
                        $fieldOperation = " != '".$fieldData."'";
                        break;
                    case "lt":
                        $fieldOperation = " < '".$fieldData."'";
                        break;
                    case "gt":
                        $fieldOperation = " > '".$fieldData."'";
                        break;
                    case "le":
                        $fieldOperation = " <= '".$fieldData."'";
                        break;
                    case "ge":
                        $fieldOperation = " >= '".$fieldData."'";
                        break;
                    case "nu":
                        $fieldOperation = " = ''";
                        break;
                    case "nn":
                        $fieldOperation = " != ''";
                        break;
                    case "in":
                        $fieldOperation = " IN (".$fieldData.")";
                        break;
                    case "ni":
                        $fieldOperation = " NOT IN '".$fieldData."'";
                        break;
                    case "bw":
                        $fieldOperation = " LIKE '".$fieldData."%'";
                        break;
                    case "bn":
                        $fieldOperation = " NOT LIKE '".$fieldData."%'";
                        break;
                    case "ew":
                        $fieldOperation = " LIKE '%".$fieldData."'";
                        break;
                    case "en":
                        $fieldOperation = " NOT LIKE '%".$fieldData."'";
                        break;
                    case "cn":
                        $fieldOperation = " LIKE '%".$fieldData."%'";
                        break;
                    case "nc":
                        $fieldOperation = " NOT LIKE '%".$fieldData."%'";
                        break;
                    default:
                        $fieldOperation = "";
                        break;
                }
                if($fieldOperation != "") $whereArray[] = $fieldName.$fieldOperation;
                if (count($whereArray)>0)
                {
                    $where = $where . " " . $groupOperation . " UCASE(CAST(" . $fieldName . " AS CHAR)) " . strtoupper($fieldOperation);
                }
                else
                {
                    $where = "";
                }
            }
        }

        $sql = "select count(*) as count from logro_referido inner join usuario on (logro_referido.usuid_referido = usuario.usuario_id) inner join usuario_mandante on (usuario.usuario_id = usuario_mandante.usuario_mandante) inner join mandante_detalle as mandante_detalle_premio on (logro_referido.tipo_premio = mandante_detalle_premio.manddetalle_id) inner join mandante_detalle as mandante_detalle_condicion on (logro_referido.tipo_condicion = mandante_detalle_condicion.manddetalle_id) inner join clasificador on (mandante_detalle_condicion.tipo = clasificador.clasificador_id) " . $where;
        $sqlQuery = new SqlQuery($sql);
        $count = $this->execute2($sqlQuery);
        if ($onlyCount) return '{"count":'. json_encode($count) .'}';

        $sql = "select ". $select ." from logro_referido inner join usuario on (logro_referido.usuid_referido = usuario.usuario_id) inner join usuario_mandante on (usuario.usuario_id = usuario_mandante.usuario_mandante) inner join mandante_detalle as mandante_detalle_premio on (logro_referido.tipo_premio = mandante_detalle_premio.manddetalle_id) inner join mandante_detalle as mandante_detalle_condicion on (logro_referido.tipo_condicion = mandante_detalle_condicion.manddetalle_id) inner join clasificador on (mandante_detalle_condicion.tipo = clasificador.clasificador_id) ". $where ." order by ". $sidx ." ". $sord . " limit ". $start ."," . $limit;
        $sqlQuery = new SqlQuery($sql);
        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : '.json_encode($count). ', "data" : '.json_encode($result).'}';

        return  $json;
    }
}