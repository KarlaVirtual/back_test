<?php namespace Backend\mysql;
/** 
* Clase 'PuntoventadimMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'Puntoventadim'
* 
* Ejemplo de uso: 
* $PuntoventadimMySqlDAO = new PuntoventadimMySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class PuntoventadimMySqlDAO implements PuntoventadimDAO{


    /**
     * Obtener el registro condicionado por la 
     * llave primaria que se pasa como parámetro
     *
     * @param String $id llave primaria
     *
     * @return Array resultado de la consulta
     *
     */
	public function load($id){
		$sql = 'SELECT * FROM puntoventadim WHERE puntoventa_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($id);
		return $this->getRow($sqlQuery);
	}

    /**
     * Obtener todos los registros de la base datos
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryAll(){
		$sql = 'SELECT * FROM puntoventadim';
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
     * @return Array resultado de la consulta
     *
     */
	public function queryAllOrderBy($orderColumn){
		$sql = 'SELECT * FROM puntoventadim ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
    
    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $puntoventa_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function delete($puntoventa_id){
		$sql = 'DELETE FROM puntoventadim WHERE puntoventa_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($puntoventa_id);
		return $this->executeUpdate($sqlQuery);
	}
	
    /**
     * Insertar un registro en la base de datos
     *
     * @param Object puntoventadim puntoventadim
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($puntoventadim){
		$sql = 'INSERT INTO puntoventadim (puntoventa_nom, concesionario_id, concesionario_nom, subconcesionario_id, subconcesionario_nom, depto_id, depto_nom, ciudad_id, ciudad_nom, mandante, regional_id, regional_nom, tipopunto_id, tipopunto_nom, tipoestablecimiento_id, tipoestablecimiento_nom, pais_id, pais_nom, moneda, moneda_nom) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($puntoventadim->puntoventaNom);
		$sqlQuery->set($puntoventadim->concesionarioId);
		$sqlQuery->set($puntoventadim->concesionarioNom);
		$sqlQuery->set($puntoventadim->subconcesionarioId);
		$sqlQuery->set($puntoventadim->subconcesionarioNom);
		$sqlQuery->set($puntoventadim->deptoId);
		$sqlQuery->set($puntoventadim->deptoNom);
		$sqlQuery->set($puntoventadim->ciudadId);
		$sqlQuery->set($puntoventadim->ciudadNom);
		$sqlQuery->set($puntoventadim->mandante);
		$sqlQuery->set($puntoventadim->regionalId);
		$sqlQuery->set($puntoventadim->regionalNom);
		$sqlQuery->set($puntoventadim->tipopuntoId);
		$sqlQuery->set($puntoventadim->tipopuntoNom);
		$sqlQuery->set($puntoventadim->tipoestablecimientoId);
		$sqlQuery->set($puntoventadim->tipoestablecimientoNom);
		$sqlQuery->set($puntoventadim->paisId);
		$sqlQuery->set($puntoventadim->paisNom);
		$sqlQuery->set($puntoventadim->moneda);
		$sqlQuery->set($puntoventadim->monedaNom);

		$id = $this->executeInsert($sqlQuery);	
		$puntoventadim->puntoventaId = $id;
		return $id;
	}
	
    /**
     * Editar un registro en la base de datos
     *
     * @param Object puntoventadim puntoventadim
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function update($puntoventadim){
		$sql = 'UPDATE puntoventadim SET puntoventa_nom = ?, concesionario_id = ?, concesionario_nom = ?, subconcesionario_id = ?, subconcesionario_nom = ?, depto_id = ?, depto_nom = ?, ciudad_id = ?, ciudad_nom = ?, mandante = ?, regional_id = ?, regional_nom = ?, tipopunto_id = ?, tipopunto_nom = ?, tipoestablecimiento_id = ?, tipoestablecimiento_nom = ?, pais_id = ?, pais_nom = ?, moneda = ?, moneda_nom = ? WHERE puntoventa_id = ?';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($puntoventadim->puntoventaNom);
		$sqlQuery->set($puntoventadim->concesionarioId);
		$sqlQuery->set($puntoventadim->concesionarioNom);
		$sqlQuery->set($puntoventadim->subconcesionarioId);
		$sqlQuery->set($puntoventadim->subconcesionarioNom);
		$sqlQuery->set($puntoventadim->deptoId);
		$sqlQuery->set($puntoventadim->deptoNom);
		$sqlQuery->set($puntoventadim->ciudadId);
		$sqlQuery->set($puntoventadim->ciudadNom);
		$sqlQuery->set($puntoventadim->mandante);
		$sqlQuery->set($puntoventadim->regionalId);
		$sqlQuery->set($puntoventadim->regionalNom);
		$sqlQuery->set($puntoventadim->tipopuntoId);
		$sqlQuery->set($puntoventadim->tipopuntoNom);
		$sqlQuery->set($puntoventadim->tipoestablecimientoId);
		$sqlQuery->set($puntoventadim->tipoestablecimientoNom);
		$sqlQuery->set($puntoventadim->paisId);
		$sqlQuery->set($puntoventadim->paisNom);
		$sqlQuery->set($puntoventadim->moneda);
		$sqlQuery->set($puntoventadim->monedaNom);

		$sqlQuery->set($puntoventadim->puntoventaId);
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
		$sql = 'DELETE FROM puntoventadim';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}







    /**
     * Obtener todos los registros donde se encuentre que
     * la columna puntoventa_nom sea igual al valor pasado como parámetro
     *
     * @param String $value puntoventa_nom requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByPuntoventaNom($value){
		$sql = 'SELECT * FROM puntoventadim WHERE puntoventa_nom = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna concesionario_id sea igual al valor pasado como parámetro
     *
     * @param String $value concesionario_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByConcesionarioId($value){
		$sql = 'SELECT * FROM puntoventadim WHERE concesionario_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna concesionario_nom sea igual al valor pasado como parámetro
     *
     * @param String $value concesionario_nom requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByConcesionarioNom($value){
		$sql = 'SELECT * FROM puntoventadim WHERE concesionario_nom = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna subconcesionario_id sea igual al valor pasado como parámetro
     *
     * @param String $value subconcesionario_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryBySubconcesionarioId($value){
		$sql = 'SELECT * FROM puntoventadim WHERE subconcesionario_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna subconcesionario_nom sea igual al valor pasado como parámetro
     *
     * @param String $value subconcesionario_nom requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryBySubconcesionarioNom($value){
		$sql = 'SELECT * FROM puntoventadim WHERE subconcesionario_nom = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna depto_id sea igual al valor pasado como parámetro
     *
     * @param String $value depto_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByDeptoId($value){
		$sql = 'SELECT * FROM puntoventadim WHERE depto_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna depto_nom sea igual al valor pasado como parámetro
     *
     * @param String $value depto_nom requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByDeptoNom($value){
		$sql = 'SELECT * FROM puntoventadim WHERE depto_nom = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna ciudad_id sea igual al valor pasado como parámetro
     *
     * @param String $value ciudad_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByCiudadId($value){
		$sql = 'SELECT * FROM puntoventadim WHERE ciudad_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna ciudad_nom sea igual al valor pasado como parámetro
     *
     * @param String $value ciudad_nom requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByCiudadNom($value){
		$sql = 'SELECT * FROM puntoventadim WHERE ciudad_nom = ?';
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
     * @return Array resultado de la consulta
     *
     */
	public function queryByMandante($value){
		$sql = 'SELECT * FROM puntoventadim WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna regional_id sea igual al valor pasado como parámetro
     *
     * @param String $value regional_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByRegionalId($value){
		$sql = 'SELECT * FROM puntoventadim WHERE regional_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna regional_nom sea igual al valor pasado como parámetro
     *
     * @param String $value regional_nom requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByRegionalNom($value){
		$sql = 'SELECT * FROM puntoventadim WHERE regional_nom = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna tipopunto_id sea igual al valor pasado como parámetro
     *
     * @param String $value tipopunto_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByTipopuntoId($value){
		$sql = 'SELECT * FROM puntoventadim WHERE tipopunto_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna tipopunto_nom sea igual al valor pasado como parámetro
     *
     * @param String $value tipopunto_nom requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByTipopuntoNom($value){
		$sql = 'SELECT * FROM puntoventadim WHERE tipopunto_nom = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna tipoestablecimiento_id sea igual al valor pasado como parámetro
     *
     * @param String $value tipoestablecimiento_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByTipoestablecimientoId($value){
		$sql = 'SELECT * FROM puntoventadim WHERE tipoestablecimiento_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna tipoestablecimiento_nom sea igual al valor pasado como parámetro
     *
     * @param String $value tipoestablecimiento_nom requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByTipoestablecimientoNom($value){
		$sql = 'SELECT * FROM puntoventadim WHERE tipoestablecimiento_nom = ?';
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
     * @return Array resultado de la consulta
     *
     */
	public function queryByPaisId($value){
		$sql = 'SELECT * FROM puntoventadim WHERE pais_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna pais_nom sea igual al valor pasado como parámetro
     *
     * @param String $value pais_nom requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByPaisNom($value){
		$sql = 'SELECT * FROM puntoventadim WHERE pais_nom = ?';
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
     * @return Array resultado de la consulta
     *
     */
	public function queryByMoneda($value){
		$sql = 'SELECT * FROM puntoventadim WHERE moneda = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna moneda_nom sea igual al valor pasado como parámetro
     *
     * @param String $value moneda_nom requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByMonedaNom($value){
		$sql = 'SELECT * FROM puntoventadim WHERE moneda_nom = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}








    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna puntoventa_nom sea igual al valor pasado como parámetro
     *
     * @param String $value puntoventa_nom requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByPuntoventaNom($value){
		$sql = 'DELETE FROM puntoventadim WHERE puntoventa_nom = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna concesionario_id sea igual al valor pasado como parámetro
     *
     * @param String $value concesionario_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByConcesionarioId($value){
		$sql = 'DELETE FROM puntoventadim WHERE concesionario_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna concesionario_nom sea igual al valor pasado como parámetro
     *
     * @param String $value concesionario_nom requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByConcesionarioNom($value){
		$sql = 'DELETE FROM puntoventadim WHERE concesionario_nom = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna subconcesionario_id sea igual al valor pasado como parámetro
     *
     * @param String $value subconcesionario_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteBySubconcesionarioId($value){
		$sql = 'DELETE FROM puntoventadim WHERE subconcesionario_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna subconcesionario_nom sea igual al valor pasado como parámetro
     *
     * @param String $value subconcesionario_nom requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteBySubconcesionarioNom($value){
		$sql = 'DELETE FROM puntoventadim WHERE subconcesionario_nom = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna depto_id sea igual al valor pasado como parámetro
     *
     * @param String $value depto_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByDeptoId($value){
		$sql = 'DELETE FROM puntoventadim WHERE depto_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna depto_nom sea igual al valor pasado como parámetro
     *
     * @param String $value depto_nom requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByDeptoNom($value){
		$sql = 'DELETE FROM puntoventadim WHERE depto_nom = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna ciudad_id sea igual al valor pasado como parámetro
     *
     * @param String $value ciudad_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByCiudadId($value){
		$sql = 'DELETE FROM puntoventadim WHERE ciudad_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna ciudad_nom sea igual al valor pasado como parámetro
     *
     * @param String $value ciudad_nom requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByCiudadNom($value){
		$sql = 'DELETE FROM puntoventadim WHERE ciudad_nom = ?';
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
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByMandante($value){
		$sql = 'DELETE FROM puntoventadim WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna regional_id sea igual al valor pasado como parámetro
     *
     * @param String $value regional_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByRegionalId($value){
		$sql = 'DELETE FROM puntoventadim WHERE regional_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna regional_nom sea igual al valor pasado como parámetro
     *
     * @param String $value regional_nom requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByRegionalNom($value){
		$sql = 'DELETE FROM puntoventadim WHERE regional_nom = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna tipopunto_id sea igual al valor pasado como parámetro
     *
     * @param String $value tipopunto_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByTipopuntoId($value){
		$sql = 'DELETE FROM puntoventadim WHERE tipopunto_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna tipopunto_nom sea igual al valor pasado como parámetro
     *
     * @param String $value tipopunto_nom requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByTipopuntoNom($value){
		$sql = 'DELETE FROM puntoventadim WHERE tipopunto_nom = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna tipoestablecimiento_id sea igual al valor pasado como parámetro
     *
     * @param String $value tipoestablecimiento_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByTipoestablecimientoId($value){
		$sql = 'DELETE FROM puntoventadim WHERE tipoestablecimiento_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna tipoestablecimiento_nom sea igual al valor pasado como parámetro
     *
     * @param String $value tipoestablecimiento_nom requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByTipoestablecimientoNom($value){
		$sql = 'DELETE FROM puntoventadim WHERE tipoestablecimiento_nom = ?';
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
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByPaisId($value){
		$sql = 'DELETE FROM puntoventadim WHERE pais_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna pais_nom sea igual al valor pasado como parámetro
     *
     * @param String $value pais_nom requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByPaisNom($value){
		$sql = 'DELETE FROM puntoventadim WHERE pais_nom = ?';
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
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByMoneda($value){
		$sql = 'DELETE FROM puntoventadim WHERE moneda = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna moneda_nom sea igual al valor pasado como parámetro
     *
     * @param String $value moneda_nom requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByMonedaNom($value){
		$sql = 'DELETE FROM puntoventadim WHERE moneda_nom = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}


	






    /**
     * Crear y devolver un objeto del tipo Puntoventadim
     * con los valores de una consulta sql
     * 
     *  
     * @param Arreglo $row arreglo asociativo
     *
     * @return Object $Puntoventadim Puntoventadim
     *
     * @access protected
     *
     */
	protected function readRow($row){
		$puntoventadim = new Puntoventadim();
		
		$puntoventadim->puntoventaId = $row['puntoventa_id'];
		$puntoventadim->puntoventaNom = $row['puntoventa_nom'];
		$puntoventadim->concesionarioId = $row['concesionario_id'];
		$puntoventadim->concesionarioNom = $row['concesionario_nom'];
		$puntoventadim->subconcesionarioId = $row['subconcesionario_id'];
		$puntoventadim->subconcesionarioNom = $row['subconcesionario_nom'];
		$puntoventadim->deptoId = $row['depto_id'];
		$puntoventadim->deptoNom = $row['depto_nom'];
		$puntoventadim->ciudadId = $row['ciudad_id'];
		$puntoventadim->ciudadNom = $row['ciudad_nom'];
		$puntoventadim->mandante = $row['mandante'];
		$puntoventadim->regionalId = $row['regional_id'];
		$puntoventadim->regionalNom = $row['regional_nom'];
		$puntoventadim->tipopuntoId = $row['tipopunto_id'];
		$puntoventadim->tipopuntoNom = $row['tipopunto_nom'];
		$puntoventadim->tipoestablecimientoId = $row['tipoestablecimiento_id'];
		$puntoventadim->tipoestablecimientoNom = $row['tipoestablecimiento_nom'];
		$puntoventadim->paisId = $row['pais_id'];
		$puntoventadim->paisNom = $row['pais_nom'];
		$puntoventadim->moneda = $row['moneda'];
		$puntoventadim->monedaNom = $row['moneda_nom'];

		return $puntoventadim;
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