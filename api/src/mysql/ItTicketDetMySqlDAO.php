<?php namespace Backend\mysql;

use Backend\dao\ItTicketDetDAO;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\ItTicketDet;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Exception;

/**
* Clase 'ItTicketDetMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'ItTicketDet'
* 
* Ejemplo de uso: 
* $ItTicketDetMySqlDAO = new ItTicketDetMySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class ItTicketDetMySqlDAO {


    /**
     * Atributo Transaction transacción
     *
     * @var object
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
     * @return Array resultado de la consulta
     *
     */
	public function load($id){
		$sql = 'SELECT * FROM it_ticket_det WHERE it_ticketdet_id = ?';
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
		$sql = 'SELECT * FROM it_ticket_det';
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
		$sql = 'SELECT * FROM it_ticket_det ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $it_ticketdet_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function delete($it_ticketdet_id){
		$sql = 'DELETE FROM it_ticket_det WHERE it_ticketdet_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($it_ticketdet_id);
		return $this->executeUpdate($sqlQuery);
	}
	
    /**
     * Insertar un registro en la base de datos
     *
     * @param Object itTicketDet itTicketDet
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($itTicketDet)
    {

        $sql = 'INSERT INTO it_ticket_det ( ticket_id, apuesta, agrupador, logro, opcion, mandante, apuesta_id, agrupador_id, fecha_evento, hora_evento, ligaid, sportid, matchid) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';

        $sqlQuery = new SqlQuery($sql);

        $sqlQuery->set($itTicketDet->ticketId);


        $replace = [
            '&lt;' => '', '&gt;' => '', '&#039;' => '', '&amp;' => '',
            '&quot;' => '', 'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'Ae',
            '&Auml;' => 'A', 'Å' => 'A', 'Ā' => 'A', 'Ą' => 'A', 'Ă' => 'A', 'Æ' => 'Ae',
            'Ç' => 'C', 'Ć' => 'C', 'Č' => 'C', 'Ĉ' => 'C', 'Ċ' => 'C', 'Ď' => 'D', 'Đ' => 'D',
            'Ð' => 'D', 'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ē' => 'E',
            'Ę' => 'E', 'Ě' => 'E', 'Ĕ' => 'E', 'Ė' => 'E', 'Ĝ' => 'G', 'Ğ' => 'G',
            'Ġ' => 'G', 'Ģ' => 'G', 'Ĥ' => 'H', 'Ħ' => 'H', 'Ì' => 'I', 'Í' => 'I',
            'Î' => 'I', 'Ï' => 'I', 'Ī' => 'I', 'Ĩ' => 'I', 'Ĭ' => 'I', 'Į' => 'I',
            'İ' => 'I', 'Ĳ' => 'IJ', 'Ĵ' => 'J', 'Ķ' => 'K', 'Ł' => 'L', 'Ľ' => 'L',
            'Ĺ' => 'L', 'Ļ' => 'L', 'Ŀ' => 'L', 'Ñ' => 'N', 'Ń' => 'N', 'Ň' => 'N',
            'Ņ' => 'N', 'Ŋ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O',
            'Ö' => 'Oe', '&Ouml;' => 'Oe', 'Ø' => 'O', 'Ō' => 'O', 'Ő' => 'O', 'Ŏ' => 'O',
            'Œ' => 'OE', 'Ŕ' => 'R', 'Ř' => 'R', 'Ŗ' => 'R', 'Ś' => 'S', 'Š' => 'S',
            'Ş' => 'S', 'Ŝ' => 'S', 'Ș' => 'S', 'Ť' => 'T', 'Ţ' => 'T', 'Ŧ' => 'T',
            'Ț' => 'T', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'Ue', 'Ū' => 'U',
            '&Uuml;' => 'Ue', 'Ů' => 'U', 'Ű' => 'U', 'Ŭ' => 'U', 'Ũ' => 'U', 'Ų' => 'U',
            'Ŵ' => 'W', 'Ý' => 'Y', 'Ŷ' => 'Y', 'Ÿ' => 'Y', 'Ź' => 'Z', 'Ž' => 'Z',
            'Ż' => 'Z', 'Þ' => 'T', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a',
            'ä' => 'ae', '&auml;' => 'ae', 'å' => 'a', 'ā' => 'a', 'ą' => 'a', 'ă' => 'a',
            'æ' => 'ae', 'ç' => 'c', 'ć' => 'c', 'č' => 'c', 'ĉ' => 'c', 'ċ' => 'c',
            'ď' => 'd', 'đ' => 'd', 'ð' => 'd', 'è' => 'e', 'é' => 'e', 'ê' => 'e',
            'ë' => 'e', 'ē' => 'e', 'ę' => 'e', 'ě' => 'e', 'ĕ' => 'e', 'ė' => 'e',
            'ƒ' => 'f', 'ĝ' => 'g', 'ğ' => 'g', 'ġ' => 'g', 'ģ' => 'g', 'ĥ' => 'h',
            'ħ' => 'h', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ī' => 'i',
            'ĩ' => 'i', 'ĭ' => 'i', 'į' => 'i', 'ı' => 'i', 'ĳ' => 'ij', 'ĵ' => 'j',
            'ķ' => 'k', 'ĸ' => 'k', 'ł' => 'l', 'ľ' => 'l', 'ĺ' => 'l', 'ļ' => 'l',
            'ŀ' => 'l', 'ñ' => 'n', 'ń' => 'n', 'ň' => 'n', 'ņ' => 'n', 'ŉ' => 'n',
            'ŋ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'oe',
            '&ouml;' => 'oe', 'ø' => 'o', 'ō' => 'o', 'ő' => 'o', 'ŏ' => 'o', 'œ' => 'oe',
            'ŕ' => 'r', 'ř' => 'r', 'ŗ' => 'r', 'š' => 's', 'ù' => 'u', 'ú' => 'u',
            'û' => 'u', 'ü' => 'ue', 'ū' => 'u', '&uuml;' => 'ue', 'ů' => 'u', 'ű' => 'u',
            'ŭ' => 'u', 'ũ' => 'u', 'ų' => 'u', 'ŵ' => 'w', 'ý' => 'y', 'ÿ' => 'y',
            'ŷ' => 'y', 'ž' => 'z', 'ż' => 'z', 'ź' => 'z', 'þ' => 't', 'ß' => 'ss',
            'ſ' => 'ss', 'ый' => 'iy', 'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G',
            'Д' => 'D', 'Е' => 'E', 'Ё' => 'YO', 'Ж' => 'ZH', 'З' => 'Z', 'И' => 'I',
            'Й' => 'Y', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
            'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F',
            'Х' => 'H', 'Ц' => 'C', 'Ч' => 'CH', 'Ш' => 'SH', 'Щ' => 'SCH', 'Ъ' => '',
            'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'YU', 'Я' => 'YA', 'а' => 'a',
            'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo',
            'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k', 'л' => 'l',
            'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's',
            'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch',
            'ш' => 'sh', 'щ' => 'sch', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e',
            'ю' => 'yu', 'я' => 'ya'
        ];
        $itTicketDet->apuesta=trim($itTicketDet->apuesta);
        $itTicketDet->apuesta = str_replace(array_keys($replace), $replace, $itTicketDet->apuesta);
        $itTicketDet->apuesta = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $itTicketDet->apuesta);

        $sqlQuery->set($itTicketDet->apuesta);

        $itTicketDet->agrupador=trim($itTicketDet->agrupador);
        $itTicketDet->agrupador = strtr($itTicketDet->agrupador, 'áéíóúÁÉÍÓÚ', 'aeiouAEIOU');

        if(strlen($itTicketDet->agrupador)>49){
            $itTicketDet->agrupador = trim(substr($itTicketDet->agrupador, 0, 48));

        }
        $itTicketDet->agrupador = str_replace(array_keys($replace), $replace, $itTicketDet->agrupador);
        $itTicketDet->agrupador = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $itTicketDet->agrupador);

        $sqlQuery->set($itTicketDet->agrupador);
        $sqlQuery->set($itTicketDet->logro);
        $sqlQuery->set($itTicketDet->opcion);
        $sqlQuery->set($itTicketDet->mandante);
        $sqlQuery->set($itTicketDet->apuestaId);
        $sqlQuery->set($itTicketDet->agrupadorId);


        if (strlen($itTicketDet->fechaEvento) > 10) {

            $fechaEvento = explode(' ', $itTicketDet->fechaEvento)[0];
            $itTicketDet->horaEvento = explode(' ', $itTicketDet->fechaEvento)[1];

        }


        $sqlQuery->set($fechaEvento);
		$sqlQuery->set($itTicketDet->horaEvento);
		$sqlQuery->set($itTicketDet->ligaid);
		$sqlQuery->set($itTicketDet->sportid);
		$sqlQuery->set($itTicketDet->matchid);






		$id = $this->executeInsert($sqlQuery);	
		$itTicketDet->itTicketdetId = $id;
		return $id;
	}
	
    /**
     * Editar un registro en la base de datos
     *
     * @param Object itTicketDet itTicketDet
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function update($itTicketDet){
		$sql = 'UPDATE it_ticket_det SET ticket_id = ?, apuesta = ?, agrupador = ?, logro = ?, opcion = ?, mandante = ?, apuesta_id = ?, agrupador_id = ?, fecha_evento = ?, hora_evento = ? WHERE it_ticketdet_id = ?';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($itTicketDet->ticketId);
		$sqlQuery->set($itTicketDet->apuesta);
		$sqlQuery->set($itTicketDet->agrupador);
		$sqlQuery->set($itTicketDet->logro);
		$sqlQuery->set($itTicketDet->opcion);
		$sqlQuery->set($itTicketDet->mandante);
		$sqlQuery->set($itTicketDet->apuestaId);
		$sqlQuery->set($itTicketDet->agrupadorId);
		$sqlQuery->set($itTicketDet->fechaEvento);
		$sqlQuery->set($itTicketDet->horaEvento);

		$sqlQuery->set($itTicketDet->itTicketdetId);
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
		$sql = 'DELETE FROM it_ticket_det';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}






    /**
     * Obtener todos los registros donde se encuentre que
     * la columna ticket_id sea igual al valor pasado como parámetro
     *
     * @param String $value ticket_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByTicketId($value){
		$sql = 'SELECT * FROM it_ticket_det WHERE ticket_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna apuesta sea igual al valor pasado como parámetro
     *
     * @param String $value apuesta requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByApuesta($value){
		$sql = 'SELECT * FROM it_ticket_det WHERE apuesta = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna agrupador sea igual al valor pasado como parámetro
     *
     * @param String $value agrupador requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByAgrupador($value){
		$sql = 'SELECT * FROM it_ticket_det WHERE agrupador = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna logro sea igual al valor pasado como parámetro
     *
     * @param String $value logro requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByLogro($value){
		$sql = 'SELECT * FROM it_ticket_det WHERE logro = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna opcion sea igual al valor pasado como parámetro
     *
     * @param String $value opcion requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByOpcion($value){
		$sql = 'SELECT * FROM it_ticket_det WHERE opcion = ?';
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
		$sql = 'SELECT * FROM it_ticket_det WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna apuesta_id sea igual al valor pasado como parámetro
     *
     * @param String $value apuesta_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByApuestaId($value){
		$sql = 'SELECT * FROM it_ticket_det WHERE apuesta_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna agrupador_id sea igual al valor pasado como parámetro
     *
     * @param String $value agrupador_id requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByAgrupadorId($value){
		$sql = 'SELECT * FROM it_ticket_det WHERE agrupador_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna fecha_evento sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_evento requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByFechaEvento($value){
		$sql = 'SELECT * FROM it_ticket_det WHERE fecha_evento = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna hora_evento sea igual al valor pasado como parámetro
     *
     * @param String $value hora_evento requerido
     *
     * @return Array resultado de la consulta
     *
     */
	public function queryByHoraEvento($value){
		$sql = 'SELECT * FROM it_ticket_det WHERE hora_evento = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}





    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna ticket_id sea igual al valor pasado como parámetro
     *
     * @param String $value ticket_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByTicketId($value){
		$sql = 'DELETE FROM it_ticket_det WHERE ticket_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna apuesta sea igual al valor pasado como parámetro
     *
     * @param String $value apuesta requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByApuesta($value){
		$sql = 'DELETE FROM it_ticket_det WHERE apuesta = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna agrupador sea igual al valor pasado como parámetro
     *
     * @param String $value agrupador requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByAgrupador($value){
		$sql = 'DELETE FROM it_ticket_det WHERE agrupador = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna logro sea igual al valor pasado como parámetro
     *
     * @param String $value logro requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByLogro($value){
		$sql = 'DELETE FROM it_ticket_det WHERE logro = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna opcion sea igual al valor pasado como parámetro
     *
     * @param String $value opcion requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByOpcion($value){
		$sql = 'DELETE FROM it_ticket_det WHERE opcion = ?';
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
		$sql = 'DELETE FROM it_ticket_det WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna apuesta_id sea igual al valor pasado como parámetro
     *
     * @param String $value apuesta_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByApuestaId($value){
		$sql = 'DELETE FROM it_ticket_det WHERE apuesta_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna agrupador_id sea igual al valor pasado como parámetro
     *
     * @param String $value agrupador_id requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByAgrupadorId($value){
		$sql = 'DELETE FROM it_ticket_det WHERE agrupador_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna fecha_evento sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_evento requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByFechaEvento($value){
		$sql = 'DELETE FROM it_ticket_det WHERE fecha_evento = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna hora_evento sea igual al valor pasado como parámetro
     *
     * @param String $value hora_evento requerido
     *
     * @return boolean resultado de la ejecución
     *
     */
	public function deleteByHoraEvento($value){
		$sql = 'DELETE FROM it_ticket_det WHERE hora_evento = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}


	




    /**
     * Crear y devolver un objeto del tipo ItTicketDet
     * con los valores de una consulta sql
     * 
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Object $ItTicketDet ItTicketDet
     *
     * @access protected
     *
     */
	protected function readRow($row){
		$itTicketDet = new ItTicketDet();
		
		$itTicketDet->itTicketdetId = $row['it_ticketdet_id'];
		$itTicketDet->ticketId = $row['ticket_id'];
		$itTicketDet->apuesta = $row['apuesta'];
		$itTicketDet->agrupador = $row['agrupador'];
		$itTicketDet->logro = $row['logro'];
		$itTicketDet->opcion = $row['opcion'];
		$itTicketDet->mandante = $row['mandante'];
		$itTicketDet->apuestaId = $row['apuesta_id'];
		$itTicketDet->agrupadorId = $row['agrupador_id'];
		$itTicketDet->fechaEvento = $row['fecha_evento'];
		$itTicketDet->horaEvento = $row['hora_evento'];

		return $itTicketDet;
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