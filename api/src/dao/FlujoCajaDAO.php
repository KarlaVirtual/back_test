<?php namespace Backend\dao;
/**
 * Interfaz para el modelo o tabla 'FlujoCaja'.
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface FlujoCajaDAO{

	/**
	 * Obtener el registro condicionado por la 
	 * llave primaria que se pasa como parámetro
	 *
	 * @param String $id llave primaria
	 */
	public function load($id);

	/**
	 * Obtener todos los registros de la base datos
	 */
	public function queryAll();
	
	/**
	 * Obtener todos los registros
	 * ordenadas por el nombre de la columna 
	 * que se pasa como parámetro
	 *
	 * @param String $orderColumn nombre de la columna
	 */
	public function queryAllOrderBy($orderColumn);
	
	/**
 	 * Eliminar todos los registros condicionados
 	 * por la llave primaria
 	 *
 	 * @param String $flujocaja_id llave primaria
 	 */
	public function delete($flujocaja_id);
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param String flujoCaja flujoCaja
 	 */
	public function insert($flujoCaja);
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Object flujoCaja flujoCaja
 	 */
	public function update($flujoCaja);	

	/**
	 * Eliminar todos los registros de la base de datos
	 */
	public function clean();






	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna FechaCrea sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaCrea requerido
 	 */	
	public function queryByFechaCrea($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna HoraCrea sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value HoraCrea requerido
 	 */	
	public function queryByHoraCrea($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna UsucreaId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsucreaId requerido
 	 */	
	public function queryByUsucreaId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna TipomovId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TipomovId requerido
 	 */	
	public function queryByTipomovId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Valor sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Valor requerido
 	 */	
	public function queryByValor($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna TicketId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TicketId requerido
 	 */	
	public function queryByTicketId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Traslado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Traslado requerido
 	 */	
	public function queryByTraslado($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna DoctrasladoId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value DoctrasladoId requerido
 	 */	
	public function queryByDoctrasladoId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna RecargaId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value RecargaId requerido
 	 */	
	public function queryByRecargaId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Formapago1Id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Formapago1Id requerido
 	 */	
	public function queryByFormapago1Id($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Formapago2Id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Formapago2Id requerido
 	 */	
	public function queryByFormapago2Id($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna ValorForma1 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ValorForma1 requerido
 	 */	
	public function queryByValorForma1($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna ValorForma2 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ValorForma2 requerido
 	 */	
	public function queryByValorForma2($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Devolucion sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Devolucion requerido
 	 */	
	public function queryByDevolucion($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna CuentaId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value CuentaId requerido
 	 */	
	public function queryByCuentaId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mandante requerido
 	 */	
	public function queryByMandante($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna PorcenIva sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PorcenIva requerido
 	 */	
	public function queryByPorcenIva($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna ValorIva sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ValorIva requerido
 	 */	
	public function queryByValorIva($value);


    /**
     * Obtener todos los registros donde se encuentre que
     * la columna recarga_agente_id sea igual al valor pasado como parámetro
     *
     * @param String $value recargaAgenteId requerido
     */
    public function queryByCupologId($value);







    /**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna FechaCrea sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaCrea requerido
 	 */	
	public function deleteByFechaCrea($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna HoraCrea sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value HoraCrea requerido
 	 */	
	public function deleteByHoraCrea($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna UsucreaId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsucreaId requerido
 	 */	
	public function deleteByUsucreaId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna TipomovId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TipomovId requerido
 	 */	
	public function deleteByTipomovId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Valor sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Valor requerido
 	 */	
	public function deleteByValor($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna TicketId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TicketId requerido
 	 */	
	public function deleteByTicketId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Traslado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Traslado requerido
 	 */	
	public function deleteByTraslado($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna DoctrasladoId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value DoctrasladoId requerido
 	 */	
	public function deleteByDoctrasladoId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna RecargaId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value RecargaId requerido
 	 */	
	public function deleteByRecargaId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Formapago1Id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Formapago1Id requerido
 	 */	
	public function deleteByFormapago1Id($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Formapago2Id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Formapago2Id requerido
 	 */	
	public function deleteByFormapago2Id($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna ValorForma1 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ValorForma1 requerido
 	 */	
	public function deleteByValorForma1($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna ValorForma2 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ValorForma2 requerido
 	 */	
	public function deleteByValorForma2($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Devolucion sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Devolucion requerido
 	 */	
	public function deleteByDevolucion($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna CuentaId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value CuentaId requerido
 	 */	
	public function deleteByCuentaId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mandante requerido
 	 */	
	public function deleteByMandante($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna PorcenIva sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PorcenIva requerido
 	 */	
	public function deleteByPorcenIva($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna ValorIva sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ValorIva requerido
 	 */	
	public function deleteByValorIva($value);

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna recarga_agente_id sea igual al valor pasado como parámetro
     *
     * @param String $value recargaAgenteId requerido
     */
    public function deleteByCupologId($value);

}
?>