<?php

namespace Backend\mysql;

use Backend\dao\UsuarioReferenteResumenDAO;
use Backend\dto\UsuarioReferenteResumen;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Exception;

use Backend\dto\Helpers;
    /**
     * Clase UsuarioReferenteResumenMySqlDAO
     * Clase encargada de proveer las consultas de la tabla usuario_referente_resumen
     *
     * @author David Torres Rendón
     * @package No
     * @category No
     * @version    1.0
     * @since Desconocido
     *
     */
class UsuarioReferenteResumenMySqlDAO implements UsuarioReferenteResumenDAO
{
            /**
         * Objeto contiene la relación entre la conexión a la base de datos y UsuarioRestriccionMySqlDAO
         * @var Transaction
         */
    var $Transaction;

    public function __construct($Transaction = '') {
        if ($Transaction == null || $Transaction == "") {
            $this->Transaction = new Transaction();
        } else {
            $this->Transaction = $Transaction;
        }
    }

    /**
     * @return Transaction|mixed|string
     */
    public function getTransaction()
    {
        return $this->Transaction;
    }

    /**
     * @param Transaction|mixed|string $Transaction
     */
    public function setTransaction($Transaction): void
    {
        $this->Transaction = $Transaction;
    }

    /**
     * @inheritDoc
     */
    public function load($usurefresumId)
    {
        $sql = 'SELECT * FROM usuario_referente_resumen WHERE usurefresum_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($usurefresumId);
        return $this->getRow($sqlQuery);
    }

    public function loadByUserAndData ($tipoUsuario, $usuarioId, $fechaCrea, $tipo, $tipoCondicion)
    {
        $sql = "select * from usuario_referente_resumen where tipo_usuario = ? and usuario_id = ? and fecha_crea like CONCAT(?, '%') and tipo = ? and tipo_condicion = ?";
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->set($tipoUsuario);

        $sqlQuery->setNumber($usuarioId);

        $sqlQuery->set($fechaCrea);

        $sqlQuery->set($tipo);

        if ($tipoCondicion != '') $sqlQuery->set($tipoCondicion);
        else $sqlQuery->setSIN('null');

        return $this->getRow($sqlQuery);
    }

    /**
     * @inheritDoc
     */
    public function queryAll()
    {
        $sql = 'SELECT * FROM usuario_referente_resumen';
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }

    /**
     * @inheritDoc
     */
    public function queryAllOrderBy($orderColumn)
    {
        $sql = 'SELECT * FROM usuario_referente_resumen ORDER BY ' . $orderColumn;
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }

    /**
     * @inheritDoc
     */
    public function insert(UsuarioReferenteResumen $UsuarioReferenteResumen)
    {
        $sql = 'insert into usuario_referente_resumen (tipo_usuario, usuario_id, valor, usucrea_id, usumodif_id, tipo, cantidad, tipo_bono, tipo_condicion) values (?, ?, ?, ?, ?, ?, ?, ?, ?)';

        $sqlQuery = new sqlQuery($sql);

        $sqlQuery->set($UsuarioReferenteResumen->getTipoUsuario());

        $sqlQuery->setNumber($UsuarioReferenteResumen->getUsuarioId());

        if ($UsuarioReferenteResumen->getValor() === null || $UsuarioReferenteResumen->getValor() === '') $sqlQuery->setSIN('null');
        else $sqlQuery->setNumber($UsuarioReferenteResumen->getValor());

        if ($UsuarioReferenteResumen->getUsucreaId() === null || $UsuarioReferenteResumen->getUsucreaId() === '') $sqlQuery->setSIN('null');
        else $sqlQuery->setNumber($UsuarioReferenteResumen->getUsucreaId());

        if ($UsuarioReferenteResumen->getUsumodifId() === null || $UsuarioReferenteResumen->getUsumodifId() === '') $sqlQuery->setSIN('null');
        else $sqlQuery->setNumber($UsuarioReferenteResumen->getUsumodifId());

        $sqlQuery->set($UsuarioReferenteResumen->getTipo());

        if ($UsuarioReferenteResumen->getCantidad() === null || $UsuarioReferenteResumen->getCantidad() === '') $sqlQuery->setSIN('null');
        else $sqlQuery->setNumber($UsuarioReferenteResumen->getCantidad());


        if ($UsuarioReferenteResumen->getTipoBono() === null || $UsuarioReferenteResumen->getTipoBono() === '') $sqlQuery->setSIN('null');
        else $sqlQuery->setNumber($UsuarioReferenteResumen->getTipoBono());

        if ($UsuarioReferenteResumen->getTipoCondicion() === null || $UsuarioReferenteResumen->getTipoCondicion() === '') $sqlQuery->setSIN('null');
        else $sqlQuery->set($UsuarioReferenteResumen->getTipoCondicion());


        $usurefresumId = $this->executeInsert($sqlQuery);
        $UsuarioReferenteResumen->setUsurefresumId($usurefresumId);
        return $usurefresumId;
    }

