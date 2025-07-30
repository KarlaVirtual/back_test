<?php

namespace Backend\mysql;

use Backend\dao\UsuarioFavoritoDAO;
use Backend\dto\Helpers;
use Backend\dto\UsuarioFavorito;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Exception;

/**
 * Clase UsuarioFavoritoMySqlDAO
 *
 * Esta clase provee las consultas vinculadas a la tabla usuario_favorito de la base de datos.
 * La clase usuario_favorito permite almacenar y manejar los productos favoritos del usuario.
 *
 * @author David Torres Rendón
 * @access public
 * @package No
 * @subpackage No
 */
class UsuarioFavoritoMySqlDAO implements UsuarioFavoritoDAO
{
    /**
     * Objeto contiene la relación entre la conexión a la base de datos y UsuarioRestriccionMySqlDAO
     * @var Transaction
     */
    public Transaction $transaction;

/**
     * Constructor de la clase.
     *
     * @param Transaction|null $transaction Objeto de transacción opcional.
     */
    public function __construct($transaction = null) {
        $this->transaction = $transaction ?? new Transaction();
    }

    /**
     * Obtiene la transacción actual.
     *
     * @return Transaction La transacción actual.
     */
    public function getTransaction(): Transaction
    {
        return $this->transaction;
    }

    /**
     * Establece una nueva transacción.
     *
     * @param Transaction $transaction La nueva transacción a establecer.
     */
    public function setTransaction(Transaction $transaction): void
    {
        $this->transaction = $transaction;
    }

    /**
     * @inheritDoc
     */
    public function load($usufavoritoId)
    {
        $sql = 'SELECT * FROM usuario_favorito WHERE usufavorito_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($usufavoritoId);
        return $this->getRow($sqlQuery);
    }

    /**
     * Carga un registro de usuario favorito basado en el ID del usuario y el ID del producto.
     *
     * @param int $usuarioId El ID del usuario.
     * @param int $productoId El ID del producto.
     * @return array|null El registro del usuario favorito si se encuentra, de lo contrario null.
     */
    public function loadByUserProduct ($usuarioId, $productoId)
    {
        $sql = 'SELECT * FROM usuario_favorito WHERE usuario_id = ? AND producto_id = ? AND estado = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setSIN($usuarioId);
        $sqlQuery->setSIN($productoId);
        $sqlQuery->set('A');
        return $this->getRow($sqlQuery);
    }


    /**
     * @inheritDoc
     */
    public function delete($usufavoritoId)
    {
        $sql = 'DELETE FROM usuario_favorito WHERE usufavorito_id = ?';

        $SqlQuery = new SqlQuery($sql);
        $SqlQuery->setNumber($usufavoritoId);

        return $this->executeUpdate($SqlQuery);
    }

    /**
     * @inheritDoc
     */
    public function insert(UsuarioFavorito $UsuarioFavorito)
    {
        $sql = 'insert into usuario_favorito (usuario_id,producto_id,estado,usucrea_id,usumodif_id) values (?, ?, ?, ?, ?)';

        $sqlQuery = new sqlQuery($sql);
        $sqlQuery->setSIN($UsuarioFavorito->getUsuarioId());
        $sqlQuery->setSIN($UsuarioFavorito->getProductoId());
        $sqlQuery->set($UsuarioFavorito->getEstado());
        $sqlQuery->setSIN($UsuarioFavorito->getUsucreaId());
        $sqlQuery->setSIN($UsuarioFavorito->getUsumodifId());

        $usufavoritoId = $this->executeInsert($sqlQuery);
        $UsuarioFavorito->setUsufavoritoId($usufavoritoId);
        return $usufavoritoId;
    }

