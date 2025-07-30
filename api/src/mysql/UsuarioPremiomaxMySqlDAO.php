<?php namespace Backend\mysql;
use Backend\dao\UsuarioPremiomaxDAO;
use Backend\dto\UsuarioPremiomax;
use Backend\sql\Transaction;
use Backend\sql\SqlQuery;
use Backend\sql\QueryExecutor;
use Exception;
/** 
* Clase 'UsuarioPremiomaxMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'UsuarioPremiomaxM'
* 
* Ejemplo de uso: 
* $UsuarioPremiomaxMySqlDAO = new UsuarioPremiomaxMySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class UsuarioPremiomaxMySqlDAO implements UsuarioPremiomaxDAO
{


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
    public function __construct($transaction="")
    {
        if ($transaction == "") 
        {
            $transaction = new Transaction();
            $this->transaction = $transaction;
        }
        else 
        {
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
		$sql = 'SELECT * FROM usuario_premiomax WHERE premiomax_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($id);
		return $this->getRow($sqlQuery);
	}



    /**
     * Obtener todos los registros condicionados por el usuario_id
     *
     * @param String $id llave primaria
     *
     * @return Array $ resultado de la consulta
     *
     */
    public function loadbyUsuarioId($id)
    {
        $sql = 'SELECT * FROM usuario_premiomax WHERE usuario_id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->set($id);
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
	public function queryAll()
	{
		$sql = 'SELECT * FROM usuario_premiomax';
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
		$sql = 'SELECT * FROM usuario_premiomax ORDER BY ' . $orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}

    /**
     * Eliminar todos los registros condicionados
     * por la llave primaria
     *
     * @param String $premiomax_id llave primaria
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function delete($premiomax_id)
	{
		$sql = 'DELETE FROM usuario_premiomax WHERE premiomax_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($premiomax_id);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Insertar un registro en la base de datos
     *
     * @param Objeto usuarioPremiomax usuarioPremiomax
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($usuarioPremiomax)
	{
		$sql = 'INSERT INTO usuario_premiomax (usuario_id, premio_max, usumodif_id, fecha_modif, cant_lineas, premio_max1, premio_max2, premio_max3, apuesta_min, valor_directo, premio_directo, mandante, optimizar_parrilla, texto_op1, texto_op2, url_op2, texto_op3, url_op3, valor_evento, valor_diario) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);

		$sqlQuery->set($usuarioPremiomax->usuarioId);
		$sqlQuery->set($usuarioPremiomax->premioMax);
		$sqlQuery->set($usuarioPremiomax->usumodifId);
		$sqlQuery->set($usuarioPremiomax->fechaModif);
		$sqlQuery->set($usuarioPremiomax->cantLineas);
		$sqlQuery->set($usuarioPremiomax->premioMax1);
		$sqlQuery->set($usuarioPremiomax->premioMax2);
		$sqlQuery->set($usuarioPremiomax->premioMax3);
		$sqlQuery->set($usuarioPremiomax->apuestaMin);
		$sqlQuery->set($usuarioPremiomax->valorDirecto);
		$sqlQuery->set($usuarioPremiomax->premioDirecto);
		$sqlQuery->set($usuarioPremiomax->mandante);
		$sqlQuery->set($usuarioPremiomax->optimizarParrilla);
		$sqlQuery->set($usuarioPremiomax->textoOp1);
		$sqlQuery->set($usuarioPremiomax->textoOp2);
		$sqlQuery->set($usuarioPremiomax->urlOp2);
		$sqlQuery->set($usuarioPremiomax->textoOp3);
		$sqlQuery->set($usuarioPremiomax->urlOp3);
		$sqlQuery->set($usuarioPremiomax->valorEvento);
		$sqlQuery->set($usuarioPremiomax->valorDiario);

		$id = $this->executeInsert($sqlQuery);
		$usuarioPremiomax->premiomaxId = $id;
		return $id;
	}

    /**
     * Editar un registro en la base de datos
     *
     * @param Objeto usuarioPremiomax usuarioPremiomax
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function update($usuarioPremiomax)
	{
		$sql = 'UPDATE usuario_premiomax SET usuario_id = ?, premio_max = ?, usumodif_id = ?, fecha_modif = ?, cant_lineas = ?, premio_max1 = ?, premio_max2 = ?, premio_max3 = ?, apuesta_min = ?, valor_directo = ?, premio_directo = ?, mandante = ?, optimizar_parrilla = ?, texto_op1 = ?, texto_op2 = ?, url_op2 = ?, texto_op3 = ?, url_op3 = ?, valor_evento = ?, valor_diario = ? WHERE premiomax_id = ?';
		$sqlQuery = new SqlQuery($sql);

		$sqlQuery->set($usuarioPremiomax->usuarioId);
		$sqlQuery->set($usuarioPremiomax->premioMax);
		$sqlQuery->set($usuarioPremiomax->usumodifId);
		$sqlQuery->set($usuarioPremiomax->fechaModif);
		$sqlQuery->set($usuarioPremiomax->cantLineas);
		$sqlQuery->set($usuarioPremiomax->premioMax1);
		$sqlQuery->set($usuarioPremiomax->premioMax2);
		$sqlQuery->set($usuarioPremiomax->premioMax3);
		$sqlQuery->set($usuarioPremiomax->apuestaMin);
		$sqlQuery->set($usuarioPremiomax->valorDirecto);
		$sqlQuery->set($usuarioPremiomax->premioDirecto);
		$sqlQuery->set($usuarioPremiomax->mandante);
		$sqlQuery->set($usuarioPremiomax->optimizarParrilla);
		$sqlQuery->set($usuarioPremiomax->textoOp1);
		$sqlQuery->set($usuarioPremiomax->textoOp2);
		$sqlQuery->set($usuarioPremiomax->urlOp2);
		$sqlQuery->set($usuarioPremiomax->textoOp3);
		$sqlQuery->set($usuarioPremiomax->urlOp3);
		$sqlQuery->set($usuarioPremiomax->valorEvento);
		$sqlQuery->set($usuarioPremiomax->valorDiario);

		$sqlQuery->set($usuarioPremiomax->premiomaxId);
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
		$sql = 'DELETE FROM usuario_premiomax';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}











    /**
     * Obtener todos los registros donde se encuentre que
     * la columna usuario_id sea igual al valor pasado como parámetro
     *
     * @param String $value usuario_id requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByUsuarioId($value)
	{
		$sql = 'SELECT * FROM usuario_premiomax WHERE usuario_id = ?';
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
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByPremioMax($value)
	{
		$sql = 'SELECT * FROM usuario_premiomax WHERE premio_max = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna usumodif_id sea igual al valor pasado como parámetro
     *
     * @param String $value usumodif_id requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByUsumodifId($value)
	{
		$sql = 'SELECT * FROM usuario_premiomax WHERE usumodif_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna fecha_modif sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_modif requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByFechaModif($value)
	{
		$sql = 'SELECT * FROM usuario_premiomax WHERE fecha_modif = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna cant_lineas sea igual al valor pasado como parámetro
     *
     * @param String $value cant_lineas requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByCantLineas($value)
	{
		$sql = 'SELECT * FROM usuario_premiomax WHERE cant_lineas = ?';
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
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByPremioMax1($value)
	{
		$sql = 'SELECT * FROM usuario_premiomax WHERE premio_max1 = ?';
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
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByPremioMax2($value)
	{
		$sql = 'SELECT * FROM usuario_premiomax WHERE premio_max2 = ?';
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
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByPremioMax3($value)
	{
		$sql = 'SELECT * FROM usuario_premiomax WHERE premio_max3 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna apuesta_min sea igual al valor pasado como parámetro
     *
     * @param String $value apuesta_min requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByApuestaMin($value)
	{
		$sql = 'SELECT * FROM usuario_premiomax WHERE apuesta_min = ?';
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
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByValorDirecto($value)
	{
		$sql = 'SELECT * FROM usuario_premiomax WHERE valor_directo = ?';
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
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByPremioDirecto($value)
	{
		$sql = 'SELECT * FROM usuario_premiomax WHERE premio_directo = ?';
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
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByMandante($value)
	{
		$sql = 'SELECT * FROM usuario_premiomax WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna optimizar_parrilla sea igual al valor pasado como parámetro
     *
     * @param String $value optimizar_parrilla requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByOptimizarParrilla($value)
	{
		$sql = 'SELECT * FROM usuario_premiomax WHERE optimizar_parrilla = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna texto_op1 sea igual al valor pasado como parámetro
     *
     * @param String $value texto_op1 requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByTextoOp1($value)
	{
		$sql = 'SELECT * FROM usuario_premiomax WHERE texto_op1 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna texto_op2 sea igual al valor pasado como parámetro
     *
     * @param String $value texto_op2 requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByTextoOp2($value)
	{
		$sql = 'SELECT * FROM usuario_premiomax WHERE texto_op2 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna url_op2 sea igual al valor pasado como parámetro
     *
     * @param String $value url_op2 requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByUrlOp2($value)
	{
		$sql = 'SELECT * FROM usuario_premiomax WHERE url_op2 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna texto_op3 sea igual al valor pasado como parámetro
     *
     * @param String $value texto_op3 requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByTextoOp3($value)
	{
		$sql = 'SELECT * FROM usuario_premiomax WHERE texto_op3 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna url_op3 sea igual al valor pasado como parámetro
     *
     * @param String $value url_op3 requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByUrlOp3($value)
	{
		$sql = 'SELECT * FROM usuario_premiomax WHERE url_op3 = ?';
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
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByValorEvento($value)
	{
		$sql = 'SELECT * FROM usuario_premiomax WHERE valor_evento = ?';
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
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByValorDiario($value)
	{
		$sql = 'SELECT * FROM usuario_premiomax WHERE valor_diario = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}













    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna usuario_id sea igual al valor pasado como parámetro
     *
     * @param String $value usuario_id requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByUsuarioId($value)
	{
		$sql = 'DELETE FROM usuario_premiomax WHERE usuario_id = ?';
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
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByPremioMax($value)
	{
		$sql = 'DELETE FROM usuario_premiomax WHERE premio_max = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna usumodif_id sea igual al valor pasado como parámetro
     *
     * @param String $value usumodif_id requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByUsumodifId($value)
	{
		$sql = 'DELETE FROM usuario_premiomax WHERE usumodif_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna fecha_modif sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_modif requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByFechaModif($value)
	{
		$sql = 'DELETE FROM usuario_premiomax WHERE fecha_modif = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna cant_lineas sea igual al valor pasado como parámetro
     *
     * @param String $value cant_lineas requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByCantLineas($value)
	{
		$sql = 'DELETE FROM usuario_premiomax WHERE cant_lineas = ?';
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
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByPremioMax1($value)
	{
		$sql = 'DELETE FROM usuario_premiomax WHERE premio_max1 = ?';
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
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByPremioMax2($value)
	{
		$sql = 'DELETE FROM usuario_premiomax WHERE premio_max2 = ?';
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
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByPremioMax3($value)
	{
		$sql = 'DELETE FROM usuario_premiomax WHERE premio_max3 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna apuesta_min sea igual al valor pasado como parámetro
     *
     * @param String $value apuesta_min requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByApuestaMin($value)
	{
		$sql = 'DELETE FROM usuario_premiomax WHERE apuesta_min = ?';
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
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByValorDirecto($value)
	{
		$sql = 'DELETE FROM usuario_premiomax WHERE valor_directo = ?';
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
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByPremioDirecto($value)
	{
		$sql = 'DELETE FROM usuario_premiomax WHERE premio_directo = ?';
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
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByMandante($value)
	{
		$sql = 'DELETE FROM usuario_premiomax WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna optimizar_parrilla sea igual al valor pasado como parámetro
     *
     * @param String $value optimizar_parrilla requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByOptimizarParrilla($value)
	{
		$sql = 'DELETE FROM usuario_premiomax WHERE optimizar_parrilla = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna texto_op1 sea igual al valor pasado como parámetro
     *
     * @param String $value texto_op1 requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByTextoOp1($value)
	{
		$sql = 'DELETE FROM usuario_premiomax WHERE texto_op1 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna texto_op2 sea igual al valor pasado como parámetro
     *
     * @param String $value texto_op2 requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByTextoOp2($value)
	{
		$sql = 'DELETE FROM usuario_premiomax WHERE texto_op2 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna url_op2 sea igual al valor pasado como parámetro
     *
     * @param String $value url_op2 requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByUrlOp2($value)
	{
		$sql = 'DELETE FROM usuario_premiomax WHERE url_op2 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna texto_op3 sea igual al valor pasado como parámetro
     *
     * @param String $value texto_op3 requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByTextoOp3($value)
	{
		$sql = 'DELETE FROM usuario_premiomax WHERE texto_op3 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna url_op3 sea igual al valor pasado como parámetro
     *
     * @param String $value apellidos requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByUrlOp3($value)
	{
		$sql = 'DELETE FROM usuario_premiomax WHERE url_op3 = ?';
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
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByValorEvento($value)
	{
		$sql = 'DELETE FROM usuario_premiomax WHERE valor_evento = ?';
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
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByValorDiario($value)
	{
		$sql = 'DELETE FROM usuario_premiomax WHERE valor_diario = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}











    /**
     * Crear y devolver un objeto del tipo UsuarioPremiomax
     * con los valores de una consulta sql
     * 
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Objeto $usuarioPremiomax UsuarioPremiomax
     *
     * @access protected
     *
     */
	protected function readRow($row)
	{
		$usuarioPremiomax = new UsuarioPremiomax();

		$usuarioPremiomax->premiomaxId = $row['premiomax_id'];
		$usuarioPremiomax->usuarioId = $row['usuario_id'];
		$usuarioPremiomax->premioMax = $row['premio_max'];
		$usuarioPremiomax->usumodifId = $row['usumodif_id'];
		$usuarioPremiomax->fechaModif = $row['fecha_modif'];
		$usuarioPremiomax->cantLineas = $row['cant_lineas'];
		$usuarioPremiomax->premioMax1 = $row['premio_max1'];
		$usuarioPremiomax->premioMax2 = $row['premio_max2'];
		$usuarioPremiomax->premioMax3 = $row['premio_max3'];
		$usuarioPremiomax->apuestaMin = $row['apuesta_min'];
		$usuarioPremiomax->valorDirecto = $row['valor_directo'];
		$usuarioPremiomax->premioDirecto = $row['premio_directo'];
		$usuarioPremiomax->mandante = $row['mandante'];
		$usuarioPremiomax->optimizarParrilla = $row['optimizar_parrilla'];
		$usuarioPremiomax->textoOp1 = $row['texto_op1'];
		$usuarioPremiomax->textoOp2 = $row['texto_op2'];
		$usuarioPremiomax->urlOp2 = $row['url_op2'];
		$usuarioPremiomax->textoOp3 = $row['texto_op3'];
		$usuarioPremiomax->urlOp3 = $row['url_op3'];
		$usuarioPremiomax->valorEvento = $row['valor_evento'];
		$usuarioPremiomax->valorDiario = $row['valor_diario'];

		return $usuarioPremiomax;
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