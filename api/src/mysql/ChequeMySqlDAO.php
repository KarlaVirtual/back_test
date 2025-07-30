<?php namespace Backend\mysql;

use Backend\dao\ChequeDAO;
use Backend\dto\Cheque;
use Backend\dto\Helpers;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Exception;

/**
 * Clase 'ChequeMySqlDAO'
 *
 * Esta clase provee las consultas del modelo o tabla 'Cheque'
 *
 * Ejemplo de uso:
 * $ChequeMySqlDAO = new ChequeMySqlDAO();
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class ChequeMySqlDAO implements ChequeDAO
{


    /**
     * Atributo Transaction transacción
     *
     * @var Objeto
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
     * Obtener el registro condicionado por la
     * llave primaria que se pasa como parámetro
     *
     * @param String $id llave primaria
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function load($id)
    {
        $sql = 'SELECT * FROM cheque WHERE id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($id);
        return $this->getRow($sqlQuery);
    }

    /**
     * Obtener todos los registros de la base datos
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryAll()
    {
        $sql = 'SELECT * FROM cheque';
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
        $sql = 'SELECT * FROM cheque ORDER BY ' . $orderColumn;
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }

    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function delete($id)
    {
        $sql = 'DELETE FROM cheque WHERE id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($id);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Insertar un registro en la base de datos
     *
     * @param Object Cheque cheque
     *
     * @return String $id resultado de la consulta
     *
     */
    public function insert($cheque)
    {
        $sql = 'INSERT INTO cheque (nro_cheque, pais_id, origen, documento_id, mandante, ticket_id) VALUES (?, ?, ?, ?, ?, ?)';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->set($cheque->nroCheque);
        $sqlQuery->set($cheque->paisId);
        $sqlQuery->set($cheque->origen);
        $sqlQuery->set($cheque->documentoId);
        $sqlQuery->set($cheque->mandante);
        $sqlQuery->set($cheque->ticketId);

        $id = $this->executeInsert($sqlQuery);
        $cheque->id = $id;
        return $id;
    }

    /**
     * Editar un registro en la base de datos
     *
     * @param Object Cheque cheque
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function update($cheque)
    {
        $sql = 'UPDATE cheque SET nro_cheque = ?, pais_id = ?, origen = ?, documento_id = ?, mandante = ?, ticket_id = ? WHERE id = ?';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->set($cheque->nroCheque);
        $sqlQuery->set($cheque->paisId);
        $sqlQuery->set($cheque->origen);
        $sqlQuery->set($cheque->documentoId);
        $sqlQuery->set($cheque->mandante);
        $sqlQuery->set($cheque->ticketId);

        $sqlQuery->set($cheque->id);
        return $this->executeUpdate($sqlQuery);
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
        $sql = 'DELETE FROM cheque';
        $sqlQuery = new SqlQuery($sql);
        return $this->executeUpdate($sqlQuery);
    }


    /**
     * Obtener todos los registros donde se encuentre que
     * la columna nro_cheque sea igual al valor pasado como parámetro
     *
     * @param String $value nro_cheque requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByNroCheque($value)
    {
        $sql = 'SELECT * FROM cheque WHERE nro_cheque = ?';
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
        $sql = 'SELECT * FROM cheque WHERE pais_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna origen sea igual al valor pasado como parámetro
     *
     * @param String $value origen requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByOrigen($value)
    {
        $sql = 'SELECT * FROM cheque WHERE origen = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna documento_id sea igual al valor pasado como parámetro
     *
     * @param String $value documento_id requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByDocumentoId($value)
    {
        $sql = 'SELECT * FROM cheque WHERE documento_id = ?';
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
        $sql = 'SELECT * FROM cheque WHERE mandante = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna ticket_id sea igual al valor pasado como parámetro
     *
     * @param String $value ticket_id requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByTicketId($value)
    {
        $sql = 'SELECT * FROM cheque WHERE ticket_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }


    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna nro_cheque sea igual al valor pasado como parámetro
     *
     * @param String $value nro_cheque requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByNroCheque($value)
    {
        $sql = 'DELETE FROM cheque WHERE nro_cheque = ?';
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
        $sql = 'DELETE FROM cheque WHERE pais_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna origen sea igual al valor pasado como parámetro
     *
     * @param String $value origen requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByOrigen($value)
    {
        $sql = 'DELETE FROM cheque WHERE origen = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna documento_id sea igual al valor pasado como parámetro
     *
     * @param String $value documento_id requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByDocumentoId($value)
    {
        $sql = 'DELETE FROM cheque WHERE documento_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna mandante sea igual al valor pasado como parámetro
     *
     * @param String $value tipoId requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByMandante($value)
    {
        $sql = 'DELETE FROM cheque WHERE mandante = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna ticket_id sea igual al valor pasado como parámetro
     *
     * @param String $value ticket_id requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByTicketId($value)
    {
        $sql = 'DELETE FROM cheque WHERE ticket_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }


    /**
     * Crear y devolver un objeto del tipo Cheque
     * con los valores de una consulta sql
     *
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Object $Cheque Cheque
     *
     * @access protected
     *
     */
    protected function readRow($row)
    {
        $cheque = new Cheque();

        $cheque->id = $row['id'];
        $cheque->nroCheque = $row['nro_cheque'];
        $cheque->paisId = $row['pais_id'];
        $cheque->origen = $row['origen'];
        $cheque->documentoId = $row['documento_id'];
        $cheque->mandante = $row['mandante'];
        $cheque->ticketId = $row['ticket_id'];

        return $cheque;
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
     * Consulta cheques personalizados basados en varios parámetros y filtros.
     *
     * @param int $usuarioId ID del usuario que realiza la consulta.
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Campo por el cual se ordenarán los resultados.
     * @param string $sord Orden de los resultados (ASC o DESC).
     * @param int $start Índice de inicio para la paginación.
     * @param int $limit Número de registros a retornar.
     * @param string $filters Filtros en formato JSON para aplicar a la consulta.
     * @param bool $searchOn Indica si se deben aplicar los filtros.
     * @param int $paisId ID del país para filtrar los cheques.
     *
     * @return string JSON con el conteo de registros y los datos resultantes de la consulta.
     */
    public function queryChequesCustom($usuarioId,$select, $sidx, $sord, $start, $limit, $filters, $searchOn,$paisId)
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
                    $where = $where . " " . $groupOperation . " UCASE(CAST(" . $fieldName . " AS CHAR)) " . strtoupper($fieldOperation);
                } else {
                    $where = "";
                }
            }

        }


        $sql = 'SELECT count(*) count FROM cheque  inner join pais  on (cheque.pais_id=pais.pais_id) left outer join cuenta_cobro on (cheque.documento_id=cuenta_cobro.cuenta_id and cheque.origen=\'NR\') left outer join usuario on (cuenta_cobro.usuario_id=usuario.usuario_id) left outer join it_ticket_enc on (cheque.ticket_id=it_ticket_enc.ticket_id and cheque.origen=\'TK\') left outer join usuario usuariobeneficiario on (it_ticket_enc.beneficiario_id=usuariobeneficiario.usuario_id and it_ticket_enc.tipo_beneficiario=\'RN\') left outer join registro_rapido  on (it_ticket_enc.beneficiario_id=registro_rapido.registro_id and it_ticket_enc.tipo_beneficiario=\'RR\') left outer join registro registro2 on (usuariobeneficiario.usuario_id=registro2.usuario_id) left outer join registro      on (usuario.usuario_id=registro.usuario_id) '.$where.' and cheque.pais_id="'.$paisId.'" and pais.req_cheque=\'S\'  and ((it_ticket_enc.usumodifica_id ="'.$usuarioId.'" AND cheque.origen = \'TK\') OR ( cuenta_cobro.puntoventa_id ="'.$usuarioId.'" AND cheque.origen=\'NR\')) and case when cheque.origen=\'NR\' and cuenta_cobro.estado=\'I\' then \'S\' when cheque.origen=\'TK\' and not it_ticket_enc.fecha_pago is null then \'S\' else \'N\' end=\'S\'  ';


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT ' .$select .' FROM cheque  inner join pais  on (cheque.pais_id=pais.pais_id) left outer join cuenta_cobro on (cheque.documento_id=cuenta_cobro.cuenta_id and cheque.origen=\'NR\') left outer join usuario on (cuenta_cobro.usuario_id=usuario.usuario_id) left outer join it_ticket_enc on (cheque.ticket_id=it_ticket_enc.ticket_id and cheque.origen=\'TK\') left outer join usuario usuariobeneficiario on (it_ticket_enc.beneficiario_id=usuariobeneficiario.usuario_id and it_ticket_enc.tipo_beneficiario=\'RN\') left outer join registro_rapido  on (it_ticket_enc.beneficiario_id=registro_rapido.registro_id and it_ticket_enc.tipo_beneficiario=\'RR\') left outer join registro registro2 on (usuariobeneficiario.usuario_id=registro2.usuario_id) left outer join registro      on (usuario.usuario_id=registro.usuario_id) '.$where.' and cheque.pais_id="'.$paisId.'" and pais.req_cheque=\'S\'  and ((it_ticket_enc.usumodifica_id ="'.$usuarioId.'" AND cheque.origen = \'TK\') OR ( cuenta_cobro.puntoventa_id ="'.$usuarioId.'" AND cheque.origen=\'NR\')) and case when cheque.origen=\'NR\' and cuenta_cobro.estado=\'I\' then \'S\' when cheque.origen=\'TK\' and not it_ticket_enc.fecha_pago is null then \'S\' else \'N\' end=\'S\'  '. " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;


        $sqlQuery = new SqlQuery($sql);

        $result = $Helpers->process_data($this->execute2($sqlQuery));

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
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