    /**
     * @inheritDoc
     */
    public function update(UsuarioFavorito $UsuarioFavorito)
    {
        $sql = 'update usuario_favorito SET usuario_id = ?, producto_id = ?, estado = ?, usucrea_id = ?, usumodif_id = ? WHERE usufavorito_id = ?';

        $sqlQuery = new sqlQuery($sql);
        $sqlQuery->setNumber($UsuarioFavorito->getUsuarioId());
        $sqlQuery->setNumber($UsuarioFavorito->getProductoId());
        $sqlQuery->set($UsuarioFavorito->getEstado());
        $sqlQuery->setNumber($UsuarioFavorito->getUsucreaId());
        $sqlQuery->setNumber($UsuarioFavorito->getUsumodifId());

        $sqlQuery->setNumber($UsuarioFavorito->getUsufavoritoId());

        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Lee una fila de la base de datos y la convierte en un objeto UsuarioFavorito.
     *
     * @param array $row La fila de la base de datos representada como un array asociativo.
     * 
     * @return UsuarioFavorito Un objeto UsuarioFavorito con los datos de la fila.
     */
    protected function readRow($row)
    {
        $UsuarioFavorito = new UsuarioFavorito();

        $UsuarioFavorito->usufavoritoId = $row['usufavorito_id'];
        $UsuarioFavorito->usuarioId = $row['usuario_id'];
        $UsuarioFavorito->productoId = $row['producto_id'];
        $UsuarioFavorito->estado = $row['estado'];
        $UsuarioFavorito->fechaCrea = $row['fecha_crea'];
        $UsuarioFavorito->fechaModif = $row['fecha_modif'];
        $UsuarioFavorito->usucreaId = $row['usucrea_id'];
        $UsuarioFavorito->usumodifId = $row['usumodif_id'];

        return $UsuarioFavorito;
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
        $tab = QueryExecutor::execute($this->transaction, $sqlQuery);
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
        return QueryExecutor::execute2($this->transaction, $sqlQuery);
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


    /**
     * Ejecutar una consulta sql como update
     *
     * @param String $sqlQuery consulta sql
     * @return Array $ resultado de la ejecución
     * @access protected
     */
    protected function executeUpdate($sqlQuery)
    {
        return QueryExecutor::executeUpdate($this->transaction, $sqlQuery);
    }


    /**
     * Consulta los productos favoritos de un usuario.
     *
     * @param int $usuarioId El ID del usuario.
     * @return array Un arreglo con los IDs de los productos favoritos del usuario.
     */
    public function queryUsuarioProductosFavoritos($usuarioId) : array
    {
        $sql = "SELECT producto_id FROM usuario_favorito WHERE estado = 'A' AND usuario_id = ?";

        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($usuarioId);

        $productIdsResponse = $this->execute2($sqlQuery);
        $productIdsResponse = json_decode(json_encode($productIdsResponse), FALSE);

        $productIds = [];
        foreach ($productIdsResponse as $productId) {
            $productIds[] = $productId->{'usuario_favorito.producto_id'};
        }

        return $productIds;
    }

    /**
     * Consulta personalizada de Usuario Favorito.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Campo por el cual se ordenará la consulta.
     * @param string $sord Orden de la consulta (ASC o DESC).
     * @param mixed $start Inicio del límite de la consulta.
     * @param mixed $limit Fin del límite de la consulta.
     * @param string $filters Filtros en formato JSON para construir la cláusula WHERE.
     * @param bool $searchOn Indica si se deben aplicar los filtros.
     * @param bool $onlyCount Indica si solo se debe devolver el conteo de resultados.
     * @return string JSON con el conteo de resultados y los datos de la consulta.
     */
    public function queryUsuarioFavoritoCustom(string $select, string $sidx, string $sord,mixed $start,mixed $limit,string $filters,bool $searchOn,bool $onlyCount = false): string
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

        $sql = "select count(*) as count from usuario_favorito inner join usuario_mandante on (usuario_favorito.usuario_id = usuario_mandante.usumandante_id) inner join producto_mandante on (usuario_favorito.producto_id = producto_mandante.prodmandante_id)" . $where;
        $sqlQuery = new SqlQuery($sql);
        $count = $this->execute2($sqlQuery);
        if ($onlyCount) return '{"count":'. json_encode($count) .'}';

        $sql = "select ". $select ." from usuario_favorito inner join usuario_mandante on (usuario_favorito.usuario_id = usuario_mandante.usumandante_id) inner join producto_mandante on (usuario_favorito.producto_id = producto_mandante.prodmandante_id) ". $where ." order by ". $sidx ." ". $sord . " limit ". $start ."," . $limit;
        $sqlQuery = new SqlQuery($sql);
        $result = $this->execute2($sqlQuery);

        $json = '{ "count" : '.json_encode($count). ', "data" : '.json_encode($result).'}';

        return  $json;
    }
}