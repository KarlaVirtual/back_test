<?php namespace Backend\mysql;

/**
 * Class that operate on table 'bodega_informe_gerencial'. Database Mysql.
 *
 * @author: DT
 * @date: 2017-09-06 18:57
 */

use Backend\dao\BodegaInformeGerencialDAO;
use Backend\dto\BodegaInformeGerencial;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Exception;

class BodegaInformeGerencialMySqlDAO implements BodegaInformeGerencialDAO
{

    /**
     * Atributo Transaction transacción
     *
     * @var Objeto
     */
    private $transaction;

    /**
     * @return mixed
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * @param mixed $transaction
     */
    public function setTransaction($transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * TransaccionBodegaInformeGerencialMySqlDAO constructor.
     * @param $transaction
     */

    /**
     * Constructor de la clase
     *
     * TransaccionBodegaFlujoCajaMySqlDAO constructor.
     * @param $transaction
     */

    public function __construct($transaction="")
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
        $sql = 'SELECT * FROM bodega_informe_gerencial WHERE usurecresume_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($id);
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
        $sql = 'SELECT * FROM bodega_informe_gerencial';
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
        $sql = 'SELECT * FROM bodega_informe_gerencial ORDER BY ' . $orderColumn;
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }

    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $usurecresume_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function delete($usurecresume_id)
    {
        $sql = 'DELETE FROM bodega_informe_gerencial WHERE usurecresume_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($usurecresume_id);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Insertar un registro en la base de datos
     *
     * @param Objeto $bodega_informe_gerencial bodega_informe_gerencial
     *
     * @return String $id resultado de la consulta
     *
     */
    public function insert($bodega_informe_gerencial)
    {
        $sql = 'INSERT INTO bodega_informe_gerencial (usuario_id, mediopago_id, fecha, saldo_apuestas,cantidad, primeros_depositos, usuarios_registrados, saldo_premios, saldo_premios_pendientes, saldo_bono,tipo_usuario,tipo_fecha) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($bodega_informe_gerencial->paisId);
        $sqlQuery->set($bodega_informe_gerencial->mandante);
        $sqlQuery->set($bodega_informe_gerencial->fecha);
        $sqlQuery->set($bodega_informe_gerencial->saldoApuestas);
        $sqlQuery->set($bodega_informe_gerencial->cantidad);
        $sqlQuery->setNumber($bodega_informe_gerencial->primerosDepositos);
        $sqlQuery->setNumber($bodega_informe_gerencial->usuariosRegistrados);

        $sqlQuery->set($bodega_informe_gerencial->saldoPremios);
        $sqlQuery->set($bodega_informe_gerencial->saldoPremiosPendientes);
        $sqlQuery->set($bodega_informe_gerencial->saldoBono);
        $sqlQuery->set($bodega_informe_gerencial->tipoUsuario);
        $sqlQuery->set($bodega_informe_gerencial->tipoFecha);


        $id = $this->executeInsert($sqlQuery);
        $bodega_informe_gerencial->usurecresumeId = $id;
        return $id;
    }

    /**
     * Editar un registro en la base de datos
     *
     * @param Objeto $bodega_informe_gerencial bodega_informe_gerencial
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function update($bodega_informe_gerencial)
    {
        $sql = 'UPDATE bodega_informe_gerencial SET usuario_id = ?, mediopago_id = ?, fecha = ?, saldo_apuestas = ?, cantidad = ?, primeros_depositos = ?, usuarios_registrados = ?, saldo_premios = ?, saldo_premios_pendientes = ?, saldo_bono = ?,tipo_usuario = ?,tipo_fecha = ? WHERE usurecresume_id = ?';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->setNumber($bodega_informe_gerencial->paisId);
        $sqlQuery->set($bodega_informe_gerencial->mandante);
        $sqlQuery->set($bodega_informe_gerencial->fecha);
        $sqlQuery->set($bodega_informe_gerencial->saldoApuestas);
        $sqlQuery->set($bodega_informe_gerencial->cantidad);
        $sqlQuery->setNumber($bodega_informe_gerencial->primerosDepositos);
        $sqlQuery->setNumber($bodega_informe_gerencial->usuariosRegistrados);

        $sqlQuery->set($bodega_informe_gerencial->saldoPremios);
        $sqlQuery->set($bodega_informe_gerencial->saldoPremiosPendientes);
        $sqlQuery->set($bodega_informe_gerencial->saldoBono);
        $sqlQuery->set($bodega_informe_gerencial->tipoUsuario);
        $sqlQuery->set($bodega_informe_gerencial->tipoFecha);


        $sqlQuery->setNumber($bodega_informe_gerencial->usurecresumeId);
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
        $sql = 'DELETE FROM bodega_informe_gerencial';
        $sqlQuery = new SqlQuery($sql);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Devuelve los registros filtados por fecha_crea
     *
     * @param Objeto $value fecha_crea
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function queryByFechaCrea($value)
    {
        $sql = 'SELECT * FROM bodega_informe_gerencial WHERE fecha_crea = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna primeros_depositos sea igual al valor pasado como parámetro
     *
     * @param String $value primeros_depositos requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByUsucreaId($value)
    {
        $sql = 'SELECT * FROM bodega_informe_gerencial WHERE primeros_depositos = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna usuarios_registrados sea igual al valor pasado como parámetro
     *
     * @param String $value usuarios_registrados requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryByUsumodifId($value)
    {
        $sql = 'SELECT * FROM bodega_informe_gerencial WHERE usuarios_registrados = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->getList($sqlQuery);
    }


    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna mediopago_id sea igual al valor pasado como parámetro
     *
     * @param String $value mediopago_id requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByExternoId($value)
    {
        $sql = 'DELETE FROM bodega_informe_gerencial WHERE mediopago_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna saldoApuestas sea igual al valor pasado como parámetro
     *
     * @param String $value saldoApuestas requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByEstado($value)
    {
        $sql = 'DELETE FROM bodega_informe_gerencial WHERE saldoApuestas = ?';
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
        $sql = 'DELETE FROM bodega_informe_gerencial WHERE fecha_crea = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna primeros_depositos sea igual al valor pasado como parámetro
     *
     * @param String $value primeros_depositos requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByUsucreaId($value)
    {
        $sql = 'DELETE FROM bodega_informe_gerencial WHERE primeros_depositos = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna usuarios_registrados sea igual al valor pasado como parámetro
     *
     * @param String $value usuarios_registrados requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteByUsumodifId($value)
    {
        $sql = 'DELETE FROM bodega_informe_gerencial WHERE usuarios_registrados = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($value);
        return $this->executeUpdate($sqlQuery);
    }


    /**
     * Crear y devolver un objeto del tipo BodegaInformeGerencial
     * con los valores de una consulta sql
     *
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Objeto $BodegaInformeGerencial BodegaInformeGerencial
     *
     * @access protected
     *
     */
    protected function readRow($row)
    {
        $bodega_informe_gerencial = new BodegaInformeGerencial();

        $bodega_informe_gerencial->usurecresumeId = $row['usurecresume_id'];
        $bodega_informe_gerencial->paisId = $row['usuario_id'];
        $bodega_informe_gerencial->mandante = $row['mediopago_id'];
        $bodega_informe_gerencial->fecha = $row['fecha'];
        $bodega_informe_gerencial->saldoApuestas = $row['saldo_apuestas'];
        $bodega_informe_gerencial->cantidad = $row['cantidad'];
        $bodega_informe_gerencial->primerosDepositos = $row['primeros_depositos'];
        $bodega_informe_gerencial->usuariosRegistrados = $row['usuarios_registrados'];

        $bodega_informe_gerencial->saldoPremios = $row['saldo_premios'];
        $bodega_informe_gerencial->saldoPremiosPendientes = $row['saldo_premios_pendientes'];
        $bodega_informe_gerencial->saldoBono = $row['saldo_bono'];
        $bodega_informe_gerencial->tipoUsuario = $row['tipo_usuario'];
        $bodega_informe_gerencial->tipoFecha = $row['tipo_fecha'];
        
        return $bodega_informe_gerencial;
    }


    /**
     * Realizar una consulta en la tabla de transacciones 'BodegaInformeGerencial'
     * de una manera personalizada
     *
     * @param String $select campos de consulta
     * @param String $sidx columna para ordenar
     * @param String $sord orden los datos asc | desc
     * @param String $start inicio de la consulta
     * @param String $limit limite de la consulta
     * @param String $filters condiciones de la consulta
     * @param boolean $searchOn utilizar los filtros o no
     * @param String $grouping columna para agrupar
     *
     * @return Array $json resultado de la consulta
     *
     */
    public function queryBodegaInformeGerencialsCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$grouping,$innProducto=false,$innConcesionario=false)
    {


        $where = " where 1=1 ";


        if ($searchOn) {
            // Construye el where
            $filters = json_decode($filters);
            $whereArray = array();
            $rules = $filters->rules;
            $groupOperation = $filters->groupOp;
            $cont = 0;

            foreach ($rules as $rule) {
                $fieldName = $rule->field;
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
                    $where = $where . " " . $groupOperation . " ((" . $fieldName . " )) " . strtoupper($fieldOperation);
                } else {
                    $where = "";
                }
            }

        }

