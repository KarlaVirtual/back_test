<?php namespace Backend\dao;
/**
 * Interfaz para el modelo o tabla 'UsuarioMensaje'.
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface UsuarioMensajeDAO
{

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
     * @param String $usumensaje_id llave primaria
     */
    public function delete($usumensaje_id);
    
    /**
     * Insertar un registro en la base de datos
     *
     * @param String UsuarioMensaje UsuarioMensaje
     */
    public function insert($UsuarioMensaje);

    /**
     * Editar un registro en la base de datos
     *
     * @param Object UsuarioMensaje UsuarioMensaje
     */
    public function update($UsuarioMensaje);

    /**
     * Eliminar todos los registros de la base de datos
     */
    public function clean();





    /**
     * Obtener todos los registros donde se encuentre que
     * la columna UsufromId sea igual al valor pasado como parámetro
     *
     * @param String $value UsufromId requerido
     */ 
    public function queryByUsufromId($value);

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna UsutoId sea igual al valor pasado como parámetro
     *
     * @param String $value UsutoId requerido
     */ 
    public function queryByUsutoId($value);

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna IsRead sea igual al valor pasado como parámetro
     *
     * @param String $value IsRead requerido
     */ 
    public function queryByIsRead($value);

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna FechaCrea sea igual al valor pasado como parámetro
     *
     * @param String $value FechaCrea requerido
     */ 
    public function queryByFechaCrea($value);

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna UsucreaId sea igual al valor pasado como parámetro
     *
     * @param String $value UsucreaId requerido
     */ 
    public function queryByUsucreaId($value);

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna FechaModif sea igual al valor pasado como parámetro
     *
     * @param String $value FechaModif requerido
     */ 
    public function queryByFechaModif($value);

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna UsumodifId sea igual al valor pasado como parámetro
     *
     * @param String $value UsumodifId requerido
     */ 
    public function queryByUsumodifId($value);





    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna UsufromId sea igual al valor pasado como parámetro
     *
     * @param String $value UsufromId requerido
     */ 
    public function deleteByUsufromId($value);

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna UsutoId sea igual al valor pasado como parámetro
     *
     * @param String $value UsutoId requerido
     */ 
    public function deleteByUsutoId($value);

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna IsRead sea igual al valor pasado como parámetro
     *
     * @param String $value IsRead requerido
     */ 
    public function deleteByIsRead($value);

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna FechaCrea sea igual al valor pasado como parámetro
     *
     * @param String $value FechaCrea requerido
     */ 
    public function deleteByFechaCrea($value);

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna UsucreaId sea igual al valor pasado como parámetro
     *
     * @param String $value UsucreaId requerido
     */ 
    public function deleteByUsucreaId($value);

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna FechaModif sea igual al valor pasado como parámetro
     *
     * @param String $value FechaModif requerido
     */ 
    public function deleteByFechaModif($value);

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna UsumodifId sea igual al valor pasado como parámetro
     *
     * @param String $value UsumodifId requerido
     */ 
    public function deleteByUsumodifId($value);

}