    /**
     * @inheritDoc
     */
    public function update(UsuarioReferenteResumen $UsuarioReferenteResumen)
    {
        $sql = 'update usuario_referente_resumen SET tipo_usuario = ?, usuario_id = ?, valor = ?, usucrea_id = ?, usumodif_id = ?, tipo = ?, cantidad = ?, tipo_bono = ?, tipo_condicion = ? WHERE usurefresum_id = ?';

        $sqlQuery = new sqlQuery($sql);

        $sqlQuery->set($UsuarioReferenteResumen->getTipoUsuario());

        $sqlQuery->setNumber($UsuarioReferenteResumen->getUsuarioId());

        if ($UsuarioReferenteResumen->getValor() === null || $UsuarioReferenteResumen->getValor() === '') $sqlQuery->setSIN('null');
        else $sqlQuery->setNumber($UsuarioReferenteResumen->getValor());

        if ($UsuarioReferenteResumen->getUsucreaId() === null || $UsuarioReferenteResumen->getUsucreaId() === '') $sqlQuery->setSIN('null');
        else $sqlQuery->setNumber($UsuarioReferenteResumen->getUsucreaId());

        if ($UsuarioReferenteResumen->getUsumodifId() === null || $UsuarioReferenteResumen->getUsumodifId() === '') $sqlQuery->setSIN('null');
        else $sqlQuery->setNumber($UsuarioReferenteResumen->getUsumodifId());

        $sqlQuery->set($UsuarioReferenteResumen->getTipo());

        if ($UsuarioReferenteResumen->getCantidad() === null || $UsuarioReferenteResumen->getCantidad() === '') $sqlQuery->setSIN('null');
        else $sqlQuery->setNumber($UsuarioReferenteResumen->getCantidad());

        if ($UsuarioReferenteResumen->getTipoBono() === null || $UsuarioReferenteResumen->getTipoBono() === '') $sqlQuery->setSIN('null');
        else $sqlQuery->setNumber($UsuarioReferenteResumen->getTipoBono());

        if ($UsuarioReferenteResumen->getTipoCondicion() === null || $UsuarioReferenteResumen->getTipoCondicion() === '') $sqlQuery->setSIN('null');
        else $sqlQuery->set($UsuarioReferenteResumen->getTipoCondicion());

        $sqlQuery->setNumber($UsuarioReferenteResumen->getUsurefresumId());


        return $this->executeUpdate($sqlQuery);
    }

    /**
     * @inheritDoc
     */
    public function delete($usurefresumId)
    {
        $sql = 'DELETE FROM usuario_referente_resumen WHERE usurefresum_id = ?';

        $SqlQuery = new SqlQuery($sql);
        $SqlQuery->setNumber($usurefresumId);

        return $this->executeUpdate($SqlQuery);
    }


    /**
     * Lee una fila de datos y la convierte en un objeto UsuarioReferenteResumen.
     *
     * @param array $row La fila de datos obtenida de la base de datos.
     * @return UsuarioReferenteResumen El objeto UsuarioReferenteResumen con los datos de la fila.
     */
    protected function readRow($row)
    {
        $UsuarioReferenteResumen = new UsuarioReferenteResumen();

        $UsuarioReferenteResumen->usurefresumId = $row['usurefresum_id'];
        $UsuarioReferenteResumen->tipoUsuario = $row['tipo_usuario'];
        $UsuarioReferenteResumen->usuarioId = $row['usuario_id'];
        $UsuarioReferenteResumen->valor = $row['valor'];
        $UsuarioReferenteResumen->usucreaId = $row['usucrea_id'];
        $UsuarioReferenteResumen->fechaCrea = $row['fecha_crea'];
        $UsuarioReferenteResumen->usumodifId = $row['usumodif_id'];
        $UsuarioReferenteResumen->fechaModif = $row['fecha_modif'];
        $UsuarioReferenteResumen->tipo = $row['tipo'];
        $UsuarioReferenteResumen->cantidad = $row['cantidad'];
        $UsuarioReferenteResumen->tipoBono = $row['tipo_bono'];
        $UsuarioReferenteResumen->tipoCondicion = $row['tipo_condicion'];
        
        return $UsuarioReferenteResumen;
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
     * Realiza una consulta personalizada en la tabla usuario_referente_resumen.
     *
     * @param string $select Las columnas a seleccionar en la consulta.
     * @param string $sidx La columna por la cual ordenar los resultados.
     * @param string $sord El orden de la columna (ASC o DESC).
     * @param int $start El índice de inicio para la paginación.
     * @param int $limit El número de registros a devolver.
     * @param string $filters Los filtros en formato JSON para aplicar en la consulta.
     * @param bool $searchOn Indica si se deben aplicar los filtros.
     * @param bool $groupBy (Opcional) La columna por la cual agrupar los resultados.
     * @param bool $onlyCount (Opcional) Indica si solo se debe devolver el conteo de registros.
     *
     * @return string Un JSON con el conteo de registros y los datos resultantes de la consulta.
     */
    public function queryUsuarioReferenteResumenCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn, $groupBy = false, $onlyCount = false)
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

        if ($groupBy) $where .= ' group by ' . $groupBy . ' ';

        $sql = "select count(*) from usuario_referente_resumen inner join usuario_otrainfo as usuotrainfo_referente on (usuario_referente_resumen.usuario_id = usuotrainfo_referente.usuario_id) " . $where;
        $sqlQuery = new SqlQuery($sql);
        $count = $this->execute2($sqlQuery);
        if ($onlyCount) return '{"count":'. json_encode($count) .'}';

        $sql = "select ". $select ." from usuario_referente_resumen inner join usuario_otrainfo as usuotrainfo_referente on (usuario_referente_resumen.usuario_id = usuotrainfo_referente.usuario_id)  ". $where ." order by ". $sidx ." ". $sord . " limit ". $start ."," . $limit;
        $sqlQuery = new SqlQuery($sql);
        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : '.json_encode($count). ', "data" : '.json_encode($result).'}';

        return  $json;
    }
}