        if($grouping != ""){
            $where = $where . " GROUP BY " . $grouping;
        }

        $table_name=' bodega_informe_gerencial ';

        if($_SESSION['usuario'] =='4089418' || $_SESSION['usuario'] =='4089493' || $_SESSION['usuario'] =='1578169' || $_SESSION['usuario'] =='6997123' ){
            $table_name=' bodega_informe_gerencial_rf bodega_informe_gerencial ';
        }


        $sql = 'SELECT count(*) count FROM '.$table_name.'  INNER JOIN pais ON (pais.pais_id = bodega_informe_gerencial.pais_id) ' . $where;


        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);


        $sql = 'SELECT ' .$select .'  FROM  '.$table_name.'   INNER JOIN pais ON (pais.pais_id = bodega_informe_gerencial.pais_id) ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

        if($_ENV["debugFixed2"] == '1'){
print_r($sql);
        }



        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }

    
    /**
     * Consulta personalizada de informes gerenciales de bodega.
     *
     * @param string $ToDateLocal Fecha final del rango de consulta.
     * @param string $FromDateLocal Fecha inicial del rango de consulta.
     * @param string $Wallet Identificador de la billetera.
     * @param string $TypeBet Tipo de apuesta.
     * @param string $TypeUser Tipo de usuario.
     * @param string $country Identificador del país.
     * @param string $Partner Identificador del socio.
     * @param string $order Orden de los resultados.
     * @param int $start Inicio del límite de resultados.
     * @param int $limit Cantidad de resultados a devolver.
     * @return string JSON con el conteo y los datos de los informes gerenciales.
     */
    public function queryBodegaInformeGerencialsCustom2($ToDateLocal,$FromDateLocal,$Wallet,$TypeBet,$TypeUser,$country,$Partner,$order, $start, $limit)
    {
        $sqlWhere='';
        if($country != ''){
            $sqlWhere.="    AND bodega_informe_gerencial.pais_id  IN ($country) ";
        }
        if($Partner != ''){
            $sqlWhere.="    AND bodega_informe_gerencial.mandante  IN ($Partner)  ";
        }
        if(in_array($TypeUser,array('1','2')) ){
            $sqlWhere.="    AND bodega_informe_gerencial.tipo_usuario  IN ($TypeUser)  ";
        }

        $sql = "SELECT COUNT(*)
FROM bodega_informe_gerencial
INNER JOIN pais ON pais.pais_id = bodega_informe_gerencial.pais_id
WHERE
    bodega_informe_gerencial.fecha >= '".date('Y-m-d',strtotime($FromDateLocal))."'
    AND bodega_informe_gerencial.fecha <= '".date('Y-m-d',strtotime($ToDateLocal))."'
    AND bodega_informe_gerencial.billetera_id = '$Wallet' 
    AND bodega_informe_gerencial.tipo_fecha= '$TypeBet' 
    AND bodega_informe_gerencial.pais_id != '1'
    {$sqlWhere}
    ";

        $sqlQuery = new SqlQuery($sql);

        $count = $this->execute2($sqlQuery);

        $sql = "SELECT bodega_informe_gerencial.fecha,
	SUM(bodega_informe_gerencial.cantidad) cantidad,
	SUM(bodega_informe_gerencial.saldo_apuestas) saldo_apuestas,
	SUM(bodega_informe_gerencial.saldo_premios) saldo_premios,
	SUM(bodega_informe_gerencial.usuarios_registrados) usuarios_registrados,
	SUM(bodega_informe_gerencial.primeros_depositos) primeros_depositos,
	SUM(bodega_informe_gerencial.saldo_bono) saldo_bono,
	SUM(bodega_informe_gerencial.impuesto_apuestas) impuesto_apuestas ,
	SUM(bodega_informe_gerencial.premio_jackpot) premio_jackpot,
	SUM(bodega_informe_gerencial.impuesto_premios) impuesto_premios,
	bodega_informe_gerencial.mandante,
	SUM(bodega_informe_gerencial.premios_live) AS premios_live,
	SUM(bodega_informe_gerencial.apuestas_live) AS apuestas_live,
	SUM(bodega_informe_gerencial.cantidad_live) AS cantidad_live,
	SUM(bodega_informe_gerencial.premios_prematch) AS premios_prematch,
	SUM(bodega_informe_gerencial.apuestas_prematch) AS apuestas_prematch,
	SUM(bodega_informe_gerencial.cantidad_prematch) AS cantidad_prematch,
	SUM(bodega_informe_gerencial.premios_mixta) AS premios_mixta,
	SUM(bodega_informe_gerencial.apuestas_mixta) AS apuestas_mixta,
	SUM(bodega_informe_gerencial.cantidad_mixta) AS cantidad_mixta,
	SUM(bodega_informe_gerencial.premios_hipicas) AS premios_hipicas,
	SUM(bodega_informe_gerencial.apuestas_hipicas) AS apuestas_hipicas,
	SUM(bodega_informe_gerencial.cantidad_hipicas) AS cantidad_hipicas,
	SUM(bodega_informe_gerencial.premios_virtuales) AS premios_virtuales,
	SUM(bodega_informe_gerencial.apuestas_virtuales) AS apuestas_virtuales,
	SUM(bodega_informe_gerencial.cantidad_virtuales) AS cantidad_virtuales,
	pais.*  FROM   bodega_informe_gerencial    
	    INNER JOIN pais ON (pais.pais_id = bodega_informe_gerencial.pais_id)  
	WHERE 1=1  
	  AND ((bodega_informe_gerencial.fecha ))  >= '".date('Y-m-d',strtotime($FromDateLocal))."' 
	  AND ((bodega_informe_gerencial.fecha ))  <= '".date('Y-m-d',strtotime($ToDateLocal))."'
	  AND ((bodega_informe_gerencial.billetera_id ))  = '$Wallet' 
	  AND ((bodega_informe_gerencial.tipo_fecha ))  = '$TypeBet' 
    {$sqlWhere}
	  AND ((bodega_informe_gerencial.pais_id ))  != '1' 
GROUP BY bodega_informe_gerencial.mandante,bodega_informe_gerencial.pais_id,bodega_informe_gerencial.fecha 
ORDER BY bodega_informe_gerencial.fecha asc LIMIT $start,$limit";



        $sqlQuery = new SqlQuery($sql);

        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;

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
     * Ejecutar2 una consulta sql
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
