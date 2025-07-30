<?php namespace Backend\dao;
/**
 * Interfaz para el modelo o tabla 'Itainment'.
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface MandanteDAO{

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
     * @param String $mandante llave primaria
     */
    public function delete($mandante);

    /**
     * Insertar un registro en la base de datos
     *
     * @param String mandante mandante
     */
    public function insert($mandante);

    /**
     * Editar un registro en la base de datos
     *
     * @param Object mandante mandante
     */
    public function update($mandante);

    /**
     * Eliminar todos los registros de la base de datos
     */
    public function clean();






    /**
     * Obtener todos los registros donde se encuentre que
     * la columna Descripcion sea igual al valor pasado como parámetro
     *
     * @param String $value Descripcion requerido
     */ 
    public function queryByDescripcion($value);

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna Contacto sea igual al valor pasado como parámetro
     *
     * @param String $value Contacto requerido
     */ 
    public function queryByContacto($value);

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna Email sea igual al valor pasado como parámetro
     *
     * @param String $value Email requerido
     */ 
    public function queryByEmail($value);

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna Telefono sea igual al valor pasado como parámetro
     *
     * @param String $value Telefono requerido
     */ 
    public function queryByTelefono($value);

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna Nit sea igual al valor pasado como parámetro
     *
     * @param String $value Nit requerido
     */ 
    public function queryByNit($value);

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna Legal1 sea igual al valor pasado como parámetro
     *
     * @param String $value Legal1 requerido
     */ 
    public function queryByLegal1($value);

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna Legal2 sea igual al valor pasado como parámetro
     *
     * @param String $value Legal2 requerido
     */ 
    public function queryByLegal2($value);

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna Legal3 sea igual al valor pasado como parámetro
     *
     * @param String $value Legal3 requerido
     */ 
    public function queryByLegal3($value);

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna Legal4 sea igual al valor pasado como parámetro
     *
     * @param String $value Legal4 requerido
     */ 
    public function queryByLegal4($value);

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna Propio sea igual al valor pasado como parámetro
     *
     * @param String $value Propio requerido
     */ 
    public function queryByPropio($value);





    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna Descripcion sea igual al valor pasado como parámetro
     *
     * @param String $value Descripcion requerido
     */ 
    public function deleteByDescripcion($value);

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna Contacto sea igual al valor pasado como parámetro
     *
     * @param String $value Contacto requerido
     */ 
    public function deleteByContacto($value);

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna Email sea igual al valor pasado como parámetro
     *
     * @param String $value Email requerido
     */ 
    public function deleteByEmail($value);

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna Telefono sea igual al valor pasado como parámetro
     *
     * @param String $value Telefono requerido
     */ 
    public function deleteByTelefono($value);

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna Nit sea igual al valor pasado como parámetro
     *
     * @param String $value Nit requerido
     */ 
    public function deleteByNit($value);

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna Legal1 sea igual al valor pasado como parámetro
     *
     * @param String $value Legal1 requerido
     */ 
    public function deleteByLegal1($value);

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna Legal2 sea igual al valor pasado como parámetro
     *
     * @param String $value Legal2 requerido
     */ 
    public function deleteByLegal2($value);

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna Legal3 sea igual al valor pasado como parámetro
     *
     * @param String $value Legal3 requerido
     */ 
    public function deleteByLegal3($value);

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna Legal4 sea igual al valor pasado como parámetro
     *
     * @param String $value Legal4 requerido
     */ 
    public function deleteByLegal4($value);

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna Propio sea igual al valor pasado como parámetro
     *
     * @param String $value Propio requerido
     */ 
    public function deleteByPropio($value);


}
?>