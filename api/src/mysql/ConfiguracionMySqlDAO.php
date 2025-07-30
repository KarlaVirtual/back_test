<?php namespace Backend\mysql;
/** 
* Clase 'ConfiguracionMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'Configuracion'
* 
* Ejemplo de uso: 
* $ConfiguracionMySqlDAO = new ConfiguracionMySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class ConfiguracionMySqlDAO implements ConfiguracionDAO{

    /**
     * Obtener el registro condicionado por la 
     * llave primaria y el mandante 
     * ambos pasados como parámetro
     *
     * @param String $configId llave primaria
     * @param String $mandante mandante 
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function load($configId, $mandante){
		$sql = 'SELECT * FROM configuracion WHERE config_id = ?  AND mandante = ? ';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($configId);
		$sqlQuery->setNumber($mandante);

		return $this->getRow($sqlQuery);
	}

    /**
     * Obtener todos los registros de la base datos
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryAll(){
		$sql = 'SELECT * FROM configuracion';
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
		$sql = 'SELECT * FROM configuracion ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria y el mandante
     *
     * @param String $configId llave primaria
     * @param String $mandante mandante
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function delete($configId, $mandante){
		$sql = 'DELETE FROM configuracion WHERE config_id = ?  AND mandante = ? ';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($configId);
		$sqlQuery->setNumber($mandante);

		return $this->executeUpdate($sqlQuery);
	}
	
    /**
     * Insertar un registro en la base de datos
     *
     * @param Object configuracion configuracion
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($configuracion){
		$sql = 'INSERT INTO configuracion (limite_lineas, ticket_pie1, ticket_pie2, ticket_pie3, ticket_pie4, ticket_pie5, dias_expira, email1, email2, email3, acceso_publico, tiempo_rota_prog, limite_logro, premio_max, lista_id, regalo_registro, resultado_dias, tiempo_rota_etiqueta, intercala_etiqueta_1, intercala_etiqueta_2, usulistabase_id, recarga_pie1, recarga_pie2, recarga_pie3, recarga_pie4, premio_max1, premio_max2, premio_max3, valor_directo, min_caduca, premio_directo, porcen_regalo_recarga, valor_evento, valor_diario, premio_max_p, premio_max1_p, premio_max2_p, premio_max3_p, limite_lineas_p, valor_directo_p, valor_evento_p, valor_diario_p, porcen_comision, porcen_comision2, valor_cupo, valor_cupo2, periodo_bodega, proceso_recarga, factura_texto1, factura_texto2, factura_texto3, factura_texto4, factura_texto5, factura_texto6, factura_texto7, pedir_anexo_doc, contingencia_itainment, porcen_iva, valor_iva, config_id, mandante) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($configuracion->limiteLineas);
		$sqlQuery->set($configuracion->ticketPie1);
		$sqlQuery->set($configuracion->ticketPie2);
		$sqlQuery->set($configuracion->ticketPie3);
		$sqlQuery->set($configuracion->ticketPie4);
		$sqlQuery->set($configuracion->ticketPie5);
		$sqlQuery->set($configuracion->diasExpira);
		$sqlQuery->set($configuracion->email1);
		$sqlQuery->set($configuracion->email2);
		$sqlQuery->set($configuracion->email3);
		$sqlQuery->set($configuracion->accesoPublico);
		$sqlQuery->set($configuracion->tiempoRotaProg);
		$sqlQuery->set($configuracion->limiteLogro);
		$sqlQuery->set($configuracion->premioMax);
		$sqlQuery->set($configuracion->listaId);
		$sqlQuery->set($configuracion->regaloRegistro);
		$sqlQuery->set($configuracion->resultadoDias);
		$sqlQuery->set($configuracion->tiempoRotaEtiqueta);
		$sqlQuery->set($configuracion->intercalaEtiqueta1);
		$sqlQuery->set($configuracion->intercalaEtiqueta2);
		$sqlQuery->set($configuracion->usulistabaseId);
		$sqlQuery->set($configuracion->recargaPie1);
		$sqlQuery->set($configuracion->recargaPie2);
		$sqlQuery->set($configuracion->recargaPie3);
		$sqlQuery->set($configuracion->recargaPie4);
		$sqlQuery->set($configuracion->premioMax1);
		$sqlQuery->set($configuracion->premioMax2);
		$sqlQuery->set($configuracion->premioMax3);
		$sqlQuery->set($configuracion->valorDirecto);
		$sqlQuery->set($configuracion->minCaduca);
		$sqlQuery->set($configuracion->premioDirecto);
		$sqlQuery->set($configuracion->porcenRegaloRecarga);
		$sqlQuery->set($configuracion->valorEvento);
		$sqlQuery->set($configuracion->valorDiario);
		$sqlQuery->set($configuracion->premioMaxP);
		$sqlQuery->set($configuracion->premioMax1P);
		$sqlQuery->set($configuracion->premioMax2P);
		$sqlQuery->set($configuracion->premioMax3P);
		$sqlQuery->set($configuracion->limiteLineasP);
		$sqlQuery->set($configuracion->valorDirectoP);
		$sqlQuery->set($configuracion->valorEventoP);
		$sqlQuery->set($configuracion->valorDiarioP);
		$sqlQuery->set($configuracion->porcenComision);
		$sqlQuery->set($configuracion->porcenComision2);
		$sqlQuery->set($configuracion->valorCupo);
		$sqlQuery->set($configuracion->valorCupo2);
		$sqlQuery->set($configuracion->periodoBodega);
		$sqlQuery->set($configuracion->procesoRecarga);
		$sqlQuery->set($configuracion->facturaTexto1);
		$sqlQuery->set($configuracion->facturaTexto2);
		$sqlQuery->set($configuracion->facturaTexto3);
		$sqlQuery->set($configuracion->facturaTexto4);
		$sqlQuery->set($configuracion->facturaTexto5);
		$sqlQuery->set($configuracion->facturaTexto6);
		$sqlQuery->set($configuracion->facturaTexto7);
		$sqlQuery->set($configuracion->pedirAnexoDoc);
		$sqlQuery->set($configuracion->contingenciaItainment);
		$sqlQuery->set($configuracion->porcenIva);
		$sqlQuery->set($configuracion->valorIva);

		
		$sqlQuery->setNumber($configuracion->configId);

		$sqlQuery->setNumber($configuracion->mandante);

		$this->executeInsert($sqlQuery);	
		//$configuracion->id = $id;
		//return $id;
	}
	
    /**
     * Editar un registro en la base de datos
     *
     * @param Object configuracion configuracion
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function update($configuracion){
		$sql = 'UPDATE configuracion SET limite_lineas = ?, ticket_pie1 = ?, ticket_pie2 = ?, ticket_pie3 = ?, ticket_pie4 = ?, ticket_pie5 = ?, dias_expira = ?, email1 = ?, email2 = ?, email3 = ?, acceso_publico = ?, tiempo_rota_prog = ?, limite_logro = ?, premio_max = ?, lista_id = ?, regalo_registro = ?, resultado_dias = ?, tiempo_rota_etiqueta = ?, intercala_etiqueta_1 = ?, intercala_etiqueta_2 = ?, usulistabase_id = ?, recarga_pie1 = ?, recarga_pie2 = ?, recarga_pie3 = ?, recarga_pie4 = ?, premio_max1 = ?, premio_max2 = ?, premio_max3 = ?, valor_directo = ?, min_caduca = ?, premio_directo = ?, porcen_regalo_recarga = ?, valor_evento = ?, valor_diario = ?, premio_max_p = ?, premio_max1_p = ?, premio_max2_p = ?, premio_max3_p = ?, limite_lineas_p = ?, valor_directo_p = ?, valor_evento_p = ?, valor_diario_p = ?, porcen_comision = ?, porcen_comision2 = ?, valor_cupo = ?, valor_cupo2 = ?, periodo_bodega = ?, proceso_recarga = ?, factura_texto1 = ?, factura_texto2 = ?, factura_texto3 = ?, factura_texto4 = ?, factura_texto5 = ?, factura_texto6 = ?, factura_texto7 = ?, pedir_anexo_doc = ?, contingencia_itainment = ?, porcen_iva = ?, valor_iva = ? WHERE config_id = ?  AND mandante = ? ';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($configuracion->limiteLineas);
		$sqlQuery->set($configuracion->ticketPie1);
		$sqlQuery->set($configuracion->ticketPie2);
		$sqlQuery->set($configuracion->ticketPie3);
		$sqlQuery->set($configuracion->ticketPie4);
		$sqlQuery->set($configuracion->ticketPie5);
		$sqlQuery->set($configuracion->diasExpira);
		$sqlQuery->set($configuracion->email1);
		$sqlQuery->set($configuracion->email2);
		$sqlQuery->set($configuracion->email3);
		$sqlQuery->set($configuracion->accesoPublico);
		$sqlQuery->set($configuracion->tiempoRotaProg);
		$sqlQuery->set($configuracion->limiteLogro);
		$sqlQuery->set($configuracion->premioMax);
		$sqlQuery->set($configuracion->listaId);
		$sqlQuery->set($configuracion->regaloRegistro);
		$sqlQuery->set($configuracion->resultadoDias);
		$sqlQuery->set($configuracion->tiempoRotaEtiqueta);
		$sqlQuery->set($configuracion->intercalaEtiqueta1);
		$sqlQuery->set($configuracion->intercalaEtiqueta2);
		$sqlQuery->set($configuracion->usulistabaseId);
		$sqlQuery->set($configuracion->recargaPie1);
		$sqlQuery->set($configuracion->recargaPie2);
		$sqlQuery->set($configuracion->recargaPie3);
		$sqlQuery->set($configuracion->recargaPie4);
		$sqlQuery->set($configuracion->premioMax1);
		$sqlQuery->set($configuracion->premioMax2);
		$sqlQuery->set($configuracion->premioMax3);
		$sqlQuery->set($configuracion->valorDirecto);
		$sqlQuery->set($configuracion->minCaduca);
		$sqlQuery->set($configuracion->premioDirecto);
		$sqlQuery->set($configuracion->porcenRegaloRecarga);
		$sqlQuery->set($configuracion->valorEvento);
		$sqlQuery->set($configuracion->valorDiario);
		$sqlQuery->set($configuracion->premioMaxP);
		$sqlQuery->set($configuracion->premioMax1P);
		$sqlQuery->set($configuracion->premioMax2P);
		$sqlQuery->set($configuracion->premioMax3P);
		$sqlQuery->set($configuracion->limiteLineasP);
		$sqlQuery->set($configuracion->valorDirectoP);
		$sqlQuery->set($configuracion->valorEventoP);
		$sqlQuery->set($configuracion->valorDiarioP);
		$sqlQuery->set($configuracion->porcenComision);
		$sqlQuery->set($configuracion->porcenComision2);
		$sqlQuery->set($configuracion->valorCupo);
		$sqlQuery->set($configuracion->valorCupo2);
		$sqlQuery->set($configuracion->periodoBodega);
		$sqlQuery->set($configuracion->procesoRecarga);
		$sqlQuery->set($configuracion->facturaTexto1);
		$sqlQuery->set($configuracion->facturaTexto2);
		$sqlQuery->set($configuracion->facturaTexto3);
		$sqlQuery->set($configuracion->facturaTexto4);
		$sqlQuery->set($configuracion->facturaTexto5);
		$sqlQuery->set($configuracion->facturaTexto6);
		$sqlQuery->set($configuracion->facturaTexto7);
		$sqlQuery->set($configuracion->pedirAnexoDoc);
		$sqlQuery->set($configuracion->contingenciaItainment);
		$sqlQuery->set($configuracion->porcenIva);
		$sqlQuery->set($configuracion->valorIva);

		
		$sqlQuery->setNumber($configuracion->configId);

		$sqlQuery->setNumber($configuracion->mandante);

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
		$sql = 'DELETE FROM configuracion';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}







    /**
     * Obtener todos los registros donde se encuentre que
     * la columna limite_lineas sea igual al valor pasado como parámetro
     *
     * @param String $value limite_lineas requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function queryByLimiteLineas($value){
		$sql = 'SELECT * FROM configuracion WHERE limite_lineas = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna ticket_pie1 sea igual al valor pasado como parámetro
     *
     * @param String $value ticket_pie1 requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function queryByTicketPie1($value){
		$sql = 'SELECT * FROM configuracion WHERE ticket_pie1 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna ticket_pie2 sea igual al valor pasado como parámetro
     *
     * @param String $value ticket_pie2 requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function queryByTicketPie2($value){
		$sql = 'SELECT * FROM configuracion WHERE ticket_pie2 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna ticket_pie3 sea igual al valor pasado como parámetro
     *
     * @param String $value ticket_pie3 requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function queryByTicketPie3($value){
		$sql = 'SELECT * FROM configuracion WHERE ticket_pie3 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna ticket_pie4 sea igual al valor pasado como parámetro
     *
     * @param String $value ticket_pie4 requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function queryByTicketPie4($value){
		$sql = 'SELECT * FROM configuracion WHERE ticket_pie4 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna ticket_pie5 sea igual al valor pasado como parámetro
     *
     * @param String $value ticket_pie5 requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByTicketPie5($value){
		$sql = 'SELECT * FROM configuracion WHERE ticket_pie5 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna dias_expira sea igual al valor pasado como parámetro
     *
     * @param String $value dias_expira requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByDiasExpira($value){
		$sql = 'SELECT * FROM configuracion WHERE dias_expira = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna email1 sea igual al valor pasado como parámetro
     *
     * @param String $value email1 requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByEmail1($value){
		$sql = 'SELECT * FROM configuracion WHERE email1 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna email2 sea igual al valor pasado como parámetro
     *
     * @param String $value email2 requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByEmail2($value){
		$sql = 'SELECT * FROM configuracion WHERE email2 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna email3 sea igual al valor pasado como parámetro
     *
     * @param String $value email3 requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByEmail3($value){
		$sql = 'SELECT * FROM configuracion WHERE email3 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna acceso_publico sea igual al valor pasado como parámetro
     *
     * @param String $value acceso_publico requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByAccesoPublico($value){
		$sql = 'SELECT * FROM configuracion WHERE acceso_publico = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna tiempo_rota_prog sea igual al valor pasado como parámetro
     *
     * @param String $value tiempo_rota_prog requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByTiempoRotaProg($value){
		$sql = 'SELECT * FROM configuracion WHERE tiempo_rota_prog = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna limite_logro sea igual al valor pasado como parámetro
     *
     * @param String $value limite_logro requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByLimiteLogro($value){
		$sql = 'SELECT * FROM configuracion WHERE limite_logro = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna premio_max sea igual al valor pasado como parámetro
     *
     * @param String $value premio_max requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByPremioMax($value){
		$sql = 'SELECT * FROM configuracion WHERE premio_max = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna lista_id sea igual al valor pasado como parámetro
     *
     * @param String $value lista_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByListaId($value){
		$sql = 'SELECT * FROM configuracion WHERE lista_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna regalo_registro sea igual al valor pasado como parámetro
     *
     * @param String $value regalo_registro requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByRegaloRegistro($value){
		$sql = 'SELECT * FROM configuracion WHERE regalo_registro = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna resultado_dias sea igual al valor pasado como parámetro
     *
     * @param String $value resultado_dias requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByResultadoDias($value){
		$sql = 'SELECT * FROM configuracion WHERE resultado_dias = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna tiempo_rota_etiqueta sea igual al valor pasado como parámetro
     *
     * @param String $value tiempo_rota_etiqueta requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByTiempoRotaEtiqueta($value){
		$sql = 'SELECT * FROM configuracion WHERE tiempo_rota_etiqueta = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna intercala_etiqueta_1 sea igual al valor pasado como parámetro
     *
     * @param String $value intercala_etiqueta_1 requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByIntercalaEtiqueta1($value){
		$sql = 'SELECT * FROM configuracion WHERE intercala_etiqueta_1 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna intercala_etiqueta_2 sea igual al valor pasado como parámetro
     *
     * @param String $value intercala_etiqueta_2 requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByIntercalaEtiqueta2($value){
		$sql = 'SELECT * FROM configuracion WHERE intercala_etiqueta_2 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna usulistabase_id sea igual al valor pasado como parámetro
     *
     * @param String $value usulistabase_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByUsulistabaseId($value){
		$sql = 'SELECT * FROM configuracion WHERE usulistabase_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna recarga_pie1 sea igual al valor pasado como parámetro
     *
     * @param String $value recarga_pie1 requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByRecargaPie1($value){
		$sql = 'SELECT * FROM configuracion WHERE recarga_pie1 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna recarga_pie2 sea igual al valor pasado como parámetro
     *
     * @param String $value recarga_pie2 requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByRecargaPie2($value){
		$sql = 'SELECT * FROM configuracion WHERE recarga_pie2 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna recarga_pie3 sea igual al valor pasado como parámetro
     *
     * @param String $value recarga_pie3 requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByRecargaPie3($value){
		$sql = 'SELECT * FROM configuracion WHERE recarga_pie3 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna recarga_pie4 sea igual al valor pasado como parámetro
     *
     * @param String $value recarga_pie4 requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByRecargaPie4($value){
		$sql = 'SELECT * FROM configuracion WHERE recarga_pie4 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna premio_max1 sea igual al valor pasado como parámetro
     *
     * @param String $value premio_max1 requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByPremioMax1($value){
		$sql = 'SELECT * FROM configuracion WHERE premio_max1 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna premio_max2 sea igual al valor pasado como parámetro
     *
     * @param String $value premio_max2 requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByPremioMax2($value){
		$sql = 'SELECT * FROM configuracion WHERE premio_max2 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna premio_max3 sea igual al valor pasado como parámetro
     *
     * @param String $value premio_max3 requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByPremioMax3($value){
		$sql = 'SELECT * FROM configuracion WHERE premio_max3 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna valor_directo sea igual al valor pasado como parámetro
     *
     * @param String $value valor_directo requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByValorDirecto($value){
		$sql = 'SELECT * FROM configuracion WHERE valor_directo = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna min_caduca sea igual al valor pasado como parámetro
     *
     * @param String $value min_caduca requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByMinCaduca($value){
		$sql = 'SELECT * FROM configuracion WHERE min_caduca = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna premio_directo sea igual al valor pasado como parámetro
     *
     * @param String $value premio_directo requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByPremioDirecto($value){
		$sql = 'SELECT * FROM configuracion WHERE premio_directo = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna porcen_regalo_recarga sea igual al valor pasado como parámetro
     *
     * @param String $value porcen_regalo_recarga requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByPorcenRegaloRecarga($value){
		$sql = 'SELECT * FROM configuracion WHERE porcen_regalo_recarga = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna valor_evento sea igual al valor pasado como parámetro
     *
     * @param String $value valor_evento requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByValorEvento($value){
		$sql = 'SELECT * FROM configuracion WHERE valor_evento = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna valor_diario sea igual al valor pasado como parámetro
     *
     * @param String $value valor_diario requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByValorDiario($value){
		$sql = 'SELECT * FROM configuracion WHERE valor_diario = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna premio_max_p sea igual al valor pasado como parámetro
     *
     * @param String $value premio_max_p requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByPremioMaxP($value){
		$sql = 'SELECT * FROM configuracion WHERE premio_max_p = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna premio_max1_p sea igual al valor pasado como parámetro
     *
     * @param String $value premio_max1_p requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByPremioMax1P($value){
		$sql = 'SELECT * FROM configuracion WHERE premio_max1_p = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna premio_max2_p sea igual al valor pasado como parámetro
     *
     * @param String $value premio_max2_p requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByPremioMax2P($value){
		$sql = 'SELECT * FROM configuracion WHERE premio_max2_p = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna premio_max3_p sea igual al valor pasado como parámetro
     *
     * @param String $value premio_max3_p requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByPremioMax3P($value){
		$sql = 'SELECT * FROM configuracion WHERE premio_max3_p = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna limite_lineas_p sea igual al valor pasado como parámetro
     *
     * @param String $value limite_lineas_p requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByLimiteLineasP($value){
		$sql = 'SELECT * FROM configuracion WHERE limite_lineas_p = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna valor_directo_p sea igual al valor pasado como parámetro
     *
     * @param String $value valor_directo_p requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByValorDirectoP($value){
		$sql = 'SELECT * FROM configuracion WHERE valor_directo_p = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna valor_evento_p sea igual al valor pasado como parámetro
     *
     * @param String $value valor_evento_p requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByValorEventoP($value){
		$sql = 'SELECT * FROM configuracion WHERE valor_evento_p = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna valor_diario_p sea igual al valor pasado como parámetro
     *
     * @param String $value valor_diario_p requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByValorDiarioP($value){
		$sql = 'SELECT * FROM configuracion WHERE valor_diario_p = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna porcen_comision sea igual al valor pasado como parámetro
     *
     * @param String $value porcen_comision requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByPorcenComision($value){
		$sql = 'SELECT * FROM configuracion WHERE porcen_comision = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna porcen_comision2 sea igual al valor pasado como parámetro
     *
     * @param String $value porcen_comision2 requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByPorcenComision2($value){
		$sql = 'SELECT * FROM configuracion WHERE porcen_comision2 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna valor_cupo sea igual al valor pasado como parámetro
     *
     * @param String $value valor_cupo requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByValorCupo($value){
		$sql = 'SELECT * FROM configuracion WHERE valor_cupo = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna valor_cupo2 sea igual al valor pasado como parámetro
     *
     * @param String $value valor_cupo2 requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByValorCupo2($value){
		$sql = 'SELECT * FROM configuracion WHERE valor_cupo2 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna periodo_bodega sea igual al valor pasado como parámetro
     *
     * @param String $value periodo_bodega requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByPeriodoBodega($value){
		$sql = 'SELECT * FROM configuracion WHERE periodo_bodega = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna proceso_recarga sea igual al valor pasado como parámetro
     *
     * @param String $value proceso_recarga requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByProcesoRecarga($value){
		$sql = 'SELECT * FROM configuracion WHERE proceso_recarga = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna factura_texto1 sea igual al valor pasado como parámetro
     *
     * @param String $value factura_texto1 requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByFacturaTexto1($value){
		$sql = 'SELECT * FROM configuracion WHERE factura_texto1 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna factura_texto2 sea igual al valor pasado como parámetro
     *
     * @param String $value factura_texto2 requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByFacturaTexto2($value){
		$sql = 'SELECT * FROM configuracion WHERE factura_texto2 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna factura_texto3 sea igual al valor pasado como parámetro
     *
     * @param String $value factura_texto3 requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByFacturaTexto3($value){
		$sql = 'SELECT * FROM configuracion WHERE factura_texto3 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna factura_texto4 sea igual al valor pasado como parámetro
     *
     * @param String $value factura_texto4 requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByFacturaTexto4($value){
		$sql = 'SELECT * FROM configuracion WHERE factura_texto4 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna factura_texto5 sea igual al valor pasado como parámetro
     *
     * @param String $value factura_texto5 requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByFacturaTexto5($value){
		$sql = 'SELECT * FROM configuracion WHERE factura_texto5 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna factura_texto6 sea igual al valor pasado como parámetro
     *
     * @param String $value factura_texto6 requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByFacturaTexto6($value){
		$sql = 'SELECT * FROM configuracion WHERE factura_texto6 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna factura_texto7 sea igual al valor pasado como parámetro
     *
     * @param String $value factura_texto7 requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByFacturaTexto7($value){
		$sql = 'SELECT * FROM configuracion WHERE factura_texto7 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna pedir_anexo_doc sea igual al valor pasado como parámetro
     *
     * @param String $value pedir_anexo_doc requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByPedirAnexoDoc($value){
		$sql = 'SELECT * FROM configuracion WHERE pedir_anexo_doc = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna contingencia_itainment sea igual al valor pasado como parámetro
     *
     * @param String $value contingencia_itainment requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByContingenciaItainment($value){
		$sql = 'SELECT * FROM configuracion WHERE contingencia_itainment = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna porcen_iva sea igual al valor pasado como parámetro
     *
     * @param String $value porcen_iva requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByPorcenIva($value){
		$sql = 'SELECT * FROM configuracion WHERE porcen_iva = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna valor_iva sea igual al valor pasado como parámetro
     *
     * @param String $value valor_iva requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByValorIva($value){
		$sql = 'SELECT * FROM configuracion WHERE valor_iva = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}







    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna limite_lineas sea igual al valor pasado como parámetro
     *
     * @param String $value limite_lineas requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByLimiteLineas($value){
		$sql = 'DELETE FROM configuracion WHERE limite_lineas = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna ticket_pie1 sea igual al valor pasado como parámetro
     *
     * @param String $value ticket_pie1 requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByTicketPie1($value){
		$sql = 'DELETE FROM configuracion WHERE ticket_pie1 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna ticket_pie2 sea igual al valor pasado como parámetro
     *
     * @param String $value ticket_pie2 requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByTicketPie2($value){
		$sql = 'DELETE FROM configuracion WHERE ticket_pie2 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna ticket_pie3 sea igual al valor pasado como parámetro
     *
     * @param String $value ticket_pie3 requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByTicketPie3($value){
		$sql = 'DELETE FROM configuracion WHERE ticket_pie3 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna ticket_pie4 sea igual al valor pasado como parámetro
     *
     * @param String $value ticket_pie4 requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByTicketPie4($value){
		$sql = 'DELETE FROM configuracion WHERE ticket_pie4 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna ticket_pie5 sea igual al valor pasado como parámetro
     *
     * @param String $value ticket_pie5 requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByTicketPie5($value){
		$sql = 'DELETE FROM configuracion WHERE ticket_pie5 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna dias_expira sea igual al valor pasado como parámetro
     *
     * @param String $value dias_expira requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByDiasExpira($value){
		$sql = 'DELETE FROM configuracion WHERE dias_expira = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna email1 sea igual al valor pasado como parámetro
     *
     * @param String $value email1 requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByEmail1($value){
		$sql = 'DELETE FROM configuracion WHERE email1 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna email2 sea igual al valor pasado como parámetro
     *
     * @param String $value email2 requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByEmail2($value){
		$sql = 'DELETE FROM configuracion WHERE email2 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna email3 sea igual al valor pasado como parámetro
     *
     * @param String $value email3 requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByEmail3($value){
		$sql = 'DELETE FROM configuracion WHERE email3 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna acceso_publico sea igual al valor pasado como parámetro
     *
     * @param String $value acceso_publico requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByAccesoPublico($value){
		$sql = 'DELETE FROM configuracion WHERE acceso_publico = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna tiempo_rota_prog sea igual al valor pasado como parámetro
     *
     * @param String $value tiempo_rota_prog requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByTiempoRotaProg($value){
		$sql = 'DELETE FROM configuracion WHERE tiempo_rota_prog = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna limite_logro sea igual al valor pasado como parámetro
     *
     * @param String $value limite_logro requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByLimiteLogro($value){
		$sql = 'DELETE FROM configuracion WHERE limite_logro = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna premio_max sea igual al valor pasado como parámetro
     *
     * @param String $value premio_max requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByPremioMax($value){
		$sql = 'DELETE FROM configuracion WHERE premio_max = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna lista_id sea igual al valor pasado como parámetro
     *
     * @param String $value lista_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByListaId($value){
		$sql = 'DELETE FROM configuracion WHERE lista_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna regalo_registro sea igual al valor pasado como parámetro
     *
     * @param String $value regalo_registro requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByRegaloRegistro($value){
		$sql = 'DELETE FROM configuracion WHERE regalo_registro = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna resultado_dias sea igual al valor pasado como parámetro
     *
     * @param String $value resultado_dias requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByResultadoDias($value){
		$sql = 'DELETE FROM configuracion WHERE resultado_dias = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna tiempo_rota_etiqueta sea igual al valor pasado como parámetro
     *
     * @param String $value tiempo_rota_etiqueta requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByTiempoRotaEtiqueta($value){
		$sql = 'DELETE FROM configuracion WHERE tiempo_rota_etiqueta = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna intercala_etiqueta_1 sea igual al valor pasado como parámetro
     *
     * @param String $value intercala_etiqueta_1 requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByIntercalaEtiqueta1($value){
		$sql = 'DELETE FROM configuracion WHERE intercala_etiqueta_1 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna intercala_etiqueta_2 sea igual al valor pasado como parámetro
     *
     * @param String $value intercala_etiqueta_2 requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByIntercalaEtiqueta2($value){
		$sql = 'DELETE FROM configuracion WHERE intercala_etiqueta_2 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna usulistabase_id sea igual al valor pasado como parámetro
     *
     * @param String $value usulistabase_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByUsulistabaseId($value){
		$sql = 'DELETE FROM configuracion WHERE usulistabase_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna recarga_pie1 sea igual al valor pasado como parámetro
     *
     * @param String $value recarga_pie1 requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByRecargaPie1($value){
		$sql = 'DELETE FROM configuracion WHERE recarga_pie1 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna recarga_pie2 sea igual al valor pasado como parámetro
     *
     * @param String $value recarga_pie2 requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByRecargaPie2($value){
		$sql = 'DELETE FROM configuracion WHERE recarga_pie2 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna recarga_pie3 sea igual al valor pasado como parámetro
     *
     * @param String $value recarga_pie3 requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByRecargaPie3($value){
		$sql = 'DELETE FROM configuracion WHERE recarga_pie3 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna recarga_pie4 sea igual al valor pasado como parámetro
     *
     * @param String $value recarga_pie4 requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByRecargaPie4($value){
		$sql = 'DELETE FROM configuracion WHERE recarga_pie4 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna premio_max1 sea igual al valor pasado como parámetro
     *
     * @param String $value premio_max1 requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByPremioMax1($value){
		$sql = 'DELETE FROM configuracion WHERE premio_max1 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna premio_max2 sea igual al valor pasado como parámetro
     *
     * @param String $value premio_max2 requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByPremioMax2($value){
		$sql = 'DELETE FROM configuracion WHERE premio_max2 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna premio_max3 sea igual al valor pasado como parámetro
     *
     * @param String $value premio_max3 requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByPremioMax3($value){
		$sql = 'DELETE FROM configuracion WHERE premio_max3 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna valor_directo sea igual al valor pasado como parámetro
     *
     * @param String $value valor_directo requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByValorDirecto($value){
		$sql = 'DELETE FROM configuracion WHERE valor_directo = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna min_caduca sea igual al valor pasado como parámetro
     *
     * @param String $value min_caduca requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByMinCaduca($value){
		$sql = 'DELETE FROM configuracion WHERE min_caduca = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna premio_directo sea igual al valor pasado como parámetro
     *
     * @param String $value premio_directo requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByPremioDirecto($value){
		$sql = 'DELETE FROM configuracion WHERE premio_directo = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna porcen_regalo_recarga sea igual al valor pasado como parámetro
     *
     * @param String $value porcen_regalo_recarga requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByPorcenRegaloRecarga($value){
		$sql = 'DELETE FROM configuracion WHERE porcen_regalo_recarga = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna valor_evento sea igual al valor pasado como parámetro
     *
     * @param String $value valor_evento requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByValorEvento($value){
		$sql = 'DELETE FROM configuracion WHERE valor_evento = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna valor_diario sea igual al valor pasado como parámetro
     *
     * @param String $value valor_diario requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByValorDiario($value){
		$sql = 'DELETE FROM configuracion WHERE valor_diario = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna premio_max_p sea igual al valor pasado como parámetro
     *
     * @param String $value premio_max_p requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByPremioMaxP($value){
		$sql = 'DELETE FROM configuracion WHERE premio_max_p = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna premio_max1_p sea igual al valor pasado como parámetro
     *
     * @param String $value premio_max1_p requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByPremioMax1P($value){
		$sql = 'DELETE FROM configuracion WHERE premio_max1_p = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna premio_max2_p sea igual al valor pasado como parámetro
     *
     * @param String $value premio_max2_p requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByPremioMax2P($value){
		$sql = 'DELETE FROM configuracion WHERE premio_max2_p = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna premio_max3_p sea igual al valor pasado como parámetro
     *
     * @param String $value premio_max3_p requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByPremioMax3P($value){
		$sql = 'DELETE FROM configuracion WHERE premio_max3_p = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna limite_lineas_p sea igual al valor pasado como parámetro
     *
     * @param String $value limite_lineas_p requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByLimiteLineasP($value){
		$sql = 'DELETE FROM configuracion WHERE limite_lineas_p = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna valor_directo_p sea igual al valor pasado como parámetro
     *
     * @param String $value valor_directo_p requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByValorDirectoP($value){
		$sql = 'DELETE FROM configuracion WHERE valor_directo_p = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna valor_evento_p sea igual al valor pasado como parámetro
     *
     * @param String $value valor_evento_p requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByValorEventoP($value){
		$sql = 'DELETE FROM configuracion WHERE valor_evento_p = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna valor_diario_p sea igual al valor pasado como parámetro
     *
     * @param String $value valor_diario_p requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByValorDiarioP($value){
		$sql = 'DELETE FROM configuracion WHERE valor_diario_p = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna porcen_comision sea igual al valor pasado como parámetro
     *
     * @param String $value porcen_comision requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByPorcenComision($value){
		$sql = 'DELETE FROM configuracion WHERE porcen_comision = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna porcen_comision2 sea igual al valor pasado como parámetro
     *
     * @param String $value porcen_comision2 requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByPorcenComision2($value){
		$sql = 'DELETE FROM configuracion WHERE porcen_comision2 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna valor_cupo sea igual al valor pasado como parámetro
     *
     * @param String $value valor_cupo requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByValorCupo($value){
		$sql = 'DELETE FROM configuracion WHERE valor_cupo = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna valor_cupo2 sea igual al valor pasado como parámetro
     *
     * @param String $value valor_cupo2 requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByValorCupo2($value){
		$sql = 'DELETE FROM configuracion WHERE valor_cupo2 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna periodo_bodega sea igual al valor pasado como parámetro
     *
     * @param String $value periodo_bodega requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByPeriodoBodega($value){
		$sql = 'DELETE FROM configuracion WHERE periodo_bodega = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna proceso_recarga sea igual al valor pasado como parámetro
     *
     * @param String $value proceso_recarga requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByProcesoRecarga($value){
		$sql = 'DELETE FROM configuracion WHERE proceso_recarga = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna factura_texto1 sea igual al valor pasado como parámetro
     *
     * @param String $value factura_texto1 requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByFacturaTexto1($value){
		$sql = 'DELETE FROM configuracion WHERE factura_texto1 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna factura_texto2 sea igual al valor pasado como parámetro
     *
     * @param String $value factura_texto2 requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByFacturaTexto2($value){
		$sql = 'DELETE FROM configuracion WHERE factura_texto2 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna factura_texto3 sea igual al valor pasado como parámetro
     *
     * @param String $value factura_texto3 requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByFacturaTexto3($value){
		$sql = 'DELETE FROM configuracion WHERE factura_texto3 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna factura_texto4 sea igual al valor pasado como parámetro
     *
     * @param String $value factura_texto4 requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByFacturaTexto4($value){
		$sql = 'DELETE FROM configuracion WHERE factura_texto4 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna factura_texto5 sea igual al valor pasado como parámetro
     *
     * @param String $value factura_texto5 requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByFacturaTexto5($value){
		$sql = 'DELETE FROM configuracion WHERE factura_texto5 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna factura_texto6 sea igual al valor pasado como parámetro
     *
     * @param String $value factura_texto6 requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByFacturaTexto6($value){
		$sql = 'DELETE FROM configuracion WHERE factura_texto6 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna factura_texto7 sea igual al valor pasado como parámetro
     *
     * @param String $value factura_texto7 requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByFacturaTexto7($value){
		$sql = 'DELETE FROM configuracion WHERE factura_texto7 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna pedir_anexo_doc sea igual al valor pasado como parámetro
     *
     * @param String $value pedir_anexo_doc requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByPedirAnexoDoc($value){
		$sql = 'DELETE FROM configuracion WHERE pedir_anexo_doc = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna contingencia_itainment sea igual al valor pasado como parámetro
     *
     * @param String $value contingencia_itainment requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByContingenciaItainment($value){
		$sql = 'DELETE FROM configuracion WHERE contingencia_itainment = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna porcen_iva sea igual al valor pasado como parámetro
     *
     * @param String $value porcen_iva requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByPorcenIva($value){
		$sql = 'DELETE FROM configuracion WHERE porcen_iva = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna valor_iva sea igual al valor pasado como parámetro
     *
     * @param String $value valor_iva requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByValorIva($value){
		$sql = 'DELETE FROM configuracion WHERE valor_iva = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}







	
    /**
     * Crear y devolver un objeto del tipo Coonfiguracion
     * con los valores de una consulta sql
     * 
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Object $Coonfiguracion Coonfiguracion
     *
     * @access protected
     *
     */
	protected function readRow($row){
		$configuracion = new Configuracion();
		
		$configuracion->configId = $row['config_id'];
		$configuracion->limiteLineas = $row['limite_lineas'];
		$configuracion->ticketPie1 = $row['ticket_pie1'];
		$configuracion->ticketPie2 = $row['ticket_pie2'];
		$configuracion->ticketPie3 = $row['ticket_pie3'];
		$configuracion->ticketPie4 = $row['ticket_pie4'];
		$configuracion->ticketPie5 = $row['ticket_pie5'];
		$configuracion->diasExpira = $row['dias_expira'];
		$configuracion->email1 = $row['email1'];
		$configuracion->email2 = $row['email2'];
		$configuracion->email3 = $row['email3'];
		$configuracion->accesoPublico = $row['acceso_publico'];
		$configuracion->tiempoRotaProg = $row['tiempo_rota_prog'];
		$configuracion->limiteLogro = $row['limite_logro'];
		$configuracion->premioMax = $row['premio_max'];
		$configuracion->listaId = $row['lista_id'];
		$configuracion->regaloRegistro = $row['regalo_registro'];
		$configuracion->resultadoDias = $row['resultado_dias'];
		$configuracion->tiempoRotaEtiqueta = $row['tiempo_rota_etiqueta'];
		$configuracion->intercalaEtiqueta1 = $row['intercala_etiqueta_1'];
		$configuracion->intercalaEtiqueta2 = $row['intercala_etiqueta_2'];
		$configuracion->usulistabaseId = $row['usulistabase_id'];
		$configuracion->recargaPie1 = $row['recarga_pie1'];
		$configuracion->recargaPie2 = $row['recarga_pie2'];
		$configuracion->recargaPie3 = $row['recarga_pie3'];
		$configuracion->recargaPie4 = $row['recarga_pie4'];
		$configuracion->premioMax1 = $row['premio_max1'];
		$configuracion->premioMax2 = $row['premio_max2'];
		$configuracion->premioMax3 = $row['premio_max3'];
		$configuracion->valorDirecto = $row['valor_directo'];
		$configuracion->minCaduca = $row['min_caduca'];
		$configuracion->premioDirecto = $row['premio_directo'];
		$configuracion->porcenRegaloRecarga = $row['porcen_regalo_recarga'];
		$configuracion->mandante = $row['mandante'];
		$configuracion->valorEvento = $row['valor_evento'];
		$configuracion->valorDiario = $row['valor_diario'];
		$configuracion->premioMaxP = $row['premio_max_p'];
		$configuracion->premioMax1P = $row['premio_max1_p'];
		$configuracion->premioMax2P = $row['premio_max2_p'];
		$configuracion->premioMax3P = $row['premio_max3_p'];
		$configuracion->limiteLineasP = $row['limite_lineas_p'];
		$configuracion->valorDirectoP = $row['valor_directo_p'];
		$configuracion->valorEventoP = $row['valor_evento_p'];
		$configuracion->valorDiarioP = $row['valor_diario_p'];
		$configuracion->porcenComision = $row['porcen_comision'];
		$configuracion->porcenComision2 = $row['porcen_comision2'];
		$configuracion->valorCupo = $row['valor_cupo'];
		$configuracion->valorCupo2 = $row['valor_cupo2'];
		$configuracion->periodoBodega = $row['periodo_bodega'];
		$configuracion->procesoRecarga = $row['proceso_recarga'];
		$configuracion->facturaTexto1 = $row['factura_texto1'];
		$configuracion->facturaTexto2 = $row['factura_texto2'];
		$configuracion->facturaTexto3 = $row['factura_texto3'];
		$configuracion->facturaTexto4 = $row['factura_texto4'];
		$configuracion->facturaTexto5 = $row['factura_texto5'];
		$configuracion->facturaTexto6 = $row['factura_texto6'];
		$configuracion->facturaTexto7 = $row['factura_texto7'];
		$configuracion->pedirAnexoDoc = $row['pedir_anexo_doc'];
		$configuracion->contingenciaItainment = $row['contingencia_itainment'];
		$configuracion->porcenIva = $row['porcen_iva'];
		$configuracion->valorIva = $row['valor_iva'];

		return $configuracion;
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