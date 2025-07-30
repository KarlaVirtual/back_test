<?php namespace Backend\mysql;
use Backend\dao\UsuarioSaldoDAO;
use Backend\dto\Helpers;
use Backend\sql\QueryExecutor;
use Backend\sql\SqlQuery;
use Backend\sql\Transaction;
use Backend\dto\UsuarioSaldo;

/**
 * Clase 'UsuarioSaldoMySqlDAO'
 *
 * Esta clase provee las consultas del modelo o tabla 'UsuarioSaldo'
 *
 * Ejemplo de uso:
 * $UsuarioSaldoMySqlDAO = new UsuarioSaldoMySqlDAO();
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class UsuarioSaldoMySqlDAO implements UsuarioSaldoDAO
{

    /**
     * Obtener todos los registros condicionados por las
     * columnas usuarioId, mandante y fecha
     *
     * @param String $usuarioId usuarioId
     * @param String $mandante mandante
     * @param String $fecha fecha
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function load($usuarioId, $mandante, $fecha){
        $sql = 'SELECT * FROM usuario_saldo WHERE usuario_id = ?  AND mandante = ?  AND fecha = ? ';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($usuarioId);
        $sqlQuery->setNumber($mandante);
        $sqlQuery->setNumber($fecha);

        return $this->getRow($sqlQuery);
    }

    /**
     * Realiza una consulta personalizada sobre los saldos de usuarios.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Campo por el cual se ordenará la consulta.
     * @param string $sord Orden de la consulta (ASC o DESC).
     * @param int $start Inicio del límite de la consulta.
     * @param int $limit Cantidad de registros a obtener.
     * @param string $filters Filtros en formato JSON para la consulta.
     * @param bool $searchOn Indica si se deben aplicar los filtros.
     * @param string $grouping Campo por el cual se agrupará la consulta.
     * @param string $puntoventas Identificador de punto de ventas.
     * @param bool $conRecargas Indica si se deben incluir las recargas en la consulta.
     * @param bool $conCount Indica si se debe contar el total de registros.
     * @param string $fechaInicio Fecha de inicio para filtrar los registros.
     * @param string $fechaFinal Fecha final para filtrar los registros.
     * @return string JSON con el conteo y los datos resultantes de la consulta.
     */
    public function queryUsuarioSaldosCustom($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$grouping="",$puntoventas='',$conRecargas=true,$conCount=false,$fechaInicio="",$fechaFinal="")
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
                        $fieldOperation =" NOT IN (" . $fieldData . ")";
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

        $leftPuntos='';
        if($puntoventas != ''){
            $leftPuntos =' INNER JOIN usuario usuario_punto_venta ON (usuario.puntoventa_id = usuario_punto_venta.usuario_id) LEFT OUTER JOIN punto_venta ON (punto_venta.usuario_id = usuario.puntoventa_id) LEFT OUTER JOIN concesionario ON (concesionario.usuhijo_id = usuario_punto_venta.usuario_id AND prodinterno_id=0 AND concesionario.estado="A")';
        }

        $nameTable="usuario_saldo";

        if($fechaInicio == $fechaFinal && $fechaInicio !=''){
            if(date("Y_m_d",strtotime($fechaInicio)) != date("Y_m_d",strtotime('-1 days'))){
                $nameTable="usuario_saldo_".date("Y_m_d",strtotime($fechaInicio));

            }


        }
        if($fechaInicio <='2022-12-31'){
            $count=array();
            $result=array();
            $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

            return $json;
        }
        if($_SESSION['usuario'] =='4089418' || $_SESSION['usuario'] =='4089493' || $_SESSION['usuario'] =='1578169'   || $_SESSION['usuario'] =='6997123' ){
            $nameTable= $nameTable.'_rf  '. ' usuario_saldo ';
        }else{
            $nameTable = $nameTable.' usuario_saldo ';
        }



        $sql = 'SELECT  /*+ MAX_EXECUTION_TIME(50000) */ count(*) count FROM '.$nameTable.'  INNER JOIN usuario ON (usuario_saldo.usuario_id = usuario.usuario_id)  INNER JOIN pais ON (usuario.pais_id = pais.pais_id) inner join usuario_perfil ON (usuario.usuario_id = usuario_perfil.usuario_id) '.$leftPuntos .' '  . $where;


        if($grouping != "" && $puntoventas == ''){
            $where = $where . " GROUP BY " . $grouping;
        }

        if($_REQUEST['debugFixed']=='1'){
            print_r($sql);
        }



        $sqlQuery = new SqlQuery($sql);

        if($conCount){
            $count = $this->execute2($sqlQuery);

        }


        if($puntoventas != ''){
            $where = $where . " GROUP BY " . $grouping;
        }

        $conRecargasSql="";

        if($conRecargas){
            $conRecargasSql="LEFT OUTER JOIN (select count(*) usuarios_recargas,
                                 SUM(cantidad) cantidad_recargas,
                                 usuario_recarga_resumen.fecha_crea,
                                 usuario_id
                          from usuario_recarga_resumen
                      inner join day_dimension force index (day_dimension_dbtimestamp_timestrc_index)
                    on (day_dimension.dbtimestamp = usuario_recarga_resumen.fecha_crea)
                          GROUP BY usuario_id, fecha_crea) usuario_recargas ON (usuario_recargas.fecha_crea = fecha and
                                                                                usuario_saldo.usuario_id = usuario_recargas.usuario_id)";
        }

        $sql = 'SELECT  /*+ MAX_EXECUTION_TIME(50000) */ ' .$select .'  FROM '.$nameTable.'  INNER JOIN usuario ON (usuario_saldo.usuario_id = usuario.usuario_id)  INNER JOIN pais ON (usuario.pais_id = pais.pais_id) inner join usuario_perfil ON (usuario.usuario_id = usuario_perfil.usuario_id) '.$conRecargasSql.' ' .$leftPuntos .' ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;
        $sqlQuery = new SqlQuery($sql);

        $result = $Helpers->process_data($this->execute2($sqlQuery));

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
    }


    /**
     * Consulta personalizada de saldos de usuarios en bodega.
     *
     * @param string $select Campos a seleccionar en la consulta.
     * @param string $sidx Campo por el cual se ordenará la consulta.
     * @param string $sord Orden de la consulta (ASC o DESC).
     * @param int $start Inicio del límite de la consulta.
     * @param int $limit Cantidad de registros a obtener.
     * @param string $filters Filtros en formato JSON para la consulta.
     * @param bool $searchOn Indica si se deben aplicar los filtros.
     * @param string $grouping Campo por el cual se agrupará la consulta.
     * @param string $puntoventas Identificador de punto de ventas.
     * @param bool $conRecargas Indica si se deben incluir recargas en la consulta.
     * @return string JSON con el conteo y los datos obtenidos de la consulta.
     */
    public function queryUsuarioSaldosCustomBodega($select, $sidx, $sord, $start, $limit, $filters, $searchOn,$grouping="",$puntoventas='',$conRecargas=true)
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
                        $fieldOperation =" NOT IN (" . $fieldData . ")";
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

        $leftPuntos='';
        if($puntoventas != ''){
            //$leftPuntos =' INNER JOIN usuario usuario_punto_venta ON (usuario.puntoventa_id = usuario_punto_venta.usuario_id) LEFT OUTER JOIN punto_venta ON (punto_venta.usuario_id = usuario.puntoventa_id) LEFT OUTER JOIN concesionario ON (concesionario.usuhijo_id = usuario_punto_venta.usuario_id AND prodinterno_id=0 AND concesionario.estado="A")';
        }

        $sql = 'SELECT count(*) count FROM usuario_saldo INNER JOIN usuario ON (usuario_saldo.usuario_id = usuario.usuario_id)  INNER JOIN pais ON (usuario.pais_id = pais.pais_id) inner join usuario_perfil ON (usuario.usuario_id = usuario_perfil.usuario_id) '.$leftPuntos .' '  . $where;


        if($grouping != "" && $puntoventas == ''){
            $where = $where . " GROUP BY " . $grouping;
        }



        //$sqlQuery = new SqlQuery($sql);

        //$count = $this->execute2($sqlQuery);


        if($puntoventas != ''){
            $where = $where . " GROUP BY " . $grouping;
        }

        $conRecargasSql="";

        if($conRecargas){
            $conRecargasSql="";
        }
        $nameTable ='bodega_usuario_saldo';
        if($_SESSION['usuario'] =='4089418' || $_SESSION['usuario'] =='4089493' || $_SESSION['usuario'] =='1578169'  || $_SESSION['usuario'] =='6997123' ){
            $nameTable= $nameTable.'_rf  '.' bodega_usuario_saldo ';
        }else{
            $nameTable = $nameTable.' bodega_usuario_saldo ';
        }

        $sql = 'SELECT  /*+ MAX_EXECUTION_TIME(50000) */   ' .$select .'  FROM '.$nameTable.'   INNER JOIN pais ON (bodega_usuario_saldo.pais_id = pais.pais_id)  '.$conRecargasSql.' ' .$leftPuntos .' ' . $where . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;
        $sqlQuery = new SqlQuery($sql);

        if ($_ENV["debugFixed"] == '1') {
            print_r($sql);
        }

        $result = $Helpers->process_data($this->execute2($sqlQuery));

        $json = '{ "count" : ' . json_encode($count) . ', "data" : ' . json_encode($result) . '}';

        return $json;
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
    public function queryAll(){
        $sql = 'SELECT * FROM usuario_saldo';
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
    public function queryAllOrderBy($orderColumn){
        $sql = 'SELECT * FROM usuario_saldo ORDER BY '.$orderColumn;
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }

    /**
     * Eliminar todos los registros condicionados
     * por la columna usuarioId, el mandante y la fecha
     *
     * @param String $usuarioId usuarioId
     * @param String $mandante mandante
     * @param String $fecha fecha
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function delete($usuarioId, $mandante, $fecha){
        $sql = 'DELETE FROM usuario_saldo WHERE usuario_id = ?  AND mandante = ?  AND fecha = ? ';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($usuarioId);
        $sqlQuery->setNumber($mandante);
        $sqlQuery->setNumber($fecha);

        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Insertar un registro en la base de datos
     *
     * @param Objeto usuarioSaldo usuarioSaldo
     *
     * @return String $id resultado de la consulta
     *
     */
    public function insert($usuarioSaldo){
        $sql = 'INSERT INTO usuario_saldo (saldo_recarga, saldo_apuestas, saldo_premios, saldo_notaret_pagadas, saldo_notaret_pend, saldo_ajustes_entrada, saldo_ajustes_salida, saldo_inicial, saldo_final, saldo_bono, usuario_id, mandante, fecha) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->set($usuarioSaldo->saldoRecarga);
        $sqlQuery->set($usuarioSaldo->saldoApuestas);
        $sqlQuery->set($usuarioSaldo->saldoPremios);
        $sqlQuery->set($usuarioSaldo->saldoNotaretPagadas);
        $sqlQuery->set($usuarioSaldo->saldoNotaretPend);
        $sqlQuery->set($usuarioSaldo->saldoAjustesEntrada);
        $sqlQuery->set($usuarioSaldo->saldoAjustesSalida);
        $sqlQuery->set($usuarioSaldo->saldoInicial);
        $sqlQuery->set($usuarioSaldo->saldoFinal);
        $sqlQuery->set($usuarioSaldo->saldoBono);


        $sqlQuery->setNumber($usuarioSaldo->usuarioId);

        $sqlQuery->setNumber($usuarioSaldo->mandante);

        $sqlQuery->setNumber($usuarioSaldo->fecha);

        $this->executeInsert($sqlQuery);
        //$usuarioSaldo->id = $id;
        //return $id;
    }

    /**
     * Editar un registro en la base de datos
     *
     * @param Objeto usuarioSaldo usuarioSaldo
     *
     * @return boolean $ resultado de la consulta
     *
     */
    public function update($usuarioSaldo){
        $sql = 'UPDATE usuario_saldo SET saldo_recarga = ?, saldo_apuestas = ?, saldo_premios = ?, saldo_notaret_pagadas = ?, saldo_notaret_pend = ?, saldo_ajustes_entrada = ?, saldo_ajustes_salida = ?, saldo_inicial = ?, saldo_final = ?, saldo_bono = ? WHERE usuario_id = ?  AND mandante = ?  AND fecha = ? ';
        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->set($usuarioSaldo->saldoRecarga);
        $sqlQuery->set($usuarioSaldo->saldoApuestas);
        $sqlQuery->set($usuarioSaldo->saldoPremios);
        $sqlQuery->set($usuarioSaldo->saldoNotaretPagadas);
        $sqlQuery->set($usuarioSaldo->saldoNotaretPend);
        $sqlQuery->set($usuarioSaldo->saldoAjustesEntrada);
        $sqlQuery->set($usuarioSaldo->saldoAjustesSalida);
        $sqlQuery->set($usuarioSaldo->saldoInicial);
        $sqlQuery->set($usuarioSaldo->saldoFinal);
        $sqlQuery->set($usuarioSaldo->saldoBono);


        $sqlQuery->setNumber($usuarioSaldo->usuarioId);

        $sqlQuery->setNumber($usuarioSaldo->mandante);

        $sqlQuery->setNumber($usuarioSaldo->fecha);

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
    public function clean(){
        $sql = 'DELETE FROM usuario_saldo';
        $sqlQuery = new SqlQuery($sql);
        return $this->executeUpdate($sqlQuery);
    }








    /**
     * Obtener todos los registros donde se encuentre que
     * la columna saldo_recarga sea igual al valor pasado como parámetro
     *
     * @param String $value saldo_recarga requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryBySaldoRecarga($value){
        $sql = 'SELECT * FROM usuario_saldo WHERE saldo_recarga = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna saldo_apuestas sea igual al valor pasado como parámetro
     *
     * @param String $value saldo_apuestas requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryBySaldoApuestas($value){
        $sql = 'SELECT * FROM usuario_saldo WHERE saldo_apuestas = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna saldo_premios sea igual al valor pasado como parámetro
     *
     * @param String $value saldo_premios requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryBySaldoPremios($value){
        $sql = 'SELECT * FROM usuario_saldo WHERE saldo_premios = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna saldo_notaret_pagadas sea igual al valor pasado como parámetro
     *
     * @param String $value saldo_notaret_pagadas requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryBySaldoNotaretPagadas($value){
        $sql = 'SELECT * FROM usuario_saldo WHERE saldo_notaret_pagadas = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna saldo_notaret_pend sea igual al valor pasado como parámetro
     *
     * @param String $value saldo_notaret_pend requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryBySaldoNotaretPend($value){
        $sql = 'SELECT * FROM usuario_saldo WHERE saldo_notaret_pend = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna saldo_ajustes_entrada sea igual al valor pasado como parámetro
     *
     * @param String $value saldo_ajustes_entrada requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryBySaldoAjustesEntrada($value){
        $sql = 'SELECT * FROM usuario_saldo WHERE saldo_ajustes_entrada = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna saldo_ajustes_salida sea igual al valor pasado como parámetro
     *
     * @param String $value saldo_ajustes_salida requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryBySaldoAjustesSalida($value){
        $sql = 'SELECT * FROM usuario_saldo WHERE saldo_ajustes_salida = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna saldo_inicial sea igual al valor pasado como parámetro
     *
     * @param String $value saldo_inicial requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryBySaldoInicial($value){
        $sql = 'SELECT * FROM usuario_saldo WHERE saldo_inicial = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna saldo_final sea igual al valor pasado como parámetro
     *
     * @param String $value saldo_final requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryBySaldoFinal($value){
        $sql = 'SELECT * FROM usuario_saldo WHERE saldo_final = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna saldo_bono sea igual al valor pasado como parámetro
     *
     * @param String $value saldo_bono requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function queryBySaldoBono($value){
        $sql = 'SELECT * FROM usuario_saldo WHERE saldo_bono = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->getList($sqlQuery);
    }















    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna saldo_recarga sea igual al valor pasado como parámetro
     *
     * @param String $value saldo_recarga requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteBySaldoRecarga($value){
        $sql = 'DELETE FROM usuario_saldo WHERE saldo_recarga = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna saldo_apuestas sea igual al valor pasado como parámetro
     *
     * @param String $value saldo_apuestas requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteBySaldoApuestas($value){
        $sql = 'DELETE FROM usuario_saldo WHERE saldo_apuestas = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna saldo_premios sea igual al valor pasado como parámetro
     *
     * @param String $value saldo_premios requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteBySaldoPremios($value){
        $sql = 'DELETE FROM usuario_saldo WHERE saldo_premios = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna saldo_notaret_pagadas sea igual al valor pasado como parámetro
     *
     * @param String $value saldo_notaret_pagadas requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteBySaldoNotaretPagadas($value){
        $sql = 'DELETE FROM usuario_saldo WHERE saldo_notaret_pagadas = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna saldo_notaret_pend sea igual al valor pasado como parámetro
     *
     * @param String $value saldo_notaret_pend requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteBySaldoNotaretPend($value){
        $sql = 'DELETE FROM usuario_saldo WHERE saldo_notaret_pend = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna saldo_ajustes_entrada sea igual al valor pasado como parámetro
     *
     * @param String $value saldo_ajustes_entrada requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteBySaldoAjustesEntrada($value){
        $sql = 'DELETE FROM usuario_saldo WHERE saldo_ajustes_entrada = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna saldo_ajustes_salida sea igual al valor pasado como parámetro
     *
     * @param String $value saldo_ajustes_salida requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteBySaldoAjustesSalida($value){
        $sql = 'DELETE FROM usuario_saldo WHERE saldo_ajustes_salida = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna saldo_inicial sea igual al valor pasado como parámetro
     *
     * @param String $value saldo_inicial requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteBySaldoInicial($value){
        $sql = 'DELETE FROM usuario_saldo WHERE saldo_inicial = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna saldo_final sea igual al valor pasado como parámetro
     *
     * @param String $value saldo_final requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteBySaldoFinal($value){
        $sql = 'DELETE FROM usuario_saldo WHERE saldo_final = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna saldo_bono sea igual al valor pasado como parámetro
     *
     * @param String $value saldo_bono requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
    public function deleteBySaldoBono($value){
        $sql = 'DELETE FROM usuario_saldo WHERE saldo_bono = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($value);
        return $this->executeUpdate($sqlQuery);
    }


	








    /**
     * Crear y devolver un objeto del tipo UsuarioSaldo
     * con los valores de una consulta sql
     *
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Objeto $usuarioSaldo UsuarioSaldo
     *
     * @access protected
     *
     */
    protected function readRow($row){
        $usuarioSaldo = new UsuarioSaldo();

        $usuarioSaldo->usuarioId = $row['usuario_id'];
        $usuarioSaldo->mandante = $row['mandante'];
        $usuarioSaldo->fecha = $row['fecha'];
        $usuarioSaldo->saldoRecarga = $row['saldo_recarga'];
        $usuarioSaldo->saldoApuestas = $row['saldo_apuestas'];
        $usuarioSaldo->saldoPremios = $row['saldo_premios'];
        $usuarioSaldo->saldoNotaretPagadas = $row['saldo_notaret_pagadas'];
        $usuarioSaldo->saldoNotaretPend = $row['saldo_notaret_pend'];
        $usuarioSaldo->saldoAjustesEntrada = $row['saldo_ajustes_entrada'];
        $usuarioSaldo->saldoAjustesSalida = $row['saldo_ajustes_salida'];
        $usuarioSaldo->saldoInicial = $row['saldo_inicial'];
        $usuarioSaldo->saldoFinal = $row['saldo_final'];
        $usuarioSaldo->saldoBono = $row['saldo_bono'];

        return $usuarioSaldo;
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
    protected function getList($sqlQuery){
        $tab = QueryExecutor::execute($this->transaction,$sqlQuery);
        $ret = array();
        for($i=0;$i<oldCount($tab);$i++){
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
    protected function getRow($sqlQuery){
        $tab = QueryExecutor::execute($this->transaction,$sqlQuery);
        if(oldCount($tab)==0){
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
    protected function execute($sqlQuery){
        return QueryExecutor::execute($this->transaction,$sqlQuery);
    }

    /**
     * Execute2 sql query
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
    protected function executeUpdate($sqlQuery){
        return QueryExecutor::executeUpdate($this->transaction,$sqlQuery);
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
    protected function querySingleResult($sqlQuery){
        return QueryExecutor::queryForString($this->transaction,$sqlQuery);
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
    protected function executeInsert($sqlQuery){
        return QueryExecutor::executeInsert($this->transaction,$sqlQuery);
    }
}
?>
