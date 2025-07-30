<?php namespace Backend\dao;
/**
 * Interfaz para el modelo o tabla 'Configuracion'.
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface ConfiguracionDAO{

	/**
	 * Obtener el registro condicionado por la 
	 * llave primaria que se pasa como parámetro
	 *
	 * @param String $id llave primaria
	 */
	public function load($configId, $mandante);

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
 	 * @param String $cargo_id llave primaria
 	 */
	public function delete($configId, $mandante);
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param String cargo_id cargo_id
 	 */
	public function insert($configuracion);
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Object cargo cargo
 	 */
	public function update($configuracion);	

	/**
	 * Eliminar todos los registros de la base de datos
	 */
	public function clean();







	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna UsupadreId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsupadreId requerido
 	 */	
	public function queryByLimiteLineas($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna TicketPie1 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TicketPie1 requerido
 	 */	
	public function queryByTicketPie1($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna TicketPie2 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TicketPie2 requerido
 	 */	
	public function queryByTicketPie2($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna TicketPie2 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TicketPie2 requerido
 	 */	
	public function queryByTicketPie3($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna TicketPie4 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TicketPie4 requerido
 	 */	
	public function queryByTicketPie4($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna TicketPie5 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TicketPie5 requerido
 	 */	
	public function queryByTicketPie5($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna DiasExpira sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value DiasExpira requerido
 	 */	
	public function queryByDiasExpira($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Email1 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Email1 requerido
 	 */	
	public function queryByEmail1($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Email2 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Email2 requerido
 	 */	
	public function queryByEmail2($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Email3 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Email3 requerido
 	 */	
	public function queryByEmail3($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna AccesoPublico sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value AccesoPublico requerido
 	 */	
	public function queryByAccesoPublico($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna TiempoRotaProg sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TiempoRotaProg requerido
 	 */	
	public function queryByTiempoRotaProg($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna LimiteLogro sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value LimiteLogro requerido
 	 */	
	public function queryByLimiteLogro($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna PremioMax sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PremioMax requerido
 	 */	
	public function queryByPremioMax($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna ListaId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ListaId requerido
 	 */	
	public function queryByListaId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna RegaloRegistro sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value RegaloRegistro requerido
 	 */	
	public function queryByRegaloRegistro($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna ResultadoDias sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ResultadoDias requerido
 	 */	
	public function queryByResultadoDias($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna TiempoRotaEtiqueta sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TiempoRotaEtiqueta requerido
 	 */	
	public function queryByTiempoRotaEtiqueta($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna IntercalaEtiqueta1 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value IntercalaEtiqueta1 requerido
 	 */	
	public function queryByIntercalaEtiqueta1($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna IntercalaEtiqueta2 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value IntercalaEtiqueta2 requerido
 	 */	
	public function queryByIntercalaEtiqueta2($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna UsulistabaseId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsulistabaseId requerido
 	 */	
	public function queryByUsulistabaseId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna RecargaPie1 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value RecargaPie1 requerido
 	 */	
	public function queryByRecargaPie1($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna RecargaPie2 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value RecargaPie2 requerido
 	 */	
	public function queryByRecargaPie2($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna RecargaPie3 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value RecargaPie3 requerido
 	 */	
	public function queryByRecargaPie3($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna RecargaPie4 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value RecargaPie4 requerido
 	 */	
	public function queryByRecargaPie4($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna PremioMax1 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PremioMax1 requerido
 	 */	
	public function queryByPremioMax1($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna PremioMax2 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PremioMax2 requerido
 	 */	
	public function queryByPremioMax2($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna PremioMax3 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PremioMax3 requerido
 	 */	
	public function queryByPremioMax3($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna ValorDirecto sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ValorDirecto requerido
 	 */	
	public function queryByValorDirecto($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna MinCaduca sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value MinCaduca requerido
 	 */	
	public function queryByMinCaduca($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna PremioDirecto sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PremioDirecto requerido
 	 */	
	public function queryByPremioDirecto($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna PorcenRegaloRecarga sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PorcenRegaloRecarga requerido
 	 */	
	public function queryByPorcenRegaloRecarga($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna ValorEvento sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ValorEvento requerido
 	 */	
	public function queryByValorEvento($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna ValorDiario sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ValorDiario requerido
 	 */	
	public function queryByValorDiario($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna PremioMaxP sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PremioMaxP requerido
 	 */	
	public function queryByPremioMaxP($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna PremioMax1P sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PremioMax1P requerido
 	 */	
	public function queryByPremioMax1P($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna PremioMax2P sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PremioMax2P requerido
 	 */	
	public function queryByPremioMax2P($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna PremioMax3P sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PremioMax3P requerido
 	 */	
	public function queryByPremioMax3P($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna LimiteLineasP sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value LimiteLineasP requerido
 	 */	
	public function queryByLimiteLineasP($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna ValorDirectoP sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ValorDirectoP requerido
 	 */	
	public function queryByValorDirectoP($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna ValorEventoP sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ValorEventoP requerido
 	 */	
	public function queryByValorEventoP($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna ValorDiarioP sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ValorDiarioP requerido
 	 */	
	public function queryByValorDiarioP($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna PorcenComisio sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PorcenComisio requerido
 	 */	
	public function queryByPorcenComision($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna PorcenComision2 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PorcenComision2 requerido
 	 */	
	public function queryByPorcenComision2($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna ValorCupo sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ValorCupo requerido
 	 */	
	public function queryByValorCupo($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna ValorCupo2 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ValorCupo2 requerido
 	 */	
	public function queryByValorCupo2($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna PeriodoBodega sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PeriodoBodega requerido
 	 */	
	public function queryByPeriodoBodega($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna ProcesoRecarga sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ProcesoRecarga requerido
 	 */	
	public function queryByProcesoRecarga($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna FacturaTexto1 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FacturaTexto1 requerido
 	 */	
	public function queryByFacturaTexto1($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna FacturaTexto2 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FacturaTexto2 requerido
 	 */	
	public function queryByFacturaTexto2($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna FacturaTexto3 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FacturaTexto3 requerido
 	 */	
	public function queryByFacturaTexto3($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna FacturaTexto4 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FacturaTexto4 requerido
 	 */	
	public function queryByFacturaTexto4($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna FacturaTexto5 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FacturaTexto5 requerido
 	 */	
	public function queryByFacturaTexto5($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna FacturaTexto6 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FacturaTexto6 requerido
 	 */	
	public function queryByFacturaTexto6($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna FacturaTexto7 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FacturaTexto7 requerido
 	 */	
	public function queryByFacturaTexto7($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna PedirAnexoDoc sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PedirAnexoDoc requerido
 	 */	
	public function queryByPedirAnexoDoc($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna ContingenciaItainment sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ContingenciaItainment requerido
 	 */	
	public function queryByContingenciaItainment($value);

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
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna LimiteLineas sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value LimiteLineas requerido
 	 */
	public function deleteByLimiteLineas($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna TicketPie1 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TicketPie1 requerido
 	 */
	public function deleteByTicketPie1($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna TicketPie2 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TicketPie2 requerido
 	 */
	public function deleteByTicketPie2($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna TicketPie3 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TicketPie3 requerido
 	 */
	public function deleteByTicketPie3($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna TicketPie4 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TicketPie4 requerido
 	 */
	public function deleteByTicketPie4($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna TicketPie5 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TicketPie5 requerido
 	 */
	public function deleteByTicketPie5($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna DiasExpira sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value DiasExpira requerido
 	 */
	public function deleteByDiasExpira($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Email1 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Email1 requerido
 	 */
	public function deleteByEmail1($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Email2 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Email2 requerido
 	 */
	public function deleteByEmail2($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Email3 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Email3 requerido
 	 */
	public function deleteByEmail3($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna AccesoPublico sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value AccesoPublico requerido
 	 */
	public function deleteByAccesoPublico($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna TiempoRotaProg sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TiempoRotaProg requerido
 	 */
	public function deleteByTiempoRotaProg($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna LimiteLogro sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value LimiteLogro requerido
 	 */
	public function deleteByLimiteLogro($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna PremioMax sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PremioMax requerido
 	 */
	public function deleteByPremioMax($value);
	
	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna ListaId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mandante requerido
 	 */
	public function deleteByListaId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna RegaloRegistro sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value RegaloRegistro requerido
 	 */
	public function deleteByRegaloRegistro($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna ResultadoDias sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ResultadoDias requerido
 	 */
	public function deleteByResultadoDias($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna TiempoRotaEtiqueta sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TiempoRotaEtiqueta requerido
 	 */
	public function deleteByTiempoRotaEtiqueta($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna IntercalaEtiqueta1 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value IntercalaEtiqueta1 requerido
 	 */
	public function deleteByIntercalaEtiqueta1($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna IntercalaEtiqueta2 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value IntercalaEtiqueta2 requerido
 	 */
	public function deleteByIntercalaEtiqueta2($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna UsulistabaseId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsulistabaseId requerido
 	 */
	public function deleteByUsulistabaseId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna RecargaPie1 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value RecargaPie1 requerido
 	 */
	public function deleteByRecargaPie1($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna RecargaPie2 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value RecargaPie2 requerido
 	 */
	public function deleteByRecargaPie2($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna RecargaPie3 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value RecargaPie3 requerido
 	 */
	public function deleteByRecargaPie3($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna RecargaPie4 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value RecargaPie4 requerido
 	 */
	public function deleteByRecargaPie4($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna PremioMax1 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PremioMax1 requerido
 	 */
	public function deleteByPremioMax1($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna PremioMax2 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PremioMax2 requerido
 	 */
	public function deleteByPremioMax2($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna PremioMax3 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PremioMax3 requerido
 	 */
	public function deleteByPremioMax3($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna ValorDirecto sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ValorDirecto requerido
 	 */
	public function deleteByValorDirecto($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna MinCaduca sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value MinCaduca requerido
 	 */
	public function deleteByMinCaduca($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna PremioDirecto sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PremioDirecto requerido
 	 */
	public function deleteByPremioDirecto($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna PorcenRegaloRecarga sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PorcenRegaloRecarga requerido
 	 */
	public function deleteByPorcenRegaloRecarga($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna ValorEvento sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ValorEvento requerido
 	 */
	public function deleteByValorEvento($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna ValorDiario sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ValorDiario requerido
 	 */
	public function deleteByValorDiario($value);
	
	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna PremioMaxP sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PremioMaxP requerido
 	 */
	public function deleteByPremioMaxP($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna PremioMax1P sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PremioMax1P requerido
 	 */
	public function deleteByPremioMax1P($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna PremioMax3P sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PremioMax3P requerido
 	 */
	public function deleteByPremioMax2P($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna PremioMax3P sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PremioMax3P requerido
 	 */
	public function deleteByPremioMax3P($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna LimiteLineasP sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value LimiteLineasP requerido
 	 */
	public function deleteByLimiteLineasP($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna ValorDirectoP sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ValorDirectoP requerido
 	 */
	public function deleteByValorDirectoP($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna ValorEventoP sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ValorEventoP requerido
 	 */
	public function deleteByValorEventoP($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna ValorDiarioP sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ValorDiarioP requerido
 	 */
	public function deleteByValorDiarioP($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna PorcenComision sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PorcenComision requerido
 	 */
	public function deleteByPorcenComision($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna PorcenComision2 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PorcenComision2 requerido
 	 */
	public function deleteByPorcenComision2($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna ValorCupo sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ValorCupo requerido
 	 */
	public function deleteByValorCupo($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna ValorCupo2 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ValorCupo2 requerido
 	 */
	public function deleteByValorCupo2($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna PeriodoBodega sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PeriodoBodega requerido
 	 */
	public function deleteByPeriodoBodega($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna ProcesoRecarga sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ProcesoRecarga requerido
 	 */
	public function deleteByProcesoRecarga($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna FacturaTexto1 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FacturaTexto1 requerido
 	 */
	public function deleteByFacturaTexto1($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna FacturaTexto2 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FacturaTexto2 requerido
 	 */
	public function deleteByFacturaTexto2($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna FacturaTexto3 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FacturaTexto3 requerido
 	 */
	public function deleteByFacturaTexto3($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna FacturaTexto4 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FacturaTexto4 requerido
 	 */
	public function deleteByFacturaTexto4($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna FacturaTexto5 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FacturaTexto5 requerido
 	 */
	public function deleteByFacturaTexto5($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna FacturaTexto5 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FacturaTexto5 requerido
 	 */
	public function deleteByFacturaTexto6($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna FacturaTexto7 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FacturaTexto7 requerido
 	 */
	public function deleteByFacturaTexto7($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna PedirAnexoDoc sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PedirAnexoDoc requerido
 	 */
	public function deleteByPedirAnexoDoc($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna ContingenciaItainment sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ContingenciaItainment requerido
 	 */
	public function deleteByContingenciaItainment($value);

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


}
?>