<?php namespace Backend\mysql;
/** 
* Clase 'UsuarioSaldo2MySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'UsuarioSaldo2'
* 
* Ejemplo de uso: 
* $UsuarioSaldo2MySqlDAO = new UsuarioSaldo2MySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class UsuarioSaldo2MySqlDAO implements UsuarioSaldo2DAO
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
		$sql = 'SELECT * FROM usuario_saldo2 WHERE usuario_id = ?  AND mandante = ?  AND fecha = ? ';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($usuarioId);
		$sqlQuery->setNumber($mandante);
		$sqlQuery->setNumber($fecha);

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
	public function queryAll(){
		$sql = 'SELECT * FROM usuario_saldo2';
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
		$sql = 'SELECT * FROM usuario_saldo2 ORDER BY '.$orderColumn;
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
		$sql = 'DELETE FROM usuario_saldo2 WHERE usuario_id = ?  AND mandante = ?  AND fecha = ? ';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($usuarioId);
		$sqlQuery->setNumber($mandante);
		$sqlQuery->setNumber($fecha);

		return $this->executeUpdate($sqlQuery);
	}
	

    /**
     * Insertar un registro en la base de datos
     *
     * @param Objeto usuarioSaldo2 usuarioSaldo2
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($usuarioSaldo2){
		$sql = 'INSERT INTO usuario_saldo2 (saldo_recarga_ini, saldo_apuestas_ini, saldo_premios_ini, saldo_notaret_pagadas_ini, saldo_notaret_pend_ini, saldo_ajustes_entrada_ini, saldo_ajustes_salida_ini, saldo_bono_ini, saldo_recarga_fin, saldo_apuestas_fin, saldo_premios_fin, saldo_notaret_pagadas_fin, saldo_notaret_pend_fin, saldo_ajustes_entrada_fin, saldo_ajustes_salida_fin, saldo_bono_fin, usuario_id, mandante, fecha) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($usuarioSaldo2->saldoRecargaIni);
		$sqlQuery->set($usuarioSaldo2->saldoApuestasIni);
		$sqlQuery->set($usuarioSaldo2->saldoPremiosIni);
		$sqlQuery->set($usuarioSaldo2->saldoNotaretPagadasIni);
		$sqlQuery->set($usuarioSaldo2->saldoNotaretPendIni);
		$sqlQuery->set($usuarioSaldo2->saldoAjustesEntradaIni);
		$sqlQuery->set($usuarioSaldo2->saldoAjustesSalidaIni);
		$sqlQuery->set($usuarioSaldo2->saldoBonoIni);
		$sqlQuery->set($usuarioSaldo2->saldoRecargaFin);
		$sqlQuery->set($usuarioSaldo2->saldoApuestasFin);
		$sqlQuery->set($usuarioSaldo2->saldoPremiosFin);
		$sqlQuery->set($usuarioSaldo2->saldoNotaretPagadasFin);
		$sqlQuery->set($usuarioSaldo2->saldoNotaretPendFin);
		$sqlQuery->set($usuarioSaldo2->saldoAjustesEntradaFin);
		$sqlQuery->set($usuarioSaldo2->saldoAjustesSalidaFin);
		$sqlQuery->set($usuarioSaldo2->saldoBonoFin);

		
		$sqlQuery->setNumber($usuarioSaldo2->usuarioId);

		$sqlQuery->setNumber($usuarioSaldo2->mandante);

		$sqlQuery->setNumber($usuarioSaldo2->fecha);

		$this->executeInsert($sqlQuery);	
		//$usuarioSaldo2->id = $id;
		//return $id;
	}
	
    /**
     * Editar un registro en la base de datos
     *
     * @param Objeto usuarioSaldo2 usuarioSaldo2
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function update($usuarioSaldo2){
		$sql = 'UPDATE usuario_saldo2 SET saldo_recarga_ini = ?, saldo_apuestas_ini = ?, saldo_premios_ini = ?, saldo_notaret_pagadas_ini = ?, saldo_notaret_pend_ini = ?, saldo_ajustes_entrada_ini = ?, saldo_ajustes_salida_ini = ?, saldo_bono_ini = ?, saldo_recarga_fin = ?, saldo_apuestas_fin = ?, saldo_premios_fin = ?, saldo_notaret_pagadas_fin = ?, saldo_notaret_pend_fin = ?, saldo_ajustes_entrada_fin = ?, saldo_ajustes_salida_fin = ?, saldo_bono_fin = ? WHERE usuario_id = ?  AND mandante = ?  AND fecha = ? ';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($usuarioSaldo2->saldoRecargaIni);
		$sqlQuery->set($usuarioSaldo2->saldoApuestasIni);
		$sqlQuery->set($usuarioSaldo2->saldoPremiosIni);
		$sqlQuery->set($usuarioSaldo2->saldoNotaretPagadasIni);
		$sqlQuery->set($usuarioSaldo2->saldoNotaretPendIni);
		$sqlQuery->set($usuarioSaldo2->saldoAjustesEntradaIni);
		$sqlQuery->set($usuarioSaldo2->saldoAjustesSalidaIni);
		$sqlQuery->set($usuarioSaldo2->saldoBonoIni);
		$sqlQuery->set($usuarioSaldo2->saldoRecargaFin);
		$sqlQuery->set($usuarioSaldo2->saldoApuestasFin);
		$sqlQuery->set($usuarioSaldo2->saldoPremiosFin);
		$sqlQuery->set($usuarioSaldo2->saldoNotaretPagadasFin);
		$sqlQuery->set($usuarioSaldo2->saldoNotaretPendFin);
		$sqlQuery->set($usuarioSaldo2->saldoAjustesEntradaFin);
		$sqlQuery->set($usuarioSaldo2->saldoAjustesSalidaFin);
		$sqlQuery->set($usuarioSaldo2->saldoBonoFin);

		
		$sqlQuery->setNumber($usuarioSaldo2->usuarioId);

		$sqlQuery->setNumber($usuarioSaldo2->mandante);

		$sqlQuery->setNumber($usuarioSaldo2->fecha);

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
		$sql = 'DELETE FROM usuario_saldo2';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}














    /**
     * Obtener todos los registros donde se encuentre que
     * la columna saldo_recarga_ini sea igual al valor pasado como parámetro
     *
     * @param String $value saldo_recarga_ini requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryBySaldoRecargaIni($value){
		$sql = 'SELECT * FROM usuario_saldo2 WHERE saldo_recarga_ini = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna saldo_apuestas_ini sea igual al valor pasado como parámetro
     *
     * @param String $value saldo_apuestas_ini requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryBySaldoApuestasIni($value){
		$sql = 'SELECT * FROM usuario_saldo2 WHERE saldo_apuestas_ini = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna saldo_premios_ini sea igual al valor pasado como parámetro
     *
     * @param String $value saldo_premios_ini requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryBySaldoPremiosIni($value){
		$sql = 'SELECT * FROM usuario_saldo2 WHERE saldo_premios_ini = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna saldo_notaret_pagadas_ini sea igual al valor pasado como parámetro
     *
     * @param String $value saldo_notaret_pagadas_ini requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryBySaldoNotaretPagadasIni($value){
		$sql = 'SELECT * FROM usuario_saldo2 WHERE saldo_notaret_pagadas_ini = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna saldo_notaret_pend_ini sea igual al valor pasado como parámetro
     *
     * @param String $value saldo_notaret_pend_ini requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryBySaldoNotaretPendIni($value){
		$sql = 'SELECT * FROM usuario_saldo2 WHERE saldo_notaret_pend_ini = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna saldo_ajustes_entrada_ini sea igual al valor pasado como parámetro
     *
     * @param String $value saldo_ajustes_entrada_ini requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryBySaldoAjustesEntradaIni($value){
		$sql = 'SELECT * FROM usuario_saldo2 WHERE saldo_ajustes_entrada_ini = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna saldo_ajustes_salida_ini sea igual al valor pasado como parámetro
     *
     * @param String $value saldo_ajustes_salida_ini requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryBySaldoAjustesSalidaIni($value){
		$sql = 'SELECT * FROM usuario_saldo2 WHERE saldo_ajustes_salida_ini = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna saldo_bono_ini sea igual al valor pasado como parámetro
     *
     * @param String $value saldo_bono_ini requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryBySaldoBonoIni($value){
		$sql = 'SELECT * FROM usuario_saldo2 WHERE saldo_bono_ini = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna saldo_recarga_fin sea igual al valor pasado como parámetro
     *
     * @param String $value saldo_recarga_fin requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryBySaldoRecargaFin($value){
		$sql = 'SELECT * FROM usuario_saldo2 WHERE saldo_recarga_fin = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna saldo_apuestas_fin sea igual al valor pasado como parámetro
     *
     * @param String $value saldo_apuestas_fin requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryBySaldoApuestasFin($value){
		$sql = 'SELECT * FROM usuario_saldo2 WHERE saldo_apuestas_fin = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna saldo_premios_fin sea igual al valor pasado como parámetro
     *
     * @param String $value saldo_premios_fin requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryBySaldoPremiosFin($value){
		$sql = 'SELECT * FROM usuario_saldo2 WHERE saldo_premios_fin = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna saldo_notaret_pagadas_fin sea igual al valor pasado como parámetro
     *
     * @param String $value saldo_notaret_pagadas_fin requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryBySaldoNotaretPagadasFin($value){
		$sql = 'SELECT * FROM usuario_saldo2 WHERE saldo_notaret_pagadas_fin = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna saldo_notaret_pend_fin sea igual al valor pasado como parámetro
     *
     * @param String $value saldo_notaret_pend_fin requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryBySaldoNotaretPendFin($value){
		$sql = 'SELECT * FROM usuario_saldo2 WHERE saldo_notaret_pend_fin = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna saldo_ajustes_entrada_fin sea igual al valor pasado como parámetro
     *
     * @param String $value saldo_ajustes_entrada_fin requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryBySaldoAjustesEntradaFin($value){
		$sql = 'SELECT * FROM usuario_saldo2 WHERE saldo_ajustes_entrada_fin = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna saldo_ajustes_salida_fin sea igual al valor pasado como parámetro
     *
     * @param String $value saldo_ajustes_salida_fin requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryBySaldoAjustesSalidaFin($value){
		$sql = 'SELECT * FROM usuario_saldo2 WHERE saldo_ajustes_salida_fin = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna saldo_bono_fin sea igual al valor pasado como parámetro
     *
     * @param String $value saldo_bono_fin requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryBySaldoBonoFin($value){
		$sql = 'SELECT * FROM usuario_saldo2 WHERE saldo_bono_fin = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}















    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna saldo_recarga_ini sea igual al valor pasado como parámetro
     *
     * @param String $value saldo_recarga_ini requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteBySaldoRecargaIni($value){
		$sql = 'DELETE FROM usuario_saldo2 WHERE saldo_recarga_ini = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna saldo_apuestas_ini sea igual al valor pasado como parámetro
     *
     * @param String $value saldo_apuestas_ini requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteBySaldoApuestasIni($value){
		$sql = 'DELETE FROM usuario_saldo2 WHERE saldo_apuestas_ini = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna saldo_premios_ini sea igual al valor pasado como parámetro
     *
     * @param String $value saldo_premios_ini requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteBySaldoPremiosIni($value){
		$sql = 'DELETE FROM usuario_saldo2 WHERE saldo_premios_ini = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna saldo_notaret_pagadas_ini sea igual al valor pasado como parámetro
     *
     * @param String $value saldo_notaret_pagadas_ini requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteBySaldoNotaretPagadasIni($value){
		$sql = 'DELETE FROM usuario_saldo2 WHERE saldo_notaret_pagadas_ini = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna saldo_notaret_pend_ini sea igual al valor pasado como parámetro
     *
     * @param String $value saldo_notaret_pend_ini requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteBySaldoNotaretPendIni($value){
		$sql = 'DELETE FROM usuario_saldo2 WHERE saldo_notaret_pend_ini = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna saldo_ajustes_entrada_ini sea igual al valor pasado como parámetro
     *
     * @param String $value saldo_ajustes_entrada_ini requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteBySaldoAjustesEntradaIni($value){
		$sql = 'DELETE FROM usuario_saldo2 WHERE saldo_ajustes_entrada_ini = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna saldo_ajustes_salida_ini sea igual al valor pasado como parámetro
     *
     * @param String $value saldo_ajustes_salida_ini requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteBySaldoAjustesSalidaIni($value){
		$sql = 'DELETE FROM usuario_saldo2 WHERE saldo_ajustes_salida_ini = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna saldo_bono_ini sea igual al valor pasado como parámetro
     *
     * @param String $value saldo_bono_ini requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteBySaldoBonoIni($value){
		$sql = 'DELETE FROM usuario_saldo2 WHERE saldo_bono_ini = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna saldo_recarga_fin sea igual al valor pasado como parámetro
     *
     * @param String $value saldo_recarga_fin requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteBySaldoRecargaFin($value){
		$sql = 'DELETE FROM usuario_saldo2 WHERE saldo_recarga_fin = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna saldo_apuestas_fin sea igual al valor pasado como parámetro
     *
     * @param String $value saldo_apuestas_fin requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteBySaldoApuestasFin($value){
		$sql = 'DELETE FROM usuario_saldo2 WHERE saldo_apuestas_fin = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna saldo_premios_fin sea igual al valor pasado como parámetro
     *
     * @param String $value saldo_premios_fin requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteBySaldoPremiosFin($value){
		$sql = 'DELETE FROM usuario_saldo2 WHERE saldo_premios_fin = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna saldo_notaret_pagadas_fin sea igual al valor pasado como parámetro
     *
     * @param String $value saldo_notaret_pagadas_fin requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteBySaldoNotaretPagadasFin($value){
		$sql = 'DELETE FROM usuario_saldo2 WHERE saldo_notaret_pagadas_fin = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna saldo_notaret_pend_fin sea igual al valor pasado como parámetro
     *
     * @param String $value saldo_notaret_pend_fin requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteBySaldoNotaretPendFin($value){
		$sql = 'DELETE FROM usuario_saldo2 WHERE saldo_notaret_pend_fin = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna saldo_ajustes_entrada_fin sea igual al valor pasado como parámetro
     *
     * @param String $value saldo_ajustes_entrada_fin requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteBySaldoAjustesEntradaFin($value){
		$sql = 'DELETE FROM usuario_saldo2 WHERE saldo_ajustes_entrada_fin = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna saldo_ajustes_salida_fin sea igual al valor pasado como parámetro
     *
     * @param String $value saldo_ajustes_salida_fin requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteBySaldoAjustesSalidaFin($value){
		$sql = 'DELETE FROM usuario_saldo2 WHERE saldo_ajustes_salida_fin = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna saldo_bono_fin sea igual al valor pasado como parámetro
     *
     * @param String $value saldo_bono_fin requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteBySaldoBonoFin($value){
		$sql = 'DELETE FROM usuario_saldo2 WHERE saldo_bono_fin = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}











	
    /**
     * Crear y devolver un objeto del tipo UsuarioSaldo2
     * con los valores de una consulta sql
     * 
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Objeto $usuarioSaldo2 UsuarioSaldo2
     *
     * @access protected
     *
     */
	protected function readRow($row){
		$usuarioSaldo2 = new UsuarioSaldo2();
		
		$usuarioSaldo2->usuarioId = $row['usuario_id'];
		$usuarioSaldo2->mandante = $row['mandante'];
		$usuarioSaldo2->fecha = $row['fecha'];
		$usuarioSaldo2->saldoRecargaIni = $row['saldo_recarga_ini'];
		$usuarioSaldo2->saldoApuestasIni = $row['saldo_apuestas_ini'];
		$usuarioSaldo2->saldoPremiosIni = $row['saldo_premios_ini'];
		$usuarioSaldo2->saldoNotaretPagadasIni = $row['saldo_notaret_pagadas_ini'];
		$usuarioSaldo2->saldoNotaretPendIni = $row['saldo_notaret_pend_ini'];
		$usuarioSaldo2->saldoAjustesEntradaIni = $row['saldo_ajustes_entrada_ini'];
		$usuarioSaldo2->saldoAjustesSalidaIni = $row['saldo_ajustes_salida_ini'];
		$usuarioSaldo2->saldoBonoIni = $row['saldo_bono_ini'];
		$usuarioSaldo2->saldoRecargaFin = $row['saldo_recarga_fin'];
		$usuarioSaldo2->saldoApuestasFin = $row['saldo_apuestas_fin'];
		$usuarioSaldo2->saldoPremiosFin = $row['saldo_premios_fin'];
		$usuarioSaldo2->saldoNotaretPagadasFin = $row['saldo_notaret_pagadas_fin'];
		$usuarioSaldo2->saldoNotaretPendFin = $row['saldo_notaret_pend_fin'];
		$usuarioSaldo2->saldoAjustesEntradaFin = $row['saldo_ajustes_entrada_fin'];
		$usuarioSaldo2->saldoAjustesSalidaFin = $row['saldo_ajustes_salida_fin'];
		$usuarioSaldo2->saldoBonoFin = $row['saldo_bono_fin'];

		return $usuarioSaldo2;
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